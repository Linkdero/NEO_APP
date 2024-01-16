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
  <script src="documentos/js/justificacion_vue_detalle.js"></script>
  <!--<script src="viaticos/js/source_modal.js"></script>-->
  <script>

  </script>

</head>
<body>
  <div id="appjusDetalle">
    <div class="modal-header">
      <h4 class="modal-title">Detalle de la Justificación: <strong>{{justificacionDetalle.correlativo}}</strong></h4>
      <ul class="list-inline ml-auto mb-0">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-personalizado btn-sm" checked @click="getOpcion(1)" checked>
            <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(2)">
            <input type="radio" name="options" id="option2" autocomplete="off"> Pedido
          </label>
          <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)">
            <input type="radio" name="options" id="option3" autocomplete="off"> Dictamen
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
    <input id="ped_tra" hidden v-bind:value="justificacionDetalle.pedido_tra"></input>

    <div class="modal-body">

      <div class="row">
        <div class="col-sm-12">
          {{justificacionDetalle.tipo}} **
          {{justificacionDetalle.fecha_docto}}
          <hr>

        </div>

        <div class="col-sm-12">
          <!-- inicio cronograma -->
          <div class="slide_up_anim" v-show="validacion==1" >
            JUSTIFICACION
            <div class="row">
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Justificación </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="1" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.titulo}}</span>
              </div></div></div><br><br>
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Especificaciones: </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="2" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.especificaciones}}</span>
              </div></div></div><br><br>
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Necesidad: </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="3" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.necesidad}}</span>
              </div></div></div><br><br>
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Temporalidad: </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="4" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.temporalidad}}</span>
              </div></div></div><br><br>
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Finalidad: </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="5" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.finalidad}}</span>
              </div></div></div><br><br>
              <div class="col-sm-12"><div class="row"><div class="col-sm-2"><h5 class="text-muted">Resultado </h5></div><div class="col-sm-10">
                <span class="string" :data-id="justificacionDetalle.docto_id" :data-pk="6" :data-name="justificacionDetalle.docto_id">{{justificacionDetalle.resultado}}</span>
              </div></div></div><br><br>
            </div>


          </div>
          <!-- fin cronograma-->
          <!-- inicio junta-->
          <div class="slide_up_anim" v-show="validacion==2">
            DETALLE DEL PEDIDO

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
            <br>

            <table class="table table-sm table-bordered table-striped">
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
          </div>
          <!-- fin junta-->
          <div class="slide_up_anim" v-show="validacion==3">
            DICTAMENES


            <br>

            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class="text-center" >Dictamen</th>
                <th class="text-center" >Fecha</th>
                <th class="text-center" >Estado</th>
              </thead>
              <tbody>
                <tr v-for="d in dictamenes">
                  <td class="text-center">{{d.docto_dictamen}}</td>
                  <td class="text-center">{{d.docto_fecha}}</td>
                  <td class="text-center">{{d.status}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- fin junta-->




        </div>


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
