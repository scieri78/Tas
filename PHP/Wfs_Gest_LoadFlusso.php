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
		

    $AzioneAut=$_POST['AzioneAut'];
    $Errore=0;
	$Note="";
	
     switch($AzioneAut){
       case 'RAUT': 
            $CallPlSql='CALL WFS.K_AUTH.RimuoviAutorizzazione(?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdAut=$_POST['IdAut'];
				
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdAut"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 5, "Note"        , DB2_PARAM_OUT);
            break;	
			
		case 'ADG': 
            $CallPlSql='CALL WFS.K_AUTH.AggiungiAutorizzazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);
                
            $IdGroup=$_POST['IdGroup'];
		
            db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdFlusso"    , DB2_PARAM_IN);
			db2_bind_param($stmt, 3, "IdGroup"     , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
            break;	
	 }
	
     if ( "$AzioneAut" != "" ){
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
			
	$ListFL="SELECT ID_AUT,g.GRUPPO 
	from 
	WFS.AUTORIZZAZIONI a, 
	WFS.GRUPPI g
	where 1=1
	AND a.ID_WORKFLOW = g.ID_WORKFLOW 
	AND a.ID_GRUPPO = g.ID_GRUPPO 
	AND a.ID_WORKFLOW = '$IdWorkFlow' 
	AND a.ID_FLUSSO = '$IdFlusso' 
	AND g.GRUPPO != 'ADMIN'
	ORDER BY g.GRUPPO";

	$rsFL=db2_prepare($conn, $ListFL);
    $resultTabRead = db2_execute($rsFL); 
	$CntFL=db2_num_rows($rsFL);
	while ( $rwFL = db2_fetch_assoc($rsFL) ) {      
	  $IdAut=$rwFL['ID_AUT'];
	  $Gruppo=$rwFL['GRUPPO'];
	  ?>
	  <div id="PulRemGroup<?php echo $IdAut; ?>" class="Plst Mattone" >
		 <?php 
		 //if ( $CntFL > 1 ) {
			?><img class="ImgIco" src="../images/Cestino.png"><?php
		 //}
		 echo $Gruppo; 
		 ?>
	  </div>
	  <script>
	     $("#PulRemGroup<?php echo $IdAut; ?>").click(function(){
			    var re = confirm('Do you want delete this Group from <?php echo $Flusso; ?> Flow?');
				if ( re == true) {  
				  $('#ShowFlu_<?php echo $IdWorkFlow.$IdFlusso; ?>').load('../PHP/Wfs_Gest_LoadFlusso.php',{
					   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
					   WorkFlow: '<?php echo $WorkFlow; ?>',
					   IdFlusso: '<?php echo $IdFlusso; ?>',
					   Flusso: '<?php echo $Flusso; ?>',
					   IdAut: '<?php echo $IdAut; ?>',
					   AzioneAut: 'RAUT'
				  });
				}
		 });
	  </script>
	  <?php
	}
}
?>

