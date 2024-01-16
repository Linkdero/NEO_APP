<?php if (function_exists('verificar_session') && verificar_session()): ?>
    <?php if(usuarioPrivilegiado_acceso()->accesoModulo(7851)): ?>


        <script src="configuracion/js/source_extensiones.js"></script>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
        <script src="assets/js/plugins/select2/select2.min.js"></script>
        <script src='configuracion/js/funciones.js'></script>



        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">Configuración de Extensiones</h2>
                                    <ul class="list-inline ml-auto mb-0">
                                        <!-- <li class="list-inline-item" title="Nueva extensión" data-toggle="modal" data-target="#modal-remoto" href="configuracion/php/front/empleado/ins_usr_ext.php"> -->
                                            <span id="new" class="link-muted h3">
                                            <i class="fa fa-plus"></i>
                                            </span>
                                            
                                        </li>
                                        <!-- <li class="list-inline-item" title="Recargar">
                                            <span id="reload" class="link-muted h3" >
                                            <i class="fa fa-sync"></i>
                                            </span>
                                        </li> -->
                                    </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">
                                
                                
                                    
                                <table id="tb_directorio" class="table table-striped table-hover" width="100%">
                                <div class="row">
                                
                                <div class="col-sm-6">
                                <!-- <p id="filter1"><label><b>Dirección:</b></label><br></p>
                                </div>
                                </div> -->
                                    <thead>
                                        <th class="text-center text-dark"><h3><strong>Extensión</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Ubicación</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Puesto</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Empleado</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Correo</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Foto</strong></h3></th>
                                        <th class="text-center text-dark"><h3><strong>Acción</strong></h3></th>
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


    <?php else: ?>


    <?php endif; ?>

<?php else: ?>


<?php endif; ?>
