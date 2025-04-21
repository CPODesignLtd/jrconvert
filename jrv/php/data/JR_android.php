<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/menuAndroid1.css"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/JRAndroid1.css"/>
    <link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/Android/kalendarAndroid1.css"/>
  </head>
  <body>
    <?php
    error_reporting(0);
    $datumJR = date('Y-m-d');
    $linka = $_GET[linka];
    $smer = $_GET[smer];
    $tarif = $_GET[tarif];
    $location = $_GET[location];
    $packet = $_GET[packet];

    if (isset($_GET[datum])) {
      $datum = $_GET[datum];
    } else {
      $datum = date_format(new DateTime($datumJR), 'd_m_Y');
    }

    if (isset($_GET[lang])) {
      $lang = $_GET[lang];
    } else {
      $lang = 'cz';
    }

    if (isset($_GET[x])) {
      $x = $_GET[x];
    } else {
      $x = null;
    }

    if (isset($_GET[y])) {
      $y = $_GET[y];
    } else {
      $y = null;
    }

    if (isset($_GET[sloupec])) {
      $sloupec = $_GET[sloupec];
    } else {
      $sloupec = null;
    }

    if (isset($_GET[sel])) {
      $sel = $_GET[sel];
    } else {
      $sel = -1;
    }

    $packets = 0;
    $denJR = 0;
    $sdruzJR = 0;
    $showkurz = 0;
    $incspoje = 0;

    $path = "https://www.mhdspoje.cz/jrw50/php/";

    if (($x == null) || ($y == null) || ($sloupec == null)) {
      $fullUrl = $path . "loadJRJSON_Android.php?linka=" . $linka .
              "&smer=" . $smer . "&tarif=" . $tarif . "&location=" . $location .
              "&packet=" . $packet . "&datum=" . $datum . "&denni=" . $denJR . "&sdruz=" . $sdruzJR .
              "&sel=" . $sel . "&packets=" . $packets . "&kurz=" . $showkurz . "&incspoje=" . $incspoje . "&lang=" . $lang;   //&callback=getJRDataAndroid
    } else {
      $fullUrl = $path . "loadJRJSON_Android.php?linka=" . $linka .
              "&smer=" . $smer . "&tarif=" . $tarif . "&location=" . $location .
              "&packet=" . $packet . "&denni=" . $denJR . "&datum=" . $datum . "&sdruz=" . $sdruzJR . "&sel=" . $sel .
              "&jrtype=" . $sloupec . "&x=" . $x . "&y=" . $y . "&packets=" . $packets . "&kurz=" . $showkurz . "&incspoje=" . $incspoje . "&lang=" . $lang;
    }

    if ($_GET['type'] == 1) {
      echo file_get_contents($fullUrl); //"http://www.mhdspoje.cz/jrw50/php/loadJRJSON_Android.php?type=1&linka=1050913&smer=0&tarif=5&location=1&packet=15&denni=0&sdruz=0"
    }
    ?>

<!--    <script type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
//      var JR = new JRData(<?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, 'select_linka', 'select_smer', 'select_trasa', null, null, 'select_spojeni_OD', 'select_spojeni_DO');
//      JR.setCodePage("W1250");
      window.onload = new function() {
//        JR.initialize(true, false);-->
    <? $datumJR = date('Y-m-d'); ?>
    <?php
    if ($_GET['type'] == 1) {
      ?>
                          <!--            getJR_Android('<?php echo $_GET[linka]; ?>', <?php echo $_GET[smer]; ?>, <?php echo $_GET[tarif]; ?>, <?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, 0, "<?php echo date_format(new DateTime($datumJR), 'd_m_Y'); ?>", 0, null, null, null, 0, 0);-->
      <?php
    }
    ?>
    <?php
    if ($_GET['type'] == 2) {
      ?>
                          <!--            getSpojeniResult(<?php echo $_GET[location]; ?>, <?php echo $_GET[packet]; ?>, "<?php echo $_GET[datum]; ?>", <?php echo $_GET[hh]; ?>, <?php echo $_GET[mm]; ?>, <?php echo $_GET[odz]; ?>, <?php echo $_GET[doz]; ?>)-->
      <?php
    }
    ?>
    }
    <!--    </script>-->
  </body>
</html>