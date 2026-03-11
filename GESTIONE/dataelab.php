<?php
$sql = "SELECT VALORE FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB'";
$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);
while ($row = db2_fetch_assoc($stmt)) {
  $DataElab=$row['VALORE'];
  ?><div id="ShowDataElab" style="position:fixed; left:200px; top:10px; color:white; font-size: 18px;; z-index:9999;" >DataElab: <?php echo $DataElab; ?></div><?php
}
?>