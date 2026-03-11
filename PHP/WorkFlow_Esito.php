<?php
$Img=$_POST["Img"];
$NotaRis=$_POST["NotaRis"];

?>
<div id="EsitoImg"><img src="../images/<?php echo $Img; ?>.png" height="40px"></div><BR>
<div id="EsitoText"><?php echo $NotaRis; ?></div><BR>
<div class="Bottone" onclick="$('#MainForm').submit();" >Chiudi</div>
