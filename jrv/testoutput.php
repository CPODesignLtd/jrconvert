<?php

header('Content-Type: text/html; charset=windows-1250');
include 'lib/CZlang.php';
include 'lib/param.php';

class TZastavka {

  var $c_zastavky = null;
  var $nazev = '';

}

class TSpoj {

  var $od_zastavky = null;
  var $do_zastavky = null;
  var $cas = null;
  var $doba_jizdy = null;
  var $c_linky = null;

}

function exp_ZASTAVKY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;

  $res = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT C_ZASTAVKY, NAZEV, PK1, PK2, PK3, PK4, PK5, PK6, IDLOCATION, PACKET, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT, EXISTS (SELECT * FROM zaslinky WHERE zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.prestup = 1) as prestup,
          (PK1 & PK2 & PK3 & PK4 & PK5 & PK6) as ma_poznamky
          FROM `savvy_mhdspoje`.`zastavky` WHERE packet = " . $packet . " and idlocation=" . $idlocation . " order by c_zastavkysort");

  $ZAST = null;
  while ($row = mysql_fetch_row($sql)) {
    $newZ = new TZastavka();
    $newZ->c_zastavky = $row[0];
    $newZ->nazev = $row[1];
    $ZAST[$row[0]] = $newZ;
  }
  $fileLocation = $path . "exp.dat";
  /*  if (!file_exists($path)) {
    mkdir($path, 0777);
    }
    $file = fopen($fileLocation, "w+");
    fwrite($file, $res);
    fclose($file); */
  file_put_contents($fileLocation, serialize($ZAST));
  chmod($fileLocation, 0777);
}

function exp_SPOJE($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;

  $res = null;

  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $sql = "call savvy_mhdspoje.getOdjezdyZastavkaTEST4(3, 27, 0,0,0,0,0,0,0,0);";

  $result = $mysqli->query($sql);

  $SPOJE = null;
  $i = 1;
  $oldodZ = null;

  $fileLocation = $path . "expS.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");

  while ($row = $result->fetch_row()) {
    /*    if ($oldodZ == null) {
      $oldodZ = $row[1];
      }
      $newZ = new TSpoj();
      $newZ->od_zastavky = $row[1];
      $newZ->do_zastavky = $row[2];
      $newZ->cas = $row[6];
      $newZ->doba_jizdy = $row[7];
      $newZ->c_linky = $row[0]; */

    //$SPOJE[$i++] = $newZ;
//    i:1;O:9:"TZastavka":2:{s:10:"c_zastavky";s:1:"1";s:5:"nazev";s:0:"";}
    $res .= 'i:' . $i++ . ':O:5:"TSpoj":i:5:{s:11:"od_zastavky";s:' . strlen($row[1]) . ':"' . $row[1] . '";s:11:"do_zastavky";s:' . strlen($row[2]) . ':"' . $row[2] . '"' .
            ';s:3:"cas";s:' . strlen($row[6]) . ':"' . $row[6] . '";s:10:"doba_jizdy";s:' . strlen($row[7]) . ':"' . $row[7] . '";s:7:"c_linky";s:' . strlen($row[0]) . ':"' . $row[0] . '";}';
    fwrite($file, $res);
    $rws = null;
  }
//  $res = 'a:' . $i . ':{' . $res . '}';

  /*  if (!file_exists($path)) {
    mkdir($path, 0777);
    }
    $file = fopen($fileLocation, "w+");
    fwrite($file, $res);
    fclose($file); */
//  file_put_contents($fileLocation, /*serialize($SPOJE)*/$res);
//  chmod($fileLocation, 0777);
  fclose($file);
  chmod($fileLocation, 0777);
}

$target_path = "../jrdata/3/27/";
//exp_ZASTAVKY(3, 27, $target_path);
exp_SPOJE(3, 27, $target_path);
echo "hotovo";
?>
