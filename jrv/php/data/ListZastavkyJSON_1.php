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

class TZastavka {

  var $idzastavky = null;
  var $nazev = null;
  var $loca = null;
  var $locb = null;

}

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

  /*$sql = "select zastavky.c_zastavky, zastavky.nazev, zastavky.pk1, zastavky.pk2, zastavky.pk3, zastavky.pk4
          , zastavky.pk5, zastavky.pk6, zastavky.loca, zastavky.locb from zastavky
          where zastavky.nazev <> '' and zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . "
          ORDER BY zastavky.nazev";*/

   $sql = "select distinct zastavky.c_zastavky, zastavky.nazev, zastavky.pk1, zastavky.pk2, zastavky.pk3, zastavky.pk4
          , zastavky.pk5, zastavky.pk6, zastavky.loca, zastavky.locb from zastavky left outer join zaslinky
    on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 and (zastavky.nazev <> '' or zastavky.nazev <> null) ORDER BY zastavky.nazev COLLATE utf8_czech_ci";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $zastavka = new TZastavka();
    $zastavka->idzastavky = $row[0];
    $zastavka->nazev = $row[1];
    $zastavka->loca = $row[8];
    $zastavka->locb = $row[9];
    $res[] = $zastavka;
//    $res[] = array($row[0], preg_replace('/,/', '|', $row[1]), $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]);
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