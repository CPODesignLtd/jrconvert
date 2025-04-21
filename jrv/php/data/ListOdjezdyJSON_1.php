<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    Header('Access-Control-Allow-Methods: GET');
    Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    Header('Access-Control-Max-Age: 86400');
    die;
}

$location = $_GET['location'];
$packet = $_GET['packet'];
$cas = $_GET['cas'];
$zastavka = $_GET['zastavka'];
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

class TSpoj {

    var $c_linky = null;
    var $nazev_linky = null;
    var $doprava = null;
    var $c_tarif = null;
    var $smer = null;
    var $HH = null;
    var $MM = null;
    var $id_spoje = null;
    var $chrono = null;
    var $konecna = null;
    var $konecnac_zast = null;

}

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqliVARGRF->query("SET NAMES 'utf-8';");

$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
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
}

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$sql = "call getOdjezdyZastavkaSpoje(" . $location . " ," . $packet . ", " . $cas . ", " . $zastavka . ", " . $varGRF . ", '" . date_format(new DateTime($datumJR), 'Y-m-d') . "');";
//echo "call getOdjezdyZastavkaSpoje(" . $location . " ," . $packet . ", " . $cas . ", " . $zastavka . ", " . $varGRF . ");";
//echo $sql;

$result = $mysqli->query($sql);

$EXP = null;
while ($row = $result->fetch_row()) {
    if ($EXP[$row[0] . "_" . $row[4]] == null) {
        $spoj = new TSpoj();
        $spoj->c_linky = $row[0];
        $spoj->nazev_linky = $row[1];
        $spoj->doprava = $row[2];
        $spoj->c_tarif = $row[3];
        $spoj->smer = $row[4];
        $spoj->HH = intval(($row[5] / 60) % 24);
        $spoj->MM = intval($row[5] % 60);
        $spoj->id_spoje = $row[6];
        $spoj->chrono = $row[7];

        $mysqlikonecna = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
        $mysqlikonecna->query("SET NAMES 'utf-8';");
        if ($spoj->smer == 0) {
            $sqlchrono = "select max(chronometr.c_tarif) from chronometr left outer join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) where chronometr.c_linky = '" . $spoj->c_linky . "' and chronometr.smer = " . $spoj->smer . " and chronometr.chrono = " . $spoj->chrono . " and zaslinky.zast_A = 1 and chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.doba_jizdy > -1";
        } else {
            $sqlchrono = "select min(chronometr.c_tarif) from chronometr left outer join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) where chronometr.c_linky = '" . $spoj->c_linky . "' and chronometr.smer = " . $spoj->smer . " and chronometr.chrono = " . $spoj->chrono . " and zaslinky.zast_B = 1 and chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.doba_jizdy > -1";
        }

        $resultkonecna = $mysqlikonecna->query($sqlchrono);

        while ($rowk = $resultkonecna->fetch_row()) {
            $spoj->konecna = $rowk[0];
            $mysqlikonecnac_zast = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
            $mysqlikonecnac_zast->query("SET NAMES 'utf-8';");
            $sqlkonecna = "select zaslinky.c_zastavky from zaslinky where zaslinky.c_linky = '" . $spoj->c_linky . "' and zaslinky.c_tarif = " . $rowk[0] . " and zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet;
            $resultkonecnac_zast = $mysqlikonecnac_zast->query($sqlkonecna);

            while ($rowc_zast = $resultkonecnac_zast->fetch_row()) {
                $spoj->konecnac_zast = $rowc_zast[0];
            }
        }

        $res[] = $spoj;
        $EXP[$row[0] . "_" . $row[4]] = 1;
    }
}

$jsonData = json_encode($res);

if (isset($_GET['callback'])) {
    echo $_GET['callback'] . '(' . $jsonData . ');';
} else {
    echo $jsonData;
}
?>
