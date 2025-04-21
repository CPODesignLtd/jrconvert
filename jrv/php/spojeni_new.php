<?php

$pocatek = $_GET[pocatek];
$cil = $_GET[cil];
$location = $_GET[location];
$packet = $_GET[packet];
$H = $_GET['h'];
$M = $_GET['m'];
$dobaSpoje = $_GET[dobaS];
$pocetprestupu = $_GET[pocetP];
$minprestup = 2;

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

$day = date_format(new DateTime($datumJR), 'd');
$month = date_format(new DateTime($datumJR), 'm');
$year = date_format(new DateTime($datumJR), 'Y');

class TZastavka {

  var $c_zastavky = null;
  var $prijezd = null;
  var $hrana_od = null;
  var $hrana_do = null;
  var $vaha = PHP_INT_MAX;
  var $nacitano = false;
  var $odebran = false;

}

class THrana {

  var $c_linky = null;
  var $odjezd = null;
  var $od_zastavky = null;
  var $do_zastavky = null;
  var $od_tarif = null;
  var $do_tarif = null;
  var $casOD = null;
  var $doba_jizdy = null;
  var $smer = null;

}

$UZLY = null;
$HRANY = null;
$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqliVARGRF->query("SET NAMES 'utf-8';");

$pocetresult = 0;
echo "time : " . time();
echo "</br>";
$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
echo $sql . "</br>";
$result = $mysqli->query($sql);

$varGRF = 0;
$maxcas = 0;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

echo $varGRF;
echo "</br>";
echo "</br>";
echo "time : " . time();
echo "</br>";

function vypisuzlu() {
  global $UZLY;
  if ($UZLY != null) {
    echo "</br></br>";
    foreach ($UZLY as $key => $val) {
      $uzel = $val;
      echo $uzel->c_zastavky . "|" . $uzel->hrana_od->c_linky . "|" . $uzel->vaha . "|" . $uzel->odebran . "</br>";
    }
    echo "</br></br>";
  }
}

function nulujGraf($startUzel, $startCas) {
  global $UZLY;
  if ($UZLY != null) {
    foreach ($UZLY as $key => $val) {
      $uzel = $val;
      $uzel->odebran = false;
      $uzel->vaha = PHP_INT_MAX;
      $uzel->prijezd = null;
      $uzel->hrana_od = null;
      $uzel->hrana_do = null;
    }
    $startUzel->odebran = true;
    $startUzel->prijezd = $startCas;
    $startUzel->vaha = 0;
  }
}

//nacti hrany od zastavky
function getHrany($odZ, $odCAS) {
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  global $location;
  global $packet;
  global $varGRF;
  global $UZLY;
  global $HRANY;
  global $minprestup;

  $od_zastavka = $UZLY[$odZ];
  if ($od_zastavka->hrana_od == null) {
    $without = -1;
  } else {
    $without = $od_zastavka->hrana_od->od_zastavky->c_zastavky;
  }
//  $without = -1;
  $sql = "call getOdjezdyZastavkaTEST(" . $location . " ," . $packet . ", " . $odCAS . ", " . $odZ . ", " . $without . ", " . $varGRF . ");";
  echo $sql . "</br>";
  $result = $mysqli->query($sql);
  while ($row = $result->fetch_row()) {
//echo "doZ = " . $row[2] . "</br>";
    $newHrana = new THrana();
    $newHrana->c_linky = $row[0];
    $newHrana->od_zastavky = $od_zastavka; //row[1]
    $do_zastavka = $UZLY[$row[2]];
    if ($do_zastavka == null) {
      $do_zastavka = new TZastavka();
      $do_zastavka->c_zastavky = $row[2];
      $UZLY[$row[2]] = $do_zastavka;
    }
echo $row[0] . " -> " . $do_zastavka->c_zastavky . "</br>";
    $newHrana->do_zastavky = $do_zastavka;
    $newHrana->od_tarif = $row[3];
    $newHrana->do_tarif = $row[4];
    $newHrana->smer = $row[5];
    $newHrana->casOD = $row[6];
    $newHrana->doba_jizdy = $row[7];
    $HRANY[$row[1] /* c_linky */ . "," . $row[2]][count($HRANY[$row[1] . "," . $row[2]])] = $newHrana;
    if ($do_zastavka->odebran == false) {
      $vahahrany = $od_zastavka->vaha + $newHrana->casOD - $od_zastavka->prijezd;
      if ($vahahrany >= 0) {
        $vahahrany += $newHrana->doba_jizdy;
        if (/*($od_zastavka->hrana_od->c_linky != $newHrana->c_linky) &&*/ ($newHrana->casOD - ($od_zastavka->hrana_od->casOD + $od_zastavka->hrana_od->doba_jizdy) < $minprestup)) {

        } else {
          if (($do_zastavka->vaha > $vahahrany) || (($do_zastavka->vaha == $vahahrany) && ($od_zastavka->hrana_od->c_linky == $newHrana->c_linky))) {
            $do_zastavka->vaha = $vahahrany;
            $od_zastavka->hrana_do = $newHrana;
            $do_zastavka->hrana_od = $newHrana;
            $do_zastavka->prijezd = $newHrana->casOD + $newHrana->doba_jizdy;
          }
        }
      }
    }
  }
  $od_zastavka->nacitano = true;
}

function getMinUzel($vybUzel) {
  global $UZLY;
  global $HRANY;
  $ret = null;
  $min = PHP_INT_MAX;
  if ($HRANY != null) {
    foreach ($HRANY as $key => $val) {
      foreach ($val as &$prvek) {
        if ($prvek->od_zastavky->c_zastavky == $vybUzel->c_zastavky) {
          if (($min >= $prvek->do_zastavky->vaha) && ($prvek->do_zastavky->odebran == false)) {
            $min = $prvek->do_zastavky->vaha;
            $ret = $prvek->do_zastavky;
          }
        }
      }
    }
  }

  if ($ret == null) {
    if ($UZLY != null) {
      foreach ($UZLY as $key => $val) {
        $uzel = $val;
        if (($min > $uzel->vaha) && ($uzel->odebran == false)) {
          $min = $uzel->vaha;
          $ret = $uzel;
        }
      }
    }
  }
  return $ret;
}

//create start uzel
$newUzel = new TZastavka();
$newUzel->c_zastavky = $pocatek;
$newUzel->prijezd = $H * 60 + $M;
$newUzel->vaha = 0;
$newUzel->odebran = true;
$UZLY[$pocatek] = $newUzel;
$mamcil = false;
while ($mamcil == false) {
  if (($newUzel->nacitano == false) && ($newUzel->odebran == true)) {
    getHrany($newUzel->c_zastavky, $newUzel->prijezd);
  }
  $uzel = getMinUzel($newUzel);
  if ($uzel->c_zastavky == $cil) {
    $mamcil = true;
  }
  vypisuzlu();
  echo "min uzel - " . $newUzel->c_zastavky . "->" . $uzel->c_zastavky . "|" . $uzel->hrana_od->c_linky . "->" . $uzel->vaha . "</br>";
  $newUzel = $uzel;
  $newUzel->odebran = true;
}
echo "time : " . time();
echo "</br>";
while ($uzel->c_zastavky != $pocatek) {
  echo $uzel->c_zastavky . "|" . $uzel->hrana_od->c_linky . "->" . $uzel->hrana_od->od_zastavky->c_zastavky . "</br>";
  $uzel = $uzel->hrana_od->od_zastavky;
}

?>