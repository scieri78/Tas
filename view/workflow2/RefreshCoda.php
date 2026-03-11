<?php

foreach ($DatiRichiesteCodaEsito as $row) {
    $Flu = $row['ID_FLU'];
    $Tip = $row['TIPO'];
    $Dip = $row['ID_DIP'];
    $Legame = $row['ID_LEGAME'];
    $Esito = $row['ESITO'];

?>
    <script>
        /* nascondiDiv('<?php echo $Flu; ?>', '<?php echo $Legame; ?>', '<?php echo $Esito; ?>'); */
        noAutoRefreshCoda('<?php echo $Flu; ?>', '<?php echo $Legame; ?>', '<?php echo $Esito; ?>');
</script>
 <?php    
}

if ($OldMaxTime == "") {
    $OldMaxTime = $Now;
    $SetMaxTime = $OldMaxTime;
}

foreach ($DatiFlusso as $row) {
    $Flu = $row['ID_FLU'];
    $NomeFlu = $row['FLUSSO'];
    $Tip = $row['TIPO'];
    $Dip = $row['ID_DIP'];
    $Legame = $row['ID_LEGAME'];
    $EsitoDip = $row['ESITO'];


    $SetMaxTime = $row['LAST'];

?>
    <script>
        /*
        MostraFlusso(<?php echo $IdWorkFlow; ?>,<?php echo $IdProcess; ?>,<?php echo $Flu; ?>);
        nascondiDivLegame('<?php echo $Legame; ?>');
        */
    </script>
    <?php
    //RefreshSons($Flu, $_model);
    ?>

    <?php

    if ($SelIdFlu == $Flu) {
    ?>
        <script>
            /*nascondiDivFlusso(<?php echo $Flu; ?>);*/
        </script>
        <?php
    }

    if ($SelIdFlu == $Flu and $SelIdDip == $Dip) {

        if ($Tip == "C" or $Tip == "E") {
            if ("$Tip" == "C") {
                $NotaRis = "Caricamento  $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente!";
            } else {
                $NotaRis = "Elaborazione $SelNomeDip del Flusso $SelNomeFlu eseguito correttamente!";
            }
            $Img = "success";
            $TMsg = "OK";
            if ($EsitoDip != "F") {
                if ($EsitoDip == "W") {
                    $TMsg = "Warning";
                    $Img = "warning";
                } else {
                    $TMsg = "Error";
                    $Img = "danger";
                }
                if ($Tipo == "C") {
                    $NotaRis = "Caricamento $SelNomeDip del Flusso $SelNomeFlu!";
                } else {
                    $NotaRis = "Elaborazione $SelNomeDip del Flusso $SelNomeFlu!";
                }
            }
        ?>
            <script>
                MostraEsito('<?php echo $Img; ?>', '<?php echo $TMsg; ?>', '<?php echo $NotaRis; ?>');
            </script>           
<?php
        }
   }
}


?>


<?php
function RefreshSons($Flu, $_model)
{
    global $IdWorkFlow, $IdProcess;
    $DatiLegamiFlusso = $_model->getLegamiFlusso($IdWorkFlow, $Flu);
    foreach ($DatiLegamiFlusso as $row) {
        $FluIn = $row['ID_FLU'];
?>
        <script>
            MostraFlusso(<?php echo $IdWorkFlow; ?>,
                <?php echo $IdProcess; ?>,
                <?php echo $Flu; ?>);
        </script>
<?php
        RefreshSons($FluIn, $_model);
    }
}
?>

<script>
    visualizzaPulsanti('<?php echo $SetMaxTime; ?>', '<?php echo $CntProc; ?>', '<?php echo $CntWfs; ?>', '<?php echo $InRun; ?>');
</script>
