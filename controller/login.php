<?php
include_once('./model/authorizer.php');
/** 
 * @property login_model $_model
 * @property authorizer_model $_model_autorizer
 */
class login extends helper
{
	private $_model_autorizer;
	function __construct()
	{
        $aSITO = $_COOKIE[$_COOKIE['tab_id']];
			//$_SESSION['DATABASE'] = strtoupper($_SESSION['aSITO']);
		$_SESSION['DATABASE'] = strtoupper($aSITO);
		$_SESSION['TServer'] = $_SESSION['SERVER_NAME'] . " " . $_SESSION['DATABASE'];
		if (!isset($_SESSION)) $_SESSION = [];
		//echo "simone";
		$db_name = $_GET['sito']?$_GET['sito']:$_POST["db_name"]; 
		$this->_model =  new login_model($db_name);
		$this->_model_autorizer =  new authorizer_model();

		$this->setDebug_attivo(0);
	}
	// mvc handler request
	public function index()
	{
		$this->validaLogin();
	}
	// page redirection

	// check validation

	// add new record
	public function insert() {}
	// update record
	public function update() {}
	// delete record
	public function logout()
	{
		session_destroy();
		session_unset();
		session_destroy();
		unset($_SESSION);	
		if (!isset($_SESSION)) $_SESSION = [];	
		$_SESSION['UserValido'] = false;
		//$this->debug("logout session:", $_SESSION);

		$this->pageRedirect('index.php');
	}

	public function validaRoot($controller)
	{

		$ret = false;

		

		$datiSetVar = $this->_model->getSetVar();
		$this->debug("datiSetVar", $datiSetVar);
		
		foreach ($datiSetVar as $row) {
			${$row["CAMPO"]} = str_replace(["TASUSR", "TASWRK"], ["TASMVC", "TASMVC"], $row["VALORE"]);
			$_SESSION[$row["CAMPO"]] = str_replace(["TASUSR", "TASWRK"], ["TASMVC", "TASMVC"], $row["VALORE"]);
		}

		if (!isset($_SESSION['HTTP_USERID'])) {
			//$PrxUsr = $_SERVER['HTTP_USERID'];
			$_SESSION['HTTP_USERID'] = $_SERVER['HTTP_USERID'];			
		}
		$this->validaLogin();
		$_SESSION['MENU'] = $this->_model->getmenu();
		if ($controller == 'login' || $controller == 'home') {
			$ret = true;
		} elseif (isset($_SESSION['codNomi'])) {
			
			$datiMenu =	$this->_model->getMenuRoutes($controller);


			if ($datiMenu) {
				$ret = true;
			}
			//$this->cambiaUtente();
			
		}

		
		
		
       // $this->validaLogin();
		return $ret;
	}
	
	/**
	 * cambiaUtente 
	 *
	 * @return void
	 */
	public function changeUserForm()
		{
			
		$DatiUser = $this->_model_autorizer->getUser();		
		
			include "view/login/changeUserForm.php";
		}
				
		/**
		 * cambiaUtente
		 *
		 * @return void
		 */
	public function changeUser()
		{
			$_SESSION['OLD_USERID'] = $_SESSION['HTTP_USERID'];
			$_SESSION['OLD_codNomi'] = $_SESSION['codNomi'];			
			$_SESSION['HTTP_USERID'] = $_POST['userchange'];
			$_SESSION['IdWorkFlow']='';
            $_SESSION['IdPeriod']='';
            $_SESSION['IdProcess']='';
			$this->validaLogin();
		}

	public function exitUser()
		{
			$_SESSION['HTTP_USERID'] = $_SESSION['OLD_USERID'];
			unset($_SESSION['OLD_USERID']);
			unset($_SESSION['OLD_codNomi']);
			$this->validaLogin();			
			
		}


	public function validaLogin()
	{
		$ret = false;
		// print_r($_SESSION); 
		if ($_SESSION['HTTP_USERID']) {
			$dati_login = $this->_model->selectUserProd($_SESSION['HTTP_USERID']);
		} else {
			$dati_login = $this->_model->getDatiLogin(strtoupper($_POST['username']), $_POST['password']);
		}
	//$this->debug("PrxUsr:", $PrxUsr);
		
		if (count($dati_login)) {

			$ret = true;

			$this->debug("login");
			$i = 0;
			$GK_List = array();
			foreach ($dati_login as $dato) {
				//  include "view/footer.php";
				$_SESSION['codNomi'] = $dato['NOMINATIVO'];
				$_SESSION['codname'] = $dato['USERNAME'];
				$_SESSION['codpwd'] = $dato['PASSWORD'];
				$_SESSION['CodUk'] = $dato['UK'];
				$GK_List[$i++] = $dato['GK'];
			}
			$_SESSION['CodGroup'] = "'" . implode("','", $GK_List) . "'";
			$_SESSION['UserValido'] = true;
			$_SESSION['loginMess'] = "";
			$this->debug("dati login:", $_SESSION);
			$_SESSION['MENU'] = $this->_model->getmenu();
			$this->debug("Menu:", $_SESSION);
		} else {
			$_SESSION['loginMess'] = "Utente Non Abilitato";
		}

		$this->debug("Menu:", $dati_login);
		$this->debug("_SESSION:", $_SESSION);
		//die();
		//include "view/header.php";
		//include "view/footer.php";
		// $this->pageRedirect('index.php?controller=home&action=index');
		//( [UK] => 1 [USERNAME] => Simone [NOMINATIVO] => Simone Cieri [PASSWORD] => Simone ) 
		// $this->_model->login();
		return $ret;
	}
}
