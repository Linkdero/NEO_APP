<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){

?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
<script src="insumos/js/source_modal.js"></script>
<div class="">



  <table id="tb_insumos_resguardo" class="table table-striped table-hover table-bordered" width="100%" >
    <thead>
      <th>Tipo</th>
      <th>Marca</th>
      <th>Modelo</th>
      <th>Serie</th>
      <th>Estado</th>
      <th>Gafete</th>
      <th>Empleado</th>


      <th>Acción</th>

    </thead>
    <tbody>
    </tbody>
  </table>
</div>
<script>

</script>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
