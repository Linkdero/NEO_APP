<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    if(usuarioPrivilegiado()->hasPrivilege(175)){
    $id_persona=null;
    //$id_plaza=$_POST['id_plaza'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/source_modal.js"></script>
      <script src="empleados/js/listados_vue.js"></script>
      <script src="empleados/js/emp_vue.js"></script>
      <script src="empleados/js/validaciones.js"></script>
      <script>

      </script>
    </head>
    <body>
      <div class="row">
        <div class="col-sm-12" >
          <div class="">
            <form class="js-validation-crear-asignacion">
              <div class="row">
                <!-- inicio -->
                <!--<script type="text/x-template" id="demo-template">
                  <div>
                    <p>Selected: {{ selected }}</p>
                    <select2 @change="echange"  :options="options" v-model="selected">
                      <option disabled value="0">Select one</option>
                    </select2>
                    <button @click="cambio">Cambio</button>
                  </div>
                </script>
                <script type="text/x-template" id="select2-template">
                  <select>
                    <slot></slot>
                  </select>
                </script>-->
                <div class="col-sm-12" id="listados_app">
                  <div class="form-group">

                    <div class="">
                      <div class="">
                        <label for="id_persona">Plaza*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="js-select2 form-control form-control-sm chosen-select-width" id="id_plaza" name="combo_plaza">
                            <option v-for="p in plazas_disponibles" v-bind:value="p.id_plaza" >{{ p.plaza_string}}</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="timeline animated fadeInUp" id="plaza_detalle" style="z-index:10;position:absolute; margin-top:-16rem;margin-left:0rem; width:100rem; display:none">
                      <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                        <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                          <h3 class=" text-light">Puesto: {{plaza_detalle.cargo}}</h3>
                          <p>
                            <div class="row">
                              <div class="col-sm-8">
                                <div class="col-sm-12">
                                  <h5>1. - {{plaza_detalle.secretaria_n}}</h5>
                          				<h5>2. - {{plaza_detalle.subsecretaria_n}}</h5>
                          				<h5>3. - {{plaza_detalle.direccion_n}}</a></li>
                                  <h5>4. - {{plaza_detalle.subdireccion_n}}</h5>
                                  <h5>5. - {{plaza_detalle.departamento_n}}</h5>
                                  <h5>6. - {{plaza_detalle.seccion_n}}</h5>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="pw_content">
                                  <div class="pw_header">
                                    <h5 class="text-muted">Codigo Plaza: {{plaza_detalle.cod_plaza}}</h5>
                                  </div>
                                  <div class="pw_header">
                                    <h5 class="text-muted">Codigo Puesto: {{plaza_detalle.cod_puesto}}</h5>
                                  </div>

                                  <div class="pw_header">
                                    <span>{{plaza_detalle.sueldo}}</span>
                                    <small class="text-muted">Sueldo + Bonos</small>
                                    <small class="text-success">{{plaza_detalle.estado}}</small>
                                  </div>
                              </div>
                            </div>



                            </div>
                          </p>
                        </div>
                      </div>
                    </div>




                    <div class="card-body slide_up_anim bg-soft-info" id="plaza_detalled" style="z-index:10;position:absolute; margin-top:-20rem;margin-left:30%; width:40rem; display:none" >
                      <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                          <div class="boxs project_widget">
                            <div class="pw_img">

                            </div>
                            <div class="pw_content">
                              <div class="pw_header">
                                <h6>{{plaza_detalle.cargo}}</h6>
                                <small class="text-muted">Codigo: {{plaza_detalle.cod_plaza}}</small>
                              </div>

                              <div class="pw_meta">
                                <span>{{plaza_detalle.sueldo}}</span>
                                <small class="text-muted">Sueldo + Bonos</small>
                                <small class="text-success">{{plaza_detalle.estado}}</small>
                              </div>
                              <hr>

                              <ul class="timeline">
                                <h4>Dependencia Nominal</h4>
                        				<li><a target="_blank">{{plaza_detalle.secretaria_n}}</a></li>
                        				<li><a target="_blank">{{plaza_detalle.subsecretaria_n}}</a></li>
                        				<li><a target="_blank">{{plaza_detalle.direccion_n}}</a></li>
                                <li><a target="_blank">{{plaza_detalle.subdireccion_n}}</a></li>
                                <li><a target="_blank">{{plaza_detalle.departamento_n}}</a></li>
                                <li><a target="_blank">{{plaza_detalle.seccion_n}}</a></li>
                        			</ul>
                              <hr>

                            </div>
                          </div>
                        </div>
                      </div>
                      <!--{{plaza_detalle.cod_plaza}}<br>
                      {{plaza_detalle.secretaria}}<br>
                      {{plaza_detalle.subsecretaria}}<br>
                      {{plaza_detalle.direccion}}<br>
                      {{plaza_detalle.subdireccion}}<br>
                      {{plaza_detalle.departamento}}<br>-->
                    </div>
                  </div>



                  </div>
                </div>
                  <!-- fin-->

                  <div class="row" id="em_app">
                    <input id="id_empleado_asignar" v-bind:value="empleado_id.id_empleado" hidden></input>
                    <!-- inicio -->
                    <div class="col-sm-12">
                      <span class="numberr">1</span><strong class=""> Datos del Acuerdo</strong><br>
                    </div>
                    <!-- fin-->

                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="id_nro_acuerdo">No. Acuerdo*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-pen form-icon__item"></i>
          										</span>
          										<span class="form-icon form-icon--right" >
          											<!--{{ new Date().getFullYear() }}-->
          										</span>
                                <input class="form-control form-control-sm form-icon-input-left" id="id_nro_acuerdo_p" required required autocomplete="off">
                              </input>

                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="id_fecha_acuerdo">Fecha del Acuerdo*</label>
                            <span class="form-icon-wrapper">

                            <input class=" form-control form-control-sm" type="date" id="id_fecha_acuerdo_asignacion_p" name="id_fecha_acuerdo_asignacion_p" required autocomplete="off">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>


                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_fecha_acuerdo">Fecha de Toma de Posesión*</label>


                          <input class="form-control form-control_ form-control-sm" type="date" id="id_fecha_toma_posesion_p" name="id_fecha_toma_posesion_p" required autocomplete="off">
                        </input>

                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- inicio -->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_detalle_remocion">Detalle*</label>
                          <span class="form-icon-wrapper">
                            <span class="form-icon form-icon--left">
                              <i class="fa fa-pen form-icon__item"></i>
                            </span>
                              <textarea class="form-control form-control-sm form-icon-input-left" id="id_detalle_asignacio_p" name="id_detalle_asignacio_p"  required rows="2" required required autocomplete="off"></textarea>

                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="row">
                          <label for="id_es_extension">Datos funcionales no igual al Nominal*</label>
                          <div class=" input-group  has-personalizado" >
                              <label class="css-input switch switch-sm switch-success"><input class="chequeado" id="chk_funcional" onchange="mostrar_formulario_funcional()" type="checkbox"/><span></span></label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- inicio -->
                  <div id="formulario_funcional" style="display:none" class="row slide_up_anim">
                    <div class="col-sm-12">
                      <span class="numberr">2</span><strong class=""> Datos del Funcionales</strong><br>
                    </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_secretaria_f">Secretaría*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_secretaria_f" name="combo1" disabled>
                                    <option v-for="d in secretarias" v-bind:value="d.id_direccion" :selected="d.id_direccion=='4'">{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->

                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_subsecretaria_f">Sub Secretaría*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_subsecretaria_f" name="combo2" @change="get_direccion_f()">
                                    <option v-for="d in subsecretarias" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin-->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_direccion_f">Dirección*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_direccion_f" name="combo3" v-on:change="get_subdireccion_f()">
                                    <option v-for="d in direcciones" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_subdireccion_f">Subdirección*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_subdireccion_f" name="combo4" v-on:change="get_departamento_f()">
                                    <option v-for="d in subdirecciones" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_departamento_f">Departamento*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_departamento_f" name="combo5" v-on:change="get_secciones_f()">
                                    <option v-for="d in departamentos" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_seccion_f">Sección*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_seccion_f" name="combo6">
                                    <option v-for="d in secciones" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_puesto_f">Puesto*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="form-control-sm chosen-select-width" id="id_puesto_f" name="combo7" style="width:100%">
                                    <option v-for="p in puestos" v-bind:value="p.id_item" >{{ p.item_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!-- inicio -->
                      <div class="col-sm-4">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_nivel_f">Nivel*</label>
                              <div class=" input-group  has-personalizado" >
                                  <select class="js-select2 form-control form-control-sm" id="id_nivel_f" name="combo8">
                                    <option v-for="n in niveles" v-bind:value="n.id_direccion" >{{ n.direccion_string }}</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->

                  </div>
                  <!-- fin -->
                  <!-- inicio -->
                  <div class="col-sm-12 text-right">
                    <button class="btn btn-sm btn-info text-right" onclick="asignarEmpleadPlaza()"><i class="fa fa-check"></i> Guardar</button>
                    <button class="btn btn-sm btn-danger" onclick="cargar_puestos_url('plazas_historial_empleado',2)"><i class="fa fa-times"></i> Cancelar</button>
                  </div>
                  <!-- fin -->

                </div>
                <!-- fin -->
            </form>


          </div>
        </div>

      </div>
    </body>

  <?php }else{
    ?><button class="btn btn-sm btn-soft-info" onclick="cargar_puestos_url('plazas_historial_empleado',2)"><i class="fa fa-arrow-left"></i> Regresar</button><?php
    echo ' No tiene privilegios para asignar plazas ';
  }?>
  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
