<?php
require_once './library/xlsxtocsv/SimpleXLSX.php';
require_once './library/simplexlsxgen/SimpleXLSXGen.php';

require_once './library/PHPExcel/Classes/PHPExcel/IOFactory.php';
//require_once './library/php-zip/src/ZipFile.php';


use SimpleXLSX;
use Shuchkin\SimpleXLSXGen;



class loadconfig_model
{
	// set database config for mysql
	// open mysql data base
	private $_db;

	private $_tableSyscatTables = 'syscat.tables';
	private $_tableLoadAnag = 'WFS.LOAD_ANAG';
	private $_tableLoadFile = 'WFS.LOAD_FILE';
	private $_tableLoadTarget = 'WFS.LOAD_TARGET';
	private $_tableLoadCheck = 'WFS.LOAD_CHECK';

	/**
	 * {@inheritDoc}
	 * @see db_driver::__construct()
	 */
	public function __construct()
	{
		$this->_db = new db_driver();
	}

	/**
	 * getSchema
	 *
	 * @return void
	 */
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

	/**
	 * getTable
	 *
	 * @param  mixed $Sel_Schema
	 * @return void
	 */
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

	/**
	 * getTableOutput
	 *
	 * @param  mixed $Sel_Schema
	 * @param  mixed $TAB_TARGET
	 * @return void
	 */
	public function getTableOutput($Sel_Schema = '', $TAB_TARGET = '')
	{

		if ($Sel_Schema != "" and $TAB_TARGET != "") {
			try {
				if ($Sel_Schema != "") {
					$sql = "Select NAME, COLTYPE, LENGTH, SCALE from Sysibm.syscolumns WHERE TBNAME = ? and TBCREATOR = ? ORDER BY COLNO";

					$res = $this->_db->getArrayByQuery($sql, [$TAB_TARGET, $Sel_Schema]);
					//$this->_db->printSql();
					$tabOutput = [];
					foreach ($res as $k => $v) {
						$tabOutput[$k]['COLONNA'] = $v['NAME'];
						$type = trim($v['COLTYPE']);
						switch (trim($v['COLTYPE'])) {
							case 'INTEGER':
								$tabOutput[$k]['TIPO'] = $type;
								break;
							case 'DATE':
								$tabOutput[$k]['TIPO'] = $type;
								break;
							case 'SMALLINT':
								$tabOutput[$k]['TIPO'] = $type;
								break;
							case 'DECIMAL':
								$tabOutput[$k]['TIPO'] = $type . ' (' . $v['LENGTH'] . ',' . $v['SCALE'] . ')';
								break;
							default:
								$tabOutput[$k]['TIPO'] = $type . ' (' . $v['LENGTH'] . ')';
								break;
						}
					}
				}
				return $tabOutput;
			} catch (Exception $e) {
				throw $e;
			}
		}
	}


	/**
	 * getDatiFile
	 *
	 * @param  mixed $FOGLIO
	 * @param  mixed $RIGA_INZ
	 * @return void
	 */
	public function getDatiFile($FOGLI = '', $RIGA_INZ = 0)
	{
		
		$infile = $_FILES["FileInput"]["tmp_name"];
		$fileName = $_FILES["FileInput"]["name"];
		$ret = [];
		if ($infile) {
			$fileType = PHPExcel_IOFactory::identify($infile);
			if ($fileType != 'CSV') {
		//		$outfile = $_SESSION['PSITO'] . '/TMP/' . $fileName . ".csv";
		//		$this->convertXLStoCSV($infile, $outfile,$FOGLI);
				$ret = $this->leggiRigaDaFoglioExcel($infile, $FOGLI[0], $RIGA_INZ);
			} else {
				$outfile = $infile;
				$ret = $this->getIntestazioneFileCsv($outfile,  $RIGA_INZ);
			}			
			
		}
	//	unlink($outfile);
		return $ret;
	}
	

	public function leggiRigaDaFoglioExcel($percorsoFile, $nomeFoglio, $numeroRiga) {
		// Carica il file Excel
		$spreadsheet = PHPExcel_IOFactory::load($percorsoFile);
		
		// Controlla se il foglio esiste
		$foglio = $spreadsheet->getSheetByName($nomeFoglio);
		if (!$foglio) {
		throw new Exception("Il foglio '$nomeFoglio' non esiste nel file Excel.");
		}
		
		// Legge le celle della riga specificata
		$rigaDati = [];
		foreach ($foglio->getColumnIterator() as $colonna) {
		$cell = $foglio->getCell($colonna->getColumnIndex() . $numeroRiga);
		$rigaDati[] = $cell->getValue();
		}
		
		return $rigaDati;
		}
		


	/**
	 * convertXLStoCSV
	 *
	 * @param  mixed $infile
	 * @param  mixed $outfile
	 * @return void
	 */
	function convertXLStoCSV($infile, $outfile,$FOGLI)
	{
		$fileType = PHPExcel_IOFactory::identify($infile);
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($infile);
		$objPHPExcel->setActiveSheetIndexByName($FOGLI[0]);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
		$objWriter->setDelimiter(';');
				
		$objWriter->setUseBOM(true);
		$objWriter->save($outfile);
		
		shell_exec('chmod 774 '. $outfile);
	}
	
	/**
	 * getIntestazioneFileCsv
	 *
	 * @param  mixed $fileTempName
	 * @param  mixed $RIGA_INZ
	 * @return void
	 */
	public function getIntestazioneFileCsv($fileTempName,  $RIGA_INZ = 1)
	{
		$row = 1;
		$file = fopen($fileTempName, "r");
		while ((($data = fgetcsv($file, 8000, ';')) !== FALSE) && $row <= $RIGA_INZ) {
			$row++;
			$ret = $data;
		}
		fclose($file);
		return $ret;
	}



	/**
	 * setDataFile
	 *
	 * @param  mixed $dataFile
	 * @param  mixed $dataTarghet
	 * @return void
	 */
	/**
	 * setDataFile
	 *
	 * @param  mixed $dataFile
	 * @param  mixed $dataTarghet
	 * @return void
	 */
	public function setDataFile($dataFile, &$dataTarghet)
	{
		$ret = [];

		foreach ($dataFile as $k => $col) {
			$ret[$k]['COLONNA'] =  trim(strtoupper(str_replace(" ","_",$col)));			
			//	$ret[$k]['TIPO'] = '';
		}
		//$this->_db->error_message('dataFile ret' ,$ret);
		//$this->_db->error_message('dataTarghet' ,$dataTarghet);
		foreach ($dataTarghet as $kt => $Targhet) {
		$dataTarghet[$kt]['FORMULA'] = '';
		}

		foreach ($dataTarghet as $kt => $Targhet) {
			foreach ($ret as $k => $col) {
				if (($col['COLONNA']) && $col['COLONNA'] == $Targhet['COLONNA']) {
					//	$ret[$k]['TIPO'] = $Targhet['TIPO'];
					$dataTarghet[$kt]['FORMULA'] = $col['COLONNA'];
				}
			}
		}
		return $ret;
	}
	/**
	 * getFogli
	 *
	 * @return void
	 */
	public function getFogli()
	{

		$ret = [];
		//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		//$ArrayGruppo = $this->getGruppi($ID_GRUPPO);
		//$GRUPPO = $ArrayGruppo[0]['DESCR'];		
		$fileTempName = $_FILES["FileInput"]["tmp_name"];
		$fileName = $_FILES["FileInput"]["name"];
		//$this->updateValiditaControllo($ID_GRUPPO, 0);
		if ($fileTempName) {
			//$worksheet = $excelObj->getSheet(0);			
			//	$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			$fileType = PHPExcel_IOFactory::identify($fileTempName);
			$objReader = PHPExcel_IOFactory::createReader($fileType);
			//Excel5 is the type of excel file.
			$objReader->setReadDataOnly(true);
			//$excelReader = PHPExcel_IOFactory::createReaderForFile($fileTempName);
			$excelObj = $objReader->load($fileTempName);
			$sheets = $excelObj->getSheetNames();

			if ($fileType == "CSV") {
				$ret[] = $fileType;

				// $xlsx = SimpleXLSX::parse($_FILES["FileInput"]["tmp_name"]);

			} elseif ($sheets) {
				//$sheets = $xlsx->sheetNames();
				$oldTiPO = '';

				foreach ($sheets as $index => $NOME_FOGLIO) {
					$ret[] = $NOME_FOGLIO;
				}
			}
		}
		//$ret[] = $fileType;
		return $ret;
	}

	/**
	 * deleteconfig
	 *
	 * @param  mixed $FLUSSO
	 * @param  mixed $FOGLIO
	 * @param  mixed $TAB_TARGET
	 * @return void
	 */
	public function deleteconfig($ID_LOAD_ANAG,$FLUSSO)
	{
		try {
			$_SESSION['error_message'] = "";
			if($this->verificaIdLoadAnag($ID_LOAD_ANAG,$FLUSSO))
				{				
					$_SESSION['error_message'] = "Nome Flusso (".$FLUSSO.") esistente!" ;					
					 return 0;
				}


			if ($ID_LOAD_ANAG) {
				/*$sql = 'DELETE FROM ' . $this->_tableLoadAnag . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);*/
				$sql = 'DELETE FROM ' . $this->_tableLoadFile . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);
				$sql = 'DELETE FROM ' . $this->_tableLoadTarget . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);
			}

			//$this->_db->error_message("insertTipoByTesto ID_TIPO", $ID_TIPO);
			return true;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

		public function deleteconfigTot($ID_LOAD_ANAG)
	{
		try {
			if ($ID_LOAD_ANAG) {
				$sql = 'DELETE FROM ' . $this->_tableLoadAnag . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);
				$sql = 'DELETE FROM ' . $this->_tableLoadFile . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);
				$sql = 'DELETE FROM ' . $this->_tableLoadTarget . ' WHERE ID_LOAD_ANAG = ?';
				$this->_db->deleteDb($sql, [$ID_LOAD_ANAG]);
			}

			//$this->_db->error_message("insertTipoByTesto ID_TIPO", $ID_TIPO);
			return true;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	/**
	 * selectConfig
	 *
	 * @param  mixed $FLUSSO
	 * @param  mixed $FOGLIO
	 * @param  mixed $TAB_TARGET
	 * @return void
	 */
	public function selectConfig($TAB_TARGET='')
	{
		try {
			$sql = 'select * from ' . $this->_tableLoadAnag . ' where 1=1 ';
			if($TAB_TARGET)
			{
				$sql.="AND TAB_TARGET = '".$TAB_TARGET."'";
			}
			$LoadConfig = $this->_db->getArrayByQuery($sql, []);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $LoadConfig;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	public function selectLoadAnag($ID_LOAD_ANAG)
	{
		try {
			$sql = 'select * from ' . $this->_tableLoadAnag . ' where ID_LOAD_ANAG = ?';
			$LoadConfig = $this->_db->getArrayByQuery($sql, [$ID_LOAD_ANAG]);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $LoadConfig;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function getIdLoadAnagByFlusso($Flusso)
	{
		try {
			$sql = 'select ID_LOAD_ANAG from ' . $this->_tableLoadAnag . ' where FLUSSO = ?';
			$LoadConfig = $this->_db->getArrayByQuery($sql, [$Flusso]);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $LoadConfig[0]['ID_LOAD_ANAG'];
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function  verificaIdLoadAnag($ID_LOAD_ANAG,$FLUSSO)
	{
		try {
			$ID_LOAD_ANAG=$ID_LOAD_ANAG?$ID_LOAD_ANAG:0;
			$sql = "SELECT COALESCE(SUM(1), 0) AS VER
								FROM WFS.LOAD_ANAG
								WHERE FLUSSO = ? 
							--	OR (FLUSSO <> ? AND ID_LOAD_ANAG = ? )
								";
			$array_par=[$FLUSSO];
			if($ID_LOAD_ANAG) {
				$sql.=" AND ID_LOAD_ANAG <> ? ";
				$array_par=[$FLUSSO,$ID_LOAD_ANAG];
			}
			$LoadConfig = $this->_db->getArrayByQuery($sql, $array_par);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $LoadConfig[0]['VER'];
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * selectLoadFile
	 *
	 * @param  mixed $ID_LOAD_ANAG
	 * @return void
	 */
	public function selectLoadFile($ID_LOAD_ANAG)
	{
		try {
			$sql = 'select * from ' . $this->_tableLoadFile . ' where ID_LOAD_ANAG = ? ORDER BY ORDINE ASC';
			$LoadConfig = $this->_db->getArrayByQuery($sql, [$ID_LOAD_ANAG]);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $LoadConfig;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}
	/**
	 * getCheck
	 *
	 * @param  mixed $ID_LOAD_ANAG
	 * @return void
	 */
	public function getCheck($ID_LOAD_ANAG, $ID_LOAD_CHECK = '')
	{
		try {
			$sql = 'select * from ' . $this->_tableLoadCheck . ' where ID_LOAD_ANAG = ' . $ID_LOAD_ANAG . '';
			if ($ID_LOAD_CHECK) {
				$sql .= " and ID_LOAD_CHECK = " . $ID_LOAD_CHECK . "";
			}
			$ret = $this->_db->getArrayByQuery($sql, []);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	public function delCheck($ID_LOAD_CHECK)
	{
		try {
			$sql = 'DELETE FROM ' . $this->_tableLoadCheck . ' where ID_LOAD_CHECK = ' . $ID_LOAD_CHECK . '';

			$ret = $this->_db->deleteDb($sql, []);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
			return $ret;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}


	public function modCheck($ID_LOAD_CHECK, $ID_LOAD_ANAG, $LABEL, $TEST)
	{
		try {
			if ($ID_LOAD_CHECK) {
				$sql = 'DELETE FROM ' . $this->_tableLoadCheck . ' where ID_LOAD_CHECK = ' . $ID_LOAD_CHECK . '';
				$ret = $this->_db->deleteDb($sql, []);
			}
			$sql = 'INSERT INTO ' . $this->_tableLoadCheck . ' (ID_LOAD_ANAG, LABEL, TEST) VALUES (?, ?, ?)';
			$this->_db->insertDb($sql, [$ID_LOAD_ANAG, $LABEL, $TEST]);
			//$this->_db->printSql();
			//$this->_db->error_message("selectConfig LoadConfig", $LoadConfig);
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
	 * selectLoadTarget
	 *
	 * @param  mixed $ID_LOAD_ANAG
	 * @return void
	 */
	public function selectLoadTarget($ID_LOAD_ANAG, $DatiLoadTarget)
	{
		try {
			$sql = 'select * from ' . $this->_tableLoadTarget . ' where ID_LOAD_ANAG = ?';
			$DatiLoadConfig = $this->_db->getArrayByQuery($sql, [$ID_LOAD_ANAG]);
			foreach ($DatiLoadTarget as $k => $LoadTarget) {
				foreach ($DatiLoadConfig as $LoadConfig) {
					if ($LoadTarget['COLONNA'] == $LoadConfig['COLONNA']) {
						$DatiLoadTarget[$k]['FORMULA'] = $LoadConfig['FORMULA'];
						$DatiLoadTarget[$k]['TRUNC'] = $LoadConfig['TRUNC'];
					}
				}
			}

			//$this->_db->printSql();
			/*	$this->_db->error_message("selectConfig DatiLoadTarget", $DatiLoadTarget);
			$this->_db->error_message("selectConfig DatiLoadConfig", $DatiLoadConfig);*/
			return $DatiLoadTarget;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * insertConfig
	 *
	 * @param  mixed $FLUSSO
	 * @param  mixed $FOGLIO
	 * @param  mixed $RIGA_INZ
	 * @param  mixed $TAB_TARGET
	 * @return void
	 */
	public function insertConfig($ID_LOAD_ANAG, $FLUSSO, $FOGLIO, $RIGA_INZ, $TAB_TARGET, $DESCR,$OPT_DEL,$MANIPOL)
	{
		try {
			$TMS_INSERT = date('Y-m-d H:i:s');
			//$OPT_DEL = str_replace("'","\''",$OPT_DEL);
			//	$DESCR = $FLUSSO . " - " . $FOGLIO;
		 //   $ID_LOAD_ANAG=$this->getIdLoadAnagByFlusso($FLUSSO);
			if ($ID_LOAD_ANAG) {			
				$_SESSION['error_message'] ="";
				$sql = "UPDATE " . $this->_tableLoadAnag . "  set 
				FLUSSO = ?, TMS_INSERT  = ?, DESCR  = ?, FOGLIO  = ?, RIGA_INZ  = ?, TAB_TARGET  = ? ,OPT_DEL = ?,MANIPOL = ?
				WHERE ID_LOAD_ANAG = ? ";
				$this->_db->updateDb($sql, [$FLUSSO, $TMS_INSERT, $DESCR, $FOGLIO, $RIGA_INZ, $TAB_TARGET,$OPT_DEL, $MANIPOL, $ID_LOAD_ANAG]);				
			} else {			
				$sql = 'INSERT INTO ' . $this->_tableLoadAnag . ' (FLUSSO, TMS_INSERT, DESCR, FOGLIO, RIGA_INZ, TAB_TARGET,OPT_DEL,MANIPOL) VALUES (?,?,?,?,?,?,?,?)';
				$ID_LOAD_ANAG = $this->_db->insertDb($sql, [$FLUSSO, $TMS_INSERT, $DESCR, $FOGLIO, $RIGA_INZ, $TAB_TARGET,$OPT_DEL,$MANIPOL]);	
			}
			//$this->_db->error_message("insertTipoByTesto ID_TIPO", $ID_TIPO);
			return $ID_LOAD_ANAG;
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

	/**
	 * insertFile
	 *
	 * @param  mixed $ID_LOAD_ANAG
	 * @param  mixed $DatiFileName
	 * @param  mixed $DatiFileType
	 * @return void
	 */
	public function insertFile($ID_LOAD_ANAG, $DatiFileName, $DatiFileType, $DatiAlias,$DatiDelNull)
	{
		try {
			foreach ($DatiFileName as $k => $FileName) {
				//'FileInput_NomeCampo'
				//'FileInput_Type'
				$COLONNA = $FileName;
				$Alias = $DatiAlias[$k];
				$DelNull = $DatiDelNull[$k]?1:0;
				$TIPO = $DatiFileType[$k];	

				$sql = 'INSERT INTO ' . $this->_tableLoadFile . ' (ID_LOAD_ANAG, ORDINE, COLONNA, COLALIAS,DELNULL) VALUES (?,?,?,?,?)';
				$ID = $this->_db->insertDb($sql, [$ID_LOAD_ANAG, $k, $COLONNA, $Alias,$DelNull]);
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
	 * insertTarget
	 *
	 * @param  mixed $ID_LOAD_ANAG
	 * @param  mixed $DatiTargetName
	 * @param  mixed $DatiTargetType
	 * @param  mixed $DatiTargetFormula
	 * @return void
	 */
	public function insertTarget($ID_LOAD_ANAG, $DatiTargetName, $DatiTargetType, $DatiTargetFormula,$DatiTargetTrunc)
	{
		try {
			foreach ($DatiTargetName as $k => $Name) {
				//'FileInput_NomeCampo'
				//'FileInput_Type'
				$COLONNA = $Name;
				$TIPO = $DatiTargetType[$k];
				$FORMULA = $DatiTargetFormula[$k];
				$TRUNC = $DatiTargetTrunc[$k];
				

				$sql = 'INSERT INTO ' . $this->_tableLoadTarget . ' ( ID_LOAD_ANAG, ORDINE, COLONNA,  FORMULA, TRUNC)  VALUES (?,?,?,?,?)';
				$ID = $this->_db->insertDb($sql, [$ID_LOAD_ANAG, $k, $COLONNA, $FORMULA, $TRUNC]);
				//$this->_db->printSql();
			}
		} catch (Throwable $e) {
			//  $this->_db->close_db();
			throw $e;
		} catch (Exception $e) {
			$this->_db->close_db();
			throw $e;
		}
	}

/*	WFS.K_LOADER.AddFileCol(
		I_ID_LOAD_ANAG IN INTEGER,
 		I_PRECCOLNAME  IN VARCHAR2,
		I_COLNAME      IN VARCHAR2,
		O_ERROR        OUT INTEGER,
		O_NOTE         OUT VARCHAR2
		)*/


		public function AddFileCol($I_ID_LOAD_ANAG,$I_PRECCOLNAME,$I_COLNAME)
		{
			try {
				$CallPlSql = 'CALL WFS.K_LOADER.AddFileCol(?, ?, ?, ?, ?)';				
				$Errore=0;
				$Note="";
				
	
				$values = [
					[
						'name' => 'I_ID_LOAD_ANAG',
						'value' => $I_ID_LOAD_ANAG,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'I_PRECCOLNAME',
						'value' => $I_PRECCOLNAME,
						'type' => DB2_PARAM_IN
					], [
						'name' => 'I_COLNAME',
						'value' => $I_COLNAME,
						'type' => DB2_PARAM_IN
					],					
					[
						'name' => 'O_ERROR',
						'value' => $Errore,
						'type' => DB2_PARAM_OUT
					],
					[
						'name' => 'O_NOTE',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]
				];
	
				/*  db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
				db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
				db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
				db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
				db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
				db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/
	
	
				$ret = $this->_db->callDb($CallPlSql, $values);
	
	
				return $ret;
			} catch (Throwable $e) {
				//  $this->_db->close_db();
				throw $e;
			} catch (Exception $e) {
				$this->_db->close_db();
				throw $e;
			}
		}



		public function DelFileCol($I_ID_LOAD_ANAG,$I_COLNAME)
		{
			try {
				$CallPlSql = 'CALL WFS.K_LOADER.DelFileCol(?, ?, ?, ?)';				
				$Errore=0;
				$Note="";
				
	
				$values = [
					[
						'name' => 'I_ID_LOAD_ANAG',
						'value' => $I_ID_LOAD_ANAG,
						'type' => DB2_PARAM_IN
					],
					[
						'name' => 'I_COLNAME',
						'value' => $I_COLNAME,
						'type' => DB2_PARAM_IN
					],					
					[
						'name' => 'O_ERROR',
						'value' => $Errore,
						'type' => DB2_PARAM_OUT
					],
					[
						'name' => 'O_NOTE',
						'value' => $Note,
						'type' => DB2_PARAM_OUT
					]
				];
	
				/*  db2_bind_param($stmt, 1, "IdWorkFlow"       , DB2_PARAM_IN);
				db2_bind_param($stmt, 2, "IdVal"            , DB2_PARAM_IN);
				db2_bind_param($stmt, 3, "IdLeg"            , DB2_PARAM_IN);
				db2_bind_param($stmt, 4, "User"             , DB2_PARAM_IN);
				db2_bind_param($stmt, 5, "Errore"           , DB2_PARAM_OUT);
				db2_bind_param($stmt, 6, "Note"             , DB2_PARAM_OUT);*/
	
	
				$ret = $this->_db->callDb($CallPlSql, $values);
	
	
				return $ret;
			} catch (Throwable $e) {
				//  $this->_db->close_db();
				throw $e;
			} catch (Exception $e) {
				$this->_db->close_db();
				throw $e;
			}
		}








}
