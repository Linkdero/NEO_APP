<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(1875)) : ?>


        <script src="directorio/js/source_telefonos.js"></script>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
        <script src="assets/js/plugins/select2/select2.min.js"></script>



        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">Teléfonos Personales</h2>
                                <ul class="list-inline ml-auto mb-0">
                                    <?php if (usuarioPrivilegiado()->hasPrivilege(322)) : ?>
                                        <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                                            <li class='nav-item'>
                                                <a class='nav-link active' data-toggle='tab' href='#telper'>Telefonos Personales</a>
                                            </li>
                                            <li class='nav-item'>
                                                <a class='nav-link' active data-toggle='tab' href='#bustel' onclick=''>Busqueda por Teléfono</a>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">

                                    <div class="tab-content">
                                        <div id="telper" class="tab-pane fade show active" role="tabpanel">
                                            <table id="tb_directorio" class="table table-striped table-hover" width="100%">
                                                <div class="row">

                                                    <div class="col-sm-4">
                                                        <p id="filter1"><label><b>Dirección:</b></label><br></p>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <p id="filter2"><label><b>Grupo:</b></label><br></p>
                                                    </div>
                                                    <?php if (usuarioPrivilegiado()->hasPrivilege(321)) : ?>
                                                        <div class="col-sm-4">
                                                            <p id="filter3"><label><b>Estado:</b></label><br></p>
                                                        </div>
                                                    <?php else : ?>

                                                    <?php endif; ?>
                                                </div>

                                                <thead>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Foto</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>ID</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Nombre</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Dirección</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Puesto</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Grupo</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Promoción</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Estado</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Tipo Servicio</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>Detalle</strong></h3>
                                                    </th>
                                                    <th class="text-center text-dark">
                                                        <h3><strong>estado_persona</strong></h3>
                                                    </th>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="bustel" class="tab-pane fade" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12 mb-12 mb-md-0">
                                                    <div class="">
                                                        <table id="tb_tels" class="table table-sm table-bordered table-striped" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Número</th>
                                                                    <th class="text-center">Empleado</th>
                                                                    <th class="text-center">Gafete</th>
                                                                    <th class="text-center">Referencia</th>
                                                                    <th class="text-center">Tipo</th>
                                                                    <th class="text-center">observaciones</th>
                                                                    <th class="text-center">Activo</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        <?php else : ?>


        <?php endif; ?>

    <?php else : ?>


    <?php endif; ?>