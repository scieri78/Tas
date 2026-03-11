<?php

include '../../GESTIONE/connection.php';
include '../../PHP/FunzioneTempi.php';

$sql="select
  RETE,SCRIPT,TO_SECONDS(min(DATA)) Min, TO_SECONDS(max(DATA)) Max
FROM
    STAT_LOG_STEP
WHERE
  NLOAD >= DATE_FORMAT(DATE_SUB(now(),INTERVAL 1 MONTH),'%Y%m')
GROUP BY
  RETE,SCRIPT
ORDER BY
  DATA";

$x_axis = array();
$y_axis = array();
$p_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;
$valstart = 0;
$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
while ($row = mysql_fetch_assoc($rt)) {
        $Rete=$row["RETE"];
        $Script=$row["SCRIPT"];
        $Inizio=$row["Min"];
        $Fine=$row["Max"];
        
        if ($valstart == 0) {$valstart = $inizio; }
        $Inizio=$Inizio-$valstart;
        $Fine=$Fine-$valstart;
        
        $x_axis[$i]=$Rete."-".$Script;
        $y_axis[$i]=$Inizio;
        $p_axis[$i]=$Fine;
        
        //echo $Rete."-".$Script."    ".$Inizio."-".$Fine."<BR>";
        if ( $MinDurata > $Inizio or $MinDurata == 0 ) {$MinDurata=$Inizio;}
        if ( $MaxDurata < $Fine ) {$MaxDurata=$Fine;}
        $i++;
}

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_line.php");
include ("../../jpgraph/src/jpgraph_bar.php");
include ("../../jpgraph/src/jpgraph_mgraph.php");
include ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(6000,2000);
$graph->img->SetMargin(60,20,35,75);
$graph->img->SetAntiAliasing();

if ($MaxDurata > 60){
  if ($MaxDurata <= 3600) {
     $Scala="Minuti";
     $Div=60;
     $MaxDurata=($MaxDurata+60)/$Div;
     $MinDurata=($MinDurata-60)/$Div;
  } else {
     $Scala="Ore";
     $Div=1800;
     $MaxDurata=($MaxDurata+1800)/$Div;
     $MinDurata=($MinDurata-1800)/$Div;
  }
} else {
  $Scala="Secondi";
  $Div=1;
  $MaxDurata=$MaxDurata+1;
  $MinDurata=$MinDurata-1;
}

if ( $MinDurata < 0 ) { $MinDurata = 0;}

for($n=0; $n < $i; ++$n ) {
    $y_axis[$n] = $y_axis[$n]/$Div;
    $p_axis[$n] = $p_axis[$n]/$Div;
}

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=5; }
$graph->SetScale("textlin",floor($MinDurata/$num)*$num,ceil($MaxDurata/$num)*$num);
$graph->yscale->ticks->Set($num);

$graph->SetShadow();
$graph->title->Set("Tempistiche Reti eccedenti l'ora in Ore");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Use 20% "grace" to get slightly larger scale then min/max of
// data
$graph->yscale->SetGrace(0);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x_axis);
$graph->xaxis->SetLabelAngle(25);
$graph->SetBox(false);

$p1 = new BarPlot($y_axis);
$p2 = new BarPlot($p_axis);

$gbplot = new GroupBarPlot(array($p2,$p1));
$gbplot->SetWidth(0.00);
$graph->Add($gbplot);

$p1->SetColor("white");
$p1->SetFillColor("white");
$p1->SetWidth(30);
$p2->SetColor("white");
$p2->SetFillColor("red");
$p2->SetWidth(30);

$p1->SetColor("#A9D0F5");
$p1->SetCenter();
$p1->SetLegend('Inizio');
$p2->SetColor("#D8D8D8");
$p2->SetCenter();
$p2->SetLegend('Fine');

$graph->legend->SetMarkAbsSize(8);
$graph->legend->SetAbsPos(1000,25,'center','bottom');
$graph->legend->SetShadow('black',0);

$graph->Stroke();


?>
