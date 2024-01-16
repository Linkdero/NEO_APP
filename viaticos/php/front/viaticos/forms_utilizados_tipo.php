<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121) || usuarioPrivilegiado_acceso()->accesoModulo(8085)){

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/plugins/datatables/new/dataTables.rowsGroup.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <!--<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script type="module" src="viaticos/js/appViaticoReporteTipo.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>
    <script src="assets/js/plugins/jspdf/viaticos/impresiones_3.2.7.js"></script>
    <script src="assets/js/plugins/jspdf/viaticos/impresion_global.js"></script>
    <script src="viaticos/js/funciones_1.3.js"></script>
    <script>

    </script>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalBody" class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div class="u-content" id="appViaticoTipo">
        <div class="u-body">
            <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Reporte de Formularios por Tipo</h2>
                    <ul class="list-inline ml-auto mb-0">


                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3" onclick="recargarFormsUtilizados()">
                          <i class="fa fa-sync"></i>
                        </span>
                      </li>
                    </ul>
                </header>

                <div class="card-body card-body-slide">
                  <div class="col-sm-6">
                    <div class="row" style=" z-index:2" >
                      <div class="col-sm-4">
                        <select id="tipo" class="form-control form-control-sm" @change="cambiarFiltros($event)">
                          <option value="1">V-A</option>
                          <option value="2">V-C</option>
                          <option value="3">V-L</option>
                          <option value="4">V-E</option>
                        </select>
                        Formulario mínimo: {{ minValue }}
                      </div>
                      <div class="col-sm-4">

                        <!--<input id="ini" class="form-control form-control-sm" value="<?php echo '2022-01-01'; ?>" type="date"></input>-->
                        <input id="ini" class="form-control form-control-sm" v-model="iniValue" type="number" :min="minValue"></input>
                      </div>
                      <div class="col-sm-4">
                        <!--<input id="fin" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date"></input>-->
                        <input id="fin" class="form-control form-control-sm" v-model="finValue" type="number" :min="minValue"></input>
                      </div>
                    </div>
                  </div>
                  <br>
                  <table id="tb_formularios_utilizados_tipo" class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">
                    <thead>
                      <tr>
                        <th class=" text-center">Correlativo</th>
                        <th class=" text-center">Estado del Formulario</th>
                        <th class=" text-center">Verificador</th>
                        <th class=" text-center">Fecha</th>
                        <th class=" text-center">Comisión</th>
                        <th class=" text-center">Estado</th>
                        <th class=" text-center">Empleado</th>
                        <th class=" text-center">Dirección</th>
                        <th class=" text-center">Tipo</th>

                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <iframe id="pdf_preview_v" hidden></iframe>

    <?php
  }
}else{
    header("Location: index.php");
}
?>
