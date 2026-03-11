<?php
/** 
 * @property dipstatus_model $_model
 */
class dipstatus extends helper
{
    
    
    function __construct()
    {
		$this->include_css = '
	   <link rel="stylesheet" href="./view/dipstatus/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/dipstatus/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
       $this->_model =  new dipstatus_model();
       $this->setDebug_attivo(1);
    }
 
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";
      $this->contentList();
	  include "view/footer.php";
    }
 
   
    public function contentList(){
		
        $Azione=$_POST['Azione'];
		$User=$_SESSION['codname'];
		$SelIdTeam=$_POST['SelIdTeam']?$_POST['SelIdTeam']:$_SESSION['IdTeam'];
		$SelWkf=$_POST['SelWkf']?$_POST['SelWkf']:$_SESSION['IdWorkFlow'];
		$SelFls=$_POST['SelFls'];
		$SelTp=$_POST['SelTp'];
		$SelDp=$_POST['SelDp'];
		$SelIdProc=$_POST['SelIdProc'];
		$SelSt=$_POST['SelSt'];
		$HideSave="no";   
		$datiTeam = $this->_model->getTeam();
		$datiWorkflow = $this->_model->getWorkflow($SelIdTeam);
		$datiFlussi = $this->_model->getFlussi($SelWkf);
		$datiLegameFlussi = $this->_model->getLegameFlussi($SelWkf,$SelFls);
		$datiDipendenza = $this->_model->getDipendenza($SelWkf,$SelFls,$SelTp);
		$datiIdProcess = $this->_model->getIdProcess($SelWkf);
		$datiStato = $this->_model->getAction($Azione,$SelSt,$SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp,$User);
		$datiStato = $this->_model->getStato($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp);
		$datiCodaStorico = $this->_model->getCodaStorico($SelIdProc ,$SelWkf ,$SelFls ,$SelTp ,$SelDp);
		$tabellaStato = $this->getTabellaByArray($datiStato,'ULTIMO STATO');
		$tabellaCodaStorico = $this->getTabellaByArray($datiCodaStorico,'CODA STORICO');
	//$this->debug("datiStato",$datiStato);
        include "view/dipstatus/index.php";
      
    }
	
}



?>