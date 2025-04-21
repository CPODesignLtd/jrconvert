<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
    <link rel="stylesheet" type="text/css" href="css/JRHradec/menuHradec.css"/>   
    <link rel="stylesheet" type="text/css" href="css/JRHradec/JRHradec.css"/>
    <link rel="stylesheet" type="text/css" href="css/JRHradec/kalendarHradec.css"/>    

    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/kalendar.js"></script>   

    <title></title>
  </head>
  <body>
    <script type="text/javascript">      
      var aJR = null;
      var moving = false;
      document.onmousemove = function mouseMove(ev){        
        if (moving == true) {           
          document.body.onselectstart = "return false";
          ev = ev || window.event;
          var mousePos = mouseCoords(ev);
          document.getElementById(aJR).style.left = document.getElementById(aJR).offsetLeft + (mousePos.x - mx) + 'px';
          document.getElementById(aJR).style.top = document.getElementById(aJR).offsetTop + (mousePos.y - my) + 'px';
          mx = mousePos.x;
          my = mousePos.y;
        }
      }        
      function getMesta(data) {
        if (aJR == null) {
          aJRn = document.createElement('div');
          aJRn.style.position = "absolute";
          aJRn.className = "div_jr";
          aJRn.id = "divJRnew";
          aJRn.style.top = "330px";
          aJRn.style.zIndex = 100000;
          aJRn.onmousedown = function(e) {           
            if (e != null) {
              e.stopPropagation();
            } else {   
              if (!e) var e = window.event;
              e.cancelBubble = true;
              e.returnValue = false;
            } 
          }
          document.body.appendChild(aJRn);
          aJR = aJRn.id;
        }  
  
        document.getElementById(aJR).innerHTML = data;  
  
        document.getElementById(aJR).style.top = ScrollXY()[1] + 20 + "px";
        document.getElementById(aJR).style.visibility = 'visible';
        document.getElementById('movediv').onmousedown = function mouseDown(ev) {
          ev = ev || window.event;
          var mousePos = mouseCoords(ev);
          mx = mousePos.x;
          my = mousePos.y;
          moving = true;
          document.onselectstart = function(ev) {
            return false;
          };
        }

        document.onmouseup = function() {
          moving = false;
          document.onselectstart = null;
        };
      }
    </script>    

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
              <script>
                getMesta(               
                "<div class = 'div_pozadikomplex'>"+
                  "<div id='movediv' class='movediv'>"+       
                  "    <img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJR();'></img>"+
                  "    </div>"+
                  "  <table border='0' cellspacing='0' cellpadding='0' align='left' style='margin-bottom: 10px;'>"+
                  "  <tr>"+
                  "    <td><img id = 'm0' class ='logomestoactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/OVram.png' onclick = 'changeLocation2(0); '>Dopravní podnik města Ostravy a.s.</td>"+
                  "    <td><img id = 'm1' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/TPram.png' onclick = 'changeLocation2(1); '></td>"+
                  "  </tr>"+
                  "  <tr>"+
                  "    <td><img id = 'm2' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/DCram.png'onclick = 'changeLocation2(2); '></td>"+
                  "    <td><img id = 'm3' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/ULram.png' onclick = 'changeLocation2(3); '></td>"+
                  "  </tr>"+
                  "  <tr>"+
                  "    <td><img id = 'm4' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/OLram.png' onclick = 'changeLocation2(4); '></td>"+
                  "    <td><img id = 'm5' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/TRram.png' onclick = 'changeLocation2(5); '></td>"+
                  "  </tr>"+
                  "  <tr>"+

                  "    <td><img id = 'm6' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/BRram.png' onclick = 'changeLocation2(6); '></td>"+
                  "    <td><img id = 'm7' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/HKram.png' onclick = 'changeLocation2(7); '></td>"+
                  "  </tr>"+
                  "  <tr>"+
                  "    <td><img id = 'm8' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/PAram.png' onclick = 'changeLocation2(8); '></td>"+
                  "    <td><img id = 'm9' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/ZLram.png' onclick = 'changeLocation2(9); '></td>"+
                  "  </tr>"+
                  "  <tr>"+

                  "    <td><img id = 'm10' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/CBram.png' onclick = 'changeLocation2(10);'></td>"+
                  "    <td><img id = 'm11' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/OPram.png' onclick = 'changeLocation2(11); '></td>"+
                  "  </tr>"+
                  "  <tr>"+
                  "    <td><img id = 'm12' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/CHram.png' onclick = 'changeLocation2(12); '></td>"+
                  "    <td><img id = 'm13' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/JIram.png' onclick = 'changeLocation2(13); '></td>"+
                  "  </tr>"+
                  "  <tr>"+

                  "    <td><img id = 'm14' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/PBram.png'  onclick = 'changeLocation2(14); '></td>"+
                  "    <td><img id = 'm15' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/ZNram.png'  onclick = 'changeLocation2(15); '></td>"+
                  "  </tr>"+
                  "  <tr>"+
                  "    <td><img id = 'm16' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/KOram.png' onclick = 'changeLocation2(16); '></td>"+
                  "    <td><img id = 'm17' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png' onclick = ''></td>"+
                  "  </tr>"+
                  "  <tr>"+

                  "    <td><img id = 'm18' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png'  onclick = ''></td>"+
                  "    <td><img id = 'm19' class ='logomestoinactive' border='0' hspace='3' vspace='3' src='http://www.mhdspoje.cz/jrw20/mesta/UNIVram.png'  onclick = ''></td>"+
                  "  </tr>"+
                  "  </table>"+
                  "</div>");
              </script>

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

    <!--    <div id="tabsecond" style="visibility: hidden">
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
              <div id="jr" style="margin-left: 20px;"">
            </div>
          </div>
          <div class = "backbottom">
          </div>
        </div>
      </div>-->


    <!--  <div id="tabfirst" style="visibility: hidden; position: absolute; top: 0px;">
        <div style = "margin-left: 10px;">
          <div class = "backtop">
          </div>
          <div class = "backside">
            <div id="divcomboodZastavkySpojeni" style="width: 350px; margin-left: 20px; margin-bottom: 10px;">
              <a style="font-family: Arial,Helvetica,sans-serif;
                   font-size: 14px;
                   font-weight: bold;
                   color: #ffffff;""><script>
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
                   color: #ffffff;""><script>
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
                   color: #ffffff;""><script>
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
                   color: #ffffff;""><script>
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
    
        </div>-->

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
    ?>


  </body>
</html>