<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
    <link rel="stylesheet" type="text/css" href="css/JRKosice/menuKosice.css"/>
    <link rel="stylesheet" type="text/css" href="css/JRKosice/JRKosice.css"/>
    <link rel="stylesheet" type="text/css" href="css/JRKosice/kalendarKosice.css"/>
  </head>
  <body>
    <?php
    $admininstrator = false;
    $counttagmenu = 2;
    ?>
    <script type="text/javascript">
      var vlocation = 16;
      var vpacket = null;
    </script>
    <script type="text/javascript" charset="windows-1250" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
    <script type="text/javascript" src="//www.mhdspoje.cz/jrw50/js/kalendar.js"></script>


    <div id="secondary-nav">
      <ul>
        <li id="menu_content_1" <?php echo (($_GET[page] == 1) || (!isset($_GET[page]))) ? 'class="active"' : ''; ?>><a name="content_1" href="?page=1">Cestovný poriadok</a></li>
        <li id="menu_content_5" <?php echo ($_GET[page] == 5) ? 'class="active"' : ''; ?>><a name="content_5" href="?page=5">Odchody</a></li>
        <li id="menu_content_4" <?php echo ($_GET[page] == 4) ? 'class="active"' : ''; ?>><a name="content_4" href="?page=4">Spojenia</a></li>
        <li id="menu_content_2" <?php echo ($_GET[page] == 2) ? 'class="active"' : ''; ?>><a name="content_2" href="?page=2">Linky</a></li>
        <li id="menu_content_3" <?php echo ($_GET[page] == 3) ? 'class="active"' : ''; ?>><a name="content_3" href="?page=3">Zastávky</a></li>
      </ul>
    </div>

    <?php
    if (($_GET[page] == 1) || (!isset($_GET[page]))) {
      ?>
      <div id="content_1" class="content_div">
        <table>
          <tr>
            <td>
              <label for="select_linka" class="label">
                Výber linky :
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
                Výber smeru :
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
                Výber zastávky :
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
                Dátum CP :
              </label>
            </td>
            <td>
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
                <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexný cestovný poriadok" style="width: 150px; height: 35px; float: left; margin-right: 20px;">CP - komplexný</button>
                <!--        </div>-->
              </td>
              <td>
                <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
                <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denný cestovný poriadok" style="width: 150px; height: 35px; float: left; margin-right: 20px;">CP - denný</button>
                <!--        </div>-->
              </td>
              <td>
                <!--        <div class="div_vyber" style="width: 100px;">-->
                <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="linkový cestovný poriadok" style="width: 150px; height: 35px; float: none;">CP - linkový</button>
                <!--        </div>-->
              </td>
            </tr>
          </table>
        </div>
        <div class="div_separator">
        </div>
            <div id="divRoute" style="margin-bottom: 25px;"> </div>
<div id="divJR"> </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if ($_GET[page] == 2) {
      ?>
      <div id="content_2" class="content_div">
        <table>
          <tr>
            <td>
              <label for="select_datum" class="label">
                Dátum :
              </label>
            </td>
            <td>
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
          <button id="button_seznam_linky" onclick="JR.seznamJR(vlocation, vpacket);" title="Zoznamy liniek" style="width: 150px; height: 35px; float: none;">Zobraziť</button>
        </div>
        <div class="div_separator">
        </div>
          <div id="divRoute" style="margin-bottom: 25px;"> </div>
<div id="divJR"> </div>
      </div>
      <?php
    }
    ?>

    <?php
    if ($_GET[page] == 3) {
      ?>
      <div id="content_3" class="content_div">
        <table>
          <tr>
            <td>
              <label for="select_datum" class="label">
                Dátum :
              </label>
            </td>
            <td>
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
          <button id="button_seznam_zastavky" onclick="JR.seznamZastavkyJR(vlocation, vpacket);" title="Zoznam zastávok" style="width: 150px; height: 35px; float: none;">Zobraziť</button>
        </div>
        <div class="div_separator">
        </div>
          <div id="divRoute" style="margin-bottom: 25px;"> </div>
<div id="divJR"> </div>
      </div>
      <?php
    }
    ?>

    <?php
    if ($_GET[page] == 4) {
      ?>
      <div id="content_4" class="content_div">
        <table>
          <tr>
            <td>
              <label for="select_spojeni_OD" class="label">
                Zo zastávky :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_spojeni_OD">
                </select>
              </div>
            </td>
            <td rowspan="2">
              <img src="http://www.mhdspoje.cz/jrw50/image/zmenasmeru.png" onclick="JR.zmenaSmeru();"/>
            </td>
          </tr>
          <tr>
            <td>
              <label for="select_spojeni_DO" class="label">
                Do zastávky :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_spojeni_DO">
                </select>
              </div>
            </td>
            <td>

            </td>
          </tr>
          <tr>
            <td>
              <label for="select_datum" class="label">
                Dátum :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <div class="vyber" id="select_datum">
                  <a class="a_select_datum" id="a_select_datum"></a>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <label for="select_time" class="label">
                Čas odjazdu :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <input class="vyber" id="select_time"></input>
              </div>
            </td>
          </tr>
        </table>
        <div class="div_separator">
        </div>
        <div>
  <!--      <input type="image" text="zobrazit JŘ" class="send" src="css/Plzen/buttonJR.png" onclick="JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM());"></input>-->
          <button title="vyhladať spojenia" onclick="JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM());" style="width: 200px; height: 35px;">Vyhladať spojenia</button>
        </div>
        <div class="div_separator">
        </div>
          <div id="divRoute" style="margin-bottom: 25px;"> </div>
<div id="divJR"> </div>
      </div>
      <?php
    }
    ?>

    <?php
    if ($_GET[page] == 5) {
      ?>
      <div id="content_3" class="content_div">
        <table>
          <tr>
            <td>
              <label for="select_datum" class="label">
                Dátum :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <div class="vyber" id="select_datum">
                  <a class="a_select_datum" id="a_select_datum"></a>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <label for="select_odjezdy" class="label">
                Zo zastávky :
              </label>
            </td>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_odjezdy">
                </select>
              </div>
            </td>
          </tr>
        </table>
        <div class="div_separator">
        </div>
        <div>
          <button id="button_seznam_zastavky" onclick="JR.odjezdyResult(vlocation, vpacket);" title="odchody" style="width: 150px; height: 35px; float: none;">Vyhladať</button>
        </div>
        <div class="div_separator">
        </div>
          <div id="divRoute" style="margin-bottom: 25px;"> </div>
<div id="divJR"> </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (($_GET[page] == 1) || ($_GET[page] == 2) || ($_GET[page] == 3) || ($_GET[page] == 4) || ($_GET[page] == 5) || (!isset($_GET[page]))) {
      ?>
      <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>
      <script type="text/javascript">
        var Kalend = new JRKalendar('select_datum', 'a_select_datum', null, null, 'sk');
        Kalend.initialize();
        Kalend.setZIndex(100001);
        var Time = new JRTime('select_time');
        Time.initialize();
        var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, 'select_spojeni_OD', 'select_spojeni_DO', null, null, 'select_odjezdy');
        JR.setMove(false);
        JR.setCodePage("W1250");
        JR.setRouteDiv("divRoute");
        JR.setSeznamyDiv("divJR");
        JR.setJRDiv("divJR");
        JR.setLang("sk");
        JR.setVersion(51);
        window.onload = new function() { JR.initialize(true, true); }
      </script>
      <?php
    }
    ?>
  </body>
</html>
