var j;
var JRData;
var Chrono;
var Trasa;
var cas;
var tabulka;
var combo;
var vychozilinka;
var vychozismer;
var vychozitarif;
var Location;
var Linky;
var Zastavky;
var CasPoznamky;
var Poznamky;
var Linky1;
var Kalendar;
var JR;
var activetab  = 1;
var verze;
var lokace;
var zobrazitkurz;
var language = 1;
var movableJR = false;
var rleft = 0;
var rtop = 0;
var rposition = 'absolute';
var packet = 0;
var packetsearch = packet;
var Packer;
var cCalendar = null;
var cCalendar1 = null;

pstoleti = new Array(0, 5, 3, 1);
pmesic = new Array(0, 6, 2, 2, 5, 7, 3, 5, 1, 4, 6, 2, 4);

function isLapYear(rok) {
  if (rok > 2000) {
    rok1 = rok - 2000;
  } else {
    rok1 = 2000 - rok;
  }
  if (rok1 % 4 == 0) {
    return 1;
  } else {
    return 0;
  }
}

function formatHHMM(h, m) {
  sh = h;
  sm = m;
  if (h.toString(10).length == 1) {
    sh = "0" + h;
  }
  if (m.toString(10).length == 1) {
    sm = "0" + m;
  }
  return sh + ":" + sm;
}

function formatDDMMYYY(d, m, y) {
  sd = d;
  sm = m;
  sy = y;
  if (d.toString(10).length == 1) {
    sd = "0" + d;
  }
  if (m.toString(10).length == 1) {
    sm = "0" + m;
  }
  return sd + "." + sm + "." + sy;
}

_JR = function(clinky, ctarif, sm) {
  this.JRtab = new Array();
  this.trasa = new Array();
  this.chrono = null;
  this.c_tarif = ctarif;
  this.c_linky = clinky;
  this.smer = sm;
  this.activeZastavka = null;
  this.numCol = 0;
  for(i = 0; i < 24; i++) {
    this.JRtab.push(null);
  }
}

_JR.prototype.reset = function(ctarif, sm, k) {
  this.JRtab = new Array();
  this.trasa = new Array();
  this.chrono = null;
  this.c_tarif = ctarif;
  this.smer = sm;
  this.kod = k;
  this.activeZastavka = null;
  this.numCol = 0;
}

_JR.prototype.addColumn = function(row, col) {
  if (this.JRtab[row] == null) {
    newrow = new Array();
    newrow.push(col);
    this.JRtab[row] = newrow;
    if (this.JRtab[row].length >= this.numCol) {
      this.numCol = this.JRtab[row].length;
    }
  } else {
    this.JRtab[row].push(col);
    if (this.JRtab[row].length >= this.numCol) {
      this.numCol = this.JRtab[row].length;
    }
  }
}

_JR.prototype.addTrasa = function(czastavky, tarif, nazev, A1t, A2t, B1t, B2t, poznamky) {
  veta = new Array();
  veta[0] = czastavky;
  veta[1] = tarif;
  veta[2] = nazev;
  veta[3] = false;
  veta[4] = poznamky;
  veta[5] = A1t;
  veta[6] = A2t;
  veta[7] = B1t;
  veta[8] = B2t;
  //    document.write(this.chrono[]);
  for(i = 1; i < this.chrono.length; i++) {
    if (this.chrono[i][this.trasa.length] != null) {
      if (this.chrono[i][this.trasa.length].doba_jizdy != -1) {
        veta[3] = true;
      }
    }
  }
  this.trasa.push(veta);
  if (this.c_tarif == tarif) {
    this.activeZastavka = this.trasa.length - 1;
  }
}

_JR.prototype.TrasaDisable = function() {
  if (this.trasa.length != 0) {
    //        if (this.smer == 0) {
    this.trasa[this.trasa.length - 1][3] = false;
  //        }
  //        if (this.smer == 1) {
  //            this.trasa[0][3] = false;
  //        }
  }
}

_JR.prototype.resetChrono = function(pCH) {
  if (pCH == null) {
    pCH = 0;
  }
  this.chrono = new Array(pCH + 1);
  for(i = 0; i < (pCH + 1); i++) {
    this.chrono[i] = new Array();
  }
}

_JR.prototype.addChronoItem = function(c_chrono, c_tarif, c_zastavky, doba_jizdy, doba_pocatek) {
  this.chrono[c_chrono].push(new _ChronoItem(c_tarif, c_zastavky, doba_jizdy, doba_pocatek));
}

JRColumn = function() {
  this.odjezd = null;
  this.pocatek_HH = null;
  this.pocatek_MM = null;
  this.chrono = null;
  this.kurz = null;
  this.poznamky = new Array();
}

JRColumn.prototype.existPoznamka = function(poznamka) {
  exist = false;
  for(i = 0; i < this.poznamky.length; i++) {
    if (this.poznamky[i] == poznamka) {
      exist = true;
      break;
    }
  }
  return exist;
}

JRColumn.prototype.addPoznamka = function(poznamka) {
  if (this.existPoznamka(poznamka) == false) {
    this.poznamky.push(poznamka);
  }
}

JRColumn.prototype.setOdjezd = function(cas, h, m, kurz) {
  if (cas.toString(10).length == 1) {
    cas = '0' + cas;
  }
  this.odjezd = cas.toString();
  this.pocatek_HH = h;
  this.pocatek_MM = m;
  this.kurz = kurz;
}

JRColumn.prototype.setChrono = function(ch) {
  this.chrono = ch;
}

_Chrono = function(pCH) {
  this.chrono = null;
}

_Chrono.prototype.reset = function(pCH) {

  }

_Chrono.prototype.addChronoItem = function(c_chrono, c_tarif, c_zastavky, doba_jizdy, doba_pocatek) {
  //    if (this.chrono[ch] == null) {
  //        this.chrono.push(new Array());
  //    }
  //    this.chrono[this.chrono.length - 1].push(new ChColumn(c_zastavky, doba_jizdy, doba_pocatek));
  this.chrono[c_chrono].push(new _ChronoItem(c_tarif, c_zastavky, doba_jizdy, doba_pocatek));
//    document.write(this.chrono[]);
}

_Chrono.prototype.print = function() {
  for(i = 0; i < Chrono.chrono.length; i++) {
    document.write("--- "+i+". ---");
    for(ii = 0; ii < Chrono.chrono[i].length; ii++) {
      if (Chrono.chrono[i][ii] != null) {
        document.write(ii+".  "+Chrono.chrono[i][ii].c_zastavky+" , "+Chrono.chrono[i][ii].doba_jizdy+" , "+Chrono.chrono[i][ii].doba_pocatek);
      }
    }
  }
}

_ChronoItem = function(_c_tarif, _c_zastavky, _doba_jizdy, _doba_pocatek) {
  this.c_tarif = _c_tarif;
  this.c_zastavky = _c_zastavky;
  this.doba_jizdy = _doba_jizdy;
  this.doba_pocatek = _doba_pocatek;
}

_Linka = function(c, n) {
  this.id = c;
  this.nazev = n;
}

_Linky = function() {
  this.self = this;
  this.linky = new Array();
}

_Linky.prototype.addLinka = function(c, n) {
  addlinka = new _Linka(c, n);
  this.linky.push(addlinka);
}

_Trasa = function() {
  this.trasa = new Array();
  this.chrono = new Array();
  this.c_linky = null;
  this.smer = null;
}

_Trasa.prototype.reset = function(clinky, sm) {
  this.trasa = new Array();
  this.chrono = new Array();
  this.c_linky = clinky;
  this.smer = sm;
}

_Trasa.prototype.addTrasa = function(czastavky, tarif, nazev) {
  veta = new Array();
  veta[0] = czastavky;
  veta[1] = tarif;
  veta[2] = nazev;
  veta[3] = false;
  /*    for(i = 1; i < this.chrono.length; i++) {
        if (this.chrono[i][this.trasa.length].doba_jizdy != -1) {
            veta[3] = true;
        }
    }*/
  this.trasa.push(veta);
}

_Trasa.prototype.resetChrono = function(pCH) {
  this.chrono = new Array(pCH + 1);
  for(i = 0; i < (pCH + 1); i++) {
    this.chrono[i] = new Array();
  }
}

_Trasa.prototype.addChronoItem = function(c_chrono, c_tarif, c_zastavky, doba_jizdy, doba_pocatek) {
  this.chrono[c_chrono].push(new _ChronoItem(c_tarif, c_zastavky, doba_jizdy, doba_pocatek));
}

function vypisJR() {
  for(i = 0; i < JR.JRtab.length; i++) {
    if (JR.JRtab[i] == null) {
      document.write(i+'.');
      document.write('<BR>');
    } else {
      document.write(i+'.   ');
      for(ii = 0; ii < JR.JRtab[i].length; ii++) {
        document.write(JR.JRtab[i][ii].odjezd+'( ');
        for(iii = 0; iii < JR.JRtab[i][ii].poznamky.length; iii++) {
          document.write(JR.JRtab[i][ii].poznamky[iii]+' , ');
        }
        document.write(') , ');
      }
      document.write('<BR>');
    }
  }
  document.write('<BR>');
  for(i = 0; i < JR.trasa.length; i++) {
    document.write(JR.trasa[i]);
      document.write("<BR>");
  }
}

function vypis1() {
/*    for(i = 0; i < Linky.linky.length; i++) {
        document.write(Linky.linky[i].nazev);
    }*/
/*    document.write("size of Zastavky1 = " + Zastavky1.zastavky.size()+"<br>");
    for(i = 0; i < Zastavky1.zastavky.size(); i++) {
        document.write(Zastavky1.zastavky.elementAt(i).nazevZastavky);
        document.write("<br>");
    }
    document.write('<br>');
    document.write("size of Linky1 = " + Linky1.linky.size()+"<br>");
    for(i = 0; i < Linky1.linky.size(); i++) {
        document.write(Linky1.linky.elementAt(i).idLinky+" : "+Linky1.linky.elementAt(i).nazevLinky);
        document.write("<br>");
        for(ii = 0; ii < Linky1.linky.elementAt(i).trasa.zastavka.size(); ii++) {
            document.write("    "+Linky1.linky.elementAt(i).trasa.zastavka.elementAt(ii).tarif+". - "+Linky1.linky.elementAt(i).trasa.zastavka.elementAt(ii).zastavka.nazevZastavky)
            document.write("<br>");
        }
    }*/
}

function vypis() {
  //    document.write(Chrono.chrono.length);
  for(i = 0; i < Chrono.chrono.length; i++) {
    document.write("--- "+i+". ---");
    for(ii = 0; ii < Chrono.chrono[i].length; ii++) {
      if (Chrono.chrono[i][ii] != null) {
        document.write(ii+".  "+Chrono.chrono[i][ii].c_zastavky+" , "+Chrono.chrono[i][ii].doba_jizdy+" , "+Chrono.chrono[i][ii].doba_pocatek);
      }
    }
  }
}

function loadDataOld() {
  comboLinky = new JRCombo("cLinky", listLinkyClick);
  for(i = 0; i < Linky1.linky.size(); i++) {
    comboLinky.addItem(Linky1.linky.elementAt(i).idLinky, Linky1.linky.elementAt(i).nazevLinky);
  }
  comboLinky.createList();
  comboLinky.activeitem = 0;
  comboLinky.edit.value = comboLinky.items[comboLinky.activeitem][1];
  comboLinky.edit.id_value = comboLinky.items[comboLinky.activeitem][0];
  j.addObj(0, comboLinky);



/*            comboSmer = new JRCombo("cSmer", listSmerClick);
            comboSmer.addItem(0, Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.elementAt(Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.size() - 1).zastavka.nazevZastavky);
            comboSmer.addItem(1, Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.elementAt(0).zastavka.nazevZastavky);
            comboSmer.createList();
            comboSmer.activeitem = 0;
            comboSmer.edit.value = comboSmer.items[comboSmer.activeitem][1];
            comboSmer.edit.id_value = comboSmer.items[comboSmer.activeitem][0];
            j.addObj(0, comboSmer);*/



/*            comboTrasa = new JRCombo("cTrasa", listTrasaClick);
            comboTrasa.clearList();
            for(i = 0; i < Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.size(); i++) {
                comboTrasa.addItem(Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.elementAt(i).tarif, Linky1.linky.elementAt(comboLinky.activeitem).trasa.zastavka.elementAt(i).zastavka.nazevZastavky);
            }
            comboTrasa.createList();
            comboTrasa.activeitem = 0;
            comboTrasa.edit.value = comboTrasa.items[comboTrasa.activeitem][1];
            comboTrasa.edit.id_value = comboTrasa.items[comboTrasa.activeitem][0];
            j.addObj(0, comboTrasa);

            j.showObj(0);*/
}

function loadLocation(id) {
  var targetCombo = 'combolocation';
  document.getElementById(targetCombo).options.length = 0;
  for (var i = 0;i < Location.locations.size(); i++) {
//    document.getElementById(targetCombo).options[i] = new Option("&nbsp&nbsp&nbsp "+Location.locations.elementAt(i).podnik.toString(), Location.locations.elementAt(i).idLocation.toString());
    document.getElementById(targetCombo).options[i] = new Option(Location.locations.elementAt(i).podnik.toString(), Location.locations.elementAt(i).idLocation.toString());
//    document.getElementById(targetCombo).options[i].title = "http://www.mhdspoje.cz/jrw20/png/"+Location.locations.elementAt(i).icon.toString();
    }
  document.getElementById(targetCombo).selectedIndex = id;
  if (document.getElementById(targetCombo).refresh != undefined) {
    document.getElementById(targetCombo).refresh();
  }
}

function loadLinky() {
  if (document.getElementById("divcombolinky") != null) {
    var targetCombo = 'combolinky';
    //    <select name="combolinky" id="combolinky" style="width: 100%; background-color: #f2f2f2; border-color: #c3c3c3;" onchange="loadSmer(this.selectedIndex);"></select>
    if (document.getElementById(targetCombo) == null) {
      nc = document.createElement("select");
      nc.name = "combolinky";
      nc.id = "combolinky";
      nc.style.border = "0px none";
      nc.onchange = function() {
        loadSmer(this.selectedIndex);
      };
      //loadSmer(this.selectedIndex);
      nc.style.width = "100%";//document.getElementById("divcombolinky").style.width;
      nc.style.backgroundColor = "#f2f2f2";
      nc.style.borderColor = "#c3c3c3";
      nc.style.styleFloat = "none";
      ncdiv = document.createElement("div");
      ncdiv.style.padding = "2px 2px 2px 0px";
      ncdiv.style.border = "1px solid #c3c3c3";
      ncdiv.style.backgroundColor = "#f2f2f2";
//      ncdiv.setAttribute("style","float: none");
      ncdiv.style.offsetWidth = document.getElementById("divcombolinky").style.width;
      ncdiv.appendChild(nc);
      document.getElementById("divcombolinky").appendChild(ncdiv);
    } else {
      nc = document.getElementById(targetCombo);
    }
    nc /*document.getElementById(targetCombo)*/.options.length = 0;
    for (var i = 0;i < Linky.linky.size(); i++) {
      nc/*document.getElementById(targetCombo)*/.options[i] = new Option(/*"&nbsp&nbsp&nbsp "+*/Linky.linky.elementAt(i).nazevLinky.toString(), Linky.linky.elementAt(i).idLinky.toString());
      //        alert(document.getElementById(targetCombo).options[i].value);
      if (Linky.linky.elementAt(i).doprava == 'A') {
        nc/*document.getElementById(targetCombo)*/.options[i].title = "//www.mhdspoje.cz/jrw20/png/tra19.PNG";
      }
      if (Linky.linky.elementAt(i).doprava == 'T') {
        nc/*document.getElementById(targetCombo)*/.options[i].title = "//www.mhdspoje.cz/jrw20/png/bus19.PNG";
      }
      if (Linky.linky.elementAt(i).doprava == 'O') {
        nc/*document.getElementById(targetCombo)*/.options[i].title = "//www.mhdspoje.cz/jrw20/png/tro19.PNG";
      }
    }
    //    $("#combolinky").msDropDown();
    nc/*document.getElementById(targetCombo)*/.selectedIndex = 0;
    if (nc/*document.getElementById(targetCombo)*/.refresh != undefined) {
      nc/*document.getElementById(targetCombo)*/.refresh();
    }
  }
}

function loadSmer(index) {
  if (document.getElementById("divcombosmer") != null) {
    //  <select name="combosmer" id="combosmer" style="WIDTH: 250px; margin-left: 20px; margin-bottom: 4px; background-color: #f2f2f2; border-color: #c3c3c3;" onchange="loadTrasa(document.getElementById('combolinky').selectedIndex, this.value/*selectedIndex - 1*/);"></select>
    targetCombo = 'combosmer';
    if (document.getElementById(targetCombo) == null) {
      nc = document.createElement("select");
      nc.name = "combosmer";
      nc.id = "combosmer";
      nc.style.border = "0px none";
      nc.onchange = function() {
        loadTrasa(index, this.selectedIndex);
      };//loadTrasa(/*(document.getElementById('combolinky') == null) ? null: document.getElementById('combolinky').selectedIndex*/index, this.value);
      nc.style.width = "100%";//document.getElementById("divcombosmer").style.width;
      nc.style.backgroundColor = "#f2f2f2";
      nc.style.borderColor = "#c3c3c3";
      ncdiv = document.createElement("div");
      ncdiv.style.padding = "2px 2px 2px 0px";
      ncdiv.style.border = "1px solid #c3c3c3";
      ncdiv.style.backgroundColor = "#f2f2f2";
      ncdiv.style.offsetWidth = document.getElementById("divcombosmer").style.width;
      ncdiv.appendChild(nc);
      document.getElementById("divcombosmer").appendChild(ncdiv);
    } else {
      nc = document.getElementById(targetCombo);
    }

    nc/*document.getElementById(targetCombo)*/.options.length = 0;
//    nc/*document.getElementById(targetCombo)*/.options[0] = new Option(Linky.linky.elementAt(index).trasa.zastavka.elementAt(Linky.linky.elementAt(index).trasa.zastavka.size() - 1).zastavka.nazevZastavky.toString(), 0);
    if (Linky.linky.elementAt(index).smerA == '') {
      nc/*document.getElementById(targetCombo)*/.options[0] = new Option(Linky.linky.elementAt(index).trasa.zastavka.elementAt(Linky.linky.elementAt(index).trasa.zastavka.size() - 1).zastavka.nazevZastavky.toString(), 0);
      nc/*document.getElementById(targetCombo)*/.options[0].title = "";
      nc/*document.getElementById(targetCombo)*/.options[1] = new Option(Linky.linky.elementAt(index).trasa.zastavka.elementAt(0).zastavka.nazevZastavky.toString(), 1);
      nc/*document.getElementById(targetCombo)*/.options[1].title = "";
    } else {
      nc/*document.getElementById(targetCombo)*/.options[0] = new Option(Linky.linky.elementAt(index).smerA.toString(), 0);
      nc/*document.getElementById(targetCombo)*/.options[0].title = "";
      nc/*document.getElementById(targetCombo)*/.options[1] = new Option(Linky.linky.elementAt(index).smerB.toString(), 1);
      nc/*document.getElementById(targetCombo)*/.options[1].title = "";
    }

    nc/*document.getElementById(targetCombo)*/.selectedIndex = 0;
    if (nc/*document.getElementById(targetCombo)*/.refresh != undefined) {
      nc/*document.getElementById(targetCombo)*/.refresh();
    }

    //    loadTrasa(index, document.getElementById(targetCombo).selectedIndex - 1);
    //    alert(document.getElementById(targetCombo).selectedIndex - 1);

    loadTrasa(index,  0);

  /*    document.write("LINKA = "+Linky.linky.elementAt(index).idLinky);
    document.write("<BR>");
    document.write("CHRONO A = "+Linky.linky.elementAt(index).chronoA.size());
    document.write("<BR>");
    for(i = 0; i < Linky.linky.elementAt(index).chronoA.size(); i++) {
        document.write(Linky.linky.elementAt(index).chronoA.elementAt(i).idchrono);
        document.write("<BR>");
    }
    document.write("CHRONO B = "+Linky.linky.elementAt(index).chronoB.size());
    document.write("<BR>");
    for(i = 0; i < Linky.linky.elementAt(index).chronoB.size(); i++) {
        document.write(Linky.linky.elementAt(index).chronoB.elementAt(i).idchrono);
        document.write("<BR>");
    }*/
  }
}

function loadTrasa(indexLinka, indexSmer) {
  if (document.getElementById("divcombotrasa") != null) {
    //    <select name="combotrasa" id="combotrasa" style="WIDTH: 250px; margin-left: 20px; margin-bottom: 4px; background-color: #f2f2f2; border-color: #c3c3c3;"></select>
    targetCombo = 'combotrasa';
    if (document.getElementById(targetCombo) == null) {
      nc = document.createElement("select");
      nc.name = "combotrasa";
      nc.id = "combotrasa";
      nc.style.border = "0px none";
      nc.style.width = "100%";//document.getElementById("divcombotrasa").style.width;
      nc.style.backgroundColor = "#f2f2f2";
      nc.style.borderColor = "#c3c3c3";
      ncdiv = document.createElement("div");
      ncdiv.style.padding = "2px 2px 2px 0px";
      ncdiv.style.border = "1px solid #c3c3c3";
      ncdiv.style.backgroundColor = "#f2f2f2";
      ncdiv.style.offsetWidth = document.getElementById("divcombotrasa").style.width;
      ncdiv.appendChild(nc);
      document.getElementById("divcombotrasa").appendChild(ncdiv);
    } else {
      nc = document.getElementById(targetCombo);
    }

    document.getElementById(targetCombo).options.length = 0;
    if (indexSmer < 0) {
      indexSmer = 0;
    }
    for (i = ((indexSmer != 0) ? 0: Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.size() - 1); ((indexSmer != 0) ? i < Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.size(): i >= 0); ((indexSmer != 0) ? i++: i--)) {
      var ii = ((indexSmer == 0) ? i: Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.size() - 1 - i);
      nc/*document.getElementById(targetCombo)*/.options[ii] = new Option(Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.elementAt(i).zastavka.nazevZastavky.toString(), Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.elementAt(i).tarif.toString());
      nc/*document.getElementById(targetCombo)*/.options[ii].title = "";
      if ((((indexSmer == 0) ? Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.elementAt(i).staviA: Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.elementAt(i).staviB) == 0) ||
        (ii == Linky.linky.elementAt(document.getElementById('combolinky').selectedIndex).trasa.zastavka.size() - 1)) {
        nc/*document.getElementById(targetCombo)*/.options[ii].disabled = true;
      }
    }

    nc/*document.getElementById(targetCombo)*/.selectedIndex = 0;
    if (nc/*document.getElementById(targetCombo)*/.refresh != undefined) {
      nc/*document.getElementById(targetCombo)*/.refresh();
    }
  }
}

function loadZastavky(start, min, max) {
  //send_xmlhttprequest(hotovo, 'GET', "js/combozastavky.js", null);
  if (start == true) {
    targetCombo = 'comboZastOd';
    targetCombo1 = 'comboZastDo';
    targetCombo = 'combotrasa';

    document.getElementById(targetCombo).options.length = 0;
    document.getElementById(targetCombo1).options.length = 0;
  }
  if (min <  Zastavky.zastavky.size()) {
    targetCombo = 'comboZastOd';
    targetCombo1 = 'comboZastDo';
    for (var i = min;i < ((max > Zastavky.zastavky.size()) ? Zastavky.zastavky.size(): max); i++) {
      document.getElementById(targetCombo).options[i] = new Option(Zastavky.zastavky.elementAt(i).nazevZastavky.toString(), Zastavky.zastavky.elementAt(i).idZastavky.toString());
      document.getElementById(targetCombo1).options[i] = new Option(Zastavky.zastavky.elementAt(i).nazevZastavky.toString(), Zastavky.zastavky.elementAt(i).idZastavky.toString());
    }
    timer = null;
    timer = setTimeout("loadZastavky(false, " + (min + 50) + "," + (max + 50) + ")", 1);
  } else {
    targetCombo = 'comboZastOd';
    targetCombo1 = 'comboZastDo';
    document.getElementById(targetCombo).selectedIndex = 0;
    if (document.getElementById(targetCombo).refresh != undefined) {
      document.getElementById(targetCombo).refresh();
    }
    document.getElementById(targetCombo1).selectedIndex = 1;
    if (document.getElementById(targetCombo1).refresh != undefined) {
      document.getElementById(targetCombo1).refresh();
    }
  }
}

function loadZastavky1(data) {
  if ((document.getElementById("divcomboodZastavkySpojeni") != null) && (document.getElementById("divcomboodZastavkySpojeni") != null)) {
    if (verze == 1) {
      targetCombo = 'comboZastOd';
      targetCombo1 = 'comboZastDo';

      if (document.getElementById(targetCombo) == null) {
        nc = document.createElement("select");
        nc.name = "comboZastOd";
        nc.id = "comboZastOd";
        nc.style.width = "100%";//document.getElementById("divcomboodZastavkySpojeni").style.width;
        nc.style.backgroundColor = "#f2f2f2";
        nc.style.borderColor = "#c3c3c3";
        nc.style.border = "0px none";
        ncdiv = document.createElement("div");
        ncdiv.style.padding = "2px 2px 2px 0px";
        ncdiv.style.border = "1px solid #c3c3c3";
        ncdiv.style.backgroundColor = "#f2f2f2";
        ncdiv.style.offsetWidth = document.getElementById("divcomboodZastavkySpojeni").style.width;
        ncdiv.appendChild(nc);
        document.getElementById("divcomboodZastavkySpojeni").appendChild(ncdiv);
      } else {
        nc = document.getElementById(targetCombo);
      }

      if (document.getElementById(targetCombo1) == null) {
        nc1 = document.createElement("select");
        nc1.name = "comboZastDo";
        nc1.id = "comboZastDo";
        nc1.style.width = "100%";//document.getElementById("divcombodoZastavkySpojeni").style.width;
        nc1.style.backgroundColor = "#f2f2f2";
        nc1.style.borderColor = "#c3c3c3";
        nc1.style.border = "0px none";
        ncdiv1 = document.createElement("div");
        ncdiv1.style.padding = "2px 2px 2px 0px";
        ncdiv1.style.border = "1px solid #c3c3c3";
        ncdiv1.style.backgroundColor = "#f2f2f2";
        ncdiv1.style.offsetWidth = document.getElementById("divcombodoZastavkySpojeni").style.width;
        ncdiv1.appendChild(nc1);
        document.getElementById("divcombodoZastavkySpojeni").appendChild(ncdiv1);
      } else {
        nc1 = document.getElementById(targetCombo1);
      }

      nc/*document.getElementById(targetCombo)*/.options.length = 0;
      nc1/*document.getElementById(targetCombo1)*/.options.length = 0;

      if (data != null) {
      for (var i = 0;i < data.zastavky.size(); i++) {
        nc/*document.getElementById(targetCombo)*/.options[i] = new Option(data.zastavky.elementAt(i).nazevZastavky.toString(), data.zastavky.elementAt(i).idZastavky.toString());
        nc1/*document.getElementById(targetCombo1)*/.options[i] = new Option(data.zastavky.elementAt(i).nazevZastavky.toString(), data.zastavky.elementAt(i).idZastavky.toString());
      }
      }
      nc.selectedIndex = 0;
      if (nc.refresh != undefined) {
        nc.refresh();
      }
      nc1.selectedIndex = 1;
      if (nc1.refresh != undefined) {
        nc1.refresh();
      }
    }
  }
}

function loadCalendar() {
  cCalendar = new JRCalendar("cDate", CasPoznamky, 50, 140, 200, 1);
  ntag = document.getElementById("datum");
  cCalendar.show(ntag);
/*    cCalendar = new JRCalendar("cDate", CasPoznamky, 50, 140, 200, 1);
    ntag = document.getElementById("datumspojeni");
    cCalendar.show(ntag);*/

}

function loadCalendar1() {
  ntag = document.getElementById("datum");
  if (ntag != null) {
    cCalendar = new JRCalendar("cDate", CasPoznamky, Kalendar, 50, 140, document.getElementById("datum").style.width, 1);
    cCalendar.show(ntag);
  }

  ntag = document.getElementById("datumspojeni");
  if (ntag != null) {
    cCalendar1 = new JRCalendar("cDate", CasPoznamky, Kalendar, 50, 140, document.getElementById("datumspojeni").style.width, 1);
    cCalendar1.show(ntag);
  }

}

function loadOther() {
  cButton = new JRButton("cButton", 200, 14, zobrazJR, "JÔøΩzdnÔøΩ ÔøΩÔøΩd");
  ntag = document.getElementById("button");
  cButton.show(ntag);
}

function loadOther1() {
  ntag = document.getElementById("button");
  if (ntag != null) {
    if (language == 1) {
      nazev_tlacitka = "JÌzdnÌ ¯·d - dennÌ";
    }
    if (language == 2) {
      nazev_tlacitka = "Cestovn˝ poriadok"
    }
    if (language == 3) {
      nazev_tlacitka = "Time table"
    }
    cButton = new JRButton("cButton", document.getElementById("button").style.width, 14, zobrazJR, nazev_tlacitka);
    cButton.show(ntag);
  }
  ntag = document.getElementById("buttonkomplex");
  if (ntag != null) {
    if (language == 1) {
      nazev_tlacitka = "JÌzdnÌ ¯·d - komplexnÌ";
    }
    if (language == 2) {
      nazev_tlacitka = "Cestovn˝ poriadok"
    }
    if (language == 3) {
      nazev_tlacitka = "Time table"
    }
    cButtonkomplex = new JRButton("cButtonkomplex", document.getElementById("buttonkomplex").style.width, 14, zobrazJRkomplex, nazev_tlacitka);
    cButtonkomplex.show(ntag);
  }
  ntag = document.getElementById("buttonspojeni");
  if (ntag != null) {
    if (language == 1) {
      nazev_tlacitka = "SpojenÌ";
    }
    if (language == 2) {
      nazev_tlacitka = "Spojenie"
    }
    if (language == 3) {
      nazev_tlacitka = "Connection"
    }
    cButtonSpojeni = new JRButton("cButtonSpojeni", document.getElementById("buttonspojeni").style.width, 14, loadSpojeni, nazev_tlacitka);
    cButtonSpojeni.show(ntag);
  }
  ntag = document.getElementById("casspojeni");
  if (ntag != null) {
    targetCombo = 'casRange';
    if (document.getElementById(targetCombo) == null) {
//      ncdiv = document.createElement("div");
      nc = document.createElement("input");
      nc.type = "text";
      nc.name = "casRange";
      nc.id = "casRange";
      nc.style.width = "100%";//document.getElementById("casspojeni").style.offsetWidth;
      nc.style.backgroundColor = "#f2f2f2";
      nc.style.borderColor = "#c3c3c3";
      nc.style.border = "0px none";
      nc.style.margin = "0px";
      nc.style.styleFloat = "none";

        ncdiv1 = document.createElement("div");
        ncdiv1.style.padding = "2px 2px 2px 0px";
        ncdiv1.style.border = "1px solid #c3c3c3";
        ncdiv1.style.backgroundColor = "#f2f2f2";

        ncdiv = document.createElement("div");
        ncdiv.style.padding = "2px 2px 2px 0px";
        ncdiv.style.border = "1px solid #c3c3c3";
        ncdiv.style.backgroundColor = "#f2f2f2";
        ncdiv.style.offsetWidth = document.getElementById("casspojeni").style.width;
//        ncdiv.appendChild(nc);
//        document.getElementById("divcomboodZastavkySpojeni").appendChild(ncdiv);


//      nc.style.borderStyle = "solid";
/*      ncdiv.style.padding = "2px 2px 2px 0px";
      ncdiv.style.border = "1px solid #c3c3c3";
      ncdiv.style.backgroundColor = "#f2f2f2";
      ncdiv.style.height = "20px";
      ncdiv.style.offsetWidth = document.getElementById("casspojeni").style.width;*/

//      ncdiv.appendChild(nc);
      ncdiv1.appendChild(ncdiv);
      document.getElementById("casspojeni").appendChild(nc/*ncdiv*/);
    } else {
      nc = document.getElementById(targetCombo);
    }
  /*      $('#casRange').timeEntry({show24Hours: true, showSeconds: false});
      nc.timeEntry('change', $.timeEntry.regional['cs']);
      time = new Date();
      nc.timeEntry('setTime', time);*/
  }

//    <input type="text" size="10" id="casRange" style="width: 242px; background-color: #f2f2f2; border-color: #c3c3c3;"></p>
}

function showpage() {
  document.getElementById("load").style.visibility = "hidden";
  document.getElementById("divVyber").style.visibility = "visible";
}

function showpage1() {
//  document.getElementById("load").style.visibility = "hidden";
  document.getElementById("tabsecond").style.visibility = "visible";
//  document.getElementById("divVyber").style.visibility = "visible";
}

function zobrazJR(obj) {
  //    document.getElementById("loadtext").innerHTML = "";
  //    document.getElementById("load").style.visibility = "visible";
  pozn = cCalendar.getPoznamky();
  kod = pozn;
  var target = document.createElement("script");
  JR = null;
  comboLinky = document.getElementById("combolinky");

  nowstr = cCalendar.getYear() + '-' + ((cCalendar.getMonth() < 10) ? '0' + cCalendar.getMonth(): cCalendar.getMonth()) + '-' + ((cCalendar.getDay() < 10) ? '0' + cCalendar.getDay(): cCalendar.getDay());
  linkavyber = comboLinky.options[comboLinky.selectedIndex].value;
  obejdipodminku = false;
  /*    if (linkavyber == '105630') {
      if ((('2010-05-29' <= nowstr) && ('2010-12-31' >= nowstr))) {
        linkavyber = '105631'; obejdipodminku = true;
      }
    }*/
  if ((Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD == '') || Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO == '') {
    obejdipodminku = true;
  }
  if (((Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD <= nowstr) && (Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO >= nowstr)) || (obejdipodminku == true)) {

    comboTrasa = document.getElementById("combotrasa");
    comboSmer = document.getElementById("combosmer");

    ntag = document.getElementById('jr');
    if (ntag == null) {
      ntag = document.createElement('div');
      ntag.id = 'jr';
      ndivtag = document.createElement('div');
      ndivtag.id = 'divJR';
      ndivtag.style.position = rposition;
      ndivtag.style.left = rleft + 'px';
      ndivtag.style.top = rtop + 'px';
      ndivtag.style.zIndex = '101';
//      ndivtag.style.backgroundColor = '#ffffff';
      ndivtag.appendChild(ntag);
      document.body.appendChild(ndivtag);
      movableJR = true;
    }

      document.body.appendChild(target);
      target.setAttribute("charset", "utf-8");
      target.setAttribute("src", "//www.mhdspoje.cz/test/LoadJR.php?l="+linkavyber+"&t="+comboTrasa.options[comboTrasa.selectedIndex].value+"&s="+comboSmer.options[comboSmer.selectedIndex].value+"&jr=JRData&j=JR&i=0&ta=tabulka&po="+kod+"&loc="+lokace+"&move="+movableJR+"&pac="+packet);
//     getJR(linkavyber, comboSmer.options[comboSmer.selectedIndex].value, comboTrasa.options[comboTrasa.selectedIndex].value, lokace, packet, null, null, null);
  } else {
    tag = 'jr';
    ntag = document.getElementById(tag);
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    ntag = document.getElementById(tag);
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }

    pozaditab = document.createElement("table");
    pozaditab.className = "jrtable";
    pozadirow = pozaditab.insertRow(0);
    pozadicol = pozadirow.insertCell(0);

    pozadidiv = document.createElement("div");
    pozadidiv.className = "pozadi";

    output = document.createElement("div");
    output.style.paddingRight = "15px";
    output.style.paddingLeft = "15px";
    output.style.paddingTop = "15px";
    output.style.paddingBottom = "15px";
    outtext = document.createElement("a");
    outtext.className = "tab_text";
    if (language == 1) {
      outtext.innerHTML = "Platnost linky : ";
    }
    if (language == 2) {
      outtext.innerHTML = "Platnosù linky : ";
    }
    if (language == 3) {
      outtext.innerHTML = "Validity route : ";
    }
    outtext.innerHTML +=
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[8]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[9]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[5]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[6]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[0]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[1]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[2]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[3]+"  -  "+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[8]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[9]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[5]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[6]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[0]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[1]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[2]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[3];
    output.appendChild(outtext);

    fdiv = document.createElement("div");
    fdiv.className = "FS_1";

    outtext1 = document.createElement("a");
    outtext1.className = "FS_1";
    outtext1.innerHTML = "JRw ver. 2.0. - SKELETON &reg FS software s.r.o.";
    fdiv.appendChild(outtext1);
    output.appendChild(fdiv);

    pozadidiv.appendChild(output);

    obloukdiv = document.createElement("div");
    obloukdiv.className = "pozadioblouk"
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_1";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_2";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_3";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_4";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_5";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_6";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_7";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_8";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_9";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_10";
    obloukdiv.appendChild(obloukelement);

    pozadicol.appendChild(obloukdiv);
    pozadicol.appendChild(pozadidiv);

    obloukdiv = document.createElement("div");
    obloukdiv.className = "pozadioblouk"
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_10";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_9";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_8";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_7";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_6";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_5";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_4";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_3";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_2";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_1";
    obloukdiv.appendChild(obloukelement);

    pozadicol.appendChild(obloukdiv);

    ntag.appendChild(pozaditab);

    document.getElementById('divJR').style.visibility = 'visible';
  }
}

function zobrazJRkomplex(obj) {
  //    document.getElementById("loadtext").innerHTML = "";
  //    document.getElementById("load").style.visibility = "visible";
  pozn = cCalendar.getPoznamky();
  kod = pozn;
  var target = document.createElement("script");
  JR = null;
  comboLinky = document.getElementById("combolinky");

  nowstr = cCalendar.getYear() + '-' + ((cCalendar.getMonth() < 10) ? '0' + cCalendar.getMonth(): cCalendar.getMonth()) + '-' + ((cCalendar.getDay() < 10) ? '0' + cCalendar.getDay(): cCalendar.getDay());
  linkavyber = comboLinky.options[comboLinky.selectedIndex].value;
  obejdipodminku = false;
  /*    if (linkavyber == '105630') {
      if ((('2010-05-29' <= nowstr) && ('2010-12-31' >= nowstr))) {
        linkavyber = '105631'; obejdipodminku = true;
      }
    }*/
  if ((Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD == '') || Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO == '') {
    obejdipodminku = true;
  }
  if (((Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD <= nowstr) && (Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO >= nowstr)) || (obejdipodminku == true)) {

    comboTrasa = document.getElementById("combotrasa");
    comboSmer = document.getElementById("combosmer");

    ntag = document.getElementById('jr');
    if (ntag == null) {
      ntag = document.createElement('div');
      ntag.id = 'jr';
      ndivtag = document.createElement('div');
      ndivtag.id = 'divJR';
      ndivtag.style.position = rposition;
      ndivtag.style.left = rleft + 'px';
      ndivtag.style.top = rtop + 'px';
      ndivtag.style.zIndex = '101';
//      ndivtag.style.backgroundColor = '#ffffff';
      ndivtag.appendChild(ntag);
      document.body.appendChild(ndivtag);
      movableJR = true;
    }

/*      document.body.appendChild(target);
      target.setAttribute("charset", "utf-8");
      target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadJR.php?l="+linkavyber+"&t="+comboTrasa.options[comboTrasa.selectedIndex].value+"&s="+comboSmer.options[comboSmer.selectedIndex].value+"&jr=JRData&j=JR&i=0&ta=tabulka&po="+kod+"&loc="+lokace+"&move="+movableJR+"&pac="+packet);*/
     getJR(linkavyber, comboSmer.options[comboSmer.selectedIndex].value, comboTrasa.options[comboTrasa.selectedIndex].value, lokace, packet, null, null, null);
     document.getElementById('divJR').style.visibility = 'visible';
  } else {
    tag = 'jr';
    ntag = document.getElementById(tag);
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    ntag = document.getElementById(tag);
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }

    pozaditab = document.createElement("table");
    pozaditab.className = "jrtable";
    pozadirow = pozaditab.insertRow(0);
    pozadicol = pozadirow.insertCell(0);

    pozadidiv = document.createElement("div");
    pozadidiv.className = "pozadi";

    output = document.createElement("div");
    output.style.paddingRight = "15px";
    output.style.paddingLeft = "15px";
    output.style.paddingTop = "15px";
    output.style.paddingBottom = "15px";
    outtext = document.createElement("a");
    outtext.className = "tab_text";
    if (language == 1) {
      outtext.innerHTML = "Platnost linky : ";
    }
    if (language == 2) {
      outtext.innerHTML = "Platnosù linky : ";
    }
    if (language == 3) {
      outtext.innerHTML = "Validity route : ";
    }
    outtext.innerHTML +=
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[8]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[9]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[5]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[6]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[0]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[1]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[2]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrOD[3]+"  -  "+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[8]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[9]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[5]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[6]+"."+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[0]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[1]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[2]+
    Linky.getLinkabyId(comboLinky.options[comboLinky.selectedIndex].value).jrDO[3];
    output.appendChild(outtext);

    fdiv = document.createElement("div");
    fdiv.className = "FS_1";

    outtext1 = document.createElement("a");
    outtext1.className = "FS_1";
    outtext1.innerHTML = "JRw ver. 2.0. - SKELETON &reg FS software s.r.o.";
    fdiv.appendChild(outtext1);
    output.appendChild(fdiv);

    pozadidiv.appendChild(output);

    obloukdiv = document.createElement("div");
    obloukdiv.className = "pozadioblouk"
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_1";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_2";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_3";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_4";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_5";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_6";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_7";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_8";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_9";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_10";
    obloukdiv.appendChild(obloukelement);

    pozadicol.appendChild(obloukdiv);
    pozadicol.appendChild(pozadidiv);

    obloukdiv = document.createElement("div");
    obloukdiv.className = "pozadioblouk"
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_10";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_9";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_8";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_7";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_6";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_5";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_4";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_3";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_2";
    obloukdiv.appendChild(obloukelement);
    obloukelement = document.createElement("div");
    obloukelement.className = "pozadi1_1";
    obloukdiv.appendChild(obloukelement);

    pozadicol.appendChild(obloukdiv);

    ntag.appendChild(pozaditab);

    document.getElementById('divJR').style.visibility = 'visible';
  }
}

function changePacket() {
  zmena = 0;

  /*packetsearch = packet;

  if (lokace == 1) {

    if ((cCalendar.getDay() >= 1) && (cCalendar.getMonth() >= 9) && (cCalendar.getYear() >= 2010)) {
     if (packet == 0) {packet = 1; zmena = 1;}
    } else {
     if (packet == 1) {packet = 0; zmena = 1;}
    }

   if ((cCalendar1.getDay() >= 1) && (cCalendar1.getMonth() >= 9) && (cCalendar1.getYear() >= 2010)) {
     if (packetsearch == 0) {packetsearch = 1;}
    } else {
     if (packetsearch == 1) {packetsearch = 0;}
    }
  }

  if  (lokace == 6) {

    if ((cCalendar.getDay() >=2) && (cCalendar.getMonth() >= 9) && (cCalendar.getYear() >= 2010)) {
     if (packet == 0) {packet = 1; zmena = 1;}
    } else {
     if (packet == 1) {packet = 0; zmena = 1;}
    }

   if ((cCalendar1.getDay() >= 2) && (cCalendar1.getMonth() >= 9) && (cCalendar1.getYear() >= 2010)) {
     if (packetsearch == 0) {packetsearch = 1;}
    } else {
     if (packetsearch == 1) {packetsearch = 0;}
    }
  }*/

  if (packet != Packer.getPack(new Date(cCalendar.getYear(), cCalendar.getMonth() - 1, cCalendar.getDay()))) {
    packet = Packer.getPack(new Date(cCalendar.getYear(), cCalendar.getMonth() - 1, cCalendar.getDay()));
    zmena = 1;
  }

  packetsearch = Packer.getPack(new Date(cCalendar1.getYear(), cCalendar1.getMonth() - 1, cCalendar1.getDay()));

 if (zmena == 1)   {

//  document.getElementById("loadtext").innerHTML = "";
//  document.getElementById('load').style.visibility = 'visible';
if (document.getElementById('divJR') != null) {
  document.getElementById('divJR').style.visibility = 'hidden';}
  if (verze == 0) {
    document.getElementById("divSpojeni").style.visibility = "hidden";
  }
  var target = document.createElement("script");
//  document.getElementById("divVyber").style.visibility = "hidden";
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadZastavky.php?str=Zastavky&loc="+lokace+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadLinky.php?str=Linky&str1=Zastavky&loc="+lokace+"&vp=0&a="+verze+"&t=1"+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc="+lokace+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadKalendar.php?str=Kalendar&loc="+lokace+"&pac="+packet);

//  lokace = document.getElementById("combolocation").value;
//  loadZastavky1(Zastavky);
//  loadLinky();
//  loadSmer(document.getElementById('combolinky').selectedIndex);
//  loadTrasa(document.getElementById('combolinky').selectedIndex, document.getElementById('combosmer').selectedIndex - 1);
 }

}

function loadPackets(data) {
/*  t = document.createElement("a");
  t.innerHTML = data.packers;
  document.body.appendChild(t);*/
  if (data == null) {
    packet = 0;
  } else {
    if (cCalendar != null) {
      packet = data.getPack(new Date(cCalendar.getYear(), cCalendar.getMonth() - 1, cCalendar.getDay()));
    } else {
      packet = data.getPack(new Date());
    }
  }
  Packer = data;
/*   t = document.createElement("a");
  t.innerHTML = "l"+packet;
  document.body.appendChild(t);*/
//  packet = 0;
//  document.write(packet);
//document.write(verze);
if (document.getElementById("combolocation") != null) {
if (document.getElementById("combolocation").value != '') {
  changeLocation1();
}
}
}

function changeLocation1() {
  var target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadZastavky.php?str=Zastavky&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadLinky.php?str=Linky&str1=Zastavky&loc="+document.getElementById("combolocation").value+"&vp=0&a="+verze+"&t=1"+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadKalendar.php?str=Kalendar&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  lokace = document.getElementById("combolocation").value;
}

function changeLocation() {
//  document.getElementById("loadtext").innerHTML = "";
//  document.getElementById('load').style.visibility = 'visible';
  document.getElementById('divJR').style.visibility = 'hidden';
  if (verze == 0) {
    document.getElementById("divSpojeni").style.visibility = "hidden";
  }

  var target = document.createElement("script");
//  document.getElementById("divVyber").style.visibility = "hidden";
  document.body.appendChild(target);
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/LoadPack.php?str=Packers&loc="+document.getElementById("combolocation").value);

/*  t = document.createElement("a");
  t.innerHTML = "c"+packet;
  document.body.appendChild(t);*/
  //packet = Packers.loadPackets1(Packers);

//8.9.2010
/*  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadZastavky.php?str=Zastavky&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadLinky.php?str=Linky&str1=Zastavky&loc="+document.getElementById("combolocation").value+"&vp=0&a="+verze+"&t=1"+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadPoznamky.php?str=CasPoznamky&str1=Poznamky&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  target = document.createElement("script");
  document.body.appendChild(target);
  target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadKalendar.php?str=Kalendar&loc="+document.getElementById("combolocation").value+"&pac="+packet);

  lokace = document.getElementById("combolocation").value;*/


//  loadZastavky1(Zastavky);
//  loadLinky();
//  loadSmer(document.getElementById('combolinky').selectedIndex);
//  loadTrasa(document.getElementById('combolinky').selectedIndex, document.getElementById('combosmer').selectedIndex - 1);
}

function changeLocation2(loc) {
  document.getElementById('divJR').style.visibility = 'hidden';
  if (verze == 0) {
    document.getElementById("divSpojeni").style.visibility = "hidden";
  }

//  var target = document.createElement("script");
//  document.body.appendChild(target);
  if (lokace != loc) {
//    target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadPack.php?str=Packers&loc="+loc);
    document.getElementById("combolocation").selectedIndex = loc;
    document.getElementById("m"+loc).className = "logomestoactive";
    document.getElementById("m"+lokace).className = "logomestoinactive";
    changeLocation();
  }
}

function hotovo(xml) {
//   document.getElementById('load').style.visibility = 'visible';
//x=xml.getElementsByTagName("people");
//    xmlDoc=xml.responseXML;
//    var data = "";

//alert(Linky);
//data = xmlDoc.getElementsByTagName("idlinky")[0].childNodes[0].nodeValue;
//alert(xmlDoc.getElementsByTagName("person").length);
//    for (i=0;i<xmlDoc.getElementsByTagName("person").length;i++)
//alert(xmlDoc.getElementsByTagName("idlinky")[0].childNodes[0].nodeValue);
//    {

//        data = xmlDoc.getElementsByTagName("idlinky")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("smer")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("idchrono")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("ct")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("cz")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("dj")[i].childNodes[0].nodeValue + ", " + xmlDoc.getElementsByTagName("dp")[i].childNodes[0].nodeValue;
//document.write(data);
//        document.write(i + ". " + xmlDoc.getElementsByTagName("idlinky")[i].childNodes[0].nodeValue + " , " + xmlDoc.getElementsByTagName("idchrono")[i].childNodes[0].nodeValue);
//document.write("<br>");
//        Linky.addChronoItem(xmlDoc.getElementsByTagName("idlinky")[i].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("smer")[i].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("idchrono")[i].childNodes[0].nodeValue,
//            xmlDoc.getElementsByTagName("ct")[i].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("cz")[i].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("dj")[i].childNodes[0].nodeValue, xmlDoc.getElementsByTagName("dp")[i].childNodes[0].nodeValue);
//alert(xmlDoc.getElementsByTagName("idlinky")[i].childNodes[0].nodeValue);
//    }
// alert(data);
}

/*function send_xmlhttprequest(obsluha, method, url, t)
{

  var xmlhttp = (window.XMLHttpRequest ? new XMLHttpRequest : (window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : false));
  if (!xmlhttp) {
    return false;
  }
  xmlhttp.open(method, url);
  xmlhttp.onreadystatechange = function() {
    //        document.write(xmlhttp.readyState);
    //        document.write("<br>");

    if (xmlhttp.readyState == 4) {
      if (xmlhttp.status == 200) {
//                      document.write("<script>");
//              document.write(xmlhttp.responseText);
//              document.write("</script>");
        //              (xmlhttp.responseText)()  ;
        //            alert(xmlhttp.responseText);
        var script = document.createElement("script");
        script.src = 'http://www.mhdspoje.cz/jrw/test_blava/js/combozastavky.js';//xmlhttp.responseText;
        document.body.appendChild(script);
      }
    }
//        if (xmlhttp.readyState == 3) {
//          document.getElementById('loadtext').innerHTML = t;
//          document.getElementById('load').style.visibility = 'visible';
//        }
  };
//  /*    if (headers) {
//        for (var key in headers) {
//            xmlhttp.setRequestHeader(key, headers[key]);
//        }
//    }
  xmlhttp.send();
  return true;
}*/

function loadSpojeni() {
/*  document.getElementById('loadtext').innerHTML = '... spojenie ...';
  document.getElementById('load').style.visibility = 'visible';*/

  ntag = document.getElementById('spojeni');
    if (ntag == null) {
      ntag = document.createElement('div');
      ntag.id = 'spojeni';
      ndivtag = document.createElement('div');
      ndivtag.id = 'divSpojeni';
      ndivtag.style.position =rposition;
      ndivtag.style.left = rleft + 'px';
      ndivtag.style.top = rtop + 'px';
//      ndivtag.style.backgroundColor = '#ffffff';
      ndivtag.style.zIndex = '101';
      ndivtag.appendChild(ntag);
      document.body.appendChild(ndivtag);
      movableJR = true;
    }

  document.getElementById("divSpojeni").style.visibility = "hidden";
  //    for(ii = 0; ii < Linky.linky.size(); ii++) {
  //    document.getElementById('load').style.visibility = 'visible';

  target = document.createElement("script");
  document.body.appendChild(target);
  /*    target.setAttribute("src", "LoadSpojeni.php?loc="+document.getElementById("combolocation").value);
    target = document.createElement("script");
    target.id = "scriptspojeni";
        document.getElementById('load').style.visibility = 'visible';
    document.body.appendChild(target);*/
  targetCombo = 'comboZastOd';
  targetCombo1 = 'comboZastDo';
  var time;
  time = $('#casRange').timeEntry('getTime');
  //    document.write(cCalendar1.getDay()+"."+cCalendar1.getMonth()+"."+cCalendar1.getYear());
  //    target.setAttribute("src", "Spojeni.php?loc="+document.getElementById("combolocation").value+"&z1="+document.getElementById(targetCombo).selectedIndex+"&z2="+document.getElementById(targetCombo1).selectedIndex+"&h="+time.getHours()+"&m="+time.getMinutes()+"&day="+cCalendar1.getDay()+"&month="+cCalendar1.getMonth()+"&year="+cCalendar1.getYear());
  pozn = cCalendar1.getPoznamky1();
  kod = pozn;

  /*document.getElementById("combolocation").value    v pokus dotaz jako loc*/
  packetsearch = Packer.getPack(new Date(cCalendar1.getYear(), cCalendar1.getMonth() - 1, cCalendar1.getDay()));
  target.setAttribute("charset", "utf-8");
  target.setAttribute("src", "//www.mhdspoje.cz/test/pokusdotaz.php?loc="+lokace+"&z1="+(document.getElementById(targetCombo).selectedIndex + 1)+"&z2="+(document.getElementById(targetCombo1).selectedIndex + 1)+"&h="+time.getHours()+"&m="+time.getMinutes()+"&day="+cCalendar1.getDay()+"&month="+cCalendar1.getMonth()+"&year="+cCalendar1.getYear()+"&po="+kod+"&move="+movableJR+"&pac="+packetsearch);
//  target.setAttribute("src", "http://www.mhdspoje.cz/test/LoadSpojeni.php?loc="+lokace+"&z1="+(document.getElementById(targetCombo).selectedIndex + 1)+"&z2="+(document.getElementById(targetCombo1).selectedIndex + 1)+"&h="+time.getHours()+"&m="+time.getMinutes()+"&day="+cCalendar1.getDay()+"&month="+cCalendar1.getMonth()+"&year="+cCalendar1.getYear()+"&po="+''+kod+''+"&move="+movableJR+"&pac="+packetsearch);

//    a = document.createElement("a");
//    a.innerHTML = poradi;
//    document.body.appendChild(a);
/*    if (ii == Linky.linky.size() - 1) {
        h = 1;
    } else {
        h = 0;
    }
    text = 'LoadSpojeni.php';*///?id='+Linky.linky.elementAt(ii).idLinky+'&h='+h;
/*    $.getScript("pokus.js", function(){
        document.getElementById('load').style.visibility = 'visible';
    });*/

//    alert(text);
//    send_xmlhttprequest(hotovo, 'GET', text, Linky.linky.elementAt(ii).idLinky);
//alert("zpoustim");
//      $.get("LoadSpojeni.php", hotovo, null);
//$.get("LoadSpojeni.php?id="+Linky.linky.elementAt(ii).idLinky+"&h="+h, null, function(data) { alert(data[1]); }, null, "xml");
/* $.get("LoadSpojeni.php?id="+Linky.linky.elementAt(ii).idLinky+"&h="+h, function(theXML){

$('person',theXML).each(function(i){
    document.getElementById('loadtext').innerHTML = $(this).find("idlinky").text();
Linky.addChronoItem($(this).find("idlinky").text(), $(this).find("smer").text(), $(this).find("idchrono").text(),
  $(this).find("ct").text(), $(this).find("cz").text(), $(this).find("dj").text(), $(this).find("dp").text());
});
});*/


//
//
//    target.setAttribute("src", "LoadSpojeni.php?id="+Linky.linky.elementAt(ii).idLinky+"&h="+h);
//    target.setAttribute("defer", "true");
//    document.body.onload = function() {document.getElementById('load').style.visibility = 'visible';};
//    target.setAttribute("onload", "function() {document.getElementById('load').style.visibility = 'visible';}");
//    document.getElementById('load').style.visibility = 'hidden';
//    }
//        document.getElementById('load').style.visibility = 'hidden';
}

function showtabs(i) {
  if (i == 1) {
    document.getElementById("tabfirst").style.visibility = "hidden";
    document.getElementById("tabfirst").style.position = "absolute";
    document.getElementById("tabfirst").style.top = "0px";

    document.getElementById("tabsecond").style.visibility = "visible";
    document.getElementById("tabsecond").style.position = "";
    document.getElementById("tabsecond").style.top = "auto";

/*    document.getElementById("divVyberSpojeni").style.visibility = "hidden";*/
    if (document.getElementById("divSpojeni") != null) {
    document.getElementById("divSpojeni").style.visibility = "hidden";
    }
//    document.getElementById("divVyber").style.visibility = "visible";
    if (movableJR != true) {
      if (document.getElementById("divJR") != null) {
        document.getElementById("divJR").style.position = "";
        document.getElementById("divJR").style.top = "auto";
      }
    }
    ntag = document.getElementById("spojeni");
    if (ntag != null) {
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    }
    ntag = document.getElementById("spojeni");
    if (ntag != null) {
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    }
  }
  if (i == 2) {
    document.getElementById("tabsecond").style.visibility = "hidden";
    document.getElementById("tabsecond").style.position = "absolute";
    document.getElementById("tabsecond").style.top = "0px";
    document.getElementById("tabfirst").style.visibility = "visible";
        document.getElementById("tabfirst").style.position = "";

/*    document.getElementById("divVyber").style.visibility = "hidden";
    document.getElementById("divVyber").style.height = "0px";*/
    if (document.getElementById("divJR") != null) {
      document.getElementById("divJR").style.visibility = "hidden";
    }
//    document.getElementById("divVyberSpojeni").style.visibility = "visible";
    ntag = document.getElementById("jr");
    if (ntag != null) {
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    }
    ntag = document.getElementById("jr");
    if (ntag != null) {
    for(x = 0; ntag.childNodes[x]; x++) {
      ntag.removeChild(ntag.childNodes[x]);
    }
    }
  }
  activetab = i;
//    document.getElementById("d2").style.visibility = "visible";
}

function nastavLinku(linka) {
  if (linka != null) {
    if (document.getElementById("divcombolinky") != null) {
      nc = document.getElementById("combolinky");
      if (nc != null) {
        nc.selectedIndex = Linky.getIndexLinkabyId(linka);
        if (nc.refresh != undefined) {
          nc.refresh();
        }
        loadSmer(nc.selectedIndex);
      }
    }
  }
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if (typeof(window.pageYOffset) == 'number') {
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else
  if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else
  if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [scrOfX, scrOfY];
}

function setLoad() {
  document.getElementById('load').style.top = getScrollXY()[1]+"px";
}