<?php
/** 
 * @property look_model $_model
 */
class look extends helper
{
    
    function __construct()
    {
       $this->include_css = '

	   <script src="./view/look/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new look_model();
       $this->setDebug_attivo(1);
	   
    }
 
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";
      $this->contentList();
	  
	  include "view/footer.php";
    }
 
   
    public function contentList()
	{  
		$DATABASE =$_SESSION['DATABASE'];
		$SSHUSR =$_SESSION['SSHUSR'];
		$SERVER =$_SESSION['SERVER']; 
		$KillAgent=$_POST['KillAgent'];
		$SelAgent=$_POST["SelAgent"];
		$datiLookDb =$this->_model->getLookDb();
		$datiLookAgent =$this->_model->getLookAgent();
		
        include "view/look/index.php";
      
    }
	public function KillAgent()
	{
		$KillAgent=$_POST['KillAgent'];
		$this->_model->KillAgent($KillAgent);
		$this->contentList();
	}
		
}



?>