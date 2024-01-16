<?php
include_once '../../../../inc/functions.php';
include_once '../../../../empleados/php/back/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    include_once '../../back/functions.php';



?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">


<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
<script src="insumos/js/source_modal.js"></script>

<div class="">

  <table id="tb_empleados_asignados" class="table table-striped table-bordered" width="100%">
    <thead>
      <th class="text-center">GAFETE</th>
      <th class="text-center">NOMBRES</th>
      <th class="text-center">APELLIDOS</th>
      <th class="text-center">PUESTO</th>
      <th class="text-center">DIRECCIÓN</th>

      <th class="text-center">MARCA</th>
      <th class="text-center">MODELO</th>
      <th class="text-center">SERIE</th>

    </thead>
    <tbody>
    </tbody>

  </table>

</div>
<script>

</script>
<?php

}
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
