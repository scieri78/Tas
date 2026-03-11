<?php
/** 
 * @property openLink_base_model $_model
 */
class openLink_base extends helper
{
    
    function __construct()
    {
       $this->include_css = '        
	   <style>
       ' . file_get_contents("./view/openLink_base/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_base/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_base_model();      
        $this->setDebug_attivo(1);
    }

    public function index()
    {
        $_view['include_css'] = $this->include_css;       
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];     
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    public function contentList()
    {
        
        include "view/openLink_base/index.php";
    }
    


  

   
}
