<div id="divDownload" >
<p>
<?php
echo $filename;
?>
</p>
<button onclick="downloadfile('<?php echo $filename;?>','DDL');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> DOWNLOAD FILE</button>
<button onclick="window.open('./DDL/<?php echo $filename;?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-window-restore"></i> APRI SU TAB</button>
<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button>
</div>
<textarea class="Pkg" readonly ><?php echo $TestoPkg; ?></textarea>



