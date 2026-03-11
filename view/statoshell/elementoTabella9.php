<li class="<?php echo $SelClsSh; ?>" >
                    <?php   
                    $cursor='';   
                    if ( "$FIdSh" != "" ){
                        $cursor='cursor:pointer;';
                        ?>
					<label class="Esplodi" >o</label>
				<?php } ?>           
                   <a onclick="ChangeA<?php echo $FInRetePasso; ?>('<?php echo $FIdRunSh; ?>')" >
                   <table class="ExcelTable Tabella9" style="box-shadow: 0px 0px 0px 1px black;" >
                        <tr>
                            <td rowspan=2 style="min-width:3px" class="<?php echo $FEsito; ?>" ></td>
                            <td <?php if ($datishell->OpenNipote != "OpenNipote".$FIdRunSh ) { ?>
                                onclick="OpenNipote('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>');"<?php } ?> class="openLi" rowspan=2 style="<?php echo $cursor; ?>min-width:50px" title='<?php echo $FShMessage; ?>' >RC:<?php echo $FShRc; ?></td>
                            <td <?php if ($datishell->OpenNipote != "OpenNipote".$FIdRunSh ) { ?>
                                onclick="OpenNipote('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>');"<?php } ?> class="openLi" rowspan=2 style="<?php echo $cursor; ?>min-width:320px" ><img title="<?php echo $FIdRunSh; ?>" src="./images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $FIdRunSh; ?>);"/>
                            <div><B><?php echo $FShName; ?></B></div>
							</td>
                            <td rowspan=2  style="min-width:170px;">
                                <?php
                                if ( $datishell->ShowSourceSh == "Y" ){ 
                                  ?>  
                                  <img src="./images/File.png" class="IconFile" onclick="openDialog('<?php echo $FIdSh; ?>','File: <?php echo $FIdSh; ?>','apriFile')">
                                 <?php 
                                }
                                if ( $FShLog != "" ){
                                    ?>
                                     <img src="./images/Log.png" class="IconSh" onclick="openDialog('<?php echo $FIdRunSh; ?>','Log: <?php echo $FIdRunSh; ?>','apriLog')">
                                                                      
                                    <?php
                                }
                                if ( $FShMail == "Y" ){
                                    ?><img src="./images/Mail.png" class="IconSh"><?php
                                }
                                if ( $FShDebugSh == "Y" ){
                                    ?><img src="./images/DebugSh.png" title="DebugSh" class="IconDebug"><?php
                                }
                                if ( $FShDebugDb == "Y" ){
                                    ?><img src="./images/DebugDb.png" title="DebugDb" class="IconDebug"><?php
                                }
                                ?>  
                                <img src="./images/Graph.png" onclick="openGrafici('<?php echo $FShName; ?>','<?php echo $FShTags; ?>','<?php echo $FIdSh; ?>')" class="IconSh">								
                                <img src="./images/PlsqlTab.png" onclick="openRelTab('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>','<?php echo $FShName; ?>')" class="IconSh" style="width:25px;">             

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
                            <?php
                            if ( $ShLastSecDiff != "" ){
                                $FSecLast=$FShSecDiff;
                                $FSecPre=$FShLastSecDiff;
                                if ($FSecPre == "0" ){
                                    $FSecPre = 1;
                                }
                                $FPerc = round(( $FSecLast * 100 ) / $FSecPre );
                                
                                if ( $FPerc <= 100 and $FShStatus != "I" ) {
                                    $FSColor = $datishell->FBarraMeglio;
                                }
                                
                                if ( $FShStatus == "I" ) {
                                    $FSColor = $datishell->BarraCaricamento;
                                }
                                if ( $FPerc > 120 ) {
                                    $FSColor = $datishell->BarraPeggio;
                                }
                                
                                if (
                                    (1==1
                                        && ( $FPerc > 120 || $FPerc < 90 ) 
                                        &&  ( $FShStatus == "F" || $FShStatus == "W" )
                                        && ( $FSecLast - $FSecPre > $datishell->GAP || $FSecLast - $FSecPre < -$datishell->GAP )
                                    ) 
                                    || ( $FShStatus == "I" )) {
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?php 
                                        if ($FShStatus == "I") {
                                            echo " active";
                                        } 
                                        ?>" role="progressbar" aria-valuenow="<?php echo $FPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                        if ($FPerc > 100) {
                                            echo 100;
                                        } else {
                                            echo $FPerc;
                                        } 
                                        ?>%;background-color: <?php echo "$FSColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                        if ($FPerc > 100) {
                                            $FPerc = $FPerc - 100;
                                            $FPerc = "+" . $FPerc;
                                        } else {
                                            if ($FShStatus != "I" ){
                                              $FPerc = $FPerc - 100;
                                            } 
                                        }                                   
                                        echo $FPerc;
                                        ?>%</LABEL>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>                      
                            </td>
                            <th class="ClsDett" ><B>Dir</B></th>
                            <td style="min-width:155px" class="ClsDett" ><?php echo $FShShellPath; ?></td>
                            <th><B><?php if ( $FShEND_TIME == "" ) { echo "Prw"; } ?>End</B></th>
                            <td><?php 
                            if ( $FShEND_TIME == "" ) {
			                 if ( "$FShPrwEnd" != "" ) { 
			                  date_default_timezone_set('Europe/Rome');
                              $prw_time=strtotime("$FShPrwEnd");
                              $now_time=strtotime(date("Y-m-d h:i:s"));
                              $diff=$prw_time-$now_time;
			                  if ( $diff < 0 ) {	
                                echo '<font color="blueviolet"><B>' . $FShPrwEnd . '</B></font>';
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
                    <?php 
                 /*   echo "<pre>";
                    echo "FShStatus ".$FShStatus;
                    echo "FIdRunSh ".$FIdRunSh;
                    print_r($datishell->ListIdOpen);

                    echo "<pre>";*/
                    ?>
                    <ul id="ShowFiglio<?php echo $FIdSh.'_'.$FIdRunSh; ?>"  style="display:<?php if ( "$FShStatus" == "I" or in_array($FIdRunSh, $datishell->ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                      <?php 
                      if ( $FShStatus == "I" || in_array($FIdRunSh, $datishell->ListIdOpen) or $datishell->OpenNipote == "OpenNipote".$FIdRunSh  or $Opentutti == "Y" ) {
                        RecSonsh($conn,$FIdSh,$FIdRunSh,'Y',$datishell,$_modelshell); 
                      }
                      ?>
                    </ul>
                </li>
