/*import { EventBus } from './eventBus.js';
import { privilegios } from './components/GlobalComponents.js';
const bienheader = httpVueLoader('./inventario/src/components/BienHeader.vue');
const biendetalle = httpVueLoader('./inventario/src/components/BienDetalle.vue');*/

  var modelBienDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      sicoin_code:'00190871',//$('#sicoin_code').val(),
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      viatico:"",
      loading:false,
      bienDetalle:""
    },
    destroyed: function(){
      this.modelBienDetalle;
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
      this.getBienWS();
    },
    methods:{
      setOption: function(opc){
        this.option = opc;

      },
      getPrivilegios: function(data){
        this.privilegio = data;
      },
      getBienWS: function(){
        axios.get('http://127.0.0.1:8181/wsinventario/bien', {

          params:
          {
            code:this.sicoin_code
          }
        })
        .then(function (response) {
          this.bienDetalle = response.data;
        }.bind(this))
        .catch(function (error) {
          console.log(error);
        });
      }

    },
    components: {
      //bienheader, biendetalle, privilegios
    }
  });

  modelBienDetalle.$mount('#biendetalle');



//})
