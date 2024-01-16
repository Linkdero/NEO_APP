<?php

include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');

  $requisicion_id=$_POST['pk'];
  $producto_id=$_POST['name'];
  $cantidad=$_POST['value'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT cantidadSolicitada, productoStatus FROM APP_POS.dbo.RequisicionDetalle WHERE requisicionId=? AND productoId = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($requisicion_id, $producto_id));
  $valor_actual = $q->fetch();
  $yes = '';

  if($valor_actual['productoStatus'] == 1){
    $sql0="UPDATE APP_POS.dbo.RequisicionDetalle SET cantidadSolicitada = ? WHERE requisicionId=? AND productoId = ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($cantidad,$requisicion_id, $producto_id));

    $valor_anterior = array(
      'requisicionId'=>$requisicion_id,
      'productoId'=>$producto_id,
      'cantidad_actual'=>$valor_actual['cantidadSolicitada']
    );

    $valor_nuevo = array(
      'requisicionId'=>$requisicion_id,
      'productoId'=>$producto_id,
      'cantidad_nueva'=>$cantidad
    );

    $log = "VALUES(364, 8326, 'APP_POS.dbo.RequisicionDetalle', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $yes = array('success'=>true,'requisicionId' => $requisicion_id,'valor_actual'=>$valor_actual['cantidadSolicitada'],'msg'=>'Done','valor_nuevo'=>$cantidad);
    Database::disconnect_sqlsrv();

  }
  else{
    $yes = array('msg'=>'Error', 'message'=>'El producto no fue aprobado, por lo que no se puede actualizar la cantidad.');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
