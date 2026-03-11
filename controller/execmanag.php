<?php
/** 
 * @property execmanag_model $_model
 */
class execmanag extends helper
{

	
	/**
	 * __construct
	 *
	 * @return void
	 */
	function __construct()
	{
		$this->include_css = '
	   <link rel="stylesheet" href="./view/execmanag/CSS/index.css?p=' . rand(1000, 9999) . '" />
	   <script src="./view/execmanag/JS/index.js?p=' . rand(1000, 9999) . '"></script>
	   ';
		$DATABASE = $_SESSION['DATABASE'];
		$this->_model = new execmanag_model($DATABASE);
		$this->setDebug_attivo(1);
	}
	
	/**
	 * index
	 *
	 * @return void
	 */
	public function index()
	{
		$_view['include_css'] = $this->include_css;
		include "view/header.php";
		//$this->debug("DATABASE",$_SESSION);
		$DATABASE = $_SESSION['DATABASE'];
		$this->contentList();
		include "view/footer.php";
	}

	
	/**
	 * ExecManag
	 *
	 * @return void
	 */
	public function ExecManag()
	{
		if (!$_POST) {
			$this->index();
		} else {


			$DATABASE = $_SESSION['DATABASE'];
			$SSHUSR = $_SESSION['SSHUSR'];
			$SERVER = $_SESSION['SERVER'];
			$PRGDIR = $_SESSION['PRGDIR'];
			$_view['include_css'] = $this->include_css;
			include "view/header.php";
			include "view/execmanag/ExecManag.php";
			include "view/footer.php";
		}
	}

	
	/**
	 * contentList
	 *
	 * @return void
	 */
	public function contentList()
	{

		include "view/execmanag/index.php";
	}	
	/**
	 * mostrastorico
	 *
	 * @return void
	 */
	public function mostrastorico()
	{

		include "view/execmanag/ExecManag_MostraStorico.php";
	}
	
	/**
	 * codaexec
	 *
	 * @return void
	 */
	public function codaexec()
	{

		include "view/execmanag/CodaExec.php";
	}
	
	/**
	 * openschedfile
	 *
	 * @return void
	 */
	public function openschedfile()
	{

		$IDOBJ = $_GET["IDOBJ"];
		$TYPE = $_GET["TYPE"];
		$datiSchedFile = $this->_model->SchedFile($IDOBJ, $TYPE);
		$titolo = $datiSchedFile['titolo'];
		$filename = $datiSchedFile['filename'];
		$TestoLog = $datiSchedFile['TestoLog'];
		include "view/execmanag/OpenSchedFile.php";
	}
}
