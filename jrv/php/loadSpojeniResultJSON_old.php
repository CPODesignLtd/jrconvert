<?php

$loc = $_GET['location'];
$odZ = $_GET['zOD'];
$doZ = $_GET['zDO'];
$h = $_GET['h'];
$m = $_GET['m'];

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

$pac = $_GET['packet'];

require_once 'Spojeni.php';
require_once 'struct.php';

class TPoznamkaElement {

  var $zkratka = null;
  var $popis = null;
  var $show = null;
  var $time = null;
  var $pic = null;

}

$TPoznamky = new Vector();

$dbname = 'savvy_mhdspoje';
$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);

$sql = "SELECT c_kodu, oznaceni, rezerva, caspozn, showing, obr FROM pevnykod where idlocation = " . $loc . " and packet = " . $pac . " order by c_kodu;";

$result = $mysqli->query($sql);
//$result = mysql_query($sql);

while ($row = $result->fetch_row()) {
  $poz = new TPoznamkaElement();
  $poz->zkratka = $row[1];
  $poz->popis = $row[2];
  $poz->time = $row[3];
  $poz->show = $row[4];
  $poz->pic = $row[5];
  $TPoznamky->addElement($poz);
}

$sql = "SELECT datum, pk FROM kalendar where datum = '" . date_format(new DateTime($datumJR), 'Y-m-d') . "' and idlocation = " . $loc . " and packet = " . $pac . " order by pk;";

$result = $mysqli->query($sql);

$pozn = '';

while ($row = $result->fetch_row()) {
//while ($row = mysql_fetch_row($result)) {
  if ($pozn != '') {
    $pozn .= ",";
  }
  $pozn .= $TPoznamky->elementAt($row[1])->zkratka;
}


/*if ($sloupec->vargrf->size() == 0) {
  $vargrfs = $typyGrfDay[date_format(new DateTime($datumJR), 'D')];
  for ($i = 0; $i < count($vargrfs); $i++) {
    for ($ii = 0; $ii < $TPoznamky->size(); $ii++) {
      if (($TPoznamky->elementAt($ii)->zkratka == $vargrfs[$i]) && ($TPoznamky->elementAt($ii)->time == true)) {
        $sloupec->vargrf->addElement($ii);
        break;
      }
    }
  }
}*/


$sql = " CALL prima_linka_pack(" . $loc . "," . $pac . "," . $_GET[zOD] . "," . $_GET[zDO] . "," . ($h * 60 + $m) . "," . (($h + 2) * 60 + $m) . ",'" . $pozn . "');";

$mysqli->query("SET NAMES 'utf-8';");
$query1 = $mysqli->query($sql);

$res = new Vector();
$spojeni = new TSpojeni();

while ($data1 = $query1->fetch_row()) {
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

$mysqli = new mysqli('fssoftware.brn.savvy.cz', 'savvy_mhdspoje', '13FO4mCL', 'savvy_mhdspoje', 3306);
$sql = "
    CALL jeden_prestup_pack(" . $loc . "," . $pac . "," . $_GET[zOD] . "," . $_GET[zDO] . "," . ($h * 60 + $m) . "," . (($h + 2) * 60 + $m) . ", 2 ,'" . $pozn . "');";

$mysqli->query("SET NAMES 'utf-8';");
$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row()) {
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
//$spojeni->createSeznamJSON();

echo $_GET['callback'] . "(" . json_encode($spojeni->createSeznamJSON()) . ");";

?>
