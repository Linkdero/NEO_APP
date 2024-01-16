<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if ($u->accesoModulo(8017)) { //privilegio documento
    date_default_timezone_set('America/Guatemala');

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <script src="assets/js/plugins/amcharts4/core.js"></script>
    <script src="assets/js/plugins/amcharts4/charts.js"></script>
    <script src="assets/js/plugins/amcharts4/themes/animated.js"></script>

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />
    <style>
    .DTFC_LeftBodyLiner {
      overflow: hidden!important;
      overflow-y: hidden!important;
    }
    .DTFC_RightBodyLiner {
      overflow: hidden!important;
      overflow-y: hidden!important;
    }
    div.dataTables_scrollBody{
      scroll-behavior: smooth;
    }

    .view {
  margin: auto;
  width: 100%;
}
.table-scroll {
  display: block;
  height: 500px;
  overflow-y: scroll;
}

.wrapper {
  position: relative;
  overflow: auto;
  white-space: nowrap;
}

.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white;
}

.first-col {
  width: 100%;
  min-width: 100px;
  left: 0px;
}

.second-col {
  width: 100%;
  min-width: 150px;
  left: 100px;
}

.s-header {
  z-index: 2;
}
.toolbar {
    float: left;
}
.dt-box {
  float: left;
  width: 50%;
}
.dataTables_wrapper .dataTables_filter {
  clear: both;
  float: left !important;
  margin-top: 5px;
}
.dataTables_wrapper .dataTables_length {
  text-align: left !important;
  float: left;
}


.tableFixHead          { overflow: auto; height: 20%; width: 240px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; background-color: #fff}
.tableFixHead tbody th { position: sticky; left: 0; }

    </style>
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script src="documentos/js/components/components1.9.js"></script>
    <script src="documentos/js/facturas/facturasvue1.6.js"></script>


    <script>



    </script>
    <div class="u-content">
      <div class="u-body" id="app_facturas">
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">

                <h2 v-if="privilegio.presupuesto_au==true && privilegio.facturas==false" class="h3 card-header-title">Control de Órdenes</h2>
                <h2 v-else class="h3 card-header-title">Control de Facturas</h2>

                <!-- Tabs Nav -->
                <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                  <li class="nav-item" v-if="privilegio.facturas == true">
                    <a href="#app_facturas1" class="nav-link" :class="[privilegio.facturas==true ? 'active' : '']" role="tab" aria-selected="true" data-toggle="tab">Facturas</a>
                  </li>
                  <li class="nav-item">
                    <a href="#tab_ordenes" class="nav-link" :class="[privilegio.presupuesto_au==true && privilegio.facturas==false ? 'active' : '']" role="tab" aria-selected="true" data-toggle="tab">Registro</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.facturas == true">
                    <a href="#tab_grafica" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Gráficas</a>
                  </li>

                </ul>
                <!-- End Tabs Nav -->
              </div>

              <div class="card-body">
                <privilegios-user v-on:privilegio_user="getPermisosUserF"></privilegios-user>
                <!--{{ privilegio }}-->
                <!-- Tabs Content -->

                <div class="tab-content">
                  <div class="tab-pane fade show slide_up_anim" id="app_facturas1" v-if="privilegio.facturas == true" :class="[privilegio.facturas ==true ? 'active' : '']" role="tabpanel">
                    <!--total: {{ totalChequeados }}
                    <br>
                    {{tipoBajaCuantia}}
              {{tipoNog}}-->
                    <input id="id_filtro_factura" value="0" hidden></input>
                    <button id="slideLeft" type="button" @click="move('left')" hidden>Slide left</button>
                    <button id="slideRight" type="button" @click="move('right')" hidden>Slide right</button>
                    <?php if($_SESSION['id_persona'] == 8747){?> <button class="btn btn-info btn-sm" type="button" @click="cargarInput(2)">Clase Proceso Ana</button> <?php }?>
                    <table id="tb_facturas" class="table table-actions table-striped table-bordered " width="100%" ref="infoBox" style="max-height:25%">
                      <thead>
                        <tr>
                          <th class=" text-center sticky-col first-col" @change="eventHeader()"></th>
                          <th class=" text-center sticky-col first-col" style="width:300px"> Estado</th>
                          <th class=" text-center">Dias</th>
                          <th class=" text-center">Tipo pago</th>
                          <th class=" text-center">Fecha recibido</th>
                          <th class=" text-center">Fecha</th>

                          <th class=" text-center">Serie</th>
                          <th class=" text-center">Número</th>
                          <th class=" text-center">Proveedor</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">PYR</th>
                          <th class=" text-center">Renglón</th>
                          <th class=" text-center">Dirección</th>
                          <th class=" text-center">Clase de Proceso</th>
                          <th class=" text-center">No. Orden</th>
                          <th class=" text-center">COM-DEV</th>

                          <th class=" text-center">NOG</th>
                          <th class=" text-center">NPG</th>

                          <th class=" text-center">Compromiso</th>
                          <th class=" text-center">Devengado</th>
                          <th class=" text-center">Cheque</th>
                          <th class=" text-center">Asignado</th>
                          <th class=" text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot id="myfoot" v-show="(tipoFiltroFa == 0  || tipoFiltroFa == 4) && privilegio.compras_recepcion == true">
                        <th><span class="btn btn-soft-info btn-sm" @click="cargarInput(4)">Enviar a Dirección <i class="fa fa-share"></i></span></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>


                      </tfoot>
                    </table>

                  </div>
                  <div class="tab-pane fade show slide_up_anim" id="tab_ordenes" :class="[privilegio.presupuesto_au==true && privilegio.facturas==false ? 'active' : '']" role="tabpanel" style="width:100%">
                    <input id="id_filtro_orden" value="0" hidden></input>
                    <table id="tb_ordenes" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center"></th>
                          <th class=" text-center">Clase de Proceso</th>
                          <th class=" text-center">Nro. de Orden</th>
                          <th class=" text-center">COM-DEV</th>
                          <th class=" text-center">CUR de compromiso</th>
                          <th class=" text-center">CUR de Devengado</th>
                          <th class=" text-center">Total</th>
                          <th class=" text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade show slide_up_anim" id="tab_grafica" role="tabpanel" v-if="privilegio.facturas ==true" style="width:100%">
                    <div class="row">
                      <div class="col-sm-6">
                        <h3 class="text-center">Bajas Cuantías</h3>
                        <div id="chart_facturas" style="width:100%;height:60vh;"></div>
                      </div>
                      <div class="col-sm-6">
                        <h3 class="text-center">NOG</h3>
                        <div id="chart_facturas_n" style="width:100%;height:60vh;"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Tabs Content -->
              </div>




              <!--<footer class="card-footer">
                      <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#">Link 1</a></li>
                        <li class="list-inline-item"><a href="#">Link 2</a></li>
                        <li class="list-inline-item"><a href="#">Link 3</a></li>
                      </ul>
                    </footer>-->
            </div>
          </div>
        </div>
      </div>
      <iframe id="pdf_preview_v" hidden></iframe>






  <?php
  }
} else {
  header("Location: index.php");
}
  ?>
