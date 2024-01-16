<?php
include_once '../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    //include_once '../../back/functions.php';
    $asignacion_id_=null;
    $tipo_filtro=null;
    $nombremodal = null;
    $vista = null;
    $privilegio = null;
    $bodega;

    if ( !empty($_GET['asignacion_id_'])) {
      $asignacion_id_ = $_REQUEST['asignacion_id_'];
    }

    if ( !empty($_GET['nombremodal'])) {
      $nombremodal = $_REQUEST['nombremodal'];
    }

    if ( !empty($_GET['vista'])) {
      $vista = $_REQUEST['vista'];
    }

    if ( !empty($_GET['privilegio'])) {
      $privilegio = $_REQUEST['privilegio'];
    }
    if (!empty($_POST)){
      header("Location: index.php?ref=_3030");
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
<input id="asignacion_id_" name="asignacion_id_" value="<?php echo $asignacion_id_ ?>" hidden></input>
<input id="id_opcion_" name="id_opcion_" value="1" hidden></input>
<input id="nombremodal_" name="nombremodal_" value="<?php echo $nombremodal ?>" hidden></input>
<textarea id="privilegio_" name="privilegio_" value="" hidden><?php echo json_encode($privilegio) ?></textarea>
<input id="vista_" name="vista_" value="<?php echo $vista ?>" hidden></input>
<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="transportes/src/appAsignacionDetalle.js?t=<?php echo time();?>"></script>
<div id="asigdetalle" style="-webkit-transform: translateZ(0);">
  <transporteheader :solicitud_id="asignacion_id_" :evento="evento" tipo="asignacion"></transporteheader>
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-3">
        <transportelist :solicitud_id="asignacion.codigos" :evento="evento" tipo="2"></transportelist>
      </div>
      <div class="col-sm-9">
        <solicitudvehiculos :solicitud_id="asignacion_id_" :privilegio="privilegio" :evento="evento" tipo="asignacion" :asignacion="asignacion"></solicitudvehiculos>
      </div>
    </div>

    <!--<transportelist :solicitud_id="solicitud_id" :evento="evento" :tipo="tipo"></transportelist>-->
    <!--<privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>
    <viaticodetalle v-if="option == 1":id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" v-on:sendviatico="getViaticoDetalle"></viaticodetalle>
    <viaticoempleados v-else-if="option == 2" :key="keyReload" :id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" :viatico="viatico"></viaticoempleados>-->
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
