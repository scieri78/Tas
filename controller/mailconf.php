<?php
/** 
 * @property mailconf_model $_model
 */
class mailconf extends helper
{
   
    private $typesh;
    
    function __construct()
    {
      $this->_model = new mailconf_model();
	   $this->setDebug_attivo(0);
	}
	
    // mvc handler request
    public function index()
    {
		$_view['include_css'] = $this->include_css; 
		 include "view/header.php";
		 $this->contentList();
        include "view/footer.php";
       
    }
	
	public function contentList()
    {
	
	$TopScrollO=$_POST['TopScrollO'];
		 $AddMail=@$_POST['SelMail'];
		 if(isset($AddMail))
			 {
			 $SetVar=explode(';',$AddMail);
			 $IdSh=$SetVar[0];
			 $Type=$SetVar[1];
			 $IdMail=$SetVar[2];
			 $SelIdSh=$IdSh;
			 }
		$_model =$this->_model;	
		
		$DatiShListMail = $this->_model->getShListMail();
		$DatiListMail = $this->_model->getListMail();
		$DatiSelEnableShMail = $this->_model->getSelEnableShMail();
		
		
		$MailType=array('T_TO','T_CC','R_TO','R_CC');
		$all_shell=array();
		   
		$id_sh_old=0;
		$consh=0;
		foreach ($DatiShListMail as $row) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			$SH_DEBUG=$row['SH_DEBUG'];
			$DB_DEBUG=$row['DB_DEBUG'];
			$SH_MAIL_ENABLE=$row['SH_MAIL_ENABLE'];
			$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
			$TYPE=$row['TYPE'];
			$MAIL=$row['MAIL'];
			$MAIL_ENABLE=$row['MAIL_ENABLE'];
			$MULTI_SEND=$row['MULTI_SEND'];
			$CNT_RTO=$row['CNT_RTO'];
			$CNT_RCC=$row['CNT_RCC'];
			$CNT_TTO=$row['CNT_TTO'];
			$CNT_TCC=$row['CNT_TCC'];
			$SH_ATTC=$row['ATTACH'];
				  
			if (! in_array(array($ID_SH,$SHELL,$SHELL_PATH,$SH_DEBUG,$DB_DEBUG,$SH_MAIL_ENABLE,$MULTI_SEND,$CNT_RTO,$CNT_RCC,$CNT_TTO,$CNT_TCC,$SH_ATTC),$all_shell) ){
				array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND",$CNT_RTO,$CNT_RCC,$CNT_TTO,$CNT_TCC,$SH_ATTC));
			}
			if($id_sh_old!=$ID_SH){ $id_sh_old!=$ID_SH;$consh=0;} else{$consh++;}
			if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
				${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
				$this->typesh[$ID_SH] =array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
				$this->typesh[$ID_SH]['TYPE'][$consh] =$TYPE;
				
			} else {
				array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
			}
			
		}
		$mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];		
        include 'view/mailconf/index.php'; 
	 }
	
	public function AddMail()
    {
		$AddMail=@$_POST['SelMail'];
		$this->_model->AddMail($AddMail);
		$this->addShMail();
	}
	
	public function RemoveMail()
    {
		$AddMail=@$_POST['SelMail'];
		$this->_model->RemoveMail($AddMail);
		$this->addShMail();
	}
	
	public function UpdateMail()
    {
		$AddMail=@$_POST['SelMail'];
		$mval=@$_POST['mval'];
		$this->_model->updateMail($mval,$AddMail);
		$this->addShMail();
	}
	
	public function updateShMail()
    {
		$mval=@$_POST['mval'];
		$idsh=@$_POST['vIdSh'];
		$this->_model->updateShMail($mval,$idsh);
		$this->contentList();
	}
	
	public function apriFile()
    {
		$IDSH=$_POST['IDSH'];
		//$IDSH=140;
		include('view/mailconf/ApriFile.php');
	}
	
	public function addShMail()
    {
	$DatiShListMail = $this->_model->getShListMail(@$_REQUEST['id']);	
	$id_sh_old=0;
	$consh=0;
	$all_shell=[];
		foreach ($DatiShListMail as $row) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			$SH_DEBUG=$row['SH_DEBUG'];
			$DB_DEBUG=$row['DB_DEBUG'];
			$SH_MAIL_ENABLE=$row['SH_MAIL_ENABLE'];
			$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
			$TYPE=$row['TYPE'];
			$MAIL=$row['MAIL'];
			$MAIL_ENABLE=$row['MAIL_ENABLE'];
			$MULTI_SEND=$row['MULTI_SEND'];
			$CNT_RTO=$row['CNT_RTO'];
			$CNT_RCC=$row['CNT_RCC'];
			$SH_ATTC=$row['ATTACH'];
				  
			if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND",$CNT_RTO,$CNT_RCC,$SH_ATTC),$all_shell) ){
				array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND",$CNT_RTO,$CNT_RCC,$SH_ATTC));
			}
			if($id_sh_old!=$ID_SH){ $id_sh_old!=$ID_SH;$consh=0;} else{$consh++;}
			if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
				${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
				
				
			} else {
				array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
			}
			
		}
		
	$MailType=array('T_TO','T_CC','R_TO','R_CC');
	$ID_SH = @$_REQUEST['id'];
	$DatiListMail = $this->_model->getListMail();
	
	include('view/mailconf/shMail.php');
	
	}

   
}


?>