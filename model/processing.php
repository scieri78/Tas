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
            $selShell = $_datiprocessing->getSelShell();
            $selInDate = $_datiprocessing->getSelInDate();
            $selEsito = $_datiprocessing->getSelEsito();
            $selEserMese = $_datiprocessing->getSelEserMese();
            $selIdProc = $_datiprocessing->getSelIdProc();
            $selAmbito = $_datiprocessing->getSelAmbito();
            $searchLimit = (int) $_datiprocessing->getNumLast();
            if ($searchLimit <= 0) {
                $searchLimit = (int) $_datiprocessing->getLimit();
            }
            if ($searchLimit <= 0) {
                $searchLimit = 100;
            }

            $pageSize = (int) $_datiprocessing->getPaginationSize();
            if ($pageSize <= 0) {
                $pageSize = 10;
            }

            $selNumPage = (int) $_datiprocessing->getSelNumPage();
            if ($selNumPage <= 0) {
                $selNumPage = 1;
            }

            $whereSql = "FROM WORK_CORE.CORE_SH
                    WHERE 1=1
                    AND TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                    AND (? IS NULL OR ID_RUN_SH = ?)
                    AND (? IS NULL OR STATUS = ?)
                    AND (? IS NULL OR ESER_MESE = ?)
                    AND (? IS NULL OR ID_PROCESS = ?)";

            $meseFilter = $meseElab ? $meseElab : '%';
            $selEsito = $selEsito !== '' ? $selEsito : null;
            $selEserMese = $selEserMese !== '' ? $selEserMese : null;
            $selIdProc = $selIdProc !== '' ? $selIdProc : null;
            $selShell = $selShell !== '' ? $selShell : null;

            $baseParams = [
                $meseFilter,
                $selShell,
                $selShell,
                $selEsito,
                $selEsito,
                $selEserMese,
                $selEserMese,
                $selIdProc,
                $selIdProc
            ];

            $sqlCount = "SELECT COUNT(1) CNT " . $whereSql;
            $countRows = $this->_db->getArrayByQuery($sqlCount, $baseParams);
            $matchedRows = isset($countRows[0]['CNT']) ? (int) $countRows[0]['CNT'] : 0;
            $totalRows = $searchLimit > 0 ? min($matchedRows, $searchLimit) : $matchedRows;
            $totalPages = $totalRows > 0 ? (int) ceil($totalRows / $pageSize) : 1;

            if ($selNumPage > $totalPages) {
                $selNumPage = $totalPages;
                if ($selNumPage <= 0) {
                    $selNumPage = 1;
                }
            }

            $offset = ($selNumPage - 1) * $pageSize;
            $upperBound = $offset + $pageSize;
            if ($searchLimit > 0 && $upperBound > $searchLimit) {
                $upperBound = $searchLimit;
            }

            $sql = "WITH BASE AS (
                        SELECT ID_SH, ID_RUN_SH, ID_PROCESS, NAME, START_TIME, END_TIME,
                               ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, MAIL, MESE_ESAME,
                               ESER_ESAME, ESER_MESE, DEBUG_DB, DEBUG_SH, PID, VARIABLES,
                               MESSAGE, RC, TAGS, ID_RUN_SH_ROOT,
                               ROW_NUMBER() OVER (ORDER BY START_TIME DESC) AS RN
                        " . $whereSql . "
                    )
                    SELECT ID_SH, ID_RUN_SH, ID_PROCESS, NAME, START_TIME, END_TIME,
                           ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, MAIL, MESE_ESAME,
                           ESER_ESAME, ESER_MESE, DEBUG_DB, DEBUG_SH, PID, VARIABLES,
                           MESSAGE, RC, TAGS, ID_RUN_SH_ROOT
                    FROM BASE
                    WHERE (? <= 0 OR RN <= ?)
                      AND RN > ?
                      AND RN <= ?
                    ORDER BY RN";

            $params = array_merge($baseParams, [$searchLimit, $searchLimit, $offset, $upperBound]);
            $rows = $this->_db->getArrayByQuery($sql, $params);

        /*    if ($selShell !== null || !empty($selAmbito) || $selInDate !== processing_dati::ALL_DAY) {
                $rows = array_values(array_filter($rows, function ($row) use ($selShell, $selAmbito, $selInDate) {
                    if ($selShell !== null) {
                        $runSh = isset($row['ID_RUN_SH']) ? (string) $row['ID_RUN_SH'] : '';
                        $runFather = isset($row['ID_RUN_SH_FATHER']) ? (string) $row['ID_RUN_SH_FATHER'] : '';
                        $runRoot = isset($row['ID_RUN_SH_ROOT']) ? (string) $row['ID_RUN_SH_ROOT'] : '';
                        if ($runSh !== (string) $selShell && $runFather !== (string) $selShell && $runRoot !== (string) $selShell) {
                            return false;
                        }
                    }

                    if (!empty($selAmbito)) {
                        $ambito = isset($row['ID_PROCESS']) ? (string) $row['ID_PROCESS'] : '';
                        if (!in_array($ambito, $selAmbito, true)) {
                            return false;
                        }
                    }

                    if ($selInDate !== processing_dati::ALL_DAY) {
                        $start = isset($row['START_TIME']) ? strtotime($row['START_TIME']) : false;
                        if ($start !== false) {
                            $today = strtotime(date('Y-m-d 00:00:00'));
                            $diffDays = (int) floor(($today - strtotime(date('Y-m-d 00:00:00', $start))) / 86400);
                            if ($selInDate === processing_dati::LAST_DAYS && $diffDays > 0) {
                                return false;
                            }
                            if ($selInDate === processing_dati::LAST_3_DAYS && $diffDays > 2) {
                                return false;
                            }
                            if ($selInDate !== processing_dati::LAST_DAYS && $selInDate !== processing_dati::LAST_3_DAYS) {
                                $day = date('d', $start);
                                if ($day !== str_pad((string) $selInDate, 2, '0', STR_PAD_LEFT)) {
                                    return false;
                                }
                            }
                        }
                    }

                    return true;
                }));
            }*/

            return [
                'rows' => $rows,
                'page' => $selNumPage,
                'pageSize' => $pageSize,
                'totalRows' => $totalRows,
                'totalPages' => $totalPages
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getSelMeseElab()
    {
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB
                FROM WORK_CORE.CORE_SH
                ORDER BY MESEELAB DESC";
        return $this->_db->getArrayByQuery($sql, []);
    }

    public function getSelLastMeseElab($meseElab)
    {
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') <= ?
                ORDER BY MESEELAB DESC";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : date('Ym')]);
    }

    public function getSelShellFather($meseElab)
    {
        $sql = "SELECT ID_SH, NAME SHELL, TAGS, '' SHELL_PATH, ID_RUN_SH, ID_RUN_SH_ROOT
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_RUN_SH_FATHER IS NULL
                ORDER BY START_TIME DESC
                FETCH FIRST 200 ROWS ONLY";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getSelShellSons($meseElab)
    {
        $sql = "SELECT ID_SH, NAME SHELL, TAGS, '' SHELL_PATH, ID_RUN_SH, ID_RUN_SH_ROOT
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_RUN_SH_FATHER IS NOT NULL
                ORDER BY START_TIME DESC
                FETCH FIRST 200 ROWS ONLY";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getSelInDate($meseElab)
    {
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'DD') DD
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                ORDER BY DD DESC";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getSelEserMese($meseElab)
    {
        $sql = "SELECT DISTINCT ESER_MESE
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                ORDER BY ESER_MESE DESC";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getSelIdProc($meseElab)
    {
        $sql = "SELECT DISTINCT ID_PROCESS
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_PROCESS IS NOT NULL
                ORDER BY ID_PROCESS";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getDatiAmbiti($meseElab)
    {
        $sql = "SELECT DISTINCT ID_PROCESS AMBITO
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_PROCESS IS NOT NULL
                ORDER BY AMBITO";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
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
    public function getArrayShell($idRunSh, $pos = null)
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
                    and (ROWNUM = ? or ? is null)
                    ORDER BY START_TIME DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh, $pos, $pos]);
            //stanpa sql
            // $this->_db->printSql();

            return $result;     

            


        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getArraySql($idRunSh,$pos = null)
    {
        try {
            $sql = "SELECT ROWNUM POS,
                    ID_RUN_SQL, ID_RUN_SH, TYPE_RUN, STEP, FILE_SQL, FILE_IN, 
                    START_TIME, END_TIME, STATUS
                    FROM WORK_CORE.CORE_DB
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    and (ROWNUM = ? or ? is null)
                    ORDER BY START_TIME DESC";
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh, $pos, $pos]);
            //stampa sql
            // $this->_db->printSql();

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
    public function getArrayStep($idRunSh, $pos = null)
    {
        try {
            $sql = 'SELECT ROWNUM POS,
                    ID_RUN_SH, STEP, "TIME"
                    FROM WORK_CORE.CORE_STEP
                    WHERE 1=1
                    AND ID_RUN_SH = ?
                    and (ROWNUM = ? or ? is null)
                    ORDER BY "TIME" DESC';
            //trasforma il return in array associativo con chiave ID_RUN_SH
            $result = $this->_db->getArrayByQuery($sql, [$idRunSh, $pos, $pos]);
            //stampa sql
            // $this->_db->printSql();

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
            // $this->_db->printSql();

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
