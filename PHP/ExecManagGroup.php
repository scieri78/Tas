<style>

#footer {
    height: 40px;
}

table{
    margin:2px;;
}

.TitShell{
    font-size: 10px;
    background: transparent;
}
.CheckLink{
    width: 100%;
    max-height: 150px;
    overflow: hidden;
    overflow-y: auto;
}
.GroupImg{
  float:left;
  width:20px;
  cursor:pointer;
  margin:2px;
}
.FolderImg{
  float:left;
  width:20px;
  margin:2px;
}
.GroupNameGroup{
  margin-left:4px;
  float:left;
  font-size:15px;
  cursor:pointer;
}
.GroupVaraibles{
  margin-left:4px;
  float:left;
  font-size:12px;
}

ul, li{
  list-style: none;
  font-weight: normal;
  text-decoration: none;
  position: relative;
  font-color: black;
  align:left;
}

.centerDiv{
  z-index:999;
  width:99%;
  height:350px;
  border:1px solid black;
  position:fixed;
  left:0;
  right:0;
  top:0;
  bottom:0;
  margin:auto;
  background:white;
  padding:5px;
  box-shadow: 0px 0px 20px 2px gray;
}

.Title{
  margin:4px;
  font-size:20px;
  left:0;
  right:0;
  margin:auto;
  width: max-content;
  color: blue;
}

.Button{
  width:145px;
  height:30px;
  border: 1px solid black;
  bachground:white;
  text-align: center;
  margin:5px;
  cursor:pointer;
}

select{
    width:100%;
}

.center{
left: 0;
right: 0;
margin: auto;
width:99%;
}

.Titolo{
    position:relative;
    left:0;
    right:0;
    margin:auto;
    font-size:20px;
    width:100%;
}

.ExcelTable TH{
    background:#E0E3DE;
}
input{
    width:100%;
}

#RefreshDiv{
    position: fixed;
    z-index: 100001;
    top: 28px;
    right: 20px;
}



#ShowCodaExec{
    position: fixed;
    z-index: 100001;
    top: 30px;
    right: 212px;
}

#InRun{
    position: fixed;
    z-index: 9999;
    bottom: 3px;
    left: 3px;  
    height: 30px;
}
.TitRun{
    float:left;
    width:65px;
    height:30px;
    font-size:20px;
}
.Run{
    float:left;
    left:5px;
    height:30px;
    font-size:20px;
    background:white;
    border:1px solid black;
    padding:2px;
}

#DivCodaExec{
    position:fixed;
    right: 50px;
    top:120px;
    bottom:0;
    width:400px;
    height:300px;
    box-shadow: 0px 0px 10px 0px solid black;
}

#MostraStorico{
    position:fixed;
    left:0px;
    right:0px;
    top:0px;
    bottom:0px;
    margin:auto;
    width:98%;
    height:400px;
    box-shadow: 0px 0px 13px 2px black;
    z-index: 100001;
    overflow:auto;
	background:white;
	padding:5px;
}

#PulMostraStorico{
    position: fixed;
    z-index: 100001;
    top: 30px;
    right: 350px;
    padding:6px;
    background-color:white;
}

.home{
    width:25px;
  margin:5px;
  cursor:pointer;   
}


#FindDiv{
    position:fixed;
    left:0px;
    right:0px;
    top:0px;
    bottom:0px;
    margin:auto;
    width:98%;
    height:400px;
    box-shadow: 0px 0px 13px 2px black;
    z-index: 100001;
    overflow:auto;
	background:white;
	padding:5px;
}

#Find{
	position:fixed;
	top:115px;
	left:5px;
	z-index:99999;
	text-align: center;
}
#Cestino{
	position:fixed;
	top:143px;
	left:5px;
	z-index:99999;
	text-align: center;
}
</style>
<form id="FormScheduler" method="POST" >
<?php 
$TopScrollG=$_POST['TopScrollG'];
$ListOpenP=$_POST['ListOpenP'];
$EnableRefresh=$_POST['EnableRefresh'];
$Ricerca=$_POST['Ricerca'];
$Search=$_POST['Search'];


include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {
?>
<div id="Find" onclick="$('#FindDiv').show();" ><img src="../images/Lente.png" class="home" ></div>
<div id="Cestino" onclick="OpenGroup(0,'CESTINO')" ><img src="../images/Cestino2.png" class="home" ></div>
<div id="FindDiv" <?php  if ( "$Ricerca" != "Ricerca" ) { ?> hidden <?php } ?> >
  <CENTER><BR>
  <input type="text" name="Search" id="Search" style="width:40%;" value="<?php  if ( "$Ricerca" != "Ricerca" ) { echo $Ricera;} ?>" >
  <input type="submit" name="Ricerca" value="Ricerca"  style="width:100px;" >
  <input type="submit" name="Chiudi" value="Chiudi" onclick="$('#FindDiv').hide();$('#Ricerca').val('');$('#Search').val('');" style="width:100px;">
  <?php
   if ( "$Search" != "" ){
   
   $sqlSearch = "SELECT G.ID_EM_GROUP, NAME_GROUP, TARGET_DIR, TARGET
   FROM 
   WORK_CORE.EXEC_MANAG_GROUPS   G
   JOIN
   WORK_CORE.EXEC_MANAG_OBJECTS  O
   ON G.ID_EM_GROUP = O.ID_EM_GROUP
   WHERE 1=1
       AND UPPER(TARGET) like UPPER('%$Search%')
    ORDER BY NAME_GROUP, TARGET_DIR, TARGET ";
     
    $stmts=db2_prepare($conn, $sqlSearch);
    $results=db2_execute($stmts);

    if ( ! $results ){
        echo $sqlSearch;
        echo "ERROR DB2:".db2_stmt_errormsg($stmts);
    }
    ?><table class="ExcelTable" >
	<tr>
	  <th>GROUP</th>
	  <th>TARGET_DIR</th>
	  <th>TARGET</th>
    </tr>
	<?php
    while ($rows = db2_fetch_assoc($stmts)) {
      $ID_GROUP=$rows['ID_EM_GROUP'];
	  $NAME_GROUP=$rows['NAME_GROUP'];
	  $TARGET_DIR=$rows['TARGET_DIR'];
	  $TARGET=$rows['TARGET'];
      ?><tr>
	    <td onclick="OpenGroup(<?php echo $ID_GROUP; ?>,'<?php echo $NAME_GROUP; ?>')" style="cursor:pointer;" ><B><?php echo $NAME_GROUP; ?></B></td>
		<td><?php echo $TARGET_DIR; ?></td>
		<td><?php echo $TARGET; ?></td>
		</tr><?php
    }  
    ?></table>
	</CENTER>
	<?php
   }
  ?>
</div>
<input type="hidden" name="ListOpenP" id="ListOpenP" value="<?php echo $ListOpenP; ?>">
<input type="hidden" id="TopScrollG" name="TopScrollG" value="<?php echo $TopScrollG; ?>" />
<div class="Titolo" ><CENTER>Execution Manager Groups</CENTER></div>
<table  id="RefreshDiv">
<tr>
<?php
$AddGroup=$_POST['AddGroup'];
$EditGroup=$_POST['EditGroup'];
if ( "$AddGroup" == "" and "$EditGroup" == "" ){
   ?>
   <td><input type="checkbox" name="EnableRefresh"  id="EnableRefresh" value="1" <?php if ( "$EnableRefresh" == "1" ) { ?>checked<?php } ?> title="AutoRefresh" ></td>
   <td><input class="Button" id="Refresh" style="background-color:yellow;" type="submit" value="REFRESH" onclick="Refresh()" ></td>
   <?php 
}
?>

<td><img class="GroupImg" src="../images/Add.png"  onclick="AddGroup(-1)" ></td>
</tr>
</table>
<?php
    
    $conn = db2_connect($db2_conn_string, '', '');


    $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.TestStatusGroup()';
    $stmt = db2_prepare($conn, $Sql);
    $result=db2_execute($stmt);
    
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
      
    $sqlListGroup = "SELECT ID_EM_GROUP,NAME_GROUP,DESCR_GROUP,ENABLE,VALID_FROM,VALID_TO,STATUS,START_TIME,END_TIME,BCK_GRND,
    ( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' AND TMS_INSERT =
       (SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' )
    ) EDITOR,
    TO_CHAR((SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'M' ),'YYYY-MM-DD HH24:MI:SS') TIME_EDITOR,
    ( SELECT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' AND TMS_INSERT =
       (SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' )
    ) CREATOR,  
    TO_CHAR((SELECT MIN(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_GROUPS_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'N' ),'YYYY-MM-DD HH24:MI:SS') TIME_CREATOR,
    ( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE STATUS = 'I' AND ID_EM_GROUP = SO.ID_EM_GROUP) IN_RUN,
    ( SELECT COUNT(*) FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ENABLE = 'N' AND ID_EM_GROUP = SO.ID_EM_GROUP) NOT_ENABLE,    
    ( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP = SO.ID_EM_GROUP ) FATHER,
    ( SELECT DISTINCT NOMINATIVO FROM WORK_CORE.EXEC_MANAG_AUDIT m, WEB.TAS_UTENTI u WHERE m.USERNAME=u.USERNAME AND ID_EM_GROUP = SO.ID_EM_GROUP AND EVENT_TYPE = 'S' AND TMS_INSERT IN
       ( SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT F WHERE F.ID_EM_GROUP = m.ID_EM_GROUP  AND F.EVENT_TYPE = 'S' )
    ) EXECUTOR, 
    TO_CHAR((SELECT MAX(TMS_INSERT) FROM WORK_CORE.EXEC_MANAG_AUDIT WHERE ID_EM_GROUP = SO.ID_EM_GROUP  AND EVENT_TYPE = 'S' ),'YYYY-MM-DD HH24:MI:SS') TIME_EXECUTOR
    FROM WORK_CORE.EXEC_MANAG_GROUPS SO
    ORDER BY NAME_GROUP";


//echo $sqlListGroup;
    $stmt=db2_prepare($conn, $sqlListGroup);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sqlListGroup;
        echo "ERROR DB2:".db2_stmt_errormsg($stmt);
    }
    $Arr_Groups=array();
    while ($row = db2_fetch_assoc($stmt)) {
      $IdEmGroup=$row['ID_EM_GROUP'];
      array_push($Arr_Groups, array("$IdEmGroup"));
    }


    $PowerStatus=$_POST['PowerStatus'];
    $Power=$_POST['Power'];
    if ( "$Power" != "" ){

          if ( "$PowerStatus" == "Y" ){ $PowerStatus='N'; } else { $PowerStatus='Y'; }

          $Sql="update WORK_CORE.EXEC_MANAG_GROUPS SET ENABLE='$PowerStatus' WHERE ID_EM_GROUP = $Power";
          $stmt = db2_prepare($conn, $Sql);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Delete Group:".db2_stmt_errormsg($stmt);
          }

    }



    $RecMode=$_POST['RecMode'];
    $RecStatus=$_POST['RecStatus'];
    
    $RecOn=$_POST['RecOn'];
    $RecOff=$_POST['RecOff'];
    if ( "$RecOn" != "" or "$RecOff" != "" ){

          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.RecursiveChangeGroup(?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);
          
          if ( "$RecOn" != "" ){ $RecVal=$RecOn; } else { $RecVal=$RecOff; }
          
          db2_bind_param($stmt, 1, "RecVal"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 2, "RecStatus"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 3, "RecMode"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 4, "User"       , DB2_PARAM_IN);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Delete Group:".db2_stmt_errormsg($stmt);
          }

    }


    $DepRun=$_POST['DepRun'];
    $DepNoRun=$_POST['DepNoRun'];
    if ( "$DepRun" != "" or "$DepNoRun" != "" ){

          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.RecursiveChangeGroup(?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);
          
          if ( "$DepRun" != "" ){ $RecVal=$DepRun; } else { $RecVal=$DepNoRun; }
          
          db2_bind_param($stmt, 1, "RecVal"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 2, "RecStatus"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 3, "RecMode"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 4, "User"       , DB2_PARAM_IN);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Delete Group:".db2_stmt_errormsg($stmt);
          }

    }   
    
    $DeleteGroup=$_POST['DeleteGroup'];
    if ( "$DeleteGroup" != "" ){

          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.DeleteGroup(?, ?)';
          $stmt = db2_prepare($conn, $Sql);

          db2_bind_param($stmt, 1, "DeleteGroup"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 2, "User"         , DB2_PARAM_IN);

          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Delete Group:".db2_stmt_errormsg($stmt);
          }

    }

    $SaveNew=$_POST['SaveNew'];
    if ( "$SaveNew" != "" ){

          $SelNameGroup  =$_POST['SelNameGroup'];
          $SelDescrGroup =$_POST['SelDescrGroup'];
          $SelEnable     =$_POST['SelEnable'];
          $SelBckGrnd    =$_POST['SelBckGrnd'];       
          $SelValidFrom  =$_POST['SelValidFrom'];
          $SelValidTo    =$_POST['SelValidTo'];
          $SelFather     =$_POST['SelFather'];
          
          $ListLinks="";
          foreach( $Arr_Groups as $Group2 ){
                  $ObjId=$Group2[0];
                  $Link=$_POST["Check".$ObjId];
                  if ( "$Link" == "$ObjId" ){
                    $ListLinks=$ListLinks.$Link.",";
                  }
          }
          if ( "$ListLinks" == "" ){ $ListLinks=""; }else{ $ListLinks=substr($ListLinks,0,-1); }
          
          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.SaveNew(?, ?, ?, ?, ?, ?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);

          db2_bind_param($stmt, 1, "SelNameGroup"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 2, "SelDescrGroup" , DB2_PARAM_IN);
          db2_bind_param($stmt, 3, "SelEnable"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 4, "SelBckGrnd"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 5, "SelValidFrom"  , DB2_PARAM_IN);
          db2_bind_param($stmt, 6, "SelValidTo"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 7, "User"          , DB2_PARAM_IN);
          db2_bind_param($stmt, 8, "SelFather"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 9, "ListLinks"     , DB2_PARAM_IN);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Save New:".db2_stmt_errormsg($stmt);
          }
          
    }

    $SaveEdit=$_POST['SaveEdit'];
    if ( "$SaveEdit" != "" ){
          $SelIdEmGroup  =$_POST['SelIdEmGroup'];
          $SelNameGroup  =$_POST['SelNameGroup'];
          $SelDescrGroup =$_POST['SelDescrGroup'];
          $SelEnable     =$_POST['SelEnable'];
          $SelBckGrnd    =$_POST['SelBckGrnd']; 
          $SelValidFrom  =$_POST['SelValidFrom'];
          $SelValidTo    =$_POST['SelValidTo'];
          $SelFather     =$_POST['SelFather'];

          $ListLinks="";
          foreach( $Arr_Groups as $Group2 ){
                  $ObjId=$Group2[0];
                  $Link=$_POST["Check".$ObjId];
                  if ( "$Link" == "$ObjId" ){
                    $ListLinks=$ListLinks.$Link.",";
                  }
          }
          if ( "$ListLinks" == "" ){ $ListLinks=""; }else{ $ListLinks=substr($ListLinks,0,-1); }
                  
          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.SaveEdit(?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);

          $Error=0;
          $Note="";

          db2_bind_param($stmt,  1, "SelIdEmGroup"  , DB2_PARAM_IN);
          db2_bind_param($stmt,  2, "SelNameGroup"  , DB2_PARAM_IN);
          db2_bind_param($stmt,  3, "SelDescrGroup" , DB2_PARAM_IN);
          db2_bind_param($stmt,  4, "SelEnable"     , DB2_PARAM_IN);
          db2_bind_param($stmt,  5, "SelBckGrnd"    , DB2_PARAM_IN);
          db2_bind_param($stmt,  6, "SelValidFrom"  , DB2_PARAM_IN);
          db2_bind_param($stmt,  7, "SelValidTo"    , DB2_PARAM_IN);
          db2_bind_param($stmt,  8, "User"          , DB2_PARAM_IN);
          db2_bind_param($stmt,  9, "SelFather"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 10, "ListLinks"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 11, "Error"         , DB2_PARAM_OUT);
          db2_bind_param($stmt, 12, "Note"          , DB2_PARAM_OUT);

          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Save <B>Edit:</B> ".db2_stmt_errormsg($stmt);
          }

          if ( $Error != 0 ){
              echo "NOTE: $Note";
          }
          $SelIdEmGroup="";
    }

    $ChangeStatus=$_POST['ChangeStatus'];
    if ( "$ChangeStatus" != "" ){
        $SelIdEmGroup=$ChangeStatus;
        $OldStatus=$_POST['OldStatus'];
        $NewStatus="Y";
        if ( "$OldStatus" == "Y" ){
            $NewStatus="N";
        }

        $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.ChangeGroupStatus(?, ?, ?)';
        $stmt = db2_prepare($conn, $Sql);

        db2_bind_param($stmt, 1, "SelIdEmGroup"  , DB2_PARAM_IN);
        db2_bind_param($stmt, 2, "NewStatus"    , DB2_PARAM_IN);
        db2_bind_param($stmt, 3, "User"         , DB2_PARAM_IN);

        $result=db2_execute($stmt);
        if ( ! $result ){
          echo "ERROR DB2 Save Change Status:".db2_stmt_errormsg($stmt);
        }

        $SelIdEmGroup="";
    }

    $ChangePriority=$_POST['ChangePriority'];
    if ( "$ChangePriority" != "" ){

          $OldPriority=$_POST['OldPriority'];
          $NewPriority=$_POST['NewPriority'];

          $Sql='CALL WORK_CORE.K_EXEC_MANAG_GROUP.ChangePriority(?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);

          db2_bind_param($stmt, 1,  "ChangePriority", DB2_PARAM_IN);
          db2_bind_param($stmt, 2,  "OldPriority"   , DB2_PARAM_IN);
          db2_bind_param($stmt, 3,  "NewPriority"   , DB2_PARAM_IN);

          $result=db2_execute($stmt);
          if ( ! $result ){
            echo "ERROR DB2 Change Priority: ".db2_stmt_errormsg($stmt);
          }
    }


    $stmt=db2_prepare($conn, $sqlListGroup);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sqlListGroup;
        echo "ERROR DB2:".db2_stmt_errormsg($stmt);
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
      $Editor=$row['EDITOR'];
      $InRun=$row['IN_RUN'];
      $BckGrnd=$row['BCK_GRND'];
      $NotEnable=$row['NOT_ENABLE'];
      $IdFather=$row['FATHER'];
      $TimeCreator=$row['TIME_CREATOR'];
      $TimeEditor=$row['TIME_EDITOR'];
      $Executor=$row['EXECUTOR'];
      $TimeExecutor=$row['TIME_EXECUTOR'];
      
      
      $Sqlg='CALL WORK_CORE.K_EXEC_MANAG.GroupTime(?,?,?)';
      $stmtg = db2_prepare($conn, $Sqlg);
      
      $AllVar='Y';
      $TrueTime=0;
    
      db2_bind_param($stmtg, 1,  "IdEmGroup", DB2_PARAM_IN);
      db2_bind_param($stmtg, 2,  "AllVar"   , DB2_PARAM_IN);
      db2_bind_param($stmtg, 3,  "TrueTime" , DB2_PARAM_OUT);
      
	  if ( "$TrueTime" == "" ){$TrueTime=0;}
	  
      $resultg=db2_execute($stmtg);
      if ( ! $resultg ){
        echo "ERROR DB2 True Time: $TrueTime ".db2_stmt_errormsg($stmtg);
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
          "$TrueTime",
          "$Editor",
          "$TimeCreator",
          "$TimeEditor",
          "$Executor",
          "$TimeExecutor"
          )
        );
    }


   ?><div id="InRun" >
   <div class="TitRun" >In Run</div>
   <?php
   foreach( $Arr_Groups as $Group ){
      if ( "$Group[9]" != "0" ){
         $NGroup=$Group[1];
         ?><div class="Run" ><img class="FolderImg" src="../images/StatusI.png"><?php echo $NGroup; ?></div><?php        
      }
   }
   ?></div><?php


    $EditGroup=$_POST['EditGroup'];
    if ( "$EditGroup" != "" ){
        foreach( $Arr_Groups as $Group ){
           $ObjId=$Group[0];
           if ( "$ObjId" == "$EditGroup" ){
                 $SelNameGroup=$Group[1];
                 $SelEnable   =$Group[2];
                 $SelValidFrom=$Group[3];
                 $SelValidTo  =$Group[4];
                 $SelStatus   =$Group[5];
                 $SelStartTime=$Group[6];
                 $SelEndTime  =$Group[7];
                 $SelCreator  =$Group[8];
                 $SelBckGrnd  =$Group[10];
                 $SelDescrGroup  =$Group[12];
                 $SelIdFather  =$Group[13];
           }
        }

                
          $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $EditGroup";
          $stmtLink=db2_prepare($conn, $sqlLink);
          $resultLink=db2_execute($stmtLink);

          if ( ! $resultLink ){
              echo $sqlLink;
              echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
          }

          $FindLinks=0;
          $Arr_Links=array();
          while ($rowLink = db2_fetch_assoc($stmtLink)) {
            $IdLink=$rowLink['ID_EM_GROUP_LINK'];
            array_push($Arr_Links, $IdLink);
          }
           
       ?>
       <div id="EditDiv" class="centerDiv" >
            <div class="Title" ><img src="../images/Folder.png" width="25px" >Edit <?php echo "$SelNameGroup"; ?></div>
            <table class="center">
            <td style="width:50%;" >
            <table class="center">
              <tr><th>Name</th><td>
              <input type="hidden" name="SelIdEmGroup" id="SelIdEmGroup" value="<?php echo "$EditGroup"; ?>" >
              <input type="text" name="SelNameGroup" id="SelNameGroup" value="<?php echo "$SelNameGroup"; ?>" >
              </td></tr>
              <tr><th>Description</th><td>
              <input type="text" name="SelDescrGroup" id="SelDescrGroup" value="<?php echo "$SelDescrGroup"; ?>" >
              </td></tr>              
              <tr><th>Enable</th><td>
              <select name="SelEnable" id="SelEnable" >
                    <option value="Y" <?php if ( "$SelEnable" == "Y" ) { ?> selected <?php } ?> >Enable</option>
                    <option value="N" <?php if ( "$SelEnable" == "N" ) { ?> selected <?php } ?> >Disable</option>
              </select>
              </td></tr>              
              <tr><th>Exec Next</th><td>
              <select name="SelBckGrnd" id="SelBckGrnd" >
                    <option value="N" <?php if ( "$SelBckGrnd" == "N" ) { ?> selected <?php } ?> >Disable</option>
                    <option value="Y" <?php if ( "$SelBckGrnd" == "Y" ) { ?> selected <?php } ?> >Enable</option>
              </select>
              </td></tr>
              <tr><th>ValidFrom</th><td><input type="date" name="SelValidFrom" id="SelValidFrom" value="<?php echo "$SelValidFrom"; ?>" ></td></tr>
              <tr><th>ValidTo</th><td><input type="date" name="SelValidTo" id="SelValidTo" value="<?php echo "$SelValidTo"; ?>" ></td></tr>
              </td></tr>
           </table>
           </td>
           <td style="width:50%;" >
           <table class="center">
              <tr><th colspan=2 >Son of</th></tr>
                  <tr><td colspan=2 ><select name="SelFather" id="SelFather" >
                <option value="0" <?php if ( "$SelIdFather" == "-1" ) { ?> selected <?php } ?> >Nothing</option>
                <?php
                foreach( $Arr_Groups as $Group2 ){
                  $IdEmGroup2=$Group2[0];
                  $NameGroup2=$Group2[1];
                  $Enable2   =$Group2[2];
                  $DescrGroup2  =$Group2[12];
                  if ( "$IdEmGroup2" != "$EditGroup"){
                      ?><option value="<?php echo $IdEmGroup2; ?>" <?php if ( "$SelIdFather" == "$IdEmGroup2" ) { ?> selected <?php } ?> ><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></option><?php
                  }
                } 
                ?>
              </select>
              </td></tr>
              <tr class="NoDep"><th colspan=2 >Dependent to</th></tr>
              <tr class="NoDep">
              <td colspan=2 >
                <div class="CheckLink" >
                <table width="100%">
                <?php
                foreach( $Arr_Groups as $Group2 ){
                  $IdEmGroup2=$Group2[0];
                  $NameGroup2=$Group2[1];
                  $Enable2   =$Group2[2];
                  $DescrGroup2  =$Group2[12];
                  $Checked="";
                  if ( in_array( $IdEmGroup2, $Arr_Links ) ){ $Checked="Checked"; }
                  if ( $IdEmGroup2 != $EditGroup && $IdEmGroup2 != $SelIdFather ){
                    ?>
                    <tr>
                    <td width="20px"><input type="checkbox" name="Check<?php echo $IdEmGroup2; ?>" id="Check<?php echo $IdEmGroup2; ?>" value="<?php echo $IdEmGroup2; ?>" <?php echo $Checked; ?> ></td>
                    <td><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
                </table>
                </div>
              </td></tr>
            </table>
            </td>
            </tr>
            </table>
            <center>
            <input class="Button" type="submit" id="SaveEdit" name="SaveEdit" value="Save" >
            <input class="Button" id="CancelEdit" type="submit" value="Cancel" >
            </center>
       </div>
       <?php
    }

    $AddGroup=$_POST['AddGroup'];
    if ( "$AddGroup" != "" ){
           $SelNameGroup="";
           $SelDescrGroup="";
           $SelEnable="";
           $SelBckGrnd="";
           $SelValidFrom=date("Y-m-d");
           $SelValidTo="2999-12-31";
           ?>
           <div id="NewDiv" class="centerDiv" >
                <div class="Title" ><img src="../images/Folder.png" width="25px" >New Group</div>
                <table class="center">
                <td style="width:50%;" >
                  <table class="center">
                  <tr><th>Name</th><td>
                  <input type="text" name="SelNameGroup" id="SelNameGroup" value="<?php echo "$SelNameGroup"; ?>" >
                  </td></tr>
                  <tr><th>Description</th><td>
                  <input type="text" name="SelDescrGroup" id="SelDescrGroup" value="<?php echo "$SelDescrGroup"; ?>" >
                  </td></tr>
                  <tr><th>Enable</th><td>
                  <select name="SelEnable" id="SelEnable" >
                    <option value="Y" <?php if ( "$SelEnable" == "Y" ) { ?> selected <?php } ?> >Enable</option>
                    <option value="N" <?php if ( "$SelEnable" == "N" ) { ?> selected <?php } ?> >Disable</option>
                    <option value="S" <?php if ( "$SelEnable" == "S" ) { ?> selected <?php } ?> >Skip</option>
                  </select>
                  </td></tr>              
                  <tr><th>Exec Next</th><td>
                  <select name="SelBckGrnd" id="SelBckGrnd" >
                        <option value="N" <?php if ( "$SelBckGrnd" == "N" ) { ?> selected <?php } ?> >Disable</option>
                        <option value="Y" <?php if ( "$SelBckGrnd" == "Y" ) { ?> selected <?php } ?> >Enable</option>
                  </select>
                  </td></tr>
                  <tr><th>ValidFrom</th><td><input type="date" name="SelValidFrom" id="SelValidFrom" value="<?php echo "$SelValidFrom"; ?>" ></td></tr>
                  <tr><th>ValidTo</th><td><input type="date" name="SelValidTo" id="SelValidTo" value="<?php echo "$SelValidTo"; ?>" ></td></tr>
                  </table>
                 </td>
                 <td style="width:50%;" >
                  <table class="center">
                     <tr><th colspan=2 >Son of</th></tr>
                      <tr><td colspan=2 >
                      <select name="SelFather" id="SelFather" >
                    <option value="0" <?php if ( "$AddGroup" == "-1" ) { ?> selected <?php } ?> >Nothing</option>
                    <?php
                    foreach( $Arr_Groups as $Group2 ){
                      $IdEmGroup2=$Group2[0];
                      $NameGroup2=$Group2[1];
                      $Enable2   =$Group2[2];
                      $DescrGroup2  =$Group2[12];
                      ?><option value="<?php echo $IdEmGroup2; ?>" <?php if ( "$AddGroup" == "$IdEmGroup2" ) { ?> selected <?php } ?> ><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></option><?php
                    } 
                    ?>
                  </select>
                  </td></tr>
                  <tr class="NoDep"><th colspan=2 >Dependent to</th></tr>
                  <tr class="NoDep">
                  <td colspan=2 >
                    <div class="CheckLink" >
                    <table width="100%">
                    <?php
                    foreach( $Arr_Groups as $Group2 ){
                      $IdEmGroup2=$Group2[0];
                      $NameGroup2=$Group2[1];
                      $Enable2   =$Group2[2];
                      $DescrGroup2  =$Group2[12];
                      $Checked="";
                      if ( in_array( $IdEmGroup2, $Arr_Links ) ){ $Checked="Checked"; }
                      if ( $IdEmGroup2 != $EditGroup ){
                        ?>
                        <tr>
                        <td width="20px"><input type="checkbox" name="Check<?php echo $IdEmGroup2; ?>" id="Check<?php echo $IdEmGroup2; ?>" value="<?php echo $IdEmGroup2; ?>" <?php echo $Checked; ?> ></td>
                        <td><?php echo $NameGroup2; if ( "$DescrGroup2" != "" ){ echo " ( $DescrGroup2 )"; } ?></td>
                        </tr>
                        <?php
                      }
                    }
                    ?>
                    </table>
                    </div>
                  </td></tr>
                </table>
                </td>
                </tr>
                </table>                
                <center>
                <input class="Button" type="submit" id="SaveNew" name="SaveNew"  value="Save" onclick="validatorGruop()" >
                <input class="Button" id="CancelNew" type="submit" value="Cancel"  >
                </center>
                <script>

                  function validatorGruop() {
                                  var nomeGruppo = $('#SelNameGroup').val();
                                  var errorMessage = '';

                                  if (nomeGruppo.length === 0) {
                                      errorMessage = 'Il campo Name è obbligatorio.';
                                  } else if (nomeGruppo.length > 50) {
                                      errorMessage = 'Il Name del gruppo non può superare i 50 caratteri.';
                                  }

                                  if (errorMessage) {
                                      alert(errorMessage);
                                      return false; // Impedisce l'invio del modulo
                                  } else {
                                  //    $('#error-message').text('');
                                      return true; // Permette l'invio del modulo
                                  }
                              }


                </script>
           </div>
           <?php
    }

 
    function Sons($IdFather, $conn, $Arr_Groups){

        $Sonsql="SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
        FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
        WHERE ID_EM_GROUP = $IdFather
        ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";

        $Sonstmt=db2_prepare($conn, $Sonsql);
        $Sonresult=db2_execute($Sonstmt);

        if ( ! $Sonresult ){
            echo $Sonsql;
            echo "ERROR DB2:".db2_stmt_errormsg($stmt);
        }

        ?><ul><?php
        while ($Sonrow = db2_fetch_assoc($Sonstmt)) {
          $SonPriority=$Sonrow['PRIORITY'];
          $SonIdEmGroup=$Sonrow['ID_EM_GROUP'];
          $SonPriorityDep=$Sonrow['PRIORITY_DEP'];
          $SonIdEmGroupDep=$Sonrow['ID_EM_GROUP_DEP'];

          if ( "$SonIdEmGroupOld" != "$SonIdEmGroup" ){

              if ( "$SonIdEmGroupOld" != "" ){
                ?></ul></li><?php
              }
              $SonIdEmGroupOld=$SonIdEmGroup;
          }

          if ( "$SonIdEmGroupDep" != "" ){

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
                         $Editor=$Group[15];
                         $TimeCreator=$Group[16];
                         $TimeEditor=$Group[17];
                         $Executor=$Group[18];
                         $TimeExecutor=$Group[19];
                         break;
                      }
                  }
                  $lidisable="";
                  $SonDepBkgr="";
                  if ( "$SonDepEnable" == "N" ){
                    $SonDepBkgr="background-color:#e12c2c;";
                    $lidisable='style="background-color:#c89696;"';
                  }
                  if ( "$SonDepEnable" == "S" ){
                    $SonDepBkgr="background-color:#9697c8;";
                  }
                  if ( "$SonDepInRun" != "0" ){
                    $SonDepBkgr="background-color:#ffcb00;";
                  }
                  
                
                  $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $SonIdEmGroupDep";
                  $stmtLink=db2_prepare($conn, $sqlLink);
                  $resultLink=db2_execute($stmtLink);

                  if ( ! $resultLink ){
                      echo $sqlLink;
                      echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
                  }

                  $FindLinks=0;
                  $Arr_Links=array();
                  while ($rowLink = db2_fetch_assoc($stmtLink)) {
                    $IdLink=$rowLink['ID_EM_GROUP_LINK'];
                    array_push($Arr_Links, $IdLink);
                    $FindLinks=1;
                  }
                  
                  $sqlCnt="SELECT count(*) CNT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_EM_GROUP = $SonIdEmGroupDep";
                  $stmtCnt=db2_prepare($conn, $sqlCnt);
                  $resultCnt=db2_execute($stmtCnt);
                  while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
                    $CntDep=$rowCnt['CNT'];
                  }


                ?>
                <li <?php echo $lidisable; ?>>
                    <div class="Group Div<?php echo $SonIdEmGroupDep ?>" >
                    <table>
                    <tr>
                    <td style="width:20px;<?php echo $SonDepBkgr; ?>" class="DivNum<?php echo $SonIdEmGroupDep; ?>" ><?php echo $SonPriorityDep; ?></td>
                    <td>
                       <table class="ExcelTable" >
                       <tr>
                            <th rowspan=2 style="background:white;" >
                             <table class="TitShell" >
                             <tr><td style="min-width:200px !important" >
                             <img class="FolderImg" src="../images/Folder.png" >
                             <?php 
                             if ( "$SonDepInRun" != "0" ){ 
                               ?><img class="FolderImg" src="../images/StatusI.png" ><?php
                             }               
                             if ( "$SonDepStatus" != "I" ){ 
                               ?><img class="FolderImg" src="../images/Status<?php echo "$SonDepStatus"; ?>.png" ><?php
                             }
                             if ( "$SonDepNotEnable" != "0" ){ 
                               ?><img class="FolderImg" src="../images/Attention.png" ><?php
                             }                               
                             ?>                          
                             <div class="GroupNameGroup"  onclick="OpenGroup(<?php echo $SonIdEmGroupDep; ?>,'<?php echo $SonDepNameGroup; ?>')" title="<?php echo $SonIdEmGroupDep; ?>" ><?php echo "$SonDepNameGroup [$CntDep]"; ?></div>
                             </td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Creator:</B> <?php echo "$SonDepCreator - $TimeCreator"; ?></div></td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div></td></tr>
                             <tr><td><div class="GroupNameGroupDir" ><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div></td></tr>
                             </table>
                            </th>
                            <td><img title="Set to No Run"    class="GroupImg" src="../images/DepNoRun.png"     onclick="DepNoRun(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <td><img title="Recursive set to  No Run" class="GroupImg" src="../images/RecOff.png"     onclick="RTecOff(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <td><img title="Add" class="GroupImg" src="../images/Add.png"      onclick="AddGroup(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                            <?php if ( "$SonDepStatus" == "I" or "$SonDepInRun" != "0" ) { ?>
                            <td></td>
                            <td></td>
                            <?php } else { ?>                   
                            <td><img title="Modify" class="GroupImg" src="../images/Setting.png"    onclick="EditGroup(<?php echo $SonIdEmGroupDep; ?>)"    ></td>
                            <td>
                            <?php if ( "$SonIdEmGroupDep" != "0" ) { ?>
                               <img title="Delete" class="GroupImg" src="../images/Remove.png"   onclick="DeleteGroup(<?php echo $SonIdEmGroupDep; ?>)" >
                            <?php } ?>
                            </td>
                            <?php } ?>
                            <td><img title="Enable/Disable" class="GroupImg" src="../images/Power.png"      onclick="Power(<?php echo $SonIdEmGroupDep; ?>,'<?php echo $SonDepEnable; ?>')" ></td>
                            <td rowspan=2>
                            <?php if ( "$SonDepBckGrnd" == "Y" ) {
                               /* ?><img class="GroupImg" src="../images/Background.png"  title="accepts parallels"><?php */
                               ?><B>EXEC<BR>NEXT</B><?php                              
                            } ?>
                            </td>
                            <th style="min-width:100px !important"  >Description</th>
                            <th style="min-width:65px !important"  >Start Time</th>
                            <td style="min-width:100px !important" ><?php echo "$SonDepStartTime"; ?></td>                           
                            <th style="min-width:65px !important"  >True Time</th>
                            <th>Enable From</th>
                            <?php if ( "$SonDepValidTo" != "2999-12-31" ) { ?><th>Enable To</th><?php } ?>
                            <th style="width:200px !important" >Dependent</th>
                       </tr>
                       <tr>
                             <td><img title="Set to Run"    class="GroupImg" src="../images/DepRun.png"     onclick="DepRun(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                             <td><img title="Recursive set to Run" class="GroupImg" src="../images/RecOn.png"     onclick="RTecOn(<?php echo $SonIdEmGroupDep; ?>)" ></td>
                             <?php if ( "$SonDepStatus" == "I" or "$SonDepInRun" != "0" ) { ?>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <?php } else { ?>                  
                             <td><img title="Up Priority"    class="GroupImg" src="../images/Up.png"       onclick="DownGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep-1; ?>)" ></td>
                             <td><img title="Down Priority"  class="GroupImg" src="../images/Down.png"     onclick="UpGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,<?php echo $SonPriorityDep+1; ?>)" ></td>
                             <td><img title="First Priority" class="GroupImg" src="../images/Frist.png"    onclick="DownGroup(<?php echo $IdEmGroup; ?>,<?php echo $SonPriorityDep; ?>,1)" ></td>
                             <td><img title="Last Priority"  class="GroupImg" src="../images/Last.png"     onclick="UpGroup(<?php echo $SonIdEmGroupDep; ?>,<?php echo $SonPriorityDep; ?>,0)" ></td>
                             <?php } ?>
                             <td><div width="100px" ><?php echo "$SonDepDescrGroup"; ?></div></td>
                             <th>End Time</th>
                             <td width="100px" ><?php echo "$SonDepEndTime"; ?></td>
                             <td style="min-width:100px !important" ><?php if ( "$TrueTime" != "0" and "$TrueTime" != "" ) { echo  floor(($TrueTime-1)/(60*60*24))."g ".gmdate('H:i:s', $TrueTime); } ?></td>
                             <td><?php echo "$SonDepValidFrom"; ?></td>
                             <?php if ( "$SonDepValidTo" != "2999-12-31" ) { ?><td><?php echo "$SonDepValidTo"; ?></td><?php } ?>
                             <td class="DivDep<?php echo $SonIdEmGroupDep; ?>">
                                <?php if ( $FindLinks != 0 ){
                                    $LisLnk="";  
                                    foreach( $Arr_Groups as $Group2 ){
                                      $IdEmGroup2=$Group2[0];
                                      $NameGroup2=$Group2[1];
                                      $Enable2   =$Group2[2];
                                      $DescrGroup2  =$Group2[12];
                                        if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                            $LisLnk=$LisLnk.$NameGroup2.' '.$DescrGroup2.',<BR>';
                                        }
                                    }
                                    echo $LisLnk;
                                    ?>
                                    <script>
                                         $('.Div<?php echo $SonIdEmGroupDep; ?>').mouseover(function(){
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('background','SteelBlue');
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('color','white');
                                               <?php
                                                foreach( $Arr_Groups as $Group2 ){
                                                  $IdEmGroup2=$Group2[0];
                                                  $NameGroup2=$Group2[1];
                                                  $Enable2   =$Group2[2];
                                                  $DescrGroup2  =$Group2[12];
                                                    if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                        ?>
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','2px solid SteelBlue');
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','white');
                                                        <?php
                                                    }   
                                               }
                                               ?>
                                         });
                                         $('.Div<?php echo $SonIdEmGroupDep; ?>').mouseleave(function(){
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('background','white');
                                               $('.DivDep<?php echo $SonIdEmGroupDep; ?>').css('color','black');
                                               <?php
                                                foreach( $Arr_Groups as $Group2 ){
                                                  $IdEmGroup2=$Group2[0];
                                                  $NameGroup2=$Group2[1];
                                                  $Enable2   =$Group2[2];
                                                  $DescrGroup2  =$Group2[12];
                                                    if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                        ?>
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','none');
                                                        $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','black');
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
                       </tr>
                       </table>
                    </td>
                    </tr>
                    </table>
                  </div>                
                <?php
                Sons($SonIdEmGroupDep, $conn, $Arr_Groups);
                //foreach( $Arr_Links as $Father ){
                //   Sons($Father, $conn, $Arr_Groups);
                //}
                ?>
                </li>
                <?php


          }

        }
        ?></li></ul><?php

    }


    $sql="SELECT PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP
    FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP SP
    WHERE ID_EM_GROUP NOT IN ( SELECT ID_EM_GROUP_DEP FROM WORK_CORE.EXEC_MANAG_GROUPS_DEP WHERE ID_EM_GROUP_DEP IS NOT NULL )
    AND ID_EM_GROUP IN ( SELECT ID_EM_GROUP FROM WORK_CORE.EXEC_MANAG_GROUPS 
    --WHERE CURRENT_DATE BETWEEN VALID_FROM AND VALID_TO 
    )
    ORDER BY PRIORITY,ID_EM_GROUP,PRIORITY_DEP,ID_EM_GROUP_DEP";

    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2:".db2_stmt_errormsg($stmt);
    }

    $IdEmGroupOld="";

    ?><ul><?php
    while ($row = db2_fetch_assoc($stmt)) {
      $Priority=$row['PRIORITY'];
      $IdEmGroup=$row['ID_EM_GROUP'];
      $PriorityDep=$row['PRIORITY_DEP'];
      $IdEmGroupDep=$row['ID_EM_GROUP_DEP'];

      if ( "$IdEmGroupOld" != "$IdEmGroup" ){

          if ( "$IdEmGroupOld" != "" ){
            ?></ul></li><?php
          }

          $IdEmGroupOld=$IdEmGroup;

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
                 $Editor=$Group[15];
                 $TimeCreator=$Group[16];
                 $TimeEditor=$Group[17];
                 $Executor=$Group[18];
                 $TimeExecutor=$Group[19];
                 
                 break;
              }
          }

         $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $IdEmGroup";
          $stmtLink=db2_prepare($conn, $sqlLink);
          $resultLink=db2_execute($stmtLink);

          if ( ! $resultLink ){
              echo $sqlLink;
              echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
          }

          $FindLinks=0;
          $Arr_Links=array();
          while ($rowLink = db2_fetch_assoc($stmtLink)) {
            $IdLink=$rowLink['ID_EM_GROUP_LINK'];
            array_push($Arr_Links, $IdLink);
            $FindLinks=1;
          }
          
          $sqlCnt="SELECT count(*) CNT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_EM_GROUP = $IdEmGroup";
          $stmtCnt=db2_prepare($conn, $sqlCnt);
          $resultCnt=db2_execute($stmtCnt);
          while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
            $CntDep=$rowCnt['CNT'];
          }

          $lidisable="";
          $Bkgr="";
          if ( "$Enable" == "N" ){
            $Bkgr="background-color:#e12c2c;";
            $lidisable='style="background-color:#c89696;"';
          }

          if ( "$Enable" == "S" ){
            $Bkgr="background-color:#9697c8;";
          }
  
          if ( "$InRun" != "0" ){
            $Bkgr="background-color:#ffcb00;";
          }
          ?>
          <li <?php echo $lidisable; ?>>
          <div class="Group Div<?php echo $IdEmGroup ?>" >
            <table>
            <tr>
            <td style="width:20px;<?php echo $Bkgr; ?>" class="DivNum<?php echo $IdEmGroup; ?>" ><?php echo $Priority; ?></td>
            <td>
               <table class="ExcelTable" >
               <tr>
                    <th rowspan=2 style="background:white;" >
                     <table class="TitShell" >
                     <tr><td style="min-width:200px !important" >
                     <?php if ( "$IdEmGroupDep" != ""  ){
                       ?><img class="FolderImg" src="../images/Explode.png" title="Open child groups"  onclick="OpenDep(<?php echo $IdEmGroup; ?>)" ><?php
                     } ?>
                     <img class="FolderImg" src="../images/Folder.png" >
                     <?php 
                     if ( "$InRun" != "0" ){ 
                       ?><img class="FolderImg" src="../images/StatusI.png" ><?php
                     }               
                     if ( "$Status" != "I" ){ 
                       ?><img class="FolderImg" src="../images/Status<?php echo "$Status"; ?>.png" ><?php
                     }
                         if ( "$NotEnable" != "0" ){ 
                           ?><img class="FolderImg" src="../images/Attention.png" ><?php
                         }                       
                     ?>
                     <div class="GroupNameGroup"  onclick="OpenGroup(<?php echo $IdEmGroup; ?>,'<?php echo $NameGroup; ?>')" title="<?php echo $IdEmGroup; ?>"  ><?php echo "$NameGroup [$CntDep]"; ?></div>
                     </td></tr>
                     <tr><td><div class="GroupNameGroupDir" ><B>Creator:</B> <?php echo "$Creator - $TimeCreator"; ?></div></td></tr>
                        <tr><td><div class="GroupNameGroupDir" ><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div></td></tr>
                        <tr><td><div class="GroupNameGroupDir" ><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div></td></tr>
                     </table>
                    </th>
                    <td><img title="Set to No Run"    class="GroupImg" src="../images/DepNoRun.png"     onclick="DepNoRun(<?php echo $IdEmGroup; ?>)" ></td>
                    <td><img title="Recursive set to  No Run" class="GroupImg" src="../images/RecOff.png"     onclick="RTecOff(<?php echo $IdEmGroup; ?>)" ></td>
                    <td><img title="Add" class="GroupImg" src="../images/Add.png"      onclick="AddGroup(<?php echo $IdEmGroup; ?>)" ></td>
                    <?php if ( "$Status" == "I" or "$InRun" != "0" ) { ?>
                    <td></td>
                    <td></td>
                    <?php } else { ?>                   
                    <td><img title="Modify" class="GroupImg" src="../images/Setting.png"    onclick="EditGroup(<?php echo $IdEmGroup; ?>)"    ></td>
                    <td>
                    <?php if ( "$IdEmGroup" != "0" ) { ?>
                       <img title="Delete" class="GroupImg" src="../images/Remove.png"   onclick="DeleteGroup(<?php echo $IdEmGroup; ?>)" >
                    <?php } ?>
                    </td>                   
                    <?php } ?>  
                    <td><img title="Enable/Disable" class="GroupImg" src="../images/Power.png"      onclick="Power(<?php echo $IdEmGroup; ?>,'<?php echo $Enable; ?>')" ></td>
                    <td rowspan=2>
                    <?php if ( "$BckGrnd" == "Y" ) {
                           /* ?><img class="GroupImg" src="../images/Background.png"  title="accepts parallels"><?php */
                           ?><B>EXEC<BR>NEXT</B><?php
                    } ?>
                    </td>
                    <th style="min-width:100px !important"  >Description</th>                                
                    <th style="min-width:65px !important"  >Start Time</th>
                    <td style="min-width:100px !important" ><?php echo "$StartTime"; ?></td>                             
                    <th style="min-width:65px !important"  >True Time</th>
                    <th>Enable From</th>
                    <?php if ( "$ValidTo" != "2999-12-31" ) { ?><th>Enable To</th><?php } ?>
                    <th style="width:200px !important" >Dependent</th>
               </tr>
               <tr>
                     <td><img title="Set to Run"    class="GroupImg" src="../images/DepRun.png"     onclick="DepRun(<?php echo $IdEmGroup; ?>)" ></td>
                     <td><img title="Recursive set to Run" class="GroupImg" src="../images/RecOn.png"     onclick="RTecOn(<?php echo $IdEmGroup; ?>)" ></td>
                     <?php if ( "$Status" == "I" or "$InRun" != "0" ) { ?>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <?php } else { ?>                  
                     <td><img title="Up Priority"    class="GroupImg" src="../images/Up.png"       onclick="DownGroup(<?php echo $IdEmGroup; ?>,<?php echo $Priority; ?>,<?php echo $Priority-1; ?>)" ></td>
                     <td><img title="Down Priority"  class="GroupImg" src="../images/Down.png"     onclick="UpGroup(<?php echo $IdEmGroup; ?>,<?php echo $Priority; ?>,<?php echo $Priority+1; ?>)" ></td>
                     <td><img title="First Priority" class="GroupImg" src="../images/Frist.png"    onclick="DownGroup(<?php echo $IdEmGroup; ?>,<?php echo $Priority; ?>,1)" ></td>
                     <td><img title="Last Priority"  class="GroupImg" src="../images/Last.png"     onclick="UpGroup(<?php echo $IdEmGroup; ?>,<?php echo $Priority; ?>,0)" ></td>
                     <?php } ?>
                     <td><div width="100px" ><?php echo "$DescrGroup"; ?></div></td>
                     <th>End Time</th>
                     <td width="100px" ><?php echo "$EndTime"; ?></td>
                    <td style="min-width:100px !important" ><?php if ( "$TrueTime" != "0" and "$TrueTime" != "" ) { echo  floor(($TrueTime-1)/(60*60*24))."g ".gmdate('H:i:s', $TrueTime); } ?></td>
                     <td><?php echo "$ValidFrom"; ?></td>
                      <?php if ( "$ValidTo" != "2999-12-31" ) { ?><td><?php echo "$ValidTo"; ?></td><?php } ?>
                     <td class="DivDep<?php echo $IdEmGroup; ?>">
                        <?php if ( $FindLinks != 0 ){
                            $LisLnk="";  
                            foreach( $Arr_Groups as $Group2 ){
                              $IdEmGroup2=$Group2[0];
                              $NameGroup2=$Group2[1];
                              $Enable2   =$Group2[2];
                              $DescrGroup2  =$Group2[12];
                                if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                    $LisLnk=$LisLnk.$NameGroup2.' '.$DescrGroup2.',<BR>';
                                }
                            }
                            echo $LisLnk;
                            ?>
                            <script>
                                 $('.Div<?php echo $IdEmGroup; ?>').mouseover(function(){
                                       $('.DivDep<?php echo $IdEmGroup; ?>').css('background','SteelBlue');
                                       $('.DivDep<?php echo $IdEmGroup; ?>').css('color','white');
                                       <?php
                                        foreach( $Arr_Groups as $Group2 ){
                                          $IdEmGroup2=$Group2[0];
                                          $NameGroup2=$Group2[1];
                                          $Enable2   =$Group2[2];
                                          $DescrGroup2  =$Group2[12];
                                            if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                ?>
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','2px solid SteelBlue');
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','white');
                                                <?php
                                            }   
                                       }
                                       ?>
                                 });
                                 $('.Div<?php echo $IdEmGroup; ?>').mouseleave(function(){
                                       $('.DivDep<?php echo $IdEmGroup; ?>').css('background','white');
                                       $('.DivDep<?php echo $IdEmGroup; ?>').css('color','black');
                                       <?php
                                        foreach( $Arr_Groups as $Group2 ){
                                          $IdEmGroup2=$Group2[0];
                                          $NameGroup2=$Group2[1];
                                          $Enable2   =$Group2[2];
                                          $DescrGroup2  =$Group2[12];
                                            if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                ?>
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','none');
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','black');
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
               </tr>
               </table>
            </td>
            </tr>
            </table>
          </div>
          <ul>
          <?php
      }

      if ( "$IdEmGroupDep" != "" ){
      ?><div class="P<?php echo $IdEmGroup; ?>" hidden ><?php
          
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
                 $Editor=$Group[15];
                 $TimeCreator=$Group[16];
                 $TimeEditor=$Group[17];
                 $Executor=$Group[18];
                 $TimeExecutor=$Group[19];
                 break;
              }
          }

          $sqlLink="SELECT ID_EM_GROUP_LINK  FROM WORK_CORE.EXEC_MANAG_GROUPS_LINKS WHERE ID_EM_GROUP = $IdEmGroupDep";
          $stmtLink=db2_prepare($conn, $sqlLink);
          $resultLink=db2_execute($stmtLink);

          if ( ! $resultLink ){
              echo $sqlLink;
              echo "ERROR DB2:".db2_stmt_errormsg($stmtLink);
          }
          
          $FindLinks=0;
          $Arr_Links=array();
          while ($rowLink = db2_fetch_assoc($stmtLink)) {
            $IdLink=$rowLink['ID_EM_GROUP_LINK'];
            array_push($Arr_Links, $IdLink);
            $FindLinks=1;
          }

          $sqlCnt="SELECT count(*) CNT  FROM WORK_CORE.EXEC_MANAG_OBJECTS WHERE ID_EM_GROUP = $IdEmGroupDep";
          $stmtCnt=db2_prepare($conn, $sqlCnt);
          $resultCnt=db2_execute($stmtCnt);
          while ($rowCnt = db2_fetch_assoc($stmtCnt)) {
            $CntDep=$rowCnt['CNT'];
          }
          $lidisable="";
          $DepBkgr="";
          if ( "$DepEnable" == "N" ){
            $DepBkgr="background-color:#e12c2c;";
            $lidisable='style="background-color:#c89696;"';
          }
          if ( "$DepEnable" == "S" ){
            $DepBkgr="background-color:#9697c8;";
          }
          ?>
          <li <?php echo $lidisable; ?>>
              <div class="Group Div<?php echo $IdEmGroupDep ?>" >
                <table>
                <tr>
                <td style="width:20px;<?php echo $DepBkgr; ?>" class="DivNum<?php echo $IdEmGroupDep; ?>" ><?php echo $PriorityDep; ?></td>
                <td>
                  <table class="ExcelTable" style="background:white;" >
                  <tr>
                       <th rowspan=2 >
                       <table class="TitShell" >
                       <tr><td style="min-width:200px !important">
                         <img class="FolderImg" src="../images/Folder.png" >
                         <?php 
                         if ( "$DepInRun" != "0" ){ 
                           ?><img class="FolderImg" src="../images/StatusI.png" ><?php
                         }               
                         if ( "$DepStatus" != "I" ){ 
                           ?><img class="FolderImg" src="../images/Status<?php echo "$DepStatus"; ?>.png" ><?php
                         }
                         if ( "$DepNotEnable" != "0" ){ 
                           ?><img class="FolderImg" src="../images/Attention.png" ><?php
                         }                               
                         ?>                        
                        <div class="GroupNameGroup"  onclick="OpenGroup(<?php echo $IdEmGroupDep; ?>,'<?php echo $DepNameGroup; ?>')" title="<?php echo $IdEmGroupDep; ?>"  ><?php echo "$DepNameGroup [$CntDep]"; ?></div>
                        </td></tr>
                        <tr><td><div class="GroupNameGroupDir" ><B>Creator:</B> <?php echo "$DepCreator - $TimeCreator"; ?></div></td></tr>
                        <tr><td><div class="GroupNameGroupDir" ><B>Editor:</B> <?php echo "$Editor - $TimeEditor"; ?></div></td></tr>
                        <tr><td><div class="GroupNameGroupDir" ><B>Executor:</B> <?php echo "$Executor - $TimeExecutor"; ?></div></td></tr>
                        </table>
                       </th>
                       <td><img title="Set to No Run"    class="GroupImg" src="../images/DepNoRun.png"  onclick="DepNoRun(<?php echo $IdEmGroupDep; ?>)" ></td>
                       <td><img title="Recursive set to  No Run" class="GroupImg" src="../images/RecOff.png"    onclick="RTecOff(<?php echo $IdEmGroupDep; ?>)" ></td>
                       <td ><img title="Add" class="GroupImg" src="../images/Add.png"      onclick="AddGroup(<?php echo $IdEmGroupDep; ?>)" ></td>
                       <?php if ( "$DepStatus" == "I" or "$DepInRun" != "0" ) { ?>
                       <td ></td>
                       <td ></td>
                       <?php } else { ?>                           
                       <td ><img title="Modify" class="GroupImg" src="../images/Setting.png"    onclick="EditGroup(<?php echo $IdEmGroupDep; ?>)"    ></td>
                        <td>
                        <?php if ( "$IdEmGroupDep" != "0" ) { ?>
                           <img title="Delete" class="GroupImg" src="../images/Remove.png"   onclick="DeleteGroup(<?php echo $IdEmGroupDep; ?>)" >
                        <?php } ?>
                        </td>                      
                       <?php } ?>                      
                       <td><img title="Enable/Disable" class="GroupImg" src="../images/Power.png"      onclick="Power(<?php echo $IdEmGroupDep; ?>,'<?php echo $DepEnable; ?>')" ></td>
                        <td rowspan=2>
                        <?php if ( "$DepBckGrnd" == "Y" ) { 
                           /* ?><img class="GroupImg" src="../images/Background.png"  title="accepts parallels"><?php */
                           ?><B>EXEC<BR>NEXT</B><?php
                        } ?>
                        </td>    
                       <th style="min-width:100px !important"  >Description</th>   
                       <th style="min-width:65px !important" >Start Time</th>
                       <td style="min-width:100px !important"><?php echo "$DepStartTime"; ?></td>                            
                       <th style="min-width:65px !important"  >True Time</th>
                       <th>Enable From</th>
                       <?php if ( "$DepValidTo" != "2999-12-31" ) { ?><th>Enable To</th><?php } ?> 
                       <th style="width:200px !important" >Dependent</th>                      
                  </tr>
                  <tr>
                        <td><img title="Set to Run"    class="GroupImg" src="../images/DepRun.png"  onclick="DepRun(<?php echo $IdEmGroupDep; ?>)" ></td>
                        <td><img title="Recursive set to Run" class="GroupImg" src="../images/RecOn.png"   onclick="RTecOn(<?php echo $IdEmGroupDep; ?>)" ></td>
                        <?php if ( "$DepStatus" == "I" or "$DepInRun" != "0") { ?>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <?php } else { ?>                          
                        <td ><img title="Up Priority"    class="GroupImg" src="../images/Up.png"       onclick="DownGroup(<?php echo $IdEmGroupDep; ?>,<?php echo $PriorityDep; ?>,<?php echo $PriorityDep-1; ?>)" ></td>
                        <td ><img title="Down Priority"  class="GroupImg" src="../images/Down.png"     onclick="UpGroup(<?php echo $IdEmGroupDep; ?>,<?php echo $PriorityDep; ?>,<?php echo $PriorityDep+1; ?>)" ></td>
                        <td ><img title="First Priority" class="GroupImg" src="../images/Frist.png"    onclick="DownGroup(<?php echo $IdEmGroupDep; ?>,<?php echo $PriorityDep; ?>,1)" ></td>
                        <td ><img title="Last Priority"  class="GroupImg" src="../images/Last.png"     onclick="UpGroup(<?php echo $IdEmGroupDep; ?>,<?php echo $PriorityDep; ?>,0)" ></td>
                        <?php } ?>
                        <td><div width="100px" ><?php echo "$DepDescrGroup"; ?></div></td>
                        <th>End Time</th>
                        <td width="100px" ><?php echo "$DepEndTime"; ?></td>
                        <td style="min-width:100px !important" ><?php if ( "$TrueTime" != "0" and "$TrueTime" != "" ) { echo  floor(($TrueTime-1)/(60*60*24))."g ".gmdate('H:i:s', $TrueTime); } ?></td>
                        <td><?php echo "$DepValidFrom"; ?></td>
                        <?php if ( "$DepValidTo" != "2999-12-31" ) { ?><td><?php echo "$DepValidTo"; ?></td><?php } ?>
                        <td class="DivDep<?php echo $IdEmGroupDep; ?>">
                        <?php if ( $FindLinks != 0 ){
                            $LisLnk="";  
                            foreach( $Arr_Groups as $Group2 ){
                              $IdEmGroup2=$Group2[0];
                              $NameGroup2=$Group2[1];
                              $Enable2   =$Group2[2];
                              $DescrGroup2  =$Group2[12];
                                if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                    $LisLnk=$LisLnk.$NameGroup2.' '.$DescrGroup2.',<BR>';
                                }
                            }
                            echo $LisLnk;
                            ?>
                            <script>
                                 $('.Div<?php echo $IdEmGroupDep; ?>').mouseover(function(){
                                       $('.DivDep<?php echo $IdEmGroupDep; ?>').css('background','SteelBlue');
                                       $('.DivDep<?php echo $IdEmGroupDep; ?>').css('color','white');
                                       <?php
                                        foreach( $Arr_Groups as $Group2 ){
                                          $IdEmGroup2=$Group2[0];
                                          $NameGroup2=$Group2[1];
                                          $Enable2   =$Group2[2];
                                          $DescrGroup2  =$Group2[12];
                                            if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                ?>
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','2px solid SteelBlue');
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','white');
                                                <?php
                                            }   
                                       }
                                       ?>
                                 });
                                 $('.Div<?php echo $IdEmGroupDep; ?>').mouseleave(function(){
                                       $('.DivDep<?php echo $IdEmGroupDep; ?>').css('background','white');
                                       $('.DivDep<?php echo $IdEmGroupDep; ?>').css('color','black');
                                       <?php
                                        foreach( $Arr_Groups as $Group2 ){
                                          $IdEmGroup2=$Group2[0];
                                          $NameGroup2=$Group2[1];
                                          $Enable2   =$Group2[2];
                                          $DescrGroup2  =$Group2[12];
                                            if ( in_array( $IdEmGroup2, $Arr_Links ) ){
                                                ?>
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('border','none');
                                                $('.DivNum<?php echo $IdEmGroup2; ?>').css('color','black');
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
                  </tr>
                  </table>
                </td>
                </tr>
                </table>
              </div>              
            <?php
            Sons($IdEmGroupDep, $conn, $Arr_Groups);
            //foreach( $Arr_Links as $Father ){
            //    Sons($Father, $conn, $Arr_Groups);
            //}
            ?>
            </li>
            <?php
      ?></div><?php
      }

    }
    ?></li></ul><?php
    db2_close($db2_conn_string);
}
?>
<div id="DivCodaExec" hidden ></div>
<div id="ShowCodaExec" ><input class="Button" id="ShowCoda" value="IN CODA" onclick="OpenCoda()" readonly  ></div>
<div id="PulMostraStorico" class="Button" >
    STORICO
</div>  
<div id="MostraStorico" hidden ></div>
</form>
<script>
 $('#PulMostraStorico').click(function(){
     if (  $('#MostraStorico').css('display') == 'none' ){
	   $( "#MostraStorico" ).empty().load('../PHP/ExecManag_MostraStorico.php').show();
	 }else{
	   $( "#MostraStorico" ).empty().hide();	 
	 }
 }); 
      
var vListP=$('#ListOpenP').val();
var Parray = vListP.split(',');
for ( i=0; i <= Parray.length; i++) {
   var vIdDep = Parray[i];
   if ( vIdDep != '' ) { $('.P'+vIdDep).show(); }
}

function OpenDep(vIdDep){
  if ( $('.P'+vIdDep) != null ){
    if ( $('.P'+vIdDep).is(':visible') ){
      $('.P'+vIdDep).hide();
      var vttx = $('#ListOpenP').val();
      vttx = vttx.replace(','+vIdDep,'');
      $('#ListOpenP').val(vttx);
    }else{
      $('.P'+vIdDep).show();
      var vttx = $('#ListOpenP').val();
      vttx = vttx+','+vIdDep;
      $('#ListOpenP').val(vttx);
    }
  }
}

function TestNoAction(){
    <?php if ( "$User" == "EUL1831" or "$User" == "RU17688"  ) { ?>exit;<?php } ?>  
}

function OpenCoda(vIdEmGroup){
    if ( $('#DivCodaExec').css('display') == 'none' ){
      $('#DivCodaExec').empty().load('../PHP/CodaExec.php');
      $('#DivCodaExec').show();
    } else {
      $('#DivCodaExec').hide();
    }
}

$('#DivCodaExec').empty().load('../PHP/CodaExec.php');

function DeleteGroup(vIdEmGroup){
   TestNoAction();
   var re = confirm('Are you sure you want Delete this object?');
   if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "DeleteGroup")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();
   }
}

function EditGroup(vIdEmGroup){
   TestNoAction();
    var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "EditGroup")
    .val(vIdEmGroup);
    $('#FormScheduler').append($(input));
    $('#FormScheduler').submit();
}

function AddGroup(vIdEmGroup){
   TestNoAction();
    var input = $("<input>")
    .attr("type", "hidden")
    .attr("name", "AddGroup")
    .val(vIdEmGroup);
    $('#FormScheduler').append($(input));   
    $('#FormScheduler').submit();
}

function ChangeStatus(vIdEmGroup,vStatus){  
   TestNoAction();
   var re = confirm('Are you sure you want change status of this object?');
   if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "ChangeStatus")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "OldStatus")
        .val(vStatus);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();

   }
}

function Power(vIdEmGroup,vStatus){
   TestNoAction();
   var re = confirm('Are you sure you want Enable/Disable of this group?');
   if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "Power")
        .val(vIdEmGroup);
        $('#FormScheduler').append($(input));
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "PowerStatus")
        .val(vStatus);
        $('#FormScheduler').append($(input));
        $('#FormScheduler').submit();

   }
}

function DownGroup(vIdEmGroup,vOldPriority,vNewPriority){
   TestNoAction();
    if ( vNewPriority != 0 ){
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ChangePriority")
      .val(vIdEmGroup);
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

function UpGroup(vIdEmGroup,vOldPriority,vNewPriority){
      TestNoAction();
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "ChangePriority")
      .val(vIdEmGroup);
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


function OpenGroup(vIdEmGroup,vNameGroup){
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "IdEmGroup")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "NameGroup")
      .val(vNameGroup);
      $('#FormScheduler').append($(input));

      $('#FormScheduler').attr('action','../PAGE/PgExecManag.php')

      $('#FormScheduler').submit();     
}

function DepRun(vIdEmGroup){
   TestNoAction();
    var re = confirm('Are you sure you want to set all objects in this group to "Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "DepRun")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('N');
      $('#FormScheduler').append($(input));   
      
      $('#FormScheduler').submit(); 
   }
}


function DepNoRun(vIdEmGroup){
   TestNoAction();
    var re = confirm('Are you sure you want to set all objects in this group to "No Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "DepNoRun")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('N');
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('N');
      $('#FormScheduler').append($(input));
      
      $('#FormScheduler').submit(); 
    }
}

function RTecOn(vIdEmGroup){
   TestNoAction();
    var re = confirm('Are you sure you want to set all recursive objects/groups in this group to "Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecOn")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('Y');
      $('#FormScheduler').append($(input));   
      
      $('#FormScheduler').submit(); 
   }
}


function RTecOff(vIdEmGroup){
   TestNoAction();
    var re = confirm('Are you sure you want to set all recursive objects/groups in this group to "No Run Status"?');
    if ( re == true) {  
      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecOff")
      .val(vIdEmGroup);
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecStatus")
      .val('N');
      $('#FormScheduler').append($(input));

      var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "RecMode")
      .val('Y');
      $('#FormScheduler').append($(input));
      
      $('#FormScheduler').submit(); 
    }
}



$('#CancelEdit').click(function(){
  $('#Waiting').show();
  $('#FormScheduler').submit();
});

$('#CancelNew').click(function(){
  $('#Waiting').show();
  $('#FormScheduler').submit();
});

function Refresh(){
  $('#Waiting').show();
  $('#FormScheduler').submit();
};

$('#Waiting').hide();

$('#SelDescrGroup').keyup(function(){
  var vtext=$('#SelDescrGroup').val();  
  vtext=vtext.replace('"','\'\'');
  $('#SelDescrGroup').val(vtext);   
});

$('#FormScheduler').submit(function(){
      $('#TopScrollG').val($(window).scrollTop());  
});     

$(window).scrollTop($('#TopScrollG').val());

<?php
if (  "$EditGroup" == "" and "$AddGroup" == "" ){
  ?> 
  if ( $('#EnableRefresh').is(':checked') ){   
    setInterval(function(){ 
	    if (  $('#MostraStorico').css('display') == 'none' ){
         Refresh(); 
        }
	  }, 30000);
  }else{
	setInterval(function(){ 
	    if (  $('#MostraStorico').css('display') == 'none' ){
         Refresh(); 
        }
	  }, 3600000);
  }
  <?php
} 
?>

</script>