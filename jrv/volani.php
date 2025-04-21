<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>   
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>  
  </head>
  <body>
    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRdata50.js"></script>

    <script type="text/javascript">
      window.onload = new function() {
        getPacketList(1, null);              
      }
    </script>
    <script type="text/javascript">
      for(i = 0; i < PacketsData.length; i++) {
        document.write("<a>" + PacketsData[i][0] + " , " + PacketsData[i][1] + " , " + PacketsData[i][2] + " , " + PacketsData[i][3] + " , " + PacketsData[i][4] + " , " + PacketsData[i][5] + " , " + PacketsData[i][6] + " , " + PacketsData[i][7]) + "</a></br>";
      }
      document.write("<a>aktualni balicek : " + getAktualPacketDatum(29, 10, 2012, PacketsData) + "</a>");
    </script>
  </body>
</html>  
