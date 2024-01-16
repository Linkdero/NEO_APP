<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){
    $tipo = $_GET['tipo'];
    $clase_proceso = $_GET['clase_proceso'];
    $orden = $_GET['nro_orden'];
    //echo $tipo_ope;
    /*include_once '../../back/functions.php';
    $p_i = documento::get_pedido_by_pedido_interno(1,date('Y'));
    echo $p_i['Ped_tra'];*/
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script src="documentos/js/validaciones.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="assets/js/pages/components.js"></script>
  <script src="documentos/js/components/components1.4.js"></script>
  <script src="documentos/js/facturas/ordenvue.js" ></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_orden">


  <div class="modal-header">
    <h3>{{ titulo }}</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" >
    <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>
    <input id="cambio" hidden></input>
    {{ tag }}
    <hr>
    <form v-if="tipo == 0 || tipo == 1" class="jsValidacionOrdenOperar" id="formValidacionOrdenOperar">
      <input type="text" id="id_tipo" name="id_tipo" value="<?php echo $tipo?>" hidden></input>
      <input type="text" id="id_clase_proceso" name="id_clase_proceso" value="<?php echo $clase_proceso?>" hidden></input>
      <input type="text" id="nro_orden" name="nro_orden" value="<?php echo $orden?>" hidden></input>
      <div class="row">
        <campo row="col-sm-12" :label="label" codigo="id_campo" tipo="number" requerido="true"></campo>
        <div class="col-sm-12">
          <button class="btn btn-sm btn-block btn-info" @click="operarOrden()"><i class="fa fa-check-circle"></i> Guardar</button>
        </div>
      </div>

    </form>

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
