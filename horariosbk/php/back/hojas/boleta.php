<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  //if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';
    $vac_id = $_POST['vac_id'];
    $BOLETA = new Boleta();
    $solicitud = $BOLETA->get_boleta_by_id($vac_id);
    $data = [];
    $data = array(

      'boleta' => $solicitud['vac_id'],
      'fsol' => $BOLETA->full_fecha(strtotime($solicitud['vac_fch_sol'])),
      'fini' => $BOLETA->full_fecha(strtotime($solicitud['vac_fch_ini'])),
      'ffin' => $BOLETA->full_fecha(strtotime($solicitud['vac_fch_fin'])),
      'fpre' => $BOLETA->full_fecha(strtotime($solicitud['vac_fch_pre'])),
      'vdia' => $BOLETA->dias_horas($solicitud['vac_dia'], 0),
      'vgoz' => $BOLETA->dias_horas($solicitud['vac_dia_goz'], 0),
      'vsub' => $BOLETA->dias_horas($solicitud['vac_sub'], 0),
      'vds' => $BOLETA->dias_horas($solicitud['vac_sub'], 2),
      'vhs' => $BOLETA->dias_horas($solicitud['vac_sub'], 3),
      'vsol' => $BOLETA->dias_horas($solicitud['vac_sol'], 0),
      'vpen' => $BOLETA->dias_horas($solicitud['vac_pen'], 0),
      'est_id' => $solicitud['est_id'],
      'vobs' => $solicitud['vac_obs'],
      'vestado' => $solicitud['est_des'],
      'nombre' => $solicitud['nombre'],
      'puesto' => strval($solicitud['p_funcional']),
      'dir_funcional' => $solicitud['dir_funcional'],
      'id_secre' => $solicitud['id_secre'],
      'id_subsecre' => $solicitud['id_subsecre'],
      'id_superior' => $solicitud['id_superior'],

    );

    echo json_encode($data);
  //}
}
