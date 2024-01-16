<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    //include_once '../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $tipo = '';//$_POST['tipo'];
    $year = '';//$_POST['year'];

    $clased = new documento;

    $data = array();

    $oficios = array();
    $oficios = $clased->getOficiosFacturasByCompras($tipo,$year);
    //$presupuesto_au = (evaluar_flag($_SESSION['id_persona'],8017,323,'flag_autoriza')==1) ? true : false;
    foreach ($oficios AS $ofi) {
      $sub_array = array(
        'DT_RowId'=>$ofi['oficioId'],
        'oficionNum'=>$ofi['oficionNum'],
        'oficioFecha'=>fecha_dmy($ofi['oficioFecha']),
        'oficioDescripcion'=>$ofi['oficioDescripcion'],
        'operadoPor'=>$ofi['operadoPor'],
        'operadoEn'=>$ofi['operadoEn'],
        'direccion'=>$ofi['direccion'],
        'estado'=>$ofi['estado'],
        'persona_recibe'=>$ofi['fnombre'].' '.$ofi['snombre'].' '.$ofi['tnombre'].' '.$ofi['fapellido'].' '.$ofi['sapellido'].' '.$ofi['tapellido'],
        'movimiento'=>$ofi['movimiento'],
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
