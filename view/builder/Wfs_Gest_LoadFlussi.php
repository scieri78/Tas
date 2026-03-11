<?php

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php';
include './GESTIONE/login.php';
include './GESTIONE/sicurezza.php';

if ( 1 )  {

    $IdWorkFlow=$_REQUEST['IdWorkFlow'];
    $WorkFlow=$_POST['WorkFlow'];
    
        
    $ListF="SELECT
      b.ID_FLU, b.FLUSSO
    from
      WFS.LEGAME_FLUSSI a,
      WFS.FLUSSI b
    where 1=1
      AND a.ID_WORKFLOW = b.ID_WORKFLOW
      AND a.ID_FLU=b.ID_FLU
      AND a.ID_WORKFLOW = $IdWorkFlow
    group by 
      b.ID_FLU, b.FLUSSO
    order by b.FLUSSO 
    ";
    $resF = db2_prepare($conn, $ListF);
    $resultTabRead = db2_execute($resF); 
    
    ?><table class="ExcelTable">
    <tr>
      <th><img class="ImgIco" src="./images/Flusso.png"></th>
      <th><img class="ImgIco" src="./images/Gruppo.png"></th>
    </tr><?php
    while ($rowFlusso = db2_fetch_assoc($resF)) { 
      $IdFlusso=$rowFlusso["ID_FLU"];
      $Flusso=$rowFlusso["FLUSSO"];     
      ?>
      <tr class="bordertop" >
        <th style="width:20%;vertical-align: baseline;" >
          <div id="PulRemUser" class="Plst Mattone" >
             <div class="Plst" style="float: left;" onclick="ModAuthDipFlu(<?php echo $IdFlusso; ?>,'<?php echo $Flusso; ?>')" ><img class="ImgIco" src="./images/Matita.png" title="<?php echo $IdFlusso; ?>" ></div>
             <?php echo "$Flusso"; ?>
          </div>
        </th>
        <td>
           <div id="ShowFlu_<?php echo $IdWorkFlow.$IdFlusso; ?>" ></div>
           <script>
              $('#ShowFlu_<?php echo $IdWorkFlow.$IdFlusso; ?>').load('./PHP/Wfs_Gest_LoadFlusso.php',{
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   WorkFlow: '<?php echo $WorkFlow; ?>',
                   IdFlusso: '<?php echo $IdFlusso; ?>',
                   Flusso: '<?php echo $Flusso; ?>'
              });         
         
           </script>           
           <div id="PulAddGrIn<?php echo $IdWorkFlow.$IdFlusso; ?>" class="Plst Mattone" style="color:red;"><img class="ImgIco" src="./images/Aggiungi.png"></div>
           <script>
                 $("#PulAddGrIn<?php echo $IdWorkFlow.$IdFlusso; ?>").click(function(){
                  $('#ADDDiv').empty();
                  $('#ADDDiv').load('./PHP/Wfs_Gest_AddGroupTo.php',{
                   IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                   WorkFlow: '<?php echo $WorkFlow; ?>',
                   IdFlusso: '<?php echo $IdFlusso; ?>',
                   Flusso: '<?php echo $Flusso; ?>'
                  });
                  $('#ADDDiv').show();
                 });              
           </script>
        </td>
      </tr>
      <tr id="InsAuthDip<?php echo $IdWorkFlow.$IdFlusso; ?>" hidden ><td colspan=2 ><div id="LoadAutDip<?php echo $IdWorkFlow.$IdFlusso; ?>" ></div>
      </td></tr>
      <?php
    }
    ?>
    <tr>
      <td colspan="2" >
        <button id="HF<?php echo $IdWorkFlow; ?>" >Close</button>
        <script>
          $("#HF<?php echo $IdWorkFlow; ?>").click(function(){
              $('#LoadFls<?php echo $IdWorkFlow; ?>').empty().hide();
          });       
        </script>
      </td>
    </tr>
    </table>
    <script>
      function ModAuthDipFlu(vIdFlu,vFlusso){
        if ( $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).children().length == 0) {
              $('#TopScroll').val($( window ).scrollTop());
              $('#BodyHeight').val($('body').height());
              $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).empty().load('./PHP/Wfs_Gest_LoadAuthDipFlusso.php', {
                IdWorkFlow: '<?php echo $IdWorkFlow; ?>',
                WorkFlow: '<?php echo $WorkFlow; ?>',
                IdFlusso: vIdFlu,
                Flusso: vFlusso
              });
              $('#InsAuthDip<?php echo $IdWorkFlow; ?>'+vIdFlu).show();
        } else {
              $('#LoadAutDip<?php echo $IdWorkFlow; ?>'+vIdFlu).empty();
              $('#InsAuthDip<?php echo $IdWorkFlow; ?>'+vIdFlu).hide();
        }
      }
    </script>
    <?php
}
?>
