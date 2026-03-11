<?php
/** 
 * @property openLink_argo_model $_model
 */
class openLink_argo extends helper
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
       ' . file_get_contents("./view/openLink_argo/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_argo/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_argo_model();

        $this->setDebug_attivo(1);
    }

    public function index()
    {
        $_view['include_css'] = $this->include_css;
        $IdLegame = $_POST['LinkIdLegame'];
        $idLink = $_POST['idLink'];
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    public function contentList()
    {
        //debug session  

        $IdProcess = $_POST['IdProcess'];
        $DataBestEstimates = $this->_model->getBestEstimates();
        $DataRiskAdjustament = $this->_model->getRiskAdjustament();
        $id_rule = $this->_model->getParametriIdProcess($IdProcess);
        $id_rule_risk = $this->_model->getParametriIdProcessRisk($IdProcess); 
        $IdLegame = $_POST['LinkIdLegame'];
        $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
        include "view/openLink_argo/index.php";
    }

    //insert parametri
    public function insertParametri()
    {
        $IdRule = $_POST['IdRule'];
        $IdRiskRule = $_POST['IdRiskRule'];
        $IdProcess = $_POST['IdProcess'];

        $this->_model->insertPatametri($IdProcess, $IdRule, $IdRiskRule);
        $this->_model->ValidaLegame();
    }
}
