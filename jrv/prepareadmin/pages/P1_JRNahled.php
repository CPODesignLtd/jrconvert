<script type="text/javascript">
  var vlocation = <?php echo getLocation($_POST["username"]); ?>;
  var vpacket = null;
</script>
<link rel="stylesheet" type="text/css" href="../css/JRFS/kalendarFS.css"/>
<script type="text/javascript" charset="windows-1250" src="../js/JRclass50.js"></script>
<script type="text/javascript" src="../js/kalendar.js"></script>

<script type="text/javascript">
<?php
  $packetsArray = null;

  $connect = mysql_connect($con_server, $con_db, $con_pass);
  mysql_select_db($con_db);
  $sql = mysql_query("SELECT packet, jr_od, jr_do, id
        FROM packets WHERE location=" . getLocation($_POST["username"]) . " order by jr_od DESC, packet DESC");
  while ($row = mysql_fetch_row($sql)) {
    list($year, $month, $day) = explode('-', $row[1]);
    $row[1] = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
    $row[4] = $day;
    $row[5] = $month;
    $row[6] = $year;
    list($year, $month, $day) = explode('-', $row[2]);
    $row[2] = $day . ". " . $mesiceW1250[$month - 1] . " " . $year;
    $row[7] = $day;
    $row[8] = $month;
    $row[9] = $year;
    $packetsArray[] = $row;
  }
?>
var packetArray = new Array();
<?php
for ($i = 0; $i < count($packetsArray); $i++) {
?>
  packetArray[<?php echo $i; ?>] = new Array(<?php echo $packetsArray[$i][4]; ?>, <?php echo $packetsArray[$i][5]; ?>, <?php echo $packetsArray[$i][6]; ?>, <?php echo $packetsArray[$i][7]; ?>, <?php echo $packetsArray[$i][8]; ?>, <?php echo $packetsArray[$i][9]; ?>, <?php echo $packetsArray[$i][0]; ?>);
<?php
}
?>
</script>

<div id="leftpanel" style="width: 570px; float: left;">
  <table class="t_akce" style="margin-left: 15px; width: 560px;">
    <tr>
      <td class="last" style="width: 100%;">
        <label for="select_linka" class="label">
          Výběr linky :
        </label>
      </td>
      <td class="last">
        <div class="div_vyber">
          <select class="vyber" id="select_linka">
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td class="last" style="width: 100%;">
        <label for="selecy_smer" class="label">
          Výběr směru :
        </label>
      </td>
      <td class="last">
        <div class="div_vyber">
          <select class="vyber" id="select_smer">
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td class="last" style="width: 100%;">
        <label for="select_trasa" class="label">
          Výběr zastávky :
        </label>
      </td>
      <td class="last">
        <div class="div_vyber">
          <select class="vyber" id="select_trasa">
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td class="last" style="width: 100%;">
        <label for="select_datum" class="label">
          Datum JŘ :
        </label>
      </td>
      <td class="last">
        <div class="div_vyber">
          <div class="vyber" id="select_datum">
            <a class="a_select_datum" id="a_select_datum"></a>
          </div>
        </div>
      </td>
    </tr>
  </table>

  <table class="t_akce" style="margin-top: 20px; margin-left: 15px; width: 560px;">
    <tr>
      <td class="last" style="width: auto;">
        <div class="button" id="button_komplex_JR" style="height: 30px; width: 150px;" title="komplexní jízdní řád" onclick="
          JR.komplexJR(vlocation, vpacket, ((document.getElementById('sKurz').checked == true) ? 1: 0));">
        <span></span><img src="image/jr.png">
          JŘ - komplexní
        </div>
<!--        <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexní jízdní řád" style="width: 150px; height: 35px; margin: auto;">JŘ - komplexní</button>-->
      </td>
      <td class="last" style="width: auto;">
        <div class="button" id="button_den_JR" style="height: 30px; width: 150px;" title="denní jízdní řád" onclick="
          JR.denJR(vlocation, vpacket, ((document.getElementById('sKurz').checked == true) ? 1: 0));">
        <span></span><img src="image/jr.png">
          JŘ - denní
        </div>
<!--        <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní řád" style="width: 150px; height: 35px; margin: auto;">JŘ - denní</button>-->
      </td>
      <td class="last" style="width: auto;">
        <div class="button" id="button_sdruz_JR" style="height: 30px; width: 150px;" title="linkový jízdní řád" onclick="
          if (document.getElementById('sICSpoje').checked == true) JR.setInterniCSpoje(1); else JR.setInterniCSpoje(0);
          JR.sdruzJR(vlocation, vpacket, ((document.getElementById('sKurz').checked == true) ? 1: 0));">
        <span></span><img src="image/jr.png">
          JŘ - linkový
        </div>
<!--        <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="linkový jízdní řád" style="width: 150px; height: 35px; margin: auto;">JŘ - linkový</button>-->
      </td>
    </tr>
    <tr>
      <td class="last" style="width: auto;">
        <div class="button" id="button_voz_JR" style="height: 30px; width: 150px;" title="vozový jízdní řád" onclick="
          if (document.getElementById('sICSpoje').checked == true) JR.setInterniCSpoje(1); else JR.setInterniCSpoje(0);
          JR.seznamKurzy(vlocation, vpacket);">
        <span></span><img src="image/jr.png">
          JŘ - vozový
        </div>
      </td>
      <td class="last" style="width: auto;">
        <div class="button" id="button_linky" style="height: 30px; width: 150px;" title="seznam linek" onclick="
          if (document.getElementById('sICSpoje').checked == true) JR.setInterniCSpoje(1); else JR.setInterniCSpoje(0);
          JR.seznamJR(vlocation, vpacket);">
        <span></span><img src="image/jr.png">
          Linky
        </div>
      </td>
      <td class="last" style="width: auto;">
        <div class="button" id="button_zastavky" style="height: 30px; width: 150px;" title="seznam zastávek" onclick="
          if (document.getElementById('sICSpoje').checked == true) JR.setInterniCSpoje(1); else JR.setInterniCSpoje(0);
          JR.seznamZastavkyJR(vlocation, vpacket);">
        <span></span><img src="image/jr.png">
          Zastávky
        </div>
      </td>
    </tr>
  </table>
</div>

<div id="rightpanel" style="margin:0 30px 0 600px;">
  <div class="separdivglobal">parametry JŘ</div>

  <table id="table_packet" class="t_akce" style="width: 100%;">
    <tr>
      <td class="last" style="font-size: 15px; font-weight: normal; width: 100%;">
        <input type="checkbox" id = "sKurz"/>
        <label for="sKurz" class="label">
          &nbsp;&nbsp;Zobrazovat čísla kurzů
        </label>
      </td>
    </tr>
    <tr>
      <td class="last" style="font-size: 15px; font-weight: normal; width: 100%;">
        <input type="checkbox" id = "sICSpoje"/>
        <label for="sICSpoje" class="label">
          &nbsp;&nbsp;Zobrazovat interní čísla spojů (linkový JŘ)
        </label>
      </td>
    </tr>
  </table>

  <br/>
  <div class="separdivglobal">výběr balíčku dat</div>

  <table id="table_packet" class="t_akce" style="width: 100%;">
    <tr>
      <td class="last" style="font-size: 15px; font-weight: normal; width: 100%;">
        <div class="div_vyber" style="background-color: transparent;">
          <select id="select_packet" class="last" style="border: none;">
          <?php
            for($i = 0; $i < count($packetsArray); $i++) {
          ?>
              <option value="<?php echo $i; ?>"><?php echo $packetsArray[$i][0] . '  =  platnost : ' . $packetsArray[$i][1] . ' - ' . $packetsArray[$i][2]; ?></option>
          <?php
            }
          ?>
          </select>
        </div>
      </td>
      <td class="first">
        <a id="go_packet" style="color: #ffffff;" onClick="gopacket('select_packet', Kalend, JR);" title="Přejít k balíčku"><img src="image/sipkaright.png"></a>
      </td>
    </tr>
  </table>

  <br/>
  <div class="separdivglobal">informace o balíčku dat</div>
  <div id="infoPacket"></div>
</div>

<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
  function paintInfoPacket(data) {
    document.getElementById("infoPacket").innerHTML = data;
  }

  function changepacket(JR) {
    actpacket = JR.getAktualPacket();
    if (JR.kalendar.d < 10) {
      d = '0' + JR.kalendar.d;
    } else {
      d = JR.kalendar.d;
    }
    if (JR.kalendar.m < 10) {
      m = '0' + JR.kalendar.m;
    } else {
      m = JR.kalendar.m;
    }
    scriptObj = document.getElementById("myscrInfoPacket");
    if (scriptObj != null) {
      document.body.removeChild(scriptObj);
    }
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "UTF");
    fullUrl = "../prepareadmin/loadPacketInfo.php?location=" + vlocation + "&packet=" + JR.getAktualPacket() + "&d=" + (JR.kalendar.y + "-" + m + "-" + d) + "&callBack=paintInfoPacket";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrInfoPacket");
    document.body.appendChild(scriptObj);

    var id_select = -1;
    for(i = 0; i < packetArray.length; i++) {
      if (packetArray[i][6] == actpacket) {
        d_od = packetArray[i][2] * 10000 + packetArray[i][1] * 100 + packetArray[i][0];
        d_do = packetArray[i][5] * 10000 + packetArray[i][4] * 100 + packetArray[i][3];
        d_d =  JR.kalendar.y * 10000 + JR.kalendar.m * 100 + JR.kalendar.d;
        if ((d_od <= d_d) && (d_do >= d_d)) {
          id_select = i;
        }
      }
    }
    if (id_select > -1) {
      obj = document.getElementById('select_packet');
      obj.selectedIndex = id_select;
    }
  }

  function gopacket(selector, Kalend, JR) {
    obj = document.getElementById(selector);
    if (obj != null) {
      Kalend.setDatum(packetArray[obj.selectedIndex][0], packetArray[obj.selectedIndex][1], packetArray[obj.selectedIndex][2]);
    }
  }

  var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
  Kalend.initialize();
  Kalend.setCodepage("UTF");
  Kalend.setZIndex(100001);
  var Time = new JRTime('select_time');
  Time.initialize();
  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, null, null);
  Kalend.setOnChange(changepacket);
  JR.setVersion(51)
  JR.setShowPrivateTLine(true);
  JR.setPacketmode(1);
  JR.setMove(true);
  JR.setCodePage("UTF");
  window.onload = new function() { JR.initialize(true, false);}
</script>