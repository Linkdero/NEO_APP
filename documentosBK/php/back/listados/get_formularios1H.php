<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';

  $results = array();
  $documentos = documento::get_formularios_1h();
  $data = array();
  foreach ($documentos as $f){
    $sub_array = array(
      'Ent_id'=>$f['Ent_id'],
      'Env_tra'=>$f['Env_tra'],
      'Env_num'=>$f['Env_num'],
      'Ser_ser'=>$f['Ser_ser'],
      'Prov_id'=>$f['Prov_id'],
      'Prov_nom'=>$f['Prov_nom'],
      'Bod_id'=>$f['Bod_id'],
      'Env_tot'=>number_format($f['Env_tot'],2,'.',','),
      'Bod_nom'=>$f['Bod_nom'],
      'Tdoc_id'=>$f['Tdoc_id'],
      'Tdoc_mov'=>$f['Tdoc_mov'],
      'Fh_nro'=>$f['Fh_nro'],
      'Fh_ser'=>$f['Fh_ser'],
      'Fh_fec'=>date('d-m-Y', strtotime($f['Fh_fec'])),
      'Fh_prg'=>$f['Fh_prg'],
      'Fh_imp'=>$f['Fh_imp'],
      'direccion'=>$f['direccion'],
      'usu_id'=>$f['usu_id'],
      'Env_fec'=>$f['Env_fec'],
      'accion'=>'<span class="btn btn-soft-info btn-sm" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/formularios1h/formulario_detalle.php?env_tra='.$f['Env_tra'].'&formulario='.$f['Fh_nro'].'">
        <i class="fa fa-pen"></i> 
      </span>'
    );
    $data[] = $sub_array;


  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
