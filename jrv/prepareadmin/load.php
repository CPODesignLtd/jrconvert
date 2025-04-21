<?php
//header('Content-Type: text/html; charset=windows-1250'); 
error_reporting (0);
include_once '../lib/CZlang.php';
include_once '../lib/param.php';
include_once '../lib/functions.php';
?>
<script type="text/javascript" src="../js/JRclass50.js" charset="UTF-8"></script>
<div id="leftpanel" style="width: 570px; float: left;">
  <div class="separdivglobalnapis"><?php echo iconv('UTF-8', 'UTF-8', "nahrané soubory"); ?></div>

  <table>

    <?php
    $target_path = "data/" . $_GET["un"] . "/decode/";

    $files = glob($target_path . '*');
    if ($files != null) {
      foreach ($files as $file) {
        if (is_file($file))
          unlink($file);
      }
    }

    if (file_exists($target_path)) {
      rmdir($target_path);
    }

    $target_path = "data/" . $_GET["un"] . "/";

    $files = glob($target_path . '*');
    if ($files != null) {
      foreach ($files as $file) {
        if (is_file($file))
          unlink($file);
      }
    }

    if (file_exists($target_path)) {
      rmdir($target_path);
    }
    if (!file_exists($target_path)) {
      mkdir($target_path, 0755);
    }

    if ($_FILES['i_linky']['name'] != '') {
      $target_path1 = $target_path . basename($_FILES['i_linky']['name']);
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename($_FILES['i_linky']['name']); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file($_FILES['i_linky']['tmp_name'], $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_zaslinky']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zaslinky']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zaslinky']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_zaslinky']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_spoje']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_spoje']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_spoje']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_spoje']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_zasspoje']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_zasspoje_pozn']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje_pozn']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje_pozn']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_zasspoje_pozn']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_chronometr']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_chronometr']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_chronometr']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_chronometr']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_zastavky']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zastavky']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_zastavky']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_zastavky']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_pevnykod']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_pevnykod']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_pevnykod']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_pevnykod']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }

    if ($_FILES['i_kalendar']['name'] != '') {
      $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['i_kalendar']['name']));
      ?>
      <tr><td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename(iconv('windows-1250', 'UTF-8', $_FILES['i_kalendar']['name'])); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['i_kalendar']['tmp_name']), $target_path1)) {
            ?>
            <img src="image/accept.png"/>
            <?php
          } else {
            ?>
            <img src="image/abort.png"/>
            <?php
          }
          ?>
        </td></tr>
      <?php
    }
    ?>

  </table>

  <form enctype="multipart/form-data" name="zpracujpacket" method="post" action="?page=3">
    <link rel="stylesheet" type="text/css" href="css/kalendar.css"/>    
    <br/>

    <table>
      <tr>
        <th>č. balíčku</th>
        <th>platnost OD</th>
        <th>platnost DO</th>
      </tr>
      <tr>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><input id ="i_cpacket" name = "i_cpacket"/></td>
        <td class="last" style="width: auto;">
          <div id="select_datum_od" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
            <span style="vertical-align: middle; height: 100%; cursor: pointer;">
              <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
              <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_od"></a>
            </span>
          </div>
          <script type="text/javascript">
            dnes = new Date();
            den = dnes.getDay();
            mesic = dnes.getMonth() + 1;
            rok = dnes.getFullYear();
            d_dnes = rok + "-" + mesic + "-" + den;
            var Kalend_od = new JRKalendar('select_datum_od', 'a_select_datum_od', null);
            Kalend_od.codepage = 'UTF';
            Kalend_od.initialize(d_dnes, 'Kalend_od');
            Kalend_od.settoall();
            Kalend_od.setZIndex(100001);
          </script>
        </td>

        <td class="last" style="width: auto;">
          <div id="select_datum_do" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
            <span style="vertical-align: middle; height: 100%; cursor: pointer;">
              <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
              <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_do"></a>
            </span>
          </div>
          <script type="text/javascript">
            dnes = new Date();
            den = dnes.getDay();
            mesic = dnes.getMonth() + 1;
            rok = dnes.getFullYear();
            d_dnes = rok + "-" + mesic + "-" + den;
            var Kalend_do = new JRKalendar('select_datum_do', 'a_select_datum_do', null);
            Kalend_do.codepage = 'UTF';
            Kalend_do.initialize(d_dnes, 'Kalend_do');
            Kalend_do.settoall();
            Kalend_do.setZIndex(100001);
          </script>
        </td>
      </tr>
    </table>

    <input id="dod" name="dod" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
    <input id="ddo" name="ddo" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">

    <div class="button" id="preparedata" style="height: 35px; width: 150px; visibility: visible;" onclick="check_import();">
      <span></span><img src="image/disk.png">
      <?php
      echo iconv('windows-1250', 'windows-1250', "Zpracovat data");
      ?>
    </div>
    <input id="post" name="post" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
  </form>
</div>

<div id="rightpanel" style="margin:0 30px 0 600px;">
  <div class="separdivglobal">Dostupné balíčky</div>
  <?php
  $connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);
  $sql = mysql_query("SELECT packet, jr_od, jr_do, jeplatny,
          (select count(c_sloupce) from jrtypes where idlocation=" . ($_GET["un"]) . " and packet = packets.packet) as pocet_sloupcu,
          (select count(c_kodu) from jrvargrfs where idtimepozn in (select idtimepozn from jrtypes where idlocation=" . ($_GET["un"]) . " and packet = packets.packet)) as pocet_variant,
          id
          FROM packets WHERE location=" . ($_GET["un"]) . " order by jr_od DESC, packet DESC");
  ?>

  <table id="table_packets_<?php echo $i; ?>" class="t_akce">
    <tr>
      <th>č. balíčku</th>
      <th>platnost OD</th>
      <th>platnost DO</th>
      <th>aktivní</th>
      <th>definice sloupců variant grafikonu</th>
    </tr>
    <?php
    while ($row = mysql_fetch_row($sql)) {

      list($year, $month, $day) = explode('-', $row[1]);
      $d_od = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;

      list($year, $month, $day) = explode('-', $row[2]);
      $d_do = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
      ?>
      <tr>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[0]; ?></td>
        <td class="last" style="width: auto;"><?php echo $d_od; ?></td>
        <td class="last" style="width: auto;"><?php echo $d_do; ?></td>

        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ($row[3] == 1 ? 'background-color: #34916C' : 'background-color: #B53929;'); ?>"><?php echo ($row[3] == 1 ? 'ANO' : 'NE'); ?></td>
        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'background-color: #34916C' : 'background-color: #B53929'); ?>"><?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'ANO' : 'NE'); ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
</div>

<script type="text/javascript">
  document.getElementById('i_cpacket').focus();

  var exist_packet = [];

<?php
//$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT packet FROM packets WHERE location=" . $_GET["un"] . " order by packet");
while ($row = mysql_fetch_row($sql)) {
  ?>
      exist_packet[<?php echo $row[0]; ?>] = 1;
  <?php
}
?>
  function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  function check_import() {
    if (isNumber(document.getElementById('i_cpacket').value)) {
      if (exist_packet[document.getElementById('i_cpacket').value] == 1) {
        if (confirm('Číslo balíčku již existuje. Nahradit data ?')) {
          document.zpracujpacket['dod'].value = Kalend_od.y + '-' + Kalend_od.m + '-' + Kalend_od.d;
          document.zpracujpacket['ddo'].value = Kalend_do.y + '-' + Kalend_do.m + '-' + Kalend_do.d;
          document.zpracujpacket['post'].value = 'prepare_packet';
          document.zpracujpacket.action = document.zpracujpacket.action + '&scup=' + getScrollXY()[1];
          document.zpracujpacket.submit();
        } else {
          document.getElementById('i_cpacket').focus();
        }
    } else {
      document.zpracujpacket['dod'].value = Kalend_od.y + '-' + Kalend_od.m + '-' + Kalend_od.d;
      document.zpracujpacket['ddo'].value = Kalend_do.y + '-' + Kalend_do.m + '-' + Kalend_do.d;
      document.zpracujpacket['post'].value = 'prepare_packet';
      document.zpracujpacket.action = document.zpracujpacket.action + '&scup=' + getScrollXY()[1];
      document.zpracujpacket.submit();
    }
    } else {
      alert('Číslo balíčku musí být číselná hodnota!');
      document.getElementById('i_cpacket').focus();
    }
  }

</script>