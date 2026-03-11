<?php

include '../../GESTIONE/connection.php';
include '../../PHP/FunzioneTempi.php';

$Rete=$_GET["RETE"];
$Script=$_GET["SCRIPT"];
$Mesi=$_GET["MESI"];

if ( "$Mesi" == "" ){
  $Mesi=24;
}

$sql="select
   NLOAD,
   STATO,
   ( SELECT  TIMESTAMPDIFF(SECOND,
         (select min(DATA) from STAT_LOG_STEP where RETE=a.RETE AND SCRIPT=a.SCRIPT and NLOAD=a.NLOAD ),
         (select max(DATA) from STAT_LOG_STEP where RETE=a.RETE AND SCRIPT=a.SCRIPT and NLOAD=a.NLOAD )
   ) from dual ) DURATA
FROM
    STAT_LOG_STEP a
WHERE
  a.RETE = '$Rete'
  AND SCRIPT = '$Script'
  AND NLOAD >= DATE_FORMAT(DATE_SUB(now(),INTERVAL $Mesi MONTH),'%Y%m')
  and STATO <> 'I'
GROUP BY
  a.NLOAD
ORDER BY NLOAD";

$x_axis = array();
$y_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;
$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
while ($row = mysql_fetch_assoc($rt)) {
        $Mese=$row["NLOAD"];
        $x_axis[$i]=$Mese;
        $STATO=$row["STATO"];
        $y_axis[$i]="-";
        if ( $STATO == "F") {
          $Durata=$row["DURATA"];
          $y_axis[$i]=$Durata;
          if ( $MinDurata > $Durata  ) {$MinDurata=$Durata;}
          if ( $MaxDurata < $Durata ) {$MaxDurata=$Durata;}
        }
        $i++;
}

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_line.php");
include ("../../jpgraph/src/jpgraph_mgraph.php");
include ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
$graph->img->SetMargin(40,40,40,80);
$graph->img->SetAntiAliasing();

if ($MaxDurata > 60){
  if ($MaxDurata <= 3600) {
     $Scala="Minuti";
     $Div=60;
     $MaxDurata=($MaxDurata+60)/$Div;
     $MinDurata=($MinDurata-60)/$Div;
  } else {
     $Scala="Ore";
     $Div=3600;
     $MaxDurata=($MaxDurata+3600)/$Div;
     $MinDurata=($MinDurata-3600)/$Div;
  }
} else {
  $Scala="Secondi";
  $Div=1;
  $MaxDurata=$MaxDurata+1;
  $MinDurata=$MinDurata-1;
}

if ( $MinDurata < 0 ) {$MinDurata = 0;}

$num=1;
if ( ($MaxDurata - ($MinDurata)) > 10 ) { $num=5; }
for($n=0; $n < $i; ++$n ) {
  if ($y_axis[$n] <> "-" ) { 
      $y_axis[$n] = $y_axis[$n]/$Div; 
      if ( $y_axis[$n] < $MinDurata ){
          $y_axis[$n]=floor($MinDurata/$num)*$num-($num+1);
      }
      if ( $y_axis[$n] > $MaxDurata ){
          $y_axis[$n]=ceil($MaxDurata/$num)*$num+($num+1);
      }      
  }
}

$graph->SetScale("textlin",floor($MinDurata/$num)*$num,ceil($MaxDurata/$num)*$num);
$graph->yscale->ticks->Set($num);

$graph->SetShadow();
$sql="select Eseguibile FROM STAT_TREE WHERE Rete = '$Rete' AND Step = '$Script'";
$rt = mysql_query($sql);
while ($row = mysql_fetch_assoc($rt)) {$Eseguibile=$row["Eseguibile"];}
$graph->title->Set("Tempistiche $Rete - $Script in $Scala - Shell: $Eseguibile");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Use 20% "grace" to get slightly larger scale then min/max of
// data
$graph->yscale->SetGrace(0);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x_axis);
$graph->xaxis->SetLabelAngle(25);

$p1 = new LinePlot($y_axis);
$graph->Add($p1);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("#A5DF00");
$p1->mark->SetWidth(2);
$p1->SetColor("#A5DF00");
$p1->SetCenter();

$graph->Stroke();

?>
