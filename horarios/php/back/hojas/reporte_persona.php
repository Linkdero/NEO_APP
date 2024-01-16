<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';
    $id_persona = $_POST['id_persona'];
    $data = [];
    $data = array(
    );

    echo json_encode($data);
  }
}
