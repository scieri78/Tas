<?php
require 'model/sports.php';
require 'model/sports_dati.php';

class sports extends helper
{
   
    
    function __construct()
    {
       $this->_model =  new sports_model();
       $this->setDebug_attivo(1);
    }
    // mvc handler request
    public function index()
    {
      $this->list();
    }
    // page redirection
   
    // check validation
    public function checkValidation($sporttb)
    {    $noerror=true;
    // Validate category
    if(empty($sporttb->getCategory())){
        $sporttb->setCategoryMsg("Field is empty.");
		$noerror=false;
    } elseif(!filter_var($sporttb->getCategory(), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $sporttb->setCategoryMsg("Invalid entry.");
		$noerror=false;
    }else{$sporttb->setCategoryMsg("");}
    // Validate name
    if(empty($sporttb->getName())){
        $sporttb->name_msg = "Field is empty.";$noerror=false;
    } elseif(!filter_var($sporttb->getName(), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $sporttb->setNameMsg("Invalid entry.");
		$noerror=false;
    }else{$sporttb->setNameMsg("");}
    return $noerror;
    }
    // add new record
    public function insert()
    {
      
        try{
            $sporttb=new sports_dati();
            if (isset($_POST['addbtn']))
            {
                // read form value
                $sporttb->setCategory(trim($_POST['category']));
                $sporttb->setName(trim($_POST['name']));
               
                //call validation
                $chk=$this->checkValidation($sporttb);
                if($chk)
                {
                    //call insert record
                    $pid = $this->_model->insertRecord($sporttb);
                    if($pid>0){
                        $this->list();
                    }else{
                        echo "Somthing is wrong..., try again.";
                    }
                }else
                {
                    $_SESSION['sporttbl0']=serialize($sporttb);//add session obj
                    include "view/sports/insert.php";
                }
            }else
            {
              //  $_SESSION['sporttbl0']=serialize($sporttb);//add session obj
                include "view/sports/insert.php";
            }
        }catch (Exception $e)
        {
            $this->close_db();
            throw $e;
        }
    }
    // update record
    public function update()
    {
        try
        {
            
            if (isset($_POST['updatebtn']))
            {
                $sporttb=unserialize($_SESSION['sporttbl0']);
                $sporttb->id = trim($_POST['id']);
                $sporttb->category = trim($_POST['category']);
                $sporttb->name = trim($_POST['name']);
                // check validation
                $chk=$this->checkValidation($sporttb);
                if($chk)
                {
                    $res = $this -> _model ->updateRecord($sporttb);
                    if($res){
                        $this->list();
                    }else{
                        echo "Somthing is wrong..., try again.";
                    }
                }else
                {
                    $_SESSION['sporttbl0']=serialize($sporttb);
                    include "view/sports/update.php";
                }
            }elseif(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
                $id=$_GET['id'];
               // $this->_model->selectRecord($id);
                $array_result = $this->_model->fetchAssoc($id);
                $row=$array_result[0];
                $sporttb=new sports_dati();
                $sporttb->setId($row["id"]);
                $sporttb->setName($row["name"]);
                $sporttb->setCategory($row["category"]);
                $_SESSION['sporttbl0']=serialize($sporttb);
                include 'view/sports/update.php';
            }else{
                echo "Invalid operation.";
            }
        }
        catch (Exception $e)
        {
            $this->close_db();
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
                    $this->pageRedirect('index.php?controller=sports&action=index');
                }else{
                    echo "Somthing is wrong..., try again.";
                }
            }else{
                echo "Invalid operation.";
            }
        }
        catch (Exception $e)
        {
            $this->close_db();
            throw $e;
        }
    }
    public function list(){
        include "view/header.php";
      /*  echo "<div class='debug_box'><pre>";
        print_r($_SERVER);
        echo "</pre></div>";   
        */
        
        $array_result =  $result=$this->_model->fetchAssoc(0);
      //  $this->debug("res", $array_result);
        $this->log_message("prova_log",$array_result);
        include "view/sports/list.php";
        include "view/footer.php";
    }
}



?>