<?php

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

$location = $_GET['location'];
$packet = $_GET['packet'];
$address = $_GET['address'];
$type = $_GET['type'];

$opts = array(
  'https'=>array(
    'method'=>"POST",
    'header'=>"Content-type : text/xml",
    'content' =>"location"  
  )
);

$context = stream_context_create($opts);

echo $_POST['t'];
//      echo $_POST['t1'];

/* Sends an http request to www.example.com
   with additional headers shown above */
//$url = 'https://www.mhdspoje.cz/jrw50/php/5_1/loadSearchMap1.php?location=" . $location . "&packet=" . $packet . "&address=" . $address . "&type=" . $type';
//$fp = file_get_contents($url, false, $context);

//print($context);

//print($fp);

if (!(isset($_POST['t']))) {
$resdata = file_get_contents("http://www.mhdspoje.cz/jrw50/php/5_1/loadSearchMap1.php?location=" . $location . "&packet=" . $packet . "&address=" . $address . "&type=" . $type);

print($resdata);}
?>