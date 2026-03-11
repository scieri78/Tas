<?php 
		
		
	class workflow_model
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
		
		public function getf()
		{
			try
			{
					$sql = "select * from dual";
                $res = $this->_db->getArrayByQuery($sql);	
				 
                return $res;
			}
			catch(Exception $e)
			{
				//echo "ddd";
				throw $e; 	
			}
			
		}
		
			
			
			
	 public function Addf()
		{
			try
			{	
				
				$sql = "INSERT INTO *****";
			
			
			$last_id = $this->_db->insertDb($sql, array('','',''));
		//	echo $this->_db->getsqlQuery();
		//	die();
		    return $last_id;
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}		
	 public function Removef($id)
		{
			try
			{	
				
			$sql = "DELETE FROM WORK_CORE.CORE_ALERT_MAIL WHERE ID_SH = ?";
			
			  $ret = $this->_db->deleteDb($sql, array($id));
			    return $ret;	
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();	
            	throw $e;
        	}
		}

		        //update record
		public function updatef()
		{
			
			try
			{	
        $sql = "UPDATE set b =? where c=? ";      

			    $values = array($v1, $v2);
				$ret = $this->_db->updateDb($sql, $values);
				
				return $ret;
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }	
		
		public function callf()
		{
			try
			{
		    $CallPlSql='CALL WFS.K_CONFIG.RimuoviValidazione(?, ?, ?, ?, ?, ? )';
            $stmt = db2_prepare($conn, $CallPlSql);

            $IdVal=$_POST['IdDip']; 
            $IdLeg=$_POST['IdLeg'];
			
			    $values = [
					 [
					'name' => 'IdWorkFlow',
					'value' => $IdWorkFlow,
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'IdVal',
					'value' => $IdVal,
					'type' => DB2_PARAM_IN
					],[
					'name' => 'IdLeg',
					'value' => $IdLeg,
					'type' => DB2_PARAM_IN
					],
					[
					'name' => 'User',
					'value' => $_SESSION['codname'],
					'type' => DB2_PARAM_IN
					],
					 [
					'name' => 'Errore',
					'value' => $Errore,
					'type' => DB2_PARAM_OUT
					],
					['name' => 'Note',
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
	
	
			$ret = $this->_db->callDb($CallPlSql ,$values);return $ret;
	 			
		
			}
			catch (Throwable $e) {
			  //  $this->_db->close_db();
			    throw $e;
			    
			}
			catch (Exception $e) 
			{
			    $this->_db->close_db();
            	throw $e;
        	}
        }


public function getFlussiWf($IdWorkFlow,$idFlu="")
		{
			try
			{
				$res="";
				$sqladd="";
				
				if($IdWorkFlow)
				{
					if($idFlu!='')
				{
					$sqladd="  AND ID_FLU = $idFlu ";
				}
				
				// if ( ( "$Dett" == "" and "$Tipo" == "E" ) || ( substr($Dett,0,8) == "WFS_TEST" and  "$Tipo" == "C" )
				$sql="SELECT *,
				 ( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU ) UTILIZZATO
				 
				,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND (
					  ( TIPO = 'E' AND ID_DIP IN ( SELECT ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_SH = 0 ) )
					  OR
					  ( TIPO = 'C' AND ID_DIP IN ( SELECT ID_CAR ID_DIP FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW  = $IdWorkFlow AND NOME_INPUT = 'WFS_TEST' ))
					)
					) LAB
				 ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI WHERE ID_FLU = F.ID_FLU AND ID_WORKFLOW = $IdWorkFlow ) USATO
				 ,(SELECT DISTINCT LIV FROM WFS.LEGAME_FLUSSI WHERE ID_FLU =  F.ID_FLU and ID_WORKFLOW = '$IdWorkFlow' ) LIV
				FROM 
					WFS.FLUSSI F
				WHERE 1=1
				   AND ID_WORKFLOW = $IdWorkFlow
				   $sqladd
				ORDER BY LIV,FLUSSO";				
				$res = $this->_db->getArrayByQuery($sql);	
				/*echo "<pre>";
				print_r($res);
				echo "</pre>";*/
				}
				return $res;
			}
			catch(Exception $e)
			{
				throw $e; 	
			}
		}
		
	
	}
	
?>