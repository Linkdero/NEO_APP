import { EventBus } from './eventBus.js';

  var modelBienVerificacion = new Vue({
    //el: '#vdetalle',
    data: {
      bien_id:$('#bien_id').val(),
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      viatico:"",
      loading:false
    },
    destroyed: function(){
      this.modelBienVerificacion;
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
    },
    components: {

    }
  });

  modelBienVerificacion.$mount('#bienverificacion');

  intanciaD = modelBienVerificacion;




//})
