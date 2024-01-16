
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){
    //$opcion = $_GET['opcion'];
    $arreglo = $_GET['arreglo'];
    $estado_actual = str_replace('"','',json_encode($_GET['estado'][0]));
    $privilegio = json_encode($_GET['privilegio']);
    $type = $_GET['type'];



    //echo $privilegio;
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <script src="documentos/js/orden/viewModelOrdenEnviar.js" ></script>
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="appOrdenDetalle">


  <div class="modal-header">
    <h3>Seguimiento de los Registros</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" >
    <input id="arreglo" name="arreglo" value='<?php echo $arreglo;?> ' hidden></input>
    <input id="estado" name="estado" value='<?php echo $estado_actual;?> ' hidden></input>
    <input id="privilegio" name="privilegio" value='<?php echo $privilegio;?> ' hidden></input>
    <input id="type" name="type" value='<?php echo $type;?> ' hidden ></input>
    <div class="row">
      <ordenes-seleccionadas :arreglo="arreglo" tipo="1"></ordenes-seleccionadas>
      <!--<empleados-listado columna="col-sm-12" verificacion="9" v-if="(estado == 80 || estado == 210 || estado == 30) && privilegio.presupuesto == 'true'"></empleados-listado>-->
      <empleados-listado columna="col-sm-12" verificacion="9" v-if="(estado == 213 || type == 2) && privilegio.compras_recepcion == 'true'"></empleados-listado>
      <div v-if="type == 1" class="col-sm-12 text-right">
        <span class="btn btn-sm btn-info" @click="setEstado(8,0,'¿Desea enviar los documentos a presupuesto','Si, enviar.','Registros enviados.')" v-if="(estado == 10 || estado == 313) && privilegio.compras_recepcion == 'true'"><i class="fa fa-check-circle"></i> Enviar a Presupuesto</span>

        <span class="btn btn-sm btn-info" @click="setEstado(8,9,'¿Desea revisar los registros','Si, revisar.','Registros en revisión.')" v-if="(estado == 80 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Revisión </span>

        <span class="btn btn-sm btn-info" @click="setEstado(2,10,'¿Desea aprobar los registros','Si, aprobar.','Registros aprobados.')" v-if="(estado == 89 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Aprobar</span>
        <span class="btn btn-sm btn-danger"  @click="setEstado(3,0,'¿Desea rechazar los registros','Si, rechazar.','Registros rechazados.')" v-if="(estado == 89 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Rechazar</span>


        <span class="btn btn-sm btn-info" @click="setEstado(2,13,'¿Desea enviar los registros a compras?','Si, enviar.','Registros enviados.')" v-if="(estado == 210 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Enviar a Compras para liquidación</span>
        <span class="btn btn-sm btn-info" @click="setEstado(3,13,'¿Desea enviar los registros a compras?','Si, enviar.','Registros enviados.')" v-if="(estado == 30 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Enviar a Compras para corrección</span>

        <span class="btn btn-sm btn-info" @click="setEstado(2,11,'¿Desea establecer en liquidación?','Si, establecer.','Registros en trámite de liquidación.')" v-if="(estado == 213 ) && (privilegio.compras_recepcion == 'true' || privilegio.compras_tecnico == 'true')"><i class="fa fa-check-circle"></i> Trámite de  liquidación</span>

        <span class="btn btn-sm btn-info" @click="setEstado(2,8,'¿Desea enviar los documentos a presupuesto','Si, enviar.','Registros enviados.')" v-if="(estado == 212) && privilegio.compras_recepcion == 'true'"><i class="fa fa-check-circle"></i> Enviar a Presupuesto</span>

        <span class="btn btn-sm btn-info" @click="setEstado(2,14,'¿Desea enviar los registros al Subdirector?','Si, enviar.','Registros enviados.')" v-if="(estado == 28 ) && privilegio.presupuesto == 'true'"><i class="fa fa-check-circle"></i> Enviar a Subdirector</span>

        <span class="btn btn-sm btn-info" @click="setEstado(2,15,'¿Desea enviar los registros al Director?','Si, enviar.','Registros enviados.')" v-if="(estado == 214 ) && privilegio.subdirectorfinanciero == 'true'"><i class="fa fa-check-circle"></i> Enviar a Director</span>
        <span class="btn btn-sm btn-info" @click="setEstado(2,5,'¿Desea aprobar el pago?','Si, aprobar.','Registros aprobados.')" v-if="(estado == 215 ) && privilegio.directorf_au == 'true'"><i class="fa fa-check-circle"></i> Aprobar </span>
        <span class="btn btn-sm btn-info" @click="setEstado(2,16,'¿Desea enviar a presupuesto?','Si, enviar.','Registros enviados.')" v-if="(estado == 25 ) && privilegio.directorf_au == 'true'"><i class="fa fa-check-circle"></i> Enviar a Presupuesto</span>


      </div>
      <div v-else-if="(estado == 313 || estado == 213) && type == 2 && privilegio.compras_recepcion == 'true'" class="col-sm-12 text-right">

        <span class="btn btn-sm btn-info" @click="setEstado(99999,0,'¿Desea asignar estos registros al técnico?','Si, asignar.','Registros asignados.')"><i class="fa fa-check-circle"></i> Asignar</span>
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
