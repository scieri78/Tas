<?php

class workflow2_model
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
        $this->_db = new db_driver('user', "workflow2_model");
        //  $this->_db->open_db("__construct");
    }

    /**
     * getWfsStato
     *  SELEZIONA  FLAG_STATO FROM ID_PROCESS 
     *
     * @param  mixed $IdProcess
     * @return $WfsStato
     */
    public function getSetWfsStato($IdProcess, $CambiaStato)
    {
        //    try {

        if ($CambiaStato == "CambiaStato") {
            if ($IdProcess != "") {
                $SelStatoWfs = $_POST['SelStatoWfs'];
                $SqlTest = "SELECT FLAG_STATO FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess";
                $res = $this->_db->getArrayByQuery($SqlTest);
                foreach ($res as $row) {
                    $WfsStato = $row['FLAG_STATO'];
                }
                if ($SelStatoWfs != $WfsStato and $SelStatoWfs != "") {
                    switch ($SelStatoWfs) {
                        case 'A':
                            $SetRo = "N";
                            break;
                        case 'C':
                            $SetRo = "Y";
                            break;
                        case 'S':
                            $SetRo = "Y";
                            break;
                    }
                    $sql = "UPDATE WORK_CORE.ID_PROCESS SET FLAG_STATO = ?, READONLY = ? WHERE ID_PROCESS = ?";
                    $ret = $this->_db->updateDb($sql, [$SelStatoWfs, $SetRo, $IdProcess]);

                    if (!$ret) {
                        //echo "Set Status IdProcess Error " . db2_stmt_errormsg();
                    } else {
                        $WfsStato = $SelStatoWfs;
                    }
                }
            }
        }
        return $WfsStato;
        /*    } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }
    /**
     * getListaUtenti
     *
     * @return array
     */
    public function getListaUtenti()
    {
        /*     try {*/
        $SqlList = "SELECT NOMINATIVO, USERNAME FROM WEB.TAS_UTENTI";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getStatoCoda
     *
     * @param  mixed $IdProcess
     * @return void
     */
    public function getStatoCoda($IdProcess, $IdWorkFlow)
    {
        /*  try {*/
        $sql = "select
            TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS.FF') ORA
            FROM DUAL";

        $res = $this->_db->getArrayByQuery($sql, []);

        $ora = $res[0]['ORA'];

        $_SESSION['ORA'] = ($_SESSION['ORA']) ? $_SESSION['ORA'] : $ora;
        $old_ora = $_SESSION['ORA'];

        $SqlList = "SELECT DISTINCT ID_FLU 
                            FROM WFS.ULTIMO_STATO 
                            WHERE ID_PROCESS = ?
                            AND TMS_UPDATE > ?
                            UNION ALL
                            SELECT DISTINCT ID_FLU FROM WFS.CODA_RICHIESTE WHERE ID_PROCESS = ? AND TMS_INSERT > ?                          
                            ";

        $_SESSION['ORA'] = $ora;
        $array_stato = [];
        $res = $this->_db->getArrayByQuery($SqlList, [$IdProcess, $old_ora, $IdProcess, $old_ora]);
        foreach ($res as $flusso) {
            $array_stato = array_merge($array_stato, [$flusso['ID_FLU']]);
            $array_stato = $this->getFlussiCollegati($flusso['ID_FLU'], $IdWorkFlow, $array_stato);
        }
        return $array_stato;
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }




    public function getFlussiCollegati($ID_FLU, $IdWorkFlow, $list_flussi = [])
    {
        /*  try {*/
        $SqlList = "
                SELECT DISTINCT ID_FLU 
                            FROM WFS.LEGAME_FLUSSI 
                            WHERE TIPO = 'F' AND ID_DIP = ?
                            AND ID_WORKFLOW = ?
                ";

        $res = $this->_db->getArrayByQuery($SqlList, [$ID_FLU, $IdWorkFlow]);

        foreach ($res  as $v) {
            $ret =  $v['ID_FLU'];
            $list_flussi[] =  $v['ID_FLU'];
            $this->getFlussiCollegati($v['ID_FLU'], $IdWorkFlow, $list_flussi);
        }

        return $list_flussi;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getStatoFlussi
     *
     * @param  mixed $IdProcess
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlusso
     * @return void
     */
    public function getStatoFlussi($IdProcess, $IdWorkFlow, $ProcMeseEsame, $IdFlusso = '')
    {
        /* try {*/


        /*      $SqlList = "SELECT DISTINCT ID_FLU FROM WFS.CODA_STORICO WHERE ID_PROCESS = 202203101 AND TMS_INSERT >  ADD_SECONDS(CURRENT_TIMESTAMP,-6)
            UNION
        SELECT DISTINCT ID_FLU FROM WFS.CODA_RICHIESTE WHERE ID_PROCESS = 202203101 AND TMS_INSERT >  ADD_SECONDS(CURRENT_TIMESTAMP,-6)";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        foreach ($res as $flusso) {
            $array_stato[] = $flusso['ID_FLU'];
        }*/
        $SqlList = "
                    WITH W_ABILITATE AS(
                    SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND OPT NOT IN ('Y','M') AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
                    AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                    AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                    )
                    SELECT F.ID_FLU, NVL(US.ESITO,'N') AS ESITO
                    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'E' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
                    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) OK
                    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'I'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) IZ
                    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'N'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TD
                    ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO NOT IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
                    ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TTF
                    FROM WFS.FLUSSI F
                    INNER JOIN WFS.ULTIMO_STATO US ON US.ID_PROCESS  = $IdProcess
                    WHERE 1=1              
                  AND F.ID_WORKFLOW  = $IdWorkFlow";
        if ($IdFlusso) {
            $SqlList .= ' AND F.ID_FLU =' . $IdFlusso;
        }
        $SqlList .= ' ORDER by US.ID_DIP';

        $res = $this->_db->getArrayByQuery($SqlList, []);
        //$this->_db->printSql();
        $array_stato = [];
        foreach ($res as $flusso) {
            $array_stato[$flusso['ID_FLU']] = $flusso['ESITO'] . "_" . $flusso['KO'] . "_" . $flusso['OK'] . "_" . $flusso['IZ'] . "_" . $flusso['TD'] . "_" . $flusso['TT'] . "_" . $flusso['TTF'];
        }

        return $array_stato;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }





    /**
     * getLegamiFlussiF
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdProcess
     * @param  mixed $ProcMeseEsame
     * @param  mixed $IdDip
     * @return void
     */
    public function getLegamiFlussiF($IdWorkFlow, $IdProcess, $ProcMeseEsame, $IdDip)
    {
        $SqlList = "WITH W_ABILITATE AS(
                SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND OPT NOT IN ('Y','M') AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
                AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                )
                SELECT ID_FLU,FLUSSO,DESCR
                ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'E' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
                ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) OK
                ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'I'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) IZ
                ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = $IdProcess AND ID_FLU = F.ID_FLU AND ESITO = 'N'   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TD
                ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO NOT IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
                ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_FLU = F.ID_FLU AND TIPO IN ( 'F', 'W' )   AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TTF
                FROM WFS.FLUSSI F
                WHERE ID_FLU = $IdDip AND ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";

        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
    }

    /**
     * getListaLegami
     *
     * @param  mixed $Uk
     * @param  mixed $IdProcess
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlu
     * @param  mixed $ProcMeseEsame
     * @return array
     */
    public function getListaLegami($Uk, $IdProcess, $IdWorkFlow, $IdFlu, $ProcMeseEsame)
    {


        /*  try {*/
        $SqlList = "SELECT A.*
    ,CASE A.TIPO
        WHEN 'E' THEN
           timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-TO_DATE(A.OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        WHEN 'V' THEN
           timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-TO_DATE(A.OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        WHEN 'C' THEN
           timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-TO_DATE(A.OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        ELSE
          null
    END AS OLD_DIFF
    ,CASE A.TIPO
        WHEN 'E' THEN
           TO_CHAR((
             select ADD_SECONDS(
             TO_DATE(A.INIZIO,'YYYY-MM-DD HH24:MI:SS'),
             timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-A.OLD_INIZIO)
             )
             FROM DUAL
             )
            ,'YYYY-MM-DD HH24:MI:SS')
        WHEN 'V' THEN
           TO_CHAR((
             select ADD_SECONDS(
             TO_DATE(A.INIZIO,'YYYY-MM-DD HH24:MI:SS'),
             timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-A.OLD_INIZIO)
             )
             FROM DUAL
             )
            ,'YYYY-MM-DD HH24:MI:SS')
        WHEN 'C' THEN
           TO_CHAR((
             select ADD_SECONDS(
             TO_DATE(A.INIZIO,'YYYY-MM-DD HH24:MI:SS'),
             timestampdiff(2,NVL(TO_DATE(A.OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)-A.OLD_INIZIO)
             )
             FROM DUAL
             )
            ,'YYYY-MM-DD HH24:MI:SS')           
        ELSE
          null
        END AS   PREVIEWEND
    FROM
   (
        SELECT B.*
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT TO_CHAR(MAX(INIZIO),'YYYY-MM-DD HH24:MI:SS') INIZIO FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH)
                WHEN 'V' THEN
                   ( SELECT TO_CHAR(MAX(INIZIO),'YYYY-MM-DD HH24:MI:SS') INIZIO FROM WFS.ULTIMO_STATO C WHERE C.ID_WORKFLOW = B.ID_WORKFLOW AND C.TIPO = 'V' AND C.ID_FLU = B.ID_FLU AND C.ID_DIP = B.ID_DIP AND C.INIZIO < B.INIZIO )
                WHEN 'C' THEN
                   ( SELECT TO_CHAR(MAX(INIZIO),'YYYY-MM-DD HH24:MI:SS') INIZIO FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW 
                   AND AZIONE = 'C' AND ID_DIP = B.ID_DIP AND ESITO = 'F' AND ID_PROCESS < $IdProcess
                   )
                ELSE
                  null
            END AS OLD_INIZIO
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT TO_CHAR(MAX(FINE),'YYYY-MM-DD HH24:MI:SS') FINE FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH)
                WHEN 'V' THEN
                   ( SELECT TO_CHAR(MAX(FINE),'YYYY-MM-DD HH24:MI:SS') FINE FROM WFS.ULTIMO_STATO C WHERE C.ID_WORKFLOW = B.ID_WORKFLOW AND C.TIPO = 'V' AND C.ID_FLU = B.ID_FLU AND C.ID_DIP = B.ID_DIP AND C.FINE < B.FINE )
                WHEN 'C' THEN
                   ( SELECT TO_CHAR(MAX(FINE),'YYYY-MM-DD HH24:MI:SS') FINE FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW 
                   AND AZIONE = 'C' AND ID_DIP = B.ID_DIP AND ESITO = 'F' AND ID_PROCESS < $IdProcess 
                   )
                ELSE
                  null
            END AS OLD_FINE
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT DISTINCT  UTENTE FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW  AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH)
                ELSE
                  null
            END AS OLD_RUN_USER
            ,CASE B.TIPO
                WHEN 'E' THEN
                   ( SELECT DISTINCT LOG FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = B.ID_WORKFLOW  AND AZIONE = 'E' AND ID_DIP = B.ID_DIP AND ID_RUN_SH = B.OLD_ID_RUN_SH )
                ELSE
                  null
            END AS OLD_LOG
            ,CASE B.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  ( SELECT CONFERMA_DATO FROM WFS.CARICAMENTI WHERE ID_CAR = B.ID_DIP )
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT SALTA_ELAB FROM WFS.ELABORAZIONI WHERE ID_ELA = B.ID_DIP )
                WHEN 'V' THEN
                  null
            END AS CONFERMA_DATO
            , WARNING
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP WHERE ID_WORKFLOW = B.ID_WORKFLOW AND ID_FLU = B.ID_FLU
                 AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )
                 AND TIPO = B.TIPO
                 AND ID_DIP = B.ID_DIP
            ) RDONLYDIP
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI_DIP
                 WHERE ID_WORKFLOW = B.ID_WORKFLOW
                 AND ID_FLU = B.ID_FLU
                 AND TIPO = B.TIPO
                 AND ID_DIP = B.ID_DIP
            ) RDONLYFLUDIP
        FROM
        (
        SELECT L.ID_WORKFLOW,ID_LEGAME,PRIORITA,L.TIPO,L.ID_DIP,US.WARNING,L.ID_FLU,
           CASE L.TIPO
                WHEN 'F' THEN
                   ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
                WHEN 'C' THEN
                  ( SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                WHEN 'L' THEN
                  ( SELECT LINK FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                WHEN 'E' THEN
                  ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                WHEN 'V' THEN
                  ( SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
            END AS DIPENDENZA
            ,CASE L.TIPO
                WHEN 'F' THEN
                   ( SELECT DESCR FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
                WHEN 'C' THEN
                  ( SELECT DESCR FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                WHEN 'L' THEN
                  ( SELECT DESCR FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                WHEN 'E' THEN
                  ( SELECT DESCR FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                WHEN 'V' THEN
                  ( SELECT DESCR FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
            END AS DESCR
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  ( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                WHEN 'L' THEN
                  ( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                WHEN 'E' THEN
                  null
                WHEN 'V' THEN
                  null
            END AS TARGET
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  ( SELECT TIPO FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                WHEN 'E' THEN
                  null
                WHEN 'V' THEN
                  null
            END AS LINK_TIPO
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                WHEN 'V' THEN
                  null
            END AS ELAB_IDSH
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT TAGS FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                WHEN 'V' THEN
                  null
            END AS ELAB_TAGS
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT READONLY FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                WHEN 'V' THEN
                  null
            END AS READONLY
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  ( SELECT BLOCKWFS FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH IN ( SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )  )
                WHEN 'V' THEN
                  null
            END AS BLOCKWFS
            ,CASE L.TIPO
                WHEN 'F' THEN
                   null
                WHEN 'C' THEN
                  null
                WHEN 'L' THEN
                  null
                WHEN 'E' THEN
                  null
                WHEN 'V' THEN
                  ( SELECT EXTERNAL FROM WFS.VALIDAZIONI V WHERE V.ID_VAL = L.ID_DIP )
            END AS EXTERNAL
            ,US.UTENTE
            ,TO_CHAR(US.INIZIO,'YYYY-MM-DD HH24:MI:SS') INIZIO
            ,TO_CHAR(US.FINE,'YYYY-MM-DD HH24:MI:SS')   FINE
            ,timestampdiff(2,NVL(US.FINE,CURRENT_TIMESTAMP)-US.INIZIO) DIFF
            ,NVL(US.ESITO,'N') AS ESITO
            ,US.NOTE
            ,US.LOG
            ,US.FILE
            ,( SELECT count(*) FROM WFS.CODA_RICHIESTE WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS  = $IdProcess AND ID_FLU = L.ID_FLU AND ID_DIP = L.ID_DIP AND TIPO = L.TIPO ) CODA
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU
                 AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )
                 AND ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND GRUPPO = 'READ')
            ) RDONLY
            ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_FLUSSO = L.ID_FLU
                 AND ID_GRUPPO NOT IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO 
                     WHERE ID_UK = $Uk 
                     AND ID_GRUPPO NOT IN ( 
                      SELECT ID_GRUPPO FROM WFS.GRUPPI 
                      WHERE GRUPPO = 'READ' 
                      AND ID_WORKFLOW = $IdWorkFlow  )
                    )
            ) PERMESSO
            ,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = L.ID_WORKFLOW AND ID_PROCESS = $IdProcess AND READONLY = 'Y' ) WFSRDONLY
            , US.ID_RUN_SH
            ,CASE L.TIPO
                WHEN 'E' THEN
                   ( SELECT MAX(ID_RUN_SH) 
                   FROM WFS.CODA_STORICO 
                   WHERE ID_WORKFLOW = L.ID_WORKFLOW 
                   AND ID_RUN_SH < NVL(US.ID_RUN_SH,9999999) 
                   AND ID_RUN_SH IS NOT NULL
                   AND TIPO = 'E'
                   AND AZIONE = 'E' 
                   AND ID_DIP = L.ID_DIP 
                   AND ESITO IN ('F','W') 
                   AND NOTE NOT LIKE '%Forzato%'
                   AND ( 
                   ID_PROCESS IN ( SELECT ID_PROCESS FROM WORK_CORE.ID_PROCESS WHERE FLAG_CONSOLIDATO = 1 )
                   OR ID_PROCESS = $IdProcess
                   )
                   )
                ELSE
                  null
            END AS OLD_ID_RUN_SH
            ,CASE L.TIPO
                WHEN 'E' THEN
                   ( SELECT SHOWPROC FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                ELSE
                  null
            END AS SHOWPROC,
            L.OPT,
            (SELECT SHELL FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP) as SHELL
            ,( SELECT FLAG_CONSOLIDATO FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) CONS
            ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WF WHERE WF.ID_WORKFLOW = L.ID_WORKFLOW AND WF.ID_FLU = L.ID_FLU AND WF.PRIORITA = L.PRIORITA+1 ) CNTNEXTPRIO
            ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WF WHERE WF.ID_WORKFLOW = L.ID_WORKFLOW AND WF.TIPO = 'F' AND WF.ID_DIP = L.ID_FLU ) CNTNEXTDIP
          FROM
                WFS.LEGAME_FLUSSI L
          LEFT JOIN
                WFS.ULTIMO_STATO US
          ON 1=1
                AND US.ID_PROCESS  = $IdProcess
                AND L.ID_WORKFLOW  = US.ID_WORKFLOW
                AND L.ID_FLU       = US.ID_FLU
                AND L.TIPO         = US.TIPO
                AND L.ID_DIP       = US.ID_DIP
                AND L.ID_WORKFLOW  = $IdWorkFlow
                AND L.ID_FLU       = $IdFlu
           WHERE 1=1
                AND DECODE(L.VALIDITA,null,' $ProcMeseEsame ',' '||L.VALIDITA||' ') like '% $ProcMeseEsame %'
                AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND L.ID_WORKFLOW  = $IdWorkFlow
                AND L.ID_FLU       = $IdFlu
        ) b
   ) a
   ORDER BY PRIORITA, TIPO, DIPENDENZA
   ";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        //$query = $this->_db->getsqlQuery();
        //$this->_db->printSql();
        //$this->_db->error_message("query",$query);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }





    /**
     * setTipoIdprocess
     *SET TIPO FROM ID_PROCESS
     * @param  mixed $IdProcess
     * @return $WfsStato
     */
    public function setTipoIdprocess($IdProcess, $ChSens, $SelChSens, $SelStatoWfs)
    {

        /*  try {*/
        if ($ChSens == "ChSens") {
            if ($IdProcess != "") {

                $sql = "UPDATE WORK_CORE.ID_PROCESS SET TIPO = '$SelChSens' WHERE ID_PROCESS = ${IdProcess}";
                $ret = $this->_db->updateDb($sql, []);
                if (!$ret) {
                    //   echo "Set Tipo IdProcess Error " . db2_stmt_errormsg();
                } else {
                    $WfsStato = $SelStatoWfs;
                }
            }
            return $WfsStato;
        }
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * forzaScodatore
     * SET DESCR FROM ID_PROCESS
     * @param  mixed $SSHUSR
     * @param  mixed $SERVER
     * @param  mixed $DIRSH
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdProcess
     * @param  mixed $PRGDIR
     * @return void
     */
    public function forzaScodatore($ForzaScodatore, $IdWorkFlow, $IdProcess)
    {
        /*  try {*/
        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];
        $UPLOADDIR = $_SESSION['UPLOADDIR'];
        $ForzaScodatore = $_POST['ForzaScodatore'] ? $_POST['ForzaScodatore'] : $ForzaScodatore;
        if ($ForzaScodatore == 1) {
            shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 > $PRGDIR/AvviaElabServer.log &");
        }
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            //$this->_db->close_db();
            throw $e;
        }*/
    }

    public function getParametriIdProcess($IdProcess)
    {
        try {
            $Sql = "SELECT VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE AMBITO = 'Sistema' AND CAMPO = 'Scodatore' AND ID_PROCESS = ?";
            $res = $this->_db->getArrayByQuery($Sql, [$IdProcess]);
            return $res[0]['VALORE'] ? $res[0]['VALORE'] : 'off';
        } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }
    }

    //202410401    Sistema    Scodatore    Off    RU20903    2025-12-17-15.49.50.939864    
    public function stopFlusso($IdProcess)
    {
        $ambito = "Sistema";
        $campo = "Scodatore";
        $valore = $this->getParametriIdProcess($IdProcess) == 'off' ? "on" : "off";
        $this->insertPatametri($IdProcess, $ambito, $campo, $valore);
    }


    public function removeParametri($IdProcess, $ambito, $campo)
    {
        try {
            $Sql = "DELETE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ? and AMBITO = ? and CAMPO = ? ";
            $res = $this->_db->deleteDb($Sql, [$IdProcess, $ambito, $campo]);
            return $res;
        } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }
    }

	// funzione per insert parametri WFS.PARAMETRI_ID_PROCESS  INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, "USER") VALUES($IdProcess, ‘ARGO', 'ID_RULE', '$IdRule', '$User');
	//INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, "USER") VALUES($IdProcess, ‘ARGO', 'ID_RULE_RISK', '$IdRiskRule', '$User');
    /**
     * Inserts parameters for a process into the WFS.PARAMETRI_ID_PROCESS table
     * 
     * @param int $IdProcess The process identifier
     * @param string $IdRule The rule identifier
     * @param string $IdRiskRule The risk rule identifier
     * @return mixed Database insert result
     * @throws Throwable If database insertion fails
     */
    public function insertPatametri($IdProcess, $ambito, $campo, $valore)
    {
        try {
            $User = $_SESSION['codname'];
            $this->removeParametri($IdProcess, $ambito, $campo);

            $Sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER) VALUES(?, ?, ?, ?, ?)";
            $res = $this->_db->insertDb($Sql, [$IdProcess, $ambito, $campo, $valore, $User]);



            //	$res = $this->_db->insertDb($Sql, [$IdProcess, 'ARGO', 'ID_RULE', $IdRule, $User]);
            //	$this->_db->printSql();

            //	$this->_db->printSql();

            return $res;
        } catch (Throwable $e) {
            //$this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }
    }


    /**
     * setDescrIdP
     *
     * @param  mixed $IdProcess
     * @return $ret2
     */
    public function setDescrIdProcess($IdProcess, $NewDesc, $DescrIdP)
    {
        $DescrIdP = strip_tags($DescrIdP);
        /*   try {*/
        if ($NewDesc == "NewDesc") {

            $sql1 = "UPDATE WORK_CORE.ID_PROCESS SET DESCR='$DescrIdP' WHERE ID_PROCESS = $IdProcess";
            $ret1 = $this->_db->updateDb($sql1, []);


            $sql2 = "UPDATE WORK_RULES.CATALOGO_ID_PROCESS SET DESCR='$DescrIdP' WHERE ID_PROCESS = $IdProcess";
            $ret2 = $this->_db->updateDb($sql2, []);


            return $ret2;
        }
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }




    /**
     * getLoadTrascodifica
     *
     * @param  mixed $DipName
     * @return void
     */
    public function getLoadTrascodifica($DipName)
    {
        /* try {*/
        $SqlList = "SELECT TAB_TARGET TARGTAB FROM WFS.LOAD_ANAG WHERE FLUSSO = '$DipName'";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
        /*   } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }*/
    }

    public function getElaborazioni($IdDip)
    {
        /* try {*/
        $SqlList = "SELECT SHELL TARGTAB FROM WFS.ELABORAZIONI WHERE ID_ELA = '$IdDip'";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
        /* } catch (Exception $e) {
            //echo "ddd";
            throw $e;
        }*/
    }


    /**
     * Addf
     *
     * @return void
     */
    public function Addf()
    {
        /*  try {*/

        $sql = "INSERT INTO *****";


        $last_id = $this->_db->insertDb($sql, array('', '', ''));
        //  echo $this->_db->getsqlQuery();
        //  die();
        return $last_id;
        /* } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * Removef
     * Remove alert mail for a given id_sh
     * @param  mixed $id
     * @return void
     */
    public function Removef($id)
    {
        /* try {*/

        $sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ?";

        $ret = $this->_db->deleteDb($sql, array($id));
        return $ret;
        /*  } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * callf
     *
     * @return void
     */
    public function callf()
    {
        /* try {*/
        $CallPlSql = 'CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';

        $IdVal = $_POST['IdDip'];
        $IdLeg = $_POST['IdLeg'];
        $values = [];
        /*  $values = [
                [
                    'name' => 'IdWorkFlow',
                    'value' => $IdWorkFlow,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdVal',
                    'value' => $IdVal,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdLeg',
                    'value' => $IdLeg,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'User',
                    'value' => $_SESSION['codname'],
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'Errore',
                    'value' => $Errore,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Note',
                    'value' => $Note,
                    'type' => DB2_PARAM_OUT
                ]
            ];*/

        /*  db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
            db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
            db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
            db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
            db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/

        $ret = $this->_db->callDb($CallPlSql, $values);
        return $ret;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * switchAction
     * RETRIVE INFORMATIONS BASED ON ACTION
     * 
     * @return void
     */
    public function switchAction()
    {
        /* try {*/
        //  $_SESSION['error_message'] = "";
        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];

        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];
        $UPLOADDIR = $_SESSION['UPLOADDIR'];
        $UPFILEDIR = $_SESSION['UPFILEDIR'];

        foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }
      //  echo "switchAction action:". $Action;
        switch ($Action) {
            case 'CancellaCoda':
                $CallPlSql = 'CALL WFS.K_WFS.RimuoviDaCoda(?, ?, ?, ?, ?, ?, ?, ? )';

                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdFlu',
                        'value' => $IdFlu,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Tipo',
                        'value' => $Tipo,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdDip',
                        'value' => $IdDip,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $User,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ],
                ];
                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "IdFlu", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "Tipo", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "IdDip", DB2_PARAM_IN);
                // db2_bind_param($stmt, 6, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 7, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 8, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);
              //  $this->_db->printSql();
                break;

            case 'Valida':
                $CallPlSql = 'CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'OnIdLegame',
                        'value' => $OnIdLegame,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $_SESSION['codname'],
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ],
                ];
                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);
                break;

            case 'Svalida':


                $CallPlSql = 'CALL WFS.K_WFS.SvalidaLegame(?, ?, ?, ?, ?, ? )';
                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'OnIdLegame',
                        'value' => $OnIdLegame,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $User,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ]
                ];
                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);
                break;
            case 'SvalidaSuc':


                $CallPlSql = 'CALL WFS.K_WFS.SvalidaLegamiSuccessivi(?, ?, ?, ?, ?, ? )';
                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'OnIdLegame',
                        'value' => $OnIdLegame,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $User,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ]
                ];
                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);
                break;

            case 'Spegni':


                $sql = "UPDATE WFS.ULTIMO_STATO SET 
                            WARNING  = 0
                           ,UTENTE = ?
                           WHERE 1=1
                           AND ID_WORKFLOW = ?
                           AND ID_PROCESS  = ?
                           AND ID_FLU      = ?
                           AND TIPO        = ?
                           AND ID_DIP      = ?   
                           ";

                $res = $this->_db->updateDb($sql, [$User, $IdWorkFlow, $IdProcess, $SelFlusso, $SelTipo, $SelDipendenza]);

                $values = [];
                $sql = "INSERT INTO WFS.CODA_STORICO(
                          ID_PROCESS
                          ,ID_WORKFLOW
                          ,ID_FLU
                          ,TIPO
                          ,ID_DIP
                          ,AZIONE
                          ,UTENTE
                          ,NOTE
                          ,ESITO
                          ,INIZIO
                          ,FINE
                          ,LOG
                          ,FILE
                          )
                          VALUES (
                           $IdProcess
                          ,$IdWorkFlow
                          ,$SelFlusso
                          ,'$SelTipo'
                          ,$SelDipendenza
                          ,'R'
                          ,'$User'
                          ,'Reset Warning'
                          ,'N'
                          ,CURRENT_TIMESTAMP
                          ,CURRENT_TIMESTAMP
                          ,null
                          ,null
                          )   
                          ";

                $newStorico = $this->_db->insertDb($sql, []);

                break;
            case 'Elabora':
                $SqlTest = "SELECT ID_SH FROM WFS.ELABORAZIONI WHERE ID_ELA = (SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_LEGAME = $OnIdLegame )";
                $res = $this->_db->getArrayByQuery($SqlTest, []);

                foreach ($res as $row) {
                    $IdDip = $row['ID_SH'];
                }

                if ($IdDip != "0") {

                    $CallPlSql = 'CALL WFS.K_WFS.ElaboraLegame(?, ?, ?, ?, ? , ?, ?)';

                    $Force = 0;
                    $SelForce = $_POST['Force'];
                    if ($SelForce == "1") {
                        $Force = 1;
                    }

                    $values = [
                        [
                            'name' => 'IdWorkFlow',
                            'value' => $IdWorkFlow,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'IdProcess',
                            'value' => $IdProcess,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'OnIdLegame',
                            'value' => $OnIdLegame,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'Force',
                            'value' => $Force,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'User',
                            'value' => $User,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'Errore',
                            'value' => $Errore,
                            'type' => DB2_PARAM_OUT
                        ],
                        [
                            'name' => 'Note',
                            'value' => $Note,
                            'type' => DB2_PARAM_OUT
                        ],
                    ];
                    // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 4, "Force", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 5, "User", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 6, "Errore", DB2_PARAM_OUT);
                    // db2_bind_param($stmt, 7, "Note", DB2_PARAM_OUT);
                    $res = $this->_db->callDb($CallPlSql, $values);

                    $Lancia = true;

                    if ($res['Errore'] != 0) {
                        $error_message =  "PLSQL Procedure Calling ElaboraLegame Error $Errore: " . $res['Note'];
                        $this->_db->error_message("PLSQL Procedure Calling ElaboraLegame Error $Errore: " . $res['Note']);
                        $_SESSION['error_message'] .= $error_message;
                        $Lancia = false;
                    }

                    if ($Lancia) {
                        //    shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 > $PRGDIR/AvviaElabServer.log &");
                    }
                } else {

                    $CallPlSql = 'CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ? , ?)';

                    $values = [
                        [
                            'name' => 'IdWorkFlow',
                            'value' => $IdWorkFlow,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'IdProcess',
                            'value' => $IdProcess,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'OnIdLegame',
                            'value' => $OnIdLegame,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'User',
                            'value' => $User,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'Errore',
                            'value' => $Errore,
                            'type' => DB2_PARAM_OUT
                        ],
                        [
                            'name' => 'Note',
                            'value' => $Note,
                            'type' => DB2_PARAM_OUT
                        ],
                    ];
                    // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
                    // db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
                    $res = $this->_db->callDb($CallPlSql, $values);
                }
                break;

            case 'CopiaDato':
                $Copia = 'Y';

                $CallPlSql = 'CALL WFS.K_WFS.ConfermaDato(?, ?, ?, ?, ?, ?, ? )';

                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'OnIdLegame',
                        'value' => $OnIdLegame,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Copia',
                        'value' => $Copia,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $User,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ],
                ];

                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "Copia", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 6, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 7, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);

                if ($res['Errore'] != 0) {
                    // echo "PLSQL Procedure Calling Error $Errore: " . $Note;
                    $error_message = "PLSQL Procedure Calling ConfermaDato Error $Errore: " . $res['Note'];
                    $this->_db->error_message($error_message);
                    $_SESSION['error_message'] .= $error_message;
                    $Lancia = false;
                }

                break;
            case 'ConfermaDato':
                $Copia = 'N';

                $CallPlSql = 'CALL WFS.K_WFS.ConfermaDato(?, ?, ?, ?, ?, ?, ? )';

                $values = [
                    [
                        'name' => 'IdWorkFlow',
                        'value' => $IdWorkFlow,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'IdProcess',
                        'value' => $IdProcess,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'OnIdLegame',
                        'value' => $OnIdLegame,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Copia',
                        'value' => $Copia,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'User',
                        'value' => $User,
                        'type' => DB2_PARAM_IN
                    ],
                    [
                        'name' => 'Errore',
                        'value' => $Errore,
                        'type' => DB2_PARAM_OUT
                    ],
                    [
                        'name' => 'Note',
                        'value' => $Note,
                        'type' => DB2_PARAM_OUT
                    ],
                ];
                // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                // db2_bind_param($stmt, 4, "Copia", DB2_PARAM_IN);
                // db2_bind_param($stmt, 5, "User", DB2_PARAM_IN);
                // db2_bind_param($stmt, 6, "Errore", DB2_PARAM_OUT);
                // db2_bind_param($stmt, 7, "Note", DB2_PARAM_OUT);
                $res = $this->_db->callDb($CallPlSql, $values);

                if ($res['Errore'] != 0) {
                    // echo "PLSQL Procedure Calling Error $Errore: " . $Note;
                    $error_message = "PLSQL Procedure Calling ConfermaDato Error $Errore: " . $res['Note'];
                    $this->_db->error_message($error_message);
                    $_SESSION['error_message'] .= $error_message;
                    $Lancia = false;
                }

                break;
            case 'Carica':
                $SqlTest = "SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = (SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_LEGAME = $OnIdLegame )";
                $res = $this->_db->getArrayByQuery($SqlTest, []);
                //$this->_db->error_message("_FILES",$_FILES);
                foreach ($res as $row) {
                    $NomeInput = $row['NOME_INPUT'];
                }

                if ($NomeInput != "WFS_TEST") {
                    //  $this->_db->error_message("OnIdLegame:",$OnIdLegame);
                    if ($_FILES['UploadFileName_' . $OnIdLegame]['name'] != '') {

                        $file_name = str_replace('(', '', $_FILES['UploadFileName_' . $OnIdLegame]['name']);
                        $file_name = str_replace(')', '', $file_name);
                        $file_name = str_replace(' ', '_', $file_name);
                        $file_size = $_FILES['UploadFileName_' . $OnIdLegame]['size'];
                        $file_tmp  = $_FILES['UploadFileName_' . $OnIdLegame]['tmp_name'];
                        $file_type = $_FILES['UploadFileName_' . $OnIdLegame]['type'];
                        $file_ext  = strtolower(end(explode('.', $_FILES['UploadFileName_' . $OnIdLegame]['name'])));

                        $expensions = ["xls", "xlsx", "csv", "txt", "zip", "gzip"];

                        $mineValidi = [
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
                            'application/vnd.ms-excel', // xls
                            'text/plain', // txt
                            'text/csv', // csv
                            'application/zip', // zip
                            'application/gzip' // gzip
                        ];

                        $ErroreUp = 0;

                        $mine = mime_content_type($file_tmp);

                        if (in_array($mine, $mineValidi) === false) {
                            //$Note = "Il File non e' conforme al formato richiesto: xls,xlsx,csv";
                            $Note = "Tipo File non valido:" . $mine;
                            //$this->_db->error_message("Errore:",$Note);
                            $ErroreUp = 1;
                        }
                        if (in_array($file_ext, $expensions) === false) {
                            //$Note = "Il File non e' conforme al formato richiesto: xls,xlsx,csv";
                            $Note .= $ErroreUp ? "<br/>" : "";
                            $Note .= "Estensione File Errata:" . $file_ext;
                            //$this->_db->error_message("Errore:",$Note);
                            $ErroreUp = 1;
                        }

                        if ($file_size > 62428800) {
                            $Note .= $ErroreUp ? "<br/>" : "";
                            $Note .= 'Il File eccede dai 60 MB massimi di caricamento';
                            //  $this->_db->error_message("Errore:",$Note);
                            $ErroreUp = 1;
                        }

                        if ($ErroreUp == 0) {
                            shell_exec('find ' . ${UPLOADDIR} . '/* -mtime +30 |xargs rm');
                            $FileRename = "$UPLOADDIR/" . $IdProcess . "_" . $NomeInput . "." . $file_ext;
                            //$this->_db->error_message("FileRename",$FileRename);
                            //  $this->_db->error_message("_POST",$_POST);

                            //$this->_db->error_message("FileRename:",$FileRename);
                            $moved = move_uploaded_file($file_tmp, $FileRename);

                            if ($moved) {
                                $Prosegui = true;
                                if ($MetSCopy == "scp" || 1) {
                                    $command = 'scp ' . $FileRename . ' ' . ${SSHUSR} . '@' . ${SERVER} . ':' . ${UPFILEDIR} . '/ ';
                                } else {
                                    $command = 'echo';
                                }
                                //$this->_db->error_message("command",$command);
                                //$this->_db->error_message("command:",$command);
                                $out = shell_exec($command);
                                if (empty($out)) {
                                    $Prosegui = true;
                                } else {
                                    $Prosegui = false;
                                }
                                if ($Prosegui) {

                                    $CallPlSql = 'CALL WFS.K_WFS.CaricaLegame(?, ?, ?, ?, ?, ?, ?, ? )';

                                    $Force = 0;
                                    $SelForce = $_POST['Force'];
                                    if ($SelForce == "1") {
                                        $Force = 1;
                                    }

                                    $values = [
                                        [
                                            'name' => 'IdWorkFlow',
                                            'value' => $IdWorkFlow,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'IdProcess',
                                            'value' => $IdProcess,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'OnIdLegame',
                                            'value' => $OnIdLegame,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'file_name',
                                            'value' => $file_name,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'Force',
                                            'value' => $Force,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'User',
                                            'value' => $User,
                                            'type' => DB2_PARAM_IN
                                        ],
                                        [
                                            'name' => 'Errore',
                                            'value' => $Errore,
                                            'type' => DB2_PARAM_OUT
                                        ],
                                        [
                                            'name' => 'Note',
                                            'value' => $Note,
                                            'type' => DB2_PARAM_OUT
                                        ],
                                    ];
                                    // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 4, "file_name", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 5, "Force", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 6, "User", DB2_PARAM_IN);
                                    // db2_bind_param($stmt, 7, "Errore", DB2_PARAM_OUT);
                                    // db2_bind_param($stmt, 8, "Note", DB2_PARAM_OUT);
                                    $res = $this->_db->callDb($CallPlSql, $values);
                                    //$this->_db->getsqlQuery();
                                    $Lancia = true;

                                    if ($res['Errore'] != 0) {
                                        $error_message = "PLSQL Procedure Calling CaricaLegame Error $Errore: " . $res['Note'];
                                        $this->_db->error_message("PLSQL Procedure Calling CaricaLegame Error $Errore: " . $res['Note']);
                                        $_SESSION['error_message'] .= $error_message;
                                        //  echo "PLSQL Procedure Calling Error $Errore: " . $res['Note'];
                                        $Lancia = false;
                                    }
                                    if ($Lancia) {
                                        //    $shComm = "sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &";
                                        //   shell_exec($shComm);
                                        //  $this->_db->error_message("shComm:",$shComm);
                                    }
                                } else {

                                    $Note = "Error upload file to Aix";
                                    //echo $Note;
                                    $error_message = "Errore:" . $Note;
                                    $this->_db->error_message($error_message);
                                    $_SESSION['error_message'] .= $error_message;
                                }
                            } else {
                                $Note = "Error upload file to WebServer";
                                $error_message = "Errore:" . $Note;
                                $this->_db->error_message($error_message);
                                $_SESSION['error_message'] .= $error_message;
                            }
                        } else {

                            //$Note = "Errore nel caricamento del File:";
                            $error_message = "Errore:" . $Note;
                            $this->_db->error_message($error_message);
                            //$this->_db->error_message("Errore:".$Note."<br>File",$_FILES);
                            $_SESSION['error_message'] .= $error_message;
                        }
                    } else {
                        $error_message = "Errore File non caricato!";
                        $this->_db->error_message($error_message);
                        $_SESSION['error_message'] .= $error_message;
                        //  $this->_db->error_message("Errore File <br>File",$_FILES);
                    }
                } else {

                    $CallPlSql = 'CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ? , ?)';
                    $values = [
                        [
                            'name' => 'IdWorkFlow',
                            'value' => $IdWorkFlow,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'IdProcess',
                            'value' => $IdProcess,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'OnIdLegame',
                            'value' => $OnIdLegame,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'User',
                            'value' => $User,
                            'type' => DB2_PARAM_IN
                        ],
                        [
                            'name' => 'Errore',
                            'value' => $Errore,
                            'type' => DB2_PARAM_OUT
                        ],
                        [
                            'name' => 'Note',
                            'value' => $Note,
                            'type' => DB2_PARAM_OUT
                        ],
                    ];
                    // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 3, "OnIdLegame", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 4, "User", DB2_PARAM_IN);
                    // db2_bind_param($stmt, 5, "Errore", DB2_PARAM_OUT);
                    // db2_bind_param($stmt, 6, "Note", DB2_PARAM_OUT);
                    $res = $this->_db->callDb($CallPlSql, $values);
                }
                break;
        }
        return $Lancia;
        /*     } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }



    /**
     * callElaborazioniPossibili
     * CALLS DB AND EXECUTE AvviaElabServer
     * 
     * @return void
     */
    public function callElaborazioniPossibili($Action, $IdProcess, $IdWorkFlow)
    {
        /*  try {*/

        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];

        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];
        $InCoda = 0;

        if ($IdProcess != "" and $IdWorkFlow != "") {
            $CallPlSql = 'CALL WFS.K_WFS.ElaborazioniPossibili(?, ?, ?, ?, ?, ?)';
            $values = [
                [
                    'name' => 'IdWorkFlow',
                    'value' => $IdWorkFlow,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdProcess',
                    'value' => $IdProcess,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'User',
                    'value' => $User,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'InCoda',
                    'value' => $InCoda,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Errore',
                    'value' => $Errore,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Note',
                    'value' => $Note,
                    'type' => DB2_PARAM_OUT
                ],
            ];
            // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
            // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
            // db2_bind_param($stmt, 3, "User", DB2_PARAM_IN);
            // db2_bind_param($stmt, 4, "Errore", DB2_PARAM_OUT);
            // db2_bind_param($stmt, 5, "Note", DB2_PARAM_OUT);
            $res = $this->_db->callDb($CallPlSql, $values);

            $ErrSt = 0;

            if ($res['Errore'] != 0) {
                $this->_db->error_message("PLSQL Procedure Calling ElaborazioniPossibili Error $Errore: " . $res['Note']);
                //   echo "PLSQL Procedure Calling Error $Errore: " . $Note;
                $ErrSt = 1;
            }
            if (($ErrSt == 0 and $res['InCoda'] != 0)) {
                shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &");
            }
        }
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * getIdGruppo
     * Retrieves the group IDs associated with a specific user.
     *
     * @param int $IdUser The ID of the user for whom to retrieve group IDs.
     * @return array An array of group IDs associated with the user.
     */
    public function getIdGruppo($IdUser)
    {
        /*   try {*/
        $ArrListGroup = array();
        $ListIdGroups = "0";
        $SqlListGroup = "SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = ?;";
        $res = $this->_db->getArrayByQuery($SqlListGroup, [$IdUser]);
        //$this->_db->printSql();
        //$this->_db->error_message("getIdGruppo res",$res);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getAuthWfs
     * get enabled workflow 
     * @param  mixed $ListIdGroups
     * @return array An array of group IDs associated with the user.
     */
    public function getAutorizzazioniWorkflow($ListIdGroups)
    {
        /*  try {*/
        $SqlList = "SELECT DISTINCT W.ID_WORKFLOW,WORKFLOW,W.DESCR,W.READONLY,W.FREQUENZA,W.MULTI
            FROM 
            WFS.WORKFLOW  W,
            WFS.AUTORIZZAZIONI A
            WHERE 1=1
            AND A.ID_WORKFLOW = W.ID_WORKFLOW
            AND A.ID_GRUPPO IN ( $ListIdGroups ) 
            AND W.ABILITATO = 'Y'
            ORDER BY WORKFLOW;";

        $res = $this->_db->getArrayByQuery($SqlList, []);
        //  $this->_db->printSql();
        //  $this->_db->error_message("getAutorizzazioniWorkflow res",$res);

        return $res;
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getPeriodo
     *
     * @param  mixed $IdWorkFlow
     * @return array $res
     */
    public function getPeriodo($IdWorkFlow)
    {
        /*  try {*/
        // 
        $SqlList = "SELECT DISTINCT ESER_ESAME||LPAD(MESE_ESAME,3,0) PERIODO
                FROM WORK_CORE.ID_PROCESS P 
                WHERE 1=1
                --AND FLAG_STATO != 'C'
                AND ID_WORKFLOW = ?
                ORDER BY 1 DESC";
        $res = $this->_db->getArrayByQuery($SqlList, [$IdWorkFlow]);
        //$this->_db->printSql();


        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * callAddIdProcess
     * call db for AddIdProcess
     * @param  mixed $AddIdProcess
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdPeriod
     * @param  mixed $ProcEserEsame
     * @param  mixed $ProcMeseEsame
     * @return $ShowErrore
     */
    public function callAddIdProcess($IdWorkFlow, $IdPeriod, $ProcEserEsame, $ProcMeseEsame, $IdTeam)
    {
        /*  try {*/
        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];



        $CallPlSql = 'CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $SqlList = "SELECT SUBSTR(MAX(ID_PROCESS),8,2)+1 CNT FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = $IdWorkFlow AND ESER_ESAME||LPAD(MESE_ESAME,3,0) = $IdPeriod
                and id_team = $IdTeam";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        foreach ($res as $row) {
            $CntIdP = $row['CNT'];
        }
        $Descr = "Chiusura $ProcEserEsame $ProcMeseEsame [$CntIdP]";
        $Tipo = 'Q';

        $values = [
            [
                'name' => 'ProcEserEsame',
                'value' => $ProcEserEsame,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'ProcMeseEsame',
                'value' => $ProcMeseEsame,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'Descr',
                'value' => $Descr,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'Tipo',
                'value' => $Tipo,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'IdTeam',
                'value' => $IdTeam,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'IdWorkFlow',
                'value' => $IdWorkFlow,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'User',
                'value' => $User,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'Errore',
                'value' => $Errore,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'Note',
                'value' => $Note,
                'type' => DB2_PARAM_OUT
            ],
        ];

        // db2_bind_param($stmt, 1, "ProcEserEsame", DB2_PARAM_IN);
        // db2_bind_param($stmt, 2, "ProcMeseEsame", DB2_PARAM_IN);
        // db2_bind_param($stmt, 3, "Descr", DB2_PARAM_IN);
        // db2_bind_param($stmt, 4, "Tipo", DB2_PARAM_IN);
        // db2_bind_param($stmt, 5, "IdTeam", DB2_PARAM_IN);
        // db2_bind_param($stmt, 6, "IdWorkFlow", DB2_PARAM_IN);
        // db2_bind_param($stmt, 7, "User", DB2_PARAM_IN);
        // db2_bind_param($stmt, 8, "Errore", DB2_PARAM_OUT);
        // db2_bind_param($stmt, 9, "Note", DB2_PARAM_OUT);

        $res = $this->_db->callDb($CallPlSql, $values);

        if ($res['Errore'] != "0") {
            $Note = "PLSQL Procedure Calling AddIdProcess Error: " . $res['Errore'] . " " . $res['Note'];
            $ShowErrore = 1;
        }

        if ($ShowErrore != 0) {
            $this->_db->error_message("Errore:", $Note);
        } else {
            $this->_db->info_message("IdProcess: '" . $Descr . "' Inserito correttamente!");
        }

        return $ShowErrore;
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getArrFlussi
     * Get ArrFlussi for a given IdWorkFlow 
     * @param  mixed $IdWorkFlow
     * @param  mixed $ArrFlussi
     * @return $ArrFlussi
     */
    public function getArrFlussi($IdWorkFlow, $ArrFlussi)
    {
        /*  try {*/
        $SqlList = "SELECT ID_FLU,FLUSSO,DESCR FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        foreach ($res as $row) {
            $Id = $row['ID_FLU'];
            $Name = $row['FLUSSO'];
            $Desc = $row['DESCR'];
            array_push($ArrFlussi, array($Id, $Name, $Desc));
        }
        return $ArrFlussi;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getIdProcess
     * Get IdProcess for a given IdWorkFlow and IdPeriod 
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdPeriod
     * @param  mixed $ArrIdProcess
     * @param  mixed $AddIdProcess
     * @return array
     */
    public function getIdProcess($IdWorkFlow, $IdPeriod, &$ArrIdProcess, $AddIdProcess)
    {
        /*  try {*/
        $SqlList = "SELECT ID_PROCESS, DESCR,  ESER_ESAME, MESE_ESAME, ESER_MESE, FLAG_CONSOLIDATO, TIPO, READONLY, FLAG_STATO, ID_TEAM
            ,FLAG_CONSOLIDATO CONS
            FROM WORK_CORE.ID_PROCESS  I
            WHERE 1=1
            AND ID_WORKFLOW = $IdWorkFlow 
            AND ESER_ESAME||LPAD(MESE_ESAME,3,0) = $IdPeriod
            ORDER BY ID_PROCESS";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * callAddKIdProcess
     * make a call to K_ID_PROCESS and use AddIdProcess method
     * @param  mixed $AddIdProcess
     * @param  mixed $ArrIdProcess
     * @param  mixed $ProcEserEsame
     * @param  mixed $ProcMeseEsame
     * @return void
     */
    public function callAddKIdProcess($AddIdProcess, $ArrIdProcess, $ProcEserEsame, $ProcMeseEsame, $IdTeam, $IdWorkFlow)
    {
        /*   try {*/

        //$AddIdProcess = $_POST['AddIdProcess'];
        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];
        if ($AddIdProcess == "1") {
            $CallPlSql = 'CALL WORK_RULES.K_ID_PROCESS.AddIdProcess(?, ?, ?, ?, ?, ?, ?, ?, ?)';

            $CntIdP = count($ArrIdProcess) + 1;
            $Descr = "Chiusura $ProcEserEsame $ProcMeseEsame [$CntIdP]";
            $Tipo = 'Q';

            $values = [
                [
                    'name' => 'ProcEserEsame',
                    'value' => $ProcEserEsame,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'ProcMeseEsame',
                    'value' => $ProcMeseEsame,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'Descr',
                    'value' => $Descr,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'Tipo',
                    'value' => $Tipo,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdTeam',
                    'value' => $IdTeam,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdWorkFlow',
                    'value' => $IdWorkFlow,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'User',
                    'value' => $User,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'Errore',
                    'value' => $Errore,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Note',
                    'value' => $Note,
                    'type' => DB2_PARAM_OUT
                ],
            ];

            //   db2_bind_param($stmt, 1, "ProcEserEsame", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 2, "ProcMeseEsame", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 3, "Descr", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 4, "Tipo", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 5, "IdTeam", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 6, "IdWorkFlow", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 7, "User", DB2_PARAM_IN);
            //   db2_bind_param($stmt, 8, "Errore", DB2_PARAM_OUT);
            //   db2_bind_param($stmt, 9, "Note", DB2_PARAM_OUT);

            $res = $this->_db->callDb($CallPlSql, $values);

            if (!$res['Errore']) {
                $Note = "PLSQL Procedure Calling Error: " . $Note;
                $ShowErrore = 1;
            }
            if ($ShowErrore != 0) {
                $this->_db->error_message("Errore:", $Note);
            }
        }
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }



    /**
     * getWorkflowMeseEsame
     * get info from Legame_Flussi
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdPeriod
     * @param  mixed $Regime
     * @param  mixed $ProcMeseEsame
     * @param  mixed $ArrLegami
     * @return array
     */
    public function getWorkflowMeseEsame($IdWorkFlow, $IdPeriod, $Regime, $ProcMeseEsame, $IdProcess, &$ArrLegami)
    {
        /*   try {*/
        $res = [];
        if ($IdWorkFlow != "" and $IdPeriod != "") {


            $SqlList = "SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP,
                ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO 
                FROM WFS.LEGAME_FLUSSI L
                WHERE ID_WORKFLOW = $IdWorkFlow 
                AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'";


            if ($Regime != "") {
                $SqlList = $SqlList . "
              AND ID_FLU IN ( SELECT ID_FLU FROM WFS.REGIME_LEGAME_FLUSSI WHERE ID_REGIME = $Regime ) ";
            }

            $SqlList = $SqlList . "
           ORDER BY LIV, NOME_FLUSSO, TIPO, ID_DIP";
            //echo $SqlList;
            $res = $this->_db->getArrayByQuery($SqlList, []);

            return $res;
        }
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getRegimeFlussiCount
     * get count of Regime_Flussi
     * @param  mixed $IdWorkFlow
     * @return $res
     */
    public function getRegimeFlussiCount($IdWorkFlow)
    {
        /*    try {*/
        $SqlList = "SELECT count(*) CNT FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_REGIME IN (SELECT DISTINCT ID_REGIME FROM WFS.REGIME_LEGAME_FLUSSI )";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getIdRegime
     * get ID_Regime and Regime from Regime_Flussi
     * @param  mixed $IdWorkFlow
     * @return $res
     */
    public function getIdRegime($IdWorkFlow)
    {
        /* try {*/
        $SqlList = "SELECT ID_REGIME, REGIME FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_REGIME IN ( SELECT DISTINCT ID_REGIME FROM WFS.REGIME_LEGAME_FLUSSI )";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        return $res;
        /*  } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getWFSAdmin
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $Uk
     * @param  mixed $WFSAdmin
     * @return $WFSAdmin
     */
    public function getWFSAdmin($IdWorkFlow, $Uk, &$WFSAdmin)
    {
        /*try {*/
        $SqlWFSAdmin = "SELECT count(*) CNT
            FROM WFS.ASS_GRUPPO
            WHERE 1=1
            AND ID_GRUPPO IN (  SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = $IdWorkFlow AND GRUPPO = 'ADMIN'  )
            AND ID_UK = $Uk
            ";
        $res = $this->_db->getArrayByQuery($SqlWFSAdmin, []);

        foreach ($res as $row) {
            $CntW = $row['CNT'];
            if ("$CntW" != "0") {
                $WFSAdmin = True;
            }
        }
        return $WFSAdmin;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }
public function isWFSRead($IdWorkFlow, $Uk)
    {
    $isWFSRead = false;

   $Sql =" SELECT count(*) as CNT FROM WFS.AUTORIZZAZIONI AU WHERE AU.ID_WORKFLOW = ?
                 AND AU.ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK =  ?  )
                 AND AU.ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = AU.ID_WORKFLOW AND GRUPPO = 'READ')";

     $res = $this->_db->getArrayByQuery($Sql, [$IdWorkFlow,$Uk]);

        foreach ($res as $row) {
            $CntW = $row['CNT'];
            if ($CntW != "0") {
                $isWFSRead = true;
            }
        }
       // $this->_db->printSql();

        return $isWFSRead;
    }
    /**
     * getArrElaTest
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlu
     * @return $ArrElaTest
     */
    public function getArrElaTest($IdWorkFlow, $IdFlu)
    {
        /*  try {*/
        $SqlList = "SELECT ID_ELA ID_DIP FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW  = $IdWorkFlow AND ID_ELA IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu ) AND ID_SH = 0";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        $ArrElaTest = array();
        foreach ($res as $row) {
            $IdDp = $row['ID_DIP'];
            array_push($ArrElaTest, $IdDp);
        }
        return $ArrElaTest;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getArrCarTest
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlu
     * @return $ArrCarTest
     */
    public function getArrCarTest($IdWorkFlow, $IdFlu)
    {
        /* try {*/
        $SqlList = "SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND ID_CAR IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu ) AND NOME_INPUT = 'WFS_TEST'";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        $ArrCarTest = array();
        foreach ($res as $row) {
            $IdDp = $row['ID_DIP'];
            array_push($ArrCarTest, $IdDp);
        }
        return $ArrCarTest;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * getArrShellDett
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlu
     * @return $ArrShellDett
     */
    public function getArrShellDett($IdWorkFlow, $IdFlu)
    {
        /*  try {*/

        $SqlList = "SELECT ID_SH,PARALL,BATCH,BLOCKWFS from WORK_CORE.CORE_SH_ANAG WHERE WFS = 'Y' AND ID_SH IN (
            SELECT ID_SH FROM WFS.ELABORAZIONI
            WHERE 1=1
                AND ID_WORKFLOW  = $IdWorkFlow
                AND ID_ELA   IN ( SELECT ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = $IdFlu )
                )
            ORDER BY ID_SH";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        $ArrShellDett = array();
        foreach ($res as $row) {
            $IdShell = $row['ID_SH'];
            $Parall = $row['PARALL'];
            $Batch = $row['BATCH'];
            $Block = $row['BLOCKWFS'];

            array_push($ArrShellDett, array($IdShell, $Parall, $Batch, $Block));
        }
        return $ArrShellDett;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }



    public function getShellName($IdSh)
    {
        /* try {*/

        $SqlList = "SELECT NAME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdSh";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        foreach ($res as $row) {
            $STEP = $row['NAME'];
        }
        return $STEP;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * setSvalidaOff
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdFlu
     * @param  mixed $IdProcess
     * @param  mixed $SvalidaOff
     * @param  mixed $BlockCons
     * @return void
     */
    public function setSvalidaOff($IdWorkFlow, $IdFlu, $IdProcess, &$SvalidaOff, &$BlockCons)
    {
        /* try {*/
        $SqlList = "SELECT BLOCK_CONS,
            ( SELECT count(*)
            FROM WORK_CORE.ID_PROCESS A
            WHERE 1=1
            AND ID_PROCESS = $IdProcess 
            ) PERIDO_CONS
            FROM WFS.FLUSSI
            WHERE ID_FLU = $IdFlu ";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        foreach ($res as $row) {
            $BlockCons = $row['BLOCK_CONS'];
            $PeriodCons = $row['PERIDO_CONS'];
            //$SvalidaOff = false;
            if ($BlockCons == "X" and $PeriodCons != "0") {
                $SvalidaOff = true;
            }
        }
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * getArrStato
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdProcess
     * @param  mixed $ListIdStato
     * @return array $ArrStato
     */
    public function getArrStato($IdWorkFlow, $IdProcess, &$ListIdStato, &$ArrStato)
    {
        /* try {*/
        //STATO
        //$ListIdStato = "0";
        $SqlList = "SELECT ID_FLU,TIPO,ID_DIP,INIZIO,FINE,ESITO,NOTE FROM WFS.ULTIMO_STATO WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS = '$IdProcess' ";
        $res = $this->_db->getArrayByQuery($SqlList, []);

        /*  foreach ($res as $row) {
                $TabIdFlu = $row['ID_FLU'];
                $Tipo = $row['TIPO'];
                $IdDip = $row['ID_DIP'];
                $Inizio = $row['INIZIO'];
                $Fine = $row['FINE'];
                $Esito = $row['ESITO'];
                $Note = $row['NOTE'];

                array_push($ArrStato, array($TabIdFlu, $Tipo, $IdDip, $Inizio, $Fine, $Esito, $Note));
                $ListIdStato = $ListIdStato . "," . $Id; //???
            }*/
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getArrUsers
     *
     * @param  mixed $ArrUsers
     * @param  mixed $ListIdStato
     * @return array
     */
    public function getArrUsers()
    {
        /*  try {*/
        $SqlList = "SELECT NOMINATIVO, USERNAME FROM WEB.TAS_UTENTI";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /* } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * RefreshCoda
     * 
     */

    /**
     * getRichiesteCoda
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdProcess
     * @return void
     */
    public function getNumRichiesteCoda($IdWorkFlow, $IdProcess)
    {
        /* try {*/
        $sqlTabRead = "SELECT
         ( SELECT count(*)  
        FROM WFS.CODA_RICHIESTE 
        WHERE 1=1
         AND ID_WORKFLOW = $IdWorkFlow
         AND ID_PROCESS IN ( SELECT ID_PROCESS FROM WORK_CORE.ID_PROCESS WHERE FLAG_STATO != 'C' 
         AND (ESER_ESAME,MESE_ESAME) IN ( SELECT ESER_ESAME,MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
         )
         AND AZIONE IN ('E','C')
        ) INTHISWORKFLOW
        ,( SELECT count(*)  
        FROM WFS.CODA_RICHIESTE 
        WHERE 1=1
         AND ID_WORKFLOW = $IdWorkFlow
         AND ID_PROCESS  = $IdProcess
         AND AZIONE IN ('E','C')
        ) INTHISPROCESS
        ,( SELECT TO_CHAR(MAX(TMS_INSERT),'YYYY-MM-DD HH24:MI:SS.FF')
        FROM WFS.CODA_STORICO 
        WHERE 1=1
         AND ID_WORKFLOW = $IdWorkFlow
         AND ID_PROCESS  = $IdProcess
        ) MAXTIME
        ,( SELECT count(*)  
        FROM WFS.ULTIMO_STATO 
        WHERE 1=1
         AND ID_WORKFLOW = $IdWorkFlow
         AND ID_PROCESS  = $IdProcess
         AND TIPO IN ('E','C')
         AND ESITO = 'I'
        ) INRUN
        ,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS.FF') ORA
        FROM DUAL
         ";

        $res = $this->_db->getArrayByQuery($sqlTabRead, []);


        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }
    public function getRichiesteCodaEsito($IdWorkFlow, $IdProcess)
    {
        /*  try {*/

      $sql = "SELECT ID_FLU,TIPO,ID_DIP, 
    (
    SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP
    ) ID_LEGAME
    ,( SELECT ESITO FROM WFS.ULTIMO_STATO WHERE ID_PROCESS = C.ID_PROCESS AND ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP ) ESITO
    FROM WFS.CODA_RICHIESTE C WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess AND AZIONE IN ('E','C')";

      /*  $sql = "
        SELECT ID_FLU,TIPO,ID_DIP,
        (SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP) ID_LEGAME,
        ( SELECT ESITO FROM WFS.ULTIMO_STATO WHERE ID_PROCESS = C.ID_PROCESS AND ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP ) ESITO
        FROM
        WFS.CODA_RICHIESTE C
        WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess AND AZIONE IN ('E','C')
        UNION ALL
        SELECT
        ID_FLU,TIPO,ID_DIP,
        (SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = B.ID_WORKFLOW AND ID_FLU = B.ID_FLU AND TIPO = B.TIPO AND ID_DIP = B.ID_DIP) ID_LEGAME,
        ESITO
        FROM WFS.ULTIMO_STATO B
        WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess
        AND 0 = (
        SELECT count(*) FROM WFS.CODA_RICHIESTE R
        WHERE R.ID_WORKFLOW = B.ID_WORKFLOW
        AND R.ID_FLU = B.ID_FLU
        AND R.TIPO = B.TIPO
        AND R.ID_DIP = B.ID_DIP
        )";*/

        $res = $this->_db->getArrayByQuery($sql, []);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getFlusso
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $OldMaxTime
     * @return void
     */
    public function getFlusso($IdWorkFlow, $IdProcess, $OldMaxTime)
    {
        /* try {*/

        $sql = "SELECT ID_FLU, ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = C.ID_FLU ) FLUSSO, TIPO, ID_DIP
        ,(
        SELECT ID_LEGAME FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP
        ) ID_LEGAME 
        ,ESITO
        ,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS.FF') LAST
        FROM WFS.CODA_STORICO C WHERE ID_WORKFLOW = $IdWorkFlow AND ID_PROCESS  = $IdProcess AND TMS_INSERT > '$OldMaxTime'
        AND UPPER(UTENTE) = UPPER('" . $_SESSION['codname'] . "')
        AND TIPO IN ('E','C')
        AND AZIONE NOT IN ('S','V')
        ORDER BY LAST";

        $res = $this->_db->getArrayByQuery($sql, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * getLegamiFlusso
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $Flu
     * @return void
     */
    public function getLegamiFlusso($IdWorkFlow, $Flu)
    {
        /*try {*/
        $sql = "SELECT ID_FLU  FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND TIPO = 'F' AND ID_DIP = $Flu ";
        $res = $this->_db->getArrayByQuery($sql, []);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * getMeseEsame
     *
     * @param  mixed $IdProcess
     * @return void
     */
    public function getMeseEsame($IdProcess)
    {
        /* try {*/
        $SqlList = "SELECT MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getListaTestFlussi
     *
     * @param  mixed $IdWorkFlow
     * @return void
     */
    public function getListaTestFlussi($IdWorkFlow)
    {
        /*    try {*/
        $SqlList = "SELECT DISTINCT ID_FLU FROM WFS.LEGAME_FLUSSI L
                    WHERE ID_WORKFLOW = $IdWorkFlow AND
                        ( (TIPO,ID_DIP) IN ( SELECT 'E',ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_ELA = L.ID_DIP AND ID_SH = 0 )
                        OR 
                        (TIPO,ID_DIP) IN ( SELECT 'C',ID_CAR FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_CAR = L.ID_DIP AND NOME_INPUT = 'WFS_TEST' )
                        ) ";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    public function getLivLegamiFlussi($IdWorkFlow)
    {
        /*   try {*/
        $SqlList = "SELECT DISTINCT LIV FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY LIV";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    public function getLegamiFlussi($IdWorkFlow)
    {
        /*  try {*/
        $SqlList = "SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP, (SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = A.ID_FLU) FLUSSO FROM WFS.LEGAME_FLUSSI A WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY LIV, FLUSSO, TIPO, ID_DIP";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    public function getFlussoLegamiFlussi($IdWorkFlow)
    {
        /* try {*/
        $SqlList = "SELECT ID_FLU,FLUSSO,DESCR FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    public function getCountStatoFlusso($IdProcess, $IdFlusso, $ProcMeseEsame)
    {
        /*try {*/
        $SqlList = "
            WITH W_ABILITATE AS(
            SELECT ID_DIP,TIPO FROM WFS.LEGAME_FLUSSI WHERE DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
            )
            SELECT ID_FLU,FLUSSO,DESCR
            ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'E'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
            ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) OK
            ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'I'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) IZ
            ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'N'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TD
            ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO NOT IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
            ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TTF
            FROM WFS.FLUSSI F 
            WHERE ID_FLU = $IdFlusso 
            ORDER BY FLUSSO";
        //die();
        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }



    /**
     * Flusso
     * 
     */

    public function getFlussiLgami($ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso)
    {
        /* try {*/


        $SqlList = "SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP,
                        ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_FLU ) NOME_FLUSSO
                        FROM WFS.LEGAME_FLUSSI L
                        WHERE ID_WORKFLOW = $IdWorkFlow 
                        AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
                        AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                        AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
                        AND ( ID_FLU = $IdFlusso OR ( TIPO = 'F' AND ID_DIP = $IdFlusso ) )
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

    public function getFlussiLgamiGruppo($Uk, $ProcMeseEsame, $IdWorkFlow, $IdProcess, $IdFlusso)
    {
        /*  try {*/

        $SqlList = "
    WITH W_ABILITATE AS(
      SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND OPT NOT IN ('Y','M') AND DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
      AND INZ_VALID <= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
      AND FIN_VALID >= ( SELECT INZ_VAL FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess )
    )
    SELECT ID_FLU,FLUSSO,DESCR, BLOCK_CONS
    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'E' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) OK
    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ( 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) WAR
    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'I' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) IZ
    ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'N' AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) TD
    ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO NOT IN ( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
    ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO IN( 'F', 'W' ) AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE ) ) TTF
    ,( SELECT count(*) FROM WFS.CODA_RICHIESTE WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND ID_PROCESS = $IdProcess   ) CODA
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLUSSO = F.ID_FLU 
         AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow  )
         AND ID_GRUPPO = ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND GRUPPO = 'READ')       
    ) RDONLY
    ,( SELECT count(*) FROM WFS.AUTORIZZAZIONI 
        WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLUSSO = F.ID_FLU 
        AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk AND ID_WORKFLOW = $IdWorkFlow 
                      AND ID_GRUPPO NOT IN ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE GRUPPO = 'READ' AND ID_WORKFLOW = $IdWorkFlow )
        )   
    ) PERMESSO
    ,( SELECT count(*) FROM WORK_CORE.ID_PROCESS WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_PROCESS = $IdProcess AND READONLY = 'Y' ) WFSRDONLY
    ,( SELECT count(*) 
    FROM WFS.LEGAME_FLUSSI L JOIN WFS.ULTIMO_STATO U ON L.ID_WORKFLOW = U.ID_WORKFLOW AND L.ID_FLU = U.ID_FLU AND L.TIPO = U.TIPO AND L.ID_DIP = U.ID_DIP
    WHERE U.ID_WORKFLOW = F.ID_WORKFLOW AND U.ID_FLU = F.ID_FLU AND U.TIPO = 'C' AND  U.NOTE = 'Confermo il dato in tabella' AND U.ID_PROCESS = $IdProcess
    ) TCD
    ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND (
      ( TIPO = 'E' AND ID_DIP IN ( SELECT ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_SH = 0 ) )
      OR
      ( TIPO = 'C' AND ID_DIP IN ( SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND NOME_INPUT = 'WFS_TEST' ))
    )
    ) CNTLAB
    ,( SELECT FLAG_CONSOLIDATO FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess ) CONS
    ,( SELECT SUM(U.WARNING)
    FROM WFS.LEGAME_FLUSSI L 
    JOIN WFS.ULTIMO_STATO U 
    ON    L.ID_WORKFLOW = U.ID_WORKFLOW
      AND L.ID_FLU = U.ID_FLU
      AND L.TIPO = U.TIPO
      AND L.ID_DIP = U.ID_DIP
    WHERE 1=1
    AND U.ID_WORKFLOW = F.ID_WORKFLOW 
    AND U.ID_FLU = F.ID_FLU 
    AND U.ID_PROCESS = $IdProcess
    AND U.WARNING > 0
    ) WARNING
    ,( SELECT count(*)
    FROM WFS.LEGAME_FLUSSI L 
    JOIN WFS.ULTIMO_STATO U 
    ON    L.ID_WORKFLOW = U.ID_WORKFLOW
      AND L.ID_FLU = U.ID_FLU
      AND L.TIPO = U.TIPO
      AND L.ID_DIP = U.ID_DIP
    WHERE 1=1
    AND U.ID_WORKFLOW = F.ID_WORKFLOW 
    AND U.ID_FLU = F.ID_FLU 
    AND U.ID_PROCESS = $IdProcess
    AND U.WARNING < 0
    ) ALERT
    ,( SELECT count(*)
    FROM WFS.LEGAME_FLUSSI L
    WHERE 1=1
    AND L.ID_WORKFLOW = F.ID_WORKFLOW 
    AND L.ID_FLU = F.ID_FLU 
    AND L.OPT = 'M'
    AND 0 = ( 
      SELECT count(*) 
      FROM WFS.ULTIMO_STATO U 
      WHERE 1=1
      AND U.ID_PROCESS = $IdProcess
      AND L.ID_WORKFLOW = U.ID_WORKFLOW
      AND L.ID_FLU = U.ID_FLU
      AND U.ESITO IN ('W','F')
      AND (U.TIPO,U.ID_DIP) IN ( SELECT T.TIPO,T.ID_DIP
                                 FROM WFS.LEGAME_FLUSSI T
                                 WHERE 1=1
                                 AND T.ID_WORKFLOW = U.ID_WORKFLOW 
                                 AND T.ID_FLU = U.ID_FLU 
                                 AND T.OPT = 'M' 
                                 )
      )
    ) MANUAL
    ,( SELECT count(*) FROM WFS.REGIME_LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_REGIME IN (SELECT ID_REGIME FROM WFS.REGIME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow AND REGIME = 'A RICHIESTA')) ARICH
    FROM WFS.FLUSSI F 
    WHERE ID_FLU = $IdFlusso AND ID_WORKFLOW = $IdWorkFlow 
    ORDER BY FLUSSO";

        $res = $this->_db->getArrayByQuery($SqlList, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    /**
     * callTestSottoFlussi
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $IdProcess
     * @param  mixed $IdFlusso
     * @param  mixed $Stato
     * @param  mixed $Errore
     * @param  mixed $Note
     * @return void
     */
    public function callTestSottoFlussi($IdWorkFlow, $IdProcess, $IdFlusso, $Stato, $Errore, $Note)
    {
        $CallPlSql = 'CALL WFS.K_WFS.TestSottoFlussi(?, ?, ?, ?, ?, ? )';

        $Errore = 0;
        $Note = "";

        $values = [
            [
                'name' => 'IdWorkFlow',
                'value' => $IdWorkFlow,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'IdProcess',
                'value' => $IdProcess,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'IdFlusso',
                'value' => $IdFlusso,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'Stato',
                'value' => $Stato,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'Errore',
                'value' => $Errore,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'Note',
                'value' => $Note,
                'type' => DB2_PARAM_OUT
            ]
        ];

        $ret = $this->_db->callDb($CallPlSql, $values);

        return $ret;
    }


    public function callFlussoCompletato($IdWorkFlow, $IdProcess, $TestIdDip)
    {

        $Errore = 0;
        $Note = "";
        $Stato = 'N';
        //$conn =  $this->_db->open_db();
        $CallPlSql = 'CALL WFS.K_WFS.FlussoCompletato(?, ?, ?, ?, ?, ? )';

        $values = [
            [
                'name' => 'IdWorkFlow',
                'value' => $IdWorkFlow,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'IdProcess',
                'value' => $IdProcess,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'TestIdDip',
                'value' => $TestIdDip,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'Stato',
                'value' => $Stato,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'Errore',
                'value' => $Errore,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'Note',
                'value' => $Note,
                'type' => DB2_PARAM_OUT
            ]
        ];

        $ret = $this->_db->callDb($CallPlSql, $values);

        return $ret;
    }

    /**
     * Mostra storico
     * 
     */

    public function getStoricoFlussi($IdWorkFlow, $SelIdProcess)
    {
        /* try {*/

        $sqlTabRead = "SELECT 
                            ID_PROCESS
                            ,C.ID_FLU ID_FLU
                            ,(SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = C.ID_FLU ) FLUSSO
                            ,TIPO
                            ,CASE TIPO
                            WHEN 'C' THEN (SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = C.ID_DIP )
                            WHEN 'E' THEN (SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = C.ID_DIP )
                            WHEN 'L' THEN (SELECT LINK FROM WFS.LINKS WHERE ID_LINK = C.ID_DIP )
                            WHEN 'V' THEN (SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = C.ID_DIP )
                            END AS DIPENDENZA
                            ,ID_DIP
                            ,AZIONE
                            ,NVL((SELECT NOMINATIVO FROM WEB.TAS_UTENTI WHERE UPPER(USERNAME) = UPPER(TRIM(UTENTE)) ),UTENTE) UTENTE
                            ,FILE
                            ,NOTE
                            ,ID_RUN_SH
                            ,TO_CHAR(INIZIO,'YYYY-MM-DD HH24:MI:SS') INIZIO
                            ,TO_CHAR(FINE,'YYYY-MM-DD HH24:MI:SS')   FINE
                            ,timestampdiff(2,NVL(FINE,INIZIO)-INIZIO) DIFF 
                            ,CASE TIPO
                            WHEN 'C' THEN null
                            WHEN 'E' THEN (
                            SELECT PARALL 
                            FROM 
                            WORK_CORE.CORE_SH_ANAG A,
                            WFS.ELABORAZIONI E
                            WHERE A.ID_SH = E.ID_SH
                            AND E.ID_ELA = C.ID_DIP
                            )
                            END AS SHELL_PARALL
                            , NVL(ESITO,'F') ESITO
                            FROM WFS.CODA_STORICO C
                            WHERE ID_WORKFLOW = $IdWorkFlow
                            AND ID_PROCESS = $SelIdProcess
                            ORDER BY INIZIO DESC
                            ";
        $res = $this->_db->getArrayByQuery($sqlTabRead, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }

    /**
     * getTabParametri
     *
     * @param  mixed $SelIdProcess
     * @return void
     */
    public function getTabParametri($SelIdProcess)
    {
        /*try {*/

        $sqlTabRead = "SELECT AMBITO,CAMPO,VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = ?;";
        $sqlTabRead = "SELECT 
                (SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = lf.ID_FLU ) FLUSSO, 
                (SELECT LINK FROM WFS.LINKS WHERE ID_LINK = lf.ID_DIP ) DIPENDENZA, 
                CAMPO,
                VALORE
                FROM WFS.PARAMETRI_ID_PROCESS p
                LEFT JOIN
                WFS.LEGAME_FLUSSI lf  
                ON p.ID_LINK = lf.ID_DIP
                AND lf.ID_WORKFLOW = ( SELECT ID_WORKFLOW FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = p.ID_PROCESS )
                WHERE ID_PROCESS = ?;";
        $res = $this->_db->getArrayByQuery($sqlTabRead, [$SelIdProcess]);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    public function getTabFormule($SelIdProcess)
    {
        /* try {*/

        $sqlTabRead = "SELECT TIPO,'FORMULA'||NUM_FORMULA FORMULA,TESTO,DESCR,VALID FROM WFS.FORMULA_ID_PROCESS WHERE ID_PROCESS = ?;
                            ";
        $res = $this->_db->getArrayByQuery($sqlTabRead, [$SelIdProcess]);
        return $res;
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }


    public function getCodaFlussi($IdWorkFlow, $IdProcess)
    {
        /*  try {*/

        $sqlTabRead = "SELECT 
                            ID_PROCESS
                            ,C.ID_FLU ID_FLU
                            ,(SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = C.ID_FLU ) FLUSSO
                            ,TIPO
                            ,CASE TIPO
                            WHEN 'C' THEN (SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = C.ID_DIP )
                            WHEN 'E' THEN (SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = C.ID_DIP )
                            END AS DIPENDENZA
                            ,ID_DIP
                            ,AZIONE
                            ,NVL((SELECT NOMINATIVO FROM WEB.TAS_UTENTI WHERE UPPER(USERNAME) = UPPER(TRIM(UTENTE)) ),UTENTE) UTENTE
                            ,FILE
                            ,TO_CHAR(TMS_INSERT,'YYYY-MM-DD HH24:MI:SS') TMS_INSERT
                            ,CASE TIPO
                            WHEN 'C' THEN null
                            WHEN 'E' THEN (
                            SELECT PARALL 
                            FROM 
                            WORK_CORE.CORE_SH_ANAG A,
                            WFS.ELABORAZIONI E
                            WHERE A.ID_SH = E.ID_SH
                            AND E.ID_ELA = C.ID_DIP
                            )
                            END AS SHELL_PARALL
                            , NVL(( SELECT ESITO FROM WFS.ULTIMO_STATO WHERE  ID_PROCESS = C.ID_PROCESS AND ID_WORKFLOW = C.ID_WORKFLOW AND ID_FLU = C.ID_FLU AND TIPO = C.TIPO AND ID_DIP = C.ID_DIP ),'N') ESITO
                            FROM WFS.CODA_RICHIESTE C
                            WHERE ID_WORKFLOW = $IdWorkFlow
                            AND ID_PROCESS  = $IdProcess
                            ORDER BY TMS_INSERT
                            ";
        $res = $this->_db->getArrayByQuery($sqlTabRead, []);
        return $res;
        /*  } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            // $this->_db->close_db();
            throw $e;
        }*/
    }



    /**
     * getCodaRichieste
     *
     * @param  mixed $IdProcess
     * @return void
     */
    public function getCodaRichieste($IdProcess)
    {
        /* try {*/
        $sql = "SELECT count(*) CNT from wFS.CODA_RICHIESTE WHERE ID_PROCESS = ?";

        $ret = $this->_db->getArrayByQuery($sql, [$IdProcess]);
        //  $this->_db->printSql();
        $res = $ret[0]['CNT'];
        return $res;
        /*  } catch (Exception $e) {
            throw $e;
        }*/
    }

    /**
     * callElaborazioniPossibili
     *
     * @return void
     */
    public function forzaElaborazioniPossibili()
    {
        /* try {*/

        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];

        foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }

        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];
        $InCoda = 0;

        if ($IdProcess != "" && $IdWorkFlow != "") {
            $CallPlSql = 'CALL WFS.K_WFS.ElaborazioniPossibili(?, ?, ?, ?, ?, ? )';
            $values = [
                [
                    'name' => 'IdWorkFlow',
                    'value' => $IdWorkFlow,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'IdProcess',
                    'value' => $IdProcess,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'User',
                    'value' => $User,
                    'type' => DB2_PARAM_IN
                ],
                [
                    'name' => 'InCoda',
                    'value' => $InCoda,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Errore',
                    'value' => $Errore,
                    'type' => DB2_PARAM_OUT
                ],
                [
                    'name' => 'Note',
                    'value' => $Note,
                    'type' => DB2_PARAM_OUT
                ],
            ];
            // db2_bind_param($stmt, 1, "IdWorkFlow", DB2_PARAM_IN);
            // db2_bind_param($stmt, 2, "IdProcess", DB2_PARAM_IN);
            // db2_bind_param($stmt, 3, "User", DB2_PARAM_IN);
            // db2_bind_param($stmt, 4, "Errore", DB2_PARAM_OUT);
            // db2_bind_param($stmt, 5, "Note", DB2_PARAM_OUT);
            $res = $this->_db->callDb($CallPlSql, $values);

            $ErrSt = 0;

            if ($res['Errore'] != 0) {
                $this->_db->error_message("PLSQL Procedure Calling ElaborazioniPossibili Error $Errore: " . $res['Note']);
                $ErrSt = 1;
            }

            if (($ErrSt == 0 and $res['InCoda'] != 0)) {
                shell_exec("sh $PRGDIR/AvviaElabServer.sh '${SSHUSR}' '${SERVER}' '${DIRSH}' ${IdWorkFlow} ${IdProcess} 2>&1 >> $PRGDIR/AvviaElabServer.log &");
            }
        }
        /*   } catch (Throwable $e) {
            //  $this->_db->close_db();
            throw $e;
        } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }


    public function getdatiTableKo($IdDip, $IdProcess)
    {
        /*  try {*/
        $sql = "SELECT ID_ERR_TRASH ,RIGA, COL_NAME COLONNA ,VALORE,NOTE_TAS ERRORE FROM WORK_ELAB.LOAD_TRASH_LOG
                        WHERE ID_ERR_TRASH = (SELECT ID_ERR_TRASH FROM WFS.ULTIMO_STATO WHERE ID_PROCESS = ?
                        AND TIPO = 'C' AND ID_DIP = ?)";

        $ret = $this->_db->getArrayByQuery($sql, [$IdProcess, $IdDip]);
        //  $this->_db->printSql();

        return $ret;
        /*   } catch (Exception $e) {
            throw $e;
        }*/
    }

    /**
     * getFlussiWf
     *
     * @param  mixed $IdWorkFlow
     * @param  mixed $idFlu
     * @return void
     */
    public function getFlussiWf($IdWorkFlow, $idFlu = "")
    {
        /*  try {*/
        $res = "";
        $sqladd = "";

        if ($IdWorkFlow) {
            if ($idFlu != '') {
                $sqladd = "  AND ID_FLU = $idFlu ";
            }

            // if ( ( "$Dett" == "" and "$Tipo" == "E" ) || ( substr($Dett,0,8) == "WFS_TEST" and  "$Tipo" == "C" )
            $sql = "SELECT *,
                 ( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU ) UTILIZZATO
                 
                ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND (
                      ( TIPO = 'E' AND ID_DIP IN ( SELECT ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_SH = 0 ) )
                      OR
                      ( TIPO = 'C' AND ID_DIP IN ( SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND NOME_INPUT = 'WFS_TEST' ))
                    )
                    ) LAB
                 ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_WORKFLOW = $IdWorkFlow ) USATO
                 ,(SELECT DISTINCT LIV FROM WFS.LEGAME_FLUSSI WHERE ID_FLU =  F.ID_FLU and ID_WORKFLOW = '$IdWorkFlow' ) LIV
                FROM 
                    WFS.FLUSSI F
                WHERE 1=1
                   AND ID_WORKFLOW = $IdWorkFlow
                   $sqladd
                ORDER BY LIV,FLUSSO";
            $res = $this->_db->getArrayByQuery($sql);
            /*echo "<pre>";
                print_r($res);
                echo "</pre>";*/
        }
        return $res;
        /*   } catch (Exception $e) {
            throw $e;
        }*/
    }

    public function getDipendenze($IdFlu)
    {
        /*  try {*/
        $sql = "SELECT 
                         ID_LEGAME
                        ,ID_WORKFLOW
                        ,PRIORITA
                        ,TIPO
                        ,ID_DIP
                        ,CASE L.TIPO 

                        WHEN 'F' THEN
                           ( SELECT FLUSSO FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
                        WHEN 'C' THEN
                          ( SELECT CARICAMENTO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                        WHEN 'L' THEN
                          ( SELECT LINK FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                        WHEN 'E' THEN
                          ( SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                        WHEN 'V' THEN
                          ( SELECT VALIDAZIONE FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
                        END AS DIPENDENZA
                        ,CASE L.TIPO 
                        WHEN 'F' THEN
                           ( SELECT DESCR FROM WFS.FLUSSI WHERE ID_FLU = L.ID_DIP )
                        WHEN 'C' THEN
                          ( SELECT DESCR FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                        WHEN 'L' THEN
                          ( SELECT DESCR FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                        WHEN 'E' THEN
                          ( SELECT DESCR FROM WFS.ELABORAZIONI WHERE ID_ELA = L.ID_DIP )
                        WHEN 'V' THEN
                          ( SELECT DESCR FROM WFS.VALIDAZIONI WHERE ID_VAL = L.ID_DIP )
                        END AS DESCR
                        ,CASE L.TIPO 
                        WHEN 'F' THEN
                           null
                        WHEN 'C' THEN
                          ( SELECT NOME_INPUT FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                          ||
                          ( SELECT ' - Conferma Dato: '||CONFERMA_DATO FROM WFS.CARICAMENTI WHERE ID_CAR = L.ID_DIP )
                        WHEN 'L' THEN
                          ( SELECT TARGET FROM WFS.LINKS WHERE ID_LINK = L.ID_DIP )
                        WHEN 'E' THEN
                        ( SELECT C.SHELL FROM WFS.ELABORAZIONI E , WORK_CORE.CORE_SH_ANAG C 
                          WHERE 1=1
                          AND E.ID_SH = C.ID_SH
                          AND E.ID_ELA = L.ID_DIP )
                        WHEN 'V' THEN
                          null
                        END AS DETT
                        ,CASE L.TIPO 
                        WHEN 'L' THEN
                          ( SELECT LS.TIPO FROM WFS.LINKS LS WHERE ID_LINK = L.ID_DIP )
                        ELSE
                          null
                        END AS TLINK    
                        ,CASE L.TIPO 
                        WHEN 'E' THEN
                          ( SELECT READONLY FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
                        ELSE
                          null
                        END AS READONLY         
                        ,CASE L.TIPO 
                        WHEN 'V' THEN
                          ( SELECT EXTERNAL FROM WFS.VALIDAZIONI E WHERE ID_VAL = L.ID_DIP )
                        ELSE
                          null
                        END AS EXTERNAL         
                        ,CASE L.TIPO 
                        WHEN 'E' THEN
                          ( SELECT SALTA_ELAB FROM WFS.ELABORAZIONI E WHERE ID_ELA = L.ID_DIP )
                        ELSE
                          null
                        END AS SALTA,
                        TO_CHAR(TMS_INSERT,'YYYYMM') TMS_INSERT,
                        INZ_VALID,
                        FIN_VALID   
                        FROM 
                            WFS.LEGAME_FLUSSI L
                        WHERE 1=1
                        AND ID_FLU = $IdFlu
                        ORDER BY 3, 4, 5";

        $res = $this->_db->getArrayByQuery($sql);

        return $res;
        /*  } catch (Exception $e) {
            throw $e;
        }*/
    }

    public function resetAutorun($ID_PROCESS)
    {
        /* try {*/
        $User = $_SESSION["codname"];
        $sql = "DELETE FROM WFS.PARAMETRI_ID_PROCESS where AMBITO=? and ID_PROCESS =?";
        $last_id = $this->_db->insertDb($sql, ['Autorun', $ID_PROCESS]);

        //  echo $this->_db->getsqlQuery();
        //  die();
        return $last_id;
        /*  } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }



    /**
     * addAutorun //IDPROCESS -  AMBITO = 'Aurorun' - Campo: On/Off    -   VALORE:ID_LEGAME
     *
     * @param  mixed $ID_PROCESS
     * @param  mixed $ID_LEGAME
     * @param  mixed $ATTIVO
     * @return void
     */
    public function addAutorun($ID_PROCESS, $ID_LEGAME, $ATTIVO)
    {
        /*try {*/
        $User = $_SESSION["codname"];
        $this->resetAutorun($ID_PROCESS);

        /*SetParametriIdProcess(
             I_ID_PROCESS  IN INTEGER
            ,I_AMBITO      IN VARCHAR2
            ,I_CAMPO       IN VARCHAR2
            ,I_VALORE       IN VARCHAR2
            ,I_USER        IN VARCHAR2
            ,O_ERROR       OUT VARCHAR2
            ,O_NOTE        OUT VARCHAR2
    ) ; */
        $Errore = 0;
        $Note = "";
        $User = $_SESSION["codname"];

        $CallPlSql = 'CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ? )';

        $values = [
            [
                'name' => 'I_ID_PROCESS',
                'value' => $ID_PROCESS,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_AMBITO',
                'value' => 'Autorun',
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_CAMPO',
                'value' => 'Attivo',
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_VALORE',
                'value' => $ATTIVO,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_USER',
                'value' => $User,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'O_ERROR',
                'value' => $Errore,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'O_NOTE',
                'value' => $Note,
                'type' => DB2_PARAM_OUT
            ],
        ];

        $res = $this->_db->callDb($CallPlSql, $values);


        $CallPlSql = 'CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ? )';

        $values = [
            [
                'name' => 'I_ID_PROCESS',
                'value' => $ID_PROCESS,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_AMBITO',
                'value' => 'Autorun',
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_CAMPO',
                'value' => 'IdEnd',
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_VALORE',
                'value' => $ID_LEGAME,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'I_USER',
                'value' => $User,
                'type' => DB2_PARAM_IN
            ],
            [
                'name' => 'O_ERROR',
                'value' => $Errore,
                'type' => DB2_PARAM_OUT
            ],
            [
                'name' => 'O_NOTE',
                'value' => $Note,
                'type' => DB2_PARAM_OUT
            ],
        ];

        $res = $this->_db->callDb($CallPlSql, $values);

        /*  $sql = "INSERT INTO WFS.PARAMETRI_ID_PROCESS (ID_PROCESS, AMBITO, CAMPO, VALORE, USER) VALUES (?,?,?,?,?)";
            $last_id = $this->_db->insertDb($sql, [$ID_PROCESS,'Autorun',$ATTIVO,$ID_LEGAME,$User]);
*/
        //   $this->_db->printSql();
        //  die();
        return $res;
        /*  } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }

    public function getIsAutorun($ID_PROCESS)
    {
        /*  try {*/
        $sql = "SELECT P.AMBITO ATTIVO FROM WFS.PARAMETRI_ID_PROCESS P                  
                        WHERE P.CAMPO = ? 
                        AND VALORE = ?
                        AND P.AMBITO = ?
                        AND P.ID_PROCESS = ?";




        $ret = $this->_db->getArrayByQuery($sql, ['Attivo', '1', 'Autorun', $ID_PROCESS]);
        //$this->_db->printSql();

        return ($ret[0]['ATTIVO']) ? 1 : 0;
        /*   } catch (Exception $e) {
            throw $e;
        }*/
    }





    public function getAutorun($ID_PROCESS, $ID_FLU = '', $ID_LEGAME = '')
    {
        /*  try {*/
        $sql = "SELECT P.VALORE ID_LEGAME , L.ID_FLU FROM WFS.PARAMETRI_ID_PROCESS P
                    LEFT JOIN WFS.LEGAME_FLUSSI L ON L.ID_LEGAME = P.VALORE
                        WHERE P.CAMPO = ?                       
                        AND P.AMBITO = ?
                        AND P.ID_PROCESS = ?";

        if ($ID_FLU) {
            $sql .= " AND  L.ID_FLU =" . $ID_FLU;
        }

        if ($ID_LEGAME) {
            $sql .= " AND  L.ID_LEGAME =" . $ID_LEGAME;
        }


        $ret = $this->_db->getArrayByQuery($sql, ['IdEnd', 'Autorun', $ID_PROCESS]);
        //$this->_db->printSql();

        return $ret;
        /*  } catch (Exception $e) {
            throw $e;
        }*/
    }

     public function svuotaCoda($ID_PROCESS)
    {
        /* try {*/
        $User = $_SESSION["codname"];
        $sql = "DELETE FROM WFS.CODA_RICHIESTE where ID_PROCESS =?";
        $ret = $this->_db->deleteDb($sql, [$ID_PROCESS]);

        //  echo $this->_db->getsqlQuery();
        //  die();
        return $ret;
        /*  } catch (Exception $e) {
            $this->_db->close_db();
            throw $e;
        }*/
    }
}
