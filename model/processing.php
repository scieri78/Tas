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

            $ambitoFilter = '';
            $ambitoParams = [];
            if (!empty($selAmbito)) {
                $placeholders = implode(', ', array_fill(0, count($selAmbito), '?'));
                $ambitoFilter = "AND (
                        SELECT SUBSTR(a.SHELL_PATH, INSTR(a.SHELL_PATH, '/', -1) + 1)
                        FROM WORK_CORE.CORE_SH_ANAG a
                        WHERE a.ID_SH = s.ID_SH
                        FETCH FIRST 1 ROW ONLY
                    ) IN ($placeholders)";
                $ambitoParams = array_values($selAmbito);
            }

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

            $allDayNum = processing_dati::ALL_DAY_NUM;
            $lastDaysNum = processing_dati::LAST_DAYS_NUM;
            $last3DaysNum = processing_dati::LAST_3_DAYS_NUM;

            $whereSql = "FROM WORK_CORE.CORE_SH s
                    WHERE 1=1
                    AND TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                    AND (? IS NULL OR s.ID_SH = ?)
                    AND (? IS NULL OR STATUS = ?)
                    AND (? IS NULL OR ESER_MESE = ?)
                    AND (? IS NULL OR ID_PROCESS = ?)
                    AND (
                    ? IN ('ALL_DAY', '" . $allDayNum . "')
                    OR (? IN ('LAST_DAYS', '" . $lastDaysNum . "') AND DATE(s.START_TIME) = CURRENT DATE)
                    OR (? IN ('LAST_3_DAYS', '" . $last3DaysNum . "') AND DATE(s.START_TIME) >= (CURRENT DATE - 2 DAYS))
                    OR (? NOT IN ('ALL_DAY', 'LAST_DAYS', 'LAST_3_DAYS', '" . $allDayNum . "', '" . $lastDaysNum . "', '" . $last3DaysNum . "') AND TO_CHAR(s.START_TIME, 'DD') = LPAD(?, 2, '0'))
                    )" . $ambitoFilter;

            $meseFilter = $meseElab ? $meseElab : '%';
            $selInDate = $selInDate ? (string) $selInDate : processing_dati::LAST_DAYS;
            $allowedSelInDate = [
                processing_dati::ALL_DAY,
                processing_dati::LAST_DAYS,
                processing_dati::LAST_3_DAYS,
                processing_dati::ALL_DAY_NUM,
                processing_dati::LAST_DAYS_NUM,
                processing_dati::LAST_3_DAYS_NUM
            ];
            if (!in_array($selInDate, $allowedSelInDate, true)) {
                if (!ctype_digit($selInDate)) {
                    $selInDate = processing_dati::LAST_DAYS;
                } else {
                    $dayInt = (int) $selInDate;
                    if ($dayInt < 1 || $dayInt > 31) {
                        $selInDate = processing_dati::LAST_DAYS;
                    }
                }
            }

            // Parametro raw (stringa originale) e parametro numerico (alias) per debug SQL piu leggibile.
            $selInDateRaw = $selInDate;
            if ($selInDate === processing_dati::LAST_DAYS) {
                $selInDateCode = processing_dati::LAST_DAYS_NUM;
            } elseif ($selInDate === processing_dati::LAST_3_DAYS) {
                $selInDateCode = processing_dati::LAST_3_DAYS_NUM;
            } elseif ($selInDate === processing_dati::ALL_DAY) {
                $selInDateCode = processing_dati::ALL_DAY_NUM;
            } else {
                $selInDateCode = $selInDate;
            }

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
                $selIdProc,
                $selInDateRaw,
                $selInDateCode,
                $selInDateCode,
                $selInDateCode,
                $selInDateRaw
            ];

            if (!empty($ambitoParams)) {
                $baseParams = array_merge($baseParams, $ambitoParams);
            }

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

            $sql = "SELECT ID_SH, ID_RUN_SH, ID_PROCESS, NAME, START_TIME, END_TIME,
                                                     ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, MAIL, MESE_ESAME,
                                                     ESER_ESAME, ESER_MESE, DEBUG_DB, DEBUG_SH, PID, VARIABLES,
                                                     MESSAGE, RC, TAGS, ID_RUN_SH_ROOT, AMBITO
                                        FROM (
                                                SELECT s.ID_SH, s.ID_RUN_SH, s.ID_PROCESS, s.NAME, s.START_TIME, s.END_TIME,
                                                             s.ID_RUN_SH_FATHER, s.LOG, s.STATUS, s.USERNAME, s.MAIL, s.MESE_ESAME,
                                                             s.ESER_ESAME, s.ESER_MESE, s.DEBUG_DB, s.DEBUG_SH, s.PID, s.VARIABLES,
                                                             s.MESSAGE, s.RC, s.TAGS, s.ID_RUN_SH_ROOT,
                                                             (
                                                                     SELECT SUBSTR(a.SHELL_PATH, INSTR(a.SHELL_PATH, '/', -1) + 1)
                                                                     FROM WORK_CORE.CORE_SH_ANAG a
                                                                     WHERE a.ID_SH = s.ID_SH
                                                                     FETCH FIRST 1 ROW ONLY
                                                             ) AS AMBITO,
                                                             ROW_NUMBER() OVER (ORDER BY s.START_TIME DESC) AS RN
                                                " . $whereSql . ") BASE
                                        WHERE (? <= 0 OR RN <= ?)
                                            AND RN > ?
                                            AND RN <= ?
                                        ORDER BY RN";

            $params = array_merge($baseParams, [$searchLimit, $searchLimit, $offset, $upperBound]);
            $rows = $this->_db->getArrayByQuery($sql, $params);
            //print sql
            $this->_db->printSql();
            // Arricchisce la lista principale con OLD_TIME e METER del vecchio lancio.
            $oldRunCache = [];
            $oldTimeCache = [];
      /*      foreach ($rows as $idx => $row) {
                $rows[$idx]['OLD_TIME'] = '';
                $rows[$idx]['METER'] = '';

                $idSh = isset($row['ID_SH']) ? $row['ID_SH'] : null;
                $tags = isset($row['TAGS']) ? (string) $row['TAGS'] : '';
                if ($idSh === null || $tags === '') {
                    continue;
                }

                $cacheKey = (string) $idSh . '|' . trim($tags);
                if (!array_key_exists($cacheKey, $oldRunCache)) {
                    $oldRun = $this->getOldRunId($_datiprocessing, $idSh, $tags);
                    $oldRunCache[$cacheKey] = ($oldRun && isset($oldRun['ID_RUN_SH_OLD'])) ? (int) $oldRun['ID_RUN_SH_OLD'] : 0;
                }

                $oldRunId = $oldRunCache[$cacheKey];
                if ($oldRunId <= 0) {
                    continue;
                }

                if (!array_key_exists($oldRunId, $oldTimeCache)) {
                    $oldTimeRow = $this->getOldTimeParent($oldRunId);
                    $oldTimeCache[$oldRunId] = ($oldTimeRow && isset($oldTimeRow['OLD_TIME'])) ? (int) $oldTimeRow['OLD_TIME'] : 0;
                }

                $oldSeconds = $oldTimeCache[$oldRunId];
                if ($oldSeconds <= 0) {
                    continue;
                }

                $rows[$idx]['OLD_TIME'] = $oldSeconds;

                $startTs = isset($row['START_TIME']) ? strtotime($row['START_TIME']) : false;
                $endTs = isset($row['END_TIME']) ? strtotime($row['END_TIME']) : false;
                if ($startTs !== false && $endTs !== false && $endTs >= $startTs) {
                    $currentSeconds = $endTs - $startTs;
                    $rows[$idx]['METER'] = (string) round(($currentSeconds / $oldSeconds) * 100);
                }
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
        $sql = "SELECT DISTINCT ID_SH, NAME SHELL, TAGS
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_RUN_SH_FATHER IS NULL
                ORDER BY NAME, TAGS";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getSelShellSons($meseElab)
    {
        $sql = "SELECT DISTINCT ID_SH, NAME SHELL, TAGS
                FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                  AND ID_RUN_SH_FATHER IS NOT NULL
                ORDER BY NAME, TAGS";
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
        $sql = "SELECT c.ID_PROCESS, c.DESCR, c.TIPO, c.FLAG_STATO,
                (SELECT t.TEAM FROM WFS.TEAM t WHERE t.ID_TEAM = c.ID_TEAM) TEAM
            FROM WORK_CORE.ID_PROCESS c
            WHERE c.ID_PROCESS IN (
                SELECT DISTINCT ID_PROCESS FROM WORK_CORE.CORE_SH
                WHERE TO_CHAR(START_TIME,'YYYYMM') LIKE ?
                AND ID_PROCESS IS NOT NULL
            )
            ORDER BY c.ID_PROCESS DESC";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    public function getDatiAmbiti($meseElab)
    {
        $sql = "SELECT DISTINCT
                    SUBSTR(a.SHELL_PATH, INSTR(a.SHELL_PATH, '/', -1) + 1) AMBITO
                FROM WORK_CORE.CORE_SH_ANAG a
                JOIN WORK_CORE.CORE_SH s ON s.ID_SH = a.ID_SH
                WHERE TO_CHAR(s.START_TIME,'YYYYMM') LIKE ?
                  AND a.SHELL_PATH IS NOT NULL
                ORDER BY AMBITO";
        return $this->_db->getArrayByQuery($sql, [$meseElab ? $meseElab : '%']);
    }

    /**
     * getRunByIdRunSh - Recupera dati run padre corrente
     *
     * @param mixed $idRunSh
     * @return array|null
     */
    public function getRunByIdRunSh($idRunSh)
    {
        try {
            $sql = "SELECT ID_SH, TAGS
                    FROM WORK_CORE.CORE_SH
                    WHERE ID_RUN_SH = ?";

            $result = $this->_db->getArrayByQuery($sql, [$idRunSh]);
            return isset($result[0]) ? $result[0] : null;
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

    public function getArraySql($idRunSh, $pos = null)
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
