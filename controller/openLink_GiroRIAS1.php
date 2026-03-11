<?php
require_once("model/girorias.php");
use girorias;
class openLink_GiroRIAS1 extends girorias
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
       parent::__construct();
       $this->set_tipo('ALL');
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
