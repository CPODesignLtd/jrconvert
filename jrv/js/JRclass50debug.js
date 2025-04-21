var mesiceW1250 = new Array("ledna", "˙nora", "b¯ezna", "dubna", "kvÏtna", "Ëervna", "Ëervence", "srpna", "z·¯Ì", "¯Ìjna", "listopadu", "prosince");
var mesiceUTF = new Array("ledna", "√∫nora", "b≈ôezna", "dubna", "kvƒõtna", "ƒçervna", "ƒçervence", "srpna", "z√°≈ô√≠", "≈ô√≠jna", "listopadu", "prosince");

var moving = false;
var closemoving = false;
var mx;
var my;
var inJR = '';
var moveit = false;
var tag = 0;

var selfobj = null;
var selfkalend = null;
var selfkalend1 = null;
var selfpacket = null;
var allkalend = new Array();

var elfade = new Array();

function SetOpa(Opa) {
  elfade.style.opacity = Opa;
/*  el.style.MozOpacity = Opa;
  el.style.KhtmlOpacity = Opa;
  el.style.filter = 'alpha(opacity=' + (Opa * 100) + ');';*/
}

function fadeOut(el) {
  for (i = 0; i <= 1; i += 0.01) {
    setTimeout("SetOpa(" + (1 - i) + ")", i * 3000);
  }
  setTimeout("FadeIn()", (100));
}

function fadeIn(el) {
  elfade = el;
  for (i = 0; i <= 1; i += 0.01) {
    setTimeout("SetOpa(" + i +")", i * 300);
  }
//   setTimeout("fadeOut()", (3000 + 2000));
}

function ScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}

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

function Packet(aID, aodD, aodM, aodY, adoD, adoM, adoY, aplatny) {
  this.ID = parseInt(aID, 10);
  this.odD = parseInt(aodD, 10);
  this.odM = parseInt(aodM, 10);
  this.odY = parseInt(aodY, 10);
  this.doD = parseInt(adoD, 10);
  this.doM = parseInt(adoM, 10);
  this.doY = parseInt(adoY, 10);
  this.platny = parseInt(aplatny, 10);
}

function JRTime(aTime) {
  this.self = this;
  this.hh = null;
  this.mm = null;
  this.tagTime = aTime;
}

JRTime.prototype.initialize = function() {
  var currentTime = new Date();
  this.hh = currentTime.getHours();
  this.mm = currentTime.getMinutes();
  if (document.getElementById(this.tagTime) != null) {
    document.getElementById(this.tagTime).value = ((this.hh < 10) ? "0" + this.hh.toString(): this.hh.toString()) + ":" + ((this.mm < 10) ? "0" + this.mm.toString(): this.mm.toString());
  }
}

JRTime.prototype.getHH = function() {
  if (document.getElementById(this.tagTime) != null) {
    strtime = document.getElementById(this.tagTime).value;
    return strtime.substr(0, strtime.search(":"));
  } else {
    return this.hh;
  }
}

JRTime.prototype.getMM = function() {
  if (document.getElementById(this.tagTime) != null) {
    strtime = document.getElementById(this.tagTime).value;
    return strtime.substr(strtime.search(":") + 1, 2);
  } else {
    return this.mm;
  }
}

function JRKalendar(aDivDatum, aTextDatum, aKalendar, other) {
  this.self = this;
  this.tagDivDatum = aDivDatum;
  this.tagTextDatum = aTextDatum;
  if (other == true) {
    this.otherKalendar = true;
  } else {
    this.otherKalendar = false;
  }
  this.tagKalendar = aKalendar;
  this.tagTextDatumChange = null;

  this.active = false;

  this.d = null;
  this.m = null;
  this.y = null;
}

JRKalendar.prototype.initialize = function(aDatum, classname) {
  var self = this;
  if (this.otherKalendar == true) {
    selfkalend1 = this;
  } else {
    selfkalend = this;
  }
  this.classname = classname;
  dnes = new Date();
  den = dnes.getDate();
  mesic = dnes.getMonth() + 1;
  rok = dnes.getFullYear();
  this.d = den;
  this.m = mesic;
  this.y = rok;
  this.ulozeny_datum = false;
  if (aDatum  != null) {
    this.ulozeny_datum = true;
  }
  if (this.ulozeny_datum == true) {
    this.d = (aDatum+'').substr(8, 2);
    this.m = (aDatum+'').substr(5, 2);
    this.y = (aDatum+'').substr(0, 4);
  } else {
    this.d = den;
    this.m = mesic;
    this.y = rok;
  }
  ulozeny_datum = true;
  this.JR = null;

  if (this.tagDivDatum != null) {
    if (this.tagKalendar == null) {
      nc = document.createElement("DIV");
      nc.style.marginTop = "5px";
      nc.style.visibility = "hidden";
      nc.style.position = "absolute";
      if (this.otherKalendar == true) {
        nc.id = "divKalendar1";
        this.tagKalendar = nc.id;
      } else {
        nc.id = "divKalendar" + "_" + this.tagDivDatum;
        this.tagKalendar = nc.id;
      }
      document.getElementById(this.tagDivDatum).appendChild(nc);
    }


    document.getElementById(this.tagDivDatum).onclick = function(e) {
      self.kalendarshow();

      if (e != null) {
        e.stopPropagation();
      } else {

        if (!e) var e = window.event;

        e.cancelBubble = true;
        e.returnValue = false;
      }
    }

    document.getElementById(this.tagKalendar).onclick = function(e) {
      self.active = true;
      if (e != null) {
        e.stopPropagation();
      } else {

        if (!e) var e = window.event;

        e.cancelBubble = true;
        e.returnValue = false;
      }
    }

    document.onclick = function(e) {
      self.kalendarhide();
      if (e != null) {
        e.stopPropagation();
      } else {

        if (!e) var e = window.event;

      /*      e.cancelBubble = true;
      e.returnValue = false;*/
      }
    }


    this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, true);
  }
}

JRKalendar.prototype.settoall = function() {
  allkalend.push(this.tagKalendar);
}

JRKalendar.getDD = function() {
  return this.d;
}

JRKalendar.prototype.setOnChange = function(func) {
  this.tagTextDatumChange = func;
}

JRKalendar.prototype.setZIndex = function(aIndex) {
  document.getElementById(this.tagKalendar).style.zIndex = aIndex;
}

JRKalendar.prototype.getKalendarData = function(tagName, day, month, year, pday, pmonth, pyear, hide, data) {
  document.getElementById(tagName).innerHTML = data;
  if (hide == true) {
    document.getElementById(tagName).style.visibility = "hidden";
    for (i = 0; i < allkalend.length; i++) {
      document.getElementById(allkalend[i]).style.visibility = "hidden";
    }
    this.setDatum(day, month, year);
  } else {
    if (document.getElementById(tagName).style['opacity'] === "") {
      //      document.getElementById(tagName).style.opacity = "0.00";
      document.getElementById(tagName).style.visibility = "visible";
    //      fadeIn(document.getElementById(tagName));
    } else {
      document.getElementById(tagName).style.visibility = "visible";
    }
    for (i = 0; i < allkalend.length; i++) {
      if (allkalend[i] != tagName) {
        document.getElementById(allkalend[i]).style.visibility = "hidden";
      }
    }
    this.setDatum(pday, pmonth, pyear);
  }
  document.getElementById(this.tagTextDatum).disabled = false;
}

JRKalendar.prototype.getKalendar = function(tagName, day, month, year, pday, pmonth, pyear, hide) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if (this.otherKalendar == true) {
    imp = 'selfkalend1';
  } else {
    imp = 'selfkalend';
  }
  if (this.classname != null) {
    imp = this.classname;
  }
  document.getElementById(this.tagTextDatum).disabled = true;
  if ((month == null) || (year == null) || (day == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendarJOSN.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendarJSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
  }

  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrKalend" + "_" + this.tagDivDatum);
  document.body.appendChild(scriptObj);
}

JRKalendar.prototype.setKalendar = function(day, month, year, pday, pmonth, pyear, hide) {
  this.getKalendar(this.tagKalendar, day, month, year, pday, pmonth, pyear, hide);
  if (hide == true) {
    if (document.getElementById(this.tagKalendar) != null) {
      document.getElementById(this.tagKalendar).style.visibility = "hidden";
      for (i = 0; i < allkalend.length; i++) {
        document.getElementById(allkalend[i]).style.visibility = "hidden";
      }
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
  if (this.JR != null) {
    if (this.JR.packet != null) {
      if (this.JR.packet != this.JR.getAktualPacket()) {
        this.JR.packet = this.JR.getAktualPacket();
        if (document.getElementById(this.JR.tagLinky) != null) {
          this.JR.oldLinka = document.getElementById(this.JR.tagLinky).value;
        }
        this.JR.loaded = false;
        this.JR.loadData();
      }
    }
  }

  if (this.tagTextDatumChange != null) {

    //      document.getElementById(this.tagKalendar).style.visibility = "hidden";
    if (this.ulozeny_datum == false) {
      if (this.tagKalendar != null) {
        if (document.getElementById(this.tagKalendar).style.visibility == "hidden") {
          this.tagTextDatumChange(this.JR);
        }
      }
    } else {
      this.ulozeny_datum = false;
    }
  }

  if (this.tagTextDatum != null) {
    if (this.JR != null) {
      switch(this.JR.codepage) {
        case "W1250" :
          document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
          break;
        case "UTF" :
          document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTF[month - 1] + " " + year;
          break;
        default :
          document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
      }
    } else {
      document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
    }
  }
}

JRKalendar.prototype.kalendarshow = function() {
  if (document.getElementById(this.tagKalendar) != null) {
    if (document.getElementById(this.tagKalendar).style.visibility == "hidden") {
      this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, false);
    } else {
      //      if (this.active == true) {
      document.getElementById(this.tagKalendar).style.visibility = "hidden";
      for (i = 0; i < allkalend.length; i++) {
        document.getElementById(allkalend[i]).style.visibility = "hidden";
      }
    //      }
    }
  }
}

JRKalendar.prototype.kalendarhide = function(aActive) {
  //  if (this.active == false) {
  document.getElementById(this.tagKalendar).style.visibility = "hidden";
  for (i = 0; i < allkalend.length; i++) {
    document.getElementById(allkalend[i]).style.visibility = "hidden";
  }
//  }
}

function JRKalendar1(aDivDatum, aTextDatum, aKalendar) {
  this.self = this;
  this.tagDivDatum = aDivDatum;
  this.tagTextDatum = aTextDatum;
  this.tagKalendar = aKalendar;
  this.tagTextDatumChange = null;

  this.active = false;

  this.d = null;
  this.m = null;
  this.y = null;
}

JRKalendar1.prototype.initialize = function(aDatum) {
  var self = this;
  selfkalend1 = this;
  dnes = new Date();
  den = dnes.getDate();
  mesic = dnes.getMonth() + 1;
  rok = dnes.getFullYear();
  this.ulozeny_datum = false;
  if (aDatum  != null) {
    this.ulozeny_datum = true;
  }
  if (this.ulozeny_datum == true) {
    this.d = (aDatum+'').substr(8, 2);
    this.m = (aDatum+'').substr(5, 2);
    this.y = (aDatum+'').substr(0, 4);
  } else {
    this.d = den;
    this.m = mesic;
    this.y = rok;
  }
  ulozeny_datum = true;
  this.JR = null;

  if (this.tagKalendar == null) {
    nc = document.createElement("DIV");
    nc.style.marginTop = "5px";
    nc.style.visibility = "hidden";
    nc.style.position = "absolute";
    nc.id = "divKalendar1";
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

JRKalendar1.getDD = function() {
  return this.d;
}

JRKalendar1.prototype.setOnChange = function(func) {
  this.tagTextDatumChange = func;
}

JRKalendar1.prototype.setZIndex = function(aIndex) {
  document.getElementById(this.tagKalendar).style.zIndex = aIndex;
}

JRKalendar1.prototype.getKalendarData = function(tagName, day, month, year, pday, pmonth, pyear, hide, data) {
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

JRKalendar1.prototype.getKalendar = function(tagName, day, month, year, pday, pmonth, pyear, hide) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  imp = 'selfkalend1';
  document.getElementById(this.tagTextDatum).disabled = true;
  if ((month == null) || (year == null) || (day == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendar1JOSN.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/kalendar1JSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
  }

  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrKalend1");
  document.body.appendChild(scriptObj);
}

JRKalendar1.prototype.setKalendar = function(day, month, year, pday, pmonth, pyear, hide) {
  this.getKalendar(this.tagKalendar, day, month, year, pday, pmonth, pyear, hide);
  if (hide == true) {
    if (document.getElementById(this.tagKalendar) != null) {
      document.getElementById(this.tagKalendar).style.visibility = "hidden";
    }
  }
}

JRKalendar1.prototype.kalendarChange = function(day, tag_mesic, tag_rok, pday, pmonth, pyear) {
  this.setKalendar(day, document.getElementById(tag_mesic).value, document.getElementById(tag_rok).value, pday, pmonth, pyear, false);
}

JRKalendar1.prototype.setDatum = function(day, month, year) {
  this.d = day;
  this.m = month;
  this.y = year;
  if (this.JR != null) {
    if (this.JR.packet != null) {
      if (this.JR.packet != this.JR.getAktualPacket()) {
        this.JR.packet = this.JR.getAktualPacket();
        this.JR.oldLinka = document.getElementById(this.JR.tagLinky).value;
        this.JR.loaded = false;
        this.JR.loadData();
      }
    }
  }

  if (this.tagTextDatumChange != null) {

    //      document.getElementById(this.tagKalendar).style.visibility = "hidden";
    if (this.ulozeny_datum == false) {
      if (document.getElementById(this.tagKalendar).style.visibility == "hidden") {
        this.tagTextDatumChange(this.JR);
      }
    } else {
      this.ulozeny_datum = false;
    }
  }

  if (this.JR != null) {
    switch(this.JR.codepage) {
      case "W1250" :
        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
        break;
      case "UTF" :
        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTF[month - 1] + " " + year;
        break;
      default :
        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
    }
  } else {
    document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
  }
}

JRKalendar1.prototype.kalendarshow = function() {
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

JRKalendar1.prototype.kalendarhide = function(aActive) {
  //  if (this.active == false) {
  document.getElementById(this.tagKalendar).style.visibility = "hidden";
//  }
}


//-----------------------------------------------------------------------------------------------------------------------------




function JRData(aLocation, aPacket, aLinky, aSmery, aTrasy, aKalendar, aJR, aSpojeniOD, aSpojeniDO, aSpojeniCas, otherkalend) {
  this.self = this;
  /*  if (otherkalend == true) {

  } else {*/
  selfobj = this;
  /*  }*/
  if (otherkalend == true) {
    selfkalend1 = aKalendar;
    this.otherKalend = true;
  } else {
    this.otherKalend = false;
    selfkalend = aKalendar;
  }
  if (aKalendar == null) {
    selfkalend = new JRKalendar(null, null, null);
    selfkalend.initialize();
  }
  this.codepage = null;
  this.location = aLocation;
  this.packet = aPacket;
  this.tagLinky = aLinky;
  this.tagLinkyChange = null;
  this.tagSmery = aSmery;
  this.tagSmeryChange = null;
  this.tagTrasy = aTrasy;
  this.tagTrasyChange = null;
  //  this.kalendar = aKalendar;
  this.kalendar = selfkalend;
  this.tagSpojeniOD = aSpojeniOD;
  this.tagSpojeniDO = aSpojeniDO;
  this.tagSpojeniCas = aSpojeniCas;
  this.ZIndexJR = 10000;
  this.ZIndexGeo = 10001;
  this.ZIndexJRSeznam = 9999;
  this.routediv = null;
  this.packetmode = 0;
  if (this.kalendar != null) {
    this.kalendar.JR = this;
  }
  this.execAction = null;
  this.onLoadJR = null;

  var selfcall = this;
  if (aJR == null) {
    aJRn = document.createElement('div');
    aJRn.className = "div_jr";
    aJRn.id = "divJRnew";
    aJRn.style.top = "330px";
    aJRn.style.zIndex = this.ZIndexJR;
    aJRn.onmousedown = function(e) {
      selfcall.changeZIndexJR();
      if (e != null) {
        e.stopPropagation();
      } else {

        if (!e) var e = window.event;

        e.cancelBubble = true;
        e.returnValue = false;
      }
    }
    document.body.appendChild(aJRn);
    aJR = aJRn.id;
  }

  this.tagJR = aJR;

  aSJRn = document.createElement('div');
  aSJRn.className = "div_jr";
  aSJRn.id = "divJRSeznamnew";
  aSJRn.style.top = "370px";
  aSJRn.style.zIndex = this.ZIndexJRSeznam;
  aSJRn.onmousedown = function(e) {
    selfcall.changeZIndexJRSeznam();
    if (e != null) {
      e.stopPropagation();
    } else {

      if (!e) var e = window.event;

      e.cancelBubble = true;
      e.returnValue = false;
    }
  }
  document.body.appendChild(aSJRn);
  this.tagJRSeznam = aSJRn.id;

  this.tagJRSeznamMap = null;

  this.datapackets = new Array();

  this.geocoder = null;
  this.myOptions = null;
  this.myOptionsRoute = null;

  this.moveit = false;
  moveit = this.moveit;

  this.oldLinka = null;
  this.deleteonshow = false;

  if (document.getElementById("page_container") != null) {
    document.writeln(document.getElementById("page_container").offsetLeft);
    document.getElementById(this.tagJR).style.left = document.getElementById("page_container").offsetLeft + "px";
  }

  this.loaded = false;
  this.loadedSpojeniData = false;
}

JRData.prototype.initialize = function(loadJR, loadSpojeni, execA) {
  this.execAction = execA;
  if (loadJR != null) {
    if (loadJR != true) {
      this.loaded = true;
    }
  }
  if (loadSpojeni != null) {
    if (loadSpojeni != true) {
      this.loadedSpojeniData = true;
    }
  }

  var self = this;
  /*  document.getElementById("content_1").loaded = false;
  document.getElementById("content_1").style.visibility = 'visible';*/

  if (this.moveit) {
    document.getElementById(self.tagJR).style.position = "absolute";
    document.getElementById(this.tagJRSeznam).style.position = "absolute";
    document.onmousemove = function mouseMove(ev){

      if (tag == 2) {
        if (moving == true) {
          self.disableSelection(document.body);
          document.getElementById('tablejrSeznam').style.visibility = 'hidden';
          document.body.onselectstart = "return false";
          ev = ev || window.event;
          var mousePos = mouseCoords(ev);
          document.getElementById(self.tagJRSeznam).style.left = document.getElementById(self.tagJRSeznam).offsetLeft + (mousePos.x - mx) + 'px';
          document.getElementById(self.tagJRSeznam).style.top = document.getElementById(self.tagJRSeznam).offsetTop + (mousePos.y - my) + 'px';
          mx = mousePos.x;
          my = mousePos.y;
        }
      }

      if (tag == 1) {
        if (moving == true) {
          self.disableSelection(document.body);
          //        document.getElementById("GeoMap").style.visibility = 'hidden';
          document.body.onselectstart = "return false";
          ev = ev || window.event;
          var mousePos = mouseCoords(ev);
          document.getElementById("divGeo").style.left = document.getElementById("divGeo").offsetLeft + (mousePos.x - mx) + 'px';
          document.getElementById("divGeo").style.top = document.getElementById("divGeo").offsetTop + (mousePos.y - my) + 'px';
          mx = mousePos.x;
          my = mousePos.y;
        }
      }

      if (tag == 0) {
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
  }

  this.loadData();

  if (document.getElementById(this.tagLinky) != null) {
    document.getElementById(this.tagLinky).onchange = function() {
      self.onLinkaChange(self.location, self.packet);
    }
  }
  if (document.getElementById(this.tagSmery) != null) {
    document.getElementById(this.tagSmery).onchange = function() {
      self.onSmerChange(self.location, self.packet);
    }
  }
  if (document.getElementById(this.tagTrasy) != null) {
    document.getElementById(this.tagTrasy).onchange = function() {
      self.onTrasaChange(self.location, self.packet);
    }
  }

  try {
    this.geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(0, 0);
    //    var OverViewOptions = new google.maps.OverviewMapControlOptions();
    //    google.maps.OverviewMapControlOptions.opened = true;
    this.myOptions = {
      zoom: 16,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,//HYBRID,
      overviewMapControl: true,
      OverviewMapControlOptions: {
        opened: true
      }
    }
    this.myOptionsRoute = {
      zoom: 14,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      overviewMapControl: true,
      OverviewMapControlOptions: {
        opened: true
      }
    }
  }
  catch(e) {}

/*  nc1 = document.createElement("a");
  nc = document.getElementById(this.kalendar.tagKalendar);
  var newLeft = 0;
  while ((nc != null)) {
    nc1.innerHTML = nc1.innerHTML + nc.id + "=" + nc.offsetLeft + " - " + nc.style.position + " | ";
    newLeft = newLeft + parseInt(nc.offsetLeft);
    nc = nc.parentNode;
    if (nc == document.body) {
      nc = null;
    }
  }
  nc = document.getElementById(this.kalendar.tagKalendar);
  document.getElementById(this.kalendar.tagDivDatum).removeChild(nc);
  document.body.appendChild(nc);
  nc.style.left = 178 + "px";
  document.body.appendChild(nc1);*/
}

/*JRData.prototype.initialize = function(loadJR, loadSpojeni) {
  if (loadJR != true) {
    this.loaded = true;
  }
  if (loadSpojeni != true) {
    this.loadedSpojeniData = true;
  }
  this.initialize();
}*/

JRData.prototype.loadData = function() {
  //  if (this.loaded == false) {
  this.disable_all_loaded_element();
  this.getPacketList(this.location);
//    this.loaded = true;
//  }
}

JRData.prototype.loadSpojeniData = function() {
  if (this.loadedSpojeniData == false) {
    this.disable_all_tag_spojeni();
    while (this.packet != null) {}
    this.getSpojeniList(this.location, this.tagSpojeniOD, this.tagSpojeniDO);
  }
}

JRData.prototype.setCodePage = function(code) {
  this.codepage = code;
}

JRData.prototype.setPacketmode = function(mode) {
  this.packetmode = mode;
}

JRData.prototype.getAktualPacket = function() {
  res = -1;
  dnes = (this.kalendar.y * 10000 + this.kalendar.m * 100 + this.kalendar.d);
  for(i = 0; i < this.datapackets.length; i++) {
    if (((this.packetmode == 1) || (this.datapackets[i].platny == 1))) {
      dOD = (this.datapackets[i].odY * 10000 + this.datapackets[i].odM * 100 + this.datapackets[i].odD);
      dDO = (this.datapackets[i].doY * 10000 + this.datapackets[i].doM * 100 + this.datapackets[i].doD);

      if ((dOD <= dnes) && (dnes <= dDO)) {
        res = this.datapackets[i].ID;
      }
    }
  }
  //  alert("packet = " + res);
  return res;
}

JRData.prototype.getAktualPacketDatum = function(kalend) {
  res = -1;
  dnes = (kalend.y * 10000 + kalend.m * 100 + kalend.d);
  for(i = 0; i < this.datapackets.length; i++) {
    if (((this.packetmode == 1) || (this.datapackets[i].platny == 1))) {
      dOD = (this.datapackets[i].odY * 10000 + this.datapackets[i].odM * 100 + this.datapackets[i].odD);
      dDO = (this.datapackets[i].doY * 10000 + this.datapackets[i].doM * 100 + this.datapackets[i].doD);

      if ((dOD <= dnes) && (dnes <= dDO)) {
        res = this.datapackets[i].ID;
      }
    }
  }
  //  alert("packet = " + res);
  return res;
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

JRData.prototype.setRouteDiv = function(aRouteDiv) {
  this.routediv = aRouteDiv;
}

JRData.prototype.changeZIndexGeo = function() {
  geo = document.getElementById('divGeo');
  jr = document.getElementById(this.tagJR);
  jrSeznam = document.getElementById(this.tagJRSeznam);
  //  if ((geo != null) && (jr != null) && (jrSeznam != null)) {
  if (geo != null) geo.style.zIndex = this.ZIndexGeo;
  if (jr != null) jr.style.zIndex = this.ZIndexJR;
  if (jrSeznam != null) jrSeznam.style.zIndex = this.ZIndexJRSeznam;
//  }
}

JRData.prototype.changeZIndexJR = function() {
  geo = document.getElementById('divGeo');
  jr = document.getElementById(this.tagJR);
  jrSeznam = document.getElementById(this.tagJRSeznam);
  //  if ((geo != null) && (jr != null) && (jrSeznam != null)) {
  if (jr != null) jr.style.zIndex = this.ZIndexGeo;
  if (geo != null) geo.style.zIndex = this.ZIndexJR;
  if (jrSeznam != null) jrSeznam.style.zIndex = this.ZIndexJRSeznam;
//  }
}

JRData.prototype.changeZIndexJRSeznam = function() {
  geo = document.getElementById('divGeo');
  jr = document.getElementById(this.tagJR);
  jrSeznam = document.getElementById(this.tagJRSeznam);

  if (jrSeznam != null) jrSeznam.style.zIndex = this.ZIndexGeo;
  if (jr != null) jr.style.zIndex = this.ZIndexJR;
  if (geo != null)  geo.style.zIndex = this.ZIndexJRSeznam;

}

JRData.prototype.map = function(address, loca, locb, idzastavky) {
  var self = this;
  aGeoObal = document.getElementById('divGeo');
  if (aGeoObal == null) {
    aGeoObal = document.createElement('div');
    aGeoObal.className = "div_pozadikomplex";
    aGeoObal.style.zIndex = this.ZIndexGeo;
    aGeoObal.onmousedown = function(e) {
      self.changeZIndexGeo();
      e.stopPropagation();
    }
    aGeoObal.id = "divGeo";
    aGeoObal.style.top = "280px";
    aGeoObal.style.width = "500px";
    aGeoObal.style.height = "500px";
    aGeoObal.style.position = "absolute";
    newLeft = (screen.width - aGeoObal.offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    aGeoObal.style.left = newLeft + "px";

    nc = document.createElement('div');
    nc.id = "moveGeo";
    nc.className = "movediv";

    nc1 = document.createElement('img');
    nc1.className = "wclose";
    nc1.style.cssFloat = "right";
    nc1.src = "http://www.mhdspoje.cz/jrw50/image/closebutton.png";
    nc1.onclick = function(e) {
      closeGeo();
      e.stopPropagation();
    }
    nc.appendChild(nc1);
    aGeoObal.appendChild(nc);

    aGeo = document.createElement('div');
    aGeo.id = "GeoMap";
    aGeo.style.width = "100%";
    aGeoObal.appendChild(aGeo);

    aGeoBottom = document.createElement('div');
    aGeoBottom.style.height = '20px';
    aGeoBottom.style.backgroundImage = "url(http://www.mhdspoje.cz/jrw50/image/bottomlista.png)";
    aGeoResize = document.createElement('img');
    aGeoResize.style.cssFloat = "right";
    aGeoResize.src = "http://www.mhdspoje.cz/jrw50/image/resize.png";
    aGeoBottom.appendChild(aGeoResize);
    aGeoObal.appendChild(aGeoBottom);

    document.body.appendChild(aGeoObal);
    aGeo.style.height = (aGeoObal.offsetHeight - nc.offsetHeight - aGeoObal.style.paddingTop - aGeoObal.style.paddingBottom) + "px";

    nc.onmousedown = function mouseDown1(ev) {
      tag = 1;
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

    if (moveit) {
      document.onmouseup = function() {
        moving = false;
        document.onselectstart = null;
      };
    }
  }

  this.changeZIndexGeo();
  aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
  aGeoObal.style.visibility = "visible";

  var map = new google.maps.Map(aGeo, this.myOptions);

  if ((loca != null) && (locb != null)) {
    this.geocoder.geocode( {
      "location": new google.maps.LatLng(loca, locb)
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
        if (index == -1) {
          index = 0;
        }
        map.setCenter(results[0].geometry.location);

        var contentString = '<div id="content" style="height: 200px;">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h1 id="firstHeading" class="firstHeading">' + address + '</h1>'+
        '<div id="seznamMap">'+
        '</div>'+'</div>';
        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        /*            '<script id="myscrSeznamZastavkaJR" type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/php/loadSeznamZastavkaJSON.php?location="' + self.location + "&packet=" + self.packet +  "&idzastavka=" + idzastavky+'">'+*/
        var image = 'http://www.mhdspoje.cz/jrw50/image/station2.png';
        var marker = new google.maps.Marker({
          map: map,
          position: results[index].geometry.location,
          icon: image
        });

        google.maps.event.addListener(marker, 'click', function() {
          self.tagJRSeznamMap = 'seznamMap';
          infowindow.open(map,marker);
        //        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
        });

        google.maps.event.addListener(infowindow, 'domready', function() {
          //        self.tagJRSeznamMap = 'seznamMap';
          //        infowindow.open(map,marker);
          getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
        });

        map.setCenter(results[index].geometry.location);
      }
    });

  }
  else {

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
        if (index == -1) {
          index = 0;
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
    if (this.tagLinkyChange != null) {
      this.tagLinkyChange();
    }
    this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
  }
}

JRData.prototype.onSmerChange = function(location, packet) {
  if (this.loaded) {
    this.disable_all_loaded_element();
    if (this.tagSmeryChange != null) {
      this.tagSmeryChange();
    }
    this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);
  }
}

JRData.prototype.onTrasaChange = function(location, packet) {
  if (this.tagTrasyChange != null) {
    this.tagTrasyChange();
  }
}

JRData.prototype.disable_all_loaded_element = function() {
  if (document.getElementById(this.tagLinky) != null) {
    document.getElementById(this.tagLinky).disabled = "disabled";
  }
  if (document.getElementById(this.tagSmery) != null) {
    document.getElementById(this.tagSmery).disabled = "disabled";
  }
  if (document.getElementById(this.tagTrasy) != null) {
    document.getElementById(this.tagTrasy).disabled = "disabled";
  }
}

function removeClass(element, cls) {
  var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
  element.className = element.className.replace(reg, ' ');
}

JRData.prototype.enable_all_loaded_element = function() {
  if (document.getElementById(this.tagLinky) != null) {
    document.getElementById(this.tagLinky).disabled = "";
    if (document.getElementById(this.tagLinky).parentNode != null) {
      document.getElementById(this.tagLinky).parentNode.disabled = "";
      removeClass(document.getElementById(this.tagLinky).parentNode, 'disabled');
    }
  }
  if (document.getElementById(this.tagSmery) != null) {
    document.getElementById(this.tagSmery).disabled = false;
    if (document.getElementById(this.tagSmery).parentNode != null) {
      document.getElementById(this.tagSmery).parentNode.disabled = "";
      removeClass(document.getElementById(this.tagSmery).parentNode, 'disabled');
    }
  }
  if (document.getElementById(this.tagTrasy) != null) {
    document.getElementById(this.tagTrasy).disabled = false;
    if (document.getElementById(this.tagTrasy).parentNode != null) {
      document.getElementById(this.tagTrasy).parentNode.disabled = "";
      removeClass(document.getElementById(this.tagTrasy).parentNode, 'disabled');
    }
  }
}

JRData.prototype.disable_all_tag_spojeni = function() {
  if (document.getElementById(this.tagSpojeniOD) != null) {
    document.getElementById(this.tagSpojeniOD).disabled = "disabled";
  }
  if (document.getElementById(this.tagSpojeniDO) != null) {
    document.getElementById(this.tagSpojeniDO).disabled = "disabled";
  }
}

JRData.prototype.enable_all_tag_spojeni = function() {
  if (document.getElementById(this.tagSpojeniOD) != null) {
    document.getElementById(this.tagSpojeniOD).disabled = "";
  }
  if (document.getElementById(this.tagSpojeniDO) != null) {
    document.getElementById(this.tagSpojeniDO).disabled = "";
  }
}

JRData.prototype.komplexJR = function(location, packet, showkurz, packets) {
  this.changeZIndexJR();
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
    location, (packet == null) ? this.packet: packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
    0, null, null, null, 0, true, packets, showkurz);
}

JRData.prototype.denJR = function(location, packet, showkurz) {
  this.changeZIndexJR();
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
    location, (packet == null) ? this.packet: packet, 1, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
    0, null, null, null, 0, true, null, showkurz);
}

JRData.prototype.sdruzJR = function(location, packet, showkurz, packets) {
  this.changeZIndexJR();
  getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
    location, (packet == null) ? this.packet: packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
    1, null, null, null, 0, true, packets, showkurz);
}

JRData.prototype.seznamJR = function(location, packet) {
  getSeznamJR(location, ((packet == null) ? this.packet: packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y);
  this.changeZIndexJRSeznam();
}

JRData.prototype.seznamZastavkyJR = function(location, packet) {
  getSeznamZastavkyJR(location, ((packet == null) ? this.packet: packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y);
  this.changeZIndexJRSeznam();
}

JRData.prototype.seznamKurzy = function(location, packet) {
  getSeznamKurzy(location, ((packet == null) ? this.packet: packet), document.getElementById(this.tagLinky).value);
  this.changeZIndexJRSeznam();
}

JRData.prototype.spojeniResult = function(location, packet, hh, mm) {
  getSpojeniResult(location, ((packet == null) ? this.packet: packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, hh, mm, document.getElementById(this.tagSpojeniOD).value, document.getElementById(this.tagSpojeniDO).value);
  this.changeZIndexJRSeznam();
}

JRData.prototype.spojeniResultotherdatum = function(location, packet, hh, mm, kalend) {
  packet = this.getAktualPacketDatum(kalend);
  getSpojeniResult(location, ((packet == null) ? this.packet: packet), kalend.d + "_" + kalend.m + "_" + kalend.y, hh, mm, document.getElementById(this.tagSpojeniOD).value, document.getElementById(this.tagSpojeniDO).value);
  this.changeZIndexJRSeznam();
}

function getJRData(data) {
  /*  document.getElementById(selfobj.tagJR).style.width = "300px";
  document.getElementById(selfobj.tagJR).style.height = "250px";
  document.getElementById(selfobj.tagJR).style.overflow = "auto";*/
  document.getElementById(selfobj.tagJR).innerHTML = data;
  if (moveit) {
    if (selfobj.deleteonshow == true) {
      newLeft = (screen.width - document.getElementById(selfobj.tagJR).offsetWidth) / 2;
      if (newLeft < 0) {
        newLeft = 0;
      }
      document.getElementById(selfobj.tagJR).style.left = newLeft + "px";
    }
  }
  if (moveit) {
    document.getElementById(selfobj.tagJR).style.top = ScrollXY()[1] + 20 + "px";
  }
  document.getElementById(selfobj.tagJR).style.visibility = 'visible';
  if (moveit) {
    document.getElementById('movediv').onmousedown = function mouseDown(ev) {
      tag = 0;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      /*      if (document.getElementById("GeoMap") != null) {
        document.getElementById("GeoMap").style.visibility = 'visible';
      }            */
      }
      //        document.write('up');
      moving = false;
      moving = false;
      document.onselectstart = null;
    };
  }
  if (this.onLoadJR != null) {
     this.onLoadJR();
  }

//      enable_all_loaded_element();
}

function getJRDataalone(data) {
  JRElement = document.getElementById("tagJR");
  if (JRElement == null) {
    JRElement = document.createElement("div");
    document.body.appendChild(JRElement);
  }
  JRElement.innerHTML = data;
}

function printJRData(data) {
  w=window.open("","","toolbar=0, location = 0, menubar = 0, resizable = 1, scrollbars = 1");
  w.document.open();
  w.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
  w.document.write('<meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>');
  w.document.write('<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/menuDecin.css"/>');
  w.document.write('<link rel="stylesheet" type="text/css" href="http://www.mhdspoje.cz/jrw50/css/JRDecin.css"/>');
  w.document.write('<style TYPE="text/css">body {font-family: sans-serif; font-size: 12px;}</style>');
  w.document.write(data);
  w.document.close();
  w.print();
//  document.getElementById(selfobj.tagJR).innerHTML = data;
}

function getVozakData(data) {
  /*  document.getElementById(selfobj.tagJR).style.width = "300px";
  document.getElementById(selfobj.tagJR).style.height = "250px";
  document.getElementById(selfobj.tagJR).style.overflow = "auto";*/
  document.getElementById(selfobj.tagJR).innerHTML = data;
  if (moveit) {
    if (selfobj.deleteonshow == true) {
      newLeft = (screen.width - document.getElementById(selfobj.tagJR).offsetWidth) / 2;
      if (newLeft < 0) {
        newLeft = 0;
      }
      document.getElementById(selfobj.tagJR).style.left = newLeft + "px";
    }
  }
  document.getElementById(selfobj.tagJR).style.top = ScrollXY()[1] + 20 + "px";
  document.getElementById(selfobj.tagJR).style.visibility = 'visible';
  if (moveit) {
    document.getElementById('movediv').onmousedown = function mouseDown(ev) {
      tag = 0;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      /*      if (document.getElementById("GeoMap") != null) {
        document.getElementById("GeoMap").style.visibility = 'visible';
      }            */
      }
      //        document.write('up');
      moving = false;
      moving = false;
      document.onselectstart = null;
    };
  }

//      enable_all_loaded_element();
}

function closeJRSeznam() {
  document.getElementById(selfobj.tagJRSeznam).innerHTML = '';
  moving = false;
}

function closeJR() {
  document.getElementById(selfobj.tagJR).innerHTML = '';
  moving = false;
}

function closeGeo() {
  document.getElementById('divGeo').style.visibility = "hidden";
  moving = false;
}

function getSeznamJRData(data) {
  document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
  selfobj.changeZIndexJR();
  if (moveit) {
    //    if (selfobj.deleteonshow == true) {
    newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";
  //    }
  }
  document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
  document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';

  if (moveit) {
    document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
      tag = 2;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      }
      moving = false;
      document.onselectstart = null;
    };
  }
}

function getSeznamZastavkyJRData(data) {
  document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
  selfobj.changeZIndexJR();
  if (moveit) {
    newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";
  }
  document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
  document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';

  if (moveit) {
    document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
      tag = 2;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      }
      moving = false;
      document.onselectstart = null;
    };
  }
}

function getSeznamZastavkaJRData(data) {
  document.getElementById(selfobj.tagJRSeznamMap).innerHTML = data;
}

function getSeznamKurzyData(data) {
  document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
  selfobj.changeZIndexJR();
  if (moveit) {
    newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";
  }
  document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
  document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';

  if (moveit) {
    document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
      tag = 2;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      }
      moving = false;
      document.onselectstart = null;
    };
  }
}

function getSpojeniResultData(data) {
  //  alert(data);
  document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
  selfobj.changeZIndexJR();
  if (moveit) {
    newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";
  }
  document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
  document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';

  if (moveit) {
    document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
      tag = 2;
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
      if (moving == true) {
        if (document.getElementById('tablejrSeznam') != null) {
          document.getElementById('tablejrSeznam').style.visibility = 'visible';
        }
        if (document.getElementById('tablejr') != null) {
          document.getElementById('tablejr').style.visibility = 'visible';
        }
      }
      moving = false;
      document.onselectstart = null;
    };
  }
}

function getJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow, packets, showkurz) {
  if (showkurz == null) {
    showkurz = 0;
  }
  if (showkurz != 1) {
    showkurz = 0;
  }
  if (packets != null) {}else packets=0;
  sel = sel || -1;
  selfobj.deleteonshow = adeleteonshow || false;
  if (selfobj.deleteonshow == true) {
    document.getElementById(selfobj.tagJR).innerHTML = "";
    document.getElementById(selfobj.tagJR).style.visibility = 'hidden';
  }
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if ((x == null) || (y == null) || (sloupec == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON_1.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&packets=" + packets + "&kurz=" + showkurz + "&callback=getJRData";
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON_1.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&packets=" + packets + "&kurz=" + showkurz + "&callback=getJRData";
  }
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrJR");
  document.body.appendChild(scriptObj);
//  selfobj.changeZIndexJR();
}

function getJRalone(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow, packets) {
  if (packets != null) {}else packets=0;

  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if ((x == null) || (y == null) || (sloupec == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&packets=" + packets + "&callback=getJRDataalone";
  } else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&packets=" + packets + "&callback=getJRDataalone";
  }
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrJR");
  document.body.appendChild(scriptObj);
}

function printJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow) {
  sel = sel || -1;
  /*  selfobj.deleteonshow = adeleteonshow || false;
  if (selfobj.deleteonshow == true) {
    document.getElementById(selfobj.tagJR).innerHTML = "";
    document.getElementById(selfobj.tagJR).style.visibility = 'hidden';
  }*/
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if ((x == null) || (y == null) || (sloupec == null)) {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON_1.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&callback=printJRData&print=1";
  }
  else {
    fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadJRJSON_1.php?linka=" + linka +
    "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
    "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&callback=printJRData&print=1";
  }
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrJR");
  document.body.appendChild(scriptObj);
//  selfobj.changeZIndexJR();
}

function getSeznamJR(location, packet, datum) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadSeznamJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&callback=getSeznamJRData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSeznamJR");
  document.body.appendChild(scriptObj);
}

function getSeznamZastavkyJR(location, packet, datum) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadSeznamZastavkyJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&callback=getSeznamZastavkyJRData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSeznamZastavkyJR");
  document.body.appendChild(scriptObj);
}

function getSeznamZastavkaJR(location, packet, datum, idzastavky) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadSeznamZastavkaJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&idzastavka=" + idzastavky  + "&callback=getSeznamZastavkaJRData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSeznamZastavkaJR");
  document.body.appendChild(scriptObj);
}

function getSeznamKurzy(location, packet, linka) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/LoadVozakSeznamJSON.php?location=" + location + "&packet=" + packet + "&linka=" + linka + "&callback=getSeznamKurzyData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSeznamKurzy");
  document.body.appendChild(scriptObj);
}

function getVozak(location, packet, linka, kurz, kurzname, kodpozn) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/LoadVozakJSON.php?location=" + location + "&packet=" + packet + "&linka=" + linka + "&kurz=" + kurz + "&kurzname=" + kurzname + "&kodpozn=" + kodpozn + "&callback=getVozakData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrJR");
  document.body.appendChild(scriptObj);
}

function getSpojeniResult(location, packet, datum, hh, mm, iOD, iDO) {
  //  var add = false;
  scriptObjold = document.getElementById("myscrSpojeniResult");
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadSpojeniResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&callback=getSpojeniResultData";
  //  fullUrl = "http://www.mhdspoje.cz/jrw50/php/pokus.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&callback=getSpojeniResultData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSpojeniResult");
  if (scriptObjold == null) {
    document.body.appendChild(scriptObj);
  }
  else {
    scriptObjold.parentNode.replaceChild(scriptObj, scriptObjold);
  }
}


JRData.prototype.getPacketData = function(data) {
  var pp = [];
  var a = (eval(data)).toString();
  a = a.split(',');
  while(a[0]) {
    pp.push(a.splice(0,8));
  }
  for (i = 0; i < pp.length; i++) {
    this.datapackets[i] = new Packet(pp[i][0], pp[i][1], pp[i][2], pp[i][3], pp[i][4], pp[i][5], pp[i][6], pp[i][7]);
  }
  if (this.packet == null) {
    this.packet = this.getAktualPacket();
  }
  if (this.execAction == 2) {
    this.seznamJR(this.location, this.packet);
  }
  if (this.execAction == 3) {
    this.seznamZastavkyJR(this.location, this.packet);
  }
  if (this.loaded == false) {
    this.getLinkyList(this.location, this.packet, this.tagLinky);
    this.loaded = true;
  } else {
    this.enable_all_loaded_element();
  }
  if (this.loadedSpojeniData == false) {
    this.disable_all_tag_spojeni();
    this.getSpojeniList(this.location, this.tagSpojeniOD, this.tagSpojeniDO);
    this.loadedSpojeniData = true;
  }

  if (this.kalendar.tagTextDatumChange != null) {
    this.kalendar.tagTextDatumChange(this.kalendar.JR);
  }
}

JRData.prototype.getPacketList = function(location) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListPacketJSON.php?location=" + location + "&callback=getPacketData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrPacket");
  document.body.appendChild(scriptObj);
}

JRData.prototype.getLinkyData = function(location, packet, comboName, data) {
  if (document.getElementById(comboName) != null) {
    var pp = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while(a[0]) {
      pp.push(a.splice(0,2));
    }
    nc = document.getElementById(comboName);
    nc.options.length = 0;
    active = 0;
    index = 0;
    for (i = 0; i < pp.length; i++) {
      pp[i][1] = pp[i][1].replace(/[|]/g, ",")
      nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
      if (pp[i][0].toString() == this.oldLinka) {
        nc.selectedIndex = i;
      }
    }
    nc = null;
    if (this.tagLinkyChange != null) {
      this.tagLinkyChange();
    }
    this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
  }
}

JRData.prototype.getLinkyList = function(location, packet, comboName) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListLinkyJSON.php?location=" + location + "&packet=" + packet + "&datum=" + this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y + "&target=" + comboName +  "&callback=getLinkyData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrLinky");
  document.body.appendChild(scriptObj);
//  document.body.removeChild(scriptObj);
//  this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
}

JRData.prototype.getSmeryData = function(location, packet, comboName, data) {
  if (document.getElementById(comboName) != null) {
    var pp = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while(a[0]) {
      pp.push(a.splice(0,3));
    }
    nc = document.getElementById(comboName);
    nc.options.length = 0;
    ii = 0;
    for (i = 0; i < pp.length; i++) {
      pp[i][2] = pp[i][2].replace(/[|]/g, ",");
      pp[i][1] = pp[i][1].replace(/[|]/g, ",");
      pp[i][0] = pp[i][0].replace(/[|]/g, ",");
      if (pp[i][2] > 0) {
        nc.options[ii] = new Option(pp[i][1].toString(), pp[i][0].toString());
        ii++;
      }
    }
    nc = null;
    if (this.tagSmeryChange != null) {
      this.tagSmeryChange();
    }
    this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);
  }
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
  if (document.getElementById(comboName) != null) {
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
  }
  if (this.tagTrasyChange != null) {
    this.tagTrasyChange();
  }
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

JRData.prototype.getSpojeniListData = function(location, packet, comboNameOD, comboNameDO, data) {
  if ((document.getElementById(comboNameOD) != null) && (document.getElementById(comboNameDO) != null)) {
    var pp = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while(a[0]) {
      pp.push(a.splice(0,2));
    }
    nc = document.getElementById(comboNameOD);
    nc.options.length = 0;
    nc1 = document.getElementById(comboNameDO);
    nc1.options.length = 0;

    //  var select_index = -1;
    for (i = 0; i < pp.length; i++) {
      //    pp[i][2] = pp[i][2].replace(/[|]/g, ",");
      pp[i][1] = pp[i][1].replace(/[|]/g, ",");
      pp[i][0] = pp[i][0].replace(/[|]/g, ",");
      nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
      //    nc.options[i].disabled = ((pp[i][2] == 1) ? false: true);
      nc1.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
    //    nc1.options[i].disabled = ((pp[i][2] == 1) ? false: true);
    /*    if ((select_index == -1) && nc.options[i].disabled == false) {
      select_index = i;
    }        */
    }
    nc.selectedIndex = 0;//select_index;
    nc = null;
    nc1.selectedIndex = 1;//select_index;
    nc1 = null;
  }
  this.enable_all_tag_spojeni();
}


JRData.prototype.getSpojeniList = function(location, comboNameOD, comboNameDO) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/ListSpojeniJSON.php?location=" + location + "&packet=" + this.packet + "&target1=" + comboNameOD + "&target2=" + comboNameDO + "&callback=getSpojeniListData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrSpojeni");
  document.body.appendChild(scriptObj);
}

JRData.prototype.getRouteData = function(data) {
  //  if ((loca != null) && (locb != null)) {
  var pp = [];
  var origin = null;
  var destination = null;
  var waypoints = [];
  var a = (eval(data)).toString();
  a = a.split(',');
  while(a[0]) {
    pp.push(a.splice(0,3));
  }
  for (i = 0; i < pp.length; i++) {
    pp[i][2] = pp[i][2].replace(/[|]/g, ",");
    pp[i][1] = pp[i][1].replace(/[|]/g, ",");
    pp[i][0] = pp[i][0].replace(/[|]/g, ",");
  }

  var self = this;
  aGeoObal = document.getElementById('divGeo');
  if (aGeoObal == null) {
    aGeoObal = document.createElement('div');
    aGeoObal.className = "div_pozadikomplex";
    aGeoObal.style.zIndex = this.ZIndexGeo;
    aGeoObal.onmousedown = function(e) {
      self.changeZIndexGeo();
      e.stopPropagation();
    }
    aGeoObal.id = "divGeo";
    aGeoObal.style.top = "280px";
    aGeoObal.style.width = "500px";
    aGeoObal.style.height = "500px";
    aGeoObal.style.position = "absolute";
    newLeft = (screen.width - aGeoObal.offsetWidth) / 2;
    if (newLeft < 0) {
      newLeft = 0;
    }
    aGeoObal.style.left = newLeft + "px";

    nc = document.createElement('div');
    nc.id = "moveGeo";
    nc.className = "movediv";

    nc1 = document.createElement('img');
    nc1.className = "wclose";
    nc1.style.cssFloat = "right";
    nc1.src = "http://www.mhdspoje.cz/jrw50/image/closebutton.png";
    nc1.onclick = function(e) {
      closeGeo();
      e.stopPropagation();
    }
    nc.appendChild(nc1);
    aGeoObal.appendChild(nc);

    nc.onmousedown = function mouseDown1(ev) {
      tag = 1;
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

  aGeo = document.getElementById(this.routediv);
  if (aGeo == null) {
  aGeo = document.createElement('div');
  aGeo.id = "GeoMap";
  aGeo.style.width = "100%";
  aGeoObal.appendChild(aGeo);

  this.changeZIndexGeo();
  aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
  aGeoObal.style.visibility = "visible";

  document.body.appendChild(aGeoObal);
  try {
  aGeo.style.height = (aGeoObal.offsetHeight - nc.offsetHeight - aGeoObal.style.paddingTop - aGeoObal.style.paddingBottom) + "px";
  } catch(e) { }
  }

  /*  nc.onmousedown = function mouseDown1(ev) {
    tag = 1;
    moving = true;
    ev = ev || window.event;
    var mousePos = mouseCoords(ev);
    mx = mousePos.x;
    my = mousePos.y;
    moving = true;
    document.onselectstart = function(ev) {
      return false;
    };*/


  if (moveit) {
    document.onmouseup = function() {
      moving = false;
      document.onselectstart = null;
    };
  }


/*  this.changeZIndexGeo();
  aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
  aGeoObal.style.visibility = "visible";*/

  var map = new google.maps.Map(aGeo, this.myOptionsRoute);
  //  var directionsDisplay = new google.maps.DirectionsRenderer();
  //  var directionsService = new google.maps.DirectionsService();
  //  directionsDisplay.setMap(map);
  //  directionsDisplay.setPanel(document.getElementById("directionsPanel"));

  for (i = 0; i < pp.length; i++) {
    pp[i][2] = pp[i][2].replace(/[|]/g, ",");
    pp[i][1] = pp[i][1].replace(/[|]/g, ",");
    pp[i][0] = pp[i][0].replace(/[|]/g, ",");

    var lokace = new google.maps.LatLng(pp[i][1], pp[i][2]);
    /*    if ((i >= 0) && (i < 5)) {
      waypoints.push({
        location: new google.maps.LatLng(pp[i][1], pp[i][2]),
        stopover: true
      });
    }*/

    var contentString = '<div id="content" style="height: 200px;">'+
    '<div id="siteNotice">'+
    '</div>'+
    '<h1 id="firstHeading" class="firstHeading">' + pp[i][0] + '</h1>'+
    '<div id="seznamMap">'+
    '</div>'+'</div>';
    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });

    var marker = new google.maps.Marker({
      position: lokace,
      map: map,
      //      icon: "http://maps.google.com/mapfiles/marker" + String.fromCharCode(i+65) + ".png"
      icon: "http://www.mhdspoje.cz/jrw50/image/mark" + (i + 1) + ".png"
    });

  /*        google.maps.event.addListener(marker, 'click', function() {
          self.tagJRSeznamMap = 'seznamMap';
          infowindow.open(map,marker);
        //        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
        });

        google.maps.event.addListener(infowindow, 'domready', function() {
          //        self.tagJRSeznamMap = 'seznamMap';
          //        infowindow.open(map,marker);
          getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, pp[i][0]);
        });*/

  }

  var lokace = new google.maps.LatLng(pp[0][1], pp[0][2]);
  var origin = new google.maps.LatLng(pp[0][1], pp[0][2]);
  var destination = new google.maps.LatLng(pp[pp.length - 1][1], pp[pp.length - 1][2]);

  getDirection(pp, 0, pp.length - 1, new google.maps.DirectionsService(), new google.maps.DirectionsRenderer({
    preserveViewport: true,
    suppressMarkers: true
  }), map);
  /*  for (i = 0; i < pp.length - 2; i++) {
    var request = {
    origin: new google.maps.LatLng(pp[0][1], pp[0][2]),
    destination: new google.maps.LatLng(pp[4][1], pp[5][2]),
    travelMode: google.maps.DirectionsTravelMode.DRIVING,
    optimizeWaypoints: false,
    avoidHighways: false,
    avoidTolls: false
  };

  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
  }*/

  map.setCenter(lokace);
//  }
}

function getDirection(points, i, endi, dirService, dirDisplay, m) {
  if (i < endi) {
    dirDisplay.setMap(m);
    var request = {
      origin: new google.maps.LatLng(points[i][1], points[i][2]),
      destination: new google.maps.LatLng(points[i + 1][1], points[i + 1][2]),
      travelMode: google.maps.DirectionsTravelMode.DRIVING,
      optimizeWaypoints: false,
      avoidHighways: false,
      avoidTolls: false
    };

    dirService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        dirDisplay.setDirections(response);
        getDirection(points, i+1, endi, new google.maps.DirectionsService(), new google.maps.DirectionsRenderer({
          preserveViewport: true,
          suppressMarkers: true
        }), m);
      }
    });
  }
}

JRData.prototype.getRoute = function(linka, smer, location, packet) {
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/loadRouteJSON.php?linka=" + document.getElementById(this.tagLinky).value + "&smer=" + document.getElementById(this.tagSmery).value + "&location=" + location + "&packet=" + this.packet +  "&callback=getRouteData";
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrRoute");
  document.body.appendChild(scriptObj);
}
