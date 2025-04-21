<?php

$md5_pass = 'jrw581003';
$cook_name = 'jrw_auth';
$con_server = 'localhost:3306';
$con_host = '193.19.176.152';
$con_user = 'savvy_mhdspoje';
$con_port = 3306;
$con_db = 'savvy_mhdspoje';
$con_pass = 'V5dArsm2NuNtumCZ';
$global_connection = null;

function connect_DB(&$mysqli) {
GLOBAL  $con_host;
GLOBAL $con_user;
GLOBAL $con_port;
GLOBAL $con_db;
GLOBAL $con_pass;

  $mysqli = new mysqli($con_host, $con_user, $con_pass, $con_db, $con_port);
  if (mysqli_connect_error()) {
    $res = FALSE;
  } else {
    $mysqli->query("SET NAMES 'utf-8';");
    $res = TRUE;
  }

  return $res;
}

function close_DB(&$mysqli) {
  $mysqli->close();
}

?>
