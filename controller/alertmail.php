<?php
/** 
 * @property alertmail_model $_model
 */
class alertmail extends helper
{
    
    private $typesh;
    
    function __construct()
    {
       $this->_model = new alertmail_model();
	   $this->include_css = '
	   <link rel="stylesheet" href="./view/alertmail/CSS/index.css?p='.rand(1000,9999).'">';
	   $this->setDebug_attivo(1);
	   
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
		$datiShList = $this->_model->getShList();
		$ListMail = $this->_model->getListMail();		
		$datiTableList = $this->_model->getTableList();
		$mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];
		include "view/alertmail/index.php";
    }
	
	public function addShMail()
    {
		$id = @$_POST['id'];
		$datiShList = $this->_model->getShList();
		$ListMail = $this->_model->getListMail();
		$datiTableList = $this->_model->getTableList($id);
		include "view/alertmail/addShMail.php";
	}
	
	public function addmail()
    {
		$ID_SH = @$_POST['ID_SH'];
		$ID_MAIL_ANAG = @$_POST['ID_MAIL_ANAG'];
		$FLG_START = @$_POST['FLG_START'];
		$FLG_END = @$_POST['FLG_END'];		
		$this->_model->AddMail($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END);
		$this->contentList();
	}
	
	public function removeshmail()
    {
		$vIdSh = @$_POST['vIdSh'];
		$this->_model->RemoveShMail($vIdSh);
		$this->contentList();
	}
	
	public function updateShMail()
    {
		$IDSH = @$_POST['id'];
		$IDANAGMAIL = @$_POST['IDANAGMAIL'];
		$TYPEMAIL = @$_POST['TYPEMAIL'];
		$FLAG = @$_POST['FLAG'];
		$this->_model->updateShMail($IDSH,$IDANAGMAIL,$TYPEMAIL,$FLAG);
		$this->addShMail();
	}
	public function RemoveMail()
    {
		$RemIdSh = @$_POST['id'];
		$RemIdMailAnag = @$_POST['IDANAGMAIL'];
		$this->_model->RemoveMail($RemIdSh,$RemIdMailAnag);
		$this->addShMail();
	}
	
	public function addInShMail()
    {
		$ID_SH = @$_POST['id'];
		$ID_MAIL_ANAG = @$_POST['ID_MAIL_ANAG'];
		$FLG_START = @$_POST['FLG_START'];
		$FLG_END = @$_POST['FLG_END'];		
		$this->_model->addShMail($ID_SH, $ID_MAIL_ANAG, $FLG_START, $FLG_END);
		$this->addShMail();
	}
	
   
}




?>