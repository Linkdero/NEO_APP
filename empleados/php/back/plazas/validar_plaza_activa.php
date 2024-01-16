<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  include_once '../functions_plaza.php';

  $id_persona = null;

  if(!empty($_GET['id_persona'])){
    $id_persona = $_REQUEST['id_persona'];
  }

  $contrato = plaza::get_empleado_plaza_actual($id_persona);

  if($contrato['emp_estado'] == 891){
    echo 'false';
  }else{
    echo 'true';
  }

else:
    header("Location: ../index.php");
endif;
