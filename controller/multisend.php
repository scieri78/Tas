<?php
/** 
 * @property multisend_model $_model
 */
class multisend extends helper
{
 
    private $typesh;
    
    function __construct()
    {
       $this->_model = new multisend_model();
	   $this->setDebug_attivo(1);

	}
	
    // mvc handler request
    public function index()
    {
		include "view/header.php";
		$this->contentList();
		include "view/footer.php";
    }
	
	 public function contentList()
     {
		$datiShList = $this->_model->getShList();
		$datiMultiSend = $this->_model->getMultiSend();		
		$mailPage   = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];
		include "view/multisend/index.php";
    }
	
	public function addMultiSend()
    {
		$flag = @$_GET['flag'];
		$idSh = @$_GET['idSh'];
		$this->_model->addShMultiSend ($idSh, $flag);
		$this->contentList();
	}
	
   
}


?>