<form id="FormParametri">
    <input type="hidden" name="ID_WORKFLOW" Id="ParamIdWorkflow" value="<?= $ID_WORKFLOW ?>">
    <input type="hidden" name="ID_PAR_GRUPPO" Id="ParamIdParGruppo" value="<?= $ID_PAR_GRUPPO ?>">
    <input type="hidden" name="ID_PAR" Id="ParamIdPar" value="<?= $ID_PAR ?>">
    <label for="ParamLabel">Label:</label>
    <input type="text" id="ParamLabel" name="LABEL" placeholder="Label" required value="<?= htmlspecialchars($LABEL) ?>">
    <br />
    <label for="ParamNome">Nome:</label>
    <input type="text" id="ParamNome" name="NOME" placeholder="Nome" required value="<?= htmlspecialchars($NOME) ?>">
    <br />
    <label for="ParamDesc">Descrizione:</label>
    <input type="text" id="ParamDesc" name="DESC" placeholder="Descrizione" value="<?= htmlspecialchars($DESC) ?>">
    <br />
    <label for="ParamTipoInput">Tipo Input:</label>
    <select id="ParamTipoInput" name="TIPO_INPUT" onchange="selectTipoImput()">
        <option value="">SELEZIONA TIPO INPUT</option>
        <option value="select" <?= $TIPO_INPUT == 'select' ? 'selected' : '' ?>>Select</option>
        <option value="text" <?= $TIPO_INPUT == 'text' ? 'selected' : '' ?>>Testo</option>
        <option value="number" <?= $TIPO_INPUT == 'number' ? 'selected' : '' ?>>Numero</option>
        <option value="datetime" <?= $TIPO_INPUT == 'datetime' ? 'selected' : '' ?>>Datetime</option>
        <option value="checkbox" <?= $TIPO_INPUT == 'checkbox' ? 'selected' : '' ?>>Checkbox</option>
        <option value="radio" <?= $TIPO_INPUT == 'radio' ? 'selected' : '' ?>>Radio</option>
        <option value="textarea" <?= $TIPO_INPUT == 'textarea' ? 'selected' : '' ?>>Textarea</option>
    </select>
    <br />

    <label for="ParamSelect">Select:</label>
    <textarea id="ParamSelect" name="SELECT" placeholder="Select"><?= htmlspecialchars($SELECT) ?></textarea>
    <div class="valueSelect" <?=  ($TIPO_INPUT == 'radio' ||  $TIPO_INPUT == 'select' || $TIPO_INPUT == 'checkbox') ? '' : 'style="display:none"' ?>>
         Inserire nella select due valori come AS 'VALUE' e 'DESCR' come:<br>
         SELECT ID_RULE as VALUE, NAME_RULE as DESCR FROM IFRS17.MAP_RULE WHERE TIPO = 'BestEstimates' ORDER BY TIME_RULE DESC
    </div>
    <div class="valueText" <?=  ($TIPO_INPUT == 'text' ||  $TIPO_INPUT == 'datetime' || $TIPO_INPUT == 'number'  || $TIPO_INPUT == 'textarea') ? '' : 'style="display:none"' ?>>
         Inserire nella select un solo valore come AS 'DEFAULT' e se necessario un %ID_PROCESS% come:<br>
         SELECT numerval as DEFAULT FROM tas.tabella where idporcess = %ID_PROCESS%
    </div>
    <br />
    <label for="ParamDefault">Default:</label>
    <input type="text" id="ParamDefault" name="DEFAULT" placeholder="Default" value="<?= htmlspecialchars($DEFAULT) ?>">
    <br />

    <div class="text" <?= $TIPO_INPUT != 'text' ? 'style="display:none"' : '' ?>>
        <label for="ParamLength">Length:</label>
        <input type="number" id="ParamLength" name="LENGTH" placeholder="Length" value="<?= htmlspecialchars($LENGTH) ?>">
        <br />
    </div>

    <div class="number" <?= $TIPO_INPUT != 'number' ? 'style="display:none"' : '' ?>>
        <label for="ParamPrecision">Precision:</label>
        <input type="number" id="ParamPrecision" name="PRECISION" placeholder="Precision" value="<?= htmlspecialchars($PRECISION) ?>">
        <br />
    </div>

    <button id="addParametro" onclick="addParametri();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Parametro</button>
</form>