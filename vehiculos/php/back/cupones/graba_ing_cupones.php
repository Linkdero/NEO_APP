<?php

    include_once '../../../../inc/functions.php';

    sec_session_start();
    set_time_limit(0);


    $pdo = Database::connect_sqlsrv();
    $arrayCupones = $_POST['arrayCupones'];
    $id_nroDocto = $_POST['id_nroDocto'];
    $id_observa = $_POST['id_observa'];
    $id_monto = $_POST['id_monto'];
    $totalCup = 0;
    $respuesta = '';
    try{
        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql1 = "INSERT INTO dayf_cupones_documento
        (id_tipo_documento, id_estado_documento, fecha, descripcion, nro_documento, id_persona_opero)
        VALUES (4351,4347,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(date('Y-m-d H:i:s'),$id_observa,$id_nroDocto, $_SESSION['id_persona']));

        $iddoc = $pdo->lastInsertId();
        $suma = 0;
        foreach ($arrayCupones as $i){
            $suma += $i['monto'];
            $sql2 = "INSERT INTO dayf_cupones
                    (nro_cupon, id_ingreso_cupon, monto, id_estado_cupon)
                    VALUES (?,?,?,?)";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($i['cupon'],$iddoc,$id_monto,1913));
        }

        $sql3 = "SELECT id_cupon, nro_cupon from dayf_cupones WHERE id_ingreso_cupon = ?";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($iddoc));
        $cuponesIngresados = $q3->fetchAll();

        $sql30 = "SELECT sum(monto) as totalCup from dayf_cupones WHERE id_ingreso_cupon = ?";
        $p = $pdo->prepare($sql30);
        $p->execute(array($iddoc));
        $totalCup = $p->fetch();        

        $sql31 = "UPDATE dayf_cupones_documento set total = ? where id_documento = ? ";
        $p = $pdo->prepare($sql31);
        $p->execute(array($suma,$iddoc));

        foreach ($cuponesIngresados as $i){
            $sql4 = "INSERT INTO dayf_cupones_documento_detalle (id_documento, id_cupon) VALUES (?,?)";
            $q4 = $pdo->prepare($sql4);
            $q4->execute(array($iddoc,$i['id_cupon']));            
        }
           
        $pdo->commit();
        $respuesta = "OK";

    }catch (PDOException $e){
        $respuesta = $e;
        try{ $pdo->rollBack();}catch(Exception $e2){
            $respuesta = $e2;
        }
    }

    Database::disconnect_sqlsrv();

    echo $respuesta;
?>
