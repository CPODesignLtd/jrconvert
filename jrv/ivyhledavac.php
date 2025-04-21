<div style="margin-top: 10px; display: inline; margin-right: 40px; float: left; width: 268px; height: 135px; display: block; border: 1px solid #e10029; border-radius: 3px; box-shadow: 0 1px 2px #e10029; background-color: #ffffff; padding: 3px 15px 3px 15px; height: 150px;">
<script type="text/javascript" charset="UTF-8" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
<script type="text/javascript">
    var cssNode1 = document.createElement('link');
    cssNode1.setAttribute('rel', 'stylesheet');
    cssNode1.setAttribute('type', 'text/css');
    cssNode1.setAttribute('href', '//www.mhdspoje.cz/jrw50/css/JRDecinnew/Timetables.css');
    document.getElementsByTagName('head')[0].appendChild(cssNode1);

    var cssNode2 = document.createElement('link');
    cssNode2.setAttribute('rel', 'stylesheet');
    cssNode2.setAttribute('type', 'text/css');
    cssNode2.setAttribute('href', '//www.mhdspoje.cz/jrw50/css/JRDecinnew/kalendarDec.css');
    document.getElementsByTagName('head')[0].appendChild(cssNode2);
</script>

<style type="text/css">
    .vyber:hover {background:#FFFFCC;}
</style>

<!--<script type="text/javascript" charset="UTF-8" src="//www.mhdspoje.cz/jrw50/js/kalendar.js"></script>-->

<script type="text/javascript">
      var vlocation = 2;
      var vpacket = null;
</script>

<?php
echo '
<form name="mainfrm" type="post" action="//www.dpb.sk/pre-cestujucich/spojenia-1/" style="padding: 5px;">
<div id="content_1" class="content_div" style="position: inherit;">
<table>
<tbody>
<tr>
<td style="vertical-align: middle; width: auto;"><a class="popisek" style="vertical-align: middle; font-size: 12px;" for="select_spojeni_OD"> Ze zast�vky </a></td>
<!--<td style="vertical-align: middle; width: 100%;"><select name="pass_od" class="vyber" style="height: 20px; padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 10px;" id="select_spojeni_OD"></select></td>-->
<td style="vertical-align: middle; width: 100%;"><input type="text" id="naseptavacText" class="vyber" style="height: 20px; padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 10px;" autocomplete="off"
                     onKeyUp="generujNaseptavac(event, 0, \'naseptavac\');" onKeyDown="posunNaseptavac(event, \'naseptavac\');"></br>
<div id="naseptavacDiv" style="visibility: hidden;">
</td>
</tr>
<tr>
<td style="vertical-align: middle; width: auto;"><a class="popisek" style="vertical-align: middle; font-size: 12px;" for="select_spojeni_DO"> Do zast�vky </a></td>
<!--<td style="vertical-align: middle; width: 100%;"><select name="pass_do" class="vyber" style="height: 20px; padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 10px;" id="select_spojeni_DO"></select></td>-->
<td style="vertical-align: middle; width: 100%;"><input type="text" id="naseptavac1Text" class="vyber" style="height: 20px; padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 10px;" autocomplete="off"
                     onKeyUp="generujNaseptavac(event, 0, \'naseptavac1\');" onKeyDown="posunNaseptavac(event, \'naseptavac1\');"></br>
<div id="naseptavac1Div" style="visibility: hidden;">
</td>
</tr>
<!--<tr>
<td style="vertical-align: middle; width: auto;"><a class="popisek" style="vertical-align: middle; font-size: 12px;" for="select_datum"> Datum </a></td>
<td style="vertical-align: middle; width: 100%;"><div class="vyber" style="
    height: 20px;
    box-shadow: none;
    padding: 0 5px;
    border: 1px solid #ccc;
    color: #555;
    vertical-align: middle;
    font-size: 10px;
    display: flex;
    -webkit-box-shadow: none" id="select_datum"> <a class="a_select_datum" style="margin-bottom: 0px; color: #555; margin-left: 3px; margin-top: 3px;" id="a_select_datum"></a></div></td>
</tr>-->
<!--<tr>
<td style="vertical-align: middle; width: auto;"><a class="popisek" style="vertical-align: middle; font-size: 12px;" for="select_time"> �as odjezdu </a></td>
<td style="vertical-align: middle; width: 100%;"><div class="vyber" style="
    height: 20px;
    box-shadow: none;
    padding: 0 5px;
    border: 1px solid #ccc;
    color: #555;
    vertical-align: middle;
    font-size: 10px;
    dfisplay: flex;
    -webkit-box-shadow: none"><input class="a_select_datum" id="select_time" style="font-size: 10px; margin-bottom: 0px; border: none; background: transparent; padding: 0px; margin-left: 3px; margin-right: 3px; width: 100%; color: #555;  margin-top: 6px;"></input></div></td>
</tr>-->

<tr>
<td colspan="2" style="text-align: center;"><button title="Vyhledat spojen�" onclick="document.mainfrm[\'pass_od\'].value = echovysledek(\'naseptavac\'); document.mainfrm[\'pass_do\'].value = echovysledek(\'naseptavac1\');  " class="formButton" style="width: 112px; border-color: #e10029; color: #000000; background-position: center center; background: url(bback.png) no-repeat; -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover; padding: 5px 3px 5px 3px; font-size: 12px;"><span class=""></span>Vyhldat spojeni</button></td>
</tr>

<tr>
<td style="vertical-align: middle; text-align: left; width: auto;"><a class="popisek" style="vertical-align: middle; font-size: 12px;" for="select_linka"> Linka </a></td>
<td style="vertical-align: middle; text-align: left; width: 100%;"><select name="linka" class="vyber" style="height: 20px; padding-top: 1px; padding-bottom: 1px; margin-bottom: 0px; width: 100%; font-size: 10px;" id="select_linka"></select></td>
</tr>

<tr>
<td colspan="2" style="text-align: center;"><button title="J�zdn� ��d" onclick="document.mainfrm[\'page\'].value = 6; document.mainfrm[\'action\'].value = 8; document.mainfrm.action = \'//http://187663.w63.wedos.ws/mmdecin/index.php/cestovani-mhd?page-6/\';" class="formButton" style="width: 112px; background-color: #e10029; padding: 5px 3px 5px 3px; font-size: 12px;"><span class=""></span>Denn� CP</button>
<br><br>
</td>
</tr>

</tbody>
</table>

<input id="action" name="action" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
<input id="page" name="page" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
<input id="pass_od" name="pass_od" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
<input id="pass_do" name="pass_do" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
</div>
</form>';
?>

<script type="application/javascript" charset="UTF-8">
      var Kalend = new JRKalendar();
      Kalend.initialize();
      var JR = new JRData(vlocation, vpacket, 'select_linka', null, null, Kalend, null, 'naseptavac', 'naseptavac1', null, null, null);
      registerNaseptavac('naseptavac');
      registerNaseptavac('naseptavac1');
      JR.setJRDiv("divJR");
      JR.setSeznamyDiv("divJR");
      JR.setRouteDiv("divRoute");
      JR.setLang("cz");
      JR.setVersion(51);
      JR.setMove(false);
      JR.setCodePage("UTF");
      window.onload = new function() { JR.initialize(true, true); }
</script>

</div>