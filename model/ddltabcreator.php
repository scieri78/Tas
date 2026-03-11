<?php


class ddltabcreator_model
{
	// set database config for mysql
	// open mysql data base
	private $_db;

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct($sito='')
	{
		$this->_db = new db_driver($sito);
	}

	public function getSchema()
	{
		try {
			$sql = "select DISTINCT TABSCHEMA from syscat.tables WHERE TABSCHEMA NOT LIKE 'SYS%' ORDER BY 1";

			$res = $this->_db->getArrayByQuery($sql);

			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getTable($Sel_Schema = '')
	{
		try {
			if ($Sel_Schema != "") {
				$sql = "select DISTINCT TABNAME, TYPE  from syscat.tables WHERE TYPE in ('T','V','N') AND TRIM(TABSCHEMA) = ? ORDER BY 1";

				$res = $this->_db->getArrayByQuery($sql, array($Sel_Schema));
			}
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getSQLTable($Sel_Schema = '', $Sel_Object = '')
	{
		$DATABASE = $_SESSION['DATABASE'];
		if ($Sel_Schema != "" and $Sel_Object != "") {

			$arrs = explode('|', $Sel_Object);
			$Sel_Table = $arrs[0];
			$Sel_Type = $arrs[1];
			$Typelabel = "t";
			$Optlabel = "-noview ";
			switch ($Sel_Type) {
				case 'T':
					$Typelabel = "n";
					if ($DATABASE == "USER") {
						$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \". /home/db2tsusr/sqllib/db2profile; db2look -d TASPCUSR -z $Sel_Schema -t $Sel_Table -e -r -nostatsclause -nofed -noview -noimplschema \" ");
					} else {
						$TestoFile = shell_exec("ssh " . $_SESSION['SSHUSR'] . "@" . $_SESSION['SERVER'] . " \". /home/db2tswrk/sqllib/db2profile; db2look -d TASPCWRK -z $Sel_Schema -t $Sel_Table -e -r -nostatsclause -nofed -noview -noimplschema \" ");
					}
					break;
				case 'N':
					$TestoFile = " ---------- ! This is Nickname and i can't create DDL ! -------------- ";
					break;
				case 'V':
					$sql = "SELECT TEXT FROM SYSCAT.VIEWS WHERE VIEWSCHEMA = ? AND VIEWNAME = ?";
					$res = $this->_db->getArrayByQuery($sql, array($Sel_Schema, $Sel_Table));
					foreach ($res as $row) {
						$TestoFile = $row['TEXT'];
					}
					break;
			}


			$Dt = date("YmdHis");
			shell_exec('rm -f ' . $_SESSION['PSITO'] . '/DDL/' . $Sel_Schema . '_' . $Sel_Table . '_*');
			$filename = $Sel_Schema . '.' . $Sel_Table . '_' . $Dt . '.sql';
			file_put_contents($_SESSION['PSITO'] . '/DDL/' . $filename, $TestoFile);
			shell_exec('chmod 774 ' . $_SESSION['PSITO'] . '/DDL/' . $filename);
			$ret['TestoFile'] = $TestoFile;
			$ret['filename'] = $filename;
			//$ret['sqlfilename']=$FileSql.'.sql';
			return $ret;
		}
	}





	public function getGRANTEE($Sel_Schema = '', $Sel_Object = '')
	{

		
			$arrs = explode('|', $Sel_Object);
			$Sel_Table = $arrs[0];
		try {
			$sql = "SELECT DISTINCT GRANTEE,
				REPLACE('GRANT '||
				DECODE(CONTROLAUTH ,'Y','CONTROL, ' ,NULL)||
				DECODE(ALTERAUTH ,'Y','ALTER, ' ,NULL)||
				DECODE(DELETEAUTH ,'Y','DELETE, ' ,NULL)||
				DECODE(INSERTAUTH ,'Y','INSERT, ' ,NULL)||
				DECODE(SELECTAUTH ,'Y','SELECT, ' ,NULL)||
				DECODE(UPDATEAUTH ,'Y','UPDATE, ' ,NULL)||
				DECODE(REFAUTH ,'Y','REF, ' ,NULL)||
				'#'||
				' ON '||TRIM(TCREATOR)||'.'||TRIM(TTNAME)||' TO '||DECODE(TRIM(GRANTEETYPE),'G','GROUP',null)||' '||GRANTEE||';'
				,', #','') GRANT
				FROM
				SYSIBM.SYSTABAUTH
				WHERE 1=1
				AND TRIM(TCREATOR) like '%" . trim($Sel_Schema) . "'
				AND TRIM(TTNAME) LIKE '%" . trim($Sel_Table) . "'
				AND GRANTEE NOT IN ('TUSC007')
				ORDER BY GRANTEE";
			$ret = $this->_db->getArrayByQuery($sql, []);
			//$this->_db->printSql();
			$res="\r\n";
			foreach($ret as $v)
			{
				$res.=$v['GRANT']."\r\n";
			}
			return $res;
		} catch (Exception $e) {
			throw $e;
		}
	}
}
