<div class="wrapper">
    <div class="container-fluid">
        <div>
            <div class="dataTables_wrapper">

                <form id="FormListaLanci" method="POST">
                    <?php $SOTTO_GRUPPO = $datiControlliLanci[0]['SOTTO_GRUPPO'];
                    $CONTROLLO = $datiControlliLanci[0]['CONTROLLO'];
                    $DESCRIZIONE = $datiControlliLanci[0]['DESCR'];
                    $TESTO = $datiControlliLanci[0]['TESTO'];
                    $TIPO = $datiControlliLanci[0]['TIPO'];
                    $CLASSE = controlli_dati::$array_classe[$datiControlliLanci[0]['CLASSE']];
                    ?>
                    <div>

                        <table id='DatiControlloLanci' class="display dataTable">
                            <thead class="headerStyle">
                                <tr>
                                    <th>SOTTO GRUPPO</th>
                                    <th>CONTROLLO</th>
                                    <th>TIPO</th>
                                    <th>DESCRIZIONE</th>                             
                                    <th>CLASSE</th>


                                </tr>
                            </thead>
                            <tbody>

                                <td> <?php echo $SOTTO_GRUPPO; ?> </td>
                                <td> <?php echo $CONTROLLO; ?> </td>
                                <td> <?php echo $TIPO; ?> </td>
                                <td> <?php echo $DESCRIZIONE; ?> </td>                           
                                <td> <?php echo $CLASSE; ?> </td>

                                </tr>

                            </tbody>
                    </div>
                    <br>
                    <table id='idTabella2' class="display dataTable">
                    <caption>Storico Lanci</caption>
                        <thead class="headerStyle">
                            <tr>
                                <th>ID_LANCIO</th>
                                <th>LANCIO</th>
                                <th>INIZIO</th>
                                <th>DURATA</th>
                                <th>ESITO</th>
                                <th>RISULTATO</th>
                                <th>NOTE</th>
                                <th>FILTRI</th>
                                <th>QUERY</th>

                            </tr>
                        </thead>
                        <tbody> <?php
                                foreach ($datiControlliLanci as $row) {
                                    $update = array();
                                    $ID_CONTR = $row['ID'];
                                    $ID_LANCIO = $row['ID_LANCIO'];
                                    $LANCIO = $row['DATI_LANCIO']['DESCR'];
                                    $QUERY = $row['LANCIO'];
                                    $FILTRI = $row['DATI_LANCIO']['FILTRI'];
                                    $RISULTATO = $row['RISULTATO'];
                                    $NOTE = $row['NOTE'];
                                    $row['ESITO'] = trim($row['ESITO']);
                                    $ESITO = $array_esito[$row['ESITO']];
                                    $INIZIO_LANCIO = substr($row['INIZIO_LANCIO'], 0, -7);
                                    $FINE_LANCIO = substr($row['FINE_LANCIO'], 0, -7);
                                    $DURATA = controlli_dati::getDurata($INIZIO_LANCIO, $FINE_LANCIO);
                                    $ESITOCLASS = controlli_dati::$array_class_esito[$row['ESITO']];
                                    $ESITOCLASS = ($ESITOCLASS) ? $ESITOCLASS : "NOEsito";
                                    $ESITO = ($row['ESITO'] == "OK") ? "<strong>$ESITO </strong>" : $ESITO;

                                ?> <tr class="<?php echo $ESITOCLASS; ?>">
                                    <td> <a href="#" onclick="visualizzaLancio(<?php echo $ID_LANCIO; ?>)"> <?php echo $ID_LANCIO; ?></a> </td>
                                    <td> <?php echo $LANCIO; ?> </td>
                                    <td> <?php echo $INIZIO_LANCIO; ?> </td>
                                    <td> <?php echo $DURATA; ?> </td>
                                    <td> <?php echo $ESITO; ?> </td>
                                    <td> <?php echo $RISULTATO; ?> </td>
                                    <td> <?php echo $NOTE; ?> </td>
                                    <td> <?php echo $FILTRI; ?> </td>
                                    <td> <?php echo $QUERY; ?> </td>
                                </tr> <?php }  ?>

                        </tbody>
                    </table>
            </div>
        </div>
    </div>