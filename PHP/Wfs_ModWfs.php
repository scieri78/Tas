<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {
$TServer="Jiak";
	$IdWorkFlow=$_REQUEST['IdWorkFlow'];
	$IdTeam=$_POST['IdTeam'];
	
    if ( "$conn" == "Resource id #4" ) {
      $conn = db2_connect($db2_conn_string, '', '');
    }
	if ( "$IdWorkFlow" != "" ){
		$sqlWfs="SELECT WORKFLOW,UPPER(DESCR) DESCR, READONLY, FREQUENZA, MULTI FROM WFS.WORKFLOW WHERE ID_WORKFLOW = $IdWorkFlow ";
		$stmtWfs=db2_prepare($conn, $sqlWfs);
		$resWfs=db2_execute($stmtWfs);
		while ($rowWfs = db2_fetch_assoc($stmtWfs)) {
			$TabWorkFlow=$rowWfs['WORKFLOW'];
			$TabDescr=$rowWfs['DESCR'];
			$TabReadOnly=$rowWfs['READONLY'];
			$TabFreq=$rowWfs['FREQUENZA'];
			$TabMulti=$rowWfs['MULTI'];
		}
	}
    ?>
	<div id="FormModWfs" >
            <input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>" >
	        <?php
		    if ( "$IdWorkFlow" != "" ){
	          ?><div id="PulCreateWF" style="color:red;" ><img class="ImgIco" src="../images/Matita.png" >Modifica Workflow <?php echo $TabWorkFlow; ?></div><?php
	        }else{
			  ?><div id="PulCreateWF" style="color:red;" ><img class="ImgIco" src="../images/Aggiungi.png" >Crea Workflow</div><?php
			}
	        ?>
        	<div id="ShowAggiungiAmb" >
				<div><label>WorkFlow</label></div>
				<div><input type="text" id="InpWorkFlow" Name="InpWorkFlow" style="width:100%" value="<?php echo $TabWorkFlow; ?>"  class="ModificaField" <?php if ( "$TServer" == "PROD USER" ){ ?> readonly <?php } ?>/></div>
				<div><label>Descrizione</label></div>
				<div><input type="text" id="InpDescr" Name="InpDescr"  style="width:100%" value="<?php echo $TabDescr; ?>"  class="ModificaField" <?php if ( "$TServer" == "PROD USER" ){ ?> readonly <?php } ?> /></div>
				<table>
				<tr><td>Frequenza</td>
				<td>
				      <select id="InpFreq" name="InpFreq"  class="ModificaField" >
                         <option value="M" <?php if ( "$TabFreq" == "M" ) { ?>selected<?php } ?> >Mensile</option>
                         <option value="Q" <?php if ( "$TabFreq" == "Q" ) { ?>selected<?php } ?> >Trimestrale</option>
                         <option value="A" <?php if ( "$TabFreq" == "A" ) { ?>selected<?php } ?> >Annuale</option>
				      </select>
				</td></tr>
				<tr><td>Multi Processo</td>
				<td>
				      <select id="InpMulti" name="InpMulti"  class="ModificaField" >
				         <option value="N" <?php if ( "$TabMulti" == "N" ) { ?>selected<?php } ?> >No</option>
				   	     <option value="S" <?php if ( "$TabMulti" == "Y" ) { ?>selected<?php } ?> >Si</option>
				      </select>
				</td></tr> 
				<?php
		        if ( "$IdWorkFlow" != "" ){
				   ?>
				 <tr><td>ReadOnly</td>
				 <td>
				    <select id="InpReadOnly" name="InpReadOnly"  class="ModificaField" >
				          <option value="N" <?php if ( "$TabReadOnly" == "N" ) { ?>selected<?php } ?> >No</option>
				    	     <option value="S" <?php if ( "$TabReadOnly" == "Y" ) { ?>selected<?php } ?> >Si</option>
				    </select>
			     </td></tr>
				 <?php
				}
				?>
				</table>
				<BR><BR>
				<button id="ChiudiModWfs" >Close</button>	
				<?php
		        if ( "$IdWorkFlow" != "" ){
	              ?><button id="PulModificaWF" hidden >Modifica</button>
				  <script>
				  function TestModifica(){
						var vTest = false;
										
						if ( $('#InpWorkFlow').val() != '' && $('#InpWorkFlow').val() != '<?php echo $TabWorkFlow; ?>' ){ vTest = true;}
						
						if ( $('#InpDescr').val() != '<?php echo $TabDescr; ?>' ){ vTest = true;}
						
						if ( $('#InpMulti').val() != '' && $('#InpMulti').val() != '<?php echo $TabMulti; ?>' ){ vTest = true;}
						
						if ( $('#InpFreq').val() != '' && $('#InpFreq').val() != '<?php echo $TabFreq; ?>' ){ vTest = true;}
						
						if ( $('#InpReadOnly').val() != '' && $('#InpReadOnly').val() != '<?php echo $TabReadOnly; ?>' ){ vTest = true;}
										
						if (vTest){
							$('#PulModificaWF').show();
						} else {
							$('#PulModificaWF').hide();
						}
					}
			      </script>
				  <?php
				}else{
				  ?><button id="PulAggiungiWF" hidden >Aggiungi</button>
				  <script>
				  function TestModifica(){
						var vTest = false;
										
						if ( $('#InpWorkFlow').val() != '' && $('#InpWorkFlow').val() != '<?php echo $TabWorkFlow; ?>' ){ vTest = true;}
						
						if ( $('#InpMulti').val() != '' && $('#InpMulti').val() != '<?php echo $TabMulti; ?>' ){ vTest = true;}
						
						if ( $('#InpFreq').val() != '' && $('#InpFreq').val() != '<?php echo $TabFreq; ?>' ){ vTest = true;}
										
						if (vTest){
							$('#PulAggiungiWF').show();
						} else {
							$('#PulAggiungiWF').hide();
						}
					}
			      </script><?php
				}
				?>			
			</div>
	</div>
	<script>

		$('.ModificaField').keyup(function(){
			TestModifica();
		});
		
		$('.ModificaField').change(function(){
			TestModifica();
		});
		
		TestModifica();
	
		$("#PulModificaWF").click(function(){	
			    $('#IdWorkFlow').val('<?php echo $IdWorkFlow; ?>');
				$('#Azione').val('Modifica');
				$('#FormMain').submit();	
		});	
		
		$("#PulAggiungiWF").click(function(){
			    $('#IdWorkFlow').val('<?php echo $IdWorkFlow; ?>');
			    $('#Azione').val('Aggiungi');
				$('#FormMain').submit();	
		});	
		
		$("#ChiudiModWfs").click(function(){
		   $('#FormMain').submit();	   
		});
		
	</script>
	<?php
	
}

?>