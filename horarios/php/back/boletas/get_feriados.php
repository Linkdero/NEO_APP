<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT *
    FROM [SAAS_APP].[dbo].[tbl_fechas_descansos] fd
    LEFT JOIN [SAAS_APP].[dbo].[tbl_listado_descansos] ld ON fd.id_descanso = ld.id_descanso
    WHERE (fecha_inicio BETWEEN ? AND ?) OR (fecha_fin BETWEEN ? AND ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($date1, $date2, $date1, $date2));
    $horarios = $stmt->fetchAll();
    Database::disconnect_sqlsrv();

    $data = array();

    foreach ($horarios as $t) {


        $sub_array = array(
            'motivo' => $t['motivo'],
            'fecha_inicio' => $t['fecha_inicio'],
            'fecha_fin' => $t['fecha_fin'],
            'nombre' => $t['nombre']
        );

        $data[] = $sub_array;
    }


    echo json_encode($data);


else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
