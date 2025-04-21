<?php
Header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: '.$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$linka = $_GET['linka'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$iszastavka = 0;
if (isset($_GET['zastavka'])) {
  $iszastavka = 1;
  $zastavka = $_GET['zastavka'];
}

$dbname = 'savvy_mhdspoje';

$tarifsmerA = -1;
$tarifsmerB = -1;

class TSmer {

  var $smer = null;
  var $ab = null;
  var $spoju = null;
  var $tarif = null;

}

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

if ($iszastavka == 1) {
  for ($smer = 0; $smer < 2; $smer++) {
    $sql1 = "select zaslinky.c_tarif, zastavky.nazev, st.stavi
            from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
            from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and idlocation = " . $location . " and
            packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
            case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and
            idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
            doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $linka . "' and idlocation = " . $location . " and packet = " . $packet . " and
            smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st
            on (zaslinky.c_tarif = st.c_tarif)
            left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
            zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
            where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and
            zaslinky.c_zastavky = " . $zastavka . " and zaslinky.voz = 1 and st.stavi = 1
            ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";
    $result1 = mysql_query($sql1);

    $row = mysql_fetch_row($result1);
    if ($row[2] != '') {
      if ($smer == 0) {
        $tarifsmerA = $row[0];
      }
      if ($smer == 1) {
        $tarifsmerB = $row[0];
      }
    }
  }
}


  $sql = "SELECT case when smera is null then
    (select nazev from zaslinky left outer join zastavky
    on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet)
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and zaslinky.voz = 1 order by zaslinky.c_tarif desc limit 1)
    else smera end,
    case when smerb is null then
    (select nazev from zaslinky left outer join zastavky
    on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet)
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and zaslinky.voz = 1 order by zaslinky.c_tarif limit 1)
    else smerb end,
    (select count(c_spoje) from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $linka . "' and smer = 0),
    (select count(c_spoje) from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $linka . "' and smer = 1)
    FROM linky where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = '" . $linka . "'";

  $result = mysql_query($sql);

  $res = "";

  $i = 0;
  while ($row = mysql_fetch_row($result)) {

    if ($iszastavka == 1) {
      if (($row[2] > 0) && ($tarifsmerA > -1)) {
        $smer = new TSmer();
        $smer->smer = $row[0];
        $smer->ab = 0;
        $smer->spoju = $row[2];
        $smer->tarif = $tarifsmerA;
        $res[] = $smer;
      }

      if (($row[3] > 0) && ($tarifsmerB > -1)) {
        $smer = new TSmer();
        $smer->smer = $row[1];
        $smer->ab = 1;
        $smer->spoju = $row[3];
        $smer->tarif = $tarifsmerB;
        $res[] = $smer;
      }
    }

    if ($iszastavka == 0) {
      if ($row[2] > 0) {
        $smer = new TSmer();
        $smer->smer = $row[0];
        $smer->ab = 0;
        $smer->spoju = $row[2];
        $smer->tarif = -1;
        $res[] = $smer;
      }

      if ($row[3] > 0) {
        $smer = new TSmer();
        $smer->smer = $row[1];
        $smer->ab = 1;
        $smer->spoju = $row[3];
        $smer->tarif = -1;
        $res[] = $smer;
      }
    }
/*    if ($row[2] >= 0) {
    $res[] = array($i++, preg_replace('/,/', '|', $row[0]), $row[2]);
    }
    if ($row[3] >= 0) {
    $res[] = array($i++, preg_replace('/,/', '|', $row[1]), $row[3]);
    }*/
  }
  mysql_close($p);
}

$jsonData = json_encode($res);

if (isset ($_GET['callback'])) {
  echo $_GET['callback'] . '(' . $jsonData . ');';
} else {
  echo $jsonData;
}

?>