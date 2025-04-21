<?php
error_reporting(0);
require_once '../../lib/param.php';
require_once '../../lib/functions.php';

$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {

  //mysql_query("SET CHARACTER SET UTF8");
  //mysql_select_db($dbname);
  
  $sql = "select distinct zastavky.c_zastavky, zastavky.nazev from zastavky
          where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . "
          ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";

  $result = mysql_query($sql);

  $res = array();

  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], preg_replace('/,/', '|', $row[1]));
  }
  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $_GET['location'] . ', ' . $_GET['packet'] . ', "' . $_GET['target1'] . '", "' . $_GET['target2'] . '", ' . $jsonData . ');';
?>