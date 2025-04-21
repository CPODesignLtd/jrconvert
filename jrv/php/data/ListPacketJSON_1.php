<?php
Header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: '.$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];

$dbname = 'savvy_mhdspoje';

/*if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}*/

$datumJR = date('Y-m-d');

class TPacket {

  var $packet = null;
  var $version = 0;

}

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "select packet, mobil_data_version from packets where jeplatny = 1 and location = " . $location . " and jr_od <= '" . $datumJR . "' and jr_do >= '" . $datumJR . "' limit 1";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $packet = new TPacket();
    $packet->packet = $row[0];
    $packet->version = $row[1];
    $res[] = $packet;
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