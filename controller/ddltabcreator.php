<?php
include_once("./model/statoshell.php");
/** 
 * @property ddltabcreator_model $_model
 * @property statoshell_model $_modelsh
 */
class ddltabcreator extends helper
{
    
    private $typesh;
    private $_modelsh;
    
    function __construct()
    {
		$sito = $_GET['sito']?$_GET['sito']:$_POST['sito'];
       $this->_model = new ddltabcreator_model($sito);
	   $this->setDebug_attivo(1);
	   $this->include_css = '';
	   $this->_modelsh = new statoshell_model($sito);
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
		
		$datiSqlTable =$this->_model->getSQLTable($Sel_Schema, $Sel_Object);
		$datiGRENTEE =$this->_model->getGRANTEE($Sel_Schema, $Sel_Object);
		$TestoFile = $datiSqlTable['TestoFile'];
		$filename = $datiSqlTable['filename'];
		
		$TestoFile = str_replace("COMMIT WORK;","",$TestoFile);
		$TestoFile = str_replace("CONNECT RESET;","",$TestoFile);
		$TestoFile = str_replace("TERMINATE;","",$TestoFile);
		$TestoFile = trim($TestoFile)."\r\n \r\n";
		$TestoFile .= '-- DCL Statements for Granting Permissions on Table "' . $Sel_Schema . '"."' . $Sel_Table . '"' . "\n\n";
		$TestoFile .=$datiGRENTEE;
	   // $this->debug('_SESSION',$_SESSION);
		include "view/ddltabcreator/index.php";
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
	
	
}

?>