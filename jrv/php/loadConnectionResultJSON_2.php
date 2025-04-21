<?php

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
  $denvtydnu = date('w', $mk);
} else {
  $datumJR = date('Y-m-d');
  $denvtydnu = date('w');
}

$day = date_format(new DateTime($datumJR), 'd');
$month = date_format(new DateTime($datumJR), 'm');
$year = date_format(new DateTime($datumJR), 'Y');

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

}

class TSpojItem {

  var $MM = null;
  var $chrono = null;
  var $c_spoje = null;

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

  var $c_linky;
  var $od_tarif;
  var $do_zastavky;
  var $do_tarif;

}

$CHRONO = null; //[c_linky][smer][chrono][c_tarif]
$SPOJE = null; //[c_linky][smer][HH]
$HRANY = null; //[do_zastavky]
$ZASTAVKY_LINKY = null; //[c_zastavky][c_linky][c_tarif]TZastavkaItem
$LINKY_ZASTAVKY = null; //[c_linky][c_zastavky][c_tarif]TZastavkaItem
$PRESTUPY = null;
$PRESTUPY_CACHE = null;
$LINKY = null;
$ZASTAVKY = null;

$SpojeniBody = null;
$myarraylist = null;
$pocet = 0;
$pocetprestupu = 0;

$typyGrfDay = array(
    "0" => array('7', '+'),
    "1" => array('X', '1'),
    "2" => array('X', '2'),
    "3" => array('X', '3'),
    "4" => array('X', '4'),
    "5" => array('X', '5'),
    "6" => array('6'));

echo $denvtydnu . "</br></br>";

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
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");

    $sql = "select linky.c_linky, linky.nazev_linky, linky.doprava from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = \"" . $c_linky . "\"";
//    echo $sql . "</br>";
    $result = $mysqli->query($sql);

    $row = $result->fetch_row();
    $res = new TLinka();
    $res->c_linky = $row[0];
    $res->nazev_linky = $row[1];
    $res->doprava = $row[2];

    $LINKY[$c_linky] = $res;
  } else {
    $res = $LINKY[$c_linky];
  }

  return $res;
}

function getZASTAVKA($c_zastavky) {
  global $ZASTAVKY;
  global $location;
  global $packet;

  $res = null;

  if ($ZASTAVKY[$c_zastavky] == null) {
    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");

    $sql = "select zastavky.c_zastavky, zastavky.nazev from zastavky
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zastavky.c_zastavky = " . $c_zastavky;
    $result = $mysqli->query($sql);

    $row = $result->fetch_row();
    $res = new TZastavka();
    $res->id_zastavky = $row[0];
    $res->nazev_zastavky = $row[1];

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
  global $pocet;

//  $pocet = 0;

  if ($PRESTUPY_CACHE[$od_zastavky] != null) {
    $PRESTUPY = $PRESTUPY_CACHE[$od_zastavky];
  } else {
    $PRESTUPY = null;

    $_ZASTAVKY_LINKY = $ZASTAVKY_LINKY[$od_zastavky];
    foreach ($_ZASTAVKY_LINKY as $key_c_linky => $val) {
      foreach ($val as $key_c_tarif => $val1) {
        $zastavka = $val1;
//      echo $zastavka->c_linky . ", " . $od_zastavky . ", " . $zastavka->c_tarif . "</br>";
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
              if ((($smer == 0) && ($zastavka->A == 1) && ($zastavka1->A == 1)) || (($smer == 1) && ($zastavka->B == 1) && ($zastavka1->B == 1))) {
                if ($PRESTUPY[$zastavka->c_linky . "_" . $zastavka->c_tarif . "_" . $l_key_c_zastavky . "_" . $zastavka1->c_tarif] == null) {
                  $prestup = new TPrestup();
                  $prestup->c_linky = $zastavka->c_linky;
                  $prestup->od_tarif = $zastavka->c_tarif;
                  $prestup->do_zastavky = $l_key_c_zastavky;
                  $prestup->do_tarif = $zastavka1->c_tarif;
                  $PRESTUPY[$zastavka->c_linky . "_" . $zastavka->c_tarif . "_" . $l_key_c_zastavky . "_" . $zastavka1->c_tarif] = $prestup;
//                $pocet++;
                }
                //echo $zastavka->c_linky . ", " . $od_zastavky . ", " . $zastavka->c_tarif . ", " . $l_key_c_zastavky . ", " . $zastavka1->c_tarif . "</br>";
              }
            }
          }
        }
      }
    }

    $PRESTUPY_CACHE[$od_zastavky] = $PRESTUPY;
    $pocet++;
  }
//  echo "pocet prestupu = " . $pocet . "</br>";
}

function getSpojeODDO($odZastavky, $pocatek, $cil, $cas) {
  global $SPOJE;
  global $CHRONO;
  global $HRANY;
  global $PRESTUPY;
  global $minprestup;
  global $maxprestup;
  global $maxcas;
  global $location;
  global $packet;
  //$pocet1 = 0;

  $HRANY = null;
  getPrujezdy($odZastavky, $cil);
  /*  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");

    $sql = "select z1.c_linky as c_linky, z1.c_tarif as od_tarif, z2.c_zastavky as do_zastavky, z2.c_tarif as do_tarif,
    z1.zast_a as z1A, z1.zast_b as z1B, z2.zast_a  as z2A, z2.zast_b  as z2B
    from zaslinky z1
    left outer join zaslinky z2
    on (z1.idlocation = z2.idlocation and z1.packet = z2.packet and z1.c_linky = z2.c_linky and (z2.prestup = 1 or z2.c_zastavky = " . $cil . "))
    where z1.idlocation = " . $location . " and z1.packet = " . $packet . " and z1.c_zastavky = " . $odZastavky . "
    and not z1.c_zastavky=z2.c_zastavky";
    $pocet++;
    echo $sql . "</br>";
    $result = $mysqli->query($sql);

    while ($row = $result->fetch_row()) { */
  if (count($PRESTUPY) > 0) {
    foreach ($PRESTUPY as $key_prestup => $value) {
      $row[0] = $value->c_linky;
      $row[1] = $value->od_tarif;
      $row[2] = $value->do_zastavky;
      $row[3] = $value->do_tarif;

      $smer = $row[1] - $row[3]; //odtarif - dotarif
      if ($smer < 0) {
        $smer = 0;
      } else {
        $smer = 1;
      }

//    if ((($smer == 0) && ($row[4] == 1) && ($row[6] == 1)) || (($smer == 1) && ($row[5] == 1) && ($row[7] == 1))) {
//      $pocet1++;
      $odHH = (int) (($cas - 60) / 60);
      $doHH = (int) ((($cas + $maxcas) / 60));
      $c_linky = $row[0];
      $od_zastavky = $odZastavky;
      $do_zastavky = $row[2];
      $od_tarif = $row[1];
      $do_tarif = $row[3];
//      echo "od do > " . $odHH . " - " . $doHH . "</br>";
//      echo $c_linky . " | " . $od_zastavky . " -> " . $do_zastavky . " : " . "</br>";
      while (($odHH <= $doHH) /* && ($konec == false) */) {
        if ($SPOJE[$c_linky][$smer][$odHH] != null) {
          for ($i = 0; $i < count($SPOJE[$c_linky][$smer][$odHH]); $i++) {
            $pomSpojItem = $SPOJE[$c_linky][$smer][$odHH][$i];
//            echo " chrono : " . $pomSpojItem->chrono . "</br>";
            if (($CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif] != null) && ($CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif] != null)) {
              $startCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
              $endCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif];
              $doba_jizdy = $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif] - $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
//              echo $c_linky . " | " . $od_zastavky . " -> " . $do_zastavky . " : " . $startCAS . " -> " . $endCAS . " ( " . $doba_jizdy . " )</br>";
              /*            if ($od_zastavky == $pocatek) {
                $pommincekani = 0;
                } else {
                $pommincekani = $minprestup;
                } */
//              echo "cas + pommincekani = " . ($cas + $pommincekani) . " | " . "cas + maxprestup = " . ($cas + $maxprestup) . " \ " . $startCAS . "</br>";
              if ((($startCAS - $cas <= $maxprestup) && ($startCAS - $cas >= $minprestup)) || (($od_zastavky == $pocatek) && ($startCAS >= $cas))) {
                //if (($cas + $pommincekani <= $startCAS) && ($cas + $maxprestup >= $startCAS)) {
                if ($HRANY[$do_zastavky] == null) {
//                  echo " ----- 1 ----- </br>";
                  $pomSpoj = new TSpoj();
                  $pomSpoj->c_linky = $c_linky;
                  $pomSpoj->od_zastavky = $od_zastavky;
                  $pomSpoj->do_zastavky = $do_zastavky;
                  $pomSpoj->od_tarif = $od_tarif;
                  $pomSpoj->do_tarif = $do_tarif;
                  $pomSpoj->smer = $smer;
                  $pomSpoj->startCAS = $startCAS;
                  $pomSpoj->endCAS = $endCAS;
                  $pomSpoj->doba_jizdy = $doba_jizdy;
                  $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
                  $HRANY[$do_zastavky] = $pomSpoj;
                } else {
//                  echo " ----- 2 ----- </br>";
                  $pomSpoj = $HRANY[$do_zastavky];
                  if ($pomSpoj->endCAS > $endCAS) {
                    $pomSpoj->c_linky = $c_linky;
                    $pomSpoj->od_zastavky = $od_zastavky;
                    $pomSpoj->do_zastavky = $do_zastavky;
                    $pomSpoj->od_tarif = $od_tarif;
                    $pomSpoj->do_tarif = $do_tarif;
                    $pomSpoj->smer = $smer;
                    $pomSpoj->startCAS = $startCAS;
                    $pomSpoj->endCAS = $endCAS;
                    $pomSpoj->doba_jizdy = $doba_jizdy;
                    $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
                  }
                }
              }
            }
          }
        }
        $odHH++;
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
  $res = null;
  $min = PHP_INT_MAX;
  $pom = $SpojeniBody->BodyListActive;
  foreach ($pom as $key_c_zastavky => $val) {
    $pomZastavka = $val;
    if ($pomZastavka->vaha <= $min) {
      $min = $pomZastavka->vaha;
      $res = $pomZastavka;
    }
  }
  if ($res != null) {
    moveActiveToInActive($res->id_zastavky);
  }
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

  global $pocetprestupu;

  $cas = $odcas;
  $pocet_spojeni = 0;
  $konec_spojeni = 0;
  $cislo_spoje = 0;
  $obj = null; //TPartSpojeni
  $konec = false;

  while ($konec == false) {
    $start = new TZastavka(); // DataZastavky.getZastavkaById(od_zastavky);
    $start->id_zastavky = $od_zastavky;
    $start->prijezd = $cas;
    $start->vaha = 0;
    $SpojeniBody = new TSpojeniBody(); //TSpojeniBody SpojeniBody = new TSpojeniBody();
//    echo "velikosti = " . count($SpojeniBody->BodyListActive) . "," . count($SpojeniBody->BodyListInActive) . "</br>";
    addActive($start);

    $bod = getSpojeniMinimum(/* SpojeniBody */);  //TZastavka
    while (($bod != null) && ($bod->id_zastavky != $do_zastavky)) {
//                Hashtable</*TZastavka*/String, THrana> HranyList = getSpojeOdDo(String.valueOf(bod.id_zastavky), od_zastavky, do_zastavky, Day, bod.prijezd, SpojeniBody, CalendCode);

      /*      $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
        $mysqli->query("SET NAMES 'utf-8';");
        $sql = "call getSpojeOdDo(" . $location . " ," . $packet . ", " . $bod->id_zastavky . ", " . $do_zastavky . ", " . $bod->prijezd . ", " . (($od_zastavky == $bod->id_zastavky) ? 0 : $minprestup) . ", " . $maxprestup . ", " . $varGRF . ");";
        //      echo $sql . "</br>";
        $result = $mysqli->query($sql); */
      getSpojeODDO($bod->id_zastavky, $od_zastavky, $do_zastavky, $bod->prijezd);
//      echo "</br></br>" . count($HRANY) . "</br></br>";

      if (count($HRANY) > 0) {
        foreach ($HRANY as $key_c_zastavky => $val) {
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

          if (getInActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null) {
            if ((getActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null) && (getInActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null)) {
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
          }
        }
      }
//                }

      $bod = getSpojeniMinimum();
    }

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

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");

$sql = "select c_linky, smer, HH, MM, chrono, c_spoje from spoje where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and ((spoje.kodpozn & " . $varGRF . ") > 0 or spoje.kodpozn = 0)  AND spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048 order by smer, hh, mm";
$result = $mysqli->query($sql);

while ($row = $result->fetch_row()) {
  $SpojItem = new TSpojItem();
  $SpojItem->MM = $row[3];
  $SpojItem->chrono = $row[4];
  $SpojItem->c_spoje = $row[5];
  $SPOJE[$row[0]][$row[1]][$row[2]][count($SPOJE[$row[0]][$row[1]][$row[2]])] = $SpojItem;
//  echo $row[0] . "(" . $row[1] . ")" .  " - " . $row[2] . " | " . $row[3] . " - " . $row[18] . ":" . $row[19] . "</br>";
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
    //[c_zastavky][c_linky][c_tarif][]TZastavkaItem
    $ZASTAVKY_LINKY[$row[5]][$row[0]][$row[3]] = $pomZastavka;
    //[c_linky][c_zastavky][c_tarif]TZastavkaItem
    $LINKY_ZASTAVKY[$row[0]][$row[5]][$row[3]] = $pomZastavka;
  }
//  echo $row[0] . " , " . $row[1] . " , " . $row[2] . " , " . $row[3] . " , " . $row[4] . "</br>";
}

//echo "count = " . count($ZASTAVKY_LINKY) . "</br>";

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



$rs_linka = "Linka";
$rs_zezastavky = "Ze zastávky";
$rs_odjezd = "Odjezd";
$rs_dozastavky = "Do zastávky";
$rs_prijezd = "Pøíjezd";
$rs_spojeninenalezeno = "Vhodné spojení nebylo nalezeno ";

$res = '';

$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
$res = $res . "<div id='movedivSeznam' class='movediv'>";
$res = $res . "<a>" . $pocet . "</a><a>|" . $pocetprestupu . "</a><a>|" . $varGRF . "</a>";
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div>";
$res = $res . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:700px; width: auto;'>";
$res = $res . "<tr>";
$res = $res . "<td>";

if (count($myarraylist) > 0) {
  for ($i = 0; $i < count($myarraylist); $i++) {
    if (1 == 1) {
      $res = $res . "<table class = 'tablejr' style='width: 100%;'>";
      $res = $res . "<tr>";

      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_linka);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_zezastavky);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_odjezd);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_dozastavky);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_prijezd);
      $res = $res . "</tr><tr>";

      for ($ii = 0; $ii < count($myarraylist[$i]->PartSpoje); $ii++) {
        $val1 = $myarraylist[$i]->PartSpoje[$ii];

        $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
        $res = $res . "<a class = 'a_nazev_linky1' style='font-size: 18px;'>";
        $res = $res . iconv('windows-1250', 'UTF-8', $val1->nazev_linky);
        $res = $res . "</a>";
        $doprava = "";
        if ($val1->typ_dopravy == 'T') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>";
        }
        if ($val1->typ_dopravy == 'O') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>";
        }
        if ($val1->typ_dopravy == 'A') {
          $doprava = "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>";
        }
        $res = $res . $doprava;

        $res = $res . "</td>";

        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";

        $res = $res . iconv('UTF-8', 'UTF-8', getZASTAVKA($val1->od_zastavky->id_zastavky)->nazev_zastavky);
        $res = $res . "</td>";

        if ($ii == 0) {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
        } else {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
        }
        $res = $res . $val1->text_odjezd/* . " (" . $val1->c_spoje . "," . $val1->smer . ")"*/;
        $res = $res . "</td>";

        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";

        $res = $res . iconv('UTF-8', 'UTF-8', getZASTAVKA($val1->do_zastavky->id_zastavky)->nazev_zastavky);
        $res = $res . "</td>";

        if ($ii == count($myarraylist[$i]->PartSpoje) - 1) {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
        } else {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
        }
        $res = $res . $val1->text_prijezd;
        $res = $res . "</td>";

        $res = $res . "</tr>";
      }
    }

    $res = $res . "</table>";
    $res = $res . "<div style='margin-top: 20px;'></div>";
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

  $res = $res . iconv('windows-1250', 'UTF-8', $rs_spojeninenalezeno) . $day . '.' . $month . '.' . $year . ' -- ' . $_GET['h'] . ":" . $_GET['m'];
  $res = $res . "</a></td>";
  $res = $res . "</tr>";
  $res = $res . "</table>";
}
$res = $res . "</td>";
$res = $res . "</tr>";
$res = $res . "</table>";
$res = $res . "</div>";

echo $_GET['callback'] . "(" . json_encode($res) . ");";

//echo $_GET['callback'] . "(" . json_encode($myarraylist) . ");";
//echo $res;
?>