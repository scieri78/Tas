<li <?php echo $lidisable; ?>>
                    <div class="Group Div<?php echo $SonIdEmGroupDep ?>" >
                    <table>
                    <tr>
                    <td style="width:20px;<?php echo $SonDepBkgr; ?>" class="DivNum<?php echo $SonIdEmGroupDep; ?>" ><?php echo $SonPriorityDep; ?></td>
                    <td>
                       <table class="ExcelTable" >
                       <tr>
                            <th rowspan=2 style="background:white;" >
                             <table class="TitShell" >
                             <tr><td style="min-width:200px !important" >
                             <img class="FolderImg" src="./images/Folder.png" >
                             <?php 
                             if ( "$SonDepInRun" != "0" ){ 
                               ?><img class="FolderImg" src="./images/StatusI.png" ><?php
                             }               
                             if ( "$SonDepStatus" != "I" ){ 
                               ?><img class="FolderImg" src="./images/Status<?php echo "$SonDepStatus"; ?>.png" ><?php
                             }
							 $DescNotEnable="";
                             if ( "$SonDepNotEnable" != "0" ){ 
                               ?><img class="FolderImg" src="./images/Attention.png" ><?php
						       $DescNotEnable="<font color='red'> ${SonDepNotEnable}Off</font>";
                             }                               
                             ?>                          
		  <div class="GroupNameGroup"  onclick="OpenGroup(<?php echo $SonIdEmGroupDep; ?>,'<?php echo $SonDepNameGroup; ?>')" title="<?php echo $SonIdEmGroupDep; ?>" ><?php echo "$SonDepNameGroup [${CntDep}${DescNotEnable}]"; ?></div>
                             </td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Creator:</B> <?php echo "$SonDepCreator - $TimeCreator"; ?></div></td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div></td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div></td></tr>
                             </table>
                            </th>
                            <td><img title="Set to No Run"    class="GroupImg" src="./images/DepNoRun.png"     onclick="DepNoRun(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <td><img title="Recursive set to  No Run" class="GroupImg" src="./images/RecOff.png"     onclick="RTecOff(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <td><img title="Add" class="GroupImg" src="./images/Add.png"      onclick="AddGroup(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <?php if ( "$SonDepStatus" == "I" or "$SonDepInRun" != "0" ) { ?>
                            <td></td>
                            <td></td>
                            <?php } else { ?>                   
                            <td><img title="Modify" class="GroupImg" src="./images/Setting.png"    onclick="EditGroup(<?php echo $SonIdEmGroupDep; ?>)"    ></td>
                            <td>
                            <?php if ( "$SonIdEmGroupDep" != "0" ) { ?>
                               <img title="Delete" class="GroupImg" src="./images/Remove.png"   onclick="DeleteGroup(<?php echo $SonIdEmGroupDep; ?>)" >
                            <?php } ?>
                            </td>
                            <?php } ?>
                            <td><img title="Enable/Disable" class="GroupImg" src="./images/Power.png"      onclick="Power(<?php echo $SonIdEmGroupDep; ?>,'<?php echo $SonDepEnable; ?>')" ></td>
                            <td rowspan=2>
                            <?php if ( "$SonDepBckGrnd" == "Y" ) {
                               /* ?><img class="GroupImg" src="./images/Background.png"  title="accepts parallels"><?php */
                               ?><B>EXEC<BR>NEXT</B><?php                              
                            } ?>
                            </td>
                            <th style="min-width:100px !important"  >Description</th>
                            <th style="min-width:65px !important"  >Start Time</th>
                            <td style="min-width:100px !important" ><?php echo "$SonDepStartTime"; ?></td>                           
                            <th style="min-width:65px !important"  >True Time</th>
                            <th>Enable From</th>
                            <?php if ( "$SonDepValidTo" != "2999-12-31" ) { ?><th>Enable To</th><?php } ?>
                            <th style="width:200px !important" >Dependent</th>
                       </tr>
                       <tr>
                             <td><img title="Set to Run"    class="GroupImg" src="./images/DepRun.png"     onclick="DepRun(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                             <td><img title="Recursive set to Run" class="GroupImg" src="./images/RecOn.png"     onclick="RTecOn(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                             <?php if ( "$SonDepStatus" == "I" or "$SonDepInRun" != "0" ) { ?>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <?php } else { ?>                  
                             <td><img title="Up Priority"    class="GroupImg" src="./images/Up.png"       onclick="DownGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep-1; ?>)" ></td>
                             <td><img title="Down Priority"  class="GroupImg" src="./images/Down.png"     onclick="UpGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep+1; ?>)" ></td>
                             <td><img title="First Priority" class="GroupImg" src="./images/Frist.png"    onclick="DownGroup(<?php echo $IdEmGroup; ?>,<?php echo $SonPriorityDep; ?>,1)" ></td>
                             <td><img title="Last Priority"  class="GroupImg" src="./images/Last.png"     onclick="UpGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,0)" ></td>
                             <?php } ?>
                             <td><div width="100px" ><?php echo "$SonDepDescrGroup"; ?></div></td>
                             <th>End Time</th>
                             <td width="100px" ><?php echo "$SonDepEndTime"; ?></td>
                             <td style="min-width:100px !important" ><?php if ( "$TrueTime" != "0" and "$TrueTime" != "" ) { echo  floor(($TrueTime-1)/(60*60*24))."g ".gmdate('H:i:s', $TrueTime); } ?></td>
                             <td><?php echo "$SonDepValidFrom"; ?></td>
                             <?php if ( "$SonDepValidTo" != "2999-12-31" ) { ?><td><?php echo "$SonDepValidTo"; ?></td><?php } ?>
                             <td class="DivDep<?php echo $SonIdEmGroupDep; ?>">
                                <?php if ( $FindLinks != 0 ){
                                    $LisLnk="";  
                                    foreach( $Arr_Groups as $Group2 ){
                                      $IdEmGroup2=$Group2[0];
                                      $NameGroup2=$Group2[1];
                                      $Enable2   =$Group2[2];
                                      $DescrGroup2  =$Group2[12];
                                        if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                            $LisLnk=$LisLnk.$NameGroup2.' '.$DescrGroup2.',<BR>';
                                        }
                                    }
                                    echo $LisLnk;
                                    ?>
                                    <script>
                                         $('.Div<?php echo $SonIdEmGroupDep; ?>').mouseover(function(){
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('background','SteelBlue');
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('color','white');
                                               <?php
                                                foreach( $Arr_Groups as $Group2 ){
                                                  $IdEmGroup2=$Group2[0];
                                                  $NameGroup2=$Group2[1];
                                                  $Enable2   =$Group2[2];
                                                  $DescrGroup2  =$Group2[12];
                                                    if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                        ?>
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','2px solid SteelBlue');
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','white');
                                                        <?php
                                                    }   
                                               }
                                               ?>
                                         });
                                         $('.Div<?php echo $SonIdEmGroupDep; ?>').mouseleave(function(){
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('background','white');
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('color','black');
                                               <?php
                                                foreach( $Arr_Groups as $Group2 ){
                                                  $IdEmGroup2=$Group2[0];
                                                  $NameGroup2=$Group2[1];
                                                  $Enable2   =$Group2[2];
                                                  $DescrGroup2  =$Group2[12];
                                                    if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                        ?>
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','none');
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','black');
                                                        <?php
                                                    }   
                                               }
                                               ?>
                                         });                                     
                                    </script>
                                    <?php                                       
                                }
                                ?>
                             </td>
                       </tr>
                       </table>
                    </td>
                    </tr>
                    </table>
                  </div>              