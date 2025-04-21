<?php

$linka = $_GET['linka'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "SELECT case when smera is null then 
    (select nazev from zaslinky left outer join zastavky 
    on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet) 
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' order by zaslinky.c_tarif desc limit 1)
    else smera end, 
    case when smerb is null then 
    (select nazev from zaslinky left outer join zastavky 
    on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet) 
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' order by zaslinky.c_tarif limit 1)
    else smerb end,
    (select count(c_spoje) from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $linka . "' and smer = 0), 
    (select count(c_spoje) from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $linka . "' and smer = 1)          
    FROM linky where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = '" . $linka . "'";

  $result = mysql_query($sql);

  $res = "";

  $i = 0;
  while ($row = mysql_fetch_row($result)) {
    if ($row[2] > 0) {
    $res[] = array($i++, preg_replace('/,/', '|', $row[0]));
    }
    if ($row[3] > 0) {
    $res[] = array($i++, preg_replace('/,/', '|', $row[1]));
    }
  }
  mysql_close($p);
}

echo json_encode($res);
?>