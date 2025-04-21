<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    Header('Access-Control-Allow-Methods: GET');
    Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    Header('Access-Control-Max-Age: 86400');
    die;
}

require_once 'Vector.php';

$location = $_GET['location'];
$packet = $_GET['packet'];
$linka = $_GET['linka'];
$smer = $_GET['smer'];
$tarif = $_GET['tarif'];
if (isset($_GET['datum'])) {
    $dob1 = trim($_GET['datum']);
    list($param_day, $param_month, $param_year) = explode('_', $dob1);
    $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
    $datumJR = date('Y-m-d', $mk);
} else {
    $datumJR = date('Y-m-d');
}


$typyGrf = array("Mon" => "('X', '1')", "Tue" => "('X', '2')", "Wed" => "('X', '3')", "Thu" => "('X', '4')",
    "Fri" => "('X', '5')", "Sat" => "('6')", "Sun" => "('7', '+')");
$typyGrfDay = array(
    "Mon" => array('X', '1'),
    "Tue" => array('X', '2'),
    "Wed" => array('X', '3'),
    "Thu" => array('X', '4'),
    "Fri" => array('X', '5'),
    "Sat" => array('6'),
    "Sun" => array('7', '+'));
$typySloupcu = array(0 => array('X', '1', '2', '3', '4', '5', 'c'), 1 => array('6'), 2 => array('7', '+'));

function getVargrf($JRType) {
    $result = "";
    for ($i = 0; $i < $JRType->vargrf->size(); $i++) {
        if ($result != "") {
            $result = $result . ", ";
        }
        $result = $result . $JRType->vargrf->elementAt($i);
    }
    return "(" . $result . ")";
}

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
} else {

    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    class TPoznamkaElement {

        var $c_kodu = null;
        var $zkratka = null;
        var $popis = null;
        var $show = null;
        var $showDen = null;
        var $time = null;
        var $pic = null;
        var $sdruz = 0;
        var $I_P = 0;

    }

    class TJRTypesElement {

        var $sloupec = null;
        var $popis = null;
        var $vargrf = null;
        var $odjezdy = null;
        var $pocetsloupcu = 1;
        var $show = FALSE;
        var $active = FALSE;

    }

    class TSpoj {

        var $id_spoje = null;
        var $HH = null;
        var $MM = null;
        var $pk1, $pk2, $pk3, $pk4, $pk5, $pk6, $pk7, $pk8, $pk9, $pk10;
        var $chpk1, $chpk2, $chpk3, $chpk4, $chpk5, $chpk6, $chpk7, $chpk8, $chpk9, $chpk10, $chpk11;
        var $konecna = null;
        var $konecnac_zast = null;
        var $chrono = null;

    }

    $sql = "select * from packets where jr_od <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\" and jr_do >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\" and location = " . $location . " and jeplatny = 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);
/*    echo $sql . '<br>';
    echo $packet . ' / ' . $row[1] . '<br>';*/
    if ($packet != $row[1]) {
        $packet = $row[1];
    }
    
    $sql = "select c_linky from linky where idlocation = " . $location . " and packet = " . $packet . " and c_linky = '" . $linka . "' and jr_od <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\" and jr_do >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\"";
    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);
    if ($row[0] === $linka) {

        $TJRTypes = new Vector();
        $TPoznamky = new Vector();

        $sql = "SELECT c_kodu, oznaceni, rezerva, caspozn, showing, obr, sdruz, I_P, showing1 FROM pevnykod where idlocation = " . $location . " and packet = " . $packet . " order by c_kodu";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_row($result)) {
            $pozn = new TPoznamkaElement();
            $pozn->c_kodu = $row[0];
            $pozn->zkratka = $row[1];
            $pozn->popis = $row[2];
            $pozn->time = $row[3];
            $pozn->show = $row[4];
            $pozn->pic = $row[5];
            $pozn->sdruz = $row[6];
            $pozn->I_P = $row[7];
            $pozn->showDen = $row[8];
            $TPoznamky->addElement($pozn);
        }

        $sql = "SELECT datum, pk FROM kalendar where datum = \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\" and idlocation = " . $location . " and packet = " . $packet . " order by pk";
        $result = mysql_query($sql);

        $sloupec = new TJRTypesElement();
        $sloupec->sloupec = 1;
        $sloupec->popis = date_format(new DateTime($datumJR), 'd') . ". " . iconv('windows-1250', 'UTF-8', $mesice[(integer) (date_format(new DateTime($datumJR), 'm') - 1)]) . " " . date_format(new DateTime($datumJR), 'Y');
        $sloupec->vargrf = new Vector();

        while ($row = mysql_fetch_row($result)) {
            $sloupec->vargrf->addElement($row[1]);
        }
        if ($sloupec->vargrf->size() == 0) {
            $vargrfs = $typyGrfDay[date_format(new DateTime($datumJR), 'D')];
            for ($i = 0; $i < count($vargrfs); $i++) {
                for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
                    if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
//          $sloupec->vargrf->addElement($ii);
                        $sloupec->vargrf->addElement($TPoznamky->elementAt($ii)->c_kodu);
                        break;
                    }
                }
            }
        }
        $TJRTypes->addElement($sloupec);

        $res = "";


        for ($i = 0; $i < $TJRTypes->size(); $i++) {
            $sloupec = $TJRTypes->elementAt($i);

            $grf = getVargrf($sloupec);

            $sqlodjezd = "SELECT spoje.c_spoje, spoje.chrono,
                  (((zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) div 60) mod 24) AS HH,
                  mod( (
                  zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek
                  ), 60 ) AS MM, chronometr.doba_jizdy,
                  spoje.pk1, spoje.pk2, spoje.pk3, spoje.pk4, spoje.pk5, spoje.pk6, spoje.pk7, spoje.pk8, spoje.pk9, spoje.pk10,
                  case when zasspoje_pozn.pk1 is null then 0 else zasspoje_pozn.pk1 end as pk1,
                  case when zasspoje_pozn.pk2 is null then 0 else zasspoje_pozn.pk2 end as pk2,
                  case when zasspoje_pozn.dpk1 is null then 0 else zasspoje_pozn.dpk1 end as dpk1,
                  case when zasspoje_pozn.dpk2 is null then 0 else zasspoje_pozn.dpk2 end as dpk2,
                  case when zasspoje_pozn.dpk3 is null then 0 else zasspoje_pozn.dpk3 end as dpk3,
                  case when zasspoje_pozn.dpk4 is null then 0 else zasspoje_pozn.dpk4 end as dpk4,
                  case when zasspoje_pozn.dpk5 is null then 0 else zasspoje_pozn.dpk5 end as dpk5,
                  case when zasspoje_pozn.dpk6 is null then 0 else zasspoje_pozn.dpk6 end as dpk6,
                  case when zasspoje_pozn.dpk7 is null then 0 else zasspoje_pozn.dpk7 end as dpk7,
                  case when zasspoje_pozn.dpk8 is null then 0 else zasspoje_pozn.dpk8 end as dpk8,
                  case when zasspoje_pozn.dpk9 is null then 0 else zasspoje_pozn.dpk9 end as dpk9,
                  zasspoje.HH as pocatek_HH, zasspoje.MM as pocatek_MM,
                  spoje.kurz
                  FROM (
                  SELECT *
                  FROM spoje
                  WHERE spoje.c_linky = '" . $linka . "' " . "
                  AND spoje.smer = " . $smer . " and idlocation = " . $location . " and packet = " . $packet . "   AND spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048
                  AND (

(
                  spoje.pk1 in " . $grf . "
                  OR spoje.pk2 in " . $grf . "
                  OR spoje.pk3 in " . $grf . "
                  OR spoje.pk4 in " . $grf . "
                  OR spoje.pk5 in " . $grf . "
                  OR spoje.pk6 in " . $grf . "
                  OR spoje.pk7 in " . $grf . "
                  OR spoje.pk8 in " . $grf . "
                  OR spoje.pk9 in " . $grf . "
                  OR spoje.pk10 in " . $grf . "
                  )


                  OR
 (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )
                  )
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


            /*                 (
              spoje.pk1 in " . $grf . "
              OR spoje.pk2 in " . $grf . "
              OR spoje.pk3 in " . $grf . "
              OR spoje.pk4 in " . $grf . "
              OR spoje.pk5 in " . $grf . "
              OR spoje.pk6 in " . $grf . "
              OR spoje.pk7 in " . $grf . "
              OR spoje.pk8 in " . $grf . "
              OR spoje.pk9 in " . $grf . "
              OR spoje.pk10 in " . $grf . "
              )


              OR */

//    echo $sqlodjezd;
            $resultodjezdy = mysql_query($sqlodjezd);

            while ($row = mysql_fetch_row($resultodjezdy)) {
                $spoj = new TSpoj();
                $spoj->id_spoje = $row[0];
                $spoj->HH = $row[2];
                $spoj->MM = $row[3];
                $spoj->pk1 = $row[5];
                $spoj->pk2 = $row[6];
                $spoj->pk3 = $row[7];
                $spoj->pk4 = $row[8];
                $spoj->pk5 = $row[9];
                $spoj->pk6 = $row[10];
                $spoj->pk7 = $row[11];
                $spoj->pk8 = $row[12];
                $spoj->pk9 = $row[13];
                $spoj->pk10 = $row[14];
                $spoj->chpk1 = $row[15];
                $spoj->chpk2 = $row[16];
                $spoj->chpk3 = $row[17];
                $spoj->chpk4 = $row[18];
                $spoj->chpk5 = $row[19];
                $spoj->chpk6 = $row[20];
                $spoj->chpk7 = $row[21];
                $spoj->chpk8 = $row[22];
                $spoj->chpk9 = $row[23];
                $spoj->chpk10 = $row[24];
                $spoj->chpk11 = $row[25];
                $spoj->chrono = $row[1];

                if ($smer == 0) {
                    $sqlchrono = "select max(chronometr.c_tarif) from chronometr left outer join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) where chronometr.c_linky = '" . $linka . "' and chronometr.smer = " . $smer . " and chronometr.chrono = " . $row[1] . " and zaslinky.zast_A = 1 and chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.doba_jizdy > -1";
                } else {
                    $sqlchrono = "select min(chronometr.c_tarif) from chronometr left outer join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) where chronometr.c_linky = '" . $linka . "' and chronometr.smer = " . $smer . " and chronometr.chrono = " . $row[1] . " and zaslinky.zast_B = 1 and chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.doba_jizdy > -1";
                }

                $resultkonecna = mysql_query($sqlchrono);

                while ($rowk = mysql_fetch_row($resultkonecna)) {
                    $spoj->konecna = $rowk[0];
                    $mysqlikonecnac_zast = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
                    $mysqlikonecnac_zast->query("SET NAMES 'utf-8';");
                    $sqlkonecna = "select zaslinky.c_zastavky from zaslinky where zaslinky.c_linky = '" . $linka . "' and zaslinky.c_tarif = " . $rowk[0] . " and zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet;
                    $resultkonecnac_zast = $mysqlikonecnac_zast->query($sqlkonecna);

                    while ($rowc_zast = $resultkonecnac_zast->fetch_row()) {
                        $spoj->konecnac_zast = $rowc_zast[0];
                    }
                }

                /*      while ($rowk = $resultkonecna->fetch_row()) {
                  $spoj->konecna = $rowk[0];
                  $mysqlikonecnac_zast = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
                  $mysqlikonecnac_zast->query("SET NAMES 'utf-8';");
                  $sqlkonecna = "select zaslinky.c_zastavky from zaslinky where zaslinky.c_linky = '" . $linka . "' and zaslinky.c_tarif = " . $rowk[0]  . " and zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet;
                  $resultkonecnac_zast = $mysqlikonecnac_zast->query($sqlkonecna);

                  while ($rowc_zast = $resultkonecnac_zast->fetch_row()) {
                  $spoj->konecnac_zast = $rowc_zast[0];
                  }
                  } */

                $res[] = $spoj;
//      $res[] = array($row[0], $row[2], $row[3], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25]);
            }
        }

        /*  while ($row = mysql_fetch_row($result)) {
          $res[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23]);
          } */
    }
    mysql_close($p);
}

$jsonData = json_encode($res);

if (isset($_GET['callback'])) {
    echo $_GET['callback'] . '(' . $jsonData . ');';
} else {
    echo $jsonData;
}
?>
