var eventBusAsigTe = new Vue();
var viewModelAsigTecnico = new Vue({

  //el:'#app_factura',
  data:{
    privilegio:"",
    orden_id:$('#id_arreglo').val(),
  },
  created: function(){
    this.setTitulo();

  },
  components: {
    //insumosc
  },
  methods:{
    setTitulo: function(){

    },
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },

  }
})

viewModelAsigTecnico.$mount('#app_tecnico_global');
