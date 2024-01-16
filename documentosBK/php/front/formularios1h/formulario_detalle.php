<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $env_tra=null;

    if ( !empty($_GET['env_tra'])) {
      $env_tra = $_REQUEST['env_tra'];
    }

    $formulario=null;

    if ( !empty($_GET['formulario'])) {
      $formulario = $_REQUEST['formulario'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_900");
    }else{

    }
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script src="documentos/js/validaciones.js"></script>
  <script src="documentos/js/funciones.js"></script>

  <script src="documentos/js/components/components1.2.js"></script>
  <script src="documentos/js/1h_detalle_vue.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_formulario_detalle">
  <div class="modal-header">
    <h3>Detalle del Formulario</h3>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado btn-sm" checked @click="getOpcion(1)" >
          <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(2)">
          <input type="radio" name="options" id="option2" autocomplete="off"> Seguimiento
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)">
          <input type="radio" name="options" id="option3" autocomplete="off"> Bitácora
        </label>

        <label class="btn btn-personalizado btn-sm salida" >
          <span name="options" id="option3" autocomplete="off"  > Salir
        </label>
      </div>
    </ul>
  </div>
  <div class="modal-body bg-light">
    <input id="env_tra" value="<?php echo $env_tra?>" hidden></input>
    <input id="formulario" value="<?php echo $formulario?>" hidden></input>

    <div class="row">
      <formulario-1h :env_tra="envioTra" :formulario="formulario" v-on:detalle-form-1h="get1hchild"></formulario-1h>
      <br><br>
    </div>
  </div>
  <div class="modal-body">
    <div v-if="opcion == 1" class="row">
      <productos-1h :env_tra="envioTra"></productos-1h>
    </div>
    <div v-else-if="opcion == 2">
      Seguimiento
      <seguimiento-1h :arreglo="f1h"></seguimiento-1h>
    </div>
    <div v-else-if="opcion == 3" class="row">
      Bitácora
    </div>
      <!-- fin factura -->
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
