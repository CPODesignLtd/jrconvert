var main_path = "//152.mhdspoje.cz/jrw50";

var mesiceW1250 = new Array("ledna", "února", "března", "dubna", "května", "června", "července", "srpna", "září", "října", "listopadu", "prosince");
var mesiceUTF = new Array("ledna", "Ăşnora", "bĹ™ezna", "dubna", "kvÄ›tna", "ÄŤervna", "ÄŤervence", "srpna", "zĂˇĹ™Ă­", "Ĺ™Ă­jna", "listopadu", "prosince");
var mesiceW1250SK = new Array("január", "február", "marec", "apríl", "máj", "jún", "jul", "august", "september", "október", "november", "december");
var mesiceUTFSK = new Array("januĂˇr", "februĂˇr", "marec", "aprĂ­l", "mĂˇj", "jĂşn", "jul", "august", "september", "oktĂłber", "november", "december");
var info_1_UTF_CZ = "PĹ™edchozĂ­ zastĂˇvka&nbsp;:&nbsp";
var info_1_W1250_CZ = "Předchozí zastávka&nbsp;:&nbsp";
var info_1_UTF_SK = "PredchĂˇdzajĂşca zastĂˇvka&nbsp;:&nbsp";
var info_1_W1250_SK = "Předchozí zastávka&nbsp;:&nbsp";

var info_2_UTF_CZ = "NĂˇsledujĂ­cĂ­ zastĂˇvka&nbsp;:&nbsp;";
var info_2_W1250_CZ = "Následující zastávka&nbsp;:&nbsp;";
var info_2_UTF_SK = "NasledujĂşca zastĂˇvka&nbsp;:&nbsp;";
var info_2_W1250_SK = "Nasledujúca zastávka&nbsp;:&nbsp;";

var info_3_UTF_CZ = "N&nbsp;E&nbsp;S&nbsp;T&nbsp;A&nbsp;V&nbsp;ĂŤ&nbsp;";
var info_3_W1250_CZ = "N&nbsp;E&nbsp;S&nbsp;T&nbsp;A&nbsp;V&nbsp;Í";
var info_3_UTF_SK = "N&nbsp;E&nbsp;Z&nbsp;A&nbsp;S&nbsp;T&nbsp;A&nbsp;V&nbsp;U&nbsp;J&nbsp;E&nbsp;";
var info_3_W1250_SK = "N&nbsp;E&nbsp;Z&nbsp;A&nbsp;S&nbsp;T&nbsp;A&nbsp;V&nbsp;U&nbsp;J&nbsp;E&nbsp;";

if ((document.characterSet == 'utf-8') || (document.characterSet == 'UTF-8')) {
    var info_4_SK = "nenĂˇjdenĂ©";
    var info_4_CZ = "nenalezeno";
} else {
    var info_4_SK = "nenájdené";
    var info_4_CZ = "nenlezeno";
}

if ((document.characterSet == 'utf-8') || (document.characterSet == 'UTF-8')) {
    var info_5_SK = "ZobraziĹĄ odjazdy";
    var info_5_CZ = "Zobrazit odjezdy";
} else {
    var info_5_SK = "Zobraziť odjazdy";
    var info_5_CZ = "Zobrazit odjezdy";
}

var callfunc = null;
var callfuncBoard = null
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
var allnaseptavac = new Array();

var elfade = new Array();
var departureMap = null;

var lastSelected;

if ((document.characterSet == 'utf-8') || (document.characterSet == 'UTF-8')) {
    sdiak = 'ĂˇĂ¤ÄŤÄŹĂ©Ä›Ă­ÄşÄľĹĂłĂ´Ă¶Ĺ•ĹˇĹĄĂşĹŻĂĽĂ˝Ĺ™ĹľĂĂ„ÄŚÄŽĂ‰ÄšĂŤÄąÄ˝Ĺ‡Ă“Ă”Ă–Ĺ”Ĺ Ĺ¤ĂšĹ®ĂśĂťĹĹ˝';
//sdiak = 'ĂˇĂ¤ÄŤÄŹĂ©Ä›Ă­ÄşÄľĹĂłĂ´Ă¶Ĺ•ĹˇĹĄĂşĹŻĂĽĂ˝Ĺ™ĹľĹĂ„ÄŚÄŽĂ‰ÄšĂŤÄąÄ˝Ĺ‡Ă“Ă”Ă–Ĺ”Ĺ Ĺ¤ĂšĹ®ĂśĂťĹĹ˝';
} else {
    sdiak = "áäčďéěíĺľ�?óôöŕšťúůüýřž�?ÄČĎÉĚÍĹĽŇÓÔÖŔŠŤÚŮÜÝ�?Ž";
}
bdiak = "aacdeeillnooorstuuuyrzAACDEEILLNOOORSTUUUYRZ";
function bezdiak(txt) {
    tx = "";
    for (p = 0; p < txt.length; p++) {
        if (sdiak.indexOf(txt.charAt(p)) != -1) {
            tx += bdiak.charAt(sdiak.indexOf(txt.charAt(p)));
        } else
            tx += txt.charAt(p);
    }
    return tx;
}

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
        setTimeout("SetOpa(" + i + ")", i * 300);
    }
//   setTimeout("fadeOut()", (3000 + 2000));
}

function ScrollXY() {
    var scrOfX = 0, scrOfY = 0;
    if (typeof (window.pageYOffset) == 'number') {
        //Netscape compliant
        scrOfY = window.pageYOffset;
        scrOfX = window.pageXOffset;
    } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
        //DOM compliant
        scrOfY = document.body.scrollTop;
        scrOfX = document.body.scrollLeft;
    } else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
        //IE6 standards compliant mode
        scrOfY = document.documentElement.scrollTop;
        scrOfX = document.documentElement.scrollLeft;
    }
    return [scrOfX, scrOfY];
}

function mouseCoords(ev) {
    if (ev.pageX || ev.pageY) {
        return {
            x: ev.pageX,
            y: ev.pageY
        };
    }
    return {
        x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
        y: ev.clientY + document.body.scrollTop - document.body.clientTop
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

function departMap(amap) {
    this.map = amap;
    this.mark = null;
}

departMap.prototype.setMapCenter = function (aLat, aLong) {
    this.map.setCenter(new google.maps.LatLng(aLat, aLong));
}

departMap.prototype.setMyPosition = function (aLat, aLong) {
    this.mark = new google.maps.Marker({
        map: this.map,
        position: new google.maps.LatLng(aLat, aLong),
        icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
    });
    this.mark.setVisible(false);
}

departMap.prototype.showMyPosition = function () {
    if (this.mark !== null) {
        this.mark.setVisible(true);
    }
}

function JRTime(aTime) {
    this.self = this;
    this.hh = null;
    this.mm = null;
    this.tagTime = aTime;
}

JRTime.prototype.initialize = function (hh, mm) {
    var currentTime = new Date();
    if ((hh != null) && (mm != null)) {
        this.hh = hh;
        this.mm = mm;
    } else {
        this.hh = currentTime.getHours();
        this.mm = currentTime.getMinutes();
    }
    if (document.getElementById(this.tagTime) != null) {
        document.getElementById(this.tagTime).value = ((this.hh < 10) ? "0" + this.hh.toString() : this.hh.toString()) + ":" + ((this.mm < 10) ? "0" + this.mm.toString() : this.mm.toString());
    }
}

JRTime.prototype.getHH = function () {
    if (document.getElementById(this.tagTime) != null) {
        strtime = document.getElementById(this.tagTime).value;
        if ((strtime.substr(0, strtime.search(":"))) == "") {
            return parseInt(strtime);
        } else {
            return parseInt(strtime.substr(0, strtime.search(":")));
        }
    } else {
        return this.hh;
    }
}

JRTime.prototype.getMM = function () {
    if (document.getElementById(this.tagTime) != null) {
        strtime = document.getElementById(this.tagTime).value;
        if (strtime.search(":") > -1) {
            return parseInt(strtime.substr(strtime.search(":") + 1, 2));
        } else {
            return 0;
        }
    } else {
        return this.mm;
    }
}

function JRKalendar(aDivDatum, aTextDatum, aKalendar, other, alang, tagotherk, otherk) {
    this.self = this;
    if (alang == null) {
        this.lang = 'cz';
    } else {
        this.lang = alang;
    }
    this.tagDivDatum = aDivDatum;
    this.tagTextDatum = aTextDatum;
    this.tagTextDatumOther = tagotherk;
    this.okal = otherk;
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

    this.codepage = null;
}

JRKalendar.prototype.initialize = function (aDatum, classname, ad, am, ay) {
    if (ad == '') {
        ad = null;
    }
    if (am == '') {
        am = null;
    }
    if (ay == '') {
        ay = null;
    }
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
    if ((ad != null) && (am != null) && (ay != null)) {
        this.d = ad;
        this.m = am;
        this.y = ay;
    } else {
        this.d = den;
        this.m = mesic;
        this.y = rok;
    }
    this.ulozeny_datum = false;
    if (aDatum != null) {
        this.ulozeny_datum = true;
    }
    if (this.ulozeny_datum == true) {
        this.d = (aDatum + '').substr(8, 2);
        this.m = (aDatum + '').substr(5, 2);
        this.y = (aDatum + '').substr(0, 4);
    } else {
        if ((ad == null) && (am == null) && (ay == null)) {
            this.d = den;
            this.m = mesic;
            this.y = rok;
        }
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


        document.getElementById(this.tagDivDatum).onclick = function (e) {
            self.kalendarshow();
            if (e != null) {
                e.stopPropagation();
            } else {

                if (!e)
                    var e = window.event;

                e.cancelBubble = true;
                e.returnValue = false;
            }
        }

        document.getElementById(this.tagKalendar).onclick = function (e) {
            self.active = true;
            if (e != null) {
                e.stopPropagation();
            } else {

                if (!e)
                    var e = window.event;

                e.cancelBubble = true;
                e.returnValue = false;
            }
        }

        document.onclick = function (e) {
            self.kalendarhide();
            fLen = allnaseptavac.length;
            for (i = 0; i < fLen; i++) {
                if (document.getElementById(allnaseptavac + "Div") != null) {
                    document.getElementById(allnaseptavac + "Div").style.visibility = "hidden";
                }
            }
            /*      */
            if (e != null) {
                e.stopPropagation();
            } else {

                if (!e)
                    var e = window.event;

                /*      e.cancelBubble = true;
                 e.returnValue = false;*/
            }
        }


        this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, true);
    }
}

JRKalendar.prototype.settoall = function () {
    allkalend.push(this.tagKalendar);
}

JRKalendar.getDD = function () {
    return this.d;
}

JRKalendar.prototype.setOnChange = function (func) {
    this.tagTextDatumChange = func;
}

JRKalendar.prototype.setZIndex = function (aIndex) {
    document.getElementById(this.tagKalendar).style.zIndex = aIndex;
}

JRKalendar.prototype.getKalendarData = function (tagName, day, month, year, pday, pmonth, pyear, hide, data) {
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

JRKalendar.prototype.getKalendar = function (tagName, day, month, year, pday, pmonth, pyear, hide) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    if (this.codepage == 'UTF') {
        scriptObj.setAttribute("charset", "UTF-8");
    } else {
        scriptObj.setAttribute("charset", "windows-1250");
    }
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
        fullUrl = main_path + "/php/5_1/kalendarJOSN.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&lang=" + this.lang + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
    } else {
        fullUrl = main_path + "/php/5_1/kalendarJSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&lang=" + this.lang + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
    }

    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrKalend" + "_" + this.tagDivDatum);
    document.body.appendChild(scriptObj);
}

JRKalendar.prototype.setKalendar = function (day, month, year, pday, pmonth, pyear, hide) {
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

JRKalendar.prototype.kalendarChange = function (day, tag_mesic, tag_rok, pday, pmonth, pyear) {
    this.setKalendar(day, document.getElementById(tag_mesic).value, document.getElementById(tag_rok).value, pday, pmonth, pyear, false);
}

JRKalendar.prototype.setCodepage = function (page) {
    this.codepage = page;
}

JRKalendar.prototype.setDatum = function (day, month, year) {
    this.d = day;
    this.m = month;
    this.y = year;
    if (this.JR != null) {
        if (this.JR.packet != null) {
            if (this.JR.packet != this.JR.getAktualPacketDatum(this)) {
                this.JR.packet = this.JR.getAktualPacketDatum(this);
                if (document.getElementById(this.JR.tagLinky) != null) {
                    this.JR.oldLinka = document.getElementById(this.JR.tagLinky).value;
                }
                this.JR.loaded = false;
                this.JR.loadedSpojeniData = false;
                this.JR.loadData();
                /*        this.JR.loadSpojeniData();*/
            } else {
                if (document.getElementById(this.JR.tagLinky) != null) {
                    this.JR.oldLinka = document.getElementById(this.JR.tagLinky).value;
                    this.JR.getLinkyList(this.JR.location, this.JR.packet, this.JR.tagLinky);
                }
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
            switch (this.JR.codepage) {
                case "W1250" :
                    if (this.lang == 'sk') {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250SK[month - 1] + " " + year;
                    } else {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
                    }
                    break;
                case "UTF" :
                    if (this.lang == 'sk') {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTFSK[month - 1] + " " + year;
                    } else {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTF[month - 1] + " " + year;
                    }
                    break;
                default :
                    document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
            }
        } else {
            switch (this.codepage) {
                case "W1250" :
                    if (this.lang == 'sk') {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250SK[month - 1] + " " + year;
                    } else {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
                    }
                    break;
                case "UTF" :
                    if (this.lang == 'sk') {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTFSK[month - 1] + " " + year;
                    } else {
                        document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTF[month - 1] + " " + year;
                    }
                    break;
                default :
                    document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
            }
            //      document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
        }
    }
    if ((this.tagTextDatumOther != null) && (this.tagTextDatum != null)) {
        if (document.getElementById(this.tagTextDatumOther).innerHTML != document.getElementById(this.tagTextDatum).innerHTML) {
            document.getElementById(this.tagTextDatumOther).innerHTML = document.getElementById(this.tagTextDatum).innerHTML;
            if (this.okal != null) {
                this.okal.d = day;
                this.okal.m = month;
                this.okal.y = year;
            }
        }
    }
}

JRKalendar.prototype.kalendarshow = function () {
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

JRKalendar.prototype.kalendarhide = function (aActive) {
    //  if (this.active == false) {
    document.getElementById(this.tagKalendar).style.visibility = "hidden";
    for (i = 0; i < allkalend.length; i++) {
        document.getElementById(allkalend[i]).style.visibility = "hidden";
    }
//  }
}

function JRKalendar1(aDivDatum, aTextDatum, aKalendar, tagotherk, otherk) {
    this.self = this;
    this.tagDivDatum = aDivDatum;
    this.tagTextDatum = aTextDatum;
    this.tagTextDatumOther = tagotherk;
    this.okal = otherk;
    this.tagKalendar = aKalendar;
    this.tagTextDatumChange = null;

    this.active = false;

    this.d = null;
    this.m = null;
    this.y = null;
}

JRKalendar1.prototype.initialize = function (aDatum) {
    var self = this;
    selfkalend1 = this;
    dnes = new Date();
    den = dnes.getDate();
    mesic = dnes.getMonth() + 1;
    rok = dnes.getFullYear();
    this.ulozeny_datum = false;
    if (aDatum != null) {
        this.ulozeny_datum = true;
    }
    if (this.ulozeny_datum == true) {
        this.d = (aDatum + '').substr(8, 2);
        this.m = (aDatum + '').substr(5, 2);
        this.y = (aDatum + '').substr(0, 4);
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

    document.getElementById(this.tagDivDatum).onclick = function (e) {
        self.kalendarshow();
        e.stopPropagation();
    }

    document.getElementById(this.tagKalendar).onclick = function (e) {
        self.active = true;
        e.stopPropagation();
    }

    document.onclick = function (e) {
        self.kalendarhide();
        e.stopPropagation();
    }

    this.getKalendar(this.tagKalendar, this.d, this.m, this.y, this.d, this.m, this.y, true);
}

JRKalendar1.getDD = function () {
    return this.d;
}

JRKalendar1.prototype.setOnChange = function (func) {
    this.tagTextDatumChange = func;
}

JRKalendar1.prototype.setZIndex = function (aIndex) {
    document.getElementById(this.tagKalendar).style.zIndex = aIndex;
}

JRKalendar1.prototype.getKalendarData = function (tagName, day, month, year, pday, pmonth, pyear, hide, data) {
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

JRKalendar1.prototype.getKalendar = function (tagName, day, month, year, pday, pmonth, pyear, hide) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    imp = 'selfkalend1';
    document.getElementById(this.tagTextDatum).disabled = true;
    if ((month == null) || (year == null) || (day == null)) {
        fullUrl = main_path + "/php/kalendar1JOSN.php?pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
    } else {
        fullUrl = main_path + "/php/kalendar1JSON.php?day=" + day + "&month=" + month + "&year=" + year + "&pday=" + pday + "&pmonth=" + pmonth + "&pyear=" + pyear + "&target=" + tagName + "&hide=" + hide + "&callback=getKalendarData&implement=" + imp;
    }

    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrKalend1");
    document.body.appendChild(scriptObj);
}

JRKalendar1.prototype.setKalendar = function (day, month, year, pday, pmonth, pyear, hide) {
    this.getKalendar(this.tagKalendar, day, month, year, pday, pmonth, pyear, hide);
    if (hide == true) {
        if (document.getElementById(this.tagKalendar) != null) {
            document.getElementById(this.tagKalendar).style.visibility = "hidden";
        }
    }
}

JRKalendar1.prototype.kalendarChange = function (day, tag_mesic, tag_rok, pday, pmonth, pyear) {
    this.setKalendar(day, document.getElementById(tag_mesic).value, document.getElementById(tag_rok).value, pday, pmonth, pyear, false);
}

JRKalendar1.prototype.setDatum = function (day, month, year) {
    this.d = day;
    this.m = month;
    this.y = year;
    if (this.JR != null) {
        if (this.JR.packet != null) {
            if (this.JR.packet != this.JR.getAktualPacket()) {
                this.JR.packet = this.JR.getAktualPacket();
                this.JR.oldLinka = document.getElementById(this.JR.tagLinky).value;
                this.JR.loaded = false;
                this.JR.loadedSpojeniData = false;
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
        switch (this.JR.codepage) {
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
        switch (this.codepage) {
            case "W1250" :
                document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;
                break;
            case "UTF" :
                document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceUTF[month - 1] + " " + year;
                break;    
            default :
                document.getElementById(this.tagTextDatum).innerHTML = day + ". " + mesiceW1250[month - 1] + " " + year;    
        }        
    }
    if ((this.tagTextDatumOther != null) && (this.tagTextDatum != null)) {
        if (document.getElementById(this.tagTextDatumOther).innerHTML != document.getElementById(this.tagTextDatum).innerHTML) {
            document.getElementById(this.tagTextDatumOther).innerHTML = document.getElementById(this.tagTextDatum).innerHTML;
            if (this.okal != null) {
                this.okal.d = day;
                this.okal.m = month;
                this.okal.y = year;
            }
        }
    }
}

JRKalendar1.prototype.kalendarshow = function () {
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

JRKalendar1.prototype.kalendarhide = function (aActive) {
    //  if (this.active == false) {
    document.getElementById(this.tagKalendar).style.visibility = "hidden";
//  }
}


//-----------------------------------------------------------------------------------------------------------------------------


function MapMarkers() {
    this.name = '';
    this.c_station = -1;
    this.loca = 0;
    this.locb = 0;
    this.marker = null;
    this.info = null;
    this.markerfunc = null;
    this.infofunc = null;
}

function showhideMap(_map, _div, _loca, _locb, _img) {
    nc = document.getElementById(_div);
    if (nc.style.width === '0px') {
        nc.style.width = '100%';
        nc.style.height = '300px';
        nc = document.getElementById(_img);
        if (nc != null) {
            nc.src = main_path + '/image/sipkaRUp.png';
        }
        google.maps.event.trigger(_map, 'resize');
        _map.setZoom(16);
        _map.setCenter(new google.maps.LatLng(_loca, _locb));
    } else {
        nc.style.width = '0px';
        nc.style.height = '0px';
        nc = document.getElementById(_img);
        if (nc != null) {
            nc.src = main_path + '/image/sipkaRDown.png';
        }
    }
}

function JRData(aLocation, aPacket, aLinky, aSmery, aTrasy, aKalendar, aJR, aSpojeniOD, aSpojeniDO, aSpojeniCas, otherkalend, aOdjezdy) {
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
    this.codepage = null
    this.version = 50;
    this.PTLine = false;
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
    this.tagOdjezdy = aOdjezdy;
    this.tagSpojeniCas = aSpojeniCas;
    this.ZIndexJR = 10000;
    this.ZIndexGeo = 10001;
    this.ZIndexJRSeznam = 9999;
    this.routediv = null;
    this.sharedmap = null;
    this.packetmode = 0;
    if (this.kalendar != null) {
        this.kalendar.JR = this;
    }
    if (otherkalend != null) {
        otherkalend.JR = this;
    }
    this.execAction = null;
    this.onLoadJR = null;
    this.onJRChange = null;
    this.onMapChange = null;
    this.incspoje = 0;
    this.lang = 'cz';
    this.flag = true;
    this.spojeni_index_od = null;
    this.spojeni_index_do = null;
    this.spojeni_prime = 0;
    this.time = null;

    this.address = null;
    this.loca = 0;
    this.locb = 0;
    this.idzastavky = null;
    this.stanice = [];
    this.linky = [];
    this.vyhledane_stanice = [];
    this.ExecOnLoad = null;
    this.odjezdyindex = null;
    this.departuremapobject = null;
    this.centerX = null;
    this.centerY = null;
    this.refreshJR = null;
    this.refreshBoard = null;
    this.passBoard = null;

    var selfcall = this;
    if (aJR == null) {
        aJRn = document.createElement('div');
        aJRn.className = "div_jr";
        aJRn.id = "divJRnew";
        aJRn.style.top = "330px";
        aJRn.style.zIndex = this.ZIndexJR;
        aJRn.onmousedown = function (e) {
            selfcall.changeZIndexJR();
            if (e != null) {
                e.stopPropagation();
            } else {

                if (!e)
                    var e = window.event;

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
    aSJRn.onmousedown = function (e) {
        selfcall.changeZIndexJRSeznam();
        if (e != null) {
            e.stopPropagation();
        } else {

            if (!e)
                var e = window.event;

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
    this.oldSmer = null;
    this.deleteonshow = false;

    if (document.getElementById("page_container") != null) {
        document.writeln(document.getElementById("page_container").offsetLeft);
        document.getElementById(this.tagJR).style.left = document.getElementById("page_container").offsetLeft + "px";
    }

    this.loaded = false;
    this.loadedSpojeniData = false;
}

JRData.prototype.setExecOnLoad = function (func) {
    this.ExecOnLoad = func;
}

JRData.prototype.zmenaSmeru = function () {
    nc = document.getElementById(this.tagSpojeniOD);
    nc1 = document.getElementById(this.tagSpojeniDO);
    pomindex = nc.selectedIndex;
    nc.selectedIndex = nc1.selectedIndex;
    nc1.selectedIndex = pomindex;
}

JRData.prototype.zmenaSmeruNaseptavac = function () {
    nc = document.getElementById(this.tagSpojeniOD);
    nc1 = document.getElementById(this.tagSpojeniDO);
    selindex = nc.selectedIndex;
    sel1index = nc1.selectedIndex;
    var pomnc = nc.options[selindex];
    nc.options[selindex] = nc1.options[sel1index];
    nc1.options[sel1index] = pomnc;
    nc = document.getElementById(this.tagSpojeniOD + "Text");
    nc1 = document.getElementById(this.tagSpojeniDO + "Text");
    var str = nc.value;
    nc.value = nc1.value;
    nc1.value = str;
}

JRData.prototype.initialize = function (loadJR, loadSpojeni, execA) {
    //this.kalendar.setDatum(d, m, y);
    //this.kalendar.setKalendar(d, m, y, d, m, y, true);
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
        document.onmousemove = function mouseMove(ev) {

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
        document.getElementById(this.tagLinky).onchange = function () {
            self.onLinkaChange(self.location, self.packet);
        }
    }
    if (document.getElementById(this.tagSmery) != null) {
        document.getElementById(this.tagSmery).onchange = function () {
            self.onSmerChange(self.location, self.packet);
        }
    }
    if (document.getElementById(this.tagTrasy) != null) {
        document.getElementById(this.tagTrasy).onchange = function () {
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
            mapTypeId: google.maps.MapTypeId.ROADMAP, //HYBRID,
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
    } catch (e) {
    }

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

JRData.prototype.loadData = function () {
    //  if (this.loaded == false) {
    this.disable_all_loaded_element();
    this.getPacketList(this.location);
//    this.loaded = true;
//  }
}

JRData.prototype.loadSpojeniData = function () {
    if (this.loadedSpojeniData == false) {
        this.disable_all_tag_spojeni();
        while (this.packet != null) {
        }
        this.getSpojeniList(this.location, this.tagSpojeniOD, this.tagSpojeniDO);
    }
}

JRData.prototype.setCodePage = function (code) {
    this.codepage = code;
}

JRData.prototype.setPacketmode = function (mode) {
    this.packetmode = mode;
}

JRData.prototype.setLang = function (lang) {
    this.lang = lang;
}

JRData.prototype.setVersion = function (ver) {
    this.version = ver;
    if (ver == 51) {
        this.PTLine = true;
    }
}

JRData.prototype.setShowPrivateTLine = function (aPTLine) {
    this.PTLine = aPTLine;
}

JRData.prototype.setIndexOdjezdy = function (i) {
    this.odjezdyindex = i;
}

JRData.prototype.setIndexOdjezdyByValue = function (i) {
    for (ii = 0; ii < document.getElementById(selfobj.tagOdjezdy).length; ii++) {
        if (document.getElementById(selfobj.tagOdjezdy)[ii].value == i) {
            document.getElementById(selfobj.tagOdjezdy)[ii].selected = true;
        }
    }
}

JRData.prototype.setIndexSpojeni = function (aod, ado, prime) {
    this.spojeni_index_od = aod;
    this.spojeni_index_do = ado;
    this.spojeni_prime = prime;
}

JRData.prototype.getAktualPacket = function () {
    res = -1;
    dnes = (this.kalendar.y * 10000 + this.kalendar.m * 100 + this.kalendar.d);
    for (i = 0; i < this.datapackets.length; i++) {
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

JRData.prototype.getAktualPacketDatum = function (kalend) {
    res = -1;
    dnes = (kalend.y * 10000 + kalend.m * 100 + kalend.d);
    for (i = 0; i < this.datapackets.length; i++) {
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

JRData.prototype.disableSelection = function (target) {
    if (typeof target.onselectstart != "undefined") //IE route
        target.onselectstart = function () {
            return false
        }
    else if (typeof target.style.MozUserSelect != "undefined") //Firefox route
        target.style.MozUserSelect = "none"
    else //All other route (ie: Opera)
        target.onmousedown = function () {
            return false
        }
    target.style.cursor = "default"
}


JRData.prototype.setMove = function (aMoveit) {
    this.moveit = aMoveit;
    moveit = aMoveit;
}

JRData.prototype.setInterniCSpoje = function (aincspoje) {
    this.incspoje = aincspoje;
}

JRData.prototype.setRouteDiv = function (aRouteDiv) {
    this.routediv = aRouteDiv;
}

JRData.prototype.setJRDiv = function (aJRDiv) {
    this.tagJR = aJRDiv;
}

JRData.prototype.setSeznamyDiv = function (aSeznamyDiv) {
    this.tagJRSeznam = aSeznamyDiv;
}

JRData.prototype.changeZIndexGeo = function () {
    geo = document.getElementById('divGeo');
    jr = document.getElementById(this.tagJR);
    jrSeznam = document.getElementById(this.tagJRSeznam);
    //  if ((geo != null) && (jr != null) && (jrSeznam != null)) {
    if (geo != null)
        geo.style.zIndex = this.ZIndexGeo;
    if (jr != null)
        jr.style.zIndex = this.ZIndexJR;
    if (jrSeznam != null)
        jrSeznam.style.zIndex = this.ZIndexJRSeznam;
//  }
}

JRData.prototype.changeZIndexJR = function () {
    geo = document.getElementById('divGeo');
    jr = document.getElementById(this.tagJR);
    jrSeznam = document.getElementById(this.tagJRSeznam);
    //  if ((geo != null) && (jr != null) && (jrSeznam != null)) {
    if (jr != null)
        jr.style.zIndex = this.ZIndexGeo;
    if (geo != null)
        geo.style.zIndex = this.ZIndexJR;
    if (jrSeznam != null)
        jrSeznam.style.zIndex = this.ZIndexJRSeznam;
//  }
}

JRData.prototype.changeZIndexJRSeznam = function () {
    geo = document.getElementById('divGeo');
    jr = document.getElementById(this.tagJR);
    jrSeznam = document.getElementById(this.tagJRSeznam);

    if (jrSeznam != null)
        jrSeznam.style.zIndex = this.ZIndexGeo;
    if (jr != null)
        jr.style.zIndex = this.ZIndexJR;
    if (geo != null)
        geo.style.zIndex = this.ZIndexJRSeznam;

}

JRData.prototype.map = function (address, loca, locb, idzastavky) {
    this.address = address;
    this.loca = loca;
    this.locb = locb;
    this.idzastavky = idzastavky;
    if (this.flag == true) {
        if (this.onMapChange != null) {
            this.onMapChange();
        }
        var self = this;
        var map = null;

        if ((this.routediv != null)) {
            aGeo = document.getElementById(this.routediv);
            aGeo.style.height = "500px";
        } else {
            aGeo = document.getElementById('GeoMap');
        }

        aGeoObal = document.getElementById('divGeo');

        if (aGeo == null) {
            aGeoObal = document.getElementById('divGeo');
            if ((aGeoObal == null)) {
                aGeoObal = document.createElement('div');
                aGeoObal.className = "div_pozadikomplex";
                aGeoObal.style.zIndex = this.ZIndexGeo;
                aGeoObal.onmousedown = function (e) {
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
                nc1.src = main_path + "/image/closebutton.png";
                nc1.onclick = function (e) {
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
                aGeoBottom.style.backgroundImage = "url(" + main_path + "/image/bottomlista.png)";
                aGeoResize = document.createElement('img');
                aGeoResize.style.cssFloat = "right";
                aGeoResize.src = main_path + "/image/resize.png";
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
                    document.onselectstart = function (ev) {
                        return false;
                    };
                }

                if (moveit) {
                    document.onmouseup = function () {
                        moving = false;
                        document.onselectstart = null;
                    };
                }
            } else {
                //      aGeoObal.appendChild(aGeo);
            }

            aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
            aGeoObal.style.visibility = "visible";
        }

        if (moveit) {
            if (aGeoObal != null) {
                aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
                aGeoObal.style.visibility = "visible";
            }
        }
        this.changeZIndexGeo();
        //    aGeo.style.visibility = "visible";
        map = new google.maps.Map(aGeo, this.myOptions);

        if ((loca != null) && (locb != null)) {
            if (loca < locb) {
                poma = loca;
                loca = locb;
                locb = poma;
            }
            var newpoint = new google.maps.LatLng(loca, locb);
            this.geocoder.geocode({
                "location": new google.maps.LatLng(loca, locb)
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var index = -1;
                    for (i = 0; i < results.length; i++) {
                        for (ii = 0; ii < results[i].types.length; ii++) {
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
                    //        map.setCenter(results[0].geometry.location);
                    map.setCenter(newpoint);

                    var contentString = '<div id="content" style="height: 200px;">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h1 id="firstHeading" class="firstHeading">' + address + '</h1>' +
                            '<div id="seznamMap">' +
                            '</div>' + '</div>';
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });
                    /*            '<script id="myscrSeznamZastavkaJR" type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/php/loadSeznamZastavkaJSON.php?location="' + self.location + "&packet=" + self.packet +  "&idzastavka=" + idzastavky+'">'+*/
                    var image = main_path + '/image/busstop.png';
                    var marker = new google.maps.Marker({
                        map: map,
                        //          position: results[index].geometry.location,
                        position: newpoint,
                        icon: image
                    });

                    google.maps.event.addListener(marker, 'click', function () {
                        self.tagJRSeznamMap = 'seznamMap';
                        infowindow.open(map, marker);
                        //        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
                    });

                    google.maps.event.addListener(infowindow, 'domready', function () {
                        //        self.tagJRSeznamMap = 'seznamMap';
                        //        infowindow.open(map,marker);
                        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
                    });

                    //        map.setCenter(results[index].geometry.location);
                    map.setCenter(newpoint);
                }
            });

        } else {
            this.geocoder.geocode({
                'address': address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var index = -1;
                    for (i = 0; i < results.length; i++) {
                        for (ii = 0; ii < results[i].types.length; ii++) {
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
        if (this.routediv != null) {
            window.scroll(0, (document.getElementById(this.routediv).offsetTop - 20));
        }
        /*    if (this.onMapChange != null) {
         this.onMapChange();
         }*/
    }
}

JRData.prototype.searchMapShow = function (tSearch_result) {
    var self = this;
    if (document.getElementById(tSearch_result).value >= 0) {
        if (this.routediv != null) {
            var aGeoS = document.getElementById(this.routediv);
            aGeoS.style.height = "500px";
            map = new google.maps.Map(aGeoS, this.myOptions);
            var image_active = '//www.mhdspoje.cz/jrw50/image/busstop_active.png';
            var image = '//www.mhdspoje.cz/jrw50/image/busstop.png';
            var image_search = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
            var allmarkers = [];
            if (self.lang == 'cz') {
                titlebutton = info_5_CZ;
            }

            if (self.lang == 'sk') {
                titlebutton = info_5_SK;
            }

            for (i = 0; i < self.vyhledane_stanice.length; i++) {

                if (document.getElementById(tSearch_result).value == self.vyhledane_stanice[i][3]) {
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
                                    titlebutton + '" onclick="JRData.prototype.setIndexOdjezdyByValue(' + self.vyhledane_stanice[i][3] + '); JR.odjezdyResult(' + self.location + ', ' + self.packet + ');" id="mod-rscontact-submit-btn-124" ' +
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
                                    titlebutton + '" onclick="JRData.prototype.setIndexOdjezdyByValue(' + self.vyhledane_stanice[i][3] + '); JR.odjezdyResult(' + self.location + ', ' + self.packet + ');" id="mod-rscontact-submit-btn-124" ' +
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

                if ((self.vyhledane_stanice[i][5] != -1) && (self.vyhledane_stanice[i][6] != -1)) {

                    var lineSymbol = {
                        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                    };

                    new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(self.vyhledane_stanice[i][5], self.vyhledane_stanice[i][6]), //newpoint,
                        icon: image_search
                    });

                    var linepath = [];
                    linepath.push(new google.maps.LatLng(self.vyhledane_stanice[i][5], self.vyhledane_stanice[i][6]));
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

JRData.prototype.nullSearch = function (tZastavky, offset) {
    if (tZastavky != null) {
        var select = document.getElementById(tZastavky);
        var length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
            select.options[i].remove();
        }
        for (i = 0; i < this.stanice.length; i++) {
            select.options[i] = new Option(this.stanice[i][0].toString(), this.stanice[i][3].toString());
        }
        this.vyhledane_stanice = this.stanice;
        if (offset != null) {
            select.options[offset].selected = true;
        }
        select.style.backgroundColor = '';
    }
}

JRData.prototype.searchMap = function (tSearch, tSearch_result, tZastavky) {

    var self = this;
    var map = null;
    address = null;
    //  if ((address == '') || (address == null)) {
    if (tSearch != null) {
        nc = document.getElementById(tSearch);
        address = nc.value;
    }
    //  }

    if (/*tSearch_result*/ tZastavky != null) {
        var select = document.getElementById(/*tSearch_result*/tZastavky);
        var length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
            //      select.options[i].remove();
            select.options[i] = null;
        }
        select.value = '';
        select.text = '';
        select.onchange = function () {
            self.onSearchResultChange(tSearch_result, tZastavky);
        }
    }

    if ((address != '') && (address != null)) {
        //    var address_nodiak = bezdiak(address).trim().toLowerCase();
        var res_address = bezdiak(address).trim().toLowerCase().split(' ');

        if (this.routediv != null) {
            var aGeoS = document.getElementById(this.routediv);
            aGeoS.style.height = "0px";
        }
        /*var aGeoS = document.createElement('div');
         aGeoS.id = "aGeoS";
         aGeoS.style.width = "0px";
         aGeoS.style.height = "0px";
         aGeoS.style.visibility = "hidden";*/

        /*aGeoObal = document.getElementById('divGeo');
         
         if (aGeo == null) {
         aGeoObal = document.getElementById('divGeo');
         if ((aGeoObal == null)) {
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
         nc1.src = "//www.mhdspoje.cz/jrw50/image/closebutton.png";
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
         aGeoBottom.style.backgroundImage = "url(//www.mhdspoje.cz/jrw50/image/bottomlista.png)";
         aGeoResize = document.createElement('img');
         aGeoResize.style.cssFloat = "right";
         aGeoResize.src = "//www.mhdspoje.cz/jrw50/image/resize.png";
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
         } else {
         
         }
         
         aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
         aGeoObal.style.visibility = "visible";
         }
         
         if (moveit) {
         if (aGeoObal != null) {
         aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
         aGeoObal.style.visibility = "visible";
         }
         }*/

        for (i = 0; i < this.stanice.length; i++) {
            this.stanice[i][5] = -1;
            this.stanice[i][6] = -1;
            if ((this.stanice[i][1] > 0) || (this.stanice[i][2] > 0)) {
                if (this.stanice[i][1] < this.stanice[i][2]) {
                    var premo = this.stanice[i][1];
                    this.stanice[i][1] = this.stanice[i][2];
                    this.stanice[i][2] = premo;
                }
            }
        }

        //    this.changeZIndexGeo();

        map = new google.maps.Map(aGeoS, this.myOptions);
        //this.sharedmap = map;

        var image = main_path + '/image/busstop.png';
        var vybrane_stanice = [];
        var location_address = '';
        if (self.location == 6) {
            location_address = ', Bratislava, SK';
        }
        if (self.location == 14) {
            location_address = ', Povážská Bystrica, SK';
        }
        if (self.location == 11) {
            location_address = ', Opava, CZ';
        }
        if (self.location == 12) {
            location_address = ', Chomutov, CZ';
        }
        if (self.location == 17) {
            location_address = ', Plze�?, CZ';
        }
        var location_lat = 0;
        var location_lng = 0;
        if (self.location == 6) {
            location_lat = 48.148601;
            location_lng = 17.107746;
        }
        if (self.location == 14) {
            location_lat = 49.113153;
            location_lng = 18.447511;
        }
        if (self.location == 11) {
            location_lat = 49.9210165;
            location_lng = 17.7534057;
        }
        if (self.location == 12) {
            location_lat = 50.462650;
            location_lng = 13.410931;
        }
        if (self.location == 17) {
            location_lat = 49.750307;
            location_lng = 13.374339;
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
                    for (ii = 0; ii < self.stanice.length; ii++) {
                        if ((Math.abs(results[i].geometry.location.lat() - self.stanice[ii][1]) <= 0.003) && (Math.abs(results[i].geometry.location.lng() - self.stanice[ii][2]) <= 0.003)) {
                            self.stanice[ii][5] = results[i].geometry.location.lat();
                            self.stanice[ii][6] = results[i].geometry.location.lng();
                            vybrane_stanice[self.stanice[ii][3]] = self.stanice[ii];
                            new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(self.stanice[ii][1], self.stanice[ii][2]), //newpoint,
                                icon: image,
                                namestation: self.stanice[ii][0]
                            });
                        }
                    }
                }


                for (i = 0; i < self.stanice.length; i++) {
                    var have = false;
                    var stanice_nodiak = bezdiak(self.stanice[i][0]).trim().toLowerCase();
                    for (ii = 0; ii < res_address.length; ii++) {
                        /*        if (stanice_nodiak.includes(res_address[ii].trim())) {
                         have = true
                         }*/
                        if (stanice_nodiak.indexOf(res_address[ii].trim()) > -1) {
                            have = true
                        }
                    }
                    if (have) {
                        self.stanice[i][5] = self.stanice[i][1];
                        self.stanice[i][6] = self.stanice[i][2];
                        vybrane_stanice[self.stanice[i][3]] = self.stanice[i];
                    }
                }

                var iter = 0;
                self.vyhledane_stanice = [];
                for (i = 0; i < vybrane_stanice.length; i++) {
                    if (vybrane_stanice[i] != null) {
                        self.vyhledane_stanice[iter] = vybrane_stanice[i];
                        iter++;
                    }
                }

                var select = document.getElementById(/*tSearch_result*/tZastavky);
                var length = self.vyhledane_stanice.length;
                if (length <= 0) {
                    if (self.lang == 'sk') {
                        select.options[0] = new Option(info_4_SK, '-1');
                    }
                    if (self.lang == 'cz') {
                        select.options[0] = new Option(info_4_CZ, '-1');
                    }
                    select.style.backgroundColor = '#C60023';
                } else {
                    select.style.backgroundColor = '';
                    for (i = 0; i < length; i++) {
                        select.options[i] = new Option(self.vyhledane_stanice[i][0].toString(), self.vyhledane_stanice[i][3].toString());
                    }
                }

                self.onSearchResultChange(tSearch_result, tZastavky);
            } else {
                var select = document.getElementById(/*tSearch_result*/tZastavky);
                if (self.lang == 'sk') {
                    select.options[0] = new Option(info_4_SK, '-1');
                }
                if (self.lang == 'cz') {
                    select.options[0] = new Option(info_4_CZ, '-1');
                }
                select.style.backgroundColor = '#C60023';
            }
        });
    } else {
        var select = document.getElementById(/*tSearch_result*/tZastavky);
        if (self.lang == 'sk') {
            select.options[0] = new Option(info_4_SK, '-1');
        }
        if (self.lang == 'cz') {
            select.options[0] = new Option(info_4_CZ, '-1');
        }
        select.style.backgroundColor = '#C60023';
    }
}

JRData.prototype.mapAllStops = function (address, loca, locb, idzastavky, typemap, formobile) {
    this.address = address;
    this.loca = loca;
    this.locb = locb;
    if (idzastavky != null) {
        this.idzastavky = idzastavky;
    } else {
        if (this.tagOdjezdy != null) {
            this.idzastavky = idzastavky = document.getElementById(this.tagOdjezdy).value;
        }
    }

    if (this.idzastavky >= 0) {
        if (this.flag == true) {
            if (this.onMapChange != null) {
                this.onMapChange();
            }
            var self = this;
            var map = null;

            if ((this.routediv != null)) {
                if (this.aGeo == null) {
                    aGeo = document.getElementById(this.routediv);
                    aGeo.style.height = "500px";
                } else {
                    aGeo = this.aGeo;
                }
            } else {
                aGeo = document.getElementById('GeoMap');
            }

            aGeoObal = document.getElementById('divGeo');

            if (aGeo == null) {
                aGeoObal = document.getElementById('divGeo');
                if ((aGeoObal == null)) {
                    aGeoObal = document.createElement('div');
                    aGeoObal.className = "div_pozadikomplex";
                    aGeoObal.style.zIndex = this.ZIndexGeo;
                    aGeoObal.onmousedown = function (e) {
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
                    nc1.src = main_path + "/image/closebutton.png";
                    nc1.onclick = function (e) {
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
                    aGeoBottom.style.backgroundImage = "url(" + main_path + "/image/bottomlista.png)";
                    aGeoResize = document.createElement('img');
                    aGeoResize.style.cssFloat = "right";
                    aGeoResize.src = main_path + "/image/resize.png";
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
                        document.onselectstart = function (ev) {
                            return false;
                        };
                    }

                    if (moveit) {
                        document.onmouseup = function () {
                            moving = false;
                            document.onselectstart = null;
                        };
                    }
                } else {
                    //      aGeoObal.appendChild(aGeo);
                }

                aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
                aGeoObal.style.visibility = "visible";
            }

            if (moveit) {
                if (aGeoObal != null) {
                    aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
                    aGeoObal.style.visibility = "visible";
                }
            }
            this.changeZIndexGeo();
            //    aGeo.style.visibility = "visible";
            mapg = new google.maps.Map(aGeo, this.myOptions);
            this.sharedmap = mapg;
            if (formobile == 1) {
                departureMap = new departMap(mapg);
            }

            if (loca < locb) {
                var premo = loca;
                loca = locb;
                locb = premo;
            }

            var markersinfo = [];

            if (this.stanice.length <= 0) {
                this.getStaniceList(this.location, this.packet);
            }

            for (i = 0; i < this.stanice.length; i++) {
                if ((this.stanice[i][1] > 0) || (this.stanice[i][2] > 0)) {
                    if (this.stanice[i][1] < this.stanice[i][2]) {
                        var premo = this.stanice[i][1];
                        this.stanice[i][1] = this.stanice[i][2];
                        this.stanice[i][2] = premo;
                    }

                    if ((loca == null) && (locb == null) && (idzastavky == this.stanice[i][3])) {
                        loca = this.stanice[i][1];
                        locb = this.stanice[i][2]
                    }
                    var iloca = this.stanice[i][1];
                    var ilocb = this.stanice[i][2];


                    var newpoint = new google.maps.LatLng(this.stanice[i][1], this.stanice[i][2]);

                    if (formobile == 1) {
                        var image = {
                            url: main_path + '/image/busstop.png'/*,
                             scaledSize: new google.maps.Size(40, 46)*/
                        }
                    } else {
                        var image = main_path + '/image/busstop.png';
                    }

                    if ((iloca == loca) && (ilocb == locb)) {
                        image = main_path + '/image/busstop_active.png';
                    }

                    if (formobile == 1) {
                        var info = new google.maps.InfoWindow({
                            title: this.stanice[i][0],
                            idstation: this.stanice[i][3], /*width: auto;*/
                            content:                                                                                        /*class="popisek"*/
                                    '<table style="width: auto;"><tr style="width: auto;"><th style="text-align: left;"><a  style = "text-align: left; font-size: 18px; ">' + this.stanice[i][0] + '&nbsp;&nbsp;</a></th><th>&nbsp;&nbsp;</th></tr>' +
                                    '<trstyle="width: auto;"><td id="seznamMap" style="width: auto;"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>'

                        });
                    } else {
                        var info = new google.maps.InfoWindow({
                            title: this.stanice[i][0],
                            idstation: this.stanice[i][3],
                            content:
                                    '<table><tr><th><a class="popisek" style = "font-size: 18px; width: auto;">' + this.stanice[i][0] + '&nbsp;&nbsp;</a></th><th>&nbsp;&nbsp;</th></tr>' +
                                    '<tr><td id="seznamMap" style="width: auto"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>'

                        });
                    }
                    markersinfo.push(info);

                    //      var fceready =

                    var marker = new google.maps.Marker({
                        map: mapg,
                        position: newpoint,
                        icon: image,
                        namestation: this.stanice[i][0],
                        obj: new MapMarkers({
                            name: this.stanice[i][0],
                            loca: this.stanice[i][1],
                            locb: this.stanice[i][2]
                        }),
                        objinfo: info,
                        markersin: markersinfo,
                        infoready: function () {
                            selfobj.tagJRSeznamMap = 'seznamMap';
                            if (typemap == 1) {
                                getOdjezdyResult(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, this.idstation, "seznamMap");
                            } else {
                                getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, this.idstation);
                            }
                        },
                        funcclick: function () {
                            for (i = 0; i < this.markersin.length; i++) {
                                this.markersin[i].close();
                            }
                            this.objinfo.open(mapg, this);
                        }
                    });

                    google.maps.event.addListener(marker, 'click', marker.funcclick);
                    google.maps.event.addListener(info, 'domready', marker.infoready);

                    if ((iloca == loca) && (ilocb == locb)) {
                        mapg.setCenter(newpoint);
                    }
                    if ((loca == null) && (locb == null)) {
                        if (this.location == 6) {
                            mapg.setCenter(new google.maps.LatLng(48.148601, 17.107746));
                        }
                        if (this.location == 14) {
                            mapg.setCenter(new google.maps.LatLng(49.113153, 18.447511));
                        }
                        if (this.location == 11) {
                            mapg.setCenter(new google.maps.LatLng(49.938518, 17.902996));
                        }
                        if (self.location == 12) {
                            mapg.setCenter(new google.maps.LatLng(50.462650, 13.410931));
                        }
                        if (self.location == 3) {
                            mapg.setCenter(new google.maps.LatLng(50.659063, 14.039776));
                        }                        
                    }

                    /*      this.geocoder.geocode( {
                     "location": new google.maps.LatLng(self.stanice[i][1], self.stanice[i][2])
                     }, function(results, status) {
                     if ((iloca == loca) && (ilocb == locb)) {
                     mapg.setCenter(newpoint);
                     }
                     });*/

                }
            }

            /*    if ((loca != null) && (locb != null)) {
             if (loca < locb) {
             poma = loca;
             loca = locb;
             locb = poma;
             }
             var newpoint = new google.maps.LatLng(loca,  locb);
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
             //        map.setCenter(results[0].geometry.location);
             map.setCenter(newpoint);
             
             var contentString = '<div id="content" style="height: 200px;">'+
             '<div id="siteNotice">'+
             '</div>'+
             '<h1 id="firstHeading" class="firstHeading">' + address + '</h1>'+
             '<div id="seznamMap">'+
             '</div>'+'</div>';
             var infowindow = new google.maps.InfoWindow({
             content: contentString
             });
             var image = '//www.mhdspoje.cz/jrw50/image/busstop.png';
             var marker = new google.maps.Marker({
             map: map,
             //          position: results[index].geometry.location,
             position: newpoint,
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
             
             //        map.setCenter(results[index].geometry.location);
             map.setCenter(newpoint);
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
             }*/
            /*    if (this.onMapChange != null) {
             this.onMapChange();
             }*/
        }

        if (this.routediv != null) {
            window.scroll(0, (document.getElementById(this.routediv).offsetTop - 20));
        }
    }
}

JRData.prototype.refreshmap = function () {
    address = this.address;
    loca = this.loca;
    locb = this.locb;
    idzastavky = this.idzastavky
    if (this.flag == true) {
        var self = this;
        var map = null;

        if ((this.routediv != null)) {
            aGeo = document.getElementById(this.routediv);
        } else {
            aGeo = document.getElementById('GeoMap');
        }

        aGeoObal = document.getElementById('divGeo');

        if (aGeo == null) {
            aGeoObal = document.getElementById('divGeo');
            if ((aGeoObal == null)) {
                aGeoObal = document.createElement('div');
                aGeoObal.className = "div_pozadikomplex";
                aGeoObal.style.zIndex = this.ZIndexGeo;
                aGeoObal.onmousedown = function (e) {
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
                nc1.src = main_path + "/image/closebutton.png";
                nc1.onclick = function (e) {
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
                aGeoBottom.style.backgroundImage = "url(" + main_path + "/image/bottomlista.png)";
                aGeoResize = document.createElement('img');
                aGeoResize.style.cssFloat = "right";
                aGeoResize.src = main_path + "/image/resize.png";
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
                    document.onselectstart = function (ev) {
                        return false;
                    };
                }

                if (moveit) {
                    document.onmouseup = function () {
                        moving = false;
                        document.onselectstart = null;
                    };
                }
            } else {
                //      aGeoObal.appendChild(aGeo);
            }

            aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
            aGeoObal.style.visibility = "visible";
        }

        if (moveit) {
            if (aGeoObal != null) {
                aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
                aGeoObal.style.visibility = "visible";
            }
        }
        this.changeZIndexGeo();
        //    aGeo.style.visibility = "visible";
        map = new google.maps.Map(aGeo, this.myOptions);

        if ((loca != null) && (locb != null)) {
            if (loca < locb) {
                poma = loca;
                loca = locb;
                locb = poma;
            }
            var newpoint = new google.maps.LatLng(loca, locb);
            this.geocoder.geocode({
                "location": new google.maps.LatLng(loca, locb)
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var index = -1;
                    for (i = 0; i < results.length; i++) {
                        for (ii = 0; ii < results[i].types.length; ii++) {
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
                    //        map.setCenter(results[0].geometry.location);
                    map.setCenter(newpoint);

                    var contentString = '<div id="content" style="height: 200px;">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h1 id="firstHeading" class="firstHeading">' + address + '</h1>' +
                            '<div id="seznamMap">' +
                            '</div>' + '</div>';
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });
                    /*            '<script id="myscrSeznamZastavkaJR" type="text/javascript" charset="windows-1250" src="http://www.mhdspoje.cz/jrw50/php/loadSeznamZastavkaJSON.php?location="' + self.location + "&packet=" + self.packet +  "&idzastavka=" + idzastavky+'">'+*/
                    var image = main_path + '/image/station2.png';
                    var marker = new google.maps.Marker({
                        map: map,
                        //          position: results[index].geometry.location,
                        position: newpoint,
                        icon: image
                    });

                    google.maps.event.addListener(marker, 'click', function () {
                        self.tagJRSeznamMap = 'seznamMap';
                        infowindow.open(map, marker);
                        //        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
                    });

                    google.maps.event.addListener(infowindow, 'domready', function () {
                        //        self.tagJRSeznamMap = 'seznamMap';
                        //        infowindow.open(map,marker);
                        getSeznamZastavkaJR(self.location, self.packet, self.kalendar.d + "_" + self.kalendar.m + "_" + self.kalendar.y, idzastavky);
                    });

                    //        map.setCenter(results[index].geometry.location);
                    map.setCenter(newpoint);
                }
            });

        } else {
            this.geocoder.geocode({
                'address': address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var index = -1;
                    for (i = 0; i < results.length; i++) {
                        for (ii = 0; ii < results[i].types.length; ii++) {
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
        /*    if (this.onMapChange != null) {
         this.onMapChange();
         }*/
    }
}

JRData.prototype.onLinkaChange = function (location, packet) {
    if (this.loaded) {
        this.disable_all_loaded_element();
        if (this.tagLinkyChange != null) {
            this.tagLinkyChange();
        }
        if (this.tagSmery == null) {
            this.enable_all_loaded_element();
        }
        this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
    }
}

JRData.prototype.onSmerChange = function (location, packet) {
    if (this.loaded) {
        this.disable_all_loaded_element();
        if (this.tagSmeryChange != null) {
            this.tagSmeryChange();
        }
        this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);
    }
}

JRData.prototype.onTrasaChange = function (location, packet) {
    if (this.tagTrasyChange != null) {
        this.tagTrasyChange();
    }
}

JRData.prototype.onSearchResultChange = function (tSearch_result, tZastavky) {
    /*nc = document.getElementById(tSearch_result);
     nc1 = document.getElementById(tZastavky);
     for(i = 0; i < nc1.length; i++) {
     if(nc1[i].value == nc.value) {
     nc1[i].selected = true;
     }
     }*/
}

JRData.prototype.disable_all_loaded_element = function () {
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

JRData.prototype.enable_all_loaded_element = function () {
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

JRData.prototype.disable_all_tag_spojeni = function () {
    if (document.getElementById(this.tagSpojeniOD) != null) {
        document.getElementById(this.tagSpojeniOD).disabled = "disabled";
    }
    if (document.getElementById(this.tagSpojeniDO) != null) {
        document.getElementById(this.tagSpojeniDO).disabled = "disabled";
    }
}

JRData.prototype.enable_all_tag_spojeni = function () {
    if (document.getElementById(this.tagSpojeniOD) != null) {
        document.getElementById(this.tagSpojeniOD).disabled = "";
    }
    if (document.getElementById(this.tagSpojeniDO) != null) {
        document.getElementById(this.tagSpojeniDO).disabled = "";
    }
}

JRData.prototype.komplexJR = function (location, packet, showkurz, packets) {
    this.stopRefreshDenJR();
    this.changeZIndexJR();
    getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
            location, (packet == null) ? this.packet : packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
            0, null, null, null, 0, true, packets, showkurz);
}

JRData.prototype.komplexJRCustom = function (location, packet, linka, smer, tarif_c_zastavky, showkurz, packets) {
    this.changeZIndexJR();
    getJR(linka, smer, tarif_c_zastavky,
            location, (packet == null) ? this.packet : packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
            0, null, null, null, 0, true, packets, showkurz);
}

JRData.prototype.denJR = function (location, packet, showkurz, print) {
    this.changeZIndexJR();
    if (print != 1) {
        getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
                location, (packet == null) ? this.packet : packet, 1, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
                0, null, null, null, 0, true, null, showkurz, null);
        if (location == 6) {
            callfunc = function () {
                getJR(document.getElementById(selfobj.tagLinky).value, document.getElementById(selfobj.tagSmery).value, document.getElementById(selfobj.tagTrasy).value,
                        selfobj.location, selfobj.packet, 1, selfobj.kalendar.d + "_" + selfobj.kalendar.m + "_" + selfobj.kalendar.y,
                        0, null, null, null, 0, false, null, showkurz, null);
            }
            this.startRefreshDenJR(this.callfunc);
        }
    } else {
        printJRden(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
                location, (packet == null) ? this.packet : packet, 1, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
                0, null, null, null, 0, false);
    }
}

JRData.prototype.denJRCustom = function (location, packet, linka, smer, tarif_c_zastavky, showkurz) {
    this.changeZIndexJR();
    getJR(linka, smer, tarif_c_zastavky,
            location, (packet == null) ? this.packet : packet, 1, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
            0, null, null, null, 0, true, null, showkurz);
}

JRData.prototype.sdruzJR = function (location, packet, showkurz, packets) {
    this.stopRefreshDenJR();
    this.changeZIndexJR();
    getJR(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, document.getElementById(this.tagTrasy).value,
            location, (packet == null) ? this.packet : packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
            1, null, null, null, 0, true, packets, showkurz, this.incspoje);
}

JRData.prototype.sdruzJRCustom = function (location, packet, linka, smer, tarif_c_zastavky, showkurz, packets) {
    this.changeZIndexJR();
    getJR(linka, smer, tarif_c_zastavky,
            location, (packet == null) ? this.packet : packet, 0, this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y,
            1, null, null, null, 0, true, packets, showkurz, this.incspoje);
}

JRData.prototype.seznamJR = function (location, packet) {
    getSeznamJR(location, ((packet == null) ? this.packet : packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y);
    this.changeZIndexJRSeznam();
}

JRData.prototype.seznamLinkyJR = function (location, packet) {
    getSeznamJR(location, ((packet == null) ? this.packet : packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y);
    this.changeZIndexJRSeznam();
}

JRData.prototype.seznamZastavkyJR = function (location, packet) {
    getSeznamZastavkyJR(location, ((packet == null) ? this.packet : packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y);
    this.changeZIndexJRSeznam();
}

JRData.prototype.seznamKurzy = function (location, packet) {
    getSeznamKurzy(location, ((packet == null) ? this.packet : packet), document.getElementById(this.tagLinky).value);
    this.changeZIndexJRSeznam();
}

JRData.prototype.spojeniResult = function (location, packet, hh, mm, prime, pp) {
    //  if (location != 1) {
    if ((document.getElementById(this.tagSpojeniOD).value >= 0) && (document.getElementById(this.tagSpojeniDO).value >= 0) && (document.getElementById(this.tagSpojeniOD).value != document.getElementById(this.tagSpojeniDO).value)) {
        if ((this.tagJR != '') && (this.location == 6)) {
            document.getElementById(this.tagJR).innerHTML = '<center><img style="padding-top: 20px; padding-bottom: 20px" src="' + main_path + '/image/loader7.gif"></center>';
        }
        getSpojeniResult(location, ((packet == null) ? this.packet : packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, hh, mm, document.getElementById(this.tagSpojeniOD).value, document.getElementById(this.tagSpojeniDO).value, prime, pp);
        this.changeZIndexJRSeznam();
    }
//  }
}

JRData.prototype.odjezdyResult = function (location, packet) {
    //  if (location != 1) {
    if (document.getElementById(this.tagOdjezdy).value >= 0) {
        if (this.tagJR != null) {
            document.getElementById(this.tagJR).innerHTML = '<center><img style="padding-top: 20px; padding-bottom: 20px" src="' + main_path + '/image/loader7.gif"></center>';
        }
        getOdjezdyResult(location, ((packet == null) ? this.packet : packet), this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y, document.getElementById(this.tagOdjezdy).value);
        this.changeZIndexJRSeznam();
    }
//  }
}

JRData.prototype.spojeniResultotherdatum = function (location, packet, hh, mm, kalend) {
    packet = this.getAktualPacketDatum(kalend);
    getSpojeniResult(location, ((packet == null) ? this.packet : packet), kalend.d + "_" + kalend.m + "_" + kalend.y, hh, mm, document.getElementById(this.tagSpojeniOD).value, document.getElementById(this.tagSpojeniDO).value);
    this.changeZIndexJRSeznam();
}

function getJRData(data) {
    /*  document.getElementById(selfobj.tagJR).style.width = "300px";
     document.getElementById(selfobj.tagJR).style.height = "250px";
     document.getElementById(selfobj.tagJR).style.overflow = "auto";*/
    if (selfobj.refreshJR != null) {
        
    } else {
        if (selfobj.version == 51) {
            if (selfobj.routediv != null) {
                document.getElementById(selfobj.routediv).style.height = '0px';
            }
        }
    }

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
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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
    if (selfobj.onLoadJR != null) {
        selfobj.onLoadJR();
    }
    if (selfobj.onJRChange != null) {
        selfobj.onJRChange();
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

function getJRDataAndroid(data) {
    JRElement = document.getElementById("tagJR");
    if (JRElement == null) {
        JRElement = document.createElement("div");
        document.body.appendChild(JRElement);
    }
    JRElement.innerHTML = data;
}

function printJRData(data) {
    w = window.open("toolbar=0, location = 0, menubar = 0, resizable = 1, scrollbars = 1");
    w.document.open();
    w.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
    w.document.write('<meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>');
    /*  w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/menuDecin.css"/>');
     w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRDecin.css"/>');*/
    if (selfobj.location == 6) {
        w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRBlava/Timetables.css"/>');
    }
    if (selfobj.location == 12) {
        w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRChomutov/Timetables.css"/>');
    }
    if (selfobj.location == 11) {
        w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JROpava/Timetables.css"/>');
    }
    if (selfobj.location == 2) {
        w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRDecin/JRDecin.css"/>');
    }
    if (selfobj.location == 1) {
        w.document.write('<link rel="stylesheet" type="text/css" href="//www.mhdspoje.cz/jrw50/css/JRTeplice/JRTeplice.css"/>');
    }
    w.document.write('<style TYPE="text/css">body {font-family: sans-serif; font-size: 10px;}</style>');
    w.document.write('<body onload="print();">');
    w.document.write(data);
    w.document.write('</body>');
    w.document.close();
//w.print();
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

        document.getElementById(selfobj.tagJR).style.top = ScrollXY()[1] + 20 + "px";
    }
    document.getElementById(selfobj.tagJR).style.visibility = 'visible';
    /*if (moveit) {
        document.getElementById('movediv').onmousedown = function mouseDown(ev) {
            tag = 0;
            moving = true;
            ev = ev || window.event;
            var mousePos = mouseCoords(ev);
            mx = mousePos.x;
            my = mousePos.y;
            moving = true;
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }*/

    if (moveit) {
        document.onmouseup = function () {
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
    if (selfobj.onJRChange != null) {
        selfobj.onJRChange();
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
    if (selfobj.version == 51) {
        if (selfobj.routediv != null) {
            document.getElementById(selfobj.routediv).style.height = '0px';
        }
    }
    document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
    document.getElementById(selfobj.tagJRSeznam).style.width = "auto";
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
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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
    if (selfobj.version == 51) {
        if (selfobj.routediv != null) {
            document.getElementById(selfobj.routediv).style.height = '0px';
        }
    }
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
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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

        document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
    }

    document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';

    /*if (moveit) {
        document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
            tag = 2;
            moving = true;
            ev = ev || window.event;
            var mousePos = mouseCoords(ev);
            mx = mousePos.x;
            my = mousePos.y;
            moving = true;
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }*/

    if (moveit) {
        document.onmouseup = function () {
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
    if (selfobj.version == 51) {
        document.getElementById(selfobj.routediv).style.height = '0px';
    }
    document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
    selfobj.changeZIndexJR();
    if (moveit) {
        newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
        if (newLeft < 0) {
            newLeft = 0;
        }
        document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";

        document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 20 + "px";
        document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';
    }

    if (moveit) {
        document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
            tag = 2;
            moving = true;
            ev = ev || window.event;
            var mousePos = mouseCoords(ev);
            mx = mousePos.x;
            my = mousePos.y;
            moving = true;
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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

function getOdjezdyResultData(data, nametag) {
    if (nametag != null) {
        if (document.getElementById(nametag) != null) {
            document.getElementById(nametag).innerHTML = data;
        }
    } else {
        if (selfobj.version == 51) {
            document.getElementById(selfobj.routediv).style.height = '0px';
        }
        document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
    }
    selfobj.changeZIndexJR();
    if (moveit) {
        newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
        if (newLeft < 0) {
            newLeft = 0;
        }
        document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";

        document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 40 + "px";
        document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';
    }

    if (moveit) {
        document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
            tag = 2;
            moving = true;
            ev = ev || window.event;
            var mousePos = mouseCoords(ev);
            mx = mousePos.x;
            my = mousePos.y;
            moving = true;
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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

function getJR(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow, packets, showkurz, incspoje, print) {
    selfobj.stopRefreshDenJR();
    callfunc = null;
    if (showkurz == null) {
        showkurz = 0;
    }
    if (showkurz != 1) {
        showkurz = 0;
    }
    if (packets != null) {
    } else
        packets = 0;
    sel = sel || -1;
    selfobj.deleteonshow = adeleteonshow || false;
    if (selfobj.deleteonshow == true) {
        document.getElementById(selfobj.tagJR).innerHTML = "";
        document.getElementById(selfobj.tagJR).style.visibility = 'hidden';
    }
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    if (location == 6) {
        showp = 1;
    } else {
        showp = 0
    }
    if ((x == null) || (y == null) || (sloupec == null)) {
        fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&packets=" + packets + "&kurz=" + showkurz + "&incspoje=" + incspoje + "&lang=" + selfobj.lang + "&showp=" + showp + "&callback=getJRData" + ((print != null) ? "&print=1" : "");
    } else {
        fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&packets=" + packets + "&kurz=" + showkurz + "&incspoje=" + incspoje + "&lang=" + selfobj.lang + "&showp=" + showp + "&callback=getJRData" + ((print != null) ? "&print=1" : "");
    }
    if (location == 6) {
        if (denJR == 1) {
            callfunc = function () {
                getJR(linka, smer, tarif,
                    location, packet, 1, datum,
                    0, sloupec, x, y, 0, sel, null, showkurz, null);
            }
        } else {
            selfobj.stopRefreshDenJR();
        }
    } 
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrJR");
    delObj = document.getElementById("myscrJR");
    if (delObj != null) {
        document.body.removeChild(delObj);
    }
    document.body.appendChild(scriptObj);
//  selfobj.changeZIndexJR();
}

function getDepartureBoard(location) {
    if (document.getElementById(selfobj.tagOdjezdy) != null) {

        if (selfobj.tagJR != null) {
            document.getElementById(selfobj.tagJR).innerHTML = '<center><img style="padding-top: 20px; padding-bottom: 20px" src="' + main_path + '/image/loader7.gif"></center>';
        }

        scriptObj = document.createElement("script");
        scriptObj.setAttribute("type", "text/javascript");
        scriptObj.setAttribute("charset", "windows-1250");

        path = main_path + "/php/";

        var zast = document.getElementById(selfobj.tagOdjezdy).value;
        var passport = -1;
        for (i = 0; i < selfobj.stanice.length; i++) {
            if (zast == selfobj.stanice[i][3]) {
                passport = selfobj.stanice[i][4]
            }
        }
        noCacheIE = '&noCacheIE=' + (new Date()).getTime();
        fullUrl = path + "DepartureBoard.php?location=" + location + "&passport=" + passport + "&lang=" + selfobj.lang + "&callback=getDepartureBoardData";
        scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
        scriptObj.setAttribute("id", "myscrJR");
        if (passport > -1) {
            document.body.appendChild(scriptObj);
        }
    }
}

function getDepartureBoardPassport(location, pass) {
//    if (document.getElementById(selfobj.tagOdjezdy) != null) {

/*        if (selfobj.tagJR != null) {
            document.getElementById(selfobj.tagJR).innerHTML = '<center><img style="padding-top: 20px; padding-bottom: 20px" src="//www.mhdspoje.cz/jrw50/image/loader7.gif"></center>';
        }*/

            
        scriptObj = document.createElement("script");
        
        scriptObj.setAttribute("type", "text/javascript");
        scriptObj.setAttribute("charset", "windows-1250");

        path = main_path + "/php/";

        var passport = '';
        for (i = 0; i < selfobj.stanice.length; i++) {
            if (pass == selfobj.stanice[i][4]) {
                passport = selfobj.stanice[i][0];
                break;
            }
        }
        
        noCacheIE = '&noCacheIE=' + (new Date()).getTime();
        fullUrl = path + "DepartureBoardTablo.php?location=" + location + "&packet=" + selfobj.packet + "&passport=" + pass + "&passportname=" + passport + "&lang=" + selfobj.lang + "&callback=getDepartureBoardPassportData";
        scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
        scriptObj.setAttribute("id", "myscrJR");
        if (pass > -1) {
            delObj = document.getElementById("myscrJR");
            if (delObj != null) {
                document.body.removeChild(delObj);
            }
            document.body.appendChild(scriptObj);
        }                
//    }
}

function getDepartureBoardData(data) {
    var obj = data;
    if (selfobj.tagJR != null) {
        document.getElementById(selfobj.tagJR).innerHTML = '<center><img style="padding-top: 20px; padding-bottom: 20px" src="' + main_path + '/image/loader7.gif"></center>';
        if (document.getElementById(selfobj.routediv) != null) {
            document.getElementById(selfobj.routediv).style.height = '0px';
        }
        document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
        selfobj.changeZIndexJR();
    }

    if (moveit) {
        newLeft = (screen.width - document.getElementById(selfobj.tagJRSeznam).offsetWidth) / 2;
        if (newLeft < 0) {
            newLeft = 0;
        }
        document.getElementById(selfobj.tagJRSeznam).style.left = newLeft + "px";

        document.getElementById(selfobj.tagJRSeznam).style.top = ScrollXY()[1] + 40 + "px";
        document.getElementById(selfobj.tagJRSeznam).style.visibility = 'visible';
    }

    if (moveit) {
        document.getElementById('movedivSeznam').onmousedown = function mouseDown2(ev) {
            tag = 2;
            moving = true;
            ev = ev || window.event;
            var mousePos = mouseCoords(ev);
            mx = mousePos.x;
            my = mousePos.y;
            moving = true;
            document.onselectstart = function (ev) {
                return false;
            };
        }
    }

    if (moveit) {
        document.onmouseup = function () {
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

function getDepartureBoardPassportData(data) {
    var obj = data;
    if (selfobj.tagJR != null) {
        document.getElementById(selfobj.tagJRSeznam).innerHTML = data;
        selfobj.changeZIndexJR();
    }
}

function getDepartureMap(location, typemap, transporttype, linename) {
    if (typemap == null) {
        typemap = 0;
    }
    if (transporttype == null) {
        transporttype = null;
    }
    if (linename == null) {
        linename = null;
    } else {
        if (selfobj.linky != null) {
            var linka = null;
            for (i = 0; i < selfobj.linky.length; i++) {
                if (selfobj.linky[i][0] == linename) {
                    linka = selfobj.linky[i][1];
                }
            }
            if (linka != null) {
                linename = linka.trim();
            }
        }
    }
    if (selfobj.tagOdjezdy != null) {
        var idzastavky = document.getElementById(selfobj.tagOdjezdy).value;
        if (selfobj.stanice != null) {
            for (i = 0; i < selfobj.stanice.length; i++) {
                if (idzastavky == selfobj.stanice[i][3]) {
                    selfobj.centerX = selfobj.stanice[i][2];
                    selfobj.centerY = selfobj.stanice[i][1];
                }
            }
        } else {
            selfobj.centerX = null;
            selfobj.centerY = null;
        }
    }
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");

    path = main_path + "/php/";

    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = path + "DepartureMap.php?location=" + location + "&x=" + selfobj.centerX + "&y=" + selfobj.centerY + "&typemap=" + typemap + "&transporttype=" + transporttype + "&linename=" + linename + "&callback=getDepartureMapData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrJR");
    document.body.appendChild(scriptObj);
}

function DepartureMapLoaded() {
    //document.getElementById(selfobj.routediv).innerHTML = selfobj.departuremapobject;
    if ((selfobj.centerX != null) && (selfobj.centerY != null)) {
//    departureMap.setMapCenter(selfobj.centerX, selfobj.centerY);
    }
}

function getDepartureMapData(data) {
    aGeo = document.getElementById(selfobj.routediv);
    if (aGeo != null) {
        aGeo.style.height = "500px";
    }
    //  selfobj.departuremapobject = '<object id="objectmap" type="text/html" style="width: 100%; height: 500px; overflow: hidden;" data="' + data + '" onload = "DepartureMapLoaded()";></object>';
    if (selfobj.routediv != null) {
        document.getElementById(selfobj.routediv).innerHTML = '<object id="objectmap" type="text/html" style="width: 100%; height: 500px; overflow: hidden;" data="' + data + '" onload = "DepartureMapLoaded()";></object>';
    }
//document.getElementById(selfobj.routediv).innerHTML = '<iframe type="text/html" style="width: 100%; height: 500px; overflow: hidden;" src="' + data + '"></iframe>';
//document.getElementById(selfobj.routediv).innerHTML = '<iframe width="100%" height="100%" style="overflow: hidden;" src="' + data + '"></iframe>';
}

function getJR_Android(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow, packets, showkurz, incspoje) {
    if (showkurz == null) {
        showkurz = 0;
    }
    if (showkurz != 1) {
        showkurz = 0;
    }
    if (packets != null) {
    } else
        packets = 0;
    sel = sel || -1;

    scriptObj = document.getElementById('SrcJR');
    if (scriptObj != null) {
        document.removeChild(oldChild);
    }
    oldChild = null;
    if (scriptObj == null) {
        scriptObj = document.createElement("script");
        scriptObj.setAttribute("id", "SrcJR");
        scriptObj.setAttribute("type", "text/javascript");
        scriptObj.setAttribute("charset", "windows-1250");
    }

    path = main_path + "/php/";

    if ((x == null) || (y == null) || (sloupec == null)) {
        fullUrl = path + "loadJRJSON_Android.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&packets=" + packets + "&kurz=" + showkurz + "&incspoje=" + incspoje + "&lang=cz&callback=getJRDataAndroid";
    } else {
        fullUrl = path + "loadJRJSON_Android.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&packets=" + packets + "&kurz=" + showkurz + "&incspoje=" + incspoje + "&lang=cz&callback=getJRDataAndriod";
    }
    scriptObj.setAttribute("src", fullUrl);
    document.body.appendChild(scriptObj);
//  selfobj.changeZIndexJR();
}

function getJRalone(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow, packets) {
    if (packets != null) {
    } else
        packets = 0;

    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    if ((x == null) || (y == null) || (sloupec == null)) {
        fullUrl = main_path + "/php/loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&packets=" + packets + "&callback=getJRDataalone";
    } else {
        fullUrl = main_path + "/php/loadJRJSON.php?linka=" + linka +
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
    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    if ((x == null) || (y == null) || (sloupec == null)) {
        fullUrl = /*/www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&callback=printJRData&print=1";
    } else {
        fullUrl = /*/www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&denni=" + denJR + "&datum=" + datum + "&sdruz=" + sdruzJR + "&sel=" + sel + "&jrtype=" + sloupec + "&x=" + x + "&y=" + y + "&callback=printJRData&print=1";
    }
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrJR");
    document.body.appendChild(scriptObj);
//  selfobj.changeZIndexJR();
}

function printJRden(linka, smer, tarif, location, packet, denJR, datum, sdruzJR, sloupec, x, y, sel, adeleteonshow) {
    sel = sel || -1;
    /*  selfobj.deleteonshow = adeleteonshow || false;
     if (selfobj.deleteonshow == true) {
     document.getElementById(selfobj.tagJR).innerHTML = "";
     document.getElementById(selfobj.tagJR).style.visibility = 'hidden';
     }*/
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    if ((x == null) || (y == null) || (sloupec == null)) {
        fullUrl = /*//www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
                "&smer=" + smer + "&tarif=" + tarif + "&location=" + location +
                "&packet=" + packet + "&datum=" + datum + "&denni=" + denJR + "&sdruz=" + sdruzJR + "&sel=" + sel + "&callback=printJRData&print=1";
    } else {
        fullUrl = /*/www.mhdspoje.cz/jrw50/php/*/path + "loadJRJSON.php?linka=" + linka +
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

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadSeznamJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&callback=getSeznamJRData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSeznamJR");
    document.body.appendChild(scriptObj);
}

function getSeznamZastavkyJR(location, packet, datum) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadSeznamZastavkyJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&callback=getSeznamZastavkyJRData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSeznamZastavkyJR");
    document.body.appendChild(scriptObj);
}

function getSeznamZastavkaJR(location, packet, datum, idzastavky) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"//www.mhdspoje.cz/jrw50/php/*/path + "loadSeznamZastavkaJSON.php?location=" + location + "&packet=" + packet + "&datum=" + datum + "&idzastavka=" + idzastavky + "&callback=getSeznamZastavkaJRData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSeznamZastavkaJR");
    document.body.appendChild(scriptObj);
}

function getSeznamKurzy(location, packet, linka) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "LoadVozakSeznamJSON.php?location=" + location + "&packet=" + packet + "&linka=" + linka + "&callback=getSeznamKurzyData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSeznamKurzy");
    document.body.appendChild(scriptObj);
}

function getVozak(location, packet, linka, kurz, kurzname, kodpozn) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "LoadVozakJSON.php?location=" + location + "&packet=" + packet + "&linka=" + linka + "&kurz=" + kurz + "&kurzname=" + kurzname + "&kodpozn=" + kodpozn + "&callback=getVozakData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrJR");
    document.body.appendChild(scriptObj);
}

function getSpojeniResult(location, packet, datum, hh, mm, iOD, iDO, prime, pp) {
    //  var add = false;
    scriptObjold = document.getElementById("myscrSpojeniResult");
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    if (prime == null) {
        prime = 0;
    }

    path = main_path + "/php/";
    if ((selfobj.version == 51) || (location == 2) || (location == 7)) {
        path = path + "5_1/";
    }

    /*if (location == -1) {
     fullUrl = path + "loadSpojeniResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
     } else {
     fullUrl = "http://www.mhdspoje.cz/jrw50/php/oldspojeni/pokusdotaz.php?loc=" + location + "&z1=" + iOD + "&z2=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&po=1&move=false&pac=" + packet + "&callback=getSpojeniResultData";
     }*/

    //  if ((location == 11) || (location == 3) || (location == 7) || (location == 19) || (location == 12)) {
    fullUrl = path + "loadConnectionResultJSON_1.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&prime=" + prime + "&PP=" + pp + "&callback=getSpojeniResultData";
    //fullUrl = path + "loadConnectionResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
    //}
    //    fullUrl = path + "spojeni_new_1.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
    //  } else {
    //    fullUrl = "http://www.mhdspoje.cz/jrw50/php/oldspojeni/pokusdotaz.php?loc=" + location + "&z1=" + iOD + "&z2=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&po=1&move=false&pac=" + packet + "&callback=getSpojeniResultData";
    //  }
    //    fullUrl = "http://www.mhdspoje.cz/jrw50/php/oldspojeni/pokusdotaz.php?loc=" + location + "&z1=" + iOD + "&z2=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&po=1&move=false&pac=" + packet + "&callback=getSpojeniResultData";
    //  fullUrl = path + "loadSpojeniResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
    //  fullUrl = "http://www.mhdspoje.cz/jrw50/php/pokus.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&callback=getSpojeniResultData";
    // } else {
    //fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadSpojeniResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
    //  fullUrl = "http://www.mhdspoje.cz/jrw50/php/pokus.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&callback=getSpojeniResultData";
    //}
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSpojeniResult");
    if (scriptObjold == null) {
        document.body.appendChild(scriptObj);
    } else {
        scriptObjold.parentNode.replaceChild(scriptObj, scriptObjold);
    }
}

function getOdjezdyResult(location, packet, datum, iOD, tagname) {
    //  var add = false;
    scriptObjold = document.getElementById("myscrOdjezdyResult");
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    /*if (location == -1) {
     fullUrl = path + "loadSpojeniResultJSON.php?location=" + location + "&pocatek=" + iOD + "&cil=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&packet=" + packet + "&dobaS=120&pocetP=10" + "&lang=" + selfobj.lang + "&callback=getSpojeniResultData";
     } else {
     fullUrl = "http://www.mhdspoje.cz/jrw50/php/oldspojeni/pokusdotaz.php?loc=" + location + "&z1=" + iOD + "&z2=" + iDO + "&h=" + hh + "&m=" + mm + "&datum=" + datum + "&po=1&move=false&pac=" + packet + "&callback=getSpojeniResultData";
     }*/

    fullUrl = path + "loadOdjezdy.php?location=" + location + "&packet=" + packet + "&zastavka=" + iOD + "&datum=" + datum + "&lang=" + selfobj.lang + "&callback=getOdjezdyResultData" + (tagname == null ? "" : "&tag=" + tagname);

    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrOdjezdyResult");
    if (scriptObjold == null) {
        document.body.appendChild(scriptObj);
    } else {
        scriptObjold.parentNode.replaceChild(scriptObj, scriptObjold);
    }
}

JRData.prototype.getPacketData = function (data) {
    var pp = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while (a[0]) {
        pp.push(a.splice(0, 8));
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
    if (this.execAction == 5) {
        getDepartureMap(this.location);
        getDepartureBoard(this.location);
    }
/*    if (this.execAction == 11) {
        if (this.version == 51) {
            this.getStaniceList(this.location, this.packet);
        }
    }*/
    if (this.loaded == false) {
        this.getLinkyList(this.location, this.packet, this.tagLinky);
        if (this.version == 51) {
            this.getStaniceList(this.location, this.packet);
        }
        this.loaded = true;
    } else {
        this.enable_all_loaded_element();
    }
    if (this.loadedSpojeniData == false) {
        this.disable_all_tag_spojeni();
        this.getSpojeniList(this.location, this.tagSpojeniOD, this.tagSpojeniDO);
        this.loadedSpojeniData = true;
    }

    if (this.tagOdjezdy != null) {
        this.getOdjezdyList(this.location, this.tagOdjezdy);
    }

    if (this.kalendar.tagTextDatumChange != null) {
        this.kalendar.tagTextDatumChange(this.kalendar.JR);
    }
}

JRData.prototype.getPacketList = function (location) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = main_path + "/php/5_1/ListPacketJSON.php?location=" + location + "&callback=getPacketData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrPacket");
    document.body.appendChild(scriptObj);
}

JRData.prototype.getStaniceData = function (location, packet, data) {
    var pp = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while (a[0]) {
        pp.push(a.splice(0, 5));
    }
    for (i = 0; i < pp.length; i++) {
        pp[i][0] = pp[i][0].replace(/[|]/g, ",");
        pp[i][5] = -1;
        pp[i][6] = -1;
        /*      this.stanice[i][0] = pp[i][0];
         this.stanice[i][1] = pp[i][1];
         this.stanice[i][2] = pp[i][2];*/
    }
    for (i = 0; i < pp.length; i++) {
        if ((pp[i][1] > 0) || (pp[i][2] > 0)) {
            if (pp[i][1] < pp[i][2]) {
                var premo = pp[i][1];
                pp[i][1] = pp[i][2];
                pp[i][2] = premo;
            }
        }
    }
    this.stanice = pp;
    this.vyhledane_stanice = pp;
    if (this.execAction == 4) {
        this.mapAllStops(null, null, null, null, 1);
    }
    if (this.execAction == 9) {
        this.mapAllStops(null, null, null, null, 1, 1);
    }
    if (this.execAction == 6) {
        if (this.location == 6) {
            getDepartureBoard(location);
        }
    }
    if (this.execAction == 11) {
        getDepartureBoardPassport(location, this.passBoard);
            callfuncBoard = function () {
                getDepartureBoardPassport(location, selfobj.passBoard);
            }
            this.startRefreshBoard(this.callfuncBoard);

    }
    this.enable_all_loaded_element();
}

JRData.prototype.getStaniceList = function (location, packet) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"/www.mhdspoje.cz/jrw50/php/*/path + "ListStaniceJSON.php?location=" + location + "&packet=" + packet + "&callback=getStaniceData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrStanice");
    document.body.appendChild(scriptObj);
}

JRData.prototype.getLinkyData = function (location, packet, comboName, data) {
    if (document.getElementById(comboName) != null) {
        var pp = [];
        var a = (eval(data)).toString();
        a = a.split(',');
        while (a[0]) {
            pp.push(a.splice(0, 2));
        }
        nc = document.getElementById(comboName);
        nc.options.length = 0;
        active = 0;
        index = 0;
        this.linky = [];
        for (i = 0; i < pp.length; i++) {
            pp[i][1] = pp[i][1].replace(/[|]/g, ",")
            nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
            this.linky[i] = pp[i];
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

JRData.prototype.getLinkyList = function (location, packet, comboName) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    if (this.PTLine == true) {
        ptl = 1;
    } else {
        ptl = 0;
    }
    fullUrl = main_path + "/php/5_1/ListLinkyJSON.php?location=" + location + "&packet=" + packet + "&datum=" + this.kalendar.d + "_" + this.kalendar.m + "_" + this.kalendar.y + "&target=" + comboName + "&ptl=" + ptl + "&callback=getLinkyData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrLinky");
    document.body.appendChild(scriptObj);
//  document.body.removeChild(scriptObj);
//  this.getSmeryList(document.getElementById(this.tagLinky).value, location, packet, this.tagSmery);
}

JRData.prototype.getSmeryData = function (location, packet, comboName, data) {
    if (document.getElementById(comboName) != null) {
        var pp = [];
        var a = (eval(data)).toString();
        a = a.split(',');
        while (a[0]) {
            pp.push(a.splice(0, 3));
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
        if (this.oldSmer != null) {
            nc.selectedIndex = this.oldSmer;
        }
        nc = null;
        if (this.tagSmeryChange != null) {
            this.tagSmeryChange();
        }
        this.getTrasyList(document.getElementById(this.tagLinky).value, document.getElementById(this.tagSmery).value, location, packet, this.tagTrasy);
    }
}

JRData.prototype.getSmeryList = function (linka, location, packet, comboName) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = main_path + "/php/5_1/ListSmeryJSON.php?linka=" + linka + "&location=" + location + "&packet=" + packet + "&target=" + comboName + "&callback=getSmeryData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSmery");
    delObj = document.getElementById("myscrSmery");
    if (delObj != null) {
        document.body.removeChild(delObj);
    }
    document.body.appendChild(scriptObj);
}

JRData.prototype.getTrasyData = function (location, packet, comboName, data) {
    if (document.getElementById(comboName) != null) {
        var pp = [];
        var a = (eval(data)).toString();
        a = a.split(',');
        while (a[0]) {
            pp.push(a.splice(0, 3));
        }
        nc = document.getElementById(comboName);
        nc.options.length = 0;
        var select_index = -1;
        for (i = 0; i < pp.length; i++) {
            pp[i][2] = pp[i][2].replace(/[|]/g, ",");
            pp[i][1] = pp[i][1].replace(/[|]/g, ",");
            pp[i][0] = pp[i][0].replace(/[|]/g, ",");
            nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
            nc.options[i].disabled = ((pp[i][2] == 1) ? false : true);
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
    if (this.execAction == 8) {
        this.denJR(this.location, this.packet);
    }
}


JRData.prototype.getTrasyList = function (linka, smer, location, packet, comboName) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = main_path + "/php/5_1/ListTrasyJSON.php?linka=" + linka + "&smer=" + smer + "&location=" + location + "&packet=" + packet + "&target=" + comboName + "&lang=" + this.lang + "&callback=getTrasyData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrTrasy");
    delObj = document.getElementById("myscrTrasy");
    if (delObj != null) {
        document.body.removeChild(delObj);
    }
    document.body.appendChild(scriptObj);
}

JRData.prototype.getSpojeniListData = function (location, packet, comboNameOD, comboNameDO, data) {
    if ((document.getElementById(comboNameOD) != null) && (document.getElementById(comboNameDO) != null)) {
        var pp = [];
        var a = (eval(data)).toString();
        a = a.split(',');
        while (a[0]) {
            pp.push(a.splice(0, 2));
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
        if (this.spojeni_index_od != null) {
            var si = 0;
            for (i = 0; i < pp.length; i++) {
                if (this.spojeni_index_od == pp[i][0]) {
                    si = i;
                }
            }
            this.spojeni_index_od = si;
            nc.selectedIndex = this.spojeni_index_od;//select_index;
        } else {
            nc.selectedIndex = 0;
        }
        nc = null;
        if (this.spojeni_index_do != null) {
            var si = 1;
            for (i = 0; i < pp.length; i++) {
                if (this.spojeni_index_do == pp[i][0]) {
                    si = i;
                }
            }
            this.spojeni_index_do = si;
            nc1.selectedIndex = this.spojeni_index_do;//select_index;
        } else {
            nc1.selectedIndex = 1;//select_index;
        }
        nc1 = null;
        this.spojeni_index_od = null;
        this.spojeni_index_do = null;
    }
    this.getStaniceList(this.location, this.packet);
    this.enable_all_tag_spojeni();
    if (this.execAction == 7) {
        JR.spojeniResult(vlocation, vpacket, this.time.getHH(), this.time.getMM(), this.spojeni_prime);
    }
}

JRData.prototype.setTimetag = function (t) {
    this.time = t;
}

JRData.prototype.getOdjezdyListData = function (location, packet, comboNameZastavky, data) {
    if ((document.getElementById(comboNameZastavky) != null) && (document.getElementById(comboNameZastavky) != null)) {
        var pp = [];
        var a = (eval(data)).toString();
        a = a.split(',');
        while (a[0]) {
            pp.push(a.splice(0, 2));
        }
        nc = document.getElementById(comboNameZastavky);
        nc.options.length = 0;

        var ind = 0;
        for (i = 0; i < pp.length; i++) {
            pp[i][1] = pp[i][1].replace(/[|]/g, ",");
            pp[i][0] = pp[i][0].replace(/[|]/g, ",");
            nc.options[i] = new Option(pp[i][1].toString(), pp[i][0].toString());
            if (this.odjezdyindex != null) {
                if (this.odjezdyindex == pp[i][0]) {
                    ind = i;
                }
            }
        }
        if (this.odjezdyindex != null) {
            nc.selectedIndex = ind;
        } else {
            nc.selectedIndex = 0;//select_index;
        }
        nc = null;
    }
    this.enable_all_tag_spojeni();

    this.getStaniceList(this.location, this.packet);

    if (this.execAction == 6) {
        if (this.location == 6) {
            //      getDepartureBoard(location);
        } else {
            this.odjezdyResult(location, packet);
        }
    }
}


JRData.prototype.getSpojeniList = function (location, comboNameOD, comboNameDO) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = main_path + "/php/5_1/ListSpojeniJSON.php?location=" + location + "&packet=" + this.packet + "&target1=" + comboNameOD + "&target2=" + comboNameDO + "&callback=getSpojeniListData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrSpojeni");
    document.body.appendChild(scriptObj);
}

JRData.prototype.getOdjezdyList = function (location, comboNameZastavky) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    fullUrl = main_path + "/php/5_1/ListOdjezdyJSON.php?location=" + location + "&packet=" + this.packet + "&target=" + comboNameZastavky + "&callback=getOdjezdyListData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrOdjezdy");
    document.body.appendChild(scriptObj);
}

JRData.prototype.getRouteData = function (data) {
    //  if ((loca != null) && (locb != null)) {
    var pp = [];
    var origin = null;
    var destination = null;
    var waypoints = [];
    var a = (eval(data)).toString();
    a = a.split(',');
    while (a[0]) {
        pp.push(a.splice(0, 6));
    }
    for (i = 0; i < pp.length; i++) {
        pp[i][5] = pp[i][5].replace(/[|]/g, ",");
        pp[i][4] = pp[i][4].replace(/[|]/g, ",");
        pp[i][3] = pp[i][3].replace(/[|]/g, ",");
        pp[i][2] = pp[i][2].replace(/[|]/g, ",");
        pp[i][1] = pp[i][1].replace(/[|]/g, ",");
        pp[i][0] = pp[i][0].replace(/[|]/g, ",");
    }

    var self = this;
    aGeo = document.getElementById(this.routediv);

    if (aGeo != null) {
        aGeo.style.height = "500px";
    }

    /*  if (moveit) {
     if (aGeo != null) {
     closeGeo();
     document.removeChild(aGeo);
     }
     aGeo = null;
     }*/
    if (aGeo == null) {
        aGeoObal = document.getElementById('divGeo');
        if (moveit) {
            if (aGeoObal != null) {
                //        document.removeChild(oldChild)
                //        aGeoObal.removeChild(document.getElementById('GeoMap'));
                //        aGeoObal.removeChild(document.getElementById('moveGeo'));
                /*        document.getElementById('GeoMap')
                 document.getElementById('moveGeo').remove();*/
                document.getElementById('divGeo').parentNode.removeChild(document.getElementById('divGeo'));
                //document.removeChild(document.getElementById('divGeo'));
            }
            aGeoObal = null;
        }
        if (aGeoObal == null) {
            aGeoObal = document.createElement('div');
            aGeoObal.className = "div_pozadikomplex";
            aGeoObal.style.zIndex = this.ZIndexGeo;
            aGeoObal.onmousedown = function (e) {
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
            nc1.src = main_path + "/image/closebutton.png";
            nc1.onclick = function (e) {
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
                document.onselectstart = function (ev) {
                    return false;
                };
            }
        }
    }

    //aGeo.innerHTML = '';

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
        } catch (e) {
        }
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
        document.onmouseup = function () {
            moving = false;
            document.onselectstart = null;
        };
    }


    /*  this.changeZIndexGeo();
     aGeoObal.style.top = ScrollXY()[1] + 20 + "px";
     aGeoObal.style.visibility = "visible";*/

    var map = new google.maps.Map(aGeo, this.myOptionsRoute);
    this.sharedmap = map;
    //  var directionsDisplay = new google.maps.DirectionsRenderer();
    //  var directionsService = new google.maps.DirectionsService();
    //  directionsDisplay.setMap(map);
    //  directionsDisplay.setPanel(document.getElementById("directionsPanel"));
    var markersinfo = [];
    var lineCoords = [];
    var num_zastavky = 0;
    for (i = 0; i < pp.length; i++) {
        pp[i][5] = pp[i][5].replace(/[|]/g, ",");
        pp[i][4] = pp[i][4].replace(/[|]/g, ",");
        pp[i][3] = pp[i][3].replace(/[|]/g, ",");
        pp[i][2] = pp[i][2].replace(/[|]/g, ",");
        pp[i][1] = pp[i][1].replace(/[|]/g, ",");
        pp[i][0] = pp[i][0].replace(/[|]/g, ",");

        if (pp[i][1] < pp[i][2]) {
            poma = pp[i][1];
            pp[i][1] = pp[i][2];
            pp[i][2] = poma;
        }

        var lokace = new google.maps.LatLng(pp[i][1], pp[i][2]);
        //    var loc = [];
        //    loc[0] = pp[i][1];
        //    loc[1] = pp[i][2];

        /*    if ((i >= 0) && (i < 5)) {
         waypoints.push({
         location: new google.maps.LatLng(pp[i][1], pp[i][2]),
         stopover: true
         });
         }*/

        num_zastavky++;
        if (pp[i][4] == 1) {
            lineCoords.push(lokace);
            var contentString = '<div id="content" style="height: 200px;">' +
                    '<div id="siteNotice">' +
                    '</div>' +
                    '<h1 id="firstHeading" class="firstHeading">' + pp[i][3] + '</h1>' +
                    '<div style="padding-top: 30px; font-weight: bold;" id="seznamMap">Linka&nbsp;:&nbsp;' + pp[i][5] + '</div>';
            var defcodepage = document.charset;
            if (defcodepage == 'UTF-8') {
                var charset = 'UTF';
            }
            if (charset == 'UTF') {
                if (this.lang == 'sk') {
                    var output = info_1_UTF_SK;
                } else {
                    var output = info_1_UTF_CZ;
                }
            } else {
                if (this.lang == 'sk') {
                    var output = info_1_W1250_SK;
                } else {
                    var output = info_1_W1250_CZ;
                }
            }

            if (i == 0) {
                contentString = contentString + '<div style="padding-top: 30px; font-weight: bold;" id="seznamMap">' + output + '--</div>';
            } else {
                //var iconv = require('iconv-lite');
                contentString = contentString + '<div style="padding-top: 30px; font-weight: bold;" id="seznamMap">' + output + pp[i - 1][3] + '</div>';
            }

            var defcodepage = document.charset;
            if (defcodepage == 'UTF-8') {
                var charset = 'UTF';
            }
            if (charset == 'UTF') {
                if (this.lang == 'sk') {
                    var output = info_2_UTF_SK;
                } else {
                    var output = info_2_UTF_CZ;
                }
            } else {
                if (this.lang == 'sk') {
                    var output = info_2_W1250_SK;
                } else {
                    var output = info_2_W1250_CZ;
                }
            }

            if (i == pp.length - 1) {
                contentString = contentString + '<div style="padding-top: 30px; font-weight: bold;" id="seznamMap">' + output + '--</div>';
            } else {
                contentString = contentString + '<div style="padding-top: 30px; font-weight: bold;" id="seznamMap">' + output + pp[i + 1][3] + '</div>';
            }
            contentString = contentString + '</div>';
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            markersinfo.push(infowindow);
            var image = {
                url: main_path + '/image/number_' + (num_zastavky) + '.png',
                scaledSize: new google.maps.Size(22.5, 26)
            };
            var marker = new google.maps.Marker({
                position: lokace,
                map: map,
                objinfo: infowindow,
                markersin: markersinfo,
                //      icon: "http://maps.google.com/mapfiles/marker" + String.fromCharCode(i+65) + ".png"
                icon: image, //"//www.mhdspoje.cz/jrw50/image/number_" + (num_zastavky) + ".png",
                funcclick: function () {
                    for (i = 0; i < this.markersin.length; i++) {
                        this.markersin[i].close();
                    }
                    this.objinfo.open(map, this);
                }
            });
        } else {
            var defcodepage = document.charset;
            if (defcodepage == 'UTF-8') {
                var charset = 'UTF';
            }
            if (charset == 'UTF') {
                if (this.lang == 'sk') {
                    var output = info_3_UTF_SK;
                } else {
                    var output = info_3_UTF_CZ;
                }
            } else {
                if (this.lang == 'sk') {
                    var output = info_3_W1250_SK;
                } else {
                    var output = info_3_W1250_CZ;
                }
            }
            var contentString = '<div id="content" style="height: 200px;">' +
                    '<div id="siteNotice">' +
                    '</div>' +
                    '<h1 id="firstHeading" class="firstHeading">' + pp[i][3] + '</h1>' +
                    '<div style="padding-top: 30px; text-align: center; font-weight: bold;" id="seznamMap">' + output +
                    '</div>' + '</div>';
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            markersinfo.push(infowindow);
            var image = {
                url: main_path + '/image/number_' + (num_zastavky) + '_dark.png',
                scaledSize: new google.maps.Size(22.5, 26)
            };
            var marker = new google.maps.Marker({
                position: lokace,
                map: map,
                objinfo: infowindow,
                markersin: markersinfo,
                //      icon: "http://maps.google.com/mapfiles/marker" + String.fromCharCode(i+65) + ".png"
//        icon: "//www.mhdspoje.cz/jrw50/image/number_" + (num_zastavky) + "_dark.png",
                icon: image,
                funcclick: function () {
                    for (i = 0; i < this.markersin.length; i++) {
                        this.markersin[i].close();
                    }
                    this.objinfo.open(map, this);
                }
            });
        }

        // Construct the polygon.
        var lineTriangle = new google.maps.Polyline({
            path: lineCoords,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        lineTriangle.setMap(map);

        google.maps.event.addListener(marker, 'click', marker.funcclick);

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

    /*getDirection(pp, 0, pp.length - 1, new google.maps.DirectionsService(), new google.maps.DirectionsRenderer({
     preserveViewport: true,
     suppressMarkers: true
     }), map);*/

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
    if (this.onMapChange != null) {
        this.onMapChange();
    }

    var $content = '';
    num_zastavky = 0;
    if (this.tagJR != null) {
        if (!moveit) {
            $content = '<table>';
            for (i = 0; i < pp.length; i++) {
                num_zastavky++;
                if (pp[i][4] == 1) {
                    $content = $content + '<tr>';
                    $content = $content + '<td>';
                    $content = $content + '<img style="height: 70%;" class="imgmap" onclick="JR.centerStation(' + pp[i][1] + ', ' + pp[i][2] + ');" src="//www.mhdspoje.cz/jrw50/image/number_' + (num_zastavky) + '.png"/>'//num_zastavky;/*+ pp[0][1] + ', ' + pp[0][2] + */
                    $content = $content + '</td>';
                    $content = $content + '<td>';
                    $content = $content + pp[i][3];
                    $content = $content + '</td>';
                    $content = $content + '<td>';
                    $content = $content + '</td>';
                    $content = $content + '</tr>';
                } else {
                    $content = $content + '<tr>';
                    $content = $content + '<td>';
                    $content = $content + '<img style="height: 70%;" class="imgmap"  onclick="JR.centerStation(' + pp[i][1] + ', ' + pp[i][2] + ');" src="//www.mhdspoje.cz/jrw50/image/number_' + (num_zastavky) + '_dark.png"/>'//num_zastavky;
                    $content = $content + '</td>';
                    $content = $content + '<td  style="text-decoration: line-through">';
                    $content = $content + pp[i][3];
                    $content = $content + '</td>';
                    $content = $content + '<td>';
                    var defcodepage = document.charset;
                    if (defcodepage == 'UTF-8') {
                        var charset = 'UTF';
                    }
                    if (charset == 'UTF') {
                        if (this.lang == 'sk') {
                            var output = info_3_UTF_SK;
                        } else {
                            var output = info_3_UTF_CZ;
                        }
                    } else {
                        if (this.lang == 'sk') {
                            var output = info_3_W1250_SK;
                        } else {
                            var output = info_3_W1250_CZ;
                        }
                    }
                    $content = $content + '(&nbsp;' + output + '&nbsp;)';
                    $content = $content + '</td>';
                    $content = $content + '</tr>';
                }
            }
            $content = $content + '</table>';
            document.getElementById(this.tagJR).innerHTML = $content
        }
    }
    if (this.routediv != null) {
        window.scroll(0, (document.getElementById(this.routediv).offsetTop - 20));
    }
}

JRData.prototype.centerStation = function (loca, locb) {
    if (this.sharedmap != null) {
        this.sharedmap.setCenter(new google.maps.LatLng(loca, locb));
        if (this.routediv != null) {
            window.scroll(0, (document.getElementById(this.routediv).offsetTop - 20));
        }
    }
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

        dirService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                dirDisplay.setDirections(response);
                getDirection(points, i + 1, endi, new google.maps.DirectionsService(), new google.maps.DirectionsRenderer({
                    preserveViewport: true,
                    suppressMarkers: true
                }), m);
            }
        });
    }
}

JRData.prototype.getRoute = function (linka, smer, location, packet) {
    scriptObj = document.createElement("script");
    scriptObj.setAttribute("type", "text/javascript");
    scriptObj.setAttribute("charset", "windows-1250");
    noCacheIE = '&noCacheIE=' + (new Date()).getTime();

    path = main_path + "/php/";
    if (selfobj.version == 51) {
        path = path + "5_1/";
    }

    fullUrl = /*"http://www.mhdspoje.cz/jrw50/php/*/path + "loadRouteJSON.php?linka=" + document.getElementById(this.tagLinky).value + "&smer=" + document.getElementById(this.tagSmery).value + "&location=" + location + "&packet=" + this.packet + "&callback=getRouteData";
    scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
    scriptObj.setAttribute("id", "myscrRoute");
    document.body.appendChild(scriptObj);
}

JRData.prototype.prohodStanice = function (staniceA, staniceB) {
    var selectA = document.getElementById(staniceA);
    var selectB = document.getElementById(staniceB);
    if ((selectA.value >= 0) && (selectB.value >= 0)) {
        var pindex = selectA.selectedIndex;
        selectA.selectedIndex = selectB.selectedIndex;
        selectB.selectedIndex = pindex;
    }
}

JRData.prototype.startRefreshDenJR = function (acallf) {
    //  callfunc = acallf;
    this.refreshJR = setInterval(this.doRefreshDenJR, 30000);
}

JRData.prototype.stopRefreshDenJR = function () {
    clearInterval(this.refreshJR);
    this.refreshJR = null;
}

JRData.prototype.doRefreshDenJR = function () {
    callfunc();
}

JRData.prototype.startRefreshBoard = function (afunc) {
   this.refreshBoard = setInterval(this.doRefreshBoard, 30000); 
}

JRData.prototype.doRefreshBoard = function () {
    callfuncBoard();
}

function registerNaseptavac(aname) {
    allnaseptavac.push(aname);
}

function naseptavacHidden(aname) {
    document.getElementById(aname + "Div").style.visibility = "hidden";
}

function GetKeyCode(e) {
    if (e) {
        return e.charCode ? e.charCode : e.keyCode;
    } else {
        return window.event.charCode ? window.event.charCode : window.event.keyCode;
    }
}

function generujNaseptavac(e, mode, aname) {
    var unicode = GetKeyCode(e);
    var str = document.getElementById(aname + "Text").value;
    if (unicode != 37 && unicode != 38 && unicode != 39 && unicode != 40 && unicode != 13/* && str != lastSelected*/) {
        if ((str != "") || (mode == 1)) {
            // IE/zbytek světa
            if (((mode == 1) && (document.getElementById(aname + "Div").style.visibility == "hidden")) || (mode != 1)) {
                if (window.ActiveXObject) {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } else {
                    httpRequest = new XMLHttpRequest();
                }
                if (mode == 1) {
                    var url = main_path + "/php/selectZastavky.php?&location=" + selfobj.location + "&packet=" + selfobj.packet + "&name=" + aname + "&str=";
                } else {
                    var url = main_path + "/php/selectZastavky.php?&location=" + selfobj.location + "&packet=" + selfobj.packet + "&name=" + aname + "&str=" + encodeURI(str);
                }
                httpRequest.open("GET", url, true);
                httpRequest.setRequestHeader('Access-Control-Allow-Origin', '*');
                httpRequest.setRequestHeader('Access-Control-Allow-Methods', 'GET');
                httpRequest.onreadystatechange = function () {
                    processRequest(aname);
                };
                httpRequest.send(null);
            } else {
                document.getElementById(aname + "Div").style.visibility = "hidden"
            }
        } else {
            document.getElementById(aname + "Div").style.visibility = "hidden";
        }
    }
}

function posunNaseptavac(e, aname) {
    var unicode = GetKeyCode(e);
    var naseptavac = document.getElementById(aname);
    if (document.getElementById(aname + "Div").style.visibility == "visible") {
        if (unicode == 40) {
            // šipka dolů
            naseptavac.options.selectedIndex =
                    naseptavac.options.selectedIndex >= 0 &&
                    naseptavac.options.selectedIndex < naseptavac.options.length - 1 ?
                    naseptavac.options.selectedIndex + 1 : 0;
            getChangeHandler(aname);
            return;
        } else if (unicode == 38) {
            // šipka nahoru

            naseptavac.options.selectedIndex =
                    naseptavac.options.selectedIndex > 0 ?
                    naseptavac.options.selectedIndex - 1 : naseptavac.options.length - 1;
            getChangeHandler(aname);
            return;
        }
    }
    if (unicode == 13) {
        lastSelected = document.getElementById(aname + "Text").value;
        // na enter ve textovém poli nechceme odesílat formulář
        if (window.event)
            e.returnValue = false;
        else
            e.preventDefault();
        document.getElementById(aname + "Div").style.visibility = "hidden";
    }
}

function checkNaseptavac() {
    /*  if (document.getElementById("naseptavacDiv") != null) {*/
//    document.getElementById("naseptavacDiv").style.visibility = "hidden";
    /*  }*/
}

function processRequest(aname) {
    if (httpRequest.readyState == 4) {
        if (httpRequest.status == 200) {
            var response = httpRequest.responseText;
            if (response == 'EMPTY') {
                document.getElementById(aname + "Div").style.visibility = "hidden";
                document.getElementById(aname + "Text").style.color = "red";
            } else {
                document.getElementById(aname + "Text").style.color = "";
                document.getElementById(aname + "Div").innerHTML = response;
                /*        document.getElementById("naseptavac").size =
                 document.getElementById("naseptavac").options.length;*/
                document.getElementById(aname + "Div").style.visibility = "visible";
                /*        var select = document.getElementById("naseptavac");
                 select.focus()*/
                /*        document.onclick = function(e) {
                 document.getElementById("naseptavacDiv").style.visibility = "hidden";
                 if (e != null) {
                 e.stopPropagation();
                 }
                 else {
                 
                 if (!e) var e = window.event;
                 }
                 }*/
            }
        } else {
            alert("Chyba při načítání stránky"

                    + httpRequest.status + ":" + httpRequest.statusText);
        }
    }
}

function echovysledek(aname) {
    var naseptavac = document.getElementById(aname);
    //alert(naseptavac.value);
    if (naseptavac == null) {
        return 0;
    } else
    {
        return naseptavac.value;
    }
}

function getChangeHandler(aname) {
    var select = document.getElementById(aname);
    var nazev = select.options[select.selectedIndex].innerHTML;
    document.getElementById(aname + "Text").value = nazev.replace(/\&amp;/g, '&');
}

function getResultClickHandler(aname) {
    getChangeHandler(aname);
    lastSelected = document.getElementById(aname + "Text").value;
    document.getElementById(aname + "Div").style.visibility = "hidden";
}