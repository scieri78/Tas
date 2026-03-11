<?php
/** 
 * @property reti_model $_model
 */
class reti extends helper
{
  
    function __construct()
    {
      $db_name = $_GET['sito']?$_GET['sito']:$_POST["db_name"]; 
       $this->_model =  new reti_model($db_name);
       $this->setDebug_attivo(1);
	   $this->include_css = '
	<link rel="stylesheet" href="./CSS/statoshell.css?p='.rand(1000,9999).'">	
    <link rel="stylesheet" href="./CSS/StatoReti.css?p='.rand(1000,9999).'">
    <link rel="stylesheet" href="./CSS/excel.css?p='.rand(1000,9999).'">
    <link rel="stylesheet" href="./view/reti/CSS/index.css?p='.rand(1000,9999).'">
	</script>';
    }
    // mvc handler request
    public function index()
    {
	$_view['include_css'] = $this->include_css; 
    include "./view/header.php";
	$_modelReti = $this->_model;
	$RetiRich=array("A41DA","A414N","A4928","A4918","A41FH");
	$DatiDataElab = $this->_model->getDataElab();
	$AutoRefresh11=$_POST['AutoRefresh11'];
	$ShowHidden=$_POST['ShowHidden'];
	$TopScrollShell1=$_POST['TopScrollShell1'];
	$LeftScrollShell1=$_POST['LeftScrollShell1'];

	$ListOpenNet=$_POST['ListOpenNet'];
	$ListOpenStep=$_POST['ListOpenStep'];
	$ListOpenId=$_POST['ListOpenId'];


	$Soglia=600;
	$BarraCaricamento = "rgb(21, 140, 240)";
	$BarraPeggio = "rgb(165, 108, 185)";
	$BarraMeglio = "rgb(104, 162, 111)";
	$BarraMeglio = "rgb(147 167 157)";
		
	$SetDataElab=$_POST['SET_DATA_ELAB'];
	if ( "$AutoRefresh11" != "" ){
	  $SetDataElab="";  
	}

	if ( $SetDataElab != "" ){
	   $DataElab=$SetDataElab;
	} else {

		
	foreach ($DatiDataElab as $row ) {
		  $DataElab=$row['DATAELAB'];
		}
	   $SetDataElab=$DataElab;
	}
	$EserMese=substr($DataElab,0,7);
	$MeseEs=substr($DataElab,5,7);


	if ( $MeseEs == 3 or $MeseEs == 6 or $MeseEs == 9 or $MeseEs == 12  ){
	  $ListMesiPrec="3,6,9,12";
	}else{
	  $ListMesiPrec="1,2,4,5,7,8,10,11";
	}
	$MIN_DATE="";
	$MAX_DATE="";

	$RETI=array();
	$DatiReti = $this->_model->getReti($DataElab);
	
foreach ($DatiReti as $row) {
   $RETE=$row['RETE'];
   $PASSO=$row['PASSO'];
   $STATUS=$row['STATUS'];
   $TIMEELAB=$row['TIMEELAB'];
   $START_TIME=$row['START_TIME'];
   $END_TIME=$row['END_TIME'];
   $ID_RUN_SH=$row['ID_RUN_SH'];
   $SEC_DIFF=$row['SEC_DIFF'];
   $PRWEND=$row['PRWEND'];   
   
   $ID_RUN_SH_OLD=$row['ID_RUN_SH_OLD'];
   $OLD_INIZIO=$row['OLD_INIZIO'];
   $OLD_FINE=$row['OLD_FINE'];
   $OLD_DIFF=$row['OLD_DIFF'];
   $OLD_DATE=$row['OLD_DATE'];
   $NOW_DATE=$row['NOW_DATE'];
   $OGGI=$row['OGGI'];
   $OLD_ESER_MESE=$row['OLD_ESER_MESE'];
   
   if ( "${'OLDNAME'.$RETE}" == "" ){
     ${'OLDNAME'.$RETE}=$row['OLDNAMERETE'];
   }
   $OLDNAMEPASSO=$row['OLDNAMEPASSO'];
   
   if ( ! in_array($RETE, $RETI) ){
        array_push($RETI,$RETE);        
        ${'New'.$RETE}=array();
        ${'Old'.$RETE}=array();
        ${'IdIn'.$RETE}=array();
        ${'IdNowIn'.$RETE}=array();
        ${'IdNowOldIn'.$RETE}=array();
        ${'IdOldIn'.$RETE}=array();                  
   }   

   if ( $ID_RUN_SH != "" ){
      array_push(${'IdIn'.$RETE},$ID_RUN_SH); 
      if ( $TIMEELAB == $EserMese ){
        array_push(${'IdNowIn'.$RETE},$ID_RUN_SH);
      } else {
        array_push(${'IdNowOldIn'.$RETE},$ID_RUN_SH);
      }     
   }
   
   if ( "$ID_RUN_SH_OLD" != "" ){
     array_push(${'IdOldIn'.$RETE},$ID_RUN_SH_OLD);
   }                                     
   
   if ( $MIN_DATE < $START_TIME ){
     $MIN_DATE=$START_TIME;
   }
   
   if ( $MAX_DATE < $END_TIME ){
     $MAX_DATE=$END_TIME;
   }
   
   if ( $TIMEELAB == $EserMese ){   
      array_push(${'New'.$RETE},array($PASSO,$STATUS,$START_TIME,$END_TIME,$ID_RUN_SH,$SEC_DIFF,$ID_RUN_SH_OLD,$OLD_INIZIO,$OLD_FINE,$OLD_DIFF,$OLD_DATE,$NOW_DATE,$OGGI,$OLD_ESER_MESE,$PRWEND,$OLDNAMEPASSO));
   }else{
      array_push(${'Old'.$RETE},array($PASSO,$STATUS,$START_TIME,$END_TIME,$ID_RUN_SH,$SEC_DIFF,$ID_RUN_SH_OLD,$OLD_INIZIO,$OLD_FINE,$OLD_DIFF,$OLD_DATE,$NOW_DATE,$OGGI,$OLD_ESER_MESE,$PRWEND,$OLDNAMEPASSO));
   }
}
	$DatiSelectElab = $this->_model->getSelectElab();
	
    include "./view/reti/index.php";
    include "./view/footer.php";
    }
    // page redirection
   
   
}



?>