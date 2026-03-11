<?php
include_once("./model/statoshell.php");
/** 
 * @property mappingreti_model $_model
 * @property statoshell_model $_modelsh
 */
class mappingreti extends helper
{
     private $typesh;
     private $_modelsh;
    
    function __construct()
    {
	   $rand = rand(1000, 9999);
       $this->_model = new mappingreti_model();
	   $this->setDebug_attivo(1);
	   $this->include_css = '<script type="text/javascript" src="./view/mappingreti/JS/index.js?p='.$rand.'"></script>
								<link rel="stylesheet" href="./CSS/statoshell.css?p='.$rand.'">	
								<link rel="stylesheet" href="./CSS/StatoReti.css?p='.$rand.'">
								<link rel="stylesheet" href="./CSS/excel.css?p='.$rand.'">';
	    $this->_modelsh = new statoshell_model();
	   
	}
	
    // mvc handler request
    public function index()
    {
		$_view['include_css'] = $this->include_css; 
		include "view/header.php";
		$this->contentList();
		include "view/footer.php";
    } 
	
	public function contentList()
    {
		
		$SetRete=$_POST['SET_RETE'];
		$SelResearchType=$_POST['SEL_RESEARCH_TYPE'];
		$SelEnableRete=$_POST['SEL_ENABLE_RETE'];
		$SelFind=$_POST['SelFind'];
		$Filenamepart=$_POST['Filenamepart'];
		
		$this->get_errors_message();
		$datiRete = $this->_model->getRete();
		$datiRow = $this->_model->getRowCount();
		$datiTableList = $this->_model->getTableList($SelEnableRete, $SetRete, $SelFind);

		
		if ( $SelEnableRete == ""){
			$SelEnableRete="Y";
		}
		if ( $SelResearchType == ""){
			$SelResearchType="Table";
		}
		
		$Sel_Schema =@$_POST['Sel_Schema'];
		$Sel_Object= @$_POST['Sel_Object'];
		$arrs=explode('|',$Sel_Object);
		$Sel_Table=$arrs[0];
		$Sel_Type=$arrs[1];
		$datiTable = $this->_model->getTable($Sel_Schema);
		$datiSqlTable =$this->_model->getSQLTable($Sel_Schema, $Sel_Table);
		$datiPkgTable =$this->_model->getPkgTable($Sel_Schema, $Sel_Table);
		$datiViewTable =$this->_model->getViewTable($Sel_Schema, $Sel_Table);

		include "view/mappingreti/index.php";
		
    } 
	
	private function visualizzaFile($dati){
	
		foreach ($dati as $rowSH) {
		$ShName=$rowSH['SHELL'];
		$ShPath=$rowSH['SHELL_PATH'];
		}
		if ( $ShPath != "" ){
		$ret = $this->createTMPFile($ShPath,$ShName);
		$TestoFile = $ret['TestoFile'];
		$filename = $ret['filename'];
		}
		include('view/statoshell/ApriFile.php');
	}
	
	
	public function apriFile(){
		$IDSH=$_GET["IDSH"];	
		$datiFileInfo = $this->_modelsh->getFileInfo($IDSH);
		$this->visualizzaFile($datiFileInfo);
	}
	
	
}



?>