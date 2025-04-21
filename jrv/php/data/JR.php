<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>   
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>  
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/menuAndroid1.css"/>   
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/JRAndroid1.css"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/kalendarAndroid1.css"/>
  </head>
  <body>  
    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>  
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">    
      var JR = new JRData(<?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, 'select_linka', 'select_smer', 'select_trasa', null, null, 'select_spojeni_OD', 'select_spojeni_DO');
      JR.setCodePage("W1250");
      window.onload = new function() { 
        JR.initialize(true, false); 
        <? $datumJR = date('Y-m-d'); ?>
        <?php
          if ($_GET['type'] == 1) {
        ?>
            getJR('<?php echo $_GET[linka]; ?>', <?php echo $_GET[smer]; ?>, <?php echo $_GET[tarif]; ?>, <?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, 0, "<?php echo date_format(new DateTime($datumJR), 'd_m_Y'); ?>", 0, null, null, null, 0, 0);
        <?php
          }
        ?>
        <?php
          if ($_GET['type'] == 2) {
        ?>
            getSpojeniResult(<?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, "<?php echo $_GET[datum]; ?>", <?php echo $_GET[hh]; ?>, <?php echo $_GET[mm]; ?>, <?php echo $_GET[odz]; ?>, <?php echo $_GET[doz]; ?>)
        <?php
          }
        ?>  
      }
    </script>
  </body>
</html>
