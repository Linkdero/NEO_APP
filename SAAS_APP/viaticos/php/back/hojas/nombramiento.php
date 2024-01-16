<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $id_nombramiento=$_POST['id_nombramiento'];



  $nombramientos = viaticos::get_nombramiento_by_id($id_nombramiento);
  $data = array();

  foreach ($nombramientos as $n){
    $fecha1 = new DateTime(date('Y-m-d', strtotime($n['fecha_salida'])).' '.$n['hora_i']);//fecha inicial
    $fecha2 = new DateTime(date('Y-m-d', strtotime($n['fecha_regreso'])).' '.$n['hora_f']);//fecha de cierre

    $intervalo = $fecha1->diff($fecha2);

    //echo $intervalo->format('%Y años %m meses %d days %H horas %i minutos%s segundos');
    $d='días';
    $d_extranjero;
    $dia = $intervalo->format('%d');
    $hora = $intervalo->format('%H');

    if($dia==1){
    	$d='día';
    }
    $beneficios;
    if($n['alimentacion']!='' && $n['hospedaje']!=''){
      $beneficios=$n['alimentacion'].' y '. $n['hospedaje'];
    }else{
      $beneficios=$n['alimentacion'].''. $n['hospedaje'];
    }

    $diferencia = $intervalo->format('%d '.$d.' %H horas %i minutos ');
    $sub_array = array(
      'correlativo'=>$n['vt_nombramiento'],
      'nombramiento'=>$n['nombramiento_direccion'],
      'direccion'=>$n['descripcion'],
      'fecha'=>'Guatemala '.fechaCastellano($n['fecha_viatico']),
      'fecha_ini'=>fechaCastellano($n['fecha_salida']),
      'fecha_fin'=>fechaCastellano($n['fecha_regreso']),
      'hora_ini'=>$n['hora_salida'],
      'hora_fin'=>$n['hora_regreso'],
      'duracion'=>$diferencia,
      'beneficios'=>$beneficios,
      'empleado'=>(empty($n['nombre_completo']))?'':$n['nombre_completo'],
      'funcionario'=>$n['nombre_funcionario'],
      'lugar'=>$n['nombre'],
      'motivo'=>$n['motivo']
    );
    $data[]=$sub_array;
  }

  $output = array(
        "data"    => $data
      );
//echo $output;
echo json_encode($output);



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
