<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){//1163 módulo recursos humanos
    $id_persona=null;
    $tipo=null;
    if( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }
    if(!empty($_GET['tipo'])){
      $tipo=$_REQUEST['tipo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_500");
    }else{
      ?>
      <script>

      <?php
      if($tipo==1){
        ?>
        obtener_accesos_por_persona(<?php echo $id_persona?>);
        <?php
      }else{
        ?>
        obtener_accesos_pendiente_por_persona(<?php echo $id_persona?>);
        <?php
      }

      ?>
      </script>
      <?php
      //$persona=empleado::get_empleado_by_id($id_persona);

    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="configuracion/js/cargar.js"></script>
  <script src="configuracion/js/funciones.js"></script>

</head>
<body>
  <div class="modal-header">

    <ol class="breadcrumb bg-transparent  p-0 modal-title">
      <li class="breadcrumb-item" id="inicio"><a >Empleados</a></li>
      <li class="breadcrumb-item active" id="acceso" aria-current="page"></li>
    </ol>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Asignar módulos">
        <span class="link-muted h3" onclick="cargar_asignar_privilegios()">
          <i class="fa fa-plus"></i>
        </span>
      </li>
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>


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
