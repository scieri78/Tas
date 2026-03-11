<div id="ListShell<?php echo $InRetePasso; ?>" class="ListShell" ><ul style="display: block;"><?php
    //sostutuzione query principale
    $IdShOld="";
    $IdRunShOld="";
    $OLDIdSql="";
    $CntR=0;
    $CntS=0;
    $ShCntPass=0;
    $FirstIdRunSh="";
    $AllPage=array();
	foreach($DatiListShell as $row){
  //  while ($row = db2_fetch_assoc($stmt)) {
      $IdSh=$row['ID_SH'];
      $IdRunSh=$row['ID_RUN_SH'];
      $ShName=$row['NAME'];
      $ShSTART_TIME=$row['START_TIME'];
      $ShEND_TIME=$row['END_TIME'];
      $ShFather=$row['ID_RUN_SH_FATHER'];
      $ShLog=$row['LOG'];
      $ShStatus=$row['STATUS'];
      $ShUser=$row['USERNAME'];
      $ShDebugSh=$row['DEBUG_SH'];
      $ShDebugDb=$row['DEBUG_DB'];
      $ShMail=$row['MAIL'];
      $ShEserMese=$row['ESER_MESE'];
      $ShEserEsame=$row['ESER_ESAME'];
      $ShMeseEsame=$row['MESE_ESAME'];
      $ShSecDiff=$row['SH_SEC_DIFF'];
      $ShShellPath=$row['SHELL_PATH'];
      $ShVariables=$row['VARIABLES'];
      $ShSons=$row['N_SON'];
      $ShRc=$row['RC'];
      $ShMessage=$row['MESSAGE'];
      $ShMessage=str_replace("$ShShellPath/$ShName:",'',$ShMessage);
      $ShMessage=str_replace("'",'',$ShMessage);
      $ShLastSecDiff=$row['LASTSH_SEC_DIFF'];
      $ShTags=$row['TAGS'];
      $ShPrwEnd=$row['PREVIEW_SH_END'];
      $ShCntPass=$row['CNTPASS'];
      $IdSql=$row['ID_RUN_SQL'];
      $WaitTime=$row['WAITTIME'];
      
      if ( $FirstIdRunSh == "" &&  $datishell->SelNumPage == "1" ){
        $FirstIdRunSh=$IdRunSh;
      }

      $Test="${IdSh}${ShVariables}$IdRunSh";
      if ( $datishell->SpliVar != "SpliVar" ){  
         $Test="${IdSh}${ShTags}$IdRunSh";
      }
      if ( $IdShOld != $Test ){
         $CntR=$CntR+1;
         $CntS=0;
         $NumPage=ceil($CntR/10);
         
         if ( ! in_array($NumPage,$AllPage)){
           array_push($AllPage,$NumPage);
         }  
        
         
      } else {
        $CntS=$CntS+1;
        
      }
	  
	    if ( $NumPage != $OldNumPage ){
        $IdShOld="";
        $OldNumPage=$NumPage;
      } 
	  
	    if ( ( $NumPage == $datishell->SelNumPage || $datishell->LastShellRun == "LastShellRun" || $datishell->DA_RETITWS != "" ) 
		  and ( $CntS < $datishell->Soglia or $datishell->SelShTarget != "" )){
      
        $ShOldDiff="";
        if ( $ShLastSecDiff != "" ){
           $ShOldDiff=gmdate('H:i:s', $ShLastSecDiff);
		   $ShOldDiff=floor(($ShLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $ShLastSecDiff);
        }
                                    
        $ShDiff=gmdate('H:i:s', $ShSecDiff);
		$ShDiff=floor(($ShSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $ShSecDiff);

        $Esito="";
        switch($ShStatus){
           case 'E': $Esito="Err"; break;
           case 'I': $Esito="Run"; break;
           case 'F': $Esito="Com"; break;
           case 'W': $Esito="War"; break;
           case 'M': $Esito="Frz"; break;
        }
        
        $Test="${IdSh}${ShVariables}$IdRunSh";
        if ( "$SpliVar" != "SpliVar" ){  
           $Test="${IdSh}${ShTags}$IdRunSh";
        }
	if ( $IdSql != "" ){
            $SqlType=$row['TYPE_RUN'];
            $SqlStep=$row['STEP'];
            $SqlFile=$row['FILE_SQL'];
            $SqlInFile=$row['FILE_IN'];
            $SqlSTART_TIME=$row['SQL_START'];
            $SqlEND_TIME=$row['SQL_END'];
            $SqlStatus=$row['SQL_STATUS'];
            $SqlSecDiff=$row['SQL_SEC_DIFF'];
            $SqlLastSecDiff=$row['LASTSQL_SEC_DIFF'];
            $SqlPrwEnd=$row['PREVIEW_SQL_END'];
            $SqlUseRoutine=$row['USE_ROUTINE'];
            #$SqlDiff="";
            #if ( "$SqlEND_TIME" != "" ){
               $SqlDiff=gmdate('H:i:s', $SqlSecDiff);
			   $SqlDiff=floor(($SqlSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SqlSecDiff);
            #}
            
            $SqlOldDiff="";
            if ( "$SqlLastSecDiff" != "" ){
               $SqlOldDiff=gmdate('H:i:s', $SqlLastSecDiff);
			   $SqlOldDiff=floor(($SqlLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SqlLastSecDiff);
            }           
          
            $SqlEsito="";
            switch($SqlStatus){
               case 'E': $SqlEsito="Err"; break;
               case 'I': $SqlEsito="Run"; break;
               case 'F': $SqlEsito="Com"; break;
               case 'W': $SqlEsito="War"; break;
               case 'M': $SqlEsito="Frz"; break;
            }	
		
		
		}
		  $FIdSh=$row['F_ID_SH'];
 
        if ( $FIdSh != "" ){
          
            $FIdRunSh=$row['F_ID_RUN_SH'];
            $FShName=$row['F_NAME'];
            $FShSTART_TIME=$row['F_START_TIME'];
            $FShEND_TIME=$row['F_END_TIME'];
            $FShFather=$row['F_ID_RUN_SH_FATHER'];
            $FShLog=$row['F_LOG'];
            $FShStatus=$row['F_STATUS'];
            $FShUser=$row['F_USERNAME'];
            $FShDebugSh=$row['F_DEBUG_SH'];
            $FShDebugDb=$row['F_DEBUG_DB'];
            $FShMail=$row['F_MAIL'];
            $FShEserMese=$row['F_ESER_MESE'];
            $FShEserEsame=$row['F_ESER_ESAME'];
            $FShMeseEsame=$row['F_MESE_ESAME'];
            $FShSecDiff=$row['F_SH_SEC_DIFF'];
            $FShShellPath=$row['F_SHELL_PATH'];
            $FShVariables=$row['F_VARIABLES'];
            $FShSons=$row['F_N_SON'];
            $FShRc=$row['F_RC'];
            $FShMessage=$row['F_MESSAGE'];
            $FShMessage=str_replace("$FShShellPath/$FShName:",'',$FShMessage);
            $FShLastSecDiff=$row['F_LASTSH_SEC_DIFF'];
            $FShTags=$row['F_TAGS'];
            $FShPrwEnd=$row['F_PREVIEW_SH_END'];
            $FShCntPass=$row['F_CNTPASS'];
            $FIdSql=$row['F_ID_RUN_SQL'];
            $FShDiff=gmdate('H:i:s', $FShSecDiff);
			$FShDiff=floor(($FShSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $FShSecDiff);
            
            $FEsito="";
            switch($FShStatus){
                case 'E': $FEsito="Err"; break;
                case 'I': $FEsito="Run"; break;
                case 'F': $FEsito="Com"; break;
                case 'W': $FEsito="War"; break;
                case 'M': $FEsito="Frz"; break;
            }
           
            $SelClsSh="";
            if( $FIdRunSh == $SelShell ){$SelClsSh="ClsRoot";}
		}
	}
     
      if ( ( $NumPage == $datishell->SelNumPage || $datishell->LastShellRun == "LastShellRun" || $datishell->DA_RETITWS != "" ) 
		  and ( $CntS < $datishell->Soglia or $datishell->SelShTarget != "" )){
      
        
		
	  if ($IdShOld == $Test && $CntS == $datishell->Soglia  && $datishell->SelShTarget == "" && $NumPage == $datishell->SelNumPage ){
            ?>
            <li class="has-sub" title="<?php echo $ShName; ?>" >
               <a>
               <table class="ExcelTable 10" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;height: 30px !important;" >
                    <tr>
                        <td ><B onclick="OpenShSel(<?php echo $IdRunSh; ?>);" style="color:red;border: 1px solid red;" >Visualizzazione Step interrotta ( clicca per visualizzare a parte )</B></td>
                    </tr>
                </table>        
                </a>
            </li>
            <?php
        }
	  
	  
	  
    
        
        if ( $IdShOld != $Test ){
            if ( $IdShOld != "" ){
                
                ?></ul></li><?php
            }
            
            $IdShOld="${IdSh}${ShVariables}$IdRunSh";
            if ( $datishell->SpliVar != "SpliVar" ){  
               $IdShOld="${IdSh}${ShTags}$IdRunSh";
            }
            
            
            ?>
            <li>
                <?php if ( $ShSons != 0 || $IdSql != ""  ){   ?><label class="Esplodi" >o</label><?php } ?>           
					<?php include "./view/statoshell/elementoTabella.php";?>
                <ul style="display:<?php if ( ( "$ShStatus" == "I"  and "$Sel_Esito" != "I" ) or in_array($IdRunSh, $ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                <?php 
        }

      
            
        if ( $IdSql != "" ){
          
          
            ?>
            <li id="List_<?php echo $IdRunSh."_".$IdSql; ?>" class="has-sub">
                <?php if  ( $SqlType == "PLSQL" and $SqlUseRoutine != "0" ){?>
					<label class="Esplodi">o</label>
			  <?php } ?> 
              <?php include "./view/statoshell/elementoTabella2.php";?>
                <?php
                if ( ( $SqlType == "PLSQL" && $SqlUseRoutine != "0" ) || ( $SSqlStatus == "I" ) ){
                    SearchFatherRoutine($conn, $ListIdOpen,$IdSql,$IdRunSh,$SSqlStatus,$_modelshell);         
                }
                ?>
            </li>
            <?php
                        
        } 
        $StepType=$row['TYPE_RUN'];
        if ( $StepType == "STEP" ){
            $StepName=$row['STEP'];
            $StepTime=$row['SQL_START'];
       
	   
	   if ( substr($StepName,1,10 ) == "Eseguo Shell in USER :" ){
			$DatiShellUser = $_modelshell->getShellUser($IdRunSh);
               foreach ($DatiShellUser as $row5) {
                   $TIdRunSh=$row5['ID_RUN_SH'];
				   $TCnt=$TCnt+1;
			       ?>
			       <li class="has-sub" id="Open<?php echo $TIdRunSh."_".$TCnt; ?>" ></li>
				   <script>
				      $( "#Open<?php echo $TIdRunSh."_".$TCnt; ?>" ).load('./PHP/StatoShell_USER.php?IDSELEM=<?php echo $TIdRunSh; ?>').show();
				   </script>
			       <?php
			   }
			}else{

            ?>
            <li class="has-sub">
               <?php include "./view/statoshell/elementoTabella3.php";?>
            </li>
            <?php
			}
        }
        
      
      
        $FIdSh=$row['F_ID_SH'];
 
        if ( $FIdSh != "" ){  
            ?>
            <li class="<?php echo $SelClsSh; ?>" >
                <?php if ( $FShSons != "0" || $FIdSql != ""){   ?><label class="Esplodi" >o</label><?php } ?>           
             <?php include "./view/statoshell/elementoTabella4.php";?>
                <ul style="display:
				<?php if ( $FShStatus == "I" or in_array($FIdRunSh, $ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                  <?php 
				  RecSonsh($conn,$FIdSh,$FIdRunSh,'N',$datishell,$_modelshell); ?>
                </ul>
            </li>
            <?php
        }         
        $InGiro=1;      
      } else {        
            if ( $InGiro == "1" ){
              if ( $IdShOld != $Test ){
                ?></ul></li><?php
              }
              $InGiro=0;
            }
            $IdShOld="${IdSh}${ShVariables}$IdRunSh";
            if ( $SpliVar != "SpliVar" ){  
               $IdShOld="${IdSh}${ShTags}$IdRunSh";
            }
      }
   } 
    

    ?>
	
