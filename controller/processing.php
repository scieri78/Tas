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
        if (isset($_POST['NumLast'])) {
            $this->_datiprocessing->setNumLast($_POST['NumLast']);
        }
        if (isset($_POST['SelNumPage'])) {
            $this->_datiprocessing->setSelNumPage($_POST['SelNumPage']);
        }
        if (isset($_POST['idRunSh'])) {
            $this->_datiprocessing->setIdRunSh($_POST['idRunSh']);
        }
        if (isset($_POST['AutoRefresh'])) {
            $this->_datiprocessing->setAutoRefresh($_POST['AutoRefresh']);
        }
        if (isset($_POST['PLSSHOWDETT'])) {
            $this->_datiprocessing->setShowDett($_POST['PLSSHOWDETT']);
        }
        if (isset($_POST['NoTags'])) {
            $this->_datiprocessing->setNoTags($_POST['NoTags']);
        }
        if (isset($_POST['SelShell'])) {
            $this->_datiprocessing->setSelShell($_POST['SelShell']);
        }
        if (isset($_POST['SelInDate'])) {
            $this->_datiprocessing->setSelInDate($_POST['SelInDate']);
        }
        if (isset($_POST['Sel_Esito'])) {
            $this->_datiprocessing->setSelEsito($_POST['Sel_Esito']);
        }
        if (isset($_POST['SelEserMese'])) {
            $this->_datiprocessing->setSelEserMese($_POST['SelEserMese']);
        }
        if (isset($_POST['Sel_Id_Proc'])) {
            $this->_datiprocessing->setSelIdProc($_POST['Sel_Id_Proc']);
        }
        if (isset($_POST['SelAmbito'])) {
            $this->_datiprocessing->setSelAmbito($_POST['SelAmbito']);
        }
        if (isset($_POST['viewfilter'])) {
            $this->_datiprocessing->setViewFilter($_POST['viewfilter']);
        }

        if (!$this->_datiprocessing->getMeseElab()) {
            $this->_datiprocessing->setMeseElab(date('Ym'));
        }
        if (!$this->_datiprocessing->getMeseDiff()) {
            $this->_datiprocessing->setMeseDiff(date('Ym'));
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
            $DatiSelMeseElab = $this->_model->getSelMeseElab();
            $DatiSelLastMeseElab = $this->_model->getSelLastMeseElab($this->_datiprocessing->getMeseElab());
            $DatiSelShellFather = $this->_model->getSelShellFather($this->_datiprocessing->getMeseElab());
            $DatiSelShellSons = $this->_model->getSelShellSons($this->_datiprocessing->getMeseElab());
            $DatiSelInDate = $this->_model->getSelInDate($this->_datiprocessing->getMeseElab());
            $DatiSelEserMese = $this->_model->getSelEserMese($this->_datiprocessing->getMeseElab());
            $DatiSelIdProc = $this->_model->getSelIdProc($this->_datiprocessing->getMeseElab());
            $DatiAmbiti = $this->_model->getDatiAmbiti($this->_datiprocessing->getMeseElab());
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
                    $DatiArrayShell = $shell['DETTAGLIO'];
                    // mostra dettaglio shell
                    include 'view/processing/ArrayShell.php';
                } elseif ($shell['TIPO'] == 'ARRAY_SQL') {
                    $ArraySql = $shell['DETTAGLIO']; // Passa i dettagli degli sql alla view
                    // mostra dettaglio sql
                    include 'view/processing/ArraySql.php';
                } elseif ($shell['TIPO'] == 'ARRAY_STEP') {
                    $ArrayStep = $shell['DETTAGLIO']; // Passa i dettagli degli step alla view
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
