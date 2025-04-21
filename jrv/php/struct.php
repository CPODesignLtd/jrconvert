<?php

class Vector {
  var $elements = Array();

  function addElement($object) {
    $this->elements[] = $object;
  }

  function elementAt($index) {
    if (Count($this->elements) - 1 < $index) {
      return null;
    } else {
      return $this->elements[$index];
    }
  }

  function firstElement() {
    if (Count($this->elements) <= 0) {
      return null;
    } else {
      return $this->elements[0];
    }
  }

  function lastElement() {
    if (Count($this->elements) <= 0) {
      return null;
    } else {
      return $this->elements[Count($this->elements) - 1];
    }
  }

  function setElementAt($object, $index) {
    if (Count($this->elements) > $index) {
      $this->elements[$index] = $object;
    }
  }

  function removeElementAt($index) {
    $res = Array();
    $ii = 0;
    for ($i = 0; $i < Count($this->elements); $i++) {
      if ($i != $index) {
        $res[$ii] = $this->elements[$i];
        $ii++;
      }
    }
    $this->elements = $res;
  }

  function size() {
      return Count($this->elements);
  }
}

class TLinka {
  var $nazev = '';
  var $id_linky = null;
  var $typ_dopravy = '';
  var $trasa = null;
  var $chronoA = null;
  var $chronoB = null;
  var $odjezdy = null;
  var $enabled = true;
  var $linky = null;
  var $special = false;
  var $A;
  var $B;
  var $sA = false;
  var $sB = false;
  var $Cas;
  var $platnost_od = null;
  var $platnost_do = null;

  function clon() {
    $res = new TLinka();
    $res->nazev = $this->nazev;
    $res->id_linky = $this->id_linky;
    $res->typ_dopravy = $this->typ_dopravy;
    $res->trasa = $this->trasa;
    $res->chronoA = $this->chronoA;
    $res->chronoB = $this->chronoB;
    $res->odjezdy = $this->odjezdy;
    $res->enabled = $this->enabled;
    $res->linky = null;
    $res->special = $this->special;
    $res->A = $this->A;
    $res->B = $this->B;
    $res->sA = $this->sA;
    $res->sB = $this->sB;
    $res->Cas = $this->Cas;
    $res->platnost_do = $this->platnost_do;
    $res->platnost_od = $this->platnost_od;
    return $res;
  }

  function isValid($Y, $M, $D) {
/*        if ($this->special == false) {
            var $d1 = ($this->platnost_od.Year * 12 * 31) + ($this->platnost_od.Month * 31) + $this->platnost_od.Day;
            var $d2 = ($this->platnost_do.Year * 12 * 31) + ($this->platnost_do.Month * 31) + $this->platnost_do.Day;
            var $d = (Y * 12 * 31) + (M * 31) + D;
            return (((d1 <= d) && (d2 >= d)) ? true: false);
        } else {
            return true;
        }*/
    return true;
  }
}

class TLinky {
  var $Linka = null;

  function addLinka($nazevLinky, $id, $doprava) {
    $pomLinka = new TLinka();
    $pomLinka->nazev = $nazevLinky;
    $pomLinka->id_linky = $id;
    $pomLinka->typ_dopravy = $doprava;
    if ($this->Linka == null) {
      $this->Linka = new Vector();
    }
    $this->Linka->addElement($pomLinka);
  }

  function TrasaIntoLinka($xidlinky, $xtrasa) {
    for ($i = 0; $i < $this->Linka->size(); $i++) {
      if ($this->Linka->elementAt($i)->id_linky == $xidlinky) {
        $this->Linka->elementAt($i)->trasa = $xtrasa;
        $this->Linka->elementAt($i)->trasa->Linka = $this->Linka->elementAt($i);
        break;
      }
    }
  }

  function ChronoIntoLinka($xidlinky, $chrono, $smer) {
    for ($i = 0; $i < $this->Linka->size(); $i++) {
      if ($this->Linka->elementAt($i)->id_linky == $xidlinky) {
        if ($smer == 0) {
          if ($this->Linka->elementAt($i)->chronoA == null) {
            $this->Linka->elementAt($i)->chronoA = new Vector();
          }
          $this->Linka->elementAt($i)->chronoA->addElement($chrono);
        }
        if ($smer == 1) {
          if ($this->Linka->elementAt($i)->chronoB == null) {
            $this->Linka->elementAt($i)->chronoB = new Vector();
          }
          $this->Linka->elementAt($i)->chronoB->addElement($chrono);
        }
        break;
      }
    }
  }

  function OdjezdyIntoLinka($xidlinky, $odjezdy) {
    for ($i = 0; $i < $this->Linka->size(); $i++) {
      if ($this->Linka->elementAt($i)->id_linky == $xidlinky) {
        $this->Linka->elementAt($i)->odjezdy = $odjezdy;
        break;
      }
    }
  }
}

class TZastavka {
  var $id_zastavky;
  var $nazev;
  var $pk1;
  var $pk2;
  var $pk3;
  var $pk4;
  var $pk5;
  var $pk6;
  var $prestup;
  var $linky;

  function TZastavka($id_zastavky, $nazev, $pk1, $pk2, $pk3, $pk4, $pk5, $pk6, $prestup) {
    $this->id_zastavky = $id_zastavky;
    $this->nazev = $nazev;
    $this->pk1 = $pk1;
    $this->pk2 = $pk2;
    $this->pk3 = $pk3;
    $this->pk4 = $pk4;
    $this->pk5 = $pk5;
    $this->pk6 = $pk6;
    $this->prestup = $prestup;
    $this->linky = new Vector();
  }

  function addLinka($Linka) {
    $this->linky->addElement($Linka);
  }
}

function getZastavkabyID($Zastavky, $id_zastavky) {
  if ($Zastavky != null) {
    return $Zastavky->elementAt($id_zastavky - 1);
  }
}

function LoadZastavkyLinky($Zastavky, $Linky) {
  for ($i = 0; $i < $Linky->Linka->size(); $i++) {
    for ($ii = 0; $ii < $Linky->Linka->elementAt($i)->trasa->Zastavky->size(); $ii++) {
      getZastavkabyID($Zastavky, $Linky->Linka->elementAt($i)->trasa->Zastavky->elementAt($ii)->id_zastavky)->addLinka($Linky->Linka->elementAt($i));
    }
  }
}

function existLinkainLinky($Linka, $eLinka) {
  $res = false;
  if ($Linka->linky != null) {
    for ($i = 0; $i < $Linka->linky->size(); $i++) {
      if ($Linka->linky->elementAt($i) == $eLinka) {
        $res = true;
        break;
      }
    }
  }
  return $res;
}

function LoadLinkyLinky($Linky, $Zastavky) {
  for ($i = 0; $i < $Linky->Linka->size(); $i++) {
    for ($ii = 0; $ii < $Linky->Linka->elementAt($i)->trasa->Zastavky->size(); $ii++) {
      for ($iii = 0; $iii < getZastavkabyID($Zastavky, $Linky->Linka->elementAt($i)->trasa->Zastavky->elementAt($ii)->id_zastavky)->linky->size(); $iii++) {
        if ($Linky->Linka->elementAt($i) != getZastavkabyID($Zastavky, $Linky->Linka->elementAt($i)->trasa->Zastavky->elementAt($ii)->id_zastavky)->linky->elementAt($iii)) {
          if (existLinkainLinky($Linky->Linka->elementAt($i), getZastavkabyID($Zastavky, $Linky->Linka->elementAt($i)->trasa->Zastavky->elementAt($ii)->id_zastavky)->linky->elementAt($iii)) == false) {
            if ($Linky->Linka->elementAt($i)->linky == null) {
              $Linky->Linka->elementAt($i)->linky = new Vector();
            }
            $Linky->Linka->elementAt($i)->linky->addElement(getZastavkabyID($Zastavky, $Linky->Linka->elementAt($i)->trasa->Zastavky->elementAt($ii)->id_zastavky)->linky->elementAt($iii));
          }
        }
      }
    }
  }
}

class TZastavkaTrasy {
  var $id_zastavky;
  var $tarif;
  var $poradi;

  function TZastavkaTrasy($id_zastavky, $tarif) {
    $this->id_zastavky = $id_zastavky;
    $this->tarif = $tarif;
    $this->poradi = -1;
  }
}

class TTrasy {
  var $Zastavky = null;
  var $Linka = null;
  var $smer;

  function addZastavka($id_zastavky, $tarif) {
    if ($this->Zastavky == null ) {
      $this->Zastavky = new Vector();
    }
    $this->Zastavky->addElement(new TZastavkaTrasy($id_zastavky, $tarif));
    $this->Zastavky->lastElement()->poradi = $this->Zastavky->size();
  }

  function getTarif($id_zastavky) {
    $res = -1;
    for ($i = 0; $i < $this->Zastavky->size(); $i++) {
      if ($this->Zastavky->elementAt($i)->id_zastavky == $id_zastavky) {
        $res = $this->Zastavky->elementAt($i)->tarif;
        break;
      }
    }
    return $res;
  }

  function getTarifVector($id_zastavky) {
    $res = new Vector();
    for($i = 0; $i < $this->Zastavky->size(); $i++) {
      if ($this->Zastavky->elementAt($i)->id_zastavky == $id_zastavky) {
        $this->Zastavky->elementAt($i)->poradi = $i;
        $res->addElement($this->Zastavky->elementAt($i));
      }
    }
    return $res;
  }


  function loadTrasa($new, $smer, $id_linky) {
    if ($smer == 0) {
/*      $restrasa = new TTrasy();
      $restrasa->Linka = $this->Linka;
      $restrasa->smer = $smer;
      $restrasa->Zastavky = new Vector();
      for ($i = 0; $i < $this->Zastavky->size(); $i++) {
        $restrasa->Zastavky->addElement(new TZastavkaTrasy($this->Zastavky->elementAt($i)->id_zastavky, $this->Zastavky->elementAt($i)->tarif));
      }*/
      return $this;//$restrasa;
    } else {
/*      echo "document.write('smer trasy 1');";
      $restrasa = new TTrasy();
      $restrasa->Linka = $this->Linka;
      $restrasa->smer = $smer;
      $restrasa->Zastavky = new Vector();
      for ($i = $this->Zastavky->size() - 1; $i >= 0; $i--) {
        $restrasa->Zastavky->addElement(new TZastavkaTrasy($this->Zastavky->elementAt($i)->id_zastavky, $this->Zastavky->elementAt($i)->tarif));
      }
      return $restrasa;*/
    }
  }
}

class TChronoItem {
  var $c_tarif;
  var $c_zastavky;
  var $doba_jizdy;
  var $doba_pocatek;

  function TChronoItem($ct, $cz, $dj, $dp) {
    $this->c_tarif = $ct;
    $this->c_zastavky = $cz;
    $this->doba_jizdy = $dj;
    $this->doba_pocatek = $dp;
  }
}

class TChrono {
  var $idchrono;
  var $chrono = null;

  function TChrono($id) {
    $this->idchrono = $id;
  }

  function addChronoItem($ct, $cz, $dj, $dp) {
    if ($this->chrono == null) {
      $this->chrono = new Vector();
    }
    $pomChronoItem = new TChronoItem($ct, $cz, $dj, $dp);
    $this->chrono->addElement($pomChronoItem);
  }

  function getChronoItem($smer, $tarif) {
    $res = null;
    if ($smer != 0) {
    //            $tarif = $this->chrono->lastElement()->c_tarif - $tarif;
    }
    for ($i = 0; $i < $this->chrono->size(); $i++) {
    //            echo "document.write('idchrono = ".$this->idchrono." jizda = ".$this->chrono->elementAt($i)->doba_pocatek."');";
    //            echo "document.write('<BR>');";
      if ($this->chrono->elementAt($i)->c_tarif == $tarif) {
        $res = $this->chrono->elementAt($i);
        //                echo "document.write('nalezeno : idchrono = ".$this->idchrono." tarif = ".$tarif." jizda = ".$this->chrono->elementAt($i)->doba_pocatek."');";
        //                echo "document.write('<BR>');";
        break;
      }
    }
    return $res;
  }
}

class TOdjezdyItem {
  var $smer;
  var $c_spoje;
  var $c_zastavky;
  var $hh;
  var $mm;
  var $chrono;
  var $pk1;
  var $pk2;
  var $pk3;
  var $pk4;
  var $pk5;
  var $pk6;
  var $pk7;
  var $pk8;
  var $pk9;
  var $pk10;

  function TOdjezdyItem($smer, $c_spoje, $c_zastavky, $hh, $mm, $chrono, $pk1, $pk2, $pk3, $pk4, $pk5, $pk6, $pk7, $pk8, $pk9, $pk10) {
    $this->smer = $smer;
    $this->c_spoje = $c_spoje;
    $this->c_zastavky = $c_zastavky;
    $this->hh = $hh;
    $this->mm = $mm;
    $this->chrono = $chrono;
    $this->pk1 = $pk1;
    $this->pk2 = $pk2;
    $this->pk3 = $pk3;
    $this->pk4 = $pk4;
    $this->pk5 = $pk5;
    $this->pk6 = $pk6;
    $this->pk7 = $pk7;
    $this->pk8 = $pk8;
    $this->pk9 = $pk9;
    $this->pk10 = $pk10;
  }
}

class TOdjezd {
  var $H = null;
  var $M = null;
  var $chrono = null;
//  var $dobapocatekOD = null;
//  var $dobapocatekDO = null;


  function TOdjezd1($h, $m, $dpOD, $dpDO) {
    $this->H = $h;
    $this->M = $m;
    $this->dobapocatekOD = $dpOD;
    $this->dobapocatekDO = $dpDO;
  }

  function TOdjezd($h, $m, $chrono) {
    $this->H = $h;
    $this->M = $m;
    $this->chrono = $chrono;
  }

  function staviA1() {
    if ($this->dobapocatekOD != -1) {
      return true;
    } else {
      return false;
    }
  }

  function staviB1() {
    if ($this->dobapocatekDO != -1) {
      return true;
    } else {
      return false;
    }
  }

  function staviA($ZeTarif) {
/*    echo "document.write('".$ZeTarif." ".$this->chrono->getChronoItem(null, $ZeTarif)->doba_jizdy." ".$this->chrono->getChronoItem(null, $ZeTarif)->doba_pocatek." ".$this->chrono->idchrono."');";
    echo "document.write('<BR>');";*/
    if ($this->chrono->getChronoItem(null, $ZeTarif)->doba_jizdy != -1) {
      return true;
    } else {
      return false;
    }
  }

  function staviB($DoTarif) {
    if ($this->chrono->getChronoItem(null, $DoTarif)->doba_jizdy != -1) {
      return true;
    } else {
      return false;
    }
  }

  function getDobaPocatekOD($Smer, $ZeTarif) {
    return $this->chrono->getChronoItem($Smer, $ZeTarif)->doba_pocatek;
  }

  function getDobaPocatekDO($Smer, $DoTarif) {
    return $this->chrono->getChronoItem($Smer, $DoTarif)->doba_pocatek;
  }
}

class TOdjezdyFilter {
  var $odjezdy = null;

  function addOdjezd1($h, $m, $dpOD, $dpDO) {
    if ($this->odjezdy == null) {
      $this->odjezdy = new Vector();
    }
    $this->odjezdy->addElement(new TOdjezd($h, $m, $dpOD, $dpDO));
  }

  function addOdjezd($h, $m, $chrono) {
    if ($this->odjezdy == null) {
      $this->odjezdy = new Vector();
    }
    $this->odjezdy->addElement(new TOdjezd($h, $m, $chrono));
  }

  function getNejblizsiOdjezd1($H, $M) {
    $res = null;
    for ($i = 0; $i < $this->odjezdy->size(); $i++) {
      if (($this->odjezdy->elementAt($i)->H >= $H) && ($this->odjezdy->elementAt($i)->M >= $M)) {
        $res = $this->odjezdy->elementAt($i);
        break;
      }
    }
    return $res;
  }

   function getNejblizsiOdjezd($H, $M, $ZeTarif, $DoTarif) {
    $res = null;
    for ($i = 0; $i < $this->odjezdy->size(); $i++) {
      $chronoitem = $this->odjezdy->elementAt($i)->chrono->getChronoItem(null, $ZeTarif);
      if ($chronoitem->doba_jizdy != -1) {
        $m = (($this->odjezdy->elementAt($i)->M + $chronoitem->doba_pocatek) % 60);
        $h = (($this->odjezdy->elementAt($i)->H + (($this->odjezdy->elementAt($i)->M + $chronoitem->doba_pocatek) / 60)) % 24);
        if ((/*$this->odjezdy->elementAt($i)->H*/$h >= $H) && (/*$this->odjezdy->elementAt($i)->M*/$m >= $M)) {
          $res = $this->odjezdy->elementAt($i);
          break;
        }
      }
    }
    return $res;
  }
}

class TOdjezdy {
  var $odjezdy = null;

  function addOdjezd($smer, $c_spoje, $c_zastavky, $hh, $mm, $chrono, $pk1, $pk2, $pk3, $pk4, $pk5, $pk6, $pk7, $pk8, $pk9, $pk10) {
    if ($this->odjezdy == null) {
      $this->odjezdy = new Vector();
    }
    $pomOdjezd = new TOdjezdyItem($smer, $c_spoje, $c_zastavky, $hh, $mm, $chrono, $pk1, $pk2, $pk3, $pk4, $pk5, $pk6, $pk7, $pk8, $pk9, $pk10);
    $this->odjezdy->addElement($pomOdjezd);
  }

  function LoadOdjezdy($Smer, $Linka, $Trasa, $ZeTarif, $DoTarif, $typdne, $poznamky) {
    $res = null;
    if ($Linka != null) {
/*      echo "document.write('Odjezdy Linky = ".$Linka->nazev."');";
      echo "document.write('<BR>');";*/
      for ($i = 0; $i < $Linka->odjezdy->odjezdy->size(); $i++) {
        if ($Linka->odjezdy->odjezdy->elementAt($i)->smer == $Smer) {
          $write = true;
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk1 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk1);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk2 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk2);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk3 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk3);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk4 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk4);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk5 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk5);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk6 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk6);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk7 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk7);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk8 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk8);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk9 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk9);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }
          if ($Linka->odjezdy->odjezdy->elementAt($i)->pk10 != 0) {
            $ppozn = $poznamky->isCasPoznamka($Linka->odjezdy->odjezdy->elementAt($i)->pk10);
            if ($ppozn != null) {
              if ($poznamky->jede($ppozn, $typdne) == false) {
                $write = false;
              }
            }
          }

          if ($write == true) {
//            $phh = $Linka->odjezdy->odjezdy->elementAt($i)->hh;
//            $pmm = $Linka->odjezdy->odjezdy->elementAt($i)->mm;
            $h = $Linka->odjezdy->odjezdy->elementAt($i)->hh;
            $m = $Linka->odjezdy->odjezdy->elementAt($i)->mm;
            if ($Smer == 0) {
              $chrono = $Linka->chronoA;
            } else {
              $chrono = $Linka->chronoB;
            }
/*            $chronoitem = $chrono->elementAt($Linka->odjezdy->odjezdy->elementAt($i)->chrono - 1)->getChronoItem($Smer, $ZeTarif);
            $chronoitem1 = $chrono->elementAt($Linka->odjezdy->odjezdy->elementAt($i)->chrono - 1)->getChronoItem($Smer, $DoTarif);*/
//            if (($chronoitem != null) && ($chronoitem1 != null)) {
/*              $m = (($pmm + $chronoitem->doba_pocatek) % 60);
              $h = (($phh + ($pmm + $chronoitem->doba_pocatek) / 60) % 24);*/
              if ($res == null) {
                $res = new TOdjezdyFilter();
              }
/*              echo "document.write('".$h.":".$m." - ".$Linka->odjezdy->odjezdy->elementAt($i)->chrono."');";
              echo "document.write('<BR>');";*/
              $res->addOdjezd($h, $m, $chrono->elementAt($Linka->odjezdy->odjezdy->elementAt($i)->chrono - 1));//$chronoitem->doba_pocatek, $chronoitem1->doba_pocatek);
//            }
          }
        }
      }
    }
    return $res;
  }
}

class TPoznamka {
  var $id = null;
  var $oznaceni = null;
  var $popis = null;
  var $typdne = null;

  function TPoznamka($i, $ozn, $p, $t) {
    $this->id = $i;
    $this->oznaceni = $ozn;
    $this->popis = $p;
    $this->typdne = $t;
  }
}

class TPoznamky {
  var $casPoznamky = null;

  function addCasPoznamka($id, $ozn) {
    if ($this->casPoznamky == null) {
      $this->casPoznamky = new Vector();
    }
    $typ = null;
    if ($ozn == "X") { $typ = 0; }
    if ($ozn == "1") { $typ = 1; }
    if ($ozn == "2") { $typ = 2; }
    if ($ozn == "3") { $typ = 3; }
    if ($ozn == "4") { $typ = 4; }
    if ($ozn == "5") { $typ = 5; }
    if ($ozn == "6") { $typ = 6; }
    if ($ozn == "7") { $typ = 7; }
    if ($ozn == "+") { $typ = 7; }
    $this->casPoznamky->addElement(new TPoznamka($id, $ozn, null, $typ));
  }

  function isCasPoznamka($id) {
    $res = null;
    for ($i = 0; $i < $this->casPoznamky->size(); $i++) {
      if ($this->casPoznamky->elementAt($i)->id == $id) {
        $res = $this->casPoznamky->elementAt($i);
        break;
      }
    }
    return $res;
  }

  function jede($poznamka, $typdne) {
    $res = false;
    if ((($poznamka->typdne == 0) && ($typdne <= 5)) ||
        (($poznamka->typdne == $typdne)) ||
        (($poznamka->typdne == 7) && ($typdne == 7))) {
      $res = true;
    }
    return $res;
  }
}

class TDateTime {
  var $Cas;
  var $width;
  var $H;
  var $M;
  var $Day;
  var $Month;
  var $Year;
  var $Smer;
  var $Linka;
  var $typdne;

  function setHM($h, $m) {
    $this->H = $h;
    $this->M = $m;
  }

  function setDMY($day, $month, $year) {
    $this->Day = $day;
    $this->Month = $month;
    $this->Year = $year;
  }
}

function getTypDneofDate($d, $m, $y) {
  $pstoleti = array (0, 5, 3, 1);
  $pmesic = array (0, 6, 2, 2, 5, 7, 3, 5, 1, 4, 6, 2, 4);
  $a = $pstoleti[(integer)(($y / 100) % 4)];
  $b = $y % 100;
  $c = (integer)($b / 4);
  $e = $b / 4;
  $d = $pmesic[$m];
  $vysledek = ((($a + $b + $c + $d + $d) % 7));
  if ($vysledek == 0) {
    $vysledek = 7;
  }
  return $vysledek;
}

function get_Date($d, $m, $y, $index) {
  $pdenmesic = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  $pocetdnimesic = $pdenmesic[$m - 1];
  if ($d + $index > $pocetdnimesic) {
    $d = (($d + $index) - $pocetdnimesic);
    $m++;
    if ($m > 12) {
      $m = ($m - 12);
      $y++;
    }
  } else {
    $d = ($d + $index);
  }
  $t = new TDateTime();
  $t->setDMY($d, $m, $y);
  return $t;
}

?>
