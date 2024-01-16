<?php

class insumo
{

    static function get_all_tipo_insumos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_bodega_insumo,
                   id_tipo_insumo,
                   descripcion,
                   descripcion_corta,
                   id_auditoria
            FROM inv_tipo_insumo";
        $p = $pdo->prepare($sql);
        $p->execute(array()); // 65 es el id de aplicaciones
        $tipos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $tipos;
    }

    static function get_all_tipo_insumos_by_bodega($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_bodega_insumo,
                   id_tipo_insumo,
                   descripcion,
                   descripcion_corta,
                   id_auditoria
            FROM inv_tipo_insumo
            WHERE id_bodega_insumo=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega)); // 65 es el id de aplicaciones
        $tipos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $tipos;
    }

    // static function get_usuarios_xnserie($numero_serie)
    // {
    //     $pdo = Database::connect_sqlsrv();
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $sql = "SELECT TOP(1) d.id_persona
    //     FROM inv_movimiento_detalle a
    //     LEFT JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
    //     LEFT JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
    //     LEFT JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
    //     LEFT JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
    //     LEFT JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo
    //     LEFT JOIN inv_tipo_movimiento g on f.id_tipo_movimiento = g.id_tipo_movimiento

    //     WHERE numero_serie=? AND f.id_estado_documento=4348 AND f.id_bodega_insumo=3552 AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada AND f.id_estado_documento=4348 AND f.id_bodega_insumo=3552 AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada

    //     ORDER BY a.id_doc_insumo DESC";
    //     $p = $pdo->prepare($sql);
    //     $p->execute(array($numero_serie));// 65 es el id de aplicaciones
    //     $accesos = $p->fetch();
    //     Database::disconnect_sqlsrv();
    //     return $accesos;
    // }


    static function get_acceso_bodega_usuario($id_usuario)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_persona,
                   b.id_acceso,
                   b.id_acceso_detalle,
                   b.id_bodega_insumo,
                   c.descripcion_corta
            FROM tbl_accesos_usuarios a
            INNER JOIN tbl_accesos_bodega_insumo b ON b.id_acceso=a.id_acceso
            INNER JOIN tbl_catalogo_detalle c ON b.id_bodega_insumo=c.id_item
            WHERE a.id_persona=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_usuario)); // 65 es el id de aplicaciones
        $accesos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $accesos;
    }

    static function get_all_movimientos_by_bodega($bodega)
    {
        set_time_limit(0);
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo,a.id_doc_insumo,a.id_movimiento,a.id_prod_insumo,a.id_prod_insumo_detalle,
                   a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,a.flag_resguardo,
                   a.impuesto,a.costo_unitario,a.costo_total,a.id_doc_insumo_ref,a.id_movimiento_ref,a.id_doc_insumo_dev,
                   a.id_auditoria,c.descripcion_corta AS tipo_movimiento,
                   d.descripcion AS producto,b.fecha

            FROM inv_movimiento_detalle a
            INNER JOIN inv_movimiento_encabezado b ON a.id_doc_insumo=b.id_doc_insumo
            INNER JOIN inv_tipo_movimiento c ON b.id_tipo_movimiento=c.id_tipo_movimiento
            INNER JOIN inv_producto_insumo d ON a.id_prod_insumo=d.id_producto_insumo
            WHERE a.id_bodega_insumo=?
            ORDER BY b.fecha DESC";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_all_movimientos_encabezado_by_bodega($bodega, $ini, $fin)
    {
        set_time_limit(0);
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, a.id_doc_insumo, a.fecha, a.descripcion,a.id_estado_documento,
                   a.id_tipo_movimiento, b.descripcion_corta AS tipo_movimiento, c.total,a.nro_documento,
                   g.primer_nombre AS n1_1,g.segundo_nombre AS n1_2,
                   g.tercer_nombre AS n1_3,g.primer_apellido AS a1_1,g.segundo_apellido AS a1_2,g.tercer_apellido AS a1_3,
                   f.primer_nombre AS n2_1,f.segundo_nombre AS n2_2,
                   f.tercer_nombre AS n2_3,f.primer_apellido AS a2_1,f.segundo_apellido AS a2_2,f.tercer_apellido AS a2_3,
                   e.id_persona
            FROM inv_movimiento_encabezado a
            INNER JOIN inv_tipo_movimiento b ON a.id_tipo_movimiento=b.id_tipo_movimiento
            LEFT JOIN (SELECT COUNT(*) AS total,id_doc_insumo FROM inv_movimiento_detalle
                       GROUP BY id_doc_insumo) AS c ON c.id_doc_insumo=a.id_doc_insumo
            LEFT JOIN inv_movimiento_persona_asignada e ON e.id_doc_insumo=a.id_doc_insumo
            LEFT JOIN rrhh_persona f ON e.id_persona=f.id_persona
            LEFT JOIN rrhh_persona g ON a.id_persona_entrega=g.id_persona
            WHERE a.id_bodega_insumo=? AND convert(varchar, a.fecha, 23)  BETWEEN ? AND ?
            ORDER BY a.fecha DESC";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $ini, $fin)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_all_movimientos_solvencia_by_bodega($bodega, $ini, $fin)
    {
        set_time_limit(0);
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, a.id_doc_solvencia, a.year, a.correlativo_solvencia, a.id_encargado,a.id_persona_solvencia,a.id_direccion_solvencia, a.id_tipo_solvencia, a.id_estado,a.fecha_solvencia as fecha, a.observaciones, a.id_solvente,
                   g.primer_nombre AS n1_1,g.segundo_nombre AS n1_2,
                   g.tercer_nombre AS n1_3,g.primer_apellido AS a1_1,g.segundo_apellido AS a1_2,g.tercer_apellido AS a1_3,
                   f.primer_nombre AS n2_1,f.segundo_nombre AS n2_2,
                   f.tercer_nombre AS n2_3,f.primer_apellido AS a2_1,f.segundo_apellido AS a2_2,f.tercer_apellido AS a2_3,
				   CASE
				   WHEN a.id_tipo_solvencia=1 THEN 'VACACIONES'
				   WHEN a.id_tipo_solvencia=2 THEN 'BAJA'
				   WHEN a.id_tipo_solvencia=3 THEN 'REMOCION'
				   WHEN a.id_tipo_solvencia=4 THEN 'TRASLADO'
				   WHEN a.id_tipo_solvencia=5 THEN 'FALLECIMIENTO'
                   WHEN a.id_tipo_solvencia=6 THEN 'RESICION DE CONTRATO'
                   WHEN a.id_tipo_solvencia=7 THEN 'FINALIZACION DE CONTRATO'    END AS tipo_solvencia,
				   CASE
				   WHEN a.id_solvente=1 THEN 'SOLVENTE'
				   ELSE 'INSOLVENTE' END AS solvente

            FROM inv_solvencia_encabezado a
            LEFT JOIN rrhh_persona f ON a.id_persona_solvencia=f.id_persona
            LEFT JOIN rrhh_persona g ON a.id_encargado=g.id_persona
            WHERE a.id_bodega_insumo=? AND convert(varchar, a.fecha_solvencia, 23)  BETWEEN ? AND ?
            ORDER BY a.fecha_solvencia DESC";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $ini, $fin)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_all_productos_asignados_actual_by_bodega($id_persona, $bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT f.fecha
                        ,a.id_doc_insumo
                        ,a.id_movimiento
                        ,a.id_prod_insumo
                        ,a.id_prod_insumo_detalle
                        ,a.cantidad_entregada
                        ,isnull(a.cantidad_devuelta,0) AS cantidad_devuelta
                        ,isnull(a.cantidad_consumida,0) AS cantidad_consumida
                        ,b.id_status
                        ,b.numero_serie
                        ,isnull(b.numero_estante,'') AS estante
                        ,c.descripcion
                        ,d.id_persona
                        ,f.id_bodega_insumo
                        ,f.id_tipo_movimiento
                        ,g.descripcion AS movimiento_tipo
                        ,c.id_tipo_insumo
                        ,g.descripcion AS movimiento_tipo
                        ,c.id_tipo_insumo
                        ,
                            CASE
                                WHEN c.id_tipo_insumo = 10 OR c.id_tipo_insumo = 11 OR c.id_tipo_insumo = 12 OR c.id_tipo_insumo = 18 OR c.id_tipo_insumo = 31 OR c.id_tipo_insumo = 34 OR c.id_tipo_insumo = 35 OR c.id_tipo_insumo = 40 OR c.id_tipo_insumo = 41 OR c.id_tipo_insumo = 42 OR c.id_tipo_insumo = 49 THEN 0
                                ELSE 1
                            END AS tipo
                        ,d.id_persona_diferente
                        ,f.descripcion AS anotaciones
                    FROM inv_movimiento_detalle a
                            left JOIN
                            inv_producto_insumo_detalle b
                            ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
                                left JOIN
                                inv_producto_insumo c
                                ON a.id_prod_insumo=c.id_producto_insumo
                                    left JOIN
                                    inv_movimiento_persona_asignada d
                                    ON a.id_doc_insumo=d.id_doc_insumo
                                        left JOIN
                                        tbl_catalogo_detalle e
                                        ON b.id_status=e.id_item
                                            left JOIN
                                            inv_movimiento_encabezado f
                                            ON a.id_doc_insumo=f.id_doc_insumo
                                                left JOIN
                                                inv_tipo_movimiento g
                                                ON f.id_tipo_movimiento = g.id_tipo_movimiento
                                        WHERE d.id_persona= ?
                                            AND f.id_estado_documento=4348
                                            AND f.id_bodega_insumo= ?
                                            AND isnull(a.cantidad_devuelta,0)<a.cantidad_entregada
                                            AND (a.flag_resguardo = 0
                                                OR a.flag_resguardo IS NULL)
                                            OR d.id_persona_diferente= ?
                                            AND d.id_persona_diferente<>0
                                            AND f.id_estado_documento=4348
                                            AND f.id_bodega_insumo= ?
                                            AND isnull(a.cantidad_devuelta,0)<a.cantidad_entregada
                                            AND (a.flag_resguardo = 0
                                                OR a.flag_resguardo IS NULL)
                                        ORDER BY a.id_doc_insumo,a.id_movimiento";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona, $bodega, $id_persona, $bodega)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_all_productos_asignados_actual_by_bodega_resguardo($id_persona, $bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_doc_insumo
                        ,a.id_movimiento
                        ,a.id_prod_insumo
                        ,a.id_prod_insumo_detalle
                        ,a.cantidad_entregada
                        ,a.cantidad_devuelta
                        ,a.cantidad_consumida
                        ,b.id_status
                        ,b.numero_serie
                        ,isnull(b.numero_estante,'') AS estante
                        ,c.descripcion
                        ,d.id_persona
                        ,f.id_bodega_insumo
                        ,f.id_tipo_movimiento
                        ,c.id_tipo_insumo
                        ,f.descripcion AS anotaciones
                        ,d.id_persona_diferente
                    FROM inv_movimiento_detalle a
                            INNER JOIN
                            inv_producto_insumo_detalle b
                            ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
                                INNER JOIN
                                inv_producto_insumo c
                                ON a.id_prod_insumo=c.id_producto_insumo
                                    INNER JOIN
                                    inv_movimiento_persona_asignada d
                                    ON a.id_doc_insumo=d.id_doc_insumo
                                        left JOIN
                                        tbl_catalogo_detalle e
                                        ON b.id_status=e.id_item
                                            INNER JOIN
                                            inv_movimiento_encabezado f
                                            ON a.id_doc_insumo=f.id_doc_insumo
                                    WHERE (d.id_persona= ?
                                            AND f.id_bodega_insumo= ?
                                            AND a.flag_resguardo = 1
                                            AND f.id_estado_documento=4348)
                                        OR (d.id_persona_diferente= ?
                                            AND f.id_bodega_insumo= ?
                                            AND a.flag_resguardo = 1
                                            AND f.id_estado_documento=4348)
                                    ORDER BY a.id_movimiento";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona, $bodega, $id_persona, $bodega)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_movimiento_tipos($transaccionTipo, $transaccionFiltro)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($transaccionFiltro == 2) {
            $sql = "SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=? AND id_tipo_movimiento=7";
            $p = $pdo->prepare($sql);
        } else {
            if ($transaccionTipo == 4353) {
                $sql = "SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=$transaccionTipo AND id_tipo_movimiento NOT IN (5,6,1,7,8,9) AND id_tipo_movimiento=3
              UNION
            SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=? AND id_tipo_movimiento NOT IN (5,6,1,7,8,9)  AND id_tipo_movimiento<>3";
                $p = $pdo->prepare($sql);
            } else {
                $sql = "SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=? AND id_tipo_movimiento NOT IN (5,6,1,7,8,9)";
                $p = $pdo->prepare($sql);
            }
        }
        $p->execute(array($transaccionTipo)); // 65 es el id de aplicaciones
        $tipos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $tipos;
    }

    static function get_insumo_by_serie($serie)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, a.flag_resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia,b.id_tipo_insumo,a.existencia

            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
           WHERE a.numero_serie=?
           order by a.id_prod_ins_detalle desc";
        $p = $pdo->prepare($sql);
        $p->execute(array($serie)); // 65 es el id de aplicaciones
        $serie = $p->fetch();
        Database::disconnect_sqlsrv();
        return $serie;
    }

    static function get_insumo_by_id($id_producto)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, a.flag_resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia,b.id_tipo_insumo,a.existencia,d.descripcion_corta AS estado,a.codigo_inventarios,
                   e.descripcion_corta as tipo

            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
            INNER JOIN tbl_catalogo_detalle d ON a.id_status=d.id_item
            INNER JOIN inv_tipo_insumo e ON b.id_tipo_insumo=e.id_tipo_insumo
           WHERE a.id_prod_ins_detalle=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_producto)); // 65 es el id de aplicaciones
        $producto = $p->fetch();
        Database::disconnect_sqlsrv();
        return $producto;
    }

    static function get_tipo_movimiento_by_id($id)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT descripcion FROM inv_tipo_movimiento WHERE id_tipo_movimiento=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id)); // 65 es el id de aplicaciones
        $movimiento = $p->fetch();
        Database::disconnect_sqlsrv();
        return $movimiento;
    }

    static function get_all_insumos_by_bodega($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_bodega_insumo
                    	,Primer_apellido
                    	,Segundo_apellido
                    	,Tercer_apellido
                    	,Primer_nombre
                    	,Segundo_nombre
                    	,Tercer_nombre
                    	,Codigo
                    	,id_prod_ins_detalle
                    	,codigo_inventarios
                    	,id_tipo_insumo
                    	,marca
                    	,modelo
                    	,numero_serie
                    	,numero_estante
                    	,estado
                    	,resguardo
                    	,existencia
                    	,Id_persona
                        ,tipo
                        ,idFrecuencia
                FROM Vw_insumos_por_bodega_estado T1
                        left JOIN
                        ( SELECT DISTINCT Mm.Id_prod_insumo_detalle AS Producto
                            ,max(Mm.Id_doc_insumo) AS Doc_insumo
                        FROM Inv_movimiento_detalle Mm
                                left JOIN
                                Inv_movimiento_persona_asignada Nn
                                ON Mm.Id_doc_insumo=Nn.Id_doc_insumo
                                    left JOIN
                                    Inv_movimiento_encabezado Pp
                                    ON Nn.Id_doc_insumo=Pp.Id_doc_insumo
                            WHERE Mm.Id_bodega_insumo = ?
                                AND Pp.Id_tipo_movimiento NOT IN (7,10)
                            GROUP BY Id_prod_insumo_detalle
                            ) T2
                            ON T1.Id_prod_ins_detalle = T2.Producto
                                left JOIN
                                (SELECT Mm.Id_doc_insumo AS Codigo
                                    ,Nn.Id_persona
                                    ,Oo.Primer_nombre
                                    ,Oo.Segundo_nombre
                                    ,Oo.Tercer_nombre
                                    ,Oo.Primer_apellido
                                    ,Oo.Segundo_apellido
                                    ,Oo.Tercer_apellido
                                FROM Inv_movimiento_encabezado Mm
                                        left JOIN
                                        Inv_movimiento_persona_asignada Nn
                                        ON Mm.Id_doc_insumo=Nn.Id_doc_insumo
                                            left JOIN
                                            Rrhh_persona Oo
                                            ON Nn.Id_persona=Oo.Id_persona
                                                left JOIN
                                                Inv_movimiento_encabezado Pp
                                                ON Nn.Id_doc_insumo=Pp.Id_doc_insumo
                                        WHERE Mm.Id_tipo_movimiento IN(2,3)
                                        ) T3
                                        ON T2.Doc_insumo = T3.Codigo
                                        AND id_tipo_insumo  NOT IN (10,11,12,18,31,34,35,40,41,42,43,49,14,15,20,21,32,54,55)
                                WHERE Id_bodega_insumo = ?
                                ORDER BY Id_prod_ins_detalle ASC";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $bodega)); // 65 es el id de aplicaciones
        $insumos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $insumos;
    }


    static function get_all_insumos_by_bodega_asignar($bodega, $estado = 5337)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, ISNULL(a.flag_resguardo, 0) AS resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia, d.descripcion AS estado,e.id_tipo_insumo,e.descripcion_corta AS tipo,
                   a.codigo_inventarios AS sicoin
            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
            INNER JOIN tbl_catalogo_detalle d ON a.id_status=d.id_item
            INNER JOIN inv_tipo_insumo e ON b.id_tipo_insumo=e.id_tipo_insumo
            WHERE b.id_bodega_insumo=? AND a.id_status=? AND b.id_tipo_insumo != 43
           ";

        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $estado)); // 65 es el id de aplicaciones
        $insumos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $insumos;
    }

    static function get_last_empleado_asignado($id_producto)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT TOP 1 b.id_persona, a.primer_nombre, a.segundo_nombre, a.tercer_nombre,
                   a.primer_apellido,a.segundo_apellido,a.tercer_apellido,
                   b.id_doc_insumo

            FROM rrhh_persona a
            INNER JOIN inv_movimiento_persona_asignada b ON a.id_persona=b.id_persona
            INNER JOIN inv_movimiento_detalle c ON c.id_doc_insumo=b.id_doc_insumo
           WHERE c.id_prod_insumo_detalle=?
           ORDER BY c.id_movimiento DESC";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_producto)); // 65 es el id de aplicaciones
        $empleado = $p->fetch();
        Database::disconnect_sqlsrv();
        return $empleado;
    }

    static function get_last_empleado_asignado_by_transaccion($transaccion)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT b.id_persona, a.primer_nombre, a.segundo_nombre, a.tercer_nombre,
                 a.primer_apellido,a.segundo_apellido,a.tercer_apellido

          FROM rrhh_persona a
          INNER JOIN inv_movimiento_persona_asignada b ON a.id_persona=b.id_persona

         WHERE b.id_doc_insumo=?
         ";
        $p = $pdo->prepare($sql);
        $p->execute(array($transaccion)); // 65 es el id de aplicaciones
        $empleado = $p->fetch();
        Database::disconnect_sqlsrv();
        return $empleado;
    }

    static function get_transaccion_encabezado_by_id($id_doc_insumo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT [id_bodega_insumo]
                  ,[nombre_bodega]
                  ,[id_doc_insumo]
                  ,[fecha]
                  ,[descripcion]
                  ,[id_proveedor]
                  ,[nombre_proveedor]
                  ,[nro_documento]
                  ,[fecha_factura]
                  ,[id_persona_entrega]
                  ,[nombre_persona_entrega]
                  ,[id_persona_autoriza]
                  ,[nombre_persona_autoriza]
                  ,[id_status]
                  ,[estado]
                  ,[id_estado_documento]
                  ,[id_tipo_movimiento]
                  ,[nombre_movimiento]
                  ,[flag_automatico]
                  ,[id_documento_ref_tipo]
                  ,[id_documento_ref]
                  ,[id_asignacion]
                  ,[flag_firmante]
                  ,[id_persona]
                  ,[nombre_persona_asignada]
                  ,[id_persona_direccion_recibe]
                  ,[nombre_direccion_recibe]
                  ,[id_persona_grupo]
                  ,[nombre_persona_grupo]
                  ,[id_orden_cedula]
                  ,[nro_registro]
                  ,[id_persona_funcionario]
                  ,[nombre_asignacion_operativa]
                  ,[numero_estante]
              FROM [SAAS_APP].[dbo].[xxx_inv_movimiento_encabezado]
              WHERE id_doc_insumo = ?
        ";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_doc_insumo));
        $transaccion_encabezado = $p->fetch();
        Database::disconnect_sqlsrv();
        return $transaccion_encabezado;
    }

    static function get_insumos_by_transaccion($transaccion)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie,
                   a.numero_estante,
                   a.flag_uso_compartido,
                   a.id_propietario,a.existencia,
                   e.id_tipo_movimiento,
                   d.cantidad_entregada,
                   d.cantidad_devuelta,
                   f.descripcion_corta,
                   CONVERT(VARCHAR(10), CAST(e.fecha AS DATETIME), 103) AS [fecha],
                   e.id_doc_insumo,
                   e.id_persona_entrega,
                   a.codigo_inventarios,
                   CASE WHEN a.id_prod_insumo=15 THEN (f.descripcion_corta + ' CASILLA: [' + a.numero_estante + ']') ELSE c.descripcion_corta END AS tipocas

            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
            INNER JOIN inv_movimiento_detalle d ON d.id_prod_insumo_detalle=a.id_prod_ins_detalle
            INNER JOIN inv_movimiento_encabezado e ON d.id_doc_insumo=e.id_doc_insumo
            INNER JOIN inv_tipo_insumo f ON b.id_tipo_insumo=f.id_tipo_insumo
            WHERE d.id_doc_insumo=?
            ORDER BY d.id_movimiento ASC
           ";
        $p = $pdo->prepare($sql);
        $p->execute(array($transaccion)); // 65 es el id de aplicaciones
        $insumos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $insumos;
    }

    static function get_empleado_by_transaccion($transaccion)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT b.id_persona, c.descripcion_corta AS tipo_movimiento,b.id_persona_direccion_recibe,b.id_persona_diferente,
                     a.descripcion, b.flag_firmante
              FROM inv_movimiento_encabezado a
              INNER JOIN inv_movimiento_persona_asignada b ON b.id_doc_insumo=a.id_doc_insumo
              INNER JOIN inv_tipo_movimiento c ON a.id_tipo_movimiento=c.id_tipo_movimiento
              WHERE a.id_doc_insumo=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($transaccion)); // 65 es el id de aplicaciones
        $empleado = $p->fetch();
        Database::disconnect_sqlsrv();
        return $empleado;
    }

    static function get_empleados_by_transaccion($transaccion)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT b.id_persona, c.descripcion_corta AS tipo_movimiento,b.id_persona_direccion_recibe,b.id_persona_diferente,
                     a.descripcion, b.flag_firmante, a.fecha as transaccion_fecha
              FROM inv_movimiento_encabezado a
              INNER JOIN inv_movimiento_persona_asignada b ON b.id_doc_insumo=a.id_doc_insumo
              INNER JOIN inv_tipo_movimiento c ON a.id_tipo_movimiento=c.id_tipo_movimiento
              WHERE a.id_doc_insumo=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($transaccion)); // 65 es el id de aplicaciones
        $empleados = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $empleados;
    }

    static function get_datos_insumo_by_id($id_insumo_detalle)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, b.descripcion AS
    descripcion,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, a.flag_resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia

            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
           WHERE a.id_prod_ins_detalle=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_insumo_detalle)); // 65 es el id de aplicaciones
        $serie = $p->fetch();
        Database::disconnect_sqlsrv();
        return $serie;
    }

    static function get_insumos_resguardo_con_empleado($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, g.descripcion AS marca, a.id_doc_insumo, a.id_movimiento, a.id_prod_insumo,a.id_prod_insumo_detalle,
                   a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,b.id_status,
                   b.numero_serie,c.descripcion AS modelo,d.id_persona, a.flag_resguardo AS resguardo,b.flag_uso_compartido,b.id_propietario,
                   b.existencia,h.descripcion AS estado,f.id_tipo_movimiento,i.id_tipo_insumo,i.descripcion_corta AS tipo,
                   f.descripcion AS anotaciones, d.id_persona_diferente
                    FROM inv_movimiento_detalle a
                    INNER JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
                    INNER JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
                    LEFT JOIN inv_marca_producto g on c.id_marca = g.id_marca
                    INNER JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
                    LEFT JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
                    INNER JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo
                    LEFT JOIN tbl_catalogo_detalle h on b.id_status = h.id_item
                    LEFT JOIN inv_tipo_insumo i on c.id_tipo_insumo = i.id_tipo_insumo

                    WHERE (f.id_bodega_insumo=? and a.flag_resguardo = 1
                    AND f.id_estado_documento=4348)
                    ORDER BY a.id_movimiento ASC";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, 5491)); // 65 es el id de aplicaciones
        $insumos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $insumos;
    }

    static function get_status_listados()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_catalogo, id_item, descripcion FROM tbl_catalogo_detalle WHERE id_catalogo=?
           ";
        $p = $pdo->prepare($sql);
        $p->execute(array(125)); // 65 es el id de aplicaciones
        $insumos = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $insumos;
    }

    // filtrar por tipo de insumo
    static function get_totales_marca_estado($bodega, $tipo)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tipo_ = ' AND id_tipo_insumo IN (1,2,3,4,6,7,8,9,14,15,16,22,23,24,25,26,28,32,36,43,44,54,55)';

        if ($bodega == 3552) {
            // $cabeceras = "COUNT(case marca when 'MOTOROLA' then 1 else null end) as MOTOROLA, COUNT(case marca when 'CHICOM' then 1 else null end) as CHICOM, COUNT(case when marca in ('HYTERA TC-508', 'HYT') then 1 else null end) as HYTERA, COUNT(case marca when 'BOAFENG' then 1 else null end) as BOAFENG, COUNT(case marca when 'KENWOOD' then 1 else null end) as KENWOOD, COUNT(case marca when 'VERTEX' then 1 else null end) as VERTEX";

            if ($tipo == 400) {
                $tipo_ = "AND id_tipo_insumo = 8";
            } elseif ($tipo == 800) {
                $tipo_ = "AND id_tipo_insumo = 9";
            }
            $sql = "SELECT MARCA, COUNT(case ESTADO when 'DISPONIBLE' then 1 else null end) AS DISPONIBLE, COUNT(case ESTADO when 'ASIGNADO' then 1 else null end) AS ASIGNADO, COUNT(case ESTADO when 'ASIGNADO TEMPORAL' then 1 else null end) AS 'ASIGNADO TEMPORAL', COUNT(case ESTADO when 'EXTRAVIADO' then 1 else null end) AS EXTRAVIADO, COUNT(case ESTADO when 'MAL ESTADO' then 1 else null end) AS 'MAL ESTADO'
                    FROM (SELECT        c.descripcion AS marca, d.descripcion AS ESTADO, e.descripcion_corta AS tipo
                                FROM            dbo.inv_producto_insumo_detalle AS a
                                INNER JOIN		dbo.inv_producto_insumo AS b ON a.id_prod_insumo = b.id_producto_insumo
                                INNER JOIN		dbo.inv_marca_producto AS c ON b.id_marca = c.id_marca
                                INNER JOIN		dbo.tbl_catalogo_detalle AS d ON a.id_status = d.id_item
                                INNER JOIN		dbo.inv_tipo_insumo AS e ON b.id_tipo_insumo = e.id_tipo_insumo
                                WHERE a.id_bodega_insumo=3552 AND e.id_tipo_insumo IN (1,2,3,4,6,7,8,9,14,15,16,22,23,24,25,26,28,32,36,43,44,54,55) AND a.estado=1 AND d.descripcion!='BAJA') AS PIVOTE
                    GROUP BY MARCA";
        }
        if ($bodega == 5907) {
            $cabeceras = "COUNT(CASE marca WHEN 'HUAWEI' THEN 1 ELSE NULL END) AS HUAWEI, COUNT(CASE marca WHEN 'IPHONE' THEN 1 ELSE NULL END) AS IPHONE, COUNT(case when marca in ('%SAMSUNG%', 'SAMSUNG') then 1 else null end) as SAMSUNG";
            $sql = "SELECT estado AS ESTADO, $cabeceras
            FROM (
            SELECT estado, marca
            FROM vw_insumos_por_bodega_estado_nuevo
            WHERE id_bodega_insumo=? $tipo_
            ) as pivote
            GROUP BY estado
            ORDER BY estado ASC";
        }
        if ($bodega == 5066) {
            $cabeceras = "COUNT(CASE marca WHEN 'DAEWOO' THEN 1 ELSE NULL END) AS DAEWOO, COUNT(CASE marca WHEN 'UZI' THEN 1 ELSE NULL END) AS UZI, COUNT(CASE marca WHEN 'KALASHNIKOV' THEN 1 ELSE NULL END) AS KALASHNIKOV, COUNT(CASE marca WHEN 'MEPOR 21' THEN 1 ELSE NULL END) AS MEPOR21, COUNT(CASE marca WHEN 'Eagle' THEN 1 ELSE NULL END) AS EAGLE, COUNT(CASE marca WHEN 'ROSSI' THEN 1 ELSE NULL END) AS ROSSI, COUNT(CASE marca WHEN 'Generico' THEN 1 ELSE NULL END) AS GENERICO, COUNT(CASE marca WHEN 'Desantis' THEN 1 ELSE NULL END) AS DESANTIS, COUNT(CASE marca WHEN 'GALIL' THEN 1 ELSE NULL END) AS GALIL, COUNT(CASE marca WHEN 'CAA' THEN 1 ELSE NULL END) AS CAA, COUNT(CASE marca WHEN 'VALTRO' THEN 1 ELSE NULL END) AS VALTRO, COUNT(CASE marca WHEN 'FN HERSTAL' THEN 1 ELSE NULL END) AS FNHERSTAL, COUNT(CASE marca WHEN 'JERICHO' THEN 1 ELSE NULL END) AS JERICHO, COUNT(CASE marca WHEN 'TAURUS' THEN 1 ELSE NULL END) AS TAURUS, COUNT(CASE marca WHEN 'IMI' THEN 1 ELSE NULL END) AS IMI, COUNT(CASE marca WHEN 'TANFOGLIO' THEN 1 ELSE NULL END) AS TANFOGLIO, COUNT(CASE marca WHEN 'Fobus' THEN 1 ELSE NULL END) AS FOBUS, COUNT(CASE marca WHEN 'S/M' THEN 1 ELSE NULL END) AS SM, COUNT(CASE marca WHEN 'Blackhawk' THEN 1 ELSE NULL END) AS BLACKHAWK, COUNT(CASE marca WHEN 'STOEGER' THEN 1 ELSE NULL END) AS STOEGER, COUNT(CASE marca WHEN 'PIETRO BERETTA' THEN 1 ELSE NULL END) AS BERETTA, COUNT(CASE marca WHEN 'CZ' THEN 1 ELSE NULL END) AS CZ, COUNT(CASE marca WHEN 'BUSHNELL' THEN 1 ELSE NULL END) AS BUSHNELL, COUNT(CASE marca WHEN 'A NAJMAN' THEN 1 ELSE NULL END) AS ANAJMAN, COUNT(CASE marca WHEN 'SIG PRO' THEN 1 ELSE NULL END) AS SIGPRO, COUNT(CASE marca WHEN 'A TAVOR' THEN 1 ELSE NULL END) AS TAVOR, COUNT(CASE marca WHEN 'S.E.G. ARMOR' THEN 1 ELSE NULL END) AS SEGARMOR, COUNT(CASE marca WHEN 'COLT' THEN 1 ELSE NULL END) AS COLT, COUNT(CASE marca WHEN 'GLOCK' THEN 1 ELSE NULL END) AS GLOCK, COUNT(CASE marca WHEN 'REMINGTON' THEN 1 ELSE NULL END) AS REMINGTON";
            $sql = "SELECT estado AS ESTADO, $cabeceras
            FROM (
            SELECT estado, marca
            FROM vw_insumos_por_bodega_estado_nuevo
            WHERE id_bodega_insumo=? $tipo_
            ) as pivote
            GROUP BY estado
            ORDER BY estado ASC";
        }

        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $tipo_)); // 65 es el id de aplicaciones
        $totales = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $totales;
    }

    static function get_totales_marca_estado_by_desc($bodega, $tipo, $status)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cabeceras = '';
        $group = '';
        $tipo_ = '';
        if ($bodega == 3552) {
            $cabeceras = 'MOTOROLA,Chicom,[HYTERA TC-508] as HYTERA,HYT,BOAFENG,KENWOOD,VERTEX';
            $group = 'MOTOROLA,Chicom,[HYTERA TC-508],HYT,BOAFENG,KENWOOD,VERTEX';

            if ($tipo == 400) {
                $tipo_ = "AND id_tipo_insumo = 8";
            } else if ($tipo == 800) {
                $tipo_ = "AND id_tipo_insumo = 9";
            } else {
                $tipo_ = 'AND id_tipo_insumo NOT IN (10,11,12,18,31,34,35,40,41,42,49)';
            }
        }
        $sql = "SELECT id_status, estado, $cabeceras FROM
             (SELECT
               id_status,
               estado,
               marca,
               id_prod_ins_detalle AS VALUE
            FROM vw_insumos_por_bodega_estado
            WHERE id_bodega_insumo=? $tipo_ AND id_status = ?
          ) x
          PIVOT
          (
            COUNT(x.VALUE)
            FOR x.marca IN ([Gasolina],[COLT],[CZ],[DAEWOO],[FN HERSTAL],[FRANCHI],[GALIL],[GLOCK],[JERICHO],[KALASHNIKOV],[PIETRO BERETTA],[ROSSI],[SIG PRO],[TANFOGLIO],[TAURUS],[UZI],[VALTRO],[STOEGER],[TAVOR],[MOTOROLA],[OTTO],[Blackhawk],[Eagle],[Fobus],[Generico],[Desantis],[icom],[VERTEX],[CAA],[A NAJMAN],[S/M],[MEPOR 21],[BUSHNELL],[BLACKBERRY],[TIGO],[SANDISK],[HUAWEI],[IPHONE],[NOKIA],[KENWOOD],[SAMSUNG],[LG],[SONY],[ALCATEL],[HTC],[CLARO],[MOVISTAR],[ZTE],[HYT],[CARGADOR HYT],[REMINGTON],[S.E.G. ARMOR],[B683],[audifono sin marca],[IPHONE 6 PLUS SPACE 64 GB],[Chicom],[Bmobile AX 660],[Microsoft],[Instructivo Operativo de Transmisiones],[SAMSUNG GALAXY J3],[BMOBILE],[HYTERA TC-508],[BOAFENG],[IMI],[DESERT EAGLE])
          ) p
          GROUP BY id_status,estado,$group
           ";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega, $status)); // 65 es el id de aplicaciones
        $totales = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $totales;
    }

    function get_totales_por_direccion($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cabeceras = '';
        $group = '';
        $tipo_insumo = '';
        $tipo_ = '';
        if ($bodega == 3552) {
            $cabeceras = 'MOTOROLA,Chicom,[HYTERA TC-508] as HYTERA,HYT,BOAFENG,KENWOOD,VERTEX';
            $group = 'MOTOROLA,Chicom,[HYTERA TC-508],HYT,BOAFENG,KENWOOD,VERTEX';


            $tipo_ = 'AND id_tipo_insumo NOT IN (10,11,12,18,31,34,35,40,41,42,49)';
        } else if ($bodega == 5907) { // Bodega de Móviles
            $cabeceras = 'MOTOROLA,Chicom,[HYTERA TC-508] as HYTERA,HYT,BOAFENG,KENWOOD,VERTEX';
            $group = 'MOTOROLA,Chicom,[HYTERA TC-508],HYT,BOAFENG,KENWOOD,VERTEX';


            $tipo_ = 'AND id_tipo_insumo NOT IN (10,11,12,18,31,34,35,40,41,42,49)';
        }
        $sql = "SELECT direccion AS DIRECCION, COUNT(case estado when 'ASIGNADO' then 1 else null end) as [ASIGNADO PERMANENTE], COUNT(case estado when 'ASIGNADO TEMPORAL' then 1 else null end) as [ASIGNADO TEMPORAL]
            FROM
              (SELECT
                b.descripcion AS direccion,
                a.estado as estado
              FROM vw_insumos_por_bodega_estado a
              LEFT JOIN rrhh_direcciones b ON a.id_persona_direccion_recibe=b.id_direccion
              WHERE a.id_bodega_insumo=? $tipo_
              ) as pivote
            GROUP BY direccion
            ORDER BY direccion DESC";
        // $sql = "SELECT direccion, [ASIGNADO PERMANENTE], [ASIGNADO TEMPORAL]
        // FROM
        //           (SELECT
        //           b.descripcion AS direccion,
        //           b.descripcion_corta as ds,
        //             a.estado,


        //             a.id_prod_ins_detalle AS VALUE
        //          FROM vw_insumos_por_bodega_estado a
        //          LEFT JOIN rrhh_direcciones b ON a.id_persona_direccion_recibe=b.id_direccion
        //          WHERE a.id_bodega_insumo=? $tipo_
        //        ) x
        //        PIVOT
        //        (
        //          COUNT(x.VALUE)
        //          FOR x.estado IN ([ASIGNADO PERMANENTE], [ASIGNADO TEMPORAL])

        //        ) p
        //        GROUP BY direccion,[ASIGNADO PERMANENTE], [ASIGNADO TEMPORAL]
        //         ";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega)); // 65 es el id de aplicaciones
        $totales = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $totales;
    }

    static function get_all_productos_asignados_y_resguardo_actual_by_bodega($id_persona, $bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_doc_insumo, a.id_movimiento, a.id_prod_insumo,a.id_prod_insumo_detalle,
                   a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,b.id_status,
                   b.numero_serie,c.descripcion,d.id_persona,f.id_bodega_insumo,f.id_tipo_movimiento,c.id_tipo_insumo,
                   f.descripcion AS anotaciones
            FROM inv_movimiento_detalle a
            INNER JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
            INNER JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
            INNER JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
            INNER JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
            INNER JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo

            WHERE d.id_persona=? AND f.id_estado_documento=4348 AND f.id_bodega_insumo=?
            AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada OR  ISNULL(a.cantidad_entregada,0)<a.cantidad_devuelta


            ORDER BY a.id_movimiento ASC";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_persona, $bodega)); // 65 es el id de aplicaciones
        $moves = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $moves;
    }

    static function get_solvencia($transaccion)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.id_bodega_insumo, a.id_doc_solvencia, a.year, a.correlativo_solvencia, a.id_encargado,a.id_persona_solvencia,a.id_direccion_solvencia, a.id_tipo_solvencia, a.id_estado,a.fecha_solvencia, a.observaciones, a.id_solvente,
                   b.id_doc_solvencia_detalle, b.id_persona_en_caliente, b.id_direccion_en_caliente, b.id_prod_insumo_detalle, b.cantidad, b.id_estado, b.anotaciones,
				   c.numero_serie, c.codigo_inventarios, e.descripcion AS marca, f.descripcion AS modelo,
				   CASE
				   WHEN a.id_tipo_solvencia=1 THEN 'VACACIONES'
				   WHEN a.id_tipo_solvencia=2 THEN 'BAJA'
				   WHEN a.id_tipo_solvencia=3 THEN 'REMOCION'
				   WHEN a.id_tipo_solvencia=4 THEN 'TRASLADO'
				   WHEN a.id_tipo_solvencia=5 THEN 'FALLECIMIENTO'
                   WHEN a.id_tipo_solvencia=6 THEN 'RESICION DE CONTRATO'
                   WHEN a.id_tipo_solvencia=7 THEN 'FINALIZACION DE CONTRATO'
                   when a.id_tipo_solvencia=8 then 'RENUNCIA'
                   when a.id_tipo_solvencia=9 then 'SUSPENCION' end as tipo_solvencia,
				   CASE
				   WHEN a.id_solvente=1 THEN 'SOLVENTE'
				   ELSE 'INSOLVENTE' END AS solvente

            FROM inv_solvencia_encabezado a
            LEFT JOIN inv_solvencia_detalle b ON b.id_doc_solvencia=a.id_doc_solvencia
			LEFT JOIN inv_producto_insumo_detalle c ON b.id_prod_insumo_detalle=c.id_prod_ins_detalle
			LEFT JOIN inv_producto_insumo d ON c.id_prod_insumo=d.id_producto_insumo
			LEFT JOIN inv_marca_producto e ON d.id_marca=e.id_marca
			LEFT JOIN inv_modelos f ON d.id_modelo=f.id_modelo
            WHERE a.id_doc_solvencia=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($transaccion)); // 65 es el id de aplicaciones
        $solvencia = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $solvencia;
    }

    static function get_bodegas_insumos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_catalogo_detalle WHERE id_catalogo=?";
        $p = $pdo->prepare($sql);
        $p->execute(array(116)); // 65 es el id de aplicaciones
        $bodegas = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $bodegas;
    }

    static function get_usuarios_por_bodega($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) AS total FROM tbl_accesos_bodega_insumo WHERE id_bodega_insumo=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega)); // 65 es el id de aplicaciones
        $total = $p->fetch();
        Database::disconnect_sqlsrv();
        return $total;
    }

    function get_empleados_ficha_asignados($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.*, b.*
            FROM xxx_rrhh_Ficha a
            INNER JOIN vw_insumos_por_bodega_estado_nuevo b ON b.id_persona=a.id_persona
            WHERE a.estado=? AND b.id_bodega_insumo=$bodega
            ORDER BY a.primer_apellido ASC";
        $p = $pdo->prepare($sql);
        $p->execute(array(1));
        $empleados = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $empleados;
    }

    function get_totales_por_direccion_nuevo($bodega)
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT ISNULL(direccion,'S/D') AS DIRECCION, COUNT(case estado when 'ASIGNADO' then 1 else null end) as a_p, COUNT(case estado when 'ASIGNADO TEMPORAL' then 1 else null end) as a_t
            FROM
              (SELECT
              b.descripcion AS direccion,
                a.estado as estado
             FROM vw_insumos_por_bodega_estado_nuevo a
             LEFT JOIN rrhh_direcciones b ON a.id_persona_direccion_recibe=b.id_direccion
             WHERE a.id_bodega_insumo=?
            ) as pivote
            GROUP BY direccion
            ORDER BY direccion ASC";

        // $sql = "SELECT COUNT(*)as conteo, id_persona_direccion_recibe AS id_direccion
        // FROM xxx_rrhh_Ficha a
        // INNER JOIN vw_insumos_por_bodega_estado b ON b.id_persona=a.id_persona
        // WHERE a.estado=? AND b.id_bodega_insumo=?
        // GROUP BY id_persona_direccion_recibe";
        $p = $pdo->prepare($sql);
        $p->execute(array($bodega));
        $direccion = $p->fetchAll();
        Database::disconnect_sqlsrv();
        return $direccion;
    }
}

function get_persona_direccion($empleado)
{
    if ($empleado['id_tipo'] == 2)
        $direccion = $empleado['id_dirs'];
    else if ($empleado['id_tipo'] == 4)
        $direccion = $empleado['id_dirapy'];
    else
        $direccion = $empleado['id_dirf'];

    return $direccion ?? NULL;
}
