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

    /*}

    if (!empty($_POST)){
      header("Location: index.php?ref=_200");
    }else{
      /*include_once '../../back/functions.php';
      $clase= new insumo();
*/
      /*$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach($datos AS $d){
        $bodega = $d['id_bodega_insumo'];
      }
    }*/


?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="insumos/js/cargar.js"></script>
  <script src="viaticos/js/funciones_1.1.js"></script>
  <!--<script src="viaticos/js/source_modal.js"></script>-->
  <script src="viaticos/js/cargar_vue.js"></script>


  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script type="text/javascript">
  //editar_fecha();




</script>

</head>
<body>


  <!--<input id="id_viatico" hidden value="<?php echo $id_viatico;?>"></input>-->

    <div id="myapp" >

      <template>


    </template>


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

            <!--- {{viaticos.id_pais}}  ---- {{viaticos.estado}}-->
            <h3 class="panel-title"><strong></strong></h3>
            <div class="table-responsive">



              <div v-if="viaticos.status == 932">
                <?php if(usuarioPrivilegiado()->hasPrivilege(2)){?>

                  <!--NO HA SIDO AUTORIZADO-->
                  <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6 text-right">
                      <button id="" class="btn btn-info btn-sm" onclick="au_solicitud(933)"><i class="fa fa-check"></i> Autorizar </button>
                      <button id="" class="btn btn-danger btn-sm" onclick="au_solicitud(934)"><i class="fa fa-times"></i> Anular </button>
                    </div>
                  </div>
                  <script>


                </script>
                <?php }?>

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
                <?php if(usuarioPrivilegiado()->hasPrivilege(4)){?>


                <div class="row">
                  <div class="col-sm-12">
                    <input class="btn btn-soft-info btn-sm" type='button' @click='calcular_viaticos()' value='Calcular monto'></input>
                    <br><br>
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
                                  <div class="form-group" v-if="c.checked==true" style="margin-bottom:0rem">
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

                          <button v-if="viaticos.status == 933" id="" type="submit" class="btn btn-info btn-sm" onclick="precesar_nombramiento(935, event)"><i class="fa fa-check"></i> Procesar </button>
                          <span v-if="viaticos.status == 933" id="" class="btn btn-danger btn-sm" onclick="au_solicitud(1635,event)"><i class="fa fa-times"></i> Anular </span>
                        </div>
                      </form>
                      </div>

                  </div>

                </div>

                  <?php }?>


              </div>
              <div v-else-if="viaticos.status == 934">
                Anulado en dirección
              </div>
              <div v-else-if="viaticos.status == 935">
                Procesado
                <?php if(usuarioPrivilegiado()->hasPrivilege(4)){?>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <button id="" class="btn btn-info btn-sm" onclick="elaborar_cheque(0)"><i class="fa fa-check"></i> Elaborar Sin Cheque </button>
                      <button id="" class="btn btn-info btn-sm" onclick="elaborar_cheque(1)"><i class="fa fa-check"></i> Elaborar con Cheque </button>
                      <button id="" class="btn btn-danger btn-sm" onclick="au_solicitud(1636)"><i class="fa fa-times"></i> Anular </button>
                    </div>
                  </div>

              </div>
              <div v-else-if="viaticos.status == 936">
                Procesado
                <h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <button id="" class="btn btn-info btn-sm" onclick="au_solicitud(938)"><i class="fa fa-check"></i> Entregar Cheque </button>
                    <button id="" class="btn btn-danger btn-sm" onclick="au_solicitud(1643)"><i class="fa fa-times"></i> Anular </button>
                  </div>
                </div>
              </div>
              <div v-else-if="viaticos.status == 7959 || viaticos.status == 938">
                Procesado
                <h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <button id="" class="btn btn-danger btn-sm" onclick="au_solicitud(7972)"><i class="fa fa-times"></i> Anular </button>
                  </div>
                </div>
              </div>
              <?php }?>

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
