<div class="parametri-container">
    <table id="idTabedlla" class="display dataTable">
        <thead class="headerStyle">
            <tr>
                <th>ID_PAR</th>
                <th>LABEL</th>
                <th>NOME</th>
                <th>VALUE</th>
                <th>DESC</th>
                <th>TIPO_INPUT</th>
                <th>TYPE</th>
                <th>LENGTH</th>
                <th>PRECISION</th>
                <th>SELECT</th>
                <th>DEFAULT</th>            
                <th>ORD</th>
                <th>TMS_INSERT</th>
                <th>MODIFICA</th>
                <th>ELIMINA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Supponiamo che $parametri sia un array di dati passato dal controller
            $parametri = [
                [
                    'ID_PAR' => 2,
                    'LABEL' => 'Risk Rule',
                    'NOME' => 'RiskRule',
                    'VALUE' => '',
                    'DESC' => '',
                    'TIPO_INPUT' => 'select',
                    'TYPE' => '',
                    'LENGTH' => '',
                    'PRECISION' => '',
                    'SELECT' => "SELECT ID_RULE VALUE, NAME_RULE , DESC_RULE FROM IFRS17.MAP_RULE WHERE TIPO = 'RiskAdjustament' ORDER BY TIME_RULE DESC",
                    'DEFAULT' => 'RA',
                    'ID_PAR_GRUPPO' => 2,
                    'ORD' => 3,
                    'TMS_INSERT' => '2025-11-06 10:35:22.849594'
                ],
                // Aggiungi altri parametri qui
            ];

            foreach ($Datiparametri as $parametro) {
                echo "<tr>";
                echo "<td>{$parametro['ID_PAR']}</td>";
                echo "<td>{$parametro['LABEL']}</td>";
                echo "<td>{$parametro['NOME']}</td>";
                echo "<td>{$parametro['VALUE']}</td>";
                echo "<td>{$parametro['DESC']}</td>";
                echo "<td>{$parametro['TIPO_INPUT']}</td>";
                echo "<td>{$parametro['TYPE']}</td>";
                echo "<td>{$parametro['LENGTH']}</td>";
                echo "<td>{$parametro['PRECISION']}</td>";
                echo $parametro['SELECT']?"<td><span title='{$parametro['SELECT']}'>Db query<span></td>":"<td></td>";
                echo "<td>{$parametro['DEFAULT']}</td>";               
                echo "<td>{$parametro['ORD']}</td>";
                echo "<td>{$parametro['TMS_INSERT']}</td>";
            echo   "<td><div id='ModParametro{$parametro['ID_PAR']}' class='Plst' onclick='showFormParametri({$parametro['ID_PAR']})'>
									<i class='fa-solid fa-pen-to-square'></i>
								</div></td>";
            echo   "<td><div id='RemoveParametro{$parametro['ID_PAR']}' class='Plst' onclick='removeParametri({$parametro['ID_PAR']})'>
									<i class='fa-solid fa-trash-can'></i>
								</div></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#idTabedlla').DataTable();
});
</script>