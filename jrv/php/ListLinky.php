<?php
$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "SELECT c_linky, nazev_linky FROM linky where idlocation = " . $location . " and packet = " . $packet . " order by c_linkysort";

  $result = mysql_query($sql);

  $res = "";
  
  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], $row[1]);
  }
  
  mysql_close($p);
}

echo json_encode($res);// $res;// . "</select>";
?>
