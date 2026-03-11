 <?php
  $titolo = '<B>' . $ShName . '</B>';
  $iconStatus = '<img title="' . $IdRunSh . '" src="./images/Shell.png" class="IconFile"  onclick="OpenShSel('.$IdRunSh.',\''.$_SESSION["db_name"].'\');" />';

  if ($_SESSION['SERVER_NAME'] == "SVIL") {
    $iconStatus .= '<img src="./images/Cestino.png" class="IconFile" title="deleteSh" onclick="deleteSh(' . $IdRunSh . ')">';
  }
  if ($ShStatus == "E") {
    $iconStatus .= '<img src="./images/ManualOk.png" class="IconFile" title="Manual Ok" onclick="ManualOk(' . $IdRunSh . ')">';
  }
  if ($ShStatus == "I") {
    $iconStatus .= '<img src="./images/Skull.png" title="Put this Shell in an error state" onclick="ForceEnd(' . $IdRunSh . ')" class="IconSh" style="width:30px;">';
  }
  if ($WaitTime >= "60") {
    $iconStatus .= '<img src="./images/WaitTime.png" title="WaitTime" class="IconSh" style="width:25px;">';
  }



  $iconAction = '';
  if ($datishell->ShowSourceSh == "Y") {
    $iconAction .= '<img src="./images/File.png" class="IconFile" onclick="openDialog(' . $IdSh . ',\'File: ' . $ShName . '\',\'apriFile\')">';
  }
  if ($ShLog != "") {
    $iconAction .= '<img src="./images/Log.png" onclick="openDialog(' . $IdRunSh . ',\'Log: ' . $ShName . '\',\'apriLog\')" class="IconSh">';
  }
  if ($ShDebugSh == "Y") {
    $iconAction .= '<img src="./images/DebugSh.png" title="DebugSh" class="IconDebug">';
  }
  if ($ShDebugDb == "Y") {
    $iconAction .= '<img src="./images/DebugDb.png" title="DebugDb" class="IconDebug">';
  }
  
 
  $iconAction .= '<img src="./images/Graph.png" onclick="openGrafici(\'' . $ShName . '\',\'' . $ShTags . '\',\'' . $IdSh . '\')" class="IconSh">';

  //if ("$ShTabTouch" != "0") {
    $iconAction .= '<img src="./images/PlsqlTab.png" onclick="openRelTab(\'' . $IdSh . '\',\'' . $IdRunSh . '\',\'\',\'' . $ShName . '\')" class="IconSh" >';
 // }
 if(!$datishell->INRETE)
 {
 $iconAction .='<img src="./images/lanciShell.png" class="IconSh" onclick="OpenSelShell(' . $IdRunSh . ')"></i>';
 }
  if ($ShMail == "Y") {
    $iconAction .= '<img src="./images/Mail.png" class="IconDebug">';
  }

  $progresElement = "";
  if ($ShLastSecDiff != "") {
    $SecLast = $ShSecDiff;
    $SecPre = $ShLastSecDiff;
    if ("$SecPre" == "0") {
      $SecPre = 1;
    }
    $Perc = round(($SecLast * 100) / $SecPre);
    if ($Perc <= 100 and $ShStatus != "I") {
      $SColor = $datishell->BarraMeglio;
    }
    if ($ShStatus == "I") {
      $SColor = $datishell->BarraCaricamento;
    }
    if ($Perc > 120) {
      $SColor = $datishell->BarraPeggio;
    }
    if ((1 == 1
        and ($Perc > 120 or $Perc < 90)
        and  ($ShStatus == "F" or $ShStatus == "W")
        and ($SecLast - $SecPre > $datishell->GAP or $SecLast - $SecPre < -$datishell->GAP)
      ) or ($ShStatus == "I")
    ) {
      $progresElement .= '<div class="progress">';
      $progresElement .= '<div class="progress-bar progress-bar-striped';
      if ($ShStatus == "I") {
        $progresElement .= ' active';
      }
      $progresElement .= '" role="progressbar" aria-valuenow="' . $Perc . '" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width:';
      if ($Perc > 100) {
        $progresElement .= ' 100';
      } else {
        $progresElement .= $Perc;
      }
      $progresElement .= '%;background-color: ' . $SColor . ';height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;">';
      if ($Perc > 100) {
        $Perc = $Perc - 100;
        $Perc = "+" . $Perc;
      } else {
        if ($ShStatus != "I") {
          $Perc = $Perc - 100;
        }
      }
      $progresElement .= $Perc . '%</LABEL></div></div>';
    }
  }
  ?>



 <a onclick="return false" >
   <table class="ExcelTable L0Def Tabella1" style="box-shadow: 0px 0px 0px 1px black;">
     <tr>
       <td onclick="ChangeA<?php echo $datishell->InRetePasso; ?>('<?php echo $IdRunSh; ?>',1)" rowspan=2 style="min-width:3px" class="<?php echo $Esito; ?>"></td>
       <td onclick="ChangeA<?php echo $datishell->InRetePasso; ?>('<?php echo $IdRunSh; ?>',1)" class="openLi" rowspan=2 style="min-width:50px" title='<?php echo $ShMessage; ?>'>RC:<?php echo $ShRc; ?></td>
       <td onclick="ChangeA<?php echo $datishell->InRetePasso; ?>('<?php echo $IdRunSh; ?>',1)" rowspan=2 class="openLi" style="cursor:pointer;min-width:280px">
         <?php echo $titolo; ?>
       </td>
       <td class="iconStatus" rowspan=2>
         <?php echo $iconStatus; ?>
       </td>
       <td rowspan=2 style="min-width:170px;">
         <?php echo $iconAction; ?>
       </td>
       <th style="min-width:50px" class=""><B>EserEsame</B></th>
       <td style="min-width:50px" class=""><?php echo $ShEserEsame; ?></td>
       <th style="min-width:50px"><B>Tags</B></th>
       <td style="min-width:50px"><?php echo $ShTags; ?></td>
       <th style="min-width:40px" class="ClsDett"><B>Variables</B></th>
       <td style="min-width:220px" class="ClsDett"><?php echo $ShVariables; ?></td>
       <th style="min-width:50px"><B>Start</B></th>
       <td style="min-width:155px"><?php echo $ShSTART_TIME; ?></td>
       <th style="min-width:80px" class=""><B>Time</B></th>
       <th style="min-width:80px" class=""><B>OldTime</B></th>
       <th class=""><B>User</B></th>
        <th class=""><B>Ambito</B></th>
     </tr>
     <tr>
       <th style="min-width:50px" class=""><B>MeseEsame</B></th>
       <td style="min-width:50px" class=""><?php echo $ShMeseEsame; ?></td>
       <th style="min-width:50px"><B>Meter</B></th>
       <td style="min-width:155px">
         <?php echo $progresElement; ?>
       </td>
       <th class="ClsDett"><B>Dir</B></th>
       <td style="min-width:155px" class="ClsDett"><?php echo $ShShellPath; ?></td>
       <th><B><?php if ("$ShEND_TIME" == "") {
                echo "Prw";
              } ?>End</B></th>
       <td><?php
            if ("$ShEND_TIME" == "") {
             if ( "$ShPrwEnd" != "" ) { 
				 date_default_timezone_set('Europe/Rome');
			     $prw_time=strtotime("$ShPrwEnd");
                 $now_time=strtotime(date("Y-m-d h:i:s"));
                 $diff=$prw_time-$now_time;
			     if ( $diff < 0 ) {	
                   echo '<font color="blueviolet"><B>' . $ShPrwEnd . '</B></font>';
			     }else{
			       echo '<font color="blue"><B>' . $ShPrwEnd . '</B></font>';
			     }	
		      }	  
            } else {
              echo $ShEND_TIME;
            }
			
            ?></td>
       <td style="min-width:55px" class=""><?php echo "$ShDiff"; ?></td>
       <td style="min-width:55px" class=""><?php echo $ShOldDiff; ?></td>

       <td style="min-width:155px" class=""><?php echo $ShUser; ?></td>
       <td style="cursor:pointer;min-width:140px">
         <?php 
         $Expl=explode("/",$ShShellPath); 
         echo $Expl[count($Expl)-1]; 
         ?>
       </td>
     </tr>
   </table>
 </a>