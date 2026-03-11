<?php
include '../GESTIONE/sicurezza.php';
if ( "$find" == "1" )  {
   ?>
     <style>
	 
	    .TabList{
			height: 50px;
			width: 100px;
			border: 1px solid black;
			text-align: center;
			padding: 5px;
			margin: 10px;
			float: left;
			background: yellow;
		}
		
		#TitTab{
			font-size: 13px;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			width: 100px;
		}
         			
		#FormTab{
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			width: 58%;
		}
	 </style>
	<div id="TitTab"><b>Elenco Tabelle:</b></div>
    <form id="FormTab" method="POST"  action="./ModTab.php" >
    <input name="TABELLA" id="TABELLA" type="text" class="nascondi" readonly >
    <?php

    $FindListTab="SELECT TABELLA FROM ".$FixAmb."_GESTIONEDB";
    $rt = mysql_query($FindListTab);
    while ($row = mysql_fetch_assoc($rt)) { 
        $vTabella=$row["TABELLA"]; 

        $FindCommTab="SELECT TABLE_COMMENT
        FROM INFORMATION_SCHEMA.TABLES
        WHERE 1=1
        AND TABLE_SCHEMA = '$DbName'
        AND TABLE_NAME = '$vTabella'";
        $Trt = mysql_query($FindCommTab);

        while ($Trow = mysql_fetch_assoc($Trt)) { 
          $OutNameTab=$Trow["TABLE_COMMENT"]; 
        }
        if ( "$OutNameTab" == ""  ){
            $OutNameTab=$vTabella;
        }		
        ?>
		<div class="TabList" id="Tab<?php echo $vTabella; ?>" ><?php echo $OutNameTab; ?></div>
        <script>
            $("#Tab<?php echo $vTabella; ?>").click(function(){
                  $("#TABELLA").val("<?php echo $vTabella; ?>");
                  $("#FormTab").submit();
            });
        </script>
        <?php
    }
    ?></form><?php
}
?>
