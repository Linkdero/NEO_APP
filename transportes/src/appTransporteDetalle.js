//import { EventBus } from './eventBus.js';
import { eBus } from './appTransporte.js';

//import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
const transporteheader = httpVueLoader('./transportes/src/components/TransporteHeader.vue');
const solicituddetalle = httpVueLoader('./transportes/src/components/SolicitudDetalle.vue');
const solicitudvehiculos = httpVueLoader('./transportes/src/components/SolicitudVehiculos.vue');
/*const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');*/

  var modelSolicitudDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      solicitud_id:$('#solicitud_id').val(),
      nombremodal:$('#nombremodal').val(),
      vista:$('#vista').val(),
      privilegio:JSON.parse($('#privilegio').val()),
      tipo:1,
      option:1,
      estado:"",
      evento:"",
      keyReload:0,
      asignacion:""
    },
    destroyed: function(){
      //this.modelSolicitudDetalle;
    },
    mounted() {
      //this.$refs.infoBox.focus();
    },
    beforeDestroy() {
      window.removeEventListener('scroll', this.onScroll)
    },
    beforeCreate: function(){
      //alert('cargando');
      console.log(this.privilegio);
      $('#data_').html('<div class="loaderr"></div>');
    },
    created: function(){
      this.evento = eBus;
      eBus.$on('getOpcion', (data) => {
        this.option = data;
        console.log(data);
        if(data == 2){
          this.keyReload ++;
        }
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
      }
    },
    components: {
      transporteheader, solicituddetalle, solicitudvehiculos
      //viaticodetalle, privilegios, estadonom
      //viaticoheaders,viaticodetalle,viaticoempleados
    }
  });

  modelSolicitudDetalle.$mount('#stdetalle');

//})
