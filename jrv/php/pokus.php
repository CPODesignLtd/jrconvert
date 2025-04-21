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

function getZastavkaText($Z, $location, $packet) {
  mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL');

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db('savvy_mhdspoje');

  $sql = "SELECT nazev FROM zastavky where idlocation = " . $location . " and packet = " . $packet . " and c_zastavky = " . $Z;

  $result = mysql_query($sql);

  $row = mysql_fetch_row($result);

  return $row[0];
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

function findOdjezdHladina($c_hladiny, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup) {
  /*  echo '</br></br>';
    echo ' hladina : ' . $c_hladiny;
    echo '</br></br>'; */
  $res = NULL;
  $odjezdy = $hladiny[$c_hladiny][$doZ];
  if ($odjezdy != NULL) {
    for ($i = 0; $i < $odjezdy->size(); $i++) {
//      echo $odjezdy->linka . ' , ' . $odjezdy->Zod . ' , ' . $odjezdy->Zdo . ' , ' . $odjezdy->CASod . ' - ' . $odjezdy->CASdo . '  ->  </br>';
      if (($odjezdy->elementAt($i)->CASdo < $casDO) && ($casDO - $odjezdy->elementAt($i)->CASdo <= $maxPrestup) && ($casDO - $odjezdy->elementAt($i)->CASdo >= $minPrestup)) {
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
      }
    }
  }
  return $res;
}

function findTrasaHladina($c_hladiny, $hladiny, $doZ) {
  /*  echo '</br></br>';
    echo ' hladina : ' . $c_hladiny;
    echo '</br></br>'; */
  $res = NULL;
  $odjezdy = $hladiny[$c_hladiny][$doZ];
  if ($odjezdy != NULL) {
    for ($i = 0; $i < $odjezdy->size(); $i++) {
//      echo $odjezdy->linka . ' , ' . $odjezdy->Zod . ' , ' . $odjezdy->Zdo . ' , ' . $odjezdy->CASod . ' - ' . $odjezdy->CASdo . '  ->  </br>';
//      if ($odjezdy->elementAt($i)->CASdo < $casDO) {
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
//      }
    }
  }
  return $res;
}

function getOdjezdy($location, $packet, &$Vod, &$Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $c_hladiny, $cil, $pocatek, &$min, &$max, $dobaPrestup) {
  $reshladiny = $hladiny;                                                                                                                                                                               /* . $varGRF . */
  //$sql = "call getOdjezdyZastavka2(" . $location . ", " . $packet . ", " . (($c_hladiny <= 1) ? ($max) : ($min + $dobaPrestup)) . ", " . (($c_hladiny <= 1) ? ($min) : ($max + $dobaSpoje)) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '');";
//  $sql = "call getOdjezdyZastavka2(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . ($H * 60 + $M + 3 * $dobaSpoje) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '');";
    $sql = "call getOdjezdyZastavka1(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . ($H * 60 + $M + 3 * $dobaSpoje) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "', '');";
  echo $sql . '</br>';
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $Vod = new Vector();

//  echo '--- hladina : ' . $c_hladiny . ' ---</br></br>';

  while ($data1 = $query1->fetch_row()) {
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

  $sql = "call existCil(" . $location . ", " . $packet . ", " . $cil . ");";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $pocetCilu = 0;
  while ($data1 = $query1->fetch_row()) {
    $pocetCilu = $data1[0];
  }
  
  if ($pocetCilu == 0) {
//  $sql = "call getOdjezdyCil1(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . (($c_hladiny > 1) ? ($H * 60 + $M + $dobaSpoje) : ($H * 60 + $M + (3 * $dobaSpoje))) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "');";
  $sql = "call getOdjezdyCil(" . $location . ", " . $packet . ", " . ($H * 60 + $M) . ", " . (($c_hladiny > 1) ? ($H * 60 + $M + $dobaSpoje) : ($H * 60 + $M + (3 * $dobaSpoje))) . ", " . $cil . ", " . $varGRF . ", '" . getVarray($Vod) . "');";  
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  while ($data1 = $query1->fetch_row()) {
    if (($c_hladiny > 1) || (($c_hladiny == 1) && ($data1[1] == $pocatek))) {
      if ($reshladiny[$c_hladiny][$data1[2]] == NULL) {
        $data = new Vector();
        $reshladiny[$c_hladiny][$data1[2]] = $data;
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
      $data->addElement($hrana);
    }
  }
  }

  return $reshladiny;
}

function getTrasy($location, $packet, &$Vod, &$Vdo, $hladiny, $c_hladiny, $cil) {
  $reshladiny = $hladiny;
  $sql = "call getTrasy(" . $location . ", " . $packet . ", '" . getVarray($Vod) . "', '" . getVarray($Vdo) . "');";
  echo $sql . '</br>';
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $Vod = new Vector();

//  echo '--- hladina : ' . $c_hladiny . ' ---</br></br>';

  while ($data1 = $query1->fetch_row()) {
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
//    $hrana->CASod = $data1[6];//$data1[6] * 60 + $data1[7];
//    $hrana->CASdo = $data1[7];//$data1[8] * 60 + $data1[9];
//    echo $hrana->linka . ' , ' . $hrana->Zod . ' , ' . $hrana->Zdo . ' , ' . $hrana->CASod . ' - ' . $hrana->CASdo . '  ->  </br>';
    $data->addElement($hrana);
//    echo 'add hrana</br>';
  }

  $sql = "call existCil(" . $location . ", " . $packet . ", " . $cil . ");";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  $pocetCilu = 0;
  while ($data1 = $query1->fetch_row()) {
    $pocetCilu = $data1[0];
  }
  
  if ($pocetCilu == 0) {
  $sql = "call getTrasyCil(" . $location . ", " . $packet . ", " . $cil . ", '" . getVarray($Vod) . "');";
//  echo $sql;
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql);

  while ($data1 = $query1->fetch_row()) {
    if (($c_hladiny > 1) || (($c_hladiny == 1) && ($data1[1] == $pocatek))) {
      if ($reshladiny[$c_hladiny][$data1[2]] == NULL) {
        $data = new Vector();
        $reshladiny[$c_hladiny][$data1[2]] = $data;
      }

      $data = $reshladiny[$c_hladiny][$data1[2]];
      $hrana = new TSpojPart();
      $hrana->linka = $data1[0];
      $hrana->Zod = $data1[1];
      $hrana->Zdo = $data1[2];
      $hrana->Tod = $data1[3];
      $hrana->Tdo = $data1[4];
      $hrana->smer = $data1[5];
      $data->addElement($hrana);
    }
  }
  }
  return $reshladiny;
}

function sortSpoje($Spoje, $location, $packet) {
  for ($i = 0; $i < $Spoje->size(); $i++) {
    for ($ii = 0; $ii < ($Spoje->size() - 1); $ii++) {
      $Spoj = $Spoje->elementAt($ii);
      if ($Spoj->CASod > $Spoje->elementAt($ii + 1)->CASod) {
        $Spoje->addElementAt($Spoje->elementAt($ii + 1), $ii);
        $Spoje->addElementAt($Spoj, ($ii + 1));
      }
    }
  }

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

  $Spoje = $SpojeEliminate;

  $SpojeEliminate = new Vector();
  for ($i = 0; $i < $Spoje->size(); $i++) {
    $mam = false;
    for ($ii = ($i + 1); $ii < $Spoje->size(); $ii++) {
      if (($Spoje->elementAt($i)->CASod < $Spoje->elementAt($ii)->CASod) && ($Spoje->elementAt($i)->CASdo >= $Spoje->elementAt($ii)->CASdo)) {
        $mam = true;
        break;
      }
    }
    if ($mam == false) {
      $SpojeEliminate->addElement($Spoje->elementAt($i));
    }
  }

  for ($i = 0; $i < $SpojeEliminate->size(); $i++) {
    for ($ii = 0; $ii < $SpojeEliminate->elementAt($i)->useky->size(); $ii++) {
      $linky = getLinkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linka, $location, $packet);
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->linkaNazev = $linky[0];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->doprava = $linky[1];
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->ZodText = getZastavkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zod, $location, $packet);
      $SpojeEliminate->elementAt($i)->useky->elementAt($ii)->ZdoText = getZastavkaText($SpojeEliminate->elementAt($i)->useky->elementAt($ii)->Zdo, $location, $packet);
    }
  }

  return $SpojeEliminate;
}

$dbname = 'savvy_mhdspoje';

$Vdo = new Vector();
$Vdo->addElement($pocatek);

$Vod = new Vector();
$Vod->addElement($pocatek);

$hladiny[0][$pocatek] = new Vector();

$sql = "SELECT datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

$varGRF = 0;
$min = $H * 60 + $M + $dobaSpoje;
$max = $H * 60 + $M;
$dobaPrestup = 10;
$maxPrestup = $dobaPrestup;
$minPrestup = 2;

while ($row = $result->fetch_row()) {
  /*  if ($varGRF != '') {
    $varGRF .= ",";
    } */
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";

  $mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqliVARGRF->query("SET NAMES 'utf-8';");
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

$iter = 0;
while ((getVarray($Vod) != '') && ($iter <= $pocetprestupu)) {
//  $hladiny = getOdjezdy($location, $packet, $Vod, $Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $iter + 1, $cil); 
  $hladiny = getOdjezdy($location, $packet, $Vod, $Vdo, $H, $M, $dobaSpoje, $varGRF, $hladiny, $iter + 1, $cil, $pocatek, $min, $max, $dobaPrestup);//getTrasy($location, $packet, $Vod, $Vdo, $hladiny, $iter + 1, $cil);
  if (count($hladiny[$iter]) <= 0) {
    $iter = $pocetprestupu;
  }
  $iter++;
}

 echo '</br>';

  while (list($index, $stav) = each($hladiny)) {
  echo '</br></br> --- hladina : ' . $index . ' --- </br>';
  while (list($index1, $stav1) = each($hladiny[$index])) {
  echo '           --- zastavka --- ' . $index1 . '</br>';
  for ($i = 0; $i < $hladiny[$index][$index1]->size(); $i++) {
  echo $hladiny[$index][$index1]->elementAt($i)->linka . ' , ' . $hladiny[$index][$index1]->elementAt($i)->Zod . ' , ' . $hladiny[$index][$index1]->elementAt($i)->Zdo . ' , ' . $hladiny[$index][$index1]->elementAt($i)->CASod . ' - ' . $hladiny[$index][$index1]->elementAt($i)->CASdo . '  ->  </br>';
  }
  }
  } 

$Spoje = new Vector();

echo '</br></br>';

for ($ii = count($hladiny) - 1; $ii >= 1; $ii--) {
  $a = $hladiny[$ii][$cil];
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
          $castSpojr = findTrasaHladina($h, $hladiny, $doZ); //findOdjezdHladina($h, $hladiny, $doZ, $casDO);
          echo $castSpojr->linka . ' , ' . $castSpojr->Zod . ' , ' . $castSpojr->Zdo . ' , ' . $castSpojr->CASod . ' - ' . $castSpojr->CASdo . ' -> ';
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
  }
}

/*$hladinypack[0][$pocatek] = new Vector();

$min = $H * 60 + $M + $dobaSpoje;
$max = $H * 60 + $M;
$dobaPrestup = 10;
$maxPrestup = $dobaPrestup;
$minPrestup = 2;

for ($h = 0; $h < count($hladiny); $h++) {
  $Vod = new Vector();
  $Vdo = new Vector();
  for ($i = 0; $i < $Spoje->size(); $i++) {
    $Spoj = $Spoje->elementAt($i)->useky->elementAt($h);
    if ($Spoj != NULL) {
      if (in_array((string) $Spoj->Zod, $Vod->toArray(), FALSE) == FALSE) {
        $Vod->addElement($Spoj->Zod);
      }
      if (in_array((string) $Spoj->Zdo, $Vdo->toArray(), FALSE) == FALSE) {
        $Vdo->addElement($Spoj->Zdo);
      }
    }
  }*/
  /*  echo '</br></br>';
    echo 'hladina zastavek : ' . $h . '</br>';
    echo getVarray($Vod);
    echo '</br>';
    echo getVarray($Vdo); */

/*  if (($Vod->size() > 0) && ($Vdo->size() > 0)) {
    $hladinypack = getOdjezdy($location, $packet, $Vod, $Vdo, $H, $M, $dobaSpoje, $varGRF, $hladinypack, $h + 1, $cil, $pocatek, $min, $max, $dobaPrestup);
  }
}

$hladiny = $hladinypack;*/

/*echo '</br>';

while (list($index, $stav) = each($hladiny)) {
  echo '</br></br> --- hladina : ' . $index . ' --- </br>';
  while (list($index1, $stav1) = each($hladiny[$index])) {
    echo '           --- zastavka --- ' . $index1 . '</br>';
    for ($i = 0; $i < $hladiny[$index][$index1]->size(); $i++) {
      echo $hladiny[$index][$index1]->elementAt($i)->linka . ' , ' . $hladiny[$index][$index1]->elementAt($i)->Zod . ' , ' . $hladiny[$index][$index1]->elementAt($i)->Zdo . ' , ' . $hladiny[$index][$index1]->elementAt($i)->CASod . ' - ' . $hladiny[$index][$index1]->elementAt($i)->CASdo . '  ->  </br>';
    }
  }
}*/

$Spoje = new Vector();

//echo '</br></br>';

for ($ii = count($hladiny) - 1; $ii >= 1; $ii--) {
  $a = $hladiny[$ii][$cil];
//  echo 'hladina == ' . $ii . '</br></br>';
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

//      echo '</br>';
//      echo $castSpoj->linka . ' , ' . $castSpoj->Zod . ' , ' . $castSpoj->Zdo . ' , ' . $castSpoj->CASod . ' - ' . $castSpoj->CASdo . ' -> ';
      if ($ii > 1) {
        for ($h = $hl; $h > 0; $h--) {
          $castSpojr = findOdjezdHladina($h, $hladiny, $doZ, $casDO, $maxPrestup, $minPrestup);
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
//      echo "vyhledaneSpoje = new TListSpojeni();";
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
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        case 1: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        case 11: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        default: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
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
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='event.cancelBubble; event.stopPropagation();  selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        case 1: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        case 11: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
            break;
          }
        default: {
            $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastOd . ");'>&nbsp;</img>";
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
//echo $res;
?>
