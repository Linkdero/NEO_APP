<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $serie = $_POST['serie'];
    $producto = insumo::get_insumo_by_serie($serie); //Ingreso a Bodega
    $data = array();
    $codigo = '';
    if ($producto['id_prod_ins_detalle'] != '') {
        $codigo = $producto['id_prod_ins_detalle'];
    }
    $cantidad = 1;
    if (
        $producto['id_tipo_insumo'] == 10 || $producto['id_tipo_insumo'] == 11
        || $producto['id_tipo_insumo'] == 12 || $producto['id_tipo_insumo'] == 18
        || $producto['id_tipo_insumo'] == 31 || $producto['id_tipo_insumo'] == 34
        || $producto['id_tipo_insumo'] == 35 || $producto['id_tipo_insumo'] == 40
        || $producto['id_tipo_insumo'] == 41 || $producto['id_tipo_insumo'] == 42
        || $producto['id_tipo_insumo'] == 43 || $producto['id_tipo_insumo'] == 49
        || $producto['id_tipo_insumo'] == 54 || $producto['id_tipo_insumo'] == 55
        || $producto['id_tipo_insumo'] == 14  || $producto['id_tipo_insumo'] == 15
        || $producto['id_tipo_insumo'] == 20  || $producto['id_tipo_insumo'] == 21
        || $producto['id_tipo_insumo'] == 32  || $producto['id_tipo_insumo'] == 68
        || $producto['id_tipo_insumo'] == 65  || $producto['id_tipo_insumo'] == 89
        || $producto['id_tipo_insumo'] == 90 || $producto['id_tipo_insumo'] == 91
        || $producto['id_tipo_insumo'] == 72 || $producto['id_tipo_insumo'] == 76
        || $producto['id_tipo_insumo'] == 70 || $producto['id_tipo_insumo'] == 75
        || $producto['id_tipo_insumo'] == 69 || $producto['id_tipo_insumo'] == 65
        || $producto['id_tipo_insumo'] == 80 || $producto['id_tipo_insumo'] == 71
        || $producto['id_tipo_insumo'] == 66 || $producto['id_tipo_insumo'] == 88
        || $producto['id_tipo_insumo'] == 73
    ) {
        $cantidad .= '<span id="message' . $codigo . '" class="bar"></span>';
        $cantidad = "<input id='txt" . $codigo . "' class='cantidad_ form-control input-sm text-center form_corto' style='' value='1' required min='1' max='" . $producto['existencia'] . "' ></input>";
    } else {
        $cantidad = "<input id='txt" . $codigo . "' disabled class='cantidad_ form-control input-sm form_corto' style='' value='1'  required min='1' ></input>";
    }
    //$desc="<textarea id='text_".$codigo."' class='form-control form_medio' rows='2'></textarea>";
    $data = array(
        'codigo' => $producto['id_prod_insumo'] . '-' . $codigo,
        'marca' => $producto['marca'],
        'modelo' => $producto['modelo'],
        'serie' => $producto['numero_serie'],
        'existencia' => number_format($producto['existencia'], 0, ".", ","),
        'estado' => $producto['id_status'],
        'cantidad' => $cantidad/*,
    'desc'=>$desc*/
    );


    echo json_encode($data);


else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
