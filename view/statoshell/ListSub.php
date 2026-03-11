<?php

  
    $StepType = $row["TYPE_RUN"];
    if ($StepType == "STEP") {
        $StepName = $row["STEP"];
        $StepTime = $row["SQL_START"];

        if (substr($StepName, 1, 10) == "Eseguo Shell in USER :") {
            $DatiShellUser = $_modelshell->getShellUser($IdRunSh);
            foreach ($DatiShellUser as $row5) {

                $TIdRunSh = $row5["ID_RUN_SH"];
                $TCnt = $TCnt + 1;
                ?>
			       <li class="has-sub" id="Open<?php echo $TIdRunSh .
              "_" .
              $TCnt; ?>" ></li>
				   <script>
				      $( "#Open<?php echo $TIdRunSh .
              "_" .
              $TCnt; ?>" ).load('./PHP/StatoShell_USER.php?IDSELEM=<?php echo $TIdRunSh; ?>').show();
				   </script>
			       <?php
            }
        } else {
             ?>
            <li class="has-sub">
               <?php include "./view/statoshell/elementoTabella3.php"; ?>
            </li>
            <?php
        }
    }

    $FIdSh = $row["F_ID_SH"];
    $FShCntPass= $row["F_CNTPASS"];
    if ($FIdSh != "") { ?>
            <li class="<?php echo $SelClsSh; ?>" >
                <?php 
				if (
                    "$FIdSh" != ""
                ) { ?><label class="Esplodi" >o</label><?php } ?>           
             <?php include "./view/statoshell/elementoTabella4.php"; ?>
                <ul style="display:
				<?php if ($FShStatus == "I" or in_array($FIdRunSh, $datishell->ListIdOpen)) {
        echo "block";
    } else {
        echo "none";
    } ?>;">
                  <?php RecSonsh(
                      $conn,
                      $FIdSh,
                      $FIdRunSh,
                      "N",
                      $datishell,
                      $_modelshell
                  ); ?>
                </ul>
            </li>
            <?php }


?>