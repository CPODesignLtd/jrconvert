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

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

//  $sql = "SELECT nazev, loca, locb, c_zastavky FROM zastavky where nazev <> '' and idlocation = " . $location . " and packet = " . $packet ;
  $sql = "select distinct zastavky.nazev, zastavky.loca, zastavky.locb, zastavky.c_zastavky, zastavky.passport from zastavky left outer join zaslinky
    on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 and (zastavky.nazev <> '' or zastavky.nazev <> null) ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";
  $result = mysql_query($sql);

  $res = array();

  while ($row = mysql_fetch_row($result)) {
    $res[] = array(preg_replace('/,/', '|', $row[0]), $row[1], $row[2], $row[3], $row[4]);
  }

  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $_GET['location'] . ', ' . $_GET['packet'] . ', ' . $jsonData . ');';
?>
