<?php 


    function RecSonsh($conn,$IdSh,$IdRunSh,$Opentutti,$datishell,$_modelshell){ 
     
	    $Opentutti='Y';
		$DatiRecSonsh = $_modelshell->getRecSonsh($IdSh,$IdRunSh,$Opentutti,$datishell);
        $Find=0;
        $CntR=0;
        
        foreach ($DatiRecSonsh as $row2) {
       
            $SIdSql=$row2['ID_RUN_SQL'];
            if ( $SIdSql != "" ){
                $SSqlType=$row2['TYPE_RUN'];
                $SSqlStep=$row2['STEP'];
                $SSqlFile=$row2['FILE_SQL'];
                $SSqlInFile=$row2['FILE_IN'];
                $SSqlSTART_TIME=$row2['SQL_START'];
                $SSqlEND_TIME=$row2['SQL_END'];
                $SSqlStatus=$row2['SQL_STATUS'];
                $SSqlSecDiff=$row2['SQL_SEC_DIFF'];
                $SSqlLastSecDiff=$row2['LASTSQL_SEC_DIFF'];
                $SSqlPrwEnd=$row2['PREVIEW_SQL_END'];
                $SSqlUseRoutine=$row2['USE_ROUTINE'];
                $SSqlDiff=gmdate('H:i:s', $SSqlSecDiff);
				$SSqlDiff=floor(($SSqlSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SSqlSecDiff);
                $SSqlOldDiff="";
                if ($SSqlLastSecDiff != "" ){
			       $SSqlOldDiff=floor(($SSqlLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SSqlLastSecDiff);
                }
                $SSqlEsito="";
                switch($SSqlStatus){
                   case 'E': $SSqlEsito="Err"; break;
                   case 'I': $SSqlEsito="Run"; break;
                   case 'F': $SSqlEsito="Com"; break;
                   case 'W': $SSqlEsito="War"; break;
                   case 'M': $SSqlEsito="Frz"; break;
                }
				$titoloSH = str_replace('.','. ',$SSqlStep);
                include "./view/statoshell/elementoTabella7.php"; 
          }
          $SStepType=$row2['TYPE_RUN'];
          if ( $SStepType == "STEP" ){
            $SStepName=$row2['STEP'];
            $SStepTime=$row2['SQL_START'];
            include "./view/statoshell/elementoTabella8.php";
          }
        
        
        
            $FIdSh=$row2['F_ID_SH'];
     
            if ( $FIdSh != "" ){
     
                $FIdRunSh=$row2['F_ID_RUN_SH'];
                $FShName=$row2['F_NAME'];
                $FShSTART_TIME=$row2['F_START_TIME'];
                $FShEND_TIME=$row2['F_END_TIME'];
                $FShFather=$row2['F_ID_RUN_SH_FATHER'];
                $FShLog=$row2['F_LOG'];
                $FShStatus=$row2['F_STATUS'];
                $FShUser=$row2['F_USERNAME'];
                $FShDebugSh=$row2['F_DEBUG_SH'];
                $FShDebugDb=$row2['F_DEBUG_DB'];
                $FShMail=$row2['F_MAIL'];
                $FShEserMese=$row2['F_ESER_MESE'];
                $FShEserEsame=$row2['F_ESER_ESAME'];
                $FShMeseEsame=$row2['F_MESE_ESAME'];
                $FShSecDiff=$row2['F_SH_SEC_DIFF'];
                $FShShellPath=$row2['F_SHELL_PATH'];
                $FShVariables=$row2['F_VARIABLES'];
                $FShSons=$row2['F_N_SON'];
                $FShRc=$row2['F_RC'];
                $FShMessage=$row2['F_MESSAGE'];
                $FShMessage=str_replace("$FShShellPath/$FShName:",'',$FShMessage);
                $FShLastSecDiff=$row2['F_LASTSH_SEC_DIFF'];
				$FShOldDiff="";
                if ($FShLastSecDiff != "" ){
			      $FShOldDiff=floor(($FShLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $FShLastSecDiff);
				}
				$FShTags=$row2['F_TAGS'];
                $FShPrwEnd=$row2['F_PREVIEW_SH_END'];
                $FShCntPass=$row2['F_CNTPASS'];
                $FIdSql=$row2['F_ID_RUN_SQL'];
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
             //  echo ("xx".$FIdRunSh);
               if( $FIdRunSh == $datishell->SelShell ){$SelClsSh="ClsRoot";}
               
               include "./view/statoshell/elementoTabella9.php";
            }           
        }      
    }
	
	 function SearchSonRoutine($conn, $ListIdOpen,$IdRunSql,$IdRunSh,$RtFather,$Status,$_modelshell){
		
		$DatiSearchSonRoutine = $_modelshell->getSearchSonRoutine($IdRunSql,$RtFather);
        if ( in_array($IdRunSh.'_'.$IdRunSql, $ListIdOpen) or $Status == "I"  ) { $uldisplay = "block"; } else { $uldisplay = "none"; }
        echo '<ul class="SearchSonRoutine"  style="display:'.$uldisplay.';">';		
        foreach ($DatiSearchSonRoutine as $rowR) {
            $Rt_SCHEMA=$rowR['SCHEMA'];
            $Rt_PACKAGE=$rowR['PACKAGE'];
            $Rt_ROUTINE=$rowR['ROUTINE'];
            $Rt_START=$rowR['START'];
            $Rt_END=$rowR['END'];
            $Rt_STATUS=$rowR['STATUS'];
            $Rt_NOTES=$rowR['NOTES'];
            $Rt_DIFF=$rowR['DIFF'];
            $Rt_FATHER2=$rowR['ID_LOG_ROUTINE'];
            $Rt_CNT_SON=$rowR['CNT_SON'];
            $RtEsito="";
            switch($Rt_STATUS){
               case 'E': $RtEsito="Err"; break;
               case 'I': $RtEsito="Run"; break;
               case 'F': $RtEsito="Com"; break;
               case 'W': $RtEsito="War"; break;
               case 'M': $RtEsito="Frz"; break;
            }
            
           include "./view/statoshell/elementoTabella10.php";
        }   
       echo '</ul>';
   }

   function SearchFatherRoutine($conn, $ListIdOpen,$IdRunSql,$IdRunSh,$Status,$_modelshell){
     $datiSearchFatherRoutine =  $_modelshell->getSearchFatherRoutine($IdRunSql);
	 if ( in_array($IdRunSh.'_'.$IdRunSql, $ListIdOpen) or "$Status" == "I" ) { $uldisplay = "block"; } else { $uldisplay = "none"; }
       echo '<ul class="SearchFatherRoutine" style="display:'.$uldisplay.';">';
     
       // while ($rowR = db2_fetch_assoc($stmtR)) {
        foreach ($datiSearchFatherRoutine as $rowR) {
            $Rt_SCHEMA=$rowR['SCHEMA'];
            $Rt_PACKAGE=$rowR['PACKAGE'];
            $Rt_ROUTINE=$rowR['ROUTINE'];
            $Rt_START=$rowR['START'];
            $Rt_END=$rowR['END'];
            $Rt_STATUS=$rowR['STATUS'];
            $Rt_NOTES=$rowR['NOTES'];
            $Rt_DIFF=$rowR['DIFF'];
            $Rt_FATHER=$rowR['ID_LOG_ROUTINE'];
            $Rt_CNT_SON=$rowR['CNT_SON'];
            $RtEsito="";
            switch($Rt_STATUS){
               case 'E': $RtEsito="Err"; break;
               case 'I': $RtEsito="Run"; break;
               case 'F': $RtEsito="Com"; break;
               case 'W': $RtEsito="War"; break;
               case 'M': $RtEsito="Frz"; break;
            }
            
           include "./view/statoshell/elementoTabella11.php";
        }
        echo '</ul>';        
   }
   
	
?>