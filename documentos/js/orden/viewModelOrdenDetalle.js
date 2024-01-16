var eventBusOrdenO = new Vue();
var viewModelOrdenCompraDetalle = new Vue({

  //el:'#app_factura',
  data:{
    arreglo:"",
    id_pago:$('#id_pago').val(),
    titulo:'',
    label:'',
    tag:'',
    privilegio:"",
    ordenDetalle:"",
    facturas:"",
    visible:false
  },
  created: function(){
    this.getOrdenDetalleById();
  },
  components: {
    //insumosc
  },
  methods:{
    getOrdenDetalleById: function(){
      axios.get('documentos/php/back/orden/get_orden_detalle', {
        params: {
          id_pago:this.id_pago
        }
      }).then(function (response) {
        this.ordenDetalle = response.data;
        setTimeout(() => {
          this.facturas = response.data.facturas;
          this.visible = response.data.visible;
        }, 900);

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  }
})

viewModelOrdenCompraDetalle.$mount('#appOrdenDetalle');
