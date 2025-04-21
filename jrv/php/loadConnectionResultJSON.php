<?php

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

class TZastavka {

  var $c_zastavky = null;
  var $c_tarif = null;
  var $nazev = null;
  var $in_hrana = null;
  var $out_hrana = null;
  var $vaha = PHP_INT_MAX;
  var $cekani = PHP_INT_MAX;
  var $prijezd = null;
  var $nacten = false;
  var $nacten_odC = null;
  var $odebran = false;

}

class THrana {

  var $c_linky = null;
  var $nazev_linky = null;
  var $doprava = null;
  var $odjezd = null;
  var $od_zastavky = null;
  var $do_zastavky = null;
  var $doba_jizdy = null;
  var $smer = null;

}

class TSpojPart {

  var $odjezd = null;
  var $prijezd = null;
  var $c_linky = null;
  var $nazev_linky = null;
  var $doprava = null;
  var $od_zastavky = null;
  var $do_zastavky = null;
  var $od_nazev = null;
  var $do_nazev = null;

}

class TSpoj {

  var $cislo_spoje = null;
  var $odjezd = null;
  var $prijezd = null;
  var $platny = true;
  var $PART = null;

}

$UZLY = null;
$HRANY = null;
$SPOJE = null;
$minUZEL = null;

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqliVARGRF->query("SET NAMES 'utf-8';");

$pocetresult = 0;
//echo "time : " . time();
//echo "</br>";
$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
//echo $sql . "</br>";
$result = $mysqli->query($sql);

$varGRF = 0;
$maxcas = 120;
$maxnacteni = 240;
$minprestup = 0;
$maxprestup = 60;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

/*echo $varGRF;
  echo "</br>";
  echo "</br>";
  echo "time : " . time();
  echo "</br>"; */

function vypis_uzlu() {
  global $UZLY;

  foreach ($UZLY as $key_c_zastavky => $val) {
    foreach ($val as $key_c_linky => $val1) {
      foreach ($val1 as $key_c_tarif => $val2) {
        echo $key_c_zastavky . "," . $key_c_linky . "," . $key_c_tarif . " | " . $val2->nazev . "," . $val2->c_tarif . "," . $val2->nacten . "|" . $val2->in_hrana->od_zastavky->nazev . "|" . $val2->odebran . " | " . $val2->vaha . "</br>";
      }
    }
  }
}

function nastav_nacteni_uzlu($c_zastavky) {
  global $UZLY;

  $UZLY_c_zastavky = $UZLY[$c_zastavky];
  foreach ($UZLY_c_zastavky as $key_c_linky => $val) {
    foreach ($val as $key_c_tarif => $val1) {
      $val1->nacten = true;
    }
  }
}

function getMinUzel($odUZEL, $cil) {
  global $UZLY;
  $min = PHP_INT_MAX;
  $ret = null;
  $mincil = PHP_INT_MAX;
  $retcil = null;

  $UZLY1 = $odUZEL;
//  echo "HLEDAM CIL od " . $odUZEL->c_zastavky . ' ( ' . count($UZLY) . ' ) ' . '</br>';

  /*    foreach ($UZLY[$odUZEL->c_zastavky] as $key_c_linky => $val1) {
    foreach ($val1 as $key_c_tarif => $val2) {
    if ($val2->odebran == false) {
    echo '<> ' . $val2->c_zastavky . '</br>';
    if ($val2->vaha < $min) {
    $min = $val2->vaha;
    $ret = $val2;
    }
    if (($val2->vaha < $mincil) && ($val2->c_zastavky == $cil)) {
    $mincil = $val2->vaha;
    $retcil = $val2;
    echo "CIL";
    }
    }
    }
    } */
  /*   foreach ($UZLY as $key_c_zastavky => $val) {
    foreach ($val as $key_c_linky => $val1) {
    foreach ($val1 as $key_c_tarif => $val2) {
    if (($val2->odebran == false) ) {
    //          echo $val2->in_hrana->od_zastavky->c_zastavky . '->' . $val2->c_zastavky . '(' . $val2->vaha . ')' . '</br>';
    if ($val2->in_hrana->od_zastavky->c_zastavky == $odUZEL->c_zastavky) {
    if ($val2->vaha < $min) {
    $min = $val2->vaha;
    $ret = $val2;
    }
    if (($val2->vaha < $mincil) && ($val2->c_zastavky == $cil)) {
    $mincil = $val2->vaha;
    $retcil = $val2;
    //              echo "CIL";
    }
    }
    }
    }
    }
    }
    if ($retcil != null) {
    $ret = $retcil;
    } */

  if ($ret == null) {
    foreach ($UZLY as $key_c_zastavky => $val) {
      foreach ($val as $key_c_linky => &$val1) {
        foreach ($val1 as $key_c_tarif => &$val2) {
          if (($val2->odebran == false)) {
            if ($val2->vaha < $min) {
              $min = $val2->vaha;
              $ret = &$val2;
            }
            if (($val2->vaha < $mincil) && ($val2->c_zastavky == $cil)) {
              $mincil = $val2->vaha;
              $retcil = &$val2;
//              echo "CIL";
            }
          }
        }
      }
    }
  }
  if ($retcil != null) {
    $ret = &$retcil;
  }

  return $ret;
}

function vypis_hran($od_Z) {
  global $HRANY;

  $HRANY1 = &$HRANY[$od_Z];
  foreach ($HRANY1 as $key_doZ => &$val) {
    foreach ($val as $key_clinky => &$val1) {
      foreach ($val1 as $key_poradi => &$val2) {
        echo "hrany > " . $val2->c_linky . " | " . intval($val2->odjezd / 60) . ":" . intval($val2->odjezd % 60) . " | " . $val2->od_zastavky->nazev . " ( " . intval($val2->od_zastavky->prijezd / 60) . ":" . intval($val2->od_zastavky->prijezd % 60) . " )" . " -> " . $val2->do_zastavky->nazev . " ( " . intval($val2->do_zastavky->prijezd / 60) . ":" . intval($val2->do_zastavky->prijezd % 60) . " )</br>";
      }
    }
  }
}

//nacti hrany od zastavky
function getHrany(&$odZastavky, $odCAS, $start_c_zastavky) {
  global $location;
  global $packet;
  global $varGRF;
  global $UZLY;
  global $HRANY;
  global $maxnacteni;
  global $cil;

//  echo "spoje od casu : " . intval($odCAS / 60) . ":" . intval($odCAS % 60) . " -- " . $odZastavky->nazev . "</br>";
  if ($odZastavky->nacten == false) {
    if ($odZastavky->in_hrana == null) {
      $without = -1;
    } else {
      $without = $odZastavky->in_hrana->od_zastavky->c_zastavky;
    }

    $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
    $mysqli->query("SET NAMES 'utf-8';");
    $sql = "call getOdjezdyZastavkaTEST3(" . $location . " ," . $packet . ", " . $odCAS . ", " . ($odCAS + $maxnacteni) . ", " . $odZastavky->c_zastavky . ", -1, " . $start_c_zastavky . ", " . $cil . ", " . $without . ", " . $varGRF . ");";
//    echo $sql . "</br>";
    $odZastavky->nacten_odC = $odCAS;

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_row()) {
      $newHrana = new THrana();
      $newHrana->c_linky = $row[0];
      $newHrana->nazev_linky = $row[12];
      $newHrana->doprava = $row[13];
//      $newHrana->od_zastavky = $row[1];
//      $newHrana->do_zastavky = $row[2];
      $newHrana->smer = $row[5];
      $newHrana->odjezd = $row[6];
      $newHrana->doba_jizdy = $row[7];

      $HRANY[$row[1]][$row[2]][$row[0]][count($HRANY[$row[1]][$row[2]][$row[0]])] = $newHrana;

      if ($UZLY[$row[2]][$row[0]][$row[4]] == null) {
        $newZastavka = new TZastavka();
        $newZastavka->c_zastavky = $row[2];
        $newZastavka->c_tarif = $row[4];
        $newZastavka->nazev = $row[11];
        $odZastavky->nazev = $row[10];
        $UZLY[$row[2]][$row[0]][$row[4]] = $newZastavka;
      } else {
        $newZastavka = $UZLY[$row[2]][$row[0]][$row[4]];
      }

      $newHrana->od_zastavky = $odZastavky;
      $newHrana->do_zastavky = $newZastavka;

      if ($odZastavky->prijezd <= $newHrana->odjezd) {
        $cekani = $newHrana->odjezd - $odZastavky->prijezd;
        $celkemVaha = $odZastavky->vaha + ($cekani * 1) + ($newHrana->doba_jizdy * 1);
        if (($newZastavka->vaha > $celkemVaha) /* && (($cekani <= 20) || ($odZastavky->c_zastavky == $start_c_zastavky)) /*&& (($cekani >= 0) || ($odZastavky->in_hrana->c_linky == $newHrana->c_linky)) */) {
          if ($odZastavky->c_zastavky == $start_c_zastavky) {
            $newZastavka->cekani = -1;
          } else {
            $newZastavka->cekani = $cekani;
          }
          $newZastavka->vaha = $celkemVaha;
          $newZastavka->in_hrana = $newHrana;
          $odZastavky->out_hrana = $newHrana;
          $newZastavka->prijezd = $newHrana->odjezd + $newHrana->doba_jizdy;
        }
      }
    }
//    echo "vypisuji hrany : " . "</br>";
//    vypis_hran($odZastavky->c_zastavky);
    nastav_nacteni_uzlu($odZastavky->c_zastavky);
  } else {
    if ($odZastavky->in_hrana == null) {
      $without = -1;
    } else {
      $without = $odZastavky->in_hrana->od_zastavky->c_zastavky;
//      echo "without = " . $odZastavky->in_hrana->od_zastavky->nazev . "</br>";
    }
//    echo "(hrany od zastavky )" . $odZastavky->c_zastavky . "(" . $odZastavky->c_tarif . ")" . " (" . $odZastavky->nazev . ") " . intval($odZastavky->prijezd / 60) . ":" . intval($odZastavky->prijezd % 60) . "</br>";
    $HRANY1 = &$HRANY[$odZastavky->c_zastavky];
    if ($HRANY1 != null) {
      $existodjezd = false;
      $lastodjezd = null;
      $UZLY1 = $UZLY[$odZastavky->c_zastavky];
//      foreach ($UZLY1 as $key_c_zastavky => $val) {
      /*        foreach ($UZLY1 as $key_c_linky => $val1) {
        foreach ($val1 as $key_c_tarif => $val2) {
        echo "(zastavky )" . $val2->c_zastavky . "(" . $val2->c_tarif . ")" . " (" . $val2->nazev . ") " . intval($val2->prijezd / 60) . ":" . intval($val2->prijezd % 60) . "</br>";
        }
        } */
//      }
      foreach ($HRANY1 as $key_doZ => $val) {
        foreach ($val as $key_clinky => $val1) {
          foreach ($val1 as $key_poradi => $val2) {
//            echo " .... " . $val2->c_linky . " | " . $val2->od_zastavky->nazev . " | " . intval($odZastavky->prijezd / 60) . ":" . intval($odZastavky->prijezd % 60) . " -- " . intval($val2->odjezd / 60) . ":" . intval($val2->odjezd % 60) . " | " . $val2->do_zastavky->nazev . " > vaha : " . $celkemVaha  .  "</br>";
//            echo "vyraz = " . (($val2->od_zastavky == $odZastavky) && ($val2->do_zastavky->c_zastavky != $start_c_zastavky)) . "</br>";
            if (($val2->od_zastavky->c_zastavky == $odZastavky->c_zastavky) /* && ($val2->od_zastavky->c_tarif == $odZastavky->c_tarif) */ /* && ($val2->od_zastavky->prijezd == $odZastavky->prijezd) */ && ($val2->do_zastavky->c_zastavky != $without) && ($val2->do_zastavky->c_zastavky != $start_c_zastavky)) {
//            echo intval($val2->odjezd / 60) . ":" . ($val2->odjezd % 60) . " -- " . $val2->od_zastavky->c_zastavky . " -> " . $val2->c_linky . " -> " . $val2->do_zastavky->c_zastavky . "</br>";
              $newHrana = $val2;
              $newZastavka = $val2->do_zastavky;
              $lastodjezd = $newHrana->odjezd;
              if ($odZastavky->prijezd <= $newHrana->odjezd) {
                $existodjezd = true;
                $cekani = $newHrana->odjezd - $odZastavky->prijezd;
                $celkemVaha = $odZastavky->vaha + ($cekani * 1) + ($newHrana->doba_jizdy * 1);
                if (($newZastavka->vaha > $celkemVaha) /* && (($cekani <= 20) || ($odZastavky->c_zastavky == $start_c_zastavky)) && (($cekani >= 0) || ($odZastavky->in_hrana->c_linky == $newHrana->c_linky)) */) {
//                  echo " .... " . $val2->c_linky . " | " . $val2->od_zastavky->nazev . " | " . intval($odZastavky->prijezd / 60) . ":" . intval($odZastavky->prijezd % 60) . " -- " . intval($val2->odjezd / 60) . ":" . intval($val2->odjezd % 60) . " | " . $val2->do_zastavky->nazev . " > vaha : " . $newZastavka->vaha . "|" . $celkemVaha . "</br>";
                  if ($odZastavky->c_zastavky == $start_c_zastavky) {
                    $newZastavka->cekani = -1;
                  } else {
                    $newZastavka->cekani = $cekani;
                  }
                  $newZastavka->vaha = $celkemVaha;
                  $newZastavka->in_hrana = $newHrana;
                  $odZastavky->out_hrana = $newHrana;
                  $newHrana->od_zastavky = $odZastavky;
//                  echo "nastavuji " . $val2->od_zastavky->c_zastavky . "(" . $val2->od_zastavky->c_tarif . ")" . " (" . $val2->od_zastavky->nazev . ") " . intval($val2->od_zastavky->prijezd / 60) . ":" . intval($val2->od_zastavky->prijezd % 60) . "</br>";
                  $newZastavka->prijezd = $val2/* $newHrana */->odjezd + $val2/* $newHrana */->doba_jizdy;
                }
              }
            }
          }
        }

//-- dalsi useky

        if (($existodjezd == false) && ($lastodjezd != null)) {
          if ($odZastavky->nacten_odC < ($lastodjezd + 1)) {
            if ($odZastavky->in_hrana == null) {
              $without = -1;
            } else {
              $without = $odZastavky->in_hrana->od_zastavky->c_zastavky;
            }

            $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
            $mysqli->query("SET NAMES 'utf-8';");
            $sql = "call getOdjezdyZastavkaTEST3(" . $location . " ," . $packet . ", " . ($lastodjezd + 1) . ", " . ($lastodjezd + 1 + $maxnacteni) . ", " . $odZastavky->c_zastavky . ", -1, " . $start_c_zastavky . ", " . $cil . ", " . $without . ", " . $varGRF . ");";
//            echo $sql . "</br>";
            $odZastavky->nacten_odC = ($lastodjezd + 1);

            $result = $mysqli->query($sql);
            while ($row = $result->fetch_row()) {
              $newHrana = new THrana();
              $newHrana->c_linky = $row[0];
              $newHrana->nazev_linky = $row[12];
              $newHrana->doprava = $row[13];
              $newHrana->smer = $row[5];
              $newHrana->odjezd = $row[6];
              $newHrana->doba_jizdy = $row[7];

              $HRANY[$row[1]][$row[2]][$row[0]][count($HRANY[$row[1]][$row[2]][$row[0]])] = $newHrana;

              if ($UZLY[$row[2]][$row[0]][$row[4]] == null) {
                $newZastavka = new TZastavka();
                $newZastavka->c_zastavky = $row[2];
                $newZastavka->c_tarif = $row[4];
                $newZastavka->nazev = $row[11];
                $odZastavky->nazev = $row[10];
                $UZLY[$row[2]][$row[0]][$row[4]] = $newZastavka;
              } else {
                $newZastavka = $UZLY[$row[2]][$row[0]][$row[4]];
              }

              $newHrana->od_zastavky = $odZastavky;
              $newHrana->do_zastavky = $newZastavka;

              if ($odZastavky->prijezd <= $newHrana->odjezd) {
                $cekani = $newHrana->odjezd - $odZastavky->prijezd;
                $celkemVaha = $odZastavky->vaha + ($cekani * 1) + ($newHrana->doba_jizdy * 1);
                if (($newZastavka->vaha > $celkemVaha)) {
//                  if (($newZastavka->vaha > $celkemVaha) && (($cekani <= 20) || ($odZastavky->c_zastavky == $start_c_zastavky)) && (($cekani >= 0) || ($odZastavky->in_hrana->c_linky == $newHrana->c_linky))) {
                  if ($odZastavky->c_zastavky == $start_c_zastavky) {
                    $newZastavka->cekani = -1;
                  } else {
                    $newZastavka->cekani = $cekani;
                  }
                  $newZastavka->vaha = $celkemVaha;
                  $newZastavka->in_hrana = $newHrana;
                  $odZastavky->out_hrana = $newHrana;
                  $newZastavka->prijezd = $newHrana->odjezd + $newHrana->doba_jizdy;
                }
              }
            }
          }
        }

//----
      }
    }
  }
}

function vyhodnoceni_spojeni() {
  global $SPOJE;
  global $minprestup;
  global $maxprestup;

  for ($i = 0; $i < count($SPOJE); $i++) {
    for ($ii = count($SPOJE[$i]->PART) - 1; $ii >= 0; $ii--) {
      $val1 = $SPOJE[$i]->PART[$ii];
      $valpred = $SPOJE[$i]->PART[$ii + 1];
      if (($ii < count($SPOJE[$i]->PART) - 1) && (($val1->odjezd - $valpred->prijezd < $minprestup) || ($val1->odjezd - $valpred->prijezd > $maxprestup))) {
//        echo $i . "." . $ii . ".    -- " . ($val1->odjezd - $valpred->prijezd) . " , " . $minprestup . " , " . $maxprestup . "</br>";
        $SPOJE[$i]->platny = false;
      }
    }
  }
  for ($i = 0; $i < count($SPOJE); $i++) {
    $val1 = $SPOJE[$i];
    if ($val1->platny == true) {
//      echo $i . ".</br>";
      for ($ii = $i + 1; $ii < count($SPOJE); $ii++) {
        $nextsamespoj = $SPOJE[$ii];
//        echo "  --  " . $ii . ". platny >: " . $nextsamespoj->platny . " - " . $nextsamespoj->prijezd . "|" . $val1->prijezd . "</br>";
        if (($nextsamespoj->platny == true) && ($nextsamespoj->prijezd == $val1->prijezd)) {
          $val1->platny = false;
        }
      }
    }
  }
}

function nulujGraf($startUzel, $startCas) {
  global $UZLY;

  foreach ($UZLY as $key_c_zastavky => $val) {
    foreach ($val as $key_c_linky => $val1) {
      foreach ($val1 as $key_c_tarif => $val2) {
        $val2->odebran = false;
        $val2->in_hrana = null;
        $val2->out_hrana = null;
        $val2->vaha = PHP_INT_MAX;
        $val2->prijezd = null;
      }
    }
  }

  $startUzel->prijezd = $startCas;
  $startUzel->vaha = 0;
  $startUzel->odebran = true;
}

//create start uzel
$cislospojeni = 0;
$newZastavka = new TZastavka();
$newZastavka->c_zastavky = $pocatek;
$newZastavka->prijezd = $H * 60 + $M;
$newZastavka->vaha = 0;
$newZastavka->odebran = true;

$UZLY[$pocatek]["-1"]["-1"] = $newZastavka;
$start_UZEL = $newZastavka;
$startCas = $H * 60 + $M;
$minUZEL = $start_UZEL;
$minulyposlednispoj_odjezd = null;
$minulyposlednispoj_prijezd = null;
$konechledani = false;
while ((/* $startCas <= ($H * 60 + $M) + 160 */$konechledani == false) && ($minUZEL != null)) {
  $minUZEL = $start_UZEL;
//  echo 'start cas - ' . $startCas . '</br>';
  while (($minUZEL->c_zastavky != $cil) && ($minUZEL != null)) {
    $minUZEL->odebran = true;
    getHrany(&$minUZEL, $minUZEL->prijezd, $pocatek);
//    vypis_uzlu();
//    vypis_hran($minUZEL->c_zastavky);

    $minUZEL = &getMinUzel($minUZEL, $cil);
//    echo '</br>min</br>' . $minUZEL->in_hrana->od_zastavky->in_hrana->nazev . "|" . intval($minUZEL->in_hrana->od_zastavky->in_hrana->prijezd / 60) . ":" . intval($minUZEL->in_hrana->od_zastavky->in_hrana->prijezd % 60) . " - " . intval($minUZEL->in_hrana->od_zastavky->in_hrana->odjezd / 60) . ":" . intval($minUZEL->in_hrana->od_zastavky->in_hrana->odjezd % 60) . " -> " . $minUZEL->in_hrana->od_zastavky->nazev . "|" . intval($minUZEL->in_hrana->prijezd / 60) . ":" . intval($minUZEL->in_hrana->prijezd % 60) . " - " . intval($minUZEL->in_hrana->odjezd / 60) . ":" . intval($minUZEL->in_hrana->odjezd % 60) . " -> " . $minUZEL->c_zastavky . " | " . $minUZEL->vaha . "  | " . intval($minUZEL->prijezd / 60) . ":" . intval($minUZEL->prijezd % 60) . " | " . $minUZEL->nazev . "| smer = " . $minUZEL->in_hrana->smer . '</br></br>';
  }
  //echo '</br>ppp</br></br>';

  $startCas = PHP_INT_MAX;
  $linka = null;
  $minulyspoj = null;
  $vypisUZEL = $minUZEL;
  $poslednispoj_prijezd = null;
  $poslednispoj_prijezd = $vypisUZEL->prijezd;
  $poslednispoj_odjezd = null;
  $Spoj = new TSpoj();
  $SPOJE[$cislospojeni] = $Spoj;
  while ($vypisUZEL->in_hrana != null) {
    if ($startCas > $vypisUZEL->prijezd) {
      $startCas = $vypisUZEL->prijezd + 1;
    }
    if ($startCas > $vypisUZEL->in_hrana->odjezd) {
      $startCas = $vypisUZEL->in_hrana->odjezd + 1;
    }
    if ($linka != $vypisUZEL->in_hrana->c_linky) {
      $newSpoj = new TSpojPart();
      $newSpoj->c_linky = $vypisUZEL->in_hrana->c_linky;
      $newSpoj->nazev_linky = iconv("UTF8", "CP1250", $vypisUZEL->in_hrana->nazev_linky);
      $newSpoj->doprava = iconv("UTF8", "CP1250", $vypisUZEL->in_hrana->doprava);
      $newSpoj->odjezd = $vypisUZEL->in_hrana->odjezd;
      $newSpoj->prijezd = $vypisUZEL->prijezd;
      $poslednispoj_odjezd = $newSpoj->odjezd;
      /*      echo intval($newSpoj->odjezd / 60) . ":" . ($newSpoj->odjezd % 60) . " - " . intval($minulyspoj->odjezd / 60) . ":" . ($minulyspoj->odjezd % 60) . "</br>";
        if ($vypisUZEL->in_hrana->odjezd->prijezd - $vypisUZEL->odjezd < $minprestup) {
        $Spoj->platny = false;
        } */
      $newSpoj->od_zastavky = $vypisUZEL->in_hrana->od_zastavky->c_zastavky;
      $newSpoj->od_nazev = iconv("UTF8", "CP1250", $vypisUZEL->in_hrana->od_zastavky->nazev);
      $newSpoj->do_zastavky = ($vypisUZEL->in_hrana == null) ? $vypisUZEL->c_zastavky : $vypisUZEL->in_hrana->do_zastavky->c_zastavky;
      $newSpoj->do_nazev = iconv("UTF8", "CP1250", ($vypisUZEL->in_hrana == null) ? $vypisUZEL->nzev : $vypisUZEL->in_hrana->do_zastavky->nazev);
      //$SPOJE[$cislospojeni][count($SPOJE[$cislospojeni])] = $newSpoj;
      $Spoj->PART[count($Spoj->PART)] = $newSpoj;
      $minulyspoj = $newSpoj;
    } else {
      $newSpoj = $minulyspoj;
      $newSpoj->odjezd = $vypisUZEL->in_hrana->odjezd;
      $newSpoj->od_zastavky = $vypisUZEL->in_hrana->od_zastavky->c_zastavky;
      $newSpoj->od_nazev = iconv("UTF8", "CP1250", $vypisUZEL->in_hrana->od_zastavky->nazev);
    }
    $linka = $vypisUZEL->in_hrana->c_linky;
//    echo intval($vypisUZEL->in_hrana->odjezd / 60) . ":" . ($vypisUZEL->in_hrana->odjezd % 60) . " - " . (intval($vypisUZEL->prijezd / 60)) . ":" . ($vypisUZEL->prijezd % 60) . " -- " . (($vypisUZEL->in_hrana == null) ? $vypisUZEL->c_zastavky : $vypisUZEL->in_hrana->do_zastavky->c_zastavky . " , " . $vypisUZEL->in_hrana->c_linky . " , " . $vypisUZEL->in_hrana->od_zastavky->c_zastavky) . "</br>";
    $vypisUZEL = $vypisUZEL->in_hrana->od_zastavky;
  }
  $Spoj->cislo_spoje = $cislospojeni++;
  $Spoj->odjezd = $poslednispoj_odjezd;
  $Spoj->prijezd = $poslednispoj_prijezd;
//  $cislospojeni++;
//  echo "posledni spoj -- " . intval($poslednispoj_odjezd /60) . ":" . intval($poslednispoj_odjezd %60) . " -> " . intval($poslednispoj_prijezd /60) . ":" . intval($poslednispoj_prijezd %60) . "</br>";
//  echo "posledni minuly spoj -- " . intval($minulyposlednispoj_odjezd /60) . ":" . intval($minulyposlednispoj_odjezd %60) . " -> " . intval($minulyposlednispoj_prijezd /60) . ":" . intval($minulyposlednispoj_prijezd % 60) . "</br>";
//  echo "limit : " . $minulyposlednispoj_prijezd . "/" . $poslednispoj_prijezd . " - " . $poslednispoj_odjezd . "/" . (($H * 60 + $M) + 120) . "</br>";
  if (($minulyposlednispoj_prijezd < $poslednispoj_prijezd) && ($poslednispoj_odjezd >= (($H * 60 + $M) + $maxcas))) {
    $konechledani = true;
//    echo "true";
  } else {
    $konechledani = false;
//    echo "false";
  }
//  echo "konec = " . $konechledani . "</br></br>";
  $minulyposlednispoj_odjezd = $poslednispoj_odjezd;
  $minulyposlednispoj_prijezd = $poslednispoj_prijezd;
//  echo "nuluj graf od casu : " . intval($startCas / 60) . ":" . intval($startCas % 60) . "</br>";
  nulujGraf($start_UZEL, $startCas);
}

//echo "</br></br>";
//vyhodnoceni_spojeni();
/* for ($i = 0; $i < count($SPOJE); $i++) {
  if ($SPOJE[$i]->platny == 1) {
  for ($ii = count($SPOJE[$i]->PART) - 1; $ii >= 0; $ii--) {
  $val1 = $SPOJE[$i]->PART[$ii];
  echo intval($val1->odjezd / 60) . ":" . ($val1->odjezd % 60) . " - " . intval($val1->prijezd / 60) . ":" . ($val1->prijezd % 60) . " --- " . $val1->c_linky . " | " . $val1->od_nazev . " (" . iconv("CP1250", "CP1250", $val1->od_zastavky) . ") " . " - " . iconv("CP1250", "CP1250", $val1->do_nazev) . " (" . $val1->do_zastavky . ")" . "</br>";
  }
  echo "</br></br>";
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
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div>";
$res = $res . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:700px; width: auto;'>";
$res = $res . "<tr>";
$res = $res . "<td>";

if (true == true/* ($this->vyhledaneSpoje != null) && ($this->vyhledaneSpoje->size() > 0) */) {
  for ($i = 0; $i < count($SPOJE); $i++) {
    if ($SPOJE[$i]->platny == 1) {
      $res = $res . "<table class = 'tablejr' style='width: 100%;'>";
      $res = $res . "<tr>";

      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_linka);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_zezastavky);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_odjezd);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_dozastavky);
      $res = $res . "<th>" . iconv('windows-1250', 'UTF-8', $rs_prijezd);
      $res = $res . "</tr><tr>";

      for ($ii = count($SPOJE[$i]->PART) - 1; $ii >= 0; $ii--) {
        //$Spoj = $this->vyhledaneSpoje->elementAt($i)->elementAt($ii);
        $val1 = $SPOJE[$i]->PART[$ii];

        $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
        $res = $res . "<a class = 'a_nazev_linky1' style='font-size: 18px;'>";
        $res = $res . iconv('windows-1250', 'UTF-8', $val1->nazev_linky);
        $res = $res . "</a>";
        $res = $res . (($val1->doprava == 'T') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/autobus_small.png'></img>" :
                        (($val1->doprava == 'O') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/trolejbus_small.png'></img>" :
                                (($val1->doprava == 'A') ? "<img src = 'http://www.mhdspoje.cz/jrw50/image/tramvaj_small.png'></img>" : "")));

        $res = $res . "</td>";

        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
        /*          switch ($location) {
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
          } */
        $res = $res . iconv('windows-1250', 'UTF-8', $val1->od_nazev);
        $res = $res . "</td>";

        if ($ii == count($SPOJE[$i]->PART) - 1) {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
        } else {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
        }
        $res = $res . ((intval($val1->odjezd / 60) < 10) ? "0" . intval($val1->odjezd / 60) : intval($val1->odjezd / 60)) . " : " . ((($val1->odjezd % 60) < 10) ? "0" . ($val1->odjezd % 60) : ($val1->odjezd % 60));
        $res = $res . "</td>";

        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
        /*          switch ($location) {
          case 17: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='event.cancelBubble; event.stopPropagation(); selfobj.map(" . $zastDo . ", " . (($Spoj->doLocA == '') ? 'null' : $Spoj->doLocA) . ", " . (($Spoj->doLocB == '') ? 'null' : $Spoj->doLocB) . ", " . $Spoj->Zdo . ");'>&nbsp;</img>";
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
          } */
        $res = $res . iconv('windows-1250', 'UTF-8', $val1->do_nazev);
        $res = $res . "</td>";

        if ($ii == 0) {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal; font-weight: bold'>";
        } else {
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
        }
        $res = $res . ((intval($val1->prijezd / 60) < 10) ? "0" . intval($val1->prijezd / 60) : intval($val1->prijezd / 60)) . " : " . ((($val1->prijezd % 60) < 10) ? "0" . ($val1->prijezd % 60) : ($val1->prijezd % 60));
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
//echo $res;
?>