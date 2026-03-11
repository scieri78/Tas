	<FORM id="FormAggiungiFlusso" method="POST" >
	
	
            <input type="hidden" id="IdTeam" name="IdTeam" value="<?php echo $IdTeam; ?>" >
            <input type="hidden" id="IdWorkFlow" name="IdWorkFlow" value="<?php echo $IdWorkFlow; ?>" >
            <input type="hidden" id="Azione" name="Azione" value="AF" >
            <input type="hidden" id="LivPersNum" name="LivPersNum" value="0" >

      
        <label>Nome Flusso</label>
        <input onblur="SpacesToUnderscore(this)" type="text"  id="NomeFlusso" Name="NomeFlusso" />
        <BR>
        <label>Descr. Flusso</label>
        <input type="text"  id="DescFlusso" Name="DescFlusso" />
        <BR>
        <label>Comportamento con periodo Consolidato</label><BR>
        <select id="BlockConsFlu" Name="BlockCons"   >
		  <option value="N" > Disabilitato con periodo Consolidato </option>
		  <option value="S" > Abilitato con periodo Consolidato </option>
		  <option value="X" > Abilitato senza svalidazione con periodo Consolidato </option>
		  <option value="O" > Sempre Abilitato </option>
        </select> 
		 <BR>
		 <BR> 
		<button id="addflusso" onclick="AggiungiFlusso();return false;" class="btn"><i class="fa-solid fa-plus-circle"> </i> Flusso</button>	
   
        
  
	
	
	</FORM>