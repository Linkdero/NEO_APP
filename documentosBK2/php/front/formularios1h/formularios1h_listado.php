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

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <!--<script src="documentos/js/source.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="documentos/js/1h_vue.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.js"></script>

    <script>



    </script>
    <div class="u-content">
      <div class="u-body" id="">
        <div class="row">
          <!-- Cards with Tabs -->
          <div class="col-md-12 mb-12 mb-md-0">
            <div class="card">
              <div class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Control de Formularios 1H</h2>

                <!-- Tabs Nav -->
                <ul class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                  <li class="nav-item">
                    <a href="#panelWithTabsTab1" class="nav-link active" role="tab" aria-selected="true" data-toggle="tab">Pedidos</a>
                  </li>
                </ul>
                <!-- End Tabs Nav -->
              </div>

              <div class="card-body" id="app_1h">

                <!-- Tabs Content -->
                <div class="tab-content">
                  <div class="tab-pane fade show active slide_up_anim" id="panelWithTabsTab1" role="tabpanel">
                    <h5 class="h4 card-title">Formularios 1H</h5>

                    <input type="text" id="id_tipo_filtro" value="0" hidden></input>

                    <table id="tb_formularios1h" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                      <thead>
                        <tr>
                          <th class=" text-center">1H</th>
                          <th class=" text-center">Fecha</th>
                          <th class=" text-center">Tran.</th>
                          <th class=" text-center">Factura</th>
                          <th class=" text-center">Serie</th>
                          <th class=" text-center">Fecha</th>
                          <th class=" text-center">Nit</th>
                          <th class=" text-center">Proveedor</th>
                          <th class=" text-center">Total</th>
                          <th class=" text-center">Bodega</th>
                          <th class=" text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>


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
