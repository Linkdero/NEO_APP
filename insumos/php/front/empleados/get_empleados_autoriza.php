<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
    if (usuarioPrivilegiado_acceso()->accesoModulo(3549)) {
        ?>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet"
              href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet"
              href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <script src="insumos/js/source_modal_2.js"></script>
        <div class="modal-body">
            <span class="btn-circle" data-dismiss="modal" aria-label="Close"></span>
            <input id="myPopupInput1" type="text" hidden>
            <br><br>
            <table id="tb_empleados_autoriza" class="table table-striped table-hover table-bordered table-responsive" width="100%">
                <thead>
                    <th>Gafete</th>
                    <th>Empleado</th>
                    <th>Estado</th>
                    <th>Añadir</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    <?php } else {
        include('inc/401.php');
    }
} else {
    header("Location: index.php");
}
?>
