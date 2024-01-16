<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;

    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_101");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">

      <script src="empleados/js/funciones.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="empleados/js/emp_nuevo_vue2.js"></script>

      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
      <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

      <script>


      </script>
    </head>
    <body>

      <div id="emp_nuevo">
        <div id="cargando"></div>
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Crear empleado </h4>
          <ul class="list-inline ml-auto mb-0">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-personalizado active btn-sm" @click="getOpcion(1)">
                <input type="radio" name="options" id="option1" autocomplete="off" checked > Agregar
              </label>
              <label class="btn btn-personalizado btn-sm"  @click="getOpcion(2)">
                <input type="radio" name="options" id="option4" autocomplete="off"> Catalogo
              </label>
              <label class="btn btn-personalizado btn-sm" data-dismiss="modal">
                <span name="options" id="option3" autocomplete="off"  > Salir
              </label>
            </div>
          </ul>
        </div>

        <div class="modal-body">
          <div v-show="opcion == 1">
            <form class="jsValidationEmpleadoNuevo" id="nuevoEmpleadoForm">
              <div class="row">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-12">
                      <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user-tie"></i>Datos Personales
                    </div>

                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="p_nombre">Primer Nombre*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="p_nombre" name="p_nombre" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="s_nombre">Segundo Nombre</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="s_nombre" name="s_nombre" autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="t_nombre">Tercer Nombre</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="t_nombre" name="t_nombre" autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="p_apellido">Primer Apellido*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="p_apellido" name="p_apellido" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="s_apellido">Segundo Apellido</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="s_apellido" name="s_apellido"  autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="t_apellido">Tercer Apellido</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="far fa-user form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="t_apellido" name="t_apellido"  autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="fecha_nac">Fecha de nacimiento*</label>

                            <input class="form-control form-control-sm" type="date" id="fecha_nac" name="fecha_nac" required autocomplete="off">
                          </input>

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
                            <label for="email">Correo electrónico*</label>
                            <span class="form-icon-wrapper">
                              <span class="form-icon form-icon--left">
                                <i class="far fa-envelope form-icon__item"></i>
                              </span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="email" name="email" required autocomplete="off">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!--inicio -->
                    <combo-items codigo="id_tipo_sangre" label="Tipo de sangre" id_catalogo="15" col="col-sm-4"></combo-items>
                    <!-- fin -->
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="cui">CUI o DPI*</label>
                            <span class="form-icon-wrapper">
                              <span class="form-icon form-icon--left">
                                <i class="fa fa-lock form-icon__item"></i>
                              </span>
                            <input class="form-control form-control-sm form-icon-input-left" type="number" id="cui" name="cui" required autocomplete="off" minlength="13" maxlength="13" oninput="this.value = this.value.toUpperCase()">
                          </input>

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
                            <label for="fecha_vencimiento">Fecha de Vencimiento del DPI*</label>

                            <input class="form-control form-control-sm" type="date" id="fecha_vencimiento" name="fecha_vencimiento" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <lugar-seleccion row1="col-sm-12" row2="col-sm-4"  tipo="0"></lugar-seleccion>
                    <!-- fin -->
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="row">
                    <!-- inicio -->
                    <div class="col-sm-12">
                      <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user-tie"></i>Datos Laborales
                    </div>
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="nit">NIT*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="nit" name="nit" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <!--<div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="nisp">NISP</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="nisp" name="nisp"  autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>-->
                    <!-- fin -->
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="igss">IGSS</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <input class="form-control form-control-sm form-icon-input-left" type="text" id="igss" name="igss"  autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->

                    <!-- procedencias -->
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="procedencia">Procedencia*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="procedencia" name="procedencia" required autocomplete="off">
                              <option v-for="p in procedencia" v-bind:value="p.id_item" >{{ p.item_string }}</option>
                            </select>

                          </input>

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
                            <label for="estado_civil">Estado Civil*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="estado_civil" name="estado_civil" required  autocomplete="off">
                              <option v-for="e in estadoCivil" v-bind:value="e.id_item" >{{ e.item_string }}</option>
                            </select>
                          </input>

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
                            <label for="genero">Género*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="genero" name="genero" required autocomplete="off">
                              <option v-for="g in genero" v-bind:value="g.id_item" >{{ g.item_string }}</option>
                            </select>
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- Servicios -->
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="tipo_servicio">Tipo de Servicio*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="tipo_servicio" name="tipo_servicio" required autocomplete="off">
                              <option v-for="t in tipoServicio" v-bind:value="t.id_item" >{{ t.item_string }}</option>
                            </select>

                          </input>

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
                            <label for="religion">Religión*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="religion" name="religion" required  autocomplete="off">
                              <option v-for="r in religion" v-bind:value="r.id_item" >{{ r.item_string }}</option>
                            </select>
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <div class="col-sm-8">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="profesion">Profesión*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="profesion" name="profesion" required autocomplete="off">
                              <option v-for="p in profesiones" v-bind:value="p.id_item" >{{ p.item_string }}</option>
                            </select>
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- Curso -->
                    <!-- inicio -->
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="tipo_curso">Tipo de Curso*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="tipo_curso" name="tipo_curso" autocomplete="off">
                              <option v-for="t in tipoCurso" v-bind:value="t.id_item" >{{ t.item_string }}</option>
                            </select>

                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->
                    <!-- inicio -->
                    <div class="col-sm-4" id="appVue">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="promocion">Promoción*</label>
                            <span class="form-icon-wrapper">
          										<span class="form-icon form-icon--left">
          											<i class="fa fa-lock form-icon__item"></i>
          										</span>
                            <select class="form-control form-control-sm form-icon-input-left" type="text" id="promocion" name="promocion" autocomplete="off">
                              <option v-for="p in promociones" v-bind:value="p.id_item" >{{ p.item_string }}</option>
                            </select>
                          </input>

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
                            <label for="fecha_cur">Fecha del curso*</label>
                            <input class="form-control form-control-sm" type="date" id="fecha_cur" name="fecha_cur" autocomplete="off">
                          </input>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->

                    <!-- fin -->
                  </div>
                </div>
                <!-- inicio -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="observaciones">Observaciones*</label>
                        <span class="form-icon-wrapper">
                          <span class="form-icon form-icon--left">
                            <i class="fa fa-lock form-icon__item"></i>
                          </span>
                        <textarea class="form-control form-control-sm form-icon-input-left" rows="3" id="observaciones" name="observaciones" required autocomplete="off">
                        </textarea>
                      </input>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->


                <!--inicio --->
                <div class="col-sm-12">
                  <button class="btn btn-sm btn-info btn-block salida" @click="crearEmpleado()"><i class="fa fa-plus-circle"></i> Guardar</button>
                </div>
                <!-- fin -->
              </div>
            </form>
          </div>
          <div v-show="opcion == 2">
            <form class="jsValidationCatalogoNuevo" id="nuevoCatalogo">
              <div class="row">
                <!-- inicio -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_catalogo">Catalogo*</label>
                        <span class="form-icon-wrapper">
                          <span class="form-icon form-icon--left">
                            <i class="fa fa-lock form-icon__item"></i>
                          </span>
                        <select class="form-control form-control-sm form-icon-input-left" type="text" id="id_catalogo" name="id_catalogo" autocomplete="off" required>
                          <option v-for="c in catalogo" v-bind:value="c.id_item" >{{ c.item_string }}</option>
                        </select>
                      </input>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="item_name">Nombre del item nuevo *</label>
                        <span class="form-icon-wrapper">
                          <span class="form-icon form-icon--left">
                            <i class="far fa-user form-icon__item"></i>
                          </span>
                        <input class="form-control form-control-sm form-icon-input-left" type="text" id="item_name" name="item_name" autocomplete="off" required oninput="this.value = this.value.toUpperCase()">
                      </input>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="" class="text-white">*</label>
                        <button class="btn btn-sm btn-info btn-block" @click="crearItem()"><i class="fa fa-plus-circle"></i> Guardar</button>
                      </input>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
              </div>
            </div>
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
