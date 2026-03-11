<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);


$SelNumPage=$_POST['SelNumPage'];
if ( "$SelNumPage" == "" ){
    $SelNumPage=1;
}
if ( "$SelNumPage" > "1" ){
    $PreIdRun=$_POST['PreIdRun'];
}

$ID_ERR=$_GET['IDERR'];
if ( "$ID_ERR" != "" ){
    $_POST['IDSEL']=$ID_ERR;
}

$DA_RETITWS=$_POST['DA_RETITWS'];
$INRETE=$_POST['INRETE'];
$INPASSO=$_POST['IDSEL'];
$LISTOPEN=$_POST['LISTOPEN'];
$LastShellRun=$_POST['LastShellRun'];
$NoTags=$_POST['NoTags'];
$InRetePasso=$INRETE.$INPASSO;
if ( "$LISTOPEN" != "" ){ $_POST['ListOpenId'.$InRetePasso]=$LISTOPEN; }

$ShowDett="none"; 
$PLSSHOWDETT=$_POST['PLSSHOWDETT'];
if ( "$PLSSHOWDETT" != "" ){ $ShowDett="display"; }

if ( "$DA_RETITWS" == ""  ) {
    ?>  
    <script>
      $('#Waiting').show();
    </script>  
    <?php
} 


?>
<style>


.ClsDett{
    display:<?php echo $ShowDett; ?>;
}
.ClsRoot{
  border-left: 2px solid blue !important;
}
.SelPage{
    background:blue !important;
    color:white;
}
#PageTab{
    position: fixed;
    bottom:10px;
    left:0;
    right:0;
    margin:auto;
}
#PageTab td{
  border: 1px solid gray;
}
#LastRun{
    position: absolute;
    right: 2px;
    top: 2px;
    width: 710px;
    border: 1px solid gray;
}

.DivIconSh{
   position:relative;
   top:5px;
   right:5px;
   z-index:9999;
}

.IconSh{
   width:30px;
   float:right;
   cursor:pointer;
}

.IconFile{
   float:right;
   cursor:pointer;
   height: 29px;
   width: auto;
}

.Tit{
    text-align:left;
    color:blue;
    width:99%;
    height: 50px;
    font-size: 15px;
    margin: 6px;
}
.Err{
    background:#ff4040 !important;
    background:rgb(198, 66, 66) !important;
}
.Frz{
    background:darkgreen !important;
}
.Run{
    background:yellow !important;
    background:rgb(192, 181, 54) !important;
}
.Com{
    background:#27ff27 !important;
    background:rgb(67, 168, 51) !important;
}
.War{
    background:orange !important;
    background:orange !important;
}
.ComSp{
    background:#7f7fd7 !important;
}

/* Base Styles MENU RETI */
#ListShell<?php echo $InRetePasso; ?>,
#ListShell<?php echo $InRetePasso; ?> ul,
#ListShell<?php echo $InRetePasso; ?> li,
#ListShell<?php echo $InRetePasso; ?> a {
  margin: 0;
  padding: 0;
  border: 0;
  list-style: none;
  font-weight: normal;
  text-decoration: none;
  line-height: 1;
  font-family: 'Lato', sans-serif;
  font-size: 11px;
  position: relative;
  font-color: black;
  align:left;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
}

#ListShell<?php echo $InRetePasso; ?> a {
  line-height: 1.3;
  padding: 3px 15px;
}

#ListShell<?php echo $InRetePasso; ?> {
  min-width:100%;
    left: 0;
    right 0;
    --margin: auto !important;
  color:white;
  height: 460px;
  overflow: auto;
  padding:5px;
  /* top: 60px;*/
}

#ListShell<?php echo $InRetePasso; ?> > ul {
    display: none;
}

#ListShell<?php echo $InRetePasso; ?> > ul > li {
  --box-shadow: 0px 0px 7px inset;
}

#ListShell<?php echo $InRetePasso; ?> > ul > li > a {
  font-size: 13px;
  display: block;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li > a:hover {
  text-decoration: none;
}

#ListShell<?php echo $InRetePasso; ?> > ul > li.has-sub > a:after {
  content: "";
  position: absolute;
  top: 10px;
  right: 10px;
  border-left: 5px solid white;
  text-align: left;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li.has-sub.active > a:after {
  right: 14px;
  top: 12px;
  border-top: 5px solid white;
}

/* Sub menu */
#ListShell<?php echo $InRetePasso; ?> ul ul {
    display: none;
    left:5%;
}
#ListShell<?php echo $InRetePasso; ?> ul ul a {
  display: block;
  font-size: 13px;
  color:white;
}
#ListShell<?php echo $InRetePasso; ?> ul ul li {
  --box-shadow: 0px 0px 7px inset;
}


/* Base Styles SOTTO MENU RETI */

#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li {
}

#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li > a {
  font-size: 13px;
  display: block;
  text-align: center;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li > a:hover {
  text-decoration: none;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li.active {
  border-bottom: white;
}

#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li.has-sub > a:after {
  content: "";
  position: absolute;
  top: 10px;
  right: 10px;
  border-left: 5px solid white;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li.has-sub.active > a:after {
  right: 14px;
  top: 12px;
  border-top: 5px solid white;
}

/* Sub menu */
#ListShell<?php echo $InRetePasso; ?> ul ul ul {
    display: none;
    left: 5%;
}
#ListShell<?php echo $InRetePasso; ?> ul ul ul a {
  display: block;
  font-size: 13px;
}
#ListShell<?php echo $InRetePasso; ?> ul ul ul li {
  --box-shadow: 0px 0px 7px inset;
  padding: 2px;
  z-index: 99;
}
#ListShell<?php echo $InRetePasso; ?> > ul > li> ul > li > ul > li > a:hover {
  text-decoration: none;
}

table{
border: 1px solid darkgray !important;
border-collapse: separate !important;
}

#ShowRCLegend{
position: fixed;
left: 0;
right: 0;
top: 0;
bottom: 0;
margin: auto;
width: 980px;
height: 300px;
background: white;
overflow: auto;
padding: 10px;
border: 1px solid black;
z-index: 99999;
box-shadow: 0px 2px 10px 0px black;
}


.Bottone{
box-shadow: 0px 1px 2px 0px black inset;
cursor: pointer;
width: 68px;
height: 25px;
background: #5c5c84;
color: white;
padding: 5px;
text-align: center;
margin: 5px;
}

.active {
-webkit-animation:progress-bar-stripes 2s linear infinite;
-o-animation:progress-bar-stripes 2s linear infinite;
animation:progress-bar-stripes 2s linear infinite;
background-size: 40px 40px;
}

li{
  /*width: 1495px;*/
  width: fit-content;
}

.Esplodi{
    position: absolute;
    top: 16px;
    width: 25px;
    height: 25px;
    color:blue;
}

.EsplodiPLSQL{
    position: absolute;
    top: 10px;
    width: 25px;
    height: 25px;
}

.ExcelTable{
  font-size:10px;
}
</style>

<?php

if( "$DA_RETITWS" != "" ){
    ?>
    <![if !IE]>
    <script src="../JS/jquery-2.2.0.min.js" type="text/javascript"></script>
    <![endif]>
    <link rel="stylesheet" href="../CSS/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../CSS/StatoReti.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/mainmenu.css">
    <link rel="stylesheet" href="../CSS/excel.css">
    <?php 
    
    include '../GESTIONE/connection.php';
    include '../GESTIONE/SettaVar.php';
    include '../GESTIONE/login.php';
}


include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {
    
    $conn = db2_connect($db2_conn_string, '', '');
    
    $Soglia=11;
    $BarraCaricamento = "rgb(21, 140, 240)";
    $BarraPeggio = "rgb(165, 108, 185)";
    $BarraMeglio = "rgb(104, 162, 111)";

    $InErrore = "rgb(198, 66, 66)";
    $InEsecuzione = "rgb(192, 181, 54)";
    $FinitaCorr = "rgb(67, 168, 51)";
    $DaEseguire = "#838383";
    $ChiusuraForzata = "#4787DA";
    $Sospeso = "#682A83";

    $GAP = 120;

    $Sel_Id_Proc=$_POST['Sel_Id_Proc'];
    $Sel_Esito=$_POST['Sel_Esito']; 
    $SelMeseElab=$_POST['SelMeseElab'];
    $SelEserMese=$_POST['SelEserMese'];
    $SelShell=$_POST['SelShell'];
    $NumLast=$_POST['NumLast'];
    if ( "$NumLast" == "" ){$NumLast=10;}
    $SelLastMeseElab=$_POST['SelLastMeseElab'];
    $SelInDate=$_POST['SelInDate'];
    
	$ManualOk=$_POST['ManualOk'];
	if ( "$ManualOk" != "" ){
	 $sql = "UPDATE WORK_CORE.CORE_SH SET STATUS='M' WHERE ID_RUN_SH = $ManualOk";
     $stmt=db2_prepare($conn, $sql);
     $result=db2_execute($stmt);
     if ( ! $result ){
         echo $sql;
         echo "ERROR DB2 1";
     }
	}
	
    $ShowSourceSh="N";
    if ( "$INPASSO" != "" ){
        $SelShTarget=$_POST['IDSEL'];
        $ShowSourceSh="Y";
        $NumLast=0;
        $SelInDate=0;
    }else { 
        $SelShTarget=$_GET['IDSELEM'];
		$IDSELEM=$_GET['IDSELEM'];
        if ( "$SelShTarget" != ""  ){
            $ShowSourceSh="Y";
            $LastShellRun=0;
            $SelInDate=0;
        } else {
          $SelShTarget=$_GET['IDWFM'];
          if ( "$Sel_Id_Proc" == "" ){
             $Sel_Id_Proc=$_GET['IDPROCERR'];
          }
          if ( "$SelShTarget" != ""  ){
            $LastShellRun=0;
            $NumLast=0;
            $SelInDate=0;
            
            if ( $Admin ){
               $ShowSourceSh="Y"; 
            } else {
            
              $sql = "SELECT 
              (
                SELECT count(*)
                FROM WFS.AUTORIZZAZIONI
                WHERE 1=1
                AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.ASS_GRUPPO WHERE ID_UK = $Uk  )
                AND ID_WORKFLOW = E.ID_WORKFLOW
                AND ID_GRUPPO IN ( SELECT ID_GRUPPO FROM WFS.GRUPPI WHERE GRUPPO = 'ADMIN'  )
              ) CNT_AUTH
              FROM WFS.ELABORAZIONI E
              WHERE ID_SH = (SELECT ID_SH FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $SelShTarget )";
              
              $stmt=db2_prepare($conn, $sql);
              $result=db2_execute($stmt);
              if ( ! $result ){
                  echo $sql;
                  echo "ERROR DB2 1";
              }
              while ($row = db2_fetch_assoc($stmt)) {
                $TestWfsAdmin=$row['CNT_AUTH'];
                if ( $TestWfsAdmin != 0 ){
                    $ShowSourceSh="Y";
                }
              }
            }
          } else {
            $ShowSourceSh="Y";
          }
        }
             
    }
    
    if ( "$INPASSO" == "" ){ ?><FORM id="FormEserEsame" method="POST" ><?php } 
    
    if ( "$SelShTarget" == ""  ){
       $sql = "SELECT count(*) CNT
          FROM WEB.TAS_MENU_ACCESS 
          WHERE 1=1
          AND MK = (SELECT MK FROM WEB.TAS_MENU WHERE MENU = 'Processing' )
          AND GK IN (SELECT GK FROM WEB.TAS_WORKGROUP WHERE UK = $Uk )
          ";
        
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo $sql;
            echo "ERROR DB2 1";
        }
        while ($row = db2_fetch_assoc($stmt)) {
          $TestMenu=$row['CNT'];
          if ( $TestMenu == 0 ){
             ?>
              <div id="divieto"> 
              <CENTER><IMG src="../images/Divieto.png"><CENTER>
              <p><CENTER><b><?php echo $User; ?> Non sei abilitato a visualizzare questa pagina</B></CENTER></p>
              </div>
              <div id="footer">
              </div>
              <script>$('Waiting').hide();</script>           
              <?php            
             exit;
          }
        }
    }
    
    
    $ISIDERR="";
    if ( "$SelShTarget" != "" ){
        
        $AutoRefresh2=$_POST['AutoRefresh2'];
        
        if ( "$DA_RETITWS" == ""  ) {
        ?>
        <div style="width:510px;background:white;color:black;border:1px solid black;margin:5px; padding:3px;" >
        <table width="100%" style="border:none !important;" >
        <tr>
        <th ><input type="submit" value="Refresh" ></th>
        <td >
             <input type="checkbox" id="AutoRefresh2" name="AutoRefresh2" value="AutoRefresh" <?php if ( "$AutoRefresh2" == "AutoRefresh" ){ ?>checked<?php } ?> >Auto Refresh<BR>
             <input type="checkbox" id="PLSSHOWDETT"  name="PLSSHOWDETT"  value="PLSSHOWDETT" <?php if ( "$PLSSHOWDETT"  == "PLSSHOWDETT" ){ ?>checked<?php } ?> >Show Dett
        </td>
        </tr>
        </table>
        </div>
        <?php
        }
        
        $ISIDERR="hidden";
        
        $sql = "SELECT TO_CHAR(START_TIME,'YYYYMM') MESEELAB, TO_CHAR(START_TIME,'DD') ISDAY FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $SelShTarget";
        
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo $sql;
            echo "ERROR DB2 1";
        }
        
        while ($row = db2_fetch_assoc($stmt)) {
          $SelMeseElab=$row['MESEELAB'];
          $SelInDate=$row['ISDAY'];
        }
    }    
    
    
    $ForceEnd=$_POST['ForceEnd'];
    if ( "$ForceEnd" != "" ){
        $sql = "SELECT PID FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $ForceEnd";
        
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo $sql;
            echo "ERROR DB2 1";
        }
        
        while ($row = db2_fetch_assoc($stmt)) {
          $SelPid=$row['PID'];
        }
        
        $CntPid=0;
        
        $CntPid=shell_exec("sh $PRGDIR/TestPid.sh '${SSHUSR}' '${SERVER}' '${SelPid}'");
        //echo "CntPid:-$CntPid-";
        if ( $CntPid == 0 ) {       
           
           $Sql='CALL WORK_CORE.K_PROCESSING.KillSh(?)';
           $stmt = db2_prepare($conn, $Sql);
           
           db2_bind_param($stmt, 1,  "ForceEnd"  , DB2_PARAM_IN);
           
           $result=db2_execute($stmt);
           if ( ! $result ){
             echo "ERROR DB2 Error State:".db2_stmt_errormsg();
           }
        
        }else{
            
            ?><script>alert('The Shell is Alive, you can not end this shell');</script><?php
        
        }
        
    }       
    
    $HideTws=$_POST['HideTws'];
    $SpliVar=$_POST['SpliVar'];
    $HideUnify=$_POST['HideUnify'];
    $SkipUnify=$_POST['SkipUnify'];
    if ( "$HideUnify" == "HideUnify" ){
        $SkipUnify="";
    }
    
    $AutoRefresh=$_POST['AutoRefresh'];
    
    ?>
    <div 
	<?php 
	if ( "$DA_RETITWS" == ""  ){
	  ?>style="width:510px;background:white;color:black;border:1px solid black;margin:5px; padding:3px;" <?php
	}
	?>
	>
    <?php 
    $TopScrollShell=$_POST['TopScrollShell'];
    $LeftScrollShell=$_POST['LeftScrollShell'];
    ?>
    <input type="hidden" id="TopScrollShell" name="TopScrollShell" value="<?php echo $TopScrollShell; ?>" />
    <input type="hidden" id="LeftScrollShell" name="LeftScrollShell" value="<?php echo $LeftScrollShell; ?>" />
    <table width="100%" style="border:none !important;font-size:14px;" >
    <tr <?php echo $ISIDERR; ?>>
    <th rowspan="8" style="font-size: 12px;" >
      <input type="submit" value="Refresh" style="background:yellow;" ><BR>
      <input type="checkbox" class="ReloadForm" id="AutoRefresh" name="AutoRefresh" value="AutoRefresh" <?php if ( "$AutoRefresh" == "AutoRefresh" ){ ?>checked<?php } ?> >Auto Refresh<BR>
      <input type="checkbox" class="" id="PLSSHOWDETT" name="PLSSHOWDETT" value="PLSSHOWDETT" <?php if ( "$PLSSHOWDETT" == "PLSSHOWDETT" ){ ?>checked<?php } ?> >Show Dett<BR>
      <input type="checkbox" class="ReloadForm" id="SpliVar" name="SpliVar" value="SpliVar" <?php if ( "$SpliVar" == "SpliVar" ){ ?>checked<?php } ?> >Split to Vars<BR>
      <input type="checkbox" class="ReloadForm" id="HideTws" name="HideTws" value="HideTws" <?php if ( "$HideTws" == "HideTws" ){ ?>checked<?php } ?> >Hide Tws<BR>  
      <input type="checkbox" class="ReloadForm" id="LastShellRun" name="LastShellRun" value="LastShellRun" <?php if ( "$LastShellRun" == "LastShellRun" ){ ?>checked<?php } ?> >Last Run<BR>      
      <?php
      if ( "$SelShell" != "" ){
        ?><input type="checkbox" class="ReloadForm" id="NoTags" name="NoTags" value="NoTags" <?php if ( "$NoTags" == "NoTags" ){ ?>checked<?php } ?> >No Tags<BR><?php
      }
      if ( "$DB2database" == "TASPCUSR" ){ 
        ?><input type="checkbox" id="HideUnify" class="ReloadForm" name="HideUnify" value="HideUnify" <?php if ( "$HideUnify" == "HideUnify" ){ ?>checked<?php } ?> >Hide Unify<BR><?php 
        if ( "$HideUnify" == "" ){
          ?><input type="checkbox" id="SkipUnify" class="ReloadForm" name="SkipUnify" value="SkipUnify" <?php if ( "$SkipUnify" == "SkipUnify" ){ ?>checked<?php } ?> >Skip Unify<BR><?php 
        }
      } ?>   
    </th>
    <th>MESE ELAB.</th>
    <td style="border-bottom:none;" >
    <SELECT name="SelMeseElab" id="SelMeseElab" onchange="$('#SelLastMeseElab').val(''); $('#SelNumPage').val('1');$('#NumLast').val('10');$('#FormEserEsame').submit();" style="width:300px;" >
	<option value="%" <?php if ( "$SelMeseElab" == "%" ){ ?>selected<?php } ?>  >All</option>
    <?php
    $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB FROM WORK_CORE.CORE_SH ORDER BY MESEELAB DESC";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    $SelMeseNow="";
    while ($row = db2_fetch_assoc($stmt)) {
      $MeseElab=$row['MESEELAB'];
      if ( "$SelMeseElab" == "" ) { $SelMeseElab=$MeseElab; }
      if ( "$SelMeseNow" == "" ) { $SelMeseNow=$MeseElab; }
      ?><option value="<?php echo $MeseElab; ?>" <?php if ( "$SelMeseElab" == "$MeseElab" ){ ?>selected<?php } ?> ><?php echo $MeseElab; ?></option><?php
    }
    ?></SELECT>
    </td>
    </tr>
    <tr <?php 
	if ( "$SelShTarget" != "" and "$DA_RETITWS" != ""  ) {
	  echo $ISIDERR; 
	}
	?>>
    <th>MESE DIFF</th>
    <td style="border-bottom:none;" >
    <SELECT name="SelLastMeseElab" id="SelLastMeseElab" onchange="$('#FormEserEsame').submit()" style="width:300px;">
    <?php
	
    $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'YYYYMM') MESEELAB,
      case 
	  WHEN TO_CHAR(START_TIME,'MM') = '01' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'10'
	  WHEN TO_CHAR(START_TIME,'MM') = '04' THEN INT(TO_CHAR(START_TIME,'YYYY')-1)||'01'
	  WHEN TO_CHAR(START_TIME,'MM') = '07' THEN TO_CHAR(START_TIME,'YYYY')||'04'
	  WHEN TO_CHAR(START_TIME,'MM') = '10' THEN TO_CHAR(START_TIME,'YYYY')||'07'
          ELSE TO_CHAR(START_TIME,'YYYY')||TO_CHAR(START_TIME,'MM')-1
	end    OLDRUN
	FROM WORK_CORE.CORE_SH 
	WHERE TO_CHAR(START_TIME,'YYYYMM') <= DECODE('$SelMeseElab','%','$SelMeseNow','$SelMeseElab')
	ORDER BY MESEELAB DESC";

    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    while ($row = db2_fetch_assoc($stmt)) {
      $MeseElab=$row['MESEELAB'];
      if ( "$SelLastMeseElab" == "" ) { $SelLastMeseElab=$row['OLDRUN']; }
      ?><option value="<?php echo $MeseElab; ?>" <?php if ( "$SelLastMeseElab" == "$MeseElab" ){ ?>selected<?php  } ?> ><?php echo $MeseElab; ?></option><?php
    }
    ?></SELECT>
    </td>
    </tr>
    <tr <?php echo $ISIDERR; ?> >
    <th>DAY</th>
    <td>
    <SELECT name="SelInDate" id="SelInDate" onchange="$('#SelNumPage').val('1'); $('#FormEserEsame').submit();" style="width:300px;" >
    <option value="99" <?php if ( "$SelInDate" == "99" ){ ?>selected<?php } ?> >Last 3 days</option>
    <option value="0" <?php if ( "$SelInDate" == "0" ){ ?>selected<?php } ?> >All</option>
    <?php
    /*
    $sql = "SELECT TO_CHAR(MAX(START_TIME),'DD') MAXDD FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab'";
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    while ($row = db2_fetch_assoc($stmt)) {
      $LastD=$row['MAXDD'];
    }
    */
    if ( "$SelInDate" == "" ){ $SelInDate="99"; }
    
    $sql = "SELECT DISTINCT TO_CHAR(START_TIME,'DD') DD FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' ORDER BY 1 DESC";
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    while ($row = db2_fetch_assoc($stmt)) {
      $day=$row['DD'];
      ?><option value="<?php echo $day; ?>" <?php if ( "$SelInDate" == "$day" ){ ?>selected<?php } ?> ><?php echo $day; ?></option><?php
    }
    ?></SELECT>  
    </td>
    </tr>
    
    <tr <?php echo $ISIDERR; ?>>
     <th>LAST</th>
     <td style="border-bottom:none;" ><input  id="NumLast" name="NumLast" style="width:300px;"value="<?php echo $NumLast; ?>" ></td>
    </tr>
    
    <tr <?php echo $ISIDERR; ?>> 
      <th>ESITO</th>
      <td style="border-bottom:none;" >
      <SELECT name="Sel_Esito" onchange="$('#SelNumPage').val('1'); $('#FormEserEsame').submit()" style="width:300px;" >
        <option value=""  <?php if ( "$Sel_Esito" == "" ){ ?>selected<?php } ?> >All</option>
        <option value="I" <?php if ( "$Sel_Esito" == "I" ){ ?>selected<?php } ?> >In Corso</option>
        <option value="E" <?php if ( "$Sel_Esito" == "E" ){ ?>selected<?php } ?> >Errore</option>
        <option value="W" <?php if ( "$Sel_Esito" == "W" ){ ?>selected<?php } ?> >Warning</option>
        <option value="F" <?php if ( "$Sel_Esito" == "F" ){ ?>selected<?php } ?> >Corretto</option>
        <option value="M" <?php if ( "$Sel_Esito" == "M" ){ ?>selected<?php } ?> >Manual</option>
      </SELECT>
      </td>
    <tr <?php echo $ISIDERR; ?>>
    <th>ESER MESE</th>
    <td style="border-bottom:none;" >
    <SELECT name="SelEserMese" id="SelEserMese" onchange="$('#SelNumPage').val('1'); $('#FormEserEsame').submit()" style="width:300px;">
    <option value="" <?php if ( "$SelEserMese" == "" ){ ?>selected<?php } ?> >All</option>
    <?php
    $sql = "SELECT DISTINCT ESER_MESE FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' AND ID_RUN_SH_FATHER IS NULL ";
    if ( "$SelInDate" != "99" and "$SelInDate" != "0" ){   
       $sql = $sql."
         AND EXTRACT(DAY FROM START_TIME) = $SelInDate
       ";
    }
    if ( "$SelInDate" == "99" ){   
       $sql = $sql."
         AND START_TIME > ( SELECT MAX(START_TIME) -4 DAY FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' )
       ";          
    }
    $sql = $sql." ORDER BY ESER_MESE DESC";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    while ($row = db2_fetch_assoc($stmt)) {
      $EserMese=$row['ESER_MESE'];
      ?><option value="<?php echo $EserMese; ?>" <?php if ( "$SelEserMese" == "$EserMese" ){ ?>selected<?php } ?> ><?php echo $EserMese; ?></option><?php
    }
    ?></SELECT>
    </td>
    </tr>
    <tr <?php echo $ISIDERR; ?>>
    <th>SHELL</th>
    <td style="border-bottom:none;" >
    <SELECT name="SelShell" id="SelShell" onchange="$('#SelInDate').val(0).prop('selected', true); 
	if ( $('#SelMeseElab').val() != '%' ){
	  $('#NumLast').val(0);
	}
	$('#SelNumPage').val('1'); 
	$('#FormEserEsame').submit()" style="width:300px;">
    <option value="" <?php if ( "$SelShell" == "" ){ ?>selected<?php } ?> >All</option>
    <optgroup label="Shell Father">
    <?php
    $sql = "SELECT A.ID_SH ID_SH, SHELL_PATH, SHELL, TAGS, MAX(ID_RUN_SH) ID_RUN_SH, MAX(ID_RUN_SH_ROOT) ID_RUN_SH_ROOT
    FROM WORK_CORE.CORE_SH_ANAG  A
    JOIN
    WORK_CORE.CORE_SH S
    ON A.ID_SH = S.ID_SH
    WHERE 1=1
    AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' 
    AND ID_RUN_SH_ROOT != 0
    AND ID_RUN_SH_FATHER IS NULL
    GROUP BY A.ID_SH, SHELL_PATH, SHELL, TAGS
    ORDER BY SHELL";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    while ($row = db2_fetch_assoc($stmt)) {
      $SID_SH=$row['ID_SH'];
      $SSHELL=$row['SHELL'];
      $STAGS=$row['TAGS'];
      $SSHELLPATH=$row['SHELL_PATH'];
      $SID_RUN_SH=$row['ID_RUN_SH'];
      $IdRunShRoot=$row['ID_RUN_SH_ROOT'];
      ?><option value="<?php echo $SID_RUN_SH; ?>" <?php if ( "$SelShell" == "$SID_RUN_SH" ){ $SelRootShell=$IdRunShRoot; ?>selected<?php } ?> ><?php echo $SSHELL.' '.$STAGS.' ['.$SSHELLPATH.']'; ?></option><?php
    }   
    
    ?>
    </optgroup>
    <optgroup label="Shell Sons">
    <?php
    $sql = "SELECT A.ID_SH ID_SH, SHELL_PATH, SHELL, TAGS, MAX(ID_RUN_SH) ID_RUN_SH, MAX(ID_RUN_SH_ROOT) ID_RUN_SH_ROOT
    FROM WORK_CORE.CORE_SH_ANAG  A
    JOIN
    WORK_CORE.CORE_SH S
    ON A.ID_SH = S.ID_SH
    WHERE 1=1
    AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' 
    AND ID_RUN_SH_ROOT != 0 
    AND ID_RUN_SH_FATHER IS NOT NULL
    GROUP BY A.ID_SH, SHELL_PATH, SHELL, TAGS
    ORDER BY SHELL, TAGS";
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
    }
    
    while ($row = db2_fetch_assoc($stmt)) {
      $SID_SH=$row['ID_SH'];
      $SSHELL=$row['SHELL'];
      $STAGS=$row['TAGS'];
      $SSHELLPATH=$row['SHELL_PATH'];
      $SID_RUN_SH=$row['ID_RUN_SH'];
      $IdRunShRoot=$row['ID_RUN_SH_ROOT'];
      ?><option value="<?php echo $SID_RUN_SH; ?>" <?php if ( "$SelShell" == "$SID_RUN_SH" ){ $SelRootShell=$IdRunShRoot; ?>selected<?php } ?> ><?php echo $SSHELL.' '.$STAGS.' ['.$SSHELLPATH.']'; ?></option><?php
    }   
    
    ?>
    </optgroup>
    </SELECT>
    </td>
    </tr>
    <?php
    if ( "$DB2database" == "TASPCUSR" and "$SelMeseElab" != "" ){
      ?>
      <tr <?php echo $ISIDERR; ?> > 
      <th>PROCESS</th>
      <td style="border-bottom:none;" >
      <SELECT name="Sel_Id_Proc" onchange="$('#SelNumPage').val('1'); $('#FormEserEsame').submit()" style="width:300px;">
      <option value="" <?php if ( "$Sel_Id_Proc" == "" ){ ?>selected<?php } ?> >All</option>
      <option value="b" <?php if ( "$Sel_Id_Proc" == "b" ){ ?>selected<?php } ?> >Batch Run</option>
      <?php
      $sql = "SELECT ID_PROCESS, DESCR, TIPO, FLAG_STATO, ( SELECT TEAM FROM WFS.TEAM WHERE ID_TEAM = c.ID_TEAM ) TEAM
      FROM WORK_CORE.ID_PROCESS c
      WHERE c.ID_PROCESS IN ( SELECT DISTINCT ID_PROCESS FROM WORK_CORE.CORE_SH WHERE TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' AND ID_PROCESS IS NOT NULL )
      ORDER BY ID_PROCESS DESC";
      
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 1";
      }
      
      while ($row = db2_fetch_assoc($stmt)) {
        $Id_Proc=$row['ID_PROCESS'];
        $Descr=$row['DESCR'];
        $Tipo=$row['TIPO'];
        $Stato=$row['FLAG_STATO'];
        $IDPTeam=$row['TEAM'];
        
        switch($Tipo){
          case 'Q':
            $LabelTipo="Quarter";
            break;;
          case 'M':
            $LabelTipo="Mensile";
            break;;
          case 'R':
            $LabelTipo="Restatemnt";
            break;;
        }
        switch($Stato){
          case 'C':
            $LabelStato="Chiuso";
            break;;
          case 'A':
            $LabelStato="Aperto";
            break;;
          case 'S':
            $LabelStato="Sospeso";
            break;;
          case 'D':
            $LabelStato="Cancellato";
            break;;
        }
        ?><option value="<?php echo $Id_Proc; ?>" <?php if ( "$Sel_Id_Proc" == "$Id_Proc" ){ ?>selected<?php } ?> ><?php echo  $Id_Proc.' '.$IDPTeam.' '.$Descr.'('.$LabelTipo.' '.$LabelStato.')'; ?></option><?php
      }
      ?></SELECT>
      </td>
      </tr>
      <?php
    }
    ?>
      
     </table>
      <script>
	  
      $('.ReloadForm').change(function(){ 
        if ( $(this).val() != 'SkipUnify' ){
          $('#SelNumPage').val('1');
        }
        $('#Waiting').show();
        $('#FormEserEsame').submit();
      });
      

function ShowGraph(vStep,vTags,vIdSh){
    window.open('../PHP/GraphStep.php?STEP='+vStep+'&TAGS='+vTags+'&IDSH='+vIdSh);
};

function OpenFile(vIdSh){
    window.open('../PHP/ApriFile.php?IDSH='+vIdSh);
}
function OpenSqlFile(vIdSql,vDescr){
    if ( vDescr != 'Anonymous Block' && vDescr != 'SQL DB2 Block' ){
      window.open('../PHP/ApriSqlFile.php?IDSQL='+vIdSql);
    }
}
function OpenLog(vIdRunSh){
    window.open('../PHP/ApriLog.php?IDSH='+vIdRunSh);
}

function OpenTab(vIdSql){
    window.open('../PHP/ApriTabLog.php?IDSQL='+vIdSql);
}
function OpenPlsql(vSchema,VPackage){
    window.open('../PHP/ApriPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage);
}

function OpenTabPlsql(vSchema,VPackage){
    window.open('../PHP/ApriTabPlsql.php?SCHEMA='+vSchema+'&PACKAGE='+VPackage,'TablePackage','width=500,height=500');
}

function OpenTabFile(vIdSh,vIdRunSh,vIdRunSql){
    window.open('../PHP/ApriRelTab.php?IDSH='+vIdSh+'&IDRUNSH='+vIdRunSh+'&IDRUNSQL='+vIdRunSql,'RelTable','width=700,height=500');
}     
</Script>
   
    </div>
    <?php
    $ListOpenId=$_POST['ListOpenId'.$InRetePasso];
    $ListIdOpen=explode(',',$ListOpenId);
    ?>
    <input type="hidden" id="ListOpenId<?php echo $InRetePasso; ?>" name="ListOpenId<?php echo $InRetePasso; ?>" value="<?php echo $ListOpenId; ?>" />
    <input type="hidden" id="SelNumPage" name="SelNumPage" value="<?php echo $SelNumPage; ?>" />
    <input type="hidden" id="PreIdRun" name="PreIdRun" value="<?php echo $PreIdRun; ?>" />
    
    
    <?php if ( "$INPASSO" == "" ){ ?></FORM><?php } ?>  
    <div id="RCLegend" class="Bottone" >Rc Legend</div>
    <div id="ShowRCLegend" hidden >
      <table width="100%" style="border-bottom:none; background:cornsilk;font-size:15px;" >
        <tr><th>RC</th><th>TYPE</th><th>Note</th></tr>
        <tr><td style="color:green;"  >0</td>  <td style="color:green;"  >OK</td>     <td style="color:green;"  >Execution was successful</td></tr>
        <tr><td style="color:green;"  >1</td>  <td style="color:green;"  >OK</td>     <td style="color:green;"  >SELECT or FETCH statement returned no rows</td></tr>
        <tr><td style="color:orange;" >2</td>  <td style="color:orange;" >WARNING</td><td style="color:orange;" >Db2 command or SQL statement warning</td></tr>
        <tr><td style="color:orange;" >81</td> <td style="color:orange;" >WARNING</td><td style="color:orange;" >Found warning in the Run ( RC=0 changed to RC=81 )</td></tr>
        <tr><td style="color:orange;" >82</td> <td style="color:orange;" >WARNING</td><td style="color:orange;" >Skip Exit found in the Run.. The core may have lost control of the RC ( RC=0 changed to RC=82 )</td></tr>
        <tr><td style="color:orange;" >83</td> <td style="color:orange;" >WARNING</td><td style="color:orange;" >Background Run found in the Run.. Launched a Son without control of the RC ( RC=0 changed to RC=83 )</td></tr>
        <tr><td style="color:red;"    >5</td>  <td style="color:red;" >ERROR</td><td style="color:red;"   >Problem SQL</td></tr>
        <tr><td style="color:red;"    >90</td> <td style="color:red;"    >ERROR</td>  <td style="color:red;"    >Error in the initial checks of the CORE</td></tr>
        <tr><td style="color:red;"    >99</td> <td style="color:red;"    >ERROR</td>  <td style="color:red;"    >Shell-System Error found in the Run</td></tr>
        <tr><td style="color:red;"    >100</td><td style="color:red;"    >ERROR</td>  <td style="color:red;"    >Application error</td></tr>
        <tr><td style="color:red;"    >13</td><td style="color:red;"     >ERROR</td>  <td style="color:red;"    >Clpplus SQL error</td></tr>
        <tr><td style="color:red;"    >14</td><td style="color:red;"     >ERROR</td>  <td style="color:red;"    >Clpplus OS  error</td></tr>
        <tr><td style="color:red;"    >126</td><td style="color:red;"     >ERROR</td>  <td style="color:red;"    >File Permission</td></tr>
      </table>
      <div id="CloseRcLegend" class="Bottone">Close</div>
    </div>  

    <?php
    //if ( "$DB2database" == "TASPCUSR" ){
        if ( "$DB2database" != "" ){
      ?>
      <div id="LastRun" <?php echo $ISIDERR; ?> >

      <table class="ExcelTable" style="font-size:9px;">
      <tr><th colspan=9 ><CENTER><B>Last 5 Runs in the Month</B><CENTER></th></tr>
      <tr><th>Shell</th><th>Tags</th><th>Mese Elab</th><th>Day</th><th>IdProcess</th><th>Mese Esame</th><th>Start</th><th>End</th><th>User</th></tr>
      <?php
      $sql = "
      SELECT 
    TO_CHAR(START_TIME,'YYYYMM') MESEELAB, 
    TO_CHAR(START_TIME,'DD') ISDAY, 
    ID_PROCESS, 
    NAME,
    TAGS,   
    ESER_ESAME, 
    MESE_ESAME, 
    TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS') START_TIME, 
    (
    SELECT TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS') FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            START_TIME = A.START_TIME
            AND NAME = A.NAME
    )   
    END_TIME, 
    USERNAME 
FROM 
    (   SELECT 
            ID_PROCESS, 
            NAME,
            RTRIM(TAGS) TAGS,           
            ESER_ESAME, 
            MESE_ESAME, 
            USERNAME, 
            MAX(START_TIME) START_TIME 
        FROM 
            WORK_CORE.CORE_SH 
        WHERE 
            START_TIME >= SYSDATE-".date("d");          
                
        if ( "$DB2database" == "TASPCUSR" ){
          if ( "$HideUnify" == "HideUnify" ){
            $sql = $sql." AND ( ID_RUN_SH_FATHER IS NULL AND ID_SH != ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) ) ";
          }else{  
            if ( "$SkipUnify" == "SkipUnify" ){
              $sql = $sql." AND ( 
              ( ID_RUN_SH_FATHER IS NULL AND ID_SH != ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) )
              OR
              ( ID_RUN_SH_FATHER IN ( SELECT ID_RUN_SH FROM WORK_CORE.CORE_SH WHERE ID_SH = ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) ) )
              )";
            }else{
              $sql = $sql." AND ID_RUN_SH_FATHER IS NULL ";
            }
          }
        }else{
          $sql = $sql." AND ID_RUN_SH_FATHER IS NULL ";
        }
        
        if ( "$HideTws" == "HideTws" ){
            $sql = $sql." AND ID_SH NOT IN ( SELECT ID_SH FROM WORK_CORE.CORE_RETI_TWS WHERE ENABLE = 'Y' ) ";
        }
           
        
        $sql = $sql." GROUP BY 
            ID_PROCESS, 
            NAME,
            TAGS,
            ESER_ESAME, 
            MESE_ESAME, 
            USERNAME 
        ORDER BY 
            START_TIME DESC ) A 
ORDER BY 
    START_TIME DESC";
    
      
      $stmt=db2_prepare($conn, $sql);
      $result=db2_execute($stmt);
      if ( ! $result ){
        echo $sql;
          echo "ERROR DB2 1";
      }
      //
      $D_Row=0;
      while ($row = db2_fetch_assoc($stmt)) {
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
        if ( $D_Row > 4 ){ break;}
      }
      ?>
      </table>
      </div>
      <?php
    }
    
    if ( "$Sel_Id_Proc" == "" ){
        $WhereCon=" 1=1";
        $LastShWhereCon=" b.ID_SH = c.ID_SH AND STATUS IN ( 'F', 'W')  AND c.ID_RUN_SH != b.ID_RUN_SH  ";
        $LastSqlWhereCon=" b.FILE_SQL = d.FILE_SQL AND b.STEP = d.STEP AND SQL_STATUS IN ( 'F', 'W')  AND d.ID_RUN_SQL != b.ID_RUN_SQL  
        AND ID_RUN_SH IN ( SELECT MAX(ID_RUN_SH) FROM WORK_CORE.CORE_SH WHERE ID_SH = b.ID_SH AND ID_RUN_SH != b.ID_RUN_SH AND STATUS IN ( 'F', 'W') AND TO_CHAR(START_TIME,'YYYYMM') = '$SelLastMeseElab'  )
        ";
    }
    if ( "$Sel_Id_Proc" == "b" ){
        $WhereCon=" ID_PROCESS IS NULL";
        $LastShWhereCon=" b.ID_SH = c.ID_SH AND  ID_PROCESS IS NULL AND STATUS IN ( 'F', 'W')  AND c.ID_RUN_SH != b.ID_RUN_SH ";
        $LastSqlWhereCon=" b.FILE_SQL = d.FILE_SQL AND b.STEP = d.STEP AND  ID_PROCESS IS NULL AND SQL_STATUS IN ( 'F', 'W')  AND d.ID_RUN_SQL != b.ID_RUN_SQL   
        AND ID_RUN_SH IN ( SELECT MAX(ID_RUN_SH) FROM WORK_CORE.CORE_SH WHERE ID_SH = b.ID_SH AND ID_RUN_SH != b.ID_RUN_SH AND STATUS IN ( 'F', 'W') AND TO_CHAR(START_TIME,'YYYYMM') = '$SelLastMeseElab'  )
        ";
    }   
    if ( "$Sel_Id_Proc" != "b" and  "$Sel_Id_Proc" != "" ){
        $WhereCon=" ID_PROCESS = $Sel_Id_Proc ";
        $LastShWhereCon=" b.ID_SH = c.ID_SH AND c.ID_RUN_SH != b.ID_RUN_SH  AND STATUS IN ( 'F', 'W')  ";
        $LastSqlWhereCon=" b.FILE_SQL = d.FILE_SQL AND b.STEP = d.STEP AND SQL_STATUS IN ( 'F', 'W')    AND d.ID_RUN_SQL != b.ID_RUN_SQL  
        AND ID_RUN_SH IN ( SELECT MAX(ID_RUN_SH) FROM WORK_CORE.CORE_SH WHERE ID_SH = b.ID_SH AND ID_RUN_SH != b.ID_RUN_SH AND STATUS IN ( 'F', 'W') AND TO_CHAR(START_TIME,'YYYYMM') = '$SelLastMeseElab' )
        ";
    } 
    
    
    if ( "$SpliVar" != "SpliVar" ){  
       $LastShWhereCon = $LastShWhereCon." AND NVL(RTRIM(b.TAGS),'-') = NVL(RTRIM(c.TAGS),'-')";
    } else {
       $LastShWhereCon = $LastShWhereCon." AND NVL(RTRIM(b.VARIABLES),'-') = NVL(RTRIM(c.VARIABLES),'-')"; 
    }
         
    

    
    $AllArrSh=array();
    $AllArrSql=array();

   function SearchSonRoutine($IdRunSql,$IdRunSh,$RtFather,$Status){
        global $conn, $ListIdOpen;
        $sqlR = "SELECT
                    SCHEMA,
                    PACKAGE,
                    ROUTINE,
                    START,
                    END,
                    STATUS,
                    NOTES,
                    timestampdiff(2,NVL(END,CURRENT_TIMESTAMP)-START) DIFF,
                    ID_LOG_ROUTINE,
                    ( SELECT count(*) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_FATHER = R.ID_LOG_ROUTINE AND ID_RUN_SQL = $IdRunSql ) CNT_SON
                FROM
                    WORK_ELAB.LOG_ROUTINES R
                WHERE ID_RUN_SQL = $IdRunSql
                AND ID_FATHER = $RtFather
                ORDER BY ID_LOG_ROUTINE";
        $stmtR=db2_prepare($conn, $sqlR);
        $resultR=db2_execute($stmtR);

        if ( ! $resultR ){
            echo $sqlR;
            echo "ERROR DB2 Routine $IdRunSql";
        }
        ?><ul style="display:<?php if ( in_array($IdRunSh.'_'.$IdRunSql, $ListIdOpen) or "$Status" == "I"  ) { echo "block"; } else { echo "none"; } ?> ><?php
        while ($rowR = db2_fetch_assoc($stmtR)) {
            $Rt_SCHEMA=$rowR['SCHEMA'];
            $Rt_PACKAGE=$rowR['PACKAGE'];
            $Rt_ROUTINE=$rowR['ROUTINE'];
            $Rt_START=$rowR['START'];
            $Rt_END=$rowR['END'];
            $Rt_STATUS=$rowR['STATUS'];
            $Rt_NOTES=$rowR['NOTES'];
            $Rt_DIFF=$rowR['DIFF'];
            $Rt_FATHER2=$rowR['ID_LOG_ROUTINE'];
            $Rt_CNT_SON=$rowR['CNT_SON'];
            $RtEsito="";
            switch($Rt_STATUS){
               case 'E': $RtEsito="Err"; break;
               case 'I': $RtEsito="Run"; break;
               case 'F': $RtEsito="Com"; break;
               case 'W': $RtEsito="War"; break;
               case 'M': $RtEsito="Frz"; break;
            }
            
            ?>
                <li id="<?php echo $Rt_ROUTINE; ?>" >
                   <?php
                   if ( "$Rt_CNT_SON" != "0" ){
                    ?><label class="EsplodiPLSQL" >o</label><?php 
                   }
                   ?>
                   <a id="<?php echo $Rt_ROUTINE; ?>" >
                   <table class="ExcelTable" style="box-shadow: 0px 0px 0px 1px black; height:35px;">
                   <tr>
                        <th class="<?php echo $RtEsito; ?>" style="width:8px;" ></th>
                        <th><b>schema</b></th>
                        <td><?php echo $Rt_SCHEMA; ?></td>
                        <?php if ( "$Rt_PACKAGE" != "" ){ ?>
                        <th><b>package</b></th>
                        <td><?php echo $Rt_PACKAGE; ?></td>
                        <td>
                        <img src="../images/PlsqlTab.png" onclick="OpenTabPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;">
                        <img src="../images/PlsqlDb.png"  onclick="OpenPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;">
                        </td>
                        <?php } ?>
                        <th><b>routine</b></th>
                        <td><?php echo $Rt_ROUTINE; ?></td>
                        <th><b>start</b></th>
                        <td><?php echo $Rt_START; ?></td>
                        <th><b>end</b></th>
                        <td><?php echo $Rt_END; ?></td>
                        <th><b>time</b></th>
                        <td><?php echo gmdate('H:i:s', $Rt_DIFF); ?></td>
                        <th><b>note</b></th>
                        <td><?php echo "$Rt_NOTES"; ?></td>
                   </tr>
                   </table>
                   </a>
                   <?php
                   if ( "$Rt_CNT_SON" != "0" ){
                    SearchSonRoutine($IdRunSql,$IdRunSh,$Rt_FATHER2,$Status);
                   }
                   ?>
                </li>
            <?php
        }   
        ?></ul><?php
   }

   function SearchFatherRoutine($IdRunSql,$IdRunSh,$Status){
        global $conn, $ListIdOpen;
        $sqlR = "SELECT
                    SCHEMA,
                    PACKAGE,
                    ROUTINE,
                    START,
                    END,
                    STATUS,
                    NOTES,
                    timestampdiff(2,NVL(END,CURRENT_TIMESTAMP)-START) DIFF,
                    ID_LOG_ROUTINE,
                    ( SELECT count(*) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_FATHER = R.ID_LOG_ROUTINE AND ID_RUN_SQL = $IdRunSql ) CNT_SON
                FROM
                    WORK_ELAB.LOG_ROUTINES R
                WHERE ID_RUN_SQL = $IdRunSql
                AND ID_FATHER is null
                ORDER BY ID_LOG_ROUTINE";
        $stmtR=db2_prepare($conn, $sqlR);
        $resultR=db2_execute($stmtR);

        if ( ! $resultR ){
            echo $sqlR;
            echo "ERROR DB2 Routine $IdRunSql";
        }
        ?><ul style="display:<?php if ( in_array($IdRunSh.'_'.$IdRunSql, $ListIdOpen) or "$Status" == "I" ) { echo "block"; } else { echo "none"; } ?>;"><?php
        while ($rowR = db2_fetch_assoc($stmtR)) {
            $Rt_SCHEMA=$rowR['SCHEMA'];
            $Rt_PACKAGE=$rowR['PACKAGE'];
            $Rt_ROUTINE=$rowR['ROUTINE'];
            $Rt_START=$rowR['START'];
            $Rt_END=$rowR['END'];
            $Rt_STATUS=$rowR['STATUS'];
            $Rt_NOTES=$rowR['NOTES'];
            $Rt_DIFF=$rowR['DIFF'];
            $Rt_FATHER=$rowR['ID_LOG_ROUTINE'];
            $Rt_CNT_SON=$rowR['CNT_SON'];
            $RtEsito="";
            switch($Rt_STATUS){
               case 'E': $RtEsito="Err"; break;
               case 'I': $RtEsito="Run"; break;
               case 'F': $RtEsito="Com"; break;
               case 'W': $RtEsito="War"; break;
               case 'M': $RtEsito="Frz"; break;
            }
            
            ?>
                <li id="<?php echo $Rt_ROUTINE; ?>" >
                   <?php
                   if ( "$Rt_CNT_SON" != "0" ){
                    ?><label class="EsplodiPLSQL" >o</label><?php 
                   }
                   ?>
                   <a id="<?php echo $Rt_ROUTINE; ?>" >
                   <table class="ExcelTable" style="box-shadow: 0px 0px 0px 1px black; height:35px;">
                   <tr>
                        <th class="<?php echo $RtEsito; ?>" style="width:8px;" ></th>
                        <th><b>schema</b></th>
                        <td><?php echo $Rt_SCHEMA ;?></td>
                        <?php if ( "$Rt_PACKAGE" != "" ){ ?>
                        <th><b>package</b></th>
                        <td><?php echo $Rt_PACKAGE; ?></td>
                        <td>
                        <img src="../images/PlsqlTab.png" onclick="OpenTabPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;">
                        <img src="../images/PlsqlDb.png" onclick="OpenPlsql('<?php echo $Rt_SCHEMA; ?>','<?php echo $Rt_PACKAGE; ?>')" class="IconSh" style="width:30px;" >
                        </td>
                        <?php } ?>
                        <th><b>routine</b></th>
                        <td><?php echo $Rt_ROUTINE ;?></td>
                        <th><b>start</b></th>
                        <td><?php echo $Rt_START ;?></td>
                        <th><b>end</b></th>
                        <td><?php echo $Rt_END ;?></td>
                        <th><b>time</b></th>
                        <td><?php echo gmdate('H:i:s', $Rt_DIFF); ?></td>
                        <th><b>note</b></th>
                        <td><?php echo $Rt_NOTES ;?></td>
                   </tr>
                   </table>
                   </a>
                   <?php
                   if ( "$Rt_CNT_SON" != "0" ){
                    SearchSonRoutine($IdRunSql,$IdRunSh,$Rt_FATHER,$Status);
                   }
                   ?>
                </li>
            <?php
        }
        ?></ul><?php        
   }
   
   
   
   //FIGLI
   $OpenNipote=$_POST['OpenNipote'];
    function RecSonsh($IdSh,$IdRunSh,$Opentutti){ 
        global $conn, $SelInDate, $WhereCon, $LastShWhereCon, $LastSqlWhereCon, $BarraPeggio, $BarraMeglio, $BarraCaricamento, $GAP, $ListIdOpen, $InRetePasso, $SelShell, $SelMeseElab, $SelLastMeseElab, $OpenNipote, $ShowSourceSh;
        
        $LastShWhereCon=$LastShWhereCon." AND ID_RUN_SH_FATHER = (   
          SELECT MAX(ID_RUN_SH) FROM WORK_CORE.CORE_SH WHERE ID_SH = $IdSh AND STATUS IN ( 'F', 'W') AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab'          
        ) ";
                    
        $sql2 = "WITH W_LAST_CORE_PROCESSING as (
          SELECT 
              *
          FROM 
              WORK_CORE.V_CORE_PROCESSING c 
          WHERE 1=1
          AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' 
        )   
        ,W_PREC_CORE_PROCESSING as (
          SELECT 
              *
          FROM 
              WORK_CORE.V_CORE_PROCESSING c 
          WHERE 1=1
          AND TO_CHAR(START_TIME,'YYYYMM') = '$SelLastMeseElab'     
        )   

        SELECT ID_SH, ID_RUN_SH, 
        F_ID_SH, F_ID_RUN_SH, F_NAME,  
        TO_CHAR(F_START_TIME,'YYYY-MM-DD HH24:MI:SS') F_START_TIME,
        TO_CHAR(F_END_TIME,'YYYY-MM-DD HH24:MI:SS') F_END_TIME ,
        F_ID_RUN_SH_FATHER, F_LOG, F_STATUS, F_USERNAME, F_DEBUG_SH, F_DEBUG_DB, F_MAIL, F_ESER_ESAME, F_MESE_ESAME, F_ESER_MESE, F_SHELL_PATH,
        timestampdiff(2,NVL(b.F_END_TIME,CURRENT_TIMESTAMP)-b.F_START_TIME) F_SH_SEC_DIFF,
        F_RC, F_MESSAGE,RTRIM(VARIABLES) VARIABLES, F_TAGS
        ,(SELECT timestampdiff(2,MAX(c.F_END_TIME)-MAX(c.F_START_TIME)) FROM W_PREC_CORE_PROCESSING c WHERE $LastShWhereCon ) F_LASTSH_SEC_DIFF  
        ,TO_CHAR(( select ADD_SECONDS(b.START_TIME,( SELECT timestampdiff(2,MAX(c.END_TIME)-MAX(c.START_TIME)) FROM W_PREC_CORE_PROCESSING c WHERE $LastShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SH_END        
        ,ID_RUN_SQL, TYPE_RUN, STEP, FILE_SQL, FILE_IN,
        TO_CHAR(SQL_START,'YYYY-MM-DD HH24:MI:SS') SQL_START,
        TO_CHAR(SQL_END,'YYYY-MM-DD HH24:MI:SS') SQL_END,
        SQL_STATUS, RTRIM(F_VARIABLES) F_VARIABLES, RTRIM(TAGS) TAGS,
        timestampdiff(2,NVL(b.SQL_END,CURRENT_TIMESTAMP)-b.SQL_START) SQL_SEC_DIFF,
        ( SELECT count(*) FROM W_LAST_CORE_PROCESSING WHERE ID_RUN_SH_FATHER = b.ID_RUN_SH  ) N_SON
        ,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) LASTSQL_SEC_DIFF  
        ,TO_CHAR(( select ADD_SECONDS(b.SQL_START,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SQL_END
        ,( SELECT COUNT(1) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = b.ID_RUN_SQL ) USE_ROUTINE 
        ,( select count(*) FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = b.ID_RUN_SH ) CNTPASS
        FROM W_LAST_CORE_PROCESSING b
        WHERE $WhereCon
        AND ID_RUN_SH = '$IdRunSh'
        ORDER BY ID_RUN_SH DESC, TIME_ORDER DESC";
        
        $stmt2=db2_prepare($conn, $sql2);
        $result2=db2_execute($stmt2);

        if ( ! $result2 ){
            echo $sql2;
            echo "ERROR DB2 $IdRunSh";
        }

        $Find=0;
        $CntR=0;
        $lastrow=db2_num_rows($stmt2);
        while ($row2 = db2_fetch_assoc($stmt2)) {
       
            $SIdSql=$row2['ID_RUN_SQL'];
            if ( "$SIdSql" != "" ){
                $SSqlType=$row2['TYPE_RUN'];
                $SSqlStep=$row2['STEP'];
                $SSqlFile=$row2['FILE_SQL'];
                $SSqlInFile=$row2['FILE_IN'];
                $SSqlSTART_TIME=$row2['SQL_START'];
                $SSqlEND_TIME=$row2['SQL_END'];
                $SSqlStatus=$row2['SQL_STATUS'];
                $SSqlSecDiff=$row2['SQL_SEC_DIFF'];
                $SSqlLastSecDiff=$row2['LASTSQL_SEC_DIFF'];
                $SSqlPrwEnd=$row2['PREVIEW_SQL_END'];
                $SSqlUseRoutine=$row2['USE_ROUTINE'];

                #$SSqlDiff="";
                #if ( "$SSqlEND_TIME" != "" ){
                   $SSqlDiff=gmdate('H:i:s', $SSqlSecDiff);
			       $SSqlDiff=floor(($SSqlSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SSqlSecDiff);
                #}
                $SSqlOldDiff="";
                if ( "$SSqlLastSecDiff" != "" ){
                   $SSqlOldDiff=gmdate('H:i:s', $SSqlLastSecDiff);
			       $SSqlOldDiff=floor(($SSqlLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SSqlLastSecDiff);
                }
                $SSqlEsito="";
                switch($SSqlStatus){
                   case 'E': $SSqlEsito="Err"; break;
                   case 'I': $SSqlEsito="Run"; break;
                   case 'F': $SSqlEsito="Com"; break;
                   case 'W': $SSqlEsito="War"; break;
                   case 'M': $SSqlEsito="Frz"; break;
                }

                ?>
                <li id="List_<?php echo $SIdRunSh."_".$SIdSql; ?>" class="has-sub Figlio<?php echo $IdRunSh; ?>">
                    <?php if ( "$SSqlType" == "PLSQL" and "$SSqlUseRoutine" != "0" ) {  ?><label class="Esplodi" >o</label><?php } ?>  
                    <a onclick="ChangeA<?php echo $InRetePasso; ?>('<?php echo $IdRunSh.'_'.$SIdRunSh."_".$SIdSql; ?>')" >
                    <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;" >
                        <tr>
                            <td rowspan=2 style="min-width:3px" class="<?php echo $SSqlEsito; ?>" ></td>
                            <td rowspan=2 style="min-width:378px" ><B><?php echo str_replace('.','. ',$SSqlStep); ?></B></td>
                            <td rowspan=2 style="min-width:70px" >
                                <?php
                                if ( "$SSqlType" == "PLSQL" and "$SSqlUseRoutine" != "0" ){
                                    ?><img src="../images/LogDb.png" onclick="OpenTab(<?php echo $SIdSql; ?>)" class="IconSh"><?php
                                }
                                if ( "$SSqlFile" != "Anonymous Block" and  "$SSqlFile" != "SQL DB2 Block" ){
                                 ?><img src="../images/File.png" onclick="OpenSqlFile(<?php echo $SIdSql; ?>,'<?php echo $SSqlFile; ?>')" class="IconSh" style="width:25px;"><?php
                                }
                                ?>                     
                            </td>
                            <th style="min-width:50px" ><B>Type</B></th>
                            <td><?php echo $SSqlType; ?></td>
                            <th style="min-width:40px"  class="ClsDett" ><B>Sql</B></th>
                            <td style="min-width:400px"  onclick="OpenSqlFile(<?php echo $SIdSql; ?>,'<?php echo $SSqlFile; ?>')" style="cursor:pointer;"  class="ClsDett" >
                              <?php echo $SSqlFile; ?>
                            </td>
                            <td style="min-width:40px" rowspan=2  >                         
                              <?php 
                              if ( "$SSqlFile" != "Anonymous Block" and  "$SSqlFile" != "SQL DB2 Block" ){
                                ?><img src="../images/PlsqlTab.png" onclick="OpenTabFile('<?php echo $SIdSh; ?>''<?php echo $SIdRunSh; ?>',,'<?php echo $SIdSql; ?>')" class="IconSh" style="width:25px;"><?php
                              }
                              ?>
                            </td>
                            <th style="min-width:50px"  ><B>Start</B></th>
                            <td style="min-width:155px" ><?php echo $SSqlSTART_TIME; ?></td>
                            <th style="min-width:50px" class="" ><B>Time</B></th>
                            <th style="min-width:50px" class="" ><B>OldTime</B></th>
                        </tr>
                        <tr>
                            <th style="min-width:50px"><B>Meter</B></th>
                            <td style="min-width:155px">
                            <?php
                            if ( "$SSqlLastSecDiff" != "" ){
                                $SecLast=$SSqlSecDiff;
                                $SecPre=$SSqlLastSecDiff;
                                if ( "$SecPre" == "0" ){
                                    $SecPre = 1;
                                }
                                $Perc = round(( $SecLast * 100 ) / $SecPre );

                                if ( $Perc <= 100 and "$SSqlStatus" != "I" ) {
                                    $SColor = "$BarraMeglio";
                                }
                                if (  "$SSqlStatus" == "I" ) {
                                    $SColor = "$BarraCaricamento";
                                }   
                                if ( $Perc > 120 ) {
                                    $SColor = "$BarraPeggio";
                                }   
                                
                                if (
                                    (   1==1
                                        and ( $Perc > 120 or $Perc < 90 ) 
                                        and  ( "$SSqlStatus" == "F" or "$SSqlStatus" == "W" )
                                        and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
                                    ) 
                                    or ( "$SSqlStatus" == "I" )
                                ) {
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?php 
                                        if ("$SSqlStatus" == "I") {
                                            echo "active";
                                        } 
                                        ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                        if ($Perc > 100) {
                                            echo 100;
                                        } else {
                                            echo "$Perc";
                                        } 
                                        ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                        if ($Perc > 100) {
                                            $Perc = $Perc - 100;
                                            $Perc = "+" . $Perc;
                                        } else {
                                            if ( "$SSqlStatus" != "I" ){
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
                            <th class="ClsDett" ><B><?php 
                            if ( "$SSqlType" == "PLSQL" and "$SSqlUseRoutine" != "0" ){
                              ?>Note<?php
                            }else{
                              ?>File<?php
                            }
                            ?></B></th>
                            <td style="min-width:155px" class="ClsDett" ><?php 
                            //if ( "$SSqlType" == "PLSQL" and "$SSqlUseRoutine" != "0" ){
                            //  $sqlt="SELECT NOTES FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $SIdSql ORDER BY START DESC";
                            //  $stmtt=db2_prepare($conn, $sqlt);
                            //  $resultt=db2_execute($stmtt);
                            //  if ( ! $resultt ){
                            //      echo $sqlt;
                            //      echo "ERROR DB2 2";
                            //  }
                            //  while ($rowt = db2_fetch_assoc($stmtt)) {
                            //    echo $rowt['NOTES'];
                            //  }
                            //}else{
                              echo $SSqlInFile; 
                            //}
                            ?></td>
                            <th ><B><?php if ( "$SSqlEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                            <td><?php 
                            if ( "$SSqlEND_TIME" == "" ) {
                              echo '<font color="blue"><B>'.$SSqlPrwEnd.'</B></font>';
                            }else{
                              echo $SSqlEND_TIME; 
                            }
                            ?></td>
                            <td style="min-width:55px" class="" ><?php echo $SSqlDiff; ?></td>
                            <td style="min-width:55px" class="" ><?php echo $SSqlOldDiff; ?></td>
                        </tr>
                    </table>
                    </a>
                    <?php 
                    if ( ( "$SSqlType" == "PLSQL" and "$SSqlUseRoutine" != "0" ) or ( "$SSqlStatus" == "I"  ) ){
                        SearchFatherRoutine($SIdSql,$IdRunSh.'_'.$SIdRunSh,$SSqlStatus);          
                    }
                    ?>
                </li>
                <?php 
          }
          $SStepType=$row2['TYPE_RUN'];
          if ( "$SStepType" == "STEP" ){
            $SStepName=$row2['STEP'];
            $SStepTime=$row2['SQL_START'];
            ?>
            <li class="has-sub">
               <a>
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;height: 30px !important;" >
                    <tr>
                        <td style="min-width:3px" class="ComSp" ></td>
                        <td style="min-width:700px"><B><?php echo $SStepName; ?></B></td>
                        <th style="min-width:50px" class="" ><B>TimeStamp</B></th>
                        <td style="min-width:155px"><?php echo $SStepTime; ?></td>
                    </tr>
                </table>        
                </a>
            </li>
            <?php
        }
        
        
        
            $FIdSh=$row2['F_ID_SH'];
     
            if ( "$FIdSh" != "" ){
              
                $FIdRunSh=$row2['F_ID_RUN_SH'];
                $FShName=$row2['F_NAME'];
                $FShSTART_TIME=$row2['F_START_TIME'];
                $FShEND_TIME=$row2['F_END_TIME'];
                $FShFather=$row2['F_ID_RUN_SH_FATHER'];
                $FShLog=$row2['F_LOG'];
                $FShStatus=$row2['F_STATUS'];
                $FShUser=$row2['F_USERNAME'];
                $FShDebugSh=$row2['F_DEBUG_SH'];
                $FShDebugDb=$row2['F_DEBUG_DB'];
                $FShMail=$row2['F_MAIL'];
                $FShEserMese=$row2['F_ESER_MESE'];
                $FShEserEsame=$row2['F_ESER_ESAME'];
                $FShMeseEsame=$row2['F_MESE_ESAME'];
                $FShSecDiff=$row2['F_SH_SEC_DIFF'];
                $FShShellPath=$row2['F_SHELL_PATH'];
                $FShVariables=$row2['F_VARIABLES'];
                $FShSons=$row2['F_N_SON'];
                $FShRc=$row2['F_RC'];
                $FShMessage=$row2['F_MESSAGE'];
                $FShMessage=str_replace("$FShShellPath/$FShName:",'',$FShMessage);
                $FShLastSecDiff=$row2['F_LASTSH_SEC_DIFF'];
                $FShTags=$row2['F_TAGS'];
                $FShPrwEnd=$row2['F_PREVIEW_SH_END'];
                $FShCntPass=$row2['F_CNTPASS'];
                $FIdSql=$row2['F_ID_RUN_SQL'];
                $FShDiff=gmdate('H:i:s', $FShSecDiff);
				$FShDiff=floor(($FShSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $FShSecDiff);
                
                $FEsito="";
                switch($FShStatus){
                    case 'E': $FEsito="Err"; break;
                    case 'I': $FEsito="Run"; break;
                    case 'F': $FEsito="Com"; break;
                    case 'W': $FEsito="War"; break;
                    case 'M': $FEsito="Frz"; break;
                }
               $SelClsSh="";
               if( "$FIdRunSh" == "$SelShell" ){$SelClsSh="ClsRoot";}
               
                ?>
                <li class="<?php echo "$SelClsSh"; ?>" >
                    <?php if ( "$FShSons" != "0" or "$FIdSql" != ""  ){   ?><label class="Esplodi" >o</label><?php } ?>           
                   <a onclick="ChangeA<?php echo $FInRetePasso; ?>('<?php echo $FIdRunSh; ?>')" >
                   <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;" >
                        <tr <?php if ( "$OpenNipote" != "OpenNipote".$FIdRunSh ) { ?>onclick="OpenNipote('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>');"<?php } ?> >
                            <td rowspan=2 style="min-width:3px" class="<?php echo $FEsito; ?>" ></td>
                            <td rowspan=2 style="min-width:50px" title='<?php echo $FShMessage; ?>' >RC:<?php echo $FShRc; ?></td>
                            <td rowspan=2 style="min-width:320px" ><img title="<?php echo $FIdRunSh; ?>" src="../images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $FIdRunSh; ?>);"/>
                            <div><B><?php echo $FShName; ?></B></div>
							</td>
                            <td rowspan=2  style="min-width:170px;">
                                <?php
                                if ( "$ShowSourceSh" == "Y" ){ 
                                  ?><img src="../images/File.png" class="IconFile" onclick="OpenFile(<?php echo $FIdSh; ?>)"><?php 
                                }
                                ?>
                                <img src="../images/PlsqlTab.png" onclick="OpenTabFile('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>','')" class="IconSh" style="width:25px;">
                                <?php
                                if ( "$FShLog" != "" ){
                                    ?><img src="../images/Log.png" onclick="OpenLog(<?php echo $FIdRunSh; ?>)" class="IconSh"><?php
                                }
                                if ( "$FShMail" == "Y" ){
                                    ?><img src="../images/Mail.png" class="IconSh"><?php
                                }
                                if ( "$FShDebugSh" == "Y" ){
                                    ?><img src="../images/DebugSh.png" title="DebugSh" class="IconSh"><?php
                                }
                                if ( "$FShDebugDb" == "Y" ){
                                    ?><img src="../images/DebugDb.png" title="DebugDb" class="IconSh"><?php
                                }
                                ?>                     
                                <img src="../images/Graph.png" onclick="ShowGraph('<?php echo $FShName; ?>','<?php echo $FShTags; ?>','<?php echo $FIdSh; ?>')" class="IconSh">
                            </td>
                            <th style="min-width:50px" class="" ><B>EserEsame</B></th>
                            <td style="min-width:50px" class="" ><?php echo $FShEserEsame; ?></td>
                            <th style="min-width:50px"><B>Tags</B></th>
                            <td style="min-width:50px"><?php echo $FShTags; ?></td>
                            <th style="min-width:40px" class="ClsDett" ><B>Variables</B></th>
                            <td style="min-width:220px" class="ClsDett" ><?php echo $FShVariables; ?></td>
                            <th style="min-width:50px"><B>Start</B></th>
                            <td style="min-width:155px"><?php echo $FShSTART_TIME; ?></td>
                            <th style="min-width:80px" class="" ><B>Time</B></th>
                            <th style="min-width:80px" class="" ><B>OldTime</B></th>
                            <th class="" ><B>User</B></th>
                        </tr>
                        <tr>
                            <th style="min-width:50px" class="" ><B>MeseEsame</B></th>
                            <td style="min-width:50px" class="" ><?php echo $FShMeseEsame; ?></td>
                            <th style="min-width:50px"><B>Meter</B></th>
                            <td style="min-width:155px">
                            <?php
                            if ( "$ShLastSecDiff" != "" ){
                                $FSecLast=$FShSecDiff;
                                $FSecPre=$FShLastSecDiff;
                                if ( "$FSecPre" == "0" ){
                                    $FSecPre = 1;
                                }
                                $FPerc = round(( $FSecLast * 100 ) / $FSecPre );
                                
                                if ( $FPerc <= 100 and "$FShStatus" != "I" ) {
                                    $FSColor = "$FBarraMeglio";
                                }
                                
                                if ( "$FShStatus" == "I" ) {
                                    $FSColor = "$FBarraCaricamento";
                                }
                                if ( $FPerc > 120 ) {
                                    $FSColor = "$FBarraPeggio";
                                }
                                
                                if (
                                    (   1==1
                                        and ( $FPerc > 120 or $FPerc < 90 ) 
                                        and  ( "$FShStatus" == "F" or "$FShStatus" == "W" )
                                        and ( $FSecLast - $FSecPre > $GAP or $FSecLast - $FSecPre < -$GAP )
                                    ) 
                                    or ( "$FShStatus" == "I" )
                                ) {
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped <?php 
                                        if ("$FShStatus" == "I") {
                                            echo "active";
                                        } 
                                        ?>" role="progressbar" aria-valuenow="<?php echo $FPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                        if ($FPerc > 100) {
                                            echo 100;
                                        } else {
                                            echo "$FPerc";
                                        } 
                                        ?>%;background-color: <?php echo "$FSColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                        if ($FPerc > 100) {
                                            $FPerc = $FPerc - 100;
                                            $FPerc = "+" . $FPerc;
                                        } else {
                                            if ( "$FShStatus" != "I" ){
                                              $FPerc = $FPerc - 100;
                                            } 
                                        }                                   
                                        echo $FPerc;
                                        ?>%</LABEL>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>                      
                            </td>
                            <th class="ClsDett" ><B>Dir</B></th>
                            <td style="min-width:155px" class="ClsDett" ><?php echo $FShShellPath; ?></td>
                            <th><B><?php if ( "$FShEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                            <td><?php 
                            if ( "$FShEND_TIME" == "" ) {
                              echo '<font color="blue"><B>'.$FShPrwEnd.'</B></font>';
                            }else{
                              echo $FShEND_TIME; 
                            }
                            ?></td>
                            <td style="min-width:55px" class="" ><?php echo $FShDiff; ?></td>
                            <td style="min-width:55px" class="" ><?php echo $FShOldDiff; ?></td>
                           
                            <td style="min-width:155px" class="" ><?php echo $FShUser; ?></td>
                        </tr>
                    </table>
                    </a>
                    <ul id="ShowFiglio<?php echo $FIdSh.'_'.$FIdRunSh; ?>"  style="display:<?php if ( "$FShStatus" == "I" or in_array($FIdRunSh, $ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                      <?php 
                      if ( "$FShStatus" == "I" or in_array($FIdRunSh, $ListIdOpen) or "$OpenNipote" == "OpenNipote".$FIdRunSh  or "$Opentutti" == "Y" ) {
                        RecSonsh($FIdSh,$FIdRunSh,'Y'); 
                      }
                      ?>
                    </ul>
                </li>
                <?php
            }           
        }      
    }
   
   //PADRI

    ?> <div id="ListShell<?php echo $InRetePasso; ?>" class="ListShell" ><ul style="display: block;"><?php
    $sql = "
      WITH W_LAST_CORE_PROCESSING as (
        SELECT 
            *
        FROM 
            WORK_CORE.V_CORE_PROCESSING c 
        WHERE 1=1
        ";  
        if ( "$IDSELEM" != "" ){
			 $sql = "$sql        
             AND ID_RUN_SH = $IDSELEM";			 
		} else {
			 $sql = "$sql        
             AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab'";			 
        
             if ( "$SelInDate" != "0" ){
                if ( "$SelInDate" != "99" ){   
                    $sql = $sql."
                      AND EXTRACT(DAY FROM START_TIME) = $SelInDate
                    ";
                } else {
                    $sql = $sql."
                      AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' )
                    ";          
                }
             } 
		     
             if ( "$Sel_Esito" != "" ){
               $sql = $sql." 
               AND ( STATUS = '$Sel_Esito' OR F_STATUS = '$Sel_Esito' ) ";  
             }       
             
             $sql = "$sql        
             AND ID_RUN_SH IN ( 
                SELECT ID_RUN_SH FROM (                
                     SELECT ID_RUN_SH, MAX(START_TIME) ST
                     FROM WORK_CORE.V_CORE_PROCESSING 
                     WHERE ID_RUN_SH_FATHER IS NULL
                     AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab'";
             
             if ( "${SelShTarget}" != "" ){
                $sql = $sql." AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.V_CORE_PROCESSING WHERE ID_RUN_SH = $SelShTarget )";
             }   
             if ( "${SelShell}" != "" ){
                $sql = $sql." AND ID_SH IN ( SELECT ID_SH FROM WORK_CORE.V_CORE_PROCESSING WHERE ID_RUN_SH IN ( $SelShell, $SelRootShell ) )";
				
             }           
                     
             if ( "$HideUnify" == "HideUnify" ){
               $sql = $sql." AND ID_SH != ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) ";
             }
		     
             if ( "$Sel_Esito" != "" ){
               $sql = $sql." AND ( STATUS = '$Sel_Esito' OR F_STATUS = '$Sel_Esito' ) ";  
             }
		     
             $sql = $sql." GROUP BY ID_RUN_SH
                     ORDER BY  MAX(START_TIME) DESC
                 )";
                 
		     
             if ( "$NumLast" != "0" ){        
                 $sql = $sql." FETCH FIRST $NumLast ROWS ONLY ";
             }
             $sql = $sql."    
             )
             ";
	   }
    
    
    $sql = "$sql
      )
      ,W_PREC_CORE_PROCESSING as (
        SELECT 
            *
        FROM 
            WORK_CORE.V_CORE_PROCESSING c 
        WHERE 1=1
        AND TO_CHAR(START_TIME,'YYYYMM') = '$SelLastMeseElab'     
      )
      SELECT 
    ID_SH, ID_RUN_SH, NAME,  
    TO_CHAR(START_TIME,'YYYY-MM-DD HH24:MI:SS') START_TIME,
    TO_CHAR(END_TIME,'YYYY-MM-DD HH24:MI:SS') END_TIME ,
    ID_RUN_SH_FATHER, LOG, STATUS, USERNAME, DEBUG_SH, DEBUG_DB, MAIL, ESER_ESAME, MESE_ESAME, ESER_MESE, SHELL_PATH,
    timestampdiff(2,NVL(b.END_TIME,CURRENT_TIMESTAMP)-b.START_TIME) SH_SEC_DIFF,
    RC, MESSAGE, RTRIM(VARIABLES)  VARIABLES, TAGS
    ,(SELECT timestampdiff(2,MAX(c.END_TIME)-MAX(c.START_TIME)) FROM W_PREC_CORE_PROCESSING c WHERE $LastShWhereCon ) LASTSH_SEC_DIFF
    ,F_ID_SH, F_ID_RUN_SH, F_NAME,  
    TO_CHAR(F_START_TIME,'YYYY-MM-DD HH24:MI:SS') F_START_TIME,
    TO_CHAR(F_END_TIME,'YYYY-MM-DD HH24:MI:SS') F_END_TIME ,
    F_ID_RUN_SH_FATHER, F_LOG, F_STATUS, F_USERNAME, F_DEBUG_SH, F_DEBUG_DB, F_MAIL, F_ESER_ESAME, F_MESE_ESAME, F_ESER_MESE, F_SHELL_PATH,
    timestampdiff(2,NVL(b.F_END_TIME,CURRENT_TIMESTAMP)-b.F_START_TIME) F_SH_SEC_DIFF,
    F_RC, F_MESSAGE
    ,(SELECT timestampdiff(2,MAX(c.F_END_TIME)-MAX(c.F_START_TIME)) FROM W_PREC_CORE_PROCESSING c WHERE $LastShWhereCon ) F_LASTSH_SEC_DIFF      
    ,TO_CHAR(( select ADD_SECONDS(b.START_TIME,( SELECT timestampdiff(2,MAX(c.END_TIME)-MAX(c.START_TIME)) FROM W_PREC_CORE_PROCESSING c WHERE $LastShWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SH_END
    ,ID_RUN_SQL, TYPE_RUN, STEP, FILE_SQL, FILE_IN,
    TO_CHAR(SQL_START,'YYYY-MM-DD HH24:MI:SS') SQL_START,
    TO_CHAR(SQL_END,'YYYY-MM-DD HH24:MI:SS') SQL_END,
    SQL_STATUS, RTRIM(F_VARIABLES)  F_VARIABLES, RTRIM(F_TAGS) F_TAGS,
    timestampdiff(2,NVL(b.SQL_END,CURRENT_TIMESTAMP)-b.SQL_START) SQL_SEC_DIFF
    ,(SELECT count(*) FROM W_LAST_CORE_PROCESSING WHERE ID_RUN_SH_FATHER = b.ID_RUN_SH  ) N_SON
    ,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) LASTSQL_SEC_DIFF 
    ,TO_CHAR(( select ADD_SECONDS(b.SQL_START,(SELECT timestampdiff(2,MAX(d.SQL_END)-MAX(d.SQL_START)) FROM W_PREC_CORE_PROCESSING d WHERE $LastSqlWhereCon ) ) FROM DUAL ),'YYYY-MM-DD HH24:MI:SS')  PREVIEW_SQL_END 
    ,( SELECT COUNT(1) FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = b.ID_RUN_SQL ) USE_ROUTINE
    ,( select count(*) FROM WORK_CORE.CORE_DB WHERE ID_RUN_SH = b.ID_RUN_SH ) CNTPASS
    FROM W_LAST_CORE_PROCESSING b
    WHERE $WhereCon ";
        
    if ( "$Sel_Esito" != "" ){
      $sql = $sql." AND ( STATUS = '$Sel_Esito' OR F_STATUS = '$Sel_Esito' ) ";  
    }
    
    if ( "$PreIdRun" != "" ){
      $sql = $sql." AND ID_RUN_SH <= $PreIdRun ";  
    }
    
    if ( "$SelRootShell" != "" ){
        
        if ( "$NoTags" != "" ){
           $sql = $sql." AND ID_SH IN ( SELECT ID_SH FROM W_LAST_CORE_PROCESSING WHERE ID_RUN_SH = $SelRootShell )";
        }else{
          $sql = $sql." AND ID_SH||TAGS IN ( SELECT ID_SH||TAGS FROM W_LAST_CORE_PROCESSING WHERE ID_RUN_SH = $SelRootShell )";
        }
        if ( "$LastShellRun" != "" ){
           
           $sql = $sql."
           AND START_TIME IN ( SELECT MAX(START_TIME) 
               FROM W_LAST_CORE_PROCESSING a 
               WHERE 1=1
               AND a.ID_SH = b.ID_SH 
           ";
               
           if ( "$SpliVar" != "SpliVar" ){  
              $sql = $sql." AND NVL(RTRIM(a.TAGS),'-') = NVL(RTRIM(b.TAGS),'-')";
           } else {
              $sql = $sql." AND NVL(RTRIM(a.VARIABLES),'-') = NVL(RTRIM(b.VARIABLES),'-')"; 
           }
           
           $sql = $sql." 
           AND  $WhereCon ";
           
           if ( "$SelInDate" != "0" ){
              if ( "$SelInDate" != "99" ){   
                  $sql = $sql."
                    AND EXTRACT(DAY FROM START_TIME) = $SelInDate
                  ";
              } else {
                  $sql = $sql."
                    AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' )
                  ";          
              }
           }   
               
           $sql = $sql." 
           ) ";
           
        }       
        
    } else {
        
      if ( "${SelShTarget}" != "" ){
         $sql = $sql." 
         AND ID_RUN_SH = ${SelShTarget} ";
      } else {
        
        if ( "$LastShellRun" != "" ){
           
           $sql = $sql."
           AND START_TIME IN ( SELECT MAX(START_TIME) 
               FROM W_LAST_CORE_PROCESSING a 
               WHERE 1=1
               AND a.ID_SH = b.ID_SH 
           ";
               
           if ( "$SpliVar" != "SpliVar" ){  
              $sql = $sql." AND NVL(RTRIM(a.TAGS),'-') = NVL(RTRIM(b.TAGS),'-')";
           } else {
              $sql = $sql." AND NVL(RTRIM(a.VARIABLES),'-') = NVL(RTRIM(b.VARIABLES),'-')"; 
           }
           
           $sql = $sql." 
           AND  $WhereCon ";
           
           if ( "$SelInDate" != "0" ){
              if ( "$SelInDate" != "99" ){   
                  $sql = $sql."
                    AND EXTRACT(DAY FROM START_TIME) = $SelInDate
                  ";
              } else {
                  $sql = $sql."
                    AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' )
                  ";          
              }
           }   
               
           $sql = $sql." 
           ) ";
           
        }
      }
    }
    
    if ( "$DB2database" == "TASPCUSR" ){
      if ( "$HideUnify" == "HideUnify" ){
        $sql = $sql." AND ( ID_RUN_SH_FATHER IS NULL AND ID_SH != ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) ) ";
      }else{  
        if ( "$SkipUnify" == "SkipUnify" ){
          $sql = $sql." AND ( 
          ( ID_RUN_SH_FATHER IS NULL AND ID_SH != ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) )
          OR
          ( ID_RUN_SH_FATHER IN ( SELECT ID_RUN_SH FROM WORK_CORE.CORE_SH WHERE ID_SH = ( SELECT ID_SH FROM WORK_CORE.CORE_SH_ANAG WHERE SHELL = 'ElabUnify.sh' ) ) )
          )";
        }else{
          if ( "$IDSELEM" == "" ){
		    $sql = $sql." AND ID_RUN_SH_FATHER IS NULL ";
		  }
        }
      }
    }else{
      $sql = $sql." AND ID_RUN_SH_FATHER IS NULL ";
    }
    

    if ( "$HideTws" == "HideTws" ){
        $sql = $sql." AND ID_SH NOT IN ( SELECT ID_SH FROM WORK_CORE.CORE_RETI_TWS WHERE ENABLE = 'Y' ) ";
    }
    
    if ( "$SelInDate" != "0" ){
       if ( "$SelInDate" != "99" ){   
           $sql = $sql."
             AND EXTRACT(DAY FROM START_TIME) = $SelInDate
           ";
       } else {
           $sql = $sql."
             AND START_TIME > ( SELECT MAX(START_TIME) -3 DAY FROM WORK_CORE.CORE_SH WHERE $WhereCon AND TO_CHAR(START_TIME,'YYYYMM') like '$SelMeseElab' )
           ";          
       }
    }
    
    if ( "$SelEserMese" != "" ){   
       $sql = $sql."
         AND ESER_MESE = $SelEserMese
       ";
    }


    $sql = $sql." 
    ORDER BY ID_RUN_SH DESC, TIME_ORDER DESC";
           
    //echo $sql;  
    
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
        
    if ( ! $result ){
        echo $sql;
        echo "ERROR DB2 2";
    }
    $IdShOld="";
    $IdRunShOld="";
    $OLDIdSql="";
    $CntR=0;
    $CntS=0;
    $ShCntPass=0;
    $FirstIdRunSh="";
    $AllPage=array();
    while ($row = db2_fetch_assoc($stmt)) {
      $IdSh=$row['ID_SH'];
      $IdRunSh=$row['ID_RUN_SH'];
      $ShName=$row['NAME'];
      $ShSTART_TIME=$row['START_TIME'];
      $ShEND_TIME=$row['END_TIME'];
      $ShFather=$row['ID_RUN_SH_FATHER'];
      $ShLog=$row['LOG'];
      $ShStatus=$row['STATUS'];
      $ShUser=$row['USERNAME'];
      $ShDebugSh=$row['DEBUG_SH'];
      $ShDebugDb=$row['DEBUG_DB'];
      $ShMail=$row['MAIL'];
      $ShEserMese=$row['ESER_MESE'];
      $ShEserEsame=$row['ESER_ESAME'];
      $ShMeseEsame=$row['MESE_ESAME'];
      $ShSecDiff=$row['SH_SEC_DIFF'];
      $ShShellPath=$row['SHELL_PATH'];
      $ShVariables=$row['VARIABLES'];
      $ShSons=$row['N_SON'];
      $ShRc=$row['RC'];
      $ShMessage=$row['MESSAGE'];
      $ShMessage=str_replace("$ShShellPath/$ShName:",'',$ShMessage);
      $ShMessage=str_replace("'",'',$ShMessage);
      $ShLastSecDiff=$row['LASTSH_SEC_DIFF'];
      $ShTags=$row['TAGS'];
      $ShPrwEnd=$row['PREVIEW_SH_END'];
      $ShCntPass=$row['CNTPASS'];
      $IdSql=$row['ID_RUN_SQL'];
      $WaitTime=$row['WAITTIME'];
      
      if ( "$FirstIdRunSh" == "" and  "$SelNumPage" == "1" ){
        $FirstIdRunSh=$IdRunSh;
      }

      $Test="${IdSh}${ShVariables}$IdRunSh";
      if ( "$SpliVar" != "SpliVar" ){  
         $Test="${IdSh}${ShTags}$IdRunSh";
      }
      if ( "$IdShOld" != "$Test" ){
         $CntR=$CntR+1;
         $CntS=0;
         $NumPage=ceil($CntR/10);
         
         if ( ! in_array($NumPage,$AllPage)){
           array_push($AllPage,$NumPage);
         }  
         
         #if ( "$NumLast" != "" and "$NumLast" != "0"){
         #  if ( "$CntR" > "$NumLast" ){
         #    break;
         #  }
         #}
         
      } else {
        $CntS=$CntS+1;
        if ( "$CntS" == $Soglia  and "$SelShTarget" == "" and "$NumPage" == "$SelNumPage" ){
            ?>
            <li class="has-sub" title="<?php echo $ShName; ?>" >
               <a>
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;height: 30px !important;" >
                    <tr>
                        <td ><B onclick="OpenShSel(<?php echo $IdRunSh; ?>);" style="color:red;border: 1px solid red;" >Visualizzazione Step interrotta ( clicca per visualizzare a parte )</B></td>
                    </tr>
                </table>        
                </a>
            </li>
            <?php
        }
      }
      if ( "$NumPage" != "$OldNumPage" ){
        $IdShOld="";
        $OldNumPage=$NumPage;
      } 
     
      if ( ( "$NumPage" == "$SelNumPage" or "$LastShellRun" == "LastShellRun" or "$DA_RETITWS" != "" ) and ( $CntS < $Soglia or "$SelShTarget" != "" )){
      
        $ShOldDiff="";
        if ( "$ShLastSecDiff" != "" ){
           $ShOldDiff=gmdate('H:i:s', $ShLastSecDiff);
		   $ShOldDiff=floor(($ShLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $ShLastSecDiff);
        }
                                    
        $ShDiff=gmdate('H:i:s', $ShSecDiff);
		$ShDiff=floor(($ShSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $ShSecDiff);

        $Esito="";
        switch($ShStatus){
           case 'E': $Esito="Err"; break;
           case 'I': $Esito="Run"; break;
           case 'F': $Esito="Com"; break;
           case 'W': $Esito="War"; break;
           case 'M': $Esito="Frz"; break;
        }
        
        $Test="${IdSh}${ShVariables}$IdRunSh";
        if ( "$SpliVar" != "SpliVar" ){  
           $Test="${IdSh}${ShTags}$IdRunSh";
        }
        
        if ( "$IdShOld" != "$Test" ){
            if ( "$IdShOld" != "" ){
                
                ?></ul></li><?php
            }
            
            $IdShOld="${IdSh}${ShVariables}$IdRunSh";
            if ( "$SpliVar" != "SpliVar" ){  
               $IdShOld="${IdSh}${ShTags}$IdRunSh";
            }
            
            
            ?>
            <li>
                <?php if ( "$ShSons" != "0" or "$IdSql" != ""  ){   ?><label class="Esplodi" >o</label><?php } ?>           
               <a onclick="ChangeA<?php echo $InRetePasso; ?>('<?php echo $IdRunSh; ?>')" >
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;" >
                    <tr>
                        <td rowspan=2 style="min-width:3px" class="<?php echo $Esito; ?>" ></td>
                        <td rowspan=2 style="min-width:50px" title='<?php echo $ShMessage; ?>' >RC:<?php echo $ShRc; ?></td>
                        <td rowspan=2 style="min-width:320px" ><img title="<?php echo $IdRunSh; ?>" src="../images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $IdRunSh; ?>);" /><B><?php echo $ShName; ?></B>
                        <?php
							if ( "$ShStatus" == "E" ){
							  ?><img src="../images/ManualOk.png" class="IconFile" title="Manual Ok" onclick="ManualOk(<?php echo $IdRunSh; ?>)"><?php 
							}
                            if ( "$ShStatus" == "I" ){
                              ?><img src="../images/Skull.png" title="Put this Shell in an error state" onclick="ForceEnd(<?php echo $IdRunSh; ?>)" class="IconSh" style="width:30px;"><?php
                            }
                            if ( "$WaitTime" >= "60" ){
                              ?><img src="../images/WaitTime.png" title="WaitTime" class="IconSh" style="width:25px;"><?php
                            }
                        ?>
                        </td>
                        <td rowspan=2  style="min-width:170px;">
                            <?php
                            if ( "$ShowSourceSh" == "Y" ){ 
                              ?><img src="../images/File.png" class="IconFile" onclick="OpenFile(<?php echo $IdSh; ?>)"><?php 
                            }
                            ?>
                            <img src="../images/PlsqlTab.png" onclick="OpenTabFile('<?php echo $IdSh; ?>','<?php echo $IdRunSh; ?>','')" class="IconSh" style="width:25px;">
                            <?php
                            if ( "$ShLog" != "" ){
                                ?><img src="../images/Log.png" onclick="OpenLog(<?php echo $IdRunSh; ?>)" class="IconSh"><?php
                            }
                            if ( "$ShMail" == "Y" ){
                                ?><img src="../images/Mail.png" class="IconSh"><?php
                            }
                            if ( "$ShDebugSh" == "Y" ){
                                ?><img src="../images/DebugSh.png" title="DebugSh" class="IconSh"><?php
                            }
                            if ( "$ShDebugDb" == "Y" ){
                                ?><img src="../images/DebugDb.png" title="DebugDb" class="IconSh"><?php
                            }
                            ?>                     
                            <img src="../images/Graph.png" onclick="ShowGraph('<?php echo $ShName; ?>','<?php echo $ShTags; ?>','<?php echo $IdSh; ?>')" class="IconSh">
                        </td>
                        <th style="min-width:50px" class="" ><B>EserEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $ShEserEsame; ?></td>
                        <th style="min-width:50px"><B>Tags</B></th>
                        <td style="min-width:50px"><?php echo $ShTags; ?></td>
                        <th style="min-width:40px" class="ClsDett" ><B>Variables</B></th>
                        <td style="min-width:220px" class="ClsDett" ><?php echo $ShVariables; ?></td>
                        <th style="min-width:50px"><B>Start</B></th>
                        <td style="min-width:155px"><?php echo $ShSTART_TIME; ?></td>
                        <th style="min-width:80px" class="" ><B>Time</B></th>
                        <th style="min-width:80px" class="" ><B>OldTime</B></th>
                        <th class="" ><B>User</B></th>
                    </tr>
                    <tr>
                        <th style="min-width:50px" class="" ><B>MeseEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $ShMeseEsame; ?></td>
                        <th style="min-width:50px"><B>Meter</B></th>
                        <td style="min-width:155px">
                        <?php
                        if ( "$ShLastSecDiff" != "" ){
                            $SecLast=$ShSecDiff;
                            $SecPre=$ShLastSecDiff;
                            if ( "$SecPre" == "0" ){
                                $SecPre = 1;
                            }
                            $Perc = round(( $SecLast * 100 ) / $SecPre );
                            
                            if ( $Perc <= 100 and "$ShStatus" != "I" ) {
                                $SColor = "$BarraMeglio";
                            }
                            
                            if ( "$ShStatus" == "I" ) {
                                $SColor = "$BarraCaricamento";
                            }
                            if ( $Perc > 120 ) {
                                $SColor = "$BarraPeggio";
                            }
                            
                            if (
                                (   1==1
                                    and ( $Perc > 120 or $Perc < 90 ) 
                                    and  ( "$ShStatus" == "F" or "$ShStatus" == "W" )
                                    and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
                                ) 
                                or ( "$ShStatus" == "I" )
                            ) {
                                ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?php 
                                    if ("$ShStatus" == "I") {
                                        echo "active";
                                    } 
                                    ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                    if ($Perc > 100) {
                                        echo 100;
                                    } else {
                                        echo "$Perc";
                                    } 
                                    ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                    if ($Perc > 100) {
                                        $Perc = $Perc - 100;
                                        $Perc = "+" . $Perc;
                                    } else {
                                        if ( "$ShStatus" != "I" ){
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
                        <th class="ClsDett" ><B>Dir</B></th>
                        <td style="min-width:155px" class="ClsDett" ><?php echo $ShShellPath; ?></td>
                        <th><B><?php if ( "$ShEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                        <td><?php 
                        if ( "$ShEND_TIME" == "" ) {
                          echo '<font color="blue"><B>'.$ShPrwEnd.'</B></font>';
                        }else{
                          echo $ShEND_TIME; 
                        }
                        ?></td>
                        <td style="min-width:55px" class="" ><?php echo "$ShDiff"; ?></td>
                        <td style="min-width:55px" class="" ><?php echo $ShOldDiff; ?></td>
                       
                        <td style="min-width:155px" class="" ><?php echo $ShUser; ?></td>
                    </tr>
                </table>
                </a>
                <ul style="display:<?php if ( ( "$ShStatus" == "I"  and "$Sel_Esito" != "I" ) or in_array($IdRunSh, $ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                <?php 
        }

      
            
        if ( "$IdSql" != "" ){
            $SqlType=$row['TYPE_RUN'];
            $SqlStep=$row['STEP'];
            $SqlFile=$row['FILE_SQL'];
            $SqlInFile=$row['FILE_IN'];
            $SqlSTART_TIME=$row['SQL_START'];
            $SqlEND_TIME=$row['SQL_END'];
            $SqlStatus=$row['SQL_STATUS'];
            $SqlSecDiff=$row['SQL_SEC_DIFF'];
            $SqlLastSecDiff=$row['LASTSQL_SEC_DIFF'];
            $SqlPrwEnd=$row['PREVIEW_SQL_END'];
            $SqlUseRoutine=$row['USE_ROUTINE'];
            #$SqlDiff="";
            #if ( "$SqlEND_TIME" != "" ){
               $SqlDiff=gmdate('H:i:s', $SqlSecDiff);
			   $SqlDiff=floor(($SqlSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SqlSecDiff);
            #}
            
            $SqlOldDiff="";
            if ( "$SqlLastSecDiff" != "" ){
               $SqlOldDiff=gmdate('H:i:s', $SqlLastSecDiff);
			   $SqlOldDiff=floor(($SqlLastSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $SqlLastSecDiff);
            }           
          
            $SqlEsito="";
            switch($SqlStatus){
               case 'E': $SqlEsito="Err"; break;
               case 'I': $SqlEsito="Run"; break;
               case 'F': $SqlEsito="Com"; break;
               case 'W': $SqlEsito="War"; break;
               case 'M': $SqlEsito="Frz"; break;
            }
          
            ?>
            <li id="List_<?php echo $IdRunSh."_".$IdSql; ?>" class="has-sub">
                <?php if  ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){    ?><label class="Esplodi" >+</label><?php }  ?> 
               <a onclick="ChangeA<?php echo $InRetePasso; ?>('<?php echo $IdRunSh."_".$IdSql; ?>')" > 
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;" >
                    <tr>
                        <td rowspan=2 style="min-width:3px" class="<?php echo $SqlEsito; ?>" ></td>
                        <td rowspan=2 style="min-width:378px" ><B><?php echo str_replace('.','. ',$SqlStep); ?></B></td>
                        <td rowspan=2 style="min-width:70px" ><Name>
                            <?php
                            if ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){
                                ?><img src="../images/LogDb.png" onclick="OpenTab(<?php echo $IdSql; ?>)" class="IconSh"><?php
                            }
                            if ( "$SqlFile" != "Anonymous Block" and  "$SqlFile" != "SQL DB2 Block" ){
                             ?><img src="../images/File.png" onclick="OpenSqlFile(<?php echo $IdSql; ?>,'<?php echo $SqlFile; ?>')" class="IconSh" style="width:25px;"><?php
                            }
                            ?>                     
                            
                            </Name>
                            
                        </td>
                        <th style="min-width:50px" ><B>Type</B></th>
                        <td style="min-width:155px"><?php echo $SqlType; ?></td>
                        <th style="min-width:40px"  class="ClsDett" ><B>Sql</B></th>
                        <td style="min-width:300px" onclick="OpenSqlFile(<?php echo $IdSql; ?>,'<?php echo $SqlFile; ?>')" style="cursor:pointer;"  class="ClsDett" >
                            <?php echo $SqlFile; ?>
                        </td>
                        <td style="min-width:40px" rowspan=2   >                           
                           <?php 
                            if ( "$SqlFile" != "Anonymous Block" and  "$SqlFile" != "SQL DB2 Block" ){
                             ?><img src="../images/PlsqlTab.png" onclick="OpenTabFile('<?php echo $IdSh; ?>','<?php echo $IdRunSh; ?>','<?php echo $IdSql; ?>')" class="IconSh" style="width:25px;"><?php
                            }  
                            ?> 
                        </td>
                        <th style="min-width:50px"><B>Start</B></th>
                        <td style="min-width:155px"><?php echo $SqlSTART_TIME; ?></td>
                        <th style="min-width:80px" class="" ><B>Time</B></th>
                        <th style="min-width:80px" class="" ><B>OldTime</B></th>
                    </tr>
                    <tr>
                        <th style="min-width:50px"><B>Meter</B></th>
                        <td style="min-width:155px">
                        <?php
                        if ( "$SqlLastSecDiff" != "" ){
                            $SecLast=$SqlSecDiff;
                            $SecPre=$SqlLastSecDiff;
                            if ( "$SecPre" == "0" ){
                                $SecPre = 1;
                            }
                            $Perc = round(( $SecLast * 100 ) / $SecPre );

                            if ( $Perc <= 100 and "$SqlStatus" != "I" ) {
                                $SColor = "$BarraMeglio";
                            }
                            
                            if ( "$SqlStatus" == "I" ) {
                                $SColor = "$BarraCaricamento";
                            }   
                            if ( $Perc > 120 ) {
                                $SColor = "$BarraPeggio";
                            }      
                            
                            if (
                                (   1==1
                                    //and ( $Perc > 120 or $Perc < 90 ) 
                                    and  ( "$SqlStatus" == "F" or "$SqlStatus" == "W" )
                                    and ( $SecLast - $SecPre > $GAP or $SecLast - $SecPre < -$GAP )
                                ) 
                                or ( "$SqlStatus" == "I" )
                            ) {
                                ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?php 
                                    if ("$SqlStatus" == "I") {
                                        echo "active";
                                    } 
                                    ?>" role="progressbar" aria-valuenow="<?php echo $Perc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                    if ($Perc > 100) {
                                        echo 100;
                                    } else {
                                        echo "$Perc";
                                    } 
                                    ?>%;background-color: <?php echo "$SColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                    if ($Perc > 100) {
                                        $Perc = $Perc - 100;
                                        $Perc = "+" . $Perc;
                                    } else {
                                        if ( "$SqlStatus" != "I" ){
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
                        <th class="ClsDett" ><B><?php 
                        if ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){
                          ?>Note<?php
                        }else{
                          ?>File<?php 
                        }
                        ?></B></th>
                        <td class="ClsDett" ><?php                        
                        //if ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ){
                        //  $sqlt="SELECT NOTES FROM WORK_ELAB.LOG_ROUTINES WHERE ID_RUN_SQL = $IdSql ORDER BY START DESC";
                        //  $stmtt=db2_prepare($conn, $sqlt);
                        //  $resultt=db2_execute($stmtt);
                        //  if ( ! $resultt ){
                        //      echo $sqlt;
                        //      echo "ERROR DB2 2";
                        //  }
                        //  while ($rowt = db2_fetch_assoc($stmtt)) {
                        //    echo $rowt['NOTES'];
                        //  }
                        //}else{
                          echo $SqlInFile; 
                        //}
                        ?></td>
                        <th><B><?php if ( "$SqlEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                        <td><?php
                        if ( "$SqlEND_TIME" == "" ) {
                            echo '<font color="blue"><B>'.$SqlPrwEnd.'</B></font>';
                        }else{
                          echo $SqlEND_TIME; 
                        }
                        ?></td>
                        <td style="min-width:55px" class="" ><?php echo $SqlDiff; ?></td>
                        <td style="min-width:55px" class="" ><?php echo $SqlOldDiff; ?></td>
                    </tr>
                </table>
                </a>
                <?php
                if ( ( "$SqlType" == "PLSQL" and "$SqlUseRoutine" != "0" ) or ( "$SSqlStatus" == "I" ) ){
                    SearchFatherRoutine($IdSql,$IdRunSh,$SSqlStatus);         
                }
                ?>
            </li>
            <?php
                        
        } 
        $StepType=$row['TYPE_RUN'];
        if ( "$StepType" == "STEP" ){
            $StepName=$row['STEP'];
            $StepTime=$row['SQL_START'];
            
			
			if ( substr($StepName,1,10 ) == "Eseguo Shell in USER :" ){
			   $TstRunUser="select ID_RUN_SH
               FROM TASPCUSR.WORK_CORE.V_CORE_PROCESSING
               WHERE 1=1
               AND VARIABLES like ' SON b $IdRunSh%'
               --AND USERNAME = 'gu01009'
               AND START_TIME >= ( SELECT START_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdRunSh)
               AND END_TIME   <= ( SELECT END_TIME FROM WORK_CORE.CORE_SH WHERE ID_RUN_SH = $IdRunSh)
               ORDER BY START_TIME";
			    $stmt=db2_prepare($conn, $TstRunUser);
                $result=db2_execute($stmt);
			   
               if ( ! $result ){
                   echo $TstRunUser;
                   echo "ERROR DB2 2";
               }
			   $TCnt=0;
               while ($row5 = db2_fetch_assoc($stmt)) {
                   $TIdRunSh=$row5['ID_RUN_SH'];
				   $TCnt=$TCnt+1;
			       ?>
			       <li class="has-sub" id="Open<?php echo $TIdRunSh."_".$TCnt; ?>" ></li>
				   <script>
				      $( "#Open<?php echo $TIdRunSh."_".$TCnt; ?>" ).load('../PHP/StatoShell_USER.php?IDSELEM=<?php echo $TIdRunSh; ?>').show();
				   </script>
			       <?php
			   }
			}else{

            ?>
            <li class="has-sub">
               <a>
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;height: 30px !important;" >
                    <tr>
                        <td style="min-width:3px" class="ComSp" ></td>
                        <td style="min-width:700px"><B><?php echo $StepName; ?></B></td>
                        <th style="min-width:50px" class="" ><B>TimeStamp</B></th>
                        <td style="min-width:155px"><?php echo $StepTime; ?></td>
                    </tr>
                </table>        
                </a>
            </li>
            <?php
			}
        }
        
      
      
        $FIdSh=$row['F_ID_SH'];
 
        if ( "$FIdSh" != "" ){
          
            $FIdRunSh=$row['F_ID_RUN_SH'];
            $FShName=$row['F_NAME'];
            $FShSTART_TIME=$row['F_START_TIME'];
            $FShEND_TIME=$row['F_END_TIME'];
            $FShFather=$row['F_ID_RUN_SH_FATHER'];
            $FShLog=$row['F_LOG'];
            $FShStatus=$row['F_STATUS'];
            $FShUser=$row['F_USERNAME'];
            $FShDebugSh=$row['F_DEBUG_SH'];
            $FShDebugDb=$row['F_DEBUG_DB'];
            $FShMail=$row['F_MAIL'];
            $FShEserMese=$row['F_ESER_MESE'];
            $FShEserEsame=$row['F_ESER_ESAME'];
            $FShMeseEsame=$row['F_MESE_ESAME'];
            $FShSecDiff=$row['F_SH_SEC_DIFF'];
            $FShShellPath=$row['F_SHELL_PATH'];
            $FShVariables=$row['F_VARIABLES'];
            $FShSons=$row['F_N_SON'];
            $FShRc=$row['F_RC'];
            $FShMessage=$row['F_MESSAGE'];
            $FShMessage=str_replace("$FShShellPath/$FShName:",'',$FShMessage);
            $FShLastSecDiff=$row['F_LASTSH_SEC_DIFF'];
            $FShTags=$row['F_TAGS'];
            $FShPrwEnd=$row['F_PREVIEW_SH_END'];
            $FShCntPass=$row['F_CNTPASS'];
            $FIdSql=$row['F_ID_RUN_SQL'];
            $FShDiff=gmdate('H:i:s', $FShSecDiff);
			$FShDiff=floor(($FShSecDiff-1)/(60*60*24))."g ".gmdate('H:i:s', $FShSecDiff);
            
            $FEsito="";
            switch($FShStatus){
                case 'E': $FEsito="Err"; break;
                case 'I': $FEsito="Run"; break;
                case 'F': $FEsito="Com"; break;
                case 'W': $FEsito="War"; break;
                case 'M': $FEsito="Frz"; break;
            }
           
            $SelClsSh="";
            if( "$FIdRunSh" == "$SelShell" ){$SelClsSh="ClsRoot";}
           
            ?>
            <li class="<?php echo "$SelClsSh"; ?>" >
                <?php if ( "$FShSons" != "0" or "$FIdSql" != ""  ){   ?><label class="Esplodi" >o</label><?php } ?>           
               <a onclick="ChangeA<?php echo $FInRetePasso; ?>('<?php echo $FIdRunSh; ?>')" >
               <table class="ExcelTable" style="cursor:pointer;box-shadow: 0px 0px 0px 1px black;" >
                    <tr>
                        <td rowspan=2 style="min-width:3px" class="<?php echo $FEsito; ?>" ></td>
                        <td rowspan=2 style="min-width:50px" title='<?php echo $FShMessage; ?>' >RC:<?php echo $FShRc; ?></td>
                        <td rowspan=2 style="min-width:320px" ><img title="<?php echo $FIdRunSh; ?>" src="../images/Shell.png" class="IconFile"  onclick="OpenShSel(<?php echo $FIdRunSh; ?>);" /><B><?php echo $FShName; ?></B></td>
                        <td rowspan=2  style="min-width:170px;">
                            <?php
                            if ( "$ShowSourceSh" == "Y" ){ 
                              ?><img src="../images/File.png" class="IconFile" onclick="OpenFile(<?php echo $FIdSh; ?>)"><?php 
                            }
                            ?>                      
                            <img src="../images/PlsqlTab.png" onclick="OpenTabFile('<?php echo $FIdSh; ?>','<?php echo $FIdRunSh; ?>','')" class="IconSh" style="width:25px;">
                            <?php
                            if ( "$FShLog" != "" ){
                                ?><img src="../images/Log.png" onclick="OpenLog(<?php echo $FIdRunSh; ?>)" class="IconSh"><?php
                            }
                            if ( "$FShMail" == "Y" ){
                                ?><img src="../images/Mail.png" class="IconSh"><?php
                            }
                            if ( "$FShDebugSh" == "Y" ){
                                ?><img src="../images/DebugSh.png" title="DebugSh" class="IconSh"><?php
                            }
                            if ( "$FShDebugDb" == "Y" ){
                                ?><img src="../images/DebugDb.png" title="DebugDb" class="IconSh"><?php
                            }
                            ?>                     
                            <img src="../images/Graph.png" onclick="ShowGraph('<?php echo $FShName; ?>','<?php echo $FShTags; ?>','<?php echo $FIdSh; ?>')" class="IconSh">
                        </td>
                        <th style="min-width:50px" class="" ><B>EserEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $FShEserEsame; ?></td>
                        <th style="min-width:50px"><B>Tags</B></th>
                        <td style="min-width:50px"><?php echo $FShTags; ?></td>
                        <th style="min-width:40px" class="ClsDett" ><B>Variables</B></th>
                        <td style="min-width:220px" class="ClsDett" ><?php echo $FShVariables; ?></td>
                        <th style="min-width:50px"><B>Start</B></th>
                        <td style="min-width:155px"><?php echo $FShSTART_TIME; ?></td>
                        <th style="min-width:80px" class="" ><B>Time</B></th>
                        <th style="min-width:80px" class="" ><B>OldTime</B></th>
                        <th class="" ><B>User</B></th>
                    </tr>
                    <tr>
                        <th style="min-width:50px" class="" ><B>MeseEsame</B></th>
                        <td style="min-width:50px" class="" ><?php echo $FShMeseEsame; ?></td>
                        <th style="min-width:50px"><B>Meter</B></th>
                        <td style="min-width:155px">
                        <?php
                        if ( "$ShLastSecDiff" != "" ){
                            $FSecLast=$FShSecDiff;
                            $FSecPre=$FShLastSecDiff;
                            if ( "$FSecPre" == "0" ){
                                $FSecPre = 1;
                            }
                            $FPerc = round(( $FSecLast * 100 ) / $FSecPre );
                            
                            if ( $FPerc <= 100 and "$FShStatus" != "I" ) {
                                $FSColor = "$FBarraMeglio";
                            }
                            
                            if ( "$FShStatus" == "I" ) {
                                $FSColor = "$FBarraCaricamento";
                            }
                            if ( $FPerc > 120 ) {
                                $FSColor = "$FBarraPeggio";
                            }
                            
                            if (
                                (   1==1
                                    and ( $FPerc > 120 or $FPerc < 90 ) 
                                    and  ( "$FShStatus" == "F" or "$FShStatus" == "W" )
                                    and ( $FSecLast - $FSecPre > $GAP or $FSecLast - $FSecPre < -$GAP )
                                ) 
                                or ( "$FShStatus" == "I" )
                            ) {
                                ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?php 
                                    if ("$FShStatus" == "I") {
                                        echo "active";
                                    } 
                                    ?>" role="progressbar" aria-valuenow="<?php echo $FPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="text-align:right;padding-right: 5px;min-width: 2em; width: <?php 
                                    if ($FPerc > 100) {
                                        echo 100;
                                    } else {
                                        echo "$FPerc";
                                    } 
                                    ?>%;background-color: <?php echo "$FSColor"; ?>;height: 100%;float:left;border-radius: 5px;" ><LABEL style="font-weight: 500;"><?php
                                    if ($FPerc > 100) {
                                        $FPerc = $FPerc - 100;
                                        $FPerc = "+" . $FPerc;
                                    } else {
                                        if ( "$FShStatus" != "I" ){
                                          $FPerc = $FPerc - 100;
                                        } 
                                    }                                   
                                    echo $FPerc;
                                    ?>%</LABEL>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>                      
                        </td>
                        <th class="ClsDett" ><B>Dir</B></th>
                        <td style="min-width:155px" class="ClsDett" ><?php echo $FShShellPath; ?></td>
                        <th><B><?php if ( "$FShEND_TIME" == "" ) { echo "Prw"; } ?>End</B></th>
                        <td><?php 
                        if ( "$FShEND_TIME" == "" ) {
                          echo '<font color="blue"><B>'.$FShPrwEnd.'</B></font>';
                        }else{
                          echo $FShEND_TIME; 
                        }
                        ?></td>
                        <td style="min-width:55px" class="" ><?php echo $FShDiff; ?></td>
                        <td style="min-width:55px" class="" ><?php echo $FShOldDiff; ?></td>
                       
                        <td style="min-width:155px" class="" ><?php echo $FShUser; ?></td>
                    </tr>
                </table>
                </a>
                <ul style="display:<?php if ( "$FShStatus" == "I" or in_array($FIdRunSh, $ListIdOpen) ) { echo "block"; } else { echo "none"; } ?>;">
                  <?php RecSonsh($FIdSh,$FIdRunSh,'N'); ?>
                </ul>
            </li>
            <?php
        }         
        $InGiro=1;      
      } else {        
            if ( "$InGiro" == "1" ){
              if ( "$IdShOld" != "$Test" ){
                ?></ul></li><?php
              }
              $InGiro=0;
            }
            $IdShOld="${IdSh}${ShVariables}$IdRunSh";
            if ( "$SpliVar" != "SpliVar" ){  
               $IdShOld="${IdSh}${ShTags}$IdRunSh";
            }
      }
   } 
    

    ?></li></ul>
    <?php if ( "$INPASSO" == "" ){ ?><BR><BR><?php }
    if ( "$DA_RETITWS" == ""  ) {
         ?>
        <input type="hidden" id="CambioPag" value="" >
        <table  id="PageTab" style="width:<?php echo count($AllPage)*30;?>px"><tr><?php
        foreach( $AllPage as $Page ){
            ?><td style="width:30px;background-color:white;text-align:center;cursor:pointer;" onclick="ChangePage(<?php echo $Page; ?>)" <?php 
            if ( "$Page" == "$SelNumPage" ){ ?> class="SelPage" <?php }
            ?> ><?php echo $Page; ?>
            </td><?php
        }
        ?></tr></table><?php
    }
    db2_close();
    ?></div><?php
}
?>
<Script>

<?php
if ( "$FirstIdRunSh" != "" ){
  ?>$('#PreIdRun').val('<?php echo $FirstIdRunSh; ?>');<?php
}
?>

function Refresh(){
  $('#Waiting').show();
  $('#FormEserEsame').submit();
};

function OpenShSel(vId){
    window.open('../PAGE/PgStatoShell.php?IDSELEM='+vId);
}


function ChangePage(vPage){
    $('#SelNumPage').val(vPage);
    $('#CambioPag').val('1');
    Refresh();
}

function ChangeA<?php echo $InRetePasso; ?>(vIdSel){
    var vFound=false;
    var vListId=$('#ListOpenId<?php echo $InRetePasso; ?>').val();  
    if ( vListId != '' ){
        var IdArray = vListId.split(',');
        for( var i=1; i<IdArray.length; i++ ){      
            var vTargets=IdArray[i];
            if ( vTargets == vIdSel ){
                vFound=true;
            }
        }
    }
    if ( ! vFound ) {
        vListId=vListId+','+vIdSel;     
    } else {
        for( var i=1; i<IdArray.length; i++ ){
            var vTargets=IdArray[i];
            if ( vTargets.startsWith(vIdSel) ){
               vListId=vListId.replace(','+vTargets,'');    
            }
        }
    }
    $('#ListOpenId<?php echo $InRetePasso; ?>').val(vListId);
}

function ForceEnd(vIdRunSh){
   var re = confirm('Are you sure you want to put this Shell in an error state?');
   if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "ForceEnd")
        .val(vIdRunSh);
        $('#FormEserEsame').append($(input));
        $('#FormEserEsame').submit();

   }  
}


function ManualOk(vIdRunSh){
   var re = confirm('Are you sure you want to change the status to manual ok');
   if ( re == true) {
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "ManualOk")
        .val(vIdRunSh);
        $('#FormEserEsame').append($(input));
        $('#FormEserEsame').submit();

   }  
}


$('#ListShell<?php echo $InRetePasso; ?> > ul > li > a').click(function() {
      $('#ListShell<?php echo $InRetePasso; ?> li').removeClass('active');
      $(this).closest('li').addClass('active');
      var checkElement = $(this).next();
      if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        $(this).closest('li').removeClass('active');
        checkElement.slideUp('normal');
      }
      if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        //$('#ListShell<?php echo $InRetePasso; ?> ul ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
      }
      if($(this).closest('li').find('ul').children().length == 0) {
        return true;
      } else {
        return false;
      }
});

$('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > a').click(function() {
  $('#ListShell<?php echo $InRetePasso; ?> ul li').removeClass('active');
  $(this).closest('li').addClass('active');
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;
  }
});

$('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > a').click(function() {
  $('#ListShell<?php echo $InRetePasso; ?> ul ul li').removeClass('active');
  $(this).closest('li').addClass('active');
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;
  }
});

$('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > ul > li > a').click(function() {
  $('#ListShell<?php echo $InRetePasso; ?> ul ul ul li').removeClass('active');
  $(this).closest('li').addClass('active');
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;
  }
});

$('#RCLegend').click(function(){
    $('#ShowRCLegend').show();
});
$('#CloseRcLegend').click(function(){
    $('#ShowRCLegend').hide();
});

$('#PLSSHOWDETT').change(function(){
    if ( $('#PLSSHOWDETT').is(":checked") ){
      $('.ClsDett').each(function(){ 
        $(this).show();
      });
    } else{
      $('.ClsDett').each(function(){ 
        $(this).hide();
      });
    }
});



</script> 
<script>
$('#FormEserEsame').submit(function(){
    if ( $('#CambioPag').val() == '1' ){
      $('#TopScrollShell').val('');  
      $('#LeftScrollShell').val('');
    }else{
      $('#TopScrollShell').val($('#ListShell<?php echo $InRetePasso; ?>').scrollTop());  
      $('#LeftScrollShell').val($('#ListShell<?php echo $InRetePasso; ?>').scrollLeft());
    }
});     


<?php if ( "$INPASSO" == "" ){ ?>
$('#ListShell<?php echo $InRetePasso; ?>').scrollTop($('#TopScrollShell').val());
$('#ListShell<?php echo $InRetePasso; ?>').scrollLeft($('#LeftScrollShell').val());
<?php } ?>  

setInterval(function(){ 
if ( $('#AutoRefresh').is(':checked') || $('#AutoRefresh2').is(':checked') ){
Refresh(); 
}
}, 30000);

    function OpenNipote(vIdSh,vIdRunSh){
      if ( $("#ShowFiglio"+vIdSh+'_'+vIdRunSh).is(':hidden') && ! $(".Figlio"+vIdRunSh).length  ){
        ChangeA<?php echo $InRetePasso; ?>(vIdRunSh);
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "OpenNipote")
        .val('OpenNipote'+vIdRunSh);
        $('#FormEserEsame').append($(input));
        Refresh();
      }
    }
</script>
