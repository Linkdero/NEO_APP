<?php
if (function_exists('verificar_session') && verificar_session(8865)) {
  //if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
?>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="ISO-8859-1">

  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">


  <script src="assets/js/plugins/select2/select2.min.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="assets/js/pages/components.js"></script>
  <script type="module" src="tickets/src/reporteriaModel.js"></script>
  <script src="./assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
  <script src="assets/js/plugins/vue/http-vue-loader.js"></script>

  <div id="body" class="u-content" width="100%">
    <div id="AppReporteria" class="u-body">
      <!-- Overall Income -->
      <div class="row">
        <!-- Current Projects -->
        <div class="col-md-12 mb-12 mb-md-0">
          <div class="card h-100">
            <header class="card-header d-flex align-items-center">

              <h2 class="h3 card-header-title">Reporteria</h2>

              <input id="limiteInferior" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off" @blur="filtrarFecha()">
              <input id="limitesuperior" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off" @blur="filtrarFecha()">

              <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item" title="Recargar">
                  <span class="link-muted h3" @click="recargarTablaRequerimientos()">
                    <i class="fa fa-sync"></i>
                </li>
              </ul>

              <!-- End Card Header Icon -->
            </header>

            <div class="card-body card-body-slide" id="body">
              <input type="hidden" id="id_filtro" v-bind:value="valor"></input>
              <div class="">
                <table id="tbl_reporteria" class="table responsive table-sm table-bordered table-striped" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center">
                        <div v-text="this.encabezado">
                        </div>
                      </th>
                      <th class="text-center">DEPARTAMENTO</th>
                      <th class="text-center">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>


              </div>
              <!-- End Card Body -->
            </div>
            <!-- End Overall Income -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    #body {
      min-width: 100%;
    }
  </style>
<?php

  /*}
else{
  include('inc/401.php');
}*/
} else {
  header("Location: index");
}
?>