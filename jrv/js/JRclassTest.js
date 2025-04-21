var mesiceW1250 = new Array("ledna", "˙nora", "b¯ezna", "dubna", "kvÏtna", "Ëervna", "Ëervence", "srpna", "z·¯Ì", "¯Ìjna", "listopadu", "prosince");
var mesiceUTF = new Array("ledna", "√∫nora", "b≈ôezna", "dubna", "kvƒõtna", "ƒçervna", "ƒçervence", "srpna", "z√°≈ô√≠", "≈ô√≠jna", "listopadu", "prosince");

var moving = false;
var closemoving = false;
var mx;
var my;
var inJR = '';
var moveit = false;

var selfobj = null;
var selfkalend = null;

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

function JRKalendar(aDivDatum, aTextDatum, aKalendar) {
  this.self = this;
  this.tagDivDatum = aDivDatum;
  this.tagTextDatum = aTextDatum;
  this.tagKalendar = aKalendar;
  
  this.active = false;

  this.d = null;
  this.m = null;
  this.y = null;
}

JRKalendar.prototype.initialize = function() {
  var self = this;
  dnes = new Date();  
  den = dnes.getDate();
  mesic = dnes.getMonth() + 1;
  rok = dnes.getFullYear();
  this.d = den;
  this.m = mesic;
  this.y = rok;
  if (this.tagKalendar == null) {
    nc = document.createElement("DIV");
    nc.style.marginTop = "5px"; 
    nc.style.visibility = "hidden"; 
    nc.style.position = "absolute";     
    nc.id = "divKalendar";
    this.tagKalendar = nc.id;
    document.getElementById(this.tagDivDatum).appendChild(nc);
  }  
    
  document.getElementById(this.tagDivDatum).onclick = function(e) {
    self.kalendarshow();
    e.stopPropagation();
  }
  
  document.getElementById(this.tagKalendar).onclick = function(e) {
    self.active = true;
    e.stopPropagation();
  }
    
  document.onclick = function(e) {
    self.kalendarhide();
    e.stopPropagation();
  }
  
  this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, true);
}

JRKalendar.prototype.setZIndex = function(aIndex) {
  document.getElementById(this.tagKalendar).style.zIndex = aIndex;
}

JRKalendar.prototype.getKalendarData = function(tagName, day, month, year, pday, pmonth, pyear, hide, data) {
  document.getElementById(tagName).innerHTML = data;      
  if (hide == true) {        
    document.getElementById(tagName).style.visibility = "hidden";
    this.setDatum(day, month, year);
  } else {
    document.getElementById(tagName).style.visibility = "visible";
    this.setDatum(pday, pmonth, pyear);
  }
  document.getElementById(this.tagTextDatum).disabled = false;  
}

JRKalendar.prototype.getKalendar = function(tagName, day, month, year, pday, pmonth, pyear, hide) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();  
  
  document.getElementById(this.tagTextDatum).disabled = true;
  if ((month == null) || (year == null) || (day == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendarJOSN.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData";
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendarJSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData";    
  }
  
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrKalend");
  document.body.appendChild(scriptObj);
}

JRKalendar.prototype.setKalendar = function(day, month, year, pday, pmonth, pyear, hide) {
  this.getKalendar(this.tagKalendar, day, month, year, pday, pmonth, pyear, hide);
  if (hide == true) {
    if (document.getElementById(this.tagKalendar) != null) {
      document.getElementById(this.tagKalendar).style.visibility = "hidden";
    }
  }
}

JRKalendar.prototype.kalendarChange = function(day, tag_mesic, tag_rok, pday, pmonth, pyear) {
  this.setKalendar(day, document.getElementById(tag_mesic).value, document.getElementById(tag_rok).value, pday, pmonth, pyear, false);
}

JRKalendar.prototype.setDatum = function(day, month, year) {
  this.d = day; 
  this.m = month; 
  this.y = year;
  document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
}

JRKalendar.prototype.kalendarshow = function() {
  if (document.getElementById(this.tagKalendar) != null) {
    if (document.getElementById(this.tagKalendar).style.visibility == "hidden") {
      this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, false);
    } else {
      //      if (this.active == true) {
      document.getElementById(this.tagKalendar).style.visibility = "hidden";
    //      }
    }
  }
}
  
JRKalendar.prototype.kalendarhide = function(aActive) {
  //  if (this.active == false) {
  document.getElementById(this.tagKalendar).style.visibility = "hidden";
//  }
}




//-----------------------------------------------------------------------------------------------------------------------------




function JRData(aLocation, aPacket, aLinky, aSmery, aTrasy, aKalendar, aJR) {
  this.self = this;
  selfobj = this;
  selfkalend = aKalendar;
  this.location = aLocation;
  this.packet = aPacket;
  this.tagLinky = aLinky;
  this.tagSmery = aSmery;
  this.tagTrasy = aTrasy;
  this.kalendar = aKalendar;
  this.tagJR = aJR;
  
  this.geocoder = null;
  this.myOptions = null;
  
  this.moveit = false;
  moveit = this.moveit;
  
  if (document.getElementById("page_container") != null) {
    document.writeln(document.getElementById("page_container").offsetLeft);    
    document.getElementById(this.tagJR).style.left = document.getElementById("page_container").offsetLeft + "px";
  }
  
  this.loaded = false;   
}

JRData.prototype.initialize = function() {
  var self = this;
  /*  document.getElementById("content_1").loaded = false;  
  document.getElementById("content_1").style.visibility = 'visible';*/
  
  if (this.moveit) {
    document.getElementById(self.tagJR).style.position = "absolute";
    document.onmousemove = function mouseMove(ev){      
      if (moving == true) {
        self.disableSelection(document.body);
        document.getElementById('tablejr').style.visibility = 'hidden';
        document.body.onselectstart = "return false";
        ev = ev || window.event;
        var mousePos = mouseCoords(ev);
        document.getElementById(self.tagJR).style.left = document.getElementById(self.tagJR).offsetLeft + (mousePos.x - mx) + 'px';
        document.getElementById(self.tagJR).style.top = document.getElementById(self.tagJR).offsetTop + (mousePos.y - my) + 'px';
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
    this.loaded = true;
  }
}

JRData.prototype.disableSelection = function(target){
  if (typeof target.onselectstart!="undefined") //IE route
    target.onselectstart=function(){
      return false
      }
  else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
    target.style.MozUserSelect="none"
  else //All other route (ie: Opera)
    target.onmousedown=function(){
      return false
      }
  target.style.cursor = "default"
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

        var marker = new google.maps.Marker({
          map: map,            
          position: results[index].geometry.location
        });
        map.setCenter(results[index].geometry.location);
      } 
    });  
  }
}

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

JRData.prototype.komplexJR = function(location, packet) {
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value, 
    location, packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, 
    0, null, null, null, 0, true);
}

JRData.prototype.denJR = function(location, packet) {
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value, 
    location, packet, 1, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, 
    0, null, null, null, 0, true);
}

JRData.prototype.sdruzJR = function(location, packet) {
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value, 
    location, packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, 
    1, null, null, null, 0, true);  
}

function getJRData(data) {
  document.getElementById(selfobj.tagJR).style.zIndex=1000;
  document.getElementById(selfobj.tagJR).innerHTML = data;  
  if (moveit) {
    //    if (deleteonshow == true) {
    newLeft = (screen.width - document.getElementById(selfobj.tagJR).offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    document.getElementById(selfobj.tagJR).style.left = /*document.getElementById("page_container").offsetLeft*/newLeft + "px";
  //    }
  }
  document.getElementById(selfobj.tagJR).style.visibility = 'visible';
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

function getJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, deleteonshow) {
  sel = sel || -1;
  deleteonshow = deleteonshow || false;
  if (deleteonshow == true) {
    document.getElementById(selfobj.tagJR).innerHTML = "";
    document.getElementById(selfobj.tagJR).style.visibility = 'hidden';
  }
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if ((x == null) || (y == null) || (sloupec == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON.php?linka=" + linka + 
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
    "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&callback=getJRData";
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON.php?linka=" + linka + 
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
    "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&callback=getJRData";
  }
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrJR");
  document.body.appendChild(scriptObj);
}

JRData.prototype.getLinkyData = function(location, packet, comboName, data) {
  var pp = [];
  var a = (eval(data)).toString(); 
  a = a.split(',');  
  while(a[0]) { 
    pp.push(a.splice(0,2)); 
  } 
  nc = document.getElementById(comboName);
  nc.options.length = 0;
  for (i = 0; i < pp.length; i++) { 
    nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
  }       
  nc = null;
  this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
//    xmlhttplinky = null;
}

JRData.prototype.getLinkyList = function(location, packet, comboName) { 
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListLinkyJSON.php?location=" + location + "&packet=" + packet + "&target=" + comboName +  "&callback=getLinkyData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrLinky");
  document.body.appendChild(scriptObj);
//  document.body.removeChild(scriptObj);  
//  this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);  
}

JRData.prototype.getSmeryData = function(location, packet, comboName, data) {
  var pp = [];
  var a = (eval(data)).toString(); 
  a = a.split(',');  
  while(a[0]) { 
    pp.push(a.splice(0,2)); 
  } 
  nc = document.getElementById(comboName);
  nc.options.length = 0;
  for (i = 0; i < pp.length; i++) { 
    pp[i][1] = pp[i][1].replace(/[|]/g, ",");
    pp[i][0] = pp[i][0].replace(/[|]/g, ","); 
    nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
  }       
  nc = null;
  this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);      
}

JRData.prototype.getSmeryList = function(linka, location, packet, comboName) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListSmeryJSON.php?linka=" + linka + "&location=" + location + "&packet=" + packet + "&target=" + comboName +  "&callback=getSmeryData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSmery");
  document.body.appendChild(scriptObj);
}

JRData.prototype.getTrasyData = function(location, packet, comboName, data) {
  var pp = [];
  var a = (eval(data)).toString(); 
  a = a.split(',');  
  while(a[0]) { 
    pp.push(a.splice(0,3)); 
  } 
  nc = document.getElementById(comboName);
  nc.options.length = 0;
  var select_index = -1;
  for (i = 0; i < pp.length; i++) { 
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
  nc = null;
  this.enable_all_loaded_element();
}

  
JRData.prototype.getTrasyList = function(linka, smer, location, packet, comboName) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListTrasyJSON.php?linka=" + linka + "&smer=" + smer + "&location=" + location + "&packet=" + packet + "&target=" + comboName +  "&callback=getTrasyData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrTrasy");
  document.body.appendChild(scriptObj);
} 