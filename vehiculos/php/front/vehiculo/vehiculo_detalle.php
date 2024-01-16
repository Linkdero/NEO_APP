<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){
    $id_vehiculo=null;
    $bodega;
    if ( !empty($_GET['id_vehiculo'])) {
      $id_vehiculo = $_REQUEST['id_vehiculo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{

    }


?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="vehiculos/js/components.js"></script>
  <script src="vehiculos/js/vehiculo_vue.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script type="text/javascript">




</script>

</head>
<div id="appVehiculoDetalle">
  <div class="modal-header">
    <h4 class="modal-title">Detalle del Vehículo</h4>
    <ul class="list-inline ml-auto mb-0">

      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>
  <input id="id_vehiculo" hidden value="<?php echo $id_vehiculo;?>"></input>
  <div class="modal-body">
    <div class="row">
        <div class="col-sm-4">
    		<div class="invoice-title">
                <foto-vehiculo :id_vehiculo="id_vehiculo"></foto-vehiculo>
    		</div>
    	</div>
        <div class="col-sm-8">
            <vehiculo :id_vehiculo="id_vehiculo" tipo="1"></vehiculo>
        </div>
    </div>

</div>

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
