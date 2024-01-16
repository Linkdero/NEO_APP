<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7852)){

?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="horarios/js/modelMarcajes.js"></script>

  <!--<script src="viaticos/js/source_modal.js"></script>-->
</head>
<body>
  <div id="app_upload">
    <div class="modal-header">
      <h4 class="modal-title">Subir marcajes en CSV</h4>
      <ul class="list-inline ml-auto mb-0">

        <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
          <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i>
          </span>
        </li>
      </ul>
    </div>

    <div class="modal-body">
      <div  class="slide_up_anim">
        <form class="jsValidacionUploadMarcaje form-horizontal" id="formValidacionUploadMarcaje">
          <p id="message"></p>
          <div class="form-group">
            <div class="col-xs-12">
              <div class="">
                <label for="user_vid">Archivo .csv*</label>
                <input id="archivo" accept="application/.xlsx" name="archivo" type="file" class="form-control"/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12 text-center">
              <button @click="uploadMarcajes" class="btn btn-sm btn-success btn-block" name="upload" id="upload"><i class="fa fa-upload"></i>  Crear Horarios</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

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
