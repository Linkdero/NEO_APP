<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
?>
<?php
    $id_departamento = $_POST["departamento"];
    $municipios = Directorio::get_dependencias_by_nombre($id_departamento);
    if ($municipios["status"] == 200) {
        $response = "";
        $response .= "<option value='TODOS'>TODOS</option>";
        foreach ($municipios["data"] as $municipio) {
            $response .= "<option value=" . $municipio['id_tipo_dependencia'] . ">" . $municipio['nombre'] . "</option>";
        }
    } else {
        $response = $municipios["msg"];
    }
    echo $response;
else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
