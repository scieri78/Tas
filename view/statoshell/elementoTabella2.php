 <?php
 $iconAction ="";
 if ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){
      $iconAction .='<img src="./images/LogDb.png" onclick="openTabLog('.$IdSql.')" class="IconSql">';
 }
 if ( "$SqlFile" != "Anonymous Block" and  "$SqlFile" != "SQL DB2 Block" ){
 $iconAction .='<img src="./images/File.png" onclick="openSqlFile('.$IdSql.',\''.$SqlFile.'\',\''.str_replace('.','. ',$SqlStep).'\')" class="IconSql" >';
 }
// if ("$SqlTabTouch" != "0") {
//  $iconAction.='<img src="./images/PlsqlTab.png" onclick="openRelTab(\'\',\'\',\''.$IdRunSql.'\',\''.$SqlFile.'\')" class="IconSh" >';
//}

 
$progresElement = "";
if ($SqlLastSecDiff != "" ){
$SecLast=$SqlSecDiff;
$SecPre=$SqlLastSecDiff;
 if ( "$SecPre" == "0" ){
    $SecPre = 1;
  }
$Perc = round(( $SecLast * 100 ) / $SecPre );
if ( $Perc <= 100 and "$SqlStatus" != "I" ) {
	$SColor =  $datishell->BarraMeglio;
	}
if ( "$SqlStatus" == "I" ) {
  $SColor =  $datishell->BarraCaricamento;
 }
if ( $Perc > 120 ) {
	$SColor =  $datishell->BarraPeggio;
}
if (( 1==1
     and ( $Perc > 120 or $Perc < 90 ) 
     and  ( "$SqlStatus" == "F" or "$SqlStatus" == "W" )
     and ( $SecLast - $SecPre > $datishell->GAP or $SecLast - $SecPre < -$datishell->GAP )
     ) or ( "$SqlStatus" == "I" )
    ) {
        $progresElement .='<div class="progress">';
        $progresElement .='<div class="progress-bar progress-bar-striped';
        if ("$SqlStatus" == "I") {
              $progresElement .= ' active';
         } 
        $progresElement .='" role="progressbar" aria-valuenow="'.$Perc.'" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width:'; 
        if ($Perc > 100) {
        $progresElement .= ' 100';
              } else {
               $progresElement .=$Perc;
              } 
       $progresElement .='%;background-color: '.$SColor.';height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;">';
              if ($Perc > 100) {
                  $Perc = $Perc - 100;
                  $Perc = "+" . $Perc;
              } else {
                  if ( "$SqlStatus" != "I" ){
                    $Perc = $Perc - 100;
                  } 
              }                                   
        $progresElement .= $Perc.'%</LABEL></div></div>';
         
      }
  }
 ?>                     
 
 
 
 <a onclick="ChangeA<?php echo $InRetePasso; ?>('<?php echo $IdRunSql."_".$IdSql; ?>')" > 
               <table class="ExcelTable L1Sql Tabella2" style="box-shadow: 0px 0px 0px 1px black;" >
                    <tr>
                        <td rowspan=2 style="min-width:3px" class="<?php echo $SqlEsito; ?>" ></td>
                        <td class="openLi" rowspan=2 style="min-width:378px" ><B><?php echo str_replace('.','. ',$SqlStep); ?></B></td>
                        <td rowspan=2 style="min-width:70px" >
						<Name>
                        <?php echo $iconAction;						
                        if ( "$SqlFile" != "Anonymous Block" and  "$SqlFile" != "SQL DB2 Block" ){
                         ?><img src="./images/PlsqlTab.png" onclick="openRelTab('<?php echo $IdSql; ?>','<?php echo $IdRunSql; ?>','<?php echo $IdSql; ?>','<?php echo str_replace('.','. ',$SqlStep); ?>')" class="IconSql" ><?php
                        }  
                        ?>
						</Name>
                            
                        </td>
                        <th style="min-width:50px" ><B>Type</B></th>
                        <td style="min-width:155px"><?php echo $SqlType; ?></td>
                        <th style="min-width:40px"  class="ClsDett" ><B>Sql</B></th>
                        <td style="min-width:300px" onclick="openSqlFile(<?php echo $IdSql; ?>,'<?php echo $SqlFile; ?>',<?php echo str_replace('.','. ',$SqlStep); ?>)" style="cursor:pointer;"  class="ClsDett" >
                            <?php echo $SqlFile; ?>
                        </td>
                        <th style="min-width:50px"><B>Start</B></th>
                        <td style="min-width:155px"><?php echo $SqlSTART_TIME; ?></td>
                        <th style="min-width:80px" class="" ><B>Time</B></th>
                        <th style="min-width:80px" class="" ><B>OldTime</B></th>
                    </tr>
                    <tr>
                        <th style="min-width:50px"><B>Meter</B></th>
                        <td style="min-width:155px">
                            <?php echo $progresElement; ?> 
                        </td>
                        <th class="ClsDett" ><B><?php 
                        if ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){
                          ?>Note<?php
                        }else{
                          ?>File<?php 
                        }
                        ?></B></th>
                        <td class="ClsDett" ><?php                        
                            echo $SqlInFile;                        
                        ?></td>
                        <th><B><?php if ( "$SqlEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                        <td><?php
                        if ( "$SqlEND_TIME" == "" ) {
							if ( "$SqlPrwEnd" != "" ) { 
				              date_default_timezone_set('Europe/Rome');
                              $prw_time=strtotime("$SqlPrwEnd");
                              $now_time=strtotime(date("Y-m-d h:i:s"));
                              $diff=$prw_time-$now_time;
			                  if ( $diff < 0 ) {	
                                echo '<font color="blueviolet"><B>' . $SqlPrwEnd . '</B></font>';
			                  }else{
			                    echo '<font color="blue"><B>' . $SqlPrwEnd . '</B></font>';
			                  }				
							}
                        }else{
                          echo $SqlEND_TIME; 
                        }
                        ?></td>
                        <td style="min-width:55px" class="" ><?php echo $SqlDiff; ?></td>
                        <td style="min-width:55px" class="" ><?php echo $SqlOldDiff; ?></td>
                    </tr>
                </table>
                </a>