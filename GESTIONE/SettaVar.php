<?php

$vSql="SELECT SITO,CAMPO,VALORE FROM WEB.SETTINGSITE WHERE SITO='TAS'";
     
if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }
$stmt=db2_prepare($conn, $vSql);
$result=db2_execute($stmt);

if ( ! $result ){
	echo "ERROR DB2 2";
}    

while ($row = db2_fetch_assoc($stmt)) {
  ${$row["CAMPO"]}=$row["VALORE"];
}
?>
<STYLE>
html body {
    background-color: <?php echo $BodyColor; ?>;
}

#header {
  background:<?php echo $FondoColor; ?>;
}

#footer {
  background:<?php echo $BodyColor; ?>;
}

/* menu */

#menustat a:hover {
  color: #17e4d6;
}

#menustat ul ul a:hover {
  color: #17e4d6;
}

#menustat ul ul li {
  border:none;
/*  border-top: 1px solid white;*/
  border-bottom: 1px solid white;
  box-shadow: inset 0 0 3px white;
  background: <?php echo $FondoColor; ?>;
  z-index: 999;
  border: 2px solid black;
  margin-bottom: 1px;
}

</STYLE>
