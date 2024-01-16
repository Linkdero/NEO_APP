<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){
    include_once '../../back/functions.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="configuracion/js/funciones.js"></script>
</head>
<body>
<div class="modal-header">
    <h5 class="modal-title">Nuevo Módulo</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <form class="validation_nuevo_modulo">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="nombre">Nombre</label>
                <div class=" input-group  has-personalizado" >
                  <input type="text" class=" form-control " id="nombre" name="nombre" placeholder="Nombre de módulo" required autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="descripcion">Descripción</label>
                <div class=" input-group  has-personalizado" >
                  <textarea type="text" rows='3' class=" form-control " id="descripcion" name="descripcion" placeholder="Describa el módulo" required autocomplete="off"/></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <span class="btn btn-block btn-sm btn-info" onclick="save_modulo()"><i class="fa fa-save"></i> Guardar</span> -->
      <button class="btn btn-block btn-sm btn-info" onclick="save_modulo()"><i class="fa fa-save"></i> Guardar</button>
    </form>
  </div>



  </div>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
