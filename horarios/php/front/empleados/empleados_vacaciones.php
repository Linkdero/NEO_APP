<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) : ?>

        <script src="horarios/js/source.js"></script>
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
                                <h2 class="h3 card-header-title">Reporte Personal</h2>
                                <ul class="list-inline ml-auto mb-0">
                                    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7852)) : ?>
                                        <li class="list-inline-item">
                                            <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/feriados/feriados.php">
                                                <i class="fa fa-plus" data-toggle="tooltip" data-placement="left" title="Permisos y Ausencias"></i>
                                            </span>
                                        </li>
                                    <?php endif; ?>

                                    <li class="list-inline-item" title="Recargar">
                                        <span class="link-muted h3" onclick="">
                                            <i class="fa fa-sync" data-toggle="tooltip" data-placement="left" title="Recargar"></i>
                                        </span>
                                    </li>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">
                                    <table id="tb_empleados" class="table table-sm table-bordered table-striped" width="100%">
                                        <thead>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p id="filter1"><label><b>Dirección:</b></label><br></p>
                                                </div>
                                                <!-- <div class="col-sm-6">
                                            <p id="filter2"><label><b>Grupo:</b></label><br></p>
                                        </div> -->
                                                <div class='col-sm-3'>
                                                    <p>
                                                        <label><b>Mes:</b></label>
                                                        <select id='month_1' class='form-control' onchange='reload_detalle()'>
                                                            <option value='1'>Enero</option>
                                                            <option value='2'>Febrero</option>
                                                            <option value='3'>Marzo</option>
                                                            <option value='4'>Abril</option>
                                                            <option value='5'>Mayo</option>
                                                            <option value='6'>Junio</option>
                                                            <option value='7'>Julio</option>
                                                            <option value='8'>Agosto</option>
                                                            <option value='9'>Septiembre</option>
                                                            <option value='10'>Octubre</option>
                                                            <option value='11'>Noviembre</option>
                                                            <option value='12'>Diciembre</option>
                                                        </select><br></p>
                                                </div>
                                                <div class='col-sm-3'>
                                                    <p id='year'>
                                                        <label><b>Año:</b></label>
                                                        <select id='year_1' class='form-control' onchange='reload_detalle()'>
                                                            <option value='2021'>2021</option>  
                                                            <option value='2020'>2020</option>
                                                        </select><br>
                                                </div>
                                            </div>
                                            <tr>
                                                <th class="text-center">Fotografia</th>
                                                <th class="text-center">Empleado</th>
                                                <th class="text-center">D Nominal</th>
                                                <th class="text-center">P Nominal</th>
                                                <th class="text-center">D Funcional</th>
                                                <th class="text-center">P Funcional</th>
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