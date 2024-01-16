<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="configuracion/js/funciones.js"></script>
</head>
<body>
  <div class="modal-header">
    <h3 class="modal-title">Cambiar usuario</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-9">
        <div class="input-group">
          <input type="username" id="username" class="form-control" placeholder="<?php echo $_GET['user']?>" autocomplete="off"></input>
        </div>
      </div>
      <div class="col-sm-3">
        <span class="btn btn-info btn-block" onclick="cambiar_username(<?php echo $_GET['id_persona']?>)"><i class="fa fa-check"></i> Guardar</span>
      </div>
    </div>
  </div>
</div>
</div>
<?php 
}
else{
  header("Location: index");
}
?>
