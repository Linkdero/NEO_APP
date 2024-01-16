var eventBusPACD = new Vue();
// seguimiento
//$(document).ready(function(){



//formulario detalle
  viewModelPACDetalle = new Vue({
      data: {
        pac_id:$('#pac_id').val(),
        //formulario:$('#formulario').val(),
        //f1h:0,
        pac:"",
        opcion: 1
      },
      mounted(){
        //instanciaPD = this;
      },

      computed: {
        /*itemsWithSubTotal() {
          return this.insumos.map(item => ({
            item,
            subtotal: this.computeSubTotal(item)
          }))
        }*/
      },
      created: function(){

      },

      methods: {
        getOpcion: function(opc){
          this.opcion = opc;
        },
        getPacD: function(data) {
          console.log('Data from component PAC', data);
          this.pac = data;

    		},
      }
    })

    viewModelPACDetalle.$mount('#app_pac_detalle');

//instanciaPD = viewModelPACDetalle;

//instanciaPD.proveedorFiltrado();

//})
