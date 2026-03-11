<?php
/** 
 * @property builder_model $_model
 */
class builder extends helper
{

	private $typesh;

	/**
	 * __construct
	 *
	 * @return void
	 */
	function __construct()
	{
		$rand = rand(1000, 9999);
		$this->_model = new builder_model();
		$this->setDebug_attivo(1);
		$this->include_css = '
	   <link rel="stylesheet" href="./view/builder/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   ';
	}

	// mvc handler request	
	/**
	 * index
	 *
	 * @return void
	 */
	public function index()
	{
		$_view['include_css'] = $this->include_css;
		include "view/header.php";
		$this->apriwf();
		include "view/footer.php";
	}

	/**
	 * contentList
	 *
	 * @return void
	 */
	public function contentList()
	{
		$Azione = @$_POST['Azione'];
		$IdTeam = @$_POST['IdTeam'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$IdTeam = $_POST['IdTeam']?$_POST['IdTeam']:$_SESSION['IdTeam'];
		//$TServer = "Jiak";

		$datiTeam = $this->_model->getTeam();
		$datiWorkflow = $this->_model->getWorkFlow($IdTeam);
		$this->_model->setAzione($Azione);

		include "view/builder/index.php";
	}
	/**
	 * addflusso
	 *
	 * @return void
	 */
	public function addflusso()
	{

		$this->_model->azioneBuilder();
	}

	/**
	 * crearilascio
	 *
	 * @return void
	 */
	public function crearilascio()
	{

		$this->_model->azioneBuilder();
	}

	/**
	 * modificawf
	 *
	 * @return void
	 */
	public function modificawf()
	{

		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$Azione = @$_POST['Azione'];
		$IdTeam = @$_POST['IdTeam'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$IdTeam = $_POST['IdTeam'];
		$ndialog = $_POST['ndialog'];
		$TServer = "Jiak";
		$this->_model->setAzione($Azione);
		$datiModWfs = $this->_model->getModWfs($IdWorkFlow);
		//$this->debug("_POST",$_POST);
		foreach ($datiModWfs as $rowWfs) {
			$TabWorkFlow = $rowWfs['WORKFLOW'];
			$TabDescr = $rowWfs['DESCR'];
			$TabReadOnly = $rowWfs['READONLY'];
			$TabFreq = $rowWfs['FREQUENZA'];
			$TabMulti = $rowWfs['MULTI'];
			$TabOpenAuto = $rowWfs['OPEN_AUTO'];
			$TabOpenMese = $rowWfs['OPEN_MESE'];
			$TabOpenGiorno= $rowWfs['OPEN_GIORNO'];					
		}
		if(!$Azione)
		include('view/builder/Wfs_ModWfs.php');
	}


	/**
	 * aggiungiflusso
	 *
	 * @return void
	 */
	public function aggiungiflusso()
	{

		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$IdTeam = @$_POST['IdTeam'];
		$Azione = @$_POST['Azione'];
		$this->_model->setAzione($Azione);
		$datiModWfs = $this->_model->getModWfs($IdWorkFlow);

		include('view/builder/aggiungiFlusso.php');
	}


	/**
	 * apriwf
	 *
	 * @return void
	 */
	public function apriwf()
	{
		$BodyHeight = $_POST['BodyHeight'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$TServer = "Jiak";
		$TopScroll = $_POST['TopScroll'];
		$IdTeam = $_POST['IdTeam']?$_POST['IdTeam']:$_SESSION['IdTeam'];
		$Azione = $_POST['Azione']; // Da inserire dopo datiSelectedWf?
		$Flusso = $_POST['Flusso']; // Da inserire dopo datiSelectedWf?
		$IdFlu = $_POST['IdFlu'];
		$Tipo = $_POST['Tipo'];
		$TLink = $_POST['TLink'];
		$selIdWorkFlow = $_POST['selIdWorkFlow']?$_POST['selIdWorkFlow']:$_SESSION['IdWorkFlow'];
		$selectFlusso = $_POST['selectFlusso'];


		$Errore = 0;
		$ShowErrore = 0;
		$Note = "";

		if ($Azione != "") {
			echo $Vali = $_POST['SELVAL']; //TEST
		}
		/*$datiWorkflow = $this->_model->getWorkFlow($IdTeam);
		$selectDettagliWf = $this->_model->getDettagliWf($IdWorkFlow);*/
		$datiTeam = $this->_model->getTeam();
		$datiSelectedWf = $this->_model->getSelectedWf($IdWorkFlow);
		$datiRilWf = $this->_model->getRilWf($IdWorkFlow);

		$datiDettagliWf = $this->_model->getDettagliWf($IdWorkFlow, $selectFlusso);
		$datiFlusso = $this->_model->getFlusso($IdFlu);
		//$this->debug('apriwf datiFlusso', $datiFlusso);

		$datiAzione = $this->_model->azioneBuilder();

		include('view/builder/Wfs_Config.php');
	}

	/**
	 * selidworkflow
	 *
	 * @return void
	 */
	public function selidworkflow()
	{
		$IdTeam = $_POST['IdTeam'];
		$datiWorkflow = $this->_model->getWorkFlow($IdTeam);
		echo json_encode($datiWorkflow);
	}
	/**
	 * selectflusso
	 *
	 * @return void
	 */
	public function selectflusso()
	{
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$selectDettagliWf = $this->_model->getDettagliWf($IdWorkFlow);
		echo json_encode($selectDettagliWf);
	}

	/**
	 * elencoFlussi
	 *
	 * @return void
	 */
	public function elencoFlussi()
	{
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$selectFlusso = $_POST['selectFlusso'];
		$datiDettagliWf = $this->_model->getDettagliWf($IdWorkFlow, $selectFlusso);
		$datiDettagliWf = $this->_getFlussoHidden($datiDettagliWf);
		//$this->debug("elencoFlussi", $datiDettagliWf);
		/*$datiFlusso = $this->_model->getFlusso($IdFlu);
		$this->debug('apriwf datiFlusso',$datiFlusso);*/
		//$this->debug("datiDettagliWf",$datiDettagliWf);
		include('view/builder/elencoFlussi.php');
	}

	/**
	 * _getFlussoHidden
	 *
	 * @param  mixed $datiDettagliWf
	 * @return void
	 */
	private function _getFlussoHidden($datiDettagliWf)
	{
		foreach ($datiDettagliWf as $k => $v) {
			$datiFlusso = $this->_model->getFlusso($v['ID_FLU']);
			$nFlussi = 0;
			$cout_hidden = 0;
			foreach ($datiFlusso as $k2 => $v2) {
				$TFinValid = $v2['FIN_VALID'];
				if ($TFinValid < date("Ym")) {
					$cout_hidden++;
				}
				$nFlussi++;
			}
			$datiDettagliWf[$k]['hide'] = ($cout_hidden > 0 && $nFlussi == $cout_hidden) ? 1 : 0;
		}
		return $datiDettagliWf;
	}

	/**
	 * loadflusso
	 *
	 * @return void
	 */
	public function loadflusso()
	{
		$BodyHeight = $_POST['BodyHeight'];
		$IdWorkFlow = @$_REQUEST['IdWorkFlow'];
		$TServer = "Jiak";
		$TopScroll = $_POST['TopScroll'];
		$IdTeam = $_POST['IdTeam'];
		$Azione = $_POST['Azione']; // Da inserire dopo datiSelectedWf?
		$Flusso = $_POST['Flusso']; // Da inserire dopo datiSelectedWf?
		$IdFlu = $_POST['IdFlu'];
		$IdLegame = $_POST['IdLegame'];
		$ndialog = $_POST['ndialog'];
		$Tipo = $_POST['Tipo'];
		$TLink = $_POST['TLink'];
		//$this->debug("post",$_POST);
		$datiAzione = $this->_model->azioneBuilder();
		$datiFlusso = $this->_model->getFlusso($IdFlu);
		//$this->debug('datiFlusso', $datiFlusso);
		include('view/builder/Wfs_OpenFlusso.php');
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
    $options[]= '999912';
    return $options;
}




public function renderSelectBox($options,  $name, $defaultValue) {
    // Trova il valore più vicino e più piccolo
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
    echo '<select class="selectSearch ModificaField" id="' . htmlspecialchars($name) . '" name="' . htmlspecialchars($name) . '">';
    foreach ($options as $option) {
        $selected = ($option === $selectedValue) ? ' selected' : '';
        echo '<option value="' . htmlspecialchars($option) . '"' . $selected . '>' . htmlspecialchars($option) . '</option>';
    }
    echo '</select>';
	if($defaultValue!=$selectedValue)
			{
			echo " - ".$defaultValue;
			}
}

	/**
	 * aggiungidipendenza
	 *
	 * @return void
	 */
	public function aggiungidipendenza()
	{

		$TotLiv = 20;

		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$IdFlu = $_POST['IdFlu'];
		$SelTipo = $_POST['Tipo'];
		$Tipo = $_POST['Tipo'];
		$Azione = "A" . $Tipo;
		$ndialog = $_POST['ndialog'];
		//$TLink=$_POST['TLink'];
		if ($Tipo == "LI") {
			$Tipo = 'L';
			$TLink = 'I';
			$Azione = 'AL';
		}
		if ($Tipo == "LE") {
			$Tipo = 'L';
			$TLink = 'E';
			$Azione = 'AL';
		}
		if ($Tipo == "F") {
			$Azione = 'ADF';
		}
		//	
		//$this->debug("post",$_POST);	
		$datiDipFlusso = $this->_model->getDipFlusso($Tipo, '', $IdWorkFlow, $IdFlu);
		$datiWfs = $this->_model->getModWfs($IdWorkFlow);
		$frequenza = $datiWfs[0]['FREQUENZA'];
		$optiondate = $this->generateDateOptions($frequenza);
		$DatiGruppo = $this->_model->getGruppiByIdWorkflow($IdWorkFlow);
		//$this->debug("opptiondate",$opptiondate);
		$this->_model->azioneBuilder();
		foreach ($datiDipFlusso as $k => $v) {
			${$k} = $v;
		}
		$TabDest = '';
		$TabSh = '';
		$TabInzVali = date("Ym");
		$TabFinVali = '999912';
		include('view/builder/Wfs_AggDip.php');
	}
	/**
	 * modificadip
	 *
	 * @return void
	 */



	public function modificadip()
	{
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$IdLegame = $_POST['IdLegame'];
		$IdFlu = $_POST['IdFlu'];
		$Tipo = $_POST['Tipo'];
		$IdDip = $_POST['IdDip'];
		$TLink = $_POST['TLink'];
		$Azione = $_POST['Azione'];
		$ndialog = $_POST['ndialog'];
		$Azione = "M" . $Tipo;
		$this->_model->azioneBuilder();
		$datiDipFlusso = $this->_model->getDipFlusso($Tipo, $IdDip, $IdWorkFlow, $IdFlu);
		$DatiGruppo = $this->_model->getGruppiByIdWorkflow($IdWorkFlow);
		$datiWfs = $this->_model->getModWfs($IdWorkFlow);
		$frequenza = $datiWfs[0]['FREQUENZA'];
		$optiondate = $this->generateDateOptions($frequenza);
		//$this->debug("datiModWfs",$datiModWfs);
		//                     $this->debug("datiDipFlusso",$datiDipFlusso);
		foreach ($datiDipFlusso as $k => $v) {
			${$k} = $v;
		}

		//$this->debug("datiDipFlusso",$datiDipFlusso);
		include('view/builder/Wfs_ModDip.php');
	}

	/**
	 * getlegamiflussi
	 *
	 * @return void
	 */
	public function getlegamiflussi()
	{
		$IdFlusso = $_POST['IdFlusso'];
		$IdWorkFlow = $_REQUEST['IdWorkFlow'];
		$datiLegamiFlussi = $this->_model->getLegamiFlussi($IdFlusso, $IdWorkFlow);
		echo json_encode($datiLegamiFlussi);
	}
}
