<?php
/** 
 * @property base_model $_model
 */
class base extends helper
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '
	   <link rel="stylesheet" href="./view/base/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/base/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
        $this->_model = new base_model();
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
        include "view/base/index.php";
    }
    
    /**
     * getSuggestion
     *
     * @return void
     */
    public function getSuggestion()
    {
        $searchTerm = $_POST['term'];
        $datiSuggestion = $this->_model->getSuggestion($searchTerm);
        $productData = array();

        foreach ($datiSuggestion as $row) {
            $data['productID'] = $row['ID_LANCIO'];
            $data['value'] = $row['FORZATURA'];
            array_push($productData, $data);
        }
        // Restituisci i risultati come array JSON
        echo json_encode($productData);
    }
}
?>