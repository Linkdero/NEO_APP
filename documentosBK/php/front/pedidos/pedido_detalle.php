<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $ped_tra=null;

    if ( !empty($_GET['ped_tra'])) {
      $ped_tra = $_REQUEST['ped_tra'];
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

  <script src="documentos/js/validaciones.js"></script>
  <script src="documentos/js/funciones.js"></script>

  <script src="documentos/js/components/components1.3.js"></script>
  <script src="documentos/js/pedido_vue_detalle_1.5.js"></script>
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_seguimiento">
</div>
<div id="app_bitacora">
</div>
<div id="app_pedido_detalle">
  <div class="modal-header">
    <h3>Detalle del Pedido</h3>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado btn-sm" checked @click="getOpcion(1)" >
          <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(2)">
          <input type="radio" name="options" id="option2" autocomplete="off"> Seguimiento
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)">
          <input type="radio" name="options" id="option3" autocomplete="off"> Bitácora
        </label>
        <label class="btn btn-personalizado btn-sm" @click="getOpcion(4)" >
          <input type="radio" name="options" id="option1" autocomplete="off" > Facturas
        </label>
        <label class="btn btn-personalizado btn-sm salida" >
          <span name="options" id="option3" autocomplete="off"  > Salir
        </label>
      </div>
    </ul>
  </div>
  <div class="modal-body">
    <input id="id_cambio" :value="cambio" hidden></input>
    <input id="id_pedido" value="<?php echo $ped_tra?>" hidden></input>
    <input id="id_direccion" :value="pedido.direccion_funcional" hidden></input>
    <input id="id_verificacion" :value="estado.verificacion" hidden></input>

    <div class="row">
      <div v-if="sopcion != 2 && sopcion != 3" class="col-sm-12">

        <div class="row">
          <pedido :ped_tra="ped_tra" :estad="estado" v-on:event_child="getPedidoD"></pedido>
          <porcentaje-pedido :ped_tra="ped_tra"></porcentaje-pedido>
          <hr>

        </div>
      </div>

      <!-- inicio detalle -->
      <div class="col-sm-12" v-show="opcion==1">
        <div class="row">
          <div class="col-sm-12">

            <insumos :ped_tra="ped_tra"></insumos>
          </div>

        </div>
      </div>
      <!-- fin detalle -->
      <!-- inicio seguimiento -->
      <div class="col-sm-12" v-show="opcion==2">
        <div class="row">
          <!-- inicio -->
          <div class="col-sm-12" v-if="
              estado.verificacion == 1 ||
              estado.verificacion == 3 ||
              estado.verificacion == 4 ||
              estado.verificacion == 5 ||
              estado.verificacion == 7 ||
              estado.verificacion == 8 ||
              estado.verificacion == 9 ||
              estado.verificacion == 10 ||
              estado.verificacion == 11 ||
              estado.verificacion == 12 ||
              estado.verificacion == 13 ||
              estado.verificacion == 1000 ||
              estado.verificacion == 1100 ">
          <personas-direccion :pedido="pedido" :verificacion="estado.verificacion" :titulo="estado.titulo"></personas-direccion>
          </div>

          <!-- inicio -->
          <div class="col-sm-12" v-if="estado.verificacion == 2 || estado.verificacion == 6">
            <br>
            <seguimiento-list :ped_tra="ped_tra" :verificacion="estado.verificacion"></seguimiento-list>
          </div>
          <!-- fin -->

          <!--estado id :{{ estado.estado_id }}<br>
          bitacora id : {{ estado.bitacora_id }}<br>
          verificación: {{ estado.verificacion}}
          Persona asignada: {{ persona.persona_asignada }}-->
          <!-- inicio devolver documento-->

          <!-- inicio -->
          <div class="col-sm-12" v-if="estado.verificacion == 0">
            <h2>{{ estado.titulo }}</h2>
            <span class="btn btn-danger btn-sm btn-estado" @click="asignar_estado_pedido(8170,2)"><i class="fa fa-times"></i> Anular en Dirección</span>
          </div>
          <!-- fin -->

          <!-- inicio -->
          <div class="col-sm-12" v-if="estado.verificacion == 10">
            <h2>Cotizar</h2>
            <span class="btn btn-info btn-sm btn-estado" @click="asignar_estado_pedido(8148,1)"><i class="fa fa-check"></i> Fase de cotización</span>
            <span class="btn btn-danger btn-sm btn-estado" @click="asignar_estado_pedido(8147,2)"><i class="fa fa-times"></i> Anular</span>
          </div>
          <!-- fin -->
          <!-- inicio -->
          <div class="col-sm-12" v-if="estado.verificacion == 12">
            <br>
            <h2>Aprobar compra</h2>
            <span class="btn btn-info btn-sm btn-estado" @click="asignar_estado_pedido(8146,1)"><i class="fa fa-check"></i> Aprobar compra</span>
            <span class="btn btn-danger btn-sm btn-estado" @click="asignar_estado_pedido(8147,2)"><i class="fa fa-times"></i> Anular</span>
          </div>
          <!-- fin -->
          <!-- inicio -->
          <div class="col-sm-12" v-if="estado.verificacion == 13">
            <br>
            <span class="btn btn-info btn-sm btn-estado" @click="asignar_estado_pedido(8149,1)"><i class="fa fa-check"></i> Producto comprado</span>
            <span class="btn btn-danger btn-sm btn-estado" @click="asignar_estado_pedido(8147,2)"><i class="fa fa-times"></i> Anular</span>
          </div>
          <!-- fin -->
          <!-- fin fase compras -->
        </div>
      </div>
      <!-- fin seguimiento -->
      <!-- inicio bitácora -->
      <div class="col-sm-12" v-show="opcion == 3">
        <table class="table table-sm table-bordered table-striped">
          <thead>
            <th class="text-center" style="width:15px">Tipo</th>
            <th class="text-center" style="width:15px">Operador</th>
            <th class="text-center" style="width:15px">Enlace</th>
            <th class="text-center" style="width:15px">Fecha y hora</th>
            <th class="text-center" style="width:15px">Observaciones</th>


          </thead>
          <tbody>
            <tr v-for='(b, index) in bitacora' :key="index" >
              <td class="text-center">{{b.tipo}}</td>
              <td class="text-center">{{b.operador}}</td>
              <td class="text-center">{{b.enlace}}</td>
              <td class="text-center">{{b.fecha}}</td>
              <td class="text-center">{{b.observaciones}}</td>

              <!--<td class="text-center">{{i.Pedd_can}}</td>-->
            </tr>
          </tbody>
        </table>
      </div>
      <!-- fin bitácora -->
      <!-- inicio facturas -->
      <div class="col-sm-12" v-show="opcion == 4">
        <div class="row">
          <div class="col-sm-12" v-show="sopcion == 1">

            <br>
            <table class="table table-sm table-bordered table-striped">
              <thead>
                <th class=" text-center">No. Orden</th>
                <th class=" text-center">Tipo pago</th>
                <th class=" text-center">Fecha</th>
                <th class=" text-center">Serie</th>
                <th class=" text-center">Número</th>
                <th class=" text-center">Proveedor</th>
                <th class=" text-center">Monto</th>
                <th class=" text-center">NOG</th>
                <th class=" text-center">CUR</th>
                <th class=" text-center">Cheque</th>
                <th class=" text-center">Estado</th>
                <th class=" text-center">Acción</th>
              </thead>
              <tbody>
                <tr v-for='(f, index) in facturas' :key="index" >
                  <td class="text-center">{{ f.nro_orden }}</td>
                  <td class="text-center">{{ f.tipo }}</td>
                  <td class="text-center">{{ f.factura_fecha }}</td>
                  <td class="text-center">{{ f.factura_serie }}</td>
                  <td class="text-center">{{ f.factura_num }}</td>
                  <td class="text-center">{{ f.proveedor }}</td>
                  <td class="text-center">{{ f.factura_total }}</td>
                  <td class="text-center">{{ f.nog }}</td>
                  <td class="text-center">{{ f.cur }}</td>
                  <td class="text-center">{{ f.cheque }}</td>
                  <td class="text-center">{{ f.estado }}</td>
                  <td class="text-center">{{ f.accion }}</td>

                  <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- fin factura -->
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
