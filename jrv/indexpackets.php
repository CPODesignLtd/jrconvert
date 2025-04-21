<meta http-equiv="Content-Type" content="text/javascript; charset=windows-1250"></meta>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/tableJR.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/tableSpojeni.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/Calendar.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/Combo.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw20/ccs/Base.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw20/ccs/BreadCrumb.css"/>
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw20/ccs/main.css"/>
<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw50/css/JR.css'/>
<?php
$location = $_GET['l'];

if ($location == null) {
  echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/ddAll.css'/>";
  echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/ButtonAll.css'/>";
  echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/backall.css'/>";
} else {
  echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/dd.css'/>";
  echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/Button.css'/>";
  if (($location == 1) || ($location == 11)) {
    echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/backTP.css'/>";
  } else {
    echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/backall.css'/>";
  }
}
?>

<!--    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw20/ccs/dd.css"></link>-->
<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw20/ccs/jquery.timeentry.css"/>

<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Structures.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/global.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/JR.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Spojeni.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Combo.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Calendar.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Button.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/JR.js"></script>    

<!--    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Structures.js"></script>
<script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/global.js"></script>-->


<!--    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
<!--    <script type="text/javascript" src="js/jquery.min.js"> </script>-->
<!--    <script type="text/javascript" src="js/jquery.easing.1.3.js" </script>-->
<!--    <script type="text/javascript" src="js/jquery.jBreadCrumb.1.1.js" </script>-->
<!--    <script type="text/javascript" src="js/jquery.dd.js"></script>-->
<!--    <script type="text/javascript">
  jQuery(document).ready(function()
  {
    jQuery("#breadCrumb1").jBreadCrumb();
  })
</script>-->

<!--    <script language="javascript" src="msdropdown/js/jquery.dd.js" type="text/javascript"></script>-->
<!--        <link rel="stylesheet" type="text/css" href="msdropdown/dd.css"></link>-->


  <!--    <div id="load" style="position:absolute; top: 0px; left: 0px; width: 100%; height: 100%; text-align: center; vertical-align: middle">
        <img id="loadimage" src="http://www.mhdspoje.cz/jrw20/png/load.PNG" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%" alt=""/>
        <table border="0" style="width: 100%; height: 100%; color: orange; font-weight: bold; font-size: 14px;">
          <tr>
            <td>
              <a>... nahrďż˝vďż˝m data ...</a>
              <br/>
              <img src="http://www.mhdspoje.cz/jrw20/png/load.GIF" width="100" height="100" alt=""/>
              <br/>
              <a id="loadtext"></a>
            </td>
          </tr>
        </table>
      </div>-->

  <?php
  $lang = $_GET['lang'];
  if ($lang == null) {
    $lang = 1;
  }
  echo "<script type='text/javascript'> language = " . $lang . "</script>";
  ?>

  <div class = "headerofmenu" style = "margin-left: 10px;">
    <div id ="menu" style = "margin-left: 20px; margin-right: 20px; border-bottom-style: dotted; border-bottom-width: 1px; border-bottom-color: #000000;">
      <div id="container">
        <div class="breadCrumbHolder module">
          <div id="breadCrumb1" class="breadCrumb module">
            <ul>
              <!--                <li>
                                <a onclick = "window.location.href='http://www.dpb.sk';"><img src="home.png"></img>&nbsp Home</a>
                              </li>-->
              <li>
                <a onclick = "showtabs(1);" style ="vertical-align: middle;"><img src="http://www.mhdspoje.cz/jrw20/png/JR.png">
                  <script>
                    if (language == 1) {
                      document.write("&nbsp Jízdní řády");
                    }
                    if (language == 2) {
                      document.write("&nbsp CestovnĂ© poriadky");
                    }
                    if (language == 3) {
                      document.write("&nbsp Time tables");
                    }
                  </script>
                </a>
              </li>
              <li>
                <img src="http://www.mhdspoje.cz/jrw20/png/ChevronOverlay.png">
              </li>
              <li>
                <a onclick = "showtabs(2);" style ="vertical-align: middle;"><img src="http://www.mhdspoje.cz/jrw20/png/spojeni.png">
                  <script>
                    if (language == 1) {
                      document.write("&nbsp Vyhledávání spojení");
                    }
                    if (language == 2) {
                      document.write("&nbsp VyhÄľadĂˇvanie spojenia");
                    }
                    if (language == 3) {
                      document.write("&nbsp Connections");
                    }
                  </script>
                </a>
              </li>
              <li>
                <img src="http://www.mhdspoje.cz/jrw20/png/ChevronOverlay.png">
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class = "backside" style="height: 18px; margin-left: 10px;"></div>
  <div class = "backbottom" style="margin-left: 10px;"></div>

  <div id ="divDopravce" style="visibility: visible; margin-left: 10px;">
    <div class = "backside">
    </div>
    <div class = "backside">
      <div style="margin-left: 20px">               
        <div style="width: 350px">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;">
            <script>
              if (language == 1) {
                document.write("Dopravce :");
              }
              if (language == 2) {
                document.write("Dopravce :");
              }
              if (language == 3) {
                document.write("Carriers :");
              }
            </script>
          </a>

          <div>
            <table border="0" cellspacing="0" cellpadding="0" align="left" style="margin-bottom: 10px;">

              <tr>
                <td><img id = "m0" class ="logomestoactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/OVram.png" onclick = "changeLocation2(0); "></td>
                <td><img id = "m1" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/TPram.png" onclick = "changeLocation2(1); "></td>
                <td><img id = "m2" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/DCram.png"onclick = "changeLocation2(2); "></td>
                <td><img id = "m3" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/ULram.png" onclick = "changeLocation2(3); "></td></tr>
              <tr>
                <td><img id = "m4" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/OLram.png" onclick = "changeLocation2(4); "></td>
                <td><img id = "m5" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/TRram.png" onclick = "changeLocation2(5); "></td>
                <td><img id = "m6" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/BRram.png" onclick = "changeLocation2(6); "></td>
                <td><img id = "m7" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/HKram.png" onclick = "changeLocation2(7); "></td></tr>
              <tr>
                <td><img id = "m8" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/PAram.png" onclick = "changeLocation2(8); "></td>
                <td><img id = "m9" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/ZLram.png" onclick = "changeLocation2(9); "></td>
                <td><img id = "m10" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/CBram.png" onclick = "changeLocation2(10);"></td>
                <td><img id = "m11" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/OPram.png" onclick = "changeLocation2(11); "></td>
              </tr>
              <tr>
                <td><img id = "m12" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/CHram.png" onclick = "changeLocation2(12); "></td>
                <td><img id = "m13" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/JIram.png" onclick = "changeLocation2(13); "></td>
                <td><img id = "m14" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/PBram.png"  onclick = "changeLocation2(14); "></td>
                <td><img id = "m15" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/ZNram.png"  onclick = "changeLocation2(15); "></td>
              </tr>
              <tr>
                <td><img id = "m16" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/KOram.png" onclick = "changeLocation2(16); "></td>
                <td><img id = "m17" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png" onclick = ""></td>
                <td><img id = "m18" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png"  onclick = ""></td>
                <td><img id = "m19" class ="logomestoinactive" border="0" hspace="3" vspace="3" src="http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png"  onclick = ""></td>
              </tr>
            </table>
          </div>

          <div id="divcombolocation">
            <select name="combolocation" id="combolocation" style="WIDTH: 400px" onchange="changeLocation();"></select>
          </div>
        </div>
      </div>
    </div>
    <div class = "backbottom">
    </div>
  </div>

  <div style="height: 18px"></div>

  <div id="tabsecond" style="visibility: hidden">
    <div style = "margin-left: 10px;">
      <div class = "backtop">
      </div>
      <div class = "backside">

        <div id="divcombolinky" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;">
            <script>
              if (language == 1) {
                document.write("Výběr linky :");
              }
              if (language == 2) {
                document.write("VĂ˝ber linky :");
              }
              if (language == 3) {
                document.write("Choice route :");
              }
            </script>
          </a>
        </div>

        <div id="divcombosmer" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;">
            <script>
              if (language == 1) {
                document.write("Výběr směru :");
              }
              if (language == 2) {
                document.write("VĂ˝ber smeru :");
              }
              if (language == 3) {
                document.write("Direction :");
              }
            </script>
          </a>
        </div>

        <div id="divcombotrasa" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;">
            <script>
              if (language == 1) {
                document.write("Výběr stanice :");
              }
              if (language == 2) {
                document.write("VĂ˝ber stanice :");
              }
              if (language == 3) {
                document.write("* Station :");
              }
            </script>
          </a>
        </div>

        <div id="datum" style="width: 350px;  margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;">
            <script>
              if (language == 1) {
                document.write("Datum JŘ :");
              }
              if (language == 2) {
                document.write("DĂˇtum CP :");
              }
              if (language == 3) {
                document.write("Date TT :");
              }
            </script>
          </a>
        </div>

        <div id="button" style="margin-top: 25px; width: 350px; margin-left: 20px;">
        </div>
        <div id="buttonkomplex" style="margin-top: 25px; width: 350px; margin-left: 20px;">
        </div>
      </div>
      <div class = "backbottom">
      </div>
    </div>

    <div id="divJR" style="margin-top: 15px; margin-left: 10px; visibility: hidden;">
      <div class = "backtop">
      </div>
      <div class = "backside">
        <div id="jr" style="margin-left: 20px;"></div>
      </div>
      <div class = "backbottom">
      </div>      
    </div>
  </div>


  <div id="tabfirst" style="visibility: hidden; position: absolute; top: 0px;">
    <div style = "margin-left: 10px;">
      <div class = "backtop">
      </div>
      <div class = "backside">
        <div id="divcomboodZastavkySpojeni" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;"><script>
               if (language == 1) {
                 document.write("Ze zastávky :");
               }
               if (language == 2) {
                 document.write("Zo zastĂˇvky :");
               }
               if (language == 3) {
                 document.write("From (station) :");
               }
             </script>
          </a>
        </div>

        <div id="divcombodoZastavkySpojeni" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;"><script>
               if (language == 1) {
                 document.write("Do zastávky :");
               }
               if (language == 2) {
                 document.write("Do zastĂˇvky :");
               }
               if (language == 3) {
                 document.write("To (station) :");
               }
             </script>
          </a>
        </div>

        <div id="datumspojeni" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;"><script>
               if (language == 1) {
                 document.write("Datum :");
               }
               if (language == 2) {
                 document.write("DĂˇtum :");
               }
               if (language == 3) {
                 document.write("Date :");
               }
             </script>
          </a>
        </div>

        <div id="casspojeni" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
          <a style="margin-top: 4px; font-family: Arial,Helvetica,sans-serif;
             font-size: 14px;
             font-weight: bold;
             color: #ffffff;"><script>
               if (language == 1) {
                 document.write("Čas :");
               }
               if (language == 2) {
                 document.write("ÄŚas :");
               }
               if (language == 3) {
                 document.write("Time :");
               }
             </script>
          </a>
        </div>

        <div id="buttonspojeni" style="margin-top: 25px; width: 350px; margin-left: 20px;"></div>
      </div>
      <div class = "backbottom">
      </div>
    </div>

    <div id ="divSpojeni" style="margin-top: 15px; margin-left: 10px; visibility: hidden;">
      <div class = "backtop" style="height: 8px;">
      </div>
      <div class = "backside">
        <div id="spojeni"  style="margin-left: 20px;">
        </div>
      </div>
      <div class = "backbottom">
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
        document.getElementById('divDopravce').style.visibility = 'hidden';
        document.getElementById('divDopravce').style.position = 'absolute';
        document.getElementById('divDopravce').style.top = '0px';
        document.getElementById('divDopravce').style.height = '0px';
        </script>";
  }
  $vlinka = $_GET['vl'];
//    echo "<script> document.getElementById('loadtext').innerHTML = '... dopravci ...';</script>";
  echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrw20/LoadLocation.php?str=Location'></script>";
//    echo "<script> document.getElementById('loadtext').innerHTML = '... zastďż˝vky ...';</script>";
  echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadPack.php?str=Packers&loc=" . $location . "'></script>";
  echo "<script type='text/javascript'>
      packet = Packers.getPack(new Date());

      lokace = " . $location . ";
      verze = 1;
      </script>";
//    echo "<script type='text/javascript' src='http://www.mhdspoje.cz/test/LoadZastavky.php?str=Zastavky&loc=".$location."&pac=0'></script>";
  echo "<script type='text/javascript' charset='utf-8'>
  var target = document.createElement('script');
  document.body.appendChild(target);
  target.setAttribute('charset', 'utf-8');
  target.setAttribute('src', 'http://www.mhdspoje.cz/jrwdebug/php/LoadZastavky.php?str=Zastavky&loc='+lokace+'&pac='+packet);
  </script>";

//    echo "<script> document.getElementById('loadtext').innerHTML = '... linky ...';</script>";
//    echo "<script type='text/javascript' src='http://www.mhdspoje.cz/test/LoadLinky.php?str=Linky&str1=Zastavky&loc=".$location."&vp=0&a=1&t=1&pac=0'></script>";
  echo "<script type='text/javascript' charset='utf-8'>
  var target = document.createElement('script');
  document.body.appendChild(target);
  target.setAttribute('charset', 'utf-8');
  target.setAttribute('src', 'http://www.mhdspoje.cz/jrwdebug/php/LoadLinky.php?str=Linky&str1=Zastavky&loc='+lokace+'&vp=0&a=1&t=1&pac='+packet);
  </script>";

//    echo "<script> document.getElementById('loadtext').innerHTML = '... poznďż˝mky ...';</script>";
//    echo "<script type='text/javascript' src='http://www.mhdspoje.cz/test/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc=".$location."&pac=0'></script>";
  echo "<script type='text/javascript' charset='utf-8'>
  var target = document.createElement('script');
  document.body.appendChild(target);
  target.setAttribute('charset', 'utf-8');
  target.setAttribute('src', 'http://www.mhdspoje.cz/jrwdebug/php/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc='+lokace+'&pac='+packet);
  </script>";

//    echo "<script> document.getElementById('loadtext').innerHTML = '... kalendďż˝ďż˝ ...';</script>";
//    echo "<script type='text/javascript' src='http://www.mhdspoje.cz/test/LoadKalendar.php?str=Kalendar&loc=".$location."&pac=0'></script>";
  echo "<script type='text/javascript' charset='utf-8'>
  var target = document.createElement('script');
  document.body.appendChild(target);
  target.setAttribute('charset', 'utf-8');  
  target.setAttribute('src', 'http://www.mhdspoje.cz/jrwdebug/php/LoadKalendar.php?str=Kalendar&loc='+lokace+'&pac='+packet);
  </script>";

  echo "<script type='text/javascript'>
                verze = 1;
                zobrazitkurz = " . $enablekurz . ";
                loadLocation(" . $location . ");
                lokace = " . $location . ";
                loadZastavky1(Zastavky);
                loadCalendar1();
                loadOther1();
                showpage1();
                nastavLinku(" . $vlinka . ");
                </script>";
  ?>

  <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery-1.3.2.min.js"></script>
  <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.dd.js"></script>
  <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.timeentry.pack.js"></script>
  <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.mousewheel.js"></script>
  <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.timeentry-cs.js"></script>


  <script language="javascript">
    //    $("#combolocation").msDropDown();
    //$("#combolinky").msDropDown();
    $("#casRange").timeEntry({show24Hours: true, showSeconds: false});
    $('#casRange').timeEntry('change', $.timeEntry.regional['cs']);
    var time = new Date();
    $('#casRange').timeEntry('setTime', time);

    //    $("#divcombosmer").msDropDown();
    //    $("#divcombotrasa").msDropDown();
  </script>

<!--    <script type="text/javascript">
      window.onscroll = function () {
        setLoad();
      }
    </script>-->

