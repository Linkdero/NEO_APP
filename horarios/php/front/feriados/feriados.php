<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';

    $HORARIO = new Horario();
    $listado = $HORARIO::get_listado_descansos();
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
    $array_meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    $modal = "
        <link rel='stylesheet' type='text/css' href='assets/js/plugins/lightpick/css/lightpick.css'>
        <script src='assets/js/plugins/lightpick/moment.min.js'></script>
        <script src='assets/js/plugins/lightpick/lightpick.js'></script>



                <link rel='stylesheet' href='assets/js/plugins/flatpickr/flatpickr.min.css'>
                <script src='assets/js/plugins/flatpickr/flatpickr.js'></script>
                <script src='assets/js/plugins/litepicker/litepicker.js'></script>
                <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet'/>
                <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
                <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script>
                <link rel='stylesheet' href='assets/js/plugins/select2/select2.min.css'>
                 <script src='assets/js/plugins/select2/select2.min.js'></script>


                <div class='modal-header'>
                    <h3 class='modal-title'>Ausencias y Permisos</h5>
                    <ul class='list-inline ml-auto mb-0'>
                        <li class='list-inline-item'>
                            <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                <i class='fa fa-times'></i>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class='modal-body'>
                    <div class=''>
                        <div class=''>
                            <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                                <li class='nav-item'>
                                    <a class='nav-link active' data-toggle='tab' href='#feriados'>Ausencias</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' active data-toggle='tab' href='#tabla' onclick='reload_table()'>Tabla Anual</a>
                                </li>
                                {$controlpermisos}
                            </ul>
                            <div class='tab-content'>
                                <div id='feriados' class='container tab-pane active'>
                                    <div style='margin-top:5vh;'>
                                        <form id='descansos'>
                                            <div class='form-group'>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <label for='motivo' class='control-label mr-sm-2'>Tipo:</label>
                                                        {$select_tipo}
                                                    </div>
                                                </div>
                                                <div class='row' id='fechas'>
                                                    <div class='col-sm-12' style='padding-top: 10px;'>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div id='tabla' class='container tab-pane fade'>
                                    <div style='margin-top:5vh;'>
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
                                </div>
                                <div id='permisos' class='container tab-pane fade'>
                                    <div style='margin-top:5vh;'>
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
                                            <tbody id='permisos_table' class='text-center'>
                                            </tbody>
                                        </table>
                                    </div>
                                    <script src='horarios/js/source_periodo.js'></script>
                                    <script> datableEmpleado.init()</script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ";
    echo $modal;
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
