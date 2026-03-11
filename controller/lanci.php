<?php
/** 
 * @property lanci_model $_model
 */
class lanci extends helper
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '
	   <link rel="stylesheet" href="./view/lanci/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/lanci/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
        $this->_model = new lanci_model();
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
        $datilanci = $this->_model->getlanci();
    //    $this->debug('datiStatus',$datilanci);
       $tabellaLanci =  $this->getTabellaByArray($datilanci, "");
        include "view/lanci/index.php";
    }
    
  
}
?>