<?php

include_once("./model/controlli_dati.php");
/** 
 * @property controlli_model $_model
 */
class controlli extends helper
{


    function __construct()
    {
        $this->include_css = '
	   <link rel="stylesheet" href="./view/controlli/CSS/index.css?p=' . rand(1000, 9999) . '" />      
	   ';
        $this->_model = new controlli_model();
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
    public function uploadcontrolli()
    {
        //$_view['include_css'] = $this->include_css;
        //include "view/header.php";
        $ID_GRUPPO  = @$_POST['ID_GRUPPO'];
        include "view/controlli/uploadControlli.php";

        // include "view/footer.php";
    }


    /**
     * insertcontrolli
     *
     * @return void
     */
    public function inportcontrolli()
    {
        //$_view['include_css'] = $this->include_css;
        //include "view/header.php";
        $ID_GRUPPO = @$_POST['ID_GRUPPO'];
        $INPORT = @$_POST['INPORT'];
        //$this->debug('file',$_FILES);
        //$this->debug('post',$_POST);

        // $this->debug('file',$_FILES);
        //    die();

        $ID_INPORT = $this->_model->getDatiByExcel($ID_GRUPPO, $INPORT);
        $this->debug('ID_INPORT', $ID_INPORT);

        echo json_encode($ID_INPORT);
        // include "view/footer.php";
    }

    /**
     * uploadfile
     *
     * @return void
     */
    public function  contentList()
    {


        // $this->uploadfile();

        // $this->debug("dati", $DatiByExce);
        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;
        $this->get_errors_message();

        //  $_SESSION['sporttbl0']=serialize($sporttb);//add session obj
        $AddName     = @$_POST['AddName'];
        $AddUsername = @$_POST['AddUsername'];
        $AddMail     = @$_POST['AddMail'];
        $mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];
        $mailSearch = @$_POST['mailSearch'];
        $mailOrdern = @$_POST['mailOrdern'];
        $mailOrdert = @$_POST['mailOrdert'];
        $FLG_REFRESH = @$_POST['FLG_REFRESH'];

        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];
        $selectIdTipo = @$_POST['selectIdTipo'];
        $selectClasse = @$_POST['selectClasse'];
        $selectEsito = @$_POST['selectEsito'];
        $selectFile = @$_POST['selectFile'];
        //  $selectInport = @$_POST['selectInport'];
        $maxLancio = $this->_model->getUltimoLancio($selectGruppo);
        // $this->debug("post",$_POST);
        $downloadFile = "";
        $datiGruppi = $this->_model->getGruppi();
        // $datiInport = $this->_model->getInport($selectGruppo);
        $datiTipo = $this->_model->getTipo();
        if ($selectGruppo  && !$selectLancio) {

            $datiControlliAnag = $this->_model->getControlliByIdGruppo($selectGruppo, '',  $selectSottoGruppo, $selectIdTipo, $selectEsito, $selectClasse, $selectFile);
            /*  $fileName_storico = $this->_model->createExcelControlli($selectGruppo, '', $selectSottoGruppo);
            $fileName_controlli = $this->_model->createExcelControlli($selectGruppo, '', $selectSottoGruppo, 0);
            $downloadFile_controlli = $fileName_controlli;
            $downloadFile_storico = $fileName_storico;*/
            // $this->debug('datiControlliAnag',$datiControlliAnag);
            //   $datiControlliAnag = $this->_model->getControlliAnagLancio(1);
        }
        //$this->debug('datiGruppi',$datiGruppi);
        if ($selectGruppo) {
            $datiGruppo = $this->_model->getGruppi($selectGruppo);
            // $this->debug("datiGruppo",$datiGruppo);     
            $datiSottoGruppi = $this->_model->getSottoGruppi($selectGruppo);
            // $this->_model->creaRisultatiControlli($datiGruppo);
            $datiListaFile =  $this->_model->getListaFile($selectGruppo, $selectSottoGruppo);

            $datiLancioGruppo = $this->_model->getLanciGruppo($selectGruppo);
            if ($selectLancio) {
                $Agruppi = $this->_model->getGruppi($selectGruppo);
                $GRUPPO = $Agruppi[0]['DESCR'];
                $datiControlliAnag = $this->_model->getControlliAnagLancio($selectLancio, $selectSottoGruppo, '', $selectIdTipo, $selectEsito, $selectClasse);

                //$this->debug("datiControlliAnag",$datiControlliAnag );
                $fileName = $this->_model->createExcelLancio($selectLancio, '', $selectSottoGruppo, $selectGruppo);
                $downloadFile = $fileName;
            }
        }
        include "view/controlli/index.php";
    }



    /**
     * modificaesito
     *
     * @return void
     */
    public function modificaesito()
    {
        $selectGruppo = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];
        //  $selectInport = @$_POST['selectInport'];

        $array_esito = controlli_dati::$array_esito;
        $datiLancio = $this->_model->getLancio($selectLancio);
        $LANCIO = $datiLancio[0]['DESCR'];
        include "view/controlli/modificaesito.php";
    }

    /**
     * modificavalidita
     *
     * @return void
     */
    public function modificavalidita()
    {


        include "view/controlli/modificavalidita.php";
    }


    /**
     * modificatipo
     *
     * @return void
     */
    public function modificatipo()
    {


        include "view/controlli/modificatipo.php";
    }


    /**
     * crealancio
     *
     * @return void
     */
    public function formcrealancio()
    {
        $selectGruppo = @$_POST['selectGruppo'];
        //  $selectInport = @$_POST['selectInport'];
        $ID_CONTR = @$_POST['ID_CONTR'];
        $array_classe = controlli_dati::$array_classe;
        $datiTipo = $this->_model->getTipo();
        $datiSottoGruppi = $this->_model->getSottoGruppi($selectGruppo);
        $maxLancio = $this->_model->getUltimoLancio($selectGruppo);
        $datiGruppo = $this->_model->getGruppi($selectGruppo);
        $LANCIO = $datiGruppo[0]['DESCR'] . "_" . ($maxLancio + 1);
        include "view/controlli/formCreaLancio.php";
    }



    /**
     * modificadbesito
     *
     * @return void
     */
    public function modificadbesito()
    {
        //   $this->debug("_POST", $_POST);
        $ret =  $this->_model->modificaEsito($_POST);
    }


    public function modificadbtipo()
    {
        //   $this->debug("_POST", $_POST);
        $ret =  $this->_model->modificaTipo($_POST);
    }


    /**
     * modificadbvaidita
     *
     * @return void
     */
    public function modificadbvaidita()
    {
        //   $this->debug("_POST", $_POST);
        $ret =  $this->_model->modificaValidita($_POST);
    }
    /**
     * creadblancio
     *
     * @return void
     */
    public function creadblancio()
    {
        $ID_GRUPPO = @$_POST['ID_GRUPPO'];
        $ID_INPORT = @$_POST['ID_INPORT'];
        $FILE_NAME_LIST = @$_POST['FILE_NAME_LIST'];
        $LISTA_ID_FILE = @$_POST['LISTA_ID_FILE'];
        if (!empty($LISTA_ID_FILE)) {
            foreach ($LISTA_ID_FILE as $k => $ID_FILE) {
                $datiLancio = $_POST;
                $datiLancio['ID_FILE'] = $ID_FILE;
                $datiLancio['NAME_FILE'] = $FILE_NAME_LIST[$k];
                $datiLancio['DESCR'] = $datiLancio['SUF_DESCR'] . "_" . $FILE_NAME_LIST[$k];
                $ID_LANCIO =  $this->_model->generaLancio($datiLancio);
                $LANCIO[$k] =  $this->_model->getDatiLancio($ID_LANCIO);
                //  $this->debug("Id Lancio", $ID_LANCIO);
                $datiGruppo = $this->_model->getGruppi($ID_GRUPPO);
            }
        } else {
            //$this->debug("datiGruppo", $datiGruppo);
            $ID_LANCIO =  $this->_model->generaLancio($_POST);
            $LANCIO[0] =  $this->_model->getDatiLancio($ID_LANCIO);
            //  $this->debug("Id Lancio", $ID_LANCIO);
            $datiGruppo = $this->_model->getGruppi($ID_GRUPPO);
        }
        $this->_model->creaLancio();
        //  $this->debug("Lancio Inserito Su:", $datiGruppo[0]['DESCR']);
        //   $this->debug("Filtri:", $LANCIO['FILTRI'] );
        //   $this->debug("File:", $LANCIO['NAME_FILE'] );
        echo json_encode($LANCIO);
    }


    public function downloadfile()
    {
        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];   
        $TIPO_DOWNLOAD = @$_POST['TIPO_DOWNLOAD'];      
       
        switch ($TIPO_DOWNLOAD) {
            case "STORICO":
                $NAME_FILE = $this->_model->createExcelControlli($selectGruppo, '', $selectSottoGruppo);
                break;
            case "CONTROLLI":
                $NAME_FILE = $this->_model->createExcelControlli($selectGruppo, '', $selectSottoGruppo,0);
                break;
            case "LANCIO":
                $NAME_FILE = $this->_model->createExcelLancio($selectLancio, '', $selectSottoGruppo, $selectGruppo);
                break;
        }



        /*  $downloadFile_controlli = $fileName_controlli;
            $downloadFile_storico = $fileName_storico;*/

        $ID_EXPORT = $this->_model->generaExport($selectGruppo, './TMP/' . $NAME_FILE . '.xlsx');
        $NEW_NAME_FILE = $ID_EXPORT . "_" . $NAME_FILE . "_" . date("Ymd");
        $this->_model->modificaExport($ID_EXPORT, './TMP/' . $NEW_NAME_FILE . '.xlsx');
        copy("./TMP/$NAME_FILE.xlsx", "./TMP/$NEW_NAME_FILE.xlsx");
        shell_exec('chmod 774 ./TMP/' . $NEW_NAME_FILE . '.xlsx');
        shell_exec('chmod 774 ./TMP/*.xlsx');
        echo json_encode("./TMP/$NEW_NAME_FILE.xlsx");
    }

    /**
     * updatevaliditacontrollo
     *
     * @return void
     */
    public function updatevaliditacontrollo()
    {

        $ID_CONTR = @$_POST['ID_CONTR'];
        $ID_LANCIO = @$_POST['selectLancio'];
        $FLG_VALIDITA = @$_POST['FLG_VALIDITA'];
        $ID_GRUPPO = @$_POST['selectGruppo'];

        $maxLancio = $this->_model->getUltimoLancio($ID_GRUPPO);

        if ($ID_CONTR) {
            $this->_model->updateValiditaControllo($ID_CONTR, $FLG_VALIDITA);
            // $this->contentList();
        } else {
            header('HTTP/1.0 419 Custom Error');
            echo json_encode(['error' => true, 'errorMessage' => 'Qualcosa è andato storto!']);
        }
    }

    /**
     * modificacontrollo
     *
     * @return void
     */
    public function modificacontrollo()
    {

        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $ID_LANCIO = @$_POST['selectLancio'];
        $ID_CONTR = @$_POST['ID_CONTR'];
        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $ID_GRUPPO = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];

        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;

        $datiControlli = $this->_model->getControlliAnagByIdContr($ID_CONTR);
        $controlli = $datiControlli[0];
        include "view/controlli/modificacontrollo.php";
    }



    /**
     * modificalancio
     *
     * @return void
     */
    public function modificalancio()
    {

        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $ID_LANCIO = @$_POST['selectLancio'];
        $ID_CONTR = @$_POST['ID_CONTR'];
        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $ID_GRUPPO = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];

        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;
        $datiControlli = $this->_model->getControlliAnagLancio($ID_LANCIO, '', $ID_CONTR);
        $controlli = $datiControlli[0];
        //$this->debug("controlli",$controlli);
        include "view/controlli/modificalancio.php";
    }

    /**
     * addcontrollo
     *
     * @return void
     */
    public function addcontrollo()
    {

        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $ID_LANCIO = @$_POST['selectLancio'];
        $ID_CONTR = @$_POST['ID_CONTR'];
        $selectSottoGruppo = @$_POST['selectSottoGruppo'];
        $selectGruppo = @$_POST['selectGruppo'];
        $selectLancio = @$_POST['selectLancio'];
        $ID_GRUPPO = @$_POST['selectGruppo'];
        $ID_INPORT = @$_POST['selectInport'];

        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;
        //  $datiControlli = $this->_model->getControlliAnagLancio($ID_LANCIO, '', $ID_CONTR);
        // $controlli = $datiControlli[0];
        include "view/controlli/modificacontrollo.php";
    }




    /**
     * modificadbcontrollo
     *
     * @return void
     */
    public function modificadbcontrollo()
    {
        $debugArray =  $this->_model->updateControllo($_POST);
    }
    /**
     * modificadbLancio
     *
     * @return void
     */
    public function modificadbLancio()
    {
        $ID_CONTR = @$_POST['ID_CONTR'];
        $ID_LANCIO = @$_POST['ID_LANCIO'];
        $FLG_VALIDITA = @$_POST['FLG_VALIDITA'];
        $ID_GRUPPO = @$_POST['selectGruppo'];
        $this->debug("_POST", $_POST);

        if ($ID_LANCIO) {
            $debugArray =  $this->_model->updateLancio($_POST);
            $this->debug("debugArray", $debugArray);
            //$this->contentList();
        } else {
            header('HTTP/1.0 419 Custom Error');
            echo json_encode(['error' => true, 'errorMessage' => 'ID_LANCIO non valido!']);
        }
    }






    /**
     * aggionalanciocontrollo
     *
     * @return void
     */
    public function aggionalanciocontrollo()
    {
        $ID_CONTR = @$_POST['ID_CONTR'];
        $ID_LANCIO = @$_POST['selectLancio'];

        $datiControlli = $this->_model->getControlliAnagLancio($ID_LANCIO, '', $ID_CONTR);
        $sql = $datiControlli[0]['TESTO'];
        $RISULTATO = $this->_model->eseguiQuery($sql);
        $this->_model->aggionaLancioControllo($ID_CONTR, $ID_LANCIO, $RISULTATO);
        //  $this->contentList();

    }

    /**
     * listalanci
     *
     * @return void
     */
    public function listalanci()
    {
        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;
        $ID_CONTR = @$_POST['ID_CONTR'];
        $datiControlliLanci = $this->_model->getControlliLanci($ID_CONTR);
        // $this->debug('datiControlliLanci',$datiControlliLanci);
        include "view/controlli/listalanci.php";
        //  $this->contentList();

    }


    public function storicodownload()
    {
        $array_classe = controlli_dati::$array_classe;
        $array_esito = controlli_dati::$array_esito;
        $ID_CONTR = @$_POST['ID_CONTR'];
        $datiStorico = $this->_model->getStoricoDownload();
        // $this->debug('datiControlliLanci',$datiControlliLanci);
        include "view/controlli/storicodownload.php";
        //  $this->contentList();

    }


    public function filtralancio()
    {
        $DatiLancio = "";
        $ID_LANCIO = @$_POST['selectLancio'];
        if ($ID_LANCIO) {
            $DatiLancio = $this->_model->getDatiLancio($ID_LANCIO);
        }
        /*   $productData = array();

        foreach ($datiSuggestion as $row) {
            $data['productID'] = $row['ID_LANCIO'];
            $data['value'] = $row['FORZATURA'];
            array_push($productData, $data);
        }*/


        // Restituisci i risultati come array JSON
        echo json_encode($DatiLancio);
    }



    public function getsottogruppo()
    {
        $ID_GRUPPO = $_POST['ID_GRUPPO'];
        $SOTTO_GRUPPO = $_POST['SOTTO_GRUPPO'];
        $ID_LANCIO = $_POST['ID_LANCIO'];
        $datiSuggestion = $this->_model->getSottoGruppi($ID_GRUPPO, $ID_LANCIO, $SOTTO_GRUPPO);
        $productData = array();

        foreach ($datiSuggestion as $row) {
            $data['productID'] = $row['SOTTO_GRUPPO'];
            $data['value'] = $row['SOTTO_GRUPPO'];
            array_push($productData, $data);
        }


        // Restituisci i risultati come array JSON
        echo json_encode($productData);
    }
    /**
     * gettipo
     *
     * @return void
     */
    public function gettipo()
    {
        $searchTerm = $_POST['term'];
        $datiSuggestion = $this->_model->getTipo('', $searchTerm);
        $productData = array();

        foreach ($datiSuggestion as $row) {
            $data['productID'] = $row['ID_TIPO'];
            $data['value'] = $row['TIPO'];
            array_push($productData, $data);
        }


        // Restituisci i risultati come array JSON
        echo json_encode($productData);
    }
    /**
     * getforzatura
     *
     * @return void
     */
    public function getforzatura()
    {
        $searchTerm = $_POST['term'];
        $datiSuggestion = $this->_model->getLancioForzatura($searchTerm);
        $productData = array();

        foreach ($datiSuggestion as $row) {
            $data['productID'] = $row['ID_LANCIO'];
            $data['value'] = $row['FORZATURA'];
            array_push($productData, $data);
        }


        // Restituisci i risultati come array JSON
        echo json_encode($productData);
    }

    public function selectfile()
    {
        $ID_GRUPPO = @$_POST['ID_GRUPPO'];
        $SOTTO_GRUPPO = @$_POST['SOTTO_GRUPPO'];
        $Agruppi = $this->_model->getGruppi($ID_GRUPPO);
        $GRUPPO = $Agruppi[0]['DESCR'];
        $productData = array();
        $getFILE = $this->_model->getFILE($GRUPPO, $SOTTO_GRUPPO);
        // $this->debug("getFILE",$getFILE);
        foreach ($getFILE as $row) {
            $data['ID_FILE'] = $row['ID_FILE'];
            $data['INPUT_FILE'] = basename($row['INPUT_FILE']);
            array_push($productData, $data);
        }


        // Restituisci i risultati come array JSON
        echo json_encode($productData);
    }


    public function getstatolancio()
    {
        $ID_LANCIO = @$_POST['ID_LANCIO'];

        $LANCIO = $this->_model->getLancio($ID_LANCIO);
        // $this->debug("getFILE",$getFILE);
        $STATO = $LANCIO[0]['STATO'];


        // Restituisci i risultati come array JSON
        echo json_encode($STATO);
    }
}
