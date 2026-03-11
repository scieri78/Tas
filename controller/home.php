<?php
require_once("model/workflow2.php");

/** 
 * @property home_model $_model
 * @property workflow2_model $_model_wf
 */

class home extends helper
{

    function __construct()
    {
        $this->include_css = '
        <link rel="stylesheet" href="./view/home/CSS/index.css?p=' . rand(1000, 9999) . '" />
        <script src="./view/home/JS/index.js?p=' . rand(1000, 9999) . '"></script>
        ';
        $this->setDebug_attivo(1);
        $this->_model = new home_model();
        $this->_model_wf = new workflow2_model();
    }
    // mvc handler request
    public function index()
    {
        $this->benvenuto();
    }


    public function pageRedirect($url)
    {
        header('Location:' . $url);
    }
    // page redirection
    function benvenuto()
    {
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        // $this->debug("session",$_SESSION);
        if (isset($_SESSION['codNomi'])) {
            $gruppi = explode(",", $_SESSION['CodGroup']);
            $isAdmin =  in_array("'2'", $gruppi);
            $sito = $_GET['sito'];
            $tickets = $this->_model->getTicketCounts();

            $workflowData = $this->_model->getWorkflowData();
            $StatusCounts = $this->_model->getStatusCounts();
         //   $this->debug("StatusCounts",$StatusCounts);
            $nomeUtente = $_SESSION['codNomi'];
            include "view/home/benvenuto.php";
        } else {
            include "view/home/login.php";
        }
        //  include "view/home/error.php";
        include "view/footer.php";
    }

     public function WfFlussi()
    {
         $flag_consolidato = $_POST['flag_consolidato'];
        $id_workflow = $_POST['id_workflow'];
        $workflow = $_POST['workflow'];
        if(isset( $_SESSION['DWF'][$id_workflow.''. $flag_consolidato ]))
        {
        $workflowFlussi =  $_SESSION['DWF'][$id_workflow.''. $flag_consolidato ];

        include "view/home/wk_dashboard.php";

        }
        else{
            $this->WfFlussiDB();
        }

    }


    public function WfFlussiDB()
    {
        $flag_consolidato = $_POST['flag_consolidato'];
        $id_workflow = $_POST['id_workflow'];
        $workflow = $_POST['workflow'];
        $workflowFlussi = $this->_model->getWorkflowFlussi($id_workflow, $flag_consolidato);
        foreach ($workflowFlussi as $k => $v) {
         //   $this->debug("IdProcess:" .$v['IdProcess']);
            $datiFusso =  $this->Flusso($v['IdWorkflow'], $v['IdProcess'], $v['meseEsame']);
            $workflowFlussi[$k]['InErrore'] = $datiFusso['InErrore'];
            $workflowFlussi[$k]['InEsecuzione'] = $datiFusso['InEsecuzione'];
            $workflowFlussi[$k]['Eseguito'] = $datiFusso['Eseguito'];
            $workflowFlussi[$k]['DaEseguire'] = $datiFusso['DaEseguire'];
            $workflowFlussi[$k]['Incompleto'] = $datiFusso['Incompleto'];
            $workflowFlussi[$k]['Utility'] = $datiFusso['Utility'];
            $workflowFlussi[$k]['NFlussi'] = $datiFusso['NFlussi'];
            //   
        }
        $_SESSION['DWF'][$id_workflow.''. $flag_consolidato ]=$workflowFlussi;

        include "view/home/wk_dashboard.php";
    }


    function error()
    {
        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        //	print_r($_REQUEST);
        include "view/home/error.php";
        include "view/footer.php";
    }

    function errorLog()
    {
        $stato = [];
        $error_message = "vv";
        $this->log_message($error_message, $stato);
        echo $error_message;
    }


    public function Flusso($IdWorkFlow = '', $IdProcess = '', $ProcMeseEsame = '')
    {

        $numeroFlussi = 0;
        $DatiFlussoLegamiFlussi = $this->_model_wf->getFlussoLegamiFlussi($IdWorkFlow);
        $ArrFlussi = [];
        $ArrPreFlussi = [];
        $ArrSucFlussi = [];


        $ret['InErrore'] = $ret['InEsecuzione'] =  $ret['Eseguito'] =  $ret['DaEseguire'] = $ret['Incompleto'] = $ret['Utility'] = 0;
        foreach ($DatiFlussoLegamiFlussi as $row) {
            $IdFlusso = $row['ID_FLU'];

            $Uk = $_SESSION['CodUk'];
            /*   if (!$IdFlusso) {
            $IdWorkFlow = $_REQUEST['IdWorkFlow'];
            $IdProcess = $_POST['IdProcess'];
            $ProcMeseEsame = $_POST['ProcMeseEsame'];
            $IdFlusso = $_POST['IdFlusso'];
        }

        if ($IdWorkFlow == "" or $IdProcess == "") {
            exit;
        }*/


            $DatiFlussiegami = $this->_model->getFlussiLgami($ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso);
            //   $this->debug("DatiFlussiegami", $DatiFlussiegami);
            $DatiFlussiegamiGruppo = [];
            if (!empty($DatiFlussiegami)) {
                //  $this->debug("numeroFlussi", ++$numeroFlussi);
                $DatiFlussiegamiGruppo = $this->_model_wf->getFlussiLgamiGruppo($Uk, $ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso);


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
                    if ($Tipo == "F" and $IdFlu == $IdFlusso) {
                        array_push($ArrPreFlussi, $IdDip);
                    }
                }

                $ArrSucFlussi = [];
                foreach ($ArrLegami as $Legame) {
                    //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
                    $IdFlu = $Legame[2];
                    $Tipo = $Legame[4];
                    $IdDip = $Legame[5];
                    if ($Tipo == "F" and $IdDip == $IdFlusso) {
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

            //     $this->debug('DatiFlussiegamiGruppo', $DatiFlussiegamiGruppo);
                if ($CntTTF != 0) {

                    $Errore = 0;
                    $Note = "";
                    $Stato = 'N';
                    try {
                     //  $SottoFlussi = @$this->_model_wf->callTestSottoFlussi($IdWorkFlow, $IdProcess, $IdFlusso, $Stato, $Errore, $Note);    
                        $SottoFlussi = @$this->_model->testSottoFlussi( $ProcMeseEsame,$IdWorkFlow, $IdProcess, $IdFlusso);                  
                        $Stato = $SottoFlussi['stato'];
                        //$this->debug("Sottoflusso Flusso Stato".$IdFlusso."Stato".$Stato);
                       
                        unset($SottoFlussi);
                    } catch (Exception  $e) {
                        $Stato = "N";
                    }
               
                 //   $this->debug("CntOK:".$CntOK. " - CntTT:". $CntTT." - Stato:".$Stato );
                    if ($CntKO != 0) {
                        $Esito = "E";
                    } else {
                        if ($CntOK == $CntTT and $CntTT != 0 and $Stato == "S") {
                            $Esito = 'F';
                        } else {
                            if ($CntOK == 0 and $CntIZ == 0  and $Stato == "N") {
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
                        if ($CntOK == $CntTT and $CntTT != 0) {
                            $Esito = 'F';
                        } else {
                            if ($CntOK == 0 and $CntIZ == 0) {
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


              //   $this->debug('NomeFlusso',$NomeFlusso);
              //   $this->debug('Esito',$Esito);

                if ($NomeFlusso == "ZUTILITY") {
                    $NomeFlusso = "UTILITY";
                    $Img = "Optional";
                    $classFlusso = "FlussoUtility";
                    $ColTit = "black";
                    $ret['Utility']++;
                } else {

                    switch ($Esito) {
                        case 'E':
                            $Img = "Terminato";
                            $classFlusso = "FlussoTerminato";
                            $ColTit = "darkred";
                            $Mostra = "";
                            $ret['InErrore']++;
                            break;
                        case 'I':
                            $Img = "InEsecuzione";
                            $classFlusso = "FlussoInEsecuzione";
                            $ColTit = "orange";
                            $Mostra = "";
                            $ret['InEsecuzione']++;
                            break;
                        case 'F':
                            $classFlusso = "FlussoEseguito";
                            $Img = "Eseguito";
                            $ColTit = "darkgreen";
                            $ret['Eseguito']++;
                            break;
                        case 'C':
                            $classFlusso = "FlussoIncompleto";
                            $Img = "Incompleto";
                            $ColTit = "darkslateblue";
                            $ret['Incompleto']++;
                            break;
                        default:
                            $Img = "DaEseguire";
                            $classFlusso = "FlussoDaEseguire";
                            $ColTit = "black";
                            $ret['DaEseguire']++;
                            break;
                    }
                }


                /* $this->debug("ArrPreFlussi",$ArrPreFlussi);
            $this->debug("ArrSucFlussi",$ArrSucFlussi);*/
                $attivoA = $this->_model_wf->getIsAutorun($IdProcess);
                if ($attivoA) {


                    $isAutorun = $this->_model_wf->getAutorun($IdProcess, $IdFlusso);
                }
            }

            //  include "view/workflow2/Flusso.php";
            // }
        }
        $ret['NFlussi'] =  $ret['InErrore'] + $ret['InEsecuzione'] +  $ret['Eseguito'] +  $ret['DaEseguire'] + $ret['Incompleto'] + $ret['Utility'];
        return $ret;
    }
}
