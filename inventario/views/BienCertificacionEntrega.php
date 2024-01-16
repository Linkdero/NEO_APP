<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8687)){


    $certificacion_id=null;
    $direccion_id=null;

    if ( !empty($_GET['certificacion_id'])) {
      $certificacion_id = $_REQUEST['certificacion_id'];
    }

    if ( !empty($_GET['direccion_id'])) {
      $direccion_id = $_REQUEST['direccion_id'];
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


<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="inventario/src/appInventarioCertificacionEntrega.js?t=<?php echo time();?>"></script>
<div id="biencertificacionentrega" style="-webkit-transform: translateZ(0);">
  <bienheader tipo="3"></bienheader>
  <div class="modal-body">
    <privilegios v-on:sendprivilegio="getPrivilegios"></privilegios>
    <!--<estadonom v-on:sendestado="getEstadoV" :id_viatico="id_viatico"></estadonom>-->
    <form class="jsValidacionEntregarCertificacion" id="formValidacionEntregarCertificacion">
      <input id="certificacion_id" name="certificacion_id" value="<?php echo $certificacion_id ?>" hidden></input>
      <input id="direccion_id" name="direccion_id" value="<?php echo $direccion_id ?>" hidden></input>
      <div class="row">
        <!--<direcciones tipo="1" :evento="evento" columna="col-sm-6" filtro="2" codigo="id_direccion"></direcciones>
        <departamentos tipo="1" :evento="evento" columna="col-sm-6" codigo="id_departamento"></departamentos>-->
        <!--<certificacioneslist :evento="evento" :certificacion_id="certificacion_id"></certificacioneslist>-->
        <empleados verificacion="0" :evento="evento" columna="col-sm-12" codigo="id_empleado"></empleados>
        <campo label="Fecha de entrega:*" row="col-sm-12" codigo="fecha_entrega" tipo="date" requerido="true"></campo>
        <div class="col-sm-12 text-right">
          <button class="btn btn-sm btn-info" @click="generarCertificacion"><i class="fa fa-check-circle"></i> Entregar certificaciones</button>
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
