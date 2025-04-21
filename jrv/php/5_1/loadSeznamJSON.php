<?php
error_reporting(0);
require_once '../../lib/param.php';
require_once '../../lib/functions.php';

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

if ($location == 6) {


if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

  class TTrasa {

  var $c_tarif = null;
  var $nazev_zastavky = '';
  var $doprava = '';
  var $nazev_linky = '';
  var $stavi = 0;

}

  $zastavky[] = null;

  $sql = "select linky.NAZEV_LINKY, linky.DOPRAVA, lzastavky.* from lzastavky left outer join linky on (linky.c_linky = lzastavky.c_linky and linky.IDLOCATION = lzastavky.idlocation and linky.PACKET = lzastavky.packet) where lzastavky.idlocation = " . $location . " and lzastavky.packet = " . $packet;
  //echo $sql;
  $result = mysql_query($sql);

  $poradi = 0;
  while ($rowlinky = mysql_fetch_row($result)) {
    $pTrasa = new TTrasa();
    $pTrasa->nazev_linky = $rowlinky[0];
    $pTrasa->doprava = $rowlinky[1];
    $pTrasa->c_tarif = $rowlinky[3];
    $pTrasa->nazev_zastavky = $rowlinky[4];
    $pTrasa->stavi = $rowlinky[6];
    $zastavky[$rowlinky[2]][$rowlinky[5]][] = $pTrasa;
//    echo $zastavky[$rowlinky[2]][$rowlinky[5]][$poradi]->nazev_zastavky . "!" . $rowlinky[5] . "$";
    $poradi++;
  }

  /*$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
  $res = $res . "<div id='movedivSeznam' class='movediv'>";
  $res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
  $res = $res . "</div>";*/
  $res = $res . "<table id='tablejrSeznam' class = 'tableseznamlinky'>"; //style='max-width:500px; width: 500px;'

//------- LINKY ------

  $sql = "SELECT c_linky, nazev_linky, doprava FROM linky where idlocation = " . $location . " and packet = " . $packet . " order by c_linkysort";

  $result = mysql_query($sql);

  while ($rowlinky = mysql_fetch_row($result)) {
    $res = $res . "<tr class='licha'>";

    $res = $res . "<td rowspan='2' class='td_linka'>"; //style='text-align: left; width: auto;'
    $res = $res . "<span class = 'a_nazev_linky1'>";
    $res = $res . $rowlinky[1];
    $res = $res . "</span><br/>";
      if ($rowlinky[2] == 'T') {
        $res = $res . "<div class = 'div_logo_doprava_T'></div>"; //style='float: left;'
      }
      if ($rowlinky[2] == 'O') {
        $res = $res . "<div class = 'div_logo_doprava_O'></div>"; //style='float: left;'
      }
      if ($rowlinky[2] == 'A') {
        $res = $res . "<div class = 'div_logo_doprava_A'></div>"; //style='float: left;'
      }

    $res = $res . "</td>";

    $res = $res . "<td class='td_smer'>"; //style='font-size: 16px; font-weight: bold; padding-left: 10px; padding-right: 10px;'
    $res = $res . "A";
    $res = $res . "</td>";

    $res = $res . "<td class='td_zastavky'>"; //style='padding: 5px 5px 5px 5px; white-space: normal;'

    $smer = 0;

    $poradi = 0;
//    echo $rowlinky[0] . "|" . count($zastavky[$rowlinky[0]][0]) . "..";
    for($i = 0; $i < count($zastavky[$rowlinky[0]][0]); $i++) {
//      if ($rowZastavkaA[2] == 0) {
//        $res = $res . (($poradi == 0) ? "" : " - ");
        $nazev = Str_Replace (" ", "&nbsp;", $zastavky[$rowlinky[0]][0][$i]->nazev_zastavky);
//        echo $nazev . "*";
        if ($zastavky[$rowlinky[0]][0][$i]->stavi != 0) {
          $res = $res . "|&nbsp;<a class='a_zastavka_enable' onClick = '" . "selfobj.changeZIndexJR(); getJR(\"" . $rowlinky[0] . "\", 0, " . (integer) $zastavky[$rowlinky[0]][0][$i]->c_tarif . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>" . $nazev . "</a>&nbsp;| ";
        } else {
          $res = $res . "|&nbsp;<a class='a_zastavka_disable'>" . $nazev . "</a>&nbsp;| "; //style='text-decoration: none; cursor: default; color: #808080; font-style: italic;'
        }
        $poradi++;
        if ($poradi % 7 == 0) {
//          $res = $res . "<br>";
        }
//      }
    }

    $res = $res . "</td>";

    $res = $res . "</tr>";

    $res = $res . "<tr class='licha'>";
//    $res = $res . "<td></td>";
    $res = $res . "<td class='td_smer'>"; //style='font-size: 16px; font-weight: bold; padding-left: 10px; padding-right: 10px;'
    $res = $res . "B";
    $res = $res . "</td>";

    $res = $res . "<td class='td_zastavky'>"; //style='padding: 5px 5px 5px 5px;'

    $smer = 1;

    $poradi = 0;
    for($i=0; $i < count($zastavky[$rowlinky[0]][1]); $i++) {
//      if ($rowZastavkaB[2] == 0) {
//        $res = $res . (($poradi == 0) ? "" : " - ");
        $nazev = Str_Replace (" ", "&nbsp;", $zastavky[$rowlinky[0]][1][$i]->nazev_zastavky);
        if ($zastavky[$rowlinky[0]][1][$i]->stavi != 0) {
        $res = $res . "|&nbsp;<a class='a_zastavka_enable' onClick = '" . "selfobj.changeZIndexJR(); getJR(\"" . $rowlinky[0] . "\", 1, " . (integer) $zastavky[$rowlinky[0]][1][$i]->c_tarif . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>" . $nazev . "</a>&nbsp;| ";
        } else {
          $res = $res . "|&nbsp;<a class='a_zastavka_disable'>" . $nazev . "</a>&nbsp;| "; //style='text-decoration: none; cursor: default; color: #808080; font-style: italic;'
        }
        $poradi++;
        if ($poradi % 7 == 0) {
//          $res = $res . "<br>";
        }
//      }
    }

    $res = $res . "</td>";
    $res = $res . "</tr>";
  }
}
mysql_close($p);

$res = $res . "</table>";







} else {
if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

  /*$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
  $res = $res . "<div id='movedivSeznam' class='movediv'>";
  $res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
  $res = $res . "</div>";*/
  $res = $res . "<table id='tablejrSeznam' class = 'tableseznamlinky'>"; //style='max-width:500px; width: 500px;'

//------- LINKY ------

  $sql = "SELECT doprava, nazev_linky, c_linky FROM linky where idlocation = " . $location . " and packet = " . $packet . " order by c_linkysort";

  $result = mysql_query($sql);

  while ($rowlinky = mysql_fetch_row($result)) {
    $res = $res . "<tr class='licha'>";

    $res = $res . "<td rowspan='2' class='td_linka'>"; //style='text-align: left; width: auto;'
    $res = $res . "<span class = 'a_nazev_linky1'>";
    $res = $res . $rowlinky[1];
    $res = $res . "</span><br/>";
      if ($rowlinky[0] == 'T') {
        $res = $res . "<div class = 'div_logo_doprava_T'></div>"; //style='float: left;'
      }
      if ($rowlinky[0] == 'O') {
        $res = $res . "<div class = 'div_logo_doprava_O'></div>"; //style='float: left;'
      }
      if ($rowlinky[0] == 'A') {
        $res = $res . "<div class = 'div_logo_doprava_A'></div>"; //style='float: left;'
      }

    $res = $res . "</td>";

    $res = $res . "<td class='td_smer'>"; //style='font-size: 16px; font-weight: bold; padding-left: 10px; padding-right: 10px;'
    $res = $res . "A";
    $res = $res . "</td>";

    $res = $res . "<td class='td_zastavky'>"; //style='padding: 5px 5px 5px 5px; white-space: normal;'

    $smer = 0;

    $sql1 = "select zaslinky.c_tarif, (case when zastavky.nazev = '' then zastavky.zkratka else zastavky.nazev end), st.stavi
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $rowlinky[2] . "' and idlocation = " . $location . " and
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $rowlinky[2] . "' and
          idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $rowlinky[2] . "' and idlocation = " . $location . " and packet = " . $packet . " and
          smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st
          on (zaslinky.c_tarif = st.c_tarif)
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $rowlinky[2] . "' and zaslinky.voz = 1
          ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";

    $result1 = mysql_query($sql1);

    $poradi = 0;
    while ($rowZastavkyA = mysql_fetch_row($result1)) {
//      if ($rowZastavkaA[2] == 0) {
//        $res = $res . (($poradi == 0) ? "" : " - ");
        $nazev = Str_Replace (" ", "&nbsp;", $rowZastavkyA[1]);
        if ($rowZastavkyA[2] != 0) {
          $res = $res . "|&nbsp;<a class='a_zastavka_enable' onClick = '" . "selfobj.changeZIndexJR(); getJR(\"" . $rowlinky[2] . "\", 0, " . (integer) $rowZastavkyA[0] . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>" . $nazev . "</a>&nbsp;| ";
        } else {
          $res = $res . "|&nbsp;<a class='a_zastavka_disable'>" . $nazev . "</a>&nbsp;| "; //style='text-decoration: none; cursor: default; color: #808080; font-style: italic;'
        }
        $poradi++;
        if ($poradi % 7 == 0) {
//          $res = $res . "<br>";
        }
//      }
    }

    $res = $res . "</td>";

    $res = $res . "</tr>";

    $res = $res . "<tr class='licha'>";
//    $res = $res . "<td></td>";
    $res = $res . "<td class='td_smer'>"; //style='font-size: 16px; font-weight: bold; padding-left: 10px; padding-right: 10px;'
    $res = $res . "B";
    $res = $res . "</td>";

    $res = $res . "<td class='td_zastavky'>"; //style='padding: 5px 5px 5px 5px;'

    $smer = 1;

    $sql1 = "select zaslinky.c_tarif, (case when zastavky.nazev = '' then zastavky.zkratka else zastavky.nazev end), st.stavi
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $rowlinky[2] . "' and idlocation = " . $location . " and
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $rowlinky[2] . "' and
          idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $rowlinky[2] . "' and idlocation = " . $location . " and packet = " . $packet . " and
          smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st
          on (zaslinky.c_tarif = st.c_tarif)
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $rowlinky[2] . "' and zaslinky.voz = 1
          ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";

    $result1 = mysql_query($sql1);

    $poradi = 0;
    while ($rowZastavkyB = mysql_fetch_row($result1)) {
//      if ($rowZastavkaB[2] == 0) {
//        $res = $res . (($poradi == 0) ? "" : " - ");
        $nazev = Str_Replace (" ", "&nbsp;", $rowZastavkyB[1]);
        if ($rowZastavkyB[2] != 0) {
        $res = $res . "|&nbsp;<a class='a_zastavka_enable' onClick = '" . "selfobj.changeZIndexJR(); getJR(\"" . $rowlinky[2] . "\", 1, " . (integer) $rowZastavkyB[0] . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>" . $nazev . "</a>&nbsp;| ";
        } else {
          $res = $res . "|&nbsp;<a class='a_zastavka_disable'>" . $nazev . "</a>&nbsp;| "; //style='text-decoration: none; cursor: default; color: #808080; font-style: italic;'
        }
        $poradi++;
        if ($poradi % 7 == 0) {
//          $res = $res . "<br>";
        }
//      }
    }

    $res = $res . "</td>";
    $res = $res . "</tr>";
  }
}
mysql_close($p);

$res = $res . "</table>";
//$res = $res . "</div>";
}

echo $_GET['callback'] . "(" . json_encode($res) . ");";
?>