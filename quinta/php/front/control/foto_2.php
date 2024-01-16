<?php

$ip_camara = $_POST["ip_camara"];
$ruta = $_POST["ruta"];
$camara = $_POST["camara"];
$strPython = "C:/Users/jorge.quinonez/AppData/Local/Programs/Python/Python38-32/python.exe C:/xampp/htdocs/repositorios/SAAS_APP/img/JCAM.pyc ".$ip_camara." ".$camara." ".$ruta; 
$scriptPython = escapeshellcmd($strPython);
$output = shell_exec($scriptPython);
echo json_encode($output);

?>