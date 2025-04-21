<?php
$str = $_GET['str'];
$loc = $_GET['loc'];

echo $str." = new TPackers();";
loadpack($str, $loc);

function loadpack($str, $loc) {
    $dbname = 'savvy_mhdspoje';

    if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

//    mysql_query("SET NAMES 'latin2';");
    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "SELECT packet, date_format(jr_od, '%M %d %Y'), date_format(jr_do, '%M %d %Y'), location, jeplatny FROM packets where location = ".$loc." order by jr_od, jr_do";

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
        echo $str.".addPack(".$row[3].", ".$row[0].", '".$row[1]."', '".$row[2]."' , ".$row[4].");";
//        echo "document.write('".$row[3].", ".$row[0].", '".$row[1]."', '".$row[2]."');";
    }
//    echo "packet = ".$str.".loadPackets1(".$str.");";
//    echo "document.write(packet);";
    echo "loadPackets(".$str.");";
    mysql_close($p);
}
?>