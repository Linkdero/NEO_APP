<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) :
  date_default_timezone_set('America/Guatemala');
  $ped_tra = $_POST['ped_tra'];
  $ped_num = $_POST['ped_num'];
  $id_persona = $_POST['id_persona'];
  $obs = $_POST['obs'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $valor_anterior = array(
    "Ped_tra" => $ped_tra,
    "Ped_num" => $ped_num,
    "Ped_status" => NULL
  );
  $valor_nuevo = array(
    "Ped_tra" => $ped_tra,
    "Ped_num" => $ped_num,
    "Ped_status" => 8170
  );

  $log = "VALUES(301, 8017, '[APP_POS].[dbo].[PEDIDO_E]', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $id_persona . ", GETDATE(), 4);";
  $sql = "INSERT INTO [SAAS_APP].[dbo].[docto_pedido_seguimiento_bitacora] 
          (ped_tra, persona_id, ped_seguimiento_fecha, ped_observaciones, ped_tipo_seguimiento_status, ped_tipo_seguimiento_id, ped_reng_num)
          VALUES(?, ?, GETDATE(), ?, 1, 8170, 1);
          UPDATE APP_POS.dbo.PEDIDO_E SET Ped_status=8170 WHERE Ped_tra= ?;
          INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
          (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
          " . $log;

  $stmt = $pdo->prepare($sql);
  if ($stmt->execute(array($ped_tra, $id_persona, $obs, $ped_tra))) {
    echo json_encode(array(
      "status" => 201,
      "msg" => "OK"
    ));
  } else {
    echo json_encode(array(
      "status" => 200,
      "msg" => "ERROR"
    ));
  }



  Database::disconnect_sqlsrv();
else :
  header("Location: ../index.php");
endif;
