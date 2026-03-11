<?php
  if (isset($_view['include_css'])) {
    echo $_view['include_css'];
  }
  ?>
    
    <form id="openLinkPage">
    <input type="hidden" id="LinkIdLegame" name="LinkIdLegame" value="<?php echo $IdLegame; ?>" >
    <input type="hidden" id="IdLegame" name="IdLegame" value="<?php echo $IdLegame; ?>" >
    <input type="hidden" id="LinkPagina" name="LinkPagina"   value="<?php echo $Pagina; ?>"   >
    <input type="hidden" id="LinkNameDip" name="LinkNameDip"  value="<?php echo $LinkNameDip; ?>"  >
	<input type="hidden" id="LinkBloccato" name="LinkBloccato"  value="<?php echo $Bloccato; ?>"  >
	<input type="hidden" id="LinkEsitoDip" name="LinkEsitoDip" id="LinkEsitoDip" value="<?php echo $EsitoDip; ?>"  >
	<input type="hidden" id="idLink" name="idLink" id="idLink" value="<?php echo $idLink; ?>"  >
   
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
	  

    </script>

