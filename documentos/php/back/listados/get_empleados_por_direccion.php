<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../../../viaticos/php/back/functions.php';

    $empleados = array();
    $direccion ='';
    if(isset($_GET['id_direccion'])){
      $direccion=$_GET['id_direccion'];
    }
    $data = array();
    $clase = new viaticos();
    if($direccion == 4){
      $sub_array = array(
        'DT_RowId'=>'',
        'id_persona'=>'',
        'empleado'=>'- Seleccionar -'
      );
      $data[] = $sub_array;
      $empleados = $clase->get_empleados_por_direccion(653);

      foreach ($empleados as $e){
        $sub_array = array(
          'DT_RowId'=>$e['id_persona'],
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
        );
        $data[] = $sub_array;
      }

      $empleados = $clase->get_empleados_por_direccion(4);
      $sub_array = array(
        'DT_RowId'=>'',
        'id_persona'=>'',
        'empleado'=>'- Seleccionar -'
      );
      $data[] = $sub_array;
      foreach ($empleados as $e){
        $sub_array = array(
          'DT_RowId'=>$e['id_persona'],
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
        );
        $data[] = $sub_array;
      }
    }else{
      $sub_array = array(
        'DT_RowId'=>'',
        'id_persona'=>'',
        'empleado'=>'- Seleccionar -'
      );
      $data[] = $sub_array;
      $empleados = $clase->get_empleados_por_direccion($direccion);

      foreach ($empleados as $e){
        $sub_array = array(
          'DT_RowId'=>$e['id_persona'],
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
        );
        $data[] = $sub_array;
      }
    }





  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
