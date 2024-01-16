<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8326)){

    $requisicion_id = null;
    $bodega = null;

    if ( !empty($_GET['requisicion_id'])) {
      $requisicion_id = $_REQUEST['requisicion_id'];
    }

    if ( !empty($_GET['bodega'])) {
      $bodega = $_REQUEST['bodega'];
    }


?>
<input id="requisicion_id" name="requisicion_id" value="<?php echo $requisicion_id ?>" hidden></input>
<input id="bodega" name="bodega" value="<?php echo $bodega ?>" hidden></input>

<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="bodega/src/appRequisicionDetalle.js?t=<?php echo time();?>"></script>
<div id="reqdetalle" style="-webkit-transform: translateZ(0);">
  <style>
  .div_insumos{
    max-height:1600px; overflow-y: auto; overflow-x: hidden
  }
  </style>
  <!--{{ privilegios }} -- -- requision estado : {{ req.requisicionStatus }}-->
  <retornaprivilegios v-on:enviaprivilegios="getPrivilegiosFromComponent"></retornaprivilegios>
  <reqheader :requisicion_id="requisicion_id"></reqheader>
  <div class="modal-body">
    <div class="row">
      <!--{{ req.requisicionBodegaId }}
      <br>Permiso :::: {{ req.permisoEdicion }}
      <br>bodega ::::: {{ req.permisoBodega }}
      <br>Visualizar:: {{ visualizarBodega }}-->
      <div >

      </div>
      <div class="col-sm-2">
        <div class="row" style=" z-index:100;  margin-left:-120px">
          <reqdetalle :requisicion_id="requisicion_id" v-on:send_status_req="getRequisicionInfo" :evento="evento" :permisoedicion="req.permisoEdicion" tipo="1"></reqdetalle>
        </div>
      </div>
      <!--<span class="btn btn-sm btn-info"  @click="reloadTable()"><i class="fa fa-sync fa-spin"></i> Reload</span>-->
      <div class="col-sm-10  div_insumos">
        <form class="jsValidacionRequisicionUpdate">
          <!--{{ privilegios.solicita_autoriza }}-->
          <div class="row">
            <insumosfiltrado v-if="req.requisicionStatus == 9 || req.requisicionStatus == 8" columna_global="col-sm-9" columna="col-sm-5" :evento="evento" :datos_tabla="insumos" tipo="2" :id_bodega="req.requisicionBodegaId" id_pro="filtro_form_edit"></insumosfiltrado>
            <insumos :evento="evento" crud="u" :requisicion_id="requisicion_id" v-on:send_insumos_to_parent="getInsumosL" fase="1" :req="req" :privilegio="privilegios" :bodega="visualizarBodega" :id_bodega="req.requisicionBodegaId" :permisoedicion="req.permisoEdicion"></insumos>

            <div class="col-sm-12 text-right" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 8) && privilegios.solicita_autoriza == true">
              <button class="btn btn-sm btn-info" @click="updateRequisicion(6,$event)"><i class="fa fa-check"></i> Autorizar</button>
              <button class="btn btn-sm btn-warning" @click="updateRequisicion(8,$event)"><i class="fa fa-check"></i> Rechazar</button>
              <button class="btn btn-sm btn-danger" @click="updateRequisicion(5,$event)"><i class="fa fa-check"></i> Anular</button>
            </div>
            <div class="col-sm-12 text-right" v-if="privilegios.bodega_residencias_rev == true && req.permisoBodega == '01'">
              <!-- residencias -->
              <button class="btn btn-sm btn-info" v-if="req.requisicionStatus == 6 || req.requisicionStatus == 9" @click="updateRequisicion(2,$event)"><i class="fa fa-check"></i> En revisión</button>
              <button class="btn btn-sm btn-info" v-if="req.requisicionStatus == 2 && privilegios.bodega_residencias_aut == true" @click="updateRequisicion(3,$event)"><i class="fa fa-check"></i> Autorizarrr</button>
              <button class="btn btn-sm btn-warning" v-if="privilegios.bodega_residencias_aut == true && (req.requisicionStatus == 9 || req.requisicionStatus == 6 || req.requisicionStatus == 3)" @click="updateRequisicion(9,$event)"><i class="fa-sharp fa-solid fa-circle-xmark"></i> Rechazado</button>

            </div>
            <div class="col-sm-12 text-right" v-else-if="visualizarBodega == true && req.permisoBodega == '04' &&  (privilegios.bodega_financiero_rev == true || privilegios.inspectoria == true)">
              <!-- financiero -->
              <button id="f1" class="btn btn-sm btn-info" v-if="req.requisicionStatus == 6 && privilegios.inspectoria_autoriza == true" @click="updateRequisicion(2,$event)"><i class="fa fa-check-circle"></i> En revisión</button>
              <button id="f1" class="btn btn-sm btn-info" v-if="req.requisicionStatus == 2 && privilegios.inspectoria_autoriza == true" @click="updateRequisicion(7,$event)"><i class="fa fa-check-circle"></i> Autorizar </button>
              <button id="f2" class="btn btn-sm btn-danger" v-if="req.requisicionStatus == 2 && privilegios.inspectoria_autoriza == true" @click="updateRequisicion(5,$event)"><i class="fa fa-times-circle"></i> Anular</button>

              <button id="f2" class="btn btn-sm btn-info" v-if="req.requisicionStatus == 7 && privilegios.bodega_financiero_aut == true" @click="updateRequisicion(3,$event)"><i class="fa fa-check-circle"></i> Autorizar</button>
            </div>

            <div class="col-sm-12 text-right" v-else-if="(privilegios.bodega_edificios_rev || privilegios.bodega_talleres_rev == true || privilegios.bodega_armeria_rev == true || privilegios.bodega_academia_rev == true ) && visualizarBodega == true">
              <!-- financiero -->
              <button id="f1" class="btn btn-sm btn-info" v-if="req.requisicionStatus == 6" @click="updateRequisicion(2,$event)"><i class="fa fa-check-circle"></i> En revisión</button>
              <button id="f2" class="btn btn-sm btn-info" v-if="req.requisicionStatus == 2" @click="updateRequisicion(3,$event)"><i class="fa fa-check-circle"></i> Autorizar</button>
              <button id="f2" class="btn btn-sm btn-danger" v-if="req.requisicionStatus == 2" @click="updateRequisicion(5,$event)"><i class="fa fa-times-circle"></i> Anular</button>
            </div>
          </div>
        </form>


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
