<?php
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);
if (function_exists('verificar_session')) {
    $permisos = array();
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8931);

    $pos = $array[3]['id_persona'];
    $permisos = array(
        'ageno' => ($array[2]['flag_insertar'] == 1) ? 1 : 0,
    );
    ?>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet"
        href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

    <script src="assets/js/plugins/select2/select2.min.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src="assets/js/pages/components.js"></script>
    <script type="module" src="tickets/diagnosticos/src/diagnosticosList.js"></script>
    <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
    <script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
    <script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>

    <style>
        div.scrollmenu {
            height: 100px;
            overflow-y: scroll;
        }
    </style>

    <div id="body" class="u-content" width="100%">
        <div id="appDiagnosticos" class="u-body">
            <div class="row">
                <div class="col-md-12 mb-12 mb-md-0">
                    <div class="card h-100">
                        <header class="card-header d-flex align-items-center">
                            <h2 class="h3 card-header-title">{{tituloModulo}}</h2>
                            <!-- Card Header Icon -->
                            <ul class="list-inline ml-auto mb-0">
                                <li class="list-inline-item" title="Agregar Diagnostico">
                                    <span class="link-muted h3" @click="setNuevoDiagnostico()">
                                        <i class="fa fa-plus"></i>
                                </li>
                                <li class="list-inline-item" title="Recargar">
                                    <span class="link-muted h3" @click="recargarDiagnosticos()">
                                        <i class="fa fa-sync"></i>
                                </li>
                            </ul>
                        </header>
                        <div class="card-body card-body-slide" width="100%" height="100%">
                            <table id="tblDiagnosticos" class="table responsive table-sm table-bordered table-striped"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Diagnostico</th>
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Solicitante</th>
                                        <th class="text-center">Equipo</th>
                                        <th class="text-center">#Bien</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Estado</th>
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
    <?php
} else {
    header("Location: index");
}
?>
<style>
    #body {
        min-width: 100%;
    }
</style>