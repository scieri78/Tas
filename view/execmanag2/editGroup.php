<?php


    $EditGroup=$_POST['EditGroup'];
    if ( "$EditGroup" != "" ){
        foreach( $Arr_Groups as $Group ){
           $ObjId=$Group[0];
           if ( "$ObjId" == "$EditGroup" ){
                 $SelNameGroup=$Group[1];
                 $SelEnable   =$Group[2];
                 $SelValidFrom=$Group[3];
                 $SelValidTo  =$Group[4];
                 $SelStatus   =$Group[5];
                 $SelStartTime=$Group[6];
                 $SelEndTime  =$Group[7];
                 $SelCreator  =$Group[8];
                 $SelBckGrnd  =$Group[10];
                 $SelDescrGroup  =$Group[12];
                 $SelIdFather  =$Group[13];
           }
        }

                
          $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $EditGroup";
          $stmtLink=db2_prepare($conn, $sqlLink);
          $resultLink=db2_execute($stmtLink);

          if ( ! $resultLink ){
              echo $sqlLink;
              echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
          }

          $FindLinks=0;
          $Arr_Links=array();
          while ($rowLink = db2_fetch_assoc($stmtLink)) {
            $IdLink=$rowLink['ID_EM_GROUP_LINK'];
            array_push($Arr_Links, $IdLink);
          }
           
       ?>
       <div id="EditDiv" class="centerDiv" >
            <div class="Title" ><img src="./images/Folder.png" width="25px" >Edit <?php echo "$SelNameGroup"; ?></div>
            <table class="center">
            <td style="width:50%;" >
            <table class="center">
              <tr><th>Name</th><td>
              <input type="hidden" name="SelIdEmGroup" id="SelIdEmGroup" value="<?php echo "$EditGroup"; ?>" >
              <input type="text" name="SelNameGroup" id="SelNameGroup" value="<?php echo "$SelNameGroup"; ?>" >
              </td></tr>
              <tr><th>Description</th><td>
              <input type="text" name="SelDescrGroup" id="SelDescrGroup" value="<?php echo "$SelDescrGroup"; ?>" >
              </td></tr>              
              <tr><th>Enable</th><td>
              <select name="SelEnable" id="SelEnable" >
                    <option value="Y" <?php if ( "$SelEnable" == "Y" ) { ?> selected <?php } ?> >Enable</option>
                    <option value="N" <?php if ( "$SelEnable" == "N" ) { ?> selected <?php } ?> >Disable</option>
              </select>
              </td></tr>              
              <tr><th>Exec Next</th><td>
              <select name="SelBckGrnd" id="SelBckGrnd" >
                    <option value="N" <?php if ( "$SelBckGrnd" == "N" ) { ?> selected <?php } ?> >Disable</option>
                    <option value="Y" <?php if ( "$SelBckGrnd" == "Y" ) { ?> selected <?php } ?> >Enable</option>
              </select>
              </td></tr>
              <tr><th>ValidFrom</th><td><input type="date" name="SelValidFrom" id="SelValidFrom" value="<?php echo "$SelValidFrom"; ?>" ></td></tr>
              <tr><th>ValidTo</th><td><input type="date" name="SelValidTo" id="SelValidTo" value="<?php echo "$SelValidTo"; ?>" ></td></tr>
              </td></tr>
           </table>
           </td>
           <td style="width:50%;" >
           <table class="center">
              <tr><th colspan=2 >Son of</th></tr>
                  <tr><td colspan=2 ><select name="SelFather" id="SelFather" >
                <option value="0" <?php if ( "$SelIdFather" == "-1" ) { ?> selected <?php } ?> >Nothing</option>
                <?php
                foreach( $Arr_Groups as $Group2 ){
                  $IdEmGroup2=$Group2[0];
                  $NameGroup2=$Group2[1];
                  $Enable2   =$Group2[2];
                  $DescrGroup2  =$Group2[12];
                  if ( "$IdEmGroup2" != "$EditGroup"){
                      ?><option value="<?php echo $IdEmGroup2; ?>" <?php if ( "$SelIdFather" == "$IdEmGroup2" ) { ?> selected <?php } ?> ><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></option><?php
                  }
                } 
                ?>
              </select>
              </td></tr>
              <tr class="NoDep"><th colspan=2 >Dependent to</th></tr>
              <tr class="NoDep">
              <td colspan=2 >
                <div class="CheckLink" >
                <table width="100%">
                <?php
                foreach( $Arr_Groups as $Group2 ){
                  $IdEmGroup2=$Group2[0];
                  $NameGroup2=$Group2[1];
                  $Enable2   =$Group2[2];
                  $DescrGroup2  =$Group2[12];
                  $Checked="";
                  if ( in_array( $IdEmGroup2, $Arr_Links ) ){ $Checked="Checked"; }
                  if ( $IdEmGroup2 != $EditGroup && $IdEmGroup2 != $SelIdFather ){
                    ?>
                    <tr>
                    <td width="20px"><input type="checkbox" name="Check<?php echo $IdEmGroup2; ?>" id="Check<?php echo $IdEmGroup2; ?>" value="<?php echo $IdEmGroup2; ?>" <?php echo $Checked; ?> ></td>
                    <td><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
                </table>
                </div>
              </td></tr>
            </table>
            </td>
            </tr>
            </table>
            <center>
            <input class="Button" type="submit" id="SaveEdit" name="SaveEdit" value="Save" >
            <input class="Button" id="CancelEdit" type="submit" value="Cancel" >
            </center>
       </div>
       <?php
    }
?>