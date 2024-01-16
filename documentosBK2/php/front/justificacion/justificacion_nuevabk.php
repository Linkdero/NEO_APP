<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $tipo_filtro=null;
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script src="documentos/js/validaciones.js"></script>
  <script src="documentos/js/funciones.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="documentos/js/justificacion_vue.js"></script>

  <script>
  </script>
</head>
<body>
  <div class="modal-header">
    <h5>Justificación Nueva</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <div id="app_justificacion">
      <form class="jsValidacionJustificacionNueva">
        <div class="row">
          <!-- inicio -->

          <div class="col-sm-8">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="">Solicitud de Bien o Servicio</label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-success" style="float:right"><input id="rd_servicio" name="tipo_compra" required type="radio"/><span></span> <span id="lbl_rdemitido"><small>Servicio, mantenimiento y/o reparación</small></span></label>
                  </div>
                  <label for="id_confirma"></label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-success" style="float:right"><input id="rd_bien" name="tipo_compra" required type="radio"/><span></span> <span id="lbl_rdrecibido"><small>Materiales, insumos o equipo</small></span></label>
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
                <div class="row">
                  <label for="">Diagnóstico Técnico</label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-success" style="float:right"><input id="rd_dg_si" name="tipo_diagnostico" @change="validacionMostrarConfirma()" required type="radio"/><span></span> <span id="lbl_rdemitido">SI</span></label>
                  </div>
                  <label for="id_confirma"></label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-success" style="float:right"><input id="rd_dg_no" name="tipo_diagnostico" @change="validacionMostrarConfirma()" required type="radio"/><span></span> <span id="lbl_rdrecibido">NO</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin-->
          <!--inicio-->
          <div class="col-sm-12" v-if="mostrarDictamen==1">
            <div class="row">
              <table class="table" id="tb_dictamenes">
                <thead class="text text-success">
                  <tr>
                    <th class="text-center">
                      <button type='button'class="btn btn-sm btn-soft-info text-right" @click="addNewRow">
                        <i class="fas fa-plus-circle"></i>
                      </button>
                      Dictamen
                    </th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for='(d, index) in dictamenes' :key="index">
                    <td width="200">
                      <div class="form-group" style="margin-bottom:0rem">
                        <div class="">
                          <div class="">
                            <input :name="'txt'+index" :id="'txt'+index"  class="form-control input-sm" v-model="d.dictamen" type="text" autocomplete="off" required></input>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td width="200">
                      <div class="form-group" style="margin-bottom:0rem">
                        <div class="">
                          <div class="">
                            <input :name="'f'+index" :id="'f'+index"  class="form-control input-sm" v-model="d.fecha" type="date" autocomplete="off" required></input>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td scope="row" class="trashIconContainer text-center">
                      <span class="btn btn-sm btn-danger" @click="deleteRow(index, d)"><i class="far fa-trash-alt" ></i></span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_justificacion">Justifiación de gasto por:*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="id_justificacion" name="id_justificacion" placeholder="@Nombre" required autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_pedido">Cantidad* (Seleccionar Pedido y Remesa)</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-select2 form-control form-control-sm" id="id_pedido" name="combo1" @change="get_pedido_detalle($event)">
                      <option v-for="p in pedidos" v-bind:value="p.ped_tra"><strong>{{ p.pedido_num }}</strong> - {{ p.fecha }} : {{ p.observaciones }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!-- inicio -->

          <div class="col-sm-12" v-show="pedido_seleccionado==1">
            <div class="form-group">
              <label for="id_fecha_salida_c">Detalle del Pedido Seleccionado</label>
              <br>
              <div class="row">
                <div class="col-sm-3">
                  <small class="text-muted">Pedido No. </small>
                   <h5><strong>{{pedido.pedido_num}}</strong></h5>
                   <small class="text-muted">Fecha: </small>
                    <h5 id="fecha_pedido" ><strong>{{pedido.fecha}}</strong></h5>
                </div>
                <div class="col-sm-9">
                  <small class="text-muted">Observaciones: </small>
                   <h5 style="text-align:justify">{{pedido.observaciones}}</h5>
                </div>
              </div>

              <table class="table-dark table table-sm table-bordered table-striped">
                <thead>
                  <th class="text-center" style="width:15px">Renglon</th>
                  <th class="text-center" style="width:15px">Insumo</th>
                  <th class="text-center" style="width:15px">Cod. Pre.</th>
                  <th class="text-center" style="width:15px">Pres.</th>
                  <th class="text-center" style="width:100px">Nombre</th>
                  <th class="text-center" style="width:15px">Cantidad</th>
                </thead>
                <tbody>
                  <tr v-for="i in insumos">
                    <td class="text-center">{{i.Ppr_Ren}}</td>
                    <td class="text-center">{{i.Ppr_cod}}</td>
                    <td class="text-center">{{i.Ppr_codPre}}</td>
                    <td class="text-center">{{i.Ppr_Pres}}</td>
                    <td class="text-center">{{i.Ppr_Nom}}</td>
                    <!--<td class="text-center">{{i.Ppr_Des}}</td>-->


                    <td class="text-center">{{i.Pedd_can}}</td>
                    <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                  </tr>
                </tbody>
              </table>
              <hr>
            </div>
          </div>

          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_especificaciones">Especificaciones*</label> <span>Le quedan {{ totalcharacter3 }} caracteres</span>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" maxlength="450" class="js-datepicker form-control form-control_ input-sm" id="id_especificaciones" name="id_especificaciones" placeholder="@Título" required autocomplete="off" v-model='message3' @keyup='charCount3()'></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_necesidad">Necesidad*</label> <span>Le quedan {{ totalcharacter4 }} caracteres</span>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" maxlength="450" class="js-datepicker form-control form-control_ input-sm" id="id_necesidad" name="id_necesidad" placeholder="@Título" required autocomplete="off" v-model='message4' @keyup='charCount4()'></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_temporalidad">Temporalidad de uso de la adquisición*</label>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" maxlength="100" class="js-datepicker form-control form-control_ input-sm" id="id_temporalidad" name="id_temporalidad" placeholder="@Título" required autocomplete="off"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_finalidad">Finalidad*</label>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" maxlength="100" class="js-datepicker form-control form-control_ input-sm" id="id_finalidad" name="id_finalidad" placeholder="@Título" required autocomplete="off"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_resultado">Resultado*</label>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" maxlength="100" class="js-datepicker form-control form-control_ input-sm" id="id_resultado" name="id_resultado" placeholder="@Título" required autocomplete="off"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->


          <!--inicio-->
          <div class="col-sm-12 text-right">
            <button class="btn-sm btn btn-info" onclick="crearJustificacion()"><i class="fa fa-check"></i> Generar</button>
          </div>
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
