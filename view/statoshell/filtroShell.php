

<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Processing <?php 
    $aSITO =$_COOKIE[$_COOKIE['tab_id']];
		//$db_name=($db_name)?$db_name:$_SESSION['aSITO'];
		$db_name=($db_name)?$db_name:$aSITO;
    $db_name = $_GET['sito']?$_GET['sito']:$_POST["db_name"]; 
     echo strtoupper( $db_name);?></p>
<FORM id="FormEserEsame" method="POST" onsubmit="NumLastNoSubmit()">

<?php 
$classHide ="";
if ($datishell->SelShTarget != "") {
  $classHide = "hidden";
  }
  if ($datishell->SelShTarget != "") {

    $datishell->AutoRefresh2 = $_POST['AutoRefresh2'];

    if ($datishell->DA_RETITWS == "") {
    ?>

      <div id="divFiltroShell ">
        <button id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> </button>

        <input type="checkbox" id="AutoRefresh2" name="AutoRefresh2" value="AutoRefresh" <?php if ($datishell->AutoRefresh2 == "AutoRefresh") {
                                                                                            echo "checked";
                                                                                          } ?>><label for="AutoRefresh2">Auto Refresh</label>
        <input type="checkbox" id="PLSSHOWDETT" name="PLSSHOWDETT" value="PLSSHOWDETT" <?php if ($datishell->PLSSHOWDETT  == "PLSSHOWDETT") {
                                                                                          echo "checked";
                                                                                        } ?>><label for="AutoRefresh2">Show Dett</label>
	
      </div>
    <?php
    }
  }

  if ($datishell->INPASSO == "") { ?>

    
    <?php }  ?>

    <div id="divFiltroShell" <?php echo $datishell->ISIDERR; ?>>
      <div class="divFilterRight" <?php echo $datishell->ISIDERR; ?>>
        <?php
        $classView = ($_POST['viewfilter'] == 'Si') ? 'divshow' : 'divHiden';
        $classEye = ($_POST['viewfilter'] == 'Si') ? 'fa-eye-slash' : 'fa-eye';
        $viewFilterH = ($_POST['viewfilter'] == 'Si') ? 'Si' : 'No';
        ?>
        <span onclick="Refresh()" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> </span>
        <span onclick="resetSession()" id="resetSessionbtn" class="btn"><i class="fa-solid fa-eraser"></i> </span>
        <span  id="viewLast5" class="btn"><i onclick="viewLastRunx()" class="fa-solid fa-table-list"></i> </span  >
        <span value="No" name="viewfilter" id="viewfilter" onclick="viewFilter()" class="btn"><i id="" class="fa-solid <?php echo $classEye; ?>"></i> </span>
        <span id="RCLegend2" onclick="apriLegend()" class="btn"><i class="fa-solid fa-circle-question"></i></button>

        <input type="hidden" id="TopScrollShell" name="TopScrollShell" value="<?php echo $datishell->TopScrollShell; ?>" />
        <input type="hidden" id="LeftScrollShell" name="LeftScrollShell" value="<?php echo $datishell->LeftScrollShell; ?>" />
        <input type="hidden" id="viewFilterH" name="viewfilter" value="<?php echo $viewFilterH; ?>" />
        <input type="hidden" id="RCLegendH" name="RCLegendH" value="0" />
        <input type="hidden" id="resetSession" name="resetSession" value="<?php echo $viewFilterH; ?>"  />
        <input type="hidden" id="DARETI" name="DARETI" value="<?php echo $DARETI; ?>"  />
        
      </div>
      <div class="divFilter">
        <input type="checkbox" class="ReloadForm" id="AutoRefresh" name="AutoRefresh" value="AutoRefresh" <?php if ($datishell->AutoRefresh == "AutoRefresh") {
                                                                                                            echo "checked";
                                                                                                          } ?>><label for="AutoRefresh">Auto Refresh</label>
                                                                                                          
        <input type="checkbox" class="" id="PLSSHOWDETT" name="PLSSHOWDETT" value="PLSSHOWDETT" 
          <?php if ($datishell->PLSSHOWDETT == "PLSSHOWDETT") { echo "checked"; } ?>><label for="PLSSHOWDETT">Show Dett</label>
             
        <input onchange="$('#FormEserEsame').submit()" type="checkbox" id="NoTags" name="NoTags" value="NoTags" 
        <?php if ($datishell->NoTags  == "NoTags") {  echo "checked";  } ?>>
        <label for="AutoRefresh2">NoTags</label>	

      
        </div>

      </div>
      <div class="divFilters">
        <div class="divFilter" <?php echo $classHide; ?>>
          <label for="SelMeseElab">MESE ELAB.</label>

          <SELECT class="inputFilter selectSearch" name="SelMeseElab" id="SelMeseElab" onchange="$('#SelShelT').val('');$('#SelLastMeseElab').val(''); $('#SelNumPage').val('1');$('#NumLast').val('10');$('#FormEserEsame').submit();">
            <option value="%" <?php if ($datishell->SelMeseElab == "%") {
                                echo "selected";
                              } ?>>All</option>
            <?php
            /* $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB FROM WORK_CORE.CORE_SH ORDER BY MESEELAB DESC";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }*/

            $SelMeseNow = "";
            foreach ($DatiSelMeseElab as $row) {
              $MeseElab = $row['MESEELAB'];
              $selected = ($datishell->SelMeseElab == $MeseElab) ? "selected" : "";
              echo '<option value="' . $MeseElab . '" ' . $selected . '>' . $MeseElab . '</option>';
            }
            ?>
          </SELECT>
        </div>
        <div class="divFilter">
          <label for="SelLastMeseElab">MESE DIFF</label>

          <SELECT class="inputFilter selectSearch" name="SelLastMeseElab" id="SelLastMeseElab" onchange="$('#SelShelT').val('');$('#FormEserEsame').submit()">
            <option value="0" >NoDiff</option>		  
            <?php

            foreach ($DatiSelLastMeseElab as $row) {
              $MeseElab = $row['MESEELAB'];
              $selected = ($datishell->SelLastMeseElab == $MeseElab) ? "selected" : "";
              if ($datishell->SelLastMeseElab == "") {
                $datishell->SelLastMeseElab = $row['OLDRUN'];
              }
              echo '<option value="' . $MeseElab . '" ' . $selected . '>' . $MeseElab . '</option>';
            }
            ?></SELECT>
        </div>
        <div class="divFilter" <?php echo $classHide; ?>>
          <label for="SelEserMese">SHELL</label>
            <input type="hidden" id="SelShelT" name="SelShelT" value="<?php echo $datishell->SelShell; ?>"/>
          <SELECT class="inputFilterSelect selectSearch" name="SelShell" id="SelShell" onchange="$('#SelShelT').val('');selectSelShell()">
            <option value="" <?php if ($datishell->SelShell == "") {
                                echo 'selected';
                              } ?>>All</option>
            <optgroup label="Shell Father">
              <?php


              foreach ($DatiSelShellFather as $row) {
                $SID_SH = $row['ID_SH'];
                $SSHELL = $row['SHELL'];
                $STAGS = $row['TAGS'];
                $SSHELLPATH = $row['SHELL_PATH'];
                $SID_RUN_SH = $row['ID_RUN_SH'];
                $IdRunShRoot = $row['ID_RUN_SH_ROOT'];
                $selected = ($datishell->SelShell == $SID_RUN_SH) ? "selected" : "";
                echo '<option value="' . $SID_RUN_SH . '" ' . $selected . '>' . $SSHELL . ' ' . $STAGS . ' [' . $SSHELLPATH . ']</option>';
              }

              ?>
            </optgroup>
            <optgroup label="Shell Sons">
              <?php
              foreach ($DatiSelShellSons as $row) {
                $SID_SH = $row['ID_SH'];
                $SSHELL = $row['SHELL'];
                $STAGS = $row['TAGS'];
                $SSHELLPATH = $row['SHELL_PATH'];
                $SID_RUN_SH = $row['ID_RUN_SH'];
                $IdRunShRoot = $row['ID_RUN_SH_ROOT'];
                $selected = ($datishell->SelShell == $SID_RUN_SH) ? "selected" : "";
                echo '<option value="' . $SID_RUN_SH . '" ' . $selected . '>' . $SSHELL . ' ' . $STAGS . ' [' . $SSHELLPATH . ']</option>';
              }

              ?>
            </optgroup>
          </SELECT>
        </div>
        <div class="divFilter" <?php echo $classHide; ?>>
          <label for="SelInDate">DAY</label>

          <SELECT class="inputFilter selectSearch" name="SelInDate" id="SelInDate" onchange="$('#SelShelT').val('');selectSelInDate()">
            <option value="<?= statoshell_dati::LAST_DAYS ?>" <?php if ($datishell->SelInDate == statoshell_dati::LAST_DAYS) {
                                  echo "selected";
                                } ?>>Last days</option>		  
            <option value="<?= statoshell_dati::LAST_3_DAYS ?>" <?php if ($datishell->SelInDate == statoshell_dati::LAST_3_DAYS) {
                                  echo "selected";
                                } ?>>Last 3 days</option>
            <option value="<?= statoshell_dati::ALL_DAY ?>" <?php if ($datishell->SelInDate == statoshell_dati::ALL_DAY) {
                                echo "selected";
                              } ?>>All</option>
            <?php

            foreach ($DatiSelInDate as $row) {
              $day = $row['DD'];
              $selected = ($datishell->SelInDate == $day) ? "selected" : "";
              echo '<option value="' . $day . '" ' . $selected . ' >' . $day . '</option>';
            }
            ?>
          </SELECT>
          <label for="NumLast">LAST</label>
          <input onblur="NumLastSubmit()" class="inputFilter" id="NumLast" name="NumLast" value="<?php echo $datishell->NumLast; ?>">
        </div>
      </div>
      <div class="<?php echo $classView; ?>">
        <div class="divFilter" <?php echo $classHide; ?>>
          <label for="Sel_Esito">ESITO</label>

          <SELECT class="inputFilter selectSearch" name="Sel_Esito" onchange="$('#SelShelT').val('');$('#SelNumPage').val('1'); $('#FormEserEsame').submit()">
            <option value="" <?php if ($datishell->Sel_Esito == "") {
                                echo "selected";
                              } ?>>All</option>
            <option value="I" <?php if ($datishell->Sel_Esito == "I") {
                                echo "selected";
                              } ?>>In Corso</option>
            <option value="E" <?php if ($datishell->Sel_Esito == "E") {
                                echo "selected";
                              } ?>>Errore</option>
            <option value="W" <?php if ($datishell->Sel_Esito == "W") {
                                echo "selected";
                              } ?>>Warning</option>
            <option value="F" <?php if ($datishell->Sel_Esito == "F") {
                                echo "selected";
                              } ?>>Corretto</option>
            <option value="M" <?php if ($datishell->Sel_Esito == "M") {
                                echo "selected";
                              } ?>>Manual</option>
          </SELECT>
        </div>
        <div class="divFilter" <?php echo $classHide; ?>>
          <label for="SelEserMese">ESER MESE</label>
          <SELECT class="inputFilter selectSearch" name="SelEserMese" id="SelEserMese" onchange="$('#SelShelT').val('');$('#SelNumPage').val('1'); $('#FormEserEsame').submit()">
            <option value="" <?php if ("$SelEserMese" == "") { ?>selected<?php } ?>>All</option>
            <?php
            foreach ($DatiSelEserMese as $row) {
              $EserMese = $row['ESER_MESE'];
              $selected = ($datishell->SelEserMese == $EserMese) ? "selected" : "";
              echo '<option value="' . $EserMese . '" ' . $selected . '>' . $EserMese . '</option>';
            }
            ?>
          </SELECT>
        </div>
        
        <div class="divFilter"  <?php echo $classHide; ?>>
          <?php
          if ($datishell->DB2database == "TASPCUSR" and $datishell->SelMeseElab != "") {
          ?>
            <label <?php echo $classHide; ?>  for="SelIdProc">PROCESS</label>
 
            <SELECT <?php echo $classHide; ?> class="inputFilter selectSearch inputFilterSelect" id="SelIdProc" name="Sel_Id_Proc" onchange="$('#SelShelT').val('');selectSelIdProc()">
              <option value="" <?php if ($datishell->Sel_Id_Proc == "") {
                                  echo 'selected';
                                } ?>>All</option>
              <option value="b" <?php if ($datishell->Sel_Id_Proc == "b") {
                                  echo 'selected';
                                } ?>>Batch Run</option>
              <?php


              foreach ($DatiSelIdProc as $row) {
                $Id_Proc = $row['ID_PROCESS'];
                $Descr = $row['DESCR'];
                $Tipo = $row['TIPO'];
                $Stato = $row['FLAG_STATO'];
                $IDPTeam = $row['TEAM'];

                switch ($Tipo) {
                  case 'Q':
                    $LabelTipo = "Quarter";
                    break;;
                  case 'M':
                    $LabelTipo = "Mensile";
                    break;;
                  case 'R':
                    $LabelTipo = "Restatemnt";
                    break;;
                }
                switch ($Stato) {
                  case 'C':
                    $LabelStato = "Chiuso";
                    break;;
                  case 'A':
                    $LabelStato = "Aperto";
                    break;;
                  case 'S':
                    $LabelStato = "Sospeso";
                    break;;
                  case 'D':
                    $LabelStato = "Cancellato";
                    break;;
                }
                $selected = ($datishell->Sel_Id_Proc == $Id_Proc) ? "selected" : "";
                echo '<option value="' . $Id_Proc . '" ' . $selected . '>' . $Id_Proc . ' ' . $IDPTeam . ' ' . $Descr . '(' . $LabelTipo . ' ' . $LabelStato . ')</option>';
              }
              ?>
            </SELECT>
        </div>

         <?php } ?>

           <div class="divFilter"  <?php echo $classHide; ?>>         
            <label <?php echo $classHide; ?>  for="SelIdProc">Ambito</label> 
            <SELECT multiple="multiple" style="width: 300px;" <?php echo $classHide; ?> class="inputFilter selectSearch inputFilterSelect" id="SelAmbito" name="SelAmbito[]"> 
               <?php
              //  $DatiAmbiti = [['ambito'=>'LOSS_RESERVING'],['ambito'=>'Test'],['ambito'=>'POPOLA_3LIV'],['ambito'=>'MVBS']];
                foreach ($DatiAmbiti as $row) {
                        $Ambito = $row['AMBITO'];
                        $selected = in_array($Ambito, $_POST['SelAmbito']) ? 'selected' : '';
                        echo '<option value="' . $Ambito . '" ' . $selected . '>' . $Ambito . '</option>';
                    }
              ?>
            </SELECT>

            <script>
              $(".divHiden").hide();
              $(document).ready(function() {
                  $('#SelAmbito').select2({
                      placeholder: "Seleziona Ambito",
                      allowClear: true
                  });
                  $(".divHiden").show();
              });
          </script>
            <button id="filter" class="btn"><i class="fa-solid fa-filter"> </i> </button>
      </div>


       

     




      </div>
    </div>
 




<input type="hidden" id="ListOpenId<?php echo $datishell->InRetePasso; ?>" name="ListOpenId<?php echo $datishell->InRetePasso; ?>" value="<?php echo $datishell->ListOpenId; ?>" />
<input type="hidden" id="SelNumPage" name="SelNumPage" value="<?php echo $datishell->SelNumPage; ?>" />
<input type="hidden" id="PreIdRun" name="PreIdRun" value="<?php echo $datishell->PreIdRun; ?>" />

</aside>
  </FORM>