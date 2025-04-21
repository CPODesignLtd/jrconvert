<?php

$linka = $_GET['linka'];
$smer = $_GET['smer'];
$location = $_GET['location'];
$packet = $_GET['packet'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {

  mysql_query("SET CHARACTER SET UTF8");
  mysql_select_db($dbname);

/*  $sql = "select zaslinky.c_tarif, zastavky.loca, zastavky.locb
          from zaslinky
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "'
          ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";*/

  $sql = "select zaslinky.c_tarif, zastavky.loca, zastavky.locb, zastavky.nazev, st.stavi, (select nazev_linky from linky where idlocation = " . $location . " and packet = " . $packet . " and c_linky = '" . $linka . "')
            from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
            from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and idlocation = " . $location . " and
            packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
            case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and
            idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
            doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $linka . "' and idlocation = " . $location . " and packet = " . $packet . " and
            smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st
            on (zaslinky.c_tarif = st.c_tarif)
            left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
            zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
            where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and ((" . $smer . "=0 and zaslinky.zast_a = 1) or (" . $smer . "=1 and zaslinky.zast_b = 1))
            ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";
/*  $sql = "select zaslinky.c_tarif, zastavky.nazev, st.stavi, (select ((sum(chronometr.doba_jizdy) div count(chronometr.doba_jizdy))) from chronometr where
          chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $linka . "'
          and chronometr.c_tarif = zaslinky.c_tarif and smer = " . $smer . ") as stavi
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and idlocation = " . $location . " and
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and
          idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $linka . "' and idlocation = " . $location . " and packet = " . $packet . " and
          smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st
          on (zaslinky.c_tarif = st.c_tarif)
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "'
          ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";*/

  /*  $sql = "SELECT zaslinky.c_tarif, zastavky.nazev FROM zaslinky LEFT OUTER JOIN zastavky on
    (zastavky.idlocation = " . $location . " AND zastavky.packet = " . $packet . " AND zaslinky.c_zastavky = zastavky.c_zastavky)
    WHERE zaslinky.idlocation = " . $location . " AND zaslinky.packet = " . $packet . " AND zaslinky.c_linky = '" . $linka . "'
    ORDER BY CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END"; */

  $result = mysql_query($sql);

  $res = "";

  while ($row = mysql_fetch_row($result)) {
    //$res[] = array($row[0], preg_replace('/,/', '|', $row[1]), $row[2]);
    $res[] = array($row[0], $row[1], $row[2], preg_replace('/,/', '|', $row[3]), $row[4], $row[5]);
  }
  mysql_close($p);
}

$jsonData = json_encode($res);

echo 'selfobj.' . $_GET['callback'] . '(' . $jsonData . ');';
?>