<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    //include_once '../../back/functions.php';
    $codigos=null;
    $tipo_filtro=null;
    $tipo = null;
    $nombremodal = null;
    $bodega;
    if ( !empty($_GET['codigos'])) {
      $codigos = $_REQUEST['codigos'];
    }

    if ( !empty($_GET['tipo'])) {
      $tipo = $_REQUEST['tipo'];
    }

    if ( !empty($_GET['nombremodal'])) {
      $nombremodal = $_REQUEST['nombremodal'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_3030");
    }else{
    }


?>
<input id="codigos" name="codigos" value="<?php echo $codigos ?>" ></input>
<input id="nombremodal" name="nombremodal" value="<?php echo $nombremodal ?>" hidden></input>
<input id="tipo" name="tipo" value="<?php echo $tipo ?>" hidden></input>
<input id="id_opcion" name="id_opcion" value="1" hidden></input>
<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script src="assets/js/pages/components.js"></script>


<script type="module" src="transportes/src/appTransporteAsignacion.js?t=<?php echo time();?>"></script>

<div id="stasignacion" style="-webkit-transform: translateZ(0);">
  <transporteheader solicitud_id="0" :evento="evento"></transporteheader>
  <div class="modal-body" v-if="tipo == 2">

    <div class="row">
      <div class="col-sm-4">
        <transportelist :solicitud_id="codigos" :evento="evento" :tipo="tipo"></transportelist>
      </div>
      <div class="col-sm-8" v-if="fechasCount.length == 1">
        <formulariovehiculo :evento="evento" tipo="1" :arreglo="arreglo"></formulariovehiculo>
        <!--<form class="jsValidacionAsignarVehiculoGlobal">
          <div class="row">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
              <div>Seleccionar tipo de vehículos</div>

              <input type="radio" id="rd_propios" value="1144" v-model="picked" @change="onChange($event)" checked/>
              <label for="one">Propios</label>

              <input type="radio" id="rd_externo" value="1147" v-model="picked" @change="onChange($event)"/>
              <label for="two">Arrendados</label>
              <i style="" class="fa fa-sync fa-spin text-center"></i>
            </div>
            <div class="col-sm-4">
            </div>
            <conductores row="col-sm-4" label="Conductor asignado" codigo="id_quien_lleva" requerido="true"></conductores>
            <vehiculoslist  codigo="id_vehiculo" columna="col-sm-4" :evento="evento" requerido="true"></vehiculoslist>
            <combo row="col-sm-3" codigo="id_tipo_transporte" label="Tipo de Transporte" :arreglo="tipoTransporte" tipo="3" requerido="true"></combo>
            <div class="col-sm-1">
              <br>
              <button class="btn btn-sm btn-info btn-block" @click="addNewRow()"><i class="fa fa-plus-circle"></i></button>
            </div>
          </div>
        </form>-->
        <div class="row">
          <vehiculosseleccionadoslist :solicitud_id="codigos" :evento="evento" :nombremodal="nombremodal"></vehiculosseleccionadoslist>
        </div>

        <!--<conductores row="col-sm-12" label="Persona conducirá el vehículo" codigo="id_quien_lleva"></conductores>
        <campo row="col-sm-12" label="Descripción de la solicitud de servicio*" tipo="textarea" codigo="id_descripcion" requerido="true"></campo>-->
      </div>
      <div class="col-sm-8" v-else>
        <div class="row">
          <div class="col-sm-3">
          </div>
          <div class="col-sm-6">
            <div class="card">
              <span style="font-size:100px" class="text-danger text-center"><i class="fa fa-times-circle"></i></span>
              <div class="card-footer text-danger card-shadow">
                No puede asignar un vehículo a solicitudes de distintas fechas
              </div>
            </div>
          </div>
          <div class="col-sm-3">
          </div>
        </div>


      </div>
    </div>


    <div v-show="idVehiculo > 0" class="col-sm-8 slide_up_anim" style="position: absolute; margin-left:500px;margin-top:-77px">
      <div class="card bg-light">

      </div>
    </div>


    <!--<combo-change row="col-sm-12" label="adfasdf" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true"></combo-change>
    <combo row="col-sm-12" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" requerido="true"></combo>
    <campo  row="col-sm-12" label="Características" codigo="txt_caracter_" tipo="text" requerido="true"></campo>
    <campo row="col-sm-12" label="Descripción de la solicitud de servicio*" tipo="textarea" codigo="id_descripcion" requerido="true"></campo>-->
    <!--<privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>
    <viaticodetalle v-if="option == 1":id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" v-on:sendviatico="getViaticoDetalle"></viaticodetalle>
    <viaticoempleados v-else-if="option == 2" :key="keyReload" :id_viatico="id_viatico" :privilegio="privilegio" :estado_nombramiento="estado" :evento="evento" :viatico="viatico"></viaticoempleados>-->
  </div>
  <div class="modal-body" v-if="tipo == 3">
    AUTORIZAR
    <transportelist :solicitud_id="codigos" :evento="evento" :tipo="tipo"></transportelist>
    <br>
    <div class="row">

      <div class="col-sm-12 text-right">
        <button class="btn btn-sm btn-info" @click="updateEstadoSolicitud(2,'Solicitud autorizada',1)"><i class="fa fa-check-circle"></i> Autorizar solicitudes</button>
        <button class="btn btn-sm btn-danger" @click="updateEstadoSolicitud(4,'Solicitud cancelada',1)"><i class="fa fa-times-circle"></i> Cancelar solicitudes</button>
      </div>
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
