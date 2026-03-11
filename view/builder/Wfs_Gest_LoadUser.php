<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
    $IdGruppo=$_POST['IdGruppo'];
    

    $Azione=$_POST['Azione'];
    $Errore=0;
	$Note="";
	
     switch($Azione){
       case 'AU': 
            $CallPlSql='CALL WFS.K_AUTH.AssegnaUtente(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdUsr=$_POST['IdUsr'];
				
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdGruppo"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdUsr"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
            break;
       case 'ANU': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiNuovoUtente(?, ?, ?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
          
            $AddUserName=$_POST['AddUserName'];
            $AddNome=$_POST['AddNome'];
            $AddCognome=$_POST['AddCognome'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdGruppo"    , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "AddUserName" , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "AddNome"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "AddCognome"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
            break;
        case 'DU': 
            $CallPlSql='CALL WFS.K_AUTH.RevocaUtente(?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAss=$_POST['IdAss'];
            
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAss"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);
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
		 if ( $res and $Errore == 0 ) {
		?>
		<script>
		 $('#LoadDett<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_DettWfs.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});
		 $('#LoadFls<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_LoadFlussi.php',{
				   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
				   WorkFlow: '<?php echo $WorkFlow; ?>'
			});
        </script>
		<?php
		 }
        
     }
    
    $ListU="SELECT w.ID_ASS,u.USERNAME USERNAME,u.NOMINATIVO NOMINATIVO
    from WEB.TAS_UTENTI u,
         WFS.ASS_GRUPPO  w
    where 1=1 
         and w.ID_UK = u.UK
         and w.ID_GRUPPO = '$IdGruppo'                                   
         ";
    $rsU=db2_prepare($conn, $ListU);
    $resultTabRead = db2_execute($rsU); 
    $CntU=db2_num_rows($rsU);
    while ($rwU = db2_fetch_assoc($rsU)) {      
      $IdAss=$rwU['ID_ASS'];
      $Nm=$rwU['USERNAME'];
      $Nom=$rwU['NOMINATIVO'];
      $PosDel=false;
      ?><div id="PlRmUsr<?php echo $IdAss; ?>" class="Plst Mattone" >
         <?php 
         if ( "$Gruppo" == "ADMIN" ){
             if ( $CntU > 1 ) {
                ?><img class="ImgIco" src="../images/Cestino.png"><?php
                $PosDel=true;
             }
         } else {
            ?><img class="ImgIco" src="../images/Cestino.png"><?php 
            $PosDel=true;
         }
         echo "$Nm - $Nom"; ?>
      </div>
      <?php 
      if ( $PosDel ) { ?>
          <script>
             $("#PlRmUsr<?php echo $IdAss; ?>").click(function(){
                    var re = confirm('Do you want delete this User from <?php echo $Gruppo; ?> group ?');
                    if ( re == true) {  
                      $('#US_<?php echo $IdWorkFlow.'_'.$IdGruppo; ?>').empty().load('../PHP/Wfs_Gest_LoadUser.php',{
                           IdWorkFlow: <?php echo $IdWorkFlow; ?>,
                           WorkFlow: '<?php echo $WorkFlow; ?>',
                           IdGruppo: <?php echo $IdGruppo; ?>,
                           IdAss: <?php echo $IdAss; ?>,
                           Azione: 'DU'                        
                      });
                    }
             });     
          </script>
          <?php
      }
    }
}
db2_close($db2_conn_string);
?>
