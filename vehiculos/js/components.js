Vue.component("foto-vehiculo", {
  props: ["id_vehiculo"],
  template: `
    <div class="userProfileInfo card" style="height:510px">
      <div class="image text-center slide_up_anim">
        <img class='mb-3 ' style="width:100%" :src='fotoVehiculo.foto' ></img>
        <img class='mb-3 show_bg_2' style="width:100%"  ></img>
        <p v-if="fotoVehiculo.cambio" href="" title="image" class="editImage">
          <i class="fa fa-camera"></i>
        </p>
      </div>
    </div>

    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      fotoVehiculo: ""
    }
  },
  mounted: function () {

  },
  methods: {
    getFotografiaById: function () {
      axios.get('vehiculos/php/back/vehiculos/get_vehiculo_fotografia', {
        params: {
          id_vehiculo: this.id_vehiculo
        }
      }).then(function (response) {
        this.fotoVehiculo = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function () {
    this.getFotografiaById();
  }
});

Vue.component("vehiculo", {
  props: ["id_vehiculo", "type"],
  template: `
        <div v-bind:style="styleObject">

            <dato-persona texto="Título" tipo="2" :dato="detalleVehiculo.nro_placa"></dato-persona>
            <dato-persona texto="Chasís" icono="fa fa-car-tilt" tipo="2" :dato="detalleVehiculo.chasis"></dato-persona>
            <dato-persona texto="Título" tipo="2" :dato="detalleVehiculo.motor"></dato-persona>
            <dato-persona texto="Título" tipo="2" :dato="detalleVehiculo.modelo"></dato-persona>
            <dato-persona texto="Título" tipo="2" :dato="detalleVehiculo.flag_franjas_de_color"></dato-persona>
            <dato-persona texto="Título" tipo="2" :dato="detalleVehiculo.nombre_marca"></dato-persona>
            <dato-persona texto="Título" tipo="2" icono="fa fa-card-side":dato="detalleVehiculo.nombre_tipo"></dato-persona>
            <dato-persona texto="Título" tipo="2"  :dato="detalleVehiculo.nombre_estado"></dato-persona>
            <dato-persona texto="Título" tipo="2" icono="fa fa-user" :dato="detalleVehiculo.nombre_persona_asignado"></dato-persona>
            <dato-persona texto="Título" tipo="2" v-if="type == 1" icono="fa fa-glass" :dato="detalleVehiculo.capacidad_tanque"></dato-persona>
            <dato-persona texto="Tipo de combustible" icono="fa fa-gas-pump" tipo="2" :dato="detalleVehiculo.nombre_tipo_combustible"></dato-persona>
            <dato-persona texto="Kilometraje actual" icono="fa fa-clock" tipo="2" :dato="detalleVehiculo.km_actual"></dato-persona>

            <input id="id_km_actual" name="id_km_actual" :value="detalleVehiculo.km_actual"></input>
        </div>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      detalleVehiculo: "",
      styleObject: ""
    }
  },
  mounted: function () {

  },
  methods: {
    getVehiculoById: function () {

      axios.get('vehiculos/php/back/vehiculos/get_vehiculo_by_id', {
        params: {
          id_vehiculo: this.id_vehiculo
        }
      }).then(function (response) {
        this.detalleVehiculo = response.data;
        eventBus.$emit('getIdVehiculo', this.id_vehiculo);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function () {

    if (this.type == 2) {
      this.styleObject = {
        //height: '320px',
        width: '100%',
        fontSize: '13px',
        'overflow-y': 'scroll'
      }
    }
    if (this.id_vehiculo != '') {
      console.log(this.tipo);
      this.getVehiculoById();
      eventBus.$on('recargarVehiculo', (valor) => {
        this.id_vehiculo = valor;
        this.getVehiculoById()

      });
    }
  }
});

Vue.component("destino-combustible", {
  props: ["tipo", "row"],
  template: `
    <div class="row">
    <combo-change :row="row" label="Destino Combustible" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true" :valor="valeDetalle.id_"></combo-change>
    <combo v-if="idDestino == 1144 || idDestino == 1147" :row="row" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" ></combo>
    </div>

    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {

    }
  },
  mounted: function () {

  },
  methods: {
    getFotografiaById: function () {
      axios.get('vehiculos/php/back/vehiculos/get_vehiculo_fotografia', {
        params: {
          id_vehiculo: this.id_vehiculo
        }
      }).then(function (response) {
        this.fotoVehiculo = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function () {
    this.getFotografiaById();
  }
});

Vue.component('procesar-cupon', {
  props: ['id_documento', "cupones"],
  template:
    `
      <span class="btn btn-sm btn-info" @click="procesaCupon()"><i class="fa fa-check-circle"></i> Procesar</span>
    `
  ,
  data() {
    return {
      clickCount: 0
    }
  },
  methods: {
    procesaCupon: function () {
      Swal.fire({
        title: '<strong>¿Desea procesar cupones ?</strong>',
        text: '',
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Procesar',
      }).then((result) => {
        console.log(result);
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "vehiculos/php/back/cupones/procesa_cupon.php",
            data: {
              cupones: this.cupones,
              id_documento: this.id_documento
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
                //$('#id_cambio').val(1);
                reload_cupones_entregados(4348);
                //$('#modal-remoto-lgg2').modal('hide');
                eventBus.$emit('recargarDocumento');
                eventBus.$emit('recargarCuponesProcesados');
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
    },
    emitGlobalClickEvent() {
      this.clickCount++;
      eventBus.$emit('clicked', this.clickCount);
    }
  }
});

//cupones disponibles lista
Vue.component("cupones-disponibles-lista", {
  props: ["row", "label", "codigo"],
  template: `
          <combo :row="row" :label="label" :codigo="codigo" :arreglo="cupones" tipo="2" requerido="true"></combo>
      `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      cupones: []
    }
  },
  mounted: function () {

  },
  methods: {
    getCuponesDisponibles: function () {
      axios.get('vehiculos/php/back/listados/get_cupones_disponibles.php', {
        params: {
          //   id_persona: this.id_persona
        }
      }).then(function (response) {
        this.cupones = response.data;
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
    this.getCuponesDisponibles();
  }
});

// Component B
Vue.component('component2', {
  template: `
      <span class="btn btn-sm btn-info" @click="bar()"><i class="fa fa-check-circle"></i> Procesar</span>
    `,
  methods: {
    bar: function () {
      const clickHandler = function (clickCount) {
        console.log(`The button has been clicked ${clickCount} times!!!!`)
      }
      eventBus.$on('clicked', clickHandler);
    }
  },
  created() {
    const clickHandler = function (clickCount) {
      console.log(`The button has been clickedxxxyyy ${clickCount} times!!!!`)
    }
    eventBus.$on('clicked', clickHandler);
  },
});

//componente filtro vehiculos
Vue.component("component-vehiculos", {
  props: ["tipo"],
  template: `
    <div class="row">
      <combo-change row="col-sm-12" :label="labelTitulo" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true"></combo-change>
      <combo v-if="idDestino == 1144 || idDestino == 1147" row="col-sm-12" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" requerido="true"></combo>
      <campo v-else row="col-sm-12" label="Características" codigo="txt_caracter_" tipo="text" requerido="true"></campo>
    </div>
    `,
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      destino: [],
      idDestino: 0,
      placas: [],
      labelTitulo: ""
    }
  },
  methods: {
    getDestinoCombustible: function () {
      axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
        params: {
          id_tipo: this.tipo
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
    getPlacas: function () {
      axios.get('vehiculos/php/back/listados/get_placas.php', {
        params: {
          id_destino: this.idDestino,
          id_tipo: 1
        }
      }).then(function (response) {
        this.placas = response.data;
        setTimeout(() => {
          $("#id_vehiculo_").select2({

          });
          $('#id_vehiculo_').on('select2:select', function (e) {
            //Instancia.getCapacidadTanque();
            //Instancia.getTipoCombustible();
            var data = e.params.data;
            console.log(data.id);
            eventBus.$emit('recargarVehiculo', data.id);
          });
        }, 400);
        //app_vue_vale.getTipoCombustible(id_des);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
  },
  created: function () {
    this.getDestinoCombustible();
    this.labelTitulo = (this.tipo == 3) ? 'Destiono Servicio' : 'Destino Combustible';
    //Instancia = this;

    eventBus.$on('valorSeleccionado', (valor) => {
      $('#id_vehiculo_').val();
      $('#id_vehiculo_').val(null).trigger('change');
      this.idDestino = valor;
      if (valor == 1144 || valor == 1147) {
        this.getPlacas();

      }

    });
  }

})


Vue.component("component-servicio-detalle", {
  props: ["id_servicio"],
  template: `
      <div class="row">
        <div class="col-sm-3">
          <!--<vehiculo tipo="1"></vehiculo>-->
          <div class="ticket">
	<div class="holes-top"></div>
	<div class="title">
		<p class="cinema">{{ servicio.nro_orden}}</p>
		<p class="movie-title">{{ servicio.nro_placa }}</p>
	</div>
	<div class="poster">
		<foto-vehiculo :id_vehiculo="189"></foto-vehiculo>
	</div>
	<div class="info">


	</div>
	<div class="holes-lower"></div>
	<div class="serial">

	</div>
</div>
        </div>
        <div class="col-sm-9 scrollable-div-persona" id="div-persona" style="left:0.8rem">
          <div ref="infoBox" id="div-persona">
            <div class="row">
              <div class="col-sm-12" >
                <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-edit"></i>Requerimientos del Servicio
                <textarea id="editor">{{ servicio.desc_solicitado }}</textarea>
              </div>

              <div class="col-sm-12" style="margin-top:15px">
                <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-edit"></i>Servicios realizados
                <textarea id="editor2">{{ servicio.desc_realizado }}</textarea>
              </div>
              <div class="col-sm-12">

                  Datos del mecánico y taller
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
      servicio: "",
      plainText: ""
    }
  },
  methods: {
    getServicioById: function () {

      axios.get('vehiculos/php/back/servicios/detalle/get_servicio_by_id.php', {
        params: {
          id_servicio: this.id_servicio
        }
      })
        .then(function (response) {
          this.servicio = response.data;
          console.log(response.data.id_vehiculo);
          setTimeout(() => {
            eventBus.$emit('recargarVehiculo', response.data.id_vehiculo);
            this.plainText = this.convertToPlain(this.servicio.obs_solicitado);
          }, 400);

        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    convertToPlain: function (rtf) {
      rtf = rtf.replace(/\\par[d]?/g, "");
      return rtf.replace(/\{\*?\\[^{}]+}|[{}]|\\\n?[A-Za-z]+\n?(?:-?\d+)?[ ]?/g, "").trim();
    }
  },
  created: function () {
    this.getServicioById();
    setTimeout(() => {
      var i1 = tinymce.init({
        selector: 'textarea',
        //plugins: 'link, lists, image, table, media',
        menubar: false,
        height: "150",
        lang: 'es-ES',
        //plugins: ["advlist autolink lists link image charmap print preview anchor"],
        //menubar: false,
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste"
        ],
        force_br_newlines: true,
        resize: false,
        rows: 3,
        toolbar: "styleselect | bold italic link bullist numlist | outdent indent | image blockquote table media undo redo | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link image"
        //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
      });

    }, 0);
  }

})


Vue.component("component-servicio-detalle2", {
  props: ["id_servicio"],
  template: `
      <div class="row">
        <div class="col-sm-3">
          <!--<vehiculo tipo="1"></vehiculo>-->
          <div class="ticket">
	<div class="holes-top"></div>
	<div class="title">
		<p class="cinema">{{ servicio.nro_orden}}</p>
		<p class="movie-title">{{ servicio.nro_placa }}</p>
	</div>
	<div class="poster">
		<foto-vehiculo :id_vehiculo="189"></foto-vehiculo>
	</div>
	<div class="info">


	</div>
	<div class="holes-lower"></div>
	<div class="serial">

	</div>
</div>
        </div>
        <div class="col-sm-9 scrollable-div-persona" id="div-persona" style="left:0.8rem">
          <div ref="infoBox" id="div-persona">
            <div class="row">
              <div class="col-sm-12" >
                <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-edit"></i>Requerimientos del Servicio
                <textarea id="editor">{{ servicio.desc_solicitado }}</textarea>
              </div>

              <div class="col-sm-12" style="margin-top:15px">
                <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-edit"></i>Servicios realizados
                <textarea id="editor2">{{ servicio.desc_realizado }}</textarea>
              </div>
              <div class="col-sm-12">

                  Datos del mecánico y taller
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
      servicio: "",
      plainText: ""
    }
  },
  methods: {
    getServicioById: function () {

      axios.get('vehiculos/php/back/servicios/detalle/get_servicio_by_id.php', {
        params: {
          id_servicio: this.id_servicio
        }
      })
        .then(function (response) {
          this.servicio = response.data;
          console.log(response.data.id_vehiculo);
          setTimeout(() => {
            eventBus.$emit('recargarVehiculo', response.data.id_vehiculo);
            this.plainText = this.convertToPlain(this.servicio.obs_solicitado);
          }, 400);

        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    convertToPlain: function (rtf) {
      rtf = rtf.replace(/\\par[d]?/g, "");
      return rtf.replace(/\{\*?\\[^{}]+}|[{}]|\\\n?[A-Za-z]+\n?(?:-?\d+)?[ ]?/g, "").trim();
    }
  },
  created: function () {
    this.getServicioById();
    setTimeout(() => {
      var i1 = tinymce.init({
        selector: 'textarea',
        //plugins: 'link, lists, image, table, media',
        menubar: false,
        height: "150",
        lang: 'es-ES',
        //plugins: ["advlist autolink lists link image charmap print preview anchor"],
        //menubar: false,
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste"
        ],
        force_br_newlines: true,
        resize: false,
        rows: 3,
        toolbar: "styleselect | bold italic link bullist numlist | outdent indent | image blockquote table media undo redo | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link image"
        //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
      });

    }, 0);
  }

})

