<form id="FormVisualizzazione">
    <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
        <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>WFS Admin | Config Parametri</p>
        <div class="asideContent">

            <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>

            <input type="hidden" name="IdWorkFlow" id="IdWorkFlow" value="<?= $IdWorkFlow ?>">
            <select id="SelectWorkflow" name="id_workflow">
                <option value="">Seleziona un Workflow</option>
                <!-- I workflow verranno caricati dinamicamente -->
            </select>
            <select id="SelectGruppo" name="id_par_gruppo">
                <option value="">Seleziona un Gruppo</option>
                <!-- I gruppi verranno caricati dinamicamente -->
            </select>
            <button id="addGruppo" onclick="showFormParametriGruppo();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Gruppo</button>
           <button id="removeGruppo" onclick="removeParametriGruppi();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-trash-can"> </i> Gruppo</button>
            <button id="addParametro" onclick="showFormParametri();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Parametro</button>
            
</form>

</div>
</aside>
<br />
<div id="ParametriContainer"></div>
<div id="FormContainer"></div>
</form>