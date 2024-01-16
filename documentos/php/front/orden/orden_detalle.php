<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){
    $id_pago = $_GET['id_pago'];

?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <script src="documentos/js/orden/viewModelOrdenDetalle.js" ></script>
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="appOrdenDetalle">


  <div class="modal-header">
    <h3>Detalle de {{ ordenDetalle.tipo_pago }}</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" >
    <input id="id_pago" name="id_pago" value="<?php echo $id_pago;?> " hidden></input>
    <div class="row">
      <div class="col-sm-6">
        <dato-persona icono="fa fa-receipt" texto="Tipo de pago:" :dato="ordenDetalle.tipo_pago" tipo="1"></dato-persona>
        <dato-persona icono="fa fa-hashtag" texto="No. de registro:" :dato="ordenDetalle.nro_registro" tipo="1"></dato-persona>
      </div>
      <div class="col-sm-6">
        <dato-persona icono="fa fa-edit" texto="CUR de compromiso:" :dato="ordenDetalle.cur_compromiso" tipo="1"></dato-persona>
        <dato-persona icono="fa fa-edit" texto="CUR de devengado:" :dato="ordenDetalle.cur_devengado" tipo="1"></dato-persona>
      </div>
    </div>
    <br>
    <hr>
    <div class="row" v-if="visible == false">
      <div class="col-sm-12 text-center">
        <div class="spinner-grow  text-info" role="status" >
          <span class="sr-only">Cargando facturas</span>
        </div>
      </div>
    </div>
    <h3 class="text-info" v-if="visible == true">Facturas asignadas a esta operación:</h3>
    <facturas-seleccionadas v-if="visible == true" v-bind:arreglo="ordenDetalle.facturas" tipo="1"></facturas-seleccionadas>

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
