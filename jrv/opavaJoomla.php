    <link rel="stylesheet" type="text/css" href="css/JROpava/menuOpava.css"/>
    <link rel="stylesheet" type="text/css" href="css/JROpava/JROpava.css"/>
    <link rel="stylesheet" type="text/css" href="css/JROpava/kalendarOpava.css"/>

    <?php
    $admininstrator = false;
    $counttagmenu = 2;
    ?>
    <script type="text/javascript">
      var vlocation = 11;
      var vpacket = null;
    </script>
    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
<!--    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/kalendar.js"></script>-->



    <div id="content_1" class="content_div">
      <table>
        <tr>
          <td>
            <label for="select_linka" class="label">
              Výbìr linky :
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
              Výbìr smìru :
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
              Výbìr zastávky :
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
              Datum JØ :
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
              <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexní jízdní øád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JØ - komplexní</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="float: left; margin-right: 20px;">-->
              <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní øád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JØ - denní</button>
              <!--        </div>-->
            </td>
            <td>
              <!--        <div class="div_vyber" style="width: 100px;">-->
              <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="linkový jízdní øád" style="width: 150px; height: 35px; float: none;">JØ - linkový</button>
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

    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
      var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
      Kalend.initialize();
      Kalend.setZIndex(100001);
      var Time = new JRTime('select_time');
      Time.initialize();
      var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, 'select_spojeni_OD', 'select_spojeni_DO');
      JR.setMove(true);
      JR.setCodePage("W1250");
      window.onload = new function() { JR.initialize(true, true); }
        </script>

