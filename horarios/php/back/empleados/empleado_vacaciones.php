<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $HORARIO = new Horario();
  $id_persona = $HORARIO->get_name($_SESSION['id_persona']);
  $empleados = $HORARIO->get_empleados_for_permiso();
  $nao = date("Y-m-d") . "T" . date("H:i");
?>
  <script>
    setTimeout(() => {
      $("#empleado_v").select2({});
    }, 200);
  </script>
<?php
  $select = "<label for='empleado_v' class='control-label mr-sm-2'>Persona:</label>
                <select id='empleado_v' class='form-control mb-2 mr-sm-2' onchange='get_periodo_empleado(this.value)'>
                <option disabled selected>Seleccione un empleado</option>";
  foreach ($empleados as $empleado) {
    $select .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
  }
  $select .= "</select></div></div>
                  <table id='tb_empleados' class='table table-sm table-bordered table-striped' width='100%'>
                      <thead>
                          <tr>
                              <th class='text-center'>Periódo</th>
                              <th class='text-center'>Días Asignados</th>
                              <th class='text-center'>Días Gozados</th>
                              <th class='text-center'>Días Pendientes</th>
                              <th class='text-center'>Estado</th>
                              <th class='text-center'>Acción</th>
                          </tr>
                      </thead>
                      <tbody id='vacaciones_table' class='text-center'>
                  </table>
              <div class='row' id='vdias'>
                <div class='col-sm-12'>
                </div>
              </div>
              <div class='row' id='vfechas'>
                <div class='col-sm-12'>
                </div>
            </div>
            <div class='row' id='vobs'>
                <div class='col-sm-12'>
                </div>
            </div>";
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

  echo $select;
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
?>