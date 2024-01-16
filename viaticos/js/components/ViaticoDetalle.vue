<template>
  <div class="row">
    <div class="col-sm-12">
      <!-- inicio -->
      <div class="row">
        <div class="col-sm-6">
          <div class="">
            <label for="destinatarios">Dirección</label>
            <div class="input-group has-personalizado">
              <strong><i class="fa fa-home"></i> {{ viaticos.direccion_solicitante }}</strong>
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
                <strong><i class="fa fa-user-check"></i> {{ viaticos.autorizado_por }}</strong>
              </div>
            </div>
          </div>
        </div>
        <div class="invoice-title">
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-6" v-if="viaticos.status == 932 || viaticos.status == 933">
                  <small class="text-muted">Fecha Salida: </small>
                  <h5 class="f_fecha" style="width:100px" :data-id="viaticos.nombramiento"
                    :data-pk="viaticos.nombramiento" :data-name="viaticos.id_fi">{{ viaticos.fecha_ini }}</h5>
                  <small class="text-muted">Fecha Regreso: </small>
                  <h5 class="f_fecha" style="width:100px" :data-id="viaticos.nombramiento"
                    :data-pk="viaticos.nombramiento" :data-name="viaticos.id_ff">{{ viaticos.fecha_fin }}</h5>
                </div>
                <div class="col-sm-6" v-else>
                  <small class="text-muted">Fecha Salida: </small>
                  <h5>{{ viaticos.fecha_ini }}</h5>
                  <small class="text-muted">Fecha Regreso: </small>
                  <h5>{{ viaticos.fecha_fin }}</h5>
                </div>
                <div class="col-sm-6" v-if="viaticos.status == 932 || viaticos.status == 933">
                  <small class="text-muted p-t-30 db">Hora de Salida</small>
                  <h5 class="horas_" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento"
                    :data-name="viaticos.id_fi">{{ viaticos.hora_ini }}</h5>
                  <small class="text-muted p-t-30 db">Hora de Regreso</small>
                  <h5 class="horas_" style="width:100px" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento"
                    :data-name="viaticos.id_ff">{{ viaticos.hora_fin }}</h5>
                </div>
                <div class="col-sm-6" v-else>
                  <small class="text-muted p-t-30 db">Hora de Salida</small>
                  <h5>{{ viaticos.hora_ini }}</h5>
                  <small class="text-muted p-t-30 db">Hora de Regreso</small>
                  <h5>{{ viaticos.hora_fin }}</h5>
                </div>
                <div class="col-sm-12">
                  <small class="text-muted p-t-30 db">Duración</small>
                  <h5>{{ viaticos.duracion }}</h5>
                </div>
              </div>
            </div>
            <div class="col-sm-3" style="border-left:1.5px dashed #F2F1EF;">
              <div class="">
                <div class="col-sm-12" v-if="viaticos.status == 932 || viaticos.status == 933">
                  <small class="text-muted">Motivo: </small>
                  <h5 class="motivo_" :data-id="viaticos.nombramiento" :data-pk="viaticos.nombramiento">
                    {{ viaticos.motivo }}</h5>
                  <br>
                  <small class="text-muted">Funcionario: </small>
                  <h5>{{ viaticos.funcionario }}</h5>
                </div>
                <div class="col-sm-12" v-else>
                  <small class="text-muted">Motivo: </small>
                  <h5>{{ viaticos.motivo }}</h5>
                  <br>
                  <small class="text-muted">Funcionario: </small>
                  <h5>{{ viaticos.funcionario }}</h5>
                </div>
              </div>
            </div>
            <div class="col-sm-3" style="border-left: 1.5px dashed #F2F1EF;">
              <div class="">
                <div class="col-sm-12">
                  <small class="text-muted">Destino: </small>
                  <h5>{{ viaticos.destino }}</h5>
                  <div v-if="viaticos.confirma_lugar == '1'">
                    <br>
                    <small class="text-muted">Destino Nuevo:</small>
                    <h5 class="text-info">{{ viaticos.historial }}</h5>
                  </div>
                  <div v-if="viaticos.confirma_lugar == '2'">
                    <br>
                    <small class="text-muted">Recorrido de la Comisión:</small>
                    <h5 class="text-info">{{ viaticos.historial }}</h5>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3" style="border-left: 1.5px dashed #F2F1EF;">
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-6">
                    <small class="text-muted">Tipo de Comisión: </small>
                    <div v-if="viaticos.id_pais == 'GT'">
                      <h5 id="pais_tipo">NACIONAL</h5>
                    </div>
                    <div v-else>
                      <h5 id="pais_tipo">EXTERIOR</h5>
                      <div v-if="viaticos.id_grupo == 1058">
                        <h5>GRUPO 1</h5>
                      </div>
                      <div v-else-if="viaticos.id_grupo == 1059">
                        <h5>GRUPO 2</h5>
                      </div>
                      <div v-else-if="viaticos.id_grupo == 1060">
                        <h5>GRUPO 3</h5>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <small class="text-muted">Beneficios: </small>
                    <h5>{{ viaticos.hospedaje }}</h5>
                    <h5>{{ viaticos.alimentacion }}</h5>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 text-right" style="padding-top:5rem">
                {{ viaticos.status }} - {{ viaticos.estado }}
              </div>
            </div>
          </div>
          <hr>
          <h3 class="panel-title"><strong></strong></h3>
          <div class="table-responsive">
            <div v-if="privilegio.director == true && viaticos.status == 932">
              <!--NO HA SIDO AUTORIZADO-->
              <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6 text-right">
                  <button id="" class="btn btn-info btn-sm" @click="seguimientoViatico(933)"><i class="fa fa-check"></i>
                    Autorizar </button>
                  <button id="" class="btn btn-danger btn-sm" @click="seguimientoViatico(934)"><i class="fa fa-times"></i>
                    Anular </button>
                </div>
              </div>
            </div>
            <div v-else-if="viaticos.status == 939">
              Se tiene que liquidar
            </div>
            <div v-else-if="viaticos.status == 940 || viaticos.status == 1635 || viaticos.status == 1643">
              <div class="row">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-2 text-right">
                  <small class="text-muted p-t-30 db">Personas liquidadas</small>
                  <h4>{{ totalesLiquidados.personas }}</h4>
                </div>
                <div class="col-sm-2 text-right">
                  <small class="text-muted p-t-30 db">Complemento</small>
                  <h4>Q {{ totalesLiquidados.complemento }}</h4>
                </div>
                <div class="col-sm-2 text-right">
                  <small class="text-muted p-t-30 db">Reintegro</small>
                  <h4>Q {{ totalesLiquidados.reintegro }}</h4>
                </div>
                <div class="col-sm-2 text-right">
                  <small class="text-muted p-t-30 db">Total Liquidado</small>
                  <h4>Q {{ totalesLiquidados.total_liquidado }}</h4>
                </div>
              </div>
            </div>
            <div v-if="viaticos.status == 932 || viaticos.status == 933"> <!-- Tiene que ser 933-->


              <div class="row"
                v-if="privilegio.cheque == true || privilegio.efectivo == true || privilegio.calculo == true">
                <div class="col-sm-2">
                  <input class="btn btn-soft-info btn-sm btn-block" type='button' @click='calcularViaticos()'
                    value='Calcular monto'></input>
                </div>
                <div class="col-sm-10">
                  <div v-if="viaticos.emitir_cheque == 1">
                    Se calculará para que emita Cheque.
                  </div>
                  <div v-else-if="viaticos.emitir_cheque == 2">
                    Se calculará para emitir Efectivo.
                  </div>
                  <div v-else="viaticos.emitir_cheque == 3 && viaticos.status == 933">
                    <select class="form-control form-control-sm" style="width:15%" @change="cambiarEmision($event)">
                      <option value="1">Cheque</option>
                      <option value="2">Efectivo</option>
                    </select>
                  </div>
                  <br>
                </div>
                <div class="col-sm-12">
                  <form class="jsValidacionProcesarNombramiento">
                    <div class="row">
                      <div class="col-sm-12">

                        <table id="procesaFormularioAccion" class="table table-sm table-bordered table-striped"
                          width="100%">
                          <thead>
                            <th class="text-center">Renglon</th>
                            <th class="text-center">Empleado</th>
                            <th class="text-center">Sueldo</th>
                            <th class="text-center">Porcentaje</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-center">Monto</th>
                            <th class="text-center" v-if="viaticos.status == 933">Cheque</th>
                            <th class="text-center" width="120px">Anticipo
                              <div class="custom-control custom-checkbox text-center"
                                style="position:absolute;margin-top:-1rem">
                                <input id="id_calculos" class="custom-control-input" type="checkbox" @click="toggleSelect"
                                  :checked="selectAll" checked>
                                <label class="custom-control-label" for='id_calculos'></label>
                              </div>
                            </th>
                          </thead>
                          <tbody>
                            <tr v-for="c in calculos" :id="c.id">
                              <td class="text-center">{{ c.reng_num }}</td>
                              <td class="text-center"><span v-if="c.cheque == 1" class="stado_success"
                                  style="margin-left:-13px"></span> {{ c.empleado }} <span v-if="c.verificar == true"
                                  class="stado_danger" style="margin-left:5px"></span></td>
                              <td class="text-right">{{ c.sueldo }}</td>
                              <td class="text-center">{{ c.porcentaje }}</td>
                              <td class="text-center">{{ c.moneda }}</td>
                              <td class="text-right" :data-id="c.cuota_diaria">{{ c.cuota_diaria }}</td>
                              <td class="text-right" v-if="viaticos.status == 933" width="180px">
                                <div class="form-group" v-if="c.checked == true && emitirViatico == 1"
                                  style="margin-bottom:0rem">
                                  <div class="">
                                    <div class="">
                                      <input type="number" :name="c.id_cheque" :id="c.id_cheque"
                                        class="form-control input-sm" autocomplete="off" required></input>

                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td class="text-center" width="10px"><input class="tgl tgl-flip text-center"
                                  :id="c.id_anticipo" :name="c.id_anticipo"
                                  v-on:change="subTotal(c.id, c.cuota_diaria, 1)" type="checkbox" v-model="c.checked"
                                  checked />
                                <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center"
                                  data-tg-on="SI" data-tg-off="No" :for="c.id_anticipo"></label>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-12 text-right">
                        <div class="row">
                          <div class="col-sm-6">
                            <div v-if="viaticos.id_pais == 'GT'">
                            </div>
                            <div v-else>
                              <input type="number" id="tasa_cambiaria" class="form-control input-sm form-corto text-right"
                                onkeyup="conversion()" placeholder="Tipo de Cambio"></input>
                            </div>
                          </div>
                          <div class="col-sm-3">

                            <h3 v-if="viaticos.id_pais != 'GT'">Conversión: <strong><span v-if="viaticos.id_pais != 'GT'"
                                  class="text-right" id="conversion"></span></strong></h3>
                          </div>

                          <div class="col-sm-3">
                            <h3>Subtotal: <strong><span class="text-right" id="sub_total"></span></strong></h3>
                          </div>
                        </div>
                        <div v-if="privilegio.cheque == true || privilegio.efectivo == true">
                          <button v-if="viaticos.status == 933" id="" class="btn btn-info btn-sm"
                            @click="procesarViatico(935, $event)"><i class="fa fa-check"></i> Procesar </button>
                          <span v-if="viaticos.status == 933" id="" class="btn btn-danger btn-sm"
                            @click="seguimientoViatico(1635, $event)"><i class="fa fa-times"></i> Anular </span>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div v-else-if="viaticos.status == 934">
            Anulado en dirección
          </div>
          <div v-else-if="viaticos.status == 935 && privilegio.cheque == true">
            Procesado
            <div class="row">
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-info btn-sm" @nclick="elaborarCheque(0)"><i class="fa fa-check"></i> Elaborar
                  Sin Cheque </button>
                <button id="" class="btn btn-info btn-sm" @click="elaborarCheque(1)"><i class="fa fa-check"></i> Elaborar
                  con Cheque </button>
                <button id="" class="btn btn-danger btn-sm" @click="seguimientoViatico(1636)"><i class="fa fa-times"></i>
                  Anular </button>
              </div>
            </div>
          </div>
          <div v-else-if="viaticos.status == 936 && privilegio.cheque == true">
            Procesado
            <h3>Personas autorizadas: <strong><span class="text-right" id="personas">{{ viaticos.personas
            }}</span></strong>
            </h3>
            <div class="row">
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-info btn-sm" @click="seguimientoViatico(938)"><i class="fa fa-check"></i>
                  Entregar Cheque </button>
                <button id="" class="btn btn-danger btn-sm" @click="seguimientoViatico(1643)"><i class="fa fa-times"></i>
                  Anular </button>
              </div>
            </div>
          </div>
          <div
            v-else-if="(viaticos.status == 7959 || viaticos.status == 938 || viaticos.status == 8194) && privilegio.cheque == true">
            Procesado
            <h3>Personas autorizadas: <strong><span class="text-right" id="personas">{{ viaticos.personas
            }}</span></strong>
            </h3>
            <div class="row">
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-danger btn-sm" @click="seguimientoViatico(7972)"><i class="fa fa-times"></i>
                  Anular </button>
              </div>
            </div>
          </div>
          <div v-if="viaticos.status == 8193 && privilegio.efectivo == true">
            Procesado
            <h3>Personas autorizadas: <strong><span class="text-right" id="personas">{{ viaticos.personas
            }}</span></strong>
            </h3>
            <div class="row">
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-info btn-sm" @click="seguimientoViatico(8194)"><i class="fa fa-check"></i>
                  Entregar Efectivo </button>
                <button id="" class="btn btn-danger btn-sm" @click="seguimientoViatico(1643)"><i class="fa fa-times"></i>
                  Anular </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
module.exports = {
  props: ["id_viatico", "privilegio", "estado_nombramiento", "evento"],
  data: function () {
    return {
      viaticos: "",
      isLoaded: false,
      calculos: [],
      emitirViatico: 0,
      cambio: 0,
      horas: "",
      totalesLiquidados: 0
      //subtotal:0
    }
  },
  mounted() {

  },
  created: function () {
    this.getViatico();
    setTimeout(() => {
      if (this.estado_nombramiento.status == 932 || this.estado_nombramiento.status == 933) {
        this.getLoadEditTable();
      }

      if (this.estado_nombramiento.status == 940) {
        this.getTotalesLiquidados();
      }

    }, 400);

    this.evento.$on('recargarViatico', () => {
      this.getViatico();
    });
    this.evento.$on('seguimientoViatico', (data) => {
      this.seguimientoViatico(data);
    });
  },
  methods: {
    getViatico: function () {
      axios.get('viaticos/php/back/viatico/ajaxfile.php', {
        params: {
          vt_nombramiento: this.id_viatico
        }
      })
        .then(function (response) {
          this.viaticos = response.data;
          this.$emit('sendviatico', this.viaticos)
          localStorage.setItem('viaticoD', this.viaticos);
          setTimeout(() => {
            if (response.data.emitir_cheque == 3) {
              this.emitirViatico = 1;
            }
            else {
              this.emitirViatico = response.data.emitir_cheque;
            }
            this.isLoaded = true;
          }, 0);
        }.bind(this))
        .catch(function (error) {
          console.log(error);
        });

    },
    calcularViaticos: function () {
      axios.get('viaticos/php/back/viatico/calcular_viaticos_by_nombramiento.php', {
        params: {
          vt_nombramiento: this.id_viatico
        }
      }).then(function (response) {
        this.calculos = response.data;
        total = 0;
        $.each(response.data, function (pos, elemento) {
          total += parseFloat(elemento.cuota_diaria);
        })
        $('#sub_total').text(total + '.00');
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    subTotal: function (id, valor, tipo) {
      var subtotal = 0;
      var total_col2 = 0;
      if (tipo == 1) {
        total_col2 = parseFloat($('#sub_total').text());
        var total = 0;
        if ($('#ac' + id).is(':checked')) {
          total_col2 += parseFloat(valor);
        } else {
          total_col2 -= parseFloat(valor);
        }
      } else
        if (tipo == 2) {
          total_col2 = 0;
          this.calculos.forEach(function (c) {
            if (c.checked) {
              total_col2 += parseFloat(c.cuota_diaria);
            } else {
              total_col2 = 0;
            }

          });
        }
      $('#sub_total').text(total_col2 + '.00');
    },
    toggleSelect: function () {
      var select = this.selectAll;
      this.calculos.forEach(function (c) {
        c.checked = !select;

      });
      this.subTotal(1, 1, 2);
      this.selectAll = !select;
    },
    selectAll: function () {
    },
    seguimientoViatico: function (estado) {
      var thisInstance = this;
      var mensaje, color, validacion, mensaje_success;
      if (estado == 933) {
        //Autorizado
        mensaje = '¿Quiere autorizar este nombramiento';
        color = '#28a745';
        validacion = '¡Si, Autorizar!';
        mensaje_success = 'Nombramiento Autorizado';
      } else if (estado == 934) {
        //Anulado en dirección
        mensaje = '¿Quiere anular este nombramiento';
        color = '#d33';
        validacion = '¡Si, Anular!';
        mensaje_success = 'Nombramiento Anulado';
      } else if (estado == 935) {
        //procesado
        mensaje = '¿Quiere procesar este nombramiento';
        color = '#28a745';
        validacion = '¡Si, Procesar!';
        mensaje_success = 'Nombramiento Procesado';
      } else if (estado == 1635) {
        //procesado
        mensaje = '¿Quiere anular este nombramiento';
        color = '#d33';
        validacion = '¡Si, Anular!';
        mensaje_success = 'Nombramiento Anulado en cálculo';
      } else if (estado == 936) {
        //procesado
        mensaje = '¿Quiere elaborar el cheque a este nombramiento';
        color = '#28a745';
        validacion = '¡Si, elaborar!';
        mensaje_success = 'Cheque elaborado';
      } else if (estado == 1636) {
        //procesado
        mensaje = '¿Quiere anular la impresión del cheque?';
        color = '#d33';
        validacion = '¡Si, Anular!';
        mensaje_success = '¡Impresión anulada!';
      } else if (estado == 938) {
        //procesado
        mensaje = '¿Quiere entregar este cheque?';
        color = '#28a745';
        validacion = '¡Si, entregar!';
        mensaje_success = 'Cheque entregado';
      } else if (estado == 939) {
        //procesado
        mensaje = '¿Quiere generar constancia de los empleados?';
        color = '#28a745';
        validacion = '¡Si, generar!';
        mensaje_success = 'Constancia generada';
      } else if (estado == 940) {
        //procesado
        mensaje = '¿Quiere liquidar el nombramiento?';
        color = '#28a745';
        validacion = '¡Si, liquidar!';
        mensaje_success = 'Nombramiento liquidado';
      }
      else if (estado == 1643) {
        //procesado
        mensaje = '¿Quiere anular el cheque impreso?';
        color = '#d33';
        validacion = '¡Si, Anular!';
        mensaje_success = 'Cheque anulado';
      } else if (estado == 7972) {
        mensaje = '¿Quiere anular el nombraiento?';
        color = '#d33';
        validacion = '¡Si, Anular!';
        mensaje_success = 'Nombramiento anulado';
      } else if (estado == 8194) {
        mensaje = '¿Quiere entregar el efectivo?';
        color = '#28a745';
        validacion = '¡Si, Entregar!';
        mensaje_success = 'Entregar efectivo';
      }
      cambio = 1;
      Swal.fire({
        title: '<strong></strong>',
        text: mensaje,
        type: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: color,
        cancelButtonText: 'Cancelar',
        confirmButtonText: validacion
      }).then((result) => {
        if (result.value) {
          this.evento.$emit('recargarEstado');
          this.evento.$emit('recargarViaticosTable');
          $.ajax({
            type: "POST",
            url: "viaticos/php/back/viatico/au_nombramiento.php",
            //dataType: 'html',
            data: {
              vt_nombramiento: this.id_viatico,
              estado: estado,
              cambio: cambio
            },
            success: function (data) {
              //$("#aldea").html(data);
              console.log(data);
              //$('#empleados_asignados_datos').html(data)
              if (estado == 933 || estado == 934 || estado == 935 || estado == 1635 || estado == 936 || estado == 163 || estado == 938 || estado == 1643 || estado == 7972 || estado == 8194) {
                thisInstance.getViatico();
                thisInstance.evento.$emit('recargarEstado');
                thisInstance.evento.$emit('recargarViaticosTable');
              } else if (estado == 939 || estado == 940) {
                thisInstance.getViatico();
                thisInstance.evento.$emit('recargarEstado');
                thisInstance.evento.$emit('recargarViaticosTable');
                if (estado == 940) {
                  $('#modal-remoto-lgg2').modal('hide');
                }
                //viewModelViaticoDetalle.recargarTableEmps();
                //viewModelViaticoDetalle.getOpcion(2);
                //recargar_nombramientos($('#id_filtro_detalle').val());
              }
              this.cambio = 1;
              Swal.fire({
                type: 'success',
                title: mensaje_success,
                showConfirmButton: false,
                timer: 2000
              });

            }
          });
        }

      });
    },
    procesarViatico: function (estado, event) {
      formulario = $('#id_viatico').val();
      var nFilas_total = $("#procesaFormularioAccion tbody tr").length;
      console.log('nivel 1');
      if (nFilas_total > 0) {
        if ($('#pais_tipo').text() == 'Extranjero' || $('#pais_tipo').text() == 'EXTERIOR') {
          if ($('#tasa_cambiaria').val() != '' && $('#tasa_cambiaria').val() > 0) {
            console.log('nivel 2');
            var cambio = $('#tasa_cambiaria').val();
            this.procesaFormularioAccion(formulario, estado, cambio)

          } else {
            console.log('nivel 3');
            event.preventDefault();
            Swal.fire({
              type: 'error',
              title: '¡Debe ingresar correctamente el tipo de cambio!',
              showConfirmButton: false,
              timer: 2000
            });

          }
        } else {
          if ($('#sub_total').text() == 0) {
            console.log('nivel 5');
            estado = 7959;
            event.preventDefault();
            this.generarNombramiento(formulario, estado, cambio);

          } else {
            console.log('nivel 6');
            this.procesaFormularioAccion(formulario, estado, 1);
          }
        }
      }
      else {
        event.preventDefault();
        Swal.fire({
          type: 'error',
          title: '¡Debe validar los montos a asignar!',
          showConfirmButton: false,
          timer: 2000
        });
      }
    },
    procesaFormularioAccion: function (formulario, estado, cambio) {
      var thisInstance = this;
      console.log(estado);
      jQuery('.jsValidacionProcesarNombramiento').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          console.log(estado + 'jajajajaj11');
          thisInstance.generarNombramiento(formulario, estado, cambio);
        }
      });
    },
    generarNombramiento: function (formulario, estado, cambio) {
      var thisInstance = this;
      console.log(estado + 'jajajajaj');
      Swal.fire({
        title: '<strong></strong>',
        text: '¿Desea procesar este nombramiento?',
        type: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Procesar Nombramiento'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "viaticos/php/back/viatico/au_nombramiento.php",
            data: {
              vt_nombramiento: formulario,
              estado: estado,
              cambio: cambio
            },
            success: function (data) {
              thisInstance.cambio = 1;
              console.log(estado + 'aldflñajdñflkj');
              $("#procesaFormularioAccion tbody tr").each(function (index, element) {
                id_row = ($(this).attr('id'));
                porcentaje = $(element).find("td").eq(3).html();
                monto = $(element).find("td").eq(5).html();
                var cheque = 0;
                anticipo = 0;
                if ($('#ac' + id_row).prop('checked')) {
                  anticipo = 1;
                  cheque = $('#ch' + id_row).val();
                }
                $.ajax({
                  type: "POST",
                  url: "viaticos/php/back/viatico/procesar_nombramiento_detalle.php",
                  data:
                  {
                    vt_nombramiento: formulario,
                    reng_num: id_row,
                    porcentaje: porcentaje,
                    monto: monto,
                    anticipo: anticipo,
                    cheque: cheque
                  },
                  beforeSend: function () {
                    $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                  },
                  success: function (data) {

                  }
                }).done(function () {

                }).fail(function (jqXHR, textSttus, errorThrown) {

                });
              });
              thisInstance.getViatico();
              thisInstance.evento.$emit('recargarEstado');
              thisInstance.evento.$emit('recargarViaticosTable');
              Swal.fire({
                type: 'success',
                title: '¡Nombramiento procesado!',
                showConfirmButton: false, timer: 2000
              });
            }
          });
        }
      });
    },
    elaborarCheque: function (tipo) {
      if (tipo == 0) {
        Swal.fire({
          title: '<strong>¿Quiere generar sin cheque?</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Generar sin cheque!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              url: "viaticos/php/back/viatico/cheque_nombramiento.php",
              //dataType: 'html',
              data: {
                vt_nombramiento: this.id_viatico,
                estado: 7959,
                cheque: 0,
                tipo: tipo
              },
              success: function (data) {
                this.cambio = 1;
                this.getViatico();
                this.evento.$emit('recargarEstado');
                this.evento.$emit('recargarViaticosTable');
                if (data != 'ok') {
                  Swal.fire({
                    type: 'error',
                    title: data,
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
                else {
                  Swal.fire({
                    type: 'success',
                    title: '¡Guardado sin cheque!',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
              }
            });
          }
        })
      } else if (tipo == 1) {
        var form = '';

        Swal.fire({
          title: '<strong>¿Quiere elaborar el cheque a este nombramiento?</strong>',
          text: "",
          type: 'question',
          //input: 'number',
          //inputPlaceholder: 'Agregar',
          showLoaderOnConfirm: true,
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Elaborar!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              url: "viaticos/php/back/viatico/cheque_nombramiento.php",
              //dataType: 'html',
              data: {
                vt_nombramiento: this.id_viatico,
                estado: 936,
                cheque: form,
                tipo: tipo
              },
              success: function (data) {
                this.getViatico();
                this.evento.$emit('recargarEstado');
                this.evento.$emit('recargarViaticosTable');
                this.cambio = 1;
                if (data != 'ok') {
                  Swal.fire({
                    type: 'error',
                    title: data,
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
                else {
                  Swal.fire({
                    type: 'success',
                    title: '¡Cheque elaborado!',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
              }
            });
          }
        })
      }
    },
    getLoadEditTable: function () {
      var x = 1;
      axios.get('viaticos/php/back/listados/get_horas_editable', {
        params: {
          tipo: 37
        }
      }).then(function (response) {
        this.horas = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
      setTimeout(() => {
        if (x == 1) {

          $('.f_fecha').editable({
            url: 'viaticos/php/back/viatico/update_fecha_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function (value, response) {
              return false;   //disable this method
            },
            success: function (response, newValue) {
              if (response.msg == 'Done') {
                $(this).text(response.valor_nuevo);
                viewModelViaticoDetalle.getViatico();
                viewModelViaticoDetalle.calcular_viaticos();
              }
            }
          });
          $('.horas_').editable({
            url: 'viaticos/php/back/viatico/update_hora_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'select',
            source: this.horas,
            display: function (value, response) {
              return false;   //disable this method
            },
            success: function (response, newValue) {
              if (response.msg == 'Done') {
                $(this).text(response.valor_nuevo);
                viewModelViaticoDetalle.getViatico();
                viewModelViaticoDetalle.calcular_viaticos();

              }
            }
          });

          $('.motivo_').editable({
            url: 'viaticos/php/back/viatico/update_motivo.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'textarea',
            display: function (value, response) {
              return false;   //disable this method
            },
            success: function (response, newValue) {
              if (response.msg == 'Done') {
                $(this).text(response.valor_nuevo);

              }
            }
          });
        }


      }, 2100);

    },
    cambiarEmision: function (event) {
      this.emitirViatico = event.currentTarget.value;
    },
    getTotalesLiquidados: function () {
      axios.get('viaticos/php/back/viatico/get_total_liquidado.php', {
        params: {
          vt_nombramiento: this.id_viatico
        }
      })
        .then(function (response) {
          this.totalesLiquidados = response.data;
        }.bind(this))
        .catch(function (error) {
          console.log(error);
        });
    },

    //fin metodos
  }

}
</script>
