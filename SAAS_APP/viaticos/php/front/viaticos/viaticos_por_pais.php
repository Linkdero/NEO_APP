<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

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
    <script src="viaticos/js/source.js"></script>
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
                    <h2 class="h3 card-header-title" >Reporte de Viáticos</h2>
                    <ul class="list-inline ml-auto mb-0">


                      <li class="list-inline-item"  title="Recargar">
                        <span class="link-muted h3" onclick="">
                          <i class="fa fa-sync"></i>
                        </span>
                      </li>
                    </ul>
                </header>

                <div class="card-body card-body-slide">
                  <div class="row" >
                    <div class="col-sm-12">
                      <div class="row">

                        <div class="col-sm-2" style="z-index:55;">
                          <div class="form-group">
                            <div class="">
                              <label for="id_tipo">Tipo de Viático:</label>
                              <select id="id_tipo" class="js-select2 form-control" onchange="recargar_reporte()">
                                <!--<option value='0'>Todos</option>-->
                                <option value='1'>Nacionales</option>
                                <option value='2'>Internacionales</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-1" style="z-index:55;">
                          <div class="form-group">
                            <div class="">
                              <label for="id_mes">Mes:</label>
                              <select id="id_mes" class="js-select2 form-control" onchange="recargar_reporte()">
                                <?php
                                for ($x=1; $x<=12; $x++) {
                                  if ($x == date('m'))
                                  echo '<option value="'.$x.'">'.User::get_nombre_mes($x).'</option>';
                                  else
                                  echo '<option value="'.$x.'">'.User::get_nombre_mes($x).'</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-1" style="z-index:55;">
                          <div class="form-group">
                            <div class="">
                              <label for="id_year">Año:</label>
                              <select id="id_year" class="js-select2 form-control" onchange="recargar_reporte()">
                                <?php
                                for($i=date('o'); $i>=2017; $i--){
                                  if ($i == date('o'))
                                  echo '<option value="'.$i.'" >'.$i.'</option>';
                                  else
                                  echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <!--<div class="col-sm-1" style="z-index:55;">
                          <div class="form-group">
                            <div class="form-material">
                              <label for="btn"></label>
                              <button class="btn btn-info " style="margin-top:30px" onclick="recargar_reporte()"><i class="fa fa-sync"></i></button>
                            </div>
                          </div>
                        </div>-->




                      </div>
                    </div>
                  </div>

                    <table id="tb_reporte" class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">


                        <thead>
                        <tr>
                            <th class=" text-center">Nombramiento</th>
                            <th class=" text-center">Empleado</th>
                            <th class=" text-center">Dirección</th>

                            <th class=" text-center">Salida</th>
                            <th class=" text-center">Regreso</th>
                            <th class=" text-center">País</th>
                            <th class=" text-center">Departamento</th>
                            <th class=" text-center">Municipio</th>
                            <th class=" text-center">Total real</th>
                            <th class=" text-center">Total por mes</th><!--
                            <th class=" text-center">Acción</th>-->

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <script>

        $("#direcciones").select2({
    placeholder: "Seleccionar una dirección",
    allowClear: true
});
      </script>

    <?php
  }
}else{
    header("Location: index.php");
}
?>
