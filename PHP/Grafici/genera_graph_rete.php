<?php

include '../../GESTIONE/connection.php';
include '../../PHP/FunzioneTempi.php';

$Rete=$_GET["RETE"];
$Mesi=$_GET["MESI"];

if ( "$Mesi" == "" ){
  $Mesi=24;
}

$sql="select
    NLOAD,
    STATO,
   ( SELECT  TIMESTAMPDIFF(SECOND,
         (select min(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD and STATO='I'),
         (select max(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD and STATO<>'I')
   ) from dual ) DURATA
FROM
    STAT_LOG_STEP a
WHERE
  a.RETE = '$Rete'
  AND a.NLOAD >= DATE_FORMAT(DATE_SUB(now(),INTERVAL $Mesi MONTH),'%Y%m')
  and STATO <> 'I'
GROUP BY
  a.NLOAD
ORDER BY NLOAD";

$x_axis = array();
$y_axis = array();
$z_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;
$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
$MediaMaxDurataTM=0;
$MediaMaxDurataTR=0;
$numvolte=0;
while ($row = mysql_fetch_assoc($rt)) {
        $Nload=$row["NLOAD"];
        $x_axis[$i]=$Nload;
        $STATO=$row["STATO"];
        $y_axis[$i]="-";
        $z_axis[$i]="-";
		$numvolte=$numvolte+1;
        if ( $STATO == "F" ) {
          $Durata=$row["DURATA"];
          //if ( $MinDurata > $Durata  ) {$MinDurata=$Durata;}
          //if ( $MaxDurata < $Durata ) {$MaxDurata=$Durata;}
          $MediaMaxDurataTM=($MediaMaxDurataTM+$Durata)/$numvolte;		  
          $y_axis[$i]=$Durata;
          $DurataStep=0;
          $Riga="Fine";
          $Inizio="";
          $Fine="";           
          $DurataStep=TempoRealeRete($Rete,$Nload);
          $z_axis[$i]=$DurataStep;
          if ( $MinDurata >= $DurataStep  ) {$MinDurata=$DurataStep;}
          if ( $MaxDurata <= $DurataStep  ) {$MaxDurata=$DurataStep;}
        }
        $i++;
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

$aggiunta=0;
if ( ( $MediaMaxDurataTM - $MaxDurata ) > 10*$Div ) { $aggiunta=10; }
$MaxDurata=(($MaxDurata+$Div)/$Div )+ $aggiunta ;
$MinDurata=(($MinDurata-$Div)/$Div )- $aggiunta;
	 
if ( $MinDurata < 0 ) { $MinDurata = 0;}

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_line.php");
include ("../../jpgraph/src/jpgraph_mgraph.php");
include ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
$graph->img->SetMargin(60,20,35,75);
$graph->img->SetAntiAliasing();

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=5; }

for($n=0; $n < $i; ++$n ) {
  if ($y_axis[$n] <> "-" ) { 
      $y_axis[$n] = $y_axis[$n]/$Div; 
      if ( $y_axis[$n] < $MinDurata ){
          $y_axis[$n]=floor($MinDurata/$num)*$num-1;
      }
      if ( $y_axis[$n] > $MaxDurata ){
          $y_axis[$n]=ceil($MaxDurata/$num)*$num+1;
      }      
  }
  if ($z_axis[$n] <> "-" ) { 
      $z_axis[$n] = $z_axis[$n]/$Div; 
      if ( $z_axis[$n] < $MinDurata ){
          $z_axis[$n]=floor($MinDurata/$num)*$num-1;
      }
      if ( $z_axis[$n] > $MaxDurata ){
          $z_axis[$n]=ceil($MaxDurata/$num)*$num+1;
      }    
  }
}

$graph->SetScale("textlin",floor($MinDurata/$num)*$num,ceil($MaxDurata/$num)*$num);
$graph->yscale->ticks->Set($num);

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
$p1->SetLegend('Con Tempi Morti');

$p2 = new LinePlot($z_axis);
$graph->Add($p2);
$p2->mark->SetType(MARK_DIAMOND);
$p2->mark->SetFillColor("#A5DF00");
$p2->mark->SetWidth(3);
$p2->SetColor("#A5DF00");
$p2->SetCenter();
$p2->SetLegend('Senza Tempi Morti');

$graph->legend->SetMarkAbsSize(8);
$graph->legend->SetAbsPos(1000,25,'center','bottom');
$graph->legend->SetShadow('black',0);




$graph->Stroke();

?>
