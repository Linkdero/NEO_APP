<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
    $direcciones = viaticos::get_direcciones();
    if($direcciones["status"] == 200){
        $response = "";
        $response .="<option value='0'>TODOS</option>";
        foreach($direcciones["data"] as $d){
            $response .="<option value=".$d['id_direccion'].">".$d['descripcion']."</option>";
        }
    }else{
        $response = $direcciones["msg"];
    }
    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
