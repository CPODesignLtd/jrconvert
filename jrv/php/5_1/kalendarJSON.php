<?php

$res = '';
// prvotni inicializace, nastavime aktualni cas,  mesic a rok
$date = time();
$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);

$pday = date('d', $date);
$pmonth = date('m', $date);
$pyear = date('Y', $date);

// pokud v GETu existuje mesic ci rok, tak "aktualni" mesic a rok je ten z URL
if ($_GET["year"]) {
  $year = (int) $_GET["year"];
}
if ($_GET["month"]) {
  $month = (int) $_GET["month"];
}
if ($_GET["day"]) {
  $day = (int) $_GET["day"];
}

if ($_GET["pyear"]) {
  $pyear = (int) $_GET["pyear"];
}
if ($_GET["pmonth"]) {
  $pmonth = (int) $_GET["pmonth"];
}
if ($_GET["pday"]) {
  $pday = (int) $_GET["pday"];
}

$lang = 'cz';
if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
}

// pro dalsi zobrazeni potrebujeme prvni den v mesici, pocet dni v mesici ...
$first_day = mktime(0, 0, 0, $month, 1, $year);
$title = date('n', $first_day);
$day_of_week = date('D', $first_day);
$days_in_month = date('t', $first_day);


// ceske mesice
if ($lang == 'cz') {
$cz_months = array(
    1 => "Leden",
    "Únor",
    "Březen",
    "Duben",
    "Květen",
    "Červen",
    "Červenec",
    "Srpen",
    "Září",
    "Říjen",
    "Listopad",
    "Prosinec"
);
}

if ($lang == 'sk') {
$cz_months = array(
    1 => "Január",
"Február",
"Marec",
"Apríl",
"Máj",
"Jún",
"Jul",
"August",
"September",
"Október",
"November",
"December"
);
}

$prevY = $nextY = $year;
$prevM = $nextM = $month;

// nastaveni odkazu pro predchozi a nasledujici mesic / rok
if ($month - 1 < 1) {
  $prevM = 12;
  $prevY--;
} else {
  $prevM = $month - 1;
};
if ($month + 1 > 12) {
  $nextM = 1;
  $nextY++;
} else {
  $nextM = $month + 1;
};

/* $prev = "<a href='?month=" . ($prevM) . "&year=" . ($prevY) . "'><<</a>";
  $next = "<a href='?month=" . ($nextM) . "&year=" . ($nextY) . "'>>></a>"; */

// timto si vyplnime v kalendari prazdne bunky, 1 den v mesici a prvniho neni vzdy pondeli ...
$emptyTD = array("Mon" => 0, "Tue" => 1, "Wed" => 2, "Thu" => 3, "Fri" => 4, "Sat" => 5, "Sun" => 6);
$blank = $emptyTD[$day_of_week];

$res = $res . "<script type=text/javascript>";
$res = $res . "function changeHeader() {
  " . $_GET['implement'] . ".setKalendar(" . $day . ", document.getElementById('" . $_GET['implement'] . "select_mesic').value, " . $nextY . ", " . $pday . ", " . $pmonth . ", " . $pyear . ", false);
  };";
$res = $res . "</script>";

// vykresleni kalendare
$res = $res . "<div class='ram_kalendar' id='div_kalendar'>";
$res = $res . "<table class='table_kalendar' id='table_kalendar'>";
$res = $res . "<tr>";
$res = $res . "<td>";
$res = $res . "<table style='width: 100%;'>";
$res = $res . "<tr>";
$res = $res . "<td style='float: left;'>";
$res = $res . "<div name='kalend' class='div_prev' onclick = '" . $_GET['implement'] . ".setKalendar(" . $day . ", " . $prevM . ", " . $prevY . ", " . $pday . ", " . $pmonth . ", " . $pyear . ", false);'></div>";
$res = $res . "</td>";
$res = $res . "<td style='text-align: justify; float: none; width: 50%;'>";
$res = $res . "<div class='div_header'>";
//$res = $res .  iconv('ISO-8859-2', 'UTF-8', $cz_months[$title]);
//$res = $res .  "<div class='div_vyber'>";
$res = $res . "<select class='vyber_kalendar' id='" . $_GET['implement'] . "select_mesic' onchange = '" . $_GET['implement'] . ".kalendarChange(" . $day . ",\"" . $_GET['implement'] . "select_mesic\", \"select_rok\", " . $pday . ", " . $pmonth . ", " . $pyear . ", false);'>";
foreach ($cz_months as $key => $val) {
  if ($key == $title) {
    $res = $res . "<option selected='selected' value=" . $key . ">" . iconv('UTF-8', 'UTF-8', $val) . "</option>";
  } else {
    $res = $res . "<option value=" . $key . ">" . iconv('UTF-8', 'UTF-8', $val) . "</option>";
  }
}
$res = $res . "</select>";
//$res = $res .  "</div>";

$res = $res . "</div>";
$res = $res . "</td>";
$res = $res . "<td style='text-align: justify; float: none; width: 50%;'>";
$res = $res . "<div class='div_header'>";
//$res = $res .  iconv('ISO-8859-2', 'UTF-8', $year);
$res = $res . "<select class='vyber_kalendar' id='select_rok' onchange = '" . $_GET['implement'] . ".kalendarChange(" . $day . ",\"" . $_GET['implement'] . "select_mesic\", \"select_rok\", " . $pday . ", " . $pmonth . ", " . $pyear . ", false);'>";
for ($rok = $year - 1; $rok < $year + 10; $rok++) {
  if ($rok == $year) {
    $res = $res . "<option selected='selected' value=" . $rok . ">" . iconv('UTF-8', 'UTF-8', $rok) . "</option>";
  } else {
    $res = $res . "<option value=" . $rok . ">" . iconv('UTF-8', 'UTF-8', $rok) . "</option>";
  }
}
$res = $res . "</select>";
$res = $res . "</div>";
$res = $res . "</td>";
$res = $res . "<td style='float: right;'>";
$res = $res . "<div class='div_next' onclick = '" . $_GET['implement'] . ".setKalendar(" . $day . ", " . $nextM . ", " . $nextY . ", " . $pday . ", " . $pmonth . ", " . $pyear . ", false);'></div>";
$res = $res . "</td>";
$res = $res . "</tr>";
$res = $res . "</table>";
$res = $res . "</td>";
$res = $res . "</tr>";
$res = $res . "<tr>";
$res = $res . "<td style='vertical-align: middle; text-align: center;'>";
$res = $res . "<table class='table_dny'>";
$res = $res . "<tr style='border: 0px 0px 1px 0px; border-bottom-style: solid; border-bottom-width: 1px;'>";
if ($lang == 'sk') {
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Po") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Ut") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "St") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Št") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Pi") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "So") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Ne") . "</a></div></td>";
}
if ($lang == 'cz') {
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Po") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Út") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "St") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Čt") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Pá") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "So") . "</a></div></td>";
  $res = $res . "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('UTF-8', 'UTF-8', "Ne") . "</a></div></td>";
}
$res = $res . "</tr>";

$day_count = 1;

$res = $res . "<tr>";

// zde prave zjistime pocet prazdnych bunek pred 1 dnem v mesici
while ($blank > 0) {
  $res = $res . "<td></td>";
  $blank--;
  $day_count++;
}

$day_num = 1;


// veskere dny v kalendari
while ($day_num <= $days_in_month) {

  if (($day_num == $pday) && ($month == $pmonth) && ($year == $pyear)) {
    $res = $res . "<td id='vyber_day' class='today' onclick = '" . $_GET['implement'] . ".setKalendar(" . $day_num . ", " . $month . ", " . $year . ", " . $pday . ", " . $pmonth . ", " . $year . ", true);'>" . iconv('ISO-8859-2', 'UTF-8', $day_num) . "</td>";
  } else {
    $res = $res . "<td id='vyber_day' class='days' onclick = '" . $_GET['implement'] . ".setKalendar(" . $day_num . ", " . $month . ", " . $year . ", " . $day . ", " . $pmonth . ", " . $pyear . ", true);'>" . iconv('ISO-8859-2', 'UTF-8', $day_num) . "</td>";
  }




  $day_num++;
  $day_count++;

  if ($day_count > 7) {
    $res = $res . "</tr>";
    $res = $res . "<tr>";
    $day_count = 1;
  }
}


// timto zajistime spravne zobrazeni kalendare a dopocitani prazdnych bunek
while ($day_count > 1 && $day_count <= 7) {
  $res = $res . "<td></td>";
  $day_count++;
}

$res = $res . "</tr>";
$res = $res . "</table>";
$res = $res . "</td>";
$res = $res . "</tr>";
$res = $res . "</table>";
$res = $res . "</div>";

//tagName, month, year, day, pmonth, pyear, pday, hide, data
echo $_GET['implement'] . "." . $_GET['callback'] . "('" . $_GET['target'] . "', " . $day . ", " . $month . ", " . $year . ", " . $pday . ", " . $pmonth . ", " . $pyear . ", " . $_GET['hide'] . ", " . json_encode($res) . ");";
//echo json_encode($res);
?>
