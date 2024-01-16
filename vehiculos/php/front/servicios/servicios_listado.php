<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if ($u->accesoModulo(1162)) { //privilegio documento
    date_default_timezone_set('America/Guatemala');
    $permisos = array();
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 1162);
    $permisos = array(
      'servicio' => ($array[1]['flag_insertar'] == 1) ? 1 : 0,
    );
    ?>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet"
      href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>

    <script type="module" src="vehiculos/js/servicios/modelServicios.js?t=<?php echo time(); ?>"></script>

    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
    <script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/vehiculos/impresionServicio.js?t=<?php echo time(); ?>"></script>
    <div class="u-content">
      <div class="u-body" id="appServicios">
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-md-12 mb-12 mb-md-0">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Control de Servicios</h2>
              </div>
              <input type="hidden" id='servicios' name='servicios' value='<?php echo $permisos['servicio']; ?>'>
              <div class="card-body">

                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active slide_up_anim">
                    <h5 class="h4 card-title">Control de Servicios</h5>

                    <input type="hidden" id="id_filtro" value="5487"></input>
                    <table id="tb_servicios" class="table table-actions table-striped table-bordered nowrap" width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center">ID. Servicio</th>
                          <th class=" text-center">Estado</th>
                          <th class=" text-center"># Orden</th>
                          <th class=" text-center">Tipo</th>
                          <th class=" text-center">Placa</th>
                          <th class=" text-center">Marca</th>
                          <th class=" text-center">Color</th>
                          <th class=" text-center">Modelo</th>
                          <th class=" text-center">Taller</th>
                          <th class=" text-center">Km Actual</th>
                          <th class=" text-center">Elaborado</th>
                          <th class=" text-center">Entrega</th>
                          <th class=" text-center">Fecha</th>
                          <th class=" text-center">Descripción</th>
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
      <iframe id="pdf_preview_v" hidden></iframe>
      <script src="vehiculos/js/servicios/funciones.js"></script>
      <?php
  }
} else {
  header("Location: index.php");
}
?>