<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7845)) : ?>

        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <script src="salones/js/source_solicitudes.js"></script>
        <script src="salones/js/functions.js"></script>
        <script src='assets/js/plugins/datatables/pdfmake.min.js'></script>
        <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>

        <div class="u-content">
            <div class="u-body">
                <div class="card mb-4">
                    <!-- Card Header -->
                    <header class="card-header d-md-flex align-items-center">
                        <h2 class="h3 card-header-title">Solicitar Salón</h2>
                        <ul class="list-inline ml-auto mb-0">
                            <li class="list-inline-item" title="Nueva Solicitud" data-toggle="modal" data-target="#modal-remoto" href="salones/php/front/solicitar/solicitud_nueva.php">
                                <span id="new" class="link-muted h3">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </li>
                            <li class="list-inline-item" title="Recargar">
                                <span id="reload" class="link-muted h3">
                                    <i class="fa fa-sync"></i>
                                </span>
                            </li>
                        </ul>
                    </header>
                    <!-- End Card Header -->

                    <!-- Card Body -->
                    <div class="card-body card-body-slide">
                        <div class="tab-content">
                            <div class="table-responsive">
                                <!-- <input type="text" id="id_tipo_filtro" hidden value="1"></input> -->
                                <table id="tb_reservaciones" class="table table-actions table-striped table-bordered responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th class=" text-center">Salón</th>
                                            <th class=" text-center">Solicitante</th>
                                            <th class=" text-center">Motivo</th>
                                            <th class=" text-center">Fecha</th>
                                            <th class=" text-center">Hora</th>
                                            <th class=" text-center">Audiovisuales</th>
                                            <th class=" text-center">Mobiliario</th>
                                            <th class=" text-center">Estado</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Card Body -->
                </div>
            </div>
            <script>
                $("#reload").click(() => {
                    reload();
                });
            </script>
        <?php else : ?>
            <?php include('inc/401.php'); ?>
        <?php endif; ?>
    <?php else : ?>
        <?php header("Location: index.php"); ?>
    <?php endif; ?>