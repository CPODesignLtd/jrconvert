<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>   
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>  
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/JRDefault/menuDefault.css"/>   
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/JRDefault/JRDefault.css"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/JRDefault/kalendarDefault.css"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/JRDefault/styles.css"/>
    <TITLE></TITLE>
  </head>
<!--  <body onclick="kalendarhide(event);">  -->
<body>
    <?php
    $admininstrator = false;
    $counttagmenu = 4;
    if (isset($_GET['move'])) {
      $move = TRUE;
    } else {
      $move = FALSE;
    }
    ?>
    <script type="text/javascript">
      var vlocation = 1;
      var vpacket = null;
    </script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/kalendar.js"></script>
    <script type="text/javascript">
      function show_content(content) {
        for(var i = 0; i < <?php echo $counttagmenu; ?> ; i++) {
          document.getElementById('content_' + (i + 1)).style.visibility = 'hidden';
          document.getElementById('menu_content_' + (i + 1)).className = 'noactive';
        }           
        if (document.getElementById(content) != null) {
          document.getElementById(content).style.visibility = 'visible'; 
          document.getElementById('menu_' + content).className = 'active';           
        }
      }
    </script>
    <div id="page-bg1">
      <div id="page-bg2">
        <div id="page-bg3">
          <div id="page-bg4">
            <div id="page_container">


              <div id="content_main">

                <div id="pp"></div>
                
                <div id="div_header_vyber" class="content_div" style="position: static;">   
                  <table class="tablevyber" style="border-color: #ffcc33; border-collapse: collapse; width: 99%;">
                    <tr>
                      <td style="background-color: #ffcc33;">
                        <label for="select_linka" class="label">
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
                        <label for="select_smer" class="label">     
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
                        <label for="select_trasa" class="label">
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
                    <tr>
                      <td style="background-color: #ffcc33;">
                        <label for="select_datum" class="label">
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
<!--                      <td style="background-color: rgb(255, 255, 204);" onMouseDown="kalendin();" onMouseOut="/*kalendout();*/">
                        <div class="div_vyber" onclick="/*kalendarshow();*/" active=false>
                          <div class="vyber" id="select_datum_label"> 
                            <a class="a_select_datum" id="a_select_datum"></a>
                          </div>      
                        </div>
                        <div id="select_datum_vyber" style="margin-top: 5px; visibility: hidden; position: absolute; background: transparent;">
                          <div id="select_datum">          
                          </div>      
                        </div>
                      </td>-->         
                    </tr>        
                  </table>
                </div>

                <div id="content_1" class="content_div" style="visibility: visible;">   
                  <div class="div_separator">
                  </div>
                  <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/JRDefault/buttonJR.png" onclick="JR.komplexJR(vlocation, vpacket);"></input>                    
                  </div>
                  <div class="div_separator">
                  </div>      
                </div>          

                <div id="content_2" class="content_div" style="visibility: hidden;">   
                  <div class="div_separator">
                  </div>
                  <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/JRDefault/buttonJR.png" onclick="JR.denJR(vlocation, vpacket);"></input>                                      
                  </div>
                  <div class="div_separator">
                  </div>      
                </div>                

                <div id="content_3" class="content_div" style="visibility: hidden;">   
                  <div class="div_separator">
                  </div>
                  <div>
                    <input type="image" text="zobrazit JŘ" class="send" src="css/JRDefault/buttonJR.png" onclick="JR.sdruzJR(vlocation, vpacket);"></input>                    
                  </div>
                  <div class="div_separator">
                  </div>      
                </div>                                 

                <div id="content_4" class="content_div" style="visibility: hidden;">   
                  <div class="div_separator">
                  </div>
                  <div class="div_separator">
                  </div>      
                </div>                                 
                
              </div>

              <div id="map_canvas" style="height:500px; margin-left: 10px; margin-right: 10px"></div>               


              <div id="heading">
                <div id="headingInner">
                  <span class="logo">
                    <a>Jízdní řády</a>
                  </span>
                </div>
              </div>

              <div id="menu_bar_top">
                <div id="menu_bar_center">
                  <ul id="menu_main">
                    <li>
                      <a id="menu_content_1" name="content_1" onclick="show_content(this.name);" class="active">
                        <span> 
                          Komplexní JŘ                    
                        </span>
                      </a>
                    </li>
                    <li>
                      <a id="menu_content_2" name="content_2" onclick="show_content(this.name);" class="noactive">
                        <span>                     
                          Denní JŘ                                        
                        </span>
                      </a>
                    </li>
                    <li>
                      <a id="menu_content_3" name="content_3" onclick="show_content(this.name);" class="noactive">
                        <span>
                          Sdružené JŘ
                        </span>
                      </a>
                    </li>
                    <li>
                      <a id="menu_content_4" name="content_4" onclick="JR.seznamJR(vlocation, vpacket);" class="noactive">
                        <span>
                          Přehled linek
                        </span>
                      </a>
                    </li>                   
                  </ul>
                </div>
              </div>

              <div class="cleaner"></div>

            </div>

  <!--          <div class="div_jr" id="divJR" style="top: 465px">-->
            </div>   

<!--            <div id="vysledek"></div>-->

            <div id="page-footer">
              <div id="footer">
                <div class="copyright">
                  <div class="copyright-inner">
                    © 2012 FS software s.r.o.      
                  </div>
                </div>
                <div class="madeby">
                  aplikace           
                  <a href="http://www.fssoftware.cz/" target="_blank">JRw ver. 5.0. - SKELETON ® FS software s.r.o.</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
/*      var a = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa');
      a.setMove(true);
      window.onload = new function() { a.initialize(); }*/
  var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
  Kalend.initialize();
  Kalend.setZIndex(888);
  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null);
  JR.setMove(true);
  JR.setCodePage("W1250");
  window.onload = new function() { JR.initialize(); }    
    </script>
<!--    <script language="javascript" type="text/javascript">
  function OpenWindow(href) {
    var sirka = 885;
    var vyska = (screen.height * 7) / 10;
    var xLeft = (screen.width - sirka) / 2;
    var yTop = (screen.height - vyska) / 2;

    //var sUrl = "PrintPage.aspx"; //New Window Name. 
    var sFeatures = "height=" + vyska + ",width=" + sirka + ",left=" + xLeft + ",top=" + yTop + ",status=no,toolbar=no,menubar=no, location=no, scrollbars=yes"; //This will give all these features to your 
    //new opened window.             
    win = window.open(href, "Tisk", sFeatures);
    win.focus(); // This will give focus to your newly opened window. 
  }

  function start() {
    //msg = window.open('../Help/Spojeni.aspx', 'nove_okno', 'toolbar=no, menubar=no, location=yes, directories=no, scrollbars=yes, resizable=no, status=no, width=1050, height=800, top=200, left=100');
    msg = window.open('../Help/Spojeni.aspx', 'nove_okno', 'toolbar=no, menubar=no, location=yes, directories=no, scrollbars=yes, resizable=no, status=no, width=1050, height=800, top=200, left=100');
  }
</script>


<script type="text/javascript">
  var pageTracker = _ga._getTracker('UA-16779301-1', '.jizdnirady.pmdp.cz');
  pageTracker._trackPageview();
  </script>-->
  </body></html>
