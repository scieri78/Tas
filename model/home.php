<?php

class home_model
{
    // set database config for mysql
    // open mysql data base
    private $_db;

    /**
     * {@inheritDoc}
     * @see db_driver::__construct()
     */
    public function __construct()
    {
        $this->_db = new db_driver();
    }


    /**
     * getTicketCounts
     *
     * @return array
     */
    public function getTicketCounts()
    {
        try {
            $USERNAME = $_SESSION['codname'];
            $tickets = [
                'assignedToMe' => [
                    'aperto' => 0,
                    'in_lavorazione' => 0,
                    'richiesta_info' => 0,
                    'test' => 0
                ],
                'openedByMe' => [
                    'aperto' => 0,
                    'in_lavorazione' => 0,
                    'richiesta_info' => 0,
                    'test' => 0
                ]
            ];

            // Query per i ticket assegnati a me
            $sqlAssigned = "SELECT tt.STATO, COUNT(*) AS COUNT FROM WORK_ELAB.TICKETS tt
                        WHERE tt.ASSEGNATO = '" . $USERNAME . "' 
                        GROUP BY tt.STATO";

            $resAssigned = $this->_db->getArrayByQuery($sqlAssigned);
            //  $this->_db->printSql();
            foreach ($resAssigned as $row) {
                $state = strtolower(str_replace(' ', '_', $row['STATO']));
                if (array_key_exists($state, $tickets['assignedToMe'])) {
                    $tickets['assignedToMe'][$state] = $row['COUNT'];
                }
            }

            // Query per i ticket aperti da me
            $sqlOpened = "SELECT tt.STATO, COUNT(*) AS COUNT FROM WORK_ELAB.TICKETS tt
                      WHERE tt.USERNAME = '" . $USERNAME . "' 
                      GROUP BY tt.STATO";

            $resOpened = $this->_db->getArrayByQuery($sqlOpened);

            foreach ($resOpened as $row) {
                $state = strtolower(str_replace(' ', '_', $row['STATO']));
                if (array_key_exists($state, $tickets['openedByMe'])) {
                    $tickets['openedByMe'][$state] = $row['COUNT'];
                }
            }
            // $this->_db->error_message("",$tickets);
            return $tickets;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getWorkflowData()
    {
        try {
            $USERNAME = $_SESSION['codname'];
            $workflowData = [];

            // Query per ottenere i workflow autorizzati per l'utente
            $sqlWorkflow = "
            SELECT DISTINCT a.ID_WORKFLOW, w.DESCR, w.WORKFLOW
            FROM WFS.AUTORIZZAZIONI a
            INNER JOIN WFS.WORKFLOW w ON w.ID_WORKFLOW = a.ID_WORKFLOW
            WHERE ID_GRUPPO IN (
                SELECT ag.ID_GRUPPO FROM WFS.ASS_GRUPPO ag
                INNER JOIN web.TAS_UTENTI tu ON tu.UK = ag.ID_UK
                WHERE tu.USERNAME = '" . $USERNAME . "'
            )
            order by w.WORKFLOW asc
            ";

            $resWorkflow = $this->_db->getArrayByQuery($sqlWorkflow);

            // Itera sui workflow autorizzati e ottieni i dati di conteggio per ciascuno
            foreach ($resWorkflow as $row) {
                $idWorkflow = $row['ID_WORKFLOW'];

                // Query per contare le righe consolidate e non consolidate
                $sqlCount = "
                SELECT 
                    COUNT(*) AS TOTALE_RIGHE,
                    SUM(CASE WHEN I.FLAG_STATO = 'A' THEN 1 ELSE 0 END) AS RIGHE_CON_FLAG_CONSOLIDATO_0,
                    SUM(CASE WHEN I.FLAG_CONSOLIDATO = 1 THEN 1 ELSE 0 END) AS RIGHE_CON_FLAG_CONSOLIDATO_1
                FROM 
                    WORK_CORE.ID_PROCESS I
                WHERE 
                    I.ID_WORKFLOW = " . $idWorkflow;



                $resCount = $this->_db->getArrayByQuery($sqlCount);
                //  $this->_db->printSql();
                $countData = $resCount[0]; // Supponendo che ci sia sempre un risultato
                //  $this->_db->error_message("",$countData);

                // Generazione dell'array con i dati ottenuti dalla query
                $workflowData[] = [
                    'name' => $row['WORKFLOW'],
                    'descr' => $row['DESCR'],
                    'id' => $row['ID_WORKFLOW'],
                    'totale' => $countData['TOTALE_RIGHE'],
                    'consolidati' => $countData['RIGHE_CON_FLAG_CONSOLIDATO_1'],
                    'aperti' => $countData['RIGHE_CON_FLAG_CONSOLIDATO_0']
                ];
            }

            //  

            return $workflowData;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getWorkflowFlussi($id_workflow, $flag_consolidato)
    {
        try {
            $workflowFlussi = [];

            // Esegui la query per ottenere i dati dal database
            $squery = ($flag_consolidato) ? " and I.FLAG_CONSOLIDATO  = 1" : " and I.FLAG_STATO = 'A'";
            $sql = "
            SELECT 
                I.ESER_ESAME || LPAD(I.MESE_ESAME, 3, 0) AS PERIODO,
                I.ID_PROCESS,
                I.DESCR,
                I.ESER_ESAME,
                I.MESE_ESAME,
                I.ESER_MESE,
                I.FLAG_CONSOLIDATO,
                I.TIPO,
                I.READONLY,
                I.FLAG_STATO,
                I.ID_TEAM,
                I.ID_WORKFLOW
            FROM 
                WORK_CORE.ID_PROCESS I           
            WHERE 
                I.ID_WORKFLOW   = ?
                " . $squery . "
            ORDER BY 
                I.ID_PROCESS desc";

            $res = $this->_db->getArrayByQuery($sql, [$id_workflow]);
            //   $this->_db->printSql();

            // Popola l'array $workflowFlussi con i dati ottenuti dalla query
            foreach ($res as $row) {
                $workflowFlussi[] = [
                    'name' => $row['DESCR'], // Supponendo che 'DESCR' sia il nome del workflow
                    'IdProcess' => $row['ID_PROCESS'],
                    'IdPeriod' => $row['PERIODO'],
                    'IdWorkflow' => $row['ID_WORKFLOW'],
                    'meseEsame' => $row['MESE_ESAME']
                ];
            }

            return $workflowFlussi;
        } catch (Exception $e) {
            throw $e;
        }
    }




    public function getFlussiLgami($ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso)
    {
        /* try {*/


        $SqlList = "SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP,
                        ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO
                        FROM WFS.LEGAME_FLUSSI L
                        WHERE ID_WORKFLOW = $IdWorkFlow 
                        AND DECODE(VALIDITA,null,'$ProcMeseEsame',' '||VALIDITA||' ') like '%$ProcMeseEsame%'
                        AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                        AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                    --    AND ( ID_FLU = $IdFlusso OR ( TIPO = 'F' AND ID_DIP = $IdFlusso ) )
                    AND ( ID_FLU = $IdFlusso )
              ORDER BY LIV, NOME_FLUSSO, TIPO, ID_DIP";


        $res = $this->_db->getArrayByQuery($SqlList, []);
        //$this->_db->printSql();
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }
    public function getSottoFlussi( $idWorkflow, $idProcess, $idFlusso)
    {
        $sql = "SELECT ID_DIP FROM WFS.LEGAME_FLUSSI
                    WHERE ID_WORKFLOW = ?
                      AND ID_FLU = ?
                      AND TIPO = 'F'
                      AND INZ_VALID <= (SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = ?)
                      AND FIN_VALID >= (SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = ?)
                    --   AND (VALIDITA IS NULL OR L.VALIDITA =?)
                      ";
        $sottoFlussi = $this->_db->getArrayByQuery($sql, [$idWorkflow, $idFlusso, $idProcess, $idProcess]);
        return $sottoFlussi;
    }



    public function testSottoFlussi( $ProcMeseEsame, $idWorkflow, $idProcess, $idFlusso,$countS = 0,  $countN = 0)
    {
        try {
            $sottoFlussi =  $this->getSottoFlussi($idWorkflow, $idProcess, $idFlusso);
            

            foreach ($sottoFlussi as $row) {
                $idFlu = $row['ID_DIP'];
                $stato = $this->flussoCompletato( $ProcMeseEsame,$idWorkflow, $idProcess, $idFlu);
                if ($stato === 'S') {
                    $countS++;
                } else {
                    $countN++;
                }
                // Chiamata ricorsiva per processare il sotto-flusso
                $result = $this->testSottoFlussi( $ProcMeseEsame,$idWorkflow, $idProcess, $idFlu,$countS, $countN);
             if ($result['stato'] === 'S') {
                    $countS++;
                } else {
                    $countN++;
                }
            }

            // Verifica lo stato del flusso corrente


            // Determina stato complessivo
            if ($countN === 0) {
                $oStato = 'S';
            } elseif ($countS > 0) {
                $oStato = 'C';
            } else {
                $oStato = 'N';
            }

            return ['stato' => $oStato, 'error' => 0, 'note' => null];
        } catch (Exception $e) {
            return ['stato' => 'N', 'error' => 1, 'note' => $e->getMessage()];
        }
    }

    /**
     * Funzione che verifica se un flusso è completato
     */
    private function flussoCompletato($ProcMeseEsame, $idWorkflow, $idProcess, $idFlusso)
    {
        try {
            // Conta quanti legami NON completati
            $sql = "SELECT COUNT(*) AS CNT
                    FROM (
                        SELECT NVL((SELECT ESITO FROM WFS.ULTIMO_STATO S
                                   WHERE S.ID_WORKFLOW = L.ID_WORKFLOW
                                     AND S.ID_FLU = L.ID_FLU
                                     AND S.TIPO = L.TIPO
                                     AND S.ID_DIP = L.ID_DIP                                    
                                     AND S.ID_PROCESS = ?),'N') AS ESITO
                        FROM WFS.LEGAME_FLUSSI L
                        WHERE L.ID_WORKFLOW = ?
                          AND L.ID_FLU = ?
                          AND L.TIPO != 'F'
                          AND L.OPT NOT IN ('Y','M')
                           AND L.INZ_VALID <= (SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = ?)
                           AND L.FIN_VALID >= (SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = ?)               
                           AND (L.VALIDITA IS NULL OR L.VALIDITA =?)
                    ) sub
                    WHERE ESITO NOT IN ('F','W')";
            $Acnt = $this->_db->getArrayByQuery($sql, [$idProcess, $idWorkflow, $idFlusso ,$idProcess,$idProcess,$ProcMeseEsame]);
         //   $this->_db->printSql();
            $cnt = $Acnt[0]['CNT'];

            return ($cnt == 0) ? 'S' : 'N';
        } catch (Exception $e) {
            return 'N';
        }
    }

   public function getStatusCounts()
{
    $sql = "WITH PeriodData AS (
    SELECT
        CASE
            WHEN START_TIME >= ADD_DAYS(CURRENT_TIMESTAMP, -3) THEN 'Last 3 Days'
            WHEN START_TIME >= ADD_MONTHS(CURRENT_TIMESTAMP, -6) THEN 'Last 6 Months'
            WHEN YEAR(START_TIME) = TO_CHAR(CURRENT_TIMESTAMP,'YYYY') THEN TO_CHAR(CURRENT_TIMESTAMP,'YYYY')
            WHEN YEAR(START_TIME) = TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY') THEN TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY')
            ELSE 'Other'
        END AS PERIOD,
        STATUS,
        CASE STATUS
            WHEN 'I' THEN 'In Corso'
            WHEN 'E' THEN 'Errore'
            WHEN 'W' THEN 'Warning'
            WHEN 'F' THEN 'Corretto'
            WHEN 'M' THEN 'Manual'
            ELSE 'Unknown'
        END AS STATUS_DESCRIPTION,
        COUNT(*) AS COUNT
    FROM WORK_CORE.CORE_SH
    WHERE
        START_TIME >= ADD_YEARS(CURRENT_TIMESTAMP, -1)
        AND ID_RUN_SH_FATHER IS null
    GROUP BY
        CASE
            WHEN START_TIME >= ADD_DAYS(CURRENT_TIMESTAMP, -3) THEN 'Last 3 Days'
            WHEN START_TIME >= ADD_MONTHS(CURRENT_TIMESTAMP, -6) THEN 'Last 6 Months'
            WHEN YEAR(START_TIME) = TO_CHAR(CURRENT_TIMESTAMP,'YYYY') THEN TO_CHAR(CURRENT_TIMESTAMP,'YYYY')
            WHEN YEAR(START_TIME) = TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY') THEN TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY')
            ELSE 'Other'
        END,
        STATUS
),
TotalCounts AS (
    SELECT
        CASE
            WHEN START_TIME >= ADD_DAYS(CURRENT_TIMESTAMP, -3) THEN 'Last 3 Days'
            WHEN START_TIME >= ADD_MONTHS(CURRENT_TIMESTAMP, -6) THEN 'Last 6 Months'
            WHEN YEAR(START_TIME) = TO_CHAR(CURRENT_TIMESTAMP,'YYYY') THEN TO_CHAR(CURRENT_TIMESTAMP,'YYYY')
            WHEN YEAR(START_TIME) = TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY') THEN TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY')
            ELSE 'Other'
        END AS PERIOD,
        COUNT(*) AS TOTAL_COUNT
    FROM WORK_CORE.CORE_SH
    WHERE
        START_TIME >= ADD_YEARS(CURRENT_TIMESTAMP, -1)
        AND ID_RUN_SH_FATHER IS null
    GROUP BY
        CASE
            WHEN START_TIME >= ADD_DAYS(CURRENT_TIMESTAMP, -3) THEN 'Last 3 Days'
            WHEN START_TIME >= ADD_MONTHS(CURRENT_TIMESTAMP, -6) THEN 'Last 6 Months'
            WHEN YEAR(START_TIME) = TO_CHAR(CURRENT_TIMESTAMP,'YYYY') THEN TO_CHAR(CURRENT_TIMESTAMP,'YYYY')
            WHEN YEAR(START_TIME) = TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY') THEN TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY')
            ELSE 'Other'
        END
)
SELECT
    p.PERIOD,
    p.STATUS,
    p.STATUS_DESCRIPTION,
    p.COUNT,
    ROUND((p.COUNT * 100.0 / NULLIF(t.TOTAL_COUNT, 0)), 2) AS PERCENTAGE
FROM PeriodData p
JOIN TotalCounts t ON p.PERIOD = t.PERIOD
ORDER BY
    CASE p.PERIOD
        WHEN 'Last 3 Days' THEN 1
        WHEN 'Last 6 Months' THEN 2
        WHEN TO_CHAR(CURRENT_TIMESTAMP,'YYYY') THEN 3
        WHEN TO_CHAR(ADD_YEARS(CURRENT_TIMESTAMP, -1),'YYYY') THEN 4
        ELSE 5
    END,
    CASE p.STATUS_DESCRIPTION
        WHEN 'In Corso' THEN 1
        WHEN 'Corretto' THEN 2
        WHEN 'Errore' THEN 3
        WHEN 'Warning' THEN 4
        WHEN 'Manual' THEN 5
        ELSE 6
    END; ";

    try {
        $results = $this->_db->getArrayByQuery($sql);

        // Inizializzazione dell'array associativo per i risultati
    

        return $results;
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
        return [
            'Errori' => 0,
            'Worning' => 0
        ];
    }
}
}
