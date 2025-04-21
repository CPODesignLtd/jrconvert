<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];
$passport = $_GET['passport'];
$passportName = $_GET['passportname'];

if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
} else {
  $lang = 'cz';
}
if ($lang == '') {
  $lang = 'cz';
}

if ($lang == 'cz') {
  require_once '../lib/CZlang.php';
}

if ($lang == 'sk') {
  require_once '../lib/SKlang.php';
}

$dbname = 'savvy_mhdspoje';

class TSpoj {

  var $nazev_linky = null;
  var $doprava = null;
  var $c_tarif = null;
  var $smer = null;
  var $cas = null;
  var $konecna = null;
  var $delay = null;

}

if ($location == 6) {
    $resdata = file_get_contents("https://skeleton.dpb.sk/Infotainment/Public/DepartureBoard?Passport=" . $passport . "&Banister=null&_ticket=973e3385-98cc-47a2-bb26-993fa41f26d2");
} else {                        
  $resdata = null;
}

$resdata = json_decode($resdata, true);

$res = '';

$reshlavicka = '';

//$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
//$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
//$reshlavicka = $reshlavicka . "</div>";
$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<td>";

$reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<tr>";
$reshlavicka = $reshlavicka . "<td colspan='4' style='padding-bottom: 10px;'><div style='margin-top: 20px; border-bottom: 1px solid #dadada; font-size: 60px; width: 100%; text-align: center; color: yellow; background-color: black;'><b>" . iconv('windows-1250', 'windows-1250', $passportName) . "</b></div></td>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<b><tr style='background-color: black; color: yellow; font-size: 30px;'>";
$reshlavicka = $reshlavicka . "<th style='width: 10%; text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_linka) . "</th>";
$reshlavicka = $reshlavicka . "<th style='width: 10%; text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_odjezd) . "</th>";
$reshlavicka = $reshlavicka . "<th style='width: 10%; text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_delay) . "</th>";
$reshlavicka = $reshlavicka . "<th style='text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_smer) . "</th></b>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr><td colspan='4'><div style='padding-top: 20px; border-top: 1px solid #dadada;'></div></td></tr>";
$reshlavicka = $reshlavicka . "<tr>";

for ($i = 0; $i < count($resdata["Departures"]); $i++) {

    $res = $res . "<b><tr style='background-color: black; color: yellow; font-size: 40px;'>";
    $res = $res . "<td style='text-align: center; width: auto; padding: 0px 15px 0px 0px;'>";
//    $res = $res . "<a class = 'popisek' style='font-size: 18px;'>";
    $res = $res . $resdata['Departures'][$i]['LineName'];
//    $res = $res . "</a>";
    $res = $res . "</td>";
/*    $res = $res . "<td style='ttext-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
    $res = $res . (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png' style='vertical-align: top !important;'></img>" :
                    (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png' style='vertical-align: top !important;'></img>" :
                            (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png' style='vertical-align: top !important;'></img>" : "")));

    $res = $res . "</td>";*/

    $res = $res . "<td style='text-align: center; padding: 5px 15px 5px 5px; width: auto; white-space: nowrap; font-weight: bold'>";
    $res = $res . $resdata['Departures'][$i]['DepartureTime'];
    $res = $res . "</td>";

    $res = $res . "<td style='text-align: center; padding: 5px 15px 5px 5px; width: auto; white-space: nowrap; font-weight: bold'>";
    $res = $res . $resdata['Departures'][$i]['Delay'];
    $res = $res . "</td>";

    $res = $res . "<td style='text-align: center; padding: 5px 15px 5px 5px; width: 100%; white-space: nowrap; vertical-align: midle;'>";
    $res = $res . $resdata['Departures'][$i]['FinalStationName'];
    $res = $res . "</td>";

    $res = $res . "</tr></b>";
}


$respaticka = '';
$respaticka = $respaticka . "</table>";

$respaticka = $respaticka . "</td>";
$respaticka = $respaticka . "</tr>";
$respaticka = $respaticka . "</table>";

$resfinal = '';

if ($res == '') {
  $reshlavicka = '';
  $res = "<p>" . iconv('windows-1250', 'UTF-8', $rs_nejede) . "</p>";
  $respaticka = '';
}

$resfinal = $reshlavicka . $res . $respaticka;

$resfinal = json_encode($resfinal);

echo $_GET['callback'] . "(" . $resfinal . ");"

?>