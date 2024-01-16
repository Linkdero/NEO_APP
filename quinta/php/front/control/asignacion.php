<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7846)){
?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="quinta/js/source_modal.js"></script>
    <script src="quinta/js/functions.js"></script>


    <div class="u-content">
        <div class="u-body">
            <div class="card mb-4">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Reporte Visitas</h2>
                </header>
                <div class="card-body card-body-slide">

                    <table id="tb_asignaciones" class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">
                        <thead>
                        <tr>
                          <th class="text-center">Gafete</th>
                            <th class="text-center">Agente</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Puerta</th>
                            <th class="text-center">Acción</th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="./assets/js/plugins/chosen/chosen.jquery.js"></script>
        <script src="./assets/js/plugins/chosen/docsupport/prism.js"></script>
        <script src="./assets/js/plugins/chosen/docsupport/init.js"></script>
        <link rel="stylesheet" href="./assets/js/plugins/chosen/chosen.css">
    <?php
  }
}else{
    header("Location: index.php");
}
?>
