<li id="<?php echo $Rt_ROUTINE; ?>" >
                   <?php
                   if ( $Rt_CNT_SON != "0" ){
                    ?><label class="EsplodiPLSQL" >o</label><?php 
                   }
                   ?>
                   <a id="<?php echo $Rt_ROUTINE; ?>" >
                   <table class="ExcelTable L4Schema Tabella10" style="box-shadow: 0px 0px 0px 1px black; height:35px;">
                   <tr>
                        <th class="<?php echo $RtEsito; ?>" style="width:8px;" ></th>
                        <th><b>schema</b></th>
                        <td><?php echo $Rt_SCHEMA; ?></td>
                        <?php if ( "$Rt_PACKAGE" != "" ){ ?>
                        <th><b>package</b></th>
                        <td><?php echo $Rt_PACKAGE; ?></td>
                        <td>
                        <img src="./images/PlsqlTab.png" onclick="apriTabPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;">
                        <img src="./images/PlsqlDb.png"  onclick="apriPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;">
                        </td>
                        <?php } ?>
                        <th><b>routine</b></th>
                        <td><?php echo $Rt_ROUTINE; ?></td>
                        <th><b>start</b></th>
                        <td><?php echo $Rt_START; ?></td>
                        <th><b>end</b></th>
                        <td><?php echo $Rt_END; ?></td>
                        <th><b>time</b></th>
                        <td><?php echo gmdate('H:i:s', $Rt_DIFF); ?></td>
                        <th><b>note</b></th>
                        <td><?php echo "$Rt_NOTES"; ?></td>
                   </tr>
                   </table>
                   </a>
                   <?php
                   if ( $Rt_CNT_SON != "0" ){
                    SearchSonRoutine($conn, $ListIdOpen,$IdRunSql,$IdRunSh,$Rt_FATHER2,$Status,$_modelshell);
                   }
                   ?>
                </li>