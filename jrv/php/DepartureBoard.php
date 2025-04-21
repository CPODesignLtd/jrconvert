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


/*echo $location . "<br>";
echo $passport . "<br>";
echo $lang . "<br>";*/

//require_once '../lib/SKlang.php';

/*if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "select MAP, SKELETON_URL, SKELETON_USERNAME, SKELETON_TICKET from location
            where idlocation = " . $location;

  $result = mysql_query($sql);
  $row = mysql_fetch_row($result);
  mysql_close($p);
}*/

/*if (($row[0] == 0) || ($row[0] == '')) {
  $res = "";
} else {
  $res = "";
    $res = $row[1] . "?_ticket=" . $row[3] . "&style=position:absolute;width:100%;height:500px;";
}
*/

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
//  $resdata = file_get_contents("https://skeleton.dpb.sk/Timetables/Public/DepartureBoard?Passport=" . $passport . "&Banister=null&_ticket=973e3385-98cc-47a2-bb26-993fa41f26d2");
    $resdata = file_get_contents("https://skeleton.dpb.sk/Infotainment/Public/DepartureBoard?Passport=" . $passport . "&Banister=null&_ticket=973e3385-98cc-47a2-bb26-993fa41f26d2");
} else {                        
  $resdata = null;
}

$resdata = json_decode($resdata, true);

$res = '';

$reshlavicka = '';

$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$reshlavicka = $reshlavicka . "</div>";
$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<td>";

$reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<tr>";
$reshlavicka = $reshlavicka . "<td colspan='5'><div style='margin-top: 20px; border-bottom: 1px solid #dadada;'></div></td>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr>";
$reshlavicka = $reshlavicka . "<th colspan='2' style='text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_linka) . "</th>";
$reshlavicka = $reshlavicka . "<th style='text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_odjezd) . "</th>";
$reshlavicka = $reshlavicka . "<th style='text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_delay) . "</th>";
$reshlavicka = $reshlavicka . "<th style='text-align: center;'>" . iconv('windows-1250', 'UTF-8', $rs_smer) . "</th>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr><td colspan='5'><div style='padding-top: 20px; border-top: 1px solid #dadada;'></div></td></tr>";
$reshlavicka = $reshlavicka . "<tr>";

//echo count($resdata);

//echo implode(", ", $resdata);

//echo count($resdata["Departures"]);

for ($i = 0; $i < count($resdata["Departures"]); $i++) {
    /*$spoj = new TSpoj();
    $spoj->c_linky = $row[0];
    $spoj->nazev_linky = $row[1];
    $spoj->doprava = $row[2];
    $spoj->c_tarif = $row[3];
    $spoj->smer = $row[4];
    $spoj->HH = intval(($row[5] / 60) % 24);
    $spoj->MM = intval($row[5] % 60);
    $spoj->id_spoje = $row[6];
    $spoj->chrono = $row[7];*/
    //echo $resdata["Departures"][$i]['FinalStaion'] . "<br>";

  /*<tr>
  <td style="text-align: right; width: auto; padding: 0px 15px 0px 0px;"><a class="popisek" style="font-size: 18px;">    23</a></td>
  <td style="text-align: right; width: auto; padding: 0px 15px 0px 0px;"><img src="http://www.mhdspoje.cz/jrw50/image/autobus_small.png" style="vertical-align: top !important;"></td>
  <td style="padding: 5px 15px 5px 5px; white-space: normal;"><img class="imgmap" src="http://www.mhdspoje.cz/jrw50/css/prapor.png" onclick="if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, 17.03164672, 48.20067596, 9);">&nbsp;</td><td class="sp_zastavka" style="width: auto; padding: 5px 15px 5px 5px; white-space: normal;">Agátová, Dúbravka, Bratislava ( 101 ) </td><td style="padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: right;">14:00</td><td style="padding: 5px 15px 5px 5px; white-space: normal;"><img class="imgmap" src="http://www.mhdspoje.cz/jrw50/css/prapor.png" onclick="if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, 17.03772163, 48.18500518, 10);">&nbsp;</td><td class="sp_zastavka" style="width: auto; padding: 5px 15px 5px 5px; white-space: normal;">Alexyho, Dúbravka, Bratislava ( 101 ) </td><td style="padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: right;">14:06</td></tr>*/

    $res = $res . "<tr>";
    $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
    $res = $res . "<a class = 'popisek' style='font-size: 18px;'>";
    $res = $res . /*iconv('UTF-8', 'windows-1250', */$resdata['Departures'][$i]['LineName']; //iconv('UTF-8', 'windows-1250', $spoj->nazev_linky);
    $res = $res . "</a>";
    $res = $res . "</td>";
    $res = $res . "<td style='ttext-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
    $res = $res . (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png' style='vertical-align: top !important;'></img>" :
                    (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png' style='vertical-align: top !important;'></img>" :
                            (($resdata['Departures'][$i]['TransportTypeName']) ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png' style='vertical-align: top !important;'></img>" : "")));

    $res = $res . "</td>";

    $res = $res . "<td style='padding: 5px 15px 5px 5px; width: auto; white-space: nowrap; font-weight: bold'>";
    $res = $res . /*iconv('UTF-8', 'windows-1250', */$resdata['Departures'][$i]['DepartureTime'];
    $res = $res . "</td>";

    $res = $res . "<td style='padding: 5px 15px 5px 5px; width: auto; white-space: nowrap; font-weight: bold'>";
    $res = $res . /*iconv('UTF-8', 'windows-1250', */$resdata['Departures'][$i]['Delay'];
    $res = $res . "</td>";

    $res = $res . "<td class = 'o_zastavka' style='padding: 5px 15px 5px 5px; width: 100%; white-space: nowrap; vertical-align: midle;'>";
    $res = $res . /*iconv('UTF-8', 'windows-1250', */$resdata['Departures'][$i]['FinalStationName'];
    $res = $res . "</td>";

    $res = $res . "</tr>";
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
/*  $reshlavicka = '';

$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$reshlavicka = $reshlavicka . "</div>";
$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style='width: auto;'>";
$reshlavicka = $reshlavicka . "<td>";

$reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<tr>";
$reshlavicka = $reshlavicka . "<th colspan='4' style='text-align: left;'>nic nejede</th>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr>";*/

/*$reshlavicka = $reshlavicka . "<div id='movedivSeznam' class='movediv'>";
$reshlavicka = $reshlavicka . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$reshlavicka = $reshlavicka . "</div>";
$reshlavicka = $reshlavicka . "<table id='tablejrSeznam' class = 'tablejr' style=' width: auto;'>";
$reshlavicka = $reshlavicka . "<td style = 'padding: 10px 10px 10px 10px;'>";

$reshlavicka = $reshlavicka . "<table class = 'tablejr' style='width: 100%;'>";
$reshlavicka = $reshlavicka . "<tr style = 'padding: 10px 10px 10px 10px;'>";
$reshlavicka = $reshlavicka . "<th>" . iconv('windows-1250', 'UTF-8', "v požadovaném datu již nic nejede");
$reshlavicka = $reshlavicka . "<th>";
$reshlavicka = $reshlavicka . "<th>";
$reshlavicka = $reshlavicka . "</tr>";
$reshlavicka = $reshlavicka . "<tr>";*/


}

//$resfinal = json_encode($reshlavicka) . json_encode($res) . json_encode($respaticka);

$resfinal = $reshlavicka . $res . $respaticka;

$resfinal = json_encode($resfinal);

echo $_GET['callback'] . "(" . $resfinal . ");"

?>