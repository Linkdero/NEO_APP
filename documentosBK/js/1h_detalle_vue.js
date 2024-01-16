var eventBusPD = new Vue();
// seguimiento
//$(document).ready(function(){
var instanciaPD;


//formulario detalle
  viewModelFormularioDetalle = new Vue({
      data: {
        envioTra:$('#env_tra').val(),
        formulario:$('#formulario').val(),
        f1h:0,
        opcion: 1
      },
      mounted(){
        instanciaPD = this;
      },

      computed: {
        itemsWithSubTotal() {
          return this.insumos.map(item => ({
            item,
            subtotal: this.computeSubTotal(item)
          }))
        }
      },
      created: function(){

      },

      methods: {
        getOpcion: function(opc){
          this.opcion = opc;
        },
        get1hchild: function(data) {
          console.log('Data from component 1H', data);
          this.f1h = data;

    		},
      }
    })

    viewModelFormularioDetalle.$mount('#app_formulario_detalle');

//instanciaPD = viewModelFormularioDetalle;

//instanciaPD.proveedorFiltrado();

//})
