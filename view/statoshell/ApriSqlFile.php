
<div id="divDownload" >
<button onclick="downloadfile('<?php echo $filename;?>','TMP');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> DOWNLOAD FILE</button>
<button onclick="window.open('./TMP/<?php echo $filename;?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-window-restore"></i> APRI SU TAB</button>
<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button>
<div id="CheckShowVar" class="CheckShowVar"><input onclick="reOpenSqlFile(<?php echo $IDSQL; ?>)" type="checkbox" id="ShowVar" name="ShowVar" value="1" <?php if ( "$ShowVar" == "1" ) { ?>checked<?php } ?> >Replace Var</div>
</div>
<textarea class="DivShowSQL" readonly ><?php echo $TestoFile; ?></textarea>


