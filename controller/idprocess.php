<?php
/** 
 * @property idprocess_model $_model
 */
class idprocess extends helper
{
  
    private $typesh;
    
    function __construct()
    {
		$this->include_css = '
			<link rel="stylesheet" href="./view/idprocess/CSS/index.css?p='.rand(1000,9999).'">
			';
       $this->_model = new idprocess_model();
	   $this->setDebug_attivo(1);
	   
   /* if ( $AddMail != "" ){
		$this->addMail($AddMail);
    }*/
	}
	
    // mvc handler request
    public function index()
    {
		$_view['include_css'] = $this->include_css; 
		include "view/header.php";
		$this->contentList();
		include "view/footer.php";
    } 
	
	public function contentList()
    {
	$this->get_errors_message();
	//$this->debug("Sessione",$_SESSION);
	$Azione=$_POST['Azione'];
    $IdWorkFlow=$_POST['IdWorkFlow']?$_POST['IdWorkFlow']:$_SESSION['IdWorkFlow'];
	$IdTeam=$_POST['IdTeam']?$_POST['IdTeam']:$_SESSION['IdTeam'];
	$FromId=$_POST['FromId'];
	$ToId=$_POST['ToId'];
    $ShowStatusCopy=$_POST['ShowStatusCopy'];
	$RemoveRO=$_POST['RemoveRO'];
	$AddRO=$_POST['AddRO'];
	$AddSvecc=$_POST['AddSvecc'];
	$RemSvecc=$_POST['RemSvecc'];	
	
	$this->_model->AzioneUatoDip();
	$DatiTeams = $this->_model->getTeams();
	$DatiWorkFlow = $this->_model->getWorkFlow($IdTeam);
	include "view/idprocess/index.php";
	
    }
	
	public function addidprocess()
    {
	$this->get_errors_message();
	$IdWorkFlow=$_POST["IdWorkFlow"];
	$SelIdTeam=$_POST["IdTeam"];
	$SelFromId=$_POST["FromId"];
	$SelToId=$_POST["ToId"];
	$ShowStatusCopy=$_POST['ShowStatusCopy'];
	
	$DatiFrequenza = $this->_model->getFrequenza($IdWorkFlow);
	//$this->debug("DatiFrequenza",$DatiFrequenza);
	$type = $DatiFrequenza[0]['FREQUENZA'];
	$optiondate = $this->generateDateOptions($type);
	$DatiIdProc = $this->_model->getIdProc();
	$ArrSvecIdP=[];
	foreach ($DatiIdProc as $row) {
    $IProc=$row['ID_PROCESS']; 
	array_push($ArrSvecIdP,$IProc);
}
	
	
	$DatiListaIdProc = $this->_model->getListaIdProc($IdWorkFlow, $SelIdTeam);
	
	//$DatiEleborazioni = $this->_model->setShWfs($IdSh,$flag);
	include "view/idprocess/addidprocess.php";
	
    }
	public function selidworkflow()
	{	
		$IdTeam=$_POST['IdTeam'];
		$datiWorkflow = $this->_model->getWorkFlow($IdTeam);
		echo json_encode($datiWorkflow); 
	}


	public function UpdateInzVal()
	{
	$IdProcess= $_POST['IdProcess'];
	$inizVal= $_POST['inizVal'];
	$this->_model->UpdateInzVal($IdProcess,$inizVal);

	}
	
	public function formidprocess()
    {
	$IdWorkFlow=$_POST["IdWorkFlow"];
	$SelIdTeam=$_POST["IdTeam"];
	
	$ShowStatusCopy=$_POST['ShowStatusCopy'];
	
	
	$DatiFrequenza = $this->_model->getFrequenza($IdWorkFlow);
	$DatiIdProc = $this->_model->getIdProc();
	
	$DatiListaIdProc = $this->_model->getListaIdProc($IdWorkFlow, $SelIdTeam);
	$ArrIdP=[];
	foreach ($DatiListaIdProc as $row) {
           $ArrIdP[]=$row['ID_PROCESS']; 
	}
	foreach ($DatiFrequenza as $row) {
		$IdFreq=$row['FREQUENZA']; 
	}

	$ArrSvecIdP=array();
	foreach ($DatiIdProc as $row) {
		//$IProc=$row['ID_PROCESS']; 
		$ArrSvecIdP[]=$row['ID_PROCESS'];
		//array_push($ArrSvecIdP,$IProc);
	}
	//$DatiEleborazioni = $this->_model->setShWfs($IdSh,$flag);
	include "view/idprocess/formIdProcess.php";
	
    }
	
	/*public function setblockwfs()
    {
	$IdSh=$_GET['IdSh'];
	$flag=$_GET['flag'];	
	$DatiEleborazioni = $this->_model->setBlockWfs($IdSh,$flag);
	$this->contentList();
	
    }	*/
	
	public function removeReadonly()
    {
	
		$RemoveRO=$_POST['IdProc'];	
	//	$this->debug("POST", $_POST);
		$this->_model->setReadonly($RemoveRO,'N');
		$this->addidprocess();
	} 
	
	public function addReadonly()
    {
	
		$RemoveRO=$_POST['IdProc'];	
	//	$this->debug("POST", $_POST);
		$this->_model->setReadonly($RemoveRO,'Y');
		$this->addidprocess();
	}

	public function AllineaDate()
    {
	
		$IdProc=$_POST['IdProc'];	
	//	$this->debug("POST", $_POST);
		$this->_model->AllineaDate($IdProc);
		$this->addidprocess();
	}
	
	
	public function showcopy()
    {
	$IdWorkFlow=$_POST["IdWorkFlow"];
	$SelIdTeam=$_POST["IdTeam"];
	$SelFromId=$_POST["FromId"];
	$SelToId=$_POST["ToId"];
	$ShowStatusCopy=$_POST['ShowStatusCopy'];
	$Azione=$_POST['Azione'];
	$RemoveRO=$_POST['RemoveRO'];
	$AddRO=$_POST['AddRO'];
	$AddSvecc=$_POST['AddSvecc'];
	$RemSvecc=$_POST['RemSvecc'];	
    
	$this->_model->AzioneUatoDip();
	$DatiTable = $this->_model->getTableData($SelFromId, $SelToId);
	
	include "view/idprocess/showcopy.php";
	
    }
	
	public function saveidprocess()
   {
	$this->error_message = "";
	$IdWorkFlow=$_POST["IdWorkFlow"];
	$SelIdTeam=$_POST["IdTeam"];
	$SelFromId=$_POST["FromId"];
	$SelToId=$_POST["ToId"];
	$ShowStatusCopy=$_POST['ShowStatusCopy'];
	$Azione=$_POST['Azione'];
	$RemoveRO=$_POST['RemoveRO'];
	$AddRO=$_POST['AddRO'];
	$AddSvecc=$_POST['AddSvecc'];
	$RemSvecc=$_POST['RemSvecc'];	
    
	$ret = $this->_model->AzioneUatoDip();
	if($ret['Errore'])
	{
		$this->error_message = $ret['Note'];
	}
	//$DatiTable = $this->_model->getTableData($SelFromId, $SelToId);
	//$this->get_errors_message();
	$this->contentList();
	
    }
	
	public function insertlist()
    {
	
		$IdProc=$_POST['IdProc'];	
	//	$this->debug("POST", $_POST);
		$this->_model->insertList($IdProc);
		$this->addidprocess();
	}
	
	public function deletelist()
    {
	
		$IdProc=$_POST['IdProc'];	
	//	$this->debug("POST", $_POST);
		$this->_model->deleteList($IdProc);
		$this->addidprocess();
	}

	public function generateDateOptions($type) {
    $options = [];


	$currentYear = date('Y')+1;
    switch ($type) {
        case 'A': // Annuale            
            for ($i = 0; $i < 10; $i++) {
                $year = $currentYear - $i;
                $options[] = $year . '12'; // Concatena il mese 12
            }
            break;

        case 'Q': // Trimestrale         
            $quarters = ['12', '09', '06', '03'];
            for ($i = 0; $i < 6; $i++) {
                $year = $currentYear - $i;
                foreach ($quarters as $quarter) {
                    $options[] = $year . $quarter;
                }
            }
            break;

        case 'M': // Mensile           
            for ($i = 0; $i < 6; $i++) {
                $year = $currentYear - $i;
                for ($month = 12; $month >= 1; $month--) {
                    $options[] = $year . sprintf('%02d', $month);
                }
            }
            break;

        default:
          //  echo "Tipo non valido.";
            return [];
    }
   // $options[]= '999912';
    return $options;
}


public function renderSelectBox($options,  $name, $defaultValue,$idProcess) {
    // Trova il valore più vicino e più piccolo
	$retSelect = false;
    $selectedValue = 0;
	$optionsR = $options;
	sort($optionsR);
	//$this->debug("optionsR",$optionsR);
    foreach ($optionsR as $option) {
        if ($option <= $defaultValue) {
            $selectedValue = $option;
        } else {
          // break; // Esci dal ciclo quando trovi un'opzione maggiore
        }
    }
	//$this->debug("defaultValue",$defaultValue);
	//$this->debug("selectedValue",$selectedValue);
    echo '<select onchange="changeInitDate(\''.$idProcess.'\')" class="selectSearch ModificaField" id="' . htmlspecialchars($name) . '" name="' . htmlspecialchars($name) . '">';
    foreach ($options as $option) {
        $selected = ($option === $selectedValue) ? ' selected' : '';
      //  $retSelect = ($option === $selectedValue) ? true : $retSelect;
        echo '<option value="' . htmlspecialchars($option) . '"' . $selected . '>' . htmlspecialchars($option) . '</option>';
    }
    echo '</select>';
	if($defaultValue!=$selectedValue)
			{
			echo " - ".$defaultValue;
			}
	//return $retSelect;
}
	
}



?>