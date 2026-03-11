<?php $option_process = '';
foreach ($ArrIdProcess as $DettProc) {
  $IdProc = $DettProc[0];
  $DescrProc = $DettProc[1];
  $StatoProc = $DettProc[8];
  $PeriodCons = $DettProc[9];
  $IdTeam = $DettProc[10];
  switch ($StatoProc) {
    case 'A':
      $StWfs = "APERTO";
      break;
    case 'C':
      $StWfs = "CHIUSO";
      break;
    case 'S':
      $StWfs = "SALVATO";
      break;
  }
  $TipoProc = $DettProc[6];
  if ("$DettProc[5]" == "1") {
    $StWfs = "CONSOLIDATO";
  }
  if ($IdProc == $IdProcess) {
    $ProcDescr = $DettProc[1];
    $ProcEserEsame = $DettProc[2];
    $ProcMeseEsame = $DettProc[3];
    $ProcEserMese = $DettProc[4];
    $ProcFlag = $DettProc[5];
    $ProcTipo = $DettProc[6];
    $WfsRdOnly = $DettProc[7];
    $WfsStato = $DettProc[8];
    $LabelTipo = "";
    switch ($ProcTipo) {
      case 'R':
        $LabelTipo = "REST ";
        break;
      default:
        $LabelTipo = "";
        break;
    }
    $_SESSION['IdTeam'] = $IdTeam;
    $_SESSION['ProcAnno'] = $ProcEserEsame;
    if ($ProcMeseEsame < 10) {
      $_SESSION['ProcMese'] = '0' . $ProcMeseEsame;
    } else {
      $_SESSION['ProcMese'] = $ProcMeseEsame;
    }
  }
  $selected = ($IdProc == $IdProcess) ? "selected" : "";
  $option_process .= '<option value="' . $IdProc . '" ' . $selected . '>' . $LabelTipo . $StWfs . ': ' . $DescrProc . '</option>';
}  ?>


<div id="mostraEsito" hidden></div>
<FORM id="MainForm" method="POST" enctype="multipart/form-data">
  <div id="ListWfs2">
    <input type="hidden" name="LastTimeCoda" id="LastTimeCoda" value="">
    <input type="hidden" name="TopScroll" id="TopScroll" value="<?php echo $TopScroll; ?>">
    <input type="hidden" name="TopScrollDett" id="TopScrollDett" value="<?php echo $TopScrollDett; ?>">
    <input type="hidden" name="SelFlusso" id="SelFlusso" value="<?php echo $SelFlusso; ?>">
    <input type="hidden" name="SelNomeFlusso" id="SelNomeFlusso" value="<?php echo $SelNomeFlusso; ?>">
    <input type="hidden" name="Resetta" id="Resetta" value="">
    <input type="hidden" name="ResettaPer" id="ResettaPer" value="">
    <input type="hidden" name="NewDesc" id="NewDesc" value="<?php echo $NewDesc; ?>">

    <input type="hidden" name="IdFlu" id="IdFlu" value="<?php echo $IdFlu; ?>">
    <input type="hidden" name="Flusso" id="Flusso" value="<?php echo $Flusso; ?>">
    <input type="hidden" name="Action" id="Action" value="<?php echo $Action; ?>">
    <input type="hidden" name="OnIdLegame" id="OnIdLegame" value="<?php echo $OnIdLegame; ?>">
    <input type="hidden" name="SelDipendenza" id="SelDipendenza" value="<?php echo $SelDipendenza; ?>">
    <input type="hidden" name="SelNomeDipendenza" id="SelNomeDipendenza" value="<?php echo $SelNomeDipendenza; ?>">
    <input type="hidden" name="SelTipo" id="SelTipo" value="<?php echo $SelTipo; ?>">
    <input type="hidden" name="LinkPagina" id="LinkPagina" value="<?php echo $LinkPagina; ?>">
    <input type="hidden" name="WfsRdOnly" id="WfsRdOnly" value="<?php echo $isWFSRead; ?>">


    <input type="hidden" name="Tipo" id="Tipo" value="<?php echo $CodaTipo; ?>">
    <input type="hidden" name="IdDip" id="IdDip" value="<?php echo $CodaIdDip; ?>">

    <?php
    if ($IdWorkFlow != "" and $IdPeriod != "" && $IdProcess!= "") {
    ?>
      <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
        <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>
     <?php if(!$isWFSRead):?>
        <?php $imageAutoRun= $isAutorun?'play-button.png':'pause-button.png';?>
         <img title="Set AutoPlay" onclick="AutoPlay()" id="pauseAutoPlay" class="autoPlay" src="./images/<?php echo $imageAutoRun; ?>">  Workflow -
        
        <?php $imageAutoRun = 'Divieto.png';?>
        <?php $classdvieto= $isStop=='off' ?'enabled-image':'disabled-image';?>
         <img title="Scodatore <?= $isStop ?>" onclick="stopFlusso()" id="pauseAutoPlay" class="divieto <?= $classdvieto ?>" src="./images/<?php echo $imageAutoRun; ?>">
                
      <?php endif;?>  
        
        <span title="ID:<?=$IdWorkFlow?> - <?= ($WfsDescr != "")?$WfsDescr." ".$LabelProcTipo:"" ?>"><?=$WfsName?></span>
          <img src="./images/Diagram.png" title="Diagramma" onclick="OpenDiagram('<?php echo $IdWorkFlow; ?>','<?php echo $IdProcess; ?>','<?php echo $WfsName; ?>','<?php echo $ProcDescr; ?>')" style="width: 25px; margin: 10px; cursor:pointer;background: white;border: 1px solid;margin: 4px;">
          <?php
          if ($PeriodCons != 0 and $PeriodCons != "") {
            if ($ProcFlag == 1) {
          ?>- CONSOLIDATO
        <?php  } else { ?>
          - PERIODO GIA' CONSOLIDATO<?php
                                  }
                                } ?>


        </p>
        <table width="100%">
          <tr>
            <th><button id="refresh" onclick="RefreshPage();return false;" class="btn"><i class="fa-solid fa-refresh"> </i></button>

<button id="RCLegend2" onclick="printLegend();return false;" class="btn"><i class="fa-solid fa-circle-question"></i>

    
        
      </button>
              <div class="table_element">
                Descrizione: <input <?= ($isWFSRead)?"disabled":"" ?> type="text" name="DescrIdP" onkeyup="visualizzaPulsIdP('<?php echo $ProcDescr; ?>')" id="DescrIdP" value="<?php echo $ProcDescr; ?>">
                <button id="PulsIdP" title="Change Descr" onclick="SaveNewDescr()" hidden class="btn"><i class="fa-solid fa-pen-to-square"></i></button>
              </div>
              <?php
              foreach ($RegimeFlussiCount as $row) {
                $nrows = $row['CNT'];
              }
              if ($nrows > 0) {
              ?>

                <div class="table_element">
                  Raggruppamento: <select id="Regime" name="Regime" onchange="changeRegime()">
                    <option value="">Tutti</option>
                    <?php
                    foreach ($IdRegimeData as $row) {
                      $IdRegime = $row['ID_REGIME'];
                      $NameRegime = $row['REGIME'];
                    ?><option value="<?php echo $IdRegime; ?>" <?php if ("$Regime" == "$IdRegime") { ?> selected <?php } ?>><?php echo $NameRegime; ?></option><?php
                                                                                                                                                            }
                                                                                                                                                              ?>
                  </select>
                </div>
              <?php } ?>
            </th>

          </tr>



          <?php


          if ($IdWorkFlow != "" and $IdPeriod != "") {


          ?>
            <tr>
              <th>
                <?php
                if ("$IdProcess" != "") {
                ?>
                  <div class="table_element">
                    <?php
                    if ( "${PeriodCons}" != "0" ) {
                        ?><img src="./images/Lock.png" width="30px"><?php
                    } else {
						if ($WfsRdOnly == "Y" ) { ?><img src="./images/ReadMode.png" width="60px"><?php }
                    }
				    ?>
                  </div>
                  <?php if ($WfsMulti == "Y" && $IdPeriod != "" && (!$isWFSRead)) { ?>
                    <div class="table_element">
                      <button id="AggiungiIdP2" onclick="AggiungiIdP();return false;" class="btn"><i title="Aggiungi IdProcess" class="fa-solid fa-plus"> </i></button>

                    </div>
                  <?php } ?>
                  <div class="table_element">
                    IdProcess: <input size="15" type="text" name="IdProcessV" id="IdProcessV" value="<?php echo $IdProcess; ?>" disabled>
                  </div>

                  <?php
                  if ($nrows > 0 || 1) {
                  ?>
                    <div class="table_element">
                      Anno: <input size="5" type="text" name="ProcEserEsameV" id="ProcEserEsameV" value="<?php echo $ProcEserEsame; ?>" disabled>
                    </div>
                    <div class="table_element">
                      Mese:
                      <input size="5" type="hidden" name="ProcMeseEsame" id="ProcMeseEsame" value="<?php echo $ProcMeseEsame; ?>" >
                       <input size="5" type="text" name="ProcMeseEsameV" id="ProcMeseEsameV" value="<?php echo $ProcMeseEsame; ?>" disabled>
                    </div>
                    <div class="table_element">
                      Tipo: <?php
                            if ("$ProcTipo" != "R" or $StatoProc != 'A') {
                              switch ($ProcTipo) {
                                case 'R':
                                  $TipoV = "Restatement ";
                                  break;
                                case 'S':
                                  $TipoV = "Sensibility ";
                                  break;
                                default:
                                  $TipoV = "Closing ";
                                  break;
                              } ?>
                        <input size="10" type="text" name="TipoV" id="TipoV" value="<?php echo $TipoV; ?>" disabled>
                      <?php     } else {
                      ?>
                        <select name="SelChSens" id="SelChSens" onchange="ChSens()">
                          <option value="S" <?php if ("$ProcTipo" == "S") { ?> selected <?php } ?>>Sensibility</option>
                          <option value="R" <?php if ("$ProcTipo" == "R") { ?> selected <?php } ?>>Restatement</option>
                        </select>
                      <?php
                            }
                      ?>
                    </div>
                    <div class="table_element">
                      <?php
                      switch ($WfsFreq) {
                        case 'M':
                          $Frequenza = "Mensile ";
                          break;
                        case 'Q':
                          $Frequenza =  "Trimestrale ";
                          break;
                        case 'A':
                          $Frequenza =  "Annuale ";
                          break;
                      }
                      ?>
                      Frequenza: <input size="10" type="text" name="FrequenzaV" id="FrequenzaV" value="<?php echo $Frequenza; ?>" disabled>
                    </div>
                     <?php if(!$isWFSRead):?>
                    <div class="table_element">
                      Stato: <?php
                              if ("$WfsStato" != "C") {
                              ?>
                        <select name="SelStatoWfs" id="SelStatoWfs" onchange="CambiaStato()">
                          <option value="A" <?php if ("$WfsStato" == "A") { ?> selected <?php } ?>>Aperto</option>
                          <option value="S" <?php if ("$WfsStato" == "S") { ?> selected <?php } ?>>Salvato</option>
                          <option value="C" <?php if ("$WfsStato" == "C") { ?> selected <?php } ?>>Chiuso</option>
                        </select>
                      <?php
                              } else { ?>
                        <input type="text" name="StatoC" id="StatoC" value="<?php echo "CHIUSO"; ?>" disabled>
                      <?php  } ?>
                    </div>
                  <?php  endif; ?>
                  <?php  } ?>
                <?php  } ?>


              </th>

            </tr>


          <?php } ?>
        </table>
  </div>

  </aside>
<?php  } ?>
<div id="ShowDiagram" style="position:fixed; width:90%; height:600px; left:0; right:0; top:0; bottom:0; margin:auto; z-index:9999999; background:white; overflow:auto; box-shadow: 0px 0px 10px 1px black;" hidden></div>

<div id="WorkFlowSelect">
  <label>WORKFLOW:</label>
  <select id="IdWorkFlow" name="IdWorkFlow" onchange="changeIdWorkFlow();return false;">
    <option value="">..</option>
    <?php
    foreach ($ArrWfs as $Wfs) {
      //ID_WORKFLOW,WORKFLOW,DESCR,READONLY
      $Id = $Wfs[0];
      $Name = $Wfs[1];
      $Descr = $Wfs[2];?>      
      <option value="<?php echo $Id; ?>" <?= ($Id == $IdWorkFlow)?"selected":"" ?>><?= $Name ?></option>
      <?php } ?>
  </select>
  <label>PERIODO:</label>

  <select id="IdPeriod" name="IdPeriod" onchange="changeIdPeriod();return false;">
    <option value="">..</option>
    <?php
    foreach ($ArrPeriod as $Prd) {
    ?><option value="<?php echo $Prd; ?>" <?php if ("$Prd" == "$IdPeriod") { ?>selected<?php } ?>><?php echo $Prd; ?></option><?php
                                                                                                                                      }
                                                                                                                                        ?>
  </select>
  <label>PROCESSO:</label>
  <select id="IdProcess" name="IdProcess" onchange="changeIdProcess();return false;">
    <option value="">..</option>
    <?php echo $option_process; ?>
  </select>

  <?php if ($IdProcess != "" and $IdPeriod != "") {  ?>
    <div id="ShowDettFlusso"  class="DettFlusso" hidden ></div>
</div>






<div id="AreaPreFlussi">
  <?php

    $arrayFlussi = [];
    if ("$IdWorkFlow" != "" and "$IdProcess" != "") {
      $OldLiv = "";
      $OldIdFlu = -1;
      foreach ($ArrLegami as $Legame1) {
        //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
        $Liv = $Legame1[1];
        if ("$Liv" != "$OldLiv") {
  ?>
        <div class="ACapo" style="display:block;"></div>
        <div id="Liv<?php echo $Liv; ?>" class="Livello">
          <div class="TitoloLiv"><B>LIVELLO <?php echo $Liv; ?></B></div>
          <?php
          $OldLiv = $Liv;
          foreach ($ArrLegami as $Legame) {
            //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
            if ($Liv == $Legame[1]) {
              $IdFlu = $Legame[2];
              if ($IdFlu != $OldIdFlu) {
                $OldIdFlu = $IdFlu;
                foreach ($ArrFlussi as $Flusso) {

                  //ID_FLU,FLUSSO,DESCR
                  $IdFlusso = $Flusso[0];
                  /*    if ( $IdFlusso == $IdFlu ){ 
                            $arrayFlussi[]= $IdFlusso;
                            ?>
                            <div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="OpenStatusFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>)" style="position: relative; float: left;" class="StatusFlusso" ></div>
                            <script>

                            MostraFlusso(<?php echo $IdWorkFlow; ?>, <?php echo $IdProcess; ?>, <?php echo $IdFlusso; ?>, <?php echo $ProcMeseEsame; ?>)
                                                         
                              $('#StatusFlusso<?php echo $IdFlusso; ?>').click(function(){
                                $('.Flusso').each(function(){$(this).removeClass('ingrandFlu');});
                              });   
                            </script>
                            <?php
                         }*/
                  if ($IdFlusso == $IdFlu) {
                    $arrayFlussi[] = $IdFlusso;
          ?>
                    <div id="StatusFlusso<?php echo $IdFlusso; ?>" onclick="OpenStatusFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $IdFlusso; ?>,'<?php echo $ProcMeseEsame; ?>',2)" style="position: relative; float: left;" class="StatusFlusso"></div>
                    <?php
                    $this->Flusso($IdWorkFlow, $IdProcess, $IdFlusso, $ProcMeseEsame);
                    ?>
                    <script>
                      //   OpenStatusFlusso(<?php echo $IdWorkFlow; ?>, <?php echo $IdProcess; ?>, <?php echo $IdFlusso; ?>, '<?php echo $ProcMeseEsame; ?>');
                    </script>
          <?php
                  }
                }
              }
            }
          }
          ?>
        </div><?php
            }
          }
        }



              ?>
</div>

<div id="EsitoUpload" hidden></div>

<div id="pageEsito"></div>
<button id="PulMostraCoda" onclick="MostraCoda(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>);return false;" class="btn" hidden style="display: inline-block;">
  <img id="ElabInCodaLoad" class="ImgIco" src="./images/Loading.gif" hidden style="display: inline;">
  <img id="ElabInCodaWait" class="ImgIco" hidden src="./images/Sveglia.png">
  <span id="FraseCoda"></span>
</button>

<button id="PulMostraStorico" onclick="MostraStorico(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>);return false;" class="btn" style="display: inline-block;">
  STORICO LANCI
</button>
<button id="PulMostraParametri" onclick="MostraParametri(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>);return false;" class="btn" style="display: inline-block;">
  PARAMETRI
</button>
<div id="RefreshCoda"></div>
<div id="MostraCoda" hidden></div>
<div id="MostraStorico" hidden></div>
<div id="MostraParametri" hidden></div>
<div id="OpenLinkPage" hidden></div>
<?php } ?>
</FORM>
<div id="countdown"></div>
<?php

//===============================================================================================
//==========   SCRIPT

if ($LinkPagina != "") {

?>
  <script>
    OpenLinkPage('<?php echo $IdWorkFlow; ?>', <?php echo json_encode($_POST); ?>);
  </script>
<?php

}
?>