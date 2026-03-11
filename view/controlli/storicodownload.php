<div class="wrapper">
    <div class="container-fluid">
 
            <div class="dataTables_wrapper">

             
                    <table id='storicoDownload' class="display dataTable">
                    <caption>Storico Export</caption>
                        <thead class="headerStyle">
                            <tr>
                                <th>ID_EXPORT</th>
                                <th>DATA</th>
                                <th>FILE</th>
                                <th>DOWNLOAD</th>
                               

                            </tr>
                        </thead>
                        <tbody> <?php
                                foreach ($datiStorico as $row) {                                   
                                    $ID_EXPORT = $row['ID_EXPORT'];
                                    $NAME_FILE = $row['NAME_FILE'];
                                   
                                    $DATA_FILE = substr($row['TMS_INSERT'], 0, -7);
                                   

                                ?> <tr class="<?php echo $ESITOCLASS; ?>">
                                 <td> <?php echo $ID_EXPORT; ?> </td>
                                 <td> <?php echo $DATA_FILE; ?> </td>
                                 <td> <?php echo $NAME_FILE; ?> </td>
                                    <td> <i onclick="window.open('<?php echo $NAME_FILE; ?>')" class="fa-solid fa-download" width="35px" style="color: black;"></i>
                                   
                                   
                                </tr> <?php }  ?>

                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    <script>

$('#storicoDownload').DataTable({
  //columns: [{ width: '5%' }, { width: '5%' }, { width: '10%' }, { width: '5%' }, { width: '20%' },{ width: '5%' },null],
  language: {
    "url": "./JSON/italian.json"
  },
  "lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50, 100,'All']]
  // responsive: true

});
    </script>