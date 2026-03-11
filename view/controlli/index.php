<?php
$DATI_LANCIO = ($selectLancio) ? $datiControlliAnag[0]['DATI_LANCIO'] : '';
?>
<div class="wrapper">
    <div class="container-fluid">
        <div>
            <div class="dataTables_wrapper">

                <form id="FormFiltriControllo" method="POST">
                    <input type="hidden" id="TopScrollO" name="TopScrollO" value="<?php echo $TopScrollO; ?>" />
                    <input type="hidden" id="mailPage" name="mailPage" value="<?php echo $mailPage; ?>" />
                    <input type="hidden" id="mailSearch" name="mailSearch" value="<?php echo $mailSearch; ?>" />
                    <input type="hidden" id="mailOrdern" name="mailOrdern" value="<?php echo $mailOrdern; ?>" />
                    <input type="hidden" id="mailOrdert" name="mailOrdert" value="<?php echo $mailOrdert; ?>" />
                    <input type="hidden" id="pageTable" name="pageTable" value="" />
                    <input type="hidden" id="mailLength" name="mailLength" value="<?php echo $mailLength; ?>" />
                    <input type="hidden" id="FLG_REFRESH" name="FLG_REFRESH" value="<?php echo $FLG_REFRESH; ?>" />

                    <aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
                        <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>
                        <strong >Tool | Controlli |</strong> <strong id="TitoloControlli"></strong> <strong id="TitoloLancio"></strong><strong id="TitoloLoadLancio"></strong>
                        
                    
                    </p>
                        <div class="asideContent">

                            <select title="SELEZIONA GRUPPO" class="selectpicker" data-live-search="true" onchange="filtraControllo(1);return false;" id="selectGruppo" name="selectGruppo" class="FieldMand selectSearch" style="width:180px;height:30px;">

                                <?php
                                foreach ($datiGruppi as $row) {
                                    $ID = $row['ID_GRUPPO'];
                                    $VAL = $row['DESCR'];
                                    $selected = "";
                                    if ($selectGruppo == $ID) {
                                        $selected = "selected";
                                        $GRUPPO = $row['DESCR'];
                                    };
                                ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"> <?php echo $VAL; ?></option>
                                <?php
                                }
                                ?>
                            </select>

                            <?php if ($selectGruppo) { ?>

                                <select data-done-button="true" title="SELEZIONA SOTTO GRUPPO" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="selectSottoGruppo" name="selectSottoGruppo" class="FieldMand selectSearch" style="width:180px;height:30px;">


                                    <?php
                                    foreach ($datiSottoGruppi as $row) {
                                        $ID = $row['SOTTO_GRUPPO'];
                                        $VAL = $row['SOTTO_GRUPPO'];

                                        $selected = in_array($ID, $selectSottoGruppo) ? "selected" : "";

                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>

                                <select data-done-button="true" title="SELEZIONA FILE" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="selectFile" name="selectFile" class="FieldMand selectSearch" style="width:180px;height:30px;">

                                    <?php
                                    foreach ($datiListaFile as $row) {
                                        $ID = $row['ID_FILE'];
                                        $VAL = $row['NAME_FILE'];
                                        // $selected = ($selectFile == $ID) ? "selected" : "";
                                        $selected = in_array($ID, $selectFile) ? "selected" : "";
                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <!--     <select class="selectpicker" data-live-search="true" onchange="filtraControllo(1);return false;" id="selectInport" name="selectInport" class="FieldMand selectSearch" style="width:180px;height:30px;">
                                    <option value=""> SELEZIONA INPORT</option>
                                    <?php

                                    foreach ($datiInport as $row) {
                                        $ID = $row['ID_INPORT'];
                                        $VAL = $row['INPORT'] . "_" . $row['ID_INPORT'] . "_" . $row['DATA_INPORT'];
                                        $selected = "";
                                        if ($selectInport == $ID) {
                                            $selected = "selected";
                                            $GRUPPO = $row['DESCR'];
                                        };
                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"> <?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>-->
                            <?php } ?>


                            


                            <?php if ($selectGruppo) { ?>
                                <button style="float:right" onclick="UploadControlli('<?php echo $selectGruppo; ?>');return false;" id="uploadFile" class="btn" style="display: inline-block;"><i class="fa-solid fa-file-import"></i> Import Controlli</button>

                            <?php } ?>

                            <?php if ($selectGruppo && !$selectLancio) { ?>
                                <button style="float:right" onclick="downloadExport('STORICO');return false;" id="downloadFile" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download Storico</button>
                                <button style="float:right" onclick="downloadExport('CONTROLLI');return false;" id="downloadFile" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download Controlli</button>
                            <?php } ?>

                            <?php if ($selectLancio) { ?>
                                <button style="float:right" onclick="downloadExport('LANCIO');return false;" id="downloadFile" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download <?php echo $fileName; ?></button>
                            <?php } ?>




                        </div>
                        <div class="asideContent">
                            <?php if ($selectGruppo) { ?>

                                <select data-done-button="true" title="SELEZIONA TIPO" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="selectIdTipo" name="selectIdTipo" class="FieldMand selectSearch" style="width:180px;height:30px;">

                                    <?php
                                    foreach ($datiTipo as $v) {
                                        $ID = $v['ID_TIPO'];
                                        $VAL = $v['TIPO'];
                                        //     $selected = ($selectIdTipo == $ID) ? "selected" : "";
                                        $selected = in_array($ID, $selectIdTipo) ? "selected" : "";
                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>


                                <select data-done-button="true" title="SELEZIONA CLASSE" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="selectClasse" name="selectClasse" class="FieldMand selectSearch" style="width:180px;height:30px;">


                                    <?php
                                    foreach ($array_classe as $k => $v) {
                                        $ID = $k;
                                        $VAL = $v;
                                        //  $selected = ($selectClasse == $ID) ? "selected" : "";
                                        $selected = in_array($ID, $selectClasse) ? "selected" : "";

                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>


                                <select data-done-button="true" title="SELEZIONA ESITO" multiple class="selectpicker" data-live-search="true" data-icon-base="fas" data-tick-icon="fa-solid fa-check" id="selectEsito" name="selectEsito" class="FieldMand selectSearch" style="width:180px;height:30px;">


                                    <?php
                                    foreach ($array_esito as $k => $v) {
                                        $ID = $k;
                                        $VAL = $v;
                                        // $selected = ($selectEsito == $ID) ? "selected" : "";
                                        $selected = in_array($ID, $selectEsito) ? "selected" : "";
                                    ?><option <?php echo $selected; ?> value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php } ?>

                            <?php if ($selectGruppo) { ?>
                                <button style="float:right" id="filtra" onclick="storicoDownload();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-rectangle-list"></i> Storico Download</button>
                                <button style="float:right" id="refresh" onclick="filtraControllo();return false;" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
                                <button style="float:right" onclick="VisualizzaControlli();return false;" id="visualizzaControlli" class="btn" style="display: inline-block;"><i class="fa-solid  fa-filter-circle-xmark"></i> Elimina Filtri</button>
                            <?php } ?>
                        </div>
                        <div class="asideContent">
                        <?php if ($selectGruppo) { ?>
                                <select title="SELEZIONA LANCIO" class="selectpicker" data-live-search="true" onchange="filtraControlloLancio();return false;" id="selectLancio" name="selectLancio" class="FieldMand selectSearch" style="width:480px;height:30px;">


                                    <?php
                                    foreach ($datiLancioGruppo as $row) {
                                        $ID = $row['ID_LANCIO'];
                                        $VAL = $row['DESCR'] . "_" . $row['DATA_LANCIO'];
                                        $selected = ($selectLancio == $ID) ? "selected" : "";

                                        switch ($row['STATO']) {
                                            case 1:
                                                $color = "red";
                                                break;
                                            case 2:
                                                $color = "orange";
                                                break;
                                            case 0:
                                                $color = "green";
                                                break;
                                        }

                                    ?><option <?php echo $selected; ?> data-content='<i style="color:<?php echo $color; ?>" class="fa-regular fa-circle"></i> <?php echo $VAL; ?> ' value="<?php echo $ID; ?>"><?php echo $VAL; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php } ?>
                            </div>
                    </aside>
                    <?php

                    $caption = " ";
                    $captionLancio = "FILE: <strong>" . $DATI_LANCIO['NAME_FILE'] . "</strong>";
                    //$caption = ($selectGruppo) ? "<strong>".$GRUPPO."</strong>" : "";
                    $caption = ($DATI_LANCIO['NAME_FILE']) ? $captionLancio : $caption;
                    $bgaction = '';
                    if ($selectLancio) {
                        $bgaction = 'bgcLancio';
                    }
                    ?>

                    <table id='idTabella' class="display dataTable">
                        <caption>
                            <div class="actionButton">
                                <?php if ($selectLancio) { ?>
                                    <button id="crearilascio" onclick="ModificaEsito();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-pen-to-square"></i> Modifica Esito</button>
                                <?php } ?>    
                                <?php if ($selectGruppo && !$selectLancio) { ?>
                                    <button id="modTipo" onclick="modtipo();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-pen-to-square"> </i> Modifica Tipo</button>
                                <?php } ?>                            
                                <?php if ($selectGruppo && !$selectLancio) { ?>
                                    <button id="modValido" onclick="modificavalidita();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-pen-to-square"> </i> Modifica Validità</button>
                                <?php } ?>
                                <?php if ($selectGruppo && !$selectLancio) { ?>
                                    <button id="addControllo" onclick="AddControllo();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-plus-circle"> </i> Add Controllo</button>
                                <?php } ?>

                                <?php if ($selectGruppo) { ?>
                                    <button id="crearilascio" onclick="CreaLancio();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-rocket"> </i> Crea Lancio</button>
                                <?php } ?>
                               
                            </div>

                        </caption>
                        <thead class="headerStyle">
                            <tr class="trControlli">
                                <th>ID</th>
                                <th>SOTTO<br>GRUPPO</th>
                                <th>CONTROLLO<br>TIPO<br>DESCRIZIONE</th>
                                <th>CLASSE</th>
                                <th>UTENTE<br>MODIFICA</th>
                                <th>VALIDO</th>
                                <th class="divisoreLancio bgcLancio">ID<br>LANCIO</th>
                                <th class="bgcLancio">INIZIO_LANCIO</th>
                                <th class="bgcLancio">DURATA</th>
                                <th class="bgcLancio">ESITO</th>
                                <th class="bgcLancio">RISULTATO</th>
                                <th class="bgcLancio">NOTE</th>
                                <th class="bgcLancio">UTENTE<br>MODIFICA</th>
                                <th class="<?php echo $bgaction; ?>"></th>
                                <th class="<?php echo $bgaction; ?>"></th>
                                <th class="<?php echo $bgaction; ?>">
                                    <?php if (count($datiControlliAnag)) {  ?>
                                        <i id="selectAllContr" onclick="selectAllContr()" class="fa-regular fa-square-check" style="font-size: 2em;"></i>

                                    <?php
                                    }
                                    ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody> <?php
                                foreach ($datiControlliAnag as $row) {
                                    $update = array();
                                    $ID_CONTR = $row['ID'];
                                    $ID_LANCIO = $row['ID_LANCIO'];
                                    $ORDINAMENTO = $row['ORDINAMENTO'];
                                    // $GRUPPO = $gruppo;
                                    $SOTTO_GRUPPO = $row['SOTTO_GRUPPO_ANAG'];
                                    $CONTROLLO = $row['CONTROLLO'];
                                    $TIPO = $row['TIPO'];
                                    $DESCRIZIONE = $row['DESCR_ANAG'];
                                    $TESTO = $row['TESTO'];
                                    $CLASSE = $row['CLASSE_ANAG'];
                                    $UTENTE_MODIFICA = $row['UTENTE_MODIFICA'];
                                    $LANCIO_UTENTE = $row['LANCIO_UTENTE'];
                                    $TMS_MODIFICA = $row['TMS_MODIFICA'];
                                    $date = strtotime($row['TMS_MODIFICA']);
                                    $DATA_MODIFICA = date('d/m/Y h:i:s', $date);
                                    if ($row['MODIFICA_LANCIO']) {
                                        $MODIFICA_LANCIO = strtotime($row['MODIFICA_LANCIO']);
                                        $DATA_MODIFICA_LANCIO = date('d/m/Y h:i:s', $MODIFICA_LANCIO);
                                    }else{
                                        $DATA_MODIFICA_LANCIO = ""; 
                                    }
                                    $update['VALIDO'] = $row['VALIDO'];
                                    $VALIDO = $row['VALIDO'];
                                    $RISULTATO = $row['RISULTATO'];
                                    $NOTE = $row['NOTE'];
                                    $row['ESITO'] = trim($row['ESITO']);
                                    $ESITO = $array_esito[$row['ESITO']];
                                    $INIZIO_LANCIO = substr($row['INIZIO_LANCIO'], 0, -7);
                                    $FINE_LANCIO = substr($row['FINE_LANCIO'], 0, -7);
                                    $DURATA = controlli_dati::getDurata($INIZIO_LANCIO, $FINE_LANCIO);
                                    if ($INIZIO_LANCIO) {
                                        $date = new DateTime($INIZIO_LANCIO);
                                        $INIZIO_LANCIO = $date->format('Y-m-d');
                                        $INIZIO_LANCIO .= "<br/> " . $date->format('H:i:s');
                                    }
                                    $ESITOCLASS = controlli_dati::$array_class_esito[$row['ESITO']];
                                    $ESITOCLASS = ($ESITOCLASS) ? $ESITOCLASS : "NOEsito";
                                    $ESITO = ($row['ESITO'] == "OK") ? "<strong>$ESITO </strong>" : $ESITO;
                                    $LANCIO = $row['DATI_LANCIO']['DESCR'];
                                    $FILTRI = $row['DATI_LANCIO']['FILTRI'];
                                    $FILTRI = str_replace(['<br>', '<br/>'], ['&#13;&#10;', '&#13;&#10;'], $FILTRI);
                                    $FILTRI =  $LANCIO . "&#13;&#10;" . $FILTRI;
                                ?> <tr class="<?php echo $ESITOCLASS; ?>">
                                    <td> <?php echo $ID_CONTR; ?> </td>
                                    <td> <?php echo $SOTTO_GRUPPO; ?> </td>
                                    <td> <strong><?php echo $CONTROLLO; ?></strong><br> <?php echo $TIPO; ?><br> <?php echo $DESCRIZIONE; ?> </td>
                                    <td class="titlehover" title="<?php echo $TESTO; ?>"> <?php echo $array_classe[$CLASSE]; ?> </td>
                                    <td> <?php echo $UTENTE_MODIFICA; ?><br><?php echo $DATA_MODIFICA; ?> </td>
                                    <?php
                                    foreach ($update as $k => $v) {
                                        if ($v == "1") {
                                            $vimage = '<i class="fa-solid fa-check Puls" class="checkIconStyle" style="color: green;"></i>';
                                        } else {
                                            $vimage = '<i class="fa-solid fa-minus Puls" width="25px" class="closeIconStyle" style="color: grey;" ></i>';
                                        }
                                        $FLG_VALIDITA = ($v == 1) ? 0 : 1; ?>
                                        <td>
                                            <?php if ($selectGruppo && !$selectLancio) { ?>
                                                <div class="Puls">
                                                    <a href="#" onclick="updateValiditaControllo(<?php echo $ID_CONTR; ?>,'<?php echo $FLG_VALIDITA; ?>')"><?php echo $vimage; ?></a>
                                                </div>
                                            <?php   }  ?>
                                        </td>
                                    <?php   }  ?>

                                    <td class="divisoreLancio" title="<?php echo $LANCIO; ?>"> <a href="#" onclick="visualizzaLancio(<?php echo $ID_LANCIO; ?>)"> <?php echo $ID_LANCIO; ?></a> </td>
                                    <td> <strong><?php echo $INIZIO_LANCIO; ?></strong></td>
                                    <td> <?php echo $DURATA; ?> </td>
                                    <td> <?php echo $ESITO; ?> </td>
                                    <td class="cpointer" title="<?php echo $FILTRI; ?>"> <?php echo $RISULTATO; ?> </td>
                                    <td> <?php echo $NOTE; ?> </td>
                                    <td> <?php echo $LANCIO_UTENTE; ?><br>
                                        <?php echo  $DATA_MODIFICA_LANCIO; ?>
                                    </td>


                                    <td>
                                        <?php if ($selectGruppo && !$selectLancio) { ?>
                                            <i onclick="modificaControllo(<?php echo $ID_CONTR; ?>)" class="fa-solid fa-pen-to-square" width="35px" style="color: black;"></i>
                                        <?php } ?>

                                        <?php if ($selectLancio) { ?>
                                            <i onclick="modificaLancio(<?php echo $ID_CONTR; ?>)" class="fa-solid fa-pen-to-square" width="35px" style="color: black;"></i>
                                        <?php }  ?>
                                    </td>
                                    <td>
                                        <?php if ($selectGruppo) { ?>
                                            <i onclick="listaLanci(<?php echo $ID_CONTR; ?>)" class="fa-solid fa-list" width="35px" style="color: black;"></i>
                                        <?php }  ?>
                                    </td>


                                    <td>
                                        <input class='checkbox_selector' type="checkbox" name="selectIdContr" value="<?php echo $ID_CONTR; ?>" />
                                    </td>


                                </tr> <?php }  ?>

                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    <script src="./view/controlli/JS/index.js?p=<?php echo rand(1000, 9999); ?>"></script>
    <script src="./JS/datatable.config.js?p=<?php echo rand(1000, 9999); ?>"></script>