<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

if (function_exists('verificar_session') && verificar_session() == true) :
    $permisos = array();
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8931);

    $pos = $array[3]['id_persona'];
    $permisos = array(
        'soli' => ($array[0]['flag_es_menu'] == 1) ? true : false,

        'dirSoli' => ($array[1]['flag_es_menu'] == 1) ? true : false,

        'tecnico' => ($array[2]['flag_es_menu'] == 1) ? true : false,
        'tecnicoActua' => ($array[2]['flag_actualizar'] == 1) ? true : false,

        'jefe' => ($array[3]['flag_es_menu'] == 1) ? true : false,
        'jefeActua' => ($array[3]['flag_actualizar'] == 1) ? true : false,
        'jefeAcce' => ($array[3]['flag_acceso'] == 1) ? true : false,

        'dir' => ($array[4]['flag_es_menu'] == 1) ? true : false,
        'dirElim' => ($array[4]['flag_eliminar'] == 1) ? true : false,
        'dirActua' => ($array[4]['flag_actualizar'] == 1) ? true : false,
        'dirAcce' => ($array[4]['flag_acceso'] == 1) ? true : false,
        'dirAuto' => ($array[4]['flag_autoriza'] == 1) ? true : false,

        'desarrollo' => ($array[5]['flag_es_menu'] == 1) ? true : false,
        'soporte' => ($array[6]['flag_es_menu'] == 1) ? true : false,
        'radios' => ($array[7]['flag_es_menu'] == 1) ? true : false,
    );
    echo json_encode($permisos);
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
