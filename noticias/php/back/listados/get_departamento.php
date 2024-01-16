<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
    $departamentos = noticia::get_departamentos();
    if($departamentos["status"] == 200){
        $response = "";
        foreach($departamentos["data"] as $departamento){
            $response .="<option value=".$departamento['id_departamento'].">".$departamento['nombre']."</option>";
        }
    }else{
        $response = $departamentos["msg"];
    }
    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
