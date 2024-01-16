<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);
class Vehiculos
{
    static function getDatos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array(
            "combustibles" => array(),
            "colores" => array(),
            "tipos" => array(),
            "usos" => array(),
            "estados" => array(),
            "marcas" => array(),
            "personas" => array(),
            "asignaciones" => array(),
            "proveedores" => array(),
            "dependencias" => array(),
            "franjas" => array(),
        );

        // Obtener Combustible
        $sql = "SELECT id_item, catalogo_detalle_descripcion
              FROM xxx_tbl_catalogo_detalle
              WHERE id_catalogo = 68";

        $p = $pdo->prepare($sql);
        $p->execute();
        $combustibles = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($combustibles as $c) {
            $data["combustibles"][] = array(
                "id" => $c["id_item"],
                "nombre" => $c["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Colores
        $sql = "SELECT id_item, catalogo_detalle_descripcion
              FROM xxx_tbl_catalogo_detalle
              WHERE id_catalogo = 26";

        $p = $pdo->prepare($sql);
        $p->execute();
        $colores = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($colores as $c) {
            $data["colores"][] = array(
                "id" => $c["id_item"],
                "nombre" => $c["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Tipos
        $sql = "SELECT id_item, catalogo_detalle_descripcion
              FROM xxx_tbl_catalogo_detalle
              WHERE id_catalogo = 38";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["tipos"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Usos
        $sql = "SELECT id_item, catalogo_detalle_descripcion
                FROM xxx_tbl_catalogo_detalle
                WHERE id_catalogo = 42";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["usos"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Estados
        $sql = "SELECT id_item, catalogo_detalle_descripcion
                FROM xxx_tbl_catalogo_detalle
                WHERE id_catalogo = 43";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["estados"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Marcas
        $sql = "SELECT id_item, catalogo_detalle_descripcion
                FROM xxx_tbl_catalogo_detalle
                WHERE id_catalogo = 39";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["marcas"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Personas
        $sql = "SELECT nombre
                ,id_persona
                FROM xxx_rrhh_Ficha
                where estado = 1";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["personas"][] = array(
                "id" => $t["id_persona"],
                "nombre" => $t["nombre"]
            );
        }

        // Obtener Asignaciones
        $sql = "SELECT id_item, catalogo_detalle_descripcion
                FROM xxx_tbl_catalogo_detalle
                WHERE id_catalogo = 59";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["asignaciones"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["catalogo_detalle_descripcion"]
            );
        }

        // Obtener Proveedores
        $sql = "SELECT id_proveedor
                ,descripcion
                FROM dayf_proveedores
                where id_categoria = 1874";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["proveedores"][] = array(
                "id" => $t["id_proveedor"],
                "nombre" => $t["descripcion"]
            );
        }

        // Obtener Dependencia
        $sql = "SELECT id_direccion
                ,descripcion
                FROM rrhh_direcciones
                where id_nivel = 2";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["dependencias"][] = array(
                "id" => $t["id_direccion"],
                "nombre" => $t["descripcion"]
            );
        }

        // Obtener Marcas
        $sql = "SELECT id_item
                ,descripcion
                FROM tbl_catalogo_detalle
                where id_catalogo = 39";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["marcas"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["descripcion"]
            );
        }

        // Obtener Franjas
        $sql = "SELECT id_item
                ,descripcion
                FROM tbl_catalogo_detalle
                where id_catalogo = 39";

        $p = $pdo->prepare($sql);
        $p->execute();
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data["franjas"][] = array(
                "id" => $t["id_item"],
                "nombre" => $t["descripcion"]
            );
        }

        echo json_encode($data);
    }

    static function getDatosVehiculo()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array(
            "vehiculo" => array(),
        );

        // Obtener vehiculo
        $sql = "SELECT id_vehiculo
        ,id_propietario
        ,propietario
        ,nro_placa
        ,chasis
        ,motor
        ,modelo
        ,flag_franjas_de_color
        ,detalle_franjas
        ,observaciones
        ,capacidad_tanque
        ,kilometros_x_galon
        ,id_tipo_combustible
        ,nombre_tipo_combustible
        ,poliza_seguro
        ,id_empresa_seguros
        ,nombre_empresa_seguros
        ,id_linea
        ,nombre_linea
        ,id_color
        ,nombre_color
        ,id_marca
        ,nombre_marca
        ,id_tipo
        ,nombre_tipo
        ,id_uso
        ,nombre_uso
        ,id_status
        ,nombre_estado
        ,km_actual
        ,id_tipo_asignacion
        ,nombre_tipo_asignacion
        ,id_persona_asignado
        ,nombre_persona_asignado
        ,id_persona_autoriza
        ,nombre_persona_autoriza
        FROM xxx_dayf_vehiculos
        where id_vehiculo = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["id"]));
        $vehiculos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($vehiculos as $v) {
            $data["vehiculo"][] = array(
                "id_vehiculo" => $v["id_vehiculo"],
                "id_propietario" => $v["id_propietario"],
                "propietario" => $v["propietario"],
                "nro_placa" => $v["nro_placa"],
                "chasis" => $v["chasis"],
                "motor" => $v["motor"],
                "modelo" => $v["modelo"],
                "flag_franjas_de_color" => $v["flag_franjas_de_color"],
                "detalle_franjas" => $v["detalle_franjas"],
                "observaciones" => $v["observaciones"],
                "capacidad_tanque" => $v["capacidad_tanque"],
                "kilometros_x_galon" => $v["kilometros_x_galon"],
                "id_tipo_combustible" => $v["id_tipo_combustible"],
                "nombre_tipo_combustible" => $v["nombre_tipo_combustible"],
                "poliza_seguro" => $v["poliza_seguro"],
                "id_empresa_seguros" => $v["id_empresa_seguros"],
                "nombre_empresa_seguros" => $v["nombre_empresa_seguros"],
                "id_linea" => $v["id_linea"],
                "nombre_linea" => $v["nombre_linea"],
                "id_color" => $v["id_color"],
                "nombre_color" => $v["nombre_color"],
                "id_marca" => $v["id_marca"],
                "nombre_marca" => $v["nombre_marca"],
                "id_tipo" => $v["id_tipo"],
                "id_uso" => $v["id_uso"],
                "nombre_uso" => $v["nombre_uso"],
                "id_status" => $v["id_status"],
                "nombre_estado" => $v["nombre_estado"],
                "km_actual" => $v["km_actual"],
                "id_tipo_asignacion" => $v["id_tipo_asignacion"],
                "nombre_tipo_asignacion" => $v["nombre_tipo_asignacion"],
                "id_persona_asignado" => $v["id_persona_asignado"],
                "nombre_persona_asignado" => $v["nombre_persona_asignado"]
            );
        }

        echo json_encode($data);
    }

    //Opción 3
    static function getLineas()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = array();

        // Obtener Combustible
        $sql = "SELECT id_marca
        ,id_linea
        ,descripcion
        FROM dayf_vehiculo_linea
        where id_marca = ?
        ORDER BY id_linea DESC";

        $p = $pdo->prepare($sql);
        $p->execute(array($_GET["marca"]));
        $lineas = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($lineas as $c) {
            $data[] = array(
                "id" => $c["id_linea"],
                "nombre" => $c["descripcion"]
            );
        }
        echo json_encode($data);
    }

    //Opción 4
    static function nuevoVehículo()
    {
        $id = $_POST["id"];
        $formulario = $_POST["formulario"];
        $fecha_actual = date("Y-m-d h:i:s");
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($id == 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener Placa
            $sql = "SELECT COUNT(nro_placa) as placas
            FROM dayf_vehiculo_placas
            WHERE nro_placa = ?";

            $p = $pdo->prepare($sql);
            $p->execute(array($formulario[0]));
            $placa = $p->fetch(PDO::FETCH_ASSOC);
            $conteo = $placa['placas'];

            if ($conteo > 0) {
                $yes = array('msg' => 'VEHÍCULO YA EXISTENTE', 'id' => '');
                echo json_encode($yes);
                Database::disconnect_sqlsrv();
                return;
            } else {
                try {
                    $pdo->beginTransaction();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "INSERT INTO dayf_vehiculos (id_color, id_status, id_tipo, id_marca, id_linea,  modelo, detalle_franjas
                    ,capacidad_tanque, kilometros_x_galon, id_tipo_combustible, km_servicio_proyectado,  km_actual
                    , id_uso ,id_empresa_seguros, poliza_seguro, id_propietario, observaciones)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $p = $pdo->prepare($sql);
                    $p->execute(
                        array(
                            $formulario[3],
                            $formulario[4],
                            $formulario[5],
                            $formulario[6],
                            $formulario[7],
                            $formulario[8],
                            $formulario[9]
                            ,
                            $formulario[10],
                            $formulario[11],
                            $formulario[12],
                            $formulario[13],
                            $formulario[14]
                            ,
                            $formulario[17],
                            $formulario[18],
                            $formulario[19],
                            $formulario[20],
                            $formulario[21]
                        )
                    );

                    // Obtener Ultimo Vehículo
                    $sql = "SELECT TOP (1) id_vehiculo as id
                    FROM dayf_vehiculos
                    ORDER BY id_vehiculo DESC";

                    $p = $pdo->prepare($sql);
                    $p->execute();
                    $vehiculo = $p->fetch(PDO::FETCH_ASSOC);
                    $ultimo = $vehiculo['id'];

                    $sql = "INSERT INTO dayf_vehiculo_placas (id_vehiculo, nro_placa, chasis, motor, fecha, descripcion)
                    VALUES (?,?,?,?,?,?)";
                    $p = $pdo->prepare($sql);
                    $p->execute(
                        array($ultimo, $formulario[0], $formulario[1], $formulario[2], $fecha_actual, $formulario[21])
                    );

                    // Obtener Datos Personas
                    $sql = "SELECT id_dirf
                    ,id_subsecre
                    ,id_secre
                    FROM xxx_rrhh_Ficha
                    where estado = 1 and id_persona = ?";

                    $p = $pdo->prepare($sql);
                    $p->execute(array($formulario[16]));
                    $asignado = $p->fetch(PDO::FETCH_ASSOC);

                    $data = array(
                        "id_dirf" => $asignado["id_dirf"],
                        "id_subsecre" => $asignado["id_subsecre"],
                        "id_secre" => $asignado["id_secre"]
                    );

                    $sql = "INSERT INTO dayf_vehiculo_asignacion (id_vehiculo, tipo_asignacion, id_persona_asignado, id_persona_asignado_direccion, id_persona_asignado_subsecretaria, id_persona_asignado_secretaria, 
                    id_persona_autoriza, km_actual, fecha_entrega, fecha_recibido)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";
                    $p = $pdo->prepare($sql);
                    $p->execute(
                        array($ultimo, $formulario[17], $formulario[16], $data['id_dirf'], $data['id_subsecre'], $data['id_secre'], $formulario[15], $formulario[14], $fecha_actual, $fecha_actual)
                    );

                    // Obtén la cadena Base64 de la imagen
                    $base64Image = $formulario[22];

                    $replace64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
                    $replace64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
                    $replace64Image = str_replace('data:image/png;base64,', '', $base64Image);

                    // Convierte la cadena Base64 a datos binarios
                    $imageData = base64_decode($replace64Image);
                    $fotoPrincipal = true;
                    // Inserta los datos binarios en la base de datos
                    $sql = "INSERT INTO dayf_vehiculo_fotografias (id_vehiculo, foto_principal, foto) VALUES (:id_vehiculo,:foto_principal,:foto)";
                    $p = $pdo->prepare($sql);
                    $p->bindParam(':id_vehiculo', $ultimo, PDO::PARAM_INT);
                    $p->bindParam(':foto_principal', $fotoPrincipal);
                    $p->bindParam(':foto', $imageData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
                    $p->execute();

                    $yes = array('msg' => 'OK', 'id' => '1', 'message' => 'Vehículo Generado');
                    $pdo->commit();

                } catch (PDOException $e) {

                    $yes = array('msg' => 'ERROR', 'id' => $e);
                    try {
                        $pdo->rollBack();
                    } catch (Exception $e2) {
                        $yes = array('msg' => 'ERROR', 'id' => $e2);
                    }
                }

                echo json_encode($yes);
            }

        } else if ($id != 0) {
            try {
                $pdo->beginTransaction();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE dayf_vehiculos
                SET id_color = ?, id_status = ?, id_tipo = ?, id_marca = ?, id_linea = ?,  modelo = ?, detalle_franjas = ?, capacidad_tanque = ?, kilometros_x_galon = ?, id_tipo_combustible = ?,
                km_servicio_proyectado = ?,  km_actual = ?, id_uso = ?, id_empresa_seguros = ?, poliza_seguro = ?, id_propietario = ?, observaciones = ?
                WHERE id_vehiculo = ?";
                $p = $pdo->prepare($sql);
                $p->execute(
                    array(
                        $formulario[3],
                        $formulario[4],
                        $formulario[5],
                        $formulario[6],
                        $formulario[7],
                        $formulario[8],
                        $formulario[9]
                        ,
                        $formulario[10],
                        $formulario[11],
                        $formulario[12],
                        $formulario[13],
                        $formulario[14]
                        ,
                        $formulario[17],
                        $formulario[18],
                        $formulario[19],
                        $formulario[20],
                        $formulario[21],
                        $id
                    )
                );

                $sql = "UPDATE dayf_vehiculo_placas
                SET nro_placa = ?, chasis = ?, motor = ?, fecha = ?, descripcion = ?
                WHERE id_vehiculo = ? ";
                $p = $pdo->prepare($sql);
                $p->execute(
                    array($formulario[0], $formulario[1], $formulario[2], $fecha_actual, $formulario[21], $id)
                );

                // Obtener Datos Personas
                $sql = "SELECT id_dirf
                        ,id_subsecre
                        ,id_secre
                        FROM xxx_rrhh_Ficha
                        where estado = 1 and id_persona = ?";

                $p = $pdo->prepare($sql);
                $p->execute(array($formulario[16]));
                $asignado = $p->fetch(PDO::FETCH_ASSOC);

                $data = array(
                    "id_dirf" => $asignado["id_dirf"],
                    "id_subsecre" => $asignado["id_subsecre"],
                    "id_secre" => $asignado["id_secre"]
                );

                $sql = "INSERT INTO dayf_vehiculo_asignacion (id_vehiculo, tipo_asignacion, id_persona_asignado, id_persona_asignado_direccion, id_persona_asignado_subsecretaria, id_persona_asignado_secretaria, 
                id_persona_autoriza, km_actual, fecha_entrega, fecha_recibido)
                VALUES (?,?,?,?,?,?,?,?,?,?)";
                $p = $pdo->prepare($sql);
                $p->execute(
                    array($id, $formulario[17], $formulario[16], $data['id_dirf'], $data['id_subsecre'], $data['id_secre'], $formulario[15], $formulario[14], $fecha_actual, $fecha_actual)
                );

                // Obtén la cadena Base64 de la imagen
                $base64Image = $formulario[22];

                $replace64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $replace64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
                $replace64Image = str_replace('data:image/png;base64,', '', $base64Image);

                // Convierte la cadena Base64 a datos binarios
                $imageData = base64_decode($replace64Image);
                $fotoPrincipal = true;
                // Inserta los datos binarios en la base de datos
                $sql = "UPDATE dayf_vehiculo_fotografias
                SET foto_principal = :foto_principal, foto=:foto
                WHERE id_vehiculo = :id_vehiculo";
                $p = $pdo->prepare($sql);
                $p->bindParam(':id_vehiculo', $id, PDO::PARAM_INT);
                $p->bindParam(':foto_principal', $fotoPrincipal);
                $p->bindParam(':foto', $imageData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
                $p->execute();

                $yes = array('msg' => 'OK', 'id' => '1', 'message' => 'Vehículo Actualizado');
                $pdo->commit();

            } catch (PDOException $e) {

                $yes = array('msg' => 'ERROR', 'id' => $e);
                try {
                    $pdo->rollBack();
                } catch (Exception $e2) {
                    $yes = array('msg' => 'ERROR', 'id' => $e2);
                }
            }

            echo json_encode($yes);
        }
        Database::disconnect_sqlsrv();
    }
    //Opción 5
    static function getFichaVehiculo()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $idVehiculo = $_GET["id"];
        $data = array();

        // Obtener Combustible
        $sql = "SELECT v.id_vehiculo, id_propietario, propietario, nro_placa, chasis, motor, modelo, detalle_franjas, observaciones, capacidad_tanque, kilometros_x_galon, id_tipo_combustible, poliza_seguro, 
        id_empresa_seguros, id_linea, id_color, id_marca, id_tipo, id_uso, id_status, km_servicio_proyectado, 
        km_actual, id_tipo_asignacion, id_persona_asignado, id_persona_autoriza, foto
        FROM xxx_dayf_vehiculos as v
        LEFT JOIN dayf_vehiculo_fotografias as vf on v.id_vehiculo = vf.id_vehiculo
        WHERE v.id_vehiculo = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($idVehiculo));
        $lineas = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($lineas as $l) {
            $data[] = array(
                "id_vehiculo" => $l["id_vehiculo"],
                "id_propietario" => $l["id_propietario"],
                "propietario" => $l["propietario"],
                "nro_placa" => $l["nro_placa"],
                "chasis" => $l["chasis"],
                "motor" => $l["motor"],
                "modelo" => $l["modelo"],
                "detalle_franjas" => $l["detalle_franjas"],
                "observaciones" => $l["observaciones"],
                "capacidad_tanque" => $l["capacidad_tanque"],
                "kilometros_x_galon" => $l["kilometros_x_galon"],
                "id_tipo_combustible" => $l["id_tipo_combustible"],
                "poliza_seguro" => $l["poliza_seguro"],
                "id_empresa_seguros" => $l["id_empresa_seguros"],
                "id_linea" => $l["id_linea"],
                "id_color" => $l["id_color"],
                "id_marca" => $l["id_marca"],
                "id_tipo" => $l["id_tipo"],
                "id_uso" => $l["id_uso"],
                "id_status" => $l["id_status"],
                "km_servicio_proyectado" => $l["km_servicio_proyectado"],
                "km_actual" => $l["km_actual"],
                "id_tipo_asignacion" => $l["id_tipo_asignacion"],
                "id_persona_asignado" => $l["id_persona_asignado"],
                "id_persona_autoriza" => $l["id_persona_autoriza"],
                "foto" => base64_encode($l['foto']),
            );
        }
        echo json_encode($data);
    }



}

// case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Vehiculos::getDatos();
            break;

        case 2:
            Vehiculos::getDatosVehiculo();
            break;

        case 3:
            Vehiculos::getLineas();
            break;

        case 4:
            Vehiculos::nuevoVehículo();
            break;

        case 5:
            Vehiculos::getFichaVehiculo();
    }
}