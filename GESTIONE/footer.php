<?php
if ( "$FindSite" != ""  ){
  ?>
  <div id="SitoOffLine" >Sito Offline</div>
  <style>
     #SitoOffLine{
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 99999999;
	border: 1px solid black;
	background: white;
	width: 300px;
	height: 100px;
	margin: auto;
	text-align: center;
	padding: 25px;
	font-size: 30px;	   
	 }
  </style>
  <?php
  exit;
}
?>