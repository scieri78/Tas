<script>
      tinymce.init({
        selector: '#mytextarea',
        menubar: false,
        statusbar: false,
       // toolbar: false    
     
       plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
   toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image print preview media | forecolor backcolor emoticons ',

      });
    
      
    </script>
   
   <?php $ticket =  $ticket[0];   ?> 
    <form method="post" id=FormAddTicket>
        <input type="hidden" name="action" value="<?= !empty($ticket) ? 'update' : 'create' ?>">
        <input type="hidden"  id="idTicket" name="id" value="<?= $ticket['ID'] ?>">       
        <label>Posizione Menu:</label><br>        
        <select id="posizione" name="posizione" class="selectSearch">
            <?php         
            foreach ($posizione as $ks => $as ) {
                $sel = (isset($ticket) && $ticket['POSIZIONE'] == $as) ? 'selected' : '';
                echo "<option value='$as' $sel>$as</option>";
            }          
              $sel = (isset($ticket) && $ticket['POSIZIONE'] == 'Altro') ? 'selected' : '';
                ?>
            <option <?=$sel ?> value="Altro" >Altro</option>
        </select><br>
        <label>Oggetto:</label><br>
        <input type="text" name="titolo" value="<?= $ticket['TITOLO']  ?>"><br>
        <label>Descrizione:</label><br>
        <textarea id="mytextarea" name="descrizione"><?= $ticket['DESCRIZIONE'] ?? '' ?></textarea><br>
        <?php if($is_admin):?>
        <label>Stato:</label><br>      
        <select name="stato">
            <?php
            $stati = ['Aperto', 'Da fare poi',  'In Lavorazione', 'Richiesta Info', 'Test', 'Risolto'];
            foreach ($stati as $s) {
                $sel = (isset($ticket) && $ticket['STATO'] == $s) ? 'selected' : '';
                echo "<option value='$s' $sel>$s</option>";
            }
            ?>
        </select><br>
        <?php else: ?>
         <input type="hidden" value="<?php echo ($ticket['STATO']?$ticket['STATO']:'Aperto')?>" name="stato">
         <?php endif; ?>  
         <?php if($is_admin):?>
        <label>Assegnato:</label><br>
        <select name="assegnato">
            <?php
            $assegnato = ['EU8736D' => 'EU8736D - Simone Cieri',
            'RU20903' => 'RU20903 - Giacomo Mandro',
            'EU8738M' => 'EU8738M - Simone Di Pisa',
            'RU18578' => 'RU18578 - Orazi Christian'
            ];
            foreach ($assegnato as $ks => $as ) {
                $sel = (isset($ticket) && $ticket['ASSEGNATO'] == $ks) ? 'selected' : '';
                echo "<option value='$ks' $sel>$as</option>";
            }
            ?>
        </select><br>  
         <?php else: ?>
         <input type="hidden" value="<?php echo ($ticket['ASSEGNATO']?$ticket['ASSEGNATO']:'EU8736D')?>" name="assegnato">
         <?php endif; ?>   
         <?php if($is_admin):?>
        <label>Tipo:</label><br>     
        <select name="tipo">
            <?php
            $tipo = ['Bug', 'Evoluzione','Merge'];
            foreach ($tipo as $t) {
                $sel = (isset($ticket) && $ticket['TIPO'] == $t) ? 'selected' : '';
                echo "<option value='$t' $sel>$t</option>";
            }
            ?>
        </select><br>
          <?php else: ?>
         <input type="hidden" value="<?php echo ($ticket['TIPO']?$ticket['TIPO']:'Bug')?>" name="tipo">
         <?php endif; ?>
         <?php if($is_admin):?>
        <label>Priorità:</label><br>
        <select name="priorita">
            <?php
            $priorita = [1=>'Bassa', 2=>'Media', 3=>'Alta'];
            foreach ($priorita as $k=>$p) {
                $sel = (isset($ticket) && $ticket['PRIORITA'] == $k) ? 'selected' : '';
                echo "<option value='$k' $sel>$p</option>";
            }
            ?>
        </select>
         <?php else: ?>
         <input type="hidden" value="<?php echo ($ticket['PRIORITA']?$ticket['PRIORITA']:1)?>" name="priorita">
         <?php endif; ?>
         <br>   
             <?php if(!$is_admin):?>        
            <label>Valida:</label><br>
            <input type="checkbox" name="valida" <?php echo ($ticket['VALIDA']?'checked':'')?>>
             <?php else: ?>
                <input type="hidden" value="<?php echo ($ticket['VALIDA']?'1':'0')?>" name="valida">
            <?php endif; ?>
        <br>   
        <br>
        <button id="addworkflow" onclick="addTichet();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-save"> </i> Salva</button>
        <?php if($is_admin):?>
        <button id="addworkflow" onclick="delTicket();return false;" class="btn" style="display: inline-block;"><i class="fa-solid fa-trash"> </i> Cancella</button>
        <?php endif; ?>
    </form>

    <script>
    $(".selectSearch").select2();
    </script>