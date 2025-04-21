<style>
  #Wrapper {
    width: 70%;
    margin-right: auto;
    margin-left: auto;
    margin-top: 50px;
    background: #EEEEEE;
    padding: 20px;
    border: 1px solid #E6E6E6;
  }
  #progressbox, .progressbox {
    border: 1px solid #0099CC;
    padding: 1px;
    position:relative;
    width:400px;
    border-radius: 3px;
    margin: 10px;
    /*    display:none;*/
    visibility: hidden;
    text-align:left;
  }
  #progressbar, .progressbar {
    height:20px;
    border-radius: 3px;
    background-color: #003333;
    width:1%;
  }
  #statustxt, .statustxt {
    top:3px;
    left:50%;
    position:absolute;
    display:inline-block;
    color: #000000;
  }
</style>


<?php
error_reporting (0);
require_once 'Vector.php';

class TZastavka {

  var $c_tarif = null;
  var $c_zastavky = null;
  var $zast_A = false;
  var $zast_B = false;
  var $prestup = false;

}

$LINKY = array();
$TRASY = null;

if ($_POST['post'] == 'prepare_packet') {

//  $connect = mysql_connect($con_server, $con_db, $con_pass);
//  mysql_select_db($con_db);
  $sql = mysql_query("SELECT packet FROM packets WHERE location = " . getLocation($_POST["username"]) . " and packet = " . $_POST['i_cpacket']);
  //echo "SELECT packet FROM packets WHERE location = " . getLocation($_POST["username"]) . " and packet = " . $_POST['i_cpacket'];
  $row = mysql_fetch_row($sql);

  if ($row[0] == '') {
//    $connect = mysql_connect($con_server, $con_db, $con_pass);
//    mysql_select_db($con_db);
    $sql = "INSERT INTO packets (packet, jr_od, jr_do, location, jeplatny) VALUES (" . $_POST['i_cpacket'] . ",
          '" . $_POST['dod'] . "', '" . $_POST['ddo'] . "', " . getLocation($_POST['username']) . ", 0)";
    mysql_query($sql);
  }

  include_once 'decode.php';
  ?>
  <div class="separdivglobalnapis">... zpracování dat ... check</div><br/>


  <!--  <div id="output_prepare"></div>-->
  <?php
  //flush();
  $target_path = "data/" . getLocation($_POST["username"]) . "/";
  $target_decode_path = "data/" . getLocation($_POST["username"]) . "/decode/";

  $files = glob($target_path . '*');
  
/*  if ($files != null) {
    foreach ($files as $file) {
      if (is_file($file)) {
        $handle = @fopen($file, "r");
        $contents = fread($handle, filesize($file));
        fclose($handle);

        if (!file_exists($target_64_path)) {
          mkdir($target_64_path, 0755);
        }
        $filename = $target_64_path . basename(iconv('windows-1250', 'UTF-8', $file));
        $handle = fopen($filename, "w");
        fwrite($handle, base64_decode($contents));
        fclose($handle);
      }
    }
  }*/

//  $files = glob($target_64_path . '*');
  
  if ($files != null) {
    $i = 0;
    foreach ($files as $file) {
      if (is_file($file)) {
        $handle = @fopen($file, "r");
        $contents = fread($handle, filesize($file));
        fclose($handle);

//        $stuff = $contents;
        $key = '#jrw_fssoftware_581003##';

//        echo $file . '<br>';
        $decrypted = decrypt($contents, $key, $file);

        if (!file_exists($target_decode_path)) {
          mkdir($target_decode_path, 0755);
        }
        $filename = $target_decode_path . basename($file);
        $handle = fopen($filename, "w");
        fwrite($handle, ($decrypted));
        fclose($handle);
      }
    }
  }

  $target_path = $target_decode_path;
  $files = glob($target_path . '*');

//  echo 'vypis';
  
  if ($files != null) {
    $i = 0;
    ?>
    <table>
      <?php
      foreach ($files as $file) {
        if (is_file($file)) {
          ?>
          <tr>
            <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo basename($file); ?></td>
            <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
              <div id="progressbox_<?php echo $i; ?>" class="progressbox" style="visibility: visible;"><div id="progressbar_<?php echo $i; ?>" class="progressbar"  style="visibility: visible;"></div ><div id="statustxt_<?php echo $i; ?>" class="statustxt" style="visibility: visible;">0%</div></div>
            </td>
            <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><div id="err_<?php echo $i; ?>"></div></td>
          </tr>
          <?php
          flush();
          $i++;
        }
      }
      ?>
    </table>
    <?php
  }

//  $connect = mysql_connect($con_server, $con_db, $con_pass);
//  mysql_select_db($con_db);

  $location = getLocation($_POST["username"]);
  $packet = $_POST['i_cpacket'];
  if ($files != null) {
    $i = 0;
    foreach ($files as $file) {
      if (is_file($file)) {
        $handle = @fopen($file, "r");
        if ($handle) {
          $size = filesize($file);
          $readsize = 0;
          $procento = 0;
          $buffer = fgets($handle);
          $codename = $buffer;
          $readsize += (strlen($buffer));
          $lines = 0;
          if (trim($codename) == trim(basename($file))) {
            while (($buffer = fgets($handle)) !== false) {
              $readsize += (strlen($buffer));
              $lines++;
              if ($lines >= 1000) {
                $procento = round($readsize / $size * 100);
                ?>
                <script type="text/javascript">
                  document.getElementById('progressbar_<?php echo $i; ?>').style.width = "<?php echo $procento; ?>%";
                  if (<?php echo $procento; ?> > 50) {
                    document.getElementById('statustxt_<?php echo $i; ?>').style.color = "#ffffff";
                  }
                  document.getElementById('statustxt_<?php echo $i; ?>').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                $lines = 0;
              }
              $query = sprintf($buffer, $location, $packet);
//              $query = iconv('UTF-8', 'windows-1250', $query);

              if (trim($codename) == 'zastavky.sql') {
                $pieces = explode("'", $query);
                if (count($pieces) == 9) {
                  $pieces[3] = str_replace(',', '.', $pieces[3]);
                  $pieces[5] = str_replace(',', '.', $pieces[5]);
                  $query = $pieces[0] . '\'' . $pieces[1] . '\'' . $pieces[2] . '\'' . $pieces[3] . '\'' . $pieces[4] . '\'' . $pieces[5] . '\'' . $pieces[6] . '\'' . $pieces[7] . '\'' . $pieces[8];
                }
              }
//              echo $query . '</br>';
              $result = mysql_query($query);
              flush();
            }
            if ($lines != 0) {
              $lines = 0;
              $procento = round($readsize / $size * 100);
              ?>
              <script type="text/javascript">
                document.getElementById('progressbar_<?php echo $i; ?>').style.width = "<?php echo $procento; ?>%";
                if (<?php echo $procento; ?> > 50) {
                  document.getElementById('statustxt_<?php echo $i; ?>').style.color = "#ffffff";
                }
                document.getElementById('statustxt_<?php echo $i; ?>').innerHTML = "<?php echo $procento; ?>%";
              </script>
              <?php
            }
          } else {
            $procento = 100;
            ?>
            <script type="text/javascript">
              document.getElementById('progressbar_<?php echo $i; ?>').style.width = "<?php echo $procento; ?>%";
              if (<?php echo $procento; ?> > 50) {
                document.getElementById('statustxt_<?php echo $i; ?>').style.color = "#ffffff";
              }
              document.getElementById('statustxt_<?php echo $i; ?>').innerHTML = "<?php echo $procento; ?>%";
              document.getElementById('err_<?php echo $i; ?>').innerHTML = "není platným souborem pro import !";
            </script>
            <?php
          }
          fclose($handle);
        }
        $i++;
      }
    }
    ?>
    <?php
  }
  /*$sql = "call setbcodespoje(" . $location . ", " . $packet . ");";
  $mysqli = new mysqli($con_server,$con_user,$con_pass,$con_db);
  $query = $mysqli->query($sql);
  
  $sql = "call finalize_import(" . $location . ", " . $packet . ");";
  $mysqli = new mysqli($con_server,$con_user,$con_pass,$con_db);
  $query = $mysqli->query($sql);*/
  
  $sql = "CREATE EVENT e_final" . $location .
    " ON SCHEDULE
      AT CURRENT_TIMESTAMP
    DO CALL finalize_import(" . $location . ", " . $packet . ");";
  $mysqli = new mysqli($con_server,$con_user,$con_pass,$con_db);
  $query = $mysqli->query($sql);
    
  if ($idlocation == 6) {
      calcWalk($idlocation, $packet);
  }

/*function calcWalk($idlocation, $packet) {

class TZastavkaWalk {

  var $c_zastavky = null;
  var $loca = null;
  var $locb = null;

}

class TPrechod {

  var $od_zastavky = null;
  var $do_zastavky = null;
  var $vzdalenost = null;
  var $doba = null;

}

$distance = 0.005448; //0.005448 = cca 500m
$distanceVzdalenost = 500; //v metrech
$rychlost = 1.39; //metr per sec
$seznamZastavek = new Vector();
$seznamPrehodu = new Vector();

$mysqli = new mysqli($con_server,$con_user,$con_pass,$con_db);

  $sql = "SELECT c_zastavky, loca, locb FROM zastavky where idlocation = " . $location . " and packet = " . $packet . " order by c_zastavky";

  $result = $mysqli->query($sql);

  while ($row = $result->fetch_row()) {
    $zastavka = new TZastavkaWalk();
    $zastavka->c_zastavky = $row[0];
    $zastavka->loca = $row[1];
    $zastavka->locb = $row[2];
    $seznamZastavek->addElement($zastavka);
  }

  for ($i = 0; $i < $seznamZastavek->size(); $i++) {
    $fromZastavka = $seznamZastavek->elementAt($i);
    for ($ii = 0; $ii < $seznamZastavek->size(); $ii++) {
      $toZastavka = $seznamZastavek->elementAt($ii);
      if ($fromZastavka->c_zastavky != $toZastavka->c_zastavky) {
        $deltaLoca = abs($fromZastavka->loca - $toZastavka->loca);
        $deltaLocb = abs($fromZastavka->locb - $toZastavka->locb);
        if (($deltaLoca <= $distance) && ($deltaLocb <= $distance)) {
          $vzdalenostDist = sqrt(($deltaLoca * $deltaLoca) + ($deltaLocb * $deltaLocb));
          $vzdalenost_m = ($vzdalenostDist * $distanceVzdalenost) / $distance;
          if ($vzdalenost_m != 0) {
            $cas = $vzdalenost_m / $rychlost;
            $prechod = new TPrechod();
            $prechod->od_zastavky = $fromZastavka->c_zastavky;
            $prechod->do_zastavky = $toZastavka->c_zastavky;
            $prechod->vzdalenost = round($vzdalenost_m);
            $prechod->doba = round($cas / 60);
            if ($prechod->doba <= 0) {
              $prechod->doba = 1;
            }
            $prechod->doba = $prechod->doba;
            $seznamPrehodu->addElement($prechod);
          }
        }
      }
    }
  }

  $sql = "DELETE FROM pesobus WHERE idlocation = " . $location . " and packet = " . $packet;
  $result = $mysqli->query($sql);

  for ($i = 0; $i < $seznamPrehodu->size(); $i++) {
    $prechod = $seznamPrehodu->elementAt($i);
    $sql = "INSERT INTO pesobus VALUES (" . $prechod->od_zastavky . ", " . $prechod->do_zastavky . ", " . $prechod->doba . ", " . $prechod->vzdalenost . ", " . $location . ", " . $packet . ")";
  }    
}*/

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

//$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
//$mysqli = new mysqli($con_server,$con_user,$con_pass,$con_db);
//$mysqli->query("SET NAMES 'utf-8';");

$sql = "SELECT c_linky, c_tarif, c_zastavky, zast_a, zast_b FROM zaslinky where idlocation = " . $location . " and packet = " . $packet . " order by c_linky, c_tarif;";
$result = $mysqli->query($sql);

while ($row = $result->fetch_row()) {
  $c_linky = $row[0];
  $c_tarif = $row[1];
  $c_zastavky = $row[2];
  $zast_a = $row[3];
  $zast_b = $row[4];

  $new_zastavka = new TZastavka();
  $new_zastavka->c_tarif = $c_tarif;
  $new_zastavka->c_zastavky = $c_zastavky;
  $new_zastavka->zast_A = $zast_a;
  $new_zastavka->zast_B = $zast_b;

  $LINKY[$c_linky][count($LINKY[$c_linky])] = $new_zastavka;
}

foreach ($LINKY as $key => $TRASY) {
//  echo $key . " : " . "</br>";
  for ($i = 0; $i < count($TRASY); $i++) {
    $TRASY[$i]->prestup = checkPrestup($key, $TRASY[$i]->c_zastavky, (($TRASY[$i - 1] == null) ? null: $TRASY[$i - 1]->c_zastavky), (($TRASY[$i + 1] == null) ? null: $TRASY[$i + 1]->c_zastavky));
//    echo "&nbsp &nbsp - " . $TRASY[$i]->c_tarif . " , " . $TRASY[$i]->c_zastavky . " | " . $TRASY[$i]->prestup . "</br>";
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

if ($location != 17) {
  if($PRESTUPY){
    foreach ($PRESTUPY as $key => $zast) {
      $sql = "update zaslinky set prestup = 1 where c_zastavky = " . $zast->c_zastavky . " and idlocation = " . $location . " and packet = " . $packet;
      $result = $mysqli->query($sql);
    }
  }
}

  ?>
  <br/>
  <div class="separdivglobalnapis">... zpracování DOKONČENO ...</div>
  <?php
}
?>

 <table>
   <tr><td>
<div id="output"></div>
</td>
</tr>
<tr><td>
<?php
if ($_POST['post'] != 'prepare_packet') {
  ?>

  <script type="text/javascript" src="../js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="../js/jquery.form.js"></script>

  <form enctype="multipart/form-data" id="UploadForm" name="importpacket" method="post" action="load.php?un=<?php echo getLocation($_POST["username"]); ?>">

    <script>
      $(document).ready(function() {
        var progressbox     = $('#progressbox');
        var progressbar     = $('#progressbar');
        var statustxt       = $('#statustxt');
        var submitbutton    = $("#SubmitButton");
        var myform          = $("#UploadForm");
        var output          = $("#output");
        var completed       = '0%';
        var i_linky_load  = $("#i_linky_load");

        $(myform).ajaxForm({
          beforeSend: function() {
            submitbutton.attr('disabled', '');
            document.getElementById('i_linky_load').style.width = "100%";
            document.getElementById('i_linky_load').style.visibility = "visible";
            document.getElementById('i_zaslinky_load').style.width = "100%";
            document.getElementById('i_zaslinky_load').style.visibility = "visible";
            document.getElementById('i_spoje_load').style.width = "100%";
            document.getElementById('i_spoje_load').style.visibility = "visible";
            document.getElementById('i_zasspoje_load').style.width = "100%";
            document.getElementById('i_zasspoje_load').style.visibility = "visible";
            document.getElementById('i_zasspoje_pozn_load').style.width = "100%";
            document.getElementById('i_zasspoje_pozn_load').style.visibility = "visible";
            document.getElementById('i_chronometr_load').style.width = "100%";
            document.getElementById('i_chronometr_load').style.visibility = "visible";
            document.getElementById('i_zastavky_load').style.width = "100%";
            document.getElementById('i_zastavky_load').style.visibility = "visible";
            document.getElementById('i_pevnykod_load').style.width = "100%";
            document.getElementById('i_pevnykod_load').style.visibility = "visible";
            document.getElementById('i_kalendar_load').style.width = "100%";
            document.getElementById('i_kalendar_load').style.visibility = "visible";
            statustxt.empty();
            progressbar.width(completed);
            statustxt.html(completed);
            statustxt.css('color','#000');
          },
          uploadProgress: function(event, position, total, percentComplete) {
            document.getElementById('progressbox').style.visibility = "visible";
            progressbar.width(percentComplete + '%')
            statustxt.html(percentComplete + '%');
            if(percentComplete>50)
            {
              statustxt.css('color','#fff');
            }
          },
          complete: function(response) {
            document.getElementById('i_linky_load').style.width = "0";
            document.getElementById('i_linky_load').style.visibility = "hidden";
            document.getElementById('i_zaslinky_load').style.width = "0";
            document.getElementById('i_zaslinky_load').style.visibility = "hidden";
            document.getElementById('i_spoje_load').style.width = "0";
            document.getElementById('i_spoje_load').style.visibility = "hidden";
            document.getElementById('i_zasspoje_load').style.width = "0";
            document.getElementById('i_zasspoje_load').style.visibility = "hidden";
            document.getElementById('i_zasspoje_pozn_load').style.width = "0";
            document.getElementById('i_zasspoje_pozn_load').style.visibility = "hidden";
            document.getElementById('i_chronometr_load').style.width = "0";
            document.getElementById('i_chronometr_load').style.visibility = "hidden";
            document.getElementById('i_zastavky_load').style.width = "0";
            document.getElementById('i_zastavky_load').style.visibility = "hidden";
            document.getElementById('i_pevnykod_load').style.width = "0";
            document.getElementById('i_pevnykod_load').style.visibility = "hidden";
            document.getElementById('i_kalendar_load').style.width = "0";
            document.getElementById('i_kalendar_load').style.visibility = "hidden";
            document.getElementById('progressbox').style.visibility = "hidden";
            output.html(response.responseText);
            myform.resetForm();
            submitbutton.removeAttr('disabled');
          }
        });
      });

    </script>

      <div id="progressbox"><div id="progressbar"></div ><div id="statustxt">0%</div></div>

      <table id="table_kongresy" class="t_akce">
        <tr>
          <th>popis souboru</th>
          <th>soubor</th>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">linky.sql</td>
          <td class="last" style="width: auto;"><input name="i_linky" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_linky_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">zaslinky.sql</td>
          <td class="last" style="width: auto;"><input name="i_zaslinky" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_zaslinky_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">spoje.sql</td>
          <td class="last" style="width: auto;"><input name="i_spoje" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_spoje_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">zasspoje.sql</td>
          <td class="last" style="width: auto;"><input name="i_zasspoje" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_zasspoje_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">zasspoje_pozn.sql</td>
          <td class="last" style="width: auto;"><input name="i_zasspoje_pozn" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_zasspoje_pozn_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">chronometr.sql</td>
          <td class="last" style="width: auto;"><input name="i_chronometr" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_chronometr_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">zastavky.sql</td>
          <td class="last" style="width: auto;"><input name="i_zastavky" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_zastavky_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">pevnykod.sql</td>
          <td class="last" style="width: auto;"><input name="i_pevnykod" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_pevnykod_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">kalendar.sql</td>
          <td class="last" style="width: auto;"><input name="i_kalendar" type="file" style="width: 500px;"/></td>
          <td style="font-size: 15px; font-weight: bold; width: 32px; text-align: center; height: 36px;"><img id="i_kalendar_load" style="width: 0; visibility: hidden;" src="image/loader7.gif"></img></td>
        </tr>
        <tr>
          <td colspan="3" style="width: auto; text-align: center;">
            <input type="submit" style="width: 0; height: 0; visibility: hidden;" id="SubmitButton" value="Upload" />
            <div class="button" id="startimport" style="height: 35px; width: 150px; visibility: visible;" onclick="document.importpacket['post'].value = 'accept_upload'; document.getElementById('SubmitButton').click();">
              <span></span><img src="image/disk.png">
              Importovat
            </div>
          </td>
        </tr>
      </table>
      <input id="post" name="post" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
  </form>
</td>
</tr>
</table>
  <?php
}
?>
