<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';

    setlocale(LC_TIME, 'es_ES');

    $HORARIO = new Horario();

    $id_persona = $_GET['id_persona'];
    $month = date("n");
    $year = date("Y");

    $nombre = $HORARIO::get_name($id_persona);
    $listado = $HORARIO::get_listado_descansos();
    $horarios = $HORARIO::get_horarios_fijos();


    if ($nombre['estado'] == 1) {
        $horas = date('H:i', strtotime($nombre['entrada'])) . ' - ' . date('H:i', strtotime($nombre['salida']));
    } else {
        $horas = 'Variado';
    }
    foreach ($horarios as $t) {


        $sub_array = array(
            'id_horario' => $t['id_horario'],
            'horario' => date('H:i', strtotime($t['entrada'])) . ' - ' . date('H:i', strtotime($t['salida'])),
        );

        $data[] = $sub_array;
    }

    $hfijos = "<select id='hfijos' class='form-control mb-2 mr-sm-2' onchange='select_horario_fijo()' disabled>
                    <option value='0' selected>Seleccione una opción</option>";

    foreach ($data as $h) {
        $hfijos .= "<option value='{$h['id_horario']}'>{$h['horario']}</option>";
    }
    $hfijos .= "</select>";

    $array_dias = array("", 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
    $array_meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    $modal = "   <script src='horarios/js/functions.js'></script>
                <input type='hidden' id='id_persona' value='" . $id_persona . "'>
                <div class='modal-header'>
                <h3 class='modal-title'>Cambios de Horario para: " . strval($nombre['nombre']) . "<br>Horario: " . $horas . "</h3>
                <h3 class='modal-title'></h5>
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
                                    <a class='nav-link active' data-toggle='tab' href='#cambiar'>Cambiar Horario</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' active data-toggle='tab' href='#cambios' onclick='refresh_cambio()'>Registro de Cambios</a>
                                </li>
                            </ul>
                            <div class='tab-content'>
                                <div id='cambiar' class=' tab-pane active'>
                                <div style='margin-top:5vh;'>
                                    <form id='fcambiar'>
                                        <div class='form-group'>
                                            <div class='row'>
                                                <div class='col-sm-6'>
                                                    <label for='iniciofecha' class='control-label mr-sm-2'>Inicio</label>
                                                    <input type='date' value='" . date('Y-m-d') . "' class='form-control mb-2 mr-sm-2' id='iniciofecha'>
                                                </div>
                                                <div class='col-sm-6'>
                                                    <label for='finfecha' class='control-label mr-sm-2'>Fin</label>
                                                    <input type='date' value='" . date('Y-m-d') . "' class='form-control mb-2 mr-sm-2' id='finfecha'>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-sm-6'>
                                                    <label for='ientrada' class='control-label mr-sm-2'>Entrada</label>
                                                    <input type='time' value='07:00' class='form-control mb-2 mr-sm-2' id='ientrada'>
                                                </div>
                                                <div class='col-sm-6'>
                                                    <label for='isalida' class='control-label mr-sm-2'>Salida</label>
                                                    <input type='time' value='15:00' class='form-control mb-2 mr-sm-2' id='isalida'>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-sm-3'>
                                                    <label for='turno' class='control-label mr-sm-3'>Turno:</label>
                                                    <input id='turno' name='turno' type='checkbox' onclick='check_turno(this)'></input>
                                                </div>
                                                <div class='col-sm-3'>
                                                    <label for='sturno' class='control-label mr-sm-3'>Sale de Turno:</label>
                                                    <input id='sturno' name='sturno' type='checkbox' onclick='check_sturno(this)'></input>
                                                </div>
                                                <div class='col-sm-6'>
                                                        <label for='fijo' class='control-label mr-sm-3'>Horario Fijo:</label>
                                                        <input id='fijo' name='fijo' type='checkbox' onclick='horario_fijo(this)'>
                                                        {$hfijos}
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-info btn-sm' onclick='save_horario()'><i class='fa fa-check'></i> Guardar </button>
                                    </div>
                                </div>
                            </div>
                                <div id='cambios' class='container tab-pane fade'>
                                    <div style='margin-top:5vh;'>
                                        <table id='tb_cambio' class='table table-sm table-bordered table-striped' width='100%'>
                                            <thead>
                                                <tr>
                                                <th class='text-center'>id_control</th>
                                                <th class='text-center'>Horario</th>
                                                <th class='text-center'>Entrada</th>
                                                <th class='text-center'>Salida</th>
                                                <th class='text-center'>Desde</th>
                                                <th class='text-center'>Hasta</th>
                                                <th class='text-center'>Días</th>
                                                <th class='text-center'>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id='body_table' class='text-center'>    
                                            </tbody>
                                        </table>
                                    </div>
                                    <script src='horarios/js/source_horario.js'></script>
                                    <script> datableEmpleado.init({$id_persona}, {$month}, {$year})</script>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>";
    echo $modal;
else :
    echo "<script type='text/javascript'>window.location='principal';</script>";
endif;


// <div class='row'>
//                                                 <div class='col-sm-12'>
//                                                 <label class='control-label'>Días para horario:</label>
//                                                 </div>
//                                                 </div>
//                                                 <div class='row'>
//                                                 <div class='col-sm-12'>
//                                                     <div class='btn-group' data-toggle='buttons'>
//                                                         <label class='btn btn-info' style='width:7rem' id='l1'>
//                                                         <input type='checkbox' name='weekdays' id='1' visibility: hidden> Lunes</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l2'>
//                                                         <input type='checkbox' name='weekdays' id='2' visibility: hidden> Martes</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l3'>
//                                                         <input type='checkbox' name='weekdays' id='3' visibility: hidden> Miércoles</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l4'>
//                                                         <input type='checkbox' name='weekdays' id='4' visibility: hidden> Jueves</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l5'>
//                                                         <input type='checkbox' name='weekdays' id='5' visibility: hidden> Viernes</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l6'>
//                                                         <input type='checkbox' name='weekdays' id='6' visibility: hidden> Sábado</label>
//                                                         <label class='btn btn-info' style='width:7rem' id='l7'>
//                                                         <input type='checkbox' name='weekdays' id='7' visibility: hidden> Domingo</label>
//                                                     </div>
//                                                 </div>
//                                             </div>