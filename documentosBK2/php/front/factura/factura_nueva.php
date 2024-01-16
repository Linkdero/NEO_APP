<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    /*include_once '../../back/functions.php';
    $p_i = documento::get_pedido_by_pedido_interno(1,date('Y'));
    echo $p_i['Ped_tra'];*/
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
  <script src="documentos/js/components/components1.9.js" ></script>
  <script src="documentos/js/facturas/facturanuevav.js" ></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_factura_n">
  <div class="modal-header">
    <h3>Crear Factura</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <input id="cambio" hidden></input>
    <div v-show="sopcion == 1">
      <form class="jsValidacionFacturaNueva" id="formValidacionFacturaNueva">
        <!-- inicio factura -->
        <div class="row">
          <!--inicio-->
          <!--<div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="fecha_factura_ingreso">Fecha de recibido en compras:*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="date" class="form-control form-control_ input-sm" id="fecha_factura_ingreso" name="fecha_factura_ingreso" placeholder="@fecha de ingreso" required autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
          </div>-->
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="fecha_factura">Fecha:*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="date" class="form-control form-control_ input-sm" id="fecha_factura" name="fecha_factura" placeholder="@fecha de la factura" required autocomplete="off" />
                  </div>
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
                  <label for="factura_serie">Serie:*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="factura_serie" name="factura_serie" placeholder="@Nro. de Serie" required autocomplete="off"/>
                  </div>
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
                  <label for="factura_nro">Número*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="factura_nro" name="factura_nro" placeholder="@Nro. de factura" required autocomplete="off"/>
                  </div>
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
                  <label for="factura_nro">Monto*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="number" maxlength="50" class="form-control form-control_ input-sm" id="factura_monto" name="factura_monto" placeholder="@Monto" required autocomplete="off" min="1"/>
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
                  <label for="id_proveedor">Proveedor</label> <span class="btn btn-sm btn-soft-info badge" @click="getsOpcion(2)"><i class="fa fa-plus"></i></span>
                  <div class=" input-group  has-personalizado" >
                    <select class="proveedor form-control form-control-sm" style="width:100%" id="id_proveedor" name="id_proveedor"></select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <div class="col-sm-12" >
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_modalidad">Modalida de compra</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_modalidad" name="id_modalidad" @change="validarNog($event)" required>
                      <option value="">-- Seleccionar --</option>
                      <option value="1">Baja Cuantía</option>
                      <option value="2">Acreditamiento</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--inicio-->
          <div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_nog">NOG</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="number" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="id_nog" name="id_nog" placeholder="@NOG" autocomplete="off" v-bind:disabled="nogValidacion == false" :required="nogValidacion == true" min="1"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <!--<div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_pedido_interno">Pedido interno</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="number" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="id_pedido_interno" name="id_pedido_interno" placeholder="@Pedido interno" autocomplete="off" min="1"/>
                  </div>
                </div>
              </div>
            </div>
          </div>-->
          <!--inicio-->
          <div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_tipo_proveedor">Régimen tributario*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_regimen" name="id_regimen">
                      <option value="">-- Seleccionar --</option>
                      <option value="1">Pequeño Contribuyente</option>
                      <option value="2">Opcional Simplificado</option>
                      <option value="3">Actividades Lucrativas</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!-- fin -->
          <renglones-listado columna="col-sm-12"></renglones-listado>
          <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-info salida" @click="agregarFactura()"><i class="fa fa-plus-circle"></i> Guardar</button>

          </div>
        </div>
      </form>
    </div>
    <div v-show="sopcion == 2">
      <h2 class="text-info"><i class="fa fa-plus-circle"></i> Agregar Proveedor</h2>
      <form class="jsValidacionProveedorNuevo">
        <div class="row">
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="proveedor_nit">NIT:*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="proveedor_nit" name="proveedor_nit" placeholder="@NIT" required autocomplete="off"/>
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
                  <label for="proveedor_nombre">Razón Social*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="100" class="form-control form-control_ input-sm" id="proveedor_nombre" name="proveedor_nombre" placeholder="@Nombre del Proveedor" required autocomplete="off"/>
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
                  <label for="id_tipo_proveedor">Tipo*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_tipo_proveedor" name="id_tipo_proveedor" required>
                      <option value="">-- Seleccionar --</option>
                      <option value="1">Bien / Insumo</option>
                      <option value="2">Servicio</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-info" @click="guardaProveedor()"><i class="fa fa-plus-circle"></i> Guardar</button>
            <span class="btn btn-sm btn-danger" @click="getsOpcion(1)"><i class="fa fa-times-circle"></i> Cancelar</span>

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
