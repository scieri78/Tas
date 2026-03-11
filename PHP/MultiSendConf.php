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

    $DisableShMultiSend=$_POST['DisableShMultiSend'];
    if ( "$DisableShMultiSend" != "" ){
		$SelIdSh=$DisableShMultiSend;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET MULTI_SEND='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$EnableShMultiSend=$_POST['EnableShMultiSend'];
    if ( "$EnableShMultiSend" != "" ){
		$SelIdSh=$EnableShMultiSend;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET MULTI_SEND='Y' WHERE ID_SH = $SelIdSh";
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
	<tr><th>Enable MultiSend on</th>
	<td>
	<select id="Sel_EnableMultiSend" >
	    <option value="" >..</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
		FROM WORK_CORE.V_CORE_MAIL_SETTING
		WHERE MULTI_SEND ='N' ORDER BY SHELL";

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
    WHERE MULTI_SEND ='Y' ORDER BY SHELL";

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
		
	}
	
	?>
	<form id="FormMail" method="POST">
	<table width="100%"  class="ExcelTable">
	<tr>
	 <th>Shell</th>
	 <th>Path</th>
	 <th>Multi Send</th>
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
			<img src="../images/Cestino.png" width="25px" class="Puls" onclick="DisableShMultiSend('<?php echo $ID_SH; ?>')">
			</td>			
		   </tr>
		   <?php	
	}
	?></table>
	</form>
	<SCRIPT>

       function DisableShMultiSend(vIdSh){
           var re = confirm('Are you sure you want Disable Multi Send File Input setting?');
           if ( re == true) { 		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShMultiSend")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableShMultiSend(vIdSh){
		   var re = confirm('Are you sure you want Enable Multi Send File Input setting?');
           if ( re == true) { 
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShMultiSend")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }	  	   
   
	   $('#Sel_EnableMultiSend').change(function(){ 
	      var Val=$('#Sel_EnableMultiSend').val();
		  if ( Val != '' ){
		    EnableShMultiSend(Val);
		  }
	   });	   
     </SCRIPT>
	<?php
}
?>
