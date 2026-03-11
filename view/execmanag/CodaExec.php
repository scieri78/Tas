<style>

</style>
<link rel="stylesheet" href="./CSS/excel.css">
<table class="ExcelTable">
    <tr><td colspan=4 style="font-size:15px; text-align:center;"><B>Esecuzioni Pendenti</B></td></tr>
    <tr>
    <th>Priorità</th>
    <th>Gruppo</th>
    <th>Lanci in Coda</th>
	<th>Time</th>
    </tr>
	<?php
	include './GESTIONE/connection.php';

//include './GESTIONE/sicurezza.php';
//if ( $find = 1 )  {
   
    $SelIdEmGroup=$_GET['SelIdEmGroup'];
 
    $conn = db2_connect($db2_conn_string, '', '');
      
    $sqlListGroup = "SELECT ID_EM_GROUP,NAME_GROUP,DESCR_GROUP,ENABLE,VALID_FROM,VALID_TO,STATUS,START_TIME,END_TIME,BCK_GRND,
    ( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP AND TMS_INSERT =
       (SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP)
    ) CREATOR,
    ( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'I' AND ID_EM_GROUP = SO.ID_EM_GROUP) IN_RUN,
    ( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ENABLE = 'N' AND ID_EM_GROUP = SO.ID_EM_GROUP) NOT_ENABLE,    
    ( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP = SO.ID_EM_GROUP ) FATHER
    FROM WORK_CORE.EXEC_MANAG_GROUPS SO
    ORDER BY NAME_GROUP";

    $stmt=db2_prepare($conn, $sqlListGroup);
    $result=db2_execute($stmt);

    if ( ! $result ){
      //  echo $sqlListGroup;
        echo "<br>ERROR 2.1 DB2:".$db2_conn_string."<br>";
    }
    $Arr_Groups=array();
   
    while ($row = db2_fetch_assoc($stmt)) {
      $IdEmGroup=$row['ID_EM_GROUP'];
      $NameGroup=$row['NAME_GROUP'];
      $DescrGroup=$row['DESCR_GROUP'];
      $Enable=$row['ENABLE'];
      $ValidFrom=$row['VALID_FROM'];
      $ValidTo=$row['VALID_TO'];
      $Status=$row['STATUS'];
      $StartTime=$row['START_TIME'];
      $EndTime=$row['END_TIME'];
      $Creator=$row['CREATOR'];
      $InRun=$row['IN_RUN'];
      $BckGrnd=$row['BCK_GRND'];
      $NotEnable=$row['NOT_ENABLE'];
      $IdFather=$row['FATHER'];
      

	  $Sqlg='CALL WORK_CORE.K_EXEC_MANAG.GroupTime(?,?,?)';
      $stmtg = db2_prepare($conn, $Sqlg);
	  
	  $AllVar='N';
	  $TrueTime=0;
	
      db2_bind_param($stmtg, 1,  "IdEmGroup", DB2_PARAM_IN);
      db2_bind_param($stmtg, 2,  "AllVar"   , DB2_PARAM_IN);
      db2_bind_param($stmtg, 3,  "TrueTime" , DB2_PARAM_OUT);
	  
      $resultg=db2_execute($stmtg);
      if ( ! $resultg ){
        echo "<br> ERROR 3 DB2 True Time: $TrueTime ".db2_stmt_errormsg($stmtg)."<br>";
      }
	  
      array_push($Arr_Groups,
          array(
          "$IdEmGroup",
          "$NameGroup",
          "$Enable",
          "$ValidFrom",
          "$ValidTo",
          "$Status",
          "$StartTime",
          "$EndTime",
          "$Creator",
          "$InRun",
          "$BckGrnd",
          "$NotEnable",
          "$DescrGroup",
          "$IdFather",
		  "$TrueTime"
          )
        );
    }

    $SumTime=0;
 
    function Sons($IdFather, $conn, $Arr_Groups, $Prio){
        
        global $TotCnt, $SelIdEmGroup, $SumTime;
        
        $Sonsql="SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
        FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
        WHERE ID_EM_GROUP = $IdFather
        ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";

        $Sonstmt=db2_prepare($conn, $Sonsql);
        $Sonresult=db2_execute($Sonstmt);

        if ( ! $Sonresult ){
           // echo $Sonsql;
            echo "<br>ERROR 1 DB2:".db2_stmt_errormsg($stmt)."<br>";
        }

        while ($Sonrow = db2_fetch_assoc($Sonstmt)) {
          $SonPriority=$Sonrow['PRIORITY'];
          $SonIdEmGroup=$Sonrow['ID_EM_GROUP'];
          $SonPriorityDep=$Sonrow['PRIORITY_DEP'];
          $SonIdEmGroupDep=$Sonrow['ID_EM_GROUP_DEP'];

      
          if ( "$SonIdEmGroupDep" != "" ){
                  $BackGrColSon="";
                  if ( "$SelIdEmGroup" == "$SonIdEmGroupDep" ){ $BackGrColSon=' style="background:yellow !important;"'; $SaveTime=$SumTime;  }
                  
                  foreach( $Arr_Groups as $Group ){
                      if ( "$Group[0]" == "$SonIdEmGroupDep" ){
                         $SonDepNameGroup=$Group[1];
                         $SonDepEnable=$Group[2];
                         $SonDepValidFrom=$Group[3];
                         $SonDepValidTo=$Group[4];
                         $SonDepStatus=$Group[5];
                         $SonDepStartTime=$Group[6];
                         $SonDepEndTime=$Group[7];
                         $SonDepCreator=$Group[8];
                         $SonDepInRun=$Group[9];
                         $SonDepBckGrnd=$Group[10];
                         $SonDepNotEnable=$Group[11];
                         $SonDepDescrGroup=$Group[12];
                         $TrueTime=$Group[14];			 
                         break;
                      }
                  }
                  
                  
                  if ( $SonDepEnable == 'Y' ){
                    
                    $sqlCnt="SELECT ID_OBJECT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'Y' AND ENABLE = 'Y' AND ID_EM_GROUP = '$SonIdEmGroupDep'";
                    $stmtCnt=db2_prepare($conn, $sqlCnt);
                    $resultCnt=db2_execute($stmtCnt);
                    $Cnt=0;
                    while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
                      $IdTest=$rowCnt['ID_OBJECT'];
                       
                      $O_TEST=0;
                      
                      $Sql2='CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestFatherGui(?,?)';
                      $stmt2 = db2_prepare($conn, $Sql2);
                          
                       db2_bind_param($stmt2, 1, "IdTest" , DB2_PARAM_IN);
                       db2_bind_param($stmt2, 2, "O_TEST" , DB2_PARAM_OUT);
                       
                       $result=db2_execute($stmt2);
                       if ( ! $result ){
                         echo "<br>ERROR 10 DB2 Em Group Sons:".db2_stmt_errormsg()."<br>";
                       }
                      
                       if ( "$O_TEST" == "0" ){ $Cnt=$Cnt+1; }
                    }
                    $TotCnt=$TotCnt+$Cnt;
                    if ( "$Cnt" != "0" ){
                      ?><tr>        
                      <td <?php echo $BackGrColSon; ?> ><?php echo $Prio.'-'.$SonPriorityDep; ?></td>
                      <td <?php echo $BackGrColSon; ?>  onclick="OpenGroup(<?php echo $SonIdEmGroupDep; ?>,'<?php echo $SonDepNameGroup; ?>')" style="cursor:pointer;" ><B><?php echo $SonDepNameGroup; ?></B></td>
                      <td <?php echo $BackGrColSon; ?> ><?php echo $Cnt; ?></td>
			          <td <?php echo $BackGrColDep; ?> ><?php echo  gmdate('H:i:s', $TrueTime);$SumTime=$SumTime+$TrueTime; ?></td>
                      </tr><?php
                    }
                     
                    Sons($SonIdEmGroupDep, $conn, $Arr_Groups, $Prio.'-'.$SonPriorityDep );
                  }
          }
        }
    }

    
    $sql="SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
    FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
    WHERE ID_EM_GROUP NOT IN ( SELECT ID_EM_GROUP_DEP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP IS NOT NULL )
    AND ID_EM_GROUP IN ( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS WHERE CURRENT_DATE BETWEEN VALID_FROM AND VALID_TO )
    ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";

    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
       // echo $sql;
        echo "<br>ERROR 6 DB2:".db2_stmt_errormsg($stmt)."<br>";
    }
    $OldIdEmGroup="";
    while ($row = db2_fetch_assoc($stmt)) {
      $Priority=$row['PRIORITY'];
      $IdEmGroup=$row['ID_EM_GROUP'];
      $PriorityDep=$row['PRIORITY_DEP'];
      $IdEmGroupDep=$row['ID_EM_GROUP_DEP'];
			
          foreach( $Arr_Groups as $Group ){
              if ( "$Group[0]" == "$IdEmGroup" ){
                 $NameGroup=$Group[1];
                 $Enable=$Group[2];
                 $ValidFrom=$Group[3];
                 $ValidTo=$Group[4];
                 $Status=$Group[5];
                 $StartTime=$Group[6];
                 $EndTime=$Group[7];
                 $Creator=$Group[8];
                 $InRun=$Group[9];
                 $BckGrnd=$Group[10];
                 $NotEnable=$Group[11];
                 $DescrGroup=$Group[12];
                 $TrueTime=$Group[14];			
                 break;
              }
          }

          
          if ( "$OldIdEmGroup" != "$IdEmGroup" ){
              
			  $BackGrCol="";
              if ( "$SelIdEmGroup" == "$IdEmGroup" ){ $BackGrCol=' style="background:yellow !important;"'; $SaveTime=$SumTime; }
      
    
              $OldIdEmGroup=$IdEmGroup;
              if ( "$Enable" == "Y" ){
                    $sqlCnt="SELECT ID_OBJECT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'Y' AND ENABLE = 'Y' AND ID_EM_GROUP = '$IdEmGroup'";
                    $stmtCnt=db2_prepare($conn, $sqlCnt);
                    $resultCnt=db2_execute($stmtCnt);
                    $Cnt=0;
                    while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
                      $IdTest=$rowCnt['ID_OBJECT'];
                      
                      $O_TEST=0;
                      
                      $Sql2='CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestFatherGui(?,?)';
                      $stmt2 = db2_prepare($conn, $Sql2);
                          
                       db2_bind_param($stmt2, 1, "IdTest"   , DB2_PARAM_IN);
                       db2_bind_param($stmt2, 2, "O_TEST"   , DB2_PARAM_OUT);
                       
                       $result=db2_execute($stmt2);
                       if ( ! $result ){
                         echo "ERROR 7 DB2 Em Group Father:".db2_stmt_errormsg()."<br>";
                       }
                      
                       if ( "$O_TEST" == "0" ){ $Cnt=$Cnt+1; }
                    }
                    $TotCnt=$TotCnt+$Cnt;
                    if ( "$Cnt" != "0" ){
                        ?><tr>        
                        <td <?php echo $BackGrCol; ?> ><?php echo $Priority; ?></td>
                        <td <?php echo $BackGrCol; ?>  onclick="OpenGroup(<?php echo $IdEmGroup; ?>,'<?php echo  $NameGroup; ?>')"  style="cursor:pointer;" ><B><?php echo $NameGroup; ?></B></td>
                        <td <?php echo $BackGrCol; ?> ><?php echo $Cnt; ?></td>
			            <td <?php echo $BackGrColDep; ?> ><?php echo  gmdate('H:i:s', $TrueTime); $SumTime=$SumTime+$TrueTime; ?></td>
                        </tr><?php
                    }
              }
          }

      if ( "$IdEmGroupDep" != "" and $Enable == 'Y' ){
          
	      $BackGrColDep="";
          if ( "$SelIdEmGroup" == "$IdEmGroupDep" ){ $BackGrColDep=' style="background:yellow !important;"';$SaveTime=$SumTime; }
      
          foreach( $Arr_Groups as $Group ){
              if ( "$Group[0]" == "$IdEmGroupDep" ){
                 $DepNameGroup=$Group[1];
                 $DepEnable=$Group[2];
                 $DepValidFrom=$Group[3];
                 $DepValidTo=$Group[4];
                 $DepStatus=$Group[5];
                 $DepStartTime=$Group[6];
                 $DepEndTime=$Group[7];
                 $DepCreator=$Group[8];
                 $DepInRun=$Group[9];
                 $DepBckGrnd=$Group[10];
                 $DepNotEnable=$Group[11];
                 $DepDescrGroup=$Group[12];
                 $TrueTime=$Group[14];			
                 break;
              }
          }
         
          if ( $DepEnable == 'Y' ){
            $sqlCnt="SELECT ID_OBJECT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'Y' AND ENABLE = 'Y' AND  ID_EM_GROUP = '$IdEmGroupDep'";
            $stmtCnt=db2_prepare($conn, $sqlCnt);
            $resultCnt=db2_execute($stmtCnt);
			$Cnt=0;
            while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
                $IdTest=$rowCnt['ID_OBJECT'];
                       
                $O_TEST=0;
                
                $Sql2='CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestFatherGui(?,?)';
                $stmt2 = db2_prepare($conn, $Sql2);
                    
                 db2_bind_param($stmt2, 1, "IdTest" , DB2_PARAM_IN);
                 db2_bind_param($stmt2, 2, "O_TEST" , DB2_PARAM_OUT);
                 
                 $result=db2_execute($stmt2);
                 if ( ! $result ){
                   echo "<br>ERROR 1 DB2 Em Group Son".db2_stmt_errormsg()."<br>";
                 }
				 
                 if ( "$O_TEST" == "0" ){ $Cnt=$Cnt+1; }
			}
            $TotCnt=$TotCnt+$Cnt;
            if ( "$Cnt" != "0" ){
              ?><tr>        
              <td <?php echo $BackGrColDep; ?> ><?php echo $Priority.'-'.$PriorityDep; ?></td>
              <td <?php echo $BackGrColDep; ?>  onclick="OpenGroup(<?php echo $IdEmGroupDep; ?>,'<?php echo $DepNameGroup; ?>')" style="cursor:pointer;" ><B><?php echo $DepNameGroup; ?></B></td>
              <td <?php echo $BackGrColDep; ?> ><?php echo $Cnt; ?></td>
			  <td <?php echo $BackGrColDep; ?> ><?php echo  gmdate('H:i:s', $TrueTime);$SumTime=$SumTime+$TrueTime; ?></td>
              </tr><?php
            }
            Sons($IdEmGroupDep, $conn, $Arr_Groups, $Priority.'-'.$PriorityDep);
          }
      }

    }

    ?>
    <tr><td colspan=2 >Totale <B><?php echo $TotCnt; ?></B> lanci in attesa</th><td><?php if ( "$SelIdEmGroup" != "" and "$SaveTime" != "0"  ) { ?>Prec.<?php echo gmdate('H:i:s', $SaveTime); } ?></td><td>Tot.<?php echo gmdate('H:i:s', $SumTime); ?></td></tr>
    </table>
    <?php   
    db2_close($db2_conn_string);
//}
?>
<script>
$('#ShowCoda').val('<?php echo $TotCnt; ?> IN CODA');
//setInterval(function(){ Refresh(); }, 30000);
</script>
