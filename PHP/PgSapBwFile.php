<?php
session_cache_limiter(‘private_no_expire’);
session_start() ;
?>
<html>
<!--[if IE]>
  <link rel="stylesheet" type="text/CSS" href="../CSS/IE7.css">
  <script src="../JS/jquery-1.6.min.js" type="text/javascript">-</script>
<![endif]-->
<![if !IE]>
    <script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
<![endif]>
<link rel="stylesheet" href="../CSS/bootstrap.min.css">
<link rel="stylesheet" href="../CSS/bootstrap-theme.min.css">
<link rel="stylesheet" href="../CSS/index.css">
<link rel="stylesheet" href="../CSS/mainmenu.css">
<link rel="stylesheet" href="../CSS/excel.css">
<?php 
include '../GESTIONE/crypto.php';
include '../GESTIONE/connection.php';
include '../GESTIONE/dataelab.php';
 include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
?>
<title><?php echo $SiteName; ?>: SapBw File </title>
<body>
  <div id="header" >
    <?php include '../GESTIONE/header.php';?>
  </div>
  <div id="footer">
    <?php include '../GESTIONE/footer.php';?>
  </div>    
  <div id="page">

    <div id="menu" >
      <?php include '../GESTIONE/menu.php';?>
    </div>
    
    <div id="message" >
      <?php include '../GESTIONE/messaggio.php';?>
    </div>
    
    <div id="contenitore" >
      <?php include '../PHP/SapBwFile.php';?>
    </div>

  </div>

  <?php include '../GESTIONE/End.php';?>
</body>
</html>
<script type="text/javascript" charset="utf8" src="../JS/menu.js"></script>
<script type="text/javascript" charset="utf8" src="../JS/ShowTab.js"></script>    
<script>

    $(document).ready(function(){
       $("#Waiting").hide('slow');
    });
    
</script>