<?php
include_once '../../../../inc/functions.php';
sec_session_start();

 if (function_exists('verificar_session') && verificar_session() == true): 
     
        include_once '../functions.php';    

        $DIRECTORIO = new configuracion();
        $vals = $_POST['pk'];
        $new_val = $_POST['value'];
        $opcion = $_POST['name'];
        $response = $DIRECTORIO::update_extension($opcion, $new_val, $vals['id_persona'], $vals['id']);

        // switch ($opcion){
        //     case 'extension':
                
        //     case 'correo':
        //         $response = $DIRECTORIO::update_extension($opcion, $new_val, $vals['id_persona'], $vals['id']);
        
        echo json_encode($response);
    else: ?>
    <script type='text/javascript'> window.location = 'principal'; </script>
<?php endif; ?>