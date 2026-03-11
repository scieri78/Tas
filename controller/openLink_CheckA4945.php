<?php
require_once("model/checka.php");
include_once './core/checka.php'; 
class openLink_CheckA4945 extends checka
{
    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
      parent::__construct();
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
        include "view/CheckA4945/refreshLinkInterni.php";
        parent::contentList();
        include "view/footerOpenLink.php";
    }

}
?>