<form id="FormParametriGruppo">
        <label for="ParamGruppoLabel">Label Gruppo:</label>
        <input type="text" id="ParamGruppoLabel" name="label_gruppo" placeholder="Label Gruppo" required>

       <input type="hidden" id="id_workflow" name="id_workflow" value="<?=$id_workflow?>">

        <button id="addParametro" onclick="addParametriGruppo();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Gruppo</button>

    </form>

