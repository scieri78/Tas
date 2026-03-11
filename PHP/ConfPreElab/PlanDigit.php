
<style>
#tabSel{
    margin-top:30px;
    margin-bottom:30px;
}

</style>
<?php

if ( "$find" == "1" )  {
  if ( "$IdWorkFlow" == "" or "$IdProcess" == ""  ) { exit; }

  $Salva=$_POST['Salva'];
  $oldTipo=$_POST['oldTipo'];
  $SelTipo=$_POST['SelTipo'];
  $oldWave=$_POST['oldWave'];
  $SelWave=$_POST['SelWave'];
    
      if ( "$Salva" != "" ){	
		  
        $Ambito="PLANDIGIT";
        
        if ( "$oldTipo" != "$SelTipo" ){
          $Campo="Tipo";
          $Error=null;
          $Note=null;
          
          $Sql='CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);
          db2_bind_param($stmt, 1,  "IdProcess" , DB2_PARAM_IN);
          db2_bind_param($stmt, 2,  "Ambito"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 3,  "Campo"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 4,  "SelTipo"   , DB2_PARAM_IN);
          db2_bind_param($stmt, 5,  "User"      , DB2_PARAM_IN);
          db2_bind_param($stmt, 6,  "Error"     , DB2_PARAM_OUT);
          db2_bind_param($stmt, 7,  "Note"      , DB2_PARAM_OUT);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
             echo "ERROR DB2 SetParametriIdProcess:".db2_stmt_errormsg();
          }
        }
        
        if ( "$oldWave" != "$SelWave" ){
          $Campo="Wave";
          $Error=null;
          $Note=null;
          $Sql='CALL WFS.K_WFS.SetParametriIdProcess(?, ?, ?, ?, ?, ?, ?)';
          $stmt = db2_prepare($conn, $Sql);
          db2_bind_param($stmt, 1,  "IdProcess" , DB2_PARAM_IN);
          db2_bind_param($stmt, 2,  "Ambito"    , DB2_PARAM_IN);
          db2_bind_param($stmt, 3,  "Campo"     , DB2_PARAM_IN);
          db2_bind_param($stmt, 4,  "SelWave"   , DB2_PARAM_IN);
          db2_bind_param($stmt, 5,  "User"      , DB2_PARAM_IN);
          db2_bind_param($stmt, 6,  "Error"     , DB2_PARAM_OUT);
          db2_bind_param($stmt, 7,  "Note"      , DB2_PARAM_OUT);
          
          $result=db2_execute($stmt);
          if ( ! $result ){
             echo "ERROR DB2 SetParametriIdProcess:".db2_stmt_errormsg();
          }
        }
        
        $Errore=0;
        $Note="";
        $CallP='CALL WFS.K_WFS.ValidaLegame(?, ?, ?, ?, ?, ? )';
        $stmt = db2_prepare($conn, $CallP);
        db2_bind_param($stmt, 1, "IdWorkFlow"  , DB2_PARAM_IN);
        db2_bind_param($stmt, 2, "IdProcess"   , DB2_PARAM_IN);
        db2_bind_param($stmt, 3, "IdLegame"    , DB2_PARAM_IN);
        db2_bind_param($stmt, 4, "User"        , DB2_PARAM_IN);
        db2_bind_param($stmt, 5, "Errore"      , DB2_PARAM_OUT);
        db2_bind_param($stmt, 6, "Note"        , DB2_PARAM_OUT);
        $res=db2_execute($stmt);
        
        if ( ! $res) {
          echo "PLSQL Procedure Exec Error ".db2_stmt_errormsg($stmt);
        } else {
        
          if ( $Errore != 0 ) {
            echo "PLSQL Procedure Calling Error $Errore: ".$Note;
          }
        
        }        
        
      }
	  
	  $SqlList="SELECT CAMPO, VALORE FROM WFS.PARAMETRI_ID_PROCESS WHERE ID_PROCESS = $IdProcess";
      $stmt=db2_prepare($conn, $SqlList);
      $res=db2_execute($stmt);
      if ( ! $res) { echo "stmt Exec Error ".db2_stmt_errormsg(); }
      while ($row = db2_fetch_assoc($stmt)) {
          $TabCampo=$row['CAMPO']; 
		  $TabValore=$row['VALORE']; 
		  ${'Conf'.$TabCampo}=$TabValore;
	  }
	  
  ?>

    <CENTER>
    <table id="tabSel" class="ExcelTable" >
    <tr>
    <td>Tipo Plan</td>
    <td>
    <select id="Tipo" name="SelTipo" >
        <option value="PD" >PD</option> 
    </select>
    <input type="Hidden" name="oldTipo" value="$SelTipo">
    </td>
    </tr>
    <tr>
    <td>
    Wave
    </td>
    <td>
    <select id="Wave" name="SelWave" >
       <option value="New" >New</option>
       <?php
      $SqlList="SELECT DISTINCT WAVE FROM MVBS.PLAN_DIGIT WHERE ESER_ESAME <= SUBSTRING($IdProcess,1,4) ORDER BY WAVE";
      $stmt=db2_prepare($conn, $SqlList);
      $res=db2_execute($stmt);
      if ( ! $res) { echo "stmt Exec Error ".db2_stmt_errormsg(); }
      while ($row = db2_fetch_assoc($stmt)) {
          $TabWave=$row['WAVE']; 
          ?><option value="<?php echo $TabWave; ?>"  <?php if ( "$TabWave" == "$ConfWave" ){ ?>Selected<?php } ?> ><?php echo $TabWave; ?></option><?php
      } 
       ?>
    </select>
    <input type="Hidden" name="oldWave" value="$SelWave">
    </td>
    </tr>
    </table>
	<?php
	if ( "$EsitoDip" != "F" and "$EsitoDip" != "W" ){
      ?><input type="Submit" name="Salva" value="Salva"><?php
    }
	?>
    </CENTER>

  <?php

}
?>


