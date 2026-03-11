<?php
/** 
 * @property openLink_idlocale_model $_model
 */
class openLink_idlocale extends helper
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
       ' . file_get_contents("./view/openLink_idlocale/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_idlocale/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_idlocale_model();
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
       // $this->debug("",$_POST);
        $IdProcess = $_POST['IdProcess'];
        //$IdProcess = '202412000';
        $IdLegame = $_POST['LinkIdLegame'];
        $RdOnly = $_POST['RdOnly'];        
        $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
       
        $datiArgo = $this->_model->getArgo($IdProcess);
        include "view/openLink_idlocale/index.php";
    }

    /**
     * formUpdateArgo
     *
     * @return void
     */
    public function formUpdateArgo()
    {
        $IdProcess = $_POST['ID_PROCESS'];
        $COMPAGNIA = $_POST['COMPAGNIA'];

        $IdLegame = $_POST['LinkIdLegame'];
        $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
        $datiArgo = $this->_model->getArgo($IdProcess, $COMPAGNIA);
       
      //  $this->debug("_POST", $_POST);
        foreach ($datiArgo[0] as $k => $v) {
             ${$k} = $v;
        }
        include "view/openLink_idlocale/formUpdateArgo.php";
    }
    /**
     * updateArgo
     *
     * @return void
     */
    public function updateArgo()
    {

        $ID_PROCESS = $_POST['ID_PROCESS'];
        $ID_REMOTO = $_POST['ID_REMOTO'];
        $NAME = $_POST['NAME'];
        $DESCR = $_POST['DESCR'];
        $COMPAGNIA = $_POST['COMPAGNIA'];
        $this->_model->updateArgo($ID_REMOTO, $NAME, $DESCR, $ID_PROCESS, $COMPAGNIA);
       // $this->_model->ValidaLegame();
        $this->index();
    }

    public function ValidaLegame()
    {
        
        $this->_model->ValidaLegame();
        $this->_model->callElaborazioniPossibili();
      //  $this->index();
    }
}
