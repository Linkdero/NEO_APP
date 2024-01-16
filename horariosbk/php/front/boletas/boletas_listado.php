<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) {

    include_once 'horarios/php/back/functions.php';
    date_default_timezone_set('America/Guatemala');

    $HORARIO = new Horario();
    $id_persona = $HORARIO->get_name($_SESSION['id_persona']);

    if (usuarioPrivilegiado()->hasPrivilege(295)) {
      $priv = 1;
      $empleados = $HORARIO->get_empleados_by_dir($_SESSION['id_persona']);
    }
    if (usuarioPrivilegiado()->hasPrivilege(297)) {
      $priv = 2;
      $empleados = $HORARIO->get_empleados_by_dir($_SESSION['id_persona']);
    }
    if (usuarioPrivilegiado()->hasPrivilege(298)) {
      $priv = 3;
      $empleados = $HORARIO->get_empleados_by_dir($_SESSION['id_persona']);
    }
    if (usuarioPrivilegiado()->hasPrivilege(297) && usuarioPrivilegiado()->hasPrivilege(298)) {
      $priv = 4;
      $empleados = $HORARIO->get_empleados_for_permiso();
    }




    $nao = date("Y-m-d") . "T" . date("H:i");
?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="horarios/js/source_vacaciones.js"></script>-->
    <script src="horarios/js/functions.js"></script>
    <script src="assets/js/pages/fechas.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/vacaciones/impresiones.js"></script>
    <script src='assets/js/plugins/datatables/pdfmake.min.js'></script>
    <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>
    <link rel='stylesheet' type='text/css' href='assets/js/plugins/lightpick/css/lightpick.css'>
    <script src='assets/js/plugins/lightpick/moment.min.js'></script>
    <script src='assets/js/plugins/lightpick/lightpick.js'></script>
    <link rel='stylesheet' href='assets/js/plugins/flatpickr/flatpickr.min.css'>
    <script src='assets/js/plugins/flatpickr/flatpickr.js'></script>
    <script src='assets/js/plugins/litepicker/litepicker.js'></script>
    <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet' />
    <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
    <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script>
    <link rel='stylesheet' href='assets/js/plugins/select2/select2.min.css'>
    <!-- <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script> -->
    <script src='assets/js/plugins/select2/select2.min.js'></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/insumos/solvencia.js"></script>
    <!--<script src="horarios/js/source_boletas.js"></script>-->

    <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="assets/js/plugins/fullcalendar/packages/core/main.css">
    <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/daygrid/main.css'>
    <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/timegrid/main.css'>
    <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/list/main.css'>
    <script src='assets/js/plugins/fullcalendar/packages/core/main.js'></script>
    <script src='assets/js/plugins/fullcalendar/packages/interaction/main.js'></script>
    <script src='assets/js/plugins/fullcalendar/packages/daygrid/main.js'></script>
    <script src='assets/js/plugins/fullcalendar/packages/timegrid/main.js'></script>
    <script src='assets/js/plugins/fullcalendar/packages/list/main.js'></script>
    <script src='assets/js/plugins/fullcalendar/packages/core/locales/es.js'></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="horarios/js/boletasvue.js"></script>
    <script src="horarios/js/functions.js"></script>


    <div class="u-content">
      <div class="u-body">
        <div class="card mb-4 ">
          <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Control de Vacaciones</h2>
            <ul class="list-inline ml-auto mb-0">
              <?php if (usuarioPrivilegiado()->hasPrivilege(295)) :  ?>
                <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                  <li class='nav-item'>
                    <a class='nav-link active' data-toggle='tab' href='#vacaciones'>Boletas</a>
                  </li>
                  <li class='nav-item'>
                    <a class='nav-link' active data-toggle='tab' href='#periodos' onclick=''>Vacaciones</a>
                  </li>
                  <li class='nav-item'>
                    <a class='nav-link' active data-toggle='tab' href='#reporte' onclick=''>Reportes</a>
                  </li>
                  <?php if (usuarioPrivilegiado()->hasPrivilege(314)) : ?>
                    <li class='nav-item'>
                      <a class='nav-link' active data-toggle='tab' href='#tabla' onclick='refresh_periodo_empleado()'>Nueva Solicitud</a>
                    </li>
                  <?php endif; ?>
                  <!-- <li class='nav-item'>
                    <a class='nav-link' active data-toggle='tab' href='#tabcalendario' onclick=''>Calendario</a>
                  </li> -->
                </ul>
              <?php endif; ?>
            </ul>
          </header>
          <iframe id="pdf_preview_v" hidden></iframe>
          <div class="card-body card-body-slide">
            <div class="tab-content">
              <div id="vacaciones" class="tab-pane fade show active" role="tabpanel">
                <div class="table-responsive">
                  <input type="text" id="id_tipo_filtro" hidden value="1"></input>
                  <table id="tb_vacaciones" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                    <thead>
                      <div class="row">
                        <div class="col-sm-2">
                          <p id="filter1"><label><b>Estado:</b></label><br></p>
                        </div>
                        <div class="col-sm-1">
                          <p id="filter3"><label><b>Año:</b></label><br></p>
                        </div>
                        <div class="col-sm-1">
                          <p id="filter4"><label><b>Mes Inicio:</b></label><br></p>
                        </div>
                        <div class="col-sm-1">
                          <p id="filter5"><label><b>Mes Fin:</b></label><br></p>
                        </div>
                        <?php if (usuarioPrivilegiado()->hasPrivilege(298)) : ?>
                          <div class="col-sm-3">
                            <p id="filter2"><label><b>Dirección:</b></label><br></p>
                          </div>
                        <?php endif; ?>
                        <input type="text" id="fff1" hidden value="0"></input>
                        <input type="text" id="fff2" hidden value="0"></input>
                        <div id="hidfechas" class="col-sm-2">
                          <!--<p id="filterf1"><label><b>Fechas:</b></label></p>
                          <input type='text' class='form-control' style="margin-top: -16px;" id='ff1' autocomplete="off">-->
                          <script>
                            fechasf1 = new Litepicker({
                              element: document.getElementById('ff1'),
                              allowRepick: true,
                              format: "DD-MM-YYYY",
                              resetButton: true,
                              showTooltip: false,
                              dropdowns: {
                                "minYear": 2015,
                                "maxYear": 2022,
                                "months": true,
                                "years": true
                              },
                              singleMode: false,
                              splitView: false,
                              lang: 'es',
                              delimiter: ' a ',
                              setup: (picker) => {
                                picker.on('selected', (date1, date2) => {
                                  recargar_fechas(date1['dateInstance'], date2['dateInstance']);
                                });
                              },
                            });
                          </script>
                        </div>
                      </div>
                      <tr>
                        <th class=" text-center">Estado</th>
                        <th class=" text-center">No. Boleta</th>
                        <th class=" text-center">Persona</th>
                        <th class=" text-center">Fecha de Solicitud</th>
                        <th class=" text-center">Inicio</th>
                        <th class=" text-center">Fin</th>
                        <th class=" text-center">Días Solicitados</th>
                        <th class=" text-center">Fecha de Regreso</th>
                        <th class=" text-center">Días Restantes</th>
                        <th class=" text-center">Periódo</th>
                        <th class=" text-center">Dirección</th>
                        <th class=" text-center">Acción</th>
                        <th class=" text-center">Mes Inicio</th>
                        <th class=" text-center">Mes Fin</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <div id='periodos' class='tab-pane fade'>
                <div class="row">
                  <div class="col-md-12 mb-12 mb-md-0">
                    <div class="">
                      <table id="tb_pendientes" class="table table-sm table-bordered table-striped" width="100%">
                        <input type="text" id="id_1_0" hidden value="1"></input>
                        <thead>
                          <div class="row">
                            <div class="col-sm-3">
                              <p id="periodopendiente"><label><b>Periódo Pendiente:</b></label><br></p>
                            </div>
                            <?php if (usuarioPrivilegiado()->hasPrivilege(298)) :  ?>
                              <div class="col-sm-6">
                                <p id="periododir"><label><b>Dirección:</b></label><br></p>
                              </div>
                            <?php endif; ?>
                          </div>
                          <tr>
                            <th class="text-center">Fotografia</th>
                            <th class="text-center">Empleado</th>
                            <th class="text-center">Dirección Funcional</th>
                            <th class="text-center">Puesto Funcional</th>
                            <th class="text-center">Días Pendientes</th>
                            <th class="text-center">Días Utilizados</th>
                            <th class="text-center">Acción</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div id="reporte" class="tab-pane fade show" role="tabpanel">
                <div class="table-responsive">
                  <input type="text" id="id_tipo_filtro" hidden value="1"></input>
                  <div class="row">
                    <div class="col-sm-2" style=" z-index:2">
                      <p><label><b>Inicio:</b></label></p>
                      <input id="fecha_i" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date" style="margin-top:-16px"></input>
                    </div>
                    <div class="col-sm-2" style=" z-index:2">
                      <p><label><b>Fin:</b></label></p>
                      <input id="fecha_f" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date" style="margin-top:-16px"></input>
                    </div>
                    <div class="col-sm-2" style=" z-index:2">
                      <p id="filter11"><label><b>Estado:</b></label></p>
                    </div>
                    <div class="col-sm-2">
                      <p id="filter22"><label><b>Dirección:</b></label></p>
                    </div>
                  </div>

                  <table id="tb_reporte" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                    <thead>
                      <tr>
                        <th class=" text-center">Estado</th>
                        <th class=" text-center">No. Boleta</th>
                        <th class=" text-center">Persona</th>
                        <th class=" text-center">Fecha de Solicitud</th>
                        <th class=" text-center">Inicio</th>
                        <th class=" text-center">Fin</th>
                        <th class=" text-center">Días Solicitados</th>
                        <th class=" text-center">Fecha de Regreso</th>
                        <th class=" text-center">Días Restantes</th>
                        <th class=" text-center">Periódo</th>
                        <th class=" text-center">Dirección</th>
                        <th class=" text-center">Acción</th>
                        <th class=" text-center">Mes Inicio</th>
                        <th class=" text-center">Mes Fin</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <div id='tabcalendario' class='tab-pane fade'>
              </div>

              <div id='tabla' class='tab-pane fade'>
                <script>
                  setTimeout(() => {
                    $("#empleado_v").select2({
                      placeholder: "Seleccione un empleado",
                    });
                  }, 200);
                </script>
                <div class="row" style="margin-bottom: 15px;">
                  <div class="col-sm-10">
                    <label for='empleado_v'>Persona:</label>
                    <select id='empleado_v' class='form-control mb-2 mr-sm-2' onchange='get_periodo_empleado(this.value)' style="width: 100%;">
                      <?php
                      foreach ($empleados as $empleado) {
                        echo "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <label for='diasua'>Diás Utilizados en el Año:</label>
                    <input type="text" id="diasua" style="text-align:center;" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table id='tb_empleados' class='table table-sm table-bordered table-striped' width='100%' hidden>
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
                    <div class='row' id='vobs' style="margin-top: 10px;">
                      <div class='col-sm-12'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    <?php
  }
} else {
  header("Location: index.php");
}
    ?>
