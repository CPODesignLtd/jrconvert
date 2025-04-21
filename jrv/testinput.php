<?php

header('Content-Type: text/html; charset=windows-1250');
include 'lib/CZlang.php';
include 'lib/param.php';

class TZastavka {

  var $c_zastavky = null;
  var $nazev = '';

}

function imp_ZASTAVKY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;

  $ZAST[] = null;

  $fileLocation = $path . "exp.dat";

  $txt = 'a:3:{i:3;O:9:"TZastavka":2:{s:10:"c_zastavky";s:1:"3";s:5:"nazev";s:0:"";}i:5;O:9:"TZastavka":2:{s:10:"c_zastavky";s:1:"5";s:5:"nazev";s:0:"";}i:7;O:9:"TZastavka":2:{s:10:"c_zastavky";s:1:"7";s:5:"nazev";s:30:"Adolfov, Krásný Les, Petrovice";}}';
  $ZAST = unserialize(/*file_get_contents($fileLocation)*/$txt);

  //echo $ZAST;
  foreach ($ZAST as $key => $val1) {
    echo "zastavka > " . $val1->c_zastavky . " | " . $val1->nazev . "</br>";
  }

}

$target_path = "../jrdata/3/27/";
imp_ZASTAVKY(3, 27, $target_path);
?>
