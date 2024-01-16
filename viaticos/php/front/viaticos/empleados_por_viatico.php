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

    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

?>

    <!--{{allEmpleados()}}-->
    <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    <script src="viaticos/js/funciones_.js"></script>
    <script src="viaticos/js/source_modal.js"></script>
    <script src="viaticos/js/cargar_vue.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>


    <!--<h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>-->
    <div class="row">
      <div class="col-sm-12 shadow-none">




            <table id="tb_empleados_por_nombramiento" class="table table-sm table-bordered " width="100%">
              <thead>
                <th class="text-center">...</th>
                <th class="text-center">Cod.</th>
                <th class="text-center">Empleado</th>

                <th class="text-center">VA</th>
                <th class="text-center">VC</th>
                <th class="text-center">VL</th>
                <th class="text-center">% P</th>
                <th class="text-center">% R</th>
                <th class="text-center">Mo. P</th>
                <th class="text-center">Mo. R</th>
                <th class="text-center">Ra</th>
                <th class="text-center">Re</th>
                <th class="text-center">Co</th>
                <th class="text-center">Estado</th>
                <!--<th class="text-center">Cheque</th>-->
                <th class="text-center">Acción</th>
                <th class="text-center">...</th>
              </thead>

            </table>
          </div>
          <div class="col-sm-12" id="myapp">
            <div v-if="viaticos.dias_pendiente>0">
              <!-- inicio -->
              <div v-if="viaticos.personas>0">
                <div v-if="viaticos.status==938 || viaticos.status==7959">
                  <button id="" class="btn btn-info btn-sm" onclick="get_viatico_detalle_encabezado('constancia_global')"><i class="fa fa-check"></i> Actualizar horas </button>
                  <button id="" v-if="viaticos.personas_c==0" class="btn btn-info btn-sm" onclick="au_solicitud(939)"><i class="fa fa-check"></i> Constancia </button>
                </div>
              </div>
              <div v-if="viaticos.status==939">
                <button id="" class="btn btn-info btn-sm" onclick="get_viatico_detalle_encabezado('liquidacion_global')"><i class="fa fa-check"></i> Actualizar montos </button>
                <button id="" v-if="viaticos.personas_l==0" class="btn btn-info btn-sm" onclick="au_solicitud(940)"><i class="fa fa-check"></i> Liquidar </button>
              </div>


                <div v-if="viaticos.status==938 || viaticos.status==7959">
                  <hr>
                  <script>

                  setTimeout('toggleB()',200);

                  function obtener_municipios(){
                    get_municipios();
                  }

                  function obtener_aldeas(){
                    get_aldeas();
                  }

                  </script>


                  <span id="id_country" hidden >{{viaticos.id_pais}}</span>
                  <span id="id_confirma" hidden>{{viaticos.confirma_lugar}}</span>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <div class="">
                          <div class="row">
                            <label for="id_confirma">Confirma lugar*</label>
                            <div class=" input-group  has-personalizado" >

                                <label class="css-input input-sm switch switch-danger"><input id="chk_confirma" @change="validacionMostrarConfirma()" type="checkbox"/><span></span> <span id="lbl_chk">SI</span></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-3" id="formulario_confirma" v-if="mostrarConfirma==1" class="slide_up_anim">
                      <div class="form-group">
                        <div class="">
                          <label for="">Seleccionar tipo de confirmación</label>
                          <select class="form-control form-control-sm" @change="validaciondConfirmacionPlace($event)">
                            <option value="">-- Seleccionar --</option>
                            <option value="1">Sustituir lugar</option>
                            <option value="2">Agregar destinos</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-7" v-if="confirma_place==2">
                      <div class="row" style="margin-left:15px">
                        <div class="col-sm-2">
                          <small class="text-muted">Fecha Salida: </small>
                           <h5>{{viaticos.fecha_ini}}</h5>
                         </div>
                         <div class="col-sm-2">
                           <small class="text-muted p-t-30 db">Hora de Salida</small>
                           <h5>{{viaticos.hora_ini}}</h5>
                         </div>
                         <div class="col-sm-2">
                           <small class="text-muted">Fecha Regreso: </small>
                            <h5>{{viaticos.fecha_fin}}</h5>
                        </div>
                        <div class="col-sm-2">
                          <small class="text-muted p-t-30 db">Hora de Regreso</small>
                          <h5>{{viaticos.hora_fin}}</h5>
                        </div>
                        <div class="col-sm-3">
                          <small class="text-muted p-t-30 db">Duración</small>
                          <h5>{{viaticos.duracion}}</h5>
                        </div>

                      </div>
                    </div>


                  </div>

                </div>


                <div  v-show="confirma_place==1">

                  <script>
                  $(document).ready(function(){
                    setTimeout(() => {
                      get_departamentos(2);
                    }, 400);
                  });

                  </script>
                  <form class="js-validation-confirma-lugar">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="departamento">Departamento</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="form-control form-control-sm" id="departamento" onchange="get_municipios()" required name="combo_dep">
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="municipio">Municipio</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="form-control form-control-sm" id="municipio" onchange="obtener_aldeas()" required name="combo_mun">
                                  </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="aldea">Aldea</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="form-control form-control-sm" id="aldea">
                                  </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button id="" class="btn btn-info btn-sm btn-block" onclick="confirmar_lugar()"><i class="fa fa-check"></i> Guardar nuevo lugar </button>
                  </form>

                </div>
                <div v-show="confirma_place==2">

                  <form class="js-validation-agregar-lugares">
                    <div class="row">
                      <!--<div class="col-sm-12 text-right">
                        <br><br>
                      </div>-->
                      <div class="col-sm-12">
                      <table class="table" id="tb_lugares">
                      <thead class="text text-success">
                          <tr>
                              <th class="text-center">
                                <button type='button'class="btn btn-sm btn-soft-info text-right" @click="addNewRow">
                                  <i class="fas fa-plus-circle"></i>
                                </button>
                              Departamento</th>
                              <th class="text-center">Municipio</th>
                              <th class="text-center">Aldea</th>
                              <th class="text-center">Llegada lugar</th>
                              <th class="text-center">Hora</th>
                              <th class="text-center">Salida Lugar</th>
                              <th class="text-center">Hora</th>
                              <th class="text-center">Acción</th>
                          </tr>
                          </thead>
                          <tbody>
                            <tr v-for='(d, index) in destinos' v-if="index <= viaticos.total_destinos" :key="index">
                              <td width="200">
                                <div class="form-group" style="margin-bottom:0rem">
                                  <div class="">
                                    <div class="">
                                      <select :name="'combo_dep'+index" :id="'combo_dep'+index" class="form-control form-control-sm" v-model="d.departamento" v-on:change="get_municipios($event,index)" required>
                                        <option v-for="dep in departamentos"v-bind:value="dep.dep_id" >{{ dep.dep_string }}</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td width="200">
                                <div class="form-group" style="margin-bottom:0rem">
                                  <div class="">
                                    <div class="">
                                      <select :name="'combo_mun'+index" :id="'combo_mun'+index" class="form-control form-control-sm" v-model="d.municipio" required>
                                        <option v-if="index==0" v-for="muni in munis1"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                                        <option v-if="index==1" v-for="muni in munis2"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                                        <option v-if="index==2" v-for="muni in munis3"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                                        <option v-if="index==3" v-for="muni in munis4"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td width="200">
                                <select :name="'combo_ald'+index" :id="'combo_ald'+index" class="form-control form-sm input-sm"  v-model="d.aldea">
                                </select>
                              </td>
                              <td class="text-center" width="150">
                                <span class="f_fecha_d" :id="'f_ini'+index" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi" v-model="d.f_ini"></span>
                              </td>
                              <td class="text-center" width="150">
                                <span class="horas_d" :id="'h_ini'+index" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi" v-model="d.h_ini"></span>
                              </td>
                              <td class="text-center" width="150">
                                <span class="f_fecha_d" :id="'f_fin'+index" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff" v-model="d.f_fin"></span>
                              </td>
                              <td class="text-center" width="150">
                                <span class="horas_d" :id="'h_fin'+index" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff" v-model="d.h_fin"></span>
                              </td>
                              <td scope="row" class="trashIconContainer text-center">
                                <span class="btn btn-sm btn-personalizado outline" @click="deleteRow(index, d)"><i class="far fa-trash-alt" ></i></span>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-12">
                        <button class="btn btn-sm btn-info btn-block" onclick="agregarLugares()"><i class="fa fa-check"></i> Guardar</button>
                      </div>
                    </div>

                  </form>
                </div>

              <!-- fin -->
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
