<?php

require_once 'Vector.php';

$pocatek = $_GET[pocatek];
$cil = $_GET[cil];
$location = $_GET[location];
$packet = $_GET[packet];
$H = $_GET['h'];
$M = $_GET['m'];
$dobaSpoje = $_GET[dobaS];
$pocetprestupu = $_GET[pocetP];

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

$day = date_format(new DateTime($datumJR), 'd');
$month = date_format(new DateTime($datumJR), 'm');
$year = date_format(new DateTime($datumJR), 'Y');

class TUzel {

  var $Zod = null;
  var $Tod = null;
  var $linka = null;
  var $smer = null;
  var $CASodjezdu = null;
  var $doba_jizdy = null;
  var $Zdo = null;
  var $Tdo = null;
  var $fixed = false;
  var $celkem_doba = 999999;
  var $id_uzel_od;
  
}

class TMinimum {
  
  var $hladina = -1;
  var $index = -1;
  
}


class TSpojPart {

  var $linka = null;
  var $Zod = null;
  var $Zdo = null;
  var $Tod = null;
  var $Tdo = null;
  var $smer = null;
  var $CASod = null;
  var $CASdo = null;
  var $linkaNazev = null;
  var $ZodText = null;
  var $ZdoText = null;
  var $doprava = null;
  var $odLocA = null;
  var $odLocB = null;
  var $doLocA = null;
  var $doLocB = null;

}

class TSpoj {

  var $CASod = null;
  var $CASdo = null;
  var $pocetPrestup = 0;
  var $CAScekani = 0;
  var $useky = null; /* Vector of TSpojPart */
  var $platny = 1;

}

function getVarray($V) {
  $res = '';
  $aV = $V->toArray();
  for ($i = 0; $i < count($aV); $i++) {
    if ($res != '') {
      $res .= ',';
    }
    $res .= $aV[$i];
  }
  return $res;
}

function findOdjezdHladina($c_hladiny, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup, $iter, $sm, $l) {
  /*  echo '</br></br>';
    echo ' hladina : ' . $c_hladiny;
    echo '</br></br>'; */
  $res = NULL;
  $odjezdy = $hladiny[$c_hladiny][$doZ];
  if ($odjezdy != NULL) {
    $initer = 1;
    for ($i = 0; $i < $odjezdy->size(); $i++) {
//      echo $odjezdy->linka . ' , ' . $odjezdy->Zod . ' , ' . $odjezdy->Zdo . ' , ' . $odjezdy->CASod . ' - ' . $odjezdy->CASdo . '  ->  </br>';
      if (($odjezdy->elementAt($i)->CASdo < $casDO) && ($casDO - $odjezdy->elementAt($i)->CASdo <= $maxPrestup) && ($casDO - $odjezdy->elementAt($i)->CASdo >= $minPrestup) && ($odjezdy->elementAt($i)->smer == $sm) && ($odjezdy->elementAt($i)->linka != $l)) {
        if ($iter == $initer) {
          $res = new TSpojPart();
          $res->linka = $odjezdy->elementAt($i)->linka;
          $res->Zod = $odjezdy->elementAt($i)->Zod;
          $res->Zdo = $odjezdy->elementAt($i)->Zdo;
          $res->Tod = $odjezdy->elementAt($i)->Tod;
          $res->Tdo = $odjezdy->elementAt($i)->Tdo;
          $res->smer = $odjezdy->elementAt($i)->smer;
          $res->CASod = $odjezdy->elementAt($i)->CASod;
          $res->CASdo = $odjezdy->elementAt($i)->CASdo;
          return $res;
        } else {
          $initer++;
        }
      }
    }
  }
  return $res;
}

function getZastavkaText($Z, $location, $packet) {
  mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL');

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db('savvy_mhdspoje');

  $sql = "SELECT nazev, loca, locb FROM zastavky where idlocation = " . $location . " and packet = " . $packet . " and c_zastavky = " . $Z;

  $result = mysql_query($sql);

  $row = mysql_fetch_row($result);

  return array($row[0], $row[1], $row[2]);
}

function getLinkaText($linka, $location, $packet) {
  mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL');

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db('savvy_mhdspoje');

  $sql = "SELECT nazev_linky, doprava FROM linky where idlocation = " . $location . " and packet = " . $packet . " and c_linky = " . $linka;

  $result = mysql_query($sql);

  $row = mysql_fetch_row($result);

  $res[0] = $row[0];
  $res[1] = $row[1];
  return $res;
}

function getOdjezdy($location, $packet, &$Vod, &$Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $c_hladiny, $cil, $pocatek, &$min, &$max, $dobaPrestup) {
  $reshladiny = $hladiny;                                                                                                                                                                               /* . $varGRF . */
//  $arrayVod=preg_split('/,/',$Vod);
  $Vodarray = $Vod->toArray();
//  echo date('i').".".date('s');
//  echo "</br>";
  $sql = '';
//  for ($i = 0; $i < count($Vodarray); $i++) {
  $sql = "call getOdjezdyZastavka1(" . $location . ", " . $packet . ", " . (($c_hladiny <= 1) ? (/* $max */$min) : ($min + $dobaPrestup)) . ", " . (($c_hladiny <= 1) ? (/* $min */$max) : ($max + $dobaSpoje)) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '');";
//    $sql .= "call getOdjezdyZastavka1(" . $location . ", " . $packet . ", " . (($c_hladiny <= 1) ? (/*$max*/$min) : ($min + $dobaPrestup)) . ", " . (($c_hladiny <= 1) ? (/*$min*/$max) : ($max + $dobaSpoje)) . ", " . $cil . ", " . $varGRF . ", '" . $Vodarray[$i] . "', '" . getVarray($Vdo) . "', '');";
//  }
//  echo $sql . '</br>';
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
//  $query1 = $mysqli->query($sql);

  $mysqli->multi_query($sql);

  $Vod = new Vector();

//  echo '--- hladina : ' . $c_hladiny . ' ---</br></br>';
//  while ($data1 = $query1->fetch_row()) {
  do {
    if ($vysledek = $mysqli->store_result()) {
      while ($data1 = $vysledek->fetch_row()) {

        if ($data1[2] != $cil) {
          if (in_array((string) $data1[2], $Vod->toArray(), FALSE) == FALSE) {
            $Vod->addElement($data1[2]);
          }
        }
        if ($data1[2] != $cil) {
          if (in_array((string) $data1[2], $Vdo->toArray(), FALSE) == FALSE) {
            $Vdo->addElement($data1[2]);
          }
        }

        if ($reshladiny[$c_hladiny][$data1[2]] == NULL) {
          $data = new Vector();
          $reshladiny[$c_hladiny][$data1[2]] = $data;
//      echo 'tvorim vector</br>';
        }

        $data = $reshladiny[$c_hladiny][$data1[2]];
        $hrana = new TSpojPart();
        $hrana->linka = $data1[0];
        $hrana->Zod = $data1[1];
        $hrana->Zdo = $data1[2];
        $hrana->Tod = $data1[3];
        $hrana->Tdo = $data1[4];
        $hrana->smer = $data1[5];
        $hrana->CASod = $data1[6]; //$data1[6] * 60 + $data1[7];
        $hrana->CASdo = $data1[7]; //$data1[8] * 60 + $data1[9];
        if ($min > $hrana->CASod) {
          $min = $hrana->CASod;
        }
        if ($max < $hrana->CASod) {
          $max = $hrana->CASod;
        }
//    echo $hrana->linka . ' , ' . $hrana->Zod . ' , ' . $hrana->Zdo . ' , ' . $hrana->CASod . ' - ' . $hrana->CASdo . '  ->  </br>';
        $data->addElement($hrana);
//    echo 'add hrana</br>';
      }
      $vysledek->free_result();
    }
  } while ($mysqli->next_result());

  /*  echo date('i').".".date('s');
    echo "</br>"; */

  $sql = "call existCil(" . $location . ", " . $packet . ", " . $cil . ");";
//  echo $sql . '</br>';
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $pocetCilu = 0;
  while ($data1 = $query1->fetch_row()) {
    $pocetCilu = $data1[0];
  }

  /*  echo date('i').".".date('s');
    echo "</br>"; */

  if ($pocetCilu == 0) {
    /*    (($c_hladiny <= 1) ? ($max) : ($min + $dobaPrestup)) . ", " . (($c_hladiny <= 1) ? ($min) : ($max + $dobaSpoje)) */
    $sql = "call getOdjezdyCil(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . (($c_hladiny <= 1) ? ($min) : ($max + $dobaSpoje))/* (($c_hladiny > 1) ? ($H * 60 + $M + $dobaSpoje) : ($H * 60 + $M + (2 * $dobaSpoje))) */ . ", " . $cil . ", " . $varGRF . ");";
//  echo $sql . '</br>';
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");
    $query1 = $mysqli->query($sql);

    while ($data1 = $query1->fetch_row()) {
      if (($c_hladiny > 1) || (($c_hladiny == 1) && ($data1[1] == $pocatek))) {
        if ($reshladiny[$c_hladiny][$data1[2]] == NULL) {
          $data = new Vector();
          $reshladiny[$c_hladiny][$data1[2]] = $data;
//      echo 'tvorim vector</br>';
        }

        $data = $reshladiny[$c_hladiny][$data1[2]];
        $hrana = new TSpojPart();
        $hrana->linka = $data1[0];
        $hrana->Zod = $data1[1];
        $hrana->Zdo = $data1[2];
        $hrana->Tod = $data1[3];
        $hrana->Tdo = $data1[4];
        $hrana->smer = $data1[5];
        $hrana->CASod = $data1[6]; //$data1[6] * 60 + $data1[7];
        $hrana->CASdo = $data1[7]; //$data1[8] * 60 + $data1[9];
//    echo $hrana->linka . ' , ' . $hrana->Zod . ' , ' . $hrana->Zdo . ' , ' . $hrana->CASod . ' - ' . $hrana->CASdo . '  ->  </br>';
        $data->addElement($hrana);
      }
    }
  }

  if ($c_hladiny == 1) {
    $sql = "call existPrima(" . $location . ", " . $packet . ", " . $pocatek . ", " . $cil . ");";
//  echo $sql . '</br>';
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");
    $query1 = $mysqli->query($sql);

    $pocetCilu = 0;
    while ($data1 = $query1->fetch_row()) {
      $pocetCilu = $data1[0];
    }

    /*  echo date('i').".".date('s');
      echo "</br>"; */

    if ($pocetCilu == 0) {
      $sql = "call getOdjezdyPrima(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . ($H * 60 + $M + $dobaSpoje) . ", " . $pocatek . ", " . $cil . ", " . $varGRF . ");";
//  echo $sql . '</br>';
      $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
      $mysqli->query("SET NAMES 'utf-8';");
      $query1 = $mysqli->query($sql);

      while ($data1 = $query1->fetch_row()) {
        if (($c_hladiny > 1) || (($c_hladiny == 1) && ($data1[1] == $pocatek))) {
          if ($reshladiny[$c_hladiny][$data1[2]] == NULL) {
            $data = new Vector();
            $reshladiny[$c_hladiny][$data1[2]] = $data;
//      echo 'tvorim vector</br>';
          }

          $data = $reshladiny[$c_hladiny][$data1[2]];
          $hrana = new TSpojPart();
          $hrana->linka = $data1[0];
          $hrana->Zod = $data1[1];
          $hrana->Zdo = $data1[2];
          $hrana->Tod = $data1[3];
          $hrana->Tdo = $data1[4];
          $hrana->smer = $data1[5];
          $hrana->CASod = $data1[6]; //$data1[6] * 60 + $data1[7];
          $hrana->CASdo = $data1[7]; //$data1[8] * 60 + $data1[9];
//    echo $hrana->linka . ' , ' . $hrana->Zod . ' , ' . $hrana->Zdo . ' , ' . $hrana->CASod . ' - ' . $hrana->CASdo . '  ->  </br>';
          $data->addElement($hrana);
        }
      }
    }
  }
  /*  echo date('i').".".date('s');
    echo "</br>";
    echo "</br>"; */

  return $reshladiny;
}

function sortSpoje($Spoje, $location, $packet) {

  /* echo "begin sort ".date('i').".".date('s');
    echo "</br>"; */

  for ($i = 0; $i < $Spoje->size(); $i++) {
    for ($ii = 0; $ii < ($Spoje->size() - 1); $ii++) {
      $Spoj = $Spoje->elementAt($ii);
      $porovnani1 = substr("0000" . $Spoj->CASod, -4, 4) . substr("0000" . $Spoj->CASdo, -4, 4);
      $porovnani2 = substr("0000" . $Spoje->elementAt($ii + 1)->CASod, -4, 4) . substr("0000" . $Spoje->elementAt($ii + 1)->CASdo, -4, 4);
      if (/* $Spoj->CASod */$porovnani1 > /* $Spoje->elementAt($ii + 1)->CASod */$porovnani2) {
        $Spoje->addElementAt($Spoje->elementAt($ii + 1), $ii);
        $Spoje->addElementAt($Spoj, ($ii + 1));
      }
    }
  }

  /* echo "end sort ".date('i').".".date('s');
    echo "</br>"; */

    $SpojeEliminate = new Vector();
    for ($i = 0; $i < $Spoje->size(); $i++) {
    $Spoj = $Spoje->elementAt($i);
    for ($ii = $i + 1; $ii < $Spoje->size(); $ii++) {
    if (($Spoje->elementAt($ii)->CASod != $Spoj->CASod) || (($Spoje->elementAt($ii)->CASod == $Spoj->CASod) && ($Spoje->elementAt($ii)->CASdo == $Spoj->CASdo))) {
    $i = $ii - 1;
    $SpojeEliminate->addElement($Spoj);
    break;
    }
    }
    if ($ii == $Spoje->size()) {
    $SpojeEliminate->addElement($Spoj);
    $i = $ii - 1;
    }
    } 

//--------------------------------------------------------------------------------------------------------

    $Spoje = $SpojeEliminate;

    $SpojeEliminate = new Vector();
    for ($i = 0; $i < $Spoje->size(); $i++) {
    $mam = false;
    for ($ii = ($i + 1); $ii < $Spoje->size(); $ii++) {
    if ($Spoje->elementAt($i)->CASod <= $Spoje->elementAt($ii)->CASod && ($Spoje->elementAt($i)->CASdo >= $Spoje->elementAt($ii)->CASdo)) {
    $mam = true;
    break;
    }
    }
    if ($mam == false) {
    $SpojeEliminate->addElement($Spoje->elementAt($i));
    }
    } 

    $Spoje = $SpojeEliminate;

/*    $SpojeEliminate = new Vector();
    for ($i = 0; $i < $Spoje->size(); $i++) {
    $prvni = $i;
    for ($ii = ($i + 1); $ii < $Spoje->size(); $ii++) {
    if ($Spoje->elementAt($i)->CASod == $Spoje->elementAt($ii)->CASod && ($Spoje->elementAt($i)->CASdo == $Spoje->elementAt($ii)->CASdo)) {
    $prvni = $ii;
    }
    }
    $SpojeEliminate->addElement($Spoje->elementAt($i));
    $i = $prvni + 1;
    } */


  $SpojeEliminate = $Spoje;

  /* echo "begin name ".date('i').".".date('s');
    echo "</br>"; */

  mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL');

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db('savvy_mhdspoje');

  $sql = "SELECT nazev, loca, locb, c_zastavky FROM zastavky where idlocation = " . $location . " and packet = " . $packet;

  $result = mysql_query($sql);

  $row = array();
  while ($rowname = mysql_fetch_row($result)) {
    $row[$rowname[3] - 1] = $rowname;
  }

  $sql = "SELECT c_linky, nazev_linky, doprava FROM linky where idlocation = " . $location . " and packet = " . $packet;

  $result = mysql_query($sql);

  $rowlinky = array();
  while ($rownamelinka = mysql_fetch_row($result)) {
    $rowlinky[$rownamelinka[0]] = $rownamelinka;
  }

  for ($i = 0; $i < $SpojeEliminate->size(); $i++) {
    for ($ii = 0; $ii < $SpojeEliminate->elementAt($i)->useky->size(); $ii++) {
//      $linky = getLinkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linka, $location, $packet);
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linkaNazev = $rowlinky[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linka][1]; //$linky[0];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->doprava = $rowlinky[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linka][2]; //$linky[1];
//      $resultText = getZastavkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zod, $location, $packet);
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->ZodText = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zod - 1][0]; //$resultText[0];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->odLocA = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zod - 1][1]; //$resultText[1];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->odLocB = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zod - 1][2]; //$resultText[2];
//      $resultText = getZastavkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zdo, $location, $packet);
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->ZdoText = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zdo - 1][0]; //$resultText[0];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->doLocA = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zdo - 1][0]; //$resultText[1];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->doLocB = $row[$SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zdo - 1][0]; //$resultText[2];
    }
  }

  /* echo "end name ".date('i').".".date('s');
    echo "</br>"; */


  return $SpojeEliminate;
}



function getMinimum($hladina) {
  $minimum = new TMinimum();
  $minvalue = 99999999999;
  for ($h = 0; $h < count($hladina); $h++) {
    for ($i = 0; $i < count($hladina[$h]); $i++) {
      $Uzel = $hladina[$h][$i];
      if ($Uzel->fixed == false) {
        if ($Uzel->celkem_doba < $minvalue) {
          $minvalue = $Uzel->celkem_doba;
          $minimum->hladina = $h;
          $minimum->index = $i;
        }
      }
    }    
  }
  return $minimum;
}

function loadUzly(&$hladiny, $hladina, $location, $packet, $datum, $cas_od, $cas_do, $cil, $tarif_od, $varGRF, $Vod, $Vdo) {
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  
  $sql = "call getOdjezdyZastavka5(" . $location . ", " . $packet . ", " . $cas_od . ", " . $cas_do . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '', " . $tarif_od . ");";
  
  $mysqli->close();
/*   while(list($index, $stav) = each($hladiny)) {
  echo '</br></br> --- hladina : ' . $index . ' --- </br>';
  while(list($index1, $stav1) = each($hladiny[$index])) {
  echo '           --- zastavka --- ' . $index1 . '</br>';
  for ($i = 0; $i < $hladiny[$index][$index1]->size(); $i++) {*/
  
}



$dbname = 'savvy_mhdspoje';

$hladina = null;

$sql = "SELECT datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

$varGRF = 0;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";

  $mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqliVARGRF->query("SET NAMES 'utf-8';");
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

$maxPrestup = 30;
$minPrestup = 2;
$Vod = new Vector();
$Vod->addElement(18);
$Vdo = new Vector();
$Vdo->addElement(18);


$sql = "call getOdjezdyZastavka5(" . $location . ", " . $packet . ", " . ($H*60+$M) . ", " . (($H*60+$M) + $maxPrestup) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '', -1);";
echo $sql . '</br>';
$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

//$minimumUzel = 9999999;
//$minimumUzel_id = -1;
  
while ($row = $result->fetch_row()) {
  $Uzel = new TUzel();
  $Uzel->Zod = $row[1];
  $Uzel->Tod = $row[3];
  $Uzel->linka = $row[0];
  $Uzel->CASodjezdu = (int)($row[6] / 100);
  $Uzel->doba_jizdy = (int)($row[6] % 100);
  $Uzel->Zdo = $row[2];
  $Uzel->Tdo = $row[4];
  $Uzel->smer = $row[5];
  if ($Uzel->celkem_doba > $Uzel->doba_jizdy + ($Uzel->CASodjezdu - ($H * 60 + $M))) {
    $Uzel->celkem_doba = $Uzel->doba_jizdy + ($Uzel->CASodjezdu - ($H * 60 + $M));
  }
  $Uzel->hladina = -1;
  $Uzel->id_uzel_od = -1;
    
  $hladina[0][] = $Uzel;
   
/*  if ($Uzel->celkem_doba < $minimumUzel) {
    $minimumUzel = $Uzel->celkem_doba;
    $minimumUzel_id = count($hladina[0]) - 1;
  }*/
  
  $Vdo->addElement($Uzel->Zdo);
}

for($i=0; $i<(count($hladina[0]));$i++) {
  $Uzel1 = ($hladina[0][$i]);
  
  echo $i . ". " . $Uzel1->linka . " ( " . $Uzel1->CASodjezdu . " - " . $Uzel1->doba_jizdy . " ) = " . $Uzel1->celkem_doba . "</br>";
}

$minimum = getMinimum($hladina);
$Uzel1 = $hladina[$minimum->hladina][$minimum->index];//($hladina[0][$minimumUzel_id]);
$Uzel1->fixed = true;

$Vod = new Vector();
$Vod->addElement($Uzel1->Zdo); 

echo "</br>minimum doba = " . $Uzel1->celkem_doba . " (" . $minimum->index . ")</br>"; 

echo "</br>----------------------------------------------------</br>";

$sql = "call getOdjezdyZastavka5(" . $location . ", " . $packet . ", " . ($Uzel1->CASodjezdu + $Uzel1->doba_jizdy + $minPrestup) . ", " . ($Uzel1->CASodjezdu + $Uzel1->doba_jizdy + $maxPrestup) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '', " . $Uzel1->Tdo . ");";
echo $sql . '</br>';
$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

//$minimumUzel = 9999999;
//$minimumUzel_id = -1;
  
while ($row = $result->fetch_row()) {
  $Uzel = new TUzel();
  $Uzel->Zod = $row[1];
  $Uzel->Tod = $row[3];
  $Uzel->linka = $row[0];
  $Uzel->CASodjezdu = (int)($row[6] / 100);
  $Uzel->doba_jizdy = (int)($row[6] % 100);
  $Uzel->Zdo = $row[2];
  $Uzel->Tdo = $row[4];
  $Uzel->smer = $row[5];
  if ($Uzel->celkem_doba > $Uzel->doba_jizdy + ($Uzel->CASodjezdu - ($H * 60 + $M))) {
    $Uzel->celkem_doba = $Uzel->doba_jizdy + ($Uzel->CASodjezdu - ($H * 60 + $M));
  }
  $Uzel->hladina = -1;
  $Uzel->id_uzel_od = -1;
    
  $hladina[1][] = $Uzel;
   
/*  if ($Uzel->celkem_doba < $minimumUzel) {
    $minimumUzel = $Uzel->celkem_doba;
    $minimumUzel_id = count($hladina[1]) - 1;
  }*/
  
  $Vdo->addElement($Uzel->Zdo);
}

//$Uzel1 = ($hladina[1][$minimumUzel_id]);
$minimum = getMinimum($hladina);
$Uzel1 = $hladina[$minimum->hladina][$minimum->index];

$Vod = new Vector();
$Vod->addElement($Uzel1->Zdo); 

for($i=0; $i<(count($hladina[1]));$i++) {
  $Uzel1 = ($hladina[1][$i]);
  
  echo $i . ". " . $Uzel1->linka . " ( " . $Uzel1->CASodjezdu . " - " . $Uzel1->doba_jizdy . " ) = " . $Uzel1->celkem_doba . "</br>";
}

$Uzel1 = $hladina[$minimum->hladina][$minimum->index];

echo "</br>minimum doba = " . $Uzel1->celkem_doba . " (" . $minimum->index . ")</br>"; 


/*$Vdo = new Vector();
$Vdo->addElement($pocatek);

$Vod = new Vector();
$Vod->addElement($pocatek);

$hladiny[0][$pocatek] = new Vector();

$sql = "SELECT datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

$varGRF = 0;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";

  $mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqliVARGRF->query("SET NAMES 'utf-8';");
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

$iter = 0;
$max = $H * 60 + $M + $dobaSpoje;
$min = $H * 60 + $M;
$dobaPrestup = 30;
$maxPrestup = $dobaPrestup;
$minPrestup = 2;

while ((getVarray($Vod) != '') && ($iter <= $pocetprestupu)) {
  $hladiny = getOdjezdy($location, $packet, $Vod, $Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $iter + 1, $cil, $pocatek, $min, $max, $dobaPrestup);
  $iter++;
}

$Spoje = new Vector();


for ($ii = count($hladiny) - 1; $ii >= 1; $ii--) {
  $a = $hladiny[$ii][$cil];
  if ($a != NULL) {
    for ($s = 0; $s < 2; $s++) {
    for ($i = 0; $i < $a->size(); $i++) {
      $hl = $ii - 1;
      $doZ = $a->elementAt($i)->Zod;
      $casDO = $a->elementAt($i)->CASod;
      $l = $a->elementAt($i)->linka;

      $Spoj = new TSpoj();
      $Spoj->CASdo = $a->elementAt($i)->CASdo;
      $Spoj->useky = new Vector();

      $castSpoj = new TSpojPart();
      $castSpoj->linka = $a->elementAt($i)->linka;
      $castSpoj->Zod = $a->elementAt($i)->Zod;
      $castSpoj->Zdo = $a->elementAt($i)->Zdo;
      $castSpoj->Tod = $a->elementAt($i)->Tod;
      $castSpoj->Tdo = $a->elementAt($i)->Tdo;
      $castSpoj->smer = $a->elementAt($i)->smer;
      $castSpoj->CASod = $a->elementAt($i)->CASod;
      $castSpoj->CASdo = $a->elementAt($i)->CASdo;

      $Spoj->useky->addElement($castSpoj);

      if ($ii > 1) {
        for ($h = $hl; $h > 0; $h--) {
            for ($ineriter = 1; $ineriter < 10; $ineriter++) {
              $castSpojr = findOdjezdHladina($h, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup, $ineriter, $s, $l);
              if ($castSpojr != NULL) {
                $ineriter = 10;
              }
            }          
          if ($castSpojr != NULL) {
            $Spoj->CAScekani += ($casDO - $castSpojr->CASdo);
            $Spoj->pocetPrestup++;
            $Spoj->CASod = $castSpojr->CASod;
            $Spoj->useky->insertElementAt($castSpojr, 1);
          } else {
            $Spoj->platny = 0;
          }
          $doZ = $castSpojr->Zod;
          $l = $casSpojr->linka;
          $casDO = $castSpojr->CASod;
        }
      } else {
        $Spoj->CAScekani += ($a->elementAt($i)->CASdo - $a->elementAt($i)->CASod);
        $Spoj->CASod = $a->elementAt($i)->CASod;
      }
      if ($Spoj->platny == 1) {
        $Spoje->insertElementAt($Spoj, 1);
      }
    }
    }
  }
}

$Spoje = sortSpoje($Spoje, $location, $packet);

$res = '';

$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
$res = $res . "<div id='movedivSeznam' class='movediv'>";
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div>";
$res = $res . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:700px; width: auto;'>";
$res = $res . "<tr>";
$res = $res . "<td>";

if (($Spoje != NULL) && ($Spoje->size() > 0)) {
  for ($i = 0; $i < $Spoje->size(); $i++) {
    $res = $res . "<table class = 'tablejr' style='width: 100%;'>";
    $res = $res . "<tr>";
    $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Linka");
    $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Ze zastávky");
    $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Odjezd");
    $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Do zastávky");
    $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Pøíjezd");
    $res = $res . "</tr>";
    for ($ii = 0; $ii < $Spoje->elementAt($i)->useky->size(); $ii++) {
      $Spoj = $Spoje->elementAt($i)->useky->elementAt($ii);
      $res = $res . "<tr class='licha' onClick = '" . "selfobj.changeZIndexJR(); getJR(" . $Spoj->linka . ", " . $Spoj->smer . ", " . (integer) $Spoj->Tod . ", " . $location . ", " . $packet . ", 0, \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", 0, null, null, null, null, 1);'>";

      $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
      $res = $res . "<a class = 'a_nazev_linky1' style='font-size: 18px;'>";
      $res = $res . $Spoj->linkaNazev;
      $res = $res . "</a>";
      $res = $res . (($Spoj->doprava == 'T') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>" :
                      (($Spoj->doprava == 'O') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>" :
                              (($Spoj->doprava == 'A') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>" : "")));

      $res = $res . "</td>";

      $zastOd = "\"" . $Spoj->ZodText . (($location == 17) ? iconv('ISO-8859-2', 'UTF-8', ", Plzeò") : (($location == 11) ? iconv('ISO-8859-2', 'UTF-8', ", Opava") : (($location == 5) ? iconv('ISO-8859-2', 'UTF-8', ", Tøebíè") : ""))) . "\"";
      $zastDo = "\"" . $Spoj->ZdoText . (($location == 17) ? iconv('ISO-8859-2', 'UTF-8', ", Plzeò") : (($location == 11) ? iconv('ISO-8859-2', 'UTF-8', ", Opava") : (($location == 5) ? iconv('ISO-8859-2', 'UTF-8', ", Tøebíè") : ""))) . "\"";

      $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
      switch ($location) {
        case 17: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ", " . (($Spoj->odLocA == '') ? 'null' : $Spoj->odLocA) . ", " . (($Spoj->odLocB == '') ? 'null' : $Spoj->odLocB) . ", " . $Spoj->Zod . ");'>&nbsp;</img>";
            break;
          }
        case 1: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ", " . (($Spoj->odLocA == '') ? 'null' : $Spoj->odLocA) . ", " . (($Spoj->odLocB == '') ? 'null' : $Spoj->odLocB) . ", " . $Spoj->Zod . ");'>&nbsp;</img>";
            break;
          }
        case 11: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ", " . (($Spoj->odLocA == '') ? 'null' : $Spoj->odLocA) . ", " . (($Spoj->odLocB == '') ? 'null' : $Spoj->odLocB) . ", " . $Spoj->Zod . ");'>&nbsp;</img>";
            break;
          }
        default: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ", " . (($Spoj->odLocA == '') ? 'null' : $Spoj->odLocA) . ", " . (($Spoj->odLocB == '') ? 'null' : $Spoj->odLocB) . ", " . $Spoj->Zod . ");'>&nbsp;</img>";
            break;
          }
      }
      $res = $res . $Spoj->ZodText;
      $res = $res . "</td>";

      if ($ii == 0) {
        $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
      } else {
        $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
      }
      $res = $res . (((($Spoj->CASod / 60) % 24) < 10) ? '0' . (($Spoj->CASod / 60) % 24) : (($Spoj->CASod / 60) % 24)) . " : " . ((($Spoj->CASod % 60) < 10) ? '0' . ($Spoj->CASod % 60) : ($Spoj->CASod % 60));
      $res = $res . "</td>";

      $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
      switch ($location) {
        case 17: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='event.cancelBubble; event.stopPropagation();  selfobj.map(" . $zastDo . ", " . (($Spoj->doLocA == '') ? 'null' : $Spoj->doLocA) . ", " . (($Spoj->doLocB == '') ? 'null' : $Spoj->doLocB) . ", " . $Spoj->Zdo . ");'>&nbsp;</img>";
            break;
          }
        case 1: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastDo . ", " . (($Spoj->doLocA == '') ? 'null' : $Spoj->doLocA) . ", " . (($Spoj->doLocB == '') ? 'null' : $Spoj->doLocB) . ", " . $Spoj->Zdo . ");'>&nbsp;</img>";
            break;
          }
        case 11: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastDo . ", " . (($Spoj->doLocA == '') ? 'null' : $Spoj->doLocA) . ", " . (($Spoj->doLocB == '') ? 'null' : $Spoj->doLocB) . ", " . $Spoj->Zdo . ");'>&nbsp;</img>";
            break;
          }
        default: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastDo . ", " . (($Spoj->doLocA == '') ? 'null' : $Spoj->doLocA) . ", " . (($Spoj->doLocB == '') ? 'null' : $Spoj->doLocB) . ", " . $Spoj->Zdo . ");'>&nbsp;</img>";
            break;
          }
      }
      $res = $res . $Spoj->ZdoText;
      $res = $res . "</td>";

      if ($ii == $Spoje->elementAt($i)->useky->size() - 1) {
        $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
      } else {
        $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
      }
      $res = $res . (((($Spoj->CASdo / 60) % 24) < 10) ? '0' . (($Spoj->CASdo / 60) % 24) : (($Spoj->CASdo / 60) % 24)) . " : " . ((($Spoj->CASdo % 60) < 10) ? '0' . ($Spoj->CASdo % 60) : ($Spoj->CASdo % 60));
      $res = $res . "</td>";

      $res = $res . "</tr>";
    }
    $res = $res . "</table>";
    $res = $res . "<div style='margin-top: 20px;'></div>";
  }
} else {
  $res = $res . "<table class='tablejr' style='max-width:500px; width: auto;'>";
  $res = $res . "<tr>";
  $res = $res . "<td><a>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "Vhodné spojení nebylo nalezeno");
  $res = $res . "</a></td>";
  $res = $res . "</tr>";
  $res = $res . "</table>";
}
$res = $res . "</td>";
$res = $res . "</tr>";
$res = $res . "</table>";
$res = $res . "</div>";


echo $_GET['callback'] . "(" . json_encode($res) . ");";*/
//echo json_encode($res);
?>
