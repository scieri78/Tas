<?php
$tabella = "";
if (count($datiArgo) > 0) {
    $tabella .= '<table id="idTabella" class="display dataTable">
               
            <thead class="headerStyle">
                <tr>
                  <th>' . implode('</th><th>', array_keys(current($datiArgo))) . '</th>
                  <th> </th>
                </tr>
            </thead>
            <tbody>';
    foreach ($datiArgo as $row) {
        array_map('htmlentities', $row);
        $tabella .= "<tr>
      <td>" . implode('</td><td>', $row) . '</td>
      <td>';
        if (!$legameValido && count($datiArgo) > 0 && $RdOnly!=1) {
            $tabella .= '<div id="ModDett935" class="Plst" onclick="formUpdateArgo(\'' . $row['ID_PROCESS'] . '\',\'' . $row['COMPAGNIA'] . '\')">
									<i class="fa-solid fa-pen-to-square"></i>
								</div>';
        }
        $tabella .= '</td>
    </tr>';
    }
    $tabella .= '  </tbody>
    </table>';
} else {
    $tabella = "<h4>Nessun dato in tabella</h4>";
}
echo $tabella;
?>

<br>
<center>
    <?php
    if (!$legameValido && count($datiArgo) > 0 && $RdOnly!=1) {
    ?>

        <div onclick="ValidaLegame()" class="btn">
            <i class="fa-solid fa-circle-check"></i> Valida
        </div>

</center> <?php  } ?>