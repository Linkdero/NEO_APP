<?php
class alimentos {
  function get_empleados_asignacionesGen(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT * from app_alimentos.dbo.XXX_Asignacion_Alimentos order by nombre";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleados_asignacionesxDir($secre,$subsecre,$direc){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT a.id_persona, a.nombre, a.desayuno, a.almuerzo, a.cena
    from app_alimentos.dbo.XXX_Asignacion_Alimentos a
      left outer join saas_app.dbo.xxx_rrhh_ficha f on a.id_persona = f.id_persona
    WHERE f.estado=1 and f.id_secre=? and f.id_subsecre=? and f.id_direc=?";

    $p = $pdo->prepare($sql);
    $p->execute(array($secre,$subsecre,$direc));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleados_excepcionesGen(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT q1.id_persona, q1.nombre, q1.fecha1, q1.fecha2, q1.id_secre, q1.id_subsecre, q1.id_direc,
          case when q2.id_persona2 is null then 0 else 1 end tieneex from
          (SELECT v.emp_id id_persona, f.nombre, f.id_secre, f.id_subsecre, f.id_direc,
          convert(varchar,v.vac_fch_ini,105) fecha1, convert(varchar,v.vac_fch_fin,105) fecha2
          FROM app_vacaciones.dbo.vacaciones v left outer join
          saas_app.dbo.xxx_rrhh_ficha f on v.emp_id = f.id_persona
          WHERE (getdate() between v.vac_fch_ini AND v.vac_fch_fin) and v.est_id = 5 ) q1
          left join
          (select distinct a.id_persona id_persona2
          from app_alimentos.dbo.xxx_Excepciones_alimentos a
          where convert(date, getdate()) between a.fecha1 AND a.fecha2 ) q2 on q1.id_persona = q2.id_persona2
          order by q1.nombre";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleados_excepcionesxDir($secre,$subsecre,$direc){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT q1.id_persona, q1.nombre, q1.fecha1, q1.fecha2, q1.id_secre, q1.id_subsecre, q1.id_direc,
          case when q2.id_persona2 is null then 0 else 1 end tieneex from
          (SELECT v.emp_id id_persona, f.nombre, f.id_secre, f.id_subsecre, f.id_direc,
          convert(varchar,v.vac_fch_ini,105) fecha1, convert(varchar,v.vac_fch_fin,105) fecha2
          FROM app_vacaciones.dbo.vacaciones v left outer join
          saas_app.dbo.xxx_rrhh_ficha f on v.emp_id = f.id_persona
          WHERE (getdate() between v.vac_fch_ini AND v.vac_fch_fin) and v.est_id = 5 and
          f.id_secre = ? and id_subsecre=? and id_direc=? ) q1
          left join
          (select distinct a.id_persona id_persona2
          from app_alimentos.dbo.xxx_Excepciones_alimentos a
          where convert(date, getdate()) between a.fecha1 AND a.fecha2 ) q2 on q1.id_persona = q2.id_persona2
          order by q1.nombre ";
    $p = $pdo->prepare($sql);
    $p->execute(array($secre,$subsecre,$direc));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_excepciones_by_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT fecha1, fecha2, desayuno, almuerzo, cena, id_excepcion, id_persona, observaciones
    from app_alimentos.dbo.xxx_Excepciones_alimentos where id_persona = ? order by fecha2 desc, fecha1";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $excep = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $excep;
  }

  function get_dir_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT f.id_persona, a.id_tipo_usuario tipo_user,
      a.id_sistema, f.id_secre, f.id_subsecre, f.id_direc, f.id_dirf
      from saas_app.dbo.xxx_rrhh_ficha f
      left outer join saas_app.dbo.tbl_accesos_usuarios a on f.id_persona=a.id_persona
      where f.id_persona = ? and id_sistema=7844";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $id_dir = $p->fetch();
    Database::disconnect_sqlsrv();
    return $id_dir;
  }

  function get_repo_alimentos_por_fecha($ini,$fin,$direccion,$comedor){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="SELECT q1.fecha,
    sum(q1.desayuno) desayuno, sum(q1.almuerzo) almuerzo, sum(q1.cena) cena
    from (
    select convert(varchar,c.fecha_hora,23) fecha,
      CASE WHEN c.id_comida = 1 THEN 1 ELSE 0 END AS desayuno,
      CASE WHEN c.id_comida = 2 THEN 1 ELSE 0 END AS almuerzo,
      CASE WHEN c.id_comida = 3 THEN 1 ELSE 0 END AS cena
      from app_alimentos.dbo.control_Comedor c ";

      if($comedor==0){  // todos los comedores

      }else{        // cocina 1 o cocina 2
        $sql.="left outer join app_alimentos.dbo.UserComedor u on c.usuario = u.usuario " ;
      }

      $sql.="where CONVERT(varchar,c.fecha_hora,23) between ? and ?  ";

      if($direccion==0){  // administrador

      }else{        // operador
        $sql.="AND c.id_direccion=".$direccion;
      }

      if($comedor==0){  // Todos

      }else{        // cocina 1 o cocina 2
        $sql.="AND u.idusuario = ".$comedor;
      }

    $sql.=") q1 group by q1.fecha order by fecha ";

    $p = $pdo->prepare($sql);
    $p->execute(array($ini,$fin));
    $comidas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $comidas;
  }

  function get_repo_alimentos_por_direccion($ini,$fin,$direccion,$comedor){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="SELECT q1.direccion,
    sum(q1.desayuno) desayuno, sum(q1.almuerzo) almuerzo, sum(q1.cena) cena
    from (
    select isnull(d.descripcion,'S/D') direccion,
      CASE WHEN c.id_comida = 1 THEN 1 ELSE 0 END AS desayuno,
      CASE WHEN c.id_comida = 2 THEN 1 ELSE 0 END AS almuerzo,
      CASE WHEN c.id_comida = 3 THEN 1 ELSE 0 END AS cena
      from app_alimentos.dbo.control_Comedor c
			left outer join SAAS_APP.dbo.rrhh_direcciones d on c.id_direccion = d.id_direccion ";

    if($comedor==0){  // todos los comedores

    }else{        // cocina 1 o cocina 2
      $sql.="left outer join app_alimentos.dbo.UserComedor u on c.usuario = u.usuario " ;
    }

    $sql.="where CONVERT(varchar,c.fecha_hora,23) between ? and ? ";

    if($comedor==0){  // Todos

    }else{        // cocina 1 o cocina 2
      $sql.="AND u.idusuario = ".$comedor;
    }

    $sql.=" ) q1 group by q1.direccion order by direccion ";


    $p = $pdo->prepare($sql);
    $p->execute(array($ini,$fin));
    $comidas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $comidas;
  }

  function get_repo_alimentos_por_colaborador($ini,$fin,$direccion,$comedor){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="SELECT q1.direccion, q1.nombre,
    sum(q1.desayuno) desayuno, sum(q1.almuerzo) almuerzo, sum(q1.cena) cena
    from (
    select isnull(d.descripcion,'S/D') direccion,
	  f.nombre,
      CASE WHEN c.id_comida = 1 THEN 1 ELSE 0 END AS desayuno,
      CASE WHEN c.id_comida = 2 THEN 1 ELSE 0 END AS almuerzo,
      CASE WHEN c.id_comida = 3 THEN 1 ELSE 0 END AS cena
      from app_alimentos.dbo.control_Comedor c
			left outer join SAAS_APP.dbo.rrhh_direcciones d on c.id_direccion = d.id_direccion
			left outer join (select distinct id_persona, nombre, id_dirg from SAAS_APP.dbo.xxx_rrhh_Ficha) f on c.id_persona = f.id_persona ";

      if($comedor==0){  // todos los comedores

      }else{        // cocina 1 o cocina 2
        $sql.="left outer join app_alimentos.dbo.UserComedor u on c.usuario = u.usuario " ;
      }

      $sql.="where CONVERT(varchar,c.fecha_hora,23) between ? and ?  ";

      if($direccion==0){  // administrador

      }else{        // operador
        $sql.="AND c.id_direccion=".$direccion;
      }

      if($comedor==0){  // Todos

      }else{        // cocina 1 o cocina 2
        $sql.="AND u.idusuario = ".$comedor;
      }

      $sql.=") q1 group by q1.direccion, q1.nombre order by q1.direccion, q1.nombre";

    $p = $pdo->prepare($sql);
    $p->execute(array($ini,$fin));
    $comidas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $comidas;
  }


  function get_repo_alimentosxDir($direccion){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT * from app_alimentos.dbo.XXX_Asignacion_Alimentos where id_dir = ? order by nombre";
    $p = $pdo->prepare($sql);
    $p->execute(array($direccion));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_direcciones(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_direccion id_dir, descripcion des_dir FROM rrhh_direcciones where id_tipo = 887 and id_nivel = 4";
    $p = $pdo->prepare($sql);
    $p->execute();
    $rrhh_direcciones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $rrhh_direcciones;
  }

  function get_des_direccion($direccion){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_direccion id_dir, descripcion des_dir FROM rrhh_direcciones where id_direccion = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($direccion));
    $des_direccion = $p->fetch();
    Database::disconnect_sqlsrv();
    return $des_direccion;
  }

  function get_empleados_con_accesos(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,b.conteo,
            c.descripcion,
            CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
            (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND
            a.id_status IN (2312,1030)) THEN 1 ELSE 0 END AS estado_persona

            FROM rrhh_persona a
            LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_tipo_Empleado_acceso($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select f.id_persona, f.id_dirf, a.id_tipo_usuario tipo_user, a.id_sistema
        from saas_app.dbo.xxx_rrhh_ficha f
        left outer join tbl_accesos_usuarios a on f.id_persona=a.id_persona
        where f.id_persona = ? and id_sistema=7844";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $acceso = $p->fetch();
    Database::disconnect_sqlsrv();
    return $acceso;
  }

  function get_empleados_busqueda(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre, a.tercer_nombre, a.primer_apellido, a.segundo_apellido,a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,b.conteo,
            c.descripcion
            FROM rrhh_persona a
            LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute();
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleado_by_id($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre, a.tercer_nombre, a.primer_apellido, a.segundo_apellido,a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,b.conteo,
            c.descripcion,
            d.id_fotografia,d.id_tipo_fotografia,d.fotografia_principal,d.fotografia,
            d.descripcion,d.id_auditoria,e.profesion,
            f.descripcion AS genero,h.descripcion AS procedencia,
            CASE WHEN  f.descripcion='FEMENINO'  AND a.id_estado_civil IN (1,2,3,4,6,5635) THEN REPLACE(g.descripcion, RIGHT(g.descripcion, 1), 'A') ELSE g.descripcion  END
            AS estado_civil, i.descripcion AS tipo_personal,
            k.descripcion AS religion, j.fecha_nacimiento,
            l.nombre AS municipio, m.nombre AS departamento,
            o.descripcion AS tipo_contrato,n.observaciones as emp_observaciones

            FROM rrhh_persona a
            LEFT JOIN (SELECT id_persona, COUNT(*) as conteo
                       FROM tbl_accesos_usuarios
                       GROUP BY id_persona) AS b ON b.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            LEFT JOIN rrhh_persona_fotografia d ON d.id_persona=a.id_persona
            LEFT JOIN (SELECT TOP 1 b.descripcion AS profesion, b.id_item,
                       a.id_titulo_obtenido, a.fecha_titulo, a.nro_colegiado,
                       a.id_persona
                       FROM rrhh_persona_escolaridad a
                       INNER JOIN tbl_catalogo_detalle b ON a.id_titulo_obtenido=b.id_item
                       WHERE a.id_persona=?
                       ORDER BY a.id_escolaridad DESC
                       ) AS e ON e.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle f ON a.id_genero=f.id_item
            LEFT JOIN tbl_catalogo_detalle g ON a.id_estado_civil=g.id_item
            LEFT JOIN tbl_catalogo_detalle h ON a.id_procedencia=h.id_item
            LEFT JOIN tbl_catalogo_detalle i ON a.id_tipo_servicio=i.id_item
            LEFT JOIN rrhh_persona_complemento j ON j.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle k ON j.id_religion=k.id_item
            LEFT JOIN tbl_municipio l ON j.id_muni_nacimiento=l.id_municipio
            LEFT JOIN tbl_departamento m ON j.id_depto_nacimiento=m.id_departamento
            LEFT JOIN rrhh_empleado n ON n.id_persona=a.id_persona
            LEFT JOIN tbl_catalogo_detalle o ON n.id_contrato=o.id_item
            WHERE a.id_persona=?
            ORDER BY a.id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,$id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }

  static function get_empleado_fotografia($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 id_persona,id_fotografia,id_tipo_fotografia,fotografia_principal,fotografia,
                   descripcion,id_auditoria
            FROM rrhh_persona_fotografia
            WHERE id_persona=? ORDER BY id_fotografia DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $foto = $p->fetch();
    Database::disconnect_sqlsrv();
    return $foto;
  }

  function get_direcciones_by_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_persona,a.id_direccion,a.id_tipo_referencia, e.descripcion AS referencia,
                   a.nro_calle_avenida, a.calle_tope,d.descripcion AS tipo_calle_desc,a.flag_actual,
                   a.nro_casa,a.zona,b.nombre AS municipio, c.nombre AS departamento, f.descripcion AS lugar

            FROM rrhh_persona_direcciones a
            LEFT JOIN tbl_municipio b ON a.id_muni=b.id_municipio
            LEFT JOIN tbl_departamento c ON a.id_depto=c.id_departamento
            LEFT JOIN tbl_catalogo_detalle d ON a.tipo_calle=d.id_item
            LEFT JOIN tbl_catalogo_detalle e ON a.id_tipo_referencia=e.id_item
            LEFT JOIN tbl_catalogo_detalle f ON a.id_aldea=f.id_item
            WHERE id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $direcciones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $direcciones;
  }

  function get_telefonos_by_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_persona, a.id_telefono,a.flag_privado,a.flag_principal,
                   a.tipo,b.descripcion AS tipo_desc,a.id_tipo_telefono AS tipo_telefono,
                   a.nro_telefono


            FROM rrhh_persona_telefonos a
            LEFT JOIN tbl_catalogo_detalle b ON a.tipo=b.id_item
            WHERE a.id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $telefonos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $telefonos;
  }

  function get_empleado_by_id_ficha($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT *
            FROM xxx_rrhh_Ficha
            WHERE id_persona=?
            ORDER BY id_persona ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,$id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }
  static function get_empleados_ficha(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT *
            FROM xxx_rrhh_Ficha
            WHERE estado=?
            ORDER BY primer_apellido ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array(1));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleado_estado_by_id($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,a.tipo_persona,a.fecha_ingreso,a.fecha_modificacion,a.id_status,
            a.NISP,a.nit,a.afiliacion_IGSS,
            a.correo_electronico,a.id_estado_civil,a.id_profesion,a.observaciones,
            a.id_tipo_servicio,a.id_genero,a.id_procedencia,a.id_auditoria,
            b.descripcion,
            CASE WHEN NOT emp.id_status IN (892,893,1012,1032,1033,1034,1035,1083,3727,3733,3734,3735,3805,5610,7349,7350) OR
            (emp.id_status IS NULL AND a.tipo_persona IN (1052,1164) AND
            a.id_status IN (2312,1030)) THEN 1 ELSE 0 END AS estado_persona
            FROM rrhh_persona a
            INNER JOIN tbl_catalogo_detalle b ON a.id_status=b.id_item
            LEFT JOIN rrhh_empleado emp ON emp.id_persona=a.id_persona
            WHERE a.id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $empleado = $p->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }

  static function get_direcciones_saas_by_id($id){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT id_direccion, id_nivel, id_superior, id_tipo, descripcion, descripcion_corta
            FROM rrhh_direcciones
            WHERE id_direccion=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id));
    $direccion = $p->fetch();
    Database::disconnect_sqlsrv();
    return $direccion;
  }

  static function get_plazas_estado(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  CASE WHEN c.id_contrato = 7 THEN '011' ELSE -- Renglon 011
                    CASE WHEN c.id_contrato = 1075 AND cnt.tipo_contrato = 8 THEN '031' ELSE -- Renglon 031
                    CASE WHEN c.id_contrato = 1075 and cnt.tipo_contrato = 9 then '029' ELSE -- Renglon 029
                    ' ' END END END AS Renglon, A.id_plaza, A.cod_plaza, A.codigo_puesto_oficial, A.partida_presupuestaria, A.Cod_estado AS cod_estado_plaza, A.Estado AS estado_plaza, A.descripcion AS descripcion_plaza, A.reng_num AS reng_num_plaza,
                         A.codigo_puesto_presupuestario, A.fecha_modificacion, A.id_jerarquia_presupuestario, A.nivel_presupuestario, A.nivel_presupuestario_ubicacion, A.nivel_presupuestario_superior, A.nombre_nivel_presupuestario,
                         COALESCE (A.nombre_seccion_presupuestaria, A.nombre_depto_presupuestario, A.nombre_subdireccion_presupuestaria, A.nombre_direccion_presupuestaria, A.nombre_subsecretaria_presupuestario,
                         A.nombre_secretaria_presupuestario) AS nombre_ubicacion_presupuestaria, A.id_secretaria_presupuestario, A.nombre_secretaria_presupuestario, A.id_subsecretaria_presupuestaria, A.nombre_subsecretaria_presupuestario,
                         A.id_direccion_presupuestaria, A.nombre_direccion_presupuestaria, A.id_subdireccion_presupuestaria, A.nombre_subdireccion_presupuestaria, A.id_depto_presupuestario, A.nombre_depto_presupuestario,
                         A.id_seccion_presupuestario, A.nombre_seccion_presupuestaria, A.Puesto_presupuestario, A.id_jerarquia_funcional, A.nivel_funcional, A.nivel_funcional_ubicacion, A.nivel_funcional_superior, A.nombre_nivel_funcional,
                         COALESCE (A.nombre_seccion_funcional, A.nombre_depto_funcional, A.nombre_subdireccion_funcional, A.nombre_direccion_funcional, A.nombre_subsecretaria_funcional, A.nombre_secretaria_funcional)
                         AS nombre_ubicacion_funcional, A.id_secretaria_funcional, A.nombre_secretaria_funcional, A.id_subsecretaria_funcional, A.nombre_subsecretaria_funcional, A.id_direccion_funcional, A.nombre_direccion_funcional,
                         A.id_subdireccion_funcional, A.nombre_subdireccion_funcional, A.id_depto_funcional, A.nombre_depto_funcional, A.id_seccion_funcional, A.nombre_seccion_funcional, A.id_puesto_funcional, A.nombre_puesto_funcional,
                         ISNULL(C.id_empleado, 0) AS id_empleado, ISNULL(C.nro_empleado, 0) AS nro_empleado, ISNULL(C.id_persona, 0) AS id_persona, ISNULL(C.tipo_persona, 0) AS tipo_persona, ISNULL(C.nombre_tipoPersona, '')
                         AS nombre_tipoPersona, C.Nombre_Completo, ISNULL(C.primer_nombre, '') AS primer_nombre, ISNULL(C.segundo_nombre, '') AS segundo_nombre, ISNULL(C.tercer_nombre, '') AS tercer_nombre, ISNULL(C.primer_apellido, '')
                         AS primer_apellido, ISNULL(C.segundo_apellido, '') AS segundo_apellido, ISNULL(C.tercer_apellido, '') AS tercer_apellido,
                          c.id_contrato, A.id_sueldo_plaza, Ps.monto_sueldo_plaza, Pv.monto_sueldo_base, B.fecha_toma_posesion
              FROM  dbo.xxx_rrhh_hst_plazas AS A
              LEFT OUTER JOIN dbo.rrhh_empleado_plaza AS B ON A.id_plaza = B.id_plaza AND B.id_status = 891
              LEFT OUTER JOIN dbo.xxx_rrhh_empleado_persona AS C ON C.id_empleado = B.id_empleado
              LEFT OUTER JOIN dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = c.id_empleado AND cnt.id_status = 908
              LEFT OUTER JOIN  -- contratos ACTIVOS (029 Y 031)
                (SELECT A.id_sueldo AS id_sueldo_plaza, SUM(B.monto_p) AS monto_sueldo_plaza
                  FROM  dbo.rrhh_plazas_sueldo AS A
                  INNER JOIN dbo.rrhh_plazas_sueldo_detalle AS B ON A.id_sueldo = B.id_sueldo
                  LEFT OUTER JOIN dbo.rrhh_plazas_sueldo_conceptos AS C ON B.id_concepto = C.id_concepto
                  WHERE (A.actual = 1)
                  GROUP BY A.id_sueldo) AS Ps ON Ps.id_sueldo_plaza = A.id_sueldo_plaza
                  LEFT OUTER JOIN (SELECT A.id_sueldo AS id_sueldo_plaza, SUM(B.monto_p) AS monto_sueldo_base
                                    FROM dbo.rrhh_plazas_sueldo AS A
                                    INNER JOIN dbo.rrhh_plazas_sueldo_detalle AS B ON A.id_sueldo = B.id_sueldo
                                    LEFT OUTER JOIN dbo.rrhh_plazas_sueldo_conceptos AS C ON B.id_concepto = C.id_concepto
                                    WHERE (A.actual = 1) AND (C.bln_aplica_viatico = 1)
                                    GROUP BY A.id_sueldo) AS Pv ON Pv.id_sueldo_plaza = A.id_sueldo_plaza
                                    WHERE (A.flag_ubicacion_actual = 1)
                                    ORDER BY Renglon";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $plazas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plazas;
  }

  static function get_empleados_por_direccion_funcional(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT a.id_persona, a.nombre, a.p_funcional, a.p_nominal, a.fecha_ingreso,
            CASE
            WHEN a.id_tipo=2 THEN '031' WHEN a.id_tipo=3 THEN '029' ELSE '011' END AS renglon, a.estado,
            a.id_tipo,a.dir_nominal,a.dir_funcional,
            CASE
            WHEN a.id_tipo=2 THEN a.dir_funcional WHEN a.id_tipo=4 THEN 'APOYO' ELSE a.dir_nominal END AS direccion,
            sd.SUELDO, f.fecha_toma_posesion, f.fecha_efectiva_resicion,f.id_status
                        FROM xxx_rrhh_Ficha a
            LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza

            LEFT JOIN (SELECT a.id_persona,
            SUM(c.monto_p) AS SUELDO
                        FROM xxx_rrhh_Ficha a
            LEFT JOIN rrhh_plazas_sueldo b ON b.id_plaza=a.id_plaza
            LEFT JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo

                        WHERE  actual =1

            group by a.id_persona) AS sd ON sd.id_persona=a.id_persona

            LEFT JOIN rrhh_persona_fotografia d ON d.id_persona=a.id_persona
			LEFT JOIN rrhh_empleado e ON a.id_persona=e.id_persona
			LEFT JOIN rrhh_empleado_plaza f ON f.id_empleado=e.id_empleado

            WHERE a.estado=1 AND f.id_status=891 ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  static function get_plaza_historial($partida){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_plaza, a.partida_presupuestaria, b.fecha_toma_posesion, b.fecha_efectiva_resicion, d.primer_nombre, d.segundo_nombre, d.tercer_nombre, d.primer_apellido, d.segundo_apellido, d.tercer_apellido
            FROM rrhh_plaza a
            INNER JOIN rrhh_empleado_plaza b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_empleado c ON b.id_empleado=c.id_empleado
            INNER JOIN rrhh_persona d ON c.id_persona=d.id_persona
            WHERE a.id_plaza=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($partida));
    $plaza = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plaza;
  }

  static function get_plazas_por_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_plaza, a.partida_presupuestaria, b.fecha_toma_posesion, b.fecha_efectiva_resicion, d.primer_nombre, d.segundo_nombre, d.tercer_nombre, d.primer_apellido, d.segundo_apellido, d.tercer_apellido,
e.descripcion AS puesto, SUELDO
            FROM rrhh_plaza a
            INNER JOIN rrhh_empleado_plaza b ON b.id_plaza=a.id_plaza
            INNER JOIN rrhh_empleado c ON b.id_empleado=c.id_empleado
            INNER JOIN rrhh_persona d ON c.id_persona=d.id_persona
			LEFT JOIN rrhh_plazas_puestos e ON a.id_puesto=e.id_puesto
			LEFT JOIN (SELECT b.id_plaza,
            SUM(c.monto_p) AS SUELDO
                        FROM rrhh_plazas_sueldo b
            LEFT JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
			WHERE b.actual=1


            group by b.id_plaza) AS sd ON sd.id_plaza=a.id_plaza
            WHERE d.id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $plazas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $plazas;
  }

  function save_employee_income($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_control_ingreso(id_persona, id_usuario, fecha, id_punto, ip_address) VALUES(?,?,GETDATE(),1,?)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($id_persona, $_SESSION["id_persona"], $_SERVER['REMOTE_ADDR']))){
      $response = array(
        "msg" => "ok",
        "status" => 200
      );
    }else{
      $response = array(
        "msg" => "error",
        "status" => 400
      );
    }
    Database::disconnect_sqlsrv();
  }
}

?>
