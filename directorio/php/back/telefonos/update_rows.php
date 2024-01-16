<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $DIRECTORIO = new Directorio();
    $id_telefono = $_POST['pk'];
    $nro_new = $_POST['value'];
    $opcion = $_POST['name'];
    if ($opcion == 1) {
        $response = $DIRECTORIO::update_tel($id_telefono, $nro_new);
    } else if ($opcion == 2) {
        $response = $DIRECTORIO::update_obs($id_telefono, $nro_new);
    } else if ($opcion == 3) {
        $response = $DIRECTORIO::update_tipo($id_telefono, $nro_new);
    } else if ($opcion == 4) {
        $response = $DIRECTORIO::update_ref($id_telefono, $nro_new);
    }



    echo json_encode($response);
else : ?>
    <script type='text/javascript'>
        window.location = 'principal';
    </script>
<?php endif; ?>