 <?php 
 $iconStatus='<img title="'.$IdRunSh.'" src="./images/Shell.png" class="IconFile"  onclick="OpenShSel('.$IdRunSh.',\''.$_SESSION["db_name"].'\');" /><B>'.$ShName.'</B>';
 if ( $datishell->ShStatus == "E" ){
	$iconStatus .= '<img src="./images/ManualOk.png" class="IconFile" title="Manual Ok" onclick="ManualOk('.$IdRunSh.')">'; 
	}
if ( $datishell->ShStatus == "I" ){
	$iconStatus .= '<img src="./images/Skull.png" title="Put this Shell in an error state" onclick="ForceEnd('.$IdRunSh.')" class="IconSh" style="width:30px;">';
	}
if ( $WaitTime >= "60" ){
	$iconStatus .= '<img src="./images/WaitTime.png" title="WaitTime" class="IconSh" style="width:25px;">';
	}
 
  

$iconAction='';
if ( $ShowSourceSh == "Y" ){ 
  $iconAction.='<img src="./images/File.png" class="IconFile" onclick="OpenFile('.$FIdSh.')">'; 
}
if ($ShLog != "" ){
  $iconAction.='<img src="./images/Log.png" onclick="OpenLog('.$FIdRunSh.')" class="IconSh">';
}                
$iconAction.='<img src="./images/Graph.png" onclick="ShowGraph(\''.$FShName.'\',\''.$FShTags.'\',\''.$FIdSh.'\')" class="IconSh">';

if ("$FShTabTouch" != "0") {
  $iconAction.='<img src="./images/PlsqlTab.png" onclick="OpenTabFile(\''.$FIdSh.'\',\''.$FIdRunSh.'\',\'\')" class="IconSh" >';
}
if ($ShMail == "Y"){
  $iconAction.='<img src="./images/Mail.png" class="IconDebug">';
}
if ($ShDebugSh == "Y" ){
  $iconAction.='<img src="./images/DebugSh.png" title="DebugSh" class="IconDebug">';
}
if ($ShDebugDb == "Y" ){
  $iconAction.='<img src="./images/DebugDb.png" title="DebugDb" class="IconDebug">';
}    


$progresElement = "";
if ($ShLastSecDiff!= "" ){
$SecLast=$ShSecDiff;
$SecPre=$ShLastSecDiff;
 if ( "$SecPre" == "0" ){
    $SecPre = 1;
  }
$Perc = round(( $SecLast * 100 ) / $SecPre );
if ( $Perc <= 100 and "$ShStatus" != "I" ) {
	$SColor =  $datishell->BarraMeglio;
	}
if ( "$ShStatus" == "I" ) {
  $SColor =  $datishell->BarraCaricamento;
 }
if ( $Perc > 120 ) {
	$SColor =  $datishell->BarraPeggio;
}
if (( 1==1
     and ( $Perc > 120 or $Perc < 90 ) 
     and  ( "$ShStatus" == "F" or "$ShStatus" == "W" )
     and ( $SecLast - $SecPre > $datishell->GAP or $SecLast - $SecPre < -$datishell->GAP )
     ) or ( "$ShStatus" == "I" )
    ) {
        $progresElement .='<div class="progress">';
        $progresElement .='<div class="progress-bar progress-bar-striped';
        if ("$ShStatus" == "I") {
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
                  if ( "$ShStatus" != "I" ){
                    $Perc = $Perc - 100;
                  } 
              }                                   
        $progresElement .= $Perc.'%</LABEL></div></div>';
         
      }
  }
 ?> 


 
   <a onclick="ChangeA<?php echo $FInRetePasso; ?>('<?php echo $FIdRunSh; ?>')" >
               <table class="ExcelTable Tabella5" style="box-shadow: 0px 0px 0px 1px black;" >
                    <tr>
                        <td rowspan=2 style="min-width:3px" class="<?php echo $FEsito; ?>" ></td>
                        <td class="openLi" rowspan=2 style="min-width:50px" title='<?php echo $FShMessage; ?>' >RC:<?php echo $FShRc; ?></td>
                        <td class="openLi" rowspan=2 style="cursor:pointer;min-width:320px" ><img title="<?php echo $FIdRunSh; ?>" src="./images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $FIdRunSh; ?>);" /><B><?php echo $FShName; ?></B></td>
                        <td rowspan=2  style="min-width:170px;">
							<?php echo $iconAction; ?>
						</td>
                        <th style="min-width:50px" class="" ><B>EserEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $FShEserEsame; ?></td>
                        <th style="min-width:50px"><B>Tags</B></th>
                        <td style="min-width:50px"><?php echo $FShTags; ?></td>
                        <th style="min-width:40px" class="ClsDett" ><B>Variables</B></th>
                        <td style="min-width:220px" class="ClsDett" ><?php echo $FShVariables; ?></td>
                        <th style="min-width:50px"><B>Start</B></th>
                        <td style="min-width:155px"><?php echo $FShSTART_TIME; ?></td>
                        <th style="min-width:80px" class="" ><B>Time</B></th>
                        <th style="min-width:80px" class="" ><B>OldTime</B></th>
                        <th class="" ><B>User</B></th>
                    </tr>
                    <tr>
                        <th style="min-width:50px" class="" ><B>MeseEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $FShMeseEsame; ?></td>
                        <th style="min-width:50px"><B>Meter</B></th>
                        <td style="min-width:155px">
							<?php echo $progresElement;?>                      
                        </td>
                        <th class="ClsDett" ><B>Dir</B></th>
                        <td style="min-width:155px" class="ClsDett" ><?php echo $FShShellPath; ?></td>
                        <th><B><?php if ( "$FShEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                        <td><?php 
                        if ( "$FShEND_TIME" == "" ) {
						  if ( "$FShPrwEnd" != "" ) { 
				            date_default_timezone_set('Europe/Rome');
                            $prw_time=strtotime("$FShPrwEnd");
                            $now_time=strtotime(date("Y-m-d h:i:s"));
                            $diff=$prw_time-$now_time;
			                if ( $diff < 0 ) {	
                              echo '<font color="red"><B>' . $FShPrwEnd . '</B></font>';
			                }else{
			                  echo '<font color="blue"><B>' . $FShPrwEnd . '</B></font>';
			                }	
						  }							
                        }else{
                          echo $FShEND_TIME; 
                        }
                        ?></td>
                        <td style="min-width:55px" class="" ><?php echo $FShDiff; ?></td>
                        <td style="min-width:55px" class="" ><?php echo $FShOldDiff; ?></td>
                       
                        <td style="min-width:155px" class="" ><?php echo $FShUser; ?></td>
                    </tr>
                </table>
   </a>
