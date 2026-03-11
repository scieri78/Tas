<?php

include '../GESTIONE/connection.php';
include '../GESTIONE/SettaVar.php';
include '../GESTIONE/login.php';
include '../GESTIONE/sicurezza.php';

if ( "$find" == "1" )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
    $IdFlusso=$_POST['IdFlusso'];
    $Flusso=$_POST['Flusso'];
        
    
        
    $ListF="SELECT DISTINCT
      L.ID_DIP
      , L.TIPO
      , CASE
       WHEN L.TIPO = 'C' THEN (SELECT CARICAMENTO  FROM WFS.CARICAMENTI  WHERE ID_CAR  = L.ID_DIP )
       WHEN L.TIPO = 'E' THEN (SELECT ELABORAZIONE FROM WFS.ELABORAZIONI WHERE ID_ELA  = L.ID_DIP )
       WHEN L.TIPO = 'V' THEN (SELECT VALIDAZIONE  FROM WFS.VALIDAZIONI  WHERE ID_VAL  = L.ID_DIP )
       WHEN L.TIPO = 'L' THEN (SELECT LINK         FROM WFS.LINKS        WHERE ID_LINK = L.ID_DIP )
      END DIPENDENZA
    from
      WFS.LEGAME_FLUSSI L
    LEFT JOIN
      WFS.AUTORIZZAZIONI_DIP A
    ON 1=1
      AND L.ID_WORKFLOW = A.ID_WORKFLOW
      AND L.ID_FLU      = A.ID_FLU
      AND L.TIPO        = A.TIPO
      AND L.ID_DIP      = A.ID_DIP
    where 1=1
      AND L.ID_WORKFLOW = $IdWorkFlow
      AND L.ID_FLU = $IdFlusso
      AND L.TIPO != 'F'
    ORDER BY 3
    ";
    $resF = db2_prepare($conn, $ListF);
    $resultTabRead = db2_execute($resF); 
    
    ?>
    <br>
    <table class="ExcelTable" style="width:90%;">
    <tr>
      <th style="font-size:15px;" >DIPENDENZA <?php echo $Flusso; ?></th>
      <th><img class="ImgIco" src="../images/Gruppo.png"></th>
    </tr><?php
    while ($rowFlusso = db2_fetch_assoc($resF)) { 
      $IdDip=$rowFlusso["ID_DIP"];
      $TipoDip=$rowFlusso["TIPO"];
      $NameDip=$rowFlusso["DIPENDENZA"];     
      $GruppoDip=$rowFlusso["ID_GRUPPO"];

       switch ( $TipoDip ) {
         case "C":
           $ImgTipo="Carica";
           break;
         case "F":
           $ImgTipo="Flusso";
           break;
         case "V":
           $ImgTipo="Valida";
           break;  
         case "E":
           $ImgTipo="Elaborazione";
           break;  
         case "L":
           $ImgTipo="Setting";
           if ( "$DipLinkTipo" == "E" ){
             $ImgTipo="Link";
           }
           break;  
       }
       
      ?>
      <tr class="bordertop" >
        <td style="width:20%;vertical-align: baseline;" >
           <img class="ImgIco" src="../images/<?php echo $ImgTipo; ?>.png" title="<?php echo $IdDip; ?>" >
           <?php echo "$NameDip"; ?>
        </td>
        <td>
           <div id="ShowAuthDip_<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" ></div>
           <script>
              $('#ShowAuthDip_<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>').empty().load('../PHP/Wfs_Gest_LoadAuthDip.php',{
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   WorkFlow: '<?php echo $WorkFlow; ?>',
                   IdFlusso: '<?php echo $IdFlusso; ?>',
                   Flusso: '<?php echo $Flusso; ?>',
                   IdDip: '<?php echo $IdDip; ?>'
              });  
           </script>           
           <div id="PulAddAthIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" class="Plst Mattone" style="color:red;"><img class="ImgIco" src="../images/Aggiungi.png"></div>
           <script>
                 $("#PulAddAthIn<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").click(function(){
                  $('#ADDDiv').empty();
                  $('#ADDDiv').load('../PHP/Wfs_Gest_AddGroupToDip.php',{
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   WorkFlow: '<?php echo $WorkFlow; ?>',
                   IdFlusso: '<?php echo $IdFlusso; ?>',
                   Flusso: '<?php echo $Flusso; ?>',
				   Tipo: '<?php echo $TipoDip; ?>',
                   IdDip: '<?php echo $IdDip; ?>',
                   Dipendenza : '<?php echo $NameDip; ?>'
                  });
                  $('#ADDDiv').show();
                 });              
           </script>
        </td>
      </tr>
      <?php
    }
    ?>
    <tr>
      <td colspan="2" >
        <button id="ClADF<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>" >Close</button>
        <script>
          $("#ClADF<?php echo $IdWorkFlow.$IdFlusso.$IdDip; ?>").click(function(){
              $('#LoadAutDip<?php echo $IdWorkFlow.$IdFlusso; ?>').empty();
              $('#InsAuthDip<?php echo $IdWorkFlow.$IdFlusso; ?>').hide();
          });       
        </script>
      </td>
    </tr>
    </table><?php
}
?>
