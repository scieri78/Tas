<?php
/** 
 * @property pendingtab_model $_model
 */
class pendingtab extends helper
{
   
    
    function __construct()
    {
       $this->include_css = '	  
	   <script src="./view/pendingtab/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new pendingtab_model();
       $this->setDebug_attivo(1);
    }
 
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";
      $this->contentList();
	  include "view/footer.php";
    }
   
    public function contentList()
	{
		$Sel_Schema=$_POST['Sel_Schema'];
		$Sel_Table=$_POST['Sel_Table'];
		$Rimuovi=($Sel_Schema && $Sel_Table)?1:0;	
		$DATABASE =$_SESSION['DATABASE'];
		$SSHUSR =$_SESSION['SSHUSR'];
		$SERVER =$_SESSION['SERVER'];
		$PRGDIR =$_SESSION['PRGDIR'];
		$SelTab="${Sel_Schema}.${Sel_Table}";
		$datiTabschema=$this->_model->getTabschema();
		$datiTables=$this->_model->getTables($Sel_Schema);
        include "view/pendingtab/index.php";
		if ($Rimuovi){
			$message="Eseguo rimozione pending: $SelTab";
			$this->get_info_message($message);	
			shell_exec("sh $PRGDIR/AvviaPendServer.sh '${DATABASE}' '${SSHUSR}' '${SERVER}' '$SelTab'");
		}
      
    }
}


?>