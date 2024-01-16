var eventBusFO = new Vue();
var viewModelFacturaOperar = new Vue({

  //el:'#app_factura',
  data:{
    tipo:$('#id_tipo').val(),
    arreglo:$('#id_arreglo').val(),
    orden_id:$('#orden_id').val(),
    tipoOpe:$('#id_tipo_ope').val(),
    privilegio:""
  },
  created: function(){
    eventBusFO.$on('regresarPrincipal', (opc) => {
      this.getOpcion(opc);
    });
  },
  components: {
    //insumosc
  },
  methods:{
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },
  }
})

viewModelFacturaOperar.$mount('#app_factura_ope');
