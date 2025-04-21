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

$cas = date("H") * 60 + date("i");

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

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'cz';
}
if ($lang == '') {
    $lang = 'cz';
}

if ($lang == 'cz') {
    require_once '../../lib/CZlang.php';
}

if ($lang == 'sk') {
    require_once '../../lib/SKlang.php';
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
$sql = "call getOdjezdyZastavkaSpoje(" . $location . " ," . $packet . ", " . $cas . ", " . $zastavka . ", " . $varGRF . ", '" . $datumJR . "');";
//echo $sql;

$result = $mysqli->query($sql);

$EXP = null;

$res = '';

$reshlavicka = '';

//$reshlavicka = $reshlavicka . "<div class = 'div_pozadikomplex' style='width: auto;'>";
//$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
//$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
//$reshlavicka = $reshlavicka . "</div>";
//$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:700px; width: auto;'>";
$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='width: auto;'>";
$reshlavicka = $reshlavicka . "<td>";

$reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: auto;'>";
$reshlavicka = $reshlavicka . "<tr>";
$reshlavicka = $reshlavicka . "<th colspan='2' style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_linka);
$reshlavicka = $reshlavicka . "<th style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_odjezd);
$reshlavicka = $reshlavicka . "<th style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_smer);
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr>";

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
        }

        $mysqlikonecna = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
        $mysqlikonecna->query("SET NAMES 'utf-8';");
        $sqlchrono = "select zastavky.nazev from zaslinky left outer join zastavky on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet) where zaslinky.c_linky = '" . $spoj->c_linky . "' and zaslinky.c_tarif = " . $spoj->konecna . " and zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet;
        $resultkonecna = $mysqlikonecna->query($sqlchrono);

        while ($rowk = $resultkonecna->fetch_row()) {
            $spoj->konecna = $rowk[0];
        }

        $res = $res . "<tr>";
        $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px; display: inline-block; white-space: nowrap; vertical-align: middle;'>";
        $res = $res . "<a class = 'popisek' style='font-size: 18px; padding-right: 10px;'>";
        $res = $res . $spoj->nazev_linky; //iconv('UTF-8', 'windows-1250', $spoj->nazev_linky);
        $res = $res . "</a>";
        $res = $res . "</td>";
        $res = $res . "<td style='text-align: left; width: auto; padding: 0px 15px 0px 0px; white-space: nowrap; vertical-align: middle;'>";
        $res = $res . (($spoj->doprava == 'T') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png' style='vertical-align: top !important;'></img>" :
                        (($spoj->doprava == 'O') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png' style='vertical-align: top !important;'></img>" :
                                (($spoj->doprava == 'A') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png' style='vertical-align: top !important;'></img>" : "")));

        $res = $res . "</td>";

        $res = $res . "<td style='padding: 5px 15px 5px 5px; width: auto; white-space: nowrap; font-weight: bold'>";
        $res = $res . (($spoj->HH < 10) ? "0" . $spoj->HH : $spoj->HH) . ":" . (($spoj->MM < 10) ? "0" . $spoj->MM : $spoj->MM);
        $res = $res . "</td>";
                                                                                               /*white-space: nowrap;*/
        $res = $res . "<td class = 'o_zastavka' style='padding: 5px 15px 5px 5px; width: 100%;  vertical-align: midle;'>";
        $res = $res . $spoj->konecna; //iconv('UTF-8', 'windows-1250', $spoj->konecna);
        $res = $res . "</td>";

        $res = $res . "</tr>";
//    $res = $res . "<div style='margin-top: 20px;'></div>";
//    $res[] = $spoj;
        $EXP[$row[0] . "_" . $row[4]] = 1;
    }
}


$respaticka = '';
$respaticka = $respaticka . "</table>";

$respaticka = $respaticka . "</td>";
$respaticka = $respaticka . "</tr>";
$respaticka = $respaticka . "</table>";
//$respaticka = $respaticka . "</div>";

$resfinal = '';

if ($res == '') {
    $reshlavicka = '';

//$reshlavicka = $reshlavicka . "<div class = 'div_pozadikomplex' style='width: auto;'>";
//$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
//$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
//$reshlavicka = $reshlavicka . "</div>";
//$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:700px; width: auto;'>";
    $reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style=' width: auto;'>";
    $reshlavicka = $reshlavicka . "<td style = 'padding: 10px 10px 10px 10px;'>";

    $reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: auto;'>";
    $reshlavicka = $reshlavicka . "<tr style = 'padding: 10px 10px 10px 10px;'>";
    $reshlavicka = $reshlavicka . "<th>" . iconv('windows-1250', 'UTF-8', $rs_odjezdy_nejede);
    $reshlavicka = $reshlavicka . "<th>";
    $reshlavicka = $reshlavicka . "<th>";
    $reshlavicka = $reshlavicka . "</tr>";
    $reshlavicka = $reshlavicka . "<tr>";
}

$resfinal = $reshlavicka . $res . $respaticka;

echo $_GET['callback'] . "(" . json_encode($resfinal) . ", " . (isset($_GET['tag']) ? ('"' . $_GET['tag'] . '"') : 'null') . ");";
?>
