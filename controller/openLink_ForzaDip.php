<?php

require_once("model/workflow2.php");
/** 
 * @property openLink_ForzaDip_model $_model
 * @property workflow2_model $_modelWF2
 */
class openLink_ForzaDip extends helper
{

    public $_modelWF2;
        
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
       $this->include_css = '        
	   <style>
       ' . file_get_contents("./view/openLink_ForzaDip/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_ForzaDip/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_ForzaDip_model();
        $this->_modelWF2 =  new workflow2_model();
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
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];
        $IdProcess = $_POST['IdProcess'];
        $Action = $_POST['Action'];
        $User = $_SESSION['codname'];
        $Uk = $_SESSION['CodUk'];
        $IdPeriod = $_SESSION['IdPeriod'];
        $IdFlu = $_POST['IdFlu'];
        $NomeFlusso = $_POST['Flusso'];
        $DescFlusso = $_POST['DescFlusso'];
        $ProcMeseEsame = substr($IdPeriod, 4, 3);
        $BarraCaricamento = "rgb(21, 140, 240)";
        $BarraPeggio = "rgb(165, 108, 185)";
        $BarraMeglio = "rgb(104, 162, 111)";

     //   $this->debug("",$_POST);
       // $this->debug("",$_FILES);
        $Lancia = $this->_modelWF2->switchAction();
        $this->_modelWF2->callElaborazioniPossibili($Action, $IdProcess, $IdWorkFlow);
        $ArrUsers = $this->_model->getListaUtentiForzaDip();
        $datiLegamiForzaDip = $this->_model->getLegamiForzaDip($IdProcess,$IdWorkFlow,$ProcMeseEsame,$Uk);
        include "view/openLink_ForzaDip/index.php";
    } 
   
}
?>