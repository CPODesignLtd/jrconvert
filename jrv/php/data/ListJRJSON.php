<?php
Header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=='OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: '.$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];
$packet = $_GET['packet'];
$linka = $_GET['linka'];
$smer = $_GET['smer'];
$tarif = $_GET['tarif'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $sql = "SELECT
                  (((zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) div 60) mod 24) AS HH,
                  mod( (
                  zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek
                  ), 60 ) AS MM,
                  spoje.pk1 as spoj_PK1,
                  spoje.pk2 as spoj_PK2,
                  spoje.pk3 as spoj_PK3,
                  spoje.pk4 as spoj_PK4,
                  spoje.pk5 as spoj_PK5,
                  spoje.pk6 as spoj_PK6,
                  spoje.pk7 as spoj_PK7,
                  spoje.pk8 as spoj_PK8,
                  spoje.pk9 as spoj_PK9,
                  spoje.pk10 as spoj_PK10,
                  case when zasspoje_pozn.pk1 is null then 0 else zasspoje_pozn.pk1 end as pk1,
                  case when zasspoje_pozn.pk2 is null then 0 else zasspoje_pozn.pk2 end as pk2,
                  case when zasspoje_pozn.dpk1 is null then 0 else zasspoje_pozn.dpk1 end as pk3,
                  case when zasspoje_pozn.dpk2 is null then 0 else zasspoje_pozn.dpk2 end as pk4,
                  case when zasspoje_pozn.dpk3 is null then 0 else zasspoje_pozn.dpk3 end as pk5,
                  case when zasspoje_pozn.dpk4 is null then 0 else zasspoje_pozn.dpk4 end as pk6,
                  case when zasspoje_pozn.dpk5 is null then 0 else zasspoje_pozn.dpk5 end as pk7,
                  case when zasspoje_pozn.dpk6 is null then 0 else zasspoje_pozn.dpk6 end as pk8,
                  case when zasspoje_pozn.dpk7 is null then 0 else zasspoje_pozn.dpk7 end as pk9,
                  case when zasspoje_pozn.dpk8 is null then 0 else zasspoje_pozn.dpk8 end as pk10,
                  case when zasspoje_pozn.dpk9 is null then 0 else zasspoje_pozn.dpk9 end as pk11,
                  spoje.chrono,
                  spoje.kurz,
                  coalesce(spoje.idkurz, -1)
                  FROM (
                  SELECT *
                  FROM spoje
                  WHERE spoje.c_linky = '" . $linka . "' " . "
                  AND spoje.smer = " . $smer . " and idlocation = " . $location . " and packet = " . $packet . "
                  ) AS spoje
                  LEFT OUTER JOIN zasspoje ON ( spoje.c_linky = zasspoje.c_linky
                  AND spoje.c_spoje = zasspoje.c_spoje AND spoje.idlocation = " . $location . " AND zasspoje.idlocation = " . $location . " AND spoje.packet = " . $packet . " AND zasspoje.packet = " . $packet . ")
                  LEFT OUTER JOIN chronometr ON ( chronometr.c_linky = spoje.c_linky
                  AND chronometr.smer = spoje.smer
                  AND chronometr.chrono = spoje.chrono
                  AND chronometr.c_tarif = " . $tarif . " and chronometr.idlocation = " . $location . " AND chronometr.packet = " . $packet . ")
                  LEFT OUTER JOIN zasspoje_pozn ON ( spoje.c_linky = zasspoje_pozn.c_linky
                  AND spoje.c_spoje = zasspoje_pozn.c_spoje
                  AND zasspoje_pozn.c_tarif = " . $tarif . " and zasspoje_pozn.idlocation = " . $location . " AND zasspoje_pozn.packet = " . $packet . ")
                  WHERE NOT chronometr.doba_jizdy = -1 and (select (sum(doba_jizdy)/count(doba_jizdy)) from chronometr left outer join zaslinky on
                  (zaslinky.idlocation = chronometr.idlocation and zaslinky.packet = chronometr.packet and zaslinky.c_linky = chronometr.c_linky and zaslinky.c_tarif = chronometr.c_tarif)
                  where ( chronometr.c_linky = '" . $linka . "'
                  AND chronometr.smer = " . $smer . "
                  AND chronometr.chrono = spoje.chrono
                  AND chronometr.idlocation = " . $location . " AND chronometr.packet = " . $packet . "
                  AND ((chronometr.smer = 0 and zaslinky.zast_a = 1) or (chronometr.smer = 1 and zaslinky.zast_b = 1))  
                  AND ((chronometr.smer = 0 and chronometr.c_tarif > " . $tarif . ") or (chronometr.smer = 1 and chronometr.c_tarif < " . $tarif . ")))) <> -1
                  ORDER BY HH, MM";

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    $res[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25]);
  }

  mysql_close($p);
}

$jsonData = json_encode($res);

if (isset ($_GET['callback'])) {
  echo $_GET['callback'] . '(' . $jsonData . ');';
} else {
  echo $jsonData;
}
?>
