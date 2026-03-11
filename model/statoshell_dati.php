<?php

class statoshell_dati
{
	public $DB2database = "TASPCUSR";
	public $SelNumPage;
    public $PreIdRun;
    public $ID_ERR;
    public $DA_RETITWS;
    public $INRETE;
    public $INPASSO;
    public $LISTOPEN;
    public $LastShellRun;
    public $NoTags;
    public $InRetePasso;
    public $ShowDett;
    public $PLSSHOWDETT;
    public $GAP;
    public $Sel_Id_Proc;
    public $Sel_Esito;
    public $SelMeseElab;
    public $SelEserMese;
    public $SelShell;
    public $NumLast;
    public $SelLastMeseElab;
    public $SelInDate;
    public $ManualOk;
    public $Uk;
    public $HideTws;
    public $SpliVar;
    public $ShowLoader;
    public $ShowUnify;
    public $SkipUnify;
    public $SelShTarget;
    public $ShowSourceSh;
    public $TestWfsAdmin;
    public $Admin;
    public $Soglia;
    public $BarraCaricamento;
    public $BarraPeggio;
    public $BarraMeglio;
    public $InErrore;
    public $InEsecuzione;
    public $FinitaCorr;
    public $DaEseguire;
    public $ChiusuraForzata;
    public $Sospeso;
	public $ISIDERR;
	public $AutoRefresh2;
	public $AutoRefresh;
	public $ForceEnd;
	public $SelPid;
	public $TopScrollShell;
	public $LeftScrollShell;
	public $SelRootShell;
	public $ListOpenId;
	public $ListIdOpen;
	public $OpenNipote;
    public $IDSELEM;

    const LAST_DAYS = 88;
    const LAST_3_DAYS = 99;
    const ALL_DAY = 999;
	
	
	
    // table fields

    function __construct()
	{ 
		$this->SelRootShell='';
		$this->SelPid='';
		$this->SelNumPage =(isset($_POST["SelNumPage"]) && $_POST["SelNumPage"] != "") ? $_POST["SelNumPage"]:1;
        if ($this->SelNumPage > 1) {$this->PreIdRun = $_POST["PreIdRun"];}
        $this->ID_ERR = isset($_GET["IDERR"]) ? $_GET["IDERR"] : "";
        if ( $this->ID_ERR != "") {$_POST["IDSEL"] =  $this->ID_ERR;}
        $this->DA_RETITWS = isset($_POST["DA_RETITWS"])? $_POST["DA_RETITWS"]: "";
        $this->INRETE = isset($_POST["INRETE"]) ? $_POST["INRETE"] : "";
        $this->INPASSO = isset($_POST["IDSEL"]) ? $_POST["IDSEL"] : "";
        $this->LISTOPEN = isset($_POST["LISTOPEN"]) ? $_POST["LISTOPEN"] : "";
        $this->LastShellRun = isset($_POST["LastShellRun"])? $_POST["LastShellRun"]: "";
        $this->NoTags = isset($_POST["NoTags"]) ? $_POST["NoTags"] : "";
        $this->InRetePasso = $this->INRETE . $this->INPASSO;
        if ($this->LISTOPEN != "") {$_POST["ListOpenId". $this->InRetePasso] = $this->LISTOPEN;}
        $this->ShowDett = "none";
        $this->PLSSHOWDETT = isset($_POST["PLSSHOWDETT"])? $_POST["PLSSHOWDETT"]: "";
        $this->GAP = 120;

        $this->Sel_Id_Proc = isset($_POST["Sel_Id_Proc"])? $_POST["Sel_Id_Proc"]: "";
        $this->Sel_Esito = isset($_POST["Sel_Esito"])? $_POST["Sel_Esito"]: "";
        $this->SelMeseElab = isset($_POST["SelMeseElab"])? $_POST["SelMeseElab"]: "";
        $this->SelEserMese = isset($_POST["SelEserMese"])? $_POST["SelEserMese"]: "";
       
        $this->SelShell = ($_POST["SelShell"]) ? $_POST["SelShell"] : "";
        $this->SelShell = ($_POST["SelShelT"]) ? $_POST["SelShelT"] : $this->SelShell;
        $this->NumLast = isset($_POST["NumLast"]) ? $_POST["NumLast"] : "";
        if ($this->NumLast == "") {$this->NumLast = 10;}
        $this->SelLastMeseElab = isset($_POST["SelLastMeseElab"])? $_POST["SelLastMeseElab"]: "";
        $this->SelInDate = isset($_POST["SelInDate"])? $_POST["SelInDate"]: "";
        $this->ManualOk = isset($_POST["ManualOk"]) ? $_POST["ManualOk"] : "";
        $this->Uk = $_SESSION["CodUk"];
        $this->HideTws = isset($_POST["HideTws"]) ? $_POST["HideTws"] : "";
        $this->SpliVar = isset($_POST["SpliVar"]) ? $_POST["SpliVar"] : "";
        $this->ShowLoader = isset($_POST["ShowLoader"])? $_POST["ShowLoader"]: "";
        $this->ShowUnify = isset($_POST["ShowUnify"])? $_POST["ShowUnify"]: "";
        $this->SkipUnify = isset($_POST["SkipUnify"])? $_POST["SkipUnify"]: "";

        $this->ShowSourceSh = "N";
       
	   if ($this->INPASSO != "") {
		   $this->SelShTarget = isset($_POST["IDSEL"]) ? $_POST["IDSEL"] : "";
		   $this->ShowSourceSh = "Y";
           $this->NumLast=  $this->NumLast?$this->NumLast:0;
           $this->SelInDate = self::ALL_DAY;
		
			} else {
				$this->SelShTarget = isset($_REQUEST["IDSELEM"])? $_REQUEST["IDSELEM"]    : "";
				$this->IDSELEM = isset($_REQUEST["IDSELEM"]) ? $_REQUEST["IDSELEM"] : "";
				if ($this->SelShTarget != "") {    
					$this->ShowSourceSh = "Y";    
					$this->LastShellRun = 0;    
					$this->SelInDate = self::ALL_DAY;
					}else {    
						$this->SelShTarget = isset($_GET["IDWFM"])? $_GET["IDWFM"]: "";
						if ($this->Sel_Id_Proc == "") {
							$this->Sel_Id_Proc = isset($_GET["IDPROCERR"])? $_GET["IDPROCERR"]: "";
							}    
						if ($this->SelShTarget != "") {		
							$this->LastShellRun = 0;
							$this->NumLast=  $this->NumLast?$this->NumLast:0;
							$this->SelInDate = self::ALL_DAY;
						if ($this->Admin) {
						 $this->ShowSourceSh = "Y";}
						 else {
					//not admin           
					//$sql = "SELECT  
					//   (         
					
					//     SELECT count(*)            
					//     FROM WFS.AUTORIZZAZIONI            
					//     WHERE 1=1            
					//     AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )            
					//     AND ID_WORKFLOW = E.ID_WORKFLOW            
					//     AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE GRUPPO = 'ADMIN'  )            
					//   ) CNT_AUTH            //   FROM WFS.ELABORAZIONI E            
					//   WHERE ID_SH = (SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $SelShTarget )";            
					//            //   $stmt=db2_prepare($conn, $sql);            
					//   $result=db2_execute($stmt);            
					//   if ( ! $result ){            
					//       echo $sql;            
					//       echo "ERROR DB2 1";            
					//   }            //   while ($row = db2_fetch_assoc($stmt){ {            
					//     $TestWfsAdmin=$row['CNT_AUTH'];            
					//     if ( $TestWfsAdmin != 0 ){            
					//         $ShowSourceSh="Y";            
					//     }            
					// }        
							}    
						}else {
							$this->ShowSourceSh = "Y";   
							}
			        }
			}

        $this->Soglia = 110;
        $this->BarraCaricamento = "rgb(197, 150, 1)";
        $this->BarraPeggio = "rgb(165, 108, 185)";
        $this->BarraMeglio = "rgb(104, 162, 111)";
        $this->InErrore = "rgb(198, 66, 66)";
        $this->InEsecuzione = "rgb(192, 181, 54)";
        $this->FinitaCorr = "rgb(67, 168, 51)";
        $this->DaEseguire = "#838383";
        $this->ChiusuraForzata = "#4787DA";
        $this->Sospeso = "#682A83";
		$this->ISIDERR="";
		if ( $this->SelShTarget != "" ){
			$this->AutoRefresh2=isset($_POST['AutoRefresh2'])?$_POST['AutoRefresh2']:"";
            $this->ISIDERR="hidden";
		}
		$this->ForceEnd=isset($_POST['ForceEnd'])?$_POST['ForceEnd']:"";
		
		 if ( $this->ShowUnify == "" ){
            $this->SkipUnify="";
			}
  		
		$this->AutoRefresh=isset($_POST['AutoRefresh'])?$_POST['AutoRefresh']:"";
		$this->TopScrollShell=isset($_POST['TopScrollShell'])?$_POST['TopScrollShell']:"";
		$this->LeftScrollShell=isset($_POST['LeftScrollShell'])?$_POST['LeftScrollShell']:"";
		if ( $this->SelInDate == "" ){ $this->SelInDate=self::LAST_DAYS; }
		$this->ListOpenId=isset($_POST['ListOpenId'.$this->InRetePasso])?$_POST['ListOpenId'.$this->InRetePasso]:"";
        $this->ListIdOpen=explode(',',$this->ListOpenId);
		$this->OpenNipote=$_POST['OpenNipote'];
		}

    function setSelNumPage($SelNumPage){$this->SelNumPage = $SelNumPage;}
    function getSelNumPage(){return $this->SelNumPage;}
    function setPreIdRun($PreIdRun){$this->PreIdRun = $PreIdRun;}
    function getPreIdRun(){return $this->PreIdRun;}
    function setID_ERR($ID_ERR){$this->ID_ERR = $ID_ERR;}
    function getID_ERR(){return $this->ID_ERR;}
    function setDA_RETITWS($DA_RETITWS){$this->DA_RETITWS = $DA_RETITWS;}
    function getDA_RETITWS(){return $this->DA_RETITWS;}
    function setINRETE($INRETE){$this->INRETE = $INRETE;}
    function getINRETE(){return $this->INRETE;}
    function setINPASSO($INPASSO){$this->INPASSO = $INPASSO;}
    function getINPASSO(){return $this->INPASSO;}
    function setLISTOPEN($LISTOPEN){$this->LISTOPEN = $LISTOPEN;}
    function getLISTOPEN(){return $this->LISTOPEN;}
    function setLastShellRun($LastShellRun){$this->LastShellRun = $LastShellRun;}
    function getLastShellRun(){return $this->LastShellRun;}
    function setNoTags($NoTags){$this->NoTags = $NoTags;}
    function getNoTags(){return $this->NoTags;}
    function setInRetePasso($InRetePasso){$this->InRetePasso = $InRetePasso;}
    function getInRetePasso(){return $this->InRetePasso;}
    function setShowDett($ShowDett){$this->ShowDett = $ShowDett;}
    function getShowDett(){return $this->ShowDett;}
    function setPLSSHOWDETT($PLSSHOWDETT){$this->PLSSHOWDETT = $PLSSHOWDETT;}
    function getPLSSHOWDETT(){return $this->PLSSHOWDETT;}
    function setGAP($GAP){$this->GAP = $GAP;}
    function getGAP(){return $this->GAP;}
    function setSel_Id_Proc($Sel_Id_Proc){$this->Sel_Id_Proc = $Sel_Id_Proc;}
    function getSel_Id_Proc(){return $this->Sel_Id_Proc;}
    function setSel_Esito($Sel_Esito){$this->Sel_Esito = $Sel_Esito;}
    function getSel_Esito(){return $this->Sel_Esito;}
    function setSelMeseElab($SelMeseElab){$this->SelMeseElab = $SelMeseElab;}
    function getSelMeseElab(){return $this->SelMeseElab;}
    function setSelEserMese($SelEserMese){$this->SelEserMese = $SelEserMese;}
    function getSelEserMese(){return $this->SelEserMese;}
    function setSelShell($SelShell){$this->SelShell = $SelShell;}
    function getSelShell(){return $this->SelShell;}
    function setNumLast($NumLast){$this->NumLast = $NumLast;}
    function getNumLast(){return $this->NumLast;}
    function setSelLastMeseElab($SelLastMeseElab){$this->SelLastMeseElab = $SelLastMeseElab;}
    function getSelLastMeseElab(){return $this->SelLastMeseElab;}
    function setSelInDate($SelInDate){$this->SelInDate = $SelInDate;}
    function getSelInDate(){return $this->SelInDate;}
    function setManualOk($ManualOk){$this->ManualOk = $ManualOk;}
    function getManualOk(){return $this->ManualOk;}
    function setUk($Uk){$this->Uk = $Uk;}
    function getUk(){return $this->Uk;}
    function setHideTws($HideTws){$this->HideTws = $HideTws;}
    function getHideTws(){return $this->HideTws;}
    function setSpliVar($SpliVar){$this->SpliVar = $SpliVar;}
    function getSpliVar(){return $this->SpliVar;}
    function setShowLoader($ShowLoader){$this->ShowLoader = $ShowLoader;}
    function getShowLoader(){return $this->ShowLoader;}
    function setShowUnify($ShowUnify){$this->ShowUnify = $ShowUnify;}
    function getShowUnify(){return $this->ShowUnify;}
    function setSkipUnify($SkipUnify){$this->SkipUnify = $SkipUnify;}
    function getSkipUnify(){return $this->SkipUnify;}
    function setSelShTarget($SelShTarget){$this->SelShTarget = $SelShTarget;}
    function getSelShTarget(){return $this->SelShTarget;}
    function setShowSourceSh($ShowSourceSh){$this->ShowSourceSh = $ShowSourceSh;}
    function getShowSourceSh(){return $this->ShowSourceSh;}
    function setTestWfsAdmin($TestWfsAdmin){$this->TestWfsAdmin = $TestWfsAdmin;}
    function getTestWfsAdmin(){return $this->TestWfsAdmin;}
    function setAdmin($Admin){$this->Admin = $Admin;}
    function getAdmin(){return $this->Admin;}
    function setSoglia($Soglia){$this->Soglia = $Soglia;}
    function getSoglia(){return $this->Soglia;}
    function setBarraCaricamento($BarraCaricamento){$this->BarraCaricamento = $BarraCaricamento;}
    function getBarraCaricamento(){return $this->BarraCaricamento;}
    function setBarraPeggio($BarraPeggio){$this->BarraPeggio = $BarraPeggio;}
    function getBarraPeggio(){return $this->BarraPeggio;}
    function setBarraMeglio($BarraMeglio){$this->BarraMeglio = $BarraMeglio;}
    function getBarraMeglio(){return $this->BarraMeglio;}
    function setInErrore($InErrore){$this->InErrore = $InErrore;}
    function getInErrore(){return $this->InErrore;}
    function setInEsecuzione($InEsecuzione){$this->InEsecuzione = $InEsecuzione;}
    function getInEsecuzione(){return $this->InEsecuzione;}
    function setFinitaCorr($FinitaCorr){$this->FinitaCorr = $FinitaCorr;}
    function getFinitaCorr(){return $this->FinitaCorr;}
    function setDaEseguire($DaEseguire){$this->DaEseguire = $DaEseguire;}
    function getDaEseguire(){return $this->DaEseguire;}
    function setChiusuraForzata($ChiusuraForzata){$this->ChiusuraForzata = $ChiusuraForzata;}
    function getChiusuraForzata(){return $this->ChiusuraForzata;}
    function setSospeso($Sospeso){$this->Sospeso = $Sospeso;}
    function getSospeso(){return $this->Sospeso;}
	function setISIDERR($ISIDERR) { $this->ISIDERR = $ISIDERR; }
	function getISIDERR() { return $this->ISIDERR; }
	function setAutoRefresh2($AutoRefresh2) { $this->AutoRefresh2 = $AutoRefresh2; }
	function getAutoRefresh2() { return $this->AutoRefresh2; }
	function setAutoRefresh($AutoRefresh) { $this->AutoRefresh = $AutoRefresh; }
	function getAutoRefresh() { return $this->AutoRefresh; }
	function setForceEnd($ForceEnd) { $this->ForceEnd = $ForceEnd; }
	function getForceEnd() { return $this->ForceEnd; }
	function setSelPid($SelPid) { $this->SelPid = $SelPid; }
	function getSelPid() { return $this->SelPid; }
	function setTopScrollShell($TopScrollShell) { $this->TopScrollShell = $TopScrollShell; }
	function getTopScrollShell() { return $this->TopScrollShell; }
	function setLeftScrollShell($LeftScrollShell) { $this->LeftScrollShell = $LeftScrollShell; }
	function getLeftScrollShell() { return $this->LeftScrollShell; }
	function setSelRootShell($SelRootShell) { $this->SelRootShell = $SelRootShell; }
	function getSelRootShell() { return $this->SelRootShell;}
	function setListOpenId($ListOpenId) { $this->ListOpenId = $ListOpenId; }
	function getListOpenId() { return $this->ListOpenId; }
	function setListIdOpen($ListIdOpen) { $this->ListIdOpen = $ListIdOpen; }
	function getListIdOpen() { return $this->ListIdOpen; }

}?>