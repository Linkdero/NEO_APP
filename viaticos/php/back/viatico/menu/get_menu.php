<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    $correlativo=$_GET['correlativo'];
    include_once '../../functions.php';
    $clase = new viaticos;
    $opciones= $clase->get_opciones_menu($correlativo);

    $data = array();

    $data = array(
      'id_pais'=>$opciones['id_pais'],

    );

    echo json_encode($data);
  }
}else{
    header("Location: index.php");
}
?>
