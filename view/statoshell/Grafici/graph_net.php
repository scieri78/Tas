<?php

include '../../GESTIONE/connection.php';

$Rete=$_GET["RETE"];
$Mesi=$_GET["MESI"];

if ( "$Mesi" == "" ){
  $Mesi=24;
}

$sql="SELECT DISTINCT
    ESER_MESE,
    timestampdiff(2,
    (
        SELECT
            MAX(END_TIME)
        FROM
            WORK_CORE.CORE_SH b
        WHERE
            NAME LIKE '$Rete%'
        AND ESER_MESE=a.ESER_MESE
		AND START_TIME = ( SELECT MAX(START_TIME) FROM WORK_CORE.CORE_SH WHERE ESER_MESE=b.ESER_MESE AND ID_SH = b.ID_SH AND STATUS IN ('F','W') ) 
        AND STATUS IN ('F','W'))
    -
    (
        SELECT
            MIN(START_TIME)
        FROM
            WORK_CORE.CORE_SH c
        WHERE
            NAME LIKE '$Rete%'
        AND ESER_MESE=a.ESER_MESE
		AND STATUS IN ('F','W')) ) DURATA
FROM
    WORK_CORE.CORE_SH a
WHERE
    NAME LIKE '$Rete%'
AND START_TIME >= ( CURRENT_TIMESTAMP - $Mesi MONTH ) 
AND STATUS IN ('F','W')
ORDER BY  ESER_MESE";

//echo $sql.'<BR><BR>';

$x_axis = array();
$y_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;

$stmt=db2_prepare($conn, $sql);
$result=db2_execute($stmt);

if ( ! $result ){
   echo "ERROR DB2";
}
while ($row = db2_fetch_assoc($stmt)) {
        $ESER_MESE=$row["ESER_MESE"];
        $Durata=$row["DURATA"];
        array_push($x_axis,$ESER_MESE);
        array_push($y_axis,$Durata);
        if ( $MinDurata >= $Durata  ) {$MinDurata=$Durata;}
        if ( $MaxDurata <= $Durata  ) {$MaxDurata=$Durata;}
        $i=$i+1;
}

if ($MaxDurata > 60){
  if ($MaxDurata <= 3600) {
     $Scala="Minuti";
     $Div=60;
  } else {
     $Scala="Ore";
     $Div=3600;
  }
} else {
  $Scala="Secondi";
  $Div=1;
}
     
if ( $MinDurata < 0 ) { $MinDurata = 0;}

require_once ("../../jpgraph/src/jpgraph.php");
require_once ("../../jpgraph/src/jpgraph_line.php");
require_once ("../../jpgraph/src/jpgraph_mgraph.php");
require_once ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
$graph->img->SetMargin(60,20,35,75);
$graph->img->SetAntiAliasing();

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=5; }

for($n=0; $n < $i; ++$n ) {
  $y_axis[$n] = $y_axis[$n]/$Div; 
  if ( $y_axis[$n] < $MinDurata ){
	  $y_axis[$n]=floor($MinDurata/$num)*$num-1;
  }
  if ( $y_axis[$n] > $MaxDurata ){
	  $y_axis[$n]=ceil($MaxDurata/$num)*$num+1;
  }      
}


//$graph->SetScale("textlin",floor($MinDurata/$num)*$num,ceil($MaxDurata/$num)*$num);
$graph->SetScale("textlin");
//$graph->yscale->ticks->Set($num);

$graph->SetShadow();
$graph->title->Set("Tempistiche Rete: $Rete in $Scala");
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
