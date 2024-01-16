<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7845)) : ?>

        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

        <link rel="stylesheet" href="assets/js/plugins/fullcalendar/packages/core/main.css">
        <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/daygrid/main.css'>
        <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/timegrid/main.css'>
        <link rel='stylesheet' href='assets/js/plugins/fullcalendar/packages/list/main.css'>

        <script src='assets/js/plugins/fullcalendar/packages/core/main.js'></script>
        <script src='assets/js/plugins/fullcalendar/packages/interaction/main.js'></script>
        <script src='assets/js/plugins/fullcalendar/packages/daygrid/main.js'></script>
        <script src='assets/js/plugins/fullcalendar/packages/timegrid/main.js'></script>
        <script src='assets/js/plugins/fullcalendar/packages/list/main.js'></script>
        <script src='assets/js/plugins/fullcalendar/packages/core/locales/es.js'></script>
        <script src='salones/js/functions.js'></script>

        <div class="u-content">
            <div class="u-body">
                <div class="card mb-4">
                    <header class="card-header d-md-flex align-items-center">
                        <h2 class="h3 card-header-title">Calendario de Salones</h2>
                        <ul class="list-inline ml-auto mb-0">
                            <li class="list-inline-item" title="Nueva Solicitud" data-toggle="modal" data-target="#modal-remoto" href="salones/php/front/solicitar/solicitud_nueva.php">
                                <span id="new" class="link-muted h3">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </li>
                            <li class="list-inline-item" title="Recargar" onclick="cargar_calendario(2);">
                                <span id="reload" class="link-muted h3">
                                    <i class="fa fa-sync"></i>
                                </span>
                            </li>
                        </ul>
                    </header>
                    <div class="card-body card-body-slide">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <script src='salones/js/calendar.js'></script>
        <?php else : ?>
            <?php include('inc/401.php'); ?>
        <?php endif; ?>
    <?php else : ?>
        <?php header("Location: index.php"); ?>
    <?php endif; ?>