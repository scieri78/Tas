<style>
.ShLine{
    border-left:0px;
    border-right:0px;
    border-bottom: 1px solid black;
    padding:5px;
    padding-bottom:10px;
}
.Puls{
    cursor:pointer;
}
.LabelMails{
    float:left;
    margin:5px;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {
    $ListDett=$_POST['ListDett'];
    
    $AddNewMail=$_POST['AddNewMail'];
    if ( "$AddNewMail" == "AddNewMail" ){
        $ID_SH=$_POST['AddShMail'];
        $ID_MAIL_ANAG=$_POST['EnableMail']; 
        $FLG_START=$_POST['AddStart'];
        $FLG_END=$_POST['AddEnd'];
        
        $sql = "INSERT INTO WORK_CORE.CORE_ALERT_MAIL(ID_SH, ID_MAIL_ANAG, FLG_START, FLG_END, VALID) VALUES($ID_SH, $ID_MAIL_ANAG, '$FLG_START', '$FLG_END', 'Y')";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  AddNewMail".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['AddNewMail']);        
    }           

    $AddInNewMail=$_POST['AddInNewMail'];
    if ( "$AddInNewMail" == "AddInNewMail" ){
        $SelId=$_POST['SelId'];
        $ID_SH=$_POST['AddInShMail'.$SelId];
        $ID_MAIL_ANAG=$_POST['AddInMail'.$SelId]; 
        $FLG_START=$_POST['AddInStart'.$SelId];
        $FLG_END=$_POST['AddInEnd'.$SelId];
        
        $sql = "INSERT INTO WORK_CORE.CORE_ALERT_MAIL(ID_SH, ID_MAIL_ANAG, FLG_START, FLG_END, VALID) VALUES($ID_SH, $ID_MAIL_ANAG, '$FLG_START', '$FLG_END', 'Y')";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  AddInNewMail".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['AddInNewMail']);      
    }   
    
    $EnableMail=$_POST['EnableMail'];
    if ( "$EnableMail" == "EnableMail" ){
        $SelId=$_POST['EnIdSh'];
        $EnIdSh=$_POST['EnIdSh'];
        $EnType=$_POST['EnType'];
        $EnIdMailAnag=$_POST['EnIdMailAnag'];
        
        $sql = "UPDATE WORK_CORE.CORE_ALERT_MAIL SET";      
        if ( "$EnType" == "Start" ){ 
          $sql = $sql." FLG_START = 'Y' ";
        }else{
          $sql = $sql." FLG_END = 'Y' ";
        }
        $sql = $sql." WHERE ID_SH = $EnIdSh AND ID_MAIL_ANAG = $EnIdMailAnag ";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  EnableMail".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['EnableMail']);
        
    }
     
    $DisableMail=$_POST['DisableMail'];
    if ( "$DisableMail" == "DisableMail" ){
        
        $SelId=$_POST['DisIdSh'];
        $DisIdSh=$_POST['DisIdSh'];
        $DisType=$_POST['DisType'];
        $DisIdMailAnag=$_POST['DisIdMailAnag'];
        
        $sql = "UPDATE WORK_CORE.CORE_ALERT_MAIL SET";      
        if ( "$DisType" == "Start" ){ 
          $sql = $sql." FLG_START = 'N' ";
        }else{
          $sql = $sql." FLG_END = 'N' ";
        }
        $sql = $sql." WHERE ID_SH = $DisIdSh AND ID_MAIL_ANAG = $DisIdMailAnag ";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  DisableMail".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['DisableMail']);                   
    }  

    $RemoveMail=$_POST['RemoveMail'];
    if ( "$RemoveMail" == "RemoveMail" ){
        $SelId=$_POST['RemIdSh'];
        $RemIdSh=$_POST['RemIdSh'];
        $RemIdMailAnag=$_POST['RemIdMailAnag'];
        
        $sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = $RemIdSh AND ID_MAIL_ANAG = $RemIdMailAnag ";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  RemoveMail".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['RemoveMail']);                    
    }      
     
    $RemoveSh=$_POST['RemoveSh'];
    if ( "$RemoveSh" == "RemoveSh" ){

        $RemIdSh=$_POST['RemIdSh'];
        
        $sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = $RemIdSh ";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2:  RemoveSh".db2_stmt_errormsg($stmt).':'.$sql;
        }       
        db2_close($db2_conn_string);
        unset($_POST['RemoveSh']);                  
            
    }      
      
       

    $ListMail=array();
    $sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY NAME";
    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo "ERROR DB2:  list mail".db2_stmt_errormsg($stmt).':'.$sql;
    }
    db2_close($db2_conn_string);
    while ($row = db2_fetch_assoc($stmt)) {
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $MAIL=$row['MAIL'];
        $NAME=$row['NAME'];
        $USERNAME=$row['USERNAME'];
        array_push($ListMail, array("$ID_MAIL_ANAG","$MAIL","$NAME","$USERNAME") );
    }


    ?>
    
    <form id="FormMail" method="POST">
    <table class="ExcelTable" >
    <tr>
      <th><input type="submit" value="REFRESH" class="Bottone" style="background:yellow;" ></th>
      <th>PATH</th>
      <th>SHELL</th>
      <th>EMAIL</th>
      <th>START</th>
      <th>END</th>
      <th></th>
    </tr>
    <tr>
        <th>Enable New Shell Alert </th>
        <td colspan=2 >
        <select name="AddShMail" id="AddShMail" >
            <option value=".." >..</option>
            <?php
            $sql = "SELECT ID_SH, SHELL, SHELL_PATH
            FROM WORK_CORE.CORE_SH_ANAG 
            WHERE ID_SH NOT IN ( SELECT ID_SH FROM WORK_CORE.CORE_ALERT_MAIL )
            ORDER BY SHELL_PATH, SHELL";

            $conn = db2_connect($db2_conn_string, '', '');
            $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            
            if ( ! $result ){
                echo "ERROR DB2: ".db2_stmt_errormsg($stmt).':'.$sql;
            }
            
            db2_close($db2_conn_string);
          
            while ($row = db2_fetch_assoc($stmt)) {
                $ID_SH=$row['ID_SH'];
                $SHELL=$row['SHELL'];
                $SHELL_PATH=$row['SHELL_PATH'];
                ?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL_PATH.'/'.$SHELL; ?></option><?php
            }
            ?>
        </select>
        </td>
        <td>
             <select  id="EnableMail"  name="EnableMail" style="width:100%;" >
                <option value=".." >..</option>
                <?php
                foreach( $ListMail as $DettMail ){
                    $IdMail=$DettMail[0];
                    $Mail=$DettMail[1];
                    $NameMail=$DettMail[2];
                    $UsrMail=$DettMail[3];
                    //if( ! in_array($IdMail ,$ListIdFind) ){
                        ?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
                    //}
                }
                ?>
             </select>
        </td>
        <td>
             <select name="AddStart" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>
        <td>
             <select name="AddEnd" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>       
        <td><img src="../images/Aggiungi.png" width="25px" onclick="AddNewMail();"></td>
    </tr>   
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
    <tr>
      <th></th>
      <th>PATH</th>
      <th>SHELL</th>
      <th>EMAIL</th>
      <th>START</th>
      <th>END</th>
      <th></th>
    </tr>   
    <?php
    
    $sql="SELECT CAM.ID_SH ID_SH, SHELL, SHELL_PATH, CAM.ID_MAIL_ANAG, FLG_START, FLG_END, CMA.MAIL
    FROM WORK_CORE.CORE_ALERT_MAIL  CAM
    JOIN WORK_CORE.CORE_SH_ANAG CSA
    ON CAM.ID_SH = CSA.ID_SH
    JOIN WORK_CORE.CORE_MAIL_ANAG CMA
    ON CAM.ID_MAIL_ANAG = CMA.ID_MAIL_ANAG
    ORDER BY SHELL_PATH, SHELL, CMA.MAIL
    ";

    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    
    if ( ! $result ){
        echo "ERROR DB2: ";
    }
    
    db2_close($db2_conn_string);
  
    $PREC_ID_SH=0;
    while ($row = db2_fetch_assoc($stmt)) {
        $ID_SH=$row['ID_SH'];
        $SHELL=$row['SHELL'];
        $SHELL_PATH=$row['SHELL_PATH'];
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $FLG_START=$row['FLG_START'];
        $FLG_END=$row['FLG_END'];
        $MAIL=$row['MAIL'];
        
        if ( "$ID_SH" != "$PREC_ID_SH" ){
            if ( "$PREC_ID_SH" != "0" ){
            ?>
                    <tr class="Dett<?php echo $OLD_ID_SH; ?>" hidden>
                        <th colspan=3>
                           <input type="hidden" id="AddInShMail<?php echo $OLD_ID_SH; ?>" name="AddInShMail<?php echo $OLD_ID_SH; ?>" value="<?php echo $OLD_ID_SH; ?>" > 
                        </th>
                        <td>
                             <select  id="AddInMail<?php echo $OLD_ID_SH; ?>"  name="AddInMail<?php echo $OLD_ID_SH; ?>" style="width:100%;" >
                                <option value=".." >..</option>
                                <?php
                                foreach( $ListMail as $DettMail ){
                                    $IdMail=$DettMail[0];
                                    $Mail=$DettMail[1];
                                    $NameMail=$DettMail[2];
                                    $UsrMail=$DettMail[3];
                                    //if( ! in_array($IdMail ,$ListIdFind) ){
                                        ?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
                                    //}
                                }
                                ?>
                             </select>
                        </td>
                        <td>
                             <select name="AddInStart<?php echo $OLD_ID_SH; ?>" >
                                <option value="Y" >Yes</option>
                                <option value="N" >No</option>
                             </select>      
                        </td>
                        <td>
                             <select name="AddInEnd<?php echo $OLD_ID_SH; ?>" >
                                <option value="Y" >Yes</option>
                                <option value="N" >No</option>
                             </select>      
                        </td>       
                        <td><img src="../images/Aggiungi.png" width="25px" onclick="AddInNewMail(<?php echo $OLD_ID_SH; ?>);"></td>
                
                     </tr>   
                   <?php 
            }
            ?>
            <tr>
                <td class="ShLine"  style="border-top: 1px solid black;" >
                  <img src="../images/Cestino.png" width="25px" class="Puls" onclick="RemoveSh('<?php echo $ID_SH; ?>')">
                </td>
                <td class="ShLine" style="border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')"><?php echo $SHELL_PATH; ?></td>
                <td  class="ShLine Puls" style="border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')">
                    <b><?php echo $SHELL; ?></b>
                </td>
                <td colspan=4 >
                </td>
            </tr>   
            <?php 
            $PREC_ID_SH=$ID_SH;
        }           
        ?>
        <tr class="Dett<?php echo $ID_SH; ?>" hidden >
            <th colspan=3></th>
            <td><?php echo $MAIL; ?></td>
            <td>
            <?php
            if ( "$FLG_START" == "N" ){
              ?><img src="../images/Add.png" width="25px" class="Puls" onclick="FEnableMail(<?php echo $ID_SH; ?>,'Start',<?php echo $ID_MAIL_ANAG; ?>)"><?php
            }else{
              ?><img src="../images/Remove.png" width="25px" class="Puls" onclick="FDisableMail(<?php echo $ID_SH; ?>,'Start',<?php echo $ID_MAIL_ANAG; ?>)"><?php
            }
            ?>
            </td>
            <td>
            <?php
            if ( "$FLG_END" == "N" ){
              ?><img src="../images/Add.png" width="25px" class="Puls" onclick="FEnableMail(<?php echo $ID_SH; ?>,'End',<?php echo $ID_MAIL_ANAG; ?>)"><?php
            }else{
              ?><img src="../images/Remove.png" width="25px" class="Puls" onclick="FDisableMail(<?php echo $ID_SH; ?>,'End',<?php echo $ID_MAIL_ANAG; ?>)"><?php
            }
            ?>
            </td>
            <td style="border-top: 1px solid black;" ><img src="../images/Remove.png" width="25px" class="Puls" onclick="RemoveMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>)">
            </td>
         </tr>   
       <?php 
        $OLD_ID_SH=$row['ID_SH'];
        $OLD_SHELL=$row['SHELL'];
        $OLD_SHELL_PATH=$row['SHELL_PATH'];
        $OLD_ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $OLD_FLG_START=$row['FLG_START'];
        $OLD_FLG_END=$row['FLG_END'];
        $OLD_MAIL=$row['MAIL'];    
    }
    if( "$PREC_ID_SH" != "0" ){
    ?>
    <tr class="Dett<?php echo $OLD_ID_SH; ?>" hidden>
        <th colspan=3>
           <input type="hidden" id="AddInShMail<?php echo $OLD_ID_SH; ?>" name="AddInShMail<?php echo $OLD_ID_SH; ?>" value="<?php echo $OLD_ID_SH; ?>" > 
        </th>
        <td>
             <select  id="AddInMail<?php echo $OLD_ID_SH; ?>"  name="AddInMail<?php echo $OLD_ID_SH; ?>" style="width:100%;" >
                <option value=".." >..</option>
                <?php
                foreach( $ListMail as $DettMail ){
                    $IdMail=$DettMail[0];
                    $Mail=$DettMail[1];
                    $NameMail=$DettMail[2];
                    $UsrMail=$DettMail[3];
                    //if( ! in_array($IdMail ,$ListIdFind) ){
                        ?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
                    //}
                }
                ?>
             </select>
        </td>
        <td>
             <select name="AddInStart<?php echo $OLD_ID_SH; ?>" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>
        <td>
             <select name="AddInEnd<?php echo $OLD_ID_SH; ?>" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>       
        <td><img src="../images/Aggiungi.png" width="25px" onclick="AddInNewMail(<?php echo $OLD_ID_SH; ?>);"></td>

     </tr>   
     <?php
    }
    ?>
    </table>
    <input type="hidden" name="ListDett" id="ListDett" value="<?php echo $ListDett; ?>">
    </form>
    <SCRIPT>
       $('.Dett<?php echo $SelId; ?>').show();
       
       vLOpen=$('#ListDett').val().split(' ');
        $.each( vLOpen, function( i, l ){
          $('.Dett'+l).show();
        });
       
       function TrDett(vIdSh){
           if( $('.Dett'+vIdSh).is(':visible') ){
                $('.Dett'+vIdSh).hide();
                vlistOpen=$('#ListDett').val();
                vlistOpen=vlistOpen.replace(' '+vIdSh,'');
                $('#ListDett').val(vlistOpen);
           }else{
                $('.Dett'+vIdSh).show();
                vlistOpen=$('#ListDett').val();
                vlistOpen=vlistOpen+' '+vIdSh;
                $('#ListDett').val(vlistOpen);
           }
       }
       
       function AddNewMail(){
          if ( $('#AddShMail').val() == '..' || $('#EnableMail').val() == '..' ){
              alert('Empty Field!');
          }else{              
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "AddNewMail")
            .val('AddNewMail');
            $('#FormMail').append($(input));
            $('#FormMail').submit();
          }
       }
       
       function AddInNewMail(vId){
          if ( $('#AddInShMail'+vId).val() == '..' || $('#AddInMail'+vId).val() == '..' ){
              alert('Empty Field!');
          }else{              
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "AddInNewMail")
            .val('AddInNewMail');
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "SelId")
            .val(vId);
            $('#FormMail').append($(input));
            
            $('#FormMail').submit();
          }
       }
       
       
       function FEnableMail(vIdSh,vType,vIdMailAnag){
           
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnableMail")
            .val('EnableMail');
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnIdSh")
            .val(vIdSh);
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnType")
            .val(vType);
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnIdMailAnag")
            .val(vIdMailAnag);
            $('#FormMail').append($(input));
            
            $('#FormMail').submit();
       }
       
       function FDisableMail(vIdSh,vType,vIdMailAnag){
           
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisableMail")
            .val('DisableMail');
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisIdSh")
            .val(vIdSh);
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisType")
            .val(vType);
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisIdMailAnag")
            .val(vIdMailAnag);
            $('#FormMail').append($(input));
            
            $('#FormMail').submit();
       }
       
       
       function RemoveMail(vIdSh,vIdMailAnag){
           
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemoveMail")
            .val('RemoveMail');
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemIdSh")
            .val(vIdSh);
            $('#FormMail').append($(input));

            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemIdMailAnag")
            .val(vIdMailAnag);
            $('#FormMail').append($(input));
            
            $('#FormMail').submit();
       }

       function RemoveSh(vIdSh){
           
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemoveSh")
            .val('RemoveSh');
            $('#FormMail').append($(input));
            
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemIdSh")
            .val(vIdSh);
            $('#FormMail').append($(input));

            $('#FormMail').submit();
       }
       
     </SCRIPT>
    <?php
   
}

?>
