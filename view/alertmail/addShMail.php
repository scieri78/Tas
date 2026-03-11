
   <?php
       if(!count($datiTableList)){?>
<script>
  $("#dialogMail").dialog("close");
  </Script>

<?php  }

   ?>
   <table class="display dataTable">
		<thead class="headerStyle">
		<tr>
		<th>EMAIL</th>
      <th>START</th>
      <th>END</th>
	  <th></th>
   </tr> 
  </thead>
   <?php
   $ListIdFind = array();

    foreach ($datiTableList as $row) {
        $ID_SH=$row['ID_SH'];
        $SHELL=$row['SHELL'];
        $SHELL_PATH=$row['SHELL_PATH'];
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $FLG_START=$row['FLG_START'];
        $FLG_END=$row['FLG_END'];
        $MAIL=$row['MAIL'];
		$ListIdFind[]=$ID_MAIL_ANAG;
  ?>

  <tbody>
  <tr class="Dett<?php echo $ID_SH; ?>"  >
           
            <td><?php echo $MAIL; ?></td>
            <td>
            <?php
            if ( $FLG_START == "N" ){
              ?><i class="fa-solid fa-xmark Puls" width="25px" class="closeIconStyle" style="color: red;"
			  onclick="UpdateSHMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>,'Start','Y')"><?php
            }else{
              ?><i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"
			  onclick="UpdateSHMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>,'Start','N')"/><?php
            }
            ?>
            </td>
            <td>
            <?php
            if ( $FLG_END == "N" ){
              ?><i class="fa-solid fa-xmark Puls" width="25px" class="closeIconStyle" style="color: red;"
			  onclick="UpdateSHMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>,'End','Y')"><?php
            }else{
              ?><i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"
			  onclick="UpdateSHMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>,'End','N')"/><?php
            }
            ?>
            </td>
            <td><i class="fa-solid fa-trash-can trashIconStyle" width="35px" style="color: black;" onclick="RemoveMail(<?php echo $ID_SH; ?>,<?php echo $ID_MAIL_ANAG; ?>)"/>
            </td>
         </tr>   
		 
	<?php
            }
            ?>
			
	
    <tr class="Dett<?php echo $ID_SH; ?>">
       
           <input type="hidden" id="AddInShMail" name="AddInShMail" value="<?php echo $ID_SH; ?>" > 
       
        <td>
             <select class="selectSearch"  id="AddInMail"  name="AddInMail" style="width:200px" >
                <option value=".." >..</option>
                <?php
                foreach( $ListMail as $DettMail ){
                    $IdMail=$DettMail[0];
                    $Mail=$DettMail[1];
                    $NameMail=$DettMail[2];
                    $UsrMail=$DettMail[3];
                    if( ! in_array($IdMail ,$ListIdFind) ){
                        ?><option value="<?php echo $IdMail; ?>" ><?php echo "$NameMail ($UsrMail)"; ?></option><?php
                    }
                }
                ?>
             </select>
        </td>
        <td>
             <select class="selectNoSearch" id="AddInStart" name="AddInStart" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>
        <td>
             <select class="selectNoSearch" id="AddInEnd" name="AddInEnd" >
                <option value="Y" >Yes</option>
                <option value="N" >No</option>
             </select>      
        </td>       
        <td>
		<a onclick="AddInNewMail();">
		<i class="fa-solid fa-square-plus addnewIconStyle"></i>
		</a></td>
     </tr>   
	 
	
</tbody>
      </table>
	  
	  
      <script src="./view/alertmail/JS/addShMail.js?p=<?php echo rand(1000,9999);?>"></script>