<?php

require_once 'param.php';

class _TSpojItem {

    public $MM = null;
    public $chrono = null;
    public $c_spoje = null;
    public $kodpozn = 0;

}

class _TZastavkaItem {

    public $c_linky = null;
    public $c_tarif = null;
    public $A = 0;
    public $B = 0;
    public $prestup = 0;

}

class _TPrestup {

    public $c_linky;
    public $od_tarif = null;
    public $do_zastavky;
    public $do_tarif = null;
    public $smer = null;
    public $prestup = 0;
    public $doba = null;
    public $vzdalenost = null;

    public function create($_c_linky, $_od_tarif, $_do_zastavky, $_do_tarif, $_prestup, $_doba, $_vzdalenost) {
        $this->c_linky = $_c_linky;
        $this->od_tarif = $_od_tarif;
        $this->do_zastavky = $_do_zastavky;
        $this->do_tarif = $_do_tarif;
        $this->smer = ($_od_tarif < $_do_tarif) ? 0 : 1;
        $this->prestup = $_prestup;
        $this->doba = $_doba;
        $this->vzdalenost = $_vzdalenost;
    }

}

class _TPrestupPeso {

    var $c_linky;
    var $od_tarif = null;
    var $do_zastavky;
    var $do_tarif = null;
    var $doba = null;
    var $vzdalenost = null;

    public function create($_c_linky, $_do_zastavky, $_doba, $_vzdalenost) {
        $this->c_linky = $_c_linky;
        $this->do_zastavky = $_do_zastavky;
        $this->doba = $_doba;
        $this->vzdalenost = $_vzdalenost;
    }

}

class _TZastavka {

    public $id_zastavky = null;
    public $nazev_zastavky = null;
    public $locA = 0;
    public $locB = 0;

    public function __construct($_id_zastavky, $_nazev_zastavky, $_locA, $_locB) {
        $this->id_zastavky = $_id_zastavky;
        $this->nazev_zastavky = $_nazev_zastavky;
        $this->locA = $_locA;
        $this->locB = $_locB;
    }

}

function convertDate($datum /* dd_mm_yyyy */) {
    list($param_day, $param_month, $param_year) = explode('_', trim($datum));
    $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
    $datum_res = date('Y-m-d', $mk);

    return $datum_res;
}

function getVARGRF($datum /* dd_mm_yyyy */, $location, $packet, &$mysqli = NULL) {
    $varGRF = -1;
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    if ($connect) {
        $sql = "SELECT distinct datum, pk FROM kalendar where datum = '" . convertDate($datum) . "' and idlocation = " . $location . " and packet = " . $packet . " order by pk;";
        $result = $mysqli->query($sql);

        $varGRF = 0;
        while ($row = $result->fetch_row()) {
            $sql = "SELECT bcode(" . $location . ", " . $packet . ", " . $row[1] . ");";
            $resultVARGRF = $mysqli->query($sql);
            $rowVARGRF = $resultVARGRF->fetch_row();
            $varGRF += $rowVARGRF[0];
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }
    return $varGRF;
}

function getSPOJE($location, $packet, /* $varGRF, */ &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $SPOJE = null; //[c_linky][smer][HH]

    if ($connect) {
        $sql = "SELECT c_linky, smer, HH, MM, chrono, c_spoje, kodpozn FROM spoje WHERE spoje.idlocation = " . $location . " AND spoje.packet = " . $packet . " AND spoje.voz = 1";
        if ($location != 6) {
            $sql = $sql . " AND (spoje.vlastnosti & 2048) <> 2048";
        }
        $sql = $sql . " order by kodpozn, c_linky";
//and ((spoje.kodpozn & " . $varGRF . ") > 0 or spoje.kodpozn = 0)
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
//      if ((($row[6] & $varGRF) > 0) || ($row[6] == 0)) {
            $SpojItem = new _TSpojItem();
            $SpojItem->MM = $row[3];
            $SpojItem->chrono = $row[4];
            $SpojItem->c_spoje = $row[5];
            $SpojItem->kodpozn = $row[6];
            $SPOJE[$row[6]][$row[0]][$row[1]][$row[2]][count($SPOJE[$row[6]][$row[0]][$row[1]][$row[2]])] = $SpojItem;
//      }
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $SPOJE;
}

function getPRESTUPY($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $PRESTUPY = NULL; //[od_zastavky]TPrestup

    if ($connect) {
        $sql = "select distinct zast.C_ZASTAVKY as OD_ZASTAVKY, zast.C_TARIF as OD_TARIF, zast.C_LINKY,
                zlA.C_ZASTAVKY as DO_ZASTAVKY_A, zlA.C_TARIF as DO_TARIF_A, zlA.PRESTUP as PRESTUP_A,
                zlB.C_ZASTAVKY as DO_ZASTAVKY_B, zlB.C_TARIF as DO_TARIF_B, zlB.PRESTUP as PRESTUP_B,
                NULL as CAS, NULL as VZDALENOST from zaslinky zast
                left outer join zaslinky zlA on zlA.IDLOCATION = zast.IDLOCATION and zlA.PACKET = zast.PACKET and zlA.C_LINKY = zast.C_LINKY and
                zlA.C_TARIF = (select zaslinky.C_TARIF from zaslinky
                left outer join (select chronometr.c_linky, chronometr.c_tarif, (sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy)) as stA from chronometr where chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.smer = 0 group by chronometr.c_linky, chronometr.c_tarif having sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy) <> -1) staviA
                on (zaslinky.c_linky = staviA.c_linky and zaslinky.c_tarif = staviA.c_tarif)
                where zaslinky.C_LINKY = zast.C_LINKY and zaslinky.C_TARIF > zast.C_TARIF and zaslinky.ZAST_A = 1 and staviA.stA is not null and zaslinky.prestup = 1 and zaslinky.IDLOCATION = " . $location . " and zaslinky.PACKET = " . $packet . " ORDER BY zaslinky.C_TARIF ASC LIMIT 1)

                left outer join zaslinky zlB on zlB.IDLOCATION = zast.IDLOCATION and zlB.PACKET = zast.PACKET and zlB.C_LINKY = zast.C_LINKY and
                zlB.C_TARIF = (select zaslinky.C_TARIF from zaslinky
                left outer join (select chronometr.c_linky, chronometr.c_tarif, (sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy)) as stB from chronometr where chronometr.idlocation = " . $location . " and chronometr.packet = " . $packet . " and chronometr.smer = 1 group by chronometr.c_linky, chronometr.c_tarif having sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy) <> -1) staviB
                on (zaslinky.c_linky = staviB.c_linky and zaslinky.c_tarif = staviB.c_tarif)
                where zaslinky.C_LINKY = zast.C_LINKY and zaslinky.C_TARIF < zast.C_TARIF and zaslinky.ZAST_B = 1 and staviB.stB is not null and zaslinky.prestup = 1 and zaslinky.IDLOCATION = " . $location . " and zaslinky.PACKET = " . $packet . " ORDER BY zaslinky.C_TARIF DESC LIMIT 1)

                where zast.idlocation = " . $location . " and zast.packet = " . $packet . " and (zast.ZAST_A = 1 or zast.ZAST_B = 1)

                UNION

                select pesobus.OD_ZASTAVKY as OD_ZASTAVKY, NULL as OD_TARIF, -1 AS C_LINKY,
                pesobus.DO_ZASTAVKY as DO_ZASTAVKY_A, NULL as DO_TARIF_A, 1 as PRESTUP_A,
                NULL as DO_ZASTAVKY_B, NULL as DO_TARIF_B, NULL as PRESTUP_B,
                pesobus.CAS as CAS, pesobus.VZDALENOST as VZDALENOST from pesobus
                where pesobus.IDLOCATION = " . $location . " and pesobus.PACKET = " . $packet . "

                ORDER BY OD_ZASTAVKY, C_LINKY, OD_TARIF";

        /*    $sql = "select distinct zast.C_ZASTAVKY as OD_ZASTAVKY, zast.C_TARIF as OD_TARIF, zast.C_LINKY,
          zastavkyA.C_ZASTAVKY as DO_ZASTAVKY, zastavkyA.C_TARIF as DO_TARIF, 0 as SMER, zastavkyA.PRESTUP,
          NULL as CAS, NULL as VZDALENOST from zaslinky zast

          left outer join
          (select * from zaslinky zasA
          left outer join (select chronometr.c_linky as CH_C_LINKY, chronometr.c_tarif as CH_C_TARIF, (sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy)) as stA from chronometr where chronometr.idlocation = " . $location. " and chronometr.packet = " . $packet. " and chronometr.smer = 0 group by chronometr.c_linky, chronometr.c_tarif having sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy) <> -1) staviA
          on (zasA.c_linky = staviA.CH_C_LINKY and zasA.c_tarif = staviA.CH_C_TARIF)
          where zasA.IDLOCATION = " . $location. " and zasA.PACKET = " . $packet. ") zastavkyA
          on (zastavkyA.IDLOCATION = zast.IDLOCATION and zastavkyA.PACKET = zast.PACKET and zastavkyA.C_LINKY = zast.C_LINKY and zastavkyA.C_TARIF > zast.C_TARIF)

          where zast.idlocation = " . $location. " and zast.packet = " . $packet. " and zast.ZAST_A = 1

          UNION

          select distinct zast.C_ZASTAVKY as OD_ZASTAVKY, zast.C_TARIF as OD_TARIF, zast.C_LINKY,
          zastavkyB.C_ZASTAVKY as DO_ZASTAVKY, zastavkyB.C_TARIF as DO_TARIF, 1 as SMER, zastavkyB.PRESTUP,
          NULL as CAS, NULL as VZDALENOST from zaslinky zast
          left outer join
          (select * from zaslinky zasB
          left outer join (select chronometr.c_linky as CH_C_LINKY, chronometr.c_tarif as CH_C_TARIF, (sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy)) as stB from chronometr where chronometr.idlocation = " . $location. " and chronometr.packet = " . $packet. " and chronometr.smer = 0 group by chronometr.c_linky, chronometr.c_tarif having sum(chronometr.doba_jizdy)/count(chronometr.doba_jizdy) <> -1) staviB
          on (zasB.c_linky = staviB.CH_C_LINKY and zasB.c_tarif = staviB.CH_C_TARIF)
          where zasB.IDLOCATION = " . $location. " and zasB.PACKET = " . $packet. ") zastavkyB
          on (zastavkyB.IDLOCATION = zast.IDLOCATION and zastavkyB.PACKET = zast.PACKET and zastavkyB.C_LINKY = zast.C_LINKY and zastavkyB.C_TARIF < zast.C_TARIF)

          where zast.idlocation = " . $location. " and zast.packet = " . $packet. " and zast.ZAST_B = 1

          UNION

          select pesobus.OD_ZASTAVKY as OD_ZASTAVKY, NULL as OD_TARIF, -1 AS C_LINKY,
          pesobus.DO_ZASTAVKY as DO_ZASTAVKY, NULL as DO_TARIF, 0 as SMER, 1 as PRESTUP,
          pesobus.CAS as CAS, pesobus.VZDALENOST as VZDALENOST from pesobus
          where pesobus.IDLOCATION = " . $location. " and pesobus.PACKET = " . $packet. "

          ORDER BY OD_ZASTAVKY, C_LINKY, OD_TARIF, SMER, DO_TARIF"; */

//and zlA.C_LINKY is not null and zlB.C_LINKY is not null
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            //$_c_linky, $_od_tarif, $_do_zastavky, $_do_tarif, $_prestup, $_doba, $_vzdalenost
            if ($row[3] != NULL) {
                $pomPrestup = new _TPrestup();
                $pomPrestup->create($row[2], $row[1], $row[3], $row[4], $row[5], $row[9], $row[10]);
                $PRESTUPY[$row[0]][count($PRESTUPY[$row[0]])] = $pomPrestup;
            }
            if ($row[6] != NULL) {
                $pomPrestup = new _TPrestup();
                $pomPrestup->create($row[2], $row[1], $row[6], $row[7], $row[8], $row[9], $row[10]);
                $PRESTUPY[$row[0]][count($PRESTUPY[$row[0]])] = $pomPrestup;
            }
            /*      $pomPrestup = new _TPrestup();
              $pomPrestup->create($row[2], $row[1], $row[3], $row[4], $row[6], $row[7], $row[8]);
              $PRESTUPY[$row[0]][count($PRESTUPY[$row[0]])] = $pomPrestup; */
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $PRESTUPY;
}

function getCHRONO($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $CHRONO = null; //[c_linky][smer][chrono][c_tarif]

    if ($connect) {
        $sql = "SELECT chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.c_tarif, chronometr.doba_pocatek, chronometr.c_zastavky, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B FROM chronometr
              LEFT OUTER JOIN zaslinky
              ON (zaslinky.idlocation = chronometr.idlocation AND zaslinky.packet = chronometr.packet AND zaslinky.c_linky = chronometr.c_linky AND zaslinky.c_tarif = chronometr.c_tarif)
              WHERE chronometr.idlocation = " . $location . " AND chronometr.packet = " . $packet . "  AND chronometr.doba_jizdy > -1";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            $CHRONO[$row[0]][$row[1]][$row[2]][$row[3]] = $row[4];
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $CHRONO;
}

function getZASTAVKY($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $ZASTAVKY = null; //[c_linky][smer][chrono][c_tarif]

    if ($connect) {
        $sql = "SELECT C_ZASTAVKY, NAZEV, LOCA, LOCB FROM zastavky where idlocation = " . $location . " AND packet = " . $packet . "  order by c_zastavky";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            $ZASTAVKY[$row[0]] = new _TZastavka($row[0], $row[1], $row[2], $row[3]);
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $ZASTAVKY;
}

function getPESOBUS($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $PESOBUS = null; //[c_linky][smer][chrono][c_tarif]

    if ($connect) {
        $sql = "SELECT OD_ZASTAVKY, DO_ZASTAVKY, CAS, VZDALENOST FROM pesobus
            WHERE pesobus.idlocation = " . $location . " AND pesobus.packet = " . $packet;
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            $pPrestup = new _TPrestupPeso();
            $pPrestup->create(-1, $row[1], $row[2], $row[3]);
            $PESOBUS[$row[0]][$row[1]] = $pPrestup;
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $PESOBUS;
}

function getCHRONO_ZASTAVKY_LINKY_ZASTAVKY($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $CHRONO = null; //[c_linky][smer][chrono][c_tarif]
    $ZASTAVKY_LINKY = null; //[c_zastavky][c_linky][c_tarif]TZastavkaItem
    $LINKY_ZASTAVKY = null; //[c_linky][c_zastavky][c_tarif]TZastavkaItem

    if ($connect) {
        $sql = "SELECT chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.c_tarif, chronometr.doba_pocatek, chronometr.c_zastavky, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B FROM chronometr
              LEFT OUTER JOIN zaslinky
              ON (zaslinky.idlocation = chronometr.idlocation AND zaslinky.packet = chronometr.packet AND zaslinky.c_linky = chronometr.c_linky AND zaslinky.c_tarif = chronometr.c_tarif)
              WHERE chronometr.idlocation = " . $location . " AND chronometr.packet = " . $packet . "  AND chronometr.doba_jizdy > -1";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            $CHRONO[$row[0]][$row[1]][$row[2]][$row[3]] = $row[4];
            if ($ZASTAVKY_LINKY[$row[5]][$row[0]][$row[3]] == null) {
                $pomZastavka = new _TZastavkaItem();
                $pomZastavka->c_linky = $row[0];
                $pomZastavka->c_tarif = $row[3];
                $pomZastavka->A = $row[7];
                $pomZastavka->B = $row[8];
                $pomZastavka->prestup = $row[6];
                $ZASTAVKY_LINKY[$row[5]][$row[0]][$row[3]] = $pomZastavka;
                $LINKY_ZASTAVKY[$row[0]][$row[5]][$row[3]] = $pomZastavka;
            }
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return array(0 => $CHRONO, 1 => $ZASTAVKY_LINKY, 2 => $LINKY_ZASTAVKY);
}

function getZASTAVKY_LINKY($location, $packet, &$mysqli = NULL) {
    $nullConnect = $mysqli;
    if ($mysqli != NULL) {
        $connect = TRUE;
    } else {
        $connect = connect_DB($mysqli);
    }

    $ZASTAVKY_LINKY = null; //[c_zastavky][c_linky][c_tarif]TZastavkaItem

    if ($connect) {
        $sql = "SELECT distinct zaslinky.c_zastavky, zaslinky.c_linky, zaslinky.c_tarif, zaslinky.prestup, zaslinky.zast_a as A, zaslinky.zast_b as B FROM zaslinky
              LEFT OUTER JOIN chronometr
              ON (zaslinky.idlocation = chronometr.idlocation AND zaslinky.packet = chronometr.packet AND zaslinky.c_linky = chronometr.c_linky AND zaslinky.c_tarif = chronometr.c_tarif)
              WHERE zaslinky.idlocation = " . $location . " AND zaslinky.packet = " . $packet . " AND chronometr.doba_jizdy > -1
              AND (zaslinky.zast_a = 1 or zaslinky.zast_b = 1) order by zaslinky.c_zastavky, zaslinky.c_linky, zaslinky.c_tarif";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_row()) {
            if ($ZASTAVKY_LINKY[$row[0]][$row[1]][$row[2]] == null) {
                $pomZastavka = new _TZastavkaItem();
                $pomZastavka->c_linky = $row[1];
                $pomZastavka->c_tarif = $row[2];
                $pomZastavka->A = $row[4];
                $pomZastavka->B = $row[5];
                $pomZastavka->prestup = $row[3];
                $ZASTAVKY_LINKY[$row[0]][$row[1]][$row[2]] = $pomZastavka;
            }
        }

        if ($nullConnect == NULL) {
            close_DB($mysqli);
        }
    }

    return $ZASTAVKY_LINKY;
}

function saveStructure($path, $filename, $class) {
    if (!file_exists($path)) {
        mkdir($path, 0777);
    }
    chmod($path, 0777);

    $fileLocation = $path . $filename;
    $file = fopen($fileLocation, "w+");
    fwrite($file, json_encode($class));
    fclose($file);
    chmod($fileLocation, 0777);
}

function loadStructure($path, $filename) {
    $fileLocation = $path . $filename;
    $file = fopen($fileLocation, "r+");
    $res = json_decode(fread($file, filesize($fileLocation)), TRUE);
    fclose($file);

    return $res;
}

function createStructure($location, $packet) {
    $path = "../jrstructure/" . $location . "/" . $packet . '/';

    if (connect_DB($mysqli)) {

        $CHZL = getCHRONO_ZASTAVKY_LINKY_ZASTAVKY($location, $packet, $mysqli);

        saveStructure($path, "spoje.dat", getSPOJE($location, $packet, $mysqli));
        saveStructure($path, "chrono.dat", $CHZL[0]);
        saveStructure($path, "zastavky_linky.dat", $CHZL[1]);
        saveStructure($path, "linky_zastavky.dat", $CHZL[2]);
//    saveStructure($path, "zastavky_linky.dat", getZASTAVKY_LINKY($location, $packet, $mysqli));
//        saveStructure($path, "prestupy.dat", getPRESTUPY($location, $packet, $mysqli));
        saveStructure($path, "chrono.dat", getCHRONO($location, $packet, $mysqli));
        saveStructure($path, "zastavky.dat", getZASTAVKY($location, $packet, $mysqli));
        saveStructure($path, "pesobus.dat", getPESOBUS($location, $packet, $mysqli));
    }
}

function mysql_connect($con_server1, $con_db, $con_pass) {
    GLOBAL $con_user;
    GLOBAL $con_server;
    GLOBAL $global_connection;

    if ($global_connection == null) {
        $connect = mysqli_connect($con_server, $con_user, $con_pass, $con_db);
        $connect->set_charset("utf8");
        $global_connection = $connect;
    }
    return $global_connection;
}

function mysql_select_db($con_db) {
    GLOBAL $global_connection;

    mysqli_select_db($global_connection, $con_db);
}

function mysql_query($sql) {
    GLOBAL $global_connection;

    return $global_connection->query($sql);
}

function mysql_fetch_row($q) {
    GLOBAL $global_connection;

    return mysqli_fetch_row($q);
}

function mysql_close($connect) {
    GLOBAL $global_connection;

    mysqli_close($connect);
    $global_connection = null;
}

function mysql_num_rows($q) {
    return $q->num_rows;
}

function mysql_result($q, $field) {
    $datarow = $q->fetch_array();
    return $datarow[$field];
}

function mysql_insert_id($q) {
//    return $q->insert_id;
    GLOBAL $global_connection;
    
    return $global_connection->insert_id;
}

function get_connection() {
    GLOBAL $global_connection;

    return $global_connection;
}

function mysql_real_escape_string($str) {
    GLOBAL $global_connection;

    return mysqli_real_escape_string($global_connection, $str);
}

function mysql_data_seek($result, $offset) {
    return mysqli_data_seek($result, $offset);
}

?>
