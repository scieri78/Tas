<?php
session_cache_limiter('private_no_expire');
session_start() ;
include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
//include './GESTIONE/login.php';
//include './GESTIONE/sicurezza.php';        
   
    ?>
    <form id="openLinkPage">
    <input type="hidden" name="LinkIdLegame" value="<?php echo $IdLegame; ?>" >
    <input type="hidden" name="LinkPagina"   value="<?php echo $Pagina; ?>"   >
    <input type="hidden" name="LinkNameDip"  value="<?php echo $NameDip; ?>"  >
	<input type="hidden" name="LinkBloccato"  value="<?php echo $Bloccato; ?>"  >
	<input type="hidden" name="LinkEsitoDip" id="LinkEsitoDip" value="<?php echo $EsitoDip; ?>"  >
    <div class="Titolo" ><?php echo $NameDip; ?></div><BR><BR> 
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
