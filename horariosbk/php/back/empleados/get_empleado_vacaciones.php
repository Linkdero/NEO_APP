<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona = $_POST["id_persona"];
  $BOLETA = new Boleta;
  $periodos = $BOLETA->get_vacaciones_pendientes($id_persona);
  $body_table = "";
  if (sizeof($periodos) == 0) {
    $body_table .= "<tr><td colspan='6'>No hay vacaciones pendientes</td></tr>";
  } else {

    $e = 1;
    foreach ($periodos as $p) {


      $d = $p['dia_asi'] - $p['dia_goz'];
      $h = ceil(fmod($d, 1) * 8);
      $d = floor($d);
      $args = "this," . $p['dia_id'] . "," . $d . "," . $h;
      $pendientes =  $BOLETA->dias_horas($d, 0);
      $estado = ($p['dia_est'] == 0) ? '<span class="badge badge-success">Vacaciones Gozadas</span>' : '<span class="badge badge-warning">Vacaciones Pendientes</span>';
      $da = $p['dia_asi'];
      $asignados =  $BOLETA->dias_horas($da, 0);
      $dg = $p['dia_goz'];
      $gozados =  $BOLETA->dias_horas($dg, 0);



      $body_table .= "<tr>";
      $body_table .= "<td >{$p['anio_des']}</td>";
      $body_table .= "<td>{$asignados}</td>";
      $body_table .= "<td>{$gozados}</td>";
      $body_table .= "<td>{$pendientes}</td>";
      $body_table .= "<td>{$estado}</td>";
      $body_table .= ($e == 1) ? "<td><input type='checkbox' id'{$p['dia_id']}' name='{$p['dia_id']}' onclick='checkVacaciones(" . $args . ")'></td>" : "<td><input type='checkbox' id'{$p['dia_id']}' name='{$p['dia_id']}' value='' disabled></td>";
      $body_table .= "</tr>";
      $e = 0;
    }
    $body_table .= "";
  }
  echo $body_table;


  // $year = "2021";
  // $HORARIO = new Horario();
  // $total_descansos = $HORARIO::get_total_descansos($year, 1, 0);
  // $array_dias = array('Domingo', 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
  // $body_table = "";
  // $dias = array();
  // foreach($total_descansos as $descanso){
  //     $dias[] = $array_dias[date('w', strtotime($descanso['inicio']))];
  //     if($descanso['inicio'] != $descanso['fin']){
  //         $fecha_actual = $descanso['inicio'];
  //         while(true){
  //             $strtime = strtotime($fecha_actual."+ 1 days");
  //             $fecha_actual = date("d-m-Y", $strtime);
  //             if($strtime >  strtotime($descanso['fin']))break;
  //             $dias[] = $array_dias[date('w', $strtime)];
  //         }
  //         $dia = implode(" - ", $dias);
  //     }else{
  //         $dia = $array_dias[date('w', strtotime($descanso['inicio']))];
  //     }
  //     $inicio = date("d-m-Y", strtotime($descanso['inicio']));
  //     $fin = date("d-m-Y", strtotime($descanso['fin']));
  //     $body_table .= "<tr>";
  //     $body_table .= "<td >{$dia}</td>";
  //     $body_table .= "<td>{$inicio}</td>";
  //     $body_table .= "<td>{$fin}</td>";
  //     $body_table .= "<td>{$descanso['motivo']}</td>";
  //     $body_table .= "</tr>";
  //     $dias = array();
  // }
  // echo $body_table;
  // $direccion = $HORARIO::get_direccion_empleado($_SESSION["id_persona"]);
  // $empleados = $HORARIO::get_empleados_by_permiso($direccion[0]["id_dirf"], $id_catalogo);

  // $nao = date("Y-m-d")."T".date("H:i");
  //   $select = "
  // <script src='horarios/js/source_periodo.js'></script>
  // <script> datableEmpleado.init({$id_persona})</script>";
  // foreach($empleados as $empleado){
  //     $select .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
  // }
  // $select .= "</select></div></div>";
  // if($_POST["id_tipo"] == 11 || $_POST["id_tipo"] == 12 || $_POST["id_tipo"] == 13 ){
  //   $select .= "<div class='row'>
  //                 <div class='col-sm-5'>
  //                     <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
  //                 </div>
  //                 <div class='col-sm-5'>
  //                     <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
  //                 </div>
  //               </div>";
  // }elseif($_POST["id_tipo"] == 10){
  //   $select .= "<div class='row'>
  //                 <div class='col-sm-5'>
  //                     <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
  //                 </div>
  //                 <div class='col-sm-5'>
  //                     <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
  //                 </div>
  //               </div>
  //               <div class='row'>
  //                 <label for='usuario'>Observaciones</label>
  //                   <div class='input-group  has-personalizado'>
  //                     <textarea rows='5' type='text' class=' form-control ' id='observaciones' name='observaciones' placeholder='Observaciones' required autocomplete='off'></textarea>
  //                   </div>
  //               </div>
  //               ";
  // }else{
  //   $select .= "<div class='row'>
  //                 <div class='col-sm-5'>
  //                     <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
  //                 </div>
  //                 <div class='col-sm-5'>
  //                     <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
  //                     <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
  //                 </div>
  //               </div>";
  // }

} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
