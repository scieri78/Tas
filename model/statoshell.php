<?php
class statoshell_model
{
    private $_db;
    public $name_model = 'statoshell_model';

    /**
     * __construct
     *
     * @param  mixed $db_name
     * @return void
     */
    public function __construct($db_name = '')
    {
        $this->_db = new db_driver($db_name);
    }


    public function getDbName()
    {
        return $this->_db->getdb();
    }


    /**
     * setManual
     *
     * @param  mixed $ID_RUN_SH
     * @return void
     */
    function setManual($ID_RUN_SH)
    {
        try {
            if ($ID_RUN_SH != "") {
                $sql = "UPDATE WORK_CORE.CORE_SH SET STATUS='M' WHERE ID_RUN_SH = ?";
                $this->_db->updateDb($sql, array($ID_RUN_SH));
            }
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }


    /**
     * deleteSh
     *
     * @param  mixed $ID_RUN_SH
     * @return void
     */
    function deleteSh($ID_RUN_SH)
    {
        try {
            if ($ID_RUN_SH != "") {
                $sql = "DELETE FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = ?";
                $this->_db->deleteDb($sql, array($ID_RUN_SH));
            }
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }

    /**
     * setPID
     *
     * @param  mixed $_datiShell
     * @return void
     */
    function setPID(&$_datiShell)
    {
        if ($_datiShell->getForceEnd() != "") {
            $sql = "SELECT PID FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = ?";
            $datiPid = $this->_db->getArrayByQuery($sql, array($_datiShell->getForceEnd()));

            foreach ($datiPid as $row) {
                $_datiShell->setSelPid($row['PID']);
            }

            $CntPid = 0;
            //vedere GESTIONE/SetvVar.php
            $SSHUSR = $_SESSION['SSHUSR'];
            $SERVER = $_SESSION['SERVER'];
            $PRGDIR = $_SESSION['PRGDIR'];
            $SelPid = $_datiShell->getSelPid();
            //echo "sh $PRGDIR/TestPid.sh '${SSHUSR}' '${SERVER}' '${SelPid}'";

            $CntPid = shell_exec("sh $PRGDIR/TestPid.sh '${SSHUSR}' '${SERVER}' '${SelPid}'");

            //echo "CntPid:-$CntPid-";
            if ($CntPid == 0) {

                $ForceEnd = $_datiShell->getForceEnd();
                $Sql = 'CALL WORK_CORE.K_PROCESSING.KillSh(?)';
                $values = [[
                    'name' => 'ForceEnd',
                    'value' => $ForceEnd,
                    'type' => DB2_PARAM_IN
                ]];

                $ret = $this->_db->callDb($Sql, $values);
                /*echo $this->_db->getsqlQuery();
                die();*/

                return $ret;

                // ??    $Sql='CALL WORK_CORE.K_PROCESSING.KillSh(?)';
                // ??    $stmt = db2_prepare($conn, $Sql);
                // ??    
                // ??    db2_bind_param($stmt, 1,  "ForceEnd"  , DB2_PARAM_IN);
                // ??    
                // ??    $result=db2_execute($stmt);
                // ??    if ( ! $result ){
                // ??      echo "ERROR DB2 Error State:".db2_stmt_errormsg();
                // ??    }
                // ?? 
                // ?? }else{
                // ??     
                // ??     echo "<script>alert('The Shell is Alive, you can not end this shell');</script>";
                // ?? 
                // ?? 

            }
        }
    }

    /**
     * setMeseElab
     *
     * @param  mixed $_datiShell
     * @return void
     */
    function setMeseElab(&$_datiShell)
    {

        if ($_datiShell->getSelShTarget() != "") {
            $sql = "SELECT TO_CHAR(START_TIME,'YYYYMM') MESEELAB, TO_CHAR(START_TIME,'DD') ISDAY 
                    FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = ?";

            $datiMese = $this->_db->getArrayByQuery($sql, array($_datiShell->getSelShTarget()));
            foreach ($datiMese  as $row) {
                $_datiShell->SetSelMeseElab($row['MESEELAB']);
                $_datiShell->SetSelInDate($row['ISDAY']);
            }
        }
    }
    /**
     * getDataElab
     *
     * @return void
     */
    function getDataElab()
    {


        $sql = "SELECT VALORE FROM WORK_ELAB.PARAMETRI WHERE NOME = 'DATA_ELAB'";
        $ret = $this->_db->getArrayByQuery($sql, []);
        foreach ($ret  as $row) {
            $DataElab = $row['VALORE'];
        }
        return $DataElab;
    }

    /**
     * getMeseElam
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getMeseElam(&$datishell)
    {
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB FROM WORK_CORE.CORE_SH ORDER BY MESEELAB DESC";
        $res = $this->_db->getArrayByQuery($sql);
        foreach ($res as $row) {
            $MeseElab = $row['MESEELAB'];
            if ($datishell->SelMeseElab == "") {
                $datishell->SelMeseElab = $MeseElab;
            }
            if ($datishell->SelMeseNow == "") {
                $datishell->SelMeseNow = $MeseElab;
            }
        }
        return $res;
    }
    /**
     * getSelLastMeseElab
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelLastMeseElab($datishell)
    {

        /*$sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB,
              case 
              WHEN TO_CHAR(START_TIME,'MM') = '01' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'10'
              WHEN TO_CHAR(START_TIME,'MM') = '04' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'01'
              WHEN TO_CHAR(START_TIME,'MM') = '07' THEN TO_CHAR(START_TIME,'YYYY')||'04'
              WHEN TO_CHAR(START_TIME,'MM') = '10' THEN TO_CHAR(START_TIME,'YYYY')||'07'
                  ELSE TO_CHAR(START_TIME,'YYYY')||TO_CHAR(START_TIME,'MM')-1
            end    OLDRUN
            FROM WORK_CORE.CORE_SH 
            WHERE TO_CHAR(START_TIME,'YYYYMM') <= DECODE('$datishell->SelMeseElab','%','$datishell->SelMeseNow','$datishell->SelMeseElab')
            ORDER BY MESEELAB DESC";*/


        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB,
              case
              WHEN TO_CHAR(START_TIME,'MM') = '01' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'10'
              WHEN TO_CHAR(START_TIME,'MM') = '02' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'12'
              WHEN TO_CHAR(START_TIME,'MM') = '04' THEN TO_CHAR(START_TIME,'YYYY')||'01'
              WHEN TO_CHAR(START_TIME,'MM') = '05' THEN TO_CHAR(START_TIME,'YYYY')||'03'
              WHEN TO_CHAR(START_TIME,'MM') = '07' THEN TO_CHAR(START_TIME,'YYYY')||'04'
              WHEN TO_CHAR(START_TIME,'MM') = '08' THEN TO_CHAR(START_TIME,'YYYY')||'06'
              WHEN TO_CHAR(START_TIME,'MM') = '10' THEN TO_CHAR(START_TIME,'YYYY')||'07'
              WHEN TO_CHAR(START_TIME,'MM') = '11' THEN TO_CHAR(START_TIME,'YYYY')||'09'
                  ELSE TO_CHAR(START_TIME,'YYYY')||TO_CHAR(START_TIME,'MM')-1
            end    OLDRUN
            FROM WORK_CORE.CORE_SH
            WHERE TO_CHAR(START_TIME,'YYYYMM') <= DECODE('$datishell->SelMeseElab','%','$datishell->SelMeseNow','$datishell->SelMeseElab')
            ORDER BY MESEELAB DESC";

        $res = $this->_db->getArrayByQuery($sql, array());
        //  $this->_db->printSql();
        return $res;
    }
    /**
     * getSelInDate
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelInDate($datishell)
    {
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'DD') DD FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' 
        ORDER BY 1 DESC";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getSelIdProc
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelIdProc($datishell)
    {

        $sql = "SELECT ID_PROCESS, DESCR, TIPO, FLAG_STATO, ( SELECT TEAM FROM WFS.TEAM WHERE ID_TEAM = c.ID_TEAM ) TEAM
          FROM WORK_CORE.ID_PROCESS c
          WHERE c.ID_PROCESS IN ( SELECT DISTINCT ID_PROCESS FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') 
          like '$datishell->SelMeseElab' AND ID_PROCESS IS NOT NULL )
          ORDER BY ID_PROCESS DESC";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getShellUser
     *
     * @param  mixed $IdRunSh
     * @return void
     */
    public function getShellUser($IdRunSh)
    {
        $sql = "select ID_RUN_SH
               FROM TASPCUSR.WORK_CORE.V_CORE_PROCESSING
               WHERE 1=1
               AND VARIABLES like ' SON b $IdRunSh%'
               --AND USERNAME = 'gu01009'
               AND START_TIME >= ( SELECT START_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdRunSh)
               AND END_TIME   <= ( SELECT END_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdRunSh)
               ORDER BY START_TIME";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getFileInfo
     *
     * @param  mixed $IDSH
     * @return void
     */
    public function getFileInfo($IDSH)
    {

        $sql = "SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = $IDSH";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getTestoPLSQL
     *
     * @param  mixed $PkgSchema
     * @param  mixed $PkgName
     * @return void
     */
    public function getTestoPLSQL($PkgSchema, $PkgName)
    {

        $sql = "select SOURCEHEADER, SOURCEBODY 
    from SYSIBM.SYSMODULES
    WHERE
    MODULESCHEMA = '$PkgSchema' AND MODULENAME = '$PkgName'";
        $res = $this->_db->getArrayByQuery($sql, array());
        foreach ($res as $row) {
            $PkgHead = $row['SOURCEHEADER'];
            $PkgBody = $row['SOURCEBODY'];
        }
        $TestoPkg = "$PkgHead
                /
                $PkgBody
                /
                ";

        return $TestoPkg;
    }

    /**
     * getLogInfo
     *
     * @param  mixed $IDSH
     * @return void
     */
    public function getLogInfo($IDSH)
    {

        $sql = "SELECT LOG FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IDSH";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getSqlFileInfo
     *
     * @param  mixed $IDSQL
     * @return void
     */
    public function getSqlFileInfo($IDSQL)
    {
        $ret = array();
        $sql = "SELECT FILE_SQL, ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = $IDSQL";
        $res = $this->_db->getArrayByQuery($sql, array());

        foreach ($res as $rowSQL) {
            $SqlName = $rowSQL['FILE_SQL'];
            $RunSh = $rowSQL['ID_RUN_SH'];
        }
        $ret['SqlName'] = $SqlName;
        $ret['RunSh'] = $RunSh;

        $sql = "SELECT ID_PROCESS, ESER_ESAME, MESE_ESAME, ESER_MESE FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $RunSh";
        $res = $this->_db->getArrayByQuery($sql, array());
        foreach ($res as $rowSQL) {
            $ret['ID_PROCESS'] = $rowSQL['ID_PROCESS'];
            $ret['ESER_ESAME'] = $rowSQL['ESER_ESAME'];
            $ret['MESE_ESAME'] = $rowSQL['MESE_ESAME'];
            $ret['ESER_MESE'] = $rowSQL['ESER_MESE'];
            $ret['ESER_ESAME_PREC'] =  $ret['ESER_MESE'] - 1;
            $ret['ESER_MESE_PREC'] = (($ret['ESER_MESE'] - 1) * 1000) + 12;
        }

        return $ret;
    }

    /**
     * getSearchSonRoutine
     *
     * @param  mixed $IdRunSql
     * @param  mixed $RtFather
     * @return void
     */
    function getSearchSonRoutine($IdRunSql, $RtFather)
    {

        $sql = "SELECT
                    SCHEMA,
                    PACKAGE,
                    ROUTINE,
                    START,
                    END,
                    STATUS,
                    NOTES,
                    timestampdiff(2,NVL(END,CURRENT_TIMESTAMP)-START) DIFF,
                    ID_LOG_ROUTINE,
                    ( SELECT count(*) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_FATHER = R.ID_LOG_ROUTINE AND ID_RUN_SQL = $IdRunSql ) CNT_SON
                FROM
                    WORK_ELAB.LOG_ROUTINES R
                WHERE ID_RUN_SQL = $IdRunSql
                AND ID_FATHER = $RtFather
                ORDER BY ID_LOG_ROUTINE";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }


    /**
     * getSearchFatherRoutine
     *
     * @param  mixed $IdRunSql
     * @return void
     */
    function getSearchFatherRoutine($IdRunSql)
    {
        $sql = "SELECT
                    SCHEMA,
                    PACKAGE,
                    ROUTINE,
                    START,
                    END,
                    STATUS,
                    NOTES,
                    timestampdiff(2,NVL(END,CURRENT_TIMESTAMP)-START) DIFF,
                    ID_LOG_ROUTINE,
                    ( SELECT count(*) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_FATHER = R.ID_LOG_ROUTINE AND ID_RUN_SQL = $IdRunSql ) CNT_SON
                FROM
                    WORK_ELAB.LOG_ROUTINES R
                WHERE ID_RUN_SQL = $IdRunSql
                AND ID_FATHER is null
                ORDER BY ID_LOG_ROUTINE";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }

    /**
     * getRecSonsh
     *
     * @param  mixed $IdSh
     * @param  mixed $IdRunSh
     * @param  mixed $Opentutti
     * @param  mixed $datishell
     * @return void
     */
    function getRecSonsh($IdSh, $IdRunSh, $Opentutti, $datishell)
    {


        if ($datishell->Sel_Id_Proc == "") {
            $WhereCon = " 1=1";
        }
        if ($datishell->Sel_Id_Proc == "b") {
            $WhereCon = " ID_PROCESS IS NULL";
        }
        if ($datishell->Sel_Id_Proc != "b" and  $datishell->Sel_Id_Proc != "") {
            $WhereCon = " ID_PROCESS = $datishell->Sel_Id_Proc ";
        }

        $LastShWhereCon  = " d.ID_SH = b.ID_SH  ";
        $LastFShWhereCon = " d.ID_SH = b.ID_SH  AND d.F_ID_SH  = b.F_ID_SH  ";
        $LastSqlWhereCon = " d.ID_SH = b.ID_SH  AND d.STEP     = b.STEP     AND d.FILE_SQL = b.FILE_SQL  ";

        if ($datishell->SpliVar != "SpliVar") {
            $LastShWhereCon  = $LastShWhereCon  . " AND d.TAGS_TRIM = b.TAGS_TRIM ";
            $LastFShWhereCon = $LastFShWhereCon . " AND d.TAGS_TRIM = b.TAGS_TRIM  AND d.F_TAGS_TRIM = b.F_TAGS_TRIM ";
        } else {
            $LastShWhereCon  = $LastShWhereCon  . " AND d.VARIABLES_TRIM   = b.VARIABLES_TRIM ";
            $LastFShWhereCon = $LastFShWhereCon . " AND d.VARIABLES_TRIM   = b.VARIABLES_TRIM AND d.F_VARIABLES_TRIM   = b.F_VARIABLES_TRIM ";
        }
        

        $PrecCond = "1=1";
        if ($datishell->SelLastMeseElab == "0") {
            $PrecCond = "1=2";
        }

        $sql2 = "WITH W_LAST_CORE_PROCESSING as (
          SELECT 
              *
                    ,  NVL(RTRIM(c.TAGS),'-')                TAGS_TRIM
                    ,  NVL(RTRIM(c.F_TAGS),'-')            F_TAGS_TRIM
                    ,  NVL(RTRIM(c.VARIABLES),'-')      VARIABLES_TRIM
                    ,  NVL(RTRIM(c.F_VARIABLES),'-') F_VARIABLES_TRIM
          FROM 
              WORK_CORE.V_CORE_PROCESSING c 
          WHERE 1=1
          AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab'
        )   
        ,W_PREC_CORE_PROCESSING as (
          SELECT 
              *
                    ,  NVL(RTRIM(c.TAGS),'-')               TAGS_TRIM
                    ,  NVL(RTRIM(c.F_TAGS),'-')           F_TAGS_TRIM
                    ,  NVL(RTRIM(c.VARIABLES),'-')     VARIABLES_TRIM
                    ,  NVL(RTRIM(c.F_VARIABLES),'-') F_VARIABLES_TRIM
          FROM 
              WORK_CORE.V_CORE_PROCESSING c 
          WHERE $PrecCond
          AND TO_CHAR(START_TIME,'YYYYMM') = '$datishell->SelLastMeseElab'     
          AND STATUS IN ('F','W')
          AND ID_SH in $IdSh
        )   

        SELECT ID_SH, ID_RUN_SH, 
        F_ID_SH, F_ID_RUN_SH, F_NAME,  
        TO_CHAR(F_START_TIME,'YYYY-MM-DD HH24:MI:SS') F_START_TIME,
        TO_CHAR(F_END_TIME,'YYYY-MM-DD HH24:MI:SS') F_END_TIME ,
        F_ID_RUN_SH_FATHER, F_LOG, F_STATUS, 
        NVL((SELECT T.NOMINATIVO FROM WEB.TAS_UTENTI T WHERE T.USERNAME = UPPER(F_USERNAME)),F_USERNAME) F_USERNAME,
         F_DEBUG_SH, F_DEBUG_DB, F_MAIL, F_ESER_ESAME, F_MESE_ESAME, F_ESER_MESE, F_SHELL_PATH,
        timestampdiff(2,NVL(b.F_END_TIME,CURRENT_TIMESTAMP)-b.F_START_TIME) F_SH_SEC_DIFF,
        F_RC, F_MESSAGE,RTRIM(VARIABLES) VARIABLES, F_TAGS,
        ID_RUN_SQL, TYPE_RUN, REPLACE(STEP,'- '||F_TAGS,'') STEP, FILE_SQL, FILE_IN,
        TO_CHAR(SQL_START,'YYYY-MM-DD HH24:MI:SS') SQL_START,
        TO_CHAR(SQL_END,'YYYY-MM-DD HH24:MI:SS') SQL_END,
        SQL_STATUS, RTRIM(F_VARIABLES) F_VARIABLES, RTRIM(TAGS) TAGS,
        timestampdiff(2,NVL(b.SQL_END,CURRENT_TIMESTAMP)-b.SQL_START) SQL_SEC_DIFF,
        ( SELECT COUNT(1) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = b.ID_RUN_SQL ) USE_ROUTINE ,
        ( SELECT count(*) FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH_FATHER = b.F_ID_RUN_SH  ) F_N_SON,
        ( SELECT 
               (select count(*) FROM WORK_CORE.CORE_DB   WHERE ID_RUN_SH        = b.F_ID_RUN_SH) +
               (select count(*) FROM WORK_CORE.CORE_STEP WHERE ID_RUN_SH        = b.F_ID_RUN_SH) +
               (SELECT count(*) FROM WORK_CORE.CORE_SH   WHERE ID_RUN_SH_FATHER = b.F_ID_RUN_SH)
           FROM DUAL
         ) F_CNTPASS      
        ,(SELECT timestampdiff(2,MAX(d.F_END_TIME)-MAX(d.F_START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastFShWhereCon ) F_LASTSH_SEC_DIFF      
        ,TO_CHAR(( select ADD_SECONDS(b.F_START_TIME,( SELECT timestampdiff(2,MAX(d.F_END_TIME)-MAX(d.F_START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastFShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  F_PREVIEW_SH_END   
        ,TO_CHAR(( select ADD_SECONDS(b.START_TIME,( SELECT timestampdiff(2,MAX(d.END_TIME)-MAX(d.START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SH_END                
        ,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) LASTSQL_SEC_DIFF  
        ,TO_CHAR(( select ADD_SECONDS(b.SQL_START,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SQL_END
        ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH,FILE) IN (SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = b.ID_SH ) ) SH_TAB_TOUCH
        ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH,FILE) IN (SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = b.F_ID_SH ) ) FSH_TAB_TOUCH
        ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH||'/'||FILE) IN (SELECT FILE FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = b.ID_RUN_SQL ) ) SQL_TAB_TOUCH       
        FROM W_LAST_CORE_PROCESSING b
        WHERE $WhereCon
        AND ID_RUN_SH = '$IdRunSh'                    
        ORDER BY ID_RUN_SH DESC, TIME_ORDER DESC";

        //echo $sql2;

        $res = $this->_db->getArrayByQuery($sql2, array());

        return $res;
    }

    /**
     * getListShell
     *
     * @param  mixed $datishell
     * @return void
     */
    function getListShell($datishell)
    {

        if ($datishell->Sel_Id_Proc == "") {
            $WhereCon = " 1=1";
        }
        if ($datishell->Sel_Id_Proc == "b") {
            $WhereCon = " ID_PROCESS IS NULL";
        }
        if ($datishell->Sel_Id_Proc != "b" and  $datishell->Sel_Id_Proc != "") {
            $WhereCon = " ID_PROCESS = $datishell->Sel_Id_Proc ";
        }

        $LastShWhereCon  = " d.ID_SH = b.ID_SH  ";
        $LastFShWhereCon = " d.ID_SH = b.ID_SH  AND d.F_ID_SH  = b.F_ID_SH  ";
        $LastSqlWhereCon = " d.ID_SH = b.ID_SH  AND d.STEP     = b.STEP     AND d.FILE_SQL = b.FILE_SQL  ";

        if ($datishell->SpliVar != "SpliVar") {
            $LastShWhereCon  = $LastShWhereCon  . " AND d.TAGS_TRIM = b.TAGS_TRIM ";
            $LastFShWhereCon = $LastFShWhereCon . " AND d.TAGS_TRIM = b.TAGS_TRIM  AND d.F_TAGS_TRIM = b.F_TAGS_TRIM ";
        } else {
            $LastShWhereCon  = $LastShWhereCon  . " AND d.VARIABLES_TRIM   = b.VARIABLES_TRIM ";
            $LastFShWhereCon = $LastFShWhereCon . " AND d.VARIABLES_TRIM   = b.VARIABLES_TRIM AND d.F_VARIABLES_TRIM   = b.F_VARIABLES_TRIM ";
        }




        //fine common query
        $sql = "
              WITH W_LAST_CORE_PROCESSING as (
                SELECT 
                    *
                    ,  NVL(RTRIM(b.TAGS),'-')                TAGS_TRIM
                    ,  NVL(RTRIM(b.F_TAGS),'-')            F_TAGS_TRIM
                    ,  NVL(RTRIM(b.VARIABLES),'-')      VARIABLES_TRIM
                    ,  NVL(RTRIM(b.F_VARIABLES),'-') F_VARIABLES_TRIM
                FROM 
                    WORK_CORE.V_CORE_PROCESSING b 
            ";

        if ($datishell->IDSELEM != "" or $datishell->INPASSO != "") {
            if ($datishell->IDSELEM != "") {
                $sql .= " WHERE ID_RUN_SH = $datishell->IDSELEM";
            } else {
                $sql .= " WHERE ID_RUN_SH = $datishell->INPASSO";
            }
        } else {
            $sql .= " WHERE ID_RUN_SH IN ( 
            	SELECT A.ID_RUN_SH FROM
                ( SELECT
		         ROWNUM NM, ID_RUN_SH
                 FROM WORK_CORE.CORE_SH
                 WHERE  $WhereCon
                ";

            $sql .= " AND ID_RUN_SH_FATHER IS NULL ";

            if ($datishell->Sel_Esito != "") {
                $sql .= "AND STATUS = '$datishell->Sel_Esito' ";
            }

            if ($datishell->SelEserMese != "") {
                $sql .= "
                 AND ESER_MESE = $datishell->SelEserMese
                  ";
            }
            
            if ($datishell->SelMeseElab != "All") {
                $sql .= " AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' ";
            }

            if ($datishell->SelInDate != statoshell_dati::ALL_DAY) {
                if ($datishell->SelInDate != statoshell_dati::LAST_3_DAYS) {

                    if ($datishell->SelInDate == statoshell_dati::LAST_DAYS) {
                        $sql .= " AND ( 
                           EXTRACT(DAY FROM START_TIME) = ( 
                           SELECT  DAY(MAX(START_TIME))  FROM WORK_CORE.CORE_SH WHERE $WhereCon 
                                AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' 
								)								
                           OR ( STATUS = 'I'  AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab'  )
                        )
						";
                    } else {
                        $sql .= "
                                AND EXTRACT(DAY FROM START_TIME) = $datishell->SelInDate
                              ";
                    }
                } else {
                    $sql .= "
                                AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon 
                                AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' )
                              ";
                }
            }

        $SelShTarget = $datishell->SelShTarget;
        if ("${SelShTarget}" != "") {
            $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.V_CORE_PROCESSING WHERE ID_RUN_SH = $datishell->SelShTarget )";
        } else {
 
            $ListaAmbito = $_POST['SelAmbito'];
            $stray = implode(',', array_map(function ($item) {
                return "'" . $item . "'";
            }, $ListaAmbito));
            //  $this->_db->error_message('stray',$stray);
            if (trim($stray)) {
                $sql .=  "AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE  SUBSTR(SHELL_PATH, INSTR(SHELL_PATH, '/', -1) + 1) IN ( $stray ))  ";
            } else {
                $sql .= " AND  ID_SH != NVL(( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'Loader.sh' ),0) ";
                $sql .= " AND  ID_SH != NVL(( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ),0) ";
            }            
        }

            
            $SelShell = $datishell->SelShell;
            $SelRootShell = $datishell->SelRootShell ? $datishell->SelRootShell : $SelShell;
            if ($datishell->SelShell != "") {
                if ("${SelShell}" != "") {
                    $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH 
                            WHERE ID_RUN_SH IN ( $datishell->SelShell, $SelRootShell) )";
                }
                if ($datishell->NoTags != "") {
                    $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $SelRootShell )";
                } else {
                    $sql .= " AND ID_SH||TAGS IN ( SELECT ID_SH||TAGS FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $SelRootShell)";
                }
            }
                  
           $sql .= "
         ORDER BY START_TIME DESC
         ) A
         WHERE A.NM <=   $datishell->NumLast
         )
         ";    
        }
        $PrecCond = "1=1";
        if ($datishell->SelLastMeseElab == "0") {
            $PrecCond = "1=2";
        }
        $sql .= "
              )
              ,W_PREC_CORE_PROCESSING as (
                SELECT 
                    *
                    ,  NVL(RTRIM(c.TAGS),'-')               TAGS_TRIM
                    ,  NVL(RTRIM(c.F_TAGS),'-')           F_TAGS_TRIM
                    ,  NVL(RTRIM(c.VARIABLES),'-')     VARIABLES_TRIM
                    ,  NVL(RTRIM(c.F_VARIABLES),'-') F_VARIABLES_TRIM
                FROM 
                    WORK_CORE.V_CORE_PROCESSING c 
                WHERE $PrecCond
                AND ID_RUN_SH IN (
                    SELECT ID_RUN_SH FROM WORK_CORE.CORE_SH 
                    WHERE 1=1
                    AND TO_CHAR(START_TIME,'YYYYMM') = '$datishell->SelLastMeseElab'     
                    AND STATUS IN ('F','W')
                    AND ID_SH IN ( SELECT DISTINCT ID_SH FROM W_LAST_CORE_PROCESSING  )
                ";

           if ($datishell->IDSELEM != "" or $datishell->INPASSO != "") {
               if ($datishell->IDSELEM != "") {
                   $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $datishell->IDSELEM )";
               } else {
                   $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $datishell->INPASSO )";
               }
           }
           
           $SelShTarget = $datishell->SelShTarget;
           if ("${SelShTarget}" != "") {
               $sql .= " AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $datishell->SelShTarget )";
           } else {
 
               $ListaAmbito = $_POST['SelAmbito'];
               $stray = implode(',', array_map(function ($item) {
                   return "'" . $item . "'";
               }, $ListaAmbito));
               //  $this->_db->error_message('stray',$stray);
               if (trim($stray)) {
                   $sql .=  "AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE  SUBSTR(SHELL_PATH, INSTR(SHELL_PATH, '/', -1) + 1) IN ( $stray ))  ";
               } else {
                   $sql .= " AND  ID_SH !=  NVL(( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'Loader.sh' ) ,0) ";
                   $sql .= " AND  ID_SH != NVL(( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ),0) ";
               }    
          } 
          $sql .= ")
              )
              SELECT 
            ID_SH, ID_RUN_SH, NAME,  
            TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS') START_TIME,
            TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS') END_TIME ,
            ID_RUN_SH_FATHER, LOG, STATUS, 
            NVL((SELECT T.NOMINATIVO FROM WEB.TAS_UTENTI T WHERE T.USERNAME = UPPER(b.USERNAME)),USERNAME) USERNAME,
            DEBUG_SH, DEBUG_DB, MAIL, ESER_ESAME, MESE_ESAME, ESER_MESE, SHELL_PATH,
            timestampdiff(2,NVL(b.END_TIME,CURRENT_TIMESTAMP)-b.START_TIME) SH_SEC_DIFF,
            RC, MESSAGE, RTRIM(VARIABLES)  VARIABLES, TAGS,
            F_ID_SH, F_ID_RUN_SH, F_NAME,  
            TO_CHAR(F_START_TIME,'YYYY-MM-DD HH24:MI:SS') F_START_TIME,
            TO_CHAR(F_END_TIME,'YYYY-MM-DD HH24:MI:SS') F_END_TIME ,
            F_ID_RUN_SH_FATHER, F_LOG, F_STATUS, 
            NVL((SELECT T.NOMINATIVO FROM WEB.TAS_UTENTI T WHERE T.USERNAME = UPPER(F_USERNAME)),F_USERNAME) F_USERNAME,
            F_DEBUG_SH, F_DEBUG_DB, F_MAIL, F_ESER_ESAME, F_MESE_ESAME, F_ESER_MESE, F_SHELL_PATH,
            timestampdiff(2,NVL(b.F_END_TIME,CURRENT_TIMESTAMP)-b.F_START_TIME) F_SH_SEC_DIFF,
            F_RC, F_MESSAGE,
            ID_RUN_SQL, TYPE_RUN, REPLACE(STEP,'- '||TAGS,'') STEP, FILE_SQL, FILE_IN,
            TO_CHAR(SQL_START,'YYYY-MM-DD HH24:MI:SS') SQL_START,
            TO_CHAR(SQL_END,'YYYY-MM-DD HH24:MI:SS') SQL_END,
            SQL_STATUS, RTRIM(F_VARIABLES)  F_VARIABLES, RTRIM(F_TAGS) F_TAGS,
            timestampdiff(2,NVL(b.SQL_END,CURRENT_TIMESTAMP)-b.SQL_START) SQL_SEC_DIFF,
            ( SELECT COUNT(1) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = b.ID_RUN_SQL ) USE_ROUTINE,
            (SELECT count(*) FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH_FATHER = b.ID_RUN_SH  ) N_SON,
            ( SELECT 
               (select count(*) FROM WORK_CORE.CORE_DB   WHERE ID_RUN_SH        = b.ID_RUN_SH) +
               (select count(*) FROM WORK_CORE.CORE_STEP WHERE ID_RUN_SH        = b.ID_RUN_SH) +
               (SELECT count(*) FROM WORK_CORE.CORE_SH   WHERE ID_RUN_SH_FATHER = b.ID_RUN_SH)
               FROM DUAL
            ) CNTPASS
            ,(SELECT timestampdiff(2,MAX(d.END_TIME)-MAX(d.START_TIME))     FROM W_PREC_CORE_PROCESSING d WHERE $LastShWhereCon )  LASTSH_SEC_DIFF
            ,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) LASTSQL_SEC_DIFF 
            ,TO_CHAR(( select ADD_SECONDS(b.SQL_START,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SQL_END 
            ,(SELECT timestampdiff(2,MAX(d.F_END_TIME)-MAX(d.F_START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastFShWhereCon ) F_LASTSH_SEC_DIFF 
            ,TO_CHAR(( select ADD_SECONDS(b.START_TIME,( SELECT timestampdiff(2,MAX(d.END_TIME)-MAX(d.START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SH_END
            ,TO_CHAR(( select ADD_SECONDS(b.F_START_TIME,( SELECT timestampdiff(2,MAX(d.F_END_TIME)-MAX(d.F_START_TIME)) FROM W_PREC_CORE_PROCESSING d WHERE $LastFShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  F_PREVIEW_SH_END
            ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH,FILE) IN (SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = b.ID_SH ) ) SH_TAB_TOUCH
            ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH,FILE) IN (SELECT SHELL_PATH, SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = b.F_ID_SH ) ) FSH_TAB_TOUCH
            ,(SELECT count(*) FROM WORK_RULES.TABLES_IN_FILE WHERE (PATH||'/'||FILE) IN (SELECT FILE FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = b.ID_RUN_SQL ) ) SQL_TAB_TOUCH       
            FROM W_LAST_CORE_PROCESSING b
            ORDER BY ID_RUN_SH DESC, TIME_ORDER DESC";

        //echo $sql;
        $res = $this->_db->getArrayByQuery($sql, array());
       //$this->_db->printSql();
        return $res;
    }


    /**
     * getSelEserMese
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelEserMese($datishell)
    {
        $WhereCon = "1=1 ";

        $sql = "SELECT DISTINCT ESER_MESE FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' AND ID_RUN_SH_FATHER IS NULL ";
        if ($datishell->SelInDate != statoshell_dati::ALL_DAY) {
            if ($datishell->SelInDate != statoshell_dati::LAST_3_DAYS) {
                if ($datishell->SelInDate == statoshell_dati::LAST_DAYS) {
                    $sql .= " AND ( 
                       EXTRACT(DAY FROM START_TIME) = ( 
                       SELECT  DAY(MAX(START_TIME))  FROM WORK_CORE.CORE_SH WHERE $WhereCon 
                            AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' 
                    		)								
                       OR STATUS = 'I' 
                    )
                    ";
                } else {
                    $sql .= "
                           AND EXTRACT(DAY FROM START_TIME) = $datishell->SelInDate
                         ";
                }
            } else {
                $sql .= "
                         AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon 
                         AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' )
                       ";
            }
        }
        $sql .= " ORDER BY ESER_MESE DESC";
        $res = $this->_db->getArrayByQuery($sql, array());

        return $res;
    }


    /**
     * getSelShellFather
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelShellFather(&$datishell)
    {

        $sql = "SELECT A.ID_SH ID_SH, SHELL_PATH, SHELL, TAGS, MAX(ID_RUN_SH) ID_RUN_SH, MAX(ID_RUN_SH_ROOT) ID_RUN_SH_ROOT
            FROM WORK_CORE.CORE_SH_ANAG  A
            JOIN
            WORK_CORE.CORE_SH S
            ON A.ID_SH = S.ID_SH
            WHERE 1=1
            AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' 
            AND ID_RUN_SH_ROOT != 0
            AND ID_RUN_SH_FATHER IS NULL
            GROUP BY A.ID_SH, SHELL_PATH, SHELL, TAGS
            ORDER BY SHELL";
        $res = $this->_db->getArrayByQuery($sql, array());
        foreach ($res as $row) {
            $SID_SH = $row['ID_SH'];
            $SSHELL = $row['SHELL'];
            $STAGS = $row['TAGS'];
            $SSHELLPATH = $row['SHELL_PATH'];
            $SID_RUN_SH = $row['ID_RUN_SH'];
            $IdRunShRoot = $row['ID_RUN_SH_ROOT'];
            if ($datishell->SelShell == $SID_RUN_SH) {
                $datishell->SelRootShell = $IdRunShRoot;
            }
        }
        //$this->_db->printSql();
        return $res;
    }



    /**
     * getSelShellSons
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getSelShellSons(&$datishell)
    {

        $sql = "SELECT A.ID_SH ID_SH, SHELL_PATH, SHELL, TAGS, MAX(ID_RUN_SH) ID_RUN_SH, MAX(ID_RUN_SH_ROOT) ID_RUN_SH_ROOT
    FROM WORK_CORE.CORE_SH_ANAG  A
    JOIN
    WORK_CORE.CORE_SH S
    ON A.ID_SH = S.ID_SH
    WHERE 1=1
    AND TO_CHAR(START_TIME,'YYYYMM') like '$datishell->SelMeseElab' 
    AND ID_RUN_SH_ROOT != 0 
    AND ID_RUN_SH_FATHER IS NOT NULL
    GROUP BY A.ID_SH, SHELL_PATH, SHELL, TAGS
    ORDER BY SHELL, TAGS";
        $res = $this->_db->getArrayByQuery($sql, array());
        foreach ($res as $row) {
            $SID_SH = $row['ID_SH'];
            $SSHELL = $row['SHELL'];
            $STAGS = $row['TAGS'];
            $SSHELLPATH = $row['SHELL_PATH'];
            $SID_RUN_SH = $row['ID_RUN_SH'];
            $IdRunShRoot = $row['ID_RUN_SH_ROOT'];
            if ($datishell->SelShell == $SID_RUN_SH) {
                $datishell->SelRootShell = $IdRunShRoot;
            }
            //if ( $datishell->SelShell == $SID_RUN_SH ){ $datishell->SelRootShell=$IdRunShRoot; }
        }
        return $res;
    }

    /**
     * getLastRun
     *
     * @param  mixed $datishell
     * @return void
     */
    public function getLastRun($datishell)
    {
        try {
            $sql = "
                      SELECT 
                    TO_CHAR(START_TIME,'YYYYMM') MESEELAB, 
                    TO_CHAR(START_TIME,'DD') ISDAY, 
                    ID_PROCESS, 
                    NAME,
                    TAGS,   
                    ESER_ESAME, 
                    MESE_ESAME, 
                    TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS') START_TIME, 
                    (
                    SELECT TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS') FROM 
                            WORK_CORE.CORE_SH 
                        WHERE 
                            START_TIME = A.START_TIME
                            AND NAME = A.NAME
                    )   
                    END_TIME, 
                    NVL((SELECT T.NOMINATIVO FROM WEB.TAS_UTENTI T WHERE T.USERNAME = UPPER(A.USERNAME)),USERNAME) USERNAME  
                FROM 
                    (   SELECT 
                            ID_PROCESS, 
                            NAME,
                            RTRIM(TAGS) TAGS,           
                            ESER_ESAME, 
                            MESE_ESAME, 
                            USERNAME, 
                            MAX(START_TIME) START_TIME 
                        FROM 
                            WORK_CORE.CORE_SH 
                        WHERE 
                            START_TIME >= SYSDATE-" . date("d");
         
                    $sql .= " AND ID_RUN_SH_FATHER IS NULL ";


            $sql .= " GROUP BY 
            ID_PROCESS, 
            NAME,
            TAGS,
            ESER_ESAME, 
            MESE_ESAME, 
            USERNAME 
        ORDER BY 
            START_TIME DESC ) A 
        ORDER BY 
                START_TIME DESC";



            $res = $this->_db->getArrayByQuery($sql);

            $this->_db->db_log_debug("getListMail", "sql", $this->_db->getsqlQuery());
            $this->_db->db_log_debug("getListMail", "dati", $res);


            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }
    /**
     * getDurataSh
     *
     * @param  mixed $Step
     * @param  mixed $Mesi
     * @param  mixed $Tags
     * @param  mixed $IdSh
     * @return void
     */
    public function getDurataSh($Step, $Mesi = '', $Tags = '', $IdSh = '')
    {
        try {

            if ($Mesi == "") {
                $Mesi = 24;
            }

            $Andtags = "";
            if ($Tags != "") {
                $Andtags = " AND TAGS = '$Tags' ";
            }

            $AndIdSh = "";
            if ($IdSh != "") {
                $AndIdSh = " AND ID_SH = '$IdSh' ";
            }


            $sql = "SELECT ESER_MESE, timestampdiff(2, (SELECT END_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = b.ID_RUN_SH ) - (SELECT START_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = b.ID_RUN_SH ) ) DURATA
            FROM (
                SELECT ID_SH, ESER_MESE, MAX(ID_RUN_SH) ID_RUN_SH
                FROM WORK_CORE.CORE_SH 
                WHERE 1=1 AND NAME  = '$Step' 
                $Andtags
                $AndIdSh        
                AND ESER_MESE IS NOT NULL AND END_TIME IS NOT NULL AND STATUS IN ('F','W') 
                GROUP BY ID_SH,ESER_MESE
                ORDER BY ID_SH,ESER_MESE
            ) b";



            $res = $this->_db->getArrayByQuery($sql);
            $this->_db->printSql();
            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }
    /**
     * getListFile
     *
     * @param  mixed $InIdRunSql
     * @param  mixed $InIdSh
     * @param  mixed $InIdRunSh
     * @return void
     */
    public function getListFile($InIdRunSql, $InIdSh, $InIdRunSh)
    {
        try {
            $ListFile = '';
            if ($InIdRunSql == "") {
                $sql = "SELECT SHELL_PATH||'/'||SHELL FILE FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = $InIdSh";
                $res = $this->_db->getArrayByQuery($sql);
                foreach ($res as $row) {
                    $ListFile = "'" . $row['FILE'] . "',";
                }
                $InFile = $ListFile;
                $sql = "SELECT DISTINCT FILE_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $InIdRunSh";
                $res = $this->_db->getArrayByQuery($sql);
                foreach ($res as $row) {
                    $ListFile = $ListFile . "'" . $row['FILE_SQL'] . "',";
                }
                $ListFile = substr($ListFile, 0, -1);
            } else {
                $sql = "SELECT FILE_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = $InIdRunSql";
                $res = $this->_db->getArrayByQuery($sql);
                foreach ($res as $row) {
                    $ListFile = $row['FILE_SQL'];
                }
                $InFile = $ListFile;
                $ListFile = "'" . $ListFile . "'";
            }

            //$this->_db->printSql();
            return $ListFile;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }

    /**
     * getRelTab
     *
     * @param  mixed $InIdRunSql
     * @param  mixed $InIdSh
     * @param  mixed $InIdRunSh
     * @return void
     */
    public function getRelTab($InIdRunSql, $InIdSh, $InIdRunSh)
    {
        try {
            $ListFile = $this->getListFile($InIdRunSql, $InIdSh, $InIdRunSh);
            $sql = "SELECT DISTINCT PATH, FILE, TRIM(SCHEMA) SCHEMA, TABELLA  FROM WORK_RULES.TABLES_IN_FILE WHERE PATH||'/'||FILE IN( $ListFile ) ORDER BY 1,2";
            $res = $this->_db->getArrayByQuery($sql);
            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }

    public function getPackage($InIdRunSql, $InIdRunSh)
    {
        try {
            if ($InIdRunSql != "") {

                $sql = "SELECT  DISTINCT ROUTINESCHEMA as PACKAGE_SCHEMA, 
            ROUTINEMODULENAME as PACKAGE, 
            BSCHEMA as TAB_SCHEMA, 
            BNAME as TAB_NAME
            FROM SYSCAT.ROUTINEDEP a
            where a.btype in ('N','T')
              AND (ROUTINESCHEMA,ROUTINEMODULENAME) IN ( SELECT DISTINCT SCHEMA,PACKAGE FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $InIdRunSql  )
            order by 1, 2, 3, 4
        ";
            } else {

                $sql = "SELECT  DISTINCT ROUTINESCHEMA as PACKAGE_SCHEMA, 
            ROUTINEMODULENAME as PACKAGE, 
            BSCHEMA as TAB_SCHEMA, 
            BNAME as TAB_NAME
            FROM SYSCAT.ROUTINEDEP a
            where a.btype in ('N','T')
              AND (ROUTINESCHEMA,ROUTINEMODULENAME) IN ( SELECT DISTINCT SCHEMA,PACKAGE FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL IN
              (SELECT ID_RUN_SQL FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = $InIdRunSh )
              )
            order by 1, 2, 3, 4
        ";
            }
            $res = $this->_db->getArrayByQuery($sql);
            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }


    /**
     * getPackageSql
     *
     * @param  mixed $PkgSchema
     * @param  mixed $PkgName
     * @return void
     */
    public function getPackageSql($PkgSchema, $PkgName)
    {
        try {

            $sql = "SELECT  DISTINCT ROUTINESCHEMA as PACKAGE_SCHEMA, 
        ROUTINEMODULENAME as PACKAGE, 
        BSCHEMA as TAB_SCHEMA, 
        BNAME as TAB_NAME
        FROM SYSCAT.ROUTINEDEP a
        where a.btype in ('N','T')
          AND ROUTINESCHEMA = '$PkgSchema'
          AND ROUTINEMODULENAME = '$PkgName'  
        order by 1, 2, 3, 4";

            $res = $this->_db->getArrayByQuery($sql);
            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }


    public function getAmbiti()
    {
        try {

            $sql = "SELECT DISTINCT SUBSTR(SHELL_PATH, INSTR(SHELL_PATH, '/', -1) + 1) AMBITO FROM WORK_CORE.CORE_SH_ANAG ORDER BY 1;";

            $res = $this->_db->getArrayByQuery($sql);
            return $res;
        } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }
    }
}
