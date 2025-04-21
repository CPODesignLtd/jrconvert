<?php

$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

  /*$sql = "select zastavky.c_zastavky, zastavky.nazev from zastavky
          where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . "
          ORDER BY zastavky.c_zastavky";*/
  $sql = "select distinct zastavky.c_zastavky, zastavky.nazev, zaslinky.voz from zastavky left outer join zaslinky
    on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], preg_replace('/,/', '|', $row[1]));
  }
  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $_GET['location'] . ', ' . $_GET['packet'] . ', "' . $_GET['target1'] . '", "' . $_GET['target2'] . '", ' . $jsonData . ');';
?>