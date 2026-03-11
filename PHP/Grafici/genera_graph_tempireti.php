<?php

include '../../GESTIONE/connection.php';
include '../../PHP/FunzioneTempi.php';

$Mesi=48;
$Rete=$_GET["RETE"];


$sql="select
    NLOAD
FROM
    STAT_LOG_STEP a
WHERE
  a.NLOAD >= DATE_FORMAT(DATE_SUB(now(),INTERVAL 13 MONTH),'%Y%m')
  and a.NLOAD > 201502
GROUP BY
  a.NLOAD
ORDER BY NLOAD";

$x_axis = array();
$z_axis = array();
$i=0;
$MinDurata=0;
$MaxDurata=0;



$rt = mysql_query($sql);
if ( mysql_num_rows($rt) == 0 ) { exit; }
while ($row = mysql_fetch_assoc($rt)) {
        $Nload=$row["NLOAD"];
        $DurataStep=0;      
        $sqlTrueTime="select SCRIPT from STAT_LOG_STEP where NLOAD = $Nload and STATO= 'I' order DATA desc";
        $rtTrueTime = mysql_query($sqlTrueTime);
        while ($rwTrueTime = mysql_fetch_assoc($rtTrueTime)) { 
          $Script=$rwTrueTime["SCRIPT"]; 
        }
       $Exit=false;
        while ( $Exit==false ) {
        
            $DataInizioScript=null;
            $sqlDataInizioScript="select DATA from STAT_LOG_STEP where SCRIPT='$Script' and NLOAD=$Nload and STATO='I'";
            $rtDataInizioScript = mysql_query($sqlDataInizioScript);
            while ($rwDataInizioScript = mysql_fetch_assoc($rtDataInizioScript)) {
              $DataInizioScript=$rwDataInizioScript["DATA"];
            }   
            
            $DataFineScript=$DataInizioScript;      
            $sqlDataFineScript="select DATA,TIME_TO_SEC(DATA) SEC from STAT_LOG_STEP where STATO<>'I' and NLOAD=$Nload and SCRIPT='$Script'";
            $rtDataFineScript = mysql_query($sqlDataFineScript);
            while ($rwDataFineScript = mysql_fetch_assoc($rtDataFineScript)) {
                $DataFineScript=$rwDataFineScript["DATA"];
                $DataFineScriptSec=$rwDataFineScript["SEC"];
            }                 
            
            $sqlElabInStep="select SCRIPT from STAT_LOG_STEP where STATO='I' and NLOAD=$Nload and SCRIPT<>'$Script' and DATA >= '$DataInizioScript' and DATA <= '$DataFineScript'";
            $rtElabInStep = mysql_query($sqlElabInStep);
            $Script=null;
            while ($rwElabInStep = mysql_fetch_assoc($rtElabInStep)) {
                $ElabInStep=$rwElabInStep["SCRIPT"];
                $sqlMaxDate="select DATA,TIME_TO_SEC(DATA) SEC from STAT_LOG_STEP where STATO<>'I' and NLOAD=$Nload and SCRIPT='$ElabInStep'";
                $rtMaxDate = mysql_query($sqlMaxDate);
                while ($rwMaxDate = mysql_fetch_assoc($rtMaxDate)) {
                  $DataNext=$rwMaxDate["DATE"];
                  $DataNextSec=$rwMaxDate["SEC"];
                  if ( $DataFineScriptSec < $DataNextSec ) { 
                    $DataFineScript=$DataNext; 
                    $Script=$ElabInStep;
                  }
                }
            }             

            if ( $Script == null) {
              $sqlNextStep="select SCRIPT from STAT_LOG_STEP where STATO='I' and NLOAD=$Nload and DATA > '$DataFineScript' order by DATA desc";
              $rtNextStep = mysql_query($sqlNextStep);
              while ($rwNextStep = mysql_fetch_assoc($rtNextStep)) {
                $Script=$rwNextStep["SCRIPT"];
              }
            }
            
            if ( $Script==null ){
              $Exit=true;
            } else {          
              $sqlTTStep="select ( SELECT  TIMESTAMPDIFF(SECOND,'$DataInizioScript','$DataFineScript') ) DURATA";
              $rtTTStep = mysql_query($sqlTTStep);
              while ($rwTTStep = mysql_fetch_assoc($rtTTStep)) {
                $DurStep=$rwTTStep["DURATA"];
                $DurataStep=$DurataStep+$DurStep;
              }     
            }             
        }
        $NumReti=0;
        $sqlNumReti="select count(*) CONTA from STAT_LOG_STEP where STATO='I' and NLOAD=$Nload";
        $rtNumReti = mysql_query($sqlNumReti);
        while ($rwNumReti = mysql_fetch_assoc($rtNumReti)) {
            $NumReti=$rwNumReti["CONTA"];       
        }
        $x_axis[$i]="$Nload - $NumReti Step";       
        $z_axis[$i]=$DurataStep;
        if ( $MinDurata > $DurataStep or $MinDurata == 0 ) {$MinDurata=$DurataStep;}
        if ( $MaxDurata < $DurataStep ) {$MaxDurata=$DurataStep;}       
        $i++;
}

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

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_line.php");
include ("../../jpgraph/src/jpgraph_mgraph.php");
include ("../../jpgraph/src/jpgraph_error.php");

$graph = new Graph(1200,300);
$graph->img->SetMargin(60,20,35,75);
$graph->img->SetAntiAliasing();

for($n=0; $n < $i; ++$n ) {
    $z_axis[$n] = $z_axis[$n]/$Div;
}

$num=1;
if ( ($MaxDurata - $MinDurata) > 10 ) { $num=3; }
$graph->SetScale("textlin",floor($MinDurata/$num)*$num,ceil($MaxDurata/$num)*$num);
$graph->yscale->ticks->Set($num);

$graph->SetShadow();
$graph->title->Set("Tempistiche Elaborazioni in $Scala");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Use 20% "grace" to get slightly larger scale then min/max of
// data
$graph->yscale->SetGrace(0);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x_axis);
$graph->xaxis->SetLabelAngle(25);

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
