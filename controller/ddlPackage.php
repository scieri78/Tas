<?php
include_once("./model/statoshell.php");
/** 
 * @property ddlPackage_model $_model
 * @property statoshell_model $_modelsh
 */
class ddlPackage extends helper
{
    
    private $typesh;
    private $_modelsh;
    
    function __construct()
    {
		$sito = $_GET['sito']?$_GET['sito']:$_POST['sito'];
       $this->_model = new ddlPackage_model($sito);
	   $this->_modelsh = new statoshell_model($sito);
	   $this->setDebug_attivo(1);
	 //  $this->debug("get __construct",$_GET);
	$this->include_css = '';
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
		$Sel_PkgSchema =@$_POST['Sel_PkgSchema'];
		$Sel_PkgName =@$_POST['Sel_PkgName'];
		$datiPackage = $this->_model->getPackage($Sel_PkgSchema);
		$datiInfoFile = $this->_model->getInfoFile($Sel_PkgSchema, $Sel_PkgName);		
		
		foreach ($datiInfoFile as $row) {
		  $PkgHead=$row['SOURCEHEADER'];
		  $PkgBody=$row['SOURCEBODY'];
		}
		$TestoPkg="$PkgHead
				/
				$PkgBody
				/
				";

		$ret=$this->createDdlFile($Sel_PkgSchema, $Sel_PkgName, $TestoPkg);
		$TestoPkg =$ret['TestoFile'];
		$filename=$ret['filename'];
		include "view/ddlPackage/index.php";
		
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