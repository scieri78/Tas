<?php
include '../GESTIONE/sicurezza.php';
$SelScript=$_GET["SelScript"];
if ( "$SelScript" == "" ) { $SelScript="Tutti"; }

$SelReti=$_GET["SelReti"];
if ( "$SelReti" == "" ) { $SelReti="Tutte"; }

?>
<div id="Statdiv"">
<form id="myform" method="get">

 <div class="Titolo" ><a style="color:orange;" href="javascript:toggleDiv('TempiSopraOra');">LANCI SUPERIORI L'ORA CON TEMPI REALI</a></CENTER></div>
 <div id="TempiSopraOra" style="display:none;" >
 
 <div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti.php" style="width:98%;" /><?php
}

?></div>
 <div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti_q.php" style="width:98%;" /><?php
}

?></div>
<div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 and mysql_num_rows($rt) > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti_UltOK.php" /><?php
}

?></div> 
</div> 



 <div class="Titolo" ><a style="color:red;" href="javascript:toggleDiv('TempiSopraOraTM');">LANCI SUPERIORI L'ORA CON TEMPI MORTI</a></CENTER></div>
 <div id="TempiSopraOraTM" style="display:none;" >
 
 <div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti_TM.php" style="width:98%;" /><?php
}

?></div>
 <div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti_q_TM.php" style="width:98%;" /><?php
}

?></div>
<div class="GrafReti">
  <?php
  $sqlCONTA="select count(*) CONTA
     from (
       select
         a.RETE RETE,
         ( SELECT  TIMESTAMPDIFF(HOUR,
           (select min(Mi.DATA) from STAT_LOG_STEP Mi WHERE Mi.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Mi.Rete=a.Rete ) ,
           (select max(Ma.DATA) from STAT_LOG_STEP Ma WHERE Ma.NLOAD = (select max(NLOAD) from STAT_LOG_STEP ) and Ma.Rete=a.Rete )
         ) from dual ) DURATA
       FROM
         STAT_LOG_STEP a
       WHERE
         a.NLOAD = (select max(NLOAD) from STAT_LOG_STEP )
       GROUP BY
         a.RETE
    ) c
    where
      c.DURATA > 1";
$rt = mysql_query($sqlCONTA);
while ($row = mysql_fetch_assoc($rt)) { $CONTA=$row["CONTA"]; }
if ( $CONTA > 0 and mysql_num_rows($rt) > 0 ) {
  ?><img style="width:100%;" src="../PHP/Grafici/graph_reti_UltOK_TM.php" /><?php
}

?></div> 
</div> 






<CENTER><a href="javascript:toggleDiv('ConfrontoReti');">
 <div class="Titolo" style="color:blue;" >ANDAMENTO MENSILE RETI</div>
</a></CENTER>
<div id="ConfrontoReti" <?php if ( "$SelReti" == "Tutte") { ?> style="display:none;" <?php } ?> >
   <?php
   $st="SELECT S.RETE FROM STAT_STEP S group by S.RETE order by S.RETE";
   $rt = mysql_query($st);
   ?>
   <SELECT name="SelReti">
     <option value="Tutte"  <?php if ( "Tutte" == "$SelReti" ) { ?> selected <?php } ?> />Tutte<?php
     while ($rd = mysql_fetch_assoc($rt)) {
       $Rete=$rd["RETE"];
       ?><option value="<?php echo $Rete; ?>" <?php if ( "$Rete" == "$SelReti" ) { ?> selected <?php } ?> /><?php echo $Rete; ?><?php
     }?>
   </SELECT>
   <input type="submit" value="Refresh"><BR>
<CENTER><a href="javascript:toggleDiv('ConfrontoReti');">
  <table class="table">
  <?php
  if ( "$SelReti" == "Tutte" ) {
    $st="SELECT S.RETE,max(LS.DATA) MAX FROM STAT_STEP S,STAT_LOG_STEP LS WHERE S.RETE=LS.RETE group by S.RETE order by MAX desc";
    $rt = mysql_query($st);
    while ($rd = mysql_fetch_assoc($rt)) {
      $Rete=$rd["RETE"];
      ?><TR><TD><img style="width:100%;" src="../PHP/Grafici/genera_graph_rete.php?RETE=<?php echo $Rete; ?>&LARG=1200&ALTE=300" style="width:98%;"/></TD></TR><?php
    }
  } else {
    ?><TR><TD><img style="width:100%;" src="../PHP/Grafici/genera_graph_rete.php?RETE=<?php echo $SelReti; ?>&LARG=1200&ALTE=300" style="width:98%;" /></TD></TR><?php
  }?>
 </table>
</a></CENTER>
</div>

<CENTER><a href="javascript:toggleDiv('ConfrontoScript');">
 <div class="Titolo" style="color:green;" >ANDAMENTO MENSILE SCRIPT</div>
</a></CENTER>
<div id="ConfrontoScript" <?php if ( "$SelScript" == "Tutti" and "$SelReti" == "Tutte" ) { ?> style="display:none;" <?php } ?> >
   <?php
   if ( "$SelReti" <> "Tutte" ) {
     $st="SELECT S.RETE,S.SCRIPT FROM STAT_STEP S WHERE S.RETE='$SelReti' group by S.RETE,S.SCRIPT order by S.RETE,S.SCRIPT";
   } else {
     $st="SELECT S.RETE,S.SCRIPT FROM STAT_STEP S group by S.RETE,S.SCRIPT order by S.RETE,S.SCRIPT";
   }

   $rt = mysql_query($st); ?>
   <SELECT name="SelScript">
     <option value="Tutti" selected />Tutti <?php if ( "$SelReti" <> "Tutte") {echo $SelReti;}
     while ($rd = mysql_fetch_assoc($rt)) {
       $Rete=$rd["RETE"];
       $Script=$rd["SCRIPT"];
       ?><option value="<?php echo "$Script"; ?>"  <?php if ( "$Script" == "$SelScript" ) { ?> selected <?php } ?> /><?php echo "$Script"; ?><?php
     }?>
   </SELECT>
   <input type="submit" value="Refresh"><BR>
<CENTER><a href="javascript:toggleDiv('ConfrontoScript');">
  <table class="table">
  <?php
  if ( "$SelReti" <> "Tutte" ) {
    $st1="SELECT S.RETE,max(LS.DATA) MAX FROM STAT_STEP S,STAT_LOG_STEP LS WHERE S.RETE=LS.RETE and S.SCRIPT=LS.SCRIPT and S.SCRIPT='$SelScript' and S.RETE='$SelReti' group by S.RETE order by S.RETE,S.SCRIPT";
  } else {
    $st1="SELECT S.RETE,max(LS.DATA) MAX FROM STAT_STEP S,STAT_LOG_STEP LS WHERE S.RETE=LS.RETE and S.SCRIPT=LS.SCRIPT and S.SCRIPT='$SelScript' group by S.RETE order by MAX desc";
  }
  $SelScriptBKP=$SelScript;
  $SelScript="Tutti";
  $rt1 = mysql_query($st1);
  while ($rd1 = mysql_fetch_assoc($rt1)) {
   $SelReti=$rd1["RETE"];
   $SelScript=$SelScriptBKP;
  }

  if (  "$SelScript" == "Tutti" ) {
   if ( "$SelReti" <> "Tutte" ) {
     $st="SELECT S.RETE,S.SCRIPT,max(LS.DATA) MAX FROM STAT_STEP S,STAT_LOG_STEP LS WHERE S.RETE=LS.RETE and S.RETE='$SelReti' group by S.RETE,S.SCRIPT order by S.RETE,S.SCRIPT";
   } else {
     $st="SELECT S.RETE,S.SCRIPT,max(LS.DATA) MAX FROM STAT_STEP S,STAT_LOG_STEP LS WHERE S.RETE=LS.RETE and S.SCRIPT=LS.SCRIPT group by S.RETE,S.SCRIPT order by MAX desc";
   }

    $rt = mysql_query($st);
    while ($rd = mysql_fetch_assoc($rt)) {
      $Rete=$rd["RETE"];
      $Script=$rd["SCRIPT"];
      ?><TR><TD><img style="width:100%;" src="../PHP/Grafici/genera_graph_script.php?RETE=<?php echo $Rete; ?>&SCRIPT=<?php echo $Script; ?>&LARG=1200&ALTE=300"  style="width:98%;"/></TD></TR><?php
    }
  } else {
    ?><TR><TD><img style="width:100%;" src="../PHP/Grafici/genera_graph_script.php?RETE=<?php echo $SelReti; ?>&SCRIPT=<?php echo $SelScript; ?>&LARG=1200&ALTE=300"  style="width:98%;"/></TD></TR><?php
  } ?>
  </table>
 </a></CENTER>
</div>

<BR><BR>   

</form>
</div>

