<li id="List_<?php echo $SIdRunSh."_".$SIdSql; ?>" class="has-sub Figlio<?php echo $IdRunSh; ?>">
                    <?php if ( $SSqlType == "PLSQL" and $SSqlUseRoutine != "0" ) {  ?><label class="Esplodi" >o</label><?php } ?>  
                    <a onclick="ChangeA<?php echo $InRetePasso; ?>('<?php echo $IdRunSh.'_'.$SIdRunSh."_".$SIdSql; ?>')" >
                    <table class="ExcelTable L2Sql Tabella7" style="box-shadow: 0px 0px 0px 1px black;" >
                        <tr>
                            <td rowspan=2 style="min-width:3px" class="<?php echo $SSqlEsito; ?>" ></td>
                            <?php
                                $cursor='';
                                if ( $SSqlType == "PLSQL" and $SSqlUseRoutine != "0" ){
                                    $cursor='cursor:pointer;';
                                }
                                    ?> 
                            <td rowspan=2 class="openLi" style="<?php echo $cursor; ?>min-width:378px" ><B><?php echo str_replace('.','. ',$SSqlStep); ?></B></td>
                            <td rowspan=2 style="min-width:70px" >
                                <?php
                                if ( $SSqlType == "PLSQL" and $SSqlUseRoutine != "0" ){
                                    ?><img src="./images/LogDb.png" onclick="openTabLog(<?php echo $SIdSql; ?>, '<?php echo $titoloSH; ?>')" class="IconSh"><?php
                                }
                                if ( $SSqlFile != "Anonymous Block" and  $SSqlFile != "SQL DB2 Block" ){
                                 ?><img src="./images/File.png" onclick="openSqlFile(<?php echo $SIdSql; ?>,'<?php echo $SSqlFile; ?>', '<?php echo $titoloSH; ?>')" class="IconSh" ><?php
                                }
                              if ( $SSqlFile != "Anonymous Block" and $SSqlFile != "SQL DB2 Block" ){
                                ?><img src="./images/PlsqlTab.png" onclick="openRelTab('<?php echo $SIdSh; ?>','<?php echo $SIdRunSh; ?>','<?php echo $SIdSql; ?>', '<?php echo $titoloSH; ?>')" class="IconSh" ><?php
                              }
                                ?>                     
                            </td>
                            <th style="min-width:50px" ><B>Type</B></th>
                            <td><?php echo $SSqlType; ?></td>
                            <th style="min-width:40px"  class="ClsDett" ><B>Sql</B></th>
                            <td style="min-width:400px"  onclick="openSqlFile(<?php echo $SIdSql; ?>,'<?php echo $SSqlFile; ?>', '<?php echo $titoloSH; ?>')" style="cursor:pointer;"  class="ClsDett" >
                              <?php echo $SSqlFile; ?>
                            </td>
                            <th style="min-width:50px"  ><B>Start</B></th>
                            <td style="min-width:155px" ><?php echo $SSqlSTART_TIME; ?></td>
                            <th style="min-width:50px" class="" ><B>Time</B></th>
                            <th style="min-width:50px" class="" ><B>OldTime</B></th>
                        </tr>
                        <tr>
                            <th style="min-width:50px"><B>Meter</B></th>
                            <td style="min-width:155px">
                            <?php
                            if ( $SSqlLastSecDiff != "" ){
                                $SecLast=$SSqlSecDiff;
                                $SecPre=$SSqlLastSecDiff;
                                if ( $SecPre == "0" ){
                                    $SecPre = 1;
                                }
                                $Perc = round(( $SecLast * 100 ) / $SecPre );

                                if ( $Perc <= 100 and $SSqlStatus != "I" ) {
                                    $SColor = $datishell->BarraMeglio;
                                }
                                if (  $SSqlStatus == "I" ) {
                                    $SColor = $datishell->BarraCaricamento;
                                }   
                                if ( $Perc > 120 ) {
                                    $SColor = $datishell->BarraPeggio;
                                }   
                                
                                if (
                                    (   1==1
                                        and ( $Perc > 120 or $Perc < 90 ) 
                                        and  ( $SSqlStatus == "F" or $SSqlStatus == "W" )
                                        and ( $SecLast - $SecPre > $datishell->GAP or $SecLast - $SecPre < -$datishell->GAP )
                                    ) 
                                    or ( $SSqlStatus == "I" )
                                ) {
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?php 
                                        if ($SSqlStatus == "I") {
                                            echo " active";
                                        } 
                                        ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                        if ($Perc > 100) {
                                            echo 100;
                                        } else {
                                            echo " $Perc";
                                        } 
                                        ?>%;background-color: <?php echo $SColor; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                        if ($Perc > 100) {
                                            $Perc = $Perc - 100;
                                            $Perc = "+" . $Perc;
                                        } else {
                                            if ( $SSqlStatus != "I" ){
                                              $Perc = $Perc - 100;
                                            } 
                                        }                                       
                                        echo $Perc;
                                        ?>%</LABEL>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>                                                                      
                            </td>
                            <th class="ClsDett" ><B><?php 
                            if ( $SSqlType == "PLSQL" && $SSqlUseRoutine != "0" ){
                              ?>Note<?php
                            }else{
                              ?>File<?php
                            }
                            ?></B></th>
                            <td style="min-width:155px" class="ClsDett" ><?php 
                          
                              echo $SSqlInFile; 
                            //}
                            ?></td>
                            <th ><B><?php if ( $SSqlEND_TIME == "" ) { echo "Prw"; } ?>End</B></th>
                            <td><?php 
                            if ( $SSqlEND_TIME == "" ) {
						     if ( "$SSqlPrwEnd" != "" ) { 
				              date_default_timezone_set('Europe/Rome');
                              $prw_time=strtotime("$SSqlPrwEnd");
                              $now_time=strtotime(date("Y-m-d h:i:s"));
                              $diff=$prw_time-$now_time;
			                  if ( $diff < 0 ) {	
                                echo '<font color="blueviolet"><B>' . $SSqlPrwEnd . '</B></font>';
			                  }else{
			                    echo '<font color="blue"><B>' . $SSqlPrwEnd . '</B></font>';
			                  }		
							 }							  
                            }else{
                              echo $SSqlEND_TIME; 
                            }
                            ?></td>
                            <td style="min-width:55px" class="" ><?php echo $SSqlDiff; ?></td>
                            <td style="min-width:55px" class="" ><?php echo $SSqlOldDiff; ?></td>
                        </tr>
                    </table>
                    </a>
                    <?php 
                    if ( ( $SSqlType == "PLSQL" && "$SSqlUseRoutine" != "0" ) || ($SSqlStatus == "I") ){
                        SearchFatherRoutine($conn, $ListIdOpen,$SIdSql,$IdRunSh.'_'.$SIdRunSh, $SSqlStatus,$_modelshell);          
                    }
                    ?>
                </li>
