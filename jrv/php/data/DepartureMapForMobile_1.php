<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    Header('Access-Control-Allow-Methods: GET');
    Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    Header('Access-Control-Max-Age: 86400');
    die;
}

$location = $_GET['location'];

$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
} else {

    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "select MAP, SKELETON_URL, SKELETON_USERNAME, SKELETON_TICKET from location
            where idlocation = " . $location;

    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);
    mysql_close($p);
}

if (($row[0] == 0) || ($row[0] == '')) {
    $res = "";
} else {
    $res = "";
//  $url = 'http:' . $row[1] . '?_ticket=' . $row[3] . '&location=' . $location;
//$curl = curl_init($url);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//$res = (curl_exec($curl));
//$json = json_decode($res);
//  $res = file_get_contents(/*'http://skeleton.dpmdas.cz/Timetables/Timetables/DepartureMapForMobile?location=2'*/$url);
//  $res = file_get_contents('http://www.mhdspoje.cz/DepartureMap.html');
//  if ($location == 2) {
    // $res = file_get_contents("http://skeleton.dpmdas.cz/Timetables/Timetables/DepartureMapForMobile?_ticket=5755CF4D-5D20-4A3F-AAFE-4A2A1546B5B3&location=2");
//    header("Location: http://skeleton.dpmdas.cz/Timetables/Timetables/DepartureMapForMobile?_ticket=5755CF4D-5D20-4A3F-AAFE-4A2A1546B5B3", true, 301);
    if ($row[3] != '') {
        $res = $row[1] . "?_ticket=" . $row[3]; //"http://skeleton.dpmdas.cz/Timetables/Timetables/DepartureMapForMobile?_ticket=5755CF4D-5D20-4A3F-AAFE-4A2A1546B5B3";
    } else {
        $res = $row[1] . "?location=" . $location;
    }
//  }
}

/* $jsonData = json_encode($res);

  echo $jsonData; */
header('Location: ' . $res);
?>