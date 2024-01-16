<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

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

    <script type="module" src="documentos/js/insumos/appInsumos.js"></script>

    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>


    <!--<script src="assets/js/plugins/jspdf/inventario/Sticker.js"></script>-->

    <script>
    $(document).ready(function(){
      /*setInterval(function(){
        show_nombramientos_pendientes_count();
      }, 5000);*/
    });

    </script>



    <div class="u-content">
        <div class="u-body" id="appInsumos">

	          <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 id="titulo_card" class="h3 card-header-title" >Control de Insumos</h2>
                    <ul class="list-inline ml-auto mb-0">
                      <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                        <li class='nav-item'>
                          <a class='nav-link active' data-toggle='tab' href='#insumoslist'>Insumos</a>
                        </li>
                        <li class='nav-item'>
                          <a class='nav-link' active data-toggle='tab' href='#medidaslist'>Medidas</a>
                        </li>
                      </ul>
                    </ul>
                </header>

                <div class="card-body card-body-slide">

                  <div class="tab-content">
                    <div id="insumoslist" class="tab-pane fade show active" role="tabpanel">

                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro" hidden value="2"></input>
                        <table id="tb_insumos" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>

                              <th class=" text-center">Identificador </th>
                              <th class=" text-center">Código </th>
                              <th class=" text-center" >cod. Presentación</th>
                              <th class=" text-center">Nombre</th>
                              <th class=" text-center" width="300px">Descripción</th>
                              <th class=" text-center">Presentación</th>
                              <th class=" text-center">Medida</th>
                              <th class=" text-center">Renglón</th>
                              <th class=" text-center">Estado</th>


                              <th class=" text-center">ACCIÓN</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div id="medidaslist" class="tab-pane fade show " role="tabpanel">

                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro" hidden value="2"></input>
                        <table id="tb_medidas" class="table table-actions table-striped table-bordered  " width="100%">
                          <thead>
                            <tr>

                              <th class=" text-center">Identificador </th>
                              <th class=" text-center">Nombre </th>
                              <th class=" text-center">Estado </th>
                              <th class=" text-center">Acción</th>
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
