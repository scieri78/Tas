<?php
/** 
 * @property chiusura_model $_model
 */
class chiusura extends helper
{    
    
    function __construct()
    {
       $this->include_css = '
	   <link rel="stylesheet" href="./CSS/StatoReti.css?p='.rand(1000,9999).'" />
		<link rel="stylesheet" href="./CSS/index.css?p='.rand(1000,9999).'" />
		<link rel="stylesheet" href="./CSS/mainmenu.css?p='.rand(1000,9999).'" />
		<link rel="stylesheet" href="./CSS/excel.css?p='.rand(1000,9999).'" />
	   <link rel="stylesheet" href="./view/chiusura/CSS/index.css?p='.rand(1000,9999).'" />
	   <script src="./view/chiusura/JS/index.js?p='.rand(1000,9999).'"></script>
	   ';
	   $this->_model =  new chiusura_model();
       $this->setDebug_attivo(1);
	   
    }
 
    public function index()
    { 
	  $_view['include_css'] = $this->include_css; 
	  include "view/header.php";   
      $this->contentList();
	  include "view/footer.php";
    }
 
   
    public function contentList(){
		
		$TABSHOW=$_POST['TABSHOW'];  
		if ($TABSHOW == "TUTTI" ){
		   $ClsCand="Down";
		   $ClsTutti="Up";	
		   }else {
			$ClsCand="Up";
		    $ClsTutti="Down";
			}   
		$ArrAZ=array();
		$ArrAZV=array('LoB_4','LoB_5','LoB_11');
		$ArrCRD=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
		$ArrGNL=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
        $ArrAZN=array('LoB_4','LoB_5','LoB_11');
		$ArrAZD=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
		$ArrUAA=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
        			
	    $TestRest= $this->_model->getTestRest();
	    $datiCompagnia = $this->_model->getLoadInfo('Compagnia');
	    $DatiLineOfBusiness = $this->_model->getLineOfBusiness();
	    $datiCaricamenti = $this->_model->getCaricamenti($TABSHOW);
	    $datiCandidatoDefinitivo = $this->_model->getCandidatoDefinitivo();
	    $datiCuboApp = $this->_model->getCuboApp();
	    $datiGiroSolvency = $this->_model->getGiroSolvency();
	    $datiCuboSolvency = $this->_model->getCuboSolvency();
		
		
		 $Arr_CodComp=array();
    foreach ($datiCompagnia as $row) {
         $CodComp=$row['VALORE'];
         array_push($Arr_CodComp,$CodComp);
    }


    $Arr_Lob=array();
    foreach ($DatiLineOfBusiness as $row) {
         $Lob=$row['VALORE'];
         array_push($Arr_Lob,$Lob);
    }
 
    $Arr_Run=array();
    foreach ($datiCaricamenti as $row) {
         $Comp=$row['COMPAGNIA'];
         $Lob=$row['LINEOFBUSINESS'];
         $Status=$row['STATUS'];
         $IdInfo=$row['ID_INFO'];
         $Desc=$row['DESCRIZIONE'];
         $File=$row['FILEOUTPUTUFFICIALE1'];
         $Zonta=$row['FILEOUTPUTUFFICIALE2'];
         $DtInfo=$row['DATA_INFO'];
         $ShName=$row['SHELL_NAME'];
         $ShStart=$row['SHELL_START'];
         $ShEnd=$row['SHELL_END'];
         $ShStatus=$row['SHELL_STATUS'];
         $ShDiff=$row['DIFF'];
         $Vers=$row['VERSIONEUTENTE'];
         $CntVers=$row['CNTVERS'];
         $Cand=$row['CAND'];
         $CandCont=$row['CAND_CONT'];
         $IdRunSh=$row['ID_RUN_SH'];         
         $Wait=$row['WAIT'];

         array_push($Arr_Run,array($Comp,$Lob,$Status,$IdInfo,$Desc,$File,$Zonta,$DtInfo,$ShName,$ShStart,$ShEnd,$ShStatus,$ShDiff,$Vers,$CntVers,$Cand,$IdRunSh,$Wait,$CandCont));
    } 
	
	
	
		
		
        include "view/chiusura/index.php";
      
    }
    public function StatoShell(){
       // $array_result =  $result=$this->objsm->fetchAssoc(0);
      //  $this->debug("res", $array_result);
      //  $this->log_message("prova_log",$array_result);
        include "view/chiusura/StatoShell.php";
      
    }
}



?>