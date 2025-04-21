<?php

require_once 'Vector.php';
require_once '../../lib/param.php';
require_once '../../lib/functions.php';

$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

class TZastavka {

  var $c_zastavky = null;
  var $loca = null;
  var $locb = null;

}

class TPrechod {

  var $od_zastavky = null;
  var $do_zastavky = null;
  var $vzdalenost = null;
  var $doba = null;

}

$distance = 0.005448; //0.005448 = cca 500m
$distanceVzdalenost = 500; //v metrech
$rychlost = 1.39; //metr per sec
$seznamZastavek = new Vector();
$seznamPrehodu = new Vector();

    global $con_server;
    global $con_db;
    global $con_pass;

$mysqli = new mysqli($con_server, $con_db, $con_pass, 'savvy_mhdspoje');
//$mysqli->query("SET NAMES 'utf-8';");

  $sql = "SELECT c_zastavky, loca, locb FROM zastavky where idlocation = " . $location . " and packet = " . $packet . " order by c_zastavky";

  $result = $mysqli->query($sql);

  while ($row = $result->fetch_row()) {
    $zastavka = new TZastavka();
    $zastavka->c_zastavky = $row[0];
    $zastavka->loca = $row[1];
    $zastavka->locb = $row[2];
    $seznamZastavek->addElement($zastavka);
  }

  for ($i = 0; $i < $seznamZastavek->size(); $i++) {
    $fromZastavka = $seznamZastavek->elementAt($i);
    for ($ii = 0; $ii < $seznamZastavek->size(); $ii++) {
      $toZastavka = $seznamZastavek->elementAt($ii);
      if ($fromZastavka->c_zastavky != $toZastavka->c_zastavky) {
        $deltaLoca = abs($fromZastavka->loca - $toZastavka->loca);
        $deltaLocb = abs($fromZastavka->locb - $toZastavka->locb);
        if (($deltaLoca <= $distance) && ($deltaLocb <= $distance)) {
          $vzdalenostDist = sqrt(($deltaLoca * $deltaLoca) + ($deltaLocb * $deltaLocb));
          $vzdalenost_m = ($vzdalenostDist * $distanceVzdalenost) / $distance;
          if ($vzdalenost_m != 0) {
            $cas = $vzdalenost_m / $rychlost;
            $prechod = new TPrechod();
            $prechod->od_zastavky = $fromZastavka->c_zastavky;
            $prechod->do_zastavky = $toZastavka->c_zastavky;
            $prechod->vzdalenost = round($vzdalenost_m);
            $prechod->doba = round($cas / 60);
            if ($prechod->doba <= 0) {
              $prechod->doba = 1;
            }
            $prechod->doba = $prechod->doba;
            $seznamPrehodu->addElement($prechod);
          }
        }
      }
    }
  }

  $sql = "DELETE FROM pesobus WHERE idlocation = " . $location . " and packet = " . $packet;
  $result = $mysqli->query($sql);

  for ($i = 0; $i < $seznamPrehodu->size(); $i++) {
    $prechod = $seznamPrehodu->elementAt($i);
    $sql = "INSERT INTO pesobus VALUES (" . $prechod->od_zastavky . ", " . $prechod->do_zastavky . ", " . $prechod->doba . ", " . $prechod->vzdalenost . ", " . $location . ", " . $packet . ")";
    echo $sql . "</br>";
    echo ($result = $mysqli->query($sql)) . "</br>";
//    echo $prechod->od_zastavky . " -> " . $prechod->do_zastavky . " | " . $prechod->vzdalenost . " metrů, " . $prechod->doba . " minut </br>";
  }

?>
