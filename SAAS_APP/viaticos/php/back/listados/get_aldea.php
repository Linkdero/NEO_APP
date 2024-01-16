<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
    $id_municipio = $_POST["municipio"];
    $aldeas = viaticos::get_aldeas($id_municipio);
    if($aldeas["status"] == 200){
        $response = "";
        $response .="<option value='0'>Seleccionar</option>";
        foreach($aldeas["data"] as $aldea){
            $response .="<option value=".$aldea['id_aldea'].">".$aldea['nombre']."</option>";
        }
    }else{
        $response = $aldeas["msg"];
    }
    echo $response;
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
