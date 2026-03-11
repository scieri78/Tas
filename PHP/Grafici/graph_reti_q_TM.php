<?php

include '../../GESTIONE/connection.php';
$SogliaOra=3600;



$sql="select max(NLOAD) NLoad from STAT_LOG_STEP";
$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
while ($row = mysql_fetch_assoc($rt)) {
        $NLoad=$row["NLoad"];
}

$sql="select
   a.RETE RETE,
   (select min(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD ) MINDATE,
   (select max(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD ) MAXDATE,
   (select TIMESTAMPDIFF(SECOND,MINDATE,MAXDATE)from dual) DURATA
FROM
    STAT_LOG_STEP a
WHERE
   a.NLOAD = $NLoad
GROUP BY
  a.RETE
ORDER BY
  a.DATA";

$x_axis = array();
$y_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;
$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
while ($row = mysql_fetch_assoc($rt)) {
        $Rete=$row["RETE"];     
        $Durata=$row["DURATA"];

        if ( $Durata > $SogliaOra ) {
          if ( $MinDurata >= $Durata  ) {$MinDurata=$Durata;}
          if ( $MaxDurata <= $Durata ) {$MaxDurata=$Durata;}

		  $sNL="select max(NLOAD) NLOAD from STAT_LOG_STEP where RETE='$Rete' and NLOAD < $NLoad and SUBSTR(NLOAD,5,6) in ('03','06','09','12') and STATO='F'";
          $rNL = mysql_query($sNL);
          while ($rwNL = mysql_fetch_assoc($rNL)) {
            $VLoad=$rwNL["NLOAD"];
          }
          $x_axis[$i]="$Rete (QRT.$VLoad)";
          if ( $Durata > (  60 * 60 * 30 ) ) { $Durata=( 60 * 60 * 30 )+( 60 * 60 * 2); }         
          $y_axis[$i]=$Durata;
          $sqlpre="select
   (select min(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD) MINDATE,
   (select max(DATA) from STAT_LOG_STEP where RETE=a.RETE and NLOAD=a.NLOAD) MAXDATE,
   (select TIMESTAMPDIFF(SECOND,MINDATE,MAXDATE)from dual) DURATA
FROM
    STAT_LOG_STEP a
WHERE
   a.NLOAD = $VLoad and
   a.RETE = '$Rete'
";
          $rtpre = mysql_query($sqlpre);
          while ($rowpre = mysql_fetch_assoc($rtpre)) {
            $DurataPre=$rowpre["DURATA"];         
            if ( $MinDurata >= $DurataPre  ) {$MinDurata=$DurataPre;}
            if ( $MaxDurata <= $DurataPre ) {$MaxDurata=$DurataPre;} 
            if ( $DurataPre > (  60 * 60 * 30 ) ) { $DurataPre=( 60 * 60 * 30 )+( 60 * 60 * 2); }            
            $p_axis[$i]=$DurataPre;
          }
          $i++;
        }
}

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_line.php");
include ("../../jpgraph/src/jpgraph_bar.php");
include ("../../jpgraph/src/jpgraph_mgraph.php");
include ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
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

if ( $MinDurata < 0 ) { $MinDurata = 0;}

for($n=0; $n < $i; ++$n ) {
    $y_axis[$n] = $y_axis[$n]/$Div;
    $p_axis[$n] = $p_axis[$n]/$Div;
}

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=5; }
$Base=0;
$Tetto=ceil($MaxDurata/$num)*$num;
if ( $Scala=="Ore" and  $MaxDurata > 24 ) { $Tetto=30; } 
$graph->SetScale("textlin",$Base, $Tetto);
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
$gbplot->SetWidth(0.05);
$graph->Add($gbplot);

$p1->SetColor("white");
$p1->SetFillColor("#A9D0F5");
$p1->SetWidth(30);
$p2->SetColor("white");
$p2->SetFillColor("darkgray");
$p2->SetWidth(30);

$p1->SetColor("#A9D0F5");
$p1->SetCenter();
$p1->SetLegend('Ultimo Lancio Term.Corr.');
$p2->SetColor("darkgray");
$p2->SetCenter();
$p2->SetLegend('Anno Prec.');

$graph->legend->SetMarkAbsSize(8);
$graph->legend->SetAbsPos(1000,25,'center','bottom');
$graph->legend->SetShadow('black',0);

$graph->Stroke();


?>
