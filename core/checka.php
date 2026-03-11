<?php

class checka extends helper
{

    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->include_css = '        
	   <style>' . file_get_contents("./view/CheckA4945/CSS/index.css") . '</style>  
       <script src="./view/CheckA4945/JS/index.js?p=' . rand(1000, 9999) . '"></script>';
        $this->_model = new checka_model();
        $this->setDebug_attivo(1);
    }


    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {
        $datiStatoElaborazioniDaIniziare = $this->_model->getStatoElaborazioniDaIniziare();
        $datiStatoElaborazioniInElaborazione = $this->_model->getStatoElaborazioniInElaborazione();
        $datiStatoElaborazioniInErrore = $this->_model->getStatoElaborazioniInErrore();
        $datiStatoElaborazioniFiniti = $this->_model->getStatoElaborazioniFiniti();
        $datiElaborazioniInCorso = $this->_model->getElaborazioniInCorso();
        $datiElaborazioniInErrore = $this->_model->getElaborazioniInErrore();
        include "view/CheckA4945/index.php";
    }
}