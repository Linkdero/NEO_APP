<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_catalogo = $_POST["id_tipo"];
  $HORARIO = new Horario();
  // $direccion = $HORARIO::get_direccion_empleado($_SESSION["id_persona"]);
  // $empleados = $HORARIO::get_empleados_by_permiso($direccion[0]["id_dirf"], $id_catalogo);
  $empleados = $HORARIO::get_empleados_for_permiso();
  $nao = date("Y-m-d") . "T" . date("H:i");
?>
  <script>
    setTimeout(() => {
      $("#select_employee").select2({});
    }, 200);
    setTimeout(() => {
      $("#select_employee1").select2({});
    }, 200);
  </script>
<?php
  $personas = "";
  $select = "<label for='select_employee' class='control-label mr-sm-2'>Persona:</label>
                <select id='select_employee' class='form-control mb-2 mr-sm-2'>
                <option disabled selected>Seleccione un empleado</option>";
  foreach ($empleados as $empleado) {
    $personas .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
  }
  $select .= $personas;
  $select .= "</select></div></div>";

  $select .= "<label for='select_employee1' class='control-label mr-sm-2'>Autoriza:</label>
                <select id='select_employee1' class='form-control mb-2 mr-sm-2'>
                <option disabled selected>Seleccione un empleado</option>";
  $select .= $personas;
  $select .= "</select></div></div>";
  if ($_POST["id_tipo"] == 11 || $_POST["id_tipo"] == 12 || $_POST["id_tipo"] == 13) {
    $select .= "<div class='row'>
                    <div class='col-sm-6'>
                        <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
                    </div>
                    <div class='col-sm-6'>
                        <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
                    </div>
                  </div>
                  <div class='row'>
                    <label for='usuario'>Observaciones</label>
                      <div class='input-group  has-personalizado'>
                        <textarea rows='5' type='text' class=' form-control ' id='observaciones' name='observaciones' placeholder='Observaciones' required autocomplete='off'></textarea>
                      </div>
                  </div>";
  } elseif ($_POST["id_tipo"] == 10) {
    $select .= "<div class='row'>
                    <div class='col-sm-6'>
                        <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
                    </div>
                    <div class='col-sm-6'>
                        <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
                    </div>
                  </div>
                  <div class='row'>
                    <label for='usuario'>Observaciones</label>
                      <div class='input-group  has-personalizado'>
                        <textarea rows='5' type='text' class=' form-control ' id='observaciones' name='observaciones' placeholder='Observaciones' required autocomplete='off'></textarea>
                      </div>
                  </div>
                  ";
  } else {
    $select .= "<div class='row'>
                    <div class='col-sm-6'>
                        <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='inicio' value={$nao}>
                    </div>
                    <div class='col-sm-6'>
                        <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                        <input type='datetime-local' class='form-control mb-2 mr-sm-2' id='fin' value={$nao}>
                    </div>
                  </div>
                  <div class='row'>
                    <label for='usuario'>Observaciones</label>
                      <div class='input-group  has-personalizado'>
                        <textarea rows='5' type='text' class=' form-control ' id='observaciones' name='observaciones' placeholder='Observaciones' required autocomplete='off'></textarea>
                      </div>
                  </div>";
  }
  $select .= "<br>
                  <div class='row'>
                    <div class='col-sm-6'>
                      <button type='button' class='btn btn-success' onclick='save_fechas(1)'><i class='fa fa-check'></i> Guardar </button>
                    </div>
                  </div>";
  echo $select;
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
?>