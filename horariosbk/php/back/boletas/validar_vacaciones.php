<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $vac_id = $_POST['vac_id'];
    $tipo = $_POST['tipo'];
    $control_id = $_POST['control_id'];
    $id_persona = $_SESSION['id_persona'];


    $aut = ($control_id > 4) ? "vac_aut_rrhh" : "vac_aut_dir";
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT *
    FROM [app_vacaciones].[dbo].[VACACIONES]
    WHERE vac_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vac_id));
    $sol = $stmt->fetch();
    $error = "";
    $vac_ini = $sol['vac_fch_ini'];
    $vac_fin = $sol['vac_fch_fin'];
    $vac_per = $sol['anio_id'];
    $est_id = $sol['est_id'];
    $dia_id = $sol['dia_id'];
    $dia_sol = $sol['vac_sol'];
    $sql = "SELECT * 
    FROM [app_vacaciones].[dbo].[VACACIONES] 
    WHERE dia_id =(SELECT dia_id 
                    FROM [app_vacaciones].[dbo].[VACACIONES] 
                    WHERE vac_id=?) AND (est_id=1 OR est_id=2 OR est_id=5) AND vac_id!=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vac_id, $vac_id));
    $vacs = $stmt->fetchAll();
    foreach ($vacs as $v) {
        if (($v['vac_fch_ini'] >= $vac_ini) && ($v['vac_fch_ini'] <= $vac_fin)) {
            $error .= "Las fechas solicitadas ya fueron apartadas\n";
        }
        if (($v['vac_fch_fin'] >= $vac_ini) && ($v['vac_fch_fin'] <= $vac_fin)) {
            $error .= "Las fechas solicitadas ya fueron apartadas\n";
        }
    }

    $sql = "SELECT DA.dia_est, VA.* FROM [app_vacaciones].[dbo].[VACACIONES] VA LEFT JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON VA.dia_id = DA.dia_id
    WHERE VA.emp_id =(SELECT emp_id FROM [app_vacaciones].[dbo].[VACACIONES] WHERE vac_id=?) 
    AND VA.vac_id!=? AND VA.est_id NOT IN (3,4,6,7) AND VA.anio_id<(SELECT anio_id FROM [app_vacaciones].[dbo].[VACACIONES] WHERE vac_id=?) AND DA.dia_est=1
	ORDER BY VA.anio_id";

    $stmt = $pdo->prepare($sql);
    // echo $vac_id;
    $stmt->execute(array($vac_id, $vac_id, $vac_id));
    $vacs = $stmt->fetchAll();
    foreach ($vacs as $v) {
        if ($v['vac_pen'] != 0 && !($control_id == 4 || $control_id == 7)) {
            $error .= "Debe utilizar todos los días pendientes\n";
        }
    }

    $sql = "SELECT * 
            FROM [app_vacaciones].[dbo].[VACACIONES] 
            WHERE emp_id =(SELECT emp_id
                           FROM [app_vacaciones].[dbo].[VACACIONES] 
                           WHERE vac_id=?) AND vac_id!=? AND est_id=?
                           AND anio_id!=(SELECT anio_id
                           FROM [app_vacaciones].[dbo].[VACACIONES] 
                           WHERE vac_id=?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vac_id, $vac_id, $est_id, $vac_id));
    $vacs = $stmt->fetchAll();


    foreach ($vacs as $v) {
        if ($vac_per > $v['anio_id'] && $tipo == 1) {
            $error .=  "Debe autorizar un periódo anterior\n";
        }
    }


    $sql = "SELECT DA.dia_est, VA.* 
    FROM [app_vacaciones].[dbo].[VACACIONES] VA
	LEFT JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON VA.dia_id = DA.dia_id
    WHERE VA.emp_id =(SELECT emp_id
                   FROM [app_vacaciones].[dbo].[VACACIONES] 
                   WHERE vac_id=?)
    AND VA.vac_id!=?
    AND VA.anio_id =((SELECT anio_id
                    FROM [app_vacaciones].[dbo].[VACACIONES] 
                    WHERE vac_id=?)-1)
    AND DA.dia_est=1";

    // echo $sql;
    // echo $vac_id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vac_id, $vac_id, $vac_id));
    $vacs = $stmt->fetchAll();

    $tryper = 0;
    foreach ($vacs as $v) {
        if ($tipo == 1 && (($est_id == 1 && $v['est_id'] == 2) || ($est_id == 2 && $v['est_id'] == 5) || ($est_id == 1 && $v['est_id'] == 5))) {
            $tryper = 1;
        }
    }
    if ($tryper == 0 && $tipo == 1 && sizeof($vacs) > 0) {
        $error = "Debe autorizar un periódo anterior\n";
    }

    $sql = "SELECT * 
            FROM [app_vacaciones].[dbo].[DIAS_ASIGNADOS] 
            WHERE dia_id =(SELECT dia_id 
                    FROM [app_vacaciones].[dbo].[VACACIONES] 
                    WHERE vac_id=?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vac_id));
    $diasa = $stmt->fetch();



    $set0 = ';';
    if ($control_id == 4 || $control_id == 7) {
        $set0 .= ' UPDATE [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] SET dia_goz=' . $diasa['dia_goz'] . ' WHERE dia_id =' . $dia_id . ';';
    }
    if ($control_id == 5) {
        $set0 .= ' UPDATE [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] SET dia_goz=' . ($diasa['dia_goz'] + $dia_sol) . ' WHERE dia_id =' . $dia_id . ';';
    }
    if ($control_id == 2) {
        $set0 .= ' UPDATE [APP_VACACIONES].[dbo].[VACACIONES] SET est_id= 4 WHERE dia_id =' . $dia_id . 'AND est_id!=2 AND est_id!=5 AND vac_id !=' . $vac_id . ';';
        $set0 .= ' UPDATE [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] SET dia_goz=' . $diasa['dia_goz'] . ' WHERE dia_id =' . $dia_id . ';';
        // $set0 .= ' UPDATE [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS] SET dia_goz=' . ($diasa['dia_goz'] + $dia_sol) . ' WHERE dia_id =' . $dia_id . ';';
    }
    $sql = "UPDATE [app_vacaciones].[dbo].[VACACIONES] SET est_id = " . $control_id . ", vac_fch_tra=GETDATE(), " . $aut . "=" . $id_persona . " WHERE vac_id = " . $vac_id . $set0;
    // echo $sql;
    // echo $error;
    $stmt = $pdo->prepare($sql);
    if ($error == "") {
        try {
            $stmt->execute();
            // echo json_encode(array(
            //     "status" => "201",
            //     "msg" => "OK"
            // ));
        } catch (exception $e) {
            // echo json_encode(array(
            //     "status" => "200",
            //     "msg" => $e
            // ));
        } finally {
            echo json_encode(array(
                "status" => "201",
                "msg" => "OK"
            ));
        }
    } else {
        echo json_encode(array(
            "status" => "200",
            "msg" => $error
        ));
    }
    Database::disconnect_sqlsrv();
else :
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
