
<div id="divDownload" >
<div id="CheckShowVar" class="CheckShowVar"></div>
<p>
<?php
$file = ($file)?$file:$Log;
//echo $file;
?>
<?php
    if(trim($TestoFile)!=''){
        
?>
</p>

<button onclick="downloadfile('<?php echo $filename;?>','TMP');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-download"> </i> DOWNLOAD FILE</button>
<button onclick="window.open('./TMP/<?php echo $filename;?>');return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-window-restore"></i> APRI SU TAB</button>
<button onclick="copy();return false;" id="removeWorkFlow" class="btn" style="display: inline-block;"><i class="fa-solid fa-copy"> </i> Copia Testo</button>
</div>
<input type="hidden" id="Log" name="Log" value="<?php echo $file; ?>">
<input type="hidden" id="IDHS" id="IDHS" value="<?php echo $IDSH; ?>">



<textarea class="DivShow" readonly ><?php echo $TestoFile; ?></textarea>
<?php
    }else{
    echo "<br><br> File Vuoto o inesistente!</p></div>";
    }


