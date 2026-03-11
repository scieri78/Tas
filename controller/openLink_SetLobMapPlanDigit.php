<?php
/** 
 * @property openLink_SetLobMapPlanDigit_model $_model
 */
class openLink_SetLobMapPlanDigit extends helper
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
       ' . file_get_contents("./view/openLink_SetLobMapPlanDigit/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_SetLobMapPlanDigit/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_SetLobMapPlanDigit_model();
        $this->setDebug_attivo(1);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];
        $_view['include_css'] = $this->include_css;       
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
        $Salva = $_POST['Salva'];
        $oldTipo = $_POST['oldTipo'];
        $SelTipo = $_POST['SelTipo'];
        $oldWave = $_POST['oldWave'];
        $SelWave = $_POST['SelWave'];
        $SelAnno = $_POST['SelAnno'];
        $IdProcess = $_POST['IdProcess']; 
        $IdLegame = $_POST['LinkIdLegame'];
       // $this->debug("",$_POST);      
        $this->_model->insertPlanDigit();
        $datiParametriIdProcess = $this->_model->getParametriIdProcess($IdProcess);
         $legameValido = $this->_model->getEsitoValidato($IdProcess, $IdLegame);
        foreach ($datiParametriIdProcess as $row) {
            $TabCampo = $row['CAMPO'];
            $TabValore = $row['VALORE'];           
            ${'Conf' . $TabCampo} = $TabValore;
            }
        
        
      //  $datiPlanDigitWave = $this->_model->getPlanDigitWave($IdProcess);
         $datiEserEsame = $this->_model->getEserEsame($IdProcess);
        include "view/openLink_SetLobMapPlanDigit/index.php";
    }


    public function getPlanDigitWave()
	{
		$SelAnno = $_POST['SelAnno'];
		$PlanDigitWave = $this->_model->getPlanDigitWave($SelAnno);
		echo json_encode($PlanDigitWave);
	}
}
