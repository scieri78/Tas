<?php
require_once("model/girorias.php");

class openLink_GiroRIASInc extends girorias
{

    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
       parent::__construct();
       $this->set_tipo('INC');
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
