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
      <div class="col-sm-12 ">
        <div >
          <div class="bg-light shadow-none" style="padding-bottom:5px">
            <hr>
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-4">
                  <small class="text-muted">Empleado: </small>
                   <h5>{{empleado.empleado}}</h5>
                   <small class="text-muted">Monto Asignado: </small>
                    <h5>{{empleado.monto_asignado}}</h5>
                    <small class="text-muted">Porcentaje proyectado: </small>
                     <h5>{{empleado.porcentaje_proyectado}}</h5>
                </div>
                <div class="col-sm-4">
                  <small class="text-muted">Viático Anticipo: </small>
                   <h5>{{empleado.form_anticipo_letras}}</h5>
                   <small class="text-muted">Viático Constancia: </small>
                    <h5>{{empleado.form_constancia_letras}}</h5>
                    <small class="text-muted">Viático Liquidación: </small>
                     <h5>{{empleado.form_liquidacion_letras}}</h5>
                </div>
                <div class="col-sm-4" v-if="viaticos.status==940">

                  <small>Reintegro hospedaje</small>
                  <h5>{{empleado.cant_hospedaje}}</h5>
                  <small>Reintegro alimentación</small>
                  <h5>{{empleado.cant_alimentacion}}</h5>
                  <small>Otros gastos</small>
                  <h5>{{empleado.cant_otros_gastos}}</h5>

                </div>
                <div v-else>
                  <h4>No se ha liquidado</h4>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>
      <div class="col-sm-12" v-if="empleado.fecha_salida_lugar=='01-01-1900'">
        <div v-if="viaticos.status == 938 || viaticos.status == 7959">

        <!-- inicio-->


          <form class="js-validation-constancia form-horizontal push-10-t push-10" action="" method="" id="s_form">
            <div class="row">
          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #F2F1EF;">

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
                          <input type="text" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_salida_saas" name="id_fecha_salida_saas" placeholder="@Fecha" required autocomplete="off" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"/>
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

          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #F2F1EF;">
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
                          <input type="text" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_llegada_lugar" name="id_fecha_llegada_lugar" placeholder="@Fecha" required autocomplete="off" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"/>
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
          <div class="col-sm-3  shadow-none" style="border-right:1.5px dashed #F2F1EF;">
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
                          <input type="text" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_salida_lugar" name="id_fecha_salida_lugar" placeholder="@Fecha" required autocomplete="off" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"/>
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
                          <input type="text" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_regreso_saas" name="id_fecha_regreso_saas" placeholder="@Fecha" required autocomplete="off" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"/>
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
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_tipo_salida" onchange="mostrar_numero_de_vuelo('id_tipo_salida')" name="combo5">
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
                          <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_tipo_entrada" onchange="mostrar_numero_de_vuelo('id_tipo_entrada')" name="combo6">
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
              <button id="" class="btn btn-warning btn-sm" onclick="confirmar_ausencia()"><i class="fa fa-times"></i> No asistió </button>
            </div>

          </div>
          <!-- fin-->

        </form>
        </div>


      </div>
      <div v-else class="col-sm-12" >
        <br><h3><strong>Datos de la constancia</strong></h3>
        <div v-if="empleado.bln_confirma==1"  class="row">
          <div class="col-sm-3 ">
            <div class="col-sm-12 card shadow-none">
              <div class="row">
                <div class="col-sm-5">
                  <p class="calendar">
                    {{empleado.d_salida_saas}}<em>{{empleado.m_salida_saas}} - {{empleado.y_salida_saas}}</em>
                  </p>
                </div>
                <div class="col-sm-7">
                  <br>
                  <h5><strong>Salida SAAS</strong></h5>
                  <small class="text-muted">Hora: </small>
                   <h5>{{empleado.h_salida_saas}}</h5>
                </div>
              </div>



            </div>

          </div>
          <div class="col-sm-3 ">
            <div class="col-sm-12 card shadow-none">
              <div class="row">
                <div class="col-sm-5">
                  <p class="calendar">
                    {{empleado.d_llegada_lugar}}<em>{{empleado.m_llegada_lugar}} - {{empleado.y_salida_saas}}</em>
                  </p>
                </div>
                <div class="col-sm-7">
                  <br>
                  <h5><strong>LLegada lugar</strong></h5>
                  <small class="text-muted">Hora: </small>
                   <h5>{{empleado.h_llegada_lugar}}</h5>
                </div>
              </div>


            </div>

          </div>
          <div class="col-sm-3">
            <div class="col-sm-12 card shadow-none">
              <div class="row">
                <div class="col-sm-5">
                  <p class="calendar">
                    {{empleado.d_salida_lugar}}<em>{{empleado.m_salida_lugar}} - {{empleado.y_salida_saas}}</em>
                  </p>
                </div>
                <div class="col-sm-7">
                  <br>
                  <h5><strong>Salida lugar</strong></h5>
                  <small class="text-muted">Hora: </small>
                   <h5>{{empleado.h_salida_lugar}}</h5>
                </div>
              </div>


            </div>

          </div>
          <div class="col-sm-3">
            <div class="col-sm-12 card shadow-none">
              <div class="row">
                <div class="col-sm-5">
                  <p class="calendar">
                    {{empleado.d_regreso_saas}}<em>{{empleado.m_regreso_saas}} - {{empleado.y_salida_saas}}</em>
                  </p>
                </div>
                <div class="col-sm-7">
                  <br>
                  <h5><strong>Regreso SAAS</strong></h5>
                  <small class="text-muted">Hora: </small>
                   <h5>{{empleado.h_regreso_saas}}</h5>
                </div>
              </div>


            </div>

          </div>
        </div>

        <div v-else>
          No asistió
        </div>

      </div>

      <div class="col-sm-12" v-if="empleado.fecha_salida_lugar!='01-01-1900'">
        <div v-if="viaticos.status==939">
          <br>
          <h3><strong>Viático Liquidación</strong> - Porcentaje real: <strong class="text-success">{{empleado.porcentaje_real}}</strong></h3>
          <hr>
          <form class="js-validation-liquidacion form-horizontal push-10-t push-10" action="" method="" id="s_form">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_reintegro_hospedaje">Reintegro Hospedaje*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_reintegro_hospedaje" name="id_reintegro_hospedaje" placeholder="@reintegro hospedaje" :value="empleado.cant_hospedaje" required autocomplete="off"  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_reintegro_alimentacion">Reintegro alimentación*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_reintegro_alimentacion" name="id_reintegro_alimentacion" placeholder="@reintegro alimentación" :value="empleado.cant_alimentacion" required  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_otros_gastos">Otros Gastos*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_otros_gastos" name="id_otros_gastos" placeholder="@reintegro alimentación" :value="empleado.cant_otros_gastos" required  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-info btn-sm" onclick="generar_liquidacion()"><i class="fa fa-check"></i> Calcular liquidación </button>

              </div>
            </div>
          </form>
          <div>


          </div>
        </div>

      </div>
      <div v-else-if="empleado.porcentaje_real>0">
        show liquidación
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
