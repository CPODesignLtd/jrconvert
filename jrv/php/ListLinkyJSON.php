<?php
$location = $_GET['location'];
$packet = $_GET['packet'];

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  //mysql_query("SET NAMES 'utf-8';"); //utf-8
  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

$sql = "SELECT c_linky, nazev_linky, popis FROM linky where idlocation = " . $location . " and packet = " . $packet .
         " and (JR_OD is null or JR_OD <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") and " .
         " (JR_DO is null or JR_DO >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") " .
          " and vyber = 1 order by c_linkysort";

  if (isset($_GET['ptl'])) {
    if ($_GET['ptl'] == 1)  {
    $sql = "SELECT c_linky, nazev_linky, popis FROM linky where idlocation = " . $location . " and packet = " . $packet .
         " and (JR_OD is null or JR_OD <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") and " .
         " (JR_DO is null or JR_DO >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\") " .
          " order by c_linkysort";
    }
  }

  //echo $sql . "</br>";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
//    echo $row[0] . "</br>";
//    echo (($row[2] != "") ? $row[1] . " - " . preg_replace('/,/', '|', $row[2]): $row[1]) . "</br>";
    $res[] = array(($row[0]), (($row[2] != "") ? $row[1] . " - " . preg_replace('/,/', '|', $row[2]): $row[1]));
  }

  mysql_close($p);
}

$jsonData = json_encode($res);
/*echo $jsonData . "</br>";
echo $res;*/

echo 'selfobj.' . $_GET['callback'] . '(' . $_GET['location'] . ', ' . $_GET['packet'] . ', "' . $_GET['target'] . '", ' . $jsonData . ');';
?>
