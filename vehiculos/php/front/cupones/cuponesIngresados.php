<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
$id_documento = $_GET["id_documento"];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>

    <script type="module" src="vehiculos/js/cuponesIngresados.js?t=<?php echo time(); ?>"></script>
</head>

<div class="modal-header bg-info">
    <h4 class="modal-title text-white font-weight-bold"> <i class="fas fa-plus-circle"></i> Ingreso del Lote No.<?php echo $id_documento ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true" class="text-white font-weight-bold">&times;</span>
    </button>
</div>

<div id="ingresoCupones" class="modal-body">
    <input id="id_documento" value="<?php echo $id_documento ?>" hidden></input>
    <input type="hidden" id="id_filtro" value="3"></input>

    <table id="tbsCuponesIngresados" class="table table-sm table-striped table-hover table-bordered" width="100%">
        <thead>
            <th class="text-center"># Documento</th>
            <th class="text-center">ID Cupon</th>
            <th class="text-center"># Cupon</th>
            <th class="text-center">Monto</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Fecha</th>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>