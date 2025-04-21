<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$linka = $_GET['linka'];
$spoj = $_GET['spoj'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

class TJizda {

  var $idzastavky = null;
  var $tarif = null;
  var $cas = '-- : --';
  var $icas = -1;

}

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

  $sql = "select zaslinky.c_tarif, zaslinky.c_zastavky, spoje.HH, spoje.MM, chronometr.doba_pocatek, chronometr.doba_jizdy
from spoje left outer join zaslinky on
(spoje.idlocation = zaslinky.idlocation and spoje.packet = zaslinky.packet and
spoje.c_linky = zaslinky.c_linky and (case when spoje.smer = 0 then zaslinky.zast_A = 1 else zaslinky.zast_B = 1 end))
left outer join chronometr on
(zaslinky.idlocation = chronometr.idlocation and zaslinky.packet = chronometr.packet and spoje.c_linky = chronometr.c_linky and
spoje.smer = chronometr.smer and spoje.chrono = chronometr.chrono and zaslinky.c_tarif = chronometr.c_tarif)
where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $linka . "' and spoje.c_spoje = " . $spoj . "
ORDER BY CASE spoje.smer WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT spoje.smer = 1 THEN zaslinky.c_tarif END";

  //echo $sql;
  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $trasa = new TJizda();
    $trasa->idzastavky = $row[1];
    $trasa->tarif = $row[0];
    if ($row[5] != -1) {
      $pomcas = $row[2] * 60 + $row[3] + $row[4];
      $trasa->cas = ((intval($pomcas / 60) < 10) ? "0" . intval($pomcas / 60): intval($pomcas / 60)) . " : " . ((intval($pomcas % 60) < 10) ? "0" . intval($pomcas % 60): intval($pomcas % 60));
      $trasa->icas = $pomcas;
    }
    $res[] = $trasa;
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