<?php
if ($_POST['typeaction'] == 'delete_sloupec') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "delete from jrvargrfs where idtimepozn = (select idtimepozn from jrtypes where c_sloupce = " . $_POST['csloupce'] . 
         " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'] . ")";
  mysql_query($sql);  
//  echo $sql . "</br>";
  $sql = "delete from jrtypes where c_sloupce = " . $_POST['csloupce'] . 
         " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);  
//  echo $sql . "</br>";  
  $sql = "update jrtypes set c_sloupce = c_sloupce - 1 where c_sloupce > " . $_POST['csloupce'] . 
         " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);  
//  echo $sql . "</br>";
}

if ($_POST['typeaction'] == 'accept_write_sloupec') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "UPDATE jrtypes set nazev_sloupce = '" . $_POST['sloupcenazev'] . "' where c_sloupce = " . $_POST['csloupce'] . 
         " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack'];
  mysql_query($sql);  
//  echo $sql . "</br>";
  $sql = mysql_query("select IDTIMEPOZN from jrtypes where c_sloupce = " . $_POST['csloupce'] . 
         " AND idlocation = " . getLocation($_POST["username"]) . " AND packet = " . $_GET['pack']);
  $row = mysql_fetch_row($sql);
  $sql = "delete from jrvargrfs where IDTIMEPOZN = " . $row[0];
  mysql_query($sql);
//  echo $sql . "</br>";
  for ($c = 0; $c < count($_POST['acceptpozn']); $c++) {
    $sql = "INSERT INTO jrvargrfs (IDTIMEPOZN, C_KODU) VALUES (" . $row[0] . ", " . $_POST['acceptpozn'][$c] . ")";
    mysql_query($sql);
//    echo $sql."</br>";
  }
//  echo  mysql_insert_id();
}

if ($_POST['typeaction'] == 'insert_write_sloupec') {
  //$connect = mysql_connect($con_server, $con_db, $con_pass);
  //mysql_select_db($con_db);  
  $sql = "INSERT INTO jrtypes (NAZEV_SLOUPCE, C_SLOUPCE, IDLOCATION, PACKET) VALUES ('" . $_POST['sloupcenazev'] . "', " . $_POST['csloupce'] . 
         ", " . getLocation($_POST["username"]) . ", " . $_GET['pack'] . ")";
  $q = mysql_query($sql);  
//  echo $sql . "</br>";
  $timepozn = mysql_insert_id($q);
  for ($c = 0; $c < count($_POST['acceptpozn']); $c++) {
    $sql = "INSERT INTO jrvargrfs (IDTIMEPOZN, C_KODU) VALUES (" . $timepozn . ", " . $_POST['acceptpozn'][$c] . ")";
    echo $sql;
    mysql_query($sql);
//    echo $sql."</br>";
  }
}

//$connect = mysql_connect($con_server, $con_db, $con_pass);
//mysql_select_db($con_db);
//mysql_query("SET NAMES 'cp1250';");
$sql = mysql_query("SELECT c_sloupce, nazev_sloupce, idtimepozn
          FROM jrtypes WHERE packet = " . $_GET['pack'] . " and idlocation=" . getLocation($_POST["username"]) . " order by c_sloupce");
?>        

<div class="separdivglobalnapis" style="clear: both;">Sloupce JŘ balíčku č. <?php echo $_GET['pack']; ?></div>

<form style="float: left;" enctype="multipart/form-data" name="frm" method="post" action="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=3">

  <table id="table_linky" class="t_akce" style="clear: both; float: none;">
    <tr>
      <th>č. sloupce</th>
      <th>název sloupce</th>      
      <th>varianty grafikonu</br>"časové poznámky"</th>
    </tr>
    <?php
    $i = 1;
    while ($row = mysql_fetch_row($sql)) {
      ?>
      <tr id="table_sloupce_row<?php echo $i; ?>">
        <td class="last" style="font-size: 15px; font-weight: normal; font-style: italic; width: auto;"><?php echo $row[0]; ?></td>
          <?php
          if ((isset($_GET['id'])) && ($_GET['id'] == $row[0])) {
          ?>
            <td class="last" style="font-size: 15px; font-weight: bold; width: <?php echo ($_GET['id'] == $row[0]) ? 'auto': '100%'; ?>; text-align: left; vertical-align: <?php echo ($_GET['id'] == $row[0]) ? 'top': 'middle'; ?>;">  
              <input type="text" name="sloupcenazev" id="sloupcenazev" style="width: 100%;" value="<?php echo $row[1]; ?>">  
            </td>  
          <?php
          } else {
          ?>      
            <td class="last" style="font-size: 15px; font-weight: bold; width: 100%; text-align: left; vertical-align: middle;"><?php echo $row[1]; ?></td>
          <?php
          }
          ?>
        <td class="last" style="font-size: 15px; font-weight: bold; width: auto;">
          <?php
          if (!isset($_GET['id'])) {
            $sql1 = mysql_query("SELECT oznaceni, obr 
                    FROM jrvargrfs left outer join pevnykod on (jrvargrfs.c_kodu = pevnykod.c_kodu) WHERE pevnykod.packet = " . $_GET['pack'] . " and pevnykod.idlocation=" . getLocation($_POST["username"]) . 
                    " and jrvargrfs.idtimepozn = " . $row[2]);          
            $first = true;
            while ($row1 = mysql_fetch_row($sql1)) {
              if ($first == false) {
                echo ', ';
              }
              if ($row1[1] == '') {
                echo $row1[0];
              } else {
                ?>
                  <img src="../pictogram/<?php echo $row1[1]; ?>"> 
                <?php
              }
              $first = false;
            }
          } else {
            if ($_GET['id'] == $row[0]) {
              $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr,   
                      case when (select distinct c_kodu from jrvargrfs where jrvargrfs.c_kodu = pevnykod.c_kodu and jrvargrfs.idtimepozn = " . $row[2] . " limit 1) is null then 0 else 1 end as accept 
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");              
              ?>
              <table id="table_pozn" class="t_akce" style="clear: both; float: none; width: 100%;">
              <?php  
              while ($row1 = mysql_fetch_row($sql1)) {
                ?>                
                  <tr>
                    <td class="last" style="font-size: 15px; font-weight: normal; width: auto;">
                      <input type="checkbox" name="acceptpozn[]" value="<?php echo $row1[0]; ?>" <?php echo ($row1[4] == 1) ? 'checked': ''; ?>>
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
        <td class="first" style="vertical-align: <?php echo ($_GET['id'] == $row[0]) ? 'top': 'middle'; ?>;">
        <?php
          if (!isset($_GET['id'])) {
        ?>              
            <a name="edit_sloupec" style="color: #ffffff;" href="?page=2&pack=<?php echo $_GET['pack']; ?>&sub=<?php echo $_GET['sub']; ?>&id=<?php echo $row[0]; ?>" onClick="app_href(this);" title="Editace sloupce"><img src="image/pencil.png"></a>
            &nbsp;
            <a name="del_sloupec" style="color: #ffffff; word-wrap: nowrap;" title="Smazat" onClick="delSloupec('<?php echo $i; ?>');"><img src="image/delete.png"></a>                        
        <?php
          } else {
            if ($_GET['id'] == $row[0]) {
        ?>
              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm['typeaction'].value = 'accept_write_sloupec'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>                
              &nbsp;             
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=<?php echo $_GET['sub']; ?>&pack=<?php echo $_GET['pack']; ?>" onClick="app_href(this);"><img src="image/abort.png"></a>    
              <input id="csloupce" name="csloupce" type="text" value="<?php echo $i; ?>" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
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
      <div class="button" id="add_sloupec" style="height: 35px; width: 150px; visibility: visible;" title="Přidat sloupec" onclick="alert('click'); addSloupec(<?php echo $i++; ?>)">   
        <span></span><img src="image/addplus.png">
          přidat sloupec
      </div>
<!--      <div id="add_sloupec" style="cursor: pointer;" class="wraptocenter" onClick="addSloupec(<?php echo $i++; ?>)" title="Přidat sloupec"><span></span><img src="image/addplus.png">&nbsp;-&nbsp;přidat sloupec</div>-->
<!--        <a id="add_sloupec" style="color: #ffffff;" onClick="addSloupec(<?php echo $i++; ?>)" title="Přidat sloupec"><img src="image/addplus.png"></a>-->
  </table>
</form>

<?php
  if (isset($_GET['id'])) {
?>
  <script  type='text/javascript'>
    document.getElementById('add_sloupec').style.visibility = 'hidden';
  </script>  
<?php
  }
?>

<script  type='text/javascript'>
  function addVargrf() {
    <?php
    $sql1 = mysql_query("select c_kodu, oznaceni, rezerva, obr 
                      from pevnykod where caspozn = 1 and idlocation = " . getLocation($_POST["username"]) . " and packet = " . $_GET['pack'] . " order by c_kodu");
    ?>
    var vargrf = document.getElementById('td_insertvargrf');
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
        fieldtext.name = 'acceptpozn[]';        
        fieldtext.value = '<?php echo $row1[0]; ?>';    
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

  function addSloupec(c_sloupce) {
    alert('insert');  
    var nc = new Array(); 
    nc = document.getElementsByName('edit_sloupec');
    for(i=0; i<nc.length; i++) {
      if (nc[i] != null) {
        tag = nc[i]
        tag.style.visibility = 'hidden'
      }
    }
    nc = document.getElementsByName('del_sloupec');
    for(i=0; i<nc.length; i++) {
      if (nc[i] != null) {
        tag = nc[i]
        tag.style.visibility = 'hidden'
      }
    }
    document.getElementById('add_sloupec').style.visibility = 'hidden';    
    
    var tablef = document.getElementById('table_linky');
    var row = tablef.insertRow(c_sloupce);    

    var td = row.insertCell(0);    
    td.className = "last";
    td.style.fontSize = '15px';
    td.style.fontWeight = 'normal';
    td.style.fontStyle = 'italic';
    td.style.width = 'auto';
    td.innerHTML = c_sloupce;



    var td = row.insertCell(1);    
    td.className = "last";
    td.style.verticalAlign = 'top';
    td.style.fontSize = '15px';
    td.style.fontWeight = 'normal';
    td.style.fontStyle = 'italic';
    td.style.width = 'auto';

    var fieldtext = document.createElement('input');
    fieldtext.type = 'text';
    fieldtext.id = 'sloupcenazev';
    fieldtext.name = 'sloupcenazev';
    fieldtext.style.width = '100%';
    fieldtext.value = '';    
    td.appendChild(fieldtext);
    fieldtext.focus();

    var td = row.insertCell(2);    
    td.className = "last";
    td.id = "td_insertvargrf";
    td.style.fontSize = '15px';
    td.style.fontWeight = 'normal';
    td.style.fontStyle = 'italic';
    td.style.width = 'auto';    
    

    var td = row.insertCell(3);
    td.className = "first";
    td.style.verticalAlign = 'top';
    
    var a = document.createElement('a');
    a.style.color = '#ffffff';
    a.style.wordWrap = 'nowrap';
    a.title = 'Zapsat';
    a.onclick = function() {
      document.frm['typeaction'].value = 'insert_write_sloupec'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();      
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
    fieldtext.id = 'csloupce';
    fieldtext.name = 'csloupce';
    fieldtext.style.visibility = 'hidden';
    fieldtext.style.width = '0px';
    fieldtext.style.margin = '0px';
    fieldtext.style.padding = '0px';
    fieldtext.style.overflow = 'hidden';
    fieldtext.value = c_sloupce;
    td.appendChild(fieldtext);
    
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
    
    addVargrf();
/*              <a style="color: #ffffff; word-wrap: nowrap;" title="Zapsat" onClick="document.frm[''typeaction''].value = 'accept_write_sloupec'; document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; document.frm.submit();"><img src="image/accept.png"></a>                
              &nbsp;
              <a style="color: #ffffff; word-wrap: nowrap;" title="Odvolat" href="?page=2&sub=&pack=" onClick="app_href(this);"><img src="image/abort.png"></a>    
              <input id="clinky" name="csloupce" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
              <input id="'typeaction'" name="'typeaction'" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">                              
  */  

  }
  
  function delSloupec(id) {
    if (confirm('opravdu odstranit vybraný záznam ?')) {
      res = document.createElement('input'); 
      res.type='text'; 
      res.name = 'typeaction'; 
      res.style.visibility = 'hidden'; 
      document.frm.appendChild(res); 
      res = document.createElement('input'); 
      res.type='text'; 
      res.name = 'csloupce'; 
      res.value = id; 
      res.style.visibility = 'hidden'; 
      document.frm.appendChild(res); 
      document.frm['typeaction'].value = 'delete_sloupec'; 
      document.frm.action = document.frm.action + '&scup=' + getScrollXY()[1]; 
      document.frm.submit();      
    } else {
    // Do nothing!
    }
  }  
</script>