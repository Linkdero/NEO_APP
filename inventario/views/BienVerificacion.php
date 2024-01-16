<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){


    $bien_id=null;

    if ( !empty($_GET['bien_id'])) {
      $bien_id = $_REQUEST['bien_id'];
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
<input id="bien" name="bien" value="<?php echo $bien ?>" hidden></input>

<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="viaticos/js/appInventarioDetalle.js?t=<?php echo time();?>"></script>
<div id="biendetalle" style="-webkit-transform: translateZ(0);">
  <div class="text-center" style="position: absolute; background-color: rgba(255, 255, 255, 0.7); min-width:100%; min-height:100%; z-index:5; border-radius:3px" v-if="loading == false">
    <div class="loaderr" style="margin-top:150px"></div>
  </div>
  <bienheaders :bien_id="bien_id"></bienheaders>
  <div class="modal-body">
    <!--<privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>-->
    <biendetalle :bien_id="bien_id"></biendetalle>
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
