<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(8017)) {

?>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
      <script src="viaticos/js/cargar.js"></script>
      <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet" />
      <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
      <script src="documentos/js/validaciones.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/servicios/modelServicioNuevo.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

    </head>
    <div id="app_orden_servicio">
      <div class="modal-header">
        <h3>Generar orden de servicio {{idVehiculo}}</h3>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body">
        <form class="jsValidacionNuevoServicio" id="formValidacionNuevoServicio">
          <div class="row">
            <div class="col-sm-12">
              <div class="row">
                <campo row="col-sm-12" label="Nro. de oficio" codigo="id_orden" tipo="text" requerido="true"></campo>
                <combo-items label="Tipo de Servicio:" codigo="id_tipo_servicio" col="col-sm-12" id_catalogo="54" requerido="true"></combo-items>
                <conductores row="col-sm-12" label="Persona que entrega vehículo a taller" codigo="id_quien_lleva"></conductores>
                <component-vehiculos class="col-sm-12" tipo="3"></component-vehiculos>
                <div :class="row">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label>Descripción de la solicitud de servicio</label>
                        <textarea id="id_descripcion" name="id_descripcion" class='form-control form-control-sm' :type="tipo" required rows="3" autocomplete="off"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-show="idVehiculo > 0" class="col-sm-8 slide_up_anim" style="position: absolute; margin-left:500px;margin-top:-77px">
              <div class="card bg-light">
                <vehiculo tipo="2"></vehiculo>
              </div>
            </div>


            <div class="col-sm-12">
              <button class="btn btn-sm btn-block btn-info" @click="generarOrdenServicio"><i class="fa fa-check-circle"></i> Guardar</button>
            </div>
          </div>

        </form>

      </div>
  <?php
  } else {
    include('inc/401.php');
  }
} else {
  header("Location: index.php");
}
  ?>