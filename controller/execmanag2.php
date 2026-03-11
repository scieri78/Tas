<?php
/** 
 * @property execmanag2_model $_model
 */
class execmanag2 extends helper
{


	/**
	 * __construct
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->include_css = '
	   <link rel="stylesheet" href="./view/execmanag2/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/execmanag2/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
		$this->_model = new execmanag2_model();
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


	/**
	 * ExecManag
	 *
	 * @return void
	 */
	public function ExecManag()
	{
		if (!$_POST) {
			$this->index();
		} else {	
			$IdEmGroup=$_POST['IdEmGroup'];
			$NameGroup=$_POST['NameGroup'];
			$TopScrollO=$_POST['TopScrollO'];
			$EnableRefresh=$_POST['EnableRefresh'];	
			$ForceRun=$_POST['ForceRun'];
			$AddObject=$_POST['AddObject'];
    		$EditObject=$_POST['EditObject'];

			$DATABASE =$_SESSION['DATABASE'];
			$SSHUSR =$_SESSION['SSHUSR'];
			$SERVER =$_SESSION['SERVER']; 
			$PRGDIR =$_SESSION['PRGDIR'];
			
			$BarraCaricamento = "rgb(21, 140, 240)";
			$BarraPeggio = "rgb(165, 108, 185)";
			$BarraMeglio = "rgb(104, 162, 111)";
		
			$InErrore = "rgb(198, 66, 66)";
			$InEsecuzione = "rgb(192, 181, 54)";
			$FinitaCorr = "rgb(67, 168, 51)";
			$DaEseguire = "#838383";
			$ChiusuraForzata = "#4787DA";
			$Sospeso = "#682A83";

			if ( $_SESSION['DATABASE'] == "WORK" ){
				$REMDB="TASPCUSR";
				$RemDir="TASUSR";
				$RemLabel='<div style="font-size:11px;float:left;" ><B>[USR]</B></div>';
			  } else {
				$REMDB="TASPCWRK";
				$RemDir="TASWRK";
				$RemLabel='<div style="font-size:11px;float:left;" ><B>[WRK]</B></div>';
			  }
			  
			$DeleteObject=$_POST['DeleteObject'];
			$_view['include_css'] = $this->include_css;
			include "view/header.php";
			$datiListObject = $this->_model->getListObject($REMDB,$IdEmGroup);
			$GAP = 10;
			$Arr_Objects = [];
			foreach ($datiListObject as $row) {
			  $IdObject=$row['ID_OBJECT'];		
			  array_push($Arr_Objects, array("$IdObject" ));
			}
			$datiListGroupE = $this->_model->getListGroupE($IdEmGroup);
			$Arr_Groups=array();
			foreach ($datiListGroupE as $row) {
			  $NameGroup=$row['NAME_GROUP'];
			  $Status=$row['STATUS'];
			  $InRun=$row['IN_RUN'];
			  array_push($Arr_Groups,
				  array(
				  "$NameGroup",
				  "$Status",
				  "$InRun"
				  )
				);
			}
			$this->_model->getAction($datiListObject);
			$Arr_Objects = $this->getArrObjects($datiListObject);
			$_model =$this->_model;
			include "view/execmanag2/ExecManag.php";
			include "view/footer.php";
		}
	}

	
	/**
	 * getArrObjects
	 *
	 * @param  mixed $datiListObject
	 * @return void
	 */
	private function getArrObjects($datiListObject)
	{
		$Arr_Objects = array();
		foreach ($datiListObject as $row ) {
		  $IdObject = $row['ID_OBJECT'];
		  $DescrObject = $row['DESCR_OBJECT'];
		  $TargetDir = $row['TARGET_DIR'];
		  $Target = $row['TARGET'];
		  $Variables = $row['VARIABLES'];
		  $Enable = $row['ENABLE'];
		  $IdRunSh = $row['ID_RUN_SH'];
		  $RemIdRunSh = $row['REM_ID_RUN_SH'];
		  $ValidFrom = $row['VALID_FROM'];
		  $ValidTo = $row['VALID_TO'];
		  $SetMonth = $row['SET_MONTH'];
		  $SetDay = $row['SET_DAY'];
		  $SetHour = $row['SET_HOUR'];
		  $SetMinute = $row['SET_MINUTE'];
		  $Frequency = $row['FREQUENCY'];
		  $Bg = $row['BG'];
		  $Status = $row['STATUS'];
		  $StartTime = $row['START_TIME'];
		  $EndTime = $row['END_TIME'];
		  $LogScheduler = $row['LOG_EXEC_MANAG'];
		  $Father = $row['FATHER'];
		  $Creator = $row['CREATOR'];
		  $Editor = $row['EDITOR'];
		  $GroupName = $row['GROUP_NAME'];
		  $LogSh = $row['LOG_SH'];
		  $Time = $row['TIME'];
		  $OldTime = $row['OLD_TIME'];
		  $PrwEnd = $row['PRW_END'];
		  $RemLogSh = $row['REM_LOG_SH'];
		  $TimeCreator = $row['TIME_CREATOR'];
		  $TimeEditor = $row['TIME_EDITOR'];
		  $Executor = $row['EXECUTOR'];
		  $TimeExecutor = $row['TIME_EXECUTOR'];
		  $StatusSh = $row['STATUS_SH'];
		  $RemStatusSh = $row['REM_STATUS_SH'];


		  array_push(
			$Arr_Objects,
			array(
			  "$IdObject",
			  "$DescrObject",
			  "$TargetDir",
			  "$Target",
			  "$Variables",
			  "$Enable",
			  "$ValidFrom",
			  "$ValidTo",
			  "$SetMonth",
			  "$SetDay",
			  "$SetHour",
			  "$SetMinute",
			  "$Frequency",
			  "$Bg",
			  "$Status",
			  "$StartTime",
			  "$EndTime",
			  "$LogScheduler",
			  "$Father",
			  "$Creator",
			  "$GroupName",
			  "$LogSh",
			  "$Time",
			  "$OldTime",
			  "$PrwEnd",
			  "$IdRunSh",
			  "$RemIdRunSh",
			  "$RemLogSh",
			  "$Editor",
			  "$TimeCreator",
			  "$TimeEditor",
			  "$Executor",
			  "$TimeExecutor",
			  "$StatusSh",
			  "$RemStatusSh"
			)
		  );
		}
		return $Arr_Objects;
	}

	/**
	 * contentList
	 *
	 * @return void
	 */
	public function contentList()
	{
		$TopScrollG = $_POST['TopScrollG'];
		$ListOpenP = $_POST['ListOpenP'];
		$EnableRefresh = $_POST['EnableRefresh'];
		$Ricerca = $_POST['Ricerca'];
		$Search = $_POST['Search'];
		$AddGroup = $_POST['AddGroup'];
		$EditGroup = $_POST['EditGroup'];
		$datiExecManagGroups = $this->_model->getExecManagGroups($Search);

		//$this->debug('Search', $Search);
		//$this->debug('datiExecManagGroups', $datiExecManagGroups);

	    $this->_model->TestStatusGroup();
		$Arr_Groups = $this->_model->getListGroup();
		//$this->debug('getListGroup', $Arr_Groups);
		$this->_model->updateExecManagGroups();
		$this->_model->recursiveChangeGroup();
		$this->_model->deleteGroup();
		$this->_model->SaveNew($Arr_Groups);
		$this->_model->SaveEdit($Arr_Groups);
		$this->_model->ChangeGroupStatus();
		$Arr_Groups  = $this->_model->getChangePriority();
		//$this->debug('getChangePriority', $Arr_Groups);
		$Arr_Links = $this->_model->editGroup();

		$DepAll = $this->_model->getExecManagGroupsDepAll();
		//$this->debug('DepAll',$DepAll);
		$excmanModel = $this->_model;
		include "view/execmanag2/index.php";
		//include "view/execmanag2/footerFunction.php";
		
	}
	function Sons($IdFather, $conn, $Arr_Groups){

     /*   $Sonsql="SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
        FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
        WHERE ID_EM_GROUP = $IdFather
        ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";

        $Sonstmt=db2_prepare($conn, $Sonsql);
        $Sonresult=db2_execute($Sonstmt);

        if ( ! $Sonresult ){
            echo $Sonsql;
            echo "ERROR DB2:".db2_stmt_errormsg($stmt);
        }*/
		$GroupsDep = $this->_model->getExecManagGroupsDep($IdFather);
		$SonIdEmGroupOld= "";
       echo "<ul>";
        foreach ($GroupsDep as $Sonrow) {
          $SonPriority=$Sonrow['PRIORITY'];
          $SonIdEmGroup=$Sonrow['ID_EM_GROUP'];
          $SonPriorityDep=$Sonrow['PRIORITY_DEP'];
          $SonIdEmGroupDep=$Sonrow['ID_EM_GROUP_DEP'];

          if ( $SonIdEmGroupOld != $SonIdEmGroup ){

              if ( $SonIdEmGroupOld != "" ){
                echo "</ul></li>";
              }
              $SonIdEmGroupOld=$SonIdEmGroup;
          }

          if ( $SonIdEmGroupDep != "" ){

                  foreach( $Arr_Groups as $Group ){
                      if ( $Group[0] == $SonIdEmGroupDep ){
                         $SonDepNameGroup=$Group[1];
                         $SonDepEnable=$Group[2];
                         $SonDepValidFrom=$Group[3];
                         $SonDepValidTo=$Group[4];
                         $SonDepStatus=$Group[5];
                         $SonDepStartTime=$Group[6];
                         $SonDepEndTime=$Group[7];
                         $SonDepCreator=$Group[8];
                         $SonDepInRun=$Group[9];
                         $SonDepBckGrnd=$Group[10];
                         $SonDepNotEnable=$Group[11];
                         $SonDepDescrGroup=$Group[12];                       
                         $TrueTime=$Group[14];
                         $Editor=$Group[15];
                         $TimeCreator=$Group[16];
                         $TimeEditor=$Group[17];
                         $Executor=$Group[18];
                         $TimeExecutor=$Group[19];
                         break;
                      }
                  }
                  $lidisable="";
                  $SonDepBkgr="";
                  if ( $SonDepEnable == "N" ){
                    $SonDepBkgr="background-color:#e12c2c;";
                    $lidisable='style="background-color:#c89696;"';
                  }
                  if ( $SonDepEnable == "S" ){
                    $SonDepBkgr="background-color:#9697c8;";
                  }
                  if ( $SonDepInRun != "0" ){
                    $SonDepBkgr="background-color:#ffcb00;";
                  }
                  
                /*
                  $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $SonIdEmGroupDep";
                  $stmtLink=db2_prepare($conn, $sqlLink);
                  $resultLink=db2_execute($stmtLink);

                  if ( ! $resultLink ){
                      echo $sqlLink;
                      echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
                  }

                  $FindLinks=0;
                  $Arr_Links=array();*/
				  $GroupsLink =	$this->_model->getExecManagGroupsLink($SonIdEmGroupDep);

                  foreach ($GroupsLink as $rowLink) {
                    $IdLink=$rowLink['ID_EM_GROUP_LINK'];
                    array_push($Arr_Links, $IdLink);
                    $FindLinks=1;
                  }
                  
             	 /*$sqlCnt="SELECT count(*) CNT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_EM_GROUP = $SonIdEmGroupDep AND enable='Y'";
                  $stmtCnt=db2_prepare($conn, $sqlCnt);
                  $resultCnt=db2_execute($stmtCnt);*/
				 $GroupsObjects = $this->_model->getExecManagGroupsObjects($SonIdEmGroupDep);

                  foreach ($GroupsObjects as $rowCnt) {
                    $CntDep=$rowCnt['CNT'];
                  }


               include "view/execmanag2/sonsFooter.php";   
               $this->Sons($SonIdEmGroupDep, $conn, $Arr_Groups);
                //foreach( $Arr_Links as $Father ){
                //   Sons($Father, $conn, $Arr_Groups);
                //}
                
           echo "</li>";
                


          }

        }
     

    }




	/**
	 * mostrastorico
	 *
	 * @return void
	 */
	public function mostrastorico()
	{

		include "view/execmanag2/ExecManag_MostraStorico.php";
	}

	/**
	 * codaexec
	 *
	 * @return void
	 */
	public function codaexec()
	{
		$datiTabRead = $this->_model->getTabRead();
		include "view/execmanag2/CodaExec.php";
	}

	/**
	 * openschedfile
	 *
	 * @return void
	 */
	public function openschedfile()
	{

		$IDOBJ = $_GET["IDOBJ"];
		$TYPE = $_GET["TYPE"];
		$datiSchedFile = $this->_model->SchedFile($IDOBJ, $TYPE);
		$titolo = $datiSchedFile['titolo'];
		$filename = $datiSchedFile['filename'];
		$TestoLog = $datiSchedFile['TestoLog'];
		include "view/execmanag2/OpenSchedFile.php";
	}
}
