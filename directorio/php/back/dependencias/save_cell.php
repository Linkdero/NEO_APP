<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $id_dependencia = $_POST["id_dependencia"];
    $numero = $_POST["numero"];
    $nombre = $_POST["nombre"];
    $puesto = $_POST["puesto"];

    $DIRECTORIO = new Directorio();
    $id_cargo = $DIRECTORIO::get_cargo_by_dependencia($id_dependencia);
    $response = $DIRECTORIO::save_cell($id_cargo[0]["id_cargo"], $numero, $nombre, $puesto);

    echo json_encode($response);
} else {
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}
