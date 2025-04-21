<?php

include '../lib/param.php';
include '../lib/functions.php';

function withslashes ($x)
{
	return (get_magic_quotes_gpc () == 1) ? $x : addslashes ($x);
}

function withoutslashes ($x)
{
	return (get_magic_quotes_gpc () == 1) ? stripslashes ($x) : $x;
}

function autentizovat() {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $md5_pass;
  global $cook_name;  
  
  if (empty($_COOKIE[$cook_name])) {
    $res = false;
  } else {
    list ($jmeno, $cas, $x) = explode('-', withoutslashes($_COOKIE[$cook_name]));
    $sul = substr($x, 0, 10);
    $sum = substr($x, 10);
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    mysql_query("SET NAMES 'utf-8';");
    $q = mysql_query("SELECT PASS FROM USERS WHERE NAME='" . $jmeno . "'");
    $res = true;
    if (mysql_num_rows($q) != 1) {
      $res = false;
    } else {
      if ($sum != md5($md5_pass . $cas . mysql_result($q, 0) . $sul)) {
        $res = false;
      } else {
        if (time() - 36000 > $cas) {
          $res = false;
        }
      }
    }
  }

  return $res;
}

function login($jmeno, $heslo) {
GLOBAL $con_host;
GLOBAL $con_user;
GLOBAL $con_port;
GLOBAL $con_db;
GLOBAL $con_pass;
GLOBAL $con_server;
GLOBAL $cook_name;
 
  $connect = mysqli_connect($con_server,$con_user,$con_pass,$con_db);//($con_host, $con_user, $con_pass, $con_db, $con_port);
  if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  mysqli_select_db($connect, $con_db);
  $connect->query("SET NAMES 'utf-8';");

  if (empty($_COOKIE[$cook_name])) {
    $q = $connect->query("SELECT PASS FROM USERS WHERE NAME='" . $jmeno . "' AND PASS='" . $heslo . "'");
    if ($q->num_rows == 1) {
      $znaky = '0123456789abcdef';
      $sul = '';
      for ($i = 0; $i < 10; $i++)
        $sul .= substr($znaky, rand(0, strlen($znaky) - 1), 1);
      $cook = $jmeno . '-' . time() . '-' . $sul . md5($md5_pass . time() . $heslo . $sul);
      setcookie($cook_name, $cook);
      return true;
    } else {
      return false;
    }
  } else {
    list ($jmeno, $cas, $x) = explode('-', withoutslashes($_COOKIE[$cook_name]));
    $sul = substr($x, 0, 10);
    $sum = substr($x, 10);
    $q = mysql_query("SELECT PASS FROM USERS WHERE NAME='" . $jmeno . "'");
    if ((mysql_num_rows($q) != 1) || ($sum != md5($md5_pass . $cas . mysql_result($q, 0) . $sul)) || (time() - 36000 > $cas)) {
      setcookie($cook_name, "", time() - 36000000);
      return false;
    } else {
      return true;
    }
  }
}

function getLocation($jmeno) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $md5_pass;
  global $cook_name;

  if ($jmeno == '') {
    list ($jmeno, $cas, $x) = explode('-', withoutslashes($_COOKIE[$cook_name]));
  }
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'utf-8';");
  $q = mysql_query("SELECT IDLOCATION FROM USERS WHERE NAME='" . $jmeno . "'");
  $row = mysql_fetch_row($q);
  return $row[0];
}

function getDopravce($jmeno) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $md5_pass;
  global $cook_name;

  if ($jmeno == '') {
    list ($jmeno, $cas, $x) = explode('-', withoutslashes($_COOKIE[$cook_name]));
  }
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'utf-8';");
  $q = mysql_query("SELECT NAZEV FROM location WHERE IDLOCATION=" . getLocation($jmeno) . "");
  $row = mysql_fetch_row($q);
  return $row[0];
}

function getUser($jmeno) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $md5_pass;
  global $cook_name;

  if ($jmeno == '') {
    list ($jmeno, $cas, $x) = explode('-', withoutslashes($_COOKIE[$cook_name]));
  }
  return $jmeno;
}
?>