<?php
/** 
 * @property emailanag_model $_model
 */
class emailanag2 extends helper
{

    
    function __construct()
    {
       $this->_model =  new emailanag_model();
       $this->setDebug_attivo(1);
    }
    // mvc handler request
    public function index()
    {
        $action = @$_POST['action'];
      //  $this->debug("post",$_POST);
        
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
          //  $sporttb=new sports_dati();
           // $this->debug("addbtn".@$_POST['AddNewMail']);
            if (isset($_POST['AddNewMail']))
            {
                // read form value
                $AddName     =$_POST['AddName'];
                $AddUsername =$_POST['AddUsername'];
                $AddMail     =$_POST['AddMail'];
                //call validation
                $chk=1;
                $this->error_message = "";
                $chk=$this->checkValidation($_POST);
                if($chk)
                {
                    //call insert record
                    $pid = $this->_model->AddMail($_POST);
                   // $this->debug("AddMail".$pid);
                    if($pid==0){
                      $this->error_message .= "Impossibile inserire la Mail.<br>";
					
                        
                    }else{
                        $this->pageRedirect('index.php?controller=emailanag');
                    }
                }
            }
       
       $this->list();
            
        }catch (Exception $e)
        {
           
           // $this->close_db();
         //   $this->log_message($e);
            
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
                if($res){
                    $this->pageRedirect('index.php?controller=emailanag&action=index');
                }else{
                    echo "Somthing is wrong..., try again.";
                }
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
         //   $this->close_db();
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
                if($res){
                    $this->pageRedirect('index.php?controller=emailanag&action=index');
                }else{
                    echo "Somthing is wrong..., try again.";
                }
            }else{
                echo "Invalid operation.";
            }
        }
        catch (Exception $e)
        {
         //   $this->close_db();
            throw $e;
        }
    }
    public function list(){
       include "view/header2.php";
        $this->get_errors_message();
        $result = $this->_model->getListMail();
      //  $this->debug("res", $result);
       // die();
      //  $_SESSION['sporttbl0']=serialize($sporttb);//add session obj
        include "view/emailAnag/list2.php";
        include "view/footer.php";
    }
}



?>