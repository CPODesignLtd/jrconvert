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

require("Spojeni.php");
require("struct.php");

$dbname = 'savvy_mhdspoje';
$minprestup = 2;

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






$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
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


$dbname = 'savvy_mhdspoje';

    if (!($p = mysql_connect('mysql4.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL'))) {
        echo 'Could not connect to database';
        exit;
    }

mysql_query("SET NAMES 'latin2';");
    mysql_select_db($dbname);

    $sql = "DROP TEMPORARY TABLE IF EXISTS zlink;
CREATE TEMPORARY TABLE zlink
(
c_linky1 varchar(6),
c_tarif1 int(2),
c_zastavky1 int(4),

c_tarif2 int(2),
c_zastavky2 int(4),

c_linky2 varchar(6),
c_tarif3 int(2),
c_zastavky3 int(4),

c_tarif4 int(2),
c_zastavky4 int(4),

odjezd1 int(4),
prijezd1 int(4),

c_spojeodjezd2 int(2)

) ENGINE = MEMORY PACK_KEYS = 1 DEFAULT CHARSET=utf8;

insert into zlink
select
z.c_linky,
z.c_tarif,
z.c_zastavky,
z1.c_tarif,
z1.c_zastavky,
z2.c_linky,
z2.c_tarif,
z2.c_zastavky,
z3.c_tarif,
z3.c_zastavky,
spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek as odjezd1,
spoje.hh * 60 + spoje.mm + chronometrprijezd.doba_pocatek as prijezd1,
(select SQL_CACHE spoje.c_spoje from
spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = ".$loc." and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = ".$pac."

where spoje.idlocation = ".$loc." and spoje.packet = ".$pac." and
spoje.c_linky =z2.c_linky and
spoje.smer = case when (z2.c_tarif > z3.c_tarif) then 1 else 0 end and
                        (
                        (
                        spoje.pk1 in (".$pozn.")
                        OR spoje.pk2 in (".$pozn.")
                        OR spoje.pk3 in (".$pozn.")
                        OR spoje.pk4 in (".$pozn.")
                        OR spoje.pk5 in (".$pozn.")
                        OR spoje.pk6 in (".$pozn.")
                        OR spoje.pk7 in (".$pozn.")
                        OR spoje.pk8 in (".$pozn.")
                        OR spoje.pk9 in (".$pozn.")
                        OR spoje.pk10 in (".$pozn.")
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )
and
chronometrodjezd.c_tarif = z2.c_tarif and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= prijezd1 + 2 and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= prijezd1 + ".$minprestup." + 60
limit 1
) as c_spojeodjezd2

from

zaslinky z

join

zaslinky z1

on
z.idlocation = ".$loc." and z.c_zastavky = ".$odZ." and z.c_linky = z1.c_linky and
z.c_linky in (select c_linky from zaslinky where idlocation = ".$loc." and c_zastavky = ".$odZ.")
and not z.c_linky in (select c_linky from zaslinky where idlocation = ".$loc." and c_zastavky = ".$doZ.") and z.packet = ".$pac." and z1.packet = ".$pac."

join

zaslinky z2

on
z1.idlocation = ".$loc."
and z1.c_zastavky = z2.c_zastavky and z2.packet = ".$pac."

join

zaslinky z3

on
z3.idlocation = ".$loc." and z3.c_zastavky = ".$doZ." and z3.c_linky = z2.c_linky and not z2.c_zastavky = ".$odZ." and z3.packet = ".$pac."

join

spoje spoje

on spoje.idlocation = ".$loc." and spoje.packet = ".$pac." and
spoje.c_linky = z.c_linky and
spoje.smer = case when (z.c_tarif > z1.c_tarif) then 1 else 0 end and
                        (
                        (
                        spoje.pk1 in (".$pozn.")
                        OR spoje.pk2 in (".$pozn.")
                        OR spoje.pk3 in (".$pozn.")
                        OR spoje.pk4 in (".$pozn.")
                        OR spoje.pk5 in (".$pozn.")
                        OR spoje.pk6 in (".$pozn.")
                        OR spoje.pk7 in (".$pozn.")
                        OR spoje.pk8 in (".$pozn.")
                        OR spoje.pk9 in (".$pozn.")
                        OR spoje.pk10 in (".$pozn.")
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = ".$loc."
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )



join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = ".$loc." and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.c_tarif = z.c_tarif and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = ".$pac." and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= ".($h*60+$m)." and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= ".(($h+2)*60+$m)."

join

chronometr chronometrprijezd

on chronometrprijezd.idlocation = ".$loc." and chronometrprijezd.c_linky = spoje.c_linky and
chronometrprijezd.c_tarif = z1.c_tarif and chronometrprijezd.smer = spoje.smer and
chronometrprijezd.chrono = spoje.chrono and not chronometrprijezd.doba_jizdy = -1 and chronometrprijezd.packet = ".$pac.";


select * from
(select

c_linky1 as linka1,
(select nazev_linky from linky where idlocation = ".$loc." and c_linky = c_linky1 and packet = ".$pac." limit 1) as nazev_linky1,
(select doprava from linky where idlocation = ".$loc." and c_linky = c_linky1 and packet = ".$pac." limit 1) as doprava_linky1,
c_zastavky1 as zezastavky1,
(select nazev from zastavky where idlocation = ".$loc." and c_zastavky = c_zastavky1 and packet = ".$pac." limit 1) as nazev_zezstavky1,
c_tarif1 as zetarif1,
c_zastavky2 as dozastavky1,
(select nazev from zastavky where idlocation = ".$loc." and c_zastavky = c_zastavky2 and packet = ".$pac." limit 1) as nazev_dozstavky1,
c_tarif2 as dotarif1,
case when (c_tarif1 > c_tarif2) then 1 else 0 end as smer1,

c_linky2 as linka2,
(select nazev_linky from linky where idlocation = ".$loc." and c_linky = c_linky2 and packet = ".$pac." limit 1) as nazev_linky2,
(select doprava from linky where idlocation = ".$loc." and c_linky = c_linky2 and packet = ".$pac." limit 1) as doprava_linky2,
c_zastavky3 as zezastavky2,
(select nazev from zastavky where idlocation = ".$loc." and c_zastavky = c_zastavky3 and packet = ".$pac." limit 1) as nazev_zezstavky2,
c_tarif3 as zetarif2,
c_zastavky4 as dozastavky2,
(select nazev from zastavky where idlocation = ".$loc." and c_zastavky = c_zastavky4 and packet = ".$pac." limit 1) as nazev_dozstavky2,
c_tarif4 as dotarif2,
case when (c_tarif3 > c_tarif4) then 1 else 0 end as smer2,

odjezd1,
prijezd1 ,

c_spojeodjezd2,

(select  spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = ".$loc." and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = ".$pac." and spoje.packet = ".$pac."

where spoje.idlocation = ".$loc." and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif3 and not chronometrodjezd.doba_jizdy = -1
) odjezd2,

(select spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = ".$loc." and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = ".$pac." and spoje.packet = ".$pac."

where spoje.idlocation = ".$loc." and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif4 and not chronometrodjezd.doba_jizdy = -1
) prijezd2

 from zlink ) finish

 where not(c_spojeodjezd2 is null or prijezd2 is null);";

echo $sql;
    $result = mysql_query($sql);

//    CALL jeden_prestup_pack(".$loc.",".$pac.",".$odZ.",".$doZ.",".($h*60+$m).",".(($h+2)*60+$m).", 2 ,'".$pozn."');";

while ($data1 = mysql_fetch_row($result))
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

mysql_close($p);

$spojeni->vyhledaneSpoje = $res;

 $spojeni->sortSpojeni(true, true);

$res = $spojeni->vyhledaneSpoje;

$spojeni->move = $move;
$spojeni->createSeznam1() ;





/*$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
$sql = "
    CALL jeden_prestuppokus(".$loc.",".$odZ.",".$doZ.",".($h*60+$m).",".(($h+2)*60+$m).", 2 ,'".$pozn."',1);";

$mysqli->query("SET NAMES 'latin2';");
$query1 = $mysqli->query($sql);

while ($data1 = $query1->fetch_row())
{
  $mysqli1 = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);
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



/*$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

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



$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

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




$mysqli = new mysqli('mysql4.savvy.cz', 'savvy_mhdspoje', '13FO4mCL',  'savvy_mhdspoje', 3306);

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
