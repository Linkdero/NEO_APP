<?php
include_once '../../../../inc/functions.php';
sec_session_start();

/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE); */
$parametros = array(
    "puerta" => $_POST["id_puerta"],
    "nombre" => $_POST["nombre"],
    "dependencia" => $_POST["dependencia"],
    "oficina" => $_POST["oficina"],
    "nombre_oficina" => $_POST["nombre_oficina"],
    "autoriza" => $_POST["autoriza"],
    "carnet" => $_POST["carnet"],
    "objeto" => $_POST["objeto"],
    "usuario" => $_SESSION["id_persona"]
);

$json = json_encode($parametros, JSON_UNESCAPED_UNICODE);
$c1 = $_POST["cam1"];
$c2 = $_POST["cam2"];
$ruta = $_POST["ruta"];
$strPython = "C:/Users/jorge.quinonez/AppData/Local/Programs/Python/Python38-32/python.exe C:/xampp/htdocs/repositorios/SAAS_APP/img/JCAP.pyc ".$ruta." ".$c1." ".$c2." ".$json; 
$scriptPython = escapeshellcmd($strPython);
$output = shell_exec($scriptPython);
echo json_encode($output);

?>