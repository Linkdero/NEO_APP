<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $tipo = $_POST['name'];
  $id_contacto = $_POST['pk'];
  $valor = $_POST['value'];
  $usuario_mod = $_SESSION['id_persona'];
  //$tipo=$_POST['tipo'];

  $fecha_mod = date('Y-m-d H:i:s');

  $sql = '';
  if ($valor != '') :
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT numero FROM dir_detalle_contactos WHERE id_detalle = :id_detalle";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("id_detalle", $id_contacto);
    $stmt->execute();
    $ref = $stmt->fetch();

    $valor_anterior = array(
      "id_detalle" => $id_contacto,
      "numero" => $ref[0]
    );
    $valor_nuevo = array(
      "id_detalle" => $id_contacto,
      "numero" => $valor
    );
    $log = "VALUES(19, 1875, 'dir_detalle_contactos', '" . json_encode($valor_anterior) . "' ,'" . json_encode($valor_nuevo) . "' , " . $_SESSION["id_persona"] . ", GETDATE(), 3)";



    $sql = "UPDATE dir_detalle_contactos SET numero=?, id_usuario_mod=?, fecha_mod=? WHERE id_detalle=?
            INSERT INTO [SAAS_APP].[dbo].[tbl_log_crud]
            (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans)
            " . $log;

    $q = $pdo->prepare($sql);
    $q->execute(array(
      $valor,
      $usuario_mod,
      $fecha_mod,
      $id_contacto
    ));

    Database::disconnect_sqlsrv();
  endif;

else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
