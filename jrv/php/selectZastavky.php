<?php
error_reporting(0);
require_once '../lib/param.php';
require_once '../lib/functions.php';

Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}

  // následující funkce budou zřejmě umístěny někde v common ...

  /**
  * Není-li zapnutá direktiva magic_quotes_gpc, pak doplníme
  * \ před každý  nebezpečný znak typu ' - ochrana před SQL injection.
  */
  function gpc_addslashes($str) {
    // return (get_magic_quotes_gpc() ? $str : mysql_real_escape_string($str));
    return mysql_real_escape_string($str);
  }

  /**
   * Připojení k databázi
   */
  function connect() {
    global $con_server;
    global $con_db;
    global $con_pass;
    mysql_connect($con_server, $con_db, $con_pass);
/*    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db('savvy_mhdspoje');*/
  }

  // a vlastní skript ...

  $params = array();
  parse_str($_SERVER['QUERY_STRING'], $params);
  $str = urldecode($params['str']);
  connect();
  $location = urldecode($params['location']);
  $packet = urldecode($params['packet']);
  $namecombo = urldecode($params['name']);
  if (trim($str) != '') {
    $query = "select distinct zastavky.nazev, zastavky.c_zastavky from zastavky left outer join zaslinky
      on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
      where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 and UPPER(zastavky.nazev) LIKE
      CONCAT('%', UPPER('" .  gpc_addslashes($str) . "'),'%') ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";
  } else {
    $query = "select distinct zastavky.nazev, zastavky.c_zastavky from zastavky left outer join zaslinky
      on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
      where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";
  }
  $result = mysql_query($query) or die(mysql_error());
//  echo mysql_num_rows($result) . "<br>";
  if (mysql_num_rows($result)==0 /*||
    (mysql_num_rows($result)==1 &&
      strcmp(mysql_result($result, 0, "nazev"),$str)==0)*/)
    echo "EMPTY";
  else {
    // nastavíme pointer zpět na začátek
    mysql_data_seek($result, 0);
    //multiple
    echo "<select id=\"".$namecombo."\" style=\"width:300px; position:absolute;\" onClick=\"getResultClickHandler('".$namecombo."');\"
      onChange=\"getChangeHandler('".$namecombo."');\" size=\"10\">";
    while ($row = mysql_fetch_row($result)) {
      echo "<option value=".$row[1].">".$row[0]."</option>";
    }
    echo "</select>";
  }
  //echo "<div>" + $query + "</div>";
  mysql_close();
?>