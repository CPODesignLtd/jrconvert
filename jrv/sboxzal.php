<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/javascript; charset=windows-1250"></meta>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/tableJR.css"></link>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/tableSpojeni.css"></link>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/Calendar.css"></link>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/Combo.css"></link>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/mainsbox.css"></link>
    <link rel='stylesheet' type='text/css' href='css/JR.css'></link>;
    <?php
    $location = $_GET['l'];

    if ($location == null) {
      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrwdebug/css/ddAll.css'></link>";
      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrwdebug/css/ButtonAll.css'></link>";
      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrwdebug/css/backall.css'></link>";
    } else {
      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/dd.css'></link>";
      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrwdebug/css/Button.css'></link>";
      if ($location == 1) {
        echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/backTP.css'></link>";
      } else {
        echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/backall.css'></link>";
      }
    }
    ?>

    <?php
    $menu = $_GET['menu'];
    if ($menu == null) {
      $menu = 1;
    }
    if ($menu == 1) {
//      echo "<link rel='stylesheet' type='text/css' href='http://www.mhdspoje.cz/jrw20/ccs/BreadCrumb.css'></link>";
    }
    ?>

    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrwdebug/css/jquery.timeentry.css"></link>

    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Structures.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw50/js/global.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/JR.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Spojeni.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Combo.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Calendar.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrwdebug/js/Button.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/JR.js"></script>

    <?php
    $lang = $_GET['lang'];
    if ($lang == null) {
      $lang = 1;
    }
    echo "<script type='text/javascript'> language = ".$lang."</script>";
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

    <div id = "searchDIV" class= "search" style="visibility: hidden; display: block;">
      <img src="http://www.mhdspoje.cz/jrw20/png/searchback.png" width="100%" height="100%"></img>
      <div style="position:absolute; margin:0px; padding:0px; left:0px; top:0px; height:100%; width:100%;">
      <?php
      if ($menu == 1) {
        echo "
      <div id ='menusbix' class = 'menusbox'>
              <ul class = 'menuli'>
                <li class = 'menuli'>
                  <a class = 'textmenu' onclick = 'showtabs(1);'><img src='http://www.mhdspoje.cz/jrw20/png/JR1.png'>
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
                  <a class = 'textmenu' onclick = 'showtabs(2);'><img src='http://www.mhdspoje.cz/jrw20/png/spojeni2.png'>
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

<!--      <div style="height: 2px"></div>-->

      <div id="tabsecond" style="visibility: hidden">
        <div style = "margin-left: 10px;">


          <div id="divcombolinky" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                if (language == 1) {
                  document.write("Výběr linky :");
                }
                if (language == 2) {
                  document.write("Výber linky :");
                }
                if (language == 3) {
                  document.write("Choice route :");
                }
              </script>
            </a>
          </div>

          <div id="divcombosmer" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                if (language == 1) {
                  document.write("Výběr smšru :");
                }
                if (language == 2) {
                  document.write("Výber smeru :");
                }
                if (language == 3) {
                  document.write("Direction :");
                }
              </script>
            </a>
          </div>

          <div id="divcombotrasa" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                if (language == 1) {
                  document.write("Výběr stanice :");
                }
                if (language == 2) {
                  document.write("Výber zastávky :");
                }
                if (language == 3) {
                  document.write("* Station :");
                }
              </script>
            </a>
          </div>

          <div id="datum" style="width: 280px;  margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                if (language == 1) {
                  document.write("Datum JŘ :");
                }
                if (language == 2) {
                  document.write("Dátum CP :");
                }
                if (language == 3) {
                  document.write("Date TT :");
                }
              </script>
            </a>
          </div>

          <div id="button" style="margin-top: 7px; width: 280px; margin-left: 0px;">
          </div>
        </div>

      </div>


      <div id="tabfirst" style="visibility: hidden; position: absolute; top: 0px;">
        <div style = "margin-left: 10px;">

          <div id="divcomboodZastavkySpojeni" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                 if (language == 1) {
                   document.write("Ze zastávky :");
                 }
                 if (language == 2) {
                   document.write("Zo zastávky :");
                 }
                 if (language == 3) {
                  document.write("From (station) :");
                }
               </script>
            </a>
          </div>

          <div id="divcombodoZastavkySpojeni" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                 if (language == 1) {
                   document.write("Do zastávky :");
                 }
                 if (language == 2) {
                   document.write("Do zastávky :");
                 }
                 if (language == 3) {
                  document.write("To (station) :");
                }
               </script>
            </a>
          </div>

          <div id="datumspojeni" style="width: 280px; margin-left: 0px; margin-bottom: 1px;">
            <a style="font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                 if (language == 1) {
                   document.write("Datum :");
                 }
                 if (language == 2) {
                   document.write("Dátum :");
                 }
                 if (language == 3) {
                  document.write("Date :");
                }
               </script>
            </a>
          </div>

          <div id="casspojeni" style="height: auto; width: 280px; margin-left: 0px; margin-bottom: 4px;">
            <a style="margin-top: 2px; font-family: Arial,Helvetica,sans-serif;
               font-size: 12px;
               font-weight: bold;
               color: #c92233;
               margin-bottom: 1px;">
              <script>
                 if (language == 1) {
                   document.write("Čas :");
                 }
                 if (language == 2) {
                   document.write("Čas :");
                 }
                 if (language == 3) {
                  document.write("Time :");
                }
               </script>
            </a>
          </div>

          <div id="buttonspojeni" style="margin-top: 7px; width: 280px; margin-left: 0px;"></div>

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

    $pac = 0;

    if ($location == 1) {
      $pac = 4;
    }
    if ($location == 6) {
      $pac = 39;
    }
   if ($location == 4) {
      $pac = 6;
    } if ($location == 12) {
      $pac = 3;
    }  if ($location == 7) {
      $pac = 4;
    }
  if ($location == 8) {
      $pac = 5;
    } if ($location == 14) {
      $pac = 1;
    } if ($location == 2) {
      $pac = 2;
    } if ($location == 11) {
      $pac = 2;
    } if ($location == 16) {
      $pac = 4;
    } if ($location == 13) {
      $pac = 1;
    } if ($location == 9) {
      $pac = 1;
    } if ($location == 3) {
      $pac = 1;
    }
    echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadPack.php?str=Packers&loc=".$location."'></script>";
    echo "<script type='text/javascript'>
              packet = ".$pac.";//Packers.getPack(new Date());
              </script>";
    echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadZastavky.php?str=Zastavky&loc=".$location."&pac=".$pac."'></script>";
    echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadLinky.php?str=Linky&str1=Zastavky&loc=".$location."&vp=0&a=1&t=1&pac=".$pac."'></script>";
    echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc=".$location."&pac=".$pac."'></script>";
    echo "<script type='text/javascript' charset='utf-8' src='http://www.mhdspoje.cz/jrwdebug/php/LoadKalendar.php?str=Kalendar&loc=".$location."&pac=".$pac."'></script>";

    echo "<script type='text/javascript'>
                verze = 1;
                zobrazitkurz = ".$enablekurz.";
                lokace = ".$location.";
                loadZastavky1(Zastavky);
                loadCalendar1();
                loadOther1();
                showpage1();
                nastavLinku(".$vlinka.");
                rleft = ".$rleft.";
                rtop = ".$rtop.";
                rposition = '".$rpos."';
                document.getElementById('searchDIV').style.position = '".$pos."';
                document.getElementById('searchDIV').style.top = '".$top."px';
                document.getElementById('searchDIV').style.left = '".$left."px';
                document.getElementById('searchDIV').style.visibility = 'visible';
                document.getElementById('searchDIV').style.zIndex = '100';                  
          </script>";

    $tab = $_GET['tab'];
    if ($tab != null) {
    echo "<script type='text/javascript'>
                showtabs(".$tab.");
                </script>";
    }
    ?>

    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.dd.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.timeentry.pack.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="http://www.mhdspoje.cz/jrw20/js/jquery.timeentry-cs.js"></script>


    <script language="javascript">
      $("#casRange").timeEntry({show24Hours: true, showSeconds: false});
      $('#casRange').timeEntry('change', $.timeEntry.regional['cs']);
      var time = new Date();
      $('#casRange').timeEntry('setTime', time);

    </script>