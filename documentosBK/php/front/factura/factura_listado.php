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

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />




    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script src="documentos/js/components/components1.4.js"></script>
    <script src="documentos/js/facturas/facturasvue1.2.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/documentos/impresiones_1.1.js"></script>

    <script>



    </script>
    <div class="u-content">
      <div class="u-body" id="">
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-md-12 mb-12 mb-md-0">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Control de Pedidos</h2>

                <!-- Tabs Nav -->
                <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                  <li class="nav-item">
                    <a href="#app_facturas1" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">Facturas</a>
                  </li>
                  <li class="nav-item">
                    <a href="#tab_ordenes" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Registro</a>
                  </li>

                </ul>
                <!-- End Tabs Nav -->
              </div>

              <div class="card-body" id="app_facturas">
                <!--{{ tipoBajaCuantia }}
                {{ tipoNog }}-->
                <privilegios-user v-on:privilegio_user="getPermisosUserF"></privilegios-user>
                <!--{{ privilegio }}-->
                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="app_facturas1" role="tabpanel">
                    <div class="row">
                      <!--<div class="col-sm-3" style=" z-index:2; position:absolute">
                        <input id="fecha_fac" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" @change="filtarFacturas()" type="date"></input>
                        <br><br>
                      </div>-->
                      <div class="col-sm-12">
                        <input id="id_filtro_factura" value="0" hidden></input>
                        <table id="tb_facturas" class="table table-actions table-striped table-bordered " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Tipo pago</th>
                              <th class=" text-center">Fecha recibido</th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center" style="width:300px"></th>
                              <th class=" text-center">Serie</th>
                              <th class=" text-center">Número</th>
                              <th class=" text-center">Proveedor</th>
                              <th class=" text-center">Monto</th>
                              <th class=" text-center">PYR</th>
                              <th class=" text-center"  style="width:300px">Clase de Proceso</th>
                              <th class=" text-center">No. Orden</th>
                              <th class=" text-center">CYD</th>

                              <th class=" text-center">NOG</th>

                              <th class=" text-center">Compromiso</th>
                              <th class=" text-center">Devengado</th>
                              <th class=" text-center">Cheque</th>
                              <th class=" text-center">Asignado</th>
                              <th class=" text-center">Acción</th>
                              <th class=" text-center"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="tab_ordenes" role="tabpanel">
                    <div class="row">
                      <!--<div class="col-sm-3" style=" z-index:2; position:absolute">
                        <input id="fecha_fac" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" @change="filtarFacturas()" type="date"></input>
                        <br><br>
                      </div>-->
                      <div class="col-sm-12">
                        <input id="id_filtro_orden" value="0" hidden></input>
                        <table id="tb_ordenes" class="table table-actions table-striped table-bordered " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Clase de Proceso</th>
                              <th class=" text-center">Nro. de Orden</th>
                              <th class=" text-center">CYD</th>
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
