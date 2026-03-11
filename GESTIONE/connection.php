<?php
session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();
 $rootdir= $_SESSION['PSITO'];
 $rootdir = str_replace(["TASUSR","TASWRK"], ["TASMVC","TASMVC"], $rootdir);
include_once $rootdir.'config/config.php'; 

switch ($_SERVER['SERVER_NAME']) {
    case 'onlines.te.azi.allianzit':       
		ini_set('display_errors', 1);
        include_once $rootdir.'config/confTasSviluppo.php';
		$_SESSION['SERVER_NAME']="SVIL";
		$_SESSION['CSS_NAME']="sviluppo.css";
        break;    
	case 'onlines.pp.azi.allianzit':
		ini_set('display_errors', 1);	
        include_once $rootdir.'config/confTasPreprod.php';
		$_SESSION['SERVER_NAME']="PREPR";
		$_SESSION['CSS_NAME']="preprod.css";
        break;    
	case 'onlines.azi.allianzit':
		ini_set('display_errors', 1);
        include_once $rootdir.'config/confTasProd.php';
		$_SESSION['SERVER_NAME']="PROD";
		$_SESSION['CSS_NAME']="prod.css";
        break;
}



$_conf = new config();

$DB2pass =  $_conf->getPass();

$DB2database = $_conf->getDb();
$DB2user = $_conf->getUser();
$DB2hostname = $_conf->getHost(); //'faitssidbteav01.te.azi.allianzit';
$DB2port =  $_conf->getPort();

$db2_conn_string = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$DB2database;" .
"HOSTNAME=$DB2hostname;PORT=$DB2port;PROTOCOL=TCPIP;UID=$DB2user;PWD=$DB2pass;";

$TServer=$_SESSION['TServer'];   
$DATABASE=$_SESSION['DATABASE'];

$conn = db2_connect($db2_conn_string, '', ''); 

if (!$conn) {
    ?>
    <script> 
    alert('CONNESSIONE AL DB2 FALLITA: <?php 
        echo "Error: " . (db2_conn_error());
        echo "Msg:   " . (db2_conn_errormsg());
    ?>');
    </script>
    <?php
	exit;
}

//$rootdir='/opt/rh/httpd24/root/var/www/html/nsw/TASMVC/';

?>