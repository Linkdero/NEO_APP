<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7844)){//1163 módulo recursos humanos





?>

  <script src="empleados/js/source.js"></script>

      <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap4.min.css">
      <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>-->
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">


  <div class="u-content">
    <div class="u-body">
      <!-- Overall Income -->
      <div class="row">
        <!-- Current Projects -->
        <div class="col-md-12 mb-12 mb-md-0">
          <div class="card h-790">
            <header class="card-header d-flex align-items-center">
              <h2 class="h3 card-header-title">Asignacion de tiempos de comida por empleado</h2>

              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">

              </ul>

              <!-- End Card Header Icon -->
            </header>

            <div class="card-body card-body-slide">
              <div class="">

              <table id="tb_empleados_listado" class="table table-sm table-bordered table-striped  " width="100%">
                <thead>
                  <tr>
                    <th class="text-center">fotografia</th>
                    <th class="text-center">Nombre de Empleado </th>
                    <!--<th class="text-center">Email</th>  -->
                    <th class="text-center">NIT</th>
                    <th class="text-center">IGSS</th>
                    <th class="text-center">Descripcion</th> 
                    <th class="text-center">Estado</th>
                    <th class="text-center">NISP</th>
                    <th class="text-center">Ingreso</th>
                    <th class="text-center">Baja</th> 
                    <th class="text-center">Acción</th>
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
  header("Location: index");
}
?>
