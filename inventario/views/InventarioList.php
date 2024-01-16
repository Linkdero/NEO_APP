<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){

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
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src="assets/js/plugins/qr/qr.js"></script>

    <!--<script src="viaticos/js/source_3.1.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>


    <!--<script src="viaticos/js/viatico_vue_1.3.js"></script>-->

    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>

    <script type="module" src="inventario/src/appInventario.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>
    <script src="assets/js/plugins/jspdf/inventario/Certificacion.1.2.js"></script>
    <script src="vehiculos/js/validaciones_vue.js"></script>
    <!--<script src="assets/js/plugins/jspdf/inventario/Sticker.js"></script>-->

    <script>
    $(document).ready(function(){
      /*setInterval(function(){
        show_nombramientos_pendientes_count();
      }, 5000);*/
    });

    </script>



    <div class="u-content">
        <div class="u-body" id="appInventario">

	          <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 id="titulo_card" class="h3 card-header-title" >Control de Bienes de la SAAS</h2>
                    <ul class="list-inline ml-auto mb-0">
                      <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                        <li class='nav-item'>
                          <a class='nav-link active' data-toggle='tab' href='#bieneslist'>Bienes</a>
                        </li>
                        <li class='nav-item'>
                          <a class='nav-link' active data-toggle='tab' href='#certificacioneslist'>Certificaciones</a>
                        </li>
                      </ul>
                    </ul>
                </header>

                <div class="card-body card-body-slide">

                  <div class="tab-content">
                    <div id="bieneslist" class="tab-pane fade show active" role="tabpanel">

                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro" hidden value="2"></input>
                        <table id="tb_inventario" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">CODIGO </th>
                              <th class=" text-center" width="300px">DESCRIPCIÓN</th>
                              <th class=" text-center">MONTO</th>
                              <th class=" text-center">FECHA DE ADQUISICIÓN</th>
                              <th class=" text-center">RENGLÓN</th>
                              <th class=" text-center">VERIFICACIÓN</th>
                              <th class=" text-center">ESTADO</th>
                              <th class=" text-center"></th>
                              <th class=" text-center">ACCIÓN</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div id="certificacioneslist" class="tab-pane fade" role="tabpanel">
                      <div class="table-responsive">
                        <input type="text" id="id_filtro_c" hidden value="1"></input>
                        <table id="tb_certificaciones" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">CORRELATIVO </th>
                              <th class=" text-center">BIENES </th>
                              <th class=" text-center">FECHA</th>
                              <th class=" text-center">DIRECCIÓN</th>
                              <th class=" text-center">DEPARTAMENTO</th>
                              <th class=" text-center">SOLICITANTE</th>
                              <th class=" text-center">ESTADO</th>
                              <th class=" text-center">GENERADOR</th>
                              <th class=" text-center">TIPO</th>
                              <th class=" text-center">ACCIÓN</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <iframe id="pdf_preview_v" hidden></iframe>


                </div>
            </div>
          </div>
        </div>

    <?php
  }
}else{
    header("Location: index.php");
}
?>
