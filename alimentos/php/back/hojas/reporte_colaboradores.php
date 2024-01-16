<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';
    $ini=date('Y-m-d', strtotime($_POST['ini']));
    $fin=date('Y-m-d', strtotime($_POST['fin']));
    

    $clase = new alimentos;
    $array_dir = array();
    $array_dir = $clase->get_dir_empleado($_SESSION['id_persona']);
    $direccion = $array_dir['id_dirf'] ;
    $tipo_user = $array_dir['tipo_user'];
    $dir_id=0;
    $comedor = $_POST['comedor'];

    $empleados = array();
    if($tipo_user==1118){  // administrador
      $empleados = $clase->get_empleados_asignacionesGen();
      $dir_id=$_POST['direccion'];
    }else{        // operador
      $empleados = $clase->get_empleados_asignacionesxDir($direccion);
      $dir_id=$direccion;
    }

     //$row_puerta = visita::get_data_by_ip($_SERVER["REMOTE_ADDR"]);
     $comidas = array();
     $comidas = $clase->get_repo_alimentos_por_colaborador($ini,$fin,$dir_id,$comedor);

     $data = array();
     $d=0;
     $a=0;
     $c=0;
    foreach ($comidas as $comida){ 
      //if($comida['de_oficina']){

        $d+=$comida['desayuno'];
        $a+=$comida['almuerzo'];
        $c+=$comida['cena'];

        $sub_array = array(
          'direccion' => $comida['direccion'],
          'nombre' => $comida['nombre'],
          'desayuno' => $comida['desayuno'],
          'almuerzo' => $comida['almuerzo'],
          'cena' => $comida['cena'],
        );
        $data[] = $sub_array;
      //}
      
    }

    $sub_array = array(
        'direccion' => '',
          'nombre' => 'Total:',
          'desayuno' => $d,
          'almuerzo' => $a,
          'cena' => $c
    );

    $data[] = $sub_array;

  

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
