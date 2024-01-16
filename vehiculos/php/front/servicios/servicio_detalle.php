<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(8017)) {
    $id_servicio = $_GET['id_servicio'];
?>

    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="viaticos/js/cargar.js"></script>
      <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet" />
      <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

      <script src="assets/js/plugins/tiny/tiny.js" referrerpolicy="origin"></script>

      <script src="documentos/js/validaciones.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/components.js?t=<?php echo time(); ?>"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/servicios/modelServicioDetalle.js"></script>

      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>
      <script>
      </script>
    </head>

    <div id="app_orden_serviciod" style="-webkit-transform: translateZ(0);">
      <div class="modal-header">
        <h3>Orden de servicio</h3>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body bg-light">
        <input id="id_servicio" name="id_servicio" value="<?php echo $id_servicio ?>" hidden></input>
        <div class="row">
          <div class="col-sm-12">
            <component-servicio-detalle :id_servicio="idServicio"></component-servicio-detalle>
          </div>
        </div>
      </div>

  <?php
  } else {
    include('inc/401.php');
  }
} else {
  header("Location: index.php");
}
  ?>