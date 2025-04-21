<?php
error_reporting(0);
require_once '../../lib/param.php';
require_once '../../lib/functions.php';
require_once 'Vector.php';

$linka = $_GET['linka'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$sloupce = null;

if (!($p = mysql_connect($con_server, $con_db, $con_pass))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

//  mysql_query("SET NAMES 'utf-8';");
//  mysql_select_db($dbname);

  $sql = "SELECT nazev_sloupce, idtimepozn FROM jrtypes where idlocation = " . $location . " and packet = " . $packet . " order by c_sloupce";

  $result = mysql_query($sql);

  while ($row = mysql_fetch_row($result)) {
    $sql_vargrf = "SELECT c_kodu from jrvargrfs where idtimepozn = " . $row[1];
    $result_vargrf = mysql_query($sql_vargrf);

    $kodpozn = null;
    while ($row_vargrf = mysql_fetch_row($result_vargrf)) {

      $sql_kodpozn = "select bcode(" . $location . ", " . $packet . ", " . $row_vargrf[0] . ");";

      $result_kodpozn = mysql_query($sql_kodpozn);

      while ($data = mysql_fetch_row($result_kodpozn)) {
        $kodpozn += $data[0];
      }
    }

    $row[] = $kodpozn;  //pozice 2
    $sloupce[] = $row;
  }

  $sql_linka = "select * from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = '" . $linka . "';";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_linka);

  $sql_linka = $query1->fetch_row();

  mysql_close($p);
}

$max = -1;
for ($i = 0; $i < count($sloupce); $i++) {
  $sql_kurzy = "call seznamKurzuLinky('" . $linka . "', " . $sloupce[$i][2] . ", " . $location . ", " . $packet . ");";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_kurzy);

  while ($row_kurzy = $query1->fetch_row()) {
    $kurzy[$i][] = $row_kurzy;
  }
  if ($max < count($kurzy[$i])) {
    $max = count($kurzy[$i]);
  }
}

$res = '';


/*$res = $res . "<div class='div_pozadikomplex' style='width: auto; min-width: 200px;'>";
$res = $res . "<div id='movedivSeznam' class='movediv'>";
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div>";*/
$res = $res . "<table id='tablejrSeznam' class = 'tableseznamkurzy'>";

$res = $res . "<tr class='licha'>";

for ($i = 0; $i < count($sloupce); $i++) {
  $res = $res . "<td class = 'td_sloupec'>"; //style='text-align: left; width: auto;'
  $res = $res . "<div class = 'div_ram'>";
  $res = $res . $sloupce[$i][0]; // . ' = ' . $sloupce[$i][2] . '</br>';
  $res = $res . "</div>";
  $res = $res . "</td>";
}

$res = $res . "</tr>";

for ($i = 0; $i < $max; $i++) {
  if ($i % 2 == 0) {
    $res = $res . "<tr class='suda'>";
  } else {
    $res = $res . "<tr class='licha'>";
  }
  for ($ii = 0; $ii < count($sloupce); $ii++) {
    $res = $res . "<td class='td_kurz' onClick='selfobj.changeZIndexJR(); getVozak(".$location.",".$packet.",\"".$linka."\",".$kurzy[$ii][$i][2].",\"".$kurzy[$ii][$i][1]."\",".$sloupce[$ii][2].");'>"; //style='text-align: left; width: auto;'
    if ($kurzy[$ii][$i][1] != '') {
      $res = $res . iconv('UTF-8', 'UTF-8', 'kurz ') . "&nbsp;" . $sql_linka[1] . ' / ' . $kurzy[$ii][$i][1];
    }
    $res = $res . "</td>";
  }
  $res = $res . "</tr>";
}

$res = $res . "</table>";
//$res = $res . "</div>";

echo $_GET['callback'] . "(" . json_encode($res) . ");";
?>
