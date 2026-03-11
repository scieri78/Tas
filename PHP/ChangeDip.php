<style>
.ModSt{ width:100px;}
</style>
<?php
$SalvaChange=$_POST['SalvaChange'];
$SelIdTeam=$_POST['SelIdTeam'];
$SelWkf=$_POST['SelWkf'];
$SelFls=$_POST['SelFls'];
$SelTp=$_POST['SelTp'];
$SelDp=$_POST['SelDp'];
$SelIdProc=$_POST['SelIdProc'];
$SelSt=$_POST['SelSt'];
$HideSave="no";
if ( "$SalvaChange" == "Salva" ){
    
    $InErr=0;  
    if ( "$SelSt" == "F" ){
      $sql="SELECT count(*) CNT FROM WFS.ULTIMO_STATO  
         WHERE 1=1
         AND ID_WORKFLOW = $SelWkf
         AND ID_PROCESS  = $SelIdProc
         AND ID_FLU      = $SelFls
         AND TIPO        = '$SelTp'
         AND ID_DIP      = $SelDp   ";
         $stmt=db2_prepare($conn, $sql);
         $result=db2_execute($stmt);
         while ($row = db2_fetch_assoc($stmt)) {
           $vCnt=$row['CNT'];   
      }
      
      if ( "$vCnt" == "0" ){
         $Sql="INSERT INTO WFS.ULTIMO_STATO(
               ID_PROCESS
               ,ID_WORKFLOW
               ,ID_FLU
               ,TIPO
               ,ID_DIP
               ,INIZIO
               ,FINE
               ,ESITO
               ,NOTE
               ,LOG
               ,FILE
               ,UTENTE
               )
               VALUES(
               $SelIdProc
               ,$SelWkf
               ,$SelFls
               ,'$SelTp'
               ,$SelDp
               ,CURRENT_TIMESTAMP
               ,CURRENT_TIMESTAMP
               ,'F'
               ,'Valido Step Forzato'
               ,null
               ,null
               ,'$User'
               ) 
            ";
         $stmt = db2_prepare($conn, $Sql);
         
         $result=db2_execute($stmt);
         if ( ! $result ){
           echo "ERROR DB2 Save New:".db2_stmt_errormsg();
           $InErr=1;
         }     
      }else{
        $Sql="UPDATE WFS.ULTIMO_STATO SET
            INIZIO = CURRENT_TIMESTAMP
           ,FINE   = CURRENT_TIMESTAMP
           ,ESITO  = 'F'
           ,WARNING  = 0
           ,NOTE   = 'N'
           ,UTENTE = '$User'
           WHERE 1=1
           AND ID_WORKFLOW = $SelWkf
           AND ID_PROCESS  = $SelIdProc
           AND ID_FLU      = $SelFls
           AND TIPO        = '$SelTp'
           AND ID_DIP      = $SelDp   
           ";
        $stmt = db2_prepare($conn, $Sql);
        
        $result=db2_execute($stmt);
        if ( ! $result ){
          echo "ERROR DB2 Save New:".db2_stmt_errormsg();
          $InErr=1;
        }      
      }  
      $Sql="INSERT INTO WFS.CODA_STORICO(
        ID_PROCESS
        ,ID_WORKFLOW
        ,ID_FLU
        ,TIPO
        ,ID_DIP
        ,AZIONE
        ,UTENTE
        ,NOTE
        ,ESITO
        ,INIZIO
        ,FINE
        ,LOG
        ,FILE
        )
        VALUES (
         $SelIdProc
        ,$SelWkf
        ,$SelFls
        ,'$SelTp'
        ,$SelDp
        ,'F'
        ,'$User'
        ,'Valido Step Forzato'
        ,'F'
        ,CURRENT_TIMESTAMP
        ,CURRENT_TIMESTAMP
        ,null
        ,null
        )   
        ";
      $stmt = db2_prepare($conn, $Sql);

      $result=db2_execute($stmt);
      if ( ! $result ){
        echo "ERROR DB2 Save New:".db2_stmt_errormsg();
        $InErr=1;
      }  
   } 
   if ( "$SelSt" == "N" ){
 
      $Sql="UPDATE WFS.ULTIMO_STATO SET
          INIZIO = CURRENT_TIMESTAMP
         ,FINE   = CURRENT_TIMESTAMP
         ,ESITO  = 'N'
         ,WARNING  = 0
         ,NOTE   = 'Svalido Step Forzato'
         ,UTENTE = '$User'
         WHERE 1=1
         AND ID_WORKFLOW = $SelWkf
         AND ID_PROCESS  = $SelIdProc
         AND ID_FLU      = $SelFls
         AND TIPO        = '$SelTp'
         AND ID_DIP      = $SelDp   
         ";
      $stmt = db2_prepare($conn, $Sql);

      $result=db2_execute($stmt);
      if ( ! $result ){
        echo "ERROR DB2 Save New:".db2_stmt_errormsg();
        $InErr=1;
      }      
          
      $Sql="INSERT INTO WFS.CODA_STORICO(
        ID_PROCESS
        ,ID_WORKFLOW
        ,ID_FLU
        ,TIPO
        ,ID_DIP
        ,AZIONE
        ,UTENTE
        ,NOTE
        ,ESITO
        ,INIZIO
        ,FINE
        ,LOG
        ,FILE
        )
        VALUES (
         $SelIdProc
        ,$SelWkf
        ,$SelFls
        ,'$SelTp'
        ,$SelDp
        ,'S'
        ,'$User'
        ,'Svalido Step Forzato'
        ,'N'
        ,CURRENT_TIMESTAMP
        ,CURRENT_TIMESTAMP
        ,null
        ,null
        )   
        ";
      $stmt = db2_prepare($conn, $Sql);

      $result=db2_execute($stmt);
      if ( ! $result ){
        echo "ERROR DB2 Save New:".db2_stmt_errormsg();
        $InErr=1;
      }  
      if ( "$InErr" == "0" ){ $HideSave="si"; }
  }
 
  if ( "$SelSt" == "R" ){
 
      $Sql="UPDATE WFS.ULTIMO_STATO SET 
          WARNING  = 0
         ,UTENTE = '$User'
         WHERE 1=1
         AND ID_WORKFLOW = $SelWkf
         AND ID_PROCESS  = $SelIdProc
         AND ID_FLU      = $SelFls
         AND TIPO        = '$SelTp'
         AND ID_DIP      = $SelDp   
         ";
      $stmt = db2_prepare($conn, $Sql);

      $result=db2_execute($stmt);
      if ( ! $result ){
        echo "ERROR DB2 Save New:".db2_stmt_errormsg();
        $InErr=1;
      }      
            
      $Sql="INSERT INTO WFS.CODA_STORICO(
        ID_PROCESS
        ,ID_WORKFLOW
        ,ID_FLU
        ,TIPO
        ,ID_DIP
        ,AZIONE
        ,UTENTE
        ,NOTE
        ,ESITO
        ,INIZIO
        ,FINE
        ,LOG
        ,FILE
        )
        VALUES (
         $SelIdProc
        ,$SelWkf
        ,$SelFls
        ,'$SelTp'
        ,$SelDp
        ,'R'
        ,'$User'
        ,'Reset Warning'
        ,'N'
        ,CURRENT_TIMESTAMP
        ,CURRENT_TIMESTAMP
        ,null
        ,null
        )   
        ";
      $stmt = db2_prepare($conn, $Sql);

      $result=db2_execute($stmt);
      if ( ! $result ){
        echo "ERROR DB2 Save New:".db2_stmt_errormsg();
        $InErr=1;
      }  
	  
      if ( "$InErr" == "0" ){ $HideSave="si"; }
  } 
}
?>
<BR><BR>
<form id="FormStatus" method="POST">
<CENTER class="Titolo" >FORZA CAMBIO STATO DIPENDENZA</CENTER>
<table class="ExcelTable" style="width:300px;margin:auto;left:0;right:0;">
<tr>
      <th>TEAM</th>
      <th>WORKFLOW</th>
      <th>FLUSSO</th>
      <th>TIPO</th>
      <th>DIPEDENZA</th>
      <th>ID_PROCESS</th>
      <th>STATO</th>
      <th></th>
</tr>
<tr>
    <td>
      <select name="SelIdTeam" id="SelIdTeam" class="ModSt" >
        <option value="" >..</option>
        <?php
        $sql="SELECT ID_TEAM, TEAM FROM WFS.TEAM ORDER BY TEAM";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['ID_TEAM'];
          $RName=$row['TEAM'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelIdTeam" ){ ?> selected <?php } ?> ><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>   
    <td>
      <select name="SelWkf" id="SelWkf" class="ModSt" >
        <option value="" >..</option>
        <?php
        $sql="SELECT ID_WORKFLOW, WORKFLOW FROM WFS.WORKFLOW WHERE ID_TEAM = $SelIdTeam ORDER BY WORKFLOW";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['ID_WORKFLOW'];
          $RName=$row['WORKFLOW'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelWkf" ){ ?> selected <?php } ?> ><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select name="SelFls" id="SelFls" class="ModSt" >
        <option value="" >..</option>
        <?php   
        $sql="SELECT ID_FLU, FLUSSO FROM WFS.FLUSSI WHERE ID_WORKFLOW = $SelWkf AND ID_FLU IN (
        SELECT ID_FLU FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $SelWkf
        )ORDER BY FLUSSO";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['ID_FLU'];
          $RName=$row['FLUSSO'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelFls" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select name="SelTp" id="SelTp" class="ModSt" >
        <option value="" >..</option>
        <?php   
        $sql="SELECT DISTINCT TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $SelWkf AND ID_FLU = $SelFls ORDER BY TIPO";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['TIPO'];
          if ( "F" == "$RIdDip" ){ $RName="Flusso"; }
          if ( "V" == "$RIdDip" ){ $RName="Validazione"; }
          if ( "E" == "$RIdDip" ){ $RName="Elaborazione"; }
          if ( "C" == "$RIdDip" ){ $RName="Caricamento"; }
          if ( "L" == "$RIdDip" ){ $RName="Link"; }       
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelTp" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>      
      <select>
    </td>
    <td>
      <select name="SelDp" id="SelDp" class="ModSt" >
        <option value="" >..</option>
        <?php   
        $sql="SELECT ID_DIP, 
        CASE  
        WHEN TIPO = 'F' THEN ( SELECT FLUSSO       FROM WFS.FLUSSI       WHERE ID_FLU  = LF.ID_DIP )
        WHEN TIPO = 'V' THEN ( SELECT VALIDAZIONE  FROM WFS.VALIDAZIONI  WHERE ID_VAL  = LF.ID_DIP )
        WHEN TIPO = 'E' THEN ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA  = LF.ID_DIP )
        WHEN TIPO = 'C' THEN ( SELECT CARICAMENTO  FROM WFS.CARICAMENTI  WHERE ID_CAR  = LF.ID_DIP )
        WHEN TIPO = 'L' THEN ( SELECT LINK         FROM WFS.LINKS        WHERE ID_LINK = LF.ID_DIP )
        END NAME 
        FROM WFS.LEGAME_FLUSSI LF
        WHERE ID_WORKFLOW = $SelWkf AND ID_FLU = $SelFls AND TIPO = '$SelTp' ORDER BY 2";
        echo $sql;
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['ID_DIP'];
          $RName=$row['NAME'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelDp" ){ ?> selected <?php } ?>><?php echo $RName; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select name="SelIdProc" id="SelIdProc" class="ModSt" >
        <option value="" >..</option>
        <?php   
        $sql="SELECT ID_PROCESS FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = $SelWkf ORDER BY ID_PROCESS DESC";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        while ($row = db2_fetch_assoc($stmt)) {
          $RIdDip=$row['ID_PROCESS'];
          ?><option value="<?php echo $RIdDip; ?>" <?php if ( "$RIdDip" == "$SelIdProc" ){ ?> selected <?php } ?> ><?php echo $RIdDip; ?></option><?php
        }
        ?>
      <select>
    </td>
    <td>
      <select name="SelSt" id="SelSt" >
        <option value="" >..</option>
        <option value="F" <?php if ( "F" == "$SelSt" ){ ?> selected <?php } ?> >Lanciato</option>
        <option value="N" <?php if ( "N" == "$SelSt" ){ ?> selected <?php } ?> >Da Lanciare</option>
		<option value="R" <?php if ( "R" == "$SelSt" ){ ?> selected <?php } ?> >Resetta Warning</option>
      <select>
    </td>
    <td><input type="submit" name="SalvaChange" id="SalvaChange" value="Salva" hidden ></td>
</tr>
</table>
</form>
<script>

    $('#SelIdTeam').change(function(){
       $('#SelWkf').val('');
       $('#SelFls').val('');
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
    
    $('#SelWkf').change(function(){
       $('#SelFls').val('');
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');        
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
       
    $('#SelFls').change(function(){
       $('#SelTp').val('');
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');           
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
       
    $('#SelTp').change(function(){
       $('#SelDp').val('');
       $('#SelIdProc').val('');
       $('#SelSt').val('');           
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
       
    $('#SelDp').change(function(){
       $('#SelIdProc').val('');
       $('#SelSt').val('');              
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
       
    $('#SelIdProc').change(function(){
       $('#SelSt').val('');         
       $('#Waiting').show();
       $('#FormStatus').submit();
    });
    
    $('#SelSt').change(function(){
       $('#SalvaChange').hide();
       var vtest=1;
       $('.ModSt').each(function(){
           if ( $(this).val() == '' ){ vtest=0;} 
       });
       if ( $('#SelSt').val() == '' ){ vtest=0;} 
       if ( vtest == 1 && '<?php echo $HideSave; ?>' == 'no' ){ $('#SalvaChange').show(); } 
    });
    
</script>