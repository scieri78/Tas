<style>
#DivLoad{
    position:absolute;
    left:0;
    right:0;
    margin:auto;
    width:600px;
    height:200px;
    background:white;
    color:black;
    border:1px solid black;
    padding:10px;
}

#EsitoImg{
	width:30px;
	left:0;
	right:0;
	margin:auto;
}

#EsitoUpload{
	position:fixed;
	top:0;
	bottom:0;
	left:0;
	right:0;
	margin:auto;
	width:300px;
	height:300px;
	background:white;
	border:1px solid black;
	z-index:9999;
	text-align: center;
    padding: 20px;
	overload:auto;
}

</style>
<?php
include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {


    function EsitoRichiesta( $NotaRis, $EsitoRis , $ErroreRis ){
        $Img="OK";
        if ( $EsitoRis == 'KO' ) {
           $Img="KO"; 
        }
        ?>
        <div id="EsitoUpload" >
            <img id="EsitoImg" src="../images/<?php echo $Img; ?>.png" h><BR>
            <div id="EsitoText"><?php echo $NotaRis; ?></div><BR>
            <?php 
            if ( $ErroreRis <> '' ) {
            ?><div id="EsitoText"><?php print_r ($ErroreRis); ?></div><?php
            }
            ?>
        </div>
        <?php
    }
    


    $SelMeseElab=$_POST['SelMeseElab'];
    $Sel_Id_Proc=$_POST['Sel_Id_Proc'];
    $Sel_Flusso=$_POST['Sel_Flusso'];
    $Sel_File=$_POST['Sel_File'];
    $LoadXls = $_POST["LoadXls"];


    if ( "$LoadXls" == "Load" ){
        
        if ( "$SelMeseElab" == ""
             or "$Sel_Id_Proc" == ""
             or "$Sel_Flusso" == ""
          ){
          $Note="Errore: non sono stati compilati tutti i campi necessari per il caricamento";        
          EsitoRichiesta( $Note ,'KO', $errors);
        } else {        
           if( isset($_FILES['Sel_File']) ){
              $errors= array();
              $file_name = $_FILES['Sel_File']['name'];
              $file_size =$_FILES['Sel_File']['size'];
              $file_tmp =$_FILES['Sel_File']['tmp_name'];
              $file_type=$_FILES['Sel_File']['type'];
              $file_ext=strtolower(end(explode('.',$_FILES['Sel_File']['name'])));
                        
              $expensions= array("xls","xlsx","csv");
              
              if(in_array($file_ext,$expensions)=== false){
                 $errors[]="Il File non e' conforme al formato richiesto: xls,xlsx,csv";
              }
              
              if($file_size > 62428800){
                 $errors[]='Il File eccede dai 60 MB massimi di caricamento';
              }
              
              if(empty($errors)==true){
                    $moved = move_uploaded_file($file_tmp,"../UploadFile/${Sel_Id_Proc}_${Sel_Flusso}.${file_ext}");
                    
                    if( $moved ) {
                        $Prosegui=true;                                
                        $command='scp ../UploadFile/'.${Sel_Id_Proc}."_".${Sel_Flusso}.'.'.${file_ext}.' tusin107@'.${SERVER}.':/area_staging_TAS/FROM_EXT/LOAD_FILES/upload_files/';
                        $out = shell_exec($command);                      
                        if ( empty($out) ){
                           $Prosegui=true;
                        } else {
                           $Prosegui=false;
                        }                    
                        if ( $Prosegui ) { 
                            $out = shell_exec("sh $PRGDIR/TAS_Loader.sh ${DATABASE} ${Sel_Id_Proc} ${Sel_Flusso} '${SERVER}' ");
                            if ( ! empty($out) ){
                              $Note= "Load in error: $out";
                              $errors="";
                              EsitoRichiesta( $Note ,'KO', $errors);					  
                            }     
                        } else {
                          $Note="Error upload file to Aix";
                          $errors="";
                          EsitoRichiesta( $Note ,'KO', $errors); 
                        }
                    } else {
                      $Note="Error upload file to WebServer";
                      $errors="";
                      EsitoRichiesta( $Note ,'KO', $errors);
                    }   
                       
           
              }else{
                 $Note="Errore nel caricamento del File:";
                 EsitoRichiesta( $Note ,'KO', $errors);
              }
           }else{
             $Note="Errore Caricamento File Web:";
             EsitoRichiesta( $Note ,'KO', $errors);
           }
       }
       unset($_POST['PulCaricaFile']);
    }


  
    ?>
    <div id="DivLoad" >
    <FORM id="FormEserEsame" method="POST" enctype="multipart/form-data">
    <table width="100%" style="border-bottom:none;" >
    <tr>
      <th>MESE ELAB.</th>
      <td style="border-bottom:none;" >
        <SELECT name="SelMeseElab" onchange="$('#FormEserEsame').submit()" style="width:300px;">
        <?php
        $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB FROM WORK_CORE.CORE_SH ORDER BY MESEELAB DESC";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo $sql;
            echo "ERROR DB2 1";
        }
        db2_close($db2_conn_string);
        while ($row = db2_fetch_assoc($stmt)) {
          $MeseElab=$row['MESEELAB'];
          if ( "$SelMeseElab" == "" ) { $SelMeseElab=$MeseElab; }
          ?><option value="<?php echo $MeseElab; ?>" <?php if ( "$SelMeseElab" == "$MeseElab" ){ ?>selected<?php } ?> ><?php echo $MeseElab; ?></option><?php
        }
        ?>
        </SELECT>
      </td>
    </tr>
    <tr>
      <th>PROCESS</th>
      <td style="border-bottom:none;" >
          <SELECT name="Sel_Id_Proc" onchange="$('#FormEserEsame').submit()" style="width:300px;">
          <?php
          $sql = "SELECT ID_PROCESS, DESCR, TIPO, FLAG_STATO 
          FROM WORK_CORE.ID_PROCESS 
          WHERE ESER_ESAME||LPAD(MESE_ESAME,2,0) = '$SelMeseElab'
          ORDER BY DATA_APERTURA DESC";
          $conn = db2_connect($db2_conn_string, '', '');
          $stmt=db2_prepare($conn, $sql);
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo $sql;
            echo "ERROR DB2 1";
          }
          db2_close($db2_conn_string);
          while ($row = db2_fetch_assoc($stmt)) {
            $Id_Proc=$row['ID_PROCESS'];
            if ( "$Sel_Id_Proc" == "" ){$Sel_Id_Proc=$Id_Proc;}   
            $Descr=$row['DESCR'];
            $Tipo=$row['TIPO'];
            $Stato=$row['FLAG_STATO'];
            switch($Tipo){
              case 'Q':
                $LabelTipo="Quarter";
                break;;
              case 'M':
                $LabelTipo="Mensile";
                break;;
              case 'R':
                $LabelTipo="Restatemnt";
                break;;         
            }
            switch($Stato){
              case 'C':
                $LabelStato="Chiuso";
                break;;
              case 'A':
                $LabelStato="Aperto";
                break;;
              case 'S':
                $LabelStato="Sospeso";
                break;;         
              case 'D':
                $LabelStato="Cancellato";
                break;;                     
            }   
            ?><option value="<?php echo $Id_Proc; ?>" <?php if ( "$Sel_Id_Proc" == "$Id_Proc" ){ ?>selected<?php } ?> ><?php echo $Descr.'('.$LabelTipo.' '.$LabelStato.')'; ?></option><?php
          }
          ?></SELECT>
      </td>
  </tr>
  <tr>
      <th>FLUSSO</th>
      <td style="border-bottom:none;" >
          <SELECT name="Sel_Flusso" onchange="$('#FormEserEsame').submit()" style="width:300px;">
          <option value="" >..</option>
          <?php
          $sql = "SELECT DISTINCT NOME_FLUSSO FROM WORK_RULES.LOAD_TRASCODIFICA ORDER BY NOME_FLUSSO";
          $conn = db2_connect($db2_conn_string, '', '');
          $stmt=db2_prepare($conn, $sql);
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo $sql;
              echo "ERROR DB2 1";
          }
          db2_close($db2_conn_string);
          while ($row = db2_fetch_assoc($stmt)) {
            $NOME_FLUSSO=$row['NOME_FLUSSO'];
            ?><option value="<?php echo $NOME_FLUSSO; ?>" <?php if ( "$NOME_FLUSSO" == "$Sel_Flusso" ){ ?>selected<?php } ?> ><?php echo $NOME_FLUSSO; ?></option><?php
          }
     
        ?>
        </SELECT>
  </td>
 </tr>
 <tr>
   <th>Load File:</th>
   <td>
       
       <input type="file" name="Sel_File" />                                   
   </td>
 </tr>
 <tr>
   <td colspan=2>
        <BR><BR>
        <CENTER><input name="LoadXls" id="LoadXls" value="Load" type="submit" class="Bottone" /></CENTER>
   </td>
 </tr>
 </table>
 </FORM>
</div>
  <?php
}
?>
<script>
 $('#LoadXls').click(function(){ $('#Waiting').show(); });
 $('#Waiting').hide();
</script>