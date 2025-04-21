function TKalendar1(name, aDivDatum, aTextDatum, aKalendar) {
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
    fullUrl = "//www.mhdspoje.cz/jrw50/php/kalendar1JSON.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
  } else {
    fullUrl = "//www.mhdspoje.cz/jrw50/php/kalendar1JSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
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