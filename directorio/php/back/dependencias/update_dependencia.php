<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';

    if (array_key_exists("id_dependencia", $_POST) && $_POST['id_dependencia'] > 0) {
        $id_dependencia = $_POST['id_dependencia'];
        $funcionario = $_POST['funcionario'];
        $direccion = $_POST['direccion'];
        $array_telefonos = $_POST['telefonos'];
        $DIRECTORIO = new Directorio();
        $dependencia = $DIRECTORIO::get_dependencia_by_id($id_dependencia);
    } else {
        $response = array(
            "msg" => "Error intente de nuevo"
        );
    }
    echo json_encode($response);
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
