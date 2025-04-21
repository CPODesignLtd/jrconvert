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

class THrana {

  var $linka = null;
  var $Zod = null;
  var $Zdo = null;
  var $Tod = null;
  var $Tdo = null;
  var $smer = null;
  var $CASod = null;
  var $Casdo = null;
  var $distance = 65535;
  var $cekani = 0;
  var $predchudce = null; // of THrana
  var $stav = 0; // 0 - pripraveny; 1 - rozpracovany
  var $linkaNazev = null;
  var $ZodText = null;
  var $ZdoText = null;
  var $doprava = null;
  var $odLocA = null;
  var $odLocB = null;
  var $doLocA = null;
  var $doLocB = null;

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
  var $distance = null;

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

function findOdjezdHladina($c_hladiny, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup, $iter, $sm, $linka, $dist) {
  $res = NULL;
  $odjezdy = $hladiny[$c_hladiny][$doZ][$linka][$dist][$sm];
//  if ($iter == 1) {
//  echo 'doZ = '.$doZ.'|smer = '.$sm.'</br>';
//  echo 'velikost pole = '.count($hladiny[$c_hladiny][$doZ][$linka][$dist][$sm]);
  /*  if ($odjezdy != NULL) {
    echo 'velikost1 pole = '.$odjezdy->size().'</br>';
    } */
//  }
  if ($odjezdy != NULL) {
//    $initer = 1;
//    echo '< casOD = '.$casDO.'</br>';
    for ($i = 0; $i < $odjezdy->size(); $i++) {
//      if ($iter == 1) {
//      echo $i.'  -----    '.$odjezdy->elementAt($i)->linka . ' , ' . $odjezdy->elementAt($i)->Zod . ' , ' . $odjezdy->elementAt($i)->Zdo . ' , ' . $odjezdy->elementAt($i)->CASod . ' - ' . $odjezdy->elementAt($i)->CASdo . ' | '.$odjezdy->elementAt($i)->distance.' ->  </br>';
//      }
      if ((($odjezdy->elementAt($i)->CASdo) < $casDO) && ($casDO - ($odjezdy->elementAt($i)->CASdo) <= $maxPrestup) && ($casDO - ($odjezdy->elementAt($i)->CASdo) >= $minPrestup)) {
//        if ($iter == $initer) {
//          echo 'mam</br>';
        $res = new TSpojPart();
        $res->linka = $odjezdy->elementAt($i)->linka;
        $res->Zod = $odjezdy->elementAt($i)->Zod;
        $res->Zdo = $odjezdy->elementAt($i)->Zdo;
        $res->Tod = $odjezdy->elementAt($i)->Tod;
        $res->Tdo = $odjezdy->elementAt($i)->Tdo;
        $res->smer = $odjezdy->elementAt($i)->smer;
        $res->CASod = $odjezdy->elementAt($i)->CASod;
        $res->CASdo = $odjezdy->elementAt($i)->CASdo;
//          echo 'vraceno '.$i.'</br>';
        return $res;
        /*        } else {
          $initer++;
          } */
      }
    }
  } else { //echo 'NULL</br>'; 
  }
//  echo 'vraceno '.$i.'</br>';
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
  $reshladiny = $hladiny;
  $sql = "call getOdjezdyZastavka1(" . $location . ", " . $packet . ", " . (($c_hladiny <= 1) ? ($min) : ($min + $dobaPrestup)) . ", " . (($c_hladiny <= 1) ? ($max) : ($max + $dobaSpoje)) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '');";
  echo $sql.'</br>';
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");

  $mysqli->multi_query($sql);

  do {
    if ($vysledek = $mysqli->store_result()) {
      while ($data1 = $vysledek->fetch_row()) {

        if ($reshladiny[(int) $data1[2]] == NULL) {
          $data = new Vector();
          $reshladiny[(int) $data1[2]] = $data;
        }

        $data = $reshladiny[(int) $data1[2]];
        $hrana = new THrana();
        $hrana->linka = $data1[0];
        $hrana->Zod = $data1[1];
        $hrana->Zdo = $data1[2];
        $hrana->Tod = $data1[3];
        $hrana->Tdo = $data1[4];
        $hrana->smer = $data1[5];
        $hrana->CASod = $data1[6];
        $hrana->CASdo = $data1[7];

        $data->addElement($hrana);
      }
      $vysledek->free_result();
    }
  } while ($mysqli->next_result());


  $sql = "call existCil(" . $location . ", " . $packet . ", " . $cil . ");";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $pocetCilu = 0;
  while ($data1 = $query1->fetch_row()) {
    $pocetCilu = $data1[0];
  }

  if ($pocetCilu == 0) {
    $sql = "call getOdjezdyCil(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . (($c_hladiny <= 1) ? ($min) : ($max + $dobaSpoje))/* (($c_hladiny > 1) ? ($H * 60 + $M + $dobaSpoje) : ($H * 60 + $M + (2 * $dobaSpoje))) */ . ", " . $cil . ", " . $varGRF . ");";
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");
    $query1 = $mysqli->query($sql);

    while ($data1 = $query1->fetch_row()) {
      if ($reshladiny[(int) $data1[2]] == NULL) {
        $data = new Vector();
        $reshladiny[(int) $data1[2]] = $data;
      }

      $data = $reshladiny[(int) $data1[2]];
      $hrana = new TSpojPart();
      $hrana->linka = $data1[0];
      $hrana->Zod = $data1[1];
      $hrana->Zdo = $data1[2];
      $hrana->Tod = $data1[3];
      $hrana->Tdo = $data1[4];
      $hrana->smer = $data1[5];
      $hrana->CASod = $data1[6]; //$data1[6] * 60 + $data1[7];
      $hrana->CASdo = $data1[7]; //$data1[8] * 60 + $data1[9];

      $data->addElement($hrana);
    }
  }

  if ($c_hladiny == 1) {
    $sql = "call existPrima(" . $location . ", " . $packet . ", " . $pocatek . ", " . $cil . ");";
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");
    $query1 = $mysqli->query($sql);

    $pocetCilu = 0;
    while ($data1 = $query1->fetch_row()) {
      $pocetCilu = $data1[0];
    }

    if ($pocetCilu == 0) {
      $sql = "call getOdjezdyPrima(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . ($H * 60 + $M + $dobaSpoje) . ", " . $pocatek . ", " . $cil . ", " . $varGRF . ");";
      $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
      $mysqli->query("SET NAMES 'utf-8';");
      $query1 = $mysqli->query($sql);

      while ($data1 = $query1->fetch_row()) {
        if ($reshladiny[(int) $data1[2]] == NULL) {
          $data = new Vector();
          $reshladiny[(int) $data1[2]] = $data;
        }

        $data = $reshladiny[(int) $data1[2]];
        $hrana = new TSpojPart();
        $hrana->linka = $data1[0];
        $hrana->Zod = $data1[1];
        $hrana->Zdo = $data1[2];
        $hrana->Tod = $data1[3];
        $hrana->Tdo = $data1[4];
        $hrana->smer = $data1[5];
        $hrana->CASod = $data1[6]; //$data1[6] * 60 + $data1[7];
        $hrana->CASdo = $data1[7]; //$data1[8] * 60 + $data1[9];

        $data->addElement($hrana);
      }
    }
  }
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
      if ($Spoje->elementAt($i)->CASod < $Spoje->elementAt($ii)->CASod && ($Spoje->elementAt($i)->CASdo >= $Spoje->elementAt($ii)->CASdo)) {
        $mam = true;
        break;
      }
    }
    if ($mam == false) {
      $SpojeEliminate->addElement($Spoje->elementAt($i));
    }
  }

  $Spoje = $SpojeEliminate;

  $SpojeEliminate = new Vector();
  for ($i = 0; $i < $Spoje->size(); $i++) {
    $prvni = $i;
    for ($ii = ($i + 1); $ii < $Spoje->size(); $ii++) {
      if ($Spoje->elementAt($i)->CASod == $Spoje->elementAt($ii)->CASod && ($Spoje->elementAt($i)->CASdo == $Spoje->elementAt($ii)->CASdo)) {
        $prvni = $ii;
      }
    }
    $SpojeEliminate->addElement($Spoje->elementAt($i));
    $i = $prvni;
  }

//  $SpojeEliminate = $Spoje;

  /* echo "begin name ".date('i').".".date('s');
    echo "</br>"; */

  mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL');

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db('savvy_mhdspoje');

  $sql = "SELECT nazev, loca, locb FROM zastavky where idlocation = " . $location . " and packet = " . $packet;

  $result = mysql_query($sql);

  $row = array();
  while ($rowname = mysql_fetch_row($result)) {
    $row[] = $rowname;
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

$dbname = 'savvy_mhdspoje';

$Vdo = new Vector();
$Vdo->addElement($pocatek);

$Vod = new Vector();
$Vod->addElement($pocatek);

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

//echo $varGRF;

$iter = 1;
$max = $H * 60 + $M + $dobaSpoje;
$min = $H * 60 + $M;
$dobaPrestup = 30;
$maxPrestup = $dobaPrestup;
$minPrestup = 2;
$hladiny = null;

function ohodnotTrasy($aHladiny, $odZ, $odCAS) {
  foreach ($aHladiny as $index => $val) {
    echo $index.'</br>';
    $min = 65535;
    $minindex = -1;
    for($i = 0; $i < $aHladiny[$index]->size(); $i++) {
      if ($aHladiny[$index]->elementAt($i)->Zod == $odZ) {
        if ($aHladiny[$index]->elementAt($i)->CASod > $odCAS) {
          if ($aHladiny[$index]->elementAt($i)->CASod - $odCAS <= $min) {
            $min = $aHladiny[$index]->elementAt($i)->CASod - $odCAS;
            $minindex = $i;
            $zindex = $index;
            echo 'have</br>';
          }
        }
      }
    }
    if ($minindex > -1) {
      echo 'change</br>';
      $aHladiny[$index]->elementAt($minindex)->distance = $aHladiny[$index]->elementAt($minindex)->CASdo - $odCAS;
      $aHladiny[$index]->elementAt($minindex)->stav = 1;
    }
  }
  echo 'res</br>';
  return TRUE;
}

while ($iter <= 1/* $pocetprestupu */) {
  $hladiny = getOdjezdy($location, $packet, $Vod, $Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $iter + 1, $cil, $pocatek, $min, $max, $minPrestup);
  $iter++;
}

ohodnotTrasy(&$hladiny, $pocatek, ($H * 60 + $M));

echo '</br>';

while (list($index, $stav) = each($hladiny)) {
  echo '           --- zastavka --- ' . $index . '</br>';
  if ($hladiny[$index] != NULL) {
    for ($i = 0; $i < $hladiny[$index]->size(); $i++) {
      echo $hladiny[$index]->elementAt($i)->linka . ' , ' . $hladiny[$index]->elementAt($i)->Zod . ' , ' . $hladiny[$index]->elementAt($i)->Zdo . ' , ' . $hladiny[$index]->elementAt($i)->CASod . ' - ' . $hladiny[$index]->elementAt($i)->CASdo . '  ->  stav = ' . $hladiny[$index]->elementAt($i)->stav . ', dist = ' . $hladiny[$index]->elementAt($i)->distance . '</br>';
    }
  }
}

$Spoje = new Vector();


function nextPart($aSpoje, $aSpoj, $aHladiny, $aHladina, $aMaxPrestup, $aMinPrestup, $aCil, $aDoZ, $aCasDO, $aPocatek) {
  if ($aHladina > 0) {
//    echo 'volani nextPart , hladina = ' . $aHladina . '</br>';
    foreach ($aHladiny[$aHladina][$aDoZ] as $index1 => $val1) {
//    while (list($index1, $stav1) = each($aHladiny[$aHladina][$aDoZ])) {
//      echo $index1.' | '.count(array_keys($aHladiny[$aHladina][$aDoZ][$index1]));
//      print_r(array_keys($aHladiny[$aHladina][$aDoZ][$index1]));
      $keymapDIST = array_keys($aHladiny[$aHladina][$aDoZ][$index1]);
//      echo '</br>';
//      foreach ($aHladiny[$aHladina][$aDoZ][$index1] as $index2 => $val2) {        
      for ($inx2 = 0; $inx2 < 1; $inx++) {
        $index2 = $keymapDIST[$inx2];
//      while (list($index2, $stav2) = each($aHladiny[$aHladina][$aDoZ][$index1])) {
        for ($sm = 0; $sm < 2; $sm++) {
          $SpojA = new TSpoj();
          $SpojA->CASdo = $aSpoj->CASdo;
          $SpojA->CASod = $aSpoj->CASod;
          $SpojA->CAScekani = $aSpoj->CAScekani;
          $SpojA->pocetPrestup = $aSpoj->pocetPrestup;
          $SpojA->useky = new Vector();
          $SpojA->platny = $aSpoj->platny;

//          echo '</br>' . $aHladina . '.   ';
          for ($i = 0; $i < $aSpoj->useky->size(); $i++) {
            $castSpoj = new TSpojPart();
            $castSpoj->linka = $aSpoj->useky->elementAt($i)->linka;
            $castSpoj->Zod = $aSpoj->useky->elementAt($i)->Zod;
            $castSpoj->Zdo = $aSpoj->useky->elementAt($i)->Zdo;
            $castSpoj->Tod = $aSpoj->useky->elementAt($i)->Tod;
            $castSpoj->Tdo = $aSpoj->useky->elementAt($i)->Tdo;
            $castSpoj->smer = $aSpoj->useky->elementAt($i)->smer;
            $castSpoj->CASod = $aSpoj->useky->elementAt($i)->CASod;
            $castSpoj->CASdo = $aSpoj->useky->elementAt($i)->CASdo;

            $SpojA->useky->addElement($castSpoj);

//            echo $castSpoj->linka . '(' . $castSpoj->smer . ') , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . ' | ' . $index1 . ' | ' . $index2 . '  -> ';
          }

          $doZ = $aDoZ;
          $casDO = $aCasDO;
//              if (trim($doZ) == '202') {
//                echo $casDO.', '.$aMaxPrestup.', '.$aMinPrestup.', '.$sm.'</br>';
//                $ineriter=1;
//              } else {
//                $ineriter=-1;
//              }
//      for ($ineriter = 1; $ineriter < 10; $ineriter++) {
          $castSpojr = findOdjezdHladina($aHladina, &$aHladiny, $doZ, $casDO, $aMaxPrestup, $aMinPrestup, $ineriter, $sm, $index1, $index2);
          /*        if ($castSpojr != NULL) {
            $ineriter = 10;
            }
            } */
          if ($castSpojr != NULL) {
//        if ($SpojA->useky->lastElement()->Zod == '202') {
            /*              echo '</br>' . $aHladina . '.   ';
              for ($i = 0; $i < $SpojA->useky->size(); $i++) {
              echo $SpojA->useky->elementAt($i)->linka . '(' . $SpojA->useky->elementAt($i)->smer . ') , ' . $SpojA->useky->elementAt($i)->Zod . ' , ' . $SpojA->useky->elementAt($i)->Zdo . ' , ' . $SpojA->useky->elementAt($i)->CASod . ' - ' . $SpojA->useky->elementAt($i)->CASdo . '  -> ';
              }
              echo '</br>'; */
//        }

            $SpojA->CAScekani += ($casDO - $castSpojr->CASdo);
            $SpojA->pocetPrestup++;
            $SpojA->CASod = $castSpojr->CASod;
            $SpojA->useky->insertElementAt($castSpojr, 1);
            $doZ = $castSpojr->Zod;
            $casDO = $castSpojr->CASod;
            if ($doZ != $aPocatek) {
              nextPart(&$aSpoje, $SpojA, &$aHladiny, $aHladina - 1, $aMaxPrestup, $aMinPrestup, $aCil, $doZ, $casDO, $aPocatek);
            } else {
//        echo 'shoda driv hladina='.$aHladina.'</br>';
              nextPart(&$aSpoje, $SpojA, &$aHladiny, 0, $aMaxPrestup, $aMinPrestup, $aCil, $doZ, $casDO, $aPocatek);
            }
          } else {
            $SpojA->platny = 0;
          }
        }
        break;
      }
    }
//    }

    /*      $SpojB = new TSpoj();
      $SpojB->CASdo = $aSpoj->CASdo;
      $SpojB->useky = $aSpoj->useky;
      $doZ = $aDoZ;
      $casDO = $aCasDO;
      $castSpojr = findOdjezdHladina($aHladina, $aHladiny, $doZ, $casDO, $aMaxPrestup, $aMinPrestup, 1, 1);
      if ($castSpojr != NULL) {
      $SpojB->CAScekani += ($casDO - $castSpojr->CASdo);
      $SpojB->pocetPrestup++;
      $SpojB->CASod = $castSpojr->CASod;
      $SpojB->useky->insertElementAt($castSpojr, 1);
      $doZ = $castSpojr->Zod;
      $casDO = $castSpojr->CASod;
      if ($doZ != $aPocatek) {
      nextPart(&$aSpoje, $SpojB, $aHladiny, $aHladina - 1, $aMaxPrestup, $aMinPrestup, $aCil, $doZ, $casDO, $aPocatek);
      } else {
      //        echo 'shoda driv hladina='.$aHladina.'</br>';
      nextPart(&$aSpoje, $SpojB, $aHladiny, 0, $aMaxPrestup, $aMinPrestup, $aCil, $doZ, $casDO, $aPocatek);
      }
      } else {
      $SpojB->platny = 0;
      } */
  } else {
//    echo 'pred zapisem</br>';
    if ($aSpoj->platny == 1) {
      $aSpoje->insertElementAt($aSpoj, 1);
//      echo '</br>zapis</br>';
    }
  }
}

/* foreach ($hladiny[2][$cil] as $key => $val) {
  //while (list($index1, $stav1) = each($hladiny[2])) {
  echo $key.'</br>';
  } */

/* echo date('i').".".date('s');
  echo "</br>"; */

/*for ($hladina = count($hladiny) - 1; $hladina > 0; $hladina--) {
//  echo ($hladina) . '  ' . count($hladiny[$hladina][$cil]) . '</br>';
  foreach ($hladiny[$hladina][$cil] as $key => $val) {
    foreach ($hladiny[$hladina][$cil][$key] as $key1 => $val1) {
//  while (list($index1, $stav1) = each($hladiny[count($hladiny) - $hladina - 1][$cil])) {
//    echo ' ---- '.$key.' ---- ';
      $a = $hladiny[$hladina][$cil][$key][$key1][0];

      if ($a != NULL) {

        for ($i = 0; $i < $a->size(); $i++) {
          $doZ = $a->elementAt($i)->Zod;
          $casDO = $a->elementAt($i)->CASod;

          $Spoj = new TSpoj();
          $Spoj->CASod = $a->elementAt($i)->CASod;
          $Spoj->CASdo = $a->elementAt($i)->CASdo;
          $Spoj->platny = 1;
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
//    $hhh = count($hladiny) - $hladina - 1; 
//      if ($castSpoj->Zod == '258') {
//          echo 'hladina = ' . ($hladina) . ' , ' . $castSpoj->linka . '(' . $castSpoj->smer . ') , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . ' | ' . $key . ' | ' . $key1 . '</br>';
//      }
          nextPart(&$Spoje, $Spoj, &$hladiny, ($hladina - 1), $maxPrestup, $minPrestup, $cil, $doZ, $casDO, $pocatek);
        }
      }
    }
  }

  foreach ($hladiny[$hladina][$cil] as $key => $val) {
    foreach ($hladiny[$hladina][$cil][$key] as $key1 => $val1) {
//  while (list($index1, $stav1) = each($hladiny[count($hladiny) - $hladina - 1][$cil])) {
      $a = $hladiny[$hladina][$cil][$key][$key1][1];

      if ($a != NULL) {

        for ($i = 0; $i < $a->size(); $i++) {
          $doZ = $a->elementAt($i)->Zod;
          $casDO = $a->elementAt($i)->CASod;

          $Spoj = new TSpoj();
          $Spoj->CASod = $a->elementAt($i)->CASod;
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
//    $hhh = count($hladiny) - $hladina - 1; 
//    echo $hhh.'.  '. $castSpoj->linka . '(' . $castSpoj->smer . ') , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . '</br>';    
//      if ($castSpoj->Zod == '258') {
//          echo 'hladina = ' . ($hladina) . ' , ' . $castSpoj->linka . '(' . $castSpoj->smer . ') , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . '</br>';
//      }

          nextPart(&$Spoje, $Spoj, &$hladiny, $hladina - 1, $maxPrestup, $minPrestup, $cil, $doZ, $casDO, $pocatek);
        }
      }
    }
  }
}

/* for ($ii = count($hladiny) - 1; $ii >= 1; $ii--) {
  for ($s = 0; $s < 2; $s++) {
  $a = $hladiny[$ii][$cil][$s];
  echo 'hladina == ' . $ii . '</br></br>';

  if ($a != NULL) {

  for ($i = 0; $i < $a->size(); $i++) {
  $hl = $ii - 1;
  $doZ = $a->elementAt($i)->Zod;
  $casDO = $a->elementAt($i)->CASod;

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

  echo '</br>';
  echo $castSpoj->linka . ' , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . ' -> ';
  if ($ii > 1) {
  for ($h = $hl; $h > 0; $h--) {
  for ($s1 = 0; $s1 < 2; $s1++) {
  for ($ineriter = 1; $ineriter < 10; $ineriter++) {
  $castSpojr = findOdjezdHladina($h, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup, $ineriter, $s1);
  if ($castSpojr != NULL) {
  $ineriter = 10;
  }
  }
  //          echo '</br>';
  //          echo $h.','. $doZ.','. $casDO.','. $maxPrestup.','. $minPrestup;
  //          echo $castSpojr->linka . ' , ' . $castSpojr->Zod . ' , ' . $castSpojr->Zdo . ' , ' . $castSpojr->CASod . ' - ' . $castSpojr->CASdo . ' -> ';
  if ($castSpojr != NULL) {
  $Spoj->CAScekani += ($casDO - $castSpojr->CASdo);
  $Spoj->pocetPrestup++;
  $Spoj->CASod = $castSpojr->CASod;
  $Spoj->useky->insertElementAt($castSpojr, 1);
  } else {
  $Spoj->platny = 0;
  }
  $doZ = $castSpojr->Zod;
  $casDO = $castSpojr->CASod;
  }
  }
  } else {
  $Spoj->CAScekani += ($a->elementAt($i)->CASdo - $a->elementAt($i)->CASod);
  //        $Spoj->pocetPrestup++;
  $Spoj->CASod = $a->elementAt($i)->CASod;
  //        $Spoj->useky->insertElementAt($castSpoj, 1);
  }
  if ($Spoj->platny == 1) {
  $Spoje->insertElementAt($Spoj, 1);
  }
  }
  //    }
  }
  }
  } */

/* echo date('i').".".date('s');
  echo "</br>"; */

//$Spoje = sortSpoje($Spoje, $location, $packet);

/* echo $Spoje->size().'</br>';

  echo date('i').".".date('s');
  echo "</br>"; */

/*$res = '';

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

/* for ($i = 0; $i < $Spoje->size(); $i++) {
  echo '</br></br>';
  echo '-- Spoj ' . $i . '. -- (doba jizdy : ' . ($Spoje->elementAt($i)->CASdo - $Spoje->elementAt($i)->CASod) . ', doba cekani : ' . $Spoje->elementAt($i)->CAScekani . ', pocet prestupu : ' . $Spoje->elementAt($i)->pocetPrestup . ', CASod : ' . $Spoje->elementAt($i)->CASod . ', CASdo : ' . $Spoje->elementAt($i)->CASdo . ')</br>';
  for ($ii = 0; $ii < $Spoje->elementAt($i)->useky->size(); $ii++) {
  $castSpoj = $Spoje->elementAt($i)->useky->elementAt($ii);
  echo '(' . $castSpoj->linka . ', OD : ' . $castSpoj->Zod . ', DO : ' . $castSpoj->Zdo . ', ' . $castSpoj->CASod . ', ' . $castSpoj->CASdo . ') -> ';
  }
  } */

echo $_GET['callback'] . "(" . json_encode($res) . ");";
//echo json_encode($res);
?>
