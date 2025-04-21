<?php

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

// pro dalsi zobrazeni potrebujeme prvni den v mesici, pocet dni v mesici ...
$first_day = mktime(0, 0, 0, $month, 1, $year);
$title = date('n', $first_day);
$day_of_week = date('D', $first_day);
$days_in_month = date('t', $first_day);


// ceske mesice
$cz_months = array(
    1 => "Leden",
    "Únor",
    "Bøezen",
    "Duben",
    "Kvìten",
    "Èerven",
    "Èervenec",
    "Srpen",
    "Záøí",
    "Øíjen",
    "Listopad",
    "Prosinec"
);

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

echo "<script type=text/javascript>";
echo "function changeHeader() {
  setKalendar(document.getElementById('select_mesic').value, " . $nextY . ", " . $day . ", " . $pmonth . ", " . $pyear . ", " . $pday . ", false); 
  };";
echo "</script>";

// vykresleni kalendare
echo "<div class='ram_kalendar' id='div_kalendar'>";
echo "<table class='table_kalendar' id='table_kalendar'>";
echo "<tr>";
echo "<td>";
echo "<table style='width: 100%;'>";
echo "<tr>";
echo "<td style='float: left;'>";
echo "<div name='kalend' class='div_prev' onclick = 'setKalendar(" . $prevM . ", " . $prevY . ", " . $day . ", " . $pmonth . ", " . $pyear . ", " . $pday . ", false);'></div>";
echo "</td>";
echo "<td style='text-align: justify; float: none; width: 50%;'>";
echo "<div class='div_header'>";
//echo iconv('ISO-8859-2', 'UTF-8', $cz_months[$title]);
//echo "<div class='div_vyber'>";
echo "<select class='vyber_kalendar' id='select_mesic' onchange = 'kalendarChange(\"select_mesic\", \"select_rok\", " . $day . ", " . $pmonth . ", " . $pyear . ", " . $pday . ", false);'>";
foreach ($cz_months as $key => $val) {
  if ($key == $title) {
    echo "<option selected='selected' value=" . $key . ">" . iconv('ISO-8859-2', 'UTF-8', $val) . "</option>";
  } else {
    echo "<option value=" . $key . ">" . iconv('ISO-8859-2', 'UTF-8', $val) . "</option>";
  }
}
echo "</select>";
//echo "</div>";

echo "</div>";
echo "</td>";
echo "<td style='text-align: justify; float: none; width: 50%;'>";
echo "<div class='div_header'>";
//echo iconv('ISO-8859-2', 'UTF-8', $year);
echo "<select class='vyber_kalendar' id='select_rok' onchange = 'kalendarChange(\"select_mesic\", \"select_rok\", " . $day . ", " . $pmonth . ", " . $pyear . ", " . $pday . ", false);'>";
for ($rok = $year - 1; $rok < $year + 10; $rok++) {
  if ($rok == $year) {
    echo "<option selected='selected' value=" . $rok . ">" . iconv('ISO-8859-2', 'UTF-8', $rok) . "</option>";
  } else {
    echo "<option value=" . $rok . ">" . iconv('ISO-8859-2', 'UTF-8', $rok) . "</option>";
  }
}
echo "</select>";
echo "</div>";
echo "</td>";
echo "<td style='float: right;'>";
echo "<div class='div_next' onclick = 'setKalendar(" . $nextM . ", " . $nextY . ", " . $day . ", " . $pmonth . ", " . $pyear . ", " . $pday . ", false);'></div>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<table class='table_dny'>";
echo "<tr style='border: 0px 0px 1px 0px; border-bottom-style: solid; border-bottom-width: 1px;'>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "Po") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "Út") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "St") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "Èt") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "Pá") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "So") . "</a></div></td>";
echo "<td class='td_dny'><div class='div_ram_den'><a>" . iconv('ISO-8859-2', 'UTF-8', "Ne") . "</a></div></td>";
echo "</tr>";

$day_count = 1;

echo "<tr>";

// zde prave zjistime pocet prazdnych bunek pred 1 dnem v mesici 
while ($blank > 0) {
  echo "<td></td>";
  $blank--;
  $day_count++;
}

$day_num = 1;


// veskere dny v kalendari
while ($day_num <= $days_in_month) {

  if (($day_num == $pday) && ($month == $pmonth) && ($year == $pyear)) {
    echo "<td id='vyber_day' class='today' onclick = 'setKalendar(" . $month . ", " . $year . ", " . $day_num . ", " . $pmonth . ", " . $pyear . ", " . $day . ", true);'>" . iconv('ISO-8859-2', 'UTF-8', $day_num) . "</td>";
  } else {
    echo "<td id='vyber_day' class='days' onclick = 'setKalendar(" . $month . ", " . $year . ", " . $day_num . ", " . $pmonth . ", " . $pyear . ", " . $day . ", true);'>" . iconv('ISO-8859-2', 'UTF-8', $day_num) . "</td>";
  }




  $day_num++;
  $day_count++;

  if ($day_count > 7) {
    echo "</tr>";
    echo "<tr>";
    $day_count = 1;
  }
}


// timto zajistime spravne zobrazeni kalendare a dopocitani prazdnych bunek
while ($day_count > 1 && $day_count <= 7) {
  echo "<td></td>";
  $day_count++;
}

echo "</tr>";
echo "</table>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</div>";
?>
