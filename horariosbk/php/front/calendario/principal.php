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


        <div class="u-content">
            <div class="u-body">
                <div class="card mb-4">
                    <header class="card-header d-md-flex align-items-center">
                        <h2 class="h3 card-header-title">Calendario de Vacaciones</h2>
                        <ul class="list-inline ml-auto mb-0">
                        </ul>
                    </header>
                    <div class="row">
                        <!-- <div class='col-sm-1'>
                        </div> -->
                        <div class='col-sm-3' style="padding-left:25px;">
                            <br>
                            <?php if (usuarioPrivilegiado()->hasPrivilege(298)) : ?>
                                <label><b>Dirección:</b></label>
                                <select id='direccion' class='form-control' onchange='cargar_calendario(2)'>
                                    <option>TODOS</option>
                                    <option value='0'>DESPACHO DEL SECRETARIO</option>
                                    <option value='1'>SUBSECRETARIA ADMINISTRATIVA</option>
                                    <option value='2'>SUBSECRETARIA DE SEGURIDAD</option>
                                    <option value='3'>ASESORIA JURIDICA</option>
                                    <option value='4'>AUDITORIA INTERNA</option>
                                    <option value='5'>UNIDAD DE INSPECTORIA</option>
                                    <option value='6'>DIRECCION ADMINISTRATIVA Y FINANCIERA</option>
                                    <option value='7'>DIRECCION DE ASUNTOS INTERNOS</option>
                                    <option value='8'>DIRECCION DE COMUNICACIONES E INFORMATICA</option>
                                    <option value='9'>DIRECCION DE INFORMACION</option>
                                    <option value='10'>DIRECCION DE RECURSOS HUMANOS</option>
                                    <option value='11'>DIRECCION DE RESIDENCIAS</option>
                                    <option value='12'>DIRECCION DE SEGURIDAD</option>
                                    <option value='13'>SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES</option>
                                    <option value='14'>S/D</option>
                                </select>
                            <?php endif; ?>
                            <?php if (usuarioPrivilegiado()->hasPrivilege(297)) : ?>
                                <label><b></b></label>
                                <select id='direccion' class='form-control' onchange='cargar_calendario(2)' style="display: none;">
                                    <option>TODOS</option>
                                    <option value='0'>DESPACHO DEL SECRETARIO</option>
                                    <option value='1'>SUBSECRETARIA ADMINISTRATIVA</option>
                                    <option value='2'>SUBSECRETARIA DE SEGURIDAD</option>
                                    <option value='3'>ASESORIA JURIDICA</option>
                                    <option value='4'>AUDITORIA INTERNA</option>
                                    <option value='5'>UNIDAD DE INSPECTORIA</option>
                                    <option value='6'>DIRECCION ADMINISTRATIVA Y FINANCIERA</option>
                                    <option value='7'>DIRECCION DE ASUNTOS INTERNOS</option>
                                    <option value='8'>DIRECCION DE COMUNICACIONES E INFORMATICA</option>
                                    <option value='9'>DIRECCION DE INFORMACION</option>
                                    <option value='10'>DIRECCION DE RECURSOS HUMANOS</option>
                                    <option value='11'>DIRECCION DE RESIDENCIAS</option>
                                    <option value='12'>DIRECCION DE SEGURIDAD</option>
                                    <option value='13'>SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES</option>
                                    <option value='14'>S/D</option>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body card-body-slide">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
            </div>
            <script src='horarios/js/calendar.js'></script>
        <?php else : ?>
            <?php include('inc/401.php'); ?>
        <?php endif; ?>
    <?php else : ?>
        <?php header("Location: index.php"); ?>
    <?php endif; ?>