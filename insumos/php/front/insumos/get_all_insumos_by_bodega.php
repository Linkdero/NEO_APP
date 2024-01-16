<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
    if (usuarioPrivilegiado_acceso()->accesoModulo(3549)) {
?>
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.css">
        <script src="//cdn.datatables.net/plug-ins/preview/searchPane/dataTables.searchPane.min.js"></script>
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
        <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet" />
        <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
        <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

        <script src="insumos/js/source_modal.js"></script>

        <table id="tb_insumos" class="table table-actions table-striped table-hover table-bordered  table-intel" width="100%">
            <thead>
                <tr>
                    <th>Sicoin</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Casilla</th>
                    <th>Existencia</th>
                    <th class="filter">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="button-group">
                                        <a class="link-info d-flex align-items-center" data-toggle="dropdown">
                                            Estado
                                            <i class="fa fa-filter text-muted ml-1"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1">
                                                    <label for="chk_disponible" class="small d-flex align-items-center link-muted py-2 px-3 mr-3 ">
                                                        <input type="checkbox" id="chk_disponible" name="pos" value="DISPONIBLE" data-text="DISPONIBLE" />
                                                        &nbsp;DISPONIBLE
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="ASIGNADO" data-text="ASIGNADO" />
                                                        &nbsp;ASIGNADO
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="ASIGNADO TEMPORAL" data-text="ASIGNADO TEMPORAL" />
                                                        &nbsp;ASIGNADO TEMPORAL
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="REPARACION" data-text="REPARACION" />
                                                        &nbsp;REPARACION
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="BAJA" data-text="BAJA" />
                                                        &nbsp;BAJA
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3">
                                                        <input type="checkbox" name="pos" value="RESGUARDO" data-text="RESGUARDO" />
                                                        &nbsp;RESGUARDO
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="EXTRAVIADO" data-text="EXTRAVIADO" />
                                                        &nbsp;EXTRAVIADO
                                                    </label>
                                                </li>
                                                <li class="mb-1">
                                                    <label class="small d-flex align-items-center link-muted py-2 px-3 margin-right-2">
                                                        <input type="checkbox" name="pos" value="MAL ESTADO" data-text="MAL ESTADO" />
                                                        &nbsp;MAL ESTADO
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </th>
                    <th>Gafete</th>
                    <th>Empleado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>

        <script>
            var options = [];
            $('.dropdown-menu span').on('click', function(event) {

                var $target = $(event.currentTarget),
                    val = $target.attr('data-value'),
                    $inp = $target.find('input'),
                    idx,
                    positions;
                //build a regex filter string with an or(|) condition

                if ((idx = options.indexOf(val)) > -1) {
                    options.splice(idx, 1);
                    setTimeout(function() {
                        /*$inp.prop( 'checked', false */
                        filterme();
                    }, 0);
                } else {
                    options.push(val);
                    setTimeout(function() {
                        /*$inp.prop( 'checked', true*/
                        filterme();
                    }, 0);
                    //filter in column 1, with an regex, no smart filtering, not case sensitive
                    //alert(x);
                }

                $(event.target).blur();

                console.log(options);

                return false;
            });


            function filterme() {
                //build a regex filter string with an or(|) condition
                var x;
                var types = $('input:checkbox[name="pos"]:checked').map(function() {
                    x = '^' + this.value + '\$';
                    return x;
                }).get().join('|');
                //alert(x);
                //filter in column 0, with an regex, no smart filtering, no inputbox,not case sensitive
                table_insumos.column(6).search(types, true, false, false).draw(false);
                console.log(types);
            }
        </script>
<?php } else {
        include('inc/401.php');
    }
} else {
    header("Location: index.php");
}
?>