<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8686)){

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <style>
    .groupAsi {
      background: #656D78;
      color:#fff;
    }
    .groupAsi td{
      background: #656D78;
      color:#fff;
    }

    .card-cuopon::after {
      position: absolute;
      content: "";
      height: 40px;
      right: -20px;
      border-radius: 40px;
      z-index: 1;
      top: 70px;
      background-color: #fff;
      width: 40px;
      border-color: red green blue yellow;
    }

    .card-cuopon::before {
      position: absolute;
      content: "";
      height: 40px;
      left: -20px;
      border-radius: 40px;
      z-index: 1;
      top: 70px;
      background-color: #fff;
      width: 40px;
      border-color: red green blue yellow;
    }

    </style>

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>

    <!--<script src="viaticos/js/source_3.1.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>

    <!--<script src="viaticos/js/viatico_vue_1.3.js"></script>-->

    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="vehiculos/js/components.js"></script>
    <script src="documentos/js/components/components1.9.js"></script>
    <script src="vehiculos/js/validaciones_vue.js"></script>
    <script type="module" src="transportes/src/appTransporte.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>


    <script>

    $(document).ready(function(){
      /*setInterval(function(){
        show_nombramientos_pendientes_count();
      }, 5000);*/
    });
    </script>

    <div class="u-content">
        <div class="u-body" id="appTransporte">
            <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >{{ titulo }}</h2>
                    <!-- Tabs Nav -->
                    <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                      <li class="nav-item" @click="setTitle('Control de Transportes')">
                        <a href="#SolicitudesTransporte" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">Solicitudes</a>
                      </li>
                      <li class="nav-item" @click="setTitle('Control de Asignaciones')">
                        <a href="#AsignacionesTransporte" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Asignaciones</a>
                      </li>
                      <li class="nav-item" @click="setTitle('Vehiculos en comisión')">
                        <a href="#VehiculosComision" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Vehiculos</a>
                      </li>
                      <!--<li class="nav-item" @click="setTitle('Solicitud nueva')">
                        <a href="#appSolicitudTransporteNueva" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Nueva solicitud</a>
                      </li>-->
                      <li class="nav-item" @click="setTitle('Calcular total de cupones')">
                        <a href="#appCalculo" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Calcular cupones</a>
                      </li>

                      <!--<li class="nav-item">
                        <a href="#app_actas" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Actas</a>
                      </li>-->


                      <!--<li class="nav-item">
                             <a href="#app_compras" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Compras</a>
                           </li>-->
                      <?php //if (usuarioPrivilegiado()->hasPrivilege(302) || $u->accesoModulo(7851)) { ?>
                        <!--<li class="nav-item">
                          <a href="#app_facturas" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Facturas</a>
                        </li>-->
                      <?php //} ?>


                    </ul>
                    <!-- End Tabs Nav -->
                </header>

                <div class="card-body card-body-slide">
                  <!--{{ privilegios }}-->
                  <retornadireccion v-on:enviadireccion="getDireccionFromComponent"></retornadireccion>
                  <retornaprivilegios v-on:enviaprivilegios="getPrivilegiosFromComponent"></retornaprivilegios>
                  <div class="tab-content">
                    <div id="SolicitudesTransporte" class="tab-pane fade show active" role="tabpanel" style="min-width:100%">
                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro_transporte" hidden value="1"></input>
                        <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                          <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="id_year" name="id_year">
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023" selected>2023</option>
                            </select>
                          </div>
                          <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                        </div>

                        <table id="tb_transporte" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Correlativo </th>
                              <th class=" text-center" @change="eventHeader()"> </th>
                              <th class=" text-center">Fecha </th>
                              <th class=" text-center">Salida </th>
                              <th class=" text-center">Duración</th>
                              <th class=" text-center">Dirección</th>

                              <th class=" text-center">Solicitante</th>
                              <th class=" text-center">Destinos</th>
                              <th class=" text-center">Motivo</th>
                              <th class=" text-center">Estado</th>
                              <!--<th class=" text-center">Seguimiento</th>-->
                              <th class=" text-center">Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                      <button v-if="(privilegios.encargado_transporte == true || privilegios.jefe_transporte == true) && chequeados > 0" class="btn btn-sm btn-info" @click="showModal(0,2)"><i class="fa fa-check"></i> Asignar Vehículo</button>
                      <button v-if="(privilegios.dir_director == true) && chequeados > 0" class="btn btn-sm btn-info" @click="showModal(0,3)"><i class="fa fa-check"></i> Autorizar Solicitud</button>
                    </div>

                    <!-- inicio -->
                    <div id="AsignacionesTransporte" class="tab-pane fade slide_up_anim" role="tabpanel" style="min-width:100%">
                      <div class="table-responsive">
                        <div class="row">
                          <!--<asignacioneslist filtro="1" columna="col-sm-12" ancho="col-sm-3" titulo="Pendientes"></asignacioneslist>-->
                        </div>

                        <input type="text" id="id_tipo_filtro_asignacion" hidden value="1"></input>
                        <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                          <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="id_year_a" name="id_year_a">
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023" selected>2023</option>
                            </select>
                          </div>
                          <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                        </div>
                        <table id="tb_asignaciones" class="table table-actions table-striped table-bordered  " style="min-width:100%" >
                          <thead>
                            <tr>
                              <th class=" text-center">Correlativo </th>
                              <th class=" text-center">Estado </th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center">Vehículos </th>
                              <th class=" text-center">Direcciones </th>
                              <th class=" text-center">Destinos</th>
                              <th class=" text-center">Acción</th>
                              <!--<th class=" text-center">Duración</th>
                              <th class=" text-center">Dirección</th>

                              <th class=" text-center">Solicitante</th>
                              <th class=" text-center">Destinos</th>-->

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <div id="VehiculosComision" class="tab-pane fade slide_up_anim" role="tabpanel" style="min-width:100%">
                      <div class="table-responsive">
                        <div class="row">
                          <!--<asignacioneslist filtro="1" columna="col-sm-12" ancho="col-sm-3" titulo="Pendientes"></asignacioneslist>-->
                        </div>

                        <!--<input type="text" id="id_filtro_vehiculo" hidden value="1"></input>
                        <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                          <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="id_year_a" name="id_year_a">
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023" selected>2023</option>
                            </select>
                          </div>-->
                          <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                        <!--</div>-->
                        <table id="tb_vehiculos_comision" class="table table-actions table-striped table-bordered  " style="min-width:100%" >
                          <thead>
                            <tr>
                              <th class=" text-center">Placa </th>
                              <th class=" text-center">Vehículo </th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center">Vehículos </th>
                              <th class=" text-center">Direcciones </th>
                              <th class=" text-center">Destinos</th>
                              <th class=" text-center">Acción</th>
                              <!--<th class=" text-center">Duración</th>
                              <th class=" text-center">Dirección</th>

                              <th class=" text-center">Solicitante</th>
                              <th class=" text-center">Destinos</th>-->

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- fin -->
                    <div class="tab-pane fade slide_up_anim" id="appCalculo" role="tabpanel" style="min-width:100%">
                      <div class="row">
                        <div class="col-sm-6">

                            <div class="row">
                              <div class="text-right" class="col-sm-6">
                                <div class="form-group">
                                  <div class="">
                                    <div class="">
                                      <label class="text-white">..</label>
                                      <div class=" input-group  has-personalizado" >
                                        <label class="css-input switch switch-success"><input class="chequeado" id="rd_propios" v-model="picked" name="pago_opcional" type="radio" value="1144" @change="onChange($event)" checked/><span></span> Propios</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                              </div>
                              <div class="text-right" class="col-sm-6">
                                <div class="form-group">
                                  <div class="">
                                    <div class="">
                                      <label class="text-white">..</label>
                                      <div class=" input-group  has-personalizado" >
                                        <label class="css-input switch switch-success"><input class="chequeado" id="rd_externo" v-model="picked" name="pago_opcional" type="radio" value="1147" @change="onChange($event)"/><span></span> Arrendados</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            <vehiculoslist  codigo="id_vehiculo_" columna="col-sm-9" :evento="evento" requerido="true" :valor="arreglo.vehiculo_id" tipo="calculo"></vehiculoslist>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="">Preico del galón</label>
                                <input class="form-control form-control-sm" v-model="precioGalon" placeholder="Precio galón"/>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="">Kilometraje de ida</label>
                                <input class="form-control form-control-sm" v-model="kmIda" placeholder="Km ida"/>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="">Kilometraje imprevisto</label>
                                <input class="form-control form-control-sm" v-model="kmImprevisto" placeholder="Km imprevisto"/>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="">Km / por galón</label>
                                <input class="form-control form-control-sm" v-model="kmPorGalon" placeholder="Km por galón"/>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="">Capacidad del Tanque</label>
                                <input class="form-control form-control-sm" v-model="capacidadTanque" placeholder="Capacidad Tanque"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <!-- inicio -->
                          <div  class="card card-info cAsignacion " style="width:100%">
                            <div class="card-header">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="row">
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-hashtag" texto="Km (Ida) :" :dato="kmIda" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-calendar-check" texto="Km. ida y vuelta:" :dato="kmIdaVuelta" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-hashtag" texto="Km. imprevisto :" :dato="kmImprevisto" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-calendar-check" texto="Subtotal Km.:" :dato="subtotalRecorrido" tipo="1"></dato-persona>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card-body bg-light">
                              <!--inicio -->
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="row">
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-hashtag" texto="Kilómetros/galón:" :dato="kmPorGalon" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-calendar-check" texto="Capacidad Tanque:" :dato="capacidadTanque" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-hashtag" texto="Consumo (galones):" :dato="consumoGalones" tipo="1"></dato-persona>
                                    </div>
                                    <div class="col-sm-3">
                                      <dato-persona icono="fa fa-calendar-check" texto="Diferencia:" :dato="diferencia" tipo="1"></dato-persona>
                                    </div>
                                    <hr>
                                  </div>
                                </div>
                              </div>
                              <!-- fin -->
                            </div>
                            <div class="card-footer">
                              <div class="row">
                                <div class="col-sm-3">
                                  <dato-persona icono="fa fa-gas-pump" texto="Precio galón:" :dato="precioGalon" tipo="1"></dato-persona>
                                </div>
                                <div class="col-sm-3">
                                  <dato-persona icono="fa fa-calendar-check" texto="Subtotal:" :dato="subtTotal" tipo="1"></dato-persona>
                                </div>
                                <div class="col-sm-3">
                                  <dato-persona icono="fa fa-calendar-check" texto="Total real:" :dato="totalCupon" tipo="1"></dato-persona>
                                </div>
                                <hr>
                              </div>
                            </div>
                          </div>
                          <!-- fin -->
                      </div>
                    </div>

                  <iframe id="pdf_preview_v" hidden></iframe>

                </div>
                </div>
            </div>
          </div>
        </div>

    <?php
  }
}else{
    header("Location: index.php");
}
?>
