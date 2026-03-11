<?php
/** 
 * @property loadconfig_model $_model
 */
class loadconfig extends helper
{

	private $typesh;

	/**
	 * __construct
	 *
	 * @return void
	 */
	function __construct()
	{
		$this->_model = new loadconfig_model();
		$this->setDebug_attivo(1);
		$this->include_css = '
	   <link rel="stylesheet" href="./view/loadconfig/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/loadconfig/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
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
	public function contentList($ID_LOAD_ANAG = '')
	{
		$this->error_message = $_SESSION['error_message'];
		$this->get_errors_message();
		
		$datiSchema = $this->_model->getSchema();
		$Sel_Schema = @$_POST['Sel_Schema'];
		$Sel_Object = @$_POST['Sel_Object'];
		$OPT_DEL = @$_POST['OPT_DEL'];
		$MANIPOL = @$_POST['MANIPOL'];

		$selectFOGLIO = @$_POST['selectFOGLIO'];
		$FOGLIO = ($_POST['selectFOGLIO']) ? $_POST['selectFOGLIO'] : $_POST['FOGLIO'];
		$RIGA_INZ = ($_POST['selectRIGA_INZ']) ? $_POST['selectRIGA_INZ'] : $_POST['RIGA_INZ'];
		//$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] : $_POST['Sel_Object'];
		$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] : "";
		$TARGET = ($_POST['Sel_Schema'] && $_POST['Sel_Object']) ? $_POST['Sel_Schema'] . "." . $_POST['Sel_Object'] : "";
		$TAB_TARGET = ($_POST['TAB_TARGET']) ? $_POST['TAB_TARGET'] : $TARGET;


		$FLUSSO = ($_POST['FLUSSO']) ? $_POST['FLUSSO'] : $_POST['Sel_Object'];
		$DatiFileName = $_POST['FileInput_NomeCampo'];
		$DatiAlias = $_POST['FileInput_Alias'];
		$DatiFileType = $_POST['FileInput_Type'];
		$datiDelNull = $_POST['FileInput_DelNull'];
		$DatiTargetName = $_POST['Target_NAME'];
		$DatiTargetType = $_POST['Target_TYPE'];
		$DatiTargetFormula = $_POST['Target_FORMULA'];
		$DatiTargetTrunc = $_POST['Target_TRUNC'];
		$arrs = explode('|', $Sel_Object);
		$Sel_Table = $arrs[0];
		$Sel_Type = $arrs[1];
		$datiTable = $this->_model->getTable($Sel_Schema);
		//	$datiSqlTable =$this->_model->getSQLTable($Sel_Schema, $Sel_Object);
		$datiTableOutput = $this->_model->getTableOutput($Sel_Schema, $Sel_Object);
	
		$FOGLIO = str_replace(",", "|", $FOGLIO);
		$FOGLI = explode("|", $FOGLIO);
		//$this->debug("contentList FOGLIO",$FOGLIO);
		//$this->debug("contentList FOGLI",$FOGLI);
		$DatiFile = $this->_model->getDatiFile($FOGLI, $RIGA_INZ);
		$DatiFile = $this->_model->setDataFile($DatiFile, $datiTableOutput);


		if (!empty($DatiFileName)) {
			$DatiFile = [];
			foreach ($DatiFileName as $k => $FileName) {
				$DatiFile[$k]['COLONNA'] = $FileName;
				$DatiFile[$k]['TIPO'] = $DatiFileType[$k];
				$DatiFile[$k]['COLALIAS'] = $DatiAlias[$k];
				$DatiFile[$k]['DELNULL'] = $datiDelNull[$k];
				
			}
		}
		if (!empty($DatiTargetName)) {
			$datiTableOutput = [];
			foreach ($DatiTargetName as $k => $TargetName) {
				$datiTableOutput[$k]['COLONNA'] = $TargetName;
				$datiTableOutput[$k]['TIPO'] = $DatiTargetType[$k];
				$datiTableOutput[$k]['FORMULA'] = $DatiTargetFormula[$k];
				$datiTableOutput[$k]['TRUNC'] = $DatiTargetTrunc[$k];
			}
		}
		//  $this->debug("contentList _POST",$_POST);
		//$TestoFile = $datiSqlTable['TestoFile'];
		//$filename = $datiSqlTable['filename'];
		/* $this->debug("_POST",$_POST);
		$this->debug("_FILES",$_FILES);*/
		$fileName = $_FILES["FileInput"]["name"];
		//$this->debug("DatiFile",$DatiFile);
		//$this->debug("datiTableOutput",$datiTableOutput);
		$ID_LOAD_ANAG = ($ID_LOAD_ANAG) ? $ID_LOAD_ANAG : $_POST['ID_LOAD_ANAG'];
		$datiConfigurazione = $this->_model->selectConfig($TAB_TARGET);
		include "view/loadconfig/index.php";
	}


	public function modificaconfigurazione()
	{
		
		$datiSchema = $this->_model->getSchema();
		$Sel_Schema = @$_POST['Sel_Schema'];
		$Sel_Object = @$_POST['Sel_Object'];
		$selectFOGLIO = @$_POST['selectFOGLIO'];
		$FOGLIO = ($_POST['selectFOGLIO']) ? $_POST['selectFOGLIO'] : $_POST['FOGLIO'];
		$RIGA_INZ = ($_POST['selectRIGA_INZ']) ? $_POST['selectRIGA_INZ'] : $_POST['RIGA_INZ'];

		$FLUSSO = ($_POST['FLUSSO']) ? $_POST['FLUSSO'] : $_POST['Sel_Object'];
		//$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] : $_POST['Sel_Object'];
		$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] : "";
		$TARGET = ($_POST['Sel_Schema'] && $_POST['Sel_Object']) ? $_POST['Sel_Schema'] . "." . $_POST['Sel_Object'] : "";
		$TAB_TARGET = ($_POST['TAB_TARGET']) ? $_POST['TAB_TARGET'] : $TARGET;
		$DatiFileName = $_POST['FileInput_NomeCampo'];
		$DatiAlias = $_POST['FileInput_Alias'];
		$DatiFileType = $_POST['FileInput_Type'];
		$DatiTargetName = $_POST['Target_NAME'];
		$DatiTargetType = $_POST['Target_TYPE'];
		$DatiTargetFormula = $_POST['Target_FORMULA'];
		$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		//$this->debug("modificaconfigurazione _POST",$_POST);

		$arrs = explode('|', $Sel_Object);
		$Sel_Table = $arrs[0];
		$Sel_Type = $arrs[1];
		if ($ID_LOAD_ANAG) {
			$LoadAnag =	$this->_model->selectLoadAnag($ID_LOAD_ANAG);
		
			$TAB_TARGET = $LoadAnag[0]['TAB_TARGET'];
			$array_Target = explode(".", $TAB_TARGET);
			$Sel_Schema = $array_Target[0];
			$Sel_Object = $array_Target[1];
			$datiTable = $this->_model->getTable($Sel_Schema);
			$DatiLoadAnag = $this->_model->selectLoadAnag($ID_LOAD_ANAG);
			foreach ($DatiLoadAnag as $LoadAnag) {
				$FOGLIO = $LoadAnag['FOGLIO'];
				$RIGA_INZ = $LoadAnag['RIGA_INZ'];
				$FLUSSO = $LoadAnag['FLUSSO'];
				$DESCR = $LoadAnag['DESCR'];
				$OPT_DEL = $LoadAnag['OPT_DEL'];
				$MANIPOL = $LoadAnag['MANIPOL'];
			}
			//	$datiSqlTable =$this->_model->getSQLTable($Sel_Schema, $Sel_Object);
			$datiTableOutput = $this->_model->getTableOutput($Sel_Schema, $Sel_Object);
			$datiTableOutput = $this->_model->selectLoadTarget($ID_LOAD_ANAG, $datiTableOutput);
			$DatiFile = $this->_model->selectLoadFile($ID_LOAD_ANAG);

			if ($_FILES["FileInput"]["tmp_name"] && $_POST['selectFOGLIO'] && $_POST['selectRIGA_INZ']) {
				$FOGLIO = ($_POST['selectFOGLIO']) ? $_POST['selectFOGLIO'] : $_POST['FOGLIO'];
				$RIGA_INZ = ($_POST['selectRIGA_INZ']) ? $_POST['selectRIGA_INZ'] : $_POST['RIGA_INZ'];
				$fileName = $_FILES["FileInput"]["name"];
				$FOGLIO = str_replace(",", "|", $FOGLIO);
				$FOGLI = explode("|", $FOGLIO);
				//	$this->debug("modificaconfigurazione FOGLI",$FOGLI);
				$DatiFile = $this->_model->getDatiFile($FOGLI, $RIGA_INZ);
				$DatiFile = $this->_model->setDataFile($DatiFile, $datiTableOutput);
			}
			//$this->debug('DatiFile',$DatiFile);


			$TAB_TARGET = ($_POST['TAB_TARGET']) ? $_POST['TAB_TARGET'] : $Sel_Schema . "." . $Sel_Object;
			$datiConfigurazione = $this->_model->selectConfig($TAB_TARGET);
			//$DatiFile = $this->_model->setDataFile($DatiFile,$datiTableOutput);
		/*	$this->debug("LoadAnag",$LoadAnag );
			$this->debug("datiConfigurazione",$datiConfigurazione );
			$this->debug("TAB_TARGET",$TAB_TARGET );*/
			//$this->debug("modificaconfigurazione _POST",$_POST);
			$this->error_message = $_SESSION['error_message'];			
			 $this->get_errors_message();
			include "view/loadconfig/index.php";
		} else {
			$this->contentList();
		}
	}


	/**
	 * openfoglio
	 *
	 * @return void
	 */
	public function openfoglio()
	{

		$datiFogli = $this->_model->getFogli();
		echo json_encode($datiFogli);
	}
	/**
	 * getconfigurazione
	 *
	 * @return void
	 */
	public function getconfigurazione()
	{
		$FOGLIO = ($_POST['selectFOGLIO']) ? $_POST['selectFOGLIO'] : $_POST['FOGLIO'];
		$RIGA_INZ = ($_POST['selectRIGA_INZ']) ? $_POST['selectRIGA_INZ'] : $_POST['RIGA_INZ'];
		$FLUSSO = ($_POST['FLUSSO']) ? $_POST['FLUSSO'] : $_POST['Sel_Object'];
		//$TAB_TARGET = $_POST['Sel_Schema'] . "." . $_POST['Sel_Object'];
		$TAB_TARGET = ($_POST['TAB_TARGET']) ? $_POST['TAB_TARGET'] : $_POST['Sel_Schema'] . "." . $_POST['Sel_Object'];
		$datiConfigurazione = $this->_model->selectConfig($TAB_TARGET);
		echo json_encode($datiConfigurazione);
	}


	/**
	 * insertconfig
	 *
	 * @return void
	 */
	public function insertconfig()
	{
		$FLUSSO = strtoupper($_POST['FLUSSO']);
		$FLUSSO = str_replace(" ","_",trim($FLUSSO));
		$FOGLIO = strtoupper($_POST['FOGLIO']);
		$RIGA_INZ = $_POST['RIGA_INZ'];
		$TAB_TARGET = ($_POST['TAB_TARGET']) ? $_POST['TAB_TARGET'] : $_POST['Sel_Schema'] . "." . $_POST['Sel_Object'];
		$DatiFileName = $_POST['FileInput_NomeCampo'];
		$DatiFileType = $_POST['FileInput_Type'];
		$DatiAlias = $_POST['FileInput_Alias'];
		$datiDellNull = $_POST['FileInput_DelNull'];
		
		$DatiTargetName = $_POST['Target_NAME'];
		$DatiTargetType = $_POST['Target_TYPE'];
		$DatiTargetFormula = $_POST['Target_FORMULA'];
		$DatiTargetTrunc = $_POST['Target_TRUNC'];
		$nuovo =  $_POST['nuovo'];
		$ID_LOAD_ANAG = (!$_POST['ID_LOAD_ANAG'] || $nuovo) ? 0 : $_POST['ID_LOAD_ANAG'];

		/*$this->debug("post nuovo",$nuovo);
		$this->debug("post ID_LOAD_ANAG",$_POST['ID_LOAD_ANAG']);
		$this->debug("ID_LOAD_ANAG",$ID_LOAD_ANAG);
		$this->debug("FLUSSO",$FLUSSO);
		die();*/
		$OPT_DEL =  $_POST['OPT_DEL'];
		$MANIPOL =  $_POST['MANIPOL'];
		
	//	$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] : $_POST['Sel_Object'];
		$DESCR = ($_POST['DESCR']) ? $_POST['DESCR'] :"";
		$Ret = $this->_model->deleteconfig($ID_LOAD_ANAG,$FLUSSO);	
	//	$this->debug('',$_SESSION['error_message']);
	//	die();	
		if($Ret){
		$ID_LOAD_ANAG = $this->_model->insertConfig($ID_LOAD_ANAG, $FLUSSO, $FOGLIO, $RIGA_INZ, $TAB_TARGET, $DESCR, $OPT_DEL, $MANIPOL);		
		$this->_model->insertFile($ID_LOAD_ANAG, $DatiFileName, $DatiFileType, $DatiAlias,$datiDellNull);
		$this->_model->insertTarget($ID_LOAD_ANAG, $DatiTargetName, $DatiTargetType, $DatiTargetFormula, $DatiTargetTrunc);
		//$this->debug("insertconfig post",$_POST);
		$this->contentList($ID_LOAD_ANAG);
		}else{
		
		$this->contentList($ID_LOAD_ANAG);	
		}
	}	
public function  EliminaConfigurazione(){
	$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
	$this->_model->deleteconfigTot($ID_LOAD_ANAG);	
}
	
	public function addColForm()
	{

		$I_ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		$DatiColums = $this->_model->selectLoadFile($I_ID_LOAD_ANAG);
		include "view/loadconfig/addColForm.php";
	}
	public function delColForm()
	{
		$I_ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		include "view/loadconfig/delColForm.php";
	}
	
	/**
	 * AddFileCol
	 *
	 * @return void
	 */
	public function AddFileCol()
	{
		$I_ID_LOAD_ANAG = $_POST['I_ID_LOAD_ANAG'];
		$I_PRECCOLNAME = $_POST['I_PRECCOLNAME'];
		$I_COLNAME = $_POST['I_COLNAME'];
		$this->_model->AddFileCol($I_ID_LOAD_ANAG, $I_PRECCOLNAME, $I_COLNAME);
		//$this->_model->DelFileCol($I_ID_LOAD_ANAG,$I_COLNAME);
		$this->contentList($I_ID_LOAD_ANAG);
	}


	public function DelFileCol()
	{
		$I_ID_LOAD_ANAG = $_POST['I_ID_LOAD_ANAG'];	
		$I_COLNAME = $_POST['I_COLNAME'];
		$this->_model->DelFileCol($I_ID_LOAD_ANAG, $I_COLNAME);
		//$this->_model->DelFileCol($I_ID_LOAD_ANAG,$I_COLNAME);
		$this->contentList($I_ID_LOAD_ANAG);
	}
	/**
	 * viewCheck
	 *
	 * @return void
	 */
	public function viewCheck()
	{

		$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];

		$datiCheck = $this->_model->getCheck($ID_LOAD_ANAG);

		include "view/loadconfig/check.php";
	}



	
	/**
	 * addfcheck
	 *
	 * @return void
	 */
	public function addfcheck()
	{

		$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		$ID_LOAD_CHECK = $_POST['ID_LOAD_CHECK'];
		//$this->debug("modificaconfigurazione _POST",$_POST);


		if ($ID_LOAD_CHECK) {
			$datiCheck = $this->_model->getCheck($ID_LOAD_ANAG, $ID_LOAD_CHECK);
		}

		include "view/loadconfig/addCheck.php";
	}
	/**
	 * delCheck
	 *
	 * @return void
	 */
	public function delCheck()
	{
		$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		$ID_LOAD_CHECK = $_POST['ID_LOAD_CHECK'];
		$del = $this->_model->delCheck($ID_LOAD_CHECK);
		$this->viewCheck();
	}

	
	/**
	 * modCheck
	 *
	 * @return void
	 */
	public function modCheck()
	{
		$ID_LOAD_ANAG = $_POST['ID_LOAD_ANAG'];
		$ID_LOAD_CHECK = $_POST['ID_LOAD_CHECK'];
		$LABEL = $_POST['LABEL'];
		$TEST = $_POST['TEST'];
		$METODO = $_POST['METODO'];
		$ATTESO = $_POST['ATTESO'];
		//$this->debug('_POST',$_POST);
		$del = $this->_model->modCheck($ID_LOAD_CHECK, $ID_LOAD_ANAG, $LABEL, $TEST, $METODO, $ATTESO);
		$this->viewCheck();
	}
}
