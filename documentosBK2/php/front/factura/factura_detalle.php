<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $orden_id=null;
    $ped_num = null;

    if ( !empty($_GET['orden_id'])) {
      $orden_id = $_REQUEST['orden_id'];
    }

    if ( !empty($_GET['ped_num'])) {
      $ped_num = $_REQUEST['ped_num'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_902");
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

  <script src="documentos/js/validaciones.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="assets/js/pages/components.js"></script>
  <script src="documentos/js/components/components1.10.js"></script>
  <script src="documentos/js/factura_vue_detalle_1.3.js" ></script>
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
  <style>
  .table-bitacora-fac > thead, .table-bitacora-fac > tbody { display: block; }

  .table-bitacora-fac >tbody td, .table-bitacora-fac > thead th {
    width: 20%;  /* Optional */
}

  .table-bitacora-fac > tbody {
    height: 250px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
    overflow-x: hidden;
    width: 100%; /* Hide the horizontal scroll */
}
  </style>
</head>

<div id="app_factura" style="-webkit-transform: translateZ(0);">
  <div class="modal-header">
    <h3>Detalle de la Factura</h3>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado btn-sm" checked @click="showAsignar(1)">
          <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
        </label>
        <label class="btn btn-personalizado btn-sm" @click="showAsignar(2)">
          <input type="radio" name="options" id="option1" autocomplete="off" > Bitácora
        </label>
        <label class="btn btn-personalizado btn-sm salida" data-toggle="tooltip" title="Cerrar">
          <span name="options" id="option3" autocomplete="off" data-dismiss="modal" aria-label="Close"> Salir
        </label>
      </div>
    </ul>
  </div>
  <div class="modal-body">
    <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>
    <input id="orden_id" value="<?php echo $orden_id?>" hidden></input>
    <div>
      <div>
        <div class="row">

          <div class="col-sm-4" style="min-height:150px; border-right:2px dashed #F2F1EF;">
            <div class="" >
              <div class="">
                <!-- inicio -->
                <div class="row">

                  <div class="col-sm-6" >
                    <dato-persona icono="fa fa-receipt" texto="Número:" :dato="factura.factura_num" tipo="1"></dato-persona>
                    <dato-persona icono="far fa-calendar-check" texto="Fecha:" :dato="factura.factura_fecha" tipo="1"></dato-persona>
                  </div>
                  <div class="col-sm-6" >
                    <dato-persona icono="fa fa-file-invoice" texto="Serie:" :dato="factura.factura_serie" tipo="1"></dato-persona>
                    <dato-persona icono="fa fa-address-card" texto="Nit del Proveedor:" :dato="factura.proveedor_nit" tipo="1"></dato-persona>
                  </div>
                  <div class="col-sm-12">
                    <dato-persona icono="fa fa-user" texto="Razón Social:" :dato="factura.proveedor" tipo="1"></dato-persona>
                  </div>
                </div>
                  <!-- fin -->
                  <br>
              </div>

              <div class="card-footer bg-muted" style="background: transparent">
                <div class="row">
                  <div class="col-sm-3 text-left">
                    Monto:
                  </div>
                  <div class="col-sm-9 text-right">
                    <h1 class="">{{ factura.factura_total }}</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <div class="col-sm-8 " v-if="asignarF == 1">
            <div class="row " style="min-height:230px;">
              <asignar-tecnico columna="col-sm-6" class="" option="1" :orden_id="orden_id" :privilegio="privilegio" tipo="1" style=" border-right:2px dashed #F2F1EF;"></asignar-tecnico>

              <asignar-modalidad-compra :factura="factura" :privilegio="privilegio" columna="col-sm-6"></asignar-modalidad-compra>
            </div>
          </div>
          <div class="col-sm-8" v-if="asignarF == 2">
            <div class="card ">
              <factura-estado columna="col-sm-12" option="1" :orden_id="orden_id" :factura="factura" v-if="privilegio.compras_recepcion == true"></factura-estado>
              <factura-bitacora :orden_id="orden_id" :privilegio="privilegio" :factura="factura"></factura-bitacora>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-body" style="border-top:1.2px solid #F2F1EF">
    <div>
      <div>


      </div>
      <div v-if="factura.estado_id == 5">
      </div>
      <div v-else>
        <!-- inicio insumos -->

        <div v-if="insumosf.length > 0">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-sm table-bordered table-striped">
                <thead>
                  <th class="text-center" style="width:15px">Renglon</th>
                  <th class="text-center" style="width:15px">Insumo</th>

                  <th class="text-center" style="width:100px">Nombre</th>
                  <th class="text-center" style="width:100px">Descripción</th>
                  <th class="text-center" style="width:15px">Pres.</th>
                  <th class="text-center" style="width:15px">Med.</th>
                  <th class="text-center" style="width:15px">Cantidad</th>
                </thead>
                <tbody>
                  <tr v-for="i in insumosf">
                    <td class="text-center">{{i.Ppr_Ren}}</td>
                    <td class="text-center">{{i.Ppr_cod}}</td>

                    <td class="text-center">{{i.Ppr_Nom}}</td>
                    <td class="text-justify">{{i.Ppr_Des}}</td>
                    <td class="text-center">{{i.Ppr_Pres}}</td>
                    <td class="text-center">{{i.Ppr_Med}}</td>
                    <td class="text-center">{{i.Ppr_can}}</td>
                    <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div v-else>

          <div class="row" v-if="privilegio.compras_tecnico_factura == true">
            <div class="col-sm-2">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <label for="id_pedido_num">No. de Pedido:</label>
                    <div class=" input-group  has-personalizado" >
                      <input type="number" class="form-control form-control_ input-sm" id="id_pedido_num" name="id_pedido_num" placeholder="@Número de Pedido" value="<?php echo $ped_num;?>" v-bind:disabled="habilitar" required autocomplete="off" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <label class="text-white">d</label>
                    <div class=" input-group  has-personalizado" >
                      <span class="btn btn-sm btn-soft-info" @click="getInsumosParaFactura()"><i class="fa fa-search"></i> Buscar</span>
                    </div>

                  </div>
                </div>

              </div>
            </div>
            <div class="col-sm-12">
              <form class="jsValidacionFacturaInsumos">
                <!-- inicio factura -->
                <div class="row">
                  <!--inicio tabla -->
                  <div class="col-sm-12">
                    <table id="tb_detalle_factura" class="table table-sm table-bordered table-striped">
                      <thead>
                        <th class="text-center" style="width:100px">Insumo</th>
                        <th class="text-center" style="width:100px">Nombre</th>
                        <th class="text-center" style="width:15px">Descripción</th>
                        <th class="text-center" style="width:15px">Med.</th>
                        <th class="text-center" style="width:15px">Requerido</th>

                        <th class="text-center" style="width:15px">Cantidad</th>
                        <th class="text-center" style="width:15px">Recibido</th>
                        <th class="text-center" style="width:15px">Factura
                          <div class="custom-control custom-checkbox text-center" style="position:absolute;margin-top:-1rem">
                            <input id="id_factura_c" class="custom-control-input" type="checkbox" @click="toggleSelect" :checked="selectAll" >
                            <label class="custom-control-label" for='id_factura_c'></label>
                          </div>
                        </th>
                      </thead>
                      <tbody>
                        <tr v-for="(i, index) in insumos">
                          <td class="text-left">
                            <strong>{{i.Ppr_Ren}} - </strong>
                            <strong>{{i.Ppr_cod}} </strong>
                          </td>
                          <td class="text-center">{{i.Ppr_Nom}}</td>
                          <td class="text-justify">{{i.Ppr_Des}}</td>
                          <td class="text-center">{{i.Ppr_Pres}}<br>{{i.Ppr_Med}}</td>
                          <td class="text-center"><span v-model="i.v_req">{{i.Pedd_can}}</span></td>
                          <td class="text-center" style="width:60px">
                            <div class="form-group" v-if="i.checked==true" style="margin-bottom:0rem">
                              <div class="">
                                <div class="">
                                  <input type="number"  :name="i.id_cant" :id="i.id_cant"  class="form-control input-sm" v-model="i.v_rec" autocomplete="off" min="1" required></input>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="text-center"><span :id="'st'+index" v-model="i.v_fal">{{ computeSubTotal(i) }}</span></td>
                          <td class="text-center">
                            <input class="tgl tgl-flip text-center" :id="i.Ppr_id" :name="i.Ppr_id" type="checkbox" v-model="i.checked"/>
                            <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="i.Ppr_id"></label>
                          </td>
                          <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-sm-12 text-right">
                    <button class="btn btn-sm btn-info" @click="agregarInsumosFactura($event)"><i class="fa fa-plus-circle"></i> Guardar</button>
                    <span class="btn btn-sm btn-danger" @click=""><i class="fa fa-times-circle"></i> Cancelar</span>

                  </div>
                </div>

                <!-- fin factura -->
              </form>

            </div>
          </div>
          <div v-else>
            <div class="alert alert-soft-danger" >
              <i class="fa fa-minus-circle alert-icon mr-3"></i>
              <span>No se ha asignado No. de PYR a esta factura.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>


</div>
  <!-- fin -->
</div>


    <!-- fin factura -->



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
