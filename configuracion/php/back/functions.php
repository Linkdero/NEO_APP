<?php
class configuracion {
  function get_modulos($tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_catalogo,b.id_item, b.id_status,
            a.descripcion AS modulo, a.Comentario AS comentario_modulo,
            b.descripcion_corta AS sub_modulo,
            b.descripcion AS comentario_submodulo, b.id_ref_tipo
            FROM tbl_catalogo a
            INNER JOIN tbl_catalogo_detalle b ON b.id_catalogo=a.id_catalogo
            WHERE a.id_catalogo=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($tipo));// 65 es el id de aplicaciones
    $modulos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $modulos;
  }

  function get_pantallas($modulo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_pantalla, a.descripcion AS pantalla_padre, a.descrip_corta AS titulo,
            a.id_activo, a.descripcion, a.id_sistema
            FROM tbl_pantallas a
            LEFT JOIN tbl_catalogo_detalle b ON a.id_sistema=b.id_item
            LEFT JOIN tbl_pantallas c ON c.id_pantalla_padre=a.id_pantalla
            WHERE a.id_sistema=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($modulo));
    $pantallas = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $pantallas;
  }

  function get_empspantallas($pantalla){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            d.descrip_corta,c.flag_es_menu,c.flag_insertar,d.id_pantalla,
            c.flag_eliminar,c.flag_actualizar,c.flag_imprimir,c.flag_acceso,
            c.flag_autoriza,c.flag_descarga,c.id_auditoria,d.id_activo,a.id_persona,b.id_acceso,
            b.id_status AS estado_acceso

            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            INNER JOIN tbl_accesos_usuarios_det c ON c.id_acceso=b.id_acceso
            INNER JOIN tbl_pantallas d ON d.id_pantalla=c.id_pantalla
            WHERE d.id_pantalla=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($pantalla));
    $empleados = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $empleados;
  }

  function get_accesos($modulo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona,c.id_item,c.descripcion_corta,b.id_acceso,b.id_status,
            d.descripcion_corta AS estado
            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            INNER JOIN tbl_catalogo_detalle c ON b.id_sistema=c.id_item
            INNER JOIN tbl_catalogo_detalle d ON b.id_status=d.id_item
            WHERE c.id_item=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($modulo));
    $accesos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $accesos;
  }

  function get_permisos_por_acceso($acceso){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            d.descrip_corta,c.flag_es_menu,c.flag_insertar,d.id_pantalla,
            c.flag_eliminar,c.flag_actualizar,c.flag_imprimir,c.flag_acceso,
            c.flag_autoriza,c.flag_descarga,c.id_auditoria,d.id_activo,a.id_persona,b.id_acceso,
            b.id_status AS estado_acceso

            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            INNER JOIN tbl_accesos_usuarios_det c ON c.id_acceso=b.id_acceso
            INNER JOIN tbl_pantallas d ON d.id_pantalla=c.id_pantalla
            WHERE b.id_acceso=? ORDER BY d.id_pantalla ASC";
    $p = $pdo->prepare($sql);
    $p->execute(array($acceso));
    $permisos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $permisos;
  }

  function get_pantalla_by_id($pantalla){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_pantalla, id_pantalla_padre, descrip_corta, descripcion, id_activo
            FROM tbl_pantallas
            WHERE id_pantalla=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($pantalla));
    $pantalla = $p->fetch();
    Database::disconnect_sqlsrv();
    return $pantalla;
  }
  function get_modulo_by_id($mod){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_item, descripcion_corta, descripcion
            FROM tbl_catalogo_detalle
            WHERE id_item=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($mod));
    $modulo = $p->fetch();
    Database::disconnect_sqlsrv();
    return $modulo;
  }
  function get_persona_by_acceso($acceso){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT (a.primer_nombre+' '+a.segundo_nombre+' '+
                    a.primer_apellido+' '+
                    a.segundo_apellido) AS nombre,
            a.id_persona, b.id_acceso,a.id_status
            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            WHERE b.id_acceso=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($acceso));
    $datos = $p->fetch();
    Database::disconnect_sqlsrv();
    return $datos;
  }
  function get_accesos_by_persona($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.primer_nombre, a.segundo_nombre,
            a.tercer_nombre,
            a.primer_apellido,
            a.segundo_apellido,
            a.tercer_apellido,
            a.id_persona, b.id_acceso,b.id_status,c.descripcion_corta AS modulo,b.id_sistema as id_modulo,
            d.descripcion_corta AS estado
            FROM rrhh_persona a
            INNER JOIN tbl_accesos_usuarios b ON b.id_persona=a.id_persona
            INNER JOIN tbl_catalogo_detalle c ON b.id_sistema=c.id_item
            INNER JOIN tbl_catalogo_detalle d ON b.id_status=d.id_item
            WHERE a.id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $accesos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $accesos;
  }

  function get_accesos_by_persona_que_no_tiene($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_item as id_modulo,
            descripcion as modulo,
            id_status
            FROM tbl_catalogo_detalle t1
            WHERE t1.id_item NOT IN
            (SELECT t2.id_sistema FROM tbl_accesos_usuarios t2
              WHERE t2.id_persona=?) AND t1.id_catalogo=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona,65));
    $accesos = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $accesos;
  }
  function getr_usuario_por_persona($persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona, persona_user, persona_pass, status FROM rrhh_persona_usuario WHERE id_persona=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($persona));
    $usuario = $p->fetch();
    Database::disconnect_sqlsrv();
    return $usuario;
  }

  function get_catalogo(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT TOP(6625) c.id_catalogo, c.descripcion AS cdescripcion, c.Comentario, d.descripcion AS ddescripcion, d.descripcion_corta, d.id_item
            FROM [SAAS_APP].[dbo].[tbl_catalogo_detalle] d
            LEFT JOIN tbl_catalogo c ON d.id_catalogo = c.id_catalogo
            ORDER BY d.id_catalogo";
    $p = $pdo->prepare($sql);
    $p->execute();
    $usuario = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $usuario;
  }

  function get_usuarios(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT u.id_persona, r.nombre, r.dir_funcional, r.p_funcional, u.persona_user, u.status, u.valida_ldap
            FROM [SAAS_APP].[dbo].[rrhh_persona_usuario] u
            LEFT JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] r ON u.id_persona=r.id_persona";
    $p = $pdo->prepare($sql);
    $p->execute();
    $usuario = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $usuario;
  }

  function cleanString($text) {
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}



  function get_listado(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Ppr_id, Ppr_Ren, Ppr_cod, Ppr_Nom, Ppr_Des, Ppr_Pres, Med_nom, Ppr_codPre
            FROM [APP_POS].[dbo].[PPR] P
            JOIN [APP_POS].[dbo].[MEDIDA] M ON M.Med_id = P.Med_id";
    $p = $pdo->prepare($sql);
    $p->execute();
    $usuario = $p->fetchAll();
    Database::disconnect_sqlsrv();
    return $usuario;
  }

  static function get_empleado_fotografia($id_persona){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id_persona,id_fotografia,id_tipo_fotografia,fotografia_principal,fotografia,
                    descripcion,id_auditoria
                FROM rrhh_persona_fotografia
                WHERE id_persona=?;";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_persona));
    $foto = $p->fetch();
    Database::disconnect_sqlsrv();
    return $foto;
}

      function get_directorio(){
              $pdo = Database::connect_sqlsrv();
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $sql = "SELECT t.id_persona, d.extension, d.ubicacion, d.puesto, r.primer_nombre, r.segundo_nombre, r.tercer_nombre, r.primer_apellido, r.segundo_apellido, r.tercer_apellido, ISNULL(t.correo, '') AS correo, t.de_persona
              FROM tbl_tel_directorio d
              LEFT JOIN tbl_tel_empleado t
              ON d.id = t.empleado_ext
              LEFT JOIN rrhh_persona r
              ON t.id_persona = r.id_persona
              WHERE d.estado!=0 OR t.estado!=0
              ORDER BY d.id ASC";
              $p = $pdo->prepare($sql);
              $p->execute();
              $directorio = $p->fetchAll();
              Database::disconnect_sqlsrv();
              return $directorio;
        }

        function get_directorio_by_id($id_persona, $extension){
          $pdo = Database::connect_sqlsrv();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "SELECT d.id, t.id_persona, d.extension, d.ubicacion, d.puesto, r.primer_nombre, r.segundo_nombre, r.tercer_nombre, r.primer_apellido, r.segundo_apellido, r.tercer_apellido, ISNULL(t.correo, '') AS correo, t.de_persona
          FROM tbl_tel_directorio d
          LEFT JOIN tbl_tel_empleado t
          ON d.id = t.empleado_ext
          LEFT JOIN rrhh_persona r
          ON t.id_persona = r.id_persona
          WHERE d.extension=? AND t.id_persona=?
          ORDER BY d.id ASC";
          $p = $pdo->prepare($sql);
          $p->execute(array($extension, $id_persona));
          $directorio = $p->fetchAll();
          Database::disconnect_sqlsrv();
          return $directorio;
    }

    function del_ext_usr($id_persona){
}



    function update_extension($opcion, $new_val, $id_persona, $id){
      
      if($id > 0){
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch($opcion){

          case 'extension':
            $sql = "UPDATE tbl_tel_empleado SET empleado_ext = (SELECT id FROM tbl_tel_directorio WHERE extension = ?) WHERE empleado_ext = ? AND  id_persona = ?
            ";
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($new_val, $id, $id_persona));
            Database::disconnect_sqlsrv();
            return $response;

          case 'correo':
            $sql = "UPDATE tbl_tel_empleado SET correo = ? WHERE empleado_ext = ? AND  id_persona = ?
            ";
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute(array($new_val, $id, $id_persona));
            Database::disconnect_sqlsrv();
            return $response;


        }
        
        
        // $sql="SELECT nro_telefono FROM rrhh_persona_telefonos WHERE id_telefono = :id_telefono";
        // $stmt = $pdo->prepare($sql);
        // $stmt->bindParam("id_telefono", $id_telefono);
        // $stmt->execute();
        // $ref = $stmt->fetch();
        // $valor_anterior = array(
        //     "id_telefono"=>$id_telefono,
        //     "nro_telefono"=>$ref[0]
        // );
        // $valor_nuevo = array(
        //     "id_telefono"=>$id_telefono,
        //     "nro_telefono"=>$nro_new
        // );
        // $log = "VALUES(228, 1875, 'rrhh_persona_telefonos', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
        // $sql = "UPDATE rrhh_persona_telefonos SET nro_telefono = ? WHERE id_telefono = ? 
        // INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
        // (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
        // ".$log;

        
    }else{
        return false;
    }
}

}
?>
