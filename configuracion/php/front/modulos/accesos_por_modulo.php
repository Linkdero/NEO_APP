<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){

    $modulo = $_POST['modulo'];

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="configuracion/js/source_modal.js"></script>

</head>
<body>
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
  <input type="text" id="id_modulo" value="<?php echo $modulo?>" hidden></input>
  <table id="tb_accesos" class="table table-sm table-bordered table-striped  " width="100%">
    <thead>
      <tr>
        <th class="text-center">Módulo</th>
        <th class="text-center">Acceso</th>
        <th class="text-center">Gafete</th>
        <th class="text-center">Empleado</th>
        <th class="text-center">Acceso</th>
        <th class="text-center">Acción</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</body>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
