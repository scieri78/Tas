<?php
    header("Content-Description: File Transfer");
    header("Content-Type: application/pdf; charset=UTF-8");    
    header("Content-Transfer-Encoding: binary");
    header('Content-Disposition: attachment; filename=' . str_replace(".txt","",$filename));   
    readfile($file);
  ?>