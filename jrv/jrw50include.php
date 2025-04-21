<script type="text/javascript">var cssNode = document.createElement('link');  cssNode.setAttribute('rel', 'stylesheet');  cssNode.setAttribute('type', 'text/css');  cssNode.setAttribute('href', 'http://www.mhdspoje.cz/jrw50/css/menuOpava.css');  document.getElementsByTagName('head')[0].appendChild(cssNode);var cssNode1 = document.createElement('link');  cssNode1.setAttribute('rel', 'stylesheet');  cssNode1.setAttribute('type', 'text/css');  cssNode1.setAttribute('href', 'http://www.mhdspoje.cz/jrw50/css/JROpava.css');  document.getElementsByTagName('head')[0].appendChild(cssNode1);    var cssNode2 = document.createElement('link');  cssNode2.setAttribute('rel', 'stylesheet');  cssNode2.setAttribute('type', 'text/css');  cssNode2.setAttribute('href', 'http://www.mhdspoje.cz/jrw50/css/kalendarOpava.css');  document.getElementsByTagName('head')[0].appendChild(cssNode2);var vlocation = 11;  var vpacket = null;</script>
<script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
<div style="display: table; color: #000000;">
  <div id="content_1Opava" style="display: table; background-color: transparent; margin-top: 21px;">
    <label class="label" for="select_datum"> Datum :
    </label>
    <div id="select_datum" class="div_vyber_combo" style="background-color: rgba(0,0,0,0); border: 0px;">
      <a id="a_select_datum" class="a_select_datum" style="font-size: 16px; font-weight: bold; color: #000000; font-family: 'Ubuntu',sans-serif; background-color: rgba(0,0,0,0);"></a>
    </div>
    <div class="clear">
    </div>
    <label class="label" for="select_linka"> Linka :
    </label>
    <div class="div_vyber_combo" style="background-color: rgba(0,0,0,0); border: 0px; height: 45px;">
      <select id="select_linka" class="vyber_combo" style="font-size: 16px; font-weight: bold; color: #000000; font-family: 'Ubuntu',sans-serif; width: 111%; background-color: rgba(0,0,0,0);">
      </select>
    </div>
    <div class="clear">
    </div>
    <label class="label" for="select_smer"> Směr jízdy :
    </label>
    <div class="div_vyber_combo" style="background-color: rgba(0,0,0,0); border: 0px; height: 45px;">
      <select id="select_smer" class="vyber_combo" style="font-size: 16px; font-weight: bold; color: #000000; font-family: 'Ubuntu',sans-serif; width: 111%; background-color: rgba(0,0,0,0);">
      </select>
    </div>
    <div class="clear">
    </div>
    <label class="label" for="select_trasa"> Zastávka :
    </label>
    <div class="div_vyber_combo" style="background-color: rgba(0,0,0,0); border: 0px; height: 45px;">
      <select id="select_trasa" class="vyber_combo" style="font-size: 16px; font-weight: bold; color: #000000; font-family: 'Ubuntu',sans-serif; width: 111%; background-color: rgba(0,0,0,0);">
      </select>
    </div>
    <div class="clear">
    </div>
    <table style="width: 100%; margin-top: 20px;">
      <tr><td>
      <center>
        <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" title="komplexí jízdní řád" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.komplexJR(vlocation, vpacket);">komplexní</a>
      </center></td><td>
      <center>
        <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" title="denní­ jízdní řád" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.denJR(vlocation, vpacket);">denní</a>
      </center></td><td>
      <center>
        <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" title="sdružený jízdní řád" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.sdruzJR(vlocation, vpacket);">sdružený</a>
      </center></td>
      </tr>
    </table>
    <!--<button id="button_komplex_JR" style="width: 120px; height: 30px; float: left; margin-right: 15px; margin-left: 5px; margin-top: 20px;" title="komplexí jízdní řád" onclick="JR.komplexJR(vlocation, vpacket);">J�? - komplexní</button><button id="button_den_JR" style="width: 120px; height: 30px; float: left; margin-right: 15px; margin-top: 20px;" title="denní­ jízdní řád" onclick="JR.denJR(vlocation, vpacket);">J�?�? - denní</button><button id="button_sdruz_JR" style="width: 120px; height: 30px; float: none; margin-top: 20px;" title="sdružený jízdní řád" onclick="JR.sdruzJR(vlocation, vpacket);">J�?�? - sdružený˝</button>-->
  </div>
</div>
<div style="background-color: #062455; padding-bottom: 20px; width: 480px;">
  <!--<div style="padding-top: 5px; border-top: 1px solid #126499; width=100%">--><h4 style="font-size: 16px; font-weight: bold; color: #ffffff; font-family: 'Ubuntu',sans-serif; padding-left: 16px; padding-bottom: 0px; padding-left: 29px; padding-top: 18px;">Seznam :</h4>
  <table style="width: 100%; margin-top: 20px;">
    <tr><td>
    <center>
      <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.seznamJR(vlocation, vpacket);">linek</a>
    </center></td><td>
    <center>
      <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.seznamZastavkyJR(vlocation, vpacket);">zastávek</a>
    </center></td><td>
    <center>
      <a class="moduleItemReadMore" style="color: #000000; text-decoration: none; cursor: pointer;" onMouseOver="this.style.color='white'" onMouseOut="this.style.color='#000000'" onclick="JR.getRoute(null, null, vlocation, vpacket);">route</a>
    </center></td>
    </tr>
  </table>
  <!--<button style="width: 120px; height: 30px; float: left; margin-right: 15px; margin-left: 5px; margin-top: 20px;" title="Seznam linek" onclick="JR.seznamJR(vlocation, vpacket);">Seznam linek</button>-->
  <!--</div>-->
</div>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);  Kalend.initialize();  Kalend.setZIndex(100001);  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null);  JR.setMove(true);  JR.setCodePage("UTF8");  window.onload = new function() { JR.initialize(); }</script>