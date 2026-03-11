 <?php if(!$datishell->DA_RETITWS && !$datishell->IDSELEM){?>
<div id="XLastRun">

      <table class="ExcelTable" style="font-size:9px;">
   
      <tr><th>Shell</th><th>Tags</th><th>Mese Elab</th><th>Day</th><th>IdProcess</th><th>Mese Esame</th><th>Start</th><th>End</th><th>User</th></tr>
     <?php
	 $D_Row= 0;
      foreach ($datiLastRun as $row) {
        $D_MESEELAB=$row['MESEELAB'];
        $D_DAY=$row['ISDAY'];
        $D_NAME=$row['NAME'];
        $D_TAGS=$row['TAGS'];
        $D_ID_PROCESS=$row['ID_PROCESS'];
        $D_ESER_ESAME=$row['ESER_ESAME'];
        $D_MESE_ESAME=$row['MESE_ESAME'];
        $D_START_TIME=$row['START_TIME'];
        $D_END_TIME=$row['END_TIME'];
        $D_USERNAME=$row['USERNAME'];
        if ( "$D_ID_PROCESS" == "" ){ $D_ID_PROCESS="Batch Run";}
        ?><tr>
        <td><?php echo "$D_NAME" ?></td>
        <td><?php echo "$D_TAGS" ?></td>
        <td><?php echo "$D_MESEELAB" ?></td>
        <td><?php echo "$D_DAY" ?></td>
        <td><?php echo "$D_ID_PROCESS" ?></td>
        <td><?php echo "$D_ESER_ESAME $D_MESE_ESAME" ?></td>
        <td><?php echo "$D_START_TIME" ?></td>
        <td><?php echo "$D_END_TIME" ?></td>
        <td><?php echo "$D_USERNAME" ?></td>
        </tr><?php
        $D_Row=$D_Row+1;
        if ( $D_Row > 10 ){ break;}
      }
      ?>
      </table>
</div>
  <?php    }
      ?>