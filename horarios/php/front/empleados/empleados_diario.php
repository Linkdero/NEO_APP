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
                                  <?php if (usuarioPrivilegiado_acceso()->accesoModulo(7851)) { ?>
                                  <li class="list-inline-item" title="Subir marcajes">
                                    <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="horarios/php/front/marcajes/subir_marcajes.php">
                                      <i class="fa fa-upload" data-toggle="tooltip" data-placement="left" title="Subir CSV"></i>
                                    </span>
                                  </li>
                                  <?php }?>
                                  <li class="list-inline-item" title="Recargar">
                                    <span class="link-muted h3" onclick="refresh_diario()">
                                      <i class="fa fa-sync" data-toggle="tooltip" data-placement="left" title="Recargar"></i>
                                        </span>
                                  </li>
                                  <li class="list-inline-item" data-toggle="tooltip" title="Filtros">

                                    <span class="link-muted h3" href="#!" role="button" id="dropdownMenuLinkBirthday" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                      <i class="fa fa-cog"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu- dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLinkBirthday" style="width: 260px;">
                                      <div class="card overflow-hidden">
                                        <div class="card-header py-3">
                                          <!-- Storage -->
                                          <div class="d-flex align-items-center mb-3">
                                            <span class="h6 text-muted text-uppercase mb-0">Filtros</span>

                                            <div class="ml-auto text-muted">
                                              <!--<strong class="text-dark">60gb</strong> / 100gb-->
                                            </div>
                                          </div>
                                        </div>

                                        <div class="card-body animacion_right_to_left" >
                                          <div class="">
                                            <div class="form-group">
                                                <p id="fdir"><label><b>Dirección:</b></label><br></p>
                                            </div>
                                            <div class="form-group">
                                                <p id="ftar"><label><b>Entrada:</b></label><br></p>
                                            </div>
                                            <div class="form-group">
                                                <p id="fmar"><label><b>Marcaje:</b></label><br></p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="fecha">Fecha:</label>
                                            <input type="date" id="fecha" class="form-control form-control-sm" onchange="refresh_diario()" value="<?php date_default_timezone_set('America/Guatemala');
                                                                                                                                    echo date("Y-m-d", time()); ?>">
                                        </div>

                                    </div>
                                    <div class="col-sm-12">
                                        <table id="tb_reporte_diario" class="table table-sm table-bordered table-striped table-bordered" width="100%">
                                            <thead style="z-index:99999999999">
                                                <tr>

                                                    <th class="text-center fixed-top-th">ID</th>
                                                    <th class="text-center fixed-top-th">Fotografia</th>
                                                    <th class="text-center fixed-top-th">Empleado</th>
                                                    <th class="text-center fixed-top-th">Direccion Funcional</th>
                                                    <th class="text-center fixed-top-th">Puesto Funcional</th>
                                                    <th class="text-center fixed-top-th">Entrada</th>
                                                    <th class="text-center fixed-top-th">Almuerzo</th>
                                                    <th class="text-center fixed-top-th">Salida</th>
                                                    <th class="text-center fixed-top-th">Tarde</th>
                                                    <th class="text-center fixed-top-th">Cuenta</th>
                                                    <th class="text-center fixed-top-th">Correlativo</th>
                                                    <th class="text-center fixed-top-th">Horario Laboral</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div></div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>


            <?php endif; ?>

        <?php else : ?>


        <?php endif; ?>
