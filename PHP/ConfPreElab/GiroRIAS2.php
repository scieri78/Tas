<style>
.FileCons{
	color:blue;
	font-size:12px;
}
.ErrorDiv{
	position:fixed;
	color:red;
	font-size:12px;
	border:2px solid red;
	width:300px;
	height:100px;
	background-color:white;
	top:0;
	bottom:0;
	left:0;
	right:0;
	margin:auto;
	shadow-box: 0px 0px 10px 0px black;
	text-align:center;
}
</style>
<?php
$Tipo='105-PREMIFUTURI';
//$Tipo='ALL';

$ConsFile=$_POST['ConsFile'];

if ( "$ConsFile" == "Consolida File" ){
	$SelCons=$_POST['SelCons'];
	
    $Sh_DIRSH="/area_staging_TAS/DIR_SHELL/MVBS";
    $Sh_SHELL="RIASS_ConsFile_Work.sh";
    $Sh_VARIABLES="$SelCons $Tipo";
	exec("sh $PRGDIR/AvviaShellServer.sh '${IdProcess}' '${SSHUSR}' '${SERVER}' '$Sh_DIRSH' '$Sh_SHELL' '$Sh_VARIABLES' 2>&1 > $PRGDIR/AvviaShellServer.log", $output, $return_var);
    

	if ( "$return_var" != "0" ){
		?>
		<div class="ErrorDiv" >
		<BR>Errore Shell di consolidamento
        <input value="Chiudi" class="Bottone" onclick="$('#MainForm').submit();">
		</div>
		<?php
	}else{
		$EsitoDip=$Esito;
		?><script>$('#LinkEsitoDip').val('F');</script><?php
	}
}

?>
<CENTER><input type="Submit" value="REFRESH" class="Bottone"></CENTER>
<table class="ExcelTable" >
<tr>
  <th class="SalvaConf" >CONSOLIDA</th>
  <th>IdFile</th>
  <th>Load</th>
  <th>Cons</th>
   <th>Mb</th>
   <th>Rows</th>
   <th>File</th>
</tr>
<?php 

$sqlT="SELECT 
    ID_FILE ,
    LOAD_DATE , 
    CONS_DATE , 
    LAST_CONS , 
    SIZE_FILE , 
    NUM_ROWS , 
    INPUT_FILE , 
    ARCHIVE , 
    NOTE , 
    TAGS 
FROM 
    ENTRY_DATA.N_INPUT_FILES_RIASS
WHERE 1=1
    AND ( ESER_ESAME, MESE_ESAME) IN  ( SELECT ESER_ESAME, MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) 
    AND NOTE = '$Tipo'
	ORDER BY LOAD_DATE DESC
";
$stmtT=db2_prepare($conn, $sqlT);
$resT=db2_execute($stmtT);
$cnt=0;
if ( ! $resT) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
while ($rowT = db2_fetch_assoc($stmtT)) {
    $T_ID_FILE=$rowT['ID_FILE'];
	$T_LOAD_DATE=$rowT['LOAD_DATE'];
    $T_CONS_DATE=$rowT['CONS_DATE'];
    $T_LAST_CONS=$rowT['LAST_CONS'];
    $T_SIZE_FILE=$rowT['SIZE_FILE'];
    $T_NUM_ROWS=$rowT['NUM_ROWS'];
    $T_INPUT_FILE=$rowT['INPUT_FILE'];
    $T_ARCHIVE=$rowT['ARCHIVE'];
    $T_NOTE=$rowT['NOTE'];
    $T_TAGS=$rowT['TAGS'];
	$cnt=$cnt+1;
	if ( "$T_LAST_CONS" == "Y" ){ $T_SEL_CONS=$T_ID_FILE; }
    ?>
	<tr>
      <td class="SalvaConf" ><input type="radio" name="SelCons" class="SelCons" value="<?php echo $T_ID_FILE; ?>" <?php if ( "$T_LAST_CONS" == "Y" ){ ?>checked<?php }?> ></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_ID_FILE"; ?></td>
	  <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_LOAD_DATE"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_CONS_DATE"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo round($T_SIZE_FILE/1024/1024,2); ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_NUM_ROWS"; ?></td>
      <td <?php if ( "$T_LAST_CONS" == "Y" ){ ?>class="FileCons"<?php }?> ><?php echo "$T_INPUT_FILE"; ?></td>
	</tr>
	<?php
}


  if ( $cnt == 1 and $EsitoDip == "N" ) {
	   $Errore=0;
	   $Note="";
       $CallP='CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
	   $stmt = db2_prepare($conn, $CallP);
	   db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
	   db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
	   db2_bind_param($stmt, 3, "IdLegame"    , DB2_PARAM_IN);
	   db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
	   db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
	   db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
	   $res=db2_execute($stmt);
	   
	   if ( ! $res) {
		 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg($stmt);
	   } else {
	   
	     if ( $Errore != 0 ) {
		   echo "PLSQL Procedure Calling Error $Errore: ".$Note;
	     } else {
		   ?><script>$('#LinkEsitoDip').val('F');</script><?php
	     }
	   
	   }
  }
?>
</table>
<CENTER><input type="submit" name="ConsFile" id="ConsFile" value="Consolida File" class="Bottone SalvaConf"  hidden ></CENTER>
<script>
  
  $('#Waiting').hide();
  
  function TestConsFile(){
	var vSelx = false;

	$('.SelCons').each(function(){      
	  if (  $(this).is(':checked') && 
	       $(this).val() == '<?php echo $T_SEL_CONS; ?>'  
	  ){
		vSelx=true;
	  }
	});
	
	if ( vSelx ){
	  $('#ConsFile').hide();
	}else{
	  $('#ConsFile').show();
	}
  };
  
  
  $('.SelCons').change(function(){ TestConsFile(); });
  
  TestConsFile();
   
	  $('#ConsFile').click(function(){
		  $('#Waiting').show();
	  });
    
</script>
