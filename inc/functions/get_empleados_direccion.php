<?php
include_once '../functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');


    $empleados = array();
    $direccion ='';
    if(isset($_GET['id_direccion'])){
      $direccion=$_GET['id_direccion'];
    }
    $data = array();

    $sub_array = array(
      'id_departamento'=>'',
      'nombre'=>'-- Seleccionar -- '
    );
    $data[] = $sub_array;

    if($_SESSION['id_persona']== 5449){
      // $sub_array = array(
      //   'DT_RowId'=>5449,
      //   'id_persona'=>5449,
      //   'empleado'=>'VLADIMIR AMILCAR LORENTE DIONICIO',
      // );
      // $data[] = $sub_array;
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql2 = "SELECT DISTINCT a.id_persona, a.nombre as nombre_completo, 
               a.id_dirapy as id_direccion_funcional
               FROM dbo.xxx_rrhh_Ficha A
               WHERE a.id_persona in(5449,6627,1151) 
               ORDER BY A.nombre ";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute(array());
      $empleados = $stmt2->fetchAll();
      Database::disconnect_sqlsrv();
      foreach ($empleados as $e){
        $sub_array = array(
          'DT_RowId'=>$e['id_persona'],
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
        );
        $data[] = $sub_array;
      }
    }else{
      //inicio
      if($direccion == 4){
        $empleados = get_empleados_por_direccion(653);

        foreach ($empleados as $e){
          $sub_array = array(
            'DT_RowId'=>$e['id_persona'],
            'id_persona'=>$e['id_persona'],
            'empleado'=>$e['nombre_completo'],
          );
          $data[] = $sub_array;
        }

        $empleados = get_empleados_por_direccion(4);

        foreach ($empleados as $e){
          $sub_array = array(
            'DT_RowId'=>$e['id_persona'],
            'id_persona'=>$e['id_persona'],
            'empleado'=>$e['nombre_completo'],
          );
          $data[] = $sub_array;
        }
      }else{

        $empleados = get_empleados_por_direccion($direccion);

        foreach ($empleados as $e){
          $sub_array = array(
            'DT_RowId'=>$e['id_persona'],
            'id_persona'=>$e['id_persona'],
            'empleado'=>$e['nombre_completo'],
          );
          $data[] = $sub_array;
        }
      }
      //fin
    }







  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
