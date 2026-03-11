<?php
/** 
 * @property loader_model $_model
 */
class loader extends helper
{
   
    
    function __construct()
    {
       $this->include_css = '
	   <link rel="stylesheet" href="./view/loader/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/loader/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new loader_model();
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
      
        include "view/loader/index.php";
      
    }
}



?>