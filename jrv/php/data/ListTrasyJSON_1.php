<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$linka = $_GET['linka'];
$smer = $_GET['smer'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

class TTrasa {

  var $idzastavky = null;
  var $tarif = null;
  var $nazev = null;
  var $staviA = null;
  var $staviB = null;
  var $smer =null;

}

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

  $sql = "select zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3
          , zaslinky.a1_tarif, zaslinky.a2_tarif, zaslinky.b1_tarif, zaslinky.b2_tarif, st.stavi, zast_A, zast_B, " .$smer. " as smer
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
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and ((" . $smer . "=0 and zaslinky.zast_a = 1) or (" . $smer . "=1 and zaslinky.zast_b = 1)) 
          ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";

  /*    $sql = "select zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3
    , zaslinky.a1_tarif, zaslinky.a2_tarif, zaslinky.b1_tarif, zaslinky.b2_tarif, st.stavi
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
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and zaslinky.voz = 1
    ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END"; */

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
//    if ($row[10] == 1) {
    $trasa = new TTrasa();
    $trasa->idzastavky = $row[0];
    $trasa->tarif = $row[1];
    $trasa->nazev = $row[2];
    $trasa->smer = $row[13];
    $trasa->staviA = $row[10] & $row[11];
    $trasa->staviB = $row[10] & $row[12];
    $res[] = $trasa;
//    }
//    $res[] = array($row[0], $row[1], preg_replace('/,/', '|', $row[2]), $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
  }
  mysql_close($p);
}

$jsonData = json_encode($res);

if (isset($_GET['callback'])) {
  echo $_GET['callback'] . '(' . $jsonData . ');';
} else {
  echo $jsonData;
}
?>