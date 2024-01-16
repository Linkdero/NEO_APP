<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {
?>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="vehiculos/js/source.js"></script>
    <script src="vehiculos/js/funciones.js"></script>

    <div class="row" style="position:absolute;width:100%">
      <div class="col-sm-12">
        <div class="row">
          <div class="col-sm-12" style="z-index:55;" hidden>
            <div class="form-group ">
              <label for="estado_cupon">Estado:</label>
              <input id="estado_cupon" value="4348"></input>
            </div>
          </div>
          <div class="col-sm-5"></div>
        </div>
      </div>
    </div>

    <table id="tb_cupones_ingresados" class="table table-sm table-striped table-hover table-bordered" width="100%">
      <thead>
        <th>ID</th>
        <th>Fecha</th>
        <th># Docto.</th>
        <th>Operó</th>
        <th>Total</th>
        <th>Acción</th>
      </thead>
      <tbody>
      </tbody>
    </table>
    <script>
      set_dates();

      function set_dates() {
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let yyyy = today.getFullYear();

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