<?php
require_once './library/xlsxtocsv/SimpleXLSX.php';
require_once './library/simplexlsxgen/SimpleXLSXGen.php';

use SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class relationtab_model
{
    // set database config for mysql
    // open mysql data base
    private $_db;

    public function __construct()
    {
        $this->_db = new db_driver();
    }

    public function getSchema()
    {
        try {
            $sql = "select DISTINCT TRIM(TABSCHEMA) TABSCHEMA from syscat.tables WHERE TABSCHEMA NOT LIKE 'SYS%' ORDER BY 1";
            $res = $this->_db->getArrayByQuery($sql);

            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getTable($Sel_Schema = '')
    {
        try {
            if ($Sel_Schema != "") {
                $sql = "select DISTINCT TABNAME, TYPE  from syscat.tables WHERE TYPE in ('T','V','N') AND TRIM(TABSCHEMA) = '$Sel_Schema' ORDER BY 1";

                $res = $this->_db->getArrayByQuery($sql, array($Sel_Schema));
            }
            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getSQLTable($Sel_Schema = '', $Sel_Table = '', $DATABASE = 'USER')
    {
        try {
            if ($Sel_Schema != "" and $Sel_Table != "") {


                $sql = "select a.TIPO,a.PATH,a.FILE, 
					CASE 
					  WHEN a.ID_SQL != 0 
					  THEN (SELECT MAX(START_TIME) FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL) 
					  ELSE null
					END LAST_RUN_SQL,
					CASE 
					  WHEN a.ID_SH != 0 OR a.ID_SQL != 0
					  THEN (SELECT MAX(START_TIME) FROM WORK_CORE.CORE_SH WHERE ID_SH = a.ID_SH )
					  ELSE null
					END LAST_RUN_SH,
					a.ID_SQL,
					a.ID_SH,
					CASE 
					WHEN a.ID_SQL != 0 
					THEN (SELECT SHELL FROM WORK_CORE.CORE_SH_ANAG WHERE ID_SH = (
					SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH in
					( SELECT ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL )
					))
					ELSE null
					END SHELL,
					CASE 
					WHEN a.ID_SQL != 0 
					  THEN (SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH in
					  ( SELECT ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL )
					  )
					  ELSE null
					END IDSHELL,
					CASE 
					  WHEN a.ID_SH != 0 OR a.ID_SQL != 0
					  THEN (SELECT MAX(START_TIME) FROM WORK_CORE.CORE_SH WHERE ID_SH = ((SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH in
					  ( SELECT ID_RUN_SH FROM WORK_CORE.CORE_DB WHERE ID_RUN_SQL = a.ID_SQL )
					  ))) 
					  ELSE null
					END LAST_RUN_SH_FATHER
					FROM(
					SELECT TIPO, PATH, FILE, 
					( SELECT MAX(ID_RUN_SQL) FROM WORK_CORE.CORE_DB WHERE FILE_SQL = TIF.PATH||'/'||TIF.FILE ) ID_SQL , 
					( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL_PATH = TIF.PATH AND SHELL = TIF.FILE ) ID_SH
                    FROM WORK_RULES.TABLES_IN_FILE TIF WHERE TRIM(SCHEMA) = '$Sel_Schema' AND TRIM(TABELLA) = '$Sel_Table' AND DATABASE = '$DATABASE' 
                    ) a
                    ORDER BY FILE";

                    $res = $this->_db->getArrayByQuery($sql);   
                     
                    return $res;
                }
            }
            catch(Exception $e)
            {
                throw $e;   
            }
            
        }           
        
        public function getPkgTable($Sel_Schema='', $Sel_Table='')
        {
            try {
                
                    $sql="SELECT  DISTINCT 
                    ROUTINESCHEMA as PACKAGE_SCHEMA, 
                    ROUTINEMODULENAME as PACKAGE
                    FROM SYSCAT.ROUTINEDEP a
                    where a.btype in ('N','T')
                      AND TRIM(BSCHEMA)= '$Sel_Schema'
                      AND TRIM(BNAME) = '$Sel_Table'  
                      AND ROUTINEMODULENAME IS NOT NULL
                    order by 1, 2
                    ";

            $res = $this->_db->getArrayByQuery($sql);

            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getViewTable($Sel_Schema = '', $Sel_Table = '')
    {
        try {

            $sql = "SELECT  DISTINCT 
                        'VIEW' as Tipo,
                        VIEWSCHEMA, 
                        VIEWNAME
                        FROM SYSCAT.VIEWS a
                        where TEXT like '%$Sel_Schema.$Sel_Table %'
                        order by 1, 2
                    ";

            $res = $this->_db->getArrayByQuery($sql);

            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //crea metodo creaFile($Sel_Schema, $Sel_Table); utilinnzando SimpleXLSX nel path ./TMP/, mettende l'intestazione torna il nome del file
    public function creaFile($Sel_Schema, $Sel_Table)
    {
        try {
            $datiTable = $this->getSQLTable($Sel_Schema, $Sel_Table);
            $header = array_keys($datiTable[0]);
            $arrayTable = [];
                foreach ($datiTable as $valore) {
                    $arrayTable[] = array_values($valore); // Assumendo che $valore sia un array associativo
                }
            $datiPkgTable = $this->getPkgTable($Sel_Schema, $Sel_Table);
            foreach ($datiPkgTable as $valore) {
                    $arrayTable[] = array_values($valore); // Assumendo che $valore sia un array associativo
                }
            $datiViewTable = $this->getViewTable($Sel_Schema, $Sel_Table);
            foreach ($datiViewTable as $valore) {
                    $arrayTable[] = array_values($valore); // Assumendo che $valore sia un array associativo
                }
            $filename = "./TMP/" . $Sel_Schema . '_' . $Sel_Table . "_" . date("YmdHis") . ".xlsx";
            $xlsx = new SimpleXLSXGen();           
            $xlsx->addSheet(array_merge([$header], $arrayTable),  $Sel_Schema . '.' . $Sel_Table );            
            $xlsx->saveAs($filename);
            return $filename;
        } catch (Exception $e) {
            throw $e;
        }
    }   
     


}
