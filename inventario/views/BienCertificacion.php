<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){


    $bien_id=null;
    $codigo = null;

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


<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="inventario/src/appInventarioCertificacion.js?t=<?php echo time();?>"></script>
<div id="biencertificacion" style="-webkit-transform: translateZ(0);">
  <!--<div class="text-center" style="position: absolute; background-color: rgba(255, 255, 255, 0.7); min-width:100%; min-height:100%; z-index:5; border-radius:3px" v-if="loading == false">
    <div class="loaderr" style="margin-top:150px"></div>
  </div>-->
  <bienheader tipo="2"></bienheader>
  <div class="modal-body">
    <privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <!--<estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>-->
    <form class="jsValidacionGenerarCertificacion" id="formValidacionGenerarCertificacion">
      <div class="row">
        <direcciones tipo="1" :evento="evento" columna="col-sm-4" filtro="2" codigo="id_direccion"></direcciones>
        <departamentos tipo="1" :evento="evento" columna="col-sm-4" codigo="id_departamento"></departamentos>
        <empleados verificacion="0" :evento="evento" columna="col-sm-4" codigo="id_empleado"></empleados>
        <campo label="Fecha de solicitud:*" row="col-sm-4" codigo="fecha_solicitud" tipo="date" requerido="true"></campo>
        <campo label="Fecha de emisión:*" row="col-sm-4" codigo="fecha_certificacion" tipo="date" requerido="true"></campo>
        <checkbox label="Baja cuantía:*" row="col-sm-4" codigo="chck_baja_cuantía" tipo="date" requerido="true"  :valor="valorc"></checkbox>
        <bienesseleccionados id="filter_bienes" label="Seleccionar Bienes" :evento="evento"></bienesseleccionados>
        <div class="col-sm-12 text-right">
          <button class="btn btn-sm btn-info" @click="generarCertificacion"><i class="fa fa-check-circle"></i> Generar certificaciones</button>
        </div>
      </div>

    </form>

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
