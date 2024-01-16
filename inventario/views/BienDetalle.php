<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){


    $bien_id=null;
    $codigo = null;
    $tipo = null;

    if ( !empty($_GET['bien_id'])) {
      $bien_id = $_REQUEST['bien_id'];
    }

    if ( !empty($_GET['sicoin_code'])) {
      $sicoin_code = $_REQUEST['sicoin_code'];
    }

    if ( !empty($_GET['tipo'])) {
      $tipo = $_REQUEST['tipo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      /*include_once '../../back/functions.php';
      $clase= new insumo();
*/
      /*$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }*/
    }


?>
<input id="bien_id" name="bien_id" value="<?php echo $bien_id ?>" hidden></input>
<input id="sicoin_code" name="sicoin_code" value="<?php echo $sicoin_code ?>" hidden></input>
<input id="tipo" name="tipo" value="<?php echo $tipo ?>" hidden></input>

<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="inventario/src/appInventarioDetalle.js?t=<?php echo time();?>"></script>
<div id="biendetalle" style="-webkit-transform: translateZ(0);">
  <!--<div class="text-center" style="position: absolute; background-color: rgba(255, 255, 255, 0.7); min-width:100%; min-height:100%; z-index:5; border-radius:3px" v-if="loading == false">
    <div class="loaderr" style="margin-top:150px"></div>
  </div>-->
  <bienheader :bien_id="bien_id" :sicoin_code="sicoin_code" tipo="1"></bienheader>
  <div class="modal-body">
    <privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <!--<estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>-->
    <biendetalle :bien_id="bien_id" :tipo="tipo"></biendetalle>
    <div class="row" v-if="(privilegio.verificar == true || privilegio.auditoria_verificar == true) && tipo == 1">
      <div class="col-sm-12">
        <hr>
        <strong><h1>Verificar bien</strong></h1>
      </div>
      <bienestadolist></bienestadolist>
      <bienlugarlist></bienlugarlist>
      <conductores row="col-sm-12" label="Empleado responsable:" codigo="id_persona" requerido="true"></conductores>
      <div class="col-sm-12 text-right">
        <span class="btn btn-sm btn-info" @click="verificarBien()"><i class="fa fa-check-circle"></i> Verificar</span>
      </div>
    </div>
    <div class="row" v-else-if="tipo == 2">

    </div>
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
