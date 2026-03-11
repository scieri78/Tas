<?php
/** 
 * @property openLink_PlanDigit_model $_model
 */
class openLink_PlanDigit extends helper
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '        
	   <style>
       ' . file_get_contents("./view/openLink_PlanDigit/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_PlanDigit/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_PlanDigit_model();
        $this->setDebug_attivo(1);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $_view['include_css'] = $this->include_css;
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {

        $IdProcess = $_POST['IdProcess'];
        $Salva = $_POST['Salva'];
        $oldTipo = $_POST['oldTipo'];
        $SelTipo = $_POST['SelTipo'];
        $oldWave = $_POST['oldWave'];
        $oldDesc = $_POST['oldDesc'];
        $SelWave = $_POST['SelWave'];
        $Desc = $_POST['Desc'];
        $IdLegame = $_POST['LinkIdLegame'];
        $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
        $this->_model->insertPlanDigit();
        $datiParametriIdProcess = $this->_model->getParametriIdProcess($IdProcess);
        $datiPalnDigitWave = $this->_model->getPalnDigitWave($IdProcess);
        foreach ($datiParametriIdProcess as $row) {
        $TabCampo = $row['CAMPO'];
        $TabValore = $row['VALORE'];
        ${'Conf' . $TabCampo} = $TabValore;
        }
        include "view/openLink_PlanDigit/index.php";
    }
}
