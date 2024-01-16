<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';
    $id_persona = $_POST['id_persona'];
    $BOLETA = new Boleta();
    $empleados = $BOLETA->get_dias_asignados($id_persona);

    $diapen = 0;
    foreach ($empleados as $empleado) {

      $diapen = ($empleado['dia_asi'] - $empleado['dia_goz']) + $diapen;
      $diapen1 = $BOLETA->dias_horas($diapen, 0);
      $data[] = array(
        'id_persona' => $id_persona,
        'nombre' => $empleado['nombre'],
        'p_nominal' => $empleado['p_nominal'],
        'dir_general' => $empleado['dir_general'],
        'fecha_ingreso' => $empleado['fecha_ingreso'],
        'dia_id' => $empleado['dia_id'],
        'dia_asi' => $empleado['dia_asi'],
        'dia_goz' => $empleado['dia_goz'],
        'anio_des' => $empleado['anio_des'],
        'dhasi' => $BOLETA->dias_horas($empleado['dia_asi'], 0),
        'dhgoz' => $BOLETA->dias_horas($empleado['dia_goz'], 0),
        'dhdiff' => $BOLETA->dias_horas(($empleado['dia_asi'] - $empleado['dia_goz']), 0),
        'diapen' => $diapen1,
      );
    }

    // $results = array(
    //   "sEcho" => 1,
    //   "iTotalRecords" => count($data),
    //   "iTotalDisplayRecords" => count($data),
    //   "aaData" => $data
    // );

    echo json_encode($data);
  }
}
