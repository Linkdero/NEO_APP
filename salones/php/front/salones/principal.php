<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7845)) : ?>

        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <script src="salones/js/source_salones.js"></script>
        <script src="salones/js/functions.js"></script>

        <div class="u-content">
            <div class="u-body">
                <div class="card mb-4">
                    <!-- Card Header -->
                    <header class="card-header d-md-flex align-items-center">
                        <h2 class="h3 card-header-title">Salones</h2>
                        <ul class="list-inline ml-auto mb-0">
                            <li class="list-inline-item" title="Nuevo Salon" data-toggle="modal" data-target="#modal-remoto" href="salones/php/front/salones/salon_nuevo.php">
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
                    <div class="card-body card-body-slide">
                        <table id="tb_salones" class="table table-sm table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Capacidad</th>
                                    <th class="text-center">Equipo Disponible</th>
                                    <!-- <th class="text-center">Usuario</th> -->
                                    <!-- <th class="text-center">Fecha</th> -->
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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