<?php
$mMil = round(microtime(true) * 1000);
$pocatek = $_GET[pocatek];
$cil = $_GET[cil];
$location = $_GET[location];
$packet = $_GET[packet];
$H = $_GET['h'];
$M = $_GET['m'];
if (isset($_GET[dobaS])) {
  $maxcas = $_GET[dobaS];
} else {
  $maxcas = 120;
}
if (isset($_GET[minP])) {
  $minprestup = $_GET[minP];
} else {
  $minprestup = 1;
}

$plus_min_prestup_pesi_hrana = 1;

if (isset($_GET[maxP])) {
  $maxprestup = $_GET[maxP];
} else {
  $maxprestup = 30;
}

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

if (isset($_GET[prime])) {
  $prime = $_GET[prime];
} else {
  $prime = 0;
}

$day = date_format(new DateTime($datumJR), 'd');
$month = date_format(new DateTime($datumJR), 'm');
$year = date_format(new DateTime($datumJR), 'Y');

if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
} else {
  $lang = 'cz';
}
if ($lang == '') {
  $lang = 'cz';
}

if ($lang == 'cz') {
  require_once '../../lib/CZlang.php';
}

if ($lang == 'sk') {
  require_once '../../lib/SKlang.php';
}

require_once '../../lib/functions.php';

class TLinka {

  var $c_linky = null;
  var $nazev_linky = '';
  var $doprava = '';

}

class TZastavka {

  var $id_zastavky = null;
  var $nazev_zastavky = null;
  // --- SPOJENI
  var $odZastavky = null; //TZastavka
  var $vaha = PHP_INT_MAX;
  var $prijezd = -1;
  var $odjezd = -1;
  var $pocet_prestupu = 0;
//    public TLinka odjezd_linka = null;
  var $Hrana = null;    //THrana
  var $LOCA = 0;
  var $LOCB = 0;
  var $AT = '';
  var $BT = '';

  // --- END SPOJENI
}

class THrana {

  var $c_linky = null; //TLinka
  var $ZastavkaOD = null; //TZastavka
  var $ZastavkaDO = null;  //TZastavka
  var $tarifOD;
  var $tarifDO;
  var $id_smeru = -1;
  var $id_chrono;
  var $startCas = 0;
  var $endCas = 0;
  var $doba_jizdy;
  var $c_spoje = null;

}

class TSpojeniBody {

  var $BodyListActive = null; //TZastavka
  var $BodyListInActive = null; //TZastavka

}

class TSpojeni {

  var $cislo_spoje = -1;
  var $cislo_spojeni = 0;
  var $PartSpoje = null; //ArrayList<TPartSpojeni>

}

class TPartSpojeni {

  var $odjezd = -1;
  var $prijezd = -1;
  var $text_odjezd = null;
  var $text_prijezd = null;
  var $c_linky = null;
  var $nazev_linky = null;
  var $typ_dopravy = -1;
  var $od_zastavky = null; //TZastavka
  var $do_zastavky = null; //TZastavka
  var $smer = -1;
  var $c_spoje = null;
  var $tarif_od = null;
  var $tarif_do = null;

}

class TSpojItem {

  var $MM = null;
  var $chrono = null;
  var $c_spoje = null;
  var $kodpozn = 0;

}

class TSpoj {

  var $c_linky = null;
  var $od_zastavky = null;
  var $do_zastavky = null;
  var $od_tarif = null;
  var $do_tarif = null;
  var $smer = null;
  var $startCAS = null;
  var $endCAS = null;
  var $doba_jizdy = null;
  var $c_spoje = null;

}

class TZastavkaItem {

  var $c_linky = null;
  var $c_tarif = null;
  var $A = 0;
  var $B = 0;
  var $prestup = 0;

}

class TPrestup {

  public $c_linky;
  public $od_tarif = null;
  public $do_zastavky;
  public $do_tarif = null;
  public $smer = null;
  public $doba = null;
  public $vzdalenost = null;

  public function create($_c_linky, $_do_zastavky, $_doba, $_vzdalenost) {
    $this->c_linky = $_c_linky;
    $this->do_zastavky = $_do_zastavky;
    $this->doba = $_doba;
    $this->vzdalenost = $_vzdalenost;
  }

}

$CHRONO = null; //[c_linky][smer][chrono][c_tarif]
$SPOJE = null; //[c_linky][smer][HH]
$HRANY = null; //[do_zastavky]
/* $ZASTAVKY_LINKY = null; //[c_zastavky][c_linky][c_tarif]TZastavkaItem
  $LINKY_ZASTAVKY = null; //[c_linky][c_zastavky][c_tarif]TZastavkaItem */
$PESOBUS_ZASTAVKY = null; //[od_zastavky][do_zastavky]TZastavkaItem
$PRESTUPY = null;
$PRESTUPY_ALL = null;
/* $PRESTUPY_CACHE = null; */
$LINKY = null;
$ZASTAVKY = null;
$OLD_HRANY = null;

$SpojeniBody = null;
$myarraylist = null;
$pocet = 0;
$pocetprestupu = 0;

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqliVARGRF->query("SET NAMES 'utf-8';");

$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
$result = $mysqli->query($sql);

$varGRF = 0;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

function getLINKA($c_linky) {
  global $LINKY;
  global $location;
  global $packet;

  $res = null;

  if ($LINKY[$c_linky] == null) {
    if ($c_linky != -1) {
      $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
      $mysqli->query("SET NAMES 'utf-8';");

      $sql = "select linky.c_linky, linky.nazev_linky, linky.doprava from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = \"" . $c_linky . "\"";
//      echo $sql . "</br>";
      $result = $mysqli->query($sql);

      $row = $result->fetch_row();
      $res = new TLinka();
      $res->c_linky = $row[0];
      $res->nazev_linky = $row[1];
      $res->doprava = $row[2];

      $LINKY[$c_linky] = $res;
    } else {
      $res = new TLinka();
      $res->c_linky = -1;
      $res->nazev_linky = "";
      $res->doprava = "P";

      $LINKY[$c_linky] = $res;
    }
  } else {
    $res = $LINKY[$c_linky];
  }

  return $res;
}

function getZASTAVKA($c_zastavky, $linka, $ctarif) {
  global $ZASTAVKY;
  global $location;
  global $packet;

  $res = null;

  if ($ZASTAVKY[$c_zastavky] == null) {
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");

    $sql = "select zastavky.c_zastavky, zastavky.nazev, zastavky.LOCA, zastavky.LOCB from zastavky
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zastavky.c_zastavky = " . $c_zastavky;
    $result = $mysqli->query($sql);

    $row = $result->fetch_row();
    $res = new TZastavka();
    $res->id_zastavky = $row[0];
    $res->nazev_zastavky = $row[1];
    $res->LOCA = $row[2];
    $res->LOCB = $row[3];

    if ($linka != -1) {
      $sql = "select zaslinky.A1_tarif as AT, zaslinky.B1_tarif as BT from zaslinky
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "' and zaslinky.c_tarif = " . $ctarif;
      //   echo $sql;
      $result = $mysqli->query($sql);
      $row = $result->fetch_row();
      $res->AT = $row[0];
      $res->BT = $row[1];
    }

    $ZASTAVKY[$c_zastavky] = $res;
  } else {
    $res = $ZASTAVKY[$c_zastavky];
  }

  return $res;
}

function getPrujezdy($od_zastavky, $cil) {
  global $ZASTAVKY_LINKY;
  global $LINKY_ZASTAVKY;
  global $PRESTUPY;
  global $PRESTUPY_CACHE;
  global $PESOBUS_ZASTAVKY;
  global $pocet;
  global $PRESTUPY_ALL;


  /* if ($PRESTUPY_CACHE[$od_zastavky] != null) {
    $PRESTUPY = $PRESTUPY_CACHE[$od_zastavky];
    } else {
    $PRESTUPY = null;

    $pesobus = $PESOBUS_ZASTAVKY[$od_zastavky];
    if (count($pesobus) > 0) {
    foreach ($pesobus as $key_do_zastavky => $val) {
    if (($key_do_zastavky != $cil)) {
    $PRESTUPY[$val->c_linky . "_" . $val->c_tarif . "_" . $key_do_zastavky . "_" . $val->c_tarif] = $val;
    }
    }
    }

    $_ZASTAVKY_LINKY = $ZASTAVKY_LINKY[$od_zastavky];
    if (count($_ZASTAVKY_LINKY) > 0) {
    foreach ($_ZASTAVKY_LINKY as $key_c_linky => $val) {
    foreach ($val as $key_c_tarif => $val1) {
    $zastavka = $val1;
    $_LINKY_ZASTAVKY = $LINKY_ZASTAVKY[$zastavka->c_linky];
    foreach ($_LINKY_ZASTAVKY as $l_key_c_zastavky => $l_val) {
    foreach ($l_val as $l_key_c_tarif => $l_val1) {
    $zastavka1 = $l_val1;
    if (($zastavka1->prestup) || ($l_key_c_zastavky == $cil)) {
    $smer = $zastavka->c_tarif - $zastavka1->c_tarif; //odtarif - dotarif
    if ($smer < 0) {
    $smer = 0;
    } else {
    $smer = 1;
    }
    if (($od_zastavky != $l_key_c_zastavky) ) {
    if ((($smer == 0) && ($zastavka->A == 1) && ($zastavka1->A == 1)) || (($smer == 1) && ($zastavka->B == 1) && ($zastavka1->B == 1))) {
    if ($PRESTUPY[$zastavka->c_linky . "_" . $zastavka->c_tarif . "_" . $l_key_c_zastavky . "_" . $zastavka1->c_tarif] == null) {
    $prestup = new TPrestup();
    $prestup->c_linky = $zastavka->c_linky;
    $prestup->od_tarif = $zastavka->c_tarif;
    $prestup->do_zastavky = $l_key_c_zastavky;
    $prestup->do_tarif = $zastavka1->c_tarif;
    $PRESTUPY[$zastavka->c_linky . "_" . $zastavka->c_tarif . "_" . $l_key_c_zastavky . "_" . $zastavka1->c_tarif] = $prestup;
    }
    }
    }
    }
    }
    }
    }
    }
    }

    $PRESTUPY_CACHE[$od_zastavky] = $PRESTUPY;
    $pocet++;
    } */

  return $PRESTUPY_ALL[$od_zastavky];
}

function getSpojeODDO($odZastavky, $pocatek, $cil, $cas, $linka_pred, $oldHrany) {
  global $SPOJE;
  global $CHRONO;
  global $HRANY;
  global $PRESTUPY;
  global $PESOBUS_ZASTAVKY;
  global $minprestup;
  global $plus_min_prestup_pesi_hrana;
  global $maxprestup;
  global $maxcas;
  global $location;
  global $packet;
  global $PRESTUPY_ALL;
  global $varGRF;
  global $pocet;
  //$pocet1 = 0;

  $HRANY = null;
  //$mMil = round(microtime(true) * 1000);
  $PRESTUPY = $PRESTUPY_ALL[$odZastavky];//= getPrujezdy($odZastavky, $cil);
  $pocet++;
//  echo "Prujezdy " . (round(microtime(true) * 1000) - $mMil) . "</br>";
//  echo "Pocet prestupu = " . count($PRESTUPY) . "<br>";
  if (count($PRESTUPY) > 0) {
    for($i = 0; $i < count($PRESTUPY); $i++) {
      $odHH = (int) (($cas - 60) / 60);
      $doHH = (int) ((($cas + $maxcas) / 60));
      $PRESTUPY[$i] = (object) $PRESTUPY[$i];
      if (($PRESTUPY[$i]->prestup == 1) || ($PRESTUPY[$i]->do_zastavky == $cil)) {
        if (1==1) {//(  (($PRESTUPY[$i]->c_linky != $linka_pred) || ($oldHrany[$PRESTUPY[$i]->c_linky . "|" . $odZastavky . "|" . $PRESTUPY[$i]->do_zastavky] == null)) || (($PRESTUPY[$i]->c_linky != $linka_pred) || ($oldHrany[$PRESTUPY[$i]->c_linky . "|" . $odZastavky . "|" . $PRESTUPY[$i]->do_zastavky] != 1)) ) {
//      echo "l : " . $PRESTUPY[$i]->c_linky . "<br>";
/*        if ($odZastavky ==327) {
          echo $PRESTUPY[$i]->c_linky . " | " . $PRESTUPY[$i]->do_zastavky . "<br>";
        }*/
      if (($PRESTUPY[$i]->c_linky == -1) && ($odZastavky != $pocatek)) {
        $endCAS = $cas + $PRESTUPY[$i]->doba;
//        echo $PRESTUPY[$i]->c_linky . " | " . $odZastavky . " -> " .  $PRESTUPY[$i]->do_zastavky . " : " . $cas . " -> " . $endCAS . " ( " .  $PRESTUPY[$i]->doba . " ) linka pred " . $linka_pred . " cas " . $cas . "</br>";
        if ($HRANY[$PRESTUPY[$i]->do_zastavky] == null) {
          $pomSpoj = new TSpoj();
          $pomSpoj->c_linky = $PRESTUPY[$i]->c_linky; //$c_linky;
          $pomSpoj->od_zastavky = $odZastavky; //$od_zastavky;
          $pomSpoj->do_zastavky = $PRESTUPY[$i]->do_zastavky; //$do_zastavky;
          $pomSpoj->od_tarif = $PRESTUPY[$i]->od_tarif; //$od_tarif;
          $pomSpoj->do_tarif = $PRESTUPY[$i]->do_tarif; //$do_tarif;
          $pomSpoj->smer = $PRESTUPY[$i]->smer;
          $pomSpoj->startCAS = $cas;
          $pomSpoj->endCAS = $cas + $PRESTUPY[$i]->doba; //$doba_pesobus;
          $pomSpoj->doba_jizdy = $PRESTUPY[$i]->doba; //$doba_pesobus;
          $pomSpoj->c_spoje = -1;
//          if (($HRANY[$odZastavky] == NULL) ||
          $HRANY[$PRESTUPY[$i]->do_zastavky] = $pomSpoj;
 //         echo /*"7 - " . */ $PRESTUPY[$i]->c_linky . " 1- "  . $cas . " - " . $endCAS . "</br>";
        } else {
          if ($HRANY[$PRESTUPY[$i]->do_zastavky]->endCAS > $endCAS) {
            $pomSpoj = $HRANY[$PRESTUPY[$i]->do_zastavky];
            $pomSpoj->c_linky = $PRESTUPY[$i]->c_linky; //$c_linky;
            $pomSpoj->od_zastavky = $odZastavky; //$od_zastavky;
            $pomSpoj->do_zastavky = $PRESTUPY[$i]->do_zastavky; //$do_zastavky;
            $pomSpoj->od_tarif = $PRESTUPY[$i]->od_tarif; //$od_tarif;
            $pomSpoj->do_tarif = $PRESTUPY[$i]->do_tarif; //$do_tarif;
            $pomSpoj->smer = $PRESTUPY[$i]->smer;
            $pomSpoj->startCAS = $cas;
            $pomSpoj->endCAS = $cas + $PRESTUPY[$i]->doba; //$doba_pesobus;
            $pomSpoj->doba_jizdy = $PRESTUPY[$i]->doba; //$doba_pesobus;
            $pomSpoj->c_spoje = -1;
 //           echo /*"7 - " . */ $PRESTUPY[$i]->c_linky . " 2- "  . $cas . " - " . $endCAS . "</br>";
          }
        }
      } else {
        if (1==1/*($oldHrany[$odZastavky] == null) || ($oldHrany[$odZastavky]->do_zastavky != $PRESTUPY[$i]->do_zastavky)*/) {
        while (($odHH <= $doHH) /* && ($konec == false) */) {
//          echo $PRESTUPY[$i]->c_linky . " | " . $PRESTUPY[$i]->do_zastavky . " - " . $PRESTUPY[$i]->smer . "<br>";
          if ($SPOJE[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$odHH] != null) {
            for ($ii = 0; $ii < count($SPOJE[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$odHH]); $ii++) {
//              echo " ii = " . $ii . "</br>";
              $pomSpojItem = (object) $SPOJE[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$odHH][$ii];
//              echo $pomSpojItem->kodpozn . " , " . $varGRF . " , " . ($pomSpojItem->kodpozn & $varGRF) . "<br>";
              if ((($pomSpojItem->kodpozn & $varGRF) > 0) || ($pomSpojItem->kodpozn = 0)) {
//              echo "povolena " . $pomSpojItem->kodpozn . "<br>";
//              echo " chrono : " . $pomSpojItem->chrono . "</br>";
//                echo $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->od_tarif/* $od_tarif */] . " -> " . $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->do_tarif/* $do_tarif */] . "<br>";
              if (($CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->od_tarif/* $od_tarif */] != null) && ($CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->do_tarif/* $do_tarif */] != null)) {
//                echo "vypis  MM chrona : " . $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->od_tarif/* $od_tarif */];
                $startCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->od_tarif/* $od_tarif */];
                $endCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->do_tarif/* $do_tarif */];
//                $doba_jizdy = $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif] - $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
//                                if (($c_linky == 9406) && ($do_zastavky == 22)) {
//                  echo $c_linky . " | " . $od_zastavky . " -> " . $do_zastavky . " : " . $startCAS . " -> " . $endCAS . " ( " . $doba_jizdy . " ) linka pred " . $linka_pred . " cas " . $cas . " spoj " . $pomSpojItem->c_spoje . "</br>";
//                  }
//                  if ($c_linky == 720) {
//                $endCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$value->c_linky/*$c_linky*/][$smer][$pomSpojItem->chrono][$value->do_tarif/*$do_tarif*/];
      //            echo $PRESTUPY[$i]->c_linky . " | " . $odZastavky . " -> " .  $PRESTUPY[$i]->do_zastavky . " : " . $startCAS . " -> " . $endCAS . " ( " .  (($startCAS - $cas) + ($endCAS - $startCAS)) . " ) linka pred " . $linka_pred . " cas " . $cas . "</br>";
//                  }
                /*            if ($od_zastavky == $pocatek) {
                  $pommincekani = 0;
                  } else {
                  $pommincekani = $minprestup;
                  } */
//              echo "cas + pommincekani = " . ($cas + $pommincekani) . " | " . "cas + maxprestup = " . ($cas + $maxprestup) . " \ " . $startCAS . "</br>";
                if ((($startCAS - $cas <= $maxprestup) && ($startCAS - $cas >= (($linka_pred != -1) ? $minprestup : $plus_min_prestup_pesi_hrana))) || (($linka_pred == $PRESTUPY[$i]->c_linky) && ($startCAS == $cas)) || (($odZastavky/* $od_zastavky */ == $pocatek) && ($startCAS >= $cas))) {
                  //if (($cas + $pommincekani <= $startCAS) && ($cas + $maxprestup >= $startCAS)) {
                  $endCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->do_tarif/* $do_tarif */];
                  $doba_jizdy = $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->do_tarif/* $do_tarif */] - $CHRONO[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$pomSpojItem->chrono][$PRESTUPY[$i]->od_tarif/* $od_tarif */];
                  if ($HRANY[$PRESTUPY[$i]->do_zastavky/* $do_zastavky */] == null) {
//                  echo " ----- 1 ----- </br>";
                    $pomSpoj = new TSpoj();
                    $pomSpoj->c_linky = $PRESTUPY[$i]->c_linky; //$c_linky;
                    $pomSpoj->od_zastavky = $odZastavky; //$od_zastavky;
                    $pomSpoj->do_zastavky = $PRESTUPY[$i]->do_zastavky; //$do_zastavky;
                    $pomSpoj->od_tarif = $PRESTUPY[$i]->od_tarif; //$od_tarif;
                    $pomSpoj->do_tarif = $PRESTUPY[$i]->do_tarif; //$do_tarif;
                    $pomSpoj->smer = $PRESTUPY[$i]->smer;
                    $pomSpoj->startCAS = $startCAS;
                    $pomSpoj->endCAS = $endCAS;
                    $pomSpoj->doba_jizdy = $doba_jizdy;
                    $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
                    $HRANY[$PRESTUPY[$i]->do_zastavky/* $do_zastavky */] = $pomSpoj;
//                                        if ($c_linky == 720) {
             //         echo /*"7 - " . */ $PRESTUPY[$i]->c_linky . " 1- "  . $startCAS . " - " . $endCAS . "</br>";
                    $ii = count($SPOJE[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$odHH]);
                    $odHH = $doHH + 1;
//                      }
//                      if ($c_linky == 9406) {
//                      echo "94 - " . $startCAS . " - " . $endCAS . "</br>";
//                      }
                  } else {
//                  echo " ----- 2 ----- </br>";
                    $pomSpoj = $HRANY[$PRESTUPY[$i]->do_zastavky/* $do_zastavky */];
                    if ($pomSpoj->endCAS > $endCAS) {
                      $pomSpoj->c_linky = $PRESTUPY[$i]->c_linky; //$c_linky;
                      $pomSpoj->od_zastavky = $odZastavky; //$od_zastavky;
                      $pomSpoj->do_zastavky = $PRESTUPY[$i]->do_zastavky; //$do_zastavky;
                      $pomSpoj->od_tarif = $PRESTUPY[$i]->od_tarif; //$od_tarif;
                      $pomSpoj->do_tarif = $PRESTUPY[$i]->do_tarif; //$do_tarif;
                      $pomSpoj->smer = $PRESTUPY[$i]->smer;
                      $pomSpoj->startCAS = $startCAS;
                      $pomSpoj->endCAS = $endCAS;
                      $pomSpoj->doba_jizdy = $doba_jizdy;
                      $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
//                                            if ($c_linky == 720) {
             //         echo  $PRESTUPY[$i]->c_linky . " 2- " . $startCAS . " - " . $endCAS . "</br>";
                      $ii = count($SPOJE[$PRESTUPY[$i]->c_linky/* $c_linky */][$PRESTUPY[$i]->smer][$odHH]);
                      $odHH = $doHH + 1;
//                        }
//                        if ($c_linky == 9406) {
//                        echo "94 - " . $startCAS . " - " . $endCAS . "</br>";
//                        }
                    }
                  }
                }
              }
            }
            }
          }
          $odHH++;
        }
        }
      }
      }
      }
//      echo "</br>";
//      }
//  echo $row[0] . " , " . $row[1] . " , " . $row[2] . " , " . $row[3] . " , " . $row[4] . " , " . $row[5] . " , " . $row[6] . " , " . $row[7] . " , " . $row[8] . "</br>";
    }
  }
//  echo "pocet1 prestupu = " . $pocet1 . "</br>";
}

function addActive($zastavka) {
  global $SpojeniBody;
  $SpojeniBody->BodyListActive[$zastavka->id_zastavky] = $zastavka;
}

function getActiveZastavkaById($zastavkaID) {
  global $SpojeniBody;
  return $SpojeniBody->BodyListActive[$zastavkaID];
}

function getInActiveZastavkaById($zastavkaID) {
  global $SpojeniBody;
  return $SpojeniBody->BodyListInActive[$zastavkaID];
}

function moveActiveToInActive($zastavkaID) {
  global $SpojeniBody;
  $pomZastavka = null;
  $pomZastavka = $SpojeniBody->BodyListActive[$zastavkaID];
  if ($pomZastavka != null) {
    $SpojeniBody->BodyListInActive[$zastavkaID] = $pomZastavka;
    unset($SpojeniBody->BodyListActive[$zastavkaID]);
  }
}

function getSpojeniMinimum() {
  global $SpojeniBody;
//  $mMil = round(microtime(true) * 1000);
  $res = null;
  $min = PHP_INT_MAX;
  $pom = $SpojeniBody->BodyListActive;
//  echo count($pom) . "<br>";
  foreach ($pom as $key_c_zastavky => $val) {
    $pomZastavka = $val;
//    echo "min > " . $key_c_zastavky . " (" . $pomZastavka->vaha . ") <br>";
    if ($pomZastavka->vaha <= $min) {
      $min = $pomZastavka->vaha;
      $res = $pomZastavka;
    }
  }
  if ($res != null) {
    moveActiveToInActive($res->id_zastavky);
  }
//  echo "vyber minima " . (round(microtime(true) * 1000) - $mMil) . "</br>";
  return $res;
}

function getSpojeni($od_zastavky, $do_zastavky, $odcas) {
  global $location;
  global $packet;
  global $varGRF;
  global $maxcas;
  global $minprestup;
  global $maxprestup;
  global $SpojeniBody;
  global $myarraylist;
  global $HRANY;
  global $OLD_HRANY;

  global $pocetprestupu;

  $CILE = null;
  $cas = $odcas;
  $pocet_spojeni = 0;
  $konec_spojeni = 0;
  $cislo_spoje = 0;
  $obj = null; //TPartSpojeni
  $konec = false;
  $pocatecni_pruchod = true;

  while ($konec == false) {
    $start = new TZastavka(); // DataZastavky.getZastavkaById(od_zastavky);
    $start->id_zastavky = $od_zastavky;
    $start->prijezd = $cas;
    $pHH = (int) (($cas / 60) % 24);
    $pMM = (int) ($cas % 60);
    echo "start cas = " . $pHH . ":" . $pMM . "<br>";
    $start->vaha = 0;
    $SpojeniBody = new TSpojeniBody(); //TSpojeniBody SpojeniBody = new TSpojeniBody();
//    echo "velikosti = " . count($SpojeniBody->BodyListActive) . "," . count($SpojeniBody->BodyListInActive) . "</br>";
    addActive($start);

//    echo "start cas = " . (int) (($cas / 60) % 24) . ":" . (int) ($cas % 60) . "<br>";
//    echo "pocet hran = " . count($HRANY) . "<br>";
    $bod = getSpojeniMinimum(/* SpojeniBody */);  //TZastavka
//    echo "c_linky = " . $bod->Hrana->c_linky . " ze zastavky = " . $bod->id_zastavky . "<br>";
    while (($bod != null) /*&& ($bod->id_zastavky != $do_zastavky)*/) {
//                Hashtable</*TZastavka*/String, THrana> HranyList = getSpojeOdDo(String.valueOf(bod.id_zastavky), od_zastavky, do_zastavky, Day, bod.prijezd, SpojeniBody, CalendCode);

      /*      $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
        $mysqli->query("SET NAMES 'utf-8';");
        $sql = "call getSpojeOdDo(" . $location . " ," . $packet . ", " . $bod->id_zastavky . ", " . $do_zastavky . ", " . $bod->prijezd . ", " . (($od_zastavky == $bod->id_zastavky) ? 0 : $minprestup) . ", " . $maxprestup . ", " . $varGRF . ");";
        //      echo $sql . "</br>";
        $result = $mysqli->query($sql); */

      getSpojeODDO($bod->id_zastavky, $od_zastavky, $do_zastavky, $bod->prijezd, $bod->Hrana->c_linky, $OLD_HRANY);
//      echo "SpojeODDO " . (round(microtime(true) * 1000) - $mMil) . "</br>";
//      echo "" . count($HRANY) . "</br>";

      if (count($HRANY) > 0) {
        foreach ($HRANY as $key_c_zastavky => $val) {
          $OLD_HRANY[$val->c_linky . "|" . $val->do_zastavky . "|" . $val->od_zastavky] = 1;
          $row = null;
          $row[0] = $val->c_linky;
          $row[2] = $val->do_zastavky;
          $row[3] = $val->od_tarif;
          $row[4] = $val->do_tarif;
          $row[5] = $val->smer;
          $row[6] = $val->startCAS;
          $row[7] = $val->endCAS;
          $row[8] = $val->doba_jizdy;
          $row[9] = $val->c_spoje;

//          echo "--- " . $val->c_linky . " , " . $key_c_zastavky . " , " . $val->do_zastavky . " , " . $val->startCAS . " , " . $val->endCAS . " , " . $val->doba_jizdy . "</br>";

/*          if ($pocatecni_pruchod) {
            echo "z pocatku " . getLINKA($val->c_linky)->nazev_linky . " (" . getZASTAVKA($od_zastavky, $val->c_linky, $val->od_tarif)->nazev_zastavky . ")" . " - " . (int) (($val->startCAS / 60) % 24) . ":" . (int) ($val->startCAS % 60) . "<br>";
          }*/
//      while ($row = $result->fetch_row()) {
//                if (HranyList != null) {
//                    Enumeration<String> e = HranyList.keys();
//                    while (e.hasMoreElements()) {
          $pomHrana = new THrana(); //(THrana) HranyList.get((String) e.nextElement());
          //zalozit HRANU !!!
          $pomHrana->c_linky = $row[0];
          $pomHrana->ZastavkaOD = $bod;
          if (getActiveZastavkaById($row[2]) == null) {
            $pomZastavka = new TZastavka();
            $pomZastavka->id_zastavky = $row[2];
          } else {
            $pomZastavka = getActiveZastavkaById($row[2]);
          }
          $pomHrana->ZastavkaDO = $pomZastavka;
          $pomHrana->tarifOD = $row[3]; //Odjezd . tarifni_cislo;
          $pomHrana->tarifDO = $row[4]; //Linka . Trasy . getTrasaByIndex(iT) . tarifni_cislo;
          $pomHrana->startCas = $row[6]; //Odjezd . HH * 60 + Odjezd . MM;
          $pomHrana->endCas = $row[7]; //Odjezd . HH * 60 + Odjezd . MM + doba_jizdy;
          $pomHrana->doba_jizdy = $row[8]; //doba_jizdy;
          $pomHrana->id_smeru = $row[5]; //Odjezd . id_smeru;
          $pomHrana->c_spoje = $row[9];

          if ($val->do_zastavky == 33) {
          echo "do zastavky > " . $val->do_zastavky . "<br>";
          }
          if (1==1) {//(getInActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null) {
            if (((getActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null) && (getInActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null)) || ($val->do_zastavky == 33)) {
//              echo "new TZastavka<br>";
              $pomZastavka = new TZastavka();
              $pomZastavka->id_zastavky = $pomHrana->ZastavkaDO->id_zastavky;
              addActive($pomZastavka);
            }
            $doZastavky = getActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky); //DataZastavky.getZastavkaById(String.valueOf(pomHrana.ZastavkaDO.id_zastavky));
            $odZastavky = getInActiveZastavkaById($pomHrana->ZastavkaOD->id_zastavky); //DataZastavky.getZastavkaById(String.valueOf(pomHrana.ZastavkaOD.id_zastavky));

            $cekani = ($pomHrana->startCas - $odZastavky->prijezd);
            $pomVaha = $pomHrana->doba_jizdy + $cekani + $odZastavky->vaha;
            if ($pomVaha < $doZastavky->vaha) {
              $doZastavky->prijezd = $pomHrana->endCas;
              $doZastavky->vaha = $pomVaha;
              $doZastavky->odZastavky = $odZastavky;
              $doZastavky->Hrana = $pomHrana;

              if ($odZastavky->Hrana->c_linky != $pomHrana->c_linky) {
                $doZastavky->pocet_prestupu++;
              }
              if ($doZastavky->pocet_prestupu > 4) {
                $pocetprestupu++;
              }
            }
            //echo "--- " . $pomHrana->c_linky . " , " . $pomHrana->ZastavkaOD->id_zastavky . " , " . $pomHrana->ZastavkaDO->id_zastavky . " , " . $pomHrana->startCas . " , " . $pomHrana->endCas . " , " . $pomHrana->doba_jizdy . "</br>";
          }


          //echo count($SpojeniBody->BodyListActive) . "<br>";
      if ($val->do_zastavky == $do_zastavky) {
        $bod1 = getInActiveZastavkaById($do_zastavky);
        while (($bod1 != null) && ($bod1->odZastavky != null)) {

          $ppHH = (int) (($bod1->Hrana->startCas / 60) % 24);
          $ppMM = (int) ($bod1->Hrana->startCas % 60);
          echo "(" . $bod1->Hrana->c_linky . ") " . $bod1->odZastavky->id_zastavky . " - " . $bod1->Hrana->ZastavkaDO->id_zastavky . " ( " . $ppHH . ":" . $ppMM . " ) <br>";
          $bod1 = $bod1->odZastavky;
        }
        echo "----------------------------<br>";
        // $bod = getSpojeniMinimum();
      }



          /*          if ($bod->id_zastavky == 534) {
            echo "--- " . $pomHrana->c_linky . " , " . $pomHrana->ZastavkaOD->id_zastavky . " , " . $pomHrana->ZastavkaDO->id_zastavky . " , " . $pomHrana->startCas . " , " . $pomHrana->endCas . " , " . $pomHrana->doba_jizdy . "</br>";
            } */
        }
        $pocatecni_pruchod = false;
      }
//                }

      $bod = getSpojeniMinimum();
/*      echo count($SpojeniBody->BodyListActive) . "<br>";
      if ($bod->id_zastavky == $do_zastavky) {
        $bod = getInActiveZastavkaById($do_zastavky);
        while (($bod != null) && ($bod->odZastavky != null)) {

          $ppHH = (int) (($bod->Hrana->startCas / 60) % 24);
          $ppMM = (int) ($bod->Hrana->startCas % 60);
          echo "(" . $bod->Hrana->c_linky . ") " . $bod->odZastavky->id_zastavky . " - " . $bod->Hrana->ZastavkaDO->id_zastavky . " ( " . $ppHH . ":" . $ppMM . " ) <br>";
          $bod = $bod->odZastavky;
        }
        echo "----------------------------<br>";
         $bod = getSpojeniMinimum();
      }*/

//      $bod = getSpojeniMinimum();
//      echo "c_linky = " . $bod->Hrana->c_linky . " ze zastavky = " . $bod->id_zastavky . "<br>";
    }

//    for($icile = 0; $icile < count($CILE); $icile++) {
    $bod = getInActiveZastavkaById($do_zastavky);

    if ($bod == null) {
      $konec = true;
    } else {
      $spojeni = new TSpojeni();
      $spojeni->cislo_spoje = $cislo_spoje;
      $myarraylist[$cislo_spoje/* count($myarraylist) */] = $spojeni;
      $konec = false;
    }

    $first = false;
    while (($bod != null) && ($bod->odZastavky != null)) {

      $ppHH = (int) (($bod->Hrana->startCas / 60) % 24);
    $ppMM = (int) ($bod->Hrana->startCas % 60);
//      echo "(" . $bod->Hrana->c_linky . ") " . $bod->odZastavky->id_zastavky . " - " . $bod->Hrana->ZastavkaDO->id_zastavky . " ( " . $ppHH . ":" . $ppMM . " ) <br>";

      $myarraylist[$cislo_spoje]->cislo_spojeni = $pocet_spojeni;

      if ((count($myarraylist[$cislo_spoje]->PartSpoje) > 0)
              && ($myarraylist[$cislo_spoje]->PartSpoje[0]->c_linky == $bod->Hrana->c_linky)
              && ($myarraylist[$cislo_spoje]->PartSpoje[0]->smer == $bod->Hrana->id_smeru)) {
        $obj = $myarraylist[$cislo_spoje]->PartSpoje[0];
      } else {
        $obj = new TPartSpojeni();
        for ($i = count($myarraylist[$cislo_spoje]->PartSpoje) - 1; $i >= 0; $i--) {
          $pomobj = $myarraylist[$cislo_spoje]->PartSpoje[$i];
          $myarraylist[$cislo_spoje]->PartSpoje[$i + 1] = $pomobj;
        }
//        unset($myarraylist[$cislo_spoje]->PartSpoje[0]);
        $myarraylist[$cislo_spoje]->PartSpoje[0] = $obj; //??????????????? asi posunout ostatni dolu
      }

      $obj->odjezd = $bod->Hrana->startCas;
      $obj->c_spoje = $bod->Hrana->c_spoje;
      $obj->tarif_od = $bod->Hrana->tarifOD;
      $obj->tarif_do = $bod->Hrana->tarifDO;

      $HH = (int) (($obj->odjezd / 60) % 24);
      $MM = (int) ($obj->odjezd % 60);
      $obj->text_odjezd = (($HH < 10) ? '0' . $HH : $HH) . ':' . (($MM < 10) ? '0' . $MM : $MM);

      if ($obj->c_linky != $bod->Hrana->c_linky) {
        $obj->prijezd = $bod->Hrana->endCas; //bod.Hrana.endHH * 60 + bod.Hrana.endMM;//bod.prijezd;
        if ($first == false) {
          $first = true;
          if ($konec_spojeni != $obj->prijezd) {
            $pocet_spojeni++;
            $myarraylist[$cislo_spoje]->cislo_spojeni = $pocet_spojeni;
            $konec_spojeni = $obj->prijezd;
          }
        }
        $obj->c_linky = $bod->Hrana->c_linky; //bod.odZastavky.odjezd_linka.id_linky;
        $obj->nazev_linky = getLINKA($bod->Hrana->c_linky)->nazev_linky; //'nazev linky ' . $bod->Hrana->c_linky; //$bod->Hrana->nazev_linky;//bod.odZastavky.odjezd_linka.nazev_linky;
        $obj->typ_dopravy = getLINKA($bod->Hrana->c_linky)->doprava; //'doprava TOA'; //$bod->Hrana->typ_dopravy;//bod.odZastavky.odjezd_linka.typ_dopravy;

        $HH = (int) (($obj->prijezd / 60) % 24);
        $MM = (int) ($obj->prijezd % 60);
        $obj->text_prijezd = (($HH < 10) ? '0' . $HH : $HH) . ':' . (($MM < 10) ? '0' . $MM : $MM);
        $obj->smer = $bod->Hrana->id_smeru;
      }

      $cas = /* bod.Hrana.startHH * 60 + bod.Hrana.startMM */$bod->Hrana->startCas + 1;
      if ($cas > $odcas + $maxcas) {
        $konec = true;
      } else {
        $konec = false;
      }

      $obj->od_zastavky = $bod->odZastavky;
      $obj->do_zastavky = $bod;

      $bod = $bod->odZastavky;
    }
    $cislo_spoje++;
//  }
//  $CILE = null;
    if ($pocet_spojeni > 3) {
      $konec = true;
    }
  }


  /*  echo "eliminace spoju</br>";
    $velikostpole = count($myarraylist) - 1;
    for ($i = $velikostpole; $i >= 0; $i--) {
    if ($myarraylist[$i] != null) {
    if ($myarraylist[$i]->cislo_spojeni > 3) {
    unset($myarraylist[$i]);
    $myarraylist[$i] = null;
    }
    if ($i + 1 < count($myarraylist)) {
    if ($myarraylist[$i]->cislo_spojeni == $myarraylist[$i + 1]->cislo_spojeni) {
    unset($myarraylist[$i]);
    $myarraylist[$i] = null;
    }
    }
    }
    } */

  $pommyarraylist = null;
  for ($i = 0; $i < count($myarraylist); $i++) {
    if ($myarraylist[$i]->cislo_spojeni <= 3) {
      $pommyarraylist[$myarraylist[$i]->cislo_spojeni - 1] = $myarraylist[$i];
    }
  }
  $myarraylist = $pommyarraylist;
}

//$sMil = round(microtime(true) * 1000);

/* $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");

  $sql = "select c_linky, smer, HH, MM, chrono, c_spoje from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and ((spoje.kodpozn & " . $varGRF . ") > 0 or spoje.kodpozn = 0)  AND spoje.voz = 1";
  if ($location != 6) {
  $sql = $sql . " AND (spoje.vlastnosti & 2048) <> 2048";
  }
  $result = $mysqli->query($sql);

  while ($row = $result->fetch_row()) {
  $SpojItem = new TSpojItem();
  $SpojItem->MM = $row[3];
  $SpojItem->chrono = $row[4];
  $SpojItem->c_spoje = $row[5];
  $SPOJE[$row[0]][$row[1]][$row[2]][count($SPOJE[$row[0]][$row[1]][$row[2]])] = $SpojItem;
  }

  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");

  $sql = "select chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.c_tarif, chronometr.doba_pocatek, chronometr.c_zastavky, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B from chronometr
  left outer join zaslinky
  on (zaslinky.idlocation = chronometr.idlocation and zaslinky.packet = chronometr.packet and zaslinky.c_linky = chronometr.c_linky and zaslinky.c_tarif = chronometr.c_tarif)
  where chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . "  and chronometr.doba_jizdy > -1";
  $result = $mysqli->query($sql);

  while ($row = $result->fetch_row()) {
  $CHRONO[$row[0]][$row[1]][$row[2]][$row[3]] = $row[4];
  if ($ZASTAVKY_LINKY[$row[5]][$row[0]][$row[3]] == null) {
  $pomZastavka = new TZastavkaItem();
  $pomZastavka->c_linky = $row[0];
  $pomZastavka->c_tarif = $row[3];
  $pomZastavka->A = $row[7];
  $pomZastavka->B = $row[8];
  $pomZastavka->prestup = $row[6];
  $ZASTAVKY_LINKY[$row[5]][$row[0]][$row[3]] = $pomZastavka;
  $LINKY_ZASTAVKY[$row[0]][$row[5]][$row[3]] = $pomZastavka;
  }
  }

  $sql = "select od_zastavky, do_zastavky, cas, vzdalenost from pesobus
  where pesobus.idlocation = " . $location . " and pesobus.packet = " . $packet;
  $result = $mysqli->query($sql);

  while ($row = $result->fetch_row()) {
  $pPrestup = new TPrestup();
  $pPrestup->create(-1, $row[1], $row[2], $row[3]);
  $PESOBUS_ZASTAVKY[$row[0]][$row[1]] = $pPrestup;
  } */

$varGRF = getVARGRF($dob1 /* dd_mm_yyyy */, $location, $packet);

$path = "../../../jrstructure/" . $location . "/" . $packet . '/';

$SPOJE = loadStructure($path, "spoje.dat");
$CHRONO = loadStructure($path, "chrono.dat");
$PRESTUPY_ALL = loadStructure($path, "prestupy.dat");
$ZASTAVKY = loadStructure($path, "zastavky.dat");

/*$POMPRESTUP = $PRESTUPY_ALL[327];

for ($i = 0; $i < count($POMPRESTUP); $i++) {
  $POMPRESTUP[$i] = (object) $POMPRESTUP[$i];
  echo $POMPRESTUP[$i]->c_linky . " | " . $POMPRESTUP[$i]->do_zastavky . "<br>";
}*/

//echo round(microtime(true) * 1000) - $sMil . "</br>";

getSpojeni($pocatek, $cil, $H * 60 + $M);

/* for ($i = 0; $i < count($myarraylist); $i++) {
  echo "</br></br>";
  echo $myarraylist[$i]->cislo_spojeni . "</br>";
  for ($ii = 0; $ii < count($myarraylist[$i]->PartSpoje); $ii++) {
  $Part = $myarraylist[$i]->PartSpoje[$ii];
  echo iconv('windows-1250', 'UTF-8', $Part->nazev_linky) . "</br>";
  echo $Part->text_odjezd . " " . iconv('UTF-8', 'UTF-8', getZASTAVKA($Part->od_zastavky->id_zastavky)->nazev_zastavky) . "(" . $Part->od_zastavky->id_zastavky . ")</br>";
  echo $Part->text_prijezd . " " . iconv('UTF-8', 'UTF-8', getZASTAVKA($Part->do_zastavky->id_zastavky)->nazev_zastavky) . "(" . $Part->do_zastavky->id_zastavky . ")</br>";
  }
  } */



/* $rs_linka = "Linka";
  $rs_zezastavky = "Ze zastávky";
  $rs_odjezd = "Odjezd";
  $rs_dozastavky = "Do zastávky";
  $rs_prijezd = "Pøíjezd";
  $rs_spojeninenalezeno = "Vhodné spojení nebylo nalezeno "; */

$res = '';

$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
$res = $res . "<div id='movedivSeznam' class='movediv'>";
$res = $res . "<a>" . $pocet . "</a><a>|" . $pocetprestupu . "</a><a>|" . $varGRF . "</a>";
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div>";
$res = $res . "<table id='tablejrSeznam' class = 'tablejr' >"; //style='max-width:700px; width: auto;'
//$res = $res . "<tr>";
//$res = $res . "<td>";

if (($prime == 1) && ($location == 6)) {
  $pommyarraylist = null;
  $ii = 0;
  for ($i = 0; $i < count($myarraylist); $i++) {
    if (count($myarraylist[$i]->PartSpoje) == 1) {
      $pommyarraylist[$ii] = $myarraylist[$i];
      $ii++;
    }
  }
  $myarraylist = $pommyarraylist;
}

if (count($myarraylist) > 0) {
  for ($i = 0; $i < count($myarraylist); $i++) {
    if (1 == 1) {
//      $res = $res . "<table class = 'tablejr' style='width: 100%;'>";
      $res = $res . "<tr><td colspan='11'><div style='margin-top: 20px; border-bottom: 1px solid #dadada;'></div></td></tr>"; //new
      $res = $res . "<tr>";

      $res = $res . "<th colspan='2' style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_linka);
      $res = $res . "<th style='text-align: left;'>";
      $res = $res . "<th style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_zezastavky);
      $res = $res . "<th style='text-align: right;'>" . iconv('windows-1250', 'UTF-8', $rs_odjezd);
      $res = $res . "<th style='text-align: left;'>";
      $res = $res . "<th style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', $rs_dozastavky);
      $res = $res . "<th style='text-align: right;'>" . iconv('windows-1250', 'UTF-8', $rs_prijezd);

      $res = $res . "</tr>"; //new
      $res = $res . "<tr><td colspan='11'><div style='padding-top: 20px; border-top: 1px solid #dadada;'></div></td></tr>"; //new
      $res = $res . "<tr>"; //new
//      $res = $res . "</tr><tr>";

      $prijezd = 0;
      $odjezd = 0;
      for ($ii = 0; $ii < count($myarraylist[$i]->PartSpoje); $ii++) {
        $val1 = $myarraylist[$i]->PartSpoje[$ii];
        if ($ii == 0) {
          $pocatek = $val1->odjezd;
          $ppocatek = $val1->text_odjezd;
        }
        $konec = $val1->prijezd;

        $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
        $res = $res . "<a class = 'popisek' style='font-size: 18px;'>";
        $res = $res . iconv('windows-1250', 'UTF-8', $val1->nazev_linky);
        $res = $res . "</a>";
        $res = $res . "</td>";

        $doprava = "";
        if ($val1->typ_dopravy == 'T') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png' style='vertical-align: top !important;'></img>";
        }
        if ($val1->typ_dopravy == 'O') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png' style='vertical-align: top !important;'></img>";
        }
        if ($val1->typ_dopravy == 'A') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png' style='vertical-align: top !important;''></img>";
        }
        if ($val1->typ_dopravy == 'P') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/peso_small.png' style='vertical-align: top !important;''></img>";
        }
        $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
        $res = $res . $doprava;

        $res = $res . "</td>";

        $ZASTAVKY[$val1->od_zastavky->id_zastavky] = (object) $ZASTAVKY[$val1->od_zastavky->id_zastavky];
        $zast_show->nazev_zastavky = $ZASTAVKY[$val1->od_zastavky->id_zastavky]->nazev_zastavky;//getZASTAVKA($val1->od_zastavky->id_zastavky, $val1->c_linky, $val1->tarif_od);
        $res = $res . "<td  style='padding: 5px 15px 5px 5px; white-space: normal;'>";
        $res = $res . "<img class='imgmap' src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, " . (($zast_show->LOCA == 0) ? 'null' : $zast_show->LOCA) . ", " . (($zast_show->LOCB == 0) ? 'null' : $zast_show->LOCB) . ", " . (integer) $zast_show->id_zastavky . ");'>&nbsp;</img>";
        $res = $res . "</td>";

        $res = $res . "<td class='sp_zastavka'  style='width: auto; padding: 5px 15px 5px 5px; white-space: normal;'>";
        $res = $res . iconv('UTF-8', 'UTF-8', $zast_show->nazev_zastavky . (($location == 6) ? (($val1->smer == 0) ? (($zast_show->AT != '') ? ' ( ' . $zast_show->AT . ' ) ' : '') : (($zast_show->BT != '') ? ' ( ' . $zast_show->BT . ' ) ' : '')) : ''));
        $res = $res . "</td>";

        if ($ii == 0) {
          $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: right;'>";
        } else {
          $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; text-align: right;;'>";
        }
        $res = $res . $val1->text_odjezd/* . " (" . $val1->c_spoje . "," . $val1->smer . ")" */;
        $res = $res . "</td>";

        $ZASTAVKY[$val1->do_zastavky->id_zastavky] = (object) $ZASTAVKY[$val1->do_zastavky->id_zastavky];
        $zast_show/*->nazev_zastavky*/ = /*$ZASTAVKY[$val1->do_zastavky->id_zastavky]->nazev_zastavky;//*/getZASTAVKA($val1->do_zastavky->id_zastavky, $val1->c_linky, $val1->tarif_do);
        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
        $res = $res . "<img class='imgmap' src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, " . (($zast_show->LOCA == 0) ? 'null' : $zast_show->LOCA) . ", " . (($zast_show->LOCB == 0) ? 'null' : $zast_show->LOCB) . ", " . (integer) $zast_show->id_zastavky . ");'>&nbsp;</img>";
        $res = $res . "</td>";

        $res = $res . "<td class='sp_zastavka' style='width: auto; padding: 5px 15px 5px 5px; white-space: normal;'>";
        $res = $res . iconv('UTF-8', 'UTF-8', $zast_show->nazev_zastavky . (($location == 6) ? (($val1->smer == 0) ? (($zast_show->AT != '') ? ' ( ' . $zast_show->AT . ' ) ' : '') : (($zast_show->BT != '') ? ' ( ' . $zast_show->BT . ' ) ' : '')) : ''));
        $res = $res . "</td>";

        if ($ii == count($myarraylist[$i]->PartSpoje) - 1) {
          $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: right;'>";
        } else {
          $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; text-align: right;'>";
        }
        $res = $res . $val1->text_prijezd;
        $res = $res . "</td>";

        $res = $res . "</tr>";
      }
    }

    if ($location == 6) {
      $res = $res . "<tr><td colspan='11' style='text-align: left;'>" . iconv('windows-1250', 'UTF-8', 'Dåžka&nbsp;cesty&nbsp;') . ($konec - $pocatek) . iconv('windows-1250', 'UTF-8', '&nbsp;min.') . "</td></tr>";
    }

//    $res = $res . "</table>";
//    $res = $res . "<div style='margin-top: 20px;'></div>";
  }
} else {
  $res = $res . "<tr>";
  $res = $res . "<td><a>";

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

  if (($location == 6) || ($location == 14)) {
    $pocatek = (((intval($_GET['h']) + 2) % 24) * 60) + intval($_GET['m'] - 1);
    $res = $res . iconv('windows-1250', 'UTF-8', 'Od zadaného èasu (') . (($_GET['h'] < 10) ? '0' . $_GET['h'] : $_GET['h']) . ":" . (($_GET['m'] < 10) ? '0' . $_GET['m'] : $_GET['m']) . iconv('windows-1250', 'UTF-8', ') sa v najbližších 2 hodinách spojenie nenašlo.');
  } else {
    $res = $res . iconv('windows-1250', 'UTF-8', $rs_spojeninenalezeno) . $day . '.' . $month . '.' . $year . ' -- ' . $_GET['h'] . ":" . $_GET['m'];
  }
  $res = $res . "</a></td>";
  $res = $res . "</tr>";
//  $res = $res . "</table>";
}
//$res = $res . "</td>";
//$res = $res . "</tr>";
$res = $res . "</table>";

/* if ($location == 6) {
  $res = $res . "<div><center>". iconv('windows-1250', 'UTF-8', 'Následujuce') . "</center></div>";
  } */

$res = $res . "</div>";

if ($location == 6) {
  $pocatek = $pocatek + 1;
  $res = $res . /* $ppocatek . " - ". intval($pocatek / 60) . ":" . ($pocatek % 60) . */"</br><div>
<center>
<button title='" . iconv('windows-1250', 'UTF-8', 'Následujuce spojenia') . "' onclick='Time.initialize(" . intval($pocatek / 60) . ", " . intval($pocatek % 60) . "); JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM(), " . $prime . ");' id='mod-rscontact-submit-btn-124' class='formButton'><span class=''></span>" . iconv('windows-1250', 'UTF-8', 'Následujuce') . "</button>
</center>
</div>";
}

echo "</br><br>";
echo (round(microtime(true) * 1000) - $mMil) . "</br>";
echo $_GET['callback'] . "(" . json_encode($res) . ");";

//echo $_GET['callback'] . "(" . json_encode($myarraylist) . ");";
//echo $res;
?>