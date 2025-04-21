<?php

require_once 'Vector.php';

$linka = $_GET['linka'];
$kurz = $_GET['kurz'];
$kurzname = $_GET['kurzname'];
$kodpozn = $_GET['kodpozn'];
$location = $_GET['location'];
$packet = $_GET['packet'];

function getPart($location, $packet, $linka, $kurz, $kurzname, $kodpozn, $nextLinka, $nextKurz, $PoznamkyList, $VybranePoznList) {
  $nextLinka = '';
  $nextKurz = '';
  $sql_spoje_zastavky = "select zastavky.c_zastavky, zastavky.nazev, zastavky.loca, zastavky.locb, zaslinky.c_tarif, zastavky.zkratka from zaslinky inner join zastavky on
    (zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and
    (zaslinky.voz_a = 1 or zaslinky.voz_b = 1) and zaslinky.c_linky = '" . $linka . "';";
/*  $sql_spoje_zastavky = "select c_tarif, zkratka from zaslinky inner join zastavky on
    (zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
    where zaslinky.idlocation = " . $location . " and zaslinky.packet = " . $packet . " and
    (zaslinky.voz_a = 1 or zaslinky.voz_b = 1) and zaslinky.c_linky = '" . $linka . "';";*/
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_spoje_zastavky);

  $zastavky = null;
  while ($sql_spoje_zastavky = $query1->fetch_row()) {
    $zastavky[$sql_spoje_zastavky[4]] = $sql_spoje_zastavky;
  }

  $sql_spoje_odjezdy = "call SpojeKurzyLinkyOdjezdy('" . $linka . "', '" . $kurz . "', " . $kodpozn . ", " . $location . ", " . $packet . ");";

  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_spoje_odjezdy);
  if ($query1->num_rows > 0) {

// -------- zahlavi zastavky ----------
  $reshlavicka = '';
  $reshlavicka = $reshlavicka . "<tr class = 'cell_time_jr_zahlavi'>";
//  $reshlavicka = $reshlavicka . "<td id='cj'>";
//  $reshlavicka = $reshlavicka . "c";
//  $reshlavicka = $reshlavicka . "</td>";
// -------- END zahlavi zastavky ----------
// -------- ODJEZDY ----------
  $spoj = -1;
  $mamtr = 0;
  $c_spoje = -1;
  $i = 0;
  $resjr = '';
  while ($row_spoje_odjezdy = $query1->fetch_row()) {
    if ($c_spoje != $row_spoje_odjezdy[0]) {
      $c_spoje = $row_spoje_odjezdy[0];
      $spoj++;
      if ($mamtr == 1) {
        if ($spoj % 2 == 0) {
          $resjr = $resjr . "<td class = 'licha_jr_last'>";
        } else {
          $resjr = $resjr . "<td class = 'suda_jr_last'>";
        }
//        $resjr = $resjr . "e";
        $resjr = $resjr . "</td>";
        $resjr = $resjr . "</tr>";
        $mamtr = 0;
      }
      if ($spoj % 2 == 0) {
        $resjr = $resjr . "<tr id='jr" . $spoj . "' class = 'suda_jr'>";
      } else {
        $resjr = $resjr . "<tr id='jr" . $spoj . "' class = 'licha_jr'>";
      }
//      $resjr = $resjr . "<td id='cj'>";
//      $resjr = $resjr . "b";
//      $resjr = $resjr . "</td>";
      $mamtr = 1;
    }

    if ($spoj == 0) {
      $reshlavicka = $reshlavicka . "<td id='cj'>";
      $zast = "\"" . $zastavky[$row_spoje_odjezdy[19]][1] . (($location == 17) ? iconv('ISO-8859-2', 'UTF-8', ", Plzeò") : (($location == 11) ? iconv('ISO-8859-2', 'UTF-8', ", Opava") : (($location == 5) ? iconv('ISO-8859-2', 'UTF-8', ", Tøebíè") : ""))) . "\"";
      $reshlavicka = $reshlavicka . "<img src='http://www.mhdspoje.cz/jrw50/css/praporB.png' onClick='selfobj.map(" . $zast . ", " . (($zastavky[$row_spoje_odjezdy[19]][2] == '') ? 'null': $zastavky[$row_spoje_odjezdy[19]][2]) . ", " . (($zastavky[$row_spoje_odjezdy[19]][3] == '') ? 'null': $zastavky[$row_spoje_odjezdy[19]][3]) . ", " . $zastavky[$row_spoje_odjezdy[19]][0] . ");'>&nbsp;</img>";
      $reshlavicka = $reshlavicka . iconv('UTF-8', 'UTF-8', $zastavky[$row_spoje_odjezdy[19]][5]);
      $reshlavicka = $reshlavicka . "</td>";
    }

    $resjr = $resjr . "<td id='cj'>";
    $resjr = $resjr . $row_spoje_odjezdy[16] . ':' . $row_spoje_odjezdy[17];

    if ($row_spoje_odjezdy[16] != '--') {
    $resjr = $resjr . "&nbsp";
            $textpozn = FALSE;
//            if ($JRType->odjezdy->elementAt($i)->elementAt($ii) != NIL) {
              for ($pki = 28; $pki <= 36; $pki++) {
                if (($row_spoje_odjezdy[$pki] != 0) && ($PoznamkyList[$row_spoje_odjezdy[$pki]] != NULL)) {
                  if (in_array((string) $row_spoje_odjezdy[$pki], $VybranePoznList, FALSE) == FALSE) {
                    $VybranePoznList[] = $row_spoje_odjezdy[$pki];
                  }
                  if ($textpozn == TRUE) {
                    $resjr = $resjr . ", ";
                  }
                  if ($PoznamkyList[$row_spoje_odjezdy[$pki]][5] == '') {
                    $resjr = $resjr . "<a class='a_pozn' title = '" . $PoznamkyList[$row_spoje_odjezdy[$pki]][2] . "'>";
                    $resjr = $resjr . $PoznamkyList[$row_spoje_odjezdy[$pki]][1];
                    $resjr = $resjr . "</a>";
                  } else {
                    /*$resjr = $resjr . "<a class='a_pozn' title = '" . $PoznamkyList[$row_spoje_odjezdy[$pki]][2] . "'>";
                    $resjr = $resjr . $PoznamkyList[$row_spoje_odjezdy[$pki]][1] . $PoznamkyList[$row_spoje_odjezdy[$pki]][5];
                    $resjr = $resjr . "</a>";*/
                    $resjr = $resjr . "<img id = 'img_poznamka' class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $PoznamkyList[$row_spoje_odjezdy[$pki]][5] . "' title = '" . $PoznamkyList[$row_spoje_odjezdy[$pki]][2] . "'></ing>";
                  }
                  $textpozn = TRUE;
                }
              }
//            }
    }

    $resjr = $resjr . "</td>";

    $nextLinka = $row_spoje_odjezdy[23];
    $nextKurz = $row_spoje_odjezdy[24];
  }
// -------- END ODJEZDY ----------

  if ($spoj % 2 == 0) {
    $resjr = $resjr . "<td class = 'suda_jr_last'>";
//    $resjr = $resjr . "e";
  } else {
    $resjr = $resjr . "<td class = 'licha_jr_last'>";
//    $resjr = $resjr . "e";
  }
  $resjr = $resjr . "</tr>";
  $reshlavicka = $reshlavicka . "<td class = 'cell_time_jr_zahlavi_last'>";
//  $reshlavicka = $reshlavicka . "a";
  $reshlavicka = $reshlavicka . "</td>";
  $reshlavicka = $reshlavicka . "</tr>";


  $sql_linka = "select * from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = '" . $linka . "';";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_linka);

  $sql_linka = $query1->fetch_row();

  $sql_kurz = "select kurz from spoje
    where spoje.idlocation = " . $location . " and spoje.packet = " . $packet . " and
    spoje.c_linky = '" . $linka . "' and spoje.idkurz = " . $kurz . " group by spoje.kurz;";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query2 = $mysqli->query($sql_kurz);

  $sql_kurz = $query2->fetch_row();

  $reshlavickalinka = '';
  $reshlavickalinka = $reshlavickalinka . "<tr class = 'cell_time_jr_zahlavi'>";
//  $reshlavickalinka = $reshlavickalinka . "<td id='cj'>";
//  $reshlavickalinka = $reshlavickalinka . "</td>";
  $reshlavickalinka = $reshlavickalinka . "<td class = 'cell_time_jr_zahlavi_last' colspan='" . (count($zastavky) + 1) . "'>";
  $reshlavickalinka = $reshlavickalinka . "<div class = 'div_ram_nobackground'>";
  $reshlavickalinka = $reshlavickalinka . iconv('ISO-8859-2', 'UTF-8', "Pøejezd na linku ") . iconv('UTF-8', 'UTF-8', $sql_linka[1]) . iconv('ISO-8859-2', 'UTF-8', " , Kurz ") . iconv('UTF-8', 'UTF-8', $sql_kurz[0]);
  $reshlavickalinka = $reshlavickalinka . "</div>";
  $reshlavickalinka = $reshlavickalinka . "</td>";
//  $reshlavickalinka = $reshlavickalinka . "<td class = 'cell_time_jr_zahlavi_last'>";
//  $reshlavickalinka = $reshlavickalinka . "</td>";
  $reshlavickalinka = $reshlavickalinka . "</tr>";

  $res1 = '';
//  $res1 = $res1 . "<tr>";
//  $res1 = $res1 . "<td>";
  $res1 = $res1 . "<div class = 'div_body_JR'>";
  $res1 = $res1 . "<div class = 'div_ram_nobackground'>";
  $res1 = $res1 . "<table class = 'table_time_JR' style='text-align:left'>";

  $res2 = '';
  $res2 = $res2 . "</table>";
  $res2 = $res2 . "</div>";
  $res2 = $res2 . "</div>";
//  $res2 = $res2 . "</td>";
//  $res2 = $res2 . "</tr>";

  return $res1 . $reshlavickalinka . $reshlavicka . $resjr . $res2;
  } else {
    return '';
  }
}




if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
  echo 'Could not connect to database';
} else {
  $dbname = 'savvy_mhdspoje';

  mysql_query("SET NAMES 'utf-8';");
  mysql_select_db($dbname);

  $PoznamkyList = NULL;

//  $sql_poznamky = "select * from pevnykod where pevnykod.idlocation = " . $location . " and pevnykod.packet = " . $packet . " and pevnykod.I_P = 1 and pevnykod.showing = 1 ORDER BY pevnykod.c_kodu;";
  $sql_poznamky = "select * from pevnykod where pevnykod.idlocation = " . $location . " and pevnykod.packet = " . $packet . " and pevnykod.I_P = 1 ORDER BY pevnykod.c_kodu;";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_poznamky);

  while ($row_poznamky = $query1->fetch_row()) {
    $PoznamkyList[$row_poznamky[0]] = $row_poznamky;
  }

/*  $sql_spoje = "call SpojeKurzyLinky('" . $linka . "', '" . $kurz . "', " . $kodpozn . ", " . $location . ", " . $packet . ");";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_spoje);*/

  /*  while ($row_spoje = $query1->fetch_row()) {
    echo $row_spoje[0] . ' , ' . $row_spoje[1] . ' , ' . $row_spoje[2] . ' , ' . $row_spoje[3] . ' , (' . $row_spoje[24] . ' , ' . $row_spoje[25] . ') , ' . $row_spoje[18] . ' , ' . $row_spoje[19] . ' , ' . $row_spoje[23] . '</br>';
    } */

  $sql_linka = "select * from linky
    where linky.idlocation = " . $location . " and linky.packet = " . $packet . " and linky.c_linky = '" . $linka . "';";
  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
  $mysqli->query("SET NAMES 'utf-8';");
  $query1 = $mysqli->query($sql_linka);

  $sql_linka = $query1->fetch_row();

  $res = '';
  $res = $res . "<div class = 'div_pozadikomplex'>";
    $res = $res . "<div id='movediv' class='movediv'>";

      $res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJR();'></img>";
//    $res = $res . "<img class='wclose' style='float:left;' src='http://www.mhdspoje.cz/jrw50/image/printer_red.png' onClick='" . "printJR(" . $Linka->idlinky . ", " . $Smer . ", " . $Tarif . ", " . $Location . ", " . $Packet . ", " . (($denniJR) ? '1' : '0') . ", \"" . date_format(new DateTime($datumJR), 'd_m_Y') . "\", " . (($sdruzJR) ? '1' : '0') . ", null, null, null, 0, 0);'></img>";
    $res = $res . "</div>";

    $res = $res . "<table id='tablejr' class = 'tablejr'>";

      $res = $res . "<tr>";
        $res = $res . "<td>";
  /*      if ($t > 0) {
    $res = $res . "<div class = 'div_separ'></div>";
    } */

//--- header linka ---
          $res = $res . "<table style = 'width: 100%;'>";

            $res = $res . "<tr>";

              $res = $res . "<td class = 'td_ram_nobackground' style = 'min-width: 70px;'>";
                $res = $res . "<a class = 'a_nazev_linky'>";
                $res = $res . iconv('UTF-8', 'UTF-8', $sql_linka[1]);
                $res = $res . "</a>";
              $res = $res . "</td>";

              $res = $res . "<td class = 'td_ram_nobackground' style = 'min-width: 70px;'>";
                $res = $res . "<a class = 'a_nazev_linky'>";
                $res = $res . "/";
                $res = $res . "</a>";
              $res = $res . "</td>";

              $res = $res . "<td class = 'td_ram_nobackground' style = 'min-width: 70px;'>";
                $res = $res . "<a class = 'a_nazev_linky'>";
                $res = $res . iconv('UTF-8', 'UTF-8', $kurzname);
                $res = $res . "</a>";
              $res = $res . "</td>";

              $res = $res . "<td class = 'td_ram_nobackground'>";
                if ($sql_linka[9] == 'T') {
                  $res = $res . "<div class = 'div_logo_doprava_T'></div>";
                }
                if ($sql_linka[9] == 'O') {
                  $res = $res . "<div class = 'div_logo_doprava_O'></div>";
                }
                if ($sql_linka[9] == 'A') {
                  $res = $res . "<div class = 'div_logo_doprava_A'></div>";
                }
              $res = $res . "</td>";

              $res = $res . "<td class = 'td_ram_nobackground' style = 'text-align: left; min-width: 70px; width: 100%'>";
/*                $res = $res . "<a class = 'a_smer_linky_label'>";
                $res = $res . iconv('ISO-8859-2', 'UTF-8', "Smìr :");
                $res = $res . "</a>";
                $res = $res . "<a class = 'a_smer_linky'>";*/
                //      if ($Smer == 0) {
//                $res = $res . "popis smeru";
                /*      } else {
                $res = $res . $Linka->smerB;
                } */
//                $res = $res . "</a>";
              $res = $res . "</td>";

            $res = $res . "</tr>";

          $res = $res . "</table>";

        $res = $res . "</td>";

      $res = $res . "</tr>";

//2      $res = $res . "<tr>";
//2        $res = $res . "<td>";

//2          $res = $res . "<div class = 'div_body_JR'>";
//1            $res = $res . "<table class = 'table_JR' style='width: 100%'>";
//1              $res = $res . "<tr>";

//1                $res = $res . "<td class = 'cell_zastavky'>";
//1                  $res = $res . "<table class='t_in'><tr><td>";

//2                    $res = $res . "<div class = 'div_ram_nobackground'>";
//2                      $res = $res . "<table class = 'table_time_JR' style='text-align:left'>";

/*  $res = $res . $reshlavicka;
  $res = $res . $resjr;*/

  $nextLinka = $linka;
  $nextKurz = $kurz;
  $VybranePoznList[] = NULL;
/*  echo '</br>';
  echo $nextLinka . '/' . $nextKurz . '</br>';*/
  while (($nextLinka != '') && ($nextKurz != '')) {
    $res = $res . "<tr>";
      $res = $res . "<td>";
        $res = $res . getPart($location, $packet, $linka, $kurz, $kurzname, $kodpozn, &$nextLinka, &$nextKurz, $PoznamkyList, &$VybranePoznList);
      $res = $res . "</td>";
    $res = $res . "</tr>";
//  $res = $res . getPart($location, $packet, $linka, $kurz, $kurzname, $kodpozn, &$nextLinka, &$nextKurz);
/*  echo '</br>';
  echo $nextLinka . '/' . $nextKurz . '</br>';*/
  $linka = $nextLinka;
  $kurz = $nextKurz;
  }
//  $res = $res . getPart($location, $packet, $linka, 10, $kurzname, $kodpozn, &$nextLinka, &$nextKurz);

//2                      $res = $res . "</table>";
//2                    $res = $res . "</div>";
//                  $res = $res . "</table>";

//                $res = $res . "</td>";
//              $res = $res . "</tr>";
//            $res = $res . "</table>";
//2          $res = $res . "</div>";
//2        $res = $res . "</td>";
//2      $res = $res . "</tr>";
    $res = $res . "</table>";

    $res = $res . "<div class = 'div_FS'>";
    $res = $res . "<a class = 'a_FS'>";
    $res = $res . iconv('ISO-8859-2', 'UTF-8', "JRw ver. 5.0. - SKELETON &reg FS software s.r.o.");
    $res = $res . "</a>";
    $res = $res . "</div>";

    $res = $res . "<div>";
    $res = $res . "<table class = 'table_global_poznamky'>";
    for ($i = 0; $i < count($VybranePoznList); $i++) {
      $res = $res . "<tr>";
      $res = $res . "<td style='padding-left: 10px;'>";
      if ($PoznamkyList[$VybranePoznList[$i]][5] == '') {
        $res = $res . $PoznamkyList[$VybranePoznList[$i]][1];
      } else {
        $res = $res . "<img class = 'img_poznamka' src = 'http://www.mhdspoje.cz/jrw20/png/" . $PoznamkyList[$VybranePoznList[$i]][5] . "'></ing>";
      }
      $res = $res . "</td>";
      $res = $res . "<td style='white-space: normal; padding-left:10px;'>";
      $res = $res . $PoznamkyList[$VybranePoznList[$i]][2];
      $res = $res . "</td>";
      $res = $res . "</tr>";
    }
    $res = $res . "</table>";
    $res = $res . "</div>";

  $res = $res . "</div>";

  mysql_close($p);
}

$resfinal = '';

$resfinal = $resfinal . $res;

echo $_GET['callback'] . "(" . json_encode($resfinal) . ");";
?>