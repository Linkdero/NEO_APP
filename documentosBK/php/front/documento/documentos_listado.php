<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){//privilegio documento

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src="documentos/js/source.js"></script>
    <script src="documentos/js/funciones.js"></script>
    <script src="documentos/js/cargar.js"></script>
    <script src="assets/js/plugins/docx/index.js"></script>
    <script src="assets/js/plugins/docx/FileSaver.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="assets/js/plugins/jspdf/documentos/licitacion.js"></script>

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

    <div class="u-content">
        <div class="u-body">
            <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Control de Documentos</h2>
                    <ul class="list-inline ml-auto mb-0">
                      <!--<li class="list-inline-item"  title="Buscar">
                        <span class="link-muted h3" onclick="buscar_nombramiento()">
                          <i class="fa fa-search"></i>
                        </span>
                      </li>-->
                      <?php if(usuarioPrivilegiado()->hasPrivilege(1)){?>
                        <li class="list-inline-item"  title="Nuevo Nombramiento">
                          <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/documento/documento_nuevo.php">
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
                                    <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_constancia_vacia()">
                                      <i class="fa fa-print mr-2"></i> Constancia vacía
                                    </a>
                                  </li>
                                  <li class="mb-1">
                                    <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_exterior_vacia()">
                                      <i class="fa fa-print mr-2"></i> Exterior Vacía
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
                        <input type="text" id="id_tipo_filtro" hidden value="1"></input>
                        <table id="tb_documentos" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                          <thead>
                            <tr>
                              <th class=" text-center">Documento</th>
                              <th class=" text-center" width="40px">Titulo</th>
                              <th class=" text-center">Categoría</th>
                              <th class=" text-center" width="40px">Correlativo</th>
                              <th class=" text-center">Fecha</th>
                              <th class=" text-center">Respuesta</th>
                              <th class=" text-center">Destinatarios</th>
                              <th class=" text-center">Acción</th>

                              <!--<th class=" text-center">Destino</th>
                              <!--<th class=" text-center">Motivo</th>
                              <th class=" text-center">Inicio</th>
                              <th class=" text-center">Final</th>

                              <th class=" text-center">Estado</th>

                              <th class=" text-center">Acción</th>-->
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
