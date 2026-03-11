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

    $DisableShWfs=$_POST['DisableShWfs'];
    if ( "$DisableShWfs" != "" ){
		$SelIdSh=$DisableShWfs;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET WFS='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$EnableShWfs=$_POST['EnableShWfs'];
    if ( "$EnableShWfs" != "" ){
		$SelIdSh=$EnableShWfs;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET WFS='Y' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
        $SelIdSh="";		
	}	


    $UnBlockWfs=$_POST['UnBlockWfs'];
    if ( "$UnBlockWfs" != "" ){
		$SelIdSh=$UnBlockWfs;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET BLOCKWFS='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$BlockWfs=$_POST['BlockWfs'];
    if ( "$BlockWfs" != "" ){
		$SelIdSh=$BlockWfs;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET BLOCKWFS='Y' WHERE ID_SH = $SelIdSh";
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
	<tr><th>Enable Wfs on</th>
	<td>
	<select id="Sel_EnableWfs" >
	    <option value="" >..</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH	FROM WORK_CORE.CORE_SH_ANAG  WHERE WFS ='N' ORDER BY SHELL";

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

    $sql = "SELECT ID_SH,SHELL,SHELL_PATH, WFS	
	, (SELECT COUNT(*) FROM WFS.ELABORAZIONI E WHERE E.ID_SH = A.ID_SH ) WFSUSED
	, BLOCKWFS
	FROM WORK_CORE.CORE_SH_ANAG A WHERE WFS ='Y' ORDER BY SHELL";
    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    
    if ( ! $result ){
        echo "ERROR DB2";
    }
    
    db2_close($db2_conn_string);
  
    $all_shell=array();
    while ($row = db2_fetch_assoc($stmt)) {
		$ID_SH=$row['ID_SH'];
		$SHELL=$row['SHELL'];
		$SHELL_PATH=$row['SHELL_PATH'];
		$WFS=$row['WFS'];
		$WFSUSED=$row['WFSUSED'];
		$BLOCKWFS=$row['BLOCKWFS'];
		
			  
		if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$WFS","$WFSUSED","$BLOCKWFS"),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$WFS","$WFSUSED","$BLOCKWFS"));
		}
	  		
	}
	
	?>
	<form id="FormMail" method="POST">
	<table width="100%"  class="ExcelTable">
	<tr>
	 <th>Shell</th>
	 <th>Path</th>
	 <th>Wfs</th>
	 <th>BlockWfs</th>
	</tr>
	<?php
	foreach( $all_shell as $DettShell){
		$ID_SH=$DettShell[0];
		$SHELL=$DettShell[1];
		$SHELL_PATH=$DettShell[2];
		$WFS=$DettShell[3];
		$WFSUSED=$DettShell[4];
		$BLOCKWFS=$DettShell[5];
	    ?><tr>
		    <td  class="ShLine Puls" style="border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')">
				<b><?php echo $SHELL; ?></b>
			</td>
			<td class="ShLine" style="border-top: 1px solid black;" ><?php echo $SHELL_PATH; ?></td>
			<td class="ShLine"  style="border-top: 1px solid black;" >
			<?php 
			if ( "$WFSUSED" == "0" ){
				?><img src="../images/Cestino.png" width="25px" class="Puls" onclick="DisableShWfs('<?php echo $ID_SH; ?>')"><?php
			} else {
				?>Used<?php
			}
			?>
			</td>
			<td class="ShLine" style="border-top: 1px solid black;" onclick="BlockWfs('<?php echo $ID_SH; ?>')" >
			<?php 
			  if ( "$BLOCKWFS" == "Y" ){
				 ?><img src="../images/Cestino.png" width="25px" class="Puls" onclick="UnBlockWfs('<?php echo $ID_SH; ?>')"><?php  
			  }else{
				 ?><img src="../images/Aggiungi.png" width="25px" class="Puls" onclick="BlockWfs('<?php echo $ID_SH; ?>')"><?php 
			  }
			?>
			</td>
		   </tr>
		   <?php	
	}
	?></table>
	</form>
	<SCRIPT>

	   function UnBlockWfs(vIdSh){
           var re = confirm('Are you sure you want disable the readonly from this Shell from the Wfs?');
           if ( re == true) { 		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "UnBlockWfs")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }

	   function BlockWfs(vIdSh){
           var re = confirm('Are you sure you want enable the readonly from this Shell from the Wfs?');
           if ( re == true) { 		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "BlockWfs")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }

       function DisableShWfs(vIdSh){
           var re = confirm('Are you sure you want Remove this Shell from the Wfs?');
           if ( re == true) { 		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShWfs")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableShWfs(vIdSh){
		   var re = confirm('Are you sure you want Enable the Wfs to use this Shell?');
           if ( re == true) { 
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShWfs")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }	  	   
   
	   $('#Sel_EnableWfs').change(function(){ 
	      var Val=$('#Sel_EnableWfs').val();
		  if ( Val != '' ){
		    EnableShWfs(Val);
		  }
	   });	   
     </SCRIPT>
	<?php
}
?>
