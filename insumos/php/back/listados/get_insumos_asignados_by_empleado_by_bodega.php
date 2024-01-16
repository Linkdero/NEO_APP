<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    include_once '../../../../empleados/php/back/functions.php';
    $data = array();
    $id_persona = $_POST['id_persona'];
    $datos = (new insumo)->get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach ($datos as $d) {
        $bodega = $d['id_bodega_insumo'];
        $bodega_nom = $d['descripcion_corta'];
    }
    $moves = array();
    $moves = (new insumo)->get_all_productos_asignados_actual_by_bodega($id_persona, $bodega);
    // echo json_encode($moves);

    foreach ($moves as $m) {
        $cantidad = '';
        $valor = $m['cantidad_entregada'] - $m['cantidad_devuelta'];
        if ($m['tipo'] == 0) {
            $cantidad .= '<span id="message_' . $m['id_prod_insumo_detalle'] . '" class="bar"></span>';
            $cantidad .= "<input id='txt_" . $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'] . "' class='cantidad_ form-control input-sm text-center' required min='1' value='" . $valor . "'></input>";
        } else {
            $cantidad .= '<span id="message_' . $m['id_prod_insumo_detalle'] . '" class="bar"></span>';
            $cantidad .= "<input id='txt_" . $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'] . "' class='cantidad_ form-control input-sm text-center'  value='" . $valor . "' required min='1' disabled></input>";
        }

        $chk1 = '';
        $chk1 .= '<label class="css-input switch switch-success"><input name="check" id="' . $m['id_doc_insumo'] . '" data-id="' . $m['id_prod_insumo'] . '-' . $m['id_prod_insumo_detalle'] . '" data-name="' . $valor . '" data-pk="' . $m['id_tipo_movimiento'] . '" type="checkbox"/><span></span></label>';

        $nombre_en_caliente = '';
        if ($m['id_persona_diferente'] != 0) {
            $p = 0;
            if ($m['id_persona_diferente'] == $id_persona) {
                $p = (new empleado)->get_empleado_by_id_ficha($m['id_persona']);
            } else {
                $p = (new empleado)->get_empleado_by_id_ficha($m['id_persona_diferente']);
            }

            $nombre_en_caliente = 'Asignado también a: ' . $p['primer_nombre'] . ' ' . $p['segundo_nombre'] . ' ' . $p['tercer_nombre'] . ' ' . $p['primer_apellido'] . ' ' . $p['segundo_apellido'] . ' ' . $p['tercer_apellido'] . ' [' . $p['id_persona'] . ']';
        }

        ($m['estante']) ? $estante = 'Casilla: ' . $m['estante'] : $estante = '';
        $sub_array = array(
            'DT_RowId' => $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'],
            'descripcion' => $m['descripcion'] . "\n" . $estante . "",
            'anotaciones' => $m['fecha'] . ' ' . $nombre_en_caliente . ' ' . $estante,
            'estante' => $m['estante'],
            'transaccion' => $m['id_doc_insumo'],
            'numero_serie' => $m['numero_serie'],
            'cod_inventario' => '',
            'propietario' => '',
            'movimiento' => $m['movimiento_tipo'],
            'cantidad_entregada' => number_format($m['cantidad_entregada'], 0, ".", ","),
            'cantidad_devuelta' => number_format($m['cantidad_devuelta'], 0, ".", ","),
            'cantidad' => $cantidad,
            'accion' => $chk1
        );

        $data[] = $sub_array;
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
