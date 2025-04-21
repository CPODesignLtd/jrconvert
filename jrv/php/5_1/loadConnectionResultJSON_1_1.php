<?php
error_reporting(0);
require_once '../../lib/param.php';
require_once '../../lib/functions.php';

$startmil = (microtime(true) * 1000);

$pocatek = $_GET['pocatek'];
$cil = $_GET['cil'];
$location = $_GET['location'];
$packet = $_GET['packet'];
$H = $_GET['h'];
$M = $_GET['m'];
if (isset($_GET['PP'])) {
    $pp = $_GET['PP'];
} else {
    $pp = 5;
}
if (isset($_GET['dobaS'])) {
    $maxcas = $_GET['dobaS'];
} else {
    $maxcas = 120;
}
if (isset($_GET['minP'])) {
    $minprestup = $_GET['minP'];
} else {
    $minprestup = 1;
}

if (($location == 11) || ($location == 17)) {
    $minprestup = 0;
}

$plus_min_prestup_pesi_hrana = 0;

if (isset($_GET['maxP'])) {
    $maxprestup = $_GET['maxP'];
} else {
    $maxprestup = 60;
}

if (isset($_GET['datum'])) {
    $dob1 = trim($_GET['datum']);
    list($param_day, $param_month, $param_year) = explode('_', $dob1);
    $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
    $datumJR = date('Y-m-d', $mk);
} else {
    $datumJR = date('Y-m-d');
}

if (isset($_GET['prime'])) {
    $prime = $_GET['prime'];
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
    var $HH = null;
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
    var $od_zastavky = null;
    var $od_tarif = null;
    var $do_zastavky;
    var $do_tarif = null;
    var $doba = null;
    var $vzdalenost = null;

    public function create($_c_linky,$_od_zastavky, $_do_zastavky, $_doba, $_vzdalenost) {
        $this->c_linky = $_c_linky;
        $this->od_zastavky = $_od_zastavky;
        $this->do_zastavky = $_do_zastavky;
        $this->doba = $_doba;
        $this->vzdalenost = $_vzdalenost;
    }

}

$CHRONO = null; //[c_linky][smer][chrono][c_tarif]
$SPOJE = null; //[c_linky][smer][HH]
$HRANY = null; //[do_zastavky]
$ZASTAVKY_LINKY = null; //[c_zastavky][c_linky][c_tarif]TZastavkaItem
$LINKY_ZASTAVKY = null; //[c_linky][c_zastavky][c_tarif]TZastavkaItem
$PESOBUS_ZASTAVKY = null; //[od_zastavky][do_zastavky]TZastavkaItem
$PRESTUPY = null;
$PRESTUPY_CACHE = null;
$PRESTUPY_NEW = null;
$PRESTUPY_CACHE_NEW = null;
$LINKY = null;
$ZASTAVKY = null;

$SpojeniBody = null;
$myarraylist = null;
$pocet = 0;
$pocet1 = 0;
$pocet2 = 0;
$pocet4 = 0;
$pocet5 = 0;
$pocetprestupu = 0;
$cas1 = 0;
$casmin = 0;
$casspoje = 0;
$uspech = 0;
$neuspech = 0;
$uspechcas = 0;
$neuspechcas = 0;

global $con_server;
global $con_db;
global $con_pass;

$mysqli = new mysqli($con_server, $con_db, $con_pass, 'savvy_mhdspoje');
$mysqli->set_charset("utf8");
$mysqli1 = new mysqli($con_server, $con_db, $con_pass, 'savvy_mhdspoje');
$sql = "SELECT distinct sum((SELECT bcode(" . $location . ", " . $packet . ", pk))) as code FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
$result = $mysqli->query($sql);

$row = $result->fetch_row();
$varGRF = $row[0];

function getLINKA($c_linky) {
    global $LINKY;
    global $location;
    global $packet;

    $res = null;

    if ($LINKY[$c_linky] == null) {
        if ($c_linky != -1) {
            global $mysqli;

            $sql = "select linky.c_linky, linky.nazev_linky, linky.doprava from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = \"" . $c_linky . "\"";
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
        global $mysqli;

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
    global $pocet1;
    global $pocet2;
    global $cas1;

//  $pocet = 0;

    $zac = (microtime(true) * 1000);
    if ($PRESTUPY_CACHE[$od_zastavky] != null) {
        $PRESTUPY = $PRESTUPY_CACHE[$od_zastavky];
        $pocet1++;
    } else {
        $PRESTUPY = null;

        //zapracovani pesich tras

        $pesobus = $PESOBUS_ZASTAVKY[$od_zastavky];

        if (count($pesobus) > 0) {
            foreach ($pesobus as $key_do_zastavky => $val) {
                if ($key_do_zastavky != $cil) {
                    $PRESTUPY[$val->c_linky . "_" . $val->c_tarif . "_" . $key_do_zastavky . "_" . $val->c_tarif] = $val;
                }
            }
        }

        //end zapracovani pesich tras

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
                                }
                            }
                        }
                    }
                }
            }
        }

        $PRESTUPY_CACHE[$od_zastavky] = $PRESTUPY;
        $pocet++;
    }
    $pocet2++;
    $kon = (microtime(true) * 1000);
    $cas1 = $cas1 + ($kon - $zac);
}

function getPrujezdy_new($od_zastavky, $cil) {
    global $ZASTAVKY_LINKY;
    global $LINKY_ZASTAVKY;
    global $PRESTUPY_NEW;
    global $PRESTUPY_CACHE_NEW;
    global $PESOBUS_ZASTAVKY;
    global $pocet;
    global $pocet1;
    global $pocet2;
    global $cas1;

    $zac = (microtime(true) * 1000);
    if ($PRESTUPY_CACHE_NEW[$od_zastavky] != null) {
        $PRESTUPY_NEW = $PRESTUPY_CACHE_NEW[$od_zastavky];
        $pocet1++;
//        echo ' Z CACHE <br><br>';
    } else {
        $PRESTUPY_NEW = null;

        //zapracovani pesich tras

        $pesobus = $PESOBUS_ZASTAVKY[$od_zastavky];

        if (count($pesobus) > 0) {
            foreach ($pesobus as $key_do_zastavky => $val) {
                if ($key_do_zastavky != $cil) {
                    $PRESTUPY_NEW[-1][0][count($PRESTUPY_NEW[-1][0])] = $val;
//                    echo $val->doba.'<br>';
                }
            }
        }

        //end zapracovani pesich tras

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
                                if ((($smer == 0) && ($zastavka->A == 1) && ($zastavka1->A == 1)) || (($smer == 1) && ($zastavka->B == 1) && ($zastavka1->B == 1))) {
//                                    if ($PRESTUPY_NEW[$zastavka->c_linky . "_" . $zastavka->c_tarif . "_" . $l_key_c_zastavky . "_" . $zastavka1->c_tarif] == null) {
                                    $prestup = new TPrestup();
                                    $prestup->c_linky = $zastavka->c_linky;
                                    $prestup->od_tarif = $zastavka->c_tarif;
                                    $prestup->do_zastavky = $l_key_c_zastavky;
                                    $prestup->do_tarif = $zastavka1->c_tarif;
                                    $PRESTUPY_NEW[$zastavka->c_linky][$zastavka->c_tarif][count($PRESTUPY_NEW[$zastavka->c_linky][$zastavka->c_tarif])] = $prestup;
//                $pocet++;
//                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $PRESTUPY_CACHE_NEW[$od_zastavky] = $PRESTUPY_NEW;
        $pocet++;
    }
    $pocet2++;
    $kon = (microtime(true) * 1000);
    $cas1 = $cas1 + ($kon - $zac);
}

function getSpoje_new($c_linky, $od_zastavky, $od_tarif, $cas, $pocatek, $linka_pred) {
    global $SPOJE;
    global $CHRONO;
    global $minprestup;
    global $plus_min_prestup_pesi_hrana;
    global $maxprestup;
    global $maxcas;

    $res = null;

    $odHH = (int) (($cas - 60) / 60);
    $doHH = (int) ((($cas + $maxcas) / 60));

    for ($smer = 0; $smer <= 1; $smer++) {

        $konec = false;
        while (($odHH <= $doHH) && ($konec == false)) {
            if ($SPOJE[$c_linky][$smer][$odHH] != null) {
                for ($i = 0; $i < count($SPOJE[$c_linky][$smer][$odHH]); $i++) {
                    $pomSpojItem = $SPOJE[$c_linky][$smer][$odHH][$i];
                    if ($CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif] != null) {
                        $startCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
                        if ((($startCAS - $cas <= $maxprestup) && ($startCAS - $cas >= (($linka_pred != -1) ? $minprestup : $plus_min_prestup_pesi_hrana))) || (($od_zastavky == $pocatek) && ($startCAS >= $cas))) {
//                            echo 'linka : ' . $c_linky . ' -> zast ' . $od_zastavky . ' tarif ' . $od_tarif . ' -> ' . $startCAS . ' (' . $cas . ') smer = ' . $smer . ' <br>';
                            $res[$smer] = $pomSpojItem;
                            $konec = true;
                            break;
                        }
                    }
                }
            }
            $odHH++;
        }
    }

    return $res;
}

function getSpojeODDO_new($odZastavky, $pocatek, $cil, $cas, $linka_pred) {
    global $SPOJE;
    global $CHRONO;
    global $HRANY;
    global $PRESTUPY;
    global $PRESTUPY_NEW;
    global $PESOBUS_ZASTAVKY;
    global $minprestup;
    global $plus_min_prestup_pesi_hrana;
    global $maxprestup;
    global $maxcas;
    global $location;
    global $packet;
    global $casspoje;
    global $uspech;
    global $neuspech;
    global $uspechcas;
    global $neuspechcas;

    $HRANY = null;
//    getPrujezdy($odZastavky, $cil);

    getPrujezdy_new($odZastavky, $cil);

    foreach ($PRESTUPY_NEW as $key_c_linky => $value) {
//        echo 'klic linka ' . $key_c_linky . '<br>';
        foreach ($value as $key_od_tarif => $value1) {
//            echo 'volam spoje' . $key_od_tarif . '<br>';
            $chronospoj = getSpoje_new($key_c_linky, $odZastavky, $key_od_tarif, $cas, $pocatek, $linka_pred);

            if ($key_c_linky == -1) {
//                echo 'mam linku -1<br>';
                for ($i = 0; $i < count($PRESTUPY_NEW[$key_c_linky][$key_od_tarif]); $i++) {
                if ($HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky] == null) {
//                    echo $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky.'<br>';
//                    echo $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i].'<br>';
//                    echo $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->od_zastavky.' - '.$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky.' + '.$i.'<br>';
//                    echo $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->$doba.'<br>';
                    $pomSpoj = new TSpoj();
                    $pomSpoj->c_linky = -1;
                    $pomSpoj->od_zastavky = $odZastavky;
                    $pomSpoj->do_zastavky = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky;
                    $pomSpoj->od_tarif = $key_od_tarif;
                    $pomSpoj->do_tarif = 0;
                    $pomSpoj->smer = $smer;
                    $pomSpoj->startCAS = $cas;
                    $pomSpoj->endCAS = $cas + $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->doba;
                    $pomSpoj->doba_jizdy = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->doba;
                    $pomSpoj->c_spoje = -1;
                    $HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky] = $pomSpoj;
                } else {
                    $pomSpoj = $HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky];
                    if ($pomSpoj->doba_jizdy > $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->doba) {
                        $pomSpoj->c_linky = -1;
                        $pomSpoj->od_zastavky = $odZastavky;
                        $pomSpoj->do_zastavky = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky;
                        $pomSpoj->od_tarif = $key_od_tarif;
                        $pomSpoj->do_tarif = 0;
                        $pomSpoj->smer = $smer;
                        $pomSpoj->startCAS = $cas;
                        $pomSpoj->endCAS = $cas + $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->doba;
                        $pomSpoj->doba_jizdy = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->doba;
                        $pomSpoj->c_spoje = -1;
                    }
                }
                }
            } else {

//            for ($i = 0; $i < count($PRESTUPY_NEW[$key_c_linky][$key_od_tarif]); $i++) {
                $startCAS0 = ($chronospoj[0]->HH * 60 + $chronospoj[0]->MM);
                $startCAS1 = ($chronospoj[1]->HH * 60 + $chronospoj[1]->MM);
//            echo $chronospoj . ' | ' . $startCAS0 . ' , ' . $startCAS1 . '<br>';
                for ($i = 0; $i < count($PRESTUPY_NEW[$key_c_linky][$key_od_tarif]); $i++) {
                    if ($chronospoj != null) {
                        $prijezd = -1;
                        $smer = -1;
                        if (($chronospoj[0] != null) && ($CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif] > 0) && ($key_od_tarif < $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif)) {
                            $prijezd = $startCAS0 + $CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif];
                            $smer = 0;
                        }
                        if (($chronospoj[1] != null) && ($CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif] > 0) && ($key_od_tarif > $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif)) {
                            $prijezd = $startCAS1 + $CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif];
                            $smer = 1;
                        }
                        if ($prijezd > -1) {
                            if ($HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky] == null) {
                                $pomSpoj = new TSpoj();
                                $pomSpoj->c_linky = $key_c_linky;
                                $pomSpoj->od_zastavky = $odZastavky;
                                $pomSpoj->do_zastavky = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky;
                                $pomSpoj->od_tarif = $key_od_tarif;
                                $pomSpoj->do_tarif = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif;
                                $pomSpoj->smer = $smer;
                                $pomSpoj->startCAS = ($smer == 0) ? ($startCAS0 + $CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->od_tarif]) : ($startCAS1 + $CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->od_tarif]);
                                $pomSpoj->endCAS = $prijezd;
                                $pomSpoj->doba_jizdy = abs($pomSpoj->endCAS - $pomSpoj->startCAS);
//                                $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
                                $HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky] = $pomSpoj;
//                            echo $odZastavky.' , '.
//                                        $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky.' , '.
//                                        $key_od_tarif.' , '.
                                $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif . ' | ' . $pomSpoj->startCAS . '<br>';
                            } else {
                                $pomSpoj = $HRANY[$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky];
                                if ($pomSpoj->endCAS > $prijezd) {
                                    $pomSpoj->c_linky = $key_c_linky;
                                    $pomSpoj->od_zastavky = $odZastavky;
                                    $pomSpoj->do_zastavky = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky;
                                    $pomSpoj->od_tarif = $key_od_tarif;
                                    $pomSpoj->do_tarif = $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif;
                                    $pomSpoj->smer = $smer;
//                                echo 'modify hrany - '.$pomSpoj->endCAS.' -> '.$prijezd.' | '.$pomSpoj->doba_jizdy.' -> '.abs($pomSpoj->endCAS - $pomSpoj->startCAS).'<br>';
                                    $pomSpoj->startCAS = ($smer == 0) ? ($startCAS0 + $CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->od_tarif]) : ($startCAS1 + $CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->od_tarif]);
                                    $pomSpoj->endCAS = $prijezd;
                                    $pomSpoj->doba_jizdy = abs($pomSpoj->endCAS - $pomSpoj->startCAS);
//                                    $pomSpoj->c_spoje = $pomSpojItem->c_spoje;
                                }
                            }
//                        echo 'do zastavky ' . $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky . ' | ' . $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif . ' ( ' . $prijezd . ' )<br>';
                        }
                    }
                    /*                if ($HRANY[$do_zastavky] == null) {
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
                      $konec = true;
                      } else {
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
                      $konec = true;
                      }
                      } */
                }
//            }
            }
        }
    }
//    echo '<br><br>';
//    echo '----------------------<br>';
//    echo count($PRESTUPY).'|'.($pocet_prujezdu).'-'.count($HRANY).'<br>';
//    echo '----------------------<br>';
}

function getSpojeODDO($odZastavky, $pocatek, $cil, $cas, $linka_pred) {
    global $SPOJE;
    global $CHRONO;
    global $HRANY;
    global $PRESTUPY;
    global $PRESTUPY_NEW;
    global $PESOBUS_ZASTAVKY;
    global $minprestup;
    global $plus_min_prestup_pesi_hrana;
    global $maxprestup;
    global $maxcas;
    global $location;
    global $packet;
    global $casspoje;
    global $uspech;
    global $neuspech;
    global $uspechcas;
    global $neuspechcas;
    //$pocet1 = 0;

    $HRANY = null;
    getPrujezdy($odZastavky, $cil);

    getPrujezdy_new($odZastavky, $cil);

    $pocet_prujezdu = 0;
    foreach ($PRESTUPY_NEW as $key_c_linky => $value) {
        echo 'klic linka ' . $key_c_linky . '<br>';
        foreach ($value as $key_od_tarif => $value1) {
            echo 'volam spoje' . $key_od_tarif . '<br>';
            $chronospoj = getSpoje_new($key_c_linky, $odZastavky, $key_od_tarif, $cas, $pocatek, $linka_pred);
            for ($i = 0; $i <= 1; $i++) {
                if ($chronospoj[$i] != null) {
                    echo $i . '. ' . $chronospoj[$i]->chrono . '<br>';
                    echo ($chronospoj[$i]->HH * 60 + $chronospoj[$i]->MM + $CHRONO[$key_c_linky][$i][$chronospoj[$i]->chrono][$key_od_tarif]) . '<br>';
                }
            }
            $startCAS0 = ($chronospoj[0]->HH * 60 + $chronospoj[0]->MM);
            $startCAS1 = ($chronospoj[1]->HH * 60 + $chronospoj[1]->MM);
            echo $chronospoj . ' | ' . $startCAS0 . ' , ' . $startCAS1 . '<br>';
            for ($i = 0; $i < count($PRESTUPY_NEW[$key_c_linky][$key_od_tarif]); $i++) {
                $pocet_prujezdu++;
                if ($chronospoj != null) {
                    $prijezd = -1;
                    if (($chronospoj[0] != null) && ($CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif] > 0) && ($key_od_tarif < $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif)) {
                        $prijezd = $startCAS0 + $CHRONO[$key_c_linky][0][$chronospoj[0]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif];
                    }
                    if (($chronospoj[1] != null) && ($CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif] > 0) && ($key_od_tarif > $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif)) {
                        $prijezd = $startCAS1 + $CHRONO[$key_c_linky][1][$chronospoj[1]->chrono][$PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif];
                    }
                    echo 'do zastavky ' . $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_zastavky . ' | ' . $PRESTUPY_NEW[$key_c_linky][$key_od_tarif][$i]->do_tarif . ' ( ' . $prijezd . ' )<br>';
                }
            }
        }
    }

//    echo '<br> od zastavky ' . $odZastavky . ' linka pred ' . $linka_pred . '<br>';
    $zac = (microtime(true) * 1000);
    if (count($PRESTUPY) > 0) {
        foreach ($PRESTUPY as $key_prestup => $value) {

//                echo $key_prestup . '<br>';

            $row[0] = $value->c_linky;
            $row[1] = $value->od_tarif;
            $row[2] = $value->do_zastavky;
            $row[3] = $value->do_tarif;
            $row[4] = $value->doba;

//            echo 'linka = ' . $value->c_linky . ' (' . $value->do_zastavky . ') ' . $value->doba . '|';

            $smer = $row[1] - $row[3]; //odtarif - dotarif
            if ($smer <= 0) {
                $smer = 0;
            } else {
                $smer = 1;
            }

            $konec = false;

            $odHH = (int) (($cas - 60) / 60);
            $doHH = (int) ((($cas + $maxcas) / 60));
            $c_linky = $row[0];
            $od_zastavky = $odZastavky;
            $do_zastavky = $row[2];
            $od_tarif = $row[1];
            $do_tarif = $row[3];
            $doba_pesobus = $row[4];

            if ($c_linky == -1) {
                if ($HRANY[$do_zastavky] == null) {
                    $pomSpoj = new TSpoj();
                    $pomSpoj->c_linky = $c_linky;
                    $pomSpoj->od_zastavky = $od_zastavky;
                    $pomSpoj->do_zastavky = $do_zastavky;
                    $pomSpoj->od_tarif = $od_tarif;
                    $pomSpoj->do_tarif = $do_tarif;
                    $pomSpoj->smer = $smer;
                    $pomSpoj->startCAS = $cas;
                    $pomSpoj->endCAS = $cas + $doba_pesobus;
                    $pomSpoj->doba_jizdy = $doba_pesobus;
                    $pomSpoj->c_spoje = -1;
                    $HRANY[$do_zastavky] = $pomSpoj;
                } else {
                    $pomSpoj = $HRANY[$do_zastavky];
                    if ($pomSpoj->endCAS > $endCAS) {
                        $pomSpoj->c_linky = $c_linky;
                        $pomSpoj->od_zastavky = $od_zastavky;
                        $pomSpoj->do_zastavky = $do_zastavky;
                        $pomSpoj->od_tarif = $od_tarif;
                        $pomSpoj->do_tarif = $do_tarif;
                        $pomSpoj->smer = $smer;
                        $pomSpoj->startCAS = $cas;
                        $pomSpoj->endCAS = $cas + $doba_pesobus;
                        $pomSpoj->doba_jizdy = $doba_pesobus;
                        $pomSpoj->c_spoje = -1;
                    }
                }
            } else {
                while (($odHH <= $doHH) && ($konec == false)) {
                    if ($SPOJE[$c_linky][$smer][$odHH] != null) {
                        for ($i = 0; $i < count($SPOJE[$c_linky][$smer][$odHH]); $i++) {
                            $pomSpojItem = $SPOJE[$c_linky][$smer][$odHH][$i];
                            if (($CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif] != null) && ($CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif] != null)) {
                                $startCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
                                $endCAS = $odHH * 60 + $pomSpojItem->MM + $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif];
                                $doba_jizdy = $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$do_tarif] - $CHRONO[$c_linky][$smer][$pomSpojItem->chrono][$od_tarif];
//echo ' start cas ' . $startCAS . ' - > ' . $endCAS . ' ('.$cas.')<br>';
                                if ((($startCAS - $cas <= $maxprestup) && ($startCAS - $cas >= (($linka_pred != -1) ? $minprestup : $plus_min_prestup_pesi_hrana))) || (($od_zastavky == $pocatek) && ($startCAS >= $cas))) {
                                    $uspech++;
                                    if ($HRANY[$do_zastavky] == null) {
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
                                        echo $od_zastavky . ' , ' .
                                        $do_zastavky . ' , ' .
                                        $od_tarif . ' , ' .
                                        $do_tarif . ' | ' . $startCAS . '<br>';
                                        $konec = true;
                                    } else {
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
                                            $konec = true;
                                        }
                                    }
//                                    echo 'zapsana hrana > '.$startCAS.' + chrono = ' . $pomSpojItem->chrono . '<br>';
//                                    break;
                                    $uspechcas = $uspechcas + ((microtime(true) * 1000) - $zac);
                                }
                                $neuspech++;
                                $neuspechcas = $neuspechcas + ((microtime(true) * 1000) - $zac);
                            }
                        }
                    }
                    $odHH++;
                }
            }
//            echo '<br>';
        }
        echo '<br><br>';
    }
    echo '----------------------<br>';
    echo count($PRESTUPY) . '|' . ($pocet_prujezdu) . '-' . count($HRANY) . '<br>';
    echo '----------------------<br>';
    $kon = (microtime(true) * 1000);
    $casspoje = $casspoje + ($kon - $zac);
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
    global $pocet4;
    global $casmin;

    $zacmin = (microtime(true) * 1000);
    $res = null;
    $min = PHP_INT_MAX;
    $pom = $SpojeniBody->BodyListActive;
    foreach ($pom as $key_c_zastavky => $val) {
        $pomZastavka = $val;

        if (($pomZastavka->vaha <= $min)/* && ($pomZastavka->pocet_prestupu < 3) */) {
            $min = $pomZastavka->vaha;
            $res = $pomZastavka;
        }
    }
    if ($res != null) {
        moveActiveToInActive($res->id_zastavky);
    }
    $konmin = (microtime(true) * 1000);
    $casmin = $casmin + ($konmin - $zacmin);
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
    global $pocet5;

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
        addActive($start);

        $bod = getSpojeniMinimum(/* SpojeniBody */);  //TZastavka

        while (($bod != null) && ($bod->id_zastavky != $do_zastavky)) {
            getSpojeODDO_new($bod->id_zastavky, $od_zastavky, $do_zastavky, $bod->prijezd, $bod->Hrana->c_linky);

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
                        if ((getActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null)/* && (getInActiveZastavkaById($pomHrana->ZastavkaDO->id_zastavky) == null) */) {
                            $pomZastavka = new TZastavka();
                            $pomZastavka->id_zastavky = $pomHrana->ZastavkaDO->id_zastavky;
                            addActive($pomZastavka);
                            $pocet5++;
//                            }
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

            if ((count($myarraylist[$cislo_spoje]->PartSpoje) > 0) && ($myarraylist[$cislo_spoje]->PartSpoje[0]->c_linky == $bod->Hrana->c_linky) && ($myarraylist[$cislo_spoje]->PartSpoje[0]->smer == $bod->Hrana->id_smeru)) {
                $obj = $myarraylist[$cislo_spoje]->PartSpoje[0];
            } else {
                $obj = new TPartSpojeni();
                for ($i = count($myarraylist[$cislo_spoje]->PartSpoje) - 1; $i >= 0; $i--) {
                    $pomobj = $myarraylist[$cislo_spoje]->PartSpoje[$i];
                    $myarraylist[$cislo_spoje]->PartSpoje[$i + 1] = $pomobj;
                }
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
        if ($pocet_spojeni > 5) {
            $konec = true;
        }
    }

    $pommyarraylist = null;
    for ($i = 0; $i < count($myarraylist); $i++) {
        if ($myarraylist[$i]->cislo_spojeni <= 5) {
            $pommyarraylist[$myarraylist[$i]->cislo_spojeni - 1] = $myarraylist[$i];
        }
    }
    $myarraylist = $pommyarraylist;
}

$startnacet = (microtime(true) * 1000);

$sql = "select distinct spoje.c_linky, smer, HH, MM, chrono, c_spoje from linky left outer join spoje on (spoje.c_linky = linky.c_linky) where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and ((spoje.kodpozn & " . $varGRF . ") > 0 or spoje.kodpozn = 0)  AND spoje.voz = 1 and linky.jr_od <= '" . $datumJR . "' and linky.jr_do >= '" . $datumJR . "'";
/* select distinct spoje.c_linky, smer, HH, MM, chrono, c_spoje from spoje
  left outer join linky on (spoje.c_linky = linky.c_linky)
  where spoje.idlocation = 17 and spoje.packet = 8 and ((spoje.kodpozn & 1) > 0 or spoje.kodpozn = 0)  AND spoje.voz = 1 and linky.jr_od <= '2019-11-9' and linky.jr_do >= '2019-11-9' */
if ($location != 6) {
    $sql = $sql . " AND (spoje.vlastnosti & 2048) <> 2048";
}

$result = $mysqli->query($sql);

$iter = 0;
while ($row = $result->fetch_row()) {
    $SpojItem = new TSpojItem();
    $SpojItem->MM = $row[3];
    $SpojItem->HH = $row[2];
    $SpojItem->chrono = $row[4];
    $SpojItem->c_spoje = $row[5];
    $SPOJE[$row[0]][$row[1]][$row[2]][count($SPOJE[$row[0]][$row[1]][$row[2]])] = $SpojItem;
}

/* $sql = "select chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.c_tarif, chronometr.doba_pocatek, chronometr.c_zastavky, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B from chronometr
  left outer join zaslinky
  on (zaslinky.idlocation = chronometr.idlocation and zaslinky.packet = chronometr.packet and zaslinky.c_linky = chronometr.c_linky and zaslinky.c_tarif = chronometr.c_tarif)
  where chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . "  and chronometr.doba_jizdy > -1"; */
$sql = "select chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.c_tarif, chronometr.doba_pocatek, chronometr.c_zastavky, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B from linky
left outer join chronometr on (linky.c_linky = chronometr.c_linky)  left outer join zaslinky
on (zaslinky.idlocation = chronometr.idlocation and zaslinky.packet = chronometr.packet and zaslinky.c_linky = chronometr.c_linky and zaslinky.c_tarif = chronometr.c_tarif)
where chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . "  and chronometr.doba_jizdy > -1 and linky.jr_od <= '" . $datumJR . "' and linky.jr_do >= '" . $datumJR . "' order by chronometr.c_tarif";

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
}

if ($location == 17) {
    $sql = "select (select c_zastavky from zastavky where idlocation = " . $location . " and packet = " . $packet . " and passport = od_zastavky), (select c_zastavky from zastavky where idlocation = " . $location . " and packet = " . $packet . " and passport = do_zastavky), cas, vzdalenost from pesobus
            where pesobus.idlocation = " . $location . " and pesobus.packet = " . $packet;
} else {
    $sql = "select od_zastavky, do_zastavky, cas, vzdalenost from pesobus
            where pesobus.idlocation = " . $location . " and pesobus.packet = " . $packet;
}
$result = $mysqli->query($sql);

while ($row = $result->fetch_row()) {
    if (($row[0] != '') && ($row[1] != '')) {
        $pPrestup = new TPrestup();
        $pPrestup->create(-1, $row[0], $row[1], $row[2], $row[3]);
        $PESOBUS_ZASTAVKY[$row[0]][$row[1]] = $pPrestup;
    }
}

$konecnacet = (microtime(true) * 1000);

getSpojeni($pocatek, $cil, $H * 60 + $M);

$konecmil = (microtime(true) * 1000);

$res = '';

$res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
$res = $res . "<div id='movedivSeznam' class='movediv'>";
$res = $res . "<a>" . $pocet . "</a><a>|" . $pocetprestupu . "<a></a>|" . $pocet1 . "</a><a>|" . $pocet2 . "</a><a>|" . $pocet4 . "</a><a>|" . $pocet5 . "</a><a>|" . $varGRF . "</a><a>|" . $cas1 . "</a><a>|" . ($konecmil - $startmil) . "</a><a>|" . ($konecnacet - $startnacet) . "</a><a>|" . $casmin . "</a><a>|" . $casspoje . "</a><a>|" . $uspech . "</a><a>|" . $neuspech . "</a><a>|" . $uspechcas . "</a><a>|" . $neuspechcas . "</a>";
$res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
$res = $res . "</div></br>";
$res = $res . "<table id='tablejrSeznam' class = 'tablejr' >";

if (($prime == 1)) {
    $pommyarraylist = null;
    $ii = 0;
    for ($i = 0; $i < count($myarraylist); $i++) {
        if (count($myarraylist[$i]->PartSpoje) == 1) {
            $pommyarraylist[$ii] = $myarraylist[$i];
            $ii++;
        }
    }
    $myarraylist = $pommyarraylist;
} else {
    $pommyarraylist = null;
    $ii = 0;
    for ($i = 0; $i < count($myarraylist); $i++) {
        if (count($myarraylist[$i]->PartSpoje) <= $pp) {
            $pommyarraylist[$ii] = $myarraylist[$i];
            $ii++;
        }
    }
    $myarraylist = $pommyarraylist;
}

$resmap = null;

if (count($myarraylist) > 0) {

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

    for ($i = 0; $i < count($myarraylist); $i++) {
        $val1 = $myarraylist[$i]->PartSpoje[0];
        if (1 == 1) {
            if ($location == 17) {
                $res = $res . "<tr><td colspan='6'><div>" . $day . '.' . $month . '.' . $year . "&nbsp;&nbsp;" . $val1->text_odjezd . "</div></td></tr>"; //new
            } else {
                $res = $res . "<tr><td colspan='7'><div style='margin-top: 20px; border-bottom: 1px solid #dadada;'></div></td></tr>"; //new
            }
            $res = $res . "<tr class = 'spojrowheader'>";

            if ($location == 17) {
                $res = $res . "<th style='text-align: center;'>" . iconv('UTF-8', 'UTF-8', $rs_spoj);
                $res = $res . "<th style='text-align: center;'>" . iconv('UTF-8', 'UTF-8', $rs_typ);
                $res = $res . "<th style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', $rs_zastavka);
                $res = $res . "<th style='text-align: center;'>" . iconv('UTF-8', 'UTF-8', $rs_prijezd);
                $res = $res . "<th style='text-align: center;'>" . iconv('UTF-8', 'UTF-8', $rs_odjezd);
                $res = $res . "<th style='text-align: center;'>" . iconv('UTF-8', 'UTF-8', $rs_zpozdeni);
                $res = $res . "</tr>";
                $res = $res . "<tr class = 'spojrow'>";
            } else {
                $res = $res . "<th colspan='2' style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', $rs_linka);
                $res = $res . "<th style='text-align: left;'>";
                $res = $res . "<th style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', $rs_zezastavky);
                $res = $res . "<th style='text-align: right;'>" . iconv('UTF-8', 'UTF-8', $rs_odjezd);
                $res = $res . "<th style='text-align: left;'>";
                $res = $res . "<th style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', $rs_dozastavky);
                $res = $res . "<th style='text-align: right;'>" . iconv('UTF-8', 'UTF-8', $rs_prijezd);
                $res = $res . "</tr>";
                $res = $res . "<tr><td colspan='11'><div style='padding-top: 20px; border-top: 1px solid #dadada;'></div></td></tr>";
                $res = $res . "<tr>";
            }


            $prijezd = 0;
            $odjezd = 0;
            if ($location == 17) {
                $plus = 1;
            } else {
                $plus = 0;
            }
            for ($ii = 0; $ii < count($myarraylist[$i]->PartSpoje) + $plus; $ii++) {
                $val1 = $myarraylist[$i]->PartSpoje[$ii];
                $val2 = $myarraylist[$i]->PartSpoje[$ii - 1];
                $val3 = $myarraylist[$i]->PartSpoje[$ii + 1];
                if (($ii == 0) && ($val1 != null)) {
                    $pocatek = $val1->odjezd;
                    $ppocatek = $val1->text_odjezd;
                }
                if ($val1 != null) {
                    $konec = $val1->prijezd;
                }

                if ($location == 17) {
                    $res = $res . "<td style='text-align: center; width: auto;'>";
                } else {
                    $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
                }
                $res = $res . "<a class = 'popisek' style='font-size: 20px; font-weight: bold;'>";
                $res = $res . iconv('UTF-8', 'UTF-8', $val1->nazev_linky);
                $res = $res . "</a>";
                $res = $res . "</td>";

                if ($val1 != null) {
                    $zast_show = getZASTAVKA($val1->od_zastavky->id_zastavky, $val1->c_linky, $val1->tarif_od);
                    $zast_show1 = getZASTAVKA($val1->do_zastavky->id_zastavky, $val1->c_linky, $val1->tarif_do);
                }

                $doprava = "";
                if ($location == 17) {
                    if ($val1 != null) {
                        if ($val1->typ_dopravy == 'T') {
                            $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/plzen/bus_small.png' style='vertical-align: top !important;'></img>";
                        }
                        if ($val1->typ_dopravy == 'O') {
                            $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/plzen/trol_small.png' style='vertical-align: top !important;'></img>";
                        }
                        if ($val1->typ_dopravy == 'A') {
                            $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/plzen/tram_small.png' style='vertical-align: top !important;''></img>";
                        }
                        if ($val1->typ_dopravy == 'P') {
                            $doprava = /* "<img id='sipkaRDown" . ($i * 100 + $ii) . "' class='walkshow' src = '//152.mhdspoje.cz/JRw50new/image/sipkaRDown.png' onClick='showhideMap(map" . ($i * 100 + $ii) . ", \"divMap" . ($i * 100 + $ii) . "\", " . $zast_show->LOCB . ", " . $zast_show->LOCA . ", \"sipkaRDown" . ($i * 100 + $ii) . "\");' style='vertical-align: top !important;''></img> */"<img src = 'http://www.mhdspoje.cz/jrw50/image/peso_small.png' class='walk' style='vertical-align: top !important;'' onClick='showhideMap(map" . ($i * 100 + $ii) . ", \"divMap" . ($i * 100 + $ii) . "\", " . $zast_show->LOCB . ", " . $zast_show->LOCA . ", \"sipkaRDown" . ($i * 100 + $ii) . "\");'></img>";
                        }
                    }
                } else {
                    if ($val1->typ_dopravy == 'T') {
                        $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/autobus_small.png' style='vertical-align: top !important;'></img>";
                    }
                    if ($val1->typ_dopravy == 'O') {
                        $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/trolejbus_small.png' style='vertical-align: top !important;'></img>";
                    }
                    if ($val1->typ_dopravy == 'A') {
                        $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/tramvaj_small.png' style='vertical-align: top !important;''></img>";
                    }
                    if ($val1->typ_dopravy == 'P') {
                        if ($location == 6) {
                            $doprava = "<img id='sipkaRDown" . ($i * 100 + $ii) . "' class='walkshow' src = '//152.mhdspoje.cz/JRw50new/image/sipkaRDown.png' onClick='showhideMap(map" . ($i * 100 + $ii) . ", \"divMap" . ($i * 100 + $ii) . "\", " . $zast_show->LOCB . ", " . $zast_show->LOCA . ", \"sipkaRDown" . ($i * 100 + $ii) . "\");' style='vertical-align: top !important;''></img><img src = 'http://www.mhdspoje.cz/jrw50/image/peso_small.png' style='vertical-align: top !important;''></img>";
                        } else {
                            $doprava = "<img src = '//152.mhdspoje.cz/JRw50new/image/peso_small.png' style='vertical-align: top !important;''></img>";
                        }
                    }
                }
                if ($location == 17) {
                    $res = $res . "<td style='text-align: center; width: auto;'>";
                } else {
                    $res = $res . "<td style='text-align: right; width: auto; padding: 0px 15px 0px 0px;'>";
                }
                $res = $res . $doprava;

                $res = $res . "</td>";

                if ($location == 17) {
                    
                } else {
                    $res = $res . "<td  style='padding: 5px 15px 5px 5px; white-space: normal;'>";
                    $res = $res . "<img class='imgmap' src='//152.mhdspoje.cz/JRw50new/css/prapor.png' onClick='if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, " . (($zast_show->LOCA == 0) ? 'null' : $zast_show->LOCA) . ", " . (($zast_show->LOCB == 0) ? 'null' : $zast_show->LOCB) . ", " . (integer) $zast_show->id_zastavky . ");'>&nbsp;</img>";
                    $res = $res . "</td>";
                }

                $res = $res . "<td class='sp_zastavka'  style='width: auto; padding: 5px 15px 5px 5px; white-space: normal;'>";
                if ($val1 != null) {
                    $res = $res . iconv('UTF-8', 'UTF-8', $zast_show->nazev_zastavky . (($location == 6) ? (($val1->smer == 0) ? (($zast_show->AT != '') ? ' ( ' . $zast_show->AT . ' ) ' : '') : (($zast_show->BT != '') ? ' ( ' . $zast_show->BT . ' ) ' : '')) : ''));
                } else {
                    $v = $myarraylist[$i]->PartSpoje[$ii - $plus];
                    $res = $res . iconv('UTF-8', 'UTF-8', getZASTAVKA($v->do_zastavky->id_zastavky, $v->c_linky, $v->tarif_do)->nazev_zastavky . (($location == 6) ? (($val1->smer == 0) ? (($zast_show->AT != '') ? ' ( ' . $zast_show->AT . ' ) ' : '') : (($zast_show->BT != '') ? ' ( ' . $zast_show->BT . ' ) ' : '')) : ''));
                }
                $res = $res . "</td>";

                if (/* ($ii == 0) || */($val1 == null)) {
                    if ($location == 17) {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: center;'>";
                    } else {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: right;'>";
                    }
                } else {
                    if ($location == 17) {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; text-align: center;'>";
                    } else {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; text-align: right;'>";
                    }
                }

                if ($location == 17) {
                    if ($ii > 0) {
                        if ($val2->typ_dopravy != 'P') {
                            $res = $res . $val2->text_prijezd/* . " (" . $val1->c_spoje . "," . $val1->smer . ")" */;
                        }
                    }
                    $res = $res . "</td>";

                    if /* ($ii == count($myarraylist[$i]->PartSpoje) - 1) */ ($val2 == null) {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; font-weight: bold; text-align: center;'>";
                    } else {
                        $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal; text-align: center;'>";
                    }
                    if ($val2 != null) {
                        if ($val1->typ_dopravy == 'P') {
                            $res = $res . "přesun&nbsp;" . ($val1->prijezd - $val2->prijezd) . "&nbsp;min.";
                        } else {
                            $res = $res . $val1->text_odjezd;
                        }
                    } else {
                        if ($val1->typ_dopravy == 'P') {
                            $res = $res . "přesun&nbsp;" . ($val1->prijezd - $val1->odjezd) . "&nbsp;min.";
                        } else {
                            $res = $res . $val1->text_odjezd;
                        }
                    }
                    $res = $res . "</td>";

                    if ($val1->typ_dopravy != 'P') {
                        $res = $res . "<td style='text-align: center;'>";
                        $res = $res . "<img id='delay" . ($i * 100 + $ii) . "' src = '//152.mhdspoje.cz/JRw50new/image/status_noinfo.png' style='vertical-align: top !important;'>";
                        $res = $res . "</td>";
                    } else {
                        $res = $res . "<td style='text-align: center;'></td>";
                    }

                    $res = $res . "</tr>";
                } else {
                    $res = $res . $val1->text_odjezd/* . " (" . $val1->c_spoje . "," . $val1->smer . ")" */;
                    $res = $res . "</td>";

                    $res = $res . "<td style='padding: 5px 15px 5px 5px; white-space: normal;'>";
                    $res = $res . "<img class='imgmap' src='//152.mhdspoje.cz/JRw50new/css/prapor.png' onClick='if (event != null) { event.cancelBubble; event.stopPropagation(); } else { if (!event) var event = window.event; event.cancelBubble = true; event.returnValue = false; } selfobj.mapAllStops(null, " . (($zast_show1->LOCA == 0) ? 'null' : $zast_show1->LOCA) . ", " . (($zast_show1->LOCB == 0) ? 'null' : $zast_show1->LOCB) . ", " . (integer) $zast_show1->id_zastavky . ");'>&nbsp;</img>";
                    $res = $res . "</td>";

                    $res = $res . "<td class='sp_zastavka' style='width: auto; padding: 5px 15px 5px 5px; white-space: normal;'>";
                    $res = $res . iconv('UTF-8', 'UTF-8', $zast_show1->nazev_zastavky . (($location == 6) ? (($val1->smer == 0) ? (($zast_show1->AT != '') ? ' ( ' . $zast_show1->AT . ' ) ' : '') : (($zast_show1->BT != '') ? ' ( ' . $zast_show1->BT . ' ) ' : '')) : ''));
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

                if (($location == 6) || ($location == 17)) {
                    if ($val1->typ_dopravy == 'P') {
                        $resmap[count($resmap)] = "var directionsService" . ($i * 100 + $ii) . " = new google.maps.DirectionsService;
var directionsDisplay" . ($i * 100 + $ii) . " = new google.maps.DirectionsRenderer;
var map" . ($i * 100 + $ii) . " = new google.maps.Map(document.getElementById('divMap" . ($i * 100 + $ii) . "'), {
zoom: 9,
center: new google.maps.LatLng(" . $zast_show->LOCB . ", " . $zast_show->LOCA . "),
mapTypeId: google.maps.MapTypeId.ROADMAP
});
directionsDisplay" . ($i * 100 + $ii) . ".setMap(map" . ($i * 100 + $ii) . ");
directionsService" . ($i * 100 + $ii) . ".route({
origin: {lat: " . $zast_show->LOCB . ", lng: " . $zast_show->LOCA . "},
destination: {lat: " . $zast_show1->LOCB . ", lng: " . $zast_show1->LOCA . "},
travelMode: google.maps.DirectionsTravelMode.WALKING
}, function(response, status) {
if (status === 'OK') {
directionsDisplay" . ($i * 100 + $ii) . ".setDirections(response);
}
});
nc = document.getElementById('divMap" . ($i * 100 + $ii) . "');
nc.style.width = '0px';
nc.style.height = '0px';";
                        $res = $res . "<tr>";
                        $res = $res . "<td colspan='6' style='height: 0px; border-left: #ffcc33 1px solid; border-right: #ffcc33 1px solid;'>";
                        $res = $res . "<div id='divMap" . ($i * 100 + $ii) . "' style='width: 100%; height: 300px; margin-top: 0px; margin-bottom: 0px;'>";
                        $res = $res . "</div>";
                        $res = $res . "</td>";
                        $res = $res . "</tr>";
                    }
                }
                $res = $res . "<tr class = 'spojrow'>";
            }
        }

        if ($location == 6) {
            $res = $res . "<tr><td colspan='11' style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', 'Dĺžka&nbsp;cesty&nbsp;') . ($konec - $pocatek) . iconv('UTF-8', 'UTF-8', '&nbsp;min.') . "</td></tr>";
        }
        if ($location == 17) {
            $res = $res . "<tr><td colspan='6' style='text-align: left;'>" . iconv('UTF-8', 'UTF-8', 'Délka cesty&nbsp;') . ($konec - $pocatek) . iconv('UTF-8', 'UTF-8', '&nbsp;min.') . "</td></tr>";
            $res = $res . "<tr><td colspan='6' style='text-align: left;'>&nbsp;</td></tr>";
        }
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
        $res = $res . iconv('UTF-8', 'UTF-8', 'Od zadaného času (') . (($_GET['h'] < 10) ? '0' . $_GET['h'] : $_GET['h']) . ":" . (($_GET['m'] < 10) ? '0' . $_GET['m'] : $_GET['m']) . iconv('windows-1250', 'UTF-8', ') sa v najbližších 2 hodinách spojenie nenašlo.');
    } else {
        $res = $res . iconv('UTF-8', 'UTF-8', $rs_spojeninenalezeno) . $day . '.' . $month . '.' . $year . ' -- ' . $_GET['h'] . ":" . $_GET['m'];
    }
    $res = $res . "</a></td>";
    $res = $res . "</tr>";
}
$res = $res . "</table>";
$res = $res . "</div>";

if ($location == 6) {
    $pocatek = $pocatek + 1;
    $res = $res . /* $ppocatek . " - ". intval($pocatek / 60) . ":" . ($pocatek % 60) . */"</br><div>
<center>
<button title='" . (($lang == 'cz') ? iconv('UTF-8', 'UTF-8', 'Další spojení') : iconv('UTF-8', 'UTF-8', 'Následujuce spojenia')) . "' onclick='Time.initialize(" . intval($pocatek / 60) . ", " . intval($pocatek % 60) . "); JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM(), " . $prime . ");' id='mod-rscontact-submit-btn-124' class='formButton'><span class=''></span>" . (($lang == 'cz') ? iconv('UTF-8', 'UTF-8', 'Další spojení') : iconv('UTF-8', 'UTF-8', 'Následujuce')) . "</button>
</center>
</div>";
}

if ($location == 17) {
    $pocatek = $pocatek + 1;
    $res = $res . /* $ppocatek . " - ". intval($pocatek / 60) . ":" . ($pocatek % 60) . */"</br><div>
<center>
<button title='Další spojení' onclick='Time.initialize(" . intval($pocatek / 60) . ", " . intval($pocatek % 60) . "); JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM(), " . $prime . ");' id='mod-rscontact-submit-btn-124' class='formButton'><span class=''></span><img src='css/Plzen/search_next.png'></img>Další spojení</button>
</center>
</div>";
}

echo $_GET['callback'] . "(" . json_encode($res) . ");";

for ($i = 0; $i < count($resmap); $i++) {
    echo $resmap[$i];
}
?>