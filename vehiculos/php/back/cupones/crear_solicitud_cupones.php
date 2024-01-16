<?php

    include_once '../../../../inc/functions.php';
    sec_session_start();
    set_time_limit(0);

    $pdo = Database::connect_sqlsrv();
    date_default_timezone_set('America/Guatemala');
    $nro_documento = $_POST['nro_documento'];
    $id_autorizador = $_POST['id_autorizador'];
    $id_conductor_ = $_POST['id_conductor_'];
    $observaciones = (!empty($_POST['observaciones'])) ? $_POST['observaciones'] : NULL;
    $cupones = $_POST['cupones'];


    try{
        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // actualiza encabezado de documentos
        $sql0 = "SELECT id_persona, id_secre, id_subsecre, id_direc
            from saas_app.dbo.xxx_rrhh_ficha
            WHERE id_persona = ?";
        $q1 = $pdo->prepare($sql0);
        $q1->execute(array($id_conductor_));
        $id_dir = $q1->fetch();
        $secre = (!empty($id_dir['id_secre'])) ? $id_dir['id_secre'] : NULL;
        $sub = (!empty($id_dir['id_subsecre'])) ? $id_dir['id_subsecre'] : NULL;
        $dir = (!empty($id_dir['id_direc'])) ? $id_dir['id_direc'] : NULL;

        $sql1 = "INSERT INTO dayf_cupones_documento (id_tipo_documento,id_estado_documento,fecha,descripcion,
                nro_documento,id_persona_autorizo,id_persona_opero,id_evento,id_persona_recibe_cupon,
                id_persona_recibe_cupon_direccion,id_persona_recibe_cupon_subsecretaria,
                id_persona_recibe_cupon_secretaria,fecha_entrega)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(4353,4348,date('Y-m-d H:i:s'),$observaciones,$nro_documento,$id_autorizador,
                           $_SESSION['id_persona'],1116,$id_conductor_,$dir,$sub,$secre,date('Y-m-d H:i:s')));
        $cgid = $pdo->lastInsertId();
        $suma = 0;
        foreach ($cupones as $c){
          $suma += $c['Cupon_monto'];
          $sql2 = "UPDATE dayf_cupones SET id_estado_cupon = ?
              WHERE id_cupon = ? ";

          $q2 = $pdo->prepare($sql2);
          $q2->execute(array(5562,$c['Cupon_id']));

          $sql3 = "INSERT INTO dayf_cupones_documento_detalle
                  (id_documento, id_cupon, flag_utilizado, flag_devuelto)
                  VALUES (?,?,?,?)";

          $q3 = $pdo->prepare($sql3);
          $q3->execute(array($cgid,$c['Cupon_id'],0,0));


        }

        $sql4 = "UPDATE dayf_cupones_documento SET total = ?
            WHERE id_documento = ? ";

        $q4 = $pdo->prepare($sql4);
        $q4->execute(array($suma,$cgid));
        echo 'OK';
        $pdo->commit();

    }catch (PDOException $e){
        echo $e;
        try{ $pdo->rollBack();}catch(Exception $e2){
        echo $e2;
        }
    }

    Database::disconnect_sqlsrv();


?>
