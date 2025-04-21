<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>   
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>  
    <link rel="stylesheet" type="text/css" href="css/menu.css"/>   
    <link rel="stylesheet" type="text/css" href="css/JRPlzen.css"/>
    <link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
  </head>
  <body onclick="kalendarhide(event);">  
    <?php
    $admininstrator = false;
    $counttagmenu = 2;
    ?>
    <script type="text/javascript">
      var vlocation = 17;
      var vpacket = 0;
    </script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/JR.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/kalendar.js"></script>
    <script type="text/javascript">
      function show_content(content) {
        for(var i = 0; i < <?php echo $counttagmenu; ?> ; i++) {
          document.getElementById('content_' + (i + 1)).style.visibility = 'hidden';
          document.getElementById('menu_content_' + (i + 1)).className = '';
        }           
        if (document.getElementById(content) != null) {
          document.getElementById(content).style.visibility = 'visible'; 
          document.getElementById('menu_' + content).className = 'active';           
        }
      }
    </script>

        <div id="secondary-nav">
          <ul>
            <li id="menu_content_1" class="active"><a name="content_1" onclick="show_content(this.name);">Jízdní řády</a></li>
            <li id="menu_content_2"><a name="content_2" onclick="show_content(this.name);">Spojení</a></li>
            <?php
            if ($administrator) {
              ?>
              <li id="menu_content_3"><a name="content_3" onclick="show_content(this.name);">Data - nastavení</a></li>
              <li id="menu_content_4"><a name="content_4" onclick="show_content(this.name);">Definice</a></li>
              <?php
            }
            ?>
          </ul>
        </div>
        <div id="content_1" class="content_div" style="visibility: hidden;" onpropertychange="OnAttrModified(event, this, vlocation, vpacket);">   
          <table>
            <tr>
              <td>
                <label for="select_linka" class="label">
                  Výběr linky :      
                </label>
              </td>
              <td>
                <div class="div_vyber">
                  <select class="vyber" id="select_linka" onchange="onLinkaChange(vlocation, vpacket);">          
                  </select>      
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <a class="label">      
                  Výběr směru :    
                </a>
              </td>
              <td>
                <div class="div_vyber">
                  <select class="vyber" id="select_smer" onchange="onSmerChange(vlocation, vpacket);">                    
                  </select>      
                </div>      
              </td>
            </tr>
            <tr>
              <td>
                <label for="select_trasa" class="label">
                  Výběr zastávky :      
                </label>
              </td>
              <td>
                <div class="div_vyber">
                  <select class="vyber" id="select_trasa">          
                  </select>      
                </div>
              </td>
            </tr>       
            <tr>
              <td>
                <label for="select_datum" class="label">
                  Datum JŘ :      
                </label>
              </td>
              <td onMouseDown="kalendin();" onMouseOut="kalendout();">
                <div class="div_vyber" onclick="kalendarshow();" active=false>
                  <div class="vyber" id="select_datum_label"> 
                    <a class="a_select_datum" id="a_select_datum"></a>
                  </div>      
                </div>
                <div id="select_datum_vyber" style="margin-top: 5px; visibility: hidden; position: absolute; background: transparent;">
                  <div id="select_datum">          
                  </div>      
                </div>
              </td>         
            </tr>        
          </table>
          <div class="div_separator">
          </div>
          <div>
            <table>
              <tr>
                <td>
                  <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
                  <button id="button_komplex_JR" onclick="komplexJR(vlocation, vpacket);" title="komplexní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - komplexní</button>
                  <!--        </div>-->
                </td>
                <td>
                  <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
                  <button id="button_den_JR" onclick="denJR(vlocation, vpacket);" title="denní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - denní</button>
                  <!--        </div>-->
                </td>
                <td>
                  <!--        <div class="div_vyber" style="width: 100px;">-->
                  <button id="button_sdruz_JR" onclick="sdruzJR(vlocation, vpacket);" title="sdružený jízdní řád" style="width: 150px; height: 35px; float: none;">JŘ - sdružený</button>
                  <!--        </div>-->
                </td>
              </tr>
            </table>
          </div>
          <div class="div_separator">
          </div>      
          <div class="div_jr" id="divJR">        
          </div>      
        </div>    
        <div id="content_2" class="content_div" style="visibility: hidden;" onpropertychange="OnAttrModified(event, this, vlocation, vpacket);">       
          contect druheho menu
        </div>
        <div id="content_3" class="content_div" style="visibility: hidden;" onpropertychange="OnAttrModified(event, this, vlocation, vpacket);">       
          contect tretiho menu
        </div>      
        <div id="content_4" class="content_div" style="visibility: hidden;" onpropertychange="OnAttrModified(event, this, vlocation, vpacket);">       
          contect ctvrteho menu
        </div>   
    <script type="text/javascript">
      //      window.onclick.cancelBubble=true;
      window.onload = new function() { initialize(vlocation, vpacket); }
      //      document.body.onclick = kalendarhide(this);
    </script>
  </body>
</html>
