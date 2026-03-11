<?php


class workflow extends helper
{
   
    
    function __construct()
    {
       $this->include_css = '
	   <link rel="stylesheet" href="./view/workflow/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/workflow/JS/index.js?p='.rand(1000,9999).'"></script>
	   <link rel="stylesheet" href="./CSS/mainmenu.css?p='.rand(1000,9999).'" />
	   <link rel="stylesheet" href="./CSS/excel.css?p='.rand(1000,9999).'" />
	   ';
	   $this->_model =  new workflow_model();
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
       
        include "view/workflow/index.php";
      
    }
	 
    public function LoadFlusso(){
    
        include "view/workflow/WorkFlow_LoadFlusso.php";
      
    }
	
	public function Legend(){
    
        include "view/workflow/WorkFlow_Legend.php";
      
    }
	
	public function Flusso(){
    
        include "view/workflow/WorkFlow_Flusso.php";
      
    }
	
	public function RefreshCoda(){
    
        include "view/workflow/Workflow_RefreshCoda.php";
      
    }
	
	public function OpenLinkPage(){
    
        include "view/workflow/Workflow_OpenLinkPage.php";
      
    }
	
	public function MostraCoda(){
    
        include "view/workflow/Workflow_MostraCoda.php";
      
    }
	public function MostraStorico(){
    
        include "view/workflow/Workflow_MostraStorico.php";
      
    }
	public function GeneraDiagram(){
    
        include "view/workflow/GeneraDiagram.php";
      
    }
	
	
	
}



?>