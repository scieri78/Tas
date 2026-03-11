<?php
$find=0;

$PrxUsr=$_SERVER['HTTP_USERID'];

if ( "$User" <> "" and "$PrxUsr" == "" ) {

  $sqlchk="SELECT count(*) CONTA from WEB.${FixAmb}_UTENTI where upper(USERNAME) = upper('$User') and PASSWORD = '$Pwd'  ";
  if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
  $stmt = db2_prepare($conn, $sqlchk);
  $result = db2_execute($stmt);
  while ($rwchk= db2_fetch_assoc($stmt)) { 
      $CONTA=$rwchk['CONTA'];
      
      if ( $CONTA > 0 ){
        $find=1; 
      }     
  }
}  

if ( "$PrxUsr" <> "" ) {
  $sqlchk="SELECT count(*) CONTA from WEB.${FixAmb}_UTENTI where upper(USERNAME) = upper('$PrxUsr') ";
  if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
  $stmt = db2_prepare($conn, $sqlchk);
  $result = db2_execute($stmt);
  while ($rwchk= db2_fetch_assoc($stmt)) { 
      $CONTA=$rwchk['CONTA'];
      
      if ( $CONTA > 0 ){
        $find=1; 
      } 
      $User=$PrxUsr;
  }
}

if ( $find == 0 ) {
  ?>
  <div id="divieto"> 
  <CENTER><IMG src="../images/Divieto.png"><CENTER>
  <p><CENTER><b><?php echo $User; ?> Non sei abilitato a visualizzare questa pagina</B></CENTER></p>
  </div>
  <div id="footer">
  </div>    
  <?php
}

?>