<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];
$packet = $_GET['packet'];

class TPoznamka {

  var $id_poznamky = null;
  var $oznaceni = null;
  var $popis = null;
  var $casova = null;
  var $zobrazovat = null;

}

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "SELECT c_kodu, oznaceni, rezerva, caspozn, showing FROM pevnykod where idlocation = " . $location . " AND packet = " . $packet . " order by c_kodu";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $poznamka = new TPoznamka();
    $poznamka->id_poznamky = $row[0];
    $poznamka->oznaceni = $row[1];
    $poznamka->popis = $row[2];
    $poznamka->casova = $row[3];
    $poznamka->zobrazovat = $row[4];
    $res[] = $poznamka;
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
