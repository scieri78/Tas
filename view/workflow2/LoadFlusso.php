<input type="hidden" name="Flusso" value="<?php echo $NomeFlusso; ?>">
<input type="hidden" id="dettList" name="dettList" value="<?php echo $dettList; ?>">
<button onclick="ReOpenDettFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','<?php echo $DescFlusso; ?>','<?php echo $ProcMeseEsame; ?>');return false" class="btn" style="background-color: #FFFFAD;"><i class="fa-solid fa-refresh"> </i></button>
    <?php

$BlkCons = false;
$NotBlkCons = false;
if (${BlockCons} == "N" and ${PeriodCons} != "0") {
$BlkCons = true;
}
if ( ${PeriodCons} != "0") {
$BlkCons = true;
}
if (${BlockCons} == "S" and ${PeriodCons} != "0" and $RdOnly == 0) {
$NotBlkCons = true;
}
$BlkCons=false;
$BlkRead=false;
$NoRead=false;
if ( $NomeFlusso != "UTILITY" ){  
  //BlockCons
  //"N"> Disabilitato con periodo Consolidato
  //"S"> Abilitato con periodo Consolidato
  //"X"> Abilitato senza svalidazione con periodo Consolidato
  //"O"> Sempre Abilitato
  //echo "B:$BlockCons";
  
  if ( ${BlockCons} == "S" and ${PeriodCons} == "0" ){ $BlkCons=true; }
  if ( ${BlockCons} == "X" and ${PeriodCons} == "0" ){ $BlkCons=true; }
  if ( ${BlockCons} == "N" and ${PeriodCons} == "1" ){ $BlkCons=true; }
  if ( ${BlockCons} == "O" and ${PeriodCons} == "1" ){ $NoRead=true; }
  if ( ${BlockCons} == "S" and ${PeriodCons} == "1" ){ $NoRead=true; }
  if ( ${BlockCons} == "X" and ${PeriodCons} == "1" ){ $NoRead=true; }
  if ( ${Permesso}  == 0   and ${PeriodCons} == "1" ){ $BlkCons=true; }
  if ( ${Permesso}  == 0   and ${PeriodCons} == "0" ){ $BlkRead=true; }
  if ( ${RdOnly}  != 0   ){ $BlkRead=true; }
  if ( ( $WfsRdOnly != 0 or $BlkRead ) and ! $BlkCons and ! $NoRead ){
    ?><img src="./images/ReadMode.png" id="FlussoReadOnly" style="width: 36px;right: 27px;position: absolute;padding-top: 5px;" ><?php
  } else {
    if ( $BlkCons ){
     ?><img src="./images/Lock.png" style="width: 23px;right: 27px;position: absolute;padding-top: 2px;"  ><?php
    } 
  }
}else{
    if ( $RdOnly  != 0 ){ $BlkRead=true; }
   // if ( ${Permesso}  == 0 ){ $BlkRead=true; }
    if ( $WfsRdOnly != 0 || $BlkRead ){
    ?><img src="./images/ReadMode.png" id="FlussoReadOnly" style="width: 36px;right: 27px;position: absolute;padding-top: 5px;" ><?php
  }
}




?>
<table class="ExcelTable">
  <tr>
    <td colspan=4>

      <div style="text-align:center;">
      <img style="width: 25px; margin: 10px;" src="./images/Flusso.png"><B><?php echo $NomeFlusso;
                                                                              if ($DescFlusso != "") {
                                                                                echo " - $DescFlusso";
                                                                              }
                                                                              if ( $BlockCons == "X" ){ ?></B>
                                                                              <b style="color:blue; margin-left:5px;">ONESHOT
                                                                              <img src="./images/OneShot.png" title="OneShot" width="30px">
                                                                              </b>
                                                                              <?php }
                                                                              ?>
      </div>
    </td>
  </tr>
  <?php
  $FlusPermesso=$Permesso;
  /*echo "<pre>";
  print_r($ArrLegami);
  echo "</pre>";*/
  foreach ($ArrLegami as $Legame) { 
    $IdLegame      = $Legame[0];
    $Prio          = $Legame[1];
    $Tipo          = $Legame[2];
    $IdDip         = $Legame[3];
    $DipName       = $Legame[4];
    $DipDesc       = $Legame[5];
    $DipIniz       = $Legame[6];
    $DipFine       = $Legame[7];
    $DipDiff       = $Legame[8];
    $DipEsito      = $Legame[9];
    $DipNote       = $Legame[10];
    $DipLog        = $Legame[11];
    $DipFile       = $Legame[12];
    $DipInCoda     = $Legame[13];
    $DipTarget     = $Legame[14];
    $DipLinkTipo   = $Legame[15];
    $DipElaIdSh    = $Legame[16];
    $DipElaTags    = $Legame[17];
    $RdOnly        = $Legame[18];
    $Permesso      = $Legame[19];
    $WfsRdOnly     = $Legame[20];
    $IdRunSh       = $Legame[21];
    $DipUtente     = $Legame[22];
    $OldIdRunSh    = $Legame[23];
    $OldInizio     = $Legame[24];
    $OldFine       = $Legame[25];
    $OldDiff       = $Legame[26];
    $OldRunUser    = $Legame[27];
    $OldLog        = $Legame[28];
    $DipCarConf    = $Legame[29];
    $BlockWfs      = $Legame[30];
    $ReadOnlyWfs   = $Legame[31];
    $External      = $Legame[32];
    $Warning       = $Legame[33];
    $DipReadDip    = $Legame[34];
    $DipReadFlu    = $Legame[35];
    $DipRPreview   = $Legame[36];
    $ShowProc      = $Legame[37];
    $Opt           = $Legame[38];
    $nomeLog        = $Legame[39];
    $CntNextPrio   = $Legame[41];
    $CntNextDip   = $Legame[42];

    if ($Tipo  == "F") {
      $DatiLegamiFlussiF = $_model->getLegamiFlussiF($IdWorkFlow, $IdProcess, $ProcMeseEsame, $IdDip);

      foreach ($DatiLegamiFlussiF as $row) {
        $CntKO = $row['KO'];
        $CntOK = $row['OK'];
        $CntIZ = $row['IZ'];
        $CntTD = $row['TD'];
        $CntTT = $row['TT'];
        $CntTTF = $row['TTF'];
      }

      if ($CntTTF != 0) {

        $Errore = 0;
        $Note = "";
        $Stato = 'N';
        try{
              $callRes = $_model->callTestSottoFlussi($IdWorkFlow, $IdProcess, $IdDip, $Stato, $Errore, $Note);
              $Stato = $callRes['Stato'];
              unset($callRes);
           } catch (Exception  $e) {
               $Stato = "N";
           }   
        if ($Stato == "S") {
          $CntKO =0;
        } else {
          if ($CntOK == $CntTT) {
            $CntOK = $CntOK + 1;
          }
        }
      }

      if ($CntKO != 0) {
        $DipEsito = "E";
      } else {
        if ($CntOK == $CntTT) {
          $DipEsito = 'F';
        } else {
          if ($CntOK == 0 and $CntIZ == 0) {
            $DipEsito = 'N';
          } else {
            $DipEsito = 'I';
          }
        }
      }
    }
   ${'ID'.$IdLegame}=$DipEsito;


    $TestPrioNonCompleta = false;

 foreach ($ArrLegami as $LegamePrio) {
    $TestLegame = $LegamePrio[0];
   $TestPrio        = $LegamePrio[1];
   if ($TestPrio < $Prio and $LegamePrio[38] == "N") {
      if ( ${'ID'.$TestLegame}  != "F" and ${'ID'.$TestLegame}  != "W" ) {  $TestPrioNonCompleta = true; break; }
   }
 }

    $Blocca = "";
    if ($TestPrioNonCompleta
    or ($BlockCons == "S" and $PeriodCons == "0")
    or ($BlockCons == "X" and $PeriodCons == "0")
    ) {
      $Blocca = " Bloccato";
    }
    $TarTab = "";
    $Batch = "N";
    switch ($Tipo) {
      case "C":
        $ImgTipo = "Carica";
        $DatiLoadTrascodifica = $_model->getLoadTrascodifica($DipName);
        foreach ($DatiLoadTrascodifica as $row) {
          $TarTab = $row['TARGTAB'];
        }
        break;
      case "F":
        $ImgTipo = "Flusso";
        break;
      case "V":
        $ImgTipo = "Valida";
        break;
      case "E":
        foreach ($ArrShellDett as $DettShell) {
          $IdShell = $DettShell[0];
          if ($IdShell == $DipElaIdSh) {
            $Parall = $DettShell[1];
            $Batch = $DettShell[2];
            $Block = $DettShell[3];
          }
        }

        if ($Batch == "Y") {
          $ImgTipo = "InMacchina";
        } else {
          if ($DipCarConf == "N") {
            $ImgTipo = "Elaborazione";
          } else {
            $ImgTipo = "SaltaElab";
          }
        }
        $datiLaborazioni = $_model->getElaborazioni($IdDip);

        foreach ($datiLaborazioni as $row) {
          $TarTab = $row['TARGTAB'];
        }
        break;
      case "L":
        $ImgTipo = "Setting";
        if ($DipLinkTipo == "E") {
          $ImgTipo = "Link";
        }
        break;
    }

    $ImgDip = './images/Attesa.png';
    if ($DipEsito == 'N' and  $Blocca == "" and $Tipo <> 'F') {
      $ImgDip = './images/Eseguibile.png';
    }

    $classDipendenza = "";
    switch ($DipEsito) {
      case "E":
        $classDipendenza = "Terminato";
        $ImgDip = './images/KO.png';
        break;
      case "F":
        $classDipendenza = "Eseguito";
        $ImgDip = './images/OK.png';
        if ($DipNote == "Confermo il dato in tabella") {
          $ImgDip = './images/ConfermoDato.png';
        }
        $Abilita = true;
        break;
      case "C":
        $classDipendenza = "InEsecuzione";
        $ImgDip = './images/Loading.gif';
        break;
      case "I":
        $classDipendenza = "InEsecuzione";
        $ImgDip = './images/Loading.gif';
        break;
      case "W":
        $classDipendenza = "Eseguito";
        $ImgDip = './images/Warning.png';
        break;
      default:
        null;
    }

    if ($DipEsito != 'F' and $DipEsito != 'W' and $Tipo == 'F') {
      $ImgDip = './images/Attesa.png';
    }

    if ($Opt == "Y") {
      $ImgDip = './images/Opt.png';
    }
    if ($Opt == "M") {
      $ImgDip = './images/hand.png';
    }
    $Bloccato = 'N';
    $RdDipOnly = 'N';
    
    if ($Blocca != "") {
      $Bloccato = 'Y';
    }
    
    if ($DipInCoda != 0) {
      $Bloccato = 'Y';
    }
    
    if ($WfsRdOnly != 0) {
      $Bloccato = 'Y';
    }
    
    if ($DipReadFlu != 0 and $DipReadDip == 0) {
      $Bloccato = 'Y';
      $RdDipOnly = 'Y';
    }

    if ($RdOnly != 0) {
      $Bloccato = 'Y';
    }

    if ($Permesso == 0) {
      $Bloccato = 'Y';
    }
    
    if ( $NomeFlusso == "UTILITY") {
      $Bloccato = 'N';
      $RdDipOnly = 'N';
      $Blocca = "";
    }

  ?>
    <tr onclick="apriDettaglio(<?php echo $IdLegame; ?>)" class="<?php if ($Tipo != "F") { ?>Dipendenza<?php }
                                                                                                        echo $Blocca; ?>">
      <td style="width:35px;text-align: right;" class="<?php echo $Blocca; ?>">
        <?php
        if ($Tipo == "V" and $External == "Y") {
        ?><div style="float:left;">EXT.</div><?php
                                            }
                                              ?>
        <img class="ImgIco" src="./images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>">
      </td>
      <td style="width:10px;text-align:center;" class="<?php echo $Blocca; ?>" title="<?php echo $TarTab; ?>"><?php  echo $Prio;  ?></td>
      <td style="width:250px;" class="<?php echo $Blocca; ?>">
        <?php
        if (
          (in_array($IdDip, $ArrElaTest) and $Tipo == "E")
          ||
          (in_array($IdDip, $ArrCarTest) and $Tipo == "C")
        ) {
        ?><img class="ImgIco" src="./images/Lab.png" title="Laboratorio"><?php
        }
        if( $attivoA) {  $isAutorun = $_model->getAutorun($IdProcess,$IdFlusso,$IdLegame);  }
        if( $isAutorun){ echo '<img src="./images/stop.png" title="Fine AutoPlay" class="ImgIco" >';  }
        if ($ReadOnlyWfs == "S") { ?><img class="ImgIco" src="./images/bandiera.png"><?php }
        $RdDipOnlyClass = "";
     if (${Permesso} == "0" and $FlusPermesso != 0 ) {
       ?><img src="./images/ReadMode.png" class="ImgIco" style="width:36px;height:auto;" ><?php
     }   
   if ($RdDipOnly == "Y") {
     $RdDipOnlyClass = "RdDipOnly";
     if (${PeriodCons} == "0") {
       ?><img src="./images/ReadMode.png" class="ImgIco"><?php
     } else {
       ?><img src="./images/Lock.png" class="ImgIco"><?php
     }
   }
   ?><div class="testoDipendenza <?php echo $RdDipOnlyClass; ?>" title="<?php
   if ($DipDesc != "") {
     echo $DipDesc;
   }
   ?>"><?php echo $DipName; ?></div><?php
        if ($Warning == "-1") { ?><img class="ImgIco" src="./images/Alert.png" title="Strato Rimpiazzato"><?php }
        if ($Warning > "0")   { ?><img class="ImgIco" src="./images/Attention.png" title="<?php if ($Warning > "1") { echo $Warning; }  ?>"><?php } ?>
      </td>
      <td style="width:30px;" class="<?php echo $Blocca; ?>" title="<?php echo $IdLegame; ?>">
        <img id="ImgDip<?php echo $IdLegame ?>" class="ImgIco" src="<?php echo $ImgDip; ?>" <?php if ($DipInCoda != 0) { ?>hidden<?php } ?>>
        <img id="ImgRun<?php echo $IdLegame ?>" class="ImgIco" src="./images/Loading.gif" hidden>
        <img onclick="nascondiActionFlusso(<?php echo $IdFlu; ?>)" id="ImgRefresh<?php echo $IdLegame ?>" class="ImgIco" src="./images/refresh.png" hidden>
        <img id="ImgSveglia<?php echo $IdLegame ?>" class="ImgIco" src="./images/Sveglia.png" <?php if ($DipInCoda == 0) { ?>hidden<?php } ?>>
      </td>
    </tr>
    <?php
    if ($Tipo != "F") {

      $HiddenDett = "hidden";
    ?>
      <tr id="Dett<?php echo $IdLegame; ?>" <?php echo $HiddenDett; ?>>
        <td colspan=4 style="text-align: center;background:#eff4ff;">
          <div id="NRef<?php echo $IdLegame; ?>" hidden>
            <table class="ExcelTable">
              <tr>
                <td style="text-align: center;cursor:pointer;"><img class="ImgIco" src="./images/refresh.png"
                    onclick="ReOpenDettFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','<?php echo $DescFlusso; ?>','<?php echo $ProcMeseEsame; ?>');return false;">Need Refresh</td>
              </tr>
            </table>
          </div>
          <?php


          if ($DipElaTags != "") {
          ?>
            <table class="ExcelTable LastRun">
              <tr>
                <th>Tags</th>
                <td><?php echo $DipElaTags; ?></td>
              </tr>
            </table>
          <?php
          }

          if ($DipIniz != "") {
          ?>
            <table class="ExcelTable LastRun">
              <tr>
                <th>User</th>
                <td><?php
                    if ($DipUtente == "Autorun") {
                      echo "Autorun";
                    } else {
                      foreach ($ArrUsers as $DUser) {
                        $UNom = $DUser[0];
                        $UUser = $DUser[1];
                        if ($UUser == $DipUtente) {
                          echo $UNom;
                        }
                      }
                    }
                    ?></td>
              </tr>
              <?php
              switch ($Tipo) {
                case "C":
              ?>
                  <tr>
                    <th>Start</th>
                    <td><?php echo $DipIniz; ?></td>
                  </tr>
                  <tr>
                    <th>End</th>
                    <td><?php
                        if ($DipFine != "") {
                          echo $DipFine;
                        } else {
                          if ($OldFine != "") {
                            echo "</B>Preview<B> $DipRPreview";
                          }
                        }
                        ?></td>
                  </tr>
                  <tr>
                    <th>Time</th>
                    <td><div id="DivTimeBar<?php echo $IdLegame; ?>"><?php 
                    if ( $DipEsito != "I" ){
                       echo gmdate('H:i:s', $DipDiff); 
                    }
                    ?></div></td>
                  </tr>
                  <tr>
                    <th>Meter</th>
                    <td>
                      <?php
                      if ($OldDiff != "") {
                      ?>
                        <div id="DivMeterBar<?php echo $IdLegame; ?>"></div>
                        <script>
                          OpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', <?php echo $DipDiff; ?>, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                          <?php
                          if ($DipEsito == "I") {
                          ?>
                            if (!$('#ImgRefresh<?php echo $IdLegame ?>').is(":visible")) {
                                avviaOpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', 0, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                            }
                          <?php
                          }
                          ?>
                        </script>
                        <?php
                      }
                      if ($DipEsito == "I") {
                      ?><script>
                     avviaNewTime(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>');
                        </script>  
                      <?php
                      }
                      ?>
                    </td>
                  </tr>               
                  <tr>
                    <th>File</th>
                    <td><?php echo $DipFile; ?></td>
                  </tr>
                  <tr>
                    <th>Note</th>
                    <td><textarea class="ReadNote" rows="4" readonly><?php echo $DipNote; ?></textarea></td>
                  </tr>
                  <tr>
                    <th></th>
                    <td>
                      <?php 
                      if ($IdRunSh != ""  or ($Admin or   $TASAdmin or $WFSAdmin )) { 
                            ?><img class="ImgIco IconButton" src="./images/LogProc.png" onclick="OpenProcessing(<?php echo $IdRunSh; ?>)"><?php 
                      }
                      if ($DipLog != "") { 
                           ?><img class="ImgIco IconButton" src="./images/Log.png" onclick="OpenLogFileElab('<?php echo $DipLog; ?>','apriLogElab')"><?php
                      }
                      if ($DipEsito == 'E'){ 
                           ?><img class="ImgIco IconButton" src="./images/Scarti.png" onclick="TableKo('<?php echo $IdDip; ?>','<?php echo $IdProcess; ?>','<?php echo $DipName; ?>')"><?php 
                      }  ?>
                    </td>
                  </tr>
                <?php
                  break;
                case "E":
                ?>
                  <tr>
                    <th>Start</th>
                    <td><?php echo $DipIniz; ?></td>
                  </tr>
                  <tr>
                    <th>End</th>
                    <td><?php
                        if ($DipFine != "") {
                          echo $DipFine;
                        } else {
                          if ($OldFine != "") {
                            echo "</B>Preview<B> $DipRPreview";
                          }
                        }
                        ?></td>
                  </tr>
                  <tr>
                    <th>Time</th>
                    <td>
                      <div id="DivTimeBar<?php echo $IdLegame; ?>"><?php 
                      if ( $DipEsito != "I" ){
                        echo gmdate('H:i:s', $DipDiff); 
                      }
                      ?></div>
                    </td>
                  </tr>
                  <tr>
                    <th>Meter</th>
                    <td>
                      <?php
                      if ($OldDiff != "") {
                      ?>
                        <div id="DivMeterBar<?php echo $IdLegame; ?>"></div>
                        <script>
                          OpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', <?php echo $DipDiff; ?>, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                          <?php
                          if ($DipEsito == "I") {
                          ?>
                            if (!$('#ImgRefresh<?php echo $IdLegame ?>').is(":visible")) {
                                avviaOpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', 0, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                            }
                          <?php
                          }
                          ?>
                        </script>
                      <?php
                      }
                      if ($DipEsito == "I") {
                      ?><script>
                        avviaNewTime(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>');
                        </script>  
                      <?php
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Note</th>
                    <td><textarea class="ReadNote" rows="4" readonly><?php echo $DipNote; ?></textarea></td>
                  </tr>
                  <tr>
                    <th></th>
                    <td>
                      <?php if ($DipLog != "") { ?><img class="ImgIco IconButton" src="./images/Log.png" onclick="OpenLogFileElab('<?php echo $DipLog; ?>','apriLogElab')"><?php } ?>
                      <?php if ($IdRunSh != ""  or ($Admin or   $TASAdmin or $WFSAdmin or $ShowProc == "Y")) { ?>
                        <img class="ImgIco IconButton" src="./images/LogProc.png" onclick="OpenProcessing(<?php echo $IdRunSh; ?>)">
                      <?php }  ?>
                      <img src="./images/Graph.png" onclick="openGrafici('<?php echo $nomeLog; ?>', '', '<?php echo $DipElaIdSh; ?>')" class="ImgIco IconButton">
                    </td>
                  </tr>
                  <?php
                  break;
                case "V":
                  if ($External == "Y") {
                  ?>
                    <tr>
                      <th>Start</th>
                      <td><?php echo $DipIniz; ?></td>
                    </tr>
                    <tr>
                      <th>End</th>
                      <td><?php
                          if ($DipEsito != "I") {
                            echo $DipFine;
                          } else {
                            if ($OldFine != "") {
                              echo "</B>Preview<B> $DipRPreview";
                            }
                          }
                          ?></td>
                    </tr>
                    <tr>
                      <th>Time</th>
                      <td>
                        <div id="DivTimeBar<?php echo $IdLegame; ?>"><?php 
                    if ( $DipEsito != "I" ){
                       echo gmdate('H:i:s', $DipDiff); 
                    }
                    ?></div>
                      </td>
                    </tr>
                    <tr>
                      <th>Meter</th>
                      <td>
                        <?php
                        if ($OldDiff != "" and $OldInizio != $OldFine) {
                        ?>
                          <div id="DivMeterBar<?php echo $IdLegame; ?>"></div>
                          <script>
                            OpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', <?php echo $DipDiff; ?>, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                            <?php
                            if ($DipEsito == "I") {
                            ?>
                              if (!$('#ImgRefresh<?php echo $IdLegame ?>').is(":visible")) {
                             //   tt = setInterval(function() {
                                  avviaOpenMeterBar(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>', 0, <?php echo $OldDiff; ?>, '<?php echo $DipEsito; ?>');
                             //   }, 3000);
                            //    console.log('Simone OpenMeterBar:' + tt);
                              }
                            <?php
                            }
                            ?>
                          </script>
                        <?php
                      }
                      if ($DipEsito == "I") {
                      ?><script>
              avviaNewTime(<?php echo $IdFlu; ?>, <?php echo $IdLegame; ?>, '<?php echo $DipIniz; ?>');
                        </script>  
                      <?php
                      }
                      ?>
                      </td>
                    </tr>
                    <?php if ($_SESSION['IdTeam'] == 4) { ?>
                      <tr>
                        <th>Log</th>
                        <td>
                         <img class="ImgIco IconButton" src="./images/Log.png" onclick="OpenLogFileElab('/area_staging_TAS/DIR_LOG/RETI_TWS/<?php echo $DipName . '.sh_' . $EserMese . '*'; ?>.log','apriLogElab')">
                        </td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <th>Note</th>
                      <td><textarea class="ReadNote" rows="4" readonly><?php echo $IdTeam . $DipNote; ?></textarea></td>
                    </tr>
                    <tr>
                      <?php
                    } else {
                      if ($_SESSION['IdTeam'] == 4) {
                      ?>
                    <tr>
                      <th>Log</th>
                      <td>
                     <img class="ImgIco IconButton" src="./images/Log.png" onclick="OpenLogFileElab('/area_staging_TAS/DIR_LOG/RETI_TWS/<?php echo $DipName . '.sh_' . $EserMese . '*'; ?>.log','apriLogElab')">
                      </td>
                    </tr>
                  <?php
                      }
                  ?>
                  <tr>
                    <th>Data</th>
                    <td><?php echo $DipIniz; ?></td>
                  </tr>
                <?php
                    }
                    break;
                  case "L":
                ?>
                <tr>
                  <th>Data</th>
                  <td><?php echo $DipIniz; ?></td>
                </tr>
            <?php
                    break;
                }
            ?>
            </table>
          <?php
          } ?>
          <div id="actionFlusso<?php echo $IdFlu; ?>">
            <?php 
            if ( $RdOnly != 0 or $Permesso == 0 ) {
               switch ($Tipo) { 
                  case "L":
                  if ($DipLinkTipo == "I") {
                  ?>
                    <div class="input_container">
                      <div onclick="OpenLink(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipTarget; ?>','<?php echo $DipName; ?>','<?php echo $Bloccato; ?>','<?php echo $DipEsito; ?>','<?php echo $IdDip; ?>','<?= $RdOnly ?>')" class="btn">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Apri Link
                      </div>
                    </div>
                  <?php
                  }
               }  
            }     
            if ( ( $NomeFlusso == "UTILITY" and $RdOnly == 0)  or  (($Blocca == "") and (($Bloccato == "N") or ($Tipo == 'L') or $BlockCons == "S" ) and $RdOnly == 0 and $Permesso != 0 and ! $BlkCons  )      ) {
              switch ($Tipo) {
                case "C":
                  if ($DipEsito == "N" or $DipEsito == "E") {
            ?>
                    <?php if (!in_array($IdDip, $ArrCarTest)): ?>
                      <div class="input_container">
                        Load File:
                        <input type="hidden" name="NomeInput" value="<?php echo $DipTarget; ?>">

                        <input onclick="destroyInterval()" style="display: inline;" type="file" name="UploadFileName_<?php echo $IdLegame; ?>" id="UploadFileName_<?php echo $IdLegame; ?>">
                        <div onclick="if(controllaFile('UploadFileName_<?php echo $IdLegame; ?>')) Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Carica','',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>');" class="btn">
                          <i class="fa-solid fa-upload"> </i> Load
                        </div>
                      </div>
                    <?php endif; ?>
                    <?php
                    if ($DipCarConf == "S" or $DipCarConf == "C") {
                    ?>
                      <div class="input_container">
                        <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','ConfermaDato','Confermi di validare il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">

                          <i class="fa-solid fa-circle-check"></i> Conferma Dato in Tabella
                        </div>
                      </div>
                      <?php if (!in_array($IdDip, $ArrCarTest) and $DipCarConf == "C" ): ?>
                        <div class="input_container">
                          <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','CopiaDato','Confermi di copiare dal vecchio strato e sostituire il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                            <i class="fa-solid fa-copy"></i> Copia Periodo Prec in Tabella
                          </div>
                        </div>
                      <?php endif; ?>
                    <?php
                    }
                  } else {
                    if ($DipEsito != "I" and ! $SvalidaOff) {
                    ?>
                      <center><table class="svalida">
                      <tr>
                      <td>
                      <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-regular fa-circle-xmark"></i> Svalida
                      </div>
                    </div>
                    <?php if ( $CntNextPrio > 1 || ( $CntNextPrio == 0 && $CntNextDip != 0 ) ) { ?>
                    </td>
                    <td>
                    <div class="input_container" >
                       <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','SvalidaSuc','Confermi di voler svalidare i passi successivi?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                         <i class="fa-regular fa-circle-xmark"></i> Svalida Passi Succ.
                       </div>
                    </div>
                    <?php } ?>
                    </td>                    
                    </tr>
                    </table class="svalida"></center>         
                    <?php
                    }
                  }
                  if ($DipEsito <> "F" and $DipEsito <> "C" and $DipEsito <> "E" and $Rinnovabile == "S") {
                    ?>
                    <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','ValidaDato','Confermi il dato esitente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-solid fa-circle-check"></i> Valida Dati Esistenti
                      </div>
                    </div>
                    <?php
                  }
                  break;
                case "V":
                  if ($DipEsito <> "F") {
                    if ($External == "N") {
                    ?>
                      <div class="input_container">
                        <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>' ,'Valida','Confermi le elaborazioni fino ad ora?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                          <i class="fa-solid fa-circle-check"></i> Valida
                        </div>
                      </div>
                      <?php
                    }
                  } else {
                    if (! $SvalidaOff) {

                      if ($External == "N") {
                      ?>
                    <center><table class="svalida">
                      <tr>
                      <td>
                      <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-regular fa-circle-xmark"></i> Svalida
                      </div>
                    </div>
                    <?php if ( $CntNextPrio > 1 || ( $CntNextPrio == 0 && $CntNextDip != 0 ) )  { ?>
                    </td>
                    <td>
                    <div class="input_container" >
                       <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','SvalidaSuc','Confermi di voler svalidare i passi successivi?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                         <i class="fa-regular fa-circle-xmark"></i> Svalida Passi Succ.
                       </div>
                    </div>
                    <?php } ?>
                    </td>                    
                    </tr>
                    </table></center>         
                        <?php
                      } else {
                        if ($Warning > 0) {
                        ?>
                          <div class="input_container">
                            <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                              <i class="fa-regular fa-circle-xmark"></i> Svalida
                            </div>
                          </div>
                        <?php
                        } else {
                        ?>
                          <div class="input_container">
                            <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','SvalidaSuc','Confermi di voler svalidare i passi successivi?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                              <i class="fa-regular fa-circle-xmark"></i> Svalida Passi Succ.
                            </div>
                          </div>
                        <?php
                        }
                        if ($Warning != "0") {
                        ?>
                          <div class="input_container">
                            <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Spegni','Confermi di voler spegnere il warning del passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                              <i class="fa-solid fa-bell-slash"></i> Spegni Warning
                            </div>
                          </div>
                    <?php
                        }
                      }
                    }
                  }

                  break;
                case "E":
                  if ( ($DipEsito == "N") ) {
                    ?>

                    <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Elabora','Confermi di voler elaborare questo passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-solid fa-play"></i> Avvia Elaborazione
                      </div>
                    </div>

                  <?php
                  }
                  if (
                  (($DipEsito == "F" ||  $DipEsito == "W") and ! $SvalidaOff ) 
                  or ($DipEsito == "E"  and ! $SvalidaOff  )   ) {
                  ?>
                      <center><table class="svalida">
                      <tr>
                      <td>
                      <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-regular fa-circle-xmark"></i> Svalida
                      </div>
                    </div>
                    <?php if  ( ( $CntNextPrio > 1 || ( $CntNextPrio == 0 && $CntNextDip != 0 ) )  && ( $DipEsito == "F" ||  $DipEsito == "W" ) &&  "$NomeFlusso" != "UTILITY"  ){ ?>
                    </td>
                    <td>
                    <div class="input_container" >
                       <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','SvalidaSuc','Confermi di voler svalidare i passi successivi?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                         <i class="fa-regular fa-circle-xmark"></i> Svalida Passi Succ.
                       </div>
                    </div>
                    <?php } ?>
                    </td>                    
                    </tr>
                    </table></center>                    
                  <?php

                  }
                  if ($DipCarConf == "S") {
                  ?>

                    <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','ConfermaDato','Confermi di voler saltare il passo?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-regular fa-circle-xmark"></i> Salta Elaborazione
                      </div>
                    </div>

                    <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','CopiaDato','Confermi di copiare dal vecchio strato e sostituire il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-solid fa-copy"></i> Copia Periodo Prec in Tabella
                      </div>
                    </div>

                  <?php
                  }
                  break;
                case "L":
                  if ($DipLinkTipo == "E") {
                  ?><a href='<?php echo $DipTarget; ?>' target='_blank'>
                      <div class="Bottone" id="Puls_Valida<?php echo $IdLegame; ?>">Apri Link</div>
                    </a><?php
                        if ($Bloccato == "N" and $DipEsito != "F" and "$NomeFlusso" != "UTILITY" ) {
                        ?>
                      <div class="input_container">
                        <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','ConfermaDato','Confermi di validare il dato presente in tabella?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                          <i class="fa-solid fa-circle-check"></i> Conferma Dato in Tabella
                        </div>
                      </div>
                      <div class="input_container">
                        <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Valida','Confermi di voler Validare il Link selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                          <i class="fa-solid fa-circle-check"></i> Valida
                        </div>
                      </div>

                    <?php
                        }
                      } else {
                    ?>
                    <div class="input_container">
                      <div onclick="OpenLink(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdLegame; ?>,'<?php echo $DipTarget; ?>','<?php echo $DipName; ?>','<?php echo $Bloccato; ?>','<?php echo $DipEsito; ?>','<?php echo $IdDip; ?>','<?= $RdOnly ?>')" class="btn">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Apri Link
                      </div>
                    </div>
                  <?php
                      }

                      if ($DipEsito == "F" and  $Bloccato == "N" and ! $SvalidaOff  ) {
                  ?>
                    <center><table class="svalida">
                      <tr>
                      <td>
                      <div class="input_container">
                      <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Svalida','Confermi di voler svalidare dal passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                        <i class="fa-regular fa-circle-xmark"></i> Svalida
                      </div>
                    </div>
                    <?php if( $CntNextPrio > 1 || ( $CntNextPrio == 0 && $CntNextDip != 0 ) && "$NomeFlusso" != "UTILITY" ){ ?>
                    </td>
                    <td>
                    <div class="input_container" >
                       <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','SvalidaSuc','Confermi di voler svalidare i passi successivi?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                         <i class="fa-regular fa-circle-xmark"></i> Svalida Passi Succ.
                       </div>
                    </div>
                    <?php } ?>
                    </td>                    
                    </tr>
                    </table></center>         
            <?php
                      }
                      if ($Warning != "0" and "$NomeFlusso" != "UTILITY"  ) {
                        ?>
                          <div class="input_container">
                            <div onclick="Action(<?php echo $IdFlu; ?>,'<?php echo $NomeFlusso; ?>','Spegni','Confermi di voler spegnere il warning del passo selezionato?',<?php echo $IdLegame; ?>,'<?php echo $IdDip; ?>','<?php echo $DipName; ?>','<?php echo $Tipo; ?>')" class="btn">
                              <i class="fa-solid fa-bell-slash"></i> Spegni Warning
                            </div>
                          </div>
                    <?php
                        }
                      break;
                    case "F":
                      break;
                  }
                } ?>
          </div>
              <?php  if ( $DipEsito == 'I' ) { ?><script>nascondiDiv('<?php echo $IdFlu; ?>', '<?php echo $IdLegame; ?>', '<?php echo $DipEsito; ?>');</script><?php }?> 
          <?php
          if ($OldInizio != ""  and $OldInizio != $OldFine) {
          ?>
            <table class="ExcelTable OldRun">
              <tr>
                <th>Old User</th>
                <td><?php
                    if ($OldRunUser != "Autorun") {
                      foreach ($ArrUsers as $DUser) {
                        $UNom = $DUser[0];
                        $UUser = $DUser[1];
                        if ($UUser == $OldRunUser) {
                          echo $UNom;
                        }
                      }
                    } else {
                      echo $OldRunUser;
                    }
                    ?></td>
              </tr>
              <tr>
                <th>Old Start</th>
                <td><?php echo $OldInizio; ?></td>
              </tr>
              <tr>
                <th>Old End</th>
                <td><?php echo $OldFine; ?></td>
              </tr>
              <tr>
                <th>Old Time</th>
                <td><?php echo gmdate('H:i:s', $OldDiff); ?></td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <?php       
                  if ($OldLog != "") {                
                    
                    ?><img class="ImgIco IconButton" src="./images/Log.png" onclick="OpenLogFileElab('<?php echo $OldLog; ?>','apriLogElab')"><?php } ?>
                  <?php if ($OldIdRunSh != "" or ($Admin or  $TASAdmin or $WFSAdmin or $ShowProc == "Y")) { ?><img class="ImgIco IconButton" src="./images/LogProc.png" onclick="OpenProcessing(<?php echo $OldIdRunSh; ?>)"><?php } ?>
                </td>
              </tr>
            </table>
          <?php
          }
          ?>
        </td>
      </tr>
      <?php
    }
      ?><?php
      }
        ?>
</table><?php


        ?>