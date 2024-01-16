<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $tipo_filtro=null;
    $bodega;
    if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_REQUEST['id_viatico'];
    }
    if(!empty($_GET['tipo_filtro'])){
      $tipo_filtro=$_REQUEST['tipo_filtro'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      /*include_once '../../back/functions.php';
      $clase= new insumo();
*/
      /*$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }*/
    }


?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/viatico_detalle_vue_1.9.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
  <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css"/>
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
  <!--<script src="viaticos/js/source_modal.js"></script>-->
  <script>

  </script>






</head>
<div id="appViaticoDetalle">
  <div class="modal-header">
    <h4 class="modal-title">Detalle del Nombramiento # <?php echo $id_viatico;?></h4>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado active btn-sm" @click="getOpcion(1)">
          <input type="radio" name="options" id="option1" autocomplete="off" checked> Detalle
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(2)">
          <input type="radio" name="options" id="option2" autocomplete="off" > Empleados
        </label>
        <label class="btn btn-personalizado btn-sm" id="actions1Invoker" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(<?php echo $id_viatico?>,3)">
        <span  autocomplete="off"> Imprimir</span>
        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px">
          <div class="card overflow-hidden" style="margin-top:-20px;">
            <div class="card-header d-flex align-items-center py-3">
              <h2 class="h4 card-header-title">Opciones:</h2>
            </div>
            <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
              <div id="menu3<?php echo $id_viatico?>"></div>
            </div>
          </div>
        </div>
        </label>
        <label class="btn btn-personalizado btn-sm salida">
          <span name="options" id="option3" autocomplete="off"  > Salir
        </label>
      </div>


      <!--<li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>-->
    </ul>

  </div>
  <input id="id_cambiodv" :value="cambio" hidden></input>
  <input id="id_viatico" hidden value="<?php echo $id_viatico;?>"></input>
  <input id="id_filtro_detalle"hidden value="<?php echo $tipo_filtro?>"></input>
  <div class="modal-body">

    <div v-show="isLoaded==true">
      <div class="row">
        <!--inicio detalle-->
        <div class="col-sm-12" v-show="opcion==1">
          <!-- inicio -->
          <div class="row">
            <div class="col-sm-6">
              <div class="">

                  <label for="destinatarios">Dirección</label>
                  <div class="input-group has-personalizado" >
                    <strong><i class="fa fa-home"></i> {{viaticos.direccion_solicitante}}</strong>
                  </div>

              </div>
            </div>
            <div class="col-sm-6">
                <div class=" ">
                  <label for="soli_cantidad">Autorizado por:</label>
                  <div class="input-group has-personalizado">
                    <div v-if="estado_nombramiento.status == 932">
                      NO HA SIDO AUTORIZADO
                    </div>
                    <div v-else>
                      <strong><i class="fa fa-user-check"></i> {{viaticos.autorizado_por}}</strong>
                    </div>


                  </div>
                </div>
            </div>
      		<div class="invoice-title">


      			<!--<h2 class="pull-left">Nombramiento</h2><h3 class="pull-right">No # {{viaticos.nombramiento}}</h3>-->
      		</div>
        </div>
      		<hr>

      		<div class="row">






            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-3">
                  <div class="row">
                    <div class="col-sm-6" v-if="viaticos.status==932 || viaticos.status==933">
                      <small class="text-muted">Fecha Salida: </small>
                       <h5 class="f_fecha" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi" >{{viaticos.fecha_ini}}</h5>
                       <small class="text-muted">Fecha Regreso: </small>
                       <h5 class="f_fecha" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff" >{{viaticos.fecha_fin}}</h5>
                    </div>
                    <div class="col-sm-6" v-else>
                      <small class="text-muted">Fecha Salida: </small>
                       <h5>{{viaticos.fecha_ini}}</h5>
                       <small class="text-muted">Fecha Regreso: </small>
                        <h5>{{viaticos.fecha_fin}}</h5>
                    </div>
                    <div class="col-sm-6" v-if="viaticos.status==932 || viaticos.status==933">
                      <small class="text-muted p-t-30 db">Hora de Salida</small>
                      <h5 class="horas_" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi">{{viaticos.hora_ini}}</h5>
                      <small class="text-muted p-t-30 db">Hora de Regreso</small>
                      <h5 class="horas_" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff">{{viaticos.hora_fin}}</h5>
                    </div>
                    <div class="col-sm-6" v-else>
                      <small class="text-muted p-t-30 db">Hora de Salida</small>
                      <h5>{{viaticos.hora_ini}}</h5>
                      <small class="text-muted p-t-30 db">Hora de Regreso</small>
                      <h5>{{viaticos.hora_fin}}</h5>
                    </div>
                    <div class="col-sm-12">
                      <small class="text-muted p-t-30 db">Duración</small>
                      <h5>{{viaticos.duracion}}</h5>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3" style="border-left:1.5px dashed #F2F1EF;">
                  <div class="">
                    <div class="col-sm-12" v-if="viaticos.status==932 || viaticos.status==933">
                      <small class="text-muted">Motivo: </small>
                       <h5 class="motivo_" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento">{{viaticos.motivo}}</h5>
                       <br>
                       <small class="text-muted">Funcionario: </small>
                       <h5>{{viaticos.funcionario}}</h5>
                    </div>
                    <div class="col-sm-12" v-else>
                      <small class="text-muted">Motivo: </small>
                       <h5>{{viaticos.motivo}}</h5>
                       <br>
                       <small class="text-muted">Funcionario: </small>
                       <h5>{{viaticos.funcionario}}</h5>

                    </div>
                  </div>
                </div>
                <div class="col-sm-3" style="border-left: 1.5px dashed #F2F1EF;">
                  <div class="">
                    <div class="col-sm-12">
                      <small class="text-muted">Destino: </small>
                       <h5>{{viaticos.destino}}</h5>

                       <div v-if="viaticos.confirma_lugar=='1'">
                         <br>
                         <small class="text-muted">Destino Nuevo:</small>
                         <h5 class="text-info">{{viaticos.historial}}</h5>
                       </div>

                       <div v-if="viaticos.confirma_lugar=='2'">
                         <br>
                         <small class="text-muted">Recorrido de la Comisión:</small>
                         <h5 class="text-info">{{viaticos.historial}}</h5>
                       </div>

                    </div>


                  </div>
                </div>
                <div class="col-sm-3" style="border-left: 1.5px dashed #F2F1EF;">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-6">
                        <small class="text-muted">Tipo de Comisión: </small>
                        <div v-if="viaticos.id_pais=='GT'">
                          <h5 id="pais_tipo">NACIONAL</h5>
                        </div>
                        <div v-else>
                          <h5 id="pais_tipo">EXTERIOR</h5>
                          <div v-if="viaticos.id_grupo==1058">
                            <h5>GRUPO 1</h5>
                          </div>
                          <div v-else-if="viaticos.id_grupo==1059">
                            <h5>GRUPO 2</h5>
                          </div>
                          <div v-else-if="viaticos.id_grupo==1060">
                            <h5>GRUPO 3</h5>
                          </div>
                        </div>


                      </div>
                      <div class="col-sm-6">
                        <small class="text-muted">Beneficios: </small>
                        <h5>{{viaticos.hospedaje}}</h5>
                        <h5>{{viaticos.alimentacion}}</h5>
                      </div>
                  </div>
                  </div>
                  <div class="col-sm-12 text-right" style="padding-top:5rem">
                    {{viaticos.status}} - {{viaticos.estado}}
                  </div>
                </div>


              </div>
              <hr>

              <!--<br><br>
              {{ privilegio.director }}<br>
              {{ privilegio.cheque }}<br>
              {{ privilegio.efectivo }}<br>
              {{ privilegio.calculo }}<br>-->

              <!--- {{viaticos.id_pais}}  ---- {{viaticos.estado}}-->
              <h3 class="panel-title"><strong></strong></h3>
              <div class="table-responsive">



                <div v-if="privilegio.director == true && viaticos.status == 932">
                  <?php //if(usuarioPrivilegiado()->hasPrivilege(2)){?>

                    <!--NO HA SIDO AUTORIZADO-->
                    <div class="row">
                      <div class="col-sm-6">

                      </div>
                      <div class="col-sm-6 text-right">
                        <button id="" class="btn btn-info btn-sm" @click="au_solicitud(933)"><i class="fa fa-check"></i> Autorizar </button>
                        <button id="" class="btn btn-danger btn-sm" @click="au_solicitud(934)"><i class="fa fa-times"></i> Anular </button>
                      </div>
                    </div>


                </div>

                <div v-else-if="viaticos.status == 939">
                  Se tiene que liquidar
                </div>

                <div v-else-if="viaticos.status == 940 || viaticos.status == 1635 || viaticos.status==1643" >
                  <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-2 text-right">
                      <small class="text-muted p-t-30 db">Personas liquidadas</small>
                      <h4>{{totales_liquidados.personas}}</h4>
                    </div>
                    <div class="col-sm-2 text-right">
                      <small class="text-muted p-t-30 db">Complemento</small>
                      <h4>Q {{totales_liquidados.complemento}}</h4>
                    </div>
                    <div class="col-sm-2 text-right">
                      <small class="text-muted p-t-30 db">Reintegro</small>
                      <h4>Q {{totales_liquidados.reintegro}}</h4>
                    </div>
                    <div class="col-sm-2 text-right">
                      <small class="text-muted p-t-30 db">Total Liquidado</small>
                      <h4>Q {{totales_liquidados.total_liquidado}}</h4>
                    </div>
                  </div>





                </div>

                <div v-if="viaticos.status == 932 || viaticos.status == 933"> <!-- Tiene que ser 933-->
                  <?php //if(usuarioPrivilegiado()->hasPrivilege(4) || usuarioPrivilegiado()->hasPrivilege(316)  || usuarioPrivilegiado()->hasPrivilege(317)){?>

                  <div class="row" v-if="privilegio.cheque == true || privilegio.efectivo == true || privilegio.calculo == true">
                    <div class="col-sm-2">
                      <input class="btn btn-soft-info btn-sm btn-block" type='button' @click='calcular_viaticos()' value='Calcular monto'></input>
                    </div>
                    <div class="col-sm-10">
                      <div v-if=" viaticos.emitir_cheque  == 1">
                        Se calculará para que emita Cheque.
                      </div>
                      <div v-else-if=" viaticos.emitir_cheque == 2">
                        Se calculará para emitir Efectivo.
                      </div>
                      <div v-else="viaticos.emitir_cheque == 3 && viaticos.status == 933">
                        <select class="form-control form-control-sm" style="width:15%" @change="cambiarEmision($event)">
                          <option value="1">Cheque</option>
                          <option value="2">Efectivo</option>
                        </select>
                      </div>



                      <br>
                    </div>
                    <div class="col-sm-12">
                      <form class="jsValidacionProcesarNombramiento">
                        <div class="row">
                          <div class="col-sm-12">

                            <table id="tb_montos" class="table table-sm table-bordered table-striped" width="100%">
                              <thead>
                                <th class="text-center">Renglon</th>
                                <th class="text-center">Empleado</th>
                                <th class="text-center">Sueldo</th>
                                <th class="text-center">Porcentaje</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-center">Monto</th>
                                <th class="text-center" v-if="viaticos.status == 933">Cheque</th>
                                <th class="text-center" width="120px">Anticipo
                                  <div class="custom-control custom-checkbox text-center" style="position:absolute;margin-top:-1rem">
                                    <input id="id_calculos" class="custom-control-input" type="checkbox" @click="toggleSelect" :checked="selectAll" checked>
                                    <label class="custom-control-label" for='id_calculos'></label>
                                  </div>
                                </th>
                              </thead>
                              <tbody >
                                <tr v-for="c in calculos" :id="c.id">
                                  <td class="text-center">{{c.reng_num}}</td>
                                  <td class="text-center"><span v-if="c.cheque==1" class="stado_success" style="margin-left:-13px"></span> {{c.empleado}} <span v-if="c.verificar==true" class="stado_danger" style="margin-left:5px"></span></td>
                                  <td class="text-right">{{c.sueldo}}</td>
                                  <td class="text-center">{{c.porcentaje}}</td>
                                  <td class="text-center">{{c.moneda}}</td>
                                  <td class="text-right" :data-id="c.cuota_diaria">{{c.cuota_diaria}}</td>
                                  <td class="text-right" v-if="viaticos.status == 933" width="180px">
                                    <div class="form-group" v-if="c.checked==true  && emitirViatico == 1" style="margin-bottom:0rem">
                                      <div class="">
                                        <div class="">
                                          <input type="number"  :name="c.id_cheque" :id="c.id_cheque"  class="form-control input-sm"  autocomplete="off" required></input>

                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                  <td class="text-center" width="10px"><input class="tgl tgl-flip text-center" :id="c.id_anticipo" :name="c.id_anticipo" v-on:change="subtotal(c.id, c.cuota_diaria,1)" type="checkbox" v-model="c.checked" checked/>
                                                        <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.id_anticipo" ></label></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="col-sm-12 text-right">
                            <div class="row">
                              <div class="col-sm-6">
                                <div v-if="viaticos.id_pais=='GT'">
                                </div>
                                <div v-else>
                                  <input type="number" id="tasa_cambiaria" class="form-control input-sm form-corto text-right" onkeyup="conversion()" placeholder="Tipo de Cambio"></input>
                                </div>
                              </div>
                              <div class="col-sm-3">

                                <h3 v-if="viaticos.id_pais!='GT'" >Conversión: <strong><span v-if="viaticos.id_pais!='GT'" class="text-right" id="conversion"></span></strong></h3>
                              </div>

                              <div class="col-sm-3">

                                <h3>Subtotal: <strong><span class="text-right" id="sub_total"></span></strong></h3>
                              </div>

                            </div>
                            <?php //if(usuarioPrivilegiado()->hasPrivilege(4) && usuarioPrivilegiado()->hasPrivilege(10) || usuarioPrivilegiado()->hasPrivilege(316) && usuarioPrivilegiado()->hasPrivilege(10)) ?>
                            <div v-if="privilegio.cheque == true || privilegio.efectivo == true">
                              <button v-if="viaticos.status == 933" id="" type="submit" class="btn btn-info btn-sm" @click="precesar_nombramiento(935, $event)"><i class="fa fa-check"></i> Procesar </button>
                              <span v-if="viaticos.status == 933" id="" class="btn btn-danger btn-sm" @click="au_solicitud(1635,event)"><i class="fa fa-times"></i> Anular </span>
                            </div>

                          </div>
                        </form>
                        </div>

                    </div>

                  </div>

                    <?php //}?>


                </div>
                <div v-else-if="viaticos.status == 934">
                  Anulado en dirección
                </div>
                <?php //if(usuarioPrivilegiado()->hasPrivilege(4) || usuarioPrivilegiado()->hasPrivilege(10)){?>
                <div v-else-if="viaticos.status == 935 && privilegio.cheque == true">
                  Procesado

                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button id="" class="btn btn-info btn-sm" @nclick="elaborar_cheque(0)"><i class="fa fa-check"></i> Elaborar Sin Cheque </button>
                        <button id="" class="btn btn-info btn-sm" @click="elaborar_cheque(1)"><i class="fa fa-check"></i> Elaborar con Cheque </button>
                        <button id="" class="btn btn-danger btn-sm" @click="au_solicitud(1636)"><i class="fa fa-times"></i> Anular </button>
                      </div>
                    </div>

                </div>
                <div v-else-if="viaticos.status == 936 && privilegio.cheque == true">
                  Procesado
                  <h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <button id="" class="btn btn-info btn-sm" @click="au_solicitud(938)"><i class="fa fa-check"></i> Entregar Cheque </button>
                      <button id="" class="btn btn-danger btn-sm" @click="au_solicitud(1643)"><i class="fa fa-times"></i> Anular </button>
                    </div>
                  </div>
                </div>
                <div v-else-if="(viaticos.status == 7959 || viaticos.status == 938 || viaticos.status == 8194) && privilegio.cheque == true">
                  Procesado
                  <h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <button id="" class="btn btn-danger btn-sm" @click="au_solicitud(7972)"><i class="fa fa-times"></i> Anular </button>
                    </div>
                  </div>
                </div>
                <?php //}?>


                <?php //if(usuarioPrivilegiado()->hasPrivilege(316) || usuarioPrivilegiado()->hasPrivilege(10)){?>
                  <div v-if="viaticos.status == 8193 && privilegio.efectivo == true">
                    Procesado
                    <h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button id="" class="btn btn-info btn-sm" @click="au_solicitud(8194)"><i class="fa fa-check"></i> Entregar Efectivo </button>
                        <button id="" class="btn btn-danger btn-sm" @click="au_solicitud(1643)"><i class="fa fa-times"></i> Anular </button>
                      </div>
                    </div>
                  </div>
                <?php //}?>

            </div>
          </div>
        </div>
          <!-- fin-->
        </div>
        <!-- fin detalle -->
        <!--inicio empleados-->
        <div class="row" v-show="opcion==2">
          <!-- inicio -->
          <div class="col-sm-12 ">
            <div class="table-responsive">
              <table id="tb_empleados_por_nombramiento" class="table table-actions table-sm table-striped table-bordered responsive " width="1170px">
                <thead>
                  <th class="text-center">.</th>
                  <th class="text-center">Cod.</th>
                  <th class="text-center">Empleado</th>

                  <th class="text-center">VA</th>
                  <th class="text-center">VC</th>
                  <th class="text-center">VL</th>
                  <th class="text-center">% P</th>
                  <th class="text-center">% R</th>
                  <th class="text-center">Mo. P</th>
                  <th class="text-center">Mo. R</th>
                  <th class="text-center">R. auto.</th>
                  <th class="text-center">Re.</th>
                  <th class="text-center">Co</th>
                  <th class="text-center">Estado</th>
                  <!--<th class="text-center">Cheque</th>-->
                  <th class="text-center">Acción</th>
                  <th class="text-center">...</th>
                </thead>

              </table>
            </div>
          </div>

              <div class="col-sm-12" id="myapp">
                <div v-if="viaticos.dias_pendiente>0">
                  <!-- inicio -->
                  <!--<div v-if="viaticos.personas>0">-->
                    <div>
                    <!--<div v-if="viaticos.status==938 || viaticos.status==7959 || tipoS==1">-->
                    <div v-if="tipoS >= 1 && tipoL == 0">
                      <button id="" class="btn btn-info btn-sm" @click="cargarInput(1)"><i class="fa fa-check"></i> Actualizar horas </button>
                      <button id="" v-if="viaticos.personas_c==0" class="btn btn-info btn-sm" @click="au_solicitud(939)"><i class="fa fa-check"></i> Constancia </button>
                    </div>
                  </div>
                  <!--<div v-if="viaticos.status==939  || tipoS==2">-->
                  <div v-if="tipoL >= 1 &&  tipoS == 0">
                    <button id="" class="btn btn-info btn-sm" @click="cargarInput(2)"><i class="fa fa-check"></i> Actualizar montos </button>

                  </div>
                  <div v-if="tipoL >= 1 &&  tipoS >= 1">
                    <div class="alert alert-soft-danger" >
                      <i class="fa fa-minus-circle alert-icon mr-3"></i>
                      <span>Debe seleccionar a las personas que estén en el mismo proceso.</span>
                    </div>
                  </div>
                  <button id="" v-if="viaticos.personas_l==0" class="btn btn-info btn-sm" @click="au_solicitud(940)"><i class="fa fa-check"></i> Liquidar </button>


                    <div v-if="viaticos.status==938 || viaticos.status==7959 || tipoS >= 1 && tipoL == 0">
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <small class="text-muted">Destino: </small>
                          <h5>{{viaticos.destino}}</h5>
                        </div>
                        <div class="col-sm-6">
                           <div v-if="viaticos.confirma_lugar=='1'">
                             <small class="text-muted">Destino Nuevo:</small>
                             <h5 class="text-info">{{viaticos.historial}}</h5>
                           </div>

                           <div v-if="viaticos.confirma_lugar=='2'">
                             <small class="text-muted">Recorrido de la Comisión:</small>
                             <h5 class="text-info">{{viaticos.historial}}</h5>
                           </div>
                        </div>
                      </div>
                      <hr>

                      <script>

                      //setTimeout('toggleB()',200);

                      /*function obtener_municipios(){
                        get_municipios();
                      }*/

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

                                    <label class="css-input input-sm switch switch-danger"><input id="chk_confirma" @change="validacionMostrarConfirma()" type="checkbox" /><span></span> <span id="lbl_chk">SI</span></label>
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

                    <div  v-show="confirma_place==1 && tipoS >= 1 && tipoL == 0">

                      <!--<script>
                      /*$(document).ready(function(){
                        setTimeout(() => {
                          get_departamentos(2);
                        }, 400);
                      });*/

                    </script>-->
                      <form class="js-validation-confirma-lugar">
                        <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <div class="">
                                <div class="">
                                  <label for="departamento">Departamento</label>
                                  <div class=" input-group  has-personalizado" >
                                      <select class="form-control form-control-sm" id="departamento" v-on:change="get_municipios($event,77)" required name="combo_dep">
                                        <option v-for="dep in departamentos"v-bind:value="dep.dep_id" >{{ dep.dep_string }}</option>
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
                                        <option v-for="muni in munis0" v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
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
                        <button id="a" class="btn btn-info btn-sm btn-block" @click="confirmar_lugar()"><i class="fa fa-check"></i> Guardar nuevo lugar </button>
                      </form>

                    </div>
                    <div v-show="confirma_place==2 && tipoS >= 1 && tipoL == 0">

                      <form class="js-validation-agregar-lugares">
                        <div class="row">
                          <!--<div class="col-sm-12 text-right">
                            <br><br>
                          </div>-->
                          <span id="destinosfal" hidden>{{ viaticos.total_destinos }}</span>
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
                                <tr v-for='(d, index) in destinos' v-if="index < viaticos.total_destinos" :key="index">
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
                            <button class="btn btn-sm btn-info btn-block" @click="agregarLugares()"><i class="fa fa-check"></i> Guardar</button>
                          </div>
                        </div>

                      </form>
                    </div>

                  <!-- fin -->
                </div>




              </div>

            </div>
          <!-- fin -->
        </div>
        <!-- fin empleados-->
        <input id="id_persona" hidden value=""></input>
        <input id="id_renglon" hidden value=""></input>
        <!-- inicio empleados -->
        <div class="row" v-show="opcion==3">
          <span class="btn-regresar" style="float:left;" @click="getOpcion(2)"></span> Regresar


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




                <div class="col-sm-12" style="border-top:1px solid #e9ecf0">
                  <div v-if="viaticos.status == 938 || viaticos.status == 7959 || viaticos.status == 8194 || tipoS >= 1">

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
                                    <input type="number" min='1' id="id_num_vuelo_salida" class="form-control input-sm"></input>
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
                                    <input type="number" min='1' id="id_num_vuelo_entrada" class="form-control input-sm" ></input>
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
                        <button id="" class="btn btn-info btn-sm" @click="generarConstancia()"><i class="fa fa-check"></i> Guardar </button>
                        <span id="" class="btn btn-warning btn-sm" @click="confirmar_ausencia()"><i class="fa fa-times"></i> No asistió </span>
                      </div>

                    </div>
                    <!-- fin-->

                  </form>
                  </div>


                </div>








              </div>

        </div>
        <!-- fin empleados -->
        <!-- inicio liquidacion global -->
        <!--<input :value="empleado.empleado"></input>-->
        <div class="row" v-show="opcion==4">
          <span class="btn-regresar" style="float:left;" @click="getOpcion(2)"></span> Regresar
          <ul>
            <li v-for="e in empleados_pro">
              {{ e.empleado }}
            </li>
          </ul>
          <hr>


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
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_fecha_liquidacion">Fecha Liquidación*</label>
                          <div class=" input-group  has-personalizado" >
                            <input type="date" class=" form-control input-sm" id="id_fecha_liquidacion" name="id_fecha_liquidacion" placeholder="@fecha liquidación" :value="empleado.fecha_liquidacion" required  autocomplete="off"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 text-right">
                    <button id="" class="btn btn-info btn-sm" @click="generar_liquidacion()"><i class="fa fa-check"></i> Calcular liquidación </button>

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

        <!-- fin liquidacion global -->
      </div>
      <!-- inicio cambio empleado -->
      <div class="row" v-show="opcion==5">
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
              <button id="" class="btn btn-info btn-sm btn-block" @click="sustituirEmpleado()"><i class="fa fa-check"></i> Sustituir </button>
            </div>
          </div>
        </div>
      </div>
      <!-- fin cambio empleado -->

    </div>
    <div v-show="isLoaded==false">
      <div class="loaderr">
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
