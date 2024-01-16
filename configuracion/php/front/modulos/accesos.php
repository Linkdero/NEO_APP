<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){//1163 módulo recursos humanos
  $modulo=null;

  if ( !empty($_GET['modulo'])) {
    $modulo = $_REQUEST['modulo'];
  }

  if ( !empty($_POST)){
    header("Location: index.php?ref=_500");
  }else{

  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="configuracion/js/cargar.js"></script>
  <script src="configuracion/js/funciones.js"></script>
  <script>
  mostrar_accesos_por_modulo(<?php echo $modulo?>);
  </script>
</head>
<body>
  <div class="modal-header">

    <ol class="breadcrumb bg-transparent  p-0 modal-title">
      <li class="breadcrumb-item" id="inicio"><a >Accesos</a></li>
      <li class="breadcrumb-item active" id="acceso" aria-current="page"></li>
    </ol>
    <span  class="btn-circle" data-dismiss="modal" aria-label="Close">

    </span>
  </div>
  <div id="panel_pantallas" class="modal-body">

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
