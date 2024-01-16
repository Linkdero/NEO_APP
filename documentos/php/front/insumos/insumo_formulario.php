<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $ppr_id=null;
    $tipo = null;

    if ( !empty($_GET['ppr_id'])) {
      $ppr_id = $_REQUEST['ppr_id'];
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
  <script src="documentos/js/components/components1.16.js" ></script>
  <script type="module" src="documentos/js/insumos/appInsumoForm.js?t=<?php echo time();?>"></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="appInsumoForm">
  <div class="modal-header">
    <h3 v-if="tipo == 1">Crear Insumo</h3> <h3 v-else-if="tipo == 2">Editar Insumo</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">

    <form class="jsValidacionInsumo">
      <input id="ppr_id" name="ppr_id" value="<?php echo $ppr_id ?>" hidden></input>
      <input id="tipo_id" name="tipo_id" value="<?php echo $tipo ?>" hidden></input>
      <input id="cambio" hidden></input>
      <div class="row">
        <campo tipo="texto" row="col-sm-6" codigo="ppr_cod" label="Código de Insumo:*" requerido="true" :valor="insumo.Ppr_cod"></campo>
        <campo tipo="texto" row="col-sm-6" codigo="ppr_nom" label="Nombre del Insumo:*" requerido="true" :valor="insumo.Ppr_Nom"></campo>
        <combo :arreglo="arrayMedidas" label="Medida" codigo="cmb_medida_id" row="col-sm-6" requerido="true" tipo="3"></combo>
        <campo tipo="texto" row="col-sm-6" codigo="ppr_presentacion" label="Presentación:" requerido="true" :valor="insumo.Ppr_Pres"></campo>
        <campo tipo="texto" row="col-sm-6" codigo="ppr_cod_presentacion" label="Código Presentación:" requerido="true" :valor="insumo.Ppr_codPre"></campo>

        <campo tipo="textarea" row="col-sm-12" codigo="ppr_descripcion" label="Descripción del insumo:*" :valor="insumo.Ppr_Des" requerido="true"></campo>
        <renglones-listado columna="col-sm-12" :valor="insumo.Ppr_Ren"></renglones-listado>
        <checkbox codigo="Ppr_estado" :valor="insumo.Ppr_est"></checkbox>
        <div class="col-sm-12 text-right">
          <button class="btn btn-sm btn-info" @click="saveInsumo()"><i class="fa fa-check"></i>Guardar</button>
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
