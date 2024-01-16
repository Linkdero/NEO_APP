
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    $direcciones = array();
    $direcciones = alimentos::get_direcciones();
    $response = "";
    $response.='<option value="0">Todas las Direcciones</option>';
    foreach($direcciones as $direccion){
      $response.="<option value='".$direccion['id_dir']."'>".$direccion['des_dir']."</option>";
    }
    echo $response;
    
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>