JRCalendar = function(id, pozn, kalend, left, top, w, z) {
/*    var cssNode = document.createElement('link');
    cssNode.setAttribute('rel', 'stylesheet');
    cssNode.setAttribute('type', 'text/css');
    cssNode.setAttribute('href', '../ccs/Calendar.css');
    document.getElementsByTagName('head')[0].appendChild(cssNode);*/

    if (language == 1) {
      this.dny = new Array("po", "út", "st", "čt", "pá", "so", "ne");
      this.dnyfull = new Array("pondělí", "úterý", "středa", "čtvrtek", "pátek", "sobota", "neděle");
      this.monthfull = new Array("Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen","Září","�?íjen","Listopad","Prosinec");
    }
    if (language == 2) {
      this.dny = new Array("po", "ut", "st", "št", "pi", "so", "ne");
      this.dnyfull = new Array("pondelok", "utorok", "streda", "štvrtok", "piatok", "sobota", "nedeľa");
      this.monthfull = new Array("Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Jul", "August","September","Október","November","December");
    }
    if (language == 3) {
      this.dny = new Array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
      this.dnyfull = new Array("monday", "Tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
      this.monthfull = new Array("January", "February", "March", "April", "May", "June", "July", "August","September","October","November","December");
    }
    this.dnymesic = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    this.pstoleti = new Array(0, 5, 3, 1);
    this.pmesic = new Array(0, 6, 2, 2, 5, 7, 3, 5, 1, 4, 6, 2, 4);
    this.self = this;
    this.dropped = false;
    this.output = document.createElement("div");
    this.output.id = id;
    this.divselect = document.createElement("div");
    this.divselect.className = "calendar_div_select";
    this.divselect.style.offsetWidth = w;//(w - 4) + "px";
    this.select = document.createElement("table");
    //    this.select.style.width = (w) + "px";
    this.select.className = "calendar";
    this.select.style.borderWidth = "0px";
    this.select.style.borderCollapse = "collapse";
    this.select.style.marginTop = "2px";
    this.select.style.marginBottom = "2px";
//    this.select.style.margin = "0px";
//    this.left = left;
//    this.top = top;
    this.w = w;
//    this.z = z;
    this.kalend = kalend;
    /*    this.select.style.left = left+"px";
    this.select.style.top = top+"px";*/
    this.select.style.zIndex = z;
    this.list = document.createElement("div");
    this.list.id = "l"+id;

    this.datum = new Date();
    this.activeday = this.datum.getDate();
    this.activemonth = this.datum.getMonth() + 1;
    this.activeyear = this.datum.getFullYear();
    this.CasPoznamky = pozn;
    this.vyberrok = document.createElement("select");
    this.vyberrok.name = "comborok";
    this.vyberrok.id = "comborok";

    this.vyberrok.style.width = "100px";
    this.startyear = this.datum.getFullYear();
    dOd = this.datum.getFullYear();
    dDo = dOd + 10;
    pomi = 0;
    this.vyberrok.options.length = 0;

    for(i = dOd; i < dDo; i++) {
        this.vyberrok.options[i - dOd] = new Option(i.toString(), i.toString());
    }
    this.vyberrok.selectedIndex = 0;
/*    if (this.vyberrok.refresh != undefined) {
        this.vyberrok.refresh();
    }*/

    this.createSelect();
    this.createList();
}

JRCalendar.prototype.createSelect = function() {
    this.rowh = this.select.insertRow(0);

    col = this.rowh.insertCell(0);
    col.style.paddingLeft = "2px";
    this.edit = document.createElement("input");
    this.edit.id = "calendaredit";
    this.edit.className = "calendarinput";
    this.edit.setAttribute("type", "text");
    this.edit.value = formatDDMMYYY(this.activeday, this.activemonth, this.activeyear);
    col.appendChild(this.edit);

    this.colup = this.rowh.insertCell(1);
    this.colup.style.width = "17px";
    this.colup.style.paddingRight = "3px";
    this.upobr = document.createElement("img");
//    this.upobr.src = "http://www.mhdspoje.cz/jrw20/png/combo_drop_up.PNG";
    this.upobr.src = "//www.mhdspoje.cz/jrw20/png/upinactive.png";
    this.upobr.style.width = "17px";
    this.upobr.style.height = "18px";
    this.colup.appendChild(this.upobr);
    if (language == 1) {
      this.colup.title = "+1 den";
    }
    if (language == 2) {
      this.colup.title = "+1 de�?";
    }
    if (language == 3) {
      this.colup.title = "+1 day";
    }
    this.colup.id = "up"+this.list.id;
    this.colup.obj = this.self;
    this.colup.obr = this.upobr;
    this.colup.onclick = function() {
        roll(this, 1);
    };
    this.colup.onmouseover = function() {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/upactive.png";this.style.cursor = "pointer";
    };
    this.colup.onmouseout = function() {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/upinactive.png";this.style.cursor = "auto";
    };

    this.coldown = this.rowh.insertCell(2);
    this.coldown.style.width = "17px";
    this.coldown.style.paddingRight = "3px";
    this.downobr = document.createElement("img");
    this.downobr.src = "//www.mhdspoje.cz/jrw20/png/downinactive.png";
    this.downobr.style.width = "17px";
    this.downobr.style.height = "18px";
    this.coldown.appendChild(this.downobr);
    if (language == 1) {
      this.coldown.title = "-1 den";
    }
    if (language == 2) {
      this.coldown.title = "-1 de�?";
    }
    if (language == 3) {
      this.coldown.title = "-1 day";
    }
    this.coldown.id = "down"+this.list.id;
    this.coldown.obj = this.self;
    this.coldown.obr = this.downobr;
    this.coldown.onclick = function() {
        roll(this, -1);
    };
    this.coldown.onmouseover = function() {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/downactive.png";this.style.cursor = "pointer";
    };
    this.coldown.onmouseout = function() {
//        this.obr.src = "http://www.mhdspoje.cz/jrw20/png/combo_drop_down.PNG"; this.style.cursor = "auto";
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/downinactive.png";this.style.cursor = "auto";
    };

    this.cold = this.rowh.insertCell(3);
    this.cold.style.width = "17px";
    this.cold.style.paddingRight = "2px";
    this.dropobr = document.createElement("img");
//    this.dropobr.src = "http://www.mhdspoje.cz/jrw20/png/combo_drop_grid.PNG";
    this.dropobr.src = "//www.mhdspoje.cz/jrw20/png/calendinactive.png";
    this.dropobr.style.width = "17px";
    this.dropobr.style.height = "18px";
    this.cold.appendChild(this.dropobr);
    if (language == 1) {
      this.cold.title = "zobrazit kalendář";
    }
    if (language == 2) {
      this.cold.title = "zobraziť kalendár";
    }
    if (language == 3) {
      this.cold.title = "show calendar";
    }
    this.cold.id = "d"+this.list.id;
    this.cold.obj = this.self;
    this.cold.obr = this.dropobr;
    this.cold.onclick = function() {
        dropclickCalendar(this);
    }
    this.cold.onmouseover = function() {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/calendactive.png";this.style.cursor = "pointer";
    }
    this.cold.onmouseout = function() {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/calendinactive.png";this.style.cursor = "auto";
    }

    this.divselect.appendChild(this.select);
    this.output.appendChild(this.divselect);
/*    document.body.onclick = function() {

    };*/
}

function dropclickCalendar(obj) {
    /*    if (obj.obj.dropdiv.offsetHeight > 100) {
        obj.obj.dropdiv.style.height = 100;
    }*/

    c1 = obj.obj;
    l1 = obj.obj.list;
    if (c1.dropped == true) {
        c1.dropped = false;
        l1.style.visibility = "hidden";
    } else {
        c1.dropped = true;
        l1.style.visibility = "visible";
    }
}

JRCalendar.prototype.createList = function() {
    if (this.dropped == false) {
        this.list.style.visibility = "hidden";
    } else {
        this.list.style.visibility = "visible";
    }
    try {
        this.list.removeChild(this.droptable);
    }
    catch (chyba) {}

    this.list.className = "calendarlist";
    this.droptable = document.createElement("table");
    this.droptable.className = "calendardroptable";

    /*    row = this.droptable.insertRow(0);
    col = row.insertCell(0);
    col.className = "calendarBorderLeftTop";
    col = row.insertCell(1);
    col.className = "calendarBorderCenterTop";
    col = row.insertCell(2);
    col.className = "calendarBorderRightTop";*/

    row = this.droptable.insertRow(0);
    /*    col = row.insertCell(0);
    col.className = "calendarBorderLeftMid";*/
    this.listcanvas = row.insertCell(0);

    /*    col = row.insertCell(2);
    col.className = "calendarBorderRightMid";*/

    /*    row = this.droptable.insertRow(1);
    col = row.insertCell(0);
    col.className = "calendarBorderLeftBottom";
    col = row.insertCell(1);
    col.className = "calendarBorderCenterBottom";
    col = row.insertCell(2);
    col.className = "calendarBorderRightBottom";*/

    this.drop = document.createElement("table");
    this.drop.className = "calendardrop";
    //    this.drop.style.zindex = 0;

    now = new Date(this.activeyear, this.activemonth, this.activeday);
    nrok = this.activeyear;
    nmesic = this.activemonth;
    nden = this.activeday;

    this.rowhead = this.drop.insertRow(0);
    this.colyear = this.rowhead.insertCell(0);
    this.colyear.colSpan = 5;
    this.colyear.obj = this.self;
    this.colyear.className = "calendarpanel";
    //    this.vyberrok = new JRCombo("cRok", RokClick, -1, -1, 50, -1, this.self, false);


    /*    this.vyberrok.style.width = "100px";
    dOd = this.datum.getFullYear();
    dDo = dOd + 10;
    pomi = 0;
    alert(this.vyberrok.name);
    this.vyberrok.options.length = 0;

    for(i = dOd; i < dDo; i++) {
    	this.vyberrok.options[i - dOd] = new Option(i.toString(), i.toString());
    }
    this.vyberrok.selectedIndex = 0;
    if (this.vyberrok.refresh != undefined) {
        this.vyberrok.refresh();
    }*/
    //    this.vyberrok = document.getElementById("comborok");
    //    if (this.vyberrok == null) {
    this.colyear.appendChild(this.vyberrok);
    //    }
    this.vyberrok.obj = this.self;
    this.vyberrok.onchange =  function() {
        RokClick(this);
    };
    this.vyberrok.selectedIndex = this.activeyear - this.startyear;

    /*    this.vyberrok.createList();
    this.vyberrok.activeitem = pomi;
    this.vyberrok.setPicture(pomi);
    this.vyberrok.edit.value = this.vyberrok.items[this.vyberrok.activeitem][1];
    this.vyberrok.edit.id_value = this.vyberrok.items[this.vyberrok.activeitem][0];
    this.vyberrok.show(this.colyear);*/

    this.mesicleft = this.rowhead.insertCell(1);
    this.mesicleft.className = "calendarpanel";
    this.leftobr = document.createElement("img");
    this.leftobr.src = "//www.mhdspoje.cz/jrw20/png/buttonleft.PNG";
    this.leftobr.style.width = "16px";
    this.leftobr.style.height = "16px";
    this.mesicleft.appendChild(this.leftobr);
    this.mesicleft.id = "left"+this.list.id;
    this.mesicleft.obj = this.self;
    this.mesicleft.obr = this.leftobr;
    if (language == 1) {
      this.mesicleft.title = "-1 měsíc";
    }
    if (language == 2) {
      this.mesicleft.title = "-1 mesiac";
    }
    if (language == 3) {
      this.mesicleft.title = "-1 month";
    }
    this.mesicleft.onclick = function() {
        rollmonth(this, -1);
    };
    this.mesicleft.onmouseover = function () {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/buttonleft1.PNG";this.style.cursor = "pointer";
    }
    this.mesicleft.onmouseout = function () {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/buttonleft.PNG";this.style.cursor = "auto";
    }
    this.mesicright = this.rowhead.insertCell(2);
    this.mesicright.className = "calendarpanel";
    this.rightobr = document.createElement("img");
    this.rightobr.src = "//www.mhdspoje.cz/jrw20/png/buttonright.PNG";
    this.rightobr.style.width = "16px";
    this.rightobr.style.height = "16px";
    this.mesicright.appendChild(this.rightobr);
    this.mesicright.id = "right"+this.list.id;
    this.mesicright.obj = this.self;
    this.mesicright.obr = this.rightobr;
    if (language == 1) {
      this.mesicright.title = "+1 měsíc";
    }
    if (language == 2) {
      this.mesicright.title = "+1 mesiac";
    }
    if (language == 3) {
      this.mesicright.title = "+1 month";
    }
    this.mesicright.onclick = function() {
        rollmonth(this, 1);
    };
    this.mesicright.onmouseover = function () {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/buttonright1.PNG";this.style.cursor = "pointer";
    }
    this.mesicright.onmouseout = function () {
        this.obr.src = "//www.mhdspoje.cz/jrw20/png/buttonright.PNG";this.style.cursor = "auto";
    }

    row = this.drop.insertRow(1);
    this.colmonth = row.insertCell(0);
    this.colmonth.colSpan = 7;
    this.colmonth.className = "calendarmonth";
    a = document.createElement("a");
    a.innerHTML = this.monthfull[nmesic - 1];
    this.colmonth.appendChild(a);

    row = this.drop.insertRow(2);
    for(i = 0; i < 7; i++) {
        this.coltitle = row.insertCell(i);
        this.coltitle.className = "calendartitle";

        a = document.createElement("a");
        a.innerHTML = this.dny[i];
        this.coltitle.appendChild(a);
    }

    crow = 3;
    cden = 1;
    while (cden < this.dnymesic[nmesic - 1] + /*isLapYear(nrok) +*/ 1) {
        row = this.drop.insertRow(crow);
        crow++;
        for(i = 0; i < 7; i++) {
            this.col = row.insertCell(i);
            this.col.obj = this.self;
            this.col.e = true;
            this.col.onclick = function() {
                calendarclick(this);
            };
            this.col.onmouseover = function() {
                calendarover(this);
            };
            this.col.onmouseout = function() {
                calendarout(this);
            };

            if (crow == 4) {
                now = new Date(nrok, nmesic - 1, 1);
                den = now.getDay() - 1;
                if (den < 0) {
                    den = 6;
                }
                a = document.createElement("a");
                if (den <= i) {
                    a.innerHTML = cden;
                    this.col.className = "calendartextitem";
                    if (i == 6) {
                        this.col.style.color = "#ff0000";
                        a.style.color = "#ff0000";
                    }
                    this.col.val = cden;
                    cden++;
                } else {
                    a.innerHTML = "-";
                    this.col.className = "calendartextiteminactive";
                    this.col.e = false;
                }
                if (this.col.val == this.activeday) {
                    this.activecol = this.col;
                    this.col.className = "calendaractiveitem";
                }
                this.col.appendChild(a);
            } else {
                a = document.createElement("a");
                if (cden > (nmesic == 1 ? this.dnymesic[nmesic - 1] + isLapYear(nrok): this.dnymesic[nmesic - 1])) {
                    a.innerHTML = "-";
                    this.col.className = "calendartextiteminactive";
                    this.col.e = false;
                } else {
                    a.innerHTML = cden;
                    this.col.className = "calendartextitem";
                    if (i == 6) {
                        this.col.style.color = "#ff0000";
                        a.style.color = "#ff0000";
                    }
                    this.col.val = cden;
                }
                cden++;
                if (this.col.val == this.activeday) {
                    this.activecol = this.col;
                    this.col.className = "calendaractiveitem";
                }
                this.col.appendChild(a);
            }
        }
    }
    row = this.drop.insertRow(crow);
    this.colfoot = row.insertCell(0);
    this.colfoot.colSpan = 7;
    this.colfoot.className = "calendarfoot";
    this.colfoot.obj = this.self;
    this.colfoot.onclick = function() {
        calendarclickdnes(this);
    };
    a = document.createElement("a");
    if (language == 1) {
          a.innerHTML = "<< dnes >>";
    }
    if (language == 2) {
          a.innerHTML = "<< dnes >>";
    }
    if (language == 3) {
          a.innerHTML = "<< today >>";
    }
    this.colfoot.appendChild(a);

    this.list.appendChild(this.droptable);
    this.listcanvas.appendChild(this.drop);
    this.output.appendChild(this.list);
}

function calendarover(obj) {
    if ((obj.val != obj.obj.activeday) && (obj.e)) {
        obj.style.background = "#c2c2ff";
    }
}

function calendarout(obj) {
    if ((obj.val != obj.obj.activeday) && (obj.e)) {
        obj.style.background = "";
    }
}

function calendarclick(obj) {
    if ((obj.val != obj.obj.activeday) && (obj.e)) {
        obj.obj.activecol.style.background = "#ffffff";
        obj.obj.activecol.className = "calendartextitem";
        obj.obj.activeday = obj.val;
        obj.style.background = "#ff8888";
        obj.className = "calendaractiveitem";
        obj.obj.activecol = obj;
        obj.obj.edit.value = formatDDMMYYY(obj.obj.activeday, obj.obj.activemonth, obj.obj.activeyear);
        dropclickCalendar(obj);
        if (obj.obj.vyberrok.dropped) {
            dropclick(obj.obj.vyberrok.cold);
        }
    }
    changePacket();
}

function calendarclickdnes(obj) {
    this.datum = new Date();
    obj.obj.activeday = this.datum.getDate();
    obj.obj.activemonth = this.datum.getMonth() + 1;
    obj.obj.activeyear = this.datum.getFullYear();
    obj.obj.edit.value = formatDDMMYYY(obj.obj.activeday, obj.obj.activemonth, obj.obj.activeyear);
    dropclickCalendar(obj);
    obj.obj.createList();
    changePacket();
}

function RokClick(obj) {
    /*    combo = obj;
    combo.activeitem = obj.name;
    combo.edit.value = combo.items[combo.activeitem][1];
    combo.edit.id_value = combo.items[combo.activeitem][0];
    dropclick(obj);
    combo.pobj.activeyear = combo.edit.id_value;
    combo.pobj.edit.value = formatDDMMYYY(combo.pobj.activeday, combo.pobj.activemonth, combo.pobj.activeyear);
    combo.pobj.createList();*/
    obj.obj.activeyear = obj.obj.startyear + obj.selectedIndex;
    obj.obj.edit.value = formatDDMMYYY(obj.obj.activeday, obj.obj.activemonth, obj.obj.activeyear);
    obj.obj.createList();
    changePacket();
}

function roll(obj, index) {
    now = new Date(obj.obj.activeyear, obj.obj.activemonth - 1, obj.obj.activeday);
    now = new Date(now.getFullYear(), now.getMonth(), now.getDate() + index);
    obj.obj.activeyear = now.getFullYear();
    obj.obj.activemonth = now.getMonth() + 1;
    obj.obj.activeday = now.getDate();
    obj.obj.edit.value = formatDDMMYYY(obj.obj.activeday, obj.obj.activemonth, obj.obj.activeyear);
    /*    if (obj.obj.dropped) {
        dropclickCalendar(obj);
    }*/
    obj.obj.createList();
    changePacket();
}

function rollmonth(obj, index) {
    now = new Date(obj.obj.activeyear, obj.obj.activemonth - 1, obj.obj.activeday);
    now = new Date(now.getFullYear(), now.getMonth() + index, now.getDate());
    obj.obj.activeyear = now.getFullYear();
    obj.obj.activemonth = now.getMonth() + 1;
    obj.obj.activeday = now.getDate();
    obj.obj.edit.value = formatDDMMYYY(obj.obj.activeday, obj.obj.activemonth, obj.obj.activeyear);
    /*    if (obj.obj.dropped) {
        dropclickCalendar(obj);
    }*/
    obj.obj.createList();
    changePacket();
}

JRCalendar.prototype.getTypeDay = function() {
    a = this.pstoleti[parseInt((this.activeyear / 100) % 4)];
    b = this.activeyear % 100;
    var c = parseInt(b / 4);
    e = b / 4;
    d = this.pmesic[this.activemonth];
    vysledek = (((a + b + c + d + this.activeday) % 7));
    if ((isLapYear(this.activeyear) == 1) && (this.activemonth <= 2)) {
      vysledek = vysledek - 1;
    }
    pp = document.createElement("a");
    pp.innerHTML = vysledek;
    document.appendChild(pp);
    if (vysledek == 0) {
        vysledek = 7;
    }
    return this.dnyfull[vysledek - 1];
}

JRCalendar.prototype.getPoznamky = function() {
    a = this.pstoleti[parseInt((this.activeyear / 100) % 4)];
    b = this.activeyear % 100;
    var c = parseInt(b / 4);
    e = b / 4;
    d = this.pmesic[this.activemonth];
    vysledek = (((a + b + c + d + this.activeday) % 7));
    if (vysledek == 0) {
        vysledek = 7;
    }
    //    alert("typ dne = "+vysledek);
    this.ozn = null;
    this.global = null;
    nowstr = this.activeyear + '-' + ((this.activemonth < 10) ? '0' + this.activemonth: this.activemonth) + '-' + ((this.activeday < 10) ? '0' + this.activeday: this.activeday);    //new Date(this.activeyear, this.activemonth - 1, this.activeday);
    if (this.kalend.kalendar != null) {
//      alert(nowstr+' | '+this.kalend.kalendar.elementAt(0).datum);
      for (i = 0; i < this.kalend.kalendar.size(); i++) {
/*        document.write(this.kalend.kalendar.elementAt(i).datum+" == "+ nowstr);
        document.write("<BR>");*/
        if (this.kalend.kalendar.elementAt(i).datum == nowstr) {
//          alert('mam   pk = '+this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu);
          if (this.ozn != null) {
            if (this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk) != null) {
              this.ozn = this.ozn + "," + this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu;
            }
          } else {
            if (this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk) != null) {
              this.ozn = this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu;
            }
          }
        }
      }
    }
    if (this.ozn == null) {
    if (this.CasPoznamky.poznamky != null) {
        for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
            if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == "X") {
                this.global = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                break;
            }
        }
        for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
            //            document.write(this.CasPoznamky.poznamky.elementAt(i).oznaceni);
            //            alert("casove > "+this.CasPoznamky.poznamky.elementAt(i).c_kodu+" - "+ this.CasPoznamky.poznamky.elementAt(i).oznaceni+" = "+ vysledek);
            if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == vysledek) {
                if (this.ozn != null) {
                    this.ozn = this.ozn + "," + this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                } else {
                    this.ozn = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                }
            //                alert("pridavam kod = "+this.CasPoznamky.poznamky.elementAt(i).c_kodu);
            //                break;
            }
        }
        if ((this.ozn == null) && (vysledek == 7)) {
            for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
                if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == "+") {
                    if (this.ozn != null) {
                        this.ozn = this.ozn + "," + this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                    } else {
                        this.ozn = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                    }
                //                    break;
                }
            }
        }
        if ((vysledek != 7) && (vysledek != 6)) {
            if (this.ozn != null) {
                this.ozn = this.ozn + "," + this.global;
            } else {
                this.ozn = this.global;
            }
        }
    }
    }
    //    alert("global = "+this.global);
    return "(" + this.ozn + ")";
}

JRCalendar.prototype.getPoznamky1 = function() {
    a = this.pstoleti[parseInt((this.activeyear / 100) % 4)];
    b = this.activeyear % 100;
    var c = parseInt(b / 4);
    e = b / 4;
    d = this.pmesic[this.activemonth];
    vysledek = (((a + b + c + d + this.activeday) % 7));
    if (vysledek == 0) {
        vysledek = 7;
    }
    //    alert("typ dne = "+vysledek);
    this.ozn = null;
    this.global = null;
    nowstr = this.activeyear + '-' + ((this.activemonth < 10) ? '0' + this.activemonth: this.activemonth) + '-' + ((this.activeday < 10) ? '0' + this.activeday: this.activeday);    //new Date(this.activeyear, this.activemonth - 1, this.activeday);
    if (this.kalend != null) {
    if (this.kalend.kalendar != null) {
//      alert(nowstr+' | '+this.kalend.kalendar.elementAt(0).datum);
      for (i = 0; i < this.kalend.kalendar.size(); i++) {
/*        document.write(this.kalend.kalendar.elementAt(i).datum+" == "+ nowstr);
        document.write("<BR>");*/
        if (this.kalend.kalendar.elementAt(i).datum == nowstr) {
//          alert('mam   pk = '+this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu);
          if (this.ozn != null) {
            if (this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk) != null) {
              this.ozn = this.ozn + "," + this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu;
            }
          } else {
            if (this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk) != null) {
              this.ozn = this.CasPoznamky.getPoznamkaID(this.kalend.kalendar.elementAt(i).pk).c_kodu;
            }
          }
        }
      }
    }
    }

    if (this.ozn == null) {
    if (this.CasPoznamky.poznamky != null) {
        for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
            if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == "X") {
                this.global = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                break;
            }
        }
        for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
            //            document.write(this.CasPoznamky.poznamky.elementAt(i).oznaceni);
            //            alert("casove > "+this.CasPoznamky.poznamky.elementAt(i).c_kodu+" - "+ this.CasPoznamky.poznamky.elementAt(i).oznaceni+" = "+ vysledek);
            if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == vysledek) {
                if (this.ozn != null) {
                    this.ozn = this.ozn + "," + this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                } else {
                    this.ozn = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                }
            //                alert("pridavam kod = "+this.CasPoznamky.poznamky.elementAt(i).c_kodu);
            //                break;
            }
        }
        if ((this.ozn == null) && (vysledek == 7)) {
            for(i = 0; i < this.CasPoznamky.poznamky.size(); i++) {
                if (this.CasPoznamky.poznamky.elementAt(i).oznaceni == "+") {
                    if (this.ozn != null) {
                        this.ozn = this.ozn + "," + this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                    } else {
                        this.ozn = this.CasPoznamky.poznamky.elementAt(i).c_kodu;
                    }
                //                    break;
                }
            }
        }
        if ((vysledek != 7) && (vysledek != 6)) {
            if (this.ozn != null) {
                this.ozn = this.ozn + "," + this.global;
            } else {
                this.ozn = this.global;
            }
        }
    }
    }
    //    alert("global = "+this.global);
    return this.ozn;
}

JRCalendar.prototype.getDay = function() {
    return this.activeday;
}

JRCalendar.prototype.getMonth = function() {
    return this.activemonth;
}

JRCalendar.prototype.getYear = function() {
    return this.activeyear;
}

JRCalendar.prototype.getHeight = function() {
    return this.rowh.offsetHeight;
}

JRCalendar.prototype.show = function(tag) {
    tag.appendChild(this.output);
}


