<?php
/** 
 * @property openLink_parametri_model $_model
 */
class openLink_parametri extends helper
{

    function __construct()
    {
        $this->include_css = '        
	   <style>
       ' . file_get_contents("./view/openLink_parametri/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_parametri/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_parametri_model();
        $this->setDebug_attivo(1);
    }

    public function index()
    {
        $_view['include_css'] = $this->include_css;
        $IdLegame = $_POST['LinkIdLegame'];
        $idLink = $_POST['idLink'];
        $LinkNameDip = $_POST['LinkNameDip'];       
        
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    public function contentList()
    {
        //debug session  
               
        $IdProcess = $_POST['IdProcess'];
        $idLink = $_POST['idLink'];
        $Flusso = $_POST['Flusso'];
        $LinkNameDip = $_POST['LinkNameDip'];
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];        
        $IdLegame = $_POST['LinkIdLegame'];
        $RdOnly = $_POST['RdOnly']; 
        $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
        $DataParametri = $this->_model->getParametriGruppo($idLink,$IdWorkFlow,$IdProcess);
        $ResParametri=$this->_model->getParametriIdProcess($IdProcess, $idLink);             
        include "view/openLink_parametri/index.php";
    }

    //insert parametri
    public function insertParametri()
    {
        $IdRule = $_POST['IdRule'];
        $IdRiskRule = $_POST['IdRiskRule'];
        $IdProcess = $_POST['IdProcess'];
        $LinkNameDip = $_POST['LinkNameDip'];
        $idLink = $_POST['idLink'];
        $Flusso = $_POST['Flusso'];       
        $this->_model->insertPatametri($IdProcess, $Flusso,$LinkNameDip, $idLink);
        $this->_model->ValidaLegame();
    }
}
