<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';

  $month = $_POST['month'];
  $year = $_POST['year'];
  $HORARIO = new Horario();
  $empleados = $HORARIO::get_empleados($year, $month);
  $data = array();
  $priv = array();

  if (usuarioPrivilegiado()->hasPrivilege(299)) {
    // Control de Horarios
    array_push($priv, 0);
  }

  if (usuarioPrivilegiado()->hasPrivilege(310)) {
    // Cambiar Horario
    array_push($priv, 1);
  }

  if (usuarioPrivilegiado()->hasPrivilege(296)) {
    // Solicitar Permisos
    array_push($priv, 2);
  }

  if (usuarioPrivilegiado()->hasPrivilege(293)) {
    // Control de Permisos
    array_push($priv, 3);
  }

  set_time_limit(300);
  foreach ($empleados as $empleado) {
    $id_persona = $empleado['id_persona'];
    $counts = 0;
    $buttons = array('<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/empleado_horario.php?id_persona=' . $id_persona . '""><i class="fas fa-calendar-check" title="Ver Horario"></i></span>', '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/empleados/cambiar_horario.php?id_persona=' . $id_persona . '""><i class="fas fa-clock" title="Cambiar Horario"></i></span>', '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_boleta.php?id_persona=' . $id_persona . '"" title=""><i class="fas fa-file-alt" title="Generar Boleta"></i></span>', '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/boletas/empleado_e_boletas.php?id_persona=' . $id_persona . '"" title=""><i class="fas fa-hourglass-half" title="Estado de Boletas"></i></span>');


    $horarios = $HORARIO::get_tarde_by_empleado_1($id_persona, $month, $year);

    foreach ($horarios as $key) {
      // echo json_encode($key['tarde']);
      $counts = ($key['tarde'] == 1) ? $counts + 1 : $counts;
    }
    $tcounts = ($counts >= 3) ? 3 : $counts;
    $bt = "";
    foreach ($priv as $value) {
      $bt .= $buttons[$value];
    }
    $data[] = array(
      'id' => $id_persona,
      'foto' => "",
      'empleado' => $empleado['nombre'],
      'dfuncional' => $empleado['dir_funcional'],
      'dnominal' => $empleado['dir_nominal'],
      'pfuncional' => $empleado['p_funcional'],
      'pnominal' => $empleado['p_nominal'],
      'horario' => '',
      'tarde' => $counts,
      'ttarde' => $tcounts,
      'accion' => '<div class="btn-group">' . $bt  . '</div>'
    );
  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
