<?php
class noticia {

  static function get_noticias($ini,$fin,$red,$categoria,$usuario){
    $pdo = Database::connect_sqlsrv();
    $stmt='';
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql='';

    $sql.= "SELECT a.id_noticia, a.fecha, a.noticias_alias, a.noticia_categoria, a.noticia_usuario, a.noticia_fuente,a.noticia_url, a.noticia_observaciones,
                  a.noticia_propietario, a.noticia_status, b.id_item, b.descripcion AS red_social, c.primer_nombre, c.segundo_nombre, c.tercer_nombre,
                  c.primer_apellido, c.segundo_apellido, c.tercer_apellido, d.nombre as municipio, e.nombre as departamento,
                  f.primer_nombre as nombre, f.primer_apellido as apellido
            FROM inf_noticia a
            INNER JOIN tbl_catalogo_detalle b ON a.noticia_fuente = b.id_item
            INNER JOIN rrhh_persona c ON a.noticia_propietario = c.id_persona
            INNER JOIN tbl_municipio d ON a.noticia_municipio = d.id_municipio
            INNER JOIN tbl_departamento e ON d.id_departamento = e.id_departamento
            LEFT JOIN rrhh_persona f ON a.usuario_aprobacion = f.id_persona
            WHERE convert(varchar, a.fecha, 23) BETWEEN ? AND ?";

    if($red > 0){
        $sql .= " AND a.noticia_fuente = $red";
    }
    if($categoria > 0){
        $sql .= " AND a.noticia_categoria = $categoria";
    }
    if($usuario > 0){
        $sql .= " AND a.noticia_propietario = $usuario";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($ini,$fin));
    $noticias = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $noticias;
  }

  static function get_redes_sociales(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_item, descripcion
            FROM tbl_catalogo_detalle
            WHERE id_catalogo=?";


    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(140));
    $redes = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $redes;
  }

  static function get_departamentos(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_departamento, nombre
            FROM tbl_departamento
            WHERE id_pais = 'GT' AND id_auditoria = 0;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute()){
      $response =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
      $response = [];
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

  static function get_noticias_by_group($ini,$fin,$red,$categoria){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(*) AS conteo, b.descripcion
            FROM inf_noticia a
            INNER JOIN tbl_catalogo_detalle b ON a.noticia_fuente=b.id_item
            WHERE convert(varchar, a.fecha, 23) BETWEEN ? AND ?";

            if($red==0 && $categoria == 0){
              $sql.=" ";
            }else if($red>0 && $categoria == 0){
              $sql .=" AND a.noticia_fuente = $red";
            }else if($red==0 && $categoria > 0){
              $sql .=" AND a.noticia_categoria = $categoria";
            }else if($red>0 && $categoria > 0){
              $sql.=" AND a.noticia_fuente=$red AND a.noticia_categoria=$categoria";
            }
            $sql.="GROUP BY b.descripcion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($ini,$fin));
    $conteo = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $conteo;
  }

  static function get_noticias_by_usuario($ini,$fin,$red,$categoria){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql= "SELECT TOP 5 COUNT(*) AS conteo, noticia_usuario
            FROM inf_noticia
            WHERE convert(varchar, fecha, 23) BETWEEN ? AND ?";
            
            if($red > 0)$sql.=" AND noticia_fuente = $red";  
            if($categoria > 0)$sql.=" AND noticia_categoria = $categoria";
          
            $sql .= " GROUP BY noticia_usuario
                      ORDER BY conteo DESC;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($ini,$fin))){
      $response = $stmt->fetchAll();
    }else{
      $response = [];
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_usuarios(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT(noticia_propietario)AS id_persona, P.primer_nombre as nombre, P.primer_apellido as apellido
            FROM inf_noticia N
            INNER JOIN rrhh_persona P ON N.noticia_propietario = P.id_persona;";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute()){
      $response = $stmt->fetchAll();
    }else{
      $response = [];
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_noticia_by_id($id_noticia){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $response = array();
    if($id_noticia != ""){
        $sql = "SELECT *, M.id_municipio, M.id_departamento, M.nombre
                FROM inf_noticia N 
                INNER JOIN tbl_municipio M ON N.noticia_municipio = M.id_municipio
                WHERE id_noticia = ?;";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute(array($id_noticia))){
          $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $response = [];
        }
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

}

?>
