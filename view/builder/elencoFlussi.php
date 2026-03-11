<div id="elencoFlussi">
	<input type="hidden" id="selectRilascio">
	<?php
	$livello = -1;
	$count=0;
	foreach ($datiDettagliWf as $row) {
		$IdFlusso = $row['ID_FLU'];
		$Flusso = $row['FLUSSO'];
		$BlockCons = $row['BLOCK_CONS'];
		$Utilizzato = $row['UTILIZZATO'];
		$LAB = $row['LAB'];
		$Usato = $row['USATO'];
		$livellof = $row['LIV'];
		$hide = $row['hide'];
		$newLevel = false;
		if ($livellof != $livello) {

			$livello = $livellof;
			$newLevel = true;
		}

		$classHide = ($hide == 1) ? 'hideFlusso' : '';

	?>
<?php if ($newLevel && $count) {  ?>
			</div>
		<?php } ?>
		
		<?php if ($newLevel) { ?>
			<div id="Liv0" class="Livello">
          <div class="TitoloLiv"><b>LIVELLO <?php echo $livello; ?></b></div>
		<?php } ?>
	

			<div onmouseenter="getlegamiflussi('<?php echo $IdFlusso; ?>',1)"
				onmouseleave="getlegamiflussi('<?php echo $IdFlusso; ?>',0)" class="Flusso <?php echo $classHide; ?>" id="Flusso<?php echo $IdFlusso; ?>">
				<div class="TitFlu TitFlusso" title="<?php echo $IdFlusso; ?>">
					<i class="fa-solid fa-caret-left"></i>
					<b onclick="FLoadDett('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdFlusso; ?>','<?php echo $Flusso; ?>')" style="color:black"><?php echo $Flusso; ?></b>
					<i class="fa-solid fa-caret-right"></i>
				</div>
				<div onclick="FLoadDett('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdFlusso; ?>','<?php echo $Flusso; ?>')" style="height:10px"></div>
				<?php if ($LAB > 0) { ?>
					<i class="fa-regular fa-flask"></i>
				<?php } else { ?>
					<i style="color:white" class="fa-regular fa-circle"></i>
				<?php } ?>
				<i onclick="FLoadDett('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdFlusso; ?>','<?php echo $Flusso; ?>')" class="fa-solid fa-pen-to-square"></i>
				<div class="selectRilascio">
					<input type="checkbox" id="ChkRilascia_<?php echo $IdFlusso; ?>" name="ChkRilascia[]" value="<?php echo $IdFlusso; ?>">
					<label for="ChkRilascia_<?php echo $IdFlusso; ?>"> <i class="fa-solid fa-rocket selRilascio"> </i></label>
				</div>
			</div>


		<?php
		$count++;
	}
		?>
</div>
<div id="ShowDettFlusso">
</div>
<script src="./JS/dialog.header.js?p=<?php echo rand(1000, 9999); ?>"></script>

<div class="page-container">

  <div class="sidebar-menu-right">
 
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    <div class="menu-right">
      <ul id="menu-right" >
<?php
	$livello = -1;
	$i =  -1;
	foreach ($datiDettagliWf as $row) {
		$IdFlusso = $row['ID_FLU'];
		$Flusso = $row['FLUSSO'];
		$BlockCons = $row['BLOCK_CONS'];
		$Utilizzato = $row['UTILIZZATO'];
		$LAB = $row['LAB'];
		$Usato = $row['USATO'];
		$livellof = $row['LIV'];
		$hide = $row['hide'];
		$newLevel = false;
		
		if ($livellof != $livello) {

			$livello = $livellof;
			$newLevel = true;
			$i++;
		}

		$classHide = ($hide == 1) ? 'hideFlusso' : '';

	?>

		<?php if ($i && $newLevel) {  ?>
			</li>
		</ul>
		<?php } ?>

		<?php if ($newLevel) { ?>
			<li id="menu-right-l<?php echo $livello; ?>"><a href="#"> Livello <?php echo $livello; ?> </a> 
			<ul id="menu-right-l<?php echo $livello; ?>-sub">
			<?php } ?>

			<li> <a href="#" onclick="FLoadDett('<?php echo $IdWorkFlow; ?>','<?php echo $WorkFlow; ?>','<?php echo $IdFlusso; ?>','<?php echo $Flusso; ?>')"><?php echo $Flusso; ?></a>
				</li>

		<?php

	}
		?>
		</ul>
    </div>
	<div id="selectDialog">
	<?php
	
	$selectSX = ($ndialog==1)?'checked="checked"':'';
	$selectDX = ($ndialog==2)?'checked="checked"':'';
	$selectSX = ($ndialog=='')?'checked="checked"':$selectSX;
    ?>
	<input type="checkbox" id="paragona" name="paragona" value="1" <?php echo $paragona; ?>>
	<label for="css">Paragona</label><br>
	<input type="radio" id="nBilderdialog1" name="xdialog" value="1" <?php echo $selectSX; ?>>
	<label for="css">SINISTRA</label><br>
	<input type="radio" id="nBilderdialog2" name="xdialog" value="2" <?php echo $selectDX; ?>>
	<label for="html">DESTRA</label><br>

	</div>
  </div>
</div>

<script>
var toggle = true;
            
$(".sidebar-icon").click(function() {                
  if (toggle)
  {
    $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
    $("#menu span").css({"position":"absolute"});
  }
  else
  {
    $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
    setTimeout(function() {
      $("#menu span").css({"position":"relative"});
    }, 400);
  }
                
                toggle = !toggle;
            });
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>