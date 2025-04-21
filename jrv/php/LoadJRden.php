<?php
$clinky = $_GET['l'];
$ctarif = $_GET['t'];
$smer = $_GET['s'];
$jr = $_GET['jr'];
$j = $_GET['j'];
$index = $_GET['i'];
$ta = $_GET['ta'];
$pozn = $_GET['po'];
$loc = $_GET['loc'];
if ($pozn == "()") {
    $pozn = "(null)";
}
$move = $_GET['move'];
$pac=$_GET['pac'];

function loadChrono($jr, $clinky, $smer, $loc, $pac) {
    $dbname = 'savvy_mhdspoje';

    //                echo "Chrono = new _Chrono();";


    if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

//    mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "select max(chronometr.chrono)
                    from chronometr
                    where chronometr.c_linky = '".$clinky."' and smer = ".$smer." and idlocation = ".$loc." and packet = ".$pac;

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
        echo $jr.".resetChrono(".$row[0].");";
    //                    echo "document.write(".$row[0].");";
    }

    $result = null;
    $sql = "select chronometr.chrono, chronometr.c_zastavky, chronometr.c_tarif,
                    chronometr.doba_jizdy, chronometr.doba_pocatek
                    from chronometr
                    where chronometr.c_linky = '".$clinky."' and smer = ".$smer." and idlocation = ".$loc." and packet = ".$pac;
    if ($smer == 0) {
        $sql = $sql." order by chronometr.chrono, chronometr.c_tarif";
    } else {
        $sql = $sql." order by chronometr.chrono, chronometr.c_tarif desc";
    }

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
//                        echo "document.write(".$row[0].", ".$row[1].", ".$row[2].", ".$row[3].", ".$row[4].");";
        echo $jr.".addChronoItem(".$row[0].", ".$row[1].", ".$row[2].", ".$row[3].", ".$row[4].");";
    }
    mysql_close($p);
}

echo "document.getElementById('divJR').style.visibility = 'hidden';";
echo $jr." = new _JR('".$clinky."', ".$ctarif.", ".$smer.");";
echo $jr.".reset(".$ctarif.",".$smer.",'".$pozn."');";

$dbname = 'savvy_mhdspoje';


if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
    exit;
}

//mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
mysql_select_db($dbname);

//                echo $pozn;

$sql = "SELECT spoje.c_spoje, spoje.chrono, spoje.pk1, spoje.pk2, spoje.pk3, spoje.pk4, spoje.pk5, spoje.pk6, spoje.pk7, spoje.pk8, spoje.pk9, spoje.pk10,
                        ((zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) div 60) AS HH,
                        mod( (
                        zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek
                        ), 60 ) AS MM, chronometr.doba_jizdy,
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
                        WHERE spoje.c_linky = '".$clinky."' "."
                        AND spoje.smer = ".$smer." and idlocation = ".$loc." and packet = ".$pac."
                        AND (
                        (
                        spoje.pk1 in ".$pozn."
                        OR spoje.pk2 in ".$pozn."
                        OR spoje.pk3 in ".$pozn."
                        OR spoje.pk4 in ".$pozn."
                        OR spoje.pk5 in ".$pozn."
                        OR spoje.pk6 in ".$pozn."
                        OR spoje.pk7 in ".$pozn."
                        OR spoje.pk8 in ".$pozn."
                        OR spoje.pk9 in ".$pozn."
                        OR spoje.pk10 in ".$pozn."
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc." and packet = ".$pac."
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
                        AND spoje.c_spoje = zasspoje.c_spoje AND spoje.idlocation = ".$loc." AND zasspoje.idlocation = ".$loc." AND spoje.packet = ".$pac." AND zasspoje.packet = ".$pac.")
                        LEFT OUTER JOIN chronometr ON ( chronometr.c_linky = spoje.c_linky
                        AND chronometr.smer = spoje.smer
                        AND chronometr.chrono = spoje.chrono
                        AND chronometr.c_tarif = ".$ctarif." and chronometr.idlocation = ".$loc." AND chronometr.packet = ".$pac.")
                        LEFT OUTER JOIN zasspoje_pozn ON ( spoje.c_linky = zasspoje_pozn.c_linky
                        AND spoje.c_spoje = zasspoje_pozn.c_spoje
                        AND zasspoje_pozn.c_tarif = ".$ctarif." and zasspoje.idlocation = ".$loc." AND zasspoje_pozn.packet = ".$pac.")
                        WHERE NOT chronometr.doba_jizdy = -1 and (select (sum(doba_jizdy)/count(doba_jizdy)) from chronometr
                        where ( chronometr.c_linky = '".$clinky."'
                        AND chronometr.smer = ".$smer."
                        AND chronometr.chrono = spoje.chrono
                        AND idlocation = ".$loc." AND packet = ".$pac."
                        AND ((chronometr.smer = 0 and chronometr.c_tarif > ".$ctarif.") or (chronometr.smer = 1 and chronometr.c_tarif < ".$ctarif.")))) <> -1                        
                        ORDER BY HH, MM";
$sql1 = "SELECT c_kodu, showing FROM pevnykod where idlocation = ".$loc." and packet = ".$pac." order by c_kodu";

$result = mysql_query($sql1);
//                echo "alert('poznamky');";
$poznamky = array();
while ($row = mysql_fetch_row($result)) {
    $pozn = array();
    for($i = 0; $i < 2; $i++) {
        $pozn[] = $row[$i];
    //                        echo "alert('".$row[$i]."');";
    }
    $poznamky[] = $pozn;
}
//echo "alert('END poznamky');";
$result = mysql_query($sql);

try {
    while ($row = mysql_fetch_row($result)) {
        if ($row[13] != null) {
        //                        echo "alert('row12 = ".$row[12]."');";
            echo "cas = new JRColumn();";
            if ($row[28] == null) {
              echo "cas.setOdjezd(".$row[13].", ".$row[26].", ".$row[27].", null);";
            } else {
              echo "cas.setOdjezd(".$row[13].", ".$row[26].", ".$row[27].", '".$row[28]."');";
            }
            echo "cas.setChrono(".$row[1].");";
            echo $jr.".addColumn(".$row[12].", cas);";
            for($pk = 2; $pk < 12; $pk++) {
                if ($poznamky[$row[$pk]/* - 1*/][1/*4*/] == 1) {
                    echo "cas.addPoznamka(".$row[$pk].");";
                }
            }
            for($pk = 15; $pk < 26; $pk++) {
                if ($poznamky[$row[$pk]/* - 1*/][1/*4*/] == 1) {
                    echo "cas.addPoznamka(".$row[$pk].");";
                }
            }
        }
    }
} catch (Exception $e) {/*echo "cas = new JRColumn();";*/}
mysql_close($p);
loadChrono($jr, $clinky, $smer, $loc, $pac);
trasa_linky($clinky, $smer, $jr, $j, $index, $ta, $loc, $poznamky, $move, $pac);

function trasa_linky($clinky, $smer, $jr, $j, $index, $ta, $loc, $poznamky, $move, $pac) {
    $dbname = 'savvy_mhdspoje';

    if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

//    mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);
    $sql = "select zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev,
                    zaslinky.pk1, zaslinky.pk2, zaslinky.pk3, zastavky.pk1 as pk4,
                    zastavky.pk2 as pk5, zastavky.pk3 as pk6, zastavky.pk4 as pk7,
                    zastavky.pk5 as pk8, zastavky.pk6 as pk9, A1_Tarif, A2_Tarif, B1_Tarif, B2_Tarif  from zaslinky
                    left outer join zastavky on
                    (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet)
                    where zaslinky.idlocation = ".$loc." and zaslinky.packet = ".$pac." and zaslinky.c_linky = '".$clinky."'";
    if ($smer == 0) {
        $sql = $sql." order by zaslinky.c_tarif";
    } else {
        $sql = $sql." order by zaslinky.c_tarif desc";
    }

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
        echo "var poznamky = new TPoznamky();";
        for($pk = 3; $pk < 12; $pk++) {
            if ($row[$pk] != 0) {
                echo "if (poznamky.getPoznamkaID(".$poznamky[$row[$pk]][0].") == null) {poznamky.addPoznamka(".$poznamky[$row[$pk]][0].", '"./*$poznamky[$row[$pk]][1].*/"', '"./*$poznamky[$row[$pk]][2].*/"', null);}";
            }
        }
        echo $jr.".addTrasa(".$row[0].", ".$row[1].", '".$row[2]."', '".$row[12]."', '".$row[13]."', '".$row[14]."', '".$row[15]."', poznamky);";
    }
    //                echo "vypisJR()";
    //                echo "j.removeObj(0);";
    //                echo $ta." = null;";
    echo $j." = new JRTab(".$jr.", 't', 50, 200, 0, ".$move.");";
    //                echo "document.write(".$i.");";
    //                echo $j.".refreshObj(".$index.", 5, tabulka);";
    //                echo $j.".showObj(".$index.");";
    //                echo "ntag = document.getElementById('jr');";
    echo $j.".show('jr');";    
    mysql_close($p);
}

?>
