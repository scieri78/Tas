<?php
include_once("./model/statoshell.php");
/** 
 * @property relationtab_model $_model
 * @property statoshell_model $_modelsh
 */
class relationtab extends helper
{    
    private $typesh;
    private $_modelsh;    
    function __construct()
    {
       $this->_model = new relationtab_model();
	   $this->setDebug_attivo(1);
	   $this->include_css = '
	   <link rel="stylesheet" href="./view/relationtab/CSS/index.css?p=' . rand(1000, 9999) . '" />  
	   <script type="text/javascript" src="./view/relationtab/JS/index.js?p='.rand(1000,9999).'"></script>';
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
		$this->get_errors_message();
		$datiSchema = $this->_model->getSchema();
		$Sel_Schema =@$_POST['Sel_Schema'];
		$Sel_Object= @$_POST['Sel_Object'];
		$arrs=explode('|',$Sel_Object);
		$Sel_Table=$arrs[0];
		$Sel_Type=$arrs[1];
		$datiTable = $this->_model->getTable($Sel_Schema);
		$datiSqlTable =$this->_model->getSQLTable($Sel_Schema, $Sel_Table);
		$datiPkgTable =$this->_model->getPkgTable($Sel_Schema, $Sel_Table);
		$datiViewTable =$this->_model->getViewTable($Sel_Schema, $Sel_Table);

		include "view/relationtab/index.php";
    } 
	
	private function visualizzaFile($dati)
	{
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
	
	
	public function apriFile()
	{
		$IDSH=$_GET["IDSH"];	
		$datiFileInfo = $this->_modelsh->getFileInfo($IDSH);
		$this->visualizzaFile($datiFileInfo);
	}
	//crea funzione downloadList come quella in searchcoll
	public function downloadfile()
	{
		$Sel_Schema = @$_POST['Sel_Schema'];
		$Sel_Object= @$_POST['Sel_Object'];
		$arrs=explode('|',$Sel_Object);
		$Sel_Table=$arrs[0];
		$Sel_Type=$arrs[1];

		
		$NEW_NAME_FILE = $this->_model->creaFile($Sel_Schema, $Sel_Table);
		
		echo json_encode($NEW_NAME_FILE);
	}
	


	
}



?>