<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../horarios/php/back/functions.php';

    $id_persona = $_GET['id_persona'];
    $HORARIO = new Horario();
    $nombre = $HORARIO::get_name($id_persona);

    $table = "<div class='modal-header'>
                <h3 class='modal-title'>Periódos vacacionales para: ".strval($nombre['nombre'])."</h5>                
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
                </div>          
                <table id='tb_horario' class='table table-sm table-bordered table-striped' width='100%'>
                  <thead>
                    <tr>
                        <th class='text-center'>No. Boleta</th>
                        <th class='text-center'>Inicio</th>
                        <th class='text-center'>Fin</th>
                        <th class='text-center'>Presentarse</th>
                        <th class='text-center'>Periódo</th>
                        <th class='text-center'>Días Solicitados</th>
                        <th class='text-center'>Días Pendientes</th>
                        <th class='text-center'>Estado</th>
                    </tr>
                </thead>
                </table>
              </div>
              <script src='horarios/js/source_periodo.js'></script>
              <script> datableEmpleado.init({$id_persona})</script>";
    echo $table;
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
