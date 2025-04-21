<link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
<script type="text/javascript" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>

<?php
if ((getLocation($_POST["username"]) == 3) || (getLocation($_POST["username"]) == 11)) {

    if ($_POST['post'] == 'accept_upload') {
        if ($_FILES['upload']['name'] != '') {
            $target_path = "reklamadata/" . getLocation($_POST['username']) . "/";
            $target_path1 = $target_path . basename(iconv('windows-1250', 'UTF-8', $_FILES['upload']['name']));
            if (move_uploaded_file(iconv('windows-1250', 'UTF-8', $_FILES['upload']['tmp_name']), $target_path1)) {
//                $val = (isset($_GET[down]) ? 0 : 1);                
            }
        }
        $connect = mysql_connect($con_server, $con_db, $con_pass);
        mysql_select_db($con_db);
        $sql = "INSERT INTO reklama (idlocation, popis, soubor, show_od, show_do, delka, active, headertxt, txt) VALUES (" . getLocation($_POST['username']) . ",
                    '" . $_POST['popis_add'] . "', " . (($_FILES['upload']['name'] != '') ? '\'' . basename(iconv('windows-1250', 'UTF-8', $_FILES['upload']['name'])) . '\'' : 'null') . ", '" . $_POST['dod_add'] . "', '" . $_POST['ddo_add'] . "', '" . $_POST['delka_add'] . "', 0, '" . $_POST['headertxt_add'] . "', '" . $_POST['txt_add'] . "')";
        mysql_query($sql);
        mysql_close($connect);
//        echo $sql;
    }

    if ($_POST['post'] == 'accept_change_row') {
        $connect = mysql_connect($con_server, $con_db, $con_pass);
        mysql_select_db($con_db);
        $sql = "update reklama set show_od = '" . $_POST['dod'] . "', show_do = '" . $_POST['ddo'] . "', popis = '" . $_POST[popis] . "' , delka = '" . $_POST[delka] . "', headertxt = '" . $_POST[headertxt] . "', txt = '" . $_POST[txt] . "' where id = '" . $_POST[idrow] . "'";
        mysql_query($sql);
        mysql_close($connect);
//        echo $sql;
    }

    if ($_POST['post'] == 'delete_row') {
        $connect = mysql_connect($con_server, $con_db, $con_pass);
        mysql_select_db($con_db);
        $sql = "delete from reklama where id = '" . $_POST[rowdel] . "'";
        mysql_query($sql);
        mysql_close($connect);
//        echo $sql;
    }

    $connect = mysql_connect($con_server, $con_db, $con_pass);
    mysql_query("SET NAMES 'cp1250';");
    mysql_select_db($con_db);
    $sql = mysql_query("SELECT ID, POPIS, SHOW_OD, SHOW_DO, DELKA, SOUBOR, ACTIVE, HEADERTXT, TXT 
          FROM reklama WHERE idlocation=" . getLocation($_POST["username"]) . " order by SHOW_OD DESC");
    ?>

    <form style="float: left;" enctype="multipart/form-data" name="importreklama" method="post" action="?page=4">

        <div class="separdivglobalnapis" style="clear: both;">Nahrání reklamy</div>

        <table id="table_reklama" class="t_akce" style="clear: both; float: none;">
            <th>popis</th>
            <th>platná OD</th>
            <th>platná DO</th>
            <th>délka zobrazení</th>
            <th>soubor</th>
            <tr>
                <td class="last" style="font-size: 15px; font-weight: bold; width: 100%;">
                    <input type="text" name="popis_add" id="popis_add" style="width: 100%;" value="<?php echo $row[1]; ?>">
                </td>
                <td class="last" style="width: auto;">
                    <div id="select_datum_od_add" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                        <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                            <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                            <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_od_add"></a>
                        </span>
                    </div>
                    <script type="text/javascript">
                        dnes = new Date();
                        den = dnes.getDay();
                        mesic = dnes.getMonth() + 1;
                        rok = dnes.getFullYear();
                        d_od = rok + "-" + mesic + "-" + den;
                        var Kalend_od_add = new JRKalendar('select_datum_od_add', 'a_select_datum_od_add', null);
                        Kalend_od_add.initialize(d_od, 'Kalend_od_add');
                        Kalend_od_add.settoall();
                        Kalend_od_add.setZIndex(100001);
                    </script>
                </td>

                <td class="last" style="width: auto;">
                    <div id="select_datum_do_add" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                        <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                            <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                            <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_do_add"></a>
                        </span>
                    </div>
                    <script type="text/javascript">
                        dnes = new Date();
                        den = dnes.getDay();
                        mesic = dnes.getMonth() + 1;
                        rok = dnes.getFullYear();
                        d_do = rok + "-" + mesic + "-" + den;
                        var Kalend_do_add = new JRKalendar('select_datum_do_add', 'a_select_datum_do_add', null);
                        Kalend_do_add.initialize(d_do, 'Kalend_do_add');
                        Kalend_do_add.settoall();
                        Kalend_do_add.setZIndex(100001);
                    </script>
                </td>
                <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
                    <input type="text" name="delka_add" id="delka_add" style="width: 100%;" value="5">
                </td>
                <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
                    <input id='upload' name="upload" type="file" />
                </td>                
            </tr>            
        </table>

        <table id="table_reklama+" class="t_akce" style="clear: both; float: none; width: 100%;">
            <th>Nadpis textové zprávy</th>
            <th>Textová zpráva</th>
            <tr>
                <td class="last" style="font-size: 15px; font-weight: bold; width: 30%;">
                    <textarea rows="10" name="headertxt_add" id="headertxt_add" style="width: 100%;"></textarea>
                </td>  
                <td class="last" style="font-size: 15px; font-weight: bold; width: 70%;">
                    <textarea rows="10" name="txt_add" id="txt_add" style="width: 100%;"></textarea>
                </td>                    
            </tr>
        </table>

        <input type="submit" style="width: 0; height: 0; visibility: hidden;" id="SubmitButton" value="Upload" />
        <input id="dod_add" name="dod_add" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
        <input id="ddo_add" name="ddo_add" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
        <div class="button" id="startimport" style="height: 35px; width: 150px; visibility: visible;" onclick="document.importreklama['dod_add'].value = Kalend_od_add.y + '-' + Kalend_od_add.m + '-' + Kalend_od_add.d; document.importreklama['ddo_add'].value = Kalend_do_add.y + '-' + Kalend_do_add.m + '-' + Kalend_do_add.d; document.importreklama['post'].value = 'accept_upload'; document.getElementById('SubmitButton').click();">
            <span></span><img src="image/disk.png">
            Nahrát
        </div>

        <input id="post" name="post" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
        <!-- multiple="multiple" directory="" webkitdirectory="" mozdirectory="" -->        

        <div class="separdivglobalnapis" style="clear: both;">Seznam reklam</div>

        <table id="table_reklama" class="t_akce" style="clear: both; float: none;">
            <tr>
                <th>ID</th>
                <th>popis</th>
                <th>platná OD</th>
                <th>platná DO</th>
                <th>délka zobrazení</th>
                <th>soubor</th>
            </tr>

            <?php
            while ($row = mysql_fetch_row($sql)) {
                list($year, $month, $day) = explode('-', $row[2]);
                $d_od = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;

                list($year, $month, $day) = explode('-', $row[3]);
                $d_do = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
                ?> 

                <tr>
                    <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[0]; ?></td>
                    <?php
                    if ((!isset($_GET['epack'])) || ($_GET['epack'] != $row[0])) {
                        ?>
                        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[1]; ?></td>
                        <?php
                    } else {
                        ?>
                        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
                            <input type="text" name="popis" id="popis" style="width: 100%;" value="<?php echo $row[1]; ?>">
                        </td>
                        <?php
                    }
                    ?>

                    <?php
                    if ((isset($_GET['epack'])) && ($_GET['epack'] == $row[0])) {
                        ?>
                        <td class="last" style="width: auto;">
                            <div id="select_datum_od_<?php echo $row[0]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                                <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                                    <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                                    <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_od_<?php echo $row[0]; ?>"></a>
                                </span>
                            </div>
                            <script type="text/javascript">
                                var Kalend_od_<?php echo $row[0]; ?> = new JRKalendar('select_datum_od_<?php echo $row[0]; ?>', 'a_select_datum_od_<?php echo $row[0]; ?>', null);
                                Kalend_od_<?php echo $row[0]; ?>.initialize('<?php echo $row[2]; ?>', 'Kalend_od_<?php echo $row[0]; ?>');
                                Kalend_od_<?php echo $row[0]; ?>.settoall();
                                Kalend_od_<?php echo $row[0]; ?>.setZIndex(100001);
                            </script>
                        </td>

                        <td class="last" style="width: auto;">
                            <div id="select_datum_do_<?php echo $row[0]; ?>" style="background-color: white; border-color: #ABADB3; border-width: 1px 1px 1px 1px; border-style: solid; vertical-align: middle;">
                                <span style="vertical-align: middle; height: 100%; cursor: pointer;">
                                    <img style="vertical-align: middle; margin-left: 3px;" src="image/calendar.png">
                                    <a style="height: 100%; vertical-align: middle; margin-left: 5px; margin-right: 5px;" id="a_select_datum_do_<?php echo $row[0]; ?>"></a>
                                </span>
                            </div>
                            <script type="text/javascript">
                                var Kalend_do_<?php echo $row[0]; ?> = new JRKalendar('select_datum_do_<?php echo $row[0]; ?>', 'a_select_datum_do_<?php echo $row[0]; ?>', null);
                                Kalend_do_<?php echo $row[0]; ?>.initialize('<?php echo $row[3]; ?>', 'Kalend_do_<?php echo $row[0]; ?>');
                                Kalend_do_<?php echo $row[0]; ?>.settoall();
                                Kalend_do_<?php echo $row[0]; ?>.setZIndex(100001);
                            </script>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td class="last" style="width: auto;"><?php echo $d_od; ?></td>
                        <td class="last" style="width: auto;"><?php echo $d_do; ?></td>
                        <?php
                    }
                    if ((!isset($_GET['epack'])) || ($_GET['epack'] != $row[0])) {
                        ?>
                        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;"><?php echo $row[4]; ?></td>
                        <?php
                    } else {
                        ?>
                        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
                            <input type="text" name="delka" id="delka" style="width: 100%;" value="<?php echo $row[4]; ?>">
                        </td>
                        <?php
                    }
                    ?>
                    <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
                        <?php
                        if ($row[5] != '') {
                            ?>
                            <a href="reklamadata/<?php echo getLocation($_POST['username']); ?>/<?php echo $row[5]; ?>"><?php echo $row[5]; ?></a></td>
                        <?php
                    }
                    ?>
                    <td class="first">
                        <?php
                        if (!isset($_GET['epack'])) {
                            ?>                                     
                            <a name="edit_sloupec" style="color: #ffffff;" href="?page=4&epack=<?php echo $row[0]; ?>" title="Editace balíčku"><img src="image/pencil.png"></a>
                            &nbsp;
                            <a name="del_sloupec" style="color: #ffffff; word-wrap: nowrap;" title="Smazat" onClick="delSloupec('<?php echo $row[0]; ?>');"><img src="image/delete.png"></a>                                                    
                            &nbsp;
                            <?php
                        }
                        if ($_GET['epack'] == $row[0]) {
                            ?>
                            <input id="dod" name="dod" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                            <input id="ddo" name="ddo" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                            <input id="idrow" name="idrow" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
                            <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.importreklama['dod'].value = Kalend_od_<?php echo $row[0]; ?>.y + '-' + Kalend_od_<?php echo $row[0]; ?>.m + '-' + Kalend_od_<?php echo $row[0]; ?>.d; document.importreklama['ddo'].value = Kalend_do_<?php echo $row[0]; ?>.y + '-' + Kalend_do_<?php echo $row[0]; ?>.m + '-' + Kalend_do_<?php echo $row[0]; ?>.d; document.importreklama['idrow'].value = '<?php echo $row[0]; ?>'; document.importreklama['post'].value = 'accept_change_row'; document.importreklama.action = document.importreklama.action + '&scup=' + getScrollXY()[1]; document.importreklama.submit();"><img src="image/accept.png"></a>
                            &nbsp;
                            <a style="color: #ffffff; word-wrap: nowrap;" title="Storno" href="?page=4"><img src="image/abort.png"></a>
                            <!--<input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">-->
                            <?php
                        }
                        ?>
                    </td>
                </tr>                  
                <th>Nadpis textové zprávy</th>
                <th>Textová zpráva</th>
                <tr>
                    <?php
                    if ((!isset($_GET['epack'])) || ($_GET['epack'] != $row[0])) {
                        ?>
                        <td class = "last" style = "font-size: 15px; font-weight: bold; width: 30%; white-space: pre-line; text-align: left;">
                            <?php echo $row[7]; ?>
                        </td>
                        <td class = "last" style = "font-size: 15px; font-weight: bold; width: 70%; white-space: pre-line; text-align: left;">
                            <?php echo $row[8]; ?>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td class = "last" style = "font-size: 15px; font-weight: bold; width: 30%;">
                            <textarea rows = "10" name = "headertxt" id = "headertxt" style = "width: 100%;"><?php echo $row[7]; ?></textarea>
                        </td>
                        <td class = "last" style = "font-size: 15px; font-weight: bold; width: 70%;">
                            <textarea rows = "10" name = "txt" id = "txt" style = "width: 100%;"><?php echo $row[8]; ?></textarea>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="6" style="width: 100%;">
                        <div class="separdiv" style="clear: both;"></div>
                    </td>
                </tr>
                <?php
            }
            mysql_close($connect);
            ?>
        </table>
    </form>
    <?php
} else {
    ?>
    <div class="separdivglobalnapis" style="clear: both;">Modul reklamy není dostupný</div>
    <?php
}
?>

<script  type='text/javascript'>
    function delSloupec(id) {
        if (confirm('opravdu odstranit vybraný záznam ?')) {
            res = document.createElement('input');
            res.type = 'text';
            res.name = 'rowdel';
            res.value = id;
            res.style.visibility = 'hidden';
            document.importreklama.appendChild(res);
            document.importreklama['post'].value = 'delete_row';
            document.importreklama.action = document.importreklama.action + '&scup=' + getScrollXY()[1];
            document.importreklama.submit();
        } else {
            // Do nothing!
        }
    }
</script>    