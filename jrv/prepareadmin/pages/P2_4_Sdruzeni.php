<?php
if ($_POST['typeaction'] == 'delete_sdruz') {
//  echo "del id = " . $_POST[idsdruz];
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "delete from sdruz where id = " . $_POST['idsdruz'];
  mysql_query($sql);
}

if ($_POST['typeaction'] == 'accept_write_sdruz') {
  $soucet = 0;
  for ($c = 0; $c < count($_POST['sdruzpozn']); $c++) {
    $soucet += $_POST[sdruzpozn][$c];
  }  
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "UPDATE sdruz set bcode = " . $soucet . ", sc_kodu = " . $_POST['sdruz_s'] . " where id = " . $_POST['idsdruz'];
  mysql_query($sql);
}

if ($_POST['typeaction'] == 'insert_write_sdruz') {
  $soucet = 0;
  for ($c = 0; $c < count($_POST['sdruzpozn']); $c++) {
    $soucet += $_POST['sdruzpozn'][$c];
  }
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "INSERT INTO sdruz (IDLOCATION, PACKET, BCODE, SC_KODU) VALUES (" . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ", " . $soucet . ", " . $_POST['sdruz_s'] . ")";
  mysql_query($sql);  
}

//$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT id, bcode, sc_kodu, oznaceni, obr 
          FROM sdruz inner join pevnykod on (sdruz.idlocation = pevnykod.idlocation and sdruz.packet = pevnykod.packet and sdruz.sc_kodu = pevnykod.c_kodu) WHERE sdruz.packet = " . $_GET['pack'] . " and sdruz.idlocation=" . getLocation($_POST["username"]) . " order by id");
?>        

<div class="separdivglobalnapis" style="clear: both;">Sdružování poznámek balíčku č. <?php echo $_GET['pack']; ?></div>

<form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=4">

  <table id="table_sdruz" class="t_akce" style="clear: both; float: none;">
    <tr>
      <th style="white-space: nowrap">sdružené varianty grafikonu - "časové poznámky"</th>      
      <th style="white-space: nowrap;">zobrazovat jako</th>
    </tr>
    <?php
    $i = 1;
    while ($row = mysql_fetch_row($sql)) {
      ?>
      <tr id="table_sdruz_row<?php echo $i; ?>">         
        <td class="last" style="font-size: 15px; font-weight: bold; width: 100%;">
          <?php
          if (!isset($_GET['id'])) {
            $sql1 = "CALL bdecode(" . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ", " . $row[1] . ")";            
            $mysqli = new mysqli($con_server, $con_db, $con_pass, $con_db, 3306);
            //$mysqli->query("SET NAMES 'utf-8';");
            $query1 = $mysqli->query($sql1);
    
            $first = true;
            while ($row1 = $query1->fetch_row()) {
              if ($first == false) {
                echo ', ';
              }
              if ($row1[3] == '') {
                echo $row1[2];
              } else {
                ?>
                  <img src="../pictogram/<?php echo $row1[3]; ?>"> 
                <?php
              }
              $first = false;
            }
            mysqli_close($mysqli);
          } else {
            if ($_GET['id'] == $row[0]) {
              $sql1 = mysql_query("select bcode(" . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ", pevnykod.c_kodu) as code, oznaceni, rezerva, obr,   
                      bdecode_one(" . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ", " . $row[1] . ", pevnykod.c_kodu) as accept 
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");              
              ?>
              <table id="table_pozn_sdruz" class="t_akce" style="clear: both; float: none; width: 100%;">
              <?php  
              while ($row1 = mysql_fetch_row($sql1)) {
                ?>                
                  <tr>
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="checkbox" name="sdruzpozn[]" value="<?php echo $row1[0]; ?>" <?php echo ($row1[4] == 1) ? 'checked': ''; ?>>
                    </td>
                    <td class="last" style="font-size: 15px; font-style: italic; font-weight: normal; width: auto;"><?php echo $row1[1]; ?></td>
                    <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;">
                    <?php
                      if ($row1[3] != null) {
                    ?>
                      <img src="../pictogram/<?php echo $row1[3]; ?>">
                    <?php
                      }
                    ?>
                    </td>
                    <td class="last" style="width: 100%; word-wrap: nowrap; font-weight: normal;"><?php echo $row1[2]; ?></td> 
                  </tr>                    
                <?php
              }
              ?>
                </table>  
              <?php
            }
          }
          ?>                
        </td>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: left; vertical-align: middle;">
        <?php        
          if ((isset($_GET['id'])) && ($_GET['id'] == $row[0])) {           
            $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr   
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");              
              ?>
              <table id="table_pozn_s" class="t_akce" style="clear: both; float: none; width: 100%;">
              <?php  
              while ($row1 = mysql_fetch_row($sql1)) {
                ?>                
                  <tr>
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="radio" name="sdruz_s" value="<?php echo $row1[0]; ?>" <?php echo ($row1[0] == $row[2]) ? 'checked': ''; ?>>
                    </td>
                    <td class="last" style="font-size: 15px; font-style: italic; font-weight: normal; width: auto;"><?php echo $row1[1]; ?></td>
                    <td class="last" style="font-size: 15px; font-weight: bold; width: auto; text-align: center;">
                    <?php
                      if ($row1[3] != null) {
                    ?>
                      <img src="../pictogram/<?php echo $row1[3]; ?>">
                    <?php
                      }
                    ?>
                    </td>
                    <td class="last" style="width: 100%; word-wrap: nowrap; font-weight: normal;"><?php echo $row1[2]; ?></td> 
                  </tr>                    
                <?php
              }
              ?>
                </table>  
          <?php
          } else {
          ?>                  
              <?php
              if ($row[3] == '') {
                echo $row[3];
              } else {
              ?>
                  <img src="../pictogram/<?php echo $row[4]; ?>"> 
              <?php
              }
              ?>
          <?php
          }
        ?>
        </td>
        <td class="first" style="vertical-align: <?php echo ($_GET['id'] == $row[0]) ? 'top': 'middle'; ?>;">
        <?php
          if (!isset($_GET['id'])) {
        ?>              
            <a name="edit_sdruz" style="color: #ffffff;" href="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=<?php echo $_GET['sub']; ?>&id=<?php echo $row[0]; ?>" onClick="app_href(this);" title="Editace sloupce"><img src="image/pencil.png"></a>
            &nbsp;
            <a name="del_sdruz" style="color: #ffffff; word-wrap: nowrap;" title="Smazat" onClick="delSdruz(<?php echo $row[0]; ?>)"><img src="image/delete.png"></a>                                    
        <?php
          } else {
            if ($_GET['id'] == $row[0]) {
        ?>
              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['typeaction'].value = 'accept_write_sdruz'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>                
              &nbsp;             
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>    
              <input id="idsdruz" name="idsdruz" type="text" value="<?php echo $row[0]; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">                              
        <?php
            }
          }
        ?>  
        </td>
      </tr>
      <?php
      $i++;
    }
    ?>  
      <div class="button" id="add_sdruz" style="height: 35px; width: 150px; visibility: visible;" title="Přidat sdružení" onclick="addSdruz();">   
        <span></span><img src="image/addplus.png">
          přidat sdružení
      </div>
<!--      <div id="add_sdruz" style="cursor: pointer;" class="wraptocenter" onClick="addSdruz();" title="Přidat sdružení"><span></span><img src="image/addplus.png">&nbsp;-&nbsp;přidat sdružení</div>-->
<!--        <a id="add_sloupec" style="color: #ffffff;" onClick="addSloupec(<?php echo $i++; ?>)" title="Přidat sloupec"><img src="image/addplus.png"></a>-->
  </table>
</form>

<?php
  if (isset($_GET['id'])) {
?>
  <script  type='text/javascript'>
    document.getElementById('add_sdruz').style.visibility = 'hidden';
  </script>  
<?php
  }
?>

<script  type='text/javascript'>
  function addSdruzVargrf() {
    <?php
    $sql1 = mysql_query("select bcode(" . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ", pevnykod.c_kodu) as code, oznaceni, rezerva, obr 
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
    ?>
    var vargrf = document.getElementById('td_insertsdruzvargrf');
    var tblef = document.createElement('table');    
    tblef.className = "t_akce";
    tblef.style.width = "100%";
    var i = 0;
    <?php        
      while ($row1 = mysql_fetch_row($sql1)) {
    ?>                
        var row = tblef.insertRow(i);
        var td = row.insertCell(0);
        td.className = "last";        
        td.style.fontSize = '15px';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'italic';
        td.style.width = 'auto';
 
        var fieldtext = document.createElement('input');
        fieldtext.type = 'checkbox';
        fieldtext.name = 'sdruzpozn[]';        
        fieldtext.value = '<?php echo $row1[0]; ?>';    
        td.appendChild(fieldtext);
        fieldtext.focus();

        td = row.insertCell(1);
        td.className = "last";
        td.style.fontSize = '15px';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'italic';
        td.style.width = 'auto';
        
        var text = document.createTextNode('<?php echo $row1[1]; ?>');
        td.appendChild(text);

        td = row.insertCell(2);
        td.className = "last";
        td.style.fontSize = '15px';
        td.style.fontWeight = 'bold';
        td.style.textAlign = 'center';
        td.style.width = 'auto';
        
        <?php
          if ($row1[3] != null) {
        ?>            
          var img = document.createElement('img');
          img.src = '../pictogram/<?php echo $row1[3]; ?>';
          td.appendChild(img);
        <?php
          }
        ?>

        td = row.insertCell(3);
        td.className = "last";
        td.style.width = '100%';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'normal';
        td.style.wordWrap = 'nowrap';
        
        text = document.createTextNode('<?php echo $row1[2]; ?>');
        td.appendChild(text);
        
        i++;
    <?php
      }
    ?>
    vargrf.appendChild(tblef);
  }

  function addSdruz_s() {
    <?php
    $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr   
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
    ?>
    var vargrf = document.getElementById('td_insertsdruz_s');
    var tblef = document.createElement('table');
    tblef.className = "t_akce";
    tblef.style.width = "100%";
    var i = 0;
    <?php        
      while ($row1 = mysql_fetch_row($sql1)) {
    ?>                
        var row = tblef.insertRow(i);
        var td = row.insertCell(0);
        td.className = "last";        
        td.style.fontSize = '15px';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'italic';
        td.style.width = 'auto';
 
        var fieldtext = document.createElement('input');
        fieldtext.type = 'radio';
        fieldtext.name = 'sdruz_s';        
        fieldtext.value = '<?php echo $row1[0]; ?>'; 
        if (i == 0) {
          fieldtext.checked = true;
        } else {
          fieldtext.checked = false;
        }
        td.appendChild(fieldtext);

        td = row.insertCell(1);
        td.className = "last";
        td.style.fontSize = '15px';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'italic';
        td.style.width = 'auto';
        
        var text = document.createTextNode('<?php echo $row1[1]; ?>');
        td.appendChild(text);

        td = row.insertCell(2);
        td.className = "last";
        td.style.fontSize = '15px';
        td.style.fontWeight = 'bold';
        td.style.textAlign = 'center';
        td.style.width = 'auto';
        
        <?php
          if ($row1[3] != null) {
        ?>            
          var img = document.createElement('img');
          img.src = '../pictogram/<?php echo $row1[3]; ?>';
          td.appendChild(img);
        <?php
          }
        ?>

        td = row.insertCell(3);
        td.className = "last";
        td.style.width = '100%';
        td.style.fontWeight = 'normal';
        td.style.fontStyle = 'normal';
        td.style.wordWrap = 'nowrap';
        
        text = document.createTextNode('<?php echo $row1[2]; ?>');
        td.appendChild(text);
        
        i++;
    <?php
      }
    ?>
    vargrf.appendChild(tblef);
  }

  function addSdruz() {
    var nc = new Array(); 
    nc = document.getElementsByName('edit_sdruz');
    for(i=0; i<nc.length; i++) {
      if (nc[i] != null) {
        tag = nc[i]
        tag.style.visibility = 'hidden'
      }
    }
    nc = document.getElementsByName('del_sdruz');
    for(i=0; i<nc.length; i++) {
      if (nc[i] != null) {
        tag = nc[i]
        tag.style.visibility = 'hidden'
      }
    }
    document.getElementById('add_sdruz').style.visibility = 'hidden';    
    
    var tablef = document.getElementById('table_sdruz');
    var row = tablef.insertRow(tablef.rows.length);    

    var td = row.insertCell(0);  
    td.id = "td_insertsdruzvargrf";
    td.className = "last";
    td.style.fontSize = '15px';
    td.style.fontWeight = 'bold';
    td.style.width = '100%';
    
    var td = row.insertCell(1);    
    td.id = "td_insertsdruz_s";
    td.className = "last";
    td.style.fontSize = '15px';
    td.style.fontWeight = 'bold';
    td.style.width = '100%';

    var td = row.insertCell(2);
    td.className = "first";
    td.style.verticalAlign = 'top';
    
    var a = document.createElement('a');
    a.style.color = '#ffffff';
    a.style.wordWrap = 'nowrap';
    a.title = 'Zapsat';
    a.onclick = function() {
      document.frm['typeaction'].value = 'insert_write_sdruz'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();      
    }
    var img = document.createElement('img');
    img.src = "image/accept.png";
    a.appendChild(img);
    td.appendChild(a);

    var text = document.createTextNode('\u00A0\u00A0\u00A0');
    td.appendChild(text);
    var text = document.createTextNode('\u00A0');
    td.appendChild(text);

    var a = document.createElement('a');
    a.style.color = '#ffffff';
    a.style.wordWrap = 'nowrap';
    a.title = 'Odvolat';
    a.href = '?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>';
    a.onclick = function() {
      app_href(this);
    }
    var img = document.createElement('img');
    img.src = "image/abort.png";
    a.appendChild(img);
    td.appendChild(a);
    
    var fieldtext = document.createElement('input');
    fieldtext.type = 'text';
    fieldtext.id = 'typeaction';
    fieldtext.name = 'typeaction';
    fieldtext.style.visibility = 'hidden';
    fieldtext.style.width = '0px';
    fieldtext.style.margin = '0px';
    fieldtext.style.padding = '0px';
    fieldtext.style.overflow = 'hidden';
    fieldtext.value = '';
    td.appendChild(fieldtext);
    
    addSdruzVargrf();
    addSdruz_s();
  }
  
  function delSdruz(id) {
    if (confirm('opravdu odstranit vybraný záznam ?')) {
      res = document.createElement('input'); 
      res.type='text'; 
      res.name = 'typeaction'; 
      res.style.visibility = 'hidden'; 
      document.frm.appendChild(res); 
      res = document.createElement('input'); 
      res.type='text'; 
      res.name = 'idsdruz'; 
      res.value = id; 
      res.style.visibility = 'hidden'; 
      document.frm.appendChild(res); 
      document.frm['typeaction'].value = 'delete_sdruz'; 
      document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; 
      document.frm.submit();
    } else {
    // Do nothing!
    }
  }
</script>