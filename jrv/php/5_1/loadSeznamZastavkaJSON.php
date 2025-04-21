<?php

$location = $_GET['location'];

$packet = $_GET['packet'];

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

$idzastavka = $_GET['idzastavka'];

$res = "";

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

//  $res = $res . "<table id='tablejrSeznam' class = 'tablejr'>";

//------- LINKY ------

    $sql1 = "select * from (select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,
            (select azaslinky.c_tarif from zaslinky azaslinky where azaslinky.c_linky = linky.c_linky and azaslinky.idlocation = " . $location .
              " and azaslinky.packet = " . $packet . " and azaslinky.c_zastavky = zaslinky.c_zastavky and azaslinky.voz = 1 and (azaslinky.zast_a = 1 or azaslinky.zast_b = 1) order by c_tarif LIMIT 1) as c_tarif, doprava
            from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and
            zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet)
            where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_zastavky = " . $idzastavka . "
            order by linky.c_linkysort) sezn where c_tarif is not null";

    $result1 = mysql_query($sql1);

    $poradi = 0;

    $res = $res . "<table class='t_seznam_zastavka'>";
    while ($rowlinky = mysql_fetch_row($result1)) {

/*      $res = $res . "<tr class='licha'>";

      $res = $res . "<td style='display: inline; width: auto;'>"; */
//      $res = $res . "<p>";

/*      if ($poradi % 6 != 0) {
        $res = $res . " , ";
      }*/

/*      $res = $res . "<a class='a_linka' style='height: 25px;' onClick = '" . "selfobj.changeZIndexJR(); getJR(" . $rowlinky[2] . ", 0, " . (integer) $rowlinky[4] . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>  "
                  . (($rowlinky[5] == 'T') ? "&nbsp;<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>":
                    (($rowlinky[5] == 'O') ? "&nbsp;<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>":
                    (($rowlinky[5] == 'A') ? "&nbsp;<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>": "")))
                  . $rowlinky[0] . "</a>";*/
      $res = $res . "<tr><td>";
      $res = $res . "<div class='a_linka' style='vertical-align: middle; width: auto; display: inline-block;' onClick = '" . "selfobj.changeZIndexJR(); getJR(" . $rowlinky[2] . ", 0, " . (integer) $rowlinky[4] . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>  "
                . (($rowlinky[5] == 'T') ? "&nbsp;<img style='vertical-align: middle;' class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>":
                  (($rowlinky[5] == 'O') ? "&nbsp;<img style='vertical-align: middle;' class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>":
                  (($rowlinky[5] == 'A') ? "&nbsp;<img style='vertical-align: middle;' class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>": "")))
                . "<a class='a_linka' style='vertical-align: middle; margin-left: 10px;'>" . $rowlinky[0] . "</a>"
                . "</div>";
      $res = $res . "</td></tr>";
/*      $poradi++;
      if ($poradi % 6 == 0) {
        $res = $res . "<br>";
      }*/


//      $res = $res . "</p>";

      /*$res = $res . "</td>";

      $res = $res . "</tr>";*/
    }
    $res = $res . "</table>";
}
mysql_close($p);

$res = $res . "</table>";
//$res = $res . "</div>";

if (isset ($_GET['callback'])) {
  echo $_GET['callback'] . "(" . json_encode($res) . ");";
} else {
  echo json_encode($res);
}
?>
