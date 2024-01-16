<?php

include_once '../../../../inc/functions.php';
set_time_limit(0);

$pdo = Database::connect_sqlsrv();
$cupones = $_POST['cupones'];
$id_documento = $_POST['id_documento'];
$campo;
try {
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // actualiza encabezado de documentos

    $devuelto = 0;
    foreach ($cupones as $i) {
        //echo $i['checked1'];
        if ($i['id_vehiculo'] != 0) {
            //echo $i['id_vehiculo'];
        }

        $sql0 = "SELECT id_persona, id_secre, id_subsecre, id_direc
          from saas_app.dbo.xxx_rrhh_ficha
          WHERE id_persona = ?";
        $q1 = $pdo->prepare($sql0);
        $q1->execute(array($i['id_refer']));
        $id_dir = $q1->fetch();
        if ($id_dir['id_subsecre'] == 15 && $id_dir['id_direc'] == 0) {
            $id_dir['id_direc'] = 659;
        }

        if ($i['id_tipo_uso'] == 1144 || $i['id_tipo_uso'] == 1145 || $i['id_tipo_uso'] == 1147 || $i['id_tipo_uso'] == 5792) {
            $sql2 = "UPDATE dayf_cupones set
                            id_entrega_cupon = ?,
                            id_estado_cupon = 1914 ,
                            id_tipo_uso = ?,
                            referencia = ?, ";
            if ($i['id_tipo_uso'] == 1144) { ///////      // vehiculos propios
                $sql2 = $sql2 . "id_vehiculo = ?, ";
                $campo = $i['id_vehiculo'];
            } else if ($i['id_tipo_uso'] == 1147) { // vehiculos arrendados
                $sql2 = $sql2 . "id_vehiculo_externo = ?, ";
                $campo = $i['id_vehiculo'];
            } else if ($i['id_tipo_uso'] != 1144 && $i['id_tipo_uso'] != 1147) {
                $sql2 = $sql2 . "caracteristicas = ?, ";
                $campo = $i['caracteristicas'];
            }

            $sql2 = $sql2 . "id_persona = ?,
                            id_persona_direccion = ?,
                            id_persona_subsecretaria = ?,
                            id_persona_secretaria = ?,
                            km_actual = ?,
                            id_departamento = ?,
                            id_municipio = ? 
                            where id_cupon = ? ";

            $q2 = $pdo->prepare($sql2);
            $q2->execute(
                array(
                    $id_documento,
                    $i['id_tipo_uso'],
                    $i['referencia'],
                    $campo,
                    $i['id_refer'],
                    $id_dir['id_direc'],
                    $id_dir['id_subsecre'],
                    $id_dir['id_secre'],
                    $i['km'],
                    $i['id_departamento'],
                    $i['id_municipio'],
                    $i['id_cupon']
                )
            );
        } else {
            $sql2 = "UPDATE dayf_cupones set
            id_estado_cupon = 1913 where id_cupon = ? ";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($i['id_cupon']));

            $devuelto += $i['monto'];
        }

    }

    $sql1 = "UPDATE dayf_cupones_documento set
                id_estado_documento = 4347,
                total_devuelto = ?,
                fecha_procesado = ?
            where id_documento = ? ";

    $q1 = $pdo->prepare($sql1);
    $q1->execute(array($devuelto, date('Y-m-d H:i:s'), $id_documento));

    echo 'OK';
    $pdo->commit();
} catch (PDOException $e) {
    echo $e;
    try {
        $pdo->rollBack();
    } catch (Exception $e2) {
        echo $e2;
    }
}

Database::disconnect_sqlsrv();
