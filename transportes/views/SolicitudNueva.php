<?php
include_once '../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8686)){

    //include_once '../../back/functions.php';

?>

<link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
<script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
<script type="module" src="transportes/src/appTransporteNuevo.js?t=<?php echo time();?>"></script>
<div id="stnuevo" style="-webkit-transform: translateZ(0);">

  <div class="modal-header">
    <h4>Solicitud Nueva</h4>


    <ul class="list-inline ml-auto mb-0">

      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>


    </span>
  </div>
  <div class="modal-body">
    <retornadireccion v-on:enviadireccion="getDireccionFromComponent"></retornadireccion>
    <retornaprivilegios v-on:enviaprivilegios="getPrivilegiosFromComponent"></retornaprivilegios>

    <form class="jsValidacionSolicitudTransporteNueva form-material">
      <div class="" style="border-radius:4px">
        <!-- inicio -->
        <div class="row">
          <!--inicio-->
          <div class="col-sm-12" v-if="privilegios.encargado_transporte == true || privilegios.jefe_transporte == true">
            <span class="numberr">{{ numbers.uno }}</span> <strong> Datos del solicitante </strong>
            <hr>
            <div class="row">
              <direcciones tipo="1" :evento="evento" columna="col-sm-6" filtro="2" codigo="id_direccion"></direcciones>
              <!--<departamentos tipo="1" :evento="evento" :columna="columna" codigo="id_departamento"></departamentos>-->
              <empleados verificacion="0" :evento="evento" columna="col-sm-6" codigo="id_empleado"></empleados>
            </div>
          </div>

          <div class="col-sm-12">

            <span class="numberr" v-if="privilegios.encargado_transporte == true || privilegios.jefe_transporte == true">
              {{ numbers.dos }}
            </span>
            <span class="numberr" v-else>
              {{ numbers.uno }}
            </span>
            <strong> Datos de la solicitud </strong>
            <hr>
          </div>

          <campo :row="columna" label="Fecha de salida: *" requerido="true" codigo="fecha_salida" tipo="datetime-local"></campo>
          <!--<campo row="col-sm-2" label="Hora de salida: *" requerido="true" codigo="hora_salida" tipo="time"></campo>-->
          <campo :row="columna" label="Duración: *" requerido="true" codigo="duracion" tipo="number"></campo>
          <combo :row="columna" label="Tipo:*" codigo="id_tipo_seleccion" :arreglo="arrayT" tipo="3" requerido="true"></combo>
          <campo :row="columna" label="Personas: *" requerido="true" codigo="cant_personas" tipo="number"></campo>
          <combo :row="columna" label="Motivo: *" requerido="true" codigo="motivo_solicitud" :arreglo="motivos" tipo="3"></combo>
          <campo :row="columna" label="Fecha de retorno: *" requerido="true" codigo="fecha_regreso" tipo="datetime-local"></campo>
          <div class="col-sm-6">
            <div class="row">
              <empleadosdireccion :arreglo="direccion" verificacion="0" :evento="evento" columna="col-sm-12" etiqueta="Seleccionar responsable" requerido="true"></empleadosdireccion>
            </div>
          </div>

          <campo row="col-sm-12" label="Observaciones: *" requerido="true" codigo="id_observaciones" tipo="textarea"></campo>

          <div class="col-sm-12">
            <hr>
            <span class="numberr" v-if="privilegios.encargado_transporte == true || privilegios.jefe_transporte == true">
              {{ numbers.tres }}
            </span>
            <span class="numberr" v-else>
              {{ numbers.dos }}
            </span>
            <strong> Información de los destinos </strong>
            <hr>
          </div>
          <lugar-seleccion row1="col-sm-9" row2="col-sm-4" tipo="0"></lugar-seleccion>
          <div class="col-sm-3">
            <br>
            <span class="btn btn-sm btn-block btn-info" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Agregar</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <br>
          <label for="">Destinos*</label>

          <table class="table table-sm  table-striped " style="border-radius: 0.3em; border-collapse: collapse;">
            <thead class="" scope="col">
              <th class="text-center" scope="col">Departamento</th>
              <th class="text-center" scope="col">Municipio</th>
              <th class="text-center" scope="col">Lugar.</th>
              <th class="text-center" scope="col">Dirección</th>
              <th class="text-center" width="120px">Acción </th>
            </thead>
            <tbody style=" border-color: 3px solid #ffffff">

              <tr v-for='(d, index) in destinos' :key="index">
                <td class="text-center">{{ d.departamento }}</td>
                <td class="text-center">{{ d.municipio }}</td>
                <td class="text-center">{{ d.lugar }}</td>
                <td width="320px">
                  <div class="form-group" style="margin-bottom:0rem">
                    <div class="">
                      <div class="">
                        <textarea :name="'d'+index" :id="'d'+index" class="form-control input-sm" v-model="d.direccion" rows="3" autocomplete="off" required></textarea>
                      </div>
                    </div>
                  </div>
                </td>

                <td scope="row" class="trashIconContainer text-center" >
                  <span class="btn btn-sm btn-danger" @click="deleteRow(index, d)"><i class="far fa-trash-alt"></i></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-sm-12">
          <!--inicio tabla-->
          <button class="btn btn-sm btn-block btn-info" @click="saveSolicitud()"><i class="fa fa-sync"></i> Guardar</button>
          <!-- fin -->
        </div>

        <!-- fin -->
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
