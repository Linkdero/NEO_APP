<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id_persona = $_POST['id_persona'];
    $id_doc_solvencia = $_POST['id_doc_solvencia'];
    $id_insumo_detalle = $_POST['id_prod_insumo_detalle'];
    $cantidad = $_POST['cantidad'];

    //$tipo=$_POST['tipo'];
    if (is_numeric($id_persona)) {
        $e = array();
        $e = empleado::get_empleado_by_id_ficha($id_persona);
        $estado = empleado::get_empleado_estado_by_id($id_persona);
        $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
        $id_bodega = 0;
        foreach ($datos as $d) {
            $id_bodega = $d['id_bodega_insumo'];
        }
        $direccion = $e['id_dirf'];

        if ($e['id_tipo'] == 2) {
            $direccion = $e['id_dirs'];
        } else
            if ($e['id_tipo'] == 4) {
                $direccion = $e['id_dirapy'];
            }

        if ($e['primer_nombre'] != '') {
            if ($estado['estado_persona'] == 1) {
                //echo $id_bodega;

                $sql = "INSERT INTO inv_solvencia_detalle
                (id_bodega_insumo,
                 id_doc_solvencia,
                 id_persona_en_caliente,
                 id_direccion_en_caliente,
                 id_prod_insumo_detalle,
                 cantidad,
                 id_estado,
                 anotaciones
               )VALUES(?,?,?,?,?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array(
                    $id_bodega,
                    $id_doc_solvencia,
                    $id_persona,
                    $direccion,
                    $id_insumo_detalle,
                    $cantidad,
                    1,
                    ''
                ));
                
            } else {
                echo 'error1';
            }
        } else {
            echo 'error2';
        }
    } else {
        echo 'error3';
    }

    Database::disconnect_sqlsrv();
//}
//echo $correlativo;

else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
