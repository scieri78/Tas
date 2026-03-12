<?php
/**
 * Class processing_dati
 * Contiene i dati di processing
 */
class processing_dati
{
    const LAST_DAYS = 'LAST_DAYS';
    const LAST_3_DAYS = 'LAST_3_DAYS';
    const ALL_DAY = 'ALL_DAY';

    public $DB2database;
    private $_meseElab;
    private $_meseDiff;
    private $_limit;
    private $_idRunSh;
    private $_SelNumPage;
    private $_autoRefresh;
    private $_showDett;
    private $_noTags;
    private $_selShell;
    private $_selInDate;
    private $_numLast;
    private $_selEsito;
    private $_selEserMese;
    private $_selIdProc;
    private $_selAmbito;
    private $_viewFilter;
    
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
        $this->_autoRefresh = '';
        $this->_showDett = '';
        $this->_noTags = '';
        $this->_selShell = '';
        $this->_selInDate = self::LAST_DAYS;
        $this->_numLast = 100;
        $this->_selEsito = '';
        $this->_selEserMese = '';
        $this->_selIdProc = '';
        $this->_selAmbito = [];
        $this->_viewFilter = 'No';
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

    public function getAutoRefresh()
    {
        return $this->_autoRefresh;
    }

    public function setAutoRefresh($autoRefresh)
    {
        $this->_autoRefresh = $autoRefresh;
    }

    public function getShowDett()
    {
        return $this->_showDett;
    }

    public function setShowDett($showDett)
    {
        $this->_showDett = $showDett;
    }

    public function getNoTags()
    {
        return $this->_noTags;
    }

    public function setNoTags($noTags)
    {
        $this->_noTags = $noTags;
    }

    public function getSelShell()
    {
        return $this->_selShell;
    }

    public function setSelShell($selShell)
    {
        $this->_selShell = $selShell;
    }

    public function getSelInDate()
    {
        return $this->_selInDate;
    }

    public function setSelInDate($selInDate)
    {
        $this->_selInDate = $selInDate;
    }

    public function getNumLast()
    {
        return $this->_numLast;
    }

    public function setNumLast($numLast)
    {
        $this->_numLast = $numLast;
    }

    public function getSelEsito()
    {
        return $this->_selEsito;
    }

    public function setSelEsito($selEsito)
    {
        $this->_selEsito = $selEsito;
    }

    public function getSelEserMese()
    {
        return $this->_selEserMese;
    }

    public function setSelEserMese($selEserMese)
    {
        $this->_selEserMese = $selEserMese;
    }

    public function getSelIdProc()
    {
        return $this->_selIdProc;
    }

    public function setSelIdProc($selIdProc)
    {
        $this->_selIdProc = $selIdProc;
    }

    public function getSelAmbito()
    {
        return $this->_selAmbito;
    }

    public function setSelAmbito($selAmbito)
    {
        $this->_selAmbito = is_array($selAmbito) ? $selAmbito : [];
    }

    public function getViewFilter()
    {
        return $this->_viewFilter;
    }

    public function setViewFilter($viewFilter)
    {
        $this->_viewFilter = ($viewFilter === 'Si') ? 'Si' : 'No';
    }
}
?>
