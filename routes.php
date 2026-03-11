<?php
function call($controller, $action)
{
  require_once("controller/$controller.php");
  require_once("model/$controller.php");
  //we call the action function on the controller
  $controller = new $controller;
  $controller->{$action}();
}

$controllers = array();
$d = dir("controller");
while (false !== ($entry = $d->read())) {
  if (strpos($entry, '.php') !== false) {
    $class = str_replace(".php", "", $entry);
    require_once('controller/' . $class . '.php');
    $controllers[$class] = get_class_methods($class);
  }
}

require_once("controller/login.php");
require_once("model/login.php");
require_once("model/openLink.php");

//if (!isset($_SESSION['old_controller']) || ($_SESSION['old_controller'] != $_SESSION['controller']  )) {
  $_SESSION['old_controller'] = $_SESSION['controller'];
  $_SESSION['old_action'] = $_SESSION['action'];
  $clogin = new login();
  $validaRoot = $clogin->validaRoot($controller);
  $_SESSION['validaRoot'] = $validaRoot;

//} else {
 //$validaRoot = $_SESSION['validaRoot'];
//}
$ret = 0;
//we check, if the invoked action is part of our mvc code
//without this check, a malicous product, could execute arbitrary code


if (($_SESSION['CodGroup'] && $_GET['IDSELEM'] && $controller == 'statoshell' && $action == 'index') || (array_key_exists('workflow2', $controllers) && $controller == 'statoshell')) {
  $ret = 1;
}

if ($ret == 1 && array_key_exists('statoshell', $controllers) && $controller == 'statoshell' && $action == 'index' && !$_GET['IDSELEM']) {
  $ret = 0;
}
/*
echo "<pre>";
//print_r($controllers);
echo "<br>controller:".$controller;
echo "<br>validaRoot:".$validaRoot;
echo "<br>HTTP_USERID:".$_SERVER['HTTP_USERID'];
echo "</pre>";
*/

if ($ret != 1) {
  if (array_key_exists($controller, $controllers) && $validaRoot) {
  
    if (in_array($action, $controllers[$controller])) {       
      $ret = 1;
    } else {      
      $controller = 'home';
      $action = 'error';
    }
  } elseif ($_REQUEST['controller']) {    
    $controller = 'home';
    $action = 'error';
  } else {   
    $controller = 'home';
    $action = 'index';
  }
}


call($controller, $action);
