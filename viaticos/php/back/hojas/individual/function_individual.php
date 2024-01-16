<?php
class hoja{
  static function get_anticipo_individual($vt_nombramiento, $id_empleado){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT
		vnd.nro_frm_vt_ant,
		vnd.monto_asignado*vn.tipo_cambio-vnd.monto_descuento_anticipo		AS	monto_asignado,
		UPPER(
			dbo.Convi_EnLetras(vnd.monto_asignado*vn.tipo_cambio-vnd.monto_descuento_anticipo)
			)																AS	monto_en_letras,
		CASE
			vn.funcionario WHEN 0
				THEN 'Comision Oficial ' + vn.motivo
				ELSE vn.motivo+' ' + df.Nombre_Completo
		END																	AS	tipo_comision,
		COALESCE(ta.nombre+'-','')+tm.nombre +'-'+td.nombre+'-'+tp.nombre	AS	lugar,
		datediff(day,vn.fecha_salida,vn.fecha_regreso) + 1					AS	dias,
		vnd.nro_nombramiento,
		vnd.porcentaje_proyectado,
    vn.fecha AS fecha_nom,
		cast(DAY(vn.fecha) as char(2))+'/'+cast(month(vn.fecha) as char(2))+'/'+cast(year(vn.fecha) as char(4))  as fecha,
		vnd.id_empleado,
    ISNULL(rp.primer_nombre,'')+' '+ISNULL(rp.segundo_nombre,'')+' '+ISNULL(rp.tercer_nombre,'')+' '+
		ISNULL(rp.primer_apellido,'')+' '+ISNULL(rp.segundo_apellido,'')+' '+ISNULL(rp.tercer_apellido,'') AS	nombre_completo,
		ISNULL(rpau.primer_nombre,'')+' '+ISNULL(rpau.segundo_nombre,'')+' '+ISNULL(rpau.tercer_nombre,'')+' '+ISNULL(rpau.primer_apellido,'')
		+' '+ISNULL(rpau.segundo_apellido,'')+' '+ISNULL(rpau.tercer_apellido,'') AS	nombre_emite,
		--rp1.nombre_puesto_presupuestario									AS	nombre_puesto,
		CAST(DAY(vn.fecha) AS VARCHAR(2))+ ' de '+ cast(month(vn.fecha) as char(2)) + ' de '+ cast(year(vn.fecha) as char(4)) AS hoy,
    vn.fecha_procesado,
    vn.fecha AS today,
		vnd.bln_anticipo,
		--pp.descripcion AS puesto_director,
		vnd.bln_confirma,vn.usr_autoriza,
    CASE WHEN vn.id_status IN (934, 1072, 1635,1636,1643,6167,7972) OR vnd.bln_confirma = 0 THEN 0 ELSE 1 END AS estado_viatico

	FROM
			vt_nombramiento_detalle vnd
		INNER JOIN
			vt_nombramiento vn
		ON
			vnd.vt_nombramiento = vn.vt_nombramiento
		LEFT OUTER JOIN
			xxx_rrhh_persona df
		ON
			vn.funcionario = df.id_persona
		INNER JOIN
			tbl_pais tp
		ON
			tp.id_pais = vn.id_pais
		INNER JOIN
			tbl_departamento td
		ON
			td.id_departamento = vn.id_departamento
		INNER JOIN
			tbl_municipio tm
		ON
			tm.id_municipio = vn.id_municipio
		LEFT OUTER JOIN
			tbl_aldea ta
		ON
			ta.id_aldea = vn.id_aldea
		INNER JOIN
			rrhh_persona rp
		ON
			vnd.id_empleado = rp.id_persona
		INNER JOIN
			rrhh_persona rpau
		ON
			vn.usr_autoriza = rpau.id_persona



	WHERE
		vnd.vt_nombramiento = ? ";
    if(!empty($id_empleado)) {
      $sql .= "AND vnd.id_empleado = $id_empleado ";
    }
    $sql .="AND vn.id_status >= 935
	and vn.id_status NOT IN (934)
";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($vt_nombramiento));
    $formulario = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $formulario;
  }
  static function get_constancia_individual($vt_nombramiento, $id_empleado){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT vn.fecha AS fecha_nom,
    CASE WHEN vnd.nro_frm_vt_cons > 0 THEN vnd.nro_frm_vt_cons
    ELSE vnd.nro_frm_vt_ant END AS nro_frm_vt_cons,ISNULL(rp.primer_nombre,'')+' '+ISNULL(rp.segundo_nombre,'')+' '+ISNULL(rp.primer_apellido,'')+' '+ISNULL(rp.segundo_apellido,'') as nombre_completo,
	rd.descripcion as descripcion_direccion,tm.nombre +'-'+td.nombre+'-'+tp.nombre as lugar,
	tcd1.descripcion_corta,
	case vn.bln_hospedaje when 1 then 'HOSPEDAJE' when 0 then '' end as hospedaje,
	case vn.bln_alimentacion when 1 then 'ALIMENTACION' when 0 then ''  end as alimentacion,
	cast(day(vnd.fecha_llegada_lugar) as char(2))+'-'+cast(month(vnd.fecha_llegada_lugar) as char(2))+'-'+cast(year(vnd.fecha_llegada_lugar) as char(4))  as fecha_llegada_lugar,
  cast(day(vnd.fecha_salida_lugar) as char(2))+'-'+cast(month(vnd.fecha_salida_lugar) as char(2))+'-'+cast(year(vnd.fecha_salida_lugar) as char(4))  as fecha_salida_lugar,
	tcd2.descripcion_corta,
	vn.descripcion_lugar,vn.vt_nombramiento,tp.nombre AS pais, vnd.destino, vnd.id_empleado,
  CASE
  WHEN vn.id_status IN (934, 1072, 1635,1636,1643,6167,7972) OR vnd.bln_confirma = 0 THEN 0
  WHEN vn.id_pais <> 'GT' THEN 0
  ELSE 1 END AS estado_viatico
	from vt_nombramiento_detalle vnd
	LEFT JOIN vt_nombramiento vn ON vnd.vt_nombramiento=vn.vt_nombramiento
	LEFT JOIN rrhh_persona rp ON vnd.id_empleado=rp.id_persona


	LEFT JOIN rrhh_direcciones rd ON vn.id_rrhh_direccion=rd.id_direccion
	LEFT JOIN tbl_municipio tm ON vn.id_municipio=tm.id_municipio
	LEFT JOIN tbl_departamento td ON tm.id_departamento=td.id_departamento
	LEFT JOIN tbl_pais tp ON td.id_pais=tp.id_pais
	LEFT JOIN tbl_catalogo_detalle tcd1 ON vnd.hora_llegada_lugar=tcd1.id_item
	LEFT JOIN tbl_catalogo_detalle tcd2 ON vnd.hora_salida_lugar=tcd2.id_item


	where
	 vnd.vt_nombramiento=?
	--and vnd.bln_confirma=1

	and vn.id_status>=939
	and vn.id_status not in (934,1072,1635,1636,1643)

  ";
  if(!empty($id_empleado)){
    $sql.=" AND vnd.id_empleado=$id_empleado";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($vt_nombramiento));

  $empleado = $stmt->fetchAll();
  Database::disconnect_sqlsrv();
  return $empleado;
  }
  static function get_liquidacion_individual($vt_nombramiento, $id_empleado){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT vnd.nro_frm_vt_liq,(vn.tipo_cambio * vnd.monto_asignado) AS monto_asignado, vn.fecha,
    	case vn.id_pais when 'GT' then 'Q.' else 'USD' end as moneda,
    	vn.tipo_cambio,
    	--upper(dbo.CantidadConLetra(abs(abs( vnd.porcentaje_proyectado - vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado) - vnd.monto_asignado )+vnd.otros_gastos ) ) as monto_en_letras,
    	case vnd.porcentaje_proyectado when 0 then upper(dbo.CantidadConLetra (vnd.monto_asignado) )  else upper (dbo.CantidadConLetra((vn.tipo_cambio) *((vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado))+(vnd.otros_gastos-vnd.monto_descuento_anticipo)-(vnd.hospedaje+vnd.reintegro_alimentacion))) end as monto_en_letras,
    	case vnd.porcentaje_proyectado when 0 then cast(vnd.monto_asignado/vnd.porcentaje_real as decimal(16,2))   else cast(vnd.monto_asignado/vnd.porcentaje_proyectado as decimal(16,2)) end as cuota,
    	--abs(abs( vnd.porcentaje_proyectado - vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado) - vnd.monto_asignado  ) +vnd.otros_gastos as total_real,
    	--(vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado)+vnd.otros_gastos-vnd.monto_descuento_anticipo as total_real,
    	case vnd.porcentaje_proyectado when 0 then vnd.monto_asignado  else ((vn.tipo_cambio)*((vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado)))+vnd.otros_gastos-vnd.monto_descuento_anticipo-(vnd.hospedaje+vnd.reintegro_alimentacion) end as total_real,
    		case vnd.porcentaje_proyectado
    		when 0 then vn.tipo_cambio*vnd.monto_asignado
    		else ((vnd.porcentaje_real)*(vnd.monto_asignado/vnd.porcentaje_proyectado)+(vnd.monto_descuento_anticipo-(vnd.hospedaje+vnd.reintegro_alimentacion)))
    	end as total_real2,
    	vnd.porcentaje_proyectado,vnd.porcentaje_real,
    	vn.justificacion_descuento_anticipo justificacion,
    	vnd.monto_descuento_anticipo,
    	vnd.otros_gastos,
    	vnd.monto_asignado+vnd.otros_gastos as total,
    	--ACÁ AGREGO LA JUSTIFICACIÓN DEL REINTEGRO AG 106-2006
    	CASE
    		WHEN (vnd.hospedaje+vnd.reintegro_alimentacion) > 0 THEN 'Reintegro por alimentación y hospedaje según Acuerdos Gubernativos No. 148-2016 / 106-2016'
    		ELSE ''
    	END AS justifica_hospedaje,
    	case vn.funcionario when  0 then 'Comision Oficial '+vn.motivo  else  vn.motivo+' '+ISNULL(df.primer_nombre,'')+' '+ISNULL(df.segundo_nombre,'')+' '+ISNULL(df.primer_apellido,'')+' '+ISNULL(df.segundo_apellido,'')+' '+ISNULL(df.tercer_apellido,'') end as tipo_comision,
    	tm.nombre +'-'+td.nombre+'-'+tp.nombre as lugar,
    	datediff(day,vn.fecha_salida,vn.fecha_regreso)+1 as dias_nombramiento,
    	datediff(day,vnd.fecha_salida,vnd.fecha_regreso)+1 as dias_detalle,
    	vnd.nro_frm_vt_ant as numero_viatico_anticipo,
    	--cast(day(getdate()) as varchar(2))+'/'+cast(month(getdate()) as varchar(2))+'/'+cast (year(getdate()) as varchar(4)) as hoy,
    	vnd.fecha_liquidacion as hoy, vnd.fecha_liquidacion,
    	ISNULL(rp.primer_nombre,'')+' '+ISNULL(rp.segundo_nombre,'')+' '+ISNULL(rp.primer_apellido,'')+ ' '+ISNULL(rp.segundo_apellido,'') as nombre,
    	--rpla.descripcion as plaza,
    	--substring(rpla.partida_presupuestaria,27,5)+substring(rpla.partida_presupuestaria,57,5) as partida,
    	vnd.sueldo,vnd.monto_asignado_sustituido,vnd.reng_sustituye,
    	vnd.hospedaje,
    	CAST((vnd.hospedaje+vnd.reintegro_alimentacion) AS DECIMAL(18,2)) hospedaje2,
    	vnd.reintegro_alimentacion,
    	(vnd.hospedaje+vnd.reintegro_alimentacion) totalReint, vnd.bln_anticipo, vn.descripcion_lugar,vn.vt_nombramiento, tp.nombre AS pais,vnd.bln_cheque,vnd.id_empleado,vnd.fecha_liquidacion, vnd.destino,
      vn.fecha_procesado,CASE WHEN vn.id_status IN (934, 1072, 1635,1636,1643,6167,7972) OR vnd.bln_confirma = 0 THEN 0 ELSE 1 END AS estado_viatico,
      vn.fecha AS fecha_nom
    	from vt_nombramiento_detalle vnd

		LEFT JOIN vt_nombramiento vn ON vnd.vt_nombramiento=vn.vt_nombramiento
		LEFT JOIN rrhh_persona df on vn.funcionario=df.id_persona

		LEFT JOIN tbl_municipio tm ON vn.id_municipio=tm.id_municipio
		LEFT JOIN tbl_departamento td on tm.id_departamento=td.id_departamento
		LEFT JOIN tbl_pais tp on td.id_pais=tp.id_pais
		LEFT JOIN rrhh_persona rp on vnd.id_empleado=rp.id_persona

		WHERE vnd.vt_nombramiento=?

    	--and vnd.bln_confirma=1



    	and vn.id_status>=939
    	and vn.id_status not in (934,1072,1635,1636,1643) ";


      if($id_empleado > 0){
        $sql.=" AND vnd.id_empleado=$id_empleado";
      }

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($vt_nombramiento));

      $empleado = $stmt->fetchAll();
      Database::disconnect_sqlsrv();
      return $empleado;
  }

  static function getPuestoEmpleado($id_persona,$fecha_comision){
    //inicio
    $response = '';
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1  c.fecha_toma_posesion AS fecha_inicio, g.descripcion AS pueston,
            substring(f.partida_presupuestaria,27,5)+substring(f.partida_presupuestaria,57,5) as partida, sd.sueldo
            FROM rrhh_empleado b
            LEFT JOIN rrhh_empleado_plaza c ON c.id_empleado = b.id_empleado
            --LEFT JOIN SAAS_APP.dbo.rrhh_asignacion_puesto_historial_detalle d ON d.id_asignacion = c.id_asignacion
            --LEFT JOIN SAAS_APP.dbo.rrhh_direcciones e ON d.direccion_f = e.id_direccion
      			INNER JOIN SAAS_APP.dbo.rrhh_plaza f ON c.id_plaza = f.id_plaza
      			INNER JOIN SAAS_APP.dbo.rrhh_plazas_puestos g ON g.id_puesto = f.id_puesto
            LEFT JOIN (SELECT c.monto_p AS sueldo, b.id_plaza
              FROM rrhh_plazas_sueldo b
              INNER JOIN rrhh_plazas_sueldo_detalle c ON c.id_sueldo=b.id_sueldo
              INNER JOIN rrhh_plazas_sueldo_conceptos d ON c.id_concepto = d.id_concepto
              WHERE  b.actual =1 AND d.aplica_plaza = 1 AND c.id_concepto = 1

            ) AS sd ON sd.id_plaza = c.id_plaza
            WHERE b.id_persona = ? AND CONVERT(VARCHAR,c.fecha_toma_posesion,23) <= ?
            ORDER BY c.id_asignacion DESC;

            --ORDER BY d.fecha_inicio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona,$fecha_comision));
    $response =  $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();
    return $response;
    //fin
  }
  static function getContratoEmpleado($id_persona,$fecha_comision){
    //inicio
    $response = '';
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 cnt.tipo_contrato,
           CASE WHEN tipo_contrato=8 THEN '031' ELSE
           CASE WHEN tipo_contrato=9 THEN '029' ELSE ' ' END END Renglon,
           e.id_persona, cnt.id_empleado, e.nombre_completo, cnt.reng_num, cnt.nro_contrato, cnt.nro_acuerdo_aprobacion, cnt.fecha_acuerdo_aprobacion,
           cnt.fecha_contrato, e.fecha_ingreso, cnt.fecha_inicio, cnt.fecha_finalizacion, cnt.monto_contrato, cnt.monto_mensual, cnt.fecha_acuerdo_resicion, cnt.fecha_efectiva_resicion,
		         dir.descripcion AS direccion, cd.descripcion AS pueston,cnt.id_direccion_servicio, '031' AS partida, 2425.80 AS sueldo

           FROM dbo.xxx_rrhh_empleado_persona e LEFT JOIN
           dbo.rrhh_empleado_contratos AS cnt ON cnt.id_empleado = e.id_empleado LEFT JOIN

           dbo.rrhh_direcciones dir ON cnt.id_direccion_servicio = dir.id_direccion
		         LEFT JOIN
		           dbo.tbl_catalogo_detalle cd ON cnt.id_puesto_servicio = cd.id_item
           WHERE
           e.id_persona=? AND CONVERT(VARCHAR,fecha_inicio,23) <= ?
           ORDER BY cnt.reng_num DESC, cnt.tipo_contrato DESC;

            --ORDER BY d.fecha_inicio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona,$fecha_comision));
    $response =  $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();
    return $response;
    //fin
  }

  static function getApoyoEmpleado($id_persona){
    //inicio
    $response = '';
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_persona, a.id_cargo, b.descripcion AS puesto, CASE WHEN LEN(a.partida_presupuestaria) > 10 THEN substring(a.partida_presupuestaria,27,5)+substring(a.partida_presupuestaria,57,5)
            ELSE a.partida_presupuestaria END AS
            partida, a.salario_base + a.bonificacion AS sueldo
            FROM rrhh_persona_apoyo a
            INNER JOIN tbl_catalogo_detalle b ON a.id_cargo = b.id_item
            WHERE id_persona = ?;

            --ORDER BY d.fecha_inicio DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_persona));
    $response =  $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();
    return $response;
    //fin
  }

  static function retornaResolucion($correlativo, $tipo){

    $arreglo = array();
    if($correlativo >45000){
      $arreglo = array(
        1 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O. –JM-47-2022C 000983 GESTIÓN NÚMERO: 644611 DE FECHA 09-03-2022 DE CUENTA S1-22 · 15000 Formulario de Viatico Anticipo en Forma Electrónica DEL No. 45001 AL 60000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 299-2022 DEL 27-04-2022 · ENVIO FISCAL 4-ASCC 19645 DEL 27-04-2022 LIBRO 4-ASCC FOLIO 157 ',
        2 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O. –JM-47-2022C 000983 GESTIÓN NÚMERO: 644611 DE FECHA 09-03-2022 DE CUENTA S1-22 · 15000 Formulario de Viatico Constancia en Forma Electrónica DEL No. 45001 AL 60000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 299-2022 DEL 27-04-2022 · ENVIO FISCAL 4-ASCC 19645 DEL 27-04-2022 LIBRO 4-ASCC FOLIO 157',
        3 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O. –JM-47-2022C 000983 GESTIÓN NÚMERO: 644611 DE FECHA 09-03-2022 DE CUENTA S1-22 · 15000 Formulario de Viatico Liquidación en Forma Electrónica DEL No. 45001 AL 60000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 299-2022 DEL 27-04-2022 · ENVIO FISCAL 4-ASCC 19645 DEL 27-04-2022 LIBRO 4-ASCC FOLIO 157 '
      );
    }else{
      $arreglo = array(
        1 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. SM./000890 GESTIÓN NÚMERO: 333273 DE FECHA 14-2-2019 DE CUENTA S1-22 · 15000 Formulario de Viatico Anticipo en Forma Electrónica DEL No. 30001 AL 45000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 235/2019 DEL 04-04-2019 · ENVIO FISCAL 4-ASCC 16296 DEL 04-04-2019 LIBRO 4-ASCC FOLIO 70',
        2 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. SM./000890 GESTIÓN NÚMERO: 333273 DE FECHA 14-2-2019 DE CUENTA S1-22 · 15000 Formulario de Viatico Constancia en Forma Electrónica DEL No. 30001 AL 45000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 235/2019 DEL 04-04-2019 · ENVIO FISCAL 4-ASCC 16296 DEL 04-04-2019 LIBRO 4-ASCC FOLIO 70',
        3 => 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. SM./000890 GESTIÓN NÚMERO: 333273 DE FECHA 14-2-2019 DE CUENTA S1-22 · 15000 Formulario de Viatico Liquidacion en Forma Electrónica DEL No. 30001 AL 45000 SIN SERIE No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 235/2019 DEL 04-04-2019 · ENVIO FISCAL 4-ASCC 16296 DEL 04-04-2019 LIBRO 4-ASCC FOLIO 70'
      );
    }
    return $arreglo[$tipo];
  }
}

?>
