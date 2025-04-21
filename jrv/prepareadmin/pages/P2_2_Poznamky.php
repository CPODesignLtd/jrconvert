<?php
if ($_POST['post'] == 'accept_write_pozn') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  $sql = "UPDATE pevnykod set OZNACENI = '" . $_POST['poznzkratka'] . "', REZERVA = '" . $_POST['pozntext'] . "', OBR = " . ($_POST['pictogramid'] = "" ? "null": ("'" . $_POST['pictogramid'] . "'")) . " where C_KODU = " . $_POST['idpoznamky'] . " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);
}

if (isset($_GET['show']) || isset($_GET['noshow'])) {
  $val = (isset($_GET['show']) ? 1 : 0);
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  mysql_query("UPDATE pevnykod SET showing = " . $val . " WHERE c_kodu = " . (isset($_GET['show']) ? $_GET['show'] : $_GET['noshow']) . " and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
}

if (isset($_GET['show1']) || isset($_GET['noshow1'])) {
  $val = (isset($_GET['show1']) ? 1 : 0);
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  mysql_query("UPDATE pevnykod SET showing1 = " . $val . " WHERE c_kodu = " . (isset($_GET['show1']) ? $_GET['show1'] : $_GET['noshow1']) . " and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
}

if (isset($_GET['cas']) || isset($_GET['nocas'])) {
  $val = (isset($_GET['cas']) ? 1 : 0);
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  mysql_query("UPDATE pevnykod SET caspozn = " . $val . " WHERE c_kodu = " . (isset($_GET['cas']) ? $_GET['cas'] : $_GET['nocas']) . " and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
}

if (isset($_GET['showip']) || isset($_GET['noshowip'])) {
  $val = (isset($_GET['showip']) ? 1 : 0);
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  mysql_query("UPDATE pevnykod SET i_p = " . $val . " WHERE c_kodu = " . (isset($_GET['showip']) ? $_GET['showip'] : $_GET['noshowip']) . " and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
}

//$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT c_kodu, oznaceni, rezerva, caspozn, showing, showing1, obr, i_p
          FROM pevnykod WHERE c_kodu > 0 and packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]));
?>

<div class="separdivglobalnapis">Poznámky k balíčku č. <?php echo $_GET['pack']; ?></div>

<form enctype="multipart/form-data" name="editpozn" method="post" action="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>">

  <table id="table_poznamky" class="t_akce">
    <tr>
      <th style="word-wrap: nowrap;">označení</th>
      <th style="word-wrap: nowrap;">piktogram</th>
      <th colspan="2" style="word-wrap: nowrap;">popis</th>
      <th colspan="1" style="word-wrap: nowrap;">časová poznámka</th>
      <th colspan="1" style="word-wrap: nowrap;">zobrazovat<br/>(koplexní,</br>linkový JŘ)</th>
      <th colspan="1" style="word-wrap: nowrap;">zobrazovat<br/>(denní JŘ)</th>
      <th colspan="1" style="word-wrap: nowrap;">interní poznámka<br/>(vozový JŘ)</th>
  <!--                <th>definice sloupců variant grafikonu</th>-->
    </tr>
    <?php
    $i = 1;
    while ($row = mysql_fetch_row($sql)) {
      ?>
      <tr id="table_poznamky_row<?php echo $i; ?>">
        <?php
        if ((!isset($_GET['id'])) || ($_GET['id'] != $row[0])) {
          ?>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[1]; ?></td>
          <?php
        } else {
          ?>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
            <input id="poznzkratka" name="poznzkratka" type="text" value="<?php echo $row[1]; ?>" style="width: 100%;">
          </td>
          <?php
        }
        ?>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;">
          <?php
          if ((!isset($_GET['id'])) || ($_GET['id'] != $row[0])) {
            if ($row[6] != null) {
              ?>
              <img src="../pictogram/<?php echo $row[6]; ?>">
              <?php
            }
          } else {
            $sql1 = mysql_query("select count(id)
                      from pictograms where idlocation = -1 or idlocation = " . getLocation($_POST["username"]));
            $row_pocet_picto = mysql_fetch_row($sql1);
            $sql1 = mysql_query("select id, nazev, path
                      from pictograms where idlocation = -1 or idlocation = " . getLocation($_POST["username"]) . " order by id");
            $picto = null;
            $i = 0;
            while ($row1 = mysql_fetch_row($sql1)) {
              $picto[$i++] = $row1;
            }
            ?>
            <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: 100%;">
              <tr>
                  <td class="last" colspan="8" style="font-size: 15px; font-weight: bold; width: auto; color: red;">
                    <input type="radio" name="pictogramid" value="" checked>
                    bez piktogramu
                  </td>
                </tr>
              <?php
              for ($i = 0; $i < intval($row_pocet_picto[0] / 8) + 1; $i++) {
                ?>
                <tr>
                  <?php
                  for ($ii = $i * 8; (($ii < $i * 8 + 8) && ($ii < $row_pocet_picto[0])); $ii++) {
                    ?>
                  <td class="last" style="font-size: 15px; font-weight: normal; width: auto; <?php echo ($picto[$ii][1] == $row[6]) ? 'border: 1px dashed red;' : 'border-right: 1px solid #c3c3c3;'; ?>">
                    <input type="radio" name="pictogramid" value="<?php echo $picto[$ii][1]; ?>" <?php echo ($picto[$ii][1] == $row[6]) ? 'checked' : ''; ?>>
                    <img src="<?php echo '../' . $picto[$ii][2]; ?>">
                  </td>
<!--                  <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;">

                  </td>-->
                  <?php
                  }
                  ?>
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
        if ((!isset($_GET['id'])) || ($_GET['id'] != $row[0])) {
          ?>
          <td class="last" style="width: 100%; word-wrap: break-word; white-space: normal;"><?php echo $row[2]; ?></td>
          <?php
        } else {
          ?>
          <td class="last" style="width: 100%; word-wrap: break-word; white-space: normal;">
            <input id="pozntext" name="pozntext" type="text" value="<?php echo $row[2]; ?>" style="width: 100%;">
          </td>
          <?php
        }
        ?>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (!isset($_GET['id'])) {
            ?>
            <a style="color: #ffffff; word-wrap: nowrap;" title="Editovat poznámku" href="?page=2&sub=<?php echo $_GET['sub']; ?>&id=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/pencil.png"></a>
            <?php
          } else {
            if ($_GET['id'] == $row[0]) {
              ?>
              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.editpozn['post'].value = 'accept_write_pozn'; document.editpozn.action = document.editpozn.action + '&scup=' + getScrollXY()[1]; document.editpozn.submit();"><img src="image/accept.png"></a>
              &nbsp;
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>
              <input id="idpoznamky" name="idpoznamky" type="text" value="<?php echo $row[0]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <input id="post" name="post" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <?php
            }
          }
          ?>
        </td>
  <!--      <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[3] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>"><?php echo ($row[3] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[3] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Časová/Nečasová poznámka" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[3] == 0 ? 'cas' : 'nocas'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[3] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[4] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>"><?php echo ($row[4] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[4] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[4] == 0 ? 'show' : 'noshow'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat poznámku" onClick="app_href(this);"><img src="<?php echo ($row[4] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[5] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>"><?php echo ($row[5] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[5] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[5] == 0 ? 'show1' : 'noshow1'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat poznámku" onClick="app_href(this);"><img src="<?php echo ($row[5] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>                  -->
  <!--      <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[3] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>"><?php echo ($row[3] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[3] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Časová/Nečasová poznámka" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[3] == 0 ? 'cas' : 'nocas'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[3] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[4] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>"><?php echo ($row[4] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[4] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[4] == 0 ? 'show' : 'noshow'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat poznámku" onClick="app_href(this);"><img src="<?php echo ($row[4] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[5] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>"><?php echo ($row[5] == 1 ? 'ANO' : 'NE'); ?>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: left; <?php echo ($row[5] == 1 ? 'background-color: #34916C' : 'background-color: #B53929'); ?>">
            <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[5] == 0 ? 'show1' : 'noshow1'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" title="Zobrazovat/Neobrazovat poznámku" onClick="app_href(this);"><img src="<?php echo ($row[5] == 1 ? 'image/sipkadown.png' : 'image/sipkaup.png'); ?>"></a>
        </td>-->
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center;">
          <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Časová/Nečasová poznámka" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[3] == 0 ? 'cas' : 'nocas'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[3] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center;">
          <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[4] == 0 ? 'show' : 'noshow'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[4] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center;">
          <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[5] == 0 ? 'show1' : 'noshow1'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[5] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
        </td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; word-wrap: nowrap; text-align: center;">
          <a style="color: #ffffff; word-wrap: nowrap; visibility: <?php echo (!isset($_GET['id'])) ? 'visible' : 'hidden'; ?>;" title="Zobrazovat/Nezobrazovat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&<?php echo ($row[7] == 0 ? 'showip' : 'noshowip'); ?>=<?php echo $row[0]; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="<?php echo ($row[7] == 1 ? 'image/check.png' : 'image/uncheck.png'); ?>"></a>
        </td>
      </tr>
      <?php
      $i++;
    }
    ?>
  </table>
</form>