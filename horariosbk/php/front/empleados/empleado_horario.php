<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../horarios/php/back/functions.php';
  $id_persona = $_GET['id_persona'];
  $month = date("n");
  $year = date("Y");
  $option_year = "";
  $option_month = "";

  $array_mes = array("", 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
  for ($x = 1; $x <= 12; $x++) {
    if ($month == $x) {
      $option_month .= "<option value='{$x}' selected>{$array_mes[$x]}</option>";
    } else {
      $option_month .= "<option value='{$x}'>{$array_mes[$x]}</option>";
    }
  }


  $HORARIO = new Horario();
  $nombre = $HORARIO::get_name($id_persona);
  $array_dias = array("", 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
  $table = "
  
                <div class='modal-header'>
                <h3 class='modal-title'>Horarios para: " . strval($id_persona) . " " . strval($nombre['nombre']) . "</h5>
                <input type='hidden' id='naem' name='naem' value='" . strval($nombre['nombre']) . "'>
                <input type='hidden' id='dirf' name='dirf' value='" . strval($nombre['dir_funcional']) . "'>
                <input type='hidden' id='puesf' name='puesf' value='" . strval($nombre['p_funcional']) . "'>
                <input type='hidden' id='gaf' name='gaf' value='" . strval($id_persona) . "'>
                <ul class='list-inline ml-auto mb-0'>
                  <li class='list-inline-item'>
                      <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                          <i class='fa fa-times'></i>
                      </span>
                  </li>
                </ul>
              </div>
              <div class='modal-body'>
              <div class='row'>
              <div class='col-sm-7'>
              <b><label for='month'>Inicio</label></b>
                </div>
                <div class='col-sm-3'>
                <b><label for='month1'>Fin</label></b>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-sm-3'>
                    <div class='form-group'>
                        <label for='month'>Mes:</label>
                        <select id='month' class='form-control' onchange='refresh()'>
                        {$option_month}
                        </select>
                    </div>
                  </div>
                  <div class='col-sm-2'>
                    <div class='form-group'>
                        <label for='year'>Año:</label>
                        <select id='year' class='form-control' onchange='refresh()'>
                        <option value='2022'>2022</option>
                        <option value='2021'>2021</option>
                        <option value='2020'>2020</option>
                        </select>
                    </div>
                  </div>
                  <div class='col-sm-2'>
                  </div>
                  <div class='col-sm-3'>
                    <div class='form-group'>
                        <label for='month1'>Mes:</label>
                        <select id='month1' class='form-control' onchange='refresh()'>
                        {$option_month}
                        </select>
                    </div>
                  </div>
                  <div class='col-sm-2'>
                    <div class='form-group'>
                        <label for='year1'>Año:</label>
                        <select id='year1' class='form-control' onchange='refresh()'>
                        <option value='2022'>2022</option>
                        <option value='2021'>2021</option>
                        <option value='2020'>2020</option>
                        </select>
                    </div>
                  </div>
                </div>       
                <table id='tb_horario' class='table table-sm table-bordered table-striped' width='100%'>
                <thead>
                    <tr>
                        <th class='text-left'>Dia</th>
                        <th class='text-center'>Fecha</th>
                        <th class='text-center'>Entrada</th>
                        <th class='text-center'>Almuerzo</th>
                        <th class='text-center'>Salida</th>
                        <th class='text-center'>Horas laboradas</th>
                        <th class='text-center'>Control</th>
                        <th class='text-center'>Observaciones</th>
                        <th class='text-center'>Permisos</th>
                        <th class='text-center'>Horas Decimal</th>
                        <th class='text-center'>Horario Laboral</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th colspan='1' style='text-align:left'>Promedio horas laboradas:</th>
                    <th colspan='1' style='text-align:left'></th>
                    <th colspan='1' style='text-align:left'></th>
                    <th colspan='1' style='text-align:left'>Total de horas laboradas:</th>
                    <th colspan='1' style='text-align:left'></th>
                    <th colspan='1' style='text-align:left'></th>
                    <th colspan='1' style='text-align:left'>Días tarde:</th>
                    <th colspan='1' style='text-align:left'></th>
                    <th colspan='1' style='text-align:left'></th>
                    </tr>
                </tfoot>
                </table>
              </div>
              <script src='horarios/js/source_horario.js'></script>
              <script> datableEmpleado.init({$id_persona}, {$month}, {$year})</script>
              ";
  echo $table;
else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
