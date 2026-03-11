<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note"> 
	<p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>LossRess | RIASS FILE [Last <?php echo $SelM; ?> Month]</p> 
	
  <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>		
						
</aside>

<?php

function alert($msg)
{
  echo "<script type='text/javascript'>alert('$msg');</script>";
}

$SelM = 12;
?>
<form id="FormSelectRete" method="POST">
 


    <table class="display dataTable">
      <caption>STRATI CONSOLIDATI SULLA RIASS</caption>
      <thead class="headerStyle">
        <tr>
          <th>ESER_ESAME</th>
          <th>MESE_ESAME</th>
          <th>TAGS</th>
          <th>ID_FILE</th>
          <th>INPUT_FILE</th>
          <th>START_TIME</th>
          <th>END_TIME</th>
          <th>TIME</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $OldPer = "1";
        foreach ($datiRiassFile as $row) {
          $ESER_ESAME = $row['ESER_ESAME'];
          $MESE_ESAME = $row['MESE_ESAME'];
          $TAGS       = $row['TAGS'];
          $START_TIME = $row['START_TIME'];
          $END_TIME   = $row['END_TIME'];
          $DIFF   = $row['DIFF'];
          $VARIABLES  = $row['VARIABLES'];
        
          if ($OldPer != $ESER_ESAME . $MESE_ESAME) {
            if ($OldPer != "1") {
        ?>
              <tr style="height:2px;">
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
              </tr>
          <?php
            }
            $OldPer = $ESER_ESAME . $MESE_ESAME;
          }

          ?>
          <tr>
            <td><?php echo $ESER_ESAME; ?></td>
            <td><?php echo $MESE_ESAME; ?></td>
            <td><?php echo $TAGS; ?></td>
            <td><?php
                $arrF = explode(' ', $VARIABLES);
                if ($arrF[1] == "SON") {
                  $IdSql = $arrF[4];
                } else {
                  $IdSql = $arrF[2];
                }
                echo $IdSql;
                ?></td>
            <td><?php
                echo $_model->getNameInputFiles($IdSql);
                ?></td>
            <td><?php echo $START_TIME; ?></td>
            <td><?php echo $END_TIME; ?></td>
            <td><?php echo gmdate('i:s', $DIFF); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <BR>

    <table class="display dataTable">
      <caption>STATO LIBRERIA MULTI FILE</caption>
      <thead class="headerStyle">
        <tr>
          <th>ESER_ESAME</th>
          <th>MESE_ESAME</th>
          <th>TIPO</th>
          <th>LAST_CONS</th>
          <th>ID_FILE</th>
          <th>INPUT_FILE</th>
          <th>LOAD_DATE</th>
          <th>NUM_ROWS</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($datiInputFiles as $row) {
          $ESER_ESAME = $row['ESER_ESAME'];
          $MESE_ESAME = $row['MESE_ESAME'];
          $ID_FILE = $row['ID_FILE'];
          $LOAD_DATE = $row['LOAD_DATE'];
          $LAST_CONS = $row['LAST_CONS'];
          $NUM_ROWS = $row['NUM_ROWS'];
          $INPUT_FILE = $row['INPUT_FILE'];
          $NOTE = $row['NOTE'];
          if ("$OldPer" != "$ESER_ESAME$MESE_ESAME") {
            if ("$OldPer" != "1") {
        ?>
              <tr style="height:2px;">
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
                <th style="height: 2px !important;"></th>
              </tr>
          <?php
            }
            $OldPer = "$ESER_ESAME$MESE_ESAME";
          }

          ?>
          <tr>
            <td><?php echo $ESER_ESAME; ?></td>
            <td><?php echo $MESE_ESAME; ?></td>
            <td><?php echo $NOTE; ?></td>
            <td><?php echo $LAST_CONS; ?></td>
            <td><?php echo $ID_FILE; ?></td>
            <td><?php echo $INPUT_FILE; ?></td>
            <td><?php echo $LOAD_DATE; ?></td>
            <td><?php echo $NUM_ROWS; ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

</form>