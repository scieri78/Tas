<BR>
<?php 

    $ArrAZ=array();
    $ArrAZV=array('LoB_4','LoB_5','LoB_11');
    $ArrCRD=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
    $ArrGNL=array('LoB_4','LoB_5','LoB_6','LoB_7','LoB_11','LoB_12','LoB_14','LoB_15');
   
	$Arr_CodComp=array('AZ','AZV','CRD','GNL');
    $Arr_Lob=array(
	'Cont'
   ,'LoB1'
   ,'LoB2'
   ,'LoB3'
   ,'LoB4'
   ,'LoB5'
   ,'LoB6'
   ,'LoB7'
   ,'LoB10'
   ,'LoB11'
   ,'LoB12'                                                                                                                                                                                                  
   ,'LoB13'
   ,'LoB14'
   ,'LoB15'
   ,'LoB16'
   ,'LoB17'
   ,'LoB18'
   ,'LoB89'
	); 
?>
<TABLE class="ExcelTable" style="width: 800px; margin: auto;">
<tr>
	<th style="text-align:center;width:50px;" >Comp</th>
	<?php   
	foreach( $Arr_Lob as $Lob ){    
	  ?><th style="text-align:center;"><?php echo $Lob; ?></th><?php
	}
	?>
</tr>
<?php   
foreach( $Arr_CodComp as $CodComp ){
   ?>
   <tr id="Riga<?php echo $CodComp; ?>" >
   <th style="text-align:center;"><?php echo $CodComp; ?></th>
   <?php
   foreach( $Arr_Lob as $Lob ){
	   ?><td><input type="checkbox" name="<?php echo $CodComp.'_'.$Lob; ?>" ></td><?php
   }
   ?>
   </tr>
   <?php
}
?>
</TABLE>

   <BR><BR>
   
 <table class="ExcelTable">
 <tr>
   <th>ConsUnit</th>
   <th>Line Of Business</th>
   <th>File Princ</th>
   <th>File Zonta</th>
   <th>Load Claims</th>
   <th>Stato</th>
 </tr>
 </table> 
 
<?php
