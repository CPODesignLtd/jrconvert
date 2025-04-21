var mesice = new Array("ledna", "února", "bøezna", "dubna", "kvìtna", "èervna", "èervence", "srpna", "záøí", "øíjna", "listopadu", "prosince");

var moving = false;
var closemoving = false;
var mx;
var my;
var inJR = '';
var moveit = false;

function mouseCoords(ev){
  if(ev.pageX || ev.pageY){
    return {
      x: ev.pageX, 
      y: ev.pageY
    };
  }
  return {
    x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
    y: ev.clientY + document.body.scrollTop  - document.body.clientTop
  };
}

function JRData(aLocation, aPacket, aLinky, aSmery, aTrasy, aDatum) {
  this.self = this;
  this.location = aLocation;
  this.packet = aPacket;
  this.tagLinky = aLinky;
  this.tagSmery = aSmery;
  this.tagTrasy = aTrasy;
  this.tagDatum = aDatum;
  
  this.geocoder = null;
  this.myOptions = null;
  
  this.moveit = false;
  moveit = this.moveit;
  
  if (document.getElementById("page_container") != null) {
    document.writeln(document.getElementById("page_container").offsetLeft);    
    document.getElementById("divJR").style.left = document.getElementById("page_container").offsetLeft + "px";
  }
  this.loaded = false;   
}

JRData.prototype.initialize = function() {
  var self = this;
  document.getElementById("content_1").loaded = false;  
  document.getElementById("content_1").style.visibility = 'visible';
  
  if (this.moveit) {
    document.getElementById('divJR').style.position = "absolute";
    document.onmousemove = function mouseMove(ev){
      if (moving == true) {
        document.getElementById('tablejr').style.visibility = 'hidden';
        document.body.onselectstart = "return false";
        ev = ev || window.event;
        var mousePos = mouseCoords(ev);
        document.getElementById('divJR').style.left = document.getElementById('divJR').offsetLeft + (mousePos.x - mx) + 'px';
        document.getElementById('divJR').style.top = document.getElementById('divJR').offsetTop + (mousePos.y - my) + 'px';
        mx = mousePos.x;
        my = mousePos.y;
      }
    }    
  }
  
  this.loadData();
  
  document.getElementById(this.tagLinky).onchange = function() {
    self.onLinkaChange(self.location, self.packet);  
  }
  document.getElementById(this.tagSmery).onchange = function() {
    self.onSmerChange(self.location, self.packet);  
  }  
  
  try {  
    this.geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(0, 0);    
    this.myOptions = {
      zoom: 19,      
      center: latlng,      
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }       
  }
  catch(e) {}
}

JRData.prototype.loadData = function() {
  if (this.loaded == false) {
    this.disable_all_loaded_element();
    this.getLinkyList(this.location, this.packet, this.tagLinky);
    var dnes = new Date();
    var den = dnes.getDate();
    var mesic = dnes.getMonth() + 1;
    var rok = dnes.getFullYear();
    getKalendar("select_datum", mesic, rok, den, mesic, rok, den, true);
  }
}

JRData.prototype.setMove = function(aMoveit) {
  this.moveit = aMoveit;
  moveit = aMoveit;
}

JRData.prototype.map = function(address) {
  if (document.getElementById("map_canvas") != null) {
    var map = new google.maps.Map(document.getElementById("map_canvas"), this.myOptions);      
    this.geocoder.geocode( {
      'address': address
    }, function(results, status) {      
      if (status == google.maps.GeocoderStatus.OK) {
        var index = -1;
        for(i = 0; i < results.length; i++) {
          for(ii = 0; ii < results[i].types.length; ii++) {
            /*          el = document.createElement("a");
          el.innerHTML = i + "/" + ii + " - " + results[i].types[ii];
          document.body.appendChild(el);*/
            if ((results[i].types[ii] == "transit_station") || (results[i].types[ii] == "bus_station")) {
              index = i;
              break;
            }
          }
          if (index > -1) {
            break;
          }
        }
        map.setCenter(results[0].geometry.location); 
        /*      el = document.createElement("a");
          el.innerHTML = "index = " + index;
          document.body.appendChild(el);*/
        var marker = new google.maps.Marker({
          map: map,            
          position: results[index].geometry.location
        });
        map.setCenter(results[index].geometry.location);
      } 
    });  
  }
}

JRData.prototype.getKalendar = function(tagName, month, year, day, pmonth, pyear, pday, hide) {
  document.getElementById("a_select_datum").disabled = true;
  xmlhttp1 = new XMLHttpRequest();
  if ((month == null) || (year == null) || (day == null)) {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/kalendar.php?pmonth=" + pmonth + "&pyear=" + pyear + "&pday=" + pday, true);
  } else {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/kalendar.php?month=" + month + "&year=" + year + "&day=" + day + "&pmonth=" + pmonth + "&pyear=" + pyear + "&pday=" + pday, true);
  }
  xmlhttp1.send(document);  
  xmlhttp1.onreadystatechange = function() {
    if(this.readyState == 4) {  
      document.getElementById(tagName).innerHTML = xmlhttp1.responseText;      
      if (hide == true) {        
        document.getElementById("select_datum_vyber").style.visibility = "hidden";
        setDatum(day, month, year);
      } else {
        document.getElementById("select_datum_vyber").style.visibility = "visible";
        setDatum(pday, pmonth, pyear);
      }
      document.getElementById("a_select_datum").disabled = false;
    }
  }
}       

function setDatum(day, month, year) {
  document.getElementById("div_kalendar").d = day; 
  document.getElementById("div_kalendar").m = month; 
  document.getElementById("div_kalendar").y = year; 
  document.getElementById("a_select_datum").innerHTML = day + ". " + mesice[month - 1] + " " + year;
}

/*JRData.prototype.OnAttrModified = function(e, tag, location, packet) {
  if (window.event.type == "propertychange") {
    if (window.event.propertyName == "style.visibility") {
      if (tag.id == "content_1") {
        this.loadData(location, packet);
      }
    }
  }
}*/

JRData.prototype.onLinkaChange = function(location, packet) {
  if (this.loaded) {
    this.disable_all_loaded_element();
    this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
  }
}

JRData.prototype.onSmerChange = function(location, packet) {
  if (this.loaded) {  
    this.disable_all_loaded_element();    
    this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);
  }
}

JRData.prototype.disable_all_loaded_element = function() {
  document.getElementById(this.tagLinky).disabled = "disabled";
  document.getElementById(this.tagSmery).disabled = "disabled";
  document.getElementById(this.tagTrasy).disabled = "disabled"; 
}

JRData.prototype.enable_all_loaded_element = function() {
  document.getElementById(this.tagLinky).disabled = "";
  document.getElementById(this.tagSmery).disabled = ""; 
  document.getElementById(this.tagTrasy).disabled = "";   
}

function komplexJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 0, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    0, null, null, null, 0, true);
}

function denJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 1, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    0, null, null, null, 0, true);
}

function sdruzJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 0, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    1, null, null, null, 0, true);  
}

function getJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, deleteonshow) {
  sel = sel || -1;
  deleteonshow = deleteonshow || false;
  if (deleteonshow == true) {
    document.getElementById("divJR").innerHTML = "";
    document.getElementById("divJR").style.visibility = 'hidden';
  }
  //  disable_all_loaded_element();
//      nn = document.getElementById('pp');
  /*nn.innerHTML = "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y;*/
//  nn.innerHTML = sel + "|";
  xmlhttp1=new XMLHttpRequest();
  if ((x == null) || (y == null) || (sloupec == null)) {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel, true);
  } else {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y, false);
  }
  xmlhttp1.send(document);  
  xmlhttp1.onreadystatechange = function() {
    if(this.readyState == 4) {  
      if (sel < 0) {
        document.getElementById("divJR").innerHTML = xmlhttp1.responseText;
      } else {
        document.getElementById("divJR").innerHTML = /*document.getElementById("divJR").innerHTML + */xmlhttp1.responseText;
//        document.write(xmlhttp1.responseText);
      }
//      document.getElementById("divJR").innerHTML = xmlhttp1.responseText; 
      if (moveit) {
        if (deleteonshow == true) {
          newLeft = (screen.width - document.getElementById("divJR").offsetWidth) / 2;
          if (newLeft < 0) {
            newLeft = 0;
          }
          document.getElementById("divJR").style.left = /*document.getElementById("page_container").offsetLeft*/newLeft + "px";
        }
      }
      document.getElementById("divJR").style.visibility = 'visible';
      if (moveit) {
        document.getElementById('movediv').onmousedown = function mouseDown(ev) {
          moving = true;
          ev = ev || window.event;
          var mousePos = mouseCoords(ev);
          mx = mousePos.x;
          my = mousePos.y;
          moving = true;
          document.onselectstart = function(ev) {
            return false;
          };
        }
      }

      if (moveit) {
        document.onmouseup = function() {
          //        document.getElementById('tablejr').innerHTML = inJR;
          document.getElementById('tablejr').style.visibility = 'visible';
          //        document.write('up');
          moving = false;
          moving = false;
          document.onselectstart = null;
        };
      }

    //      enable_all_loaded_element();
    }
  }
}

JRData.prototype.getLinkyList = function(location, packet, comboName) {   
  var self = this;
  xmlhttplinky = new XMLHttpRequest();
  xmlhttplinky.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListLinky.php?location=" + location + "&packet=" + packet);
  xmlhttplinky.send(document); 
  xmlhttplinky.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttplinky.responseText)).toString(); 
      a = a.split(',');  
      while(a[0]) { 
        pp.push(a.splice(0,2)); 
      } 
      nc = document.getElementById(comboName);
      nc.options.length = 0;
      for (var i in pp) { 
        nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
      }       
      self.getSmeryList(document.getElementById(self.tagLinky).value, location, packet, self.tagSmery);
      xmlhttplinky = null;
    }
  }
}

JRData.prototype.getSmeryList = function(linka, location, packet, comboName) {
  var self = this;
  xmlhttpsmery = new XMLHttpRequest();
  xmlhttpsmery.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListSmery.php?linka=" + linka + "&location=" + location + "&packet=" + packet);
  xmlhttpsmery.send(document);  
  xmlhttpsmery.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttpsmery.responseText)).toString();  
      a = a.split(',');  
      while(a[0]) { 
        pp.push(a.splice(0,2)); 
      } 
      nc = document.getElementById(comboName);
      nc.options.length = 0;
      for (var i in pp) { 
        pp[i][1] = pp[i][1].replace(/[|]/g, ",");
        pp[i][0] = pp[i][0].replace(/[|]/g, ",");        
        nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
      }
      self.getTrasyList(document.getElementById(self.tagLinky).value, document.getElementById(self.tagSmery).value, location, packet, self.tagTrasy);    
      xmlhttpsmery = null;
    } 
  }
}
  
JRData.prototype.getTrasyList = function(linka, smer, location, packet, comboName) {
  var self = this;
  xmlhttptrasy = new XMLHttpRequest();
  xmlhttptrasy.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListTrasy.php?linka=" + linka + "&location=" + location + "&packet=" + packet + "&smer=" + smer);
  xmlhttptrasy.send(document);  
  xmlhttptrasy.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttptrasy.responseText)).toString(); 
      a = a.split(',');  
      while(a[0]) { 
        pp.push(a.splice(0,3)); 
      } 
      nc = document.getElementById(comboName);
      nc.options.length = 0;
      var select_index = -1;
      for (var i in pp) { 
        pp[i][2] = pp[i][2].replace(/[|]/g, ",");        
        pp[i][1] = pp[i][1].replace(/[|]/g, ",");
        pp[i][0] = pp[i][0].replace(/[|]/g, ",");        
        nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
        nc.options[i].disabled = ((pp[i][2] == 1) ? false: true);
        if ((select_index == -1) && nc.options[i].disabled == false) {
          select_index = i;
        }        
      }
      nc.selectedIndex = select_index;
      xmlhttptrasy = null;
      self.enable_all_loaded_element(); 
      self.loaded = true;
    }
  }  
}

function getKalendar(tagName, month, year, day, pmonth, pyear, pday, hide) {
  document.getElementById("a_select_datum").disabled = true;
  xmlhttp1=new XMLHttpRequest();
  if ((month == null) || (year == null) || (day == null)) {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/kalendar.php?pmonth=" + pmonth + "&pyear=" + pyear + "&pday=" + pday, true);
  } else {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/kalendar.php?month=" + month + "&year=" + year + "&day=" + day + "&pmonth=" + pmonth + "&pyear=" + pyear + "&pday=" + pday, true);
  }
  xmlhttp1.send(document);  
  xmlhttp1.onreadystatechange = function() {
    if(this.readyState == 4) {  
      document.getElementById(tagName).innerHTML = xmlhttp1.responseText;      
      if (hide == true) {        
        document.getElementById("select_datum_vyber").style.visibility = "hidden";
        setDatum(day, month, year);
      } else {
        document.getElementById("select_datum_vyber").style.visibility = "visible";
        setDatum(pday, pmonth, pyear);
      }
      document.getElementById("a_select_datum").disabled = false;
    }
  }
} 

function setKalendar(month, year, day, pmonth, pyear, pday, hide) {
  getKalendar("select_datum", month, year, day, pmonth, pyear, pday, hide);
  if (hide == true) {
    if (document.getElementById("select_datum_vyber") != null) {
      document.getElementById("select_datum_vyber").style.visibility = "hidden";
    }
  }
}

function kalendarshow() {
  if (document.getElementById("select_datum_vyber") != null) {
    if (document.getElementById("select_datum_vyber").style.visibility == "hidden") {
      getKalendar("select_datum", document.getElementById("div_kalendar").m, document.getElementById("div_kalendar").y, document.getElementById("div_kalendar").d, document.getElementById("div_kalendar").m, document.getElementById("div_kalendar").y, document.getElementById("div_kalendar").d, false);
    } else {
      document.getElementById("select_datum_vyber").style.visibility = "hidden";
    }
  }
}

function kalendarhide() {
  if (document.getElementById("div_kalendar").active == false) {
    document.getElementById("select_datum_vyber").style.visibility = "hidden";
  }
}

function kalendin() {
  document.getElementById("div_kalendar").active = true;
}

function kalendout() {
  document.getElementById("div_kalendar").active = false;
} 
