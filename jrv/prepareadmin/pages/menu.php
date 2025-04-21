<script type="text/javascript" charset="windows-1250" src="../js/functions.js"></script>

<div id="header"><img src="css/image/logo.png"></div>

<?php
include_once '../lib/functions.php';
$connect = mysql_connect($con_server, $con_db, $con_pass);
mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT logo, url
          FROM location WHERE idlocation=" . getLocation($_POST["username"]));
$row = mysql_fetch_row($sql);
mysql_close($connect);
if ($row[0] != '') {
  ?>
  <a href="<?php echo $row[1]; ?>" target="_blank" style="position: absolute; left: 20px; top: 20px;"><img src="../loga/<?php echo $row[0]; ?>"/></a>
  <?php
}
?>

<div class="separdivglobalnapis"><?php echo getDopravce($_POST["username"]) . " ( uživatel : " . getUser($_POST["username"]) . " ) - ( " . getLocation($_POST["username"]) . " )"; ?></div>

<div id="menu_bar_top">
  <div id="menu_bar_center">
    <ul id="menu_main">
      <li>
        <a id="menu_content_1" name="content_1" href="?page=1" <?php
if ($_GET['page'] == 1) {
  echo "class='active'";
} else {
  echo "class='noactive'";
}
?> >
          <span>
            JŘ - náhledy
          </span>
        </a>
      </li>
      <li>
        <a id="menu_content_2" name="content_2" href="?page=2"  <?php
           if ($_GET['page'] == 2) {
             echo "class='active'";
           } else {
             echo "class='noactive'";
           }
?> >
          <span>
            Datové balíčky
          </span>
        </a>
      </li>
      <li>
        <a id="menu_content_3" name="content_3" href="?page=3"  <?php
           if ($_GET['page'] == 3) {
             echo "class='active'";
           } else {
             echo "class='noactive'";
           }
?> >
          <span>
            Importy
          </span>
        </a>
      </li>
      <li>
        <a id="menu_content_4" name="content_4" href="?page=4"  <?php
           if ($_GET['page'] == 4) {
             echo "class='active'";
           } else {
             echo "class='noactive'";
           }
?> >
          <span>
            Reklama
          </span>
        </a>
      </li>
      <!--      <li>
              <a id="menu_content_4" name="content_3" href="?page=4"  <?php
           /*           if ($_GET['page'] == 4) {
             echo "class='active'";
             } else {
             echo "class='noactive'";
             } */
?> >
                <span>
                  Nastavení
                </span>
              </a>
            </li>  -->
      <li>
        <a id="menu_content_5" name="content_4" onClick="Odhlasit('//www.mhdspoje.cz/jrw50/prepareadmin/');" <?php
      if ($_GET['page'] == 5) {
        echo "class='active'";
      } else {
        echo "class='noactive'";
      }
?> >
          <span>
            Odhlásit
          </span>
        </a>
      </li>
    </ul>
  </div>
</div>  </br>

<div style="margin-top: 15px; margin-bottom: 5px; white-space: nowrap"></div>
