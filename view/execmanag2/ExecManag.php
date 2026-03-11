<link rel="stylesheet" href="./CSS/statoshell.css?p=<?php echo rand(1000, 9999);  ?>">
<link rel="stylesheet" href="./CSS/StatoReti.css?p=<?php echo rand(1000, 9999);  ?>">
<link rel="stylesheet" href="./CSS/excel.css?p=?p=<?php echo rand(1000, 9999);  ?>">
<link rel="stylesheet" href="./CSS/mainmenu.css?p=<?php echo rand(1000, 9999);  ?>">
<link rel="stylesheet" href="./CSS/excel.css?p=<?php echo rand(1000, 9999);  ?>">
<form id="FormScheduler" method="POST">
  <?php
 include './GESTIONE/connection.php';
 include './GESTIONE/dataelab.php';
 include './GESTIONE/SettaVar.php';
 include './GESTIONE/login.php';


  if ($ForceRun == "Force Run") {
    shell_exec("sh $PRGDIR/RunExecManag.sh '${SSHUSR}' '${SERVER}' '${DATABASE}' &");
  ?><script>
      alert('Force Run Done!');
    </script><?php
            }

           
            if (1) {
              ?>
    <form id="FormScheduler" method="POST">
      <input type="hidden" id="IdEmGroup" name="IdEmGroup" value="<?php echo $IdEmGroup; ?>" />
      <input type="hidden" id="NameGroup" name="NameGroup" value="<?php echo $NameGroup; ?>" />
      <input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
      <div class="Titolo">
        <CENTER>Execution Management <?php echo $NameGroup; ?></CENTER>
      </div>
      <div id="Back" onclick="OpenExecManagGroup()"><img src="./images/Folder.png" class="home"><BR>BACK</div>
      <table id="RefreshDiv">
        <tr>
          <?php
              $AddObject = $_POST['AddObject'];
              $EditObject = $_POST['EditObject'];
              if ("$AddObject" == "" and "$EditObject" == "") { ?>
            <td>
              <button id="ShowCoda" onclick="OpenCoda('<?php echo $IdEmGroup; ?>');return false;" class="Button btn"><i class="fa-brands fa-firstdraft"> </i> IN CODA</button>
            </td>
            <td>
              <button id="ForceRun" value="Force Run" class="Button btn"><i class="fa-solid fa-circle-play"></i> Force Run</button>

            </td>
            <td><input type="checkbox" name="EnableRefresh" id="EnableRefresh" value="1" <?php if ("$EnableRefresh" == "1") { ?>checked<?php } ?> title="AutoRefresh"></td>
            <td>
              <button onclick="Refresh();return false;" id="Refresh" class="Button btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
            </td>

            <td>
              <div id="ViewLog">
                <img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('/area_staging_TAS/DIR_LOG/ExecManag/ExecManag_Group_<?php echo $DATABASE . '_' . date('Ym'); ?>.log','LSH')">
              </div>
            </td>
          <?php } ?>
          <td><img class="ObjectImg" src="./images/Add.png" onclick="AddObject(0)"></td>
        </tr>
      </table>
      <?php




              $conn = db2_connect($db2_conn_string, '', '');






            

           /*   $stmt = db2_prepare($conn, $sqlListObject);
              $result = db2_execute($stmt);*/

           /*   if (!$result) {
                echo $sqlListObject;
                echo "ERROR DB2:" . db2_stmt_errormsg($stmt);
              }*/
            


      ?><div id="InRun">
        <div class="TitRun">Now In Run:</div>
        <?php
              foreach ($Arr_Groups as $Group) {
                if ("$Group[2]" != "0") {
                  $NGroup = $Group[0];
        ?><div class="Run"><img class="ObjectImg2" src="./images/StatusI.png">GRP:<?php echo $NGroup; ?></div><?php
                                                                                                          }
                                                                                                        }
      foreach ($Arr_Objects as $Object) {
        if ("$Object[14]" == "I") {
          $NObject = $Object[3];
          $DObject = $Object[1];
          ?><div class="Run" title="<?php echo $DObject; ?>"><img class="ObjectImg2" src="./images/StatusI.png"><?php echo $NObject; ?></div><?php
                                    }
                                  }
                                                                                                                                        ?>
      </div><?php
         

              $EditObject = $_POST['EditObject'];
              if ("$EditObject" != "") {
                foreach ($Arr_Objects as $Object) {
                  $ObjId = $Object[0];
                  if ("$ObjId" == "$EditObject") {
                    $SelDescrObject = $Object[1];
                    $SelTargetDir = $Object[2];
                    $SelTarget = $Object[3];
                    $SelVariables = $Object[4];
                    $SelEnable = $Object[5];
                    $SelValidFrom = $Object[6];
                    $SelValidTo = $Object[7];
                    $SelSetMonth = $Object[8];
                    $SelSetDay = $Object[9];
                    $SelSetHour = $Object[10];
                    $SelSetMinute = $Object[11];
                    $SelFrequency = $Object[12];
                    $SelBg = $Object[13];
                    $SelFather = $Object[18];
                    $SelCreator = $Object[19];
                  }
                }

                $sqlLink = "SELECT ID_OBJECT_LINK  FROM WORK_CORE.EXEC_MANAG_LINKS WHERE ID_OBJECT = $EditObject ";
                $stmtLink = db2_prepare($conn, $sqlLink);
                $resultLink = db2_execute($stmtLink);

                if (!$resultLink) {
                  echo $sqlLink;
                  echo "ERROR DB2:" . db2_stmt_errormsg($stmtLink);
                }

                $Arr_Links = array();
                while ($rowLink = db2_fetch_assoc($stmtLink)) {
                  $IdLink = $rowLink['ID_OBJECT_LINK'];
                  array_push($Arr_Links, $IdLink);
                }

                if ("$SelIdEmGroup" == "") {
                  $SelIdEmGroup = $IdEmGroup;
                }

            ?>
        <div id="EditDiv" class="centerDiv">
          <div class="Title">Edit <?php echo "$SelTarget"; ?></div>
          <table class="center">
            <tr>
              <td style="width:50%;">
                <table class="center">
                  <tr>
                    <th>Descr</th>
                    <td>
                      <input type="hidden" name="SelIdObject" id="SelIdObject" value="<?php echo "$EditObject"; ?>">
                      <input type="text" name="SelDescrObject" id="SelDescrObject" value="<?php echo "$SelDescrObject"; ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Group</th>
                    <td><select name="SelIdEmGroup" id="SelIdEmGroup">
                        <?php

                        $sqlListGroups = "SELECT ID_EM_GROUP,NAME_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS ORDER BY NAME_GROUP";

                        $stmt2 = db2_prepare($conn, $sqlListGroups);
                        $result2 = db2_execute($stmt2);

                        if (!$result2) {
                          echo $sqlListGroup2;
                          echo "ERROR DB2:" . db2_stmt_errormsg($stmt2);
                        }
                        while ($row2 = db2_fetch_assoc($stmt2)) {
                          $IdEmGroup2 = $row2['ID_EM_GROUP'];
                          $IdEmNameGroup2 = $row2['NAME_GROUP'];
                        ?><option value="<?php echo $IdEmGroup2; ?>" <?php if ("$SelIdEmGroup" == "$IdEmGroup2") { ?> selected <?php } ?>><?php echo $IdEmNameGroup2; ?></option><?php
                                                                                                                                                                            } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>Target Dir</th>
                    <td><input type="text" name="SelTargetDir" id="SelTargetDir" value="<?php echo "$SelTargetDir"; ?>" required></td>
                  </tr>
                  <tr>
                    <th>Target</th>
                    <td><input type="text" name="SelTarget" id="SelTarget" value="<?php echo "$SelTarget"; ?>" required></td>
                  </tr>
                  <tr>
                    <th>Variables</th>
                    <td><input type="text" name="SelVariables" id="SelVariables" value='<?php echo "${SelVariables}"; ?>'></td>
                  </tr>
                  <tr>
                    <th>Enable</th>
                    <td>
                      <select name="SelEnable" id="SelEnable">
                        <option value="Y" <?php if ("$SelEnable" == "Y") { ?> selected <?php } ?>>Enable</option>
                        <option value="N" <?php if ("$SelEnable" == "N") { ?> selected <?php } ?>>Disable</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>ValidFrom</th>
                    <td><input type="date" name="SelValidFrom" id="SelValidFrom" value="<?php echo "$SelValidFrom"; ?>"></td>
                  </tr>
                  <tr>
                    <th>ValidTo</th>
                    <td><input type="date" name="SelValidTo" id="SelValidTo" value="<?php echo "$SelValidTo"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetMonth</th>
                    <td><input type="text" name="SelSetMonth" id="SelSetMonth" value="<?php echo "$SelSetMonth"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetDay</th>
                    <td><input type="text" name="SelSetDay" id="SelSetDay" value="<?php echo "$SelSetDay"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetHour</th>
                    <td><input type="text" name="SelSetHour" id="SelSetHour" value="<?php echo "$SelSetHour"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetMinute</th>
                    <td><input type="text" name="SelSetMinute" id="SelSetMinute" value="<?php echo "$SelSetMinute"; ?>"></td>
                  </tr>
                  <tr>
                    <th>Frequency</th>
                    <td>
                      <select name="SelFrequency" id="SelFrequency">
                        <option value="S" <?php if ("$SelFrequency" == "S") { ?> selected <?php } ?>>Single Run</option>
                        <option value="D" <?php if ("$SelFrequency" == "D") { ?> selected <?php } ?>>Daily</option>
                        <option value="M" <?php if ("$SelFrequency" == "M") { ?> selected <?php } ?>>Monthly</option>
                        <option value="Q" <?php if ("$SelFrequency" == "Q") { ?> selected <?php } ?>>Quarterly</option>
                        <option value="A" <?php if ("$SelFrequency" == "A") { ?> selected <?php } ?>>Annuality</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>Exec Next</th>
                    <td>
                      <select name="SelBg" id="SelBg">
                        <option value="N" <?php if ("$SelBg" == "N") { ?> selected <?php } ?>>Disable</option>
                        <option value="Y" <?php if ("$SelBg" == "Y") { ?> selected <?php } ?>>Enable</option>
                      </select>
                    </td>
                  </tr>
                </table>
              </td>
              <td style="width:50%;">
                <table class="center">
                  <tr>
                    <th colspan=2>Son of</th>
                  </tr>
                  <tr>
                    <td colspan=2><select name="SelFather" id="SelFather">
                        <option value="0" <?php if ("$SelFather" == "0") { ?> selected <?php } ?>>Nothing</option>
                        <?php
                        $List = explode(',', $_model->ListSons($EditObject));
                        foreach ($Arr_Objects as $Object2) {
                          $ObjId = $Object2[0];
                          $ObjDesc = $Object2[1];
                          $ObjTarget = $Object2[3];
                          $SelVariables = $Object2[4];
                          $SelGroup = $Object2[20];
                          if ("$ObjId" != "$EditObject") {
                            if (!in_array($ObjId, $List)) {
                        ?><option value="<?php echo $ObjId; ?>" <?php if ("$SelFather" == "$ObjId") { ?> selected <?php } ?>><?php echo $ObjTarget . " $SelVariables ( $ObjDesc )"; ?></option><?php
                                                                                                                                                                                            }
                                                                                                                                                                                          }
                                                                                                                                                                                        }
                                                                                                                                                                                              ?>
                      </select>
                    </td>
                  </tr>
                  <tr class="NoDep">
                    <th colspan=2>Dependent to</th>
                  </tr>
                  <tr class="NoDep">
                    <td colspan=2>
                      <div class="CheckLink">
                        <table width="100%">
                          <?php
                          foreach ($Arr_Objects as $Object2) {
                            $ObjId = $Object2[0];
                            $ObjDesc = $Object2[1];
                            $ObjTarget = $Object2[3];
                            $SelVariables = $Object2[4];
                            $SelGroup = $Object2[20];
                            $Checked = "";
                            if (in_array($ObjId, $Arr_Links)) {
                              $Checked = "Checked";
                            }
                            if ("$ObjId" != "$EditObject") {
                              if (!in_array($ObjId, $List)) {
                          ?>
                                <tr>
                                  <td style="width:20px;"><input type="checkbox" name="Check<?php echo $ObjId; ?>" id="Check<?php echo $ObjId; ?>" value="<?php echo $ObjId; ?>" <?php echo $Checked; ?>></td>
                                  <td><?php echo $ObjTarget . " $SelVariables ( $ObjDesc )"; ?></td>
                                </tr>
                          <?php
                              }
                            }
                          }
                          ?>
                        </table>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <center>
            <input class="Button" type="submit" id="SaveEdit" name="SaveEdit" value="Save">
            <input class="Button" id="CancelEdit" type="submit" value="Cancel">
          </center>
        </div>
      <?php
              }

              $AddObject = $_POST['AddObject'];
              if ("$AddObject" != "") {

                $IsCopy = $_POST['IsCopy'];
                if ("$IsCopy" != "") {
                  foreach ($Arr_Objects as $Object) {
                    $ObjId = $Object[0];
                    if ("$ObjId" == "$IsCopy") {
                      $SelDescrObject = $Object[1];
                      $SelTargetDir = $Object[2];
                      $SelTarget = $Object[3];
                      $SelVariables = $Object[4];
                      $SelEnable = $Object[5];
                      $SelValidFrom = $Object[6];
                      $SelValidTo = $Object[7];
                      $SelSetMonth = $Object[8];
                      $SelSetDay = $Object[9];
                      $SelSetHour = $Object[10];
                      $SelSetMinute = $Object[11];
                      $SelFrequency = $Object[12];
                      $SelBg = $Object[13];
                      $SelFather = $Object[18];
                      $SelCreator = $Object[19];
                    }
                  }
                  $AddObject = $SelFather;
                } else {
                  $SelDescrObject = "";
                  $SelTargetDir = "";
                  $SelTarget = "";
                  $SelVariables = "";
                  $SelEnable = "";
                  $SelValidFrom = date("Y-m-d");
                  $SelValidTo = "2999-12-31";
                  $SelSetMonth = "";
                  $SelSetDay = "";
                  $SelSetHour = "";
                  $SelSetMinute = "";
                  $SelFrequency = "";
                  $SelBg = "";
                  $SelFather = "";
                }

                if ("$SelIdEmGroup" == "") {
                  $SelIdEmGroup = $IdEmGroup;
                }

      ?>
        <div id="NewDiv" class="centerDiv">
          <div class="Title">New Object</div>
          <table class="center">
            <tr>
              <td style="width:50%;">
                <table class="center">
                  <tr>
                    <th>Descr</th>
                    <td>
                      <input type="text" name="SelDescrObject" id="SelDescrObject" value="<?php echo "$SelDescr"; ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Group</th>
                    <td><select name="SelIdEmGroup" id="SelIdEmGroup">
                        <?php

                        $sqlListGroups = "SELECT ID_EM_GROUP,NAME_GROUP
                    FROM WORK_CORE.EXEC_MANAG_GROUPS SO
                    ORDER BY NAME_GROUP";

                        $stmt2 = db2_prepare($conn, $sqlListGroups);
                        $result2 = db2_execute($stmt2);

                        if (!$result2) {
                          echo $sqlListGroup2;
                          echo "ERROR DB2:" . db2_stmt_errormsg($stmt2);
                        }
                        while ($row2 = db2_fetch_assoc($stmt2)) {
                          $IdEmGroup2 = $row2['ID_EM_GROUP'];
                          $IdEmNameGroup2 = $row2['NAME_GROUP'];
                        ?><option value="<?php echo $IdEmGroup2; ?>" <?php if ("$SelIdEmGroup" == "$IdEmGroup2") { ?> selected <?php } ?>><?php echo $IdEmNameGroup2; ?></option><?php
                                                                                                                                                                                } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>Target Dir</th>
                    <td><input type="text" name="SelTargetDir" id="SelTargetDir" value="<?php echo "$SelTargetDir"; ?>" required></td>
                  </tr>
                  <tr>
                    <th>Target</th>
                    <td><input type="text" name="SelTarget" id="SelTarget" value="<?php echo "$SelTarget"; ?>" required></td>
                  </tr>
                  <tr>
                    <th>Variables</th>
                    <td><input type="text" name="SelVariables" id="SelVariables" value='<?php echo "$SelVariables"; ?>'></td>
                  </tr>
                  <tr>
                    <th>Enable</th>
                    <td>
                      <select name="SelEnable" id="SelEnable">
                        <option value="Y" <?php if ("$SelEnable" == "Y") { ?> selected <?php } ?>>Enable</option>
                        <option value="N" <?php if ("$SelEnable" == "N") { ?> selected <?php } ?>>Disable</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>ValidFrom</th>
                    <td><input type="date" name="SelValidFrom" id="SelValidFrom" value="<?php echo "$SelValidFrom"; ?>"></td>
                  </tr>
                  <tr>
                    <th>ValidTo</th>
                    <td><input type="date" name="SelValidTo" id="SelValidTo" value="<?php echo "$SelValidTo"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetMonth</th>
                    <td><input type="text" name="SelSetMonth" id="SelSetMonth" value="<?php echo "$SelSetMonth"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetDay</th>
                    <td><input type="text" name="SelSetDay" id="SelSetDay" value="<?php echo "$SelSetDay"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetHour</th>
                    <td><input type="text" name="SelSetHour" id="SelSetHour" value="<?php echo "$SelSetHour"; ?>"></td>
                  </tr>
                  <tr>
                    <th>SetMinute</th>
                    <td><input type="text" name="SelSetMinute" id="SelSetMinute" value="<?php echo "$SelSetMinute"; ?>"></td>
                  </tr>
                  <tr>
                    <th>Frequency</th>
                    <td>
                      <select name="SelFrequency" id="SelFrequency">
                        <option value="S" <?php if ("$SelFrequency" == "S") { ?> selected <?php } ?>>Single Run</option>
                        <option value="D" <?php if ("$SelFrequency" == "D") { ?> selected <?php } ?>>Daily</option>
                        <option value="M" <?php if ("$SelFrequency" == "M") { ?> selected <?php } ?>>Monthly</option>
                        <option value="Q" <?php if ("$SelFrequency" == "Q") { ?> selected <?php } ?>>Quarterly</option>
                        <option value="A" <?php if ("$SelFrequency" == "A") { ?> selected <?php } ?>>Annuality</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>Exec Next</th>
                    <td>
                      <select name="SelBg" id="SelBg">
                        <option value="N" <?php if ("$SelBg" == "N") { ?> selected <?php } ?>>Disable</option>
                        <option value="Y" <?php if ("$SelBg" == "Y") { ?> selected <?php } ?>>Enable</option>
                      </select>
                    </td>
                  </tr>
                </table>
              </td>
              <td style="width:50%;">
                <table class="center">
                  <tr>
                    <th colspan=2>Son of</th>
                  </tr>
                  <tr>
                    <td colspan=2><select name="SelFather" id="SelFather">
                        <option value="0" <?php if ("$AddObject" == "0") { ?> selected <?php } ?>>Nothing</option>
                        <?php
                        $List = explode(',', $_model->ListSons($AddObject));
                        foreach ($Arr_Objects as $Object2) {
                          $ObjId = $Object2[0];
                          $ObjDesc = $Object2[1];
                          $ObjTarget = $Object2[3];
                          $SelVariables = $Object2[4];
                          $SelGroup = $Object2[20];
                          $Selected = "";
                          if ("$ObjId" == "$AddObject") {
                            $Selected = "selected";
                          }
                          if (!in_array($ObjId, $List)) {
                        ?><option value="<?php echo $ObjId; ?>" <?php if ("$AddObject" == "$ObjId") { ?> selected <?php } ?> <?php echo $Selected; ?>><?php echo $ObjTarget . " $SelVariables ( $ObjDesc )"; ?></option><?php
                                                                                                                                                                                                                        }
                                                                                                                                                                                                                      }
                                                                                                                                                                                                                          ?>
                      </select>
                    </td>
                  </tr>
                  <tr class="NoDep">
                    <th colspan=2>Dependent to</th>
                  </tr>
                  <tr class="NoDep">
                    <td colspan=2>
                      <div class="CheckLink">
                        <table width="100%">
                          <?php
                          foreach ($Arr_Objects as $Object2) {
                            $ObjId = $Object2[0];
                            $ObjDesc = $Object2[1];
                            $ObjTarget = $Object2[3];
                            $SelVariables = $Object2[4];
                            $SelGroup = $Object2[20];
                            if (!in_array($ObjId, $List)) {
                          ?>
                              <tr>
                                <td style="width:20px;"><input type="checkbox" name="Check<?php echo $ObjId; ?>" id="Check<?php echo $ObjId; ?>" value="<?php echo $ObjId; ?>"></td>
                                <td><?php echo $ObjTarget . " $SelVariables ( $ObjDesc )"; ?></td>
                              </tr>
                          <?php
                            }
                          }
                          ?>
                        </table>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <center>
            <input class="Button" type="submit" id="SaveNew" name="SaveNew" value="Save">
            <input class="Button" id="CancelNew" type="submit" value="Cancel">
          </center>
        </div>
      <?php
              }

              function Sons($IdFather, $conn, $Arr_Objects)
              {
                global $BarraPeggio, $BarraMeglio, $BarraCaricamento, $GAP, $RemLabel;

                $Sonsql = "SELECT PRIORITY,ID_OBJECT,PRIORITY_DEP,ID_OBJECT_DEP,
        (SELECT count(*) FROM WORK_CORE.EXEC_MANAG_PRIORITY A WHERE A.ID_OBJECT = SP.ID_OBJECT_DEP ) CNT
        FROM WORK_CORE.EXEC_MANAG_PRIORITY SP
        WHERE ID_OBJECT = $IdFather
        ORDER BY PRIORITY,ID_OBJECT,PRIORITY_DEP,ID_OBJECT_DEP";

                $Sonstmt = db2_prepare($conn, $Sonsql);
                $Sonresult = db2_execute($Sonstmt);

                if (!$Sonresult) {
                  echo $Sonsql;
                  echo "ERROR DB2:" . db2_stmt_errormsg($stmt);
                }

                $SonIdObjectOld = "";

      ?><ul><?php
                while ($Sonrow = db2_fetch_assoc($Sonstmt)) {
                  $SonPriority = $Sonrow['PRIORITY'];
                  $SonIdObject = $Sonrow['ID_OBJECT'];
                  $SonPriorityDep = $Sonrow['PRIORITY_DEP'];
                  $SonIdObjectDep = $Sonrow['ID_OBJECT_DEP'];
                  $SonIsFather = $Sonrow['CNT'];

                  if ("$SonIdObjectOld" != "$SonIdObject") {

                    if ("$SonIdObjectOld" != "") {
              ?></ul>
        </li><?php
                    }

                    $SonIdObjectOld = $SonIdObject;
                  }

                  if ("$SonIdObjectDep" != "") {

                    foreach ($Arr_Objects as $Object) {
                      if ("$Object[0]" == "$SonIdObjectDep") {
                        $SonDepDescrObject = $Object[1];
                        $SonDepTargetDir = $Object[2];
                        $SonDepTarget = $Object[3];
                        $SonDepVariables = $Object[4];
                        $SonDepEnable = $Object[5];
                        $SonDepValidFrom = $Object[6];
                        $SonDepValidTo = $Object[7];
                        $SonDepSetMonth = $Object[8];
                        $SonDepSetDay = $Object[9];
                        $SonDepSetHour = $Object[10];
                        $SonDepSetMinute = $Object[11];
                        $SonDepFrequency = $Object[12];
                        $SonDepBg = $Object[13];
                        $SonDepStatus = $Object[14];
                        $SonDepStartTime = $Object[15];
                        $SonDepEndTime = $Object[16];
                        $SonDepLogScheduler = $Object[17];
                        $SonDepCreator = $Object[19];
                        $SonDepLogSh = $Object[21];
                        $SonTime = $Object[22];
                        $SonOldTime = $Object[23];
                        $SonPrwEnd = $Object[24];
                        $SonIdRunSh = $Object[25];
                        $SonRemIdRunSh = $Object[26];
                        $SonDepRemLogSh = $Object[27];
                        $Editor = $Object[28];
                        $TimeCreator = $Object[29];
                        $TimeEditor = $Object[30];
                        $Executor = $Object[31];
                        $TimeExecutor = $Object[32];
                        $StatusSh = $Object[33];
                        $RemStatusSh = $Object[34];
                        break;
                      }
                    }

                    if ("$SonEndTime" == "" and "$SonPrwEnd" != "") {
                      $SonEndTime = "<B color=\"blue\">Preview:<BR>" . $SonPrwEnd . "<B>";
                    }
                    $lidisable = "";
                    $SonDepBkgr = "";
                    if ("$SonDepEnable" == "N") {
                      $SonDepBkgr = "background-color:#e12c2c;";
                      $lidisable = 'style="background-color:#c89696;"';
                    }
                    if ("$SonDepEnable" == "S") {
                      $SonDepBkgr = "background-color:#9697c8;";
                    }

                    if ("$SonDepStatus" == "I") {
                      $SonDepBkgr = "background-color:#ffcb00;";
                    }
                    $sqlLink = "SELECT ID_OBJECT_LINK  FROM WORK_CORE.EXEC_MANAG_LINKS L  WHERE L.ID_OBJECT = $SonIdObjectDep";

                    $stmtLink = db2_prepare($conn, $sqlLink);
                    $resultLink = db2_execute($stmtLink);

                    if (!$resultLink) {
                      echo $sqlLink;
                      echo "ERROR DB2:" . db2_stmt_errormsg($stmtLink);
                    }

                    $Arr_Links = array();
                    $FindLinks = 0;
                    while ($rowLink = db2_fetch_assoc($stmtLink)) {
                      $IdLink = $rowLink['ID_OBJECT_LINK'];
                      array_push($Arr_Links, $IdLink);
                      $FindLinks = 1;
                    }


              ?>
      <li <?php echo $lidisable; ?>>
        <div class="Object Div<?php echo $SonIdObjectDep ?>">
          <table>
            <tr>
              <td style="width:20px;<?php echo $SonDepBkgr; ?>" class="DivNum<?php echo $SonIdObjectDep; ?> "><?php echo $SonPriorityDep; ?></td>
              <td>
                <table class="ExcelTable">
                  <tr>
                    <th rowspan=2>
                      <table class="TitShell ts1">
                        <tr>
                          <td style="width:300px !important">
                            <?php
                            if ("$SonDepStatus" == "Y") {
                            ?><img class="ObjectImg" src="./images/StatusY.png" onclick="ChangeStatus(<?php echo $SonIdObjectDep; ?>,'<?php echo $SonDepStatus; ?>')"><?php
                                                                                                                                                                      } else {
                                                                                                                                                                        if ("$SonDepStatus" == "I") {
                                                                                                                                                                        ?><img class="ObjectImg2" src="./images/StatusI.png"><?php
                                                                                                                                                                        } else {
                                                                                      ?><img class="ObjectImg" src="./images/StatusN.png" onclick="ChangeStatus(<?php echo $SonIdObjectDep; ?>,'<?php echo $SonDepStatus; ?>')"><?php
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                                                                                      if ("$SonDepStatus" != "Y" and "$SonDepStatus" != "N" and "$SonDepStatus" != "I") {
                                                                                                                                                                        if ("$SonRemIdRunSh" != "" and "$SonIdRunSh" == "") {
                                                                                                                                                                          if ("$RemStatusSh" != "") {
                                                                                                                                                                          ?><img class="ObjectImg2" src="./images/Status<?php echo "$RemStatusSh"; ?>.png"><?php
                                                                                                                                                                          }
                                                                                                                                                                        } else {
                                                                                                                                                                          if ("$StatusSh" != "") {
                                                                                                        ?><img class="ObjectImg2" src="./images/Status<?php echo "$StatusSh"; ?>.png"><?php
                                                                                                                                                                          }
                                                                                                                                                                        }
                                                                                                                                                                      }
                                                                                                    ?>


                            <div class="ObjectTarget" onclick="OpenFile(<?php echo $SonIdObjectDep; ?>,'S')" title="<?php echo $SonIdObjectDep; ?>"><?php echo $SonDepTarget;
                                                                                                                                                    if ("$SonRemIdRunSh" != "" and "$SonIdRunSh" == "") {
                                                                                                                                                      echo $RemLabel;
                                                                                                                                                    }
                                                                                                                                                    ?></div>
                          </td>
                        <tr>
                        <tr>
                          <td>
                            <div class="ObjectVaraibles"><?php echo "$SonDepVariables"; ?></div>
                          </td>
                        <tr>
                        <tr>
                          <td>
                            <div class="ObjectTargetDir"><?php echo "$SonDepTargetDir"; ?></div>
                          </td>
                        <tr>
                        <tr>
                          <td>
                            <div class="ObjectTargetDir"><B>Creator:</B> <?php echo "$SonDepCreator - $TimeCreator"; ?></div>
                          </td>
                        <tr>
                        <tr>
                          <td>
                            <div class="ObjectTargetDir"><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="GroupNameGroupDir"><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div>
                          </td>
                        </tr>
                      </table>
                    </th>
                    <td><img class="ObjectImg" src="./images/Add.png" onclick="AddObject(<?php echo $SonIdObjectDep; ?>)"></td>
                    <td><img class="ObjectImg" src="./images/Copy.png" onclick="CopyObject(<?php echo $SonIdObjectDep; ?>)"></td>
                    <?php if ("$SonDepStatus" == "I") { ?>
                      <td></td>
                      <td></td>
                    <?php } else { ?>
                      <td><img class="ObjectImg" src="./images/Setting.png" onclick="EditObject(<?php echo $SonIdObjectDep; ?>)"></td>
                      <td><img class="ObjectImg" src="./images/Remove.png" onclick="DeleteObject(<?php echo $SonIdObjectDep; ?>)"></td>
                    <?php } ?>
                    <td>
                      <?php if ("$SonIdRunSh" != "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $SonIdRunSh; ?>,'user','<?php echo $SonDepTarget; ?>')"><?php } ?>
                      <?php if ("$SonRemIdRunSh" != "" and "$SonIdRunSh" == "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $SonRemIdRunSh; ?>,'work','<?php echo $SonDepTarget; ?>')"><?php } ?>
                    </td>
                    <td rowspan=2>
                      <?php
                      if ("$SonDepBg" == "Y") {
                      ?><B>EXEC<BR>NEXT</B><?php
                                                  }
                                                    ?>
                    </td>
                    <th style="width:30px !important">Meter</th>
                    <td style="width:200px !important">
                      <?php
                      if ("$SonOldTime" != "") {
                        $SecLast = $SonTime;
                        $SecPre = $SonOldTime;
                        if ("$SecPre" == "0") {
                          $SecPre = 1;
                        }
                        $Perc = round(($SecLast * 100) / $SecPre);

                        $SColor = "";
                        if ($Perc <= 100 and "$SonDepStatus" != "I") {
                          $SColor = "$BarraMeglio";
                        }
                        if ("$SonDepStatus" == "I") {
                          $SColor = "$BarraCaricamento";
                        }
                        if ($Perc > 120) {
                          $SColor = "$BarraPeggio";
                        }

                        if (
                          (1 == 1
                            //and ( $Perc > 120 or $Perc < 90 ) 
                            and  ("$SonDepStatus" == "F" or "$SonDepStatus" == "W")
                            and ($SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP)
                          )
                          or ("$SonDepStatus" == "I")
                        ) {
                      ?>
                          <div class="progress">
                            <div class="progress-bar progress-bar-striped <?php
                                                                          if ("$SonDepStatus" == "I") {
                                                                            echo "active";
                                                                          }
                                                                          ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;width: 2em; width: <?php
                                                                                                                                                                                      if ($Perc > 100) {
                                                                                                                                                                                        echo 100;
                                                                                                                                                                                      } else {
                                                                                                                                                                                        echo "$Perc";
                                                                                                                                                                                      }
                                                                                                                                                                                      ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;"><LABEL style="font-weight: 500;"><?php
                                                                                                                                                    if ($Perc > 100) {
                                                                                                                                                      $Perc = $Perc - 100;
                                                                                                                                                      $Perc = "+" . $Perc;
                                                                                                                                                    } else {
                                                                                                                                                      if ("$SonDepStatus" != "I") {
                                                                                                                                                        $Perc = $Perc - 100;
                                                                                                                                                      }
                                                                                                                                                    }
                                                                                                                                                    echo $Perc;
                                                                                                                                                    ?>%</LABEL>
                            </div>
                          </div>
                      <?php
                        }
                      }
                      ?>
                    </td>
                    <th style="width:65px !important">Start Time</th>
                    <td style="width:145px !important"><?php echo "$SonDepStartTime"; ?></td>
                    <th style="width:45px !important">Duration</th>
                    <th style="width:45px !important">Old Time</th>
                    <th style="width:200px !important">Dependent</th>
                    <th>Enable From</th>
                    <th <?php if ("$SonDepValidTo"  == "2999-12-31") { ?> hidden <?php } ?>>Enable To</th>
                    <th <?php if ("$SonDepSetMonth"  == "") { ?> hidden <?php } ?>>Month</th>
                    <th <?php if ("$SonDepSetDay"    == "") { ?> hidden <?php } ?>>Day</th>
                    <th <?php if ("$SonDepSetHour"   == "") { ?> hidden <?php } ?>>Hour</th>
                    <th <?php if ("$SonDepSetMinute" == "") { ?> hidden <?php } ?>>Minute</th>
                    <th>Frequency</th>
                  </tr>
                  <tr>
                    <?php if ("$SonDepStatus" == "I") { ?>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    <?php } else { ?>
                      <td><img class="ObjectImg" src="./images/Up.png" onclick="DownObject(<?php echo $SonIdObjectDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep - 1; ?>)"></td>
                      <td><img class="ObjectImg" src="./images/Down.png" onclick="UpObject(<?php echo $SonIdObjectDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep + 1; ?>)"></td>
                      <td><img class="ObjectImg" src="./images/Frist.png" onclick="DownObject(<?php echo $SonIdObjectDep; ?>,<?php echo $SonPriorityDep; ?>,1)"></td>
                      <td><img class="ObjectImg" src="./images/Last.png" onclick="UpObject(<?php echo $SonIdObjectDep; ?>,<?php echo $SonPriorityDep; ?>,0)"></td>
                      <td>
                        <?php if ("$SonDepLogSh" != "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $SonDepLogSh; ?>','LSH')"><?php } ?>
                        <?php if ("$SonDepRemLogSh" != "" and "$SonDepLogSh" == "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $SonDepRemLogSh; ?>','LSH')"><?php } ?>
                      </td>
                    <?php } ?>
                    <th style="width:30px !important">Descr</th>
                    <td style="width:200px !important"><?php echo "$SonDepDescrObject"; ?></td>
                    <th>End Time</th>
                    <td width="145px"><?php echo "$SonDepEndTime"; ?></td>
                    <td style="width:45px !important"><?php if ("$SonTime" != "0" and "$SonTime" != "") {
                                                        echo floor(($SonTime - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $SonTime);
                                                      } ?></td>
                    <td style="width:45px !important"><?php if ("$SonOldTime" != "0" and "$SonOldTime" != "") {
                                                        echo floor(($SonOldTime - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $SonOldTime);
                                                      } ?></td>
                    <td class="DivDep<?php echo $SonIdObjectDep; ?>">
                      <?php if ($FindLinks != 0) {
                        $LisLnk = "";
                        foreach ($Arr_Objects as $Object2) {
                          $ObjId = $Object2[0];
                          $ObjTarget = $Object2[3];
                          $SelVariables = $Object2[4];
                          if (in_array($ObjId, $Arr_Links)) {
                            $LisLnk = $LisLnk . $ObjTarget . ' ' . $SelVariables . ',<BR>';
                          }
                        }
                        echo $LisLnk;
                      ?>
                        <script>
                          $('.Div<?php echo $SonIdObjectDep; ?>').mouseover(function() {
                            $('.DivDep<?php echo $SonIdObjectDep; ?>').css('background', 'SteelBlue');
                            $('.DivDep<?php echo $SonIdObjectDep; ?>').css('color', 'white');
                            <?php
                            foreach ($Arr_Objects as $Object2) {
                              $ObjId = $Object2[0];
                              $ObjTarget = $Object2[3];
                              $SelVariables = $Object2[4];
                              if (in_array($ObjId, $Arr_Links)) {
                            ?>
                                $('.DivNum<?php echo $ObjId; ?>').css('border', '2px solid SteelBlue');
                                $('.DivNum<?php echo $ObjId; ?>').css('color', 'white');
                            <?php
                              }
                            }
                            ?>
                          });
                          $('.Div<?php echo $SonIdObjectDep; ?>').mouseleave(function() {
                            $('.DivDep<?php echo $SonIdObjectDep; ?>').css('background', 'white');
                            $('.DivDep<?php echo $SonIdObjectDep; ?>').css('color', 'black');
                            <?php
                            foreach ($Arr_Objects as $Object2) {
                              $ObjId = $Object2[0];
                              $ObjTarget = $Object2[3];
                              $SelVariables = $Object2[4];
                              if (in_array($ObjId, $Arr_Links)) {
                            ?>
                                $('.DivNum<?php echo $ObjId; ?>').css('border', 'none');
                                $('.DivNum<?php echo $ObjId; ?>').css('color', 'black');
                            <?php
                              }
                            }
                            ?>
                          });
                        </script>
                      <?php
                      }
                      ?>
                    </td>
                    <td><?php echo "$SonDepValidFrom"; ?></td>
                    <td <?php if ("$SonDepValidTo"  == "2999-12-31") { ?> hidden <?php } ?>><?php echo "$SonDepValidTo"; ?></td>
                    <td <?php if ("$SonDepSetMonth"  == "") { ?> hidden <?php } ?>><?php echo "$SonDepSetMonth"; ?></td>
                    <td <?php if ("$SonDepSetDay"    == "") { ?> hidden <?php } ?>><?php echo "$SonDepSetDay"; ?></td>
                    <td <?php if ("$SonDepSetHour"   == "") { ?> hidden <?php } ?>><?php echo "$SonDepSetHour"; ?></td>
                    <td <?php if ("$SonDepSetMinute" == "") { ?> hidden <?php } ?>><?php echo "$SonDepSetMinute"; ?></td>
                    <td><?php echo "$SonDepFrequency"; ?></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
        <?php
                    if ("$SonIsFather" != "0") {
                      Sons($SonIdObjectDep, $conn, $Arr_Objects);
                    }
        ?>
      </li>
  <?php


                  }
                }
  ?></li>
  </ul><?php

              }


              $sql = "SELECT PRIORITY,ID_OBJECT,PRIORITY_DEP,ID_OBJECT_DEP,
    (SELECT count(*) FROM WORK_CORE.EXEC_MANAG_PRIORITY A WHERE A.ID_OBJECT = SP.ID_OBJECT_DEP ) CNT
    FROM WORK_CORE.EXEC_MANAG_PRIORITY SP
    WHERE ID_OBJECT NOT IN ( SELECT ID_OBJECT_DEP FROM WORK_CORE.EXEC_MANAG_PRIORITY WHERE ID_OBJECT_DEP IS NOT NULL )
    AND ID_OBJECT IN ( SELECT ID_OBJECT FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE CURRENT_DATE BETWEEN VALID_FROM AND VALID_TO AND ID_EM_GROUP = $IdEmGroup )
    ORDER BY PRIORITY,ID_OBJECT,PRIORITY_DEP,ID_OBJECT_DEP";

              $stmt = db2_prepare($conn, $sql);
              $result = db2_execute($stmt);

              if (!$result) {
                echo $sql;
                echo "ERROR DB2:" . db2_stmt_errormsg($stmt);
              }

              $IdObjectOld = "";

        ?><ul><?php
              while ($row = db2_fetch_assoc($stmt)) {
                $Priority = $row['PRIORITY'];
                $IdObject = $row['ID_OBJECT'];
                $PriorityDep = $row['PRIORITY_DEP'];
                $IdObjectDep = $row['ID_OBJECT_DEP'];
                $IsFather = $row['CNT'];

                if ("$IdObjectOld" != "$IdObject") {

                  if ("$IdObjectOld" != "") {
          ?></ul>
</li><?php
                  }

                  $IdObjectOld = $IdObject;

                  foreach ($Arr_Objects as $Object) {
                    if ("$Object[0]" == "$IdObject") {
                      $DescrObject = $Object[1];
                      $TargetDir = $Object[2];
                      $Target = $Object[3];
                      $Variables = $Object[4];
                      $Enable = $Object[5];
                      $ValidFrom = $Object[6];
                      $ValidTo = $Object[7];
                      $SetMonth = $Object[8];
                      $SetDay = $Object[9];
                      $SetHour = $Object[10];
                      $SetMinute = $Object[11];
                      $Frequency = $Object[12];
                      $Bg = $Object[13];
                      $Status = $Object[14];
                      $StartTime = $Object[15];
                      $EndTime = $Object[16];
                      $LogScheduler = $Object[17];
                      $CreatorObject = $Object[19];
                      $LogSh = $Object[21];
                      $Time = $Object[22];
                      $OldTime = $Object[23];
                      $PrwEnd = $Object[24];
                      $IdRunSh = $Object[25];
                      $RemIdRunSh = $Object[26];
                      $RemLogSh = $Object[27];
                      $Editor = $Object[28];
                      $TimeCreator = $Object[29];
                      $TimeEditor = $Object[30];
                      $Executor = $Object[31];
                      $TimeExecutor = $Object[32];
                      $StatusSh = $Object[33];
                      $RemStatusSh = $Object[34];
                      break;
                    }
                  }

                  if ("$EndTime" == "" and "$PrwEnd" != "") {
                    $EndTime = "<B color=\"blue\">Preview:<BR>" . $PrwEnd . "</B>";
                  }

                  $sqlLink = "SELECT ID_OBJECT_LINK  FROM WORK_CORE.EXEC_MANAG_LINKS L  WHERE L.ID_OBJECT = $IdObject";

                  $stmtLink = db2_prepare($conn, $sqlLink);
                  $resultLink = db2_execute($stmtLink);

                  if (!$resultLink) {
                    echo $sqlLink;
                    echo "ERROR DB2:" . db2_stmt_errormsg($stmtLink);
                  }

                  $Arr_Links = array();
                  $FindLinks = 0;
                  while ($rowLink = db2_fetch_assoc($stmtLink)) {
                    $IdLink = $rowLink['ID_OBJECT_LINK'];
                    array_push($Arr_Links, $IdLink);
                    $FindLinks = 1;
                  }

                  $lidisable = '';
                  $Bkgr = "";
                  if ("$Enable" == "N") {
                    $Bkgr = "background-color:#e12c2c;";
                    $lidisable = 'style="background-color:#c89696;"';
                  }
                  if ("$Enable" == "S") {
                    $Bkgr = "background-color:#9697c8;";
                  }
                  if ("$Status" == "I") {
                    $Bkgr = "background-color:#ffcb00;";
                  }
      ?>
<li <?php echo $lidisable; ?>>
  <div class="Object Div<?php echo $IdObject ?>">
    <table>
      <tr>
        <td style="width:20px;<?php echo $Bkgr; ?>" class="DivNum<?php echo $IdObject; ?> "><?php echo $Priority; ?></td>
        <td>
          <table class="ExcelTable">
            <tr>
              <th rowspan=2>
                <table class="TitShell ts2">
                  <tr>
                    <td style="width:300px !important">
                      <?php
                      if ("$Status" == "Y") {
                      ?><img class="ObjectImg" src="./images/StatusY.png" onclick="ChangeStatus(<?php echo $IdObject; ?>,'<?php echo $Status; ?>')"><?php
                                                                                                                                                    } else {
                                                                                                                                                      if ("$Status" == "I") {
                                                                                                                                                      ?><img class="ObjectImg2" src="./images/StatusI.png"><?php
                                                                                                                                                      } else {
                                                                              ?><img class="ObjectImg" src="./images/StatusN.png" onclick="ChangeStatus(<?php echo $IdObject; ?>,'<?php echo $Status; ?>')"><?php
                                                                                                                                                      }
                                                                                                                                                    }
                                                                                                                                                    if ("$Status" != "Y" and "$Status" != "N" and "$Status" != "I") {
                                                                                                                                                      if ("$RemIdRunSh" != "" and "$IdRunSh" == "") {
                                                                                                                                                        if ("$RemStatusSh" != "") {
                                                                                                                                                        ?><img class="ObjectImg2" src="./images/Status<?php echo "$RemStatusSh"; ?>.png"><?php
                                                                                                                                                        }
                                                                                                                                                      } else {
                                                                                                                                                        if ("$StatusSh" != "") {
                                                                                                  ?><img class="ObjectImg2" src="./images/Status<?php echo "$StatusSh"; ?>.png"><?php
                                                                                                                                                        }
                                                                                                                                                      }
                                                                                                                                                    }
                                                                                              ?>

                      <div class="ObjectTarget" onclick="OpenFile(<?php echo $IdObject; ?>,'S')" title="<?php echo $IdObject; ?>"><?php echo $Target;
                                                                                                                                  if ("$RemIdRunSh" != "" and "$IdRunSh" == "") {
                                                                                                                                    echo $RemLabel;
                                                                                                                                  }
                                                                                                                                  ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="ObjectVaraibles"><?php echo "$Variables"; ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="ObjectTargetDir"><?php echo "$TargetDir"; ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="ObjectTargetDir"><B>Creator:</B> <?php echo "$CreatorObject - $TimeCreator"; ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="ObjectTargetDir"><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="GroupNameGroupDir"><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div>
                    </td>
                  </tr>
                </table>
              </th>
              <td><img class="ObjectImg" src="./images/Add.png" onclick="AddObject(<?php echo $IdObject; ?>)"></td>
              <td><img class="ObjectImg" src="./images/Copy.png" onclick="CopyObject(<?php echo $IdObject; ?>)"></td>
              <?php if ("$Status" == "I") { ?>
                <td></td>
                <td></td>
              <?php } else { ?>
                <td><img class="ObjectImg" src="./images/Setting.png" onclick="EditObject(<?php echo $IdObject; ?>)"></td>
                <td><img class="ObjectImg" src="./images/Remove.png" onclick="DeleteObject(<?php echo $IdObject; ?>)"></td>
              <?php } ?>
              <td>
                <?php if ("$IdRunSh" != "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $IdRunSh; ?>,'user','<?php echo $Target; ?>')"><?php } ?>
                <?php if ("$RemIdRunSh" != "" and "$IdRunSh" == "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $RemIdRunSh; ?>,'work','<?php echo $Target; ?>')"><?php } ?>
              </td>
              <td rowspan=2>
                <?php
                  if ("$Bg" == "Y") {
                ?><B>EXEC<BR>NEXT</B><?php
                                }
                                  ?>
              </td>
              <th style="width:30px !important">Meter</th>
              <td style="width:200px !important">
                <?php
                  if ("$OldTime" != "" and "$OldTime" != "0") {
                    $SecLast = $Time;
                    $SecPre = $OldTime;
                    if ("$SecPre" == "0") {
                      $SecPre = 1;
                    }
                    $Perc = round(($SecLast * 100) / $SecPre);

                    $SColor = "";
                    if ($Perc <= 100 and "$Status" != "I") {
                      $SColor = "$BarraMeglio";
                    }
                    if ("$Status" == "I") {
                      $SColor = "$BarraCaricamento";
                    }
                    if ($Perc > 120) {
                      $SColor = "$BarraPeggio";
                    }

                    if (
                      (1 == 1
                        //and ( $Perc > 120 or $Perc < 90 ) 
                        and  ("$Status" == "F" or "$Status" == "W")
                        and ($SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP)
                      )
                      or ("$Status" == "I")
                    ) {
                ?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped <?php
                                                                    if ("$Status" == "I") {
                                                                      echo "active";
                                                                    }
                                                                    ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;width: 2em; width: <?php
                                                                                                                                                                                  if ($Perc > 100) {
                                                                                                                                                                                    echo 100;
                                                                                                                                                                                  } else {
                                                                                                                                                                                    echo "$Perc";
                                                                                                                                                                                  }
                                                                                                                                                                                  ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;"><LABEL style="font-weight: 500;"><?php
                                                                                                                                                if ($Perc > 100) {
                                                                                                                                                  $Perc = $Perc - 100;
                                                                                                                                                  $Perc = "+" . $Perc;
                                                                                                                                                } else {
                                                                                                                                                  if ("$Status" != "I") {
                                                                                                                                                    $Perc = $Perc - 100;
                                                                                                                                                  }
                                                                                                                                                }
                                                                                                                                                echo $Perc;
                                                                                                                                                ?>%</LABEL>
                      </div>
                    </div>
                <?php
                    }
                  }
                ?>
              </td>
              <th style="width:65px !important">Start Time</th>
              <td style="width:145px !important"><?php echo "$StartTime"; ?></td>
              <th style="width:45px !important">Duration</th>
              <th style="width:45px !important">Old Time</th>
              <th style="width:200px !important">Dependent</th>
              <th>Enable From</th>
              <th <?php if ("$ValidTo"  == "2999-12-31") { ?> hidden <?php } ?>>Enable To</th>
              <th <?php if ("$SetMonth"  == "") { ?> hidden <?php } ?>>Month</th>
              <th <?php if ("$SetDay"    == "") { ?> hidden <?php } ?>>Day</th>
              <th <?php if ("$SetHour"   == "") { ?> hidden <?php } ?>>Hour</th>
              <th <?php if ("$SetMinute" == "") { ?> hidden <?php } ?>>Minute</th>
              <th>Frequency</th>
            </tr>
            <tr>
              <?php if ("$Status" == "I") { ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              <?php } else { ?>
                <td><img class="ObjectImg" src="./images/Up.png" onclick="DownObject(<?php echo $IdObject; ?>,<?php echo $Priority; ?>,<?php echo $Priority - 1; ?>)"></td>
                <td><img class="ObjectImg" src="./images/Down.png" onclick="UpObject(<?php echo $IdObject; ?>,<?php echo $Priority; ?>,<?php echo $Priority + 1; ?>)"></td>
                <td><img class="ObjectImg" src="./images/Frist.png" onclick="DownObject(<?php echo $IdObject; ?>,<?php echo $Priority; ?>,1)"></td>
                <td><img class="ObjectImg" src="./images/Last.png" onclick="UpObject(<?php echo $IdObject; ?>,<?php echo $Priority; ?>,0)"></td>
                <td>
                  <?php if ("$LogSh" != "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $LogSh; ?>','LSH')"><?php } ?>
                  <?php if ("$RemLogSh" != "" and "$LogSh" == "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $RemLogSh; ?>','LSH')"><?php } ?>
                </td>
              <?php } ?>
              <th style="width:30px !important">Descr</th>
              <td style="width:200px !important"><?php echo "$DescrObject"; ?></td>
              <th>End Time</th>
              <td width="145px"><?php echo "$EndTime"; ?></td>
              <td style="width:45px !important"><?php if ("$Time" != "0" and "$Time" != "") {
                                                  echo floor(($Time - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $Time);
                                                } ?></td>
              <td style="width:45px !important"><?php if ("$OldTime" != "0" and "$OldTime" != "") {
                                                  echo floor(($OldTime - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $OldTime);
                                                } ?></td>
              <td class="DivDep<?php echo $IdObject; ?>">
                <?php if ($FindLinks != 0) {
                    $LisLnk = "";
                    foreach ($Arr_Objects as $Object2) {
                      $ObjId = $Object2[0];
                      $ObjTarget = $Object2[3];
                      $SelVariables = $Object2[4];
                      if (in_array($ObjId, $Arr_Links)) {
                        $LisLnk = $LisLnk . $ObjTarget . ' ' . $SelVariables . ',<BR>';
                      }
                    }
                    echo $LisLnk;
                ?>
                  <script>
                    $('.Div<?php echo $IdObject; ?>').mouseover(function() {
                      $('.DivDep<?php echo $IdObject; ?>').css('background', 'SteelBlue');
                      $('.DivDep<?php echo $IdObject; ?>').css('color', 'white');
                      <?php
                      foreach ($Arr_Objects as $Object2) {
                        $ObjId = $Object2[0];
                        $ObjTarget = $Object2[3];
                        $SelVariables = $Object2[4];
                        if (in_array($ObjId, $Arr_Links)) {
                      ?>
                          $('.DivNum<?php echo $ObjId; ?>').css('border', '2px solid SteelBlue');
                          $('.DivNum<?php echo $ObjId; ?>').css('color', 'white');
                      <?php
                        }
                      }
                      ?>
                    });
                    $('.Div<?php echo $IdObject; ?>').mouseleave(function() {
                      $('.DivDep<?php echo $IdObject; ?>').css('background', 'white');
                      $('.DivDep<?php echo $IdObject; ?>').css('color', 'black');
                      <?php
                      foreach ($Arr_Objects as $Object2) {
                        $ObjId = $Object2[0];
                        $ObjTarget = $Object2[3];
                        $SelVariables = $Object2[4];
                        if (in_array($ObjId, $Arr_Links)) {
                      ?>
                          $('.DivNum<?php echo $ObjId; ?>').css('border', 'none');
                          $('.DivNum<?php echo $ObjId; ?>').css('color', 'black');
                      <?php
                        }
                      }
                      ?>
                    });
                  </script>
                <?php
                  }
                ?>
              </td>
              <td><?php echo "$ValidFrom"; ?></td>
              <td <?php if ("$ValidTo"  == "2999-12-31") { ?> hidden <?php } ?>><?php echo "$ValidTo"; ?></td>
              <td <?php if ("$SetMonth"  == "") { ?> hidden <?php } ?>><?php echo "$SetMonth"; ?></td>
              <td <?php if ("$SetDay"    == "") { ?> hidden <?php } ?>><?php echo "$SetDay"; ?></td>
              <td <?php if ("$SetHour"   == "") { ?> hidden <?php } ?>><?php echo "$SetHour"; ?></td>
              <td <?php if ("$SetMinute" == "") { ?> hidden <?php } ?>><?php echo "$SetMinute"; ?></td>
              <td><?php echo "$Frequency"; ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
  <ul>
  <?php
                }

                if ("$IdObjectDep" != "") {


                  foreach ($Arr_Objects as $Object) {
                    if ("$Object[0]" == "$IdObjectDep") {
                      $DepDescrObject = $Object[1];
                      $DepTargetDir = $Object[2];
                      $DepTarget = $Object[3];
                      $DepVariables = $Object[4];
                      $DepEnable = $Object[5];
                      $DepValidFrom = $Object[6];
                      $DepValidTo = $Object[7];
                      $DepSetMonth = $Object[8];
                      $DepSetDay = $Object[9];
                      $DepSetHour = $Object[10];
                      $DepSetMinute = $Object[11];
                      $DepFrequency = $Object[12];
                      $DepBg = $Object[13];
                      $DepStatus = $Object[14];
                      $DepStartTime = $Object[15];
                      $DepEndTime = $Object[16];
                      $DepLogScheduler = $Object[17];
                      $DepCreatorObject = $Object[19];
                      $DepLogSh = $Object[21];
                      $DepTime = $Object[22];
                      $DepOldTime = $Object[23];
                      $DepPrwEnd = $Object[24];
                      $DepIdRunSh = $Object[25];
                      $DepRemIdRunSh = $Object[26];
                      $DepRemLogSh = $Object[27];
                      $Editor = $Object[28];
                      $TimeCreator = $Object[29];
                      $TimeEditor = $Object[30];
                      $Executor = $Object[31];
                      $TimeExecutor = $Object[32];
                      $StatusSh = $Object[33];
                      $RemStatusSh = $Object[34];
                      break;
                    }
                  }

                  if ("$DepEndTime" == "" and "$DepPrwEnd" != "") {
                    $DepEndTime = "<B color=\"blue\">Preview:<BR>" . $DepPrwEnd . "<B>";
                  }

                  $sqlLink = "SELECT ID_OBJECT_LINK  FROM WORK_CORE.EXEC_MANAG_LINKS L  WHERE L.ID_OBJECT = $IdObjectDep";

                  $stmtLink = db2_prepare($conn, $sqlLink);
                  $resultLink = db2_execute($stmtLink);

                  if (!$resultLink) {
                    echo $sqlLink;
                    echo "ERROR DB2:" . db2_stmt_errormsg($stmtLink);
                  }

                  $Arr_Links = array();
                  $FindLinks = 0;
                  while ($rowLink = db2_fetch_assoc($stmtLink)) {
                    $IdLink = $rowLink['ID_OBJECT_LINK'];
                    array_push($Arr_Links, $IdLink);
                    $FindLinks = 1;
                  }

                  $lidisable = '';
                  $DepBkgr = "";
                  if ("$DepEnable" == "N") {
                    $DepBkgr = "background-color:#e12c2c;";
                    $lidisable = 'style="background-color:#c89696;"';
                  }
                  if ("$DepEnable" == "S") {
                    $DepBkgr = "background-color:#9697c8;";
                  }
                  if ("$DepStatus" == "I") {
                    $DepBkgr = "background-color:#ffcb00;";
                  }

  ?>
    <li <?php echo $lidisable; ?>>
      <div class="Object Div<?php echo $IdObjectDep ?>">
        <table>
          <tr>
            <td style="width:20px;<?php echo $DepBkgr; ?>" class="DivNum<?php echo $IdObjectDep; ?> "><?php echo $PriorityDep; ?></td>
            <td>
              <table class="ExcelTable">
                <tr>
                  <th rowspan=2>
                    <table class="TitShell ts3">
                      <tr>
                        <td style="width:300px !important">
                          <?php
                          if ("$DepStatus" == "Y") {
                          ?><img class="ObjectImg" src="./images/StatusY.png" onclick="ChangeStatus(<?php echo $IdObjectDep; ?>,'<?php echo $DepStatus; ?>')"><?php
                                                                                                                                                            } else {
                                                                                                                                                              if ("$DepStatus" == "I") {
                                                                                                                                                              ?><img class="ObjectImg2" src="./images/StatusI.png"><?php
                                                                                                                                                              } else {
                                                                                ?><img class="ObjectImg" src="./images/StatusN.png" onclick="ChangeStatus(<?php echo $IdObjectDep; ?>,'<?php echo $DepStatus; ?>')"><?php
                                                                                                                                                              }
                                                                                                                                                            }
                                                                                                                                                            if ("$DepStatus" != "Y" and "$DepStatus" != "N" and "$DepStatus" != "I") {
                                                                                                                                                              if ("$DepRemIdRunSh" != "" and "$DepIdRunSh" == "") {
                                                                                                                                                                if ("$RemStatusSh" != "") {
                                                                                                                                                              ?><img class="ObjectImg2" src="./images/Status<?php echo "$RemStatusSh"; ?>.png"><?php
                                                                                                                                                                }
                                                                                                                                                              } else {
                                                                                                                                                                if ("$StatusSh" != "") {
                                                                                                    ?><img class="ObjectImg2" src="./images/Status<?php echo "$StatusSh"; ?>.png"><?php
                                                                                                                                                                }
                                                                                                                                                              }
                                                                                                                                                            }
                                                                                                ?>
                          <div class="ObjectTarget" onclick="OpenFile(<?php echo $IdObjectDep; ?>,'S')" title="<?php echo $IdObjectDep; ?>"><?php echo "$DepTarget";
                                                                                                                                            if ("$DepRemIdRunSh" != "" and "$DepIdRunSh" == "") {
                                                                                                                                              echo "$RemLabel";
                                                                                                                                            }
                                                                                                                                            ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="ObjectVaraibles"><?php echo "$DepVariables"; ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="ObjectTargetDir"><?php echo "$DepTargetDir"; ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="ObjectTargetDir"><B>Creator:</B> <?php echo "$DepCreatorObject - $TimeCreator"; ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="ObjectTargetDir"><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="GroupNameGroupDir"><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div>
                        </td>
                      </tr>
                    </table>
                  </th>
                  <td><img class="ObjectImg" src="./images/Add.png" onclick="AddObject(<?php echo $IdObjectDep; ?>)"></td>
                  <td><img class="ObjectImg" src="./images/Copy.png" onclick="CopyObject(<?php echo $IdObjectDep; ?>)"></td>
                  <?php if ("$DepStatus" == "I") { ?>
                    <td></td>
                    <td></td>
                  <?php } else { ?>
                    <td><img class="ObjectImg" src="./images/Setting.png" onclick="EditObject(<?php echo $IdObjectDep; ?>)"></td>
                    <td><img class="ObjectImg" src="./images/Remove.png" onclick="DeleteObject(<?php echo $IdObjectDep; ?>)"></td>
                  <?php } ?>
                  <td>
                    <?php if ("$DepIdRunSh" != "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $DepIdRunSh; ?>,'user','<?php echo $DepTarget; ?>')"><?php } ?>
                    <?php if ("$DepRemIdRunSh" != "" and "$DepIdRunSh" == "") { ?><img class="ObjectImg" src="./images/LogProc.png" onclick="OpenLink(<?php echo $DepRemIdRunSh; ?> ,'work','<?php echo $DepTarget; ?>')"><?php } ?>
                  </td>
                  <td rowspan=2>
                    <?php
                    if ("$DepBg" == "Y") {
                    ?><B>EXEC<BR>NEXT</B><?php
                                  }
                                    ?>
                  </td>
                  <th style="width:30px !important">Meter</th>
                  <td style="width:200px !important">
                    <?php
                    if ("$DepOldTime" != "" and "$DepOldTime" != "0") {
                      $SecLast = $DepTime;
                      $SecPre = $DepOldTime;
                      if ("$SecPre" == "0") {
                        $SecPre = 1;
                      }
                      $Perc = round(($SecLast * 100) / $SecPre);


                      $SColor = "";
                      if ($Perc <= 100 and "$DepStatus" != "I") {
                        $SColor = "$BarraMeglio";
                      }
                      if ("$DepStatus" == "I") {
                        $SColor = "$BarraCaricamento";
                      }
                      if ($Perc > 120) {
                        $SColor = "$BarraPeggio";
                      }

                      if (
                        (1 == 1
                          //and ( $Perc > 120 or $Perc < 90 ) 
                          and  ("$DepStatus" == "F" or "$DepStatus" == "W")
                          and ($SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP)
                        )
                        or ("$DepStatus" == "I")
                      ) {
                    ?>
                        <div class="progress">
                          <div class="progress-bar progress-bar-striped <?php
                                                                        if ("$DepStatus" == "I") {
                                                                          echo "active";
                                                                        }
                                                                        ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;width: 2em; width: <?php
                                                                                                                                                                                    if ($Perc > 100) {
                                                                                                                                                                                      echo 100;
                                                                                                                                                                                    } else {
                                                                                                                                                                                      echo "$Perc";
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;"><LABEL style="font-weight: 500;"><?php
                                                                                                                                                  if ($Perc > 100) {
                                                                                                                                                    $Perc = $Perc - 100;
                                                                                                                                                    $Perc = "+" . $Perc;
                                                                                                                                                  } else {
                                                                                                                                                    if ("$DepStatus" != "I") {
                                                                                                                                                      $Perc = $Perc - 100;
                                                                                                                                                    }
                                                                                                                                                  }
                                                                                                                                                  echo $Perc;
                                                                                                                                                  ?>%</LABEL>
                          </div>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </td>
                  <th style="width:65px !important">Start Time</th>
                  <td style="width:145px !important"><?php echo "$DepStartTime"; ?></td>
                  <th style="width:45px !important">Duration</th>
                  <th style="width:45px !important">Old Time</th>
                  <th style="width:200px !important">Dependent</th>
                  <th>Enable From</th>
                  <th <?php if ("$DepValidTo"  == "2999-12-31") { ?> hidden <?php } ?>>Enable To</th>
                  <th <?php if ("$DepSetMonth"  == "") { ?> hidden <?php } ?>>Month</th>
                  <th <?php if ("$DepSetDay"    == "") { ?> hidden <?php } ?>>Day</th>
                  <th <?php if ("$DepSetHour"   == "") { ?> hidden <?php } ?>>Hour</th>
                  <th <?php if ("$DepSetMinute" == "") { ?> hidden <?php } ?>>Minute</th>
                  <th>Frequency</th>
                </tr>
                <tr>
                  <?php if ("$DepStatus" == "I") { ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php } else { ?>
                    <td><img class="ObjectImg" src="./images/Up.png" onclick="DownObject(<?php echo $IdObjectDep; ?>,<?php echo $PriorityDep; ?>,<?php echo $PriorityDep - 1; ?>)"></td>
                    <td><img class="ObjectImg" src="./images/Down.png" onclick="UpObject(<?php echo $IdObjectDep; ?>,<?php echo $PriorityDep; ?>,<?php echo $PriorityDep + 1; ?>)"></td>
                    <td><img class="ObjectImg" src="./images/Frist.png" onclick="DownObject(<?php echo $IdObjectDep; ?>,<?php echo $PriorityDep; ?>,1)"></td>
                    <td><img class="ObjectImg" src="./images/Last.png" onclick="UpObject(<?php echo $IdObjectDep; ?>,<?php echo $PriorityDep; ?>,0)"></td>
                    <td>
                      <?php if ("$DepLogSh" != "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $DepLogSh; ?>','LSH')"><?php } ?>
                      <?php if ("$DepRemLogSh" != "" and "$DepLogSh" == "") { ?><img class="ObjectImg" src="./images/Log.png" onclick="OpenFile('<?php echo $DepRemLogSh; ?>','LSH')"><?php } ?>
                    </td>
                  <?php } ?>
                  <th style="width:30px !important">Descr</th>
                  <td style="width:200px !important"><?php echo "$DepDescrObject"; ?></td>
                  <th>End Time</th>
                  <td width="145px"><?php echo "$DepEndTime"; ?></td>
                  <td style="width:45px !important"><?php if ("$DepTime" != "0" and "$DepTime" != "") {
                                                      echo floor(($DepTime - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $DepTime);
                                                    } ?></td>
                  <td style="width:45px !important"><?php if ("$DepOldTime" != "0"  and "$DepOldTime" != "") {
                                                      echo floor(($DepOldTime - 1) / (60 * 60 * 24)) . "g " . gmdate('H:i:s', $DepOldTime);
                                                    } ?></td>
                  <td class="DivDep<?php echo $IdObjectDep; ?>">
                    <?php if ($FindLinks != 0) {
                      $LisLnk = "";
                      foreach ($Arr_Objects as $Object2) {
                        $ObjId = $Object2[0];
                        $ObjTarget = $Object2[3];
                        $SelVariables = $Object2[4];
                        if (in_array($ObjId, $Arr_Links)) {
                          $LisLnk = $LisLnk . $ObjTarget . ' ' . $SelVariables . ',<BR>';
                        }
                      }
                      echo $LisLnk;
                    ?>
                      <script>
                        $('.Div<?php echo $IdObjectDep; ?>').mouseover(function() {
                          $('.DivDep<?php echo $IdObjectDep; ?>').css('background', 'SteelBlue');
                          $('.DivDep<?php echo $IdObjectDep; ?>').css('color', 'white');
                          <?php
                          foreach ($Arr_Objects as $Object2) {
                            $ObjId = $Object2[0];
                            $ObjTarget = $Object2[3];
                            $SelVariables = $Object2[4];
                            if (in_array($ObjId, $Arr_Links)) {
                          ?>
                              $('.DivNum<?php echo $ObjId; ?>').css('border', '2px solid SteelBlue');
                              $('.DivNum<?php echo $ObjId; ?>').css('color', 'white');
                          <?php
                            }
                          }
                          ?>
                        });
                        $('.Div<?php echo $IdObjectDep; ?>').mouseleave(function() {
                          $('.DivDep<?php echo $IdObjectDep; ?>').css('background', 'white');
                          $('.DivDep<?php echo $IdObjectDep; ?>').css('color', 'black');
                          <?php
                          foreach ($Arr_Objects as $Object2) {
                            $ObjId = $Object2[0];
                            $ObjTarget = $Object2[3];
                            $SelVariables = $Object2[4];
                            if (in_array($ObjId, $Arr_Links)) {
                          ?>
                              $('.DivNum<?php echo $ObjId; ?>').css('border', 'none');
                              $('.DivNum<?php echo $ObjId; ?>').css('color', 'black');
                          <?php
                            }
                          }
                          ?>
                        });
                      </script>
                    <?php
                    }
                    ?>
                  </td>
                  <td><?php echo "$DepValidFrom"; ?></td>
                  <td <?php if ("$DepValidTo"  == "2999-12-31") { ?> hidden <?php } ?>><?php echo "$DepValidTo"; ?></td>
                  <td <?php if ("$DepSetMonth"  == "") { ?> hidden <?php } ?>><?php echo "$DepSetMonth"; ?></td>
                  <td <?php if ("$DepSetDay"    == "") { ?> hidden <?php } ?>><?php echo "$DepSetDay"; ?></td>
                  <td <?php if ("$DepSetHour"   == "") { ?> hidden <?php } ?>><?php echo "$DepSetHour"; ?></td>
                  <td <?php if ("$DepSetMinute" == "") { ?> hidden <?php } ?>><?php echo "$DepSetMinute"; ?></td>
                  <td><?php echo "$DepFrequency"; ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
      <?php
                  if ("$IsFather" != "0") {
                    Sons($IdObjectDep, $conn, $Arr_Objects);
                  }
      ?>
    </li>
<?php

                }
              }
?>
</li>
</ul><?php
              db2_close($db2_conn_string);
            }
      ?>
<div id="DivCodaExec" hidden></div>
<div id="ShowCodaExec"></div>
    </form>
    <script>
      $('#ForceRun').click(function() {
        var re = confirm('Are you sure you want Force the Run?');
        if (re == true) {
          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "ForceRun")
            .val('Force Run');
          $('#Waiting').show();
          $('#FormScheduler').append($(input));
          $('#FormScheduler').submit();
        }
      });

      function TestNoAction() {
        <?php if ("$User" == "EUL1831" or "$User" == "RU17688") { ?>exit;
      <?php } ?>
      }


      function OpenGroup(vIdEmGroup, vNameGroup) {
        $('#IdEmGroup').val(vIdEmGroup);
        $('#NameGroup').val(vNameGroup);
        $('#FormScheduler').submit();
      }

      function OpenCoda(IdEmGroup) {

        $.ajax({
          method: "get",

          // specifico la URL della risorsa da contattare
          //url: './PHP/ExecManag_MostraStorico.php',
          url: 'index.php?controller2&action=codaexec&SelIdEmGroup=' + IdEmGroup,
          // imposto l'azione in caso di successo
          success: function(risposta) {

            //visualizzo il contenuto del file nel div htmlm


            $("#dialogMail").dialog({
              title: 'IN CODA'
            });
            $("#dialogMail").dialog("open");
            $("#dialogMail").html(risposta);
            $('#Waiting').hide();
            //$('#ShowDataElab').hide();
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato) {
            alert("Qualcosa è andato storto");
          }
        });
        /*  if ( $('#DivCodaExec').css('display') == 'none' ){
            $('#DivCodaExec').empty().load('./view/execmanag/CodaExec.php');
            $('#DivCodaExec').show();
          } else {
            $('#DivCodaExec').hide();
          }*/
      }
      /*
      function OpenCoda(){
      	if ( $('#DivCodaExec').css('display') == 'none' ){
      	  $('#DivCodaExec').empty().load('./view/execmanag/CodaExec.php',{'SelIdEmGroup':'<?php echo $IdEmGroup; ?>'});
      	  $('#DivCodaExec').show();
      	} else {
      	  $('#DivCodaExec').hide();
      	}
      }

      $('#DivCodaExec').empty().load('./view/execmanag/CodaExec.php',{'SelIdEmGroup':'<?php echo $IdEmGroup; ?>'});
      */
      function DeleteObject(vIdObject) {
        TestNoAction();
        var re = confirm('Are you sure you want Delete this object?');
        if (re == true) {
          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DeleteObject")
            .val(vIdObject);
          $('#FormScheduler').append($(input));
          $('#FormScheduler').submit();
        }
      }

      function EditObject(vIdObject) {
        TestNoAction();
        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "EditObject")
          .val(vIdObject);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
      }

      function CopyObject(vIdObject) {
        TestNoAction();
        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "AddObject")
          .val(vIdObject);
        $('#FormScheduler').append($(input));
        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "IsCopy")
          .val(vIdObject);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
      }

      function AddObject(vIdObject) {
        TestNoAction();
        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "AddObject")
          .val(vIdObject);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
      }

      function ChangeStatus(vIdObject, vStatus) {
        TestNoAction();
        var re = confirm('Are you sure you want change status of this object?');
        if (re == true) {
          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "ChangeStatus")
            .val(vIdObject);
          $('#FormScheduler').append($(input));
          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "OldStatus")
            .val(vStatus);
          $('#FormScheduler').append($(input));
          $('#FormScheduler').submit();

        }
      }

      function DownObject(vIdObject, vOldPriority, vNewPriority) {
        TestNoAction();
        if (vNewPriority != 0) {
          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "ChangePriority")
            .val(vIdObject);
          $('#FormScheduler').append($(input));

          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "OldPriority")
            .val(vOldPriority);
          $('#FormScheduler').append($(input));

          var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "NewPriority")
            .val(vNewPriority);
          $('#FormScheduler').append($(input));

          $('#FormScheduler').submit();
        }
      }

      function UpObject(vIdObject, vOldPriority, vNewPriority) {
        TestNoAction();
        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "ChangePriority")
          .val(vIdObject);
        $('#FormScheduler').append($(input));

        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "OldPriority")
          .val(vOldPriority);
        $('#FormScheduler').append($(input));

        var input = $("<input>")
          .attr("type", "hidden")
          .attr("name", "NewPriority")
          .val(vNewPriority);
        $('#FormScheduler').append($(input));

        $('#FormScheduler').submit();
      }


      function OpenFile(vIdObject, vType) {
        $.ajax({
          method: "get",

          // specifico la URL della risorsa da contattare
          url: 'index.php?controller=execmanag2&action=openschedfile&IDOBJ=' + vIdObject + '&TYPE=' + vType,
          // imposto l'azione in caso di successo
          success: function(risposta) {

            //visualizzo il contenuto del file nel div htmlm


            //	$("#Filedialog").dialog({title: titoloSh+" ( "+db_name+" )"});
            $("#Filedialog").dialog("open");
            $("#Filedialog").html(risposta);
            $('#Waiting').hide();
            $('#ShowDataElab').hide();
            $("textarea").css('height', ($(window).height() - 260));
            $("textarea").css('width', ($(window).width() - 160));
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato) {
            alert("Qualcosa è andato storto");
          }
        });



        // window.open('./PHP/OpenSchedFile.php?IDOBJ='+vIdObject+'&TYPE='+vType);
      }

      function OpenLink(vIdRunSh, db_name, titoloSh) {

        $.ajax({
          method: "get",

          // specifico la URL della risorsa da contattare
          url: 'index.php?controller=statoshell&action=contentList&IDSELEM=' + vIdRunSh + "&db_name=" + db_name,
          // imposto l'azione in caso di successo
          success: function(risposta) {

            //visualizzo il contenuto del file nel div htmlm


            $("#SHdialog").dialog({
              title: titoloSh + " ( " + db_name + " )"
            });
            $("#SHdialog").dialog("open");
            $("#SHdialog").html(risposta);
            $('#Waiting').hide();
            $('#ShowDataElab').hide();
          },
          //imposto l'azione in caso di insuccesso
          error: function(stato) {
            alert("Qualcosa è andato storto");
          }
        });







        //window.open('index.php?controller=statoshell&action=index&IDSELEM='+vIdRunSh+"&db_name=user");
      }




      $('#CancelEdit').click(function() {
        $('#Waiting').show();
        $('#FormScheduler').submit();
      });

      $('#CancelNew').click(function() {
        $('#Waiting').show();
        $('#FormScheduler').submit();
      });

      function Refresh() {
        $('#Waiting').show();
        $('#FormScheduler').submit();
      };

      $('#Waiting').hide();

      function OpenExecManagGroup() {
        $('#FormScheduler').attr('action', 'index.php?controller=execmanag2&action=index');
        $('#FormScheduler').submit();
      }


      $('#SelDescrObject').keyup(function() {
        var vtext = $('#SelDescrObject').val();
        vtext = vtext.replace('"', '\'\'');
        $('#SelDescrObject').val(vtext);
      });

      $('#FormScheduler').submit(function() {
        $('#TopScrollO').val($(window).scrollTop());
      });

      $(window).scrollTop($('#TopScrollO').val());


      <?php
      if ("$EditObject" == "" and "$AddObject" == "") {
      ?>
        if ($('#EnableRefresh').is(':checked')) {
          setInterval(function() {
            Refresh();
          }, 30000);
        } else {
          setInterval(function() {
            Refresh();
          }, 3600000);
        }
      <?php
      }
      ?>

      $('#SelVariables').keyup(function() {
        $('#SelVariables').val($('#SelVariables').val().replace("'", '"'));
      });
    </script>