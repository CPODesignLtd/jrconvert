<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>   
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>  
    <link rel="stylesheet" type="text/css" href="css/menu.css"/>   
    <link rel="stylesheet" type="text/css" href="css/JRDefault.css"/>
    <link rel="stylesheet" type="text/css" href="css/kalendar.css"/>
  </head>
  <body>  
    <script type="text/javascript">
      var vlocation = 2;
      var vpacket = null;
    </script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script> 

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
              <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - komplexní</button>
            </td>
            <td>
              <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní řád" style="width: 150px; height: 35px; float: left; margin-right: 20px;">JŘ - denní</button>
            </td>
            <td>
              <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="sdružený jízdní řád" style="width: 150px; height: 35px; float: none;">JŘ - sdružený</button>
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
      JR.initialize(); 
    </script>
  </body>
</html>
