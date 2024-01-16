<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){
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
    <script src="vehiculos/js/vales_vue_new.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
  

    <script>
      setTimeout(() => {
        $("#id_conductor" ).select2({
        })}, 400);
    </script>    

</head>
<body>
<div class="modal-header">
    <h5 class="modal-title">Nuevo Vale de Combustible </h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" id="app_nuevo_vale">
   
   <!-- *** validaciones_vue.js  ( ) -->
   <formulario-vales tipo="1"></formulario-vales>
    <form class="validation_nuevo_vale" name="miForm">

      
    </form>
  </div>



</div>
  <script>
    set_dates();

   </script>

<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
