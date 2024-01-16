<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    //include_once '../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $tipo = $_POST['tipo'];

    $clased = new documento;

    $data = array();

    $ordenes = array();
    $ordenes = $clased->get_ordenes_group_pendientes($tipo);
    $presupuesto_au = (evaluar_flag($_SESSION['id_persona'],8017,323,'flag_autoriza')==1) ? true : false;
    foreach ($ordenes as $orden) {
      // code...
      //if(!empty($orden['id_tipo_pago'])){
        $proceso = '';
        if($orden['id_tipo_pago'] == 1){
          $proceso = "Nro. de Orden";
        }else{
          $proceso = "CYD";
        }

        $tipo = 0;
        if(!empty($orden['cur_compromiso']) && empty($orden['cur_devengado'])){
          $tipo = 1;
        }else if(!empty($orden['cur_compromiso']) && !empty($orden['cur_devengado'])){
          $tipo = 2;
        }
        $accion = '<div class="btn-group">';
        //$accion = '<span class="btn btn-sm btn-soft-info"><i class="fa fa-pencil-alt"></i></span>';
        if($presupuesto_au == true){
          $accion = '<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto" href="documentos/php/front/factura/orden_operar.php?nro_orden=0&tipo='.$tipo.'&clase_proceso='.$orden['id_tipo_pago'].'&id_pago='.$orden['id_pago'].'"><i class="fa fa-pencil-alt"></i></span>';
        }
        $accion .= '<div class="btn-group">';


        $sub_array = array(
          'DT_RowId'=>$orden['id_pago'],
          'clase_proceso'=>$proceso,
          'nro_orden'=>($orden['id_tipo_pago'] == 1) ? $orden['nro_registro'] : '',
          'cyd'=>($orden['id_tipo_pago'] == 2) ? $orden['nro_registro'] : '',
          'cur_c'=>(!empty($orden['cur_compromiso'])) ? $orden['cur_compromiso'] : '',
          'cur_d'=>(!empty($orden['cur_devengado'])) ? $orden['cur_devengado'] : '',
          'total'=>$orden['total'],
          'accion'=>$accion

        );
        $data[] = $sub_array;
      //}

    }
    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    echo json_encode($results);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
