<?php

class girorias extends helper
{
    protected $_tipo;
    protected $_retunVar;
    protected $_Sh_SHELL;

    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->_retunVar = 0;
        $this->_Sh_SHELL = "RIASS_Consolida.sh";
        $this->include_css = '
        
	   <style>
       ' . file_get_contents("./view/girorias/CSS/index.css") . '	  
       </style>  
        <script src="./view/girorias/JS/index.js?p=' . rand(1000, 9999) . '"></script>    
	   ';
        $this->_model = new girorias_model();
        $this->setDebug_attivo(1);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $_view['include_css'] = $this->include_css;
        $Tipo = $this->get_tipo();
        $IdLegame = $_POST['LinkIdLegame'];
        $Pagina = $_POST['LinkPagina'];
        $NameDip = $_POST['LinkNameDip'];
        $Bloccato = $_POST['LinkBloccato'];
        $EsitoDip = $_POST['LinkEsitoDip'];
        include "view/headerOpenLink.php";
        $this->contentList();
        include "view/footerOpenLink.php";
    }


    /**
     * contentList
     *
     * @return void
     */
    public function contentList()
    {
        $Tipo = $this->get_tipo();
        $return_var = $this->_retunVar;
        $IdWorkFlow = $_REQUEST['IdWorkFlow'];
        $IdProcess = $_POST['IdProcess'];
        $Action = $_POST['Action'];
        $User = $_SESSION['codname'];
        $Uk = $_SESSION['CodUk'];
        $IdPeriod = $_SESSION['IdPeriod'];
        $IdFlu = $_POST['IdFlu'];
        $NomeFlusso = $_POST['Flusso'];
        $DescFlusso = $_POST['DescFlusso'];
        $ProcMeseEsame = substr($IdPeriod, 4, 3);
        //$this->debug("post", $_POST);
        $datiGiroRIASViva = $this->_model->getGiroRIASViva($IdProcess, $Tipo);
        include "view/girorias/index.php";
    }

    /**
     * consolidaGiro
     *
     * @return void
     */
    public function consolidaGiro()
    {
        $Tipo = $this->get_tipo();
        $PRGDIR = $_SESSION['PRGDIR'];
        $SERVER = $_SESSION['SERVER'];
        $SSHUSR = $_SESSION['SSHUSR'];
        $DIRSH = $_SESSION['DIRSH'];
        $IdProcess = $_POST['IdProcess'];
        $SelCons = $_POST['SelCons'];
        $ConsFile = $_POST['ConsFile'];
        $Sh_DIRSH = "/area_staging_TAS/DIR_SHELL/MVBS";
        $Sh_SHELL = $this->get_Sh_SHELL();
        if ($SelCons) {
            $Sh_VARIABLES = "$SelCons $Tipo";
            exec("sh $PRGDIR/AvviaShellServer.sh '${IdProcess}' '${SSHUSR}' '${SERVER}' '$Sh_DIRSH' '$Sh_SHELL' '$Sh_VARIABLES' 2>&1 > $PRGDIR/AvviaShellServer.log", $output, $this->_retunVar);
        }
        $this->index();
    }

    /**
     * validaGiro
     *
     * @return void
     */
    public function validaGiro()
    {
        $this->_model->ValidaLegame();
        $this->index();
    }

    /**
     * Get the value of _tipo
     */
    public function get_tipo()
    {
        return $this->_tipo;
    }

    /**
     * Set the value of _tipo
     *
     * @return  self
     */
    public function set_tipo($_tipo)
    {
        $this->_tipo = $_tipo;

        return $this;
    }

    /**
     * Get the value of _Sh_SHELL
     */
    public function get_Sh_SHELL()
    {
        return $this->_Sh_SHELL;
    }

    /**
     * Set the value of _Sh_SHELL
     *
     * @return  self
     */
    public function set_Sh_SHELL($_Sh_SHELL)
    {
        $this->_Sh_SHELL = $_Sh_SHELL;

        return $this;
    }
}
