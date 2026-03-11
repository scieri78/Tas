<?php
/** 
 * @property debug_model $_model
 */

class debug extends helper
{

    private $typesh;
    
    function __construct()
    {
		$this->include_css = '
	   <link rel="stylesheet" href="./view/debug/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/debug/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
       $this->_model = new debug_model();
	   $this->setDebug_attivo(1);
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
		$datiShList = $this->_model->getShList();
		$datiSqlList = $this->_model->getSqlList();
		$datiDebugList = $this->_model->getDebugList();
		 $mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];
		include "view/debug/index.php";
    }
	
	
	public function updateShDebug()
    {
		$flag = @$_GET['flag'];
		$idSh = @$_GET['idSh'];
		$this->_model->updateShDebug($idSh, $flag);
		$this->contentList();
	}

	public function updateDbDebug()
    {
		$flag = @$_GET['flag'];
		$idSh = @$_GET['idSh'];
		$this->_model->updateDbDebug($idSh, $flag);
		$this->contentList();
	}

	public function updateDb()
    {
		$flagDb = @$_POST['flagDb']?'Y':'N';
		$flagSh = @$_POST['flagSh']?'Y':'N';
		$idSh = @$_POST['ID_SH'];
		$this->_model->updateDb($idSh, $flagSh,$flagDb);
		$this->contentList();
	}
	
   
}


?>