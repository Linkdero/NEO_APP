<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    set_time_limit(0);

    $datos = (new insumo)->get_acceso_bodega_usuario($_SESSION['id_persona']);
    $bodega = 0;
    foreach ($datos as $d) {
        $bodega = $d['id_bodega_insumo'];
        $bodega_nom = $d['descripcion_corta'];
    }
    $insumos = (new insumo)->get_all_insumos_by_bodega($bodega);
    $data = array();

    foreach ($insumos as $insumo) {
        $accion = '';
        $primer_nombre = $insumo['Primer_nombre'] ?: '';
        $segundo_nombre = $insumo['Segundo_nombre'] ?: '';
        $tercer_nombre = $insumo['Tercer_nombre'] ?: '';
        $primer_apellido = $insumo['Primer_apellido'] ?: '';
        $segundo_apellido = $insumo['Segundo_apellido'] ?: '';
        $tercer_apellido = $insumo['Tercer_apellido'] ?: '';
        $nombre = $primer_nombre . ' ' . $segundo_nombre . ' ' . $tercer_nombre . ' ' . $primer_apellido . ' ' . $segundo_apellido . ' ' . $tercer_apellido;
        $asignacion = ($insumo['Codigo']) ? ' [' . $insumo['Codigo'] . ']' : '';
        $frecuencia = (!empty($insumo['idFrecuencia'])) ? '<br> - '.$insumo['idFrecuencia'] : '';
        $sub_array = array(
            'DT_RowId' => $insumo['id_prod_ins_detalle'],
            'sicoin' => $insumo['codigo_inventarios'],
            'tipo' => $insumo['id_tipo_insumo'] . '/' . $insumo['tipo'],
            'marca' => $insumo['marca'],
            'modelo' => $insumo['modelo'],
            'serie' => $insumo['numero_serie'].$frecuencia,
            'estante' => $insumo['numero_estante'] ?: '',
            'inventario' => $insumo['codigo_inventarios'],
            'estado' => $insumo['estado'],
            'resguardo' => $insumo['resguardo'],
            'existencia' => number_format($insumo['existencia'], 0, ".", ","),
            'gafete' => $insumo['Id_persona'] ?: '',
            'empleado' => $nombre . $asignacion,
            'accion' => $accion,
            'bodega' => $bodega_nom
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
