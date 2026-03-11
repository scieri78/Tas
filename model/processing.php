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
     * @return void
     */
    public function getDbName()
    {
        return $this->_db->getdb();
    }

    /**
     * getDataProcessing
     *
     * @param  mixed $_datiprocessing
     * @return void
     */
    public function getDataProcessing($_datiprocessing)
    {
        try {
            $sql = "SELECT * FROM WORK_CORE.CORE_PROCESSING";
            return $this->_db->getArrayByQuery($sql, array());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * saveDataProcessing
     *
     * @param  mixed $data
     * @return void
     */
    public function saveDataProcessing($data)
    {
        try {
            // Implementare la logica di salvataggio
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * deleteDataProcessing
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteDataProcessing($id)
    {
        try {
            $sql = "DELETE FROM WORK_CORE.CORE_PROCESSING WHERE ID = ?";
            $this->_db->deleteDb($sql, array($id));
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>
