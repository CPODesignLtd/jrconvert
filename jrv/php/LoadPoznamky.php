<?php
$str = $_GET['str'];
$str1 = $_GET['str1'];
$loc = $_GET['loc'];
$pac = $_GET['pac'];

echo $str." = new TPoznamky();";
echo $str1." = new TPoznamky();";
loadCasPozn($str, $loc, $pac);
loadCentralPozn($str1, $loc, $pac);

function loadCasPozn($str, $loc, $pac) {
    $dbname = 'savvy_mhdspoje';

    if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

//    mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "SELECT c_kodu, oznaceni, rezerva FROM pevnykod where caspozn = 1 and idlocation = ".$loc." and packet = ".$pac;

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
        echo $str.".addPoznamka(".$row[0].", '".$row[1]."', '".$row[2]."', null);";
    //            echo "alert('".$row[0]." - ".$row[1]."');";
    }
    echo "try { cCalendar.CasPoznamky = CasPoznamky; } catch (ex) {}";
    echo "try { cCalendar1.CasPoznamky = CasPoznamky; } catch (ex) {}";
    mysql_close($p);
}

function loadCentralPozn($str1, $loc, $pac) {
    $dbname = 'savvy_mhdspoje';

    if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

//    mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

//    $sql = "SELECT c_kodu, oznaceni, rezerva, obr FROM pevnykod where caspozn = 0 and idlocation = ".$loc." and packet = ".$pac;
    $sql = "SELECT c_kodu, oznaceni, rezerva, obr FROM pevnykod where idlocation = ".$loc." and packet = ".$pac;    

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
        if ($row[3] == '') {
            echo $str1.".addPoznamka(".$row[0].", '".$row[1]."', '".$row[2]."', null);";
        } else {
            echo $str1.".addPoznamka(".$row[0].", '".$row[1]."', '".$row[2]."', '".$row[3]."');";
        }
    //            echo "alert('".$row[2]."');";
    }
    mysql_close($p);
}
?>