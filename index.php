<?php
session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();
if($_REQUEST['controller']=='tickets'){ ?>
    <!DOCTYPE html>
    <?php
    }
if (empty($_POST) && ($_REQUEST['controller'] != 'login' && $_REQUEST['controller'] != 'workflow2') && ( $_REQUEST['action'] != 'downloadfile')) { ?>
    <script src="./JS/jquery.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
    <script src="./JS/jquery.cookie.js?p=<?php echo rand(1000, 9999); ?>"></script>
    <script src="./JS/tabid.js?p=<?php echo rand(1000, 9999); ?>"></script>
    <script>
        defineTabID('<?php echo $_REQUEST["sito"]; ?>');
        verificaTabId('<?php echo $_REQUEST["sito"]; ?>', '<?php echo $_COOKIE[$_COOKIE['tab_id']]; ?>');
    </script>
<?php
    if ($_GET["controller"] == 'workflow2' || $_GET["controller"] == 'tickets')
        $_SESSION['tab_id'] = $_COOKIE['tab_id'] ? $_COOKIE['tab_id'] : 1;
}

//we read the product intent into two variables
//$controller and $action passed over query strings
include_once './config/config.php';
include_once './core/helper.php';
include_once './core/girorias.php';
?>
<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);

?>
<?php
$_SESSION['SERVER_TITOLO'] = "";
switch ($_SERVER['SERVER_NAME']) {
    case 'onlines.te.azi.allianzit':
        ini_set('display_errors', 1);
        include_once './config/confTasSviluppo.php';
        $_SESSION['SERVER_NAME'] = "SVIL";
        $_SESSION['CSS_NAME'] = "sviluppo.css";
        $_SESSION['SERVER_TITOLO'] .= 'TASSV';
        break;
    case 'onlines.pp.azi.allianzit':
        ini_set('display_errors', 1);
        include_once './config/confTasPreprod.php';
        $_SESSION['SERVER_NAME'] = "PREPR";
        $_SESSION['CSS_NAME'] = "preprod.css";
        $_SESSION['SERVER_TITOLO'] .= 'TASPP';

        break;
    case 'onlines.azi.allianzit':
        ini_set('display_errors', 1);
        include_once './config/confTasProd.php';
        $_SESSION['SERVER_NAME'] = "PROD";
        $_SESSION['CSS_NAME'] = "prod.css";
        $_SESSION['SERVER_TITOLO'] .= 'TAS';
        break;
}

$_conf = new config();
$_COOKIE[$_COOKIE['tab_id']];
//$aSITO = $_COOKIE[$_COOKIE['tab_id']];
$aSITO =$_GET['sito']?$_GET['sito']:$_COOKIE[$_COOKIE['tab_id']];
$_SESSION['SERVER_TITOLO'] .= ': ' . strtoupper($aSITO);


switch ($_conf->getDriver()) {
    case 'mysql':
        include_once './core/db_mysql.php';
        break;
    case 'db2':
        include_once './core/db_db2.php';
        break;
}
if (isset($_REQUEST["controller"]) && isset($_REQUEST["action"])) {
    $controller = $_REQUEST["controller"];
    $action = $_REQUEST["action"];
} else {
    if (isset($_REQUEST["controller"])) {
        $controller = $_REQUEST["controller"];
        $action = "index";
    } else {
        $controller = $_SESSION["controller"]?$_SESSION["controller"]:'home';
        $action = $_SESSION["action"]?$_SESSION["action"]:'index';
    }
}

if($controller == 'login' && $action == 'index')
    {
    $controller ="home"; 
    }
if($_REQUEST['DARETI']==1 && $controller =='statoshell')
{
$_SESSION["controller"] = 'reti';
$_SESSION["action"] = 'index';
}
else {
$_SESSION["controller"] = $controller;
$_SESSION["action"] = 'index';
}
$_SESSION['SERVER_TITOLO'] .= ': ' . strtoupper($_conf->getController($_SESSION['controller']));
if ($_REQUEST["controller"] == 'workflow2' || $_REQUEST["controller"] == 'tickets') {
   // if($_SESSION['tab_id']){
    $_COOKIE['tab_id'] = $_SESSION['tab_id'];
   // }
}

require_once("routes.php");
?>