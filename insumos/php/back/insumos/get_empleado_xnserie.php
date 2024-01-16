<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    // include_once '../functions.php';
    // include_once '../../../../empleados/php/back/functions.php';
    $numero_serie=$_POST['numero_serie'];

    $numero_serie = str_replace(' ', '', $numero_serie);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP(1) d.id_persona
    FROM inv_movimiento_detalle a
    LEFT JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
    LEFT JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
    LEFT JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
    LEFT JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
    LEFT JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo
    LEFT JOIN inv_tipo_movimiento g on f.id_tipo_movimiento = g.id_tipo_movimiento
    
    WHERE numero_serie LIKE '%".$numero_serie."%' AND f.id_estado_documento=4348 AND f.id_bodega_insumo=3552 AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada AND f.id_estado_documento=4348 AND f.id_bodega_insumo=3552 AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada
    
    ORDER BY a.id_doc_insumo DESC";
    $p = $pdo->prepare($sql);
    $p->execute();// 65 es el id de aplicaciones
    $accesos = $p->fetch();
    Database::disconnect_sqlsrv();
    echo ($accesos["id_persona"]);
    

    // $clase1 = new insumo;
    // $clase2 = new empleado;
    // $id_persona =  $clase1-> get_usuarios_xnserie($numero_serie);
    // $results =  $id_persona[0];
    // // $datos = $clase1->get_acceso_bodega_usuario($_SESSION['id_persona']);
    // foreach($datos AS $d){
    //   $bodega = $d['id_bodega_insumo'];
    //   $bodega_nom = $d['descripcion_corta'];

    //   $moves = array();
    //   $moves = $clase1->get_all_productos_asignados_actual_by_bodega($id_persona,$bodega);
    //   $data = array();

    //   foreach ($moves as $m){
    //     $cantidad='';
    //     $valor=$m['cantidad_entregada']-$m['cantidad_devuelta'];
    //     if($m['tipo']==0){

    //       $cantidad.='<span id="message_'.$m['id_prod_insumo_detalle'].'" class="bar"></span>';
    //       $cantidad.="<input id='txt_".$m['id_doc_insumo'].'-'.$m['id_prod_insumo_detalle']."' class='cantidad_ form-control input-sm text-center' required min='1'value='".$valor."'></input>";
    //     }
    //     else{
    //       $cantidad.='<span id="message_'.$m['id_prod_insumo_detalle'].'" class="bar"></span>';
    //       $cantidad.="<input id='txt_".$m['id_doc_insumo'].'-'.$m['id_prod_insumo_detalle']."' class='cantidad_ form-control input-sm text-center'  value='1' required min='1' disabled></input>";
    //     }

    //     $chk1='';
    //     $chk1.='<label class="css-input switch switch-success"><input name="check" id="'.$m['id_doc_insumo'].'" data-id="'.$m['id_prod_insumo_detalle'].'" data-name="'.$valor.'" data-pk="'.$m['id_tipo_movimiento'].'" type="checkbox"/><span></span></label>';

    //     $nombre_en_caliente='';
    //     if($m['id_persona_diferente']!=0){
    //       $p=0;
    //       if($m['id_persona_diferente']==$id_persona){
    //         $p = $clase2->get_empleado_by_id_ficha($m['id_persona']);
    //       }else{
    //         $p = $clase2->get_empleado_by_id_ficha($m['id_persona_diferente']);
    //       }

    //       $nombre_en_caliente = 'Asignado también a: '. $p['primer_nombre'].' '.$p['segundo_nombre'].' '.$p['tercer_nombre'].' '.$p['primer_apellido'].' '.$p['segundo_apellido'].' '.$p['tercer_apellido'].' ['.$p['id_persona'].']';
    //     }


    //     $sub_array = array(
    //       'DT_RowId'=>$m['id_doc_insumo'].'-'.$m['id_prod_insumo_detalle'],
    //       'descripcion'=>$m['descripcion'],
    //       'anotaciones'=>$m['fecha'].' '.$nombre_en_caliente,
    //       //'estante'=>'',//$m['id_bodega_insumo'],
    //       'transaccion'=>$m['id_doc_insumo'],
    //       'numero_serie'=>$m['numero_serie'],
    //       'cod_inventario'=>'',
    //       'propietario'=>'',
    //       'movimiento'=>$m['movimiento_tipo'],
    //       'cantidad_entregada'=>number_format($m['cantidad_entregada'], 0, ".", ","),
    //       'cantidad_devuelta'=>number_format($m['cantidad_devuelta'], 0, ".", ","),
    //       'cantidad'=>$cantidad,
    //       'accion'=>$chk1
    //     );

    //     $data[]=$sub_array;
    //   }
    // }


  // $results = array(
  //   "sEcho" => 1,
  //   "iTotalRecords" => count($data),
  //   "iTotalDisplayRecords" => count($data),
  //   "aaData"=>$data);

    // echo $results;


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
