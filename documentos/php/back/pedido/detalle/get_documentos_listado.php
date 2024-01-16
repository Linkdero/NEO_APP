<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../../functions.php';
  $ped_tra = $_GET['ped_tra'];

  $response = array();

  $clased = new documento;
  $documentos = $clased->get_documentos_respaldo($ped_tra);
  $state = array(0 => 'Registrado', 1 => 'Activo', 2 => 'Aprobado', 3=>'Rechazado');
  $color = array(0 => 'text-warning', 1 => 'text-info', 2 => 'text-success', 3=>'text-danger');
  $data = array();

  foreach($documentos as $d){
    if(!empty($d['reng_num'])){
      $sub_array = array(
        'ped_tra'=>$d['ped_tra'],
        'reng_num'=>$d['reng_num'],
        'archivo'=>$d['archivo'],
        'id_status'=>$d['id_status'],
        'estado'=>$state[$d['id_status']],
        'color'=>$color[$d['id_status']],
        'subido_por'=>$d['subido_por'],
        'operador'=>$d['primer_nombre'].' '.$d['segundo_nombre'].' '.$d['tercer_nombre'].' '.$d['primer_apellido'].' '.$d['segundo_apellido'].' '.$d['tercer_apellido'],
        'revisador'=>$d['r_primer_nombre'].' '.$d['r_segundo_nombre'].' '.$d['r_tercer_nombre'].' '.$d['r_primer_apellido'].' '.$d['r_segundo_apellido'].' '.$d['r_tercer_apellido'],
        'subido_en'=>$d['subido_en'],
        'descripcion'=>$d['descripcion'],
        'observaciones'=>$d['observaciones'],
      );
      $data[] = $sub_array;
    }
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
