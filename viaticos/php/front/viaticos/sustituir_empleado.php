<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $bodega;
    //if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_POST['id_viatico'];
      $id_persona = $_POST['id_persona'];
      $id_renglon = $_POST['id_renglon'];

$clase= new viaticos();
$horas = $clase->get_items(37);

$empleado = $clase->get_empleado_datos_por_nombramiento($id_viatico,$id_persona);
?>
<script src="viaticos/js/cargar_vue.js"></script>
<script src="viaticos/js/funciones.js"></script>
<script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
<script src="assets/js/plugins/vue/vue.js"></script>
<span class="btn-regresar" style="float:left;" onclick="get_viatico_detalle_encabezado('empleados_por_viatico')"></span> Regresar
    <!--<input type='button' @click='allEmpleados()' class="btn btn-sm btn-secondary" value='Validar constancia'>-->


    <!--{{allEmpleados()}}-->


    <!--<h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>-->
    <input id="id_persona" hidden value="<?php echo $id_persona;?>"></input>

    <div class="row" id="myapp" style="margin-top:5px;">
      <!--<input :value="empleado.empleado"></input>-->
      <input id="id_renglon" hidden value="<?php echo $id_renglon?>"></input>


        <div class="col-sm-12">
          <h3>Empleado actual: <strong>{{empleado.empleado}}</strong></h3>
        </div>
        <div class="col-sm-12" style="padding-bottom:-15px">
          <div class="js-validation-sustituye " action="" method="" id="s_form">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_empleado_sustituye">Sustituir por*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control_ chosen-select-width" id="id_empleado_sustituye">
                      <option v-for="emp in empleados" v-bind:value="emp.id_persona">{{ emp.empleado }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group text-right form-material">
              <button id="" class="btn btn-info btn-sm btn-block" onclick="sustituir_empleado()"><i class="fa fa-check"></i> Sustituir </button>
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
