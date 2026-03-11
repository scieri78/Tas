<?php

/**
 * @property db_driver 2 $_db
 */
class configparametri_model
{
    private $_db;

    public function __construct()
    {
        $this->_db = new db_driver();
    }

    public function getWorkflows()
    {
        try {
            $sql = "SELECT id, name FROM workflows";
            return $this->_db->getArrayByQuery($sql);
        } catch (Exception $e) {
            // throw $e;
        }
    }

    public function getGruppiByWorkflow($id_workflow)
    {
        try {
            $sql = "SELECT ID_PAR_GRUPPO, LABEL FROM WFS.LINK_PARAMETRI_GRUPPO WHERE ID_WORKFLOW = ?";
            return $this->_db->getArrayByQuery($sql, [$id_workflow]);
        } catch (Exception $e) {
            // throw $e;
        }
    }

    public function getParametriByGruppo($id_par_gruppo)
    {
        try {
            $sql = "SELECT * FROM WFS.LINK_PARAMETRI LI
            INNER JOIN WFS.LINK_PARAMETRI_LEGAME LPL ON LPL.ID_PAR = LI.ID_PAR
            WHERE LPL.ID_PAR_GRUPPO = ?
            ORDER BY LPL.ORD";
            $ret = $this->_db->getArrayByQuery($sql, [$id_par_gruppo]);
            // $this->_db->error_message('getParametriByGruppo', $ret );
            return $ret;
        } catch (Exception $e) {
            // throw $e;
        }
    }

    public function getParametro($id_par)
    {
        try {
            $sql = "SELECT * FROM WFS.LINK_PARAMETRI LI
          
            WHERE LI.ID_par = ?";
            $ret = $this->_db->getArrayByQuery($sql, [$id_par]);
            //$this->_db->printSql();
            // $this->_db->error_message('getParametriByGruppo', $ret );
            return $ret;
        } catch (Exception $e) {
            // throw $e;
        }
    }

    public function addLinkParametri($data)
    {
        try {
            
            if ($data['ID_PAR']) {
                $retParametriLegami = $this->updateLinkParametri($data);
            } else {

                $maxIdQuery = "SELECT MAX(ID_PAR) AS max_id FROM WFS.LINK_PARAMETRI";
                $result = $this->_db->getArrayByQuery($maxIdQuery, []);
                $maxId = $result[0]['MAX_ID'] + 1;
                //  $this->_db->error_message("MAX(ID_PAR)",$result);
                //  $this->_db->printSql();
                // Aggiungi il nuovo ID_PAR_GRUPPO ai dati
                $data['ID_PAR'] = $maxId;


                $sql = "INSERT INTO WFS.LINK_PARAMETRI (ID_PAR,LABEL, NOME, DESC, TIPO_INPUT, LENGTH, PRECISION, SELECT, DEFAULT)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $retParametri = $this->_db->insertDb($sql, [
                    $data['ID_PAR'],
                    $data['LABEL'],
                    $data['NOME'],                   
                    $data['DESC'],
                    $data['TIPO_INPUT'],                  
                    $data['LENGTH'] ? $data['LENGTH'] : null,
                    $data['PRECISION'] ? $data['PRECISION'] : null,
                    $data['SELECT'],
                    $data['DEFAULT']
                ]);
                  $this->_db->printSql();
                $retParametriLegami = $this->addLinkParametriLegame($data);
            }
            return $retParametriLegami;
        } catch (Exception $e) {
            //throw $e;
        }
    }

    public function addLinkParametriGruppo($data)
    {
        try {
            // Ottieni il valore massimo di ID_PAR_GRUPPO e incrementalo di 1
            $maxIdQuery = "SELECT MAX(ID_PAR_GRUPPO) AS max_id FROM WFS.LINK_PARAMETRI_GRUPPO";
            $result = $this->_db->getArrayByQuery($maxIdQuery, []);
            $maxId = $result[0]['MAX_ID'] + 1;
            // $this->_db->error_message("MAX(ID_PAR_GRUPPO)",$result);
            // $this->_db->printSql();
            // Aggiungi il nuovo ID_PAR_GRUPPO ai dati
            $data['ID_PAR_GRUPPO'] = $maxId;

            // Prepara la query di inserimento
            $sql = "INSERT INTO WFS.LINK_PARAMETRI_GRUPPO (ID_WORKFLOW, LABEL, ID_PAR_GRUPPO) VALUES (?, ?, ?)";

            // Inserisci i dati nel database
            $ret = $this->_db->insertDb($sql, [$data['id_workflow'], $data['label'], $data['ID_PAR_GRUPPO']]);
            //   $this->_db->printSql();
            return $ret;
        } catch (Exception $e) {
            // Gestisci l'eccezione
            // throw $e; // Puoi decidere di rilanciare l'eccezione o gestirla in altro modo
        }
    }

    public function addLinkParametriLegame($data)
    {
        try {
            $maxIdQuery = "SELECT MAX(ORD) AS MAX_ID FROM WFS.LINK_PARAMETRI_LEGAME WHERE ID_PAR_GRUPPO = ?";
            $result = $this->_db->getArrayByQuery($maxIdQuery, [$data['ID_PAR_GRUPPO']]);
            $maxId = $result[0]['MAX_ID'] + 1;
            //  $this->_db->error_message("MAX(ORD)",$result);
            //  $this->_db->printSql();
            // Aggiungi il nuovo ID_PAR_GRUPPO ai dati
            $data['ORD'] = $maxId;
            $sql = "INSERT INTO WFS.LINK_PARAMETRI_LEGAME (ID_PAR_GRUPPO, ID_PAR, ORD, TMS_INSERT) VALUES (?, ?, ?, CURRENT_TIMESTAMP);";

            $res = $this->_db->insertDb($sql, [
                $data['ID_PAR_GRUPPO'],
                $data['ID_PAR'],
                $data['ORD']
            ]);
            $this->_db->printSql();
            return $res;
        } catch (Exception $e) {
            //  throw $e;
        }
    }

    public function removeLinkParametri($id_par, $id_par_gruppo)
    {
        try {
            $sql = "DELETE FROM WFS.LINK_PARAMETRI_LEGAME WHERE ID_PAR = ? and ID_PAR_GRUPPO = ?";
            return $this->_db->deleteDb($sql, [$id_par, $id_par_gruppo]);
        } catch (Exception $e) {
            //  throw $e;
        }
    }

    public function removeLinkParametriGruppo($id_par_gruppo, $id_workflow)
    {
        try {
            $sql = "DELETE FROM WFS.LINK_PARAMETRI_GRUPPO WHERE ID_PAR_GRUPPO = ? AND ID_WORKFLOW = ?";
            return $this->_db->deleteDb($sql, [$id_par_gruppo, $id_workflow]);
        } catch (Exception $e) {
            //  throw $e;
        }
    }

    public function updateLinkParametri($data)
    {
        try {
            $sql = "UPDATE WFS.LINK_PARAMETRI 
        SET LABEL = ?, 
            NOME = ?, 
            DESC = ?, 
            TIPO_INPUT = ?,
            LENGTH = ?, 
            PRECISION = ?, 
            SELECT = ?, 
            DEFAULT = ?
        WHERE ID_PAR = ?;";

            $retParametri = $this->_db->updateDb($sql, [
                $data['LABEL'],
                $data['NOME'],
                $data['DESC'],
                $data['TIPO_INPUT'],
                $data['LENGTH'] ? $data['LENGTH'] : null,
                $data['PRECISION'] ? $data['PRECISION'] : null,
                $data['SELECT'],
                $data['DEFAULT'],
                $data['ID_PAR'] // Assuming ID_PAR is the unique identifier for the row
            ]);
            return $retParametri;
        } catch (Exception $e) {
            //  throw $e;
        }
    }
}
