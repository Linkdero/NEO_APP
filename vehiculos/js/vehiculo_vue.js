var eventBus = new Vue();
var instanciaP;

 appVehiculoDetalle_ = new Vue({
    //el: '#app_nuevo_vale',
    data: {
        id_vehiculo:$('#id_vehiculo').val(),
        vehiculoDetalle:""
    },
    created: function(){
 

    },
    methods: {
        get_correla: function(){
            // if(this.id_persona > 0){
    
              axios.get('vehiculos/php/back/listados/get_correla.php', {
                params: {
                //   id_persona: this.id_persona
                }
              }).then(function (response) {
                appVehiculoDetalle_.correlativo = response.data;
              }).catch(function (error) {
                console.log(error);
              });
            // }
    
          }

    }

  })
  appVehiculoDetalle_.$mount('#appVehiculoDetalle');
  instanciaP = appVehiculoDetalle_;
  
//})


