<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';

  $id_persona = $_POST['id_persona'];
  $inicio = $_POST['inicio'];
  $fin = $_POST['fin'];
  $entrada = $_POST['entrada'];
  $salida = $_POST['salida'];
  $fijo = $_POST['fijo'];
  $turno = $_POST['turno'];
  $sturno = $_POST['sturno'];
  // $wks = json_encode($_POST['wks']);


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $turno = ($turno == "true") ? 1 : 0;
  $sturno = ($sturno == "true") ? 1 : 0;
  if ($fijo == "1") {
    // echo 1;
    $sql = "INSERT INTO tbl_persona_horario (id_persona, id_horario, flag_fijo, fecha_ini, estado) VALUES(?, ?, ?, ?, 1)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array($id_persona, $entrada, 1, $inicio))) {
      $response =  array(
        "status" => 200,
        "msg" => ""
      );
    } else {
      $response =  array(
        "status" => 400,
        "msg" => "error"
      );
    }
    Database::disconnect_sqlsrv();
  } else if ($turno == 1 || $sturno == 1) {
    $sql = "INSERT INTO [SAAS_APP].[dbo].[tbl_control_permisos]
    (id_persona, id_catalogo, fecha_inicio, fecha_fin, id_persona_mod, fecha_mod, id_persona_ing, fecha_ing, observaciones, estado, goce, autoriza, nro_boleta) 
    VALUES(?, ? ,CAST(? AS DATE) ,CAST(? AS DATE) ,?, GETDATE(), ?, GETDATE(), NULL, 5, NULL, NULL, NULL)";
    $stmt = $pdo->prepare($sql);
    $turnoid = ($turno == 1) ? 63 : 64;

    if ($stmt->execute(array($id_persona, $turnoid, $inicio, $fin, $_SESSION["id_persona"], $_SESSION["id_persona"]))) {
      $response =  array(
        "status" => 200,
        "msg" => ""
      );
    } else {
      $response =  array(
        "status" => 400,
        "msg" => "error"
      );
    }
    Database::disconnect_sqlsrv();
  } else {
    // echo 3;
    $sql = "INSERT INTO tbl_persona_horario (id_persona, id_horario, flag_fijo, fecha_ini, fecha_fin, hora_ini, hora_fin, dia, estado) VALUES(?, ?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $pdo->prepare($sql);
    // echo $sql;
    if ($stmt->execute(array($id_persona, 0, 0, $inicio, $fin, $entrada, $salida, NULL))) {
      $response =  array(
        "status" => 200,
        "msg" => ""
      );
    } else {
      $response =  array(
        "status" => 400,
        "msg" => "error"
      );
    }
    Database::disconnect_sqlsrv();
  }
  echo json_encode($response);
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
