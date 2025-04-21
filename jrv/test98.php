<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
    <link rel="stylesheet" type="text/css" href="css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="css/JROpava.css"/>
    <link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
  </head>
  <body>
    <?php
    $admininstrator = false;
    $counttagmenu = 2;
    ?>
    <script type="text/javascript">
      var vlocation = 6;
      var vpacket = null;
    </script>
    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
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
    <div id="content_1" class="content_div">
      <table>
        <tr>
          <td>
            <label for="select_linka" class="label">
              Výběr linky :
            </label>
          </td>
          <td>
            <div class="div_vyber">
              <select class="vyber" id="select_linka">
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
              <select class="vyber" id="select_smer">
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
<!--          <td onMouseDown="kalendin();" onMouseOut="kalendout();">-->
          <td>
            <!--            <div class="div_vyber" onclick="kalendarshow();" active=false>-->
            <div class="div_vyber">
              <div class="vyber" id="select_datum">
                <a class="a_select_datum" id="a_select_datum"></a>
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
              <button id="button_komplex_JR" onclick="JR.searchMap('bosakova');" title="komplexní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - komplexní</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
              <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - denní</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="width: 100px;">-->
              <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="sdružený jízdní řád" style="width: 150px; height: 35px; float: none;">JŘ - sdružený</button>
              <!--        </div>-->
            </td>
          </tr>
        </table>

        <table>
          <tr>
            <td>
              <label for="select_odjezdy" class="label">
                Ze zastávky :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_odjezdy">
                </select>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>
              <label for="select_odjezdy" class="label">
                Vyhledat zastavku :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <input type="text" class="vyber" id="search_zastavky">
                </select>
              </div>
            </td>
            <td><button title="vyhledat"  onclick="JR.searchMap('');" style="width: 150px; height: 35px; float: none;">vyhledat</button></td>
          </tr>
          <tr>
            <td>
              <label for="select_search_result" class="label">
                Ze zastávky :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_search_result">
                </select>
              </div>
            </td>
            <td></td>
          </tr>
        </table>

      </div>
      <div class="div_separator">
      </div>
      <div class="div_jr" id="div_JR">
      </div>
    </div>
    <div id="content_2" class="content_div" style="visibility: hidden;">
      contect druheho menu
    </div>
    <div id="content_3" class="content_div" style="visibility: hidden;">
      contect tretiho menu
    </div>
    <div id="content_4" class="content_div" style="visibility: hidden;">
      contect ctvrteho menu
    </div>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>
    <script type="text/javascript">
/*      var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
      Kalend.initialize();
      Kalend.setZIndex(1001);
      var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, 'div_JR');
      JR.setMove(true);

      window.onload = new function() {
        JR.initialize();
      }*/
  var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
  Kalend.initialize();
  Kalend.setZIndex(100001);
  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, null, null, null, null, 'select_odjezdy', 'search_zastavky', 'select_search_result');
  JR.setMove(true);
  JR.setCodePage("UTF");
  JR.setVersion(51);
  window.onload = new function() { JR.initialize(true, true); }
    </script>
  </body>
</html>
