<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $pk = $_POST['pk'];
  $value = $_POST['value'];
  $name = $_POST['name'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT numero_estante FROM [SAAS_APP].[dbo].[inv_producto_insumo_detalle] WHERE numero_serie = :numero_serie";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam("numero_serie", $pk);
  $stmt->execute();
  $ref = $stmt->fetch();
  $valor_anterior = array(
    "numero_serie" => $pk,
    "numero_estante" => $ref[0]
  );
  $valor_nuevo = array(
    "numero_serie" => $pk,
    "numero_estante" => $value
  );
  $log = "VALUES(199, 3549, 'inv_producto_insumo_detalle', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";
  $sql = "UPDATE [SAAS_APP].[dbo].[inv_producto_insumo_detalle] SET numero_estante = ? WHERE numero_serie = ? 
  INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
  (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
  " . $log;
  $stmt = $pdo->prepare($sql);
  $response = $stmt->execute(array($value, $pk));
  Database::disconnect_sqlsrv();


else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
