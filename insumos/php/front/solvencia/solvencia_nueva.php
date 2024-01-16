<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
    if (usuarioPrivilegiado_acceso()->accesoModulo(3549)){
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="assets/js/plugins/datatables/new/dataTables.rowsGroup.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/insumos/solvencia.js"></script>
    <script src="insumos/js/funciones_solvencia.js"></script>
    <script src="insumos/js/cargar.js"></script>
    <script src="insumos/js/source_modal.js"></script>
</head>
<body>
<script type="text/javascript">
    //reporte_solvencia(7);
</script>

<div class="">
    <div class="row">
        <div class="col-sm-3">
            <div class="input-group">
                <input id="id_persona" maxlength="4" class="form-control form-corto"
                       autocomplete="off" autofocus placeholder="No. Gafete" onkeyup="onkeyup_enter(event, 0,3);">
                <div class="input-group-append">
                    <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg"
                          href="insumos/php/front/empleados/get_empleados.php"><i class="fa fa-plus-circle"></i></span>
                </div>
            </div>
        </div>
    </div>
    <br>
    <iframe id="pdf_preview_estado" hidden></iframe>
    <div id="tran_form">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div Class="row" style="height:110px">
                            <div class="col-sm-12 ">
                                <div id="datos" class="form-group">
                                    Escriba el Número de Gafete del Empleado
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table id="tb_insumos_solvencia" class="table  table-hover table-bordered" style="width:100%;">
            <thead>
                <th>Transacción</th>
                <th>Anotaciones</th>
                <th>Producto</th>
                <th>Serie</th>
                <th>Movimiento</th>
                <th>Entregado</th>
                <th>Devuelto</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <br>
    <div class="">
        <span class="btn btn-block btn-info" id="btn_save_in" onclick="solvencia_crear()"><i class="fa fa-print"></i> Generar Solvencia</span>
    </div>


    <script>
        // $("#id_persona").on('keyup', function (e) {
        //     if (e.keyCode == 13) {
        //         get_empleado_insumos(0,3);
        //     }
        // });
    </script>
</div>
<?php }
else {
    include('inc/401.php');
}
}
else {
    header("Location: index.php");
}
?>
