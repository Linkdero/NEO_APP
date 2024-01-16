<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

    $nro_vale = $_GET["nro_vale"];
    $types = $_GET['tipo'];

    $xTipo = "Despachar";
    if ($types == 2) {
      $xTipo = "Anular";
    }

    ?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="vehiculos/js/validaciones.js"></script>


      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>

      <script src="vehiculos/js/vales_vue_des.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>


    </head>

    <body>

      <div class="modal-header">

        <h5 class="modal-title">
          <?php echo $xTipo ?> Vale de Combustible 
        </h5>

        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>

      <div class="modal-body" id="app_despacha_vale">
        <input value="<?php echo $nro_vale ?>" id="id_nro_vale" hidden></input>
        <input value="<?php echo $types ?>" id="id_tipo" hidden></input>
        <detalle-vale :nro_vale="nroVale" v-on:enviar_vale="recibirVale"></detalle-vale>
        <hr>
        <formulario-vales :tipo="types" :id_vale="nroVale" :data_vale="arreglo"></formulario-vales>

      </div>
    </body>
  <?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>