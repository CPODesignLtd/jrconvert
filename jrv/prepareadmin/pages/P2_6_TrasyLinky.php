<?php

function update_spoje($location, $packet, $linka) {
  $sql = "eliminate_spoje(" . $location . ", " . $packet . ", '" . $linka . "');";
//  $mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
//  $mysqli->query("SET NAMES 'utf-8';");
//  $query = $mysqli->query($sql);
}

if ($_POST['typeaction'] == 'accept_write_tarif_zastavka') {
//  $connect = mysql_connect($con_server, $con_db, $con_pass);
//  mysql_select_db($con_db);
  $altnazevA = (($_POST['zastavkaalternazevA'] == '') ? "null" : "'" . $_POST['zastavkaalternazevA'] . "'");
  $altnazevB = (($_POST['zastavkaalternazevB'] == '') ? "null" : "'" . $_POST['zastavkaalternazevB'] . "'");
  /*    $loca = (($_POST[zastavkaloca] == '') ? "null" : "'" . $_POST[zastavkaloca] . "'");
    $locb = (($_POST[zastavkalocb] == '') ? "null" : "'" . $_POST[zastavkalocb] . "'"); */
  $pk1 = $_POST['pk1'];
  $pk2 = $_POST['pk2'];
  $pk3 = $_POST['pk3'];
  $pk4 = $_POST['pk4'];
  $pk5 = $_POST['pk5'];
  $pk6 = $_POST['pk6'];
  $sql = "UPDATE zaslinky set ALT_NAZEV_A = " . $altnazevA . ", ALT_NAZEV_B = " . $altnazevB .
          ", pk1 = " . $pk1 .
          ", pk2 = " . $pk2 .
          ", pk3 = " . $pk3 .
          ", pk4 = " . $pk4 .
          ", pk5 = " . $pk5 .
          ", pk6 = " . $pk6 .
          " where c_tarif = " . $_POST['ctarif'] . " AND c_linky = " . $_POST['clinky'] . "" .
          " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);
//  echo $sql;
}

$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");

if ((isset($_GET['unvza'])) || (isset($_GET['chvza']))) {
  $sql = "UPDATE zaslinky set " . (isset($_GET['unvza']) ? 'voz_a = 0' : 'voz_a = 1') . " where zaslinky.c_tarif = " . (isset($_GET['unvza']) ? $_GET['unvza'] : $_GET['chvza']) . " and zaslinky.c_linky = " . $_GET['l'] . " and zaslinky.idlocation =  " . getLocation($_POST["username"]) . " and zaslinky.packet = " . $_GET['pack'];
  mysql_query($sql);
  update_spoje(getLocation($_POST["username"]), $_GET['pack'], $_GET['l']);
}
if ((isset($_GET['unvzb'])) || (isset($_GET['chvzb']))) {
  $sql = "UPDATE zaslinky set " . (isset($_GET['unvzb']) ? 'voz_b = 0' : 'voz_b = 1') . " where zaslinky.c_tarif = " . (isset($_GET['unvzb']) ? $_GET['unvzb'] : $_GET['chvzb']) . " and zaslinky.c_linky = " . $_GET['l'] . " and zaslinky.idlocation =  " . getLocation($_POST["username"]) . " and zaslinky.packet = " . $_GET['pack'];
  mysql_query($sql);
  update_spoje(getLocation($_POST["username"]), $_GET['pack'], $_GET['l']);
}
if ((isset($_GET['unsta'])) || (isset($_GET['chsta']))) {
  $sql = "UPDATE zaslinky set " . (isset($_GET['unsta']) ? 'zast_a = 0' : 'zast_a = 1') . " where zaslinky.c_tarif = " . (isset($_GET['unsta']) ? $_GET['unsta'] : $_GET['chsta']) . " and zaslinky.c_linky = " . $_GET['l'] . " and zaslinky.idlocation =  " . getLocation($_POST["username"]) . " and zaslinky.packet = " . $_GET['pack'];
  mysql_query($sql);
  update_spoje(getLocation($_POST["username"]), $_GET[pack], $_GET['l']);
}
if ((isset($_GET['unstb'])) || (isset($_GET['chstb']))) {
  $sql = "UPDATE zaslinky set " . (isset($_GET['unstb']) ? 'zast_b = 0' : 'zast_b = 1') . " where zaslinky.c_tarif = " . (isset($_GET['unstb']) ? $_GET['unstb'] : $_GET['chstb']) . " and zaslinky.c_linky = " . $_GET['l'] . " and zaslinky.idlocation =  " . getLocation($_POST["username"]) . " and zaslinky.packet = " . $_GET['pack'];
  mysql_query($sql);
  update_spoje(getLocation($_POST["username"]), $_GET[pack], $_GET['l']);
}
?>

<div class="separdivglobalnapis" style="clear: both;">Zastávky k balíčku č. <?php echo $_GET['pack']; ?></div>

<?php
$sql = mysql_query("SELECT C_LINKY, NAZEV_LINKY, POPIS
          FROM linky WHERE packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]) . " order by c_linkysort");
?>

<table id="table_linka" class="t_akce" style="width: 100%;">
  <tr>
    <td class="last" style="font-size: 15px; font-weight: normal; width: 100%;">
      <div class="div_vyber" style="background-color: transparent;">
        <select id="select_linka" class="last" style="border: none;">
          <?php
          while ($row = mysql_fetch_row($sql)) {
            ?>
            <option <?php echo (($_GET['l'] == $row[0]) ? 'SELECTED' : ''); ?> value="<?php echo $row[0]; ?>"><?php echo $row[1] . ' ( ' . $row[0] . ' ) - ' . $row[2]; ?></option>
            <?php
          }
          ?>
        </select>
      </div>
    </td>
  </tr>
</table>

<div style="margin-top: 10px; margin-bottom: 10px;">
  <div class="button" id="go_linka" style="height: 35px; width: 150px; visibility: visible;" onclick="vyberLinky('?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>');">
    <span></span><img src="image/book_open.png">
    zobrazit trasu
  </div>
</div>

<?php
if (isset($_GET['l'])) {

  $sql = mysql_query("SELECT c_kodu, oznaceni, rezerva, caspozn, showing, showing1, obr
          FROM pevnykod WHERE c_kodu > 0 and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
  $pozn = null;
  while ($row = mysql_fetch_row($sql)) {
    $pozn[$row[0]] = $row;
  }

  $sql = mysql_query("select zaslinky.c_tarif, zaslinky.c_zastavky, zastavky.nazev, zaslinky.voz_a, zaslinky.voz_b, zaslinky.zast_a, zaslinky.zast_b, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3, zaslinky.pk4, zaslinky.pk5, zaslinky.pk6, zaslinky.alt_nazev_a, zaslinky.alt_nazev_b
 from zaslinky inner join zastavky on (zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet)
 where zaslinky.c_linky = \"" . $_GET['l'] . "\" and zaslinky.idlocation =  " . getLocation($_POST["username"]) . " and zaslinky.packet = " . $_GET['pack'] . " order by zaslinky.c_linky, zaslinky.c_tarif");
  ?>

  <form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=6">

    <table id="table_zastavky" class="t_akce" style="clear: both; float: none;">
      <tr>
        <th colspan="2"></th>
        <th colspan="2">vozový JŘ</th>
        <th colspan="2">zastávkový JŘ</th>
        <th></th>
        <th colspan="3">poznámky směr - A</th>
        <th colspan="3">poznámky směr - B</th>
        <th></th>
        <th colspan="2"></th>
      </tr>
      <tr>
        <th>tarifní číslo</th>
        <th>název zastávky</th>
        <th>směr - A</th>
        <th>směr - B</th>
        <th>směr - A</th>
        <th>směr - B</th>
        <th></th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th></th>
        <th>alter. název zastávky A</th>
        <th>alter. název zastávky B</th>
      </tr>
      <?php
      while ($row = mysql_fetch_row($sql)) {
        ?>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>; width: auto;"><?php echo $row[0]; ?></td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;"><?php echo $row[2]; ?></td>
          <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <a style="color: #ffffff; word-wrap: nowrap;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[3] == 1 ? 'unvza' : 'chvza'); ?>=<?php echo $row[0]; ?>&l=<?php echo $_GET['l']; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat" onClick="app_href(this);"><img src="<?php echo ($row[3] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
          </td>
          <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <a style="color: #ffffff; word-wrap: nowrap;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[4] == 1 ? 'unvzb' : 'chvzb'); ?>=<?php echo $row[0]; ?>&l=<?php echo $_GET['l']; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat" onClick="app_href(this);"><img src="<?php echo ($row[4] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
          </td>
          <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <a style="color: #ffffff; word-wrap: nowrap;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[5] == 1 ? 'unsta' : 'chsta'); ?>=<?php echo $row[0]; ?>&l=<?php echo $_GET['l']; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat" onClick="app_href(this);"><img src="<?php echo ($row[5] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
          </td>
          <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <a style="color: #ffffff; word-wrap: nowrap;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[6] == 1 ? 'unstb' : 'chstb'); ?>=<?php echo $row[0]; ?>&l=<?php echo $_GET['l']; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat" onClick="app_href(this);"><img src="<?php echo ($row[6] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET[ct])) || ($_GET[ct] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {

            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-style: italic; font-weight: normal; width: auto;">---</td>
                  <td></td>
                </tr>
                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-style: italic; font-weight: normal; width: auto;"><?php echo $row1[1]; ?></td>
                    <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;">
                      <?php
                      if ($row1[3] != null) {
                        ?>
                        <img src="../pictogram/<?php echo $row1[3]; ?>">
                        <?php
                      }
                      ?>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[7]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[7]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[7]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk1" value="0" <?php echo (($row[7] == "") || ($row[7] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>
                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk1" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[7]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[8]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[8]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[8]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk2" value="0" <?php echo (($row[8] == "") || ($row[8] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>

                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk2" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[8]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[9]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[9]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[9]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk3" value="0" <?php echo (($row[9] == "") || ($row[9] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>

                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk3" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[9]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[10]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[10]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[10]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk4" value="0" <?php echo (($row[10] == "") || ($row[10] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>

                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk4" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[10]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[11]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[11]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[11]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk5" value="0" <?php echo (($row[11] == "") || ($row[11] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>

                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk5" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[11]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
              if ($pozn[$row[12]][6] != null) {
                ?>
                <img src="../pictogram/<?php echo $pozn[$row[12]][6]; ?>">
                <?php
              } else {
                echo $pozn[$row[12]][1];
              }
            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                    <input type="radio" name="pk6" value="0" <?php echo (($row[12] == "") || ($row[12] == 0)) ? 'checked' : ''; ?>>
                  </td>
                </tr>

                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="pk6" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[12]) ? 'checked' : ''; ?>>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
            <?php
            if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {

            } else {
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: auto;">
                <tr style="height: 26px;">
                  <td class="last" style="font-size: 15px; width: auto; font-weight: normal; white-space: pre-wrap">BEZ POZNÁMKY</td>
                </tr>
                <?php
                $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr
                      from pevnykod where caspozn = 0 and showing = 1 and showing1 = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
                while ($row1 = mysql_fetch_row($sql1)) {
                  ?>
                  <tr style="height: 26px;">
                    <td class="last" style="font-size: 8px; width: auto; font-weight: normal; white-space: pre-wrap"><?php echo $row1[2]; ?></td>
                  </tr>
                  <?php
                }
                ?>
              </table>
              <?php
            }
            ?>
          </td>
          <?php
          if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
            ?>
            <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;"><?php echo $row[13]; ?>
              <?php
            } else {
              ?>
            <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
              <input type="text" name="zastavkaalternazevA" id="zastavkanazev" style="width: 100%;" value="<?php echo $row[13]; ?>"></input>
              <?php
            }
            ?>
          </td>
          <?php
          if ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) {
            ?>
            <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;"><?php echo $row[14]; ?>
              <?php
            } else {
              ?>
            <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>;">
              <input type="text" name="zastavkaalternazevB" id="zastavkanazev" style="width: 100%;" value="<?php echo $row[14]; ?>"></input>
              <?php
            }
            ?>
          </td>
          <td class="first" style="vertical-align: <?php echo ((!isset($_GET['ct'])) || ($_GET['ct'] != $row[0])) ? "middle" : "top"; ?>">
            <?php
            if (!isset($_GET['ct'])) {
              ?>
              <a name="edit_zastavky" style="color: #ffffff;" href="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=<?php echo $_GET['sub']; ?>&ct=<?php echo $row[0]; ?>&l=<?php echo $_GET['l']; ?>" onClick="app_href(this);" title="Editace záznamu"><img src="image/pencil.png"></a>
              <?php
            } else {
              if ($_GET['ct'] == $row[0]) {
                ?>
                <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['typeaction'].value = 'accept_write_tarif_zastavka'; document.frm.action = document.frm.action + '&l=<?php echo $_GET['l']; ?>&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>
                &nbsp;
                <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&l=<?php echo $_GET['l']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>
                <input id="czastavky" name="ctarif" type="text" value="<?php echo $row[0]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                <input id="czastavky" name="clinky" type="text" value="<?php echo $_GET['l']; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                <?php
              }
            }
            ?>
          </td>
        </tr>
        <?php
      }
      ?>
    </table>
  </form>

  <?php
}
?>

<script  type='text/javascript'>
  function vyberLinky(h) {
    document.location.href = h + "&l=" + document.getElementById('select_linka').value;
  }
</script>