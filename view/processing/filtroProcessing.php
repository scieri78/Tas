<?php
/**
 * View - Filtro Processing
 * Layout filtri allineato a statoshell
 */

$dbName = isset($_GET['sito']) ? $_GET['sito'] : (isset($_POST['db_name']) ? $_POST['db_name'] : $datiprocessing->DB2database);
$selectedAmbiti = $datiprocessing->getSelAmbito();
?>

<aside aria-label="Filtri processing" class="fileter-aside starlight-aside starlight-aside--note">
    <p class="starlight-aside__title" aria-hidden="true">
        <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>
        Processing <?php echo strtoupper((string) $dbName); ?>
    </p>

    <form id="formProcessing" method="POST" onsubmit="NumLastNoSubmit()">
        <div id="divFiltroShell">
            <div class="divFilterRight">
                <span onclick="Refresh()" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> </span>
                <span onclick="resetSession()" id="resetSessionbtn" class="btn"><i class="fa-solid fa-eraser"></i> </span>
                <span id="RCLegend2" onclick="apriLegendProcessing()" class="btn"><i class="fa-solid fa-circle-question"></i></span>

                <input type="hidden" id="resetSession" name="resetSession" value="0" />
                <input type="hidden" id="SelNumPage" name="SelNumPage" value="<?php echo (int) $datiprocessing->getSelNumPage(); ?>" />
                <input type="hidden" name="db_name" value="<?php echo htmlspecialchars((string) $dbName, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="divFilter">
                <input type="checkbox" class="ReloadForm" id="AutoRefresh" name="AutoRefresh" value="AutoRefresh" <?php if ($datiprocessing->getAutoRefresh() === 'AutoRefresh') { echo 'checked'; } ?>>
                <label for="AutoRefresh">Auto Refresh</label>

                <input type="checkbox" id="PLSSHOWDETT" name="PLSSHOWDETT" value="PLSSHOWDETT" <?php if ($datiprocessing->getShowDett() === 'PLSSHOWDETT') { echo 'checked'; } ?>>
                <label for="PLSSHOWDETT">Show Dett</label>

                <input onchange="document.getElementById('formProcessing').submit()" type="checkbox" id="NoTags" name="NoTags" value="NoTags" <?php if ($datiprocessing->getNoTags() === 'NoTags') { echo 'checked'; } ?>>
                <label for="NoTags">NoTags</label>
            </div>
        </div>

        <div class="divFilters">
            <div class="divFilter">
                <label for="SelMeseElab">MESE ELAB.</label>
                <select class="inputFilter selectSearch" name="meseElab" id="SelMeseElab" onchange="document.getElementById('SelNumPage').value='1';document.getElementById('formProcessing').submit();">
                    <option value="%" <?php if ($datiprocessing->getMeseElab() === '%') { echo 'selected'; } ?>>All</option>
                    <?php foreach ($DatiSelMeseElab as $row) {
                        $mese = $row['MESEELAB'];
                        $selected = ($datiprocessing->getMeseElab() === $mese) ? 'selected' : '';
                        echo '<option value="' . $mese . '" ' . $selected . '>' . $mese . '</option>';
                    } ?>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelLastMeseElab">MESE DIFF</label>
                <select class="inputFilter selectSearch" name="meseDiff" id="SelLastMeseElab" onchange="document.getElementById('formProcessing').submit();">
                    <option value="0">NoDiff</option>
                    <?php foreach ($DatiSelLastMeseElab as $row) {
                        $mese = $row['MESEELAB'];
                        $selected = ($datiprocessing->getMeseDiff() === $mese) ? 'selected' : '';
                        echo '<option value="' . $mese . '" ' . $selected . '>' . $mese . '</option>';
                    } ?>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelShell">SHELL</label>
                <select class="inputFilterSelect selectSearch" name="SelShell" id="SelShell" onchange="selectSelShell()">
                    <option value="" <?php if ($datiprocessing->getSelShell() === '') { echo 'selected'; } ?>>All</option>
                    <optgroup label="Shell Father">
                        <?php foreach ($DatiSelShellFather as $row) {
                            $idRunSh = $row['ID_RUN_SH'];
                            $selected = ((string) $datiprocessing->getSelShell() === (string) $idRunSh) ? 'selected' : '';
                            $label = $row['SHELL'] . ' ' . $row['TAGS'];
                            echo '<option value="' . $idRunSh . '" ' . $selected . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
                        } ?>
                    </optgroup>
                    <optgroup label="Shell Sons">
                        <?php foreach ($DatiSelShellSons as $row) {
                            $idRunSh = $row['ID_RUN_SH'];
                            $selected = ((string) $datiprocessing->getSelShell() === (string) $idRunSh) ? 'selected' : '';
                            $label = $row['SHELL'] . ' ' . $row['TAGS'];
                            echo '<option value="' . $idRunSh . '" ' . $selected . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
                        } ?>
                    </optgroup>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelInDate">DAY</label>
                <select class="inputFilter selectSearch" name="SelInDate" id="SelInDate" onchange="selectSelInDate()">
                    <option value="<?php echo processing_dati::LAST_DAYS; ?>" <?php if ($datiprocessing->getSelInDate() === processing_dati::LAST_DAYS) { echo 'selected'; } ?>>Last days</option>
                    <option value="<?php echo processing_dati::LAST_3_DAYS; ?>" <?php if ($datiprocessing->getSelInDate() === processing_dati::LAST_3_DAYS) { echo 'selected'; } ?>>Last 3 days</option>
                    <option value="<?php echo processing_dati::ALL_DAY; ?>" <?php if ($datiprocessing->getSelInDate() === processing_dati::ALL_DAY) { echo 'selected'; } ?>>All</option>
                    <?php foreach ($DatiSelInDate as $row) {
                        $day = $row['DD'];
                        $selected = ((string) $datiprocessing->getSelInDate() === (string) $day) ? 'selected' : '';
                        echo '<option value="' . $day . '" ' . $selected . '>' . $day . '</option>';
                    } ?>
                </select>

                <label for="NumLast">LAST</label>
                <input onblur="NumLastSubmit()" class="inputFilter" id="NumLast" name="NumLast" value="<?php echo (int) $datiprocessing->getNumLast(); ?>">
            </div>
        </div>

        <div class="divshow">
            <div class="divFilter">
                <label for="Sel_Esito">ESITO</label>
                <select class="inputFilter selectSearch" id="Sel_Esito" name="Sel_Esito" onchange="document.getElementById('formProcessing').submit();">
                    <option value="" <?php if ($datiprocessing->getSelEsito() === '') { echo 'selected'; } ?>>All</option>
                    <option value="I" <?php if ($datiprocessing->getSelEsito() === 'I') { echo 'selected'; } ?>>In Corso</option>
                    <option value="E" <?php if ($datiprocessing->getSelEsito() === 'E') { echo 'selected'; } ?>>Errore</option>
                    <option value="W" <?php if ($datiprocessing->getSelEsito() === 'W') { echo 'selected'; } ?>>Warning</option>
                    <option value="F" <?php if ($datiprocessing->getSelEsito() === 'F') { echo 'selected'; } ?>>Corretto</option>
                    <option value="M" <?php if ($datiprocessing->getSelEsito() === 'M') { echo 'selected'; } ?>>Manual</option>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelEserMese">ESER MESE</label>
                <select class="inputFilter selectSearch" name="SelEserMese" id="SelEserMese" onchange="document.getElementById('formProcessing').submit();">
                    <option value="" <?php if ($datiprocessing->getSelEserMese() === '') { echo 'selected'; } ?>>All</option>
                    <?php foreach ($DatiSelEserMese as $row) {
                        $eserMese = $row['ESER_MESE'];
                        $selected = ($datiprocessing->getSelEserMese() === $eserMese) ? 'selected' : '';
                        echo '<option value="' . $eserMese . '" ' . $selected . '>' . $eserMese . '</option>';
                    } ?>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelIdProc">PROCESS</label>
                <select class="inputFilter selectSearch inputFilterSelect" id="SelIdProc" name="Sel_Id_Proc" onchange="selectSelIdProc()">
                    <option value="" <?php if ($datiprocessing->getSelIdProc() === '') { echo 'selected'; } ?>>All</option>
                    <?php foreach ($DatiSelIdProc as $row) {
                        $idProc = $row['ID_PROCESS'];
                        $selected = ($datiprocessing->getSelIdProc() === $idProc) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($idProc, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($idProc, ENT_QUOTES, 'UTF-8') . '</option>';
                    } ?>
                </select>
            </div>

            <div class="divFilter">
                <label for="SelAmbito">Ambito</label>
                <select multiple="multiple" style="width: 300px;" class="inputFilter selectSearch inputFilterSelect" id="SelAmbito" name="SelAmbito[]">
                    <?php foreach ($DatiAmbiti as $row) {
                        $ambito = $row['AMBITO'];
                        $selected = in_array($ambito, $selectedAmbiti, true) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($ambito, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($ambito, ENT_QUOTES, 'UTF-8') . '</option>';
                    } ?>
                </select>
                <button id="filter" class="btn"><i class="fa-solid fa-filter"> </i> </button>
            </div>
        </div>
    </form>
</aside>
