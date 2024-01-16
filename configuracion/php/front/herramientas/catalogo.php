<?php if (function_exists('verificar_session') && verificar_session()): ?>
    <?php if(usuarioPrivilegiado_acceso()->accesoModulo(7851)): ?>


        
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
        <script src="assets/js/plugins/select2/select2.min.js"></script>
        <!-- <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet'/>
        <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
        <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script> -->

        <script src="configuracion/js/source_catalogo.js"></script>
        <div class="u-content">
            <div class="u-body">
                <div class="row">
                    <div class="col-md-12 mb-12 mb-md-0">
                        <div class="card">
                            <header class="card-header d-flex align-items-center">
                                <h2 class="h3 card-header-title">CONSULTA DE CATALOGO</h2>
                                <ul class="list-inline ml-auto mb-0">
                                </ul>
                            </header>
                            <div class="card-body card-body-slide">
                                <div class="">
                                
                                
                                    
                                <table id="tb_catalogo" class="table table-striped table-hover" width="100%">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p id="filter1"><label><b>CATALOGO:</b></label><br></p>
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <p id="filter2"><label><b>Grupo:</b></label><br></p>
                                        </div> -->
                                    </div>
                                    
                                    <thead>
                                    <th class="text-center text-dark"><h3><strong>ID CATALOGO</strong></h3></th>
                                    <th class="text-center text-dark"><h3><strong>CATALOGO DESCRIPCION</strong></h3></th>
                                    <th class="text-center text-dark"><h3><strong>CATALOGO COMENTARIO</strong></h3></th>
                                    <th class="text-center text-dark"><h3><strong>ITEM DESCRIPCION</strong></h3></th>
                                    <th class="text-center text-dark"><h3><strong>ITEM COMENTARIO</strong></h3></th>
                                    <th class="text-center text-dark"><h3><strong>ID ITEM</strong></h3></th>
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



