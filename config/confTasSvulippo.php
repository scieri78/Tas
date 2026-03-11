<?php

class config extends absConfig
{	
  
    
    
    public function __construct($db_name="") {
        //$pswDb2 = 'V0ZTcDQ1NXR1c2lu';
        $pswDb2 = $_SERVER['TASPDBW'];
        $this->host = 'faitssidbteav01.te.azi.allianzit';
        $this->user = "tusin107";
        $this->pass = base64_decode($pswDb2);
        $this->db = "TASPCUSR";
        $this->port = "50003";
        $this->driver = "db2";
        $aSITO =$_COOKIE[$_COOKIE['tab_id']];
		//$db_name=($db_name)?$db_name:$_SESSION['aSITO'];
		$db_name=($db_name)?$db_name:$aSITO;
        if($db_name=="work")
		{
			$this->db = 'TASPCWRK';
			$this->port = 50001;
		}
    }
}

?>