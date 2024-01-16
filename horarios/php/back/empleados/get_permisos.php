<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');
  if (usuarioPrivilegiado()->hasPrivilege(313)) {
    $tipo = 1;
  }
  if (usuarioPrivilegiado()->hasPrivilege(312)) {
    $tipo = 2;
  }
  $HORARIO = new Horario();
  $permisos = $HORARIO::get_permisos($tipo);
  $empleados = $HORARIO::get_empleados_for_permiso();

  $nao = date("Y-m-d");

?>
  <script>
    setTimeout(() => {
      $("#select_employee").select2({});
    }, 200);
    setTimeout(() => {
      $("#select_employee1").select2({});
    }, 200);
    setTimeout(() => {
      $("#tipo").select2({});
    }, 200);
  </script>
<?php

  $select_tipos = "  
                      <div class='col-sm-12' style='padding-top: 10px;'>
                      <label for='tipo' class='control-label mr-sm-2'>Motivo:</label>
                      <select id='tipo' class='form-control mb-2 mr-sm-2'>
                      <option disabled selected>Seleccione una opción</option>";
  foreach ($permisos as $permiso) {
    $select_tipos .= "<option value={$permiso['id_catalogo']}>{$permiso['nombre']}</option>";
  }

  if ($tipo == 1) {
    $select_tipos .= "</select></div>
                      <div class='col-sm-6' style='padding-top: 10px;'>";
  } else {
    $select_tipos .= "</select></div>
                      <div class='col-sm-12' style='padding-top: 10px;'>";
  }
  $personas = "";
  $select_tipos .= "<label for='select_employee' class='control-label mr-sm-2'>Persona:</label>
                <select id='select_employee' class='form-control mb-2 mr-sm-2'>
                <option disabled selected>Seleccione un empleado</option>";


  foreach ($empleados as $empleado) {
    $personas .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
  }
  $select_tipos .= $personas;
  $select_tipos .= "</select></div>";

  if ($tipo == 1) {
    $select_tipos .= "<div class='col-sm-6' style='padding-top: 10px;'>
                <label for='select_employee1' class='control-label mr-sm-2'>Autoriza:</label>
                <select id='select_employee1' class='form-control mb-2 mr-sm-2'>
                <option disabled selected>Seleccione un empleado</option>";

    $empleados = $HORARIO::get_autoriza_for_permiso();
    $personas = "";
    foreach ($empleados as $empleado) {
      $personas .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
    }
    $select_tipos .= $personas;
    $select_tipos .= "</select></div>";
  }
  $select_tipos .= "<div class='col-sm-6' style='padding-top: 10px;'>
                      <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                      <input type='date' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
                    </div>
                    <div class='col-sm-6' style='padding-top: 10px;'>
                      <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                      <input type='date' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
                    </div>
                    <div class='col-sm-12' style='padding-top: 10px;'>
                      <label for='usuario'>Observaciones</label>
                      <div class='input-group  has-personalizado'>
                          <textarea rows='5' type='text' class=' form-control ' id='observaciones' name='observaciones' placeholder='Observaciones' required autocomplete='off'></textarea>
                      </div>
                    </div>";

  if ($tipo == 1) {
    $select_tipos .= "<div class='col-sm-4' style='padding-top: 20px;'>
                      <input type='text' class='form-control mb-2 mr-sm-2' id='nro_boleta' placeholder='Número de Boleta'>
                    </div>
                     <div class='col-sm-1' style='padding-top: 20px;'>
                     </div>
                    <div class='col-sm-5' style='padding-top: 20px;'>
                       Autorizado Recursos Humanos <label for='aut' class='css-input switch switch-success switch-sm'>
                       <input type='checkbox' class='dt-checkboxes' id='aut' checked><span></span></label>
                    </div>";
  }
  $select_tipos .= "<div class='col-sm-2' style='padding-top: 20px;'>
                      <button type='button' class='btn btn-success' onclick='save_fechas(1," . $tipo . ")'><i class='fa fa-check'></i> Guardar </button>
                    </div>";
  echo $select_tipos;
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
