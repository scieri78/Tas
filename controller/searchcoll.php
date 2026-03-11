<?php
/** 
 * @property searchcoll_model $_model
 * @property statoshell_model $_modelsh
 */
class searchcoll extends helper
{    
    private $typesh;
    private $_modelsh;    
    function __construct()
    {
       $this->_model = new searchcoll_model();
	   $this->setDebug_attivo(1);
	   $this->include_css = '
	   <link rel="stylesheet" href="./view/searchcoll/CSS/index.css?p=' . rand(1000, 9999) . '" />   
	   <script type="text/javascript" src="./view/searchcoll/JS/index.js?p='.rand(1000,9999).'"></script>';
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
		
		$COLNAME =@$_POST['COLNAME'];
	
		$datiTable = $this->_model->getTable($COLNAME);
	

		include "view/searchcoll/index.php";
    } 	
	//genera classe download lista 		
	/**
	 * downloadList
	 *
	 * @return void
	 */
	public function downloadList()  {
		$COLNAME =@$_POST['COLNAME'];			
		$NEW_NAME_FILE = $this->_model->creaFile($COLNAME);	 
      
		echo json_encode($NEW_NAME_FILE);
		
	}	


	
}



?>