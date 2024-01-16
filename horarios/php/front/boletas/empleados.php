<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) : ?>
        <script src="assets/js/plugins/jspdf/jspdf.js"></script>
        <script src="assets/js/plugins/jspdf/insumos/solvencia.js"></script>
        <script src="horarios/js/source_boletas.js"></script>
        <script src="horarios/js/functions.js"></script>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <script src='assets/js/plugins/datatables/pdfmake.min.js'></script>
        <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>
        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card h-100">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">Control de Vacaciones</h2>
                                <ul class="list-inline ml-auto mb-0">
                                    <?php if (usuarioPrivilegiado()->hasPrivilege(293) or usuarioPrivilegiado()->hasPrivilege(295) or usuarioPrivilegiado()->hasPrivilege(296)) :  ?>
                                        <iframe id="pdf_preview_v" hidden></iframe>
                                        <li class="list-inline-item">
                                            <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/feriados/feriados.php">
                                                <i class="fa fa-address-card" data-toggle="tooltip" data-placement="left" title="Feriados y Ausencias"></i>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">
                                    <table id="tb_empleados" class="table table-sm table-bordered table-striped" width="100%">
                                        <thead>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p id="filter1"><label><b>Periódo Pendiente:</b></label><br></p>
                                                </div>
                                                <?php if (usuarioPrivilegiado()->hasPrivilege(298)) :  ?>
                                                    <div class="col-sm-6">
                                                        <p id="filter2"><label><b>Dirección:</b></label><br></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <tr>
                                                <th class="text-center">Fotografia</th>
                                                <th class="text-center">Empleado</th>
                                                <th class="text-center">Dirección Funcional</th>
                                                <th class="text-center">Puesto Funcional</th>
                                                <th class="text-center">Días Pendientes</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>


            <?php endif; ?>

        <?php else : ?>


        <?php endif; ?>