<?php
require_once("model/girorias.php");

class openLink_GiroRIAS2 extends girorias
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
       parent::__construct();
       $this->set_tipo('105-PREMIFUTURI');
       $this->set_Sh_SHELL("RIASS_Consolida.sh");
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

?>