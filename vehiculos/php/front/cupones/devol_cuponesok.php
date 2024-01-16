<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

    $id_documento = $_GET["id_documento"];

    $tipo = new vehiculos();
    $dataenc = $tipo::get_devolCuponesEnc($id_documento);
?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="vehiculos/js/validaciones.js"></script>

      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/cupones_detalle_vue.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

      <script>

      </script>

    </head>

    <body>

      <div class="modal-header">
        <h5 class="modal-title">Entrega y devolucion de cupones</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>


      <div id="app_cupones_detalle" class="modal-body">

        <form class="validation_cupones_detalle" name="miForm">

          <input id="id_documento" value="<?php echo $id_documento ?>" hidden></input>
          <div class="row">
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-3">
                  <small class="text-muted">Correlativo </small>
                  <h5><strong>{{ documento.id_documento }}</strong></h5>
                </div>
                <div class="col-sm-3">
                  <small class="text-muted">Fecha </small>
                  <h5><strong>{{ documento.fecha }}</strong></h5>
                </div>
                <div class="col-sm-6">
                  <small class="text-muted">Estado </small>
                  <h5><strong>{{ documento.estado }}</strong></h5>
                </div>

                <div class="col-sm-3">
                  <small class="text-muted">Documento </small>
                  <h5><strong>{{ documento.nro_documento }}</strong></h5>
                </div>
                <div class="col-sm-3">
                  <small class="text-muted"> </small>
                  <h5><strong></strong></h5>
                </div>
                <div class="col-sm-6">
                  <small class="text-muted">Autorizo </small>
                  <h5><strong>{{ documento.auto }}</strong></h5>
                </div>

                <div class="col-sm-3">
                  <small class="text-muted">Total Q. </small>
                  <h5><strong>{{ documento.total }}</strong></h5>
                </div>
                <div class="col-sm-3">
                  <small class="text-muted">Devuelto Q. </small>
                  <h5><strong>{{ documento.devuelto }}</strong></h5>
                </div>
                <div class="col-sm-6">
                  <small class="text-muted">Recibe </small>
                  <h5><strong>{{ documento.recibe }}</strong></h5>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <small class="text-muted">Observaciones </small>
                  <h5><strong>{{ documento.observa }}</strong></h5>
                </div>
              </div>

              <div class="row">

              </div>

              <div class="col-sm-4" text-right>
                <button type="submit" class="btn btn-sm btn-info" @click="procesaCupon()"><i class="fa fa-ticket-alt"></i> Procesar</button>
              </div>


            </div>

            <!-- <div class="col-sm-12 slide_up_anim" v-if="cch1 >= 1 && opcion == 1">
        <span class="btn btn-info btn-sm btn-estado"   @click="setOpcion(2)"><i class="fa fa-check"></i> Procesar</span>
      </div> -->

            <!-- <div class="col-sm-12 text-right">
        <button type="submit" class="btn btn-sm btn-info" @click="procesaCupon()"><i class="fa fa-ticket-alt"></i> Procesar</button>
      </div> -->



            <hr><br>




            <!-- inicio detalle -->
            <div class="col-sm-12" v-show="opcion == 1">
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-sm table-bordered table-striped">
                    <thead>
                      <th class="text-center" style="width:15px">Cupon</th>
                      <th class="text-center" style="width:15px">Monto Q.</th>
                      <th class="text-center" style="width:100px">Usado</th>
                      <th class="text-center" style="width:100px">Dev.</th>
                      <th class="text-center" style="width:15px">Usado en</th>
                      <th class="text-center" style="width:15px">Nombre</th>
                      <th class="text-center" style="width:15px">Placa</th>
                      <th class="text-center" style="width:15px">Km</th>
                    </thead>
                    <tbody>
                      <tr v-for="(c, index) in cupones">
                        <td class="text-center">{{c.cupon}}</td>
                        <td class="text-center">{{ c.monto }}</td>
                        <td class="text-center">
                          <input class="tgl tgl-flip text-center" :id="c.cupon1" type="radio" v-model="c.radio" :value="1" v-bind:disabled="documento.id_estado" @change="marcarVarios(1)" />
                          <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon1"></label>
                        </td>
                        <td class="text-center">
                          <input class="tgl tgl-flip text-center" :id="c.cupon2" type="radio" v-model="c.radio" :value="2" v-bind:disabled="documento.id_estado" @change="marcarVarios(2)" />
                          <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon2"></label>
                        </td>
                        <td class="text-center">{{c.usadoen}}</td>
                        <td class="text-center">{{c.nombre}}</td>
                        <td class="text-center">{{c.placa}}</td>
                        <td class="text-center">{{c.km}}</td>


                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
            <div class="col-sm-12 slide_up_anim" v-if="cch1 >= 1 && opcion == 1">
              <span class="btn btn-info btn-sm btn-estado" @click="setOpcion(2)"><i class="fa fa-check"></i> Asignar</span>
            </div>
            <!-- fin detalle -->
            <div class="col-sm-12" v-show="opcion == 2">

              <hr>
              <div class="row">
                <formulario-cupones tipo="1"></formulario-cupones>
              </div>

              Asignación
              <form class="jsValidationAsignarCupon">


                <!-- inicio campos -->

                <div class="row">

                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <label for="id_destino">Uso del cupon</label>
                        <select class="form-control form-control-sm" id="id_destino" name="id_destino" style="width:100%">
                          <!-- <option v-for="d in destino" v-bind:value="d.id_desti" >{{ d.destino_str }}</option> -->
                          <option value="">- Seleccionar -</option>
                          <option value="1144">VEHICULOS</option>
                          <option value="1147">VEHICULO ARRENDADO</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4" id="div_vehiculo">
                    <div class="form-group">
                      <div class="">
                        <label for="lbl_vehiculo">Placa</label>
                        <select class="form-control form-control-sm" id="id_vehiculo" name="id_vehiculo" v-on:change="getTipoCombustible()" style="width:100%">
                          <option v-for="p in placas" v-bind:value="p.id_item">{{ p.item_string }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <label for="id_refer">Personal / Referencia</label>
                        <select class="form-control form-control-sm" id="id_refer" name="id_refer" style="width:100%">
                          <option v-for="r in refer" v-bind:value="r.id_refer">{{ r.refer_str }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- *** DEPARTAMENTO Y MUNICIPIO *** -->
                <div class="row">

                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <label for="km_actual" class="col-form-label-md">Kilometraje actual</label>
                        <input type="number" class="form-control form-control-sm" id="km_actual" name="km_actual"></input>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <label for="id_departamento">Departamento</label>
                        <select class="form-control form-control-sm" id="id_departamento" name="id_departamento" style="width:100%">
                          <option v-for="dep in departamento" v-bind:value="dep.id_depto">{{ dep.depto_str }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <label for="id_municipio">Municipio</label>
                        <select class="form-control form-control-sm" id="id_municipio" name="id_municipio" style="width:100%">
                          <option v-for="mun in municipio" v-bind:value="mun.id_muni">{{ mun.muni_str }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="row">
                        <label for="id_observa">Observaciones</label>
                        <div class=" input-group  has-personalizado">
                          <textarea type="text" rows='3' class=" form-control " id="id_observa" name="id_observa" placeholder="Observaciones" autocomplete="off"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- fin campos -->
                <div class="col-sm-12 text-right">
                  <button type="submit" class="btn btn-sm btn-info" @click="guardaCupon()"><i class="fa fa-ticket-alt"></i> Despachar</button>
                  <span type="submit" class="btn btn-sm btn-danger" @click="setOpcion(1)"><i class="fa fa-times"></i> Cancelar</span>
                </div>
            </div>
        </form>
      </div>

      </form>

      </div>
    </body>
<?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>