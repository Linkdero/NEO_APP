<?php
class documento
{
  public $documento_id;

  static function get_estado_doc_respaldo($estado){
    if(!empty($estado)){
      $state = array(0 => 'Anulado', 1 => 'Activo', 2 => 'Aprobado');
      return $estado[$estado];
    }
  }

  static function get_documentos_listado()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.docto_id,a.docto_titulo,a.docto_fecha, b.descripcion AS dir, REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0') AS docto_correlativo,a.docto_year,
              b.descripcion_corta AS dir_c, c.descripcion AS doc_categoriacorta, c.descripcion_corta AS doc_categoria,
              CASE WHEN c.id_item IN (8020,8022,8046,8047,8048,8049) THEN c.descripcion_corta+ ' SAAS No. '+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+ CAST(a.docto_year AS varchar)
			  ELSE 'SAAS-'+b.descripcion_corta+'-'+c.descripcion_corta+'-'+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+CAST(a.docto_year AS varchar) END as correlativo,
              /*docto_destinatario = COALESCE(STUFF((
              SELECT ', '+c.descripcion AS des,
                                     a.docto_id
                                     FROM docto_encabezado AS a
                                     LEFT JOIN docto_destinatario AS b
                                     ON b.docto_id=a.docto_id
                                     LEFT JOIN rrhh_direcciones AS c
                                     ON b.direccion_id=c.id_direccion
                                     WHERE b.tipo LIKE 1 and b.status LIKE 1
                                     FOR XML PATH(N''),TYPE).value(N'.[1]',N'nvarchar(max)'),1,2, N''
              ),N''),
              docto_destinatario_cc = COALESCE(STUFF((
              SELECT ', '+c.descripcion AS des,
                                     a.docto_id
                                     FROM docto_encabezado AS a
                                     LEFT JOIN docto_destinatario AS b
                                     ON b.docto_id=a.docto_id
                                     LEFT JOIN rrhh_direcciones AS c
                                     ON b.direccion_id=c.id_direccion
                                     WHERE b.tipo LIKE 2 and b.status LIKE 1
                                     FOR XML PATH(N''),TYPE).value(N'.[1]',N'nvarchar(max)'),1,2, N''
              ),N''),*/
              a.docto_descripcion,a.docto_categoria,a.docto_nombre,a.docto_respuesta,a.docto_respondido,a.docto_tipo_emision,a.docto_correlativo_externo
              FROM docto_encabezado a
              LEFT JOIN rrhh_direcciones b ON a.docto_direccion_id=b.id_direccion
              LEFT JOIN tbl_catalogo_detalle c ON a.docto_categoria=c.id_item
              WHERE a.docto_categoria NOT IN (8091)
              ORDER BY docto_id DESC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $documentos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $documentos;

    /*if(!empty($direccion)){
      $sql.="WHERE docto_depto_id=$direccion";
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
    return $nombramientos;*/
  }

  static function get_documento_by_id($documento_id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_catalogo, id_item, descripcio
            FROM tbl_pais
            WHERE id_auditoria = 0 AND id_pais<>?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array('GT', 'GT'))) {
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

  static function get_documentos_categoria()
  {
    if (usuarioPrivilegiado()->hasPrivilege(2)) {
    }
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_catalogo, id_item, descripcion, descripcion_corta
            FROM tbl_catalogo_detalle
            WHERE id_catalogo=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array(142))) {
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

  static function get_destinatarios()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_direccion, id_nivel, id_superior, id_tipo, descripcion, descripcion_corta
            FROM rrhh_direcciones
            WHERE id_tipo=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array(887))) {
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

  static function genera_correlativo_documento($direccion, $depto, $categoria, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT MAX(docto_correlativo) AS id FROM docto_encabezado
             WHERE docto_direccion_id=? AND YEAR(docto_fechacreacion)=? AND docto_categoria=? AND docto_tipo_emision=? ";
    if ($depto > 0) {
      $sql0 .= "AND docto_depto_id=$depto";
    }
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($direccion, date('Y'), $categoria, $tipo));
    $ca = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $ca;
  }

  static function get_correlativo_generado($id_persona)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 docto_id FROM docto_encabezado
             WHERE docto_creador=? ORDER BY docto_id DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_persona));
    $ca = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $ca;
  }

  static function generar_documento_word($docto_id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.docto_id,a.docto_titulo,a.docto_nombre,a.docto_fecha, b.descripcion AS dir, REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0') AS docto_correlativo,a.docto_year,
              b.descripcion_corta AS dir_c,
              c.descripcion_corta AS tipo,
              a.docto_categoria,
              CASE WHEN c.id_item IN (8020,8022,8041,8046,8047,8048,8049) THEN 'right' ELSE 'center' END AS alineacion,
              CASE WHEN c.id_item IN (8048,8049) THEN 'true' ELSE 'false' END AS validacion,
              CASE WHEN c.id_item IN (8020,8022,8046,8047,8048,8049) THEN c.descripcion_corta+ ' SAAS No. '+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+ CAST(a.docto_year AS varchar)
              ELSE c.descripcion+'-'+'SAAS-'+b.descripcion_corta+'-'+ISNULL(d.descripcion_corta+'-', '')+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+CAST(a.docto_year AS varchar) END as correlativo,
              c.descripcion AS doc_nom, c.descripcion_corta AS doc_nomc,
              a.docto_descripcion, b.descripcion AS direccion, d.descripcion AS departamento, e.pedido_tra, e.pedido_tipo, e.pedido_diagnostico, e.pedido_diagnostico_list,
              a.docto_temporalidad,a.docto_finalidad,a.docto_resultados
              FROM docto_encabezado a
              LEFT JOIN rrhh_direcciones b ON a.docto_direccion_id=b.id_direccion
              LEFT JOIN tbl_catalogo_detalle c ON a.docto_categoria=c.id_item
              LEFT JOIN rrhh_departamentos d ON a.docto_depto_id=d.id_departamento
              LEFT JOIN docto_pedido e ON a.docto_id=e.docto_id
              WHERE a.docto_id=?
              ";

    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($docto_id));
    $documento = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $documento;
  }

  static function get_base_detalle($docto_id, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT b.docto_id, c.id_item, c.descripcion,c.descripcion_corta,b.fecha,b.valor
            FROM docto_base_detalle b
            INNER JOIN tbl_catalogo_detalle c ON b.base_detalle_id=c.id_item
            WHERE b.docto_id=? AND b.base_id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($docto_id, $tipo))) {
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

  static function get_cronograma($docto_id, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT b.docto_id, b.base_id, c.id_item, c.descripcion,c.descripcion_corta,b.fecha,b.valor,d.base_literal_descripcion
            FROM docto_base_detalle b
            INNER JOIN tbl_catalogo_detalle c ON b.base_detalle_id=c.id_item
			LEFT JOIN docto_base_literal d ON b.base_detalle_id=(CAST(d.base_literal_nom AS int)) AND d.base_id=8054
            WHERE b.docto_id=? AND b.base_id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($docto_id, $tipo))) {
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

  static function get_base_literales()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT base_id, base_literal_id FROM docto_base_literal WHERE base_literal_status=? AND base_literal_id BETWEEN ? AND ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array(1, 1, 48))) {
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

  static function get_base_literales_by_docto($docto_id, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.docto_id,b.base_id, b.base_literal_id,b.base_literal_nom, b.base_literal_titulo, b.base_literal_descripcion
            FROM docto_base_literal_asignacion a
            LEFT JOIN docto_base_literal AS b ON a.base_literal_id=b.base_literal_id
            WHERE a.docto_id=? AND b.base_id=?
            ORDER BY base_literal_nom ASC";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($docto_id, $tipo))) {
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

  /* inicio justificaciones */

  static function get_justificaciones_listado($depto, $dir)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.docto_id,a.docto_titulo,a.docto_fecha, b.descripcion AS dir, REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0') AS docto_correlativo,a.docto_year,
              b.descripcion_corta AS dir_c, c.descripcion AS doc_categoriacorta, c.descripcion_corta AS doc_categoria,
              CASE WHEN c.id_item IN (8020,8022,8046,8047,8048,8049) THEN c.descripcion_corta+ ' SAAS No. '+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+ CAST(a.docto_year AS varchar)
              ELSE c.descripcion+'-'+'SAAS-'+b.descripcion_corta+'-'+ISNULL(d.descripcion_corta+'-', '')+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+CAST(a.docto_year AS varchar) END as correlativo,
              a.docto_descripcion,a.docto_categoria,a.docto_nombre,a.docto_respuesta,a.docto_respondido,a.docto_tipo_emision,a.docto_correlativo_externo,d.descripcion AS departamento,e.pedido_tra, e.pedido_tipo, e.pedido_diagnostico, e.pedido_diagnostico_list,
              a.docto_temporalidad,a.docto_finalidad,a.docto_resultados,f.ped_num
              FROM docto_encabezado a
              LEFT JOIN rrhh_direcciones b ON a.docto_direccion_id=b.id_direccion
              LEFT JOIN tbl_catalogo_detalle c ON a.docto_categoria=c.id_item
              LEFT JOIN rrhh_departamentos d ON a.docto_depto_id=d.id_departamento
              LEFT JOIN docto_pedido e ON a.docto_id=e.docto_id
              LEFT JOIN APP_POS.dbo.PEDIDO_E f ON e.pedido_tra=f.ped_tra
              WHERE a.docto_categoria IN (8091)";
    if ($depto != 0) {
      $sql0 .= " AND a.docto_depto_id=$depto ";
    } else {
      $sql0 .= " AND a.docto_direccion_id=$dir ";
    }
    $sql0 .= "ORDER BY a.docto_id DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $documentos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $documentos;
  }

  static function get_pedidos_remesas_by_secretaria($fecha,$tipo)
  {
    //$dir_pos = self::devuelve_direccion_app_pos($direccion);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra,a.ped_num,a.ped_fec, a.ped_obs, c.Dir_com AS direccion, b.Uni_Nom AS departamento,
                    d.descripcion AS estado, e.descripcion AS verificacion,
                    CASE WHEN a.Ped_status IN (8147,8144,8141,8170) THEN 'danger' ELSE 'info' END AS color,
                    CASE
                    WHEN a.Ped_status IN (8156) THEN 15
                    WHEN a.Ped_status = 8139 AND a.Ped_bitacora_id = 8156 THEN 20
                    WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 0 THEN 5
                    WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 8157 THEN 100
                    WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 0 THEN 25
                    WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 8157 THEN 30

                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 0 THEN 35
                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8139 THEN 37
                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8142 THEN 40
                    WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id  = 0 THEN 45

                    WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id = 8161 THEN 50

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 0 THEN 55

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8145 THEN 60

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8148 THEN 70
                    WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 0 THEN 75
                    WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 8149 THEN 80

                    WHEN a.Ped_status IN (8147) THEN 100
                    WHEN a.Ped_status IN (8144) THEN 100
                    WHEN a.Ped_status IN (8170) THEN 100
                    ELSE 0 END AS porcentaje, f.id_persona_asignada, ISNULL(g.primer_nombre,'')+' '+ISNULL(g.segundo_nombre, '')+' '+ISNULL(g.tercer_nombre,'')+' '+ISNULL(g.primer_apellido,'')+' '+ISNULL(g.segundo_apellido,'')+' '+ISNULL(g.tercer_apellido,'') AS asignado,
                    a.Ped_num_interno, a.Pac_id,a.Ped_status
             FROM APP_POS.dbo.PEDIDO_E a
             INNER JOIN APP_POS.dbo.UNIDAD b ON a.Uni_cor=b.Uni_cor
             INNER JOIN APP_POS.dbo.DIRECCION c ON b.Dir_cor=c.Dir_cor
             LEFT JOIN tbl_catalogo_detalle d ON a.Ped_status = d.id_item
             LEFT JOIN tbl_catalogo_detalle e ON a.Ped_bitacora_id = e.id_item
             LEFT JOIN docto_ped_seguimiento_asignado f ON f.Ped_tra = a.Ped_tra AND f.status_id = 1
             LEFT JOIN rrhh_persona g ON f.id_persona_asignada = g.id_persona
             WHERE YEAR(a.ped_fec) = ? ";
             if($tipo == 0){
               $u = usuarioPrivilegiado();
               if ($u->hasPrivilege(301)){
                  //echo '0';
               }
               else if ($u->hasPrivilege(302) ){
                 $sql0.="AND a.Ped_status IN (8143,8161,8164,8145,8148) ";
                  //echo '3';
               }
               else if($u->hasPrivilege(308) ){
                 $sql0.="AND a.Ped_status IN (8140,8157,8160) ";
                  //echo '2';
               }
               else if($u->hasPrivilege(311)){
                 $sql0.="AND a.Ped_status IN (8156,8139) OR YEAR(a.ped_fec) = '2022' AND ISNULL(a.Ped_status,0) = 0 ";
                 //echo '1';

               }
             }else if($tipo == 2){
               $u = usuarioPrivilegiado();
               if ($u->hasPrivilege(301)){
                  //echo '0';
               }
               else if ($u->hasPrivilege(302) ){
                 $sql0.="AND a.Ped_status IN (8146,8149) ";
                  //echo '3';
               }
               else if($u->hasPrivilege(308) ){
                 $sql0.="AND a.Ped_status IN (8143,8161,8164,8145,8148,8140,8157,8160) ";
                  //echo '2';
               }
               else if($u->hasPrivilege(311)){
                 $sql0.="AND a.Ped_status IN (8140,8157,8143,8161,8164,8145,8148,8140,8157,8160) ";
                 //echo '1';

               }
             }

             $sql0.="ORDER BY a.ped_tra DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($fecha));
    $pedidos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $pedidos;
  }

  static function get_pedidos_remesas_by_secretaria_tipo($fecha, $tipo)
  {
    //$dir_pos = self::devuelve_direccion_app_pos($direccion);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT DISTINCT a.ped_tra,a.ped_num,a.ped_fec, a.ped_obs, c.Dir_com AS direccion, b.Uni_Nom AS departamento,
                    e.descripcion AS estado,
                    CASE WHEN d.ped_tipo_seguimiento_id IN (8147,8144,8141) THEN 'danger' ELSE 'info' END AS color,
                    '' AS asignado, '' AS verificacion,
                    CASE
                    WHEN d.ped_tipo_seguimiento_id IN (8156) THEN 15
                    WHEN d.ped_tipo_seguimiento_id = 8139 THEN 20
                    WHEN d.ped_tipo_seguimiento_id = 8141 THEN 5
                    WHEN d.ped_tipo_seguimiento_id = 8141 THEN 100
                    WHEN d.ped_tipo_seguimiento_id = 8140 THEN 25
                    WHEN d.ped_tipo_seguimiento_id = 8140 THEN 30

                    WHEN d.ped_tipo_seguimiento_id = 8160 THEN 35
                    WHEN d.ped_tipo_seguimiento_id = 8160 THEN 40
                    WHEN d.ped_tipo_seguimiento_id = 8143  THEN 45

                    WHEN d.ped_tipo_seguimiento_id = 8143 THEN 50

                    WHEN d.ped_tipo_seguimiento_id = 8164 THEN 55

                    WHEN d.ped_tipo_seguimiento_id = 8164 THEN 60

                    WHEN d.ped_tipo_seguimiento_id = 8164 THEN 70
                    WHEN d.ped_tipo_seguimiento_id = 8146 THEN 75
                    WHEN d.ped_tipo_seguimiento_id = 8146 THEN 80

                    WHEN d.ped_tipo_seguimiento_id IN (8147) THEN 100
                    WHEN d.ped_tipo_seguimiento_id IN (8144) THEN 100
                    ELSE 0 END AS porcentaje

					FROM APP_POS.dbo.PEDIDO_E a
             INNER JOIN APP_POS.dbo.UNIDAD b ON a.Uni_cor=b.Uni_cor
             INNER JOIN APP_POS.dbo.DIRECCION c ON b.Dir_cor=c.Dir_cor
			 INNER JOIN docto_pedido_seguimiento_bitacora d ON a.Ped_tra = d.ped_tra
			 INNER JOIN tbl_catalogo_detalle e ON d.ped_tipo_seguimiento_id = e.id_item



             ";
    // PLANIFICACION
    if ($tipo == 3) {
      $sql0 .= "WHERE d.ped_tipo_seguimiento_id IN (8140, 8141)";
    } else if ($tipo == 4) {
      $sql0 .= " AND a.Ped_status IN (8140) ";
    } else if ($tipo == 5) {
      $sql0 .= " AND a.Ped_status IN (8141) ";
    }

    //SUB ADMINISTRATIVA
    if ($tipo == 6) {
      $sql0 .= " AND a.Ped_status IN (8143,8144,8160) ";
    } else if ($tipo == 7) {
      $sql0 .= " AND a.Ped_status IN (8143) ";
    } else if ($tipo == 8) {
      $sql0 .= " AND a.Ped_status IN (8144) ";
    }

    //COMPRAS
    if ($tipo == 9) {
      $sql0 .= " AND a.Ped_status IN (8145,8146,8147,8164) ";
    } else if ($tipo == 10) {
      $sql0 .= " AND a.Ped_status IN (8146) ";
    } else if ($tipo == 11) {
      $sql0 .= " AND a.Ped_status IN (8147) ";
    }
    $sql0 .= " AND a.ped_fec >= ? ";

    $sql0 .= "ORDER BY a.ped_tra DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($fecha));
    $pedidos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $pedidos;
  }


  static function get_pedidos_remesas_by_direccion($direccion, $tipo, $fecha)
  {
    //$dir_pos = self::devuelve_direccion_app_pos($direccion);


    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra,a.ped_num,a.ped_fec, a.ped_obs, c.Dir_com AS direccion, b.Uni_Nom AS departamento,
                    d.descripcion AS estado, e.descripcion AS verificacion,
                    CASE WHEN a.Ped_status IN (8147,8144,8141,8170) THEN 'danger' ELSE 'info' END AS color,
                    CASE
                    WHEN a.Ped_status IN (8156) THEN 15
                    WHEN a.Ped_status = 8139 AND a.Ped_bitacora_id = 8156 THEN 20
                    WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 0 THEN 5
                    WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 8157 THEN 100
                    WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 0 THEN 25
                    WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 8157 THEN 30

                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 0 THEN 35
                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8139 THEN 37
                    WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8142 THEN 40
                    WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id  = 0 THEN 45

                    WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id = 8161 THEN 50

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 0 THEN 55

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8145 THEN 60

                    WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8148 THEN 70
                    WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 0 THEN 75
                    WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 8149 THEN 80

                    WHEN a.Ped_status IN (8147) THEN 100
                    WHEN a.Ped_status IN (8144) THEN 100
                    WHEN a.Ped_status IN (8170) THEN 100
                    ELSE 0 END AS porcentaje, f.id_persona_asignada,
                    ISNULL(g.primer_nombre,'')+' '+ISNULL(g.segundo_nombre, '')+' '+ISNULL(g.tercer_nombre,'')+' '+ISNULL(g.primer_apellido,'')+' '+ISNULL(g.segundo_apellido,'')+' '+ISNULL(g.tercer_apellido,'') AS asignado,
                    a.Ped_num_interno, a.Pac_id, a.Ped_status
             FROM APP_POS.dbo.PEDIDO_E a
             INNER JOIN APP_POS.dbo.UNIDAD b ON a.Uni_cor=b.Uni_cor
             INNER JOIN APP_POS.dbo.DIRECCION c ON b.Dir_cor=c.Dir_cor
             LEFT JOIN tbl_catalogo_detalle d ON a.Ped_status = d.id_item
             LEFT JOIN tbl_catalogo_detalle e ON a.Ped_bitacora_id = e.id_item
             LEFT JOIN docto_ped_seguimiento_asignado f ON f.Ped_tra = a.Ped_tra
             LEFT JOIN rrhh_persona g ON f.id_persona_asignada = g.id_persona
             ";
    if ($tipo == 1) {
      $sql0 .= "WHERE b.id_departamento = ?  AND a.Ped_justificacion=0 AND YEAR(a.ped_fec)= '$fecha'";
    } else {
      if ($direccion == 4) {
        $sql0 .= "WHERE b.Dir_cor IN  (2,4,42)   AND YEAR(a.ped_fec) = '$fecha'";
      } else
      if ($direccion == 207) {
        $sql0 .= "WHERE c.id_direccion = ?  AND YEAR(a.ped_fec) = '$fecha'";
      } else {
        $sql0 .= "WHERE b.Dir_cor = ?  AND YEAR(a.ped_fec) = '$fecha'";
      }
    }

    $sql0 .= " ORDER BY a.ped_tra DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($direccion));
    $pedidos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $pedidos;
  }

  static function get_pedido_by_id($id_pedido)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra,a.ped_num,a.ped_fec, a.ped_obs, b.Dir_cor, ISNULL(a.Ped_justificacion, 0) AS Ped_justificacion,
                    a.persona_id
             FROM APP_POS.dbo.PEDIDO_E a
             INNER JOIN APP_POS.dbo.UNIDAD b ON a.Uni_cor=b.Uni_cor
             WHERE a.ped_tra=?
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_pedido));
    $pedido = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $pedido;
  }

  static function get_unidad_by_pedido($ped_tra)
  {
    //$dir_pos = self::devuelve_direccion_app_pos($direccion);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra, c.Dir_com AS direccion, b.Uni_Nom AS departamento

             FROM APP_POS.dbo.PEDIDO_E a
             INNER JOIN APP_POS.dbo.UNIDAD b ON a.Uni_cor=b.Uni_cor
             INNER JOIN APP_POS.dbo.DIRECCION c ON b.Dir_cor=c.Dir_cor
             WHERE a.Ped_tra=?";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $pedido = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $pedido;
  }

  static function get_insumos_by_pedido($id_pedido)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ppr_id, a.Pedd_can, b.Ppr_cod, b.Ppr_codPre, b.Ppr_Nom, b.Ppr_Des, b.Ppr_Pres, b.Ppr_Ren,
              UPPER(LEFT((b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres),1))+LOWER(SUBSTRING((b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres),2,
              LEN((b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres)))) AS descripcion,
              LOWER(b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres) AS ds, c.Med_nom, ISNULL(a.Pedd_canf,0) AS Pedd_canf
             FROM APP_POS.dbo.PEDIDO_D a
             INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id=b.Ppr_id
             INNER JOIN APP_POS.dbo.MEDIDA c ON b.Med_id = c.Med_id
             WHERE a.ped_tra=?
             ORDER BY Pedd_ord ASC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_pedido));
    $insumos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_insumos_by_ped_num($ped_num)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ped_tra, a.Ppr_id, a.Pedd_can, b.Ppr_cod, b.Ppr_codPre, b.Ppr_Nom, b.Ppr_Des, b.Ppr_Pres, b.Ppr_Ren,
              LOWER(b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres) AS descripcion, c.Med_nom, ISNULL(a.Pedd_canf,0) AS Pedd_canf
             FROM APP_POS.dbo.PEDIDO_D a
             INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id=b.Ppr_id
             INNER JOIN APP_POS.dbo.MEDIDA c ON b.Med_id = c.Med_id
             INNER JOIN APP_POS.dbo.PEDIDO_E d ON a.Ped_tra=d.Ped_tra
             WHERE d.Ped_num=?
             ORDER BY Pedd_ord ASC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_num));
    $insumos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_aprobacion_plani_by_pedido($ped_tra){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT ped_tipo_seguimiento_id FROM docto_pedido_seguimiento_bitacora
    WHERE ped_tra = ? AND ped_tipo_seguimiento_id = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra,8140));
    $aprobacion = $q0->fetch();
    Database::disconnect_sqlsrv();
    if($aprobacion['ped_tipo_seguimiento_id'] == 8140){
      return 'true';
    }else{
      return 'false';
    }

  }

  static function genera_correlativo_pedido_interno($unidad)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 (a.Ped_num_interno) AS num_interno FROM APP_POS.dbo.PEDIDO_E a
             WHERE A.Uni_cor=?
             ORDER BY a.Ped_num_interno DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($unidad));
    $reng = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $reng;
  }

  static function get_pedido_by_pedido_interno($pedido_interno, $year)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ped_num, a.Ped_tra, a.Ped_num_interno
             FROM APP_POS.dbo.PEDIDO_E a
             WHERE a.Ped_num_interno = ? AND YEAR(a.Ped_fec) = ? AND a.Uni_cor = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($pedido_interno, $year, 63));
    $reng = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $reng;
  }

  static function get_dictamenes_by_docto($docto_id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT docto_id, reng_num, docto_dictamen, docto_fecha, status
             FROM docto_dictamen
             WHERE docto_id=?
             ORDER BY reng_num ASC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($docto_id));
    $dictamenes = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $dictamenes;
  }

  static function devuelve_direccion_app_pos($direccion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT  Dir_cor FROM APP_POS.dbo.DIRECCION
             WHERE id_direccion = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($direccion));
    $dir = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $dir['Dir_cor'];
  }

  static function devuelve_direccion_from_app_pos($dircor)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT id_direccion FROM APP_POS.dbo.DIRECCION
             WHERE Dir_cor = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($dircor));
    $dir = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $dir['id_direccion'];
  }

  static function devuelve_direccion_app_posBK($direccion)
  {
    $dir_pos = 0;
    if ($direccion == 1) {
      $dir_pos = 25; // APP_POS
    } else if ($direccion == 2) {
      $dir_pos = 5;
    } else if ($direccion == 3) {
      $dir_pos = 11;
    } else if ($direccion == 4) {
      $dir_pos = 6;
    } else if ($direccion == 5) {
      $dir_pos = 2;
    } else if ($direccion == 6) {
      $dir_pos = 4;
    } else if ($direccion == 7) {
      $dir_pos = 10;
    } else if ($direccion == 8) {
      $dir_pos = 11;
    } else if ($direccion == 9) {
      $dir_pos = 12;
    } else if ($direccion == 10) {
      $dir_pos = 14;
    } else if ($direccion == 11) {
      $dir_pos = 8;
    } else if ($direccion == 12) {
      $dir_pos = 9;
    } else if ($direccion == 13) {
      $dir_pos = 2;
    } else if ($direccion == 14) {
      $dir_pos = 10;
    } else if ($direccion == 15) {
      $dir_pos = 20;
    } else if ($direccion == 207) {
      $dir_pos = 24;
    } else if ($direccion == 24) {
      $dir_pos = 207;
    } else if ($direccion == 30) {
      $dir_pos = 669;
    }
    return $dir_pos;
  }

  static function devuelve_direccion_funcional_desde_app_pos($direccion)
  {
    $dir_pos = 0;
    if ($direccion == 25) {
      $dir_pos = 1;
    } else if ($direccion == 13) {
      $dir_pos = 2;
    } else if ($direccion == 2) {
      $dir_pos = 5;
    } else if ($direccion == 4) {
      $dir_pos = 6;
    } else if ($direccion == 10) {
      $dir_pos = 7;
    } else if ($direccion == 11) {
      $dir_pos = 3;
    } else if ($direccion == 12) {
      $dir_pos = 9;
    } else if ($direccion == 14) {
      $dir_pos = 10;
    } else if ($direccion == 8) {
      $dir_pos = 11;
    } else if ($direccion == 9) {
      $dir_pos = 12;
    } else if ($direccion == 23) {
      $dir_pos = 14;
    } else if ($direccion == 20) {
      $dir_pos = 15;
    } else if ($direccion == 24 || $direccion == 6) {
      $dir_pos = 207;
    }
    return $dir_pos;
  }

  /* fin justificaciones */

  function createAcronym($string, $onlyCapitals = false)
  {
    $output = null;
    $token  = strtok($string, ' ');
    while ($token !== false) {
      $character = mb_substr($token, 0, 1);
      if ($onlyCapitals and mb_strtoupper($character) !== $character) {
        $token = strtok(' ');
        continue;
      }
      $output .= $character;
      $token = strtok(' ');
    }
    return $output;
  }

  //inicio pedidos

  static function get_insumos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est, b.Med_nom
           FROM APP_POS.dbo.PPR a
           INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id = b.Med_id
           WHERE a.Ppr_est=?
           ORDER BY a.Ppr_id ASC
          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(1));
    $insumos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_insumos_filtro($filtro)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est
           FROM APP_POS.dbo.PPR a
           WHERE a.Ppr_est=? AND a.Ppr_Des LIKE ?
           ORDER BY a.Ppr_id ASC
          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(1, $filtro));
    $insumos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_insumos_by_id($id_insumo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est,b.Med_nom
           FROM APP_POS.dbo.PPR a
           INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id = b.Med_id
           WHERE a.Ppr_id=?

          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_insumo));
    $insumo = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $insumo;
  }

  static function get_seguimiento_list($verificacion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.seguimiento_id, a.ped_seguimiento_id, b.descripcion, a.ped_seguimiento_nom, a.ped_seguimiento_status
           FROM docto_ped_seguimiento_listado a
           INNER JOIN tbl_catalogo_detalle b ON a.seguimiento_id=b.id_item
           WHERE a.ped_seguimiento_status=? ";
    if ($verificacion == 2) {
      $sql0 .= " AND ped_seguimiento_id IN (2,3,4,5)";
    } else
  if ($verificacion == 6) {
      $sql0 .= " AND ped_seguimiento_id IN (6)";
    }
    $sql0 .= "ORDER BY a.ped_seguimiento_id ASC
          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(1));
    $listado = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $listado;
  }

  static function get_check_seguimiento($ped_tra, $tipo_verificacion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT COUNT(ped_seguimiento_id) AS conteo FROM docto_ped_seguimiento_detalle WHERE ped_tra=? AND ped_seguimiento_id = ?";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra, $tipo_verificacion));
    $verificacion = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $verificacion;
  }

  static function get_estado_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ped_status, a.Ped_bitacora_id, b.descripcion AS estado, c.descripcion AS tipo_verificacion,
           a.persona_id
           FROM APP_POS.dbo.PEDIDO_E a
           INNER JOIN tbl_catalogo_detalle b ON a.Ped_status=b.id_item
           LEFT JOIN tbl_catalogo_detalle c ON a.Ped_bitacora_id=c.id_item
           WHERE a.Ped_tra = ?
          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $estado = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $estado;
  }

  function get_porcentaje_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra, a.ped_num, a.Ped_status, a.Ped_bitacora_id, b.descripcion AS estado, c.descripcion AS tipo_verificacion,
           a.persona_id,
           CASE WHEN a.Ped_status IN (8147,8144,8141,8170) THEN 'danger' ELSE 'info' END AS color,
           CASE
           WHEN a.Ped_status IN (8156) THEN 15
           WHEN a.Ped_status = 8139 AND a.Ped_bitacora_id = 8156 THEN 20
           WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 0 THEN 5
           WHEN a.Ped_status = 8141 AND a.Ped_bitacora_id = 8157 THEN 100
           WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 0 THEN 25
           WHEN a.Ped_status = 8140 AND a.Ped_bitacora_id = 8157 THEN 30

           WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 0 THEN 35
           WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8139 THEN 37

           WHEN a.Ped_status = 8160 AND a.Ped_bitacora_id = 8142 THEN 40
           WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id  = 0 THEN 45

           WHEN a.Ped_status = 8143 AND a.Ped_bitacora_id = 8161 THEN 50

           WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 0 THEN 55

           WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8145 THEN 60

           WHEN a.Ped_status = 8164 AND a.Ped_bitacora_id = 8148 THEN 70
           WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 0 THEN 75
           WHEN a.Ped_status = 8146 AND a.Ped_bitacora_id = 8149 THEN 80

           WHEN a.Ped_status IN (8147) THEN 100
           WHEN a.Ped_status IN (8144) THEN 100
           WHEN a.Ped_status IN (8170) THEN 100
           ELSE 0 END AS porcentaje
           FROM APP_POS.dbo.PEDIDO_E a
           INNER JOIN tbl_catalogo_detalle b ON a.Ped_status=b.id_item
           LEFT JOIN tbl_catalogo_detalle c ON a.Ped_bitacora_id=c.id_item
           WHERE a.Ped_tra = ?
          ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $porcentaje = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $porcentaje;
  }

  static function get_documentos_respaldo($ped_tra){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.ped_tra, a.reng_num, a.archivo, a.id_status, a.subido_por, a.subido_en, a.descripcion, a.observaciones,
            b.primer_nombre, b.segundo_nombre, b.tercer_nombre,b.primer_apellido, b.segundo_apellido, b.tercer_apellido,
            c.primer_nombre AS r_primer_nombre, c.segundo_nombre AS r_segundo_nombre, c.tercer_nombre AS r_tercer_nombre,c.primer_apellido AS r_primer_apellido, c.segundo_apellido AS r_segundo_apellido, c.tercer_apellido AS r_tercer_apellido,
            a.revisado_en
            FROM docto_ped_doctos_respaldo AS a
            INNER JOIN rrhh_persona AS b ON a.subido_por = b.id_persona
            LEFT JOIN rrhh_persona AS c ON a.revisado_por = c.id_persona
            WHERE a.ped_tra = ?
            ORDER BY a.reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $ped_tra
    ));
    $doctos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $doctos;
  }


  //fin pedidos

  static function validar_lineas($lineas)
  {
    $lineas_r = 0;
    if ($lineas == 1) {
      $lineas_r = 4 + 10;
    } else if ($lineas == 2) {
      $lineas_r = 4 + 10;
    } else if ($lineas == 3) {
      $lineas_r += 8;
    } else if ($lineas == 4) {
      $lineas_r = 12;
    } else if ($lineas == 5) {
      $lineas_r = 16;
    } else if ($lineas == 6) {
      $lineas_r = 20;
    } else if ($lineas == 7) {
      $lineas_r = 24;
    } else if ($lineas == 8) {
      $lineas_r = 28;
    } else if ($lineas == 9) {
      $lineas_r = 32;
    } else if ($lineas == 10) {
      $lineas_r = 36;
    } else if ($lineas == 11) {
      $lineas_r = 40;
    } else if ($lineas == 12) {
      $lineas_r = 44;
    } else if ($lineas == 13) {
      $lineas_r = 48;
    } else if ($lineas == 14) {
      $lineas_r = 52;
    } else if ($lineas == 15) {
      $lineas_r = 56;
    } else if ($lineas == 16) {
      $lineas_r = 60;
    } else if ($lineas == 17) {
      $lineas_r = 64;
    }

    return $lineas_r;
  }

  static function genera_correlativo_bitacora_pedido($ped_tra, $tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 (ped_reng_num) AS reng_num,ped_tipo_seguimiento_id FROM docto_pedido_seguimiento_bitacora
           WHERE ped_tra=? AND ped_tipo_seguimiento_id=?
           ORDER BY ped_seguimiento_fecha DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra, $tipo));
    $reng = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $reng;
  }

  static function get_nombre_verificacion_by_id($id_verificacion)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT ped_seguimiento_id, ped_seguimiento_nom FROM docto_ped_seguimiento_listado
           WHERE ped_seguimiento_id=?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_verificacion));
    $verificacion = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $verificacion;
  }

  static function get_bitacora_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT c.id_item, a.ped_tra, c.descripcion, b.primer_nombre AS pn,b.segundo_nombre AS sn,b.tercer_nombre AS tn,b.primer_apellido AS pa,b.segundo_apellido AS sa,b.tercer_apellido AS ta, a.ped_seguimiento_fecha,
        		      d.primer_nombre AS pne,d.segundo_nombre AS sne, d.tercer_nombre AS tne, d.primer_apellido AS pae,d.segundo_apellido AS sae,d.tercer_apellido tae, a.ped_observaciones
          FROM docto_pedido_seguimiento_bitacora a
          INNER JOIN rrhh_persona b ON a.persona_id=b.id_persona
          INNER JOIN tbl_catalogo_detalle c ON a.ped_tipo_seguimiento_id=c.id_item
          LEFT JOIN rrhh_persona d ON a.persona_propietario_id=d.id_persona
          WHERE a.ped_tra =?
          ORDER BY a.ped_seguimiento_fecha ASC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $bitacora = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $bitacora;
  }

  static function get_bitacora_by_id($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 c.id_item, a.ped_tra, c.descripcion, b.primer_nombre AS pn,b.segundo_nombre AS sn,b.tercer_nombre AS tn,b.primer_apellido AS pa,b.segundo_apellido AS sa,b.tercer_apellido AS ta, a.ped_seguimiento_fecha,
        		      d.primer_nombre AS pne,d.segundo_nombre AS sne, d.tercer_nombre AS tne, d.primer_apellido AS pae,d.segundo_apellido AS sae,d.tercer_apellido tae, a.ped_observaciones
          FROM docto_pedido_seguimiento_bitacora a
          INNER JOIN rrhh_persona b ON a.persona_id=b.id_persona
          INNER JOIN tbl_catalogo_detalle c ON a.ped_tipo_seguimiento_id=c.id_item
          LEFT JOIN rrhh_persona d ON a.persona_propietario_id=d.id_persona
          WHERE a.ped_tra =?
          ORDER BY a.ped_seguimiento_fecha DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $bitacora = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $bitacora;
  }

  static function get_accesos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,b.id_acceso,b.id_status,
            c.id_pantalla
            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            INNER JOIN tbl_accesos_usuarios_det c ON c.id_acceso=b.id_acceso

            WHERE b.id_sistema = ? AND c.id_pantalla=? AND c.flag_es_menu=? AND b.id_status = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(8017, 302, 1, 1119));
    $accesos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $accesos;
  }

  static function get_persona_asignada($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 b.id_persona_asignada AS id_persona, a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            ISNULL(a.primer_nombre,'')+ ' '+ISNULL(a.segundo_nombre,'')+ ' '+ISNULL(a.tercer_nombre,'')+ ' '+ISNULL(a.primer_apellido,'')+ ' '+ISNULL(a.segundo_apellido,'')+ ' '+ISNULL(a.tercer_apellido,'') AS persona,
            b.status_id
            FROM docto_ped_seguimiento_asignado b
            INNER JOIN rrhh_persona a ON b.id_persona_asignada=a.id_persona

            WHERE b.ped_tra = ?
            ORDER BY b.reng_num DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $asig = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $asig;
  }

  static function genera_correlativo_persona_asignada($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 (reng_num) AS reng_num FROM docto_ped_seguimiento_asignado
           WHERE ped_tra=?
           ORDER BY reng_num DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $reng = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $reng;
  }
  static function get_unidad_pos($id_unidad)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT Uni_cor, Dir_cor FROM APP_POS.dbo.UNIDAD
           WHERE id_departamento = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_unidad));
    $uni = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $uni;
  }


  static function enviar_correo_estado($destinatarios, $subject, $body)
  {
    $pdo = Database::connect_sqlsrv();
    $yes = '';

    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql10 = "
    DECLARE @body_content varchar(MAX);
    SET @body_content = $body;
              EXEC msdb.dbo.sp_send_dbmail
              @profile_name = 'APP_Alertas',
              @recipients = $destinatarios,
              @body = @body_content,
              @body_format = 'HTML',
              @subject = $subject; ";

      $q10 = $pdo->prepare($sql10);
      $q10->execute(array());
      $pdo->commit();
      //echo 'OK';
      //echo json_encode($yes);
      $yes = array('msg' => 'OK', 'id' => '');
      echo json_encode($yes);
    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);
        echo json_encode($yes);
      }
    }
  }
  static function get_reporte_movimiento($fecha)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ped_tra, d.Ped_num, d.Ped_fec, f.ped_seguimiento_fecha as recibido, g.ped_seguimiento_fecha as devuelto,
                a.Ppr_id, a.Pedd_can, b.Ppr_cod, b.Ppr_codPre, b.Ppr_Nom, b.Ppr_Des, b.Ppr_Pres, b.Ppr_Ren,
              LOWER(b.Ppr_Nom+ ' - '+ b.Ppr_Des+ ' - '+ b.Ppr_Pres) AS descripcion, c.Med_nom, e.descripcion as estado, e.ped_observaciones,
              e.ped_tipo_seguimiento_id
             FROM APP_POS.dbo.PEDIDO_D a
             INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id=b.Ppr_id
             INNER JOIN APP_POS.dbo.MEDIDA c ON b.Med_id = c.Med_id
             INNER JOIN APP_POS.dbo.PEDIDO_E d ON a.Ped_tra=d.Ped_tra

             INNER JOIN (SELECT a.ped_tra, b.descripcion, a.ped_tipo_seguimiento_id, a.ped_observaciones
							FROM  docto_pedido_seguimiento_bitacora a
							INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id=b.id_item
								WHERE a.ped_tipo_seguimiento_id  IN (8140,8141) GROUP BY a.ped_tra,b.descripcion,a.ped_tipo_seguimiento_id,a.ped_observaciones) AS e ON d.Ped_tra = e.ped_tra

              INNER JOIN (SELECT a.ped_tra, b.descripcion, a.ped_tipo_seguimiento_id, a.ped_seguimiento_fecha
							FROM  docto_pedido_seguimiento_bitacora a
							INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id=b.id_item
								WHERE a.ped_tipo_seguimiento_id  IN (8156) AND a.ped_seguimiento_id IN (0,8141) GROUP BY a.ped_tra,b.descripcion,a.ped_tipo_seguimiento_id, a.ped_seguimiento_fecha) AS f ON d.Ped_tra = f.ped_tra
								INNER JOIN (SELECT a.ped_tra, b.descripcion, a.ped_tipo_seguimiento_id, a.ped_seguimiento_fecha
							FROM  docto_pedido_seguimiento_bitacora a
							INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id=b.id_item
								WHERE a.ped_tipo_seguimiento_id  IN (8157) AND a.ped_seguimiento_id IN (8140,8141) GROUP BY a.ped_tra,b.descripcion,a.ped_tipo_seguimiento_id, a.ped_seguimiento_fecha) AS g ON d.Ped_tra = g.ped_tra
                WHERE CONVERT(varchar, g.ped_seguimiento_fecha, 23) = ?
             GROUP BY a.Ped_tra,d.Ped_num, d.Ped_fec, a.Ped_tra, a.Ppr_id,a.Pedd_can, b.Ppr_cod, b.Ppr_codPre, b.Ppr_Nom, b.Ppr_Des, b.Ppr_Pres, b.Ppr_Ren,c.Med_nom,e.descripcion,e.ped_tipo_seguimiento_id,
             f.ped_seguimiento_fecha,g.ped_seguimiento_fecha,e.ped_observaciones
             ORDER BY a.Ped_tra DESC
";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($fecha));
    $reng = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $reng;
  }

  static function get_reporte_por_fase($fechai, $fechaf, $tipo)
  {

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = '';
    if ($tipo == 1) {
      $sql0 = "SELECT d.Ped_tra, d.Ped_num, d.Ped_fec, f.ped_seguimiento_fecha as recibido, g.ped_seguimiento_fecha as devuelto,e.descripcion AS estado,
                    e.ped_tipo_seguimiento_id,
                    e.ped_tipo_seguimiento_id,
                    i.Dir_com AS direccion,
                    e.ped_observaciones,
                    d.Ped_obs
            FROM APP_POS.dbo.PEDIDO_E d
            INNER JOIN (
              SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha, T.descripcion, T.ped_observaciones
              FROM
              (
                SELECT a.*, b.descripcion, ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk
                FROM docto_pedido_seguimiento_bitacora a
                INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id = b.id_item
                WHERE a.ped_tipo_seguimiento_id IN (8140,8141)
              ) T
              WHERE T.rnk = 1
            ) AS e ON d.Ped_tra = e.ped_tra
            INNER JOIN (
              SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
              FROM
              (
                SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
                WHERE a.ped_tipo_seguimiento_id IN (8156) AND a.ped_seguimiento_id IN (0,8141)
              ) T
              WHERE T.rnk = 1
            ) AS f ON d.Ped_tra = f.ped_tra
            LEFT JOIN (SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
              FROM
              (
                SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
                WHERE a.ped_tipo_seguimiento_id IN (8157) AND a.ped_seguimiento_id IN (8140,8141)
              ) T
              WHERE T.rnk = 1
            ) AS g ON d.Ped_tra = g.ped_tra
            INNER JOIN APP_POS.dbo.UNIDAD h ON d.Uni_cor=h.Uni_cor
            INNER JOIN APP_POS.dbo.DIRECCION i ON h.Dir_cor = i.Dir_cor
            WHERE CONVERT(varchar, f.ped_seguimiento_fecha, 23) BETWEEN ? AND ?
            GROUP BY d.Ped_tra,d.Ped_num, d.Ped_fec,e.ped_tipo_seguimiento_id,e.descripcion,
            f.ped_seguimiento_fecha,g.ped_seguimiento_fecha,e.ped_observaciones,
            i.Dir_com,d.Ped_obs
            ORDER BY f.ped_seguimiento_fecha DESC
  ";
    } else if ($tipo == 2) {
      $sql0 = "SELECT d.Ped_tra, d.Ped_num, d.Ped_fec, f.ped_seguimiento_fecha as recibido, g.ped_seguimiento_fecha as devuelto,e.descripcion AS estado,
                  e.ped_tipo_seguimiento_id,
                  e.ped_tipo_seguimiento_id,
                  i.Dir_com AS direccion,
                  e.ped_observaciones,
                  d.Ped_obs
          FROM APP_POS.dbo.PEDIDO_E d
          INNER JOIN (
            SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha, T.descripcion, T.ped_observaciones
            FROM
            (
              SELECT a.*, b.descripcion, ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk
              FROM docto_pedido_seguimiento_bitacora a
              INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id = b.id_item
              WHERE a.ped_tipo_seguimiento_id IN (8143,8144)
            ) T
            WHERE T.rnk = 1
          ) AS e ON d.Ped_tra = e.ped_tra
          INNER JOIN (
            SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
            FROM
            (
              SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
              WHERE a.ped_tipo_seguimiento_id IN (8160) AND a.ped_seguimiento_id IN (8140)
            ) T
            WHERE T.rnk = 1
          ) AS f ON d.Ped_tra = f.ped_tra
          LEFT JOIN (SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
            FROM
            (
              SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
              WHERE a.ped_tipo_seguimiento_id IN (8161) AND a.ped_seguimiento_id IN (8143,8144)
            ) T
            WHERE T.rnk = 1
          ) AS g ON d.Ped_tra = g.ped_tra
          INNER JOIN APP_POS.dbo.UNIDAD h ON d.Uni_cor=h.Uni_cor
          INNER JOIN APP_POS.dbo.DIRECCION i ON h.Dir_cor = i.Dir_cor
          WHERE CONVERT(varchar, f.ped_seguimiento_fecha, 23) BETWEEN ? AND ?
          GROUP BY d.Ped_tra,d.Ped_num, d.Ped_fec,e.ped_tipo_seguimiento_id,e.descripcion,
          f.ped_seguimiento_fecha,g.ped_seguimiento_fecha,e.ped_observaciones,
          i.Dir_com,d.Ped_obs
          ORDER BY f.ped_seguimiento_fecha DESC


";
    } else if ($tipo == 3) {
      $sql0 = "SELECT d.Ped_tra, d.Ped_num, d.Ped_fec, f.ped_seguimiento_fecha as recibido, g.ped_seguimiento_fecha as devuelto,e.descripcion AS estado,
                  e.ped_tipo_seguimiento_id,
                  e.ped_tipo_seguimiento_id,
                  i.Dir_com AS direccion,
                  e.ped_observaciones,
                  d.Ped_obs
          FROM APP_POS.dbo.PEDIDO_E d
          INNER JOIN (
            SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha, T.descripcion, T.ped_observaciones
            FROM
            (
              SELECT a.*, b.descripcion, ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk
              FROM docto_pedido_seguimiento_bitacora a
              INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id = b.id_item
              WHERE a.ped_tipo_seguimiento_id IN (8146,8147,8148,8164)
            ) T
            WHERE T.rnk = 1
          ) AS e ON d.Ped_tra = e.ped_tra
          INNER JOIN (
            SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
            FROM
            (
              SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
              WHERE a.ped_tipo_seguimiento_id IN (8164)
            ) T
            WHERE T.rnk = 1
          ) AS f ON d.Ped_tra = f.ped_tra
          LEFT JOIN (SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
            FROM
            (
              SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
              WHERE a.ped_tipo_seguimiento_id IN (8146)
            ) T
            WHERE T.rnk = 1
          ) AS g ON d.Ped_tra = g.ped_tra
          INNER JOIN APP_POS.dbo.UNIDAD h ON d.Uni_cor=h.Uni_cor
          INNER JOIN APP_POS.dbo.DIRECCION i ON h.Dir_cor = i.Dir_cor
          --WHERE CONVERT(varchar, f.ped_seguimiento_fecha, 23) BETWEEN ? AND ?
          WHERE YEAR(f.ped_seguimiento_fecha) = '2022'
          GROUP BY d.Ped_tra,d.Ped_num, d.Ped_fec,e.ped_tipo_seguimiento_id,e.descripcion,
          f.ped_seguimiento_fecha,g.ped_seguimiento_fecha,e.ped_observaciones,
          i.Dir_com,d.Ped_obs
          ORDER BY f.ped_seguimiento_fecha DESC
";
    }



    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($fechai, $fechaf));
    $reng = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $reng;
  }
  static function get_renglones_por_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT  a.Ped_tra,b.Ppr_Ren FROM APP_POS.dbo.PEDIDO_D a
              INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id=b.Ppr_id
              WHERE a.Ped_tra = ?
              GROUP BY b.Ppr_Ren, a.Ped_tra
              ORDER BY a.Ped_tra DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $uni = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $uni;
  }
  static function get_cantidades_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT  a.Ped_tra,a.Pedd_can, a.Ppr_id, b.Ppr_Nom
            FROM APP_POS.dbo.PEDIDO_D a
            INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id = b.Ppr_id
            WHERE a.Ped_tra = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $uni = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $uni;
  }

  static function get_persona_asignada_($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT ISNULL(b.primer_nombre,'')+ ' '+ISNULL(b.segundo_nombre,'')+ ' '+ISNULL(b.tercer_nombre,'')+ ' '+ISNULL(b.primer_apellido,'')+ ' '+ISNULL(b.segundo_apellido,'')+ ' '+ISNULL(b.tercer_apellido,'') AS persona
            FROM docto_ped_seguimiento_asignado a
            INNER JOIN rrhh_persona b ON a.id_persona_asignada=b.id_persona
            WHERE a.Ped_tra=? AND a.status_id = ?
    ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra, 1));
    $per = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $per;
  }
  static function get_facturas($tipo)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.orden_compra_id, a.nro_orden, a.year, a.tipo_pago, a.status, a.asignado_en, a.asignado_por,b.ped_tra, a.reng_num,
                    a.id_status, a.factura_fecha, a.factura_serie, a.factura_num, a.factura_total, a.proveedor_id, c.Prov_nom,
                    a.password, a.ped_nog AS nog, a.cheque_nro AS cheque, a.cur,b.Ped_num,a.cur_devengado,
                    ISNULL(e.primer_nombre, '')+ ' '+ISNULL(e.segundo_nombre, '')+ ' '+ISNULL(e.tercer_nombre, '')+ ' '+ISNULL(e.primer_apellido, '')+ ' '+
                    ISNULL(e.segundo_apellido, '')+ ' '+ISNULL(e.tercer_apellido, '') AS empleado
            FROM docto_ped_pago a
            LEFT JOIN APP_POS.dbo.PEDIDO_E b ON a.ped_tra = b.Ped_tra
            LEFT JOIN APP_POS.dbo.PROVEEDOR c ON a.proveedor_id = c.Prov_id COLLATE Modern_Spanish_CI_AS
            LEFT JOIN docto_ped_pago_asignado d ON d.orden_compra_id = a.orden_compra_id AND d.status = 1
            LEFT JOIN rrhh_persona e ON d.id_persona = e.id_persona ";
            if($tipo == 0){//pendientes
              $sql0 .= "WHERE YEAR(a.asignado_en) = 2022 AND ISNULL(a.modalidad_pago,0) = 0 OR YEAR(a.asignado_en) = 2022 AND ISNULL(a.modalidad_pago,0) = 2 AND ISNULL(a.cur,0) = 0";
            }else
            if($tipo == 1){ //deuda
              $sql0 .= 'WHERE YEAR(a.asignado_en) = 2022 AND ISNULL(a.modalidad_pago,0) = 2 AND a.cur > 0 AND ISNULL(a.cur_devengado,0) = 0';
            }else
            if($tipo == 2){ //liquidadas acreditamiento
              $sql0 .= ' WHERE YEAR(a.asignado_en) = 2022 AND ISNULL(a.modalidad_pago,0) = 2 AND a.cur > 0 AND a.cur_devengado > 0';
            }
            $sql0.="ORDER BY a.orden_compra_id DESC";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $facturas = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $facturas;
  }

  static function get_facturas_by_pedido($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.orden_compra_id, a.nro_orden, a.year, a.tipo_pago, a.status, a.asignado_en, a.asignado_por,b.ped_tra, a.reng_num,
                    a.id_status, a.factura_fecha, a.factura_serie, a.factura_num, a.factura_total, a.proveedor_id, c.Prov_nom,
                    a.password, a.ped_nog AS nog, a.cheque_nro AS cheque, a.cur,b.Ped_num
            FROM docto_ped_pago a
            LEFT JOIN APP_POS.dbo.PEDIDO_E b ON a.ped_tra = b.Ped_tra
            LEFT JOIN APP_POS.dbo.PROVEEDOR c ON a.proveedor_id = c.Prov_id COLLATE Modern_Spanish_CI_AS
            WHERE a.ped_tra = ?
            ORDER BY a.orden_compra_id DESC
    ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $facturas = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $facturas;
  }

  static function get_factura_by_id($orden)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.orden_compra_id, a.nro_orden, a.year, a.tipo_pago, a.status, a.asignado_en, a.asignado_por,b.ped_tra, a.reng_num,
                    a.id_status, a.factura_fecha, a.factura_serie, a.factura_num, a.factura_total, a.proveedor_id, c.Prov_nom,
                    a.password, a.ped_nog AS nog, a.cheque_nro AS cheque, a.cur,b.Ped_num, a.ped_tra, a.modalidad_pago AS forma_de_pago,
                    a.cur_devengado,a.regimen_proveedor,
                    CASE WHEN a.modalidad_pago = 3 THEN 'Caja Chica'
                     WHEN a.modalidad_pago = 1 THEN 'Cheque'
                     WHEN a.modalidad_pago = 2 THEN 'Transferencia'
                    ELSE 'Ninguno'
                    END AS forma_pago_text
                    /*,
					  ISNULL(e.primer_nombre,'')+' '+ISNULL(e.segundo_nombre,'')+ ' '+ISNULL(e.tercer_nombre,'')+' '+
					  ISNULL(e.primer_apellido,'')+' '+ISNULL(e.segundo_apellido,'')+' '+ISNULL(e.tercer_apellido,'') AS tecnico*/
            FROM docto_ped_pago a
            LEFT JOIN APP_POS.dbo.PEDIDO_E b ON a.ped_tra = b.Ped_tra
            LEFT JOIN APP_POS.dbo.PROVEEDOR c ON a.proveedor_id = c.Prov_id COLLATE Modern_Spanish_CI_AS
            /*LEFT JOIN (SELECT  T.orden_compra_id, T.id_persona, T.reng_num
                       FROM
                        (
                          SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY id_persona ORDER BY reng_num DESC) AS rnk
						  FROM docto_ped_pago_asignado a
                          WHERE status = 1
                        ) T
                        WHERE T.rnk = 1
                      ) AS d ON d.orden_compra_id = a.orden_compra_id
			      LEFT JOIN rrhh_persona e ON d.id_persona = e.id_persona*/
            WHERE a.orden_compra_id=?
    ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($orden));
    $facturas = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $facturas;
  }

  static function get_insumos_by_factura($orden)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.orden_compra_id, a.reng_num, a.Ppr_id, a.Ppr_can, a.Ppr_status,
                    b.Ppr_cod, b.Ppr_codPre,
                    b.Ppr_Ren, b.Ppr_Nom, b.Ppr_Des, c.Med_nom, b.Ppr_Pres
             FROM docto_ped_pago_detalle a
             INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id=b.Ppr_id
             INNER JOIN APP_POS.dbo.MEDIDA c ON b.Med_id = c.Med_id
             WHERE a.orden_compra_id=?
             ORDER BY a.reng_num ASC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($orden));
    $insumos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $insumos;
  }

  static function get_bitacora_by_factura($orden_id){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.orden_compra_id,a.id_seguimiento,a.reng_num,a.operado_por,a.operado_en
                ,a.persona_id,a.fac_observaciones,a.fac_status,a.id_seguimiento_detalle,
      				b.descripcion_corta, c.primer_nombre, c.segundo_nombre, c.tercer_nombre,
      				c.primer_apellido, c.segundo_apellido, c.tercer_apellido
                   FROM docto_ped_pago_bitacora AS a
      			 INNER JOIN tbl_catalogo_detalle AS b ON a.id_seguimiento = b.id_item
      			 INNER JOIN rrhh_persona c ON a.operado_por = c.id_persona

             WHERE a.orden_compra_id=?
             ORDER BY a.reng_num DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($orden_id));
    $bitacora = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $bitacora;
  }

  static function genera_correlativo_asignado_factura($orden_id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 (reng_num) AS reng_num,orden_compra_id FROM docto_ped_pago_asignado
             WHERE orden_compra_id=?
             ORDER BY reng_num DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($orden_id));
    $reng = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $reng;
  }

  static function get_tecnico_actual_by_factura($orden_id){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT TOP 1 (a.reng_num) AS reng_num,a.id_persona, a.orden_compra_id,
             ISNULL(b.primer_nombre,'')+' '+ISNULL(b.segundo_nombre,'')+ ' '+ISNULL(b.tercer_nombre,'')+' '+
             ISNULL(b.primer_apellido,'')+' '+ISNULL(b.segundo_apellido,'')+' '+ISNULL(b.tercer_apellido,'') AS tecnico
             FROM docto_ped_pago_asignado a
             INNER JOIN rrhh_persona b ON a.id_persona = b.id_persona
             WHERE a.orden_compra_id=?
             ORDER BY reng_num DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($orden_id));
    $tecnico = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $tecnico;
  }

  static function insert_bitacora_factura($fac_id, $tipo, $persona_id, $observaciones,$estado,$tipo_detalle){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // genera correlativo
    $sql = "SELECT TOP 1 (reng_num) AS reng_num,id_seguimiento FROM docto_ped_pago_bitacora
           WHERE orden_compra_id=? AND id_seguimiento=?
           ORDER BY operado_en DESC";
    $q = $pdo->prepare($sql);
    $q->execute(array($fac_id, $tipo));
    $reng = $q->fetch();

    $reng_num = 1;
    if(!empty($reng['reng_num'])){
      $reng_num += $reng['reng_num'];
    }
    //finaliza correlativo

    $sql0 = "INSERT INTO docto_ped_pago_bitacora (orden_compra_id, id_seguimiento, reng_num, operado_por, operado_en,
             persona_id, fac_observaciones, fac_status, id_seguimiento_detalle)
    VALUES(?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(
      array(
        $fac_id,
        $tipo,
        1,
        $_SESSION['id_persona'],
        date('Y-m-d H:i:s'),
        $persona_id,
        $observaciones,
        1,
        $tipo_detalle

      )
    );
    Database::disconnect_sqlsrv();
  }

  //JUSTIFICACION

  static function get_justificacion_by_ped_tra($ped_tra)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.docto_id,a.docto_titulo,a.docto_nombre,a.docto_fecha, b.descripcion AS dir, REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0') AS docto_correlativo,a.docto_year,
              b.descripcion_corta AS dir_c,
              c.descripcion_corta AS tipo,
              a.docto_categoria,
              CASE WHEN c.id_item IN (8020,8022,8041,8046,8047,8048,8049) THEN 'right' ELSE 'center' END AS alineacion,
              CASE WHEN c.id_item IN (8048,8049) THEN 'true' ELSE 'false' END AS validacion,
              CASE WHEN c.id_item IN (8020,8022,8046,8047,8048,8049) THEN c.descripcion_corta+ ' SAAS No. '+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+ CAST(a.docto_year AS varchar)
              ELSE c.descripcion+'-'+'SAAS-'+b.descripcion_corta+'-'+ISNULL(d.descripcion_corta+'-', '')+REPLACE(STR(a.docto_correlativo, 4), SPACE(1), '0')+'-'+CAST(a.docto_year AS varchar) END as correlativo,
              c.descripcion AS doc_nom, c.descripcion_corta AS doc_nomc,
              a.docto_descripcion, b.descripcion AS direccion, d.descripcion AS departamento, e.pedido_tra, e.pedido_tipo, e.pedido_diagnostico, e.pedido_diagnostico_list,
              a.docto_temporalidad,a.docto_finalidad,a.docto_resultados
              FROM docto_encabezado a
              LEFT JOIN rrhh_direcciones b ON a.docto_direccion_id=b.id_direccion
              LEFT JOIN tbl_catalogo_detalle c ON a.docto_categoria=c.id_item
              LEFT JOIN rrhh_departamentos d ON a.docto_depto_id=d.id_departamento
              LEFT JOIN docto_pedido e ON a.docto_id=e.docto_id
              WHERE e.pedido_tra=?
              ";

    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($ped_tra));
    $documento = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $documento;
  }


  // ********************** CHEQUES  **********************
  static function get_cheques_all()
  {

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT *
    FROM docto_cheque c
    LEFT JOIN docto_cheque_detalle cd ON c.id_cheque=cd.id_cheque";

    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $formularios = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $formularios;
  }


  // formularios  1H

  static function get_formularios_1h()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT A.Ent_id, A.Env_tra, B.Env_num, B.Ser_ser, B.Prov_id, C.Prov_nom,
             	B.Bod_id, B.Env_tot, D.Bod_nom, B.Tdoc_id, E.Tdoc_mov, A.Fh_nro,
             	A.Fh_ser, A.Fh_fec, A.Fh_prg, A.Fh_imp, f.Dir_com direccion, a.usu_id,
              B.Env_fec
             FROM APP_POS.dbo.FH_E A
             	LEFT OUTER JOIN APP_POS.dbo.ENVIO_E B ON B.Env_tra = A.Env_tra
             	LEFT OUTER JOIN APP_POS.dbo.PROVEEDOR C ON C.Prov_id = B.Prov_id
             	LEFT OUTER JOIN APP_POS.dbo.BODEGA D ON D.Bod_id = B.Bod_id
             	LEFT OUTER JOIN APP_POS.dbo.TIPO_DOC E ON E.Tdoc_id = B.Tdoc_id
             	LEFT OUTER JOIN APP_POS.dbo.DIRECCION F ON F.Dir_cor = b.Dir_cor
             WHERE CONVERT(varchar,A.Fh_fec, 23) BETWEEN '2021-01-01'
                 AND '2023-09-10'
                 AND B.Tdoc_id = '001'
                 AND A.Fh_imp = 1
                 ORDER BY A.Fh_nro DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $formularios = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $formularios;
  }

  static function get_formularios_1h_by_id($env_tra, $formulario)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT A.Ent_id, A.Env_tra, B.Env_num, B.Ser_ser, B.Prov_id, C.Prov_nom,
             	B.Bod_id, B.Env_tot, D.Bod_nom, B.Tdoc_id, E.Tdoc_mov, A.Fh_nro,
             	A.Fh_ser, A.Fh_fec, A.Fh_prg, A.Fh_imp, f.Dir_com direccion, a.usu_id,
              B.Env_fec, F.id_direccion
             FROM APP_POS.dbo.FH_E A
             	LEFT OUTER JOIN APP_POS.dbo.ENVIO_E B ON B.Env_tra = A.Env_tra
             	LEFT OUTER JOIN APP_POS.dbo.PROVEEDOR C ON C.Prov_id = B.Prov_id
             	LEFT OUTER JOIN APP_POS.dbo.BODEGA D ON D.Bod_id = B.Bod_id
             	LEFT OUTER JOIN APP_POS.dbo.TIPO_DOC E ON E.Tdoc_id = B.Tdoc_id
             	LEFT OUTER JOIN APP_POS.dbo.DIRECCION F ON F.Dir_cor = b.Dir_cor
             WHERE A.env_tra=? AND A.Fh_nro = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($env_tra, $formulario));
    $formulario = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $formulario;
  }

  static function get_productos_by_formulario($env_tra)
  {

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Env_tra, a.Envd_can, b.Pro_des, b.Renglon_PPR, c.Med_nom
             FROM APP_POS.dbo.ENVIO_D a
             INNER JOIN APP_POS.dbo.PRODUCTO b ON a.Pro_idint=b.Pro_idint
             INNER JOIN APP_POS.dbo.MEDIDA c ON b.Med_id = c.Med_id
             WHERE a.Env_tra = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($env_tra));
    $productos = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $productos;
  }

  //PLAN ANUNAL DE COMPRAS

  static function get_renglones_pac(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT id_renglon, nombre
             FROM APP_POS.dbo.RENGLON
             ";

    //if($e['id_de'])
    $stmt = $pdo->prepare($sql0);
    if ($stmt->execute(array())) {
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

  static function genera_correlativo_pac($direccion, $depto,$ejercicio)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT MAX(pac_correlativo) AS id FROM APP_POS.dbo.PAC_E
             WHERE id_direccion=? AND pac_ejercicio_fiscal=? ";
    if (!empty($depto)){
      $sql0 .= "AND id_departamento=$depto";
    }
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($direccion, $ejercicio));
    $ca = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $ca;
  }

  static function get_pac_by_ejercicio($dir)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT * FROM APP_POS.dbo.vw_pac ";

    if($dir > 0){
      $sql0.= "WHERE id_direccion = $dir";
    }

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $planes = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $planes;
  }

  static function get_pac_by_id($pac_id)
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.pac_id, a.pac_nombre, a.pac_detalle, a.pac_renglon, b.nombre AS renglon_nm,
            a.pac_ejercicio_ant, a.pac_ejercicio_ant_desc, a.pac_creado_por, a.id_status
            FROM APP_POS.dbo.PAC_E a
            INNER JOIN APP_POS.dbo.RENGLON b ON a.pac_renglon = b.id_renglon
            WHERE a.pac_id = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($pac_id));
    $pac = $q0->fetch();
    Database::disconnect_sqlsrv();
    return $pac;
  }

  static function get_meses_by_id($pac_id){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT * FROM APP_POS.dbo.PAC_D
            WHERE pac_id = ?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($pac_id));
    $meses = $q0->fetchAll();
    Database::disconnect_sqlsrv();
    return $meses;

  }

}
