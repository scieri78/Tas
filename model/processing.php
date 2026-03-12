<?php
class processing_model
{
    private $_db;
    public $name_model = 'processing_model';

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

    /**
     * getDbName
     *
     * @return mixed
     */
    public function getDbName()
    {
        return $this->_db->getdb();
    }

    /**
     * getListProcessing - Lista principale dei processing padre
     *
     * @param  mixed $_datiprocessing
     * @return array
     */
    public function getListProcessing($_datiprocessing)
    {
        try {
            $meseElab = $_datiprocessing->getMeseElab();
            $limit = $_datiprocessing->getLimit();
            
            $sql = "SELECT ID_SH, ID_RUN_SH, ID_PROCESS, NAME, START_TIME, END_TIME, 
                    ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, MAIL, MESE_ESAME, 
                    ESER_ESAME, ESER_MESE, DEBUG_DB, DEBUG_SH, PID, VARIABLES, 
                    MESSAGE, RC, TAGS, ID_RUN_SH_ROOT
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND TO_CHAR(START_TIME,'YYYYMM') = ?
                    AND ID_RUN_SH_FATHER IS NULL
                    ORDER BY START_TIME DESC
                    FETCH FIRST ? ROWS ONLY";
            
            return $this->_db->getArrayByQuery($sql, [$meseElab, $limit]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getOldRunId - Recupera l'ID del vecchio lancio padre
     *
     * @param  mixed $_datiprocessing
     * @return array
     */
    public function getOldRunId($_datiprocessing, $idSh, $tag)
    {
        try {
            $meseDiff = $_datiprocessing->getMeseDiff();
            
            $sql = "SELECT MAX(ID_RUN_SH) ID_RUN_SH_OLD
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND TO_CHAR(START_TIME,'YYYYMM') = ?
                    AND ID_SH = ?
                    AND TRIM(TAGS) = TRIM(?)
                    AND STATUS = 'F'";
            
            $result = $this->_db->getArrayByQuery($sql, [$meseDiff, $idSh, $tag]);
            return isset($result[0]) ? $result[0] : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getOldTimeParent - Ricava il tempo del vecchio lancio padre
     *
     * @param  mixed $idRunShOld
     * @return array
     */
    public function getOldTimeParent($idRunShOld)
    {
        try {
            $sql = "SELECT TIMESTAMPDIFF(2, END_TIME - START_TIME) OLD_TIME
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND ID_RUN_SH = ?";
            
            $result = $this->_db->getArrayByQuery($sql, [$idRunShOld]);
            return isset($result[0]) ? $result[0] : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getArrayShell - Array dei shell figli
     *
     * @param  mixed $idRunSh
     * @return array
     */
    public function getArrayShell($idRunSh)
    {
        try {
            $sql = "SELECT ROWNUM POS,
                    ID_SH, ID_RUN_SH, ID_PROCESS, NAME, START_TIME, END_TIME, 
                    ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, MAIL, MESE_ESAME, 
                    ESER_ESAME, ESER_MESE, DEBUG_DB, DEBUG_SH, PID, VARIABLES, 
                    MESSAGE, RC, TAGS, ID_RUN_SH_ROOT
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND ID_RUN_SH_FATHER = ?
                    ORDER BY START_TIME DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh]);
            //stanpa sql
            $this->_db->printSql();

            return $result;     

            


        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getArraySql($idRunSh)
    {
        try {
            $sql = "SELECT ROWNUM POS,
                    ID_RUN_SQL, ID_RUN_SH, TYPE_RUN, STEP, FILE_SQL, FILE_IN, 
                    START_TIME, END_TIME, STATUS
                    FROM WORK_CORE.CORE_DB
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    ORDER BY START_TIME DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh]);
            //stampa sql
            $this->_db->printSql();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getArrayStep - Array degli step
     *
     * @param  mixed $idRunSh
     * @return array
     */
    public function getArrayStep($idRunSh)
    {
        try {
            $sql = "SELECT ROWNUM POS,
                    ID_RUN_SH, STEP, \"TIME\"
                    FROM WORK_CORE.CORE_STEP
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    ORDER BY \"TIME\" DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh]);
            //stampa sql
            $this->_db->printSql();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getArrayShow($idRunSh)
    {
        try {
            $sql = "SELECT * FROM 
                    (SELECT ROWNUM POS, 
                    'ARRAY_SHELL' TIPO, START_TIME
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND ID_RUN_SH_FATHER = ?
                    ORDER BY START_TIME DESC)
                    UNION ALL
                    (SELECT ROWNUM POS, 
                    'ARRAY_SQL' TIPO, START_TIME
                    FROM WORK_CORE.CORE_DB
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    ORDER BY START_TIME DESC)
                    UNION ALL
                    (SELECT ROWNUM POS,  
                    'ARRAY_STEP' TIPO, TIME START_TIME
                    FROM WORK_CORE.CORE_STEP
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    ORDER BY TIME DESC)
                    ORDER BY START_TIME DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh, $idRunSh, $idRunSh]);
            //stampa sql
            $this->_db->printSql();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getOldTimeShellChild - Tempo del vecchio shell figlio
     *
     * @param  mixed $idRunShOld, $idSh, $tag
     * @return array
     */
    public function getOldTimeShellChild($idRunShOld, $idSh, $tag)
    {
        try {
            $sql = "SELECT TIMESTAMPDIFF(2, END_TIME - START_TIME) OLD_TIME
                    FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND ID_RUN_SH_FATHER = ?
                    AND ID_SH = ?
                    AND TRIM(TAGS) = TRIM(?)";
            
            $result = $this->_db->getArrayByQuery($sql, [$idRunShOld, $idSh, $tag]);
            return isset($result[0]) ? $result[0] : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * getOldTimeSqlChild - Tempo della vecchia query SQL figlio
     *
     * @param  mixed $idRunShOld, $step
     * @return array
     */
    public function getOldTimeSqlChild($idRunShOld, $step)
    {
        try {
            $sql = "SELECT TIMESTAMPDIFF(2, END_TIME - START_TIME) OLD_TIME
                    FROM WORK_CORE.CORE_DB
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    AND STEP = ?";
            
            $result = $this->_db->getArrayByQuery($sql, [$idRunShOld, $step]);
            return isset($result[0]) ? $result[0] : null;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>
