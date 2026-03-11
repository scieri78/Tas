<?php

class login_model
	{
		
	    
	    private $_db;
	    private $tableSpace;
	    private $tableNameUser;
	    private $tableNameMenu ;
	    //WEB.${FixAmb}_ TAS_
	    
	    private $strTableSpace;
		// set database config for mysql
		
		// open mysql data base
		
		 /**
		 * {@inheritDoc}
		 * @see db_driver::__construct()
		 */
		public function __construct($_dbname = '')
		{
		//	echo "_dbname:".$_dbname;
		    $this->_db = new db_driver($_dbname);
		    $this->strTableSpace = '';
			$this->tableSpace ="WEB.TAS_";
	       $this->tableNameUser ="UTENTI";
	       $this->tableNameMenu ="MENU";
			
		    if ($this->_db->getDriver() =='db2')
		    {
				//echo "driver"."<br>";
		        $this->strTableSpace = $this->tableSpace;
		    }
		
		}
		public function getDataBase()
        {
			return $this->_db->getDb();
		}
		
		   
        // select record    
		public function getDatiLogin($user,$pass)
        {
           $user = $this->selectUser($user,$pass);
           //$user = $this->_db->fetchAssocdb();
           // print_r($ret);
            $this->_db->db_log_debug("Utente loggato",'login',$user);
            if(isset($user))
            {
                $ret =$user;
            }
            else{
                 $ret =null;
            }
            return $ret;
        }
        
		
		public function getSetVar()
		{
			try
			{
			 	$sql="SELECT SITO,CAMPO,VALORE FROM WEB.SETTINGSITE WHERE SITO='TAS'";
				$res = $this->_db->getArrayByQuery($sql);
    			return $res;
			}
			catch(Exception $e)
			{
				
				throw $e; 	
			}
			
		}
		
		
        
        private function selectUser($user,$pass)
		{
			try
			{
			 	$sql = "SELECT * FROM ".$this->strTableSpace.$this->tableNameUser." gr
                    inner join ".$this->strTableSpace."WORKGROUP wg on gr.UK = wg.UK
                    where USERNAME = ? and PASSWORD = ?";
				$params =array($user,$pass);
				$res = $this->_db->getArrayByQuery($sql,$params,"ss");
    			return $res;
			}
			catch(Exception $e)
			{
				
				throw $e; 	
			}
			
		}
		
 public function selectUserProd($user)
		{
			try
			{
			 	$sql = "SELECT * FROM ".$this->strTableSpace.$this->tableNameUser." gr
                    inner join ".$this->strTableSpace."WORKGROUP wg on gr.UK = wg.UK
                    where USERNAME = ?";
				$params =array($user);
				$res = $this->_db->getArrayByQuery($sql,$params,"ss");
				//$this->_db->printSql();
				$this->_db->db_log_debug("Utente loggato",'login',$user);
    			return $res;
			}
			catch(Exception $e)
			{
				
				throw $e; 	
			}
			
		}
		
		
		
		public function getMenu()
		{
			
			/*$values = array("multisend","index",9);
			$ret = $this->_db->updateDb("UPDATE ".$this->strTableSpace.$this->tableNameMenu." SET CONTROLLER =?,ACTION=? WHERE MK =?", $values);	*/
		    if(isset($_SESSION['CodGroup']))
		    {
		       $menus = $this->getMenuLivello0();
				//echo $this->_db->getsqlQuery();
		        //print_r($menus);
		        $i=0;
		        foreach ($menus as $menu) {
		            
		            $retMenu[$i]['DESC']=$menu['MENU'];
		            $retMenu[$i]['CONTROLLER']=$menu['CONTROLLER'];
		            $retMenu[$i]['ACTION']=$menu['ACTION'];
		            $retMenu[$i]['SUB']=0;
					$retMenu[$i]['MK']=$menu['MK'];
		            $subMenus= $this->getSubMenu($menu['MK']);
					//$subMenus = $this->_db->fetchAssocdb();
		            if($subMenus)
		            {
		                
		               
		                $is=0;
		                if(count($subMenus))
		                {
		                    $retMenu[$i]['SUB']=1;
		                }
		                
		                foreach ($subMenus as $subMenu)
		                {
		                    $retMenu[$i]['SMENU'][$is]['DESC']=$subMenu['MENU'];
		                    $retMenu[$i]['SMENU'][$is]['CONTROLLER']=$subMenu['CONTROLLER'];
		                    $retMenu[$i]['SMENU'][$is]['ACTION']=$subMenu['ACTION'];
							$retMenu[$i]['SMENU'][$is]['MK']=$subMenu['MK'];
		                    $is++;
		                }
		            }
		            $i++;
		        }
		        // print_r($ret);
		        $this->_db->db_log_debug("Menu Utente",'Menu',$retMenu);
		        
		        $ret =$retMenu;
				
				//print_r($ret);
				//die();
		    }
		    else{
		        $ret =null;
		    }
		    return $ret;
		}
		public function getMenuRoutes($controller='')
		{
		    try
		    {
				
				$CodGroup = $_SESSION['CodGroup'];
				
				$sql="SELECT MK,MENU,PAGE,CONTROLLER,ACTION  from ".$this->strTableSpace.$this->tableNameMenu." where MK in 
				( select MK from ".$this->strTableSpace."MENU_ACCESS where GK in ( ".$CodGroup." )) ";
				
				if($controller!=''){
						$sql.=" and CONTROLLER='$controller' ";
				}
				
				
				$sql.="ORDER BY MENU";

				
		        $res = $this->_db->getArrayByQuery($sql,array(),"i");
				//	echo $this->_db->getsqlQuery()."<br><br>";
				
		        return $res;
		    }
		    catch(Exception $e)
		    {
		        
		        throw $e;
		    }
		    
		}
		
		private  function getMenuLivello0()
		{
		    try
		    {
				
				$CodGroup = $_SESSION['CodGroup'];
				
				$sql="SELECT MK,MENU,PAGE,CONTROLLER,ACTION  from ".$this->strTableSpace.$this->tableNameMenu." where LEVEL=0 and ABILITATO = 1 and MK in 
				( select MK from ".$this->strTableSpace."MENU_ACCESS where GK in ( ".$CodGroup." )) ";
				$sql.=" and (CONTROLLER <> '#' or  CONTROLLER is null)";
				$sql.=" and (PAGE is null or CONTROLLER <> '' or CONTROLLER is not null) ";
				$sql.="ORDER BY MK";

				
		        $res = $this->_db->getArrayByQuery($sql,array(),"i");
			//	echo $this->_db->getsqlQuery()."<br><br>";
				//die();
		        return $res;
		    }
		    catch(Exception $e)
		    {
		        
		        throw $e;
		    }
		    
		}
		
		
		private function getSubMenu($mk)
		{
		    try
		    {
				//echo "<h3/>getSubMenu<h3/>";
				$CodGroup = $_SESSION['CodGroup'];
				//$CodGroup = implode(",", $_SESSION['gk']);
				$sSottoMenu = "SELECT MK,MENU,PAGE,CONTROLLER,ACTION from ".$this->strTableSpace.$this->tableNameMenu."
							  where 1=1 
							  and UNDER = ?
							  and ABILITATO = 1 
							  and MK in ( select MK from ".$this->strTableSpace."MENU_ACCESS where GK in ($CodGroup)) ";
			   $sSottoMenu.=" and (PAGE is null or CONTROLLER <> '' or CONTROLLER is not null) ";
			   $sSottoMenu.=" and (CONTROLLER <> '#' or  CONTROLLER is null) ";
			   $sSottoMenu.="ORDER BY MENU";
		       
			   
			   $res = $this->_db->getArrayByQuery($sSottoMenu, array($mk),"i");
				
				//echo $this->_db->getsqlQuery()."<br><br>";
		        return $res;
		    }
		    catch(Exception $e)
		    {
		        
		        throw $e;
		    }
		    
		}
	}

?>