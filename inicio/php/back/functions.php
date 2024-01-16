<?php
class acreditacion{
  static function get_usuario($id){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT a.Usu_id, a.Usu_log,
                     a.Usu_nom,a.Usu_psw,
                     a.Usu_niv,a.Usu_sta,a.usu_activo,
                     a.Eve_id,a.Pnt_id,
                     b.Eve_nom,
                     c.Pnt_des
              FROM USUARIO AS a
              LEFT JOIN EVENTO AS b ON a.Eve_id=b.Eve_id
              LEFT JOIN PUNTO AS c ON a.Pnt_id=c.Pnt_id
              WHERE a.Usu_id=? AND a.Usu_sta LIKE 1";
    $statement = $pdo->prepare($query);
    $statement->execute(array($id));
    $usuario = $statement->fetch();
    Database::disconnect_sqlsrv();
    return $usuario;
  }

  static function get_invitado($evento,$invitado,$tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo==1){
      $query = "SELECT a.Inv_id,
                       a.Inv_nom,
                       a.Inv_fotografia,
                       a.Inv_activo,
                       a.Eve_id,
                       b.Eve_nom,
                       a.Inv_pro
                FROM INVITADO AS a
                LEFT JOIN EVENTO AS b ON a.Eve_id=b.Eve_id
                WHERE a.Eve_id=? AND a.Inv_ref=? AND a.Inv_activo=?";
                $statement = $pdo->prepare($query);
                $statement->execute(array($evento,$invitado,1));
                $guest = $statement->fetch();

                return $guest;
    }
    else if($tipo==2){
      $query = "SELECT a.Inv_id,
                       a.Inv_nom,
                       a.Inv_fotografia,
                       a.Inv_activo,
                       a.Eve_id,
                       b.Eve_nom,
                       a.Inv_pro
                FROM INVITADO AS a
                LEFT JOIN EVENTO AS b ON a.Eve_id=b.Eve_id
                WHERE a.Eve_id=? AND a.Inv_ref=? ";
                $statement = $pdo->prepare($query);
                $statement->execute(array($evento,$invitado));
                $guest = $statement->fetch();

                return $guest;
    }

    Database::disconnect_sqlsrv();
  }

  static function verificar_entrada_salida($invitado,$valo,$fecha){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT COUNT(*) AS CONTEO
              FROM TRANSACCION
              WHERE Inv_id=? AND CONVERT(date, Tra_ent)=?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($invitado,$fecha));
    $guest = $statement->fetch();
    Database::disconnect_sqlsrv();
    $valor=$guest['CONTEO']+$valo;
    //if ($valor%2==0){
    if($valor==3 || $valor==4){

      return '
        <div class="u-icon u-icon--sm bg-danger text-white rounded-circle mr-2">
          <i class="fa fa-arrow-left"></i>
        </div>

        <div class="media-body">
          <h4 class="mb-0">Saliendo</h4>
        </div>
      ';
    }else{
      return '
        <div class="u-icon u-icon--sm bg-success text-white rounded-circle mr-2">
          <i class="fa fa-arrow-right"></i>
        </div>

        <div class="media-body">
          <h4 class="mb-0">Entrando</h4>
        </div>
      ';
    }



  }

  static function get_last_ingresos($evento,$puerta,$fecha){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT TOP 10 a.Inv_id, a.Inv_nom AS invitado,a.Inv_fotografia AS foto,
                        b.Tra_ent as fecha, a.Inv_pro AS institucion,b.tipo_registro
              FROM INVITADO AS a
              INNER JOIN TRANSACCION AS b ON b.Inv_id=a.Inv_id
              WHERE a.Eve_id=? AND b.Pnt_id=? AND CONVERT(date, Tra_ent)=?
	      ORDER BY b.Tra_ent DESC";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento,$puerta,$fecha));
    $ingresos = $statement->fetchAll();
    Database::disconnect_sqlsrv();
    return $ingresos;
  }

  static function contar_personas_por_puerta_1($evento,$punto){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query0 = "SELECT COUNT(a.Inv_id) FROM TRANSACCION a
              INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
              WHERE a.tipo_registro=? AND Eve_id=? AND a.Pnt_id=?
              GROUP BY a.Inv_id
              ";
    $statement0 = $pdo->prepare($query0);
    $statement0->execute(array(0,$evento,$punto));


    $query1 = "SELECT COUNT(a.Inv_id) FROM TRANSACCION a
              INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
              WHERE a.tipo_registro=? AND Eve_id=? AND a.Pnt_id=?
              GROUP BY a.Inv_id
              ";
    $statement1 = $pdo->prepare($query1);
    $statement1->execute(array(1,$evento,$punto));


    $total0 = $statement0->fetchAll();
    $total1 = $statement1->fetchAll();
    Database::disconnect_sqlsrv();
    return count($total0)-count($total1);

  }

  static function contar_personas_por_puerta($evento,$punto){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT a.Tra_num, b.Eve_id, b.Inv_nom,a.Inv_id, a.tipo_registro,a.Pnt_id FROM TRANSACCION a
              INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
              WHERE  Eve_id=? AND a.Pnt_id=? AND DAY(Tra_ent)=? AND MONTH(Tra_ent)=? AND YEAR(Tra_ent)=?
              ORDER BY a.Tra_num";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento,$punto,date('d'),date('m'),date('Y')));
    $procedencias = $statement->fetchAll();
    Database::disconnect_sqlsrv();

    return $procedencias;


    //return $total;


  }

  static function encontrar_salida_de_invitado($evento,$invitado,$tipo){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT TOP 1 a.Tra_num, a.tipo_registro,a.Pnt_id,a.Inv_id
              FROM TRANSACCION a
              INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
              WHERE  Eve_id=? AND a.Inv_id=? AND tipo_registro=?
              ORDER BY a.Tra_num DESC";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento,$invitado,$tipo));
    $salida = $statement->fetch();
    Database::disconnect_sqlsrv();

    return $salida;
  }

  static function get_all_invitados($evento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT COUNT(*) AS conteo FROM INVITADO WHERE Eve_id=? AND Inv_activo=? AND Inv_tipo BETWEEN ? AND ?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento,1,1,3));
    $total = $statement->fetch();
    Database::disconnect_sqlsrv();
    return $total;
  }

  static function contar_personas_adentro($evento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT b.Eve_id, b.Inv_nom,a.Inv_id, a.tipo_registro,a.Pnt_id,a.Tra_num FROM TRANSACCION a
              INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
              WHERE  Eve_id=? AND DAY(Tra_ent)=? AND MONTH(Tra_ent)=? AND YEAR(Tra_ent)=?
              ORDER BY a.Tra_num";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento,date('d'),date('m'),date('Y')));
    $procedencias = $statement->fetchAll();
    Database::disconnect_sqlsrv();

    return $procedencias;

  }

  static function get_procedencias($evento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT Inv_pro FROM INVITADO
              WHERE Eve_id=?
              GROUP BY Inv_pro";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento));
    $procedencias = $statement->fetchAll();
    Database::disconnect_sqlsrv();
    return $procedencias;
  }

  static function get_porcentaje($valor,$total){
   $porcentaje=  round((($valor/$total)*100));
    return $porcentaje;
  }
  static function get_invitados($evento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT Eve_id,
                      Inv_cor,
                      Inv_ref,
                      Inv_id,
                      Inv_nom,
                      Inv_pro,
                      Inv_iden,
                      Inv_sta,

                      Inv_activo
              FROM INVITADO
              WHERE Eve_id=?";
    $statement = $pdo->prepare($query);
    $statement->execute(array($evento));
    $invitados = $statement->fetchAll();
    Database::disconnect_sqlsrv();

    return $invitados;
  }

  static function get_reporte_totales($evento,$punto){
    if($punto==0){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT x.fecha, COUNT(x.fecha) AS conteo, x.Pnt_id FROM (
                    SELECT CONVERT(date, a.Tra_ent) AS fecha, a.Inv_id, MAX(a.Tra_num) AS transaccion, b.Inv_tipo, a.Pnt_id
                    FROM TRANSACCION a
                    INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
                    WHERE b.Eve_id=? AND a.tipo_registro=?
                    GROUP BY a.Inv_id, CONVERT(date, Tra_ent), b.Inv_tipo, a.Pnt_id
                )x
                GROUP BY x.fecha, x.Pnt_id";
      $statement = $pdo->prepare($query);
      $statement->execute(array($evento,0));
      $totales = $statement->fetchAll();
      Database::disconnect_sqlsrv();

      return $totales;
    }else{

    }

  }

  static function get_reporte_totales_por_tipo($evento,$inv_tipo,$fecha,$punto){
    if($punto==0){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT x.fecha, COUNT(x.fecha) AS conteo FROM (
                    SELECT CONVERT(date, a.Tra_ent) AS fecha, a.Inv_id, MAX(a.Tra_num) AS transaccion, b.Inv_tipo, a.Pnt_id
                    FROM TRANSACCION a
                    INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
                    WHERE b.Eve_id=? AND a.tipo_registro=? AND b.Inv_tipo=? AND CONVERT(date, a.Tra_ent)=? AND a.Pnt_id=?
                    GROUP BY a.Inv_id, CONVERT(date, Tra_ent), b.Inv_tipo
                )x
                ";
      $statement = $pdo->prepare($query);
      $statement->execute(array($evento,0,$inv_tipo,$fecha,$punto));
      $totales = $statement->fetch();
      Database::disconnect_sqlsrv();

      return $totales;

    }

  }

  static function get_reporte_totales_2($evento,$punto){
    if($punto==0){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT x.fecha, COUNT(x.fecha) AS conteo FROM (
                    SELECT CONVERT(date, a.Tra_ent) AS fecha, a.Inv_id, MAX(a.Tra_num) AS transaccion, b.Inv_tipo
                    FROM TRANSACCION a
                    INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
                    WHERE b.Eve_id=? AND a.tipo_registro=? AND a.Pnt_id=?
                    GROUP BY a.Inv_id, CONVERT(date, Tra_ent), b.Inv_tipo
                )x
                GROUP BY x.fecha";
      $statement = $pdo->prepare($query);
      $statement->execute(array($evento,0,$punto));
      $totales = $statement->fetchAll();
      Database::disconnect_sqlsrv();

      return $totales;
    }else{

    }

  }

  static function get_reporte_totales_por_tipo_2($evento,$inv_tipo,$fecha,$punto){
    if($punto==0){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT x.fecha, COUNT(x.fecha) AS conteo FROM (
                    SELECT CONVERT(date, a.Tra_ent) AS fecha, a.Inv_id, MAX(a.Tra_num) AS transaccion, b.Inv_tipo
                    FROM TRANSACCION a
                    INNER JOIN INVITADO b ON a.Inv_id=b.Inv_id
                    WHERE b.Eve_id=? AND a.tipo_registro=? AND b.Inv_tipo=? AND CONVERT(date, a.Tra_ent)=? AND a.Pnt_id=?
                    GROUP BY a.Inv_id, CONVERT(date, Tra_ent), b.Inv_tipo
                )x
                GROUP BY x.fecha";
      $statement = $pdo->prepare($query);
      $statement->execute(array($evento,0,$inv_tipo,$fecha,$punto));
      $totales = $statement->fetch();
      Database::disconnect_sqlsrv();

      return $totales;

    }

  }

  static function get_puntos_por_Evento($evento){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Eve_id, Pnt_id, Pnt_des, Usu_id
              FROM PUNTO
              WHERE Eve_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($evento));// 65 es el id de aplicaciones
    $puntos = $p->fetchAll();
    Database::disconnect_sqlsrv();

    return $puntos;
  }
  static function get_punto_por_id($pnt){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Eve_id, Pnt_id, Pnt_des, Usu_id
              FROM PUNTO
              WHERE Pnt_id=?";
    $p = $pdo->prepare($sql);
    $p->execute(array($pnt));// 65 es el id de aplicaciones
    $punto = $p->fetch();
    Database::disconnect_sqlsrv();

    return $punto;
  }

  static function get_horarios_empleados($direccion, $fecha){
    if($direccion==0){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT direccion, gafete, nombre, fecha, max(entrada) entrada, max(entra_alm) entra_alm, max(sale_alm) sale_alm, max(salida) salida from (

       select i.inv_pro direccion, i.inv_cor gafete, i.inv_nom nombre, t.tipo_registro io, t.tra_ent, convert(date,t.tra_ent) fecha, convert(time,t.tra_ent) hora,
       case when convert(time,t.tra_ent) between '00:00:00' and '10:30:59' then convert(time,t.tra_ent) else '' end entrada,
       case when convert(time,t.tra_ent) between '10:31:00' and '14:59:59' then case when t.tipo_registro=0 then convert(time,t.tra_ent) else '' end else '' end entra_alm ,
       case when convert(time,t.tra_ent) between '10:31:00' and '14:59:59' then case when t.tipo_registro=1 then convert(time,t.tra_ent) else '' end else '' end sale_alm ,
       case when convert(time,t.tra_ent) between '15:00:00' and '23:59:59' then convert(time,t.tra_ent) else '' end salida

       from transaccion t
             left outer join invitado i on t.inv_id = i.Inv_id
             left outer join saas_app.dbo.rrhh_persona p on i.inv_cor=p.id_persona

       where eve_id = 1042 and convert(date,t.tra_ent) = ?

) as query1

group by direccion, gafete, nombre, fecha
";
      $p = $pdo->prepare($sql);
      $p->execute(array($fecha));// 65 es el id de aplicaciones
      $horarios = $p->fetchAll();
      Database::disconnect_sqlsrv();
      return $horarios;
    }
    else{
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT Eve_id, Pnt_id, Pnt_des, Usu_id
                FROM PUNTO
                WHERE Eve_id=?";
      $p = $pdo->prepare($sql);
      $p->execute(array($direccion,$fecha));// 65 es el id de aplicaciones
      $horarios = $p->fetchAll();
      Database::disconnect_sqlsrv();
      return $horarios;
    }

  }
}


?>
