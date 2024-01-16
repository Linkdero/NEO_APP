<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');

    $id_persona = $_POST['id_persona'];
    $id_persona_diferente = 0;
    if (isset($_POST['id_persona_diferente'])) {
        $id_persona_diferente = $_POST['id_persona_diferente'];
    }
    $descripcion = $_POST['descripcion'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    if (is_numeric($id_persona)) {
        $e = array();
        $e = empleado::get_empleado_by_id_ficha($id_persona);
        $estado = empleado::get_empleado_estado_by_id($id_persona);
        $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
        $id_bodega = 0;
        foreach ($datos as $d) {
            $id_bodega = $d['id_bodega_insumo'];

        }
        $data = array();

        if ($e['primer_nombre'] != '') {
            if ($estado['estado_persona'] == 1) {
                //echo $id_bodega;
                $pdo = Database::connect_sqlsrv();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO inv_movimiento_encabezado
                (id_bodega_insumo,
                 fecha,
                 descripcion,
                 id_persona_entrega,
                 id_status,
                 id_estado_documento,
                 id_tipo_movimiento,
                 flag_automatico
               )VALUES(?,?,?,?,?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array(
                    $id_bodega,
                    date('Y-m-d H:i:s'),
                    $descripcion,
                    $_SESSION['id_persona'],
                    1,
                    4348,
                    $tipo_movimiento,
                    0
                ));

                $sql2 = "SELECT MAX(id_doc_insumo) AS id FROM inv_movimiento_encabezado
                 WHERE id_persona_entrega=?";
                $q2 = $pdo->prepare($sql2);
                $q2->execute(array($_SESSION['id_persona']));
                $codigo = $q2->fetch();

                $direccion = $e['id_dirf'];
                if ($e['id_tipo'] == 2) {
                    $direccion = $e['id_dirs'];
                } else
                    if ($e['id_tipo'] == 4) {
                        $direccion = $e['id_dirapy'];
                    }

                $sql4 = "INSERT INTO inv_movimiento_persona_asignada
                (id_bodega_insumo,
                 id_doc_insumo,

                 flag_firmante,
                 id_persona,
                 id_persona_direccion_recibe,
                 id_persona_diferente
                 )
                 VALUES (?,?,?,?,?,?)";
                $q4 = $pdo->prepare($sql4);
                $q4->execute(array($id_bodega, $codigo['id'], True, $id_persona, $direccion, $id_persona_diferente));
                $code = $codigo['id'];
                Database::disconnect_sqlsrv();
                echo $code;
            } else {
                echo 'error1';
            }
        } else {
            echo 'error2';
        }


    } else {
        echo 'error3';
    }


else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
