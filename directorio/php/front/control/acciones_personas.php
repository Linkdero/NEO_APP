<?php if (function_exists('verificar_session') && verificar_session()) : ?>
    <?php if (usuarioPrivilegiado_acceso()->accesoModulo(1875)) : ?>


        <script src="directorio/js/source_log.js"></script>
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
                                <h2 class="h3 card-header-title">Bitácora de Personal</h2>
                                <ul class="list-inline ml-auto mb-0">
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">



                                    <table id="tb_acciones" class="table table-striped table-hover" width="100%">
                                        <thead>
                                            <th class="text-center text-dark">
                                                <h3><strong>Foto</strong></h3>
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
                                            <!-- <th class="text-center text-dark"><h3><strong>Puesto Servicio</strong></h3></th>                                     -->
                                            <th class="text-center text-dark">
                                                <h3><strong>Última Acción</strong></h3>
                                            </th>
                                            <th class="text-center text-dark">
                                                <h3><strong>Tipo Acción</strong></h3>
                                            </th>
                                            <th class="text-center text-dark">
                                                <h3><strong>Detalle</strong></h3>
                                            </th>
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


        <?php else : ?>


        <?php endif; ?>

    <?php else : ?>


    <?php endif; ?>