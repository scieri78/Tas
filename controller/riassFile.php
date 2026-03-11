<?php
/** 
 * @property riassFile_model $_model
 */
class riassFile extends helper
{
  
        
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
       $this->include_css = '
	   <link rel="stylesheet" href="./view/riassFile/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/riassFile/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new riassFile_model();
       $this->setDebug_attivo(1);
	   
    }
     
    /**
     * index
     *
     * @return void
     */
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";
      $this->contentList();
	  include "view/footer.php";
    }
 
       
    /**
     * contentList
     *
     * @return void
     */
    public function contentList(){
		$RicId=$_POST['RicId'];
		$RicTipo=$_POST['RicTipo'];
		$RicPeriod=$_POST['RicPeriod'];
		$SelM = 12;
		//$this->_model->AvviaShellServer($RicId,$RicTipo);
		$datiRiassFile = $this->_model->getRiassFile($SelM);
		$datiInputFiles = $this->_model->getInputFiles($SelM);
		$_model = $this->_model;
        include "view/riassFile/index.php";
      
    }
}



?>