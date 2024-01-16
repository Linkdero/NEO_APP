<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';

  $transaccion=$_POST['id_doc_insumo'];
  $clase1 = new insumo;
  $clase2 = new empleado;
  $insumos = array();
  $insumos = $clase1->get_insumos_by_transaccion($transaccion);
  $data = array();

  $datos=$clase1->get_empleado_by_transaccion($transaccion);

  $id_persona=$datos['id_persona'];
  $id_persona_diferente=$datos['id_persona_diferente'];

  $e = array();
  $e = $clase2->get_empleado_by_id_ficha($id_persona);
  $e2 = $clase2->get_empleado_by_id_ficha($id_persona_diferente);
  $direccion = empleado::get_direcciones_saas_by_id($datos['id_persona_direccion_recibe']);
  //$tipos = insumo::get_tipos_movimientos($tipo);//Ingreso a Bodega
  $data = array();

  $nombre=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.
      $e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'].' ['.$datos['id_persona'].']';

  $nombre3 =' - Ninguno -';
  if($id_persona_diferente!=0){
    $nombre3=$e2['primer_nombre'].' '.$e2['segundo_nombre'].' '.$e2['tercer_nombre'].' '.
        $e2['primer_apellido'].' '.$e2['segundo_apellido'].' '.$e2['tercer_apellido'].' ['.$datos['id_persona_diferente'].']';
  }



  $movimiento=$datos['tipo_movimiento'];


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT id_persona_entrega,id_bodega_insumo
          FROM inv_movimiento_encabezado
          WHERE id_doc_insumo=?
         ";
  $p = $pdo->prepare($sql);
  $p->execute(array($transaccion));// 65 es el id de aplicaciones
  $encargado = $p->fetch();

  $sql2 = "SELECT b.descripcion_corta
          FROM inv_movimiento_encabezado a
          INNER JOIN tbl_catalogo_detalle b ON a.id_bodega_insumo=b.id_item
          WHERE a.id_doc_insumo=?
         ";
  $p2 = $pdo->prepare($sql2);
  $p2->execute(array($transaccion));// 65 es el id de aplicaciones
  $bodega = $p2->fetch();

  Database::disconnect_sqlsrv();

  $e2 = empleado::get_empleado_by_id_ficha($encargado['id_persona_entrega']);
  $nombre2=$e2['primer_nombre'].' '.
      $e2['primer_apellido'];

  foreach ($insumos as $insumo){


    $chk1='';

    $cantidad=0;

    if($insumo['id_tipo_movimiento']==1 || $insumo['id_tipo_movimiento']==4
    || $insumo['id_tipo_movimiento']==10){
      $cantidad=$insumo['cantidad_devuelta'];
    }else
    if($insumo['id_tipo_movimiento']==2 || $insumo['id_tipo_movimiento']==3
    || $insumo['id_tipo_movimiento']==7){
      $cantidad=$insumo['cantidad_entregada'];
    }

    //$chk1.='<label class="css-input switch switch-danger"><input name="check" data-id="" type="checkbox"/><span></span></label>';
    //$movimiento = insumo::get_tipo_movimiento_by_id($m['id_tipo_movimiento']);
    $tipo_='';
    if($bodega['descripcion_corta']=='Moviles'){
      $tipo_=$insumo['codigo_inventarios'];
    }else{
      $tipo_=$insumo['descripcion_corta'];
    }
    $sub_array = array(
      'transaccion'=>$insumo['id_doc_insumo'],
      'fecha'=>$insumo['fecha'],
      'empleado'=>$nombre,
      'direccion'=>$direccion['descripcion'],
      'encargado'=>$nombre2,
      'bodega'=>$bodega['descripcion_corta'],
      'hora'=>'',
      'empleado'=>$nombre,
      'empleado2'=>$nombre3,
      //'direccion'=>$direccion,
      'movimiento'=>$movimiento,
      'tipo'=>$tipo_,
      'marca'=>$insumo['marca'],
      'modelo'=>$insumo['modelo'],
      'serie'=>$insumo['numero_serie'],
      'cantidad'=>number_format($cantidad, 0, ".", ",")/*,
      'estante'=>'',//$m['id_bodega_insumo'],
      'numero_serie'=>$m['numero_serie'],
      'cod_inventario'=>'',
      'propietario'=>'',
      'movimiento'=>$movimiento['descripcion'],
      'cantidad'=>number_format($m['cantidad_entregada'], 0, ".", ","),
      'cantidad_devuelta'=>number_format($m['cantidad_devuelta'], 0, ".", ","),
      'accion'=>$chk1*/
    );
    $data[]=$sub_array;
  }

  echo json_encode($data);




else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
