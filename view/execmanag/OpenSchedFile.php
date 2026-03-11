

<form id="FormFile" method="POST">
  <div id="divDownload" >
<button  onclick="downloadfile('<?php echo $filename;?>','TMP');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> Download File</button>
<button onclick="window.open('./TMP/<?php echo $filename;?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-window-restore"></i> APRI SU TAB</button>
<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button>
</div>
<textarea class="DivShowSQL" readonly ><?php echo $TestoLog; ?></textarea>
</form>
<script>
$("#Filedialog").dialog({title: "<?php echo $titolo; ?>"});
</script>


