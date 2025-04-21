<?php
if ($_POST['typeaction'] == 'order_write_zastavka') {
//  $connect = mysql_connect($con_server, $con_db, $con_pass);
//  mysql_select_db($con_db);
  for ($i = 0; $i < sizeof($_POST['order']); $i++) {
    $sql = "UPDATE zastavky set C_ZASTAVKYSORT = " . $_POST['order'][$i] . " where C_ZASTAVKY = " . $_POST['idzastavky'][$i] . " AND idlocation = " . getLocation($_POST["username"]) .
            " AND packet = " . $_GET['pack'];
    mysql_query($sql);
//    echo $sql;
  }
}

if ($_POST['typeaction'] == 'accept_write_zastavka') {
//    $connect = mysql_connect($con_server, $con_db, $con_pass);
//    mysql_select_db($con_db);
    $nazev = (($_POST['zastavkanazev'] == '') ? "null" : "'" . $_POST['zastavkanazev'] . "'");
    $zkratka = (($_POST['zastavkazkratka'] == '') ? "null" : "'" . $_POST['zastavkazkratka'] . "'");
    $loca = (($_POST['zastavkaloca'] == '') ? "null" : "'" . $_POST['zastavkaloca'] . "'");
    $locb = (($_POST['zastavkalocb'] == '') ? "null" : "'" . $_POST['zastavkalocb'] . "'");
    $sql = "UPDATE zastavky set NAZEV = " . $nazev . ", LOCA = " . $loca .
    ", LOCB = " . $locb . ", ZKRATKA = " . $zkratka . " where C_ZASTAVKY = " . $_POST['czastavky'] .
    " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
    mysql_query($sql);
//  echo $sql;
}

$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT c_kodu, oznaceni, rezerva, caspozn, showing, showing1, obr
          FROM pevnykod WHERE c_kodu > 0 and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
$pozn = null;
while ($row = mysql_fetch_row($sql)) {
  $pozn[$row[0]] = $row;
}

$sql = mysql_query("SELECT C_ZASTAVKY, NAZEV, PK1, PK2, PK3, PK4, PK5, PK6, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT
          FROM zastavky WHERE packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]) . " order by c_zastavkysort");
?>

<div class="separdivglobalnapis" style="clear: both;">Zastávky k balíčku č. <?php echo $_GET['pack']; ?></div>

<form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=5">

  <table>
    <tr>
      <?php
      if (!isset($_GET['cz'])) {
        ?>
      <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; height: 0px; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
      <!--  <input type="button" name="order_button" style="margin: 8px 4px 8px 4px; float: left;" onClick="visibledisable_elements('order_button'); visibledisable_elements('edit_linky'); visibledisable_elements('post');" value="změna pořadí">-->
      <td>
        <div class="button" id="change_order_button" style="height: 35px; width: 150px; visibility: visible;" onclick="document.getElementById('change_order_button').style.visibility='hidden'; visibledisable_elements('order_button'); /*visibledisable_elements('edit_linky'); */document.getElementById('storno_button').style.visibility='visible'; document.getElementById('zapsat_button').style.visibility='visible';">
          <span></span><img src="image/sort_number.png">
          změna pořadí
        </div>
      </td>
      <?php
    }
    ?>
    <td>
    <!--  <input type="submit" name="post" style="margin: 8px 4px 8px 4px; visibility: hidden;" value="Zapsat">-->
      <div class="button" id="zapsat_button" style="height: 35px; width: 150px; visibility: hidden; border-color: #3C7FB1;" onclick="document.frm['typeaction'].value = 'order_write_zastavka'; document.frm.submit();">
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

  <table id="table_zastavky" class="t_akce" style="clear: both; float: none;">
    <tr>
      <th>celý název zastávky</th>
      <th>zkratka</th>
      <th>GPS X</th>
      <th>GPS Y</th>
      <th colspan="6">poznámky</th>
    </tr>
    <?php
    $i = 1;
    while ($row = mysql_fetch_row($sql)) {
      ?>
      <tr id="table_zastavky_row<?php echo $i; ?>">
        <?php
        if ((!isset($_GET['cz'])) || ($_GET['cz'] != $row[0])) {
          ?>
          <td class="last" style="font-size: 15px; font-weight: bold; text-align: left; vertical-align: middle; width: auto;"><?php echo $row[1]; ?>
            <?php
          } else {
            ?>
          <td class="last" style="font-size: 15px; font-weight: bold; text-align: left; vertical-align: middle; width: auto;">
            <input type="text" name="zastavkanazev" id="zastavkanazev" style="width: 100%;" value="<?php echo $row[1]; ?>"></input>
            <?php
          }
          ?>
          <input name="order[]" style="width: 0px; visibility: hidden;" value="<?php echo $row[11]; ?>">
          <input name="idzastavky[]" style="width: 0px; visibility: hidden;" value="<?php echo $row[0]; ?>">
        </td>
        <?php
        if ((!isset($_GET['cz'])) || ($_GET['cz'] != $row[0])) {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;"><?php echo $row[10]; ?></td>
          <?php
        } else {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
            <input type="text" name="zastavkazkratka" id="zastavkazkratka" style="width: 100%;" value="<?php echo $row[10]; ?>"></input>
          </td>
          <?php
        }
        ?>
        <?php
        if ((!isset($_GET['cz'])) || ($_GET['cz'] != $row[0])) {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;"><?php echo $row[8]; ?></td>
          <?php
        } else {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
            <input type="text" name="zastavkaloca" id="zastavkaloca" style="width: 100%;" value="<?php echo $row[8]; ?>"></input>
          </td>
          <?php
        }
        ?>
        <?php
        if ((!isset($_GET['cz'])) || ($_GET['cz'] != $row[0])) {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;"><?php echo $row[9]; ?></td>
          <?php
        } else {
          ?>
          <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
            <input type="text" name="zastavkalocb" id="zastavkalocb" style="width: 100%;" value="<?php echo $row[9]; ?>"></input>
          </td>
          <?php
        }
        ?>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[2]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[2]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[2]][1];
          }
          ?>
        </td>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[3]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[3]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[3]][1];
          }
          ?>
        </td>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[4]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[4]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[4]][1];
          }
          ?>
        </td>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[5]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[5]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[5]][1];
          }
          ?>
        </td>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[6]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[6]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[6]][1];
          }
          ?>
        </td>
        <td class="last" style="font-size: 15px; font-weight: normal; width: auto; text-align: left; vertical-align: middle;">
          <?php
          if ($pozn[$row[7]][6] != null) {
            ?>
            <img src="../pictogram/<?php echo $pozn[$row[7]][6]; ?>">
            <?php
          } else {
            echo $pozn[$row[7]][1];
          }
          ?>
        </td>
        <td class="first">
          <?php
          if (!isset($_GET['cz'])) {
            ?>
            <a name="edit_zastavky" style="color: #ffffff;" href="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=<?php echo $_GET['sub']; ?>&cz=<?php echo $row[0]; ?>" onClick="app_href(this);" title="Editace zastávky"><img src="image/pencil.png"></a>
            <?php
          } else {
            if ($_GET['cz'] == $row[0]) {
              ?>
              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['typeaction'].value = 'accept_write_zastavka'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>
              &nbsp;
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>
              <input id="czastavky" name="czastavky" type="text" value="<?php echo $row[0]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <?php
            }
          }
          ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; visibility: hidden;">
          <img name="order_button" style="visibility: hidden;" title="Nahoru" src="image/sipkaup.png" onClick="moveRowUp('table_zastavky', 'table_zastavky_row<?php echo $i; ?>');">
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; visibility: hidden;">
          <img name="order_button" style="visibility: hidden;" title="Dolu" src="image/sipkadown.png" onClick="moveRowDown('table_zastavky', 'table_zastavky_row<?php echo $i; ?>');">
        </td>
      </tr>
      <?php
      $i++;
    }
    ?>
  </table>
</form>