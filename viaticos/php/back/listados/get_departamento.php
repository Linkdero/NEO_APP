<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
$pais=$_POST['pais'];
    $departamentos = viaticos::get_departamentos($pais);
    if($departamentos["status"] == 200){
        $response = "";
        $response .="<option value=''>- Seleccionar -</option>";
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
