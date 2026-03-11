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
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG='N' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);
	}	
    
	
	$EnableShDebug=$_POST['EnableShDebug'];
    if ( "$EnableShDebug" != "" ){
		$SelIdSh=$EnableShDebug;
		$sql = "UPDATE WORK_CORE.CORE_SH_ANAG SET DEBUG='Y' WHERE ID_SH = $SelIdSh";
		$conn = db2_connect($db2_conn_string, '', '');
		$stmt=db2_prepare($conn, $sql);
		$result=db2_execute($stmt);
		if ( ! $result ){
			echo "ERROR DB2";
		}		
		db2_close($db2_conn_string);		
	}	

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
	}	

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
    $sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY USERNAME";
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
		
	
    $sql = 'SELECT ID_SH,SHELL,SH_DEBUG,SH_MAIL_ENABLE, TYPE, ID_MAIL_ANAG, MAIL, MAIL_ENABLE, MULTI_SEND FROM WORK_CORE.V_CORE_MAIL_SETTING ORDER BY SHELL';

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
		$SH_DEBUG=$row['SH_DEBUG'];
		$SH_MAIL_ENABLE=$row['SH_MAIL_ENABLE'];
		$ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
		$TYPE=$row['TYPE'];
		$MAIL=$row['MAIL'];
		$MAIL_ENABLE=$row['MAIL_ENABLE'];
		$MULTI_SEND=$row['MULTI_SEND'];
			  
		if (! in_array(array("$ID_SH","$SHELL","$SH_DEBUG","$SH_MAIL_ENABLE"),$all_shell) ){
		    array_push($all_shell,array("$ID_SH","$SHELL","$SH_DEBUG","$SH_MAIL_ENABLE","$MULTI_SEND"));
		}
	  
		if ( ! isset(${'Mail_'.$TYPE.'_'.$ID_SH}) ){
			${'Mail_'.$TYPE.'_'.$ID_SH}=array(array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE","$MULTI_SEND"));
		} else {
			array_push(${'Mail_'.$TYPE.'_'.$ID_SH},array("$ID_MAIL_ANAG","$MAIL","$MAIL_ENABLE","$MULTI_SEND"));
		}
		
	}
	
	?>
	<form id="FormMail" method="POST">
	<table width="100%">
	<tr style="background: lightgray;" ><th>Shell</th><th>Mails</th><th>Debug</th><th>Multi Send</th></tr>
	<?php
	foreach( $all_shell as $DettShell){
		$ID_SH=$DettShell[0];
		$SHELL=$DettShell[1];
		$SH_DEBUG=$DettShell[2];
		$SH_MAIL_ENABLE=$DettShell[3];
		$MULTI_SEND=$DettShell[4];
	    ?><tr>
		    <td  class="ShLine Puls" style="background:#e8f7ce;border-top: 1px solid black;" onclick="TrDett('<?php echo $ID_SH; ?>')">
				<b><?php echo $SHELL; ?></b>
			</td>
			<td class="ShLine"  style="background:#e8f7ce;border-top: 1px solid black;" ><?php 
			if ( "$SH_MAIL_ENABLE" == "Y" ){
				?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableShMail('<?php echo $ID_SH; ?>')"><?php
			} else {
				?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableShMail('<?php echo $ID_SH; ?>')"><?php
			}
			?>
			</td>			
			<td class="ShLine"  style="background:#e8f7ce;border-top: 1px solid black;" ><?php 
			if ( "$SH_DEBUG" == "Y" ){
				?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableShDebug('<?php echo $ID_SH; ?>')"><?php
			} else {
				?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableShDebug('<?php echo $ID_SH; ?>')"><?php
			}
			?>
			</td>
			<td class="ShLine"  style="background:#e8f7ce;border-top: 1px solid black;" ><?php 
			if ( "$MULTI_SEND" == "Y" ){
				?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableShMultiSend('<?php echo $ID_SH; ?>')"><?php
			} else {
				?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableShMultiSend('<?php echo $ID_SH; ?>')"><?php
			}
			?>
			</td>			
		   </tr>
		   <tr>
		   <td id="Dett<?php echo $ID_SH; ?>" <?php if ( "$SelIdSh" != "$ID_SH" ){ ?>hidden<?php } ?> >
				<table  style="width:100%;border:1px solid black;background:white;left:10%;margin:auto;position: relative;">
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
							<td class="ShLine">
								<B><?php echo $DescType; ?></B>
								<table style="width:300px;left:0;right:0;margin:auto;">
								<?php
								$ListIdFind=array();
								foreach( ${'Mail_'.$TYPE.'_'.$ID_SH} as $DettMail ){
									$ID_MAIL_ANAG=$DettMail[0];
									array_push($ListIdFind, $ID_MAIL_ANAG); 
									$MAIL=$DettMail[1];
									$MAIL_ENABLE=$DettMail[2];
									?><tr>
											<td><img src="../images/Cestino.png" width="25px" class="Puls" onclick="RemoveMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"><?php echo $MAIL; ?></td>
											<td><?php 
											if ( "$MAIL_ENABLE" == "Y" ){
												?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"><?php
											} else {
												?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableMail('<?php echo $ID_SH; ?>','<?php echo $TYPE; ?>','<?php echo $ID_MAIL_ANAG; ?>')"><?php
								            }
											?></td>
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
													?><option value="<?php echo $IdMail; ?>" ><?php echo "$UsrMail ($NameMail)"; ?></option><?php
											    }
											}
											?>
										 </select>
									 </td>
									 <td><img src="../images/Aggiungi.png" width="25px" ></td>
								 </tr>
							     </table>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
		   </td><?php	
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
	   
	   function DisableShMultiSend(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShMultiSend")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   function EnableShMultiSend(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShMultiSend")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   
	   
	   function DisableShMail(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShMail")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   function EnableShMail(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShMail")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   function DisableShDebug(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableShDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   function EnableShDebug(vIdSh){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableShDebug")
			.val(vIdSh);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }	   
	   function RemoveMail(vIdSh, vType, vIdMail){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "RemoveMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();		   
	   }
	   function DisableMail(vIdSh, vType, vIdMail){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "DisableMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
	   }
	   function EnableMail(vIdSh, vType, vIdMail){
		    var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "EnableMail")
			.val(vIdSh+';'+vType+';'+vIdMail);
			$('#FormMail').append($(input));
			$('#FormMail').submit();
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
     </SCRIPT>
	<?php
}
?>
