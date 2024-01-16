<?php

class Directorio
{

    function get_dependencias($departamento, $dependencia)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT D.id_dependencia, D.nombre as dependencia, D.direccion, C.cargo, C.persona, T.numero, M.nombre as municipio, DP.nombre as departamento
                FROM dir_dependencias D
                LEFT JOIN dir_cargos_dependencias C ON C.id_dependencia = D.id_dependencia
                INNER JOIN dir_municipios M ON D.id_municipio = M.id_municipio
                INNER JOIN dir_departamentos DP ON M.id_departamento = DP.id_departamento
                LEFT JOIN dir_dependencias_tel T ON D.id_dependencia = T.id_dependencia AND T.estado = 1 ";
        if ($departamento > 0 && $dependencia == 'TODOS') {
            $sql .= "WHERE DP.id_departamento=$departamento";
        } else if ($departamento == 0 && $dependencia != 'TODOS') {
            $sql .= "WHERE D.nombre LIKE '%$dependencia%'";
        } else if ($departamento > 0 && $dependencia != 'TODOS') {
            $sql .= "WHERE DP.id_departamento=$departamento AND D.nombre LIKE '%$dependencia%'";
        }

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            ini_set('memory_limit', '500M');
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_dependencia_by_id($id_dependencia)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT D.id_dependencia, D.nombre as dependencia, D.direccion, C.cargo, C.persona, T.numero, M.nombre as municipio, DP.nombre as departamento
                FROM dir_dependencias D
                LEFT JOIN dir_cargos_dependencias C ON C.id_dependencia = D.id_dependencia
                INNER JOIN dir_municipios M ON D.id_municipio = M.id_municipio
                INNER JOIN dir_departamentos DP ON M.id_departamento = DP.id_departamento
                LEFT JOIN dir_dependencias_tel T ON D.id_dependencia = T.id_dependencia
                WHERE D.id_dependencia = ?;";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_dependencia))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_detalle_dependencia($id_dependencia = null)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $valor_nuevo = array(
            "id_dependencia" => $id_dependencia
        );
        $log = "VALUES(19, 1875, 'dir_cargos_dependencias','" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 2)";


        $sql = "SELECT D.id_detalle, D.nombre, D.puesto, D.numero, convert(varchar, D.fecha_mod, 23) AS fecha
                FROM dir_cargos_dependencias C
                INNER JOIN dir_detalle_contactos D ON  C.id_cargo = D.id_cargo
                WHERE C.id_dependencia = ? AND D.estado = 1;
                INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                " . $log;

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($id_dependencia))) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response = [];
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_telefonos_dependencia($id_dependencia = null)
    {
        if ($id_dependencia != null) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $valor_nuevo = array(
                "id_dependencia" => $id_dependencia
            );
            $log = "VALUES(19, 1875, 'dir_dependencias_tel','" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 2)";

            $sql = "SELECT id_telefono, numero
                    FROM dir_dependencias_tel
                    WHERE id_dependencia = ? AND estado = 1
                    INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                    (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                    " . $log;

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array($id_dependencia))) {
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $response = [];
            }
            Database::disconnect_sqlsrv();
        } else {
            $response = [];
        }
        return $response;
    }

    function update_row($id_dependencia, $funcionario, $direccion, $telefonos)
    {
        if ($id_dependencia > 0) {
            $dump = array();
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT D.id_dependencia, D.nombre as dependencia, D.direccion, C.cargo, C.persona, T.numero, M.nombre as municipio, DP.nombre as departamento
                    FROM dir_dependencias D
                    LEFT JOIN dir_cargos_dependencias C ON C.id_dependencia = D.id_dependencia
                    INNER JOIN dir_municipios M ON D.id_municipio = M.id_municipio
                    INNER JOIN dir_departamentos DP ON M.id_departamento = DP.id_departamento
                    LEFT JOIN dir_dependencias_tel T ON D.id_dependencia = T.id_dependencia AND T.estado = 1
                    WHERE D.id_dependencia = :id;";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id", $id_dependencia);
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = true;
                if ($data[0]["persona"] != $funcionario) {
                    //ACTUALIZAR NOMBRE FUNCIONARIO

                    $valor_anterior = array(
                        "id_dependencia" => $id_dependencia,
                        "persona" => $data[0]["persona"]
                    );
                    $valor_nuevo = array(
                        "id_dependencia" => $id_dependencia,
                        "persona" => $funcionario
                    );
                    $log = "VALUES(19, 1875, 'dir_cargos_dependencias', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";


                    $query = "  UPDATE dir_cargos_dependencias 
                                SET persona = :persona, fecha_mod = GETDATE(), id_usuario_mod = :id_persona 
                                WHERE id_dependencia = :id;
                                INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                                (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                                " . $log;
                    $stmtUpdate = $pdo->prepare($query);
                    $stmtUpdate->bindParam("persona", $funcionario);
                    $stmtUpdate->bindParam("id_persona", $_SESSION["id_persona"]);
                    $stmtUpdate->bindParam("id", $id_dependencia);
                    $response = $stmtUpdate->execute();
                }
                if ($data[0]["direccion"] != $direccion) {
                    //ACTUALIZAR DIRECCION DEPENDENCIA

                    $valor_anterior = array(
                        "id_dependencia" => $id_dependencia,
                        "direccion" => $data[0]["direccion"]
                    );
                    $valor_nuevo = array(
                        "id_dependencia" => $id_dependencia,
                        "direccion" => $direccion
                    );
                    $log = "VALUES(19, 1875, 'dir_dependencias', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";


                    $query = "  UPDATE dir_dependencias 
                                SET direccion = :direccion, fecha_mod = GETDATE(), id_usuario_mod = :id_persona 
                                WHERE id_dependencia = :id;
                                INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                                (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                                " . $log;

                    $stmtUpdate = $pdo->prepare($query);
                    $stmtUpdate->bindParam("direccion", $direccion);
                    $stmtUpdate->bindParam("id_persona", $_SESSION["id_persona"]);
                    $stmtUpdate->bindParam("id", $id_dependencia);
                    $response = $stmtUpdate->execute();
                }

                if (count($telefonos) > count($data) || $data[0]["numero"] === NULL) {
                    //SE AGREGO ALGUN NUMERO
                    $inicio = ($data[0]["numero"] === NULL) ? 0 : count($data);
                    for ($i = $inicio; $i < count($telefonos); $i++) {
                        $telefono = $telefonos[$i]["value"];
                        $patron = "/^[[:digit:]]+$/";
                        //if(preg_match($patron, $telefono)){
                        if (true) {
                            $valor_nuevo = array(
                                "id_dependencia" => $id_dependencia,
                                "telefono" => $telefono,
                            );
                            $log = "VALUES(19, 1875, 'dir_dependencias_tel','" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 1)";

                            $query = "INSERT INTO dir_dependencias_tel VALUES(?,?,?,GETDATE(),?,GETDATE(),1);
                                    INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                                    (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                                    " . $log;
                            $stmtInsert = $pdo->prepare($query);
                            $response = $stmtInsert->execute(array($id_dependencia, $telefono, $_SESSION["id_persona"], $_SESSION["id_persona"]));
                        } else {
                            $response =  false;
                        }
                    }
                } else {
                    $query = "SELECT id_telefono, numero FROM dir_dependencias_tel WHERE id_dependencia = :id AND estado = 1;";
                    $stmtSelect = $pdo->prepare($query);
                    $stmtSelect->bindParam("id", $id_dependencia);
                    if ($stmtSelect->execute()) {
                        $data_telefonos = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($data_telefonos as $key => $value) {
                            if ($telefonos[$key]["value"] != $value["numero"]) {
                                //SE CAMBIO EL NUMERO
                                if ($telefonos[$key]["value"] == "") { //NUMERO ELIMINADO
                                    $valor_anterior = array(
                                        "id_telefono" => $value["id_telefono"],
                                        "estado" => 1
                                    );
                                    $valor_nuevo = array(
                                        "id_telefono" => $value["id_telefono"],
                                        "estado" => 0
                                    );
                                    $log = "VALUES(19, 1875, 'dir_dependencias_tel', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 4)";

                                    $query = "UPDATE dir_dependencias_tel SET estado = 0, fecha_mod = GETDATE(), id_usuario_mod = :usuario WHERE id_telefono = :id;
                                              INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                                              (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                                              " . $log;

                                    $stmtUpdate = $pdo->prepare($query);
                                    $stmtUpdate->bindParam("id", $value["id_telefono"]);
                                    $stmtUpdate->bindParam("usuario", $_SESSION["id_persona"]);
                                    $response = $stmtUpdate->execute();
                                } else { //NUMERO ACTUALIZADO
                                    $valor_anterior = array(
                                        "id_telefono" => $value["id_telefono"],
                                        "numero" => $value["numero"]
                                    );
                                    $valor_nuevo = array(
                                        "id_telefono" => $value["id_telefono"],
                                        "numero" => $telefonos[$key]["value"]
                                    );
                                    $log = "VALUES(19, 1875, 'dir_dependencias_tel', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";


                                    $query = "UPDATE dir_dependencias_tel SET numero = :numero, fecha_mod = GETDATE(), id_usuario_mod = :usuario WHERE id_telefono = :id;
                                            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                                            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                                            " . $log;
                                    $stmtUpdate = $pdo->prepare($query);
                                    $stmtUpdate->bindParam("numero", $telefonos[$key]["value"]);
                                    $stmtUpdate->bindParam("id", $value["id_telefono"]);
                                    $stmtUpdate->bindParam("usuario", $_SESSION["id_persona"]);
                                    $response = $stmtUpdate->execute();
                                }
                            }
                        }
                    }
                }
            } else {
                $response = false;
            }
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function update_dependencia($id_dependencia, $funcionario, $direccion, $telefonos)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $valor_anterior = array(
            "id_dependencia" => $id_dependencia
        );
        $valor_nuevo = array(
            "id_dependencia" => $id_dependencia,
            "funcionario" => $funcionario,
            "direccion" => $direccion
        );
        $log = "VALUES(19, 1875, 'dir_detalle_contactos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";

        $sql = "UPDATE dir_detalle_contactos SET funcionario = :funcionario, direcccion = :direccion, id_usuario_mod = :id_usuario, fecha_mod = GETDATE() WHERE id_dependencia = :id;
                INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
                (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
                " . $log;

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("id", $id_dependencia);
        $stmt->bindParam("id_usuario", $_SESSION["id_persona"]);
        $response = $stmt->execute();
        Database::disconnect_sqlsrv();
        return $response;
    }

    function delete_row($id_detalle)
    {
        if ($id_detalle > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $valor_anterior = array(
                "id_detalle" => $id_detalle,
                "estado" => 1
            );
            $valor_nuevo = array(
                "id_detalle" => $id_detalle,
                "estado" => 0
            );
            $log = "VALUES(19, 1875, 'dir_detalle_contactos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 4)";

            $sql = "UPDATE dir_detalle_contactos SET estado = 0, id_usuario_mod = :id_usuario, fecha_mod = GETDATE() WHERE id_detalle = :id;
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id", $id_detalle);
            $stmt->bindParam("id_usuario", $_SESSION["id_persona"]);
            $response = $stmt->execute();
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    // function add_log($tabla, $id_row, $valor_anterior, $valor_nuevo){
    //     $pdo = Database::connect_sqlsrv();
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $sql = "INSERT INTO tbl_log_directorio VALUES(?,?,?,?,?,GETDATE());";
    //     $stmt = $pdo->prepare($sql);
    //     $response = $stmt->execute(array($tabla, $id_row, $valor_anterior, $valor_nuevo, $_SESSION["id_persona"]));
    //     Database::disconnect_sqlsrv();
    //     return $response;
    // }


    static function exists_value($id, $data)
    {
        $value = 0;
        foreach ($data as $element) {
            if ($data[$value]['id_dependencia'] == $id) {
                return $value;
            }
            $value++;
        }
        return -1;
    }

    static function get_departamentos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_departamento, nombre, estado FROM dir_departamentos WHERE estado=?";
        $stmt = $pdo->prepare($sql);
        $response = $stmt->execute(array(1));
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();
        return $response;
    }

    static function get_dependencias_by_nombre($id_departamento)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT a.id_tipo_dependencia,a.nombre
                FROM dir_dependencias a
                INNER JOIN dir_municipios b ON a.id_municipio=b.id_municipio ";
        if ($id_departamento > 0) {
            $sql .= "WHERE b.id_departamento=$id_departamento";
        }

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $response = array(
                "data" => $stmt->fetchAll(),
                "status" => 200,
                "msg" => "Ok"
            );
        } else {
            $response = array(
                "data" => null,
                "status" => 400,
                "msg" => "Error al ejecutar la consulta"
            );
        }
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_cargo_by_dependencia($id_dependencia)
    {
        if ($id_dependencia > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT id_cargo FROM dir_cargos_dependencias WHERE id_dependencia = :id;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id", $id_dependencia);
            if ($stmt->execute()) {
                $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $response = [];
            }
            Database::disconnect_sqlsrv();
        } else {
            $response = [];
        }
        return $response;
    }

    function save_cell($id_cargo, $numero, $nombre, $puesto)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor_nuevo = array(
            "id_cargo" => $id_cargo,
            "numero" => $numero,
            "nombre" => $nombre,
            "puesto" => $puesto
        );
        $log = "VALUES(19, 1875, 'dir_detalle_contactos', '" . json_encode($valor_nuevo) . "', " . $_SESSION["id_persona"] . ", GETDATE(), 1)";

        $sql = "INSERT INTO dir_detalle_contactos VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(),1);
        INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
        (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
        " . $log;

        $stmt = $pdo->prepare($sql);
        $response = $stmt->execute(array($id_cargo, $nombre, $puesto, $numero, $_SESSION["id_persona"], $_SESSION["id_persona"]));
        Database::disconnect_sqlsrv();
        return $response;
    }

    function get_directorio()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(r.id_persona), r.nombre, r.dir_funcional, r.p_funcional, r.grupo, o.nombre_promocion, o.nombre_TipoPersona, o.Estado, o.tipo_servicio
                FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] r
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_persona_puesto_operativo_funcional] o ON r.id_persona=o.id_persona
                WHERE r.estado!=0
                ORDER BY r.id_persona ASC;";
        $p = $pdo->prepare($sql);
        $p->execute();
        $directorio = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $directorio;
    }

    function get_personas_dir($priv)
    {

        if ($priv != 1) {
            $where = ' WHERE emp.id_status=891 ';
        } else {
            $where = '';
        }
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(a.id_persona)
        ,a.primer_nombre
        ,a.segundo_nombre
        ,a.tercer_nombre
        ,a.primer_apellido
        ,a.segundo_apellido
        ,a.tercer_apellido
        ,a.tipo_persona
        ,a.fecha_ingreso
        ,a.id_status
        ,a.correo_electronico
        ,a.observaciones
        ,a.id_tipo_servicio
        ,a.id_genero
        ,b.descripcion
        ,c.fecha_nacimiento
        ,o.nombre_promocion, o.nombre_TipoPersona, o.Estado, o.tipo_servicio
		,f.p_funcional, f.grupo,
        CASE
        WHEN NOT hst.id_direccion_funcional IS NULL THEN dirf.descripcion
        ELSE CASE WHEN NOT cnt.id_direccion_servicio IS NULL THEN dirc.descripcion
            ELSE CASE WHEN NOT apy.id_direccion_servicio IS NULL THEN dira.descripcion
                ELSE 'S/D' END END
            END AS dir_general
        ,isnull(dirf.descripcion,isnull(dirsubf.descripcion,'S/D')) AS dir_funcional
        ,
            CASE
                WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND a.id_status IN (2312,1030)) THEN 1
                ELSE 0
            END AS estado_persona
        ,e.fecha_toma_posesion
        ,
            CASE
                WHEN e.fecha_toma_posesion IS NULL THEN ''
                ELSE convert(varchar(50),e.fecha_toma_posesion,105)
            END AS fecha_inicio
            FROM rrhh_persona a
            LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_persona_puesto_operativo_funcional] o ON a.id_persona=o.id_persona
			LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] f ON a.id_persona=f.id_persona
            left JOIN
                                rrhh_empleado emp
                                ON emp.id_persona=a.id_persona
                                    left JOIN
                                    rrhh_persona_complemento c
                                    ON a.id_persona=c.id_persona
                                        left JOIN
                                        tbl_catalogo_detalle b
                                        ON emp.id_status=b.id_item
                                            left JOIN
                                            rrhh_empleado_plaza AS e
                                            ON (e.id_empleado = emp.id_empleado
                                                AND e.id_status = 891)
                                                left OUTER JOIN
                                                dbo.rrhh_persona_apoyo AS apy
                                                ON a.id_persona = apy.id_persona
                                                    left OUTER JOIN
                                                    dbo.rrhh_empleado_contratos AS cnt
                                                    ON emp.id_empleado = cnt.id_empleado
                                                    AND cnt.id_status = 908
                                                        left JOIN
                                                        rrhh_hst_plazas AS hst
                                                        ON (e.id_plaza = hst.id_plaza
                                                            AND hst.flag_ubicacion_actual = 1)
                                                            left JOIN
                                                            rrhh_direcciones AS dirf
                                                            ON hst.id_direccion_funcional = dirf.id_direccion
                                                                left JOIN
                                                                rrhh_direcciones AS dirsubf
                                                                ON hst.id_subsecretaria_funcional = dirsubf.id_direccion
                                                                    left OUTER JOIN
                                                                    dbo.rrhh_direcciones AS dirc
                                                                    ON cnt.id_direccion_servicio = dirc.id_direccion
                                                                        left OUTER JOIN
                                                                        dbo.rrhh_direcciones AS dira
                                                                        ON dira.id_direccion = apy.id_direccion_servicio
                                                                        " . $where . "
                                                                ORDER BY a.id_persona ASC";
        $p = $pdo->prepare($sql);
        $p->execute();
        $directorio = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $directorio;
    }

    static function get_empleado_fotografia($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_persona,id_fotografia,id_tipo_fotografia,fotografia_principal,fotografia,
                        descripcion,id_auditoria
                    FROM rrhh_persona_fotografia
                    WHERE id_persona=? AND fotografia_principal = 1;";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $foto;
    }

    static function get_telefono_by_id($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor_nuevo = array(
            "id_persona" => $id_persona
        );
        $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_nuevo) . "', " . $_SESSION["id_persona"] . ", GETDATE(), 2)";
        $sql = "INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
        (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
        " . $log;
        $p = $pdo->prepare($sql);
        $p->execute();

        $sql = "SELECT id_telefono, nro_telefono, nombre_tipo_referencia, nombre_tipo_telefono, observaciones, Nombre_Completo
                FROM [SAAS_APP].[dbo].[xxx_rrhh_ficha_persona_telefono]
                WHERE id_persona=? AND Telefono_Activo=1;";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();
        return $foto;
    }

    function get_directorio_by_id($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(r.id_persona), r.nombre, r.dir_funcional, r.p_funcional, o.puesto_servicio, r.grupo, o.nombre_promocion, o.nombre_TipoPersona, o.Estado, o.tipo_servicio
                FROM [SAAS_APP].[dbo].[xxx_rrhh_Ficha] r
                LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_persona_puesto_operativo_funcional] o ON r.id_persona=o.id_persona
                WHERE r.estado!=0 AND r.id_persona=?
                ORDER BY r.id_persona ASC;";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $foto;
    }

    function get_telefonos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT rpt.id_persona, rpt.Nombre_Completo, rpt.nro_telefono, rpt.observaciones, rpt.nombre_tipo_referencia, rpt.nombre_tipo_telefono, rpt.Telefono_Activo
        FROM [SAAS_APP].[dbo].[xxx_rrhh_ficha_persona_telefono] rpt
        WHERE rpt.nro_telefono IS NOT NULL
        ORDER BY rpt.id_telefono;";
        $p = $pdo->prepare($sql);
        $p->execute(array());
        $foto = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $foto;
    }

    function inactivar_tel($id_persona, $nro_telefono)
    {
        if ($id_persona > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT id_telefono FROM rrhh_persona_telefonos WHERE id_persona = :id_persona AND nro_telefono =:nro_telefono;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id_persona", $id_persona);
            $stmt->bindParam("nro_telefono", $nro_telefono);
            $stmt->execute();
            $ref = $stmt->fetch();
            $valor_anterior = array(
                "id_telefono" => $ref[0],
                "flag_activo" => 1
            );
            $valor_nuevo = array(
                "id_telefono" => $ref[0],
                "flag_activo" => 0
            );
            $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 4)";
            $sql = "UPDATE rrhh_persona_telefonos SET flag_activo = 0 WHERE id_telefono = ? 
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($ref[0]));
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function update_tel($id_telefono, $nro_new)
    {
        if ($id_telefono > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT nro_telefono FROM rrhh_persona_telefonos WHERE id_telefono = :id_telefono";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id_telefono", $id_telefono);
            $stmt->execute();
            $ref = $stmt->fetch();
            $valor_anterior = array(
                "id_telefono" => $id_telefono,
                "nro_telefono" => $ref[0]
            );
            $valor_nuevo = array(
                "id_telefono" => $id_telefono,
                "nro_telefono" => $nro_new
            );
            $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
            $sql = "UPDATE rrhh_persona_telefonos SET nro_telefono = ? WHERE id_telefono = ? 
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($nro_new, $id_telefono));
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function update_obs($id_telefono, $nro_new)
    {
        if ($id_telefono > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT observaciones FROM rrhh_persona_telefonos WHERE id_telefono = :id_telefono";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id_telefono", $id_telefono);
            $stmt->execute();
            $ref = $stmt->fetch();
            $valor_anterior = array(
                "id_telefono" => $id_telefono,
                "observaciones" => $ref[0]
            );
            $valor_nuevo = array(
                "id_telefono" => $id_telefono,
                "observaciones" => $nro_new
            );
            $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
            $sql = "UPDATE rrhh_persona_telefonos SET observaciones = ? WHERE id_telefono = ? 
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($nro_new, $id_telefono));
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function update_tipo($id_telefono, $nro_new)
    {
        if ($id_telefono > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT id_tipo_telefono FROM rrhh_persona_telefonos WHERE id_telefono = :id_telefono";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id_telefono", $id_telefono);
            $stmt->execute();
            $ref = $stmt->fetch();
            $valor_anterior = array(
                "id_telefono" => $id_telefono,
                "id_tipo_telefono" => $ref[0]
            );
            $valor_nuevo = array(
                "id_telefono" => $id_telefono,
                "id_tipo_telefono" => $nro_new
            );
            $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
            $sql = "UPDATE rrhh_persona_telefonos SET id_tipo_telefono = ? WHERE id_telefono = ? 
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($nro_new, $id_telefono));
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function update_ref($id_telefono, $nro_new)
    {
        if ($id_telefono > 0) {
            $pdo = Database::connect_sqlsrv();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT tipo FROM rrhh_persona_telefonos WHERE id_telefono = :id_telefono";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("id_telefono", $id_telefono);
            $stmt->execute();
            $ref = $stmt->fetch();
            $valor_anterior = array(
                "id_telefono" => $id_telefono,
                "tipo" => $ref[0]
            );
            $valor_nuevo = array(
                "id_telefono" => $id_telefono,
                "tipo" => $nro_new
            );
            $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
            $sql = "UPDATE rrhh_persona_telefonos SET tipo = ? WHERE id_telefono = ? 
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($nro_new, $id_telefono));
            Database::disconnect_sqlsrv();
            return $response;
        } else {
            return false;
        }
    }

    function insert_tel($nro, $tipo, $ref, $obs, $id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor_nuevo = array(
            "id_persona" => $id_persona,
            "flag_activo" => 1,
            "flag_principal" => 1,
            "tipo" => $ref,
            "id_tipo_telefono" => $tipo,
            "nro_telefono" => $nro,
            "observaciones" => $obs
        );
        $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '" . json_encode($valor_nuevo) . "', " . $_SESSION["id_persona"] . ", GETDATE(), 1)";

        $sql = "
        INSERT INTO [SAAS_APP].[dbo].[rrhh_persona_telefonos]
        (id_persona, flag_activo, flag_principal, tipo, id_tipo_telefono, nro_telefono, observaciones)
        VALUES(:id_persona, 1, 1, :ref, :tipo, :nro, :obs);
        
        INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
        (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
        " . $log;

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("id_persona", $id_persona);
        $stmt->bindParam("nro", $nro);
        $stmt->bindParam("tipo", $tipo);
        $stmt->bindParam("ref", $ref);
        $stmt->bindParam("obs", $obs);
        $response = $stmt->execute();
        Database::disconnect_sqlsrv();
        return $response;
    }


    function get_personas_control()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT t.usr_mod, r.nombre, r.dir_funcional, r.p_funcional, t.fecha_mod, t.tipo_trans
                FROM tbl_log_crud t
                INNER JOIN (
                    SELECT usr_mod, MAX(fecha_mod) AS MaxDate
                    FROM tbl_log_crud
                    GROUP BY usr_mod
                    ) tm ON t.usr_mod = tm.usr_mod and t.fecha_mod = tm.MaxDate
                LEFT JOIN xxx_rrhh_Ficha r ON t.usr_mod = r.id_persona
                ORDER BY t.fecha_mod DESC";
        $p = $pdo->prepare($sql);
        $p->execute();
        $directorio = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $directorio;
    }

    function get_name($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $sql = "SELECT (primer_nombre+' '+segundo_nombre+' '+tercer_nombre+' '+primer_apellido+' '+segundo_apellido) FROM rrhh_persona WHERE id_persona=:id_persona";
        $sql = "SELECT primer_nombre+' '+segundo_nombre+' '+primer_apellido+' '+segundo_apellido FROM rrhh_persona WHERE id_persona=:id_persona";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $foto[0];
    }

    function get_name_id_tel($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT(r.nombre_completo_inverso)
        FROM [SAAS_APP].[dbo].[xxx_rrhh_empleado_persona] r
        LEFT OUTER JOIN rrhh_persona_telefonos t ON r.id_persona=t.id_persona
        WHERE t.id_telefono=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $foto[0];
    }

    function get_name_catalogo($id_persona)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT descripcion
                FROM [SAAS_APP].[dbo].[tbl_catalogo_detalle]
                WHERE id_item=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $foto[0];
    }



    static function get_log_by_id($id_persona, $month, $year)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo $month;
        // $valor_nuevo = array(
        //     "id_persona"=>$id_persona
        // );
        // $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '".json_encode($valor_nuevo)."', ". $_SESSION["id_persona"].", GETDATE(), 2)";
        // $sql ="INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
        // (id_pantalla, id_sistema, nom_tabla, val_nuevo, usr_mod, fecha_mod, tipo_trans)
        // ".$log;
        // $p = $pdo->prepare($sql);
        // $p->execute();

        $saladdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $date1 = "'" . $year . "-" . $month . "-" . "01'";
        $date2 = "'" . $year . "-" . $month . "-" . $saladdays . "'";

        $sql = "SELECT c.descripcion_corta, p.descrip_corta, l.tipo_trans, ISNULL(l.val_anterior,'No aplica') AS val_anterior, l.val_nuevo, l.fecha_mod
                FROM [SAAS_APP].[dbo].[tbl_log_crud] l
                LEFT JOIN tbl_pantallas p ON l.id_pantalla = p.id_pantalla
                LEFT JOIN tbl_catalogo_detalle c ON l.id_sistema= c.id_item
                WHERE usr_mod=? AND CONVERT(DATE,l.fecha_mod) BETWEEN CAST(" . $date1 . "AS DATE) AND CAST(" . $date2 . " AS DATE)
                ORDER BY l.fecha_mod DESC";
        // echo $sql;
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona));
        $foto = $p->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect_sqlsrv();
        return $foto;
    }
}
