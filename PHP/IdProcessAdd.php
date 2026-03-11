<style>
#ShowCopy{
    width:25px;
    cursor:pointer;
}

#ShowStatusCopy{
    position:fixed;
    width:90%;
    height:500px;
    left:0;
    right:0;
    top:0;
    bottom:0;
    margin:auto;
    border: 2px solid black;    
    box-shadow: 0px 0px 10px 0px solid black;
    background:white;
    z-index:9999;
    scroll:auto;
    overflow:auto;
}

#ShowTab{
    position:fixed;
    width:80%;
    height:400px;
    left:0;
    right:0;
    top:0;
    bottom:0;
    margin:auto;
    z-index:9999;
    scroll:auto;
    overflow:auto;
}
</style>
<?php
include '../GESTIONE/connection.php';

$IdWorkFlow=$_POST["IdWorkFlow"];
$SelIdTeam=$_POST["IdTeam"];
$SelFromId=$_POST["FromId"];
$SelToId=$_POST["ToId"];
$ShowStatusCopy=$_POST['ShowStatusCopy'];

$SqlList="SELECT FREQUENZA FROM WFS.WORKFLOW  WHERE ID_WORKFLOW = $IdWorkFlow";
$stmt=db2_prepare($conn, $SqlList);
$res=db2_execute($stmt);
if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
while ($row = db2_fetch_assoc($stmt)) {
  $IdFreq=$row['FREQUENZA']; 
}

if ( "$SelIdTeam" == "" ){  exit; }


$ArrSvecIdP=array();
$SqlList="SELECT ID_PROCESS FROM WORK_CORE.DELETE_LIST_ID_PROCESS WHERE FLG_DONE = 0 ORDER BY ID_PROCESS DESC";
$stmt=db2_prepare($conn, $SqlList);
$res=db2_execute($stmt);
if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
while ($row = db2_fetch_assoc($stmt)) {
    $IProc=$row['ID_PROCESS']; 
	array_push($ArrSvecIdP,$IProc);
}

if ( ( "$SelFromId" != "" or "$SelToId" != "" ) and "$ShowStatusCopy" == "ShowStatusCopy" ){
   ?>
   <div id="ShowStatusCopy" >
     <div id="ShowTab" >
     <table class="ExcelTable">
     <tr>
	   <th>FROM_ID_PROCESS</th>
       <th>ID_PROCESS</th>
       <th>TABSCHEMA</th>
       <th>TABNAME</th>
       <th>FLG_PART</th>
       <th>FLG_DATA_FOUND</th>
	   <th>DIFF</th>
       <th>TMS_INSERT</th>
	   <th>TMS_START_COPY</th>
	   <th>TMS_END_COPY</th>
	   <th>ESITO</th>
     </tr>
     <?php 
     if ( "$SelFromId" != "" and "$SelToId" != "" ){
       $WhrCnd=" AND FROM_ID_PROCESS = '$SelFromId' AND ID_PROCESS = '$SelToId' ";
       $WhrCnd2=" AND DATAPARTITIONNAME = '$SelFromId' ";
     } else {
       if ( "$SelFromId" != "" ){
         $WhrCnd=" AND FROM_ID_PROCESS = '$SelFromId' ";
         $WhrCnd2=" AND DATAPARTITIONNAME = '$SelFromId' ";
       }else{
         $WhrCnd=" AND ID_PROCESS = '$SelToId' ";
         $WhrCnd2=" AND DATAPARTITIONNAME = '$SelToId' ";
       }
     }
     
         
     $sql="SELECT *
	 ,timestampdiff(2,NVL(TMS_END_COPY,CURRENT_TIMESTAMP)-TMS_START_COPY) AS DIFF 
	 FROM (
     SELECT FROM_ID_PROCESS, ID_PROCESS, TABSCHEMA, TABNAME, FLG_PART, FLG_DATA_FOUND, TMS_INSERT, TMS_START_COPY, TMS_END_COPY, ESITO
       FROM WORK_CORE.COPY_STATUS_ID_PROCESS 
       WHERE 1=1
	   AND LASTRUN = 1
       $WhrCnd
     UNION ALL
       SELECT DISTINCT null FROM_ID_PROCESS, null ID_PROCESS,  TRIM(TABSCHEMA) TABSCHEMA, TRIM(TABNAME) TABNAME, 1 FLG_PART, 0 FLG_DATA_FOUND, null TMS_INSERT, null TMS_START_COPY, null TMS_END_COPY , null ESITO
         FROM SYSCAT.DATAPARTITIONS 
         WHERE 1=1
          $WhrCnd2
          AND TABNAME NOT LIKE '%ID_PROCESS%'
          AND TABSCHEMA IN ( 'MVBS')         
         AND (TRIM(TABSCHEMA), TRIM(TABNAME)) NOT IN ( SELECT TABSCHEMA, TABNAME FROM WORK_CORE.COPY_STATUS_ID_PROCESS WHERE 1=1 AND LASTRUN = 1 $WhrCnd )
     UNION ALL
       SELECT DISTINCT null FROM_ID_PROCESS, null ID_PROCESS,  TRIM(TABSCHEMA) TABSCHEMA, TRIM(TABNAME) TABNAME, 0 FLG_PART, 0 FLG_DATA_FOUND, null TMS_INSERT, null TMS_START_COPY, null TMS_END_COPY, null ESITO 
          FROM SYSCAT.TABLES A
          WHERE 1=1
          AND TYPE = 'T'
          AND (TABSCHEMA,TABNAME) NOT IN (SELECT TABSCHEMA,TABNAME FROM SYSCAT.DATAPARTITIONS WHERE TABSCHEMA =  A.TABSCHEMA   AND DATAPARTITIONID != 0  )
          AND (TABSCHEMA,TABNAME)     IN (SELECT TABSCHEMA,TABNAME FROM SYSCAT.COLUMNS WHERE TABSCHEMA =  A.TABSCHEMA  AND COLNAME = 'ID_PROCESS'  )
          AND TABNAME NOT LIKE '%ID_PROCESS%'
          AND TABSCHEMA IN ( 'MVBS')
          AND (TRIM(TABSCHEMA), TRIM(TABNAME)) NOT IN ( SELECT TABSCHEMA, TABNAME FROM WORK_CORE.COPY_STATUS_ID_PROCESS WHERE 1=1 AND LASTRUN = 1 $WhrCnd )
     )
     ORDER BY FROM_ID_PROCESS, FLG_DATA_FOUND, TABSCHEMA, TABNAME
     ";
     $stmt=db2_prepare($conn, $sql);
     $result=db2_execute($stmt);
	 $TOTDIFF=0;
     if ( ! $result) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
     while ($row = db2_fetch_assoc($stmt)) {
        $FROM_ID_PROCESS=$row['FROM_ID_PROCESS'];
		$ID_PROCESS=$row['ID_PROCESS'];
		$TABSCHEMA=$row['TABSCHEMA'];
        $TABNAME=$row['TABNAME'];
        $FLG_PART=$row['FLG_PART'];
        $FLG_DATA_FOUND=$row['FLG_DATA_FOUND'];
        $TMS_INSERT=$row['TMS_INSERT'];
		$TMS_START_COPY=$row['TMS_START_COPY'];
		$TMS_END_COPY=$row['TMS_END_COPY'];
		$ESITO=$row['ESITO'];
		$DIFF=$row['DIFF'];
        $TOTDIFF=$TOTDIFF+$DIFF;
      ?>
      <tr>       
		<td><?php echo $FROM_ID_PROCESS; ?></td>          
        <td><?php echo $ID_PROCESS; ?></td>
        <td><?php echo $TABSCHEMA; ?></td>
        <td><?php echo $TABNAME; ?></td>
        <td><?php echo $FLG_PART; ?></td>
        <td><?php echo $FLG_DATA_FOUND; ?></td>
		<td><?php echo gmdate('H:i:s',$DIFF); ?></td>
        <td><?php echo $TMS_INSERT; ?></td>
		<td><?php echo $TMS_START_COPY; ?></td>
		<td><?php echo $TMS_END_COPY; ?></td>
		<td><?php echo $ESITO; ?></td>
      </tr>
      <?php
     }
     ?>
	  <tr>       
		<td><?php echo $FROM_ID_PROCESS; ?></td>          
        <td><?php echo $ID_PROCESS; ?></td>
        <td><?php echo $TABSCHEMA; ?></td>
        <td></td>
        <td></td>
        <td></td>
		<td><?php echo gmdate('H:i:s',$TOTDIFF); ?></td>
        <td></td>
		<td></td>
		<td></td>
		<td></td>
      </tr>
     </table>
     </div>
     <table style="position: absolute;bottom:10px;right:0;left:0;margin:auto;" ><tr>
     <td><div class="Bottone" onclick="ShowCopy()" >Refresh</div></td>
     <td><div class="Bottone" onclick="$('#ShowStatusCopy').hide()" >Chiudi</div></td>
     </tr></table>
   </div>
   <?php
}
?>
<div class="Titolo" >Lista IdProcess</div>
<div style="height:100px;overflow:auto;" >
<table class="ExcelTable" >
    <tr>
       <th><img src="../images/Cestino.png" style="width:20px;" ></th>
       <th style="width: 100px;">IdProcess</th>
       <th style="width: 100px;">Descrizione</th>
       <th style="width: 80px;">Anno Esame</th>
       <th style="width: 80px;">Mese Esame</th>
       <th style="width: 80px;">Tipologia</th>
       <th style="width: 80px;">ReadOnly</th>
    </tr>
    <?php 
       $ArrIdP=array();
       $SqlList="SELECT ID_PROCESS, DESCR,  ESER_ESAME, MESE_ESAME, ESER_MESE, FLAG_CONSOLIDATO, TIPO, READONLY,FLAG_STATO 
       FROM WORK_CORE.ID_PROCESS  
       WHERE 1=1
       AND ID_WORKFLOW = '$IdWorkFlow'
       AND ID_TEAM = '$SelIdTeam'
	   AND FLAG_CONSOLIDATO = 0
       ORDER BY ID_PROCESS DESC";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
       while ($row = db2_fetch_assoc($stmt)) {
           $IdProc=$row['ID_PROCESS']; 
           $DescrProc=$row['DESCR']; 
           $EserEsameProc=$row['ESER_ESAME'];
           $MeseEsameProc=$row['MESE_ESAME'];  
           $EserMeseProc=$row['ESER_MESE']; 
           $FlagProc=$row['FLAG_CONSOLIDATO']; 
           $TipoProc=$row['TIPO']; 
           $ReadOnly=$row['READONLY'];
           $FlgStato=$row['FLAG_STATO'];
           array_push($ArrIdP,$IdProc);
		   $InSvec="";
		   $Svec=0;
		   $LabSvec="Add";
		   if ( in_array($IdProc,$ArrSvecIdP) ){
		     $InSvec="background:red;";
             $Svec=1;			 
			 $LabSvec="Rem";
		   }
           if ( "$FlgStato" != "C" ){          
             ?>
             <tr>
                 <td><input type="checkbox" id="CheckSvec<?php echo $IdProc; ?>" <?php if ( "$Svec" == "1" ) { ?>checked<?php } ?> 
				 onclick="<?php echo $LabSvec; ?>SveccIdp(<?php echo $IdProc; ?>)" ></td>   
                 <td  style="<?php echo $InSvec; ?>width: 200px;"><?php echo $IdProc; ?></td>
                 <td  style="<?php echo $InSvec; ?>width: 200px;"><?php echo $DescrProc; ?></td>
                 <td  style="<?php echo $InSvec; ?>width: 80px;"><?php echo $EserEsameProc; ?></td>
                 <td  style="<?php echo $InSvec; ?>width: 80px;"><?php echo $MeseEsameProc; ?></td>
                 <td  style="<?php echo $InSvec; ?>width: 100px;"><?php 
                 switch ($TipoProc){
                  case 'R': echo "Restatement"; break;
				  default: echo "Closing"; break;
                 }
                 ?></td>
              <td style="width: 80px;">
              <?php
              if ( "$ReadOnly" == "Y" ){
                ?><img onclick="RemoveReadOnly(<?php echo $IdProc; ?>)" title="Remove ReadOnly" src="../images/ReadMode.png" width="30px" style="cursor:pointer;"><?php
              } else {
                ?><div onclick="AddReadOnly(<?php echo $IdProc; ?>)"><a style="width: 30px;cursor:pointer;" title="Add ReadOnly" ><u>No</u></a></div><?php
              }
              ?>
              </td>
              </tr>
              <?php
           }
       }     
     ?>
</table>
</div>
<div class="Titolo" >Aggiungi IdProcess</div>
<input type="hidden" name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>">
<input type="hidden" name="IdWorkFlow" id="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>">

<table style="left:0;right:0;margin:auto;width:500px;">
<tr><th style="width:100px;" >Esercizio</th>
  <td style="width:400px;">
    <select id="Esercizio" name="Esercizio"  class="FieldMand" style="width:100%;height:30px;">
        <option value="" >..</option>
        <?php 
            $Anno=date("Y");
            $Mese=date("m");
            
            $dateVal = date("Y-m-t");
            date_add($dateVal, date_interval_create_from_date_string("1 months"));  
    
            ?><option value="<?php echo $dateVal; ?>" <?php if ( "$Sel_New_Process_Period" == "$dateVal" ) { ?> selected <?php } ?> ><?php echo $dateVal; ?></option><?php
            
            for( $m=$Mese-1; $m>=1 ;$m--){
                  $mm=str_pad($m,2,0,STR_PAD_LEFT);
				  if ( "$IdFreq" == "A" ){
					if ( "$mm" == "12" ){
					  $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));  
					  ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					}
				  }else{
					if ( "$IdFreq" == "Q" ){
				      if ( "$mm" == "3" or "$mm" == "6" or "$mm" == "9" or "$mm" == "12" ){
					    $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));  
					    ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					  } 
				    } else{
					  $dd=date("Y-m-t", strtotime($Anno.'-'.$mm.'-01'));
                      ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
				    }
				  }
                  
            }
            
            $Gap=$Anno-2015;
            for( $a=$Anno-1; $a>$Anno-$Gap ; $a-- ){
                for( $m=12; $m>=1 ;$m--){
                  $mm=str_pad($m,2,0,STR_PAD_LEFT);
                   if ( "$IdFreq" == "A" ){
					if ( "$mm" == "12" ){
					  $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));  
					  ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					}
				  }else{
					if ( "$IdFreq" == "Q" ){
				      if ( "$mm" == "3" or "$mm" == "6" or "$mm" == "9" or "$mm" == "12" ){
					    $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));  
					    ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
					  } 
				    } else{
					  $dd=date("Y-m-t", strtotime($a.'-'.$mm.'-01'));
                      ?><option value="<?php echo $dd; ?>" <?php if ( "$Sel_New_Process_Period" == "$dd" ) { ?> selected <?php } ?> ><?php echo $dd; ?></option><?php
				    }
				  }
              }
            }
        ?>
    </select>
  </td>
</tr>
<?php
/*
<tr>
  <th>Tipo</th>
  <td>
    <select id="Tipo" name="Tipo"  class="FieldMand" style="width:100%;height:30px;">
        <option value="" >..</option>
		<option value="Q" >Chiusura</option>
        <option value="S" >Sensibility</option>
        <option value="R" >Restatement</option>
    </select>
  </td>
</tr>
*/
?>
<tr><th>Descrizione</th><td><input type="text" name="Descr" id="Descr" class="FieldMand" style="width:100%;height:30px;" ></td></tr>
<tr><td height="30px" colspan=2><div id="SaveIdProcess"class="Bottone" onclick="SaveIdProcess()" hidden >Salva</div></td></tr>
</table>
<table style="left:0;right:0;margin:auto;">
<tr><td></td><th>From</th><th>To</th><th style="width:210px;"></th></tr>
<tr>
  <th>COPIA</th>
  <td>
    <select id="FromId" name="FromId"  class="FieldMandCopy" style="width:150px;height:30px;">
        <option value="" >..</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( "$SelFromId" == "$IdP" ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
  </td>
  <td>
    <select id="ToId" name="ToId"  class="FieldMandCopy" style="width:150px;height:30px;">
        <option value="" >..</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( "$SelToId" == "$IdP" ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
  </td>
  <td>
    <div id="ShowCopy"      class="Bottone" onclick="ShowCopy()"      style="width:40%;float:left;" hidden >Mostra</div>
    <div id="CopyIdProcess" class="Bottone" onclick="CopyIdProcess()" style="width:40%;float:left;" hidden >Copia</div>
  </td>
</tr>
<tr><td></td><th>Svuota</th><th>Cancella</th><th></th></tr>
<tr>
  <th>RIMUOVI</th>
  <td>
    <select id="SvuotaId" name="SvuotaId" class="FieldMandRem" style="width:150px;height:30px;">
        <option value="" >..</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( "$SvuotaId" == "$IdP" ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
  </td>
  <td>
    <select id="CancellaId" name="CancellaId" class="FieldMandRem" style="width:150px;height:30px;">
        <option value="" >..</option>
        <?php
            foreach ( $ArrIdP as $IdP ){
                  ?><option value="<?php echo $IdP; ?>" <?php if ( "$CancellaId" == "$IdP" ) { ?> selected <?php } ?> ><?php echo $IdP; ?></option><?php
            }
        ?>
    </select>
  </td>
  <td><div id="RimuoviIdProcess" class="Bottone" onclick="RimuoviIdProcess()" hidden >Rimuovi</div></td>
</tr>
</table>
<?php

db2_close($conn); 
?>
<script> 
    function AddSveccIdp(vId){ 
	    var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "AddSvecc")
        .val(vId);
        $('#FormMain').append($(input));     			
		$('#Waiting').show();
        $('#FormMain').submit(); 	
	}
	
    function RemSveccIdp(vId){ 
	    var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "RemSvecc")
        .val(vId);
		$('#FormMain').append($(input));     			
		$('#Waiting').show();
        $('#FormMain').submit(); 	
	}
	
    function Testsave(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
              
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
       $('.FieldMandRem').each(function(){
           $(this).val('');
       });
       
       var vSave=true;
       $('.FieldMand').each(function(){
           if ( $(this).val() == '' ){ vSave=false; }
       });
       
       if ( vSave ){
         $('#SaveIdProcess').show();
       } 
       
    }

    function RimuoviIdProcess(){        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Azione")
        .val('Rimuovi');
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                            
    }

    function SaveIdProcess(){
        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Azione")
        .val('Aggiungi');
        $('#FormMain').append($(input));
        $('#FormMain').submit();
                    
        $('#Waiting').show();
        $('#FormMain').submit();
    }
       
    function CopyIdProcess(){
           
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "Azione")
      .val('Copia');
      $('#FormMain').append($(input));
      $('#FormMain').submit();
                  
      $('#Waiting').show();
      $('#FormMain').submit();
    }   
    
    function ShowCopy(){
           
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ShowStatusCopy")
      .val('ShowStatusCopy');
      $('#FormMain').append($(input));
                 
      $('#Waiting').show();
      $('#FormMain').submit();
    }   
    
       
    function RemoveReadOnly(vIdProc){        
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "RemoveRO")
        .val(vIdProc);
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                            
    }
    
    function AddReadOnly(vIdProc){
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "AddRO")
        .val(vIdProc);
        $('#FormMain').append($(input));        
        $("#Waiting").show();
        $('#FormMain').submit();                 
    }

    
   $('#Esercizio').change(function(){
       $('#Descr').val('Chiusura '+$('#Esercizio').val());
   });

   $('#Tipo').change(function(){
       $('#Descr').val($('#Descr').val()+' ['+$(this).val()+']');
   });
    
   $('.FieldMand').change(function(){
      Testsave();
   });           
   $('.FieldMand').keyup(function(){
      Testsave();
   });     
   
         

    function TestsaveCopy(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
      
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       
       $('.FieldMandRem').each(function(){
           $(this).val('');
       });
       
       var vSave=true;
       var vShow=false;
       $('.FieldMandCopy').each(function(){
           if ( $(this).val() == '' ){ vSave=false; } else { vShow=true; }
       });
       
       if ( $('#FromId').val() == $('#ToId').val() ){
           vSave=false;
       }
       
       if ( vSave ){
         $('#CopyIdProcess').show();
       }
       
       if ( vShow ){
         $('#ShowCopy').show();
       }
    }

   $('.FieldMandCopy').change(function(){
      TestsaveCopy();
   });           
   $('.FieldMandCopy').keyup(function(){
      TestsaveCopy();
   });     
   TestsaveCopy();

   $('#SvuotaId').change(function(){
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
      $('#CancellaId').val('');
      $('#RimuoviIdProcess').hide();
      if ( $('#SvuotaId').val() != '' ){ $('#RimuoviIdProcess').show(); }
   });

   $('#CancellaId').change(function(){ 
       $('#CopyIdProcess').hide();
       $('#RimuoviIdProcess').hide();
       $('#SaveIdProcess').hide();
       $('#ShowCopy').hide();
       $('.FieldMand').each(function(){
           $(this).val('');
       });
       $('.FieldMandCopy').each(function(){
           $(this).val('');
       });
       
      $('#SvuotaId').val('');
      $('#CancellaId').change(function(){ });
      $('#RimuoviIdProcess').hide();
      if ( $('#CancellaId').val() != '' ){ $('#RimuoviIdProcess').show(); }
   });   
   
   $("#Waiting").hide();
   
</script> 

