<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){

?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">


<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="insumos/js/source_modal.js"></script>

<div class="">
  <table id="tb_totales_por_direccion" class="table table-striped table-bordered" width="100%">
    <thead>
      <th class="text-center">DIRECCIÓN</th>
      <th class="text-center">PERMANENTE</th>
      <th class="text-center">TEMPORAL</th>

    </thead>
    <tbody>
    </tbody>
    <tfoot align="right">
		<tr><th>TOTAL</th><th></th><th></th></tr>
	</tfoot>
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
