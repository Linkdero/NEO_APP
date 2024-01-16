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
        $id_persona = $_POST['id_persona'];
        $nro_telefono = $_POST['nro_telefono'];
        $response = $DIRECTORIO::inactivar_tel($id_persona, $nro_telefono);
        $valor_anterior = array(
            "estado" => 1
        );
        $valor_nuevo = array(
            "estado" => 0
        );
    } else if ($opcion == 1) {
    }

    echo json_encode($response);
    ?>
<?php else : ?>
    <script type='text/javascript'>
        window.location = 'principal';
    </script>
<?php endif; ?>