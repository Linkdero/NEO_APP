<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

    ?>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet"
      href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.11/dist/cropper.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/js/plugins/sweetalert/sweetalert.min.js">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

    <script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
    <script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
    <script src="assets/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="module" src="vehiculos/js/table_vehiculos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.11/dist/cropper.js"></script>

    <div class="u-content">
      <div class="u-body" id="appVehiculos">
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-md-12 mb-12 mb-md-0">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Listado de Vehiculos</h2>
              </div>
              <div class="card-body">

                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active slide_up_anim">
                    <h5 class="h4 card-title">Listado de Vehiculos</h5>

                    <table id="tb_vehiculos" class="table table-actions table-striped table-bordered nowrap mb-0"
                      width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center">ID</th>
                          <th class=" text-center">Estado</th>
                          <th class=" text-center">Placa</th>
                          <th class=" text-center">Chasis</th>
                          <th class=" text-center">Motor</th>
                          <th class=" text-center">Tipo</th>
                          <th class=" text-center">Marca</th>
                          <th class=" text-center">Linea</th>
                          <th class=" text-center">Color</th>
                          <th class=" text-center">Franjas</th>
                          <th class=" text-center">Modelo</th>
                          <th class=" text-center">Observaciones</th>
                          <th class=" text-center">Capacidad Tanque</th>
                          <th class=" text-center">Combustible</th>
                          <th class=" text-center">Km.Actual</th>
                          <th class=" text-center">Propietario</th>
                          <th class=" text-center">Per.Asignada</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <iframe id="pdf_preview_v" hidden></iframe> -->
      <script src="vehiculos/js/vehiculos/funciones.js?t=<?php echo time(); ?>"></script>
      <?php
  }
} else {
  header("Location: index.php");
}
?>