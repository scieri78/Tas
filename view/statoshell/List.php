
<?php

if (
    $NumPage == $datishell->SelNumPage ||
        $datishell->LastShellRun == "LastShellRun" ||
        $datishell->DA_RETITWS != "" and
    ($CntS < $datishell->Soglia or $datishell->SelShTarget != "")
) {


    if ($IdShOld != $Test) {

        if ($IdShOld != "") { ?></ul></li><?php }

        $IdShOld = "${IdSh}${ShVariables}$IdRunSh";
        if ($datishell->SpliVar != "SpliVar") {
            $IdShOld = "${IdSh}${ShTags}$IdRunSh";
        }
        ?>
            <li id="p<?=$IdRunSh?>">
                <?php if ($IdSh!= "") { 
				   ?><label class="Esplodi" >o</label><?php 
				} ?>           
					<?php include "./view/statoshell/elementoTabella.php"; ?>
                <ul id="s<?= $IdRunSh ?>" class="riga26" style="display:<?= (in_array($IdRunSh, $datishell->ListIdOpen))?"block":"none"?>;">
                <?php
    }
 
    
    if ($IdSql != "" && in_array($IdRunSh, $datishell->ListIdOpen)) { ?>
            <li id="List_<?php echo $IdRunSh .
                "_" .
                $IdSql; ?>" class="has-sub">
                <?php if ($SqlType == "PLSQL" and $SqlUseRoutine != "0") { ?>
					<label class="Esplodi">o</label>
			  <?php } ?> 
              <?php include "./view/statoshell/elementoTabella2.php"; ?>
                <?php if (
                    ($SqlType == "PLSQL" && $SqlUseRoutine != "0") ||
                    $SSqlStatus == "I"
                ) {
                    SearchFatherRoutine(
                        $conn,
                        $datishell->ListIdOpen,
                        $IdSql,
                        $IdRunSh,
                        $SSqlStatus,
                        $_modelshell
                    );
                } ?>
            </li>
            <?php }
    $StepType = $row["TYPE_RUN"];
    if ($StepType == "STEP" && in_array($IdRunSh, $datishell->ListIdOpen)) {
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
  /*  $this->debug("ListIdOpen",$datishell->ListIdOpen);
    $this->debug("FIdSh",$FIdSh);
    $this->debug("IdRunSh",$IdRunSh);*/
    if ($FIdSh != "" && in_array($IdRunSh, $datishell->ListIdOpen)) { ?>
            <li class="<?php echo $SelClsSh; ?>" >
                <?php 
				if (
                    "$FIdSh" != ""
                ) { ?><label class="Esplodi" >o</label><?php } ?>           
             <?php include "./view/statoshell/elementoTabella4.php"; ?>
                <ul class="riga94" style="display:<?= ($FShStatus == "I" || in_array($FIdRunSh, $datishell->ListIdOpen))?"block":"none"?>">
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
    $InGiro = 1;
} else {
    if ($InGiro == "1") {
        if ($IdShOld != $Test) { ?></ul></li><?php }
        $InGiro = 0;
    }
    $IdShOld = "${IdSh}${ShVariables}$IdRunSh";
    if ($SpliVar != "SpliVar") {
        $IdShOld = "${IdSh}${ShTags}$IdRunSh";
    }
}

?>