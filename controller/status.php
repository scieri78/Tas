<?php
/** 
 * @property status_model $_model
 */
class status extends helper
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '
	   <link rel="stylesheet" href="./view/status/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/status/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
        $this->_model = new status_model();
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
    public function contentList()
    {
        $datiStatus = $this->_model->getStatus();
       //$this->debug(datiStatus,$datiStatus);
       $tabellaStatus = $this->getTabellaByArray($datiStatus, "");
        include "view/status/index.php";
    }
    
  
}
?>