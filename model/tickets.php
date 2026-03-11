<?php
class tickets_model
{
	private $_db;
	public function __construct()
	{
		//$this->_db = new db_driver();
		$this->_db = new db_driver('',"tickets_model");
	//	$this->_db->open_db("__construct");
	}



	public function getAll($numTTRisolti, $FlgAssegnato = '', $ttuser = '', $ttuseraassegnato = '',$ttposizione='')
	{
		try {
			$USERNAME = $_SESSION['codname'];
			$sql = "SELECT tt.* ,u.NOMINATIVO ,u2.NOMINATIVO ASSEGNATO FROM WORK_ELAB.TICKETS  tt
			left join WEB.TAS_UTENTI u on u.USERNAME = tt.USERNAME			
			left join WEB.TAS_UTENTI u2 on u2.USERNAME = tt.ASSEGNATO			
			where tt.STATO <> 'Risolto' ";
			if ($FlgAssegnato) {
				$sql .= "AND tt.ASSEGNATO = '" . $USERNAME . "'";
			}

			if ($ttuser) {
				$sql .= "AND tt.USERNAME = '" . $ttuser . "' ";
			}

			if ($ttuseraassegnato) {
				$sql .= "AND tt.ASSEGNATO = '" . $ttuseraassegnato . "' ";
			}

			if ($ttposizione) {
				$sql .= "AND tt.POSIZIONE = '" . $ttposizione . "' ";
			}
			$sql .= "ORDER BY tt.TIPO ASC, tt.PRIORITA DESC, tt.ID ASC ";
			$res = $this->_db->getArrayByQuery($sql);
			$sql = "SELECT tt.*,u.NOMINATIVO,u2.NOMINATIVO ASSEGNATO FROM WORK_ELAB.TICKETS tt
			left join WEB.TAS_UTENTI u on u.USERNAME = tt.USERNAME	
			left join WEB.TAS_UTENTI u2 on u2.USERNAME = tt.ASSEGNATO	
			
			where tt.STATO ='Risolto' ";
			
			if ($ttuser) {
				$sql .= "AND tt.USERNAME = '" . $ttuser . "' ";
			}

			if ($ttuseraassegnato) {
				$sql .= "AND tt.ASSEGNATO = '" . $ttuseraassegnato . "' ";
			}

			$sql .= "ORDER BY tt.DATA_AGGIORNAMENTO DESC FETCH FIRST " . $numTTRisolti . " ROWS ONLY";
			$res2 = $this->_db->getArrayByQuery($sql);
			//$this->_db->printSql();
			return array_merge($res, $res2);
			// return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	//lista USERNAME WORK_ELAB.TICKETS  getUtentiTT
	public function getUtentiTT()
	{
		try {
			$sql = "SELECT tt.USERNAME,u.NOMINATIVO FROM WORK_ELAB.TICKETS tt
			left join WEB.TAS_UTENTI u on u.USERNAME = tt.USERNAME	
			group by tt.USERNAME,u.NOMINATIVO
			order by u.NOMINATIVO";
			$res = $this->_db->getArrayByQuery($sql);
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
	//lista ASSEGNATO WORK_ELAB.TICKETS  getUtentiAssegnati
	public function getUtentiAssegnati()
	{
		try {
			$sql = "SELECT tt.ASSEGNATO,u.NOMINATIVO FROM WORK_ELAB.TICKETS tt
			left join WEB.TAS_UTENTI u on u.USERNAME = tt.ASSEGNATO
			group by tt.ASSEGNATO,u.NOMINATIVO
			order by u.NOMINATIVO";
			$res = $this->_db->getArrayByQuery($sql);
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}





	public function getUtenti($IdGruppo)
	{
		try {
			$res = false;
			if (isset($IdGruppo)) {
				$sql = "SELECT w.ID_ASS,u.USERNAME USERNAME,u.NOMINATIVO NOMINATIVO
					from WEB.TAS_UTENTI u,
						 WFS.ASS_GRUPPO  w
					where 1=1 
						 and w.ID_UK = u.UK
						 and w.ID_GRUPPO = '$IdGruppo'                                   
         ";
				$res = $this->_db->getArrayByQuery($sql);
			}
			return $res;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}



	public function myTicket()
	{
		try {
			$USERNAME = $_SESSION['codname'];
			$sql = "SELECT * FROM WORK_ELAB.TICKETS where USERNAME = '" . $USERNAME . "'";
			$sql .= "ORDER BY TIPO ASC, PRIORITA DESC, ID ASC";
			$res = $this->_db->getArrayByQuery($sql);

			return $res;
			// return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	public function getById($id)
	{
		try {
			$sql = "SELECT * FROM WORK_ELAB.TICKETS WHERE ID = ? ";
			$res = $this->_db->getArrayByQuery($sql, [$id]);
			return $res;
			// return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}



	public function create($posizione, $titolo, $descrizione, $stato, $tipo, $priorita, $assegnato, $valida)
	{
		try {
			$valida = $valida ? TRUE : FALSE;

			$sql = "INSERT INTO WORK_ELAB.TICKETS (POSIZIONE,TITOLO, DESCRIZIONE, STATO, USERNAME,DATA_AGGIORNAMENTO,TIPO,PRIORITA,ASSEGNATO,VALIDA) VALUES (?,?,?,?,?,CURRENT_TIMESTAMP,?,?,?,?)";
			$USERNAME = $_SESSION['codname'];
			//$today = date("Y-m-d H:i:s", time());
			$last_id = $this->_db->insertDb($sql, [$posizione, $titolo, $descrizione, $stato, $USERNAME, $tipo, $priorita, $assegnato, $valida]);
			//$this->update($last_id,$posizione, $titolo, $descrizione, $stato);
			//	$this->_db->printSql();
			//	die();
			return $last_id;
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}


	public function update($id, $posizione, $titolo, $descrizione, $stato, $tipo, $priorita, $assegnato, $valida)
	{
		try {
			$valida = $valida ? TRUE : FALSE;
			$sql = "UPDATE WORK_ELAB.TICKETS
			 SET POSIZIONE = ?, TITOLO = ?, DESCRIZIONE = ?, STATO = ?, DATA_AGGIORNAMENTO = CURRENT_TIMESTAMP , TIPO =? , PRIORITA =? , ASSEGNATO =? , VALIDA = ?			 
			 WHERE ID = ?";
			$assegnato = $stato == 'Risolto' ? $_SESSION['codname'] : $assegnato;
			return $this->_db->updateDb($sql, [$posizione, $titolo, $descrizione, $stato, $tipo, $priorita, $assegnato, $valida, $id]);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	public function delTicket($id)
	{
		try {
			$sql = "DELETE FROM WORK_ELAB.TICKETS WHERE ID = ?";
			return $this->_db->deleteDb($sql, [$id]);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}

	public function updateStato($id, $stato)
	{
		try {

			//	 ['EU8736D','RU20903', 'EU8738M'];
			//['Aperto', 'Da fare poi',  'In Lavorazione', 'Richiesta Info', 'Test', 'Risolto'];
			switch ($stato) {
				case 'Aperto':
					$assegnato = "EU8736D";
					break;
				case 'Da fare poi':
					$assegnato = "RU20903";
					break;
				case 'In Lavorazione':
					$assegnato = "EU8736D";
					break;
				case 'Richiesta Info':
					$assegnato = "RU20903";
					break;
				case 'Test':
					$assegnato = "EU8738M";
					break;
				case 'Risolto':
					$assegnato = $_SESSION['codname'];
					break;
			}
			$sql = "UPDATE WORK_ELAB.TICKETS SET STATO = ?, ASSEGNATO = ?, DATA_AGGIORNAMENTO = CURRENT_TIMESTAMP WHERE ID = ?";
			return $this->_db->updateDb($sql, [$stato, $assegnato, $id]);
		} catch (Exception $e) {
			//echo "ddd";
			throw $e;
		}
	}
}
