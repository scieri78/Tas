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
    $IdFlusso=$_POST['IdFlusso'];
    $Flusso=$_POST['Flusso'];
    $IdDip=$_POST['IdDip'];
	$Dipendenza=$_POST['Dipendenza'];
        

    $AzioneDipAut=$_POST['AzioneDipAut'];
    $Errore=0;
    $Note="";
    
     switch($AzioneDipAut){
       case 'RAUT': 
            $CallPlSql='CALL WFS.K_AUTH.RimuoviAutorizzazioneDip(?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAut=$_POST['IdAut'];
                
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAut"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);
            break;  
            
        case 'ADG': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiAutorizzazioneDip(?, ?, ?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdGroup=$_POST['IdGroup'];
	        $TipoDip=$_POST['TipoDip'];
        
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlusso"    , DB2_PARAM_IN);
			db2_bind_param($stmt, 3, "TipoDip"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "IdDip"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "IdGroup"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 6, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 7, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 8, "Note"        , DB2_PARAM_OUT);
            break;  
     }
    
     if ( "$AzioneDipAut" != "" ){
        $res=db2_execute($stmt);
        
        if ( ! $res) {
          echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
        }
        
        if ( $Errore != 0 ) {
          echo "PLSQL Procedure Calling Error $Errore: ".$Note;
        }
                
     }
            
    $ListFL="SELECT ID_AUT_DIP,g.GRUPPO 
    from 
    WFS.AUTORIZZAZIONI_DIP a, 
    WFS.GRUPPI g
    where 1=1
    AND a.ID_WORKFLOW = g.ID_WORKFLOW 
    AND a.ID_GRUPPO = g.ID_GRUPPO 
    AND a.ID_WORKFLOW = $IdWorkFlow
    AND a.ID_FLU = $IdFlusso
    AND a.ID_DIP = $IdDip
    AND g.GRUPPO != 'ADMIN'
    ORDER BY g.GRUPPO";

    $rsFL=db2_prepare($conn, $ListFL);
    $resultTabRead = db2_execute($rsFL); 
    $CntFL=db2_num_rows($rsFL);
    while ( $rwFL = db2_fetch_assoc($rsFL) ) {      
      $IdAut=$rwFL['ID_AUT_DIP'];
      $Gruppo=$rwFL['GRUPPO'];
      ?>
      <div id="PulRemDipGroup<?php echo $IdAut; ?>" name="PulRemDipGroup<?php echo $IdAut; ?>"  class="Plst Mattone" >
         <?php 
         //if ( $CntFL > 1 ) {
            ?><img class="ImgIco" src="../images/Cestino.png"><?php
         //}
         echo $Gruppo; 
         ?>
      </div>
      <script>
         $('#PulRemDipGroup<?php echo $IdAut; ?>').click(function(){
                var re = confirm('Do you want delete this Group from the <?php echo $Dipendenza; ?> Flow?');
                if ( re == true) {  
                  $('#ShowAuthDip_<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>').empty().load('../PHP/Wfs_Gest_LoadAuthDip.php',{
                       IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                       WorkFlow: '<?php echo $WorkFlow; ?>',
                       IdFlusso: '<?php echo $IdFlusso; ?>',
                       IdDip: '<?php echo $IdDip; ?>',
                       Flusso: '<?php echo $Flusso; ?>',
                       IdAut: '<?php echo $IdAut; ?>',
                       AzioneDipAut: 'RAUT'
                  });
                }
         });
      </script>
      <?php
    }
}
?>

