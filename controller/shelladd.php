<?php
/** 
 * @property shelladd_model $_model
 */
class shelladd extends helper
{
    
    private $typesh;
    private $messafeType='';
    
    function __construct()
    {
       $this->_model = new shelladd_model();
	   $this->setDebug_attivo(1);
	   $this->include_css = '
	   <link rel="stylesheet" href="./view/shelladd/CSS/index.css?p='.rand(1000,9999).'" />	 
	   ';
   /* if ( $AddMail != "" ){
		$this->addMail($AddMail);
    }*/
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
		$this->get_errors_message($this->messafeType);
		$hideParall = ($this->_model->getDbName() == 'TASPCWRK');
		$datiShList = $this->_model->getShList();
		$datiTableList = $this->_model->getTableList();
		$mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];
		include "view/shelladd/index.php";
		
    } 
	
	public function addShell()
    {
		$this->error_message = "";
		$SHELL_PATH = @$_GET['SHELL_PATH'];
		$SHELL = @$_GET['SHELL'];	
		if(!$SHELL_PATH || !$SHELL){
           $this->error_message .= "Inserire Shell Name!";
		   $this->messafeType = 'Error';
					                          
        }
		else
		{
			$noerr = $this->_model->addShell($SHELL, $SHELL_PATH);
			if(!$noerr){
				$this->error_message .= "Shell già esistente.<br>";
								  
			}
		}
		
		$this->contentList();
	}
	
	
	public function removeSh()
    {
		
		$ID_SH = @$_GET['ID_SH'];
		$this->_model->removeSh($ID_SH);
		$this->contentList();
	}

public function formSh()
    {
	$hideParall = ($this->_model->getDbName() == 'TASPCWRK');
	$ID_SH = @$_POST['ID_SH'];
	$SHELL = @$_POST['SHELL'];
	$SHELL_PATH = @$_POST['SHELL_PATH'];
	$PARALL = @$_POST['PARALL'];
	include "view/shelladd/formSh.php";
		
    } 


public function modificaSh()
    {
		
		$ID_SH = @$_POST['ID_SH'];
		$SHELL = @$_POST['SHELL'];
		$SHELL_PATH = @$_POST['SHELL_PATH'];
		$PARALL = @$_POST['PARALL'];
		
		$this->_model->updateShell($SHELL,$SHELL_PATH,$ID_SH,$PARALL);
		$this->contentList();
	}

	
	
}



?>