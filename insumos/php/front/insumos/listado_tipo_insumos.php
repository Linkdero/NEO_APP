<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){//1163 módulo recursos humanos


?>

  <script src="insumos/js/source.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">





  <div class="u-content">
    <div class="u-body">
      <!-- Overall Income -->
      <div class="row">
        <!-- Current Projects -->
        <div class="col-md-12 mb-12 mb-md-0">
          <div class="card h-100">
            <header class="card-header d-flex align-items-center">
              <h2 class="h3 card-header-title">Tipo de Insumos</h2>

              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item" data-toggle="tooltip" title="Empleados">
                  <span class="link-muted h3">
                    <i class="fa fa-users"></i>
                  </span>
                </li>
                <li class="list-inline-item" data-toggle="tooltip" title="Nuevo módulo">
                  <span class="link-muted h3" >
                    <i class="fa fa-plus"></i>
                  </span>
                </li>
                <li class="list-inline-item" data-toggle="tooltip" title="Recargar">
                  <span class="link-muted h3" >
                    <i class="fa fa-sync"></i>
                  </span>
                </li>
              </ul>

              <!-- End Card Header Icon -->
            </header>

            <div class="card-body card-body-slide">
              <div class="">

              <table id="tb_tipo_insumos_listado" class="table table-sm table-bordered table-striped  " width="100%">
                <thead>
                  <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Descripcion</th>

                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- End Card Body -->
          </div>
          <!-- End Overall Income -->
        </div>
      </div>
      </div>
      </div><?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
