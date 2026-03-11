<?php
$rootdir= $_SESSION['PSITO'];

require_once ($rootdir."jpgraph/src/jpgraph.php");
require_once ($rootdir."jpgraph/src/jpgraph_line.php");
require_once ($rootdir."jpgraph/src/jpgraph_mgraph.php");
require_once ($rootdir."jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
$graph->img->SetMargin(60,20,35,75);
$graph->img->SetAntiAliasing();

for($n=0; $n < $i; ++$n ) {
  $val=$y_axis[$n];
  $y_axis[$n]=$val/$Div; 
}

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=5; }
if ( ($MinDurata - 1) < 0 ) { $MinDurata=0; } else { $MinDurata=$MinDurata-1; }

$graph->SetScale("textlin",$MinDurata,$MaxDurata+1);
#$graph->SetScale("textlin");
$graph->yscale->ticks->Set($num);

$graph->SetShadow();
$graph->title->Set("Tempistiche Step: $Step $Tags in $Scala");
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
$p1->mark->SetFillColor("#A9D0F5");
$p1->mark->SetWidth(0);
$p1->SetColor("#A9D0F5");
$p1->SetCenter();
$p1->SetLegend('Durata');

$graph->legend->SetMarkAbsSize(8);
$graph->legend->SetAbsPos(1000,25,'center','bottom');
$graph->legend->SetShadow('black',0);

$graph->Stroke();

?>
