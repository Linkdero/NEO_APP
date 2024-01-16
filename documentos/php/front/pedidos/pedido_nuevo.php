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
  <script src="documentos/js/pedidos_vue.js"></script>

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
    <div id="app_pedidos">
      <form class="jsValidacionJustificacionNueva">
        <div class="row">



          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_pedido">Cantidad* (Seleccionar Pedido y Remesa)</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-select2 form-control form-control-sm" id="id_insumo" name="combo1">
                      <option v-for="i in insumos_list" ><strong>{{ i.Ppr_cod }}</strong> - {{ i.Ppr_cod }} : {{ i.Ppr_Nom }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!-- inicio -->

          <!--<div class="col-sm-12" v-show="pedido_seleccionado==1">
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
                    <td class="text-center">{{i.Ppr_id}}</td>
                    <td class="text-center">{{i.Ppr_codPre}}</td>
                    <td class="text-center">{{i.Ppr_Pres}}</td>
                    <td class="text-center">{{i.Ppr_Nom}}</td>
                    <!--<td class="text-center">{{i.Ppr_Des}}</td>-->


                    <!---<td class="text-center">{{i.Pedd_can}}</td>
                    <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                  <!--</tr>
                </tbody>
              </table>
              <hr>
            </div>
          </div>-->

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
