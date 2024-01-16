<template>
  <div class="row">
    <combo-change row="col-sm-12" :label="labelTitulo" codigo="id_destino_c" :arreglo="destino" tipo="2" requerido="true"></combo-change>
    <combo v-if="idDestino == 1144 || idDestino == 1147" row="col-sm-12" label="Placas" codigo="id_vehiculo_" :arreglo="placas" tipo="2" requerido="true"></combo>
    <campo v-else row="col-sm-12" label="Características" codigo="txt_caracter_" tipo="text" requerido="true"></campo>
  </div>
</template>
<script>
module.exports = {
  props:["tipo"],
  mounted() {
    //console.log('Component mounted.')
  },
  data(){
    return {
      destino:[],
      idDestino:0,
      placas:[],
      labelTitulo:""
    }
  },
  methods:{
    getDestinoCombustible: function(){
      axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
        params: {
          id_tipo:this.tipo
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
    getPlacas: function(){
      axios.get('vehiculos/php/back/listados/get_placas.php', {
        params: {
          id_destino:this.idDestino,
          id_tipo:1
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
  created: function(){
    this.getDestinoCombustible();
    this.labelTitulo = (this.tipo == 3) ? 'Destiono Servicio' : 'Destino Combustible';
    //Instancia = this;

    eventBus.$on('valorSeleccionado', (valor) => {
      $('#id_vehiculo_').val();
      $('#id_vehiculo_').val(null).trigger('change');
      this.idDestino = valor;
        if(valor==1144 || valor==1147){
            this.getPlacas();

        }

      });
  }
}
</script>
