<?php

$postTypeAction = RequestProvider::GetPostValueByName('typeaction', "undefined");

if ($postTypeAction == 'order_write_linka') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  for ($i = 0; $i < sizeof($_POST['order']); $i++) {
    $sql = "UPDATE linky set C_LINKYSORT = " . $_POST['order'][$i] . " where C_LINKY = " . $_POST[idlinky][$i] . " AND idlocation = " . getLocation($_POST["username"]) .
           " AND packet = " . $_GET['pack'];
    mysql_query($sql);
//    echo $sql;
  }
}

if ($postTypeAction == 'accept_write_linka') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  $smera = (($_POST['linkasmera'] == '') ? "null": "'" . $_POST['linkasmera'] . "'");
  $smerb = (($_POST['linkasmerb'] == '') ? "null": "'" . $_POST['linkasmerb'] . "'");
  $popis = (($_POST['linkapopis'] == '') ? "null": "'" . $_POST['linkapopis'] . "'");
  $sql = "UPDATE linky set NAZEV_LINKY = '" . $_POST['linkanazev'] . "', DOPRAVA = '" . $_POST['linkadoprava'] .
         "', SMERA = " . $smera . ", SMERB = " . $smerb . ", jr_od = '" . $_POST['dod'] . "', jr_do = '" . $_POST['ddo'] . "', popis = " . $popis . " where C_LINKY = \"" . $_POST['clinky'] .
         "\" AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);
  $infoa = (($_POST['linkapoznA'] == '') ? null: "'" . $_POST['linkapoznA'] . "'");
  $infob = (($_POST['linkapoznB'] == '') ? null: "'" . $_POST['linkapoznB'] . "'");
  $sql = "DELETE FROM info where C_LINKY = \"" . $_POST['clinky'] .
         "\" AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'] . " AND smer = 0;";
  mysql_query($sql);
//  echo $sql . "</br>";
  $sql = "DELETE FROM info where C_LINKY = \"" . $_POST['clinky'] .
         "\" AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'] . " AND smer = 1;";
  mysql_query($sql);
//  echo $sql . "</br>";
  if ($infoa != null) {
    $sql =  "INSERT INTO info (C_LINKY, C_TARIF, INFO, IDLOCATION, PACKET, SMER) VALUES (" .
          "\"" . $_POST['clinky'] . "\", -1, " . $infoa . ", " . getLocation($_POST["username"]) . "," . $_GET['pack'] . ",0);";
    mysql_query($sql);
//    echo $sql . "</br>";
  }
  if ($infob != null) {
    $sql =  "INSERT INTO info (C_LINKY, C_TARIF, INFO, IDLOCATION, PACKET, SMER) VALUES (" .
          "\"" . $_POST['clinky'] . "\", -1, " . $infob . ", " . getLocation($_POST["username"]) . "," . $_GET['pack'] . ",1);";
    mysql_query($sql);
//    echo $sql . "</br>";
  }
//  echo $sql;
}

//$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'utf8' COLLATE 'utf8';");
$sql = mysql_query("SELECT c_linky, doprava, nazev_linky, smera, smerb, jr_od, jr_do, c_linkysort, popis,
          (select info from info where info.c_linky = linky.c_linky and info.idlocation = " . getLocation($_POST["username"]) . " and info.packet = " . $_GET['pack'] . " and smer = 0) as infoa,
          (select info from info where info.c_linky = linky.c_linky and info.idlocation = " . getLocation($_POST["username"]) . " and info.packet = " . $_GET['pack'] . " and smer = 1) as infob,
          vyber
          FROM linky WHERE packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]) . " order by c_linkysort");
/*echo "SELECT c_linky, doprava, nazev_linky, smera, smerb, jr_od, jr_do, c_linkysort, popis,
          (select info from info where info.c_linky = linky.c_linky and info.idlocation = " . getLocation($_POST["username"]) . " and info.packet = " . $_GET['pack'] . " and smer = 0) as infoa,
          (select info from info where info.c_linky = linky.c_linky and info.idlocation = " . getLocation($_POST["username"]) . " and info.packet = " . $_GET['pack'] . " and smer = 1) as infob,
          FROM linky WHERE packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]) . " order by c_linkysort" . "</br>";*/
?>

<div class="separdivglobalnapis" style="clear: both;">Linky k balíčku č. <?php echo $_GET['pack']; ?></div>

<form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=1">
<link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
<table>
  <tr>
<?php
  if (!isset($_GET['cl'])) {
?>
<input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; height: 0px; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
<!--  <input type="button" name="order_button" style="margin: 8px 4px 8px 4px; float: left;" onClick="visibledisable_elements('order_button'); visibledisable_elements('edit_linky'); visibledisable_elements('post');" value="změna pořadí">-->
<td>
<div class="button" id="change_order_button" style="height: 35px; width: 150px; visibility: visible;" onclick="document.getElementById('change_order_button').style.visibility='hidden'; visibledisable_elements('order_button'); visibledisable_elements('edit_linky'); document.getElementById('storno_button').style.visibility='visible'; document.getElementById('zapsat_button').style.visibility='visible';">
  <span></span><img src="image/sort_number.png">
  změna pořadí
</div>
</td>
<?php
  }
?>
<td>
<!--  <input type="submit" name="post" style="margin: 8px 4px 8px 4px; visibility: hidden;" value="Zapsat">-->
<div class="button" id="zapsat_button" style="height: 35px; width: 150px; visibility: hidden; border-color: #3C7FB1;" onclick="document.frm['typeaction'].value = 'order_write_linka'; document.frm.submit();">
  <span></span><img src="image/accept.png">
  Zapsat
</div>
</td>
<td>
<!--  <input type="submit" name="post" style="margin: 8px 4px 8px 4px; visibility: hidden;" value="Storno">-->
<div class="button" id="storno_button" style="height: 35px; width: 150px; visibility: hidden;  border-color: #3C7FB1;" onclick="document.frm.submit();">
  <span></span><img src="image/abort.png">
  Storno
</div>
</td>
  </tr>
</table>
<!--  <script type="text/javascript" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>-->

  <table id="table_linky" class="t_akce" style="clear: both; float: none;">
    <tr>
      <th>č. linky</th>
      <th colspan="2">doprava</th>
      <th>název linky</th>
      <th>směr A</th>
      <th>směr B</th>
      <th>popis linky</th>
      <th>platná OD</th>
      <th>platná DO</th>
      <th>poznámky sm. A</th>
      <th>poznámky sm. B</th>
    </tr>
    <?php
    $i = 1;
    while ($row = mysql_fetch_row($sql)) {
      list($year, $month, $day) = explode('-', $row[5]);
      $l_od = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
/*      $mk = mktime(0, 0, 0, $month, $day, $year);
      $l_od = date('d.m.Y', $mk);*/

      list($year, $month, $day) = explode('-', $row[6]);
      $l_do = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
/*      $mk = mktime(0, 0, 0, $month, $day, $year);
      $l_do = date('d.m.Y', $mk);*/
      ?>
      <tr id="table_linky_row<?php echo $i; ?>">
        <td class="last" style="font-size: 15px; font-weight: normal; font-style: italic; width: auto;"><?php echo $row[0]; ?>
          <input name="order[]" style="width: 0px; visibility: hidden;" value="<?php echo $row[7]; ?>">
          <input name="idlinky[]" style="width: 0px; visibility: hidden;" value="<?php echo $row[0]; ?>">
        </td>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($row[1] != null) {
            ?>
            <img src="../image/<?php
          if ($row[1] == 'T') {
            echo 'autobus.png';
          }
          if ($row[1] == 'O') {
            echo 'trolejbus.png';
          }
          if ($row[1] == 'A') {
            echo 'tramvaj.png';
          }
            ?>">
          <?php
          }
          ?>
        </td>
          <?php
          if ((isset($_GET['cl'])) && ($_GET['cl'] == $row[0])) {
          ?>
            <td class="last" style="font-size: 15px; font-weight: bold; width: auto;  min-width: 90px; text-align: left; vertical-align: middle;">
<!--              <div class="div_vyber" style="background-color: transparent;">-->
                <select name="linkadoprava" id="linkadoprava" class="last" style="border: none; min-width: 100px;">
                  <option value="T" <?php echo ($row[1] == 'T') ? 'SELECTED': ''; ?>>Autobus</option>
                  <option value="O" <?php echo ($row[1] == 'O') ? 'SELECTED': ''; ?>>Trolejbus</option>
                  <option value="A" <?php echo ($row[1] == 'A') ? 'SELECTED': ''; ?>>Tramvaj</option>
                  <option value="L" <?php echo ($row[1] == 'L') ? 'SELECTED': ''; ?>>Lanovka</option>
                </select>
<!--              </div>-->
            </td>
          <?php
          } else {
          ?>
            <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: left; vertical-align: middle;"><?php echo $row[1]; ?></td>
          <?php
          }
          ?>
        <?php
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[2]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
            <input type="text" name="linkanazev" id="linkanazev" style="width: 100%;" value="<?php echo $row[2]; ?>">
          </td>
        <?php
        }
        ?>
        <?php
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;"><?php echo $row[3]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;">
            <input type="text" name="linkasmera" id="linkasmera" style="width: 100%;" value="<?php echo $row[3]; ?>">
          </td>
        <?php
        }
        ?>
        <?php
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;"><?php echo $row[4]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;">
            <input type="text" name="linkasmerb" id="linkasmerb" style="width: 100%;" value="<?php echo $row[4]; ?>">
          </td>
        <?php
        }
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;"><?php echo $row[8]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="width: 10%; word-wrap: break-word; white-space: normal;">
            <input type="text" name="linkapopis" id="linkapopis" style="width: 100%;" value="<?php echo $row[8]; ?>">
          </td>
        <?php
        }
        ?>
        <td class="last" style="width: auto;">
          <?php
          if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
          ?>
            <?php echo $l_od; ?>
          <?php
          } else {
          ?>
            <div id="select_datum_od_<?php echo $row[0]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
              <span style="vertical-align: middle; height: 100%; cursor: pointer;">
              <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
              <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_od_<?php echo $row[0]; ?>"></a>
              <input id="dod" name="dod" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              </span>
            </div>
            <script type="text/javascript">
              var Kalend_od_<?php echo $row[0]; ?> = new JRKalendar('select_datum_od_<?php echo $row[0]; ?>', 'a_select_datum_od_<?php echo $row[0]; ?>', null);
              Kalend_od_<?php echo $row[0]; ?>.initialize('<?php echo $row[5]; ?>', 'Kalend_od_<?php echo $row[0]; ?>');
              Kalend_od_<?php echo $row[0]; ?>.settoall();
              Kalend_od_<?php echo $row[0]; ?>.setZIndex(100001);
            </script>
          <?php
          }
          ?>
        </td>
        <td class="last" style="width: auto;">
          <?php
          if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
          ?>
            <?php echo $l_do; ?>
          <?php
          } else {
          ?>
            <div id="select_datum_do_<?php echo $row[0]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
              <span style="vertical-align: middle; height: 100%; cursor: pointer;">
              <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
              <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_do_<?php echo $row[0]; ?>"></a>
              <input id="ddo" name="ddo" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              </span>
            </div>
            <script type="text/javascript">
              var Kalend_do_<?php echo $row[0]; ?> = new JRKalendar('select_datum_do_<?php echo $row[0]; ?>', 'a_select_datum_do_<?php echo $row[0]; ?>', null);
              Kalend_do_<?php echo $row[0]; ?>.initialize('<?php echo $row[6]; ?>', 'Kalend_do_<?php echo $row[0]; ?>');
              Kalend_do_<?php echo $row[0]; ?>.settoall();
              Kalend_do_<?php echo $row[0]; ?>.setZIndex(100001);
            </script>
          <?php
          }
          ?>
        </td>
        <?php
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;"><?php echo $row[9]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="width: 10%; word-wrap: break-word; white-space: normal;">
            <textarea style="width: 100%;" id="linkapoznA" name="linkapoznA" rows="4" value=""><?php echo $row[9]; ?></textarea>
          </td>
        <?php
        }
        ?>
        <?php
        if ((!isset($_GET['cl'])) || ($_GET['cl'] != $row[0])) {
        ?>
          <td class="last" style="width: auto; word-wrap: break-word; white-space: normal;"><?php echo $row[10]; ?></td>
        <?php
        } else {
        ?>
          <td class="last" style="width: 10%; word-wrap: break-word; white-space: normal;">
            <textarea style="width: 100%;" id="linkapoznB" name="linkapoznB" rows="4" value=""><?php echo $row[10]; ?></textarea>
          </td>
        <?php
        }
        ?>
        <td class="first">
        <?php
          if (!isset($_GET['cl'])) {
        ?>
            <a name="edit_linky" style="color: #ffffff;" href="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=<?php echo $_GET['sub']; ?>&cl=<?php echo $row[0]; ?>" onClick="app_href(this);" title="Editace linky"><img src="image/pencil.png"></a>
        <?php
          } else {
            if ($_GET['cl'] == $row[0]) {
        ?>
              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['dod'].value = Kalend_od_<?php echo $row[0]; ?>.y + '-' + Kalend_od_<?php echo $row[0]; ?>.m + '-' + Kalend_od_<?php echo $row[0]; ?>.d; document.frm['ddo'].value = Kalend_do_<?php echo $row[0]; ?>.y + '-' + Kalend_do_<?php echo $row[0]; ?>.m + '-' + Kalend_do_<?php echo $row[0]; ?>.d; document.frm['typeaction'].value = 'accept_write_linka'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>
              &nbsp;
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>
              <input id="clinky" name="clinky" type="text" value="<?php echo $row[0]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
        <?php
            }
          }
        ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; visibility: hidden;">
          <img name="order_button" style="visibility: hidden;" title="Nahoru" src="image/sipkaup.png" onClick="moveRowUp('table_linky', 'table_linky_row<?php echo $i; ?>');">
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; visibility: hidden;">
          <img name="order_button" style="visibility: hidden;" title="Dolu" src="image/sipkadown.png" onClick="moveRowDown('table_linky', 'table_linky_row<?php echo $i; ?>');">
        </td>
      </tr>
      <?php
      $i++;
    }
    ?>
  </table>
</form>
