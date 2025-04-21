<?php
/* header('Content-Type: text/html; charset=windows-1250'); */
include '../lib/CZlang.php';
include '../lib/param.php';
include 'lib/func.php';

class TPoznamka {

  var $c_kodu = null;
  var $nove_c_kodu = null;
  var $oznaceni = null;
  var $popis = null;
  var $caspozn = 0;
  var $zobrazovat = 1;

}

class TOffset {

  var $spoje = null;
  var $chrono = null;

}

class TZastavky {

  var $c_tarif = null;
  var $cislo_zastavky = null;
  var $znaku_pasma = 0;
  var $text_pasma = '';
  var $pocet_poznamek = 0;
  var $poznamky = '';
  var $priznaky = 0;
  var $staviA = 0;
  var $staviB = 0;

}

class TZastavka {

  var $c_tarif = null;
  var $c_zastavky = null;
  var $zast_A = false;
  var $zast_B = false;
  var $prestup = false;

}

$CENTRAL_POZNAMKY = null;
$OFFSETY = null;
$LINKY = array();
$TRASY = null;
?>

<?php
/* $idlocation = $_GET[un];
  $packet = $_GET[p]; */
$idlocation = getLocation($_POST["username"]);
$packet = $_POST[exportpack];
echo $idlocation . " / " . $packet;
$target_path = "../../jrdata/" . $idlocation . "/" . $packet . '/';

loadPoznamky($idlocation, $packet);
exp_ZASTAVKY($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 2);
</script>
<?php
exp_KALENDAR($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 3);
</script>
<?php
exp_POZNAMKY($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 4);
</script>
<?php
exp_SPOJE($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 5);
</script>
<?php
exp_CHRONO($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 6);
</script>
<?php
exp_LINKY($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 7);
</script>
<?php
exp_BODY($idlocation, $packet, $target_path);
?>
<script>
  progress(100/9 * 8);
</script>
<?php
exp_PRESTUPY($idlocation, $packet, $target_path);
?>
<script>
  progress(100);
</script>
<?php
/* foreach ($OFFSETY as $key_c_linky => $val) {
  echo '///' . $key_c_linky . '/' . conv_num_byte($val->spoje, 3);
  } */
?>
<div class="button" id="startexport" style="height: 25px; width: 150px; visibility: visible;" onclick="document.location.href = '?page=2';">
  <span></span><img src="image/abort.png">
  Zavøít
</div>



<?php

function loadPoznamky($idlocation, $packet) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $CENTRAL_POZNAMKY;

  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT C_KODU, OZNACENI, REZERVA, CASPOZN, SHOWING
          FROM `savvy_mhdspoje`.`pevnykod` WHERE idlocation=" . $idlocation . " AND packet = " . $packet . " and (i_p = 0 or i_p is null) and c_kodu > 0 order by c_kodu");
  $poradi = 1;
  while ($row = mysql_fetch_row($sql)) {
    $pozn = new TPoznamka();
    $pozn->c_kodu = $row[0];
    $pozn->nove_c_kodu = $poradi;
    $pozn->oznaceni = $row[1];
    $pozn->popis = $row[2];
    $pozn->caspozn = $row[3];
    $pozn->zobrazovat = $row[4];
    $CENTRAL_POZNAMKY[$row[0]] = $pozn;
    $poradi++;
  }
}

function exp_ZASTAVKY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;

  $res = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  /*$sql = mysql_query("SELECT C_ZASTAVKY, NAZEV, PK1, PK2, PK3, PK4, PK5, PK6, IDLOCATION, PACKET, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT, EXISTS (SELECT * FROM zaslinky WHERE zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.prestup = 1) as prestup,
          (PK1 & PK2 & PK3 & PK4 & PK5 & PK6) as ma_poznamky
          FROM `savvy_mhdspoje`.`zastavky` WHERE packet = " . $packet . " and idlocation=" . $idlocation . " order by c_zastavkysort");*/
  $sql = mysql_query("SELECT distinct C_ZASTAVKY, NAZEV, PK1, PK2, PK3, PK4, PK5, PK6, IDLOCATION, PACKET, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT, EXISTS (SELECT * FROM zaslinky WHERE zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.prestup = 1) as prestup,
          (PK1 & PK2 & PK3 & PK4 & PK5 & PK6) as ma_poznamky
          FROM `savvy_mhdspoje`.`zastavky` left outer join zaslinky
    on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky) WHERE packet = " . $packet . " and idlocation=" . $idlocation . " and zaslinky.voz = 1 ORDER BY zastavky.nazev");
  $poradi = 1;
  while ($row = mysql_fetch_row($sql)) {
    $byte = 0;
    $byte += ($row[14] == 1) ? 2 : 0; // prestup? (0,1)
    $byte += ($row[15] == 1) ? 1 : 0; // ma poznamky (0,1)
    $res .= conv_num_byte($byte, 1); // prestup/ma poznamky
    if ($row[15] == 1) { // blok poznamek
      $pocet_poznamek = 0;
      $poznamky = null;
      for ($i = 2; $i <= 7; $i++) {
        if ($row[$i] != 0) {
          $pocet_poznamek++;
          $poznamky .= conv_num_byte($row[$i], 1);
        }
      }
      $res .= conv_num_byte($pocet_poznamek, 1);
      $res .= $poznamky;
    }
    setlocale(LC_CTYPE, 'cs_CZ.UTF-8');
    $res .= conv_num_byte(strlen(iconv('cp1250', 'UTF-8', $row[1])), 1); // conv_num_byte(strlen(utf8_encode($row[1])), 1);
    $res .= iconv('cp1250', 'UTF-8', $row[1]); // utf8_encode($row[1]);
//    echo $row[1] . '|' . strlen(utf8_encode($row[1]));
    $poradi++;
  }
  $res = conv_num_byte(($poradi - 1), 2) . $res;
  $fileLocation = $path . "zastavky.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  chmod($path, 0777);
  $file = fopen($fileLocation, "w+");
  fwrite($file, $res);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_KALENDAR($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;

  $res = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT DISTINCT DATUM, PK FROM `savvy_mhdspoje`.`kalendar` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " ORDER BY DATUM");
  $poradi = 1;
  while ($row = mysql_fetch_row($sql)) {
    list($year, $month, $day) = explode('-', $row[0]);
    $res .= conv_num_byte($day, 1); // den
    $res .= conv_num_byte($month, 1); // mesic
    $res .= conv_num_byte(($year - 2000), 1); // rok
    $res .= conv_num_byte($row[1], 1); // cislo pk
    $poradi++;
  }
  $res = conv_num_byte(($poradi - 1), 2) . $res;
  $fileLocation = $path . "kalendar.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $res);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_POZNAMKY($idlocation, $packet, $path) {
  global $CENTRAL_POZNAMKY;

  $res = null;
  $poradi = 1;
  foreach ($CENTRAL_POZNAMKY as $key_c_kodu => $val) {
    $byte = 0;
    $byte += ($val->caspozn == 1) ? 2 : 0; // casova? (0,1)
    $byte += ($val->zobrazovat == 1) ? 1 : 0; // zobrazovat (0,1)
    $res .= conv_num_byte($byte, 1); // casova/zobrazovat
    setlocale(LC_CTYPE, 'cs_CZ');
    $res .= conv_num_byte(strlen(iconv('cp1250', 'ASCII//TRANSLIT', $val->oznaceni)), 1); //conv_num_byte(strlen(iconv('cp1250', 'UTF-8', $val->oznaceni)), 1); //conv_num_byte(strlen(utf8_encode($val->oznaceni)), 1);
//    echo iconv('cp1250', 'ASCII//TRANSLIT', $val->oznaceni);
    $res .= iconv('cp1250', 'ASCII//TRANSLIT', $val->oznaceni); //iconv('cp1250', 'UTF-8', $val->oznaceni); //utf8_encode($val->oznaceni);
    setlocale(LC_CTYPE, 'cs_CZ.UTF-8');
    $res .= conv_num_byte(strlen(iconv('cp1250', 'UTF-8', $val->popis)), 1); //conv_num_byte(strlen(utf8_encode($val->popis)), 1);
    $res .= iconv('cp1250', 'UTF-8', $val->popis); //utf8_encode($val->popis);
    $poradi++;
  }
  $res = conv_num_byte(($poradi - 1), 2) . $res;
  $fileLocation = $path . "poznamky.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $res);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_SPOJE($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $OFFSETY;
  global $CENTRAL_POZNAMKY;

  $resall = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT DISTINCT C_LINKY from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " ORDER BY C_LINKYSORT");
  while ($row = mysql_fetch_row($sql)) {
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    mysql_query("SET NAMES 'cp1250';");
    $sql1 = mysql_query("select SMER, CHRONO, HH, MM, PK1, PK2, PK3, PK4, PK5, PK6, PK7, PK8, PK9, PK10, C_SPOJE from spoje where idlocation = " . $idlocation . " AND PACKET = " . $packet . " AND c_linky = '" . $row[0] . "' AND spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048 order by c_linky, smer, HH, MM");
    $poradi = 1;
    $res = null;
    while ($row_spoje = mysql_fetch_row($sql1)) {
      $res .= conv_num_byte($row_spoje[1], 1); // cislo chrono
      $res .= conv_num_byte($row_spoje[2], 1); // HH
      $res .= conv_num_byte($row_spoje[3], 1); // MM

      $pocet_poznamek = 0;
      $poznamky = null;
      for ($i = 4; $i <= 13; $i++) {
        if ($row_spoje[$i] != 0) {
          if ($CENTRAL_POZNAMKY[$row_spoje[$i]] != null) {
            $pocet_poznamek++;
            $poznamky .= conv_num_byte($CENTRAL_POZNAMKY[$row_spoje[$i]]->nove_c_kodu, 1);
          }
        }
      }

      $connect = mysql_connect($con_server, $con_db, $con_pass);
      mysql_select_db($con_db);
      mysql_query("SET NAMES 'cp1250';");
      $sql2 = mysql_query("select zasspoje_pozn.C_TARIF, coalesce(zasspoje_pozn.PK1, 0), coalesce(zasspoje_pozn.PK2, 0), coalesce(zasspoje_pozn.DPK1, 0), coalesce(zasspoje_pozn.DPK2, 0), coalesce(zasspoje_pozn.DPK3, 0),
                              coalesce(zasspoje_pozn.DPK4, 0), coalesce(zasspoje_pozn.DPK5, 0), coalesce(zasspoje_pozn.DPK6, 0), coalesce(zasspoje_pozn.DPK7, 0), coalesce(zasspoje_pozn.DPK8, 0), coalesce(zasspoje_pozn.DPK9, 0)
                              from spoje left outer join zasspoje_pozn on (spoje.idlocation = zasspoje_pozn.idlocation and spoje.packet = zasspoje_pozn.packet and
                              spoje.c_linky = zasspoje_pozn.c_linky and spoje.c_spoje = zasspoje_pozn.c_spoje) where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = '" . $row[0] . "' and spoje.c_spoje = " . $row_spoje[14] . "
                              and (zasspoje_pozn.pk1 <> 0 or zasspoje_pozn.pk2 <> 0 or zasspoje_pozn.dpk1 is not null or zasspoje_pozn.dpk2 is not null or zasspoje_pozn.dpk3 is not null
                              or zasspoje_pozn.dpk4 is not null or zasspoje_pozn.dpk5 is not null or zasspoje_pozn.dpk6 is not null or zasspoje_pozn.dpk7 is not null
                              or zasspoje_pozn.dpk8 is not null or zasspoje_pozn.dpk9 is not null)
                              order by zasspoje_pozn.c_tarif");
      $pocet_pozn_chrono = 0;
      $poznamky_chrono = null;
      while ($row_ch_pozn = mysql_fetch_row($sql2)) {
        for ($i = 1; $i <= 11; $i++) {
          if ($row_ch_pozn[$i] != 0) {
            if ($CENTRAL_POZNAMKY[$row_ch_pozn[$i]] != null) {
              $pocet_pozn_chrono++;
              $poznamky_chrono .= conv_num_byte($row_ch_pozn[0], 1);
              $poznamky_chrono .= conv_num_byte($CENTRAL_POZNAMKY[$row_ch_pozn[$i]]->nove_c_kodu, 1);
            }
          }
        }
      }

      $byte = 0;
      $byte += ($pocet_poznamek > 0) ? ($pocet_poznamek * 4) : 0; // pocet poznamek
      $byte += ($row_spoje[0] == 1) ? 2 : 0; // smer
      $byte += ($pocet_pozn_chrono > 0) ? 1 : 0; // ma chrono pozn? (0, 1)
      $res .= conv_num_byte($byte, 1);
      if ($pocet_poznamek > 0) {
        $res .= $poznamky;
      }
      if ($pocet_pozn_chrono > 0) {
        $res .= conv_num_byte($pocet_pozn_chrono, 1);
        $res .= $poznamky_chrono;
      }
      $poradi++;
    }
    $res = conv_num_byte(($poradi - 1), 2) . $res;
    $offset = new TOffset();
    $offset->spoje = strlen($resall);
    $OFFSETY[$row[0]] = $offset;
    $resall .= $res;
  }
  $fileLocation = $path . "spoje.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $resall);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_CHRONO($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $OFFSETY;
  global $CENTRAL_POZNAMKY;

  $resall = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT DISTINCT C_LINKY from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " ORDER BY C_LINKYSORT");
  while ($row = mysql_fetch_row($sql)) {
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    mysql_query("SET NAMES 'cp1250';");
    $sql1 = mysql_query(
            "select zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3
          , zaslinky.a1_tarif, zaslinky.a2_tarif, zaslinky.b1_tarif, zaslinky.b2_tarif,

          (select (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $row[0] . "' and idlocation = " . $idlocation . " and
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $row[0] . "' and
          idlocation = " . $idlocation . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $row[0] . "' and idlocation = " . $idlocation . " and packet = " . $packet . " and
          smer = 0 group by c_tarif, smer, chrono) dis where c_tarif = zaslinky.c_tarif group by c_tarif) & zast_A as stavi_A,

          (select (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $row[0] . "' and idlocation = " . $idlocation . " and
          packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = '" . $row[0] . "' and
          idlocation = " . $idlocation . " and packet = " . $packet . " and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = '" . $row[0] . "' and idlocation = " . $idlocation . " and packet = " . $packet . " and
          smer = 1 group by c_tarif, smer, chrono) dis where c_tarif = zaslinky.c_tarif group by c_tarif) & zast_B as stavi_B

          from zaslinky

          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)

          where zaslinky.idlocation = " . $idlocation . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $row[0] . "'
          ORDER BY zaslinky.c_tarif");


    $pocet_zastavek = 0;
    $res = null;
    $vyb_tarif = null;
    $TRASA = null;
    while ($row_zastavky = mysql_fetch_row($sql1)) {
      if (($row_zastavky[10] || $row_zastavky[11]) == true) {
        $newzastavka = new TZastavky();
        $newzastavka->c_tarif = $row_zastavky[1];
        $newzastavka->staviA = $row_zastavky[10];
        $newzastavka->staviB = $row_zastavky[11];

        $newzastavka->cislo_zastavky = $row_zastavky[0];
        $newzastavka->znaku_pasma = 0;
        $newzastavka->text_pasma = '';
//        $res .= conv_num_byte($row_zastavky[0], 2); // cislo zastavky
//        $res .= conv_num_byte(0, 1); // pocet znaku popisu pasma
//      $res .= ''; // text pasma
//        $res .= conv_num_byte($row_zastavky[1], 1); // tarifni cislo zastavky

        $pocet_poznamek = 0;
        $poznamky = null;
        for ($i = 3; $i <= 5; $i++) {
          if ($row_spoje[$i] != 0) {
            if ($CENTRAL_POZNAMKY[$row_zastavky[$i]] != null) {
              $pocet_poznamek++;
              $poznamky .= conv_num_byte($CENTRAL_POZNAMKY[$row_zastavky[$i]]->nove_c_kodu, 1);
            }
          }
        }

//        $byte = 0;
//        $byte += ($row_zastavky[10] * 128); // stavi A
//        $byte += ($row_zastavky[11] * 64); // stavi B
//        $byte += ($pocet_poznamek > 0) ? ($pocet_poznamek) : 0; // pocet poznamek
//        $res .= conv_num_byte($byte, 1);

        $newzastavka->pocet_poznamek = $pocet_poznamek;
        $newzastavka->poznamky = $poznamky;
//        $newzastavka->priznaky = $byte;

        if (($row_zastavky[10] || $row_zastavky[11]) == true) {
          if ($vyb_tarif != null) {
            $vyb_tarif .= ",";
          }
          $vyb_tarif .= $row_zastavky[1];
        }

//        if ($pocet_poznamek > 0) {
//          $res .= $poznamky;
//        }

        $TRASA[$row_zastavky[1]] = $newzastavka;
        $pocet_zastavek++;
      }
    }

    $iter = 0;
    $first = 0;
    $last = count($TRASA) - 1;
    foreach ($TRASA as $key_c_tarif => $val) {
      $res .= conv_num_byte($val->cislo_zastavky, 2); // cislo zastavky
      $res .= conv_num_byte(0, 1); // pocet znaku popisu pasma
//      $res .= ''; // text pasma
      $res .= conv_num_byte($val->c_tarif, 1); // tarifni cislo zastavky
      $byte = 0;
      if ($iter == $first) {
        $val->staviB = 0;
      }
      if ($iter == $last) {
        $val->staviA = 0;
      }
      $byte += ($val->staviA * 128); // stavi A
      $byte += ($val->staviB * 64); // stavi B
      $byte += ($val->pocet_poznamek > 0) ? ($val->pocet_poznamek) : 0; // pocet poznamek
      $res .= conv_num_byte($byte, 1);
      if ($val->pocet_poznamek > 0) {
        $res .= $val->poznamky;
      }
      $iter++;
    }

    if ($vyb_tarif != null) {
      $vyb_tarif = " and chronometr.c_tarif in (" . $vyb_tarif . ")";
    } else {
      $vyb_tarif = "";
    }

    $res = conv_num_byte($pocet_zastavek, 1) . $res;

    $pocet_chrono = 0;
    $cislo_chrono = 0;
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    mysql_query("SET NAMES 'cp1250';");
    $sql2 = mysql_query("select chronometr.CHRONO, chronometr.SMER, chronometr.DOBA_JIZDY, chronometr.doba_pocatek, chronometr.c_tarif
                              from chronometr where chronometr.idlocation = " . $idlocation . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $row[0] . "'
                              " . $vyb_tarif . " and chronometr.smer = 0 order by chronometr.smer, chronometr.chrono, chronometr.c_tarif");

    /*    echo "select chronometr.CHRONO, chronometr.SMER, chronometr.DOBA_JIZDY, chronometr.doba_pocatek
      from chronometr where chronometr.idlocation = " . $idlocation . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $row[0] . "'
      " . $vyb_tarif . " order by chronometr.smer, chronometr.chrono, chronometr.c_tarif" . "</br>"; */

    while ($row_chrono = mysql_fetch_row($sql2)) {
      if ($cislo_chrono != $row_chrono[0]) {
        $res .= conv_num_byte($row_chrono[0], 1);
        $res .= conv_num_byte($row_chrono[1], 1);
        $pocet_chrono++;
        $cislo_chrono = $row_chrono[0];
      }
      if ($row_chrono[1] == 0) {
        if (($row_chrono[3] == -1) || ($TRASA[$row_chrono[4]]->staviA == 0)) {
          $res .= conv_num_byte(0, 1);
        } else {
          $res .= conv_num_byte(1, 1);
        }
      }
      if ($row_chrono[1] == 1) {
        if (($row_chrono[3] == -1) || ($TRASA[$row_chrono[4]]->staviB == 0)) {
          $res .= conv_num_byte(0, 1);
        } else {
          $res .= conv_num_byte(1, 1);
        }
      }

      $res .= conv_num_byte($row_chrono[2], 1);
    }

    $cislo_chrono = 0;
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    mysql_query("SET NAMES 'cp1250';");
    $sql2 = mysql_query("select chronometr.CHRONO, chronometr.SMER, chronometr.DOBA_JIZDY, chronometr.doba_pocatek, chronometr.c_tarif
                              from chronometr where chronometr.idlocation = " . $idlocation . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $row[0] . "'
                              " . $vyb_tarif . " and chronometr.smer = 1 order by chronometr.smer, chronometr.chrono, chronometr.c_tarif DESC");

    /*    echo "select chronometr.CHRONO, chronometr.SMER, chronometr.DOBA_JIZDY, chronometr.doba_pocatek
      from chronometr where chronometr.idlocation = " . $idlocation . " and chronometr.packet = " . $packet . " and chronometr.c_linky = '" . $row[0] . "'
      " . $vyb_tarif . " order by chronometr.smer, chronometr.chrono, chronometr.c_tarif" . "</br>"; */

    while ($row_chrono = mysql_fetch_row($sql2)) {
      if ($cislo_chrono != $row_chrono[0]) {
        $res .= conv_num_byte($row_chrono[0], 1);
        $res .= conv_num_byte($row_chrono[1], 1);
        $pocet_chrono++;
        $cislo_chrono = $row_chrono[0];
      }
      if ($row_chrono[1] == 0) {
        if (($row_chrono[3] == -1) || ($TRASA[$row_chrono[4]]->staviA == 0)) {
          $res .= conv_num_byte(0, 1);
        } else {
          $res .= conv_num_byte(1, 1);
        }
      }
      if ($row_chrono[1] == 1) {
        if (($row_chrono[3] == -1) || ($TRASA[$row_chrono[4]]->staviB == 0)) {
          $res .= conv_num_byte(0, 1);
        } else {
          $res .= conv_num_byte(1, 1);
        }
      }
      $res .= conv_num_byte($row_chrono[2], 1);
    }

    $res = conv_num_byte($pocet_chrono, 1) . $res;
    $OFFSETY[$row[0]]->chrono = strlen($resall);
    $resall .= $res;
  }

  $fileLocation = $path . "chrono.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $resall);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_LINKY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $OFFSETY;
  global $CENTRAL_POZNAMKY;

  $res = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("select jr_od, jr_do from savvy_mhdspoje.packets
           WHERE location=" . $idlocation . " and packet = " . $packet);
  $row = mysql_fetch_row($sql);

  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("select c_linky, cast(nazev_linky as char(6)), doprava, jr_od, jr_do,
           (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 0) as spoje_a,
           (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 1) as spoje_b, smera, smerb
           from savvy_mhdspoje.linky
           WHERE idlocation=" . $idlocation . " and packet = " . $packet . " and vyber = 1 and jr_do >= '" . $row[0] . "'  order by c_linkysort");
  /*  echo "select c_linky, cast(nazev_linky as char(6)), doprava, jr_od, jr_do,
    (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 0) as spoje_a,
    (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 1) as spoje_b
    from savvy_mhdspoje.linky
    WHERE idlocation=" . $idlocation . " and packet = " . $packet . " and jr_do >= '" . $row[0]  . "'  order by c_linkysort"; */
  /* echo "select c_linky, cast(nazev_linky as char(6)), doprava, jr_od, jr_do,
    (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 0) as spoje_a,
    (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 1) as spoje_b
    from savvy_mhdspoje.linky
    WHERE idlocation=" . $idlocation . " and packet = " . $packet . " order by c_linkysort"; */
  $poradi = 1;
  while ($row = mysql_fetch_row($sql)) {
    $res .= iconv('cp1250', 'ASCII//TRANSLIT', $row[1]); //$row[1];
    if ($row[2] == 'T') {
      $res .= conv_num_byte(0, 1);
    }
    if ($row[2] == 'A') {
      $res .= conv_num_byte(1, 1);
    }
    if ($row[2] == 'O') {
      $res .= conv_num_byte(2, 1);
    }
    if ($row[2] == 'L') {
      $res .= conv_num_byte(3, 1);
    }
    $res .= conv_num_byte($OFFSETY[$row[0]]->spoje, 3);
    $res .= conv_num_byte($OFFSETY[$row[0]]->chrono, 3);
    list($year, $month, $day) = explode('-', $row[3]);
    $res .= conv_num_byte($day, 1); // den jr_od
    $res .= conv_num_byte($month, 1); // mesic jr_od
    $res .= conv_num_byte(($year - 2000), 1); // rok jr_od
    list($year, $month, $day) = explode('-', $row[4]);
    $res .= conv_num_byte($day, 1); // den jr_do
    $res .= conv_num_byte($month, 1); // mesic jr_do
    $res .= conv_num_byte(($year - 2000), 1); // rok jr_do

    if ($row[5] > 0) {
      $res .= conv_num_byte(1, 1);
    } else {
      $res .= conv_num_byte(0, 1);
    }
    if ($row[6] > 0) {
      $res .= conv_num_byte(1, 1);
    } else {
      $res .= conv_num_byte(0, 1);
    }

    if ($idlocation == 1) {
      echo $poradi + ". 1</br>";
      $res .= conv_num_byte(3, 1); // ma popis smeru? 0 - ne, 1-A, 2-B, 3-AB
      setlocale(LC_CTYPE, 'cs_CZ.UTF-8');
      $res .= conv_num_byte(strlen(iconv('cp1250', 'UTF-8', $row[7])), 1); // conv_num_byte(strlen(utf8_encode($row[1])), 1);
      $res .= iconv('cp1250', 'UTF-8', $row[7]);
      setlocale(LC_CTYPE, 'cs_CZ.UTF-8');
      $res .= conv_num_byte(strlen(iconv('cp1250', 'UTF-8', $row[8])), 1); // conv_num_byte(strlen(utf8_encode($row[1])), 1);
      $res .= iconv('cp1250', 'UTF-8', $row[8]);
//    7, 8
    }


    $poradi++;
  }
  $res = conv_num_byte(($poradi - 1), 1) . $res;
  $fileLocation = $path . "linky.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $res);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_BODY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $LINKY;
  global $TRASY;


  $res = null;

  function checkPrestup($c_linky, $c_zastavky, $c_zastavky_pred, $c_zastavky_next) {
    global $LINKY;

    $TRASY = null;

    $ret = false;

//  echo $c_linky . " , " . $c_zastavky . " , " . $c_zastavky_pred . " , " . $c_zastavky_next . "</br>";

    foreach ($LINKY as $key => $TRASY) {
      if ($key != $c_linky) {
        for ($i = 0; $i < count($TRASY); $i++) {
          if ($TRASY[$i]->c_zastavky == $c_zastavky) {
            $pred = ($TRASY[$i - 1] == null) ? null : $TRASY[$i - 1]->c_zastavky;
            $next = ($TRASY[$i + 1] == null) ? null : $TRASY[$i + 1]->c_zastavky;
//          echo $key . " | " . $pred . " / " .
            if (($pred != null) && ($pred != $c_zastavky_pred)) {
              $ret = true;
            }
            if (($next != null) && ($next != $c_zastavky_next)) {
              $ret = true;
            }
          }
        }
      }
    }

    return $ret;
  }

  $res = null;
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  mysql_query("SET NAMES 'cp1250';");
  $sql = mysql_query("SELECT c_linky, c_tarif, c_zastavky, zast_a, zast_b FROM zaslinky where idlocation = " . $idlocation . " and packet = " . $packet . " order by c_linky, c_tarif;");

  while ($row = mysql_fetch_row($sql)) {
    $c_linky = $row[0];
    $c_tarif = $row[1];
    $c_zastavky = $row[2];
    $zast_a = $row[3];
    $zast_b = $row[4];

    /* if ($LINKY[$c_linky] == null) {
      $TRASY = array();
      $LINKY[$c_linky] = $TRASY;
      } else {
      $TRASY = $LINKY[$c_linky];
      } */

    $new_zastavka = new TZastavka();
    $new_zastavka->c_tarif = $c_tarif;
    $new_zastavka->c_zastavky = $c_zastavky;
    $new_zastavka->zast_A = $zast_a;
    $new_zastavka->zast_B = $zast_b;

    $LINKY[$c_linky][count($LINKY[$c_linky])] = $new_zastavka;
  }

  foreach ($LINKY as $key => $TRASY) {
//    echo $key . " : " . "</br>";
    for ($i = 0; $i < count($TRASY); $i++) {
      $TRASY[$i]->prestup = checkPrestup($key, $TRASY[$i]->c_zastavky, (($TRASY[$i - 1] == null) ? null : $TRASY[$i - 1]->c_zastavky), (($TRASY[$i + 1] == null) ? null : $TRASY[$i + 1]->c_zastavky));
//      echo "&nbsp &nbsp - " . $TRASY[$i]->c_tarif . " , " . $TRASY[$i]->c_zastavky . " | " . $TRASY[$i]->prestup . "</br>";
    }
  }

  $PRESTUPY = null;

  foreach ($LINKY as $key => $TRASY) {
    for ($i = 0; $i < count($TRASY); $i++) {
      if ($TRASY[$i]->prestup == true) {
        $PRESTUPY[$TRASY[$i]->c_zastavky] = $TRASY[$i];
      }
    }
  }

  /*  echo "</br></br>";
    echo count($PRESTUPY);
    echo "</br></br>"; */

  $res = conv_num_byte(count($PRESTUPY), 2);

  foreach ($PRESTUPY as $key => $zast) {
//    $sql = "update zaslinky set prestup = 1 where c_zastavky = " . $zast->c_zastavky . " and idlocation = " . $location . " and packet = " . $packet;
//    echo $sql . "<br>";
//    $result = $mysqli->query($sql);
//  echo $zast->c_zastavky . "</br>";
    $res .= conv_num_byte($zast->c_zastavky, 2);
  }

  $fileLocation = $path . "body.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fwrite($file, $res);
  fclose($file);
  chmod($fileLocation, 0777);
}

function exp_PRESTUPY($idlocation, $packet, $path) {
  global $con_server;
  global $con_db;
  global $con_pass;
  global $LINKY;
  global $TRASY;

  $fileLocation = $path . "prestupy.dat";
  if (!file_exists($path)) {
    mkdir($path, 0777);
  }
  $file = fopen($fileLocation, "w+");
  fclose($file);
  chmod($fileLocation, 0777);
}
?>


<script type="text/javascript">
  function progress(procento) {
    document.getElementById('progressbar').style.width = procento + "%";
    if (procento > 50) {
      document.getElementById('statustxt').style.color = "#ffffff";
    }
    document.getElementById('statustxt').innerHTML = procento + " %";
  }
</script>