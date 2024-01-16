import { EventBus } from './eventBus.js';

import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
const viaticodetalle = httpVueLoader('./viaticos/js/components/ViaticoDetalle.vue');
const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');

  var modelViaticoDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      id_viatico:$('#id_viatico').val(),
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      viatico:"",
      loading:false
    },
    destroyed: function(){
      this.modelViaticoDetalle;
    },
    mounted() {
      //this.$refs.infoBox.focus();
    },
    beforeDestroy() {
      window.removeEventListener('scroll', this.onScroll)
    },
    beforeCreate: function(){
      //alert('cargando');
      $('#data_').html('<div class="loaderr"></div>');
    },
    created: function(){
      this.evento = EventBus;
      EventBus.$on('getOpcion', (data) => {
        this.option = data;
        if(data == 2){
          this.keyReload ++;
        }
      });
      EventBus.$on('destroyInstance', () => {
        //alert('jaldfjñlajsdlfk');
        this.$destroy();
      });
    },
    methods:{
      setOption: function(opc){
        this.option = opc;

      },
      getPrivilegios: function(data){
        this.privilegio = data;
      },
      getEstadoV: function(data){
        this.estado = data;
      },
      getViaticoDetalle: function(data){
        this.viatico = data;
        setTimeout(() => {
          if(data.nombramiento > 1){
            this.loading = true;
          }
        }, 0);

      },
      getDetalleFactura: function(data){
        console.log('datos 2: '+data);
        EventBus.$emit('mostrarFacturaDetalle',data);
      },
    },
    components: {
      //viaticodetalle, privilegios, estadonom
      viaticoheaders,viaticodetalle,viaticoempleados
    }
  });

  modelViaticoDetalle.$mount('#vdetalle');

  intanciaD = modelViaticoDetalle;




//})
