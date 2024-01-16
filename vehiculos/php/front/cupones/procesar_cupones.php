<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

    $id_documento = $_GET["id_documento"];

    $tipo = new vehiculos();
    $dataenc = $tipo::get_devolCuponesEnc($id_documento);
?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="vehiculos/js/validaciones.js"></script>

      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/cupones_detalle_vue.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

      <script>

      </script>

      <style>
        .fixed_header,
        tr td {
          border: 2px solid powderblue;
          /*  lightsteelblue steelblue */
          ;
        }

        .fixed_header>tbody {
          display: block;
          height: 300px;
          overflow: auto;
        }

        .fixed_header>thead,
        .fixed_header>tbody tr {
          display: table;
          width: 100%;
          table-layout: fixed;
          /* even columns width , fix width of table too*/
          ;
        }

        .fixed_header>thead {
          width: calc(100% - 1em)
            /* scrollbar is average 1em/16px width, remove it from thead width */
          ;
        }
      </style>


    </head>
    <div id="modalCupones" style="-webkit-transform: translateZ(0);">

      <div class="modal-header">
        <h5 class="modal-title">Entrega y Devolucion de Cupones</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span data-dismiss="modal" aria-label="Close" class="link-muted h3" id="cerrarModal">
              <i class="fa fa-times">
              </i>
            </span>
          </li>
        </ul>
      </div>

      <div id="app_cupones_detalle" class="modal-body">
        <input id="id_documento" value="<?php echo $id_documento ?>" hidden></input>
        <input id="id_cambio" value="" hidden></input>
        <div class="row">
          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-3">
                <small class="text-muted">Correlativo </small>
                <h5><strong>{{ documento.id_documento }}</strong></h5>
              </div>
              <div class="col-sm-3">
                <small class="text-muted">Fecha </small>
                <h5><strong>{{ documento.fecha }}</strong></h5>
              </div>
              <div class="col-sm-6">
                <small class="text-muted">Estado </small>
                <h5><strong>{{ documento.estado }}</strong></h5>
              </div>
              <div class="col-sm-3">
                <small class="text-muted">Documento </small>
                <h5><strong>{{ documento.nro_documento }}</strong></h5>
              </div>
              <div class="col-sm-3">
                <small class="text-muted"> </small>
                <h5><strong></strong></h5>
              </div>
              <div class="col-sm-6">
                <small class="text-muted">Autorizo </small>
                <h5><strong>{{ documento.auto }}</strong></h5>
              </div>
              <div class="col-sm-3">
                <small class="text-muted">Total Q. / Cant. </small>
                <h5><strong>{{ documento.total }}</strong></h5>
              </div>
              <div class="col-sm-3">
                <small class="text-muted">Devuelto Q. / Cant. </small>
                <h5><strong>{{ documento.devuelto }}</strong></h5>
              </div>
              <div class="col-sm-6">
                <small class="text-muted">Recibe </small>
                <h5><strong>{{ documento.recibe }}</strong></h5>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-12">
                <small class="text-muted">Observaciones</small>
                <h5><strong>{{ documento.observa }}</strong></h5>
              </div>
            </div>

          </div>
          <div class="col-sm-12">
            <formulario-cupones tipo="1" :idcupon="id_documento" :documento="documento" v-on:cancelar="cancelarDevol"></formulario-cupones>
          </div>
        </div>
      </div>
    </div>
<?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>