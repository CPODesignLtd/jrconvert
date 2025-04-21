<?php
if (isset($_GET["remove"])) {
  $dbname = 'savvy_mhdspoje';

  if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
  } else {

    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    if ($_GET["remove"][key($_GET["remove"])] == "potvrdit") {
      $hodnota = 1;
    } else {
      $hodnota = 0;
    }
    $sql = "UPDATE packets SET jeplatny = " . $hodnota . " WHERE location = " . $location . " AND packet = " . key($_GET["remove"]);

    $result = mysql_query($sql);
    
    mysql_close($p);
  }
}
?>
<form action = "" method = "POST">
<table style = "font-family: sans-serif; font-size: 16px; text-align: center;">
  <tr>
    <td style = "padding-left: 10px">
      <a style = "font-weight: bold; text-align: center;">Èíslo balíèku</a>
    </td>
    <td style = "padding-left: 10px">
      <a style = "font-weight: bold; text-align: center;">Platný OD</a>
    </td>
    <td style = "padding-left: 10px">
      <a style = "font-weight: bold; text-align: center;">Platný DO</a>
    </td>      
    <td style = "padding-left: 25px"></td>      
  </tr>

  <?php
  $location = $_GET['location'];

  $dbname = 'savvy_mhdspoje';

  if (!($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
    echo 'Could not connect to database';
  } else {

    mysql_query("SET NAMES 'utf-8';");
    mysql_select_db($dbname);

    $sql = "SELECT packet, jr_od, jr_do, jeplatny FROM packets WHERE location = " . $location . " ORDER BY packet";

    $result = mysql_query($sql);

    while ($row = mysql_fetch_row($result)) {
      ?>
      <tr>
        <td>
          <a style = "text-align: center;"><?php echo $row[0]; ?></a>
        </td>
        <td>
          <a style = "text-align: center;"><?php echo $row[1]; ?></a>
        </td>
        <td>
          <a style = "text-align: center;"><?php echo $row[2]; ?></a>
        </td>
        <td>
          <?php
            if ($row[3] == 1) {
              ?>
          <input type = "submit" name = "remove[<?php echo $row[0]; ?>]" value = "zrusit"></input>
          <?php
            } else {
              ?>
          <input type = "submit" name = "remove[<?php echo $row[0]; ?>]" value = "potvrdit"></input>
          <?php
            }
            ?>
        </td>
      </tr>
      <?php
    }
  }

  mysql_close($p);
  ?>
</table>
</form>