<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

        <link rel="stylesheet" type="text/css" href="//152.mhdspoje.cz/JRw50new/css/Plzen/menuPlzen.css"/>   
        <link rel="stylesheet" type="text/css" href="//152.mhdspoje.cz/JRw50new/css/Plzen/JRPlzen.css"/>
        <link rel="stylesheet" type="text/css" href="//152.mhdspoje.cz/JRw50new/css/Plzen/kalendarPlzen.css"/>
        <!--        <link rel="stylesheet" type="text/css" href="//152.mhdspoje.cz/JRw50new/css/Plzen/styles.css"/>-->
        <link rel="stylesheet" type="text/css" href="//152.mhdspoje.cz/JRw50new/css/Plzen/style.css"/>
        <TITLE></TITLE>
    </head>
    <!--  <body onclick="kalendarhide(event);">  -->
    <body>
        <style>
            .Rtable {
                display: flex;
                flex-wrap: wrap;
                margin: 0 0 3em 0;
                padding: 0;
            }
            .Rtable--3cols > .Rtable-cell {
                width: auto;
            }
            .Rtable {
                /*position: relative;*/
                top: 3px;
                left: 3px;
            }
            @media all and (max-width: 500px) {
                .Rtable--collapse {
                    display: block;
                }
            }
            .no-flexbox .Rtable {
                display: block;
            }

        </style>

        <?php
        $active_page = $_GET['page'];
        if (!isset($_GET['page'])) {
            $active_page = 6;
        }
        $admininstrator = false;
        $counttagmenu = 17;
        if (isset($_GET['move'])) {
            $move = TRUE;
        } else {
            $move = FALSE;
        }
        $move = FALSE;
        ?>
        <script type="text/javascript">
            var vlocation = 17;
            var vpacket = null;
        </script>
        <script type="text/javascript" src="//152.mhdspoje.cz/JRw50new/js/JRclass50.js" charset="UTF-8"></script>
<!--        <script type="text/javascript" src="//152.mhdspoje.cz/JRw50new/js/kalendar.js"></script>-->


        <div id="heading">
            <div id="headingInner" style="display: block; float: left;">
                <span class="logo"><a href="../." title="Plzeňské městské dopravní podniky">
                        Jízdní řády PMDP</a></span>
                <div id="top_menu">
                    <ul>
                        <li><a title="Web PMDP, a.s." href="http://www.pmdp.cz/cz/">
                                Web PMDP, a.s.</a></li>
                        <li><a title="Kontakt" href="http://www.pmdp.cz/o-nas/kontakty/">
                                Kontakt
                            </a></li>
                    </ul>
                </div>
<!--                <div id="languages">
                    <a href="../vyhledavan_spojeni.aspx">
                        <img src="../css/czech_flag.png"></a> <a href="../vyhledavan_spojeni.aspx?lang=en">
                        <img src="../css/english_flag.png"></a>
                </div>-->


<!--                <div id="bannerLB" style="text-align: right;">
                    <iframe src="//www.qap.cz/iframe_pmdp_leaderboard.htm" scrolling="no" frameborder="0" width="728" height="90" name="qap-iframe-leaderboard" id="qap-iframe-leaderboard"></iframe>
                </div>-->

            </div>
            <div id="bannerLB" style="text-align: center;">
                    <iframe src="//www.qap.cz/iframe_pmdp_leaderboard.htm" scrolling="no" frameborder="0" width="728" height="90" name="qap-iframe-leaderboard" id="qap-iframe-leaderboard"></iframe>
                </div>
        </div>

        <nav class="menu">
            <ul>
                <li> <?php echo (($active_page == 6) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 6) ? 'class="active"' : ''); ?> href="?page=6">Vyhledat spojení</a></li>
                <li> <?php echo (($active_page == 5) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 5) ? 'class="active"' : ''); ?> href="?page=5">Panel odjezdů</a></li>
                <li> <?php echo (($active_page == 1) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 1) ? 'class="active"' : ''); ?> href="?page=1">Zastávkové JŘ</a></li>
                <li> <?php echo (($active_page == 8) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 8) ? 'class="active"' : ''); ?> href="?page=8">Zastávky</a></li>
                <li> <?php echo (($active_page == 0) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 0) ? 'class="active"' : ''); ?> href="//www.pmdp.cz/informace-o-preprave/mobilni-aplikace/">JŘ v mobilu</a></li>
                <li> <?php echo (($active_page == 0) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 0) ? 'class="active"' : ''); ?> href="//www.pmdp.cz/informace-o-preprave/zmeny-v-doprave/">Změny v dopravě</a></li>
                <li> <?php echo (($active_page == 0) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 0) ? 'class="active"' : ''); ?> href="?page=1">Mapa</a></li>
                <li> <?php echo (($active_page == 0) ? 'class="active"' : ''); ?>><a <?php echo (($active_page == 0) ? 'class="active"' : ''); ?> href="?page=1">Poloha vozidel</a></li>
            </ul>
        </nav>

        <?php
        if (($active_page == 5) || ($active_page == 1) || ($active_page == 8)) {
            ?>
            <div style="visibility: hidden; height: 0px;" class="vyber" id="select_datum"> 
                <a class="a_select_datum" id="a_select_datum"></a>
            </div>
            <?php
        }
        ?> 

        <!--        <div id="page-bg1">
                    <div id="page-bg2">
                        <div id="page-bg3">
                            <div id="page-bg4">
                                <div id="page_container">
        
        
                                    <div id="content_main">-->

        <?php
        if (($active_page == 1) || ($active_page == 2) || ($active_page == 3)) {
            ?>
            <div id="div_header_vyber" class="content_div">   
                <table class="tablevyber" style="border-color: #ffcc33; border-collapse: collapse; width: 100%">
                    <tr>
                        <td style="background-color: #ffcc33; white-space: nowrap; min-width: 107px; width: 107px;">
                            <label for="select_linka" class="label_main">
                                Výběr linky :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <select class="vyber" id="select_linka">          
                                </select>      
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_smer" class="label_main">     
                                Výběr směru :    
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <select class="vyber" id="select_smer">                    
                                </select>      
                            </div>      
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_trasa" class="label_main">
                                Výběr zastávky :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <select class="vyber" id="select_trasa">          
                                </select>      
                            </div>
                        </td>
                    </tr>                                                 
    <!--                                            <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_datum" class="label_main">
                                Datum JŘ :      
                            </label>
                        </td>

                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <div class="vyber" id="select_datum"> 
                                    <a class="a_select_datum" id="a_select_datum"></a>                
                                </div>      
                            </div>
                        </td>
                    </tr>-->        
                </table>
            </div>
            <?php
        }
        ?>  

        <?php
        if (($active_page == 6)) {
            ?>
            <div id="div_header_vyber" class="content_div" style="position: static;">   
                <table class="tablevyber" style="border-color: #ffcc33; border-collapse: collapse; width: 99%;">
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_linka" class="label_main">
                                Ze zastávky :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
<!--                                <select class="vyber" id="select_spojeni_OD">          
                                </select>      -->
                                <input type="text" id="naseptavacText" class="vyber" style="padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 12px;" autocomplete="off"
                                       onKeyUp="generujNaseptavac(event, 0, 'naseptavac');" onKeyDown="posunNaseptavac(event, 'naseptavac');"></input>
                                </br>
                                <div id="naseptavacDiv" style="visibility: hidden;">
                            </div>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);" rowspan="2">
                            <button title="Prohodit" style="background-image: url('css/Plzen/button-switch.png'); width: 30px; height: 25px; background-color: #c00;
                                    color: #fff;
                                    font-weight: bold;
                                    border: 1px solid #b00;
                                    border-radius: 5px;
                                    padding: 5px 10px;
                                    cursor: pointer; background-position: 0 0;
                                    background-repeat: no-repeat;" class="send" onclick="JR.zmenaSmeruNaseptavac();"></button>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_smer" class="label_main">     
                                Do zastávky :    
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <!--<select class="vyber" id="select_spojeni_DO">          
                                </select>      -->
                                <input type="text" id="naseptavac1Text" class="vyber" style="padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 12px;" autocomplete="off"
                                       onKeyUp="generujNaseptavac(event, 0, 'naseptavac1');" onKeyDown="posunNaseptavac(event, 'naseptavac1');"></input>
                                    </br>
                                    <div id="naseptavac1Div" style="visibility: hidden;">
                            </div>      
                        </td>                                                    
                        <td style="background-color: rgb(255, 255, 204);" rowspan="2"></td>                                                    
                    </tr>       
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_datum" class="label_main">
                                Datum JŘ :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <div class="vyber" id="select_datum"> 
                                    <a class="a_select_datum" id="a_select_datum"></a>                
                                </div>      
                            </div>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>                                                    
                    </tr>        
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_time" class="label_main">
                                Čas odjezdu :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <input class="vyber" id="select_time"></input>                            
                            </div>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>   
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>                      
                    <tr>    
                        <td style="background-color: #ffcc33;"></td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <input type="checkbox" id="select_prime" value="1" style="margin-left: 10px; border: none; font-size: 12px; padding: 0px; color: #555;  margin-top: 6px;">Jen přímé spojení</input>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>   
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>  
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label class="label_main">
                                Max. počet přestupů :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber" style="width: 50px;">
                                <input class="vyber" style="width: 40px;" id="select_prestupy" value="5"></input>                            
                            </div>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>   
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label class="label_main">
                                Min. čas přestupu (min) :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber" style="width: 50px;">
                                <input class="vyber" style="width: 40px;" id="select_time_prestup" value="0"></input>                            
                            </div>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>   
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label class="label_main">
                                MHD :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <input type="checkbox" id="ch_bus" checked="checked" value="1">Autobus</input>
                            <input type="checkbox" id="ch_trol" checked="checked" value="2">Trolejbus</input>
                            <input type="checkbox" id="ch_trm" checked="checked" value="3">Tramvaj</input>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);"></td>   
                        <td style="background-color: rgb(255, 255, 204);"></td>
                    </tr>
                </table>
            </div>
            <?php
        }
        ?>                                                                      

        <?php
        if (($active_page == 7)) {
            ?>
            <div id="div_header_vyber" class="content_div" style="position: static;">   
                <table class="tablevyber" style="border-color: #ffcc33; border-collapse: collapse; width: 99%;">
                    <tr>
                        <td style="background-color: #ffcc33;">
                            <label for="select_datum" class="label_main">
                                Datum JŘ :      
                            </label>
                        </td>
                        <td style="background-color: rgb(255, 255, 204);">
                            <div class="div_vyber">
                                <div class="vyber" id="select_datum"> 
                                    <a class="a_select_datum" id="a_select_datum"></a>                
                                </div>      
                            </div>
                        </td>
                    </tr>        
                </table>
            </div>
            <?php
        }
        ?>                                                                                                                                                    
        
        <?php
        if ($active_page == 1) {
            ?>
            <div id="content_1" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.komplexJR(vlocation, vpacket, 0);"></input>                    
                </div>
                <div class="div_separator">
                </div>      
            </div>          
            <?php
        }
        ?>  

        <?php
        if ($active_page == 2) {
            ?>                
            <div id="content_2" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.denJR(vlocation, vpacket);"></input>                                      
                </div>
                <div class="div_separator">
                </div>      
            </div>                
            <?php
        }
        ?>

        <?php
        if ($active_page == 3) {
            ?>                
            <div id="content_3" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.sdruzJR(vlocation, vpacket, 1);"></input>                    
                </div>
                <div class="div_separator">
                </div>      
            </div>                 
            <?php
        }
        ?>

        <?php
        if ($active_page == 4) {
            ?>                                
            <div id="content_4" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick=""></input>                    
                </div>
                <div class="div_separator">
                </div>      
            </div>             
            <?php
        }
        ?>

        <?php
        if ($active_page == 5) {
            ?>                                    
            <div id="div_header_vyber" class="content_div" style="position: static;"> 
                <table class="tablevyber" style="border-color: #ffcc33; border-collapse: collapse; width: 99%;">
                    <tbody>
    <!--                                                <tr style="visibility: hidden;">
                            <td style="background-color: #ffcc33;">
                                <label class="label_main" for="select_datum"> Datum </label>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);">
                                <div class="div_vyber">
                                    <div class="vyber" id="select_datum"> 
                                        <a class="a_select_datum" id="a_select_datum"></a>
                                    </div>      
                                </div>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);"></td>
                            <td style="background-color: rgb(255, 255, 204);"></td>
                        </tr>-->
                        <tr>
                            <td style="background-color: #ffcc33;">
                                <label class="label_main" for="search_zastavky"> Zastávka v okolí </label>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);">
                                <div class="div_vyber"><input type="text" class="vyber" id="search_zastavky"></div>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);">
                                <button title="Zastávka v okolí" class="send" src="css/Plzen/buttonJR.png" onclick="JR.searchMap('search_zastavky', 'select_search_result', 'select_odjezdy');">Zastávka v okolí</button>
                            </td>    
                            <td style="background-color: rgb(255, 255, 204);"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #ffcc33;">
                                <label class="label_main" for="select_odjezdy"> Ze zastávky </label>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);;">
                                <div class="div_vyber"><select class="vyber" id="select_odjezdy"></select></div>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);">
                                <button title="zobrazit v mapě" onclick="JR.searchMapShow('select_odjezdy');">Zobrazit v mapě</button>
                            </td>
                            <td style="background-color: rgb(255, 255, 204);">
                                <button title="Zruš výběr" onclick="JR.nullSearch('select_odjezdy'); document.getElementById('search_zastavky').value = ''"></span>Zruš výběr</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="content_5" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <button title="Zobrazit odjezdy" onclick="getDepartureBoard(vlocation);">Zobrazit odjezdy</button>
                    <button title="Zobrazit mapu" onclick="getDepartureMap(vlocation);">Zobrazit mapu</button>
                </div>
                <div class="div_separator">
                </div>      
            </div>
            <?php
        }
        ?>

        <?php
        if ($active_page == 6) {
            ?>                                                
            <div id="content_6" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="Vyhledat spojení" class="send" src="css/Plzen/button-search.png" onclick="JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM(), ((document.getElementById('select_prime').checked) ? 1: 0), document.getElementById('select_prestupy').value);"></input>
                </div>  
                <div class="div_separator">
                </div>      
            </div>                            
            <?php
        }
        ?>                                                

        <?php
        if ($active_page == 7) {
            ?>                                
            <div id="content_7" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.seznamJR(vlocation, vpacket);"></input>
                </div>  
                <div class="div_separator">
                </div>      
            </div>                                
            <?php
        }
        ?>  

        <?php
        if ($active_page == 8) {
            ?>                                                
<!--            <div id="content_8" class="content_div">   
                <div class="div_separator">
                </div>
                <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.seznamZastavkyJR(vlocation, vpacket);"></input>
                </div>  
                <div class="div_separator">
                </div>      
            </div>   -->
            <?php
        }
        ?>  

        <!--                            </div>-->

        <div id="map_canvas" style="min-height:500px; margin-left: 10px; margin-right: 10px">
            <div id="divRoute" style="margin-top: 10px; height: 0px;"> </div>
            <div id="divJR" style="margin-top: 10px;"> </div>
        </div>               


<!--        <div id="heading">
            <div id="headingInner">
                <span class="logo">
                    <a title="Plzeňské městské dopravní podniky">Jízdní řády</a>
                </span>
            </div>
        </div>-->

        <!--                            <div id="menu_bar_top">
                                        <div id="menu_bar_center">-->

        <!--                                    <ul id="menu_main">
                                                <li>
                                                    <a id="menu_content_5" name="content_5" href="?page=5" <?php echo ($_GET['page'] == 5) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>
                                                            Odjezdy
                                                        </span>
                                                    </a>
                                                </li>  
                                                <li>
                                                    <a id="menu_content_1" name="content_1" href="?page=1" <?php echo ($_GET['page'] == 1) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span> 
                                                            Komplexní JŘ                    
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a id="menu_content_2" name="content_2" href="?page=2" <?php echo ($_GET['page'] == 2) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>                     
                                                            Denní JŘ                                        
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a id="menu_content_3" name="content_3" href="?page=3" <?php echo ($_GET['page'] == 3) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>
                                                            Linkový JŘ
                                                        </span>
                                                    </a>
                                                </li>                                  
                                                <li>
                                                    <a id="menu_content_6" name="content_6" href="?page=6" <?php echo ($_GET['page'] == 6) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>
                                                            Spojení
                                                        </span>
                                                    </a>
                                                </li>                   
                                                <li>
                                                    <a id="menu_content_7" name="content_7" href="?page=7" <?php echo ($_GET['page'] == 7) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>
                                                            Linky
                                                        </span>
                                                    </a>
                                                </li>                   
                                                <li>
                                                    <a id="menu_content_8" name="content_8" href="?page=8" <?php echo ($_GET['page'] == 8) ? "class='active'" : "class='noactive'"; ?> >
                                                        <span>
                                                            Zastávky
                                                        </span>
                                                    </a>
                                                </li>                                                           
                                            </ul>-->
        <!--                                </div>
                                    </div>-->

        <div class="cleaner">

        </div>

        <!--                        </div>-->

        <!--          <div class="div_jr" id="divJR" style="top: 465px">-->
        <!--                    </div>   -->

        <!--            <div id="vysledek"></div>-->         

        <div id="sklikReklama_59515" style="text-align: center;"><iframe style="margin: auto;" width="468" height="282" src="//c.imedia.cz/context?url=http%3A%2F%2Fjizdnirady.pmdp.cz%2FDZJR_dynamic.aspx%3FlineName%3D11%26from%3D60000128%26to%3D60001348%26startDate%3D21.5.2019%26endDate%3D30.6.2019&amp;z=59515&amp;hash=7508754779017607" frameborder="0" scrolling="no" style="display: block;"></iframe></div>

        <div style="padding-top: 20px; padding-bottom: 20px; background-color: #ffcc33;" id="page-footer">
            <div id="footer">
                <div class="copyright">
                    <div style="text-align: center;" class="copyright-inner">
                        © 2019 Plzeňské městské dopravní podniky, a.s.      
                    </div>
                </div>
                <div style="text-align: center;" class="madeby">
                    aplikace           
                    <a href="http://www.fssoftware.cz/" target="_blank">JRw ver. 5.0. - SKELETON ® FS software s.r.o.</a>
                    | grafické zpracování                  
                    <a href="http://www.ceskysoftware.cz/">Český software s.r.o.</a>
                </div>
            </div>
        </div>
        <!--                </div>
                    </div>
                </div>-->

        <div id="tagJRSeznamZastavky"></div>

        <script type="application/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCqdToSAutmuqDTrd-rsDR0RuGbHlucPhY&sensor=true&libraries=places"></script>
        <script type="text/javascript" charset="UTF-8">
                    /*      var a = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa');
                     a.setMove(true);
                     window.onload = new function() { a.initialize(); }*/
                    var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
                    Kalend.initialize();
                    Kalend.setZIndex(100001);
                    var Time = new JRTime('select_time');
                    Time.initialize();
                    var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, 'naseptavac'/*'select_spojeni_OD'*/, 'naseptavac1'/*'select_spojeni_DO'*/, null, null, 'select_odjezdy');
/*                    var JR = new JRData(vlocation, vpacket, 'select_linka', null, null, Kalend, null, 'naseptavac', 'naseptavac1', null, null, null);*/
      registerNaseptavac('naseptavac');
      registerNaseptavac('naseptavac1');
                    JR.setJRDiv("divJR");
                    JR.setSeznamyDiv("divJR");
                    JR.setRouteDiv("divRoute");
                    JR.setMove(false);
                    JR.setVersion(51);
                    JR.setCodePage("WIN");
                    window.onload = new function () {
                        JR.initialize(<?php echo (($active_page == 1) || ($active_page == 2) || ($active_page == 3) || ($active_page == 7) || ($active_page == 8)) ? 'true' : 'false'; ?>, <?php echo (($active_page == 6)) ? 'true' : 'false'; ?>, <?php echo (($active_page == 8)) ? '3' : ''; ?>);
                    }
        </script>
    </body></html>
