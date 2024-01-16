<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $id_bodega_insumo = NULL;

    $id_persona = $_POST['id_persona'] ?? NULL;
    $id_persona_diferente = $_POST['id_persona_diferente'] ?? NULL;
    $personas = array();
    $empleados_estado = false;
    $asignacion_tipo = NULL;
    $devuelve_otro_empleado = $_POST['otroEmpleado'] ?? NULL;

    function get_persona_estado($id_persona): bool
    {
        $empleado_estado = (new empleado)->get_empleado_estado_by_id($id_persona);
        if ($empleado_estado['estado_persona'])
            return true;
        else
            return false;
    }

    function agregar_persona_asignada(&$personas,$id_persona, $flag_firmante = false): bool
    {
        if (is_numeric($id_persona))
        {
            $empleado = (new empleado)->get_empleado_by_id_ficha($id_persona);
            $estado = get_persona_estado($id_persona);
            $persona = array(
                'flag_firmante' => $flag_firmante,
                'empleado_estado' => $estado,
                'id_persona_direccion_recibe' => get_persona_direccion($empleado) ?? NULL
            );
            $personas[$id_persona] = $persona;
        }
        else if ($id_persona == NULL && $flag_firmante == false)
            $estado = true;
        else
            $estado = false;

        return $estado;
    }

    $empleados_estado = agregar_persona_asignada($personas,$id_persona, true);
    $empleados_estado = agregar_persona_asignada($personas,$id_persona_diferente);

    $movimiento_detalle = stripcslashes($_POST['movimientoDetalle'] ?? NULL);
    $movimiento_detalle = json_decode($movimiento_detalle, TRUE);
    if ($empleados_estado) {
        $bodega_accesos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
        //Parametros comunes
        //Obtener id de bodega insumo
        foreach ($bodega_accesos as $bodega_acceso) {
            $id_bodega_insumo = $bodega_acceso['id_bodega_insumo'];
        }
        $fecha = date('Y-m-d H:i:s');
        $descripcion = $_POST['descripcion'] ?? NULL;

        //campos ingreso a bodega
        $id_proveedor = $_POST['id_proveedor'] ?? NULL;
        $fecha_factura = $_POST['fecha_factura'] ?? NULL;

        //documento autoriza correlativo
        $nro_documento = $_POST['nro_documento'] ?? NULL;

        $id_persona_entrega = $_SESSION['id_persona'];
        $id_persona_autoriza = $_POST['id_persona_autoriza'] ?? NULL;
        $id_status = 1;
        $id_tipo_movimiento = $_POST['tipo_movimiento'] ?? NULL;
        //variables ingreso
        $flag_resguardo = 0;
        $id_tipo_insumo = [0];
        //Asignar estado de documento segun tipo de movimiento
        // 4348 -> Documento sin procesar
        // 4347 -> Documento procesado
        switch ($id_tipo_movimiento) {
            //Asignacion permamente
            case 2:
                $id_estado_documento = 4348;
                $asignacion_tipo = 5338;
                break;
            //Asignacion Temporal
            case 3:
                $id_estado_documento = 4348;
                $asignacion_tipo = 5339;
                break;
            //Devolucion
            case 4:
                $id_estado_documento = 4347;
                $asignacion_tipo = 5337; //disponible
                break;
            //Reparacion
            case 6:
                $id_estado_documento = 4348;
                break;
            //Resguardo
            case 10:
                $id_estado_documento = 4347;
                $asignacion_tipo = 5494; //resguardo
                $flag_resguardo = 1;
                $id_tipo_insumo = [10,11,12,18,31,34,35,40,41,42,43,49,54,55,14,15,20,21,32,68,65,89,90,91,72,76,70,69,65,80,71,66,88,75,73];
                break;
            //Entrega
            case 7:
                $asignacion_tipo = 5338;
            default:
                $id_estado_documento = 4347;
                break;
        }
        $flag_automatico = false;

        //echo $id_bodega;
        $pdo = Database::connect_sqlsrv();
        try
        {
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO inv_movimiento_encabezado
                        (id_bodega_insumo,
                         fecha,
                         descripcion,
                         id_proveedor,
                         nro_documento,
                         fecha_factura,
                         id_persona_entrega,
                         id_persona_autoriza,
                         id_status,
                         id_estado_documento,
                         id_tipo_movimiento,
                         flag_automatico
                       )VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(
                $id_bodega_insumo,
                $fecha,
                $descripcion,
                $id_proveedor,
                $nro_documento,
                $fecha_factura,
                $id_persona_entrega,
                $id_persona_autoriza,
                $id_status,
                $id_estado_documento,
                $id_tipo_movimiento,
                $flag_automatico
            ));
            $id_doc_insumo = $pdo->lastInsertId();

            //Insertar persona asignada
            $sql4 = "INSERT INTO inv_movimiento_persona_asignada
                (id_bodega_insumo,
                 id_doc_insumo,
                 flag_firmante,
                 id_persona,
                 id_persona_direccion_recibe
                 )
                 VALUES (?,?,?,?,?)";
            $q4 = $pdo->prepare($sql4);

            //Insertar persona asignada sin dirección
            $sqlPASD = "INSERT INTO inv_movimiento_persona_asignada
                (id_bodega_insumo,
                 id_doc_insumo,
                 flag_firmante,
                 id_persona
                 )
                 VALUES (?,?,?,?)";
            $qPASD = $pdo->prepare($sqlPASD);

            //Recorrer arreglo de personas asignadas
            foreach ($personas as $key => $persona){
                $id_persona = $key;
                $flag_firmante = $persona["flag_firmante"];
                $id_persona_direccion_recibe = $persona["id_persona_direccion_recibe"] ?? NULL;
                if ($id_persona_direccion_recibe != NULL && $id_persona_direccion_recibe > 0)
                    $q4->execute(array($id_bodega_insumo, $id_doc_insumo,$flag_firmante, $id_persona, $id_persona_direccion_recibe));
                else
                    $qPASD->execute(array($id_bodega_insumo, $id_doc_insumo,$flag_firmante, $id_persona));
            }

            //Query movimiento detalle
            $sqlMD = "INSERT INTO inv_movimiento_detalle
                      (
                        id_bodega_insumo,
                        id_doc_insumo,
                        id_prod_insumo,
                        id_prod_insumo_detalle,
                        cantidad_entregada,
                        cantidad_devuelta,
                        id_doc_insumo_ref
                      )
                      VALUES (?,?,?,?,?,?,?)";
            $qMD = $pdo->prepare($sqlMD);

            $sql5 = "UPDATE inv_movimiento_detalle
            SET cantidad_devuelta=(ISNULL(cantidad_devuelta,0)+ISNULL(?,0)), id_doc_insumo_dev = ?
            WHERE id_doc_insumo=? AND id_prod_insumo_detalle=?";
            $qMRD = $pdo->prepare($sql5);

            $sqlMovimientoReferenciaResguardo = "UPDATE inv_movimiento_detalle
            SET flag_resguardo = ?
            WHERE id_doc_insumo=? AND id_prod_insumo_detalle=?";
            $qMovimientoReferenciaResguardo = $pdo->prepare($sqlMovimientoReferenciaResguardo);

            //Query actualizar producto estado
            //TODO: IF... ELSE ... estado dinamico
            $in = str_repeat('?,',count($id_tipo_insumo)-1) . '?';
            $sqlPD = "UPDATE inv_producto_insumo_detalle
           SET id_status=?,flag_resguardo=?
           FROM inv_producto_insumo_detalle
           INNER JOIN inv_producto_insumo
           ON (inv_producto_insumo_detalle.id_prod_insumo = inv_producto_insumo.id_producto_insumo)
           WHERE inv_producto_insumo_detalle.id_prod_ins_detalle=?
           AND inv_producto_insumo.id_tipo_insumo  NOT IN (10,11,12,18,31,34,35,40,41,42,43,49,54,55,14,15,20,21,32,68,65,89,90,91,72,76,70,69,65,80,71,66,88,75,73)"; //que no sean cargadores audifonos etc
           //10,11,12,18,31,34,35,40,41,42,49,54,55,65,90,70,72,66,91
            $qPD = $pdo->prepare($sqlPD);

            //Query actualizar producto existencia
            $sqlPDE = "UPDATE inv_producto_insumo_detalle
           SET existencia=(ISNULL(existencia,0)-ISNULL(?,0)+ISNULL(?,0))
           WHERE id_prod_ins_detalle=?
           ";
            $qPDE = $pdo->prepare($sqlPDE);

            //Insertar movimiento detalle
            foreach ($movimiento_detalle as $movimiento){
                $id_prod_insumo = $movimiento['id_prod_insumo'];
                $id_prod_insumo_detalle = $movimiento['id_prod_insumo_detalle'];
                $cantidad_egreso = $cantidad_ingreso = NULL;
                switch($id_tipo_movimiento){
                    case 1:
                    case 4:
                    case 10:
                        $cantidad_ingreso = $movimiento['cantidad'];
                        break;
                    default:
                        $cantidad_egreso = $movimiento['cantidad'];
                        break;
                }

                $id_doc_insumo_ref = $movimiento['id_doc_insumo_ref'] ?? NULL;
                //Cantidad consumidad repetia valor de cantidad entregada en egreso
                //$insumo = insumo::get_datos_insumo_by_id($id_prod_insumo_detalle);
                $qMD->execute(array($id_bodega_insumo,$id_doc_insumo,$id_prod_insumo,$id_prod_insumo_detalle,$cantidad_egreso,$cantidad_ingreso,$id_doc_insumo_ref));
                $qPD->execute(array($asignacion_tipo,$flag_resguardo,$id_prod_insumo_detalle));
                $qPDE->execute(array($cantidad_egreso,$cantidad_ingreso,$id_prod_insumo_detalle));
                $qMovimientoReferenciaResguardo->execute(array($flag_resguardo,$id_doc_insumo_ref,$id_prod_insumo_detalle));
                if ($id_doc_insumo_ref and $id_tipo_movimiento == 4)
                    $qMRD->execute(array($cantidad_ingreso, $id_doc_insumo, $id_doc_insumo_ref, $id_prod_insumo_detalle));
            }

            //Query procesar documentos
            $sqlProcesarDocumento = "UPDATE t1
                        SET T1.id_estado_documento = CASE WHEN devuelto = entregado THEN 4347 ELSE T1.id_estado_documento END
                        FROM inv_movimiento_encabezado t1
                                left JOIN
                                (SELECT t1.id_bodega_insumo
                                    ,t1.id_doc_insumo
                                    ,t2.id_persona
                                    ,id_estado_documento
                                    ,fecha
                                    ,sum(COALESCE([cantidad_entregada],0)) AS entregado
                                    ,sum(case when t4.flag_resguardo = 0 then COALESCE([cantidad_devuelta],0) else 0 end) AS devuelto
                                FROM inv_movimiento_encabezado t1
                                        left JOIN
                                        inv_movimiento_persona_asignada t2
                                        ON t1.id_doc_insumo = t2.id_doc_insumo
                                            left JOIN
                                            inv_movimiento_detalle t3
                                            ON t1.id_doc_insumo = t3.id_doc_insumo
                                            left join
                                                inv_producto_insumo_detalle t4
                                                on t3.id_prod_insumo_detalle = t4.id_prod_ins_detalle
                                    GROUP BY t1.id_bodega_insumo,t1.id_doc_insumo,t2.id_persona,id_estado_documento,fecha
                                    ) t2
                                    ON t1.id_doc_insumo = t2.id_doc_insumo
                            WHERE t1.id_bodega_insumo = ?
                                AND t2.id_persona = ?
                                AND t2.id_estado_documento = 4348";
            $qProcesarDocumento = $pdo->prepare($sqlProcesarDocumento);
            $qProcesarDocumento->execute(array($id_bodega_insumo,$id_persona));

            //finalizar transaccion
            $pdo->commit();
        }catch(Exception $ex)
        {
            if ($pdo->inTransaction())
            {
                echo $ex;
                $pdo->rollBack();
            }

        }

        Database::disconnect_sqlsrv();
        echo $id_doc_insumo;

    } else {
        echo 'error';
    }
else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
