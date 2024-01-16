<?php
class vehiculos
{
  //date_default_timezone_set('America/Guatemala');

  static function get_corte_combustible()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT top 1 id_corte, identificador_corte from dayf_combustible_corte order by id_corte desc ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $corte = $p->fetch();
    Database::disconnect_sqlsrv();
    return $corte;
  }

  static function get_all_valesCombustible($fin)
  {
    //$fin=date('Y-m-d');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT v.fecha_entrega fecha, v.nro_vale, p.nro_placa,
          es.descripcion estado, u.descripcion uso, ev.descripcion evento,
          isnull(pemit.Nombre_Completo,' ') emite,
          isnull(pauto.Nombre_Completo,' ') autoriza,
          isnull(rprec.Nombre_Completo,' ') recibe,
          cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones, v.flag_tanque_lleno tlleno,v.id_estado
      FROM dayf_combustibles v
          INNER JOIN dayf_vehiculo_placas p on v.id_vehiculo = p.id_vehiculo
          INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
          INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
          --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
          INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
          INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
          INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
          FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
          FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
          --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
          FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
          where convert(varchar, v.fecha_entrega, 23) =? AND p.reng_num in
            ( SELECT TOP (1) reng_num
              FROM dbo.dayf_vehiculo_placas AS p2
              WHERE (p2.id_vehiculo = v.id_vehiculo)
              ORDER BY reng_num DESC )
     union
     SELECT v.fecha_entrega fecha, v.nro_vale, ex.nro_placa,
          es.descripcion estado, u.descripcion uso, ev.descripcion evento,
          isnull(pemit.Nombre_Completo,' ') emite,
          isnull(pauto.Nombre_Completo,' ') autoriza,
          isnull(rprec.Nombre_Completo,' ') recibe,
          cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones, v.flag_tanque_lleno tlleno,v.id_estado
      FROM dayf_combustibles v
          LEFT OUTER JOIN dayf_vehiculos_externos ex on v.id_vehiculo_externo = ex.id_vehiculo_externo
          INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
          INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
          --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
          INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
          INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
          INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
          FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
          FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
          --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
          FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
          where convert(varchar, v.fecha_entrega, 23) = ? and not ex.nro_placa is null
      union
      SELECT v.fecha_entrega fecha, v.nro_vale, space(10) nro_placa,
          es.descripcion estado, u.descripcion, ev.descripcion evento,
          isnull(pemit.Nombre_Completo,' ') emite,
          isnull(pauto.Nombre_Completo,' ') autoriza,
          isnull(rprec.Nombre_Completo,' ') recibe,
          cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones, v.flag_tanque_lleno tlleno,v.id_estado
      FROM dayf_combustibles v
          INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
          INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
          --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
          INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
          INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
          INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
          FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
          FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
          --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
          FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
          where convert(varchar, v.fecha_entrega, 23) = ? and v.id_vehiculo is null and v.id_vehiculo_externo is null
      order by v.nro_vale";

    $p = $pdo->prepare($sql);
    $p->execute(array($fin, $fin, $fin)); // 65 es el id de aplicaciones
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function get_all_cupones_entregados($ini, $fin, $estado)
  {
    //$fin=date('Y-m-d');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT c.id_documento, e.descripcion_corta estado,
    c.fecha, c.nro_documento,
    isnull(o.Nombre_Completo,' ') opero,
    isnull(a.Nombre_Completo,' ') autorizo,
    isnull(r.Nombre_Completo,' ') recibe,
    isnull(v.descripcion,' ') evento,
    (select count(*) from dayf_cupones_documento_detalle where id_documento=c.id_documento) cupones,
    c.total,
    (c.total-c.total_devuelto) pendiente
  FROM dayf_cupones_documento c
    left outer join tbl_catalogo_detalle e on c.id_estado_documento = e.id_item
    left OUTER JOIN xxx_rrhh_persona o on c.id_persona_opero = o.id_persona
    left OUTER JOIN xxx_rrhh_persona a on c.id_persona_autorizo = a.id_persona
    left OUTER JOIN xxx_rrhh_persona r on c.id_persona_recibe_cupon = r.id_persona
    left outer join tbl_catalogo_detalle v on c.id_evento = v.id_item
  WHERE c.id_tipo_documento=4353 and CONVERT(date, c.fecha, 105) >= ? and CONVERT(date,c.fecha,105) <= ? ";

    if ($estado == 0) {
    } else {
      $sql .= " AND c.id_estado_documento = " . $estado;
    }

    $sql .= " ORDER BY c.id_documento DESC";

    $p = $pdo->prepare($sql);
    $p->execute(array($ini, $fin, $estado));
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function get_cupones_by_cupon($noCupon)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT c.id_documento, e.descripcion_corta estado,
              c.fecha, c.nro_documento,
              isnull(o.Nombre_Completo,' ') opero,
              isnull(a.Nombre_Completo,' ') autorizo,
              isnull(r.Nombre_Completo,' ') recibe,
              isnull(v.descripcion,' ') evento, c.total
            FROM dayf_cupones cup
              left outer join dayf_cupones_documento_detalle d on cup.id_cupon = d.id_cupon
              left outer join dayf_cupones_documento c on c.id_documento = d.id_documento
              left outer join tbl_catalogo_detalle e on c.id_estado_documento = e.id_item
              left OUTER JOIN xxx_rrhh_persona o on c.id_persona_opero = o.id_persona
              left OUTER JOIN xxx_rrhh_persona a on c.id_persona_autorizo = a.id_persona
              left OUTER JOIN xxx_rrhh_persona r on c.id_persona_recibe_cupon = r.id_persona
              left outer join tbl_catalogo_detalle v on c.id_evento = v.id_item
            WHERE cup.nro_cupon = ? and c.id_tipo_documento = 4353
            ORDER BY c.id_documento DESC ";

    $p = $pdo->prepare($sql);
    $p->execute(array($noCupon));
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function get_all_cupones_ingresados($ini, $fin)
  {
    //$fin=date('Y-m-d');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT d.id_documento, e.descripcion estado, d.fecha, d.nro_documento, 
              isnull(f.nombre,' ') opero, d.total
            from dayf_cupones_documento d
              left outer join tbl_catalogo_detalle e on d.id_estado_documento = e.id_item
              left outer join (select distinct id_persona, nombre from xxx_rrhh_ficha) f on d.id_persona_opero = f.id_persona
            where d.id_tipo_documento=4351 and convert(date,d.fecha) >= ? and convert(date,d.fecha) <= ? 
            order by d.id_documento desc ";

    $p = $pdo->prepare($sql);
    $p->execute(array($ini, $fin));
    $cup = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $cup;
  }

  static function get_valeCombustible($nro_vale)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql =
      "SELECT v.fecha_entrega fecha, v.nro_vale, p.nro_placa, isnull(v.nro_referencia,' ') refer,
        es.descripcion estado, u.descripcion uso, ev.descripcion evento,
        isnull(pemit.Nombre_Completo,' ') emite,
        isnull(pauto.Nombre_Completo,' ') autoriza,
        isnull(rprec.Nombre_Completo,' ') recibe,
        cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones,
        v.flag_tanque_lleno tlleno
    FROM dayf_combustibles v
        INNER JOIN dayf_vehiculo_placas p on v.id_vehiculo = p.id_vehiculo
        INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
        INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
        --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
        INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
        INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
        INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
        FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
        FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
        --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
        FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
        where nro_vale=? AND p.reng_num in
          ( SELECT TOP (1) reng_num
            FROM dbo.dayf_vehiculo_placas AS p2
            WHERE (p2.id_vehiculo = v.id_vehiculo)
            ORDER BY reng_num DESC )
    union
    SELECT v.fecha_entrega fecha, v.nro_vale, ex.nro_placa,  isnull(v.nro_referencia,' ') refer,
        es.descripcion estado, u.descripcion uso, ev.descripcion evento,
        isnull(pemit.Nombre_Completo,' ') emite,
        isnull(pauto.Nombre_Completo,' ') autoriza,
        isnull(rprec.Nombre_Completo,' ') recibe,
        cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones,
        v.flag_tanque_lleno tlleno
    FROM dayf_combustibles v
        LEFT OUTER JOIN dayf_vehiculos_externos ex on v.id_vehiculo_externo = ex.id_vehiculo_externo
        INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
        INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
        --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
        INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
        INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
        INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
        FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
        FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
        --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
        FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
        where nro_vale=? and not ex.nro_placa is null
    union
    SELECT v.fecha_entrega fecha, v.nro_vale, space(0) nro_placa, isnull(v.nro_referencia,' ') refer,
        es.descripcion estado, u.descripcion, ev.descripcion evento,
        isnull(pemit.Nombre_Completo,' ') emite,
        isnull(pauto.Nombre_Completo,' ') autoriza,
        isnull(rprec.Nombre_Completo,' ') recibe,
        cte.identificador_corte, v.id_combustible, comb.descripcion, v.cant_galones_autorizados cant_galones,
        v.flag_tanque_lleno tlleno
    FROM dayf_combustibles v
        INNER JOIN dayf_combustible_corte cte on v.id_corte = cte.id_corte
        INNER JOIN inv_producto_insumo comb on v.id_tipo_combustible = comb.id_producto_insumo
        --INNER JOIN tbl_catalogo_detalle au on au.id_item = 7940 -- este registro contiene el id, de la persona que autoriza.
        INNER JOIN tbl_catalogo_detalle es on v.id_estado = es.id_item
        INNER JOIN tbl_catalogo_detalle u on v.id_tipo_uso = u.id_item
        INNER JOIN tbl_catalogo_detalle ev on v.id_evento = ev.id_item
        FULL OUTER JOIN xxx_rrhh_persona pemit on v.id_persona_entrega_vale = pemit.id_persona
        FULL OUTER JOIN xxx_rrhh_persona pauto on v.id_persona_autoriza_vale = pauto.id_persona
        --FULL OUTER JOIN xxx_rrhh_persona pauto on au.id_ref_catalogo = pauto.id_persona
        FULL OUTER JOIN xxx_rrhh_persona rprec on v.id_persona_recibe_vale = rprec.id_persona
        where nro_vale=? and v.id_vehiculo is null and v.id_vehiculo_externo is null
    order by v.nro_vale";
    $p = $pdo->prepare($sql);
    $p->execute(array($nro_vale, $nro_vale, $nro_vale)); // 65 es el id de aplicaciones
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function get_TipoDespacho($nro_vale)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nro_vale, id_tipo_uso from dayf_combustibles where nro_vale = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($nro_vale)); // 65 es el id de aplicaciones
    $tipoDes = $p->fetch();
    Database::disconnect_sqlsrv();
    return $tipoDes;
  }

  static function get_valeDespacho($nro_vale)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT c.fecha_entrega fecha, c.nro_vale, isnull(c.nro_referencia,' ') refer,
      pl.nro_placa, u.descripcion uso, c.nro_referencia caracteristica,
      c.id_combustible, c.id_tipo_combustible, p.descripcion tipo_comb, c.cant_galones_autorizados cant_autor,
      c.flag_tanque_lleno tlleno, c.observaciones observa, v.km_actual, isnull(r.Nombre_Completo_inverso,' ') recibe
      FROM dayf_combustibles c
      left outer join dayf_vehiculos v on c.id_vehiculo = v.id_vehiculo
      INNER JOIN inv_producto_insumo p on c.id_tipo_combustible = p.id_producto_insumo
      left outer join tbl_catalogo_detalle u on c.id_tipo_uso = u.id_item
      left outer join dayf_vehiculo_placas pl on v.id_vehiculo = pl.id_vehiculo
      FULL OUTER JOIN xxx_rrhh_persona r on c.id_persona_recibe_vale = r.id_persona
      where c.nro_vale = ? and
      pl.reng_num in( SELECT TOP (1) p2.reng_num
		    FROM dbo.dayf_vehiculo_placas AS p2
		    WHERE ( p2.id_vehiculo = pl.id_vehiculo )
		  ORDER BY p2.reng_num DESC ) ";
    $p = $pdo->prepare($sql);
    $p->execute(array($nro_vale)); // 65 es el id de aplicaciones
    $vale = $p->fetch();
    Database::disconnect_sqlsrv();
    return $vale;
  }

  static function get_valeDespacho_Ext($nro_vale)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT c.fecha_entrega fecha, c.nro_vale, isnull(c.nro_referencia,' ') caracteristica,
    v.nro_placa, u.descripcion uso,
    c.id_combustible, c.id_tipo_combustible, p.descripcion tipo_comb, c.cant_galones_autorizados cant_autor,
    c.flag_tanque_lleno tlleno, c.observaciones observa, isnull(r.Nombre_Completo_inverso,' ') recibe
    FROM dayf_combustibles c
    left outer join dayf_vehiculos_externos v on c.id_vehiculo_externo = v.id_vehiculo_externo
    INNER JOIN inv_producto_insumo p on c.id_tipo_combustible = p.id_producto_insumo
    left outer join tbl_catalogo_detalle u on c.id_tipo_uso = u.id_item
    FULL OUTER JOIN xxx_rrhh_persona r on c.id_persona_recibe_vale = r.id_persona
    where c.nro_vale = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($nro_vale)); // 65 es el id de aplicaciones
    $valeEXT = $p->fetch();
    Database::disconnect_sqlsrv();
    return $valeEXT;
  }

  static function get_valeDespacho_Gen($nro_vale)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT c.fecha_entrega fecha, c.nro_vale, isnull(c.nro_referencia,' ') nro_placa, u.descripcion uso,
    c.id_combustible, c.id_tipo_combustible, p.descripcion tipo_comb, 0 tlleno,
    c.cant_galones_autorizados cant_autor, isnull(r.Nombre_Completo_inverso,' ') recibe
    FROM dayf_combustibles c
    INNER JOIN inv_producto_insumo p on c.id_tipo_combustible = p.id_producto_insumo
    left outer join tbl_catalogo_detalle u on c.id_tipo_uso = u.id_item
    FULL OUTER JOIN xxx_rrhh_persona r on c.id_persona_recibe_vale = r.id_persona
    where c.nro_vale = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($nro_vale)); // 65 es el id de aplicaciones
    $valeGEN = $p->fetch();
    Database::disconnect_sqlsrv();
    return $valeGEN;
  }

  static function get_placas($tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1141 - super, 1142 - regular, 1143 - diesel
    $sql = "SELECT p.nro_placa+' '+ltrim(rtrim(m.descripcion)) +' '+case when l.descripcion = 'Sin Datos' then '' else l.descripcion end nro_placa,
        p.id_vehiculo, v.id_tipo_combustible
      FROM dayf_vehiculo_placas p
        LEFT OUTER JOIN dayf_vehiculos v ON p.id_vehiculo=v.id_vehiculo
        LEFT OUTER JOIN tbl_catalogo_detalle m ON v.id_marca=m.id_item
        LEFT OUTER JOIN dayf_vehiculo_linea l ON v.id_linea=l.id_linea
      WHERE p.reng_num in( SELECT TOP (1) p2.reng_num
		    FROM dbo.dayf_vehiculo_placas AS p2
		    WHERE ( p2.id_vehiculo = p.id_vehiculo )
		  ORDER BY p2.reng_num DESC ) ";

    if ($tipo == 0) {
      $sql .= "AND
  	      p.id_vehiculo not in( SELECT c2.id_vehiculo
  		    FROM dayf_combustibles c2
  		  WHERE convert(varchar(10),c2.fecha_entrega,23)=convert(varchar(10),getdate(),23)
          AND c2.id_estado=1150 and c2.id_vehiculo > 0 )";
    }


    $sql .= " ORDER by nro_placa ";

    $p = $pdo->prepare($sql);
    $p->execute(array());
    $placas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $placas;
  }

  static function get_placa_by_placa($id_vehiculo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1141 - super, 1142 - regular, 1143 - diesel
    $sql = "SELECT p.nro_placa+' '+
    ltrim(rtrim(m.descripcion)) +' '+case when l.descripcion = 'Sin Datos' then '' else l.descripcion end nro_placa,
    p.id_vehiculo, isnull(v.id_tipo_combustible,0) id_tipo_combustible
    FROM dayf_vehiculo_placas p
    LEFT OUTER JOIN dayf_vehiculos v ON p.id_vehiculo=v.id_vehiculo
    LEFT OUTER JOIN dayf_vehiculo_asignacion a ON p.id_vehiculo = a.id_vehiculo
    LEFT OUTER JOIN rrhh_direcciones d ON a.id_persona_asignado_direccion = d.id_direccion
    LEFT OUTER JOIN tbl_catalogo_detalle m ON v.id_marca=m.id_item
    LEFT OUTER JOIN dayf_vehiculo_linea l ON v.id_linea=l.id_linea
    WHERE p.reng_num in( SELECT TOP (1) p2.reng_num
    FROM dbo.dayf_vehiculo_placas AS p2
    WHERE ( p2.id_vehiculo = p.id_vehiculo )
    ORDER BY p2.reng_num DESC )
    AND a.id_vehiculo_asignacion in( SELECT TOP (1) a2.id_vehiculo_asignacion
    FROM dbo.dayf_vehiculo_asignacion AS a2
    WHERE ( a2.id_vehiculo = p.id_vehiculo ) AND p.id_vehiculo=?
    ORDER BY a2.id_vehiculo_asignacion DESC )
    ORDER by nro_placa ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo));
    $placas = $p->fetch();
    Database::disconnect_sqlsrv();
    return $placas;
  }

  static function get_placas_ex()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT v.nro_placa+' '+
      ltrim(rtrim(m.descripcion)) +' '+isnull(convert(varchar(5),v.id_modelo),'')+' '+c.descripcion nro_placa,
      id_vehiculo_externo id_vehiculo, isnull(id_tipo_combustible,0) id_tipo_combustible
      from dayf_vehiculos_externos v
      LEFT OUTER JOIN tbl_catalogo_detalle m ON v.id_marca=m.id_item
      LEFT OUTER JOIN tbl_catalogo_detalle c ON v.id_color=c.id_item
      where v.id_vehiculo_externo not in( SELECT c2.id_vehiculo_externo
		    FROM dayf_combustibles c2
		    WHERE convert(varchar(10),c2.fecha_entrega,23)=convert(varchar(10),getdate(),23)
			  AND c2.id_estado=1150 and c2.id_vehiculo_externo > 0  ) ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $placasex = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $placasex;
  }

  static function get_placasex_by_placa($id_vehiculo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT v.nro_placa+' '+
    ltrim(rtrim(m.descripcion)) +' '+isnull(convert(varchar(5),id_modelo),'')+' '+c.descripcion nro_placa,
    id_vehiculo_externo id_vehiculo, isnull(id_tipo_combustible,0) id_tipo_combustible
    from dayf_vehiculos_externos v
    LEFT OUTER JOIN tbl_catalogo_detalle m ON v.id_marca=m.id_item
    LEFT OUTER JOIN tbl_catalogo_detalle c ON v.id_color=c.id_item
    where id_vehiculo_externo = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo));
    $placasex = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $placasex;
  }

  static function get_eventos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT descripcion, id_item id_evento from tbl_catalogo_detalle where id_catalogo=62";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $eventos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $eventos;
  }

  static function get_tipo($tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT descripcion, id_producto_insumo, id_categoria
            from inv_producto_insumo
            where id_bodega_insumo=4107 and flag_codigo_individual=0 ";
    if ($tipo != 0) {
      $sql .= " AND id_categoria=$tipo";
    }
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function get_capa_tanque($id_vehiculo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_vehiculo, capacidad_tanque capaT FROM DAYF_VEHICULOS WHERE id_vehiculo = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo));
    $capaTanque = $p->fetch();
    Database::disconnect_sqlsrv();
    return $capaTanque;
  }

  static function get_empleados_activos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT distinct nombre, id_persona, id_secre, id_subsecre, id_direc
            FROM xxx_rrhh_ficha
            WHERE estado=1";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $tipos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $tipos;
  }

  static function genera_corte()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "EXEC saas_app.dbo.sp_Corte_Combustibles ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    Database::disconnect_sqlsrv();
  }

  static function get_correla()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT max(v_actual) as correl from tbl_correlativo_detalle where id_correlativo = 1140 ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $correl = $p->fetch();
    Database::disconnect_sqlsrv();
    return $correl;
  }

  //static function get_destino($id_tipo){
  static function get_destino($id_tipo)
  {

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT descripcion, id_item from tbl_catalogo_detalle where id_catalogo = 69 ";

    // revisar esto que fue comentado, para vales,  pero en cupones ?????
    if ($id_tipo == 2) {
      $sql .= ' AND id_item IN (1144,1147,5792,1145)';
    } else if ($id_tipo == 3) {
      $sql .= ' AND id_item IN (1144)';
    }

    $p = $pdo->prepare($sql);
    $p->execute(array()); // 65 es el id de aplicaciones
    $uso_combustible = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $uso_combustible;
  }

  static function get_bomba_comb($id_tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT c.id_bomba_despacho, rtrim(i.numero_serie)+' / '+RTRIM(p.descripcion) descripcion, c.id_bomba_combustible, id_tipo_combustible
      from dayf_combustible_corte_bombas c
      left outer join inv_producto_insumo_detalle i on c.id_bomba_despacho = i.id_prod_ins_detalle
      left outer join inv_producto_insumo p on c.id_bomba_combustible = p.id_producto_insumo
      where c.id_corte = (SELECT top 1 id_corte from dayf_combustible_corte order by id_corte desc)
      and c.id_tipo_combustible = ?
      ORDER BY c.id_corte_bomba ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_tipo)); // 65 es el id de aplicaciones
    $bomba_combus = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $bomba_combus;
  }

  static function get_devolCuponesEnc($id_documento)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT cdh.id_documento, cdh.fecha, cdh.nro_documento,
    cdh.id_estado_documento id_estado, isnull(pro.descripcion,' ') estado,
    cdh.id_persona_autorizo id_auto, isnull(aut.nombre_completo,' ') auto,
    cdh.id_evento, isnull(eve.descripcion,' ') evento, isnull(ope.nombre_completo,' ') opero,
    cdh.id_persona_recibe_cupon id_recibe, isnull(rec.nombre_completo,' ') recibe,
    cdh.total, cdh.total_devuelto devuelto, cdh.descripcion observa,
    cdh.total-cdh.total_devuelto utilizado, cdh.fecha_entrega, cdh.fecha_procesado
  from dayf_cupones_documento cdh
    left outer join tbl_catalogo_detalle pro on cdh.id_estado_documento = pro.id_item
    left outer join xxx_rrhh_persona aut on cdh.id_persona_autorizo = aut.id_persona
    left outer join tbl_catalogo_detalle eve on cdh.id_evento = eve.id_item
    left outer join xxx_rrhh_persona rec on cdh.id_persona_recibe_cupon = rec.id_persona
    left outer join xxx_rrhh_persona ope on cdh.id_persona_opero = ope.id_persona
  where id_documento = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_documento));
    $cupon = $p->fetch();
    Database::disconnect_sqlsrv();
    return $cupon;
  }

  static function get_devolCuponesDet($id_documento)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT
     id_cupon, id_documento, id_detalle, correlativo cupon, monto, usado, devuelto, usadoen, nombre,
    nro_placa placa, km_actual km, id_tipo_uso, radio, id_vehiculo, id_refer,
	id_departamento, id_municipio, referencia, caracteristicas
	from (
    select d.caracteristicas, d.id_cupon, d.id_documento, d.id_documento_detalle id_detalle, n.nro_cupon correlativo, n.monto,
      d.flag_utilizado usado, d.flag_devuelto devuelto, d.id_tipo_uso, isnull(u.descripcion,' ') usadoen,
      f.nombre, ISNULL(d.id_vehiculo,d.id_vehiculo_externo) id_vehiculo, p.nro_placa, d.km_actual,
      CASE WHEN d.flag_utilizado = 1 THEN 1 WHEN d.flag_devuelto = 1 THEN 2 ELSE 0 END AS radio,
	  d.id_persona id_refer, d.id_departamento, d.id_municipio, d.referencia
    from dayf_cupones_documento_detalle d
      left outer join dayf_cupones n on d.id_cupon = n.id_cupon
      left outer join tbl_catalogo_detalle u on d.id_tipo_uso = u.id_item
      left outer join dayf_vehiculo_placas p on d.id_vehiculo = p.id_vehiculo
      left outer join xxx_rrhh_Ficha f on d.id_persona = f.id_persona
    where id_documento = ? AND d.flag_utilizado=1 and p.reng_num in
            ( SELECT TOP (1) reng_num
              FROM dayf_vehiculo_placas AS p2
              WHERE p2.id_vehiculo = d.id_vehiculo )
    UNION
    select d.caracteristicas, d.id_cupon, d.id_documento, d.id_documento_detalle id_detalle, n.nro_cupon correlativo, n.monto,
      d.flag_utilizado usado, d.flag_devuelto devuelto, d.id_tipo_uso, isnull(u.descripcion,' ') usadoen,
      f.nombre, ISNULL(d.id_vehiculo,d.id_vehiculo_externo) id_vehiculo, p.nro_placa, d.km_actual,
      CASE WHEN d.flag_utilizado = 1 THEN 1 WHEN d.flag_devuelto = 1 THEN 2 ELSE 0 END AS radio,
	  d.id_persona id_refer, d.id_departamento, d.id_municipio, d.referencia
    from dayf_cupones_documento_detalle d
      left outer join dayf_cupones n on d.id_cupon = n.id_cupon
      left outer join tbl_catalogo_detalle u on d.id_tipo_uso = u.id_item
      left outer join dayf_vehiculos_externos p on d.id_vehiculo_externo = p.id_vehiculo_externo
      left outer join xxx_rrhh_Ficha f on d.id_persona = f.id_persona
    where id_documento = ? AND d.flag_utilizado=1 and d.id_tipo_uso in (1145,1147,5792)
    
    UNION
    select d.caracteristicas, d.id_cupon, d.id_documento, d.id_documento_detalle id_detalle, n.nro_cupon correlativo, n.monto,
      0 usado, d.flag_devuelto devuelto, 0 id_tipo_uso, ' ' usadoen, ' ' nombre, ' ' as id_vehiculo, ' ' nro_placa, 0 km_actual,
       case when c.id_estado_documento=4348 then 0 else 2 end AS radio,
	   0 id_refer, 0 id_departamento, 0 id_municipio, ' ' referencia
    from dayf_cupones_documento_detalle d
      left outer join dayf_cupones n on d.id_cupon = n.id_cupon
      left outer join dayf_cupones_documento c on d.id_documento = c.id_documento
    where d.id_documento = ? and d.flag_utilizado=0
    ) q order by correlativo";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_documento, $id_documento, $id_documento));
    $cupones = $p->fetchAll();
    // echo json_encode($cupones);
    Database::disconnect_sqlsrv();
    return $cupones;
  }

  static function get_departamentos($pais)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nombre, id_departamento
            FROM TBL_DEPARTAMENTO
            WHERE id_pais = ? AND id_auditoria = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($pais));
    $deptos = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $deptos;
  }

  static function get_municipios($id_departamento)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nombre, id_municipio
            FROM TBL_MUNICIPIO
            WHERE id_departamento = ? AND id_auditoria = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_departamento));
    $munic = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $munic;
  }

  static function get_departamentox($pais)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nombre, id_departamento
            FROM tbl_departamento
            WHERE id_pais = ? AND id_auditoria = 0";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($pais))) {
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
  static function get_all_productos_asignados_actual_by_bodega($id_persona, $bodega)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_doc_insumo, a.id_movimiento, a.id_prod_insumo,a.id_prod_insumo_detalle,
                   a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,b.id_status,
                   b.numero_serie,c.descripcion,d.id_persona,f.id_bodega_insumo,f.id_tipo_movimiento,c.id_tipo_insumo,
                   f.descripcion AS anotaciones,
                   CASE
				   WHEN c.id_tipo_insumo = 10 OR c.id_tipo_insumo = 11 OR c.id_tipo_insumo = 12 OR c.id_tipo_insumo = 18
				   OR c.id_tipo_insumo = 31 OR c.id_tipo_insumo = 34 OR c.id_tipo_insumo = 35 OR c.id_tipo_insumo = 40
				   OR c.id_tipo_insumo = 41 OR c.id_tipo_insumo = 42 OR c.id_tipo_insumo = 49 THEN 0 ELSE 1 END AS tipo,
           d.id_persona_diferente
            FROM inv_movimiento_detalle a
            INNER JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
            INNER JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
            INNER JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
            INNER JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
            INNER JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo

            WHERE d.id_persona=? AND f.id_estado_documento=4348 AND f.id_bodega_insumo=? AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada
            OR d.id_persona_diferente=? AND d.id_persona_diferente<>0 AND f.id_estado_documento=4348 AND f.id_bodega_insumo=? AND ISNULL(a.cantidad_devuelta,0)<a.cantidad_entregada

            ORDER BY a.id_movimiento ASC";
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
    $sql = "SELECT a.id_doc_insumo, a.id_movimiento, a.id_prod_insumo,a.id_prod_insumo_detalle,
                   a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,b.id_status,
                   b.numero_serie,c.descripcion,d.id_persona,f.id_bodega_insumo,f.id_tipo_movimiento,c.id_tipo_insumo,
                   f.descripcion AS anotaciones, d.id_persona_diferente
            FROM inv_movimiento_detalle a
            INNER JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
            INNER JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
            INNER JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
            LEFT JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
            INNER JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo

            WHERE d.id_persona=? AND f.id_bodega_insumo=?
            AND ISNULL(a.cantidad_entregada,0)<a.cantidad_devuelta

            AND f.id_tipo_movimiento=10

            OR d.id_persona_diferente=? AND f.id_bodega_insumo=?
            AND ISNULL(a.cantidad_entregada,0)<a.cantidad_devuelta

            AND f.id_tipo_movimiento=10

            ORDER BY a.id_movimiento ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona, $bodega, $id_persona, $bodega)); // 65 es el id de aplicaciones
    $moves = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $moves;
  }
  static function get_tipos_movimientos($tipo_tr, $subtipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($subtipo == 2) {
      $sql = "SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=? AND id_tipo_movimiento=7";
      $p = $pdo->prepare($sql);
    } else {
      $sql = "SELECT id_tipo_movimiento, descripcion, descripcion_corta, id_tipo_transaccion
              FROM inv_tipo_movimiento WHERE id_tipo_transaccion=? AND id_tipo_movimiento NOT IN (5,6,1,7,8,9)";
      $p = $pdo->prepare($sql);
    }

    $p->execute(array($tipo_tr)); // 65 es el id de aplicaciones
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
           WHERE a.numero_serie=?";
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
    /*$sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, ISNULL(a.flag_resguardo, 0) AS resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia, d.descripcion AS estado,e.id_tipo_insumo,e.descripcion_corta AS tipo


            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
            INNER JOIN tbl_catalogo_detalle d ON a.id_status=d.id_item
            INNER JOIN inv_tipo_insumo e ON b.id_tipo_insumo=e.id_tipo_insumo
            WHERE b.id_bodega_insumo=?
           ";*/
    $sql = "SELECT * FROM vw_insumos_por_bodega_estado WHERE id_bodega_insumo=$bodega
    ORDER BY id_prod_ins_detalle ASC";


    /*SELECT        EE.id_bodega_insumo, EE.modelo, EE.marca, EE.id_prod_insumo, EE.id_prod_ins_detalle, EE.id_status, EE.numero_serie, EE.flag_asignado, EE.resguardo, EE.flag_uso_compartido, EE.id_propietario, EE.existencia,
                         EE.estado, EE.id_tipo_insumo, EE.tipo, EE.asignacion, EE.sicoin, rper.primer_nombre, rper.segundo_nombre, rper.tercer_nombre, rper.primer_apellido, rper.segundo_apellido, rper.tercer_apellido, rper.id_persona,
                         EE.id_sub_tipo_insumo, per.id_persona_direccion_recibe, EE.codigo_inventarios
FROM            (SELECT        a.id_bodega_insumo, f.descripcion AS modelo, c.descripcion AS marca, a.id_prod_insumo, a.id_prod_ins_detalle, a.id_status, a.numero_serie, a.flag_asignado, ISNULL(a.flag_resguardo, 0) AS resguardo,
                                                    a.flag_uso_compartido, a.id_propietario, a.existencia, d.descripcion AS estado, e.id_tipo_insumo, e.descripcion_corta AS tipo, a.codigo_inventarios, CASE WHEN a.id_status = 5338 OR
                                                    a.id_status = 5339 OR
                                                    a.id_status = 5491 OR
                                                    a.id_status = 7733 THEN
                                                        (SELECT        TOP 1 mm.id_doc_insumo AS codigo
                                                          FROM            inv_movimiento_detalle mm
                                                          WHERE        mm.id_prod_insumo_detalle = a.id_prod_ins_detalle
                                                          ORDER BY mm.id_doc_insumo DESC) ELSE '' END AS asignacion, a.codigo_inventarios AS sicoin, b.id_sub_tipo_insumo
                          FROM            dbo.inv_producto_insumo_detalle AS a INNER JOIN
                                                    dbo.inv_producto_insumo AS b ON a.id_prod_insumo = b.id_producto_insumo INNER JOIN
                                                    dbo.inv_marca_producto AS c ON b.id_marca = c.id_marca INNER JOIN
                                                    dbo.tbl_catalogo_detalle AS d ON a.id_status = d.id_item INNER JOIN
                                                    dbo.inv_tipo_insumo AS e ON b.id_tipo_insumo = e.id_tipo_insumo INNER JOIN
                                                    dbo.inv_modelos AS f ON b.id_modelo = f.id_modelo) AS EE LEFT OUTER JOIN
                         dbo.inv_movimiento_persona_asignada AS per ON per.id_doc_insumo = EE.asignacion LEFT OUTER JOIN
                         dbo.rrhh_persona AS rper ON per.id_persona = rper.id_persona*/
    $p = $pdo->prepare($sql);
    $p->execute(array()); // 65 es el id de aplicaciones
    $insumos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_top_transaccion_by_producto($id_producto)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP 1 mm.id_doc_insumo AS codigo, nn.id_persona, oo.primer_nombre, oo.segundo_nombre, oo.tercer_nombre, oo.primer_apellido, oo.segundo_apellido, oo.tercer_apellido
          FROM inv_movimiento_detalle mm
          INNER JOIN inv_movimiento_persona_asignada nn ON mm.id_doc_insumo=nn.id_doc_insumo
          INNER JOIN rrhh_persona oo ON nn.id_persona=oo.id_persona
          WHERE mm.id_prod_insumo_detalle = ?
          ORDER BY mm.id_doc_insumo DESC
           ";

    $p = $pdo->prepare($sql);
    $p->execute(array($id_producto)); // 65 es el id de aplicaciones
    $transaccion = $p->fetch();
    Database::disconnect_sqlsrv();
    return $transaccion;
  }

  static function get_all_insumos_by_bodega_asignar($bodega)
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
            WHERE b.id_bodega_insumo=? AND a.id_status<>?
           ";

    $p = $pdo->prepare($sql);
    $p->execute(array($bodega, 5347)); // 65 es el id de aplicaciones
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
  static function get_insumos_by_transaccion($transaccion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie,
                   a.flag_uso_compartido,
                   a.id_propietario,a.existencia,
                   e.id_tipo_movimiento,
                   d.cantidad_entregada,
                   d.cantidad_devuelta,
                   f.descripcion_corta,
                   CONVERT(VARCHAR(10), CAST(e.fecha AS DATETIME), 103) AS [fecha],
                   e.id_doc_insumo,
                   e.id_persona_entrega,
                   a.codigo_inventarios

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
                     a.descripcion
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
    $sql = "SELECT a.id_bodega_insumo, b.descripcion AS modelo,c.descripcion AS marca, a.id_prod_insumo,
                   a.id_prod_ins_detalle, a.id_status,
                   a.numero_serie, a.flag_asignado, ISNULL(a.flag_resguardo, 0) AS resguardo,a.flag_uso_compartido,
                   a.id_propietario,a.existencia, d.descripcion AS estado,e.id_tipo_insumo,e.descripcion_corta AS tipo


            FROM inv_producto_insumo_detalle a
            INNER JOIN inv_producto_insumo b ON a.id_prod_insumo=b.id_producto_insumo
            INNER JOIN inv_marca_producto c ON b.id_marca=c.id_marca
            INNER JOIN tbl_catalogo_detalle d ON a.id_status=d.id_item
            INNER JOIN inv_tipo_insumo e ON b.id_tipo_insumo=e.id_tipo_insumo
            WHERE b.id_bodega_insumo=? AND a.id_status=?
           ";
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

    $tipo_ = 'AND id_tipo_insumo NOT IN (10,11,12,18,31,34,35,40,41,42,49)';

    if ($bodega == 3552) {
      $cabeceras = "COUNT(case marca when 'MOTOROLA' then 1 else null end) as MOTOROLA, COUNT(case marca when 'CHICOM' then 1 else null end) as CHICOM, COUNT(case when marca in ('HYTERA TC-508', 'HYT') then 1 else null end) as HYTERA, COUNT(case marca when 'BOAFENG' then 1 else null end) as BOAFENG, COUNT(case marca when 'KENWOOD' then 1 else null end) as KENWOOD, COUNT(case marca when 'VERTEX' then 1 else null end) as VERTEX";
      if ($tipo == 400) {
        $tipo_ = "AND id_tipo_insumo = 8";
      } elseif ($tipo == 800) {
        $tipo_ = "AND id_tipo_insumo = 9";
      }
    }
    if ($bodega == 5907) {
      $cabeceras = "COUNT(CASE marca WHEN 'HUAWEI' THEN 1 ELSE NULL END) AS HUAWEI, COUNT(CASE marca WHEN 'IPHONE' THEN 1 ELSE NULL END) AS IPHONE, COUNT(case when marca in ('%SAMSUNG%', 'SAMSUNG') then 1 else null end) as SAMSUNG";
    }
    if ($bodega == 5066) {
      $cabeceras = "COUNT(CASE marca WHEN 'DAEWOO' THEN 1 ELSE NULL END) AS DAEWOO, COUNT(CASE marca WHEN 'UZI' THEN 1 ELSE NULL END) AS UZI, COUNT(CASE marca WHEN 'KALASHNIKOV' THEN 1 ELSE NULL END) AS KALASHNIKOV, COUNT(CASE marca WHEN 'MEPOR 21' THEN 1 ELSE NULL END) AS MEPOR21, COUNT(CASE marca WHEN 'Eagle' THEN 1 ELSE NULL END) AS EAGLE, COUNT(CASE marca WHEN 'ROSSI' THEN 1 ELSE NULL END) AS ROSSI, COUNT(CASE marca WHEN 'Generico' THEN 1 ELSE NULL END) AS GENERICO, COUNT(CASE marca WHEN 'Desantis' THEN 1 ELSE NULL END) AS DESANTIS, COUNT(CASE marca WHEN 'GALIL' THEN 1 ELSE NULL END) AS GALIL, COUNT(CASE marca WHEN 'CAA' THEN 1 ELSE NULL END) AS CAA, COUNT(CASE marca WHEN 'VALTRO' THEN 1 ELSE NULL END) AS VALTRO, COUNT(CASE marca WHEN 'FN HERSTAL' THEN 1 ELSE NULL END) AS FNHERSTAL, COUNT(CASE marca WHEN 'JERICHO' THEN 1 ELSE NULL END) AS JERICHO, COUNT(CASE marca WHEN 'TAURUS' THEN 1 ELSE NULL END) AS TAURUS, COUNT(CASE marca WHEN 'IMI' THEN 1 ELSE NULL END) AS IMI, COUNT(CASE marca WHEN 'TANFOGLIO' THEN 1 ELSE NULL END) AS TANFOGLIO, COUNT(CASE marca WHEN 'Fobus' THEN 1 ELSE NULL END) AS FOBUS, COUNT(CASE marca WHEN 'S/M' THEN 1 ELSE NULL END) AS SM, COUNT(CASE marca WHEN 'Blackhawk' THEN 1 ELSE NULL END) AS BLACKHAWK, COUNT(CASE marca WHEN 'STOEGER' THEN 1 ELSE NULL END) AS STOEGER, COUNT(CASE marca WHEN 'PIETRO BERETTA' THEN 1 ELSE NULL END) AS BERETTA, COUNT(CASE marca WHEN 'CZ' THEN 1 ELSE NULL END) AS CZ, COUNT(CASE marca WHEN 'BUSHNELL' THEN 1 ELSE NULL END) AS BUSHNELL, COUNT(CASE marca WHEN 'A NAJMAN' THEN 1 ELSE NULL END) AS ANAJMAN, COUNT(CASE marca WHEN 'SIG PRO' THEN 1 ELSE NULL END) AS SIGPRO, COUNT(CASE marca WHEN 'A TAVOR' THEN 1 ELSE NULL END) AS TAVOR, COUNT(CASE marca WHEN 'S.E.G. ARMOR' THEN 1 ELSE NULL END) AS SEGARMOR, COUNT(CASE marca WHEN 'COLT' THEN 1 ELSE NULL END) AS COLT, COUNT(CASE marca WHEN 'GLOCK' THEN 1 ELSE NULL END) AS GLOCK, COUNT(CASE marca WHEN 'REMINGTON' THEN 1 ELSE NULL END) AS REMINGTON";
    }


    $sql = "SELECT estado AS ESTADO, $cabeceras
    FROM (
    SELECT estado, marca
    FROM vw_insumos_por_bodega_estado_nuevo
    WHERE id_bodega_insumo=? $tipo_
    ) as pivote
    GROUP BY estado
    ORDER BY estado ASC";

    // $cabeceras='';
    // $group='';
    // $tipo_insumo='';
    // $tipo_='';
    // if($bodega==3552){
    //   $cabeceras='MOTOROLA,Chicom,[HYTERA TC-508] as HYTERA,HYT,BOAFENG,KENWOOD,VERTEX';
    //   $group='MOTOROLA,Chicom,[HYTERA TC-508],HYT,BOAFENG,KENWOOD,VERTEX';

    //   if($tipo==400){
    //     $tipo_="AND id_tipo_insumo = 8";
    //   }else if($tipo==800){
    //     $tipo_="AND id_tipo_insumo = 9";
    //   }else{
    //     $tipo_insumo='';
    //     $tipo_='AND id_tipo_insumo NOT IN (10,11,12,18,31,34,35,40,41,42,49)';
    //   }
    // }
    // $sql = "SELECT estado, $cabeceras FROM
    //          (SELECT
    //            estado,
    //            marca,
    //            id_prod_ins_detalle AS VALUE
    //         FROM vw_insumos_por_bodega_estado
    //         WHERE id_bodega_insumo=? $tipo_
    //       ) x
    //       PIVOT
    //       (
    //         COUNT(x.VALUE)
    //         FOR x.marca IN ([Gasolina],[COLT],[CZ],[DAEWOO],[FN HERSTAL],[FRANCHI],[GALIL],[GLOCK],[JERICHO],[KALASHNIKOV],[PIETRO BERETTA],[ROSSI],[SIG PRO],[TANFOGLIO],[TAURUS],[UZI],[VALTRO],[STOEGER],[TAVOR],[MOTOROLA],[OTTO],[Blackhawk],[Eagle],[Fobus],[Generico],[Desantis],[icom],[VERTEX],[CAA],[A NAJMAN],[S/M],[MEPOR 21],[BUSHNELL],[BLACKBERRY],[TIGO],[SANDISK],[HUAWEI],[IPHONE],[NOKIA],[KENWOOD],[SAMSUNG],[LG],[SONY],[ALCATEL],[HTC],[CLARO],[MOVISTAR],[ZTE],[HYT],[CARGADOR HYT],[REMINGTON],[S.E.G. ARMOR],[B683],[audifono sin marca],[IPHONE 6 PLUS SPACE 64 GB],[Chicom],[Bmobile AX 660],[Microsoft],[Instructivo Operativo de Transmisiones],[SAMSUNG GALAXY J3],[BMOBILE],[HYTERA TC-508],[BOAFENG],[IMI],[DESERT EAGLE])
    //       ) p
    //       GROUP BY estado,$group
    //        ";
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
    $tipo_insumo = '';
    $tipo_ = '';
    if ($bodega == 3552) {
      $cabeceras = 'MOTOROLA,Chicom,[HYTERA TC-508] as HYTERA,HYT,BOAFENG,KENWOOD,VERTEX';
      $group = 'MOTOROLA,Chicom,[HYTERA TC-508],HYT,BOAFENG,KENWOOD,VERTEX';

      if ($tipo == 400) {
        $tipo_ = "AND id_tipo_insumo = 8";
      } else if ($tipo == 800) {
        $tipo_ = "AND id_tipo_insumo = 9";
      } else {
        $tipo_insumo = '';
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
    $sql = "SELECT a.id_doc_solvencia, a.year, a.correlativo_solvencia, a.id_encargado,a.id_persona_solvencia,a.id_direccion_solvencia, a.id_tipo_solvencia, a.id_estado,a.fecha_solvencia, a.observaciones, a.id_solvente,
                   b.id_doc_solvencia_detalle, b.id_persona_en_caliente, b.id_direccion_en_caliente, b.id_prod_insumo_detalle, b.cantidad, b.id_estado, b.anotaciones,
				   c.numero_serie, c.codigo_inventarios, e.descripcion AS marca, f.descripcion AS modelo,
				   CASE
				   WHEN a.id_tipo_solvencia=1 THEN 'VACACIONES'
				   WHEN a.id_tipo_solvencia=2 THEN 'BAJA'
				   WHEN a.id_tipo_solvencia=3 THEN 'REMOCION'
				   WHEN a.id_tipo_solvencia=4 THEN 'TRASLADO' END AS tipo_solvencia,
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

  static function get_vehiculos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_vehiculo,id_propietario,propietario,nro_placa,chasis,motor,
                   modelo,flag_franjas_de_color,detalle_franjas,observaciones,capacidad_tanque,kilometros_x_galon,
                   id_tipo_combustible,nombre_tipo_combustible,poliza_seguro,id_empresa_seguros,nombre_empresa_seguros,
                   id_linea,nombre_linea,id_color,nombre_color,id_marca,nombre_marca,id_tipo,nombre_tipo,id_uso,nombre_uso,
                   id_status,nombre_estado,id_servicio,km_servicio_proyectado,km_actual,km_ultimo_servicio,id_tipo_asignacion,
                   nombre_tipo_asignacion,id_giras,id_persona_asignado,nombre_persona_asignado,id_direccion,nombre_direccion,
                   id_persona_autoriza,nombre_persona_autoriza,codigo_interno,flag_devuelto
             FROM xxx_dayf_vehiculos";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $vehiculos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $vehiculos;
  }

  static function get_fotografia_by_vehiculo($id_vehiculo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  id_vehiculo
              ,reng_num
              ,id_tipo_foto
              ,foto_principal
              ,foto
              ,descripcion
              ,id_auditoria
          FROM dayf_vehiculo_fotografias
          WHERE id_vehiculo = ? AND foto_principal = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo, 1));
    $fotografia = $p->fetch();
    Database::disconnect_sqlsrv();
    return $fotografia;
  }

  static function get_vehiculo_by_id($id_vehiculo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_vehiculo,id_propietario,propietario,nro_placa,chasis,motor,
                   modelo,flag_franjas_de_color,detalle_franjas,observaciones,capacidad_tanque,kilometros_x_galon,
                   id_tipo_combustible,nombre_tipo_combustible,poliza_seguro,id_empresa_seguros,nombre_empresa_seguros,
                   id_linea,nombre_linea,id_color,nombre_color,id_marca,nombre_marca,id_tipo,nombre_tipo,id_uso,nombre_uso,
                   id_status,nombre_estado,id_servicio,km_servicio_proyectado,km_actual,km_ultimo_servicio,id_tipo_asignacion,
                   nombre_tipo_asignacion,id_giras,id_persona_asignado,nombre_persona_asignado,id_direccion,nombre_direccion,
                   id_persona_autoriza,nombre_persona_autoriza,codigo_interno,flag_devuelto
             FROM xxx_dayf_vehiculos
             WHERE id_vehiculo = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo));
    $vehiculo = $p->fetch();
    Database::disconnect_sqlsrv();
    return $vehiculo;
  }
  static function get_cupones_disponibles($cupon_ini, $cupon_fin)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_cupon, nro_cupon, monto FROM dayf_cupones WHERE id_cupon BETWEEN ? AND ? AND id_estado_cupon = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($cupon_ini, $cupon_fin, 1913));
    $cupones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $cupones;
  }

  static function get_cupones_disponibles_para_seleccion()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_cupon, nro_cupon, monto FROM dayf_cupones WHERE id_estado_cupon = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array(1913));
    $cupones = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $cupones;
  }

  static function get_servicios($f_ini, $f_fin, $estado)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nro_orden,
    nro_placa,
    chasis,
    nombre_tipo,
    motor,
    nombre_marca AS marca,
    modelo,
    nombre_linea AS linea,
    nombre_color AS color,
    id_servicio, id_vehiculo,
    tipo_vehiculo,
    nombre_taller,
    direccion,
    telefono,
    id_tipo_servicio,
    tipo_servicio,
    km_vehiculo,
    km_servicio,
    km_servicio_proyectado,
    nombre_recepcion_persona_recibe,
    CONVERT(VARCHAR,fecha_recepcion,23) AS fecha_recepcion,
    fecha_entregado, fecha_asignacion_mecanico,
    [dbo].[RTF2Text](descripcion_solicitado) AS [desc_solicitado],
    [dbo].[RTF2Text](descripcion_solicitado) AS [desc_solicitado],
    [dbo].[RTF2Text](descripcion_realizado) AS [desc_realizado],
    id_status,
    nombre_estado_servicio AS estado
    FROM xxx_dayf_vehiculo_servicios
    WHERE id_status = ?";

    if ($estado == 5489) {
      $sql .= " AND CONVERT(VARCHAR, fecha_recepcion, 23) BETWEEN '2022-01-01' AND '2022-05-26' ";
    }

    $sql .= 'ORDER BY fecha_recepcion DESC';

    $p = $pdo->prepare($sql);
    $p->execute(array($estado));
    $servicios = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $servicios;
  }

  static function get_servicio_by_id($id_servicio)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT nro_orden, nro_placa, chasis, nombre_tipo, motor, nombre_marca AS marca, modelo, nombre_linea AS linea, nombre_color AS color,
            id_servicio, id_vehiculo, tipo_vehiculo, nombre_taller,
            direccion, telefono,id_tipo_servicio, tipo_servicio, km_vehiculo, km_servicio, km_servicio_proyectado, nombre_recepcion_persona_recibe,
            fecha_recepcion, fecha_entregado, fecha_asignacion_mecanico, [dbo].[RTF2Text](descripcion_solicitado) AS [desc_solicitado],
            [dbo].[RTF2Text](descripcion_solicitado) AS [desc_solicitado], [dbo].[RTF2Text](descripcion_realizado) AS [desc_realizado],
            descripcion_solicitado AS obs_solicitado,
            id_status, nombre_estado_servicio AS estado
            FROM xxx_dayf_vehiculo_servicios
            WHERE id_servicio = ?
            ";

    $p = $pdo->prepare($sql);
    $p->execute(array($id_servicio));
    $servicio = $p->fetch();
    Database::disconnect_sqlsrv();
    return $servicio;
  }
}


/*SELECT a.id_doc_insumo, a.id_movimiento, a.id_prod_insumo,a.id_prod_insumo_detalle,
               a.cantidad_entregada,a.cantidad_devuelta,a.cantidad_consumida,b.id_status,
               b.numero_serie,c.descripcion,d.id_persona,f.id_bodega_insumo,f.id_tipo_movimiento,c.id_tipo_insumo,
               f.descripcion AS anotaciones
        FROM inv_movimiento_detalle a
        INNER JOIN inv_producto_insumo_detalle b ON a.id_prod_insumo_detalle=b.id_prod_ins_detalle
        INNER JOIN inv_producto_insumo c ON a.id_prod_insumo=c.id_producto_insumo
        INNER JOIN inv_movimiento_persona_asignada d ON a.id_doc_insumo=d.id_doc_insumo
        LEFT JOIN tbl_catalogo_detalle e ON b.id_status=e.id_item
        INNER JOIN inv_movimiento_encabezado f ON a.id_doc_insumo=f.id_doc_insumo

        WHERE d.id_persona=? AND f.id_bodega_insumo=?
        AND ISNULL(a.cantidad_entregada,0)<a.cantidad_devuelta

        AND f.id_tipo_movimiento=10

        ORDER BY a.id_movimiento ASC*/