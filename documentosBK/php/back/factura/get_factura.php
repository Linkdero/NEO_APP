<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $orden = $_GET['orden_id'];

    $clased = new documento;
    $facturas = array();
    $data = array();

    $f = array();
    if($u->hasPrivilege(302) || $u2->accesoModulo(7851)){
      $f = $clased->get_factura_by_id($orden);
    }

      $tipo = 'No definido';
      $modalidad_pago = '';
      $estado_pago = 0;
      $f_cheque = false;
      $f_clase_proceso = false;
      $f_orden = false;
      $f_cur = false;
      $f_curl = false;
      $modalidad_pago = true;
      if($f['tipo_pago']==1){
        $tipo = 'Baja Cuantía';
        if($f['forma_de_pago'] == 3){
          $modalidad_pago = false;
        }
         else if($f['forma_de_pago']==1){
          $modalidad_pago = (!empty($f['forma_de_pago'])) ? false : true;
          $f_cheque = (empty($f['cheque'])) ? true : false;
          $estado_pago = 2; // si ya tiene asignado
        }else{
          $modalidad_pago = (!empty($f['forma_de_pago'])) ? false : true;
          $f_clase_proceso = (empty($f['clase_proceso']) && !empty($f['forma_de_pago'])) ? true : false;
          $f_orden = (!empty($f['clase_proceso']) && empty($f['nro_orden'])) ? true : false;
          $f_cur = (!empty($f['nro_orden']) && empty($f['cur'])) ? true : false;
          $f_curl = (!empty($f['nro_orden']) && !empty($f['cur']) && empty($f['cur_devengado'])) ? true : false;
          $estado_pago = 3;
        }
      }else if($f['tipo_pago']==2){
        $tipo = 'Acreditamiento';
        $f_clase_proceso = (empty($f['clase_proceso']) && !empty($f['forma_de_pago'])) ? true : false;
        $f_orden = (!empty($f['clase_proceso']) && empty($f['nro_orden'])) ? true : false;
        $f_cur = (!empty($f['nro_orden']) && empty($f['cur'])) ? true : false;
        $f_curl = (!empty($f['nro_orden']) && !empty($f['cur']) && empty($f['cur_devengado'])) ? true : false;
        $modalidad_pago = (!empty($f['forma_de_pago'])) ? false : true;
        if($f['forma_de_pago']==2){
          $estado_pago = 4;
        }
      }else{
        $estado_pago = 1;
      }

      $estado_id = $f['status'];
      $estado = '';
      if($f['status'] == 5){
        $estado = 'Anulada';
      }
      $estado_factura = $clased->get_estado_by_factura($orden);

      $data = array(
        'DT_RowId'=>$f['orden_compra_id'],
        'nro_orden'=>(!empty($f['nro_orden']))?$f['nro_orden']:'Sin número',
        'tipo'=>$tipo,
        'tipo_pago'=>(!empty($f['tipo_pago']))?$f['tipo_pago']:0,
        'ped_tra'=>(!empty($f['ped_tra']))?$f['ped_tra']:0,
        'factura_fecha'=>fecha_dmy($f['factura_fecha']),
        'factura_serie'=>$f['factura_serie'],
        'factura_num'=>$f['factura_num'],
        'proveedor_nit'=>$f['proveedor_id'],
        'proveedor'=>$f['Prov_nom'],
        'factura_total'=>$f['factura_total'],
        'ped_num'=>$f['Ped_num'],
        'nog'=>(!empty($f['nog']))?$f['nog']:0,
        'orden'=>(!empty($f['nro_orden']))?$f['nro_orden']:0,
        'cur'=>(!empty($f['cur']))?$f['cur']:'Sin valor',
        'cur_devengado'=>(!empty($f['cur_devengado']))?$f['cur_devengado']:'Sin valor',
        'regimen_proveedor'=>(!empty($f['regimen_proveedor']))?$f['regimen_proveedor']:'Sin valor',
        'cheque'=>(!empty($f['cheque']))?$f['cheque']:0,
        'forma_de_pago'=>$f['forma_de_pago'],
        'estado_pago'=>$estado_pago,
        'estado'=>$estado,
        'estado_id'=>$estado_id,
        'f_cheque'=>$f_cheque,
        'f_clase_proceso'=>$f_clase_proceso,
        'f_orden'=>$f_orden,
        'f_cur'=>$f_cur,
        'f_curl'=>$f_curl,
        'modalidad_pago'=>$modalidad_pago,
        'clase_proceso'=>$f['clase_proceso'],
        'forma_pago_text'=>$f['forma_pago_text'],
        'tecnico'=>$f['id_tecnico'],
        'tecnico_au'=>($f['id_tecnico'] == $_SESSION['id_persona']) ? true : false,
        'estado_factura'=>$estado_factura['id_seguimiento']
        //'tecnico'=>$f['tecnico']

      );




    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
