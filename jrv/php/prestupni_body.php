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

  var $c_tarif = null;
  var $c_zastavky = null;
  var $zast_A = false;
  var $zast_B = false;
  var $prestup = false;

}

$LINKY = array();
$TRASY = null;

function checkPrestup($c_linky, $c_zastavky, $c_zastavky_pred, $c_zastavky_next) {
  global $LINKY;
  $TRASY = null;

  $ret = false;

//  echo $c_linky . " , " . $c_zastavky . " , " . $c_zastavky_pred . " , " . $c_zastavky_next . "</br>";

  foreach ($LINKY as $key => $TRASY) {
    if ($key != $c_linky) {
      for ($i = 0; $i < count($TRASY); $i++) {
        if ($TRASY[$i]->c_zastavky == $c_zastavky) {
          $pred = ($TRASY[$i - 1] == null) ? null : $TRASY[$i - 1]->c_zastavky;
          $next = ($TRASY[$i + 1] == null) ? null : $TRASY[$i + 1]->c_zastavky;
//          echo $key . " | " . $pred . " / " .
          if (($pred != null) && ($pred != $c_zastavky_pred)) {
            $ret = true;
          }
          if (($next != null) && ($next != $c_zastavky_next)) {
            $ret = true;
          }
        }
      }
    }
  }

  return $ret;
}

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");

$sql = "SELECT c_linky, c_tarif, c_zastavky, zast_a, zast_b FROM zaslinky where idlocation = " . $location . " and packet = " . $packet . " order by c_linky, c_tarif;";
$result = $mysqli->query($sql);

while ($row = $result->fetch_row()) {
  $c_linky = $row[0];
  $c_tarif = $row[1];
  $c_zastavky = $row[2];
  $zast_a = $row[3];
  $zast_b = $row[4];

  /* if ($LINKY[$c_linky] == null) {
    $TRASY = array();
    $LINKY[$c_linky] = $TRASY;
    } else {
    $TRASY = $LINKY[$c_linky];
    } */

  $new_zastavka = new TZastavka();
  $new_zastavka->c_tarif = $c_tarif;
  $new_zastavka->c_zastavky = $c_zastavky;
  $new_zastavka->zast_A = $zast_a;
  $new_zastavka->zast_B = $zast_b;

  $LINKY[$c_linky][count($LINKY[$c_linky])] = $new_zastavka;
}

foreach ($LINKY as $key => $TRASY) {
  echo $key . " : " . "</br>";
  for ($i = 0; $i < count($TRASY); $i++) {
    $TRASY[$i]->prestup = checkPrestup($key, $TRASY[$i]->c_zastavky, (($TRASY[$i - 1] == null) ? null: $TRASY[$i - 1]->c_zastavky), (($TRASY[$i + 1] == null) ? null: $TRASY[$i + 1]->c_zastavky));
    echo "&nbsp &nbsp - " . $TRASY[$i]->c_tarif . " , " . $TRASY[$i]->c_zastavky . " | " . $TRASY[$i]->prestup . "</br>";
  }
}

$PRESTUPY = null;

foreach ($LINKY as $key => $TRASY) {
  for ($i = 0; $i < count($TRASY); $i++) {
    if ($TRASY[$i]->prestup == true) {
      $PRESTUPY[$TRASY[$i]->c_zastavky] = $TRASY[$i];
    }
  }
}

echo "</br></br>";
echo count($PRESTUPY);
echo "</br></br>";

foreach ($PRESTUPY as $key => $zast) {
  $sql = "update zaslinky set prestup = 1 where c_zastavky = " . $zast->c_zastavky . " and idlocation = " . $location . " and packet = " . $packet;
  echo $sql . "<br>";
  $result = $mysqli->query($sql);
//  echo $zast->c_zastavky . "</br>";
}
?>