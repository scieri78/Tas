<?php
/** 
 * @property exptowfs_model $_model
 */
class exptowfs extends helper
{

    private $typesh;
    
    function __construct()
    {
		$this->include_css = '
			<link rel="stylesheet" href="./view/exptowfs/CSS/index.css?p='.rand(1000,9999).'">
			';
       $this->_model = new exptowfs_model();
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
	$DatiEleborazioni = $this->_model->getEleborazioni();
	$mailPage = @$_POST['mailPage'];
	$mailLength = @$_POST['mailLength'];		
	$mailSearch = @$_POST['mailSearch'];		
	$mailOrdern = @$_POST['mailOrdern'];		
	$mailOrdert = @$_POST['mailOrdert'];
		include "view/exptowfs/index.php";
	
    }
	
	public function setshwfs()
    {
	$IdSh=$_GET['IdSh'];
	$flag=$_GET['flag'];		
	$DatiEleborazioni = $this->_model->setShWfs($IdSh,$flag);
	$this->contentList();
	
    }
	
	public function setblockwfs()
    {
	$IdSh=$_GET['IdSh'];
	$flag=$_GET['flag'];	
	$DatiEleborazioni = $this->_model->setBlockWfs($IdSh,$flag);
	$this->contentList();
	
    }	
	
   
}



?>