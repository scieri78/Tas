<?php
require_once("model/girorias.php");

class openLink_GiroRIASViva000 extends girorias
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_tipo('000-COMPETENZA');
        $this->set_Sh_SHELL("RIASS_Viva_Consolida.sh");
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        parent::index();
    }
}
