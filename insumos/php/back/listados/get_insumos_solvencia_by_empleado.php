<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    include_once '../../../../empleados/php/back/functions.php';
    $persona_id = $_POST['id_persona'];
    $movimiento_tipo = $_POST['tipo_movimiento'];
    $data = array();
    if ($persona_id != '') {

        $insumo = new insumo;
        $empleado = new empleado;
        $bodega_accesos = $insumo->get_acceso_bodega_usuario($_SESSION['id_persona']);

        foreach ($bodega_accesos as $bodega_acceso) {
            $bodega = $bodega_acceso['id_bodega_insumo'];
            $bodega_nombre = $bodega_acceso['descripcion_corta'];
            $movimientos = array();
            $movimientos = $insumo->get_all_productos_asignados_actual_by_bodega($persona_id, $bodega);
            $data = array();

            foreach ($movimientos as $movimiento) {
                $anotaciones = '';
                $nombre_en_caliente = '';
                if ($movimiento['id_persona_diferente'] != 0) {
                    $persona_diferente = new empleado;
                    if ($movimiento['id_persona_diferente'] == $persona_id) {
                        $persona_diferente = $empleado->get_empleado_by_id_ficha($movimiento['id_persona']);
                    } else {
                        $persona_diferente = $empleado->get_empleado_by_id_ficha($movimiento['id_persona_diferente']);
                    }
                    $nombre_en_caliente = 'Asignado también a: ' . $persona_diferente['primer_nombre'] . ' ' . $persona_diferente['segundo_nombre'] . ' ' . $persona_diferente['tercer_nombre'] . ' ' . $persona_diferente['primer_apellido'] . ' ' . $persona_diferente['segundo_apellido'] . ' ' . $persona_diferente['tercer_apellido'] . ' [' . $persona_diferente['id_persona'] . ']';
                }
                $sub_array = array(
                    'DT_RowId' => $movimiento['id_prod_insumo_detalle'],
                    'descripcion' => $movimiento['descripcion'],
                    'anotaciones' => $movimiento['anotaciones'] . ' ' . $nombre_en_caliente,
                    //'estante'=>'',//$m['id_bodega_insumo'],
                    'transaccion' => $movimiento['id_doc_insumo'],
                    'numero_serie' => $movimiento['numero_serie'],
                    'cod_inventario' => '',
                    'propietario' => '',
                    'movimiento' => $movimiento['movimiento_tipo'],
                    'cantidad_entregada' => number_format($movimiento['cantidad_entregada'], 0, ".", ","),
                    'cantidad_devuelta' => number_format($movimiento['cantidad_devuelta'], 0, ".", ",")
                );
                $data[] = $sub_array;
            }
        }
    }
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    );

    echo json_encode($results);


else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
