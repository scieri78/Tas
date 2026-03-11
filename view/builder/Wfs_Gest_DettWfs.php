<?php


include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
        
    $Azione=$_POST['Azione'];
    $Errore=0;
	$Note="";
	
     switch($Azione){        
       case 'AG': 
	   
            $RunPlSql='CALL WFS.K_AUTH.AggiungiGruppo(?, ?, ?, ?, ? )';
            $stmt=db2_prepare($conn, $RunPlSql);
                        
            $AddGrp=$_POST['AddGrp'];
                                    
            db2_bind_param($stmt, 1, "IdWorkFlow" , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "AddGrp"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"     , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"       , DB2_PARAM_OUT);
            
            break;
       case 'RG': 
            $RunPlSql='CALL WFS.K_AUTH.RimuoviGruppo( ?, ?, ?, ?, ?)';
            $stmt=db2_prepare($conn, $RunPlSql);
                
            $DelGr=$_POST['DelGr'];
              
            db2_bind_param($stmt, 1, "IdWorkFlow" , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "DelGr"      , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"     , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"       , DB2_PARAM_OUT);
            break;
     }
     
     if ( "$Azione" != "" ){
         
        $res=db2_execute($stmt);
        
        if ( ! $res) {
          echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
        }
        
        if ( $Errore != 0 ) {
          echo "PLSQL Procedure Calling Error $Errore: ".$Note;
        }
        
		?>
		<script>
		 $('#LoadFls<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_LoadFlussi.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});
        </script>
		<?php
     }  
 
    ?>
    <table class="ExcelTable" >
     <tr>
       <th style="width:50%;" >
         <div id="PulAddGroup<?php echo $IdWorkFlow; ?>" class="Plst" ><img class="ImgIco" src="../images/Aggiungi.png"><img class="ImgIco" src="../images/Gruppo.png"></div>
         <script>
            $("#PulAddGroup<?php echo $IdWorkFlow; ?>").click(function(){
                $('#ADDDiv').empty().load('../PHP/Wfs_Gest_AddGroup.php',{
                       IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                       WorkFlow: '<?php echo $WorkFlow; ?>'
                }).show();  
            }); 
         </script>
       </th>
       <th style="width:50%;" >
          <img class="ImgIco" src="../images/Utente.png">
       </th>
      </tr>
    <?php
    $ListGr="SELECT ID_GRUPPO, GRUPPO, ( SELECT count(*) from WFS.ASS_GRUPPO WHERE ID_GRUPPO = g.ID_GRUPPO ) CONTA from WFS.GRUPPI g where g.ID_WORKFLOW = $IdWorkFlow ";
    $rsGr=db2_prepare($conn, $ListGr);
    $resultTabRead = db2_execute($rsGr); 
    while ($rwGr = db2_fetch_assoc($rsGr)) {
      $IdGruppo=$rwGr['ID_GRUPPO'];
      $Gruppo=$rwGr['GRUPPO'];
      $Conta=$rwGr['CONTA'];
      ?><tr class="bordertop" >
            <th><?php 
            if ( "$Gruppo" != "USER" and "$Gruppo" != "ADMIN" and "$Gruppo" != "READ" and "$Conta" == "0" ){
                ?><div id="PulRmGroup<?php echo $IdWorkFlow; ?>" class="Plst" ><img class="ImgIco" src="../images/Cestino.png"><?php echo $Gruppo; ?></div>
                 <script>
                    $("#PulRmGroup<?php echo $IdWorkFlow; ?>").click(function(){
                        var re = confirm('Do you want delete this Group from <?php echo $Flusso; ?> Flow?');
                        if ( re == true) {  
                            $('#LoadDett<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_DettWfs.php',{
                                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                                   WorkFlow: '<?php echo $WorkFlow; ?>',
                                   Azione: 'RG',
                                   DelGr: '<?php echo $IdGruppo; ?>'
                            });
                        }
                    }); 
                 </script>              
                <?php
            } else {
              echo $Gruppo;
            } 
            ?></th>
            <td>
                <div id="US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>" ></div>
                <div id="PulAddUser<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>" class="Plst Mattone" style="color:red;"><img class="ImgIco" src="../images/Aggiungi.png"></div>
                <script>
                  $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').load('../PHP/Wfs_Gest_LoadUser.php',{
                       IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                       WorkFlow: '<?php echo $WorkFlow; ?>',
                       IdGruppo: '<?php echo $IdGruppo; ?>',
                       Gruppo: '<?php echo $Gruppo; ?>'
                  });
                  $("#PulAddUser<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>").click(function(){
                      $('#ADDDiv').empty().load('../PHP/Wfs_Gest_AddUser.php',{
                           IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                           WorkFlow: '<?php echo $WorkFlow; ?>',
                           IdGruppo: '<?php echo $IdGruppo; ?>',
                           Gruppo: '<?php echo $Gruppo; ?>'
                      }).show();
                  });       
                </script>
            </td>
        </tr><?php
    } ?>
    </table>
    <?php
}

db2_close($conn);
?>