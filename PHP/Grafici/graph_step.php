<?php
session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();
include '../../GESTIONE/connection.php';

$Step=@$_REQUEST["STEP"];
$Tags=@$_REQUEST["TAGS"];
$Mesi=@$_REQUEST["MESI"];
$IdSh=@$_REQUEST["IDSH"];

if ( "$Mesi" == "" ){
  $Mesi=24;
}

$Andtags="";
if ( "$Tags" != "" ){	 
	$Andtags=" AND TAGS = '$Tags' ";
}

$AndIdSh="";
if ( "$IdSh" != "" ){	 
	$AndIdSh=" AND ID_SH = '$IdSh' ";
}
 $aSITO =$_GET['sito']?$_GET['sito']:$_COOKIE[$_COOKIE['tab_id']];
$_db = "TASPCUSR";
//$db_name=$_SESSION['aSITO'];
$db_name=$aSITO;
if($db_name=="work")
{
$_db = 'TASPCWRK';
}


$sql="SELECT
ESER_MESE,
timestampdiff(2,
	(SELECT END_TIME   FROM $_db.WORK_CORE.CORE_SH WHERE ID_RUN_SH = b.ID_RUN_SH )
     -
	(SELECT START_TIME FROM $_db.WORK_CORE.CORE_SH WHERE ID_RUN_SH = b.ID_RUN_SH )
) DURATA
FROM (
	SELECT 
		ID_SH,
		ESER_MESE,
		MAX(ID_RUN_SH) ID_RUN_SH
	FROM
		$_db.WORK_CORE.CORE_SH
	WHERE 1=1
	AND NAME  = '$Step' 
	$Andtags
	$AndIdSh
	AND ESER_MESE IS NOT NULL
	AND END_TIME IS NOT NULL
	AND STATUS IN ('F','W')
	GROUP BY ID_SH,ESER_MESE
	ORDER BY ID_SH,ESER_MESE
) b
";

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

if($i==0)
{
  $MaxDurata = $MinDurata = 0;
  $x_axis = [0];
  $y_axis = [0];
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
$MinDurata=$MinDurata/$Div;
$MaxDurata=$MaxDurata/$Div;

$rootdir= $_SESSION['PSITO'];
$rootdir=str_replace("TASUSR","TASMVC",$rootdir); 
$rootdir=str_replace("TASWRK","TASMVC",$rootdir); 
/*
echo "<pre>";
echo "rootdir:".$rootdir."<br>";
echo "MinDurata:".$MinDurata."<br>";
echo "MaxDurata:".$MaxDurata."<br>";
echo "</pre>";
die();*/

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
