<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions_plaza.php';
  date_default_timezone_set('America/Guatemala');
  $clase=new plaza;

  $id_persona = $_SESSION['id_persona'];
  $id_plaza = (!empty($_POST['id_plaza'])) ? $_POST['id_plaza'] : NULL;

  $tipo = $_POST['tipo'];
  $id_cod_plaza = $_POST['id_cod_plaza'];
  $id_partida = $_POST['id_partida'];
  $arraySueldos = $_POST['arraySueldos'];
  $descripcionPlaza = (!empty($_POST['descripcionPlaza'])) ? $_POST['descripcionPlaza'] : NULL;
  $id_puesto_n = $_POST['id_puesto_n'];
  $fecha = date('Y-m-d H:i:s');

  $idNivelNo = (!empty($_POST['idNivelNo'])) ? $_POST['idNivelNo'] : NULL;
  $idSecretariaNo = (!empty($_POST['idSecretariaNo'])) ? $_POST['idSecretariaNo'] : NULL;
  $idSubSecretariaNo = (!empty($_POST['idSubSecretariaNo'])) ? $_POST['idSubSecretariaNo'] : NULL;
  $idDireccionNo = (!empty($_POST['idDireccionNo'])) ? $_POST['idDireccionNo'] : NULL;
  $idSubDireccionNo = (!empty($_POST['idSubDireccionNo'])) ? $_POST['idSubDireccionNo'] : NULL;
  $idDepartamentoNo = (!empty($_POST['idDepartamentoNo'])) ? $_POST['idDepartamentoNo'] : NULL;
  $idSeccionNo = (!empty($_POST['idSeccionNo'])) ? $_POST['idSeccionNo'] : NULL;
  $idSecretariaF = (!empty($_POST['idSecretariaF'])) ? $_POST['idSecretariaF'] : NULL;
  $idSubSecretariaF = (!empty($_POST['idSubSecretariaF'])) ? $_POST['idSubSecretariaF'] : NULL;
  $idDireccionF = (!empty($_POST['idDireccionF'])) ? $_POST['idDireccionF'] : NULL;
  $idSubDireccionF = (!empty($_POST['idSubDireccionF'])) ? $_POST['idSubDireccionF'] : NULL;
  $idDepartamentoF = (!empty($_POST['idDepartamentoF'])) ? $_POST['idDepartamentoF'] : NULL;
  $idSeccionF = (!empty($_POST['idSeccionF'])) ? $_POST['idSeccionF'] : NULL;
  $idPuestoF = (!empty($_POST['idPuestoF'])) ? $_POST['idPuestoF'] : NULL;
  $idNivelF = (!empty($_POST['idNivelF'])) ? $_POST['idNivelF'] : NULL;


  $pdo = Database::connect_sqlsrv();

  //echo $id_remocion_reingreso;

  $yes = '';
  $message = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($tipo == 1){
      //inicio
      $sql3 = "INSERT INTO rrhh_plaza (cod_plaza,partida_presupuestaria,id_status,descripcion,
                id_puesto,id_auditoria,existe,vacante)
                VALUES (?,?,?,?,?,?,?,?)
                ";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($id_cod_plaza,$id_partida,890,$descripcionPlaza, $id_puesto_n,NULL, 'X','Y'));

      $id_plaza = $pdo->lastInsertId();

      $sql4 = "INSERT INTO rrhh_plazas_sueldo (id_plaza, actual, fecha_asignacion, observaciones)
              VALUES(?,?,?,?)";
      $q4 = $pdo->prepare($sql4);
      $q4->execute(array($id_plaza,1,$fecha,NULL));

      $id_sueldo = $pdo->lastInsertId();

      $sueldo = 0;
      $reng_num = 0;

      foreach ($arraySueldos AS $key => $su) {
        // code...
        if($key > 0){
          if($su["bln_confirma"] == 'true'){
            $reng_num ++;
            $sql5 = "INSERT INTO rrhh_plazas_sueldo_detalle (id_sueldo, reng_num, id_concepto, monto_p, monto_n)
                    VALUES (?,?,?,?,?)";
            $q5 = $pdo->prepare($sql5);
            $q5->execute(array($id_sueldo,$reng_num,intval($su['id_item']),floatval($su['monto_n']),0));
            $sueldo += floatval($su['monto_n']);
          }
        }

      }


      $clase->ingresar_historial_hst_plaza($id_plaza,$idPuestoF,$idNivelNo,$idSecretariaNo,$idSubSecretariaNo,$idDireccionNo,$idSubDireccionNo,$idDepartamentoNo,$idSeccionNo,$idNivelF,$idSecretariaF,$idSubSecretariaF,
      $idDireccionF,$idSubDireccionF,$idDepartamentoF,$idSeccionF,$sueldo);

      $sql5 = "INSERT INTO rrhh_plaza_detalle_ubicacion (id_plaza,reng_num,id_nivel_n,id_secretaria_n,id_subsecretaria_n,id_direccion_n,id_subdireccion_n,
        id_departamento_n,id_seccion_n,id_nivel_f,id_secretaria_f,id_subsecretaria_f,id_direccion_f,id_subdireccion_f,id_departamento_f,id_seccion_f,id_puesto_f,id_sueldo,id_status)
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q5 = $pdo->prepare($sql5);
      $q5->execute(array($id_plaza,1,$idNivelNo,$idSecretariaNo,$idSubSecretariaNo,$idDireccionNo,$idSubDireccionNo,$idDepartamentoNo,$idSeccionNo,$idNivelF,$idSecretariaF,$idSubSecretariaF,
      $idDireccionF,$idSubDireccionF,$idDepartamentoF,$idSeccionF,$idPuestoF,$id_sueldo,1));

      $valor_anterior = array(
        'id_persona'=>$id_persona,
        'estado'=>891
        //'estado'=>1051
      );

      $valor_nuevo = array(
        'id_persona'=>$id_persona,
        'descripcion'=>'Se creó plaza',
        'id_contrato'=>$id_plaza
      );

      $log = "VALUES(82, 1163, 'rrhh_empleado_plaza', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
      $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array());

      createLog(175, 1163, 'rrhh_plaza','Se creó nueva plaza = id_plaza: '.$id_plaza,'', '');
      //fin
      $message = 'Plaza creada';
    }else
    if($tipo == 2){
      //inicio
      //inicio
      $sql3 = "UPDATE rrhh_plaza SET cod_plaza = ?,partida_presupuestaria = ?,id_status = ?,descripcion = ?,
                id_puesto = ?,id_auditoria = ?,existe = ?,vacante = ?
                WHERE id_plaza = ?
                ";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($id_cod_plaza,$id_partida,890,$descripcionPlaza, $id_puesto_n,NULL, 'X','Y',$id_plaza));



      $sql55 = "SELECT b.id_sueldo, SUM(a.monto_p) AS sueldo
                FROM rrhh_plazas_sueldo_detalle a
                INNER JOIN rrhh_plazas_sueldo b ON a.id_sueldo = b.id_sueldo
                WHERE b.id_plaza = ? AND b.actual = 1
                GROUP BY b.id_sueldo";
      $q55 = $pdo->prepare($sql55);
      $q55->execute(array($id_plaza));
      $sueldoP = $q55->fetch();
      //echo 'sueldo actual ---- ----'.$sueldoP['sueldo'] .' ??? -- ';
      $sumaSueldo = array_sum(array_column($arraySueldos, 'monto_n'));

      if($sueldoP['sueldo'] == $sumaSueldo){
        $id_sueldo = $sueldoP['id_sueldo'];
        $sueldo = $sueldoP['id_sueldo'];
        //echo '1 --- '. $sumaSueldo. ' ||| '.$sueldoP['sueldo'];
      }else{
        //echo '2 --- '. $sumaSueldo. ' ||| '.$sueldoP['sueldo'];
        //inicio

        $sql4 = "UPDATE rrhh_plazas_sueldo SET actual = ?
                WHERE id_plaza = ?";
        $q4 = $pdo->prepare($sql4);
        $q4->execute(array(0,$id_plaza));
        $sql4 = "INSERT INTO rrhh_plazas_sueldo (id_plaza, actual, fecha_asignacion, observaciones)
                VALUES(?,?,?,?)";
        $q4 = $pdo->prepare($sql4);
        $q4->execute(array($id_plaza,1,$fecha,NULL));

        $id_sueldo = $pdo->lastInsertId();

        $sueldo = 0;
        $reng_num = 0;

        foreach ($arraySueldos AS $key => $su) {
          // code...
          //if($key > 0){
            if($su["bln_confirma"] == 'true'){
              $reng_num ++;
              $sql5 = "INSERT INTO rrhh_plazas_sueldo_detalle (id_sueldo, reng_num, id_concepto, monto_p, monto_n)
                      VALUES (?,?,?,?,?)";
              $q5 = $pdo->prepare($sql5);
              $q5->execute(array($id_sueldo,$reng_num,intval($su['id_item']),floatval($su['monto_n']),0));
              $sueldo += floatval($su['monto_n']);
            }
          //}

        }

        //fin
      }

      $clase->ingresar_historial_hst_plaza($id_plaza,$idPuestoF,$idNivelNo,$idSecretariaNo,$idSubSecretariaNo,$idDireccionNo,$idSubDireccionNo,$idDepartamentoNo,$idSeccionNo,$idNivelF,$idSecretariaF,$idSubSecretariaF,
      $idDireccionF,$idSubDireccionF,$idDepartamentoF,$idSeccionF,$id_sueldo);

      $sql5 = "UPDATE rrhh_plaza_detalle_ubicacion SET reng_num = ?,id_nivel_n = ?,id_secretaria_n = ?,id_subsecretaria_n = ?,id_direccion_n = ?,id_subdireccion_n = ?,
        id_departamento_n = ?,id_seccion_n = ?,id_nivel_f = ?,id_secretaria_f = ?,id_subsecretaria_f = ?,id_direccion_f = ?,id_subdireccion_f = ?,id_departamento_f = ?,id_seccion_f = ?,id_puesto_f = ?,id_sueldo = ?,id_status = ?
              WHERE id_plaza = ?";
      $q5 = $pdo->prepare($sql5);
      $q5->execute(array(1,$idNivelNo,$idSecretariaNo,$idSubSecretariaNo,$idDireccionNo,$idSubDireccionNo,$idDepartamentoNo,$idSeccionNo,$idNivelF,$idSecretariaF,$idSubSecretariaF,
      $idDireccionF,$idSubDireccionF,$idDepartamentoF,$idSeccionF,$idPuestoF,$id_sueldo,1,$id_plaza));

      $valor_anterior = array(
        'id_persona'=>$id_persona,
        'estado'=>891
        //'estado'=>1051
      );

      $valor_nuevo = array(
        'id_persona'=>$id_persona,
        'descripcion'=>'Se actualizó plaza',
        'id_contrato'=>$id_plaza
      );

      $log = "VALUES(82, 1163, 'rrhh_empleado_plaza', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
      $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array());

      createLog(175, 1163, 'rrhh_plaza','Se la plaza plaza = id_plaza: '.$id_plaza,'', '');
      //fin
      $message = 'Plaza actualizada';
      //fin
    }

    $pdo->commit();

    $yes = array('msg'=>'OK','message'=>$message);
  }catch (PDOException $e){
    $yes = array('msg'=>'ERROR','message'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','message'=>$e2);
    }
  }

  Database::disconnect_sqlsrv();

  echo json_encode($yes);



endif;
?>
