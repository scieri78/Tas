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
        
        // Carica i dati dalla sessione POST
        if ($_POST['meseElab']) {
            $this->_datiprocessing->setMeseElab($_POST['meseElab']);
        }
        if ($_POST['meseDiff']) {
            $this->_datiprocessing->setMeseDiff($_POST['meseDiff']);
        }
        if ($_POST['limitRows']) {
            $this->_datiprocessing->setLimit($_POST['limitRows']);
        }
        if ($_POST['SelNumPage']) {
            $this->_datiprocessing->setSelNumPage($_POST['SelNumPage']);
        }
        if ($_POST['idRunSh']) {
            $this->_datiprocessing->setIdRunSh($_POST['idRunSh']);
        }
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
        
        // Verifica se deve mostrare il dettaglio oppure la lista
        if ($_POST['action'] == 'detail' && $this->_datiprocessing->getIdRunSh()) {
            $this->detail();
        } else {
            include 'view/processing/filtroProcessing.php';
            $this->list();
        }
        
        include "view/footer.php";
    }

    /**
     * list - Lista principale
     *
     * @return void
     */
    public function list()
    {
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        // Recupera la lista dei processing padre
        $DatiListProcessing = $this->_model->getListProcessing($this->_datiprocessing);
        
        include 'view/processing/ListProcessing.php';
    }
    
    /**
     * detail - Dettaglio del processing
     *
     * @return void
     */
    public function detail()
    {
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        $idRunSh = $this->_datiprocessing->getIdRunSh();
        
        if ($idRunSh) {
            // Recupera i 4 array di dettaglio
            $ArrayShell = $this->_model->getArrayShell($idRunSh);
            $ArraySql = $this->_model->getArraySql($idRunSh);
            $ArrayStep = $this->_model->getArrayStep($idRunSh);
            $ArrayShow = $this->_model->getArrayShow($idRunSh);
            
            include 'view/processing/DetailProcessing.php';
        }
        
        include "view/footer.php";
    }
}
?>
