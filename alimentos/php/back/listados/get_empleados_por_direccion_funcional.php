<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $empleados = array();
    $empleados=empleado::get_empleados_por_direccion_funcional();
    $data = array();

    foreach($empleados as $empleado){
      if($empleado['SUELDO'] == "")$empleado['SUELDO'] = 0;
      //$encoded_image = base64_encode($empleado['fotografia']);
      //$Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";
      $sub_array = array(
        //'fotografia'=>$empleado['fotografia'],
        //'DT_RowId'=>$empleado['id_persona'],
        'id_persona' => $empleado['id_persona'],
        'empleado' => $empleado['nombre'],
        'dir_nominal' => $empleado['dir_nominal'],
        'dir_funcional' => $empleado['dir_funcional'],
        'renglon' => $empleado['renglon'],
        'direccion' => $empleado['direccion'],
        'puesto_f' => $empleado['p_funcional'],
        'puesto_n' => $empleado['p_nominal'],
        'fecha_i' => fecha_dmy($empleado['fecha_toma_posesion']),
        'estado' => $empleado['estado'],
        'sueldo' =>  $empleado['SUELDO'],
        'accion' => '<span class="btn btn-sm btn-personalizado outline"><i class="fa fa-check"></i></span>'
        //'empleado' => $empleado['primer_nombre'].' '.$empleado['segundo_nombre'].' '.$empleado['tercer_nombre'].' '.$empleado['primer_apellido'].' '.$empleado['segundo_apellido'].' '.$empleado['tercer_apellido']
      );
      $data[]=$sub_array;
    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($output);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
