<?php
Header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  Header('Access-Control-Allow-Methods: GET');
  Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  Header('Access-Control-Max-Age: 86400');
  die;
}
$location = $_GET['location'];
?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
<script type="text/javascript" charset="UTF-8" src="//www.mhdspoje.cz/jrw50/js/JRclass50.js"></script>

<script type="text/javascript">
  var vlocation = <?php echo $location; ?>;
  var vpacket = null;
</script>

<div id="divRoute" style="height: 100%;"></div>
<!--&sensor=true  style="height: 100%;"-->
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyA__SF9O1u5pvfMuspvpSxLToNLTRhtGdo&sensor=true&libraries=places"></script>

<script>
  var JR = new JRData(vlocation, vpacket);
  JR.setRouteDiv("divRoute");
  JR.aGeo = document.getElementById(JR.routediv);
  JR.setLang("cz");
  JR.setVersion(51);
  JR.setMove(false);
  JR.setCodePage("UTF");
  window.onload = new function() { JR.initialize(true, false, 9); }
</script>
