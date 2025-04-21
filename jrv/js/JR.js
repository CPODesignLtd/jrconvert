var mesice = new Array("ledna", "února", "bøezna", "dubna", "kvìtna", "èervna", "èervence", "srpna", "záøí", "øíjna", "listopadu", "prosince");

function initialize(location, packet) {
  document.getElementById("content_1").loaded = false;  
  document.getElementById("content_1").style.visibility = 'visible';
  loadData(location, packet);
}

function loadData(location, packet) {
  if (document.getElementById("content_1").loaded == false) {
    getLinkyList(location, packet, "select_linka");
    var dnes = new Date();
    var den = dnes.getDate();
    var mesic = dnes.getMonth() + 1;
    var rok = dnes.getFullYear();
    getKalendar("select_datum", mesic, rok, den, mesic, rok, den, true);
    document.getElementById("content_1").loaded = true;
  }
}        

function setDatum(day, month, year) {
  document.getElementById("div_kalendar").d = day; 
  document.getElementById("div_kalendar").m = month; 
  document.getElementById("div_kalendar").y = year; 
  document.getElementById("a_select_datum").innerHTML = day + ". " + mesice[month - 1] + " " + year;
}

function OnAttrModified(event, tag, location, packet) {
  if (event.type == "propertychange") {
    if (event.propertyName == "style.visibility") {
      if (tag.id == "content_1") {
        loadData(location, packet);
      }
    }
  }
}

function onLinkaChange(location, packet) {
  getSmeryList(document.getElementById("select_linka").value, location, packet, "select_smer");
}

function onSmerChange(location, packet) {
  getTrasyList(document.getElementById("select_linka").value, location, packet, document.getElementById("select_smer").value, "select_trasa");
}

function disable_all_loaded_element() {
  document.getElementById("select_linka").disabled = "disabled";
  document.getElementById("select_smer").disabled = "disabled";
  document.getElementById("select_trasa").disabled = "disabled"; 
  document.getElementById("button_komplex_JR").disabled = "disabled"; 
  document.getElementById("button_den_JR").disabled = "disabled"; 
  document.getElementById("button_sdruz_JR").disabled = "disabled"; 
}

function enable_all_loaded_element() {
  document.getElementById("select_linka").disabled = "";
  document.getElementById("select_smer").disabled = ""; 
  document.getElementById("select_trasa").disabled = "";   
  document.getElementById("button_komplex_JR").disabled = "";  
  document.getElementById("button_den_JR").disabled = ""; 
  document.getElementById("button_sdruz_JR").disabled = "";  
}

function komplexJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 0, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    0, null, null, null, true);
}

function denJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 1, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    0, null, null, null, true);
}

function sdruzJR(location, packet) {
  getJR(document.getElementById("select_linka").value, document.getElementById("select_smer").value, document.getElementById("select_trasa").value, 
    location, packet, 0, document.getElementById("div_kalendar").d + "_" + document.getElementById("div_kalendar").m + "_" + document.getElementById("div_kalendar").y, 
    1, null, null, null, true);  
}

function getJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, deleteonshow) {
  deleteonshow = deleteonshow || false;
  if (deleteonshow == true) {
    document.getElementById("divJR").innerHTML = "";
  }
  disable_all_loaded_element();
/*  nn = document.createElement("a");
  nn.innerHTML = "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y;
  document.body.appendChild(nn);*/
  xmlhttp1=new XMLHttpRequest();
  if ((x == null) || (y == null) || (sloupec == null)) {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR, true);
  } else {
    xmlhttp1.open("GET", "http://www.mhdspoje.cz/jrw50/php/loadJR.php?linka=" + linka + 
      "&smer=" + smer + "&tarif=" + tarif + "&location=" + location + 
      "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y, true);
  }
  xmlhttp1.send(document);  
  xmlhttp1.onreadystatechange = function() {
    if(this.readyState == 4) {  
      document.getElementById("divJR").innerHTML = xmlhttp1.responseText;
      enable_all_loaded_element();
    }
  }
}

function getLinkyList(location, packet, comboName) {   
  disable_all_loaded_element();
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListLinky.php?location=" + location + "&packet=" + packet);
  xmlhttp.send(document);  
  xmlhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttp.responseText)).toString(); 
 
      a = a.split(',');  
 
      while(a[0]) { 
        pp.push(a.splice(0,2)); 
      } 

      nc = document.getElementById(comboName);
      nc.options.length = 0;
      for (var i in pp) { 
        nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
      }
      getSmeryList(document.getElementById("select_linka").value, location, packet, "select_smer");
    //      enable_all_loaded_element();
    }
  }
}

function getSmeryList(linka, location, packet, comboName) {
  disable_all_loaded_element();
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListSmery.php?linka=" + linka + "&location=" + location + "&packet=" + packet);
  xmlhttp.send(document);  
  xmlhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttp.responseText)).toString(); 
 
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
      getTrasyList(document.getElementById("select_linka").value, location, packet, document.getElementById("select_smer").value, "select_trasa");
    //      enable_all_loaded_element();      
    } 
  }
}
  
function getTrasyList(linka, location, packet, smer, comboName) {
  disable_all_loaded_element();
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET", "http://www.mhdspoje.cz/jrw50/php/ListTrasy.php?linka=" + linka + "&location=" + location + "&packet=" + packet + "&smer=" + smer);
  xmlhttp.send(document);  
  xmlhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      var pp = [];
      var a = (eval(xmlhttp.responseText)).toString(); 
 
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
      enable_all_loaded_element();       
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


