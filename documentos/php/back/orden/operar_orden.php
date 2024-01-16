<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];
  $creado_en = date('Y-m-d H:i:s');
  $observaciones = '';
  $persona = '';
  $campo = '';

  $pk = explode("-",$_POST['pk']);

  $id_tipo = $pk[1];
  $id_clase_proceso = $pk[2];
  //$nro_orden = $_POST['nro_orden'];
  /*$id_campo = $_POST['id_campo'];
  $id_pago = $_POST['id_pago'];*/


  $id_pago=$pk[0];
  $id_campo=$_POST['value'];
  $message = '';
  $campos = '';

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$id = $campo.' | '.$modalidad_pago.' | '.$tipo;

    if($id_tipo == 1){
      $sql0 = "SELECT cur_compromiso FROM  docto_ped_pago_presupuesto WHERE cur_compromiso = ?
               AND id_year = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          date('Y')
        )
      );
      $valor = $q0->fetch();

      if(empty($valor['cur_compromiso'])){
        //inicio
        $sql0 = "UPDATE docto_ped_pago SET cur = ?
                 WHERE id_pago = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_campo,
            $id_pago
          )
        );

        $sql0 = "UPDATE docto_ped_pago_presupuesto SET cur_compromiso = ?
                 WHERE id_pago = ? AND ISNULL(cur_compromiso,0) = 0";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_campo,
            $id_pago
          )
        );
        $message = 'CUR de compromiso asignado';
        $titulo = 'compromiso';
        $campos = 'cur_compromiso: '.$id_campo;
        if($id_clase_proceso == 3){
          //inicio
          $sql0 = "UPDATE docto_ped_pago SET cur_devengado = ?
                   WHERE id_pago = ? AND ISNULL(cur_devengado,0) = 0";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(
            array(
              $id_campo,
              $id_pago
            )
          );
          $sql0 = "UPDATE docto_ped_pago_presupuesto SET cur_devengado = ?
                   WHERE id_pago = ? AND ISNULL(cur_devengado,0) = 0";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(
            array(
              $id_campo,
              $id_pago
            )
          );
          //fin
          $message = 'CUR de compromiso y devengado asignado';
          $campos = 'valor: '.$id_campo .', valor2: '.$id_campo;
          $titulo = 'compromiso_devendago';
        }
        $observaciones = "{'message': '".$message."', 'titulo': '".$titulo."', ".$campos."}";
        //fin
      }else{
        $yes = array('msg'=>'ERROR','message'=>'Este CUR ya existe.');
      }

    }else
    if($id_tipo == 2){
      $sql0 = "SELECT cur_devengado FROM  docto_ped_pago_presupuesto WHERE cur_devengado = ?
               AND id_year = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          date('Y')
        )
      );
      $valor = $q0->fetch();

      if(empty($valor['cur_devengado'])){
        //inicio
        $sql0 = "UPDATE docto_ped_pago SET cur_devengado = ?
                 WHERE id_pago = ? AND ISNULL(cur_devengado,0) = 0";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_campo,
            $id_pago
          )
        );
        $sql0 = "UPDATE docto_ped_pago_presupuesto SET cur_devengado = ?
                 WHERE id_pago = ? AND ISNULL(cur_devengado,0) = 0";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_campo,
            $id_pago
          )
        );
        $message = 'CUR de devengado asignado';
        $observaciones = "{'message': '".$message."', 'titulo': 'devengado', 'valor': ".$id_campo."}";
        //fin
      }else{
        $yes = array('msg'=>'ERROR','message'=>'Este CUR ya existe.');
      }

    }
    if($id_tipo == 3){

      $sql0 = "UPDATE docto_ped_pago_presupuesto SET nro_liquidacion = ?, id_bitacora = ?, id_seguimiento = ?
               WHERE id_pago = ? AND ISNULL(nro_liquidacion,0) = 0";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $id_campo,
          2,
          12,
          $id_pago
        )
      );


      $message = 'Anexo agregado';
      $observaciones = "{'message': ".$message.", 'titulo': 'liquidacion', 'valor': ".$id_campo."}";
    }

    $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacora (id_bitacora,id_seguimiento,operador_por,operado_en,observaciones,persona_responsable,oficio_nro,oficio_fecha) VALUES (?,?,?,?,?,?,?,?)";
    $q10 = $pdo->prepare($sql10);
    $q10->execute(array(2,12,$creador,$creado_en,$observaciones,$persona,NULL,NULL));

    $cgid = $pdo->lastInsertId();
    $sql10 = "INSERT INTO DoctoPedPagoPresupuestoBitacoraDet (id_control,id_pago,reng_num,operado_en,operado_por)
      VALUES (?,?,?,?,?)";
    $q10 = $pdo->prepare($sql10);
    $q10->execute(
      array(
        $cgid,
        $id_pago,
        1,
        $creado_en,
        $creador
      )
    );



  $yes = array('msg'=>'OK','message'=>$message);

  $yes = array('success'=>true,'id_pago' => $id_pago,'msg'=>'Done','valor_nuevo'=>$id_campo);
  //echo json_encode($yes);
  $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','message'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','message'=>$e2);

    }
  }
  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
