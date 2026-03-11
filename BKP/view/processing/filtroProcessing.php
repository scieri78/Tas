<?php
/**
 * View - Filtro Processing
 * Form di filtro con select box per MESE_ELAB, MESE_DIFF e LIMIT
 */

// Recupera il mese corrente se non impostato
$meseElab = $datiprocessing->getMeseElab();
if (!$meseElab) {
    $meseElab = date('Ym');
    $datiprocessing->setMeseElab($meseElab);
}

$meseDiff = $datiprocessing->getMeseDiff();
if (!$meseDiff) {
    $meseDiff = date('Ym');
    $datiprocessing->setMeseDiff($meseDiff);
}

$limit = $datiprocessing->getLimit();
?>

<div class="filter-processing">
    <form method="POST" id="formProcessing">
        <div class="filter-row">
            <label for="meseElab">Mese Elaborazione:</label>
            <select name="meseElab" id="meseElab" onchange="document.getElementById('formProcessing').submit();">
                <?php
                    // Genera ultimi 24 mesi
                    for ($i = 0; $i < 24; $i++) {
                        $mese = date('Ym', strtotime("-$i months"));
                        $meseFormatted = date('m/Y', strtotime($mese . '01'));
                        $selected = ($mese == $meseElab) ? 'selected' : '';
                        echo "<option value='$mese' $selected>$meseFormatted</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="filter-row">
            <label for="meseDiff">Mese Precedente (confronto):</label>
            <select name="meseDiff" id="meseDiff" onchange="document.getElementById('formProcessing').submit();">
                <?php
                    // Genera ultimi 24 mesi
                    for ($i = 0; $i < 24; $i++) {
                        $mese = date('Ym', strtotime("-$i months"));
                        $meseFormatted = date('m/Y', strtotime($mese . '01'));
                        $selected = ($mese == $meseDiff) ? 'selected' : '';
                        echo "<option value='$mese' $selected>$meseFormatted</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="filter-row">
            <label for="limitRows">Numero Righe:</label>
            <input type="number" name="limitRows" id="limitRows" value="<?php echo $limit ?: 10; ?>" min="1" onchange="document.getElementById('formProcessing').submit();" />
        </div>
        
        <input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>">
        <button type="submit" class="btn-filter">Filtra</button>
    </form>
</div>
