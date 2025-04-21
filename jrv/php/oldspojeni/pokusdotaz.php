<?php

$location = $_GET['loc'];
$odZ = $_GET['z1'];
$doZ = $_GET['z2'];
$h = $_GET['h'];
$m = $_GET['m'];
$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];
$pozn = $_GET['po'];
if ($pozn == "") {
    $pozn = "null";
}
$move = $_GET['move'];
$pac = $_GET['pac'];


if (isset($_GET['datum'])) {
  $dob1 = trim($_GET['datum']);
  list($param_day, $param_month, $param_year) = explode('_', $dob1);
  $mk = mktime(0, 0, 0, $param_month, $param_day, $param_year);
  $datumJR = date('Y-m-d', $mk);
} else {
  $datumJR = date('Y-m-d');
}

$day = date_format(new DateTime($datumJR), 'd');
$month = date_format(new DateTime($datumJR), 'm');
$year = date_format(new DateTime($datumJR), 'Y');

require("Spojeni.php");
require("struct.php");

$dbname = 'savvy_mhdspoje';

/*$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

$sql = "
    CALL prima_linka(0,1,2,12*60,14*60,'1');";

$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
echo "document.write('".$data1[0]."');";
echo "document.write('<BR>');";
}

echo "document.write('<BR>');";
echo "document.write('<BR>');";*/


$sql = "SELECT datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $location . " and packet = " . $pac . " order by pk;";

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$mysqli->query("SET NAMES 'utf-8';");
$result = $mysqli->query($sql);

$varGRF = '';

while ($row = $result->fetch_row()) {
  if ($varGRF != '') {
    $varGRF = $varGRF . ',';
  }
  $varGRF = $varGRF . $row[1];
}

$pozn = $varGRF;

//echo $pozn;

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
//mysql_query("SET NAMES 'latin2';");
//$mysqli_query("SET NAMES 'utf-8';");
$sql = " CALL prima_linka_pack(".$loc.",".$pac.",".$odZ.",".$doZ.",".($h*60+$m).",".(($h+2)*60+$m).",'".$pozn."');";

//$mysqli->query("SET NAMES 'latin2';");
$mysqli->query("SET NAMES 'utf-8';");
$query1 = $mysqli->query($sql);

$res = new Vector();
$spojeni = new TSpojeni();

while ($data1 = $query1->fetch_row())
{
  $Spoj = new TCastSpoj();
  $Spoj->Linka = $data1[0];
  $Spoj->nazev_linky = $data1[1];
  $Spoj->doprava = $data1[2];
  $Spoj->SpojDetail = new TSpojDetail();
  $Spoj->SpojDetail->Smer = $data1[9];
  $Spoj->SpojDetail->ZeZastavky = $data1[4];
  $Spoj->SpojDetail->ZeTarif = $data1[5];
  $Spoj->SpojDetail->DoZastavky = $data1[7];
  $Spoj->SpojDetail->DoTarif = $data1[8];

  $OP = new TOdjezdPrijezd();
  $OP->Odjezd = new TCasInter(($data1[10] / 60) % 24, $data1[10] - ((($data1[10] / 60) % 24) * 60), 0);
  $OP->Odjezd->DD = 12;
  $OP->Odjezd->MM = 4;
  $OP->Odjezd->YYYY = 2010;

  $OP->Prijezd = new TCasInter(($data1[11] / 60) % 24, $data1[11] - ((($data1[11] / 60) % 24) * 60), 0);
  $OP->Prijezd->DD = 12;
  $OP->Prijezd->MM = 4;
  $OP->Prijezd->YYYY = 2010;


  $Spoj->SpojDetail->OdjezdPrijezd = new Vector();
  $Spoj->SpojDetail->OdjezdPrijezd->addElement($OP);


  $addTrasa = new Vector();
  $addTrasa->addElement($Spoj);

  if ($spojeni->porovnejSpoje($addTrasa, $res) == -1) {
    $res->addElement($addTrasa);
  }
}

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
$sql = "
    CALL jeden_prestup_pack(".$loc.",".$pac.",".$odZ.",".$doZ.",".($h*60+$m).",".(($h+2)*60+$m).", 2 ,'".$pozn."');";

//$mysqli->query("SET NAMES 'latin2';");
$mysqli->query("SET NAMES 'utf-8';");
$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
  $Spoj = new TCastSpoj();
  $Spoj->Linka = $data1[0];
  $Spoj->nazev_linky = $data1[1];
  $Spoj->doprava = $data1[2];
  $Spoj->SpojDetail = new TSpojDetail();
  $Spoj->SpojDetail->Smer = $data1[9];
  $Spoj->SpojDetail->ZeZastavky = $data1[4];
  $Spoj->SpojDetail->ZeTarif = $data1[5];
  $Spoj->SpojDetail->DoZastavky = $data1[7];
  $Spoj->SpojDetail->DoTarif = $data1[8];

  $OP = new TOdjezdPrijezd();
  $OP->Odjezd = new TCasInter(($data1[20] / 60) % 24, $data1[20] - ((($data1[20] / 60) % 24) * 60), 0);
  $OP->Odjezd->DD = 12;
  $OP->Odjezd->MM = 4;
  $OP->Odjezd->YYYY = 2010;

  $OP->Prijezd = new TCasInter(($data1[21] / 60) % 24, $data1[21] - ((($data1[21] / 60) % 24) * 60), 0);
  $OP->Prijezd->DD = 12;
  $OP->Prijezd->MM = 4;
  $OP->Prijezd->YYYY = 2010;


  $Spoj->SpojDetail->OdjezdPrijezd = new Vector();
  $Spoj->SpojDetail->OdjezdPrijezd->addElement($OP);


  $addTrasa = new Vector();
  $addTrasa->addElement($Spoj);

  $Spoj = new TCastSpoj();
  $Spoj->Linka = $data1[10];
  $Spoj->nazev_linky = $data1[11];
  $Spoj->doprava = $data1[12];
  $Spoj->SpojDetail = new TSpojDetail();
  $Spoj->SpojDetail->Smer = $data1[19];
  $Spoj->SpojDetail->ZeZastavky = $data1[14];
  $Spoj->SpojDetail->ZeTarif = $data1[15];
  $Spoj->SpojDetail->DoZastavky = $data1[17];
  $Spoj->SpojDetail->DoTarif = $data1[18];

  $OP = new TOdjezdPrijezd();
  $OP->Odjezd = new TCasInter(($data1[23] / 60) % 24, $data1[23] - ((($data1[23] / 60) % 24) * 60), 0);
  $OP->Odjezd->DD = 12;
  $OP->Odjezd->MM = 4;
  $OP->Odjezd->YYYY = 2010;

  $OP->Prijezd = new TCasInter(($data1[24] / 60) % 24, $data1[24] - ((($data1[24] / 60) % 24) * 60), 0);
  $OP->Prijezd->DD = 12;
  $OP->Prijezd->MM = 4;
  $OP->Prijezd->YYYY = 2010;


  $Spoj->SpojDetail->OdjezdPrijezd = new Vector();
  $Spoj->SpojDetail->OdjezdPrijezd->addElement($OP);

  $addTrasa->addElement($Spoj);


  if ($spojeni->porovnejSpoje($addTrasa, $res) == -1) {
    $res->addElement($addTrasa);
  }
}

$spojeni->vyhledaneSpoje = $res;

 $spojeni->sortSpojeni(true, true);

$res = $spojeni->vyhledaneSpoje;

$spojeni->move = $move;
$spojeni->createSeznam2() ;


/*$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
$sql = "
    CALL jeden_prestuppokus(".$loc.",".$odZ.",".$doZ.",".($h*60+$m).",".(($h+2)*60+$m).", 2 ,'".$pozn."',1);";

$mysqli->query("SET NAMES 'latin2';");
$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
  $mysqli1 = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
  $sql1 = "
    CALL jeden_prestuppokus(".$loc.",".$data1[6].",".$doZ.",".$data1[10].",".(($h+2)*60+$m).", 2 ,'".$pozn."',2);";

  $mysqli1->query("SET NAMES 'latin2';");
  $query2 = $mysqli1->query($sql);

  while ($data2 = $query2->fetch_row())
  {
echo "document.write('".$data2[0]."');";
echo "document.write('<BR>');";
  }
}*/




/*for($i = 0; $i < $res->size(); $i++) {
  for($ii = 0; $ii < $res->elementAt($i)->size(); $ii++) {
  $spoj = $res->elementAt($i)->elementAt($ii);
  echo "document.write('".$spoj->SpojDetail->ZeZastavky." - ".$spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H." : ".$spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M.
  " - ".$spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H." : ".$spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M."             ');";
  }
  echo "document.write('<BR>');";
}*/



/*$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

$sql = "
    CALL dva_prestup(0,1,2,12*60,14*60,'1',1);";

$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
echo "document.write('".$data1[0]."');";
echo "document.write('<BR>');";
}

echo "document.write('<BR>');";
echo "document.write('<BR>');";



$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

$sql = "
    CALL dva_prestup(0,1,2,12*60,14*60,'1',2);";

$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
echo "document.write('".$data1[0]."');";
echo "document.write('<BR>');";
}

echo "document.write('<BR>');";
echo "document.write('<BR>');";




$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

$sql = "
    CALL dva_prestup(0,1,2,12*60,14*60,'1',3);";

$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
echo "document.write('".$data1[0]."');";
echo "document.write('<BR>');";
}

echo "document.write('<BR>');";
echo "document.write('<BR>');";*/


?>
