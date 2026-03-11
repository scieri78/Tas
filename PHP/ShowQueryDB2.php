<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
	
?>
<style>
#ShowDataElab{ display:none; }
</style>
<link rel="stylesheet" href="../CSS/ShowTab.css">
<STYLE>
    .ShowQuery{
	   top:<?php echo $TopDist; ?>px;
	}
</STYLE>
<div class="ShowQuery" >		  
<CENTER>
<form method="POST" >
<?php

$sql=$_SESSION['Query'];  

echo $_GET['DIPENDENZA'];

if ( "$sql" <> "" && !strstr($sql,"DELETE") && !strstr($sql,"UPDATE") ) {

  	$RigheXPag=20;
	$conn = db2_connect($db2_conn_string, '', '');
	$stmt=db2_prepare($conn, $sql);
  	$result=db2_execute($stmt);
  	db2_close($db2_conn_string);
  
  	$columns=db2_num_fields($stmt);
  
  	$Num=0;
  
  	$StgTitolo='<TR  id="Title" ><TH>Riga</TH>';
  
  	for($i=0;$i<$columns;$i++){
  		  $fieldName[$i] = db2_field_name($stmt,$i);
  		  $fieldType[$i] = db2_field_type($stmt,$i);
  		  $fieldPreci[$i] = db2_field_precision($stmt,$i);
  		  $Num=$Num+1;
  		  $StgTitolo=$StgTitolo.'<TH id="Colonna" >'.$fieldName[$i].'<BR><div class="type">('.$fieldType[$i].' '.$fieldPreci[$i].')</div></TH>';
  	}
  	$StgTitolo=$StgTitolo.'</TR>';
	if ( $Num == 0 ){ $StgTitolo=""; }
  	$numRiga=1;
  	$Page=1;
  	?><div id="Page<?php echo $Page; ?>" class="Page" >
  	<table><?php echo $StgTitolo ;?>
  	<?php
  	while ($row = db2_fetch_array($stmt)) {

  	  if ( $numRiga & 1 ) { $PoD="dispari"; }else{ $PoD="pari"; }; 
  	  ?><TR class="<?php echo $PoD; ?>" ><TD><?php echo $numRiga; ?></TD><?php
  	  for($i=0;$i<$Num;$i++){
  	   $Parola=explode(' ',$row[$i]);
  	   if ( $Parola[1] != ":SLCT:" ) {
  		 ?><TD class="<?php echo $fieldType[$i]; ?>" ><?php echo $row[$i];?></TD><?php
  	   } else {
  		 ?><TD class="Select" >
  			 <button name="SELECT" type="submit" class="invsel" value="<?php echo $row[$i];?>" >
  			  <?php echo $Parola[0];?>
  			 </button>
  		 </TD><?php
  	   }
  	  }
  	  ?></TR><?php
  
  	  if ( ( $numRiga % $RigheXPag == 0 ) ){
  		$Page=$Page+1;
  		?></table></div>
  		<div id="Page<?php echo $Page; ?>" class="Page nascondi" >
  		<table><?php echo $StgTitolo;
  	  }	  
  	  $numRiga=$numRiga+1;
  	} ?>
  	</table>
  	</div>
  
  	<div class="ListaPage">
  	<?php
  	$TotPage=ceil(($numRiga-1)/$RigheXPag);
  	for ($i=1;$i<=$TotPage;$i++){ ?>
  		<div id="LinkPage<?php echo $i; ?>" class="LinkPage" ><?php echo $i; ?></div>
  		<script>
  			$("#LinkPage<?php echo $i; ?>").click( function () {
  				$(".LinkPage").css("color","black");
  				$(".Page").addClass("nascondi");
  				$("#Page<?php echo $i; ?>").removeClass("nascondi");
  				$("#LinkPage<?php echo $i; ?>").css("color","red");
  			} );
  		</script>
  		<?php
  	}
  	?></div><?php
} ?>
</form>	

</CENTER>
</div>
