<?php
session_cache_limiter('private_no_expire');
session_start() ;
include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
//include './GESTIONE/login.php';
//include './GESTIONE/sicurezza.php';

if ( 1 )  {
        
    $IdWorkFlow=$_REQUEST['IdWorkFlow'];   
    $IdProcess=$_POST['IdProcess'];

    if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }

    $IdLegame=$_POST['LinkIdLegame'];  
    $Pagina=$_POST['LinkPagina'];
    $NameDip=$_POST['LinkNameDip'];
    $Bloccato=$_POST['LinkBloccato'];
    
    $EsitoDip=$_POST['LinkEsitoDip'];

    $User=$_SESSION['codname'];
    ?>
    <input type="hidden" name="LinkIdLegame" value="<?php echo $IdLegame; ?>" >
    <input type="hidden" name="LinkPagina"   value="<?php echo $Pagina; ?>"   >
    <input type="hidden" name="LinkNameDip"  value="<?php echo $NameDip; ?>"  >
	<input type="hidden" name="LinkBloccato"  value="<?php echo $Bloccato; ?>"  >
	<input type="hidden" name="LinkEsitoDip" id="LinkEsitoDip" value="<?php echo $EsitoDip; ?>"  >
    <div class="Titolo" ><?php echo $NameDip; ?></div><BR><BR>
    <div class="Bottone" onclick="$('#OpenLinkPage').empty().hide();$('#Waiting').hide();" >Chiudi</div>
	<div id="ConfPreElab" name="ConfPreElab" >
	<?php
    include('./PHP/ConfPreElab/'.$Pagina);
    ?>
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
          $('#OpenLinkPage').empty().load('./PHP/Workflow_OpenLinkPage.php',{
                IdWorkFlow:   '<?php echo $IdWorkFlow; ?>'
                <?php
                foreach($_POST as $Nome => $Variabile ){
                   echo ",$Nome : '".$Variabile."'";
                }
                ?>
          }).show();
	  }
	  
	  $('#Waiting').hide();
    </script>
    <?php
}
?>
<script>
	  $('#Waiting').hide();
</script>