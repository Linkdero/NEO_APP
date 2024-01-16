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
    <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
    <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>

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
    .select2-dropdown {
      z-index: 1061;
    }



    .table-f thead tr > th:first-child {
      position:sticky;
      left:0;
      z-index:0;
      background-color:white;
    }

    .table-f tbody tr > td:first-child {
      position:sticky;
      left:0;
      z-index:1;

    }
    .table-f thead tr > th:nth-child(2)  {
      position:sticky;
      left:60px;
      z-index:0;
      background: white;
      box-shadow: 10px 5px 5px 0px rgba(67, 74, 84, 0.4);
    }

    .table-f tbody tr > td:nth-child(2){
      position:sticky;
      left:60px;
      z-index:1;
      box-shadow: 10px 5px 5px 0px rgba(67, 74, 84, 0.4);
    }


    .table-ff thead tr > th:first-child {
      position:sticky;
      left:0;
      z-index:0;
      background-color:white;
    }

    .table-ff tbody tr > td:first-child {
      position:sticky;
      left:0;
      z-index:1;

    }
    .table-ff thead tr > th:nth-child(1)  {
      position:sticky;
      left:0px;
      z-index:0;
      background: white;
      box-shadow: 10px 5px 5px 0px rgba(67, 74, 84, 0.4);
    }

    .table-ff tbody tr > td:nth-child(1){
      position:sticky;
      left:0px;
      z-index:1;
      box-shadow: 10px 5px 5px 0px rgba(67, 74, 84, 0.4);
    }
    .table-ff tbody tr:nth-child(odd) td {
    background: #ffffff;
    }
    .table-ff tbody tr:nth-child(even) td {
    background: #edf2f7;
    }


    .tableFixHead th {
      position: sticky;
      top: 0;
      background: #eee;
      z-index:-10
    }
    .table-f tbody tr:nth-child(odd) td {
    background: #ffffff;
    }
    .table-f tbody tr:nth-child(even) td {
    background: #edf2f7;
    }

    .second-column {
      box-shadow: 10px 5px 5px 0px rgba(67, 74, 84, 0.4);
    }

    .table-f tbody tr.danger{
      background: #dc3545;
    }

    .ancho-fecha {
      width: 1800px
    }

    </style>
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script src="documentos/js/components/components1.16.js"></script>
    <script src="documentos/js/facturas/viewModelFacturas1.6.js"></script>


    <script>



    </script>
    <div class="u-content">
      <div class="u-body" id="app_facturas">
        <input id="idopcion" name="idopcion" value="0" hidden></input>

        <!--{{ arregloOrdenes }}-->
        <br>
        <!--{{ privilegio }}-->
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">

                <!--<h2 v-if="privilegio.presupuesto_au==true && privilegio.facturas==false" class="h3 card-header-title">Control de Órdenes</h2>
                <h2 v-else-if="privilegio.presupuesto_au!=true && privilegio.facturas!=false && opcion != 1" class="h3 card-header-title">Control de Facturas</h2>-->

                <div v-show="opcion == 0">
                  <div class="col-sm-12 text-center">
                    <div class="spinner-grow  text-info" role="status" >
                      <span class="sr-only">Cargando facturas</span>
                    </div>
                  </div>
                </div>

                <div id="" v-show="(privilegio.facturas==true) && opcion == 1 " style="position: absolute" >
                  <div id="botonesF" class=" btn-group-toggle btn-group text-left" data-toggle="buttons" style="width:100%" hidden>
                    <a v-if="privilegio.compras_recepcion == true || privilegio.tesoreria_recepcion == true || privilegio.compras_tecnico == true" data-tooltip="Agregar" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-add-fact text-left" tabindex="0" aria-controls="tb_facturas" @click="addInvoice(1,'','')"><i class="fa fa-plus-circle"></i></a>

                    <label data-tooltip="Pendientes" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-fact btn-f1 active" checked @click="recargarFacturas(0,'btn-f1')">
                      <input type="radio" name="option11" id="option11" autocomplete="off" checked ><i class="fa fa-hourglass-start"></i></input>
                    </label>
                    <label data-tooltip="Publicadas" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-fact btn-f5" @click="recargarFacturas(4,'btn-f5')">
                      <input type="radio" name="option22" id="option22" autocomplete="off" ><i class="fa fa-upload"></i></input>
                    </label>
                    <label data-tooltip="Compromiso" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-fact btn-f2" @click="recargarFacturas(1,'btn-f2')">
                      <input type="radio" name="option33" id="option33" autocomplete="off" ><i class="fa fa-handshake"></i></input>
                    </label>
                    <label data-tooltip="Devengado" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-fact btn-f3" @click="recargarFacturas(2,'btn-f3')">
                      <input type="radio" name="option44" id="option44" autocomplete="off" ><i class="fa fa-hand-holding-usd"></i></input>
                    </label>
                    <label data-tooltip="Anuladas" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-fact btn-f4" @click="recargarFacturas(3,'btn-f4')">
                      <input type="radio" name="option55" id="option55" autocomplete="off" ><i class="fa fa-times-circle"></i></input>
                    </label>
                    <span v-if="(tipoFiltroFa == 0 || tipoFiltroFa == 4) && privilegio.tesoreria_recepcion == true" data-tooltip="Asignar Cheque" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-proceso text-left btn-fact" @click="cargarInput(6)"><i class="fa fa-money-check"></i></span>
                    <span v-if="tipoFiltroFa == 4 && (privilegio.compras_tecnico == true || privilegio.compras_recepcion == true)" data-tooltip="Clase de Proceso" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-proceso text-left btn-fact" @click="cargarInput(2)"><i class="fa fa-edit"></i></span>
                    <span v-if="privilegio.compras_asignar_tecnico == true" data-tooltip="Asignar técnico" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-asig-tecnico text-left btn-fact" @click="cargarInput(3)"><i class="fa fa-user-plus"></i></span>
                    <span v-if="(tipoFiltroFa == 0 || tipoFiltroFa == 4) && privilegio.compras_recepcion == true" data-tooltip="Enviar a dirección" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left btn-fact" @click="cargarInput(4)"><i class="fa fa-paper-plane"></i></span>
                    <span v-if="(tipoFiltroFa == 0 || tipoFiltroFa == 4) && privilegio.compras_recepcion == true" data-tooltip="Recibir de dirección" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left btn-fact" @click="cargarInput(5)"><i class="fa fa-reply"></i></span>

                  </div>
                </div>

                <div  v-show="opcion == 2" style="position: absolute">
                  <div id="botonesO" class=" btn-group-toggle btn-group text-left" data-toggle="buttons" style="width:100%" hidden>
                    <label data-tooltip="Pendientes" class="btn btn-outline-info btn-sm text-left btn-for btn-fo1 active" @click="recargarOrdenes(0,'btn-fo1')" >
                      <input type="radio" name="options" id="option1" autocomplete="off" checked ><i class="fa fa-hourglass-start"></i></input>
                      Pendientes
                    </label>
                    <!--<label data-tooltip="Compromiso" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-for btn-fo2" @click="recargarOrdenes(1,'btn-fo2')">
                      <input type="radio" name="options" id="option2" autocomplete="off" ><i class="fa fa-handshake"></i></input>
                    </label>
                    <label data-tooltip="Anexo" class="alerta_no tooltip_noti btn btn-outline-info btn-sm text-left btn-for btn-fo3" @click="recargarOrdenes(3,'btn-fo3')" >
                      <input type="radio" name="options" id="option3" autocomplete="off" checked ><i class="fa fa-hourglass-start"></i></input>
                    </label>-->
                    <label data-tooltip="Devengado" class="btn btn-outline-info btn-sm text-left btn-for btn-fo4" @click="recargarOrdenes(2,'btn-fo4')" >
                      <input type="radio" name="options" id="option4" autocomplete="off" ><i class="fa fa-hand-holding-usd"></i></input>
                      Pagados
                    </label>
                    <label data-tooltip="Anulados" class="btn btn-outline-info btn-sm text-left btn-for btn-fo5" @click="recargarOrdenes(4,'btn-fo5')" >
                      <input type="radio" name="options" id="option5" autocomplete="off" ><i class="fa fa-times-circle"></i></input>
                      Anulados
                    </label>
                    <span class="btn btn-outline-info btn-sm text-left btn-for btn-seg-reg" @click="cargarInputO(1)" ><i class="fa fa-paper-plane"></i> Seguimiento</span>
                    </label>
                    <span class="btn btn-outline-info btn-sm text-left btn-for btn-seg-reg" @click="cargarInputO(2)" ><i class="fa fa-user-plus"></i> Asignar</span>

                    <!--<span v-if="tipoFiltroFa == 0 && privilegio.compras_recepcion == true" data-tooltip="Enviar a Presupuesto" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left"><i class="fa fa-paper-plane"></i></span>
                    <span v-if="tipoFiltroFa == 0 && privilegio.compras_recepcion == true" data-tooltip="Recibir de presupuesto" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left"><i class="fa fa-reply"></i></span>

                    <span v-if="tipoFiltroFa == 0 && privilegio.presupuesto_recepcion == true" data-tooltip="Enviar a Compras" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left"><i class="fa fa-paper-plane"></i></span>
                    <span v-if="tipoFiltroFa == 0 && privilegio.presupuesto_recepcion == true" data-tooltip="Recibir de Compras" class="alerta_no tooltip_noti btn btn-sm btn-outline-info btn-facts-env-direccion text-left"><i class="fa fa-reply"></i></span>-->
                  </div>
                </div>

                <!-- Tabs Nav -->
                <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                  <li class="nav-item" v-if="privilegio.facturas == true" >
                    <a href="#app_facturas1" class="nav-link" :class="[privilegio.facturas==true ? 'active' : '']" role="tab" aria-selected="true" data-toggle="tab" @click="showOpcion(1)">Facturas</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.registro == true">
                    <a href="#tab_ordenes" class="nav-link" :class="[(privilegio.registro == true) && privilegio.facturas==false ? 'active' : '']" role="tab" aria-selected="true" data-toggle="tab"  @click="showOpcion(2)">Registro</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.facturas == true && privilegio.compras_recepcion == true">
                    <a href="#tab_oficios" class="nav-link" role="tab" aria-selected="true" data-toggle="tab"  @click="showOpcion(4)">Oficios</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.facturas == true">
                    <a href="#tab_grafica" class="nav-link" role="tab" aria-selected="true" data-toggle="tab"  @click="showOpcion(3)">Gráficas</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.facturas == true">
                    <a href="#tab_reporte_facturas" class="nav-link" role="tab" aria-selected="true" data-toggle="tab"  @click="showOpcion(6)">Reportes</a>
                  </li>
                  <li class="nav-item" v-if="privilegio.tesoreria_recepcion == true">
                    <a href="#tab_cheques" class="nav-link" role="tab" aria-selected="true" data-toggle="tab"  @click="showOpcion(5)">Cheques</a>
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
                    {{ tipoFiltroFa }}
                    <div class="row">
                      <div class="">
                        <input id="id_filtro_factura" value="0" hidden></input>
                        <button id="slideLeft" type="button" @click="move('left')" hidden>Slide left</button>
                        <button id="slideRight" type="button" @click="move('right')" hidden>Slide right</button>


                      </div>
                      <div class="col-sm-12">
                        <!-- inicio -->
                        <div class="row" style="position: absolute; width:30rem; margin-top:0rem;z-index:555">
                          <div class="col-sm-4">
                            <select class="form-control form-control-sm" id="id_year" name="id_year" @change="recargarFacturas(tipoFiltroFa, buttonActual)">
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024" selected>2024</option>
                            </select>
                          </div>
                          <div class="col-sm-4" v-if="privilegio.subdirectorfinanciero == true">
                            <select class="form-control form-control-sm" id="id_tipo_pago_filter" name="id_tipo_pago_filter" @change="recargarFacturas(tipoFiltroFa, buttonActual)">
                              <option value="0" selected>Transferencia</option>
                              <option value="1">Cheque</option>
                              <option value="2">Todos</option>
                            </select>
                          </div>
                          <div class="col-sm-4" v-if="tipoFiltroFa == 0">
                            <div id="filterdays">
                            </div>
                          </div>
                          <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                        </div>

                        <table id="tb_facturas" class="table-f table table-actions table-striped table-bordered " width="100%" ref="infoBox" style="max-height:25%">
                          <thead>
                            <tr>
                              <th class=" text-center" @change="eventHeader()"></th>
                              <th class=" text-center"> Estado</th>
                              <th class=" text-center">Dias</th>
                              <th class=" text-center">Modalidad de compra</th>
                              <th class=" text-center ancho-fecha">Fecha recibido</th>
                              <th class=" text-center ancho-fecha">Fecha</th>

                              <th class=" text-center">Serie</th>
                              <th class=" text-center">Número</th>
                              <th class=" text-center">Proveedor</th>
                              <th class=" text-center">Monto</th>
                              <th class=" text-center">PYR</th>
                              <th class=" text-center">Renglón</th>
                              <th class=" text-center">Dirección</th>
                              <th class=" text-center">Clase de Proceso</th>
                              <th class=" text-center">No. Orden</th>
                              <th class=" text-center">Registro</th>

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
                          <!--<tfoot id="myfoot" v-show="tipoFiltroFa == 0 && privilegio.compras_recepcion == true">
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


                          </tfoot>-->
                        </table>
                        <!-- fin -->
                      </div>
                    </div>
                    <!--total: {{ totalChequeados }}
                    <br>
                    {{tipoBajaCuantia}}
              {{tipoNog}}-->



                  </div>
                  <!-- inicio -->

                  <!-- fin -->
                  <div class="tab-pane fade show slide_up_anim" id="tab_ordenes" :class="[(privilegio.presupuesto ==true || privilegio.subdirectorfinanciero == true) && privilegio.facturas==false ? 'active' : '']" role="tabpanel" style="width:100%">
                    <input id="id_filtro_orden" value="0" hidden></input>
                    <div class="row" style="position: absolute; width:100%; margin-top:0rem;z-index:555">
                      <div class="col-sm-2">
                        <select class="form-control form-control-sm" id="id_year_registro" name="id_year_registro" @change="recargarOrdenes(opcOrdenes,classOrdenes)">
                          <option value="2022">2022</option>
                          <option value="2023">2023</option>
                          <option value="2024" selected>2024</option>
                        </select>
                      </div>
                      <div class="col-sm-10" v-if="opcOrdenes == 0">
                        <div class="row">
                          <div class="col-sm-1">
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkPen" class="custom-control-input"name="chkPen" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">Pendiente</span>
                              <label class="custom-control-label" for="chkPen"></label>
                            </div>
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkApro" class="custom-control-input"name="chkApro" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">Aprobado</span>
                              <label class="custom-control-label" for="chkApro"></label>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkLiq" class="custom-control-input"name="chkLiq" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">Con liquidación</span>
                              <label class="custom-control-label" for="chkLiq"></label>
                            </div>
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkSinLiq" class="custom-control-input"name="chkSinLiq" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">Sin liquidación</span>
                              <label class="custom-control-label" for="chkSinLiq"></label>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkCurC" class="custom-control-input"name="chkCurC" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">CUR C</span>
                              <label class="custom-control-label" for="chkCurC"></label>
                            </div>
                            <div class="custom-control custom-checkbox text-center">
                              <input id="chkCurD" class="custom-control-input"name="chkCurD" type="checkbox" @click="recargarOrdenes(opcOrdenes,classOrdenes)">
                              <span style="margin-left:0.1rem; position:absolute">CUR D</span>
                              <label class="custom-control-label" for="chkCurD"></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <table id="tb_ordenes" class="table table-actions table-bordered " width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center">Estado</th>

                          <th class=" text-center">Clase de Proceso</th>
                          <th class=" text-center">Registro</th>

                          <th class=" text-center">CUR-C</th>
                          <th class=" text-center">Liquidación</th>
                          <th class=" text-center">CUR-D</th>
                          <th class=" text-justify" width="170px">Proveedor</th>
                          <th class=" text-center" >Facturas</th>
                          <th class=" text-center">Total</th>
                          <th class=" text-center" width="170px">Creador</th>

                          <th class=" text-center" >Acción</th>
                          <th class=" text-center" @change="eventHeaderO()"></th>

                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade show slide_up_anim" id="tab_oficios" role="tabpanel" v-if="privilegio.facturas ==true" style="width:100%">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="tb_oficios" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Oficio</th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center">Dirección</th>
                              <th class=" text-center">Estado</th>
                              <th class=" text-center">Recibido por</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
                  <div class="tab-pane fade show slide_up_anim" id="tab_grafica" role="tabpanel" v-if="privilegio.facturas ==true" style="width:100%">
                    <div class="row">
                      <div class="col-sm-12"><button class="btn btn-info btn-sm" @click="recargarGraficas()"><i class="fa fa-sync"></i> Recargar gráfica</button></div>
                      <!--<div class="col-sm-12">
                        <select class="form-control form-control-sm" id="id_year_chart" name="id_year_chart">
                          <option value="2022">2022</option>
                          <option value="2023" selected>2023</option>
                        </select>
                      </div>-->
                      <div class="col-sm-6" v-if="privilegio.compras">
                        <h3 class="text-center">Bajas Cuantías</h3>
                        <div id="chart_facturas" style="width:100%;height:60vh;"></div>
                      </div>
                      <div class="col-sm-6" v-if="privilegio.compras">
                        <h3 class="text-center">NOG</h3>
                        <div id="chart_facturas_n" style="width:100%;height:60vh;"></div>
                      </div>

                      <div class="col-sm-6" v-if="privilegio.tesoreria">
                        <h3 class="text-center">CHEQUES</h3>
                        <div id="chart_facturas_c" style="width:100%;height:60vh;"></div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade show slide_up_anim" id="tab_cheques" role="tabpanel" v-if="privilegio.tesoreria_recepcion ==true" style="width:100%">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="tb_cheques" class="table table-actions table-bordered nowrap" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Cheque</th>

                              <th class=" text-center">Proveedor</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                  </div>




                </div>
                <div class="tab-pane fade show slide_up_anim" id="tab_reporte_facturas" v-if="privilegio.facturas == true"  role="tabpanel">
                  <div class="row">

                    <div class="col-sm-3">
                      <div class="card bg-soft-primary">
                        <div class="card-header" >
                          <div class="row">
                            <div class="col-sm-6">
                              <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-upload"></i> Sin publicar
                            </div>
                            <div class="col-sm-6">
                              <h1>{{ cPendientePublicar }}</h1>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="card">
                        <div class="card-header">
                          <div class="row">
                            <div class="col-sm-8">
                              <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-upload"></i> Sin Técnico
                            </div>
                            <div class="col-sm-4">
                              <h1>{{ cPendienteAsignarT }}</h1>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="card">
                        <div class="card-header">
                          <div class="row">
                            <div class="col-sm-8">
                              <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-upload"></i> Sin Registro
                            </div>
                            <div class="col-sm-4">
                              <h1>{{ cPendienteAsignarCP }}</h1>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-sm-12">
                      <!-- inicio -->
                      <div class="row" style="position: absolute; width:30rem; margin-top:0rem;z-index:555">
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm" id="id_year_re" name="id_year_re" @change="recargarFacturas(tipoFiltroFa, buttonActual)">
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024" selected>2024</option>
                          </select>
                        </div>
                        <div class="col-sm-4">
                          <select class="form-control form-control-sm" id="id_tipo_pago_filter" name="id_tipo_pago_filter" @change="changeReporteFactura($event)">
                            <option value="" >Todos</option>
                            <option value="1" >Pendiente de publicar</option>
                            <option value="2">Pendiente de asignar técnico</option>
                            <option value="3">Pendiente de asignar registro</option>
                          </select>
                        </div>
                        <div class="col-sm-4">
                          <div id="botonesFR" class=" btn-group-toggle btn-group text-left" data-toggle="buttons" style="width:100%" >
                            <button class="btn btn-sm btn-outline-info btn-factr" @click="reloadTableReporte"><i class="fa fa-sync"></i></button>

                          </div>


                        </div>
                        <!--<div class="col-sm-6">
                          <p id="estado"><label><b>Estado:</b></label><br></p>
                        </div>-->
                      </div>


                      <table id="tb_reporte_facturas" class="table-ff table table-actions table-striped table-bordered " width="100%" ref="infoBox" style="max-height:25%">
                        <thead>
                          <tr>
                            <th class=" text-center" @change="eventHeader()"></th>
                            <!--<th class=" text-center"> Estado</th>-->
                            <th class=" text-center">Dias</th>

                            <th class=" text-center">Dias</th>
                            <th class=" text-center">Dias</th>
                            <th class=" text-center">Dias</th>

                            <th class=" text-center" style="width:150px">Fecha para publicar</th>
                            <th class=" text-center">Modalidad de compra</th>
                            <th class=" text-center ancho-fecha">Fecha de recibido</th>
                            <th class=" text-center ancho-fecha" style="width:150px">Fecha de la Factura</th>

                            <th class=" text-center">Serie</th>
                            <th class=" text-center">Número</th>
                            <th class=" text-center">Proveedor</th>
                            <th class=" text-center">Monto</th>
                            <th class=" text-center">PYR</th>
                            <th class=" text-center">Renglón</th>
                            <th class=" text-center">Dirección</th>
                            <th class=" text-center">Clase de Proceso</th>
                            <th class=" text-center">No. Orden</th>
                            <th class=" text-center">Registro</th>

                            <th class=" text-center">NOG</th>
                            <th class=" text-center">NPG</th>

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

                      <!-- fin -->
                    </div>
                  </div>
                  <!--total: {{ totalChequeados }}
                  <br>
                  {{tipoBajaCuantia}}
            {{tipoNog}}-->



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
