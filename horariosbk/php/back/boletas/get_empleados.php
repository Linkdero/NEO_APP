<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  $id_persona =  $_SESSION['id_persona'];
  $tipo = $_POST['tipo'];
  $BOLETA = new Boleta();
  $HORARIO = new Horario();
  $persona = $HORARIO->get_name($id_persona);

  $data = array();
  if (usuarioPrivilegiado()->hasPrivilege(297) && usuarioPrivilegiado()->hasPrivilege(298)) {
    $empleados = $BOLETA->get_empleados_1_0($tipo);
    foreach ($empleados as $empleado) {
      $vacaciones = $BOLETA->get_vacaciones_pendientes($empleado['id_persona']);
      $utilizados = $BOLETA->get_vacaciones_utilizadas($empleado['id_persona']);
      $pendientes = "";
      $tdias = 0;
      $thoras = 0;
      $menu = '<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(' . $empleado['id_persona'] . ')"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu' . $empleado['id_persona'] . '"></div></div></div></div>';

      foreach ($vacaciones as $vacacion) {
        $d = $vacacion['dia_asi'] - $vacacion['dia_goz'];
        $h = ceil(fmod($d, 1) * 8);
        $d = floor($d);
        $tdias += $d;
        $thoras += $h;
        // $h = ($h == 0) ? "" : $h . " horas";
        // $d = ($d == 0) ? "" : $d . " días ";
        $pendientes = $pendientes . "<b>" . $vacacion['anio_des'] . "</b>" . " : " . $d . " \r\n<br>";
      }
      $data[] = array(
        'id' => $empleado['id_persona'],
        'empleado' => $empleado['nombre'],
        'dfuncional' => $empleado['dir_general'],
        'pfuncional' => is_null($empleado['p_contrato']) ? $empleado['p_funcional'] : $empleado['p_contrato'],
        'pendientes' => $pendientes . "<b>Total: " . $tdias .  " </b>",
        'utilizados' => round($utilizados['dutz']),
        // 'accion' => '<div class="btn-group">' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/empleado_horario.php?id_persona=' . $empleado['id_persona'] . '""><i class="fas fa-calendar" title="Ver Horarios"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_vacaciones.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fa fa-certificate" title="Certificación de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_periodo.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-calendar-check" title="Estado de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_e_boletas.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-hourglass-half" title="Estado de Boletas"></i></span></div>'
        'accion' => '<div class="btn-group">' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/empleado_horario.php?id_persona=' . $empleado['id_persona'] . '""><i class="fas fa-calendar" title="Ver Horarios"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_vacaciones.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fa fa-certificate" title="Certificación de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_periodo.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-calendar-check" title="Estado de Vacaciones"></i></span>' .  $menu . '</div>'
      );
    }
  } else {
    $empleados = $BOLETA->get_empleados($persona['id_direc']);
    foreach ($empleados as $empleado) {
      $vacaciones = $BOLETA->get_vacaciones_pendientes($empleado['id_persona']);
      $utilizados = $BOLETA->get_vacaciones_utilizadas($empleado['id_persona']);
      $pendientes = "";
      $tdias = 0;
      $thoras = 0;
      $menu = '<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(' . $empleado['id_persona'] . ')"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu' . $empleado['id_persona'] . '"></div></div></div></div>';

      foreach ($vacaciones as $vacacion) {
        $d = $vacacion['dia_asi'] - $vacacion['dia_goz'];
        $h = ceil(fmod($d, 1) * 8);
        $d = floor($d);
        $tdias += $d;
        $thoras += $h;
        // $h = ($h == 0) ? "" : $h . " horas";
        // $d = ($d == 0) ? "" : $d . " días ";
        $pendientes = $pendientes . "<b>" . $vacacion['anio_des'] . "</b>" . " : " . $d .  " \r\n<br>";
      }
      $data[] = array(
        'id' => $empleado['id_persona'],
        'empleado' => $empleado['nombre'],
        'dfuncional' => $empleado['dir_general'],
        'pfuncional' => is_null($empleado['p_contrato']) ? $empleado['p_funcional'] : $empleado['p_contrato'],
        'pendientes' => $pendientes . "<b>Total: " . $tdias . " </b>",
        'utilizados' => round($utilizados['dutz']),
        // 'accion' => '<div class="btn-group">' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/empleado_horario.php?id_persona=' . $empleado['id_persona'] . '""><i class="fas fa-calendar" title="Ver Horarios"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_vacaciones.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fa fa-certificate" title="Certificación de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_periodo.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-calendar-check" title="Estado de Vacaciones"></i></span>' .  $menu . '</div>'
        'accion' => '<div class="btn-group">' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/empleado_horario.php?id_persona=' . $empleado['id_persona'] . '""><i class="fas fa-calendar" title="Ver Horarios"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_vacaciones.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fa fa-certificate" title="Certificación de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_periodo.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-calendar-check" title="Estado de Vacaciones"></i></span>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/empleado_e_boletas.php?id_persona=' . $empleado['id_persona'] . '"" title=""><i class="fas fa-hourglass-half" title="Estado de Boletas"></i></span></div>'
      );
    }
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
