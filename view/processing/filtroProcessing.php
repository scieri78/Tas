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
            <select name="limitRows" id="limitRows" onchange="document.getElementById('formProcessing').submit();">
                <option value="50" <?php echo ($limit == 50) ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo ($limit == 100) ? 'selected' : ''; ?>>100</option>
                <option value="200" <?php echo ($limit == 200) ? 'selected' : ''; ?>>200</option>
                <option value="500" <?php echo ($limit == 500) ? 'selected' : ''; ?>>500</option>
                <option value="1000" <?php echo ($limit == 1000) ? 'selected' : ''; ?>>1000</option>
            </select>
        </div>
        
        <input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>">
        <button type="submit" class="btn-filter">Filtra</button>
    </form>
</div>
