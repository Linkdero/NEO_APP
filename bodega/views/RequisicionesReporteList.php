<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(8326)) {

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
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet"
      href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

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

    <script type="module" src="bodega/src/appRequisicionesReporte.js"></script>
    <style>
      .DTFC_RightBodyLiner {
        overflow: hidden !important;
        overflow-y: hidden !important;
      }
    </style>


    <div class="u-content">
      <div class="u-body" id="appRequisicion">

        <div class="card mb-12 ">
          <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Reporte de Requisiciones utilizadas</h2>
            <!-- Tabs Nav -->

            <!-- End Tabs Nav -->
          </header>

          <div class="card-body card-body-slide">
            <!--{{ privilegios }}-->
            <retornaprivilegios v-on:enviaprivilegios="getPrivilegiosFromComponent"></retornaprivilegios>
            <div class="tab-content">
              <div id="RequisicionesReporteListado" class="tab-pane fade show slide_up_anim active" role="tabpanel"
                style="min-width:100%">
                <div class="table-responsive">
                  <input type="text" id="id_tipo_filtro_reporte" hidden value="1"></input>
                  <div class="row" style="position: absolute; width:20rem; margin-top:0rem;z-index:555">
                    <div class="col-sm-6">
                      <select class="form-control form-control-sm" id="id_year" name="id_year"
                        @change="recargarRequisicionesReporte()">
                        <option value="2024" selected>2024</option>
                        <option value="2023">2023</option>
                      </select>
                    </div>
                    <div class="col-sm-6" style="z-index:55;">
                      <select id="id_mes" class="js-select2 form-control form-control-sm"
                        @change="recargarRequisicionesReporte()">
                        <?php
                        for ($x = 1; $x <= 12; $x++) {
                          $m = ($x < 10) ? '0' . $x : $x;
                          if ($m == date('m'))
                            echo '<option value="' . $x . '">' . User::get_nombre_mes($x) . '</option>';
                          else
                            echo '<option value="' . $x . '">' . User::get_nombre_mes($x) . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <!--<div class="col-sm-6">
                            <p id="estado"><label><b>Estado:</b></label><br></p>
                          </div>-->
                  </div>

                  <table id="tb_reporte_requisiciones" class="table table-actions table-striped table-bordered  "
                    width="100%">
                    <thead>
                      <tr>
                        <th class=" text-center">Requisición</th>
                        <th class=" text-center" style="max-width:100px">Fecha </th>
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
              <iframe id="pdf_preview_requ" hidden></iframe>

            </div>
          </div>
        </div>
      </div>


      <?php
  }
} else {
  header("Location: index.php");
}
?>