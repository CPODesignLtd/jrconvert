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

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

  $sql = "SELECT c_linky, nazev_linky, popis FROM linky where idlocation = " . $location . " and packet = " . $packet .
         " and (JR_OD is null or JR_OD <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") and " .
         " (JR_DO is null or JR_DO >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") " .
          " order by c_linkysort";

  $result = mysql_query($sql);

  $res = array();

  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], ($row[2] != "") ? $row[1] . " - " . preg_replace('/,/', '|', $row[2]): $row[1]);
  }

  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $_GET['location'] . ', ' . $_GET['packet'] . ', "' . $_GET['target'] . '", ' . $jsonData . ');';
?>