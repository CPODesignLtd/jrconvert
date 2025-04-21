<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "//www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="//www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
    <link rel="stylesheet" type="text/css" href="css/JRBlava/menuBlava.css"/>
    <link rel="stylesheet" type="text/css" href="css/JRBlava/JRBlava.css"/>
    <link rel="stylesheet" type="text/css" href="css/JRBlava/kalendarBlava.css"/>
  </head>
  <body>
    <?php
    $admininstrator = false;
    $counttagmenu = 2;
    $showkurz = 0;
    if (isset ($_GET[kurz])) {
      if ($_GET[kurz] == 1) {
        $showkurz = 1;
      }
    }
    ?>
    <script type="text/javascript">
      var vlocation = 6;
      var vpacket = null;
    </script>
    <script type="text/javascript" charset="windows-1250" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
    <script type="text/javascript" src="//www.mhdspoje.cz/jrw50/js/kalendar.js"></script>


    <div id="secondary-nav">
      <ul>
        <li id="menu_content_1" <?php echo (($_GET[page] == 1) || (!isset ($_GET[page]))) ? 'class="active"': ''; ?>><a name="content_1" href="?page=1<?php echo ($showkurz == 1) ? '&kurz=1': ''; ?>">J�zdn� ��dy</a></li>
        <li id="menu_content_2" <?php echo ($_GET[page] == 2) ? 'class="active"': ''; ?>><a name="content_2" href="?page=2<?php echo ($showkurz == 1) ? '&kurz=1': ''; ?>">Linky</a></li>
        <li id="menu_content_3" <?php echo ($_GET[page] == 3) ? 'class="active"': ''; ?>><a name="content_3" href="?page=3<?php echo ($showkurz == 1) ? '&kurz=1': ''; ?>">Zast�vky</a></li>
        <li id="menu_content_4" <?php echo ($_GET[page] == 4) ? 'class="active"': ''; ?>><a name="content_4" href="?page=4<?php echo ($showkurz == 1) ? '&kurz=1': ''; ?>">Spojen�</a></li>
      </ul>
    </div>

    <?php
      if (($_GET[page] == 1) || (!isset ($_GET[page]))) {
    ?>
    <div id="content_1" class="content_div">
      <table>
        <tr>
          <td>
            <label for="select_linka" class="label">
              V�b�r linky :
            </label>
          </td>
          <td>
            <div class="div_vyber">
              <select class="vyber" id="select_linka">
              </select>
            </div>
          </td>
        <td>
            <label class="label">
              V�b�r zastavky :
            </label>
          </td>
          <td>
            <div class="div_vyber">
              <input type="text" id="naseptavacText" class="vyber" style="width:300px" autocomplete="off"
                     onKeyUp="generujNaseptavac(event, 0, 'naseptavac');" onKeyDown="posunNaseptavac(event, 'naseptavac');"></input><img onClick="generujNaseptavac(event, 1, 'naseptavac');" width="7px" height="7px" style="padding-left: 5px;" src="//www.mhdspoje.cz/jrw50/image/combodown.png"></img></br>
<div id="naseptavacDiv" style="visibility: hidden;"></div>
<button onclick="echovysledek('naseptavac')"></button>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <a class="label">
              V�b�r sm�ru :
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
              V�b�r zast�vky :
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
              Datum J� :
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
              <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket, <?php echo ($showkurz == 1) ? '1': '0'; ?>);" title="komplexn� j�zdn� ��d" style="width: 150px; height: 35px; float: left; margin-right: 20px;">J� - komplexn�</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
              <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket, <?php echo ($showkurz == 1) ? '1': '0'; ?>);" title="denn� j�zdn� ��d" style="width: 150px; height: 35px; float: left; margin-right: 20px;">J� - denn�</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="width: 100px;">-->
              <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket, <?php echo ($showkurz == 1) ? '1': '0'; ?>);" title="linkov� j�zdn� ��d" style="width: 150px; height: 35px; float: none;">J� - linkov�</button>
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
              Datum :
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
        <button id="button_seznam_linky" onclick="JR.seznamJR(vlocation, vpacket);" title="Seznam linek" style="width: 150px; height: 35px; float: none;">Zobrazit</button>
      </div>
      <div class="div_separator">
      </div>
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
              Datum :
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
        <button id="button_seznam_zastavky" onclick="JR.seznamZastavkyJR(vlocation, vpacket);" title="Seznam zat�vek" style="width: 150px; height: 35px; float: none;">Zobrazit</button>
      </div>
      <div class="div_separator">
      </div>
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
                            Ze zast�vky :
                          </label>
                        </td>
                        <td>
                          <div class="div_vyber">
                            <select class="vyber" id="select_spojeni_OD">
                            </select>
                            <div class="div_vyber">
              <input type="text" id="naseptavacText" class="vyber" style="width:300px" autocomplete="off"
                     onKeyUp="generujNaseptavac(event, 0, 'naseptavac');" onKeyDown="posunNaseptavac(event, 'naseptavac');"></input><img onClick="generujNaseptavac(event, 1, 'naseptavac');" width="7px" height="7px" style="padding-left: 5px;" src="//www.mhdspoje.cz/jrw50/image/combodown.png"></img></br>
<div id="naseptavacDiv" style="visibility: hidden;"></div>
<button onclick="echovysledek('naseptavac')"></button>
            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <label for="select_spojeni_DO" class="label">
                            Do zast�vky :
                          </label>
                        </td>
                        <td>
                          <div class="div_vyber">
                            <select class="vyber" id="select_spojeni_DO">
                            </select>
                            <div class="div_vyber">
              <input type="text" id="naseptavac1Text" class="vyber" style="width:300px" autocomplete="off"
                     onKeyUp="generujNaseptavac(event, 0, 'naseptavac1');" onKeyDown="posunNaseptavac(event, 'naseptavac1');"></input><img onClick="generujNaseptavac(event, 1, 'naseptavac1');" width="7px" height="7px" style="padding-left: 5px;" src="//www.mhdspoje.cz/jrw50/image/combodown.png"></img></br>
<div id="naseptavac1Div" style="visibility: hidden;"></div>
<button onclick="echovysledek('naseptavac1')"></button>
            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <label for="select_datum" class="label">
                            Datum J� :
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
                            �as odjezdu :
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
<!--      <input type="image" text="zobrazit J�" class="send" src="css/Plzen/buttonJR.png" onclick="JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM());"></input>-->
        <button title="vyhledat spojen�" onclick="JR.spojeniResult(vlocation, vpacket, Time.getHH(), Time.getMM());" style="width: 200px; height: 35px;">Vyhledat spojen�</button>
      </div>
      <div class="div_separator">
      </div>
    </div>
    <?php
      }
    ?>

    <?php
      if (($_GET[page] == 1) || ($_GET[page] == 2) || ($_GET[page] == 3) || ($_GET[page] == 4) || (!isset ($_GET[page]))) {
    ?>
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
      var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
      Kalend.initialize();
      Kalend.setZIndex(100001);
      var Time = new JRTime('select_time');
      Time.initialize();
      //'select_spojeni_OD' 'select_spojeni_DO'
      var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, 'naseptavac', 'naseptavac1');
      registerNaseptavac('naseptavac');
      registerNaseptavac('naseptavac1');
      JR.setMove(true);
      JR.setCodePage("W1250");
      window.onload = new function() { JR.initialize(true, false); }
    </script>
    <?php
      }
    ?>
  </body>
</html>
