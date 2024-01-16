<?php
class viaticos{

  function get_all_solicitudes($tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.vt_nombramiento, a.fecha, a.id_rrhh_direccion, b.descripcion AS direccion,
            c.descripcion AS estado, d.nombre as municipio, e.nombre as departamento, f.nombre AS pais,
            a.fecha_salida, a.fecha_regreso,a.motivo, a.id_status,a.id_pais, a.descripcion_lugar
            FROM vt_nombramiento a
            INNER JOIN rrhh_direcciones b ON a.id_rrhh_direccion=b.id_direccion
            INNER JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            INNER JOIN tbl_municipio d ON a.id_municipio = d.id_municipio
            INNER JOIN tbl_departamento e ON d.id_departamento = e.id_departamento
            INNER JOIN tbl_pais f ON e.id_pais=f.id_pais
            ";
    if($tipo==2){
      $sql.="WHERE a.id_status IN (932,933,935,936,937,938,939,7959)";
    }else
    if($tipo==3)
    {
      $current_year=date('Y-m-d');
      $last_year=strtotime ( '-1 year' , strtotime ( date('Y-m-d') ) ) ;
      $last_year=date('Y-m-d',$last_year);

      $sql.="WHERE convert(varchar, a.fecha, 23) BETWEEN '".$last_year."' AND '".$current_year ."'";
    }
    $sql.="ORDER BY a.vt_nombramiento DESC";
    //echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nombramientos = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $nombramientos;
  }

  function get_all_solicitudes_by_direccion($direccion,$tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.vt_nombramiento, a.fecha, a.id_rrhh_direccion, b.descripcion AS direccion,
            c.descripcion AS estado, d.nombre as municipio, e.nombre as departamento, f.nombre AS pais,
            a.fecha_salida, a.fecha_regreso,a.motivo, a.id_status,a.id_pais, a.descripcion_lugar
            FROM vt_nombramiento a
            INNER JOIN rrhh_direcciones b ON a.id_rrhh_direccion=b.id_direccion
            INNER JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
            INNER JOIN tbl_municipio d ON a.id_municipio = d.id_municipio
            INNER JOIN tbl_departamento e ON d.id_departamento = e.id_departamento
            INNER JOIN tbl_pais f ON e.id_pais=f.id_pais
            WHERE a.id_rrhh_direccion=?

            ";
            if($tipo==2){
              $sql.=" AND a.id_status IN (932,933,935,936,937,938,939,7959)";
            }else
            if($tipo==3)
            {
              $current_year=date('Y-m-d');
              $last_year=strtotime ( '-1 year' , strtotime ( date('Y-m-d') ) ) ;
              $last_year=date('Y-m-d',$last_year);

              $sql.=" AND convert(varchar, a.fecha, 23) BETWEEN '".$last_year."' AND '".$current_year ."'";
            }
            $sql.="ORDER BY a.vt_nombramiento DESC";

    //echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($direccion));
    $nombramientos = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $nombramientos;
  }

  static function get_paises(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_pais, nombre
            FROM tbl_pais
            WHERE id_auditoria = 0 AND id_pais<>?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array('GT','GT'))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_departamentos($pais){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_departamento, nombre
            FROM tbl_departamento
            WHERE id_pais = ? AND id_auditoria = 0;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($pais))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_municipios($id_departamento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_municipio, nombre
            FROM tbl_municipio
            WHERE id_departamento = ?;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($id_departamento))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_aldeas($id_municipio){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_aldea, nombre
            FROM tbl_aldea
            WHERE id_municipio = ?;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($id_municipio))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_all_empleados_asignar($direccion){
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

            WHERE a.estado=1 AND f.id_status=891
            ";
            if($direccion>0){
              $sql.=" AND a.id_dirf=$direccion";
            }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nombramientos = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $nombramientos;
  }

  static function get_items($id_catalogo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_item, descripcion, descripcion_corta
            FROM tbl_catalogo_detalle
            WHERE id_catalogo = ?;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($id_catalogo))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_funcionarios(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_persona
          ,a.reng_num
          ,a.id_periodo
          ,a.id_institucion
          ,a.id_puesto_funcionario
          ,a.bln_presta_servicio
          ,a.bln_genera_viaticos
          ,a.bln_ubicacion_actual
          ,a.fecha_asignacion
          ,a.id_auditoria,
          b.primer_nombre, b.segundo_nombre, b.tercer_nombre, b.primer_apellido, b.segundo_apellido, b.tercer_apellido
      FROM rrhh_persona_funcionario AS a
      INNER JOIN rrhh_persona AS b ON a.id_persona=b.id_persona
      WHERE a.bln_genera_viaticos=?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array(1))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_direcciones(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_direccion,id_nivel, id_superior, id_tipo,descripcion, descripcion_corta, id_auditoria
      FROM rrhh_direcciones
      WHERE id_tipo=?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array(888))){
      $response = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    }else{
      $response = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }
    Database::disconnect_sqlsrv();
    return $response;
  }
  static function get_estado_viatico($opcion){
    $paramentros;
    if($opcion==1){
      $parametros='(933,934)';
    }
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_item, id_status, descripcion
      FROM tbl_catalogo_detalle
      WHERE id_item in $parametros";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array());
      $s = $stmt->fetchAll();
      Database::disconnect_sqlsrv();
      return $s;
  }
  static function get_estado_by_id($vt_nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_status FROM vt_nombramiento
    				WHERE vt_nombramiento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vt_nombramiento));
    $s = $stmt->fetch();
    Database::disconnect_sqlsrv();
    return $s;
  }

  static function get_solicitud_by_id($id_solicitud){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.vt_nombramiento, a.fecha, a.id_rrhh_direccion, b.descripcion AS direccion,
    				c.descripcion AS estado, d.nombre as municipio, e.nombre as departamento, f.nombre AS pais,
    				a.fecha_salida, a.fecha_regreso,a.motivo, a.id_status,
					a.usr_solicita, g.primer_nombre, g.segundo_nombre, g.primer_apellido, g.tercer_apellido,
					h.primer_nombre, h.segundo_nombre, h.primer_apellido, h.segundo_apellido,
          hi.descripcion AS hora_ini, hf.descripcion AS hora_fin, hi.descripcion_corta AS hora_i, hf.descripcion_corta AS hora_f,
          vt_detalle.personas, a.id_pais, f.id_grupo, a.hora_regreso,
          fun.primer_nombre AS f_pn, fun.segundo_nombre AS f_sn, fun.primer_apellido AS f_pa, fun.segundo_apellido AS f_sa, a.tipo_cambio,
          ISNULL(vt_constancia.personas_c,0) AS personas_c,
          ISNULL(vt_liquidacion.personas_l,0) AS personas_l,
		  liquidacion_gt.liquidado_gt,
      a.descripcion_lugar, a.bln_hospedaje,a.bln_alimentacion
    				FROM vt_nombramiento a
    				INNER JOIN rrhh_direcciones b ON a.id_rrhh_direccion=b.id_direccion
    				INNER JOIN tbl_catalogo_detalle c ON a.id_status=c.id_item
    				INNER JOIN tbl_municipio d ON a.id_municipio = d.id_municipio
    				INNER JOIN tbl_departamento e ON d.id_departamento = e.id_departamento
    				INNER JOIN tbl_pais f ON e.id_pais=f.id_pais
					LEFT JOIN rrhh_persona g ON a.usr_solicita=g.id_persona
					LEFT JOIN rrhh_persona h ON a.usr_autoriza=h.id_persona
          LEFT JOIN tbl_catalogo_detalle hi ON a.hora_salida=hi.id_item
          LEFT JOIN tbl_catalogo_detalle hf ON a.hora_regreso=hf.id_item
          LEFT JOIN rrhh_persona fun ON a.funcionario=fun.id_persona
          LEFT JOIN (
                      SELECT vt_nombramiento, COUNT(vt_nombramiento) AS personas
                      FROM vt_nombramiento_detalle
                      WHERE bln_confirma=1
                      GROUP BY vt_nombramiento
                    )AS vt_detalle ON vt_detalle.vt_nombramiento=a.vt_nombramiento

					 LEFT JOIN (
                      SELECT vt_nombramiento, COUNT(vt_nombramiento) AS personas_c
                      FROM vt_nombramiento_detalle
                      WHERE bln_confirma=1 AND convert(varchar, fecha_salida_lugar, 23) = '1900-01-01'
                      GROUP BY vt_nombramiento
                    )AS vt_constancia ON vt_constancia.vt_nombramiento=a.vt_nombramiento

					LEFT JOIN (
                      SELECT vt_nombramiento, COUNT(vt_nombramiento) AS personas_l
                      FROM vt_nombramiento_detalle
                      WHERE bln_confirma=1 AND porcentaje_real = 0 AND nro_frm_vt_liq=0
                      GROUP BY vt_nombramiento
                    )AS vt_liquidacion ON vt_liquidacion.vt_nombramiento=a.vt_nombramiento
					LEFT JOIN (
					SELECT a.vt_nombramiento, SUM((a.porcentaje_real)*(a.monto_asignado/a.porcentaje_proyectado)+a.otros_gastos-a.monto_descuento_anticipo-(a.hospedaje+a.reintegro_alimentacion)) AS liquidado_gt
					FROM vt_nombramiento_detalle a WHERE a.vt_nombramiento=30139
					AND a.bln_confirma=1
					GROUP BY a.vt_nombramiento
					) AS liquidacion_gt ON liquidacion_gt.vt_nombramiento=a.vt_nombramiento

    				WHERE a.vt_nombramiento=?

    				";

    //echo $sql;

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_solicitud));
    $s = $stmt->fetch();
    Database::disconnect_sqlsrv();
    return $s;
  }

  function get_all_viaticos_by_pais($direccion,$tipo,$mes,$year){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT DISTINCT  vte.vt_nombramiento, vtd.id_empleado, RTRIM(LTRIM(ISNULL(per.primer_nombre, '')))
                      + CASE WHEN LEN(RTRIM(LTRIM(ISNULL(per.segundo_nombre, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(per.segundo_nombre, '')))
                      END + CASE WHEN LEN(RTRIM(LTRIM(ISNULL(per.tercer_nombre, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(per.tercer_nombre, '')))
                      END + CASE WHEN LEN(RTRIM(LTRIM(ISNULL(per.primer_apellido, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(per.primer_apellido, '')))
                      END + CASE WHEN LEN(RTRIM(LTRIM(ISNULL(per.segundo_apellido, '')))) = 0 THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(per.segundo_apellido, '')))
                      END + CASE WHEN LEN(RTRIM(LTRIM(ISNULL(per.tercer_apellido, '')))) = 0 THEN '' ELSE ' DE ' + RTRIM(LTRIM(ISNULL(per.tercer_apellido, ''))) END AS nombre,
                      dir.descripcion AS direccion, vte.fecha_salida, vtd.fecha_salida fecha_salida_real,
                      datediff(day,vte.fecha_salida,vtd.fecha_salida) dias,
                      vte.fecha_regreso, vtd.fecha_regreso fecha_regreso_real,
                      datediff(day,vte.fecha_regreso,vtd.fecha_regreso) dias_real,
                      pais.nombre AS pais, dep.nombre AS departamento, mun.nombre AS municipio, (420 * vtd.porcentaje_real) total,
                                    vtd.hospedaje+vtd.reintegro_alimentacion+vtd.otros_gastos reintegro,
                                    ( (420 * vtd.porcentaje_real) - (vtd.hospedaje+vtd.reintegro_alimentacion+vtd.otros_gastos) ) * vte.tipo_cambio gastos, vtd.monto_asignado,
                      --( (vtd.monto_asignado + vtd.otros_gastos) - (vtd.hospedaje + vtd.reintegro_alimentacion )) * vte.tipo_cambio AS calculo2,
                                    MONTH(vte.fecha_salida) AS mes, YEAR(vte.fecha_salida)
                      AS año, esc.monto, VTE.TIPO_CAMBIO, vte.id_pais, conteo.valor_mayor, vte.descripcion_lugar
                      FROM         dbo.vt_nombramiento AS vte LEFT OUTER JOIN
                      dbo.vt_nombramiento_detalle AS vtd ON vte.vt_nombramiento = vtd.vt_nombramiento LEFT OUTER JOIN
                      dbo.tbl_pais AS pais ON vte.id_pais = pais.id_pais LEFT OUTER JOIN
                      dbo.tbl_departamento AS dep ON vte.id_pais = dep.id_pais AND vte.id_departamento = dep.id_departamento LEFT OUTER JOIN
                      dbo.tbl_municipio AS mun ON vte.id_departamento = mun.id_departamento AND vte.id_municipio = mun.id_municipio LEFT OUTER JOIN
                      dbo.rrhh_direcciones AS dir ON vte.id_rrhh_direccion = dir.id_direccion LEFT OUTER JOIN
                      dbo.rrhh_persona AS per ON vtd.id_empleado = per.id_persona left outer join
                      dbo.tbl_escalas_grupo_categoria esc on pais.id_grupo = esc.id_grupo and esc.id_categoria=1053
					  LEFT JOIN (SELECT DISTINCT  COUNT(vte.vt_nombramiento) AS valor_mayor, vtd.id_empleado
                      FROM         dbo.vt_nombramiento AS vte LEFT OUTER JOIN
                      dbo.vt_nombramiento_detalle AS vtd ON vte.vt_nombramiento = vtd.vt_nombramiento LEFT OUTER JOIN
                      dbo.tbl_pais AS pais ON vte.id_pais = pais.id_pais LEFT OUTER JOIN

                      dbo.tbl_escalas_grupo_categoria esc on pais.id_grupo = esc.id_grupo and esc.id_categoria=1053
                      WHERE     (NOT (vtd.nro_frm_vt_liq = 0)) AND (vtd.monto_asignado > 0) and month(vte.fecha_salida) = ? and year(vte.fecha_salida) = ? ";
                      if($tipo==1){
                        $sql.=" AND vte.id_pais ='GT' ";
                      }else
                      if($tipo==2){
                        $sql.=" AND vte.id_pais<> 'GT' ";
                      }
                      if($direccion>0){
                        $sql.=" AND vte.id_rrhh_direccion=$direccion";
                      }

            $sql.="group by vtd.id_empleado) AS conteo ON conteo.id_empleado=vtd.id_empleado
                      WHERE     (NOT (vtd.nro_frm_vt_liq = 0)) AND (vtd.monto_asignado > 0) and month(vte.fecha_salida) = ? and year(vte.fecha_salida) = ?
					  ";
                      if($tipo==1){
                        $sql.=" AND vte.id_pais ='GT' ";
                      }else
                      if($tipo==2){
                        $sql.=" AND vte.id_pais<> 'GT' ";
                      }
                      if($direccion>0){
                        $sql.=" AND vte.id_rrhh_direccion=$direccion";
                      }



                      $sql.=" order by nombre, vte.vt_nombramiento, vte.fecha_salida ASC";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute(array($mes,$year,$mes,$year));
                      $s = $stmt->fetchAll();
                      Database::disconnect_sqlsrv();
                      return $s;
  }

  function get_nombramiento_by_id($id_nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT rd.descripcion,vn.fecha AS fecha_viatico,vn.fecha_salida, vn.fecha_regreso,
  		rd.descripcion_corta+'/' + REPLACE(STR(vn.nro_nombramiento, 4), SPACE(1), '0')+'-'+CAST(YEAR(vn.fecha) AS VARCHAR(100)) AS nombramiento_direccion,
  	YEAR(vn.fecha) AS anio	,
  	DAY(vn.fecha) AS dia,
  	DATENAME(MONTH, vn.fecha) AS mes,
  	CASE
  		WHEN VND.reng_sustituye = 0 THEN
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_nombre,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_nombre,''))) END +
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_nombre,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_nombre,''))) END +
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_nombre,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_nombre,''))) END +
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_apellido,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_apellido,''))) END +
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_apellido,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_apellido,''))) END +
  			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_apellido,'')))) = 0
  				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_apellido,''))) END
  		--ELSE
  		--	'<B> NO ASISTIO - </B>' +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_nombre,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_nombre,''))) END +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_nombre,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_nombre,''))) END +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_nombre,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_nombre,''))) END +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_apellido,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_apellido,''))) END +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_apellido,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_apellido,''))) END +
  		--	CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_apellido,'')))) = 0
  		--		THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_apellido,''))) END
  	END AS nombre_completo 	,
  	vnd.sueldo,
    CASE vn.funcionario WHEN 0 THEN 'Ninguno' ELSE ISNULL(rp1.primer_nombre, '')+' '+ISNULL(rp1.segundo_nombre, '')+' '+ISNULL(rp1.primer_apellido, '')+' '+ISNULL(rp1.segundo_apellido, '')+' '+ISNULL(rp1.tercer_apellido,'') END AS nombre_funcionario,
  	CASE vn.funcionario
  		WHEN 0 THEN 'Comision Oficial '+ISNULL(vn.motivo, '')
  		ELSE ISNULL(vn.motivo, '')+' '+ISNULL(rp1.primer_nombre, '')+' '+ISNULL(rp1.segundo_nombre, '')+' '+ISNULL(rp1.primer_apellido, '')+' '+ISNULL(rp1.segundo_apellido, '')+' '+ISNULL(rp1.tercer_apellido, '')
  	END AS motivo,
  	tm.nombre+'-'+td.nombre+'-'+tp.nombre AS nombre,
  	DAY(vn.fecha_salida) AS dia_salida,
  	DATENAME(MONTH, vn.fecha_salida) AS mes_salida,
  	DAY(vn.fecha_regreso) AS dia_regreso,
  	DATENAME(MONTH,vn.fecha_regreso) AS mes_regreso,
  	YEAR(vn.fecha_regreso) anio_regreso,
  	tcd1.descripcion hora_salida,
  	tcd2.descripcion hora_regreso,
    tcd1.descripcion_corta hora_i,
    tcd2.descripcion_corta hora_f,
  	CASE vn.bln_alimentacion
  		WHEN 1 THEN 'Alimentación'
  		WHEN 0 THEN ''
  	END AS alimentacion,
  	CASE vn.bln_hospedaje
  		WHEN 1 THEN 'Hospedaje'
  		WHEN 0 THEN ''
  	END AS hospedaje,
  	vn.Observaciones,
  	vn.vt_nombramiento,
  	CASE
  		WHEN len(rtrim(ltrim(vn.nro_nombramiento_relacionado))) = ' ' THEN ''
  		ELSE 'Ext.'+ vn.nro_nombramiento_relacionado
  	END AS relacionado,
  	CASE
  		WHEN vn.id_status <> 940 THEN ''
  	END AS Tp_Nombramiento
  FROM vt_nombramiento vn
  	LEFT JOIN vt_nombramiento_detalle VND
  		ON VND.vt_nombramiento = VN.vt_nombramiento
  	LEFT JOIN rrhh_direcciones RD
  		ON rd.id_direccion = vn.id_rrhh_direccion
  	LEFT JOIN rrhh_persona RP
  		ON RP.id_persona = VND.id_empleado
  	LEFT JOIN tbl_municipio TM
  		ON TM.id_municipio = VN.id_municipio
  	LEFT JOIN tbl_departamento TD
  		ON TD.id_departamento = VN.id_departamento
  	LEFT JOIN tbl_pais TP
  		ON TP.id_pais = VN.id_pais
  	LEFT JOIN rrhh_persona RP1
  		ON RP1.id_persona = VN.funcionario
  	LEFT JOIN tbl_catalogo_detalle TCD1
  		ON TCD1.id_item = VN.hora_salida
  	LEFT JOIN tbl_catalogo_detalle TCD2
  		ON TCD2.id_item = VN.hora_regreso
  WHERE vn.vt_nombramiento=?
  AND VND.reng_sustituye = 0";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_nombramiento));
  $s = $stmt->fetchAll();
  Database::disconnect_sqlsrv();
  return $s;
  }

  function get_nombramiento_definitivo_by_id($dia,$mes,$year,$id_nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT rd.descripcion,vn.fecha AS fecha_viatico,vn.fecha_salida, vn.fecha_regreso,
		rd.descripcion_corta+'/' + REPLACE(STR(vn.nro_nombramiento, 4), SPACE(1), '0')+'-'+CAST(YEAR(vn.fecha) AS VARCHAR(100)) AS nombramiento_direccion,
	? AS anio	,
	? AS mes,
	? AS dia,
	CASE
		/*SI EL EMPLEADO ASISTE UNICAMENTE MUESTRA EL NOMBRE*/
		WHEN bln_confirma = 1 THEN
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_apellido,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_apellido,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_apellido,''))) END
		ELSE

			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_nombre,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_nombre,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.primer_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.primer_apellido,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.segundo_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.segundo_apellido,''))) END +
			CASE WHEN LEN(RTRIM(LTRIM(ISNULL(rp.tercer_apellido,'')))) = 0
				THEN '' ELSE ' ' + RTRIM(LTRIM(ISNULL(rp.tercer_apellido,''))) END
        + ' - NO ASISTIO - '
	END AS nombre_completo 	,
	vnd.sueldo,
  CASE vn.funcionario WHEN 0 THEN 'Ninguno' ELSE ISNULL(rp1.primer_nombre, '')+' '+ISNULL(rp1.segundo_nombre, '')+' '+ISNULL(rp1.primer_apellido, '')+' '+ISNULL(rp1.segundo_apellido, '')+' '+ISNULL(rp1.tercer_apellido,'') END AS nombre_funcionario,
	CASE vn.funcionario
		WHEN 0 THEN 'Comision Oficial '+ISNULL(vn.motivo, '')
		ELSE ISNULL(vn.motivo, '')+' '+ISNULL(rp1.primer_nombre, '')+' '+ISNULL(rp1.segundo_nombre, '')+' '+ISNULL(rp1.primer_apellido, '')+' '+ISNULL(rp1.segundo_apellido, '')+' '+ISNULL(rp1.tercer_apellido, '')
	END AS motivo,
	tm.nombre+'-'+td.nombre+'-'+tp.nombre AS nombre,
	DAY(vnd.fecha_salida) AS dia_salida,
	DATENAME(MONTH, vnd.fecha_salida) AS mes_salida,
	DAY(vnd.fecha_regreso) AS dia_regreso,
	DATENAME(MONTH,vnd.fecha_regreso) AS mes_regreso,
	YEAR(vnd.fecha_regreso) anio_regreso,
	tcd1.descripcion hora_salida,
	tcd2.descripcion hora_regreso,
  tcd1.descripcion_corta hora_i,
  tcd2.descripcion_corta hora_f,
	CASE vn.bln_alimentacion
		WHEN 1 THEN 'Alimentacion'
		WHEN 0 THEN ''
	END AS alimentacion,
	CASE vn.bln_hospedaje
		WHEN 1 THEN 'Hospedaje'
		WHEN 0 THEN ''
	END AS hospedaje,
	vn.Observaciones,
	vn.vt_nombramiento,
	CASE
		WHEN len(rtrim(ltrim(vn.nro_nombramiento_relacionado))) = ' ' THEN ''
		ELSE 'Ext.'+ vn.nro_nombramiento_relacionado
	END AS relacionado,
	CASE
		WHEN vn.id_status = 940 THEN '<B>DEFINITIVO</B>'
	END AS Tp_Nombramiento, vn.descripcion_lugar, vn.id_pais,tp.nombre AS pais
FROM vt_nombramiento vn
	LEFT JOIN vt_nombramiento_detalle VND
		ON VND.vt_nombramiento = VN.vt_nombramiento
	LEFT JOIN rrhh_direcciones RD
		ON rd.id_direccion = vn.id_rrhh_direccion
	LEFT JOIN rrhh_persona RP
		ON RP.id_persona = VND.id_empleado
	LEFT JOIN tbl_municipio TM
		ON TM.id_municipio = VN.id_municipio
	LEFT JOIN tbl_departamento TD
		ON TD.id_departamento = VN.id_departamento
	LEFT JOIN tbl_pais TP
		ON TP.id_pais = VN.id_pais
	LEFT JOIN rrhh_persona RP1
		ON RP1.id_persona = VN.funcionario
	LEFT JOIN tbl_catalogo_detalle TCD1
		ON TCD1.id_item = VND.hora_salida
	LEFT JOIN tbl_catalogo_detalle TCD2
		ON TCD2.id_item = VND.hora_regreso
WHERE vn.vt_nombramiento=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($year,$mes,$dia,$id_nombramiento));
  $s = $stmt->fetchAll();
  Database::disconnect_sqlsrv();
  return $s;
  }

  function get_empleados_asistieron($nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.vt_nombramiento AS nombramiento, a.id_empleado, a.bln_confirma, b.primer_nombre, b.segundo_nombre,a.reng_num,a.reng_sustituye,
                   b.tercer_nombre, b.primer_apellido, b.segundo_apellido, b.tercer_apellido,a.bln_cheque,a.bln_confirma,
                   c.id_status, a.nro_frm_vt_ant, a.nro_frm_vt_cons, a.nro_frm_vt_ext, a.nro_frm_vt_liq, c.id_pais, a.fecha_regreso,
                   a.fecha_salida_lugar,
                   a.hospedaje+a.reintegro_alimentacion as totalReint,
                   a.porcentaje_proyectado, a.porcentaje_real, a.monto_asignado, c.tipo_cambio,
                   case a.porcentaje_proyectado when 0 then a.monto_asignado
                   else (a.porcentaje_real)*(a.monto_asignado/a.porcentaje_proyectado)+a.otros_gastos-a.monto_descuento_anticipo-(a.hospedaje+a.reintegro_alimentacion)
                   end as monto_real, a.bln_anticipo
            FROM vt_nombramiento_detalle a
            INNER JOIN rrhh_persona b ON a.id_empleado=b.id_persona
            INNER JOIN vt_nombramiento c ON a.vt_nombramiento=c.vt_nombramiento
            WHERE a.vt_nombramiento=?
            ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nombramiento));
    $empleados = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_empleado_datos_por_nombramiento($nombramiento,$id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.vt_nombramiento AS nombramiento, a.id_empleado, a.bln_confirma, b.primer_nombre, b.segundo_nombre,a.reng_num,
                   b.tercer_nombre, b.primer_apellido, b.segundo_apellido, b.tercer_apellido,a.bln_cheque,a.monto_asignado,
                   c.id_status, a.nro_frm_vt_ant, a.nro_frm_vt_cons, a.nro_frm_vt_ext, a.nro_frm_vt_liq, c.id_pais,a.porcentaje_proyectado,c.tipo_cambio,a.bln_confirma,
                   convert(varchar, a.fecha_salida, 23) as fecha_salida_saas,
                   convert(varchar, a.fecha_llegada_lugar, 23) as fecha_llegada_lugar,
                   convert(varchar, a.fecha_salida_lugar, 23) as fecha_salida_lugar,
                   convert(varchar, a.fecha_regreso, 23) as fecha_regreso_saas,
                   a.porcentaje_real, d.descripcion AS h_salida_saas, e.descripcion AS h_llegada_lugar, f.descripcion AS h_salida_lugar, g.descripcion AS h_regreso_saas,
                   a.hospedaje, a.reintegro_alimentacion, a.otros_gastos
            FROM vt_nombramiento_detalle a
            INNER JOIN rrhh_persona b ON a.id_empleado=b.id_persona
            INNER JOIN vt_nombramiento c ON a.vt_nombramiento=c.vt_nombramiento
            INNER JOIN tbl_catalogo_detalle d ON a.hora_salida=d.id_item
            INNER JOIN tbl_catalogo_detalle e ON a.hora_llegada_lugar=e.id_item
            INNER JOIN tbl_catalogo_detalle f ON a.hora_salida_lugar=f.id_item
            INNER JOIN tbl_catalogo_detalle g ON a.hora_regreso=g.id_item
            WHERE a.vt_nombramiento=? AND a.id_empleado=?
            ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nombramiento,$id_persona));
    $empleado = $stmt->fetch();
    Database::disconnect_sqlsrv();
    return $empleado;
  }

  function get_datos_para_calculo($nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "EXEC sp_sel_datos_para_calculo ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nombramiento));
    $empleados = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_bln_confirma($nombramiento,$reng){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT bln_confirma FROM vt_nombramiento_detalle WHERE vt_nombramiento=? AND reng_num=?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nombramiento,$reng));
    $valor = $stmt->fetch();
    Database::disconnect_sqlsrv();
    return $valor['bln_confirma'];
  }

  function devuelve_porcentaje($dias,$hs,$hr,$hospedaje,$des,$alm,$cen,$hos,$alimentacion,$tipo,$dia_inicio)
  {
      //variable $alimentacion trae bln_alimentacion y $alm es el porcentaje asignado que tiene el tiempo de comida alimentacion en la base de datos
      if ($tipo==1077)
      {  //si el tipo del nombramiento es normal
            $porcentaje=0;
            $contador=1;
            while ($contador<=$dias)
            {
                        if ( ($alimentacion==1)&&($hospedaje==1) ) //alimentacion y hospedaje
                        {
                              if ( ($contador==1)&&($dias>1) )                    //primer dia
                              {
                                    /*if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=18)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    $porcentaje=$porcentaje+$hos;*/
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hs<=17)
                                          $porcentaje=$porcentaje+$cen;
                              }
                              if ( ($contador==1)&&($dias==1) )
                              {
                                    if  ( ($hs<=5)  && ($hr>=8)  )
                                          $porcentaje=$porcentaje+$des;
                                    if  ( ($hs<=11) && ($hr>=14) )
                                          $porcentaje=$porcentaje+$alm;
                                    if  ( ($hs<=17) && ($hr>=20) )
                                          $porcentaje=$porcentaje+$cen;

                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                                $porcentaje=$porcentaje+$hos;
                                                $porcentaje=$porcentaje+50;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    $porcentaje=$porcentaje+$hos;
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        } else  if  ( ($alimentacion==0)&&($hospedaje==1) ) //no alimentacion y hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    $porcentaje=$porcentaje+$hos;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                    $porcentaje=$porcentaje+50;
                              }
                        }
                        else  if  ( ($alimentacion==1)&&($hospedaje==0) ) // alimentacion y no hospedaje
                        {
                              if    ( ($contador==1)&&($dias>1) )                     //primer dia
                              {
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=17)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                              }
                              if ( ($contador==1)&&($dias==1) )
                              {
                                    if  ( ($hs<=5)  && ($hr>=8)  )
                                          $porcentaje=$porcentaje+$des;
                                    if  ( ($hs<=11) && ($hr>=14) )
                                          $porcentaje=$porcentaje+$alm;
                                    if  ( ($hs<=17) && ($hr>=20) )
                                          $porcentaje=$porcentaje+$cen;

                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                    $porcentaje=$porcentaje+50;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        }
                  $contador++;
            }

      }
      else //si el tipo de nombramiento es semana dos dias
      {
            $porcentaje=0;
            $contador=1;
            while ($contador<=$dias)
            {
                  if  ( ($dia_inicio=="Lunes")|| ($dia_inicio=="Monday") )
                  {
                        if ( ($alimentacion==1)&&($hospedaje==1) ) //alimentacion y hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=18)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    $porcentaje=$porcentaje+$hos;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==5)||($contador==6)||($contador==7)||($contador==10) )
                                                $porcentaje=$porcentaje+100;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        } else  if  ( ($alimentacion==0)&&($hospedaje==1) ) //no alimentacion y hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    $porcentaje=$porcentaje+$hos;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==5)||($contador==6)||($contador==7)||($contador==10) )
                                                $porcentaje=$porcentaje+50;
                              }
                        }
                        else  if  ( ($alimentacion==1)&&($hospedaje==0) ) // alimentacion y no hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=18)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==5)||($contador==6)||($contador==7)||($contador==10) )
                                                $porcentaje=$porcentaje+50;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        }
                  }else if ( ($dia_inicio=="Wednesday")|| ($dia_inicio=="Mi�rcoles") )
                  {
                        if ( ($alimentacion==1)&&($hospedaje==1) ) //alimentacion y hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=18)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    $porcentaje=$porcentaje+$hos;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==6)||($contador==7)||($contador==10)||($contador==11)||($contador==12) )
                                                $porcentaje=$porcentaje+100;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        } else  if  ( ($alimentacion==0)&&($hospedaje==1) ) //no alimentacion y hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    $porcentaje=$porcentaje+$hos;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==6)||($contador==7)||($contador==10)||($contador==11)||($contador==12) )
                                                $porcentaje=$porcentaje+50;
                              }
                        }
                        else  if  ( ($alimentacion==1)&&($hospedaje==0) ) // alimentacion y no hospedaje
                        {
                              if ($contador==1)                     //primer dia
                              {
                                    if ($hs<=5)
                                          $porcentaje=$porcentaje+$des;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=11)
                                          $porcentaje=$porcentaje+$alm;
                                    else
                                          $porcentaje=$porcentaje+0;
                                    if ($hs<=18)
                                          $porcentaje=$porcentaje+$cen;
                                    else
                                          $porcentaje=$porcentaje+0;
                              }
                              if ( ($contador>1)&&($contador<$dias) )//dias de en medio
                              {
                                        if ( ($contador==2)||($contador==6)||($contador==7)||($contador==10)||($contador==11)||($contador==12) )
                                                $porcentaje=$porcentaje+50;
                              }
                              if ($contador+1==$dias)    //ultimo dia
                              {
                                    if ($hr>=8)
                                          $porcentaje=$porcentaje+$des;
                                    if ($hr>=14)
                                          $porcentaje=$porcentaje+$alm;
                                    if ($hr>=20)
                                          $porcentaje=$porcentaje+$cen;
                              }
                        }
                  }else if ($dia_inicio=="Viernes")
                  {
                        $porcentaje=350;
                  }
                  $contador++;
            }
      }
      $porcentaje=$porcentaje/100;
      return $porcentaje;
    }

    function get_opciones_menu($nombramiento){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT a.usr_autoriza,
              MAX(b.nro_frm_vt_ant) AS anticipo,
              MAX(b.nro_frm_vt_cons) AS constancia,
              MAX(b.nro_frm_vt_liq) AS liquidacion,
              MAX(b.nro_frm_vt_ext) AS exterior,
              a.id_pais
              FROM vt_nombramiento a
              INNER JOIN vt_nombramiento_detalle b ON b.vt_nombramiento=a.vt_nombramiento
              WHERE a.vt_nombramiento=?
              GROUP BY a.vt_nombramiento, a.id_pais, a.usr_autoriza";

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($nombramiento));
      $cantidades = $stmt->fetch();
      Database::disconnect_sqlsrv();
      return $cantidades;
    }

    function get_empleados_por_direccion($direccion){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      $sql2 = "EXEC sp_sel_colaborador_x_direccion ?";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute(array($direccion));
      $empleados = $stmt2->fetchAll();

      Database::disconnect_sqlsrv();
      return $empleados;
    }

    function get_historial_viatico($vt_nombramiento){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql2 = "SELECT TOP(1) id_log, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans, id_pantalla
               FROM tbl_log_crud WHERE nom_tabla=? AND id_pantalla=? AND val_anterior LIKE '%$vt_nombramiento%'";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute(array('vt_nombramiento',5));
      $log = $stmt2->fetch();

      Database::disconnect_sqlsrv();
      return $log;
    }

    function get_personas_para_liquidar($vt_nombramiento,$parametros){
      $id_status=939;

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql="SELECT
	'PRESUPUESTADO',
	vn.vt_nombramiento,
	vn.fecha_salida										'Salida_nombramiento',
	vnd.monto_asignado,
	cast(substring(tcd1.descripcion_corta,1,2) as int)	'hora_salida_nombramiento',
	vn.fecha_regreso									'regreso_nombramiento',
	datediff(day,vn.fecha_salida,vn.fecha_regreso)+1	'dias_nombramiento',
	datediff(day,vnd.fecha_salida,vnd.fecha_regreso)+1	'dias_real',
	cast(substring(tcd2.descripcion_corta,1,2) as int)	'hora_regreso_nombramiento',
	vnd.fecha_salida									'Salida_real_detalle',
	cast(substring(tcd3.descripcion_corta,1,2) as int)	'hora_real_salida',
	vnd.fecha_regreso									'entrada_real_detalle',
	cast(substring(tcd4.descripcion_corta,1,2) as int)	'hora_real_regreso',
	vn.bln_hospedaje,
	vnd.bln_cheque,
	DATENAME ( weekday ,vn.fecha_salida )				dia_inicio,
	vn.bln_alimentacion,
	vn.id_tipo_nombramiento,
	vnd.sueldo,
	rp.primer_nombre+' '+rp.segundo_nombre+' '+rp.primer_apellido+' '+rp.segundo_apellido as nombre,
	vnd.reng_num,
	vnd.bln_confirma,
	vn.id_pais,
	tp.id_grupo,
	rpp.id_categoria,
	vnd.hospedaje,
	vnd.reintegro_alimentacion
from
		vt_nombramiento vn
	INNER JOIN
		vt_nombramiento_detalle vnd
	ON
			vn.vt_nombramiento = vnd.vt_nombramiento
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd1
	ON
			vn.hora_salida = tcd1.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd2
	ON
			vn.hora_regreso = tcd2.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd3
	ON
			vnd.hora_salida = tcd3.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd4
	ON
			vnd.hora_regreso = tcd4.id_item
	LEFT OUTER JOIN
		rrhh_persona rp
	ON
			vnd.id_empleado = rp.id_persona
	LEFT OUTER JOIN
		rrhh_empleado re
	ON
			re.id_persona = rp.id_persona
	LEFT OUTER JOIN
		tbl_pais tp
	ON
			vn.id_pais = tp.id_pais
	LEFT OUTER JOIN
		rrhh_empleado_plaza rep
	ON
			re.id_empleado = rep.id_empleado
	LEFT OUTER JOIN
		rrhh_plaza rpl
	ON
			rep.id_plaza = rpl.id_plaza
	LEFT OUTER JOIN
		rrhh_plazas_puestos rpp
	ON
			rpl.id_puesto = rpp.id_puesto
where
		vn.vt_nombramiento = $vt_nombramiento	AND vn.id_status = $id_status
	AND rep.id_status = 891
	and vnd.reng_num IN ($parametros)
UNION
select
	'CONTRATOS',
	vn.vt_nombramiento,
	vn.fecha_salida										'Salida_nombramiento',
	vnd.monto_asignado,
	cast(substring(tcd1.descripcion_corta,1,2) as int)	'hora_salida_nombramiento',
	vn.fecha_regreso									'regreso_nombramiento',
	datediff(day,vn.fecha_salida,vn.fecha_regreso)+1	'dias_nombramiento',
	datediff(day,vnd.fecha_salida,vnd.fecha_regreso)+1	'dias_real',
	cast(substring(tcd2.descripcion_corta,1,2) as int)	'hora_regreso_nombramiento',
	vnd.fecha_salida									'Salida_real_detalle',
	cast(substring(tcd3.descripcion_corta,1,2) as int)	'hora_real_salida',
	vnd.fecha_regreso									'entrada_real_detalle',
	cast(substring(tcd4.descripcion_corta,1,2) as int)	hora_real_regreso,
	vn.bln_hospedaje,
	vnd.bln_cheque,
	DATENAME ( weekday ,vn.fecha_salida )				dia_inicio,
	vn.bln_alimentacion,
	vn.id_tipo_nombramiento,
	vnd.sueldo,
	rp.primer_nombre+' '+rp.segundo_nombre+' '+rp.primer_apellido+' '+rp.segundo_apellido as nombre,
	vnd.reng_num,
	vnd.bln_confirma,
	vn.id_pais,
	tp.id_grupo,
	rec.id_categoria,
	vnd.hospedaje,
	vnd.reintegro_alimentacion
from
		vt_nombramiento vn
	INNER JOIN
		vt_nombramiento_detalle vnd
	ON
			vn.vt_nombramiento = vnd.vt_nombramiento
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd1
	ON
			vn.hora_salida = tcd1.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd2
	ON
			vn.hora_regreso = tcd2.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd3
	ON
			vnd.hora_salida = tcd3.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd4
	ON
			vnd.hora_regreso = tcd4.id_item
	LEFT OUTER JOIN
		rrhh_persona rp
	ON
			vnd.id_empleado = rp.id_persona
	LEFT OUTER JOIN
		rrhh_empleado re
	ON
			re.id_persona = rp.id_persona
	LEFT OUTER JOIN
		tbl_pais tp
	ON
			vn.id_pais = tp.id_pais
	LEFT OUTER JOIN
		rrhh_empleado_contratos rec
	ON
			re.id_empleado = rec.id_empleado
		AND	rec.id_status = 908
where
		vn.vt_nombramiento = $vt_nombramiento	AND vn.id_status = $id_status
	AND re.id_contrato = 1075
	and vnd.reng_num IN ($parametros)
UNION
select
	'APOYO',
	vn.vt_nombramiento,
	vn.fecha_salida										'Salida_nombramiento',
	vnd.monto_asignado,
	cast(substring(tcd1.descripcion_corta,1,2) as int)	'hora_salida_nombramiento',
	vn.fecha_regreso									'regreso_nombramiento',
	datediff(day,vn.fecha_salida,vn.fecha_regreso)+1	'dias_nombramiento',
	datediff(day,vnd.fecha_salida,vnd.fecha_regreso)+1	'dias_real',
	cast(substring(tcd2.descripcion_corta,1,2) as int)	'hora_regreso_nombramiento',
	vnd.fecha_salida									'Salida_real_detalle',
	cast(substring(tcd3.descripcion_corta,1,2) as int)	'hora_real_salida',
	vnd.fecha_regreso									'entrada_real_detalle',
	cast(substring(tcd4.descripcion_corta,1,2) as int)	'hora_real_regreso',
	vn.bln_hospedaje,
	vnd.bln_cheque,
	DATENAME ( weekday ,vn.fecha_salida )				dia_inicio,
	vn.bln_alimentacion,
	vn.id_tipo_nombramiento,
	vnd.sueldo,
	rp.primer_nombre+' '+rp.segundo_nombre+' '+rp.primer_apellido+' '+rp.segundo_apellido as nombre,
	vnd.reng_num,
	vnd.bln_confirma,
	vn.id_pais,
	tp.id_grupo,
	rpa.id_categoria,
	vnd.hospedaje,
	vnd.reintegro_alimentacion
from
		vt_nombramiento vn
	INNER JOIN
		vt_nombramiento_detalle vnd
	ON
			vn.vt_nombramiento = vnd.vt_nombramiento
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd1
	ON
			vn.hora_salida = tcd1.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd2
	ON
			vn.hora_regreso = tcd2.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd3
	ON
			vnd.hora_salida = tcd3.id_item
	LEFT OUTER JOIN
		tbl_catalogo_detalle tcd4
	ON
			vnd.hora_regreso = tcd4.id_item
	LEFT OUTER JOIN
		rrhh_persona rp
	ON
			vnd.id_empleado = rp.id_persona
	LEFT OUTER JOIN
		rrhh_empleado re
	ON
			re.id_persona = rp.id_persona
	LEFT OUTER JOIN
		tbl_pais tp
	ON
			vn.id_pais = tp.id_pais
	LEFT OUTER JOIN
		rrhh_persona_apoyo rpa
	ON
		rpa.id_persona = rp.id_persona
where
	vn.vt_nombramiento = $vt_nombramiento	AND vn.id_status = $id_status
AND	rp.tipo_persona IN (1052,1164)
and vnd.reng_num IN ($parametros)
";

$stmt2 = $pdo->prepare($sql);
$stmt2->execute(array());
$empleados = $stmt2->fetchAll();

Database::disconnect_sqlsrv();
return $empleados;
    }

    function validar_participacion($id_persona,$fecha,$hora){
      $sql='';
    }

  function get_liquidacion_pendiente_por_empleado($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(a.nro_frm_vt_liq) as CONTEO FROM vt_nombramiento_detalle a
            INNER JOIN vt_nombramiento b ON a.vt_nombramiento=b.vt_nombramiento
            WHERE a.id_empleado=? AND a.bln_confirma=? AND a.nro_frm_vt_liq=?
            AND b.id_status IN (933,935,936,937,938,939)";

    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,1,0));
    $liquidacion = $p->fetch();
    Database::disconnect_sqlsrv();

    if($liquidacion['CONTEO']>0){
      return true;
    }
    else{
      return false;
    }
  }

  function get_liquidacion_pendiente_por_empleado_anterior($id_persona,$vt_nombramiento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(a.nro_frm_vt_liq) as CONTEO FROM vt_nombramiento_detalle a
            INNER JOIN vt_nombramiento b ON a.vt_nombramiento=b.vt_nombramiento
            WHERE a.id_empleado=? AND a.bln_confirma=? AND a.nro_frm_vt_liq=?
            AND b.id_status IN (933,935,936,937,938,939) AND b.vt_nombramiento < ?";

    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,1,0,$vt_nombramiento));
    $liquidacion = $p->fetch();
    Database::disconnect_sqlsrv();

    if($liquidacion['CONTEO']>0){
      return true;
    }
    else{
      return false;
    }
  }
}

?>
