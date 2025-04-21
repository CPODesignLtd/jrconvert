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

$zezastavky = 0;
if (isset ($_GET['zastavka'])) {
  $zezastavky = 1;
  $c_zastavky = $_GET['zastavka'];
}

$dbname = 'savvy_mhdspoje';

class TLinka {

  var $idlinky = null;
  var $nazev = null;
  var $smerA = null;
  var $smerB = null;
  var $doprava = null;

}

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  if ($packet == '') {
    $sql = "select packet from packets where jeplatny = 1 and location = 3 and jr_od <= '2013-12-08' and jr_do >= '2013-12-08' limit 1";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_row($result)) {
    }
  } else {
  $sql = mysql_query("select jr_od, jr_do from savvy_mhdspoje.packets
           WHERE location=" . $location . " and packet = " . $packet);
  $row = mysql_fetch_row($sql);
  }

  if ($zezastavky == 1) {
    $sql = "select distinct linky.c_linky, linky.nazev_linky, doprava
            from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and
            zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet)
            where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_zastavky = " . $c_zastavky . " and zaslinky.voz = 1 and (zaslinky.zast_A = 1 or zast_B = 1)
            order by linky.c_linkysort";
  } else {
    $sql = "SELECT c_linky, nazev_linky, doprava FROM linky where idlocation = " . $location . " and packet = " . $packet . " and jr_do >= '" . $row[0]  . "' " .
         " order by c_linkysort";
  }

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $linka = new TLinka();
    $linka->idlinky = $row[0];
    $linka->nazev = $row[1];
    $linka->doprava = $row[2];
    $res[] = $linka;//array($row[0], preg_replace('/,/', '|', $row[1]));
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
