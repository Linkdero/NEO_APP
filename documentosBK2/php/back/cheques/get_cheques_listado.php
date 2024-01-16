<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';
  $results = array();
  $documentos = documento::get_cheques_all();
  $data = array();
  foreach ($documentos as $f) {
    $sub_array = array(
      'id_cheque' => $f['id_cheque'],
      'resolucion' => $f['resolucion'],
      'fecha_res' => $f['fecha_res'],
      'nro_cheque' => $f['nro_cheque'],
      'monto' => $f['monto'],
      'id_persona' => $f['id_persona'],
      'vt_nombramiento' => $f['vt_nombramiento'],
      'orden_compra_id' => $f['orden_compra_id'],
      'accion' => '<span class="btn btn-soft-info btn-sm" data-toggle="modal" data-target="#modal-remoto-lgg2" href="">
        <i class="fa fa-pen"></i> 
      </span>'
    );
    $data[] = $sub_array;
  }
  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );
  echo json_encode($results);
else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
