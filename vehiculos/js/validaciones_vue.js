var Instancia;

Vue.component("conductores", {
  props: ["row", "label", "codigo"],
  template: `
        <combo :row="row" :label="label" :codigo="codigo" :arreglo="conductores" tipo="2" requerido="true"></combo>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      conductores: []
    }
  },
  mounted: function () {

  },
  methods: {
    getConductores: function () {
      axios.get('vehiculos/php/back/listados/get_conductores.php', {
        params: {
          //   id_persona: this.id_persona
        }
      }).then(function (response) {
        this.conductores = response.data;
        var codigo = this.codigo;
        setTimeout(() => {
          $("#" + codigo).select2({

          });

        }, 400);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function () {
    this.getConductores();
  }
});

//componente bomba
Vue.component("bomba", {
  props: ["row", "valor", "codigo"],
  template: `
        <combo :row="row" label="Bomba de despacho" :codigo="codigo" :arreglo="bombas" tipo="2" requerido="true"></combo>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      bombas: []
    }
  },
  mounted: function () {

  },
  methods: {
    getBombas: function () {
      setTimeout(() => {
        axios.get('vehiculos/php/back/listados/get_bomba', {
          params: {
            id_tipo: this.valor
          }
        }).then(function (response) {
          this.bombas = response.data;

        }.bind(this)).catch(function (error) {
          console.log(error);
        });

      }, 2000);

    }
  },
  created: function () {
    this.getBombas();
  }
});

//componente detalle vale
Vue.component("detalle-vale", {
  props: ["nro_vale"],
  template: `
        <div class="row" >

          <div class="col-sm-6" >
            <dato-persona icono="fa fa-home" texto="Numero de vale" :dato="detalleVale.nro_vale"></dato-persona>
            <dato-persona icono="fa fa-home" texto="Uso del combustible" :dato="detalleVale.uso"></dato-persona>
            <dato-persona icono="fa fa-home" texto="Galones autorizados" :dato="detalleVale.cant_autor"></dato-persona>

            

          </div>

          <div class="col-sm-6" >
            
            <dato-persona icono="fa fa-home" texto="Numero de placa" :dato="detalleVale.nro_placa"></dato-persona>
            <dato-persona icono="fa fa-home" texto="Tipo de combustible" :dato="detalleVale.tipo_comb"></dato-persona>
            <dato-persona icono="fa fa-home" texto="Conductor" :dato="detalleVale.recibe"></dato-persona>
          </div>
          <div class="col-sm-12">
          <hr>
            <div class="form-group">
              <label for="">Tanque Lleno</label>
              <div class="input-group  has-personalizado">
                <label class="css-input switch switch-success">
                  <input name="chk_Tanque_" id="chk_Tanque_" data-id="" v-on:change="validarCheck()" disabled v-model="detalleVale.tlleno" data-name="" type="checkbox" false/><span></span>
                </label>
              </div>
            </div>
          </div>
          <hr>
        </div>

    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      detalleVale: "",
      verificarCapacidad: false
    }
  },
  mounted: function () {

  },
  methods: {
    getDetalleVale: function () {
      axios.get('vehiculos/php/back/vales/get_vale_by_id', {
        params: {
          nro_vale: this.nro_vale
        }
      }).then(function (response) {
        this.detalleVale = response.data;
        this.$emit('enviar_vale', response.data);

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function () {
    this.getDetalleVale();
  }
});


Vue.component("formulario-vales", {
  props: ["tipo", "id_vale", "data_vale"],
  template: `
        <form class="jsValidacionValeComubistible" id="formValidacionValeCombustible">
          <input id="tipo_accion" name="tipo_accion":value="tipo" type="text" hidden ></input>
          <input id="cant_autor" name="cant_autor":value="cantidadAutorizada" type="text" hidden></input>
          <input id="id_tipo_combustible" name="id_tipo_combustible":value="tipoCombustible" type="text" hidden ></input>
          <input id="nro_vale" name="nro_vale" :value="id_vale" type="text" hidden></input>

          <div class="row" v-if="tipo == 1">
            <combo-change row="col-sm-6" label="Destino Combustible" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true" :valor="valeDetalle.id_"></combo-change>
            <combo v-if="idDestino == 1144 || idDestino == 1147" row="col-sm-6" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" ></combo>
            <campo v-else row="col-sm-6" label="Características" codigo="txt_caracter_" tipo="text" ></campo>

            <campo row="col-sm-4" label="Galones autorizados" codigo="id_galones_" requerido="true" tipo="number" :valor="valorGalones"></campo>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="">Tanque Lleno</label>
                <div class="input-group  has-personalizado">
                  <label class="css-input switch switch-success">
                    <input name="chk_Tanque_" id="chk_Tanque_"  v-on:change="validarCheck()" v-model="verificarCapacidad" data-name="" type="checkbox" false/>
                    <span></span>
                  </label>
                </div>
              </div>
            </div>
            <combo row="col-sm-6" label="Tipo de combustible" codigo="id_combustible" :arreglo="tipos" tipo="2" requerido="true"></combo>
            <conductores row="col-sm-12" label="Persona recibe combustible" codigo="id_conductor_"></conductores>
            <campo row="col-sm-12" label="Observaciones" codigo="observa" tipo="textarea"></campo>
            <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>

          </div>
          <div class="row" v-else-if="tipo == 2">
            <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-danger text-right" @click="generarVale()"><i class="fa fa-times-circle"></i> Anular Vale</button>
            </div>
            
          </div>
          <div class="row" v-else-if="tipo == 3">
            <conductores row="col-sm-6" label="Persona despacha gasolina " codigo="id_despacho_"></conductores>
            <conductores row="col-sm-6" label="Persona recibe gasolina" codigo="id_recibe_"></conductores>
            <bomba row="col-sm-4" label="Bomba de despacho x" codigo="id_bomba" :arreglo="bomba" tipo="2" requerido="true" :valor="data_vale.id_tipo_combustible"></bomba>
            <campo row="col-sm-4" label="Galones entregados" codigo="id_galones_" requerido="true" tipo="number" ></campo>
            <campo row="col-sm-4" label="Kilometraje actual" codigo="id_kmactual" requerido="true" tipo="number" ></campo>
            <campo row="col-sm-12" label="Observaciones" codigo="observa" tipo="textarea"></campo>
            <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
          </div>
        </form>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      valeDetalle: "",
      destino: [],
      idDestino: 0,
      placas: [],
      idVehiculo: "",
      capaTanque: "",
      valorGalones: "",
      tipos: "",
      verificarCapacidad: false,
      bomba: [],
      cantidadAutorizada: 0,
      tipoCombustible: 0,
      //tipo:0

    }
  },
  methods: {
    getDestinoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
        params: {
          id_tipo: 1
        }
      })
        .then(function (response) {
          this.destino = response.data;
          console.log(response.data);
          setTimeout(() => {
            $("#id_destino").select2({});

          }, 400);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    emit: function (opc) {
      this.$emit('event_child', opc);
    },
    getPlacas: function () {
      axios.get('vehiculos/php/back/listados/get_placas.php', {
        params: {
          id_destino: this.idDestino
        }
      }).then(function (response) {
        this.placas = response.data;
        setTimeout(() => {
          $("#id_vehiculo_").select2({

          });

          $('#id_vehiculo_').on('select2:select', function (e) {
            Instancia.getCapacidadTanque();
            Instancia.getTipoCombustible();
            var data = e.params.data;
            console.log(data);
          });

        }, 400);
        //app_vue_vale.getTipoCombustible(id_des);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCapacidadTanque: function () {
      axios.get('vehiculos/php/back/listados/get_capa.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.capaTanque = response.data;
        this.valorGalones = response.data.capaT;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    validarCheck: function () {
      if ($('#chk_Tanque_').is(':checked')) {
        this.verificarCapacidad = true;
        this.getCapacidadTanque();
      } else {
        this.verificarCapacidad = false;
        this.valorGalones = "0.00";
      }
    },
    getTipoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_tipo.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.tipos = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function (id) {
      const thisInstance = this;
      if (id == 1) {
        thisInstance.generarVale(id);
      } else {
        //
        $('#modal-remoto-lg').modal('hide');
      }
    },
    getBombas: function () {
      axios.get('vehiculos/php/back/listados/get_bomba.php', {
        params: { id_tipo: $('#id_tipo_combustible').val() }
      }).then(function (response) {
        this.bomba = response.data;
        console.log(response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    generarVale: function (id) {
      jQuery('.jsValidacionValeComubistible').validate({
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
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formValidacionValeCombustible');
          var txt = (tipo_acccion = 1) ? 'grabar' : (tipo_accion = 2) ? 'anular' : 'despachar';
          Swal.fire({
            title: '<strong>Desea ' + txt + ' el vale de combustible </strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "vehiculos/php/back/save/save_vale_combustible.php",
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend: function () {
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success: function (data) {
                  $('#modal-remoto-lg').modal('hide');
                  reload_movimientos_en();
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });
                }
              }).done(function () {


              }).fail(function (jqXHR, textSttus, errorThrown) {

                alert(errorThrown);

              });

            }

          })
        },
        rules: {
        }
      });
    },
    //fin validaciones
    getValeById: function () {
      if (this.tipo == 2) {
        axios.get('vehiculos/php/back/listados/get_capa.php', {
          params: {
            id_vehiculo: this.idVale
          }
        }).then(function (response) {
          this.valeDetalle = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }

    }

  },
  created: function () {
    if (this.tipo == 1) {
      this.getDestinoCombustible();
    }

    if (this.tipo == 4) {
      setTimeout(() => {
        console.log(this.data_vale.cant_autor);
        this.cantidadAutorizada = this.data_vale.cant_autor;
        console.log(this.data_vale.id_tipo_combustible);
        this.tipoCombustible = this.data_vale.id_tipo_combustible;
      }, 400);

      this.getBombas();
    }

    this.getValeById();

    Instancia = this;

    eventBus.$on('valorSeleccionado', (valor) => {
      $('#id_vehiculo_').val();
      $('#id_vehiculo_').val(null).trigger('change');
      this.idDestino = valor;
      if (valor == 1144 || valor == 1147) {
        this.getPlacas();
      } else {
        this.getTipoCombustible();
      }

    });

  }

})

Vue.component("formulario-cupones", {
  props: ["tipo", "idcupon", "documento"],
  template: `
  <div>
  <div class="">
    <table class="table table-bg table-bordered table-striped fixed_header" width="100%">
      <thead>
        <tr>
          <th class="text-center" width="10%">Cupon</th>
          <th class="text-center" width="10%">Monto Q.</th>
          <th class="text-center" width="8%">Usado</th>
          <th class="text-center" width="8%">Dev.</th>
          <th class="text-center" width="10%">Usado en</th>
          <th class="text-center" width="34%">Nombre</th>
          <th class="text-center" width="10%">Placa</th>
          <th class="text-center" width="10%">Km</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(c, index) in cupones">
          <td class="text-center" width="10%">{{c.cupon}}</td>
          <td class="text-center" width="10%">{{c.monto}}</td>
          <td class="text-center" width="8%">
            <input class="tgl tgl-flip text-center usado" :id="c.cupon1" type="radio" v-model="c.radio" :value="1" v-bind:disabled="documento.id_estado" @change="marcarVarios(1)" />
            <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon1"></label>
          </td>
          <td class="text-center" width="8%">
            <input class="tgl tgl-flip text-center devuelto" :id="c.cupon2" type="radio" v-model="c.radio" :value="2" v-bind:disabled="documento.id_estado" @change="marcarVarios(2)" />
            <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon2"></label>
          </td>
          <td class="text-center" width="10%">{{c.usadoen}}</td>
          <td class="text-center" width="34%">{{c.nombre}}</span></td>
          <td class="text-center" width="10%">{{c.placa}}</td>
          <td class="text-center" width="10%">{{c.km}}</td>
        </tr>
      </tbody>

    </table>
    <div class="row" v-if="documento.docto_estado == 4348">
      <div class="col-sm-12 text-right">
    <div class="row">
      <div class="col" style="justify-content: center">
        <h4>Cupones Usados:<h1><span class="badge badge-secondary">{{sumarUsado}}</span></h1></h4>
      </div>

      <div class="col" style="justify-content: center">
        <h4>Cupones Devueltos:<h1><span class="badge badge-secondary">{{sumarDevuelto}}</span></h1></h4>
      </div>

      <div class="col" style="justify-content: center">
        <h4>Total Cupones: <h1><span class="badge badge-secondary">{{sumarUsado+sumarDevuelto}}</span></h1></h4>
      </div>

      <div class="col" style="justify-content: center">
      <span v-if="cch1 >= 1" class="btn btn-info btn-sm btn-estado" @click="getOpc(2)"><i class="fa fa-check"></i> Asignar</span>
      <procesar-cupon :id_documento="idcupon" :cupones="cupones"></procesar-cupon>
      </div>

    </div>
       
      </div>
    </div>

  </div>
  <div v-if="opc == 2">
    <div id="myModal" class="modal-vue">

      <div class="modal-vue-content">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title">
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-ticket-alt">
              </i><span v-if="opc == 2" class="text-white"> Procesar Documento</span>
            </h4>
            <span class="close-icon" @click="getOpc(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body">
            <form class="jsValidacionCuponCombustible" id="formValidacionCuponCombustible">
              <input id="tipo_accion" name="tipo_accion" :value="tipo" type="text" hidden></input>
              <input id="id_cupon" name="id_cupon" :value="idcupon" type="text" v-if="tipo == 2" hidden></input>
              <div class="row" v-if="tipo == 1">
                <combo-change row="col-sm-6" label="Destino Combustible" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true" :valor="cuponDetalle.id_"></combo-change>
                <combo v-if="idDestino == 1144 || idDestino == 1147" row="col-sm-6" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" ></combo>
                <campo v-else row="col-sm-6" label="Características" codigo="caracteristicas" tipo="text" requerido="true" ></campo>
                <campo v-if="idDestino == 1144 || idDestino == 1147" row="col-sm-4" label="Kilometraje" codigo="kilometraje" tipo="number" requerido="true"></campo>
                <conductores row="col-sm-8" label="Persona recibe cupones" codigo="id_conductor_"> </conductores>

                <lugar-seleccion row1="col-sm-12" row2="col-sm-4" :depto="cuponDetalle.id_departamento" :muni="cuponDetalle.id_municipio" :lugar="cuponDetalle.id_lugar" tipo="0"></lugar-seleccion>
                <campo row="col-sm-12" label="Observaciones" codigo="observa" tipo="textarea"></campo>
                <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>

              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>`,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      total: 0,
      opc: 1,
      valeDetalle: "",
      destino: [],
      idDestino: 0,
      placas: [],
      idVehiculo: "",
      capaTanque: "",
      valorGalones: "",
      tipos: "",
      verificarCapacidad: false,
      cuponDetalle: "",
      cupones: [],
      contarCupones: 0,
      cch1: 0,
      contador: 0
    }
  },
  computed: {
    sumarUsado: function () {
      let contUsado = 0;

      this.cupones.forEach(function (c) {
        if (c.radio == 1) {
          contUsado++
        }
      });

      return contUsado
    },

    sumarDevuelto: function () {
      let contDevuelto = 0;

      this.cupones.forEach(function (c) {
        if (c.radio == 2) {
          contDevuelto++
        }
      });

      return contDevuelto
    }
  },
  methods: {
    getOpc: function (opc) {
      this.opc = opc;
    },
    getDestinoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
        params: {
          id_tipo: 2
        }
      })
        .then(function (response) {
          this.destino = response.data;
          console.log(response.data);
          setTimeout(() => {
            $("#id_destino").select2({});
          }, 400);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    emit: function (opc) {
      this.$emit('event_child', opc);
    },
    getPlacas: function () {
      axios.get('vehiculos/php/back/listados/get_placas.php', {
        params: {
          id_destino: this.idDestino,
          id_tipo: 2
        }
      }).then(function (response) {
        this.placas = response.data;
        setTimeout(() => {
          $("#id_vehiculo_").select2({

          });
        }, 400);
        //app_vue_vale.getTipoCombustible(id_des);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCapacidadTanque: function () {
      axios.get('vehiculos/php/back/listados/get_capa.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.capaTanque = response.data;
        this.valorGalones = response.data.capaT;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    validarCheck: function () {
      if ($('#chk_Tanque_').is(':checked')) {
        this.verificarCapacidad = true;
        this.getCapacidadTanque();
      } else {
        this.verificarCapacidad = false;
        this.valorGalones = "0.00";
      }
    },
    getTipoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_tipo.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.tipos = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCuponesbyDocumento: function () {
      if (this.idcupon > 0) {
        axios.get('vehiculos/php/back/listados/get_cupones_by_documento.php', {
          params: {
            id_documento: this.idcupon
          }
        }).then(function (response) {
          this.cupones = response.data;

          var i = 0;
          $.each(this.cupones, function (pos, elemento) {
            if (elemento.checked1) {
              i += parseInt(1);
            }
            if (elemento.checked2) {
              i += parseInt(1);
            }

          })

          this.contador = i;
          this.contarCupones = this.contador;
          console.log(this.cch1);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    eventAccion: function (id) {
      const thisInstance = this;
      if (id == 1) {
        thisInstance.generarEntregaCupon(id);
      } else {
        //this.$emit('cancelar', 1);
        this.opc = 1;
      }
    },
    generarEntregaCupon: function (id) {
      let id_vehiculo;
      let km_actual;
      if ($('#id_destino_c').val() != 1144 && $('#id_destino_c').val() != 1147) {
        id_vehiculo = $('#caracteristicas').val()
        km_actual = 0;
      } else {
        id_vehiculo = $('#id_vehiculo_').val()
        km_actual = $('#kilometraje').val()
      }
      const thisInstance = this;
      console.log(thisInstance.cupones)
      jQuery('.jsValidacionCuponCombustible').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          let elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          let cupon = $("#cupon").val();
          let data = { cupon };
          title = "¿Desea actualizar cupon ?";
          btn_text = "¡Si, Actualizar!";

          Swal.fire({
            title: '<strong>¿Desea actualizar cupones?</strong>',
            text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: btn_text
          }).then((result) => {
            console.log(result);
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "vehiculos/php/back/cupones/actualiza_cupon.php",
                data: {
                  cupones: thisInstance.cupones,
                  id_destino: $('#id_destino_c').val(),
                  id_vehiculo: id_vehiculo,
                  id_refer: $('#id_conductor_').val(),
                  km_actual: km_actual,
                  id_departamento: $('#departamento').val(),
                  id_municipio: $('#municipio').val(),
                  id_aldea: $('#poblado').val(),
                  id_observa: $('#observa').val(),
                },
                //dataType: "json",
                success: function (data) {
                  $('#modal-remoto-lg').modal('hide');
                  //reload_movimientos_en();
                  if (data == 'OK') {
                    Swal.fire({
                      type: 'success',
                      title: 'Cupon actualizado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getOpc(1);
                    thisInstance.cch1 = 0;
                    thisInstance.getCuponesbyDocumento();
                    viewModelEntregaCupon.getDocumento();
                    //reload();
                  } else {
                    Swal.fire({
                      type: 'warning',
                      title: 'Ocurrio un error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).fail(function (jqXHR, textSttus, errorThrown) {
                Swal.fire({
                  type: 'warning',
                  title: errorThrown, //'Error al actualizar cupon',
                  showConfirmButton: false,
                  timer: 1100
                });
              });
            }
          });
        }
      });
    },
    marcarVarios: function (tipo) {
      if (tipo == 1) {
        this.cch1 += 1;
      } else {
        this.cch1 -= 1;
      }
    },
    //fin validaciones
    getValeById: function () {
      if (this.tipo == 2) {
        axios.get('vehiculos/php/back/listados/get_capa.php', {
          params: {
            id_vehiculo: this.idCupon
          }
        }).then(function (response) {
          this.valeDetalle = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }

    },

  },
  created: function () {
    this.getCuponesbyDocumento();
    this.getDestinoCombustible();
    eventBus.$on('valorSeleccionado', (valor) => {
      this.idDestino = valor;
      if (valor == 1144 || valor == 1147) {
        this.getPlacas();
      }

    });

    eventBus.$on('recargarCuponesProcesados', () => {
      this.getCuponesbyDocumento();
    });


  }

});

//solicitar cupones
Vue.component('form-solicitar-cupon', {
  props: [],
  template:
    `
  <div>
    <form class="jsValidacionSolicitudCupon" id="formValidacionSolicitudCupon">
      <div class="row">
        <campo row="col-sm-4" label="Nro. de documento" codigo="nro_documento" tipo="text" requerido="true"></campo>
        <combo row="col-sm-4" label="Autorizado por:" codigo="id_autorizador" :arreglo="autorizadores" tipo="2" requerido="true"></combo>
        <conductores row="col-sm-4" label="Persona recibe cupones" codigo="id_conductor_"> </conductores>
  
        <div class="col-sm-12">
          <table class="table table-sm table-bordered table-striped table-perfil fixed_header" width="100%">
            <thead>
              <tr>
                <th class="text-center">
                  <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i></span>
                  Cupón
                </th>
                <th class="text-center">Monto</th>
                <th class="text-center"><span class="btn btn-sm btn-soft-danger" @click="limpiarLista()"><i class="fa fa-trash-alt"></i></span></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in cupones">
                <td class="text-center">{{c.Cupon_nro}}</td>
                <td class="text-center">{{parseInt(c.Cupon_monto)}}</td>
                <td class="text-center"><span class="btn btn-sm btn-soft-danger"><i class="fa fa-trash-alt"></i></span></td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div class="col-12">
          <div class="row">
            <div class="col" style="justify-content: center">
              <h3>Total Cupones de 100:<h1><span class="badge badge-secondary">{{cupones100}}</span></h1></h3>
            </div>
  
            <div class="col" style="justify-content: center">
              <h3>Total Cupones de 50:<h1><span class="badge badge-secondary">{{cupones50}}</span></h1></h3>
            </div>
  
            <div class="col" style="justify-content: center">
              <h3>Total Cupones: <h1><span class="badge badge-secondary">{{total}}</span></h1></h3>
            </div>
          </div>
  
          <div class="row">
            <div class="col-sm-8">
              <campo label="Observaciones" codigo="observaciones" tipo="textarea" requerido="true"></campo>
            </div>
  
            <div class="col-sm-4">
              <div class="col">
                <br><br>
                <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
              </div>
            </div>
          </div>
        </div>
  
        <div v-if="opc == 2">
          <div id="myModal" class="modal-vue">
            <!-- Modal content -->
            <div class="modal-vue-content">
              <div class="card shadow-card">
                <header class="header-color">
                  <h4 class="card-header-title">
                    <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-ticket-alt">
                    </i><span v-if="opc == 2" class="text-white">Ingresar correlativos</span>
                  </h4>
                  <span class="close-icon" @click="getOpc(1)">
                    <i class="fa fa-times"></i>
                  </span>
                </header>
                <div class="card-body">
                  <form class="jsValidacionSelectCupon" id="formValidacionSelectCupon">
                    <div class="row">
                      <cupones-disponibles-lista row="col-sm-6" label="Cupón inicial" codigo="c_ini"></cupones-disponibles-lista>
                      <cupones-disponibles-lista row="col-sm-6" label="Cupón final" codigo="c_fin"></cupones-disponibles-lista>
                      <!--<campo row="col-sm-6" label="Correlativo inicial:" codigo="c_ini" tipo="number" requerido="true"></campo>
                            <campo row="col-sm-6" label="Correlativo final:" codigo="c_fin" tipo="number" requerido="true"></campo>-->
                      <accion confirmacion="1" cancelar="2" v-on:event_child="eventAgregarC"></accion>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
`,

  data() {
    return {
      cupones100: 0,
      cupones50: 0,
      totalCupones: 0,
      opc: 1,
      clickCount: 0,
      autorizadores: [],
      cuponesT: [],
      cupones: [{
        Cupon_id: '',
        Cupon_nro: '',
        Cupon_monto: 0
      }],
    }
  },
  computed: {
    total: function () {
      let suma = 0
      let thes = this
      this.cupones100 = 0
      this.cupones50 = 0

      if (this.cupones.length > 1) {
        let total = this.cupones.reduce(function callback(valorAnterior, valorActual) {

          if (parseInt(valorActual.Cupon_monto) == 100) {
            thes.cupones100++
          } else if (parseInt(valorActual.Cupon_monto) == 50) {
            thes.cupones50++
          }

          return valorAnterior + parseInt(valorActual.Cupon_monto) /* resultado de la función callback */
        }, 0);
        return total
      } else {
        return suma
      }
    }
  },
  methods: {
    getOpc: function (opc) {
      this.opc = opc;
    },
    getAutorizadores: function () {
      axios.get('vehiculos/php/back/listados/get_autorizadores.php', {
      }).then(function (response) {
        this.autorizadores = response.data;
        setTimeout(() => {
          $("#id_autorizador").select2({});
        }, 400);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    eventAgregarC: function (id) {
      if (id == 1) {
        this.generarEntregaCupon()
      } else {
        this.opc = 1;
      }
    },
    limpiarLista: function () {
      this.cupones.splice(0, this.cupones.length);
      this.cupones100 = 0
      this.cupones50 = 0
    },
    generarEntregaCupon: function (id) {
      const thisInstance = this;
      jQuery('.jsValidacionSelectCupon').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          let elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          var form = $('#formValidacionSelectCupon');

          axios.get('vehiculos/php/back/listados/get_cupones_seleccionados', {
            params: {
              cupon_ini: $('#c_ini').val(),
              cupon_fin: $('#c_fin').val()
            }
          }).then(function (response) {
            //thisInstance.cuponesT = response.data;
            var iguales = 0;
            if (response.data.length > 0) {
              //form.reset();
              for (var c in response.data) {
                //sconsole.log(response.data[c].id_cupon);
                if (thisInstance.cupones.find((item) => item.Cupon_id == response.data[c].id_cupon)) {
                  iguales += 1;
                } else {
                  thisInstance.cupones.push({
                    Cupon_id: response.data[c].id_cupon,
                    Cupon_nro: response.data[c].nro_cupon,
                    Cupon_monto: response.data[c].monto
                  })
                }
              }
              if (iguales > 0) {
                var cant = (iguales > 1) ? ' cupones ya están ' : ' cupón ya está ';
                Swal.fire({
                  type: 'error',
                  title: iguales + cant + 'en la lista',
                  showConfirmButton: false,
                  //timer: 1100,
                  showConfirmButton: true
                });
                //limpiarLista();
              }
              console.log(iguales);
            }
            $(".close-icon").click();
          }.bind(this)).catch(function (error) {
            console.log(error);
          });
        },
        rules: {
          c_fin: {
            remote: {
              url: 'vehiculos/php/back/cupones/validar_cupones.php',
              data: {
                c_ini: function () { return $('#c_ini').val(); },
                c_fin: function () { return $('#c_fin').val(); }
              }
            }
          }
        },
        messages: {
          c_fin: {
            remote: "El cupón final no puede ser menor al inicial."
          }
        }
      });
    },
    eventAccion: function (id) {
      if (id == 1) {
        this.crearSolicitud();
      } else {
        $('#modal-remoto-lg').modal('hide');
      }
    },
    crearSolicitud: function () {
      const thisInstance = this;
      jQuery('.jsValidacionSolicitudCupon').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          let elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          var form = $('#formValidacionSolicitudCupon');
          if (thisInstance.cupones.length > 0) {
            //inicio solicitud
            Swal.fire({
              title: '<strong>¿Desea entregar los cupones?</strong>',
              //text: 'title',
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, entregar!'
            }).then((result) => {
              console.log(result);
              if (result.value) {
                $.ajax({
                  type: "POST",
                  url: "vehiculos/php/back/cupones/crear_solicitud_cupones.php",
                  data: {
                    cupones: thisInstance.cupones,
                    nro_documento: $('#nro_documento').val(),
                    id_autorizador: $('#id_autorizador').val(),
                    id_conductor_: $('#id_conductor_').val(),
                    observaciones: $('#observaciones').val(),
                  },
                  beforeSend: function () {

                  },
                  //dataType: "json",
                  success: function (data) {
                    if (data == 'OK') {
                      Swal.fire({
                        type: 'success',
                        title: 'Cupon actualizado',
                        showConfirmButton: false,
                        timer: 1100
                      });
                      //reload();
                      $('#modal-remoto-lg').modal('hide');
                      reload_cupones_entregados(4348);
                    } else {
                      Swal.fire({
                        type: 'warning',
                        title: 'Ocurrio un error',
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                  }
                }).fail(function (jqXHR, textSttus, errorThrown) {
                  Swal.fire({
                    type: 'warning',
                    title: errorThrown,//'Error al actualizar cupon',
                    showConfirmButton: false,
                    timer: 1100
                  });
                });
              }
            });
            // fin solicitud
          } else {
            Swal.fire({
              type: 'error',
              title: 'Debe seleccionar al menos un cupón',
              showConfirmButton: false,
              timer: 1100,
              //showConfirmButton: true
            });
          }

        }
      });
    }
  },
  created: function () {
    this.getAutorizadores();
    this.cupones.splice(0, 1);
  }
});

//fin solicitar


// este no
Vue.component("form-entrega-cupones", {
  props: ["tipo", "idcupon"],
  template: `
      <div>
        <div class="card-body">
          <div v-if = "contarCupones > 0">
            <component2>xxxx</component2>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-sm table-bordered table-striped fixed_header">
               <thead height="10px">
                  <tr>
                  <th class="text-center" >Cupon</th>
                  <th class="text-center" >Monto Q.</th>
                  <th class="text-center" >Usado</th>
                  <th class="text-center" >Dev.</th>
                  <th class="text-center" >Usado en</th>
                  <th class="text-center" >Nombre</th>
                  <th class="text-center" >Placa</th>
                  <th class="text-center" >Km</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(c, index) in cupones #" height="5px">
                    <td class="text-center">{{c.cupon}}</td>
                    <td class="text-center">{{ c.monto }}</td>

                    <td class="text-center">
                      <input class="tgl tgl-flip text-center" :id="c.cupon1" type="radio" v-model="c.radio" :value="1" v-bind:disabled="documento.id_estado" @change="marcarVarios(1)"/>
                      <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon1" ></label>
                    </td>

                    <td class="text-center">
                      <input class="tgl tgl-flip text-center" :id="c.cupon2"  type="radio" v-model="c.radio" :value="2" v-bind:disabled="documento.id_estado"  @change="marcarVarios(2)"/>
                      <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="c.cupon2" ></label>
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
        <div v-if="opc == 2">
          <div id="myModal" class="modal-vue">

            <!-- Modal content -->
            <div class="modal-vue-content">
              <div class="card shadow-card">
                <header class="header-color">
                  <h4 class="card-header-title" >
                    <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-ticket-alt">
                    </i><span v-if="opc == 2" class="text-white"> Procesar Documento</span>
                  </h4>
                  <span class="close-icon"  @click="getOpc(1)">
                    <i class="fa fa-times"></i>
                  </span>
                </header>
                <div class="card-body">
                  <form class="jsValidacionEntregaCupon" id="formValidacionEntregaCupon">

                    <div class="row" >
                          <campo row="col-sm-4" label="Nro. Documento" codigo="nro_docto" tipo="text" requerido="true"></campo>
                          <conductores row="col-sm-6" label="Persona que autorizo" codigo="id_autorizo_"> </conductores>
                          <conductores row="col-sm-6" label="Persona que recibe" codigo="id_recibe_"> </conductores>

                          <campo row="col-sm-12" label="Observaciones" codigo="observa" tipo="textarea"></campo>
                          <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      valeDetalle: "",
      destino: [],
      idDestino: 0,
      placas: [],
      idVehiculo: "",
      capaTanque: "",
      valorGalones: "",
      tipos: "",
      verificarCapacidad: false,
      cuponDetalle: "",
      cupones: [],
      cch1: 0,
      contarCupones: 0
    }
  },
  mounted: function () {
  },
  methods: {
    getDestinoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
        params: {
          id_tipo: 2
        }
      })
        .then(function (response) {
          this.destino = response.data;
          console.log(response.data);
          setTimeout(() => {
            $("#id_destino").select2({});
          }, 400);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    emit: function (opc) {
      this.$emit('event_child', opc);
    },
    getPlacas: function () {
      axios.get('vehiculos/php/back/listados/get_placas.php', {
        params: {
          id_destino: this.idDestino,
          id_tipo: 2
        }
      }).then(function (response) {
        this.placas = response.data;
        setTimeout(() => {
          $("#id_vehiculo_").select2({

          });


        }, 400);
        //app_vue_vale.getTipoCombustible(id_des);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCapacidadTanque: function () {
      axios.get('vehiculos/php/back/listados/get_capa.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.capaTanque = response.data;
        this.valorGalones = response.data.capaT;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    validarCheck: function () {
      if ($('#chk_Tanque_').is(':checked')) {
        this.verificarCapacidad = true;
        this.getCapacidadTanque();
      } else {
        this.verificarCapacidad = false;
        this.valorGalones = "0.00";
      }
    },
    getTipoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_tipo.php', {
        params: {
          id_vehiculo: $('#id_vehiculo_').val()
        }
      }).then(function (response) {
        this.tipos = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCuponesbyDocumento: function () {
      if (this.id_documento > 0) {
        axios.get('vehiculos/php/back/listados/get_cupones_by_documento.php', {
          params: {
            id_documento: this.id_documento
          }
        }).then(function (response) {
          this.cupones = response.data;

          var i = 0;
          $.each(this.cupones, function (pos, elemento) {
            if (elemento.checked1) {
              i += parseInt(1);
            }
            if (elemento.checked2) {
              i += parseInt(1);
            }

          })

          this.contador = i;
          this.contarCupones = this.contador;
          console.log(this.cch1);
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    eventAccion: function (id) {
      const thisInstance = this;
      if (id == 1) {
        thisInstance.generarEntregaCupon(id);
      } else {
        this.$emit('cancelar', 1);
      }
    },
    generarEntregaCupon: function (id) {
      jQuery('.jsValidacionEntregaCupon').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          let elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          let cupon = $("#cupon").val();
          let data = { cupon };
          title = "¿Desea actualizar cupon ?";
          btn_text = "¡Si, Actualizar!";

          Swal.fire({
            title: '<strong>¿Desea actualizar cupones ?</strong>',
            text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: btn_text
          }).then((result) => {
            console.log(result);
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "vehiculos/php/back/cupones/actualiza_cupon.php",
                data: {
                  cupones: this.cupones,
                  id_destino: $('#id_destino_c').val(),
                  id_vehiculo: $('#id_vehiculo_').val(),
                  id_refer: $('#id_conductor_').val(),
                  km_actual: $('#kilometraje').val(),
                  id_departamento: $('#departamento').val(),
                  id_municipio: $('#municipio').val(),
                  id_aldea: $('#poblado').val(),
                  id_observa: $('#observa').val(),
                },
                //dataType: "json",
                success: function (data) {
                  $('#modal-remoto-lg').modal('hide');
                  reload_movimientos_en();
                  if (data == 'OK') {
                    Swal.fire({
                      type: 'success',
                      title: 'Cupon actualizado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  } else {
                    Swal.fire({
                      type: 'warning',
                      title: 'Ocurrio un error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).fail(function (jqXHR, textSttus, errorThrown) {
                Swal.fire({
                  type: 'warning',
                  title: errorThrown,//'Error al actualizar cupon',
                  showConfirmButton: false,
                  timer: 1100
                });
              });
            }
          });
        }
      });
    },
    //fin validaciones
    getValeById: function () {
      if (this.tipo == 2) {
        axios.get('vehiculos/php/back/listados/get_capa.php', {
          params: {
            id_vehiculo: this.idCupon
          }
        }).then(function (response) {
          this.valeDetalle = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }

    }

  },
  created: function () {
    this.getCuponesbyDocumento();
    this.getDestinoCombustible();
    eventBus.$on('valorSeleccionado', (valor) => {
      this.idDestino = valor;
      if (valor == 1144 || valor == 1147) {
        this.getPlacas();
      }

    });
  }

})

Vue.component("form-ingreso-cupon", {
  props: ["tipo", "idcupon"],
  template: `
      <div>
       <form class="jsValidacionIngCupon" id="formValidacionIngCupon">
        <div class="card-body">
          <div class="row" >
            <campo row="col-sm-4" label="Nro. Documento" codigo="nro_docto" tipo="text" requerido="true"></campo>
            <campo row="col-sm-8" label="Observaciones" codigo="observa" tipo="textarea"></campo>
            <campo row="col-sm-2" label="del Cupon #" codigo="cupon1" tipo="text" requerido="true" ></campo>
            <campo row="col-sm-2" label="al Cupon #" codigo="cupon2" tipo="text" requerido="true"></campo>
            <campo row="col-sm-2" label="Con monto de Q." codigo="monto" tipo="text" requerido="true"></campo>
            <div class="col-sm-4">
          </div>

          <div class="col-sm-2">
            <span type="number" id="btProc" class="btn btn-sm btn-info"@click="generarCupones()"><i class="fa fa-check"></i> Procesar</span>
          </div>

          </div>
          <div class="row">
    
            <div class="col-sm-12">

              <table class="table table-sm table-bordered table-striped fixed_header" width="100%">
                <thead>
                  <tr>
                  <th class="text-center" >Nro. Cupon</th>
                  <th class="text-center" >Monto Q.</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="c in arrayCupones">
                    <td class="text-center">{{ c.cupon }}</td>
                    <td class="text-center">{{ c.monto }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

          <div class="col-sm-12">
            <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
          </div>

        </div>
       </form>
      </div>
    `,

  data() {
    return {
      //cupones: [],
      //contarCupones: 0,
      arrayCupones: []

    }
  },

  methods: {

    generarCupones: function () {
      const thisInstance = this;
      var cini = parseInt($('#cupon1').val());
      var cfin = parseInt($('#cupon2').val());
      var cMonto = $('#monto').val();
      var cTotal = 0;
      for (var x = cini; x <= cfin; x++) {
        this.arrayCupones.push({ cupon: x, monto: cMonto });
      }

      for (var i = 0; i < this.arrayCupones.length; i++) {
        cTotal = cTotal + this.arrayCupones['monto'].val;
      }

    },

    eventAccion: function (id) {
      const thisInstance = this;
      if (id == 1) {
        thisInstance.grabarCupones(id);
      } else {
        this.$emit('cancelar', 1);
      }
    },

    grabarCupones: function (id) {
      var thisInstance = this;
      jQuery('.jsValidacionIngCupon').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
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
          var form = $('#formValidacionIngCupon');
          var cupon = $("#cupon").val();
          //var data = { cupon };
          title = "¿Desea grabar cupones ?";
          btn_text = "¡Si, Grabar!";

          Swal.fire({
            title: '<strong>¿Desea grabar ?</strong>',
            text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: btn_text
          }).then((result) => {
            //console.log(result);
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "vehiculos/php/back/cupones/graba_ing_cupones.php",
                data: {
                  arrayCupones: thisInstance.arrayCupones,
                  id_nroDocto: $('#nro_docto').val(),
                  id_observa: $('#observa').val(),
                  id_monto: $('#monto').val(),
                },
                //dataType: "json",
                success: function (data) {
                  $('#modal-remoto-lg').modal('hide');
                  reload_movimientos_en();

                  if (data == 'OK') {
                    Swal.fire({
                      type: 'success',
                      title: 'Cupon actualizado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getOpc(1);
                  } else {
                    Swal.fire({
                      type: 'warning',
                      title: 'Ocurrio un error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).fail(function (jqXHR, textSttus, errorThrown) {
                Swal.fire({
                  type: 'warning',
                  title: errorThrown,//'Error al actualizar cupon',
                  showConfirmButton: false,
                  timer: 1100
                });
              });
            }
          });
        }
      });
    },
  },
})