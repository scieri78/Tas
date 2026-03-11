<html hreflang="it">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <![if !IE]>
  <script src="./JS/jquery-2.2.0.min.js"></script>
  <![endif]>
	<link rel="stylesheet" href="./CSS/jquery.dataTables.min.css?p=<?php echo rand(1000,9999);?>">
    
    <link rel="stylesheet" href="./CSS/bootstrap-theme.min.css?p=<?php echo rand(1000,9999);?>">

  <link rel="stylesheet" href="./CSS/index.css?p=<?php echo rand(1000,9999);?>">
	<link rel="stylesheet" href="./CSS/aside.css?p=<?php echo rand(1000,9999);?>">
    <link rel="stylesheet" href="./CSS/index.css?p=<?php echo rand(1000,9999);?>">
    <link rel="stylesheet" href="./CSS/mainmenu.css?p=<?php echo rand(1000,9999);?>">
    <link rel="stylesheet" href="./CSS/select2.min.css?p=<?php echo rand(1000,9999);?>">
   <link rel="stylesheet" href="./CSS/jquery-ui.min.css?p=<?php echo rand(1000,9999);?>">  

  <link href="./CSS/fontawesome/css/fontawesome.css" rel="stylesheet" />
  <link href="./CSS/fontawesome/css/brands.css" rel="stylesheet" />
  <link href="./CSS/fontawesome/css/solid.css" rel="stylesheet" />  

  <script src="./JS/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="./JS/bootstrap3.min.js"></script>
  <script src="./JS/jquery.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jquery-ui.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jqueryui.dialog.fullmode.js?p=<?php echo rand(1000, 9999); ?>"></script>
  
  
  <script src="./JS/jquery.dataTables.min.js?p=<?php echo rand(1000, 9999); ?>"></script>

 
 
 
  <script src="./JS/select2.full.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/dataTables.bootstrap.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script type="text/javascript" src="./JS/menu.js?p=<?php echo rand(1000, 9999); ?>"></script>
  
  <script src="./JS/dialog.header.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jquery.cookie.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <link rel="stylesheet" href="./CSS/bootstrap3.min.css">
  <link rel="stylesheet" href="./CSS/bootstrap-select.css">
  <script src="./JS/bootstrap-select.js"></script>
  <link rel="stylesheet" href="./CSS/font-awesome.min.css">
  <link rel="stylesheet" href="./CSS/mainmenu.css?p=<?php echo rand(1000, 9999); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <?php
  if (isset($_view['include_css'])) {
    echo $_view['include_css'];
  }
  $aSITO =$_COOKIE[$_COOKIE['tab_id']];
/*
  if ($_SESSION['aSITO'] == "user") {
      $asito = "work";
    }
   if ($_SESSION['aSITO'] == "work") {
      $asito = "user"; 
    }*/
    if ($aSITO == "user") {
      $asito = "work";
    }
   if ($aSITO == "work") {
      $asito = "user"; 
    }
  ?>

  <link rel="stylesheet" href="./CSS/<?php echo $_SESSION['CSS_NAME']; ?>?p=<?php echo rand(1000, 9999); ?>">
  <link rel="stylesheet" href="./CSS/baseSito.css?p=<?php echo rand(1000, 9999); ?>">
   
  <title><?php echo $_SESSION['SERVER_TITOLO'];?></title>
</head>

<body>
  <div id="header">
    <div id="Logo">
      <a href="./index.php?sito=<?php echo $asito; ?>">
        <img src="./images/LogoTAS.png" id="LogoImage">
      </a>
      <div id="AmbLav"><?php echo strtoupper($_SESSION['SERVER_NAME']); ?> <?php 
     // echo strtoupper($_SESSION['aSITO']); 
      echo strtoupper($aSITO); 
      
      ?></div>
    </div>
    <div id="allianz">
      <img src="./images/Allianz.jpg">
    </div>



  <div id="login" class="loginSection"> <?php

                                        $idlogout = (isset($_SESSION['HTTP_USERID'])) ? "prxlogin-out" : "login-out";


                                        if (isset($_SESSION['UserValido'])) {
                                          echo '<div id="' . $idlogout . '" class="loginOut">
                                                <span id="welcomeText" class="loginOutLabel">Welcome, ' . $_SESSION['codNomi'] . '</span>
                                              </div>';
                                        }
                                        ?> </div>

</div>
  <div class="topnav" id="myTopnav">
    <?php

    if (isset($_SESSION['UserValido'])) {
      $menus = $_SESSION['MENU'];
      foreach ($menus as $menu) {
        $action = $menu['ACTION'] ? $menu['ACTION'] : 'index';
        if ($menu['SUB'] == 0) {
          //  echo "no sub".$menu['SUB']." ".$menu['DESC'];
          if (! $menu['CONTROLLER']) {
            $html_menu .= '<a class="nav-link" href="#">' . $menu['DESC'] . '</a>';
          } else {
            $url = "index.php?controller=" . $menu['CONTROLLER'] . "&action=" . $action;
            $html_menu .= '<a class="nav-link" href="' . $url . '">' . $menu['DESC'] . '</a>';
          }
        } else {

          if (!$menu['CONTROLLER']) {
            //  echo "sub".$menu['SUB']." ".$menu['DESC'];
            $html_menu .= '<div class="dropdown2">
										<div class="dropbtn">' . $menu['DESC'] . ' 
										<i class="fa fa-caret-down"></i>
										</div>
										<div class="dropdown-content">
                            ';
            //  echo "sub";
          } else {
            $url = "index.php?controller=" . $menu['CONTROLLER'] . "&action=" . $action;
            $html_menu .= '<div class="dropdown2">
										<div class="dropbtn"><a class="nav-link dropdown-toggle" href="' . $url . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $menu['DESC'] . '</a>
										<i class="fa fa-caret-down"></i>
										</div>
										<div class="dropdown-content">                       
																										
																								
                        ';
          }
          //echo "submenu";
          // print_r($menu['SMENU']);
          foreach ($menu['SMENU'] as $smenu) {
            $saction = $smenu['ACTION'] ? $smenu['ACTION'] : 'index';
            if (!$smenu['CONTROLLER']) {

              $html_menu .= '<a class="dropdown-item" href="#">' . $smenu['DESC'] . '</a>';
            } else {

              $surl = "index.php?controller=" . $smenu['CONTROLLER'] . "&action=" . $saction;
              $html_menu .= '<a class="dropdown-item" href="' . $surl . '">' . $smenu['DESC'] . '</a>';
            }
          }
          $html_menu .= "</div>
					</div>";
        }
      }
    }

    echo  $html_menu;
    ?>

    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
  </div>


  <div id="alert2" class="alert2">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <strong id="message"></strong>
  </div>
  <div name="Waiting" id="Waiting"></div>

  <div id="contenitore">