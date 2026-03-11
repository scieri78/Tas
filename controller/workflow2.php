<?php
session_start();
/** 
 * @property workflow2_model $_model
 */
class workflow2 extends helper
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '
	   
        <link rel="stylesheet" href="./view/builder/CSS/index.css?p=' . rand(1000, 9999) . '" />
        <link rel="stylesheet" href="./view/workflow2/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/workflow2/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   <link rel="stylesheet" href="./CSS/mainmenu.css?p=' . rand(1000, 9999) . '" />
	   <link rel="stylesheet" href="./CSS/excel.css?p=' . rand(1000, 9999) . '" />
	   ';


        $this->_model =  new workflow2_model();
        $this->setDebug_attivo(1);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
       // $this->debug("_SESSION",$_SESSION);
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $this->contentList();
        include "view/footer.php";
    }

    private function resetVar($ResettaPer, $Resetta)
    {
       /* if ($ResettaPer == "Resetta") {
            unset($_SESSION['IdPeriod']);
        }*/

        if ($Resetta == "Resetta") {
            unset($_SESSION['IdPeriod']);
            unset($_SESSION['IdWorkFlow']);
            unset($_SESSION['IdProcess']);
            unset($_POST['IdWorkFlow']);
            unset($_POST['IdProcess']);
            unset($_POST['SelFlusso']);
            unset($_POST['SelNomeFlusso']);
            unset($_POST['SelDipendenza']);
            unset($_POST['SelNomeDipendenza']);
            unset($_POST['SelTipo']);
            unset($_POST['LinkIdLegame']);
            unset($_POST['LinkPagina']);
            unset($_POST['LinkNameDip']);
            unset($_POST['Action']);
            unset($_POST['OnIdLegame']);
        }
    }


   public function restSession(){
    $_SESSION['error_message'] = '';
}


    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {
        $this->error_message =  $_SESSION['error_message'];
        $this->get_errors_message(); 
        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];
        $IdTeam = $_SESSION['IdTeam'];
        //$this->debug("_POST",$_POST);
        //inizializzazioni varibili post
        /*  $this->debug("_POST",$_POST);
        $this->debug("IdTeam",$IdTeam);*/
      //  $this->debug("_SESSION",$_SESSION);
        foreach ($_REQUEST as $key => $value) {
            ${$key} = $value;
        }
        //unset varibili post
        // $this->debug("IdProcess",$IdProcess);
         $this->resetVar($ResettaPer, $Resetta);
        //  $this->debug("_SESSION ",$_SESSION);
        //   $this->debug("_POST ",$_POST);

        if ($IdWorkFlow == "" and isset($_SESSION['IdWorkFlow'])) {
            $IdWorkFlow = $_SESSION['IdWorkFlow'];
        } else {
            $_SESSION['IdWorkFlow'] = $IdWorkFlow;
        }
        if ($IdPeriod == "" and isset($_SESSION['IdPeriod'])) {
            $IdPeriod = $_SESSION['IdPeriod'];
        } else {
            $_SESSION['IdPeriod'] = $IdPeriod;
        }
        if ($IdProcess == "" and isset($_SESSION['IdProcess'])) {
            $IdProcess = $_SESSION['IdProcess'];
        } else {
            $_SESSION['IdProcess'] = $IdProcess;
        }
        $IdUser=$_SESSION['CodUk'];
      /* $this->debug("IdProcess",$_SESSION['IdProcess']);
         $this->debug("IdUser",$_SESSION['CodUk']);
        $this->debug("_SESSION HTTP_USERID",$_SESSION['HTTP_USERID']);
        $this->debug("server HTTP_USERID",$_SERVER['HTTP_USERID']);*/
        
        $Action = $_POST['Action'];
        $ArrWfs = [];
        $ArrLegami = [];

        //==========   PLSQL Call Services  
        //$this->debug("IdProcess",$IdProcess);
        //$this->debug("CambiaStato",$CambiaStato);
        $WfsStato = $this->_model->getSetWfsStato($IdProcess, $CambiaStato);

        $WfsStato = $this->_model->setTipoIdprocess($IdProcess, $ChSens, $SelChSens, $SelStatoWfs);

        $this->_model->forzaScodatore($ForzaScodatore, $IdWorkFlow, $IdProcess);

        $this->_model->setDescrIdProcess($IdProcess, $NewDesc, $DescrIdP);
      //  $this->debug("post",$_POST);
        
        $Lancia = $this->_model->switchAction();
        //SC: Da commentare perchè non produce risultato e impalla tutto
        if ($Action){
            $this->_model->callElaborazioniPossibili($Action, $IdProcess, $IdWorkFlow);
       }
        //==========   Create Arrs ListGruppi

        $ArrListGroup = array();
        $ListIdGroups = "0";
        $DatiIdGruppo = $this->_model->getIdGruppo($IdUser);
        foreach ($DatiIdGruppo as $row) {
            $IdGruppo = $row['ID_GRUPPO'];
            array_push($ArrListGroup, $IdGruppo); //???
            $ListIdGroups = $ListIdGroups . "," . $IdGruppo;
        }

        //==========   Create Arrs Data
        $DatiAuthWfs = $this->_model->getAutorizzazioniWorkflow($ListIdGroups);
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

            array_push($ArrWfs, array($Id, $Name, $Desc, $ReadOnly, $Freq, $Multi));
        }
        $retwf= false;      

        if (count($ArrWfs) == 1) {
            $IdWorkFlow = $Id;
            $WfsName = $Name;
            $WfsDescr = $Desc;
            $WfsReadOnly = $ReadOnly;
            $WfsFreq = $Freq;
            $WfsMulti = $Multi;
        }
       // $this->debug("",$DatiAuthWfs);
        //==========   Create Arrs Data
        //==========   PERIODO
        if ($IdWorkFlow != "") {
            $ArrPeriod = [];
            $DatiPeriodo = $this->_model->getPeriodo($IdWorkFlow, $IdPeriod, $ArrPeriod);
            foreach ($DatiPeriodo as $row) {
                $Perd = $row['PERIODO'];
                if ("$IdPeriod" == "") {
                    $IdPeriod = $Perd;
                }
                array_push($ArrPeriod, $Perd);
            }
            if (count($ArrPeriod) == 1) {
                $IdPeriod = $Perd;
            }

            if ($IdPeriod != "") {

                if ($AddIdProcess == "1") {

                    $ProcEserEsame = substr($IdPeriod, 0, 4);
                    $ProcMeseEsame = ltrim(substr($IdPeriod, 4, 3), 0);
                    $_SESSION['ProcMeseEsame'] = $ProcMeseEsame;
                    $ShowErrore = $this->_model->callAddIdProcess($IdWorkFlow, $IdPeriod, $ProcEserEsame, $ProcMeseEsame, $IdTeam);
                }
            }
            $_SESSION['Old_Stato_Flussi'] = $this->_model->getStatoFlussi($IdProcess, $IdWorkFlow, $ProcMeseEsame);
            //FLUSSI
            $ArrFlussi = [];
            $ArrPreFlussi = [];
            $ArrSucFlussi = [];
            $ArrIdProcess = [];



            $ArrFlussi = $this->_model->getArrFlussi($IdWorkFlow, $ArrFlussi);
            $DatiIdProcess = $this->_model->getIdProcess($IdWorkFlow, $IdPeriod, $ArrIdProcess, $AddIdProcess);
            foreach ($DatiIdProcess as $row) {
                $IdProc = $row['ID_PROCESS'];
                $DescrProc = $row['DESCR'];
                $EserEsameProc = $row['ESER_ESAME'];
                $MeseEsameProc = $row['MESE_ESAME'];
                $EserMeseProc = $row['ESER_MESE'];
                $FlagProc = $row['FLAG_CONSOLIDATO'];
                $TipoProc = $row['TIPO'];
                $ReadOnly = $row['READONLY'];
                $FlgStato = $row['FLAG_STATO'];
                $PCons = $row['CONS'];
                $IdTeam = $row['ID_TEAM'];

                array_push($ArrIdProcess, array($IdProc, $DescrProc, $EserEsameProc, $MeseEsameProc, $EserMeseProc, $FlagProc, $TipoProc, $ReadOnly, $FlgStato, $PCons, $IdTeam));
            }
            if (count($ArrIdProcess) == 1 or $AddIdProcess == "1") {
                $IdProcess = $IdProc;
            }
        }

        $DatiWorkflowMeseEsame = $this->_model->getWorkflowMeseEsame($IdWorkFlow, $IdPeriod, $Regime, $ProcMeseEsame, $IdProcess, $ArrLegami);
        foreach ($DatiWorkflowMeseEsame as $row) {
            $Id = $row['ID_LEGAME'];
            $Liv = $row['LIV'];
            $IdFlu = $row['ID_FLU'];
            $Prio = $row['PRIORITA'];
            $Tipo = $row['TIPO'];
            $IdDip = $row['ID_DIP'];

            array_push($ArrLegami, array($Id, $Liv, $IdFlu, $Prio, $Tipo, $IdDip));
        }
        $RegimeFlussiCount = $this->_model->getRegimeFlussiCount($IdWorkFlow);
        $IdRegimeData = $this->_model->getIdRegime($IdWorkFlow);
        $isAutorun = $this->_model->getIsAutorun($IdProcess);
        $isStop = $this->_model->getParametriIdProcess($IdProcess);
     //   $this->debug('isAutorun',$isAutorun);

        /* if ($AddIdProcess == "1") {
         // callAddKIdProcess($AddIdProcess, $ArrIdProcess, $ProcEserEsame, $ProcMeseEsame, $IdTeam, $IdWorkFlow)
          $this->_model->callAddKIdProcess($AddIdProcess, $ArrIdProcess, $ProcEserEsame, $ProcMeseEsame, $IdTeam, $IdWorkFlow);
        }*/

        /* $this->debug("IdProcess:",$IdProcess);
        $this->debug("IdProcess:",$IdProcess);
        $this->debug("IdPeriod:",$IdPeriod);   */
        // $this->debug('ArrLegami',$ArrLegami);
        $WFSAdmin = False;
        $Uk = $_SESSION['CodUk'];
        $WFSAdmin = $this->_model->getWFSAdmin($IdWorkFlow, $Uk, $WFSAdmin);
        $isWFSRead = $this->_model->isWFSRead($IdWorkFlow, $Uk);
        include "view/workflow2/index.php";
    }



    /**
     * flussiAction
     *
     * @return void
     */
    public function flussiAction()
    {
        $IdWorkFlow = $_SESSION['IdWorkFlow'];
        $IdPeriod = $_SESSION['IdPeriod'];
        $IdProcess = $_SESSION['IdProcess'];
        $IdUser = $_SESSION['CodUk'];
        $Action = $_POST['Action'];
        //$Lancia = $this->_model->switchAction();

        //$this->_model->callElaborazioniPossibili($Action, $IdProcess, $IdWorkFlow);
        $arrayFlussi = [];
        $ArrFlussi = [];
        $ArrLegami = [];
        $Regime = '';
    	$Regime=$_POST['Regime'];
        $IdTeam = '';
        if ($IdPeriod != "") {
            $AddIdProcess = $_POST['AddIdProcess'];
            if ($AddIdProcess == "1") {

                $ProcEserEsame = substr($IdPeriod, 0, 4);
                $ProcMeseEsame = ltrim(substr($IdPeriod, 4, 3), 0);
                //    $ShowErrore = $this->_model->callAddIdProcess($AddIdProcess, $IdWorkFlow, $IdPeriod, $ProcEserEsame, $ProcMeseEsame, $IdTeam);
            }
        }
       // $ArrFlussi = $this->_model->getArrFlussi($IdWorkFlow, $ArrFlussi);
    $this->contentList();
       // echo json_encode($ArrFlussi);
    }

    /**
     * LoadFlusso
     *
     * @return void
     */
    public function LoadFlusso()
    {
        $BarraCaricamento = "rgb(21, 140, 240)";
        $BarraPeggio = "rgb(165, 108, 185)";
        $BarraMeglio = "rgb(104, 162, 111)";

        $Id = '';
        $Uk = $_SESSION['CodUk'];
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];;
        $IdProcess = $_POST['IdProcess'];
        $ProcMeseEsame = $_POST['ProcMeseEsame'];
        $dettList = $_POST['dettList'];
        $_model = $this->_model;
        if ("$IdWorkFlow" == "" or "$IdProcess" == "") {
            exit;
        }

        $IdFlu = $_POST['IdFlusso'];
        $NomeFlusso = $_POST['Flusso'];
        $DescFlusso = $_POST['DescFlusso'];
        $ProcAnno = $_SESSION['ProcAnno'];
        $ProcMese = $_SESSION['ProcMese'];
        $ListIdStato = "0";

        if ("$ProcMese" == "12") {
            $ProcAnno = $ProcAnno + 1;
            $ProcMese = "01";
        } else {
            $ProcMese = $ProcMese + 1;
        }

        $EserMese = $ProcAnno . substr($ProcMese + 1000, -2);
        $WFSAdmin = False;

        $WFSAdmin = $this->_model->getWFSAdmin($IdWorkFlow, $Uk, $WFSAdmin);
        $ArrElaTest = $this->_model->getArrElaTest($IdWorkFlow, $IdFlu);
        $ArrCarTest = $this->_model->getArrCarTest($IdWorkFlow, $IdFlu);
        $ArrShellDett = $this->_model->getArrShellDett($IdWorkFlow, $IdFlu);

        //Se PERIODO CONSOLIDATO
        if ($IdProcess != "") {
            $BlockCons = "";
            $SvalidaOff = false;
            $ArrStato = [];


            $this->_model->setSvalidaOff($IdWorkFlow, $IdFlu, $IdProcess, $SvalidaOff, $BlockCons);

            $ArrStato = $this->_model->getArrStato($IdWorkFlow, $IdFlu, $ListIdStato, $ArrStato);
            foreach ($ArrStato as $row) {
                $TabIdFlu = $row['ID_FLU'];
                $Tipo = $row['TIPO'];
                $IdDip = $row['ID_DIP'];
                $Inizio = $row['INIZIO'];
                $Fine = $row['FINE'];
                $Esito = $row['ESITO'];
                $Note = $row['NOTE'];

                array_push($ArrStato, array($TabIdFlu, $Tipo, $IdDip, $Inizio, $Fine, $Esito, $Note));
                $ListIdStato = $ListIdStato . "," . $Id; //???
            }
        }

        $ArrUsers = [];
        $DatiArrUsers = $this->_model->getArrUsers();

        foreach ($DatiArrUsers as $row) {
            $TNom = $row['NOMINATIVO'];
            $TUserN = $row['USERNAME'];

            array_push($ArrUsers, array($TNom, $TUserN));
            $ListIdStato = $ListIdStato . "," . $Id;
        }

        $DatiListaUtenti = $this->_model->getListaUtenti();
        $ArrUsers = array();
        /* $SqlList="SELECT NOMINATIVO, USERNAME FROM WEB.TAS_UTENTI";
            $stmt=db2_prepare($conn, $SqlList);
            $res=db2_execute($stmt);
            if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }*/
        foreach ($DatiListaUtenti as $row) {
            $TNom = $row['NOMINATIVO'];
            $TUserN = $row['USERNAME'];

            array_push($ArrUsers, array($TNom, $TUserN));
            $ListIdStato = $ListIdStato . "," . $Id;
        }


        $DatiListaLegami = $this->_model->getListaLegami($Uk, $IdProcess, $IdWorkFlow, $IdFlu, $ProcMeseEsame);

        $ArrLegami = array();
        //  $this->debug('DatiListaLegami',$DatiListaLegami);
        foreach ($DatiListaLegami as $row) {
            $IdLegame = $row['ID_LEGAME'];
            $Prio = $row['PRIORITA'];
            $Tipo = $row['TIPO'];
            $IdDip = $row['ID_DIP'];
            $DipName = $row['DIPENDENZA'];
            $DipDesc = $row['DESCR'];
            $DipUtente = $row['UTENTE'];
            $DipIniz = $row['INIZIO'];
            $DipFine = $row['FINE'];
            $DipDiff = $row['DIFF'];
            $DipEsito = $row['ESITO'];
            $DipNote = $row['NOTE'];
            $DipLog = $row['LOG'];
            $DipFile = $row['FILE'];
            $DipInCoda = $row['CODA'];
            $DipTarget = $row['TARGET'];
            $DipLinkTipo = $row['LINK_TIPO'];
            $DipElaIdSh = $row['ELAB_IDSH'];
            $DipElaTags = $row['ELAB_TAGS'];
            $RdOnly = $row['RDONLY'];
            $Permesso = $row['PERMESSO'];
            $WfsRdOnly = $row['WFSRDONLY'];
            $IdRunSh = $row['ID_RUN_SH'];
            $BlockWfs = $row['BLOCKWFS'];
            $ReadOnlyWfs = $row['READONLY'];
            $External = $row['EXTERNAL'];
            $Warning = $row['WARNING'];
            $ShowProc = $row['SHOWPROC'];
            $Opt = $row['OPT'];


            #Prev.RUN
            $OldIdRunSh = $row['OLD_ID_RUN_SH'];
            $OldInizio = $row['OLD_INIZIO'];
            $OldFine = $row['OLD_FINE'];
            $OldDiff = $row['OLD_DIFF'];
            $OldRunUser = $row['OLD_RUN_USER'];
            $OldLog = $row['OLD_LOG'];

            $DipCarConf = $row['CONFERMA_DATO'];

            $DipReadDip = $row['RDONLYDIP'];
            $DipReadFlu = $row['RDONLYFLUDIP'];
            $DipRPreview = $row['PREVIEWEND'];
            $shell = $row['SHELL'];
            $PeriodCons = $row['CONS'];
            $CntNextPrio = $row['CNTNEXTPRIO'];
            $CntNextDip = $row['CNTNEXTDIP'];
            
            
            array_push($ArrLegami, array($IdLegame, $Prio, $Tipo, $IdDip, $DipName, $DipDesc, $DipIniz, $DipFine, $DipDiff, $DipEsito, $DipNote, $DipLog, $DipFile, $DipInCoda, $DipTarget, $DipLinkTipo, $DipElaIdSh, $DipElaTags, $RdOnly, $Permesso, $WfsRdOnly, $IdRunSh, $DipUtente, $OldIdRunSh, $OldInizio, $OldFine, $OldDiff, $OldRunUser, $OldLog, $DipCarConf, $BlockWfs, $ReadOnlyWfs, $External, $Warning, $DipReadDip, $DipReadFlu, $DipRPreview, $ShowProc, $Opt, $shell, $PeriodCons,$CntNextPrio,$CntNextDip));
        }
       // $this->debug('DatiListaLegami',$DatiListaLegami);

        // $DatiCallTestSottoFlussi = $this->_model->callTestSottoFlussi($IdWorkFlow, $IdProcess, $IdFlusso, $Stato, $Errore, $Note);

           $attivoA = $this->_model->getIsAutorun($IdProcess);
           


        include "view/workflow2/LoadFlusso.php";
    }


    /**
     * getChangeStato
     *
     * @return void
     */
    public function getChangeStato()
    {

        $IdFlusso = $_POST['IdFlusso'];
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];
        $IdProcess = $_POST['IdProcess'];
        $ProcMeseEsame = $_SESSION['ProcMeseEsame'];

        $ret = $this->_model->getStatoCoda($IdProcess,$IdWorkFlow);
        echo json_encode($ret);

        /*  $StatoFlussi = $this->_model->getStatoFlussi($IdProcess, $IdWorkFlow, $ProcMeseEsame);
        $ret = [];

        foreach ($StatoFlussi as $IdFlusso => $Stato) {
         
            if ($_SESSION['Old_Stato_Flussi'][$IdFlusso] != $Stato) {
                $ret[] = $IdFlusso;
                $_SESSION['Old_Stato_Flussi'][$IdFlusso] = $Stato;
            }
        }*/
        // echo json_encode($ret);
    }
    /**
     * Legend
     *
     * @return void
     */
    public function Legend()
    {
        include "view/workflow2/Legend.php";
    }

    /**
     * Flusso
     *
     * @return void
     */
    public function Flusso($IdWorkFlow = '', $IdProcess = '', $IdFlusso = '', $ProcMeseEsame = '')
    {

        $Uk = $_SESSION['CodUk'];
        if (!$IdFlusso) {
            $IdWorkFlow = $_REQUEST['IdWorkFlow'];
            $IdProcess = $_POST['IdProcess'];
            $ProcMeseEsame = $_POST['ProcMeseEsame'];
            $IdFlusso = $_POST['IdFlusso'];
        }

        if ($IdWorkFlow == "" or $IdProcess == "") {
            exit;
        }


        $DatiFlussiegami = $this->_model->getFlussiLgami($ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso);
        $DatiFlussiegamiGruppo = $this->_model->getFlussiLgamiGruppo($Uk, $ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso);

        $Stato = 'F';
        $ArrLegami = [];
        foreach ($DatiFlussiegami as $row) {
            $Id = $row['ID_LEGAME'];
            $Liv = $row['LIV'];
            $IdFlu = $row['ID_FLU'];
            $Prio = $row['PRIORITA'];
            $Tipo = $row['TIPO'];
            $IdDip = $row['ID_DIP'];

            array_push($ArrLegami, array($Id, $Liv, $IdFlu, $Prio, $Tipo, $IdDip));
        }

        //DIP FLUSSI
        $ArrPreFlussi = [];
        foreach ($ArrLegami as $Legame) {
            //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
            $IdFlu = $Legame[2];
            $Tipo = $Legame[4];
            $IdDip = $Legame[5];
        if ( $Tipo == "F" and $IdFlu == $IdFlusso){
                array_push($ArrPreFlussi, $IdDip);
            }
        }

        $ArrSucFlussi = [];
        foreach ($ArrLegami as $Legame) {
            //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
            $IdFlu = $Legame[2];
            $Tipo = $Legame[4];
            $IdDip = $Legame[5];
           if ( $Tipo == "F" and $IdDip == $IdFlusso ){
                array_push($ArrSucFlussi, $IdFlu);
            }
        }


        foreach ($DatiFlussiegamiGruppo as $row) {
            $NomeFlusso = $row['FLUSSO'];
            $DescFlusso = $row['DESCR'];
            $BlockCons = $row['BLOCK_CONS'];
            $PeriodCons = $row['CONS'];

            $CntKO = $row['KO'];
            $CntOK = $row['OK'];
            $CntIZ = $row['IZ'];
            $CntTD = $row['TD'];
            $CntTT = $row['TT'];
            $CntTTF = $row['TTF'];
            $CntWar = $row['WAR'];

            $CntCD = $row['CODA'];
            $RdOnly = $row['RDONLY'];
            $Permesso = $row['PERMESSO'];
            $WfsRdOnly = $row['WFSRDONLY'];
            $ARich = $row['ARICH'];


            $Alert = $row['ALERT'];
            if ("$Alert" != 0) {
                $Warning = "-1";
            } else {
                $Warning = $row['WARNING'];
            }

            $StManual = $row['MANUAL'];
            $CntTCD = $row['TCD'];
            $CntLab = $row['CNTLAB'];
        }


        if ($CntTTF != 0) {

            $Errore = 0;
            $Note = "";
            $Stato = 'N';
            $SottoFlussi = $this->_model->callTestSottoFlussi($IdWorkFlow, $IdProcess, $IdFlusso, $Stato, $Errore, $Note);
            $Stato = $SottoFlussi['Stato'];
       
            // $this->debug("CntOK:".$CntOK. " - CntTT:". $CntTT." - Stato:".$Stato );
            if ($CntKO != 0) {
                $Esito = "E";
            } else {
                if ($CntOK == $CntTT and $CntTT != 0 and $Stato == "S" ) {
                    $Esito = 'F';
                } else {
                    if ( $CntOK == 0 and $CntIZ == 0  and $Stato == "N" ) {
                        $Esito = 'N';
                    } else {
                        if ($CntIZ != 0) {
                            $Esito = 'I';
                        } else {
                            $Esito = 'C';
                        }
                    }
                }
            }
	   
	   } else {
        // $this->debug("CntOK:".$CntOK. " - CntTT:". $CntTT." - Stato:".$Stato );
        if ($CntKO != 0) {
            $Esito = "E";
        } else {
            if ($CntOK == $CntTT and $CntTT != 0 ) {
                $Esito = 'F';
            } else {
                if ( $CntOK == 0 and $CntIZ == 0 ) {
                    $Esito = 'N';
                } else {
                    if ($CntIZ != 0) {
                        $Esito = 'I';
                    } else {
                        $Esito = 'C';
                    }
                }
            }
        }		
	   }
		
		
       

        if ($NomeFlusso == "ZUTILITY") {
            $NomeFlusso = "UTILITY";
            $Img = "Optional";
            $classFlusso = "FlussoUtility";
            $ColTit = "black";
        } else {
            $Img = "DaEseguire";
            $classFlusso = "FlussoDaEseguire";
            $ColTit = "black";
            switch ($Esito) {
                case 'E':
                    $Img = "Terminato";
                    $classFlusso = "FlussoTerminato";
                    $ColTit = "darkred";
                    $Mostra = "";
                    break;
                case 'I':
                    $Img = "InEsecuzione";
                    $classFlusso = "FlussoInEsecuzione";
                    $ColTit = "orange";
                    $Mostra = "";
                    break;
                case 'F':
                    $classFlusso = "FlussoEseguito";
                    $Img = "Eseguito";
                    $ColTit = "darkgreen";
                    break;
                case 'C':
                    $classFlusso = "FlussoIncompleto";
                    $Img = "Incompleto";
                    $ColTit = "darkslateblue";
                    break;
            }
        }

        /* $this->debug("ArrPreFlussi",$ArrPreFlussi);
            $this->debug("ArrSucFlussi",$ArrSucFlussi);*/
            $attivoA = $this->_model->getIsAutorun($IdProcess);
            if( $attivoA)
            {
                 

                $isAutorun = $this->_model->getAutorun($IdProcess,$IdFlusso);
            }

        include "view/workflow2/Flusso.php";
        // }
    }
    /**
     * RefreshCoda
     *
     * @return void
     */
    public function RefreshCoda()
    {
        $IdWorkFlow = $_POST["IdWorkFlow"];
        $IdProcess = $_POST["IdProcess"];

        if ($IdWorkFlow == "" or $IdProcess == "") {
            exit;
        }


        $SelIdFlu = $_POST["SelIdFlu"];
        $SelNomeFlu = $_POST["SelNomeFlu"];
        $SelIdDip = $_POST["SelIdDip"];
        $SelNomeDip = $_POST["SelNomeDip"];
        $SelTipo = $_POST["SelTipo"];
        $OldMaxTime = $_POST["MaxTime"];
        $SetMaxTime = $OldMaxTime;

        if ($SelIdFlu == "") {
            $SelIdFlu = 0;
            $SelIdDip = 0;
        }
        $DatiNumRichiesteCoda = $this->_model->getNumRichiesteCoda($IdWorkFlow, $IdProcess);

        foreach ($DatiNumRichiesteCoda as $rowTabRead) {
            $CntWfs = $rowTabRead['INTHISWORKFLOW'];
            $CntProc = $rowTabRead['INTHISPROCESS'];
            $MaxTime = $rowTabRead['MAXTIME'];
            $InRun = $rowTabRead['INRUN'];
            $Now = $rowTabRead['ORA'];
        }


        $DatiRichiesteCodaEsito = $this->_model->getRichiesteCodaEsito($IdWorkFlow, $IdProcess);
        $DatiFlusso = $this->_model->getFlusso($IdWorkFlow, $IdProcess, $OldMaxTime);
        $_model = $this->_model;

        include "view/workflow2/RefreshCoda.php";
    }

    /**
     * OpenLinkPage
     *
     * @return void
     */
  /*  public function OpenLinkPage()
    {
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];
        $IdProcess = $_POST['IdProcess'];
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];
        $Action = $_POST['Action'];
        $User = $_SESSION['codname'];
        $Uk = $_SESSION['CodUk'];
        $IdPeriod = $_SESSION['IdPeriod'];
        $IdFlu = $_POST['IdFlu'];
        $NomeFlusso = $_POST['Flusso'];
        $DescFlusso = $_POST['DescFlusso'];       
        $ProcMeseEsame = substr($IdPeriod, 4, 3);
        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];
       

        $Lancia = $this->_model->switchAction();
        $this->_model->callElaborazioniPossibili($Action, $IdProcess, $IdWorkFlow);
        switch ($Pagina) {
            case 'SetLobMapPlanDigit.php':
                $this->_model->insertPlanDigit();
                $datiParametriIdProcess = $this->_model->getParametriIdProcess($IdProcess);
                $datiPlanDigitWave = $this->_model->getPlanDigitWave($IdProcess); 
                break;
            case 'PlanDigit.php':
                $this->_model->insertPlanDigit();
                $datiParametriIdProcess = $this->_model->getParametriIdProcess($IdProcess);
                $datiPalnDigitWave = $this->_model->getPalnDigitWave($IdProcess); 
                break;
            case 'CheckA4945.php':
                $datiStatoElaborazioniDaIniziare = $this->_model->getStatoElaborazioniDaIniziare();
                $datiStatoElaborazioniInElaborazione = $this->_model->getStatoElaborazioniInElaborazione();
                $datiStatoElaborazioniInErrore = $this->_model->getStatoElaborazioniInErrore();
                $datiStatoElaborazioniFiniti = $this->_model->getStatoElaborazioniFiniti(); 
                $datiElaborazioniInCorso = $this->_model->getElaborazioniInCorso(); 
                $datiElaborazioniInErrore = $this->_model->getElaborazioniInErrore(); 
                break;
            case 'ForzaDip.php': 
                $BarraCaricamento = "rgb(21, 140, 240)";
                $BarraPeggio = "rgb(165, 108, 185)";
                $BarraMeglio = "rgb(104, 162, 111)";
                $ArrUsers = $this->_model->getListaUtentiForzaDip();
                $datiLegamiForzaDip = $this->_model->getLegamiForzaDip($IdProcess,$IdWorkFlow,$ProcMeseEsame,$Uk);
                break;
             case 'GiroRIASViva000.php': 
                $Tipo='000-COMPETENZA';
                $datiGiroRIASViva = $this->_model->getGiroRIASViva($IdProcess, $Tipo);
                break; 
            case 'GiroRIASViva.php': 
                $Tipo='VIVA';
                $datiGiroRIASViva = $this->_model->getGiroRIASViva($IdProcess, $Tipo);
                break; 
                case 'GiroRIAS2.php': 
                    $Tipo='105-PREMIFUTURI';
                    $datiGiroRIASViva = $this->_model->getGiroRIASViva($IdProcess, $Tipo);
                 break; 
                 case 'GiroRIAS1.php': 
                    $Tipo='ALL';
                    $datiGiroRIASViva = $this->_model->getGiroRIASViva($IdProcess, $Tipo); 
                 break; 
             
                
        }

       
        include "view/workflow2/OpenLinkPage.php";
       include "view/workflow2/ConfPreElab/" . $Pagina;
        include "view/workflow2/ConfPreElab.php";
    }*/

   


    /**
     * Esito
     *
     * @return void
     */
    public function Esito()
    {
        $Img = $_POST["Img"];
        $NotaRis = $_POST["NotaRis"];
        $TMsg = $_POST["TMsg"];




        switch ($Img) {
            case 'success':
                $icon = "fa-circle-check";
                break;
            case 'warning':
                $icon = "fa-circle-exclamation";
                break;
            case 'danger':
                $icon = "fa-triangle-exclamation";
                break;
        }




        include "view/workflow2/Esito.php";
    }

    /**
     * MostraCoda
     *
     * @return void
     */
    public function MostraCoda()
    {
        $IdWorkFlow = $_POST["IdWorkFlow"];
        $SelIdProcess = $_POST["IdProcess"];
        $IdProcess = $_POST["IdProcess"];
        $WfsRdOnly = $_POST["WfsRdOnly"];

        $DatiCodaFlussi = $this->_model->getCodaFlussi($IdWorkFlow,$IdProcess);
        // $this->debug('DatiCodaFlussi', $DatiCodaFlussi);
        $CountCodaRichieste = $this->_model->getCodaRichieste($IdProcess);
       include "view/workflow2/MostraCoda.php";
        
    }

    public function forzaElaborazioniPossibili()
    {
         $DatiCodaFlussi = $this->_model->forzaElaborazioniPossibili();
        $this->MostraCoda();
         
    }




    /**
     * MostraStorico
     *
     * @return void
     */
    public function MostraStorico()
    {

        $IdWorkFlow = $_POST["IdWorkFlow"];
        $SelIdProcess = $_POST["IdProcess"];
        $DatiStoricoFlussi = $this->_model->getStoricoFlussi($IdWorkFlow, $SelIdProcess);
        //  $this->debug("DatiStoricoFlussi",$DatiStoricoFlussi);
        include "view/workflow2/MostraStorico.php";
    }

     public function MostraParametri()
    {

        $IdWorkFlow = $_POST["IdWorkFlow"];
        $SelIdProcess = $_POST["IdProcess"];
        $DatiTabParametri = $this->_model->getTabParametri($SelIdProcess);
        $DatiTabFormule = $this->_model->getTabFormule($SelIdProcess);
       $TabParametri = $this->getTabellaByArray($DatiTabParametri, "Parametri");
       $TabFormule = $this->getTabellaByArray($DatiTabFormule, "Formule");
        //  $this->debug("DatiStoricoFlussi",$DatiStoricoFlussi);
        include "view/workflow2/MostraParametri.php";
    }


    /**
     * MeterBar
     *
     * @return void
     */
    public function MeterBar()
    {

        include "view/workflow2/MeterBar.php";
    }

    /**
     * GraphShell
     *
     * @return void
     */
    public function GraphShell()
    {
        $IdSh = $_GET['IDSH'];
        if ($IdSh) {
            $STEP = $this->_model->getShellName($IdSh);
            include "view/workflow2/GraphShell.php";
        }
    }


    /**
     * GeneraDiagram
     *
     * @return void
     */
    public function GeneraDiagram()
    {
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];
        $IdProcess = $_POST['IdProcess'];
        $WfsName = $_POST['WfsName'];
        $ProcDescr = $_POST['ProcDescr'];
        $_model = $this->_model;

        $DatiMeseEsame = $this->_model->getMeseEsame($IdProcess);

        foreach ($DatiMeseEsame as $row) {
            $ProcMeseEsame = $row['MESE_ESAME'];
        }

        $DatiListaTestFlussi = $this->_model->getListaTestFlussi($IdWorkFlow);
        $ArrElaTest = [];
        $ArrTest = [];
        foreach ($DatiListaTestFlussi as $row) {
            $IdFl = $row['ID_FLU'];
            array_push($ArrTest, $IdFl);
        }
        if ($IdWorkFlow != "") {
            $ArrLevel = [];
            $DatiLivLegamiFlussi = $this->_model->getLivLegamiFlussi($IdWorkFlow);
            foreach ($DatiLivLegamiFlussi as $row) {
                $Liv = $row['LIV'];

                array_push($ArrLevel, $Liv);
            }
            $ArrLegami = [];
            $DatiLegamiFlussi = $this->_model->getLegamiFlussi($IdWorkFlow);
            foreach ($DatiLegamiFlussi as $row) {
                $Id = $row['ID_LEGAME'];
                $Liv = $row['LIV'];
                $IdFlu = $row['ID_FLU'];
                $Prio = $row['PRIORITA'];
                $Tipo = $row['TIPO'];
                $IdDip = $row['ID_DIP'];

                array_push($ArrLegami, array($Id, $Liv, $IdFlu, $Prio, $Tipo, $IdDip));
            }
            $DatiFlussoLegamiFlussi = $this->_model->getFlussoLegamiFlussi($IdWorkFlow);
            $ArrFlussi = [];
            $ArrPreFlussi = [];
            $ArrSucFlussi = [];
            foreach ($DatiFlussoLegamiFlussi as $row) {
                $Id = $row['ID_FLU'];
                $Name = $row['FLUSSO'];
                $Desc = $row['DESCR'];
                array_push($ArrFlussi, array($Id, $Name, $Desc));
            }
        }

        include "view/workflow2/GeneraDiagram.php";
    }
    
    /**
     * TableKo
     *
     * @return void
     */
    public function TableKo(){
        $IdDip = $_POST['IdDip'];
        $IdProcess = $_POST['IdProcess'];
        $datiTableKo = $this->_model->getdatiTableKo($IdDip,$IdProcess);
        echo $this->getTabellaByArray($datiTableKo,'');
    }
 
 /**
  * AutoPlayForm
  *
  * @return void
  */
 public function AutoPlayForm(){
   // $this->debug("",$_POST);
    $IdWorkFlow = $_REQUEST['IdWorkFlow'];    
    $IdProcess = $_POST['IdProcess'];   
    $datiFlussi = $this->_model->getFlussiWf($IdWorkFlow);
    $datiAutorun = $this->_model->getAutorun($IdProcess);
    $ATTIVO = $this->_model->getIsAutorun($IdProcess);   
    //$this->debug('datiAutorun',$datiAutorun);
    include "view/workflow2/AutoPlayForm.php";
 }

 
 /**
  * selectDipendenza
  *
  * @return void
  */
 public function selectDipendenza()
	{
		$idFluso = @$_POST['idFluso'];
		$Dipendenze = $this->_model->getDipendenze($idFluso);
		echo json_encode($Dipendenze);
	} 
 /**
  * addAutorun
  *
  * @return void
  */
 public function addAutorun()
	{
		$ID_PROCESS = @$_POST['IdProcess'];
		$ID_LEGAME = @$_POST['ID_LEGAME'];
		$ATTIVO = @$_POST['ATTIVO'];
        $IdWorkFlow = $_SESSION['IdWorkFlow'];
        $IdPeriod = $_SESSION['IdPeriod'];
        $IdProcess = $_SESSION['IdProcess'];
		$Dipendenze = $this->_model->addAutorun($ID_PROCESS,$ID_LEGAME,$ATTIVO);
        $this->_model->forzaScodatore($ATTIVO, $IdWorkFlow, $IdProcess);
		//echo json_encode($Dipendenze);
	}


     public function stopFlusso()
	{		
        $IdProcess = $_POST['IdProcess'];	
        $this->_model->stopFlusso($IdProcess);
		//echo json_encode($Dipendenze);
	}


     public function svuotaCoda()
	{
		$IdProcess = @$_POST['IdProcess'];
		$Dipendenze = $this->_model->svuotaCoda($IdProcess);
		echo json_encode($Dipendenze);
	} 
    

}
