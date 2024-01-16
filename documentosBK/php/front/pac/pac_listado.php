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

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">


    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script src="documentos/js/components/components1.4.js"></script>
    <script src="documentos/js/pac/pac.1.2.js"></script>


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
                <h2 class="h3 card-header-title">Control de Plan Anual de Compras</h2>

                <!-- Tabs Nav -->
                <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                  <li class="nav-item">
                    <a href="#panelWithTabsTab1" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">PAC</a>
                  </li>
                  <!--<li class="nav-item">
                    <a href="#app_pedido_nuevo" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Nuevo Plan</a>
                  </li>-->
                </ul>
                <!-- End Tabs Nav -->
              </div>

              <div class="card-body" id="app_pac">

                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active slide_up_anim" id="panelWithTabsTab1" role="tabpanel">
                    <!-- <h5 class="h4 card-title">Listado de pedidos</h5> -->
                    <?php
                    $up = usuarioPrivilegiado();
                    if ($up->hasPrivilege(302) && $up->hasPrivilege(325) || $up->hasPrivilege(301) && $up->hasPrivilege(325)) { ?>
                      <input type="text" id="id_tipo_filtro" value="0" hidden></input>

                      <div class="row" style="position:absolute; margin-top:-25px; z-index:5">
                        <div class="col-sm-4">
                          <div class="row">
                            <div class="col-sm-6">
                              <p id="fdir"><label><b>Dirección:</b></label><br></p>
                            </div>
                            <div class="col-sm-6">
                              <p id="fren"><label><b>Renglón:</b></label><br></p>
                            </div>
                          </div>
                        </div>

                        <!-- <div class="col-sm-3">
                          <p id="fnom"><label><b>Nombre:</b></label><br></p>
                        </div> -->
                      </div>

                      <!-- <div class="row" style="position: absolute; width:20rem; margin-top:-2rem;z-index:555">
                      <div class="col-sm-6">
                        <combo label="Dirección:" codigo="id_direccion" :arreglo="direcciones" tipo="3" requerido="true"></combo>
                      </div>
                    </div> -->
                    <?php } ?>
                    <table id="tb_pac" class="table table-actions table-striped table-bordered  " width="100%">
                      <thead>
                        <tr>
                          <th rowspan="2" class="text-center">Nombre</th>
                          <th rowspan="2" class="text-center">Descripción</th>
                          <th rowspan="2" class="text-center">Solicitante</th>
                          <th rowspan="2" class="text-center">Renglón</th>
                          <th rowspan="2" class="text-center">Ejercicio Anterior</th>
                          <th colspan="2" class="text-center">ENERO</th>
                          <th colspan="2" class="text-center">FEBRERO</th>
                          <th colspan="2" class="text-center">MARZO</th>
                          <th colspan="2" class="text-center">ABRIL</th>
                          <th colspan="2" class="text-center">MAYO</th>
                          <th colspan="2" class="text-center">JUNIO</th>
                          <th colspan="2" class="text-center">JULIO</th>
                          <th colspan="2" class="text-center">AGOSTO</th>
                          <th colspan="2" class="text-center">SEPTIEMBRE</th>
                          <th colspan="2" class="text-center">OCTUBRE</th>
                          <th colspan="2" class="text-center">NOVIEMBRE</th>
                          <th colspan="2" class="text-center">DICIEMBRE</th>
                          <th class=" text-center" rowspan="2">TOTAL</th>
                          <th class=" text-center" rowspan="2">ESTADO</th>
                        </tr>
                        <tr>
                          <!--<th class=" text-center">Nombre</th>
                          <th class=" text-center">Descripción</th>
                          <th class=" text-center">Solicitante</th>
                          <th class=" text-center">Renglón</th>
                          <th class=" text-center">Anterior</th>-->
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>

                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>

                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>
                          <th class=" text-center">Cantidad</th>
                          <th class=" text-center">Monto</th>


                          <!--<th class=" text-center">Solicitante</th>
                          <th class=" text-center">Asignado</th>
                          <th class=" text-center">Observaciones</th>
                          <th class=" text-center">Estado</th>
                          <th class=" text-center">Acción</th>-->
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>

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

                  <div class="tab-pane fade slide_up_anim" id="app_pedido_nuevo" role="tabpanel">
                    <div>
                      <form class="jsValidacionPacNuevo form-material" id="formValidacionPacNuevo">
                        <div class="row">

                          <campo row="col-sm-4" label="Nombre de la compra:" codigo="id_nombre" tipo="text" requerido="true"></campo>
                          <unidades columna="col-sm-4"></unidades>
                          <renglones-listado columna="col-sm-4"></renglones-listado>
                          <campo row="col-sm-12" label="Descripción del bien o servicio:" codigo="id_descripcion" tipo="textarea" requerido="true"></campo>
                          <!-- inicio -->

                          <div class="col-sm-3">
                            <div class="form-group">
                              <div class="">
                                <div class="row">
                                  <label for="id_ejercicio_ant">Ejercicio Anterior</label>
                                  <div class="input-group  has-personalizado">
                                    <label class="css-input input-sm switch switch-success"><input id="id_ejercicio_ant" name="id_ejercicio_ant" type="checkbox" v-model="ejercicioAnterior" /><span></span> <span id="lbl_rdrecibido"><small>Eventos multianuales</small></span></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin-->
                          <campo v-if="ejercicioAnterior == true" row="col-sm-3" label="Ejercicio Anterior:*" codigo="id_year_anterior" tipo="number" requerido="true"></campo>
                          <campo v-if="ejercicioAnterior == true" row="col-sm-6" label="Descripción ejercicio o NOG:" codigo="id_descripcion_year" tipo="text" requerido="true"></campo>
                          <meses-listado columna="col-sm-3" label="Mes de compra:*" v-on:send_months="recibirMeses"></meses-listado>
                          <!--inicio-->
                          <div class="col-sm-12">
                            <button class="btn btn-sm btn-block btn-info" @click="guardarNuevoPac()"><i class="fa fa-sync"></i> Guardar</button>
                          </div>
                          <!-- fin -->
                        </div>

                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Tabs Content -->
              </div>
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
