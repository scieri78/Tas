<?php
/** 
 * @property emailanag_model $_model
 */
class emailanag extends helper
{

    
    function __construct()
    {
       $this->_model =  new emailanag_model();
       $this->setDebug_attivo(1);
       $this->include_css = '
	   <link rel="stylesheet" href="./view/emailanag/CSS/index.css?p='.rand(1000,9999).'" />	 
	   ';
    }
    // mvc handler request
    public function index()
    {
        $action = @$_POST['action'];
        
        if ( $action == "update" ){
            $this->update();
        }
        $this->error_message = "";
        $this->list();
    }
    
    // add new record
    public function addnewmail()
    {
      
        try{

            if (isset($_GET['AddUsername']))
            {
                // read form value
                $AddName     =$_GET['AddName'];
                $AddUsername =$_GET['AddUsername'];
                $AddMail     =$_GET['AddMail'];
                //call validation
                $chk=1;
                $this->error_message = "";
                $chk=$this->checkValidation($_GET);
                if($chk)
                {
                    //call insert record
                    $pid = $this->_model->AddMail($AddName,$AddUsername,$AddMail);
                    if($pid==0){
                      $this->error_message .= "Impossibile inserire la Mail!<br>";
					                          
                    }else{
                    $this->get_info_message("Email inserita correttamente!");
                }
                }
            }
       
   $this->contentList(); 
            
        }catch (Exception $e)
        {
           
          //  $this->close_db();
            $this->log_message($e);
            
           throw $e;
        }
    }
    
    
    public function checkValidation($fields)
    {    $noerror=true;
    // Validate category
    
    if(empty($fields['AddUsername'])){
        $this->error_message .=  "Email field is empty.<br>";
        $noerror=false;
    }
    if(empty($fields['AddName'])){
        $this->error_message .=  "Name field is empty.<br>";
        $noerror=false;
    }
    if(empty($fields['AddMail'])){
        $this->error_message .=  "Email field is empty.<br>";
        $noerror=false;
    } elseif(!filter_var($fields['AddMail'], FILTER_VALIDATE_EMAIL)){
        $this->error_message .=  "Invalid Email.<br>";
        $noerror=false;
    }
    
    
    
    return $noerror;
    }
    // update record
    public function update()
    {
        try
        {
            if (isset($_REQUEST['id']))
            {
                $id=$_GET['id'];
                $res=$this->_model->updateRecord($_REQUEST['field'],$_REQUEST['uval'],$_REQUEST['id']);
                $this->contentList(); 
            }else{
                echo "Invalid operation.";
            }
        }
        catch (Throwable $e) {
            include "view/header.php";
           throw $e;
        }
        catch (Exception $e)
        {
          //  $this->close_db();
            throw $e;
        }
    }
    // delete record
    public function delete()
    {
        try
        {
            if (isset($_GET['id']))
            {
                $id=$_GET['id'];
                $res=$this->_model->deleteRecord($id);
                $this->contentList(); 
            }else{
                echo "Invalid operation.";
            }
        }
        catch (Exception $e)
        {
          //  $this->close_db();
            throw $e;
        }
    }
	
	
	
    public function list(){
         $_view['include_css'] = $this->include_css; 
       include "view/header.php";
        $this->contentList();
       
        include "view/footer.php";
    }    
	
	public function contentList(){
      
        $this->get_errors_message();
        $datiControlliAnag = $this->_model->getListMail();
        //$this->debug('datiControlliAnag',$datiControlliAnag);

      //  $_SESSION['sporttbl0']=serialize($sporttb);//add session obj
	   $AddName     =@$_POST['AddName'];
	   $AddUsername =@$_POST['AddUsername'];
	   $AddMail     =@$_POST['AddMail'];
	   $mailPage = @$_POST['mailPage'];
        $mailLength = @$_POST['mailLength'];		
        $mailSearch = @$_POST['mailSearch'];		
        $mailOrdern = @$_POST['mailOrdern'];		
        $mailOrdert = @$_POST['mailOrdert'];
        include "view/emailanag/index.php";
     
    }


public function formSh()
    {
	$NAME = @$_POST['NAME'];
    $USERNAME = @$_POST['USERNAME'];
    $MAIL = @$_POST['MAIL'];
    $ID_MAIL_ANAG = @$_POST['ID_MAIL_ANAG'];
	include "view/emailanag/formSh.php";		
    } 


public function modificaSh()
    {		
        $this->error_message="";
		$NAME = @$_POST['NAME'];
		$USERNAME = @$_POST['USERNAME'];
		$MAIL = @$_POST['MAIL'];
		$ID_MAIL_ANAG = @$_POST['ID_MAIL_ANAG'];
        try
			{
		$this->_model->updateShell($NAME,$USERNAME,$MAIL,$ID_MAIL_ANAG);
        }
			catch(Exception $e)
			{
            $this->error_message="Impossibile Modificare questo utente! Utente già presente!"; 	
			}
		$this->contentList();
	}



}



?>