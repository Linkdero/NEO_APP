<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $persona_id = $_POST['id_persona'];
    $descripcion = $_POST['descripcion'];
    $movimiento_tipo = $_POST['tipo_movimiento'];

    if (is_numeric($persona_id) and is_numeric($movimiento_tipo)) {
        $empleado = array();
        $estado = empleado::get_empleado_estado_by_id($persona_id);

        if ($estado['primer_nombre'] != '') {
            if ($estado['estado_persona'] == 1) {
                $bodega_accesos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
                foreach ($bodega_accesos as $bodega_acceso) {
                    $bodega = $bodega_acceso['id_bodega_insumo'];
                    $movimientos = array();
                    $movimientos = insumo::get_all_productos_asignados_actual_by_bodega($persona_id, $bodega);
                    $movimientos_total = 0;
                    foreach ($movimientos as $movimiento) {
                        $movimientos_total++;
                    }
                }
                if ($movimientos_total === 0) {
                    echo 'solvente';
                } elseif (($bodega == 3552 || $bodega == 5066 || $bodega == 5907) && $movimiento_tipo == 1) {
                    echo 'vacaciones-insolvente';
                } else {
                    echo 'insolvente';
                }
            } else {
                echo 'error1';
            }
        } else {
            echo 'error2';
        }
    } else {
        echo 'error3';
    }
else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
