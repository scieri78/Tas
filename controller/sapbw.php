<?php
/** 
 * @property sapbw_model $_model
 */
class sapbw extends helper
{
  
    
    function __construct()
    {
       $this->include_css = '
	   <link rel="stylesheet" href="./view/sapbw/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/sapbw/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new sapbw_model();
       $this->setDebug_attivo(1);
	   
    }
 
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";
      $this->contentList();
	  include "view/footer.php";
    }
 
   
    public function contentList(){
		$RicId=$_POST['RicId'];
		$RicTipo=$_POST['RicTipo'];
		$RicPeriod=$_POST['RicPeriod'];
		$SelM=6;
		$this->_model->AvviaShellServer($RicId,$RicTipo);
		$datiSapbwFile = $this->_model->getSapbwFile($SelM);
		$datiInputFiles = $this->_model->getInputFiles($SelM);
        include "view/sapbw/index.php";
      
    }
}



?>