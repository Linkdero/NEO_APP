
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8085)){
?>

<!-- Resources -->
<script src="assets/js/plugins/amcharts4/core.js"></script>
<script src="assets/js/plugins/amcharts4/charts.js"></script>
<script src="assets/js/plugins/amcharts4/themes/animated.js"></script>
<link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
<script src="reportes/js/functions.js"></script>
<script src="assets/js/pages/fechas.js"></script>
  <div class="card col-sm-12">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" id="graph-list" role="tablist">
        <!--<?php if(usuarioPrivilegiado()->hasPrivilege(278)): ?>-->
          <li class="nav-item">
            <a class="nav-link" href="#puertas" role="tab" aria-controls="puertas" aria-selected="true">Control Puertas</a>
          </li>
        <!--<?php endif; ?>-->
        <!--<?php if(usuarioPrivilegiado()->hasPrivilege(87)): ?>-->
          <li class="nav-item">
            <a class="nav-link"  href="#rrhh" role="tab" aria-controls="rrhh" aria-selected="true">RRHH</a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="#puestos" role="tab" aria-controls="puestos" aria-selected="true">Puestos</a>
          </li>
        <!--<?php endif; ?>-->
        <!--<?php if(usuarioPrivilegiado()->hasPrivilege(285)): ?>-->
          <li class="nav-item">
            <a class="nav-link" href="#noticias" role="tab" aria-controls="noticias" aria-selected="true">Noticias</a>
          </li>
        <!--<?php endif; ?>-->
        <!--<?php if(usuarioPrivilegiado()->hasPrivilege(285)): ?>-->
          <li class="nav-item">
            <a class="nav-link" href="#viaticos" role="tab" aria-controls="viaticos" aria-selected="true">Viáticos</a>
          </li>
        <!--<?php endif; ?>-->
          <li class="nav-item">
            <a class="nav-link" href="#insumos" role="tab" aria-controls="insumos" aria-selected="true">Insumos</a>
          </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content mt-3">
        <?php //if(usuarioPrivilegiado()->hasPrivilege(278)):
          $visita = new visita;
      $puertas = $visita->get_puertas();
          ?>
          <script>
          $(document).ready(function(){
            establecer_fecha('ini_p',2);
            establecer_fecha('fin_p',2);
          });

          $.ajax({
              type: "POST",
              url: "quinta/php/back/empleado/get_oficina.php",
              dataType: 'html',
              data: { id_puerta: 0 },
              success:function(data) {
                  $("#oficina_visita_").html(data);
              }
          });
          </script>
          <div class="tab-pane" id="puertas" role="tabpanel">
            <div class="tab-pane" id="visitas" role="tabpanel">
              <div class="row" style="width:100%">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-2>">
                      <div class="form-group ">
                        <label for="oficina_visita">Inicio:</label>
                        <span class="form-icon-wrapper" style="z-index:55;">
                          <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                          </span>
                          <input id="ini_p" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                        </div>
                      </div>
                      <div class="col-sm-2>">
                        <div class="form-group ">
                          <label for="oficina_visita">Final:</label>
                          <span class="form-icon-wrapper" style="z-index:55;">
                            <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                            </span>
                            <input id="fin_p" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value=""  data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                          </div>
                        </div>
                          <div class="col-sm-2" style="z-index:55;">
                            <div class="form-group">
                                <div class="">
                                    <label for="oficina_visita">Oficina que Visita:</label>
                                    <select id="oficina_visita_" class="form-control" >

                                    </select>

                                </div>
                            </div>
                          </div>
                          <div class="col-sm-2" style="z-index:55;">
                            <div class="form-group">
                                <div class="">
                                    <label for="puerta_">Puerta:</label>
                                    <select id="puerta_" class="form-control" >
                                      <option value="0">Todos</option>
                                      <?php
                                      foreach($puertas as $p){
                                        echo '<option value="'.$p['id_puerta'].'"';

                                        echo '>'.$p['nombre_puerta'].'</option>';
                                      }
                                      ?>
                                    </select>

                                </div>
                            </div>
                          </div>
                          <div class="col-sm-1" style="z-index:55;">
                            <div class="form-group">
                                <div class="">
                                    <label for="no_salido">No ha salido:</label>
                                    <select id="no_salido" class="form-control" >
                                      <option value="0">Todos</option>
                                      <option value="1">No ha salido</option>
                                    </select>

                                </div>
                            </div>
                          </div>
                        <div class="col-sm-2"style="z-index:55;">
                          <button class="btn btn-info " style="margin-top:30px" onclick="recargar_puertas()"><i class="fa fa-sync"></i></button>
                        </div>
                      </div>

                    </div>
                  </div>
              <div class="row">
                <div class="col-sm-12">
                  <input id="all_doors" value="true" type="hidden">
                  <div id="chart_all_doors" style="width:100%;height:50vh;"></div>
                </div>
                <div class="col-sm-12">
                  <input id="date_door" value="true" type="hidden">
                  <div id="chart_door" style="width:100%;height:50vh;"></div>
                </div>
              </div>
            </div>
          </div>
        <?php //endif; ?>
        <?php //if(usuarioPrivilegiado()->hasPrivilege(87)): ?>
          <div class="tab-pane" id="rrhh" role="tabpanel" aria-labelledby="rrhh-tab">
            <div class="row">
              <div class="col-sm-12">
                  <input id="all_jobs" value="true" type="hidden">
                  <div id="chart_all_jobs" style="width:100%;height:50vh;"></div>
              </div>
              <div class="col-sm-12">
                  <input id="job" value="true" type="hidden">
                  <div id="chart_job" style="width:100%;height:50vh;"></div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="puestos" role="tabpanel" aria-labelledby="puestos-tab">
            <div class="row">
              <div class="col-sm-12">
                  <input id="all_puestos" value="true" type="hidden">
                  <div id="chart_all_puestos" style="width:100%;height:50vh;"></div>
              </div>
              <div class="col-sm-12">
                  <input id="job" value="true" type="hidden">
                  <div id="chart_job" style="width:100%;height:50vh;"></div>
              </div>
            </div>
          </div>
        <?php //endif; ?>
        <?php //if(usuarioPrivilegiado()->hasPrivilege(285)):
          $redes_ = array();
          $redes_ = noticia::get_redes_sociales();
        ?>
          <div class="tab-pane" id="noticias" role="tabpanel" aria-labelledby="noticias-tab">
            <div class="row" style="width:100%">
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-2>">
                    <div class="form-group ">
                      <label for="oficina_visita">Inicio:</label>
                      <span class="form-icon-wrapper" style="z-index:55;">
                        <input id="ini_" class="form-control" type="date" value="<?php echo date('Y-m-d', time()); ?>"></input>
                      </div>
                    </div>
                    <div class="col-sm-2>">
                      <div class="form-group ">
                        <label for="oficina_visita">Final:</label>
                        <span class="form-icon-wrapper" style="z-index:55;">
                          <input id="fin_" class="form-control" type="date" value="<?php echo date('Y-m-d', time()); ?>"></input>                        </div>
                      </div>
                      <div class="col-sm-3" style="z-index:55;">
                        <div class="form-group">
                            <div class="">
                                <label for="redes__">Red Social:</label>
                                <select id="redes__" class="form-control" >
                                  <option value="0">Todas las redes</option>
                                  <?php
                                  foreach($redes_ AS $r){
                                    echo '<option value="'.$r['id_item'].'">'.$r['descripcion'].'</option>';
                                  }
                                  ?>
                                </select>
                            </div>
                        </div>
                      </div>
                      <div class="col-sm-3" style="z-index:55;">
                        <div class="form-group">
                            <div class="">
                                <label for="categoria__">Categoria:</label>
                                <select id="categoria__" class="form-control" >
                                  <option value="0">Todos</option>
                                  <option value="1">Positiva</option>
                                  <option value="2">Negativa</option>
                                </select>
                            </div>
                        </div>
                      </div>
                      <div class="col-sm-2"style="z-index:55;">
                        <button class="btn btn-info " style="margin-top:30px" onclick="recargar_noticias()"><i class="fa fa-sync"></i></button>
                      </div>
                    </div>

                  </div>
                </div>
            <div class="row">
              <div class="col-sm-6">
                <input id="all_notices" value="true" type="hidden">
                <div id="chart_all_notice" style="width:100%;height:60vh;"></div>
              </div>
              <div class="col-sm-6">
                <input id="notice_user" value="true" type="hidden">
                <div id="chart_notice" style="width:100%;height:60vh;"></div>
              </div>
            </div>
          </div>
        <?php //endif; ?>
        <?php //if(usuarioPrivilegiado()->hasPrivilege(87)): ?>
          <div class="tab-pane" id="viaticos" role="tabpanel" aria-labelledby="viaticos-tab">
            <!-- inicio -->
            <div class="row">

              <div class="col-sm-2" style="z-index:55;">
                <div class="form-group">
                  <div class="">
                    <label for="id_tipo">Tipo de Viático:</label>
                    <select id="id_tipo" class="js-select2 form-control" onchange="recargar_viaticos()">
                      <!--<option value='0'>Todos</option>-->
                      <option value='1'>Nacionales</option>
                      <option value='2'>Internacionales</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-sm-2" style="z-index:55;">
                <div class="form-group">
                  <div class="">
                    <label for="id_mes">Mes:</label>
                    <select id="id_mes" class="js-select2 form-control" onchange="recargar_viaticos()">
                      <?php
                      for ($x=1; $x<=12; $x++) {
                        if ($x == date('m'))
                        echo '<option value="'.$x.'">'.User::get_nombre_mes($x).'</option>';
                        else
                        echo '<option value="'.$x.'">'.User::get_nombre_mes($x).'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-sm-1" style="z-index:55;">
                <div class="form-group">
                  <div class="">
                    <label for="id_year">Año:</label>
                    <select id="id_year" class="js-select2 form-control" onchange="recargar_viaticos()">
                      <?php
                      for($i=date('o'); $i>=2017; $i--){
                        if ($i == date('o'))
                        echo '<option value="'.$i.'" >'.$i.'</option>';
                        else
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>


            <!-- fin -->
            <div class="row">
              <div class="col-sm-12">
                  <input id="all_viaticos" value="true" type="hidden">
                  <div id="chart_all_viaticos" style="width:100%;height:50vh;"></div>
              </div>
              <!--<div class="col-sm-12">
                  <input id="viatico" value="true" type="hidden">
                  <div id="chart_viaticos" style="width:100%;height:50vh;"></div>
              </div>-->
            </div>
          </div>
        <?php //endif; ?>
        <!--inicio-->
        <?php //if(usuarioPrivilegiado()->hasPrivilege(235)): ?>
          <div class="tab-pane" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
            <div class="row">
              <div class="col-sm-3" style="z-index:55;">
                <div class="form-group">
                    <div class="">
                        <label for="bodega_">Bodega:</label>
                        <select id="id_bodega" class="form-control" onchange="recargar_insumos()">
                          <option value="3552">Radios</option>
                          <option value="5066">Armería</option>
                          <option value="5907">Móviles</option>
                        </select>
                    </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <input id="all_insumos" value="true" type="hidden">
                <div id="chart_all_insumos" style="width:100%;height:50vh;">

                </div>
              </div>
            </div>
          </div>

        <?php //endif; ?>
        <!-- fin -->
      </div>
    </div>
  </div>

<script src="reportes/js/cargar.js"></script>
  <?php
}
 }else{
    header("Location: index.php");
}
?>
