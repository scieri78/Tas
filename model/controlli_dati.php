<?php

class controlli_dati
{
    // table fields
   public static  $array_classe = ["C" => "COUNT", "S" => "SUM", "V" => "VARCNT" , "T" => "VARSUM", "M" => "MANUAL" , "R" => "REPORT", "P" => "PLSQL" ];//, "N" => "NECESSARIO"
   public static  $array_esito= ["OK"=>"OK","KO"=>"KO","DB"=>"DB ERROR","NV"=>"DA VALIDARE"];
   public static  $array_class_esito= ["OK"=>"esitoOK","KO"=>"esitoKO","DB"=>"esitoDB","NV"=>"esitoNV"];
  
    
   /**
    * getDurata
    *
    * @param  mixed $INIZIO_LANCIO
    * @param  mixed $FINE_LANCIO
    * @return void
    */
   public static function getDurata($INIZIO_LANCIO,$FINE_LANCIO){
	$DURATA="";
	if ($INIZIO_LANCIO && $FINE_LANCIO) {
		$date1 = new DateTime($INIZIO_LANCIO );
		$date2 = new DateTime( $FINE_LANCIO);

		// The diff-methods returns a new DateInterval-object...
		$diff = $date2->diff($date1);

		// Call the format method on the DateInterval-object
		$giorni = $diff->format('%a');
		$ore = $diff->format('%h');
		$minuti = $diff->format('%i');
		$secondi = $diff->format('%s');
		$DURATA.=($giorni)?$giorni.'G ':'';
		$DURATA.=($ore)?$ore.'H ':'';
		$DURATA.=($minuti)?$minuti.'m ':'';
		$DURATA.=($secondi)?$secondi.'s ':'';	
		$DURATA=($DURATA)?$DURATA:'0s';	
	}


return $DURATA;
   }
}


?>