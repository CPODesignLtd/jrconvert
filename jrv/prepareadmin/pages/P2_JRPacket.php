<link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
<script type="text/javascript" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>

<script type="text/javascript">
    var Kalend_od_insert;
</script>

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
        width: 200px;
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
        color: #ff0000;
    }
    #statustxt {
        top:3px;
        left:50%;
        position:absolute;
        display:inline-block;
        color: #ff0000;
    }
</style>

<?php
$valexp = 0;

$postTypeAction = RequestProvider::GetPostValueByName('typeaction', "undefined");

if ($postTypeAction == 'insert_packet') {
    $val = (isset($_GET['down']) ? 0 : 1);
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    $sql = "INSERT INTO packets (packet, jr_od, jr_do, location, jeplatny) VALUES (" . $_POST['packetnumber'] . ",
          '" . $_POST['dod'] . "', '" . $_POST['ddo'] . "', " . getLocation($_POST['username']) . ", 0)";
    mysql_query($sql);
    //echo $sql;
}

if ($postTypeAction == 'accept_change_packet') {
    $val = (isset($_GET['down']) ? 0 : 1);
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    $sql = "UPDATE packets SET jr_od = '" . $_POST['dod'] . "', jr_do = '" . $_POST['ddo'] . "' WHERE id = " . $_POST['changepack'];
    mysql_query($sql);
}

if (isset($_GET['put']) || isset($_GET['down'])) {
    $val = (isset($_GET['down']) ? 0 : 1);
    $valexp = $val;
    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_select_db($con_db);
    $sql = "UPDATE packets SET jeplatny = " . $val . " WHERE id = " . (isset($_GET['down']) ? $_GET['down'] : $_GET['put']);
    mysql_query($sql);
    /*    if ($val == 1) {
      ?>
      <script>
      document.frm['typeaction'].value = 'export_mobile';
      document.frm['exportpack'].value = '<?php echo $_GET[put]; ?>';
      document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1];
      document.frm.submit();
      </script>
      <?php
      } */
}


if (!isset($_GET['pack'])) {
    ?>
    <script type="text/javascript">
        var vlocation = <?php echo getLocation($_POST["username"]); ?>;
        var vpacket = null;
    </script>

    <form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2">

        <?php
        for ($i = 1; $i >= 0; $i--) {
            $connect = mysql_connect($con_server, $con_db, $con_pass);
            mysql_select_db($con_db);
            $sql = mysql_query("SELECT packet, jr_od, jr_do, jeplatny,
          (select count(c_sloupce) from jrtypes where idlocation=" . getLocation($_POST["username"]) . " and packet = packets.packet) as pocet_sloupcu,
          (select count(c_kodu) from jrvargrfs where idtimepozn in (select idtimepozn from jrtypes where idlocation=" . getLocation($_POST["username"]) . " and packet = packets.packet)) as pocet_variant,
          id
          FROM packets WHERE jeplatny = " . $i . " and location=" . getLocation($_POST["username"]) . " order by jr_od DESC, packet DESC");
            ?>

            <?php
            if ($i > 0) {
                ?>
                <div class="button" id="add_packet" style="height: 35px; width: 150px; visibility: <?php echo (!isset($_GET['epack'])) ? 'visible' : 'hidden'; ?>;" title="Přidat balíček" onclick="addPacket();">
                    <span></span><img src="image/addplus.png">
                    přidat balíček
                </div>
                <?php
            }
            ?>


            <div class="separdivglobalnapis"><?php echo ($i > 0) ? 'balíčky AKTIVNÍ' : 'balíčky NEAKTIVNÍ'; ?></div>

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
                        <?php
                        if ((isset($_GET['epack'])) && ($_GET['epack'] == $row[6])) {
                            ?>
                            <td class="last" style="width: auto;">
                                <div id="select_datum_od_<?php echo $row[6]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                                    <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                                        <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                                        <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_od_<?php echo $row[6]; ?>"></a>
                            <!--                  <input id="dod" name="dod" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">-->
                                    </span>
                                </div>
                                <script type="text/javascript">
                                    var Kalend_od_<?php echo $row[6]; ?> = new JRKalendar('select_datum_od_<?php echo $row[6]; ?>', 'a_select_datum_od_<?php echo $row[6]; ?>', null);
                                    Kalend_od_<?php echo $row[6]; ?>.initialize('<?php echo $row[1]; ?>', 'Kalend_od_<?php echo $row[6]; ?>');
                                    Kalend_od_<?php echo $row[6]; ?>.settoall();
                                    Kalend_od_<?php echo $row[6]; ?>.setCodepage('UTF');
                                    Kalend_od_<?php echo $row[6]; ?>.setZIndex(100001);
                                </script>
                            </td>

                            <td class="last" style="width: auto;">
                                <div id="select_datum_do_<?php echo $row[6]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                                    <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                                        <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                                        <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_do_<?php echo $row[6]; ?>"></a>
                            <!--                <input id="ddo" name="ddo" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">-->
                                    </span>
                                </div>
                                <script type="text/javascript">
                                    var Kalend_do_<?php echo $row[6]; ?> = new JRKalendar('select_datum_do_<?php echo $row[6]; ?>', 'a_select_datum_do_<?php echo $row[6]; ?>', null);
                                    Kalend_do_<?php echo $row[6]; ?>.initialize('<?php echo $row[2]; ?>', 'Kalend_do_<?php echo $row[6]; ?>');
                                    Kalend_do_<?php echo $row[6]; ?>.settoall();
                                    Kalend_do_<?php echo $row[6]; ?>.setCodepage('UTF');
                                    Kalend_do_<?php echo $row[6]; ?>.setZIndex(100001);
                                </script>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td class="last" style="width: auto;"><?php echo $d_od; ?></td>
                            <td class="last" style="width: auto;"><?php echo $d_do; ?></td>
                            <?php
                        }
                        ?>
            <!--          <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ($row[3] == 1 ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0);'); ?>"><?php echo ($row[3] == 1 ? 'ANO' : 'NE'); ?></td>
            <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'background-color: rgb(78, 122, 255)' : 'background-color: rgb(183, 0, 0)'); ?>"><?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'ANO' : 'NE'); ?></td>        -->
                        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ($row[3] == 1 ? 'background-color: #34916C' : 'background-color: #B53929;'); ?>"><?php echo ($row[3] == 1 ? 'ANO' : 'NE'); ?></td>
                        <td class="first" style="font-size: 15px; font-weight: bold; width: auto; text-align: center; <?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'background-color: #34916C' : 'background-color: #B53929'); ?>"><?php echo ((($row[4] > 0) && ($row[5] > 0)) ? 'ANO' : 'NE'); ?></td>
                        <td class="first">
                            <?php
                            if ((isset($_GET['epack'])) && ($_GET['epack'] == $row[6])) {
                                ?>
                                <input id="dod" name="dod" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                                <input id="ddo" name="ddo" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                                <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['dod'].value = Kalend_od_<?php echo $row[6]; ?>.y + '-' + Kalend_od_<?php echo $row[6]; ?>.m + '-' + Kalend_od_<?php echo $row[6]; ?>.d; document.frm['ddo'].value = Kalend_do_<?php echo $row[6]; ?>.y + '-' + Kalend_do_<?php echo $row[6]; ?>.m + '-' + Kalend_do_<?php echo $row[6]; ?>.d; document.frm['typeaction'].value = 'accept_change_packet'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>
                                &nbsp;
                                <a style="color: #ffffff; word-wrap: nowrap;" title="Storno" href="?page=2"><img src="image/abort.png"></a>
                                <input id="changepack" name="changepack" type="text" value="<?php echo $row[6]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                                <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                                <?php
                            }
                            if (!isset($_GET['epack'])) {
                                ?>
                                <a name="edit_sloupec" style="color: #ffffff;" href="?page=2&<?php echo ($i > 0 ? 'down' : 'put'); ?>=<?php echo $row[6]; ?>&packet=<?php echo $row[0]; ?>" title="Aktivace/Deaktivace balíčku dat" onClick="app_href(this);"><img src="<?php echo ($i > 0 ? 'image/application_put.png' : 'image/application_get.png'); ?>"></a>
                                &nbsp;&nbsp;
                                <a name="edit_sloupec" style="color: #ffffff;" href="?page=2&pack=<?php echo $row[0]; ?>&sub=1" title="Editace balíčku"><img src="image/pencil.png"></a>
                                &nbsp;&nbsp;
                                <a name="edit_sloupec" style="color: #ffffff;" href="?page=2&epack=<?php echo $row[6]; ?>" title="Editace platnosti balíčku" onClick="app_href(this);"><img src="image/calendar.png"></a>
                                &nbsp;&nbsp;
                                <?php
                                if ($i > 0) {
                                    ?>
                                    <a name="edit_sloupec" title="Export dat pro mobilní zařízení" style="color: #ffffff;" onClick="document.frm['typeaction'].value = 'export_mobile'; document.frm['exportpack'].value = '<?php echo $row[0]; ?>'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/disk.png"></a>
                                    <?php
                                }
                                ?>
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

        if (!isset($_GET['epack'])) {
            ?>
            <input id="exportpack" name="exportpack" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
            <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
            <?php
        }
    } else {
        ?>
        <!-- #1FB0FF-->
        <div style="background-color: #4D84A1; padding-top: 5px; padding-bottom: 5px; white-space: nowrap; display: inline-block; width: 100%;">
            <ul style="display: inline;">
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=1" title="Linky">Linky</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=2" title="Poznánmky">Poznámky</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=3" title="Sloupce">Sloupce JŘ</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=4" title="Sdružení">Sdružování poznámek</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=5" title="Zastávky">Zastávky</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=6" title="Trasy linek">Trasy linek</a>
                </li>
                <li class="submenuitem" style="margin-left: 30px; float: left;">
                    <a style="font-weight: normal;" href="?page=<?php echo $_GET['page']; ?>&pack=<?php echo $_GET['pack']; ?>&sub=7" title="Kalendář provozu">Kalendář provozu</a>
                </li>
            </ul>
        </div>
        <?php
        if ($_GET['sub'] == 1) {
            include 'P2_1_Linky.php';
        }

        if ($_GET['sub'] == 2) {
            include 'P2_2_Poznamky.php';
        }

        if ($_GET['sub'] == 3) {
            include 'P2_3_Sloupce.php';
        }

        if ($_GET['sub'] == 4) {
            include 'P2_4_Sdruzeni.php';
        }

        if ($_GET['sub'] == 5) {
            include 'P2_5_Zastavky.php';
        }
        if ($_GET['sub'] == 6) {
            include 'P2_6_TrasyLinky.php';
        }
        if ($_GET['sub'] == 7) {
            include 'P2_7_Kalendar.php';
        }
    }
//    echo "value = " . $val;
    ?>
</form>

<?php
if ($postTypeAction == 'export_mobile') {
    ?>
    <script>
        function disable_all_buttons() {
            var nc = new Array();
            nc = document.getElementsByName('edit_sloupec');
            for (i = 0; i < nc.length; i++) {
                if (nc[i] != null) {
                    tag = nc[i]
                    tag.style.visibility = 'hidden'
                }
            }
            document.getElementById('add_packet').style.visibility = 'hidden';
        }

        disable_all_buttons();
    </script>
    <div id="export_div" style=" display: inline-block; position: fixed; top: 0; bottom: 0; left: 0; right: 0; width: 350px; height: 100px; margin: auto; background-color: #f3f3f3;">


        <div style="background-color: #f3f3f3; border-radius: 5px 5px 5px 5px; border-width: 1px; border-style: solid; border-color: #000000; box-shadow:10px 10px 5px gray;">

            <div class="separdivglobalnapis"><?php echo 'EXPORT pro mobilní zařízení'; ?></div>

            <table>
                <tr>
                    <td style="font-size: 15px; font-weight: normal; width: 100%;">
                        <div id="progressbox" class="progressbox" style="visibility: visible;"><div id="progressbar" class="progressbar"  style="visibility: visible;"></div ><div id="statustxt" class="statustxt" style="visibility: visible;">0%</div></div>
                    </td>
                    <td style="font-size: 15px; font-weight: bold; width: 100%;"><img id="i_export" style="visibility: hidden;" src="image/loader7.gif"></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="font-size: 15px; font-weight: normal; width: auto;">
                        <div id="progressbox1" class="progressbox" style="visibility: visible;"><div id="progressbar1" class="progressbar"  style="visibility: visible;"></div ><div id="statustxt1" class="statustxt" style="visibility: visible;">0%</div></div>
                    </td>
                    <td style="font-size: 15px; font-weight: bold; width: auto;"><img id="i_export1" style="visibility: hidden;" src="image/loader7.gif"></td>
                </tr>
            </table>

            <!--      <div style="margin-left: 20px; margin-right: 20px;">
                    <div class="button" id="startexport" style="height: 25px; width: 150px; visibility: visible;" onclick="document.frm['typeaction'].value = 'export_mobile_now'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();">
                      <span></span><img src="image/disk.png">
                      Exportovat
                    </div>
                  </div>-->

            <div id="output" style="margin-left: 20px; margin-right: 20px; margin-bottom: 20px;">
                <?php
                include '../lib/CZlang.php';
//        include '../lib/param.php';
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
                $CENTRAL_ZASTAVKY = null;
                $OFFSETY = null;
                $LINKY = array();
                $TRASY = null;
                ?>

                <?php
                /* $idlocation = $_GET[un];
                  $packet = $_GET[p]; */
                $idlocation = getLocation($_POST["username"]);
                $packet = $_POST['exportpack'];
                $target_path = "../../jrdata/" . $idlocation . "/" . $packet . '/';

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
                    global $CENTRAL_ZASTAVKY;

                    $res = null;
                    $connect = mysql_connect($con_server, $con_db, $con_pass);
                    mysql_select_db($con_db);
                    mysql_query("SET NAMES 'cp1250';");
                    $sql = mysql_query("SELECT count(C_ZASTAVKY)
            FROM `savvy_mhdspoje`.`zastavky` WHERE packet = " . $packet . " and idlocation=" . $idlocation . " order by c_zastavkysort");
                    $row = mysql_fetch_row($sql);
                    $celkem = $row[0];

                    /*          $sql = mysql_query("SELECT C_ZASTAVKY, NAZEV, PK1, PK2, PK3, PK4, PK5, PK6, IDLOCATION, PACKET, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT, EXISTS (SELECT * FROM zaslinky WHERE zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.prestup = 1) as prestup,
                      (PK1 & PK2 & PK3 & PK4 & PK5 & PK6) as ma_poznamky
                      FROM `savvy_mhdspoje`.`zastavky` WHERE packet = " . $packet . " and idlocation=" . $idlocation . " order by c_zastavkysort"); */

                    $sql = mysql_query("SELECT distinct zastavky.C_ZASTAVKY, NAZEV, zastavky.PK1, zastavky.PK2, zastavky.PK3, zastavky.PK4, zastavky.PK5, zastavky.PK6, zastavky.IDLOCATION, zastavky.PACKET, LOCA, LOCB, ZKRATKA, C_ZASTAVKYSORT, EXISTS (SELECT * FROM zaslinky WHERE zaslinky.c_zastavky = zastavky.c_zastavky and zaslinky.idlocation = zastavky.idlocation and zaslinky.packet = zastavky.packet and zaslinky.prestup = 1) as prestup,
          (zastavky.PK1 & zastavky.PK2 & zastavky.PK3 & zastavky.PK4 & zastavky.PK5 & zastavky.PK6) as ma_poznamky
          FROM `savvy_mhdspoje`.`zastavky` left outer join zaslinky
          on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky) WHERE zastavky.packet =  " . $packet . " and zastavky.idlocation= " . $idlocation . " and (zaslinky.zast_a = 1 or zaslinky.zast_b = 1) ORDER BY zastavky.nazev COLLATE utf8_czech_ci");

                    $poradi = 1;
                    while ($row = mysql_fetch_row($sql)) {
//            $pomZastavka = new TZastavka();
                        $CENTRAL_ZASTAVKY[$row[0]] = $poradi;
//            $pomZastavka->c_zastavky = $poradi;
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

                        $proc = round($poradi / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();

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
                    $sql = mysql_query("SELECT DISTINCT count(DATUM) FROM `savvy_mhdspoje`.`kalendar` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " ORDER BY DATUM");
                    $row = mysql_fetch_row($sql);
                    $celkem = $row[0];
                    $sql = mysql_query("SELECT DISTINCT DATUM, PK FROM `savvy_mhdspoje`.`kalendar` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " ORDER BY DATUM");
                    $poradi = 1;
                    while ($row = mysql_fetch_row($sql)) {
                        $proc = round($poradi / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();

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
                    $celkem = count($CENTRAL_POZNAMKY);
                    foreach ($CENTRAL_POZNAMKY as $key_c_kodu => $val) {
                        $proc = round($poradi / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();
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
                    $sql = mysql_query("SELECT DISTINCT count(C_LINKY) from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " AND VYBER = 1 ORDER BY C_LINKYSORT");
                    $row = mysql_fetch_row($sql);
                    $celkem = $row[0];
                    $sql = mysql_query("SELECT DISTINCT C_LINKY from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " AND VYBER = 1 ORDER BY C_LINKYSORT");
                    $poradiLinka = 1;
                    while ($row = mysql_fetch_row($sql)) {
                        $proc = round($poradiLinka / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();
                        $connect = mysql_connect($con_server, $con_db, $con_pass);
                        mysql_select_db($con_db);
                        mysql_query("SET NAMES 'cp1250';");
                        if ($idlocation == 13) {
                            $sql1 = mysql_query("select SMER, CHRONO, HH, MM, PK1, PK2, PK3, PK4, PK5, PK6, PK7, PK8, PK9, PK10, C_SPOJE from spoje where idlocation = " . $idlocation . " AND PACKET = " . $packet . " AND c_linky = '" . $row[0] . "' AND spoje.voz = 1 " /* AND (spoje.vlastnosti & 2048) <> 2048 */ . " group by c_linky, smer, chrono, pk1, pk2, pk3, pk4, pk5, pk6, pk7, pk8, pk9, pk10, c_tarif, c_zastavky, hh, mm, kodpozn order by c_linky, smer, HH, MM");
                        } else {
                            $sql1 = mysql_query("select SMER, CHRONO, HH, MM, PK1, PK2, PK3, PK4, PK5, PK6, PK7, PK8, PK9, PK10, C_SPOJE from spoje where idlocation = " . $idlocation . " AND PACKET = " . $packet . " AND c_linky = '" . $row[0] . "' AND spoje.voz = 1 " /* AND (spoje.vlastnosti & 2048) <> 2048 */ . " order by c_linky, smer, HH, MM");
                        }
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
                        $poradiLinka++;
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
                    global $CENTRAL_ZASTAVKY;

                    $resall = null;
                    $connect = mysql_connect($con_server, $con_db, $con_pass);
                    mysql_select_db($con_db);
                    mysql_query("SET NAMES 'cp1250';");
                    $sql = mysql_query("SELECT DISTINCT count(C_LINKY) from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " AND VYBER = 1 ORDER BY C_LINKYSORT");
                    $row = mysql_fetch_row($sql);
                    $celkem = $row[0];
                    $sql = mysql_query("SELECT DISTINCT C_LINKY from `savvy_mhdspoje`.`linky` WHERE idlocation=" . $idlocation . " AND PACKET = " . $packet . " AND VYBER = 1 ORDER BY C_LINKYSORT");
                    $poradiLinka = 0;
                    while ($row = mysql_fetch_row($sql)) {
                        $proc = round($poradiLinka / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();
                        $poradiLinka++;

                        $connect = mysql_connect($con_server, $con_db, $con_pass);
                        mysql_select_db($con_db);
                        mysql_query("SET NAMES 'cp1250';");
                        if ($idlocation == 23) {
                            $sql1 = mysql_query(
                                    "select distinct zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3
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

          left outer join (select distinct * from zastavky where zastavky.idlocation = " . $idlocation . " and zastavky.packet = " . $packet . " group by c_zastavky) zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)

          where zaslinky.idlocation = " . $idlocation . " and zaslinky.packet = " . $packet . " and zaslinky.c_linky = '" . $row[0] . "'
          ORDER BY zaslinky.c_tarif");
                        } else {
                            $sql1 = mysql_query(
                                    "select distinct zaslinky.c_zastavky, zaslinky.c_tarif, zastavky.nazev, zaslinky.pk1, zaslinky.pk2, zaslinky.pk3
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
                        }

                        $pocet_zastavek = 0;
                        $res = null;
                        $vyb_tarif = null;
                        $TRASA = null;
//                        echo $row[0] . ";</br>";
                        while ($row_zastavky = mysql_fetch_row($sql1)) {
                            if (($row_zastavky[10] == 1) || ($row_zastavky[11] == 1)) {
                                $newzastavka = new TZastavky();
                                $newzastavka->c_tarif = $row_zastavky[1];
                                $newzastavka->staviA = $row_zastavky[10];
                                $newzastavka->staviB = $row_zastavky[11];

                                $newzastavka->cislo_zastavky = $CENTRAL_ZASTAVKY[$row_zastavky[0]]; //$row_zastavky[0];
//                                echo $newzastavka->cislo_zastavky . ";";
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
                    $sql = mysql_query("select count(c_linky)
           from savvy_mhdspoje.linky
           WHERE idlocation=" . $idlocation . " and packet = " . $packet . " and jr_do >= '" . $row[0] . "' and vyber = 1 order by c_linkysort");
                    $row1 = mysql_fetch_row($sql);
                    $celkem = $row1[0];

                    if ($idlocation == 13) {
                        $sql = mysql_query("select c_linky, cast(nazev_linky as char(6)), doprava, jr_od, jr_do,
             (select count(spoju_a) from (select count(spoje.c_spoje) as spoju_a, c_linky from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and smer = 0 group by chrono, pk1, pk2, pk3, pk4, pk5, pk6, pk7, pk8, pk9, pk10, c_tarif, c_zastavky, hh, mm, kodpozn) as spojua where c_linky = linky.c_linky) as spoje_a,
             (select count(spoju_b) from (select count(spoje.c_spoje) as spoju_b, c_linky from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and smer = 1 group by chrono, pk1, pk2, pk3, pk4, pk5, pk6, pk7, pk8, pk9, pk10, c_tarif, c_zastavky, hh, mm, kodpozn) as spojub where c_linky = linky.c_linky) as spoje_b, smera, smerb
             from savvy_mhdspoje.linky WHERE idlocation=" . $idlocation . " and packet = " . $packet . " and jr_do >= '" . $row[0] . "' and vyber = 1 order by c_linkysort");
                    } else {
                        $sql = mysql_query("select c_linky, cast(nazev_linky as char(6)), doprava, jr_od, jr_do,
             (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 0) as spoje_a,
             (select count(spoje.c_spoje) from spoje where spoje.idlocation = " . $idlocation . " and spoje.packet = " . $packet . " and spoje.c_linky = linky.c_linky and smer = 1) as spoje_b, smera, smerb
             from savvy_mhdspoje.linky
             WHERE idlocation=" . $idlocation . " and packet = " . $packet . " and jr_do >= '" . $row[0] . "' and vyber = 1 order by c_linkysort");
                    }
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
                        $proc = round($poradi / $celkem * 100);
                        ?>
                        <script type="text/javascript">
                            document.getElementById('progressbar1').style.width = "<?php echo $proc; ?>%";
                            document.getElementById('statustxt1').innerHTML = "<?php echo $proc; ?>%";
                        </script>
                        <?php
                        ob_flush();
                        flush();

                        $nazev_linky = '';
                        $delka_nazvu_linky = strlen(iconv('cp1250', 'ASCII//TRANSLIT', $row[1]));
                        for ($idelka = 0; $idelka < 6 - $delka_nazvu_linky; $idelka++) {
                            $nazev_linky .= ' ';
                        }
                        $nazev_linky .= iconv('cp1250', 'ASCII//TRANSLIT', $row[1]);
                        $res .= $nazev_linky; //*/$row[1];
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

                        if (($idlocation == 1) || ($idlocation == 2) /* || ($idlocation == 7) */) {
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

                $procento = round(100 / 9 * 1);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                loadPoznamky($idlocation, $packet);
                echo "</br>export seznamu zastávek ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_ZASTAVKY($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 2);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export kalendáře ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_KALENDAR($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 3);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export seznamu poznámek ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_POZNAMKY($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 4);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export spojů ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_SPOJE($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 5);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export chronometráží ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_CHRONO($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 6);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export seznamu linek ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_LINKY($idlocation, $packet, $target_path);
                echo "HOTOVO";
                $procento = round(100 / 9 * 7);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                echo "</br>export seznamu přestupních bodů ... ";
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
//        exp_BODY($idlocation, $packet, $target_path);
                echo "HOTOVO";
                echo "</br>";
                echo "</br>";
                $procento = round(100 / 9 * 8);
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                exp_PRESTUPY($idlocation, $packet, $target_path);
                $procento = 100;
                ?>
                <script type="text/javascript">
                    document.getElementById('progressbar').style.width = "<?php echo $procento; ?>%";
                    document.getElementById('statustxt').innerHTML = "<?php echo $procento; ?>%";
                </script>
                <?php ?>
                <script type="text/javascript">
                    document.getElementById('progressbar1').style.width = "<?php echo 0; ?>%";
                    document.getElementById('statustxt1').innerHTML = "<?php echo 0; ?>%";
                </script>
                <?php
                ob_flush();
                flush();
                /* foreach ($OFFSETY as $key_c_linky => $val) {
                  echo '///' . $key_c_linky . '/' . conv_num_byte($val->spoje, 3);
                  } */
                ?>
                <div class="button" id="startexport" style="height: 25px; width: 150px; visibility: visible;" onclick="document.location.href = '?page=2';">
                    <span></span><img src="image/abort.png">
                    Zavřít
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<script type='text/javascript'>

    function addPacket(num_row) {
        var nc = new Array();
        nc = document.getElementsByName('edit_sloupec');
        for (i = 0; i < nc.length; i++) {
            if (nc[i] != null) {
                tag = nc[i]
                tag.style.visibility = 'hidden'
            }
        }
        document.getElementById('add_packet').style.visibility = 'hidden';

        var tablef = document.getElementById('table_packets_0');
        var row = tablef.insertRow(tablef.rows.length);
        //    window.scroll(0, tablef.offsetTop);

        var td = row.insertCell(0);
        td.className = "last";
        td.style.fontSize = '15px';
        td.style.fontWeight = 'bold';
        td.style.width = 'auto';

        var fieldtext = document.createElement('input');
        fieldtext.type = 'text';
        fieldtext.id = 'packetnumber';
        fieldtext.name = 'packetnumber';
        fieldtext.style.width = '50px';
        fieldtext.value = '';
        td.appendChild(fieldtext);
        fieldtext.focus();

        var td = row.insertCell(1);
        td.className = "last";
        td.style.width = 'auto';

        var divfield = document.createElement('div');
        divfield.id = 'select_datum_od_insert';
        divfield.style.backgroundColor = 'white';
        divfield.style.borderColor = '#ABADB3';
        divfield.style.borderWidth = '1px 1px 1px 1px';
        divfield.style.borderStyle = 'solid';
        divfield.style.verticalAlign = 'middle';
        divfield.style.cursor = 'pointer';
        td.appendChild(divfield);

        var spanfield = document.createElement('span');
        spanfield.style.height = '100%';
        spanfield.style.cursor = 'pointer';
        spanfield.style.verticalAlign = 'middle';
        divfield.appendChild(spanfield);

        var imgfield = document.createElement('img');
        imgfield.style.marginLeft = '3px';
        imgfield.style.verticalAlign = 'middle';
        imgfield.src = 'image/calendar.png';
        divfield.appendChild(imgfield);

        var afield = document.createElement('a');
        afield.id = 'a_select_datum_od_insert';
        afield.style.height = '100%';
        afield.style.marginLeft = '5px';
        afield.style.marginRight = '5px';
        afield.style.verticalAlign = 'middle';
        divfield.appendChild(afield);

        dnes = new Date();
        den = dnes.getDate();
        mesic = dnes.getMonth() + 1;
        rok = dnes.getFullYear();
        d_od = rok + "-" + mesic + "-" + den;

        Kalend_od_insert = new JRKalendar('select_datum_od_insert', 'a_select_datum_od_insert', null);
        Kalend_od_insert.initialize(d_od, 'Kalend_od_insert');
        Kalend_od_insert.settoall();
        Kalend_od_insert.setZIndex(100001);

        var td = row.insertCell(2);
        td.className = "last";
        td.style.width = 'auto';

        var divfield = document.createElement('div');
        divfield.id = 'select_datum_do_insert';
        divfield.style.backgroundColor = 'white';
        divfield.style.borderColor = '#ABADB3';
        divfield.style.borderWidth = '1px 1px 1px 1px';
        divfield.style.borderStyle = 'solid';
        divfield.style.verticalAlign = 'middle';
        divfield.style.cursor = 'pointer';
        td.appendChild(divfield);

        var spanfield = document.createElement('span');
        spanfield.style.height = '100%';
        spanfield.style.cursor = 'pointer';
        spanfield.style.verticalAlign = 'middle';
        divfield.appendChild(spanfield);

        var imgfield = document.createElement('img');
        imgfield.style.marginLeft = '3px';
        imgfield.style.verticalAlign = 'middle';
        imgfield.src = 'image/calendar.png';
        divfield.appendChild(imgfield);

        var afield = document.createElement('a');
        afield.id = 'a_select_datum_do_insert';
        afield.style.height = '100%';
        afield.style.marginLeft = '5px';
        afield.style.marginRight = '5px';
        afield.style.verticalAlign = 'middle';
        divfield.appendChild(afield);

        dnes = new Date();
        den = dnes.getDate();
        mesic = dnes.getMonth() + 1;
        rok = dnes.getFullYear();
        d_do = rok + "-" + mesic + "-" + den;

        Kalend_do_insert = new JRKalendar('select_datum_do_insert', 'a_select_datum_do_insert', null);
        Kalend_do_insert.initialize(d_do, 'Kalend_do_insert');
        Kalend_do_insert.settoall();
        Kalend_do_insert.setZIndex(100001);

        var td = row.insertCell(3);
        td.className = "first";
        td.style.verticalAlign = 'top';

        var td = row.insertCell(4);
        td.className = "first";
        td.style.verticalAlign = 'top';

        var td = row.insertCell(5);
        td.className = "first";
        td.style.verticalAlign = 'top';

        var fieldtext = document.createElement('input');
        fieldtext.type = 'text';
        fieldtext.id = 'dod';
        fieldtext.name = 'dod';
        fieldtext.style.visibility = 'hidden';
        fieldtext.style.width = '0px';
        fieldtext.style.margin = '0px';
        fieldtext.style.padding = '0px';
        fieldtext.style.overflow = 'hidden';
        fieldtext.value = '';
        td.appendChild(fieldtext);

        var fieldtext = document.createElement('input');
        fieldtext.type = 'text';
        fieldtext.id = 'ddo';
        fieldtext.name = 'ddo';
        fieldtext.style.visibility = 'hidden';
        fieldtext.style.width = '0px';
        fieldtext.style.margin = '0px';
        fieldtext.style.padding = '0px';
        fieldtext.style.overflow = 'hidden';
        fieldtext.value = '';
        td.appendChild(fieldtext);

        /*var fieldtext = document.createElement('input');
         fieldtext.type = 'text';
         fieldtext.id = 'typeaction';
         fieldtext.name = 'typeaction';
         fieldtext.style.visibility = 'hidden';
         fieldtext.style.width = '0px';
         fieldtext.style.margin = '0px';
         fieldtext.style.padding = '0px';
         fieldtext.style.overflow = 'hidden';
         fieldtext.value = '';
         td.appendChild(fieldtext);*/

        var a = document.createElement('a');
        a.style.color = '#ffffff';
        a.style.wordWrap = 'nowrap';
        a.title = 'Zapsat';
        a.onclick = function () {
            document.frm['dod'].value = Kalend_od_insert.y + '-' + Kalend_od_insert.m + '-' + Kalend_od_insert.d;
            document.frm['ddo'].value = Kalend_do_insert.y + '-' + Kalend_do_insert.m + '-' + Kalend_do_insert.d;
            document.frm['typeaction'].value = 'insert_packet';
            document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1];
            document.frm.submit();
        }
        var img = document.createElement('img');
        img.src = "image/accept.png";
        a.appendChild(img);
        td.appendChild(a);

        var text = document.createTextNode('\u00A0');
        td.appendChild(text);
        var text = document.createTextNode('\u00A0');
        td.appendChild(text);

        var a = document.createElement('a');
        a.style.color = '#ffffff';
        a.style.wordWrap = 'nowrap';
        a.title = 'Odvolat';
        a.href = '?page=2';
        a.onclick = function () {
            app_href(this);
        }
        var img = document.createElement('img');
        img.src = "image/abort.png";
        a.appendChild(img);
        td.appendChild(a);
    }
</script>

<?php
if ($valexp == 1) {
    $valexp = 0;
    ?>
    <script type="text/javascript">
        window.onload = new function () {
            document.frm['typeaction'].value = 'export_mobile';
            document.frm['exportpack'].value = '<?php echo $_GET[packet]; ?>';
            document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1];
            document.frm.submit();
        }
    </script>
    <?php
}
?>