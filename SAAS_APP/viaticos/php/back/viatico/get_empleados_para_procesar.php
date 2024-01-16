<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $empleados = array();
    $id_persona=$_GET['id_persona'];
    /*$u=usuarioPrivilegiado_acceso();
    if (isset($u) && $u->accesoModulo(7851))*/

    $clase = new empleado;
    //$e = $clase->get_empleados_in_id_ficha($id_persona);
    $parametros=str_replace("'","()",$id_persona);

    $str = ltrim($parametros, ',');
    if(!empty($str)){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_persona, primer_nombre, segundo_nombre, tercer_nombre,
                     primer_apellido, segundo_apellido, tercer_apellido
              FROM rrhh_persona
              WHERE id_persona in ($str)
              ORDER BY id_persona ASC";
              //echo $sql;
      $p = $pdo->prepare($sql);
      $p->execute(array());


      $empleados = $p->fetchAll();
      Database::disconnect_sqlsrv();
      $data = array();

      foreach ($empleados as $e){
        $sub_array = array(
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
        );
        $data[] = $sub_array;
      }
    }else{

    }


  echo json_encode($data);
  //echo $sql;

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
