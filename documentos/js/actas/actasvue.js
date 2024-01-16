var eventBusActaC = new Vue();
var viewModelActaCompra = new Vue({

  //el:'#app_factura',
  data:{
    privilegio:""
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

viewModelActaCompra.$mount('#app_acta');
