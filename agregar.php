<?php

$db_srv = '172.27.48.125';
$db_usr = 'merhengo';
$db_psw = '10ceroun0';
$db_name = 'abps_control';
header('Content-Type: text/html; charset=UTF-8');

$connection_options = array('Database'=>''.$db_name.'', 'UID'=>''.$db_usr.'', 'PWD'=>''.$db_psw.'');
$conn = sqlsrv_connect($db_srv, $connection_options);
if(!is_resource($conn)){
    echo "ok";
}
if($_POST['id'] == "" || strtoupper($_POST['id']) == 'NO TIENE')
{
    $consecutivo = 'No tiene';
}
else{
    $consecutivo = strtoupper($_POST['id']);
}
$marca = $_POST['marca'];
$nombre = $_POST['nombre'];
$sn = strtoupper($_POST['sn']);
$camp = $_POST['campaign'];
$coord= $_POST['coordinador'];
$dirip = $_SERVER['REMOTE_HOST'];

$nombre = iconv('UTF-8', 'ISO-8859-1', $nombre);
$nombre = mb_strtoupper($nombre, 'UTF-8');

echo $consecutivo; 
echo $marca;
echo $nombre;
echo $sn;

$sql = "insert into inventariodiademas (consecutivo, marca, nombre, serial, coordinador, campaign, ip_equipo, fecha) values (
      '".$consecutivo."', 
      '".$marca."', 
      '".$nombre."', 
      '".$sn."', 
      '".$coord."', 
      '".$camp."', 
      '".$dirip."', 
      GETDATE())";

$stmt = sqlsrv_query($conn, $sql);
$url = 'Location: index.php?do=1';

if(!stmt)
{
    die('MSSQL error: ' . mssql_get_last_message());
    $url = 'Location: index.php?do=0';
}

header($url);

?>