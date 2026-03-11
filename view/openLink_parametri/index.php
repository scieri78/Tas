<table id="tabSel" class="openLinkTable">
    <?php foreach ($DataParametri as $row): ?>
        <tr>
            <td>
                <?php
                echo $row['LABEL'];
                echo $row['PRECISION'] ? " (Numero Decimali: " . $row['PRECISION'] . ")" : "";
                echo $row['LENGTH'] ? " (Numero Massimo di caratteri: " . $row['LENGTH'] . ")" : "";
                $nameId = str_replace(" ", "_", htmlspecialchars($row['NOME']));
                ?>
                <input type="hidden" id="TIPO_<?= $nameId ?>" name="TIPO_<?= $nameId ?>" value="<?= $row['TIPO_INPUT'] ?>">
            </td>
            <td>
                <?php
                $optionVal = $row['OPTIONS'][0]['DEFAULT'];
                $valdef = $optionVal ? $optionVal : $row['DEFAULT'];
                $value = isset($ResParametri[$nameId]) ? $ResParametri[$nameId] : $valdef;

                if ($legameValido || $RdOnly==1) {
                    // Mostra solo il contenuto
                    switch ($row['TIPO_INPUT']) {
                        case 'select':
                            $selectedOption = '';
                            foreach ($row['OPTIONS'] as $option) {
                                if ($ResParametri[$nameId] == $option['VALUE'] ) {
                                    $selectedOption = $option['DESCR'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($selectedOption);
                            break;

                        case 'text':
                            echo htmlspecialchars($value);
                            break;
                        case 'number':
                            // Supponendo che il valore sia un numero o una stringa numerica
                            $formattedNumber = number_format((float)$value, $row['PRECISION'] ?: 0, ',', '.');
                            echo htmlspecialchars($formattedNumber);
                            break;
                        case 'datetime':
                             case 'datetime':
                            $dateTime = new DateTime($value);
                            echo htmlspecialchars($dateTime->format('d/m/Y H:i'));
                            break;
                          

                        case 'radio':
                            foreach ($row['OPTIONS'] as $option) {
                                if ($ResParametri[$nameId] == $option['VALUE'] || trim($option['DESCR']) == trim($row['DEFAULT'])) {
                                    echo htmlspecialchars($option['DESCR']);
                                    break;
                                }
                            }
                            break;

                        case 'checkbox':
                            $checkedOptions = [];
                            foreach ($row['OPTIONS'] as $option) {
                                foreach ($ResParametri as $key => $value) {
                                    if (strpos($key, $nameId . '_') !== false && $value == $option['VALUE']) {
                                        $checkedOptions[] = $option['DESCR'];
                                    }
                                }
                            }
                            echo htmlspecialchars(implode(', ', $checkedOptions));
                            break;

                        case 'textarea':
                            echo nl2br(htmlspecialchars($value));
                            break;
                    }
                } else {
                    // Mostra gli elementi di input normali
                    switch ($row['TIPO_INPUT']) {
                        case 'select':
                            ?>
                            <select id="<?= $nameId ?>" name="PAR_<?= $nameId ?>">
                                <option value="">..</option>
                                <?php foreach ($row['OPTIONS'] as $option): ?>
                                    <?php
                                    $sel = ($ResParametri[$nameId] == $option['VALUE']) ? 'selected' : ((trim($option['DESCR']) == trim($row['DEFAULT'])) ? 'selected' : "");
                                    ?>
                                    <option value="<?= $option['VALUE'] ?>" <?= $sel ?>><?= $option['DESCR'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php
                            break;

                        case 'text':
                            ?>
                            <input type="text" id="PAR_<?= $row['ID_PAR'] ?>" name="PAR_<?= $nameId ?>"
                                value="<?= htmlspecialchars($value) ?>"
                                oninput="validaMaxLength(this, <?= $row['LENGTH'] ?: 0 ?>)" />
                            <?php
                            break;

                        case 'number':
                            $precision = $row['PRECISION'] ?: 0;
                            $step = $precision > 0 ? '0.' . str_repeat('0', $precision - 1) . '1' : '1';
                            ?>
                            <input type="number" id="PAR_<?= $row['ID_PAR'] ?>" name="PAR_<?= $nameId ?>"
                                value="<?= texttonumber($value) ?>"
                                step="<?= $step ?>"
                                oninput="validateDecimalPrecision('PAR_<?= $row['ID_PAR'] ?>', <?= $precision ?>)" />
                            <?php
                            break;

                        case 'datetime':
                            ?>
                            <input type="datetime-local" id="PAR_<?= $row['ID_PAR'] ?>" name="PAR_<?= $nameId ?>"
                                value="<?= $value ?>"
                                onchange="updateFormattedDisplay('PAR_<?= $row['ID_PAR'] ?>')" />
                            <div id="formattedDisplay_<?= $row['ID_PAR'] ?>"></div>
                            <?php
                            break;

                        case 'radio':
                            foreach ($row['OPTIONS'] as $option):
                                $ck = ($ResParametri[$nameId] == $option['VALUE']) ? 'checked' : ((trim(strtoupper($option['DESCR'])) == trim(strtoupper($row['DEFAULT']))) ? 'checked' : "");
                                ?>
                                <input type="radio" id="PAR_<?= $option['DESCR'] ?>" name="PAR_<?= $nameId ?>"
                                    value="<?= $option['VALUE'] ?>" <?= $ck ?>>
                                <label for="<?= $option['DESCR'] ?>"><?= $option['DESCR'] ?></label><br>
                            <?php endforeach;
                            break;

                        case 'checkbox':
                            foreach ($row['OPTIONS'] as $k => $option):
                                $isChecked = false;
                                $defval = true;
                                foreach ($ResParametri as $key => $value) {
                                    if (strpos($key, $nameId . '_') !== false) {
                                        $defval = false;
                                        if ($value == $option['VALUE']) {
                                            $isChecked = true;
                                            break;
                                        }
                                    }
                                }

                                if ($defval && !$isChecked && trim(strtoupper($option['DESCR'])) == trim(strtoupper($row['DEFAULT']))) {
                                    $isChecked = true;
                                }

                                $ck = $isChecked ? 'checked' : '';
                                ?>
                                <input type="checkbox" id="<?= $nameId ?>_<?= $k ?>" name="PAR_<?= $nameId ?>[]"
                                    value="<?= $option['VALUE'] ?>" <?= $ck ?>>
                                <label for="<?= $option['DESCR'] ?>"><?= $option['DESCR'] ?></label>
                            <?php endforeach;
                            break;

                        case 'textarea':
                            ?>
                            <textarea id="<?= $nameId ?>" name="PAR_<?= $nameId ?>" 
                            rows="4" cols="50"><?= htmlspecialchars($value) ?></textarea>
                            <?php
                            break;
                    }
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if (!$legameValido && $RdOnly!=1): ?>
    <button onclick="saveParametri();return false;" id="saveArgo" class="btn openLinkBtn"><i class="fa-solid fa-save"> </i> Salva</button>
<?php endif; ?>

<?php
function texttonumber($num)
{
    return str_replace(",", ".", $num);
}
?>
