<?php
mb_internal_encoding("iso-8859-1");
mb_http_output( "iso-8859-1" );
header("Cache-Control: no-cache, must-revalidate");
$mesiceW1250 = array("ledna", "února", "března", "dubna", "května", "června", "července", "srpna", "září", "října", "listopadu", "prosince");

require_once 'lib/login.php';

$povoleni = false;
if (autentizovat() == true) {
  $povoleni = true;
} else {

  if (login($_POST["username"], $_POST["pass"]) == true) {
    $povoleni = true;
  } else {
    $povoleni = false;
  }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="cs">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/main.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/styles.css" media="screen">
    <link rel="stylesheet" href="css/global.css" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="../css/JRFS/JRFS.css">
<!--    <link rel="stylesheet" type="text/css" href="../css/JRTeplice.css">      -->
  </head>
  <body style="visibility: visible;">
    <?php
    if (isset($_POST["username"]) && isset($_POST["pass"])) {
      if ($povoleni == false) {
        ?>
        <h1 align="center">chybné přihlášení !!</h1>
        <?php
      }
    }
    ?>

    <?php
    if ($povoleni == false) {
      if (autentizovat() == false) {
        include 'pages/login.php';
      }
    } else {

      include 'pages/menu.php';

      if ($_GET['page'] == 1) {
        include 'pages/P1_JRNahled.php';
      }

      if ($_GET['page'] == 2) {
        include 'pages/P2_JRPacket.php';
      }

      if ($_GET['page'] == 3) {
        include 'pages/P3_Import.php';
      }
      
      if ($_GET['page'] == 4) {
        include 'pages/P4_Reklama.php';
      }
    }
    ?>

    <div id="footer" style="clear:both;text-align:right;">&copy; <?php echo date('Y'); ?> FS Software s.r.o.</div>

    <script type='text/javascript'>
//      document.body.style.visibility = "";

<?php if (isset($_GET['scup'])) { ?>
    window.scroll(0, <?php echo $_GET['scup']; ?>);
<?php } ?>

  function app_href(tag) {
    if (tag != null) {
      tag.href = tag.href + '&scup=' + getScrollXY()[1];
    }
  }

  function Odhlasit(hrefurl) {
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + (-1));
    var c_value=escape('') + ((-1==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie="<?php echo $cook_name; ?>" + "=" + c_value;
    document.location.href=hrefurl;
  }

  if (document.getElementById('username') != null) {
    document.getElementById('username').focus();
  }
    </script>
  </body>
</html>