</div>
    <script>
     $('.FieldConf').change(function(){
          if ( '<?php echo $EsitoDip; ?>' == 'F'|| '<?php echo $Bloccato; ?>' == 'Y'  ){
             $('.SalvaConf').each(function(){$(this).hide();});      
          } else {  
            var vTest = true;
            $('.FieldConf').each(function(){
                if ($(this).val() == '' ){ vTest = false; }
            });
            if ( vTest && 'N' == '<?php echo $Bloccato; ?>' ){
               $('.SalvaConf').each(function(){$(this).show();});
            } else {
               $('.SalvaConf').each(function(){$(this).hide();});
            }
          }
      });
      if ( '<?php echo $EsitoDip; ?>' == 'F' || '<?php echo $Bloccato; ?>' == 'Y' ){
         $('.SalvaConf').each(function(){$(this).hide();});      
      }
	  
	  function ReloadLink(){
         // $('#Waiting').show();
         OpenLinkPage(IdWorkFlow,array_post);
        
	  }
	  
	  $('#Waiting').hide();
    </script>
    <?php

?>
</form>
