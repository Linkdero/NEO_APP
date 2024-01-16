<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121) || usuarioPrivilegiado_acceso()->accesoModulo(8085)){

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">
    <script src="assets/js/plugins/datatables/new/dataTables.rowsGroup.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <!--<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
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
                    <h2 class="h3 card-header-title" >Reporte de Formularios</h2>
                    <ul class="list-inline ml-auto mb-0">


                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3" onclick="recargarFormsUtilizados()">
                          <i class="fa fa-sync"></i>
                        </span>
                      </li>
                    </ul>
                </header>

                <div class="card-body card-body-slide">
                  <div class="col-sm-6">
                    <div class="row" style=" z-index:2">
                      <div class="col-sm-4">
                        <input id="ini" class="form-control form-control-sm" value="<?php echo '2022-01-01'; ?>" type="date"></input>
                      </div>
                      <div class="col-sm-4">
                        <input id="fin" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" type="date"></input>
                      </div>
                    </div>
                  </div>
                  <br>

                  <table id="tb_formularios_utilizados" class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">
                    <thead>
                      <tr>

                        <th class=" text-center">Anticipo</th>
                        <th class=" text-center">Estado V-A</th>
                        <th class=" text-center">Constancia</th>
                        <th class=" text-center">Exterior</th>
                        <th class=" text-center">Liquidación</th>

                        <th class=" text-center">Comisión</th>
                        <th class=" text-center">Estado</th>

                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div>
        </div>

    <?php
  }
}else{
    header("Location: index.php");
}
?>
