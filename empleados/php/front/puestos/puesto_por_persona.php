<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)){//1163 módulo recursos humanos
    $id_persona=null;
    $id_tipo_filto = null;
    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }
    if ( !empty($_GET['tipo'])) {
      $id_tipo_filtro = $_REQUEST['tipo'];
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
      <script src="assets/js/pages/components.js"></script>
      <script src="empleados/js/components.js"></script>
      <script src="empleados/js/validacionesv.js"></script>
      <script src="empleados/js/empleado_datos_vue_3.6.js"></script>


      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
      <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
      <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
      <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    </head>
    <body>

      <div id="emp_datos_app">
        <div id="cargando"></div>
        <input type="text" id="id_tipo_filtro" hidden value="<?php echo $id_tipo_filtro?>" ></input>
        <input type="text" id="id_cambio" hidden value="0"></input>
        <input type="text" id="id_gafete" hidden value="<?php echo $id_persona?>" ></input>
        <input type="text" id="id_empleado" :value="datos_persona.id_empleado" hidden></input>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Puesto Actual </h5>
          <ul class="list-inline ml-auto mb-0" v-if="datos_persona.id_empleado==0">
            <label class="btn btn-personalizado btn-sm" data-dismiss="modal">
              <span name="options" id="option3" autocomplete="off"  > Salir
            </label>
          </ul>
          <ul class="list-inline ml-auto mb-0" v-else="sLoaded==true">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-personalizado active btn-sm" @click="getOpcion(1)">
                <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
              </label>

              <label class="btn btn-personalizado btn-sm"  @click="getOpcion(2)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off"> Plazas
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off" > Contratos
              </label>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(8)" v-if="dPuesto.estado != 2312">
                <input type="radio" name="options" id="option4" autocomplete="off" > Ubicaciones
              </label>
              <?php if(usuarioPrivilegiado()->hasPrivilege(280)){?>
              <label class="btn btn-personalizado btn-sm" @click="getOpcion(11)">
                <input type="radio" name="options" id="option4" autocomplete="off" > Carnet
              </label>
            <?php }?>
              <label class="btn btn-personalizado btn-sm out">
                <span name="options" id="option6" autocomplete="off"  > Salir
              </label>
            </div>
          </ul>
        </div>

        <div>
          <div class="modal-body bg-light">
            <div class="row " id="" style="z-index:5000">
              <fotografia :id_persona="id_persona" tipo="2"></fotografia>
              <detalle-puesto v-on:event_child="getEmpEstadoChild" ref="comp" :id_persona="id_persona" :apy="dPuesto.estado"></detalle-puesto>
            </div>
          </div>
          <!-- fin-->
        </div>

        <div class="modal-body">
          <div v-if="isLoaded==true">
            <!-- inicio detalle -->
            <div  v-if="datosPersona.tipo_persona==1051">
              <?php if(evaluar_flag($_SESSION['id_persona'],1163,82,'flag_actualizar')==1){?>
                <aspirante-proceso :id_persona="datosPersona.id_persona" :id_empleado="datosPersona.id_empleado" v-on:siguiente-proceso="setEstadoProceso"></aspirante-proceso>
              <?php }else{?>
                <h2>Aspirante en Proceso</h2>


                <?php if(evaluar_flag($_SESSION['id_persona'],1163,175,'flag_actualizar')==1){?>
                  <span v-if="datosPersona.tipo_aspirante == 1" class="btn btn-sm btn-soft-info" @click="getOpcion(5)"><i class="fa fa-plus"></i> Asignar plaza</span>
                <?php }?>
                <?php if(evaluar_flag($_SESSION['id_persona'],1163,167,'flag_actualizar')==1){?>
                  <span v-if="datosPersona.tipo_aspirante == 2 || datosPersona.tipo_aspirante == 3" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                <?php }?>
                {{ datosPersona.tipo_aspirante }}
                <?php
              }?>

              <!-- finalizar denegación -->
            </div>
            <div v-else-if="datosPersona.id_empleado > 0 ||  datosPersona.tipo_persona==1052">
              <!-- inicio -->
              <div id="datos_puesto_">
                <!-- inicio puesto detalle-->
                <div v-if="opcion==1" class="slide_up_anim">
                  <detalle-asignacion-plaza  v-on:event_child="getDetallePuestoChild" :id_persona="id_persona"></detalle-asignacion-plaza>
                </div>
                <!-- fin puesto detalle -->
                <!-- inicio plazas-->
                <div v-if="opcion==2" class="slide_up_anim">
                  <!--<script src="empleados/js/source_modal.js"></script>-->
                  <div class="row">
                    <div class="col-sm-12">
                      <?php if(evaluar_flag($_SESSION['id_persona'],1163,175,'flag_actualizar')==1){?>
                      <span v-if="datos_persona.id_empleado!=0" class="btn btn-sm btn-soft-info" @click="getOpcion(5)"><i class="fa fa-plus"></i> Asignar plaza</span>
                      <span v-else>No tiene un empleado asignado</span>
                    <?php }?>

                      <br>
                    </div>
                    <div class="col-md-12" v-if="plazas.length > 0" >
                      <div class="el-wrapper" v-for="p in plazas">
                        <div class="box-up">
                          <div class="img-info">
                            <div class="info-inner">
                              <div class="row">
                                <div class="col-sm-2 text-left">
                                  <dato-persona icono="far fa-calendar-check" texto="Codigo de la Plaza" :dato="p.cod_plaza" tipo="0"></dato-persona>
                                </div>
                                <div class="col-sm-6 text-left">
                                  <dato-persona icono="far fa-calendar-check" texto="Partida Presupuestaria:" :dato="p.partida" tipo="0"></dato-persona>
                                </div>

                                <div class="col-sm-12 text-right" style="position:absolute;margin-top:15px">
                                  <h2 v-if="p.status==891" class="text-secondary">{{p.estado}}</h2>
                                  <h2 v-else class="text-danger">{{p.estado}}</h2>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="box-down">
                          <div class="h-bg">
                            <div class="h-bg-inner"></div>
                          </div>
                          <a class="cart" href="#">


                            <span class="add-to-cart">
                              <?php if(usuarioPrivilegiado()->hasPrivilege(175)){?>
                                <span class="txt" v-if="p.status==891 || p.status==5610" @click="getOpcionRemocion(p.id_plaza,p.id_asignacion,6)"><i class="fa fa-user-times"></i> Remocion</span>
                                <span class="txt" @click="get_empleado_plaza(p.id_plaza,p.id_asignacion,9)"><i class="fa fa-file-powerpoint"></i> Ver plaza</span>
                              <?php }?>

                            </span>
                          </a>
                          <div class="row" style="margin-top:-5px">
                            <div class="col-sm-1 text-left">
                              <dato-persona icono="far fa-calendar-check" texto="Inicio" :dato="p.inicio" tipo="0"></dato-persona>
                            </div>
                            <div class="col-sm-1 text-left">
                              <dato-persona icono="far fa-calendar-times" texto="Fin" :dato="p.final" tipo="0"></dato-persona>
                            </div>
                            <div class="col-sm-1 text-left">
                              <dato-persona icono="far fa-calendar-check" texto="Sueldo" :dato="p.sueldo" tipo="0"></dato-persona>
                            </div>
                            <div class="col-sm-4 text-left">
                              <dato-persona icono="far fa-calendar-check" texto="Puesto:" :dato="p.puesto" tipo="0"></dato-persona>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--<script src="empleados/js/source_modal.js"></script>-->

                    </div>

                  </div>
                </div>
                <!-- fin plazas-->
                <!-- inicio contratos -->
                <div v-if="opcion==3" class="slide_up_anim">
                  <div class="row">
                    <div class="col-sm-12">
                      <?php if(evaluar_flag($_SESSION['id_persona'],1163,167,'flag_actualizar')==1){?>
                        <div v-if="datos_persona.id_empleado!=0">
                          <div v-if="datos_plaza.tipo==7">
                            <span v-if="datos_plaza.renglon=='011'" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                          </div>
                          <div v-else-if="datos_plaza.tipo==1075">
                            <span v-if="datos_plaza.emp_estado==908 && datos_plaza.renglon=='031' || datos_plaza.emp_estado==908 && datos_plaza.renglon=='029'" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Renovar contrato</span>
                            <span v-else-if="datos_plaza.emp_estado==911 || datos_plaza.emp_estado==909" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                          </div>
                          <div v-else>
                            <span>No tiene un empleado asignado</span>
                          </div>
                        </div>
                      <?php }?>
                    </div>
                    <div class="col-sm-12" v-if="displayedPosts.length > 0" >
                      <table id="tb_contratos_por_persona" class="table table-sm table-striped table-bordered" width="100%">
                        <thead>
                          <!--<th class="text-center">ID</th>-->
                          <th class="text-center">Renglón</th>
                          <th class="text-center">Contrato</th>
                          <th class="text-center">Acuerdo</th>
                          <th class="text-center">Inicio</th>
                          <th class="text-center">Fin</th>
                          <th class="text-center">Finalización</th>
                          <th class="text-center">Sueldo</th>
                          <th class="text-center">Puesto</th>
                          <th class="text-center">Estado</th>
                          <th class="text-center">Acción</th>
                        </thead>
                        <tbody>
                          <tr v-for="c in displayedPosts">
                            <td class="text-center">{{c.renglon}}</td>
                            <td class="text-center">{{c.nro_contrato}}</td>
                            <td class="text-center">{{c.nro_acuerdo_aprobacion}}</td>
                            <td class="text-center">{{c.fecha_acuerdo_aprobacion}}</td>
                            <td class="text-center">{{c.fecha_finalizacion}}</td>
                            <td class="text-center">{{c.fecha_fin}}</td>
                            <td class="text-center">{{c.monto_mensual}}</td>
                            <td class="text-center">{{c.puesto}}</td>
                            <td class="text-center">
                              <span v-if="c.id_status==908" class="badge badge-soft-success">{{c.estado}}</span>
                              <span v-else class="badge badge-soft-danger">{{c.estado}}</span>
                            </td>
                            <td class="text-center">
                              <div class="btn-group">
                                <?php if(usuarioPrivilegiado()->hasPrivilege(85)){?>

                                  <button class="btn-sm btn btn-soft-info" v-if="c.id_status==908" @click="getOpcionFinContrato(c.reng_num,10)"><i class="fa fa-user-times"></i></button>
                                  <button class="btn-sm btn btn-soft-info" @click="getContratoById(c.reng_num,15)"><i class="fa fa-file-powerpoint"></i></button>
                                <?php }?>

                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-sm-12 text-right" v-if="displayedPosts.length > 0" >
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-soft-info" v-if="page != 1" @click="page--"> Anterior </button>
                        <button type="button" class="btn btn-sm btn-soft-info" v-for="pageNumber in pages.slice(page-1, page+5)" @click="page = pageNumber"> {{pageNumber}} </button>
                        <button type="button" @click="page++" v-if="page < pages.length" class="btn btn-sm btn-soft-info"> Siguiente </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin contratos -->
                <div v-if="opcion==15" class="slide_up_anim">
                  <!-- actualizar puesto-->
                  <form-asignacion-contrato v-if="opcion==15" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="id_reng_num" tipo_accion="2"></form-asignacion-contrato>
                </div>
                <!-- fin update datos funcionales -->
                <!-- inicio update datos funcionales -->
                <div v-if="opcion==4" class="slide_up_anim">
                  <!-- actualizar puesto-->
                  <form-asignacion-ubicacion v-if="opcion==4" :id_persona="id_persona" :plazas="plazas" tipo_accion="1"></form-asignacion-ubicacion>
                </div>
                <div v-if="opcion==14" class="slide_up_anim">
                  <!-- actualizar puesto-->
                  <form-asignacion-ubicacion v-if="opcion==14" :id_persona="id_persona" :id_asignacion="id_asignacion_u" :reng_num="id_reng_num_u" :plazas="plazas" tipo_accion="2"></form-asignacion-ubicacion>
                </div>
                <!-- fin update datos funcionales -->

                <!-- inicio remocion  -->
                <div v-if="opcion==6" class="slide_up_anim">
                  <h3>Crear remoción</h3><br>
                  <input id="id_plaza_re" :value="id_plaza" hidden></input>
                  <input id="id_asignacion_re" :value="id_asignacion" hidden></input>
                  <div class="row">
                    <div class="col-sm-12" v-if="datos_plaza.emp_estado==891">
                      <form class="js-validation-tramite-solvencia form-material">
                        <div class="row">
                          <!--inicio-->
                          <campo row="col-sm-4" label="No. del Acuerdo*" codigo="id_nro_acuerdo_re" tipo="text" requerido="true"></campo>
                          <campo row="col-sm-4" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo_re" tipo="date" requerido="true"></campo>
                          <campo row="col-sm-4" label="Fecha Reseción*" codigo="id_fecha_remocion_re" tipo="date" requerido="true"></campo>
                          <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_remocion_re" tipo="textarea" requerido="true"></campo>
                          <div class="col-sm-12 text-right">
                            <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.estado==891" @click="tramiteDeSolvencia()"><i class="fa fa-check"></i> Trámite de solvencia</button>
                            <button class="btn btn-sm btn-danger" @click="getOpcion(2)"><i class="fa fa-times"></i> Cancelar</button>
                          </div>

                        </div>
                      </form>

                    </div>
          					<div class="col-sm-12" v-if="datos_plaza.emp_estado==5610">
                      <form class="js-validation-crear-baja form-material">
                        <br>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="alert alert-soft-danger fade show" role="alert">
              								<i class="fa fa-times-circle alert-icon mr-3"></i>
              								<span>Dar de baja al empleado</span>
                            </div>
            							</div>
                          <!-- inicio -->
                          <combo row="col-sm-6" label="Tipo de baja*" codigo="id_tipo_baja_bj" :arreglo="items" tipo="2" requerido="true"></combo>
                          <campo row="col-sm-6" label="Fecha*" codigo="id_fecha_baja_bj" tipo="date" requerido="true"></campo>
                          <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_baja_bj" tipo="textarea" requerido="true"></campo>
                          <div class="col-sm-12 text-right">
                            <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.emp_estado==5610" @click="crearBaja()"><i class="fa fa-check"></i> Actualizar</button>
                            <button class="btn btn-sm btn-danger" @click="getOpcion(2)"><i class="fa fa-times"></i> Cancelar</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!-- fin remocion-->
                </div>
                <!-- inicio ubicaciones -->

                <div v-if="opcion==8" class="slide_up_anim">
                  <div class="row">
                    <div class="col-sm-12" v-if="datos_plaza.emp_estado==891 || datos_plaza.emp_estado==908">
                      <?php if(usuarioPrivilegiado()->hasPrivilege(74)){ ?><span style="margin-top:0px" class="btn btn-sm btn-soft-info" @click="getOpcion(4)"><i class="fa fa-plus"></i> Asignar Ubicación</span><?php }?>
                        <br><br>
                    </div>
                    <div class="col-md-12" >
                      <!--<script src="empleados/js/source_modal.js"></script>-->
                      <table id="tb_plazas_por_empleado_h" class="table table-sm table-striped table-bordered" width="100%">
                        <thead>
                          <!--<th class="text-center">ID</th>-->
                          <th class="text-center">Plaza</th>
                          <th class="text-center">Secretaría</th>

                          <th class="text-center">Subsecretaría</th>
                          <th class="text-center">Dirección</th>
                          <th class="text-center">Subdirección</th>
                          <th class="text-center">Departamento</th>
                          <th class="text-center">Sección</th>
                          <th class="text-center">Puesto</th>
                          <th class="text-center">Inicio</th>
                          <th class="text-center">Fin</th>
                          <th class="text-center">Acuerdo</th>
                          <th class="text-center">Acción</th>
                        </thead>
                        <tbody>
                          <tr v-for="u in ubicaciones">
                            <td class="text-center"><strong>{{u.cod_plaza}}</strong></td>
                            <td class="text-center"><small>{{u.s}}</small></td>
                            <td class="text-center"><small>{{u.ss}}</small></td>
                            <td class="text-center"><small>{{u.dir}}</small></td>
                            <td class="text-center"><small>{{u.sdir}}</small></td>
                            <td class="text-center"><small>{{u.dep}}</small></td>
                            <td class="text-center"><small>{{u.sec}}</small></td>
                            <td class="text-center"><small>{{u.puesto}}</small></td>
                            <td class="text-center"><small>{{u.fecha_ini}}</small></td>
                            <td class="text-center"><small>{{u.fecha_fin}}</small></td>
                            <td class="text-center"><small>{{u.acuerdo}} | {{ u.reng_num}}</small></td>
                            <td class="text-center">
                              <div class="btn-group" v-if="u.status==1">
                                <span class="btn-soft-info btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,14)"><i class="fa fa-pencil-alt"></i>
                                </span>
                                <span class="btn-soft-info btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,2)"><i class="fa fa-check-circle"></i>
                                </span>
                                <span class="btn-soft-danger btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,3)"><i class="fa fa-trash-alt"></i>
                                </span>
                              </div>
                              <div v-else-if="u.status==2">
                                <span class="text-info"><i class="fa fa-check-circle"></i>
                                </span>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- fin ubicaciones -->
                <!-- inicio detalle plaza -->
                <div v-if="opcion==9">
                  <h3>Detalle de la plaza</h3>
                  <div class="row">
                    <div class="col-sm-12 col-xs-12">
                      <div class="row">
                        <div class="col-sm-6">
                            <div class="">
                              <div class="">
                                <h4>{{emp_plaza.cargo}}</h4>
                                <small class="text-muted">Codigo: {{emp_plaza.cod_plaza}}</small>
                              </div>
                              <br><br>
                              <div class="">
                                <h2>{{emp_plaza.sueldo}}</h2>
                                <small class="text-muted">Sueldo + Bonos</small>
                                <small class="text-success">{{emp_plaza.estado}}</small>
                              </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                          <ul class="timeline">
                            <h4>Dependencia Nominal</h4>
                            <li><a target="_blank">{{emp_plaza.secretaria_n}}</a></li>
                            <li><a target="_blank">{{emp_plaza.subsecretaria_n}}</a></li>
                            <li><a target="_blank">{{emp_plaza.direccion_n}}</a></li>
                            <li><a target="_blank">{{emp_plaza.subdireccion_n}}</a></li>
                            <li><a target="_blank">{{emp_plaza.departamento_n}}</a></li>
                            <li><a target="_blank">{{emp_plaza.seccion_n}}</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 text-right">
                      <span class="btn btn-sm btn-soft-info" @click="getOpcion(2)"><i class="fa fa-arrow-left"></i> Regresar</span>
                    </div>
                  </div>
                  <form-asignacion-plaza v-if="opcion==9" :id_persona="id_persona" :id_gafete="id_persona" :id_empleado="datos_persona.id_empleado" :id_asignacion="id_asignacion_plaza" :tipo_accion="2"></form-asignacion-plaza>
                </div>
                <!-- fin detalle plaza -->
                <!-- inicio finalizacion contrato -->
                <div v-if="opcion==10">
                  <input id="reng_num" :value="reng_num" hidden></input>
                  <h3>Finalizar contrato</h3><br>
                  <form class="js-validation-finalizacion-contrato form-material">
                    <div class="row">
                      <!--inicio-->

                      <campo row="col-sm-3" label="No. Acuerdo*" codigo="id_nro_acuerdo" tipo="text" requerido="true"></campo>
                      <campo row="col-sm-3" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo" tipo="date" requerido="true"></campo>
                      <campo row="col-sm-3" label="Fecha Reseción*" codigo="id_fecha_finalizacion_fc" tipo="date" requerido="true"></campo>
                      <combo row="col-sm-3" label="Tipo de Finalización*" codigo="id_tipo_fc" :arreglo="tipos_baja_contrato" tipo="3" requerido="true"></combo>
                      <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_remocion_fc" tipo="textarea" requerido="true"></campo>

                      <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-info text-right"  @click="finalizarContrato()"><i class="fa fa-check"></i> Finalizar contrato</button>
                        <button class="btn btn-sm btn-danger" @click="getOpcion(3)"><i class="fa fa-times"></i> Cancelar</button>
                      </div>

                    </div>
                  </form>
                </div>
                <!-- fin finalizacion contrato-->
                <!-- inicio carnets -->
                <div v-if="opcion==11">
                  carnets
                  <table id="tb_carnets" class="table table-sm table-striped responsive" width="100%">
                    <thead>
                      <tr>
                        <th class="text-center">id_gafete</th>
                        <th class="text-center">id_empleado</th>
                        <th class="text-center">id_contrato</th>
                        <th class="text-center">id_version</th>
                        <th class="text-center">puesto</th>
                        <th class="text-center">fecha_generado</th>
                        <th class="text-center">fecha_vencimiento</th>
                        <th class="text-center">fecha_validado</th>
                        <th class="text-center">Acción</th>
                      </tr>

                    </thead>
                    <tbody>
                    </tbody>
                  </table>

                </div>
                <!-- fin carnets -->
                <div v-if="opcion==12">
                  Generar Carnet
                  <form class="jsValidationCarnetNuevo">
                    <div class="row">
                      <!--inicio-->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_tipo_carnet">Tipo de Carnet*</label>
                              <span class="form-icon-wrapper">
                                <span class="form-icon form-icon--left">
                                  <i class="fa fa-id-card form-icon__item"></i>
                                </span>

                              <select class=" form-control form-control-sm form-icon-input-left form-control-alternative" id="id_tipo_carnet" name="id_tipo_carnet" required autocomplete="off">
                                <option v-for="t in tipoCarnets" v-bind:value="t.id_item" >{{ t.item_string }}</option>
                              </select>

                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- fin -->
                      <!--inicio-->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="">
                            <div class="">
                              <label for="id_posee_arma">Poseción de Arma*</label>
                              <span class="form-icon-wrapper">
                                <span class="form-icon form-icon--left">
                                  <i class="fa fa-thumbs-down form-icon__item"></i>
                                </span>

                              <select class=" form-control form-control-sm form-icon-input-left form-control-alternative" id="id_posee_arma" name="id_posee_arma" required autocomplete="off">
                                <option value="" >-- Seleccionar --</option>
                                <option value="1" >SI</option>
                                <option value="2" >NO</option>
                              </select>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.estado==891 || datos_plaza.estado==908" @click="generarCarnet()"><i class="fa fa-check"></i> Generar Carnet</button>
                        <span class="btn btn-sm btn-danger" @click="getOpcion(12)"><i class="fa fa-times"></i> Cancelar</span>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <!-- fin -->
            </div>
            <!-- fin detalle -->
          </div>
          <div v-if="isLoaded==false">
            <div class="loaderr"  style =" z-index:55"></div>
            <!-- inicio detalle -->
            <!-- fin detalle -->
          </div>
          <!-- inicio agregar plaza -->
          <div v-if="opcion==5" class="slide_up_anim">
            <form-asignacion-plaza v-if="opcion==5 || tipoEstado == 1" :id_persona="id_persona" :id_gafete="id_persona" :id_empleado="datos_persona.id_empleado" :id_asignacion="0" :tipo_accion="1" ></form-asignacion-plaza>
          </div>
          <!-- fin agregar plaza -->
          <!-- inicio agregar contrato -->
          <div v-if="opcion==7" class="slide_up_anim">
            <form-asignacion-contrato v-if="opcion==7 || tipoEstado == 2" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1"></form-asignacion-contrato>
          </div>
          <!-- fin agregar contrato -->
          <!-- inicio persona apoyo -->
          <div v-if="opcion==16 || tipoEstado == 4" class="slide_up_anim">
            <form-asignacion-apoyo v-if="opcion==16 || tipoEstado == 4" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1"></form-asignacion-apoyo>
          </div>
          <!-- fin persona apoyo -->
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
