<?php

$Submit=$_POST['Submit'];
if ( $Submit == 'Esci' ) {
    session_unset();
    session_destroy();
    unset($headers['UserID']);
}
$_SESSION['UserValido']=false;

$PrxUsr=$_SERVER['HTTP_USERID'];

if ( "$PrxUsr" == "" ) { 
  
    $CodGroup=="";
    $User=strtoupper($_POST['username']);
    $Pwd=$_POST['password'];
    if ( $User == "" and  $Pwd == "" ) {
        session_start();
        $Uk=$_SESSION['CodUk'];
		$User=$_SESSION['codname'];
        $Pwd=$_SESSION['codpwd'];
        $Nominativo=$_SESSION['codNomi'];
        $CodGroup=$_SESSION['CodGroup']; 
		$Admin=$_SESSION['Admin']; 
    }

    if ( "$Pwd" != "" ){
          unset($_SESSION['CodGroup']); 
          $UserValido=false; 
		  $sql="SELECT UK,USERNAME,NOMINATIVO from WEB.${FixAmb}_UTENTI where USERNAME = '$User' and PASSWORD='$Pwd' ";
		  if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
          $stmt = db2_prepare($conn, $sql);
		  $result = db2_execute($stmt);
          while ($row = db2_fetch_assoc($stmt)) { 
            $Uk=$row['UK'];
			$_SESSION['CodUk']=$Uk;
            $Username=$row['USERNAME'];
            $Nominativo=$row['NOMINATIVO'];
            $UserValido=true;
			$_SESSION['UserValido']=true;
          }

          if ( ! $UserValido ) {
            $Mess="Utente $User non censito nel sistema";
            login($Mess);
          } else {
            $_SESSION['codname'] = strtoupper($User); 
            $_SESSION['codpwd']  = $Pwd;
            $_SESSION['codNomi'] = $Nominativo;

			
			$Admin=false;
			$sql="SELECT UK from WEB.${FixAmb}_WORKGROUP WHERE UK = '$Uk' AND GK = ( SELECT GK FROM WEB.${FixAmb}_GRUPPI WHERE GRUPPO = 'ADMIN')";
			if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
			$stmt = db2_prepare($conn, $sql);
			$result = db2_execute($stmt);
			while ($row = db2_fetch_assoc($stmt)) {
			  $Admin=true;
			  $_SESSION['Admin']=$Admin;
			}		
            $TASAdmin=false;
			$sql="SELECT UK from WEB.${FixAmb}_WORKGROUP WHERE UK = '$Uk' AND GK = ( SELECT GK FROM WEB.${FixAmb}_GRUPPI WHERE GRUPPO = 'TAS')";
			if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
			$stmt = db2_prepare($conn, $sql);
			$result = db2_execute($stmt);
			while ($row = db2_fetch_assoc($stmt)) {
			  $TASAdmin=true;
			  $_SESSION['TASAdmin']=$TASAdmin;
			}								
			unset($_SESSION['CodGroup']);
            $CodGroup="";
			if ( $Admin ) {
				$sql="SELECT GK from WEB.${FixAmb}_GRUPPI";
			} else {
				$sql="SELECT g.GK
				from 
				WEB.${FixAmb}_WORKGROUP w,
				WEB.${FixAmb}_GRUPPI g 
				where 1=1
				AND g.GK = w.GK
				AND w.UK = '$Uk'
				";
			}
            if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
            $stmt = db2_prepare($conn, $sql);
			$result = db2_execute($stmt);
            while ($row = db2_fetch_assoc($stmt)) {
              $CodGroup=$CodGroup."'".$row["GK"]."',";
            }
			$CodGroup=substr($CodGroup, 0, -1);
            
            $_SESSION['CodGroup'] = $CodGroup;

			if ( "$NewPage" == "1" ){
               logout($User,$Nominativo);    
		    }
          }
    } else {
        if ( "$User" <> "" ) { $Mess="Utente Non Abilitato"; }
        login($Mess);
    }
    
} else {
    
      $UserValido=false; 
      $sql="SELECT UK,USERNAME,NOMINATIVO  from WEB.${FixAmb}_UTENTI where USERNAME = '$PrxUsr' ";
      if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
	  $stmt = db2_prepare($conn, $sql);
	  $result = db2_execute($stmt);
      while ($row = db2_fetch_assoc($stmt)) { 
        $Uk=$row['UK'];
		$_SESSION['CodUk']=$Uk;
        $Username=$row['USERNAME'];
        $Nominativo=$row['NOMINATIVO'];
        $UserValido=true;
		$_SESSION['UserValido']=true;
      }
      
      if ( ! $UserValido ) {
        $Mess="Utente $PrxUsr non censito nel sistema";
        prxlogin($Mess);
      } else {
        $_SESSION['codname'] = strtoupper($PrxUsr);
        $User=strtoupper($PrxUsr);
        
        $_SESSION['codNomi'] = $Nominativo;     

		$Admin=false;
	    $sql="SELECT UK from WEB.${FixAmb}_WORKGROUP WHERE UK = '$Uk' AND GK = ( SELECT GK FROM WEB.${FixAmb}_GRUPPI WHERE GRUPPO = 'ADMIN')";
		if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
		$stmt = db2_prepare($conn, $sql);
	    $result = db2_execute($stmt);
		while ($row = db2_fetch_assoc($stmt)) {
		  $Admin=true;
		  $_SESSION['Admin']=$Admin;
		}
		
		$TASAdmin=false;
		$sql="SELECT UK from WEB.${FixAmb}_WORKGROUP WHERE UK = '$Uk' AND GK = ( SELECT GK FROM WEB.${FixAmb}_GRUPPI WHERE GRUPPO = 'TAS')";
		if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
		$stmt = db2_prepare($conn, $sql);
		$result = db2_execute($stmt);
		while ($row = db2_fetch_assoc($stmt)) {
		  $TASAdmin=true;
		  $_SESSION['TASAdmin']=$TASAdmin;
		}						
		
        unset($_SESSION['CodGroup']); 
        $CodGroup="";
		if ( $Admin ) {
			$sql="SELECT GK from WEB.${FixAmb}_GRUPPI";
		} else {
			$sql="SELECT g.GK
			from 
			WEB.${FixAmb}_WORKGROUP w,
			WEB.${FixAmb}_GRUPPI g 
			where 1=1
			AND g.GK = w.GK
			AND w.UK = '$Uk'
			";
		}
		if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
		$stmt = db2_prepare($conn, $sql);
		$result = db2_execute($stmt);
		while ($row = db2_fetch_assoc($stmt)) {
		  $CodGroup=$CodGroup."'".$row["GK"]."',";
		}
		$CodGroup=substr($CodGroup, 0, -1);
		$_SESSION['CodGroup'] = $CodGroup;
	
	    if ( "$NewPage" == "1" ){
          prxlogout($User,$Nominativo);     
		}
		
      }

}

function login($Mess) {
?>
<div id="login">
    <form method="post" >

          <div id="login-username" >
            <label for="modlgn-username" >Username</label>
            <input id="modlgn-username" name="username" class="input-small" tabindex="0" size="18" placeholder="Nome utente" type="text">
          </div>
        
          <div id="login-password" >
            <label for="modlgn-passwd" >Password</label>
            <input id="modlgn-passwd" name="password" class="input-small" tabindex="0" size="18" placeholder="Password" type="password">
          </div>

          <div id="login-accedi">
            <button type="submit" tabindex="0" name="Submit" id="AccediEsci" >Login</button>
          </div>
          
          <div id="login-mess">
            <label ><?php echo $Mess; ?></label>
          </div>

          <div id="login-ricorda">
            <label for="modlgn-remember" class="control-label">Remember</label><input id="modlgn-remember" name="remember" class="inputbox" value="yes" type="checkbox">       
          </div>    
          
    </form>
</div>
<?php
}


function logout($User, $Nominativo) {
?>
<div id="login" >
    <form method="post" >

          <div id="login-out" >
            <label for="modlgn-username" >Welcome, <?php 
            if ( "$Nominativo" != "" ) { 
              echo "$Nominativo"; 
            } else { 
              echo "$User"; 
            } 
            ?></label>
          </div>

          <div id="login-esci">
            <button type="submit" tabindex="0" name="Submit" id="AccediEsci" value="Esci" >Exit</button>
          </div>

    </form>
</div>
<?php
}

function prxlogin($Mess) {
?>
<div id="login" >

          <div id="login-mess">
            <label ><?php echo $Mess; ?></label>
          </div>

</div>
<?php
}


function prxlogout($User, $Nominativo) {
?>
<div id="login" >

          <div id="prxlogin-out" >
            <label for="modlgn-username" >Welcome, <?php 
            if ( "$Nominativo" != "" ) { 
              echo "$Nominativo"; 
            } else { 
              echo "$User"; 
            } 
            ?></label>
          </div>

</div>
<?php
}
?>