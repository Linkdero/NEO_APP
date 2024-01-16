//import { EventBus } from './eventBus.js';
import { eBus } from './appTransporte.js';

//import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
const transporteheader = httpVueLoader('./transportes/src/components/TransporteHeader.vue');
const solicituddetalle = httpVueLoader('./transportes/src/components/SolicitudDetalle.vue');
const solicitudvehiculos = httpVueLoader('./transportes/src/components/SolicitudVehiculos.vue');
const transportelist = httpVueLoader('./transportes/src/components/SolicitudDetalleList.vue');
/*const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');*/

  var modelSolicitudDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      asignacion_id_:$('#asignacion_id_').val(),
      nombremodal:$('#nombremodal_').val(),
      vista:$('#vista_').val(),
      privilegio:JSON.parse($('#privilegio_').val()),
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
      this.getAsignacion();
      eventBus.$on('recargarAsignacion', () => {
        this.getAsignacion();
      })
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
      },
      getAsignacion: function(){
        //inicio
        axios.get('transportes/model/Asignaciones', {
          params: {
            opcion:6,
            asignacion_id: this.asignacion_id_
            /*tipo:0,
            year:2023,
            filtro:''//this.filtro*/
          }
        }).then(function (response) {
          this.asignacion = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
        //fin
      }
    },
    components: {
      transporteheader, solicituddetalle, solicitudvehiculos, transportelist
      //viaticodetalle, privilegios, estadonom
      //viaticoheaders,viaticodetalle,viaticoempleados
    }
  });

  modelSolicitudDetalle.$mount('#asigdetalle');

//})
