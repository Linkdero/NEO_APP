<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) : ?>

        <script src="horarios/js/source_diario.js"></script>
        <script src="horarios/js/functions.js"></script>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card h-100">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">Reporte Diario</h2>
                                <ul class="list-inline ml-auto mb-0">
                                    <li class="list-inline-item" title="Recargar">
                                        <span class="link-muted h3" onclick="refresh_diario()">
                                            <i class="fa fa-sync" data-toggle="tooltip" data-placement="left" title="Recargar"></i>
                                        </span>
                                    </li>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="fecha">Fecha:</label>
                                            <input type="date" id="fecha" class="form-control" onchange="refresh_diario()" value="<?php date_default_timezone_set('America/Guatemala');
                                                                                                                                    echo date("Y-m-d", time()); ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="">
                                    <table id="tb_reporte_diario" class="table table-sm table-bordered table-striped" width="100%">
                                        <thead>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p id="fdir"><label><b>Dirección:</b></label><br></p>
                                                </div>
                                                <div class="col-sm-3">
                                                    <p id="ftar"><label><b>Entrada:</b></label><br></p>
                                                </div>
                                                <div class="col-sm-3">
                                                    <p id="fmar"><label><b>Marcaje:</b></label><br></p>
                                                </div>
                                            </div>

                                            <tr>

                                                <th class="text-center">ID</th>
                                                <th class="text-center">Fotografia</th>
                                                <th class="text-center">Empleado</th>
                                                <th class="text-center">Direccion Funcional</th>
                                                <th class="text-center">Puesto Funcional</th>
                                                <th class="text-center">Entrada</th>
                                                <th class="text-center">Almuerzo</th>
                                                <th class="text-center">Salida</th>
                                                <th class="text-center">Tarde</th>
                                                <th class="text-center">Cuenta</th>
                                                <th class="text-center">Correlativo</th>
                                                <th class="text-center">Horario Laboral</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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