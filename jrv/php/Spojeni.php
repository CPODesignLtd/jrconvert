<?php

//require("struct.php");

$location = $_GET['loc'];
$odZ = $_GET['z1'];
$doZ = $_GET['z2'];
$h = $_GET['h'];
$m = $_GET['m'];
$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

//require("LoadSpojeni.php");

class TDepozitOdjezd {

  var $d;
  var $m;
  var $y;
  var $smer;
  var $idlinka;
  var $Odjezdy;

  function TDepozitOdjezd($nd, $nm, $ny, $id, $sm, $nodjezdy) {
    $this->d = $nd;
    $this->m = $nm;
    $this->y = $ny;
    $this->smer = $sm;
    $this->idlinka = $id;
    $this->Odjezdy = $nodjezdy;
  }

}

class TSpojDetail {

  var $Smer = -1;
  var $ZeTarif = -1;
  var $DoTarif = -1;
  var $ZeZastavky = -1;
  var $DoZastavky = -1;
  var $Trasa = null;
  var $Odjezdy = null;
  var $OdjezdPrijezd = null;

}

class TCastSpoj {

  var $Linka = null;
  //nove
  var $nazev_linky = null;
  var $doprava = null;
  //-- nove
  var $Trasa = null;
  var $prunik = null;
  var $SpojDetail = null;
  var $vaha = 0;

  function clon() {
    $res = new TCastSpoj();
    $res->Linka = $this->Linka;
    $res->Trasa = $this->Trasa;
    $res->prunik = $this->prunik;
    $res->vaha = 0;
    $res->SpojDetail = new TSpojDetail();
    $res->SpojDetail->DoTarif = $this->SpojDetail->DoTarif;
    $res->SpojDetail->DoZastavky = $this->SpojDetail->DoZastavky;
    $res->SpojDetail->Smer = $this->SpojDetail->Smer;
    $res->SpojDetail->ZeTarif = $this->SpojDetail->ZeTarif;
    $res->SpojDetail->ZeZastavky = $this->SpojDetail->ZeZastavky;
    $res->SpojDetail->Trasa = $this->SpojDetail->Trasa;
    $res->SpojDetail->Odjezdy = $this->SpojDetail->Odjezdy;
    return $res;
  }

}

class TOdjezdPrijezd {

  var $Odjezd = null;
  var $Prijezd = null;

}

class TDetail {

  var $Linka = null;
  var $pred = null;
  var $po = null;
  var $Detail = null;

}

class TNullLinky {

  var $Linka = null;
  var $Smer = -1;
  var $ZaZastavky = -1;

}

class TCasInter {

  var $H;
  var $M;
  var $plusden = 0;
  var $DD;
  var $MM;
  var $YYYY;

  function TCasInter($h, $m, $plus) {
    $this->H = $h;
    $this->M = $m;
    $this->plusden = $plus;
  }

}

class TSpojeni {

  var $OdZastavky;
  var $DoZastavky;
  var $Linky;
  var $Zastavky;
  var $Poznamky;
  var $TypDne;
  var $vychoziCas;
  var $vychoziCasO;
  var $Zastavka;
  var $Cas;
  var $vyhledaneSpoje = null;
  var $vyhledaneSpoje1 = null;
  var $maxDobaSpoju;
  var $maxDobaPrestup;
  var $minDobaPrestup;
  var $omezeniNonPrefer = false;
  var $OdjezdyDepozit;
  var $move = false;

  function nactiPrunik($A, $B, $odTarif) {
    $nonPreferA = new Vector();
    $PreferA = new Vector();
    $nonPreferB = new Vector();
    $PreferB = new Vector();
    $poradiPrunik = new Vector();
    $poradiPrunikA = new Vector();
    $poradiPrunikB = new Vector();

    /*     echo "document.write('prunik :');";
      echo "document.write('<BR>');"; */
    if ($A->Linka != $B->Linka) {
      /*      echo "document.write('".$A->Linka->nazev." -> ".$B->Linka->nazev."');";
        echo "document.write('<BR>');"; */
      for ($i = 0; $i < $A->Zastavky->size(); $i++) {
        for ($ii = 0; $ii < $B->Zastavky->size(); $ii++) {
          if ($B->Zastavky->elementAt($ii) != $this->OdZastavky) {
            if ($A->Zastavky->elementAt($i)->id_zastavky == $B->Zastavky->elementAt($ii)->id_zastavky) {
              if ($this->Zastavky->elementAt($A->Zastavky->elementAt($i)->id_zastavky - 1)->prestup) {
                if ($odTarif < $A->Zastavky->elementAt($i)->tarif) {
                  $PreferA->addElement($A->Zastavky->elementAt($i));
                } else {
                  $PreferB->addElement($A->Zastavky->elementAt($i));
                }
              } else {
                if ($odTarif < $A->Zastavky->elementAt($i)->tarif) {
                  $nonPreferA->addElement($A->Zastavky->elementAt($i));
//                  echo "document.write('".$this->Zastavky->elementAt($A->Zastavky->elementAt($i)->id_zastavky - 1)->nazev."');";
//                  echo "document.write('<BR>');";
                } else {
                  $nonPreferB->addElement($A->Zastavky->elementAt($i));
//                  echo "document.write('".$this->Zastavky->elementAt($A->Zastavky->elementAt($i)->id_zastavky - 1)->nazev."');";
//                  echo "document.write('<BR>');";
                }
              }
            }
          }
        }
      }

      for ($i = ($PreferA->size() - 1); $i >= 0; $i--) {
        $poradiPrunikA->addElement($PreferA->elementAt($i));
      }

      if ($this->omezeniNonPrefer == true) {
        if ($nonPreferA->size() > 0) {
          $poradiPrunikA->addElement($nonPreferA->lastElement());
          $poradiPrunikA->addElement($nonPreferA->firstElement());
        }
      } else {
        for ($i = ($nonPreferA->size() - 1); $i >= 0; $i--) {
          $poradiPrunikA->addElement($nonPreferA->elementAt($i));
        }
      }

      for ($i = 0; $i < $PreferB->size(); $i++) {
        $poradiPrunikB->addElement($PreferB->elementAt($i));
      }

      if ($this->omezeniNonPrefer == true) {
        if ($nonPreferB->size() > 0) {
          $poradiPrunikB->addElement($nonPreferB->firstElement());
          $poradiPrunikB->addElement($nonPreferB->lastElement());
        }
      } else {
        for ($i = 0; $i < $nonPreferB->size(); $i++) {
          $poradiPrunikB->addElement($nonPreferB->elementAt($i));
        }
      }
      $poradiPrunik->addElement($poradiPrunikA);
      $poradiPrunik->addElement($poradiPrunikB);
    } else {
      $A->Zastavky->elementAt(0)->tarif = (1);
      $A->Zastavky->elementAt($A->Zastavky->size() - 1)->tarif = ($A->Zastavky->size());
      $poradiPrunikA->addElement($A->Zastavky->elementAt(0));
      $poradiPrunikB->addElement($A->Zastavky->elementAt($A->Zastavky->size() - 1));
      $poradiPrunik->addElement($poradiPrunikA);
      $poradiPrunik->addElement($poradiPrunikB);
    }
    return $poradiPrunik;
  }

  function nactiDetailSpojeOdjezdy($spojod) {
    $result = null;
    try {
      $TrasaA = LoadTrasa(false, 0, $spojod->Linka->id_linky);
      $res = new TSpojDetail();
      $res->ZeZastavky = $OdZastavky->id_zastavky;
      $res->ZeTarif = $TrasaA->getTarif($res->ZeZastavky);

      $res->DoZastavky = $TrasaA->Zastavky->lastElement()->id_zastavky;
      $res->DoTarif = -1;
      $res->Trasa = null;
      $res->Smer = 0;
      if ($res->ZeTarif != ($TrasaA->size()) - 1) {
        if ($result == null) {
          $result = new Vector();
        }
        $result->addElement($res);
      }

      $res = new TSpojDetail();
      $res->ZeZastavky = $OdZastavky->id_zastavky;
      $res->ZeTarif = $TrasaA->size() - 1 - $TrasaA->getTarif($res->ZeZastavky);

      $res->DoZastavky = $TrasaA->Zastavky . firstElement()->id_zastavky;
      $res->DoTarif = -1;
      $res->Trasa = null;
      $res->Smer = 1;
      if ($res->ZeTarif != ($TrasaA->size()) - 1) {
        if ($result == null) {
          $result = new Vector();
        }
        $result->addElement($res);
      }
    } catch (Exception $ex) {
      
    };
    return $result;
  }

  function nactiDetailSpoje($spojod, $pred, $po) {
    $result = null;
    $res = null;
    $lpred = (($pred == null) ? null : $pred->Linka);
    $lpo = (($po == null) ? null : $po->Linka);

    if ($res == null) {
      if (($pred == null) && ($po == null)) {

        try {
          if ($spojod->Trasa == null) {
            $spojod->Trasa = $spojod->Linka->trasa->LoadTrasa(false, 0, $spojod->Linka->id_linky);
          }
          $newTrasa = $spojod->Trasa;
          $vZeZastavky = $newTrasa->getTarifVector($this->OdZastavky->id_zastavky);
          $vDoZastavky = $newTrasa->getTarifVector($this->DoZastavky->id_zastavky);

          for ($i = 0; $i < $vZeZastavky->size(); $i++) {
            for ($ii = 0; $ii < $vDoZastavky->size(); $ii++) {
              $res = new TSpojDetail();
              $res->ZeZastavky = $vZeZastavky->elementAt($i)->id_zastavky;
              $res->DoZastavky = $vDoZastavky->elementAt($ii)->id_zastavky;

              $res->ZeTarif = $vZeZastavky->elementAt($i)->tarif;
              $res->DoTarif = $vDoZastavky->elementAt($ii)->tarif;

              if (($res->ZeTarif > -1) && ($res->DoTarif > -1)) {
                $res->Smer = ($res->ZeTarif <= $res->DoTarif) ? 0 : 1;
                if ($res->Smer == 1) {
                  
                }
                if ($result == null) {
                  $result = new Vector();
                }
                $result->addElement($res);
              }
            }
          }
        } catch (Exception $ex) {
          
        };
      }
      if (($pred == null) && ($po != null)) {
        $res = new TSpojDetail();
        $res->ZeZastavky = $this->OdZastavky->id_zastavky;
        try {
          if ($spojod->Trasa == null) {
            $spojod->Trasa = $spojod->Linka->trasa->LoadTrasa(false, 0, $spojod->Linka->id_linky);
          }
          $newTrasaA = $spojod->Trasa;
          if ($po->Trasa == null) {
            $po->Trasa = $po->Linka->trasa->LoadTrasa(false, 0, $po->Linka->id_linky);
          }
          $newTrasaB = $po->Trasa;
          $res->ZeTarif = $newTrasaA->getTarif($res->ZeZastavky);
          if ($res->ZeTarif > -1) {
            if ($spojod->prunik == null) {
              $spojod->prunik = $this->nactiPrunik($newTrasaA, $newTrasaB, $res->ZeTarif);
            }
          }
          $prunik = $spojod->prunik;

          if ($prunik != null) {
            for ($pr = 0; $pr < $prunik->size(); $pr++) {
              if ($prunik->elementAt($pr)->size() > 0) {
                for ($pr1 = 0; $pr1 < $prunik->elementAt($pr)->size(); $pr1++) {
                  $vZeZastavky = $newTrasaA->getTarifVector($this->OdZastavky->id_zastavky);
                  $vDoZastavky = $newTrasaA->getTarifVector($prunik->elementAt($pr)->elementAt($pr1)->id_zastavky);
                  for ($i = 0; $i < $vZeZastavky->size(); $i++) {
                    for ($ii = 0; $ii < $vDoZastavky->size(); $ii++) {
                      $res = new TSpojDetail();
                      $res->ZeZastavky = $vZeZastavky->elementAt($i)->id_zastavky;
                      $res->DoZastavky = $vDoZastavky->elementAt($ii)->id_zastavky;
                      $res->ZeTarif = $vZeZastavky->elementAt($i)->tarif;
                      $res->DoTarif = $vDoZastavky->elementAt($ii)->tarif;

                      $res->Smer = ($res->ZeTarif <= $res->DoTarif) ? 0 : 1;

                      if ($result == null) {
                        $result = new Vector();
                      }
                      $result->addElement($res);
                    }
                  }
                }
              }
            }
          }
        } catch (Exception $ex) {
          
        };
      }
      if (($pred != null) && ($po == null)) {

        try {
          if ($spojod->Trasa == null) {
            $spojod->Trasa = $spojod->Linka->trasa->LoadTrasa(false, 0, $spojod->Linka->id_linky);
          }
          $newTrasa = $spojod->Trasa;
          $vZeZastavky = $newTrasa->getTarifVector($pred->SpojDetail->DoZastavky);
          $vDoZastavky = $newTrasa->getTarifVector($this->DoZastavky->id_zastavky);

          for ($i = 0; $i < $vZeZastavky->size(); $i++) {
            for ($ii = 0; $ii < $vDoZastavky->size(); $ii++) {
              $res = new TSpojDetail();
              $res->ZeZastavky = $vZeZastavky->elementAt($i)->id_zastavky;
              $res->DoZastavky = $vDoZastavky->elementAt($ii)->id_zastavky;

              $res->ZeTarif = $vZeZastavky->elementAt($i)->tarif;
              $res->DoTarif = $vDoZastavky->elementAt($ii)->tarif;


              $res->Smer = ($res->ZeTarif <= $res->DoTarif) ? 0 : 1;
              if ($result == null) {
                $result = new Vector();
              }
              $result->addElement($res);
            }
          }
        } catch (Exception $ex) {
          
        };
      }
      if (($pred != null) && ($po != null)) {
        $res = new TSpojDetail();
        try {
          if ($spojod->Trasa == null) {
            $spojod->Trasa = $spojod->Linka->trasa->LoadTrasa(false, 0, $spojod->Linka->id_linky);
          }
          $newTrasaA = $spojod->Trasa;
          if ($po->Trasa == null) {
            $po->Trasa = $po->Linka->trasa->LoadTrasa(false, 0, $po->Linka->id_linky);
          }
          $newTrasaB = $po->Trasa;
          $res->ZeZastavky = $pred->SpojDetail->DoZastavky;
          $res->ZeTarif = $newTrasaA->getTarif($res->ZeZastavky);
          if ($spojod->prunik == null) {
            $spojod->prunik = $this->nactiPrunik($newTrasaA, $newTrasaB, $res->ZeTarif);
          }
          $prunik = $spojod->prunik;
          $result = new Vector();

          for ($pr = 0; $pr < $prunik->size(); $pr++) {
            if ($prunik->elementAt($pr)->size() > 0) {
              for ($pr1 = 0; $pr1 < $prunik->elementAt($pr)->size(); $pr1++) {
                $vZeZastavky = $newTrasaA->getTarifVector($pred->SpojDetail->DoZastavky);
                $vDoZastavky = $newTrasaA->getTarifVector($prunik->elementAt($pr)->elementAt($pr1)->id_zastavky);
                for ($i = 0; $i < $vZeZastavky->size(); $i++) {
                  for ($ii = 0; $ii < $vDoZastavky->size(); $ii++) {
                    $res = new TSpojDetail();
                    $res->ZeZastavky = $vZeZastavky->elementAt($i)->id_zastavky;
                    $res->DoZastavky = $vDoZastavky->elementAt($ii)->id_zastavky;
                    $res->ZeTarif = $vZeZastavky->elementAt($i)->tarif;
                    $res->DoTarif = $vDoZastavky->elementAt($ii)->tarif;

                    $res->Smer = ($res->ZeTarif <= $res->DoTarif) ? 0 : 1;
                    if ($result == null) {
                      $result = new Vector();
                    }
                    $result->addElement($res);
                  }
                }
              }
            }
          }
        } catch (Exception $ex) {
          
        };
      }
    }
    if ($result == null) {
      $result = new Vector();
    }
    return $result;
  }

  function getOdjezdDepozit($d, $m, $y, $idlinky, $smer) {
    $res = null;
    for ($i = 0; $i < $this->OdjezdyDepozit->size(); $i++) {
      if (($this->OdjezdyDepozit->elementAt($i)->d == $d) &&
              ($this->OdjezdyDepozit->elementAt($i)->m == $m) &&
              ($this->OdjezdyDepozit->elementAt($i)->y == $y) &&
              ($this->OdjezdyDepozit->elementAt($i)->idlinka == $idlinky) &&
              ($this->OdjezdyDepozit->elementAt($i)->smer == $smer)) {
        $res = $this->OdjezdyDepozit->elementAt($i)->Odjezdy;
        break;
      }
    }
    return $res;
  }

  function doplnCasy($castSpoj, $pred, $cas) {
    $res = true;
    $Spoj = $castSpoj->SpojDetail;
    $plus = 0;
    if ($pred != null) {
      if ($pred->SpojDetail->OdjezdPrijezd != null) {
        $H = $pred->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H;
        $M = $pred->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
        $plus = $pred->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden;
      } else {
        $H = $this->vychoziCas->H;
        $M = $this->vychoziCas->M;
        $plus = 0;
      }
    } else {
      if ($cas != null) {
        $H = $cas->H;
        $M = $cas->M;
        $plus = $cas->plusden;
      } else {
        $H = $this->vychoziCas->H;
        $M = $this->vychoziCas->M;
        $plus = 0;
      }
    }

    if (!$castSpoj->Linka->special) {
      $pomTrasa = null;
      $DD = 0;
      $MM = 0;
      $YYYY = 0;
      if ($castSpoj->Linka->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD)) {
        try {
          if ($Spoj->Trasa == null) {
            $Spoj->Trasa = $castSpoj->Linka->trasa->LoadTrasa(false, 0, $castSpoj->Linka);
          }
          $pomTrasa = $Spoj->Trasa;
          $d = get_Date($this->vychoziCas->DD, $this->vychoziCas->MM, $this->vychoziCas->YYYY, $plus);
          $DD = $d->Day;
          $MM = $d->Month;
          $YYYY = $d->Year;
          if ($Spoj->Odjezdy == null) {
            $pomOdjezdy = $this->getOdjezdDepozit($d->Day, $d->Month, $d->Year, $castSpoj->Linka->id_linky, $Spoj->Smer);
            if ($pomOdjezdy == null) {
              $Spoj->Odjezdy = $castSpoj->Linka->odjezdy->LoadOdjezdy($Spoj->Smer, $castSpoj->Linka, $pomTrasa, $Spoj->ZeTarif, $Spoj->DoTarif, getTypDneofDate($d->Day, $d->Month, $d->Year), $this->Poznamky);
              $this->OdjezdyDepozit->addElement(new TDepozitOdjezd($d->Day, $d->Month, $d->Year, $castSpoj->Linka->id_linky, $Spoj->Smer, $Spoj->Odjezdy));
              /*              echo "document.write('nahravam odjezdy linky ".$castSpoj->Linka->nazev."');";
                echo "document.write('<BR>');"; */
            } else {
              $Spoj->Odjezdy = $pomOdjezdy;
              /*              echo "document.write('uz mam odjezdy linky ".$castSpoj->Linka->nazev."');";
                echo "document.write('<BR>');"; */
            }
          }
        } catch (Exception $ex) {
          
        };
      }
      if ($Spoj->Odjezdy != null) {
        $Odjezd = $Spoj->Odjezdy->getNejblizsiOdjezd($H, $M, $Spoj->ZeTarif, $Spoj->DoTarif);
      } else {
        $Odjezd = null;
      }

      $nplus = $plus;
      if ($Odjezd == null) {
        if ((($H * 60 + $M + $this->maxDobaSpoju) / 60 >= 24) && ($pred == null)) {
          $nplus++;
          if ($castSpoj->Linka->isValid($vychoziCas->YYYY, $vychoziCas->MM, $vychoziCas->DD + $nplus)) {
            try {
              $d = get_Date($vychoziCas->DD, $vychoziCas->MM, $vychoziCas->YYYY, $nplus);
              $this->Odjezdy = $castSpoj->Linka->LoadOdjezdy($Spoj->Smer, $castSpoj->Linka, $pomTrasa, $Spoj->ZeTarif, $Spoj->DoTarif, getTypDneofDate($d->Day, $d->Month, $d->Year), $this->Poznamky);
              $DD = $d->Day;
              $MM = $d->Month;
              $YYYY = $d->Year;
            } catch (Exception $ex) {
              
            };
            $Odjezd = $Odjezdy->getNejblizsiOdjezd1(0, 0, $Spoj->ZeTarif, $Spoj->DoTarif);
          }
        }
      }

      if ($Odjezd != null) {
        $OM = (($Odjezd->M + $Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)) % 60);
        $OH = (($Odjezd->H + ($Odjezd->M + $Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)) / 60) % 24);
        /*        echo "document.write('linka = ".$castSpoj->Linka->nazev." - ".$Odjezd->H.":".$Odjezd->M."  ".$Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)." ( ".$OH.":".$OM." )');";
          echo "document.write('<BR>');"; */

        $CasOd = (($plus * 1440) + ($H * 60) + $M);
//        $CasDo = (($nplus * 1440) + ($Odjezd->H * 60) + $Odjezd->M);
        $CasDo = (($nplus * 1440) + ($OH * 60) + $OM);

        /*        echo "document.write('".$CasDo." - ".$CasOd."  ".($this->maxDobaSpoju + $this->maxDobaPrestup)."');";
          echo "document.write('<BR>');"; */

        if ($CasDo - $CasOd <= $this->maxDobaSpoju + $this->maxDobaPrestup) {
          $res = true;
          $noc = ((($H >= 22) || ($H <= 3) || ($pred == null)) ? 0 : $this->minDobaPrestup);

          if (($CasDo - $CasOd <= (($pred == null) ? $this->maxDobaSpoju : $this->maxDobaPrestup)) && ($CasDo - $CasOd >= $noc)) {
            /*            echo "document.write('".$Odjezd->staviA($Spoj->ZeTarif)." (".$Spoj->ZeTarif.") ".$Odjezd->staviB($Spoj->DoTarif)." (".$Spoj->DoTarif.")');";
              echo "document.write('<BR>');"; */
            if ($Spoj->DoTarif != -1) {
              if (($Odjezd->staviA($Spoj->ZeTarif)) &&
                      ($Odjezd->staviB($Spoj->DoTarif))) {
                $OP = new TOdjezdPrijezd();
                $OP->Odjezd = new TCasInter($OH, $OM, $nplus);
                $OP->Odjezd->DD = $DD;
                $OP->Odjezd->MM = $MM;
                $OP->Odjezd->YYYY = $YYYY;

                $pomH = $OH;
                $pomM = $OM + $Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->DoTarif)/* $Odjezd->dobapocatekDO */ - $Odjezd->getDobaPocatekDO($Spoj->Smer, $Spoj->ZeTarif)/* $Odjezd->dobapocatekOD */;
                $HMint = ($nplus * 1440) + ($pomH * 60) + $pomM;
                $nplus = (int) ($HMint / 1440);
                $HMint = ($HMint % 1440);
                $pomH = (int) ($HMint / 60);
                $pomM = ($HMint % 60);
                $OP->Prijezd = new TCasInter($pomH, $pomM, $nplus);
                /*                echo "document.write('linka = ".$castSpoj->Linka->nazev." ".$OH.":".$OM." - ".$pomH.":".$pomM." ( ".$Odjezd->H.":".$Odjezd->M." + ".$Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)."');";
                  echo "document.write('<BR>');"; */
                $castSpoj->SpojDetail->OdjezdPrijezd = new Vector();
                $castSpoj->SpojDetail->OdjezdPrijezd->addElement($OP);
              }
            } else {
              $OP = new TOdjezdPrijezd();
              $OM = (($Odjezd->M + $Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)) % 60);
              $OH = (($Odjezd->H + ($Odjezd->M + $Odjezd->getDobaPocatekOD($Spoj->Smer, $Spoj->ZeTarif)) / 60) % 24);
              $OP->Odjezd = new TCasInter(/* $Odjezd->H */$OH, /* $Odjezd->M */$OM, $nplus);
              $OP->Odjezd->DD = $DD;
              $OP->Odjezd->MM = $MM;
              $OP->Odjezd->YYYY = $YYYY;
              $castSpoj->SpojDetail->OdjezdPrijezd = new Vector();
              $castSpoj->SpojDetail->OdjezdPrijezd->addElement($OP);
            }
          } else {
            $res = false;
          }
        }
      }
    } else {
      $castSpoj->SpojDetail->OdjezdPrijezd = new Vector();
      $OP = new TOdjezdPrijezd();
      $OP->Odjezd = new TCasInter($H, $M, $plus);

      $pomH = $H;
      $pomM = ($M + $castSpoj->Linka->Cas);
      $HMint = (($plus * 1440) + ($pomH * 60) + $pomM);
      $plus = ($HMint / 1440);
      $HMint = ($HMint % 1440);
      $H = ($HMint / 60);
      $M = ($HMint % 60);
      $OP->Prijezd = new TCasInter($H, $M, $plus);
      $castSpoj->SpojDetail->OdjezdPrijezd->addElement($OP);
    }
    return $res;
  }

  function zapisSpoj($Spoj, $pred, $po, $cas) {
    $res = 0;
    if (!$Spoj->Linka->special) {
      /*      if ($this->Zastavky->elementAt($Spoj->SpojDetail->ZeZastavky)->prestup) {
        $Spoj->vaha += 1;
        }
        if ($this->Zastavky->elementAt($Spoj->SpojDetail->DoZastavky)->prestup) {
        $Spoj->vaha += 1;
        } */
      /*      echo "document.write('A res = ".$res."');";
        echo "document.write('<BR>');"; */
      if (($pred != null) || ($po != null)) {
        if ($Spoj->SpojDetail->ZeZastavky == $Spoj->SpojDetail->DoZastavky) {
          $res = -1;
        }
      }
      /*      echo "document.write('B res = ".$res."');";
        echo "document.write('<BR>');"; */
      if ($this->DoZastavky != null) {
        if (($Spoj->SpojDetail->DoZastavky == $this->DoZastavky->id_zastavky) && ($po != null)) {
          $res = -1;
        }
      }
    } else {
      if (($Spoj->SpojDetail->ZeZastavky == $Spoj->SpojDetail->DoZastavky) &&
              ($Spoj->Linka->A != $Spoj->Linka->B)) {
        $res = -1;
      }
    }
    if (($cas != null) && ($pred == null)) {
      if (($cas->plusden * 1439 + $cas->H * 60 + $cas->M) - $this->vychoziCasO > $this->maxDobaSpoju) {
        $res = -1;
      }
    }
    if ($res == 0) {
      if ($Spoj->SpojDetail->OdjezdPrijezd == null) {
        try {
          if ($this->doplnCasy($Spoj, $pred, $cas)) {
            $res = 0;
          } else {
            $res = -2;
          }
          /*            if ($pred != null) {
            $nSpoj = $pred;
            echo "document.write('".$nSpoj->Linka->nazev."  ');";
            if ($nSpoj->SpojDetail->OdjezdPrijezd != null) {
            echo "document.write('".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden.")  - ".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden.") ".$nSpoj->SpojDetail->DoZastavky." ".$nSpoj->SpojDetail->Smer."     ->     ');";
            }
            //            echo "document.write('<BR>');";
            }
            $nSpoj = $Spoj;
            echo "document.write('".$nSpoj->Linka->nazev."  ');";
            if ($nSpoj->SpojDetail->OdjezdPrijezd != null) {
            echo "document.write('".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden.")  - ".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden.") ".$nSpoj->SpojDetail->DoZastavky." ".$nSpoj->SpojDetail->Smer."     ->     ');";
            }
            //            echo "document.write('<BR>');";
            if ($po != null) {
            $nSpoj = $po;
            if ($nSpoj->SpojDetail != null) {
            echo "document.write('".$nSpoj->Linka->nazev."  ');";
            if ($nSpoj->SpojDetail->OdjezdPrijezd != null) {
            echo "document.write('".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden.")  - ".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden.") ".$nSpoj->SpojDetail->DoZastavky." ".$nSpoj->SpojDetail->Smer."     ->     ');";
            }
            }
            //            echo "document.write('<BR>');";
            }
            echo "document.write('<BR>');"; */
        } catch (Exception $ex) {
          
        };
        if ($res == 0) {
          if (($Spoj->SpojDetail->OdjezdPrijezd == null) || ($Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd == null)) {
            $res = -1;
          } else {
            if ($pred == null) {
              $Odjezd = $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M;
              if ($Odjezd - $this->vychoziCasO > $this->maxDobaSpoju) {
                $res = -1;
              }
            }
          }
          /*          echo "document.write('".$Spoj->Linka->nazev." - ".$this->Zastavky->elementAt($Spoj->SpojDetail->ZeZastavky - 1)->nazev." - ".$this->Zastavky->elementAt($Spoj->SpojDetail->DoZastavky - 1) ->nazev." res = ".$res."');";
            echo "document.write('<BR>');"; */
        }
      }
    }
    /*    echo "document.write('vysledek >');";
      echo "document.write('".$Spoj->Linka->nazev." res = ".$res."');";
      echo "document.write('<BR>');"; */
    return $res;
  }

  function porovnejSpoje1($Spojeni, $res) {
    $result = -1;

    for ($i = 0; $i < $res->size(); $i++) {
      $CasDojezdu1 = ($res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);
      $CasDojezdu2 = ($Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);

      if ($CasDojezdu1 == $CasDojezdu2) {
        $CasOdjezdu1 = ($res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
        $CasOdjezdu2 = ($Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
        if ($CasOdjezdu2 > $CasOdjezdu1) {
          $res->setElementAt($Spojeni, $i);
        }
        $result = $i;
        break;
      }
    }
    return $result;
  }

  function porovnejSpoje($Spojeni, $res) {
    $result = -1;

    for ($i = 0; $i < $res->size(); $i++) {
      $CasOdjezdu1 = ($res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
      $CasOdjezdu2 = ($Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);

      if ($CasOdjezdu1 == $CasOdjezdu2) {
        $CasDojezdu1 = ($res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $res->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);
        $CasDojezdu2 = ($Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);
        if ($CasDojezdu2 < $CasDojezdu1) {
          $res->setElementAt($Spojeni, $i);
        }
        $result = $i;
        break;
      }
    }
    if ($result == -1) {
      $result = $this->porovnejSpoje1($Spojeni, $res);
    } else {
      $CasOdjezdu1 = ($Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
      $CasDojezdu1 = ($Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);
      $vaha1 = 0;
      $size1 = $Spojeni->size();
      for ($i = 0; $i < $Spojeni->size(); $i++) {
        $vaha1 += $Spojeni->elementAt($i)->vaha;
      }
      $Spojeni = $res->elementAt($result);
      $CasOdjezdu2 = ($Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spojeni->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
      $CasDojezdu2 = ($Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $Spojeni->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M);
      $vaha2 = 0;
      $size2 = $Spojeni->size();
      for ($i = 0; $i < $Spojeni->size(); $i++) {
        $vaha2 += $Spojeni->elementAt($i)->vaha;
      }
      if (($CasOdjezdu1 == $CasOdjezdu2) && ($CasDojezdu2 == $CasDojezdu1)) {
        if ($vaha1 > $vaha2) {
          $res->removeElementAt($result);
          $result = -1;
        }
        if ($size1 < $size2) {
          $res->removeElementAt($result);
          $result = -1;
        }
      } else {
        $res->removeElementAt($result);
        $newresult = $this->porovnejSpoje1($Spojeni, $res);
        if ($newresult == -1) {
          $res->addElement($Spojeni);
        }
      }
    }
    return $result;
  }

  function existOdjezd($Spoj, $res, $x) {
    $result = false;
    if ($Spoj->SpojDetail->OdjezdPrijezd != null) {
      for ($i = 0; $i < $res->size(); $i++) {
        $CasOdjezdu1 = ($res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);
        $CasOdjezdu2 = ($Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1439 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M);

        if ($CasOdjezdu1 == $CasOdjezdu2) {
          if ($res->elementAt($i)->size() <= $x) {
            $result = true;
            break;
          } else {
            $result = false;
          }
        }
      }
    } else {
      $result = true;
    }
    return $result;
  }

  function existOdjezd1($Spoj, $res, $x) {
    $result = false;
    for ($i = 0; $i < $res->size(); $i++) {
      if ($Spoj->Linka == $res->elementAt($i)->firstElement()->Linka) {
        if ($res->elementAt($i)->size() == $x) {
          $result = true;
          break;
        } else {
          $result = false;
        }
      }
    }
    return $result;
  }

  function addNullLinky($res, $linka, $smer, $id_zastavky) {
    $NL = new TNullLinky();
    $NL->Linka = $linka;
    $NL->Smer = $smer;
    $NL->ZaZastavky = $id_zastavky;
    $res->addElement($NL);
  }

  function existNullLinky($res, $linka, $smer, $id_zastavky) {
    $result = false;
    for ($i = 0; $i < $res->size(); $i++) {
      if (($res->elementAt($i)->Linka == $linka) && ($res->elementAt($i)->Smer == $smer) && ($res->elementAt($i)->ZaZastavky == $id_zastavky)) {
        $result = true;
        break;
      }
    }
    return $result;
  }

  function casPlus($cas, $delta) {
    $newCas = new TCasInter(null, null, null);
    $Casint = $cas->plusden * 1440 + $cas->H * 60 + $cas->M + $delta;
    $plus = (int) ($Casint / 1440);
    $Casint = $Casint % 1440;
    $H = (int) ($Casint / 60);
    $M = $Casint % 60;
    $newCas->H = $H;
    $newCas->M = $M;
    $newCas->plusden = $plus;
    return $newCas;
  }

  function moznyPrestup($linka, $zastavka) {
    $res = false;
    for ($i = 0; $i < $zastavka->linky->size(); $i++) {
      if ($zastavka->linky->elementAt($i) == $linka) {
        $res = true;
        break;
      }
    }
    return $res;
  }

  function porovnejCasy($a, $b) {
    $res = false;
    if (($a == null) || ($b == null)) {
      $res = false;
    } else {
      if ($a->plusden * 1439 + $a->H * 60 + $a->M == $b->plusden * 1439 + $b->H * 60 + $b->M) {
        $res = true;
      } else {
        $res = false;
      }
    }
    return $res;
  }

  function moznostiSpojeni() {
    $res = new Vector();

    for ($iod = 0; $iod < $this->OdZastavky->linky->size(); $iod++) {
      for ($ido = 0; $ido < $this->DoZastavky->linky->size(); $ido++) {
        //0 prestupu
        if ($this->OdZastavky->linky->elementAt($iod) == $this->DoZastavky->linky->elementAt($ido)) {
          if (($this->OdZastavky->linky->elementAt($iod)->special == false) && ($this->DoZastavky->linky->elementAt($ido)->special == false) && ($this->OdZastavky->linky->elementAt($iod)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD))) {

            $Spoj = new TCastSpoj();
            $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
            $castSpoje = $this->nactiDetailSpoje($Spoj, null, null);
            for ($pr = 0; $pr < $castSpoje->size(); $pr++) {
              $pokracuj = true;
              $Spoj = new TCastSpoj();
              $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
              $Spoj->SpojDetail = $castSpoje->elementAt($pr);

//echo "document.write('".$Spoj->Linka->nazev."');";

              if ($this->zapisSpoj($Spoj, null, null, null) != 0) {
                $pokracuj = false;
              }
              if ($pokracuj) {
                $addTrasa = new Vector();
                $addTrasa->addElement($Spoj);
                if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                  $res->addElement($addTrasa);
                }
              }

              $konec = false;
              while ($konec == false) {
                $pokracuj = true;
                if (!$Spoj->Linka->special) {
                  if ($Spoj->SpojDetail->OdjezdPrijezd != null) {
                    if ($Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd != null) {
                      $newCasOdjezd = $this->casPlus($Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd, 1);
                      $Spoj = $Spoj->clon();
                      if ($this->zapisSpoj($Spoj, null, null, $newCasOdjezd) != 0) {
                        $pokracuj = false;
                        $konec = true;
                      }
                      if ($pokracuj) {
                        $addTrasa = new Vector();
                        $addTrasa->addElement($Spoj);
                        if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                          $res->addElement($addTrasa);
                        }
                      }
                    } else {
                      $konec = true;
                    }
                  } else {
                    $konec = true;
                  }
                } else {
                  $konec = true;
                }
              }
            }
          }
          //-- 0 prestupu
        }
      }
    }
    for ($iod = 0; $iod < $this->OdZastavky->linky->size(); $iod++) {
      for ($ido = 0; $ido < $this->DoZastavky->linky->size(); $ido++) {
        $depozitOdjezd = null;
        $depozitTrasa = null;
        $depozitSmer = -1;
        $Linka2Od = $this->OdZastavky->linky->elementAt($iod);
        $Linka2Do = $this->DoZastavky->linky->elementAt($ido);
        {
          for ($i2do = 0; $i2do < $Linka2Do->linky->size(); $i2do++) {
            if (($Linka2Od == $Linka2Do->linky->elementAt($i2do)) && ($this->OdZastavky->linky->elementAt($iod)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD)) && ($this->DoZastavky->linky->elementAt($ido)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD))) {
              //1 prestup
              $Spoj = new TCastSpoj();
              $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
              $Spoj1 = new TCastSpoj();
              $Spoj1->Linka = $this->DoZastavky->linky->elementAt($ido);
//              echo "document.write('".$Spoj->Linka->nazev." -> ".$Spoj1->Linka->nazev."');";
//              echo "document.write('<BR>');";
              $castSpoje = $this->nactiDetailSpoje($Spoj, null, $Spoj1);
              for ($pr = 0; $pr < $castSpoje->size(); $pr++) {
                $pokracuj = true;
                $Spoj = new TCastSpoj();
                $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
                $Spoj->SpojDetail = $castSpoje->elementAt($pr);
                if (($depozitTrasa != null) && ($depozitOdjezd != null) && ($castSpoje->elementAt($pr)->Smer == $depozitSmer)) {
                  $castSpoje->elementAt($pr)->Trasa = $depozitTrasa;
                  $castSpoje->elementAt($pr)->Odjezdy = $depozitOdjezd;
                }
                if ($this->zapisSpoj($Spoj, null, $Spoj1, null) != 0) {
                  $pokracuj = false;
                } else {
                  if (($castSpoje->elementAt($pr)->Odjezdy != null) && ($castSpoje->elementAt($pr)->Smer != $depozitSmer)) {
                    $depozitTrasa = $castSpoje->elementAt($pr)->Trasa;
                    $depozitOdjezd = $castSpoje->elementAt($pr)->Odjezdy;
                    $depozitSmer = $castSpoje->elementAt($pr)->Smer;
                  }
                }
                if ($this->existOdjezd($Spoj, $res, 1)) {
                  $pokracuj = false;
                }
                if ($pokracuj) {
                  $castSpoje1 = $this->nactiDetailSpoje($Spoj1, $Spoj, null);
                  for ($pr1 = 0; $pr1 < $castSpoje1->size(); $pr1++) {
                    $Spoj1 = new TCastSpoj();
                    $Spoj1->Linka = $this->DoZastavky->linky->elementAt($ido);
                    $Spoj1->SpojDetail = $castSpoje1->elementAt($pr1);
                    /*            echo "document.write('".$Spoj->Linka->nazev." : ".$Spoj->SpojDetail->ZeZastavky." - ".$Spoj->SpojDetail->DoZastavky." (".$Spoj->SpojDetail->Smer.") -> ".$Spoj1->Linka->nazev." : ".$Spoj1->SpojDetail->ZeZastavky." - ".$Spoj1->SpojDetail->DoZastavky."');";
                      echo "document.write('<BR>');"; */
                    if ($this->zapisSpoj($Spoj1, $Spoj, null, null) != 0) {
                      $pokracuj = false;
                    }
                    if ($pokracuj) {
                      $addTrasa = new Vector();
                      $addTrasa->addElement($Spoj);
                      $addTrasa->addElement($Spoj1);
                      /*            for($ii = 0; $ii < $addTrasa->size(); $ii++) {
                        $nSpoj = $addTrasa->elementAt($ii);
                        echo "document.write('".$nSpoj->Linka->nazev."');";
                        if ($nSpoj->SpojDetail->OdjezdPrijezd != null) {
                        echo "document.write('".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden.")  - ".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden.") ".$nSpoj->SpojDetail->DoZastavky." ".$nSpoj->SpojDetail->Smer."');";
                        }
                        echo "document.write(') , ');";
                        }
                        echo "document.write('<BR>');"; */
                      if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                        $res->addElement($addTrasa);
                      }
                    }
                    $konec = false;
                    $aSpoj = $Spoj;
                    $aSpoj1 = $Spoj1;
                    while ($konec == false) {
//                      echo "document.write('iteruji');";
//                      echo "document.write('<BR>');";
                      $pokracuj = true;
                      $delta = 1;
                      if (!$aSpoj->Linka->special) {
                        if ($aSpoj->SpojDetail->OdjezdPrijezd != null) {
                          if ($aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd != null) {
                            if ($aSpoj1->SpojDetail->OdjezdPrijezd == null) {
                              $delta = $this->maxDobaPrestup;
                            } else {
                              $delta = 1;
                            }
                            $newCasOdjezd = $this->casPlus($aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd, $delta);
                            $aSpoj = $Spoj->clon();
                            $aSpoj1 = $Spoj1->clon();
                            /*                      echo "document.write('zapis');";
                              echo "document.write('<BR>');"; */
                            if ($this->zapisSpoj($aSpoj, null, $aSpoj1, $newCasOdjezd) != 0) {
                              $pokracuj = false;
                              $konec = true;
                              /*                      echo "document.write('a');";
                                echo "document.write('<BR>');"; */
                            }
                            /*                      echo "document.write('po zapsani');";
                              echo "document.write('<BR>');"; */
                            if ($this->existOdjezd($aSpoj, $res, 1)) {
                              $pokracuj = false;
                              /*                      echo "document.write('b');";
                                echo "document.write('<BR>');"; */
                            }
                            if ($pokracuj) {
//                              echo "document.write('".$aSpoj->SpojDetail." ".$aSpoj1->SpojDetail."');";
//                              echo "document.write('<BR>');";
                              $z = $this->zapisSpoj($aSpoj1, $aSpoj, null, null);
                              if ($z == -1) {
                                $pokracuj = false;
                                /*                      echo "document.write('c');";
                                  echo "document.write('<BR>');"; */
                              }
                              if ($z == -2) {
                                $pokracuj = false;
                                $konec = true;
                                /*                      echo "document.write('d');";
                                  echo "document.write('<BR>');"; */
                              }
                              if ($pokracuj) {
                                $addTrasa = new Vector();
                                $addTrasa->addElement($aSpoj);
                                $addTrasa->addElement($aSpoj1);
                                /*            for($ii = 0; $ii < $addTrasa->size(); $ii++) {
                                  $nSpoj = $addTrasa->elementAt($ii);
                                  echo "document.write('".$nSpoj->Linka->nazev." (');";
                                  if ($nSpoj->SpojDetail->OdjezdPrijezd != null) {
                                  echo "document.write('".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden.")  - ".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." (".$nSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden.") ".$nSpoj->SpojDetail->DoZastavky." ".$nSpoj->SpojDetail->Smer."');";
                                  }
                                  echo "document.write(') , ');";
                                  }
                                  echo "document.write('<BR>');"; */
                                if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                                  $res->addElement($addTrasa);
                                }
                              }
                            }
                          } else {
                            $konec = true;
                          }
                        } else {
                          $konec = true;
                        }
                      } else {
                        $konec = true;
                      }
                    }
                  }
                }
              }
            }
            //-- 1 prestup
          }
        }
      }
    }
    $this->vyhledaneSpoje = $res;
    $this->sortSpojeni(false, true);
    $max = 0;
    for ($i = 0; $i < $res->size() - 1; $i++) {
      $Spoj1 = $res->elementAt($i)->firstElement()->SpojDetail->OdjezdPrijezd->elementAt(0);
      $Spoj2 = $res->elementAt($i + 1)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0);
      $prijezd = $Spoj2->Prijezd->plusden * 1439 + $Spoj2->Prijezd->H * 60 + $Spoj2->Prijezd->M;
      $odjezd = $Spoj1->Odjezd->plusden * 1439 + $Spoj1->Odjezd->H * 60 + $Spoj1->Odjezd->M;
      if ($prijezd - $odjezd > $max) {
        $max = $prijezd - $odjezd;
      }
    }
    if ($max == 0) {
      $max = $this->maxDobaSpoju;
    }


    $nullLinky = new Vector();
    for ($iod = 0; $iod < $this->OdZastavky->linky->size(); $iod++) {
      $depozitOdjezd = new Vector();
      $depozitOdjezd->addElement(null);
      $depozitOdjezd->addElement(null);
      $depozitTrasa = new Vector();
      $depozitTrasa->addElement(null);
      $depozitTrasa->addElement(null);
      for ($ido = 0; $ido < $this->DoZastavky->linky->size(); $ido++) {
        $Linka2Od = $this->OdZastavky->linky->elementAt($iod);
        $Linka2Do = $this->DoZastavky->linky->elementAt($ido);

        if (($Linka2Od->linky != null) && ($Linka2Do != null)) {
          for ($i3od = 0; $i3od < $Linka2Od->linky->size(); $i3od++) {
            for ($i3do = 0; $i3do < $Linka2Do->linky->size(); $i3do++) {
              if (($Linka2Od->linky->elementAt($i3od)) == ($Linka2Do->linky->elementAt($i3do))) {
                if ((!$this->moznyPrestup($Linka2Od->linky->elementAt($i3od), $this->DoZastavky)) && ($this->OdZastavky->linky->elementAt($iod)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD)) && ($Linka2Od->linky->elementAt($i3od)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD)) && ($this->DoZastavky->linky->elementAt($ido)->isValid($this->vychoziCas->YYYY, $this->vychoziCas->MM, $this->vychoziCas->DD))) {
                  //2 prestupy
                  $Spoj = new TCastSpoj();
                  $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
                  if (!$this->existOdjezd1($Spoj, $res, 1)) {

                    $Spoj1 = new TCastSpoj();
                    $Spoj1->Linka = $Linka2Od->linky->elementAt($i3od);
                    $Spoj2 = new TCastSpoj();
                    $Spoj2->Linka = $this->DoZastavky->linky->elementAt($ido);
                    $castSpoje = $this->nactiDetailSpoje($Spoj, null, $Spoj1);
                    if ($castSpoje != null) {
                      for ($pr = 0; $pr < $castSpoje->size(); $pr++) {
                        $pokracuj = true;
                        $savePrunik = $Spoj->prunik;
                        $Spoj = new TCastSpoj();
                        $Spoj->prunik = $savePrunik;
                        $Spoj->Linka = $this->OdZastavky->linky->elementAt($iod);
                        $Spoj->SpojDetail = $castSpoje->elementAt($pr);
                        if (!$this->existNullLinky($nullLinky, $Spoj->Linka, $Spoj->SpojDetail->Smer, $Spoj->SpojDetail->ZeZastavky)) {
                          if (($depozitTrasa->elementAt($castSpoje->elementAt($pr)->Smer) != null) && ($depozitOdjezd->elementAt($castSpoje->elementAt($pr)->Smer) != null)) {
                            $castSpoje->elementAt($pr)->Trasa = $depozitTrasa->elementAt($castSpoje->elementAt($pr)->Smer);
                            $castSpoje->elementAt($pr)->Odjezdy = $depozitOdjezd->elementAt($castSpoje->elementAt($pr)->Smer);
                          }
                          if ($this->zapisSpoj($Spoj, null, $Spoj1, null) != 0) {
                            $pokracuj = false;
                          } else {
                            if (($castSpoje->elementAt($pr)->Odjezdy != null) && ($depozitOdjezd->elementAt($castSpoje->elementAt($pr)->Smer) == null)) {
                              $depozitTrasa->setElementAt($castSpoje->elementAt($pr)->Trasa, $castSpoje->elementAt($pr)->Smer);
                              $depozitOdjezd->setElementAt($castSpoje->elementAt($pr)->Odjezdy, $castSpoje->elementAt($pr)->Smer);
                            }
                          }
                          if ($this->existOdjezd($Spoj, $res, 2)) {
                            $pokracuj = false;
                          }
                          if ($pokracuj) {
                            $castSpoje1 = $this->nactiDetailSpoje($Spoj1, $Spoj, $Spoj2);
                            for ($pr1 = 0; $pr1 < $castSpoje1->size(); $pr1++) {
                              $savePrunik1 = $Spoj1->prunik;
                              $Spoj1 = new TCastSpoj();
                              $Spoj1->prunik = $savePrunik1;
                              $Spoj1->Linka = $Linka2Od->linky->elementAt($i3od);
                              $Spoj1->SpojDetail = $castSpoje1->elementAt($pr1);
                              if (!$this->existNullLinky($nullLinky, $Spoj1->Linka, $Spoj1->SpojDetail->Smer, $Spoj1->SpojDetail->ZeZastavky)) {
                                if ($this->zapisSpoj($Spoj1, $Spoj, $Spoj2, null) != 0) {
                                  $pokracuj = false;
                                } else {
                                  for ($copypr = 0; $copypr < $castSpoje1->size(); $copypr++) {
                                    if (($copypr != $pr1) && ($castSpoje1->elementAt($copypr)->ZeZastavky == $castSpoje1->elementAt($pr1)->ZeZastavky)) {
                                      $castSpoje1->elementAt($copypr)->Trasa = $castSpoje1->elementAt(0)->Trasa;
                                      $castSpoje1->elementAt($copypr)->Odjezdy = $castSpoje1->elementAt(0)->Odjezdy;
                                    }
                                  }
                                }
                                $castSpoje2 = $this->nactiDetailSpoje($Spoj2, $Spoj1, null);
                                for ($pr2 = 0; $pr2 < $castSpoje2->size(); $pr2++) {
                                  $savePrunik2 = $Spoj2->prunik;
                                  $Spoj2 = new TCastSpoj();
                                  $Spoj2->prunik = $savePrunik2;
                                  $Spoj2->Linka = $this->DoZastavky->linky->elementAt($ido);
                                  $Spoj2->SpojDetail = $castSpoje2->elementAt($pr2);
                                  if (!$this->existNullLinky($nullLinky, $Spoj2->Linka, $Spoj2->SpojDetail->Smer, $Spoj2->SpojDetail->ZeZastavky)) {
                                    if ($this->zapisSpoj($Spoj2, $Spoj1, null, null) != 0) {
                                      $pokracuj = false;
                                    } else {
                                      
                                    }
                                    if ($pokracuj) {
                                      $addTrasa = new Vector();
                                      $addTrasa->addElement($Spoj);
                                      $addTrasa->addElement($Spoj1);
                                      $addTrasa->addElement($Spoj2);
                                      $odjezd = $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1349 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M;
                                      $prijezd = $Spoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1349 + $Spoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $Spoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
                                      if ($prijezd - $odjezd > $max) {
                                        
                                      } else {
                                        if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                                          $res->addElement($addTrasa);
                                        }
                                      }
                                    }
                                    $konec = false;
                                    $aSpoj = $Spoj;
                                    $aSpoj1 = $Spoj1;
                                    $aSpoj2 = $Spoj2;
                                    while ($konec == false) {
                                      $pokracuj = true;
                                      $delta = 1;
                                      if (!$aSpoj->Linka->special) {
                                        if ($aSpoj->SpojDetail->OdjezdPrijezd != null) {
                                          if ($aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd != null) {
                                            if ($aSpoj1->SpojDetail->OdjezdPrijezd == null) {
                                              $delta = $this->maxDobaPrestup;
                                            } else {
                                              $delta = 1;
                                            }
                                            if ($aSpoj2->SpojDetail->OdjezdPrijezd == null) {
                                              $delta = $this->maxDobaPrestup;
                                            } else {
                                              $delta = 1;
                                            }
                                            $newCasOdjezd = $this->casPlus($aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd, $delta);
                                            $aSpoj = $Spoj->clon();
                                            $aSpoj1 = $Spoj1->clon();
                                            $aSpoj2 = $Spoj2->clon();
                                            if (($depozitTrasa->elementAt($castSpoje->elementAt($pr)->Smer) != null) && ($depozitOdjezd->elementAt($castSpoje->elementAt($pr)->Smer) != null)) {
                                              $aSpoj->SpojDetail->Trasa = $depozitTrasa->elementAt($aSpoj->SpojDetail->Smer);
                                              $aSpoj->SpojDetail->Odjezdy = $depozitOdjezd->elementAt($aSpoj->SpojDetail->Smer);
                                            }
                                            if ($this->zapisSpoj($aSpoj, null, $aSpoj1, $newCasOdjezd) != 0) {
                                              $pokracuj = false;
                                              $konec = true;
                                            } else {
                                              if (($castSpoje->elementAt($pr)->Odjezdy != null) && ($depozitOdjezd->elementAt($castSpoje->elementAt($pr)->Smer) == null)) {
                                                $depozitTrasa->setElementAt($aSpoj->SpojDetail->Trasa, $aSpoj->SpojDetail->Smer);
                                                $depozitOdjezd->setElementAt($aSpoj->SpojDetail->Odjezdy, $aSpoj->SpojDetail->Smer);
                                              }
                                            }
                                            if ($this->existOdjezd($aSpoj, $res, 2)) {
                                              $pokracuj = false;
                                            }
                                            if ($pokracuj) {
                                              $z = $this->zapisSpoj($aSpoj1, $aSpoj, $aSpoj2, null);
                                              if ($z == -1) {
                                                $pokracuj = false;
                                              }
                                              if ($z == -2) {
                                                $pokracuj = false;
                                                $konec = true;
                                                if (!$this->existNullLinky($nullLinky, $aSpoj1->Linka, $aSpoj1->SpojDetail->Smer, $aSpoj1->SpojDetail->ZeZastavky)) {
                                                  $this->addNullLinky($nullLinky, $aSpoj1->Linka, $aSpoj1->SpojDetail->Smer, $aSpoj1->SpojDetail->ZeZastavky);
                                                }
                                              }
                                              if ($pokracuj) {
                                                $z1 = $this->zapisSpoj($aSpoj2, $aSpoj1, null, null);
                                                if ($z1 == -1) {
                                                  $pokracuj = false;
                                                }
                                                if ($z1 == -2) {
                                                  $pokracuj = false;
                                                  $konec = true;
                                                  if (!$this->existNullLinky($nullLinky, $aSpoj2->Linka, $aSpoj2->SpojDetail->Smer, $aSpoj2->SpojDetail->ZeZastavky)) {
                                                    $this->addNullLinky($nullLinky, $aSpoj2->Linka, $aSpoj2->SpojDetail->Smer, $aSpoj2->SpojDetail->ZeZastavky);
                                                  }
                                                }
                                                if ($pokracuj) {
                                                  $addTrasa = new Vector();
                                                  $addTrasa->addElement($aSpoj);
                                                  $addTrasa->addElement($aSpoj1);
                                                  $addTrasa->addElement($aSpoj2);
                                                  $odjezd = $aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->plusden * 1349 + $aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H * 60 + $aSpoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M;
                                                  $prijezd = $aSpoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1349 + $aSpoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $aSpoj2->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
                                                  if ($prijezd - $odjezd > $max) {
                                                    
                                                  } else {

                                                    if ($this->porovnejSpoje($addTrasa, $res) == -1) {
                                                      $res->addElement($addTrasa);
                                                    }
                                                  }
                                                }
                                              }
                                            }
                                          } else {
                                            $konec = true;
                                          }
                                        } else {
                                          $konec = true;
                                        }
                                      } else {
                                        $konec = true;
                                      }
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                    //2 prestupy
                  }
                }
              }
            }
          }
        }
      }
    }
    return $res;
  }

  function sortSpojeni($showprogress, $eliminate) {
    if ($this->vyhledaneSpoje != null) {
      for ($i = 0; $i < $this->vyhledaneSpoje->size(); $i++) {
        for ($ii = 0; $ii < $this->vyhledaneSpoje->size() - 1; $ii++) {
          $first = null;
          $second = null;
          if (($this->vyhledaneSpoje->elementAt($ii)->size() > 1) && ($this->vyhledaneSpoje->elementAt($ii)->elementAt(0)->Linka->special)) {
            $first = $this->vyhledaneSpoje->elementAt($ii)->elementAt(1)->SpojDetail->OdjezdPrijezd->elementAt(0);
          } else {
            $first = $this->vyhledaneSpoje->elementAt($ii)->elementAt(0)->SpojDetail->OdjezdPrijezd->elementAt(0);
          }
          if (($this->vyhledaneSpoje->elementAt($ii + 1)->size() > 1) && ($this->vyhledaneSpoje->elementAt($ii + 1)->elementAt(0)->Linka->special)) {
            $second = $this->vyhledaneSpoje->elementAt($ii + 1)->elementAt(1)->SpojDetail->OdjezdPrijezd->elementAt(0);
          } else {
            $second = $this->vyhledaneSpoje->elementAt($ii + 1)->elementAt(0)->SpojDetail->OdjezdPrijezd->elementAt(0);
          }
          if (($first->Odjezd->H * 60 + $first->Odjezd->M + $first->Odjezd->plusden * 1439) > ($second->Odjezd->H * 60 + $second->Odjezd->M + $second->Odjezd->plusden * 1439)) {
            $pom = $this->vyhledaneSpoje->elementAt($ii); // Usek->elementAt(ii);
            $this->vyhledaneSpoje->setElementAt($this->vyhledaneSpoje->elementAt($ii + 1), $ii);
            $this->vyhledaneSpoje->setElementAt($pom, $ii + 1);
          }
          if ($this->vyhledaneSpoje->elementAt($ii)->elementAt(0)->SpojDetail->DoTarif != -1) {
            if (($first->Odjezd->H * 60 + $first->Odjezd->M + $first->Odjezd->plusden * 1439) == ($second->Odjezd->H * 60 + $second->Odjezd->M + $second->Odjezd->plusden * 1439)) {
              $first = $this->vyhledaneSpoje->elementAt($ii)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0);
              $second = $this->vyhledaneSpoje->elementAt($ii + 1)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0);
              if (($first->Prijezd->H * 60 + $first->Prijezd->M + $first->Prijezd->plusden * 1439) > ($second->Prijezd->H * 60 + $second->Prijezd->M + $second->Prijezd->plusden * 1439)) {
                $pom = $this->vyhledaneSpoje->elementAt($ii);
                $this->vyhledaneSpoje->setElementAt($this->vyhledaneSpoje->elementAt($ii + 1), $ii);
                $this->vyhledaneSpoje->setElementAt($pom, $ii + 1);
              }
            }
          }
        }
      }
    }
    if ($eliminate) {
      $this->vyhledaneSpoje1 = new Vector();
      if ($this->vyhledaneSpoje != null) {
        for ($i = 0; $i < $this->vyhledaneSpoje->size(); $i++) {
          $CasDojezdu1 = $this->vyhledaneSpoje->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $this->vyhledaneSpoje->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $this->vyhledaneSpoje->elementAt($i)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
          $mam = false;
          for ($ii = ($i + 1); $ii < $this->vyhledaneSpoje->size(); $ii++) {
            $CasDojezdu2 = $this->vyhledaneSpoje->elementAt($ii)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->plusden * 1439 + $this->vyhledaneSpoje->elementAt($ii)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H * 60 + $this->vyhledaneSpoje->elementAt($ii)->lastElement()->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
            if ($CasDojezdu1 >= $CasDojezdu2) {
              $mam = true;
              break;
            }
          }
          if ($mam == false) {
            $this->vyhledaneSpoje1->addElement($this->vyhledaneSpoje->elementAt($i));
          }
        }
        $this->vyhledaneSpoje = $this->vyhledaneSpoje1;
      }
    }
  }

  function createSeznam() {

    if (($this->vyhledaneSpoje != null) && ($this->vyhledaneSpoje->size() > 0)) {
      echo "vyhledaneSpoje = new TListSpojeni();";
      for ($i = 0; $i < $this->vyhledaneSpoje->size(); $i++) {
        echo "spoj = new TListSpoj();";
        for ($ii = 0; $ii < $this->vyhledaneSpoje->elementAt($i)->size(); $ii++) {
          $Spoj = $this->vyhledaneSpoje->elementAt($i)->elementAt($ii);
          echo "linka = new TLinka();";
          echo "linka.setValues(null, " . $Spoj->Linka->id_linky . ", '" . $Spoj->Linka->nazev . "', '" . $Spoj->Linka->typ_dopravy . "', null, null);";
          echo "partspoj = new TPartSpoj();";
//          Zastavky.elementAt(".$Spoj->SpojDetail->ZeZastavky.")
          echo "partspoj.setLinka(linka, Zastavky.zastavky.elementAt(" . ($Spoj->SpojDetail->ZeZastavky - 1) . "), null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M . ", Zastavky.zastavky.elementAt(" . ($Spoj->SpojDetail->DoZastavky - 1) . "), null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M . ");";
          echo "spoj.addPartSpoj(partspoj);";
//          echo "document.write('".$Spoj->Linka->typ_dopravy." ... ".$Spoj->SpojDetail->ZeZastavky."(".$Spoj->SpojDetail->ZeTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." ) -> ".$Spoj->SpojDetail->DoZastavky."(".$Spoj->SpojDetail->DoTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." ) ');";
        }
        echo "vyhledaneSpoje.addSpoj(spoj);";
//        echo "document.write('<br>');";
      }
      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'ahoj', vyhledaneSpoje);";
      echo "Spojeni.show('spojeni');";
    } else {
      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'Poï¿½adovanï¿½ spojenï¿½ v rozmezï¿½ od poï¿½adovanï¿½ho ï¿½asu do 2 hodin nebylo nalezeno', null);";
      echo "Spojeni.show('spojeni');";
    }
  }

  function createSeznam1() {

    if (($this->vyhledaneSpoje != null) && ($this->vyhledaneSpoje->size() > 0)) {
      echo "vyhledaneSpoje = new TListSpojeni();";
      for ($i = 0; $i < $this->vyhledaneSpoje->size(); $i++) {
        echo "spoj = new TListSpoj();";
        for ($ii = 0; $ii < $this->vyhledaneSpoje->elementAt($i)->size(); $ii++) {
          $Spoj = $this->vyhledaneSpoje->elementAt($i)->elementAt($ii);
          echo "linka = new TLinka();";
          echo "linka.setValues(null, '" . $Spoj->Linka . "', '" . $Spoj->nazev_linky . "', '" . $Spoj->doprava . "', null, null);";
          echo "partspoj = new TPartSpoj();";
//          Zastavky.elementAt(".$Spoj->SpojDetail->ZeZastavky.")
          echo "partspoj.setLinka(linka,'" . $Spoj->SpojDetail->ZeZastavky . "', null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M . ", '" . $Spoj->SpojDetail->DoZastavky . "', null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M . ");";
          echo "spoj.addPartSpoj(partspoj);";
//          echo "document.write('".$Spoj->Linka->typ_dopravy." ... ".$Spoj->SpojDetail->ZeZastavky."(".$Spoj->SpojDetail->ZeTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." ) -> ".$Spoj->SpojDetail->DoZastavky."(".$Spoj->SpojDetail->DoTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." ) ');";
        }
        echo "vyhledaneSpoje.addSpoj(spoj);";
//        echo "document.write('<br>');";
      }
      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'ahoj', vyhledaneSpoje, " . $this->move . ");";
      echo "Spojeni.show('spojeni');";
    } else {
      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'Zadanï¿½ spojenie (od ï¿½iadanï¿½ho ï¿½asu do 2 hodï¿½n) sa nenaï¿½lo', null, " . $this->move . ");";
      echo "Spojeni.show('spojeni');";
    }
  }

  function createSeznamJSON() {
  $res = '';  
  $res = $res . "<div class = 'div_pozadikomplex' style='width: auto;'>";
  $res = $res . "<div id='movedivSeznam' class='movediv'>";
  $res = $res . "<img class='wclose' style='float:right;' src='http://www.mhdspoje.cz/jrw50/image/closebutton.png' onClick='closeJRSeznam();'></img>";
  $res = $res . "</div>";
  $res = $res . "<table id='tablejrSeznam' class = 'tablejr' style='max-width:500px; width: auto;'>";               
  $res = $res . "<tr>";
  $res = $res . "<td>";

    if (($this->vyhledaneSpoje != null) && ($this->vyhledaneSpoje->size() > 0)) {
//      echo "vyhledaneSpoje = new TListSpojeni();";
      for ($i = 0; $i < $this->vyhledaneSpoje->size(); $i++) {
//        echo "spoj = new TListSpoj();";
        $res = $res . "<table class = 'tablejr' style='max-width:500px; width: auto;'>";               
        $res = $res . "<tr>";
        $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Linka");
        $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Ze zastávky");        
        $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Odjezd");
        $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Do zastávky");        
        $res = $res . "<th>" . iconv('ISO-8859-2', 'UTF-8', "Pøíjezd");        
        $res = $res . "</tr>";
        for ($ii = 0; $ii < $this->vyhledaneSpoje->elementAt($i)->size(); $ii++) {
          $Spoj = $this->vyhledaneSpoje->elementAt($i)->elementAt($ii);
          $res = $res . "<tr class='licha'>";
//          if ($ii == 0) {
          $res = $res . "<td style='text-align: left; width: auto;'>";
            $res = $res . "<a class = 'a_nazev_linky1' style='font-size: 18px;'>";
              $res = $res . $Spoj->nazev_linky;
            $res = $res . "</a>";
          $res = $res . "</td>";
//          }
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
            $res = $res . $Spoj->SpojDetail->ZeZastavky;
          $res = $res . "</td>";

          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
            $res = $res . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H . " : " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M;
          $res = $res . "</td>";
                  
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
            $res = $res . $Spoj->SpojDetail->DoZastavky;
          $res = $res . "</td>";
          
          $res = $res . "<td style='padding: 5px 5px 5px 5px; white-space: normal;'>";
            $res = $res . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H . " : " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M;
          $res = $res . "</td>";
          
          $res = $res . "</tr>";

//          echo "linka = new TLinka();";
//          echo "linka.setValues(null, '" . $Spoj->Linka . "', '" . $Spoj->nazev_linky . "', '" . $Spoj->doprava . "', null, null);";
//          echo "partspoj = new TPartSpoj();";
//          Zastavky.elementAt(".$Spoj->SpojDetail->ZeZastavky.")
//          echo "partspoj.setLinka(linka,'" . $Spoj->SpojDetail->ZeZastavky . "', null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M . ", '" . $Spoj->SpojDetail->DoZastavky . "', null, " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H . ", " . $Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M . ");";
//          echo "spoj.addPartSpoj(partspoj);";
//          echo "document.write('".$Spoj->Linka->typ_dopravy." ... ".$Spoj->SpojDetail->ZeZastavky."(".$Spoj->SpojDetail->ZeTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Odjezd->M." ) -> ".$Spoj->SpojDetail->DoZastavky."(".$Spoj->SpojDetail->DoTarif.")"." ( ".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->H.":".$Spoj->SpojDetail->OdjezdPrijezd->elementAt(0)->Prijezd->M." ) ');";
        }
//        echo "vyhledaneSpoje.addSpoj(spoj);";
//        echo "document.write('<br>');";
        $res = $res . "</table>";  
        $res = $res . "<div style='margin-top: 20px;'></div>";
      }
//      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'ahoj', vyhledaneSpoje, " . $this->move . ");";
//      echo "Spojeni.show('spojeni');";
    } else {
//      echo "Spojeni = new JRSpojeni('sp', 100, 25, 'Zadanï¿½ spojenie (od ï¿½iadanï¿½ho ï¿½asu do 2 hodï¿½n) sa nenaï¿½lo', null, " . $this->move . ");";
//      echo "Spojeni.show('spojeni');";
        $res = $res . "<table class='tablejr' style='max-width:500px; width: auto;'>";               
        $res = $res . "<tr>";
        $res = $res . "<td><a>";
        $res = $res . iconv('ISO-8859-2', 'UTF-8', "Vhodné spojení nebylo nalezeno");
        $res = $res . "</a></td>";
        $res = $res . "</tr>";
        $res = $res . "</table>";
    }
    $res = $res . "</td>";
    $res = $res . "</tr>";
    $res = $res . "</table>";                    
    $res = $res . "</div>";
    
//    echo $res;
    return $res;
  }

  /*  function TSpojeni($maxprestup, $d, $m, $y, $H, $M, $typdne, $Calendar, $OdZastavky, $DoZastavky, $Linky, $Zastavky, $Poznamky, $Trasa, $Odjezdy) {
    $this->OdZastavky = $OdZastavky;
    $this->DoZastavky = $DoZastavky;
    $this->Linky = $Linky;
    $this->Zastavky = $Zastavky;
    $this->Poznamky = $Poznamky;
    $this->TypDne = $typdne;

    $this->vychoziCas = new TCasInter($H, $M, 0);
    $this->vychoziCas->H = $H;
    $this->vychoziCas->M = $M;
    $this->vychoziCasO = (($H * 60) + $M);
    $this->vychoziCas->DD = $d;
    $this->vychoziCas->MM = $m;
    $this->vychoziCas->YYYY = $y;

    $this->maxDobaSpoju = 120;
    $this->maxDobaPrestup = 60;
    $this->minDobaPrestup = 2;

    $this->OdjezdyDepozit = new Vector();

    $this->vyhledaneSpoje = $this->moznostiSpojeni();

    $this->sortSpojeni(true, true);
    $this->createSeznam();
    } */

  function TSpojeni() {
    
  }

}

/*
  echo "document.getElementById('load').style.visibility = 'visible';";
  //echo "document.write('".$day.".".$month.":".$year."');";
  $vspoj = new TSpojeni(2, $day, $month, $year, $h, $m, 3, null, $Zastavky->elementAt($odZ), $Zastavky->elementAt($doZ), $Linky, $Zastavky, $Poznamky, null, null); */
?>
