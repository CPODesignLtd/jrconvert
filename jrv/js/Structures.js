TVector = function() {
  this.self = this;
  this.vector = null;
}

TVector.prototype.addElement = function(object) {
  if (this.vector == null) {
    this.vector = new Array();
  }
  this.vector.push(object);
}

TVector.prototype.elementAt = function(index) {
  return this.vector[index];
}

TVector.prototype.size = function() {
  if (this.vector == null) {
    return 0;
  } else {
    return this.vector.length;
  }
}

TZastavka = function() {
  this.self = this;
  this.idZastavky = null;
  this.nazevZastavky = null;
}

TZastavka.prototype.setValues = function(id, nazev) {
  this.idZastavky = id;
  this.nazevZastavky = nazev;
}

TZastavky = function() {
  this.self = this;
  this.zastavky = null;
}

TZastavky.prototype.addZastavka = function(id, nazev) {
  if (this.zastavky == null) {
    this.zastavky = new TVector();
  }
  this.zastavka = new TZastavka();
  this.zastavka.setValues(id, nazev);
  this.zastavky.addElement(this.zastavka);
  var targetCombo = 'comboZastOd';
//    document.getElementById(targetCombo).options[id] = new Option(nazev + "(" + id + ")", id);
}


TTrasaElement = function(zast, id, trf, sA, sB) {
  this.self = this;
  this.zastavka = zast.zastavky.elementAt(id - 1);
  this.tarif = trf;
  this.staviA = sA;
  this.staviB = sB;
}

TTrasa = function(zastavky) {
  this.self = this;
  this.Zastavky = zastavky;
  this.zastavka = null;
}

TTrasa.prototype.addZastavka = function(id, trf, sA, sB) {
  if (this.zastavka == null) {
    this.zastavka = new TVector();
  }
  this.zastavka.addElement(new TTrasaElement(this.Zastavky, id, trf, sA, sB));
}

TChronoItem = function(ct, cz, dj, dp) {
  this.self = this;
  this.c_tarif = ct;
  this.c_zastavky = cz;
  this.doba_jizdy = dj;
  this.doba_pocatek = dp;
}

TChrono = function(id) {
  this.self = this;
  this.idchrono = id;
  this.chrono = null;
}

TChrono.prototype.addChronoItems = function(ct, cz, dj, dp) {
  if (this.chrono == null) {
    this.chrono = new TVector();
  }
  this.chrono.addElement(new TChronoItem(ct, cz, dj, dp));
}

TLinka = function() {
  this.self = this;
  this.idLinky = null;
  this.nazevLinky = null;
  this.doprava = null;
  this.smerA = null;
  this.smerB = null;
  this.trasa = null;
  this.chronoA = new TVector();
  this.chronoB = new TVector();
  this.odjezdyA = new TVector();
  this.odjezdyB = new TVector();
}

TLinka.prototype.setValues = function(zastavky, id, nazev, dopr, sA, sB, sjrOD, sjrDO) {
  this.idLinky = id;
  this.nazevLinky = nazev;
  this.doprava = dopr;
  this.smerA = sA;
  this.smerB = sB;
  this.jrOD = sjrOD;
  this.jrDO = sjrDO;
  this.trasa = new TTrasa(zastavky);
}

TLinka.prototype.getChronobyId = function(smer, idChrono) {
  this.pomChrono = null;

  if (smer == 0) {
    if (this.chronoA.size() > 0) {
      if (this.chronoA.elementAt(this.chronoA.size() - 1).idchrono == idChrono) {
        this.pomChrono = this.chronoA.elementAt(this.chronoA.size() - 1);
      }
    }
  }
  if (smer == 1) {
    if (this.chronoB.size() > 0) {
      if (this.chronoB.elementAt(this.chronoB.size() - 1).idchrono == idChrono) {
        this.pomChrono = this.chronoB.elementAt(this.chronoB.size() - 1);
      }
    }
  }

  /*    if (smer == 0) {
        for(i = 0; i < this.chronoA.size(); i++) {
            if (this.chronoA.elementAt(i).idchrono == idChrono) {
                this.pomChrono = this.chronoA.elementAt(i);
            }
        }
    }
    if (smer == 1) {
        for(i = 0; i < this.chronoB.size(); i++) {
            if (this.chronoB.elementAt(i).idchrono == idChrono) {
                this.pomChrono = this.chronoB.elementAt(i);
            }
        }
    }*/
  return this.pomChrono;
}

TLinky = function() {
  this.self = this;
  this.linky = null;
}

TLinky.prototype.addLinka = function(z, id, nazev, dopr, sA, sB, sjrOD, sjrDO) {
  if (this.linky == null) {
    this.linky = new TVector();
  }
  this.linka = new TLinka();
  this.linka.setValues(z, id, nazev, dopr, sA, sB, sjrOD, sjrDO);
  this.linky.addElement(this.linka);
}

TLinky.prototype.addTrasaElement = function(idlinky, id, trf, sA, sB) {
  this.pomtrasa = null;
  for(i = 0; i < this.linky.size(); i++) {
    if (this.linky.elementAt(i).idLinky == idlinky) {
      this.pomtrasa = this.linky.elementAt(i).trasa;
      break;
    }
  }
  if (this.pomtrasa != null) {
    this.pomtrasa.addZastavka(id, trf, sA, sB);
  }
}

TLinky.prototype.getLinkabyId = function(idlinky) {
  this.pomLinka = null;
  for(i = 0; i < this.linky.size(); i++) {
    if (this.linky.elementAt(i).idLinky == idlinky) {
      this.pomLinka = this.linky.elementAt(i);
    }
  }
  return this.pomLinka;
}

TLinky.prototype.getIndexLinkabyId = function(idlinky) {
  this.pomLinka = null;
  for(i = 0; i < this.linky.size(); i++) {
    if (this.linky.elementAt(i).idLinky == idlinky) {
      this.pomLinka = i;
    }
  }
  return this.pomLinka;
}

TLinky.prototype.addChronoItem = function(idlinky, smer, idchrono, ct, cz, dj, dp) {
  this.pomChrono = null;
  this.pomLinka = this.getLinkabyId(idlinky);
  if (this.pomLinka != null) {
    this.pomChrono = this.pomLinka.getChronobyId(smer, idchrono);
    //        alert("linka = "+this.pomLinka.idLinky+" , smer = "+smer+" , idchrono = "+idchrono+" , ct = "+ct+" , "+this.pomChrono);
    if (this.pomChrono == null) {
      this.pomChrono = new TChrono(idchrono);
      (smer == 0) ? this.pomLinka.chronoA.addElement(this.pomChrono): this.pomLinka.chronoB.addElement(this.pomChrono);
    }
    this.pomChrono.addChronoItems(ct, cz, dj, dp);
  }
}

TLinky.prototype.addOdjezdyItem = function(idlinky, smer, cz, hh, mm, idchrono, pk1, pk2, pk3, pk4, pk5, pk6, pk7, pk8, pk9, pk10) {
  this.pomLinka = this.getLinkabyId(idlinky);
  if (this.pomLinka != null) {
    this.pomOdjezd = new TOdjezd(smer, cz, hh, mm, idchrono, pk1, pk2, pk3, pk4, pk5, pk6, pk7, pk8, pk9, pk10);
    (smer == 0) ? this.pomLinka.odjezdyA.addElement(this.pomOdjezd): this.pomLinka.odjezdyB.addElement(this.pomOdjezd);
  }
}

TPoznamka = function() {
  this.self = this;
  this.c_kodu = null;
  this.oznaceni = null;
  this.popis = null;
}

TPoznamka.prototype.setValues = function(ck, ozn, pop, obr)  {
  this.c_kodu = ck;
  this.oznaceni = ozn;
  this.popis = pop;
  this.obrazek = obr;
}

TPoznamky = function() {
  this.self = this;
  this.poznamky = null;
}

TPoznamky.prototype.addPoznamka = function(ck, ozn, pop, obr) {
  if (this.poznamky == null) {
    this.poznamky = new TVector();
  }
  this.pozn = new TPoznamka();
  this.pozn.setValues(ck, ozn, pop, obr);
  this.poznamky.addElement(this.pozn);
}

TPoznamky.prototype.getPoznamkaOznaceni = function(ozn) {
  this.pozn = null;
  if (this.poznamky != null) {
    for(i = 0; i < this.poznamky.size(); i++) {
      if (this.poznamky.elementAt(i).oznaceni == ozn) {
        this.pozn = this.poznamky.elementAt(i);
        break;
      }
    }
  }
  return this.pozn;
}

TPoznamky.prototype.getPoznamkaID = function(id1) {
  this.pozn = null;
  if (this.poznamky != null) {
    for(this.i = 0; this.i < this.poznamky.size(); this.i++) {
      p = this.poznamky.elementAt(this.i);
      if (parseInt(p.c_kodu) == parseInt(id1)) {
        this.pozn = this.poznamky.elementAt(this.i);
        break;
      }
    }
  }
  return this.pozn;
}

TLocation = function() {
  this.self = this;
  this.idLocation = null;
  this.podnik = null;
  this.icon = null;
}

TLocation.prototype.setValues = function(id, nazev, icon) {
  this.idLocation = id;
  this.podnik = nazev;
  this.icon = icon;
}

TLocations = function() {
  this.self = this;
  this.locations = null;
}

TLocations.prototype.addLocation = function(id, nazev, icon) {
  if (this.locations == null) {
    this.locations = new TVector();
  }
  this.location = new TLocation();
  this.location.setValues(id, nazev, icon);
  this.locations.addElement(this.location);
}

TKalendarItem = function(datum, pk) {
  this.datum = datum;
  this.pk = pk;
  }

TKalendar = function() {
  this.self = this;
  this.kalendar = null;
}

TKalendar.prototype.addItem = function(datum, pk) {
//  document.write('add '+datum+', '+pk);
  if (this.kalendar == null) {
    this.kalendar = new TVector();
  }
  this.kalendar.addElement(new TKalendarItem(datum, pk));
}

TOdjezd = function(nsmer, ncz, nhh, nmm, nidchrono, npk1, npk2, npk3, npk4, npk5, npk6, npk7, npk8, npk9, npk10) {
  this.self = this;
  this.smer = nsmer;
  this.c_zastavky = ncz;
  this.HH = nhh;
  this.MM = nmm;
  this.chrono = nidchrono;
  this.pk1 = npk1;
  this.pk2 = npk2;
  this.pk3 = npk3;
  this.pk4 = npk4;
  this.pk5 = npk5;
  this.pk6 = npk6;
  this.pk7 = npk7;
  this.pk8 = npk8;
  this.pk9 = npk9;
  this.pk10 = npk10;
}

TPartSpoj = function() {
  this.self = this;
  this.Linka = null;
  this.odZastavky = null;
  this.odTarif = null;
  this.odH = null;
  this.odM = null;
  this.doZastavky = null;
  this.doTarif = null;
  this.doH = null;
  this.doM = null;
}

TPartSpoj.prototype.setLinka = function(linka, odZ, odT, odHH, odMM, doZ, doT, doHH, doMM) {
  this.Linka = linka;
  this.odZastavky = odZ;
  this.odTarif = odT;
  this.odH = odHH;
  this.odM = odMM;
  this.doZastavky = doZ;
  this.doTarif = doT;
  this.doH = doHH;
  this.doM = doMM;
}

TListSpoj = function() {
  this.self = this;
  this.partSpoj = null;
}

TListSpoj.prototype.addPartSpoj = function(partspoj) {
  if (this.partSpoj == null) {
    this.partSpoj = new TVector();
  }
  this.partSpoj.addElement(partspoj);
}

TListSpojeni = function() {
  this.self = this;
  this.spoj = null;
}

TListSpojeni.prototype.addSpoj = function(Spoj) {
  if (this.spoj == null) {
    this.spoj = new TVector();
  }
  this.spoj.addElement(Spoj);
}

TPacker = function() {
  this.self = this;
  this.idLocation = null;
  this.idPack = null;
  this.jrOD = null;
  this.jrDO = null;
  this.jeplatny = 0;
}

TPacker.prototype.setValues = function(id, idpack, jOD, jDO, platny) {
  this.idLocation = id;
  this.idPack = idpack;
  this.jrOD = jOD;
  this.jrDO = jDO;
  this.jeplatny = platny;
}

TPackers = function() {
  this.self = this;
  this.packers = null;
}

TPackers.prototype.addPack = function(id, idpack, jOD, jDO, platny) {
  if (this.packers == null) {
    this.packers = new TVector();
  }
  this.packer = new TPacker();
  this.packer.setValues(id, idpack, jOD, jDO, platny);
  this.packers.addElement(this.packer);
}

TPackers.prototype.loadPackets1 = function(data) {
  packet = this.getPack(new Date());
  return this.getPack(new Date());
}

TPackers.prototype.getPack = function(idate) {
  this.mam = 0;
  if (this.packers != null) {
  for(this.i = 0; this.i < this.packers.size(); this.i++) {
      p = this.packers.elementAt(this.i);
      d_od = new Date(Date.parse(p.jrOD));
      d_do = new Date(Date.parse(p.jrDO));
      if ((d_od <= idate) && (d_do >= idate) && (p.jeplatny == 1)) {
        this.mam = p.idPack;
        break;
      }
  }
  }
  return this.mam;
}