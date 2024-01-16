<?php
class visita {
  function get_visitas($id_puerta = null){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($id_puerta == null){
      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL' AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
    }else{
      $fecha = date("Y-m-d");
      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL' AND CV.estado = 1 AND CV.id_puerta = ".$id_puerta." AND CV.fecha = '".$fecha."'
              ORDER BY CV.id_visita DESC";

    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $visitas = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $visitas;
  }
  function get_visitas_sub($id_puerta,$ini,$fin,$oficina,$puerta,$no_salido){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($oficina == 0 && $puerta==0 && $no_salido==0){
      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL' AND CV.estado = 1
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ?
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;
    }else
    if($oficina > 0 && $puerta==0 && $no_salido==0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL' AND CV.estado = 1
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_oficina=?
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$oficina));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    } else
    if($oficina>0 && $puerta>0 && $no_salido>0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_oficina=? AND CV.id_puerta=? AND CV.hora_sale != NULL AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$oficina,$puerta));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    } else
    if($oficina == 0 && $puerta>0 && $no_salido==0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_puerta=? AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$puerta));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    }
    else
    if($oficina == 0 && $puerta==0 && $no_salido>0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.salida = 0 AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    } else
    if($oficina > 0 && $puerta==0 && $no_salido>0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_oficina=? AND CV.salida = 0 AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$oficina));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    }else
    if($oficina == 0 && $puerta>0 && $no_salido>0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_puerta=? AND CV.salida = 0 AND CV.estado = 1
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$puerta));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    } else
    if($oficina>0 && $puerta>0 && $no_salido==0)
    {

      $sql = "SELECT CV.id_captura1, CV.id_visita, CV.fecha, CV.hora_entra, CV.hora_sale, CV.id_oficina, CP.nombre_puerta, CV.dependencia, CV.no_gafete, CV.autoriza, CV.de_oficina, CV.de_visita, CP.ruta_puerta
              FROM tbl_control_visitas CV INNER JOIN tbl_control_puerta CP ON CV.id_puerta = CP.id_puerta
              WHERE CV.de_visita != 'NULL' AND CV.de_oficina != 'NULL' AND CV.de_oficina != 'NULL' AND CV.autoriza != 'NULL' AND CV.dependencia != 'NULL'
              AND convert(varchar, CV.fecha, 23) BETWEEN ? AND ? AND CV.id_oficina=? AND CV.id_puerta=?
              ORDER BY CV.id_visita DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($ini,$fin,$oficina,$puerta));
              $visitas = $stmt->fetchAll();
              Database::disconnect_sqlsrv();
              return $visitas;

    }

  }

  function get_oficinas($id_puerta){
    //$pdo = Database::connect_sqlsrv();
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_oficina, nombre_oficina
            FROM tbl_control_oficina";
    $p = $pdo->prepare($sql);
    if($p->execute()){
        $visitas = $p->fetchAll();
        return $visitas;
    }
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_autoriza(){
    //$pdo = Database::connect_sqlsrv();
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_oficina, nombre_oficina
            FROM tbl_control_oficina";
    $p = $pdo->prepare($sql);
    if($p->execute()){
        $visitas = $p->fetchAll();
        return $visitas;
    }
    //Database::disconnect_sqlsrv();
    Database::disconnect_sqlsrv();
    return $response;
  }

  static function get_data_by_ip($ip_address){
    //$ip_address = "172.16.0.162";
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.id_puerta, b.ip_camara, b.ruta_puerta, a.grupo, b.nombre_puerta, b.ip_address
            FROM tbl_control_puerta_persona a
            INNER JOIN tbl_control_puerta b ON a.id_puerta = b.id_puerta
            WHERE b.ip_address = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($ip_address));
    $response = $stmt->fetch();
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_puertas(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_puerta, nombre_puerta
            FROM tbl_control_puerta";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $response = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_data_by_doors($ini,$fin,$oficina,$puerta,$no_salido){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT count(V.id_puerta) as visitas, P.nombre_puerta as puerta, P.siglas_puerta as siglas
            FROM tbl_control_visitas V INNER JOIN tbl_control_puerta P ON V.id_puerta = P.id_puerta
            WHERE V.de_visita != 'NULL' AND V.de_oficina != 'NULL' AND V.de_oficina != 'NULL' AND V.autoriza != 'NULL' AND V.dependencia != 'NULL' AND V.estado = 1 AND convert(varchar, V.fecha, 23) BETWEEN ? AND ?
            ";

            if($oficina > 0 && $puerta==0 && $no_salido==0)
            {
              $sql.="AND V.id_oficina=$oficina";
        } else
            if($oficina>0 && $puerta>0 && $no_salido>0)
            {
              $sql.="AND V.id_oficina=$oficina AND V.id_puerta=$puerta AND V.hora_sale != NULL";
        } else
            if($oficina == 0 && $puerta>0 && $no_salido==0)
            {
              $sql.="AND V.id_puerta=$puerta";
        }
            else
            if($oficina == 0 && $puerta==0 && $no_salido>0)
            {
              $sql.='AND V.salida = 0';
        } else
            if($oficina > 0 && $puerta==0 && $no_salido>0)
            {
              $sql.="AND V.id_oficina=$oficina AND V.salida = 0";
        }else
            if($oficina == 0 && $puerta>0 && $no_salido>0)
            {
              $sql.="AND V.id_puerta=$puerta AND V.salida = 0";
        }
        $sql.="GROUP BY V.id_puerta, P.nombre_puerta, P.siglas_puerta
        ORDER BY visitas DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($ini,$fin));
    $response = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $response;
  }

  function get_data_by_date($siglas,$ini,$fin){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT count(V.fecha) as visitas, V.fecha
            FROM tbl_control_visitas V INNER JOIN tbl_control_puerta P ON V.id_puerta = P.id_puerta
            WHERE P.siglas_puerta = ? AND convert(varchar, V.fecha, 23) BETWEEN ? AND ?
            GROUP BY V.fecha;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($siglas,$ini,$fin));
    $response = $stmt->fetchAll();
    Database::disconnect_sqlsrv();
    return $response;
  }

}
?>
