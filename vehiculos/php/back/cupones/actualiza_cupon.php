<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);

$pdo = Database::connect_sqlsrv();
$cupones = $_POST['cupones'];
$respuesta = '';

try {
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($cupones as $i) {
        if ($i['radio'] == 1 && strlen($i['nombre']) == 1) {
            $id_destino = $_POST['id_destino'];
            $id_vehiculo = $_POST['id_vehiculo'];
            $id_refer = $_POST['id_refer'];
            $id_departamento = $_POST['id_departamento'];
            $id_municipio = $_POST['id_municipio'];
            $id_observa = $_POST['id_observa'];
            $km_actual = $_POST['km_actual'];
            // id_direccion
            $sql0 = "SELECT id_persona, id_secre, id_subsecre, id_direc
                    from saas_app.dbo.xxx_rrhh_ficha
                    WHERE id_persona = ?";
            $q1 = $pdo->prepare($sql0);
            $q1->execute(array($id_refer));
            $id_dir = $q1->fetch();

            $sql0 = "UPDATE dayf_cupones_documento_detalle set
                    flag_utilizado = 1,
                    flag_devuelto = 0,
                    id_tipo_uso = ?,
                    referencia = ?, ";

            if ($id_destino == 1144) { // vehiculos propios
                $sql0 = $sql0 . "id_vehiculo = ?, ";
            } else if ($id_destino == 1147) { // vehiculos arrendados
                $sql0 = $sql0 . "id_vehiculo_externo = ?, ";
            } else if ($id_destino != 1144 && $id_destino != 1147) {
                $sql0 = $sql0 . "caracteristicas = ?, ";
            }

            $sql0 = $sql0 . "id_persona = ?,
                    id_persona_direccion = ?,
                    id_persona_subsecretaria = ?,
                    id_persona_secretaria = ?,
                    km_actual = ?,
                    id_departamento = ?,
                    id_municipio = ?
                where id_cupon = ? ";

            $q2 = $pdo->prepare($sql0);
            $q2->execute(
                array(
                    $id_destino,
                    $id_observa,
                    $id_vehiculo,
                    $id_refer,
                    $id_dir['id_direc'],
                    $id_dir['id_subsecre'],
                    $id_dir['id_secre'],
                    $km_actual,
                    $id_departamento,
                    $id_municipio,
                    $i['id_cupon']
                )
            );
        }
    }

    $pdo->commit(); // Haz el commit después de que todas las operaciones se hayan completado correctamente
    $respuesta = "OK";
} catch (PDOException $e) {
    $respuesta = $e;
    try {
        $pdo->rollBack(); // Si hay un error, deshace todas las operaciones
    } catch (Exception $e2) {
        $respuesta = $e2;
    }
}

Database::disconnect_sqlsrv();
echo $respuesta;