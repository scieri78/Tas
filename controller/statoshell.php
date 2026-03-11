<?php
include_once("./model/statoshell_dati.php");
/** 
 * @property statoshell_model $_model
 */
class statoshell extends helper
{
    private $_datishell;

    function __construct()
    {
        $this->setDebug_attivo(1);
        $DATABASE = $_SESSION['DATABASE'];

        if ($_REQUEST['DARETI'] != 1) {
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
                    <link rel="stylesheet" href="./view/statoshell/CSS/index.css?p=' . rand(1000, 9999) . '">	
                    <link rel="stylesheet" href="./CSS/StatoReti.css?p=' . rand(1000, 9999) . '">
                    <link rel="stylesheet" href="./CSS/excel.css?p=' . rand(1000, 9999) . '">';
        $this->_model = new statoshell_model($db_name);
        $db_name =  $this->_model->getDbName();        //$this->debug("db_name", $db_name);

        $this->_datishell = new statoshell_dati();
        $this->_datishell->DB2database = $db_name;
        $this->_model->setMeseElab($this->_datishell);
        $this->_model->setPID($this->_datishell);
    }
    // mvc handler request    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {

       // $this->debug("post", $_POST);

        $_view['include_css'] = $this->include_css;
        include "view/header.php";
        $_modelshell = $this->_model;
        //$datiLastRun = $this->_model->getLastRun($this->_datishell);
        $DatiSelMeseElab = $this->_model->getMeseElam($this->_datishell);
        $DatiSelLastMeseElab = $this->_model->getSelLastMeseElab($this->_datishell);
        $DatiSelInDate = $this->_model->getSelInDate($this->_datishell);
        $DatiSelEserMese = $this->_model->getSelEserMese($this->_datishell);
        $DatiSelShellFather = $this->_model->getSelShellFather($this->_datishell);
        $DatiSelShellSons = $this->_model->getSelShellSons($this->_datishell);
        $DatiSelIdProc = $this->_model->getSelIdProc($this->_datishell);

        $resetSession = 0;
        //$this->debug("DatiSelMeseElab",$DatiSelMeseElab);
        $datishell = $this->_datishell;
        $viewfilter = $_POST['viewfilter'];
        //	$_SESSION['datishell']=serialize($datishell);
        //include 'view/statoshell/lastRun.php'; 
        $DARETI = $_REQUEST['DARETI'] == 1 ? 1 : 0;

        $DatiAmbiti = $this->_model->getAmbiti();

        include 'view/statoshell/filtroShell.php';



        $this->list();

        include "view/footer.php";
    }


    /**
     * lastRun
     *
     * @return void
     */
    public function lastRun()
    {
        $datiLastRun = $this->_model->getLastRun($this->_datishell);
        include 'view/statoshell/lastRun.php';
    }



    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {
        $_modelshell = $this->_model;
        // $this->debug("contentList");
        $_model = $this->_model;
        //$datiLastRun = $this->_model->getLastRun($this->_datishell);
        $DatiSelMeseElab = $this->_model->getMeseElam($this->_datishell);
        $DatiSelLastMeseElab = $this->_model->getSelLastMeseElab($this->_datishell);
        $DatiSelInDate = $this->_model->getSelInDate($this->_datishell);
        $DatiSelEserMese = $this->_model->getSelEserMese($this->_datishell);
        $DatiSelShellFather = $this->_model->getSelShellFather($this->_datishell);
        $DatiSelShellSons = $this->_model->getSelShellSons($this->_datishell);
        $DatiSelIdProc = $this->_model->getSelIdProc($this->_datishell);
        $viewfilter = $_POST['viewfilter'];
        $resetSession = 0;


        //$this->debug("DatiSelMeseElab",$DatiSelMeseElab);
        $datishell = $this->_datishell;
        //	$_SESSION['datishell']=serialize($datishell);
        //include 'view/statoshell/lastRun.php';
        if (!isset($_POST['DAOPENLINK'])) {
            include 'view/statoshell/filtroShell.php';
        }


        $this->list();
    }



    /**
     * list
     *
     * @return void
     */
    public function list()
    {
        // $this->debug("list");
        $_modelshell = $this->_model;
        // include './GESTIONE/connection.php';
        $datishell = $this->_datishell;
        $_model = $this->_model;
        $DatiListShell = $this->_model->getListShell($this->_datishell);
        if (!isset($_POST['DAOPENLINK'])) {
            include 'view/statoshell/ListFunction.php';
            //include 'view/statoshell/ListHead_bk.php';

            include 'view/statoshell/ListHead.php';
        }
        unset($_POST['DAOPENLINK']);

        $IdShOld = "";
        $IdRunShOld = "";
        $OLDIdSql = "";
        $CntR = 0;
        $CntS = 0;
        $ShCntPass = 0;
        $FirstIdRunSh = "";
        $AllPage = array();
        foreach ($DatiListShell as $row) {
            //  while ($row = db2_fetch_assoc($stmt)) {
            $IdSh = $row['ID_SH'];
            $IdRunSh = $row['ID_RUN_SH'];
            $ShName = $row['NAME'];
            $ShSTART_TIME = $row['START_TIME'];
            $ShEND_TIME = $row['END_TIME'];
            $ShFather = $row['ID_RUN_SH_FATHER'];
            $ShLog = $row['LOG'];
            $ShStatus = $row['STATUS'];
            $ShUser = $row['USERNAME'];
            $ShDebugSh = $row['DEBUG_SH'];
            $ShDebugDb = $row['DEBUG_DB'];
            $ShMail = $row['MAIL'];
            $ShEserMese = $row['ESER_MESE'];
            $ShEserEsame = $row['ESER_ESAME'];
            $ShMeseEsame = $row['MESE_ESAME'];
            $ShSecDiff = $row['SH_SEC_DIFF'];
            $ShShellPath = $row['SHELL_PATH'];
            $ShVariables = $row['VARIABLES'];
            $ShSons = $row['N_SON'];
            $ShRc = $row['RC'];
            $ShMessage = $row['MESSAGE'];
            $ShMessage = str_replace("$ShShellPath/$ShName:", '', $ShMessage);
            $ShMessage = str_replace("'", '', $ShMessage);
            $ShLastSecDiff = $row['LASTSH_SEC_DIFF'];
            $ShTags = $row['TAGS'];
            $ShPrwEnd = $row['PREVIEW_SH_END'];
            $ShCntPass = $row['CNTPASS'];
            $IdSql = $row['ID_RUN_SQL'];
            $WaitTime = $row['WAITTIME'];

            $ShTabTouch  = $row['SH_TAB_TOUCH'];
            $FShTabTouch = $row['FSH_TAB_TOUCH'];
            $SqlTabTouch = $row['SQL_TAB_TOUCH'];

            if ($FirstIdRunSh == "" &&  $datishell->SelNumPage == 1) {
                $FirstIdRunSh = $IdRunSh;
            }

            $Test = "${IdSh}${ShVariables}$IdRunSh";
            if ($datishell->SpliVar != "SpliVar") {
                $Test = "${IdSh}${ShTags}$IdRunSh";
            }
            if ($IdShOld != $Test) {
                $CntR = $CntR + 1;
                $CntS = 0;
                $NumPage = ceil($CntR / 10);

                if (! in_array($NumPage, $AllPage)) {
                    array_push($AllPage, $NumPage);
                }
            } else {
                $CntS = $CntS + 1;
            }

            if ($NumPage != $OldNumPage) {
                $IdShOld = "";
                $OldNumPage = $NumPage;
            }

            if (($NumPage == $datishell->SelNumPage || $datishell->LastShellRun == "LastShellRun" || $datishell->DA_RETITWS != "")
                and ($CntS < $datishell->Soglia or $datishell->SelShTarget != "")
            ) {

                $ShOldDiff = "";
                if ($ShLastSecDiff != "") {
                    $ShOldDiff = gmdate('H:i:s', $ShLastSecDiff);
                    $ShOldDiff = floor(($ShLastSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $ShLastSecDiff);
                }

                $ShDiff = gmdate('H:i:s', $ShSecDiff);
                $ShDiff = floor(($ShSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $ShSecDiff);

                $Esito = "";
                switch ($ShStatus) {
                    case 'E':
                        $Esito = "Err";
                        break;
                    case 'I':
                        $Esito = "Run";
                        break;
                    case 'F':
                        $Esito = "Com";
                        break;
                    case 'W':
                        $Esito = "War";
                        break;
                    case 'M':
                        $Esito = "Frz";
                        break;
                }

                $Test = "${IdSh}${ShVariables}$IdRunSh";
                if ("$SpliVar" != "SpliVar") {
                    $Test = "${IdSh}${ShTags}$IdRunSh";
                }
                if ($IdSql != "") {
                    $SqlType = $row['TYPE_RUN'];
                    $SqlStep = $row['STEP'];
                    $SqlFile = $row['FILE_SQL'];
                    $SqlInFile = $row['FILE_IN'];
                    $SqlSTART_TIME = $row['SQL_START'];
                    $SqlEND_TIME = $row['SQL_END'];
                    $SqlStatus = $row['SQL_STATUS'];
                    $SqlSecDiff = $row['SQL_SEC_DIFF'];
                    $SqlLastSecDiff = $row['LASTSQL_SEC_DIFF'];
                    $SqlPrwEnd = $row['PREVIEW_SQL_END'];
                    $SqlUseRoutine = $row['USE_ROUTINE'];
                    #$SqlDiff="";
                    #if ( "$SqlEND_TIME" != "" ){
                    $SqlDiff = gmdate('H:i:s', $SqlSecDiff);
                    $SqlDiff = floor(($SqlSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $SqlSecDiff);
                    #}

                    $SqlOldDiff = "";
                    if ("$SqlLastSecDiff" != "") {
                        $SqlOldDiff = floor(($SqlLastSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $SqlLastSecDiff);
                    }

                    $SqlEsito = "";
                    switch ($SqlStatus) {
                        case 'E':
                            $SqlEsito = "Err";
                            break;
                        case 'I':
                            $SqlEsito = "Run";
                            break;
                        case 'F':
                            $SqlEsito = "Com";
                            break;
                        case 'W':
                            $SqlEsito = "War";
                            break;
                        case 'M':
                            $SqlEsito = "Frz";
                            break;
                    }
                }
                $FIdSh = $row['F_ID_SH'];

                if ($FIdSh != "") {
                    //  $this->debug("row", $row);
                    $FIdRunSh = $row['F_ID_RUN_SH'];
                    $FShName = $row['F_NAME'];
                    $FShSTART_TIME = $row['F_START_TIME'];
                    $FShEND_TIME = $row['F_END_TIME'];
                    $FShFather = $row['F_ID_RUN_SH_FATHER'];
                    $FShLog = $row['F_LOG'];
                    $FShStatus = $row['F_STATUS'];
                    $FShUser = $row['F_USERNAME'];
                    $FShDebugSh = $row['F_DEBUG_SH'];
                    $FShDebugDb = $row['F_DEBUG_DB'];
                    $FShMail = $row['F_MAIL'];
                    $FShEserMese = $row['F_ESER_MESE'];
                    $FShEserEsame = $row['F_ESER_ESAME'];
                    $FShMeseEsame = $row['F_MESE_ESAME'];
                    $FShSecDiff = $row['F_SH_SEC_DIFF'];
                    $FShShellPath = $row['F_SHELL_PATH'];
                    $FShVariables = $row['F_VARIABLES'];
                    $FShSons = $row['F_N_SON'];
                    $FShRc = $row['F_RC'];
                    $FShMessage = $row['F_MESSAGE'];
                    $FShMessage = str_replace("$FShShellPath/$FShName:", '', $FShMessage);
                    $FShLastSecDiff = $row['F_LASTSH_SEC_DIFF'];
                    if ("$FShLastSecDiff" != "") {
                        $FShOldDiff = floor(($FShLastSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $FShLastSecDiff);
                    }
                    $FShTags = $row['F_TAGS'];
                    $FShPrwEnd = $row['F_PREVIEW_SH_END'];
                    $FShCntPass = $row['F_CNTPASS'];
                    $FIdSql = $row['F_ID_RUN_SQL'];
                    $FShDiff = floor(($FShSecDiff - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $FShSecDiff);

                    $FEsito = "";
                    switch ($FShStatus) {
                        case 'E':
                            $FEsito = "Err";
                            break;
                        case 'I':
                            $FEsito = "Run";
                            break;
                        case 'F':
                            $FEsito = "Com";
                            break;
                        case 'W':
                            $FEsito = "War";
                            break;
                        case 'M':
                            $FEsito = "Frz";
                            break;
                    }

                    $SelClsSh = "";
                    if ($FIdRunSh == $SelShell) {
                        $SelClsSh = "ClsRoot";
                    }
                }
            }

            if (
                $IdShOld == $Test &&
                $CntS == $datishell->Soglia &&
                $datishell->SelShTarget == "" &&
                $NumPage == $datishell->SelNumPage
            ) { ?>
                <li class="has-sub" title="<?php echo $ShName; ?>">
                    <a>
                        <table class="ExcelTable 10" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;height: 30px !important;">
                            <tr>
                                <td><B onclick="OpenShSel(<?php echo $IdRunSh; ?>);" style="color:red;border: 1px solid red;">Visualizzazione Step interrotta ( clicca per visualizzare a parte )</B></td>
                            </tr>
                        </table>
                    </a>
                </li>
<?php
            } else {
                //$this->debug("ShSons",$ShSons);
                $DataElab = $this->_model->getDataElab();
                include 'view/statoshell/List.php';
            }
        }
        if (!isset($_POST['DAOPENLINK'])) {
            include 'view/statoshell/ListFooter.php';
        }
        unset($_POST['DAOPENLINK']);
        // $this->debug("ListFooter");
    }


    /**
     * visualizzaFile
     *
     * @param  mixed $dati
     * @return void
     * 
     */
    private function visualizzaFile($dati)
    {
        $IDSH = $_POST["IDSH"];
        foreach ($dati as $rowSH) {
            $ShName = $rowSH['SHELL'];
            $ShPath = $rowSH['SHELL_PATH'];
            $file = $ShPath . "/" . $ShName;
        }
        if ($ShPath != "") {
            $ret = $this->createTMPFile($ShPath, $ShName);
            $TestoFile = $ret['TestoFile'];
            $filename = $ret['filename'];
        }
        //  $this->debug("session",$_SESSION);
        include('view/statoshell/ApriFile.php');
    }


    /**
     * setManual
     *
     * @return void
     */
    public function setManual()
    {
        $ID_RUN_SH = $_POST['ID_RUN_SH'];
        $this->_model->setManual($ID_RUN_SH);
        $this->contentList();
    }


    public function deleteSh()
    {
        $ID_RUN_SH = $_POST['ID_RUN_SH'];
        $this->_model->deleteSh($ID_RUN_SH);
        $this->contentList();
    }


    /**
     * apriFile
     *
     * @return void
     */
    public function apriFile()
    {
        $IDSH = $_POST["IDSH"];
        $datiFileInfo = $this->_model->getFileInfo($IDSH);
        $this->visualizzaFile($datiFileInfo);
    }

    /**
     * apriLog
     *
     * @return void
     */
    public function apriLog()
    {
        // $this->debug("_SESSION",$_SESSION);
        $IDSH = $_POST["IDSH"];
        $datiFileInfo = $this->_model->getLogInfo($IDSH);
        foreach ($datiFileInfo as $row) {
            $Log = $row['LOG'];
        }
        if ($Log != "") {
            $ret = $this->createTMPLog($Log);
            $TestoFile = $ret['TestoFile'];
            $filename = $ret['filename'];
        }
        include('view/statoshell/ApriFile.php');
    }


    /**
     * apriLogElab
     *
     * @return void
     */
    public function apriLogElab()
    {

        $Log = $_POST['LOG'];
        if ($Log != "") {
            $ret = $this->createTMPLog($Log);
            $TestoFile = $ret['TestoFile'];
            $filename = $ret['filename'];
        }
        include('view/statoshell/ApriFile.php');
    }

    /**
     * apriSqlFile
     *
     * @return void
     */
    public function apriSqlFile()
    {
        $IDSQL = $_POST["IDSQL"];
        $ShowVar = $_POST["ShowVar"];
        $datiFileInfo = $this->_model->getSqlFileInfo($IDSQL);
        $ret = $this->createTMPSqlFile($ShowVar, $datiFileInfo);
        $TestoFile = $ret['TestoFile'];
        $filename = $ret['filename'];
        $SqlName = $ret['sqlfilename'];
        include('view/statoshell/ApriSqlFile.php');
    }

    public function apriRelTab()
    {
        $InIdSh = $_POST['IDSH'];
        $InIdRunSh = $_POST['IDRUNSH'];
        $InIdRunSql = $_POST['IDRUNSQL'];
        $datiFileInfo = $this->_model->getRelTab($InIdRunSql, $InIdSh, $InIdRunSh);
        $datiPackage = $this->_model->getPackage($InIdRunSql, $InIdRunSh);
        include('view/statoshell/ApriRelTab.php');
    }

    public function apriTabPlsql()
    {
        $PkgSchema = $_REQUEST['SCHEMA'];
        $PkgName = $_REQUEST['PACKAGE'];
        $DatiPackageSql = $this->_model->getPackageSql($PkgSchema, $PkgName);
        include('view/statoshell/ApriTabPlsql.php');
    }

    public function apriPlsql()
    {

        $PkgSchema = $_POST['SCHEMA'];
        $PkgName = $_POST['PACKAGE'];
        $TestoPkg = $this->_model->getTestoPLSQL($PkgSchema, $PkgName);
        $ret = $this->createDdlPLSQLFile($PkgSchema, $PkgName, $TestoPkg);
        //$TestoFile = $ret['TestoFile'];
        $filename = $ret['filename'];

        include('view/statoshell/ApriPlsql.php');
    }





    public function apriTabLog()
    {
        $IDSQL = $_POST["IDSQL"];
        include('view/statoshell/ApriTabLog.php');
    }

    public function downloadfile()
    {
        $filename = $_GET["filename"];
        $filedir = $_GET["filedir"];
        $rootdir = $_SESSION['PSITO'];
        $rootdir = str_replace(["TASUSR", "TASWRK"], ["TASMVC", "TASMVC"], $rootdir);
        $file = $rootdir . $filedir . '/' . $filename;
        include('view/statoshell/downloadFile.php');
    }



    public function apriGrafici()
    {
        $STEP = $_POST['STEP'];
        $TAGS = $_POST['TAGS'];
        $IDSH = $_POST['IDSH'];
        include('./view/statoshell/ApriGrafici.php');
    }


    public function graph_step()
    {
        $Step = @$_GET["STEP"];
        $Tags = @$_GET["TAGS"];
        $Mesi = @$_GET["MESI"];
        $IdSh = @$_GET["IDSH"];
        $x_axis = array();
        $y_axis = array();
        $i = 0;
        $MinDurata = 0;
        $MaxDurata = 0;
        $DurataSh = $this->_model->getDurataSh($Step, $Mesi, $Tags, $IdSh);
        $this->debug("DurataSh", $DurataSh);
        foreach ($DurataSh as $row) {
            $ESER_MESE = $row["ESER_MESE"];
            $Durata = $row["DURATA"];
            array_push($x_axis, $ESER_MESE);
            array_push($y_axis, $Durata);
            if ($MinDurata >= $Durata) {
                $MinDurata = $Durata;
            }
            if ($MaxDurata <= $Durata) {
                $MaxDurata = $Durata;
            }
            $i = $i + 1;
        }
        include('./view/statoshell/graph_step.php');
    }

    public function rcLegend()
    {
        include('./view/statoshell/rcLegend.php');
    }

    public function resetSession()
    {
        $DATABASE = $_SESSION['DATABASE'];
        $_SESSION[$DATABASE] = [];
        $this->contentList();
    }
    // add new record

}
