<style>
.ShLine{
	border-left:0px;
	border-right:0px;
	border-bottom: 1px solid black;
	padding:5px;
	padding-bottom:10px;
}
.Puls{
	cursor:pointer;
}
.LabelMails{
	float:left;
	margin:5px;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {

    $DisableShDebug=$_POST['DisableShDebug'];
    if ( "$DisableShDebug" != "" ){
		$SelIdSh=$DisableShDebug;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_SH='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$EnableShDebug=$_POST['EnableShDebug'];
    if ( "$EnableShDebug" != "" ){
		$SelIdSh=$EnableShDebug;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_SH='Y' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
        $SelIdSh="";		
	}	

	$DisableDbDebug=$_POST['DisableDbDebug'];
    if ( "$DisableDbDebug" != "" ){
		$SelIdSh=$DisableDbDebug;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_DB='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$EnableDbDebug=$_POST['EnableDbDebug'];
    if ( "$EnableDbDebug" != "" ){
		$SelIdSh=$EnableDbDebug;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG_DB='Y' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
        $SelIdSh="";		
	}
		

    ?>
	<table width="100%" class="ExcelTable" >
	<tr><th>Enable Debug Shell on</th>
	<td>
	<select id="Sel_EnableDebugSh" >
	    <option value="" >..</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
		FROM WORK_CORE.V_CORE_MAIL_SETTING 
		WHERE SH_DEBUG = 'N' ORDER BY SHELL";


		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		
		if ( ! $result ){
			echo "ERROR DB2";
		}
		
		db2_close($db2_conn_string);
	  
		while ($row = db2_fetch_assoc($stmt)) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL.' ('.$SHELL_PATH.')'; ?></option><?php
		}
		?>
	</select>
	</td>
	</tr>
	<tr><th>Enable Debug PLSQL on</th>
	<td>
	<select id="Sel_EnableDebugDb" >
	    <option value="" >..</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
		FROM WORK_CORE.V_CORE_MAIL_SETTING 
		WHERE DB_DEBUG = 'N' ORDER BY SHELL";


		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		
		if ( ! $result ){
			echo "ERROR DB2";
		}
		
		db2_close($db2_conn_string);
	  
		while ($row = db2_fetch_assoc($stmt)) {
			$ID_SH=$row['ID_SH'];
			$SHELL=$row['SHELL'];
			$SHELL_PATH=$row['SHELL_PATH'];
			?><option value="<?php echo $ID_SH; ?>" ><?php echo $SHELL.' ('.$SHELL_PATH.')'; ?></option><?php
		}
		?>
	</select>
	</td>
	</tr>
	</table>
	<?php
	
    $sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
	FROM WORK_CORE.V_CORE_MAIL_SETTING 
	WHERE DB_DEBUG = 'Y' OR SH_DEBUG = 'Y' ORDER BY SHELL";

    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    
    if ( ! $result ){
        echo "ERROR DB2";
    }
    
    db2_close($db2_conn_string);
  
    $MailType=array('T_TO','T_CC','R_TO','R_CC');
    $all_shell=array();
    while ($row = db2_fetch_assoc($stmt)) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SHELL_PATH=$row['SHELL_PATH'];
		$SH_DEBUG=$row['SH_DEBUG'];
		$DB_DEBUG=$row['DB_DEBUG'];
		$SH_MAIL_ENABLE=$row['SH_MAIL_ENABLE'];
		$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
		$TYPE=$row['TYPE'];
		$MAIL=$row['MAIL'];
		$MAIL_ENABLE=$row['MAIL_ENABLE'];
		$MULTI_SEND=$row['MULTI_SEND'];
			  
		if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND"),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND"));
		}
	  
		if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
			${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		} else {
			array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		}
		
	}
	
	?>
	<form id="FormMail" method="POST">
	<table width="100%"  class="ExcelTable">
	<tr>
	 <th>Shell</th>
	 <th>Path</th>
	 <th>Debug Sh</th>
	 <th>Debug Db</th>
	</tr>
	<?php
	foreach( $all_shell as $DettShell){
		$ID_SH=$DettShell[0];
		$SHELL=$DettShell[1];
		$SHELL_PATH=$DettShell[2];
		$SH_DEBUG=$DettShell[3];
		$DB_DEBUG=$DettShell[4];
		$SH_MAIL_ENABLE=$DettShell[5];
		$SH_MULTI_SEND=$DettShell[6];
	    ?><tr>
		    <td  class="ShLine Puls" style="border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')">
				<b><?php echo $SHELL; ?></b>
			</td>
			<td class="ShLine" style="border-top: 1px solid black;" ><?php echo $SHELL_PATH; ?></td>
			<td class="ShLine"  style="border-top: 1px solid black;" >
			<?php 
			if ( "$SH_DEBUG" == "Y" ){
				?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableShDebug('<?php echo $ID_SH; ?>')"><?php
			} else {
				/* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableShDebug('<?php echo $ID_SH; ?>')"><?php */
				?><div class="Puls" onclick="EnableShDebug('<?php echo $ID_SH; ?>')" >N</siv><?php
			}
			?>
			</td>		
			<td class="ShLine"  style="border-top: 1px solid black;" >
			<?php 
			if ( "$DB_DEBUG" == "Y" ){
				?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableDbDebug('<?php echo $ID_SH; ?>')"><?php
			} else {
				/* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableDbDebug('<?php echo $ID_SH; ?>')"><?php */
				?><div class="Puls" onclick="EnableDbDebug('<?php echo $ID_SH; ?>')" >N</siv><?php
			}
			?>
			</td>				
			</tr>
		   <?php	
	}
	?></table>
	</form>
	<SCRIPT>
	   function TrDett(vIdSh){
		   if( $('#Dett'+vIdSh).is(':visible') ){
				$('#Dett'+vIdSh).hide();
		   }else{
				$('#Dett'+vIdSh).show();
		   }
	   }
	   
	   function DisableShDebug(vIdSh){
           var re = confirm('Are you sure you want Disable Debug setting?');
           if ( re == true) { 			   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableShDebug(vIdSh){
           var re = confirm('Are you sure you want Enable Debug setting?');
           if ( re == true) { 		   		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function DisableDbDebug(vIdSh){
           var re = confirm('Are you sure you want Disable Debug PLSQ setting?');
           if ( re == true) { 			   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableDbDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableDbDebug(vIdSh){
           var re = confirm('Are you sure you want Enable Debug PLSQ setting?');
           if ( re == true) { 		   		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableDbDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }	

	   $('#Sel_EnableDebugSh').change(function(){ 
	      var Val=$('#Sel_EnableDebugSh').val();
		  if ( Val != '' ){
		    EnableShDebug(Val);
		  }
	   });	 
	   
	   $('#Sel_EnableDebugDb').change(function(){ 
	      var Val=$('#Sel_EnableDebugDb').val();
		  if ( Val != '' ){
		    EnableDbDebug(Val);
		  }
	   });	 	   
       
     </SCRIPT>
	<?php
}
?>
