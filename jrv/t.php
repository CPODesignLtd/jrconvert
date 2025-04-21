<?php

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

$dayofweek = jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, $day, $year), 0);
if ($dayofweek == 0) {
  $dayofweek = 7;
}

$location = 0;
$packet = 14;

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqliVARGRF->query("SET NAMES 'utf-8';");

$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = 0 and packet = 14 order by pk;";
$result = $mysqli->query($sql);

$varGRF = 0;

$pocet = 0;
while ($row = $result->fetch_row()) {
  $pocet++;
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

if ($pocet == 0) {
  $select = "select distinct OZNACENI, C_KODU from pevnykod";
  $where = " where idlocation = " . $location . " and packet = " . $packet . " and CASPOZN = 1 and (OZNACENI = \"" . $dayofweek . "\"";
  if (($dayofweek >= 1) && ($dayofweek <= 5)) {
    $where .= " or OZNACENI = \"X\")";
  }
  if ($dayofweek == 6) {
    $where .= ")";
  }
  if ($dayofweek == 7) {
    $where .= " or OZNACENI = \"+\")";
  }
  $where .= " order by c_kodu";

  echo $select . $where . "</BR>";

  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqliVARGRF->query("SET NAMES 'utf-8';");

  $sql = $select . $where;
  $result = $mysqli->query($sql);

  $varGRF = 0;

  $pocet = 0;
  while ($row = $result->fetch_row()) {
    $pocet++;
    $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
    $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
    $rowVARGRF = $resultVARGRF->fetch_row();
    $varGRF += $rowVARGRF[0];
  }

  echo $varGRF . "</BR>";
}
?>
