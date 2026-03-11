<?php
/** 
 * @property openLink_LR_LoadInfo_model $_model
 */
class openLink_LR_LoadInfo extends helper
{

    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '        
	   <style>
       ' . file_get_contents("./view/openLink_LR_LoadInfo/CSS/index.css") . '	  
       </style>  
        <script src="./view/openLink_LR_LoadInfo/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new openLink_LR_LoadInfo_model();
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
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {

        include "view/openLink_LR_LoadInfo/index.php";
    }
}
