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
    <h3 class="modal-title">Cambiar contraseña</h3>
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
          <div class="input-group-append">
            <button id="show_password" class="btn btn-info" type="button" onclick="mostrarPassword()"> <span class="fa fa-eye-slash icon"></span> </button>
          </div>
          <input type="password" id="clave" class="form-control" placeholder="Ingrese el nuevo password" autocomplete="off"></input>
        </div>
      </div>
      <div class="col-sm-3">
        <span class="btn btn-info btn-block" onclick="cambiar_password(<?php echo $_SESSION['id_persona']?>)"><i class="fa fa-check"></i> Guardar</span>
      </div>
    </div>
  </div>
  <script>
    function mostrarPassword(){
      let input_clave = $("#clave");
        if(input_clave.attr("type") == "password"){
          $("#clave").attr("type", "text");
          $('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        }else{
          $("#clave").attr("type", "password");
          $('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        }
    }
  </script>
</div>
</div>
<?php 
}
else{
  header("Location: index");
}
?>
