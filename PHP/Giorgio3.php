<style>
.ShLine{
    border-left:0px;
    border-right:0px;
    border-bottom: 1px solid black;
    padding:5px;
    padding-bottom:10px;
}
.Puls{
    cursor:pointer;
}
.LabelMails{
    float:left;
    margin:5px;
}
</style>
<?php
include '../GESTIONE/sicurezza.php';
if ( $find == 1 )  {
    $AddName     =$_POST['AddName'];
    $AddUsername =$_POST['AddUsername'];
    $AddMail     =$_POST['AddMail'];

    $AddNewMail=$_POST['AddNewMail'];
    if ( "$AddNewMail" != "" ){

        if ( "$AddName" != "" and "$AddMail" != "" ){
            $sql = "INSERT INTO WORK_CORE.CORE_MAIL_ANAG(ID_MAIL_ANAG,NAME,USERNAME,MAIL,TECNIC,VALID,CHIUSURA,CCN) VALUES
            (
            (SELECT Max(NVL(ID_MAIL_ANAG,0))+1 FROM WORK_CORE.CORE_MAIL_ANAG ),
            '$AddName',
            '$AddUsername',
            '$AddMail',
            'N',
            'Y',
			'N',
			'N'
            )
            ";
            $conn = db2_connect($db2_conn_string, '', '');
            $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            if ( ! $result ){
                echo "ERROR DB2 ADD NEW MAIL";
            } else {
                $AddName     = "";
                $AddUsername = "";
                $AddMail     = "";
            }
            db2_close($db2_conn_string);

        }
    }

    $RemoveMail=$_POST['RemoveMail'];
    if ( "$RemoveMail" != "" ){
        $conn = db2_connect($db2_conn_string, '', '');
        $sql = "DELETE FROM WORK_CORE.CORE_MAIL_ANAG WHERE ID_MAIL_ANAG = $RemoveMail";
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( $result ){
            $sql = "DELETE FROM WORK_CORE.CORE_MAIL_ASSIGN WHERE ID_MAIL_ANAG = $RemoveMail";
            $stmt=db2_prepare($conn, $sql);
            $result=db2_execute($stmt);
            if ( ! $result ){
                echo "ERROR DB2 DELETE ASSIGN";
            }
        } else {
                echo "ERROR DB2 DELETE ANAG";
        }
        db2_close($db2_conn_string);
    }

    $DisableTecMail=$_POST['DisableTecMail'];
    if ( "$DisableTecMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET TECNIC = 'N' WHERE ID_MAIL_ANAG = $DisableTecMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }

    $EnableTecMail=$_POST['EnableTecMail'];
    if ( "$EnableTecMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET TECNIC = 'Y' WHERE ID_MAIL_ANAG = $EnableTecMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }

    $DisableMail=$_POST['DisableMail'];
    if ( "$DisableMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET VALID = 'N' WHERE ID_MAIL_ANAG = $DisableMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }

    $EnableMail=$_POST['EnableMail'];
    if ( "$EnableMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET VALID = 'Y' WHERE ID_MAIL_ANAG = $EnableMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }


    $DisableChiusura=$_POST['DisableChiusura'];
    if ( "$DisableChiusura" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET CHIUSURA = 'N' WHERE ID_MAIL_ANAG = $DisableChiusura";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }

    $EnableChiusura=$_POST['EnableChiusura'];
    if ( "$EnableChiusura" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET CHIUSURA = 'Y' WHERE ID_MAIL_ANAG = $EnableChiusura";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }


    $DisableCcnMail=$_POST['DisableCcnMail'];
    if ( "$DisableCcnMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET CCN = 'N' WHERE ID_MAIL_ANAG = $DisableCcnMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }

    $EnableCcnMail=$_POST['EnableCcnMail'];
    if ( "$EnableCcnMail" != "" ){
        $sql = "UPDATE WORK_CORE.CORE_MAIL_ANAG SET CCN = 'Y' WHERE ID_MAIL_ANAG = $EnableCcnMail";
        $conn = db2_connect($db2_conn_string, '', '');
        $stmt=db2_prepare($conn, $sql);
        $result=db2_execute($stmt);
        if ( ! $result ){
            echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
        }
        db2_close($db2_conn_string);
    }


    $ListMail=array();
    $sql = "SELECT ID_MAIL_ANAG, MAIL, NAME, USERNAME FROM WORK_CORE.CORE_MAIL_ANAG WHERE VALID = 'Y' ORDER BY USERNAME";
    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);
    if ( ! $result ){
        echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
    }
    db2_close($db2_conn_string);
    while ($row = db2_fetch_assoc($stmt)) {
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $MAIL=$row['MAIL'];
        $NAME=$row['NAME'];
        $USERNAME=$row['USERNAME'];
        array_push($ListMail, array("$ID_MAIL_ANAG","$MAIL","$NAME","$USERNAME") );
    }


    $sql = 'SELECT
    ID_MAIL_ANAG,
    NAME,
    USERNAME,
    MAIL,
    TECNIC,
    VALID,
	CHIUSURA,
    CCN
    FROM WORK_CORE.CORE_MAIL_ANAG
    ORDER BY MAIL';

    $conn = db2_connect($db2_conn_string, '', '');
    $stmt=db2_prepare($conn, $sql);
    $result=db2_execute($stmt);

    if ( ! $result ){
        echo "ERROR DB2".db2_stmt_errormsg($stmt).':'.$sql;
    }

    db2_close($db2_conn_string);
    ?>
    <form id="FormMail" method="POST">
    <table style="width:100%;background:white;">
    <tr style="border-bottom:1px solid black;">
        <th></th>
        <th>USERNAME</th>
        <th>NAME</th>
        <th>MAIL</th>
        <th>TECNIC</th>
        <th>VALID</th>
		<th>RETI TWS</th>
		<th>CCN</th>
    </tr>
    <?php
    $ArrMail=array();
    while ($row = db2_fetch_assoc($stmt)) {
        $ID_MAIL_ANAG=$row['ID_MAIL_ANAG'];
        $USERNAME=$row['USERNAME'];
        $NAME=$row['NAME'];
        $MAIL=$row['MAIL'];
        $TECNIC=$row['TECNIC'];
        $VALID=$row['VALID'];
		$CHIUSURA=$row['CHIUSURA'];
		$CCN=$row['CCN'];

        ?><tr style="border-bottom:1px solid black;">
                <td><img src="../images/Cestino.png" width="25px" class="Puls" onclick="RemoveMail('<?php echo $ID_MAIL_ANAG; ?>')"></td>
                <td><?php echo $USERNAME; ?></td>
                <td><?php echo $NAME; ?></td>
                <td><?php echo $MAIL; ?></td>
                <td><?php
                if ( "$TECNIC" == "Y" ){
                    ?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableTecMail('<?php echo $ID_MAIL_ANAG; ?>')"><?php
                } else {
                    /* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableTecMail('<?php echo $ID_MAIL_ANAG; ?>')"><?php */
					?><div class="Puls" onclick="EnableTecMail('<?php echo $ID_MAIL_ANAG; ?>')" >N</siv><?php
                }
                ?></td>
                <td><?php
                if ( "$VALID" == "Y" ){
                    ?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableMail('<?php echo $ID_MAIL_ANAG; ?>')"><?php
                } else {
                    /* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableMail('<?php echo $ID_MAIL_ANAG; ?>')"><?php */
					?><div class="Puls" onclick="EnableMail('<?php echo $ID_MAIL_ANAG; ?>')" >N</siv><?php
                }
                ?></td>
                <td><?php
                if ( "$CHIUSURA" == "Y" ){
                    ?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableChiusura('<?php echo $ID_MAIL_ANAG; ?>')"><?php
                } else {
                    /* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableChiusura('<?php echo $ID_MAIL_ANAG; ?>')"><?php */
					?><div class="Puls" onclick="EnableChiusura('<?php echo $ID_MAIL_ANAG; ?>')" >N</siv><?php
                }
                ?></td>
                <td><?php
                if ( "$CCN" == "Y" ){
                    ?><img src="../images/OK.png" width="25px" class="Puls" onclick="DisableCcnMail('<?php echo $ID_MAIL_ANAG; ?>')"><?php
                } else {
                    /* ?><img src="../images/KO.png" width="25px" class="Puls" onclick="EnableChiusura('<?php echo $ID_MAIL_ANAG; ?>')"><?php */
					?><div class="Puls" onclick="EnableCcnMail('<?php echo $ID_MAIL_ANAG; ?>')" >N</siv><?php
                }
                ?></td>
        </tr><?php
    }
      ?><tr style="border-bottom:1px solid black;">
                <td><img src="../images/Aggiungi.png" width="25px" ></td>
                <td><input name="AddUsername" type="text" value="<?php echo $AddUsername; ?>" ></td>
                <td><input name="AddName"     type="text" value="<?php echo $AddName;     ?>" ></td>
                <td><input name="AddMail"     type="text" value="<?php echo $AddMail;     ?>" ></td>
                <td colspan=2 ><input type="submit" name="AddNewMail" value="Add Mail"></td>
        </tr>
    </table>
    </form>
    <SCRIPT>

       function RemoveMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "RemoveMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function DisableTecMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisableTecMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function EnableTecMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnableTecMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function DisableMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisableMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function EnableMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnableMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function DisableChiusura(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisableChiusura")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function EnableChiusura(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnableChiusura")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function DisableCcnMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "DisableCcnMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
       function EnableCcnMail(vIdMail){
            var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "EnableCcnMail")
            .val(vIdMail);
            $('#FormMail').append($(input));
            $('#FormMail').submit();
       }
     </SCRIPT>
    <?php
}
?>
