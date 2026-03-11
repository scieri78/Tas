<style>

.Puls{
	cursor:pointer;
	background:lightgray;
}
#TabAddShell{
	width:100%;
	left:0;
	right:0;
	margin:auto;
}

#DivAddShell{
	width:600px;
	height: 270px;
	background:white;
	border:1px solid black;
	border-radius:10px;
	left:0;
	right:0;
	margin:auto;
	padding:20px;
}

.Titolo{
    font-size: 20px;
    margin: 10px;
    text-align: center;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {

    $ShellPath=$_POST['ShellPath'];
	
	$SaveAdd=$_POST['SaveAdd'];
    if ( "$SaveAdd" != "" ){
		
          $Sql='CALL WORK_CORE.K_CORE.Sh_Anag(?,?,?)';
          $stmt = db2_prepare($conn, $Sql);

          $ShellPath=$_POST['ShellPath'];
		  $ShellName=$_POST['ShellName'];
		  $IdOutSh=0;

          db2_bind_param($stmt, 1, "ShellName"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 2, "ShellPath"  , DB2_PARAM_IN);
		  db2_bind_param($stmt, 3, "IdOutSh"    , DB2_PARAM_OUT);

          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2:".db2_stmt_errormsg();
          }	else {
            ?><script>alert('Shell Added');</script><?php
		  }			  
	}
	
	if ( "$ShellPath" == "" ){
       $ShellPath="/area_staging_TAS/DIR_SHELL";
	}		
	
    $sql = 'SELECT ID_SH,SHELL,SHELL_PATH FROM WORK_CORE.CORE_SH_ANAG';

    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    
    if ( ! $result ){
        echo "ERROR DB2";
    }
      
    $all_shell=array();
    while ($row = db2_fetch_assoc($stmt)) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SH_PATH=$row['SHELL_PATH'];
		
		if (! in_array(array($SH_PATH,$SHELL),$all_shell) ){
		    array_push($all_shell,array($SH_PATH,$SHELL));
		}
	  		
	}
		
	?>
	<form id="FormAddShell" method="POST">
	<div id="DivAddShell" >
    <div class="Titolo" >Add Shell</div>
	<table id="TabAddShell">
	    <tr>
		    <th>
				Path
			</th>
		</tr>
		<tr>
			<td>
			  <input type="text" name="ShellPath" id="ShellPath" value="<?php echo $ShellPath; ?>" style="width:100%;" >
			</td>			
	    </tr>
		<tr>
		    <td><BR></td>
		</tr>
	    <tr>
		    <th>
				Shell Name 
			</th>
        </tr>
		<tr>
					
			<td>
			   <input type="text" name="ShellName" id="ShellName" style="width:100%;" >
			</td>			
	     </tr>	
		 <tr>
		 	<td>
			   <BR>
			   <input class="Puls" name="SaveAdd" id="SaveAdd" value="Add Shell" onclick="TestShell()" >
			</td>			
	     </tr>	
	</table>
	</div>
	</form>
	<SCRIPT>
	
	   function TestShell(){
		    var vPath=$('#ShellPath').val();
			var vShell=$('#ShellName').val();
			
	        if ( vPath != '' && vShell != '' ){
			   if ( 1==2
			   <?php
			   foreach ($all_shell as $Dett ){
			      ?> || ( vPath == '<?php echo $Dett[0]; ?>' && vShell == '<?php echo $Dett[1]; ?>' )
			      <?php
			   }
			   ?>			
			   ){
					alert('Shell already in anag');
			   } else {
					$('#FormAddShell').submit();
			   }
	        } else {
				alert('Field empty!');
			}
	   }
	   
	   
     </SCRIPT>
	<?php
}
?>
