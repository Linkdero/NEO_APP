<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)){//1163 módulo recursos humanos
    $id_persona=null;
    $id_tipo_filto = null;
    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }
    if ( !empty($_GET['tipo'])) {
      $id_tipo_filtro = $_REQUEST['tipo'];
    }
    if (!empty($_POST)){
      header("Location: index.php?ref=_101");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">

      <script src="empleados/js/funciones.js"></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="empleados/js/appComponentes.1.3.js"></script>
      <script src="empleados/js/appComponentesEmpleado.1.2.js"></script>
      <script src="empleados/js/appValidacionesForm.1.2.js?v=<?php echo time();?>"></script>

      <script src="empleados/js/viewModelDatosEmpleado.js"></script>


      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
      <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    </head>
    <body>

      <div id="emp_datos_app" style="-webkit-transform: translateZ(0);">
        <!--<vue-pdf-app style="height: 50vh;" :pdf="pdf" :config="config" theme="dark"></vue-pdf-app>-->
        <div id="cargando"></div>
        <input type="text" id="id_tipo_filtro" hidden value="<?php echo $id_tipo_filtro?>" ></input>
        <input type="text" id="id_cambio" hidden value="0"></input>
        <input type="text" id="id_gafete" hidden value="<?php echo $id_persona?>" ></input>
        <input type="text" id="id_empleado" :value="datos_persona.id_empleado" hidden></input>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Puesto Actual </h5>
          <ul class="list-inline ml-auto mb-0" v-if="datos_persona.id_empleado==0">
            <label class="btn btn-personalizado btn-sm" data-dismiss="modal">
              <span name="options" id="option3" autocomplete="off"  > Salir
            </label>
          </ul>
          <ul class="list-inline ml-auto mb-0" v-else="sLoaded==true">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-personalizado active btn-sm" @click="getOpcion(1)">
                <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
              </label>

              <label class="btn btn-personalizado btn-sm"  @click="getOpcion(2)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off"> Plazas
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off" > Contratos
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(8)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off" > Ubicaciones
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(20)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off" > Catálogo
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)" v-if="dPuesto.estado != 2312 && privilegio.asuntos == true">
                <input type="radio" name="options" id="option4" autocomplete="off" > Carnets
              </label>

              <label class="btn btn-personalizado btn-sm out">
                <span name="options" id="option6" autocomplete="off"  > Salir
              </label>
            </div>
          </ul>
        </div>

        <div>
          <div class="modal-body bg-light">
            <div class="row " id="">
              <fotografia :id_persona="id_persona" tipo="2"></fotografia>
              <detalle-puesto v-on:event_child="getEmpEstadoChild" ref="comp" :id_persona="id_persona" :apy="dPuesto.estado"></detalle-puesto>
            </div>
          </div>
          <!-- fin-->
        </div>

        <div class="modal-body">
          <div v-if="isLoaded==true">
            <!-- inicio detalle -->
            <div  v-if="datosPersona.tipo_persona==1051">
              <!--permiso de Reclutamiento -->
                <div  v-if="privilegio.reclu == true">
                  <aspirante-proceso :id_persona="datosPersona.id_persona" :id_empleado="datosPersona.id_empleado" v-on:siguiente-proceso="setEstadoProceso"></aspirante-proceso>
                </div>
                <div  v-else>
                  <!-- else -->
                  <h2>Aspirante en Proceso</h2>
                  <span v-if="datosPersona.tipo_aspirante == 1 && privilegio.acciones == true" class="btn btn-sm btn-soft-info" @click="getOpcion(5)"><i class="fa fa-plus"></i> Asignar plaza</span>
                  <span v-if="(datosPersona.tipo_aspirante == 2 || datosPersona.tipo_aspirante == 3) && privilegio.nominas == true" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                </div>
              <!-- finalizar denegación -->
            </div>
            <div v-else-if="datosPersona.id_empleado > 0 ||  datosPersona.tipo_persona==1052 || datosPersona.tipo_persona == 9103">
              <!-- inicio -->
              <div id="datos_puesto_">
                <!-- inicio puesto detalle-->
                <div v-show="opcion==1" class="">
                  <detalle-asignacion-plaza  v-on:event_child="getDetallePuestoChild" :id_persona="id_persona"></detalle-asignacion-plaza>
                </div>
                <!-- fin puesto detalle -->

                <!-- inicio plazas-->
                <div v-show="opcion==2" class="">
                  <detalle-plazas :id_persona="id_persona" :privilegio="privilegio" :datos_persona="datosPersona" :datos_plaza="datos_plaza"></detalle-plazas>
                </div>
                <!-- fin plazas-->
                <!-- inicio contratos -->
                <div v-if="opcion==3" class="">
                  <detalle-contratos :id_persona="id_persona" :privilegio="privilegio" :datos_persona="datosPersona" :datos_plaza="datos_plaza"></detalle-contratos>
                </div>
                <!-- fin contratos -->

                <!-- inicio ubicaciones -->
                <div v-if="opcion==8" class="">
                  <detalle-ubicaciones :id_persona="id_persona" :privilegio="privilegio" :datos_persona="datosPersona" :datos_plaza="datos_plaza"></detalle-ubicaciones>
                </div>
                <!-- fin ubicaciones -->
                <!-- inicio carnets -->
                <div v-if="opcion==11">
                  carnets
                  <table id="tb_carnets" class="table table-sm table-striped responsive" width="100%">
                    <thead>
                      <tr>
                        <th class="text-center">id_gafete</th>
                        <th class="text-center">id_empleado</th>
                        <th class="text-center">id_contrato</th>
                        <th class="text-center">id_version</th>
                        <th class="text-center">puesto</th>
                        <th class="text-center">fecha_generado</th>
                        <th class="text-center">fecha_vencimiento</th>
                        <th class="text-center">fecha_validado</th>
                        <th class="text-center">Acción</th>
                      </tr>

                    </thead>
                    <tbody>
                    </tbody>
                  </table>

                </div>
                <!-- fin carnets -->
                <div v-if="opcion==12">
                  Generar Carnet
                  <form class="jsValidationCarnetNuevo">
                    <div class="row">
                      <!--inicio-->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_tipo_carnet">Tipo de Carnet*</label>
                              <span class="form-icon-wrapper">
                                <span class="form-icon form-icon--left">
                                  <i class="fa fa-id-card form-icon__item"></i>
                                </span>

                              <select class=" form-control form-control-sm form-icon-input-left form-control-alternative" id="id_tipo_carnet" name="id_tipo_carnet" required autocomplete="off">
                                <option v-for="t in tipoCarnets" v-bind:value="t.id_item" >{{ t.item_string }}</option>
                              </select>

                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!--inicio-->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_posee_arma">Poseción de Arma*</label>
                              <span class="form-icon-wrapper">
                                <span class="form-icon form-icon--left">
                                  <i class="fa fa-thumbs-down form-icon__item"></i>
                                </span>

                              <select class=" form-control form-control-sm form-icon-input-left form-control-alternative" id="id_posee_arma" name="id_posee_arma" required autocomplete="off">
                                <option value="" >-- Seleccionar --</option>
                                <option value="1" >SI</option>
                                <option value="2" >NO</option>
                              </select>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.estado==891 || datos_plaza.estado==908" @click="generarCarnet()"><i class="fa fa-check"></i> Generar Carnet</button>
                        <span class="btn btn-sm btn-danger" @click="getOpcion(12)"><i class="fa fa-times"></i> Cancelar</span>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <!-- fin -->
            </div>
            <!-- fin detalle -->
          </div>
          <div v-if="isLoaded==false">
            <div class="loaderr"  style =" z-index:55"></div>
            <!-- inicio detalle -->
            <!-- fin detalle -->
          </div>
          <!-- inicio agregar plaza -->
          <div v-if="opcion==5" class="">
            <form-asignacion-plaza v-if="opcion==5 || tipoEstado == 1" :id_persona="id_persona" :id_gafete="id_persona" :id_empleado="datos_persona.id_empleado" :id_asignacion="0" :tipo_accion="1" ></form-asignacion-plaza>
          </div>
          <!-- fin agregar plaza -->
          <!-- inicio agregar contrato -->
          <div v-if="opcion==7" class="">
            <form-asignacion-contrato v-if="opcion==7 || tipoEstado == 2" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1"></form-asignacion-contrato>
          </div>
          <!-- fin agregar contrato -->
          <!-- inicio persona apoyo -->
          <div v-if="opcion==16 || tipoEstado == 4" class="">
            <form-asignacion-apoyo v-if="opcion==16 || tipoEstado == 4" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1" tipo="1"></form-asignacion-apoyo>
          </div>
          <!-- fin persona apoyo -->
          <!-- inicio agregar practicante -->
          <div v-if="opcion==16 || tipoEstado == 5" class="">
            <form-asignacion-apoyo v-if="opcion==16 || tipoEstado == 5" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1" tipo="2"></form-asignacion-apoyo>
          </div>
          <!-- fin agregar practicante-->
          <!-- inicio opciones de catálogos -->
          <form-catalogo v-if="showCatalogo == true" :option="cargarCatalogo" :tipocarga="2" :opcionActual="opcion"></form-catalogo>
          <!-- fin opciones de catálogos -->
        </div>
      </div>

    </div>
  </div>


</body>

  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
