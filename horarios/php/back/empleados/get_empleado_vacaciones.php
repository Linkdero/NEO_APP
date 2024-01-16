<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');
  $id_persona = $_POST["id_persona"];
  $BOLETA = new Boleta;
  $periodos = $BOLETA->get_vacaciones_pendientes($id_persona);
  $utilizados = $BOLETA->get_vacaciones_utilizadas($id_persona);
  $dutz = (floor($utilizados['dutz']) == 0) ? '' : floor($utilizados['dutz']);
  $trows = sizeof($periodos);
  $body_table = "<input type='hidden' value='{$trows}' id='tt'>
                 <input type='hidden' value='{$id_persona}' id='idp'>
                 <input type='hidden' value='{$dutz}' id='hdiasua'>";
  if ($trows == 0) {
    $body_table .= "<tr><td colspan='6'>No hay vacaciones pendientes</td></tr>";
  } else {
    $e = 1;
    $cnt = 1;
    $arr = array_fill(0, $trows + 1, $e);

    foreach ($periodos as $p) {

      $decs = $p['dia_asi'] - $p['dia_goz'];
      $h = round(fmod($decs, 1) * 8);
      $d = floor($decs);
      $dia_id = $p['dia_id'];
      $pendientes =  $BOLETA->dias_horas($decs, 0);
      $estado = ($p['dia_est'] == 0) ? '<span class="badge badge-success">Vacaciones Gozadas</span>' : '<span class="badge badge-warning">Vacaciones Pendientes</span>';
      $da = $p['dia_asi'];
      $asignados =  $BOLETA->dias_horas($da, 0);
      $dg = $p['dia_goz'];
      $gozados =  $BOLETA->dias_horas($dg, 0);
      $pen = (!empty($p['dias_apartados'])) ? $p['dias_apartados'] : 0;
      $apartados = (!empty($p['dias_apartados'])) ? number_format($pen,0,'','') : 0;
      $disponibles =  number_format(($da- $dg - $apartados),0,'','');

      //$args = "this," . $dia_id . "," . $d . "," . $h . "," . $decs; // backup
      $args = "this," . $dia_id . "," . $disponibles . "," . $h . "," . $decs.",".$apartados;
      $body_table .= "<tr>";
      $body_table .= "<td >{$p['anio_des']}</td>";
      $body_table .= "<td>{$asignados}</td>";
      $body_table .= "<td>{$gozados}</td>";
      $body_table .= "<td>{$pendientes}</td>";
      $body_table .= "<td>{$apartados}</td>";
      $body_table .= "<td>{$disponibles}</td>";
      $body_table .= "<td>{$estado}</td>";
      if ($arr[$cnt - 1] == 0) {
        $e = 0;
      }

      //$body_table .= ($e == 1) ? "<td><label for='{$p['dia_id']}' class='css-input switch switch-info switch-sm'><input type='checkbox' class='dt-checkboxes' id='{$p['dia_id']}' value='{$p['dia_id']}' onclick='checkVacaciones(" . $args . ")'><span></span></label></td>" : "<td><label for='{$p['dia_id']}' class='css-input switch switch-danger switch-sm'><input type='checkbox' class='dt-checkboxes' id='{$p['dia_id']}' value='{$p['dia_id']}' disabled><span></span></label></td>";
      $body_table .= ($e == 1 && $disponibles > 0) ?
      "<td><label for='{$p['dia_id']}' class='css-input switch switch-info switch-sm'><input type='checkbox' class='dt-checkboxes' id='{$p['dia_id']}' value='{$p['dia_id']}' onclick='checkVacaciones(" . $args . ")'><span></span></label></td>" : "<td><label for='{$p['dia_id']}' class='css-input switch switch-danger switch-sm'><input type='checkbox' class='dt-checkboxes' id='{$p['dia_id']}' value='{$p['dia_id']}' disabled><span></span></label></td>";
      $arr[$cnt] = $e;
      $body_table .= "</tr>";
      $body_table .= "<input type='hidden' value='{$p['dia_id']}' id='cnt{$cnt}'>";
      $checkpen = $BOLETA->get_vacaciones_apartadas($p['dia_id']);
      $e = 0;
      foreach ($checkpen as $c) {
        if ($c['vac_pen'] == 0) {
          $e = 1;
        }
      }

      $cnt += 1;
    }
    $body_table .= "";
  }
  echo $body_table;
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
