<?php
include_once '../functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../empleados/php/back/functions.php';
  $dir ='';

  $response = array();
  //$dir=(!empty($e['id_dirf']))?$e['id_dirf']:0;
  $clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
  //$depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:0;
  $dir = '';

  if($e['id_subdireccion_funcional'] == 34 || $e['id_subdireccion_funcional'] == 37 || $_SESSION['id_persona'] == 8678 || $_SESSION['id_persona'] == 8753 || $_SESSION['id_persona'] == 6584){
    $dir = 79977;// para unir las 2 subdirecciones
  }else{
    if(!empty($e['id_dirf'])){
      $dir = $e['id_dirf'];
    }else{
      if(!empty($e['id_subsecre'])){
        $e['id_subsecre'];
      }else{
        $dir = $e['id_secre'];
      }
    }
  }

  $data = array();
  $data = array(
    'id_direccion'=>$dir
  );


  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
