<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {

  $u = usuarioPrivilegiado();
  //if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
  if (true) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';

    if ($u->hasPrivilege(313)) {
      $tipo = 1;
    }
    if ($u->hasPrivilege(312)) {
      $tipo = 2;
    }

    $id_persona = $_GET['id_persona'];
    $HORARIO = new Horario();
    $nombre = $HORARIO->get_name($id_persona);
    $permisos = $HORARIO->get_permisos($tipo);
    $empleados = $HORARIO->get_autoriza_for_permiso();
    $nao = date("Y-m-d");
?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="horarios/js/functions.js"></script>

      <link rel='stylesheet' href='assets/js/plugins/select2/select2.min.css'>
      <script src='assets/js/plugins/select2/select2.min.js'></script>
    </head>

    <body>
      <div class="modal-header">
        <h5 class="modal-title">Generar Boleta</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>

      </div>
      <div class="modal-body">

        <form class="validation_nueva_boleta">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="empleado">Empleado</label>
                    <div class=" input-group  has-personalizado">
                      <input type="text" class=" form-control " id="empleado" name="empleado" value="<?php echo $nombre['nombre']; ?>" disabled />
                      <input type="hidden" id="select_employee" name="select_employee" value="<?php echo $id_persona; ?>" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="tipo">Motivo</label>
                    <div class=" input-group  has-personalizado">
                      <?php
                      $select_tipos = "<select id='tipo' class='form-control mb-2 mr-sm-2'>
                                        <option disabled selected>Seleccione una opción</option>";
                      foreach ($permisos as $permiso) {
                        $select_tipos .= "<option value={$permiso['id_catalogo']}>{$permiso['nombre']}</option>";
                      }
                      $select_tipos .= "</select>";
                      echo $select_tipos;
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($tipo == 1) :  ?>
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="row">
                      <label for="select_employee1">Autoriza</label>
                      <div class=" input-group  has-personalizado">
                        <?php
                        $personas = "";
                        $select_tipos = "<select id='select_employee1' class='form-control mb-2 mr-sm-2'>
                                       <option disabled selected>Seleccione un empleado</option>";
                        foreach ($empleados as $empleado) {
                          $personas .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
                        }
                        $select_tipos .= $personas;
                        $select_tipos .= "</select>";
                        echo $select_tipos;
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <div class='col-sm-6' style='padding-top: 10px;'>
              <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
              <input type='date' class='form-control mb-2 mr-sm-2' id='inicio' value="<?php echo $nao; ?>">
            </div>
            <div class='col-sm-6' style='padding-top: 10px;'>
              <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
              <input type='date' class='form-control mb-2 mr-sm-2' id='fin' value="<?php echo $nao; ?>">
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="observaciones">Observaciones</label>
                    <div class=" input-group  has-personalizado">
                      <textarea rows="5" type="text" class=" form-control " id="observaciones" name="observaciones" placeholder="Observaciones" autocomplete="off"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($tipo == 1) :  ?>
              <div class='col-sm-4' style='padding-top: 20px;'>
                <input type='text' class='form-control mb-2 mr-sm-2' id='nro_boleta' placeholder='Número de Boleta'>
              </div>
              <div class='col-sm-1' style='padding-top: 20px;'>
              </div>
              <div class='col-sm-5' style='padding-top: 20px;'>
                Autorizado Recursos Humanos <label for='aut' class='css-input switch switch-success switch-sm'>
                  <input type='checkbox' class='dt-checkboxes' id='aut' checked><span></span></label>
              </div>
            <?php endif; ?>
            <div class='col-sm-2' style='padding-top: 20px;'>
              <button type='button' class='btn btn-success' onclick='save_fechas(0,<?php echo $tipo; ?>)'><i class='fa fa-check'></i> Guardar </button>
            </div>
          </div>
        </form>
      </div>

      <script>
        // setTimeout(() => {
        //   $("#select_employee").select2({});
        // }, 200);
        setTimeout(() => {
          $("#select_employee1").select2({});
        }, 200);
        setTimeout(() => {
          $("#tipo").select2({});
        }, 200);
      </script>
  <?php
  } else {
    include('../inc/401.php');
  }
} else {
  header("Location: index");
}
  ?>
