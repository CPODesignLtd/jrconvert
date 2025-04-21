
<script type="text/javascript">
  var cssNode = document.createElement('link');
  cssNode.setAttribute('rel', 'stylesheet');
  cssNode.setAttribute('type', 'text/css');
  cssNode.setAttribute('href', 'http://mhdspoje.cz/jrw50/css/menu.css');
  document.getElementsByTagName('head')[0].appendChild(cssNode);

  var cssNode1 = document.createElement('link');
  cssNode1.setAttribute('rel', 'stylesheet');
  cssNode1.setAttribute('type', 'text/css');
  cssNode1.setAttribute('href', 'http://mhdspoje.cz/jrw50/css/JRDefault.css');
  document.getElementsByTagName('head')[0].appendChild(cssNode1);
    
  var cssNode2 = document.createElement('link');
  cssNode2.setAttribute('rel', 'stylesheet');
  cssNode2.setAttribute('type', 'text/css');
  cssNode2.setAttribute('href', 'http://mhdspoje.cz/jrw50/css/kalendar.css');
  document.getElementsByTagName('head')[0].appendChild(cssNode2);    
  var vlocation = 11;
  var vpacket = 6;
</script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script> 

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
          <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexní jízdní øád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JØ - komplexní</button>
        </td>
        <td>
          <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní øád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JØ - denní</button>
        </td>
        <td>
          <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="sdružený jízdní øád" style="width: 150px; height: 35px; float: none;">JØ - sdružený</button>
        </td>
      </tr>
    </table>
  </div>
  <div class="div_separator">
  </div>      
  <div class="div_jr" id="divJR">        
  </div>      
</div>    

<script type="text/javascript">
  var Kalend = new JRKalendar('select_datum', 'a_select_datum', null);
  Kalend.initialize();
  Kalend.setZIndex(1001);
  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, 'divJR');
  JR.setMove(true);
  window.onload = new function() { JR.initialize(); }
</script>
