<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

    ?>
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet"
      href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <script type="module" src="vehiculos/js/cuponesModels.js"></script>
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/vehiculos/impresion_cupon.js"></script>
    <script type="module" src="vehiculos/js/source.js"></script>
    <script src="vehiculos/js/funciones.js"></script>
    <div class="row" style="position:absolute;width:100%">
      <div class="col-sm-12">
        <div class="row">

          <div class="col-sm-2" style="z-index:55;" hidden>
            <div class="form-group ">
              <label for="estado_cupon">Estado:</label>
              <input id="estado_cupon" value="4348"></input>
            </div>
          </div>
        </div>
      </div>
    </div>

    <br>

    <div id="tbsCupones">
      <div v-show="tablaContent == 1">
        <div id="tabla1">
          <table id="tb_cupones_entregados" class="table table-sm table-striped table-hover table-bordered" width="100%">
            <thead>
              <th>Docto.</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th># Docto.</th>
              <th>Autorizó</th>
              <th>Recibe</th>
              <th>Cupones</th>
              <th>Solicitado</th>
              <th>Utilizado</th>
              <th>Acción</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <div v-show="tablaContent == 2">
        <div id="tabla2">
          <table id="tbPorCupon" class="table table-sm table-striped table-hover table-bordered" width="100%">
            <thead>
              <th>ID</th>
              <th>Cupon</th>
              <th>Monto</th>
              <th>Estado</th>
              <th># Doc</th>
              <th>Fecha Entregado</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
      set_dates();

      function set_dates() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        firstDay = '01-' + mm + '-' + yyyy;
        today = dd + '-' + mm + '-' + yyyy;

        $('#ini').val(firstDay);
        $('#fin').val(today);
      }
    </script>
  <?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index.php");
}
?>