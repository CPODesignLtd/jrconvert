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
    $res[] = array($row[0], preg_replace('/,/', '|', $row[1]), preg_replace('/,/', '|', $row[2]), $row[3], $row[4]);   
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
