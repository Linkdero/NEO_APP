<?php if (function_exists('verificar_session') && verificar_session()) :
    include_once 'horarios\php\back\functions.php';
    $HORARIO = new Horario();
    $listado = $HORARIO->get_listado_descansos();
    $select_tipo = "<select id='motivo' class='form-control mb-2 mr-sm-2' onchange='updateDate(this.value)'>
                    <option disabled selected>Seleccione una opción</option>";
    // foreach ($listado as $value) {
    //     $mes = $value['mes'];
    //     $dia = $value['dia'];
    //     $select_value = $value['id_descanso'] . "-" . $dia . "-" . $mes . "-" . $value["tipo"] . "-" . $value["id_tipo_ausencia"];
    //     $select_tipo .= "<option value={$select_value}>{$value['nombre']}</option>";
    // }

    if (usuarioPrivilegiado()->hasPrivilege(296)) {
        $select_tipo .= '<option value="47---2-3">PERMISOS</option>';
    }
    if (usuarioPrivilegiado()->hasPrivilege(295)) {
        $select_tipo .= '<option value="48---2-4">VACACIONES</option>';
    }
    // if (usuarioPrivilegiado()->hasPrivilege(312)) {
    //     $select_tipo .= '<option value="49---2-5">SUSPENSIONES</option>';
    // }
    if (usuarioPrivilegiado()->hasPrivilege(293)) {
        $controlpermisos = "<li class='nav-item'>
                            <a class='nav-link' active data-toggle='tab' href='#permisos'>Permisos</a>
                            </li>";
    } else {
        $controlpermisos = "";
    }
    $select_tipo .= "</select>";
    $array_meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
?>



    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) : ?>
        <script src="horarios/js/source.js"></script>
        <script src="horarios/js/functions.js"></script>
        <script src='assets/js/plugins/jspdf/jspdf.js'></script>
        <script src='assets/js/plugins/jspdf/vacaciones/impresiones.js'></script>
        <script src='assets/js/plugins/datatables/pdfmake.min.js'></script>
        <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <link rel='stylesheet' type='text/css' href='assets/js/plugins/lightpick/css/lightpick.css'>
        <script src='assets/js/plugins/lightpick/moment.min.js'></script>
        <script src='assets/js/plugins/lightpick/lightpick.js'></script>
        <link rel='stylesheet' href='assets/js/plugins/flatpickr/flatpickr.min.css'>
        <script src='assets/js/plugins/flatpickr/flatpickr.js'></script>
        <script src='assets/js/plugins/litepicker/litepicker.js'></script>
        <!-- <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet' />
        <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
        <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script> -->
        <link rel='stylesheet' href='assets/js/plugins/select2/select2.min.css'>
        <script src='assets/js/plugins/select2/select2.min.js'></script>


        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card h-100">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">Reporte Personal</h2>
                                <ul class="list-inline ml-auto mb-0">
                                    <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                                        <?php if (usuarioPrivilegiado()->hasPrivilege(292)) :  ?>
                                            <li class="list-inline-item" title="Todos los empleados">
                                                <a class='nav-link active' data-toggle='tab' href='#templeados' onclick="reload_empleados()">Todos los empleados</a>
                                            </li>
                                            <li class=" list-inline-item" title="Personal de 8 horas">
                                                <a class='nav-link' data-toggle='tab' href='#templeados' onclick="reload_detalle()">Personal de 8 horas</a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (usuarioPrivilegiado()->hasPrivilege(293) or usuarioPrivilegiado()->hasPrivilege(295) or usuarioPrivilegiado()->hasPrivilege(296)) :  ?>
                                            <li class=" nav-item">
                                                <a class='nav-link' data-toggle='tab' href='#fferiados' onclick="reload_feriados()">Feriados y Ausencias</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="tab-content">
                                    <div id="templeados" class="tab-pane fade show active" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tb_empleados" class="table table-sm table-bordered table-striped" width="100%">
                                                <thead>
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <label><b>Dirección:</b></label><br>
                                                            <p id="filter1"></p>
                                                        </div>
                                                        <!-- <div class="col-sm-6">
                                                        <p id="filter2"><label><b>Grupo:</b></label><br></p>
                                                    </div> -->
                                                        <div class='col-sm-3'>
                                                            <p>
                                                                <label><b>Mes:</b></label>
                                                                <select id='month_1' class='form-control' onchange='reload_detalle_1()'>
                                                                    <option value='1'>Enero</option>
                                                                    <option value='2'>Febrero</option>
                                                                    <option value='3'>Marzo</option>
                                                                    <option value='4'>Abril</option>
                                                                    <option value='5'>Mayo</option>
                                                                    <option value='6'>Junio</option>
                                                                    <option value='7'>Julio</option>
                                                                    <option value='8'>Agosto</option>
                                                                    <option value='9'>Septiembre</option>
                                                                    <option value='10'>Octubre</option>
                                                                    <option value='11'>Noviembre</option>
                                                                    <option value='12'>Diciembre</option>
                                                                </select><br>
                                                            </p>
                                                        </div>
                                                        <div class='col-sm-3'>
                                                            <p id='year'>
                                                                <label><b>Año:</b></label>
                                                                <select id='year_1' class='form-control' onchange='reload_detalle_1()'>
                                                                  <option value='2024'>2024</option>
                                                                  <option value='2023'>2023</option>
                                                                  <option value='2022'>2022</option>
                                                                  <option value='2021'>2021</option>
                                                                  <option value='2020'>2020</option>
                                                                </select><br>
                                                        </div>
                                                        <?php if (usuarioPrivilegiado()->hasPrivilege(292)) :  ?>
                                                            <div class="col-sm-3">
                                                                <label><b>Tarde:</b></label><br>
                                                                <p id="filter2"></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <tr>
                                                        <th class="text-center">Fotografia</th>
                                                        <th class="text-center">Empleado</th>
                                                        <th class="text-center">D Nominal</th>
                                                        <th class="text-center">P Nominal</th>
                                                        <th class="text-center">D Funcional</th>
                                                        <th class="text-center">P Funcional</th>
                                                        <th class="text-center">Horario</th>
                                                        <th class="text-center">Tarde</th>
                                                        <th class="text-center">TTarde</th>
                                                        <th class="text-center">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id='fferiados' class='tab-pane fade'>
                                        <div class="table-responsive">
                                            <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist' style="margin-bottom: 15px;">
                                                <li class='nav-item'>
                                                    <a class='nav-link active' data-toggle='tab' href='#feriados'>Ausencias</a>
                                                </li>
                                                <li class='nav-item'>
                                                    <a class='nav-link' active data-toggle='tab' href='#tabla' onclick='reload_table()'>Tabla Anual</a>
                                                </li>
                                                <?php echo $controlpermisos ?>
                                            </ul>
                                            <div class='tab-content'>
                                                <div id='feriados' class='container tab-pane active'>

                                                    <form id='descansos'>
                                                        <div class='form-group'>
                                                            <div class='row'>
                                                                <div class='col-sm-6'>
                                                                    <label for='motivo' class='control-label mr-sm-2'>Tipo:</label>
                                                                    <?php echo $select_tipo ?>
                                                                </div>
                                                            </div>
                                                            <div class='row' id='fechas'>
                                                                <div class='col-sm-12' style='padding-top: 10px;'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                                <div id='tabla' class='container tab-pane fade'>

                                                    <table id='tb_empleados' class='table table-sm table-bordered table-striped' width='100%'>
                                                        <thead>
                                                            <tr>
                                                                <th class='text-center'>Dia/s</th>
                                                                <th class='text-center'>Fecha Inicio</th>
                                                                <th class='text-center'>Fecha Fin</th>
                                                                <th class='text-center'>Motivo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id='body_table' class='text-center'>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div id='permisos' class='container tab-pane fade'>
                                                    <table id='tb_permisos' class='table table-sm table-bordered table-striped' width='100%'>
                                                        <thead>
                                                            <div class='row'>
                                                                <div class='col-sm-3'>
                                                                    <p>
                                                                        <label><b>Mes:</b></label>
                                                                        <select id='month_p' class='form-control' onchange='refresh_boletas()'>
                                                                            <option value='0'>TODOS</option>
                                                                            <option value='1'>Enero</option>
                                                                            <option value='2'>Febrero</option>
                                                                            <option value='3'>Marzo</option>
                                                                            <option value='4'>Abril</option>
                                                                            <option value='5'>Mayo</option>
                                                                            <option value='6'>Junio</option>
                                                                            <option value='7'>Julio</option>
                                                                            <option value='8'>Agosto</option>
                                                                            <option value='9'>Septiembre</option>
                                                                            <option value='10'>Octubre</option>
                                                                            <option value='11'>Noviembre</option>
                                                                            <option value='12'>Diciembre</option>
                                                                        </select><br>
                                                                    </p>
                                                                </div>
                                                                <div class='col-sm-3'>
                                                                    <p id='year'>
                                                                        <label><b>Año:</b></label>
                                                                        <select id='year_p' class='form-control' onchange='refresh_boletas()'>
                                                                          <option value='2024'>2024</option>
                                                                          <option value='2023'>2023</option>
                                                                          <option value='2022'>2022</option>
                                                                            <option value='2021'>2021</option>
                                                                            <option value='2020'>2020</option>
                                                                        </select><br>
                                                                </div>
                                                            </div>
                                                            <tr>
                                                                <th class='text-center'>ID</th>
                                                                <th class='text-center'>No. Boleta</th>
                                                                <th class='text-center'>Persona</th>
                                                                <th class='text-center'>Fecha Inicio</th>
                                                                <th class='text-center'>Fecha Fin</th>
                                                                <th class='text-center'>Motivo</th>
                                                                <th class='text-center'>Observaciones</th>
                                                                <th class='text-center'>Autoriza</th>
                                                                <th class='text-center'>Estado</th>
                                                                <th class='text-center'>Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <!-- <tbody id='permisos_table' class='text-center'>
                                                        </tbody> -->
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else : ?>


        <?php endif; ?>

    <?php else : ?>


    <?php endif; ?>
