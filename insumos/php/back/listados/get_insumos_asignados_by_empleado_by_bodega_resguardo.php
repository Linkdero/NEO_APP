<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    $id_persona = $_POST['id_persona'];
    $data = array();
    $datos = (new insumo)->get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach ($datos as $d) {
        $bodega = $d['id_bodega_insumo'];
        $bodega_nom = $d['descripcion_corta'];
    }
    $moves = array();
    $moves = (new insumo)->get_all_productos_asignados_actual_by_bodega_resguardo($id_persona, $bodega);

    // echo json_encode($moves);
    foreach ($moves as $m) {
        $cantidad = '';
        $valor = $m['cantidad_entregada'] - $m['cantidad_devuelta'];
        if ($m['id_tipo_insumo'] == 10 || $m['id_tipo_insumo'] == 11
            || $m['id_tipo_insumo'] == 12 || $m['id_tipo_insumo'] == 18
            || $m['id_tipo_insumo'] == 31 || $m['id_tipo_insumo'] == 34
            || $m['id_tipo_insumo'] == 35 || $m['id_tipo_insumo'] == 40
            || $m['id_tipo_insumo'] == 41 || $m['id_tipo_insumo'] == 42
            || $m['id_tipo_insumo'] == 49) {

            $cantidad .= '<span id="message_' . $m['id_prod_insumo_detalle'] . '" class="bar"></span>';
            $cantidad .= "<input id='txt_" . $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'] . "' class='cantidad_ form-control input-sm text-center' required min='1'value='" . intval($valor) . "' disabled></input>";
        } else {
            $cantidad .= '<span id="message_' . $m['id_prod_insumo_detalle'] . '" class="bar"></span>';
            $cantidad .= "<input id='txt_" . $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'] . "' class='cantidad_ form-control input-sm text-center'  value='" . intval($valor) . "' required min='1' disabled></input>";
        }

        $chk1 = '';
        $chk1 .= '<label class="css-input switch switch-success"><input class="chequeado" name="check" id="' . $m['id_doc_insumo'] . '" data-id="' . $m['id_prod_insumo'] . '-' . $m['id_prod_insumo_detalle'] . '" data-name="' . $valor . '" type="checkbox"/><span></span></label>';
        $movimiento = (new insumo)->get_tipo_movimiento_by_id($m['id_tipo_movimiento']);

        ($m['estante']) ? $estante = 'Casilla: '.$m['estante'] : $estante = '';
        $sub_array = array(
            'DT_RowId' => $m['id_doc_insumo'] . '-' . $m['id_prod_insumo_detalle'],
            'descripcion' => $m['descripcion'],
            'anotaciones' => $m['anotaciones'].' '.$estante,
            'estante'=> $m['estante'],
            'transaccion' => $m['id_doc_insumo'],
            'numero_serie' => $m['numero_serie'],
            'cod_inventario' => '',
            'propietario' => '',
            'movimiento' => $movimiento['descripcion'],
            'cantidad_entregada' => number_format($m['cantidad_entregada'], 0, ".", ","),
            'cantidad_devuelta' => number_format($valor, 0, ".", ","),
            'cantidad' => $cantidad,
            'accion' => $chk1
        );

        $data[] = $sub_array;
    }


    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data);

    echo json_encode($results);
else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
