<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';

  $data = $_POST["data"];
  $opcion  = $_POST["opcion"];
  $aut  = $_POST["aut"];
  $HORARIO = new Horario();
  $data[2] = (date("Y-m-d H:i:s", strtotime($data[2])));
  $data[3] = (date("Y-m-d H:i:s", strtotime($data[3])));
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if ($opcion == "1") {
    $sql = "INSERT INTO tbl_fechas_descansos (id_persona, id_catalogo, fecha_inicio, fecha_fin, id_persona_mod, fecha_mod, id_persona_ing, fecha_ing, observaciones, estado, autoriza, goce, nro_boleta) VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(),NULL, NULL, NULL, NULL)";
  } else if ($opcion == "2") {
    if (sizeof($data) == 8) {
      $sql = "INSERT INTO tbl_control_permisos (id_persona, id_catalogo, fecha_inicio, fecha_fin, id_persona_mod, fecha_mod, id_persona_ing, fecha_ing, observaciones, estado, autoriza, goce, nro_boleta) VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(), '" . $data[4] . "', ?, ?, ?, ?)";
    } else {
      $sql = "INSERT INTO tbl_control_permisos (id_persona, id_catalogo, fecha_inicio, fecha_fin, id_persona_mod, fecha_mod, id_persona_ing, fecha_ing, observaciones, estado, autoriza, goce, nro_boleta) VALUES(?,?,?,?,?,GETDATE(),?,GETDATE(), NULL, ?, ?, ?, ?)";
    }
  }
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute(array($data[0], $data[1], $data[2], $data[3], $_SESSION["id_persona"], $_SESSION["id_persona"], $aut, $data[5], $data[6], $data[7]))) {
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
  echo json_encode($response);
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}