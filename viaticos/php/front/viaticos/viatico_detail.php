<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $tipo_filtro=null;
    $bodega;
    if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_REQUEST['id_viatico'];
    }
    if(!empty($_GET['tipo_filtro'])){
      $tipo_filtro=$_REQUEST['tipo_filtro'];
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
<input id="id_viatico" name="id_viatico" value="<?php echo $id_viatico ?>" hidden></input>
<input id="id_opcion" name="id_opcion" value="1" hidden></input>
<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="viaticos/js/appViaticoDetalle.js?t=<?php echo time();?>"></script>
<div id="vdetalle" style="-webkit-transform: translateZ(0);">
  <div class="text-center" style="position: absolute; background-color: rgba(255, 255, 255, 0.7); min-width:100%; min-height:100%; z-index:5; border-radius:3px" v-if="loading == false">
    <div class="loaderr" style="margin-top:150px"></div>
  </div>
  <viaticoheaders :id_viatico="id_viatico"></viaticoheaders>
  <div class="modal-body">
    <privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>
    <viaticodetalle v-if="option == 1":id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" v-on:sendviatico="getViaticoDetalle"></viaticodetalle>
    <viaticoempleados v-else-if="option == 2" :key="keyReload" :id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" :viatico="viatico"></viaticoempleados>
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
