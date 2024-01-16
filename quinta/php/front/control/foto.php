<?php

$ip_camara = $_POST["ip_camara"];
$ruta = $_POST["ruta"];
$camara = $_POST["camara"];
$strPython = "C:/Users/hugo.delacruz/AppData/Local/Programs/Python/Python38-32/python.exe F:/xampp/htdocs/saas_app/img/JCAM.pyc ".$ip_camara." ".$camara." ".$ruta; 
$scriptPython = escapeshellcmd($strPython);
$output = shell_exec($scriptPython);
echo json_encode($output);

?>