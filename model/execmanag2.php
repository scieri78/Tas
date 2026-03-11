<?php


/**
 * execmanag_model
 */
class execmanag2_model
{
	// set database config for mysql
	// open mysql data base
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();	
	}

	/**
	 * getExecManagGroups
	 *
	 * @param  mixed $Search
	 * @return void
	 */
	public function getExecManagGroups($Search)
	{
		try {
			$res = false;
			if ($Search) {
				$sql = "SELECT G.ID_EM_GROUP, NAME_GROUP, TARGET_DIR, TARGET
					FROM 
					WORK_CORE.EXEC_MANAG_GROUPS   G
					JOIN
					WORK_CORE.EXEC_MANAG_OBJECTS  O
					ON G.ID_EM_GROUP = O.ID_EM_GROUP
					WHERE 1=1
						AND UPPER(TARGET) like UPPER('%$Search%')
					ORDER BY NAME_GROUP, TARGET_DIR, TARGET ";
				$res = $this->_db->getArrayByQuery($sql);
			}
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	/**
	 * TestStatusGroup
	 * CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestStatusGroup
	 * @return void
	 */
	public function TestStatusGroup()
	{

		try {
			// $sql = "UPDATE set b =? where c=? ";  
			$sql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestStatusGroup()';
			//   $values = array($v1, $v2);
			$ret = $this->_db->updateDb($sql, []);

			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * updateExecManagGroups
	 *
	 * @return void
	 */
	public function updateExecManagGroups()
	{
		$PowerStatus = $_POST['PowerStatus'];
		$Power = $_POST['Power'];
		if ($Power != "") {
			if ($PowerStatus == "Y") {
				$PowerStatus = 'N';
			} else {
				$PowerStatus = 'Y';
			}

			$Sql = "update WORK_CORE.EXEC_MANAG_GROUPS SET ENABLE = ? WHERE ID_EM_GROUP = ?";
			$ret = $this->_db->updateDb($Sql, [$PowerStatus, $Power]);
		}
	}


	/**
	 * getExecManagGroupsDep
	 *
	 * @param  mixed $IdFather
	 * @return void
	 */
	public function getExecManagGroupsDep($IdFather)
	{
		try {
			$Sonsql = "SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
					FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
					WHERE ID_EM_GROUP = ?
					ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";
			$ret = $this->_db->getArrayByQuery($Sonsql, [$IdFather]);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function getExecManagGroupsDepAll()
	{
		try {
			$sql = "SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
			FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
			WHERE ID_EM_GROUP NOT IN ( SELECT ID_EM_GROUP_DEP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP IS NOT NULL )
			AND ID_EM_GROUP IN ( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS 
			--WHERE CURRENT_DATE BETWEEN VALID_FROM AND VALID_TO 
			)
			ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";
			$ret = $this->_db->getArrayByQuery($sql, []);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	public function getExecManagGroupsLink($IdEmGroup)
	{
		try {
			$sqlLink = "SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = ?";
			$ret = $this->_db->getArrayByQuery($sqlLink, [$IdEmGroup]);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}



	public function getExecManagGroupsObjects($IdEmGroup)
	{
		try {
			$sqlCnt = "SELECT count(*) CNT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_EM_GROUP = ?  AND enable = ?";
			$ret = $this->_db->getArrayByQuery($sqlCnt, [$IdEmGroup,"Y"]);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * getListGroup
	 *
	 * @return Arr_Groups
	 */
	public function getListGroup()
	{
		try {
			$Arr_Groups = [];
			$ListGroups = $this->listGroup();
			foreach ($ListGroups as $row) {
				$IdEmGroup = $row['ID_EM_GROUP'];
				array_push($Arr_Groups, array("$IdEmGroup"));
			}
			return $Arr_Groups;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	/**
	 * editGroup
	 *
	 * @return void
	 */
	public function editGroup()
	{
		try {
			$Arr_Links = [];
			$EditGroup = $_POST['EditGroup'];
			if ($EditGroup != "") {


				$sqlLink = "SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $EditGroup";
				$ret = $this->_db->getArrayByQuery($sqlLink, []);
				foreach ($ret as $rowLink) {
					$IdLink = $rowLink['ID_EM_GROUP_LINK'];
					array_push($Arr_Links, $IdLink);
				}
				$Sql = "";
			}
			return $Arr_Links;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * listGroup
	 *
	 * @return listGroup
	 */
	private function listGroup()
	{
		try {
			$sqlListGroup = "SELECT ID_EM_GROUP,NAME_GROUP,DESCR_GROUP,ENABLE,VALID_FROM,VALID_TO,STATUS,START_TIME,END_TIME,BCK_GRND,
				( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' AND TMS_INSERT =
				   (SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' )
				) EDITOR,
				TO_CHAR((SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' ),'YYYY-MM-DD HH24:MI:SS') TIME_EDITOR,
				( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' AND TMS_INSERT =
				   (SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' )
				) CREATOR,  
				TO_CHAR((SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' ),'YYYY-MM-DD HH24:MI:SS') TIME_CREATOR,
				( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'I' AND ID_EM_GROUP = SO.ID_EM_GROUP) IN_RUN,
				( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ENABLE = 'N' AND ID_EM_GROUP = SO.ID_EM_GROUP) NOT_ENABLE,    
				( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP = SO.ID_EM_GROUP ) FATHER,
				( SELECT DISTINCT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP AND EVENT_TYPE = 'S' AND TMS_INSERT IN
				   ( SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT F WHERE F.ID_EM_GROUP = m.ID_EM_GROUP  AND F.EVENT_TYPE = 'S' )
				) EXECUTOR, 
				TO_CHAR((SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'S' ),'YYYY-MM-DD HH24:MI:SS') TIME_EXECUTOR
				FROM WORK_CORE.EXEC_MANAG_GROUPS SO
				ORDER BY NAME_GROUP";
			$res = $this->_db->getArrayByQuery($sqlListGroup);

			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * recursiveChangeGroup
	 *
	 * @return void
	 */
	public function recursiveChangeGroup()
	{
		try {

			$RecMode = $_POST['RecMode'];
			$RecStatus = $_POST['RecStatus'];
			$RecOn = $_POST['RecOn'];
			$RecOff = $_POST['RecOff'];
			$DepRun = $_POST['DepRun'];
			$DepNoRun = $_POST['DepNoRun'];

			if ($RecOn != "" or $RecOff != "" or $DepRun != "" or $DepNoRun != "") {

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.RecursiveChangeGroup(?, ?, ?, ?)';
				//$stmt = db2_prepare($conn, $Sql);

				if ($RecOn != "") {
					$RecVal = $RecOn;
				} else {
					$RecVal = $RecOff;
				}
				if ($DepRun != "") {
					$RecVal = $DepRun;
				} else {
					$RecVal = $DepNoRun;
				}

				$values = [
					[
						'name' => 'RecVal',
						'value' => $RecVal,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'RecStatus',
						'value' => $RecStatus,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'RecMode',
						'value' => $RecMode,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					]
				];

				/*	db2_bind_param($stmt, 1, "RecVal"     , DB2_PARAM_IN);
				db2_bind_param($stmt, 2, "RecStatus"  , DB2_PARAM_IN);
				db2_bind_param($stmt, 3, "RecMode"    , DB2_PARAM_IN);
				db2_bind_param($stmt, 4, "User"       , DB2_PARAM_IN);*/


				$ret = $this->_db->callDb($CallPlSql, $values);
				return $ret;
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}



	/**
	 * deleteGroup
	 *
	 * @return void
	 */
	public function deleteGroup()
	{
		try {
			$DeleteGroup = $_POST['DeleteGroup'];
			if ($DeleteGroup != "") {
				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.DeleteGroup(?, ?)';
				$values = [
					[
						'name' => 'DeleteGroup',
						'value' => $DeleteGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					]
				];

				/*db2_bind_param($stmt, 1, "DeleteGroup", DB2_PARAM_IN);
				db2_bind_param($stmt, 2, "User", DB2_PARAM_IN);*/
				$ret = $this->_db->callDb($CallPlSql, $values);
				return $ret;
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * getChangePriority
	 *
	 * @return void
	 */
	public function getChangePriority()
	{
		try {

			$ChangePriority = $_POST['ChangePriority'];
			if ($ChangePriority != "") {

				$OldPriority = $_POST['OldPriority'];
				$NewPriority = $_POST['NewPriority'];

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.ChangePriority(?, ?, ?)';
				//	  $stmt = db2_prepare($conn, $Sql);
				$values = [
					[
						'name' => 'ChangePriority',
						'value' => $ChangePriority,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'OldPriority',
						'value' => $OldPriority,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'NewPriority',
						'value' => $NewPriority,
						'type' => DB2_PARAM_IN
					]
				];

				/* db2_bind_param($stmt, 1,  "ChangePriority", DB2_PARAM_IN);
			  db2_bind_param($stmt, 2,  "OldPriority"   , DB2_PARAM_IN);
			  db2_bind_param($stmt, 3,  "NewPriority"   , DB2_PARAM_IN);*/
				$ret = $this->_db->callDb($CallPlSql, $values);
			}



			$ListGroups = $this->listGroup();

			$Arr_Groups = array();
			foreach ($ListGroups as $row) {
				$IdEmGroup = $row['ID_EM_GROUP'];
				$NameGroup = $row['NAME_GROUP'];
				$DescrGroup = $row['DESCR_GROUP'];
				$Enable = $row['ENABLE'];
				$ValidFrom = $row['VALID_FROM'];
				$ValidTo = $row['VALID_TO'];
				$Status = $row['STATUS'];
				$StartTime = $row['START_TIME'];
				$EndTime = $row['END_TIME'];
				$Creator = $row['CREATOR'];
				$Editor = $row['EDITOR'];
				$InRun = $row['IN_RUN'];
				$BckGrnd = $row['BCK_GRND'];
				$NotEnable = $row['NOT_ENABLE'];
				$IdFather = $row['FATHER'];
				$TimeCreator = $row['TIME_CREATOR'];
				$TimeEditor = $row['TIME_EDITOR'];
				$Executor = $row['EXECUTOR'];
				$TimeExecutor = $row['TIME_EXECUTOR'];

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG.GroupTime(?,?,?)';

				$AllVar = 'Y';
				$TrueTime = 0;



				$values = [
					[
						'name' => 'IdEmGroup',
						'value' => $IdEmGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'AllVar',
						'value' => $AllVar,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'TrueTime',
						'value' => $TrueTime,
						'type' => DB2_PARAM_OUT
					]
				];
				/*  db2_bind_param($stmtg, 1,  "IdEmGroup", DB2_PARAM_IN);
		  db2_bind_param($stmtg, 2,  "AllVar"   , DB2_PARAM_IN);
		  db2_bind_param($stmtg, 3,  "TrueTime" , DB2_PARAM_OUT);*/

				$ret = $this->_db->callDb($CallPlSql, $values);
				$TrueTime =$ret['TrueTime'];
				if ($TrueTime == "") {
					$TrueTime = 0;
				}


				array_push(
					$Arr_Groups,
					array(
						"$IdEmGroup",
						"$NameGroup",
						"$Enable",
						"$ValidFrom",
						"$ValidTo",
						"$Status",
						"$StartTime",
						"$EndTime",
						"$Creator",
						"$InRun",
						"$BckGrnd",
						"$NotEnable",
						"$DescrGroup",
						"$IdFather",
						"$TrueTime",
						"$Editor",
						"$TimeCreator",
						"$TimeEditor",
						"$Executor",
						"$TimeExecutor"
					)
				);
			}
			return $Arr_Groups;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * ChangeGroupStatus
	 *
	 * @return void
	 */
	public function ChangeGroupStatus()
	{
		try {
			$ChangeStatus = $_POST['ChangeStatus'];
			if ("$ChangeStatus" != "") {
				$SelIdEmGroup = $ChangeStatus;
				$OldStatus = $_POST['OldStatus'];
				$NewStatus = "Y";
				if ("$OldStatus" == "Y") {
					$NewStatus = "N";
				}

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.ChangeGroupStatus(?, ?, ?)';

				/*	db2_bind_param($stmt, 1, "SelIdEmGroup"  , DB2_PARAM_IN);
					db2_bind_param($stmt, 2, "NewStatus"    , DB2_PARAM_IN);
					db2_bind_param($stmt, 3, "User"         , DB2_PARAM_IN);*/

				$SelIdEmGroup = "";
				$values = [
					[
						'name' => 'SelIdEmGroup',
						'value' => $SelIdEmGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'NewStatus',
						'value' => $NewStatus,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					]
				];
				$ret = $this->_db->callDb($CallPlSql, $values);
				return $ret;
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * updateGroup
	 *
	 * @param  mixed $Arr_Groups
	 * @return void
	 */
	public function SaveEdit($Arr_Groups)
	{
		try {
			$SaveEdit = $_POST['SaveEdit'];
			if ($SaveEdit != "") {
				$SelIdEmGroup  = $_POST['SelIdEmGroup'];
				$SelNameGroup  = $_POST['SelNameGroup'];
				$SelDescrGroup = $_POST['SelDescrGroup'];
				$SelEnable     = $_POST['SelEnable'];
				$SelBckGrnd    = $_POST['SelBckGrnd'];
				$SelValidFrom  = $_POST['SelValidFrom'];
				$SelValidTo    = $_POST['SelValidTo'];
				$SelFather     = $_POST['SelFather'];

				$ListLinks = "";
				foreach ($Arr_Groups as $Group2) {
					$ObjId = $Group2[0];
					$Link = $_POST["Check" . $ObjId];
					if ("$Link" == "$ObjId") {
						$ListLinks = $ListLinks . $Link . ",";
					}
				}
				if ($ListLinks == "") {
					$ListLinks = "";
				} else {
					$ListLinks = substr($ListLinks, 0, -1);
				}

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.SaveEdit(?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?)';


				$Error = 0;
				$Note = "";

				$values = [
					[
						'name' => 'SelIdEmGroup',
						'value' => $SelIdEmGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelNameGroup',
						'value' => $SelNameGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelDescrGroup',
						'value' => $SelDescrGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelEnable',
						'value' => $SelEnable,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelBckGrnd',
						'value' => $SelBckGrnd,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelValidFrom',
						'value' => $SelValidFrom,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelValidTo',
						'value' => $SelValidTo,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelFather',
						'value' => $SelFather,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'ListLinks',
						'value' => $ListLinks,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'Error',
						'value' => $Error,
						'type' => DB2_PARAM_OUT
					],
					[
						'name' => 'Note',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]
				];




				/* db2_bind_param($stmt,  1, "SelIdEmGroup"  , DB2_PARAM_IN);
			  db2_bind_param($stmt,  2, "SelNameGroup"  , DB2_PARAM_IN);
			  db2_bind_param($stmt,  3, "SelDescrGroup" , DB2_PARAM_IN);
			  db2_bind_param($stmt,  4, "SelEnable"     , DB2_PARAM_IN);
			  db2_bind_param($stmt,  5, "SelBckGrnd"    , DB2_PARAM_IN);
			  db2_bind_param($stmt,  6, "SelValidFrom"  , DB2_PARAM_IN);
			  db2_bind_param($stmt,  7, "SelValidTo"    , DB2_PARAM_IN);
			  db2_bind_param($stmt,  8, "User"          , DB2_PARAM_IN);
			  db2_bind_param($stmt,  9, "SelFather"     , DB2_PARAM_IN);
			  db2_bind_param($stmt, 10, "ListLinks"     , DB2_PARAM_IN);
			  db2_bind_param($stmt, 11, "Error"         , DB2_PARAM_OUT);
			  db2_bind_param($stmt, 12, "Note"          , DB2_PARAM_OUT);*/



				$ret = $this->_db->callDb($CallPlSql, $values);
				return $ret;
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * SaveNew
	 *
	 * @return void
	 */
	public function SaveNew($Arr_Groups)
	{
		try {

			$SaveNew = $_POST['SaveNew'];
			if ($SaveNew != "") {

				$SelNameGroup  = $_POST['SelNameGroup'];
				$SelDescrGroup = $_POST['SelDescrGroup'];
				$SelEnable     = $_POST['SelEnable'];
				$SelBckGrnd    = $_POST['SelBckGrnd'];
				$SelValidFrom  = $_POST['SelValidFrom'];
				$SelValidTo    = $_POST['SelValidTo'];
				$SelFather     = $_POST['SelFather'];

				$ListLinks = "";
				foreach ($Arr_Groups as $Group2) {
					$ObjId = $Group2[0];
					$Link = $_POST["Check" . $ObjId];
					if ("$Link" == "$ObjId") {
						$ListLinks = $ListLinks . $Link . ",";
					}
				}
				if ("$ListLinks" == "") {
					$ListLinks = "";
				} else {
					$ListLinks = substr($ListLinks, 0, -1);
				}

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG_GROUP.SaveNew(?, ?, ?, ?, ?, ?, ?, ?, ?)';
				//$stmt = db2_prepare($conn, $Sql);

				/*   db2_bind_param($stmt, 1, "SelNameGroup"  , DB2_PARAM_IN);
						db2_bind_param($stmt, 2, "SelDescrGroup" , DB2_PARAM_IN);
						db2_bind_param($stmt, 3, "SelEnable"     , DB2_PARAM_IN);
						db2_bind_param($stmt, 4, "SelBckGrnd"    , DB2_PARAM_IN);
						db2_bind_param($stmt, 5, "SelValidFrom"  , DB2_PARAM_IN);
						db2_bind_param($stmt, 6, "SelValidTo"    , DB2_PARAM_IN);
						db2_bind_param($stmt, 7, "User"          , DB2_PARAM_IN);
						db2_bind_param($stmt, 8, "SelFather"     , DB2_PARAM_IN);
						db2_bind_param($stmt, 9, "ListLinks"     , DB2_PARAM_IN);*/
				$values = [
					[
						'name' => 'SelNameGroup',
						'value' => $SelNameGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelDescrGroup',
						'value' => $SelDescrGroup,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelEnable',
						'value' => $SelEnable,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelBckGrnd',
						'value' => $SelBckGrnd,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelValidFrom',
						'value' => $SelValidFrom,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelValidTo',
						'value' => $SelValidTo,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelFather',
						'value' => $SelFather,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'ListLinks',
						'value' => $ListLinks,
						'type' => DB2_PARAM_IN
					]
				];

				$ret = $this->_db->callDb($CallPlSql, $values);
				return $ret;
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}




	/**
	 * SchedFile
	 *
	 * @param  mixed $IDOBJ
	 * @param  mixed $TYPE
	 * @return void
	 */
	public function SchedFile($IDOBJ, $TYPE)
	{
		try {
			$ret = [];

			$SSHUSR = $_SESSION["SSHUSR"];
			$SERVER = $_SESSION["SERVER"];

			$Label = "LOG:";
			if ("$TYPE" != "LSH") {
				$sql = "SELECT LOG_EXEC_MANAG AS FILE FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";

				if ("$TYPE" == "S") {
					$sql = "SELECT '/area_staging_TAS/'||TARGET_DIR||'/'||TARGET AS FILE,TARGET FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_OBJECT = $IDOBJ";
					$Label = "FILE";
				}
				$res = $this->_db->getArrayByQuery($sql);

				foreach ($res as $row) {
					$File = $row['FILE'];
					$Target = $row['TARGET'];
				}
			} else {
				$File = "$IDOBJ";
				$IDOBJ = "";
			}

			$TestoLog = shell_exec("ssh $SSHUSR@$SERVER \"more $File\" ");
			$TestoFile = "-------------------------------------------------------------------------------------<BR>";
			$TestoFile .= preg_replace("/\r\n|\r|\n/", '<br/>', $TestoLog);
			$TestoFile .= "-------------------------------------------------------------------------------------";
			//echo $_SESSION['PSITO'];
			$Dt = date("YmdHis");
			shell_exec('rm -f ' . $_SESSION['PSITO'] . '/TMP/' . $Target);
			$filename = $Target;
			file_put_contents($_SESSION['PSITO'] . '/TMP/' . $filename, $TestoLog);
			$ret['titolo'] = "$Label : ($IDOBJ) $File";
			$ret['filename'] = $filename;
			$ret['TestoLog'] = $TestoLog;
			return $ret;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	/**
	 * getTabRead
	 *
	 * @return void
	 */
	public function getTabRead()
	{
		try {
			$sqlTabRead = "SELECT 
			ID_OBJECT, 
			DESCR_OBJECT, 
			TARGET, 
			TARGET_DIR, 
			VARIABLES, 
			ENABLE, 
			VALID_FROM, 
			VALID_TO, 
			SET_MONTH, 
			SET_DAY, 
			SET_HOUR, 
			SET_MINUTE, 
			FREQUENCY, 
			BG, 
			STATUS, 
			START_TIME, 
			END_TIME, 
			LOG_EXEC_MANAG, 
			( SELECT NAME_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS WHERE ID_EM_GROUP = H.ID_EM_GROUP ) NAME_GROUP,
			( SELECT NVL((SELECT NOMINATIVO USERNAME FROM WEB.TAS_UTENTI WHERE USERNAME = A.USERNAME),A.USERNAME) USERNAME 
			FROM WORK_CORE.EXEC_MANAG_AUDIT A
			WHERE ID_OBJECT = H.ID_OBJECT 
			AND TMS_INSERT = ( SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = H.ID_OBJECT AND TMS_INSERT <= H.START_TIME)
			) USERNAME
				FROM 
					WORK_CORE.EXEC_MANAG_HISTORY H
				WHERE START_TIME is not null
				AND START_TIME >= (SELECT CURRENT_timestamp - 60 DAYS FROM DUAL)	
				ORDER BY START_TIME DESC	
				";
			$res = $this->_db->getArrayByQuery($sqlTabRead);

			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function getListObject($REMDB, $IdEmGroup)
	{
		try {
			$sqlListObject = "
	SELECT B.*
	,    ( SELECT LOG FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH  ) LOG_SH
	,    ( SELECT LOG FROM $REMDB.WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.REM_ID_RUN_SH  ) REM_LOG_SH
	,    ( SELECT STATUS FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.ID_RUN_SH  AND START_TIME >=  NVL(B.TIME_EDITOR,B.TIME_CREATOR)) STATUS_SH
	,    ( SELECT STATUS FROM $REMDB.WORK_CORE.CORE_SH WHERE ID_RUN_SH = B.REM_ID_RUN_SH  AND START_TIME >=  NVL(B.TIME_EDITOR,B.TIME_CREATOR) ) REM_STATUS_SH
	FROM (
		SELECT A.* 
		,( SELECT   MAX(CS1.ID_RUN_SH) as REM_ID_RUN_SH FROM $REMDB.WORK_CORE.CORE_SH CS1  WHERE CS1.NAME = A.TARGET  AND NVL(LTRIM(CS1.VARIABLES),'-') = NVL(LTRIM(A.VARIABLES),'-') ) REM_ID_RUN_SH 
		FROM (
			SELECT 
			ID_OBJECT,DESCR_OBJECT,TARGET_DIR,TARGET,VARIABLES,ENABLE,VALID_FROM,VALID_TO,SET_MONTH,SET_DAY,SET_HOUR,SET_MINUTE,FREQUENCY,BG,STATUS
			,( SELECT MAX(ID_RUN_SH) FROM WORK_CORE.CORE_SH CS WHERE CS.NAME = SO.TARGET AND NVL(LTRIM(CS.VARIABLES),'-') = NVL(LTRIM(SO.VARIABLES),'-')  ) ID_RUN_SH
			, TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS') START_TIME
			, TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS') END_TIME
			,( SELECT ID_OBJECT FROM WORK_CORE.EXEC_MANAG_PRIORITY WHERE ID_OBJECT_DEP = SO.ID_OBJECT ) FATHER
			,( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_OBJECT = SO.ID_OBJECT  AND EVENT_TYPE = 'N' AND TMS_INSERT =
			   (SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT  AND EVENT_TYPE = 'N' )
			) CREATOR
			, TO_CHAR((SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT AND EVENT_TYPE = 'N' ),'YYYY-MM-DD HH24:MI:SS') TIME_CREATOR
			, ( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_OBJECT = SO.ID_OBJECT  AND EVENT_TYPE = 'M' AND TMS_INSERT =
			   (SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT  AND EVENT_TYPE = 'M' )
			) EDITOR
			, TO_CHAR((SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT AND EVENT_TYPE = 'M'),'YYYY-MM-DD HH24:MI:SS') TIME_EDITOR
			, ( SELECT G.NAME_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS G WHERE G.ID_EM_GROUP = SO.ID_EM_GROUP ) GROUP_NAME
			, timestampdiff(2,NVL(SO.END_TIME,CURRENT_TIMESTAMP)-SO.START_TIME) TIME
			, NVL(( SELECT timestampdiff(2,MAX(h.END_TIME)-MAX(h.START_TIME)) FROM WORK_CORE.EXEC_MANAG_HISTORY h WHERE h.ID_OBJECT = SO.ID_OBJECT AND h.START_TIME < SO.START_TIME AND STATUS IN ( 'F','W','N') 
			 ),0) OLD_TIME
			, TO_CHAR(ADD_SECONDS(START_TIME, ( SELECT timestampdiff(2,MAX(h.END_TIME)-MAX(h.START_TIME)) FROM WORK_CORE.EXEC_MANAG_HISTORY h WHERE h.ID_OBJECT = SO.ID_OBJECT AND h.START_TIME < SO.START_TIME AND STATUS IN ( 'F','W','N')) ),'YYYY-MM-DD HH24:MI:SS') PRW_END
			,( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_OBJECT = SO.ID_OBJECT AND EVENT_TYPE = 'S' AND TMS_INSERT =
			   (SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT AND EVENT_TYPE = 'S' )
			) EXECUTOR
			,TO_CHAR(( SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_OBJECT = SO.ID_OBJECT AND EVENT_TYPE = 'S' ),'YYYY-MM-DD HH24:MI:SS') TIME_EXECUTOR
			FROM WORK_CORE.EXEC_MANAG_OBJECTS SO
			WHERE ID_EM_GROUP = $IdEmGroup
		) A
	)B
    ORDER BY GROUP_NAME, TARGET";
			$res = $this->_db->getArrayByQuery($sqlListObject);

			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

		
	/**
	 * getAction
	 *
	 * @return void
	 */
	public function getAction($Arr_Objects)
	{
		try {

			$DeleteObject = $_POST['DeleteObject'];
			if ($DeleteObject != "") {

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG.DeleteObject(?, ?)';
			//	$stmt = db2_prepare($conn, $Sql);

				/* db2_bind_param($stmt, 1,  "DeleteObject", DB2_PARAM_IN);
	  db2_bind_param($stmt, 2, "User", DB2_PARAM_IN);*/

				$values = [
					[
						'name' => 'DeleteObject',
						'value' => $DeleteObject,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					]
				];


				$ret = $this->_db->callDb($CallPlSql, $values);
			}

			$SaveNew = $_POST['SaveNew'];
			if ($SaveNew != "") {

				$SelDescrObject = $_POST['SelDescrObject'];
				$SelTargetDir   = $_POST['SelTargetDir'];
				$SelTarget      = $_POST['SelTarget'];
				$SelVariables   = $_POST['SelVariables'];
				$SelEnable      = $_POST['SelEnable'];
				$SelValidFrom   = $_POST['SelValidFrom'];
				$SelValidTo     = $_POST['SelValidTo'];
				$SelSetMonth    = $_POST['SelSetMonth'];
				$SelSetDay      = $_POST['SelSetDay'];
				$SelSetHour     = $_POST['SelSetHour'];
				$SelSetMinute   = $_POST['SelSetMinute'];
				$SelFrequency   = $_POST['SelFrequency'];
				$SelBg          = $_POST['SelBg'];
				$SelFather      = $_POST['SelFather'];
				$SelIdEmGroup   = $_POST['SelIdEmGroup'];


				$ListLinks = "0";
				foreach ($Arr_Objects as $Object2) {
					$ObjId = $Object2[0];
					$Link = $_POST["Check" . $ObjId];
					if ("$Link" == "$ObjId") {
						$ListLinks = $ListLinks . ", " . $Link;
					}
				}

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG.SaveNew(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
			//	$stmt = db2_prepare($conn, $Sql);

				/*db2_bind_param($stmt, 1,  "SelDescrObject", DB2_PARAM_IN);
	  db2_bind_param($stmt, 2,  "SelTargetDir", DB2_PARAM_IN);
	  db2_bind_param($stmt, 3,  "SelTarget", DB2_PARAM_IN);
	  db2_bind_param($stmt, 4,  "SelVariables", DB2_PARAM_IN);
	  db2_bind_param($stmt, 5,  "SelEnable", DB2_PARAM_IN);
	  db2_bind_param($stmt, 6,  "SelValidFrom", DB2_PARAM_IN);
	  db2_bind_param($stmt, 7,  "SelValidTo", DB2_PARAM_IN);
	  db2_bind_param($stmt, 8,  "SelSetMonth", DB2_PARAM_IN);
	  db2_bind_param($stmt, 9,  "SelSetDay", DB2_PARAM_IN);
	  db2_bind_param($stmt, 10, "SelSetHour", DB2_PARAM_IN);
	  db2_bind_param($stmt, 11, "SelSetMinute", DB2_PARAM_IN);
	  db2_bind_param($stmt, 12, "SelFrequency", DB2_PARAM_IN);
	  db2_bind_param($stmt, 13, "SelBg", DB2_PARAM_IN);
	  db2_bind_param($stmt, 14, "SelFather", DB2_PARAM_IN);
	  db2_bind_param($stmt, 15, "User", DB2_PARAM_IN);
	  db2_bind_param($stmt, 16, "ListLinks", DB2_PARAM_IN);
	  db2_bind_param($stmt, 17, "SelIdEmGroup", DB2_PARAM_IN);*/


				$values = [
					[
						'name' => 'SelDescrObject',
						'value' => $SelDescrObject,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelTargetDir',
						'value' => $SelTargetDir,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelTarget',
						'value' => $SelTarget,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelVariables',
						'value' => $SelVariables,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelEnable',
						'value' => $SelEnable,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelValidFrom',
						'value' => $SelValidFrom,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelValidTo',
						'value' => $SelValidTo,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetMonth',
						'value' => $SelSetMonth,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetDay',
						'value' => $SelSetDay,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetHour',
						'value' => $SelSetHour,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetMinute',
						'value' => $SelSetMinute,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelFrequency',
						'value' => $SelFrequency,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelBg',
						'value' => $SelBg,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelFather',
						'value' => $SelFather,
						'type' => DB2_PARAM_IN
					], 
					[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],[
						'name' => 'ListLinks',
						'value' => $ListLinks,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'SelIdEmGroup',
						'value' => $SelIdEmGroup,
						'type' => DB2_PARAM_IN
					]				
				];


				$ret = $this->_db->callDb($CallPlSql, $values);
			}

			$SaveEdit = $_POST['SaveEdit'];
			if ("$SaveEdit" != "") {

				$SelIdObject    = $_POST['SelIdObject'];
				$SelDescrObject = $_POST['SelDescrObject'];
				$SelTargetDir   = $_POST['SelTargetDir'];
				$SelTarget      = $_POST['SelTarget'];
				$SelVariables   = $_POST['SelVariables'];
				$SelEnable      = $_POST['SelEnable'];
				$SelValidFrom   = $_POST['SelValidFrom'];
				$SelValidTo     = $_POST['SelValidTo'];
				$SelSetMonth    = $_POST['SelSetMonth'];
				$SelSetDay      = $_POST['SelSetDay'];
				$SelSetHour     = $_POST['SelSetHour'];
				$SelSetMinute   = $_POST['SelSetMinute'];
				$SelFrequency   = $_POST['SelFrequency'];
				$SelBg          = $_POST['SelBg'];
				$SelFather      = $_POST['SelFather'];
				$SelIdEmGroup   = $_POST['SelIdEmGroup'];

				$ListLinks = "0";
				foreach ($Arr_Objects as $Object2) {
					$ObjId = $Object2[0];
					$Link = $_POST["Check" . $ObjId];
					if ("$Link" == "$ObjId") {
						$ListLinks = $ListLinks . ", " . $Link;
					}
				}

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG.SaveEdit(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				//$stmt = db2_prepare($conn, $Sql);

				$Error = 0;
				$Note = "";

				$values = [
					[
						'name' => 'SelIdObject',
						'value' => $SelIdObject,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'SelDescrObject',
						'value' => $SelDescrObject,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'SelTargetDir',
						'value' => $SelTargetDir,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelTarget',
						'value' => $SelTarget,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelVariables',
						'value' => $SelVariables,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelEnable',
						'value' => $SelEnable,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelValidFrom',
						'value' => $SelValidFrom,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelValidTo',
						'value' => $SelValidTo,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetMonth',
						'value' => $SelSetMonth,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetDay',
						'value' => $SelSetDay,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetHour',
						'value' => $SelSetHour,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelSetMinute',
						'value' => $SelSetMinute,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelFrequency',
						'value' => $SelFrequency,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelBg',
						'value' => $SelBg,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'SelFather',
						'value' => $SelFather,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					],[
						'name' => 'ListLinks',
						'value' => $ListLinks,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'SelIdEmGroup',
						'value' => $SelIdEmGroup,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'Error',
						'value' => $Error,
						'type' => DB2_PARAM_OUT
					],[
						'name' => 'Note',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]				
				];
				
				$ret = $this->_db->callDb($CallPlSql, $values);

			/*	db2_bind_param($stmt, 1,  "SelIdObject", DB2_PARAM_IN);
				db2_bind_param($stmt, 2,  "SelDescrObject", DB2_PARAM_IN);
				db2_bind_param($stmt, 3,  "SelTargetDir", DB2_PARAM_IN);
				db2_bind_param($stmt, 4,  "SelTarget", DB2_PARAM_IN);
				db2_bind_param($stmt, 5,  "SelVariables", DB2_PARAM_IN);
				db2_bind_param($stmt, 6,  "SelEnable", DB2_PARAM_IN);
				db2_bind_param($stmt, 7,  "SelValidFrom", DB2_PARAM_IN);
				db2_bind_param($stmt, 8,  "SelValidTo", DB2_PARAM_IN);
				db2_bind_param($stmt, 9,  "SelSetMonth", DB2_PARAM_IN);
				db2_bind_param($stmt, 10, "SelSetDay", DB2_PARAM_IN);
				db2_bind_param($stmt, 11, "SelSetHour", DB2_PARAM_IN);
				db2_bind_param($stmt, 12, "SelSetMinute", DB2_PARAM_IN);
				db2_bind_param($stmt, 13, "SelFrequency", DB2_PARAM_IN);
				db2_bind_param($stmt, 14, "SelBg", DB2_PARAM_IN);
				db2_bind_param($stmt, 15, "SelFather", DB2_PARAM_IN);
				db2_bind_param($stmt, 16, "User", DB2_PARAM_IN);
				db2_bind_param($stmt, 17, "ListLinks", DB2_PARAM_IN);
				db2_bind_param($stmt, 18, "SelIdEmGroup", DB2_PARAM_IN);
				db2_bind_param($stmt, 19, "Error", DB2_PARAM_OUT);
				db2_bind_param($stmt, 20, "Note", DB2_PARAM_OUT);*/

			
			}

			$ChangeStatus = $_POST['ChangeStatus'];
			if ("$ChangeStatus" != "") {
				$SelIdObject = $ChangeStatus;
				$OldStatus = $_POST['OldStatus'];
				$NewStatus = "Y";
				if ("$OldStatus" == "Y") {
					$NewStatus = "N";
				}

				$CallPlSql =  'CALL WORK_CORE.K_EXEC_MANAG.ChangeStatus(?, ?, ?)';
				//$stmt = db2_prepare($conn, $Sql);

				/* db2_bind_param($stmt, 1,  "DeleteObject", DB2_PARAM_IN);
	  db2_bind_param($stmt, 2, "User", DB2_PARAM_IN);*/

				$values = [
					[
						'name' => 'SelIdObject',
						'value' => $SelIdObject,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'NewStatus',
						'value' => $NewStatus,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'User',
						'value' => $_SESSION['codname'],
						'type' => DB2_PARAM_IN
					]
				];


				$ret = $this->_db->callDb($CallPlSql, $values);

				$SelIdObject = "";
			}

			$ChangePriority = $_POST['ChangePriority'];
			if ("$ChangePriority" != "") {

				$OldPriority = $_POST['OldPriority'];
				$NewPriority = $_POST['NewPriority'];

				$CallPlSql = 'CALL WORK_CORE.K_EXEC_MANAG.ChangePriority(?, ?, ?)';

				$values = [
					[
						'name' => 'ChangePriority',
						'value' => $ChangePriority,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'OldPriority',
						'value' => $OldPriority,
						'type' => DB2_PARAM_IN
					],[
						'name' => 'NewPriority',
						'value' => $NewPriority,
						'type' => DB2_PARAM_IN
					]
				];


				$ret = $this->_db->callDb($CallPlSql, $values);

			//	$stmt = db2_prepare($conn, $Sql);

				/*db2_bind_param($stmt, 1,  "ChangePriority", DB2_PARAM_IN);
				db2_bind_param($stmt, 2,  "OldPriority", DB2_PARAM_IN);
				db2_bind_param($stmt, 3,  "NewPriority", DB2_PARAM_IN);*/
				
			}

			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * getListGroupE
	 *
	 * @param  mixed $IdEmGroup
	 * @return void
	 */
	public function getListGroupE($IdEmGroup)
	{
		try {
			$sqlListGroup = "SELECT NAME_GROUP,STATUS
    ,( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'I' AND ID_EM_GROUP = SO.ID_EM_GROUP) IN_RUN
    FROM WORK_CORE.EXEC_MANAG_GROUPS SO
	WHERE ID_EM_GROUP != $IdEmGroup
    ORDER BY NAME_GROUP";
			$res = $this->_db->getArrayByQuery($sqlListGroup);

			return $res;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}
	
	/**
	 * ListSons
	 *
	 * @param  mixed $IdFather
	 * @return void
	 */
	function ListSons($IdFather)
	{
	  global $conn;
	  $ListS = "";
	  $sqlListGroup = "SELECT ID_OBJECT_DEP FROM WORK_CORE.EXEC_MANAG_PRIORITY WHERE ID_OBJECT = $IdFather AND ID_OBJECT_DEP IS NOT NULL";

	  $arrayListSons = $this->_db->getArrayByQuery($sqlListGroup);
	  foreach ($arrayListSons as $row) {
		$ID_OBJECT_DEP = $row['ID_OBJECT_DEP'];
		$ListS = $ListS . $ID_OBJECT_DEP . ',';
		$ListS = $ListS . ListSons($ID_OBJECT_DEP) . ',';
	  }
	  if ("$ListS" != "") {
		$ListS = strtr($ListS, 0, -1);
	  }
	  return $ListS;
	}
}
