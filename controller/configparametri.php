<?php
require_once("model/workflow2.php");
/**
 * @property configparametri_model $_model
 * @property workflow2_model $_modelWf
 */
class configparametri extends helper
{

    function __construct()
    {
        $this->_model = new configparametri_model();
        $this->_modelWf = new workflow2_model();

        $this->include_css = '
        <link rel="stylesheet" href="./view/builder/CSS/index.css?p=' . rand(1000, 9999) . '" />
        <link rel="stylesheet" href="./view/configparametri/CSS/index.css?p=' . rand(1000, 9999) . '" />
        <script src="./view/configparametri/JS/index.js?p=' . rand(1000, 9999) . '"></script>
        ';
    $this->debug_attivo=1;
    }

    public function index()
    {
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $this->showVisualizzazione();
        include "view/footer.php";
    }

    public function showVisualizzazione()
    {
        $IdWorkFlow = "";
        if ($IdWorkFlow == "" and isset($_SESSION['IdWorkFlow'])) {
            $IdWorkFlow = $_SESSION['IdWorkFlow'];
        }

        include "view/configparametri/index.php";
    }
    public function getWorkflows()
    {
        // $workflows = $this->_model->getWorkflows();
        $IdWorkFlow = "";
        if ($IdWorkFlow == "" and isset($_SESSION['IdWorkFlow'])) {
            $IdWorkFlow = $_SESSION['IdWorkFlow'];
        }


        $ArrWfs = [];
        $DatiAuthWfs = [];
        $IdUser = $_SESSION['CodUk'];
        //==========   Create Arrs ListGruppi
        $ArrListGroup = array();
        $ListIdGroups = "0";
        $DatiIdGruppo = $this->_modelWf->getIdGruppo($IdUser);
        foreach ($DatiIdGruppo as $row) {
            $IdGruppo = $row['ID_GRUPPO'];
            array_push($ArrListGroup, $IdGruppo); //???
            $ListIdGroups = $ListIdGroups . "," . $IdGruppo;
        }

        //==========   Create Arrs Data
        $DatiAuthWfs = $this->_modelWf->getAutorizzazioniWorkflow($ListIdGroups);
        foreach ($DatiAuthWfs as $row) {
            $Id = $row['ID_WORKFLOW'];
            $Name = $row['WORKFLOW'];
            $Desc = $row['DESCR'];
            $ReadOnly = $row['READONLY'];
            $Freq = $row['FREQUENZA'];
            $Multi = $row['MULTI'];

            if ($Id == $IdWorkFlow) {
                $WfsName = $Name;
                $WfsDescr = $Desc;
                $WfsReadOnly = $ReadOnly;
                $WfsFreq = $Freq;
                $WfsMulti = $Multi;
            }

            // array_push($ArrWfs, array($Id, $Name, $Desc, $ReadOnly, $Freq, $Multi));
            $ArrWfs[] = $row;
        }

        echo json_encode($ArrWfs);
    }



    public function getGruppi()
    {
        $id_workflow = $_POST['id_workflow'];
        $gruppi = $this->_model->getGruppiByWorkflow($id_workflow);
        echo json_encode($gruppi);
        /*foreach ($gruppi as $gruppo) {
            echo '<option value="' . $gruppo['ID_PAR_GRUPPO'] . '">' . $gruppo['LABEL'] . '</option>';
        }*/
    }

    public function getParametri()
    {
        $id_par_gruppo = $_POST['id_par_gruppo'];
        $Datiparametri = $this->_model->getParametriByGruppo($id_par_gruppo);

        include "view/configparametri/listaParametri.php";
    }
    
    /**
     * formParametri
     *
     * @return void
     */
    public function formParametri()
    {
        foreach ($_POST as $k => $v) {
            ${$k} = $v;
        }
        if ($id_par) {
            $datiParametro = $this->_model->getParametro($id_par);
        }
//$this->debug("datiParametro",$datiParametro);
        foreach ($datiParametro[0] as $k => $v) {
            ${$k} = $v;
        }

        include "view/configparametri/form_parametri.php";
    }
    public function formParametriGruppo()
    {
        $id_workflow = $_POST['id_workflow'];
        include "view/configparametri/form_parametri_gruppo.php";
    }


    public function insertLinkParametri()
    {
        try {
            $postData = $_POST;

            $lastId = $this->_model->addLinkParametri($postData);
            //  echo "Link Parametri inserted successfully with ID: $lastId";
        } catch (Exception $e) {
            echo "Error inserting Link Parametri: " . $e->getMessage();
        }
    }

    public function insertLinkParametriGruppo()
    {
        try {
            $postData = $_POST;
            $linkParametriGruppoData = [
                'id_workflow' => $postData['id_workflow'],
                'label' => $postData['label_gruppo']
            ];

            $lastId = $this->_model->addLinkParametriGruppo($linkParametriGruppoData);
            //    echo "Link Parametri Gruppo inserted successfully with ID: $lastId";
        } catch (Exception $e) {
            echo "Error inserting Link Parametri Gruppo: " . $e->getMessage();
        }
    }

    public function insertLinkParametriLegame()
    {
        try {
            $postData = $_POST;
            $linkParametriLegameData = [
                'id_par_gruppo' => $postData['id_par_gruppo'],
                'id_par' => $postData['id_par'],
                'ord' => $postData['ord']
            ];

            $lastId = $this->_model->addLinkParametriLegame($linkParametriLegameData);
            echo "Link Parametri Legame inserted successfully with ID: $lastId";
        } catch (Exception $e) {
            echo "Error inserting Link Parametri Legame: " . $e->getMessage();
        }
    }

    public function removeParametri()
    {
        try {
            $id_par = $_POST['id_par'];
            $id_par_gruppo = $_POST['id_par_gruppo'];
            $result = $this->_model->removeLinkParametri($id_par, $id_par_gruppo);
            echo "Parametro rimosso con successo!";
        } catch (Exception $e) {
            echo "Errore nella rimozione del parametro: " . $e->getMessage();
        }
    }


    public function removeParametriGruppo()
    {
        try {
            $id_par_gruppo = $_POST['id_par_gruppo'];
            $id_workflow = $_POST['id_workflow'];
            $result = $this->_model->removeLinkParametriGruppo($id_par_gruppo, $id_workflow);
            // echo "Parametro rimosso con successo!";
        } catch (Exception $e) {
            echo "Errore nella rimozione del parametro: " . $e->getMessage();
        }
    }

    public function modificaParametri()
    {
        // Implementa la logica per modificare i parametri
    }
}
