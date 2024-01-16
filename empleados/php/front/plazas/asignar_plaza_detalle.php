<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    $id_plaza=$_POST['id_plaza'];
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/source_modal.js"></script>
      <script src="empleados/js/listados_vue.js"></script>
      <script src="empleados/js/emp_vue.js"></script>
      <script>

      </script>
    </head>
    <body>
      <div class="row">
        <div class="col-sm-12" >
          <div class="">
            <form class="js-validation-asignar-plaza">
              <div class="row">
                <!-- inicio -->
                <div class="col-sm-12"  >
                  <div class="form-group" id="listados_app">
                    <div class="">
                      <div class="">
                        <label for="id_persona">Empleado*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="js-select2 form-control form-control-sm chosen-select-width" id="id_persona" name="combo1">
                            <option v-for="e in empleados" v-bind:value="e.id_persona" >{{e.id_persona}} - {{ e.empleado}}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin-->

                  <div class="row" id="em_app">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="id_secretaria_f">Secretaría*</label>
                            <div class=" input-group  has-personalizado" >
                                <select class="js-select2 form-control form-control-sm" id="id_secretaria_f" name="combo1">
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
                                <select class="js-select2 form-control form-control-sm" id="id_subsecretaria_f" name="combo2" v-on:change="get_direccion_f()">
                                  <option v-for="d in subsecretarias" v-bind:value="d.id_direccion" >{{ d.direccion_string }}</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin-->
                    <!-- inicio -->
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
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
                    <div class="col-sm-6">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="id_puesto_f">Puesto*</label>
                            <div class=" input-group  has-personalizado" >
                                <select class="js-select2 form-control form-control-sm" id="id_puesto_f" name="combo7">
                                  <option v-for="p in puestos" v-bind:value="p.id_item" >{{ p.item_string }}</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <div class="col-sm-6">
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

                </div>
                <!-- fin -->
            </form>


          </div>
        </div>

      </div>
    </body>




  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
