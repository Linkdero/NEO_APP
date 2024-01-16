<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';

  $transaccion = $_POST['id_doc_insumo'] ?? NULL;
  $insumos = (new insumo)->get_insumos_by_transaccion($transaccion);
  $transaccion_encabezado = (new insumo)->get_transaccion_encabezado_by_id($transaccion);
  $data = array();

  $empleados = (new insumo)->get_empleados_by_transaccion($transaccion);
  $empleado_secundario = '- Ninguno -';

  foreach ($empleados as $empleado) {
    $persona = (new empleado)->get_empleado_by_id_ficha($empleado['id_persona']);
    if ($empleado['flag_firmante']) {
      $direccion = (new empleado)->get_direcciones_saas_by_id($empleado['id_persona_direccion_recibe']);
      $movimiento = $empleado['tipo_movimiento'];
      $empleado_primario = $persona['primer_nombre'] . ' ' . $persona['segundo_nombre'] . ' ' .
        $persona['tercer_nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido'] . ' ' .
        $persona['tercer_apellido'] . ' [' . $empleado['id_persona'] . ']';
    } else {
      $empleado_secundario = $persona['primer_nombre'] . ' ' . $persona['segundo_nombre'] . ' ' .
        $persona['tercer_nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido'] . ' ' .
        $persona['tercer_apellido'] . ' [' . $empleado['id_persona'] . ']';
    }
  }

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT id_persona_entrega,id_bodega_insumo
          FROM inv_movimiento_encabezado
          WHERE id_doc_insumo=?
         ";
  $p = $pdo->prepare($sql);
  $p->execute(array($transaccion)); // 65 es el id de aplicaciones
  $encargado = $p->fetch();

  $sql2 = "SELECT b.descripcion_corta
          FROM inv_movimiento_encabezado a
          INNER JOIN tbl_catalogo_detalle b ON a.id_bodega_insumo=b.id_item
          WHERE a.id_doc_insumo=?
         ";
  $p2 = $pdo->prepare($sql2);
  $p2->execute(array($transaccion)); // 65 es el id de aplicaciones
  $bodega = $p2->fetch();

  Database::disconnect_sqlsrv();

  $encargado_ficha = (new empleado)->get_empleado_by_id_ficha($encargado['id_persona_entrega']);
  $encargado_nombre = $encargado_ficha['primer_nombre'] . ' ' . $encargado_ficha['primer_apellido'];

  $encabezado_observaciones = NULL;
  $descripcion = $transaccion_encabezado['descripcion'] ?? NULL;
  $nombre_persona_autoriza = $transaccion_encabezado['nombre_persona_autoriza'] ?? NULL;
  $nro_documento = $transaccion_encabezado['nro_documento'] ?? NULL;
  if ($descripcion or $nombre_persona_autoriza or $nro_documento) {
    $encabezado_observaciones = 'Observaciones: ';
    if ($descripcion)
      $encabezado_observaciones .= $descripcion . ', ';
    if ($nombre_persona_autoriza)
      $encabezado_observaciones .= 'Persona Autoriza [' . $nombre_persona_autoriza . '] ';
    if ($nro_documento)
      $encabezado_observaciones .= 'No. Documento Autorización [' . $nro_documento . ']';
  }

  $encabezado_arreglo = array(
    'transaccion' => $transaccion_encabezado['id_doc_insumo'],
    'fecha' => $transaccion_encabezado['fecha'],
  );

  foreach ($insumos as $insumo) {

    $chk1 = '';
    $cantidad = 0;

    if (
      $insumo['id_tipo_movimiento'] == 1 || $insumo['id_tipo_movimiento'] == 4
      || $insumo['id_tipo_movimiento'] == 10
    ) {
      $cantidad = $insumo['cantidad_devuelta'];
    } else
    if (
      $insumo['id_tipo_movimiento'] == 2 || $insumo['id_tipo_movimiento'] == 3
      || $insumo['id_tipo_movimiento'] == 7
    ) {
      $cantidad = $insumo['cantidad_entregada'];
    }

    //$chk1.='<label class="css-input switch switch-danger"><input name="check" data-id="" type="checkbox"/><span></span></label>';
    //$movimiento = insumo::get_tipo_movimiento_by_id($m['id_tipo_movimiento']);
    $tipo_ = '';
    if ($bodega['descripcion_corta'] == 'Moviles') {
      $tipo_ = $insumo['codigo_inventarios'];
    } else {
      $tipo_ = $insumo['tipocas'];
    }
    $sub_array = array(
      'transaccion' => $insumo['id_doc_insumo'],
      'fecha' => $insumo['fecha'],
      'direccion' => $direccion['descripcion'] ?? 'S/D',
      'encargado' => $encargado_nombre,
      'bodega' => $bodega['descripcion_corta'],
      'observaciones' => $encabezado_observaciones,
      'descripcion' => $descripcion,
      'persona_autoriza' => $nombre_persona_autoriza,
      'nro_documento_autoriza' => $nro_documento,
      'hora' => '',
      'empleado' => $empleado_primario,
      'empleado2' => $empleado_secundario,
      'movimiento' => $movimiento,
      'tipo' => $tipo_,
      'marca' => $insumo['marca'],
      'modelo' => $insumo['modelo'],
      'serie' => $insumo['numero_serie'],
      'cantidad' => number_format($cantidad, 0, ".", ",")/*,
      'estante'=>'',//$m['id_bodega_insumo'],
      'numero_serie'=>$m['numero_serie'],
      'cod_inventario'=>'',
      'propietario'=>'',
      'movimiento'=>$movimiento['descripcion'],
      'cantidad'=>number_format($m['cantidad_entregada'], 0, ".", ","),
      'cantidad_devuelta'=>number_format($m['cantidad_devuelta'], 0, ".", ","),
      'accion'=>$chk1*/
    );
    $data[] = $sub_array;
  }

  echo json_encode($data);

else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
