<?php
include_once("./model/processing_dati.php");
/** 
 * @property processing_model $_model
 */
class processing extends helper
{
    private $_datiprocessing;

    function __construct()
    {
        $this->setDebug_attivo(1);
        $DATABASE = $_SESSION['DATABASE'];

        if ($_REQUEST['DAPROCESSING'] != 1) {
            if ($_POST['resetSession'] == 1) {
                $_SESSION[$DATABASE] = [];
                $_POST = [];
            } elseif ($_POST) {
                $_SESSION[$DATABASE] = $_POST;
            } elseif ($_SESSION[$DATABASE]) {
                $_POST = $_SESSION[$DATABASE];
            }
        }
        $db_name = $_GET['sito'] ? $_GET['sito'] : $_POST["db_name"];

        $this->include_css = '
                    <link rel="stylesheet" href="./view/processing/CSS/index.css?p=' . rand(1000, 9999) . '">';
        $this->_model = new processing_model($db_name);
        $db_name =  $this->_model->getDbName();

        $this->_datiprocessing = new processing_dati();
        $this->_datiprocessing->DB2database = $db_name;
    }
    
    // mvc handler request    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        include 'view/processing/index.php';
        include "view/footer.php";
    }

    /**
     * list
     *
     * @return void
     */
    public function listProcessing()
    {
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        include 'view/processing/list.php';
    }
}
?>
