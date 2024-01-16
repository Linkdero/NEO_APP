<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $solicitante = $_SESSION['id_persona'];

  $reng_num=$_POST['reng_num'];
  $id_empleado=$_POST['id_empleado'];
  $bln_cheque=$_POST['bln_cheque'];
  $sueldo=5000;

  if (is_numeric($id_empleado)) {
    $clase = new empleado;
    $e = $clase->get_empleado_by_id_ficha($solicitante);

    $fecha_comision=$_POST['fecha_comision'];
    $fecha_salida=$_POST['fecha_salida'];
    $hora_salida=$_POST['hora_salida'];
    $fecha_regreso=$_POST['fecha_regreso'];
    $hora_regreso=$_POST['hora_regreso'];
    //$id_tipo_nombramiento=$_POST['id_tipo_nombramiento'];
    //$id_tipo_evento=$_POST['id_tipo_evento'];
    $motivo=$_POST['motivo'];
    $id_fecha_cheque=$_POST['id_fecha_cheque'];
    $id_hora_cheque=$_POST['id_hora_cheque'];
    $id_funcionario=$_POST['id_funcionario'];
    $pais=$_POST['pais'];
    $departamento=$_POST['departamento'];
    $municipio=$_POST['municipio'];
    $aldea=$_POST['aldea'];
    $beneficios=$_POST['beneficios'];
    $observaciones=$_POST['observaciones'];

    $formulario_anterior=$_POST['formulario_anterior'];
    $extension=$_POST['extension'];

    $hospedaje=0;
    $alimentacion=0;
    if($beneficios==1){
      $hospedaje=1;
      $alimentacion=1;
    }else if($beneficios==2){
      $hospedaje=1;
    }else if($beneficios==3){
      $alimentacion=1;
    }

    $direccion =$e['id_dirf'];
    if($e['id_tipo']==2){
      $direccion=$e['id_dirs'];
    }
    else
    if($e['id_tipo']==4){
      $direccion=$e['id_dirapy'];
    }

    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql0 = "SELECT TOP 1 (nro_nombramiento) AS id FROM vt_nombramiento
               WHERE id_rrhh_direccion=? AND YEAR(fecha)=?
               ORDER BY vt_nombramiento DESC";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($direccion,date('Y')));
      $nombramiento_max = $q0->fetch();

      $nro_nombramiento;
      if($nombramiento_max['id']!=''){
        $nro_nombramiento=$nombramiento_max['id']+1;
      }else{
        $nro_nombramiento=1;
      }


      //echo $id_bodega;

      $sql = "INSERT INTO vt_nombramiento
                          (
                            fecha,
                            id_status,
                            id_rrhh_direccion,
                            id_pais,
                            id_departamento,
                            id_municipio,
                            id_aldea,
                            id_tipo_nombramiento,
                            id_tipo_evento,

                            fecha_salida,
                            hora_salida,
                            fecha_regreso,
                            hora_regreso,
                            motivo,




                            usr_solicita,
                            funcionario,
                            fecha_solicita_cheque,
                            hora_solicita_cheque,
                            observaciones,
                            bln_hospedaje,
                            bln_alimentacion,

                            nro_nombramiento_relacionado,
                            bln_extension,
                            nro_nombramiento

                          )
                          VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
       $q = $pdo->prepare($sql);
       $q->execute(
         array(
           date('Y-m-d', strtotime($fecha_comision)),
           932,
           $direccion,
           $pais,
           $departamento,
           $municipio,
           $aldea,
           1077,
           1116,
           date('Y-m-d', strtotime($fecha_salida)),
           $hora_salida,
           date('Y-m-d', strtotime($fecha_regreso)),
           $hora_regreso,
           $motivo,
           $solicitante,
           $id_funcionario,
           date('Y-m-d', strtotime($id_fecha_cheque)),
           $id_hora_cheque,
           $observaciones,
           $hospedaje,
           $alimentacion,
           $formulario_anterior,
           $extension,
           $nro_nombramiento
         ));

            $sql2 = "SELECT MAX(vt_nombramiento) AS id FROM vt_nombramiento
                     WHERE usr_solicita=?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($solicitante));
            $codigo = $q2->fetch();

            $vt_nombramiento=$codigo['id'];



              $clase = new empleado;
              $e = $clase->get_empleado_by_id_ficha($id_empleado);

              if($e['id_tipo']==1){
                //011
                $sueldo=11;
              }else
              if($e['id_tipo']==2){
                //031
                $sueldo=31;
              }


              $sql = "EXEC sp_ins_detalle_nombramiento @nombramiento=?, @empleado=?, @cheque=?,@reemplazo=?,@p=?";
              $p = $pdo->prepare($sql);
              $p->execute(array($vt_nombramiento,$id_empleado,$bln_cheque,0,0));// 65 es el id de aplicaciones
              //kardex = $p->fetchAll();
              Database::disconnect_sqlsrv();

      $pdo->commit();
    }catch (PDOException $e){
      echo $e;
      try{ $pdo->rollBack();}catch(Exception $e2){
        echo $e2;
      }
    }

  }



  /*$pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);*/



}
else{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}




?>
