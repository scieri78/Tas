<?php

class reti2_model
{

    // set database config for mysql

    // open mysql data base
    public $_db;
    /**
     * {@inheritDoc}
     * @see db_driver::__construct()
     */
    public function __construct()
    {
        $this->_db = new db_driver();
    }

    public function getDataElab()
    {
        $sql = "SELECT DISTINCT MAX(DATE(LAST_DAY(DATE(ESER_ESAME||'-'||LPAD(MESE_ESAME,2,0)||'-01')))) DATAELAB
        FROM WORK_CORE.CORE_SH 
        WHERE 1=1
        AND ESER_ESAME is not null
        AND MESE_ESAME is not null
        AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = '/area_staging_TAS/RETI_TWS')
        ";
        $res = $this->_db->getArrayByQuery($sql);

        return $res;
    }

    public function getSelectElab()
    {

        $sql = "SELECT DISTINCT DATE(LAST_DAY(DATE(ESER_ESAME||'-'||LPAD(MESE_ESAME,2,0)||'-01'))) DATAELAB
    FROM WORK_CORE.CORE_SH 
    WHERE 1=1
    AND ESER_ESAME is not null
    AND MESE_ESAME is not null
    AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = '/area_staging_TAS/RETI_TWS')
    ORDER BY 1 DESC";

        $res = $this->_db->getArrayByQuery($sql);

        return $res;
    }


    public function getNowEserMese($ListId)
    {

        $sql="SELECT DISTINCT ESER_ESAME||'-'||LPAD(MESE_ESAME,2,0) NOW_ESERMESE FROM WORK_CORE.CORE_SH WHERE  ID_RUN_SH IN ( $ListId 0 ) ORDER BY 1";

        $res = $this->_db->getArrayByQuery($sql);

        return $res;
    }
    public function getIdRunSh($ListId, $DataElab)
    {

        $sql="SELECT ID_RUN_SH FROM WORK_CORE.CORE_SH  WHERE  MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab') AND  ID_RUN_SH IN ( $ListId 0 ) 
        AND START_TIME >= (
            SELECT MAX(END_TIME) END_TIME FROM WORK_CORE.CORE_SH  WHERE  MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab') AND  ID_RUN_SH IN ( $ListId 0 ) AND 
            ID_SH = ( SELECT C.ID_SH 
                        FROM
                            WORK_CORE.CORE_RETI_TWS  CRT, 
                            WORK_CORE.CORE_SH C 
                        WHERE 1=1
                            AND CRT.ENABLE = 'Y'
                            AND C.ID_SH = CRT.ID_SH 
                            AND MESE_ESAME = INT(MONTH('$DataElab')) AND ESER_ESAME = YEAR('$DataElab')
                            AND ID_RUN_SH IN ( $ListId 0 ) 
                            AND PASSO_ORD = (
                                SELECT MAX(PASSO_ORD) 
                                FROM 
                                    WORK_CORE.CORE_RETI_TWS CRT, 
                                    WORK_CORE.CORE_SH C 
                                WHERE 1=1
                                    AND CRT.ENABLE = 'Y'
                                    AND C.ID_SH = CRT.ID_SH 
                                    AND ID_RUN_SH IN ( $ListId 0 )
                                    AND C.MESE_ESAME = INT(MONTH('$DataElab')) AND C.ESER_ESAME = YEAR('$DataElab')
                            )
                    )
        )
        "; 

        $res = $this->_db->getArrayByQuery($sql);

        return $res;
    }

    public function getCTNReti($ListId, $ListOldId, $DataElab)
    {

        $sql="SELECT A.*    
        ,timestampdiff(2,
        NVL(TO_DATE(A.NOWMAX,'YYYY-MM-DD HH24:MI:SS'),CURRENT_TIMESTAMP)
           -TO_DATE(A.NOWMIN,'YYYY-MM-DD HH24:MI:SS')
        ) DIFF
        ,timestampdiff(2, TO_DATE(A.OLDMAX ,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(A.OLDMIN,'YYYY-MM-DD HH24:MI:SS') )  OLDDIFF
      FROM (
        SELECT 
         ( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId $ListOldId 0 ) ) 
         WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) < YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_N
        ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS = 'I' 
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_I
        ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS IN ( 'F', 'M' )
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_F
        ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS IN ( 'W' )
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_W
        ,( SELECT count(*)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) ) WHERE STATUS = 'E' 
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) ) CNT_E
        ,TO_CHAR(( SELECT MIN(START_TIME)  FROM (  SELECT * FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 ) 
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) 
        ) ),'YYYY-MM-DD HH24:MI:SS') NOWMIN
        ,TO_CHAR(( SELECT MAX(NVL(END_TIME,CURRENT_TIMESTAMP))  FROM (  SELECT END_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListId 0 )
           AND ESER_ESAME||LPAD(MESE_ESAME,2,0) = YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0) 
          ) ),'YYYY-MM-DD HH24:MI:SS') NOWMAX
        ,TO_CHAR(( SELECT MIN(START_TIME)  FROM (  SELECT START_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListOldId 0 ) 
             ) ),'YYYY-MM-DD HH24:MI:SS') OLDMIN
        ,TO_CHAR(( SELECT MAX(NVL(END_TIME,CURRENT_TIMESTAMP))  FROM (  SELECT END_TIME FROM  WORK_CORE.CORE_SH   WHERE  ID_RUN_SH IN ( $ListOldId 0 )
             ) ),'YYYY-MM-DD HH24:MI:SS') OLDMAX
        FROM DUAL  
      ) A
      ";  

        $res = $this->_db->getArrayByQuery($sql);

        return $res;
    }

    public function getReti($DataElab)
    {

        $sql="SELECT A.*,
        timestampdiff(2,TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS')) AS OLD_DIFF 
        ,TO_CHAR(TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS'),'YYYY-MM-DD HH24:MI:SS') AS OLD_DATE
        ,TO_CHAR(TO_DATE(END_TIME,'YYYY-MM-DD HH24:MI:SS'),'YYYY-MM-DD HH24:MI:SS') AS NOW_DATE 
        ,TO_CHAR(CURRENT_TIMESTAMP,'YYYY-MM-DD HH24:MI:SS')                         AS OGGI
        ,TO_CHAR(TO_DATE(END_TIME,'YYYY-MM-DD HH24:MI:SS'),'YYYYMM')                AS OLD_ESER_MESE 
        ,TO_CHAR(ADD_SECONDS(A.START_TIME,
        timestampdiff(2,TO_DATE(OLD_FINE,'YYYY-MM-DD HH24:MI:SS')-TO_DATE(OLD_INIZIO,'YYYY-MM-DD HH24:MI:SS'))
        ),'YYYY-MM-DD HH24:MI:SS')          AS PRWEND
        ,(
        SELECT MIN(START_TIME) 
        FROM WORK_CORE.CORE_SH S
        WHERE 1=1
          AND S.ID_SH IN ( SELECT T.ID_SH FROM WORK_CORE.CORE_RETI_TWS T WHERE T.RETE= A.RETE ) 
          AND S.ESER_ESAME = YEAR('$DataElab')
          AND S.MESE_ESAME = MONTH('$DataElab')
         ) START_RETE
    FROM (
        SELECT B.*
            ,(SELECT  TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS')  START_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH_OLD ) OLD_INIZIO
            ,(SELECT  TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS')    END_TIME   FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH_OLD ) OLD_FINE
        FROM (
            SELECT C.*
             ,
                ( SELECT 
                        MAX(ID_RUN_SH) 
                    FROM 
                        WORK_CORE.CORE_SH H
                    WHERE 1=1
                        AND ID_SH = C.ID_SH
                        AND STATUS IN ('W','F')
                        AND H.ESER_ESAME||LPAD(H.MESE_ESAME,2,0) < YEAR('$DataElab')||LPAD(MONTH('$DataElab'),2,0)
                 ) ID_RUN_SH_OLD  
              ,  NVL( (SELECT  REPLACE(substr(O.OLD_SH,1,5),'.sh','') FROM WORK_CORE.CORE_RETI_TWS_OLD O WHERE O.NEW_SH = C.PASSO||'.sh' ), '-') OLDNAMERETE
              ,  NVL( (SELECT  REPLACE(O.OLD_SH,'.sh','')             FROM WORK_CORE.CORE_RETI_TWS_OLD O WHERE O.NEW_SH = C.PASSO||'.sh' ), '-') OLDNAMEPASSO          
            FROM (
            SELECT 
                T.RETE, 
                REPLACE( 
                (   SELECT 
                        SHELL 
                    FROM 
                        WORK_CORE.CORE_SH_ANAG 
                    WHERE 
                        ID_SH = T.ID_SH 
                        AND ENABLE = 'Y' ),'.sh','') PASSO, 
                S.STATUS, 
                S.ESER_ESAME||'-'||LPAD(S.MESE_ESAME,2,0)                       TIMEELAB, 
                TO_CHAR(S.START_TIME,'YYYY-MM-DD HH24:MI:SS')                   START_TIME, 
                TO_CHAR(S.END_TIME,'YYYY-MM-DD HH24:MI:SS')                     END_TIME, 
                timestampdiff(2,NVL(S.END_TIME,CURRENT_TIMESTAMP)-S.START_TIME) SEC_DIFF, 
                S.ID_RUN_SH,
                RETE_ORD,
                PASSO_ORD,
                S.ID_SH
            FROM 
                WORK_CORE.CORE_RETI_TWS T 
            LEFT JOIN 
                WORK_CORE.CORE_SH S 
            ON 
                S.ID_SH = T.ID_SH 
            AND S.ID_SH IN 
                (   SELECT 
                        ID_SH 
                    FROM 
                        WORK_CORE.CORE_SH_ANAG 
                    WHERE 
                        VALID = 'Y' ) 
            AND ID_RUN_SH = 
                (   SELECT 
                        MAX(ID_RUN_SH) 
                    FROM 
                        WORK_CORE.CORE_SH H
                    WHERE 
                        ID_SH = S.ID_SH 
                        AND H.ESER_ESAME||LPAD(H.MESE_ESAME,2,0) <= YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),2,0)
                        ) 
            WHERE T.ENABLE = 'Y'
            AND S.ESER_ESAME||LPAD(S.MESE_ESAME,2,0) <= YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),2,0)
            AND S.ID_SH not in ( SELECT ID_SH FROM  WORK_CORE.CORE_PASSI_SOSPESI WHERE ESER_MESE < YEAR('$DataElab')||LPAD(INT(MONTH('$DataElab')),3,0)  )
            ) C
       ) B
    ) A
    ORDER BY
        START_RETE DESC,
        PASSO_ORD DESC";   

     
        $res = $this->_db->getArrayByQuery($sql);
        /*echo $this->_db->getsqlQuery();
				die();*/
        return $res;
    }
    
        
    /**
     * callTrueOldTime
     *
     * @param  mixed $RETE
     * @param  mixed $ListOldId
     * @param  mixed $ListDone
     * @param  mixed $OldTrueTime
     * @return void
     */
    public function callTrueOldTime($RETE, $ListOldId, $ListDone, $OldTrueTime)
    {
        try
        {
         //   echo "callTrueOldTime";
        $CallPlSql = 'CALL WORK_CORE.K_TRUE_TIME.TrueTime(?, ?, ?, ?)';
       // $stmt = db2_prepare($conn, $Sql);

      //  $values = array($RETE, $ListOldId, $ListDone, $TrueTime);
        /*
      db2_bind_param($stmt, 1,  "RETE"       , DB2_PARAM_IN);
      db2_bind_param($stmt, 2,  "ListOldId"  , DB2_PARAM_IN);
      db2_bind_param($stmt, 3,  "ListDone"   , DB2_PARAM_IN);
      db2_bind_param($stmt, 4,  "OldTrueTime", DB2_PARAM_OUT);
      */

      $values = [
        [
       'name' => 'RETE',
       'value' => $RETE,
       'type' => DB2_PARAM_IN
       ],
        [
       'name' => 'ListOldId',
       'value' => $ListOldId,
       'type' => DB2_PARAM_IN
       ],[
       'name' => 'ListDone',
       'value' => $ListDone,
       'type' => DB2_PARAM_IN
       ],
       [
       'name' => 'OldTrueTime',
       'value' => $OldTrueTime,
       'type' => DB2_PARAM_OUT
       ]
       ];	

    

    $ret = $this->_db->callDb($CallPlSql ,$values);
  //  echo " OldTrueTime:".$ret['OldTrueTime'];
    $OldTrueTime=$ret['OldTrueTime'];
      
        return $OldTrueTime;
    }
    catch (Throwable $e) {
      //  $this->_db->close_db();
        throw $e;
        
    }
    catch (Exception $e) 
    {
        $this->_db->close_db();
        throw $e;
    }

    }

            
    /**
     * callTrueTime
     *
     * @param  mixed $RETE
     * @param  mixed $LisIdT
     * @param  mixed $ListDone
     * @param  mixed $TrueTime
     * @return void
     */
    public function callTrueTime($RETE, $LisIdT, $ListDone, $TrueTime)
    {
        $CallPlSql = 'CALL WORK_CORE.K_TRUE_TIME.TrueTime(?, ?, ?, ?)';
       // $stmt = db2_prepare($conn, $Sql);

      //  $values = array($RETE, $ListOldId, $ListDone, $TrueTime);


       /* db2_bind_param($stmt,1,"RETE"     , DB2_PARAM_IN);
      db2_bind_param($stmt,2,"LisIdT", DB2_PARAM_IN);
      db2_bind_param($stmt,3,"ListDone" , DB2_PARAM_IN);
      db2_bind_param($stmt,4,"TrueTime" , DB2_PARAM_OUT);*/

      $values = [
        [
       'name' => 'RETE',
       'value' => $RETE,
       'type' => DB2_PARAM_IN
       ],
        [
       'name' => 'LisIdT',
       'value' => $LisIdT,
       'type' => DB2_PARAM_IN
       ],[
       'name' => 'ListDone',
       'value' => $ListDone,
       'type' => DB2_PARAM_IN
       ],
       [
       'name' => 'TrueTime',
       'value' => $TrueTime,
       'type' => DB2_PARAM_OUT
       ]
       ];	


    $ret = $this->_db->callDb($CallPlSql ,$values);
    
    $TrueTime=$ret['TrueTime'];
      
    return $TrueTime;
       
    }
}
