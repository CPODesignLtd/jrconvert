
function getScrollXY() {
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

function moveRowUp(tableid, rowid) {
  nc = document.getElementById(tableid);
  res = -1;
  for (i = 0; i < nc.rows.length; i++) {
    if (nc.rows[i].id == rowid) {
      res = i;
    }
  }
  if (res > 1) {
    PreviousRow = nc.rows[res-1];
    CurrentRow = nc.rows[res];
    var nc1 = new Array(); 
    nc1 = document.getElementsByName('order[]');
    nc1[res - 1].value = res - 1;
    nc1[res - 2].value = res;
    PreviousRow.parentNode.insertBefore(CurrentRow,PreviousRow);
  }
}

function moveRowDown(tableid, rowid) {
  nc = document.getElementById(tableid);
  res = -1;
  for (i = 0; i < nc.rows.length; i++) {
    if (nc.rows[i].id == rowid) {
      res = i;
    }
  }
  
  CurrentRow = nc.rows[res];
  var nc1 = new Array(); 
  nc1 = document.getElementsByName('order[]');   
  
  if (res+2 < nc.rows.length) {
    NextRow = nc.rows[res+2];
    nc1[res - 1].value = res + 1; 
    nc1[res].value = res;
    NextRow.parentNode.insertBefore(CurrentRow,NextRow);
  } else {
    if (res < nc.rows.length - 1) {
      nc1[res - 1].value = res + 1; 
      nc1[res].value = res;
      CurrentRow.parentNode.appendChild(CurrentRow);
    }
  }
}

function visibledisable_elements(name) {
  var nc = new Array(); 
  nc = document.getElementsByName(name);
  for(i=0; i<nc.length; i++) {
    if (nc[i].style.visibility == 'hidden') {
      nc[i].style.visibility = 'visible';
    } else {
      nc[i].style.visibility = 'hidden';
    }
  }  
}

function enabledisable_elements(name) {
  var nc = new Array(); 
  nc = document.getElementsByName(name);
  for(i=0; i<nc.length; i++) {
    if (nc[i].disabled == 'disabled') {
      nc[i].disabled = '';
    } else {
      nc[i].disabled == 'disabled';
    }
  }
}


