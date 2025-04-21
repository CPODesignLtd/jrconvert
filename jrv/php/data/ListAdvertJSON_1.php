<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    Header('Access-Control-Allow-Methods: GET');
    Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    Header('Access-Control-Max-Age: 86400');
    die;
}

$location = $_GET['location'];
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

    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    class TAdvertElement {

        var $url = null;
        var $delka = null;

    }

    $res = "";

    $sql = "select soubor, delka, headertxt, txt, id from reklama where idlocation = " . $location . " and show_od <= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\" and show_do >= \"" . date_format(new DateTime($datumJR), 'Y-m-d') . "\"";
//    echo $sql;
    $result = mysql_query($sql);
    while ($row = mysql_fetch_row($result)) {
        $addAdvert = new TAdvertElement();
        if (($row[2] != '') || ($row[3] != '')) {
            $addAdvert->url = 'http://www.mhdspoje.cz/jrw50/php/data/AdvertForMobile_1.php?id=' . $row[4];
        } else {
            $addAdvert->url = 'http://www.mhdspoje.cz/jrw50/php/data/AdvertForMobile_1.php?url=http://www.mhdspoje.cz/jrw50/prepareadmin/reklamadata/' . $location . '/' . $row[0];
        }
        $addAdvert->delka = $row[1];
        $res[] = $addAdvert;
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
