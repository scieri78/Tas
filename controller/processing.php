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

        if (!isset($_REQUEST['DAPROCESSING']) || $_REQUEST['DAPROCESSING'] != 1) {
            if (isset($_POST['resetSession']) && $_POST['resetSession'] == 1) {
                $_SESSION[$DATABASE] = [];
                $_POST = [];
            } elseif (!empty($_POST)) {
                $_SESSION[$DATABASE] = $_POST;
            } elseif (isset($_SESSION[$DATABASE]) && !empty($_SESSION[$DATABASE])) {
                $_POST = $_SESSION[$DATABASE];
            }
        }
        $db_name = isset($_GET['sito']) ? $_GET['sito'] : (isset($_POST["db_name"]) ? $_POST["db_name"] : '');

        $this->include_css = '
                    <link rel="stylesheet" href="./view/processing/CSS/index.css?p=' . rand(1000, 9999) . '">
                    <script src="./view/processing/JS/index.js?p=' . rand(1000, 9999) . '"></script>';
        $this->_model = new processing_model($db_name);
        $db_name =  $this->_model->getDbName();

        $this->_datiprocessing = new processing_dati();
        $this->_datiprocessing->DB2database = $db_name;
        
        // Carica i dati dalla sessione POST
        if (isset($_POST['meseElab'])) {
            $this->_datiprocessing->setMeseElab($_POST['meseElab']);
        }
        if (isset($_POST['meseDiff'])) {
            $this->_datiprocessing->setMeseDiff($_POST['meseDiff']);
        }
        if (isset($_POST['limitRows'])) {
            $this->_datiprocessing->setLimit($_POST['limitRows']);
        }
        if (isset($_POST['SelNumPage'])) {
            $this->_datiprocessing->setSelNumPage($_POST['SelNumPage']);
        }
        if (isset($_POST['idRunSh'])) {
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
        $_view = [];
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        // Verifica se deve mostrare il dettaglio oppure la lista
        if (isset($_POST['action']) && $_POST['action'] == 'detail' && $this->_datiprocessing->getIdRunSh()) {
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
        $_view = [];
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

    /**
     * detailAjax - Dettaglio del processing via AJAX (shell figli)
     *
     * @return void
     */
    public function detailAjax()
    {
        $_modelprocessing = $this->_model;
        $datiprocessing = $this->_datiprocessing;
        
        $idRunSh = isset($_GET['idRunSh']) ? $_GET['idRunSh'] : 0;
        
        if ($idRunSh) {
            $ArrayShell = $this->_model->getArrayShow($idRunSh);         // Recupera gli shell figli ordinati per tempo
            //per ogni tipo di shell recupera il dettaglio puoi visualizzare il dettagio corrispondente in base al tipo (shell, sql, step)
        foreach ($ArrayShell as $key => $shell) {
                if ($shell['TIPO'] == 'ARRAY_SHELL') {
                    $ArrayShell[$key]['DETTAGLIO'] = $this->_model->getArrayShell($idRunSh, $shell['POS']);
                } elseif ($shell['TIPO'] == 'ARRAY_SQL') {
                    $ArrayShell[$key]['DETTAGLIO'] = $this->_model->getArraySql($idRunSh, $shell['POS']);
                } elseif ($shell['TIPO'] == 'ARRAY_STEP') {
                    $ArrayShell[$key]['DETTAGLIO'] = $this->_model->getArrayStep($idRunSh, $shell['POS']);
                }
            }
           //richiama la view corrispondente per mostrare il dettaglio degli shell figli, puoi passare l'array completo con i dettagli alla view e gestire la visualizzazione in base al tipo di shell
           foreach ($ArrayShell as $shell) {
                if ($shell['TIPO'] == 'ARRAY_SHELL') {
                    // mostra dettaglio shell
                    include 'view/processing/ArrayShell.php';
                } elseif ($shell['TIPO'] == 'ARRAY_SQL') {
                    // mostra dettaglio sql
                    include 'view/processing/ArraySql.php';
                } elseif ($shell['TIPO'] == 'ARRAY_STEP') {
                    // mostra dettaglio step
                    include 'view/processing/ArrayStep.php';
                }
            }

           // $ArrayShell = $this->_model->getArrayShell($idRunSh);
            
            // Restituisci il contenuto degli shell come HTML
           // include 'view/processing/ArrayShell.php';
        }
    }
}
?>
