<?php
/** 
 * @property tickets_model $_model
 */
class tickets extends helper {
   private  $numTTRisolti= 8;
     function __construct()
    {
        $this->include_css = '
	   <link rel="stylesheet" href="./view/tickets/CSS/index.css?p=' . rand(1000, 9999) . '" />';
        $this->_model = new tickets_model();
 
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
        include "view/header.php";
        
        $this->contentList();
        include "view/footer.php";
    }



function getMenuArray() {
    $result = [];

    foreach ($_SESSION['MENU'] as $menu) {
        // Controlla se il menu principale non ha sottomenu
        if (!isset($menu['SMENU']) || !is_array($menu['SMENU'])) {
            $result[] = $menu['DESC'];
        } else {
            // Se ha sottomenu, aggiungi solo i sottomenu con il prefisso del menu principale
            foreach ($menu['SMENU'] as $submenu) {
                $result[] = $menu['DESC'] . ' => ' . $submenu['DESC'];
            }
        }
    }

    return $result;
}
	    
    /**
     * contentList
     *
     * @return void
     */
    public function contentList() {            
        $ttassegnato = $_SESSION['TTASSEGATO']?$_SESSION['TTASSEGATO']:"";
        $ttuser = $_SESSION['TTUSER']?$_SESSION['TTUSER']:'';
        $ttuserassegnato = $_SESSION['TTUSERASSEGNATO']?$_SESSION['TTUSERASSEGNATO']:'';
        $ttposizione = $_SESSION['TTPOSIZIONE']?$_SESSION['TTPOSIZIONE']:'';
        if($this->is_admin())
        {
        $tickets = $this->_model->getAll($this->numTTRisolti,$ttassegnato,$ttuser,$ttuserassegnato,$ttposizione);
        }else{
           $tickets = $this->_model->myTicket(); 
        }
        $is_admin =$this->is_admin();
        $utentiTT = $this->_model->getUtentiTT();
        $assegnatoList = $this->_model->getUtentiAssegnati();
        $datiPosizione = $this->getMenuArray();
       // $this->debug('',$_SESSION);
        include  'view/tickets/ticket_kanban.php';
    }    

     public function ttassegnato() {    
        $_SESSION['TTASSEGATO'] = ($_POST['TTASSEGATO'])?$_POST['TTASSEGATO']:0;
        //$this->contentList();
    } 


      public function ttuser() {    
        $_SESSION['TTUSER'] = ($_POST['TTUSER'])?$_POST['TTUSER']:"";
        //$this->contentList();
    } 

     public function ttuserassegnato() {    
        $_SESSION['TTUSERASSEGNATO'] = ($_POST['TTUSERASSEGNATO'])?$_POST['TTUSERASSEGNATO']:"";
        //$this->contentList();
    } 

      public function ttposizione() {    
        $_SESSION['TTPOSIZIONE'] = ($_POST['TTPOSIZIONE'])?$_POST['TTPOSIZIONE']:"";
        //$this->contentList();
    } 
    /**
     * formTicket
     *
     * @return void
     */
    public function formTicket() {
        $id =$_POST['id'];
        $ticket = $this->_model->getById($id);
         $is_admin =$this->is_admin();     
        $posizione = $this->getMenuArray();
       // $this->debug("ticket",$ticket);
       // $images = $this->_model->getImages($id);
        include 'view/tickets/ticket_form.php';
    }    
    /**
     * create
     *
     * @param  mixed $titolo
     * @param  mixed $descrizione
     * @return void
     */
    public function create() {
        foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }
        $this->_model->create($posizione,$titolo, $descrizione,$stato,$tipo,$priorita,$assegnato,$valida);
		//$this->contentList();
       // header('Location: index.php?action=kanban');
    }
        
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $titolo
     * @param  mixed $descrizione
     * @param  mixed $stato
     * @return void
     */
    public function update() {
         foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }
       
        $this->_model->update($id,$posizione, $titolo, $descrizione, $stato,$tipo,$priorita,$assegnato,$valida);
		$this->contentList();
       // header('Location: index.php?action=kanban');
    }
    
    /**
     * updateStato
     *
     * @param  mixed $id
     * @param  mixed $stato
     * @return void
     */
    public function updateStato() {
        if($this->is_admin()){
        foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }
        $this->_model->updateStato($id, $stato);
        }
		$this->contentList();
       // header('Location: index.php?action=kanban');
    }

        
    /**
     * delTicket
     *
     * @return void
     */
    public function delTicket() {
        if($this->is_admin()){
        $id = $_POST['id'];
        $this->_model->delTicket($id);
        }
		$this->contentList();
       // header('Location: index.php?action=kanban');
    }

    


}
?>