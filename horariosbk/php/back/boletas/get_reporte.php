<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  date_default_timezone_set('America/Guatemala');
  //setlocale(LC_TIME, 'es_ES');
  //include_once '../../../../horarios/php/back/functions.php';
  include_once '../functions.php';
  function get_reporte_solicitudes($tipo, $dir, $subsec, $sec, $fff1, $fff2)
  {

      if (!is_numeric($tipo) == 1) {
          $tipo = 3;
      }
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT DISTINCT V.vac_id,
                              F.id_persona,
                              V.emp_pue,
                              CD.descripcion AS pue_des,
                              F.nombre,
                              F.dir_funcional,
                              V.emp_dir,
                              D.descripcion AS dir_des,
                              V.vac_fch_sol,
                              V.vac_fch_ini,
                              V.vac_fch_fin,
                              V.vac_sol,
                              V.vac_fch_pre,
                              A.anio_des,
                              EV.est_id,
                              EV.est_des,
      CASE WHEN DATEDIFF(day, GETDATE(), V.vac_fch_pre) >=1 THEN
  datediff(dd, GETDATE(), V.vac_fch_pre) - (datediff(wk, GETDATE(), V.vac_fch_pre) * 2) -
  case when datepart(dw, GETDATE()) = 1 then 1 else 0 end +
  case when datepart(dw, V.vac_fch_pre) = 1 then 1 else 0 end
  ELSE 0 END AS diares
      FROM [app_vacaciones].[dbo].[VACACIONES] V
      INNER JOIN [app_vacaciones].[dbo].[ANIO] A ON A.anio_id = V.anio_id
      INNER JOIN [app_vacaciones].[dbo].[ESTADO_VACACIONES] EV ON EV.est_id = V.est_id
      INNER JOIN [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA ON DA.dia_id = V.dia_id
      INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON V.emp_id = F.id_persona
  INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON V.emp_dir=D.id_direccion
  INNER JOIN [SAAS_APP].[dbo].[tbl_catalogo_detalle] CD ON V.emp_pue=CD.id_item
  WHERE CONVERT(VARCHAR, V.vac_fch_ini, 23) BETWEEN ? AND ?";

      if ($dir != 0) {
          $sql .= " AND F.id_direc =" . $dir;
      } else if ($subsec != 0) {
          $sql .= " AND F.id_direc = 0 AND F.id_subsecre =" . $subsec;
      } else if ($sec != 1) {
          $sql .= " AND F.id_direc = 0 AND F.id_subsecre = 0 AND F.id_subsecre = 4";
      } else {
          $sql .= "";
      }
      $sql .= " ORDER BY V.vac_id DESC";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($fff1,$fff2));
      $vacaciones = $stmt->fetchAll();
      Database::disconnect_sqlsrv();
      return $vacaciones;
  }
  set_time_limit(0);
  $array_meses = ['', "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  $id_persona = $_SESSION['id_persona'];
  $tipo = $_POST['tipo'];
  $fff1 = date('Y-m-d', strtotime($_POST['f_ini']));
  $fff2 = date('Y-m-d', strtotime($_POST['f_fin']));
  $BOLETA = new Boleta;
  $priv = 0;
  $solicitudes = array();
  $data = array();
  $e = $BOLETA->get_empleado_by_id_ficha($id_persona);

  if (usuarioPrivilegiado()->hasPrivilege(295)) {
    $priv = 1;
  }
  if (usuarioPrivilegiado()->hasPrivilege(297)) {
    $priv = 2;
  }
  if (usuarioPrivilegiado()->hasPrivilege(298)) {
    $priv = 3;
  }
  if (usuarioPrivilegiado()->hasPrivilege(297) && usuarioPrivilegiado()->hasPrivilege(298)) {
    $priv = 4;
  }

  switch ($priv) {
    case 1:
      $solicitudes = get_reporte_solicitudes($tipo, $e['id_direc'], $e['id_subsecre'], $e['id_secre'], $fff1, $fff2);
      break;
    case 2:
      $solicitudes = get_reporte_solicitudes($tipo, $e['id_direc'], $e['id_subsecre'], $e['id_secre'], $fff1, $fff2);
      break;
    case 3:
      $solicitudes = get_reporte_solicitudes($tipo, 0, 0, 1, $fff1, $fff2);
      break;
    case 4:
      $solicitudes = get_reporte_solicitudes($tipo, 0, 0, 1, $fff1, $fff2);
      break;
  }
  foreach ($solicitudes as $s) {
    $vid = $s['vac_id'];
    $est_id = $s['est_id'];
    $est_des = $s['est_des'];
    $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-check" title="Aprobar Solicitud"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-times" title="Anular Solicitud"></i></button>' . '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span></div>';
    $estado = $BOLETA->set_estado_badge($est_id, $est_des);
    // echo $priv;
    // echo $est_id;
    /*if ($priv == 1 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="" disabled><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 2 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_vacaciones(' . $vid . ', 2,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 3 && $est_id == 2) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_vacaciones(' . $vid . ', 5,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_vacaciones(' . $vid . ', 7,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 4 && $est_id == 1) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_vacaciones(' . $vid . ', 2,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_vacaciones(' . $vid . ', 4,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span>' . '</div>';
    } else if ($priv == 4 && $est_id == 2) {
      $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_vacaciones(' . $vid . ', 5,' . $tipo . ')"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_vacaciones(' . $vid . ', 7,' . $tipo . ')"><i class="fa fa-times"></i></button>' .  '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/boletas/ver_solicitud.php?vac_id=' . $vid . '""><i class="fas fa-calendar" title="Ver Solicitud"></i></span>' . '</div>';
    }*/
    $pendientes = $BOLETA->dias_horas($s['vac_sol'], 2);
    $today = date('Y-m-d');
    $diares = ($s['vac_fch_ini'] < $today) ? (($s['diares']) . "<br><span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/telefonos/detalle_telefonos.php?id_persona={$s['id_persona']}' data-toggle='tooltip' data-placement='top' title='Mostrar contactos'>
    <i class='fa fa-phone'></i>
    </span>") : "No ha iniciado vacaciones";
    $sub_array = array(
      'vac_id' => $vid,
      'id_persona' => $s['id_persona'],
      'persona' => $s['nombre'],
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
