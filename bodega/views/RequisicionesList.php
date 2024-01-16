<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8326)){

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">


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
    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>
    <script src="assets/js/plugins/jspdf/bodega/requisicion.1.1.js"></script>

    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>

    <script type="module" src="bodega/src/appRequisiciones.js"></script>
    <style>
    .DTFC_RightBodyLiner {
      overflow: hidden!important;
      overflow-y: hidden!important;
    }
    </style>


    <div class="u-content">
        <div class="u-body" id="appRequisicion">

            <div class="card mb-12 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >{{ titulo }}</h2>
                    <!-- Tabs Nav -->
                    <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                      <li class="nav-item" @click="setTitle('Control de Requisiciones')">
                        <a href="#RequisicionesListado" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">Solicitudes</a>
                      </li>
                      <li v-if="privilegios.requisicion_solicita == true" class="nav-item" @click="setTitle('Nueva Requisición')">
                        <a href="#RequisicionNueva" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Nueva Requisición</a>
                      </li>
                      <li v-if="privilegios.inventarios == true" class="nav-item" @click="setTitle('Familias')">
                        <a href="#FamiliaListado" class="nav-link" role="tab" aria-selected="true" data-toggle="tab">Familias</a>
                      </li>
                    </ul>
                    <!-- End Tabs Nav -->
                </header>

                <div class="card-body card-body-slide">
                  <!--{{ privilegios }}-->
                  <retornadireccion v-on:enviardireccion="getDireccionFromComponent" :evento="evento"></retornadireccion>
                  <retornaprivilegios v-on:enviaprivilegios="getPrivilegiosFromComponent"></retornaprivilegios>
                  <div class="tab-content">
                    <div id="RequisicionesListado" class="tab-pane fade show slide_up_anim active" role="tabpanel" style="min-width:100%">
                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro_requisiciones" hidden value="1"></input>
                        <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                          <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="id_year" name="id_year">
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024" selected>2024</option>
                            </select>
                          </div>
                          <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                        </div>

                        <table id="tb_requisiciones" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center"style="max-width:100px">Estado</th>
                              <th class=" text-center">Requisición</th>
                              <th class=" text-center"style="max-width:100px">Fecha </th>
                              <th class=" text-center">Dirección </th>
                              <th class=" text-center">Unidad</th>
                              <th class=" text-center">Solicitante</th>
                              <th class=" text-center">Bodega</th>
                              <th class=" text-center" style="max-width:350px">Observaciones</th>
                              <th class=" text-center">Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <!-- inicio -->
                    <div v-if="privilegios.requisicion_solicita == true" id="RequisicionNueva" class="tab-pane fade slide_up_anim" role="tabpanel" style="min-width:100%">
                      <form class="jsValidacionRequisicionNueva">
                        <div class="row">
                          <unidadesl v-if="showUnidad == true"columna="col-sm-3"></unidadesl>
                          <insumosfiltrado columna_global="col-sm-9" columna="col-sm-5" :evento="evento" :datos_tabla="insumos" tipo="1" id_pro="filtro_form_nuevo"></insumosfiltrado>
                          <direcciones columna="col-sm-4" v-if="privilegios.residencias_solicita_recursos == true || privilegios.bodega_armeria_rev == true || privilegios.bodega_talleres_rev == true" :evento="evento" codigo="id_direccion" filtro="2"></direcciones>
                          <departamentos columna="col-sm-4" v-if="privilegios.residencias_solicita_recursos == true || privilegios.bodega_armeria_rev == true || privilegios.bodega_talleres_rev == true" :evento="evento" codigo="id_unidad"></departamentos>
                          <empleados v-if="privilegios.bodega_talleres_rev == true" :columna="columnae" codigo="solicitante_c_id" :direccion="idDireccion.id_direccion"></empleados>
                          <empleados v-else :columna="columnae" codigo="solicitante_c_id" :direccion="idDireccion.id_direccion" :evento="evento"></empleados>
                          <insumos :evento="evento" v-on:send_insumos_to_parent="getInsumosL" crud="c" fase="0" :req="req"></insumos>
                          <!--inicio-->
                          <div class="col-sm-12">
                            <div class="form-group">
                              <div class="">
                                <div class="">
                                  <label for="id_descripcion">Justificación (necesidad, finalidad y temporalidad)* Le quedan {{ totalCharacter }} caracteres</label>
                                  <div class=" input-group  has-personalizado">
                                    <textarea rows="3" oninput="this.value = this.value.toUpperCase()" maxlength="300" class="js-select2 form-control form-control-sm" id="id_observaciones" name="id_observaciones" required v-model='messageCharacter' @keyup='charCount()'>

                                    </textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin -->

                          <div class="col-sm-12 text-right">
                            <button class="btn btn-info btn-sm" @click="saveRequisicion()"><i class="fa fa-check-circle"></i> Generar Requisición</button>
                          </div>
                        </div>
                      </form>


                    </div>
                    <!-- fin -->

                    <div v-if="privilegios.inventarios == true" id="FamiliaListado" class="tab-pane fade show slide_up_anim" role="tabpanel" style="min-width:100%" >
                      <div class="table-responsive">

                        <table id="tb_familias" class="table table-actions table-striped table-bordered  " width="100%" style="min-width:100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Código</th>
                              <th class=" text-center">Familia</th>
                              <th class=" text-center">Residencias</th>
                              <th class=" text-center">Financiero </th>
                              <th class=" text-center">Talleres </th>
                              <th class=" text-center">Edificios</th>
                              <th class=" text-center">Academia</th>
                              <th class=" text-center">Armeria</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>



                  <iframe id="pdf_preview_requ" hidden></iframe>

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
