<?php

include './GESTIONE/connection.php';
include './GESTIONE/SettaVar.php'; 


$IdWorkFlow=$_REQUEST['IdWorkFlow'];
$IdProcess=$_POST['IdProcess'];
$WfsName=$_POST['WfsName'];
$ProcDescr=$_POST['ProcDescr'];
?>
<table>
<tr>
<td><div style="font-size:28px;"><?php echo $WfsName; if ( "$ProcDescr" != "" ) { echo "    ( $ProcDescr )"; } ?></div></td>
<td width="100px" ><div id="CloseDiagram" style="margin:2px;height: 30px;background: red;border: 1px solid black;text-align: center;padding: 6px;color: white;" onclick="$('#ShowDiagram').empty().hide();" >CLOSE</div></td>
<td width="100px" ><div id="CloseDiagram" style="margin:2px;height: 30px;background: red;border: 1px solid black;text-align: center;padding: 6px;color: white;" onclick="OpenDiagram1()" >REFRESH</div></td>
</tr>
</table>
<script>
    function OpenDiagram1(){
        $( "#ShowDiagram" ).empty().load('./PHP/GeneraDiagram.php',{
                IdWorkFlow : '<?php echo $IdWorkFlow; ?>',
                IdProcess  : '<?php echo $IdProcess; ?>',
                WfsName    : '<?php echo $WfsName; ?>',
                ProcDescr  : '<?php echo $ProcDescr; ?>'
        }).show();
    }
    
</script>

<?php

       $SqlList="SELECT MESE_ESAME FROM WORK_CORE.ID_PROCESS WHERE ID_PROCESS = $IdProcess";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
       while ($row = db2_fetch_assoc($stmt)) {
           $ProcMeseEsame=$row['MESE_ESAME'];  
       }
    

    //===============================================================================================
    //==========   Lista Test Flussi
       $ArrTest=array();
       $SqlList="SELECT DISTINCT ID_FLU FROM WFS.LEGAME_FLUSSI L
       WHERE ID_WORKFLOW = $IdWorkFlow AND
        ( (TIPO,ID_DIP) IN ( SELECT 'E',ID_ELA FROM WFS.ELABORAZIONI WHERE ID_WORKFLOW = $IdWorkFlow AND ID_ELA = L.ID_DIP AND ID_SH = 0 )
          OR 
          (TIPO,ID_DIP) IN ( SELECT 'C',ID_CAR FROM WFS.CARICAMENTI  WHERE ID_WORKFLOW = $IdWorkFlow AND ID_CAR = L.ID_DIP AND NOME_INPUT = 'WFS_TEST' )
          ) ";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); }
       $ArrElaTest=array();  
       while ($row = db2_fetch_assoc($stmt)) {
           $IdFl=$row['ID_FLU'];
           array_push($ArrTest,$IdFl);     
       }
    


  if ( "$IdWorkFlow" != "" ){
        //LEVEL
       $ArrLevel=array();
       $SqlList="SELECT DISTINCT LIV FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY LIV";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg($stmt); }  
       while ($row = db2_fetch_assoc($stmt)) {
           $Liv=$row['LIV']; 
               
           array_push($ArrLevel,$Liv);
       }   
       //LEGAMI
       $ArrLegami=array();
       $SqlList="SELECT ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP FROM WFS.LEGAME_FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY LIV, ID_FLU, TIPO, ID_DIP";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg($stmt); }  
       while ($row = db2_fetch_assoc($stmt)) {
           $Id=$row['ID_LEGAME']; 
           $Liv=$row['LIV']; 
           $IdFlu=$row['ID_FLU']; 
           $Prio=$row['PRIORITA']; 
           $Tipo=$row['TIPO']; 
           $IdDip=$row['ID_DIP']; 
               
           array_push($ArrLegami,array($Id,$Liv,$IdFlu,$Prio,$Tipo,$IdDip));
       } 

       //FLUSSI
       $ArrFlussi=array();
       $ArrPreFlussi=array();
       $ArrSucFlussi=array();
       $SqlList="SELECT ID_FLU,FLUSSO,DESCR FROM WFS.FLUSSI WHERE ID_WORKFLOW = $IdWorkFlow ORDER BY FLUSSO";
       $stmt=db2_prepare($conn, $SqlList);
       $res=db2_execute($stmt);
       if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg($stmt); } 
       while ($row = db2_fetch_assoc($stmt)) {
           $Id=$row['ID_FLU']; 
           $Name=$row['FLUSSO']; 
           $Desc=$row['DESCR'];        
           array_push($ArrFlussi,array($Id,$Name,$Desc));      
       }
       
    }
?>
<div style="width:5000px; height:1500px;">
<svg id="Schemasvg" style="width:4000; height:1500;">
<?php
  
$Assex=10;
$OldLiv="";
$ArrLine=array();
foreach( $ArrLevel as $Liv ){
    
         if ( $Liv <> $OldLiv ) {
              $Assex=$Assex+240;
              $OldLiv=$Liv;
              $Assey=$Liv*30;
          } 
                                    
        foreach( $ArrLegami as $Legame ){
           //ID_LEGAME,LIV,ID_FLU,PRIORITA,TIPO,ID_DIP
           if ( $Liv == $Legame[1] ){              
                                         
             $IdFlu=$Legame[2];
             if ( "$IdFlu" != "$OldIdFlu"){
               $OldIdFlu=$IdFlu;
               foreach( $ArrFlussi as $Flusso ){
                 $IdFlusso=$Flusso[0];
                 if ( "$IdFlusso" == "$IdFlu" ){ 
                      $NomeFlusso=$Flusso[1];
             
                      $SqlList="
                        WITH W_ABILITATE AS(
                          SELECT ID_DIP , TIPO FROM WFS.LEGAME_FLUSSI WHERE DECODE(VALIDITA,null,' $ProcMeseEsame ',' '||VALIDITA||' ') like '% $ProcMeseEsame %'
                        )
                        SELECT ID_FLU,FLUSSO,DESCR
                        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'E'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) KO
                        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) OK
                        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'I'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) IZ
                        ,( SELECT count(*) FROM WFS.ULTIMO_STATO   WHERE ID_WORKFLOW = F.ID_WORKFLOW AND NVL(ID_PROCESS,0) = NVL('$IdProcess',0) AND ID_FLU = F.ID_FLU AND ESITO = 'N'  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TD
                        ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO NOT IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TT
                        ,( SELECT count(*) FROM WFS.LEGAME_FLUSSI  WHERE ID_WORKFLOW = F.ID_WORKFLOW AND ID_FLU = F.ID_FLU AND TIPO IN ('W','F')  AND (TIPO,ID_DIP) IN ( SELECT TIPO,ID_DIP FROM W_ABILITATE )) TTF
                        FROM WFS.FLUSSI F 
                        WHERE ID_FLU = $IdFlusso 
                        ORDER BY FLUSSO";
                        //echo $SqlList;
                        $stmt=db2_prepare($conn, $SqlList);
                        $res=db2_execute($stmt);
                        if ( ! $res) { echo "Stmt Exec Error ".db2_stmt_errormsg(); } 
                        while ($row = db2_fetch_assoc($stmt)) {
                           $NomeFlusso=$row['FLUSSO']; 
                           $DescFlusso=$row['DESCR'];  
                           $CntKO=$row['KO'];  
                           $CntOK=$row['OK'];  
                           $CntIZ=$row['IZ'];  
                           $CntTD=$row['TD'];  
                           $CntTT=$row['TT'];  
                           $CntTTF=$row['TTF'];
                        }       
                           $Stato='F';
                           if ( $CntTTF != 0 ){
                                        
                               $Errore=0;
                               $Note="";
                               $Stato='N';
                                        
                               $CallPlSql='CALL WFS.K_WFS.TestSottoFlussi(?, ?, ?, ?, ?, ? )';
                               $stmt = db2_prepare($conn, $CallPlSql);
                               db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
                               db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
                               db2_bind_param($stmt, 3, "IdFlusso"    , DB2_PARAM_IN);
                               db2_bind_param($stmt, 4, "Stato"       , DB2_PARAM_OUT);
                               db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
                               db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
                               $res=db2_execute($stmt);
                               
                               if ( ! $res) {
                                 echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg();
                               }
                               
                               if ( $Errore != 0 ) {
                                 echo "PLSQL Procedure Calling Error $Errore: ".$Note;
                               } 
                               
                           }
                        
                            if (  $CntKO != 0 ) { 
                               $Esito="E";
                            } else {
                               if (  $CntOK == $CntTT and $CntTT != 0 and ( "$Stato" == "S" or "$Stato" == "F" ) ){     
                                  $Esito='F';
                               } else {
                                 if (  $CntOK == 0 and $CntIZ == 0  and ( "$Stato" == "N" or "$Stato" == "F" ) ) {    
                                   $Esito='N';
                                 }else{
                                   if (  $CntIZ != 0  ) {
                                      $Esito='I';
                                   } else {
                                      $Esito='C';
                                   }
                                 }
                               }
                            }
                                                   
                           $Color="lightgray";  
                           
                           switch ( $Esito ){
                           case 'E' :
                            $Color='red';
                            break;
                           case 'I':
                            $Color="yellow";
                            break;
                           case 'F':
                            $Color='lightgreen';
                            break;
                           case 'C':
                            $Color="lightblue";
                            break;
                           } 
                        
                            $Assey=$Assey+40;
							$IfFluTrg=$Legame[5];
                           ?>
                           <rect id="RFlu<?php echo $IdFlusso; ?>" width="200" height="30" x="<?php echo $Assex; ?>" y="<?php echo $Assey; ?>" fill="<?php echo $Color; ?>" style="stroke:rgb(0,0,0);" />                                        
                           <text id="TFlu<?php echo $IdFlusso; ?>"  x="<?php echo $Assex+5; ?>" y="<?php echo $Assey+20; ?>" fill="black" font-family="Arial" font-size="15" style="text-align:center;"  ><?php echo $NomeFlusso; ?></text> 
                           <?php
                             if ( in_array($IdFlu, $ArrTest) ){
                                   ?><image xlink:href="./images/Lab.png" x="<?php echo $Assex+175; ?>" y="<?php echo $Assey+2; ?>" width="25px" /><?php
                             }
                        }
                 }
               }
             }
           }
        }

$cnt=0;
foreach( $ArrLegami as $Legame ){
     $IdFlu=$Legame[2];
     $Tipo=$Legame[4];
	 if ( "$Tipo" == "F"){
        $IfFluTrg=$Legame[5];
        ?><line id="Ln<?php echo $IdFlu.'_'.$IfFluTrg; ?>" stroke="black"  /><?php
	 }
}
?>
</svg> 
<script>

  function Posiziona(vDa,vA){
             var altezza = 15;
             var larghezza = 200;
             
             var SchemPos=$("#Schemasvg").position();
             var pos1 = $('#RFlu'+vDa).position();
             var pos2 = $('#RFlu'+vA).position();
			 
			 //alert('FATTO'+vDa+vA);
			 var posx1 = pos1.left - SchemPos.left;
			 var posy1 = pos1.top + altezza - SchemPos.top;
						 
			 var posx2 = pos2.left + larghezza - SchemPos.left;
			 var posy2 = pos2.top + altezza - SchemPos.top;
			 
             $('#Ln'+vDa+'_'+vA)
			 .attr('x1',posx1)
			 .attr('y1',posy1)
			 .attr('x2',posx2)
			 .attr('y2',posy2);

  }
<?php
foreach( $ArrLegami as $Legame ){
     $IdFlu=$Legame[2];
     $Tipo=$Legame[4];
     if ( "$Tipo" == "F"){
        $IfFluTrg=$Legame[5];
        ?>Posiziona(<?php echo $IdFlu; ?>,<?php echo $IfFluTrg; ?>);<?php
     } 
}
?>
</script>
</div>
