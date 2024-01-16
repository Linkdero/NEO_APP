<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';

    $opcion = $_POST['opcion'];
    $DIRECTORIO = new Directorio();
    if ($opcion == 0) {
        $id_detalle = $_POST['id_detalle'];
        $response = $DIRECTORIO::delete_row($id_detalle);
        // $valor_anterior = array(
        //     "estado" => 1
        // );
        // $valor_nuevo = array(
        //     "estado" => 0
        // );
        // $response = $DIRECTORIO::add_log("dir_detalle_contactos", $id_detalle, json_encode($valor_anterior), json_encode($valor_nuevo));
    } else if ($opcion == 1) {
        $id_dependencia = $_POST["id_dependencia"];
        $funcionario = $_POST["funcionario"];
        $direccion = $_POST["direccion"];
        $telefonos = $_POST["telefonos"];
        $response = $DIRECTORIO::update_row($id_dependencia, $funcionario, $direccion, $telefonos);
    }

    echo json_encode($response);
    ?>
<?php else : ?>
    <script type='text/javascript'>
        window.location = 'principal';
    </script>
<?php endif; ?>