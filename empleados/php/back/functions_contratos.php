<?php
class contrato{
  static function get_contratos_por_empleado($id_persona,$tipo){
    $select = "";
    if($tipo == 2){
      $select = " TOP (4) ";
    }
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo == 2){
      //inicio
      $sql = "SELECT query.* FROM (
            SELECT $select cnt.tipo_contrato,
             CASE WHEN tipo_contrato=8 THEN '031' ELSE
             CASE WHEN tipo_contrato=9 THEN '029' ELSE ' ' END END Renglon,
             e.id_persona, cnt.id_empleado, e.nombre_completo, cnt.reng_num, cnt.nro_contrato, cnt.nro_acuerdo_aprobacion, cnt.fecha_acuerdo_aprobacion,
             cnt.fecha_contrato, e.fecha_ingreso, cnt.fecha_inicio, cnt.fecha_finalizacion, cnt.monto_contrato, cnt.monto_mensual, cnt.fecha_acuerdo_resicion, cnt.fecha_efectiva_resicion,
             cnt.id_categoria, cat.descripcion categoria,
             cnt.id_puesto_servicio, pto.descripcion puesto, cnt.id_secretaria_servicio, sec.descripcion secretaria, subs.descripcion sub_secretaria,
             cnt.id_direccion_servicio, dir.descripcion direccion, cnt.id_subdireccion_servicio, subd.descripcion subdireccion, cnt.id_depto_servicio, dep.descripcion departamento,
             cnt.id_nivel_servicio, cnt.id_seccion_servicio,cnt.id_status,stacnt.descripcion AS estado, cd.archivo
             FROM dbo.xxx_rrhh_empleado_persona e LEFT JOIN
             dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = e.id_empleado LEFT JOIN
             dbo.tbl_catalogo_detalle cat ON cnt.id_categoria = cat.id_item LEFT JOIN
             dbo.tbl_catalogo_detalle stacnt ON cnt.id_status = stacnt.id_item LEFT JOIN
             dbo.tbl_catalogo_detalle pto ON cnt.id_puesto_servicio = pto.id_item LEFT JOIN
             dbo.rrhh_direcciones sec ON cnt.id_secretaria_servicio = sec.id_direccion LEFT JOIN
             dbo.rrhh_direcciones subs ON cnt.id_subsecretaria_servicio = subs.id_direccion LEFT JOIN
             dbo.rrhh_direcciones dir ON cnt.id_direccion_servicio = dir.id_direccion LEFT JOIN
             dbo.rrhh_subdirecciones subd ON cnt.id_direccion_servicio = subd.id_direccion AND cnt.id_subdireccion_servicio = subd.id_subdireccion LEFT JOIN
             dbo.rrhh_departamentos dep ON cnt.id_depto_servicio = dep.id_departamento
             LEFT JOIN  (SELECT T.archivo, T.reng_num, T.correlativo
               FROM
                (
                  SELECT archivo, reng_num, correlativo,  ROW_NUMBER() OVER (PARTITION BY reng_num ORDER BY correlativo DESC) AS rnk FROM rrhh_empleado_contrato_detalle a
                  --WHERE   id_status IN (1210,891)
                ) T
                WHERE T.rnk = 1
              ) AS cd ON cd.reng_num = cnt.reng_num
         WHERE
         e.id_persona=?
  	   ORDER BY cnt.reng_num DESC, cnt.tipo_contrato DESC
  	   ) as query ";

         if($tipo == 2){
           $sql.=" ORDER BY reng_num ASC ";
         }

      //fin
    }else{
      //inicio
      $sql = "SELECT $select cnt.tipo_contrato,
       CASE WHEN tipo_contrato=8 THEN '031' ELSE
       CASE WHEN tipo_contrato=9 THEN '029' ELSE ' ' END END Renglon,
       e.id_persona, cnt.id_empleado, e.nombre_completo, cnt.reng_num, cnt.nro_contrato, cnt.nro_acuerdo_aprobacion, cnt.fecha_acuerdo_aprobacion,
       cnt.fecha_contrato, e.fecha_ingreso, cnt.fecha_inicio, cnt.fecha_finalizacion, cnt.monto_contrato, cnt.monto_mensual, cnt.fecha_acuerdo_resicion, cnt.fecha_efectiva_resicion,
       cnt.id_categoria, cat.descripcion categoria,
       cnt.id_puesto_servicio, pto.descripcion puesto, cnt.id_secretaria_servicio, sec.descripcion secretaria, subs.descripcion sub_secretaria,
       cnt.id_direccion_servicio, dir.descripcion direccion, cnt.id_subdireccion_servicio, subd.descripcion subdireccion, cnt.id_depto_servicio, dep.descripcion departamento,
       cnt.id_nivel_servicio, cnt.id_seccion_servicio,cnt.id_status,stacnt.descripcion AS estado, cd.archivo
       FROM dbo.xxx_rrhh_empleado_persona e LEFT JOIN
       dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = e.id_empleado LEFT JOIN
       dbo.tbl_catalogo_detalle cat ON cnt.id_categoria = cat.id_item LEFT JOIN
       dbo.tbl_catalogo_detalle stacnt ON cnt.id_status = stacnt.id_item LEFT JOIN
       dbo.tbl_catalogo_detalle pto ON cnt.id_puesto_servicio = pto.id_item LEFT JOIN
       dbo.rrhh_direcciones sec ON cnt.id_secretaria_servicio = sec.id_direccion LEFT JOIN
       dbo.rrhh_direcciones subs ON cnt.id_subsecretaria_servicio = subs.id_direccion LEFT JOIN
       dbo.rrhh_direcciones dir ON cnt.id_direccion_servicio = dir.id_direccion LEFT JOIN
       dbo.rrhh_subdirecciones subd ON cnt.id_direccion_servicio = subd.id_direccion AND cnt.id_subdireccion_servicio = subd.id_subdireccion LEFT JOIN
       dbo.rrhh_departamentos dep ON cnt.id_depto_servicio = dep.id_departamento
       LEFT JOIN  (SELECT T.archivo, T.reng_num, T.correlativo
         FROM
          (
            SELECT archivo, reng_num, correlativo,  ROW_NUMBER() OVER (PARTITION BY reng_num ORDER BY correlativo DESC) AS rnk FROM rrhh_empleado_contrato_detalle a
            --WHERE   id_status IN (1210,891)
          ) T
          WHERE T.rnk = 1
        ) AS cd ON cd.reng_num = cnt.reng_num
        WHERE
        e.id_persona=?
        ORDER BY cnt.reng_num DESC, cnt.tipo_contrato DESC
      ";
      //fin
    }

    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $contratos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $contratos;

  }

  function get_contrato_activo($id_empleado){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 a.reng_num, a.id_empleado,a.id_status
             FROM rrhh_empleado_contratos a
             WHERE a.id_empleado=? AND a.id_status=? ORDER BY a.reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_empleado,908));
    $asignacion_actual=$q0->fetch();
    Database::disconnect_sqlsrv();
    return $asignacion_actual;
  }

  function get_empleado_contrato_actual($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 a.nro_contrato, a.fecha_contrato, a.reng_num, a.id_status AS emp_estado, a.fecha_acuerdo_resicion, a.fecha_inicio,a.fecha_finalizacion,
                   c.descripcion_corta AS renglon, c.descripcion
            FROM rrhh_empleado_contratos a
            INNER JOIN rrhh_empleado b ON a.id_empleado=b.id_empleado
            LEFT JOIN tbl_catalogo_detalle c ON a.tipo_contrato=c.id_item
            WHERE b.id_persona=?
            ORDER BY a.reng_num DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $plaza = $p->fetch();
    Database::disconnect_sqlsrv();
    return $plaza;
  }

  function get_contrato_por_asignacion($id_reng_num){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.tipo_contrato,a.nro_contrato, a.fecha_contrato, a.reng_num, a.id_status AS emp_estado,
            a.nro_acuerdo_aprobacion,a.fecha_acuerdo_aprobacion,a.fecha_acuerdo_resicion, a.fecha_inicio,a.fecha_finalizacion,
            c.descripcion_corta AS renglon, c.descripcion,
            a.monto_contrato,a.monto_mensual,a.id_puesto_servicio,a.id_nivel_servicio,a.id_secretaria_servicio,
            a.id_subsecretaria_servicio,a.id_direccion_servicio,a.id_subdireccion_servicio,
            a.id_depto_servicio,a.id_seccion_servicio,a.id_categoria,a.id_puesto_funcional,a.observaciones, a.id_tipo_servicio
            FROM rrhh_empleado_contratos a
            INNER JOIN rrhh_empleado b ON a.id_empleado=b.id_empleado
            LEFT JOIN tbl_catalogo_detalle c ON a.tipo_contrato=c.id_item
            WHERE a.reng_num=?
            ORDER BY a.reng_num DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_reng_num));
    $plaza = $p->fetch();
    Database::disconnect_sqlsrv();
    return $plaza;
  }
}
?>
