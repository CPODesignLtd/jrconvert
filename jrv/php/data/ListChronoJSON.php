<?php
Header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: '.$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];
$packet = $_GET['packet'];
$c_linky = $_GET['c_linky'];
$smer = $_GET['smer'];
$chrono = $_GET['chrono'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "SELECT c_linky, smer, chrono, c_tarif, c_zastavky, doba_jizdy, doba_pocatek FROM chronometr where c_linky = " . $c_linky . " and smer = " . $smer . " and chrono = " . $chrono . " and idlocation = " . $location . " and packet = " . $packet .
         " ORDER BY CASE " . $smer . " WHEN 1 THEN chronometr.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN chronometr.c_tarif END";
  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
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
