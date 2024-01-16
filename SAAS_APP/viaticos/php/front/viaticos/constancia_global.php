<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $bodega;
    $id_viatico = $_POST['id_viatico'];
    $id_persona = $_POST['id_persona'];
    $id_renglon=$_POST['id_renglon'];

?>
<script src="viaticos/js/cargar_vue.js"></script>
<script src="viaticos/js/funciones.js"></script>
<script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
<script src="assets/js/plugins/vue/vue.js"></script>
<span class="btn-regresar" style="float:left;" onclick="get_viatico_detalle_encabezado('empleados_por_viatico')"></span> Regresar

    <input id="id_persona" hidden  value="<?php echo $id_persona;?>"></input>
    <br><br>
    <h3>Empleados seleccionados</h3>
    <div class="row" id="myapp" style="margin-top:5px;">
      <div class="col-sm-5">
        <ul>
          <li v-for="e in empleados_pro">
            {{ e.empleado }}
          </li>
        </ul>

      </div>
      <div class="col-sm-7">
        <div class="row">
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
          <div class="col-sm-4">
            <small class="text-muted p-t-30 db">Duración</small>
            <h5>{{viaticos.duracion}}</h5>
          </div>
        </div>

      </div>


      <input id="id_renglon" hidden value="<?php echo $id_renglon?>"></input>

      <div class="col-sm-12" style="border-top:1px solid #e9ecf0">
        <div v-if="viaticos.status == 938 || viaticos.status == 7959">

        <!-- inicio-->


          <form class="js-validation-constancia form-horizontal push-10-t push-10" action="" method="" id="s_form">
            <div class="row">
          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #e9ecf0;">

            <div class="card-body">
              <div class="row">
                <span class="numberr">1</span><strong class=""> Salida SAAS</strong><br><br>
                <!-- inicio-->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_fecha_salida_c">Fecha*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="date" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_salida_saas" name="id_fecha_salida_saas" placeholder="@Fecha" required autocomplete="off" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_salida_c">Hora*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="js-select2 form-control form-control-sm" id="id_hora_salida_saas" name="combo1">
                              <option v-for="hora in horas" v-bind:value="hora.id_hora">{{ hora.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
              </div>

            </div>
          </div>

          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #e9ecf0;">
            <div class="card-body">
              <div class="row">
                <span class="numberr">2</span><strong class=""> Llegada al lugar</strong><br><br>
                <!-- inicio-->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_fecha_llegada_lugar_c">Fecha*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="date" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_llegada_lugar" name="id_fecha_llegada_lugar" placeholder="@Fecha" required autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_salida_c">Hora*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="js-select2 form-control form-control-sm" id="id_hora_llegada_lugar" name="combo2">
                              <option v-for="hora in horas" v-bind:value="hora.id_hora">{{ hora.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
              </div>

            </div>
          </div>
          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #e9ecf0;">
            <div class="card-body">
              <div class="row">
                <span class="numberr">3</span><strong class=""> Salida del lugar</strong><br><br>
                <!-- inicio-->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_fecha_salida_lugar_c">Fecha*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="date" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_salida_lugar" name="id_fecha_salida_lugar" placeholder="@Fecha" required autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_salida_c">Hora*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="js-select2 form-control form-control-sm" id="id_hora_salida_lugar" name="combo3">
                              <option v-for="hora in horas" v-bind:value="hora.id_hora">{{ hora.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
              </div>

            </div>
          </div>
          <div class="col-sm-3  shadow-none">
            <div class="card-body">
              <div class="row">
                <span class="numberr">4</span><strong class=""> Llegada a SAAS</strong><br><br>
                <!-- inicio-->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_fecha_regreso_c">Fecha*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="date" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_regreso_saas" name="id_fecha_regreso_saas" placeholder="@Fecha" required autocomplete="off"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Hora*</label>
                        <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm form-control_ chosen-select-width" id="id_hora_regreso_saas" name="combo4">
                              <option v-for="hora in horas" v-bind:value="hora.id_hora">{{ hora.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- fin -->
              </div>
              </div>


            </div>

          </div>
          <div class="">


          </div>
          <div >
            <span id="id_country_" hidden >{{viaticos.id_pais}}</span>
            <div  v-if="viaticos.id_pais!='GT'">
              <div class="row">
                <!-- inicio -->
                <div class="col-sm-6 ">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Tipo de salida*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_tipo_salida" onchange="mostrar_numero_de_vuelo('id_tipo_salida')"name="combo5">
                            <option v-for="t in transportes" v-bind:value="t.id_hora">{{ t.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->

                <!-- inicio -->
                <div class="col-sm-6 ">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Tipo de entrada*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" onchange="mostrar_numero_de_vuelo('id_tipo_entrada')" id="id_tipo_entrada" name="combo6">
                            <option v-for="t in transportes" v-bind:value="t.id_hora">{{ t.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-6 ">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Empresa salida*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_empresa_salida" placeholder="Seleccionar" name="combo7">
                            <option v-for="e in empresas" v-bind:value="e.id_hora">{{ e.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-6 ">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Empresa entrada*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_empresa_entrada" name="combo8">
                            <option v-for="e in empresas" v-bind:value="e.id_hora">{{ e.hora_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-6 " id="num_vuelo1" style="display:none">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Número de vuelo*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="number" min='1' id="id_num_vuelo_salida" class="form-control input-sm" required></input>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-6 " id="num_vuelo2" style="display:none">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_hora_regreso_c">Número de vuelo*</label>
                        <div class=" input-group  has-personalizado" >
                          <input type="number" min='1' id="id_num_vuelo_entrada" class="form-control input-sm" required></input>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->

              </div>

            </div>


          </div>



          <!--<div class="col-sm-12">
            <table id="tb_empleados_asistieron" class="table table-sm table-bordered table-striped" width="100%">
              <thead>
                <th class="text-center">Gafete</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Asistió</th>
              </thead>
              <tbody v-for="empleado in empleados">
                <tr>
                  <td class="text-center">{{empleado.id_empleado}}</td>
                  <td class="text-center">{{empleado.nombre}}</td>
                  <td class="text-center"><input class="tgl tgl-flip" :id="empleado.id_empleado" name="cb{{empleado.id_empleado}}" type="checkbox" checked/>
                                        <label class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="empleado.id_empleado" ></label></td>
                </tr>
              </tbody>
            </table>
          </div>-->
          <script>
          $("#sid_hora_salida_saas").select2({
            placeholder: "Seleccionar una dirección",
            allowClear: true
          });
          </script>
          <div class="row text-right">

            <div class="col-sm-12">
              <hr>
              <button id="" class="btn btn-info btn-sm" onclick="generar_constancia()"><i class="fa fa-check"></i> Guardar </button>
              <span id="" class="btn btn-warning btn-sm" onclick="confirmar_ausencia()"><i class="fa fa-times"></i> No asistió </span>
            </div>

          </div>
          <!-- fin-->

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
