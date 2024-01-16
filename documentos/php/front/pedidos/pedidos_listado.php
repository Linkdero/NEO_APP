<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if ($u->accesoModulo(8017)) { //privilegio documento
    date_default_timezone_set('America/Guatemala');
    $u = usuarioPrivilegiado();

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">
    <style>
    .DTFC_RightBodyLiner {
      overflow: hidden!important;
      overflow-y: hidden!important;
    }
    </style>



    <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script src="documentos/js/components/sendMail.js"></script>
    <script src="documentos/js/components/components1.16.js"></script>
    <script src="documentos/js/pedidos/viewModelPedidos.1.3.js"></script>
    <script src="assets/js/plugins/push/push.min.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>
    <script src="assets/js/plugins/jspdf/documentos/impresiones_1.5.js"></script>

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
                    <a href="#panelWithTabsTab1" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">Pedidos</a>
                  </li>
                  <li class="nav-item">
                    <a href="#app_pedido_nuevo" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Nuevo pedido</a>
                  </li>
                  <?php if ($u->hasPrivilege(302)){?>
                  <li class="nav-item">
                    <a href="#app_actas" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Actas</a>
                  </li>
                  <?php }?>
                  <!--<li class="nav-item">
                         <a href="#app_compras" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Compras</a>
                       </li>-->
                  <?php //if (usuarioPrivilegiado()->hasPrivilege(302) || $u->accesoModulo(7851)) { ?>
                    <!--<li class="nav-item">
                      <a href="#app_facturas" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Facturas</a>
                    </li>-->
                  <?php //} ?>
                  <li class="nav-item">
                    <a href="#app_reporte" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Reporte</a>
                  </li>

                </ul>
                <!-- End Tabs Nav -->
              </div>

              <div class="card-body" id="app_pedidos">

                <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>
                <pedido-notificaciones></pedido-notificaciones>

                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active slide_up_anim" id="panelWithTabsTab1" role="tabpanel">
                    <!-- <h5 class="h4 card-title">Listado de pedidos</h5> -->

                    <input type="text" id="id_tipo_filtro" value="0" hidden></input>

                    <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                      <div class="col-sm-6">
                        <select class="form-control form-control-sm" id="id_year" name="id_year">
                          <option value="2020">2020</option>
                          <option value="2021">2021</option>
                          <option value="2022">2022</option>
                          <option value="2023" >2023</option>
                          <option value="2024" selected>2024</option>
                        </select>
                      </div>
                      <!--<div class="col-sm-6">
                        <p id="estado"><label><b>Estado:</b></label><br></p>
                      </div>-->
                    </div>
                    <table id="tb_pedidos" class="table table-actions table-striped table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center">Pedido</th>
                          <th class=" text-center">Urgente</th>
                          <th class=" text-center">PAC</th>
                          <th class=" text-center">Fecha</th>
                          <th class=" text-center">Solicitante</th>
                          <th class=" text-center">Asignado</th>
                          <th class=" text-center">Observaciones</th>
                          <th class=" text-center">Estado</th>
                          <th class=" text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div class="tab-pane fade slide_up_anim" id="app_pedido_nuevo" role="tabpanel">
                    <!--<div class="u-icon u-icon--sm rounded-circle bg-info text-white mr-3">
                      <i class="fa fa-plus"></i>
                    </div> Pedido Nuevo
                    <div class="row">

                    </div>-->

                    <div>

                      <form class="jsValidacionPedidoNuevo form-material">
                        <div class="row">

                          <!-- inicio -->
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="col-sm-2">
                                <div class="form-group">
                                  <div class="">
                                    <div class="row">
                                      <label for="id_ejercicio_ant">Tiene Plan de Compra</label>
                                      <div class="input-group  has-personalizado">
                                        <label class="css-input input-sm switch switch-success"><input id="id_pac" name="id_pac" type="checkbox" v-model="pacActivo" @change="validarPac" /><span></span> <span id="lbl_rdrecibido"><small>{{ msgPac }}</small></span></label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- fin-->
                              <!--inicio-->
                              <div v-show="pacActivo == true" class="col-sm-10">
                                <div class="form-group">
                                  <div class="">
                                    <label for="id_unidad">Plan de Compra*</label>
                                    <div class=" input-group  has-personalizado">
                                      <div class=" input-group  has-personalizado">
                                        <select class="pacName form-control" style="width:100%" id="pacName" name="pacName" onclick="valorSeleccionado()" :required="pacActivo == true"></select>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- fin -->
                              <!-- inicio -->

                              <div  v-if="pac_id > 0" class="col-sm-12 bg-soft-info">
                                <br>
                                <div class="col-sm-12"><h3>Detalle del Plan de compra</h3></div>
                                <hr>
                                <pac-detalle v-if="pac_id > 0" :pac_id="pac_id"></pac-detalle>
                              </div>

                              <!-- fin -->
                            </div>
                            <hr>
                          </div>

                          <!--<campo row="col-sm-4" label="No. del Pedido:*" codigo="pedido_nro" tipo="number" requerido="true"></campo>-->
                          <campo row="col-sm-6" label="Fecha del pedido:*" codigo="fecha_pedido" tipo="date" requerido="true"></campo>
                          <unidades columna="col-sm-6"></unidades>

                          <!--inicio-->
                          <div class="col-sm-10">
                            <div class="form-group">
                              <div class="">
                                <div class="">
                                  <label for="id_pedido">Insumo</label>
                                  <div class=" input-group  has-personalizado">
                                    <select class="categoryName form-control" style="width:100%" id="Ppr_id" name="categoryName"></select>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin -->
                          <!--inicio-->
                          <div class="col-sm-2">
                            <div class="form-group">
                              <div class="form-material">
                                <label>Agregar insumo</label>
                                <span class="btn btn-sm btn-soft-info btn-block" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Agregar</span>
                              </div>

                            </div>

                          </div>
                          <!-- fin -->


                          <!--inicio tabla-->
                          <div class="col-sm-12">
                            <label for="">Insumos*</label>
                            Insumos agregados: {{ insumos.length }}
                            <porcentaje-insumos-pedido :total="totalLineas"></porcentaje-insumos-pedido>
                            <table class="table table-sm table-bordered table-striped">
                              <thead>
                                <th class="text-center">Renglón</th>
                                <th class="text-center">Insumo</th>
                                <th class="text-center">Cod.Pre.</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Pres.</th>
                                <th class="text-center">Med</th>

                                <th class="text-center">Cuatrimestre</th>
                                <th class="text-center">Gastado</th>

                                <th class="text-center">Cantidad</th>
                                <th class="text-center" width="120px">Acción <span class="btn btn-sm btn-danger" v-if="insumos.length > 0" @click="limpiar_lista()"><i class="fa fa-trash-alt"></i></span></th>
                              </thead>
                              <tbody>

                                <tr v-for='(i, index) in insumos' :key="index">
                                  <td class="text-center">{{ i.Ppr_Ren }}</td>
                                  <td class="text-center">{{ i.Ppr_cod }}</td>
                                  <td class="text-center">{{ i.Ppr_codPre }}</td>
                                  <td class="text-center">{{ i.Ppr_Nom }}</td>
                                  <td class="text-justify">{{ i.Ppr_Des }}</td>
                                  <td class="text-center">{{ i.Ppr_Pres }}</td>
                                  <td class="text-center">{{ i.Ppr_Med }}</td>

                                  <td class="text-center">{{ i.cuatrimestre }}</td>
                                  <td class="text-center">{{ i.gastado }}</td>

                                  <td width="120px">
                                    <div class="form-group" style="margin-bottom:0rem">
                                      <div class="">
                                        <div class="">
                                          <input :name="'i'+index" :id="'i'+index" class="form-control input-sm" v-model="i.Ppr_can" type="number" min="1" autocomplete="off" required></input>
                                        </div>
                                      </div>
                                    </div>
                                  </td>

                                  <td scope="row" class="trashIconContainer text-center">
                                    <span class="btn btn-sm btn-danger" @click="deleteRow(index, i, i.Ppr_lineas)"><i class="far fa-trash-alt"></i></span>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <!-- fin tabla -->
                          <!--inicio-->
                          <div class="col-sm-12">
                            <div class="form-group">
                              <div class="">
                                <div class="">
                                  <label for="id_descripcion">Justificación (necesidad, finalidad y temporalidad)* Le quedan {{ totalCharacter }} caracteres</label>
                                  <div class=" input-group  has-personalizado">
                                    <textarea rows="2" oninput="this.value = this.value.toUpperCase()" maxlength="500" class="js-select2 form-control form-control-sm" id="id_observaciones" name="id_observaciones" required v-model='messageCharacter' @keyup='charCount()'>

                                    </textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin -->
                          <!-- inicio -->
                          <div class="col-sm-12">
                            <div class="form-group">
                              <div class="">
                                <div class="row">
                                  <label for="id_urgente">Es urgente (Seleccionar solo si es realmente urgente)</label>
                                  <div class="input-group  has-personalizado">
                                    <label class="css-input input-sm switch switch-success"><input id="id_urgente" name="id_urgente" type="checkbox" v-model="isUrgente" @change="validarUrgente" /><span></span> <span id="lbl_rdrecibido"><small>{{ msgUrgente }}</small></span></label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin -->

                          <!--inicio-->
                          <div class="col-sm-12">
                            <button class="btn btn-sm btn-block btn-info" @click="guardarPedidoNuevo()"><i class="fa fa-sync"></i> Guardar</button>
                          </div>
                          <!-- fin -->
                        </div>

                      </form>
                    </div>

                  </div>
                  <div class="tab-pane fade slide_up_anim" id="app_compras" role="tabpanel">
                    <div class="row">

                      <div class="col-sm-12">
                        <table id="tb_compras" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Nro. Compra</th>
                              <th class=" text-center">Año</th>
                              <th class=" text-center">Tipo</th>
                              <th class=" text-center">Pedidos</th>
                              <th class=" text-center">Estado</th>
                              <th class=" text-center">NOG</th>
                              <th class=" text-center">CUR</th>
                              <th class=" text-center">CHEQUE</th>
                              <th class=" text-center">Acción</th>

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade slide_up_anim" id="app_actas" role="tabpanel">
                    <div class="row">
                      <!--<div class="col-sm-3" style=" z-index:2; position:absolute">
                        <input id="fecha_fac" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" @change="filtarFacturas()" type="date"></input>
                        <br><br>
                      </div>-->
                      <div class="col-sm-12">

                        <input id="id_filtro_actas" value="0" hidden></input>
                        <table id="tb_actas" class="table table-actions table-striped table-bordered " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Acta</th>
                              <th class=" text-center">PYR</th>
                              <th class=" text-center">Tipo</th>
                              <th class=" text-center">Pago</th>
                              <th class=" text-center">Monto</th>
                              <th class=" text-center">Director</th>
                              <th class=" text-center">Jefe</th>
                              <th class=" text-center">Técnico</th>
                              <th class=" text-center">Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade slide_up_anim" id="app_reporte" role="tabpanel">
                    <div class="row" style=" width:30rem; height:35px">
                      <div class="col-sm-4" >
                        <input id="fecha_r" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date"></input>
                      </div>
                      <div class="col-sm-4">
                        <input id="fecha_r2" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date"></input>
                      </div>
                      <?php if ($u->hasPrivilege(301)) { ?>
                        <div class="col-sm-4" >
                          <select id="tipo_filtro" class="form-control form-control-sm" v-model="tipoFiltroR">
                            <option value="0">Seleccionar</option>
                            <option value="1">Planificación</option>
                            <option value="2">subsecretaría</option>
                            <option value="3">Compras</option>
                          </select>
                          <br><br>
                        </div>
                      <?php } ?>

                    </div>
                    <div class="row">

                      <div class="col-sm-12">
                        <table id="tb_reporte" class="table table-actions table-striped table-bordered" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Pedido</th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center">Dir.</th>
                              <th class=" text-center">Recibido</th>
                              <th class=" text-center">Devuelto</th>
                              <th class=" text-center">Justificación</th>
                              <th class=" text-center">Renglón</th>
                              <th class=" text-center">Insumos</th>
                              <th class=" text-center">Cant.</th>
                              <th class=" text-center">Estado</th>
                              <th class=" text-center">Motivo</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
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
