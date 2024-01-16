<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $med_id=null;
    $tipo = null;

    if ( !empty($_GET['med_id'])) {
      $med_id = $_REQUEST['med_id'];
    }

    if ( !empty($_GET['tipo'])) {
      $tipo = $_REQUEST['tipo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_900");
    }else{
    }

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
  <script src="documentos/js/components/components1.14.js" ></script>
  <script type="module" src="documentos/js/insumos/appMedidaForm.js?t=<?php echo time();?>"></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="appMedidaForm">
  <div class="modal-header">
    <h3 v-if="tipo == 1">Crear Medida</h3> <h3 v-else-if="tipo == 2">Editar Medida</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">

    <form class="jsValidacionMedida">
      <input id="med_id" name="med_id" value="<?php echo $med_id ?>" hidden></input>
      <input id="tipo_id" name="tipo_id" value="<?php echo $tipo ?>" hidden></input>
      <input id="cambio" hidden></input>
      <div class="row">
        <campo tipo="texto" row="col-sm-6" codigo="med_nom" label="Nombre de la medida:*" requerido="true" :valor="medida.Med_nom"></campo>
        <checkbox codigo="med_estado" :valor="medida.Med_est"></checkbox>
        <div class="col-sm-12 text-right">
          <button class="btn btn-sm btn-info" @click="saveMedida()"><i class="fa fa-check"></i>Guardar</button>
        </div>
      </div>
    </form>


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
