<?php
require_once("model/checka.php");
include_once './core/checka.php'; 

class elaborazioni extends checka
{
    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
      parent::__construct();
      $this->include_css = '
	   <link rel="stylesheet" href="./view/CheckA4945/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <link rel="stylesheet" href="./CSS/excel.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/CheckA4945/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';       
       
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
      include "view/CheckA4945/refreschLinkEsterni.php";
      parent::contentList();
      include "view/footer.php";
    }

    
    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {
     
      include "view/CheckA4945/refreschLinkEsterni.php";
      parent::contentList();
     
    }
    
}

?>
