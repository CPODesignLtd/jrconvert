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

class TUzel {

  var $c_zastavky = null;
  var $c_tarif = null;
  var $c_linky = null;
  var $DOc_zastavky = null;
  var $DOc_tarif = null;
  var $DOc_linky = null;
  var $cas = null;
  var $casDO = null;
  var $dobajizdy = null;
  var $dobacekani = null;
  var $vaha = null;
  var $odUzel = null;
  var $odebran = 0;
  var $doUzelMam = 0;

}

$pocetresult = 0;
echo "time : " . time();
echo "</br>";
$sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
echo $sql . "</br>";
$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

$varGRF = 0;
$maxcas = 0;

while ($row = $result->fetch_row()) {
  $sqlVARGRF = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";

  $mysqliVARGRF = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqliVARGRF->query("SET NAMES 'utf-8';");
  $resultVARGRF = $mysqliVARGRF->query($sqlVARGRF);
  $rowVARGRF = $resultVARGRF->fetch_row();
  $varGRF += $rowVARGRF[0];
}

echo $varGRF;
echo "</br>";
echo "</br>";
echo "time : " . time();
echo "</br>";

function existUzel($sit, $ODZastavky, $DOZastavky, $ODTarif, $DOTarif, $linka) {
  $ret = null;
  if ($sit != null) {
//    foreach ($sit as $key => $val) {
    $val = $sit[$ODZastavky];
      for ($i = 0; $i < count($val); $i++) {
        if (($val[$i]->c_zastavky == $ODZastavky)  && ($val[$i]->DOc_zastavky == $DOZastavky) /*&& ($val[$i]->c_tarif == $ODTarif) && ($val[$i]->DOc_tarif == $DOTarif)*//* && ($val[$i]->c_linky == $linka)*/) {
          $ret = &$val[$i];
          break;
        }
      }
//    }
  }
  return $ret;
}

function getUzly($sit, $location, $packet, $c_zastavky, $cas, $oduzel, $varGRF, $minprestup) {
  global $maxcas;
  global $pocetresult;
  $sql = "call getOdjezdyZastavkaTEST1(" . $location . " ," . $packet . ", " . $cas . ", " . $c_zastavky . ", " . $varGRF . ");";

  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $result = $mysqli->query($sql);
  $pocetresult += 1;

  while ($row = $result->fetch_row()) {
    $nalezenuzel = existUzel(&$sit, $row[1], $row[2], $row[3], $row[4], $row[0]);
    $zapis = true;
    if ($nalezenuzel != null) {
      if ($nalezenuzel->odebran == 0) {
        if ($oduzel == null) {
          $vaha = $row[8] - $cas; // $uzel->dobacekani;
        } else {
          $vaha = $oduzel->vaha + $oduzel->dobajizdy + ($row[8] - $cas);
          if ($oduzel->c_linky <> $row[0]) {
            if ((($row[8] - $cas) >= $minprestup) && (($row[8] - $cas) <= 30)) {
              $zapis = true;
            } else {
              $zapis = false;
            }
          }
        }
      } else {
        $zapis = false;
      }
      if (($nalezenuzel->vaha > $vaha) && ($zapis == true)) {
        $nalezenuzel->vaha = $vaha;
        $nalezenuzel->odUzel = $oduzel;
        $uzel = $nalezenuzel;
        $uzel->c_zastavky = $row[1];
        $uzel->c_tarif = $row[3];
        $uzel->c_linky = $row[0];
        $uzel->DOc_zastavky = $row[2];
        $uzel->DOc_tarif = $row[4];
        $uzel->DOc_linky = $row[0];
        $uzel->odUzel = $oduzel;
        $oduzel->doUzelMam = 1;
        $uzel->cas = $row[8];
        $uzel->casDO = $row[9];
        $uzel->dobajizdy = $uzel->casDO - $uzel->cas;
        $uzel->dobacekani = $uzel->cas - $cas;
//        echo "nalezen uzel" . "</br>";
//        echo $uzel->c_zastavky . "->" . $uzel->DOc_zastavky . " | " . $uzel->c_tarif . "->" . $uzel->DOc_tarif . " | " . $uzel->c_linky . " | " . (int) ($uzel->cas / 60) . ":" . (int) ($uzel->cas % 60) . " | " . (int) ($uzel->casDO / 60) . ":" . (int) ($uzel->casDO % 60) . " | " . $uzel->dobajizdy . "|" . $uzel->dobacekani . "|" . $uzel->vaha . "</br>";
      }
    } else {
      $uzel = new TUzel();
      $uzel->c_zastavky = $row[1];
      $uzel->c_tarif = $row[3];
      $uzel->c_linky = $row[0];
      $uzel->DOc_zastavky = $row[2];
      $uzel->DOc_tarif = $row[4];
      $uzel->DOc_linky = $row[0];
      $uzel->odUzel = $oduzel;
      $oduzel->doUzelMam = 1;
      $uzel->cas = $row[8];
      $uzel->casDO = $row[9];
      $uzel->dobajizdy = $uzel->casDO - $uzel->cas;
      $uzel->dobacekani = $uzel->cas - $cas;
      $uzel->vaha = 9999999; //$uzel->cas - $cas;
      $preduzel = $uzel->odUzel;
      if ($preduzel == null) {
        $uzel->vaha = $uzel->dobacekani;
        if ($maxcas < $uzel->cas) {
          $maxcas = $uzel->cas + 1;
        }
      } else {
        $uzel->vaha = $preduzel->vaha + $preduzel->dobajizdy + $uzel->dobacekani;
      }
      $uzel->odebran = 0;
      /*      if ($oduzel != null) {
        if (($oduzel->c_zastavky <> $uzel->DOc_zastavky) || ($oduzel->c_tarif <> $uzel->DOc_tarif)) {
        $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
        echo "zapis" . "</br>";
        echo $uzel->c_zastavky . "->" . $uzel->DOc_zastavky . " | " . $uzel->c_tarif . "->" . $uzel->DOc_tarif . " | " . $uzel->c_linky . " | " . (int) ($uzel->cas / 60) . ":" . (int) ($uzel->cas % 60) . " | " . (int) ($uzel->casDO / 60) . ":" . (int) ($uzel->casDO % 60) . " | " . $uzel->dobajizdy . "|" . $uzel->dobacekani . "|" . $uzel->vaha . "</br>";
        }
        } else {
        $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
        echo "zapis" . "</br>";
        echo $uzel->c_zastavky . "->" . $uzel->DOc_zastavky . " | " . $uzel->c_tarif . "->" . $uzel->DOc_tarif . " | " . $uzel->c_linky . " | " . (int) ($uzel->cas / 60) . ":" . (int) ($uzel->cas % 60) . " | " . (int) ($uzel->casDO / 60) . ":" . (int) ($uzel->casDO % 60) . " | " . $uzel->dobajizdy . "|" . $uzel->dobacekani . "|" . $uzel->vaha . "</br>";
        } */
      $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
//        echo "zapis" . "</br>";
//        echo $uzel->c_zastavky . "->" . $uzel->DOc_zastavky . " | " . $uzel->c_tarif . "->" . $uzel->DOc_tarif . " | " . $uzel->c_linky . " | " . (int) ($uzel->cas / 60) . ":" . (int) ($uzel->cas % 60) . " | " . (int) ($uzel->casDO / 60) . ":" . (int) ($uzel->casDO % 60) . " | " . $uzel->dobajizdy . "|" . $uzel->dobacekani . "|" . $uzel->vaha . "</br>";
    }
//    echo $uzel->c_zastavky . "->" . $uzel->DOc_zastavky . " | " . $uzel->c_tarif . "->" . $uzel->DOc_tarif . " | " . $uzel->c_linky . " | " . (int) ($uzel->cas / 60) . ":" . (int) ($uzel->cas % 60) . " | " . (int) ($uzel->casDO / 60) . ":" . (int) ($uzel->casDO % 60) . " | " . $uzel->dobajizdy . "|" . $uzel->dobacekani . "|" . $uzel->vaha . "</br>";
    /*    if ($oduzel != null) {
      if ($oduzel->c_linky <> $uzel->c_linky) {
      if ($oduzel->casDO - $uzel->cas >= $minprestup) {
      if (existUzel($sit, $uzel->c_zastavky, $uzel->DOc_zastavky, $uzel->c_tarif, $uzel->DOc_tarif, $uzel->c_linky) == false) {
      $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
      echo "zapis" . "</br>";
      }
      }
      } else {
      if (existUzel($sit, $uzel->c_zastavky, $uzel->DOc_zastavky, $uzel->c_tarif, $uzel->DOc_tarif, $uzel->c_linky) == false) {
      $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
      echo "zapis" . "</br>";
      }
      }
      } else {
      if (existUzel($sit, $uzel->c_zastavky, $uzel->DOc_zastavky, $uzel->c_tarif, $uzel->DOc_tarif, $uzel->c_linky) == false) {
      $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
      echo "zapis" . "</br>";
      }
      } */

    /*    if (existUzel($sit, $uzel->c_zastavky, $uzel->DOc_zastavky, $uzel->c_tarif, $uzel->DOc_tarif, $uzel->c_linky) == false) {
      $sit[$c_zastavky][count($sit[$c_zastavky])] = $uzel;
      echo "zapis" . "</br>";
      } */
  }
}

function ohodnotUzly($sit) {
  if ($sit != null) {
    foreach ($sit as $key => $val) {
      for ($i = 0; $i < count($val); $i++) {
        $uzel = $val[$i];
        if ($uzel->odebran == 0) {
          $oduzel = $uzel->odUzel;
          $celkemdoba = 99999999;
          if ($oduzel != null) {
            $celkemdoba = $oduzel->dobacekani + $oduzel->dobajizdy + $uzel->dobacekani;
          } else {
            $celkemdoba = $uzel->dobacekani;
          }
          if ($uzel->vaha > $celkemdoba) {
            $uzel->vaha = $celkemdoba;
          }
        }
      }
    }
  }
}

function getMinUzel($sit) {
  $ret = null;
  $min = 999999999;
  if ($sit != null) {
    foreach ($sit as $key => $val) {
      for ($i = 0; $i < count($val); $i++) {
        $uzel = $val[$i];
        if (($min > $uzel->vaha) && ($uzel->odebran == 0)) {
          $min = $uzel->vaha;
          $ret = $uzel;
        }
      }
    }
  }
  return $ret;
}

//9-beethovenova - 28 - divadlo

$minprestup = 2;
$odcas = 720;
$vysledku = 0;
echo $pocatek . "</br>";
echo $cil . "</br>";

while (($odcas < (720 + 2 * 60)) && ($vysledku < 24)) {
  $sit = null;
  echo "time : " . time();
echo "</br>";
  getUzly(&$sit, $location, $packet, $pocatek, $odcas, null, $varGRF, $minprestup);
  echo "time : " . time();
echo "</br>";

/*  if ($sit != null) {
    foreach ($sit as $key => $val) {
      for ($i = 0; $i < count($val); $i++) {
        echo $val[$i]->c_zastavky . "->" . $val[$i]->DOc_zastavky . " | " . $val[$i]->c_tarif . "->" . $val[$i]->DOc_tarif . " | " . $val[$i]->c_linky . " | " . (int) ($val[$i]->cas / 60) . ":" . (int) ($val[$i]->cas % 60) . " | " . (int) ($val[$i]->casDO / 60) . ":" . (int) ($val[$i]->casDO % 60) . " | " . $val[$i]->dobajizdy . "|" . $val[$i]->dobacekani . "|" . $val[$i]->vaha . "|" . $val[$i]->odebran . "</br>";
      }
    }
  }
  echo "</br></br>";*/

//  ohodnotUzly(&$sit);

  $minuzel = getMinUzel($sit);
  if ($minuzel != null) {
//    $vysledek[count($vysledek)] = &$minuzel;
    $minuzel->odebran = 1;
//    echo $minuzel->c_zastavky . "->" . $minuzel->DOc_zastavky . " | " . $minuzel->c_tarif . "->" . $minuzel->DOc_tarif . " | " . $minuzel->c_linky . " | " . (int) ($minuzel->cas / 60) . ":" . (int) ($minuzel->cas % 60) . " | " . (int) ($minuzel->casDO / 60) . ":" . (int) ($minuzel->casDO % 60) . " | " . $minuzel->dobajizdy . "|" . $minuzel->dobacekani . "|" . $minuzel->vaha . "</br>";
//    if ($minuzel->odUzel != null) {
//      $preduzel = $minuzel->odUzel;
//      echo " <- " . $preduzel->c_zastavky . "->" . $preduzel->DOc_zastavky . " | " . $preduzel->c_tarif . "->" . $preduzel->DOc_tarif . " | " . $preduzel->c_linky . " | " . (int) ($preduzel->cas / 60) . ":" . (int) ($preduzel->cas % 60) . " | " . (int) ($preduzel->casDO / 60) . ":" . (int) ($preduzel->casDO % 60) . " | " . $preduzel->dobajizdy . "|" . $preduzel->dobacekani . "|" . $preduzel->vaha . "</br>";
//    }
  }

  if ($minuzel != null) {
    $konec = false;
    while ($konec == false) {
      if ($minuzel != null) {
        getUzly(&$sit, $location, $packet, $minuzel->DOc_zastavky, $minuzel->casDO, &$minuzel, $varGRF, $minprestup);
        echo "time : " . time();
echo "</br>";
//        ohodnotUzly(&$sit);

/*        if ($sit != null) {
          foreach ($sit as $key => $val) {
            for ($i = 0; $i < count($val); $i++) {
              echo $val[$i]->c_zastavky . "->" . $val[$i]->DOc_zastavky . " | " . $val[$i]->c_tarif . "->" . $val[$i]->DOc_tarif . " | " . $val[$i]->c_linky . " | " . (int) ($val[$i]->cas / 60) . ":" . (int) ($val[$i]->cas % 60) . " | " . (int) ($val[$i]->casDO / 60) . ":" . (int) ($val[$i]->casDO % 60) . " | " . $val[$i]->dobajizdy . "|" . $val[$i]->dobacekani . "|" . $val[$i]->vaha . "|" . $val[$i]->odebran . "</br>";
              ob_flush();
              flush();
            }
          }
        }
        echo "</br></br>";*/

        $minuzel = getMinUzel($sit);
        if ($minuzel != null) {
//          $vysledek[count($vysledek)] = &$minuzel;
          $minuzel->odebran = 1;
          if ($minuzel->DOc_zastavky == $cil) {
            echo "mam cil" . "</br>";
            $konec = true;
          }
//          echo $minuzel->c_zastavky . "->" . $minuzel->DOc_zastavky . " | " . $minuzel->c_tarif . "->" . $minuzel->DOc_tarif . " | " . $minuzel->c_linky . " | " . (int) ($minuzel->cas / 60) . ":" . (int) ($minuzel->cas % 60) . " | " . (int) ($minuzel->casDO / 60) . ":" . (int) ($minuzel->casDO % 60) . " | " . $minuzel->dobajizdy . "|" . $minuzel->dobacekani . "|" . $minuzel->vaha . "</br>";
//          if ($minuzel->odUzel != null) {
//            $preduzel = $minuzel->odUzel;
//            echo " <- " . $preduzel->c_zastavky . "->" . $preduzel->DOc_zastavky . " | " . $preduzel->c_tarif . "->" . $preduzel->DOc_tarif . " | " . $preduzel->c_linky . " | " . (int) ($preduzel->cas / 60) . ":" . (int) ($preduzel->cas % 60) . " | " . (int) ($preduzel->casDO / 60) . ":" . (int) ($preduzel->casDO % 60) . " | " . $preduzel->dobajizdy . "|" . $preduzel->dobacekani . "|" . $preduzel->vaha . "</br>";
//          }
        } else {
          $konec = true;
        }
      } else {
        $konec = true;
      }
    }
  }

  echo "</br></br>";

  if ($minuzel == null) {
    $odcas = $maxcas;
    $mamcil = false;
  }
  while ($minuzel != null) {
    echo $minuzel->c_zastavky . "->" . $minuzel->DOc_zastavky . " | " . $minuzel->c_tarif . "->" . $minuzel->DOc_tarif . " | " . $minuzel->c_linky . " | " . (int) ($minuzel->cas / 60) . ":" . (int) ($minuzel->cas % 60) . " | " . (int) ($minuzel->casDO / 60) . ":" . (int) ($minuzel->casDO % 60) . " | " . $minuzel->dobajizdy . "|" . $minuzel->dobacekani . "|" . $minuzel->vaha . "</br>";
    $odcas = $minuzel->cas + 1;
    $minuzel = $minuzel->odUzel;
    $mamcil = true;
  }
  if ($mamcil == true) {
    $vysledku = $vysledku + 1;
  }
  echo "</br>";
  echo $vysledku;
  echo "</br>";
  echo "hhh";
  echo "</br>";
  echo $pocetresult;
  echo "</br>";
  echo "time : " . time();
echo "</br>";
  ob_flush();
  flush();
}
?>
