var eventBus = new Vue();
var instanciaSN;

viewModelServicioDetalle = new Vue({
  //el: '#app_nuevo_vale',
  data: {
    idServicio: $('#id_servicio').val()
  },
  created: function () {
    eventBus.$on('getIdVehiculo', (valor) => {
      this.idVehiculo = valor;
    });
  },
  methods: {
  },
})
viewModelServicioDetalle.$mount('#app_orden_serviciod');
instanciaSN = viewModelServicioDetalle;

//})
