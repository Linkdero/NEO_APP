<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $id_empleado=null;
    $bodega;
    //if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_POST['id_viatico'];

$clase= new viaticos();
$horas = $clase->get_items(37);
?>

    <!--{{allEmpleados()}}-->
    <script src="viaticos/js/funciones.js"></script>
    <script src="viaticos/js/source_modal.js"></script>
    <script src="viaticos/js/cargar_vue.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>


    <!--<h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>-->
    <div class="" id="myapp">
  <div class="panel-group">
    <div class="panel panel-primary">
     <div class="panel-heading">Agregar los destinos</div>
        <div class="panel-body">
          <!--<span class="btn btn-sm btn-soft-info" @click="addRow"><i class="fa fa-plus pull-right"></i> Agregar destino</span><br><br>-->
          <form>
            <button type='button'class="btn btn-sm btn-soft-info" @click="addNewRow">
              <i class="fas fa-plus-circle"></i> Agregar Destino
            </button><br><br>

            <table class="table table-bordered table-sm">
            <thead class="text text-success">
                <tr>
                    <th class="text-center">Departamento</th>
                    <th class="text-center">Municipio</th>
                    <th class="text-center">Aldea</th>
                    <th class="text-center">Llegada lugar</th>
                    <th class="text-center">hora</th>
                    <th class="text-center">Salida Lugar</th>
                    <th class="text-center">hora</th>
                    <th class="text-center">Acción</th>
                </tr>
                </thead>
                <tbody>
                  <tr v-for='(d, index) in destinos' v-if="index <= 2" :key="index">
                    <td width="200">
                      <select class="form-control form-control-sm" v-model="d.departamento" v-on:change="get_municipios($event,index)" required>
                        <option v-for="dep in departamentos"v-bind:value="dep.dep_id" >{{ dep.dep_string }}</option>
                      </select>
                    </td>
                    <td width="200">
                      <select class="form-control form-control-sm" v-model="d.municipio">
                        <option v-if="index==0" v-for="muni in munis1"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                        <option v-if="index==1" v-for="muni in munis2"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                        <option v-if="index==2" v-for="muni in munis3"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                      </select>

                    </td>
                    <td width="200">
                      <select class="form-control form-sm input-sm"  v-model="d.aldea">
                      </select>
                    </td>
                    <td class="text-center" width="150">
                      <span class="f_fecha_d" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi" v-model="d.f_ini"></span>
                    </td>
                    <td class="text-center" width="150">
                      <span class="horas_d" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi" v-model="d.h_ini"></span>
                    </td>
                    <td class="text-center" width="150">
                      <span class="f_fecha_d" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff" v-model="d.f_fin"></span>
                    </td>
                    <td class="text-center" width="150">
                      <span class="horas_d" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff" v-model="d.h_fin"></span>
                    </td>
                    <td scope="row" class="trashIconContainer text-center">
                      <span class="btn btn-sm btn-personalizado outline"><i class="far fa-trash-alt" @click="deleteRow(index, d)"></i></span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <button class="btn btn-sm btn-info"><i class="fa fa-check"></i> Guardar</button>
            </div>
        </form>
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
