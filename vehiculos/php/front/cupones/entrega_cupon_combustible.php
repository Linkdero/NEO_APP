<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {


?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">



      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/cupones_detalle_vue.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

      <script>
      </script>

      <style>
      </style>

    </head>
    <div id="app_solicitar_cupones">

      <div class="modal-header">
        <h5 class="modal-title">Entrega de cupones --- </h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>

      <div id="" class="modal-body">
        <form class="validation_cupones_detalle" name="miForm">

          <!-- <form-entrega-cupones tipo="1" v-on:cancelar="cancelarDevol"></form-entrega-cupones> -->
          <!-- <form-entrega-cupones>prueba</form-entrega-cupones> -->

          <form-entrega-cupones></form-entrega-cupones>



      </div>

    </div>
<?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>