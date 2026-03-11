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
<link rel="stylesheet" href="./CSS/excel.css">
<?php
//include './GESTIONE/crypto.php';
include './GESTIONE/connection.php';
//include './GESTIONE/SettaVar.php';
//include './GESTIONE/login.php';
//include './GESTIONE/sicurezza.php';
if (1 || '$find' == 1 )  {

     $TopScrollO=$_POST['TopScrollO'];
  
    $DisableShMail=$_POST['DisableShMail'];
    if ( "$DisableShMail" != "" ){
		$SelIdSh=$DisableShMail;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET MAIL='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
		$SelIdSh="";
	}	
    
	
	$EnableShMail=$_POST['EnableShMail'];
    if ( "$EnableShMail" != "" ){
		$SelIdSh=$EnableShMail;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET MAIL='Y' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
        $SelIdSh="";		
	}		

	$AddMail=$_POST['AddMail'];
    if ( "$AddMail" != "" ){
		$SetVar=explode(';',$AddMail);
		$IdSh=$SetVar[0];
		$Type=$SetVar[1];
		$IdMail=$SetVar[2];
		$SelIdSh=$IdSh;
		$sql = "INSERT INTO WORK_CORE.CORE_MAIL_ASSIGN(ID_SH,TYPE,ID_MAIL_ANAG,VALID) VALUES ($SelIdSh,'$Type',$IdMail,'Y')";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);			
	}	
	
	$RemoveMail=$_POST['RemoveMail'];
    if ( "$RemoveMail" != "" ){
		$SetVar=explode(';',$RemoveMail);
		$IdSh=$SetVar[0];
		$Type=$SetVar[1];
		$IdMail=$SetVar[2];
		$SelIdSh=$IdSh;
		$sql = "DELETE FROM WORK_CORE.CORE_MAIL_ASSIGN WHERE ID_SH = $SelIdSh AND TYPE = '$Type' AND ID_MAIL_ANAG = $IdMail";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);			
	}		
    
	$DisableMail=$_POST['DisableMail'];
    if ( "$DisableMail" != "" ){
		$SetVar=explode(';',$DisableMail);
		$IdSh=$SetVar[0];
		$Type=$SetVar[1];
		$IdMail=$SetVar[2];
		$SelIdSh=$IdSh;
		$sql = "UPDATE WORK_CORE.CORE_MAIL_ASSIGN SET VALID = 'N' WHERE ID_SH = $SelIdSh AND TYPE = '$Type' AND ID_MAIL_ANAG = $IdMail";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);		
	}			
	
	$EnableMail=$_POST['EnableMail'];
    if ( "$EnableMail" != "" ){
		$SetVar=explode(';',$EnableMail);
		$IdSh=$SetVar[0];
		$Type=$SetVar[1];
		$IdMail=$SetVar[2];
		$SelIdSh=$IdSh;
		$sql = "UPDATE WORK_CORE.CORE_MAIL_ASSIGN SET VALID = 'Y' WHERE ID_SH = $SelIdSh AND TYPE = '$Type' AND ID_MAIL_ANAG = $IdMail";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);				
	}		
	

    $ListMail=array();
    $sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY NAME";
    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo "ERROR DB2";
    }
    db2_close($db2_conn_string);
    while ($row = db2_fetch_assoc($stmt)) {
		$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
		$MAIL=$row['MAIL'];
		$NAME=$row['NAME'];
		$USERNAME=$row['USERNAME'];
		array_push($ListMail, array("$ID_MAIL_ANAG","$MAIL","$NAME","$USERNAME") );
	}
	

    ?>
	
	<form id="FormMail" method="POST">	
    <input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
	<table width="100%" class="ExcelTable" >	
	<tr><td style="width: 60px;" ><input type="submit" value="REFRESH" class="Bottone" style="background:yellow;" ></td><th>Enable Mail on </th>
	<td colspan=2 >
	<select id="Sel_EnableShMail" >
	    <option value="" >..</option>
		<?php
		$sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG, SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND 
		FROM WORK_CORE.V_CORE_MAIL_SETTING WHERE SH_MAIL_ENABLE = 'N' ORDER BY SHELL";

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
	
    $sql = "SELECT ID_SH,SHELL,SHELL_PATH,SH_DEBUG,DB_DEBUG, SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND
    ,(SELECT count(*) FROM WORK_CORE.CORE_MAIL_ASSIGN SET WHERE ID_SH = V.ID_SH AND TYPE = 'R_TO' ) CNT_RTO
    ,(SELECT count(*) FROM WORK_CORE.CORE_MAIL_ASSIGN SET WHERE ID_SH = V.ID_SH AND TYPE = 'R_CC' ) CNT_RCC
	,ATTACH
	FROM WORK_CORE.V_CORE_MAIL_SETTING V WHERE SH_MAIL_ENABLE = 'Y' ORDER BY SHELL_PATH,SHELL";

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
		$CNT_RTO=$row['CNT_RTO'];
		$CNT_RCC=$row['CNT_RCC'];
		$SH_ATTC=$row['ATTACH'];
			  
		if (! in_array(array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND",$CNT_RTO,$CNT_RCC,$SH_ATTC),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SHELL_PATH","$SH_DEBUG","$DB_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND",$CNT_RTO,$CNT_RCC,$SH_ATTC));
		}
	  
		if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
			${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		} else {
			array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE"));
		}
		
	}
	
	?>
	<table width="100%" class="ExcelTable" >
	<tr>
	 <th style="width:500px" >Path</th>
	 <th>Shell</th>
	 <th>Num. Dest.</th>
	 <th>Num. CC.</th>
	 <th>With Attachment</th>
	 <th>Mail</th>
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
		$SH_R_TO=$DettShell[7];
		$SH_R_CC=$DettShell[8];
		$SH_ATTC=$DettShell[9];
		
		if ( "$SH_ATTC" == "Y" ){ $SH_ATTC="Yes"; }
	    ?><tr>
			<td class="ShLine" style="border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')"><?php echo $SHELL_PATH; ?></td>
		    <td  class="ShLine Puls" style="border-top: 1px solid black;" >
				<img src="./images/File.png" class="IconFile" onclick="OpenFile(<?php echo $ID_SH; ?>)" style="width: 20px;">
				<b onclick="TrDett('<?php echo $ID_SH; ?>')"><?php echo $SHELL; ?></b>
			</td>
            <td><?php echo $SH_R_TO; ?></td>			
            <td><?php echo $SH_R_CC; ?></td>	
			<td><?php echo $SH_ATTC; ?></td>
			<td class="ShLine"  style="border-top: 1px solid black;" >
			  <img src="./images/Cestino.png" width="25px" class="Puls" onclick="DisableShMail('<?php echo $ID_SH; ?>')">
			</td>		
		   </tr>
		   <tr id="Dett<?php echo $ID_SH; ?>" <?php if ( "$SelIdSh" != "$ID_SH" ){ ?>hidden<?php } ?> >
		   <th></th>
		   <th colspan=2  >
				<table  class="ExcelTable" style="width: 100%;position: relative;left: 0;right: 0;margin: auto;">
					<?php
					foreach( $MailType as $TYPE ){
						switch($TYPE){
							case 'T_TO': $DescType="DESTINATARI TECNICI";   break;
							case 'T_CC': $DescType="COPIA CARBONE TECNICI"; break;
							case 'R_TO': $DescType="DESTINATARI FINALI";    break;
							case 'R_CC': $DescType="COPIA CARBONE FINALI";  break;							
						}
						?>
						<tr>	
                            <td style="text-align:right;" ><B><?php echo $DescType; ?></B></td>						
							<td class="ShLine">
								
								<table style="width:300px;left:0;right:0;margin:auto;">
								<?php
								$ListIdFind=array();
								foreach( ${'Mail_'.$TYPE.'_'.$ID_SH} as $DettMail ){
									$ID_MAIL_ANAG=$DettMail[0];
									array_push($ListIdFind, $ID_MAIL_ANAG); 
									$MAIL=$DettMail[1];
									$MAIL_ENABLE=$DettMail[2];
									
									?><tr>
											<td><img src="./images/Cestino.png" width="25px" class="Puls" onclick="RemoveMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"><?php echo $MAIL; ?></td>
											<?php
											if ( "$MAIL_ENABLE" == "Y" ){
											  ?><td><img src="./images/OK.png" width="25px" class="Puls" onclick="DisableMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"></td><?php
								            } else {
											  ?><td><img src="./images/KO.png" width="25px" class="Puls" onclick="EnableMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"></td><?php
					                        }
											?>
									</tr><?php
								}
								?>
								
							    <tr>
									 <td>
										 <select id="AddSelMail<?php echo $ID_SH.$TYPE; ?>" style="width:300px;" onchange="AddMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>')" >
											<option value="" >..</option>
											<?php
											foreach( $ListMail as $DettMail){
												$IdMail=$DettMail[0];
												$Mail=$DettMail[1];
												$NameMail=$DettMail[2];
												$UsrMail=$DettMail[3];
												if( ! in_array($IdMail ,$ListIdFind) ){
													?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
											    }
											}
											?>
										 </select>
									 </td>
									 <td><img src="./images/Aggiungi.png" width="25px" ></td>
								 </tr>
							     </table>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
		   </th><?php	
	   ?></tr><?php	
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
	   
	   function DisableShMail(vIdSh){
           var re = confirm('Are you sure you want Disable Mail setting?');
           if ( re == true) {			   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShMail")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableShMail(vIdSh){
           var re = confirm('Are you sure you want Enable Mail setting?');
           if ( re == true) {		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShMail")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	
	   function RemoveMail(vIdSh, vType, vIdMail){
           var re = confirm('Are you sure you want to Remove this Mail?');
           if ( re == true) {  		   
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "RemoveMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();		   
		   }
	   }
	   function DisableMail(vIdSh, vType, vIdMail){
		   var re = confirm('Are you sure you want to Disable the Mails?');
           if ( re == true) {  
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function EnableMail(vIdSh, vType, vIdMail){
		   var re = confirm('Are you sure you want to Enable the Mails?');
           if ( re == true) { 
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
		   }
	   }
	   function AddMail(vIdSh, vType){
		    vIdMail=$('#AddSelMail'+vIdSh+vType).val();
		    if ( vIdMail != '' ){
				var input = $("<input>")
				.attr("type", "hidden")
				.attr("name", "AddMail")
				.val(vIdSh+';'+vType+';'+vIdMail);
				$('#FormMail').append($(input));
				$('#FormMail').submit();
			}
	   }	   
	   
	   $('#Sel_EnableShMail').change(function(){ 
	      var Val=$('#Sel_EnableShMail').val();
		  if ( Val != '' ){
		    EnableShMail(Val);
		  }
	   });
	   
	   
       function OpenFile(vIdSh){
           window.open('./PHP/ApriFile.php?IDSH='+vIdSh);
       }

$('#FormMail').submit(function(){
	  $('#TopScrollO').val($(window).scrollTop());  
});     

$(window).scrollTop($('#TopScrollO').val());

     </SCRIPT>
	<?php
}
?>
