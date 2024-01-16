
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){ // 1163 Módulo Empleados
//h3Xhg3jvCcrX9ygp

?>


<script src="configuracion/js/cargar.js"></script>
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap4.min.css">-->
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
        <div class="card h-100">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Módulos de la aplicación</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item"  title="Empleados">
                <!--<span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lgg2" href="configuracion/php/front/modulos/menu_empleados.php">
                  <i class="fa fa-users"></i>
                </span>-->
                <span class="link-muted h3" onclick="obtener_empleados()">
                  <i class="fa fa-users"></i>
                </span>
              </li>
              <li class="list-inline-item"  title="Nuevo módulo">
                <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto" href="configuracion/php/front/modulos/nuevo_modulo.php">
                  <i class="fa fa-plus"></i>
                </span>
              </li>
              <li class="list-inline-item"  title="Recargar">
                <span class="link-muted h3" onclick="cargar_modulos()">
                  <i class="fa fa-sync"></i>
                </span>
              </li>
            </ul>

            <!-- End Card Header Icon -->
          </header>

          <div id="info_modulos" class="card-body card-body-slide card-body-slide">

          <!-- Tab Content -->



      <!-- End Card Body -->
    </div>
    <!-- End Overall Income -->
  </div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
  cargar_modulos();
});
</script>
<!-- End Comments -->
<?php
}
else{
  //include('../../../../../inc/401.php');
}
}
else{
  header("Location: index");
}
?>
