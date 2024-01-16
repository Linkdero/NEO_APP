<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $nombre=$_POST['nombre'];

  $titulo=$_POST['titulo'];
  $doc_interno=($_POST['doc_interno']==1)?1:2;
  $fecha_doc=$_POST['fecha_documento'];
  $categoria=$_POST['categoria'];

  $respuesta=($categoria==8047 || $categoria==8048 || $categoria==8049)?2:$_POST['respuesta'];
  $correlativo=$_POST['correlativo_res'];
  $emision_recibido=$_POST['emision_recibido'];

  $destinatarios=(!empty($_POST['destinatarios']))?$_POST['destinatarios']:'';
  $destinatarios_cc=(!empty($_POST['destinatarios_cc']))?$_POST['destinatarios_cc']:'';

  $clase = new empleado;
  $clased = new documento;
  $e = $clase->get_empleado_by_id_ficha($creador);

  //obtiene el correlativo actual

  $correlativo_actual=1;
  $depto=(!empty($e['id_departamento']))?$e['id_departamento']:0;
  $ca=$clased->genera_correlativo_documento($e['id_dirf'],$depto,$categoria,$emision_recibido);
  if(!empty($ca['id'])){
    $correlativo_actual=$ca['id']+1;
  }

  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO docto_encabezado (docto_titulo,docto_descripcion,docto_categoria,docto_direccion_id,docto_fecha,docto_correlativo,docto_year,docto_creador,docto_fechacreacion,docto_status,docto_depto_id,docto_tipo_emision,docto_respuesta,docto_nombre,docto_correlativo_externo)
                         VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($titulo,'',$categoria,$e['id_dirf'],$fecha_doc,$correlativo_actual,date('Y'),$creador,date('Y-m-d H:i:s'),0,0,$emision_recibido,$respuesta,$nombre,$correlativo));

    //$cg=$clased->get_correlativo_generado($creador);
    $cgid = $pdo->lastInsertId();

    if($categoria!=8047 || $categoria!=8048 || $categoria!=8049){
      if(!empty($destinatarios)){
        foreach ($destinatarios as $c) {
          $sql2 = "INSERT INTO docto_destinatario (docto_id,direccion_id,tipo,status) values(?,?,?,?)";
          $q2 = $pdo->prepare($sql2);
          $q2->execute(array($cgid,$c,1,1));
        }
      }

      if(!empty($destinatarios_cc)){
        foreach ($destinatarios_cc as $c) {
          $sql3 = "INSERT INTO docto_destinatario (docto_id,direccion_id,tipo,status) values(?,?,?,?)";
          $q3 = $pdo->prepare($sql3);
          $q3->execute(array($cgid,$c,2,1));
        }
      }
    }

    if($categoria==8048 || $categoria==8049){
      //indice bases
      $bases=get_items(143);
      if($bases["status"] == 200){
        foreach($bases["data"] as $bd){
          $sql0 = "INSERT INTO docto_base (docto_id,base_id)
                               VALUES(?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($cgid,$bd['id_item']));
        }
      }

      //agrega cronograma
      $bases_detalle=get_items(144);
      if($bases_detalle["status"] == 200){
        foreach($bases_detalle["data"] as $bd){
          if($categoria==8048 && $bd['id_item']==8068){
          }else{
            $sql0 = "INSERT INTO docto_base_detalle (docto_id,base_id,base_detalle_id)
                                 VALUES(?,?,?)";
            $q0 = $pdo->prepare($sql0);
            $q0->execute(array($cgid,144,$bd['id_item']));
          }


        }
      }


      //agrega criterios
      $bases_criterio=get_items(145);
      if($bases_criterio["status"] == 200){
        foreach($bases_criterio["data"] as $bd){
          $sql0 = "INSERT INTO docto_base_detalle (docto_id,base_id,base_detalle_id)
                               VALUES(?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($cgid,145,$bd['id_item']));
        }
      }

      //condiciones del contrato
      $condiciones=get_items(146);
      if($condiciones["status"] == 200){
        foreach($condiciones["data"] as $bd){
          $sql0 = "INSERT INTO docto_base_detalle (docto_id,base_id,base_detalle_id)
                               VALUES(?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($cgid,146,$bd['id_item']));
        }
      }

      //condiciones economicas y financieras
      $condiciones_ef=get_items(147);
      if($condiciones_ef["status"] == 200){
        foreach($condiciones_ef["data"] as $bd){
          $sql0 = "INSERT INTO docto_base_detalle (docto_id,base_id,base_detalle_id,status)
                               VALUES(?,?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($cgid,147,$bd['id_item'],0));
        }
      }

      //numerales
      //condiciones economicas y financieras
      $literales=$clased->get_base_literales();
      if($literales["status"] == 200){
        foreach($literales["data"] as $l){
          $sql0 = "INSERT INTO docto_base_literal_asignacion (docto_id,base_id,base_literal_id)
                               VALUES(?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($cgid,$l['base_id'],$l['base_literal_id']));
        }
      }
    }


    $yes = array('msg'=>'OK','id'=>$cgid);

    $pdo->commit();
    echo json_encode($yes);
  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);
      echo json_encode($yes);
    }
  }

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
