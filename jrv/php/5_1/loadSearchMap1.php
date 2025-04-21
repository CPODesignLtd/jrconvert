<meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
<?php
$location = $_GET['location'];
$packet = $_GET['packet'];
$address = $_GET['address'];
$type = $_GET['type'];
?>
<?php
  if ($_POST['post'] == 'vypis') {
      echo $_POST['t'];
      echo $_POST['t1'];
  } else {
?>  
<form enctype="multipart/form-data" name="frm" method="post" action="http://www.mhdspoje.cz/jrw50/php/5_1/loadSearchMapContext.php?location=6&packet=232&address=tesco&type=1">
    <input type="text" style="width: 100%;" name="t" id="t" style="visibility: hidden">
    <input style="margin-right: 80px;" id = "post" type="submit" name="post" value="vypis" style="visibility: hidden;">
<div id="map" style="width: 0px; height: 0px; visibility: hidden;"></div>
<?php
if ($type == 2) {                       
               ?>    
               <div id="divRoute" style="height: 100%;"></div>               
               <?php
               }
               ?>                              

<div id="overlay">
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyCqdToSAutmuqDTrd-rsDR0RuGbHlucPhY&sensor=true&libraries=places"></script>
<script type="text/javascript">
    function getJSON() {       
        Android.getJSON(JSON.stringify(this.vyhledane_stanice));
    }
</script>
<script  type="application/javascript" charset="windows-1250">
  var stanice = new Array();
  var vybrane_stanice = new Array();
  var vyhledane_stanice = new Array();
  var pom = new Array();

  var sdiak = "áäčďéěíĺľňóôöŕšťúůüýřžÁÄČĎÉĚÍĹĽŇÓÔÖŔŠŤÚŮÜÝŘŽ";
  var bdiak = "aacdeeillnooorstuuuyrzAACDEEILLNOOORSTUUUYRZ";

  function bezdiak(txt) {
    tx = "";
    for(p = 0; p < txt.length; p++) {
      if (sdiak.indexOf(txt.charAt(p)) != -1) {
        tx += bdiak.charAt(sdiak.indexOf(txt.charAt(p)));
      }
      else tx += txt.charAt(p);
    }
    return tx;
  }

<?php
$dbname = 'savvy_mhdspoje';

if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
} else {

    mysql_query("SET CHARACTER SET cp1250");
    mysql_select_db($dbname);

    $sql = "select distinct zastavky.nazev, zastavky.locb, zastavky.loca, zastavky.c_zastavky, zastavky.passport from zastavky left outer join zaslinky
    on (zastavky.idlocation = zaslinky.idlocation and zastavky.packet = zaslinky.packet and zastavky.c_zastavky = zaslinky.c_zastavky)
    where zastavky.idlocation = " . $location . " and zastavky.packet = " . $packet . " and zaslinky.voz = 1 ORDER BY zastavky.nazev  COLLATE utf8_czech_ci";

    $result = mysql_query($sql);

    $iter = 0;

    while ($row = mysql_fetch_row($result)) {
        ?>

                          stanice[<?php echo $iter; ?>] = new Array(<?php echo '"' . preg_replace('/,/', '|', $row[0]) . '", "' . $row[1] . '", "' . $row[2] . '", "' . $row[3] . '", "' . $row[4] . '", "' . ($iter + 1) . '"'; ?>);
        <?php
        $iter++;
    }
    ?>


             var aGeoS = document.getElementById("map");
             map = new google.maps.Map(aGeoS, this.myOptions);

             var image = '//www.mhdspoje.cz/jrw50/image/busstop.png';
             var location_address = '';
             var address = '<?php echo $address; ?>';
             var res_address = bezdiak(address).trim().toLowerCase().split(' ');
             if (<?php echo $location; ?> == 6) {
               location_address = ', Bratislava, SK';
             }
             if (<?php echo $location; ?> == 14) {
               location_address = ', Povážská Bystrica, SK';
             }
             var location_lat = 0;
             var location_lng = 0;
             if (<?php echo $location; ?> == 6) {
               location_lat = 48.148601;
               location_lng = 17.107746;
             }
             if (self.location == 14) {
               location_lat = 49.113153;
               location_lng = 18.447511;
             }

             var request = {
               location: new google.maps.LatLng(location_lat, location_lng),
               radius: '50000',
               query: address + location_address
             };

             service = new google.maps.places.PlacesService(map);
             service.textSearch(request, function (results, status) {
               if (status == google.maps.places.PlacesServiceStatus.OK) {
                 for (var i = 0; i < results.length; i++) {
                   this.pom[i] = results[i].geometry.location.lat().toString() + ' , ' + results[i].geometry.location.lng().toString();
                   for (ii = 0; ii < stanice.length; ii++) {
                     if ((Math.abs(results[i].geometry.location.lat() - this.stanice[ii][1]) <= 0.003) && (Math.abs(results[i].geometry.location.lng() - this.stanice[ii][2]) <= 0.003)) {
                       this.stanice[ii][6] = results[i].geometry.location.lat();
                       this.stanice[ii][7] = results[i].geometry.location.lng();
                       this.vybrane_stanice[stanice[ii][3]] = this.stanice[ii];
                       new google.maps.Marker({
                         map: map,
                         position: new google.maps.LatLng(this.stanice[ii][1],  this.stanice[ii][2]),
                         icon: image,
                         namestation: this.stanice[ii][0]
                       });
                     }
                   }
                 }


                 for(i = 0; i < this.stanice.length; i++) {
                   var have = false;
                   var stanice_nodiak = bezdiak(this.stanice[i][0]).trim().toLowerCase();
                   for(ii = 0; ii < res_address.length; ii++) {
                     if (stanice_nodiak.indexOf(res_address[ii].trim()) > -1) {
                       have = true
                     }
                   }
                   if (have) {
                     this.stanice[i][6] = this.stanice[i][1];
                     this.stanice[i][7] = this.stanice[i][2];
                     this.vybrane_stanice[this.stanice[i][3]] = this.stanice[i];
                   }
                 }

                 var iter = 0;
                 for(i = 0; i < this.vybrane_stanice.length; i++) {
                   if (this.vybrane_stanice[i] != null) {
                     var zaznam = new Object();
                     zaznam.Name = this.vybrane_stanice[i][0];
                     zaznam.Lat = this.vybrane_stanice[i][1];
                     zaznam.Lon = this.vybrane_stanice[i][2];
                     zaznam.ID = this.vybrane_stanice[i][3];
                     zaznam.iID = this.vybrane_stanice[i][4];
                     zaznam.Poradi = this.vybrane_stanice[i][5];
                     zaznam.Lat1 = this.vybrane_stanice[i][6];
                     zaznam.Lon1 = this.vybrane_stanice[i][7];
                     this.vyhledane_stanice[iter] = zaznam;//this.vybrane_stanice[i];
                     iter++;
                   }
                 }
               }
               
               <?php
               if ($type == 1) {
                   ?>
               this.vypis();
               <?php
               }
               if ($type == 2) {                       
               ?>    
               this.searchMapShow(this.vyhledane_stanice[0][3]);
               <?php
               }
               ?>
             });
           
             function vypis() {
/*               var dd = document.createNode('div');
               dd.id = 'json';
               document.body.appendChild(dd);
               dd.appendChild(document.createTextNode((JSON.stringify(this.vyhledane_stanice))));*/
/*        var node = document.createElement("DIV");
        node.id = "json";
        node.innerHTML = */
       
        
    xx = document.getElementById("t");
    xx.setAttribute("value", JSON.stringify(this.vyhledane_stanice));    
    document.getElementById("post").click();    
//document.write(window.Android.getJSON(JSON.stringify(this.vyhledane_stanice)));

    
//                document.body.appendChild(document.createTextNode((JSON.stringify(this.vyhledane_stanice))));
//    getJSON();
//                document.open();
//    document.write(JSON.stringify(this.vyhledane_stanice));
//    document.close();
    
//    var myWindow = window.open();
//    myWindow.document.write(JSON.stringify(this.vyhledane_stanice));
    
//document.body.appendChild(node);
var target = document.getElementById('overlay');
//document.body.removeChild( target );
//               document.body.appendChild(document.createTextNode((JSON.stringify(this.vyhledane_stanice))));           
             }     
             
             
             function searchMapShow(tSearch_result) {
            var self = this;
            this.routediv = 'divRoute';
            if (tSearch_result >= 0) {
                if (this.routediv != null) {
                    var aGeoS = document.getElementById(this.routediv);
                    aGeoS.style.height = "500px";
                    this.myOptions = {
                    zoom: 16,
    //                center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP, //HYBRID,
                    overviewMapControl: true,
                    OverviewMapControlOptions: {
                        opened: true
                    }
                }
                    map = new google.maps.Map(aGeoS, this.myOptions);
                    var image_active = '//www.mhdspoje.cz/jrw50/image/busstop_active.png';
                    var image = '//www.mhdspoje.cz/jrw50/image/busstop.png';
                    var image_search = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
                    var allmarkers = [];
        /*            if (self.lang == 'cz') {
                        titlebutton = 'fdfdf';//info_5_CZ;
                    }

                    if (self.lang == 'sk') {
                        titlebutton = 'fssdfd';//info_5_SK;
                    }*/
                    
                    titlebutton = 'fdfdf';

                    for (i = 0; i < self.vyhledane_stanice.length; i++) {

                        if (tSearch_result == self.vyhledane_stanice[i][3]) {
                            var marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(self.vyhledane_stanice[i][1], self.vyhledane_stanice[i][2]), //newpoint,
                                icon: image_active,
                                namestation: self.vyhledane_stanice[i][0],
                                id: self.vyhledane_stanice[i][3],
                                info: new google.maps.InfoWindow({
                                    content: '<div id="content" style="height: 200px;">' +
                                            '<div id="siteNotice">' +
                                            '</div>' +
                                            '<h1 id="firstHeading" class="firstHeading">' + self.vyhledane_stanice[i][0] + '</h1>' +
                                            '<div id="seznamMap" style="text-align: center; padding-top: 20px; padding-bottom: 20px;">' +
                                            '<button style="width: 150px;" title="' +
        //                                    titlebutton + '" onclick="JRData.prototype.setIndexOdjezdyByValue(' + self.vyhledane_stanice[i][3] + '); JR.odjezdyResult(' + self.location + ', ' + self.packet + ');" id="mod-rscontact-submit-btn-124" ' +
                                            'name="mod_rscontact_submit-btn-124" class="formButton"><span class=""></span>' + titlebutton + '</button>' +
                                            '</div>' + '</div>'
                                })
                            });

                            allmarkers.push(marker);
                            marker.addListener('click', function () {
                                for (i = 0; i < allmarkers.length; i++) {
                                    allmarkers[i].info.close();
                                }
                                this.info.open(map, this);
                            });
                            /*          google.maps.event.addListener(marker, 'click', function() {
                             self.tagJRSeznamMap = 'seznamMap';
                             marker.info.open(map, marker);
                             });*/

                            /*          google.maps.event.addListener(marker.info, 'domready', function() {
                             getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, marker.id);
                             });*/

                            map.setCenter(new google.maps.LatLng(self.vyhledane_stanice[i][1], self.vyhledane_stanice[i][2]));
                        } else {
                            var marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(self.vyhledane_stanice[i][1], self.vyhledane_stanice[i][2]), //newpoint,
                                icon: image,
                                namestation: self.vyhledane_stanice[i][0],
                                id: self.vyhledane_stanice[i][3],
                                info: new google.maps.InfoWindow({
                                    content: '<div id="content" style="height: 200px;">' +
                                            '<div id="siteNotice">' +
                                            '</div>' +
                                            '<h1 id="firstHeading" class="firstHeading">' + self.vyhledane_stanice[i][0] + '</h1>' +
                                            '<div id="seznamMap"  style="text-align: center; padding-top: 20px; padding-bottom: 20px;">' +
                                            '<button style="width: 150px;" title="' +
        //                                    titlebutton + '" onclick="JRData.prototype.setIndexOdjezdyByValue(' + self.vyhledane_stanice[i][3] + '); JR.odjezdyResult(' + self.location + ', ' + self.packet + ');" id="mod-rscontact-submit-btn-124" ' +
                                            'name="mod_rscontact_submit-btn-124" class="formButton"><span class=""></span>' + titlebutton + '</button>' +
                                            '</div>' + '</div>'
                                })
                            });

                            allmarkers.push(marker);
                            marker.addListener('click', function () {
                                for (i = 0; i < allmarkers.length; i++) {
                                    allmarkers[i].info.close();
                                }
                                this.info.open(map, this);
                            });
                            /*          google.maps.event.addListener(marker, 'click', function() {
                             self.tagJRSeznamMap = 'seznamMap';
                             marker.info.open(map,marker);
                             });*/

                            /*          google.maps.event.addListener(marker.info, 'domready', function() {
                             getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, marker.id);
                             });*/
                        }

                        if ((self.vyhledane_stanice[i][6] != -1) && (self.vyhledane_stanice[i][7] != -1)) {

                            var lineSymbol = {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                            };

                            new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(self.vyhledane_stanice[i][6], self.vyhledane_stanice[i][7]), //newpoint,
                                icon: image_search
                            });

                            var linepath = [];
                            linepath.push(new google.maps.LatLng(self.vyhledane_stanice[i][6], self.vyhledane_stanice[i][7]));
                            linepath.push(new google.maps.LatLng(self.vyhledane_stanice[i][1], self.vyhledane_stanice[i][2]));
                            var lineTriangle = new google.maps.Polyline({
                                path: linepath,
                                icons: [{
                                        icon: lineSymbol,
                                        offset: '100%'
                                    }],
                                geodesic: true,
                                strokeColor: '#FF0000',
                                strokeOpacity: 1.0,
                                strokeWeight: 2
                            });
                            lineTriangle.setMap(map);
                        }

                    }
                }
                if (this.routediv != null) {
                    window.scroll(0, (document.getElementById(this.routediv).offsetTop - 20));
                }
            }
        }
    </script>        
</div>
               </form>
    <?php
}
  }
?>
