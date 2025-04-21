var PacketsData = null;
var Linky = null;

function getAktualPacketDatum(d, m, y, packets) {
  res = -1;
  dnes = (y * 10000 + m * 100 + d);
  for(i = 0; i < packets.length; i++) {
    if (packets[i].platny == 1) {
      dOD = (packets[i][3] * 10000 + packets[i][2] * 100 + packets[i][1]);
      dDO = (packets[i][6] * 10000 + packets[i][5] * 100 + packets[i][4]);
     
      if ((dOD <= dnes) && (dnes <= dDO)) {
        res = packets[i][0];
      }
    }
  }
  return res;
}

function getPacketData(data) {
  var packets = new Array();
  var pp = [];
  var a = (eval(data)).toString(); 
  a = a.split(',');  
  while(a[0]) { 
    pp.push(a.splice(0,8)); 
  }   
  for (i = 0; i < pp.length; i++) { 
    packets[i] = new Array();
    packets[i][0] = pp[i][0];
    packets[i][1] = pp[i][1];
    packets[i][2] = pp[i][2];
    packets[i][3] = pp[i][3];
    packets[i][4] = pp[i][4];
    packets[i][5] = pp[i][5];
    packets[i][6] = pp[i][6];
    packets[i][7] = pp[i][7];    
  } 
  PacketsData = packets;
  return packets;
}

function getPacketList(location, callbackFunctionName) { 
  scriptObjold = document.getElementById("myscrPacket");
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if (callbackFunctionName == null) {
    callbackname = 'getPacketData';
  } else {
    callbackname = callbackFunctionName;
  }
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/data/ListPacketJSON.php?location=" + location + "&callback=" + callbackname;
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrPacket");
  if (scriptObjold == null) {
    document.body.appendChild(scriptObj);
  } else {
    scriptObjold.parentNode.replaceChild(scriptObj, scriptObjold);
  }
}

function getLinkyData(data) {
  var linky = new Array();
  var pp = [];
  var a = (eval(data)).toString(); 
  a = a.split(',');  
  while(a[0]) { 
    pp.push(a.splice(0,2)); 
  } 
  for (i = 0; i < pp.length; i++) { 
    linky[i] = new Array();
    linky[i][0] = pp[i][0];
    linky[i][1] = pp[i][1];
  }     
  Linky = linky;
  return linky; 
}

function getLinkyList(location, packet, callbackFunctionName) { 
  scriptObjold = document.getElementById("myscrPacket");
  scriptObj = document.createElement("script");
  scriptObj.setAttribute("type", "text/javascript");
  scriptObj.setAttribute("charset", "windows-1250");
  noCacheIE = '&noCacheIE=' + (new Date()).getTime();
  if (callbackFunctionName == null) {
    callbackname = 'getLinkyData';
  } else {
    callbackname = callbackFunctionName;
  }
  fullUrl = "http://www.mhdspoje.cz/jrw50/php/data/ListLinkyJSON.php?location=" + location + "&packet=" + packet + "&callback=" + callbackname;
  scriptObj.setAttribute("src", fullUrl/* + noCacheIE*/);
  scriptObj.setAttribute("id", "myscrLinky");
  if (scriptObjold == null) {
    document.body.appendChild(scriptObj);
  } else {
    scriptObjold.parentNode.replaceChild(scriptObj, scriptObjold);
  }
}


