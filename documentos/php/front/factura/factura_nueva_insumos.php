<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    $tipo_operacion = $_GET['tipo'];
    $factura_id = (!empty($_GET['arreglo'])) ? $_GET['arreglo'] : NULL;
    $operacion = (!empty($_GET['tipoOpe'])) ? $_GET['tipoOpe'] : NULL;
    /*include_once '../../back/functions.php';
    $p_i = documento::get_pedido_by_pedido_interno(1,date('Y'));
    echo $p_i['Ped_tra'];*/

    //echo $tipo_operacion . ' || '.$factura_id. ' || '.$operacion;
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
  <script src="documentos/js/components/components1.13.js" ></script>
  <script src="documentos/js/facturas/facturanuevav1.6.js" ></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="app_factura_n">
  <div class="modal-header">
    <h3>{{ tituloIngreso }}</h3>
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
    <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>

    <!--{{ privilegio }}
    <br>
    Tipo ingreso: {{ ingresoTipo }}-->


    <div v-show="sopcion == 1">
      <form class="jsValidacionFacturaNueva" id="formValidacionFacturaNueva">
        <input type="text" id="id_tipo_operacion" name="id_tipo_operacion" value="<?php echo $tipo_operacion; ?>" hidden></input>
        <input type="text" id="factura_id" name="factura_id" value="<?php echo $factura_id; ?>" hidden></input>

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
          <combo-change codigo="id_tipo_ingreso" v-if="privilegio.tesoreria_recepcion == true && tipoOperacion == 1" row="col-sm-12" label="Seleccionar tipo de ingreso*" :arreglo="opcionesIngreso" tipo="3" requerido="true"></combo-change>

          <div class="col-sm-3">
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
          <div class="col-sm-3" v-if="ingresoTipo == 1">
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
          <div class="col-sm-3" v-if="ingresoTipo == 1 || ingresoTipo == 3 || ingresoTipo == 4">
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
          <div class="col-sm-3" v-if="tipoOperacion == 1">
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
          <div class="col-sm-12" v-if="tipoOperacion == 1">
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
          <div class="col-sm-3" v-if="tipoOperacion == 1">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_modalidad">Modalida de compra</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_modalidad" name="id_modalidad" @change="validarNog($event)" required>
                      <option value="">-- Seleccionar --</option>
                      <option value="1">Baja Cuantía</option>
                      <option value="2" v-if="privilegio.compras_recepcion == true || privilegio.compras_tecnico == true">Acreditamiento</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--inicio-->
          <div class="col-sm-3" v-if="tipoOperacion == 1">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="password">Contraseña</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" maxlength="50" class="form-control form-control_ input-sm" id="password" name="password" placeholder="@Contraseña" autocomplete="off" min="1"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-3"  v-if="(privilegio.compras_recepcion == true || privilegio.compras_tecnico == true) && tipoOperacion == 1">
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
          <div :class="[(privilegio.compras_recepcion == true || privilegio.compras_tecnico == true) ? 'col-sm-3' : 'col-sm-12']" v-if="(ingresoTipo == 1 || ingresoTipo == 2) && tipoOperacion == 1">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_tipo_proveedor">Régimen tributario*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_regimen" name="id_regimen" @change="opcSimplificado($event)">
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


          <div class="col-sm-12" v-if="mostrarSimplificado == true">
            <div class="row">
              <div class="text-right" class="col-sm-6">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label class="text-white">..</label>
                      <div class=" input-group  has-personalizado" >
                        <label class="css-input switch switch-success"><input class="chequeado" id="pago_directo" v-model="opcionSimplificado" name="pago_opcional" type="radio" value="1" :required="opcionSimplificado == ''" /><span></span> Pago directo</label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div class="text-right" class="col-sm-6">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label class="text-white">..</label>
                      <div class=" input-group  has-personalizado" >
                        <label class="css-input switch switch-success"><input class="chequeado" id="retencion_definitiva" v-model="opcionSimplificado" name="pago_opcional" type="radio" value="2" :required="opcionSimplificado == ''" /><span></span> Sujeto a retención definitiva</label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <hr>
          </div>
          <!-- fin -->
          <renglones-listado columna="col-sm-12" v-if="tipoOperacion == 1"></renglones-listado>


          <!-- inicio insumos -->
          <div class="col-sm-2">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_pedido_num">PYR:</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="number" class="form-control form-control_ input-sm" id="id_pedido_num" name="id_pedido_num" placeholder="@Número de Pedido" value="<?php echo $ped_num;?>" v-bind:disabled="habilitar" autocomplete="off" />
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
                      <th class="text-center" style="width:50px">Insumo</th>
                      <th class="text-center" style="width:75px">Nombre</th>
                      <th class="text-center" style="width:15px">Descripción</th>
                      <th class="text-center" style="width:15px">Med.</th>
                      <th class="text-center" style="width:15px">Requerido</th>

                      <th class="text-center" style="width:15px">Cantidad</th>
                      <th class="text-center" style="width:15px">Precio U.</th>
                      <th class="text-center" style="width:15px">Total</th>
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
                        <!--<td class="text-center"><span v-model="i.v_req">{{i.Pedd_can}}</span></td>-->
                        <td class="text-center"><span>{{i.Pedd_can}}</span></td>
                        <td class="text-center" style="width:60px">
                          <div class="form-group" v-if="i.checked==true" style="margin-bottom:0rem">
                            <div class="">
                              <div class="">
                                <input type="number"  :name="i.id_cant" :id="i.id_cant"  class="form-control input-sm" v-model="i.v_rec" autocomplete="off" min="1" required @change="changeValue"></input>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="text-center" style="width:60px">
                          <div class="form-group" v-if="i.checked==true" style="margin-bottom:0rem">
                            <div class="">
                              <div class="">
                                <input type="number"  :name="i.id_pre" :id="i.id_pre"  class="form-control input-sm" v-model="i.precio_unitario" autocomplete="off" min="1" required @change="changeValue"></input>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="text-center"><span>{{ i.importe }}</span></td>
                        <td class="text-center"><span> {{ i.recibido }}</span></td>
                        <!--<td class="text-center"><span :id="'st'+index" v-model="i.v_fal">{{ computeSubTotal(i) }}</span></td>-->
                        <td class="text-center">
                          <input class="tgl tgl-flip text-center" :id="i.Ppr_id" :name="i.Ppr_id" type="checkbox" v-model="i.checked" @change="clearClass(i,i.checked)"/>
                          <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="i.Ppr_id"></label>
                        </td>
                        <!--<td class="text-center">{{i.Pedd_can}}</td>-->
                      </tr>
                    </tbody>
                  </table>
                </div>
                {{ totalImporte }}
                <div class="col-sm-12 text-right">
                  <button class="btn btn-sm btn-info" @click="agregarFactura()"><i class="fa fa-plus-circle"></i> Guardar</button>

                </div>
                <div class="col-sm-12 text-right">
                  <button class="btn btn-sm btn-info" @click="agregarInsumosFactura($event)"><i class="fa fa-plus-circle"></i> Guardar</button>
                  <span class="btn btn-sm btn-danger" @click=""><i class="fa fa-times-circle"></i> Cancelar</span>

                </div>
              </div>
          <!-- fin insumos -->
        </div>



            <!-- fin factura -->
          </form>

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
