
<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  // if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
	// $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
  //   foreach($datos AS $d){
  //     $bodega = $d['id_bodega_insumo'];
  //   }
?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="alimentos/js/source_reporte.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/alimentos/reporte_alimentos.js"></script>

<div class="">
  <table id="tb_alimentos_por_fecha" class="table table-striped table-hover table-bordered" width="100%">
    <thead>
      <tr>
        <th class=" text-center">Fecha</th>
        <th class=" text-center">Desayuno</th>
        <th class=" text-center">Almuerzo</th>
        <th class=" text-center">Cena</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot align="right">
      <tr><th>TOTALES</th><th></th><th></th><th></th></tr>
	  </tfoot>
  </table>
</div>

<script>

</script>
<?php

// }
// else{
//   include('inc/401.php');
// }
}
else{
  header("Location: index.php");
}
?>

