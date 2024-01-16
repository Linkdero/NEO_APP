<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){
    $tipo = $_GET['tipo'];
    $arreglo = $_GET['arreglo'];
    $parametros = substr($arreglo, 1);
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
  <script src="documentos/js/components/components1.9.js"></script>
  <script src="documentos/js/facturas/facturaasigtecnico.js" ></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_tecnico_global" style="-webkit-transform: translateZ(0);">
  <input type="text" id="id_tipo" value="<?php echo $tipo?>" hidden></input>
  <input type="text" id="orden_id" value="<?php echo $parametros?>" hidden></input>
  <div class="modal-header">
    <h3>Asignar técnico a esta factura</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" >
    <div class="card card-body">
      <asignar-tecnico columna="col-sm-12" option="1" :orden_id="orden_id" :privilegio="privilegio" tipo="2"></asignar-tecnico>
      <hr>
      <!--<asignar-modalidad-compra :factura="factura" :privilegio="privilegio"></asignar-modalidad-compra>-->
    </div>
    <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>
    <input id="cambio" hidden></input>

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
