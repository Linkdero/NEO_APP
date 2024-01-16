<?php
include_once '../../../../inc/functions.php';
sec_session_start();

 if (function_exists('verificar_session') && verificar_session() == true): 
     
        include_once '../functions.php';

        $DIRECTORIO = new Directorio();
        $nro = $_POST['nro'];
        $tipo = $_POST['tipo'];
        $ref = $_POST['ref'];
        $obs = $_POST['obs'];
        $id_persona = $_POST['id_persona'];
        $response = $DIRECTORIO::insert_tel($nro, $tipo, $ref, $obs, $id_persona);
        echo json_encode($response);
    else: ?>
    <script type='text/javascript'> window.location = 'principal'; </script>
<?php endif; ?>