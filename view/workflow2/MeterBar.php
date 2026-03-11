<?php

$IdFlu=$_POST['IdFlu'];
$IdLegame=$_POST['IdLegame'];
$Inizio=$_POST['Inizio'];
$SecLast=$_POST['SecLast'];


$SecPre=$_POST['SecPre'];
$DipEsito=$_POST['DipEsito'];


$BarraCaricamento = "rgb(21, 140, 240)";
$BarraPeggio = "rgb(165, 108, 185)";
$BarraMeglio = "rgb(104, 162, 111)";


if ( "$SecPre" == "0" ){
	$SecPre = 1;
}
$Perc = round(( $SecLast * 100 ) / $SecPre );

if ( $Perc > 100 ) {
	$SColor = "$BarraPeggio";
}
if ( $Perc <= 100 and "$DipEsito" != "I" ) {
	$SColor = "$BarraMeglio";
}

if ( "$DipEsito" == "I" ) {
	$SColor = "$BarraCaricamento";
}         

if (
	(   1==1
		and ( $Perc > 120 or $Perc < 90 ) 
		and  ( "$DipEsito" == "F" or "$DipEsito" == "W" )
		//and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
	) 
	or ( "$DipEsito" == "I" )
) {
	?>
	<div class="progress">
		<div class="progress-bar progress-bar-striped <?php 
		if ("$DipEsito" == "I") {
			echo "active";
		} 
		?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
		if ($Perc > 100) {
			echo 100;
		} else {
			echo "$Perc";
		} 
		?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="margin:0;font-weight: 500;"><?php
		if ($Perc > 100) {
			$Perc = $Perc - 100;
			$Perc = "+" . $Perc;
		} else {
			if ( "$DipEsito" != "I" ){
			  $Perc = $Perc - 100;
			} 
		}                                                                       
		echo $Perc;
		?>%</LABEL>
		</div>
	</div>
	<?php
}
?>