<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?sensor=true">
    </script>
    <script type="text/javascript">
      function initialize() {
    var geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(0, 0);    
    var myOptions = {      zoom: 8,      center: latlng,      mapTypeId: google.maps.MapTypeId.ROADMAP    }   
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);      
    geocoder.geocode( { 'address': 'Praha'}, function(results, status) {      
      if (status == google.maps.GeocoderStatus.OK) {        
        map.setCenter(results[0].geometry.location);        
        var marker = new google.maps.Marker({            map: map,            position: results[0].geometry.location        });      } 
      else {        alert("Geocode was not successful for the following reason: " + status);      }    });
      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>