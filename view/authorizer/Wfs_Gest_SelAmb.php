<style>
.Titolo{
	font-size: 25px;
    margin: auto;
    left: 0;
    right: 0;
    width: 225px;
    padding-top: 10px;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $Azione=$_POST['Azione'];
    $WorkFlow=$_POST['WorkFlow'];
    $Descr=$_POST['Descr'];
	$IdTeam=$_POST['IdTeam'];
	

    if ( "$conn" == "Resource id #4" ) {
      $conn = db2_connect($db2_conn_string, '', '');
    }


    ?>
	<FORM id="FormMain" method="POST" >
	<div class="Titolo" >Workflow Authorizer</div>
    <div id="LoadConfig" >
	    <input type="submit" value="REFRESH"><BR>
        <label>Team</label><BR>
		<select id="IdTeam" name="IdTeam"  class="FieldMand" style="width:150px;height:30px;">
			<option value=""     <?php if ( "$IdTeam" == "" ){?>selected<?php } ?> >..</option>
			<?php
			$sqlLA="SELECT ID_TEAM,TEAM FROM WFS.TEAM ORDER BY TEAM";
			$stmtLA=db2_prepare($conn, $sqlLA);
			$resLA=db2_execute($stmtLA);
			while ($rowLA = db2_fetch_assoc($stmtLA)) {
				$TabIdTeam=$rowLA['ID_TEAM'];
				$TabTeam=$rowLA['TEAM'];
				?><option value="<?php echo $TabIdTeam; ?>" <?php if ( "$IdTeam" == "$TabIdTeam" ){?>selected<?php } ?> ><?php echo $TabTeam; ?></option><?php
			}
			?>
		</select>
		<BR>
		<?php
		if ( "$IdTeam" != "" ){
			$sqlLA="SELECT ID_WORKFLOW,WORKFLOW,UPPER(DESCR) DESCR, FREQUENZA, MULTI FROM WFS.WORKFLOW  WHERE ABILITATO = 'Y' AND ID_TEAM = '$IdTeam' ORDER BY WORKFLOW";
			$stmtLA=db2_prepare($conn, $sqlLA);
			$resLA=db2_execute($stmtLA);
			while ($rowLA = db2_fetch_assoc($stmtLA)) {
				$IdWorkFlow=$rowLA['ID_WORKFLOW'];
				$WorkFlow=$rowLA['WORKFLOW'];
				$Descr=$rowLA['DESCR'];
				$Freq=$rowLA['FREQUENZA'];
				$Multi=$rowLA['MULTI'];

				?>
				<div class="Ambiente" id="WFS<?php echo $IdWorkFlow; ?>" >
					<table>
					<tr >
					 <td style="width:20%;vertical-align: baseline;" >
						<table>
						<tr>
						<td style="width:40px;" >
						  <div id="PulShowFls<?php echo $IdWorkFlow; ?>" class="Plst" ><img class="ImgIco" src="../images/Matita.png"></div>
						</td>
						<td>
						  <div style="color: brown;font-size:15px;" ><?php echo $WorkFlow." [$Freq]"; if ( "$Descr" != "" ) { echo " ( $Descr )"; } ?></div>
						</td>
						</tr>
						</table>
						<script>
						  $('#PulShowFls<?php echo $IdWorkFlow; ?>').click(function(){
							if ( $('#LoadFls<?php echo $IdWorkFlow; ?>').children().length == 0) {
							  $('#LoadFls<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_LoadFlussi.php',{
								   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
								   WorkFlow: '<?php echo $WorkFlow; ?>'
							  });
							} else {
							  $('#LoadFls<?php echo $IdWorkFlow; ?>').empty();
							}
						  });
						</script>
					 </td>
					 <td>
						<div id="LoadDett<?php echo $IdWorkFlow; ?>"></div>
						<div id="LoadFls<?php echo $IdWorkFlow; ?>" ></div>
						<script>
							$('#LoadDett<?php echo $IdWorkFlow; ?>').load('../PHP/Wfs_Gest_DettWfs.php',{
								   IdWorkFlow: <?php echo $IdWorkFlow; ?>,
								   WorkFlow: '<?php echo $WorkFlow; ?>'
							});
						</script>
					</td>
					</tr>
					</table>
				</div>
				<?php		           
			}
		}
		?>
    </div>
	<div id="ADDDiv" ></div>
    </form>
	<script>
		$('#IdTeam').change(function(){
            $('#Waiting').show();
            $('#FormMain').submit();
		});
	</script>
    <?php
}
?>
