<?php

require_once 'Vector.php';

$linka = $_GET['linka'];
$smer = $_GET['smer'];
$tarif = $_GET['tarif'];
$location = $_GET['location'];
$packet = $_GET['packet'];

if (isset($_GET['x'])) {
  $x = $_GET['x'];
} else {
  $x = NULL;
}

if (isset($_GET['y'])) {
  $y = $_GET['y'];
} else {
  $y = NULL;
}

if (isset($_GET['jrtype'])) {
  $jrtype = $_GET['jrtype'];
} else {
  $jrtype = NULL;
}

$denniJR = FALSE;
if (isset($_GET['denni'])) {
  if ($_GET['denni'] == 1) {
    $denniJR = TRUE;
  } else {
    $denniJR = FALSE;
  }
} else {
  $denniJR = TRUE;
}

$sdruzJR = FALSE;
if (isset($_GET['sdruz'])) {
  if ($_GET['sdruz'] == 1) {
    $sdruzJR = TRUE;
  } else {
    $sdruzJR = FALSE;
  }
} else {
  $sdruzJR = TRUE;
}

if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

if (isset($_GET['sel'])) {
  $sel = $_GET['sel'];
} else {
  $sel = -1;
}

$dbname = 'savvy_mhdspoje';

class TLinka {

  var $idlinky = null;
  var $nazev = null;
  var $smerA = null;
  var $smerB = null;
  var $doprava = null;

}

class TTrasaElement {

  var $Tarif = null;
  var $Nazev = null;
  var $PasmoA = null;
  var $PasmoB = null;
  var $poznamky = null;
  var $stavi = FALSE;

}

class TOdjezdElement {

  var $hh = null;
  var $mm = null;
  var $cspoje = null;
  var $ctarif = null;
  var $chrono = null;
  var $kurz = null;
  var $poznamky = null;
  var $odjezdytrasa = null;

}

class TPoznamkaElement {

  var $zkratka = null;
  var $popis = null;
  var $show = null;
  var $time = null;
  var $pic = null;

}

class TJRTypesElement {

  var $sloupec = null;
  var $popis = null;
  var $vargrf = null;
  var $odjezdy = null;
  var $pocetsloupcu = 1;
  var $show = FALSE;
  var $active = FALSE;

}

class TChronoElement {

  var $c_zastavky = null;
  var $c_tarif = null;
  var $doba_jizdy = null;
  var $doba_pocatek = null;

}

class TChronometr {

  var $chrono = null;

}

$TPoznamky = new Vector();
$TPoznamkyZastavky = new Vector();
$TPoznamkySpoje = new Vector();
$TJRTypes = new Vector();
$TChrono = new Vector();

$hasPasmoA = FALSE;
$hasPasmoB = FALSE;

$actualJRType = null;
$nextJRType = null;

$typyGrf = array("Mon" => "('X', '1')", "Tue" => "('X', '2')", "Wed" => "('X', '3')", "Thu" => "('X', '4')",
    "Fri" => "('X', '5')", "Sat" => "('6')", "Sun" => "('7', '+')");
$typyGrfDay = array(
    "Mon" => array('X', '1'),
    "Tue" => array('X', '2'),
    "Wed" => array('X', '3'),
    "Thu" => array('X', '4'),
    "Fri" => array('X', '5'),
    "Sat" => array('6'),
    "Sun" => array('7', '+'));
$typySloupcu = array(0 => array('X', '1', '2', '3', '4', '5', 'c'), 1 => array('6'), 2 => array('7', '+'));
$mesice = array(0 => "ledna", "února", "bøezna", "dubna", "kvìtna", "èervna", "èervence", "srpna", "záøí", "øíjna", "listopadu", "prosince");

$layout = array(1 => array(2), 2 => array(3), 17 => array(1, 2));

function getJRTypeByDate($datum, $location, $packet, $typyGrf) {
  if (!($p1 = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
  } else {
    mysql_query("SET NAMES 'utf-8';");

    $sql = "select exists(select 1 from savvy_mhdspoje.kalendar where 
            kalendar.datum = '" . date_format(new DateTime($datum), 'Y-m-d') . "' and kalendar.idlocation = " . $location . " and 
            kalendar.packet = " . $packet . ") as exist_kalendar";

    $result = mysql_query($sql);

    $rowdatum = mysql_fetch_row($result);

    if (($rowdatum == FALSE) || ($rowdatum[0] == 0)) {
      $vargrf = $typyGrf[date_format(new DateTime($datum), 'D')];

      $sql = "select distinct c_sloupce from 
              (select * from savvy_mhdspoje.pevnykod where pevnykod.oznaceni in " . $vargrf . " and pevnykod.caspozn = 1 and 
              pevnykod.idlocation = " . $location . " and pevnykod.packet = " . $packet . ") akalendar inner join 
              savvy_mhdspoje.jrvargrfs ajrvargrfs 
              on (akalendar.c_kodu = ajrvargrfs.c_kodu) 
              inner join savvy_mhdspoje.jrtypes ajrtypes 
              on (ajrvargrfs.idtimepozn = ajrtypes.idtimepozn and ajrtypes.idlocation = " . $location . " and ajrtypes.packet = " . $packet . ")";

      $result = mysql_query($sql);

      $res = mysql_fetch_row($result);

      return (($res == FALSE) ? $res : (integer) $res[0]);
    } else {
      $sql = "select distinct jrtypes.c_sloupce from savvy_mhdspoje.kalendar join savvy_mhdspoje.jrvargrfs on (kalendar.pk = jrvargrfs.c_kodu) join savvy_mhdspoje.jrtypes 
              on (jrvargrfs.idtimepozn = jrtypes.idtimepozn and jrtypes.idlocation = " . $location . " and jrtypes.packet = " . $packet . ") 
              where kalendar.datum = '" . date_format(new DateTime($datum), 'Y-m-d') . "' and kalendar.idlocation = " . $location . " and kalendar.packet = " . $packet;

      $result = mysql_query($sql);

      $res = mysql_fetch_row($result);

      return (($res == FALSE) ? $res : (integer) $res[0]);
    }
  }
}

function getActualJRType(&$actualJRType, &$nextJRType, $TJRTypes, $location, $packet, $typyGrf) {
  $actualJRType = getJRTypeByDate(date('Y-m-d'), $location, $packet, $typyGrf);

  $plusday = 1;
  do {
    $nextJRType = getJRTypeByDate(date('Y-m-d', strtotime("+" . $plusday . " day")), $location, $packet, $typyGrf);
    $plusday++;
  } while ((($nextJRType == FALSE) || ($TJRTypes->elementAt((integer) ($nextJRType - 1))->show == FALSE)) && ($plusday < 366));
}

function getNejblizsiOdjezd(&$TJRTypes, &$x, &$y, $sloupec, $odCasu) {
  $hh = (integer) date("G");
  $mm = (integer) date("i");
  $br = FALSE;
  for ($radek = (($odCasu) ? $hh : 0); $radek < $TJRTypes->elementAt($sloupec - 1)->odjezdy->size(); $radek++) {
    for ($sloupek = 0; $sloupek < $TJRTypes->elementAt($sloupec - 1)->odjezdy->elementAt($radek)->size(); $sloupek++) {
      $index = ($radek * 60) + $TJRTypes->elementAt($sloupec - 1)->odjezdy->elementAt($radek)->elementAt($sloupek)->mm + (($odCasu) ? 0 : 10000);
      if ($index > ($hh * 60) + $mm) {
        $x = $sloupek;
        $y = $radek;
        $br = TRUE;
        break;
      }
    }
    if ($br) {
      break;
    }
  }
}

function findZastavkaByTarif($Trasa, $c_tarif) {
  $ret = NIL;
  for ($i = 0; $i < $Trasa->size(); $i++) {
    if ($Trasa->elementAt($i)->Tarif == $c_tarif) {
      $ret = $Trasa->elementAt($i);
    }
  }
  return $ret;
}

function getVargrf($JRType) {
  $result = "";
  for ($i = 0; $i < $JRType->vargrf->size(); $i++) {
    if ($result != "") {
      $result = $result . ", ";
    }
    $result = $result . $JRType->vargrf->elementAt($i);
  }
  return "(" . $result . ")";
}

function getTrasaTable($Linka, $Trasa, $Tarif, $Smer, $Pasmo, $Poznamky, $Location, $Packet, $denniJR, $datumJR, $sdruzJR) {
  $res = "";
  $res = $res . "<table class='t_in'>";
  $res = $res . "<tr>";

// --- ukazatel ---  
  $res = $res . "<td style='vertical-align:top;text-align:center'>";
  $res = $res . "<div class='div_ram_transparent'>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "&nbsp");
  $res = $res . "</div>";

  $res = $res . "<div class='div_ram_transparent'>";
  $res = $res . "<table class='t_in' style='width:35px'>";

  if ($sdruzJR == TRUE) {
    $res = $res . "<tr>";
    $res = $res . "<td>";
    $res = $res . "&nbsp";
    $res = $res . "</td>";
    $res = $res . "</tr>";

    $res = $res . "<tr>";
    $res = $res . "<td>";
    $res = $res . "&nbsp";
    $res = $res . "</td>";
    $res = $res . "</tr>";
  }

  for ($i = 0; $i < $Trasa->elementCount; $i++) {
    if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<tr class='pointer_active'>";
    } else {
      $res = $res . "<tr class='pointer_inactive'>";
    }

//    $res = $res . "<td class = 'cell_zastavky_pointer'>";
    $res = $res . "<td>";
    $res = $res . "&nbsp";
    /*    if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<div id = 'div_zastavky_pointer_active' class = 'div_zastavky_pointer_active'>&nbsp</div>";
      } else {
      $res = $res . "<div id = 'div_zastavky_pointer_inactive' class = 'div_zastavky_pointer_inactive'>&nbsp</div>";
      } */
    $res = $res . "</td>";
    $res = $res . "</tr>";
  }
  $res = $res . "</table>";
  $res = $res . "</div>";
  $res = $res . "</td>";

// --- pásmo ---  
  if ($Pasmo != null) {
//    $res = $res . "<td class='cell_zastavky_pasmo' style='vertical-align:top;text-align:center'>";
    $res = $res . "<td style='vertical-align:top;text-align:center'>";

    $res = $res . "<div class='div_ram'>";
    $res = $res . iconv('ISO-8859-2', 'UTF-8', "Pásmo");
    $res = $res . "</div>";

    $res = $res . "<div class='div_ram_nobackgroundtransparent'>";
    $res = $res . "<table class='t_in' style='text-align:center;'>";

    if ($sdruzJR == TRUE) {
      $res = $res . "<tr>";
      $res = $res . "<td>";
      $res = $res . "&nbsp";
      $res = $res . "</td>";
      $res = $res . "</tr>";

      $res = $res . "<tr>";
      $res = $res . "<td>";
      $res = $res . "&nbsp";
      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    for ($i = 0; $i < $Trasa->elementCount; $i++) {
      if ($i % 2 == 0) {
        $res = $res . "<tr class='suda'>";
      } else {
        $res = $res . "<tr class='licha'>";
      }
      $res = $res . "<td>";

      if ($Smer == 0) {
        if ($Trasa->elementAt($i)->PasmoA == '') {
          $res = $res . "&nbsp";
        } else {
          $res = $res . $Trasa->elementAt($i)->PasmoA;
        }
      } else {
        if ($Trasa->elementAt($i)->PasmoB == '') {
          $res = $res . "&nbsp";
        } else {
          $res = $res . $Trasa->elementAt($i)->PasmoB;
        }
      }
      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    $res = $res . "</table>";
    $res = $res . "</div>";
    $res = $res . "</td>";
  }

// --- zastávky ---  
//  $res = $res . "<td class = 'cell_zastavky_zastavka' style = 'vertical-align: top; text-align: center;'>";
  $res = $res . "<td style='vertical-align:top;text-align:center'>";

  $res = $res . "<div class='div_ram'>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "Zastávka");
  $res = $res . "</div>";

  $res = $res . "<div class='div_ram_nobackground'>";
  $res = $res . "<table class='t_in'>";

  if ($sdruzJR == TRUE) {
    $res = $res . "<tr>";
    $res = $res . "<td>";
    $res = $res . "&nbsp";
    $res = $res . "</td>";
    $res = $res . "</tr>";
    $res = $res . "<tr>";
    $res = $res . "<td>";
    $res = $res . "&nbsp";
    $res = $res . "</td>";
    $res = $res . "</tr>";
  }

  for ($i = 0; $i < $Trasa->elementCount; $i++) {
    if ($i % 2 == 0) {
      if ($Trasa->elementAt($i)->stavi == FALSE) {
        $res = $res . "<tr class='suda_disabled'>";
      } else {
        if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
          $res = $res . "<tr class='suda_focused'>";
        } else {
          if ($sdruzJR) {
            $res = $res . "<tr class='suda' onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, " . $i . ", 0);'>";
//            echo "onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, " . $i . ", 0);";
          } else {
            $res = $res . "<tr class='suda' onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, null, 0);'>";
//            echo "onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, 0);";
          }
        }
      }
    } else {
      if ($Trasa->elementAt($i)->stavi == FALSE) {
        $res = $res . "<tr class='licha_disabled'>";
      } else {
        if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
          $res = $res . "<tr class='licha_focused'>";
        } else {
          if ($sdruzJR) {
            $res = $res . "<tr class='licha' onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, " . $i . ", 0);'>";
          } else {
            $res = $res . "<tr class='licha' onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . (integer) $Trasa->elementAt($i)->Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, null, 0);'>";
          }
        }
      }
    }
    $res = $res . "<td>";
    $zast = "\"" . $Trasa->elementAt($i)->Nazev . (($Location == 17) ? iconv('ISO-8859-2', 'UTF-8', ", Plzeò") : "") . "\"";
    switch ($Location) {
      case 17: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/prapor.png' onClick='a.map(" . $zast . ");'>&nbsp</img>";
          break;
        }
      case 1: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporR.png' onClick='a.map(" . $zast . ");'>&nbsp</img>";
          break;
        }
      default: {
          $res = $res . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='a.map(" . $zast . ");'>&nbsp</img>";
          break;
        }
    }

    $res = $res . $Trasa->elementAt($i)->Nazev . "&nbsp &nbsp";

    $textpozn = FALSE;
    for ($pki = 0; $pki < $Trasa->elementAt($i)->poznamky->size(); $pki++) {
      if ((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki) != 0) {
        if ($textpozn == TRUE) {
          $res = $res . ", ";
        }
        if ($Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->pic == null) {
          if ($Trasa->elementAt($i)->stavi == FALSE) {
            $res = $res . "<a class='a_zastavky_poznamka_disabled' title='" . $Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->popis . "'>";
          } else {
            $res = $res . "<a class='a_zastavky_poznamka' title='" . $Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->popis . "'>";
          }
          $res = $res . $Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->zkratka;
          $res = $res . "</a>";
        } else {
          $res = $res . "<img class='img_poznamka' src='http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->pic . "' title = '" . $Poznamky->elementAt((integer) $Trasa->elementAt($i)->poznamky->elementAt($pki))->popis . "'></ing>";
        }
        $textpozn = TRUE;
      }
    }
    $res = $res . "</td>";
    $res = $res . "</tr>";
  }

  $res = $res . "</table>";
  $res = $res . "</div>";
  $res = $res . "</td>";

  $res = $res . "</tr>";
  $res = $res . "</table>";
  return $res;
}

function getJR($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $JRType, $Poznamky, $Location, $Packet, $TypeJR, $X, $Y, $denniJR, $datumJR, $sdruzJR) {
  $res = "";
  if (($JRType->show == TRUE) || ($JRType->show == FALSE)) {
//    $res = $res . "<div class = 'div_header_jr'>";
    $res = $res . "<div class = 'div_ram'>";
    $res = $res . iconv('UTF-8', 'UTF-8', $JRType->popis);
    $res = $res . "</div>";

    $res = $res . "<div class = 'div_ram_nobackground'>";
    $res = $res . "<table class = 'table_time_JR' style='text-align:left'>";

    if ($sdruzJR == FALSE) {
      for ($i = 0; $i < 24; $i++) {
        if ($i % 2 == 0) {
          $res = $res . "<tr class='suda_jr'>";
        } else {
          $res = $res . "<tr class='licha_jr'>";
        }

        $res = $res . "<td class='cell_hour_jr'>";
        if ($i < 10) {
          $res = $res . "0" . $i;
        } else {
          $res = $res . $i;
        }
        $res = $res . "</td>";

        for ($ii = 0; $ii < $JRType->pocetsloupcu + 1; $ii++) {
          if ($ii == $JRType->pocetsloupcu) {
            /*            if (($TypeJR == $JRType->sloupec) && ($i == $Y) && ($ii == $X)) {
              $res = $res . "<td id='time_jr_active' style='width:100%'>";
              } else {
              if ($JRType->odjezdy->elementAt($i)->elementAt($ii) != NIL) {
              $res = $res . "<td id='time_jr' style='width:100%'>";
              } else {
              $res = $res . "<td style='width:100%'>";
              }
              } */
            $res = $res . "<td style='width:100%'>";
          } else {
            if (($TypeJR == $JRType->sloupec) && ($i == $Y) && ($ii == $X)) {
              $res = $res . "<td id='time_jr_active'>";
            } else {
              if ($JRType->odjezdy->elementAt($i)->elementAt($ii) != NIL) {
                $res = $res . "<td id='time_jr' onClick = '" . "getJR(" . $Linka->idlinky . ", " . $Smer . ", " . $Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", " . $JRType->sloupec . ", " . $ii . ", " . $i . ");'>";
              } else {
                $res = $res . "<td>";
              }
            }
          }
          if ($JRType->odjezdy->elementAt($i)->elementAt($ii) != NIL) {
            $res = $res . (($JRType->odjezdy->elementAt($i)->elementAt($ii)->mm < 10) ? "0" . $JRType->odjezdy->elementAt($i)->elementAt($ii)->mm : $JRType->odjezdy->elementAt($i)->elementAt($ii)->mm);
            $res = $res . "&nbsp";
            $textpozn = FALSE;
            if ($JRType->odjezdy->elementAt($i)->elementAt($ii) != NIL) {
              for ($pki = 0; $pki < $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->size(); $pki++) {
                if ((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki) != 0) {
                  if ($textpozn == TRUE) {
                    $res = $res . ", ";
                  }
                  if ($Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki))->pic == null) {
                    $res = $res . "<a class='a_pozn' title = '" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki))->popis . "'>";
                    $res = $res . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki))->zkratka;
                    $res = $res . "</a>";
                  } else {
                    $res = $res . "<img id = 'img_poznamka' class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki))->pic . "' title = '" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->elementAt($ii)->poznamky->elementAt($pki))->popis . "'></ing>";
                  }
                  $textpozn = TRUE;
                }
              }
            }
          } else {
            $res = $res . "&nbsp";
          }
          $res = $res . "&nbsp";
          $res = $res . "</td>";
        }

        $res = $res . "</tr>";
      }
    } else {
// -------- zahlavi c. spoje ----------    
      $res = $res . "<tr class = 'cell_time_jr_zahlavi'>";
      for ($i = 0; $i < $JRType->odjezdy->size() + 1; $i++) {
        if ($i == $JRType->odjezdy->size()) {
          $res = $res . "<td class = 'cell_time_jr_zahlavi_last'>";
        } else {
          $res = $res . "<td title = '" . iconv('ISO-8859-2', 'UTF-8', "spoj èíslo : ") . $JRType->odjezdy->elementAt($i)->cspoje . "'>";
        }
        $res = $res . $JRType->odjezdy->elementAt($i)->cspoje;
        $res = $res . "</td>";
      }
      $res = $res . "</tr>";
// -------- END zahlavi c. spoje ----------       
// -------- zahlavi poznamky ----------  
      $res = $res . "<tr class = 'cell_time_jr_zahlavi'>";
      for ($i = 0; $i < $JRType->odjezdy->size() + 1; $i++) {
        if ($i == $JRType->odjezdy->size()) {
          $res = $res . "<td class = 'cell_time_jr_zahlavi_last'>";
        } else {
          $res = $res . "<td>";
        }
//        $res = $res . "&nbsp";
        if ($JRType->odjezdy->elementAt($i)->poznamky != NIL) {
          $textpozn = FALSE;
          for ($pki = 0; $pki < $JRType->odjezdy->elementAt($i)->poznamky->size(); $pki++) {
            if ((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki) != 0) {
              if ($textpozn == TRUE) {
                $res = $res . ", ";
              }
              if ($Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->pic == null) {
//                $res = $res . "<a class = 'a_time_jr_poznamka' title = '" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->popis . "'>";
                $res = $res . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->zkratka;
//                $res = $res . "</a>";
              } else {
//                $res = $res . "<img class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->pic . "' title = '" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->popis . "'></ing>";
                $res = $res . "<img class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $JRType->odjezdy->elementAt($i)->poznamky->elementAt($pki))->pic . "'></ing>";
              }
              $textpozn = TRUE;
            }
          }
        }

        $res = $res . "</td>";
      }
      $res = $res . "</tr>";
// -------- END zahlavi poznamky ---------- 
// -------- ODJEZDY ----------      
      for ($ii = 0; $ii < $Trasa->size(); $ii++) {
        if ($Trasa->elementAt($ii)->Tarif == $Tarif) {
          $res = $res . "<tr id='jr" . $ii . "' class = 'cell_time_jr_zahlavi_active'>";
        } else {
          if ($ii % 2 == 0) {
            $res = $res . "<tr id='jr" . $ii . "' class = 'suda_jr'>";
          } else {
            $res = $res . "<tr id='jr" . $ii . "' class = 'licha_jr'>";
          }
        }
        for ($i = 0; $i < $JRType->odjezdy->size() + 1; $i++) {
          if ($i == $JRType->odjezdy->size()) {
            if ($ii % 2 == 0) {
              $res = $res . "<td class = 'suda_jr_last'>";
            } else {
              $res = $res . "<td class = 'licha_jr_last'>";
            }
          } else {
            $res = $res . "<td id='cj'>";
          }

          if ($JRType->odjezdy->elementAt($i)->odjezdytrasa != NIL) {
            $res = $res . $JRType->odjezdy->elementAt($i)->odjezdytrasa->elementAt($ii)->hh . ":" . $JRType->odjezdy->elementAt($i)->odjezdytrasa->elementAt($ii)->mm;
          }
          $res = $res . "</td>";
        }
        $res = $res . "</tr>";
      }
// -------- END ODJEZDY ----------
    }
    $res = $res . "</table>";
    $res = $res . "</div>";
  }
  return $res;
}

function getJRPoznamky($Poznamky, $PoznamkyZastavky, $PoznamkySpoje) {
  $res = "";

  if ($PoznamkySpoje->size() != 0) {
//    $res = $res . "<div class = 'div_poznamky_header'>";
//    $res = $res . "<a class = 'a_poznamky_header'>";
    $res = $res . iconv('ISO-8859-2', 'UTF-8', "Poznámky spojù :");
//    $res = $res . "</a>";
//    $res = $res . "</div>";

    $res = $res . "<table class = 'table_global_poznamky'>";
    for ($i = 0; $i < $PoznamkySpoje->size(); $i++) {
      $res = $res . "<tr>";
      $res = $res . "<td style='padding-left: 10px;'>";
//      $res = $res . "<div style='display: table;'>";
//      $res = $res . "<div class = 'div_poznamky_logo' style='float: left;'>";
//      $res = $res . "<div style='float: left; display: table; margin-left: 10px;'>";
      if ($Poznamky->elementAt((integer) $PoznamkySpoje->elementAt($i))->pic == null) {
//        $res = $res . "<a class = 'a_poznamky_logo'>";
        $res = $res . $Poznamky->elementAt((integer) $PoznamkySpoje->elementAt($i))->zkratka;
//        $res = $res . "</a>";
      } else {
//        $res = $res . "&nbsp";
        $res = $res . "<img class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $PoznamkySpoje->elementAt($i))->pic . "'></ing>";
      }
//      $res = $res . "</div>";
      $res = $res . "</td>";
      $res = $res . "<td style='white-space: normal; padding-left:10px;'>";
//      $res = $res . "<div class = 'div_poznamky_text' style='white-space: normal; margin-left: 10px;'>";
//      $res = $res . "<div style='white-space: normal; margin-left: 20px; display: table;'>";//      
//      $res = $res . "<a class = 'a_poznamky_text'>";
      $res = $res . $Poznamky->elementAt((integer) $PoznamkySpoje->elementAt($i))->popis;
//      $res = $res . "</a>";
//      $res = $res . "</div>";
//      $res = $res . "</div>";
      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    $res = $res . "</table>";
  }

  if ($PoznamkyZastavky->size() != 0) {
//    $res = $res . "<div class = 'div_poznamky_header'>";
//    $res = $res . "<a class = 'a_poznamky_header'>";
    $res = $res . iconv('ISO-8859-2', 'UTF-8', "Poznámky zastávek :");
//    $res = $res . "</a>";
//    $res = $res . "</div>";

    $res = $res . "<table class = 'table_global_poznamky'>";
    for ($i = 0; $i < $PoznamkyZastavky->size(); $i++) {
      $res = $res . "<tr>";
      $res = $res . "<td style='padding-left: 10px;'>";
//      $res = $res . "<div class = 'div_poznamky_logo'>";
      if ($Poznamky->elementAt((integer) $PoznamkyZastavky->elementAt($i))->pic == null) {
//        $res = $res . "<a class = 'a_poznamky_logo'>";
        $res = $res . $Poznamky->elementAt((integer) $PoznamkyZastavky->elementAt($i))->zkratka;
//        $res = $res . "</a>";
      } else {
        $res = $res . "<img class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $Poznamky->elementAt((integer) $PoznamkyZastavky->elementAt($i))->pic . "'></ing>";
      }
//      $res = $res . "</div>";
      $res = $res . "</td>";


      $res = $res . "<td style='white-space: normal; padding-left:10px;'>";
//      $res = $res . "<div class = 'div_poznamky_text'>";
//      $res = $res . "<a class = 'a_poznamky_text'>";
      $res = $res . $Poznamky->elementAt((integer) $PoznamkyZastavky->elementAt($i))->popis;
//      $res = $res . "</a>";
//      $res = $res . "</div>";
      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    $res = $res . "</table>";
  }

  return $res;
}

function getJizdaTable($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $Poznamky, $Location, $Packet, $JRTypes, $TypeJR, $X, $Y) {
  $res = "";
  $res = $res . "<table class='t_in'>";

  $res = $res . "<tr>";

// --- Min ---  
  $res = $res . "<td class = 'cell_zastavky_pasmo' style = 'vertical-align: top; text-align: center;'>";

  $res = $res . "<div class = 'div_ram'>";
//  $res = $res . "<a id = 'header_zastavky_pasmo' class = 'header_zastavky_pasmo'>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "Min");
//  $res = $res . "</a>";
  $res = $res . "</div>";

  $res = $res . "<div class = 'div_ram_nobackgroundtransparent'>";
  $res = $res . "<table class = 't_in' style='text-align:right'>";
  $lastmin = 0;
  for ($i = 0; $i < $Trasa->elementCount; $i++) {

    /*    if ((is_null($Y) == FALSE) && (is_null($X) == FALSE) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<tr class = 'licha_disabled'>";
      } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<tr class = 'licha_focused'>";
      } else {
      $res = $res . "<tr class = 'licha'>";
      }
      } */
    $res = $res . "<tr class='row_nb'>";

//    $res = $res . "<tr>";
//    $res = $res . "<td id = 'cell_zastavky_zastavka' class = 'cell_zastavky_zastavka'>";
    $res = $res . "<td>";

    /*    if ((is_null($Y) == FALSE) && (is_null($X) == FALSE) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<a id = 'a_trasa_zastavka_disabled' class = 'a_trasa_zastavka_disabled'>";
      } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<a id = 'a_trasa_zastavka_focused' class = 'a_trasa_zastavka_focused'>";
      } else {
      $res = $res . "<a id = 'a_trasa_zastavka' class = 'a_trasa_zastavka'>";
      }
      } */
    if ((is_null($X) == FALSE) && (is_null($Y) == FALSE)) {
      if (($Smer == 0) ? ((integer) $Tarif < (integer) $Trasa->elementAt($i)->Tarif) : ((integer) $Tarif > (integer) $Trasa->elementAt($i)->Tarif)) {
        if ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy != -1) {
          if ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy != -1) {
            $lastmin = $lastmin + $TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy;
          }
          $res = $res . $lastmin;
        } else {
          $res = $res . "&nbsp";
        }
      } else {
        $res = $res . "&nbsp";
      }
    } else {
      $res = $res . "&nbsp";
    }
//    $res = $res . "</a>";

    $res = $res . "</td>";
    $res = $res . "</tr>";
  }
  $res = $res . "</table>";
  $res = $res . "</div>";
  $res = $res . "</td>";

// --- Èas ---  
  $res = $res . "<td class = 'cell_zastavky_pasmo' style = 'vertical-align: top; text-align: center;'>";

  $res = $res . "<div class = 'div_ram'>";
//  $res = $res . "<a id = 'header_zastavky_pasmo' class = 'header_zastavky_pasmo'>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "Èas");
//  $res = $res . "</a>";
  $res = $res . "</div>";

  $res = $res . "<div class = 'div_ram_nobackgroundtransparent'>";
  $res = $res . "<table class = 't_in' style='text-align:center'>";
  $lastmin = ((is_null($X) == FALSE) && (is_null($Y) == FALSE)) ? ($Y * 60) + $JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->mm : 0;
  for ($i = 0; $i < $Trasa->elementCount; $i++) {
//    $res = $res . "<tr>";
    if ((is_null($Y) == FALSE) && (is_null($X == FALSE)) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<tr class = 'row_nb_disabled'>";
    } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
        $res = $res . "<tr class = 'row_nb_focused'>";
      } else {
        $res = $res . "<tr class = 'row_nb'>";
      }
    }
//    $res = $res . "<td id = 'cell_zastavky_zastavka' class = 'cell_zastavky_zastavka'>";
    $res = $res . "<td>";

    /*    if ((is_null($Y) == FALSE) && (is_null($X == FALSE)) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<a id = 'a_trasa_zastavka_disabled' class = 'a_trasa_zastavka_disabled'>";
      } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<a id = 'a_trasa_zastavka_focused' class = 'a_trasa_zastavka_focused'>";
      } else {
      $res = $res . "<a id = 'a_trasa_zastavka' class = 'a_trasa_zastavka'>";
      }
      } */
    if ((is_null($X) == FALSE) && (is_null($Y) == FALSE)) {
      if (($Smer == 0) ? ((integer) $Tarif <= (integer) $Trasa->elementAt($i)->Tarif) : ((integer) $Tarif >= (integer) $Trasa->elementAt($i)->Tarif)) {
        if ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy != -1) {
          if ($Tarif != (integer) $Trasa->elementAt($i)->Tarif) {
            $lastmin = $lastmin + $TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy;
          }
          $thh = (integer) (($lastmin) / 60);
          $tmm = (integer) ($lastmin % 60);
          $res = $res . (($thh < 10) ? "0" . $thh : $thh) . " : " . (($tmm < 10) ? "0" . $tmm : $tmm);
        } else {
          $res = $res . "--:--";
        }
      } else {
        $res = $res . "--:--";
      }
    } else {
      $res = $res . "--:--";
    }
//    $res = $res . "</a>";

    $res = $res . "</td>";
    $res = $res . "</tr>";
  }
  $res = $res . "</table>";
  $res = $res . "</div>";
  $res = $res . "</td>";

// --- zastávky ---  
  $res = $res . "<td class = 'cell_zastavky_zastavka' style = 'vertical-align: top; text-align: center;'>";

  $res = $res . "<div class = 'div_ram'>";
//  $res = $res . "<a id = 'header_zastavky_zastavka' class = 'header_zastavky_zastavka'>";
  $res = $res . iconv('ISO-8859-2', 'UTF-8', "Zastávka");
//  $res = $res . "</a>";
  $res = $res . "</div>";

  $res = $res . "<div class = 'div_ram_nobackground'>";
  $res = $res . "<table class = 't_in'>";

  for ($i = 0; $i < $Trasa->elementCount; $i++) {
//    $res = $res . "<tr>";
    if ((is_null($Y) == FALSE) && (is_null($X == FALSE)) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<tr class = 'row_nb_disabled'>";
    } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
        $res = $res . "<tr class = 'row_nb_focused'>";
      } else {
        $res = $res . "<tr class = 'row_nb'>";
      }
    }
//    $res = $res . "<td id = 'cell_zastavky_zastavka' class = 'cell_zastavky_zastavka'>";
    $res = $res . "<td>";

    /*    if ((is_null($Y) == FALSE) && (is_null($X == FALSE)) && ($TChrono->elementAt($JRTypes->elementAt($TypeJR - 1)->odjezdy->elementAt($Y)->elementAt($X)->chrono - 1)->chrono->elementAt($i)->doba_jizdy == -1)) {
      $res = $res . "<a id = 'a_trasa_zastavka_disabled' class = 'a_trasa_zastavka_disabled'>";
      } else {
      if ((integer) $Tarif == (integer) $Trasa->elementAt($i)->Tarif) {
      $res = $res . "<a id = 'a_trasa_zastavka_focused' class = 'a_trasa_zastavka_focused'>";
      } else {
      $res = $res . "<a id = 'a_trasa_zastavka' class = 'a_trasa_zastavka'>";
      }
      } */
    $res = $res . $Trasa->elementAt($i)->Nazev;
//    $res = $res . "</a>";

    $res = $res . "</td>";
    $res = $res . "</tr>";
  }

  $res = $res . "</table>";
  $res = $res . "</div>";
  $res = $res . "</td>";

  $res = $res . "</tr>";
  $res = $res . "</table>";

  return $res;
}

function getJRDiv($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $Poznamky, $JRTypes, $Location, $Packet, $TypeJR, $X, $Y, $PoznamkyZastavky, $PoznamkySpoje, $denniJR, $datumJR, $sdruzJR, $layout) {
  $lastindex = 0;
  $res = "";

// style = 'min-width: 500px; width: 990px;'  
  $res = $res . "<div class = 'div_pozadikomplex'>";
//style='background-color: rgb(255, 204, 51); margin-top: 5px; height: 30px;'
  $res = $res . "<div id='movediv' class='movediv'></div>";

  $res = $res . "<table id='tablejr' class = 'tablejr'>";
  for ($t = 0; $t < (($sdruzJR) ? $JRTypes->Size() : 1); $t++) {
    if ($sdruzJR == TRUE) {
      $JRTypes->elementAt($t)->active = TRUE;
    }
    if (($sdruzJR == FALSE) || (($sdruzJR == TRUE) && ($JRTypes->elementAt($t)->show))) {

      $res = $res . "<tr>";
      $res = $res . "<td>";
      if ($t > 0) {
        $res = $res . "<div class = 'div_separ'></div>";
      }
//--- header linka ---
      $res = $res . "<table style = 'width: 100%;'>";

      $res = $res . "<tr>";

      $res = $res . "<td class = 'td_ram_nobackground' style = 'min-width: 70px;'>";
      $res = $res . "<a class = 'a_nazev_linky'>";
      $res = $res . $Linka->nazev;
      $res = $res . "</a>";
      $res = $res . "</td>";

      $res = $res . "<td class = 'td_ram_nobackground'>";
//      $res = $res . "<div class = 'div_header_linka'>";
      if ($Linka->doprava == 'T') {
        $res = $res . "<div class = 'div_logo_doprava_T'></div>";
      }
      if ($Linka->doprava == 'O') {
        $res = $res . "<div class = 'div_logo_doprava_O'></div>";
      }
      if ($Linka->doprava == 'A') {
        $res = $res . "<div class = 'div_logo_doprava_A'></div>";
      }
      $res = $res . "</td>";

      $res = $res . "<td class = 'td_ram_nobackground' style = 'text-align: left; min-width: 70px; width: 100%'>";
      $res = $res . "<a class = 'a_smer_linky_label'>";
      $res = $res . iconv('ISO-8859-2', 'UTF-8', "Smìr :");
      $res = $res . "</a>";
      $res = $res . "<a class = 'a_smer_linky'>";
      if ($Smer == 0) {
        $res = $res . $Linka->smerA;
      } else {
        $res = $res . $Linka->smerB;
      }
      $res = $res . "</a>";
      $res = $res . "</td>";

      $res = $res . "</tr>";

      $res = $res . "</table>";

      $res = $res . "</td>";

      $res = $res . "</tr>";

//--- tìlo JØ ---  
      $res = $res . "<tr>";
      $res = $res . "<td>";

      $res = $res . "<div class = 'div_body_JR'>";
      $res = $res . "<table class = 'table_JR' style='width: 100%'>";
      $res = $res . "<tr>";

      $res = $res . "<td class = 'cell_zastavky' rowspan=" . count($layout[$Location]) . ">";
      $res = $res . getTrasaTable($Linka, $Trasa, $Tarif, $Smer, $Pasmo, $Poznamky, $Location, $Packet, $denniJR, $datumJR, $sdruzJR);
      $res = $res . "</td>";

      for ($jri = 0; $jri < ((($denniJR == TRUE) || ($sdruzJR == TRUE) || ($layout[$Location] == NULL)) ? $JRTypes->Size() : $layout[$Location][0]); $jri++) {
        if ($sdruzJR == FALSE) {
//          $res = $res . "<td class = 'cell_jr' colspan=2>";
          $res = $res . "<td class = 'cell_zastavky' colspan=2>";
          $res = $res . "<table class='t_in'><tr><td>";
          $res = $res . getJR($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $JRTypes->elementAt($jri), $Poznamky, $Location, $Packet, $TypeJR, $X, $Y, $denniJR, $datumJR, $sdruzJR);
          $res = $res . "</td></tr></table>";
          $res = $res . "</td>";
        } else {
          if (($JRTypes->elementAt($jri)->active) && ($JRTypes->elementAt($jri)->show)) {
//            $res = $res . "<td class = 'cell_jr'>";
            $res = $res . "<td class = 'cell_zastavky'>";
            $res = $res . "<table class='t_in'><tr><td>";
            $res = $res . getJR($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $JRTypes->elementAt($jri), $Poznamky, $Location, $Packet, $TypeJR, $X, $Y, $denniJR, $datumJR, $sdruzJR);
            $res = $res . "</td></tr></table>";
            $res = $res . "</td>";
          }
        }
      }

      $lastindex += $layout[$Location][0];

      if ($sdruzJR == FALSE) {
        $res = $res . "<td class = 'cell_zastavky' rowspan=" . count($layout[$Location]) . ">";
        $res = $res . getJizdaTable($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $Poznamky, $Location, $Packet, $JRTypes, $TypeJR, $X, $Y);
        $res = $res . "</td>";
      }

      $res = $res . "</tr>";

      if (($denniJR == FALSE) && ($layout[$Location] != NULL) && ($sdruzJR == FALSE)) {
        for ($l = 1; $l < count($layout[$Location]); $l++) {
          if ($JRTypes->elementAt($jri) != NULL) {
            $res = $res . "<tr>";
//      for ($jri = $JRTypes->Size() - 2; $jri < $JRTypes->Size(); $jri++) {
            for ($jri = $lastindex; $jri < min($lastindex + $layout[$Location][$l], $JRTypes->Size()); $jri++) {
              if ($sdruzJR == FALSE) {
//                $res = $res . "<td class = 'cell_jr'>";
                $res = $res . "<td class = 'cell_zastavky'>";
                $res = $res . "<table class='t_in'><tr><td>";
                $res = $res . getJR($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $JRTypes->elementAt($jri), $Poznamky, $Location, $Packet, $TypeJR, $X, $Y, $denniJR, $datumJR, $sdruzJR);
                $res = $res . "</td></tr></table>";
                $res = $res . "</td>";
              } /* else {
                if (($JRTypes->elementAt($jri)->active) && ($JRTypes->elementAt($jri)->show)) {
                $res = $res . "<td class = 'cell_jr'>";
                $res = $res . getJR($Linka, $Trasa, $TChrono, $Tarif, $Smer, $Pasmo, $JRTypes->elementAt($jri), $Poznamky, $Location, $Packet, $TypeJR, $X, $Y, $denniJR, $datumJR, $sdruzJR);
                $res = $res . "</td>";
                }
                } */
            }
          }
          $res = $res . "</tr>";
          $lastindex += $layout[$Location][$l];
        }
      }

      $res = $res . "</table>";
      $res = $res . "</div>";

      $res = $res . "<div class = 'div_FS'>";
      $res = $res . "<a class = 'a_FS'>";
      $res = $res . iconv('ISO-8859-2', 'UTF-8', "JRw ver. 5.0. - SKELETON &reg FS software s.r.o.");
      $res = $res . "</a>";
      $res = $res . "</div>";

      $res = $res . getJRPoznamky($Poznamky, $PoznamkyZastavky, $PoznamkySpoje);

      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    if ($sdruzJR == TRUE) {
      $JRTypes->elementAt($t)->active = FALSE;
    }
  }

  $res = $res . "</table>";
  $res = $res . "</div>";

  return $res;
}

function load() {
  global $linka;
  global $smer;
  global $tarif;
  global $location;
  global $packet;

  global $x;
  global $y;
  global $jrtype;
  global $denniJR;
  global $sdruzJR;
  global $datumJR;

  global $TPoznamky;
  global $TPoznamkyZastavky;
  global $TPoznamkySpoje;
  global $TJRTypes;
  global $TChrono;

  global $hasPasmoA;
  global $hasPasmoB;

  global $actualJRType;
  global $nextJRType;

  global $typyGrf;
  global $typyGrfDay;
  global $typySloupcu;
  global $mesice;

  global $layout;

  global $sel;

  if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
  } else {
    global $dbname;


    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "SELECT c_kodu, oznaceni, rezerva, caspozn, showing, obr FROM pevnykod where idlocation = " . $location . " and packet = " . $packet . " order by c_kodu";

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
      $pozn = new TPoznamkaElement();
      $pozn->zkratka = $row[1];
      $pozn->popis = $row[2];
      $pozn->time = $row[3];
      $pozn->show = $row[4];
      $pozn->pic = $row[5];
      $TPoznamky->addElement($pozn);
    }

    if ($denniJR == FALSE) {
      $sql = "SELECT nazev_sloupce, idtimepozn, c_sloupce FROM jrtypes where idlocation = " . $location . " and packet = " . $packet . " order by c_sloupce";

      $result = mysql_query($sql);

      while ($row = mysql_fetch_row($result)) {
        $sloupec = new TJRTypesElement();
        $sloupec->sloupec = $row[2];
        $sloupec->popis = $row[0];
        $sloupec->vargrf = new Vector();
        $sql1 = "SELECT c_kodu FROM jrvargrfs where idtimepozn = " . $row[1] . " order by c_kodu";
        $result1 = mysql_query($sql1);
        while ($row1 = mysql_fetch_row($result1)) {
          $sloupec->vargrf->addElement($row1[0]);
        }
        $TJRTypes->addElement($sloupec);
      }

      if ($TJRTypes->size() == 0) {
        $sloupec = new TJRTypesElement();
        $sloupec->sloupec = 1;
        $sloupec->popis = iconv('windows-1250', 'UTF-8', 'Všední den');
        $sloupec->vargrf = new Vector();
        $vargrfs = $typySloupcu[0];
        for ($i = 0; $i < count($vargrfs); $i++) {
          for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
            if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
              $sloupec->vargrf->addElement($ii);
              break;
            }
          }
        }
        $TJRTypes->addElement($sloupec);

        $sloupec = new TJRTypesElement();
        $sloupec->sloupec = 2;
        $sloupec->popis = iconv('windows-1250', 'UTF-8', 'Sobota');
        $sloupec->vargrf = new Vector();
        $vargrfs = $typySloupcu[1];
        for ($i = 0; $i < count($vargrfs); $i++) {
          for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
            if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
              $sloupec->vargrf->addElement($ii);
              break;
            }
          }
        }
        $TJRTypes->addElement($sloupec);

        $sloupec = new TJRTypesElement();
        $sloupec->sloupec = 3;
        $sloupec->popis = iconv('windows-1250', 'UTF-8', 'Nedìle, svátek');
        $sloupec->vargrf = new Vector();
        $vargrfs = $typySloupcu[2];
        for ($i = 0; $i < count($vargrfs); $i++) {
          for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
            if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
              $sloupec->vargrf->addElement($ii);
              break;
            }
          }
        }
        $TJRTypes->addElement($sloupec);
      }
    } else {
      $sql = "SELECT datum, pk FROM kalendar where datum = " . date_format(new DateTime($datumJR), 'Y-m-d') . " and idlocation = " . $location . " and packet = " . $packet . " order by pk";

      $result = mysql_query($sql);

      $sloupec = new TJRTypesElement();
      $sloupec->sloupec = 1;
      $sloupec->popis = date_format(new DateTime($datumJR), 'd') . ". " . iconv('windows-1250', 'UTF-8', $mesice[(integer) (date_format(new DateTime($datumJR), 'm') - 1)]) . " " . date_format(new DateTime($datumJR), 'Y');
      $sloupec->vargrf = new Vector();
      while ($row = mysql_fetch_row($result)) {
        $sloupec->vargrf->addElement($row[1]);
      }
      if ($sloupec->vargrf->size() == 0) {
        $vargrfs = $typyGrfDay[date_format(new DateTime($datumJR), 'D')];
        for ($i = 0; $i < count($vargrfs); $i++) {
          for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
            if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
              $sloupec->vargrf->addElement($ii);
              break;
            }
          }
        }
      }
      $TJRTypes->addElement($sloupec);
    }

    $sql = "select zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1 as pk1, zaslinky.pk2 as pk2, zaslinky.pk3 as pk3,
          zastavky.pk1 as pk4, zastavky.pk2 as pk5, zastavky.pk3 as pk6, zastavky.pk4 as pk7, zastavky.pk5 as pk8, zastavky.pk6 as pk9,
          zaslinky.a1_tarif, zaslinky.a2_tarif, zaslinky.b1_tarif, zaslinky.b2_tarif, st.stavi, (select ((sum(chronometr.doba_jizdy) div count(chronometr.doba_jizdy))) from chronometr where 
          chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $linka . "' 
          and chronometr.c_tarif = zaslinky.c_tarif and smer = " . $smer . ") as stavi 
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi 
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and idlocation = " . $location . " and 
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek, 
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $linka . "' and 
          idlocation = " . $location . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek, 
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $linka . "' and idlocation = " . $location . " and packet = " . $packet . " and 
          smer = " . $smer . " group by c_tarif, smer, chrono) dis group by c_tarif) st 
          on (zaslinky.c_tarif = st.c_tarif) 
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and 
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky) 
          where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $linka . "'";

    if ($smer == 0) {
      $sql = $sql . " order by zaslinky.c_tarif";
    } else {
      $sql = $sql . " order by zaslinky.c_tarif desc";
    }

    $result = mysql_query($sql);

    $Trasa = new Vector();

    while ($row = mysql_fetch_row($result)) {
      $TrasaElement = new TTrasaElement();
      $TrasaElement->Tarif = $row[0];
      $TrasaElement->Nazev = $row[1];

      $TrasaElement->poznamky = new Vector();
      for ($pki = 2; $pki < 11; $pki++) {
        if (($row[$pki] != 0) && ($TPoznamky->elementAt((integer) $row[$pki])->show != 0)) {
          if ((in_array((string) $row[$pki], $TPoznamkyZastavky->toArray(), FALSE) == FALSE) || ($TPoznamkyZastavky->isEmpty())) {
            $TPoznamkyZastavky->addElement((integer) $row[$pki]);
          }
          if ((in_array((string) $row[$pki], $TrasaElement->poznamky->toArray(), FALSE) == FALSE) || ($TrasaElement->poznamky->isEmpty())) {
            $TrasaElement->poznamky->addElement((integer) $row[$pki]);
          }
        }
      }
      $TrasaElement->PasmoA = $row[11];
      if ($row[12] != NULL) {
        $TrasaElement->PasmoA = $TrasaElement->PasmoA . ', ' . $row[12];
      }
      if (($row[11] != NULL) | ($row[12] != NULL)) {
        $hasPasmoA = TRUE;
      }
      $TrasaElement->PasmoB = $row[13];
      if ($row[14] != NULL) {
        $TrasaElement->PasmoB = $TrasaElement->PasmoB . ', ' . $row[14];
      }
      if (($row[13] != NULL) | ($row[14] != NULL)) {
        $hasPasmoB = TRUE;
      }
      if ($row[15] == 1) {
        $TrasaElement->stavi = True;
      } else {
        $TrasaElement->stavi = False;
      }

      $Trasa->addElement($TrasaElement);
    }

    if ($sdruzJR == FALSE) {
      for ($i = 0; $i < $TJRTypes->size(); $i++) {
        $sloupec = $TJRTypes->elementAt($i);

        $grf = getVargrf($sloupec);

        $sqlodjezd = "SELECT spoje.c_spoje, spoje.chrono,
                  (((zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) div 60) mod 24) AS HH,
                  mod( (
                  zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek
                  ), 60 ) AS MM, chronometr.doba_jizdy,
                  spoje.pk1, spoje.pk2, spoje.pk3, spoje.pk4, spoje.pk5, spoje.pk6, spoje.pk7, spoje.pk8, spoje.pk9, spoje.pk10,
                  case when zasspoje_pozn.pk1 is null then 0 else zasspoje_pozn.pk1 end as pk1,
                  case when zasspoje_pozn.pk2 is null then 0 else zasspoje_pozn.pk2 end as pk2,
                  case when zasspoje_pozn.dpk1 is null then 0 else zasspoje_pozn.dpk1 end as dpk1,
                  case when zasspoje_pozn.dpk2 is null then 0 else zasspoje_pozn.dpk2 end as dpk2,
                  case when zasspoje_pozn.dpk3 is null then 0 else zasspoje_pozn.dpk3 end as dpk3,
                  case when zasspoje_pozn.dpk4 is null then 0 else zasspoje_pozn.dpk4 end as dpk4,
                  case when zasspoje_pozn.dpk5 is null then 0 else zasspoje_pozn.dpk5 end as dpk5,
                  case when zasspoje_pozn.dpk6 is null then 0 else zasspoje_pozn.dpk6 end as dpk6,
                  case when zasspoje_pozn.dpk7 is null then 0 else zasspoje_pozn.dpk7 end as dpk7,
                  case when zasspoje_pozn.dpk8 is null then 0 else zasspoje_pozn.dpk8 end as dpk8,
                  case when zasspoje_pozn.dpk9 is null then 0 else zasspoje_pozn.dpk9 end as dpk9,
                  zasspoje.HH as pocatek_HH, zasspoje.MM as pocatek_MM,
                  spoje.kurz
                  FROM (
                  SELECT *
                  FROM spoje
                  WHERE spoje.c_linky = '" . $linka . "' " . "
                  AND spoje.smer = " . $smer . " and idlocation = " . $location . " and packet = " . $packet . "
                  AND (
                  (
                  spoje.pk1 in " . $grf . "
                  OR spoje.pk2 in " . $grf . "
                  OR spoje.pk3 in " . $grf . "
                  OR spoje.pk4 in " . $grf . "
                  OR spoje.pk5 in " . $grf . "
                  OR spoje.pk6 in " . $grf . "
                  OR spoje.pk7 in " . $grf . "
                  OR spoje.pk8 in " . $grf . "
                  OR spoje.pk9 in " . $grf . "
                  OR spoje.pk10 in " . $grf . "
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )
                  )
                  ) AS spoje
                  LEFT OUTER JOIN zasspoje ON ( spoje.c_linky = zasspoje.c_linky
                  AND spoje.c_spoje = zasspoje.c_spoje AND spoje.idlocation = " . $location . " AND zasspoje.idlocation = " . $location . " AND spoje.packet = " . $packet . " AND zasspoje.packet = " . $packet . ")
                  LEFT OUTER JOIN chronometr ON ( chronometr.c_linky = spoje.c_linky
                  AND chronometr.smer = spoje.smer
                  AND chronometr.chrono = spoje.chrono
                  AND chronometr.c_tarif = " . $tarif . " and chronometr.idlocation = " . $location . " AND chronometr.packet = " . $packet . ")
                  LEFT OUTER JOIN zasspoje_pozn ON ( spoje.c_linky = zasspoje_pozn.c_linky
                  AND spoje.c_spoje = zasspoje_pozn.c_spoje
                  AND zasspoje_pozn.c_tarif = " . $tarif . " and zasspoje.idlocation = " . $location . " AND zasspoje_pozn.packet = " . $packet . ")
                  WHERE NOT chronometr.doba_jizdy = -1 and (select (sum(doba_jizdy)/count(doba_jizdy)) from chronometr
                  where ( chronometr.c_linky = '" . $linka . "'
                  AND chronometr.smer = " . $smer . "
                  AND chronometr.chrono = spoje.chrono
                  AND idlocation = " . $location . " AND packet = " . $packet . "
                  AND ((chronometr.smer = 0 and chronometr.c_tarif > " . $tarif . ") or (chronometr.smer = 1 and chronometr.c_tarif < " . $tarif . ")))) <> -1                        
                  ORDER BY HH, MM";
        $sloupec->odjezdy = new Vector();
        for ($io = 0; $io < 24; $io++) {
          $TOdjezd = new Vector();
          $sloupec->odjezdy->addElement($TOdjezd);
        }

        $resultodjezdy = mysql_query($sqlodjezd);

        while ($rowo = mysql_fetch_row($resultodjezdy)) {
          $newOdjezd = new TOdjezdElement();
          $newOdjezd->chrono = $rowo[1];
          $newOdjezd->mm = $rowo[3];
          $newOdjezd->kurz = $rowo[28];
          $sloupec->odjezdy->elementAt($rowo[2])->addElement($newOdjezd);
          $sloupec->show = TRUE;

          $newOdjezd->poznamky = new Vector();
          for ($pki = 5; $pki <= 25; $pki++) {
            if (($rowo[$pki] != 0) && ($TPoznamky->elementAt((integer) $rowo[$pki])->show != 0)) {
              if ((in_array((string) $rowo[$pki], $TPoznamkySpoje->toArray(), FALSE) == FALSE) || ($TPoznamkySpoje->isEmpty())) {
                $TPoznamkySpoje->addElement((integer) $rowo[$pki]);
              }
              if ((in_array((string) $rowo[$pki], $newOdjezd->poznamky->toArray(), FALSE) == FALSE) || ($newOdjezd->poznamky->isEmpty())) {
                $newOdjezd->poznamky->addElement((integer) $rowo[$pki]);
              }
            }
          }
        }

        for ($maxrow = 0; $maxrow < 24; $maxrow++) {
          if ($sloupec->pocetsloupcu < $sloupec->odjezdy->elementAt($maxrow)->size()) {
            $sloupec->pocetsloupcu = $sloupec->odjezdy->elementAt($maxrow)->size();
          }
        }
      } //end for for sloupce
    } else {
      for ($i = 0; $i < $TJRTypes->size(); $i++) {
        $sloupec = $TJRTypes->elementAt($i);

        $grf = getVargrf($sloupec);

        $sqlodjezd = "SELECT spoje.c_spoje, spoje.c_tarif, spoje.chrono,
                  spoje.pk1, spoje.pk2, spoje.pk3, spoje.pk4, spoje.pk5, spoje.pk6, spoje.pk7, spoje.pk8, spoje.pk9, spoje.pk10,
                  spoje.HH, spoje.MM,
                  chronometr.doba_jizdy,
                  case 
                    when (chronometr.doba_jizdy = -1) then '--'
                    when ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24) < 10 then
                      concat('0', cast( ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24) as char ) )
                    else
                      ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24)
                  end as odjezdHH,
                  case 
                    when (chronometr.doba_jizdy = -1) then '--'
                    when (((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60) < 10 then
                      concat('0', cast((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60) as char))
                    else
                      (((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60)
                  end as odjezdMM,
                  spoje.kurz,
                  zaslinky.c_tarif as zaslinky_c_tarif,
                  chronometr.doba_pocatek,
                  chronometr.chrono,
                  chronometr.c_tarif
                  FROM (
                  SELECT *
                  FROM savvy_mhdspoje.spoje
                  WHERE spoje.c_linky = '" . $linka . "' " . "
                  AND spoje.smer = " . $smer . " and idlocation = " . $location . " and packet = " . $packet . "
                  AND (
                  (
                  spoje.pk1 in " . $grf . "
                  OR spoje.pk2 in " . $grf . "
                  OR spoje.pk3 in " . $grf . "
                  OR spoje.pk4 in " . $grf . "
                  OR spoje.pk5 in " . $grf . "
                  OR spoje.pk6 in " . $grf . "
                  OR spoje.pk7 in " . $grf . "
                  OR spoje.pk8 in " . $grf . "
                  OR spoje.pk9 in " . $grf . "
                  OR spoje.pk10 in " . $grf . "
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = " . $location . " and packet = " . $packet . "
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )
                  )
                  ) AS spoje
                  left outer join savvy_mhdspoje.zaslinky on (spoje.c_linky = zaslinky.c_linky and zaslinky.idlocation = spoje.idlocation and zaslinky.packet = spoje.packet)
                  left outer join savvy_mhdspoje.chronometr on (spoje.c_linky = chronometr.c_linky and spoje.smer = chronometr.smer
                  and spoje.chrono = chronometr.chrono and zaslinky.c_tarif = chronometr.c_tarif and chronometr.idlocation = spoje.idlocation and chronometr.packet = spoje.packet)
                  ORDER BY HH, MM, c_spoje, 
                  CASE " . $smer . " WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT " . $smer . " = 1 THEN zaslinky.c_tarif END";

        $sloupec->odjezdy = new Vector();

        $resultodjezdy = mysql_query($sqlodjezd);

        $startIndex = 0;
        while ($rowo = mysql_fetch_row($resultodjezdy)) {
          if (($startIndex % $Trasa->size()) == 0) {
            $odjezdSpoj = new TOdjezdElement();
            $odjezdSpoj->cspoje = $rowo[0];
            $odjezdSpoj->kurz = $rowo[18];
            $odjezdSpoj->odjezdytrasa = new Vector();
            $odjezdSpoj->poznamky = new Vector();
            for ($pki = 3; $pki <= 12; $pki++) {
              if ((in_array((string) $rowo[$pki], $TPoznamkySpoje->toArray(), FALSE) == FALSE) || ($TPoznamkySpoje->isEmpty())) {
                $TPoznamkySpoje->addElement((integer) $rowo[$pki]);
              }
              if ((in_array((string) $rowo[$pki], $odjezdSpoj->poznamky->toArray(), FALSE) == FALSE) || ($odjezdSpoj->poznamky->isEmpty())) {
                $odjezdSpoj->poznamky->addElement((integer) $rowo[$pki]);
              }
            }
            $sloupec->odjezdy->addElement($odjezdSpoj);
            $sloupec->show = TRUE;
          }

          $newOdjezdTrasa = new TOdjezdElement();
          $newOdjezdTrasa->hh = $rowo[16];
          $newOdjezdTrasa->mm = $rowo[17];
          $newOdjezdTrasa->ctarif = $rowo[19];
          $odjezdSpoj->odjezdytrasa->addElement($newOdjezdTrasa);

          $startIndex++;
        }
      } //end for for sloupce    
    }

    $sql = "SELECT nazev_linky, smerA, smerB, doprava FROM linky where c_linky = '" . $linka . "' and idlocation = " . $location . " and packet = " . $packet;

    $result = mysql_query($sql);

    $Linka = new TLinka();
    $Linka->idlinky = $linka;

    while ($row = mysql_fetch_row($result)) {
      $Linka->nazev = $row[0];
      if ($row[1] == NULL) {
        $Linka->smerA = $Trasa->lastElement()->Nazev;
      } else {
        $Linka->smerA = $row[1];
      }
      if ($row[2] == NULL) {
        $Linka->smerB = $Trasa->lastElement()->Nazev;
      } else {
        $Linka->smerB = $row[2];
      }
      $Linka->doprava = $row[3];
    }

    if ((is_null($x) == TRUE) || (is_null($y) == TRUE) || (is_null($jrtype) == TRUE)) {
      if (($denniJR == FALSE) && ($sdruzJR == FALSE)) {
        getActualJRType($actualJRType, $nextJRType, $TJRTypes, $location, $packet, $typyGrf);
        getNejblizsiOdjezd($TJRTypes, $x, $y, $actualJRType, TRUE);
        $jrtype = $actualJRType;
        if ((is_null($x) == TRUE) || (is_null($y) == TRUE)) {
          getNejblizsiOdjezd($TJRTypes, $x, $y, $nextJRType, FALSE);
          $jrtype = $nextJRType;
        }
      } else {
        $jrtype = 1;
      }
    }

    if ((is_null($x) == FALSE) && (is_null($y) == FALSE) && (is_null($jrtype) == FALSE) && ($sdruzJR == FALSE)) {
      $sql = "select chronometr.chrono, chronometr.c_zastavky, chronometr.c_tarif,
                    chronometr.doba_jizdy, chronometr.doba_pocatek
                    from chronometr
                    where chronometr.c_linky = '" . $linka . "' and smer = " . $smer . " and 
                    chrono = " . $TJRTypes->elementAt($jrtype - 1)->odjezdy->elementAt($y)->elementAt($x)->chrono . " and idlocation = " . $location . " and packet = " . $packet;
      if ($smer == 0) {
        $sql = $sql . " order by chronometr.chrono, chronometr.c_tarif";
      } else {
        $sql = $sql . " order by chronometr.chrono, chronometr.c_tarif desc";
      }

      $result = mysql_query($sql);

      while ($row = mysql_fetch_row($result)) {
        $chrono = new TChronoElement();
        $chrono->c_zastavky = $row[1];
        $chrono->c_tarif = $row[2];
        $chrono->doba_jizdy = $row[3];
        $chrono->doba_pocatek = $row[4];
        if ($TChrono->elementAt((integer) $row[0] - 1) == NIL) {
          $chronometr = new TChronometr();
          $chronometr->chrono = new Vector();
          $TChrono->addElementAt($chronometr, (integer) $row[0] - 1);
        }
        $chronometr = $TChrono->elementAt((integer) $row[0] - 1);
        $chronometr->chrono->addElement($chrono);
      }
      $Trasa->lastElement()->stavi = FALSE;
    } else {
      
    }

    $res = $res . getJRDiv($Linka, $Trasa, $TChrono, $tarif, $smer, ($smer == 0) ? $hasPasmoA : $hasPasmoB, $TPoznamky, $TJRTypes, $location, $packet, $jrtype, $x, $y, $TPoznamkyZastavky, $TPoznamkySpoje, $denniJR, $datumJR, $sdruzJR, $layout);

    mysql_close($p);

    echo $res;
  }
}

if ($sel > -1) {
/*    echo "<script>";
    echo "document.getElementById('jr" . $sel . "').style.color = '#00ff00';";
    //className = 'cell_time_jr_zahlavi_active'
    echo "</script>"; */
  load();
} else {
  load();
}
?>
