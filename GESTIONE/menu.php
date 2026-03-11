<div id="menustat">
<ul><?php
$CodGroup=$_SESSION['CodGroup'];
if ( "$CodGroup" != "" ){
$sql="SELECT MK,MENU,PAGE from WEB.${FixAmb}_MENU where LEVEL=0 and ABILITATO = 1 and MK in ( select MK from WEB.${FixAmb}_MENU_ACCESS where GK in ( $CodGroup )) ORDER BY MK";
if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
$stmt = db2_prepare($conn, $sql);
$result = db2_execute($stmt);
if ( ! $result ){  
	echo db2_stmt_errormsg($stmt); 
	die("Failed Query");
}
while ($row = db2_fetch_assoc($stmt)) {
  $MK=$row["MK"];
  $MENU=$row["MENU"];
  $PAGE=$row["PAGE"];
  $sSottoMenu = "SELECT MENU,PAGE from WEB.${FixAmb}_MENU 
  where 1=1 
  and UNDER = $MK
  and ABILITATO = 1 
  and MK in ( select MK from WEB.${FixAmb}_MENU_ACCESS where GK in ($CodGroup) )
  ORDER BY MK";

  if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
  $stmtSottoMenu = db2_prepare($conn, $sSottoMenu);
  $result = db2_execute($stmtSottoMenu);
  $NRw=0;
  while ($rwlv2Menu = db2_fetch_assoc($stmtSottoMenu)) { $NRw=1; }
  if  ( $NRw != 0 ) { ?>
    <li class="has-sub" >
      <a href="#" >
        <span><?php echo "$MENU";  ?></span>
      </a>
      <ul class="UlColleg" ><?php
	   if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
	  $stmtSottoMenu = db2_prepare($conn, $sSottoMenu);
	  $result = db2_execute($stmtSottoMenu);     
	  while ($rwlv2Menu = db2_fetch_assoc($stmtSottoMenu)) {
           $lv2MENU=$rwlv2Menu["MENU"];
           $lv2PAGE=$rwlv2Menu["PAGE"];
           ?>
           <li>
             <a href="<?php echo "$lv2PAGE"; ?>" class="Colleg" >
                <span><?php echo "$lv2MENU";  ?></span>
             </a>
           </li><?php
      }?>
      </ul>
    </li><?php
  } else { ?>
           <li>
             <a href="<?php echo "$PAGE"; ?>"  class="Colleg" >
                <span><?php echo "$MENU";  ?></span>
             </a>
           </li><?php
     }
}
}
?>
</ul>
</div>
 <div name="Waiting" id="Waiting" style="display:none;">
    <table width="100%">
	<tr>
	<td><div id="WaitImg"><img src="../images/Attendere.gif" height="40px"></div></td>
	<td><div id="WaitText">Attendere Prego..</div></td>
	</tr>
	</table>
</div>

