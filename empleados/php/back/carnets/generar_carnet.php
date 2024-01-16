<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $creador=$_SESSION['id_persona'];
  $creado_en = date('Y-m-d H:i:s');
  $id_empleado=$_POST['id_empleado'];

  $id_plaza=$_POST['id_plaza'];
  $id_persona=$_POST['id_persona'];
  $id_empleado=$_POST['id_empleado'];
  $id_tipo_carnet=$_POST['id_tipo_carnet'];
  $id_posee_arma=$_POST['id_posee_arma'];

  $year = date('Y');

  $clase=new empleado;
  $pdo = Database::connect_sqlsrv();
  $yes = '';
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $persona = $clase->get_empleado_by_id_ficha($id_persona);
    $foto = $clase->get_empleado_fotografia($id_persona);

    $sql0 = "SELECT TOP 1 id_version, id_status FROM rrhh_empleado_gafete WHERE id_contrato=? AND id_empleado = ? ORDER BY id_version DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($persona['id_tipo'],$id_empleado));
    $conteo = $q0->fetch(PDO::FETCH_ASSOC);

    $version = 1;
    $estado = 1;
    $crear = 1;
    if(!empty($conteo['id_version']))
    {
      $version = $conteo['id_version']+1;
      if($conteo['id_status']==4){
        $crear = 1;
      }else{
        $crear = 0;
      }
    }

    $fecha_v = '';
    if($persona['id_tipo'] == 1){
      $fecha_v = '2024-01-14';
    }else{
      $fecha_v = $persona['fecha_finalizacion'];
    }

    $estado = 1;
    if($crear == 1){
      $sql0 = "INSERT INTO rrhh_empleado_gafete (id_empleado, id_contrato, id_version, puesto, fecha_generado, fecha_vencimiento, creado_por, id_status, id_tipo_carnet, id_arma, id_fotografia)
               VALUES (?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array(
        $id_empleado,
        $persona['id_tipo'],
        $version,
        $persona['p_funcional'],
        date('Y-m-d H:i:s'),
        $fecha_v,
        $creador,
        $estado,
        $id_tipo_carnet,
        $id_posee_arma,
        $foto['id_fotografia']
      ));
      $yes = array('msg'=>'OK','id'=>'');
    }else{

      $yes = array('msg'=>'El empleado tiene un carnet activo','id'=>'');
    }

    $pdo->commit();
    }catch (PDOException $e){
      $yes = array('msg'=>'ERROR','id'=>$e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }

    echo json_encode($yes);
  else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
  endif;


?>
