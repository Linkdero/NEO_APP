<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');
  setlocale(LC_TIME, 'es_ES');
  include_once '../../../../horarios/php/back/functions.php';
  include_once '../functions.php';
  set_time_limit(300);
  $array_meses = ['', "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  $id_persona = $_SESSION['id_persona'];
  $tipo = $_POST['tipo'];
  $fff1 = $_POST['fff1'];
  $fff2 = $_POST['fff2'];
  $BOLETA = new Boleta;
  $priv = 0;
  $solicitudes = array();
  $data = array();
  $e = $BOLETA->get_empleado_by_id_ficha($id_persona);

  $u = usuarioPrivilegiado();

  if ($u->hasPrivilege(295)) {
    $priv = 1;
  }
  if ($u->hasPrivilege(297)) {
    $priv = 2;
  }
  if ($u->hasPrivilege(298)) {
    $priv = 3;
  }
  if ($u->hasPrivilege(297) && $u->hasPrivilege(298)) {
    $priv = 4;
  }

  switch ($priv) {
    case 1:
      $solicitudes = $BOLETA->get_all_solicitudes($tipo, $e['id_direc'], $e['id_subsecre'], $e['id_secre'], $fff1, $fff2);
      break;
    case 2:
      $solicitudes = $BOLETA->get_all_solicitudes($tipo, $e['id_direc'], $e['id_subsecre'], $e['id_secre'], $fff1, $fff2);
      break;
    case 3:
      $solicitudes = $BOLETA->get_all_solicitudes($tipo, 0, 0, 1, $fff1, $fff2);
      break;
    case 4:
      $solicitudes = $BOLETA->get_all_solicitudes($tipo, 0, 0, 1, $fff1, $fff2);
      break;
  }
  foreach ($solicitudes as $s) {
    $vid = $s['vac_id'];
    $est_id = $s['est_id'];
    $est_des = $s['est_des'];
    $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="" disabled><i class="fa fa-check" title="Aprobar Solicitud"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="" disabled><i class="fa fa-times" title="Anular Solicitud"></i></button>' . '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span></div>';
    $estado = $BOLETA->set_estado_badge($est_id, $est_des);
    // echo $priv;
    // echo $est_id;
    if ($priv == 1 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="" disabled><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 2 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="aprobar_vacaciones(' . $vid . ', 2,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 3 && $est_id == 2) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="aprobar_vacaciones(' . $vid . ', 5,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="anular_vacaciones(' . $vid . ', 7,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 4 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="aprobar_vacaciones(' . $vid . ', 2,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 4 && $est_id == 2) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-outline-info" onclick="aprobar_vacaciones(' . $vid . ', 5,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-outline-info" onclick="anular_vacaciones(' . $vid . ', 7,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="far fa-calendar-check" title="Ver Solicitud"></i></span>' . '</div>';
    }
    $pendientes =  $BOLETA->dias_horas($s['vac_sol'], 2);
    $today = date('Y-m-d');
    $diares = ($s['vac_fch_ini'] < $today) ? (($s['diares']) . "<br><span class='btn btn-sm btn-outline-info' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/telefonos/detalle_telefonos.php?id_persona={$s['id_persona']}' data-toggle='tooltip' data-placement='top' title='Mostrar contactos'>
    <i class='fa fa-phone'></i>
    </span>") : "No ha iniciado vacaciones";
    $sub_array = array(
      'vac_id' => '<strong>'.$vid.'</strong>',
      'id_persona' => $s['id_persona'],
      'persona' =>$s['nombre'],
      'dir' => $s['dir_funcional'],
      // 'subsec' => $s['dir_funcional'],
      // 'sec' => $s['dir_funcional'],
      'sol' => fecha_dmy($s['vac_fch_sol']),
      'inicio' => fecha_dmy($s['vac_fch_ini']),
      'fin' => fecha_dmy($s['vac_fch_fin']),
      'dias' => $pendientes,
      'periodo' => $s['anio_des'],
      'mesini' => $array_meses[date("n", strtotime($s['vac_fch_ini']))],
      'mesfin' => $array_meses[date("n", strtotime($s['vac_fch_fin']))],
      'pre' => fecha_dmy($s['vac_fch_pre']),
      'diares' => $diares,
      'estado' => $estado,
      'accion' => $accion
    );
    $data[] = $sub_array;
  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);

else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
