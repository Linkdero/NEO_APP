<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  //$orden_id=$_POST['orden_id'];
  $insumos= (!empty($_POST['insumos'])) ? $_POST['insumos'] : NULL;

  $id_tipo_operacion = $_POST['id_tipo_operacion'];
  $factura_id = (!empty($_POST['factura_id'])) ? $_POST['factura_id'] : NULL;

  $id_tipo_ingreso = (!empty($_POST['id_tipo_ingreso'])) ? $_POST['id_tipo_ingreso'] : 1;
  $fecha_factura_ingreso= (!empty($_POST['fecha_factura_ingreso'])) ? date('Y-m-d', strtotime($_POST['fecha_factura_ingreso'])) : date('Y-m-d H:i:s');
  $fecha_factura=date('Y-m-d', strtotime($_POST['fecha_factura']));
  $factura_serie=(!empty($_POST['factura_serie'])) ? $_POST['factura_serie'] : NULL;
  $factura_nro=(!empty($_POST['factura_nro'])) ? $_POST['factura_nro'] : NULL;
  $factura_monto=(!empty($_POST['factura_monto'])) ? floatval($_POST['factura_monto']) : NULL;
  $id_nog=(!empty($_POST['id_nog']))?$_POST['id_nog']:NULL;
  $proveedor=(!empty($_POST['id_proveedor'])) ? $_POST['id_proveedor'] : NULL;
  $id_modalidad = (!empty($_POST['id_modalidad'])) ? $_POST['id_modalidad'] : NULL;
  $pedido_interno=(!empty($_POST['pedido_interno']))?$_POST['pedido_interno']:NULL;
  $regimen = (!empty($_POST['id_regimen'])) ? $_POST['id_regimen'] : NULL;
  $id_renglon = (!empty($_POST['id_renglon'])) ? $_POST['id_renglon'] : NULL;
  $password = (!empty($_POST['password'])) ? $_POST['password'] : NULL;

  $rdb_value = (!empty($_POST['pago_opcional'])) ? $_POST['pago_opcional'] : NULL;


  //$uni_cor = documento::get_unidad_pos($unidad);
  $clased = new documento;
  $privilegio = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);

  $tipo_pago = ($privilegio[13]['flag_insertar'] == 1) ? 1 : NULL;

  $compras_recepcion = ($privilegio[2]['flag_insertar'] == 1) ? 1 : 0;
  $tecnico_compras = ($privilegio[2]['flag_actualizar'] == 1) ? 1 : 0;

  $p_i = $clased->get_pedido_by_pedido_interno($pedido_interno,date('Y'));
  $ped_tra = (!empty($p_i['Ped_tra']))?$p_i['Ped_tra']:NULL;

  $reng_actual = 1;

  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($id_tipo_operacion == 1){
      //inicio
      $sql0 = "INSERT INTO docto_ped_pago (status, asignado_en, asignado_por, reng_num, id_status, factura_fecha, factura_serie,
               factura_num, factura_total,proveedor_id,ped_nog, ped_tra, pedido_interno,regimen_proveedor,id_renglon,id_tipo_ingreso,password,modalidad_pago,opcion_simplificado)
      VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          1,
          $fecha_factura_ingreso,//date('Y-m-d H:i:s'),
          $creador,
          $reng_actual,
          8337,
          $fecha_factura,
          $factura_serie,
          $factura_nro,
          $factura_monto,
          $proveedor,
          $id_nog,
          $ped_tra,
          $pedido_interno,
          $regimen,
          $id_renglon,
          $id_tipo_ingreso,
          $password,
          $tipo_pago,
          $rdb_value
        )
      );

      $cgid = $pdo->lastInsertId();

      if($id_modalidad == 1 || $id_modalidad == 3){
        $sql0 = "UPDATE docto_ped_pago SET tipo_pago=?
                 WHERE orden_compra_id=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_modalidad,
            $cgid
          )
        );
      }else if($id_modalidad == 2){
        $sql0 = "UPDATE docto_ped_pago SET tipo_pago=?, modalidad_pago = ?
                 WHERE orden_compra_id=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(
          array(
            $id_modalidad,
            $id_modalidad,
            $cgid
          )
        );
      }

      if($id_tipo_ingreso == 1){
        $clased->insert_bitacora_factura($cgid, 8337,NULL, 'Registro de Factura',1,NULL,NULL);
        if($tecnico_compras == 1 && $compras_recepcion == 0){
          $reng_actual = 1;
          $sql0 = "INSERT INTO docto_ped_pago_asignado (orden_compra_id,id_persona, reng_num, status, asignado_en, asignado_por)
                   VALUES (?,?,?,?,?,?)";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(
            array(
              $cgid,
              $creador,
              $reng_actual,
              1,
              date('Y-m-d H:i:s'),
              $creador
            )
          );

        }
      }else{

      }

      $ped_tra=0;
      $x = 0;
      if(!empty($insumos)){
        //inicio
        foreach($insumos AS $i){
          $x ++;
          $ped_tra = $cgid;
          //echo $i['v_rec']+$i['Pedd_canf'].' |-| ';
          $ped_tra = (!empty($i['Ped_tra'])) ? $i['Ped_tra'] : NULL;
          $sql2 = "INSERT INTO docto_ped_pago_detalle (orden_compra_id, reng_num, Ppr_id, Ppr_can, Ppr_status, Ppr_costo, Ped_tra)
          values(?,?,?,?,?,?,?)";
          $q2 = $pdo->prepare($sql2);
          $q2->execute(array($cgid, $x, $i['Ppr_id'],$i['cantidad'],1,$i['precio_unitario'],$ped_tra));

          /*$sql3 = "UPDATE APP_POS.dbo.PEDIDO_D SET Pedd_canf = ? WHERE Ped_tra=? AND Ppr_id = ?";
          $q3 = $pdo->prepare($sql3);
          $q3->execute(array(($i['v_rec']+$i['Pedd_canf']),$ped_tra, $i['Ppr_id']));*/

        }
        //fin
      }


      /*$sql0 = "UPDATE docto_ped_pago SET status=?, asignado_en=?, asignado_por=?, ped_tra=?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          1,
          date('Y-m-d H:i:s'),
          $creador,
          $ped_tra,
          $cgid
        )
      );*/

      //$fac_id, $tipo, $persona_id, $observaciones,$estado,$tipo_detalle,$id_direccion


    $yes = array('msg'=>'OK','id'=>'', 'message'=> 'Factura registrada');
      //fin
    }else if ($id_tipo_operacion == 2){
      //inicio
      $sql0 = "UPDATE docto_ped_pago SET factura_serie=?, factura_num = ?, factura_fecha = ?
               WHERE orden_compra_id=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(
        array(
          $factura_serie,
          $factura_nro,
          $fecha_factura,
          $factura_id
        )
      );
      $clased->insert_bitacora_factura($factura_id, 8337,NULL, 'Registro de Factura',1,NULL,NULL);
      $yes = array('msg'=>'OK','id'=>'', 'message'=> 'Datos agregados a la factura');
      //fin
    }



     $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }
  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
