<div class="wrapper">
    <div class="container-fluid">
        <div>
            <div class="dataTables_wrapper">

                <table id='idTabella' class="display dataTable">
                    <thead class="headerStyle">
                        <tr>
                            <th>ID_CONTR</th>
                            <th>GRUPPO</th>
                            <th>CONTROLLO</th>
                            <th>DESCRIZIONE</th>
                            <th>TIPO</th>
                            <th>VALIDO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody> <?php
                            foreach ($datiControlliAnag as $row) {
                                $update = array();
                                $ID_CONTR = $row['ID_CONTR'];
                                $GRUPPO = $row['GRUPPO'];
                                $CONTROLLO = $row['CONTROLLO'];
                                $DESCRIZIONE = $row['DESCRIZIONE'];
                                $TIPO = $row['TIPO'];
                                $update['VALIDO'] = $row['VALIDO'];


                            ?> <tr>
                                <td> <?php echo $ID_CONTR; ?> </td>
                                <td> <?php echo $GRUPPO; ?> </td>
                                <td> <?php echo $CONTROLLO; ?> </td>
                                <td> <?php echo $DESCRIZIONE; ?> </td>
                                <td> <?php echo $array_tipo[$TIPO]; ?> </td>
                                <?php
                                foreach ($update as $k => $v) {
                                    if ($v == "Y") {
                                        $vimage = '<i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"></i>';
                                    } else {
                                        $vimage = '<i class="fa-solid fa-minus Puls" width="25px" class="closeIconStyle" style="color: grey;" ></i>';
                                    }
                                    echo    '<td>
											<div class="Puls">
												<a href="#" onclick="updateMailAnag(' . $ID_CONTR . ',\'' . $k . '\',\'' . ($v == 'Y' ? 'N' : 'Y') . '\')">' . $vimage . '</a>												
											</div>
										</td>';
                                }  ?>
                                <td>
                                    <i onclick="deleteMailAnag(<?php echo $ID_CONTR; ?>,'<?php echo $USERNAME; ?>')" class="fa-solid fa-trash-can trashIconStyle" width="35px" style="color: black;"></i>

                                </td>
                            </tr> <?php }  ?>

                    </tbody>
                </table>


                <script src="./view/emailanag/JS/index.js?p=<?php echo rand(1000, 9999); ?>"></script>
                <script src="./JS/datatable.config.js?p=<?php echo rand(1000, 9999); ?>"></script>