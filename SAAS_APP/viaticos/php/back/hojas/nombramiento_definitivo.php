<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $id_nombramiento=$_POST['id_nombramiento'];
  $dia=$_POST['dia'];
  $mes="'".$_POST['mes']."'";
  $year=$_POST['year'];

  $clase1 = new viaticos;
  $nombramientos = $clase1->get_nombramiento_definitivo_by_id($dia,$mes,$year,$id_nombramiento);
  $data = array();
  $data_f1 = array();
  $data_f2 = array();
  $data_f3 = array();
  $data_f4 = array();
  $data_h1 = array();
  $data_h2 = array();
  $data_h3 = array();
  $data_h4 = array();

  foreach($nombramientos as $e){
    $data_f1 []= (date('d-m-Y', strtotime($e['fecha_salida'])));
    $data_f4 []= (date('d-m-Y', strtotime($e['fecha_regreso'])));

    $data_h1 []= ($e['hora_i']);
    $data_h4 []= ($e['hora_f']);
    $data_h2 []=($e['hora_salida']);
    $data_h3 []=($e['hora_regreso']);
  }

  foreach ($nombramientos as $n){
    $fecha1 = new DateTime(date('Y-m-d', strtotime(min($data_f1))).' '.min($data_h1));//fecha inicial
    $fecha2 = new DateTime(date('Y-m-d', strtotime(max($data_f4))).' '.max($data_h4));//fecha de cierre

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
    $lugar=$n['nombre'];
    $dep='';
    if($n['descripcion_lugar']==1){
      $valor = $clase1->get_historial_viatico($n['vt_nombramiento']);
      //$historial = $valor['val_anterior'];
      $va = json_decode($valor['val_nuevo'], true);
      $keys = array_keys($va);

      $deptos=$clase1->get_departamentos($n['id_pais']);
      $munis=$clase1->get_municipios($va['id_departamento']);
      //$aldeas=$clase1->get_aldeas($va['id_municipio']);

      foreach($munis["data"] as $m){
        $dep.=($va['id_municipio']==$m['id_municipio'])?$m['nombre']:'';
      }
      foreach($deptos["data"] as $d){
        $dep.=($va['id_departamento']==$d['id_departamento'])?'-'.$d['nombre']:'';
      }
      /*foreach($aldeas["data"] as $a){
        $dep.=($va['id_aldea']==$a['id_aldea'])?', '.$a['nombre']:'';
      }*/
      $lugar=$dep.'-'.$n['pais'];
    }

    $sub_array = array(
      'correlativo'=>$n['vt_nombramiento'],
      'nombramiento'=>$n['nombramiento_direccion'],
      'direccion'=>$n['descripcion'],
      'fecha'=>'Guatemala '.$n['dia'].' de '.str_replace("'","",$n['mes']).' de '.$n['anio'],//.fechaCastellano($n['anio'].'-'.$n['mes'].'-'.$n['dia']),
      'fecha_ini'=>fechaCastellano(min($data_f1)),
      'fecha_fin'=>fechaCastellano(max($data_f4)),
      'hora_ini'=>min($data_h2),//$n['hora_salida'],
      'hora_fin'=>max($data_h3),//$n['hora_regreso'],
      'duracion'=>$diferencia,
      'beneficios'=>$beneficios,
      'empleado'=>(empty($n['nombre_completo']))?'':$n['nombre_completo'],
      'funcionario'=>$n['nombre_funcionario'],
      'lugar'=>$lugar,
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
