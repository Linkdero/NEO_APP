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
    $secre = $array_dir['id_secre'] ;
    $subsecre = $array_dir['id_subsecre'] ;
    $direc = $array_dir['id_direc'] ;
    $tipo_user = $array_dir['tipo_user'];
    $dir_id=0;
    $comedor = $_POST['comedor'];

    $empleados = array();
    if($tipo_user==1118 || usuarioPrivilegiado_acceso()->accesoModulo(8085)){  // administrador
      $empleados = $clase->get_empleados_asignacionesGen();
      $dir_id=$_POST['direccion'];
    }else{        // operador
      $empleados = $clase->get_empleados_asignacionesxDir($secre,$subsecre,$direc);
      $dir_id=$direc;
    }

     //$row_puerta = visita::get_data_by_ip($_SERVER["REMOTE_ADDR"]);
     $comidas = array();
     $comidas = $clase->get_repo_alimentos_por_direccion($ini,$fin,$dir_id,$comedor);

     $data = array();
    foreach ($comidas as $comida){
      //if($comida['de_oficina']){

        $sub_array = array(
          'direccion' => $comida['direccion'],
          'desayuno' => $comida['desayuno'],
          'almuerzo' => $comida['almuerzo'],
          'cena' => $comida['cena'],
        );
        $data[] = $sub_array;
      //}

    }

    createLog(272, 1237, 'APP_ALIMENTOS.dbo.Asignacion_Alimentos','Generando reporte por direcciones del: '. fecha_dmy($ini).' al '.fecha_dmy($fin),'', '');

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
