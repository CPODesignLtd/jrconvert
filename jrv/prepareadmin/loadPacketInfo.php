<?php
error_reporting(0);
$mesiceW1250 = array("ledna", "února", "března", "dubna", "května", "června", "července", "srpna", "září", "října", "listopadu", "prosince");
require_once '../lib/param.php';
require_once '../lib/functions.php';

$connect = mysql_connect($con_server, $con_db, $con_pass);
mysql_select_db($con_db);
$sql = mysql_query("SELECT packet, jr_od, jr_do, jeplatny,
  (select count(c_sloupce) from jrtypes where idlocation=" . $_GET['location'] . " and packet = " . $_GET['packet'] . ") as pocet_sloupcu,
  (select count(c_kodu) from jrvargrfs where idtimepozn in (select idtimepozn from jrtypes where idlocation=" . $_GET['location'] . " and packet = " . $_GET['packet'] . ")) as pocet_variant
  FROM packets WHERE location=" . $_GET['location'] . " and packet = " . $_GET['packet'] . " and jr_od <= '" . $_GET['d'] . "' and jr_do >= '" . $_GET['d'] . "'");
$row = mysql_fetch_row($sql);

list($year, $month, $day) = explode('-', $row[1]);
/*$mk = mktime(0, 0, 0, $month, $day, $year);
$d_od = date('d.m.Y', $mk);*/
$d_od = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;

list($year, $month, $day) = explode('-', $row[2]);
/*$mk = mktime(0, 0, 0, $month, $day, $year);
$d_do = date('d.m.Y', $mk);*/
$d_do = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;

$res = '';

$res .= '
  <table id="table_kongresy" class="t_akce">
    <tr>
      <th>'. iconv('UTF-8', 'UTF-8', 'č. balíčku') . '</th>
      <th>'. iconv('UTF-8', 'UTF-8', 'platnost OD') . '</th>
      <th>'. iconv('UTF-8', 'UTF-8', 'platnost DO') . '</th>
      <th>'. iconv('UTF-8', 'UTF-8', 'aktivní') . '</th>
      <th>'. iconv('UTF-8', 'UTF-8', 'definice sloupců variant grafikonu') . '</th>
    </tr>
    <tr>
      <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">' . $row[0] . '</td>
      <td class="last" style="width: auto;">' . iconv('UTF-8', 'UTF-8', $d_od) . '</td>
      <td class="last" style="width: auto;">' . iconv('UTF-8', 'UTF-8', $d_do) . '</td>
      <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;' . ($row[3] == 1 ? 'background-color: #34916C': 'background-color: #B53929;') . '">' . ($row[3] == 1 ? 'ANO': 'NE') . '</td>
      <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;' . ((($row[4] > 0) && ($row[5] > 0)) ? 'background-color: #34916C;': 'background-color: #B53929;') . '">' . ((($row[4] > 0) && ($row[5] > 0)) ? 'ANO': 'NE') . '</td>
    </tr>
  </table>';

//echo '<script type="text/javascript"> document.getElementById("' . $_GET[content] . '").innerHTML = ' . json_encode($res) . '; </script>';
echo $_GET['callBack'].'('.json_encode($res).')';
?>
