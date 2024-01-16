<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';

  $id = $_POST['id'];
  $BOLETA = new Boleta();
  $HORARIO = new Horario();
  if (usuarioPrivilegiado()->hasPrivilege(297)) {
    // Autorizar Direccion
    $priv = 0;
  } else if (usuarioPrivilegiado()->hasPrivilege(298)) {
    // Autorizar RRHH
    $priv = 1;
  } else {
    // disabled
    $priv = 2;
  }
  if (usuarioPrivilegiado()->hasPrivilege(313)) {
    $tipo = 1;
  }
  if (usuarioPrivilegiado()->hasPrivilege(312)) {
    $tipo = 2;
  }
  switch ($id) {
    case 0:
      $id_persona = $_POST['id_persona'];
      $empleados = $BOLETA->get_vacaciones_periodos($id_persona);

      $data = array();
      foreach ($empleados as $empleado) {
        $est_id = $empleado['est_id'];
        $est_des = $empleado['est_des'];
        $estado = $BOLETA->set_estado_badge($est_id, $est_des);
        $data[] = array(
          'id' => $empleado['vac_id'],
          'year' => $empleado['anio_des'],
          // 'ftran'=>$empleado['vac_fch_tra'],
          // 'fsol'=>$empleado['vac_fch_sol'],
          'fini' => $empleado['vac_fch_ini'],
          'ffin' => $empleado['vac_fch_fin'],
          'fpre' => $empleado['vac_fch_pre'],
          // 'vac_dia'=>floor($empleado['vac_dia']),
          // 'vac_dia_goz'=>floor($empleado['vac_dia_goz']),
          // 'vac_sub'=>floor($empleado['vac_sub']),
          'vac_sol' => $BOLETA->dias_horas($empleado['vac_sol'], 0),
          'vac_pen' => $BOLETA->dias_horas($empleado['vac_pen'], 0),
          // 'vac_obs'=>$empleado['vac_obs'],
          'est_des' => $estado,
          // 'vac_obs_anula'=>$empleado['vac_obs_anula'],
        );
      }
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
      );
      echo json_encode($results);
      break;
    case 1:
      $id_persona = $_POST['id_persona'];
      $empleados = $BOLETA->get_dias_asignados($id_persona);
      $pendientes = "";
      $data = array();
      foreach ($empleados as $empleado) {
        $d = $empleado['dia_asi'] - $empleado['dia_goz'];
        $pendientes =  $BOLETA->dias_horas($d, 0);

        $estado = '';

        if ($empleado['dia_est'] == 0 && empty($empleado['dia_liq'])){
          $estado = '<span class="text-success"><i class="fa fa-check-circle"></i> Vacaciones Gozadas</span>';
        }else if($empleado['dia_est'] == 1 && empty($empleado['dia_liq'])){
          $estado = '<span class="text-info"><i class="fa fa-check-circle"></i> Vacaciones Pendientes</span>';
        }else if($empleado['dia_est'] == 1 && $empleado['dia_liq'] == 1) {
          $estado = '<span class="text-secondary">Vacaciones Liquidadas</span>';
        }

        $data[] = array(
          'year' => $empleado['anio_des'],
          'dia_asi' => $BOLETA->dias_horas($empleado['dia_asi'], 0),
          'dia_goz' => $BOLETA->dias_horas($empleado['dia_goz'], 0),
          'dia_pen' => $pendientes,
          'dia_est' => $estado,
          'id_persona' => $id_persona,
          'nombre' => $empleado['nombre'],
          'p_nominal' => $empleado['p_nominal'],
          'dir_general' => $empleado['dir_general'],
          'fecha_ingreso' => $empleado['fecha_ingreso'],

        );
      }
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
      );
      echo json_encode($results);
      break;
    case 2:
      $id_persona = $_POST['id_persona'];
      $empleados = $BOLETA->get_boletas_empleado($id_persona, $tipo);
      $data = array();
      foreach ($empleados as $empleado) {
        $est_id = $empleado['est_id'];
        $est_des = $empleado['est_des'];
        $autoriza = $HORARIO->get_name($empleado['autoriza']);
        $estado = $BOLETA->set_estado_badge($est_id, $est_des);



        $accion =    array(
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_boleta(' . $empleado['id_control'] . ', 2)"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_boleta(' . $empleado['id_control'] . ', 4)"><i class="fa fa-times"></i></button></div>',
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_boleta(' . $empleado['id_control'] . ', 5)"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_boleta(' . $empleado['id_control'] . ', 7)"><i class="fa fa-times"></i></button></div>',
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-times"></i></button></div>'
        );
        // if ($est_id > 2) {
        //   $x = $accion[2];
        // } else {
        //   $x = $accion[$priv];
        // }
        $x = $accion[1];
        $data[] = array(
          'id' => $empleado['id_control'],
          'nro_boleta' => ($empleado['nro_boleta'] == '0') ? "" : $empleado['nro_boleta'],
          'motivo' => $empleado['motivo'],
          'fini' => date("d-m-Y", strtotime($empleado['fecha_inicio'])),
          'ffin' => date("d-m-Y", strtotime($empleado['fecha_fin'])),
          'observaciones' => $empleado['observaciones'],
          'autoriza' => $autoriza['nombre'],
          'est_des' => $estado,
          'est_id' => $empleado['est_id'],
          'accion' => $x
        );
      }
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
      );
      echo json_encode($results);
      break;
    case 3:
      $month = $_POST['month'];
      $year = $_POST['year'];

      $total_descansos = $HORARIO->get_empleado_permiso(0, $tipo, $month, $year);
      $data = array();

      foreach ($total_descansos as $descanso) {
        $estado = $BOLETA->set_estado_badge($descanso['est_id'], $descanso['est_des']);
        $id_persona = $HORARIO->get_name($descanso['id_persona']);
        $autoriza = $HORARIO->get_name($descanso['autoriza']);

        $accion =    array(
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_boleta(' . $descanso['id_control'] . ', 2)"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_boleta(' . $descanso['id_control'] . ', 4)"><i class="fa fa-times"></i></button></div>',
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_boleta(' . $descanso['id_control'] . ', 5)"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_boleta(' . $descanso['id_control'] . ', 7)"><i class="fa fa-times"></i></button></div>',
          '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-times"></i></button></div>'
        );
        $inicio = date("d-m-Y", strtotime($descanso['fecha_inicio']));
        $fin = date("d-m-Y", strtotime($descanso['fecha_fin']));
        $data[] = array(
          'id' => $descanso['id_control'],
          'persona' => $id_persona['nombre'],
          'motivo' => $descanso['nombre'],
          'fini' => $inicio,
          'ffin' => $fin,
          'nro_boleta' => ($descanso['nro_boleta'] == '0') ? "" : $descanso['nro_boleta'],
          'goce' => $descanso['goce'],
          'autoriza' => $autoriza['nombre'],
          'observaciones' => $descanso['observaciones'],
          'est_des' => $estado,
          'accion' => $accion[$priv]
        );
      }
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
      );
      echo json_encode($results);
      break;
  }
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
