<aside aria-label="Uploaded icons have their own unique 'fak' prefix" class="fileter-aside starlight-aside starlight-aside--note">
    <p class="starlight-aside__title" aria-hidden="true"> <i class="fa fa-regular fa-star-shooting fa-rotate-180"></i>Tickets Tas</p>
    <div class="asideContent">
        <button onclick="refreshPage();return false;" id="refresh" class="btn"><i class="fa-solid fa-refresh"> </i> Refresh</button>
        <button id="addworkflow" onclick="formTicket();return false;" class="btn" style="display: block;"><i class="fa-solid fa-plus-circle"> </i> Tickets </button>
        <?php if ($is_admin): ?>
            <button id="addworkflow" onclick="ttassegnato(<?php echo $_SESSION['TTASSEGATO'] ? 0 : 1; ?>);return false;" class="btn" style="display: block;">
                <?php if ($_SESSION['TTASSEGATO'] == 1): ?>
                    <i class="fa-solid fa-eye"> </i> Tutti
                <?php else: ?>
                    <i class="fa-solid fa-eye-slash"> </i> Assegnati
                <?php endif; ?>
            </button>

            <select id="userTicket" name="userTicket" class="selectSearch " onchange="ttuser(this.value);">
                <option selected value=""> -- Creato da -- </option>
                <?php
                foreach ($utentiTT as $u) {
                    $sel = ($_SESSION['TTUSER'] == $u['USERNAME']) ? 'selected' : '';
                    if ($u['USERNAME'] != '') {
                        echo "<option " . $sel . " value='" . $u['USERNAME'] . "'>" . $u['NOMINATIVO'] . "</option>";
                    }
                }
                ?>
            </select> -
            <select id="ttuserAssegnato" name="ttuserAssegnato" class="selectSearch" onchange="ttuserAssegnato(this.value);">
                <option selected value=""> -- Assegnato a -- </option>
                <?php
                foreach ($assegnatoList as $u) {
                    if ($u['ASSEGNATO']) {
                        $sel2 = ($_SESSION['TTUSERASSEGNATO'] == $u['ASSEGNATO']) ? 'selected' : '';
                        echo "<option " . $sel2 . " value='" . $u['ASSEGNATO'] . "'>" . $u['NOMINATIVO'] . "</option>";
                    }
                }
                ?>
            </select>

            <select id="ttposizone" name="ttposizone" class="selectSearch" onchange="ttposizione(this.value);">
                <option selected value=""> -- Posizione -- </option>
                <?php
                foreach ($datiPosizione as $u) {
                    if ($u) {
                        $sel2 = ($_SESSION['TTPOSIZIONE'] == $u) ? 'selected' : '';
                        echo "<option " . $sel2 . " value='" . $u . "'>" . $u . "</option>";
                    }
                }
           
                 $sel2 = ($_SESSION['TTPOSIZIONE'] == 'altro') ? 'selected' : '';
                echo "<option " . $sel2 . " value='altro'>Altro</option>";
                ?>
            </select>
        <?php endif; ?>
    </div>
</aside>
<br />
<div class="kanban-board">
    <?php

    $stati = ['Da fare poi', 'Aperto',  'In Lavorazione', 'Richiesta Info', 'Test', 'Risolto'];
    $conRisolto = 0;
    foreach ($stati as $stato) {
        if ($stato == 'Da fare poi' && !$is_admin) {
            $nascondi = 'hidden';
        } else {
            $nascondi = '';
        }

        $tipo ='';

        $classStato = strtolower(str_replace(" ", "_", $stato));

        echo "<div " . $nascondi . " class='kanban-column $classStato' data-stato='$stato'><h3>$stato</h3>";
        foreach ($tickets as $t) {
            $classValida = $t['VALIDA'] ? "valido" : "";
            $t['conRisolto'] = ($stato == 'Risolto' && $t['STATO'] == 'Risolto') ? ++$conRisolto : 0;
            /*echo "<pre>";
        print_r($t);
        echo "</pre>";*/
            if ($t['STATO'] == $stato) {
                $t['TITOLO'] = utf8_encode($t['TITOLO']);
                $date = date_create($t['DATA_CREAZIONE']);
                $t['DATA_CREAZIONE'] = date_format(date_create($t['DATA_CREAZIONE']), "Y-m-d H:i:s");
                $icon = "";
                if ($t['TIPO'] == 'Evoluzione') $icon = '<i class="fa-solid fa-flask-vial"></i>';
                if ($t['TIPO'] == 'Bug') $icon = '<i class="fa-solid fa-bug"></i> ';
                if ($t['TIPO'] == 'Merge') $icon = '<i class="fa-solid fa-code-merge"></i> ';
                if ($t['PRIORITA'] == 1) $icon .= ' <i class="fa-solid fa-b"></i> ';
                if ($t['PRIORITA'] == 2) $icon .= ' <i class="fa-solid fa-m"></i> ';
                if ($t['PRIORITA'] == 3) $icon .= ' <i class="fa-solid fa-a"></i> ';
                $icon = ($is_admin) ? $icon : "";
                $USERNAME = ($is_admin) ? "<br/><strong>" . $t['NOMINATIVO'] . "</strong> - <strong>" . $t['ASSEGNATO'] . "</strong>" : '';
                if( $tipo !=$t['TIPO'])
                {
                     $tipo=$t['TIPO'];
                  echo  '<div class="horizontal-separator">
                            <span>'.$tipo.'</span>
                        </div>';
                 
                }

                $t['TESTO'] = "Id: <strong>" . $t['ID'] . "</strong> " . $icon . "Data: <strong>" . date_format($date, "d/m/Y H:i:s") . "</strong>" . $USERNAME . "<br/><strong>" . $t['POSIZIONE'] . "</strong>:" . utf8_decode($t['TITOLO']);
                $divKamban = "<div onclick='formTicket({$t['ID']}, \"{$t['NOMINATIVO']}\", \"{$t['DATA_CREAZIONE']}\");return false;' class='$classValida ticket' data-id='{$t['ID']}'>{$t['TESTO']}</div>";
                echo $divKamban;
            }
        }
        echo "</div>";
    }
    ?>
</div>
<div id="divKambanForm"></div>
<script src="./view/tickets/JS/kanban.js?p=<?php echo rand(1000, 9999); ?>"></script>