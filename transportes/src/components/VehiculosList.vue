<template>
  <combo v-if="idDestino == 1144 || idDestino == 1147" label="Seleccionar vehículo" :codigo="codigo" :row="columna" :arreglo="vehiculos" tipo="2" :requerido="requerido" :valor="valor"></combo>

</template>
<script>
  module.exports = {
    props:["codigo","columna","solicitud","solicitud_id","privilegio","estado", "evento","requerido","valor","tipo"],
    data() {
      return {
        vehiculos:[],
        idDestino:1144,
        incremental:0
      }
    },
    mounted(){

    },
    created: function(){

      this.evento.$on('getDestinoVehiculo', (valor) => {
        this.idDestino = valor;
        //console.log( 'adfkjaldjf'+valor);
        this.getVehiculos();
      });

      this.$nextTick(() => {
        this.getVehiculos();
        setTimeout(() => {
          /*$("#"+this.codigo).select2({

          });*/
        }, 1400);
      });


    },
    methods:{
      setOption: function(opc){
        //this.option = opc;
        this.evento.$emit('getOpcion', opc);
      },
      getVehiculos: function(){
        var thisInstance = this;
        axios.get('vehiculos/php/back/listados/get_placas.php', {
          params: {
            id_destino:this.idDestino,
            id_tipo:0
          }
        }).then(function (response) {
          this.vehiculos = response.data;
          setTimeout(() => {
            $("#"+thisInstance.codigo).select2({

            });
            $('#'+thisInstance.codigo).on('select2:select', function (e) {
              //Instancia.getCapacidadTanque();
              //Instancia.getTipoCombustible();
              var data = e.params.data;

              //const datav = JSON.parse(data);
              console.log(data.id);
              if(thisInstance.tipo == 'calculo'){
                //console.log(JSON.stringify(data, ...));
                thisInstance.evento.$emit('getDataVehiculo', data.id);
              }else{
                console.log(2);
              }

            });
          }, 00);
          //app_vue_vale.getTipoCombustible(id_des);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
        this.incremental += 1;
        console.log(this.incremental);
        if(this.incremental == 1){

        }

      }
    }
  }
</script>
