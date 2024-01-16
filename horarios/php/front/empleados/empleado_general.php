<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../horarios/php/back/functions.php';

  //$id_persona = $_POST['id_persona'];
  $month = $_POST['month'];
  $year = $_POST['year'];
  $dir = $_POST['dir'];
  // $option_year = "";
  // for ($i = 5; $i >= 0; $i--) {
  //   $tmp = $year - $i;
  //   $option_year .= "<option value='{$tmp}'>{$tmp}</option>";
  // }
  $array_dias = array("", 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
  $table = "<script src='horarios/js/source_general.js'></script>
  <script> datableEmpleado.init({$month}, {$year})</script><div class='modal-header'>
      <input type='hidden' id='dir' name='dir' value='".$dir."'>
                <h3 class='modal-title'>Horarios para: ".$dir."</h5>
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
                  <div class='col-sm-3'>
                    <div class='form-group'>
                        <label for='month'>Mes:</label>
                        <select id='month' class='form-control' onchange='refresh()'>
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
                        </select>
                    </div>
                  </div>
                  <div class='col-sm-3'>
                    <div class='form-group'>
                        <label for='year'>Año:</label>
                        <select id='year' class='form-control' onchange='refresh()'>
                        <option value='2024'>2024</option>
                        <option value='2023'>2023</option>
                        <option value='2022'>2022</option>
                        <option value='2021'>2021</option>
                        <option value='2020'>2020</option>
                        </select>
                    </div>
                  </div>
                </div>
                <table id='tb_general' class='table table-sm table-bordered table-striped' width='100%'>
                <thead>
                    <tr>
                        <th class='text-left'>ID</th>
                        <th class='text-left'>Dia</th>
                        <th class='text-center'>Fecha</th>
                        <th class='text-center'>Entrada</th>
                        <th class='text-center'>Almuerzo</th>
                        <th class='text-center'>Salida</th>
                        <th class='text-center'>Horas laboradas</th>
                        <th class='text-center'>Control</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
              </div>
              ";
  echo $table;
else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
