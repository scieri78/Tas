<?php
/** 
 * @property authorizer_model $_model
 */
class authorizer extends helper
{

	private $typesh;

	function __construct()
	{
		$this->include_css = '
			<link rel="stylesheet" href="./view/authorizer/CSS/index.css?p=' . rand(1000, 9999) . '">
			<script src="./view/authorizer/JS/index.js?p=' . rand(1000, 9999) . '"></script>';
		$this->_model = new authorizer_model();
		$this->setDebug_attivo(1);

		/* if ( $AddMail != "" ){
		$this->addMail($AddMail);
    }*/
	}

	// mvc handler request	
	/**
	 * index
	 *
	 * @return void
	 */
	public function index()
	{
		$_view['include_css'] = $this->include_css;
		include "view/header.php";
		$this->contentList();
		include "view/footer.php";
	}
	
	/**
	 * contentList
	 *
	 * @return void
	 */
	public function contentList()
	{
		$Azione = @$_POST['Azione'];
		$WorkFlow = @$_POST['WorkFlow'];
		$Descr = @$_POST['Descr'];
		$IdTeam = @$_POST['IdTeam']?$_POST['IdTeam']:$_SESSION['IdTeam'];
		$IdWorkFlow = @$_POST['selIdWorkFlow']?$_POST['selIdWorkFlow']:$_SESSION['IdWorkFlow'];
		$DatiTeams = $this->_model->getTeams();
		$DatiWF = $this->_model->getAuthorizer($IdTeam);
		include "view/authorizer/index.php";
	}
	
	/**
	 * LoadWf
	 *
	 * @return void
	 */
	public function LoadWf()
	{
		$IdTeam = @$_POST['selIdTeam'];
		$selIdWorkFlow = @$_POST['selIdWorkFlow'];
		$DatiAuthorizer = $this->_model->getAuthorizer($IdTeam, $selIdWorkFlow);
		//$this->debug("DatiAuthorizer", $DatiAuthorizer);
		include "view/authorizer/LoadWf.php";
	}

	
	/**
	 * loadflussi
	 *
	 * @return void
	 */
	public function loadflussi()
	{
		$WorkFlow = @$_POST['WorkFlow'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$selectFlusso = @$_POST['selectFlusso'];
		$DatiSelectFlussi = $this->_model->getFlussi($IdWorkFlow);
		$DatiFlussi = $this->_model->getFlussi($IdWorkFlow, $selectFlusso);
		$DatiAutorizzazioni = $this->_model->getAutorizzazioni($IdWorkFlow);
		include "view/authorizer/LoadFlussi.php";
	}
	
	/**
	 * addgroup
	 *
	 * @return void
	 */
	public function addgroup()
	{
		$WorkFlow = @$_POST['WorkFlow'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		//$datiFlussi = $this->_model->getFlussi($IdWorkFlow);
		//$DatiAutorizzazioni = $this->_model->getAutorizzazioni($IdWorkFlow);
		include "view/authorizer/AddGroup.php";
	}
	
	/**
	 * addgroupto
	 *
	 * @return void
	 */
	public function addgroupto()
	{
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];;
		$WorkFlow = @$_REQUEST['WorkFlow'];
		$IdFlusso = @$_REQUEST['IdFlusso'];
		$Flusso = @$_REQUEST['Flusso'];


		$DatiGruppi = $this->_model->getGruppi($IdWorkFlow, $IdFlusso);
		//$datiFlussi = $this->_model->getFlussi($IdWorkFlow);
		//$DatiAutorizzazioni = $this->_model->getAutorizzazioni($IdWorkFlow);
		include "view/authorizer/AddGroupTo.php";
	}
	
	/**
	 * loadflusso
	 *
	 * @return void
	 */
	public function loadflusso()
	{
		$this->_model->addAzioneFlusso();
		$this->loadflussi();
	//	include "view/authorizer/LoadFlussi.php";
	}
	
	/**
	 * loaddettwfs
	 *
	 * @return void
	 */
	public function loaddettwfs()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$Azione = $_POST['Azione'];
		$Errore = 0;
		$Note = "";
		$this->_model->addAzioneDett();
		$DatiListGR = $this->_model->getListGR($IdWorkFlow);
		include "view/authorizer/LoadDett.php";
	}

	//funzione porova
	
		
	/**
	 * loaduser
	 *
	 * @return void
	 */
	public function loaduser()
	{
		$IdFlusso = @$_POST['IdFlusso'];
		$Flusso = @$_POST['Flusso'];
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$IdGruppo = $_POST['IdGruppo'];
		$Gruppo = $_POST['Gruppo'];
		$ret = $this->_model->addAzione();
		if($ret['Errore'])
			{
			$this->error_message = $ret['Note'];
			}
		$this->get_errors_message();
		$DatiUtenti = $this->_model->getUtenti($IdGruppo);
		$CntU = count($DatiUtenti);
		include "view/authorizer/LoadUser.php";
	}
	
	/**
	 * loaddett
	 *
	 * @return void
	 */
	public function loaddett()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$Azione = $_POST['Azione'];
		$Errore = 0;
		$Note = "";
		$this->_model->addAzioneDett();
		$DatiListGR = $this->_model->getListGR($IdWorkFlow);

		include "view/authorizer/LoadDett.php";
	}
	
	/**
	 * adduser
	 *
	 * @return void
	 */
	public function adduser()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$IdGruppo = $_POST['IdGruppo'];
		$Gruppo = $_POST['Gruppo'];

		$Azione = $_POST['Azione'];
		$Errore = 0;
		$Note = "";
		$DatiUser = $this->_model->getUser($IdGruppo);


		include "view/authorizer/AddUser.php";
	}
	
	/**
	 * modauthdipflu
	 *
	 * @return void
	 */
	public function modauthdipflu()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$IdFlusso = $_POST['IdFlusso'];
		$Flusso = $_POST['Flusso'];
		//$Azione=$_POST['Azione'];
		$Errore = 0;
		$Note = "";
		//$this->debug('post',$_POST);
		$this->_model->AzioneUatoDip();
		$DatiListDipFlusso = $this->_model->getListDipFlusso($IdWorkFlow, $IdFlusso);
		$DatiAutoDip = $this->_model->getAutoDip($IdWorkFlow, $IdFlusso);
		include "view/authorizer/LoadAuthDipFlusso.php";
	}
	
	/**
	 * addgrouptodip
	 *
	 * @return void
	 */
	public function addgrouptodip()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$WorkFlow = $_POST['WorkFlow'];
		$IdFlusso = $_POST['IdFlusso'];
		$Flusso = $_POST['Flusso'];
		$Tipo = $_POST['Tipo'];
		$IdDip = $_POST['IdDip'];
		$Dipendenza = $_POST['Dipendenza'];
		$Azione = $_POST['Azione'];
		$Errore = 0;
		$Note = "";
		$DatiGruppiDip = $this->_model->getGruppiDip($IdWorkFlow, $IdFlusso, $Tipo, $IdDip);
		include "view/authorizer/AddGroupToDip.php";
	}
	/**
	 * getAuthorizer
	 *
	 * @return void
	 */
	public function getAuthorizer()
	{
		$IdTeam = $_POST['selIdTeam'];
		//$datiWorkflow = $this->_model->getWorkFlow($IdTeam);
		$DatiAuthorizer = $this->_model->getAuthorizer($IdTeam);
		echo json_encode($DatiAuthorizer);
	}
}
