<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    include_once '../../back/functions.php';
    $docto_id=null;

    if ( !empty($_GET['docto_id'])) {
      $docto_id = $_REQUEST['docto_id'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_900");
    }else{

    }


?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="documentos/js/documento_vue_detalle.js"></script>
  <!--<script src="viaticos/js/source_modal.js"></script>-->
  <script>

  </script>

</head>
<body>
  <div id="appdocDetalle">
    <div class="modal-header">
      <h4 class="modal-title">Detalle del Documento: <strong>{{doctoDetalle.correlativo}}</strong></h4>
      <ul class="list-inline ml-auto mb-0">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-personalizado btn-sm" checked @click="getOpcion(1)" checked>
            <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(2)" v-show="doctoDetalle.validacion">
            <input type="radio" name="options" id="option2" autocomplete="off"  > Junta
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)" v-show="doctoDetalle.validacion">
            <input type="radio" name="options" id="option3" autocomplete="off"  > Criterios
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(4)" v-show="doctoDetalle.validacion">
            <input type="radio" name="options" id="option3" autocomplete="off"  > Bases
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(5)" v-show="doctoDetalle.validacion">
            <input type="radio" name="options" id="option3" autocomplete="off"  > Fluctuación
          </label>
          <label class="btn btn-personalizado btn-sm" data-dismiss="modal">
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
    <input id="docto_id" hidden value="<?php echo $docto_id;?>"></input>
    <input id="categoria_id" hidden v-bind:value="doctoDetalle.tipo_id"></input>

    <div class="modal-body">

      <div class="row">
        <div class="col-sm-12">
          {{doctoDetalle.titulo}} **
          {{doctoDetalle.tipo}} **
          {{doctoDetalle.fecha_docto}}
          <hr>

        </div>

        <div v-show="doctoDetalle.validacion" class="col-sm-12">
          <!-- inicio cronograma -->
          <div class="slide_up_anim" v-show="validacion==1" >
            CRONOGRAMA
            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class="text-center">ACTIVIDAD</th>
                <th class="text-center">FECHA O PLAZO</th>
                <th class="text-center">ESTADO</th>
              </thead>
              <tbody>
                <tr v-for="c in cronograma">
                  <td style="width:400px;text-align:justify">{{c.actividad_string}}</td>
                  <td class="text-center" v-if="c.actividad_id==8067 || c.actividad_id==8068 || c.actividad_id==8071 || c.actividad_id==8072">
                    <span class="fecha_cronograma" :data-id="doctoDetalle.docto_id" :data-pk="c.actividad_parametros" :data-name="doctoDetalle.docto_id">{{c.actividad_fecha}}</span>
                  </td>
                  <td v-else style="text-align:justify">{{c.actividad_fecha}}</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- fin cronograma-->
          <!-- inicio junta-->
          <div class="slide_up_anim" v-show="validacion==2">
            Junta
          </div>
          <!-- fin junta-->

          <!-- inicio criterios de evaluación-->
          <div class="slide_up_anim" v-show="validacion==3">
            Criterios

            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class="text-center">No.</th>
                <th class="text-center">Criterio a Calificar</th>
                <th class="text-center">Punteo</th>
              </thead>
              <tbody>
                <tr v-for="c in criterios">
                  <td class="text-center">{{c.actividad_string_c}}</td>
                  <td style="width:550px">{{c.actividad_string}}</td>
                  <td class="text-center">
                    <span class="valor_criterio" :data-id="doctoDetalle.docto_id" :data-pk="c.actividad_parametros" :data-name="doctoDetalle.docto_id">{{c.actividad_valor}}</span>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <td></td>
                <td class="text-right" >TOTAL EN PUNTOS</td>
                <td class="text-center" :class="[total==100 ? 'bg-soft-success' : 'bg-soft-danger']"><i class="fa" :class="[total==100 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger']"></i><span> {{total}}</span></td>
              </tfoot>
            </table>
            <!-- inicio contrato-->
            <br><h4>Condiciones del contrato</h4>
            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class="text-center">No.</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">En</th>
              </thead>

              <tbody>
                <tr v-for="c in condiciones_contrato">
                  <td class="text-center" style="width:300px">{{c.actividad_string_c}}</td>
                  <td class="text-center" style="width:200px">
                    <span class="valor_criterio" :data-id="doctoDetalle.docto_id" :data-pk="c.actividad_parametros" :data-name="doctoDetalle.docto_id">{{c.actividad_valor}}</span>
                  </td>
                  <td style="width:550px">meses</td>

                </tr>
              </tbody>
            </table>
            <!-- fin contrato -->
          </div>
          <!-- fin criterios de evaluación-->

          <!-- inicio condiciones del contrato-->
          <div class="slide_up_anim" v-show="validacion==4">
            Bases <span class="btn btn-sm btn-soft-info" @click="getOpcion(6)"><i class="fa fa-plus-circle"></i> Agregar</span><br><br>
            <!-- bases del contrato -->
            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class="text-center">No.</th>
                <th class="text-center">Descripción</th>
              </thead>

              <tbody>
                <tr v-for="l in listado_literales">
                  <td class="text-center" >{{l.base_literal_nom}}</td>
                  <td style="text-align:justify" >
                    <strong>{{l.base_literal_titulo}}</strong> {{l.base_literal_descripcion}}
                    <!--<span class="valor_criterio" :data-id="doctoDetalle.docto_id" :data-pk="c.actividad_parametros" :data-name="doctoDetalle.docto_id">{{c.actividad_valor}}</span>-->
                  </td>


                </tr>
              </tbody>
            </table>
            <!-- fin bases -->

          </div>
          <!-- fin condiciones del contrato-->
          <!-- inicio agregar literal por documento -->
          <div class="slide_up_anim" v-show="validacion==6">
            Agregar listado
            <!-- bases del contrato -->
            <form class="jsValidacionNuevaLiteral">
              <div class="row">
                <!--inicio-->
                <div class="col-sm-6">
                  <div class="form-group" id="fg_correlativo_respuesta">
                    <div class="">
                      <div class="">
                        <label for="id_hora_salida_c">Inciso / literal*</label>
                        <div class=" input-group  has-personalizado" >
                          <input class="form-control input-sm" type="text" id="id_literal" oninput="this.value = this.value.toUpperCase()" name="id_literal" placeholder="@Inciso / literal" autocomplete="off" required/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <!--inicio-->
                <div class="col-sm-6">
                  <div class="form-group" id="fg_correlativo_respuesta">
                    <div class="">
                      <div class="">
                        <label for="id_titulo_literal">Titulo*</label>
                        <div class=" input-group  has-personalizado" >
                          <input class="form-control input-sm" type="text" id="id_titulo_literal" name="id_titulo_literal" placeholder="@Título" autocomplete="off" required/>
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
                        <label for="id_descripcion_literal">Descripción*</label>
                        <div class=" input-group  has-personalizado" >
                          <textarea rows="2" class="js-datepicker form-control form-control_ input-sm" id="id_descripcion_literal" name="id_descripcion_literal" placeholder="@Descripción" required autocomplete="off"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->
                <div class="col-sm-12 text-right">
                  <button class="btn btn-sm btn-info" @click="saveNewLiteral()"><i class="fa fa-check"></i> Guardar</button>
                  <span class="btn btn-sm btn-danger" @click="getOpcion(4)"><i class="fa fa-times"></i> Cancelar</span>
                </div>
              </div>
            </form>
            <!-- fin bases -->

          </div>
          <!-- fin agregar literal por documento -->

          <!-- inicio condiciones económicas-->
          <div class="slide_up_anim" v-show="validacion==5">
            Fluctuaciones

            <div class="form-group">
              <textarea class="form-control" rows="3"></textarea>
            </div>
          </div>
          <!-- fin condiciones económicas-->


        </div>


      </div>

      <div id="datos_nombramiento" class="slide_up_anim">
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
