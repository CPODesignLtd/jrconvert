<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRHradec/menuHradec.css"/>
<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRHradec/JRHradec.css"/>
<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRHradec/kalendarHradec.css"/>
<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRHradec/mainsbox.css"/>
<?php
$location = $_GET['l'];

?>

<script type="text/javascript">
  var vlocation = <?php echo $location; ?>;
  var vpacket = null;
</script>
<script type="text/javascript" charset="utf-8" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/kalendar.js"></script>

<?php
$menu = $_GET['menu'];
if ($menu == null) {
  $menu = 1;
}
if ($menu == 1) {

}
?>

<script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/global.js"></script>

<?php
$lang = $_GET['lang'];
if ($lang == null) {
  $lang = 1;
}
echo "<script type='text/javascript'> language = " . $lang . "</script>";
?>

<?php
$left = $_GET['left'];
if ($left == null) {
  $left = 0;
}
$top = $_GET['top'];
if ($top == null) {
  $top = 0;
}
$pos = $_GET['pos'];
if (($pos != 'absolute') && ($pos != 'relative')) {
  $pos = null;
}
if ($pos == null) {
  $pos = 'absolute';
}
$rleft = $_GET['rleft'];
if ($rleft == null) {
  $rleft = 200;
}
$rtop = $_GET['rtop'];
if ($rtop == null) {
  $rtop = 300;
}
$rpos = $_GET['rpos'];
if (($rpos != 'absolute') && ($rpos != 'relative')) {
  $rpos = null;
}
if ($rpos == null) {
  $rpos = 'absolute';
}
?>

<div id = "searchDIV" class= "search" style="visibility: hidden; display: block; height: auto; width: 300px;">
<div style="position:absolute; margin:0px; padding:0px; left:0px; top:20px; height:100%; width:100%;">
    <?php
    if ($menu == 1) {
      echo "
      <div id ='menusbix' class = 'menusbox'>
              <ul class = 'menuli'>
                <li class = 'menuli'>
                  <a class = 'textmenu' onclick = 'showtabs(1);'><img src='http://www.mhdspoje.cz/jrw50/image/JR1.png'>
                    <script>
                      if (language == 1) {
                        document.write('Jízdní řády');
                      }
                      if (language == 2) {
                        document.write('Cestovné poriadky');
                      }
                      if (language == 3) {
                        document.write('Time tables');
                      }
                    </script>
                  </a>
                </li>
                <li class = 'menuli'>
                  <a class = 'textmenu' onclick = 'showtabs(2);'><img src='http://www.mhdspoje.cz/jrw50/image/spojeni2.png'>
                    <script>
                      if (language == 1) {
                        document.write('Vyhledávání spojení');
                      }
                      if (language == 2) {
                        document.write('Vyhľadávanie spojenia');
                      }
                      if (language == 3) {
                        document.write('Connections');
                      }
                    </script>
                  </a>
                </li>
              </ul>
      </div>";
    } else {
      echo "<div style='margin-top: 20px;'></div>";
    }
    ?>

    <div id="tabsecond" style="visibility: hidden">
      <div style = "margin-left: 10px;">
        <table>
          <tr>
            <td>
              <label for="select_linka" class="label">
                Výběr linky :
              </label>
            </td>
          </tr>
          <tr>
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
          </tr>
          <tr>
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
          </tr>
          <tr>
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
          </tr>
          <tr>
            <td>
              <div class="div_vyber">
                <div class="vyber" id="select_datum">
                  <a class="a_select_datum" id="a_select_datum"></a>
                </div>
              </div>
            </td>
          </tr>
        </table>

        <div style="padding-top: 6px;">
            <table>
            <tr>
              <td>
                <button id="button_komplex_JR" onclick="JR.komplexJR(vlocation, vpacket);" title="komplexní jízdní řád" style="width: 135px; height: 25px; float: left; margin-right: 5px;">JŘ - komplexní</button>
              </td>
              <td>
                <button id="button_den_JR" onclick="JR.denJR(vlocation, vpacket);" title="denní jízdní řád" style="width: 135px; height: 25px; float: left; margin-right: 5px;">JŘ - denní</button>
              </td>
<!--              <td>
                <button id="button_sdruz_JR" onclick="JR.sdruzJR(vlocation, vpacket);" title="linkov� j�zdn� ��d" style="width: 110px; height: 22px; float: none;">J� - linkov�</button>
              </td>-->
            </tr>
          </table>
        </div>
        <div class="div_jr" id="divJR">
        </div>
      </div>
    </div>


    <div id="tabfirst" style="visibility: hidden; position: absolute; top: 0px;">
      <div style = "margin-left: 10px;">

        <table>
          <tr>
            <td>
              <label for="select_spojeni_OD" class="label">
                Ze zastávky :
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <div class="div_vyber">
                <select class="vyber" id="select_spojeni_OD">
                </select>
              </div>
            </td>
<!--            <td rowspan="3" style="width: auto;">
              <img src="http://www.mhdspoje.cz/jrw50/image/zmenasmeru.png" onclick="JR.zmenaSmeru();"/>
            </td>-->
          </tr>
          <tr>
            <td>
              <label for="select_spojeni_DO" class="label">
                Do zastávky :
              </label>
            </td>
          </tr>
          <tr>
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
              <label for="select_datum1" class="label">
                Datum :
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <div class="div_vyber">
                <div class="vyber" id="select_datum1">
                  <a class="a_select_datum" id="a_select_datum1"></a>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <label for="select_time" class="label">
                Čas odjezdu :
              </label>
            </td>
          </tr>
          <tr>
            <td>
              <div class="div_vyber">
                <input class="vyber" id="select_time"></input>
              </div>
            </td>
          </tr>
        </table>
        <div style="padding-top: 6px;">
          <button title="vyhledat spojení" onclick="JR.spojeniResultotherdatum(vlocation, vpacket, Time.getHH(), Time.getMM(), Kalend1);" style="width: 290px; height: 25px;">Vyhledat spojení</button>
        </div>


      </div>

    </div>
  </div>
</div>

<?php
$enablekurz = $_GET['kurz'];
if ($enablekurz == null) {
  $enablekurz = 0;
}
$location = $_GET['l'];
if ($location == null) {
  $location = 0;
} else {
  echo "<script type='text/javascript'>
        if (document.getElementById('divDopravce') != null) {
          document.getElementById('divDopravce').style.visibility = 'hidden';
          document.getElementById('divDopravce').style.position = 'absolute';
          document.getElementById('divDopravce').style.top = '0px';
          document.getElementById('divDopravce').style.height = '0px';
        }
        </script>";
}
$vlinka = $_GET['vl'];

echo "<script type='text/javascript'>
                showpage1();
                rleft = " . $rleft . ";
                rtop = " . $rtop . ";
                rposition = '" . $rpos . "';
                document.getElementById('searchDIV').style.position = '" . $pos . "';
                document.getElementById('searchDIV').style.top = '" . $top . "px';
                document.getElementById('searchDIV').style.left = '" . $left . "px';
                document.getElementById('searchDIV').style.visibility = 'visible';
                document.getElementById('searchDIV').style.zIndex = '100';
          </script>";

$tab = $_GET['tab'];
if ($tab != null) {
  echo "<script type='text/javascript'>
                showtabs(" . $tab . ");
                </script>";
}
?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
  var Kalend = new JRKalendar('select_datum', 'a_select_datum', null, true, 'cz', 'a_select_datum1', Kalend1);
  Kalend.initialize();
  Kalend.setZIndex(100001);
  var Kalend1 = new JRKalendar('select_datum1', 'a_select_datum1', null, true, 'cz', 'a_select_datum', Kalend);
  Kalend1.initialize();
  Kalend1.setZIndex(100001);
  var Time = new JRTime('select_time');
  Time.initialize();
  var JR = new JRData(vlocation, vpacket, 'select_linka', 'select_smer', 'select_trasa', Kalend, null, 'select_spojeni_OD', 'select_spojeni_DO', null, Kalend1, 'select_odjezdy');
  JR.setMove(true);
  JR.setCodePage("W1250");
  JR.setLang("cz");
  window.onload = new function() { JR.initialize(true, true); }
</script>