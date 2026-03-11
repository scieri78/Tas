<?php
/**
 * Class processing_dati
 * Contiene i dati di processing
 */
class processing_dati
{
    public $DB2database;
    private $_meseElab;
    private $_meseDiff;
    private $_limit;
    private $_idRunSh;
    private $_SelNumPage;
    
    /**
     * __construct
     */
    public function __construct()
    {
        $this->DB2database = '';
        $this->_meseElab = '';
        $this->_meseDiff = '';
        $this->_limit = 100;
        $this->_idRunSh = '';
        $this->_SelNumPage = 1;
    }
    
    /**
     * getMeseElab
     *
     * @return string
     */
    public function getMeseElab()
    {
        return $this->_meseElab;
    }
    
    /**
     * setMeseElab
     *
     * @param  string $meseElab
     * @return void
     */
    public function setMeseElab($meseElab)
    {
        $this->_meseElab = $meseElab;
    }
    
    /**
     * getMeseDiff
     *
     * @return string
     */
    public function getMeseDiff()
    {
        return $this->_meseDiff;
    }
    
    /**
     * setMeseDiff
     *
     * @param  string $meseDiff
     * @return void
     */
    public function setMeseDiff($meseDiff)
    {
        $this->_meseDiff = $meseDiff;
    }
    
    /**
     * getLimit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }
    
    /**
     * setLimit
     *
     * @param  int $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }
    
    /**
     * getIdRunSh
     *
     * @return string
     */
    public function getIdRunSh()
    {
        return $this->_idRunSh;
    }
    
    /**
     * setIdRunSh
     *
     * @param  string $idRunSh
     * @return void
     */
    public function setIdRunSh($idRunSh)
    {
        $this->_idRunSh = $idRunSh;
    }
    
    /**
     * getSelNumPage
     *
     * @return int
     */
    public function getSelNumPage()
    {
        return $this->_SelNumPage;
    }
    
    /**
     * setSelNumPage
     *
     * @param  int $selNumPage
     * @return void
     */
    public function setSelNumPage($selNumPage)
    {
        $this->_SelNumPage = $selNumPage;
    }
}
?>
