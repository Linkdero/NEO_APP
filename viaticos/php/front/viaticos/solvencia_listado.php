<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">


    <script src="viaticos/js/source_3.3.js"></script>
    <script>
    $(document).ready(function(){

    });
    </script>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalBody" class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div class="u-content">
        <div class="u-body">
            <div class="card mb-4 ">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Solvencia listado</h2>
                    <ul class="list-inline ml-auto mb-0">


                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3" onclick="">
                          <i class="fa fa-sync"></i>
                        </span>
                      </li>
                    </ul>
                </header>

                <div class="card-body card-body-slide">
                  <table id="tb_empleados_solvencia" class="table table-sm table-actions table-striped table-bordered nowrap mb-0" width="100%">
                    <thead>

                        <th class=" text-center">Nombramiento</th>
                        <th class=" text-center">Fotografía</th>
                        <th class=" text-center">Empleado</th>
                        <th class=" text-center">Correlativo Dirección</th>
                        <th class=" text-center"> Acción

                        </th>

                        </thead>

                    </table>

                </div>
            </div>
        </div>
        <!--<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
        <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>-->
<?php

  }
}else{
    header("Location: index.php");
}
?>
