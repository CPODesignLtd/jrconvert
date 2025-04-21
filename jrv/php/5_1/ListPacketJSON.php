<?php
error_reporting(0);
require_once '../../lib/param.php';
require_once '../../lib/functions.php';

$location = $_GET['location'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

  $sql = "SELECT packet, jr_od, jr_do, jeplatny FROM packets where location = " . $location . " order by jr_od";

  $result = mysql_query($sql);

  $res = array();
  
  while ($row = mysql_fetch_row($result)) {    
    $res[] = array($row[0], date_format(new DateTime($row[1]), 'd'), date_format(new DateTime($row[1]), 'm'), date_format(new DateTime($row[1]), 'Y'), date_format(new DateTime($row[2]), 'd'), date_format(new DateTime($row[2]), 'm'), date_format(new DateTime($row[2]), 'Y'), $row[3]);   
  }
  
  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $jsonData . ');';
?>
