<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) {
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

  $opcion = 2;
  $dir = $_POST['dir'];
  $array_solicitudes = array();
  $horario = new Horario();
  $direc = $horario->get_name($_SESSION['id_persona']);

  if (usuarioPrivilegiado()->hasPrivilege(298)) {
    $array_solicitudes = $horario->get_vacaciones($dir);
  } else if (usuarioPrivilegiado()->hasPrivilege(297)) {
    $array_solicitudes = $horario->get_vacaciones_dir($direc['id_direc']);
  }

  $data = array();
  if ($opcion == 1) {
    foreach ($array_solicitudes as $value) {
      $data[] = array(
        "salon" => $value["emp_id"],
        "motivo" => $value["emp_dir"],
        "solicitante" => $value["anio_id"],
        "inicio" => $value["vac_fch_ini"],
        "fin" => $value["vac_fch_fin"],
        "audiovisuales" => $value["vac_sol"],
        "mobiliario" => $value["vac_pen"],
        "estado" => $value["est_id"]
      );
    }

    $response = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );

    echo json_encode($response);
  } else if ($opcion == 2) {

    foreach ($array_solicitudes as $value) {
      $color  = "";
      $badge = "";
      if ($value['est_id'] == 0) {
        $color = '#fb4143';
        $badge = '<span class="badge badge-danger">Rechazado</span>';
      } else if ($value['est_id'] == 1) {
        $color = '#fab633';
        $badge = '<span class="badge badge-warning">Pendiente</span>';
      } else if ($value['est_id'] == 2) {
        $color = '#0dd157';
        $badge = '<span class="badge badge-success">Aprobado</span>';
      } else {
        $color = '#0000FF';
        $badge = '<span class="badge badge-success">Vacaciones</span>';
      }


      $data[] = array(
        "title" => $value["nombre"],
        "start" => $value["vac_fch_ini"],
        "end" => $value["vac_fch_pre"],
        "color" => '#28a745',
        "textColor" => 'white',
        // "motivo" => $value["anio_id"],
        // "solicitante" => $value["emp_dir"],
        // "audiovisuales" => $value["vac_sol"],
        // "mobiliario" => $value["vac_pen"],
        // "estado" => $value["est_id"],
        // "nombre" => $value["emp_id"],
        // "inicio" => $value["vac_fch_ini"],
        // "fin" => $value["vac_fch_fin"],
        "badge" => $badge,
        "vac_id" => $value["vac_id"]
      );
    }
    echo json_encode($data);
  }
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
