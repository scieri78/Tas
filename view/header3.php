<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link rel="stylesheet" href="./CSS/jquery-ui.css?p=<?php echo rand(1000,9999);?>">

  <link href="./CSS/fontawesome/css/fontawesome.css" rel="stylesheet" />
  <link href="./CSS/fontawesome/css/brands.css" rel="stylesheet" />
  <link href="./CSS/fontawesome/css/solid.css" rel="stylesheet" />


  <script src="./JS/bootstrap3.min.js"></script>
  <script src="./JS/jquery.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jquery-ui.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jqueryui.dialog.fullmode.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/jqueryui.dialog.fullmode.js" ?p=<?php echo rand(1000, 9999); ?>></script>
  <script src="./JS/jquery.dataTables.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/select2.full.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/dataTables.bootstrap.min.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script type="text/javascript" src="./JS/menu.js?p=<?php echo rand(1000, 9999); ?>"></script>
  <script src="./JS/dialog.header.js?p=<?php echo rand(1000, 9999); ?>"></script>

  <link rel="stylesheet" href="./CSS/bootstrap3.min.css">
  <link rel="stylesheet" href="./CSS/bootstrap-select.css">
  <script src="./JS/bootstrap-select.js"></script>
  <link rel="stylesheet" href="./CSS/font-awesome.min.css">
  <link rel="stylesheet" href="./CSS/mainmenu.css?p=<?php echo rand(1000, 9999); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	
	
	
<?php 
		if(isset($_view['include_css']))
			{
			echo $_view['include_css'];
			}
	if($_SESSION['aSITO']=="user")
	{
		$asito="work";
	}else {
		$asito="user";
	}		
?> 

 <link rel="stylesheet" href="./CSS/<?php echo $_SESSION['CSS_NAME'];?>?p=<?php echo rand(1000,9999);?>"> 
 <link rel="stylesheet" href="./CSS/baseSito.css?p=<?php echo rand(1000,9999);?>">





	
	
	<title>TASMVC: HOME </title>
  </head>
  <body>
  <div id="header">
    <div id="Logo">
<a href="./index.php?sito=<?php echo $asito;?>">
  <img src="./images/LogoTAS.png" id="LogoImage">
</a>
<div id="AmbLav"><?php echo strtoupper($_SESSION['SERVER_NAME']);?> <?php echo strtoupper($_SESSION['aSITO']);?></div>
</div>
<div id="allianz">
<img src="./images/Allianz.jpg">
</div>

  </div>
    
	  <div id="login" class="loginSection"> <?php 
	
	$idlogout=(isset($_SESSION['HTTP_USERID']))?"prxlogin-out":"login-out";
	  
	  
if (isset($_SESSION['UserValido'])) {
   echo '<form method="post" id="loginForm" class="loginForm">
												<input name="controller" type="hidden" value="login">
													<input name="action" type="hidden" value="logout">
														<div id="'.$idlogout.'" class="loginOut">
															<span id="welcomeText" class="loginOutLabel">Welcome, ' . $_SESSION['codNomi'] . '</span>
														</div>';
	if (!isset($_SESSION['HTTP_USERID'])) {
	echo '<div id="login-esci" class="loginAccedi">
		<button class="loginButton" type="submit" tabindex="0" name="Submit" id="AccediEsci" value="Esci">
		<label for="AccediEsci">Exit</label>
		</button>
		</div>';
	}
	echo '</form>';
}else {
	echo '
    
												
												<form id="loginForm"  method="post" class="loginForm">
												<div class="firstRowLogin">
													<div id="login-username" class="loginUsername">
														<label for="modlgn-username" name="userLabel">Username</label>
														<input id="modlgn-username" name="username" class="input-small" tabindex="0" size="18" placeholder="Username" type="text">
													</div>
													<div id="login-password" class="loginPassword">
														<label for="modlgn-passwd" >Password</label>
														<input id="modlgn-passwd" name="password" class="input-small" tabindex="0" size="18" placeholder="Password" type="password">
													</div>
													<div id="login-accedi" class="loginAccedi">
														<button class="loginButton" type="submit" tabindex="0" name="Submit" id="AccediEsci">
															<label for="AccediEsci">Login</label>
														</button>
													</div>
													<div id="login-mess" class="loginMess">
														<label >'.$_SESSION['loginMess'].'</label>
													</div>
												</div>
												<div class="secondRowLogin" id="login-ricorda" class="loginRicorda">
													<label for="modlgn-remember">Remember</label>
													<input id="modlgn-remember" name="remember" class="inputbox" value="yes" type="checkbox">
												</div>
												<input name="controller" type="hidden" value="login">
													<input name="action" type="hidden" value="index">
												</form>
';


}
	
	?> </div>


    <div id="page">
    <nav class="navbar">
<input type="checkbox" id="menu-toggle">
<label for="menu-toggle" class="hamburger">☰</label>
      <div id="menu">
        <div id="menustat">
          <ul class="menu">
            <li> <?php
      // print_r($_SESSION);
        $html_menu='';
        if (isset($_SESSION['UserValido'])) {
            $menus = $_SESSION['MENU'];
            foreach ($menus as $menu) {
                $action = $menu['ACTION'] ? $menu['ACTION'] : 'index';
                if ($menu['SUB'] == 0) {
                  //  echo "no sub".$menu['SUB']." ".$menu['DESC'];
                    if (! $menu['CONTROLLER']) {
                        $html_menu.= '
                            
																								
																							<li class="nav-item">
																								<a class="nav-link" href="#">' . $menu['DESC'] . '</a>
																							</li>';
                    } else {
                        $url = "index.php?controller=".$menu['CONTROLLER']."&action=".$action;
                        $html_menu.= '
                        
																								
																							<li class="nav-item">
																								<a class="nav-link" href="'.$url.'">' . $menu['DESC'] . '</a>
																							</li>';
                    }
                } else {
                  
                    if (!$menu['CONTROLLER']) {
                      //  echo "sub".$menu['SUB']." ".$menu['DESC'];
                        $html_menu.= '
                           
																								
																							<li class="has-sub">
																								<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $menu['DESC'] . '</a>
																								<ul class="UlColleg">
                            ';
                      //  echo "sub";
                    } else {
                        $url = "index.php?controller=".$menu['CONTROLLER']."&action=".$action;
                        $html_menu.= '
                        
																										
																									<li class="nav-item dropdown">
																										<a class="nav-link dropdown-toggle" href="'.$url.'" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $menu['DESC'] . '</a>
																										<ul class="dropdown-menu">
                        ';
                    }
                    //echo "submenu";
                   // print_r($menu['SMENU']);
                    foreach ($menu['SMENU'] as $smenu) {
                        $saction = $smenu['ACTION'] ? $smenu['ACTION'] : 'index';
                        if (!$smenu['CONTROLLER']) {
                            
                            $html_menu.= '
																												
																											<li>
																												<a class="dropdown-item" href="#">' . $smenu['DESC'] . '</a>
																											</li>';
                        } 
                        else {
                            
                            $surl = "index.php?controller=".$smenu['CONTROLLER']."&action=".$saction;
                            $html_menu.= '
																												
																											<li>
																												<a class="dropdown-item" href="'.$surl.'">' . $smenu['DESC'] . '</a>
																											</li>';
                        }
                    }
                    $html_menu.= "
																											
																										</ul>
																									</li>";
                }
            }
		}
          echo  $html_menu.= "
																									
																								</ul>";
         
       unset($_SESSION['loginMess']);
        ?>
        </div>
        </nav>
      
      </div>
      <div id="alert2" class="alert2">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <strong id="message"></strong>
</div>
	  <div name="Waiting" id="Waiting" ></div>
      
      <div id="contenitore">
	  
	 <SCRIPT>
	  	$("form#loginForm").submit(function (event) {
	  $('#Waiting').show();
	  var formData = {};
   	
	let obj_form = $(this).serializeArray();
	obj_form.forEach(function (input) { 
       
	   formData[input.name] =input.value; 
            });

     $.ajax({
          type: "POST",
		   data: formData,
		   encode: true,	  
           
          // specifico la URL della risorsa da contattare
          url: "index.php?controller=login&action=index",  
          // imposto l'azione in caso di successo
          success: function(risposta){
			
		  $(location).prop('href', 'index.php')
          //visualizzo il contenuto del file nel div htmlm
		//  $("#shellContent").html("");
          // $("#contenitore").html(risposta);			
			
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato){
            alert("Qualcosa è andato storto");
          }
        }).done(function (data) {
		
     
    });

    event.preventDefault();
  });
	  </SCRIPT>
