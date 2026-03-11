		<?php
		
		if ( $IdTeam != ""  && $selIdWorkFlow != "") {
			
			foreach ($DatiAuthorizer as $rowLA) {
				$IdWorkFlow=$rowLA['ID_WORKFLOW'];
				$WorkFlow=$rowLA['WORKFLOW'];
				$Descr=$rowLA['DESCR'];
				$Freq=$rowLA['FREQUENZA'];
				$Multi=$rowLA['MULTI'];
				$titoloWF = $WorkFlow." [$Freq]"." ( $Descr )";

				?>
				<div  class="Ambiente" id="WFS<?php echo $IdWorkFlow; ?>" >
					<table>
					 <caption><?php echo $titoloWF;?></caption>
					<tr>					 
					 <td>
						<div class="loadAutorizer" id="LoadDett<?php echo $IdWorkFlow; ?>"></div>
						<div class="loadAutorizer" id="LoadFls<?php echo $IdWorkFlow; ?>"></div>						
					</td>
					</tr>
					</table>
				</div>
				<?php		           
			}
		}
		?>