<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    include_once '../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $plazas = array();
    $plazas=plaza::get_plazas_por_empleado($id_persona,NULL);
    $data = array();

    $flag=0;
    if(evaluar_flag($_SESSION['id_persona'],1163,175,'flag_actualizar')==1){
      $flag=1;
    }

    foreach($plazas as $e){
      $f_r='';
      if($e['fecha_efectiva_resicion']!=''){
        $f_r=fecha_dmy($e['fecha_efectiva_resicion']);
      }
      $accion='<div class="btn-group">';
      $accion.='<button class="btn-sm btn btn-personalizado outline"';
      if($e['id_status']==891 || $e['id_status']==5610){
        if(usuarioPrivilegiado()->hasPrivilege(175)){
          $accion.='onclick="cargar_remocion_empleado('.$e['id_plaza'].','.$e['id_asignacion'].',1)"';
        }else{
        $accion.="disabled";
        }
      }else{
        $accion.='disabled';
      }
      $accion.='><i class="fa fa-times"></i></button>';
      $accion.='<button class="btn-sm btn btn-personalizado outline" onclick="cargar_remocion_empleado('.$e['id_plaza'].','.$e['id_asignacion'].',2)"><i class="fa fa-eye"></i></button>';
      $accion.='</div>';
      $sub_array = array(
        //'plaza'=>$e['partida_presupuestaria'],
        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'id_plaza'=>$e['id_plaza'],
        'id_asignacion'=>$e['id_asignacion'],
        'cod_plaza'=>$e['cod_plaza'],
        'partida'=>$e['partida_presupuestaria'],
        'plaza'=>$e['id_plaza'],
        'puesto'=>$e['puesto'],
        'inicio'=>fecha_dmy($e['fecha_toma_posesion']),
        'final'=>$f_r,
        'sueldo'=>$e['SUELDO'],
        'status'=>$e['id_status'],
        'estado'=>$e['estado'],//($e['id_status']==891)?'<span class="text-info">'.$e['estado'].'</span>':'<span class="text-danger">'.$e['estado'].'</span>',
        'accion'=>$accion,
        'remocion_reingreso'=>(!empty($e['id_remocion_reingreso'])) ? 'Remoción - reingreso' : '',
        'flag'=>$flag

        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $data[]=$sub_array;
    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
