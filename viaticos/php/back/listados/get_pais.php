<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
    $paises = viaticos::get_paises();
    if($paises["status"] == 200){
        $response = "";
        foreach($paises["data"] as $pais){
            $response .="<option value=".$pais['id_pais'].">".$pais['nombre']."</option>";
        }
    }else{
        $response = $pais["msg"];
    }
    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
