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
    $tesoreria_au = (evaluar_flag($_SESSION['id_persona'],8017,324,'flag_autoriza')==1) ? true : false;
    foreach ($ordenes as $orden) {
      // code...
      if(!empty($orden['clase_proceso'])){
        $proceso = '';
        if($orden['clase_proceso'] == 1){
          $proceso = "Nro. de Orden";
        }else{
          $proceso = "CYD";
        }

        $tipo = 0;
        if(!empty($orden['cur']) && empty($orden['cur_devengado'])){
          $tipo = 1;
        }else if(!empty($orden['cur']) && !empty($orden['cur_devengado'])){
          $tipo = 2;
        }
        $accion = '<div class="btn-group">';
        //$accion = '<span class="btn btn-sm btn-soft-info"><i class="fa fa-pencil-alt"></i></span>';
        if($tesoreria_au == true){
          $accion = '<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto" href="documentos/php/front/factura/orden_operar.php?nro_orden='.$orden['nro_orden'].'&tipo='.$tipo.'&clase_proceso='.$orden['clase_proceso'].'"><i class="fa fa-pencil-alt"></i></span>';
        }
        $accion .= '<div class="btn-group">';


        $sub_array = array(
          'clase_proceso'=>(!empty($orden['clase_proceso'])) ? $proceso : '',
          'nro_orden'=>($orden['clase_proceso'] == 1) ? $orden['nro_orden'] : '',
          'cyd'=>($orden['clase_proceso'] == 2) ? $orden['nro_orden'] : '',
          'cur_c'=>(!empty($orden['cur'])) ? $orden['cur'] : '',
          'cur_d'=>(!empty($orden['cur_devengado'])) ? $orden['cur_devengado'] : '',
          'total'=>$orden['total'],
          'accion'=>$accion

        );
        $data[] = $sub_array;
      }

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
