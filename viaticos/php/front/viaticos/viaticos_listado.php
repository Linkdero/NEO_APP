<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

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

    <!--<script src="viaticos/js/source_3.1.js"></script>-->
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <!--<script src="viaticos/js/viatico_vue_1.3.js"></script>-->

    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script type="module" src="viaticos/js/components/GlobalComponents.js"></script>
    <script type="module" src="viaticos/js/appViaticos.js"></script>
    <script src="viaticos/js/funciones_1.3.js"></script>
    <script src="assets/js/pages/fechas.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>
    <script src="assets/js/plugins/jspdf/viaticos/liquidacion.1.2.js"></script>
    <script src="assets/js/plugins/jspdf/viaticos/impresiones_3.2.7.js"></script>

    <script>
    $(document).ready(function(){
      /*setInterval(function(){
        show_nombramientos_pendientes_count();
      }, 5000);*/
    });
    </script>

    <div class="u-content">
        <div class="u-body" id="appViatico">
            <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Solicitudes de Viáticos</h2>
                    <ul class="list-inline ml-auto mb-0">
                      <!--<li class="list-inline-item"  title="Buscar">
                        <span class="link-muted h3" onclick="buscar_nombramiento()">
                          <i class="fa fa-search"></i>
                        </span>
                      </li>-->
                      <?php if(usuarioPrivilegiado()->hasPrivilege(1)){?>
                        <li class="list-inline-item"  title="Nuevo Nombramiento">
                          <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="viaticos/php/front/viaticos/viatico_nuevo.php">
                            <i class="fa fa-plus"></i>
                          </span>
                        </li>
                      <?php }?>
                      <li class="list-inline-item"  title="Nombramientos Pendientes">
                        <span id="ns" class=" label-danger-count1 contar"></span>
                        <span class="link-muted h3">
                          <i class="fa fa-bell" id="actions1Invoker" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_nombramientos_pendiente()"></i>

                          <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px">
                            <div class="card overflow-hidden" style="margin-top:-f;">
                              <div class="card-header d-flex align-items-center py-3">
                                <h2 class="h4 card-header-title" onclick="recargar_nombramientos(2)"><i class="fa fa-table"></i> Ver Pendientes:</h2>
                              </div>
                              <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
                                <div id="menu_nombramientos"></div>
                              </div>
                            </div>
                          </div>
                        <!--<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>-->
                        </span>
                      </li>
                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3">
                          <i class="fa fa-print" id="actions1Invoker2" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick=""></i>

                          <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker2" style="margin-right:20px">
                            <div class="card overflow-hidden" style="margin-top:-f;">
                              <div class="card-header d-flex align-items-center py-3">
                                <h2 class="h4 card-header-title"><i class="fa fa-print"></i> Imprimir:</h2>
                              </div>
                              <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
                                <ul class="list-unstyled mb-0">
                                  <li class="mb-1">
                                    <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_constancia_vacia(1,'')">
                                      <i class="fa fa-print mr-2"></i> Constancia vacía
                                    </a>
                                  </li>
                                  <li class="mb-1">
                                    <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_exterior_vacia()">
                                      <i class="fa fa-print mr-2"></i> Exterior Vacía
                                    </a>
                                  </li>
                                  <li class="mb-1">
                                    <a class="d-flex align-items-center link-muted py-2 px-3" onclick="impresion_formato_informe()">
                                      <i class="fa fa-print mr-2"></i> Informe Personal
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </span>
                      </li>
                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3" onclick="recargar_nombramientos()">
                          <i class="fa fa-sync"></i>
                        </span>
                      </li>
                    </ul>
                </header>

                <div class="card-body card-body-slide">

                  <div class="tab-content">
                    <div id="nombramientos" class="tab-pane fade show active" role="tabpanel">
                      <div class="table-responsive">
                        <input type="text" id="id_tipo_filtro" hidden value="2"></input>
                        <table id="tb_viaticos" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Nombramiento</th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center" width="40px">Dirección</th>
                              <th class=" text-center">Tipo</th>
                              <th class=" text-center">Destino</th>
                              <!--<th class=" text-center">Motivo</th>-->
                              <th class=" text-center">Inicio</th>
                              <th class=" text-center">Final</th>

                              <th class=" text-center">Estado</th>

                              <th class=" text-center">Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <div class="row" >
                    <div class="col-sm-12">

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
