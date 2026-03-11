<?php
include_once("./model/controlli_dati.php");
class sports_dati
{
    // table fields
    public $id;
    public $category;
    public $name;
    public $category_msg;
    public $name_msg;
 
	public function setId($id)
		{
		$this->id = $id;
		} 
// get user's first name
	public function getId()
		{
		return $this->id;
		} 
 
     public function setCategory($category)
		{
		$this->category = $category;
		}
 
// get user's first name
	public function getCategory()
		{
		return $this->category;
		}
		
		
	public function setName($name)
		{
		$this->name = $name;
		}
 
// get user's first name
	public function getName()
		{
		return $this->name;
		}
		
	public function setNameMsg($name_msg)
		{
		$this->name_msg = $name_msg;
		}
 
// get user's first name
    public function getNameMsg()
		{
		return $this->name_msg;
		}
		
		public function setCategoryMsg($category_msg)
		{
		    $this->category_msg = $category_msg;
		}
		
		// get user's first name
	public function getCategoryMsg()
		{
		    return $this->category_msg;
		}
    
 
    function __construct()
    {
        $this->category_msg='';
    }
}

?>