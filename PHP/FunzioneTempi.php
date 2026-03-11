<?php

 function TempoRealeRete($Shell,$Nload,$Log) {
         $sqlFirstTime="select min(DATA) DATA,SCRIPT from WEB.WFS_SCRIPT where SHELL='$Shell' and NLOAD=$Nload and STATO='I' and LOG='$Log'";
         $rtFirstTime = mysql_query($sqlFirstTime);
          while ($rwFirstTime = mysql_fetch_assoc($rtFirstTime)) { 
            $Script=$rwFirstTime["SCRIPT"]; 
            $DM=$rwFirstTime["DATA"];
          }
          $Exit=false;
          $Durata=0;
          while ( $Exit==false ) {
              $DataFine=null;
              $sqlDataFine="select max(DATA) DATA from WEB.WFS_SCRIPT where SHELL='$Shell' and SCRIPT='$Script' and NLOAD=$Nload AND STATO<>'I' and LOG='$Log'";
              $rtDataFine = mysql_query($sqlDataFine);
              while ($rwDataFine = mysql_fetch_assoc($rtDataFine)) { $DataFine=$rwDataFine["DATA"]; } 
              if ($DataFine==null ) { $DataFine=date("Y-m-d H:i:s.p");}
              
              $DataInizio=null;
              $sqlDataInizio="select min(DATA) DATA from WEB.WFS_SCRIPT where SHELL='$Shell' and SCRIPT='$Script' and STATO='I' and DATA <= '$DataFine' and NLOAD=$Nload and LOG='$Log'";
              $rtDataInizio = mysql_query($sqlDataInizio);
              while ($rwDataInizio = mysql_fetch_assoc($rtDataInizio)) { $DataInizio=$rwDataInizio["DATA"]; }
              
              $DataInizioIn=$DataInizio;
              $DataFineIn=$DataFine;
              $Ripeti=true;
              while ( $Ripeti==true ) {
                  $nessunScriptIn=null;
                  $sqlScriptIn="select SCRIPT,DATA from WEB.WFS_SCRIPT where SHELL='$Shell' and SCRIPT <> '$Script' and STATO='I' and DATA >= '$DataInizioIn' and DATA <= '$DataFineIn' and NLOAD=$Nload and LOG='$Log'";
                  $rtScriptIn = mysql_query($sqlScriptIn);
                  while ($rwScriptIn = mysql_fetch_assoc($rtScriptIn)) {
                    $nessunScriptIn="TrovatiScriptInterni";
                    $ScriptIn=$rwScriptIn["SCRIPT"];
                    $ScriptDataInz=$rwScriptIn["DATA"];
                    $DataFineStepUnito=null;
                    $sqlDataFineIn="select max(DATA) DATA  from WEB.WFS_SCRIPT where SHELL='$Shell' and SCRIPT = '$ScriptIn' and NLOAD=$Nload and STATO<>'I' and LOG='$Log'";
                    $rtDataFineIn = mysql_query($sqlDataFineIn);
                    while ($rwDataFineIn = mysql_fetch_assoc($rtDataFineIn)) {
                      $DataFineStepUnito=$rwDataFineIn["DATA"]; 
                      $sqlTempo="SELECT  TIMESTAMPDIFF('$DataFineIn','$DataFineStepUnito') DURATA";
                      $rtTempo = mysql_query($sqlTempo);
                      while ($rwTempo = mysql_fetch_assoc($rtTempo)) { $Tempo=$rwTempo["DURATA"]; }
                      if ( $Tempo > 0 ) { 
                        $DataFineIn=$DataFineStepUnito;
                        $DataInizioIn=$ScriptDataInz;
                        $Script=$ScriptIn;
                        $DataFine=$DataFineIn;                      
                      }
                    }
                    if ( $DataFineStepUnito == null){ 
                      $Ripeti = false;
                      $DataFineIn=date("Y-m-d H:i:s.%p"); 
                    }                     
                  }
                  if ( $nessunScriptIn == null or "$DataFine" == "$DataFineIn" ){ $Ripeti = false; }
              }
              
              
              $sqlTTStep="SELECT  TIMESTAMPDIFF('$DataInizio','$DataFine') DURATA";
              $rtTTStep = mysql_query($sqlTTStep);
              while ($rwTTStep = mysql_fetch_assoc($rtTTStep)) {
                $DurStep=$rwTTStep["DURATA"];
                $Durata=$Durata+$DurStep;
              } 
              $Script=null;           
              $sqlControl="select min(DATA),SCRIPT from WEB.WFS_SCRIPT where SHELL='$Shell' and STATO='I' and DATA > '$DataFine' and NLOAD=$Nload and LOG='$Log'";
              $rtControl = mysql_query($sqlControl);
              while ($rwControl = mysql_fetch_assoc($rtControl)) { $Script=$rwControl["SCRIPT"]; }
             
              if ( $Script == null ){
                $Exit=true;
              }               
          }
          return $Durata;
 }
 
 ?>