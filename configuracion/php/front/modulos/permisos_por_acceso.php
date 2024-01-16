<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){

    $acceso = $_POST['acceso'];

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
  <input type="text" id="id_acceso" value="<?php echo $acceso?>" hidden></input>
<div class="">
  <table id="tb_permisosaccesos" class="table table-bordered table-striped  " width="100%">
    <thead>
      <tr>
        <th class="text-center">Acceso</th>
        <th class="text-center">Menu</th>
        <th class="text-center">Insertar</th>
        <th class="text-center">Eliminar</th>
        <th class="text-center">Actualizar</th>

        <th class="text-center">Imprimir</th>
        <th class="text-center">Acceso</th>
        <th class="text-center">Autoriza</th>
        <th class="text-center">Descarga</th>


      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
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
