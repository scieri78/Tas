<?php
$pswDb2 = 'V0ZTcDQ1NXR1c2lu';
$DB2pass = base64_decode($pswDb2);

$DB2database = 'TASPCUSR';
$DB2user = 'tusin107';
$DB2hostname = 'faitssidbteav01.te.azi.allianzit';
$DB2port = 50003;

$db2_conn_string = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$DB2database;" .
"HOSTNAME=$DB2hostname;PORT=$DB2port;PROTOCOL=TCPIP;UID=$DB2user;PWD=$DB2pass;";

$TServer='SVIL USER';
$DATABASE='USER';

if ( !$conn ){ $conn = db2_connect($db2_conn_string, '', ''); }

if (!$conn) {
    ?>
    <script> 
    alert('CONNESSIONE AL DB2 FALLITA: <?php 
        echo "Error: " . (db2_conn_error());
        echo "Msg:   " . (db2_conn_errormsg());
    ?>');
    </script>
    <?php
	exit;
}

$rootdir='/opt/rh/httpd24/root/var/www/html/nsw/TASUSR';

?>
