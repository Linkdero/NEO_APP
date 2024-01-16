<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $transaccion = $_POST['id_doc_solvencia'];
    $solvencia = array();
    $solvencia = (new insumo)->get_solvencia($transaccion);
    $data = array();

    $datos = (new insumo)->get_empleado_by_transaccion($transaccion);

    $id_persona = $datos['id_persona'];

    $e = array();
    //$e = empleado::get_empleado_by_id_ficha($id_persona);
    //$direccion = empleado::get_direcciones_saas_by_id($datos['id_persona_direccion_recibe']);
    //$tipos = insumo::get_tipos_movimientos($tipo);//Ingreso a Bodega
    $data = array();

    /*$nombre=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.
        $e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'];
  */

    $movimiento = $datos['tipo_movimiento'];

    foreach ($solvencia as $s) {
        //echo $s['id_doc_solvencia'];

        $e = (new empleado)->get_empleado_by_id_ficha($s['id_persona_solvencia']);
        //$direccion = $clase2->get_direcciones_saas_by_id($s['id_direccion_solvencia']);
        //$tipos = insumo::get_tipos_movimientos($tipo);//Ingreso a Bodega
        $marca = '';
        $modelo = '';
        $serie = '';
        $imei = '';
        $bodega = '';
        $gafete = '';
        $area = '';
        if ($s['modelo'] = !null) {
            $marca = $s['marca'];
            $modelo = $s['modelo'];
            $serie = $s['numero_serie'];
            $imei = $s['codigo_inventarios'];
        }
        $nombre = $e['primer_nombre'] . ' ' . $e['segundo_nombre'] . ' ' . $e['tercer_nombre'] . ' ' .
            $e['primer_apellido'] . ' ' . $e['segundo_apellido'] . ' ' . $e['tercer_apellido'];
        $encargado = (new empleado)->get_empleado_by_id_ficha($s['id_encargado']);
        $gafete = ' [' . $s['id_persona_solvencia'] . ']';
        if ($s['id_bodega_insumo'] == 5907) {
            $bodega = 'Móviles';
            $direccion_abreviatura = 'DCEI';
            $bodega_abreviatura = 'M';
            $area = 'Telefonía Movil';
            $direccion = 'Comunicaciones e Informática';
        } elseif ($s['id_bodega_insumo'] == 3552) {
            $bodega = 'Radios';
            $direccion_abreviatura = 'DCEI';
            $bodega_abreviatura = 'R';
            $area = 'Radiocomunicaciones';
            $direccion = 'Comunicaciones e Informática';
        } elseif ($s['id_bodega_insumo'] == 5066) {
            $bodega = 'ARMERIA';
            $direccion_abreviatura = 'SEG';
            $bodega_abreviatura = 'A';
            $area = 'Armeria';
            $direccion = 'DIRECCION DE SEGURIDAD';
        } else {
            $bodega = $s['id_bodega_insumo'];
            $direccion_abreviatura = NULL;
            $bodega_abreviatura = NULL;
            $area = NULL;
        }
        $sub_array = array(
            'transaccion' => $s['id_doc_solvencia'],
            'empleado' => $nombre,
            'gafete' => $gafete,
            'direccion' => $direccion,
            'direccion_abreviatura' => $direccion_abreviatura,
            'marca' => $marca,
            'modelo' => $modelo,
            'serie' => $serie,
            'imei' => $imei,
            'cantidad' => number_format($s['cantidad'], 0, ".", ","),
            'solvente' => $s['solvente'],
            'encargado' => $encargado['primer_nombre'] . ' ' . $encargado['primer_apellido'],
            'solvencia_tipo' => $s['tipo_solvencia'],
            'correlativo' => $s['correlativo_solvencia'],
            'fecha' => 'Guatemala ' . fechaCastellano($s['fecha_solvencia']),
            'hora' => date('H:m',strtotime($s['fecha_solvencia'])),
            'bodega_id' => $s['id_bodega_insumo'],
            'bodega' => $bodega,
            'bodega_abreviatura' => $bodega_abreviatura,
            'area' => $area,
            'anio' => $s['year'],
            'observaciones' => $s['observaciones']
        );
        $data[] = $sub_array;
    }
    echo json_encode($data);
else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
