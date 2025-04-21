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

$res = "";

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $res = $res . "<div class='div_pozadikomplex' style='width: auto; min-width: 200px;'>";
  $res = $res . "<div id='movedivSeznam' class='movediv'>";
  $res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
  $res = $res . "</div>";
  $res = $res . "<table id='tablejrSeznam' class = 'tablejr'>";

//------- LINKY ------

  $sql = "SELECT distinct zastavky.c_zastavky, (case when zastavky.nazev = '' then zastavky.zkratka else zastavky.nazev end), zastavky.loca, zastavky.locb from zastavky left outer join zaslinky on (zastavky.c_zastavky = zaslinky.c_zastavky and zastavky.idlocation = zaslinky.idlocation
and zastavky.packet = zaslinky.packet) where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1  order by zastavky.c_zastavky";
//FROM zastavky where idlocation = " . $location . " and packet = " . $packet . " order by c_zastavky";

  $result = mysql_query($sql);

  while ($rowzastavky = mysql_fetch_row($result)) {
    $res = $res . "<tr class='licha'>";

    $res = $res . "<td style='text-align: left; width: auto;'>";

    $zast = "\"" . $rowzastavky[1] . (($location == 17) ? iconv('ISO-8859-2', 'UTF-8', ", Plzeò") : (($location == 11) ? iconv('ISO-8859-2', 'UTF-8', ", Opava") : (($location == 5) ? iconv('ISO-8859-2', 'UTF-8', ", Tøebíè") : ""))) . "\"";
    switch ($location) {
      case 17: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='selfobj.map(" . $zast . ", " . (($rowzastavky[2] == '') ? 'null': $rowzastavky[2]) . ", " . (($rowzastavky[3] == '') ? 'null': $rowzastavky[3]) . ", " . $rowzastavky[0] . ");'>&nbsp;</img>";
          break;
        }
      case 1: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='selfobj.map(" . $zast . ", " . (($rowzastavky[2] == '') ? 'null': $rowzastavky[2]) . ", " . (($rowzastavky[3] == '') ? 'null': $rowzastavky[3]) . ", " . $rowzastavky[0] . ");'>&nbsp;</img>";
          break;
        }
      case 11: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='selfobj.map(" . $zast . ", " . (($rowzastavky[2] == '') ? 'null': $rowzastavky[2]) . ", " . (($rowzastavky[3] == '') ? 'null': $rowzastavky[3]) . ", " . $rowzastavky[0] . ");'>&nbsp;</img>";
          break;
        }
      default: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='selfobj.map(" . $zast . ", " . (($rowzastavky[2] == '') ? 'null': $rowzastavky[2]) . ", " . (($rowzastavky[3] == '') ? 'null': $rowzastavky[3]) . ", " . $rowzastavky[0] . ");'>&nbsp;</img>";
          break;
        }
    }

    $res = $res . $rowzastavky[1];
/*    $res = $res . "<a class = 'a_nazev_linky1'>";
    $res = $res . $rowlinky[1];
    $res = $res . "</a>";
          if ($rowlinky[0] == 'T') {
        $res = $res . "<div style='float: right;' class = 'div_logo_doprava_T'></div>";
      }
      if ($rowlinky[0] == 'O') {
        $res = $res . "<div style='float: right;' class = 'div_logo_doprava_O'></div>";
      }
      if ($rowlinky[0] == 'A') {
        $res = $res . "<div style='float: right;' class = 'div_logo_doprava_A'></div>";
      }*/

    $res = $res . "</td>";

    $res = $res . "<td style='display: inline; width: auto;'>";

    $sql1 = "select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,
            (select azaslinky.c_tarif from zaslinky azaslinky where azaslinky.c_linky = linky.c_linky and azaslinky.idlocation = " . $location .
              " and azaslinky.packet = " . $packet . " and azaslinky.c_zastavky = zaslinky.c_zastavky and azaslinky.voz = 1 order by c_tarif LIMIT 1) as c_tarif, doprava
            from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and
            zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet)
            where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_zastavky = " . $rowzastavky[0] . " and zaslinky.voz = 1
            order by linky.c_linkysort";

    $result1 = mysql_query($sql1);

    $poradi = 0;

    while ($rowlinky = mysql_fetch_row($result1)) {
//      $res = $res . "<td style='display: inline-block; width: auto;'>";
/*      if ($poradi+1 % 6 == 1) {
        $res = $res . "<div class='a_linka' style='clear:both;'>";
      } else {
        $res = $res . "<div class='a_linka' style='float: left;'>";
      }*/
//      $res = $res . "<span class='a_linka'>";
      if ($poradi % 6 != 0) {
        $res = $res . " , ";
      }

      $res = $res . "<a class='a_linka' onClick = '" . "selfobj.changeZIndexJR(); getJR(\"" . $rowlinky[2] . "\", 0, " . (integer) $rowlinky[4] . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>"
                  . (($rowlinky[5] == 'T') ? "<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>":
                    (($rowlinky[5] == 'O') ? "<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>":
                    (($rowlinky[5] == 'A') ? "<img class='a_linka' src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>": "")))
                  . $rowlinky[0] /*. " (" . $rowlinky[4] . ") "*/ . "</a>";
      $poradi++;
//      $res = $res . "</td>";
//      $res = $res . "</span>";
      if ($poradi % 6 == 0) {
        $res = $res . "<br>";
      }
    }

    $res = $res . "</td>";

    $res = $res . "</tr>";
  }
}
mysql_close($p);

$res = $res . "</table>";
$res = $res . "</div>";

echo $_GET['callback'] . "(" . json_encode($res) . ");";
?>
